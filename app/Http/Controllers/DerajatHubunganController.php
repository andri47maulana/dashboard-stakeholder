<?php
namespace App\Http\Controllers;

use App\Models\DerajatHubungan;
use App\Models\Unit;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\DerajatHubunganImport;
use Auth;

class DerajatHubunganController extends Controller
{
    public function index()
    {
        
        $user = Auth::user()->region;
        $query = DerajatHubungan::with('unitx');

        // Jika user = PTPN I HO → lihat semua
        if ($user === "PTPN I HO") {
            $data = $query->get();
            $units = Unit::all();
           
        } else {
           
            $data = $query->whereHas('unitx', function($q) use ($user) {
                $q->where('region', $user);
            })->get();

            $units = Unit::where('region', $user)->get();
        }
        return view('derajat.index', compact('data', 'units'));
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
        $request->validate([
            'id_unit' => 'required|exists:tb_unit,id',
            'isu_strategis' => 'required|string',
            'tahun' => 'required|integer',
        ]);

        $data = [
            'id_unit' => $request->id_unit,
            'isu_strategis' => $request->isu_strategis,
            'tahun' => $request->tahun,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        \DB::table('tb_isu_strategis')->insert($data);

        return response()->json(['success' => true, 'message' => 'Isu strategis berhasil disimpan.']);
    }
}
