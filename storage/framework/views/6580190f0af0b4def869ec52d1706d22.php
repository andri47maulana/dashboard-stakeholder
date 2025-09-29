

<style>
    .map-container {
        width: 100%;
        height: 100%;
        border: 1px solid #ddd;
        border-radius: 6px;
        overflow: hidden;
        position: relative;
    }

    #map {
        width: 100%;
        height: 100%;
        position: relative !important;
        z-index: 0;
    }

    

    .bg-yellow {
        background-color: #ffff00 !important; /* kuning cerah */
        color: #000 !important; /* teks hitam biar kontras */
    }

</style>


<?php $__env->startSection('content'); ?>
<h1 class="h3 mb-2 text-gray-800">Derajat Hubungan Regional</h1>



<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row" >
            <div class="col-md-12">
                <label>Unit</label>
                <select name="id_unit" id="id_unit" class="form-control select2" required>
                <option value="">Pilih Unit</option>
                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>"><?php echo e($u->unit); ?> </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <br>
          </div>
        </div>
        
        <div class="row">
    <!-- Kolom Map -->
    <div class="col-12 col-md-6 mb-3" style="height: 560px;">
        <div class="map-container h-100">
            <div id="map"></div>
        </div>
    </div>

    <!-- Kolom Chart & Info -->
    <div class="col-12 col-md-6">
        <div class="row">
            <!-- Pie Chart -->
            <div class="col-12 col-md-6 mb-3">
                <h6 class="text-center">Derajat Hubungan <?php echo e($region); ?></h6>
                <div class="card-body">
                    <canvas id="pieChartPrioritas"></canvas>
                </div>
            </div>
            
            <!-- Tabel Indikator -->
            <div class="col-12 col-md-6 mb-3">
                <h6 class="text-center">Indeks Kepuasan</h6>
                <table class="table table-bordered table-sm" style="font-size:0.9em;">
                    <tr class="text-center fw-bold">
                        <th>Indikator</th>
                        <th>Nilai</th>
                    </tr>
                    <tr>
                        <td>Kepuasan</td>
                        <td class="text-center"><?php echo e($rataRata->avg_kepuasan ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td>Kontribusi</td>
                        <td class="text-center"><?php echo e($rataRata->avg_kontribusi ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td>Komunikasi</td>
                        <td class="text-center"><?php echo e($rataRata->avg_komunikasi ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td>Kepercayaan</td>
                        <td class="text-center"><?php echo e($rataRata->avg_kepercayaan ?? '-'); ?></td>
                    </tr>
                    <tr>
                        <td>Keterlibatan/Kerjasama</td>
                        <td class="text-center"><?php echo e($rataRata->avg_keterlibatan ?? '-'); ?></td>
                    </tr>
                    <tr class="fw-bold">
                        <td>Total Skor Indeks Kepuasan</td>
                        <td class="text-center"><?php echo e($rataRata->avg_indeks_kepuasan ?? '-'); ?></td>
                    </tr>
                    <tr class="fw-bold">
                        <td>Jumlah Stakeholder</td>
                        <td class="text-center"><?php echo e($jlhUnit ?? '-'); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Line Chart Full Width -->
        <div class="row mt-3">
            <div class="col-12">
                <h6 class="mb-3 text-center">Tren Hubungan Stakeholder <?php echo e($region); ?></h6>
                <div class="card-body">
                    <canvas id="lineChart" height="120"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>


        
    </div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function(){
    var map = L.map('map', { zoomControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // fokus awal Indonesia
    map.fitBounds([[-11,95],[6,141]]);
    L.control.zoom({ position: 'topright' }).addTo(map);
    
    var kebunJsons = <?php echo json_encode($kebunJsons->map(fn($k) => [
        'id' => $k->id, 'unit_id' => $k->unit_id, 'decoded' => $k->decoded
    ])) ?>;

    var colors = ["#FF5733","#33C1FF","#28A745","#FFC300","#9B59B6","#E67E22"];
    function getColor(index){ return colors[index % colors.length]; }

    var currentLayers = [];
    var selectedUnitId = null;   // <<< simpan unit yang dipilih
    var selectedYear = null;     // <<< simpan tahun yang dipilih

    function clearPolygons(){
        currentLayers.forEach(l => map.removeLayer(l));
        currentLayers = [];
    }

    var allBounds = L.latLngBounds([]);

    kebunJsons.forEach((jsonData,index) => {
        var color = getColor(index);
        var layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
            vectorTileLayerStyles: {
                [jsonData.decoded.id]: {
                    weight: 2,
                    color: color,
                    fill: true,
                    fillColor: color,
                    fillOpacity: 0.4
                }
            }
        }).addTo(map);

        currentLayers.push(layer);

        if(jsonData.decoded.bounds?.length === 4){
            allBounds.extend([
                [jsonData.decoded.bounds[1], jsonData.decoded.bounds[0]],
                [jsonData.decoded.bounds[3], jsonData.decoded.bounds[2]]
            ]);
        }
    });

    if(allBounds.isValid()){
        map.fitBounds(allBounds, { padding:[20,20] });
    }


});
</script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('pieChartPrioritas').getContext('2d');

    const data = {
        labels: ['P1', 'P2', 'P3', 'P4'],
        datasets: [{
            label: 'Jumlah',
            data: [
                <?php echo e($jumlahPrioritas->total_p1 ?? 0); ?>,
                <?php echo e($jumlahPrioritas->total_p2 ?? 0); ?>,
                <?php echo e($jumlahPrioritas->total_p3 ?? 0); ?>,
                <?php echo e($jumlahPrioritas->total_p4 ?? 0); ?>

            ],
            backgroundColor: [
                '#ff0000d5', // merah
                '#fd7e14', // biru
                '#ffff00', // kuning
                '#28a745'  // hijau
            ],
            borderColor: '#fff',
            borderWidth: 2
        }]
    };

    new Chart(ctx, {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            let value = context.raw || 0;
                            return label + ': ' + value;
                        }
                    }
                }
            }
        }
    });
});
</script>
<script>
document.addEventListener("DOMContentLoaded", function(){
    const countsPerYear = <?php echo json_encode($countsPerYear, 15, 512) ?>;

    // Ambil label tahun
    const labels = countsPerYear.map(item => item.tahun);

    // Dataset per kategori
    const dataP1 = countsPerYear.map(item => item.total_p1);
    const dataP2 = countsPerYear.map(item => item.total_p2);
    const dataP3 = countsPerYear.map(item => item.total_p3);
    const dataP4 = countsPerYear.map(item => item.total_p4);

    const ctx = document.getElementById('lineChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'P1',
                    data: dataP1,
                    borderColor: '#ff0000d5',
                    backgroundColor: '#ff0000d5',
                    tension: 0.3
                },
                {
                    label: 'P2',
                    data: dataP2,
                    borderColor: '#fd7e14',
                    backgroundColor: '#fd7e14',
                    tension: 0.3
                },
                {
                    label: 'P3',
                    data: dataP3,
                    borderColor: '#ffff00',
                    backgroundColor: '#ffff00',
                    tension: 0.3
                },
                {
                    label: 'P4',
                    data: dataP4,
                    borderColor: '#28a745',
                    backgroundColor: '#28a745',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision:0
                    }
                }
            }
        }
    });
});
</script>
<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/peta/peta_region.blade.php ENDPATH**/ ?>