<?php

namespace App\Http\Controllers;

use App\Models\Tjsl;
use App\Models\BiayaTjsl;
use App\Models\Pilar;
use App\Models\Unit;
use App\Models\Wilayah;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardTjslController extends Controller
{
    public function index(Request $request)
    {
        // Kartu ringkas
        $programCount = Tjsl::count();
        $actualSpend = (float) BiayaTjsl::sum('realisasi');

        // Sum 'penerima_dampak' jika numerik (fallback 0 jika string)
        $penerimaManfaat = (int) DB::table('tb_tjsl')
            ->selectRaw('SUM(CASE WHEN penerima_dampak REGEXP "^[0-9]+$" THEN penerima_dampak ELSE 0 END) as total')
            ->value('total');

        // Status: 1=Proposed, 2=Active, 3=Completed
        $statusRaw = Tjsl::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')->get()->pluck('total', 'status')->toArray();
        $statusSummary = [
            'proposed' => (int) ($statusRaw[1] ?? 0),
            'active' => (int) ($statusRaw[2] ?? 0),
            'completed' => (int) ($statusRaw[3] ?? 0),
        ];

        // Rekap per pilar (nama pilar) - tampilkan semua pilar dengan default 0
        $pilarMap = Pilar::pluck('pilar', 'id'); // [id => nama]
        $tjslByPilar = Tjsl::select('pilar_id', DB::raw('COUNT(*) as total'))
            ->groupBy('pilar_id')->get()
            ->pluck('total', 'pilar_id')->toArray(); // [pilar_id => total]

        $byPilar = collect($pilarMap)->map(function ($pilarName, $pilarId) use ($tjslByPilar) {
            return [
                'pilar' => $pilarName,
                'total' => (int) ($tjslByPilar[$pilarId] ?? 0),
            ];
        })->values();

        // Rekap per region dari unit
        $byRegion = Tjsl::select('units.region', DB::raw('COUNT(tb_tjsl.id) as total'))
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->groupBy('units.region')
            ->orderBy('units.region')
            ->get()
            ->map(function ($row) {
                return [
                    'region' => $row->region ?? 'Tidak diketahui',
                    'total' => (int) $row->total,
                ];
            });

        // Rekap per provinsi: tampilkan semua provinsi dengan default 0 + pagination
        $allProvinces = Wilayah::whereRaw('LENGTH(kode) = 2')->pluck('nama', 'kode'); // Ambil semua provinsi

        $lokasiList = Tjsl::whereNotNull('lokasi_program')->pluck('lokasi_program');
        $provCounter = [];
        foreach ($lokasiList as $lok) {
            $provCode = null;
            if (strpos($lok, '.') !== false) {
                $provCode = explode('.', $lok)[0]; // ambil "NN"
            }
            if ($provCode && isset($allProvinces[$provCode])) {
                $provName = $allProvinces[$provCode];
                $provCounter[$provName] = ($provCounter[$provName] ?? 0) + 1;
            }
        }

        // Buat array untuk semua provinsi dengan default 0
        $allProvincesData = collect($allProvinces)->map(function ($provName, $provCode) use ($provCounter) {
            return [
                'provinsi' => $provName,
                'total' => (int) ($provCounter[$provName] ?? 0)
            ];
        })->sortByDesc('total')->values();

        // Pagination untuk provinsi
        $provincePage = (int) $request->get('province_page', 1);
        $perPage = 5;
        $offset = ($provincePage - 1) * $perPage;

        // Pagination data
        $totalProvinces = $allProvincesData->count();
        $totalPages = ceil($totalProvinces / $perPage);
        $byProvince = $allProvincesData->slice($offset, $perPage)->values();
        $totalProgramsInProvinces = $allProvincesData->sum('total');

        // Actual Spend by Pilar per bulan (berdasar tanggal_mulai)
        $spendByPilarPerMonth = BiayaTjsl::selectRaw('MONTH(tanggal_mulai) as bulan, tb_tjsl.pilar_id, SUM(tb_biaya_tjsl.realisasi) as total')
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy('bulan', 'tb_tjsl.pilar_id')
            ->orderBy('bulan')
            ->get()
            ->groupBy('bulan')
            ->map(function ($rows, $bulan) use ($pilarMap) {
                $data = [];
                foreach ($rows as $r) {
                    $name = $pilarMap[$r->pilar_id] ?? 'Lainnya';
                    $data[$name] = (float) $r->total;
                }
                return ['bulan' => (int) $bulan, 'data' => $data];
            })->values();

        // Partisipasi pegawai (proxy: jumlah program per pilar per tahun)
        $participation = Tjsl::selectRaw('YEAR(tanggal_mulai) as tahun, pilar_id, COUNT(*) as total')
            ->whereNotNull('tanggal_mulai')
            ->groupBy('tahun', 'pilar_id')
            ->orderBy('tahun')
            ->get()
            ->groupBy('tahun')
            ->map(function ($rows, $tahun) use ($pilarMap) {
                $data = [];
                foreach ($rows as $r) {
                    $name = $pilarMap[$r->pilar_id] ?? 'Lainnya';
                    $data[$name] = (int) $r->total;
                }
                return ['tahun' => (int) $tahun, 'data' => $data];
            })->values();

        return view('tjsl.dashboard', compact(
            'programCount',
            'actualSpend',
            'penerimaManfaat',
            'statusSummary',
            'byPilar',
            'byRegion',
            'byProvince',
            'totalProvinces',
            'totalProgramsInProvinces',
            'provincePage',
            'totalPages',
            'spendByPilarPerMonth',
            'participation'
        ));
    }

    public function getProvinceData(Request $request)
    {
        $provincePage = (int) $request->get('page', 1);
        $perPage = 5;
        $offset = ($provincePage - 1) * $perPage;

        // Ambil data provinsi (sama seperti di index)
        $allProvinces = Wilayah::whereRaw('LENGTH(kode) = 2')->pluck('nama', 'kode');

        $lokasiList = Tjsl::whereNotNull('lokasi_program')->pluck('lokasi_program');
        $provCounter = [];
        foreach ($lokasiList as $lok) {
            $provCode = null;
            if (strpos($lok, '.') !== false) {
                $provCode = explode('.', $lok)[0];
            }
            if ($provCode && isset($allProvinces[$provCode])) {
                $provName = $allProvinces[$provCode];
                $provCounter[$provName] = ($provCounter[$provName] ?? 0) + 1;
            }
        }

        $allProvincesData = collect($allProvinces)->map(function ($provName, $provCode) use ($provCounter) {
            return [
                'provinsi' => $provName,
                'total' => (int) ($provCounter[$provName] ?? 0)
            ];
        })->sortByDesc('total')->values();

        $totalProvinces = $allProvincesData->count();
        $totalPages = ceil($totalProvinces / $perPage);
        $byProvince = $allProvincesData->slice($offset, $perPage)->values();
        $totalProgramsInProvinces = $allProvincesData->sum('total');

        return response()->json([
            'byProvince' => $byProvince,
            'provincePage' => $provincePage,
            'totalPages' => $totalPages,
            'totalProvinces' => $totalProvinces,
            'totalProgramsInProvinces' => $totalProgramsInProvinces,
            'rangeStart' => ($provincePage - 1) * $perPage + 1,
            'rangeEnd' => min($provincePage * $perPage, $totalProvinces)
        ]);
    }
}
