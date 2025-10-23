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

    <!-- Include Modals -->
    @include('tjsl.modals.create-modal')
@include('tjsl.modals.edit-modal')

    <!-- CSS Styles -->
    <style>
        .program-card[data-status="1"] .card {
            border-left: 4px solid #007bff !important;
        }
        .program-card[data-status="2"] .card {
            border-left: 4px solid #ffc107 !important;
        }
        .program-card[data-status="3"] .card {
            border-left: 4px solid #28a745 !important;
        }
    </style>
@endsection

@push('scripts')
    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    
    <!-- Data untuk JavaScript -->
    <script>
        // Data sub pilar untuk JavaScript
        window.subpilarsData = @json($subpilars);
        window.unitsData = @json($units);
        
        console.log('Data loaded:', {
            subpilars: window.subpilarsData.length,
            units: window.unitsData.length
        });
    </script>
    
    <!-- TJSL Scripts -->
    <script src="{{ asset('js/tjsl-scripts.js') }}?v={{ time() }}"></script>
@endpush