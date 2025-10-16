@extends('layouts.app')

@section('content')
<style>
    .table {
  border-collapse: collapse;
  width: 100%;
}

.table th,
.table td {
  border-top: 1px solid #dee2e6;
  border-bottom: 1px solid #dee2e6;
  border-left: none !important;
  border-right: none !important;
  padding: 4px 6px; /* default Bootstrap biasanya 12px 8px → ini diperkecil */
  line-height: 1.1; /* rapatkan tinggi baris */
}

.table thead th {
  border-bottom: 2px solid #dee2e6;
}

.table tbody tr:last-child td {
  border-bottom: 2px solid #dee2e6;
}

</style>
<div class="container py-4">
  <h4 class="mb-4">📊 Dashboard Program TJSL</h4>
    <div class="d-flex justify-content-end mb-3">
  <form id="filterForm" method="GET" action="{{ route('dashboard.tjsl') }}">
  <div class="input-group" style="width: 200px;">
    <select name="tahun" id="tahun" class="form-select form-select-sm">
      <option value="">Semua Tahun</option>
      @foreach($availableYears as $y)
        <option value="{{ $y }}"
          {{ ($selectedYear == $y || (!request()->has('tahun') && $y == date('Y'))) ? 'selected' : '' }}>
          {{ $y }}
        </option>
      @endforeach
    </select>
    <button class="btn btn-primary btn-sm" type="submit">Filter</button>
  </div>
</form>

</div>

  {{-- ======== SUMMARY CARDS ======== --}}
  <div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-primary text-uppercase mb-1">
                            Jumlah Program</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800">{{ number_format($jumlah_program) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-success text-uppercase mb-1">
                            Total Realisasi (Rp)</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800" id="cardStakeholder">{{ number_format($total_realisasi) }}</div>
                        
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-warning text-uppercase mb-1">
                            Total Penerima</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800" id="cardGov">{{ number_format($total_penerima) }}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

  </div>
  

  {{-- ======== CHARTS ======== --}}
  <div class="row">
    <div class="col-md-3">
        <div class="card shadow-sm mb-4">
            <div class="card-body" style="height: 250px; position: relative;">
                <canvas id="statusAnggaranDonut"></canvas>
            </div>
        </div>
    </div>
    {{-- <div class="col-md-3">
      <div class="card p-3 shadow-sm">
        <h6 class="text-center mb-2">Status Program</h6>
        <canvas id="statusDonut"></canvas>
      </div>
    </div> --}}
    <div class="col-md-6">
      <div class="card shadow-sm">
        <div class="card-body" style="height: 250px; position: relative;">
            <canvas id="barRealisasi"></canvas>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm">
        <div class="card-body" style="height: 250px; position: relative;">
        <p class="text-center mb-2">Jumlah Program </p>
        <canvas id="regionalDonut"></canvas>
        </div>
      </div>
    </div>
  {{-- </div> --}}


  {{-- ======== TABLES ======== --}}
  
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body" style="height: 250px; position: relative;">
        <p class="text-center mb-2">Berdasarkan Provinsi </p>
        <table class="table" style="font-size: 0.8em;">
            <thead class="table">
              <tr>
                <th>Berdasarkan Provinsi</th>
                <th>Jumlah</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tabel_provinsi as $index => $row)
              <tr @if($row->provinsi === 'Total') class="fw-bold bg-light" @endif>
                {{-- <td>{{ $row->provinsi === 'Total' ? '' : $index + 1 }}</td> --}}
                <td>{{ $row->provinsi }}</td>
                <td>{{ number_format($row->jumlah_program) }}</td>
            </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body" style="height: 250px; position: relative;">
            <p class="text-center mb-2">Berdasarkan Pilar </p>
        <table class="table " style="font-size: 0.8em;" >
            <thead class="table">
              <tr>
                <th style="width: 50%;">Berdasarkan Pilar</th>
                <th style="width: 5%;">Jlh.</th>
                <th style="width: 45%;">Total Realisasi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($tabel_pilar as $row)
              <tr @if($row->pilar == 'Total') class="fw-bold bg-light" @endif>
                <td>{{ $row->pilar }}</td>
                <td style="text-align: right;">{{ $row->jumlah_program }}</td>
                <td style="text-align: right;">Rp {{ number_format($row->total_realisasi, 2, ',', '.') }}</td>
            </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card shadow-sm">
        <div class="card-body" style="height: 250px; position: relative;">
        <p class="text-center mb-2">Employee Participation </p>
        <canvas id="employeeParticipationChart" height="100"></canvas>
        </div>
      </div>
    </div>   
   
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script>
// ===== Donut Chart Regional =====
const ctxRegional = document.getElementById('regionalDonut');

