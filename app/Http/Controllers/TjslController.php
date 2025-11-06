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
use App\Models\Anggaran;

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

        $tjsls = $query->orderBy('id', 'asc')->paginate(12);

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

        // Total realisasi dari biaya (tb_biaya_tjsl)
        $totalRealisasi = $tjsl->biayaTjsl->sum('realisasi');

        // Ambil sub_pilar_id dari tb_biaya_tjsl (abaikan null)
        $subPilarIdsCollection = $tjsl->biayaTjsl->pluck('sub_pilar_id')->filter()->unique();
        $hasSubPilarAnggaran = $subPilarIdsCollection->isNotEmpty();
        $subPilarIds = $subPilarIdsCollection->values()->all();

        // Tahun program dari tanggal_mulai (opsional)
        $programYear = $tjsl->tanggal_mulai ? $tjsl->tanggal_mulai->format('Y') : null;

        // Hitung total anggaran dari tb_anggaran_tjsl sesuai sub_pilar_id biaya
        $totalAnggaran = 0.0;
        if ($hasSubPilarAnggaran) {
            $queryAnggaran = Anggaran::whereIn('sub_pilar_id', $subPilarIds);
            
            if ($programYear) {
                $queryAnggaran->where('tahun', (string) $programYear);
            }
            $totalAnggaran = (float) $queryAnggaran->sum('anggaran');
        }

        // Persentase realisasi
        $persentaseRealisasi = $totalAnggaran > 0 ? ($totalRealisasi / $totalAnggaran) * 100 : 0;

        // Hitung persentase RKA (contoh)
        $persentaseRka = 0;
        $persentaseRka = 0;

        // Feedback data untuk gauge display (boolean-based)
        $latestFeedback = $tjsl->feedbackTjsl->last(); // Ambil feedback terbaru
        $feedbackStats = [
            'sangat_puas' => $latestFeedback ? $latestFeedback->sangat_puas : false,
            'puas' => $latestFeedback ? $latestFeedback->puas : false,
            'kurang_puas' => $latestFeedback ? $latestFeedback->kurang_puas : false,
            'saran' => $latestFeedback ? $latestFeedback->saran : null,
            'has_feedback' => $latestFeedback ? true : false,
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

        // Tambahkan: mapping kode lokasi_program ke nama wilayah
        $lokasiNames = [
            'provinsi' => null,
            'kabupaten' => null,
            'kecamatan' => null,
            'desa' => null,
        ];
        $kodeLokasi = trim($tjsl->lokasi_program ?? '');
        if ($kodeLokasi !== '') {
            $parts = strpos($kodeLokasi, '.') !== false ? explode('.', $kodeLokasi) : [$kodeLokasi];
    
            // Provinsi
            if (count($parts) >= 1) {
                $provCode = $parts[0];
                $prov = DB::table('wilayah')->where('kode', $provCode)->first();
                $lokasiNames['provinsi'] = $prov->nama ?? null;
            }
            // Kabupaten/Kota
            if (count($parts) >= 2) {
                $kabCode = $parts[0] . '.' . $parts[1];
                $kab = DB::table('wilayah')->where('kode', $kabCode)->first();
                $lokasiNames['kabupaten'] = $kab->nama ?? null;
            }
            // Kecamatan
            if (count($parts) >= 3) {
                $kecCode = $parts[0] . '.' . $parts[1] . '.' . $parts[2];
                $kec = DB::table('wilayah')->where('kode', $kecCode)->first();
                $lokasiNames['kecamatan'] = $kec->nama ?? null;
            }
            // Desa/Kelurahan
            if (count($parts) >= 4) {
                $desaCode = $parts[0] . '.' . $parts[1] . '.' . $parts[2] . '.' . $parts[3];
                $desa = DB::table('wilayah')->where('kode', $desaCode)->first();
                $lokasiNames['desa'] = $desa->nama ?? null;
            }
        }

        return view('tjsl.show', compact(
            'tjsl',
            'totalAnggaran',
            'totalRealisasi',
            'persentaseRealisasi',
            'persentaseRka',
            'feedbackStats',
            'publications',
            'documentations',
            'pilars',
            'hasSubPilarAnggaran',
            'lokasiNames' // kirim ke view
        ));
    }

    public function create()
    {
        $units = Unit::all();
        $pilars = Pilar::orderBy('pilar')->get();
        return view('tjsl.create', compact('units', 'pilars'));
    }

    public function store(Request $request)
    {
        // Debug logging
        \Log::info('TJSL Store Method Called', [
            'request_data' => $request->all(),
            'user_id' => Auth::id()
        ]);

        try {
            \Log::info('Starting validation process');

            $request->validate([
                // Tab 1 - Data Program (REQUIRED)
                'nama_program' => 'required|string|max:255',
                'unit_id' => 'required|exists:tb_unit,id',
                'pilar_id' => 'required|exists:m_pilar,id',
                'deskripsi' => 'required|string',
                'lokasi_program' => 'nullable|string|max:255',
                'latitude' => 'nullable|numeric|min:-90|max:90',
                'longitude' => 'nullable|numeric|min:-180|max:180',
                'program_unggulan_id' => 'nullable|exists:m_program_unggulan,id',
                'sub_pilar' => 'nullable|array',
                'sub_pilar.*' => 'nullable|exists:m_sub_pilar,id',
                'tanggal_mulai' => 'nullable|date',
                'tanggal_akhir' => 'nullable|date',
                'penerima_dampak' => 'nullable|string|max:255',
                'status' => 'nullable|integer',

                // Tab 2 - Data Biaya (NULLABLE)
                'biaya.*.sub_pilar_id' => 'nullable|exists:m_sub_pilar,id',
                'biaya.*.realisasi' => 'nullable|numeric|min:0',

                // Tab 3 - Data Publikasi (NULLABLE)
                'publikasi.*.media' => 'nullable|string|max:255',
                'publikasi.*.link' => 'nullable|url|max:500',

                // Tab 4 - Data Dokumentasi (NULLABLE)
                'proposal' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'izin_prinsip' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'survei_feedback' => 'nullable|file|mimes:pdf,doc,docx|max:10240',
                'foto' => 'nullable|file|mimes:jpg,jpeg,png,gif|max:5120',

                // Tab 5 - Data Feedback (NULLABLE)
                'feedback.*.sangat_puas' => 'nullable|string',
                'feedback.*.puas' => 'nullable|string',
                'feedback.*.kurang_puas' => 'nullable|string',
                'feedback.*.saran' => 'nullable|string',
            ]);

            \Log::info('Validation passed, starting transaction');
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed', [
                'errors' => $e->errors(),
                'message' => $e->getMessage()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Unexpected error during validation', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }

        try {
            \DB::beginTransaction();

            // Debug: Log validation passed
            \Log::info('TJSL validation passed, starting save process');

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

            \Log::info('TJSL main record created successfully', [
                'tjsl_id' => $tjsl->id,
                'nama_program' => $tjsl->nama_program
            ]);

            // Debug: Log after TJSL creation
            \Log::info('TJSL record created successfully', [
                'tjsl_id' => $tjsl->id,
                'nama_program' => $tjsl->nama_program
            ]);

            // 2. Simpan data biaya TJSL (dari array biaya) - SELALU BUAT RECORD KOSONG JIKA TIDAK ADA DATA
            if ($request->has('biaya') && is_array($request->biaya) && count($request->biaya) > 0) {
                \Log::info('Processing biaya data', [
                    'biaya_count' => count($request->biaya),
                    'biaya_data' => $request->biaya
                ]);

                foreach ($request->biaya as $biaya) {
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
            } else {
                // Buat record kosong untuk menjaga konsistensi ID
                $biayaTjsl = BiayaTjsl::create([
                    'tjsl_id' => $tjsl->id,
                    'sub_pilar_id' => null,
                    'realisasi' => 0,
                ]);
                \Log::info('Empty BiayaTjsl record created for consistency', ['biaya_id' => $biayaTjsl->id]);
            }

            // 3. Simpan data publikasi TJSL - SELALU BUAT RECORD KOSONG JIKA TIDAK ADA DATA
            if ($request->has('publikasi') && is_array($request->publikasi) && count($request->publikasi) > 0) {
                \Log::info('Processing publikasi data', [
                    'publikasi_count' => count($request->publikasi),
                    'publikasi_data' => $request->publikasi
                ]);

                foreach ($request->publikasi as $publikasi) {
                    $pubTjsl = PubTjsl::create([
                        'tjsl_id' => $tjsl->id,
                        'media' => $publikasi['media'] ?? null,
                        'link' => $publikasi['link'] ?? null,
                    ]);

                    \Log::info('PubTjsl created', [
                        'pub_id' => $pubTjsl->id,
                        'media' => $pubTjsl->media,
                        'link' => $pubTjsl->link
                    ]);
                }
            } else {
                // Buat record kosong untuk menjaga konsistensi ID
                $pubTjsl = PubTjsl::create([
                    'tjsl_id' => $tjsl->id,
                    'media' => null,
                    'link' => null,
                ]);
                \Log::info('Empty PubTjsl record created for consistency', ['pub_id' => $pubTjsl->id]);
            }

            // 4. Simpan data dokumentasi TJSL dengan upload file - SELALU BUAT RECORD KOSONG JIKA TIDAK ADA FILE
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

            // Selalu buat record dokumentasi, meskipun tidak ada file yang diupload
            $docTjsl = DocTjsl::create(array_merge([
                'tjsl_id' => $tjsl->id,
                'proposal' => null,
                'izin_prinsip' => null,
                'survei_feedback' => null,
                'foto' => null,
            ], $docData));

            \Log::info('DocTjsl created', [
                'doc_id' => $docTjsl->id,
                'files' => !empty($docData) ? array_keys($docData) : 'no files uploaded'
            ]);

            // 5. Simpan data feedback TJSL (dari array feedback) - SELALU BUAT RECORD KOSONG JIKA TIDAK ADA DATA
            if ($request->has('feedback') && is_array($request->feedback) && count($request->feedback) > 0) {
                \Log::info('Processing feedback data', [
                    'feedback_count' => count($request->feedback),
                    'feedback_data' => $request->feedback
                ]);

                foreach ($request->feedback as $feedbackData) {
                    // Initialize all feedback values to 0 (default for unchecked checkboxes)
                    $sangat_puas = 0;
                    $puas = 0;
                    $kurang_puas = 0;

                    // Handle feedback rating based on 'puas' field value
                    if (isset($feedbackData['puas'])) {
                        $puasValue = $feedbackData['puas'];
                        if ($puasValue == '1' || $puasValue == 1) {
                            $sangat_puas = 1; // Value 1 = Sangat Puas
                        } elseif ($puasValue == '2' || $puasValue == 2) {
                            $puas = 1; // Value 2 = Puas
                        } elseif ($puasValue == '3' || $puasValue == 3) {
                            $kurang_puas = 1; // Value 3 = Kurang Puas
                        }
                    }

                    // Handle individual boolean checkbox fields (if they exist)
                    if (isset($feedbackData['sangat_puas']) && ($feedbackData['sangat_puas'] == '1' || $feedbackData['sangat_puas'] === true)) {
                        $sangat_puas = 1;
                        $puas = 0;
                        $kurang_puas = 0;
                    }
                    if (isset($feedbackData['puas']) && !is_numeric($feedbackData['puas']) && ($feedbackData['puas'] == '1' || $feedbackData['puas'] === true)) {
                        $sangat_puas = 0;
                        $puas = 1;
                        $kurang_puas = 0;
                    }
                    if (isset($feedbackData['kurang_puas']) && ($feedbackData['kurang_puas'] == '1' || $feedbackData['kurang_puas'] === true)) {
                        $sangat_puas = 0;
                        $puas = 0;
                        $kurang_puas = 1;
                    }

                    $feedbackTjsl = FeedbackTjsl::create([
                        'tjsl_id' => $tjsl->id,
                        'sangat_puas' => $sangat_puas,
                        'puas' => $puas,
                        'kurang_puas' => $kurang_puas,
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
            } else {
                // Buat record kosong untuk menjaga konsistensi ID
                $feedbackTjsl = FeedbackTjsl::create([
                    'tjsl_id' => $tjsl->id,
                    'sangat_puas' => 0,
                    'puas' => 0,
                    'kurang_puas' => 0,
                    'saran' => null,
                ]);
                \Log::info('Empty FeedbackTjsl record created for consistency', ['feedback_id' => $feedbackTjsl->id]);
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
            'files' => $request->allFiles(),
            'lokasi_program' => $request->lokasi_program,
            'sub_pilar' => $request->sub_pilar
        ]);

        $tjsl = Tjsl::findOrFail($id);

        // Build validation rules dynamically
        $rules = [
            // Tab 1: Program Data (Required)
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

            // Tab 2-5: Optional fields (nullable for partial saving)
            'biaya.*.sub_pilar_id' => 'nullable|exists:m_sub_pilar,id',
            'biaya.*.realisasi' => 'nullable|numeric|min:0',
            'publikasi.*.media' => 'nullable|string|max:255',
            'publikasi.*.link' => 'nullable|url|max:500',
            'feedback.*.sangat_puas' => 'nullable|string',
            'feedback.*.puas' => 'nullable|string',
            'feedback.*.kurang_puas' => 'nullable|string',
            'feedback.*.saran' => 'nullable|string',
        ];

        // Only add file validation if file is actually present and valid
        if ($request->hasFile('proposal') && $request->file('proposal')->isValid()) {
            $rules['proposal'] = 'file|mimes:pdf,doc,docx|max:51200';
        }
        if ($request->hasFile('izin_prinsip') && $request->file('izin_prinsip')->isValid()) {
            $rules['izin_prinsip'] = 'file|mimes:pdf,doc,docx|max:51200';
        }
        if ($request->hasFile('survei_feedback') && $request->file('survei_feedback')->isValid()) {
            $rules['survei_feedback'] = 'file|mimes:pdf,doc,docx|max:51200';
        }
        if ($request->hasFile('foto') && $request->file('foto')->isValid()) {
            $rules['foto'] = 'file|mimes:jpg,jpeg,png,gif,webp|max:10240';
        }

        $request->validate($rules);

        try {
            \DB::beginTransaction();

            // Update data TJSL utama
            \Log::info('Before TJSL Update', [
                'lokasi_program' => $request->lokasi_program,
                'sub_pilar' => $request->sub_pilar,
                'sub_pilar_type' => gettype($request->sub_pilar),
                'sub_pilar_empty' => empty($request->sub_pilar)
            ]);

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

            \Log::info('After TJSL Update', [
                'lokasi_program_saved' => $tjsl->lokasi_program,
                'sub_pilar_saved' => $tjsl->sub_pilar
            ]);

            // Update biaya TJSL - Always create/update record to maintain ID consistency
            BiayaTjsl::where('tjsl_id', $tjsl->id)->delete();

            // Always create at least one BiayaTjsl record
            if ($request->has('biaya') && is_array($request->biaya) && !empty($request->biaya)) {
                foreach ($request->biaya as $biaya) {
                    BiayaTjsl::create([
                        'tjsl_id' => $tjsl->id,
                        'sub_pilar_id' => $biaya['sub_pilar_id'] ?? null,
                        'realisasi' => $biaya['realisasi'] ?? 0,
                    ]);
                }
            } else {
                // Create empty record to maintain ID consistency
                BiayaTjsl::create([
                    'tjsl_id' => $tjsl->id,
                    'sub_pilar_id' => null,
                    'realisasi' => 0,
                ]);
            }

            // Update publikasi TJSL - Always create/update record to maintain ID consistency
            PubTjsl::where('tjsl_id', $tjsl->id)->delete();

            // Always create at least one PubTjsl record
            if ($request->has('publikasi') && is_array($request->publikasi) && !empty($request->publikasi)) {
                foreach ($request->publikasi as $publikasi) {
                    PubTjsl::create([
                        'tjsl_id' => $tjsl->id,
                        'media' => $publikasi['media'] ?? null,
                        'link' => $publikasi['link'] ?? null,
                    ]);
                }
            } else {
                // Create empty record to maintain ID consistency
                PubTjsl::create([
                    'tjsl_id' => $tjsl->id,
                    'media' => null,
                    'link' => null,
                ]);
            }

            // Update dokumentasi TJSL - Always create/update record to maintain ID consistency
            $docTjsl = DocTjsl::where('tjsl_id', $tjsl->id)->first();
            $docData = ['tjsl_id' => $tjsl->id];

            // Handle file uploads untuk update
            if ($request->hasFile('proposal')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->proposal) {
                    \Storage::disk('public')->delete('dokumen/proposal/' . $docTjsl->proposal);
                }
                $docData['proposal'] = $this->handleFileUpload($request->file('proposal'), 'proposal', $tjsl->id);
            } else {
                $docData['proposal'] = $docTjsl ? $docTjsl->proposal : null;
            }

            if ($request->hasFile('izin_prinsip')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->izin_prinsip) {
                    \Storage::disk('public')->delete('dokumen/izin_prinsip/' . $docTjsl->izin_prinsip);
                }
                $docData['izin_prinsip'] = $this->handleFileUpload($request->file('izin_prinsip'), 'izin_prinsip', $tjsl->id);
            } else {
                $docData['izin_prinsip'] = $docTjsl ? $docTjsl->izin_prinsip : null;
            }

            if ($request->hasFile('survei_feedback')) {
                // Hapus file lama jika ada
                if ($docTjsl && $docTjsl->survei_feedback) {
                    \Storage::disk('public')->delete('dokumen/survei_feedback/' . $docTjsl->survei_feedback);
                }
                $docData['survei_feedback'] = $this->handleFileUpload($request->file('survei_feedback'), 'survei_feedback', $tjsl->id);
            } else {
                $docData['survei_feedback'] = $docTjsl ? $docTjsl->survei_feedback : null;
            }

            if ($request->hasFile('foto')) {
                \Log::info('Processing foto upload', [
                    'has_file' => true,
                    'file_size' => $request->file('foto')->getSize(),
                    'file_mime' => $request->file('foto')->getMimeType(),
                    'file_name' => $request->file('foto')->getClientOriginalName()
                ]);

                try {
                    // Hapus file lama jika ada
                    if ($docTjsl && $docTjsl->foto) {
                        \Storage::disk('public')->delete('dokumen/foto/' . $docTjsl->foto);
                    }
                    $docData['foto'] = $this->handleFileUpload($request->file('foto'), 'foto', $tjsl->id);
                    \Log::info('Foto uploaded successfully', ['filename' => $docData['foto']]);
                } catch (\Exception $e) {
                    \Log::error('Foto upload error', [
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    throw new \Exception('Foto upload failed: ' . $e->getMessage());
                }
            } else {
                $docData['foto'] = $docTjsl ? $docTjsl->foto : null;
            }

            // Always create or update DocTjsl record
            if ($docTjsl) {
                $docTjsl->update($docData);
            } else {
                DocTjsl::create($docData);
            }

            // Update feedback TJSL - Always create/update record to maintain ID consistency
            FeedbackTjsl::where('tjsl_id', $tjsl->id)->delete();

            // Always create at least one FeedbackTjsl record
            if ($request->has('feedback') && is_array($request->feedback) && !empty($request->feedback)) {
                foreach ($request->feedback as $feedbackData) {
                    FeedbackTjsl::create([
                        'tjsl_id' => $tjsl->id,
                        'sangat_puas' => isset($feedbackData['sangat_puas']) ? 1 : 0,
                        'puas' => isset($feedbackData['puas']) ? 1 : 0,
                        'kurang_puas' => isset($feedbackData['kurang_puas']) ? 1 : 0,
                        'saran' => $feedbackData['saran'] ?? null,
                    ]);
                }
            } else {
                // Create empty record to maintain ID consistency
                FeedbackTjsl::create([
                    'tjsl_id' => $tjsl->id,
                    'sangat_puas' => 0,
                    'puas' => 0,
                    'kurang_puas' => 0,
                    'saran' => null,
                ]);
            }

            \DB::commit();

            \Log::info('TJSL Update Success', [
                'tjsl_id' => $id,
                'updated_data' => $tjsl->fresh()->toArray()
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Program TJSL berhasil diperbarui',
                    'data' => $tjsl->fresh()
                ]);
            }

            return redirect()->route('tjsl.show', $tjsl->id)
                ->with('success', 'Program TJSL berhasil diperbarui.');

        } catch (\Exception $e) {
            \DB::rollback();
            \Log::error('TJSL update failed', [
                'tjsl_id' => $id,
                'error_message' => $e->getMessage(),
                'error_trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat memperbarui program TJSL: ' . $e->getMessage()
                ], 500);
            }

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

        try {
            // Validate file before upload
            if (!$file->isValid()) {
                \Log::error('Invalid file upload', [
                    'folder' => $folder,
                    'error' => $file->getErrorMessage()
                ]);
                throw new \Exception('File upload is not valid: ' . $file->getErrorMessage());
            }

            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $file->getClientOriginalExtension();

            // Sanitize filename
            $originalName = preg_replace('/[^A-Za-z0-9_\-]/', '_', $originalName);
            $filename = 'TJSL_' . $tjslId . '_' . $originalName . '_' . time() . '.' . $extension;

            // Ensure directory exists
            $directory = storage_path('app/public/dokumen/' . $folder);
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }

            $path = $file->storeAs('dokumen/' . $folder, $filename, 'public');

            if (!$path) {
                throw new \Exception('Failed to store file');
            }

            \Log::info('File uploaded successfully', [
                'folder' => $folder,
                'filename' => $filename,
                'tjsl_id' => $tjslId,
                'path' => $path
            ]);

            return $filename;
        } catch (\Exception $e) {
            \Log::error('File upload failed', [
                'folder' => $folder,
                'tjsl_id' => $tjslId,
                'error' => $e->getMessage(),
                'file_size' => $file->getSize(),
                'file_mime' => $file->getMimeType()
            ]);
            throw $e;
        }
    }
}
