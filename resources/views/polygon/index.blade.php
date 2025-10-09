@extends('layouts.app')

@section('content')
<style>
.card-header {
    font-weight: bold;
}
.distance-tooltip {
    background: white;
    color: black;
    font-weight: bold;
    border: 1px solid gray;
    border-radius: 4px;
    padding: 2px 6px;
}

</style>

<div class="container-fluid">
    <h4 class="mb-4">🔎 Cek Titik Koordinat</h4>

    <div class="row">
        <!-- Input -->
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Form Koordinat</div>
                <div class="card-body">
                    <form id="checkPointForm">
                        <div class="mb-3">
                            <label for="coords" class="form-label">Koordinat (format: lat,lng)</label>
                            <input type="text" id="coords" name="coords" class="form-control" placeholder="contoh: -6.9,107.6" required>
                        </div>
                        <div class="mb-3">
                            <label for="radius_km" class="form-label">Radius (km) <small class="text-muted">opsional</small></label>
                            <input type="number" step="0.1" min="0" id="radius_km" name="radius_km" class="form-control" placeholder="misal: 10">
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cek Lokasi</button>
                    </form>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>
        <div class="col-md-8" >
            <div class="card shadow-sm" style="height:530px;">
                    <div id="map"></div>
            </div>
        </div>
       
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
{{-- 
<script>
// Pastikan variabel layer didefinisikan di scope yang sama sebelum dipakai
let marker = null;
let polyline = null;
let highlightLayer = null;
let boundingBoxLayer = null;
let radiusCircle = null; // <- definisi awal agar tidak ReferenceError
let withinMarkers = [];

document.addEventListener("DOMContentLoaded", function () {
    var map = L.map('map', { zoomControl: true });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    map.fitBounds([[-11,95],[6,141]]);

    let kebunJsons = @json($kebunJsons);
    let polygonLayers = {};

    kebunJsons.forEach(jsonData=>{
        if (jsonData.decoded && jsonData.decoded.tileurl) {
            let layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
                vectorTileLayerStyles: {
                    [jsonData.decoded.id]: {
                        weight: 2, color: "#28a745", fill: true, fillOpacity: 0.3
                    }
                }
            }).addTo(map);
            polygonLayers[jsonData.decoded.id] = layer;
        }
    });

    // (Variabel layer sudah dideklarasikan di atas)

    document.getElementById("checkPointForm").addEventListener("submit", function(e){
        e.preventDefault();
        let coords = document.getElementById("coords").value.trim();
    let [lat, lng] = coords.split(",").map(c => parseFloat(c.trim()));
    let radius_km = document.getElementById('radius_km').value.trim();
    if(radius_km === '') radius_km = null; else radius_km = parseFloat(radius_km);

        if (isNaN(lat) || isNaN(lng)) {
            document.getElementById("result").innerHTML = `<div class="alert alert-danger">Format koordinat tidak valid.</div>`;
            return;
        }

        fetch("{{ route('polygons.check') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ lat: lat, lng: lng })
        })
        .then(res => res.json())
        .then(data => {
            let resDiv = document.getElementById("result");
            resDiv.innerHTML = "";

            if (marker) map.removeLayer(marker);
            if (polyline) map.removeLayer(polyline);
            if (highlightLayer) map.removeLayer(highlightLayer);
            if (boundingBoxLayer) map.removeLayer(boundingBoxLayer);

            marker = L.marker([lat, lng]).addTo(map).bindPopup("Titik Anda").openPopup();

            if (data.inside) {
                resDiv.innerHTML = `<div class="alert alert-success">
                    Titik berada di dalam kebun: <b>${data.inside.title}</b>
                </div>`;
                map.setView([lat, lng], 15);

            } else if (data.nearest && data.nearest.bounds && data.nearest.bounds.length === 4) {
                
                const userPoint = turf.point([lng, lat]);
                const bounds = data.nearest.bounds; 
                
                const boundingBoxPolygonCoords = [[
                    [bounds[0], bounds[1]],
                    [bounds[2], bounds[1]],
                    [bounds[2], bounds[3]],
                    [bounds[0], bounds[3]],
                    [bounds[0], bounds[1]]
                ]];
                const boundingBoxLine = turf.lineString(boundingBoxPolygonCoords[0]);
                
                const nearestPointOnEdge = turf.nearestPointOnLine(boundingBoxLine, userPoint);
                const nearestEdgeCoords = nearestPointOnEdge.geometry.coordinates; 
                
                const preciseDistanceKm = turf.distance(userPoint, nearestPointOnEdge, { units: 'kilometers' });

                resDiv.innerHTML = `<div class="alert alert-warning">
                    Titik tidak berada di dalam kebun manapun.<br>
                    Kebun terdekat: <b>${data.nearest.title}</b><br>
                    Jarak ke tepi area terdekat (estimasi): <b>${preciseDistanceKm.toFixed(2)} km</b>
                </div>`;
                
                if (data.nearest.decoded && data.nearest.decoded.tileurl) {
                    highlightLayer = L.vectorGrid.protobuf(data.nearest.decoded.tileurl, {
                        vectorTileLayerStyles: {
                            [data.nearest.decoded.id]: { weight: 3, color: "red", fill: false }
                        }
                    }).addTo(map);
                }
                
                // --- PERBAIKAN DI SINI ---
                // Kode baru ini jauh lebih sederhana dan benar.
                // Kita langsung mengambil array koordinat, menukar urutan [lng, lat] menjadi [lat, lng]
                // yang sesuai dengan format Leaflet.
                const leafletBoundsCoords = boundingBoxPolygonCoords[0].map(coord => [coord[1], coord[0]]);
                boundingBoxLayer = L.polygon(leafletBoundsCoords, { color: 'blue', weight: 1, dashArray: '5,5', fill: false }).addTo(map);

                const nearestEdgeLatLng = [nearestEdgeCoords[1], nearestEdgeCoords[0]];
                const lineCoordinates = [[lat, lng], nearestEdgeLatLng];

                if (lineCoordinates.every(coord => !isNaN(coord[0]) && !isNaN(coord[1]))) {
                    polyline = L.polyline(
                        lineCoordinates,
                        { color: "red", dashArray: "5, 5", weight: 2 }
                    )
                    .addTo(map)
                    .bindTooltip(
                        `${preciseDistanceKm.toFixed(2)} km`,
                        { permanent: true, className: "distance-tooltip", offset: [0, -10] }
                    )
                    .openTooltip();

                    let mapBounds = L.latLngBounds(lineCoordinates);
                    map.fitBounds(mapBounds, { padding: [50, 50] });
                } else {
                    console.error("Gagal membuat polyline karena koordinat tidak valid!");
                }

            } else {
                 resDiv.innerHTML = `<div class="alert alert-info">
                    Tidak ditemukan kebun terdekat atau data bounds tidak lengkap.
                </div>`;
                 map.setView([lat, lng], 15);
            }
        });
    });
});
</script> --}}
<script>
// Deklarasi variabel global untuk layer agar tidak ReferenceError
let marker, polyline, highlightLayer, boundingBoxLayer, radiusCircle;
let withinMarkers = [];

