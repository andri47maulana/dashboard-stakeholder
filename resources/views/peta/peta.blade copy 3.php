
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

    /* Panel overlay kiri atas */
    .tree-panel {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 250px;
        max-height: 600px;
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        overflow-y: auto;
        z-index: 0; /* lebih tinggi dari leaflet control */
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        font-size: 12px;
    }

    .tree-panel h6 {
        margin-bottom: 8px;
        font-size: 13px;
    }
    .tree-panel a {
        cursor: pointer;
        display: block;
        padding: 2px 0;
    }

    /* Tombol reset view */
    .leaflet-control-reset {
        background: white;
        border: 2px solid #ccc;
        border-radius: 4px;
        padding: 4px;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .leaflet-control-reset:hover {
        background: #f0f0f0;
    }
    
    .tree-toggle-btn {
        position: absolute;
        top: 10px;
        left: 260px; /* sejajar dengan panel */
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        z-index: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        font-size: 14px;
    }

    .tree-toggle-btn:hover {
        background: #f5f5f5;
    }
    .info-toggle-btn {
        position: absolute;
        top: 11px;
        left: 520px; /* sejajar dengan panel */
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        z-index: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        font-size: 14px;
    }

    .info-toggle-btn:hover {
        background: #f5f5f5;
    }

    #unitInfo {
        position: absolute;
        top: 10px;
        left: 550px;
        width: 500px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        display: none;
        z-index: 0;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }

    #unitInfo h6 {
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: bold;
    }

    #unitDetail {
        font-size: 12px;
        max-height: 500px;
        overflow-y: auto;
        line-height: 1.4;
    }

    .bg-yellow {
        background-color: #ffff00 !important; /* kuning cerah */
        color: #000 !important; /* teks hitam biar kontras */
    }

</style>
{{-- @extends('layouts.app') --}}

@section('content')
<h1 class="h3 mb-2 text-gray-800">Master Data Kebun</h1>
<p class="mb-4">Klik unit pada tree menu untuk menampilkan polygon di atas peta.</p>

