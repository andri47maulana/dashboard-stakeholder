@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h4 class="mb-3">Tanggung Jawab Sosial dan Lingkungan</h4>

        <!-- Cards -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <small class="text-muted">PROGRAM</small>
                        <h3 class="mt-2">{{ number_format($programCount) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <small class="text-muted">ACTUAL SPEND</small>
                        <h3 class="mt-2">Rp{{ number_format($actualSpend, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow h-100">
                    <div class="card-body">
                        <small class="text-muted">PENERIMA MANFAAT</small>
                        <h3 class="mt-2">{{ number_format($penerimaManfaat) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts row -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header">Anggaran 2025</div>
                    <div class="card-body d-flex flex-column">
                        <div style="height: 300px;">
                            <canvas id="donutAnggaran"></canvas>
                        </div>
                        {{-- <div class="mt-auto pt-2">
                            <div class="mt-2">
                                <span class="badge bg-secondary">Proposed {{ $statusSummary['proposed'] }}</span>
                                <span class="badge bg-warning text-dark">Active {{ $statusSummary['active'] }}</span>
                                <span class="badge bg-success">Completed {{ $statusSummary['completed'] }}</span>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header">Actual Spend by Pilar</div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="barSpendPilar"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header">Jumlah Program (Regional)</div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="pieRegional"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow mb-4 h-100" id="provinceCard">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>Berdasarkan Provinsi</span>
                        <small class="text-muted" id="provincePageInfo">Hal {{ $provincePage }} dari
                            {{ $totalPages }}</small>
                    </div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Provinsi</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody id="provinceTableBody">
                                @foreach ($byProvince as $row)
                                    <tr>
                                        <td>{{ $row['provinsi'] }}</td>
                                        <td class="text-end">{{ $row['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <th>Total ({{ $totalProvinces }} provinsi)</th>
                                    <th class="text-end">{{ number_format($totalProgramsInProvinces) }}</th>
                                </tr>
                            </tfoot> --}}
                        </table>

                        <!-- Pagination Controls -->
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <button class="btn btn-sm btn-outline-primary" id="provincePrevBtn"
                                onclick="changeProvincePage({{ $provincePage - 1 }})"
                                {{ $provincePage <= 1 ? 'disabled' : '' }}>
                                <i class="fas fa-chevron-left"></i> Prev
                            </button>

                            <small class="text-muted" id="provinceRangeInfo">
                                {{ ($provincePage - 1) * 5 + 1 }}-{{ min($provincePage * 5, $totalProvinces) }} dari
                                {{ $totalProvinces }}
                            </small>

                            <button class="btn btn-sm btn-outline-primary" id="provinceNextBtn"
                                onclick="changeProvincePage({{ $provincePage + 1 }})"
                                {{ $provincePage >= $totalPages ? 'disabled' : '' }}>
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-footer">
                        <strong id="provinceTotalInfo">Total: {{ number_format($totalProgramsInProvinces) }}
                            program</strong>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header">Berdasarkan Pilar</div>
                    <div class="card-body">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Pilar</th>
                                    <th class="text-end">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($byPilar as $row)
                                    <tr>
                                        <td>{{ $row['pilar'] }}</td>
                                        <td class="text-end">{{ $row['total'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            {{-- <tfoot>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-end">{{ number_format($byPilar->sum('total')) }}</th>
                                </tr>
                            </tfoot> --}}
                        </table>
                    </div>
                    <div class="card-footer">
                        <strong id="pilarTotalInfo">Total: {{ number_format($byPilar->sum('total')) }}
                            program</strong>
                    </div>
                </div>
            </div>

            {{-- <div class="col-md-4">
                <div class="card shadow mb-4 h-100">
                    <div class="card-header">Employee Participation</div>
                    <div class="card-body">
                        <div style="height: 300px;">
                            <canvas id="lineParticipation"></canvas>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

    </div>
@endsection

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Donut Anggaran
        new Chart(document.getElementById('donutAnggaran'), {
            type: 'doughnut',
            data: {
                labels: ['Proposed', 'Active', 'Completed'],
                datasets: [{
                    data: [{{ $statusSummary['proposed'] }}, {{ $statusSummary['active'] }},
                        {{ $statusSummary['completed'] }}
                    ],
                    backgroundColor: ['#dc3545', '#ffc107', '#28a745']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Bar Chart Spend by Pilar
        const spendData = @json($spendByPilarPerMonth);
        const allPilars = @json($byPilar); // Ambil semua pilar dari controller
        const pilars = allPilars.map(p => p.pilar); // Ekstrak nama pilar
        const bulanLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agust', 'Sep', 'Okt', 'Nov',
            'Des'
        ];

        const datasets = pilars.map((pilar, idx) => {
            const colors = ['#17a2b8', '#dc3545', '#28a745', '#007bff', '#6f42c1', '#fd7e14'];
            return {
                label: pilar,
                backgroundColor: colors[idx % colors.length],
                data: Array.from({
                    length: 12
                }, (_, i) => {
                    const found = spendData.find(x => x.bulan === (i + 1));
                    return found && found.data[pilar] ? found.data[pilar] : 0;
                }),
                skipNull: false
            };
        });

        new Chart(document.getElementById('barSpendPilar'), {
            type: 'bar',
            data: {
                labels: bulanLabels,
                datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                scales: {
                    x: {
                        stacked: true
                    },
                    y: {
                        stacked: true,
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000000) {
                                    return (value / 1000000000).toFixed(1) + ' M';
                                } else if (value >= 1000000) {
                                    return (value / 1000000).toFixed(1) + ' jt';
                                } else if (value >= 1000) {
                                    return (value / 1000).toFixed(1) + ' rb';
                                }
                                return value;
                            }
                        }
                    }
                },
                elements: {
                    bar: {
                        borderWidth: 1
                    }
                }
            }
        });

        // Pie Regional
        const regionalData = @json($byRegion);
        new Chart(document.getElementById('pieRegional'), {
            type: 'pie',
            data: {
                labels: regionalData.map(r => r.region),
                datasets: [{
                    data: regionalData.map(r => r.total),
                    backgroundColor: ['#007bff', '#6f42c1', '#fd7e14', '#dc3545', '#ffc107',
                        '#17a2b8', '#20c997', '#6610f2'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Line Participation
        const participationData = @json($participation);
        const tahunLabels = participationData.map(r => r.tahun);
        const pilarSetPart = new Set();
        participationData.forEach(r => Object.keys(r.data).forEach(p => pilarSetPart.add(p)));
        const pilarsPart = Array.from(pilarSetPart);

        const lineDatasets = pilarsPart.map((pilar, idx) => {
            const colors = ['#007bff', '#dc3545', '#28a745', '#6f42c1', '#fd7e14', '#17a2b8'];
            return {
                label: pilar,
                borderColor: colors[idx % colors.length],
                backgroundColor: colors[idx % colors.length],
                tension: 0.2,
                fill: false,
                data: tahunLabels.map(t => {
                    const found = participationData.find(x => x.tahun === t);
                    return found && found.data[pilar] ? found.data[pilar] : 0;
                })
            };
        });

        new Chart(document.getElementById('lineParticipation'), {
            type: 'line',
            data: {
                labels: tahunLabels,
                datasets: lineDatasets
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>

<script>
    let currentProvincePage = {{ $provincePage }};

    function changeProvincePage(page) {
        // Show loading
        document.getElementById('provinceTableBody').innerHTML =
            '<tr><td colspan="2" class="text-center">Loading...</td></tr>';

        fetch(`{{ route('tjsl.dashboard.provinces') }}?page=${page}`)
            .then(response => response.json())
            .then(data => {
                // Update table body
                let tbody = '';
                data.byProvince.forEach(row => {
                    tbody += `<tr><td>${row.provinsi}</td><td class="text-end">${row.total}</td></tr>`;
                });
                document.getElementById('provinceTableBody').innerHTML = tbody;

                // Update pagination info
                document.getElementById('provincePageInfo').textContent =
                    `Hal ${data.provincePage} dari ${data.totalPages}`;
                document.getElementById('provinceRangeInfo').textContent =
                    `${data.rangeStart}-${data.rangeEnd} dari ${data.totalProvinces}`;
                document.getElementById('provinceTotalInfo').innerHTML =
                    `<strong>Total: ${data.totalProgramsInProvinces.toLocaleString()} program</strong>`;

                // Update buttons
                const prevBtn = document.getElementById('provincePrevBtn');
                const nextBtn = document.getElementById('provinceNextBtn');

                prevBtn.disabled = data.provincePage <= 1;
                nextBtn.disabled = data.provincePage >= data.totalPages;

                prevBtn.onclick = () => changeProvincePage(data.provincePage - 1);
                nextBtn.onclick = () => changeProvincePage(data.provincePage + 1);

                currentProvincePage = data.provincePage;
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('provinceTableBody').innerHTML =
                    '<tr><td colspan="2" class="text-center text-danger">Error loading data</td></tr>';
            });
    }

    // ... existing chart scripts ...
</script>