new Chart(ctxRegional, {
  type: 'doughnut',
  data: {
    labels: @json($program_per_regional->pluck('regional')),
    datasets: [{
      data: @json($program_per_regional->pluck('total')),
      backgroundColor: [
        '#007bff','#28a745','#ffc107','#dc3545',
        '#6f42c1','#20c997','#fd7e14','#17a2b8'
      ]
    }]
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        position: 'right',
        labels: {
          boxWidth: 12,
          boxHeight: 12,
          padding: 8,
          font: { size: 9 }
        }
      },
      tooltip: {
        callbacks: {
          label: function(context) {
            const total = context.dataset.data.reduce((a,b) => a + b, 0);
            const value = context.parsed;
            const percentage = ((value / total) * 100).toFixed(1);
            return `${context.label}: ${value} (${percentage}%)`;
          }
        }
      },
      // 🔹 Data labels di dalam donut
      datalabels: {
        color: '#000',
        font: {
        //   weight: 'bold',
          size: 10
        },
        formatter: (value, ctx) => {
          const dataset = ctx.chart.data.datasets[0];
          const total = dataset.data.reduce((a,b) => a + b, 0);
          const percentage = ((value / total) * 100).toFixed(1);
          return value > 0 ? `${percentage}%` : '';
        }
      }
    },
    cutout: '30%', // ukuran lubang donut
  },
  plugins: [ChartDataLabels] // aktifkan plugin
});
</script>
<script>
// document.addEventListener("DOMContentLoaded", function() {
//   const labels = @json($status_donut->pluck('status'));
//   const values = @json($status_donut->pluck('total'));
//   const total = values.reduce((a, b) => a + b, 0);

//   const ctx = document.getElementById('statusDonut');
//   const statusData = {
//     labels: labels,
//     datasets: [{
//       data: values,
//       backgroundColor: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
//       borderWidth: 1
//     }]
//   };

//   new Chart(ctx, {
//     type: 'doughnut',
//     data: statusData,
//     options: {
//       responsive: true,
//       plugins: {
//         legend: {
//           position: 'bottom',
//           labels: {
//             color: '#333',
//             boxWidth: 10,      // ukuran kotak warna lebih kecil
//       boxHeight: 10,
//     //   padding: 8,
//             font: { size: 9 },
//             generateLabels: function(chart) {
//               const dataset = chart.data.datasets[0];
//               const total = dataset.data.reduce((a, b) => a + b, 0);
//               return chart.data.labels.map((label, i) => {
//                 const value = dataset.data[i];
//                 const percentage = ((value / total) * 100).toFixed(1);
//                 return {
//                   text: `${label} (${percentage}%)`,
//                   fillStyle: dataset.backgroundColor[i],
//                   strokeStyle: '#fff',
//                   lineWidth: 1,
//                   hidden: isNaN(value),
//                   index: i
//                 };
//               });
//             }
//           }
//         },
//         tooltip: {
//           callbacks: {
//             label: function(context) {
//               const dataset = context.dataset;
//               const total = dataset.data.reduce((a, b) => a + b, 0);
//               const value = dataset.data[context.dataIndex];
//               const percentage = ((value / total) * 100).toFixed(1);
//               return `${context.label}: ${value} (${percentage}%)`;
//             }
//           }
//         },
//         // Tambahkan plugin untuk teks di tengah chart
//         datalabels: false
//       },
//       cutout: '40%',
//     },
//     plugins: [{
//       // Plugin custom: tulis "Total" di tengah chart
//       id: 'centerText',
//       afterDraw(chart, args, options) {
//         const {ctx, chartArea: {width, height}} = chart;
//         ctx.save();
//         ctx.font = 'bold 18px sans-serif';
//         ctx.textAlign = 'center';
//         ctx.fillStyle = '#333';
//         ctx.fillText('Total: ' + total, width / 2, height / 2 + 10);
//       }
//     }]
//   });
// });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // Ambil data dari Laravel (pastikan sudah dikirim via compact)
  const labels = @json($status_anggaran->pluck('status'));
  const dataValues = @json($status_anggaran->pluck('total_anggaran')).map(Number);

  const total = dataValues.reduce((a, b) => a + b, 0);

  // Warna per status
  const colors = {
    'Active': '#ffc107',
    'Proposed': '#fa0202',
    'Completed': '#28a745'
  };

  const backgroundColors = labels.map(label => colors[label] || '#999');

  // Inisialisasi chart
  const ctx = document.getElementById('statusAnggaranDonut').getContext('2d');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: labels,
      datasets: [{
        data: dataValues,
        backgroundColor: backgroundColors,
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const value = context.raw;
              const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
              return `${context.label}: Rp ${value.toLocaleString('id-ID')} (${percentage}%)`;
            }
          }
        },
        legend: {
          position: 'bottom',
          labels: { boxWidth: 12,      // ukuran kotak warna lebih kecil
      boxHeight: 12,
      padding: 8,        // jarak antar legend item
      font: {
        size: 11         // font lebih kecil
      },
            generateLabels: function(chart) {
              const data = chart.data;
              if (data.labels.length && data.datasets.length) {
                const total = data.datasets[0].data.reduce((a, b) => a + b, 0);
                return data.labels.map(function(label, i) {
                  const value = data.datasets[0].data[i];
                  const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                  return {
                    text: `${label} (${percentage}%)`,
                    fillStyle: data.datasets[0].backgroundColor[i],
                    strokeStyle: '#fff',
                    lineWidth: 2
                  };
                });
              }
              return [];
            }
          }
        },
        title: {
          display: true,
          text: `Total Anggaran: Rp ${total.toLocaleString('id-ID')}`,
          font: { size: 14 }
        }
      }
    }
  });
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {

  const data = @json($realisasi_perbulan);
  const monthNames = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
  const months = Array.from({length: 12}, (_, i) => i + 1);
  const pilars = [...new Set(data.map(item => item.pilar))];

  const groupedData = {};
  pilars.forEach(pilar => {
    groupedData[pilar] = months.map(() => 0);
  });

  data.forEach(item => {
    const bulan = item.bulan ? parseInt(item.bulan) : null;
    if (bulan && groupedData[item.pilar]) {
      groupedData[item.pilar][bulan - 1] = parseFloat(item.total);
    }
  });

  const datasets = Object.entries(groupedData).map(([pilar, values], i) => ({
    label: pilar,
    data: values,
    backgroundColor: `hsl(${i * 70}, 70%, 50%)`,
  }));

  new Chart(document.getElementById('barRealisasi'), {
    type: 'bar',
    data: {
      labels: monthNames,
      datasets: datasets
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      scales: {
        x: {
          title: { display: true, text: 'Bulan' },
        },
        y: {
          beginAtZero: true,
          title: { display: true, text: 'Total Realisasi (juta)' },
          ticks: {
            // 🔹 ubah nilai ke jutaan
            callback: function(value) {
              return (value / 1000000).toFixed(1).replace('.0', '') + ' jt';
            }
          }
        }
      },
      plugins: {
        legend: {
          position: 'bottom',
          labels: {
            boxWidth: 12,
            boxHeight: 12,
            padding: 8,
            font: { size: 11 }
          }
        },
        title: {
          display: true,
          text: 'Realisasi Program TJSL per Bulan per Pilar'
        },
        tooltip: {
          callbacks: {
            label: function(ctx) {
              const value = ctx.parsed.y;
              const juta = (value / 1000000).toFixed(2);
              return `${ctx.dataset.label}: ${juta} juta`;
            }
          }
        }
      }
    }
  });
});
</script>