<div class="card shadow mb-4">
    <div class="card-body p-0">
        <div class="row" style="height: 650px;">
            <div class="col-md-12">
                <div class="map-container">
                <!-- Peta -->
                {{-- <div class="col-md-9" style="height:100%;">
                    <div id="map" style="height:100%; width:100%; border:1px solid #ddd; border-radius:4px;"></div>
                </div> --}}
                <div id="map"></div>
                <!-- Panel Tree -->
                <div id="toggleTree" class="tree-toggle-btn">☰</div>
                <div class="tree-panel">
                {{-- <div class="tree-panel" style="height:100%; overflow-y:auto; border-right:1px solid #ddd;"> --}}
                    <ul id="treeMenu" class="list-unstyled small">
                        @foreach($units as $region => $list)
                            <li>
                                <a href="javascript:void(0)" class="region-toggle fw-bold" style="font-size:13px;">
                                    ▸ {{ $region }}
                                </a>
                                <ul class="unit-list ms-3" style="display:none;">
                                    @foreach($list as $unit)
                                        <li>
                                            <a href="javascript:void(0)" class="tree-link" data-unit="{{ $unit->id }}" style="font-size:12px;">
                                                {{ $unit->unit }}
                                            </a>
                                            <ul class="list-unstyled ms-3">
                                                {{-- @foreach($kebunJsons->where('unit_id', $unit->id) as $json)
                                                    <li>
                                                        <a href="javascript:void(0)" class="polygon-link" data-json="{{ $json->id }}" style="font-size:11px; color:#007bff;">
                                                            {{ $json->decoded['name'] ?? 'Polygon '.$json->id }}
                                                        </a>
                                                    </li>
                                                @endforeach --}}
                                            </ul>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div id="toggleInfo" class="info-toggle-btn"></div>
                <div id="unitInfo" class="info-panel">
                    
                    {{-- <h6 id="unitTitle">Info Unit</h6> --}}
                    <div id="unitDetail"></div>
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
    // var map = L.map('map');
    var map = L.map('map', { zoomControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // fokus awal Indonesia
    map.fitBounds([[-11,95],[6,141]]);
    L.control.zoom({ position: 'topright' }).addTo(map);
    // Simpan bounds default Indonesia
    var defaultBounds = L.latLngBounds([[-11,95],[6,141]]);

    // Buat custom control reset
    L.Control.Reset = L.Control.extend({
        onAdd: function(map) {
            var btn = L.DomUtil.create('div', 'leaflet-control-reset');
            btn.innerHTML = '<i class="fas fa-sync-alt"></i>'; // pakai FontAwesome atau teks
            btn.title = "Reset view";

            // cegah map drag saat klik tombol
            L.DomEvent.disableClickPropagation(btn);

            L.DomEvent.on(btn, 'click', function () {
                map.fitBounds(defaultBounds, { padding:[20,20] });
            });

            return btn;
        }
    });

    // Posisi di topright
    L.control.reset = function(opts){
        return new L.Control.Reset(opts);
    }
    L.control.reset({ position:'topright' }).addTo(map);

    var kebunJsons = @json($kebunJsons->map(fn($k) => [
        'id' => $k->id,
        'unit_id' => $k->unit_id,
        'decoded' => $k->decoded
    ]));

    var colors = ["#FF5733","#33C1FF","#28A745","#FFC300","#9B59B6","#E67E22"];
    function getColor(index){ return colors[index % colors.length]; }

    var currentLayers = [];

    // hapus semua layer lama
    function clearPolygons(){
        currentLayers.forEach(l => map.removeLayer(l));
        currentLayers = [];
    }

    // klik polygon link
    $(document).on("click",".polygon-link", function(e){
        e.preventDefault();
        clearPolygons();

        var jsonId = $(this).data("json");
        var jsonData = kebunJsons.find(j => j.id == jsonId);
        if(!jsonData) return;

        var color = getColor(jsonId);

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
            map.fitBounds([
                [jsonData.decoded.bounds[1], jsonData.decoded.bounds[0]],
                [jsonData.decoded.bounds[3], jsonData.decoded.bounds[2]]
            ]);
        }
    });

    // klik unit → tampilkan semua polygon dalam unit
    $(document).on("click",".tree-link", function(e){
        e.preventDefault();
        clearPolygons();

        var unitId = $(this).data("unit");
        var unitJsons = kebunJsons.filter(j => j.unit_id == unitId);

        var allBounds = L.latLngBounds([]);

        unitJsons.forEach((jsonData,index) => {
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
        // === Tampilkan info derajat_hubungan ===
        var allDerajat = @json($derajatHubungan);
        var data = allDerajat[unitId] ?? [];

        if(data.length > 0){
            var html = "";
            data.forEach(function(item){
                // html += `<div style="margin-bottom:6px;">
                //     <b>Tahun:</b> ${item.tahun}<br>
                //     <b>Derajat Hubungan:</b> ${item.derajat_hubungan}<br>
                //     <b>Indeks Kepuasan:</b> ${item.indeks_kepuasan}<br>
                //     <b>Prioritas:</b> ${item.prioritas_socmap}<br>
                //     <b>Deskripsi:</b> ${item.deskripsi}
                // </div><hr>`;
                // Fungsi untuk mapping warna
                function getBgClass(val) {
                    switch (val) {
                        case "P1": return "bg-danger text-white";   // merah
                        case "P2": return "bg-warning text-white";  // oranye
                        case "P3": return "bg-yellow text-dark";   // kuning
                        case "P4": return "bg-success text-white";  // hijau
                        default:   return "bg-secondary text-white"; // default abu-abu
                    }
                }

                // Pemakaian
                let bgHubungan = getBgClass(item.derajat_hubungan);
                let bgKepuasan = getBgClass(item.derajat_kepuasan);
                let bgSocmap = getBgClass(item.prioritas_socmap);
                html += `<div class="row">
                        <div class="col-md-12">
                            <div class="card mb-3 shadow-sm">
                                
                                <div class="card-header bg-info text-white" align="center">Data Analisis Hubungan Stakeholder</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x:auto;">
                                                <h6 align="center">Derajat Hubungan</h6>
                                                <div class="${bgHubungan}" ><h4 align="center">${item.derajat_hubungan}</h4></div>
                                                <p align="justify">${item.deskripsi}</p>
                                            </div>
                                        </div>
                                    </div>               
                                </div>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-info text-white" align="center">Indek Kepuasan Stakeholder</div>
                                <div class="card-body" style="font-size:0.9em;">                                    
                                    <table style="font-size:0.9em;">
                                        <tr>
                                            <td style="width: 150px;">Kepuasan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.kepuasan}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Kontribusi</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.kontribusi}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Komunikasi</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.komunikasi}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Kepercayaan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.kepercayaan}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Keterlibatan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.keterlibatan}</td>
                                        </tr>                                        
                                        <tr>
                                            <td style="width: 150px;">Total</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.indeks_kepuasan}</td>
                                        </tr> 
                                    </table>
                                    <div class="${bgKepuasan}" ><h4 align="center">${item.derajat_kepuasan}</h4></div>
                                </div>
                            </div>
                            

                            
                        </div>

                        <div class="col-md-6">
                        <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-info text-white">Social Mapping</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <table style="font-size:0.9em;">
                                        <tr>
                                            <td style="width: 150px;">Lingkungan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.lingkungan}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Ekonomi</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.ekonomi}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Pendidikan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.pendidikan}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Sosial Kesesjahteraan</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.sosial_kesesjahteraan}</td>
                                        </tr>
                                        <tr>
                                            <td style="width: 150px;">Okupasi</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.okupasi}</td>
                                        </tr>                                        
                                        <tr>
                                            <td style="width: 150px;">Total</td>
                                            <td style="width: 10px;">:</td>
                                            <td>${item.skor_socmap}</td>
                                        </tr>     
                                    </table>
                                    <div class="${bgSocmap}" ><h4 align="center">${item.prioritas_socmap}</h4></div>
                                    
                                </div>
                            </div>

                            
                        </div>
                    </div>`;
                    // html2 += `☰`;
            });

            // $("#unitTitle").text("Info Unit ID: " + unitId);
            $("#unitDetail").html(html);
            // $("#toggleInfo").html(`☰`);
            // $("#toggleInfo").show();
             $("#toggleInfo").css("left", "520px").text("☰"); 
            $("#unitInfo").show();
            
        } else {
            // $("#unitTitle").text("Info Unit ID: " + unitId);
            // html2 += `☰`;
            $("#unitDetail").html("<i>Tidak ada data derajat hubungan</i>");
            // $("#toggleInfo").html(`☰`);
            $("#toggleInfo").css("left", "520px").text("☰"); 
            // $("#toggleInfo").show();
            $("#unitInfo").show();
        }
    });
    // Expand/collapse region
    $(document).on("click", ".region-toggle", function(e){
        e.preventDefault();
        var $icon = $(this);
        var $list = $icon.next(".unit-list");

        $list.slideToggle(200);

        // ganti icon ▸ ↘
        if ($icon.text().trim().startsWith("▸")) {
            $icon.text($icon.text().replace("▸", "▾"));
        } else {
            $icon.text($icon.text().replace("▾", "▸"));
        }
    });

    // toggle tree panel
    $(document).on("click", "#toggleTree", function(){
        $(".tree-panel").toggle();
        if ($(".tree-panel").is(":visible")) {
            $(this).css("left", "270px").text("☰"); // posisi di samping panel
        } else {
            $(this).css("left", "10px").text("⮞"); // geser ke kiri, icon berubah
        }
    });

    $(document).on("click", "#toggleInfo", function(){
    $(".info-panel").toggle(); // tampilkan/sembunyikan panel
    if ($(".info-panel").is(":visible")) {
        // geser tombol ke kanan sejauh width panel misal 500px
        $(this).css("left", "520px").text("☰"); 
    } else {
        // kembali ke posisi awal
        $(this).css("left", "1020px").text("⮞"); 
    }
});


});
</script>
@endsection




