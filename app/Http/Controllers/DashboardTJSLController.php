<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardTJSLController extends Controller
{
    public function index(Request $request)
{
    $currentYear = date('Y');
    $selectedYear = $request->get('tahun', $currentYear); // default tahun saat ini

    // Ambil semua tahun tersedia dari data
    $availableYears = DB::table('tb_program_tjsl')
        ->selectRaw('YEAR(tgl_selesai) as tahun')
        ->whereNotNull('tgl_selesai')
        ->groupBy(DB::raw('YEAR(tgl_selesai)'))
        ->orderBy('tahun', 'desc')
        ->pluck('tahun');

    // Base query (gunakan filter tahun jika dipilih)
    $query = DB::table('tb_program_tjsl');

    // Jika bukan "Semua Tahun" (nilai kosong), filter berdasarkan tahun
    if ($request->has('tahun') && $request->get('tahun') != '') {
        $query->whereYear('tgl_selesai', $selectedYear);
    }

    // 1️⃣ Jumlah Program
    $jumlah_program = (clone $query)->count();

    // 2️⃣ Total Realisasi
    $total_realisasi = (clone $query)->sum('realisasi');

    // 3️⃣ Total Penerima
    $total_penerima = (clone $query)->sum('penerima');

    // 4️⃣ Status Donut
    $status_donut = (clone $query)
        ->select('status', DB::raw('COUNT(*) as total'))
        ->groupBy('status')
        ->get();

    // 5️⃣ Status Anggaran Donut
    $status_anggaran = (clone $query)
        ->select('status', DB::raw('SUM(anggaran) as total_anggaran'))
        ->groupBy('status')
        ->get();

    // 6️⃣ Bar Chart: Realisasi per bulan per Pilar
    $realisasi_perbulan = (clone $query)
        ->selectRaw('MONTH(tgl_selesai) as bulan, pilar, SUM(realisasi) as total')
        ->whereNotNull('pilar')
        ->groupBy(DB::raw('MONTH(tgl_selesai)'), 'pilar')
        ->orderBy('bulan')
        ->get();

    // 7️⃣ Donut Chart: Jumlah Program per Regional
    $program_per_regional = (clone $query)
        ->select('regional', DB::raw('COUNT(*) as total'))
        ->groupBy('regional')
        ->get();

    // 8️⃣ Tabel Berdasarkan Pilar
    $tabel_pilar = (clone $query)
        ->select('pilar',
            DB::raw('COUNT(*) as jumlah_program'),
            DB::raw('SUM(realisasi) as total_realisasi')
        )
        ->groupBy('pilar')
        ->get();

    // Tambah total baris di tabel pilar
    $tabel_pilar->push((object)[
        'pilar' => 'Total',
        'jumlah_program' => $tabel_pilar->sum('jumlah_program'),
        'total_realisasi' => $tabel_pilar->sum('total_realisasi'),
    ]);

    // 9️⃣ Tabel Berdasarkan Provinsi
    $tabel_provinsi = (clone $query)
        ->join('wilayah as w', 'tb_program_tjsl.lokasi_program', '=', 'w.kode')
        ->selectRaw('LEFT(w.kode, 2) as kode_provinsi, COUNT(*) as jumlah_program')
        ->groupBy(DB::raw('LEFT(w.kode, 2)'))
        ->orderBy(DB::raw('LEFT(w.kode, 2)'))
        ->get()
        ->map(function ($item) {
            $prov = DB::table('wilayah')->where('kode', substr($item->kode_provinsi, 0, 2))->first();
            $item->provinsi = $prov->nama ?? '-';
            return $item;
        });

    // Tambahkan total
    $tabel_provinsi->push((object)[
        'kode_provinsi' => null,
        'provinsi' => 'Total',
        'jumlah_program' => $tabel_provinsi->sum('jumlah_program'),
    ]);

    // Data Employee Participation (TIDAK DIFILTER)
    $currentYear = date('Y');
    $years = [intval($currentYear) - 2, intval($currentYear) - 1, intval($currentYear)];

    $employee_participation = DB::table('tb_program_tjsl')
        ->selectRaw('YEAR(tgl_selesai) as tahun, pilar, SUM(employee) as total_employee')
        ->whereBetween(DB::raw('YEAR(tgl_selesai)'), [$years[0], $years[2]])
        ->groupBy(DB::raw('YEAR(tgl_selesai)'), 'pilar')
        ->orderBy('tahun')
        ->get();

    $pilar_list = $employee_participation->pluck('pilar')->unique()->values();

    return view('programs.tjsl', compact(
        'jumlah_program', 'total_realisasi', 'total_penerima',
        'status_donut', 'status_anggaran', 'realisasi_perbulan',
        'program_per_regional', 'tabel_pilar', 'tabel_provinsi',
        'employee_participation', 'years', 'pilar_list',
        'availableYears', 'selectedYear'
    ));
}

}