document.addEventListener("DOMContentLoaded", function () {
    var map = L.map('map', { zoomControl: true });

    // --- AWAL PERUBAHAN 1: Membuat Pane Khusus ---
    // Membuat sebuah 'lapisan' baru untuk poligon kita dan memastikan Z-Index nya tinggi
    // (lebih tinggi dari basemap tapi lebih rendah dari marker)
    map.createPane('polygonPane');
    map.getPane('polygonPane').style.zIndex = 450;
    // --- AKHIR PERUBAHAN 1 ---

    // Definisi basemap
    var osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    });
    var satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
        attribution: 'Tiles &copy; Esri &mdash; Source: Esri...',
        maxZoom: 19
    });
    var topo = L.tileLayer('https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png', {
        attribution: 'Map data: &copy; OpenStreetMap contributors, SRTM | Map style: &copy; OpenTopoMap (CC-BY-SA)',
        maxZoom: 17
    });
    var baseMaps = {
        "Peta Jalan": osm,
        "Citra Satelit": satellite,
        "Topografi": topo
    };
    osm.addTo(map);
    L.control.layers(baseMaps).addTo(map);

    map.fitBounds([[-11,95],[6,141]]);

    let kebunJsons = @json($kebunJsons);
    let polygonLayers = {};

    kebunJsons.forEach(jsonData=>{
        if (jsonData.decoded && jsonData.decoded.tileurl) {
            // --- AWAL PERUBAHAN 2: Menambahkan opsi 'pane' ---
            let layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
                pane: 'polygonPane', // <--- TAMBAHKAN INI
                vectorTileLayerStyles: {
                    [jsonData.decoded.id]: {
                        weight: 2, color: "#28a745", fill: true, fillOpacity: 0.3
                    }
                }
            }).addTo(map);
            // --- AKHIR PERUBAHAN 2 ---
            polygonLayers[jsonData.decoded.id] = layer;
        }
    });

    // Inisialisasi (opsional)
    marker = null;
    polyline = null;
    highlightLayer = null;
    boundingBoxLayer = null;

    document.getElementById("checkPointForm").addEventListener("submit", function(e){
        e.preventDefault();
        let coords = document.getElementById("coords").value.trim();
    let [lat, lng] = coords.split(",").map(c => parseFloat(c.trim()));
    let radius_km = document.getElementById('radius_km').value.trim();
    if(radius_km === '') radius_km = null; else radius_km = parseFloat(radius_km);

        if (isNaN(lat) || isNaN(lng)) {
            document.getElementById("result").innerHTML = `<div class="alert alert-danger">Format koordinat tidak valid.</div>`;
            return;
        }

        fetch("{{ route('polygons.check') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({ lat: lat, lng: lng, radius_km: radius_km })
        })
        .then(res => res.json())
        .then(data => {
            let resDiv = document.getElementById("result");
            resDiv.innerHTML = "";

            if (marker) map.removeLayer(marker);
            if (polyline) map.removeLayer(polyline);
            if (highlightLayer) map.removeLayer(highlightLayer);
            if (boundingBoxLayer) map.removeLayer(boundingBoxLayer);
            if (typeof radiusCircle !== 'undefined' && radiusCircle) map.removeLayer(radiusCircle);
            withinMarkers.forEach(m=> map.removeLayer(m));
            withinMarkers = [];

            marker = L.marker([lat, lng]).addTo(map).bindPopup("Titik Anda").openPopup();
            
            // Gambarkan circle jika ada
            if (data.circle) {
                radiusCircle = L.circle([lat, lng], {
                    radius: data.circle.radius_km * 1000,
                    color: 'blue', weight: 1, fillOpacity: 0.05
                }).addTo(map);
            }

            if (data.inside) {
                resDiv.innerHTML += `<div class="alert alert-success p-2">Titik berada di dalam kebun: <b>${data.inside.unit || data.inside.id}</b></div>`;
                map.setView([lat, lng], 14);

            } else if (data.nearest && data.nearest.bounds && data.nearest.center) {
                const userPoint = turf.point([lng, lat]);
                const bounds = data.nearest.bounds; 
                const boundingBoxPolygonCoords = [[
                    [bounds[0], bounds[1]], [bounds[2], bounds[1]],
                    [bounds[2], bounds[3]], [bounds[0], bounds[3]],
                    [bounds[0], bounds[1]]
                ]];
                const boundingBoxLine = turf.lineString(boundingBoxPolygonCoords[0]);
                const nearestPointOnEdge = turf.nearestPointOnLine(boundingBoxLine, userPoint);
                const distanceToEdgeKm = turf.distance(userPoint, nearestPointOnEdge, { units: 'kilometers' });

                resDiv.innerHTML += `<div class="alert alert-warning p-2 mb-2">
                    Titik tidak berada di dalam kebun manapun.<br>
                    Kebun terdekat: <b>${data.nearest.unit || data.nearest.id}</b><br>
                    Jarak ke tepi (estimasi bounding box): ± <b>${distanceToEdgeKm.toFixed(2)} km</b><br>
                    Jarak ke center: <b>${(data.nearest.distance_km ?? 0).toFixed(3)} km</b>
                </div>`;
                
                if (data.nearest.decoded && data.nearest.decoded.tileurl) {
                    // --- AWAL PERUBAHAN 3: Menambahkan opsi 'pane' untuk highlight ---
                    highlightLayer = L.vectorGrid.protobuf(data.nearest.decoded.tileurl, {
                        pane: 'polygonPane', // <--- TAMBAHKAN INI JUGA
                        vectorTileLayerStyles: {
                            [data.nearest.decoded.id]: { weight: 3, color: "red", fill: false }
                        }
                    }).addTo(map);
                    // --- AKHIR PERUBAHAN 3 ---
                }
                
                let centerLat = data.nearest.center[1];
                let centerLng = data.nearest.center[0];
                polyline = L.polyline(
                    [[lat, lng], [centerLat, centerLng]],
                    { color: "red", dashArray: "5, 5", weight: 2 }
                ).addTo(map).bindTooltip(
                    `${distanceToEdgeKm.toFixed(2)} km`, 
                    { permanent: true, className: "distance-tooltip", offset: [0, -10] }
                ).openTooltip();
                let mapBounds = L.latLngBounds([[lat, lng], [centerLat, centerLng]]);
                map.fitBounds(mapBounds, { padding: [50, 50] });

            } else {
                 resDiv.innerHTML += `<div class="alert alert-info p-2">Tidak ditemukan kebun terdekat atau data tidak lengkap.</div>`;
                 map.setView([lat, lng], 12);
            }

            // Tampilkan daftar within_radius jika ada
            if (data.within_radius && data.within_radius.length) {
                let listHtml = '<div class="mt-2"><h6>Kebun dalam radius:</h6><ol class="small ps-3">';
                data.within_radius.forEach(row => {
                    const dist = (typeof row.distance_km === 'number') ? row.distance_km.toFixed(3) : row.distance_km;
                    const dtype = (row.distance_type || 'center');
                        const badgeClass = dtype === 'edge' ? 'bg-success' : (dtype === 'bbox' ? 'bg-info text-dark' : 'bg-secondary');
                        // Badge tanpa teks: gunakan titik kecil berwarna, tetap beri title untuk aksesibilitas
                        const badge = `<span class="badge ${badgeClass}" title="${dtype}" style="width:10px;height:10px;display:inline-block;border-radius:8px;padding:0;vertical-align:middle;"></span>`;
                    const title = row.center_distance_km ? ` title="jarak ke center: ${row.center_distance_km} km"` : '';
                    listHtml += `<li${title}>${badge} ${row.unit || row.id} (${dist} km)</li>`;
                    if (row.center) {
                        // Ubah warna marker sesuai tipe jarak
                        const color = dtype === 'edge' ? 'green' : (dtype === 'bbox' ? 'orange' : 'purple');
                        let m = L.circleMarker([row.center[1], row.center[0]], {
                            radius: 4, color: color, fillColor: color, fillOpacity: 0.7
                        }).addTo(map).bindTooltip(`${row.unit || row.id} - ${dist} km (${dtype})`);
                        withinMarkers.push(m);
                    }
                });
                listHtml += '</ol></div>';
                resDiv.innerHTML += listHtml;
            }
        });
    });
});
</script>
@endsection
