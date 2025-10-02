
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
    }

    .tree-panel {
        position: absolute;
        top: 10px;
        left: 10px;
        width: 250px;
        max-height: calc(100% - 20px); /* Agar tidak melebihi tinggi peta */
        background: white;
        border: 1px solid #ddd;
        border-radius: 6px;
        padding: 10px;
        overflow-y: auto;
        z-index: 1000; /* Pastikan di atas peta */
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        font-size: 12px;
    }

    .tree-toggle-btn {
        position: absolute;
        top: 10px;
        left: 260px; /* Posisi awal saat panel terlihat */
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        font-size: 14px;
        transition: left 0.2s; /* Animasi halus */
    }

    /* CSS BARU: Menggunakan 'right' untuk posisi menempel di kanan */
    #unitInfo {
        position: absolute;
        top: 10px;
        right: 58px; /* Menempel 10px dari kanan */
        left: auto;  /* Hapus properti left */
        width: 500px;
        background: white;
        border: 1px solid #ccc;
        border-radius: 6px;
        padding: 10px;
        display: none; /* Sembunyi secara default */
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        max-height: calc(100% - 20px);
    }
    
    /* CSS BARU: Tombol toggle juga menempel di kanan */
    .info-toggle-btn {
        position: absolute;
        top: 10px;
        right: 558px; /* Posisi awal: 500px (lebar panel) + 10px (jarak) + 10px (jarak) */
        left: auto;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 4px 8px;
        cursor: pointer;
        z-index: 1000;
        box-shadow: 0 2px 6px rgba(0,0,0,0.2);
        font-size: 14px;
        display: none; /* Sembunyi juga secara default */
        transition: right 0.2s; /* Animasi halus */
    }

    #unitDetail {
        font-size: 12px;
        max-height: 500px;
        overflow-y: auto;
        line-height: 1.4;
    }

    .leaflet-control-reset {
        background: white; border: 2px solid #ccc; border-radius: 4px;
        padding: 4px; cursor: pointer; box-shadow: 0 2px 6px rgba(0,0,0,0.2);
    }
    .leaflet-control-reset:hover { background: #f0f0f0; }
    .bg-yellow { background-color: #ffff00 !important; color: #000 !important; }
    .tree-panel h6 { margin-bottom: 8px; font-size: 13px; }
    .tree-panel a { cursor: pointer; display: block; padding: 2px 0; }
    #unitInfo h6 { margin-bottom: 5px; font-size: 14px; font-weight: bold; }

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
                <div id="toggleInfo" class="info-toggle-btn">☰</div>
                <!-- <div id="unitInfo" class="info-panel">
                    
                    {{-- <h6 id="unitTitle">Info Unit</h6> --}}
                    <div id="unitDetail"></div>
                </div> -->
                <div id="unitInfo" class="info-panel text-center">
                    <h6 class="mb-2">
                        <span id="unitName" class="fw-bold text-primary"></span> - 
                        <span id="regionName" class="text-muted"></span>
                    </h6>

                    <div class="d-flex justify-content-center align-items-center gap-2 mb-2 w-100">
                        <button id="prevYear" class="btn btn-sm btn-outline-secondary">⬅️</button>
                        <select id="tahunSelect" class="form-select form-select-sm text-center" style="max-width:150px; font-size:12px;">
                            <!-- tahun akan diisi lewat JS -->
                        </select>
                        <button id="nextYear" class="btn btn-sm btn-outline-secondary">➡️</button>
                    </div>

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
    // var map = L.map('map', { zoomControl: false });
    // L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
    //     attribution: '&copy; OpenStreetMap contributors',
    //     maxZoom: 19
    // }).addTo(map);
    
    // // fokus awal Indonesia
    // map.fitBounds([[-11,95],[6,141]]);
    // L.control.zoom({ position: 'topright' }).addTo(map);
    // var defaultBounds = L.latLngBounds([[-11,95],[6,141]]);

    // // tombol reset
    // L.Control.Reset = L.Control.extend({
    //     onAdd: function(map) {
    //         var btn = L.DomUtil.create('div', 'leaflet-control-reset');
    //         btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
    //         btn.title = "Reset view";
    //         L.DomEvent.disableClickPropagation(btn);
    //         L.DomEvent.on(btn, 'click', function () {
    //             map.fitBounds(defaultBounds, { padding:[20,20] });
    //         });
    //         return btn;
    //     }
    // });
    // L.control.reset = function(opts){ return new L.Control.Reset(opts); }
    // L.control.reset({ position:'topright' }).addTo(map);
    var map = L.map('map', { zoomControl: false });

    // 1. Definisikan Konfigurasi Basemap
    const basemapConfig = {
        osm: {
            url: 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',
            options: { attribution: '&copy; OpenStreetMap contributors', maxZoom: 19 }
        },
        satellite: {
            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}',
            options: { attribution: 'Tiles &copy; Esri &mdash; Source: Esri...', maxZoom: 19 }
        },
        topo: {
            url: 'https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png',
            options: { attribution: 'Map data: &copy; OSM contributors, SRTM | Map style: &copy; OpenTopoMap (CC-BY-SA)', maxZoom: 17 }
        }
    };
    
    // 2. Tambahkan Kontrol Pilihan Basemap
    const baseLayers = {
        "Peta Jalan": L.tileLayer(basemapConfig.osm.url, basemapConfig.osm.options),
        "Citra Satelit": L.tileLayer(basemapConfig.satellite.url, basemapConfig.satellite.options),
        "Topografi": L.tileLayer(basemapConfig.topo.url, basemapConfig.topo.options)
    };
    baseLayers["Peta Jalan"].addTo(map);
    L.control.layers(baseLayers).addTo(map);
    
    // Inisialisasi peta lainnya
    map.fitBounds([[-11,95],[6,141]]);
    L.control.zoom({ position: 'topright' }).addTo(map);
    var defaultBounds = L.latLngBounds([[-11,95],[6,141]]);

    // tombol reset
    L.Control.Reset = L.Control.extend({
        onAdd: function(map) {
            var btn = L.DomUtil.create('div', 'leaflet-control-reset');
            btn.innerHTML = '<i class="fas fa-sync-alt"></i>';
            btn.title = "Reset view";
            L.DomEvent.disableClickPropagation(btn);
            L.DomEvent.on(btn, 'click', function () {
                map.fitBounds(defaultBounds, { padding:[20,20] });
            });
            return btn;
        }
    });
    L.control.reset = function(opts){ return new L.Control.Reset(opts); }
    L.control.reset({ position:'topright' }).addTo(map);

    var kebunJsons = @json($kebunJsons->map(fn($k) => [
        'id' => $k->id,
        'unit_id' => $k->unit_id,
        'decoded' => $k->decoded
    ]));

    var colors = ["#FF5733","#33C1FF","#28A745","#FFC300","#9B59B6","#E67E22"];
    function getColor(index){ return colors[index % colors.length]; }

    var currentLayers = [];
    var selectedUnitId = null;
    var selectedYear = null;

    function clearPolygons(){
        currentLayers.forEach(l => map.removeLayer(l));
        currentLayers = [];
    }

    function drawPolygons() {
        clearPolygons();
        
        kebunJsons.forEach((jsonData, index) => {
            if (!jsonData.decoded || !jsonData.decoded.tileurl || !jsonData.decoded.id) {
                console.error("Data poligon tidak lengkap:", jsonData);
                return;
            }

            let polygonColor = getColor(index);

            let layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
                vectorTileLayerStyles: {
                    [jsonData.decoded.id]: {
                        weight: 2,
                        color: polygonColor,
                        fill: true,
                        fillColor: polygonColor,
                        fillOpacity: 0.4
                    }
                }
            }).addTo(map);

            // --- [PERBAIKAN UTAMA DI SINI] ---
            // Secara paksa mengatur z-index dari container layer poligon ini
            // agar selalu di atas basemap (yang z-indexnya ~200).
            // Kita tidak lagi menggunakan sistem pane kustom.
            if (layer.getContainer) {
                layer.getContainer().style.zIndex = 450;
            }

            currentLayers.push(layer);
        });
    }

    // Panggil fungsi untuk menggambar poligon saat halaman pertama kali dimuat
    drawPolygons();

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

    // helper tahun default
    function getDefaultYear() {
        return String(new Date().getFullYear() - 1);
    }

    // render isi detail
    function renderUnitDetailIntoContainer(data, tahun) {
        selectedYear = tahun; // update global tahun
        let filtered = data.filter(item => String(item.tahun) === String(tahun));
        if(filtered.length === 0) {
            $("#unitDetail").html("<i>Tidak ada data untuk tahun ini.</i>");
            return;
        }

        let html = "";
        filtered.forEach(function(item){
            function getBgClass(val) {
                switch (val) {
                    case "P1": return "bg-danger text-white";
                    case "P2": return "bg-warning text-white";
                    case "P3": return "bg-yellow text-dark";
                    case "P4": return "bg-success text-white";
                    default:   return "bg-secondary text-white";
                }
            }

            let bgHubungan = getBgClass(item.derajat_hubungan);
            let bgKepuasan = getBgClass(item.derajat_kepuasan);
            let bgSocmap   = getBgClass(item.prioritas_socmap);

            html += `<div class="row">
                <div class="col-md-12">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-info text-white text-center">Data Analisis Hubungan Stakeholder</div>
                        <div class="card-body" style="font-size:0.9em;">
                            <h6 class="text-center">Derajat Hubungan</h6>
                            <div class="${bgHubungan}"><h4 class="text-center mb-0">${item.derajat_hubungan}</h4></div>
                            <p style="text-align:justify; margin-top:8px;">${item.deskripsi || '-'}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-info text-white text-center">Indeks Kepuasan Stakeholder</div>
                        <div class="card-body" style="font-size:0.9em;">
                            <table style="font-size:0.9em;">
                                <tr><td style="width:150px;">Kepuasan</td><td>:</td><td>${item.kepuasan ?? '-'}</td></tr>
                                <tr><td>Kontribusi</td><td>:</td><td>${item.kontribusi ?? '-'}</td></tr>
                                <tr><td>Komunikasi</td><td>:</td><td>${item.komunikasi ?? '-'}</td></tr>
                                <tr><td>Kepercayaan</td><td>:</td><td>${item.kepercayaan ?? '-'}</td></tr>
                                <tr><td>Keterlibatan</td><td>:</td><td>${item.keterlibatan ?? '-'}</td></tr>
                                <tr><td>Total</td><td>:</td><td>${item.indeks_kepuasan ?? '-'}</td></tr>
                            </table>
                            <div class="${bgKepuasan} text-center mt-2"><h4 class="mb-0">${item.derajat_kepuasan ?? '-'}</h4></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-info text-white text-center">Social Mapping</div>
                        <div class="card-body" style="font-size:0.9em;">
                            <table style="font-size:0.9em;">
                                <tr><td style="width:150px;">Lingkungan</td><td>:</td><td>${item.lingkungan ?? '-'}</td></tr>
                                <tr><td>Ekonomi</td><td>:</td><td>${item.ekonomi ?? '-'}</td></tr>
                                <tr><td>Pendidikan</td><td>:</td><td>${item.pendidikan ?? '-'}</td></tr>
                                <tr><td>Sosial</td><td>:</td><td>${item.sosial_kesesjahteraan ?? '-'}</td></tr>
                                <tr><td>Okupasi</td><td>:</td><td>${item.okupasi ?? '-'}</td></tr>
                                <tr><td>Total</td><td>:</td><td>${item.skor_socmap ?? '-'}</td></tr>
                            </table>
                            <div class="${bgSocmap} text-center mt-2"><h4 class="mb-0">${item.prioritas_socmap ?? '-'}</h4></div>
                        </div>
                    </div>
                </div>
            </div>`;
        });
        $("#unitDetail").html(html);
    }

    // setup dropdown + prev/next
    function setupYearControlsForUnit(dataForUnit) {
        let tahunList = [...new Set(dataForUnit.map(it => String(it.tahun)))].sort((a,b) => Number(b) - Number(a));
        if(tahunList.length === 0) {
            $("#tahunSelect").empty();
            $("#unitDetail").html("<i>Tidak ada data</i>");
            return;
        }

        $("#tahunSelect").html(tahunList.map(t => `<option value="${t}">${t}</option>`).join(""));

        let prefer = getDefaultYear();
        let defaultYear = tahunList.includes(prefer) ? prefer : tahunList[0];
        $("#tahunSelect").val(defaultYear);

        renderUnitDetailIntoContainer(dataForUnit, defaultYear);

        $("#tahunSelect").off("change").on("change", function(){
            renderUnitDetailIntoContainer(dataForUnit, $(this).val());
        });

        $("#prevYear").off("click").on("click", function(){
            let cur = String($("#tahunSelect").val());
            let idx = tahunList.indexOf(cur);
            if(idx < tahunList.length - 1) {
                $("#tahunSelect").val(tahunList[idx + 1]).trigger("change");
            }
        });
        $("#nextYear").off("click").on("click", function(){
            let cur = String($("#tahunSelect").val());
            let idx = tahunList.indexOf(cur);
            if(idx > 0) {
                $("#tahunSelect").val(tahunList[idx - 1]).trigger("change");
            }
        });
    }

    // klik unit
