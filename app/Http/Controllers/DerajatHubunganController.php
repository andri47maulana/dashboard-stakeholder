<?php
namespace App\Http\Controllers;

use App\Models\DerajatHubungan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DerajatHubunganImport;
use Auth;
use DB;
use App\Models\IsuDetail;
use App\Models\DesaDetail;
use App\Models\InstansiDetail;
use App\Models\OkupasiDetail;

class DerajatHubunganController extends Controller
{
    public function index()
    {
        
        $user = Auth::user()->region;
        // $query = DerajatHubungan::with('unitx');
        $query = DerajatHubungan::with(['unitx'])
            ->withCount([
                'isuDetail',
                'isuDesa',
                'isuInstansi',
                'isuOkupasi',
            ]);
            // dd($query);

        // Jika user = PTPN I HO → lihat semua
        if ($user === "PTPN I HO") {
            $data = $query->get();
            $units = Unit::all();
           $stake = DB::table('stakeholder')->get();
        } else {
           
            $data = $query->whereHas('unitx', function($q) use ($user) {
                $q->where('region', $user);
            })->get();

            $units = Unit::where('region', $user)->get();
            $stake = DB::table('stakeholder')->where('region', $user)->get();
        }
        
        return view('derajat.index', compact('data', 'units', 'stake'));
    }

    public function store(Request $request)
    {
        $request->merge(['input_date' => now(), 'modified_date' => now()]);
        DerajatHubungan::create($request->all());
        return response()->json(['success' => true]);
    }

    // public function update(Request $request, $id)
    // {
    //     $derajat = DerajatHubungan::findOrFail($id);
    //     $request->merge(['modified_date' => now()]);
    //     $derajat->update($request->all());
    //     return response()->json(['success' => true]);
    // }

    public function update(Request $request, $id)
    {
        $derajat = DerajatHubungan::findOrFail($id);

        // ambil hanya deskripsi tambahan
        // $deskripsiManual = trim($request->deskripsi1 ?? '');

        $derajat->update([
            // 'id_unit'          => $request->id_unit,
            // 'tahun'            => $request->tahun,
            'kepuasan'          => $request->kepuasan,
            'kontribusi'        => $request->kontribusi,
            'komunikasi'        => $request->komunikasi,
            'kepercayaan'       => $request->kepercayaan,
            'keterlibatan'      => $request->keterlibatan,
            'indeks_kepuasan'   => $request->indeks_kepuasan,
            'lingkungan'        => $request->lingkungan,
            'ekonomi'           => $request->ekonomi,
            'pendidikan'        => $request->pendidikan,
            'sosial_kesesjahteraan' => $request->sosial_kesesjahteraan,
            'okupasi'           => $request->okupasi,
            'skor_socmap'       => $request->skor_socmap,
            'derajat_hubungan'  => $request->derajat_hubungan,
            'derajat_kepuasan'  => $request->derajat_kepuasan,
            'prioritas_socmap'  => $request->prioritas_socmap,
            'deskripsi'         => $request->deskripsi,
            'modified_date'     => now(),
        ]);

        return response()->json(['success' => true]);
    }


    public function destroy($id)
    {
        DerajatHubungan::destroy($id);
        return response()->json(['success' => true]);
    }

    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls']);
        Excel::import(new DerajatHubunganImport, $request->file('file'));
        return back()->with('success', 'Import berhasil!');
    }

    public function isu_store(Request $request)
    {
        try {
            $request->validate([
                'derajat_id' => 'required|exists:tb_derajat_hubungan,id',
            ]);

            $derajatId = $request->derajat_id;

            // === CEK apakah derajat_id sudah ada di salah satu tabel ===
            $exists = DB::table('tb_isu_detail')->where('derajat_id', $derajatId)->exists()
                || DB::table('tb_isu_desa')->where('derajat_id', $derajatId)->exists()
                || DB::table('tb_isu_instansi')->where('derajat_id', $derajatId)->exists()
                || DB::table('tb_isu_okupasi')->where('derajat_id', $derajatId)->exists();

            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data untuk ini sudah ada!',
                ]);
            }

            // === Simpan ISU ===
            if ($request->has('isu')) {
                foreach ($request->isu as $key => $isu) {
                    if ($isu) {
                        DB::table('tb_isu_detail')->insert([
                            'derajat_id' => $derajatId,
                            'isu'        => $isu,
                            'keterangan' => $request->keterangan[$key] ?? null,
                        ]);
                    }
                }
            }

            // === Simpan DESA ===
            if ($request->has('desa')) {
                foreach ($request->desa as $key => $desa) {
                    if ($desa) {
                        DB::table('tb_isu_desa')->insert([
                            'derajat_id' => $derajatId,
                            'desa_id'    => $desa,
                            'isu_utama'  => $request->isu_utama[$key] ?? null,
                        ]);
                    }
                }
            }

            // === Simpan INSTANSI ===
            if ($request->has('instansi')) {
                foreach ($request->instansi as $key => $instansi) {
                    if ($instansi) {
                        DB::table('tb_isu_instansi')->insert([
                            'derajat_id' => $derajatId,
                            'instansi_id'=> $instansi,
                            'program'    => $request->program[$key] ?? null,
                        ]);
                    }
                }
            }

            // === Simpan OKUPASI ===
            if ($request->has('okupasi')) {
                DB::table('tb_isu_okupasi')->insert([
                    'derajat_id' => $derajatId,
                    'okupasi'    => $request->okupasi ?? null,
                    'keterangan' => $request->keterangan_okupasi ?? null,
                ]);
            }

            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ]);
        }
        // dd('masuk instansi');
