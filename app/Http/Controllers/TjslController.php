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
        $subpilars = \App\Models\SubPilar::orderBy('id')->get();

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
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',
            'program_unggulan_id' => 'nullable|exists:m_program_unggulan,id',
            'sub_pilar' => 'nullable|array',
            'sub_pilar.*' => 'exists:m_sub_pilar,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'penerima_dampak' => 'nullable|string|max:255',
            'status' => 'nullable|integer',

            // Validasi untuk data terkait
            'biaya.*.sub_pilar_id' => 'nullable|exists:m_sub_pilar,id',
            'biaya.*.realisasi' => 'nullable|numeric|min:0',
            'publikasi.*.media' => 'nullable|string|max:255',
            'publikasi.*.link' => 'nullable|url|max:500',

            // Validasi untuk file dokumentasi
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'izin_prinsip' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'survei_feedback' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',

            // Validasi untuk feedback array
            'feedback.*.sangat_puas' => 'nullable|string',
            'feedback.*.puas' => 'nullable|string',
            'feedback.*.kurang_puas' => 'nullable|string',
            'feedback.*.saran' => 'nullable|string',
        ]);

        \Log::info('Validation passed, starting transaction');

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
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'pilar_id' => $request->pilar_id,
                'program_unggulan_id' => $request->program_unggulan_id,
                'sub_pilar' => is_array($request->sub_pilar) ? json_encode($request->sub_pilar) : $request->sub_pilar,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'penerima_dampak' => $request->penerima_dampak,
                'status' => $request->status ?? 1,
                'created_by' => Auth::id(),
            ]);

            // Debug: Log after TJSL creation
            \Log::info('TJSL record created successfully', [
                'tjsl_id' => $tjsl->id,
                'nama_program' => $tjsl->nama_program
            ]);

            // 2. Simpan data biaya TJSL (dari array biaya)
            if ($request->has('biaya') && is_array($request->biaya)) {
                \Log::info('Processing biaya data', [
                    'biaya_count' => count($request->biaya),
                    'biaya_data' => $request->biaya
                ]);

                foreach ($request->biaya as $biaya) {
                    if (!empty($biaya['realisasi'])) {
                        $biayaTjsl = BiayaTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'sub_pilar_id' => $biaya['sub_pilar_id'] ?? null,
                            'realisasi' => $biaya['realisasi'] ?? 0,
                        ]);

                        \Log::info('BiayaTjsl created', [
                            'biaya_id' => $biayaTjsl->id,
                            'sub_pilar_id' => $biayaTjsl->sub_pilar_id,
                            'realisasi' => $biayaTjsl->realisasi
                        ]);
                    }
                }
            }

            // 3. Simpan data publikasi TJSL
            if ($request->has('publikasi')) {
                \Log::info('Processing publikasi data', [
                    'publikasi_count' => count($request->publikasi),
                    'publikasi_data' => $request->publikasi
                ]);

                foreach ($request->publikasi as $publikasi) {
                    if (!empty($publikasi['media']) || !empty($publikasi['link'])) {
                        $pubTjsl = PubTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'media' => $publikasi['media'],
                            'link' => $publikasi['link'],
                        ]);

                        \Log::info('PubTjsl created', [
                            'pub_id' => $pubTjsl->id,
                            'media' => $pubTjsl->media,
                            'link' => $pubTjsl->link
                        ]);
                    }
                }
            }

            // 4. Simpan data dokumentasi TJSL dengan upload file
            $docData = [];
            \Log::info('Processing documentation files', [
                'has_proposal' => $request->hasFile('proposal'),
                'has_izin_prinsip' => $request->hasFile('izin_prinsip'),
                'has_survei_feedback' => $request->hasFile('survei_feedback'),
                'has_foto' => $request->hasFile('foto')
            ]);

            // Helper function untuk upload file
            $uploadFile = function($file, $folder) use ($tjsl) {
                if ($file) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $filename = 'TJSL_' . $tjsl->id . '_' . $originalName . '_' . time() . '.' . $extension;
                    $path = $file->storeAs('dokumen/' . $folder, $filename, 'public');
                    return $filename;
                }
                return null;
            };

            if ($request->hasFile('proposal')) {
                $docData['proposal'] = $uploadFile($request->file('proposal'), 'proposal');
                \Log::info('Proposal file uploaded', ['filename' => $docData['proposal']]);
            }
            if ($request->hasFile('izin_prinsip')) {
                $docData['izin_prinsip'] = $uploadFile($request->file('izin_prinsip'), 'izin_prinsip');
                \Log::info('Izin prinsip file uploaded', ['filename' => $docData['izin_prinsip']]);
            }
            if ($request->hasFile('survei_feedback')) {
                $docData['survei_feedback'] = $uploadFile($request->file('survei_feedback'), 'survei_feedback');
                \Log::info('Survei feedback file uploaded', ['filename' => $docData['survei_feedback']]);
            }
            if ($request->hasFile('foto')) {
                $docData['foto'] = $uploadFile($request->file('foto'), 'foto');
                \Log::info('Foto file uploaded', ['filename' => $docData['foto']]);
            }

            if (!empty($docData)) {
                $docTjsl = DocTjsl::create(array_merge([
                    'tjsl_id' => $tjsl->id,
                ], $docData));

                \Log::info('DocTjsl created', [
                    'doc_id' => $docTjsl->id,
                    'files' => array_keys($docData)
                ]);
            }

            // 5. Simpan data feedback TJSL (dari array feedback)
            if ($request->has('feedback') && is_array($request->feedback)) {
                \Log::info('Processing feedback data', [
                    'feedback_count' => count($request->feedback),
                    'feedback_data' => $request->feedback
                ]);

                $feedbackData = $request->feedback[0]; // Ambil feedback pertama
                if (!empty($feedbackData['sangat_puas']) || !empty($feedbackData['puas']) ||
                    !empty($feedbackData['kurang_puas']) || !empty($feedbackData['saran'])) {
                    $feedbackTjsl = FeedbackTjsl::create([
                        'tjsl_id' => $tjsl->id,
                        'sangat_puas' => isset($feedbackData['sangat_puas']) ? 1 : 0,
                        'puas' => isset($feedbackData['puas']) ? 1 : 0,
                        'kurang_puas' => isset($feedbackData['kurang_puas']) ? 1 : 0,
                        'saran' => $feedbackData['saran'] ?? null,
                    ]);

                    \Log::info('FeedbackTjsl created', [
                        'feedback_id' => $feedbackTjsl->id,
                        'sangat_puas' => $feedbackTjsl->sangat_puas,
                        'puas' => $feedbackTjsl->puas,
                        'kurang_puas' => $feedbackTjsl->kurang_puas,
                        'saran' => $feedbackTjsl->saran
                    ]);
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
        \Log::info('TJSL Update Method Called', [
            'tjsl_id' => $id,
            'request_data' => $request->all(),
            'files' => $request->allFiles()
        ]);

        $tjsl = Tjsl::findOrFail($id);

        $request->validate([
            'nama_program' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'unit_id' => 'required|exists:tb_unit,id',
            'lokasi_program' => 'nullable|string|max:255',
            'pilar_id' => 'required|exists:m_pilar,id',
            'program_unggulan_id' => 'nullable|exists:m_program_unggulan,id',
            'sub_pilar' => 'nullable|array',
            'sub_pilar.*' => 'exists:m_sub_pilar,id',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
            'penerima_dampak' => 'nullable|string|max:255',
            'status' => 'nullable|integer',
            'latitude' => 'nullable|numeric|min:-90|max:90',
            'longitude' => 'nullable|numeric|min:-180|max:180',

            // Validasi untuk data biaya array
            'biaya.*.sub_pilar_id' => 'nullable|exists:m_sub_pilar,id',
            'biaya.*.realisasi' => 'nullable|numeric|min:0',

            // Validasi untuk publikasi array
            'publikasi.*.media' => 'nullable|string|max:255',
            'publikasi.*.link' => 'nullable|url|max:500',

            // Validasi untuk file dokumentasi
            'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'izin_prinsip' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'survei_feedback' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
            'foto' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',

            // Validasi untuk feedback array
            'feedback.*.sangat_puas' => 'nullable|string',
            'feedback.*.puas' => 'nullable|string',
            'feedback.*.kurang_puas' => 'nullable|string',
            'feedback.*.saran' => 'nullable|string',
        ]);

        try {
            \DB::beginTransaction();

            // Update data TJSL utama
            $tjsl->update([
                'nama_program' => $request->nama_program,
                'deskripsi' => $request->deskripsi,
                'unit_id' => $request->unit_id,
                'lokasi_program' => $request->lokasi_program,
                'pilar_id' => $request->pilar_id,
                'program_unggulan_id' => $request->program_unggulan_id,
                'sub_pilar' => $request->sub_pilar ? json_encode($request->sub_pilar) : null,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'penerima_dampak' => $request->penerima_dampak,
                'status' => $request->status,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'updated_by' => Auth::id(),
            ]);

            // Update biaya TJSL (hapus yang lama, tambah yang baru)
            BiayaTjsl::where('tjsl_id', $tjsl->id)->delete();
            if ($request->has('biaya') && is_array($request->biaya)) {
                foreach ($request->biaya as $biaya) {
                    if (!empty($biaya['realisasi'])) {
                        BiayaTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'sub_pilar_id' => $biaya['sub_pilar_id'] ?? null,
                            'realisasi' => $biaya['realisasi'] ?? 0,
                        ]);
                    }
                }
            }

            // Update publikasi TJSL (hapus yang lama, tambah yang baru)
            PubTjsl::where('tjsl_id', $tjsl->id)->delete();
            if ($request->has('publikasi') && is_array($request->publikasi)) {
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

            // Update dokumentasi TJSL
            $docTjsl = DocTjsl::where('tjsl_id', $tjsl->id)->first();
            $docData = [];

            // Handle file uploads untuk update
            if ($request->hasFile('proposal')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->proposal) {
                    \Storage::disk('public')->delete('dokumen/proposal/' . $docTjsl->proposal);
                }
                $docData['proposal'] = $this->handleFileUpload($request->file('proposal'), 'proposal', $tjsl->id);
            }
            
            if ($request->hasFile('izin_prinsip')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->izin_prinsip) {
                    \Storage::disk('public')->delete('dokumen/izin_prinsip/' . $docTjsl->izin_prinsip);
                }
                $docData['izin_prinsip'] = $this->handleFileUpload($request->file('izin_prinsip'), 'izin_prinsip', $tjsl->id);
            }
            
            if ($request->hasFile('survei_feedback')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->survei_feedback) {
                    \Storage::disk('public')->delete('dokumen/survei_feedback/' . $docTjsl->survei_feedback);
                }
                $docData['survei_feedback'] = $this->handleFileUpload($request->file('survei_feedback'), 'survei_feedback', $tjsl->id);
            }
            
            if ($request->hasFile('foto')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->foto) {
                    \Storage::disk('public')->delete('dokumen/foto/' . $docTjsl->foto);
                }
                $docData['foto'] = $this->handleFileUpload($request->file('foto'), 'foto', $tjsl->id);
            }

            if (!empty($docData)) {
                $docData['tjsl_id'] = $tjsl->id;
                if ($docTjsl) {
                    $docTjsl->update($docData);
                } else {
                    DocTjsl::create($docData);
                }
            }

            // Update feedback TJSL (hapus yang lama, tambah yang baru)
            FeedbackTjsl::where('tjsl_id', $tjsl->id)->delete();
            if ($request->has('feedback') && is_array($request->feedback)) {
                foreach ($request->feedback as $feedbackData) {
                    if (!empty($feedbackData['sangat_puas']) || !empty($feedbackData['puas']) || 
                        !empty($feedbackData['kurang_puas']) || !empty($feedbackData['saran'])) {
                        FeedbackTjsl::create([
                            'tjsl_id' => $tjsl->id,
                            'sangat_puas' => isset($feedbackData['sangat_puas']) ? 1 : 0,
                            'puas' => isset($feedbackData['puas']) ? 1 : 0,
                            'kurang_puas' => isset($feedbackData['kurang_puas']) ? 1 : 0,
                            'saran' => $feedbackData['saran'] ?? null,
                        ]);
                    }
                }
            }

            \DB::commit();

            \Log::info('TJSL Update Success', [
                'tjsl_id' => $id,
                'updated_data' => $tjsl->fresh()->toArray()
            ]);

            return redirect()->route('tjsl.show', $tjsl->id)
                ->with('success', 'Program TJSL berhasil diperbarui.');

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('TJSL update failed', [
                'tjsl_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat memperbarui program TJSL: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $tjsl = Tjsl::findOrFail($id);

            // Hapus file dokumentasi jika ada
            $docTjsl = DocTjsl::where('tjsl_id', $id)->first();
            if ($docTjsl) {
                if ($docTjsl->proposal && \Storage::disk('public')->exists('dokumen/proposal/' . $docTjsl->proposal)) {
                    \Storage::disk('public')->delete('dokumen/proposal/' . $docTjsl->proposal);
                }
                if ($docTjsl->izin_prinsip && \Storage::disk('public')->exists('dokumen/izin_prinsip/' . $docTjsl->izin_prinsip)) {
                    \Storage::disk('public')->delete('dokumen/izin_prinsip/' . $docTjsl->izin_prinsip);
                }
                if ($docTjsl->survei_feedback && \Storage::disk('public')->exists('dokumen/survei_feedback/' . $docTjsl->survei_feedback)) {
                    \Storage::disk('public')->delete('dokumen/survei_feedback/' . $docTjsl->survei_feedback);
                }
                if ($docTjsl->foto && \Storage::disk('public')->exists('dokumen/foto/' . $docTjsl->foto)) {
                    \Storage::disk('public')->delete('dokumen/foto/' . $docTjsl->foto);
                }
            }

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

    // Method untuk menambah publikasi
    public function addPublikasi(Request $request, $id)
    {
        $request->validate([
            'media' => 'required|string',
            'link' => 'nullable|url',
         ]);

        PubTjsl::create([
            'tjsl_id' => $id,
            'media' => $request->media,
            'link' => $request->link,
        ]);

        return redirect()->route('tjsl.show', $id)
            ->with('success', 'Publikasi berhasil ditambahkan.');
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

    /**
     * Handle file upload for TJSL documents
     */
    private function handleFileUpload($file, $folder, $tjslId)
    {
        if (!$file) {
            return null;
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $filename = 'TJSL_' . $tjslId . '_' . $originalName . '_' . time() . '.' . $extension;
        
        $path = $file->storeAs('dokumen/' . $folder, $filename, 'public');
        
        \Log::info('File uploaded successfully', [
            'folder' => $folder,
            'filename' => $filename,
            'tjsl_id' => $tjslId
        ]);
        
        return $filename;
    }
}