$(document).on("click",".tree-link", function(e){
    e.preventDefault();
    clearPolygons();
    selectedUnitId = $(this).data("unit"); // <<< simpan unit global
    const unitName = $(this).text();
    const regionName = $(this)
    .closest("ul.unit-list")
    .prev(".region-toggle")
    .text()
    .replace(/▸|▾/g, "") // buang simbol
    .trim();


    // isi nama unit & region
    $("#unitName").text(unitName);
    $("#regionName").text(regionName);
    $("#unitInfo").show();
    $("#toggleInfo").show().css('right', '558px').text("☰");
    // reset isi info panel setiap kali klik unit baru
    $("#unitDetail").empty();
    $("#tahunSelect").empty();
    $("#unitInfo").hide(); // sembunyikan dulu, nanti ditampilkan ulang

    var unitJsons = kebunJsons.filter(j => j.unit_id == selectedUnitId);
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
        if (layer.getContainer) {
                layer.getContainer().style.zIndex = 450;
            }
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

    var allDerajat = @json($derajatHubungan);
    var dataForUnit = allDerajat[selectedUnitId] || [];
        if(dataForUnit.length > 0){
            setupYearControlsForUnit(dataForUnit);
            $("#unitInfo, #toggleInfo").show(); // Tampilkan jika ada data
        } else {
            $("#unitDetail").html('<div class="text-center p-3"><i>Tidak ada data derajat hubungan untuk unit ini.</i></div>');
            $("#tahunSelect").empty();
            $("#unitInfo, #toggleInfo").show();
        }
});


    // expand/collapse region
    $(document).on("click", ".region-toggle", function(e){
        e.preventDefault();
        var $icon = $(this);
        var $list = $icon.next(".unit-list");
        $list.slideToggle(200);
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
            $(this).css("left", "260px").text("☰");
        } else {
            $(this).css("left", "10px").text("⮞");
        }
    });

    // toggle info panel
    $("#toggleInfo").on("click", function(){
        $("#unitInfo").toggle();
        if ($("#unitInfo").is(":visible")) {
            $(this).css('right', '558px').text("☰"); // 500px lebar + 20px buffer
        } else {
            $(this).css('right', '58px').text("⮜"); // Panah ke kiri
        }
    });
});
</script>

@endsection




