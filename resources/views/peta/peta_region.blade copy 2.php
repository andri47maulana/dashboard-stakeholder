
@extends('layouts.app')
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
{{-- @extends('layouts.app') --}}

@section('content')
<h1 class="h3 mb-2 text-gray-800">Derajat Hubungan Regional</h1>
{{-- <p class="mb-4">Klik unit pada tree menu untuk menampilkan polygon di atas peta.</p> --}}


<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <label>Tahun</label>
                <select name="tahun" id="tahun" class="form-control select2" required>
                    <option value="">Pilih Tahun</option>
                    @foreach($tahun as $t)
                        <option value="{{ $t }}" {{ $t == ($yearNow-1) ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label>Unit</label>
                <select name="id_unit" id="id_unit" class="form-control select2" required>
                    <option value="">Pilih Unit</option>
                    @foreach($units as $u)
                        <option value="{{ $u->id }}">{{ $u->unit }}</option>
                    @endforeach
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
                        <h6 class="text-center">Derajat Hubungan {{ $region }}</h6>
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
                            <tr><td>Kepuasan</td><td id="avg_kepuasan">{{ $rataRata->avg_kepuasan ?? '-' }}</td></tr>
                            <tr><td>Kontribusi</td><td id="avg_kontribusi">{{ $rataRata->avg_kontribusi ?? '-' }}</td></tr>
                            <tr><td>Komunikasi</td><td id="avg_komunikasi">{{ $rataRata->avg_komunikasi ?? '-' }}</td></tr>
                            <tr><td>Kepercayaan</td><td id="avg_kepercayaan">{{ $rataRata->avg_kepercayaan ?? '-' }}</td></tr>
                            <tr><td>Keterlibatan</td><td id="avg_keterlibatan">{{ $rataRata->avg_keterlibatan ?? '-' }}</td></tr>
                            <tr class="fw-bold"><td>Total Skor</td><td id="avg_indeks">{{ $rataRata->avg_indeks_kepuasan ?? '-' }}</td></tr>
                            <tr class="fw-bold"><td>Jumlah Stakeholder</td><td id="jlhUnit">{{ $jlhUnit ?? '-' }}</td></tr>
                        </table>
                    </div>
                </div>

                <!-- Line Chart -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="mb-3 text-center">Tren Hubungan Stakeholder {{ $region }}</h6>
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
        {{-- <h6 id="unitName" style="text-align: center;"></h6>
        <p style="text-align: center;"><strong>Tahun:</strong> <span id="unitYear"></span></p> --}}

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
document.addEventListener("DOMContentLoaded", function(){
    var map = L.map('map', { zoomControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // fokus awal Indonesia
    map.fitBounds([[-11,95],[6,141]]);
    L.control.zoom({ position: 'topright' }).addTo(map);
    
    var kebunJsons = @json($kebunJsons->map(fn($k) => [
        'id' => $k->id,
        'unit_id' => $k->unit_id,
        'decoded' => $k->decoded
    ]));

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
<script>
document.addEventListener("DOMContentLoaded", function() {

    const region = "{{ $region }}";
    let selectedYear = parseInt(document.getElementById('tahun').value) || {{ $yearNow-1 }};
    let selectedUnitId = null;

    // --- MAP ---
    

    // --- CHARTS ---
    let pieCtx = document.getElementById('pieChartPrioritas').getContext('2d');
    let pieChart = new Chart(pieCtx, {
        type: 'pie',
        data: { labels:['P1','P2','P3','P4'], datasets:[{ label:'Jumlah', data:[0,0,0,0], backgroundColor:['#ff0000d5','#fd7e14','#ffff00','#28a745'], borderColor:'#fff', borderWidth:2 }] },
        options:{ responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });

    let lineCtx = document.getElementById('lineChart').getContext('2d');
    let lineChart = new Chart(lineCtx, {
        type: 'line',
        data: { labels:[], datasets:[] },
        options:{ responsive:true, scales:{ y:{ beginAtZero:true, ticks:{ precision:0 } } } }
    });

    // --- AJAX FETCH DATA UNTUK TAHUN TERPILIH ---
    function loadDataByYear(year){
        fetch(`/peta/peta_region/data/${region}/${year}`)
        .then(res=>res.json())
        .then(data=>{
            // Update pie
            pieChart.data.datasets[0].data = [
                data.jumlahPrioritas.total_p1,
                data.jumlahPrioritas.total_p2,
                data.jumlahPrioritas.total_p3,
                data.jumlahPrioritas.total_p4
            ];
            pieChart.update();

            // Update line chart
            lineChart.data.labels = data.countsPerYear.map(i=>i.tahun);
            lineChart.data.datasets = [
                {label:'P1', data:data.countsPerYear.map(i=>i.total_p1), borderColor:'#ff0000d5', backgroundColor:'#ff0000d5', tension:0.3},
                {label:'P2', data:data.countsPerYear.map(i=>i.total_p2), borderColor:'#fd7e14', backgroundColor:'#fd7e14', tension:0.3},
                {label:'P3', data:data.countsPerYear.map(i=>i.total_p3), borderColor:'#ffff00', backgroundColor:'#ffff00', tension:0.3},
                {label:'P4', data:data.countsPerYear.map(i=>i.total_p4), borderColor:'#28a745', backgroundColor:'#28a745', tension:0.3},
            ];
            lineChart.update();

            // Update tabel rata-rata
            document.getElementById('avg_kepuasan').innerText = data.rataRata.avg_kepuasan ?? '-';
            document.getElementById('avg_kontribusi').innerText = data.rataRata.avg_kontribusi ?? '-';
            document.getElementById('avg_komunikasi').innerText = data.rataRata.avg_komunikasi ?? '-';
            document.getElementById('avg_kepercayaan').innerText = data.rataRata.avg_kepercayaan ?? '-';
            document.getElementById('avg_keterlibatan').innerText = data.rataRata.avg_keterlibatan ?? '-';
            document.getElementById('avg_indeks').innerText = data.rataRata.avg_indeks_kepuasan ?? '-';
            document.getElementById('jlhUnit').innerText = data.jlhUnit ?? '-';
        })
        .catch(err=>console.error(err));
    }

    // --- PANGGIL DATA TAHUN DEFAULT SAAT HALAMAN LOAD ---
    loadDataByYear(selectedYear);

    // --- EVENT SELECT ---
    document.getElementById('tahun').addEventListener('change', function(){
        selectedYear = parseInt(this.value);
        loadDataByYear(selectedYear);
    });

    let mapAll=null;
    document.getElementById('id_unit').addEventListener('change', function(){
       
// batas map
        selectedUnitId = this.value;
        if(selectedUnitId) {
            fetch(`/peta/unit/detail/${selectedUnitId}/${selectedYear}`)
            .then(res=>res.json())
            .then(data=>{
                // ------------ MAP ------------
                // const kebunJsons = data.kebunJsons; // ambil dari hasil fetch
                // var colors = ["#FF5733","#33C1FF","#28A745","#FFC300","#9B59B6","#E67E22"];

                // function getColor(index){ return colors[index % colors.length]; }

                // // Hapus map lama
                // if(mapAll){ mapAll.remove(); mapAll = null; }

                // // Buat map baru
                // mapAll = L.map('mapContainerAll');
                // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAll);

                // var allBounds = L.latLngBounds([]);
                // var legendHtml = '<div class="map-legend"><b>Legend:</b><br>';

                // kebunJsons.forEach((json, index) => {
                //     var color = getColor(index);

                //     L.vectorGrid.protobuf(json.tileurl, {
                //         vectorTileLayerStyles: {
                //             [json.id || index]: {
                //                 weight: 3,
                //                 color: color,
                //                 fill: true,
                //                 fillColor: color,
                //                 fillOpacity: 0.5
                //             }
                //         },
                //         interactive: false
                //     }).addTo(mapAll);

                //     if(json.bounds?.length === 4){
                //         allBounds.extend([[json.bounds[1],json.bounds[0]], [json.bounds[3],json.bounds[2]]]);
                //     }

                //     legendHtml += `<div><span class="legend-color" style="background:${color}"></span>${json.name}</div>`;
                // });

                // if(allBounds.isValid()){
                //     // mapAll.fitBounds(allBounds, { padding:[30,30] });
                //     mapAll.fitBounds(allBounds, { padding: [50,50], maxZoom: 12 });
                //     mapAll.setZoom(mapAll.getZoom() - 1);

                // }

                // // Legend
                // var legend = L.control({position: 'topright'});
                // legend.onAdd = function() {
                //     var div = L.DomUtil.create('div', 'map-legend');
                //     div.innerHTML = legendHtml;
                //     return div;
                // };
                // legend.addTo(mapAll);
                
                // mapAll.invalidateSize();
                // setTimeout(() => mapAll.invalidateSize(), 300);
                const kebunJsons = data.kebunJsons; // ambil dari hasil fetch

               
                let color = "#0084ff"; 
                if (data.derajatHubungan && data.derajatHubungan.derajat_hubungan) {
                    switch (data.derajatHubungan.derajat_hubungan) {
                        case "P1":
                            color = "#dc3545";   
                            break;
                        case "P2":
                            color = "#fd7e14";  
                            break;
                        case "P3":
                            color = "#ffff00";   
                            break;
                        case "P4":
                            color = "#28a745";   
                            break;
                    }
                }

                console.log("Derajat:", data.derajatHubungan?.derajat_hubungan, "=> Warna:", color);

                // Hapus map lama
                if (mapAll) { 
                    mapAll.remove(); 
                    mapAll = null; 
                }

                // Buat map baru
                mapAll = L.map('mapContainerAll');
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAll);

                var allBounds = L.latLngBounds([]);
                var legendHtml = '<div class="map-legend"><b>Legend:</b><br>';

                kebunJsons.forEach((json, index) => {
                    L.vectorGrid.protobuf(json.tileurl, {
                        vectorTileLayerStyles: {
                            [json.id || index]: {
                                weight: 3,
                                color: color,
                                fill: true,
                                fillColor: color,
                                fillOpacity: 0.5
                            }
                        },
                        interactive: false
                    }).addTo(mapAll);

                    if (json.bounds?.length === 4) {
                        allBounds.extend([
                            [json.bounds[1], json.bounds[0]], 
                            [json.bounds[3], json.bounds[2]]
                        ]);
                    }
                });

                // Legend (hanya tampilkan 1 warna global)
                legendHtml += `<div><span class="legend-color" style="background:${color}"></span> Semua Polygon</div>`;

                if (allBounds.isValid()) {
                    mapAll.fitBounds(allBounds, { padding: [50, 50], maxZoom: 12 });
                    mapAll.setZoom(mapAll.getZoom() - 1);
                }

                // Legend
                var legend = L.control({ position: 'topright' });
                legend.onAdd = function () {
                    var div = L.DomUtil.create('div', 'map-legend');
                    div.innerHTML = legendHtml;
                    return div;
                };
                legend.addTo(mapAll);

                mapAll.invalidateSize();
                setTimeout(() => mapAll.invalidateSize(), 300);



                document.getElementById('unitDetailModalLabel').innerText = data.unit.unit + ' Tahun ' + selectedYear;
                // document.getElementById('unitYear').innerText = selectedYear;
                // console.log(data.derajatHubungan.derajat_hubungan);
                const dh = data.derajatHubungan;
                document.getElementById('deskripsi').innerText = dh?.deskripsi ?? '-';

                

                const tbody = document.querySelector('#sasarandesa tbody');
                tbody.innerHTML = '';
                if(data.desa && data.desa.length > 0){
                    data.desa.forEach(item => {
                        tbody.innerHTML += `
                            <tr>
                                <td>${item.nama ?? '-'}</td>
                                <td>${item.isu_utama ?? '-'}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbody.innerHTML = `<tr><td colspan="2" class="text-center">Tidak ada data desa</td></tr>`;
                }
                // isu details
                const tbodyCard = document.querySelector('#isuCardTable tbody');
                tbodyCard.innerHTML = ''; // kosongkan dulu
                if(data.isu && data.isu.length > 0){
                    data.isu.forEach(item => {
                        tbodyCard.innerHTML += `
                            <tr>
                                <td>${item.isu ?? '-'}</td>
                                <td>${item.keterangan ?? '-'}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbodyCard.innerHTML = `<tr><td colspan="2" class="text-center">Tidak ada data isu</td></tr>`;
                }

                // isu lembaga
                const tbodyCard1 = document.querySelector('#isuLembagaTable tbody');
                tbodyCard1.innerHTML = ''; // kosongkan dulu
                if(data.lembaga && data.lembaga.length > 0){
                    data.lembaga.forEach(item => {
                        tbodyCard1.innerHTML += `
                            <tr>
                                <td>${item.nama ?? '-'}</td>
                                <td>${item.program ?? '-'}</td>
                            </tr>
                        `;
                    });
                } else {
                    tbodyCard1.innerHTML = `<tr><td colspan="2" class="text-center">Tidak ada data lembaga/instansi</td></tr>`;
                }

                function getBgClass(val) {
                    switch (val) {
                        case "Tinggi": return "bg-danger text-white";
                        case "Sedang": return "bg-warning text-dark"; // bg-yellow diganti bg-warning bootstrap
                        case "Rendah": return "bg-success text-white";
                        default: return "bg-secondary text-white";
                    }
                }

                // Ambil data okupasi dari response
                const okArray = data.okupasi; 
                const okupasiCard = document.getElementById('okupasiCardBody');

                // Kosongkan dulu
                okupasiCard.innerHTML = '';

                // Tambahkan konten
                if(okArray && okArray.length > 0){
                    const ok = okArray[0]; // ambil item pertama
                    let bgOkupasi = getBgClass(ok.okupasi);
                    okupasiCard.innerHTML = `
                        <div class="${bgOkupasi} p-2 rounded mb-2">
                            <h4 class="text-center mb-0">${ok.okupasi}</h4>
                        </div>
                        <p style="text-align:justify; margin-top:8px;">${ok.keterangan || '-'}</p>
                    `;
                } else {
                    okupasiCard.innerHTML = '<p class="text-center mb-0">Tidak ada data</p>';
                }


                // new bootstrap.Modal(document.getElementById('unitDetailModal')).show();
                const modalEl = document.getElementById('unitDetailModal');
                const bsModal = new bootstrap.Modal(modalEl);
                bsModal.show();

                modalEl.addEventListener('shown.bs.modal', function () {
                    if (mapAll) {
                        mapAll.invalidateSize();
                        if (allBounds && allBounds.isValid()) {
                            mapAll.fitBounds(allBounds, { padding: [30,30] });
                        }
                    }
                }, { once: true }); // {once:true} biar gak nambah event listener berkali-kali

//                 

            })
            .catch(err=>console.error(err));
        }
    });

});


</script>

@endsection




