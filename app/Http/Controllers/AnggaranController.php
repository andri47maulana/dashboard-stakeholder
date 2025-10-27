<?php

namespace App\Http\Controllers;

use App\Models\Anggaran;
use App\Models\SubPilar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AnggaranController extends Controller
{
    public function index()
    {
        $anggarans = Anggaran::with('subPilar')->paginate(10);
        $subPilars = SubPilar::all();

        return view('anggaran.index', compact('anggarans', 'subPilars'));
    }

    public function store(Request $request)
    {
        // Debug: Log semua data yang diterima

        $validator = Validator::make($request->all(), [
            'sub_pilar_id' => 'required|exists:m_sub_pilar,id',
            'tahun' => 'required|digits:4',
            'anggaran' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Cek duplikasi data
            $exists = Anggaran::where('sub_pilar_id', $request->sub_pilar_id)
                             ->where('tahun', $request->tahun)
                             ->exists();



            if ($exists) {

                return response()->json([
                    'success' => false,
                    'message' => 'Data anggaran untuk sub pilar dan tahun ini sudah ada'
                ], 422);
            }


            $anggaran = Anggaran::create([
                'sub_pilar_id' => (int) $request->sub_pilar_id,
                'tahun' => $request->tahun,
                'anggaran' => (float) $request->anggaran,
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil ditambahkan',
                'data' => $anggaran->load('subPilar')
            ]);
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Kesalahan database saat menyimpan data: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'sub_pilar_id' => 'required|exists:m_sub_pilar,id',
            'tahun' => 'required|digits:4',
            'anggaran' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $anggaran = Anggaran::findOrFail($id);
            $anggaran->update([
                'sub_pilar_id' => (int) $request->sub_pilar_id,
                'tahun' => $request->tahun,
                'anggaran' => (float) $request->anggaran,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil diperbarui',
                'data' => $anggaran->load('subPilar')
            ]);
        } catch (\Illuminate\Database\QueryException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Kesalahan database saat memperbarui data: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui data'
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $anggaran = Anggaran::findOrFail($id);
            $anggaran->delete();

            return response()->json([
                'success' => true,
                'message' => 'Anggaran berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus data'
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $anggaran = Anggaran::with('subPilar')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $anggaran
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);
        }
    }
}
