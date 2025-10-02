<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class IsuController extends Controller
{
  
    public function store(Request $request)
    {
        $request->validate([
            'derajat_id' => 'required|exists:tb_derajat_hubungan,id',
        ]);

        $derajatId = $request->derajat_id;

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
            foreach ($request->desa as $desa) {
                if ($desa) {
                    DB::table('tb_desa_detail')->insert([
                        'derajat_id' => $derajatId,
                        'desa'       => $desa,
                    ]);
                }
            }
        }

        // === Simpan INSTANSI ===
        if ($request->has('instansi')) {
            foreach ($request->instansi as $instansi) {
                if ($instansi) {
                    DB::table('tb_instansi_detail')->insert([
                        'derajat_id' => $derajatId,
                        'instansi'   => $instansi,
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan.');
    }
    

    public function destroy($id)
    {
        try {
            // hapus semua detail berdasarkan id (derajat_id)
            DB::table('tb_isu_detail')->where('derajat_id', $id)->delete();
            DB::table('tb_desa_detail')->where('derajat_id', $id)->delete();
            DB::table('tb_instansi_detail')->where('derajat_id', $id)->delete();
            DB::table('tb_okupasi_detail')->where('derajat_id', $id)->delete();

            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal hapus data: ' . $e->getMessage());
        }
    }
}
