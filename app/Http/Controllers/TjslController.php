<?php

namespace App\Http\Controllers;

use App\Models\Tjsl;
use App\Models\BiayaTjsl;
use App\Models\PubTjsl;
use App\Models\DocTjsl;
use App\Models\FeedbackTjsl;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TjslController extends Controller
{
    public function index(Request $request)
    {
        $query = Tjsl::with(['unit', 'creator', 'pilar']);

        // Filter berdasarkan region (melalui unit)
        if ($request->filled('region')) {
            $query->whereHas('unit', function($q) use ($request) {
                $q->where('region', $request->region);
            });
        }

        // Filter berdasarkan pilar
        if ($request->filled('pilar_id')) {
            $query->where('pilar_id', $request->pilar_id);
        }

        // Filter berdasarkan kebun/unit
        if ($request->filled('unit_id')) {
            $query->where('unit_id', $request->unit_id);
        }

        // Filter berdasarkan tahun tanggal_mulai
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search berdasarkan nama program
        if ($request->filled('search')) {
            $query->where('nama_program', 'like', '%' . $request->search . '%');
        }

        $tjsls = $query->orderBy('created_at', 'desc')->paginate(12);

        // Data untuk dropdown filter
        $pilars = \App\Models\Pilar::orderBy('pilar')->get();
        $units = \App\Models\Unit::orderBy('unit')->get();

        // Ambil region unik dari tabel unit
        $regions = \App\Models\Unit::select('region')
            ->distinct()
            ->whereNotNull('region')
            ->orderBy('region')
            ->pluck('region');

        // Ambil tahun unik dari tanggal_mulai
        $tahunList = Tjsl::selectRaw('YEAR(tanggal_mulai) as tahun')
            ->whereNotNull('tanggal_mulai')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        return view('tjsl.index', compact('tjsls', 'pilars', 'units', 'regions', 'tahunList'));
    }

    public function show($id)
    {
        $tjsl = Tjsl::with([
            'unit',
            'biayaTjsl',
            'pubTjsl',
            'docTjsl',
            'feedbackTjsl',
            'creator',
            'updater',
            'pilar' // Tambahkan relasi pilar
        ])->findOrFail($id);

        $pilars = $tjsl->pilarRelation; // Mendapatkan data pilar lengkap

        // Hitung total anggaran
        $totalAnggaran = $tjsl->biayaTjsl->sum('anggaran');
        $totalRealisasi = $tjsl->biayaTjsl->sum('realisasi');

        // Hitung persentase realisasi (contoh, sesuaikan dengan logika bisnis)
        $persentaseRealisasi = $totalAnggaran > 0 ? ($totalRealisasi / $totalAnggaran) * 100 : 0;

        // Hitung persentase RKA (contoh)
        $persentaseRka = $tjsl->biayaTjsl->where('jenis_biaya', 'rka')->sum('nominal');
        $persentaseRka = $totalAnggaran > 0 ? ($persentaseRka / $totalAnggaran) * 100 : 0;

        // Feedback statistics dengan kategori yang lebih detail
        $feedbackStats = [
            'total' => $tjsl->feedbackTjsl->count(),
            'rating_avg' => 0, // Default value karena tidak ada kolom rating
            'sangat_puas' => $tjsl->feedbackTjsl->sum('sangat_puas'), // Jumlah nilai sangat_puas
            'puas' => $tjsl->feedbackTjsl->sum('puas'), // Jumlah nilai puas
            'kurang_puas' => $tjsl->feedbackTjsl->sum('kurang_puas'), // Jumlah nilai kurang_puas
            'saran' => $tjsl->feedbackTjsl->pluck('saran')->filter()->last(), // Saran terbaru yang tidak kosong
            'latest_feedback' => $tjsl->feedbackTjsl->sortByDesc('id')->first(), // Feedback terbaru berdasarkan ID
        ];

        $publications = [
            'total' => $tjsl->pubTjsl->count(),
            'medias' => $tjsl->pubTjsl->map(function($pub) {
                return [
                    'media' => $pub->media,
                    'link' => $pub->link
                ];
            })->filter(function($pub) {
                return !empty($pub['media']) || !empty($pub['link']); // Filter publikasi yang memiliki media atau link
            })->values()->toArray(), // Convert ke array dan reset index
        ];

        $documentations = [
            'total' => $tjsl->docTjsl->count(),
            'documens' => $tjsl->docTjsl->map(function($doc) {
                return [
                    'nama_dokumen' => $doc->nama_dokumen,
                    'link' => $doc->link
                ];
            })->filter(function($doc) {
                return !empty($doc['nama_dokumen']) || !empty($doc['link']); // Filter dokumentasi yang memiliki nama_dokumen atau link
            })->values()->toArray(), // Convert ke array dan reset index
        ];

        return view('tjsl.show', compact(
            'tjsl',
            'totalAnggaran',
            'persentaseRealisasi',
            'persentaseRka',
            'feedbackStats',
            'publications',
            'documentations',
            'pilars' // Tambahkan pilar ke compact
        ));
    }

    public function create()
    {
        $units = Unit::all();
        return view('tjsl.create', compact('units'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'unit_id' => 'required|exists:units,id',
            'lokasi_program' => 'required|string|max:255',
            'pilar' => 'required|string|max:100',
            'sub_pilar' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|string',
            'penerima_dampak' => 'required|string|max:255',
            'tpb' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        $tjsl = Tjsl::create([
            'nama_program' => $request->nama_program,
            'deskripsi' => $request->deskripsi,
            'unit_id' => $request->unit_id,
            'lokasi_program' => $request->lokasi_program,
            'pilar' => $request->pilar,
            'sub_pilar' => $request->sub_pilar,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'penerima_dampak' => $request->penerima_dampak,
            'tpb' => $request->tpb,
            'status' => $request->status,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tjsl.show', $tjsl->id)
            ->with('success', 'Program TJSL berhasil dibuat.');
    }

    public function edit($id)
    {
        $tjsl = Tjsl::findOrFail($id);
        $units = Unit::all();
        return view('tjsl.edit', compact('tjsl', 'units'));
    }

    public function update(Request $request, $id)
    {
        $tjsl = Tjsl::findOrFail($id);

        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'unit_id' => 'required|exists:units,id',
            'lokasi_program' => 'required|string|max:255',
            'pilar' => 'required|string|max:100',
            'sub_pilar' => 'required|string|max:100',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|string',
            'penerima_dampak' => 'required|string|max:255',
            'tpb' => 'required|string|max:255',
            'status' => 'required|integer',
        ]);

        $tjsl->update([
            'nama_program' => $request->nama_program,
            'deskripsi' => $request->deskripsi,
            'unit_id' => $request->unit_id,
            'lokasi_program' => $request->lokasi_program,
            'pilar' => $request->pilar,
            'sub_pilar' => $request->sub_pilar,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_akhir' => $request->tanggal_akhir,
            'penerima_dampak' => $request->penerima_dampak,
            'tpb' => $request->tpb,
            'status' => $request->status,
            'updated_by' => Auth::id(),
        ]);

        return redirect()->route('tjsl.show', $tjsl->id)
            ->with('success', 'Program TJSL berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tjsl = Tjsl::findOrFail($id);
        $tjsl->delete();

        return redirect()->route('tjsl.index')
            ->with('success', 'Program TJSL berhasil dihapus.');
    }

    // Method untuk menambah biaya
    public function addBiaya(Request $request, $id)
    {
        $request->validate([
            'jenis_biaya' => 'required|string',
            'nominal' => 'required|numeric|min:0',
            'keterangan' => 'nullable|string',
        ]);

        BiayaTjsl::create([
            'tjsl_id' => $id,
            'jenis_biaya' => $request->jenis_biaya,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tjsl.show', $id)
            ->with('success', 'Biaya berhasil ditambahkan.');
    }

    // Method untuk menambah publikasi
    public function addPublikasi(Request $request, $id)
    {
        $request->validate([
            'judul_publikasi' => 'required|string',
            'jenis_publikasi' => 'required|string',
            'tanggal_publikasi' => 'required|date',
            'media' => 'required|string',
            'url_publikasi' => 'nullable|url',
            'keterangan' => 'nullable|string',
        ]);

        PubTjsl::create([
            'tjsl_id' => $id,
            'judul_publikasi' => $request->judul_publikasi,
            'jenis_publikasi' => $request->jenis_publikasi,
            'tanggal_publikasi' => $request->tanggal_publikasi,
            'media' => $request->media,
            'url_publikasi' => $request->url_publikasi,
            'keterangan' => $request->keterangan,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tjsl.show', $id)
            ->with('success', 'Publikasi berhasil ditambahkan.');
    }

    // Method untuk menambah dokumen
    public function addDokumen(Request $request, $id)
    {
        $request->validate([
            'nama_dokumen' => 'required|string',
            'jenis_dokumen' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240',
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('tjsl_documents', $fileName, 'public');

        DocTjsl::create([
            'tjsl_id' => $id,
            'nama_dokumen' => $request->nama_dokumen,
            'jenis_dokumen' => $request->jenis_dokumen,
            'file_path' => $filePath,
            'file_name' => $fileName,
            'file_size' => $file->getSize(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tjsl.show', $id)
            ->with('success', 'Dokumen berhasil ditambahkan.');
    }

    // Method untuk menambah feedback
    public function addFeedback(Request $request, $id)
    {
        $request->validate([
            'nama_pemberi_feedback' => 'required|string',
            'email' => 'required|email',
            'feedback' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
        ]);

        FeedbackTjsl::create([
            'tjsl_id' => $id,
            'nama_pemberi_feedback' => $request->nama_pemberi_feedback,
            'email' => $request->email,
            'feedback' => $request->feedback,
            'rating' => $request->rating,
            'status' => 1, // aktif
            'tanggal_feedback' => now(),
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('tjsl.show', $id)
            ->with('success', 'Feedback berhasil ditambahkan.');
    }

    public function getUnitsByRegion(Request $request)
    {
        $units = Unit::where('region', $request->region)
            ->orderBy('unit')
            ->get(['id', 'unit']);

        dd($units);

        return response()->json($units);
    }
}
