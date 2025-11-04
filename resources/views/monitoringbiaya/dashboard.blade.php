@extends('layouts.app')

@section('title', 'Dashboard Monitoring Biaya')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card shadow mb-4">
                    <div class="card-body">
                        <form method="GET" action="{{ route('monitoringbiaya.dashboard') }}">
                            <div class="row align-items-end">
                                <div class="col-md-2">
                                    <label class="form-label small text-muted">Tahun</label>
                                    <select name="tahun" class="form-control form-control-sm">
                                        <option value="">Semua Tahun</option>
                                        @foreach ($years ?? [] as $y)
                                            <option value="{{ $y }}"
                                                {{ (string) request('tahun') === (string) $y ? 'selected' : '' }}>
                                                {{ $y }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label small text-muted">Sub Pilar</label>
                                    <select name="sub_pilar_id" class="form-control form-control-sm">
                                        <option value="">Semua Sub Pilar</option>
                                        @foreach ($subPilars ?? collect() as $sp)
                                            <option value="{{ $sp->id }}"
                                                {{ (string) request('sub_pilar_id') === (string) $sp->id ? 'selected' : '' }}>
                                                {{ $sp->id }} - {{ $sp->sub_pilar }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="form-label small text-muted">Regional</label>
                                    <select name="region" class="form-control form-control-sm">
                                        <option value="">Semua Regional</option>
                                        @foreach ($regions ?? collect() as $region)
                                            <option value="{{ $region }}"
                                                {{ request('region') === $region ? 'selected' : '' }}>
                                                {{ $region }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <div class="d-flex gap-2">
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="fas fa-filter"></i> Filter
                                        </button>
                                        <a href="{{ route('monitoringbiaya.dashboard') }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-undo"></i> Reset
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-primary h-100">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Total Anggaran</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalAnggaran ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-success h-100">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Total Realisasi</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    Rp {{ number_format($totalRealisasi ?? 0, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card border-left-info h-100">
                            <div class="card-body">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Persentase</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ isset($pctSummary) && $pctSummary !== null ? number_format($pctSummary, 2, ',', '.') . '%' : '-' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Chart: Anggaran vs Realisasi per Sub Pilar --}}
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Anggaran vs Realisasi per Sub Pilar</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartSubPilar" height="360"></canvas>
                    </div>
                </div>

                <div id="accordionSubPilar">
                    <div class="card shadow mb-4">
                        <a class="card-header d-flex align-items-center" href="#collapseSubPilar" data-toggle="collapse"
                            aria-expanded="true" aria-controls="collapseSubPilar">
                            <h3 class="card-title mb-0 flex-grow-1">Rekap per Sub Pilar</h3>
                            <span class="small text-secondary ml-2">Klik untuk buka/tutup</span>
                        </a>
                        <div id="collapseSubPilar" class="collapse show" data-parent="#accordionSubPilar">
                            <div class="card-body">
                                <div class="table-responsive">
                                    {{-- Rekap per Sub Pilar --}}
                                    <table id="tableSubPilar" class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th class="text-left">Sub Pilar</th>
                                                <th class="text-right text-end">Anggaran</th>
                                                <th class="text-right text-end">Realisasi</th>
                                                <th class="text-end">%</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($datasetSubPilar ?? collect() as $row)
                                                <tr>
                                                    <td class="text-left">{{ $row['sub_pilar_id'] }} -
                                                        {{ $row['sub_pilar_name'] }}</td>
                                                    <td class="text-right">Rp
                                                        {{ number_format($row['anggaran_total'], 0, ',', '.') }}</td>
                                                    <td class="text-right">Rp
                                                        {{ number_format($row['realisasi_total'], 0, ',', '.') }}</td>
                                                    <td class="text-end">
                                                        {{ $row['pct'] !== null ? number_format($row['pct'], 2, ',', '.') . '%' : '-' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                            @if (($datasetSubPilar ?? collect())->isEmpty())
                                                <tr>
                                                    <td colspan="4" class="text-center">Tidak ada data</td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- Chart: Anggaran vs Realisasi per Regional --}}
                <div class="card shadow mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Chart Anggaran vs Realisasi per Regional</h3>
                    </div>
                    <div class="card-body">
                        <canvas id="chartRegional" height="360"></canvas>
                    </div>
                </div>

                <div class="card shadow mb-4">
                    <div id="accordionRegion">
                        <div class="card shadow mb-4">
                            <a class="card-header d-flex align-items-center" href="#collapseRegion" data-toggle="collapse"
                                aria-expanded="true" aria-controls="collapseRegion">
                                <h3 class="card-title mb-0 flex-grow-1">Rekap per Regional</h3>
                                <span class="small text-secondary ml-2">Klik untuk buka/tutup</span>
                            </a>
                            <div id="collapseRegion" class="collapse show" data-parent="#accordionRegion">
                                <div class="card-body">
                                    <div class="table-responsive">
                                        {{-- Rekap per Regional --}}
                                        <table id="tableRegion" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-left">Regional</th>
                                                    <th class="text-right text-end">Anggaran</th>
                                                    <th class="text-right text-end">Realisasi</th>
                                                    <th class="text-end">%</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($datasetRegion ?? collect() as $row)
                                                    <tr>
                                                        <td class="text-left">{{ $row['regional_name'] }}</td>
                                                        <td class="text-right text-end">Rp
                                                            {{ number_format($row['anggaran_total'], 0, ',', '.') }}</td>
                                                        <td class="text-right text-end">Rp
                                                            {{ number_format($row['realisasi_total'], 0, ',', '.') }}</td>
                                                        <td class="text-end">
                                                            {{ $row['pct'] !== null ? number_format($row['pct'], 2, ',', '.') . '%' : '-' }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                @if (($datasetRegion ?? collect())->isEmpty())
                                                    <tr>
                                                        <td colspan="4" class="text-center">Tidak ada data</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Dukungan BS4 dan BS5 */
        #tableSubPilar th.text-right,
        #tableSubPilar td.text-right,
        #tableRegion th.text-right,
        #tableRegion td.text-right,
        #tableSubPilar th.text-end,
        #tableSubPilar td.text-end,
        #tableRegion th.text-end,
        #tableRegion td.text-end {
            text-align: right !important;
        }

        #tableSubPilar th.text-left,
        #tableSubPilar td.text-left,
        #tableRegion th.text-left,
        #tableRegion td.text-left {
            text-align: left !important;
        }

        /* Fallback hardening jika kelas tidak terpasang oleh DataTables */
        #tableSubPilar tbody td:nth-child(2),
        #tableSubPilar tbody td:nth-child(3),
        #tableSubPilar tbody td:nth-child(4),
        #tableRegion tbody td:nth-child(2),
        #tableRegion tbody td:nth-child(3),
        #tableRegion tbody td:nth-child(4) {
            text-align: right !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <script>
        $(function() {

            // Utilitas formatter (harus berada sebelum inisialisasi chart)
            const formatNumber = (n) => Number(n).toLocaleString('id-ID');

            // DataTables tetap
            if ($.fn.DataTable) {
                $('#tableSubPilar').DataTable({
                    pageLength: 10,
                    order: [
                        [0, 'asc']
                    ],
                    columnDefs: [{
                            targets: 0,
                            type: 'num'
                        } // pastikan sorting numerik
                    ]
                });
                $('#tableRegion').DataTable({
                    pageLength: 10,
                    order: [
                        [0, 'asc']
                    ]
                });
            }

            // ---------- Chart Sub Pilar ----------
            const subPilarRaw = @json($datasetSubPilar ?? []);
            if (subPilarRaw.length && window.Chart) {
                const subLabels = subPilarRaw.map(r => `${r.sub_pilar_id}`);
                const subNames = subPilarRaw.map(r => r.sub_pilar_name || `Sub Pilar ${r.sub_pilar_id}`);
                const subAnggaran = subPilarRaw.map(r => Number(r.anggaran_total));
                const subRealisasi = subPilarRaw.map(r => Number(r.realisasi_total));
                // Formatter lokal agar pasti tersedia
                const fmt = (n) => Number(n).toLocaleString('id-ID');

                const iconBase = "{{ asset('img/sub_pilar') }}";
                const subIcons = subPilarRaw.map(r => `${iconBase}/${Number(r.sub_pilar_id)}.png`);

                const xAxisImagePlugin = {
                    id: 'xAxisImagePlugin',
                    beforeLayout(chart, args, opts) {
                        const pad = (opts && typeof opts.paddingBottom === 'number') ? opts.paddingBottom : 40;
                        chart.options.layout = chart.options.layout || {};
                        const existing = chart.options.layout.padding || {};
                        chart.options.layout.padding = {
                            ...existing,
                            bottom: Math.max(existing.bottom || 0, pad)
                        };
                    },
                    afterDraw(chart, args, opts) {
                        const ctx = chart.ctx;
                        const area = chart.chartArea;
                        const xScale = (chart.scales && (chart.scales.x || chart.scales['x-axis-0']));
                        if (!xScale) return;

                        const size = (opts && typeof opts.size === 'number') ? opts.size : 40;
                        const offsetY = (opts && typeof opts.offsetY === 'number') ? opts.offsetY :
                            60; // JARAK LEBIH BESAR DARI CHART
                        const images = (opts && Array.isArray(opts.images) && opts.images.length) ? opts
                            .images : subIcons;

                        if (!chart.$xAxisImageCache) {
                            chart.$xAxisImageCache = images.map(src => {
                                const img = new Image();
                                img.src = src;
                                img.onload = () => chart.draw();
                                return img;
                            });
                        }

                        const ticksCount = subLabels.length;
                        for (let i = 0; i < ticksCount; i++) {
                            const x = xScale.getPixelForTick ? xScale.getPixelForTick(i) : xScale
                                .getPixelForValue(i);
                            const img = chart.$xAxisImageCache[i];
                            if (!img || !img.complete || !img.naturalWidth) continue;

                            const y = area.bottom + offsetY;
                            ctx.save();
                            ctx.drawImage(img, x - (size / 2), y - size, size, size);
                            ctx.restore();
                        }
                    }
                };

                const ctxSub = document.getElementById('chartSubPilar').getContext('2d');
                new Chart(ctxSub, {
                    type: 'bar',
                    data: {
                        labels: subLabels,
                        datasets: [{
                                label: 'Anggaran',
                                data: subAnggaran,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Realisasi',
                                data: subRealisasi,
                                backgroundColor: 'rgba(75, 192, 192, 0.5)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                displayColors: false,
                                callbacks: {
                                    title: (items) => {
                                        const item = Array.isArray(items) ? items[0] : items;
                                        const idx = item?.dataIndex ?? item?.index ?? (typeof item
                                            ?.parsed?.x === 'number' ? item.parsed.x : 0);
                                        return subNames[idx] ??
                                            `Sub Pilar ${subLabels[idx] ?? (idx + 1)}`;
                                    },
                                    label: (context) => {
                                        const datasetLabel = context.dataset?.label || '';
                                        return `${datasetLabel}: ${fmt(context.raw)}`;
                                    }
                                }
                            },
                            // Opsi untuk plugin ikon
                            xAxisImagePlugin: {
                                images: subIcons,
                                size: 40,
                                paddingBottom: 100,
                                offsetY: 60
                            }
                        },
                        // Fallback Chart.js v2 (tooltips + yAxes/xAxes)
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                title: function(items, data) {
                                    const first = Array.isArray(items) ? items[0] : items;
                                    const idx = first.index;
                                    return subNames[idx] || ('Sub Pilar ' + (subLabels[idx] || (idx +
                                        1)));
                                },
                                label: function(tooltipItem, data) {
                                    const ds = data?.datasets?.[tooltipItem.datasetIndex];
                                    const label = ds?.label || '';
                                    const val = tooltipItem.yLabel ?? tooltipItem.value;
                                    return `${label}: ${fmt(val)}`;
                                }
                            }
                        },
                        scales: {
                            // v3+
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => fmt(value)
                                }
                            },
                            x: {
                                ticks: {
                                    display: true,
                                    padding: 16
                                }
                            },
                            // v2 fallback
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: function(value) {
                                        return fmt(value);
                                    }
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    display: true,
                                    padding: 16
                                }
                            }]
                        }
                    },
                    plugins: [xAxisImagePlugin]
                });
            }



            // ---------- Chart Regional ----------
            const regionalRaw = @json($datasetRegion ?? []);
            if (regionalRaw.length && window.Chart) {
                const regLabels = regionalRaw.map(r => r.regional_name);
                const regAnggaran = regionalRaw.map(r => Number(r.anggaran_total));
                const regRealisasi = regionalRaw.map(r => Number(r.realisasi_total));

                const ctxReg = document.getElementById('chartRegional').getContext('2d');
                new Chart(ctxReg, {
                    type: 'bar',
                    data: {
                        labels: regLabels,
                        datasets: [{
                                label: 'Anggaran',
                                data: regAnggaran,
                                backgroundColor: 'rgba(255, 159, 64, 0.5)',
                                borderColor: 'rgba(255, 159, 64, 1)',
                                borderWidth: 1
                            },
                            {
                                label: 'Realisasi',
                                data: regRealisasi,
                                backgroundColor: 'rgba(153, 102, 255, 0.5)',
                                borderColor: 'rgba(153, 102, 255, 1)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            },
                            // v3+
                            tooltip: {
                                mode: 'index',
                                intersect: false,
                                callbacks: {
                                    label: (context) => {
                                        const datasetLabel = context.dataset?.label || '';
                                        return `${datasetLabel}: ${formatNumber(context.raw)}`;
                                    }
                                }
                            }
                        },
                        // v2 fallback
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                            callbacks: {
                                label: function(tooltipItem, data) {
                                    const ds = data?.datasets?.[tooltipItem.datasetIndex];
                                    const label = ds?.label || '';
                                    const val = tooltipItem.yLabel ?? tooltipItem.value;
                                    return `${label}: ${formatNumber(val)}`;
                                }
                            }
                        },
                        scales: {
                            // v3+
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    callback: (value) => formatNumber(value)
                                }
                            },
                            // v2 fallback
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    callback: (value) => formatNumber(value)
                                }
                            }]
                        }
                    }
                });
            }
        });
    </script>
@endpush
