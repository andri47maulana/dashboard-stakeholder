<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\BiayaTjsl;
use App\Models\SubPilar;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Tjsl;

class MonitoringBiayaController extends Controller
{
    public function index(Request $request)
    {
        $tahun = $request->input('tahun');
        $subPilarId = $request->input('sub_pilar_id');

        // Agregasi anggaran per tahun & sub_pilar (dengan filter)
        $anggaranQuery = Anggaran::select(
                'tahun',
                'sub_pilar_id',
                DB::raw('SUM(anggaran) as anggaran_total')
            )
            ->groupBy('tahun', 'sub_pilar_id');

        if (!empty($tahun)) {
            $anggaranQuery->where('tahun', $tahun);
        }
        if (!empty($subPilarId)) {
            $anggaranQuery->where('sub_pilar_id', $subPilarId);
        }

        $anggaran = $anggaranQuery->get();

        // Agregasi realisasi per tahun (YEAR(tanggal_mulai)) & sub_pilar (dengan filter)
        $realisasiQuery = BiayaTjsl::select(
                DB::raw('YEAR(tb_tjsl.tanggal_mulai) as tahun'),
                'tb_biaya_tjsl.sub_pilar_id',
                DB::raw('SUM(tb_biaya_tjsl.realisasi) as realisasi_total')
            )
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy(DB::raw('YEAR(tb_tjsl.tanggal_mulai)'), 'tb_biaya_tjsl.sub_pilar_id');

        if (!empty($tahun)) {
            $realisasiQuery->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        }
        if (!empty($subPilarId)) {
            $realisasiQuery->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        }

        $realisasi = $realisasiQuery->get();

        // Gabungkan kedua agregasi berdasarkan (tahun, sub_pilar_id)
        $rows = [];
        foreach ($anggaran as $a) {
            $key = (string)$a->tahun . '-' . (int)$a->sub_pilar_id;
            $rows[$key] = [
                'tahun' => (string)$a->tahun,
                'sub_pilar_id' => (int)$a->sub_pilar_id,
                'anggaran_total' => (float)$a->anggaran_total,
                'realisasi_total' => 0.0,
            ];
        }

        foreach ($realisasi as $r) {
            $tahunKey = (string)$r->tahun;
            $key = $tahunKey . '-' . (int)$r->sub_pilar_id;
            if (!isset($rows[$key])) {
                $rows[$key] = [
                    'tahun' => $tahunKey,
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
                return $row;
            })
            ->sortBy([['tahun', 'desc'], ['sub_pilar_id', 'asc']])
            ->values();

        // Opsi filter: tahun (dari anggaran & YEAR(tanggal_mulai)) dan sub pilar
        $yearsAnggaran = Anggaran::distinct()->pluck('tahun')->toArray();
        $yearsRealisasi = Tjsl::whereNotNull('tanggal_mulai')
            ->selectRaw('YEAR(tanggal_mulai) as tahun')
            ->distinct()
            ->pluck('tahun')
            ->toArray();
        $years = collect($yearsAnggaran)->merge($yearsRealisasi)->unique()->sort()->values()->all();

        // Urutkan Sub Pilar numerik by id (menghindari urut string)
        $subPilars = SubPilar::orderByRaw('CAST(id AS UNSIGNED)')->get();

        return view('monitoringbiaya.index', compact('dataset', 'years', 'subPilars'));
    }
}
