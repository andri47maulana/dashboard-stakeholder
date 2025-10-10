@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Program TJSL</h1>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label for="filterRegion" class="form-label small text-muted">Region</label>
                        <select class="form-control form-control-sm" id="filterRegion">
                            <option value="">Semua Regional</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterPilar" class="form-label small text-muted">Pilar</label>
                        <select class="form-control form-control-sm" id="filterPilar">
                            <option value="">Semua Pilar</option>
                            @foreach ($pilars as $pilar)
                                <option value="{{ $pilar->id }}"
                                    {{ request('pilar_id') == $pilar->id ? 'selected' : '' }}>{{ $pilar->pilar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterKebun" class="form-label small text-muted">Kebun</label>
                        <select class="form-control form-control-sm" id="filterKebun">
                            <option value="">Semua Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="filterTahun" class="form-label small text-muted">Tahun</label>
                        <select class="form-control form-control-sm" id="filterTahun">
                            <option value="">Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-success btn-sm" id="applyFilter">
                                <i class="fas fa-filter"></i> Filter
                            </button>
                            <button type="button" class="btn btn-warning btn-sm" id="resetFilter">
                                <i class="fas fa-undo"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <button type="button" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal"
                data-target="#tjslModal">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Program
            </button>
        </div>

        <!-- Cards Grid -->
        <div class="row" id="programCards">
            @forelse($tjsls as $tjsl)
                <div class="col-xl-3 col-md-6 mb-4 program-card" data-status="{{ $tjsl->status }}"
                    data-program="{{ strtolower($tjsl->nama_program) }}">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <!-- Nama Program -->
                            <div class="row no-gutters align-items-center mb-2">
                                <div class="col">
                                    <div class="text-sm font-weight-bold mb-1">
                                        {{ $tjsl->nama_program }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-warning edit-program-btn"
                                            data-id="{{ $tjsl->id }}" data-toggle="modal" data-target="#editTjslModal"
                                            title="Edit Program">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-program-btn"
                                            data-id="{{ $tjsl->id }}" data-name="{{ $tjsl->nama_program }}"
                                            title="Hapus Program">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Pilar dan Status -->
                            <div class="row no-gutters align-items-center mb-2">
                                <div class="col mr-2">
                                    <div class="badge badge-primary">
                                        {{ $tjsl->pilar->pilar }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    @if ($tjsl->status == 1)
                                        <span class="badge badge-primary">Proposed</span>
                                    @elseif($tjsl->status == 2)
                                        <span class="badge badge-warning">Active</span>
                                    @elseif($tjsl->status == 3)
                                        <span class="badge badge-success">Completed</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Sub Pilar -->
                            <div class="row no-gutters align-items-center mb-2">
                                <div class="col">
                                    <div class="text-xs mb-0 text-gray-600 d-flex align-items-center">
                                        @if ($tjsl->hasSubPilarImages())
                                            @foreach ($tjsl->sub_pilar_images as $image)
                                                <img src="{{ $image['path'] }}" alt="{{ $image['alt'] }}" class="me-1"
                                                    style="width: 50px; height: 50px; object-fit: contain;"
                                                    title="{{ $image['alt'] }}">
                                                <span style="margin-right: 5px;"> </span>
                                            @endforeach
                                        @else
                                            <i class="fas fa-tag fa-sm text-gray-400 me-2"></i>
                                        @endif

                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Mulai -->
                            <div class="row no-gutters align-items-center mb-2">
                                <div class="col">
                                    <div class="text-sm mb-0 text-black-600">
                                        <i class="fas fa-calendar-alt fa-sm text-black-400"></i>
                                        Mulai : {{ $tjsl->tanggal_mulai->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Akhir -->
                            <div class="row no-gutters align-items-center mb-3">
                                <div class="col">
                                    <div class="text-sm mb-0 text-black-600">
                                        <i class="fas fa-calendar-check fa-sm text-black-400"></i>
                                        Selesai: {{ $tjsl->tanggal_akhir->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Button Lihat Detail -->
                            <div class="row no-gutters">
                                <div class="col">
                                    <a href="{{ route('tjsl.show', $tjsl->id) }}"
                                        class="btn btn-primary btn-sm btn-block">
                                        <i class="fas fa-eye fa-sm"></i> Lihat Program
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada program TJSL</h5>
                            <p class="text-muted">Mulai dengan menambahkan program TJSL pertama Anda.</p>
                            <a href="{{ route('tjsl.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Program Pertama
                            </a>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($tjsls->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $tjsls->links() }}
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Input Data TJSL Komprehensif -->
    <div class="modal fade" id="tjslModal" tabindex="-1" role="dialog" aria-labelledby="tjslModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tjslModalLabel">
                        <i class="fas fa-plus-circle"></i> Tambah Program TJSL Lengkap
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="tjslForm" action="{{ route('tjsl.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="tjslTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="program-tab" data-toggle="tab" href="#program"
                                    role="tab">
                                    <i class="fas fa-info-circle"></i> Data Program
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="biaya-tab" data-toggle="tab" href="#biaya" role="tab">
                                    <i class="fas fa-money-bill"></i> Biaya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="publikasi-tab" data-toggle="tab" href="#publikasi"
                                    role="tab">
                                    <i class="fas fa-newspaper"></i> Publikasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="dokumentasi-tab" data-toggle="tab" href="#dokumentasi"
                                    role="tab">
                                    <i class="fas fa-file-alt"></i> Dokumentasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="feedback-tab" data-toggle="tab" href="#feedback"
                                    role="tab">
                                    <i class="fas fa-comments"></i> Feedback
                                </a>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content mt-3" id="tjslTabContent">
                            <!-- Tab Data Program -->
                            <div class="tab-pane fade show active" id="program" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="nama_program" class="form-label">Nama Program <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="nama_program"
                                                name="nama_program" required>
                                            <div class="invalid-feedback">
                                                Nama program wajib diisi.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="unit_id" class="form-label">Unit/Kebun <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="unit_id" name="unit_id" required>
                                                <option value="">Pilih Unit</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Unit/Kebun wajib dipilih.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="pilar_id" class="form-label">Pilar <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="pilar_id" name="pilar_id" required>
                                                <option value="">Pilih Pilar</option>
                                                @foreach ($pilars as $pilar)
                                                    <option value="{{ $pilar->id }}">{{ $pilar->pilar }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Pilar wajib dipilih.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="sub_pilar" class="form-label">Sub Pilar</label>
                                            <select class="form-control" id="sub_pilar" name="sub_pilar[]" multiple>
                                                @foreach ($subpilars as $subPilar)
                                                    <option value="{{ $subPilar->id }}">{{ $subPilar->sub_pilar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Pilih satu atau lebih sub pilar (gunakan
                                                Ctrl+Click untuk memilih multiple)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="deskripsi" class="form-label">Deskripsi Program</label>
                                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="lokasi_program" class="form-label">Lokasi Program</label>
                                            <input type="text" class="form-control" id="lokasi_program"
                                                name="lokasi_program">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="penerima_dampak" class="form-label">Penerima Dampak</label>
                                            <input type="text" class="form-control" id="penerima_dampak"
                                                name="penerima_dampak">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="tanggal_mulai"
                                                name="tanggal_mulai">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="tanggal_akhir"
                                                name="tanggal_akhir">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="status" class="form-label">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="1">Proposed</option>
                                                <option value="2">Active</option>
                                                <option value="3">Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tpb" class="form-label">TPB</label>
                                            <input type="text" class="form-control" id="tpb" name="tpb"
                                                placeholder="Contoh: 1,2,3">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Biaya TJSL -->
                            <div class="tab-pane fade" id="biaya" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6><i class="fas fa-money-bill text-success"></i> Data Biaya TJSL</h6>

                                </div>
                                <div id="biayaContainer">
                                    <div class="biaya-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-5">
                                                <label class="form-label">Anggaran (Rp)</label>
                                                <input type="number" class="form-control" name="biaya[0][anggaran]"
                                                    step="0.01" placeholder="0.00">
                                            </div>
                                            <div class="col-md-5">
                                                <label class="form-label">Realisasi (Rp)</label>
                                                <input type="number" class="form-control" name="biaya[0][realisasi]"
                                                    step="0.01" placeholder="0.00">
                                            </div>
                                            {{-- <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-biaya"
                                                    disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div> --}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Publikasi -->
                            <div class="tab-pane fade" id="publikasi" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6><i class="fas fa-newspaper text-info"></i> Data Publikasi TJSL</h6>
                                    <button type="button" class="btn btn-sm btn-info" id="addPublikasi">
                                        <i class="fas fa-plus"></i> Tambah Publikasi
                                    </button>
                                </div>
                                <div id="publikasiContainer">
                                    <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Media</label>
                                                <input type="text" class="form-control" name="publikasi[0][media]"
                                                    placeholder="Nama Media">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Link</label>
                                                <input type="url" class="form-control" name="publikasi[0][link]"
                                                    placeholder="https://...">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-publikasi"
                                                    disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Dokumentasi -->
                            <div class="tab-pane fade" id="dokumentasi" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6><i class="fas fa-file-alt text-warning"></i> Data Dokumentasi TJSL</h6>
                                    <button type="button" class="btn btn-sm btn-warning" id="addDokumentasi">
                                        <i class="fas fa-plus"></i> Tambah Dokumentasi
                                    </button>
                                </div>
                                <div id="dokumentasiContainer">
                                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <label class="form-label">Nama Dokumen</label>
                                                <input type="text" class="form-control"
                                                    name="dokumentasi[0][nama_dokumen]" placeholder="Nama Dokumen">
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">Link Dokumen</label>
                                                <input type="url" class="form-control" name="dokumentasi[0][link]"
                                                    placeholder="https://...">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-dokumentasi"
                                                    disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Feedback -->
                            <div class="tab-pane fade" id="feedback" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6><i class="fas fa-comments text-primary"></i> Data Feedback TJSL</h6>
                                    {{-- <button type="button" class="btn btn-sm btn-primary" id="addFeedback">
                                        <i class="fas fa-plus"></i> Tambah Feedback
                                    </button> --}}
                                </div>
                                <div id="feedbackContainer">
                                    <div class="feedback-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label class="form-label">Sangat Puas</label>
                                                <input type="number" class="form-control"
                                                    name="feedback[0][sangat_puas]" placeholder="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Puas</label>
                                                <input type="number" class="form-control" name="feedback[0][puas]"
                                                    placeholder="0">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Kurang Puas</label>
                                                <input type="number" class="form-control"
                                                    name="feedback[0][kurang_puas]" placeholder="0">
                                            </div>
                                            {{-- <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-feedback"
                                                    disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div> --}}
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <label class="form-label">Saran</label>
                                                <textarea class="form-control" name="feedback[0][saran]" rows="2" placeholder="Masukkan saran..."></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save"></i> Simpan Semua Data TJSL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Program TJSL -->
    <div class="modal fade" id="editTjslModal" tabindex="-1" role="dialog" aria-labelledby="editTjslModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editTjslModalLabel">
                        <i class="fas fa-edit"></i> Edit Program TJSL
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editTjslForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="editTjslTabs" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="edit-program-tab" data-toggle="tab" href="#edit-program"
                                    role="tab">
                                    <i class="fas fa-info-circle"></i> Data Program
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-biaya-tab" data-toggle="tab" href="#edit-biaya"
                                    role="tab">
                                    <i class="fas fa-money-bill"></i> Biaya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-publikasi-tab" data-toggle="tab" href="#edit-publikasi"
                                    role="tab">
                                    <i class="fas fa-newspaper"></i> Publikasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-dokumentasi-tab" data-toggle="tab" href="#edit-dokumentasi"
                                    role="tab">
                                    <i class="fas fa-file-alt"></i> Dokumentasi
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="edit-feedback-tab" data-toggle="tab" href="#edit-feedback"
                                    role="tab">
                                    <i class="fas fa-comments"></i> Feedback
                                </a>
                            </li>
                        </ul>

                        <!-- Tab content -->
                        <div class="tab-content mt-3" id="editTjslTabContent">
                            <!-- Tab Data Program -->
                            <div class="tab-pane fade show active" id="edit-program" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_nama_program" class="form-label">Nama Program <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="edit_nama_program"
                                                name="nama_program" required>
                                            <div class="invalid-feedback">
                                                Nama program wajib diisi.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_unit_id" class="form-label">Unit/Kebun <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="edit_unit_id" name="unit_id" required>
                                                <option value="">Pilih Unit</option>
                                                @foreach ($units as $unit)
                                                    <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Unit/Kebun wajib dipilih.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_pilar_id" class="form-label">Pilar <span
                                                    class="text-danger">*</span></label>
                                            <select class="form-control" id="edit_pilar_id" name="pilar_id" required>
                                                <option value="">Pilih Pilar</option>
                                                @foreach ($pilars as $pilar)
                                                    <option value="{{ $pilar->id }}">{{ $pilar->pilar }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback">
                                                Pilar wajib dipilih.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_sub_pilar" class="form-label">Sub Pilar</label>
                                            <select class="form-control" id="edit_sub_pilar" name="sub_pilar[]" multiple>
                                                @foreach ($subpilars as $subPilar)
                                                    <option value="{{ $subPilar->id }}">{{ $subPilar->sub_pilar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Pilih satu atau lebih sub pilar (gunakan
                                                Ctrl+Click untuk memilih multiple)</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="edit_deskripsi" class="form-label">Deskripsi Program</label>
                                    <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_lokasi_program" class="form-label">Lokasi Program</label>
                                            <input type="text" class="form-control" id="edit_lokasi_program"
                                                name="lokasi_program">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_penerima_dampak" class="form-label">Penerima Dampak</label>
                                            <input type="text" class="form-control" id="edit_penerima_dampak"
                                                name="penerima_dampak">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                            <input type="date" class="form-control" id="edit_tanggal_mulai"
                                                name="tanggal_mulai">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                            <input type="date" class="form-control" id="edit_tanggal_akhir"
                                                name="tanggal_akhir">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_status" class="form-label">Status</label>
                                            <select class="form-control" id="edit_status" name="status">
                                                <option value="1">Proposed</option>
                                                <option value="2">Active</option>
                                                <option value="3">Completed</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_tpb" class="form-label">TPB/SDGs</label>
                                            <input type="text" class="form-control" id="edit_tpb" name="tpb">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Biaya -->
                            <div class="tab-pane fade" id="edit-biaya" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Data Biaya Program</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="editAddBiaya">
                                        <i class="fas fa-plus"></i> Tambah Biaya
                                    </button>
                                </div>
                                <div id="editBiayaContainer">
                                    <!-- Biaya items will be loaded here -->
                                </div>
                            </div>

                            <!-- Tab Publikasi -->
                            <div class="tab-pane fade" id="edit-publikasi" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Data Publikasi Program</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="editAddPublikasi">
                                        <i class="fas fa-plus"></i> Tambah Publikasi
                                    </button>
                                </div>
                                <div id="editPublikasiContainer">
                                    <!-- Publikasi items will be loaded here -->
                                </div>
                            </div>

                            <!-- Tab Dokumentasi -->
                            <div class="tab-pane fade" id="edit-dokumentasi" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Dokumentasi Program</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="editAddDokumentasi">
                                        <i class="fas fa-plus"></i> Tambah Dokumentasi
                                    </button>
                                </div>
                                <div id="editDokumentasiContainer">
                                    <!-- Dokumentasi items will be loaded here -->
                                </div>
                            </div>

                            <!-- Tab Feedback -->
                            <div class="tab-pane fade" id="edit-feedback" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Feedback Program</h6>
                                    <button type="button" class="btn btn-sm btn-success" id="editAddFeedback">
                                        <i class="fas fa-plus"></i> Tambah Feedback
                                    </button>
                                </div>
                                <div id="editFeedbackContainer">
                                    <!-- Feedback items will be loaded here -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                            <i class="fas fa-save"></i> Update Program TJSL
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Declare dynamic form index variables
            let biayaIndex = 1;
            let publikasiIndex = 1;
            let dokumentasiIndex = 1;
            let feedbackIndex = 1;

            // Apply filter button
            $('#applyFilter').click(function() {
                applyFilters();
            });

            // Handle Edit Program Button Click
            $(document).on('click', '.edit-program-btn', function() {
                const tjslId = $(this).data('id');

                // Set form action URL
                $('#editTjslForm').attr('action', `/tjsl/${tjslId}`);

                // Load TJSL data
                loadTjslData(tjslId);
            });

            // Handle Delete Program Button Click
            $(document).on('click', '.delete-program-btn', function() {
                const tjslId = $(this).data('id');
                const programName = $(this).data('name');

                // Show confirmation dialog
                if (confirm(
                        `Apakah Anda yakin ingin menghapus program "${programName}"?\n\nTindakan ini tidak dapat dibatalkan dan akan menghapus semua data terkait program ini.`
                        )) {
                    // Create form for DELETE request
                    const form = $('<form>', {
                        'method': 'POST',
                        'action': `/tjsl/${tjslId}`
                    });

                    // Add CSRF token
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_token',
                        'value': '{{ csrf_token() }}'
                    }));

                    // Add method spoofing for DELETE
                    form.append($('<input>', {
                        'type': 'hidden',
                        'name': '_method',
                        'value': 'DELETE'
                    }));

                    // Append form to body and submit
                    $('body').append(form);
                    form.submit();
                }
            });

            // Function to load TJSL data for editing
            function loadTjslData(tjslId) {
                $.ajax({
                    url: `/tjsl/${tjslId}/edit-data`,
                    method: 'GET',
                    beforeSend: function() {
                        // Show loading overlay without removing modal content
                        if (!$('#editTjslModal .loading-overlay').length) {
                            $('#editTjslModal .modal-content').append(
                                '<div class="loading-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: rgba(255,255,255,0.8); z-index: 9999; display: flex; align-items: center; justify-content: center;">' +
                                '<div class="text-center"><i class="fas fa-spinner fa-spin fa-2x"></i><br><small>Loading...</small></div>' +
                                '</div>'
                            );
                        }
                    },
                    success: function(response) {
                        // Remove loading overlay
                        $('#editTjslModal .loading-overlay').remove();

                        // Debug: log the response to see what dates we're getting
                        // console.log('Response data:', response);
                        // console.log('tanggal_mulai:', response.tanggal_mulai);
                        // console.log('tanggal_akhir:', response.tanggal_akhir);

                        // Format dates for HTML date inputs
                        let tanggalMulai = response.tanggal_mulai ? response.tanggal_mulai.split('T')[
                            0] : '';
                        let tanggalAkhir = response.tanggal_akhir ? response.tanggal_akhir.split('T')[
                            0] : '';

                        // Populate form fields
                        $('#edit_nama_program').val(response.nama_program);
                        $('#edit_unit_id').val(response.unit_id);
                        $('#edit_pilar_id').val(response.pilar_id);
                        $('#edit_deskripsi').val(response.deskripsi);
                        $('#edit_lokasi_program').val(response.lokasi_program);
                        $('#edit_penerima_dampak').val(response.penerima_dampak);
                        $('#edit_tanggal_mulai').val(tanggalMulai);
                        $('#edit_tanggal_akhir').val(tanggalAkhir);
                        $('#edit_status').val(response.status);
                        $('#edit_tpb').val(response.tpb);

                        // Handle sub_pilar (multiple select)
                        if (response.sub_pilar && Array.isArray(response.sub_pilar)) {
                            $('#edit_sub_pilar').val(response.sub_pilar);
                        }

                        // Load related data
                        loadEditBiayaData(response.biaya || []);
                        loadEditPublikasiData(response.publikasi || []);
                        loadEditDokumentasiData(response.dokumentasi || []);
                        loadEditFeedbackData(response.feedback || []);
                    },
                    error: function(xhr, status, error) {
                        // Remove loading overlay
                        $('#editTjslModal .loading-overlay').remove();
                        alert('Error loading data: ' + error);
                        $('#editTjslModal').modal('hide');
                    }
                });
            }

            // Functions to load related data
            function loadEditBiayaData(biayaData) {
                const container = $('#editBiayaContainer');
                container.empty();

                biayaData.forEach(function(biaya, index) {
                    const biayaHtml = `
                    <div class="biaya-item border p-3 mb-3 rounded bg-light">
                        <input type="hidden" name="biaya[${index}][id]" value="${biaya.id || ''}">
                        <div class="row">
                            <div class="col-md-5">
                                <label class="form-label">Anggaran (Rp)</label>
                                <input type="number" class="form-control" name="biaya[${index}][anggaran]"
                                       value="${biaya.anggaran || ''}" step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Realisasi (Rp)</label>
                                <input type="number" class="form-control" name="biaya[${index}][realisasi]"
                                       value="${biaya.realisasi || ''}" step="0.01" placeholder="0.00">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-edit-biaya">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                    container.append(biayaHtml);
                });
            }

            function loadEditPublikasiData(publikasiData) {
                const container = $('#editPublikasiContainer');
                container.empty();

                publikasiData.forEach(function(publikasi, index) {
                    const publikasiHtml = `
                    <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                        <input type="hidden" name="publikasi[${index}][id]" value="${publikasi.id || ''}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Media</label>
                                <input type="text" class="form-control" name="publikasi[${index}][media]"
                                       value="${publikasi.media || ''}" placeholder="Nama Media">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Link</label>
                                <input type="url" class="form-control" name="publikasi[${index}][link]"
                                       value="${publikasi.link || ''}" placeholder="https://...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-edit-publikasi">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                    container.append(publikasiHtml);
                });
            }

            function loadEditDokumentasiData(dokumentasiData) {
                const container = $('#editDokumentasiContainer');
                container.empty();

                dokumentasiData.forEach(function(dokumentasi, index) {
                    const dokumentasiHtml = `
                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                        <input type="hidden" name="dokumentasi[${index}][id]" value="${dokumentasi.id || ''}">
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Nama Dokumen</label>
                                <input type="text" class="form-control" name="dokumentasi[${index}][nama_dokumen]"
                                       value="${dokumentasi.nama_dokumen || ''}" placeholder="Nama Dokumen">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Link Dokumen</label>
                                <input type="url" class="form-control" name="dokumentasi[${index}][link]"
                                       value="${dokumentasi.link || ''}" placeholder="https://...">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-edit-dokumentasi">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                `;
                    container.append(dokumentasiHtml);
                });
            }

            function loadEditFeedbackData(feedbackData) {
                const container = $('#editFeedbackContainer');
                container.empty();

                feedbackData.forEach(function(feedback, index) {
                    const feedbackHtml = `
                    <div class="feedback-item border p-3 mb-3 rounded bg-light">
                        <input type="hidden" name="feedback[${index}][id]" value="${feedback.id || ''}">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="form-label">Sangat Puas</label>
                                <input type="number" class="form-control" name="feedback[${index}][sangat_puas]"
                                       value="${feedback.sangat_puas || ''}" min="0" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Puas</label>
                                <input type="number" class="form-control" name="feedback[${index}][puas]"
                                       value="${feedback.puas || ''}" min="0" placeholder="0">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kurang Puas</label>
                                <input type="number" class="form-control" name="feedback[${index}][kurang_puas]"
                                       value="${feedback.kurang_puas || ''}" min="0" placeholder="0">
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger btn-sm remove-edit-feedback">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                                <label class="form-label">Saran</label>
                                <textarea class="form-control" name="feedback[${index}][saran]"
                                          rows="2" placeholder="Masukkan saran...">${feedback.saran || ''}</textarea>
                            </div>
                        </div>
                    </div>
                `;
                    container.append(feedbackHtml);
                });
            }

            // Add new items in edit modal
            let editBiayaIndex = 1000; // Start with high number to avoid conflicts
            let editPublikasiIndex = 1000;
            let editDokumentasiIndex = 1000;
            let editFeedbackIndex = 1000;

            $('#editAddBiaya').click(function() {
                const biayaHtml = `
                <div class="biaya-item border p-3 mb-3 rounded bg-light">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">Anggaran (Rp)</label>
                            <input type="number" class="form-control" name="biaya[${editBiayaIndex}][anggaran]" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Realisasi (Rp)</label>
                            <input type="number" class="form-control" name="biaya[${editBiayaIndex}][realisasi]" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-edit-biaya">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                $('#editBiayaContainer').append(biayaHtml);
                editBiayaIndex++;
            });

            // Remove items in edit modal
            $(document).on('click', '.remove-edit-biaya', function() {
                $(this).closest('.biaya-item').remove();
            });

            $(document).on('click', '.remove-edit-publikasi', function() {
                $(this).closest('.publikasi-item').remove();
            });

            $(document).on('click', '.remove-edit-dokumentasi', function() {
                $(this).closest('.dokumentasi-item').remove();
            });

            $(document).on('click', '.remove-edit-feedback', function() {
                $(this).closest('.feedback-item').remove();
            });

            // Reset edit modal when closed
            $('#editTjslModal').on('hidden.bs.modal', function() {
                $('#editTjslForm')[0].reset();
                $('#editBiayaContainer').empty();
                $('#editPublikasiContainer').empty();
                $('#editDokumentasiContainer').empty();
                $('#editFeedbackContainer').empty();

                // Reset to first tab
                $('#edit-program-tab').tab('show');

                // Clear validation states
                $('.is-invalid').removeClass('is-invalid');
            });

            // Auto filter on change (KECUALI region - karena region punya handler khusus)
            $('#filterPilar, #filterKebun, #filterTahun, #filterStatus').change(function() {
                applyFilters();
            });

            // Search on input
            $('#searchProgram').on('input', function() {
                clearTimeout(window.searchTimeout);
                window.searchTimeout = setTimeout(function() {
                    applyFilters();
                }, 500);
            });

            function applyFilters() {
                const filters = {
                    region: $('#filterRegion').val(),
                    pilar_id: $('#filterPilar').val(),
                    unit_id: $('#filterKebun').val(),
                    tahun: $('#filterTahun').val(),
                    status: $('#filterStatus').val(),
                    search: $('#searchProgram').val()
                };

                // Build query string
                const queryString = Object.keys(filters)
                    .filter(key => filters[key] !== '')
                    .map(key => key + '=' + encodeURIComponent(filters[key]))
                    .join('&');

                // Redirect with filters
                window.location.href = '{{ route('tjsl.index') }}' + (queryString ? '?' + queryString : '');
            }

            // Filter kebun berdasarkan region yang dipilih
            $('#filterRegion').on('change', function() {
                const selectedRegion = $(this).val();
                const kebunSelect = $('#filterKebun');


                if (selectedRegion && selectedRegion.trim() !== '') {

                    // AJAX call untuk mendapatkan unit berdasarkan region
                    $.ajax({
                        url: '{{ route('get.units.by.region') }}',
                        method: 'GET',
                        data: {
                            region: selectedRegion
                        },
                        beforeSend: function() {
                            kebunSelect.html('<option value="">Loading...</option>');
                        },
                        success: function(response) {


                            kebunSelect.html('<option value="">Semua Kebun</option>');

                            if (response && Array.isArray(response) && response.length > 0) {
                                response.forEach(function(unit) {
                                    kebunSelect.append(
                                        `<option value="${unit.id}">${unit.unit}</option>`
                                    );
                                });
                            } else {

                                kebunSelect.append('<option value="">Tidak ada kebun</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            kebunSelect.html('<option value="">Error loading data</option>');
                        }
                    });
                } else {
                    // Reset ke semua kebun
                    kebunSelect.html('<option value="">Semua Kebun</option>');
                    @foreach ($units as $unit)
                        kebunSelect.append(
                            '<option value="{{ $unit->id }}">{{ $unit->unit }}</option>');
                    @endforeach
                }
            });

            // Reset filter button
            $('#resetFilter').click(function() {
                $('#filterRegion').val('');
                $('#filterPilar').val('');
                $('#filterKebun').val('');
                $('#filterTahun').val('');
                $('#filterStatus').val('');
                $('#searchProgram').val('');

                // Trigger region change to reset kebun dropdown
                $('#filterRegion').trigger('change');

                // Apply filters (redirect to clean URL)
                window.location.href = '{{ route('tjsl.index') }}';
            });

            // Manual test setelah 3 detik
            setTimeout(function() {
                $('#filterRegion').val('').trigger('change');
            }, 3000);
        });
    </script>

    <script>
        $(document).ready(function() {
            // Add Biaya
            $('#addBiaya').click(function() {
                const biayaHtml = `
            <div class="biaya-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-5">
                        <label class="form-label">Anggaran (Rp)</label>
                        <input type="number" class="form-control" name="biaya[${biayaIndex}][anggaran]" step="0.01" placeholder="0.00">
                    </div>
                    <div class="col-md-5">
                        <label class="form-label">Realisasi (Rp)</label>
                        <input type="number" class="form-control" name="biaya[${biayaIndex}][realisasi]" step="0.01" placeholder="0.00">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-biaya">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
                $('#biayaContainer').append(biayaHtml);
                biayaIndex++;
                updateRemoveButtons();
            });

            // Add Publikasi
            $('#addPublikasi').click(function() {
                const publikasiHtml = `
            <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Media</label>
                        <input type="text" class="form-control" name="publikasi[${publikasiIndex}][media]" placeholder="Nama Media">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link</label>
                        <input type="url" class="form-control" name="publikasi[${publikasiIndex}][link]" placeholder="https://...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-publikasi">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
                $('#publikasiContainer').append(publikasiHtml);
                publikasiIndex++;
                updateRemoveButtons();
            });

            // Add Dokumentasi
            $('#addDokumentasi').click(function() {
                const dokumentasiHtml = `
            <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Nama Dokumen</label>
                        <input type="text" class="form-control" name="dokumentasi[${dokumentasiIndex}][nama_dokumen]" placeholder="Nama Dokumen">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Link Dokumen</label>
                        <input type="url" class="form-control" name="dokumentasi[${dokumentasiIndex}][link]" placeholder="https://...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-dokumentasi">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
                $('#dokumentasiContainer').append(dokumentasiHtml);
                dokumentasiIndex++;
                updateRemoveButtons();
            });

            // Add Feedback
            $('#addFeedback').click(function() {
                const feedbackHtml = `
            <div class="feedback-item border p-3 mb-3 rounded bg-light">
                <div class="row">
                    <div class="col-md-3">
                        <label class="form-label">Sangat Puas (%)</label>
                        <input type="number" class="form-control" name="feedback[${feedbackIndex}][sangat_puas]" step="0.01" min="0" max="100" placeholder="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Puas (%)</label>
                        <input type="number" class="form-control" name="feedback[${feedbackIndex}][puas]" step="0.01" min="0" max="100" placeholder="0.00">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Kurang Puas (%)</label>
                        <input type="number" class="form-control" name="feedback[${feedbackIndex}][kurang_puas]" step="0.01" min="0" max="100" placeholder="0.00">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-feedback">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12">
                        <label class="form-label">Saran</label>
                        <textarea class="form-control" name="feedback[${feedbackIndex}][saran]" rows="2" placeholder="Masukkan saran..."></textarea>
                    </div>
                </div>
            </div>
        `;
                $('#feedbackContainer').append(feedbackHtml);
                feedbackIndex++;
                updateRemoveButtons();
            });

            // Remove handlers
            $(document).on('click', '.remove-biaya', function() {
                $(this).closest('.biaya-item').remove();
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-publikasi', function() {
                $(this).closest('.publikasi-item').remove();
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-dokumentasi', function() {
                $(this).closest('.dokumentasi-item').remove();
                updateRemoveButtons();
            });

            $(document).on('click', '.remove-feedback', function() {
                $(this).closest('.feedback-item').remove();
                updateRemoveButtons();
            });

            // Update remove button states
            function updateRemoveButtons() {
                // $('.remove-biaya').prop('disabled', $('.biaya-item').length <= 1);
                $('.remove-publikasi').prop('disabled', $('.publikasi-item').length <= 1);
                $('.remove-dokumentasi').prop('disabled', $('.dokumentasi-item').length <= 1);
                // $('.remove-feedback').prop('disabled', $('.feedback-item').length <= 1);
            }

            // Form submission with loading state
            $('#tjslForm').on('submit', function(e) {
                let isValid = true;
                const requiredFields = ['nama_program', 'unit_id', 'pilar_id'];

                // Validate required fields
                requiredFields.forEach(function(field) {
                    const input = $(`#${field}`);
                    if (!input.val().trim()) {
                        input.addClass('is-invalid');
                        isValid = false;
                    } else {
                        input.removeClass('is-invalid');
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    alert('Mohon lengkapi semua field yang wajib diisi!');
                    $('#program-tab').tab('show'); // Switch to program tab
                    return false;
                }

                // Show loading state
                const submitBtn = $('#submitBtn');
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
            });

            // Initialize Select2 for multi-select sub_pilar
            $('#sub_pilar').select2({
                placeholder: "Pilih Sub Pilar",
                allowClear: true,
                width: '100%'
            });

            // Reset Select2 when modal is closed
            $('#tjslModal').on('hidden.bs.modal', function() {
                // Reset form
                $('#tjslForm')[0].reset();
                $('#tjslForm').removeClass('was-validated');

                // Reset Select2
                $('#sub_pilar').val(null).trigger('change');

                // Reset to first tab (Bootstrap 4 syntax)
                $('#program-tab').tab('show');

                // Reset dynamic forms to initial state
                $('.biaya-item:not(:first)').remove();
                $('.publikasi-item:not(:first)').remove();
                $('.dokumentasi-item:not(:first)').remove();
                $('.feedback-item:not(:first)').remove();

                // Reset index counters
                biayaIndex = 1;
                publikasiIndex = 1;
                dokumentasiIndex = 1;
                feedbackIndex = 1;

                updateRemoveButtons();
            });

            // Initialize remove button states
            updateRemoveButtons();
        });
    </script>

    <style>
        .card {
            transition: transform 0.2s ease-in-out;
        }

        .card:hover {
            transform: translateY(-2px);
        }

        .border-left-primary {
            border-left: 0.25rem solid #4e73df !important;
        }

        .program-card[data-status="1"] .card {
            border-left-color: #1cc88a !important;
        }

        .program-card[data-status="2"] .card {
            border-left-color: #f6c23e !important;
        }

        .program-card[data-status="0"] .card {
            border-left-color: #6c757d !important;
        }

        .text-xs {
            font-size: 0.75rem;
        }
    </style>
@endsection