<script>
const empRaw = @json($employee_participation);
const tahunList = @json($years);
const pilarList = @json($pilar_list);

// Buat struktur data awal dengan 0 untuk semua tahun
const empGroups = {};
pilarList.forEach(pilar => empGroups[pilar] = tahunList.map(() => 0));

// Isi nilai employee berdasarkan hasil query
empRaw.forEach(item => {
  const tIndex = tahunList.indexOf(parseInt(item.tahun));
  if (tIndex >= 0 && empGroups[item.pilar]) {
    empGroups[item.pilar][tIndex] = parseFloat(item.total_employee);
  }
});

// Dataset per pilar (warna otomatis)
const empDatasets = Object.entries(empGroups).map(([pilar, data], i) => ({
  label: pilar,
  data: data,
  backgroundColor: `hsl(${i * 60}, 70%, 55%)`
}));

// Inisialisasi chart horizontal
new Chart(document.getElementById('employeeParticipationChart'), {
  type: 'bar',
  data: {
    labels: tahunList,
    datasets: empDatasets
  },
  options: {
    indexAxis: 'y', // 🔹 ubah jadi horizontal bar
    responsive: true,
    maintainAspectRatio: false,
    layout: {
      padding: { bottom: 30 }
    },
    scales: {
      x: {
        beginAtZero: true,
        title: { display: true, text: 'Total Employee' },
        grid: { drawBorder: false } // opsional: hilangkan garis tepi
      },
      y: {
        title: { display: true, text: 'Tahun' },
        grid: { display: false } // opsional: hilangkan garis horizontal
      }
    },
    plugins: {
      legend: {
        position: 'bottom',
        labels: { boxWidth: 12, padding: 10 }
      },
      tooltip: {
        callbacks: {
          label: ctx => `${ctx.dataset.label}: ${ctx.formattedValue}`
        }
      },
      // 🔹 Aktifkan Data Labels
      datalabels: {
        anchor: 'end',     // posisi relatif terhadap bar
        align: 'right',    // letakkan di ujung kanan bar
        color: '#000',     // warna teks
        font: {
          weight: 'bold',
          size: 10
        },
        formatter: (value) => value ? value : '' // hanya tampilkan jika > 0
      }
    }
  },
  plugins: [ChartDataLabels] // 🔹 wajib: aktifkan plugin
});
</script>

@endsection

@push('scripts')


@endpush
