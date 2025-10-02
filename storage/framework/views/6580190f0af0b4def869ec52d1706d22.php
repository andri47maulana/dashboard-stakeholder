

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
        <div class="row">
            <div class="col-md-4">
                <label>Tahun</label>
                <select name="tahun" id="tahun" class="form-control select2" required>
                    <option value="">Pilih Tahun</option>
                    <?php $__currentLoopData = $tahun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($t); ?>" <?php echo e($t == ($yearNow-1) ? 'selected' : ''); ?>><?php echo e($t); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-6">
                <label>Unit</label>
                <select name="id_unit" id="id_unit" class="form-control select2" required>
                    <option value="">Pilih Unit</option>
                    <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($u->id); ?>"><?php echo e($u->unit); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
        </div>

        <hr>

        <div class="row">
            <!-- Map -->
            <div class="col-md-6" style="height:560px;">
                <div class="map-container h-100">
                    <div id="map"></div>
                </div>
            </div>

            <!-- Chart & Tabel -->
            <div class="col-md-6">
                <div class="row">
                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <h6 class="text-center">Derajat Hubungan <?php echo e($region); ?></h6>
                        <canvas id="pieChartPrioritas"></canvas>
                    </div>
                    <!-- Tabel Indikator -->
                    <div class="col-md-6">
                        <h6 class="text-center">Indeks Kepuasan</h6>
                        <table class="table table-bordered table-sm" style="font-size:0.9em;">
                            <tr class="text-center fw-bold">
                                <th>Indikator</th>
                                <th>Nilai</th>
                            </tr>
                            <tr><td>Kepuasan</td><td id="avg_kepuasan"><?php echo e($rataRata->avg_kepuasan ?? '-'); ?></td></tr>
                            <tr><td>Kontribusi</td><td id="avg_kontribusi"><?php echo e($rataRata->avg_kontribusi ?? '-'); ?></td></tr>
                            <tr><td>Komunikasi</td><td id="avg_komunikasi"><?php echo e($rataRata->avg_komunikasi ?? '-'); ?></td></tr>
                            <tr><td>Kepercayaan</td><td id="avg_kepercayaan"><?php echo e($rataRata->avg_kepercayaan ?? '-'); ?></td></tr>
                            <tr><td>Keterlibatan</td><td id="avg_keterlibatan"><?php echo e($rataRata->avg_keterlibatan ?? '-'); ?></td></tr>
                            <tr class="fw-bold"><td>Total Skor</td><td id="avg_indeks"><?php echo e($rataRata->avg_indeks_kepuasan ?? '-'); ?></td></tr>
                            <tr class="fw-bold"><td>Jumlah Stakeholder</td><td id="jlhUnit"><?php echo e($jlhUnit ?? '-'); ?></td></tr>
                        </table>
                    </div>
                </div>

                <!-- Line Chart -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="mb-3 text-center">Tren Hubungan Stakeholder <?php echo e($region); ?></h6>
                        <canvas id="lineChart" height="120"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Unit -->
