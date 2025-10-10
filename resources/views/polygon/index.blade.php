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
                        <hr>
                        <div class="mb-3">
                            <label for="title" class="form-label">Judul Log <small class="text-muted">opsional</small></label>
                            <input type="text" id="title" name="title" class="form-control" placeholder="Contoh: Cek lokasi proyek A">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Kaitkan data</label>
                            <div class="row g-2">
                                <div class="col-12">
                                    <select id="stakeholder_select" class="form-control" style="width:100%"></select>
                                    <input type="hidden" id="stakeholder_id" name="stakeholder_id">
                                </div>
                                <div class="col-12">
                                    <input type="number" id="tjsl_id" name="tjsl_id" class="form-control" placeholder="TJSL ID (opsional)">
                                </div>
                            </div>
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

    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Riwayat Pencarian</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-sm table-striped mb-0" id="logTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Waktu</th>
                                    <th>Judul</th>
                                    <th>Koordinat</th>
                                    <th>Radius</th>
                                    <th>Di dalam Kebun</th>
                                    <th>Kebun Terdekat</th>
                                    <th>Stakeholder</th>
                                    <th>TJSL</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @isset($logs)
                                    @foreach($logs as $i => $log)
                                        <tr>
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>{{ $log->title ?? '-' }}</td>
                                            <td>{{ number_format($log->lat,6) }}, {{ number_format($log->lng,6) }}</td>
                                            <td>{{ $log->radius_km ?? '-' }}</td>
                                            <td>{{ $log->inside_unit ?? '-' }}</td>
                                            <td>{{ $log->nearest_unit ? $log->nearest_unit . ($log->nearest_distance_km ? ' ('.$log->nearest_distance_km.' km)' : '') : '-' }}</td>
                                            <td>{{ $log->stakeholder_nama ?? '-' }}</td>
                                            <td>{{ $log->tjsl_id ?? '-' }}</td>
                                            <td>
                                                <a class="btn btn-sm btn-outline-primary" href="{{ route('polygons.log.view',$log->id) }}">View</a>
                                                <button type="button" class="btn btn-sm btn-outline-danger ms-1 btn-log-delete" data-id="{{ $log->id }}">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endisset
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
    // Flag to signal the submit handler is wired
    window._checkPointHandlerReady = false;
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

        // Use a global flag to avoid logging on replay (View action)
        const no_log = window._replayActiveLog === true;
        fetch("{{ route('polygons.check') }}", {
            method: "POST",
            headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": "{{ csrf_token() }}" },
            body: JSON.stringify({
                lat: lat,
                lng: lng,
                radius_km: radius_km,
                title: document.getElementById('title').value.trim() || null,
                stakeholder_id: parseInt(document.getElementById('stakeholder_id').value) || null,
                tjsl_id: parseInt(document.getElementById('tjsl_id').value) || null,
                no_log: no_log
            })
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

            // Tambahkan baris ke tabel log jika user mengisi Judul / asosiasi
            const title = document.getElementById('title').value.trim();
                        const stakeholderId = document.getElementById('stakeholder_id').value.trim();
            const tjslId = document.getElementById('tjsl_id').value.trim();
            if (!no_log && (title || stakeholderId || tjslId)) {
                const tbody = document.querySelector('#logTable tbody');
                const row = document.createElement('tr');
                const idx = tbody.children.length + 1;
                const insideText = data.inside ? (data.inside.unit || data.inside.id) : '-';
                const nearestText = data.nearest ? `${data.nearest.unit || data.nearest.id} (${(data.nearest.distance_km ?? 0).toFixed(3)} km)` : '-';
                row.innerHTML = `
                    <td>${idx}</td>
                    <td>${new Date().toLocaleString()}</td>
                    <td>${title || '-'}</td>
                    <td>${lat.toFixed(6)}, ${lng.toFixed(6)}</td>
                    <td>${radius_km ?? '-'}</td>
                    <td>${insideText}</td>
                    <td>${nearestText}</td>
                    <td>${$('#stakeholder_select').find(':selected').text() || '-'}</td>
                                        <td>${tjslId || '-'}</td>
                    <td><span class="text-muted">(tersimpan)</span></td>
                `;
                tbody.prepend(row);
            }

            // Reset replay flag after one run
            if (window._replayActiveLog) {
                window._replayActiveLog = false;
            }
        });
    });

    // Mark handler ready
    window._checkPointHandlerReady = true;
});
</script>
<script>
// Init select2 for stakeholder async search
$(function(){
    $('#stakeholder_select').select2({
        placeholder: 'Cari stakeholder…',
        allowClear: true,
        ajax: {
            url: '{{ route('stakeholder.search') }}',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data };
            }
        }
    }).on('select2:select', function(e){
        $('#stakeholder_id').val(e.params.data.id);
    }).on('select2:clear', function(){
        $('#stakeholder_id').val('');
    });

    // If this page loaded with an activeLog, pre-fill the form and auto-run search
    @isset($activeLog)
        const active = @json($activeLog);
        // Prefill fields
        $('#coords').val(active.lat + ',' + active.lng);
        if (active.radius_km) $('#radius_km').val(active.radius_km);
        if (active.title) $('#title').val(active.title);
        if (active.stakeholder_id) {
                // Fetch display text via search API (best-effort) or set raw ID
                $.get('{{ route('stakeholder.search') }}', { q: active.stakeholder_id }, function(items){
                        if (Array.isArray(items) && items.length) {
                                const item = items[0];
                                const option = new Option(item.text, item.id, true, true);
                                $('#stakeholder_select').append(option).trigger('change');
                                $('#stakeholder_id').val(item.id);
                        } else {
                                $('#stakeholder_id').val(active.stakeholder_id);
                        }
                });
        }
        if (active.tjsl_id) $('#tjsl_id').val(active.tjsl_id);
        // Auto submit to re-draw map and details without creating a new log
        window._replayActiveLog = true;
        (function kick(){
            if (window._checkPointHandlerReady) {
                $('#checkPointForm').trigger('submit');
            } else {
                setTimeout(kick, 150);
            }
        })();
    @endisset

        // Delete handler
            $(document).on('click', '.btn-log-delete', function(){
                const $btn = $(this);
                const id = $btn.data('id');
            if (!id) return;
            if (!confirm('Hapus log ini?')) return;
                fetch(`{{ url('/peta/polygons/log') }}/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                }).then(r=>{
                        if (!r.ok) throw new Error('HTTP error');
                        return r.json().catch(()=>({}));
                }).then(()=>{
                        // Remove row from table
                        $btn.closest('tr').remove();
                }).catch(()=> alert('Gagal menghapus log'));
        });
});
</script>
@endsection
