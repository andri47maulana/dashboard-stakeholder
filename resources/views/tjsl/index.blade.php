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
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="row align-items-end">
                    <div class="col-md-2">
                        <label for="filterRegion" class="form-label small text-muted">Region</label>
                        <select class="form-control form-control-sm" id="filterRegion">
                            <option value="">Semua Regionals</option>
                            @foreach ($regions as $region)
                                <option value="{{ $region }}">{{ $region }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label for="filterPilar" class="form-label small text-muted">Pilar</label>
                        <select class="form-control form-control-sm" id="filterPilar">
                            <option value="">Semua Pilar</option>
                            @foreach ($pilars as $pilar)
                                <option value="{{ $pilar->id }}">{{ $pilar->pilar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterKebun" class="form-label small text-muted">Kebun</label>
                        <select class="form-control form-control-sm" id="filterKebun">
                            <option value="">Semua Unit</option>
                            @foreach ($units as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <label for="filterTahun" class="form-label small text-muted">Tahun</label>
                        <select class="form-control form-control-sm" id="filterTahun">
                            <option value="">Semua Tahun</option>
                            @foreach ($tahunList as $tahun)
                                <option value="{{ $tahun }}">{{ $tahun }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-success btn-sm" id="applyFilter">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-warning btn-sm" id="resetFilter">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
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
                                    <div class="text-sm font-weight-bold mb-1">
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
        console.log('Filter region element found:', $('#filterRegion').length);
        $(document).ready(function() {
            console.log('tes');
            console.log('Document ready - jQuery loaded:', typeof $ !== 'undefined');
            console.log('Filter region element found:', $('#filterRegion').length);

            // Apply filter button
            $('#applyFilter').click(function() {
                applyFilters();
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

            // PERBAIKAN: Filter kebun berdasarkan region yang dipilih
            // Hapus semua event handler yang ada dan buat yang baru
            $('#filterRegion').on('click', function(e) {
                console.log('disini');

                const selectedRegion = $(this).val();
                const kebunSelect = $('#filterKebun');

                console.log('Selected region:', selectedRegion);
                console.log('Kebun select element found:', kebunSelect.length);

                if (selectedRegion && selectedRegion.trim() !== '') {
                    console.log('Making AJAX request for region:', selectedRegion);

                    // AJAX call untuk mendapatkan unit berdasarkan region
                    $.ajax({
                        url: '{{ route('get.units.by.region') }}',
                        method: 'GET',
                        data: {
                            region: selectedRegion,
                            _token: '{{ csrf_token() }}'
                        },
                        beforeSend: function(xhr) {
                            console.log('AJAX beforeSend - URL:',
                                '{{ route('get.units.by.region') }}');
                            console.log('AJAX beforeSend - Data:', {
                                region: selectedRegion
                            });
                            kebunSelect.html('<option value="">Loading...</option>');
                        },
                        success: function(response) {
                            console.log('AJAX SUCCESS - Response:', response);
                            console.log('Response type:', typeof response);
                            console.log('Is array:', Array.isArray(response));

                            kebunSelect.html('<option value="">Semua Kebun</option>');

                            if (response && Array.isArray(response) && response.length > 0) {
                                console.log('Processing', response.length, 'units');
                                response.forEach(function(unit, index) {
                                    console.log('Unit', index + 1, ':', unit);
                                    kebunSelect.append(
                                        `<option value="${unit.id}">${unit.unit}</option>`
                                    );
                                });
                                console.log('Units added to dropdown');
                            } else {
                                console.log('No units found for region:', selectedRegion);
                                kebunSelect.append('<option value="">Tidak ada kebun</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('=== AJAX ERROR ===');
                            console.error('Status:', status);
                            console.error('Error:', error);
                            console.error('Response Text:', xhr.responseText);
                            console.error('Status Code:', xhr.status);

                            kebunSelect.html('<option value="">Error loading data</option>');

                            // Tampilkan error detail
                            if (xhr.responseText) {
                                try {
                                    const errorResponse = JSON.parse(xhr.responseText);
                                    console.error('Error Response JSON:', errorResponse);
                                } catch (e) {
                                    console.error('Error Response (not JSON):', xhr
                                        .responseText);
                                }
                            }
                        }
                    });
                } else {
                    console.log('Resetting to all units (empty region)');
                    // Reset ke semua kebun
                    kebunSelect.html('<option value="">Semua Kebun</option>');
                    @foreach ($units as $unit)
                        kebunSelect.append(
                            '<option value="{{ $unit->id }}">{{ $unit->unit }}</option>');
                    @endforeach
                    console.log('All units restored');
                }
            });

            // Reset filter button
            $('#resetFilter').click(function() {
                console.log('Reset button clicked');
                $('#filterRegion').val('').trigger('change');
                $('#filterPilar').val('');
                $('#filterKebun').val('');
                $('#filterTahun').val('');
                $('#filterStatus').val('');
                $('#searchProgram').val('');
                applyFilters();
            });

            // Test manual trigger (untuk debugging)
            console.log('=== TESTING MANUAL TRIGGER ===');
            setTimeout(function() {
                console.log('Manual trigger test in 2 seconds...');
                $('#filterRegion').trigger('change');
            }, 2000);
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