<div class="modal fade" id="unitDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="unitDetailModalLabel" >Detail Unit</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        

        <div class="row">
            <div class="col-md-6">
                <div class="card mb-3 shadow-sm">
                <div id="mapContainerAll" style="height:300px; width:100%"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-3 shadow-sm" >
                        <div class="card-header bg-info text-white text-center">Deskripsi</div>
                        <div class="card-body" style="font-size:0.9em; height:250px; display:flex; flex-direction:column; overflow-y:auto;">
                            <p style="text-align:justify; margin-top:8px;" id="deskripsi"></p>
                            <table class="table table-bordered table-sm" id="isuCardTable">
                                <thead>
                                    <tr>
                                        <th>Isu</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Data akan diisi JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
            </div>
            <br><br>
            <div class="col-md-3" >
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-info text-white text-center">Desa Sasaran utama</div>
                    <div class="card-body" style="font-size:0.9em; height:150px; display:flex; flex-direction:column; overflow-y:auto;">
                        <table class="table table-bordered table-sm" id="sasarandesa">
                            <thead>
                                <tr>
                                    <th>Desa</th>
                                    <th>Isu Utama</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>  
                    </div>
                </div>  
            </div>
            <div class="col-md-6" >
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-info text-white text-center">Prioritas Lembaga/Instansi</div>
                    <div class="card-body" style="font-size:0.9em; height:150px; display:flex; flex-direction:column; overflow-y:auto;">
                        <table class="table table-bordered table-sm" id="isuLembagaTable">
                            <thead>
                                <tr>
                                    <th>Lemabaga/Instansi</th>
                                    <th>Program</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>  
                    </div>
                </div>  
            </div>
            <div class="col-md-3">
                <div class="card mb-3 shadow-sm" >
                    <div class="card-header bg-info text-white text-center">Okupasi</div>
                    <div class="card-body flex-grow-1 overflow-auto" id="okupasiCardBody" style="font-size:0.9em; height:150px; display:flex; flex-direction:column; overflow-y:auto;">
                        <!-- Data akan diisi JS -->
                    </div>
                </div>  
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<!-- Leaflet + Chart.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // ================== MAP UTAMA ==================
    var map = L.map('map', { zoomControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    map.fitBounds([[-11,95],[6,141]]);
    L.control.zoom({ position: 'topright' }).addTo(map);

    let polygonLayers = [];
    function clearPolygons(){
        polygonLayers.forEach(l => map.removeLayer(l));
        polygonLayers = [];
    }

    // ================== CHARTS ==================
    const region = "<?php echo e($region); ?>";
    let selectedYear = parseInt(document.getElementById('tahun').value) || <?php echo e($yearNow-1); ?>;
    let selectedUnitId = null;

    let pieCtx = document.getElementById('pieChartPrioritas').getContext('2d');
    let pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: { 
            labels:['P1','P2','P3','P4'], 
            datasets:[{ label:'Jumlah', data:[0,0,0,0],
                backgroundColor:['#ff0000d5','#fd7e14','#ffff00','#28a745'],
                borderColor:'#fff', borderWidth:2 
            }] 
        },
        options:{ responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });

    let lineCtx = document.getElementById('lineChart').getContext('2d');
    let lineChart = new Chart(lineCtx, {
        type: 'line',
        data: { labels:[], datasets:[] },
        options:{ responsive:true, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
    });

    // ================== FETCH DATA PER TAHUN ==================
    function loadDataByYear(year){
        fetch(`/peta/peta_region/data/${region}/${year}`)
        .then(res=>res.json())
        .then(data=>{
            // --- update pie ---
            pieChart.data.datasets[0].data = [
                data.jumlahPrioritas.total_p1,
                data.jumlahPrioritas.total_p2,
                data.jumlahPrioritas.total_p3,
                data.jumlahPrioritas.total_p4
            ];
            pieChart.update();

            // --- update line ---
            lineChart.data.labels = data.countsPerYear.map(i=>i.tahun);
            lineChart.data.datasets = [
                {label:'P1', data:data.countsPerYear.map(i=>i.total_p1), borderColor:'#ff0000d5', backgroundColor:'#ff0000d5', tension:0.3},
                {label:'P2', data:data.countsPerYear.map(i=>i.total_p2), borderColor:'#fd7e14', backgroundColor:'#fd7e14', tension:0.3},
                {label:'P3', data:data.countsPerYear.map(i=>i.total_p3), borderColor:'#ffff00', backgroundColor:'#ffff00', tension:0.3},
                {label:'P4', data:data.countsPerYear.map(i=>i.total_p4), borderColor:'#28a745', backgroundColor:'#28a745', tension:0.3},
            ];
            lineChart.update();

            // --- update tabel rata-rata ---
            document.getElementById('avg_kepuasan').innerText = data.rataRata.avg_kepuasan ?? '-';
            document.getElementById('avg_kontribusi').innerText = data.rataRata.avg_kontribusi ?? '-';
            document.getElementById('avg_komunikasi').innerText = data.rataRata.avg_komunikasi ?? '-';
            document.getElementById('avg_kepercayaan').innerText = data.rataRata.avg_kepercayaan ?? '-';
            document.getElementById('avg_keterlibatan').innerText = data.rataRata.avg_keterlibatan ?? '-';
            document.getElementById('avg_indeks').innerText = data.rataRata.avg_indeks_kepuasan ?? '-';
            document.getElementById('jlhUnit').innerText = data.jlhUnit ?? '-';

            // --- update polygon di map utama ---
            const derajatColors = { "P1":"#ff0000d5","P2":"#fd7e14","P3":"#ffff00","P4":"#28a745" };
            clearPolygons();
            let bounds = L.latLngBounds([]);

            data.kebunJsons.forEach(jsonData=>{
                let color = derajatColors[jsonData.derajat] || "#999";
                let layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
                    vectorTileLayerStyles: {
                        [jsonData.decoded.id]: {
                            weight: 2, color: color, fill: true, fillColor: color, fillOpacity: 0.5
                        }
                    }
                }).addTo(map);

                polygonLayers.push(layer);

                if(jsonData.decoded.bounds?.length === 4){
                    bounds.extend([
                        [jsonData.decoded.bounds[1], jsonData.decoded.bounds[0]],
                        [jsonData.decoded.bounds[3], jsonData.decoded.bounds[2]]
                    ]);
                }
            });

            if(bounds.isValid()) map.fitBounds(bounds,{padding:[20,20]});
        })
        .catch(err=>console.error(err));
    }

    // panggil default
    loadDataByYear(selectedYear);

    // event tahun
    document.getElementById('tahun').addEventListener('change', function(){
        selectedYear = parseInt(this.value);
        loadDataByYear(selectedYear);
    });

    // ================== EVENT UNIT (MODAL DETAIL) ==================
    let mapAll = null;
    document.getElementById('id_unit').addEventListener('change', function(){
        selectedUnitId = this.value;
        if(!selectedUnitId) return;

        fetch(`/peta/unit/detail/${selectedUnitId}/${selectedYear}`)
        .then(res=>res.json())
        .then(data=>{
            const kebunJsons = data.kebunJsons;

            let color = "#0084ff"; 
            if (data.derajatHubungan && data.derajatHubungan.derajat_hubungan) {
                switch (data.derajatHubungan.derajat_hubungan) {
                    case "P1": color = "#dc3545"; break;
                    case "P2": color = "#fd7e14"; break;
                    case "P3": color = "#ffff00"; break;
                    case "P4": color = "#28a745"; break;
                }
            }

            // hapus map lama
            // if (mapAll) { mapAll.remove(); mapAll = null; }
            // mapAll = L.map('mapContainerAll');
            // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAll);

            // var allBounds = L.latLngBounds([]);
            // kebunJsons.forEach(json=>{
            //     L.vectorGrid.protobuf(json.tileurl, {
            //         vectorTileLayerStyles: {
            //             [json.id]: {
            //                 weight: 3, color: color, fill: true, fillColor: color, fillOpacity: 0.5
            //             }
            //         }, interactive: false
            //     }).addTo(mapAll);

            //     if (json.bounds?.length === 4) {
            //         allBounds.extend([[json.bounds[1],json.bounds[0]], [json.bounds[3],json.bounds[2]]]);
            //     }
            // });

            // if(allBounds.isValid()){
            //     mapAll.fitBounds(allBounds, { padding:[50,50], maxZoom:12 });
            //     mapAll.setZoom(mapAll.getZoom()-1);
            // }
            if (mapAll) { 
                mapAll.remove(); 
                mapAll = null; 
            }
            mapAll = L.map('mapContainerAll');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAll);

            var allBounds = L.latLngBounds([]);

            // render polygon jika ada
            kebunJsons.forEach(json=>{
                L.vectorGrid.protobuf(json.tileurl, {
                    vectorTileLayerStyles: {
                        [json.id]: {
                            weight: 3, color: color, fill: true, fillColor: color, fillOpacity: 0.5
                        }
                    }, interactive: false
                }).addTo(mapAll);

                if (json.bounds?.length === 4) {
                    allBounds.extend([
                        [json.bounds[1], json.bounds[0]],
                        [json.bounds[3], json.bounds[2]]
                    ]);
                }
            });

            // cek apakah ada polygon valid
            if (allBounds.isValid()) {
                mapAll.fitBounds(allBounds, { padding:[50,50], maxZoom:12 });
                mapAll.setZoom(mapAll.getZoom()-1);
            } else {
                // fallback ke peta Indonesia
                mapAll.fitBounds([[-11,95],[6,141]],{ padding:[50,50], maxZoom:5 });
                mapAll.setZoom(mapAll.getZoom()-1);
            }


            // legend
            var legendHtml = `<div class="map-legend"><b>Legend:</b><br>
                <div><span class="legend-color" style="background:${color}"></span> Semua Polygon</div></div>`;
            var legend = L.control({position:'topright'});
            legend.onAdd = function(){ let div = L.DomUtil.create('div','map-legend'); div.innerHTML=legendHtml; return div; };
            legend.addTo(mapAll);
            mapAll.invalidateSize();
            setTimeout(() => mapAll.invalidateSize(), 300);

            // isi modal
            document.getElementById('unitDetailModalLabel').innerText = data.unit.unit+' Tahun '+selectedYear;
            document.getElementById('deskripsi').innerText = data.derajatHubungan?.deskripsi ?? '-';

            // isi tabel desa
            const tbody = document.querySelector('#sasarandesa tbody');
            tbody.innerHTML = data.desa?.length ? 
                data.desa.map(d=>`<tr><td>${d.nama??'-'}</td><td>${d.isu_utama??'-'}</td></tr>`).join('')
                : `<tr><td colspan="2" class="text-center">Tidak ada data desa</td></tr>`;

            // isi isu card
            const tbodyCard = document.querySelector('#isuCardTable tbody');
            tbodyCard.innerHTML = data.isu?.length ? 
                data.isu.map(i=>`<tr><td>${i.isu??'-'}</td><td>${i.keterangan??'-'}</td></tr>`).join('')
                : `<tr><td colspan="2" class="text-center">Tidak ada data isu</td></tr>`;

            // isi isu lembaga
            const tbodyCard1 = document.querySelector('#isuLembagaTable tbody');
            tbodyCard1.innerHTML = data.lembaga?.length ? 
                data.lembaga.map(i=>`<tr><td>${i.nama??'-'}</td><td>${i.program??'-'}</td></tr>`).join('')
                : `<tr><td colspan="2" class="text-center">Tidak ada data lembaga/instansi</td></tr>`;

            // isi okupasi
            const okupasiCard = document.getElementById('okupasiCardBody');
            if(data.okupasi?.length){
                const ok = data.okupasi[0];
                let bg = (ok.okupasi=="Tinggi")?"bg-danger text-white":(ok.okupasi=="Sedang")?"bg-warning text-dark":(ok.okupasi=="Rendah")?"bg-success text-white":"bg-secondary text-white";
                okupasiCard.innerHTML = `
                    <div class="${bg} p-2 rounded mb-2"><h4 class="text-center mb-0">${ok.okupasi}</h4></div>
                    <p style="text-align:justify; margin-top:8px;">${ok.keterangan||'-'}</p>`;
            } else {
                okupasiCard.innerHTML = '<p class="text-center mb-0">Tidak ada data</p>';
            }

            // tampilkan modal
            const modalEl = document.getElementById('unitDetailModal');
            const bsModal = new bootstrap.Modal(modalEl);
            bsModal.show();
            modalEl.addEventListener('shown.bs.modal', function () {
                mapAll.invalidateSize();
                if(allBounds.isValid()) mapAll.fitBounds(allBounds,{padding:[30,30]});
            }, {once:true});
        })
        .catch(err=>console.error(err));
    });

});
</script>


<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/peta/peta_region.blade.php ENDPATH**/ ?>