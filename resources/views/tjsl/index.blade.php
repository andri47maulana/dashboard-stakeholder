@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Program TJSL</h1>
            <a href="{{ route('tjsl.create') }}" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Tambah Program
            </a>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="filterStatus">Filter Status:</label>
                    <select class="form-control" id="filterStatus">
                        <option value="">Semua Status</option>
                        <option value="1">Proposed</option>
                        <option value="2">Active</option>
                        <option value="3">Completed</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="searchProgram">Cari Program:</label>
                    <input type="text" class="form-control" id="searchProgram" placeholder="Masukkan nama program...">
                </div>
            </div>
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
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        {{ $tjsl->nama_program }}
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
                                        <span class="badge badge-success">Completed</span>
                                    @elseif($tjsl->status == 2)
                                        <span class="badge badge-warning">On Progress</span>
                                    @else
                                        <span class="badge badge-secondary">Planning</span>
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
                                    <div class="text-xs mb-0 text-gray-600">
                                        <i class="fas fa-calendar-alt fa-sm text-gray-400"></i>
                                        Mulai: {{ $tjsl->tanggal_mulai->format('d M Y') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Tanggal Akhir -->
                            <div class="row no-gutters align-items-center mb-3">
                                <div class="col">
                                    <div class="text-xs mb-0 text-gray-600">
                                        <i class="fas fa-calendar-check fa-sm text-gray-400"></i>
                                        Selesai: {{ $tjsl->tanggal_akhir }}
                                    </div>
                                </div>
                            </div>

                            <!-- Button Lihat Detail -->
                            <div class="row no-gutters">
                                <div class="col">
                                    <a href="{{ route('tjsl.show', $tjsl->id) }}" class="btn btn-primary btn-sm btn-block">
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Filter by status
            $('#filterStatus').on('change', function() {
                var selectedStatus = $(this).val();
                filterCards();
            });

            // Search by program name
            $('#searchProgram').on('keyup', function() {
                filterCards();
            });

            function filterCards() {
                var selectedStatus = $('#filterStatus').val();
                var searchText = $('#searchProgram').val().toLowerCase();

                $('.program-card').each(function() {
                    var cardStatus = $(this).data('status').toString();
                    var cardProgram = $(this).data('program');

                    var statusMatch = selectedStatus === '' || cardStatus === selectedStatus;
                    var programMatch = searchText === '' || cardProgram.includes(searchText);

                    if (statusMatch && programMatch) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });

                // Show/hide empty message
                var visibleCards = $('.program-card:visible').length;
                if (visibleCards === 0 && $('.program-card').length > 0) {
                    if ($('#noResultsMessage').length === 0) {
                        $('#programCards').append(`
                    <div class="col-12" id="noResultsMessage">
                        <div class="card shadow">
                            <div class="card-body text-center py-4">
                                <i class="fas fa-search fa-2x text-muted mb-3"></i>
                                <h6 class="text-muted">Tidak ada program yang sesuai dengan filter</h6>
                                <p class="text-muted mb-0">Coba ubah kriteria pencarian Anda.</p>
                            </div>
                        </div>
                    </div>
                `);
                    }
                } else {
                    $('#noResultsMessage').remove();
                }
            }
        });
    </script>
@endpush

@push('styles')
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
@endpush
