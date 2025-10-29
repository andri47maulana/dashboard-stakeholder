@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">Detail Program TJSL</h4>
                        {{-- <div>
                            <a href="{{ route('tjsl.edit', $tjsl->id) }}" class="btn btn-warning btn-sm">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="{{ route('tjsl.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Kembali
                            </a>
                        </div> --}}
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Kolom Kiri -->
                            <div class="col-md-6">
                                <!-- Jelajah Kuliner Nusantara -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">{{ $tjsl->nama_program }}</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td width="30%"><strong>Regional</strong></td>
                                                <td>: {{ $tjsl->unit->region ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Kebun/Unit</strong></td>
                                                <td>: {{ $tjsl->unit->unit ?? '-' }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Lokasi Program</strong></td>
                                                <td>: {{ $tjsl->lokasi_program }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal Pelaksanaan</strong></td>
                                                <td>: {{ $tjsl->tanggal_mulai->format('d M Y') }} -
                                                    {{ $tjsl->tanggal_akhir->format('d M Y') }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Penerima Dampak</strong></td>
                                                <td>: {{ $tjsl->penerima_dampak }}</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Sub Pilar</strong></td>
                                                <td>:
                                                    @php
                                                        $subPilarData = $tjsl->sub_pilar;
                                                        if (is_string($subPilarData)) {
                                                            // Try to decode as JSON first
                                                            $decoded = json_decode($subPilarData, true);
                                                            if (
                                                                json_last_error() === JSON_ERROR_NONE &&
                                                                is_array($decoded)
                                                            ) {
                                                                $subPilarNumbers = $decoded;
                                                            } else {
                                                                // If not JSON, try comma-separated values
                                                                $subPilarNumbers = array_map(
                                                                    'trim',
                                                                    explode(',', $subPilarData),
                                                                );
                                                            }
                                                        } elseif (is_array($subPilarData)) {
                                                            $subPilarNumbers = $subPilarData;
                                                        } else {
                                                            $subPilarNumbers = [];
                                                        }

                                                        // Remove quotes and brackets, then join with comma
                                                        $cleanNumbers = array_map(function ($num) {
                                                            return trim(str_replace(['"', '[', ']'], '', $num));
                                                        }, $subPilarNumbers);

                                                        echo implode(', ', $cleanNumbers);
                                                    @endphp
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Status</strong></td>
                                                <td>:
                                                    @if ($tjsl->status == 1)
                                                        <span class="badge badge-primary">Proposed</span>
                                                    @elseif ($tjsl->status == 2)
                                                        <span class="badge badge-warning">Active</span>
                                                    @elseif ($tjsl->status == 3)
                                                        <span class="badge badge-success">Completed</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Deskripsi Program -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white">
                                        <h6 class="mb-0">Deskripsi Program</h6>
                                    </div>
                                    <div class="card-body">
                                        <p class="text-justify">{{ $tjsl->deskripsi }}</p>
                                    </div>
                                </div>

                                <!-- Publikasi Media -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Publikasi Media</h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($tjsl->pubTjsl->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Media</th>
                                                            <th>Link Berita</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tjsl->pubTjsl as $pub)
                                                            <tr>
                                                                <td>{{ $pub->media }}</td>
                                                                <td>
                                                                    @if ($pub->link)
                                                                        <a href="{{ $pub->link }}" target="_blank"
                                                                            class="btn btn-sm btn-primary">
                                                                            <i class="fas fa-external-link-alt"></i>
                                                                        </a>
                                                                    @else
                                                                        -
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">Belum ada publikasi media.</p>
                                        @endif
                                    </div>
                                </div>

                                <!-- Anggaran -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Biaya</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <!-- Baris tabel Perencanaan Anggaran -->
                                            <tr>
                                                <th>Perencanaan Anggaran</th>
                                                <td>
                                                    : @if(isset($hasSubPilarAnggaran) && $hasSubPilarAnggaran)
                                                        Rp. {{ number_format($totalAnggaran, 0, ',', '.') }},-
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Realisasi Anggaran</strong></td>
                                                <td>: Rp.
                                                    {{ number_format($totalRealisasi, 0, ',', '.') }},-
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Persentase Realisasi</strong></td>
                                                <td>: {{ number_format($persentaseRealisasi, 0) }}%</td>
                                            </tr>
                                            {{-- <tr>
                                                <td><strong>Persentase RKA</strong></td>

                                            </tr> --}}
                                        </table>
                                    </div>
                                </div>


                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">

                                <!-- Dokumentasi -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Dokumentasi</h6>
                                    </div>
                                    <div class="card-body">
                                        @php
                                            $docTjsl = $tjsl->docTjsl->first(); // Ambil dokumentasi pertama
                                        @endphp

                                        @if ($docTjsl)
                                            <!-- Foto Section -->
                                            @if ($docTjsl->foto)
                                                <div class="mb-4">
                                                    <h6 class="text-primary mb-2">
                                                        <i class="fas fa-camera"></i> Foto Program
                                                    </h6>
                                                    <div class="photo-container">
                                                        <img src="{{ asset('storage/dokumen/foto/' . $docTjsl->foto) }}"
                                                            alt="Foto Program TJSL"
                                                            class="img-thumbnail documentation-photo">
                                                    </div>
                                                </div>
                                            @endif

                                            <!-- Documents List -->
                                            <div class="documents-section">
                                                <h6 class="text-primary mb-3">
                                                    <i class="fas fa-file-alt"></i> Daftar Dokumentasi
                                                </h6>
                                                <div class="row">
                                                    <!-- Proposal -->
                                                    <div class="col-md-4 mb-3">
                                                        <div class="document-item p-3 border rounded">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <h6 class="mb-1">
                                                                        <i class="fas fa-file-pdf text-danger"></i>
                                                                        Proposal
                                                                    </h6>

                                                                </div>
                                                                <div>
                                                                    @if ($docTjsl->proposal)
                                                                        <a href="{{ asset('storage/dokumen/proposal/' . $docTjsl->proposal) }}"
                                                                            target="_blank" class="btn btn-sm btn-success">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    @else
                                                                        <button class="btn btn-sm btn-secondary" disabled>
                                                                            <i class="fas fa-download"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Izin Prinsip -->
                                                    <div class="col-md-4 mb-3">
                                                        <div class="document-item p-3 border rounded">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <h6 class="mb-1">
                                                                        <i class="fas fa-file-contract text-primary"></i>
                                                                        Izin Prinsip
                                                                    </h6>

                                                                </div>
                                                                <div>
                                                                    @if ($docTjsl->izin_prinsip)
                                                                        <a href="{{ asset('storage/dokumen/izin_prinsip/' . $docTjsl->izin_prinsip) }}"
                                                                            target="_blank" class="btn btn-sm btn-success">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    @else
                                                                        <button class="btn btn-sm btn-secondary" disabled>
                                                                            <i class="fas fa-download"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Survey Feedback -->
                                                    <div class="col-md-4 mb-3">
                                                        <div class="document-item p-3 border rounded">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <h6 class="mb-1">
                                                                        <i class="fas fa-poll text-warning"></i> Survey
                                                                        Feedback
                                                                    </h6>

                                                                </div>
                                                                <div>
                                                                    @if ($docTjsl->survei_feedback)
                                                                        <a href="{{ asset('storage/dokumen/survei_feedback/' . $docTjsl->survei_feedback) }}"
                                                                            target="_blank" class="btn btn-sm btn-success">
                                                                            <i class="fas fa-download"></i>
                                                                        </a>
                                                                    @else
                                                                        <button class="btn btn-sm btn-secondary" disabled>
                                                                            <i class="fas fa-download"></i>
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                                <p class="text-muted">Belum ada dokumentasi yang tersedia.</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>


                                <!-- Feedback Program -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Feedback Program</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Horizontal Emoticon Feedback Display -->
                                            <div class="col-md-3">
                                                <div class="text-center mb-3 p-3 bg-light rounded shadow-sm">
                                                    <h6 class="font-weight-bold mb-3">Rating Kepuasan</h6>

                                                    @if ($feedbackStats['has_feedback'])
                                                        <!-- Horizontal Emoticon Layout -->
                                                        <div class="emoticon-feedback-container">
                                                            <div class="row text-center">
                                                                <!-- Kurang Puas (Red) -->
                                                                <div class="col-4">
                                                                    <div class="emoticon-item">
                                                                        <div class="emoticon-icon mb-2">
                                                                            <i class="fas fa-frown text-danger"
                                                                                style="font-size: 3rem;"></i>
                                                                        </div>
                                                                        <div class="emoticon-checkbox">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                {{ $feedbackStats['kurang_puas'] ? 'checked' : '' }}
                                                                                disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Puas (Yellow) -->
                                                                <div class="col-4">
                                                                    <div class="emoticon-item">
                                                                        <div class="emoticon-icon mb-2">
                                                                            <i class="fas fa-meh text-warning"
                                                                                style="font-size: 3rem;"></i>
                                                                        </div>
                                                                        <div class="emoticon-checkbox">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                {{ $feedbackStats['puas'] ? 'checked' : '' }}
                                                                                disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- Sangat Puas (Green) -->
                                                                <div class="col-4">
                                                                    <div class="emoticon-item">
                                                                        <div class="emoticon-icon mb-2">
                                                                            <i class="fas fa-smile text-success"
                                                                                style="font-size: 3rem;"></i>
                                                                        </div>
                                                                        <div class="emoticon-checkbox">
                                                                            <input type="checkbox"
                                                                                class="form-check-input"
                                                                                {{ $feedbackStats['sangat_puas'] ? 'checked' : '' }}
                                                                                disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <!-- No Feedback State -->
                                                        <div class="no-feedback text-center">
                                                            <div class="emoticon-feedback-container">
                                                                <div class="row text-center">
                                                                    <!-- Kurang Puas (Red) -->
                                                                    <div class="col-4">
                                                                        <div class="emoticon-item">
                                                                            <div class="emoticon-icon mb-2">
                                                                                <i class="fas fa-frown text-muted"
                                                                                    style="font-size: 3rem; opacity: 0.3;"></i>
                                                                            </div>
                                                                            <div class="emoticon-checkbox">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input" disabled>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <!-- Puas (Yellow) -->
                                                                    <div class="col-4">
                                                                        <div class="emoticon-item">
                                                                            <div class="emoticon-icon mb-2">
                                                                                <i class="fas fa-meh text-muted"
                                                                                    style="font-size: 3rem; opacity: 0.3;"></i>
                                                                            </div>
                                                                            <div class="emoticon-checkbox">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input" disabled>
                                                                            </div>

                                                                        </div>
                                                                    </div>

                                                                    <!-- Sangat Puas (Green) -->
                                                                    <div class="col-4">
                                                                        <div class="emoticon-item">
                                                                            <div class="emoticon-icon mb-2">
                                                                                <i class="fas fa-smile text-muted"
                                                                                    style="font-size: 3rem; opacity: 0.3;"></i>
                                                                            </div>
                                                                            <div class="emoticon-checkbox">
                                                                                <input type="checkbox"
                                                                                    class="form-check-input" disabled>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <p class="text-muted mt-3">Belum ada feedback</p>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Saran dan Rekomendasi -->
                                            <div class="col-md-9">
                                                <h6 class="font-weight-bold mb-3">Saran dan Rekomendasi</h6>
                                                <div class="feedback-content">
                                                    @if ($feedbackStats['saran'])
                                                        <p class="text-justify mb-3">
                                                            {{ $feedbackStats['saran'] }}
                                                        </p>
                                                    @else
                                                        <p class="text-justify mb-3 text-muted">
                                                            Belum ada saran dari feedback yang diberikan.
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <!-- Modal untuk menambah biaya -->
@include('tjsl.modals.add-biaya')

<!-- Modal untuk menambah publikasi -->
@include('tjsl.modals.add-publikasi')

<!-- Modal untuk menambah dokumen -->
@include('tjsl.modals.add-dokumen')

<!-- Modal untuk menambah feedback -->
@include('tjsl.modals.add-feedback') --}}

@endsection

<style>
    /* Emoticon Feedback Styles */
    .emoticon-feedback-container {
        padding: 20px 0;
    }

    .emoticon-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 15px 10px;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .emoticon-item:hover {
        background-color: #f8f9fa;
        transform: translateY(-2px);
    }

    .emoticon-icon {
        transition: transform 0.3s ease;
    }

    .emoticon-item:hover .emoticon-icon {
        transform: scale(1.1);
    }

    .emoticon-checkbox {
        margin: 10px 0;
    }

    .emoticon-checkbox input[type="checkbox"] {
        width: 20px;
        height: 20px;
        transform: scale(1.5);
        cursor: not-allowed;
    }

    .emoticon-checkbox input[type="checkbox"]:checked {
        background-color: #007bff;
        border-color: #007bff;
    }

    .emoticon-label {
        font-size: 0.85rem;
        text-align: center;
        min-height: 20px;
    }

    .no-feedback .emoticon-item {
        opacity: 0.6;
    }

    .no-feedback .emoticon-icon i {
        opacity: 0.3 !important;
    }

    /* Documentation Section Styles */
    .documentation-photo {
        max-width: 300px;
        max-height: 200px;
        width: auto;
        height: auto;
        object-fit: cover;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .photo-container {
        text-align: center;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 2px dashed #dee2e6;
    }

    .document-item {
        transition: all 0.3s ease;
        background-color: #fff;
        border: 1px solid #e3e6f0 !important;
    }

    .document-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        border-color: #007bff !important;
    }

    .document-item h6 {
        color: #5a5c69;
        font-weight: 600;
    }

    .document-item .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .documents-section {
        border-top: 1px solid #e3e6f0;
        padding-top: 20px;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .emoticon-icon i {
            font-size: 2.5rem !important;
        }

        .emoticon-item {
            padding: 10px 5px;
        }

        .emoticon-checkbox input[type="checkbox"] {
            transform: scale(1.3);
        }

        .documentation-photo {
            max-width: 250px;
            max-height: 150px;
        }

        .document-item {
            margin-bottom: 15px;
        }
    }
</style>
