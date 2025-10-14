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
                                <div class="col mr-2">
                                    @if ($tjsl->hasProgramUnggulanImage())
                                        <img src="{{ $tjsl->program_unggulan_image }}"
                                            alt="{{ $tjsl->programUnggulan->program_unggulan ?? 'Program Unggulan' }}"
                                            style="width:50px; height:50px; object-fit:contain;">
                                    @else
                                        <div class="badge badge-secondary d-inline-flex align-items-center"
                                            style="border-radius:999px; font-weight:600; padding:.35rem .6rem;">
                                            <i class="fas fa-award mr-1"></i>
                                            {{ $tjsl->programUnggulan->program_unggulan ?? 'Program Unggulan' }}
                                        </div>
                                    @endif
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
                                    <div class="col-md-12">
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
                                </div>
                                <div class="row">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="program_unggulan_id" class="form-label">Program Unggulan</label>
                                            <select class="form-control" id="program_unggulan_id"
                                                name="program_unggulan_id">
                                                <option value="">Pilih Program Unggulan</option>
                                                @php
                                                    // Jika controller belum mengirimkan $programUnggulans, ambil di sini
                                                    $programUnggulans = \App\Models\ProgramUnggulan::all();
                                                @endphp
                                                @foreach ($programUnggulans as $pu)
                                                    <option value="{{ $pu->id }}"
                                                        data-subpilars='@json($pu->sub_pilar)'>
                                                        {{ $pu->program_unggulan }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                    <div class="col-md-12">
                                        <label class="form-label small d-block mb-2">Lokasi Program</label>
                                        <div class="border rounded p-3">
                                            <div class="row">
                                                <!-- Kolom kiri: Provinsi + Kabupaten/Kota -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label small">Provinsi</label>
                                                        <select id="lokasi_provinsi" name="lokasi_provinsi"
                                                            class="form-control"></select>
                                                        <small class="text-muted">Pilih provinsi</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small">Kabupaten/Kota</label>
                                                        <select id="lokasi_kabupaten" name="lokasi_kabupaten"
                                                            class="form-control" disabled></select>
                                                        <small class="text-muted">Pilih kabupaten/kota berdasarkan
                                                            provinsi</small>
                                                    </div>
                                                </div>

                                                <!-- Kolom kanan: Kecamatan + Desa/Kelurahan -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label small">Kecamatan</label>
                                                        <select id="lokasi_kecamatan" name="lokasi_kecamatan"
                                                            class="form-control" disabled></select>
                                                        <small class="text-muted">Pilih kecamatan berdasarkan
                                                            kabupaten/kota</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small">Desa/Kelurahan</label>
                                                        <select id="lokasi_desa" name="lokasi_desa" class="form-control"
                                                            disabled></select>
                                                        <small class="text-muted">Pilih desa/kelurahan berdasarkan
                                                            kecamatan</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Nilai gabungan nama lokasi untuk dikirim ke server -->
                                            <input type="hidden" id="lokasi_program" name="lokasi_program">

                                            <!-- Input Koordinat -->
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label small" for="latitude">Latitude</label>
                                                        <input type="text" class="form-control" id="latitude"
                                                            name="latitude" placeholder="-6.2">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="mb-2">
                                                        <label class="form-label small" for="longitude">Longitude</label>
                                                        <input type="text" class="form-control" id="longitude"
                                                            name="longitude" placeholder="106.8">
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Peta Klik untuk Koordinat -->
                                            <div class="mt-2">
                                                <div id="lokasiMap"
                                                    style="height: 300px; border: 1px solid #ddd; border-radius: 6px;">
                                                </div>
                                            </div>

                                            <!-- Gabungan koordinat untuk backend (format: lat,lng) -->
                                            <input type="hidden" id="koordinat" name="koordinat">
                                        </div>
                                    </div>
                                </div>

                                <!-- Penerima Dampak dipindahkan tepat di bawah bingkai lokasi -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
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
                                    <button type="button" class="btn btn-sm btn-success" id="addBiaya">
                                        <i class="fas fa-plus"></i> Tambah Biaya
                                    </button>
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
                                                <label class="form-label">Keterangan</label>
                                                <input type="text" class="form-control" name="biaya[0][keterangan]"
                                                    placeholder="Keterangan biaya">
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm removeBiaya">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
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
                                    <div class="col-md-12">
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
                                </div>
                                <div class="row">
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
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="edit_program_unggulan_id" class="form-label">Program
                                                Unggulan</label>
                                            <select class="form-control" id="edit_program_unggulan_id"
                                                name="program_unggulan_id">
                                                <option value="">Pilih Program Unggulan</option>
                                                @php
                                                    $programUnggulans =
                                                        $programUnggulans ?? \App\Models\ProgramUnggulan::all();
                                                @endphp
                                                @foreach ($programUnggulans as $pu)
                                                    <option value="{{ $pu->id }}"
                                                        data-subpilars='@json($pu->sub_pilar)'>
                                                        {{ $pu->program_unggulan }}
                                                    </option>
                                                @endforeach
                                            </select>
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
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label class="form-label">Lokasi Program</label>
                                            <div class="border rounded p-3">
                                                <div class="row">
                                                    <!-- Kolom kiri: Provinsi + Kabupaten/Kota -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label small">Provinsi</label>
                                                            <select id="edit_lokasi_provinsi" name="edit_lokasi_provinsi"
                                                                class="form-control"></select>
                                                            <small class="text-muted">Pilih provinsi</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small">Kabupaten/Kota</label>
                                                            <select id="edit_lokasi_kabupaten"
                                                                name="edit_lokasi_kabupaten" class="form-control"
                                                                disabled></select>
                                                            <small class="text-muted">Pilih kabupaten/kota berdasarkan
                                                                provinsi</small>
                                                        </div>
                                                    </div>

                                                    <!-- Kolom kanan: Kecamatan + Desa/Kelurahan -->
                                                    <div class="col-md-6">
                                                        <div class="mb-3">
                                                            <label class="form-label small">Kecamatan</label>
                                                            <select id="edit_lokasi_kecamatan"
                                                                name="edit_lokasi_kecamatan" class="form-control"
                                                                disabled></select>
                                                            <small class="text-muted">Pilih kecamatan berdasarkan
                                                                kabupaten/kota</small>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label small">Desa/Kelurahan</label>
                                                            <select id="edit_lokasi_desa" name="edit_lokasi_desa"
                                                                class="form-control" disabled></select>
                                                            <small class="text-muted">Pilih desa/kelurahan berdasarkan
                                                                kecamatan</small>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Nilai gabungan nama lokasi untuk dikirim ke server -->
                                                <input type="hidden" id="edit_lokasi_program" name="lokasi_program">


                                                <!-- Section Peta Lokasi Program -->
                                                <div class="mb-3">

                                                    <div class="row">
                                                        <div class="col-md-6">
                                                            <label for="edit_latitude" class="form-label">Latitude</label>
                                                            <input type="text" class="form-control" id="edit_latitude"
                                                                name="latitude" readonly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="edit_longitude"
                                                                class="form-label">Longitude</label>
                                                            <input type="text" class="form-control"
                                                                id="edit_longitude" name="longitude" readonly>
                                                        </div>
                                                    </div>
                                                    <div class="mt-2">
                                                        <div id="editMapContainer"
                                                            style="height: 400px; border: 1px solid #ddd; border-radius: 5px;">
                                                        </div>
                                                        <small class="form-text text-muted">Klik pada peta untuk memilih
                                                            lokasi program.
                                                            Gunakan layer control di pojok kanan atas untuk mengubah
                                                            tampilan peta.</small>
                                                    </div>
                                                    <!-- Hidden field untuk koordinat -->
                                                    <input type="hidden" id="edit_koordinat" name="koordinat">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Penerima Dampak dipindahkan tepat di bawah bingkai lokasi -->
                                <div class="row mt-3">
                                    <div class="col-md-12">
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

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.css" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.4/dist/leaflet.js" crossorigin="anonymous"></script>

    <script>
        // Util reset Select2
        function resetSelect2(selector, disabled) {
            $(selector).val(null).trigger('change');
            $(selector).prop('disabled', !!disabled);
        }

        // Inisialisasi berjenjang: provinsi → kab/kota → kecamatan → desa
        function initWilayahSelect2Group(group) {
            const {
                prov,
                kab,
                kec,
                desa
            } = group;

            // Destroy jika sudah pernah diinit (hindari duplikasi container)
            [prov, kab, kec, desa].forEach(sel => {
                if ($(sel).hasClass('select2-hidden-accessible')) $(sel).select2('destroy');
            });

            // Provinsi (level: provinsi)
            $(prov).prop('disabled', false).select2({
                placeholder: 'Pilih Provinsi',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '/get-wilayah',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        level: 'provinsi',
                        q: params.term || ''
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.kode,
                            text: item.nama
                        }))
                    })
                }
            }).on('change', function() {
                const hasProv = !!$(this).val();
                resetSelect2(kab, !hasProv);
                resetSelect2(kec, true);
                resetSelect2(desa, true);
            });

            // Kabupaten/Kota (level: kabupaten, parent: provinsi)
            $(kab).select2({
                placeholder: 'Pilih Kabupaten/Kota',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '/get-wilayah',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        level: 'kabupaten',
                        parent: $(prov).val() || '',
                        q: params.term || ''
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.kode,
                            text: item.nama
                        }))
                    })
                }
            }).on('change', function() {
                const hasKab = !!$(this).val();
                resetSelect2(kec, !hasKab);
                resetSelect2(desa, true);
            });

            // Kecamatan (level: kecamatan, parent: kabupaten)
            $(kec).select2({
                placeholder: 'Pilih Kecamatan',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '/get-wilayah',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        level: 'kecamatan',
                        parent: $(kab).val() || '',
                        q: params.term || ''
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.kode,
                            text: item.nama
                        }))
                    })
                }
            }).on('change', function() {
                const hasKec = !!$(this).val();
                resetSelect2(desa, !hasKec);
            });

            // Desa/Kelurahan (level: desa, parent: kecamatan)
            $(desa).select2({
                placeholder: 'Pilih Desa/Kelurahan',
                allowClear: true,
                width: '100%',
                minimumInputLength: 0,
                ajax: {
                    url: '/get-wilayah',
                    dataType: 'json',
                    delay: 250,
                    data: params => ({
                        level: 'desa',
                        parent: $(kec).val() || '',
                        q: params.term || ''
                    }),
                    processResults: data => ({
                        results: data.map(item => ({
                            id: item.kode,
                            text: item.nama
                        }))
                    })
                }
            });

            // Set initial state
            $(kab).prop('disabled', !$(prov).val());
            $(kec).prop('disabled', !$(kab).val());
            $(desa).prop('disabled', !$(kec).val());
        }

        // Helper untuk init jika elemen ada
        function initWilayahIfExists() {
            if ($('#lokasi_provinsi').length) {
                initWilayahSelect2Group({
                    prov: '#lokasi_provinsi',
                    kab: '#lokasi_kabupaten',
                    kec: '#lokasi_kecamatan',
                    desa: '#lokasi_desa'
                });
            }
            if ($('#edit_lokasi_provinsi').length) {
                initWilayahSelect2Group({
                    prov: '#edit_lokasi_provinsi',
                    kab: '#edit_lokasi_kabupaten',
                    kec: '#edit_lokasi_kecamatan',
                    desa: '#edit_lokasi_desa'
                });
            }
        }

        $(document).ready(function() {
            // Declare dynamic form index variables
            let biayaIndex = 1;
            window.publikasiIndex = 1;
            let dokumentasiIndex = 1;
            let feedbackIndex = 1;

            // Pastikan inisialisasi berjalan pada konteks visible
            // Init saat load (jika tab sudah aktif)
            initWilayahIfExists();

            // Re-init saat tab dibuka (Bootstrap 4)
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                const target = $(e.target).attr('href'); // #program atau #edit-program
                if (target === '#program' || target === '#edit-program') {
                    initWilayahIfExists();
                }
            });

            // Re-init saat modal ditampilkan
            $('.modal').on('shown.bs.modal', function() {
                initWilayahIfExists();
            });

            // Inisialisasi peta saat modal tambah benar-benar ditampilkan
            let addMapInstance = null;
            $('#tjslModal').on('shown.bs.modal', function() {
                // Pastikan Leaflet sudah ter-load
                if (typeof L === 'undefined') {
                    console.error('Leaflet belum ter-load');
                    return;
                }
                if (!addMapInstance) {
                    addMapInstance = initLeafletMap('lokasiMap', 'latitude', 'longitude', 'koordinat');
                } else {
                    // Pastikan peta merender ulang ukuran saat modal dibuka
                    setTimeout(function() {
                        addMapInstance.map.invalidateSize(true);
                    }, 100);
                }

                // Jika input sudah berisi angka, posisikan marker
                const lat = parseFloat($('#latitude').val());
                const lng = parseFloat($('#longitude').val());
                if (!isNaN(lat) && !isNaN(lng)) {
                    addMapInstance.setMarker(lat, lng, true);
                }
            });

            // Reset koordinat ketika modal ditutup
            $('#tjslModal').on('hidden.bs.modal', function() {
                $('#latitude').val('');
                $('#longitude').val('');
                $('#koordinat').val('');
            });

            // Sinkronisasi dua arah: input -> peta
            $('#latitude, #longitude').on('input change', function() {
                const lat = parseFloat($('#latitude').val());
                const lng = parseFloat($('#longitude').val());
                if (!isNaN(lat) && !isNaN(lng) && addMapInstance) {
                    addMapInstance.setMarker(lat, lng, true);
                    $('#koordinat').val(lat.toFixed(6) + ',' + lng.toFixed(6));
                } else {
                    $('#koordinat').val('');
                }
            });

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



                        // Format dates for HTML date inputs
                        let tanggalMulai = response.tanggal_mulai ? response.tanggal_mulai.split('T')[
                            0] : '';
                        let tanggalAkhir = response.tanggal_akhir ? response.tanggal_akhir.split('T')[
                            0] : '';

                        // Populate form fields
                        $('#edit_nama_program').val(response.nama_program);
                        $('#edit_unit_id').val(response.unit_id);
                        $('#edit_pilar_id').val(response.pilar_id);
                        $('#edit_program_unggulan_id').val(response.program_unggulan_id);
                        $('#edit_deskripsi').val(response.deskripsi);
                        $('#edit_penerima_dampak').val(response.penerima_dampak);
                        $('#edit_tanggal_mulai').val(tanggalMulai);
                        $('#edit_tanggal_akhir').val(tanggalAkhir);
                        $('#edit_status').val(response.status);
                        $('#edit_tpb').val(response.tpb);

                        // Set latitude dan longitude
                        $('#edit_latitude').val(response.latitude || '');
                        $('#edit_longitude').val(response.longitude || '');

                        // Handle lokasi program
                        if (response.lokasi_program) {
                            $('#edit_lokasi_program').val(response.lokasi_program);
                        }

                        // Handle sub_pilar (multiple select) - set setelah program unggulan
                        if (response.program_unggulan_id) {
                            // Trigger filter sub pilar berdasarkan program unggulan
                            setTimeout(function() {
                                $('#edit_program_unggulan_id').trigger('change');
                                // Set sub pilar setelah filter diterapkan
                                if (response.sub_pilar && Array.isArray(response.sub_pilar)) {
                                    $('#edit_sub_pilar').val(response.sub_pilar).trigger(
                                        'change');
                                }
                            }, 100);
                        } else {
                            // Jika tidak ada program unggulan, set sub pilar langsung
                            if (response.sub_pilar && Array.isArray(response.sub_pilar)) {
                                $('#edit_sub_pilar').val(response.sub_pilar).trigger('change');
                            }
                        }

                        // Load related data
                        loadEditBiayaData(response.biaya || []);
                        loadEditPublikasiData(response.publikasi || []);
                        loadEditDokumentasiData(response.dokumentasi || []);
                        loadEditFeedbackData(response.feedback || []);

                        // Inisialisasi peta edit
                        setTimeout(function() {
                            const editMapControl = initEditLeafletMap('editMapContainer',
                                'edit_latitude', 'edit_longitude', 'edit_koordinat');

                            if (response.latitude && response.longitude) {
                                const lat = parseFloat(response.latitude);
                                const lng = parseFloat(response.longitude);
                                editMapControl.setMarker(lat, lng, true);
                            }
                        }, 500);

                        // Show modal
                        $('#editTjslModal').modal('show');

                        // Preselect wilayah berdasarkan kode jika lokasi_program berformat kode (e.g. 11.01.01.2002)
                        if (response.lokasi_program && typeof response.lokasi_program === 'string') {
                            const kode = response.lokasi_program.trim();
                            const isKodeWilayah = /^\d{2}\.\d{2}\.\d{2}\.\d{4}$/.test(kode);
                            if (isKodeWilayah) {
                                parseAndSetWilayahFromCode(kode);
                            }
                        }
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
                                <label class="form-label">Keterangan</label>
                                <input type="text" class="form-control" name="biaya[${index}][keterangan]"
                                       value="${biaya.keterangan || ''}" placeholder="Keterangan biaya">
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
            // Event handler untuk tombol edit program
            $('.edit-program-btn').click(function() {
                const tjslId = $(this).data('id');
                loadTjslData(tjslId);
            });

            // Event handler untuk tombol tambah di modal input
            $('#addBiaya').click(function() {
                const biayaHtml = `
                <div class="biaya-item border p-3 mb-3 rounded bg-light">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">Anggaran (Rp)</label>
                            <input type="number" class="form-control" name="biaya[${biayaIndex}][anggaran]" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="biaya[${biayaIndex}][keterangan]" placeholder="Keterangan biaya">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm removeBiaya">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                $('#biayaContainer').append(biayaHtml);
                biayaIndex++;
            });

            // Event handler untuk tombol tambah di modal edit
            $('#editAddBiaya').click(function() {
                const biayaHtml = `
                <div class="biaya-item border p-3 mb-3 rounded bg-light">
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label">Anggaran (Rp)</label>
                            <input type="number" class="form-control" name="biaya[${editBiayaIndex}][anggaran]" step="0.01" placeholder="0.00">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">Keterangan</label>
                            <input type="text" class="form-control" name="biaya[${editBiayaIndex}][keterangan]" placeholder="Keterangan biaya">
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

            $('#editAddPublikasi').click(function() {
                const publikasiHtml = `
                <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Media</label>
                            <input type="text" class="form-control" name="publikasi[${editPublikasiIndex}][media]" placeholder="Nama Media">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link</label>
                            <input type="url" class="form-control" name="publikasi[${editPublikasiIndex}][link]" placeholder="https://...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-edit-publikasi">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                $('#editPublikasiContainer').append(publikasiHtml);
                editPublikasiIndex++;
            });

            $('#editAddDokumentasi').click(function() {
                const dokumentasiHtml = `
                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label">Nama Dokumen</label>
                            <input type="text" class="form-control" name="dokumentasi[${editDokumentasiIndex}][nama_dokumen]" placeholder="Nama Dokumen">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Link Dokumen</label>
                            <input type="url" class="form-control" name="dokumentasi[${editDokumentasiIndex}][link]" placeholder="https://...">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="button" class="btn btn-danger btn-sm remove-edit-dokumentasi">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            `;
                $('#editDokumentasiContainer').append(dokumentasiHtml);
                editDokumentasiIndex++;
            });

            $('#editAddFeedback').click(function() {
                const feedbackHtml = `
                <div class="feedback-item border p-3 mb-3 rounded bg-light">
                    <div class="row">
                        <div class="col-md-3">
                            <label class="form-label">Sangat Puas</label>
                            <input type="number" class="form-control" name="feedback[${editFeedbackIndex}][sangat_puas]" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Puas</label>
                            <input type="number" class="form-control" name="feedback[${editFeedbackIndex}][puas]" min="0" placeholder="0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Kurang Puas</label>
                            <input type="number" class="form-control" name="feedback[${editFeedbackIndex}][kurang_puas]" min="0" placeholder="0">
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
                            <textarea class="form-control" name="feedback[${editFeedbackIndex}][saran]" rows="2" placeholder="Masukkan saran..."></textarea>
                        </div>
                    </div>
                </div>
            `;
                $('#editFeedbackContainer').append(feedbackHtml);
                editFeedbackIndex++;
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

        // Util: inisialisasi Leaflet dengan klik-peta -> isi input
        function initLeafletMap(mapId, latInputId, lngInputId, hiddenKoordId) {
            // Pusat default Indonesia (Jakarta)
            const defaultCenter = [-6.200000, 106.816666];
            const map = L.map(mapId, {
                zoomControl: true
            }).setView(defaultCenter, 6);

            // Definisi berbagai layer peta
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap contributors'
            });

            const satelliteLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: '&copy; Esri &mdash; Source: Esri, i-cubed, USDA, USGS, AEX, GeoEye, Getmapping, Aerogrid, IGN, IGP, UPR-EGP, and the GIS User Community'
                });
            // Hybrid layer (satellite with labels)
            const hybridLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                attribution: '© Google',
                maxZoom: 20
            });

            // Set default layer (OSM)
            osmLayer.addTo(map);

            // Definisi base layers untuk layer control
            const baseLayers = {
                "OpenStreetMap": osmLayer,
                "Citra Satelit": satelliteLayer,
                "Hybrid": hybridLayer
                // "Topografi": topoLayer,
                // "CartoDB Light": cartoLayer
            };

            // Tambahkan layer control
            L.control.layers(baseLayers).addTo(map);

            // Pastikan render saat container sebelumnya tersembunyi (modal)
            setTimeout(function() {
                map.invalidateSize(true);
            }, 100);

            let marker = null;

            // Klik peta: pasang/geser marker & isi input
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (!marker) {
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    marker.setLatLng([lat, lng]);
                }

                $('#' + latInputId).val(lat.toFixed(6));
                $('#' + lngInputId).val(lng.toFixed(6));
                if (hiddenKoordId) {
                    $('#' + hiddenKoordId).val(lat.toFixed(6) + ',' + lng.toFixed(6));
                }

                // Fokuskan peta ke titik yang dipilih
                map.setView([lat, lng], Math.max(map.getZoom(), 14));
            });

            // API: set marker dari input manual
            function setMarker(lat, lng, pan) {
                if (!marker) {
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    marker.setLatLng([lat, lng]);
                }
                if (pan) {
                    map.setView([lat, lng], Math.max(map.getZoom(), 14));
                }
            }

            return {
                map,
                setMarker
            };
        }



        // Fungsi untuk mengekstrak dan mengisi data wilayah dari kode
        function parseAndSetWilayahFromCode(kodeWilayah) {
            // console.log('=== parseAndSetWilayahFromCode START ===');
            // console.log('Parsing kode wilayah:', kodeWilayah);
            // console.log('Modal edit visible:', $('#editTjslModal').is(':visible'));
            // console.log('Edit provinsi element exists:', $('#edit_lokasi_provinsi').length);

            if (!kodeWilayah || kodeWilayah.length < 10) {
                // console.log('Kode wilayah tidak valid atau terlalu pendek');
                return;
            }

            // Format kode: 11.01.01.2002
            // 11 = Provinsi, 01 = Kabupaten, 01 = Kecamatan, 2002 = Desa
            const parts = kodeWilayah.split('.');
            if (parts.length !== 4) {
                // console.log('Format kode wilayah tidak sesuai, parts:', parts);
                return;
            }

            const kodeProvinsi = parts[0];
            const kodeKabupaten = parts[0] + '.' + parts[1];
            const kodeKecamatan = parts[0] + '.' + parts[1] + '.' + parts[2];
            const kodeDesa = kodeWilayah;

            // console.log('Kode yang akan di-set:', {
            //     provinsi: kodeProvinsi,
            //     kabupaten: kodeKabupaten,
            //     kecamatan: kodeKecamatan,
            //     desa: kodeDesa
            // });

            // Tunggu sebentar untuk memastikan Select2 sudah terinisialisasi
            setTimeout(function() {
                // console.log('Starting to set provinsi...');
                // Set provinsi terlebih dahulu
                setWilayahByCode('edit_lokasi_provinsi', kodeProvinsi, function() {
                    // console.log('Provinsi set, now setting kabupaten...');
                    // Setelah provinsi ter-set, set kabupaten
                    setTimeout(function() {
                        $('#edit_lokasi_kabupaten').prop('disabled', false);
                        setWilayahByCode('edit_lokasi_kabupaten', kodeKabupaten,
                            function() {
                                // console.log(
                                //     'Kabupaten set, now setting kecamatan...');
                                // Setelah kabupaten ter-set, set kecamatan
                                setTimeout(function() {
                                    $('#edit_lokasi_kecamatan').prop(
                                        'disabled', false);
                                    setWilayahByCode(
                                        'edit_lokasi_kecamatan',
                                        kodeKecamatan,
                                        function() {
                                            // console.log(
                                            //     'Kecamatan set, now setting desa...'
                                            // );
                                            // Setelah kecamatan ter-set, set desa
                                            setTimeout(function() {
                                                $('#edit_lokasi_desa')
                                                    .prop(
                                                        'disabled',
                                                        false);
                                                setWilayahByCode
                                                    (
                                                        'edit_lokasi_desa',
                                                        kodeDesa,
                                                        function() {
                                                            // console
                                                            //     .log(
                                                            //         'All regions set, composing location...'
                                                            //     );
                                                            composeEditLokasiProgram
                                                                ();
                                                            // console
                                                            //     .log(
                                                            //         '=== parseAndSetWilayahFromCode END ==='
                                                            //     );
                                                        });
                                            }, 300);
                                        });
                                }, 300);
                            });
                    }, 300);
                });
            }, 500); // Delay untuk memastikan modal dan Select2 sudah siap
        }

        function setWilayahByCode(selectId, kode, callback) {
            // console.log('Setting wilayah untuk', selectId, 'dengan kode:', kode);

            $.ajax({
                url: '/get-wilayah-by-code',
                method: 'GET',
                data: {
                    kode: kode
                },
                success: function(data) {
                    // console.log('Response untuk', selectId, ':', data);

                    if (data && data.nama) {
                        // Clear existing options first
                        $('#' + selectId).empty();

                        // Buat option baru dan set sebagai selected
                        const newOption = new Option(data.nama, data.kode, true, true);
                        $('#' + selectId).append(newOption).trigger('change');

                        // console.log('Berhasil set', selectId, 'dengan nilai:', data.nama);

                        if (callback) {
                            callback();
                        }
                    } else {
                        // console.log('Data tidak valid untuk', selectId);
                        if (callback) {
                            callback();
                        }
                    }
                },
                error: function(xhr, status, error) {
                    // console.log('Error loading wilayah data for', selectId, ':', error);
                    // console.log('Response:', xhr.responseText);
                    if (callback) {
                        callback();
                    }
                }
            });
        }

        // Fungsi untuk inisialisasi Select2 wilayah edit
        function initEditWilayahSelect2() {
            // Provinsi Edit
            $('#edit_lokasi_provinsi').select2({
                placeholder: 'Pilih Provinsi',
                allowClear: true,
                width: '100%',
                ajax: {
                    url: '/get-wilayah',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            level: 'provinsi',
                            q: params.term || ''
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.map(function(item) {
                                return {
                                    id: item.kode,
                                    text: item.nama
                                };
                            })
                        };
                    }
                }
            }).on('change', function() {
                const provinsiKode = $(this).val();
                resetEditSelect('#edit_lokasi_kabupaten');
                resetEditSelect('#edit_lokasi_kecamatan');
                resetEditSelect('#edit_lokasi_desa');

                if (provinsiKode) {
                    $('#edit_lokasi_kabupaten').prop('disabled', false);
                    $('#edit_lokasi_kabupaten').select2({
                        placeholder: 'Pilih Kabupaten/Kota',
                        allowClear: true,
                        width: '100%',
                        ajax: {
                            url: '/get-wilayah',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    level: 'kabupaten',
                                    parent_kode: provinsiKode,
                                    q: params.term || ''
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(item) {
                                        return {
                                            id: item.kode,
                                            text: item.nama
                                        };
                                    })
                                };
                            }
                        }
                    });
                } else {
                    $('#edit_lokasi_kabupaten').prop('disabled', true);
                }
                composeEditLokasiProgram();
            });

            // Kabupaten Edit
            $('#edit_lokasi_kabupaten').on('change', function() {
                const kabupatenKode = $(this).val();
                resetEditSelect('#edit_lokasi_kecamatan');
                resetEditSelect('#edit_lokasi_desa');

                if (kabupatenKode) {
                    $('#edit_lokasi_kecamatan').prop('disabled', false);
                    $('#edit_lokasi_kecamatan').select2({
                        placeholder: 'Pilih Kecamatan',
                        allowClear: true,
                        width: '100%',
                        ajax: {
                            url: '/get-wilayah',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    level: 'kecamatan',
                                    parent_kode: kabupatenKode,
                                    q: params.term || ''
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(item) {
                                        return {
                                            id: item.kode,
                                            text: item.nama
                                        };
                                    })
                                };
                            }
                        }
                    });
                } else {
                    $('#edit_lokasi_kecamatan').prop('disabled', true);
                }
                composeEditLokasiProgram();
            });

            // Kecamatan Edit
            $('#edit_lokasi_kecamatan').on('change', function() {
                const kecamatanKode = $(this).val();
                resetEditSelect('#edit_lokasi_desa');

                if (kecamatanKode) {
                    $('#edit_lokasi_desa').prop('disabled', false);
                    $('#edit_lokasi_desa').select2({
                        placeholder: 'Pilih Desa/Kelurahan',
                        allowClear: true,
                        width: '100%',
                        ajax: {
                            url: '/get-wilayah',
                            dataType: 'json',
                            delay: 250,
                            data: function(params) {
                                return {
                                    level: 'desa',
                                    parent_kode: kecamatanKode,
                                    q: params.term || ''
                                };
                            },
                            processResults: function(data) {
                                return {
                                    results: data.map(function(item) {
                                        return {
                                            id: item.kode,
                                            text: item.nama
                                        };
                                    })
                                };
                            }
                        }
                    });
                } else {
                    $('#edit_lokasi_desa').prop('disabled', true);
                }
                composeEditLokasiProgram();
            });

            // Desa Edit
            $('#edit_lokasi_desa').on('change', function() {
                composeEditLokasiProgram();
            });
        }

        // Fungsi untuk reset select edit
        function resetEditSelect(selector) {
            $(selector).empty().append('<option></option>').val(null).trigger('change').prop('disabled', true);
        }

        // Fungsi untuk menyusun lokasi program edit
        function composeEditLokasiProgram() {
            const prov = $('#edit_lokasi_provinsi option:selected').text();
            const kab = $('#edit_lokasi_kabupaten option:selected').text();
            const kec = $('#edit_lokasi_kecamatan option:selected').text();
            const desa = $('#edit_lokasi_desa option:selected').text();

            const parts = [desa, kec, kab, prov].filter(part => part && part !== 'Pilih Provinsi' && part !==
                'Pilih Kabupaten/Kota' && part !== 'Pilih Kecamatan' && part !== 'Pilih Desa/Kelurahan');
            $('#edit_lokasi_program').val(parts.join(', '));
        }

        // Fungsi untuk inisialisasi peta edit
        function initEditLeafletMap(containerId, latInputId, lngInputId, hiddenKoordId) {
            // Hapus peta yang sudah ada jika ada
            if (window.editMapInstance) {
                window.editMapInstance.remove();
            }

            // Inisialisasi peta dengan center default
            const map = L.map(containerId).setView([-6.2088, 106.8456], 10);

            // Simpan instance peta untuk referensi global
            window.editMapInstance = map;

            // Layer basemap
            const osmLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            });

            const satelliteLayer = L.tileLayer(
                'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    attribution: '© Esri, Maxar, Earthstar Geographics'
                });

            const hybridLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
                attribution: '© Google',
                maxZoom: 20
            });


            // Set default layer
            osmLayer.addTo(map);

            // Layer control
            const baseLayers = {
                "OpenStreetMap": osmLayer,
                "Satelit": satelliteLayer,
                "Hybrid": hybridLayer
            };

            // Tambahkan layer control
            L.control.layers(baseLayers).addTo(map);

            // Pastikan render saat container sebelumnya tersembunyi (modal)
            setTimeout(function() {
                map.invalidateSize(true);
            }, 100);

            let marker = null;

            // Klik peta: pasang/geser marker & isi input
            map.on('click', function(e) {
                const lat = e.latlng.lat;
                const lng = e.latlng.lng;

                if (!marker) {
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    marker.setLatLng([lat, lng]);
                }

                $('#' + latInputId).val(lat.toFixed(6));
                $('#' + lngInputId).val(lng.toFixed(6));
                if (hiddenKoordId) {
                    $('#' + hiddenKoordId).val(lat.toFixed(6) + ',' + lng.toFixed(6));
                }

                // Fokuskan peta ke titik yang dipilih
                map.setView([lat, lng], Math.max(map.getZoom(), 14));
            });

            // API: set marker dari input manual atau data yang sudah ada
            function setMarker(lat, lng, pan) {
                if (!marker) {
                    marker = L.marker([lat, lng]).addTo(map);
                } else {
                    marker.setLatLng([lat, lng]);
                }
                if (pan) {
                    map.setView([lat, lng], Math.max(map.getZoom(), 14));
                }
            }

            return {
                map,
                setMarker
            };
        }

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
                    <label class="form-label">Keterangan</label>
                    <input type="text" class="form-control" name="biaya[${biayaIndex}][keterangan]" placeholder="Keterangan biaya">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm removeBiaya">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
            $('#biayaContainer').append(biayaHtml);
            biayaIndex++;
        });

        // Add Publikasi
        $('#addPublikasi').click(function() {
            // Pastikan counter tersedia (hindari ReferenceError jika scope berbeda)
            if (typeof window.publikasiIndex === 'undefined') {
                window.publikasiIndex = 1;
            }
            const index = window.publikasiIndex;

            const publikasiHtml = `
        <div class="publikasi-item border p-3 mb-3 rounded bg-light">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Media</label>
                    <input type="text" class="form-control" name="publikasi[${index}][media]" placeholder="Nama Media">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link</label>
                    <input type="url" class="form-control" name="publikasi[${index}][link]" placeholder="https://...">
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
            window.publikasiIndex++;
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
                <div class="col-md-10">
                    <label class="form-label">Feedback</label>
                    <textarea class="form-control" name="feedback[${feedbackIndex}][feedback]" rows="3" placeholder="Masukkan feedback..."></textarea>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-feedback">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
            $('#feedbackContainer').append(feedbackHtml);
            feedbackIndex++;
        });

        // Remove handlers
        $(document).on('click', '.removeBiaya', function() {
            $(this).closest('.biaya-item').remove();
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
        });

        // Update remove button states
        function updateRemoveButtons() {
            $('.remove-publikasi').prop('disabled', $('.publikasi-item').length <= 1);
            $('.remove-dokumentasi').prop('disabled', $('.dokumentasi-item').length <= 1);
        }


        // Initialize Select2 for multi-select sub_pilar
        $('#sub_pilar').select2({
            placeholder: "Pilih Sub Pilar",
            allowClear: true,
            width: '100%'
        });

        // Initialize Select2 for edit modal sub_pilar
        $('#edit_sub_pilar').select2({
            placeholder: "Pilih Sub Pilar",
            allowClear: true,
            width: '100%',
            dropdownParent: $('#editTjslModal')
        });

        // Kumpulkan mapping Program Unggulan -> daftar sub_pilar (array id)
        const programUnggulanMap = {};
        $('#program_unggulan_id option').each(function() {
            const id = $(this).val();
            if (!id) return;
            let sp = $(this).data('subpilars'); // bisa array atau string JSON
            if (typeof sp === 'string') {
                try {
                    sp = JSON.parse(sp);
                } catch (e) {
                    sp = [];
                }
            }
            programUnggulanMap[id] = Array.isArray(sp) ? sp.map(String) : [];
        });

        // Simpan semua sub pilar untuk rebuild opsi saat filter
        const allSubpilars = @json(
            $subpilars->map(function ($s) {
                return ['id' => (string) $s->id, 'text' => $s->sub_pilar];
            }));

        function renderSubPilarOptions(allowedIds) {
            const $select = $('#sub_pilar');
            // Rebuild opsi sesuai allowedIds; jika kosong, tampilkan semua
            const data = (allowedIds && allowedIds.length) ?
                allSubpilars.filter(s => allowedIds.includes(s.id)) :
                allSubpilars;

            // Reset pilihan lalu isi ulang opsi
            $select.empty();
            data.forEach(s => $select.append(new Option(s.text, s.id)));
            // Kosongkan nilai terpilih dan trigger update ke Select2
            $select.val(null).trigger('change');
        }

        // Filter saat program unggulan berubah
        $('#program_unggulan_id').on('change', function() {
            const id = $(this).val();
            renderSubPilarOptions(programUnggulanMap[id] || []);
        });

        // Optional: render awal (tanpa filter)
        renderSubPilarOptions([]);

        // Kumpulkan mapping Program Unggulan -> daftar sub_pilar untuk edit modal
        const editProgramUnggulanMap = {};
        $('#edit_program_unggulan_id option').each(function() {
            const id = $(this).val();
            if (!id) return;
            let sp = $(this).data('subpilars');
            if (typeof sp === 'string') {
                try {
                    sp = JSON.parse(sp);
                } catch (e) {
                    sp = [];
                }
            }
            editProgramUnggulanMap[id] = Array.isArray(sp) ? sp.map(String) : [];
        });

        // Semua sub pilar (id dan text) untuk rebuild opsi edit
        const allSubpilarsEdit = @json($subpilars->map(fn($s) => ['id' => (string) $s->id, 'text' => $s->sub_pilar]));

        function renderEditSubPilarOptions(allowedIds) {
            const $select = $('#edit_sub_pilar');
            // Simpan pilihan saat ini agar tidak hilang
            const currentVals = ($select.val() || []).map(String);

            const data = (allowedIds && allowedIds.length) ?
                allSubpilarsEdit.filter(s => allowedIds.includes(s.id)) :
                allSubpilarsEdit;

            $select.empty();
            data.forEach(s => $select.append(new Option(s.text, s.id)));

            // Pertahankan nilai yang masih valid
            const keep = currentVals.filter(v => data.some(d => d.id === v));
            $select.val(keep).trigger('change');
        }

        // Filter saat Program Unggulan berubah di modal edit
        $('#edit_program_unggulan_id').on('change', function() {
            const id = $(this).val();
            renderEditSubPilarOptions(editProgramUnggulanMap[id] || []);
        });

        // Saat modal edit dibuka, apply filter sesuai nilai awal (jika ada)
        $('#editTjslModal').on('shown.bs.modal', function() {
            const id = $('#edit_program_unggulan_id').val();
            renderEditSubPilarOptions(editProgramUnggulanMap[id] || []);
        });

        $('#edit-program-tab').on('shown.bs.tab', function() {
            if (window.editMapInstance) {
                setTimeout(function() {
                    window.editMapInstance.invalidateSize(true);
                }, 100);
            }
        });

        // initWilayahSelect2();
    </script>

    <script>
        $(document).ready(function() {

            // Event handler untuk refresh peta edit ketika tab program diklik
            $('#edit-program-tab').on('shown.bs.tab', function() {
                if (window.editMapInstance) {
                    setTimeout(function() {
                        window.editMapInstance.invalidateSize(true);
                    }, 100);
                }
            });

            // Inisialisasi Select2 ketika tab program dibuka
            $('a[data-bs-toggle="tab"]').on('shown.bs.tab', function(e) {
                var target = $(e.target).attr("href");
                // console.log('Tab dibuka:', target);

                if (target === '#program') {
                    // console.log('Tab program aktif, inisialisasi Select2');
                    setTimeout(function() {
                        initWilayahSelect2();
                    }, 100);
                }
            });

            // Juga panggil saat document ready jika tab program sudah aktif
            if ($('#program').hasClass('active')) {
                // console.log('Tab program sudah aktif saat load');
                initWilayahSelect2();
            } else {
                // console.log('Tab program tidak aktif, tunggu sampai dibuka');
            }
            // Definisi fungsi initWilayahSelect2
            function initWilayahSelect2() {
                // console.log('initWilayahSelect2 dipanggil');
                // console.log('Element lokasi_provinsi ditemukan:', $('#lokasi_provinsi').length);
                // console.log('Element lokasi_provinsi visible:', $('#lokasi_provinsi').is(':visible'));
                // console.log('Element lokasi_provinsi CSS display:', $('#lokasi_provinsi').css('display'));

                // Cek apakah Select2 sudah ada
                if ($('#lokasi_provinsi').hasClass('select2-hidden-accessible')) {
                    // console.log('Select2 sudah diinisialisasi sebelumnya, destroy dulu');
                    $('#lokasi_provinsi').select2('destroy');
                }

                // Test dengan data statis dulu
                $('#lokasi_provinsi').empty().append('<option value="">Pilih Provinsi</option>');
                $('#lokasi_provinsi').append('<option value="test1">Test Provinsi 1</option>');
                $('#lokasi_provinsi').append('<option value="test2">Test Provinsi 2</option>');

                // Inisialisasi Select2 tanpa AJAX dulu
                var select2Instance = $('#lokasi_provinsi').select2({
                    placeholder: 'Pilih Provinsi',
                    allowClear: true,
                    width: '100%'
                });

                // console.log('Select2 instance created:', select2Instance);
                // console.log('Select2 container:', $('.select2-container').length);

                // Cek apakah container Select2 terlihat
                setTimeout(function() {
                    // console.log('Select2 container visible:', $('.select2-container').is(':visible'));
                    // console.log('Select2 container CSS:', $('.select2-container').css('display'));
                }, 100);

                $('#lokasi_provinsi').on('select2:open', function() {
                    // console.log('Select2 provinsi dibuka');
                }).on('change', function() {
                    const prov = $(this).val();

                    // Reset tingkat bawah
                    resetSelect('#lokasi_kabupaten');
                    resetSelect('#lokasi_kecamatan');
                    resetSelect('#lokasi_desa');

                    // Enable kabupaten bila provinsi dipilih
                    $('#lokasi_kabupaten').prop('disabled', !prov);

                    composeLokasiProgram();
                });

                // Kabupaten/Kota
                $('#lokasi_kabupaten').select2({
                    placeholder: 'Pilih Kabupaten/Kota',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '/get-wilayah',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                level: 'kabupaten',
                                parent: $('#lokasi_provinsi').val() || '',
                                q: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.kode,
                                        text: item.nama
                                    };
                                })
                            };
                        }
                    }
                }).on('change', function() {
                    const kab = $(this).val();

                    // Reset tingkat bawah
                    resetSelect('#lokasi_kecamatan');
                    resetSelect('#lokasi_desa');

                    // Enable kecamatan bila kabupaten dipilih
                    $('#lokasi_kecamatan').prop('disabled', !kab);

                    composeLokasiProgram();
                });

                // Kecamatan
                $('#lokasi_kecamatan').select2({
                    placeholder: 'Pilih Kecamatan',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '/get-wilayah',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                level: 'kecamatan',
                                parent: $('#lokasi_kabupaten').val() || '',
                                q: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.kode,
                                        text: item.nama
                                    };
                                })
                            };
                        }
                    }
                }).on('change', function() {
                    const kec = $(this).val();

                    // Reset tingkat bawah
                    resetSelect('#lokasi_desa');

                    // Enable desa bila kecamatan dipilih
                    $('#lokasi_desa').prop('disabled', !kec);

                    composeLokasiProgram();
                });

                // Desa
                $('#lokasi_desa').select2({
                    placeholder: 'Pilih Desa/Kelurahan',
                    allowClear: true,
                    width: '100%',
                    ajax: {
                        url: '/get-wilayah',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                level: 'desa',
                                parent: $('#lokasi_kecamatan').val() || '',
                                q: params.term || ''
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data.map(function(item) {
                                    return {
                                        id: item.kode,
                                        text: item.nama
                                    };
                                })
                            };
                        }
                    }
                }).on('change', function() {
                    composeLokasiProgram();
                });
            }


            function resetSelect(selector) {
                $(selector).val(null).trigger('change').prop('disabled', true);
            }

            function composeLokasiProgram() {
                const prov = $('#lokasi_provinsi').select2('data')[0]?.text || '';
                const kab = $('#lokasi_kabupaten').select2('data')[0]?.text || '';
                const kec = $('#lokasi_kecamatan').select2('data')[0]?.text || '';
                const desa = $('#lokasi_desa').select2('data')[0]?.text || '';

                // Susun teks lokasi: Desa, Kecamatan, Kabupaten/Kota, Provinsi
                const parts = [desa, kec, kab, prov].filter(Boolean);
                $('#lokasi_program').val(parts.join(', '));
            }

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
                window.publikasiIndex = 1; // pastikan counter global ikut reset
                dokumentasiIndex = 1;
                feedbackIndex = 1;

                // Reset koordinat
                $('#latitude').val('');
                $('#longitude').val('');
                $('#koordinat').val('');

                updateRemoveButtons();
            });

            // Reset Select2 when edit modal is closed
            $('#editTjslModal').on('hidden.bs.modal', function() {
                // Reset Select2 wilayah edit
                resetEditSelect('#edit_lokasi_provinsi');
                resetEditSelect('#edit_lokasi_kabupaten');
                resetEditSelect('#edit_lokasi_kecamatan');
                resetEditSelect('#edit_lokasi_desa');

                // Reset hidden lokasi program
                $('#edit_lokasi_program').val('');

                // Reset koordinat edit
                $('#edit_latitude').val('');
                $('#edit_longitude').val('');
                $('#edit_koordinat').val('');

                // Hapus peta edit jika ada
                if (window.editMapInstance) {
                    window.editMapInstance.remove();
                    window.editMapInstance = null;
                }
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
