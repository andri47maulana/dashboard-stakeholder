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
                    <div class="col-md-2">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-primary btn-sm" id="applyFilter">
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
                    <div class="card border-left-primary shadow py-0"
                        style="height: 380px; display: flex; flex-direction: column;">
                        <!-- Header dengan Background Warna -->
                        <div class="card-header"
                            style="background: #d3d3d3; border: none; padding: 0.75rem 1.25rem; flex-shrink: 0; min-height: 70px; display: flex; align-items: center;">
                            <div class="text-black font-weight-bold"
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
                                            data-id="{{ $tjsl->id }}" title="Edit Program">
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
                        {{ $tjsls->links('pagination::bootstrap-4') }}
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
            // Edit button click handler
            $(document).on('click', '.edit-program-btn', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const tjslId = $(this).data('id');

                // Set form action
                $('#editTjslForm').attr('action', `/tjsl/${tjslId}`);

                // Show modal
                $('#editTjslModal').modal('show');

                // Load data
                $.ajax({
                    url: `/tjsl/${tjslId}/edit-data`,
                    method: 'GET',
                    success: function(response) {

                        // Check if response is the data directly (not wrapped in success/data)
                        const data = response.data ? response.data : response;

                        // Populate form fields
                        if (data && (response.success !== false)) {

                            // Basic fields
                            $('#edit_nama_program').val(data.nama_program || '');
                            $('#edit_deskripsi').val(data.deskripsi || '');
                            $('#edit_status').val(data.status || '').trigger('change');

                            // Pilar field
                            if (data.pilar_id) {
                                $('#edit_pilar_id').val(data.pilar_id).trigger('change');
                            }

                            // Unit field first
                            if (data.unit_id) {
                                $('#edit_unit_id').val(data.unit_id).trigger('change');
                            }

                            // Program unggulan HARUS di-set SEBELUM sub_pilar
                            if (data.program_unggulan_id) {
                                $('#edit_program_unggulan_id').val(data.program_unggulan_id)
                                    .trigger('change');

                                // Set sub_pilar SETELAH program unggulan dengan delay
                                setTimeout(function() {
                                    if (data.sub_pilar && Array.isArray(data
                                            .sub_pilar)) {
                                        console.log(
                                            'Setting sub_pilar after program unggulan:',
                                            data.sub_pilar);
                                        $('#edit_sub_pilar').val(data.sub_pilar)
                                            .trigger('change');
                                    }
                                }, 200); // Increase delay to 200ms
                            } else {
                                // Jika tidak ada program unggulan, set sub_pilar langsung
                                if (data.sub_pilar && Array.isArray(data.sub_pilar)) {
                                    console.log('Setting sub_pilar directly:', data.sub_pilar);
                                    $('#edit_sub_pilar').val(data.sub_pilar).trigger(
                                        'change');
                                }
                            }

                            // Date fields (fix field names and format)
                            if (data.tanggal_mulai) {
                                // Convert ISO date to YYYY-MM-DD format
                                const startDate = new Date(data.tanggal_mulai).toISOString()
                                    .split('T')[0];
                                $('#edit_tanggal_mulai').val(startDate);
                            }
                            // End date (tanggal_akhir) - convert from ISO to YYYY-MM-DD format
                            console.log('Checking tanggal_akhir data...');
                            console.log('Raw tanggal_akhir:', data.tanggal_akhir);

                            if (data.tanggal_akhir) {
                                console.log('Setting tanggal_akhir:', data.tanggal_akhir);
                                // Convert ISO date to YYYY-MM-DD format
                                const endDate = new Date(data.tanggal_akhir).toISOString()
                                    .split('T')[0];
                                console.log('Formatted end date:', endDate);
                                $('#edit_tanggal_akhir').val(endDate);
                                console.log('Tanggal akhir field value after setting:', $(
                                    '#edit_tanggal_akhir').val());
                            } else {
                                console.log('No tanggal_akhir data available');
                                $('#edit_tanggal_akhir').val('');
                            }

                            // Location fields
                            console.log('Setting lokasi_program:', data.lokasi_program);
                            $('#edit_lokasi_program').val(data.lokasi_program || '');

                            console.log('Setting penerima_dampak:', data.penerima_dampak);
                            $('#edit_penerima_dampak').val(data.penerima_dampak || '');

                            // Parse location code to populate separate dropdowns
                            if (data.lokasi_program) {
                                const lokasiCode = data.lokasi_program;
                                console.log('Parsing location code:', lokasiCode);

                                // Extract codes for each administrative level
                                const provinsiCode = lokasiCode.substring(0, 2); // e.g., "32"
                                const kabupatenCode = lokasiCode.substring(0,
                                    5); // e.g., "32.73"
                                const kecamatanCode = lokasiCode.substring(0,
                                    8); // e.g., "32.73.09"
                                const desaCode = lokasiCode; // full code e.g., "32.73.09.1003"

                                console.log('Extracted codes:', {
                                    provinsi: provinsiCode,
                                    kabupaten: kabupatenCode,
                                    kecamatan: kecamatanCode,
                                    desa: desaCode
                                });

                                // Load and set provinsi
                                $.get('/api/provinsi', function(provinsiData) {
                                    $('#edit_lokasi_provinsi').empty().append(
                                        '<option value="">Pilih Provinsi</option>');
                                    provinsiData.forEach(function(prov) {
                                        $('#edit_lokasi_provinsi').append(
                                            `<option value="${prov.id}">${prov.name}</option>`
                                        );
                                    });
                                    $('#edit_lokasi_provinsi').val(provinsiCode);
                                    console.log('Set provinsi:', provinsiCode);

                                    // Load and set kabupaten
                                    if (lokasiCode.length >= 5) {
                                        $.get(`/api/kabupaten/${provinsiCode}`,
                                            function(kabupatenData) {
                                                $('#edit_lokasi_kabupaten').empty()
                                                    .append(
                                                        '<option value="">Pilih Kabupaten</option>'
                                                    );
                                                kabupatenData.forEach(function(
                                                    kab) {
                                                    $('#edit_lokasi_kabupaten')
                                                        .append(
                                                            `<option value="${kab.id}">${kab.name}</option>`
                                                        );
                                                });
                                                $('#edit_lokasi_kabupaten').val(
                                                    kabupatenCode).prop(
                                                    'disabled', false);
                                                console.log('Set kabupaten:',
                                                    kabupatenCode);

                                                // Load and set kecamatan
                                                if (lokasiCode.length >= 8) {
                                                    $.get(`/api/kecamatan/${kabupatenCode}`,
                                                        function(
                                                            kecamatanData) {
                                                            $('#edit_lokasi_kecamatan')
                                                                .empty().append(
                                                                    '<option value="">Pilih Kecamatan</option>'
                                                                );
                                                            kecamatanData
                                                                .forEach(
                                                                    function(
                                                                        kec) {
                                                                        $('#edit_lokasi_kecamatan')
                                                                            .append(
                                                                                `<option value="${kec.id}">${kec.name}</option>`
                                                                            );
                                                                    });
                                                            $('#edit_lokasi_kecamatan')
                                                                .val(
                                                                    kecamatanCode
                                                                ).prop(
                                                                    'disabled',
                                                                    false);
                                                            console.log(
                                                                'Set kecamatan:',
                                                                kecamatanCode
                                                            );

                                                            // Load and set desa
                                                            if (lokasiCode
                                                                .length >= 13) {
                                                                $.get(`/api/desa/${kecamatanCode}`,
                                                                    function(
                                                                        desaData
                                                                    ) {
                                                                        $('#edit_lokasi_desa')
                                                                            .empty()
                                                                            .append(
                                                                                '<option value="">Pilih Desa</option>'
                                                                            );
                                                                        desaData
                                                                            .forEach(
                                                                                function(
                                                                                    desa
                                                                                ) {
                                                                                    $('#edit_lokasi_desa')
                                                                                        .append(
                                                                                            `<option value="${desa.id}">${desa.name}</option>`
                                                                                        );
                                                                                }
                                                                            );
                                                                        $('#edit_lokasi_desa')
                                                                            .val(
                                                                                desaCode
                                                                            )
                                                                            .prop(
                                                                                'disabled',
                                                                                false
                                                                            );
                                                                        console
                                                                            .log(
                                                                                'Set desa:',
                                                                                desaCode
                                                                            );
                                                                    });
                                                            }
                                                        });
                                                }
                                            });
                                    }
                                });
                            } else {
                                console.log('No location code to parse');
                            }

                            // Coordinates
                            console.log('Checking coordinates data...');
                            console.log('Latitude:', data.latitude);
                            console.log('Longitude:', data.longitude);

                            if (data.latitude && data.longitude) {
                                console.log('Setting coordinates:', data.latitude, data
                                    .longitude);
                                $('#edit_latitude').val(data.latitude);
                                $('#edit_longitude').val(data.longitude);
                                $('#edit_koordinat_display').val(data.latitude + ', ' + data
                                    .longitude);
                                $('#edit_koordinat').val(data.latitude + ', ' + data.longitude);
                                console.log('Koordinat display field value after setting:', $(
                                    '#edit_koordinat_display').val());

                                // Store coordinates for later use when map is ready
                                window.editCoordinates = {
                                    lat: parseFloat(data.latitude),
                                    lng: parseFloat(data.longitude)
                                };
                                console.log('Stored coordinates for map:', window
                                    .editCoordinates);

                                // Function to set map coordinates
                                function setEditMapCoordinates() {
                                    if (typeof editMap !== 'undefined' && editMap && window
                                        .editCoordinates) {
                                        console.log('Setting map view and marker to:', window
                                            .editCoordinates);

                                        // Force map to resize first
                                        editMap.invalidateSize();

                                        // Set view
                                        editMap.setView([window.editCoordinates.lat, window
                                            .editCoordinates.lng
                                        ], 15);

                                        // Remove existing marker if any
                                        if (typeof editCurrentMarker !== 'undefined' &&
                                            editCurrentMarker) {
                                            editMap.removeLayer(editCurrentMarker);
                                        }

                                        // Add new marker
                                        editCurrentMarker = L.marker([window.editCoordinates
                                            .lat, window.editCoordinates.lng
                                        ]).addTo(editMap);

                                        console.log('Map coordinates set successfully');
                                        return true;
                                    }
                                    return false;
                                }

                                // Try to set coordinates immediately if map exists
                                if (!setEditMapCoordinates()) {
                                    console.log(
                                        'Map not ready, will set coordinates when modal is shown'
                                    );
                                }
                            } else {
                                console.log('No coordinates data available');
                                window.editCoordinates = null;
                            }


                            if (data.biaya && data.biaya.length > 0) {
                                console.log('Processing biaya fields...');
                                const biaya = data.biaya[0]; // First biaya record
                                console.log('First biaya record:', biaya);

                                if (biaya.sub_pilar_id) {
                                    console.log('Setting edit_biaya_sub_pilar:', biaya
                                        .sub_pilar_id);
                                    $('#edit_biaya_sub_pilar').val(biaya.sub_pilar_id).trigger(
                                        'change');
                                    console.log('Sub pilar field value after setting:', $(
                                        '#edit_biaya_sub_pilar').val());
                                } else {
                                    console.log('No sub_pilar_id in biaya record');
                                }

                                // if (biaya.anggaran) {
                                //     console.log('Setting edit_biaya_anggaran:', biaya.anggaran);
                                //     $('#edit_biaya_anggaran').val(biaya.anggaran);
                                //     console.log('Anggaran field value after setting:', $('#edit_biaya_anggaran').val());
                                // } else {
                                //     console.log('No anggaran in biaya record');
                                // }

                                if (biaya.realisasi) {
                                    $('#edit_biaya_realisasi').val(biaya.realisasi);
                                } else {
                                    console.log('No realisasi in biaya record');
                                }
                            } else {

                            }

                            // Dokumentasi fields - show existing files as download links

                            if (data.dokumentasi && data.dokumentasi.length > 0) {
                                const doc = data.dokumentasi[0]; // First dokumentasi record

                                // Proposal
                                if (doc.proposal) {
                                    let proposalLink = `<a href="/storage/dokumen/proposal/${doc.proposal}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.proposal}
                                    </a>`;
                                    $('#current_proposal').html(proposalLink);
                                } else {
                                    $('#current_proposal').html(
                                        '<span class="text-muted">Tidak ada file</span>');
                                }

                                // Izin Prinsip
                                if (doc.izin_prinsip) {
                                    let izinLink = `<a href="/storage/dokumen/izin_prinsip/${doc.izin_prinsip}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.izin_prinsip}
                                    </a>`;
                                    $('#current_izin_prinsip').html(izinLink);
                                } else {
                                    $('#current_izin_prinsip').html(
                                        '<span class="text-muted">Tidak ada file</span>');
                                }

                                // Survei Feedback
                                if (doc.survei_feedback) {
                                    let surveiLink = `<a href="/storage/dokumen/survei_feedback/${doc.survei_feedback}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.survei_feedback}
                                    </a>`;
                                    $('#current_survei_feedback').html(surveiLink);
                                } else {
                                    $('#current_survei_feedback').html(
                                        '<span class="text-muted">Tidak ada file</span>');
                                }

                                // Foto
                                if (doc.foto) {

                                    let fotoLink = `<a href="/storage/dokumen/foto/${doc.foto}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> ${doc.foto}
                                    </a>`;
                                    $('#current_foto').html(fotoLink);

                                } else {

                                    $('#current_foto').html(
                                        '<span class="text-muted">Tidak ada file</span>');
                                }
                            } else {

                                $('#current_proposal').html(
                                    '<span class="text-muted">Tidak ada file</span>');
                                $('#current_izin_prinsip').html(
                                    '<span class="text-muted">Tidak ada file</span>');
                                $('#current_survei_feedback').html(
                                    '<span class="text-muted">Tidak ada file</span>');
                                $('#current_foto').html(
                                    '<span class="text-muted">Tidak ada file</span>');
                            }


                            // Clear existing publikasi container
                            $('#editPublikasiContainer').empty();

                            if (data.publikasi && data.publikasi.length > 0) {
                                console.log('Setting publikasi data:', data.publikasi);

                                // Add all publikasi records to the form
                                data.publikasi.forEach((pub, index) => {
                                    const publikasiHtml = `
                                        <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                                            <div class="row">
                                                <div class="col-md-4">
                                                    <label class="form-label">Media</label>
                                                    <input type="text" class="form-control" name="publikasi[${index}][media]"
                                                           value="${pub.media || ''}" placeholder="Nama Media">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label">Link</label>
                                                    <input type="url" class="form-control" name="publikasi[${index}][link]"
                                                           value="${pub.link || ''}" placeholder="https://...">
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-edit-publikasi"
                                                            ${data.publikasi.length === 1 ? 'disabled' : ''}>
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                    $('#editPublikasiContainer').append(publikasiHtml);
                                });

                                // Update the global index for new items
                                window.editPublikasiIndex = data.publikasi.length;

                                console.log(
                                    `Loaded ${data.publikasi.length} publikasi records`);
                            } else {
                                // Add one empty publikasi item if no data
                                const publikasiHtml = `
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
                                                <button type="button" class="btn btn-danger btn-sm remove-edit-publikasi" disabled>
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                `;
                                $('#editPublikasiContainer').append(publikasiHtml);
                                window.editPublikasiIndex = 1;
                                console.log('No publikasi data available - added empty form');
                            }

                            // Feedback fields
                            if (data.feedback && data.feedback.length > 0) {
                                console.log('Setting feedback data:', data.feedback);
                                const feedback = data.feedback[0]; // First feedback record
                                console.log('Feedback record:', feedback);
                                if (feedback.sangat_puas) {
                                    console.log('Setting edit_sangat_puas:', feedback
                                        .sangat_puas);
                                    $('#edit_sangat_puas').prop('checked', true);
                                    console.log('Checkbox checked after setting:', $(
                                        '#edit_sangat_puas').is(':checked'));
                                }
                                if (feedback.puas) {
                                    console.log('Setting edit_puas:', feedback.puas);
                                    $('#edit_puas').prop('checked', true);
                                    console.log('Checkbox checked after setting:', $(
                                        '#edit_puas').is(':checked'));
                                }
                                if (feedback.kurang_puas) {
                                    console.log('Setting edit_kurang_puas:', feedback
                                        .kurang_puas);
                                    $('#edit_kurang_puas').prop('checked', true);
                                    console.log('Checkbox checked after setting:', $(
                                        '#edit_kurang_puas').is(':checked'));
                                }
                                if (feedback.saran) {
                                    console.log('Setting saran textarea:', feedback.saran);
                                    $('textarea[name="saran"]').val(feedback.saran);
                                    console.log('Textarea value after setting:', $(
                                        'textarea[name="saran"]').val());
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

            // Handler untuk form submission edit modal
            $('#editTjslForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                console.log('=== FORM SUBMISSION STARTED ===');

                // Clear any previous validation states
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                // Compose lokasi_program before submitting
                const provKode = $('#edit_lokasi_provinsi').val() || '';
                const kabKode = $('#edit_lokasi_kabupaten').val() || '';
                const kecKode = $('#edit_lokasi_kecamatan').val() || '';
                const desaKode = $('#edit_lokasi_desa').val() || '';

                console.log('Location values:', {
                    provKode,
                    kabKode,
                    kecKode,
                    desaKode
                });

                // Susun kode lokasi dalam format: NN.NN.NN.NNNN (provinsi.kabupaten.kecamatan.desa)
                if (desaKode) {
                    $('#edit_lokasi_program').val(
                    desaKode); // Kode desa sudah lengkap (format: NN.NN.NN.NNNN)
                } else if (kecKode) {
                    $('#edit_lokasi_program').val(kecKode); // Kode kecamatan (format: NN.NN.NN)
                } else if (kabKode) {
                    $('#edit_lokasi_program').val(kabKode); // Kode kabupaten (format: NN.NN)
                } else if (provKode) {
                    $('#edit_lokasi_program').val(provKode); // Kode provinsi (format: NN)
                } else {
                    $('#edit_lokasi_program').val('');
                }

                console.log('Final lokasi_program before submit:', $('#edit_lokasi_program').val());

                // Validate required fields
                let isValid = true;
                const requiredFields = ['edit_nama_program', 'edit_unit_id', 'edit_pilar_id'];

                requiredFields.forEach(fieldId => {
                    const field = $('#' + fieldId);
                    const value = field.val();

                    if (!value || value === '') {
                        console.log('Required field is empty:', fieldId, 'Value:', value);
                        field.addClass('is-invalid');
                        isValid = false;
                    }
                });

                // Special validation for Select2 fields
                const select2Fields = ['#edit_unit_id', '#edit_pilar_id'];
                select2Fields.forEach(selector => {
                    const field = $(selector);
                    if (field.hasClass('select2-hidden-accessible')) {
                        const value = field.val();
                        if (!value || value === '') {
                            console.log('Select2 field is empty:', selector);
                            field.next('.select2-container').addClass('is-invalid');
                            isValid = false;
                        }
                    }
                });

                if (!isValid) {
                    console.log('Form validation failed');
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal',
                        text: 'Mohon lengkapi semua field yang wajib diisi',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                console.log('Form validation passed, proceeding with submission...');

                var formData = new FormData(this);
                var tjslId = $('#editTjslForm').attr('action').split('/').pop();

                // Show loading state
                $('#editSubmitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Updating...');

                $.ajax({
                    url: $('#editTjslForm').attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Close modal
                        $('#editTjslModal').modal('hide');

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Program TJSL berhasil diperbarui',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to index page
                            window.location.href = "{{ route('tjsl.index') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Update error:', xhr.responseText);
                        var errorMessage = 'Terjadi kesalahan saat mengupdate data';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            var errorList = [];
                            for (var field in errors) {
                                errorList.push(errors[field][0]);
                            }
                            errorMessage = errorList.join('\n');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    },
                    complete: function() {
                        // Reset button state
                        $('#editSubmitBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Update Program TJSL');
                    }
                });
            });

            // Handler untuk form submission create modal
            $('#tjslForm').on('submit', function(e) {
                e.preventDefault(); // Prevent default form submission

                console.log('=== CREATE FORM SUBMISSION STARTED ===');

                // Clear any previous validation states
                $('.is-invalid').removeClass('is-invalid');
                $('.invalid-feedback').hide();

                // Compose lokasi_program before submitting
                const provKode = $('#lokasi_provinsi').val() || '';
                const kabKode = $('#lokasi_kabupaten').val() || '';
                const kecKode = $('#lokasi_kecamatan').val() || '';
                const desaKode = $('#lokasi_desa').val() || '';

                console.log('Location values:', {
                    provKode,
                    kabKode,
                    kecKode,
                    desaKode
                });

                // Set lokasi_program based on the most specific location selected
                if (desaKode) {
                    $('#lokasi_program').val(desaKode);
                } else if (kecKode) {
                    $('#lokasi_program').val(kecKode);
                } else if (kabKode) {
                    $('#lokasi_program').val(kabKode);
                } else if (provKode) {
                    $('#lokasi_program').val(provKode);
                } else {
                    $('#lokasi_program').val('');
                }

                console.log('Final lokasi_program:', $('#lokasi_program').val());

                // Validate required fields
                let isValid = true;
                let invalidFields = [];

                // Validate nama_program
                if (!$('#nama_program').val().trim()) {
                    $('#nama_program').addClass('is-invalid');
                    invalidFields.push('Nama Program');
                    isValid = false;
                } else {
                    $('#nama_program').removeClass('is-invalid');
                }

                // Validate unit_id
                if (!$('#unit_id').val()) {
                    $('#unit_id').addClass('is-invalid');
                    invalidFields.push('Unit/Kebun');
                    isValid = false;
                } else {
                    $('#unit_id').removeClass('is-invalid');
                }

                // Validate pilar_id
                if (!$('#pilar_id').val()) {
                    $('#pilar_id').addClass('is-invalid');
                    invalidFields.push('Pilar');
                    isValid = false;
                } else {
                    $('#pilar_id').removeClass('is-invalid');
                }

                console.log('Validation result:', {
                    isValid,
                    invalidFields
                });

                if (!isValid) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Validasi Gagal!',
                        text: 'Field berikut wajib diisi: ' + invalidFields.join(', ')
                    });
                    return false;
                }

                // Prepare form data
                var formData = new FormData(this);

                // Show loading state
                $('#submitBtn').prop('disabled', true).html(
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        // Close modal
                        $('#tjslModal').modal('hide');

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: 'Program TJSL berhasil disimpan',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to index page
                            window.location.href = "{{ route('tjsl.index') }}";
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error('Create error:', xhr.responseText);
                        var errorMessage = 'Terjadi kesalahan saat menyimpan data';

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errors = xhr.responseJSON.errors;
                            var errorList = [];
                            for (var field in errors) {
                                errorList.push(errors[field][0]);
                            }
                            errorMessage = errorList.join('\n');
                        }

                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: errorMessage
                        });
                    },
                    complete: function() {
                        // Reset button state
                        $('#submitBtn').prop('disabled', false).html(
                            '<i class="fas fa-save"></i> Simpan Program');
                    }
                });
            });
        });
    </script>

    <!-- TJSL Scripts -->
    <script src="{{ asset('js/tjsl-scripts.js') }}?v={{ time() }}"></script>

    <!-- Filter Scripts -->
    <script>
        $(document).ready(function() {
            // Apply Filter Button
            $('#applyFilter').click(function() {
                const params = new URLSearchParams();

                const region = $('#filterRegion').val();
                const pilarId = $('#filterPilar').val();
                const unitId = $('#filterKebun').val();
                const tahun = $('#filterTahun').val();

                if (region) params.append('region', region);
                if (pilarId) params.append('pilar_id', pilarId);
                if (unitId) params.append('unit_id', unitId);
                if (tahun) params.append('tahun', tahun);

                const url = '{{ route('tjsl.index') }}' + (params.toString() ? '?' + params.toString() :
                    '');
                window.location.href = url;
            });

            // Reset Filter Button
            $('#resetFilter').click(function() {
                window.location.href = '{{ route('tjsl.index') }}';
            });
        });
    </script>
@endpush
