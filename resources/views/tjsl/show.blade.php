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
                                                <td><strong>TPB</strong></td>
                                                <td>: {{ $tjsl->tpb }}</td>
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
                            </div>

                            <!-- Kolom Kanan -->
                            <div class="col-md-6">
                                <!-- Anggaran -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Anggaran</h6>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-borderless table-sm">
                                            <tr>
                                                <td><strong>Perencanaan Anggaran</strong></td>
                                                <td>: Rp. {{ number_format($totalAnggaran, 0, ',', '.') }},-</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Realisasi Anggaran</strong></td>
                                                <td>: Rp.
                                                    {{ number_format($totalAnggaran * ($persentaseRealisasi / 100), 0, ',', '.') }},-
                                                </td>
                                            </tr>
                                            <tr>
                                                <td><strong>Persentase Realisasi</strong></td>
                                                <td>: {{ number_format($persentaseRealisasi, 0) }}%</td>
                                            </tr>
                                            <tr>
                                                <td><strong>Persentase RKA</strong></td>
                                                <td>:
                                                    {{ $persentaseRka > 0 ? number_format($persentaseRka, 0) . '%' : '-' }}
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                <!-- Feedback Program -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Feedback Program</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <!-- Donut Chart -->
                                            <div class="col-md-6">
                                                <div class="text-center mb-3">
                                                    <div class="d-flex justify-content-center mb-2">
                                                        <span class="badge badge-success mr-2">● Sangat Puas</span>
                                                        <span class="badge badge-warning mr-2">● Puas</span>
                                                        <span class="badge badge-danger">● Kurang Puas</span>
                                                    </div>
                                                    <div class="d-flex justify-content-center mb-2">
                                                        <canvas id="feedbackChart" width="200" height="200"></canvas>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Saran dan Rekomendasi -->
                                            <div class="col-md-6">
                                                <h6 class="font-weight-bold mb-3">Saran dan Rekomendasi</h6>
                                                <div class="feedback-content">
                                                    @if ($feedbackStats['saran'])
                                                        <p class="text-justify mb-3">
                                                            {{ $feedbackStats['saran'] }}
                                                        </p>
                                                    @else
                                                        <p class="text-justify mb-3">
                                                            Belum ada saran dari feedback yang diberikan.
                                                        </p>
                                                    @endif

                                                    <div class="mt-3">
                                                        <strong>SROI Ratio</strong>
                                                        <span class="float-right">
                                                            :
                                                            {{ $feedbackStats['rating_avg'] > 0 ? number_format($feedbackStats['rating_avg'], 1) : '-' }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                                <!-- Dokumentasi -->
                                <div class="card mb-3">
                                    <div class="card-header bg-info text-white d-flex justify-content-between">
                                        <h6 class="mb-0">Dokumentasi</h6>
                                    </div>
                                    <div class="card-body">
                                        @if ($tjsl->docTjsl->count() > 0)
                                            <div class="table-responsive">
                                                <table class="table table-sm">
                                                    <thead>
                                                        <tr>
                                                            <th>Media</th>
                                                            <th>File</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($tjsl->docTjsl as $doc)
                                                            <tr>
                                                                <td>{{ $doc->nama_dokumen }}</td>
                                                                <td>
                                                                    <a href="{{ $doc->link }}" target="_blank"
                                                                        class="btn btn-sm btn-primary">
                                                                        <i class="fas fa-download"></i>
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted">Belum ada dokumentasi.</p>
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

    {{-- <!-- Modal untuk menambah biaya -->
@include('tjsl.modals.add-biaya')

<!-- Modal untuk menambah publikasi -->
@include('tjsl.modals.add-publikasi')

<!-- Modal untuk menambah dokumen -->
@include('tjsl.modals.add-dokumen')

<!-- Modal untuk menambah feedback -->
@include('tjsl.modals.add-feedback') --}}

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing chart...');

        // Check if canvas exists
        const canvas = document.getElementById('feedbackChart');
        if (!canvas) {
            console.error('Canvas element with id "feedbackChart" not found');
            return;
        }

        console.log('Canvas found, creating chart...');

        // Data untuk donut chart
        const sangat_puas = {{ $feedbackStats['sangat_puas'] ?? 0 }};
        const puas = {{ $feedbackStats['puas'] ?? 0 }};
        const kurang_puas = {{ $feedbackStats['kurang_puas'] ?? 0 }};
        const total = sangat_puas + puas + kurang_puas;

        console.log('Data:', {
            sangat_puas,
            puas,
            kurang_puas,
            total
        });

        let chartData, chartColors;

        if (total === 0) {
            // Data dummy untuk chart kosong
            chartData = [1, 1, 1];
            chartColors = ['#e9ecef', '#e9ecef', '#e9ecef'];
        } else {
            chartData = [sangat_puas, puas, kurang_puas];
            chartColors = ['#28a745', '#ffc107', '#dc3545'];
        }

        const feedbackData = {
            labels: ['Sangat Puas', 'Puas', 'Kurang Puas'],
            datasets: [{
                data: chartData,
                backgroundColor: chartColors,
                borderWidth: 2,
                borderColor: '#fff'
            }]
        };

        // Konfigurasi chart
        const config = {
            type: 'doughnut',
            data: feedbackData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (total === 0) {
                                    return 'Belum ada data feedback';
                                }
                                const percentage = Math.round((context.parsed / total) * 100);
                                return context.label + ': ' + context.parsed + ' (' + percentage + '%)';
                            }
                        }
                    }
                },
                cutout: '60%'
            }
        };

        // Render chart
        try {
            const ctx = canvas.getContext('2d');
            new Chart(ctx, config);
            console.log('Chart created successfully');
        } catch (error) {
            console.error('Error creating chart:', error);
        }
    });
</script>

<style>
    .feedback-content {
        font-size: 0.9rem;
        line-height: 1.5;
    }

    #feedbackChart {
        max-width: 200px;
        max-height: 200px;
    }

    .badge {
        font-size: 0.75rem;
    }
</style>
