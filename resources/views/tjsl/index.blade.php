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
                                            data-id="{{ $tjsl->id }}"
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
        
        // Custom edit button handler
        $(document).ready(function() {
            console.log('=== SETTING UP EDIT HANDLER ===');
            
            // Edit button click handler
            $(document).on('click', '.edit-program-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();
                
                const tjslId = $(this).data('id');
                console.log('=== EDIT BUTTON CLICKED ===');
                console.log('TJSL ID:', tjslId);
                
                // Set form action
                $('#editTjslForm').attr('action', `/tjsl/${tjslId}`);
                
                // Show modal
                $('#editTjslModal').modal('show');
                
                // Load data
                console.log('Loading data for ID:', tjslId);
                $.ajax({
                    url: `/tjsl/${tjslId}/edit-data`,
                    method: 'GET',
                    success: function(response) {
                        console.log('=== DATA LOADED SUCCESSFULLY ===');
                        console.log('Response:', response);
                        console.log('Response type:', typeof response);
                        console.log('Response.success:', response.success);
                        console.log('Response.data:', response.data);
                        
                        // Check if response is the data directly (not wrapped in success/data)
                        const data = response.data ? response.data : response;
                        console.log('Using data:', data);
                        
                        // Populate form fields
                        if (data && (response.success !== false)) {
                            console.log('=== POPULATING FIELDS ===');
                            
                            // Basic fields
                            console.log('Setting nama_program:', data.nama_program);
                            $('#edit_nama_program').val(data.nama_program || '');
                            
                            console.log('Setting deskripsi:', data.deskripsi);
                            $('#edit_deskripsi').val(data.deskripsi || '');
                            
                            console.log('Setting status:', data.status);
                            $('#edit_status').val(data.status || '').trigger('change');
                            
                            // Pilar field
                            if (data.pilar_id) {
                                console.log('Setting pilar_id:', data.pilar_id);
                                $('#edit_pilar_id').val(data.pilar_id).trigger('change');
                            }
                            
                            // Select2 fields
                            if (data.sub_pilar_id) {
                                console.log('Setting sub_pilar_id:', data.sub_pilar_id);
                                $('#edit_sub_pilar').val(data.sub_pilar_id).trigger('change');
                            }
                            if (data.unit_id) {
                                console.log('Setting unit_id:', data.unit_id);
                                $('#edit_unit_id').val(data.unit_id).trigger('change');
                            }
                            
                            // Program unggulan
                            if (data.program_unggulan_id) {
                                console.log('Setting program_unggulan_id:', data.program_unggulan_id);
                                $('#edit_program_unggulan_id').val(data.program_unggulan_id).trigger('change');
                            }
                            
                            // Date fields (fix field names and format)
                            if (data.tanggal_mulai) {
                                console.log('Setting tanggal_mulai:', data.tanggal_mulai);
                                // Convert ISO date to YYYY-MM-DD format
                                const startDate = new Date(data.tanggal_mulai).toISOString().split('T')[0];
                                console.log('Formatted start date:', startDate);
                                $('#edit_tanggal_mulai').val(startDate);
                            }
                            // End date (tanggal_akhir) - convert from ISO to YYYY-MM-DD format
                            console.log('Checking tanggal_akhir data...');
                            console.log('Raw tanggal_akhir:', data.tanggal_akhir);
                            
                            if (data.tanggal_akhir) {
                                console.log('Setting tanggal_akhir:', data.tanggal_akhir);
                                $('#edit_tanggal_akhir').val(data.tanggal_akhir);
                                console.log('Tanggal akhir field value after setting:', $('#edit_tanggal_akhir').val());
                            } else {
                                console.log('No tanggal_akhir data available');
                                $('#edit_tanggal_akhir').val('');
                            }
                            
                            // Location fields
                            console.log('Setting lokasi_program:', data.lokasi_program);
                            $('#edit_lokasi_program').val(data.lokasi_program || '');
                            
                            console.log('Setting penerima_dampak:', data.penerima_dampak);
                            $('#edit_penerima_dampak').val(data.penerima_dampak || '');
                            
                            // Location dropdowns - TJSL doesn't have separate location fields
                            // Location is stored in lokasi_program as a code (e.g., "32.73.09.1003")
                            console.log('Checking location data...');
                            console.log('Lokasi program:', data.lokasi_program);
                            
                            // Note: TJSL model doesn't have provinsi_id, kabupaten_id, kecamatan_id, desa_id fields
                            // Location is stored as a single code in lokasi_program field
                            // The edit form uses separate dropdowns but they're not connected to the TJSL model
                            console.log('Location dropdowns are not connected to TJSL model - they use separate wilayah system');
                            
                            // Coordinates
                            console.log('Checking coordinates data...');
                            console.log('Latitude:', data.latitude);
                            console.log('Longitude:', data.longitude);
                            
                            if (data.latitude && data.longitude) {
                                console.log('Setting coordinates:', data.latitude, data.longitude);
                                $('#edit_latitude').val(data.latitude);
                                $('#edit_longitude').val(data.longitude);
                                $('#edit_koordinat_display').val(data.latitude + ', ' + data.longitude);
                                $('#edit_koordinat').val(data.latitude + ', ' + data.longitude);
                                console.log('Koordinat display field value after setting:', $('#edit_koordinat_display').val());
                                
                                // Initialize edit map if not already initialized
                                if (typeof editMap === 'undefined' || !editMap) {
                                    console.log('Initializing edit map...');
                                    if (typeof initializeEditMap === 'function') {
                                        initializeEditMap();
                                    }
                                }
                                
                                // Set map view and marker after initialization
                                setTimeout(function() {
                                    if (typeof editMap !== 'undefined' && editMap) {
                                        console.log('Setting map view and marker');
                                        editMap.setView([data.latitude, data.longitude], 13);
                                        
                                        // Remove existing marker if any
                                        if (typeof editCurrentMarker !== 'undefined' && editCurrentMarker) {
                                            editMap.removeLayer(editCurrentMarker);
                                        }
                                        
                                        // Add new marker
                                        editCurrentMarker = L.marker([data.latitude, data.longitude]).addTo(editMap);
                                    } else {
                                        console.log('Edit map not found or not initialized');
                                    }
                                }, 100);
                            } else {
                                console.log('No coordinates data available');
                            }
                            
                            // Biaya fields - handle first biaya record
                            console.log('Checking biaya data...');
                            console.log('Biaya data:', data.biaya);
                            
                            if (data.biaya && data.biaya.length > 0) {
                                console.log('Processing biaya fields...');
                                const biaya = data.biaya[0]; // First biaya record
                                console.log('First biaya record:', biaya);
                                
                                if (biaya.sub_pilar_id) {
                                    console.log('Setting edit_biaya_sub_pilar:', biaya.sub_pilar_id);
                                    $('#edit_biaya_sub_pilar').val(biaya.sub_pilar_id).trigger('change');
                                    console.log('Sub pilar field value after setting:', $('#edit_biaya_sub_pilar').val());
                                } else {
                                    console.log('No sub_pilar_id in biaya record');
                                }
                                
                                if (biaya.anggaran) {
                                    console.log('Setting edit_biaya_anggaran:', biaya.anggaran);
                                    $('#edit_biaya_anggaran').val(biaya.anggaran);
                                    console.log('Anggaran field value after setting:', $('#edit_biaya_anggaran').val());
                                } else {
                                    console.log('No anggaran in biaya record');
                                }
                                
                                if (biaya.realisasi) {
                                    console.log('Setting edit_biaya_realisasi:', biaya.realisasi);
                                    $('#edit_biaya_realisasi').val(biaya.realisasi);
                                    console.log('Realisasi field value after setting:', $('#edit_biaya_realisasi').val());
                                } else {
                                    console.log('No realisasi in biaya record');
                                }
                            } else {
                                console.log('No biaya data available');
                            }
                            
                            // Dokumentasi fields - show existing files as download links
                            console.log('Checking dokumentasi data...');
                            console.log('Dokumentasi data:', data.dokumentasi);
                            
                            if (data.dokumentasi && data.dokumentasi.length > 0) {
                                console.log('Processing dokumentasi fields...');
                                const doc = data.dokumentasi[0]; // First dokumentasi record
                                console.log('Dokumentasi record:', doc);
                                
                                // Proposal
                                if (doc.proposal) {
                                    console.log('Setting proposal file:', doc.proposal);
                                    let proposalLink = `<a href="/storage/dokumen/proposal/${doc.proposal}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.proposal}
                                    </a>`;
                                    $('#current_proposal').html(proposalLink);
                                    console.log('Proposal link set');
                                } else {
                                    console.log('No proposal file available');
                                    $('#current_proposal').html('<span class="text-muted">Tidak ada file</span>');
                                }
                                
                                // Izin Prinsip
                                if (doc.izin_prinsip) {
                                    console.log('Setting izin_prinsip file:', doc.izin_prinsip);
                                    let izinLink = `<a href="/storage/dokumen/izin_prinsip/${doc.izin_prinsip}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.izin_prinsip}
                                    </a>`;
                                    $('#current_izin_prinsip').html(izinLink);
                                    console.log('Izin prinsip link set');
                                } else {
                                    console.log('No izin_prinsip file available');
                                    $('#current_izin_prinsip').html('<span class="text-muted">Tidak ada file</span>');
                                }
                                
                                // Survei Feedback
                                if (doc.survei_feedback) {
                                    console.log('Setting survei_feedback file:', doc.survei_feedback);
                                    let surveiLink = `<a href="/storage/dokumen/survei_feedback/${doc.survei_feedback}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.survei_feedback}
                                    </a>`;
                                    $('#current_survei_feedback').html(surveiLink);
                                    console.log('Survei feedback link set');
                                } else {
                                    console.log('No survei_feedback file available');
                                    $('#current_survei_feedback').html('<span class="text-muted">Tidak ada file</span>');
                                }
                                
                                // Foto
                                if (doc.foto) {
                                    console.log('Setting foto file:', doc.foto);
                                    let fotoLink = `<a href="/storage/dokumen/foto/${doc.foto}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.foto}
                                    </a>`;
                                    $('#current_foto').html(fotoLink);
                                    console.log('Foto link set');
                                } else {
                                    console.log('No foto file available');
                                    $('#current_foto').html('<span class="text-muted">Tidak ada file</span>');
                                }
                            } else {
                                console.log('No dokumentasi data available');
                                $('#current_proposal').html('<span class="text-muted">Tidak ada file</span>');
                                $('#current_izin_prinsip').html('<span class="text-muted">Tidak ada file</span>');
                                $('#current_survei_feedback').html('<span class="text-muted">Tidak ada file</span>');
                                $('#current_foto').html('<span class="text-muted">Tidak ada file</span>');
                            }
                            
                            // Publikasi fields - show all publikasi records
                            console.log('Checking publikasi data...');
                            console.log('Publikasi data:', data.publikasi);
                            
                            // Clear existing values first
                            $('#edit_publikasi_media').val('');
                            $('#edit_publikasi_link').val('');
                            
                            if (data.publikasi && data.publikasi.length > 0) {
                                console.log('Processing publikasi fields...');
                                console.log('All publikasi records:', data.publikasi);
                                
                                // Use first record for form fields
                                const pub = data.publikasi[0];
                                console.log('First publikasi record:', pub);
                                
                                if (pub.media) {
                                    console.log('Setting edit_publikasi_media:', pub.media);
                                    $('#edit_publikasi_media').val(pub.media);
                                    console.log('Media field value after setting:', $('#edit_publikasi_media').val());
                                } else {
                                    console.log('No media data in publikasi record');
                                }
                                
                                if (pub.link) {
                                    console.log('Setting edit_publikasi_link:', pub.link);
                                    $('#edit_publikasi_link').val(pub.link);
                                    console.log('Link field value after setting:', $('#edit_publikasi_link').val());
                                } else {
                                    console.log('No link data in publikasi record');
                                }
                                
                                // Display all publikasi records if display area exists
                                if ($('#publikasi_display_area').length > 0) {
                                    let publikasiList = '<div class="publikasi-list">';
                                    data.publikasi.forEach((pub, index) => {
                                        publikasiList += `<div class="publikasi-item mb-2">
                                            <strong>Publikasi ${index + 1}:</strong><br>
                                            Media: ${pub.media || 'N/A'}<br>
                                            Link: ${pub.link || 'N/A'}
                                        </div>`;
                                    });
                                    publikasiList += '</div>';
                                    $('#publikasi_display_area').html(publikasiList);
                                    console.log('All publikasi records displayed');
                                }
                            } else {
                                console.log('No publikasi data available');
                            }
                            
                            // Feedback fields
                            if (data.feedback && data.feedback.length > 0) {
                                console.log('Setting feedback data:', data.feedback);
                                const feedback = data.feedback[0]; // First feedback record
                                console.log('Feedback record:', feedback);
                                if (feedback.sangat_puas) {
                                    console.log('Setting edit_sangat_puas:', feedback.sangat_puas);
                                    $('#edit_sangat_puas').prop('checked', true);
                                    console.log('Checkbox checked after setting:', $('#edit_sangat_puas').is(':checked'));
                                }
                                if (feedback.puas) {
                                    console.log('Setting edit_puas:', feedback.puas);
                                    $('#edit_puas').prop('checked', true);
                                    console.log('Checkbox checked after setting:', $('#edit_puas').is(':checked'));
                                }
                                if (feedback.kurang_puas) {
                                    console.log('Setting edit_kurang_puas:', feedback.kurang_puas);
                                    $('#edit_kurang_puas').prop('checked', true);
                                    console.log('Checkbox checked after setting:', $('#edit_kurang_puas').is(':checked'));
                                }
                                if (feedback.saran) {
                                    console.log('Setting saran textarea:', feedback.saran);
                                    $('textarea[name="saran"]').val(feedback.saran);
                                    console.log('Textarea value after setting:', $('textarea[name="saran"]').val());
                                }
                            } else {
                                console.log('No feedback data available');
                            }
                            
                            console.log('=== FIELD POPULATION COMPLETED ===');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error loading data:', error);
                        alert('Error loading data: ' + error);
                        $('#editTjslModal').modal('hide');
                    }
                });
            });
        });
    </script>
    
    <!-- TJSL Scripts -->
    <script src="{{ asset('js/tjsl-scripts.js') }}?v={{ time() }}"></script>
@endpush