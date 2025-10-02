{{-- @extends('layouts.app')

@section('content')
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row" style="height: 650px;">
            <div class="col-md-12">
                <div class="map-container">
                    <div id="map"></div>
                    <div class="tree-panel">
                        <h6>Daftar Area</h6>
                        <ul class="list-unstyled">
                            @foreach($units as $region => $list)
                                <li>
                                    <a href="javascript:void(0)" class="region-toggle" style="font-size:13px; font-weight:bold;">
                                        ▸ {{ $region }}
                                    </a>
                                    <ul class="unit-list ms-3" style="display:none;">
                                        @foreach($list as $unit)
                                            <li>
                                                <a href="javascript:void(0)" 
                                                class="polygon-link" 
                                                data-id="{{ $unit->id }}" 
                                                style="font-size:12px; text-decoration:none; color:#007bff;">
                                                    {{ $unit->unit }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

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
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    var map = L.map('map', { zoomControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);

    var indonesiaBounds = [[-11.0, 95.0], [6.0, 141.0]];
    map.fitBounds(indonesiaBounds);
    L.control.zoom({ position: 'topright' }).addTo(map);

    var polygonLayers = [];

    // expand/collapse region
    document.querySelectorAll(".region-toggle").forEach(region => {
        region.addEventListener("click", function() {
            let ul = this.nextElementSibling;
            if (ul.style.display === "none") {
                ul.style.display = "block";
                this.innerHTML = this.innerHTML.replace("▸", "▾");
            } else {
                ul.style.display = "none";
                this.innerHTML = this.innerHTML.replace("▾", "▸");
            }
        });
    });

    // klik unit → ambil polygon dari DB
    document.querySelectorAll(".polygon-link").forEach(link => {
        link.addEventListener("click", function() {
            var unitId = this.dataset.id;

            // hapus polygon sebelumnya
            polygonLayers.forEach(layer => map.removeLayer(layer));
            polygonLayers = [];

            fetch(`/peta/polygons/${unitId}`)
                .then(res => res.json())
                .then(data => {
                    if (!data || data.length === 0) {
                        alert("Polygon tidak ditemukan untuk unit ini");
                        return;
                    }

                    data.forEach(poly => {
                        // tileurl dari DB
                        if (poly.tileurl) {
                            let vector = L.tileLayer(poly.tileurl, {
                                minZoom: poly.minzoom || 0,
                                maxZoom: poly.maxzoom || 22
                            }).addTo(map);
                            polygonLayers.push(vector);

                            // zoom ke bounds kalau ada
                            if (poly.bounds) {
                                let b = poly.bounds;
                                map.fitBounds([[b[1], b[0]], [b[3], b[2]]]);
                            }
                        }
                    });
                })
                .catch(err => console.error(err));
        });
    });
});

</script>
@endsection
`` --}}





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
</style>
{{-- @extends('layouts.app') --}}

@section('content')
<h1 class="h3 mb-2 text-gray-800">Master Data Kebun</h1>
<p class="mb-4">Klik unit pada tree menu untuk menampilkan polygon di atas peta.</p>

<div class="card shadow mb-4">
    <div class="card-body p-0">
        <div class="p-3" style="height: 700px; position: relative;">
            <div class="row h-100">
                <!-- Panel Tree -->
                <div class="col-md-3" style="height:100%; overflow-y:auto; border-right:1px solid #ddd;">
                    <ul id="treeMenu" class="list-unstyled small">
                        @foreach($units as $unit)
                            <li>
                                <a href="#" class="tree-link" data-unit="{{ $unit->id }}">
                                    {{ $unit->unit }}
                                </a>
                                <ul class="list-unstyled ms-3">
                                    @foreach($kebunJsons->where('unit_id', $unit->id) as $json)
                                        <li>
                                            <a href="#" class="polygon-link" data-json="{{ $json->id }}">
                                                {{ $json->decoded['name'] ?? 'Polygon '.$json->id }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Peta -->
                <div class="col-md-9" style="height:100%;">
                    <div id="map" style="height:100%; width:100%; border:1px solid #ddd; border-radius:4px;"></div>
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
    var map = L.map('map');
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

    // fokus awal Indonesia
    map.fitBounds([[-11,95],[6,141]]);

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
    });
});
</script>
@endsection




