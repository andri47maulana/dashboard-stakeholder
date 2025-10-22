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
                            @foreach ($regions->sort() as $region)
                                <option value="{{ $region }}" {{ request('region') == $region ? 'selected' : '' }}>
                                    {{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterPilar" class="form-label small text-muted">Pilar</label>
                        <select class="form-control form-control-sm" id="filterPilar">
                            <option value="">Semua Pilar</option>
                            @foreach ($pilars->sortBy('pilar') as $pilar)
                                <option value="{{ $pilar->id }}"
                                    {{ request('pilar_id') == $pilar->id ? 'selected' : '' }}>{{ $pilar->pilar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterKebun" class="form-label small text-muted">Kebun</label>
                        <select class="form-control form-control-sm" id="filterKebun">
                            <option value="">Semua Unit</option>
                            @foreach ($units->sortBy('unit') as $unit)
                                <option value="{{ $unit->id }}" {{ request('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="filterTahun" class="form-label small text-muted">Tahun</label>
                        <select class="form-control form-control-sm" id="filterTahun">
                            <option value="">Tahun</option>
                            @foreach ($tahunList->sort() as $tahun)
                                <option value="{{ $tahun }}" {{ request('tahun') == $tahun ? 'selected' : '' }}>
                                    {{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <div class="d-flex gap-2">
                            {{-- <button type="button" class="btn btn-success btn-sm" id="applyFilter">
                                <i class="fas fa-filter"></i> Filter
                            </button> --}}
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
                    <div class="card border-left-primary shadow py-0"
                        style="height: 380px; display: flex; flex-direction: column;">
                        <!-- Header dengan Background Warna -->
                        <div class="card-header"
                            style="background: linear-gradient(135deg, #4ec2df 0%, #224abe 100%); border: none; padding: 0.75rem 1.25rem; flex-shrink: 0; min-height: 70px; display: flex; align-items: center;">
                            <div class="text-white font-weight-bold"
                                style="font-size: 1.1rem; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                {{ $tjsl->nama_program }}
                            </div>
                        </div>
                        <div class="card-body" style="flex: 1; display: flex; flex-direction: column; overflow: hidden;">
                            <div class="row no-gutters align-items-center mb-2">
                                <div class="col">
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
                                                <img src="{{ $image['path'] }}" alt="{{ $image['alt'] }}"
                                                    class="me-1" style="width: 50px; height: 50px; object-fit: contain;"
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
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="text-sm mb-0 text-black-600">
                                        <i class="fas fa-calendar-check fa-sm text-black-400"></i>
                                        Selesai: {{ $tjsl->tanggal_akhir->format('d M Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer dengan Button -->
                        <div class="card-footer bg-light border-0 p-3">
                            <a href="{{ route('tjsl.show', $tjsl->id) }}" class="btn btn-primary btn-sm btn-block">
                                <i class="fas fa-eye fa-sm"></i> Lihat Program
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card shadow">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Belum ada program TJSL</h5>
                            {{-- <p class="text-muted">Mulai dengan menambahkan program TJSL pertama Anda.</p>
                            <a href="{{ route('tjsl.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Tambah Program Pertama
                            </a> --}}
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
                                            <label for="sub_pilar" class="form-label">TPB</label>
                                            <select class="form-control" id="sub_pilar" name="sub_pilar[]" multiple>
                                                @foreach ($subpilars as $subPilar)
                                                    <option value="{{ $subPilar->id }}">
                                                        {{ $subPilar->id }}.{{ $subPilar->sub_pilar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <small class="form-text text-muted">Pilih satu atau lebih sub pilar</small>
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
                                                <div class="col-md-12">
                                                    <div class="mb-2">
                                                        <label class="form-label small" for="koordinat">Koordinat
                                                            (Latitude, Longitude)</label>
                                                        <input type="text" class="form-control" id="koordinat"
                                                            name="koordinat" placeholder="-6.2, 106.8">
                                                        <small class="text-muted">Format: latitude, longitude (contoh:
                                                            -6.2, 106.8)</small>
                                                    </div>
                                                </div>
                                                <!-- Hidden fields untuk latitude dan longitude terpisah -->
                                                <input type="hidden" id="latitude" name="latitude">
                                                <input type="hidden" id="longitude" name="longitude">
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
                                    {{-- <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="tpb" class="form-label">TPB</label>
                                            <input type="text" class="form-control" id="tpb" name="tpb"
                                                placeholder="Contoh: 1,2,3">
                                        </div>
                                    </div> --}}
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
                                            <div class="col-md-4">
                                                <label class="form-label">Sub Pilar/TPB</label>
                                                <select class="form-control biaya-sub-pilar"
                                                    name="biaya[0][sub_pilar_id]">
                                                    <option value="">Pilih Sub Pilar</option>
                                                    @foreach ($subpilars as $subPilar)
                                                        <option value="{{ $subPilar->id }}">
                                                            {{ $subPilar->id }}.{{ $subPilar->sub_pilar }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Anggaran (Rp)</label>
                                                <input type="number" class="form-control" name="biaya[0][anggaran]"
                                                    step="0.01" placeholder="0.00">
                                            </div>
                                            <div class="col-md-3">
                                                <label class="form-label">Realisasi (Rp)</label>
                                                <input type="number" class="form-control" name="biaya[0][realisasi]"
                                                    step="0.01" placeholder="0.00">
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

                                </div>
                                <div id="dokumentasiContainer">
                                    <!-- Proposal (PDF) -->
                                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Proposal (PDF)</label>
                                                <input type="file" class="form-control" name="proposal"
                                                    accept=".pdf">
                                                <small class="form-text text-muted">Format: PDF</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Izin Prinsip (PDF) -->
                                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Izin Prinsip (PDF)</label>
                                                <input type="file" class="form-control" name="izin_prinsip"
                                                    accept=".pdf">
                                                <small class="form-text text-muted">Format: PDF</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Survei Feedback (PDF) -->
                                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Survei Feedback (PDF)</label>
                                                <input type="file" class="form-control" name="survei_feedback"
                                                    accept=".pdf">
                                                <small class="form-text text-muted">Format: PDF</small>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Foto (JPG, PNG) -->
                                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label class="form-label">Foto (JPG, PNG)</label>
                                                <input type="file" class="form-control" name="foto"
                                                    accept=".jpg,.jpeg,.png">
                                                <small class="form-text text-muted">Format: JPG, PNG</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Feedback -->
                            <div class="tab-pane fade" id="feedback" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6><i class="fas fa-comments text-primary"></i> Data Feedback TJSL</h6>
                                </div>
                                <div id="feedbackContainer">
                                    <div class="feedback-item border p-3 mb-3 rounded bg-light">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="feedback[0][sangat_puas]" id="sangat_puas_0"
                                                        value="1">
                                                    <label class="form-check-label" for="sangat_puas_0">
                                                        Sangat Puas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="feedback[0][puas]" id="puas_0" value="1">
                                                    <label class="form-check-label" for="puas_0">
                                                        Puas
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox"
                                                        name="feedback[0][kurang_puas]" id="kurang_puas_0"
                                                        value="1">
                                                    <label class="form-check-label" for="kurang_puas_0">
                                                        Kurang Puas
                                                    </label>
                                                </div>
                                            </div>
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
                <form id="editTjslForm" method="POST" enctype="multipart/form-data">
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
                                            <label for="edit_sub_pilar" class="form-label">TPB</label>
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
                                                        <div class="col-md-12">
                                                            <label for="edit_koordinat_display"
                                                                class="form-label">Koordinat (Latitude, Longitude)</label>
                                                            <input type="text" class="form-control"
                                                                id="edit_koordinat_display" placeholder="-6.2, 106.8">
                                                            <small class="text-muted">Format: latitude, longitude (contoh:
                                                                -6.2, 106.8)</small>
                                                        </div>
                                                        <!-- Hidden fields untuk latitude dan longitude terpisah -->
                                                        <input type="hidden" id="edit_latitude" name="latitude">
                                                        <input type="hidden" id="edit_longitude" name="longitude">
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
                                            <label for="edit_tpb" class="form-label">TPB</label>
                                            <input type="text" class="form-control" id="edit_tpb" name="tpb">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Tab Biaya -->
                            <div class="tab-pane fade" id="edit-biaya" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Data Biaya Program</h6>
                                    {{-- <button type="button" class="btn btn-sm btn-success" id="editAddBiaya">
                                        <i class="fas fa-plus"></i> Tambah Biaya
                                    </button> --}}
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
                                    {{-- <button type="button" class="btn btn-sm btn-success" id="editAddDokumentasi">
                                        <i class="fas fa-plus"></i> Tambah Dokumentasi
                                    </button> --}}
                                </div>
                                <div id="editDokumentasiContainer">
                                    <!-- Dokumentasi items will be loaded here -->
                                </div>
                            </div>

                            <!-- Tab Feedback -->
                            <div class="tab-pane fade" id="edit-feedback" role="tabpanel">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">Feedback Program</h6>
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
                        results: data.sort((a, b) => a.nama.localeCompare(b.nama)).map(item => ({
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
                        results: data.sort((a, b) => a.nama.localeCompare(b.nama)).map(item => ({
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
                        results: data.sort((a, b) => a.nama.localeCompare(b.nama)).map(item => ({
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
                        results: data.sort((a, b) => a.nama.localeCompare(b.nama)).map(item => ({
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

                        // Set unit_id dengan dropdown custom
                        if (response.unit_id) {
                            // Cari nama unit berdasarkan unit_id
                            var unitText = $('#edit_unit_id option[value="' + response.unit_id + '"]')
                                .text();
                            editUnitDropdown.setValue(response.unit_id, unitText);
                        } else {
                            editUnitDropdown.setValue('', '');
                        }

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

                                // Isi field koordinat gabungan juga
                                $('#edit_koordinat_display').val(lat.toFixed(6) + ', ' + lng
                                    .toFixed(6));
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
                                <label class="form-label">Sub Pilar/TPB</label>
                                <select class="form-control edit-biaya-sub-pilar" name="biaya[${index}][sub_pilar_id]">
                                    <option value="">Pilih Sub Pilar</option>
                                    @foreach ($subpilars as $subPilar)
                                        <option value="{{ $subPilar->id }}" ${biaya.sub_pilar_id == '{{ $subPilar->id }}' ? 'selected' : ''}>{{ $subPilar->id }}.{{ $subPilar->sub_pilar }}</option>
                                    @endforeach
                                </select>
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
                // Tab dokumentasi di modal edit menggunakan struktur yang sama dengan modal input
                // Karena dokumentasi adalah file upload, kita hanya perlu menampilkan nama file yang sudah ada
                const container = $('#editDokumentasiContainer');
                container.empty();

                // Struktur dokumentasi tetap (sesuai dengan modal input)
                const dokumentasiHtml = `
                    <!-- Proposal (PDF) -->
                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Proposal (PDF)</label>
                                <input type="file" class="form-control" name="proposal" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX (Max: 10MB)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Saat Ini</label>
                                <div class="current-file">
                                    ${dokumentasiData.length > 0 && dokumentasiData[0].proposal ?
                                        `<a href="/storage/dokumen/proposal/${dokumentasiData[0].proposal}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-download"></i> ${dokumentasiData[0].proposal}
                                                                </a>` :
                                        '<span class="text-muted">Tidak ada file</span>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Izin Prinsip (PDF) -->
                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Izin Prinsip (PDF)</label>
                                <input type="file" class="form-control" name="izin_prinsip" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX (Max: 10MB)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Saat Ini</label>
                                <div class="current-file">
                                    ${dokumentasiData.length > 0 && dokumentasiData[0].izin_prinsip ?
                                        `<a href="/storage/dokumen/izin_prinsip/${dokumentasiData[0].izin_prinsip}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-download"></i> ${dokumentasiData[0].izin_prinsip}
                                                                </a>` :
                                        '<span class="text-muted">Tidak ada file</span>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Survei Feedback (PDF) -->
                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Survei Feedback (PDF)</label>
                                <input type="file" class="form-control" name="survei_feedback" accept=".pdf,.doc,.docx">
                                <small class="form-text text-muted">Format: PDF, DOC, DOCX (Max: 10MB)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Saat Ini</label>
                                <div class="current-file">
                                    ${dokumentasiData.length > 0 && dokumentasiData[0].survei_feedback ?
                                        `<a href="/storage/dokumen/survei_feedback/${dokumentasiData[0].survei_feedback}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-download"></i> ${dokumentasiData[0].survei_feedback}
                                                                </a>` :
                                        '<span class="text-muted">Tidak ada file</span>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Foto (JPG, PNG) -->
                    <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Foto (JPG, PNG)</label>
                                <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png,.gif">
                                <small class="form-text text-muted">Format: JPG, PNG, GIF (Max: 5MB)</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">File Saat Ini</label>
                                <div class="current-file">
                                    ${dokumentasiData.length > 0 && dokumentasiData[0].foto ?
                                        `<a href="/storage/dokumen/foto/${dokumentasiData[0].foto}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                                    <i class="fas fa-download"></i> ${dokumentasiData[0].foto}
                                                                </a>` :
                                        '<span class="text-muted">Tidak ada file</span>'
                                    }
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                container.append(dokumentasiHtml);
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
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="feedback[${index}][sangat_puas]" id="edit_sangat_puas_${index}"
                                        value="1" ${feedback.sangat_puas == 1 ? 'checked' : ''}>
                                    <label class="form-check-label" for="edit_sangat_puas_${index}">
                                        Sangat Puas
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="feedback[${index}][puas]" id="edit_puas_${index}"
                                        value="1" ${feedback.puas == 1 ? 'checked' : ''}>
                                    <label class="form-check-label" for="edit_puas_${index}">
                                        Puas
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox"
                                        name="feedback[${index}][kurang_puas]" id="edit_kurang_puas_${index}"
                                        value="1" ${feedback.kurang_puas == 1 ? 'checked' : ''}>
                                    <label class="form-check-label" for="edit_kurang_puas_${index}">
                                        Kurang Puas
                                    </label>
                                </div>
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
            // $('#addBiaya').click(function() {
            //     const biayaHtml = `
        //     <div class="biaya-item border p-3 mb-3 rounded bg-light">
        //         <div class="row">
        //             <div class="col-md-5">
        //                 <label class="form-label">Anggaran (Rp)</label>
        //                 <input type="number" class="form-control" name="biaya[${biayaIndex}][anggaran]" step="0.01" placeholder="0.00">
        //             </div>
        //             <div class="col-md-5">
        //                 <label class="form-label">Realisasi (Rp)</label>
        //                 <input type="number" class="form-control" name="biaya[${biayaIndex}][realisasi]" step="0.01" placeholder="0.00">
        //             </div>
        //             <div class="col-md-2 d-flex align-items-end">
        //                 <button type="button" class="btn btn-danger btn-sm removeBiaya">
        //                     <i class="fas fa-trash"></i>
        //                 </button>
        //             </div>
        //         </div>
        //     </div>
        // `;
            //     $('#biayaContainer').append(biayaHtml);
            //     biayaIndex++;
            // });

            // Event handler untuk tombol tambah di modal edit
            // $('#editAddBiaya').click(function() {
            //     const biayaHtml = `
        //     <div class="biaya-item border p-3 mb-3 rounded bg-light">
        //         <div class="row">
        //             <div class="col-md-5">
        //                 <label class="form-label">Anggaran (Rp)</label>
        //                 <input type="number" class="form-control" name="biaya[${editBiayaIndex}][anggaran]" step="0.01" placeholder="0.00">
        //             </div>
        //             <div class="col-md-5">
        //                 <label class="form-label">Keterangan</label>
        //                 <input type="text" class="form-control" name="biaya[${editBiayaIndex}][keterangan]" placeholder="Keterangan biaya">
        //             </div>
        //             <div class="col-md-2 d-flex align-items-end">
        //                 <button type="button" class="btn btn-danger btn-sm remove-edit-biaya">
        //                     <i class="fas fa-trash"></i>
        //                 </button>
        //             </div>
        //         </div>
        //     </div>
        // `;
            //     $('#editBiayaContainer').append(biayaHtml);
            //     editBiayaIndex++;
            // });

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

            // Handler untuk tombol editAddDokumentasi sudah dihapus karena tidak diperlukan lagi
            /*
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
                                    */

            // Remove items in edit modal
            $(document).on('click', '.remove-edit-biaya', function() {
                $(this).closest('.biaya-item').remove();
            });

            $(document).on('click', '.remove-edit-publikasi', function() {
                $(this).closest('.publikasi-item').remove();
            });

            $(document).on('click', '.remove-edit-publikasi', function() {
                $(this).closest('.publikasi-item').remove();
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
            $('#filterPilar, #filterKebun, #filterTahun').change(function() {
                applyFilters();
            });

            function applyFilters() {
                const filters = {
                    region: $('#filterRegion').val(),
                    pilar_id: $('#filterPilar').val(),
                    unit_id: $('#filterKebun').val(),
                    tahun: $('#filterTahun').val()
                };

                // Build query string
                const queryString = Object.keys(filters)
                    .filter(key => filters[key] !== '' && filters[key] !== null)
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

                            // Auto filter setelah region dipilih
                            applyFilters();
                        },
                        error: function(xhr, status, error) {
                            kebunSelect.html('<option value="">Error loading data</option>');
                        }
                    });
                } else {
                    // Reset ke semua kebun dengan sorting
                    kebunSelect.html('<option value="">Semua Kebun</option>');
                    const units = [
                        @foreach ($units->sortBy('unit') as $unit)
                            {
                                id: '{{ $unit->id }}',
                                unit: '{{ $unit->unit }}'
                            },
                        @endforeach
                    ];

                    units.forEach(function(unit) {
                        kebunSelect.append(
                            `<option value="${unit.id}">${unit.unit}</option>`
                        );
                    });
                }
            });

            // Reset filter button
            $('#resetFilter').click(function() {
                $('#filterRegion').val('');
                $('#filterPilar').val('');
                $('#filterKebun').val('');
                $('#filterTahun').val('');

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

                // Update field koordinat gabungan juga
                if (latInputId === 'latitude') {
                    $('#koordinat').val(lat.toFixed(6) + ', ' + lng.toFixed(6));
                } else if (latInputId === 'edit_latitude') {
                    $('#edit_koordinat_display').val(lat.toFixed(6) + ', ' + lng.toFixed(6));
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
                        // Sort data berdasarkan nama secara ascending
                        const sortedData = data.sort(function(a, b) {
                            return a.nama.localeCompare(b.nama);
                        });

                        return {
                            results: sortedData.map(function(item) {
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
                                // Sort data berdasarkan nama secara ascending
                                const sortedData = data.sort(function(a, b) {
                                    return a.nama.localeCompare(b.nama);
                                });

                                return {
                                    results: sortedData.map(function(item) {
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
                                // Sort data berdasarkan nama secara ascending
                                const sortedData = data.sort(function(a, b) {
                                    return a.nama.localeCompare(b.nama);
                                });

                                return {
                                    results: sortedData.map(function(item) {
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
                                // Sort data berdasarkan nama secara ascending
                                const sortedData = data.sort(function(a, b) {
                                    return a.nama.localeCompare(b.nama);
                                });

                                return {
                                    results: sortedData.map(function(item) {
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
            // Ambil kode wilayah (value) bukan nama (text)
            const provKode = $('#edit_lokasi_provinsi').val() || '';
            const kabKode = $('#edit_lokasi_kabupaten').val() || '';
            const kecKode = $('#edit_lokasi_kecamatan').val() || '';
            const desaKode = $('#edit_lokasi_desa').val() || '';

            // Susun kode lokasi dalam format: NN.NN.NN.NNNN (provinsi.kabupaten.kecamatan.desa)
            if (desaKode) {
                $('#edit_lokasi_program').val(desaKode); // Kode desa sudah lengkap (format: NN.NN.NN.NNNN)
            } else if (kecKode) {
                $('#edit_lokasi_program').val(kecKode); // Kode kecamatan (format: NN.NN.NN)
            } else if (kabKode) {
                $('#edit_lokasi_program').val(kabKode); // Kode kabupaten (format: NN.NN)
            } else if (provKode) {
                $('#edit_lokasi_program').val(provKode); // Kode provinsi (format: NN)
            } else {
                $('#edit_lokasi_program').val('');
            }
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
                <div class="col-md-4">
                    <label class="form-label">Sub Pilar/TPB</label>
                    <select class="form-control biaya-sub-pilar" name="biaya[${biayaIndex}][sub_pilar_id]">
                        <option value="">Pilih Sub Pilar</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Anggaran (Rp)</label>
                    <input type="number" class="form-control" name="biaya[${biayaIndex}][anggaran]" step="0.01" placeholder="0.00">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Realisasi (Rp)</label>
                    <input type="number" class="form-control" name="biaya[${biayaIndex}][realisasi]" step="0.01" placeholder="0.00">
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm removeBiaya">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
            const $newBiaya = $(biayaHtml);
            $('#biayaContainer').append($newBiaya);

            // Populate sub pilar options for the new dropdown
            const $newSelect = $newBiaya.find('.biaya-sub-pilar');

            // Urutkan berdasarkan ID sebelum menambahkan opsi
            const sortedSubpilars = [...allSubpilars].sort((a, b) => parseInt(a.id) - parseInt(b.id));

            sortedSubpilars.forEach(function(subpilar) {
                $newSelect.append(
                    `<option value="${subpilar.id}">${subpilar.id}.${subpilar.text}</option>`);
            });

            // Update sub pilar options based on current program unggulan
            const programUnggulanId = $('#program_unggulan_id').val();
            if (programUnggulanId && programUnggulanMap[programUnggulanId]) {
                updateBiayaSubPilarOptions(programUnggulanMap[programUnggulanId]);
            }

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

        // Handler untuk tombol addDokumentasi sudah dihapus karena tidak diperlukan lagi
        /*
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
                                    */

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

        // Hapus Select2 dan buat dropdown searchable manual
        if ($('#unit_id').hasClass('select2-hidden-accessible')) {
            $('#unit_id').select2('destroy');
        }
        if ($('#edit_unit_id').hasClass('select2-hidden-accessible')) {
            $('#edit_unit_id').select2('destroy');
        }

        // Fungsi untuk membuat dropdown searchable
        function createSearchableDropdown(selectId, placeholder) {
            var $select = $(selectId);
            var $parent = $select.parent();

            // Buat wrapper
            var $wrapper = $('<div class="searchable-dropdown-wrapper" style="position: relative;"></div>');

            // Buat input search
            var $input = $('<input type="text" class="form-control searchable-input" placeholder="' + placeholder +
                '" autocomplete="off">');

            // Buat dropdown list
            var $dropdown = $(
                '<div class="searchable-dropdown-list" style="display: none; position: absolute; top: 100%; left: 0; right: 0; max-height: 200px; overflow-y: auto; background: white; border: 1px solid #ced4da; border-top: none; z-index: 1050; border-radius: 0 0 0.25rem 0.25rem;"></div>'
            );

            // Simpan opsi asli
            var options = [];
            $select.find('option').each(function() {
                if ($(this).val()) {
                    options.push({
                        value: $(this).val(),
                        text: $(this).text()
                    });
                }
            });

            // Sembunyikan select asli dan tambahkan wrapper
            $select.hide();
            $parent.append($wrapper);
            $wrapper.append($input).append($dropdown);

            // Set nilai awal jika ada
            var initialValue = $select.val();
            if (initialValue) {
                var selectedOption = options.find(opt => opt.value == initialValue);
                if (selectedOption) {
                    $input.val(selectedOption.text);
                }
            }

            // Event untuk menampilkan dropdown
            $input.on('focus click', function() {
                showOptions('');
                $dropdown.show();
                $input.css('border-radius', '0.25rem 0.25rem 0 0');
            });

            // Event untuk mencari
            $input.on('input', function() {
                var searchTerm = $(this).val().toLowerCase();
                showOptions(searchTerm);
                $dropdown.show();

                // Jika input kosong, reset select value
                if (!searchTerm) {
                    $select.val('').trigger('change');
                }
            });

            // Event untuk menyembunyikan dropdown
            $(document).on('click', function(e) {
                if (!$wrapper.is(e.target) && $wrapper.has(e.target).length === 0) {
                    $dropdown.hide();
                    $input.css('border-radius', '0.25rem');

                    // Validasi input saat dropdown ditutup
                    validateInput();
                }
            });

            // Fungsi validasi input
            function validateInput() {
                var inputText = $input.val().trim();
                var currentValue = $select.val();

                if (inputText) {
                    // Cari opsi yang cocok dengan text yang diinput
                    var matchedOption = options.find(function(option) {
                        return option.text.toLowerCase() === inputText.toLowerCase();
                    });

                    if (matchedOption) {
                        // Jika ada yang cocok, set value
                        if (currentValue !== matchedOption.value) {
                            $select.val(matchedOption.value).trigger('change');
                        }
                    } else {
                        // Jika tidak ada yang cocok, reset
                        $input.val('');
                        $select.val('').trigger('change');
                    }
                } else {
                    // Jika input kosong, reset select
                    $select.val('').trigger('change');
                }
            }

            // Fungsi untuk menampilkan opsi
            function showOptions(searchTerm) {
                $dropdown.empty();

                // Tambah opsi kosong jika tidak ada search term
                if (!searchTerm) {
                    var $emptyOption = $(
                        '<div class="dropdown-option" data-value="" style="padding: 8px 12px; cursor: pointer; color: #6c757d;">Pilih ' +
                        placeholder + '</div>');
                    $emptyOption.on('click', function() {
                        $input.val('');
                        $select.val('').trigger('change');
                        $dropdown.hide();
                        $input.css('border-radius', '0.25rem');
                    });
                    $dropdown.append($emptyOption);
                }

                // Filter dan tampilkan opsi
                var filteredOptions = options.filter(function(option) {
                    return option.text.toLowerCase().includes(searchTerm);
                });

                filteredOptions.forEach(function(option) {
                    var $option = $('<div class="dropdown-option" data-value="' + option.value +
                        '" style="padding: 8px 12px; cursor: pointer;">' + option.text + '</div>');

                    $option.on('click', function() {
                        $input.val(option.text);
                        $select.val(option.value).trigger('change');
                        $dropdown.hide();
                        $input.css('border-radius', '0.25rem');

                        // Trigger validation untuk Bootstrap
                        $select[0].setCustomValidity('');
                        $select.removeClass('is-invalid').addClass('is-valid');
                    });

                    // Hover effect
                    $option.on('mouseenter', function() {
                        $(this).css('background-color', '#f8f9fa');
                    }).on('mouseleave', function() {
                        $(this).css('background-color', 'white');
                    });

                    $dropdown.append($option);
                });

                // Jika tidak ada hasil
                if (filteredOptions.length === 0 && searchTerm) {
                    $dropdown.append(
                        '<div style="padding: 8px 12px; color: #6c757d; font-style: italic;">Tidak ada hasil ditemukan</div>'
                    );
                }
            }

            // Keyboard navigation
            $input.on('keydown', function(e) {
                var $options = $dropdown.find('.dropdown-option[data-value]');
                var $active = $options.filter('.active');

                if (e.key === 'ArrowDown') {
                    e.preventDefault();
                    if ($active.length === 0) {
                        $options.first().addClass('active').css('background-color', '#007bff').css('color',
                            'white');
                    } else {
                        $active.removeClass('active').css('background-color', 'white').css('color', 'black');
                        var next = $active.next('.dropdown-option[data-value]');
                        if (next.length === 0) next = $options.first();
                        next.addClass('active').css('background-color', '#007bff').css('color', 'white');
                    }
                } else if (e.key === 'ArrowUp') {
                    e.preventDefault();
                    if ($active.length === 0) {
                        $options.last().addClass('active').css('background-color', '#007bff').css('color', 'white');
                    } else {
                        $active.removeClass('active').css('background-color', 'white').css('color', 'black');
                        var prev = $active.prev('.dropdown-option[data-value]');
                        if (prev.length === 0) prev = $options.last();
                        prev.addClass('active').css('background-color', '#007bff').css('color', 'white');
                    }
                } else if (e.key === 'Enter') {
                    e.preventDefault();
                    if ($active.length > 0) {
                        $active.click();
                    }
                } else if (e.key === 'Escape') {
                    $dropdown.hide();
                    $input.css('border-radius', '0.25rem');
                    validateInput();
                }
            });

            // Event blur untuk validasi
            $input.on('blur', function() {
                setTimeout(function() {
                    if (!$dropdown.is(':visible')) {
                        validateInput();
                    }
                }, 150);
            });

            // Return object dengan method untuk set value
            return {
                setValue: function(value, text) {
                    if (text) {
                        $input.val(text);
                    } else if (value) {
                        // Cari text berdasarkan value
                        var option = options.find(opt => opt.value == value);
                        if (option) {
                            $input.val(option.text);
                        }
                    } else {
                        $input.val('');
                    }
                    $select.val(value).trigger('change');
                },
                getValue: function() {
                    return $select.val();
                },
                getText: function() {
                    return $input.val();
                },
                validate: function() {
                    validateInput();
                    return $select.val() !== '';
                }
            };
        }

        // Terapkan ke unit_id
        var unitDropdown = createSearchableDropdown('#unit_id', 'Ketik untuk mencari Unit/Kebun');

        // Terapkan ke edit_unit_id juga
        var editUnitDropdown = createSearchableDropdown('#edit_unit_id', 'Ketik untuk mencari Unit/Kebun');

        // Tambahkan event handler untuk edit form submit validation
        $('#editTjslForm').on('submit', function(e) {
            // Validasi field edit modal
            var isValid = true;
            var invalidFields = [];

            // Validasi edit_nama_program
            var editNamaProgram = $('#edit_nama_program').val().trim();
            if (!editNamaProgram) {
                $('#edit_nama_program').addClass('is-invalid');
                invalidFields.push('Nama Program');
                isValid = false;
            } else {
                $('#edit_nama_program').removeClass('is-invalid').addClass('is-valid');
            }

            // Validasi edit_unit_id (dropdown custom)
            if (typeof editUnitDropdown !== 'undefined') {
                if (!editUnitDropdown.validate()) {
                    $('#edit_unit_id').addClass('is-invalid');
                    invalidFields.push('Unit/Kebun');
                    isValid = false;
                } else {
                    $('#edit_unit_id').removeClass('is-invalid').addClass('is-valid');
                }
            } else {
                // Fallback untuk validasi edit_unit_id biasa
                var editUnitId = $('#edit_unit_id').val();
                if (!editUnitId) {
                    $('#edit_unit_id').addClass('is-invalid');
                    invalidFields.push('Unit/Kebun');
                    isValid = false;
                } else {
                    $('#edit_unit_id').removeClass('is-invalid').addClass('is-valid');
                }
            }

            // Validasi edit_pilar_id
            var editPilarId = $('#edit_pilar_id').val();
            if (!editPilarId) {
                $('#edit_pilar_id').addClass('is-invalid');
                invalidFields.push('Pilar');
                isValid = false;
            } else {
                $('#edit_pilar_id').removeClass('is-invalid').addClass('is-valid');
            }

            if (!isValid) {
                e.preventDefault();
                var errorMessage = 'Field berikut wajib diisi:\n• ' + invalidFields.join('\n• ');
                alert(errorMessage);

                // Scroll ke field pertama yang invalid dalam modal
                var firstInvalidField = $('#editTjslModal .is-invalid').first();
                if (firstInvalidField.length) {
                    firstInvalidField.focus();
                }

                return false;
            }
        });

        // Tambahkan event handler untuk form submit validation (input form saja)
        $('#tjslForm').on('submit', function(e) {
            console.log('Input form submit triggered');

            // Validasi hanya field input form (bukan edit modal)
            var isValid = true;
            var invalidFields = [];

            // Validasi nama_program
            var namaProgram = $('#nama_program').val().trim();
            console.log('Nama Program:', namaProgram);
            if (!namaProgram) {
                $('#nama_program').addClass('is-invalid');
                invalidFields.push('Nama Program');
                isValid = false;
            } else {
                $('#nama_program').removeClass('is-invalid').addClass('is-valid');
            }

            // Validasi unit_id (dropdown custom)
            if (typeof unitDropdown !== 'undefined') {
                console.log('Using custom unit dropdown');
                if (!unitDropdown.validate()) {
                    $('#unit_id').addClass('is-invalid');
                    invalidFields.push('Unit/Kebun');
                    isValid = false;
                } else {
                    $('#unit_id').removeClass('is-invalid').addClass('is-valid');
                }
            } else {
                // Fallback untuk validasi unit_id biasa
                var unitId = $('#unit_id').val();
                console.log('Unit ID (fallback):', unitId);
                if (!unitId) {
                    $('#unit_id').addClass('is-invalid');
                    invalidFields.push('Unit/Kebun');
                    isValid = false;
                } else {
                    $('#unit_id').removeClass('is-invalid').addClass('is-valid');
                }
            }

            // Validasi pilar_id
            var pilarId = $('#pilar_id').val();
            console.log('Pilar ID:', pilarId);
            if (!pilarId) {
                $('#pilar_id').addClass('is-invalid');
                invalidFields.push('Pilar');
                isValid = false;
            } else {
                $('#pilar_id').removeClass('is-invalid').addClass('is-valid');
            }

            console.log('Validation result:', isValid, 'Invalid fields:', invalidFields);

            if (!isValid) {
                e.preventDefault();
                var errorMessage = 'Field berikut wajib diisi:\n• ' + invalidFields.join('\n• ');
                alert(errorMessage);

                // Scroll ke field pertama yang invalid
                var firstInvalidField = $('.is-invalid').first();
                if (firstInvalidField.length) {
                    firstInvalidField.focus();
                    $('html, body').animate({
                        scrollTop: firstInvalidField.offset().top - 100
                    }, 500);
                }

                return false;
            }

            console.log('Input form validation passed, submitting...');
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

            // Urutkan berdasarkan ID
            data.sort((a, b) => parseInt(a.id) - parseInt(b.id));

            // Reset pilihan lalu isi ulang opsi
            $select.empty();
            data.forEach(s => $select.append(new Option(`${s.id}.${s.text}`, s.id)));
            // Kosongkan nilai terpilih dan trigger update ke Select2
            $select.val(null).trigger('change');
        }

        // Filter saat program unggulan berubah
        $('#program_unggulan_id').on('change', function() {
            const id = $(this).val();
            renderSubPilarOptions(programUnggulanMap[id] || []);
            // Update biaya sub pilar dropdowns
            updateBiayaSubPilarOptions(programUnggulanMap[id] || []);
        });

        // Function to update biaya sub pilar dropdowns
        function updateBiayaSubPilarOptions(allowedIds) {
            $('.biaya-sub-pilar').each(function() {
                const $select = $(this);
                const currentVal = $select.val();

                $select.empty();
                $select.append('<option value="">Pilih Sub Pilar</option>');

                const data = (allowedIds && allowedIds.length) ?
                    allSubpilars.filter(s => allowedIds.includes(s.id)) :
                    allSubpilars;

                // Urutkan berdasarkan ID
                data.sort((a, b) => parseInt(a.id) - parseInt(b.id));

                data.forEach(s => {
                    $select.append(`<option value="${s.id}">${s.id}.${s.text}</option>`);
                });

                // Keep current value if still valid
                if (currentVal && data.some(d => d.id === currentVal)) {
                    $select.val(currentVal);
                }
            });
        }

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

            // Urutkan berdasarkan ID
            data.sort((a, b) => parseInt(a.id) - parseInt(b.id));

            $select.empty();
            data.forEach(s => $select.append(new Option(`${s.id}.${s.text}`, s.id)));

            // Pertahankan nilai yang masih valid
            const keep = currentVals.filter(v => data.some(d => d.id === v));
            $select.val(keep).trigger('change');
        }

        // Filter saat Program Unggulan berubah di modal edit
        $('#edit_program_unggulan_id').on('change', function() {
            const id = $(this).val();
            renderEditSubPilarOptions(editProgramUnggulanMap[id] || []);
            // Update edit biaya sub pilar dropdowns
            updateEditBiayaSubPilarOptions(editProgramUnggulanMap[id] || []);
        });

        // Function to update edit biaya sub pilar dropdowns
        function updateEditBiayaSubPilarOptions(allowedIds) {
            $('.edit-biaya-sub-pilar').each(function() {
                const $select = $(this);
                const currentVal = $select.val();

                $select.empty();
                $select.append('<option value="">Pilih Sub Pilar</option>');

                const data = (allowedIds && allowedIds.length) ?
                    allSubpilarsEdit.filter(s => allowedIds.includes(s.id)) :
                    allSubpilarsEdit;

                // Urutkan berdasarkan ID
                data.sort((a, b) => parseInt(a.id) - parseInt(b.id));

                data.forEach(s => {
                    $select.append(`<option value="${s.id}">${s.id}.${s.text}</option>`);
                });

                // Keep current value if still valid
                if (currentVal && data.some(d => d.id === currentVal)) {
                    $select.val(currentVal);
                }
            });
        }

        // Saat modal edit dibuka, apply filter sesuai nilai awal (jika ada)
        $('#editTjslModal').on('shown.bs.modal', function() {
            const id = $('#edit_program_unggulan_id').val();
            renderEditSubPilarOptions(editProgramUnggulanMap[id] || []);
            // Also update edit biaya sub pilar options
            updateEditBiayaSubPilarOptions(editProgramUnggulanMap[id] || []);
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
                            // Sort data berdasarkan nama secara ascending
                            const sortedData = data.sort(function(a, b) {
                                return a.nama.localeCompare(b.nama);
                            });

                            return {
                                results: sortedData.map(function(item) {
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
                            // Sort data berdasarkan nama secara ascending
                            const sortedData = data.sort(function(a, b) {
                                return a.nama.localeCompare(b.nama);
                            });

                            return {
                                results: sortedData.map(function(item) {
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
                            // Sort data berdasarkan nama secara ascending
                            const sortedData = data.sort(function(a, b) {
                                return a.nama.localeCompare(b.nama);
                            });

                            return {
                                results: sortedData.map(function(item) {
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
                // Ambil kode wilayah (id) bukan nama (text)
                const provKode = $('#lokasi_provinsi').val() || '';
                const kabKode = $('#lokasi_kabupaten').val() || '';
                const kecKode = $('#lokasi_kecamatan').val() || '';
                const desaKode = $('#lokasi_desa').val() || '';

                // Susun kode lokasi dalam format: NN.NN.NN.NNNN (provinsi.kabupaten.kecamatan.desa)
                if (desaKode) {
                    $('#lokasi_program').val(desaKode); // Kode desa sudah lengkap (format: NN.NN.NN.NNNN)
                } else if (kecKode) {
                    $('#lokasi_program').val(kecKode); // Kode kecamatan (format: NN.NN.NN)
                } else if (kabKode) {
                    $('#lokasi_program').val(kabKode); // Kode kabupaten (format: NN.NN)
                } else if (provKode) {
                    $('#lokasi_program').val(provKode); // Kode provinsi (format: NN)
                } else {
                    $('#lokasi_program').val('');
                }
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
                $('#edit_koordinat_display').val('');

                // Hapus peta edit jika ada
                if (window.editMapInstance) {
                    window.editMapInstance.remove();
                    window.editMapInstance = null;
                }
            });

            // Initialize remove button states
            updateRemoveButtons();

            // Handler untuk koordinat gabungan - Form Tambah
            $('#koordinat').on('input', function() {
                const koordinat = $(this).val().trim();
                if (koordinat) {
                    const parts = koordinat.split(',');
                    if (parts.length === 2) {
                        const lat = parts[0].trim();
                        const lng = parts[1].trim();
                        $('#latitude').val(lat);
                        $('#longitude').val(lng);

                        // Update peta jika ada
                        if (window.lokasiMapInstance && lat && lng) {
                            const latNum = parseFloat(lat);
                            const lngNum = parseFloat(lng);
                            if (!isNaN(latNum) && !isNaN(lngNum)) {
                                window.lokasiMapInstance.setMarker(latNum, lngNum, true);
                            }
                        }
                    }
                } else {
                    $('#latitude').val('');
                    $('#longitude').val('');
                }
            });

            // Handler untuk koordinat gabungan - Form Edit
            $('#edit_koordinat_display').on('input', function() {
                const koordinat = $(this).val().trim();
                if (koordinat) {
                    const parts = koordinat.split(',');
                    if (parts.length === 2) {
                        const lat = parts[0].trim();
                        const lng = parts[1].trim();
                        $('#edit_latitude').val(lat);
                        $('#edit_longitude').val(lng);

                        // Update peta jika ada
                        if (window.editMapInstance && lat && lng) {
                            const latNum = parseFloat(lat);
                            const lngNum = parseFloat(lng);
                            if (!isNaN(latNum) && !isNaN(lngNum)) {
                                window.editMapInstance.setMarker(latNum, lngNum, true);
                            }
                        }
                    }
                } else {
                    $('#edit_latitude').val('');
                    $('#edit_longitude').val('');
                }
            });
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
