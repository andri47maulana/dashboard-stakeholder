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
                            <input type="text" id="coords" name="coords" class="form-control" placeholder="contoh: 3.539954,98.766092" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Cek Lokasi</button>
                    </form>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>

        <!-- Map -->
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Peta</div>
                <div class="card-body p-0">
                    <div id="map" style="height:600px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    var map = L.map('map', { zoomControl: true });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    map.fitBounds([[-11,95],[6,141]]);

    let kebunJsons = @json($kebunJsons);
    let polygonLayers = {}; // simpan layer untuk tiap polygon

    kebunJsons.forEach(jsonData=>{
        let layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
            vectorTileLayerStyles: {
                [jsonData.decoded.id]: {
                    weight: 2, color: "#28a745", fill: true, fillOpacity: 0.3
                }
            }
        }).addTo(map);

        polygonLayers[jsonData.decoded.id] = layer;
    });

    let marker = null;
    let polyline = null;
    let highlightLayer = null;

    document.getElementById("checkPointForm").addEventListener("submit", function(e){
        e.preventDefault();
        let coords = document.getElementById("coords").value.trim();
        let [lat, lng] = coords.split(",").map(c => parseFloat(c.trim()));

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

            // Hapus marker/garis lama
            if (marker) map.removeLayer(marker);
            if (polyline) map.removeLayer(polyline);
            if (highlightLayer) map.removeLayer(highlightLayer);

            // Tambah marker titik user
            marker = L.marker([lat, lng]).addTo(map).bindPopup("Titik Anda").openPopup();
            map.setView([lat, lng], 15);

            if (data.inside) {
                resDiv.innerHTML = `<div class="alert alert-success">
                    Titik berada di dalam kebun: <b>${data.inside.title}</b>
                </div>`;
            } else if (data.nearest) {
                resDiv.innerHTML = `<div class="alert alert-warning">
                    Titik tidak berada di dalam kebun manapun.<br>
                    Kebun terdekat: <b>${data.nearest.title}</b><br>
                    Jarak: <b>${data.nearest.distance_km} km</b>
                </div>`;

                // highlight polygon terdekat (outline merah tebal)
                highlightLayer = L.vectorGrid.protobuf(data.nearest.decoded.tileurl, {
                    vectorTileLayerStyles: {
                        [data.nearest.decoded.id]: {
                            weight: 3, color: "red", fill: false
                        }
                    }
                }).addTo(map);

                // gambar garis putus-putus dari titik ke center polygon
                if (data.nearest.center) {
                    let centerLat = data.nearest.center[1];
                    let centerLng = data.nearest.center[0];

                    polyline = L.polyline(
                        [[lat, lng], [centerLat, centerLng]],
                        { color: "red", dashArray: "5, 5", weight: 2 }
                    )
                    .addTo(map)
                    .bindTooltip(
                        `${data.nearest.distance_km} km`,
                        { permanent: true, className: "distance-tooltip", offset: [0, -10] }
                    )
                    .openTooltip();

                    // zoom otomatis fit titik + center polygon
                    let bounds = L.latLngBounds([
                        [lat, lng],
                        [centerLat, centerLng]
                    ]);
                    map.fitBounds(bounds, { padding: [30, 30] });
                }

            }

        });
    });
});
</script>

@endsection
