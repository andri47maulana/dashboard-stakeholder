<?php

namespace App\Http\Controllers;

use App\Models\AnggaranRegional;
use App\Models\BiayaTjsl;
use App\Models\SubPilar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tjsl;
use App\Models\Unit;

class MonitoringBiayaController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $subPilarId = $request->input('sub_pilar_id');
        $regionFilter = $request->input('region');

        // Agregasi anggaran per tahun & sub_pilar (dengan filter)
        $anggaranQuery = AnggaranRegional::select(
                'tahun',
                'regional_id',
                'sub_pilar_id',
                DB::raw('SUM(anggaran) as anggaran_total')
            )
            ->groupBy('tahun', 'regional_id', 'sub_pilar_id');

        if (!empty($tahun)) {
            $anggaranQuery->where('tahun', $tahun);
        }
        if (!empty($subPilarId)) {
            $anggaranQuery->where('sub_pilar_id', $subPilarId);
        }
        // Terapkan filter regional ke anggaran (ambil digit terakhir dari string region)
        if (!empty($regionFilter)) {
            $selectedRegionId = substr(trim((string)$regionFilter), -1);
            $anggaranQuery->where('regional_id', $selectedRegionId);
        }

        $anggaran = $anggaranQuery->get();

        // Agregasi realisasi per tahun (YEAR(tanggal_mulai)) & sub_pilar (dengan filter)
        $realisasiQuery = BiayaTjsl::select(
                DB::raw('YEAR(tb_tjsl.tanggal_mulai) as tahun'),
                'tb_biaya_tjsl.sub_pilar_id',
                DB::raw('SUM(tb_biaya_tjsl.realisasi) as realisasi_total'),
                DB::raw('units.region as region')
            )
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy(DB::raw('YEAR(tb_tjsl.tanggal_mulai)'), 'tb_biaya_tjsl.sub_pilar_id', 'units.region');

        if (!empty($tahun)) {
            $realisasiQuery->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        }
        if (!empty($subPilarId)) {
            $realisasiQuery->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        }
        // Filter opsional berdasarkan region (full string, contoh: "Regional 5")
        if (!empty($regionFilter)) {
            $realisasiQuery->where('units.region', $regionFilter);
        }

        $realisasi = $realisasiQuery->get();

        // Gabungkan kedua agregasi berdasarkan (tahun, sub_pilar_id, regional_id)
        $rows = [];
        foreach ($anggaran as $a) {
            $key = (string)$a->tahun . '-' . (int)$a->sub_pilar_id . '-' . (string)$a->regional_id;
            $rows[$key] = [
                'tahun' => (string)$a->tahun,
                'regional_id' => (string)$a->regional_id,
                'sub_pilar_id' => (int)$a->sub_pilar_id,
                'anggaran_total' => (float)$a->anggaran_total,
                'realisasi_total' => 0.0,
            ];
        }

        foreach ($realisasi as $r) {
            $tahunKey = (string)$r->tahun;
            // Ambil 1 karakter terakhir dari field region (contoh: "Regional 8" -> "8")
            $regionId = $r->region !== null ? substr(trim((string)$r->region), -1) : null;

            $key = $tahunKey . '-' . (int)$r->sub_pilar_id . '-' . (string)$regionId;
            if (!isset($rows[$key])) {
                $rows[$key] = [
                    'tahun' => $tahunKey,
                    'regional_id' => (string)$regionId,
                    'sub_pilar_id' => (int)$r->sub_pilar_id,
                    'anggaran_total' => 0.0,
                    'realisasi_total' => 0.0,
                ];
            }
            $rows[$key]['realisasi_total'] = (float)$r->realisasi_total;
        }

        $subPilarMap = SubPilar::pluck('sub_pilar', 'id');

        $dataset = collect($rows)
            ->map(function ($row) use ($subPilarMap) {
                $row['sub_pilar_name'] = $subPilarMap[$row['sub_pilar_id']] ?? '-';
                $row['regional_name'] = $row['regional_id'] ? ('Regional ' . $row['regional_id']) : '-';
                return $row;
            })
            ->sortBy([['tahun', 'desc'], ['regional_id', 'asc'], ['sub_pilar_id', 'asc']])
            ->values();

        // Opsi filter list region untuk dropdown
        $regions = Unit::whereNotNull('region')
            ->select('region')
            ->distinct()
            ->orderBy('region')
            ->pluck('region');

        // Opsi filter: tahun (dari anggaran & YEAR(tanggal_mulai)) dan sub pilar
        $yearsAnggaran = AnggaranRegional::distinct()->pluck('tahun')->toArray();
        $yearsRealisasi = Tjsl::whereNotNull('tanggal_mulai')
            ->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->pluck('tahun')
            ->toArray();
        $years = collect($yearsAnggaran)->merge($yearsRealisasi)->unique()->sort()->values()->all();

        // Urutkan Sub Pilar numerik by id (menghindari urut string)
        $subPilars = SubPilar::orderByRaw('CAST(id AS UNSIGNED)')->get();

        return view('monitoringbiaya.index', compact('dataset', 'years', 'subPilars', 'regions'));
    }
}
