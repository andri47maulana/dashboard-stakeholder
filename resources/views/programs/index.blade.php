@extends('layouts.app')
 
@section('content')


<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Menu Stakeholder</h1>
{{-- <form method="GET" action="{{ url()->current() }}">
<div class="card shadow mb-4">
<div class="card-body row" style="font-size: 0.85em;">
    @if(Auth::user()->hakakses == 'Admin')
    <div class="col-md-3">
        <div class="form-group row">
            <label for="region" class="col-md-4 control-label text-end"><strong>Region</strong></label>
            <select name="region" id="region" class="col-sm-7 form-control">
                <option value="">Pilih..</option>
                @php
                    $regions = collect($datakebun)->pluck('region')->unique();
                @endphp
                @foreach($regions as $region)
                    <option value="{{ $region }}" {{ $searchregion == $region ? 'selected' : '' }}>
                        {{ $region }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
    @endif

    <div class="col-md-3">
        <div class="form-group row">
            <label for="kebun" class="col-md-4 control-label text-end"><strong>Kebun</strong></label>
            <select name="kebun" id="kebun" class="col-sm-7 form-control">
                <option value="">Pilih..</option>
                @foreach($datakebun as $item)
                    @if(!$searchregion || $item->region == $searchregion)
                        <option value="{{ $item->unit }}" {{ $searchkebun == $item->unit ? 'selected' : '' }}>
                            {{ $item->unit }}
                        </option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group row">
            <label for="searchprogram" class="col-md-4 control-label text-end"><strong>Program</strong></label>
            <select name="searchprogram" id="searchprogram" class="col-sm-7 form-control">
                <option value="">Pilih..</option>
                @foreach($programtjsl as $itemprogram)
                    <option value="{{ $itemprogram->program }}" {{ $searchprogram == $itemprogram->program ? 'selected' : '' }}>
                        {{ $itemprogram->program }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-3">
        <div class="form-group row">
            <label for="tahun" class="col-md-4 control-label text-end"><strong>Tahun</strong></label>
            <select name="tahun" id="tahun" class="col-sm-7 form-control">
                <option value="">Pilih..</option>
                @foreach($tahunfilter as $filtertahun)
                    <option value="{{ $filtertahun->tahun }}" {{ $searchtahun == $filtertahun->tahun ? 'selected' : '' }}>
                        {{ $filtertahun->tahun }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="card-footer text-end">
    <a href="{{ url()->current() }}" class="btn btn-outline-warning btn-sm float-right" style="margin-right: 10px;">
        <i class="fas fa-fw fa-stop"></i> Batalkan
    </a>
    <button type="submit" class="btn btn-outline-success btn-sm float-right" style="margin-right: 10px;">
        <i class="fas fa-fw fa-filter"></i> Filter
    </button>
</div>
</div>
</form> --}}
@if (session('success'))
  <div class="alert alert-success">
    {{ session('success') }}
  </div>
@endif

@if (session('error'))
  <div class="alert alert-danger">
    {{ session('error') }}
  </div>
@endif
<form method="GET" action="{{ url()->current() }}">
<div class="card shadow mb-4">
    <div class="card-body row" style="font-size: 0.85em;">
        @if(Auth::user()->hakakses == 'Admin')
        <div class="col-md-3">
            <div class="form-group row">
                <label for="region" class="col-md-4 control-label text-end"><strong>Region</strong></label>
                <select name="region" id="region" class="col-sm-7 form-control region-select">
                    <option value="">Pilih..</option>
                    @php
                        $regions = collect($datakebun)->pluck('region')->unique();
                    @endphp
                    @foreach($regions as $region)
                        <option value="{{ $region }}" {{ $searchregion == $region ? 'selected' : '' }}>
                            {{ $region }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        @endif

        <div class="col-md-3">
            <div class="form-group row">
                <label for="kebun" class="col-md-4 control-label text-end"><strong>Kebun</strong></label>
                <select name="kebun" id="kebun" class="col-sm-7 form-control kebun-select">
                    <option value="">Pilih..</option>
                    @foreach($datakebun as $item)
                        @if(!$searchregion || $item->region == $searchregion)
                            <option value="{{ $item->unit }}" {{ $searchkebun == $item->unit ? 'selected' : '' }}>
                                {{ $item->unit }}
                            </option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group row">
                <label for="searchprogram" class="col-md-4 control-label text-end"><strong>Program</strong></label>
                <select name="searchprogram" id="searchprogram" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    @foreach($programtjsl as $itemprogram)
                        <option value="{{ $itemprogram->program }}" {{ $searchprogram == $itemprogram->program ? 'selected' : '' }}>
                            {{ $itemprogram->program }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="col-md-3">
            <div class="form-group row">
                <label for="tahun" class="col-md-4 control-label text-end"><strong>Tahun</strong></label>
                <select name="tahun" id="tahun" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    @foreach($tahunfilter as $filtertahun)
                        <option value="{{ $filtertahun->tahun }}" {{ $searchtahun == $filtertahun->tahun ? 'selected' : '' }}>
                            {{ $filtertahun->tahun }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="card-footer text-end">
        <a href="{{ url()->current() }}" class="btn btn-outline-warning btn-sm float-right" style="margin-right: 10px;">
            <i class="fas fa-fw fa-stop"></i> Batalkan
        </a>
        <button type="submit" class="btn btn-outline-success btn-sm float-right" style="margin-right: 10px;">
            <i class="fas fa-fw fa-filter"></i> Filter
        </button>
    </div>
</div>
</form>

<div class="card shadow mb-4">
    <div class="card-body row align-items-center" style="font-size: 0.85em;">
        <div class="col-md-6">
            <button type="button" class="btn btn-sm btn-primary btn-block" data-toggle="modal" data-target="#modalTambahProgram">
                <i class="fas fa-plus"></i> Tambah
            </button>
        </div>

        <div class="col-md-6 text-end">
           
            <a href="{{ url('/programs/export') }}" class="btn btn-sm btn-success  btn-block">
                <i class="fas fa-file-excel"></i> Export
            </a>
        </div>
    </div>
</div>


<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            @forelse ($programs as $program)
                @php
                    $pilarColors = [
                        'Lingkungan' => 'bg-green-100 text-green-800',
                        'Sosial' => 'bg-red-100 text-red-800',
                        'Pendidikan' => 'bg-blue-100 text-blue-800',
                        'Ekonomi' => 'bg-yellow-100 text-yellow-800',
                    ];

                    $statusColors = [
                        'Active' => 'text-blue-600',
                        'Proposed' => 'text-orange-600',
                        'Completed' => 'text-green-600',
                    ];

                    $statusIcons = [
                        'Active' => '<i class="fas fa-hourglass-start" style="color: #2563eb;"></i>',
                        'Proposed' => '<i class="fas fa-spinner fa-spin" style="color: #f59e0b;"></i>',
                        'Completed' => '<i class="fas fa-check-circle" style="color: #16a34a;"></i>',
                    ];

                    // Pecah gambar TPB (hasil GROUP_CONCAT)
                    $gambarTpbs = $program->gambar_tpbs ? explode(',', $program->gambar_tpbs) : [];
                @endphp

                <div class="col-md-4 mb-3">
                    <div class="card-body border border-primary" style="border: 2px solid #007bff; border-radius: 10px; height: 250px;">
                        <div class="row">
                            <div class="col-12">
                                <!-- 🔹 Judul & Tombol Edit sejajar -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">{{ $program->nama_program }}</h6>

                                    <div class="btn-group">
                                        <button class="btn btn-warning btn-sm btnEditProgram" 
                                                data-id="{{ $program->id }}" 
                                                style="padding: 2px 5px; font-size: 0.55rem;">
                                            <i class="fas fa-edit"></i>
                                        </button>

                                        <button class="btn btn-danger btn-sm btnDeleteProgram" 
                                                data-id="{{ $program->id }}" 
                                                style="padding: 2px 5px; font-size: 0.55rem;">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Pilar dan Status sejajar -->
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge text-primary"
                                        style="background-color: #e0f0ff; border-radius: 10px; padding: 5px 10px;">
                                        {{ $program->pilar }}
                                    </span>

                                    <div class="d-flex align-items-center font-semibold {{ $statusColors[$program->status] ?? 'text-gray-600' }}">
                                        {!! $statusIcons[$program->status] ?? '<i class="fas fa-circle-question text-gray-500"></i>' !!}
                                        <span class="ms-1">{{ $program->status }}</span>
                                    </div>
                                </div>

                                <!-- Gambar Program dan TPB -->
                                <div class="d-flex flex-wrap align-items-center gap-2 mt-2">
                                    <!-- Gambar utama program -->
                                    @if(!empty($program->gambar_program))
                                        <img src="{{ asset('img/' . $program->gambar_program) }}"
                                            alt="{{ $program->nama_program }}"
                                            class="img-fluid rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @endif

                                    <!-- Loop gambar TPB -->
                                    @foreach ($gambarTpbs as $gbr)
                                        <img src="{{ asset('img/' . trim($gbr)) }}"
                                            alt="TPB"
                                            class="img-fluid rounded"
                                            style="width: 50px; height: 50px; object-fit: cover;">
                                    @endforeach
                                </div>

                                <!-- Tanggal -->
                                <div class="mt-2">
                                    <p style="margin-bottom: 4px; line-height: 1.2;">
                                        <span class="fw-semibold">Tanggal mulai :</span>
                                        {{ \Carbon\Carbon::parse($program->tgl_mulai)->isoFormat('DD MMMM YYYY') }}
                                    </p>
                                    <p style="margin-bottom: 0; line-height: 1.2;">
                                        <span class="fw-semibold">Tanggal selesai :</span>
                                        {{ \Carbon\Carbon::parse($program->tgl_selesai)->isoFormat('DD MMMM YYYY') }}
                                    </p>
                                </div>

                                <!-- Tombol di tabel -->
                                <a href="#" class="btn btn-primary btn-sm btnLihatProgram mt-2 w-100" data-id="{{ $program->id}}">
                                Lihat Program
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5 bg-white rounded-lg shadow-sm border">
                    <p class="text-gray-500 mb-0">Tidak ada program yang ditemukan.</p>
                </div>
            @endforelse
        </div>

        {{-- 🔹 Pagination --}}
        <div class="d-flex justify-content-center mt-3">
            {{ $programs->links('pagination::bootstrap-5') }}
        </div>
    </div>
</div>


{{-- Modal Tambah --}}
<div class="modal fade" id="modalTambahProgram" tabindex="-1" aria-labelledby="modalTambahProgramLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalTambahProgramLabel">Tambah Program TJSL</h5>
        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">X</button>
      </div>

      <form id="formTambahProgram" method="POST" action="{{ route('program-tjsl.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
          {{-- Tab Navigasi --}}
          <ul class="nav nav-tabs" id="programTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="data-utama-tab" data-toggle="tab" href="#dataUtama" role="tab">📋 Data Utama</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="feedback-tab" data-toggle="tab" href="#feedbackTab" role="tab">📸 Feedback Program</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="media-tab" data-toggle="tab" href="#mediaTab" role="tab">📸 Media & Laporan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="publikasi-tab" data-toggle="tab" href="#publikasiTab" role="tab">📰 Publikasi</a>
            </li>
          </ul>

          <div class="tab-content mt-3" id="programTabsContent">

            {{-- TAB 1: DATA UTAMA --}}
            <div class="tab-pane fade show active" id="dataUtama" role="tabpanel">
              <div class="row">
                <div class="col-md-6 mb-3">
                  <label><strong>Region</strong></label>
                  <select name="regional" id="region_modal" class="form-control region-select" required>
                    <option value="">Pilih..</option>
                    @foreach($regions as $region)
                      <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label><strong>Kebun</strong></label>
                  <select name="kebun" id="kebun_modal" class="form-control kebun-select" required>
                    <option value="">Pilih..</option>
                  </select>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Nama Program</label>
                    <input type="text" name="nama_program" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Lokasi Program (Desa)</label>
                    <select name="desa" class="form-control select2-desa" style="width:100%" required></select>
                </div>
              </div>
              

              


              <div class="mb-3">
                <label class="form-label">Penerima</label>
                <input type="text" name="penerima" class="form-control" required>
              </div>
              <div class="row">
                <div class="col-md-2 mb-3">
                  <label class="form-label">Tanggal Mulai</label>
                  <input type="date" name="tgl_mulai" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Tanggal Selesai</label>
                  <input type="date" name="tgl_selesai" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                  <label><strong>Program</strong></label>
                  <select name="program" id="program" class="form-control program-select" required>
                    <option value="">Pilih..</option>
                    @foreach($programptpn as $proptpn)
                      <option value="{{ $proptpn->program }}">{{ $proptpn->program }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2 mb-3">
                  <label><strong>Pilar</strong></label>
                  <select name="pilar" id="pilar" class="form-control pilar-select" required>
                    <option value="">Pilih..</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Sosial">Sosial</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Ekonomi">Ekonomi</option>
                  </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label><strong>Gambar Program & TPB</strong></label>
                    <div id="gambarProgramContainer" class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    <p class="text-muted m-0">Belum ada gambar</p>
                    </div>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-2 mb-3">
                  <label><strong>Status</strong></label>
                  <select name="status" id="status" class="form-control status-select" required>
                    <option value="">Pilih..</option>
                    <option value="Proposed">Proposed</option>
                    <option value="Active">Active</option>
                    <option value="Completed">Completed</option>
                  </select>
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Anggaran</label>
                  <input type="number" step="0.01" name="anggaran" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Realisasi</label>
                  <input type="number" step="0.01" name="realisasi" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Persentase</label>
                  <input type="number" step="0.01" name="persentase" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Persentase RKA</label>
                  <input type="number" step="0.01" name="persentase_rka" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Eployee Participation</label>
                  <input type="number" step="0.01" name="employee" class="form-control">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" class="form-control" rows="3"></textarea>
              </div>

              
            </div>

            {{-- TAB 2: MEDIA & LAPORAN --}}
            <div class="tab-pane fade" id="feedbackTab" role="tabpanel">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Sangat Puas</label>
                        <input type="number" name="sangat_puas" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Puas</label>
                        <input type="number" name="puas" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Kurang Puas</label>
                        <input type="number" name="kurang_puas" class="form-control">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">SROI Ratio</label>
                        <input type="number" name="sroi" class="form-control">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Saran dan Rekomendasi</label>
                    <textarea name="saran" class="form-control" rows="3"></textarea>
              </div>
            </div>
            {{-- TAB 2: MEDIA & LAPORAN --}}
            <div class="tab-pane fade" id="mediaTab" role="tabpanel">
                <div class="mb-4">
                    <label class="form-label"><strong>Upload Laporan (PDF/DOCX)</strong></label>
                    <input type="file" name="laporan[]" class="form-control" multiple accept=".pdf,.doc,.docx">
                    <small class="text-muted">Anda dapat memilih lebih dari satu file laporan.</small>
                </div>
                <div class="row">
                    {{-- === Upload Foto Dinamis === --}}
                    <div class="col-md-6 mb-4">
                        <label class="form-label"><strong>Upload Foto Dokumentasi</strong></label>
                        <div id="fotoContainer">
                        <div class="row mb-3 foto-item align-items-center">
                            <div class="col-md-11">
                            <input type="file" name="foto[]" class="form-control media-input" accept="image/*">
                            </div>
                            <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-foto">&times;</button>
                            </div>
                        </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm" id="addFoto">+ Tambah Foto</button>
                    </div>

                    {{-- === Upload Video Dinamis === --}}
                    <div class="col-md-6 mb-4">
                        <label class="form-label"><strong>Upload Video Dokumentasi</strong></label>
                        <div id="videoContainer">
                        <div class="row mb-3 video-item align-items-center">
                            <div class="col-md-11   ">
                            <input type="file" name="video[]" class="form-control media-input" accept="video/*">
                            </div>
                            <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-sm remove-video">&times;</button>
                            </div>
                        </div>
                        </div>
                        <button type="button" class="btn btn-success btn-sm" id="addVideo">+ Tambah Video</button>
                    </div>
                    </div>     
                {{-- === TAMPILAN THUMBNAIL FOTO & VIDEO SEMUA DALAM SATU TEMPAT === --}}
                <div id="mediaPreview" class="d-flex flex-wrap border rounded p-2 mt-3" style="gap:10px; min-height:100px;">
                <p class="text-muted m-0">Belum ada media (foto/video) dipilih</p>
                </div>
            </div>

            {{-- TAB 3: PUBLIKASI --}}
            <div class="tab-pane fade" id="publikasiTab" role="tabpanel">
                <div id="linkContainer">
                    <label class="form-label"><strong>Media & Link Berita</strong></label>

                    <div id="mediaContainer">
                        <div class="row mb-2 media-item align-items-center">
                            <div class="col-md-5">
                                <input type="text" name="media[]" class="form-control" placeholder="Nama Media">
                            </div>
                            <div class="col-md-6">
                                <input type="url" name="link_berita[]" class="form-control" placeholder="Link Berita (URL)">
                            </div>
                            <div class="col-md-1 d-flex align-items-center">
                                <button type="button" class="btn btn-danger btn-sm removeMedia">
                                    &times;
                                </button>
                            </div>
                        </div>
                    </div>

                    <button type="button" class="btn btn-success btn-sm mt-2" id="addMedia">
                        <i class="bi bi-plus-circle"></i> Tambah Media
                    </button>
                </div>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- modal edit --}}
<div class="modal fade" id="modalEditProgram" tabindex="-1" aria-labelledby="modalEditProgramLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-warning text-white">
        <h5 class="modal-title" id="modalEditProgramLabel">Edit Program TJSL</h5>
        <button type="button" class="btn-close btn-close-white" data-dismiss="modal" aria-label="Close">X</button>
      </div>

      <form id="formEditProgram" action="{{ route('program-tjsl.update',0) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body" style="max-height: 75vh; overflow-y: auto;">
          {{-- Tab Navigasi --}}
          <ul class="nav nav-tabs" id="editProgramTabs" role="tablist">
            <li class="nav-item">
              <a class="nav-link active" id="edit-data-utama-tab" data-toggle="tab" href="#editDataUtama" role="tab">📋 Data Utama</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-feedback-tab" data-toggle="tab" href="#editFeedbackTab" role="tab">📸 Feedback Program</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-media-tab" data-toggle="tab" href="#editMediaTab" role="tab">📸 Media & Laporan</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" id="edit-publikasi-tab" data-toggle="tab" href="#editPublikasiTab" role="tab">📰 Publikasi</a>
            </li>
          </ul>

          <div class="tab-content mt-3" id="editProgramTabsContent">

            {{-- TAB 1: DATA UTAMA --}}
            <div class="tab-pane fade show active" id="editDataUtama" role="tabpanel">
              <input type="hidden" name="id_program" id="edit_id_program">

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label><strong>Region</strong></label>
                  <select name="regional" id="edit_region_modal" class="form-control region-select" required>
                    <option value="">Pilih..</option>
                    @foreach($regions as $region)
                      <option value="{{ $region }}">{{ $region }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-6 mb-3">
                  <label><strong>Kebun</strong></label>
                  <select name="kebun" id="edit_kebun_modal" class="form-control kebun-select" required>
                    <option value="">Pilih..</option>
                  </select>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-3">
                  <label class="form-label">Nama Program</label>
                  <input type="text" name="nama_program" id="edit_nama_program" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                <label class="form-label">Lokasi Program (Desa)</label>
                <select id="edit_desa" name="desa" class="form-control select2-desa-edit" style="width:100%" required></select>
                </div>

              </div>

              <div class="mb-3">
                <label class="form-label">Penerima</label>
                <input type="text" name="penerima" id="edit_penerima" class="form-control" required>
              </div>

              <div class="row">
                <div class="col-md-2 mb-3">
                  <label class="form-label">Tanggal Mulai</label>
                  <input type="date" name="tgl_mulai" id="edit_tgl_mulai" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Tanggal Selesai</label>
                  <input type="date" name="tgl_selesai" id="edit_tgl_selesai" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                  <label><strong>Program</strong></label>
                  <select name="program" id="edit_program" class="form-control program-select" required>
                    <option value="">Pilih..</option>
                    @foreach($programptpn as $proptpn)
                      <option value="{{ $proptpn->program }}">{{ $proptpn->program }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-2 mb-3">
                  <label><strong>Pilar</strong></label>
                  <select name="pilar" id="edit_pilar" class="form-control pilar-select" required>
                    <option value="">Pilih..</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Sosial">Sosial</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Ekonomi">Ekonomi</option>
                  </select>
                </div>
                
                <div class="col-md-4 mb-3">
                  <label><strong>Gambar Program & TPB</strong></label>
                  <div id="edit_gambarProgramContainer" class="d-flex flex-wrap align-items-center gap-2 mt-2">
                    <p class="text-muted m-0">Belum ada gambar</p>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-2 mb-3">
                  <label><strong>Status</strong></label>
                  <select name="status" id="edit_status" class="form-control status-select" required>
                    <option value="">Pilih..</option>
                    <option value="Proposed">Proposed</option>
                    <option value="Active">Active</option>
                    <option value="Completed">Completed</option>
                  </select>
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Anggaran</label>
                  <input type="number" step="0.01" name="anggaran" id="edit_anggaran" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Realisasi</label>
                  <input type="number" step="0.01" name="realisasi" id="edit_realisasi" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Persentase</label>
                  <input type="number" step="0.01" name="persentase" id="edit_persentase" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Persentase RKA</label>
                  <input type="number" step="0.01" name="persentase_rka" id="edit_persentase_rka" class="form-control">
                </div>
                <div class="col-md-2 mb-3">
                  <label class="form-label">Eployee Participation</label>
                  <input type="number" step="0.01" name="employee" id="edit_employee"class="form-control">
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label">Deskripsi</label>
                <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="3"></textarea>
              </div>
            </div>

            {{-- TAB 2: FEEDBACK --}}
            <div class="tab-pane fade" id="editFeedbackTab" role="tabpanel">
              <div class="row">
                <div class="col-md-3 mb-3">
                  <label class="form-label">Sangat Puas</label>
                  <input type="number" name="sangat_puas" id="edit_sangat_puas" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Puas</label>
                  <input type="number" name="puas" id="edit_puas" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">Kurang Puas</label>
                  <input type="number" name="kurang_puas" id="edit_kurang_puas" class="form-control">
                </div>
                <div class="col-md-3 mb-3">
                  <label class="form-label">SROI Ratio</label>
                  <input type="number" name="sroi" id="edit_sroi" class="form-control">
                </div>
              </div>
              <div class="mb-3">
                <label class="form-label">Saran dan Rekomendasi</label>
                <textarea name="saran" id="edit_saran" class="form-control" rows="3"></textarea>
              </div>
            </div>

            {{-- TAB 3: MEDIA & LAPORAN --}}
            <div class="tab-pane fade" id="editMediaTab" role="tabpanel">
              
            <div class="mb-4">
                <label class="form-label fw-bold">Laporan Program</label>

                <!-- Tempat daftar laporan lama -->
                <div id="edit_laporanContainer" class="border p-2 rounded bg-light" style="min-height: 50px;">
                    <small class="text-muted">Belum ada laporan diunggah.</small>
                </div>

                <!-- Input upload laporan baru -->
                <input type="file" class="form-control mt-2" id="edit_file_laporan" name="file_laporan[]" multiple accept=".pdf,.doc,.docx">
                <small class="text-muted">Unggah file PDF (opsional untuk menambah atau mengganti laporan lama)</small>
            </div>


              <div class="row">
                <div class="col-md-6 mb-4">
                  <label class="form-label"><strong>Upload Foto Dokumentasi</strong></label>
                  <div id="edit_fotoContainer"></div>
                  
                  <button type="button" class="btn btn-success btn-sm" id="edit_addFoto">+ Tambah Foto</button>
                </div>

                <div class="col-md-6 mb-4">
                  <label class="form-label"><strong>Upload Video Dokumentasi</strong></label>
                  <div id="edit_videoContainer"></div>
                  <button type="button" class="btn btn-success btn-sm" id="edit_addVideo">+ Tambah Video</button>
                </div>
              </div>

              <div id="edit_mediaPreview" class="d-flex flex-wrap border rounded p-2 mt-3" style="gap:10px; min-height:100px;">
                <p class="text-muted m-0">Belum ada media (foto/video) tersimpan</p>
              </div>
            </div>

            {{-- TAB 4: PUBLIKASI --}}
            <div class="tab-pane fade" id="editPublikasiTab" role="tabpanel">
              <label class="form-label"><strong>Media & Link Berita</strong></label>
              <div id="edit_mediaContainer"></div>
              <button type="button" class="btn btn-success btn-sm mt-2" id="edit_addMedia">
                <i class="bi bi-plus-circle"></i> Tambah Media
              </button>
            </div>

          </div>
        </div>
        {{-- <input type="hidden" name="deleted_foto_ids[]" id="deleted_foto_ids">
        <input type="hidden" name="deleted_video_ids[]" id="deleted_video_ids">
        <input type="hidden" name="delete_publikasi[]" id="delete_publikasi"> --}}
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-warning btn-sm">Simpan Perubahan</button>
        </div>
      </form>
    </div>
  </div>
</div>

{{-- modal detail --}}
<!-- Modal Detail Program TJSL -->
{{-- <div class="modal fade" id="modalDetailProgram" tabindex="-1" aria-labelledby="modalDetailProgramLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalDetailProgramLabel">Detail Program TJSL</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>

      <div class="modal-body">
        <div class="row">
          <!-- Kolom Kiri: Info Dasar Program -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white" id="detail_nama_program">Informasi Program</div>
              <div class="card-body" style="font-size:0.9em;">
                <table class="table table-borderless table-sm mb-0">
                  <tr>
                    <th width="35%">Regional</th>
                    <td id="detail_regional">-</td>
                  </tr>
                  <tr>
                    <th>Kebun/Unit</th>
                    <td id="detail_kebun">-</td>
                  </tr>
                  <tr>
                    <th>Lokasi Program</th>
                    <td id="detail_lokasi_program">-</td>
                  </tr>
                  <tr>
                    <th>Tanggal Pelaksanaan</th>
                    <td id="detail_tgl_mulai">-</td>
                  </tr>
                  <tr>
                    <th>Penerima</th>
                    <td id="detail_penerima">-</td>
                  </tr>
                  <tr>
                    <th>Pilar</th>
                    <td id="detail_pilar">-</td>
                  </tr>
                <tr>
                    <th>Program</th>
                    <td id="detail_program">-</td>
                  </tr>
                  <tr>
                    <th>Status</th>
                    <td id="detail_status">-</td>
                  </tr>
                  
                </table>
              </div>
            </div>
          </div>

          <!-- Kolom Kanan: Deskripsi -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white">Anggaran</div>
              <div class="card-body" style="font-size:0.9em; height:250px; overflow-y:auto;">
                <table class="table table-borderless table-sm mb-0">
                    <tr>
                        <th width="40%">Perenacaan Anggaran</th>
                        <td id="detail_anggaran">-</td>
                    </tr>
                    <tr>
                        <th>Realisasi Anggaran</th>
                        <td id="detail_realisasi">-</td>
                    </tr>
                    <tr>
                        <th>Persentase Realisasi</th>
                        <td id="detail_persentase">-</td>
                    </tr>
                    <tr>
                        <th>Persentase RKA</th>
                        <td id="detail_rka">-</td>
                    </tr>
                </table>
              </div>
            </div>
          </div>
          <!-- Kolom Kanan: Deskripsi -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Deskripsi Program</div>
              <div class="card-body" style="font-size:0.9em; height:250px; overflow-y:auto;">
                <p id="detail_deskripsi" style="text-align:justify;">-</p>
              </div>
            </div>
          </div>
          <!-- Kolom Kanan: Deskripsi -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Feedback Program</div>
              <div class="card-body" style="font-size:0.9em; height:250px; overflow-y:auto;">
                <table class="table table-bordered table-sm mb-0 text-center">
                  <thead>
                    <tr>
                      <th>Sangat Puas</th>
                      <th>Puas</th>
                      <th>Kurang Puas</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td id="detail_sangat_puas">-</td>
                      <td id="detail_puas">-</td>
                      <td id="detail_kurang_puas">-</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- ===== Bagian Media & Laporan ===== -->
<div class="col-md-6">
  <div class="card mb-3 shadow-sm">
    <div class="card-header bg-info text-white text-center">Media & Laporan</div>
    <div class="card-body" style="font-size:0.9em;">
      <div class="table-responsive">
        <table class="table table-bordered table-sm align-middle text-center" id="tableMedia">
          <thead >
            <tr>
              <th style="width: 30%;">Kategori</th>
              <th>File</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><strong>Laporan Pertanggungjawaban</strong></td>
              <td id="laporanCell">
                <small class="text-muted">Belum ada laporan.</small>
              </td>
            </tr>
            <tr>
              <td><strong>Foto</strong></td>
              <td id="fotoCell">
                <small class="text-muted">Belum ada foto.</small>
              </td>
            </tr>
            <tr>
              <td><strong>Video</strong></td>
              <td id="videoCell">
                <small class="text-muted">Belum ada video.</small>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>

        <div class="col-md-6">
        <div class="card mb-3 shadow-sm">
            <div class="card-header bg-info text-white text-center">Publikasi Media</div>
            <div class="card-body" id="detail_publikasiPreview" style="font-size:0.9em;">
            <small class="text-muted">Belum ada publikasi.</small>
            </div>
        </div>
        </div>

          

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div> --}}
<!-- Modal Detail Program TJSL -->
<div class="modal fade" id="modalDetailProgram" tabindex="-1" aria-labelledby="modalDetailProgramLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalDetailProgramLabel">Detail Program TJSL</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Tutup">X</button>
      </div>

      <div class="modal-body">
        <div class="row">

          <!-- Kolom Kiri -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white" id="detail_nama_program">Informasi Program</div>
              <div class="card-body" style="font-size:0.8em; height:200px; display:flex; flex-direction:column; overflow-y:auto;">
                <table class="table table-borderless table-sm mb-0">
                  <tr><th width="35%">Regional</th><td id="detail_regional">-</td></tr>
                  <tr><th>Kebun/Unit</th><td id="detail_kebun">-</td></tr>
                  <tr><th>Lokasi Program</th><td id="detail_lokasi_program">-</td></tr>
                  <tr><th>Tanggal Pelaksanaan</th><td id="detail_tgl_mulai">-</td></tr>
                  <tr><th>Penerima</th><td id="detail_penerima">-</td></tr>
                  <tr><th>Pilar</th><td id="detail_pilar">-</td></tr>
                  <tr><th>Program</th><td id="detail_program">-</td></tr>
                  <tr><th>Status</th><td id="detail_status">-</td></tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Kolom Kanan -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white">Anggaran</div>
              <div class="card-body" style="font-size:0.8em; height:200px; display:flex; flex-direction:column; overflow-y:auto;">
                <table class="table table-borderless table-sm mb-0">
                  <tr><th width="40%">Perencanaan Anggaran</th><td id="detail_anggaran">-</td></tr>
                  <tr><th>Realisasi Anggaran</th><td id="detail_realisasi">-</td></tr>
                  <tr><th>Persentase Realisasi</th><td id="detail_persentase">-</td></tr>
                  <tr><th>Persentase RKA</th><td id="detail_rka">-</td></tr>
                </table>
              </div>
            </div>
          </div>

          <!-- Deskripsi -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white">Deskripsi Program</div>
              <div class="card-body" style="font-size:0.8em; height:200px; display:flex; flex-direction:column; overflow-y:auto;">
                <p id="detail_deskripsi" style="text-align:justify;">-</p>
              </div>
            </div>
          </div>

          <!-- Feedback -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white ">Feedback Program</div>
              <div class="card-body" style="font-size:0.8em; height:200px; display:flex; flex-direction:column; overflow-y:auto;">
                <div class="row">
                    <div class="col-md-6">
                        <div style="height:200px;">
                            <canvas id="chartFeedback"></canvas>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Saran dan Rekomendasi</strong></p>
                        <p id="detail_saran_rekomendasi"></p>
                        <p><strong>SROI Ratio:</strong><span id="detail_sroi_ratio">-</span></p>
                    </div>

                </div>
                
              </div>
            </div>
          </div>
          <!-- Publikasi -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white ">Publikasi Media</div>
              <div class="card-body" id="detail_publikasiPreview" style="font-size:0.8em; height:200px; display:flex; flex-direction:column; overflow-y:auto;">
                <small class="text-muted">Belum ada publikasi.</small>
              </div>
            </div>
          </div>

          <!-- Media & Laporan -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white ">Dokumentasi</div>
              <div class="card-body" id="detail_mediaPreview" style="font-size:0.8em; height:200px; display:flex; flex-direction:column; overflow-y:auto;">
                <small class="text-muted">Belum ada data.</small>
              </div>
            </div>
          </div>

          

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Preview Media -->
{{-- <div class="modal fade" id="modalPreviewMedia" tabindex="-1" aria-labelledby="modalPreviewMediaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalPreviewMediaLabel">Preview Media</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body" id="previewMediaContent">
        <p class="text-center text-muted m-0">Tidak ada data.</p>
      </div>
    </div>
  </div>
</div> --}}
<div class="modal fade" id="modalPreviewMedia" tabindex="-1" aria-labelledby="modalPreviewMediaLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h5 class="modal-title" id="modalPreviewMediaLabel">Media Preview</h5>
        <button type="button" class="btn-close btn-close-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="container-fluid" style="margin-top:1.5rem;">
          <div class="row" id="previewMediaContent" style="flex-direction:column;"></div>
        </div>
      </div>
    </div>
  </div>
</div>


{{-- <style>
  /* Animasi muncul */
  #previewMediaContent {
    animation: fadeIn 0.4s ease-in-out;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
  }

  /* Foto Gallery */
  .foto-item {
    position: relative;
    overflow: hidden;
    border-radius: 10px;
  }
  .foto-item img {
    width: 100%;
    height: 200px;
    object-fit: cover;
    transition: transform 0.3s ease;
  }
  .foto-item:hover img {
    transform: scale(1.08);
  }
  .foto-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.4);
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
  }
  .foto-item:hover .foto-overlay {
    opacity: 1;
  }
  .foto-overlay i {
    color: #fff;
    font-size: 1.8rem;
  }

  /* Video Card */
  .video-card {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
  }
  .video-card:hover {
    transform: translateY(-3px);
  }
</style> --}}
<style>
  #previewMediaContent {
    animation: fadeIn 0.4s ease-in-out;
  }
  @keyframes fadeIn {
    from { opacity: 0; transform: scale(0.98); }
    to { opacity: 1; transform: scale(1); }
  }

  .media-besar {
    position: relative;
    overflow: hidden;
    border-radius: 12px;
  }
  .media-besar img,
  .media-besar video {
    transition: transform 0.3s ease;
  }
  .media-besar:hover img,
  .media-besar:hover video {
    transform: scale(1.02);
  }

  .media-overlay {
    position: absolute;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.25);
    opacity: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: opacity 0.3s ease;
  }
  .media-besar:hover .media-overlay {
    opacity: 1;
  }
  .media-overlay i {
    color: #fff;
    font-size: 2rem;
  }
</style>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

{{-- script modal detail --}}
<script>
    $(document).on('click', '.btnLihatProgram', function () {
        const id = $(this).data('id');

        $.ajax({
            url: `/program-tjsl/${id}`,
            method: 'GET',
            success: function (res) {
            const data = res.data.program;
            const foto = res.data.foto || [];
            const video = res.data.video || [];
            const publikasi = res.data.publikasi || [];
            const laporan = res.data.laporan || [];
            let periode = '';
            if (data.tgl_mulai && data.tgl_selesai) {
                periode = `${formatTanggalIndo(data.tgl_mulai)} - ${formatTanggalIndo(data.tgl_selesai)}`;
            } else if (data.tgl_mulai) {
                periode = formatTanggalIndo(data.tgl_mulai);
            } else if (data.tgl_selesai) {
                periode = formatTanggalIndo(data.tgl_selesai);
            } else {
                periode = '-';
            }
            $('#detail_tgl_mulai').text(periode);
            // --- Isi data utama
            $('#detail_nama_program').text(data.nama_program ?? '-');
            $('#detail_lokasi_program').text(data.nama_desa ?? '-');
            $('#detail_regional').text(data.regional ?? '-');
            $('#detail_kebun').text(data.kebun ?? '-');
            $('#detail_penerima').text(data.penerima ?? '-');
            $('#detail_pilar').text(data.pilar ?? '-');
            $('#detail_program').text(data.program ?? '-');
            $('#detail_status').text(data.status ?? '-');
            $('#detail_anggaran').text(formatRupiah(data.anggaran));
            $('#detail_realisasi').text(formatRupiah(data.realisasi));
            $('#detail_rka').text(data.persentase_rka +'%' ?? '-');
            $('#detail_persentase').text(data.persentase +'%' ?? '-');
            $('#detail_deskripsi').text(data.deskripsi ?? '-');
            $('#detail_sangat_puas').text(data.sangat_puas ?? '-');
            $('#detail_puas').text(data.puas ?? '-');
            $('#detail_kurang_puas').text(data.kurang_puas ?? '-');
            $('#detail_saran_rekomendasi').text(data.saran ?? '-');
            $('#detail_sroi_ratio').text(data.sroi_ratio +'%' ?? '-');
            // === Pie Chart Feedback ===
            if (window.feedbackChart) {
                window.feedbackChart.destroy(); // Hapus chart lama sebelum buat baru
            }

            const sangatPuas = parseInt(data.sangat_puas || 0);
            const puas = parseInt(data.puas || 0);
            const kurangPuas = parseInt(data.kurang_puas || 0);

            const ctx = document.getElementById('chartFeedback').getContext('2d');
            window.feedbackChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Sangat Puas', 'Puas', 'Kurang Puas'],
                    datasets: [{
                        data: [sangatPuas, puas, kurangPuas],
                        backgroundColor: [
                            'rgba(0, 213, 42, 1)',  // Biru - Sangat Puas
                            'rgba(255, 146, 0, 1)', // Hijau - Puas
                            'rgba(255, 0, 0, 1)'  // Merah - Kurang Puas
                        ],
                        borderColor: '#fff',
                        borderWidth: 2
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                font: { size: 8 }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const total = sangatPuas + puas + kurangPuas;
                                    const val = context.raw || 0;
                                    const percent = total ? ((val / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${val} (${percent}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // --- Program & Gambar Program ---
            $('#detail_program').html('<p class="text-muted m-0">Memuat gambar...</p>');

            $('#detail_program').empty().append(`
                <div class="d-flex align-items-center gap-2">
                    <span>${data.program ?? '-'}</span>
                    <div id="gambarProgramDetail" class="d-flex gap-1"></div>
                </div>
            `);

             // --- Program & Gambar Program
            $('#detail_program').html(`
                <div class="d-flex align-items-center gap-2">
                    <span>${data.program ?? '-'}</span>
                    <div id="gambarProgramDetail" class="d-flex gap-1"></div>
                </div>
            `);
            const container = $('#gambarProgramDetail');
            if (data.program) {
                $.get('{{ url("/program-tjsl/get-gambar") }}', { program: data.program }, function(resp) {
                    container.empty();
                    if (resp.gambar) {
                        container.append(`<img src="{{ asset('img/') }}/${resp.gambar}" class="rounded shadow-sm" style="width:35px;height:35px;object-fit:cover;">`);
                    }
                    if (resp.gambar_tpb && resp.gambar_tpb.length > 0) {
                        resp.gambar_tpb.forEach(gbr => {
                            container.append(`<img src="{{ asset('img/') }}/${gbr}" class="rounded shadow-sm" style="width:35px;height:35px;object-fit:cover;">`);
                        });
                    }
                    if (!resp.gambar && (!resp.gambar_tpb || resp.gambar_tpb.length === 0)) {
                        container.html('<small class="text-muted">Tidak ada gambar</small>');
                    }
                });
            }

            // === Tabel Media ===
            const tableMedia = `
            
                <table class="table table-bordered align-middle text-center">
                  <thead>
                    <tr>
                      <th>Kategori</th>
                      <th>File</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><strong>Laporan</strong></td>
                      <td>${laporan.length > 0
                        ? `<button class="btn btn-sm btn-outline-primary w-100" onclick="showMediaPreview('laporan')">Lihat ${laporan.length} Laporan</button>`
                        : '<small class="text-muted">Belum ada laporan.</small>'}</td>
                    </tr>
                    <tr>
                      <td><strong>Foto</strong></td>
                      <td>${foto.length > 0
                        ? `<button class="btn btn-sm btn-outline-success w-100" onclick="showMediaPreview('foto')">Lihat ${foto.length} Foto</button>`
                        : '<small class="text-muted">Belum ada foto.</small>'}</td>
                    </tr>
                    <tr>
                      <td><strong>Video</strong></td>
                      <td>${video.length > 0
                        ? `<button class="btn btn-sm btn-outline-danger w-100" onclick="showMediaPreview('video')">Lihat ${video.length} Video</button>`
                        : '<small class="text-muted">Belum ada video.</small>'}</td>
                    </tr>
                  </tbody>
                </table>
            `;
            $('#detail_mediaPreview').html(tableMedia);

            // Simpan media global
            window.currentMedia = { foto, video, laporan };

            // --- Publikasi
            const publikasiContainer = $('#detail_publikasiPreview').empty();
            if (publikasi.length > 0) {
                publikasiContainer.html(renderPublikasiTable(publikasi));
            } else {
                publikasiContainer.html('<small class="text-muted">Belum ada publikasi.</small>');
            }


            $('#modalDetailProgram').modal('show');
            }
        });
    });

    function showMediaPreview(type) {
    const modal = new bootstrap.Modal(document.getElementById('modalPreviewMedia'));
    const container = $('#previewMediaContent');
    const data = window.currentMedia[type] || [];
    container.empty();

    if (data.length === 0) {
        container.html('<p class="text-center text-muted m-0">Tidak ada data.</p>');
        return modal.show();
    }

    // === FOTO ===
    if (type === 'foto') {
        $('#modalPreviewMediaLabel').text('📸 Kumpulan Foto');
        container.html(`
            <div class="d-flex flex-column align-items-center gap-3">
                ${data.map(f => `
                    <div class="media-besar position-relative w-100">
                        <img src="/storage/${f.file_path}" class="img-fluid rounded shadow-sm w-100"
                             style="object-fit:contain; max-height:80vh; cursor:pointer;"
                             onclick="window.open('/storage/${f.file_path}', '_blank')">
                        <div class="media-overlay">
                            <i class="bi bi-search"></i>
                        </div>
                    </div>
                `).join('')}
            </div>
        `);
    }

    // === VIDEO ===
    else if (type === 'video') {
        $('#modalPreviewMediaLabel').text('🎥 Kumpulan Video');
        container.html(`
            <div class="d-flex flex-column align-items-center gap-3">
                ${data.map(v => `
                    <div class="media-besar position-relative w-100">
                        <video controls class="w-100 rounded shadow-sm bg-dark"
                               style="max-height:80vh; object-fit:contain;">
                            <source src="/storage/${v.file_path}" type="video/mp4">
                            Browser Anda tidak mendukung pemutar video.
                        </video>
                    </div>
                `).join('')}
            </div>
        `);
    }

    // === LAPORAN ===
    else if (type === 'laporan') {
        $('#modalPreviewMediaLabel').text('📑 Laporan Pertanggungjawaban');
        container.html(`
            <ul class="list-group list-group-flush align-item-center" style="display: flex;
  flex-direction: row;
  font-size: 18px;
  text-align: center;">
                ${data.map(l => {
                    const fileName = l.file_laporan.split('/').pop();
                    const ext = fileName.split('.').pop().toLowerCase();
                    let icon = 'bi-file-earmark-text';
                    if (ext === 'pdf') icon = 'bi-file-earmark-pdf text-danger';
                    else if (ext === 'doc' || ext === 'docx') icon = 'bi-file-earmark-word text-primary';
                    else if (ext === 'xls' || ext === 'xlsx') icon = 'bi-file-earmark-excel text-success';
                    return `
                        <li class="list-group-item d-flex justify-content-between align-items-center" style="border:0px;">
                            <div><i class="bi ${icon} me-2"></i>${fileName}</div>
                            <a href="/storage/${l.file_laporan}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-box-arrow-up-right"></i> Buka
                            </a>
                        </li>`;
                }).join('')}
            </ul>
        `);
    }

    modal.show();
}



// Helper
function renderPublikasiTable(data) {
  let html = `<table class="table table-bordered table-sm text-center align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Media</th>
                        <th>Link Berita</th>
                    </tr>
                    </thead>
                    <tbody>`;
                    data.forEach((p,i)=>html += `<tr><td>${i+1}</td><td>${p.media}</td><td><a href="${p.link_berita}" target="_blank">${p.link_berita}</a></td></tr>`);
  return html + '</tbody></table>';
}
function formatTanggalIndo(tgl){
    if(!tgl)return'-';
    return new Date(tgl).toLocaleDateString('id-ID',{day:'2-digit',month:'long',year:'numeric'});
}
function formatRupiah(a){
    if(!a)return'-';
    a=a.toString().replace(/[^,\d]/g,'');
    let s=a.split(','),b=s[0].length%3,r=s[0].substr(0,b),t=s[0].substr(b).match(/\d{3}/gi);
    if(t){let p=b?'.':'';
    r+=p+t.join('.');
    }
    return'Rp. '+r+(s[1]!==undefined?','+s[1]:'')+',-';
    }
</script>

{{-- script modal edit --}}
<script>
document.addEventListener('DOMContentLoaded', function() {

    $(document).ready(function () {

    // ============================
    // 1️⃣ REGION -> KEBUN (dinamis)
    // ============================
    $(document).on('change', '.region-select', function () {
        const region = $(this).val();
        const form = $(this).closest('form, .modal-body');
        const kebunSelect = form.find('.kebun-select');

        kebunSelect.html('<option value="">Memuat...</option>');

        $.get('{{ url("/get-kebun-by-region") }}', { region: region }, function (data) {
            let options = '<option value="">Pilih..</option>';
            data.forEach(function (item) {
                options += `<option value="${item.unit}">${item.unit}</option>`;
            });
            kebunSelect.html(options);
        });
    });


    // ============================
    // 2️⃣ INIT SELECT2 DESA
    // ============================
    // ✅ Inisialisasi Select2 Desa untuk modal EDIT
    function initSelect2DesaEdit(el) {
        el.select2({
            placeholder: 'Cari Desa...',
            ajax: {
                url: '{{ route("wilayah.desa") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            dropdownParent: $('#modalEditProgram') // perbedaan utama di sini
        });
    }

    // ✅ Jalankan saat modal EDIT dibuka
    $('#modalEditProgram').on('shown.bs.modal', function () {
        initSelect2DesaEdit($('.select2-desa-edit'));
    });


    // 🔧 Fungsi bantu untuk set value dropdown
    function setSelectValue(selector, value, text = null) {
        const selectEl = $(selector);
        if (!value) return;

        // Jika option belum ada, tambahkan
        if (selectEl.find(`option[value="${value}"]`).length === 0) {
            const newOption = new Option(text || value, value, true, true);
            selectEl.append(newOption);
        }

        selectEl.val(value).trigger('change');
    }

    // ============================
    // 3️⃣ BUKA MODAL EDIT
    // ============================
    $(document).on('click', '.btnEditProgram', function () {
        const programId = $(this).data('id');
        

        // Kosongkan dulu form
        // Reset form dulu
        $('#formEditProgram')[0].reset();
        $('#formEditProgram').attr('action', `/program-tjsl/${programId}`);

        $('#edit_laporanPreview').html('<small class="text-muted">Belum ada laporan diunggah.</small>');
        $.ajax({
            url: `/program-tjsl/${programId}/edit`,
            type: 'GET',
            success: function (response) {
                const data = response.program;

                // === Isi Field Text ===
                $('#edit_id').val(data.id);
                $('#edit_nama_program').val(data.nama_program);
                $('#edit_penerima').val(data.penerima);
                $('#edit_tgl_mulai').val(data.tgl_mulai);
                $('#edit_tgl_selesai').val(data.tgl_selesai);
                $('#edit_program').val(data.program);
                $('#edit_pilar').val(data.pilar);
                $('#edit_status').val(data.status);
                $('#edit_anggaran').val(data.anggaran);
                $('#edit_realisasi').val(data.realisasi);
                $('#edit_persentase').val(data.persentase);
                $('#edit_persentase_rka').val(data.persentase_rka);
                $('#edit_employee').val(data.employee);
                $('#edit_deskripsi').val(data.deskripsi);
                $('#edit_sangat_puas').val(data.sangat_puas);
                $('#edit_puas').val(data.puas);
                $('#edit_kurang_puas').val(data.kurang_puas);
                $('#edit_sroi').val(data.sroi);
                $('#edit_saran').val(data.saran);

            // === Isi Dropdown Region ===
            setSelectValue('#edit_region_modal', data.regional, data.regional);

            // === Setelah Region terpilih, load Kebun ===
            $.get('{{ url("/get-kebun-by-region") }}', { region: data.regional }, function (kebunList) {
                let options = '<option value="">Pilih..</option>';
                kebunList.forEach(item => {
                    options += `<option value="${item.unit}">${item.unit}</option>`;
                });
                $('#edit_kebun_modal').html(options);

                // Auto-select kebun
                setSelectValue('#edit_kebun_modal', data.kebun, data.kebun);
            });
            
            // === Render laporan lama dari response (pada AJAX edit) ===
            const laporanContainer = $('#edit_laporanContainer');
            laporanContainer.empty();

            if (response.laporan && response.laporan.length > 0) {
                response.laporan.forEach(lap => {
                    laporanContainer.append(`
                        <div class="row mb-2 align-items-center laporan-item" data-id="${lap.id_laporan}">
                            <div class="col-md-11">
                                <a href="/storage/${lap.file_laporan}" target="_blank" class="text-decoration-none">
                                    <i class="bi bi-file-earmark-text"></i> ${lap.file_laporan.split('/').pop()}
                                </a>
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-danger btn-sm removeLaporan">&times;</button>
                            </div>
                        </div>
                    `);
                });
            } else {
                laporanContainer.html('<small class="text-muted">Belum ada laporan diunggah.</small>');
            }

            // === Event Hapus Laporan ===
            $(document).on('click', '.removeLaporan', function () {
                const parent = $(this).closest('.laporan-item');
                const laporanId = parent.data('id');

                if (laporanId) {
                    $('#formEditProgram').append(`
                        <input type="hidden" name="delete_laporan[]" value="${laporanId}">
                    `);
                }

                parent.fadeOut(200, () => parent.remove());
            });



            // === Autoselect Desa di Select2 Edit ===
            const desaSelect = $('#edit_desa');
            desaSelect.empty(); // kosongkan opsi dulu

            if (data.lokasi_program) {
                const desaText = data.nama_desa || data.lokasi_program; // ambil nama dari controller
                const desaOption = new Option(desaText, data.lokasi_program, true, true);
                desaSelect.append(desaOption).trigger('change');
            }

                // === Media Preview ===
                let mediaHTML = '';
                if ((response.foto && response.foto.length) || (response.video && response.video.length)) {
                    if (response.foto) {
                        response.foto.forEach(f => {
                            mediaHTML += `
                                <div class="media-item text-center me-2 preview-foto" data-filename="${f.nama_file}">
                                    <img src="/storage/${f.file_path}" class="rounded" width="100">
                                </div>`;
                        });
                    }
                    if (response.video) {
                        response.video.forEach(v => {
                            mediaHTML += `
                                <div class="media-item text-center me-2 preview-video" data-filename="${v.nama_file}">
                                    <video width="120" controls>
                                        <source src="/storage/${v.file_path}" type="video/mp4">
                                    </video>
                                </div>`;
                        });
                    }
                } else {
                    mediaHTML = '<p class="text-muted m-0">Belum ada media (foto/video)</p>';
                }

                $('#edit_mediaPreview').html(mediaHTML);
                
                // === Isi Input Dinamis Foto ===
                const fotoContainer = $('#edit_fotoContainer');
                fotoContainer.empty();
                if (response.foto && response.foto.length > 0) {
                    response.foto.forEach(f => {
                        // console.log(f.id);
                        fotoContainer.append(`
                            <div class="row mb-2 align-items-center media-item existing-foto" data-filename="${f.nama_file}" data-id="${f.id_foto}">
                                <div class="col-md-11">
                                    <input type="hidden" name="existing_foto[]" value="${f.nama_file}">
                                    <span class="input-group-text bg-light">
                                        <a href="/storage/${f.file_path}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-eye"></i> ${f.nama_file}
                                        </a>
                                    </span>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-danger btn-sm removeInput">&times;</button>
                                </div>
                            </div>
                        `);
                    });
                }
                else {
                    fotoContainer.append(`
                        <div class="row mb-2 align-items-center foto-item">
                            <div class="col-md-11">
                                <input type="file" name="foto[]" class="form-control" accept="image/*">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-danger btn-sm removeInput">&times;</button>
                            </div>
                        </div>
                    `);
                }

                // === Isi Input Dinamis Video ===
                const videoContainer = $('#edit_videoContainer');
                videoContainer.empty();
                if (response.video && response.video.length > 0) {
                    response.video.forEach(v => {
                        videoContainer.append(`
                            <div class="row mb-2 align-items-center media-item existing-video" data-filename="${v.nama_file}" data-id="${v.id_video}">
                                <div class="col-md-11">
                                    <input type="hidden" name="existing_video[]" value="${v.nama_file}">
                                    <span class="input-group-text bg-light">
                                        <a href="/storage/${v.file_path}" target="_blank" class="text-decoration-none">
                                            <i class="bi bi-eye"></i> ${v.nama_file}
                                        </a>
                                    </span>
                                </div>
                                <div class="col-md-1 text-end">
                                    <button type="button" class="btn btn-danger btn-sm removeInput">&times;</button>
                                </div>
                            </div>
                        `);
                    });
                }
                else {
                    videoContainer.append(`
                        <div class="row mb-2 align-items-center video-item">
                            <div class="col-md-11">
                                <input type="file" name="video[]" class="form-control" accept="video/*">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-danger btn-sm removeInput">&times;</button>
                            </div>
                        </div>
                    `);
                }
                // === Render Publikasi dari Database ===
                const container = $('#edit_mediaContainer');
                container.empty();

                if (response.publikasi && response.publikasi.length) {
                    response.publikasi.forEach(pub => {
                        container.append(`
                            <div class="row mb-2 align-items-center media-item" data-id="${pub.id_publikasi}">
                                <div class="col-md-5">
                                    <input type="text" name="media_existing[${pub.id_publikasi}]"  class="form-control" value="${pub.media}" placeholder="Nama Media" disabled>
                                </div>
                                <div class="col-md-6">
                                    <input type="url" name="link_existing[${pub.id_publikasi}]" class="form-control" value="${pub.link_berita}" placeholder="Link Berita (URL)" disabled>
                                </div>
                                <div class="col-md-1 text-center">
                                    <button type="button" class="btn btn-danger btn-sm removeMedia">&times;</button>
                                </div>
                            </div>
                        `);
                    });
                } else {
                    // Jika belum ada publikasi, tampilkan satu baris kosong
                    container.append(`
                        <div class="row mb-2 align-items-center media-item">
                            <div class="col-md-5">
                                <input type="text" name="media[]" class="form-control" placeholder="Nama Media">
                            </div>
                            <div class="col-md-6">
                                <input type="url" name="link_berita[]" class="form-control" placeholder="Link Berita (URL)">
                            </div>
                            <div class="col-md-1 text-center">
                                <button type="button" class="btn btn-danger btn-sm removeMedia">&times;</button>
                            </div>
                        </div>
                    `);
                }

                // Buka modal edit
                $('#modalEditProgram').modal('show');
            },
            error: function (xhr) {
                alert('Gagal memuat data program!');
                console.error(xhr.responseText);
            }
        });
    });

});
// === Event tambah media baru (HANYA DIDAFTARKAN SEKALI) ===
    $('#edit_addMedia').on('click', function () {
        $('#edit_mediaContainer').append(`
            <div class="row mb-2 align-items-center media-item">
                <div class="col-md-5">
                    <input type="text" name="media[]" class="form-control" placeholder="Nama Media">
                </div>
                <div class="col-md-6">
                    <input type="url" name="link_berita[]" class="form-control" placeholder="Link Berita (URL)">
                </div>
                <div class="col-md-1 text-center">
                    <button type="button" class="btn btn-danger btn-sm removeMedia">&times;</button>
                </div>
            </div>
        `);
    });

    // === Event hapus media (delegation, untuk semua item lama & baru) ===
    $(document).on('click', '.removeMedia', function () {
        const parent = $(this).closest('.media-item');
        const mediaId = parent.data('id');

        if (mediaId) {
            // simpan ID publikasi yang akan dihapus ke form
            $('#formEditProgram').append(`
                <input type="hidden" name="delete_publikasi[]" value="${mediaId}">
            `);
        }

        parent.fadeOut(200, () => parent.remove());
    });
    // ============================
    // 4️⃣ INPUT DINAMIS FOTO & VIDEO
    // ============================

    // ===============================
    // ➕ Tambah baris foto baru
    // ===============================
    $('#edit_addFoto').on('click', function () {
    $('#edit_fotoContainer').append(`
        <div class="row mb-2 align-items-center foto-item">
            <div class="col-md-11">
                <input type="file" name="foto[]" class="form-control" accept="image/*">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger btn-sm removeInput">&times;</button>
            </div>
        </div>
    `);
});

// Tambah video baru
$('#edit_addVideo').on('click', function () {
    $('#edit_videoContainer').append(`
        <div class="row mb-2 align-items-center video-item">
            <div class="col-md-11">
                <input type="file" name="video[]" class="form-control" accept="video/*">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-danger btn-sm removeInput">&times;</button>
            </div>
        </div>
    `);
});

    
    $(document).on('click', '.removeInput', function () {
    const parent = $(this).closest('.row');
    const filename = parent.data('filename'); // ambil nama file
    const id = parent.data('id'); // ambil id foto/video jika ada

    // === Jika foto lama ===
    if (parent.hasClass('existing-foto')) {
        if (id) {
            $('#formEditProgram').append(`
                <input type="hidden" name="deleted_foto_ids[]" value="${id}">
            `);
        }
        // Hapus preview dengan nama file yang sama
        $(`#edit_mediaPreview .preview-foto[data-filename="${filename}"]`).fadeOut(200, function() {
            $(this).remove();
        });

        parent.fadeOut(200, () => parent.remove());
        return;
    }

    // === Jika video lama ===
    if (parent.hasClass('existing-video')) {
        if (id) {
            $('#formEditProgram').append(`
                <input type="hidden" name="deleted_video_ids[]" value="${id}">
            `);
        }
        // Hapus preview video yang cocok
        $(`#edit_mediaPreview .preview-video[data-filename="${filename}"]`).fadeOut(200, function() {
            $(this).remove();
        });

        parent.fadeOut(200, () => parent.remove());
        return;
    }

    // === Jika file baru (foto/video baru yang diinput user) ===
    // Cari preview terakhir berdasarkan urutan input
    const index = parent.index();
    const inputType = parent.find('input[type="file"]').attr('accept');

    if (inputType && inputType.includes('image')) {
        // Hapus preview foto terakhir
        const preview = $('#edit_mediaPreview .preview-foto').eq(index);
        preview.fadeOut(200, () => preview.remove());
    } else if (inputType && inputType.includes('video')) {
        // Hapus preview video terakhir
        const preview = $('#edit_mediaPreview .preview-video').eq(index);
        preview.fadeOut(200, () => preview.remove());
    }

    // Hapus input baris
    parent.fadeOut(200, () => parent.remove());
});



    // ===============================
    // 🖼️ Preview foto baru
    // ===============================
    $(document).on('change', 'input[name="foto[]"]', function () {
        const file = this.files[0];
        if (file) {
            $('#edit_mediaPreview').find('p.text-muted').remove();
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#edit_mediaPreview').append(`
                    <div class="media-item text-center me-2 preview-foto">
                        <img src="${e.target.result}" class="rounded" width="100">
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        }
    });

    // ===============================
    // 🎥 Preview video baru
    // ===============================
    $(document).on('change', 'input[name="video[]"]', function () {
        const file = this.files[0];
        if (file) {
            $('#edit_mediaPreview').find('p.text-muted').remove();
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#edit_mediaPreview').append(`
                    <div class="media-item text-center me-2 preview-video">
                        <video width="120" controls>
                            <source src="${e.target.result}" type="${file.type}">
                        </video>
                    </div>
                `);
            };
            reader.readAsDataURL(file);
        }
    });

});
</script>




{{-- SCRIPT modal tambah --}}
<script>
$(document).ready(function () {
    // ✅ Dropdown Region → Kebun (bisa di modal maupun halaman)
    $(document).on('change', '.region-select', function () {
        const region = $(this).val();
        const kebunSelect = $(this).closest('form, .modal-body').find('.kebun-select');
        kebunSelect.html('<option value="">Memuat...</option>');

        $.get('{{ url("/get-kebun-by-region") }}', { region: region }, function (data) {
            let options = '<option value="">Pilih..</option>';
            data.forEach(function (item) {
                options += `<option value="${item.unit}">${item.unit}</option>`;
            });
            kebunSelect.html(options);
        });
    });

    // ✅ Inisialisasi Select2 Desa
    function initSelect2Desa(el) {
        el.select2({
            placeholder: 'Cari Desa...',
            ajax: {
                url: '{{ route("wilayah.desa") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.results
                    };
                },
                cache: true
            },
            minimumInputLength: 2,
            dropdownParent: $('#modalTambahProgram') // agar dropdown muncul di atas modal
        });
    }

    // ✅ Trigger saat modal dibuka (Bootstrap 5 event)
    $('#modalTambahProgram').on('shown.bs.modal', function () {
        initSelect2Desa($('.select2-desa'));
    });


    // === FOTO DINAMIS ===
    $('#addFoto').click(function () {
        const html = `
            <div class="row mb-3 foto-item">
                <div class="col-md-11">
                    <input type="file" name="foto[]" class="form-control media-input" accept="image/*">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-foto">&times;</button>
                </div>
            </div>`;
        $('#fotoContainer').append(html);
    });

    // === Hapus Foto ===
    $(document).on('click', '.remove-foto', function () {
        const container = $(this).closest('.foto-item');
        const input = container.find('input[type="file"]')[0];

        // Hapus preview yang terkait
        if (input && input.dataset.previewId) {
            $(`#${input.dataset.previewId}`).remove();
        }

        container.remove();
    });

    // === VIDEO DINAMIS ===
    $('#addVideo').click(function () {
        const html = `
            <div class="row mb-3 video-item">
                <div class="col-md-11">
                    <input type="file" name="video[]" class="form-control media-input" accept="video/*">
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-video">&times;</button>
                </div>
            </div>`;
        $('#videoContainer').append(html);
    });

    // === Hapus Video ===
    $(document).on('click', '.remove-video', function () {
        const container = $(this).closest('.video-item');
        const input = container.find('input[type="file"]')[0];

        // Hapus preview yang terkait
        if (input && input.dataset.previewId) {
            $(`#${input.dataset.previewId}`).remove();
        }

        container.remove();
    });

    // === MEDIA (FOTO & VIDEO) PREVIEW GABUNGAN ===
    $(document).on('change', '.media-input', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const fileType = file.type;
        const url = URL.createObjectURL(file);

        const previewArea = $('#mediaPreview');
        previewArea.find('p').remove(); // hapus teks default

        // Buat ID unik untuk preview
        const previewId = 'preview_' + Math.random().toString(36).substring(2, 9);
        this.dataset.previewId = previewId;

        let previewHtml = '';
        if (fileType.startsWith('image/')) {
            previewHtml = `
                <div id="${previewId}" class="position-relative d-inline-block me-2 mb-2">
                    <img src="${url}" class="img-thumbnail" style="height:100px; width:auto; border-radius:8px;">
                </div>`;
        } else if (fileType.startsWith('video/')) {
            previewHtml = `
                <div id="${previewId}" class="position-relative d-inline-block me-2 mb-2">
                    <video src="${url}" controls muted
                        style="height:100px; width:auto; border-radius:8px; border:1px solid #ccc;">
                    </video>
                </div>`;
        }

        previewArea.append(previewHtml);
    });



});
</script>
<script>
$(document).ready(function () {
    // Tambah input media baru
    $('#addMedia').on('click', function () {
        const mediaRow = `
        <div class="row g-2 mb-2 media-item">
            <div class="col-md-5">
                <input type="text" name="media[]" class="form-control" placeholder="Nama Media">
            </div>
            <div class="col-md-6">
                <input type="url" name="link_berita[]" class="form-control" placeholder="Link Berita (URL)">
            </div>
            <div class="col-md-1 d-flex align-items-center">
                <button type="button" class="btn btn-danger btn-sm removeMedia">&times;</i></button>
            </div>
        </div>`;
        $('#mediaContainer').append(mediaRow);
    });

    // Hapus input media
    $(document).on('click', '.removeMedia', function () {
        $(this).closest('.media-item').remove();
    });
});
</script>
<script>
    $(document).ready(function() {
$('#program').on('change', function() {
    const program = $(this).val();
    const container = $('#gambarProgramContainer');
    container.html('<p class="text-muted m-0">Memuat gambar...</p>');

    if(program) {
        $.get('{{ url("/program-tjsl/get-gambar") }}', { program: program }, function(data) {
            container.empty();

            // Gambar utama program
            if(data.gambar) {
                container.append(`
                    <img src="{{ asset('img/') }}/${data.gambar}" 
                        alt="Program" class="img-fluid rounded" 
                        style="width:35px; height:35px; object-fit:cover;">
                `);
            }

            // Loop semua gambar TPB
            if(data.gambar_tpb.length > 0) {
                data.gambar_tpb.forEach(gbr => {
                    container.append(`
                        <img src="{{ asset('img/') }}/${gbr}" 
                            alt="TPB" class="img-fluid rounded" 
                            style="width:35px; height:35px; object-fit:cover;">
                    `);
                });
            }

            if(!data.gambar_program && data.gambar_tpb.length === 0) {
                container.html('<p class="text-muted m-0">Tidak ada gambar</p>');
            }
        });
    } else {
        container.html('<p class="text-muted m-0">Tidak ada gambar</p>');
    }
});
});

</script>
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    $(document).on('click', '.btnDeleteProgram', function() {
        let programId = $(this).data('id');

        Swal.fire({
            title: 'Hapus Program?',
            text: 'Apakah Anda yakin ingin menghapus program ini? Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `/delete-program-tjsl/${programId}`,  // pastikan route-nya sesuai
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Terhapus!',
                            text: 'Program berhasil dihapus.',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        // Hapus elemen card dari tampilan
                        $(`.btnDeleteProgram[data-id="${programId}"]`).closest('.col-md-4').fadeOut(500, function() {
                            $(this).remove();
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: 'Terjadi kesalahan saat menghapus data.'
                        });
                    }
                });
            }
        });
    });

</script>

@endsection
