<?php

namespace App\Http\Controllers;

use App\Models\EventModel;
use App\Models\ProgramTjsl;
use Illuminate\Http\Request;
// use DB;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramTjslController extends Controller
{
   
    public function index(Request $request)
{
    // 🔹 Data dropdown kebun
    $datakebun = DB::table('tb_unit')
        ->select('unit', 'region')
        ->orderBy('region')
        ->orderBy('unit')
        ->get();
    $programptpn = DB::table('tb_program')
        ->select('program')
        ->get();

    // 🔹 Data dropdown program
    $programtjsl = DB::table('tb_program')
        ->select('program')
        ->orderBy('program')
        ->get();

    // 🔹 Data dropdown tahun
    $tahunfilter = DB::table('tb_program_tjsl')
        ->select(DB::raw('DISTINCT YEAR(tgl_mulai) as tahun'))
        ->orderBy('tahun', 'desc')
        ->get();

    // 🔹 Query utama
    $query = DB::table('tb_program_tjsl')
        ->leftJoin('tb_program', 'tb_program.program', '=', 'tb_program_tjsl.program')
        ->leftJoin('tb_tpb', 'tb_tpb.program', '=', 'tb_program_tjsl.program')
        ->select(
            'tb_program_tjsl.*',
            'tb_program.gambar as gambar_program',
            DB::raw('GROUP_CONCAT(tb_tpb.gambar_tpb ORDER BY tb_tpb.tpb ASC) as gambar_tpbs'),
            DB::raw('GROUP_CONCAT(tb_tpb.nama_tpb ORDER BY tb_tpb.tpb ASC) as nama_tpbs')
        )
        ->groupBy('tb_program_tjsl.id', 'tb_program.gambar');

    // 🔹 Filter dinamis
    $query->when($request->filled('region'), function ($q) use ($request) {
        return $q->where('tb_program_tjsl.regional', $request->region);
    });

    $query->when($request->filled('kebun'), function ($q) use ($request) {
        return $q->where('tb_program_tjsl.kebun', $request->kebun);
    });

    $query->when($request->filled('searchprogram'), function ($q) use ($request) {
        return $q->where('tb_program_tjsl.program', $request->searchprogram);
    });

    $query->when($request->filled('tahun'), function ($q) use ($request) {
        return $q->whereYear('tb_program_tjsl.tgl_mulai', $request->tahun);
    });

    // 🔹 Pagination
    $programs = $query->latest('tb_program_tjsl.id')->paginate(6)->withQueryString();

    // 🔹 Kirim ke view
    return view('programs.index', [
        'programs' => $programs,
        'programptpn' => $programptpn,
        'datakebun' => $datakebun,
        'programtjsl' => $programtjsl,
        'tahunfilter' => $tahunfilter,
        'searchregion' => $request->region ?? '',
        'searchkebun' => $request->kebun ?? '',
        'searchprogram' => $request->searchprogram ?? '',
        'searchtahun' => $request->tahun ?? '',
    ]);
}


    public function getUnitsByRegion(Request $request)
    {
        $region = $request->region;

        $datakebun = DB::table('tb_unit')
            ->select('unit', 'region')
            ->when($region, function ($query, $region) {
                $query->where('region', $region);
            })
            ->orderBy('unit')
            ->get();

        return response()->json($datakebun);
    }

    public function getGambarProgram(Request $request)
    {
        $program = $request->query('program');

        // Ambil gambar program
        $programData = DB::table('tb_program')
            ->where('program', $program)
            ->first();

        if (!$programData) {
            return response()->json(['gambar' => null, 'gambar_tpb' => []]);
        }

        // Ambil semua gambar TPB terkait program
        $tpbGambar = DB::table('tb_tpb')
            ->where('program', $program)
            ->pluck('gambar_tpb')  // hanya ambil kolom gambar_tpb
            ->filter() // hapus null atau kosong
            ->values(); // reset index

        return response()->json([
            'gambar' => $programData->gambar,
            'gambar_tpb' => $tpbGambar
        ]);
    }

    public function store(Request $request)
    {
        // ✅ 1. Validasi input
        // dd($request->all());
        $validated = $request->validate([
            'regional'        => 'required|string',
            'kebun'           => 'required|string',
            'nama_program'    => 'required|string',
            'desa'            => 'required|string',
            'penerima'        => 'required|string',
            'tgl_mulai'       => 'required|date',
            'tgl_selesai'     => 'required|date',
            'program'         => 'required|string',
            'pilar'         => 'required|string',
            'status'          => 'required|string',
            'anggaran'        => 'nullable|numeric',
            'realisasi'       => 'nullable|numeric',
            'persentase'      => 'nullable|numeric',
            'persentase_rka'  => 'nullable|numeric',
            'employee'        => 'nullable|numeric',
            'deskripsi'       => 'nullable|string',
            'sangat_puas'     => 'nullable|integer',
            'puas'            => 'nullable|integer',
            'kurang_puas'     => 'nullable|integer',
            'sroi'            => 'nullable|numeric',
            'saran'           => 'nullable|string',
            'laporan.*'       => 'nullable|mimes:pdf,doc,docx|max:5120',
            'foto.*'          => 'nullable|mimes:jpg,jpeg,png|max:5120',
            'video.*'         => 'nullable|mimes:mp4,mov,avi|max:10240',
            'media.*'         => 'nullable|string',
            'link_berita.*'   => 'nullable|url',
        ]);
        
        DB::beginTransaction();
        try {
            // ✅ 2. Simpan data utama
            
            $id = DB::table('tb_program_tjsl')->insertGetId([
                'regional'        => $validated['regional'],
                'kebun'           => $validated['kebun'],
                'nama_program'    => $validated['nama_program'],
                'lokasi_program'  => $validated['desa'],
                'penerima'        => $validated['penerima'],
                'tgl_mulai'       => $validated['tgl_mulai'],
                'tgl_selesai'     => $validated['tgl_selesai'],
                'program'         => $validated['program'],
                'pilar'         => $validated['pilar'],
                'status'          => $validated['status'],
                'anggaran'        => $validated['anggaran'] ?? null,
                'realisasi'       => $validated['realisasi'] ?? null,
                // 'persentase'      => $validated['persentase'] ?? null,
                'persentase_rka'  => $validated['persentase_rka'] ?? null,
                'deskripsi'       => $validated['deskripsi'] ?? null,
                'sangat_puas'     => $validated['sangat_puas'] ?? null,
                'puas'            => $validated['puas'] ?? null,
                'kurang_puas'     => $validated['kurang_puas'] ?? null,
                'sroi'            => $validated['sroi'] ?? null,
                'saran'           => $validated['saran'] ?? null,
                'created_at'      => now(),
                'updated_at'      => now(),
                'employee'           => $validated['employee'] ?? null,
            ]);
            // dd($id);
            // ✅ 3. Upload laporan
            if ($request->hasFile('laporan')) {
                foreach ($request->file('laporan') as $file) {
                    $filename = 'laporan_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('img/program/laporan', $filename, 'public');

                    DB::table('tb_program_laporan')->insert([
                        'id_program' => $id,
                        'file_laporan'  => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            // ✅ 4. Upload foto
            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $filename = 'foto_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('img/program/foto', $filename, 'public');

                    DB::table('tb_program_foto')->insert([
                        'id_program' => $id,
                        'nama_file' =>$filename,
                        'file_path'  => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            // ✅ 5. Upload video
            if ($request->hasFile('video')) {
                foreach ($request->file('video') as $file) {
                    $filename = 'video_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('img/program/video', $filename, 'public');

                    DB::table('tb_program_video')->insert([
                        'id_program' => $id,
                        'nama_file' =>$filename,
                        'file_path'  => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            // ✅ 6. Simpan media berita
            if ($request->media && $request->link_berita) {
                foreach ($request->media as $i => $mediaName) {
                    $link = $request->link_berita[$i] ?? null;
                    if ($mediaName || $link) {
                        DB::table('tb_program_publikasi')->insert([
                            'id_program' => $id,
                            'media' => $mediaName,
                            'link_berita' => $link,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Program TJSL berhasil disimpan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }


    public function edit($id)
    {
        try {
            // 🔹 Ambil data utama program
            $program = DB::table('tb_program_tjsl')
                ->where('id', $id)
                ->first();

            if (!$program) {
                return response()->json(['error' => 'Program tidak ditemukan.'], 404);
            }

            // 🔹 Ambil nama desa lengkap berdasarkan kode lokasi_program
            $namaDesa = null;
            if (!empty($program->lokasi_program)) {
                $desa = DB::table('wilayah')->where('kode', $program->lokasi_program)->first();

                if ($desa) {
                    $kecamatan = DB::table('wilayah')->where('kode', substr($desa->kode, 0, 8))->first();
                    $kabupaten = DB::table('wilayah')->where('kode', substr($desa->kode, 0, 5))->first();
                    $provinsi  = DB::table('wilayah')->where('kode', substr($desa->kode, 0, 2))->first();

                    $namaDesa = $desa->nama
                        . ', ' . ($kecamatan->nama ?? '')
                        . ', ' . ($kabupaten->nama ?? '')
                        . ', ' . ($provinsi->nama ?? '');
                }
            }

            // 🔹 Tambahkan nama desa ke objek program
            $program->nama_desa = $namaDesa;

            // 🔹 Ambil data tambahan (foto, video, publikasi, laporan)
            $foto = DB::table('tb_program_foto')
                ->where('id_program', $id)
                ->select('id_foto', 'nama_file', 'file_path')
                ->get();

            $video = DB::table('tb_program_video')
                ->where('id_program', $id)
                ->select('id_video', 'nama_file', 'file_path')
                ->get();

            $publikasi = DB::table('tb_program_publikasi')
                ->where('id_program', $id)
                ->select('id_publikasi', 'media', 'link_berita')
                ->get();
            
            $laporan = DB::table('tb_program_laporan')
                ->where('id_program', $id)
                ->select('id_laporan', 'file_laporan')
                ->get();

            // 🔹 Gabungkan hasil ke satu respons JSON
            return response()->json([
                'program' => $program,
                'foto' => $foto,
                'video' => $video,
                'publikasi' => $publikasi,
                'laporan' => $laporan,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan server: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        // dd($request->delete_publikasi);
        // dd($request->all());
        $validated = $request->validate([
            'regional'        => 'required|string',
            'kebun'           => 'required|string',
            'nama_program'    => 'required|string',
            'desa'            => 'required|string',
            'penerima'        => 'required|string',
            'tgl_mulai'       => 'required|date',
            'tgl_selesai'     => 'required|date',
            'program'         => 'required|string',
            'pilar'           => 'required|string',
            'status'          => 'required|string',
            'anggaran'        => 'nullable|numeric',
            'realisasi'       => 'nullable|numeric',
            'persentase'      => 'nullable|numeric',
            'employee'        => 'nullable|numeric',
            // 'persentase_rka'  => 'nullable|numeric',
            'deskripsi'       => 'nullable|string',
            'sangat_puas'     => 'nullable|integer',
            'puas'            => 'nullable|integer',
            'kurang_puas'     => 'nullable|integer',
            'sroi'            => 'nullable|numeric',
            'saran'           => 'nullable|string',

            'laporan.*'       => 'nullable|mimes:pdf,doc,docx|max:5120',
            'foto.*'          => 'nullable|mimes:jpg,jpeg,png|max:5120',
            'video.*'         => 'nullable|mimes:mp4,mov,avi|max:10240',
            'media.*'         => 'nullable|string',
            'link_berita.*'   => 'nullable|url',

            'delete_foto'        => 'nullable|array',
            'delete_video'       => 'nullable|array',
            'delete_laporan'     => 'nullable|array',
            'delete_publikasi'   => 'nullable|array',
        ]);
        
        DB::beginTransaction();
        try {
            // =========================
            // ✅ Update data utama
            // =========================
            
            DB::table('tb_program_tjsl')
                ->where('id', $id)
                ->update([
                    'regional'        => $validated['regional'],
                    'kebun'           => $validated['kebun'],
                    'nama_program'    => $validated['nama_program'],
                    'lokasi_program'  => $validated['desa'],
                    'penerima'        => $validated['penerima'],
                    'tgl_mulai'       => $validated['tgl_mulai'],
                    'tgl_selesai'     => $validated['tgl_selesai'],
                    'program'         => $validated['program'],
                    'pilar'           => $validated['pilar'],
                    'status'          => $validated['status'],
                    'anggaran'        => $validated['anggaran'] ?? null,
                    'realisasi'       => $validated['realisasi'] ?? null,
                    'persentase_rka'  => $validated['persentase_rka'] ?? null,
                    'deskripsi'       => $validated['deskripsi'] ?? null,
                    'sangat_puas'     => $validated['sangat_puas'] ?? null,
                    'puas'            => $validated['puas'] ?? null,
                    'kurang_puas'     => $validated['kurang_puas'] ?? null,
                    'sroi'            => $validated['sroi'] ?? null,
                    'saran'           => $validated['saran'] ?? null,
                    'employee'           => $validated['employee'] ?? null,
                    'updated_at'      => now(),
                ]);

            // =========================
            // ✅ Hapus file fisik dan record lama (jika ada)
            // =========================
            // ===========================
            // Hapus Foto
            // ===========================
            if ($request->filled('deleted_foto_ids')) {
                $files = DB::table('tb_program_foto')->whereIn('id_foto', $request->deleted_foto_ids)->get();

                foreach ($files as $f) {
                    Storage::disk('public')->delete($f->file_path); // hapus file fisik
                }

                DB::table('tb_program_foto')->whereIn('id_foto', $request->deleted_foto_ids)->delete(); // hapus record
            }

            // ===========================
            // Hapus Video
            // ===========================
            if ($request->filled('deleted_video_ids')) {
                $files = DB::table('tb_program_video')->whereIn('id_video', $request->deleted_video_ids)->get();

                foreach ($files as $f) {
                    Storage::disk('public')->delete($f->file_path);
                }

                DB::table('tb_program_video')->whereIn('id_video', $request->deleted_video_ids)->delete();
            }

            // ===========================
            // Hapus Laporan
            // ===========================
            if ($request->filled('delete_laporan')) {
                $files = DB::table('tb_program_laporan')->whereIn('id_laporan', $request->delete_laporan)->get();

                foreach ($files as $f) {
                    Storage::disk('public')->delete($f->file_laporan);
                }

                DB::table('tb_program_laporan')->whereIn('id_laporan', $request->delete_laporan)->delete();
            }

            // ===========================
            // Hapus Publikasi
            // ===========================
            if ($request->filled('delete_publikasi')) {
                DB::table('tb_program_publikasi')->whereIn('id_publikasi', $request->delete_publikasi)->delete();
            }


            // =========================
            // ✅ Upload file baru
            // =========================
            if ($request->hasFile('file_laporan')) {
                $files = $request->file('file_laporan');

                if (!is_array($files)) {
                    $files = [$files]; // ubah menjadi array biar foreach bisa jalan
                }

                foreach ($files as $file) {
                    $filename = 'laporan_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('img/program/laporan', $filename, 'public');

                    DB::table('tb_program_laporan')->insert([
                        'id_program' => $id,
                        'file_laporan' => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            if ($request->hasFile('foto')) {
                foreach ($request->file('foto') as $file) {
                    $filename = 'foto_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('img/program/foto', $filename, 'public');
                    DB::table('tb_program_foto')->insert([
                        'id_program' => $id,
                        'nama_file'  => $filename,
                        'file_path'  => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            if ($request->hasFile('video')) {
                foreach ($request->file('video') as $file) {
                    $filename = 'video_' . Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $path = $file->storeAs('img/program/video', $filename, 'public');
                    DB::table('tb_program_video')->insert([
                        'id_program' => $id,
                        'nama_file'  => $filename,
                        'file_path'  => $path,
                        'created_at' => now(),
                    ]);
                }
            }

            // =========================
            // ✅ Tambah publikasi baru
            // =========================
            if ($request->media && $request->link_berita) {
                foreach ($request->media as $i => $mediaName) {
                    $link = $request->link_berita[$i] ?? null;
                    if ($mediaName || $link) {
                        DB::table('tb_program_publikasi')->insert([
                            'id_program' => $id,
                            'media' => $mediaName,
                            'link_berita' => $link,
                            'created_at' => now(),
                        ]);
                    }
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Program TJSL berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            // 🔹 Ambil data utama program
            $program = DB::table('tb_program_tjsl')->where('id', $id)->first();

            if (!$program) {
                return response()->json(['error' => 'Program tidak ditemukan.'], 404);
            }

            // 🔹 Ambil nama desa lengkap berdasarkan kode lokasi_program
            $namaDesa = null;
            if (!empty($program->lokasi_program)) {
                $desa = DB::table('wilayah')->where('kode', $program->lokasi_program)->first();

                if ($desa) {
                    // ambil semua level wilayah hanya jika ada
                    $kecamatan = DB::table('wilayah')->where('kode', substr($desa->kode, 0, 8))->first();
                    $kabupaten = DB::table('wilayah')->where('kode', substr($desa->kode, 0, 5))->first();
                    $provinsi  = DB::table('wilayah')->where('kode', substr($desa->kode, 0, 2))->first();

                    $namaDesa = collect([
                        $desa->nama ?? null,
                        $kecamatan->nama ?? null,
                        $kabupaten->nama ?? null,
                        $provinsi->nama ?? null,
                    ])->filter()->implode(', ');
                }
            }

            $program->nama_desa = $namaDesa;

            // 🔹 Ambil data tambahan
            $foto = DB::table('tb_program_foto')
                ->where('id_program', $id)
                ->select('id_foto', 'nama_file', 'file_path')
                ->get();

            $video = DB::table('tb_program_video')
                ->where('id_program', $id)
                ->select('id_video', 'nama_file', 'file_path')
                ->get();

            $publikasi = DB::table('tb_program_publikasi')
                ->where('id_program', $id)
                ->select('id_publikasi', 'media', 'link_berita')
                ->get();

            $laporan = DB::table('tb_program_laporan')
                ->where('id_program', $id)
                ->select('id_laporan', 'file_laporan')
                ->get();

            // 🔹 Gabungkan hasil ke satu respons JSON
            return response()->json([
                'data' => [
                    'program' => $program,
                    'foto' => $foto,
                    'video' => $video,
                    'publikasi' => $publikasi,
                    'laporan' => $laporan,
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan server.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Cek program utama
            $program = DB::table('tb_program_tjsl')->where('id', $id)->first();
            if (!$program) {
                return response()->json(['success' => false, 'error' => 'Program tidak ditemukan'], 404);
            }

            // 🔹 Ambil dan hapus semua foto
            $fotos = DB::table('tb_program_foto')->where('id_program', $id)->get();
            foreach ($fotos as $foto) {
                if (Storage::disk('public')->exists($foto->file_path)) {
                    Storage::disk('public')->delete($foto->file_path);
                }
            }
            DB::table('tb_program_foto')->where('id_program', $id)->delete();

            // 🔹 Ambil dan hapus semua video
            $videos = DB::table('tb_program_video')->where('id_program', $id)->get();
            foreach ($videos as $video) {
                if (Storage::disk('public')->exists($video->file_path)) {
                    Storage::disk('public')->delete($video->file_path);
                }
            }
            DB::table('tb_program_video')->where('id_program', $id)->delete();

            // 🔹 Ambil dan hapus semua laporan
            $laporans = DB::table('tb_program_laporan')->where('id_program', $id)->get();
            foreach ($laporans as $laporan) {
                if (Storage::disk('public')->exists($laporan->file_laporan)) {
                    Storage::disk('public')->delete($laporan->file_laporan);
                }
            }
            DB::table('tb_program_laporan')->where('id_program', $id)->delete();

            // 🔹 Hapus publikasi (tidak ada file)
            DB::table('tb_program_publikasi')->where('id_program', $id)->delete();

            // 🔹 Terakhir hapus program
            DB::table('tb_program_tjsl')->where('id', $id)->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function index_event()
    {
        $datakebun = DB::table('tb_unit')
        ->select('unit', 'region')
        ->orderBy('region')
        ->orderBy('unit')
        ->get();
        return view('programs.event', [
        'datakebun' => $datakebun
    ]);
    }

    public function fetch_event(Request $request)
    {
        // $events = EventModel::all();

        // $data = $events->map(function ($event) {
        //     return [
        //         'id'    => $event->id,
        //         'title' => $event->judul,
        //         'start' => $event->start_event,
        //         'end'   => $event->end_event,
        //         'region' => $event->region, // 🟢 tambahkan ini
        //     ];
        // });

        // return response()->json($data);
        $query = EventModel::query();

        if ($request->filled('region')) {
            $query->where('region', $request->region);
        }
        if ($request->filled('kat_stekholder')) {
            $query->where('kat_stekholder', $request->kat_stekholder);
        }
        if ($request->filled('tipe_event')) {
            $query->where('tipe_event', $request->tipe_event);
        }

        $events = $query->get(['id', 'judul as title', 'start_event as start', 'end_event as end', 'region', 'kat_stekholder', 'tipe_event', 'unit']);

        return response()->json($events);
    }



    public function store_event(Request $request)
    {
        try {
            // Validasi data terlebih dahulu
            $validated = $request->validate([
                'judul'          => 'required|string|max:255',
                'deskripsi'      => 'nullable|string',
                'start_event'    => 'required|date',
                'end_event'      => 'nullable|date|after_or_equal:start_event',
                'region'         => 'required|string|max:100',
                'unit'           => 'required|string|max:100',
                'kat_stekholder' => 'nullable|string|max:100',
                'tipe_event'     => 'nullable|string|max:100',
            ]);

            // Tambahkan tanggal otomatis
            $validated['date_created']  = now();
            $validated['date_modified'] = now();

            // Simpan ke database
            $event = EventModel::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Event berhasil disimpan.',
                'event'   => $event
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan event.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    public function destroy_event($id)
    {
        $event = EventModel::findOrFail($id);
        $event->delete();
        return response()->json(['success' => true]);
    }

    public function show_event($id)
    {
        $event = EventModel::find($id);

        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event tidak ditemukan'], 404);
        }

        return response()->json([
            'success' => true,
            'event' => $event
        ]);
    }




}