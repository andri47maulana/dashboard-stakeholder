<?php

namespace App\Http\Controllers;

use App\Models\Tjsl;
use App\Models\BiayaTjsl;
use App\Models\PubTjsl;
use App\Models\DocTjsl;
use App\Models\Pilar;
use App\Models\SubPilar;
use App\Models\FeedbackTjsl;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $subpilars = \App\Models\SubPilar::orderBy('sub_pilar')->get();

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

        return view('tjsl.index', compact('tjsls', 'pilars', 'subpilars', 'units', 'regions', 'tahunList'));
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
        // Debug logging
        \Log::info('TJSL Store Method Called', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        $request->validate([
            'nama_program' => 'required|string|max:255',
            'unit_id' => 'required|exists:tb_unit,id',
            'pilar_id' => 'required|exists:m_pilar,id',
            'deskripsi' => 'nullable|string',
            'lokasi_program' => 'nullable|string|max:255',
            'sub_pilar' => 'nullable|array',
            'sub_pilar.*' => 'exists:m_sub_pilar,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'penerima_dampak' => 'nullable|string|max:255',
            'tpb' => 'nullable|string|max:255',
            'status' => 'nullable|integer',

            // Validasi untuk data terkait
            'biaya.*.anggaran' => 'nullable|numeric|min:0',
            'biaya.*.realisasi' => 'nullable|numeric|min:0',
            'publikasi.*.media' => 'nullable|string|max:255',
            'publikasi.*.link' => 'nullable|url|max:500',
            'dokumentasi.*.nama_dokumen' => 'nullable|string|max:255',
            'dokumentasi.*.link' => 'nullable|url|max:500',
            'feedback.*.sangat_puas' => 'nullable|numeric|min:0|max:100',
            'feedback.*.puas' => 'nullable|numeric|min:0|max:100',
            'feedback.*.kurang_puas' => 'nullable|numeric|min:0|max:100',
            'feedback.*.saran' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // Debug: Log before creating TJSL
            \Log::info('Creating TJSL record', [
                'nama_program' => $request->nama_program,
                'unit_id' => $request->unit_id,
                'pilar_id' => $request->pilar_id,
                'sub_pilar' => $request->sub_pilar
            ]);

            // 1. Simpan data TJSL utama
            $tjsl = Tjsl::create([
                'nama_program' => $request->nama_program,
                'deskripsi' => $request->deskripsi,
                'unit_id' => $request->unit_id,
                'lokasi_program' => $request->lokasi_program,
                'pilar_id' => $request->pilar_id,
                'sub_pilar' => is_array($request->sub_pilar) ? json_encode($request->sub_pilar) : $request->sub_pilar,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'penerima_dampak' => $request->penerima_dampak,
                'tpb' => $request->tpb,
                'status' => $request->status ?? 1,
                'created_by' => Auth::id(),
            ]);

            // Debug: Log after TJSL creation
            \Log::info('TJSL record created successfully', [
                'tjsl_id' => $tjsl->id,
                'nama_program' => $tjsl->nama_program
            ]);

            // 2. Simpan data biaya TJSL
            if ($request->has('biaya')) {
                foreach ($request->biaya as $biaya) {
                    if (!empty($biaya['anggaran']) || !empty($biaya['realisasi'])) {
                        BiayaTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'anggaran' => $biaya['anggaran'] ?? 0,
                            'realisasi' => $biaya['realisasi'] ?? 0,
                        ]);
                    }
                }
            }

            // 3. Simpan data publikasi TJSL
            if ($request->has('publikasi')) {
                foreach ($request->publikasi as $publikasi) {
                    if (!empty($publikasi['media']) || !empty($publikasi['link'])) {
                        PubTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'media' => $publikasi['media'],
                            'link' => $publikasi['link'],
                        ]);
                    }
                }
            }

            // 4. Simpan data dokumentasi TJSL
            if ($request->has('dokumentasi')) {
                foreach ($request->dokumentasi as $dokumentasi) {
                    if (!empty($dokumentasi['nama_dokumen']) || !empty($dokumentasi['link'])) {
                        DocTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'nama_dokumen' => $dokumentasi['nama_dokumen'],
                            'link' => $dokumentasi['link'],
                        ]);
                    }
                }
            }

            // 5. Simpan data feedback TJSL
            if ($request->has('feedback')) {
                foreach ($request->feedback as $feedback) {
                    if (!empty($feedback['sangat_puas']) || !empty($feedback['puas']) ||
                        !empty($feedback['kurang_puas']) || !empty($feedback['saran'])) {
                        FeedbackTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'sangat_puas' => $feedback['sangat_puas'] ?? 0,
                            'puas' => $feedback['puas'] ?? 0,
                            'kurang_puas' => $feedback['kurang_puas'] ?? 0,
                            'saran' => $feedback['saran'],
                        ]);
                    }
                }
            }

            \DB::commit();

            // Debug: Log successful completion
            \Log::info('TJSL store completed successfully', [
                'tjsl_id' => $tjsl->id
            ]);

            return redirect()->route('tjsl.index')
                ->with('success', 'Program TJSL lengkap berhasil disimpan!');

        } catch (\Exception $e) {
            \DB::rollback();

            // Debug: Log error details
            \Log::error('TJSL store failed', [
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage());
        }
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
        try {
            DB::beginTransaction();

            $tjsl = Tjsl::findOrFail($id);

            // Delete related data first
            BiayaTjsl::where('tjsl_id', $id)->delete();
            PubTjsl::where('tjsl_id', $id)->delete();
            DocTjsl::where('tjsl_id', $id)->delete();
            FeedbackTjsl::where('tjsl_id', $id)->delete();

            // Delete the main TJSL record
            $tjsl->delete();

            DB::commit();

            return redirect()->route('tjsl.index')
                ->with('success', 'Program TJSL berhasil dihapus beserta semua data terkait.');

        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error deleting TJSL ID ' . $id . ': ' . $e->getMessage());

            return redirect()->route('tjsl.index')
                ->with('error', 'Terjadi kesalahan saat menghapus program: ' . $e->getMessage());
        }
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

    public function getEditData($id)
    {
        try {
            Log::info('Getting edit data for TJSL ID: ' . $id);

            $tjsl = Tjsl::with(['biayaTjsl', 'pubTjsl', 'docTjsl', 'feedbackTjsl'])
                ->findOrFail($id);

            // Format dates for form inputs
            $tjsl->tanggal_mulai = $tjsl->tanggal_mulai ? $tjsl->tanggal_mulai->format('Y-m-d') : '';
            $tjsl->tanggal_akhir = $tjsl->tanggal_akhir ? $tjsl->tanggal_akhir->format('Y-m-d') : '';

            // Convert sub_pilar to array if it's a string
            if (is_string($tjsl->sub_pilar)) {
                $tjsl->sub_pilar = json_decode($tjsl->sub_pilar, true) ?: [];
            }

            // Rename relationships to match frontend expectations
            $tjsl->biaya = $tjsl->biayaTjsl;
            $tjsl->publikasi = $tjsl->pubTjsl;
            $tjsl->dokumentasi = $tjsl->docTjsl;
            $tjsl->feedback = $tjsl->feedbackTjsl;

            // Remove the original relationship data to avoid confusion
            unset($tjsl->biayaTjsl, $tjsl->pubTjsl, $tjsl->docTjsl, $tjsl->feedbackTjsl);

            Log::info('Successfully retrieved edit data for TJSL ID: ' . $id);

            return response()->json($tjsl);
        } catch (\Exception $e) {
            Log::error('Error getting edit data for TJSL ID ' . $id . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load data'], 500);
        }
    }

    public function getUnitsByRegion(Request $request)
    {
        $units = Unit::where('region', $request->region)
            ->orderBy('unit')
            ->get(['id', 'unit']);



        return response()->json($units);
    }
}