// dd('masuk instansi');

        // return response()->json(['success' => true, 'message' => 'Isu strategis berhasil disimpan.']);
    }
    public function getDesa(Request $request)
    {
        $search = $request->get('q');

        $query = DB::table('wilayah')->whereRaw('LENGTH(kode) = 13'); // hanya desa

        if ($search) {
            $query->where('nama', 'like', "%$search%");
        }

        $desaList = $query->limit(20)->get(); // batasi 20 data per request

        $results = $desaList->map(function ($desa) {
            $kode = $desa->kode;
            $namaDesa = $desa->nama;

            $kecamatan = DB::table('wilayah')->where('kode', substr($kode, 0, 8))->first();
            $kabupaten = DB::table('wilayah')->where('kode', substr($kode, 0, 5))->first();
            $provinsi  = DB::table('wilayah')->where('kode', substr($kode, 0, 2))->first();

            return [
                'id'   => $kode,
                'text' => $namaDesa
                    . ', ' . ($kecamatan->nama ?? '')
                    . ', ' . ($kabupaten->nama ?? '')
                    . ', ' . ($provinsi->nama ?? ''),
            ];
        });

        return response()->json(['results' => $results]);
    }

    // === SHOW untuk AJAX ===
    public function show($id)
    {
        $isu = IsuDetail::where('derajat_id', $id)->get();
        $desaDetails = DesaDetail::where('derajat_id', $id)->get();
        // $instansi = InstansiDetail::where('derajat_id', $id)->get();
        $okupasi = OkupasiDetail::where('derajat_id', $id)->first();

        // Ambil instansi detail + join ke stakeholder
        $instansi = InstansiDetail::where('derajat_id', $id)
            ->get()
            ->map(function($item){
                $stakeholder = DB::table('stakeholder')->where('id', $item->instansi_id)->first();
                return [
                    'instansi' => $stakeholder->nama_instansi ?? null,
                    'program'  => $item->program
                ];
            });
        $unit=DerajatHubungan::where('id',$id)->first();
            // dd($unit->id_unit);
        $kebunJsons = DB::table('kebun_json')
                ->where('unit_id', $unit->id_unit)
                ->get()
                ->map(function($item){
                    return json_decode($item->json, true); // return langsung hasil decode
                });
        // mapping desa agar ada nama lengkap
        $desa = $desaDetails->map(function ($d) {
            $kode = $d->desa_id;

            $desa = DB::table('wilayah')->where('kode', $kode)->first();
            if (!$desa) {
                return [
                    'desa_id'   => $kode,
                    'desa_nama' => null
                ];
            }

            $kecamatan = DB::table('wilayah')->where('kode', substr($kode, 0, 8))->first();
            $kabupaten = DB::table('wilayah')->where('kode', substr($kode, 0, 5))->first();
            $provinsi  = DB::table('wilayah')->where('kode', substr($kode, 0, 2))->first();

            return [
                'desa_id'   => $kode,
                'desa_nama' => $desa->nama
                    . ', ' . ($kecamatan->nama ?? '')
                    . ', ' . ($kabupaten->nama ?? '')
                    . ', ' . ($provinsi->nama ?? ''),
                'isu_utama' => $d->isu_utama
            ];
        });

        return response()->json([
            'isu'      => $isu,
            'desa'     => $desa,
            'instansi' => $instansi,
            'okupasi'  => $okupasi,
            'kebunJsons' => $kebunJsons,
            'derajatHubungan' => $unit,
        ]);
    }


    // === UPDATE ===
    public function update_isu(Request $request)
    {
        $id = $request->derajat_id;
        // dd($request->all());
        // --- ISU ---
        IsuDetail::where('derajat_id', $id)->delete();
        if ($request->has('isu')) {
            foreach ($request->isu as $key => $val) {
                IsuDetail::create([
                    'derajat_id' => $id,
                    'isu'        => $val,
                    'keterangan' => $request->keterangan[$key] ?? ''
                ]);
            }
        }
        // dd($id);
        // --- DESA ---
        DesaDetail::where('derajat_id', $id)->delete();
        
        if ($request->has('desa')) {
            foreach ($request->desa as $key => $desaId) {
                DesaDetail::create([
                    'derajat_id' => $id,
                    'desa_id'    => $desaId,                     // <-- ini wajib diisi
                    'isu'        => $request->isu[$key] ?? null,
                    'isu_utama'  => $request->isu_utama[$key] ?? null,
                    'keterangan' => $request->keterangan[$key] ?? null,
                ]);
            }
        }

        // --- INSTANSI ---
        InstansiDetail::where('derajat_id', $id)->delete();
        if ($request->has('instansi')) {
            foreach ($request->instansi as $key => $val) {
                InstansiDetail::create([
                    'derajat_id'  => $id,
                    'instansi_id' => $val,
                    'program'     => $request->program[$key] ?? ''
                ]);
            }
        }

        // --- OKUPASI ---
        OkupasiDetail::updateOrCreate(
            ['derajat_id' => $id],
            [
                'okupasi'    => $request->okupasi,
                'keterangan' => $request->keterangan_okupasi
            ]
        );

        return redirect()->back()->with('success', 'Data isu berhasil diperbarui!');
    }
}
