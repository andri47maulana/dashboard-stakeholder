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

        // Summary cards: total anggaran
        $anggaranSummary = AnggaranRegional::query();
        if (!empty($tahun)) {
            $anggaranSummary->where('tahun', $tahun);
        }
        if (!empty($subPilarId)) {
            $anggaranSummary->where('sub_pilar_id', $subPilarId);
        }
        if (!empty($regionFilter)) {
            $selectedRegionId = substr(trim((string) $regionFilter), -1);
            $anggaranSummary->where('regional_id', $selectedRegionId);
        }
        $totalAnggaran = (float) $anggaranSummary->sum('anggaran');

        // Summary cards: total realisasi
        $realisasiSummary = BiayaTjsl::join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai');
        if (!empty($tahun)) {
            $realisasiSummary->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        }
        if (!empty($subPilarId)) {
            $realisasiSummary->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        }
        if (!empty($regionFilter)) {
            $realisasiSummary->where('units.region', $regionFilter);
        }
        $totalRealisasi = (float) $realisasiSummary->sum('tb_biaya_tjsl.realisasi');

        $pctSummary = $totalAnggaran > 0 ? ($totalRealisasi / $totalAnggaran) * 100 : null;

        // Rekap per Sub Pilar
        $angBySub = AnggaranRegional::select(
                'sub_pilar_id',
                DB::raw('SUM(anggaran) as anggaran_total')
            )->groupBy('sub_pilar_id');
        if (!empty($tahun)) $angBySub->where('tahun', $tahun);
        if (!empty($subPilarId)) $angBySub->where('sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $angBySub->where('regional_id', substr(trim((string) $regionFilter), -1));
        $angBySub = $angBySub->get()->keyBy('sub_pilar_id');

        $realBySub = BiayaTjsl::select(
                'tb_biaya_tjsl.sub_pilar_id',
                DB::raw('SUM(tb_biaya_tjsl.realisasi) as realisasi_total')
            )
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy('tb_biaya_tjsl.sub_pilar_id');
        if (!empty($tahun)) $realBySub->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        if (!empty($subPilarId)) $realBySub->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $realBySub->where('units.region', $regionFilter);
        $realBySub = $realBySub->get()->keyBy('sub_pilar_id');

        $pilarMap = SubPilar::pluck('sub_pilar', 'id');
        $datasetSubPilar = collect($pilarMap)->keys()->map(function ($id) use ($angBySub, $realBySub, $pilarMap) {
            $a = (float) ($angBySub[$id]->anggaran_total ?? 0);
            $r = (float) ($realBySub[$id]->realisasi_total ?? 0);
            return [
                'sub_pilar_id' => (int) $id,
                'sub_pilar_name' => $pilarMap[$id] ?? '-',
                'anggaran_total' => $a,
                'realisasi_total' => $r,
                'pct' => $a > 0 ? ($r / $a) * 100 : null,
            ];
        })->sortBy('sub_pilar_id')->values();

        // Rekap per Regional
        $angByRegion = AnggaranRegional::select(
                'regional_id',
                DB::raw('SUM(anggaran) as anggaran_total')
            )
            ->groupBy('regional_id');
        if (!empty($tahun)) $angByRegion->where('tahun', $tahun);
        if (!empty($subPilarId)) $angByRegion->where('sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $angByRegion->where('regional_id', substr(trim((string) $regionFilter), -1));
        $angByRegion = $angByRegion->get()->keyBy('regional_id');

        $realByRegion = BiayaTjsl::select(
                DB::raw('units.region as region'),
                DB::raw('SUM(tb_biaya_tjsl.realisasi) as realisasi_total')
            )
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy('units.region');
        if (!empty($tahun)) $realByRegion->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        if (!empty($subPilarId)) $realByRegion->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $realByRegion->where('units.region', $regionFilter);
        $realByRegion = $realByRegion->get();

        $datasetRegion = $realByRegion->map(function ($row) use ($angByRegion) {
            $rid = $row->region !== null ? substr(trim((string) $row->region), -1) : null;
            $a = (float) ($angByRegion[$rid]->anggaran_total ?? 0);
            $r = (float) ($row->realisasi_total ?? 0);
            return [
                'regional_id' => (string) $rid,
                'regional_name' => $rid ? ('Regional ' . $rid) : '-',
                'anggaran_total' => $a,
                'realisasi_total' => $r,
                'pct' => $a > 0 ? ($r / $a) * 100 : null,
            ];
        })->sortBy('regional_id')->values();

        // Dropdowns
        $regions = Unit::whereNotNull('region')
            ->select('region')->distinct()->orderBy('region')->pluck('region');

        $yearsAnggaran = AnggaranRegional::distinct()->pluck('tahun')->toArray();
        $yearsRealisasi = Tjsl::whereNotNull('tanggal_mulai')->selectRaw('YEAR(tanggal_mulai) as tahun')->distinct()->pluck('tahun')->toArray();
        $years = collect($yearsAnggaran)->merge($yearsRealisasi)->unique()->sort()->values()->all();

        $subPilars = SubPilar::orderByRaw('CAST(id AS UNSIGNED)')->get();

        return view('monitoringbiaya.index', compact('dataset', 'years', 'subPilars', 'regions'));
    }
    public function dashboard(Request $request)
    {
        $tahun = $request->input('tahun');
        $subPilarId = $request->input('sub_pilar_id');
        $regionFilter = $request->input('region');

        // Summary: total anggaran
        $anggaranSummary = AnggaranRegional::query();
        if (!empty($tahun)) $anggaranSummary->where('tahun', $tahun);
        if (!empty($subPilarId)) $anggaranSummary->where('sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $anggaranSummary->where('regional_id', substr(trim((string)$regionFilter), -1));
        $totalAnggaran = (float) $anggaranSummary->sum('anggaran');

        // Summary: total realisasi
        $realisasiSummary = BiayaTjsl::join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai');
        if (!empty($tahun)) $realisasiSummary->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        if (!empty($subPilarId)) $realisasiSummary->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $realisasiSummary->where('units.region', $regionFilter);
        $totalRealisasi = (float) $realisasiSummary->sum('tb_biaya_tjsl.realisasi');

        $pctSummary = $totalAnggaran > 0 ? ($totalRealisasi / $totalAnggaran) * 100 : null;

        // Rekap per Sub Pilar
        $angBySub = AnggaranRegional::select('sub_pilar_id', DB::raw('SUM(anggaran) as anggaran_total'))
            ->groupBy('sub_pilar_id');
        if (!empty($tahun)) $angBySub->where('tahun', $tahun);
        if (!empty($subPilarId)) $angBySub->where('sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $angBySub->where('regional_id', substr(trim((string)$regionFilter), -1));
        $angBySub = $angBySub->get()->keyBy('sub_pilar_id');

        $realBySub = BiayaTjsl::select('tb_biaya_tjsl.sub_pilar_id', DB::raw('SUM(tb_biaya_tjsl.realisasi) as realisasi_total'))
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy('tb_biaya_tjsl.sub_pilar_id');
        if (!empty($tahun)) $realBySub->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        if (!empty($subPilarId)) $realBySub->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $realBySub->where('units.region', $regionFilter);
        $realBySub = $realBySub->get()->keyBy('sub_pilar_id');

        $pilarMap = SubPilar::pluck('sub_pilar', 'id');
        $datasetSubPilar = collect($pilarMap)->keys()->map(function ($id) use ($angBySub, $realBySub, $pilarMap) {
            $a = (float) ($angBySub[$id]->anggaran_total ?? 0);
            $r = (float) ($realBySub[$id]->realisasi_total ?? 0);
            return [
                'sub_pilar_id' => (int) $id,
                'sub_pilar_name' => $pilarMap[$id] ?? '-',
                'anggaran_total' => $a,
                'realisasi_total' => $r,
                'pct' => $a > 0 ? ($r / $a) * 100 : null,
            ];
        })->sortBy('sub_pilar_id')->values();

        // Rekap per Regional
        $angByRegion = AnggaranRegional::select('regional_id', DB::raw('SUM(anggaran) as anggaran_total'))
            ->groupBy('regional_id');
        if (!empty($tahun)) $angByRegion->where('tahun', $tahun);
        if (!empty($subPilarId)) $angByRegion->where('sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $angByRegion->where('regional_id', substr(trim((string)$regionFilter), -1));
        $angByRegion = $angByRegion->get()->keyBy('regional_id');

        $realByRegion = BiayaTjsl::select(DB::raw('units.region as region'), DB::raw('SUM(tb_biaya_tjsl.realisasi) as realisasi_total'))
            ->join('tb_tjsl', 'tb_tjsl.id', '=', 'tb_biaya_tjsl.tjsl_id')
            ->join('tb_unit as units', 'units.id', '=', 'tb_tjsl.unit_id')
            ->whereNotNull('tb_tjsl.tanggal_mulai')
            ->groupBy('units.region');
        if (!empty($tahun)) $realByRegion->whereYear('tb_tjsl.tanggal_mulai', $tahun);
        if (!empty($subPilarId)) $realByRegion->where('tb_biaya_tjsl.sub_pilar_id', $subPilarId);
        if (!empty($regionFilter)) $realByRegion->where('units.region', $regionFilter);
        $realByRegion = $realByRegion->get();

        // Key realisasi per regional_id (ambil digit angka dari units.region, aman untuk multi-digit/leading zero)
        $realKeyed = $realByRegion->mapWithKeys(function ($row) {
            $regionStr = (string) $row->region;
            preg_match('/(\d+)/', $regionStr, $m);
            $rid = isset($m[1]) ? ltrim($m[1], '0') : null; // '07' -> '7'
            $rid = $rid === '' ? '0' : $rid;
            return $rid !== null ? [$rid => (float) ($row->realisasi_total ?? 0)] : [];
        });

        // Union semua regional_id dari anggaran dan realisasi, sort numerik
        $allRegionIds = collect(array_keys($angByRegion->toArray()))
            ->merge(collect(array_keys($realKeyed->toArray())))
            ->unique()
            ->sort(function ($a, $b) { return (int)$a <=> (int)$b; })
            ->values();

        $datasetRegion = $allRegionIds->map(function ($rid) use ($angByRegion, $realKeyed) {
            $a = (float) ($angByRegion[(string)$rid]->anggaran_total ?? 0);
            $r = (float) ($realKeyed[(string)$rid] ?? 0);
            return [
                'regional_id' => (string) $rid,
                'regional_name' => $rid ? ('Regional ' . $rid) : '-',
                'anggaran_total' => $a,
                'realisasi_total' => $r,
                'pct' => $a > 0 ? ($r / $a) * 100 : null,
            ];
        })->sortBy('regional_id')->values();

        // Dropdowns
        $regions = Unit::whereNotNull('region')
            ->select('region')->distinct()->orderBy('region')->pluck('region');

        $yearsAnggaran = AnggaranRegional::distinct()->pluck('tahun')->toArray();
        $yearsRealisasi = Tjsl::whereNotNull('tanggal_mulai')->selectRaw('YEAR(tanggal_mulai) as tahun')->distinct()->pluck('tahun')->toArray();
        $years = collect($yearsAnggaran)->merge($yearsRealisasi)->unique()->sort()->values()->all();

        $subPilars = SubPilar::orderByRaw('CAST(id AS UNSIGNED)')->get();

        return view('monitoringbiaya.dashboard', compact(
            'totalAnggaran',
            'totalRealisasi',
            'pctSummary',
            'datasetSubPilar',
            'datasetRegion',
            'years',
            'regions',
            'subPilars'
        ));
    }
}
