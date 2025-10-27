@extends('layouts.app')

@section('title', 'Monitoring Biaya TJSL')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Monitoring Biaya TJSL</h3>
                    </div>
                    <div class="card-body">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <form method="GET" action="{{ route('monitoringbiaya.index') }}">
                                    <div class="row align-items-end">
                                        <div class="col-md-2">
                                            <label for="filter_tahun" class="form-label small text-muted">Tahun</label>
                                            @php
                                                $currentYear = now()->year;
                                                $selectedYear = request('tahun', $currentYear);
                                            @endphp
                                            <select name="tahun" id="filter_tahun" class="form-control form-control-sm">
                                                <option value="">Semua Tahun</option>
                                                @foreach (range($currentYear, $currentYear + 10) as $y)
                                                    <option value="{{ $y }}"
                                                        {{ (string) $selectedYear === (string) $y ? 'selected' : '' }}>
                                                        {{ $y }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-4">
                                            <label for="filter_sub_pilar" class="form-label small text-muted">Sub
                                                Pilar</label>
                                            <select name="sub_pilar_id" id="filter_sub_pilar" class="form-control">
                                                <option value="">Semua Sub Pilar</option>
                                                @foreach (($subPilars ?? collect())->sortBy(fn($sp) => (int) $sp->id) as $sp)
                                                    <option value="{{ $sp->id }}"
                                                        {{ request('sub_pilar_id') == $sp->id ? 'selected' : '' }}>
                                                        {{ $sp->id }} - {{ $sp->sub_pilar }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="fas fa-filter"></i> Terapkan
                                                </button>
                                                <a href="{{ route('monitoringbiaya.index') }}"
                                                    class="btn btn-warning btn-sm">
                                                    <i class="fas fa-undo"></i> Reset
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="monitoringBiayaTable">
                                <thead>
                                    <tr>
                                        <th>Tahun</th>
                                        <th class="text-left">Sub Pilar</th>
                                        <th class="text-end">Anggaran</th>
                                        <th class="text-end">Realisasi</th>
                                        <th class="text-end">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dataset as $row)
                                        @php
                                            $pct =
                                                $row['anggaran_total'] > 0
                                                    ? ($row['realisasi_total'] / $row['anggaran_total']) * 100
                                                    : null;
                                        @endphp
                                        <tr>
                                            <td>{{ $row['tahun'] }}</td>
                                            <td class="text-left" data-order="{{ (int) $row['sub_pilar_id'] }}">
                                                {{ $row['sub_pilar_id'] }} - {{ $row['sub_pilar_name'] }}
                                            </td>
                                            <td class="text-end" data-order="{{ (float) $row['anggaran_total'] }}">
                                                Rp {{ number_format($row['anggaran_total'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-end" data-order="{{ (float) $row['realisasi_total'] }}">
                                                Rp {{ number_format($row['realisasi_total'], 0, ',', '.') }}
                                            </td>
                                            <td class="text-end" data-order="{{ $pct !== null ? $pct : -1 }}">
                                                {{ $pct !== null ? number_format($pct, 2, ',', '.') . '%' : '-' }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        /* Pastikan kelas ini menang, baik untuk TH maupun TD */
        #monitoringBiayaTable th.text-end,
        #monitoringBiayaTable td.text-end {
            text-align: right !important;
        }

        #monitoringBiayaTable th.text-left,
        #monitoringBiayaTable td.text-left {
            text-align: left !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(function() {
            if ($.fn.DataTable) {
                // Sorting numerik via data-order
                $.fn.dataTable.ext.order['dom-data-order'] = function(settings, col) {
                    return this.api().column(col, {
                        order: 'index'
                    }).nodes().map(function(td) {
                        var val = $(td).data('order');
                        return val !== undefined ? parseFloat(val) : $(td).text();
                    });
                };

                $('#monitoringBiayaTable').DataTable({
                    pageLength: 10,
                    order: [
                        [0, 'asc'], // Tahun
                        [1, 'asc'] // Sub Pilar
                    ],
                    columnDefs: [{
                            targets: 1,
                            orderDataType: 'dom-data-order',
                            className: 'text-left'
                        }, // Sub Pilar
                        {
                            targets: [2, 3, 4],
                            className: 'text-end',
                            orderDataType: 'dom-data-order'
                        } // Anggaran, Realisasi, %
                    ]
                });
            }
        });
    </script>
@endpush
