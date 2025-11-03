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

/* Layout improvements for map card */
.map-card {
    height: 730px;
    display: flex;
    flex-direction: column;
}
.map-tools {
    padding: 8px 12px;
    border-bottom: 1px solid #e9ecef;
}
.map-filters {
    padding: 6px 12px 8px 12px;
    border-bottom: 1px solid #f1f3f5;
}
.map-container {
    flex: 1 1 auto;
    position: relative;
}
#appFullscreenOverlayFix { display:none; }
#mapOverlay { position:absolute; top:12px; left:12px; z-index:1001; width: 340px; max-height: calc(100vh - 24px); overflow:auto; background:#ffffff; border:1px solid #e9ecef; border-radius:10px; box-shadow:0 8px 24px rgba(0,0,0,0.2); padding:10px; }
.map-overlay-header { display:flex; align-items:center; justify-content:space-between; gap:8px; margin-bottom:6px; }
.map-overlay-header .title { font-weight:600; font-size:13px; color:#0b7285; }
.map-overlay-header .close { border:none; background:#f1f3f5; border-radius:6px; padding:2px 6px; font-size:12px; }

.map-card.fullscreen { position: fixed; inset: 0; width: 100vw; height: 100vh; z-index: 1050; border-radius: 0 !important; }
.map-card.fullscreen .map-container { height: 100%; }
body.overflow-hidden { overflow: hidden; }
#map {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    min-height: 300px;
}
/* Show pointer cursor on interactive vector features */
.leaflet-interactive { cursor: pointer; }

/* Styled polygon profile popup - extra compact */
.leaflet-popup-content-wrapper.poly-profile-popup { padding: 0; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.2); }
.leaflet-popup-content.poly-profile { margin: 0; padding: 0; }
.poly-profile .pp-header { padding: 6px 10px; background: #ffffff; border-bottom: 1px solid #edf2f7; border-radius: 8px 8px 0 0; }
.poly-profile .pp-title { font-weight: 700; color: #1971c2; text-align: center; font-size: 13px; }
.poly-profile .pp-sub { display:flex; align-items:center; justify-content:center; gap:4px; margin-top:4px; }
.poly-profile .pp-sub .pp-btn { border:1px solid #cbd5e1; background:#f8fafc; border-radius:5px; padding:0 5px; font-size:10px; line-height:18px; height:18px; }
.poly-profile .pp-body { padding: 8px 10px; background: #fff; }
.poly-profile .pp-section-title { background:#20a4b9; color:#fff; font-weight:600; padding:4px 8px; border-radius:8px; text-align:center; margin-bottom:6px; font-size:11px; }
.poly-profile .pp-badge { display:block; text-align:center; font-weight:800; font-size:14px; color:#fff; padding:3px 0; border-radius:6px; margin:4px 0 6px 0; }
.poly-profile .pp-badge.red { background:#e55353; }
.poly-profile .pp-badge.gray { background:#6c757d; }
/* Legend-based badge colors */
.poly-profile .pp-badge.p1 { background:#d32f2f; color:#fff; }
.poly-profile .pp-badge.p2 { background:#1e88e5; color:#fff; }
.poly-profile .pp-badge.p3 { background:#ffff00; color:#000; }
.poly-profile .pp-badge.p4 { background:#28a745; color:#fff; }
.poly-profile .pp-desc { background:#fff; border:1px solid #e9ecef; border-radius:8px; padding:6px 8px; color:#343a40; line-height:1.3; font-size:11px; }
.poly-profile .pp-grid { display:flex; gap:8px; margin-top:8px; }
.poly-profile .pp-col { flex:1 1 0; background:#fff; border:1px solid #e9ecef; border-radius:8px; padding:0; overflow:hidden; }
.poly-profile .pp-col .pp-col-head { background:#20a4b9; color:#fff; padding:6px 8px; font-weight:600; font-size:11px; }
.poly-profile .pp-col .pp-col-body { padding:6px 8px; }
.poly-profile .pp-list { list-style:none; margin:0; padding:0; }
.poly-profile .pp-list li { display:flex; justify-content:space-between; padding:2px 0; border-bottom:1px dashed #eef2f7; font-size:11px; }
.poly-profile .pp-list li:last-child { border-bottom:none; }
.poly-profile .pp-col-foot { padding:5px 8px; background:#f1f3f5; color:#495057; text-align:center; font-weight:700; font-size:11px; }
.poly-profile .pp-col-foot.badge { background:#e55353; color:#fff; font-size:12px; }
/* Legend-based foot colors (left/right sections) */
.poly-profile .pp-col-foot.p1, .poly-profile .pp-col-foot.badge.p1 { background:#d32f2f; color:#fff; }
.poly-profile .pp-col-foot.p2, .poly-profile .pp-col-foot.badge.p2 { background:#1e88e5; color:#fff; }
.poly-profile .pp-col-foot.p3, .poly-profile .pp-col-foot.badge.p3 { background:#ffff00; color:#000; }
.poly-profile .pp-col-foot.p4, .poly-profile .pp-col-foot.badge.p4 { background:#28a745; color:#fff; }

@media (max-width: 480px) {
    .poly-profile .pp-grid { flex-direction: column; }
        .leaflet-popup-content-wrapper.poly-profile-popup { max-width: 90vw; }
}

/* Pick-from-map UX: change cursor to a placemark and show a ghost pin following the mouse */
#map.is-picking,
#map.is-picking * {
    cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="%23e53935"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>') 12 24, crosshair !important;
}
.pick-ghost-icon { pointer-events: none; }

/* TJSL Marker styling */
.tjsl-marker-icon {
    background: transparent !important;
    border: none !important;
}
.tjsl-marker-icon > div {
    transition: transform 0.2s ease;
}
.tjsl-marker-icon:hover > div {
    transform: scale(1.15);
}

/* TJSL Tooltip/Label styling - muncul saat hover via JavaScript */
.tjsl-label {
    background: rgba(255, 107, 53, 0.95) !important;
    border: 2px solid #fff !important;
    border-radius: 6px !important;
    box-shadow: 0 3px 8px rgba(0,0,0,0.3) !important;
    color: #fff !important;
    font-weight: 600 !important;
    font-size: 11px !important;
    padding: 4px 8px !important;
    white-space: nowrap !important;
    max-width: 200px !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}
.tjsl-label::before {
    display: none !important;
}

</style>

<div class="container-fluid">
    <h4 class="mb-4">🔎 Cek Titik Koordinat</h4>

    <div class="row">
        <!-- Input -->
        <div class="col-md-3 mb-2">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Form Koordinat</div>
                <div class="card-body">
                    <form id="checkPointForm">
                        <div class="mb-3">
                            <label for="coords" class="form-label">Koordinat (format: lat,lng)</label>
                            <div class="input-group">
                                <input type="text" id="coords" name="coords" class="form-control" placeholder="contoh: -6.9,107.6" required>
                                <button type="button" id="pickFromMap" class="btn btn-outline-secondary" title="Klik untuk memilih koordinat dari peta">Ambil dari Peta</button>
                            </div>
                            <div class="form-check mt-1 d-none" id="autoSubmitWrap">
                                <input class="form-check-input" type="checkbox" value="1" id="autoSubmitPick">
                                <label class="form-check-label" for="autoSubmitPick">
                                    Submit otomatis setelah memilih
                                </label>
                            </div>
                            <small id="pickHelp" class="text-muted d-none">Mode ambil aktif: klik pada peta untuk mengisi koordinat. Tekan Esc untuk batal.</small>
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
                                    <select id="tjsl_select" class="form-control" style="width:100%"></select>
                                    <input type="hidden" id="tjsl_id" name="tjsl_id">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Cek Lokasi</button>
                    </form>
                    <div id="result" class="mt-3"></div>
                </div>
            </div>
        </div>
        <div class="col-md-9" >
            <div class="card shadow-sm map-card">
                <div class="map-tools d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                    <button type="button" id="toggleRegionFilters" class="btn btn-sm btn-outline-secondary">
                        Regional (<span id="regionSelectedCount">0</span>)
                        <span id="regionChevron" aria-hidden="true">▼</span>
                    </button>
                    <button type="button" id="toggleFullscreen" class="btn btn-sm btn-outline-primary">Full Screen</button>
                    <div class="d-flex align-items-center gap-2 ms-3">
                        <span style="background:#ff6b35;width:18px;height:18px;border-radius:50%;border:2px solid #fff;box-shadow:0 2px 4px rgba(0,0,0,0.2);display:inline-block;"></span>
                        <small class="text-muted">Program TJSL (<span id="tjslMarkerCount">0</span>)</small>
                    </div>
                    </div>
                    <small class="text-muted">Tampilkan/sembunyikan filter regional</small>
                </div>
                <div id="regionFiltersContainer" class="map-filters d-none">
                    @php($regionItems = isset($regions) ? $regions : collect([]))
                    <select id="regionSelect" class="form-control" multiple style="width:100%; min-width:240px; max-width:520px;" data-placeholder="Pilih regional…">
                        @foreach($regionItems as $r)
                            @php($isDefault = (strval($r) === '1' || strtolower(strval($r)) === 'regional 1'))
                            <option value="{{ $r }}" {{ $isDefault ? 'selected' : '' }}>Reg {{ $r }}</option>
                        @endforeach
                    </select>
                    @if($regionItems->isEmpty())
                        <div class="text-muted small mt-2">Tidak ada data regional.</div>
                    @endif
                    <div class="mt-2">
                        <label for="unitSelect" class="form-label mb-1">Unit/Kebun</label>
                        <select id="unitSelect" class="form-control" style="width:100%; min-width:240px; max-width:520px;" data-placeholder="Pilih unit/kebun…">
                            <option></option>
                        </select>
                    </div>
                </div>
                <div class="map-container">
                    <div id="map"></div>
                    <div id="mapOverlay" class="d-none">
                        <div class="map-overlay-header">
                            <div class="title">Form Koordinat</div>
                            <button type="button" id="closeOverlay" class="close">Tutup</button>
                        </div>
                        <!-- The form panel will be moved here in fullscreen mode -->
                    </div>
                </div>
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
                                        <tr
                                            data-lat="{{ $log->lat }}"
                                            data-lng="{{ $log->lng }}"
                                            data-radius="{{ $log->radius_km }}"
                                            data-title="{{ $log->title }}"
                                            data-stakeholder-id="{{ $log->stakeholder_id }}"
                                            data-stakeholder-text="{{ $log->stakeholder_nama }}"
                                            data-tjsl-id="{{ $log->tjsl_id }}"
                                        >
                                            <td>{{ $i+1 }}</td>
                                            <td>{{ $log->created_at }}</td>
                                            <td>{{ $log->title ?? '-' }}</td>
                                            <td>{{ number_format($log->lat,6) }}, {{ number_format($log->lng,6) }}</td>
                                            <td>{{ $log->radius_km ?? '-' }}</td>
                                            <td>{{ $log->inside_unit ?? '-' }}</td>
                                            <td>{{ $log->nearest_unit ? $log->nearest_unit . ($log->nearest_distance_km ? ' ('.$log->nearest_distance_km.' km)' : '') : '-' }}</td>
                                            <td>{{ $log->stakeholder_nama ?? '-' }}</td>
                                            <td>{{ $log->tjsl_nama ? $log->tjsl_nama.' (ID: '.$log->tjsl_id.')' : ($log->tjsl_id ?? '-') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-danger btn-log-delete" data-id="{{ $log->id }}">Delete</button>
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

@push('scripts')
<!-- Load JavaScript libraries in correct order (after jQuery from layout) -->
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>
<script src="https://unpkg.com/@turf/turf/turf.min.js"></script>

<script>
// Wait for document ready and ensure all dependencies are loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if all libraries are loaded
    if (typeof jQuery === 'undefined' || typeof L === 'undefined' || typeof turf === 'undefined') {
        console.error('Dependencies not loaded!');
        return;
    }

// Pastikan variabel layer didefinisikan di scope yang sama sebelum dipakai
let marker = null;
let polyline = null;
let highlightLayer = null;
let boundingBoxLayer = null;
let radiusCircle = null;
let withinMarkers = [];

// Map initialization (called from initPolygonMap wrapper)
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
    // Safe fallback: ensure derajatMap exists and is keyed by id_unit (not by array index)
    window.__derajatMap = @json($derajatMap ?? []);
    function buildDerajatMap(src){
        const m = {};
        if (Array.isArray(src)) {
            src.forEach(v => { if (v && (v.id_unit !== undefined && v.id_unit !== null)) m[String(v.id_unit)] = v; });
        } else if (src && typeof src === 'object') {
            Object.keys(src).forEach(k => { const v = src[k]; if (v && (v.id_unit !== undefined && v.id_unit !== null)) m[String(v.id_unit)] = v; });
        }
        return m;
    }
    const derajatMap = buildDerajatMap(window.__derajatMap);
    let polygonLayers = {}; // key: kebun decoded id -> layer instance
    let regionToIds = {};   // key: region value -> array of kebun decoded ids
    let idToRegion = {};    // key: kebun decoded id -> region value
    // Maps to resolve unit_id for color lookup later
    let didToUnitId = {};   // key: decoded tile id -> unit_id
    let unitNameToUnitId = {}; // key: UPPER(nm_unit) -> unit_id
    // Maps to support Unit/Kebun filter and zoom
    let regionToUnits = {}; // key: region -> array of {unit_id, nm_unit}
    let unitIdToJson = {};  // key: unit_id -> jsonData
    // Shared popup interaction state
    let profileOpen = false;
    let hoverPopup = null;

    // Build index of regions to decoded tile layer ids
    kebunJsons.forEach(jsonData => {
        const region = jsonData.nm_region ?? jsonData.region ?? null;
        const did = jsonData?.decoded?.id;
        if (!did || region === null || region === undefined || region === '') return;
        if (!regionToIds[region]) regionToIds[region] = [];
        regionToIds[region].push(did);
        idToRegion[did] = region;
        // Build helper maps for later color resolution
        if (jsonData.unit_id != null) {
            didToUnitId[did] = jsonData.unit_id;
            if (jsonData.nm_unit) {
                unitNameToUnitId[String(jsonData.nm_unit).trim().toUpperCase()] = jsonData.unit_id;
            }
            
            // Unit filter datasets
            if (!regionToUnits[region]) regionToUnits[region] = [];
            regionToUnits[region].push({ unit_id: jsonData.unit_id, nm_unit: jsonData.nm_unit || 'Unit ' + jsonData.unit_id });
            if (!unitIdToJson[jsonData.unit_id]) unitIdToJson[jsonData.unit_id] = jsonData;
        }
    });
    // Deduplicate units per region by unit_id
    Object.keys(regionToUnits).forEach(r => {
        const seen = new Set();
        regionToUnits[r] = regionToUnits[r].filter(u => {
            if (seen.has(u.unit_id)) return false;
            seen.add(u.unit_id); return true;
        }).sort((a,b)=> String(a.nm_unit).localeCompare(String(b.nm_unit)));
    });

    function colorForDerajat(derajat){
        // Map Derajat Hubungan badges (e.g., P1, PP1, P2, PP2, ...) to colors
        // Legend per image: Null=gray, P1=red, P2=blue, P3=yellow, P4=green
        if (!derajat) return '#6c757d';
        const key = String(derajat).trim().toUpperCase();
        if (key === 'NULL' || key === 'NONE' || key === '-') return '#6c757d';
        // Extract numeric level if present (handles P1, PP1, etc.)
        const numMatch = key.match(/(\d+)/);
        const lvl = numMatch ? parseInt(numMatch[1], 10) : null;
        switch (lvl) {
            case 1: return '#d32f2f'; // red (P1)
            case 2: return '#1e88e5'; // blue (P2)
            case 3: return '#ffff00'; // yellow (P3)
            case 4: return '#28a745'; // green (P4)
            default:
                // fallback for text-based categories if any
                return '#6c757d';
        }
    }

    // Helper to create a layer for a kebun record
    function createPolygonLayer(jsonData) {
    // If pick mode enabled, suppress interactivity
        const isPicking = () => !!window.__pickFromMapActive;
    const derajat = derajatMap[String(jsonData.unit_id)]?.derajat_hubungan ?? null;
        const fillColor = colorForDerajat(derajat);
        const layer = L.vectorGrid.protobuf(jsonData.decoded.tileurl, {
            pane: 'polygonPane',
            interactive: true,
            vectorTileLayerStyles: {
                [jsonData.decoded.id]: (properties, zoom) => ({
                    weight: 1.2,
                    color: '#ffffff',
                    opacity: 0.9,
                    fill: true,
                    fillColor: fillColor,
                    fillOpacity: 0.45,
                    interactive: true
                })
            }
        });
        // Hover/Click handlers
        layer.on('mouseover', (e) => {
            if (isPicking()) return; // ignore while picking
            const uid = jsonData.unit_id;
            const d = derajatMap[String(uid)] || {};
            const html = `<div><b>${jsonData.nm_unit || 'Unit'}</b><br/>
                Derajat: <b>${d.derajat_hubungan ?? '-'}</b><br/>
                Indeks Kepuasan: ${d.indeks_kepuasan ?? '-'}<br/>
                Prioritas (SocMap): ${d.prioritas_socmap ?? '-'}<br/>
                Tahun: ${d.tahun ?? '-'}
            </div>`;
            const latlng = e.latlng || map.getCenter();
            if (!profileOpen) {
                if (!hoverPopup) {
                    hoverPopup = L.popup({autoPan:false, closeButton:false, offset:[0,-8]});
                }
                hoverPopup.setLatLng(latlng).setContent(html).openOn(map);
            }
        });
        layer.on('mouseout', () => {
            if (isPicking()) return; // ignore while picking
            if (!profileOpen && hoverPopup) {
                map.closePopup(hoverPopup);
            }
        });
        layer.on('click', (e) => {
            if (isPicking()) return; // ignore while picking
            const uid = jsonData.unit_id;
            const d = derajatMap[String(uid)] || {};
            const unitName = (jsonData.nm_unit || 'Unit').toUpperCase();
            const regionName = jsonData.nm_region ? `Regional ${jsonData.nm_region}` : '';
            const tahun = d.tahun ?? '';
            const derajat = d.derajat_hubungan ?? '-';
            const kep = d.kepuasan ?? '-';
            const kontri = d.kontribusi ?? '-';
            const komu = d.komunikasi ?? '-';
            const perc = d.kepercayaan ?? '-';
            const keter = d.keterlibatan ?? '-';
            const indeks = (d.indeks_kepuasan ?? '-');
            const prioritas = (d.prioritas_socmap ?? '-');
            // Social Mapping breakdown and score (when available)
            const smLing = (d.lingkungan ?? '-');
            const smEko  = (d.ekonomi ?? '-');
            const smPend = (d.pendidikan ?? '-');
            const smSos  = (d.sosial_kesesjahteraan ?? d.sosial ?? '-');
            const smOku  = (d.okupasi ?? '-');
            const smSkor = (d.skor_socmap ?? '-');
            // Helpers: normalize badge text (avoid double 'P') and format numbers
            const badgeText = (val) => {
                if (val === null || val === undefined || val === '') return '-';
                const s = String(val).trim();
                if (!s) return '-';
                return /^p/i.test(s) ? s.toUpperCase() : ('P' + s);
            };
            const fmt = (v, digits = 2) => {
                if (v === null || v === undefined || v === '' || v === '-') return '-';
                const n = Number(v);
                return Number.isFinite(n) ? n.toFixed(digits) : String(v);
            };
            const derajatBadge = badgeText(derajat);
            const prioritasBadge = badgeText(prioritas);
            // Legend-based class (p1..p4) for consistent popup colors
            const badgeClassFrom = (val) => {
                if (!val) return '';
                const s = String(val).trim().toUpperCase();
                const m = s.match(/(\d+)/);
                if (!m) return '';
                const n = parseInt(m[1],10);
                return (n>=1 && n<=4) ? ('p'+n) : '';
            };
            const derajatCls = badgeClassFrom(derajat);
            const prioritasCls = badgeClassFrom(prioritas);
            const desc = d.deskripsi ?? '';

            const profileHtml = `
                <div class="poly-profile">
                    <div class="pp-header">
                        <div class="pp-title">${unitName}${regionName ? ' - ' + regionName : ''}</div>
                        <div class="pp-sub">
                            <button class="pp-btn" disabled>&larr;</button>
                            <select class="form-select form-select-sm" style="width:auto; min-width:100px; display:inline-block;">
                                ${tahun ? `<option selected>${tahun}</option>` : `<option selected>-</option>`}
                            </select>
                            <button class="pp-btn" disabled>&rarr;</button>
                        </div>
                    </div>
                    <div class="pp-body">
                        <div class="pp-section-title">Data Analisis Hubungan Stakeholder</div>
                        <div class="text-center fw-semibold mb-1">Derajat Hubungan</div>
                        <span class="pp-badge ${derajatCls || (derajat !== '-' ? 'red' : 'gray')}">${derajatBadge}</span>
                        <div class="pp-desc mb-2">
                            Unit ${unitName} ${regionName ? '(' + regionName + ')' : ''}
                            mendapat skor Indeks Kepuasan Stakeholder di <b>${fmt(indeks)}</b>
                            dan skor Social Mapping di <b>${prioritas}</b>
                            yang menempatkan <b>${unitName}</b> pada kategori <b>${derajatBadge}</b>.
                            ${desc ? `<br/><span class="text-muted">${desc}</span>` : ''}
                        </div>
                        <div class="pp-grid">
                            <div class="pp-col">
                                <div class="pp-col-head">Indeks Kepuasan Stakeholder</div>
                                <div class="pp-col-body">
                                    <ul class="pp-list">
                                        <li><span>Kepuasan</span><span>: ${fmt(kep,2)}</span></li>
                                        <li><span>Kontribusi</span><span>: ${fmt(kontri,2)}</span></li>
                                        <li><span>Komunikasi</span><span>: ${fmt(komu,2)}</span></li>
                                        <li><span>Kepercayaan</span><span>: ${fmt(perc,2)}</span></li>
                                        <li><span>Keterlibatan</span><span>: ${fmt(keter,2)}</span></li>
                                        <li><span>Total</span><span>: ${fmt(indeks,2)}</span></li>
                                    </ul>
                                </div>
                                <div class="pp-col-foot ${derajatCls}">${derajatBadge}</div>
                            </div>
                            <div class="pp-col">
                                <div class="pp-col-head">Social <br>Mapping</div>
                                <div class="pp-col-body">
                                    <ul class="pp-list">
                                        <li><span>Lingkungan</span><span>: ${fmt(smLing,2)}</span></li>
                                        <li><span>Ekonomi</span><span>: ${fmt(smEko,2)}</span></li>
                                        <li><span>Pendidikan</span><span>: ${fmt(smPend,2)}</span></li>
                                        <li><span>Sosial</span><span>: ${fmt(smSos,2)}</span></li>
                                        <li><span>Okupasi</span><span>: ${fmt(smOku,2)}</span></li>
                                        <li><span>Total</span><span>: ${fmt(smSkor,2)}</span></li>
                                    </ul>
                                </div>
                                <div class="pp-col-foot ${prioritasCls}">${prioritasBadge}</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            profileOpen = true;
            // Close hover popup if open
            if (hoverPopup) map.closePopup(hoverPopup);
            const prof = L.popup({autoPan:true, maxWidth: 360, className: 'poly-profile-popup'})
                .setLatLng(e.latlng)
                .setContent(profileHtml)
                .openOn(map);
            // Reset state when profile popup closes
            map.once('popupclose', function() { profileOpen = false; });
        });
        return layer;
    }

    // Add only layers for selected regions
    function addLayersForRegions(selectedRegions) {
        kebunJsons.forEach(jsonData => {
            if (!jsonData.decoded || !jsonData.decoded.tileurl) return;
            const region = jsonData.nm_region ?? jsonData.region ?? null;
            const id = jsonData.decoded.id;
            if (!selectedRegions.has(String(region))) return;
            if (polygonLayers[id]) return; // already added
            const layer = createPolygonLayer(jsonData).addTo(map);
            polygonLayers[id] = layer;
        });
    }
    // Fallback: add all layers when no region filters are available
    function addAllLayers() {
        kebunJsons.forEach(jsonData => {
            if (!jsonData.decoded || !jsonData.decoded.tileurl) return;
            const id = jsonData.decoded.id;
            if (polygonLayers[id]) return;
            const layer = createPolygonLayer(jsonData).addTo(map);
            polygonLayers[id] = layer;
        });
    }
    function removeLayersForRegions(unselectedRegions) {
        Object.entries(polygonLayers).forEach(([id, layer]) => {
            const region = idToRegion[id];
            if (unselectedRegions.has(String(region))) {
                map.removeLayer(layer);
                delete polygonLayers[id];
            }
        });
    }

    // Initialize selection from dropdown (Select2 multi): default Regional 1 selected
    const selectedRegions = new Set();
    const regionSelect = document.getElementById('regionSelect');
    if (!regionSelect || regionSelect.options.length === 0) {
        // No region filters provided: load all layers
        addAllLayers();
    } else {
        // Collect initially selected options
        Array.from(regionSelect.selectedOptions).forEach(opt => selectedRegions.add(String(opt.value)));
        if (selectedRegions.size === 0 && regionSelect.options.length > 0) {
            // Ensure at least one default selection (first option)
            regionSelect.options[0].selected = true;
            selectedRegions.add(String(regionSelect.options[0].value));
        }
        addLayersForRegions(selectedRegions);
    }

    // Update count and collapsible behavior (collapsed by default)
    const countEl = document.getElementById('regionSelectedCount');
    if (countEl) countEl.textContent = (!regionSelect || regionSelect.options.length === 0) ? '-' : String(selectedRegions.size);
    const filtersEl = document.getElementById('regionFiltersContainer');
    const chevronEl = document.getElementById('regionChevron');
    const toggleBtn = document.getElementById('toggleRegionFilters');
    if (toggleBtn && filtersEl) {
        toggleBtn.addEventListener('click', () => {
            const hidden = filtersEl.classList.toggle('d-none');
            if (chevronEl) chevronEl.textContent = hidden ? '▼' : '▲';
        });
    }

    // Wire Select2 for region dropdown (reuse Select2 already loaded for stakeholder)
    try {
        if (window.jQuery && typeof jQuery.fn.select2 === 'function' && regionSelect) {
            jQuery(regionSelect).select2({
                placeholder: jQuery(regionSelect).data('placeholder') || 'Pilih regional…',
                width: '100%',
                dropdownParent: jQuery('#regionFiltersContainer'),
                closeOnSelect: false,
                allowClear: true
            }).on('change', function(){
                // Determine new selection set
                const newSelected = new Set(Array.from(this.selectedOptions).map(o => String(o.value)));
                // Compute additions and removals
                const toAdd = new Set([...newSelected].filter(x => !selectedRegions.has(x)));
                const toRemove = new Set([...selectedRegions].filter(x => !newSelected.has(x)));
                if (toAdd.size) {
                    addLayersForRegions(toAdd);
                    addTjslMarkersForRegions(toAdd);
                }
                if (toRemove.size) {
                    removeLayersForRegions(toRemove);
                    removeTjslMarkersForRegions(toRemove);
                }
                // Update current selection set
                selectedRegions.clear();
                newSelected.forEach(v => selectedRegions.add(v));
                // Update count badge
                if (countEl) countEl.textContent = (!regionSelect || regionSelect.options.length === 0) ? '-' : String(selectedRegions.size);
                // Update TJSL counter
                updateTjslCounter();
                // Refresh unit options based on regions
                refreshUnitOptions();
            });
        }
    } catch(_) {}

    // Unit/Kebun select handling
    const unitSelect = document.getElementById('unitSelect');
    function refreshUnitOptions(){
        if (!unitSelect) return;
        const $unit = window.jQuery ? jQuery(unitSelect) : null;
        const units = [];
        selectedRegions.forEach(r => {
            const arr = regionToUnits[r] || [];
            arr.forEach(u => units.push(u));
        });
        // Dedup again across regions
        const byId = new Map();
        units.forEach(u => { if (!byId.has(u.unit_id)) byId.set(u.unit_id, u); });
        const finalUnits = Array.from(byId.values()).sort((a,b)=> String(a.nm_unit).localeCompare(String(b.nm_unit)));
        // Rebuild options
        unitSelect.innerHTML = '<option></option>' + finalUnits.map(u => `<option value="${u.unit_id}">${u.nm_unit}</option>`).join('');
        if ($unit && typeof $unit.select2 === 'function') {
            $unit.val(null).trigger('change.select2');
        }
    }
    // Initialize unit select2
    try {
        if (window.jQuery && typeof jQuery.fn.select2 === 'function' && unitSelect) {
            jQuery(unitSelect).select2({
                placeholder: jQuery(unitSelect).data('placeholder') || 'Pilih unit/kebun…',
                width: '100%',
                dropdownParent: jQuery('#regionFiltersContainer'),
                allowClear: true
            }).on('change', function(){
                const val = this.value ? String(this.value) : '';
                if (!val) return;
                // Ensure the layer for this unit's region is loaded
                const jd = unitIdToJson[val];
                if (!jd) return;
                const reg = jd.nm_region ?? jd.region;
                if (reg != null && !selectedRegions.has(String(reg))) {
                    // Add region selection visually and in set
                    if (regionSelect) {
                        Array.from(regionSelect.options).forEach(opt => {
                            if (String(opt.value) === String(reg)) opt.selected = true;
                        });
                        jQuery(regionSelect).trigger('change');
                    } else {
                        selectedRegions.add(String(reg));
                        const regSet = new Set([String(reg)]);
                        addLayersForRegions(regSet);
                        addTjslMarkersForRegions(regSet);
                        updateTjslCounter();
                    }
                }
                // Zoom to bounds/center
                const bounds = jd?.decoded?.bounds;
                const center = jd?.decoded?.center;
                if (bounds && bounds.length === 4) {
                    const latLngBounds = L.latLngBounds([
                        [bounds[1], bounds[0]],
                        [bounds[3], bounds[2]]
                    ]);
                    map.fitBounds(latLngBounds, { padding: [40, 40] });
                } else if (center && center.length === 2) {
                    map.setView([center[1], center[0]], 14);
                }
            });
        }
    } catch(_) {}

    // Populate unit options for initial selected regions
    refreshUnitOptions();

    // --- TJSL Locations Markers ---
    // Ensure this runs after map is initialized
    let tjslMarkers = [];
    let tjslMarkersMap = {}; // key: tjsl.id -> marker instance
    let regionToTjslIds = {}; // key: region value -> array of tjsl ids
    let idToTjslRegion = {}; // key: tjsl.id -> region value
    const tjslLocations = @json($tjslLocations ?? []);

    // Custom icon untuk TJSL marker - menggunakan icon calendar event/program
    const tjslIcon = L.divIcon({
        className: 'tjsl-marker-icon',
        html: '<div style="background:#ff6b35;width:32px;height:42px;border-radius:50% 50% 50% 0;transform:rotate(-45deg);border:3px solid #fff;box-shadow:0 4px 12px rgba(255,107,53,0.4);display:flex;align-items:center;justify-content:center;"><svg width="18" height="18" fill="#fff" viewBox="0 0 16 16" style="transform:rotate(45deg);"><path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg></div>',
        iconSize: [32, 42],
        iconAnchor: [16, 42],
        popupAnchor: [0, -42]
    });

    // Build index of regions to TJSL ids
    tjslLocations.forEach(tjsl => {
        const region = tjsl.nm_region ?? null;
        const id = tjsl.id;
        if (id && region !== null && region !== undefined && region !== '') {
            if (!regionToTjslIds[region]) regionToTjslIds[region] = [];
            regionToTjslIds[region].push(id);
            idToTjslRegion[id] = region;
        }
    });

    // Helper function to create TJSL marker
    function createTjslMarker(tjsl) {
        if (!tjsl.latitude || !tjsl.longitude) return null;

        const statusText = tjsl.status == 1 ? 'Aktif' : 'Tidak Aktif';
        const statusClass = tjsl.status == 1 ? 'success' : 'secondary';
        const tanggalMulai = tjsl.tanggal_mulai ? new Date(tjsl.tanggal_mulai).toLocaleDateString('id-ID') : '-';
        const tanggalAkhir = tjsl.tanggal_akhir ? new Date(tjsl.tanggal_akhir).toLocaleDateString('id-ID') : '-';

        const popupContent = `
            <div style="min-width:200px;">
                <h6 style="color:#ff6b35;font-weight:bold;margin-bottom:8px;">Program TJSL</h6>
                <div style="margin-bottom:6px;">
                    <strong>${tjsl.nama_program}</strong>
                </div>
                ${tjsl.lokasi_program ? `<div style="font-size:12px;color:#666;margin-bottom:4px;">Lokasi: ${tjsl.lokasi_program}</div>` : ''}
                <div style="font-size:11px;color:#888;margin-bottom:4px;">
                    Periode: ${tanggalMulai} - ${tanggalAkhir}
                </div>
                <div style="margin-top:6px;">
                    <span class="badge bg-${statusClass}" style="font-size:10px;">${statusText}</span>
                </div>
                <div style="font-size:10px;color:#999;margin-top:6px;">
                    Koordinat: ${parseFloat(tjsl.latitude).toFixed(6)}, ${parseFloat(tjsl.longitude).toFixed(6)}
                </div>
            </div>
        `;

        const marker = L.marker([tjsl.latitude, tjsl.longitude], { icon: tjslIcon })
            .bindPopup(popupContent);

        // Tambahkan tooltip yang muncul hanya saat hover
        const tooltip = L.tooltip({
            permanent: false,
            direction: 'top',
            offset: [0, -45],
            className: 'tjsl-label',
            opacity: 1
        }).setContent(tjsl.nama_program);

        // Event handlers untuk menampilkan/menyembunyikan tooltip
        marker.on('mouseover', function(e) {
            this.bindTooltip(tooltip).openTooltip();
        });

        marker.on('mouseout', function(e) {
            this.closeTooltip();
        });

        return marker;
    }

    // Add TJSL markers for selected regions
    function addTjslMarkersForRegions(selectedRegions) {
        tjslLocations.forEach(tjsl => {
            if (!tjsl.id) return;
            const region = tjsl.nm_region ?? null;
            if (!selectedRegions.has(String(region))) return;
            if (tjslMarkersMap[tjsl.id]) return; // already added
            const marker = createTjslMarker(tjsl);
            if (marker) {
                marker.addTo(map);
                tjslMarkersMap[tjsl.id] = marker;
                tjslMarkers.push(marker);
            }
        });
    }

    // Remove TJSL markers for unselected regions
    function removeTjslMarkersForRegions(unselectedRegions) {
        Object.entries(tjslMarkersMap).forEach(([id, marker]) => {
            const region = idToTjslRegion[id];
            if (unselectedRegions.has(String(region))) {
                map.removeLayer(marker);
                delete tjslMarkersMap[id];
                const idx = tjslMarkers.indexOf(marker);
                if (idx !== -1) tjslMarkers.splice(idx, 1);
            }
        });
    }

    // Add all TJSL markers when no region filters available
    function addAllTjslMarkers() {
        tjslLocations.forEach(tjsl => {
            if (!tjsl.id || tjslMarkersMap[tjsl.id]) return;
            const marker = createTjslMarker(tjsl);
            if (marker) {
                marker.addTo(map);
                tjslMarkersMap[tjsl.id] = marker;
                tjslMarkers.push(marker);
            }
        });
    }

    // Update counter display
    function updateTjslCounter() {
        const tjslCountEl = document.getElementById('tjslMarkerCount');
        if (tjslCountEl) tjslCountEl.textContent = tjslMarkers.length;
    }

    // Initialize TJSL markers based on selected regions or all
    if (!regionSelect || regionSelect.options.length === 0) {
        // No region filters: show all TJSL markers
        addAllTjslMarkers();
    } else {
        // Show TJSL markers for initially selected regions
        addTjslMarkersForRegions(selectedRegions);
    }

    console.log(`Loaded ${tjslMarkers.length} TJSL location markers`);
    updateTjslCounter();

    // Fullscreen toggle handling: move form into overlay when fullscreen
    const fullscreenBtn = document.getElementById('toggleFullscreen');
    const mapCard = document.querySelector('.map-card');
    const mapOverlay = document.getElementById('mapOverlay');
    const closeOverlayBtn = document.getElementById('closeOverlay');
    const formColumn = document.querySelector('.row > .col-md-3');
    const formCard = formColumn ? formColumn.querySelector('.card') : null;
    let formPlaceholder = null;

    function enterFullscreen(){
        if (!mapCard) return;
        mapCard.classList.add('fullscreen');
        document.body.classList.add('overflow-hidden');
        if (mapOverlay) mapOverlay.classList.remove('d-none');
        // Move form card into overlay
        if (formCard && mapOverlay) {
            if (!formPlaceholder) {
                formPlaceholder = document.createElement('div');
                formPlaceholder.id = 'appFullscreenOverlayFix';
                formPlaceholder.style.display = 'none';
                formCard.parentNode.insertBefore(formPlaceholder, formCard);
            }
            mapOverlay.appendChild(formCard);
        }
        setTimeout(()=> map.invalidateSize(), 50);
    }

    function exitFullscreen(){
        if (!mapCard) return;
        mapCard.classList.remove('fullscreen');
        document.body.classList.remove('overflow-hidden');
        if (mapOverlay) mapOverlay.classList.add('d-none');
        // Move form card back to its column
        if (formCard && formPlaceholder && formPlaceholder.parentNode) {
            formPlaceholder.parentNode.insertBefore(formCard, formPlaceholder.nextSibling);
        }
        setTimeout(()=> map.invalidateSize(), 50);
    }

    if (fullscreenBtn) fullscreenBtn.addEventListener('click', function(){
        if (mapCard && mapCard.classList.contains('fullscreen')) {
            exitFullscreen();
            this.textContent = 'Full Screen';
        } else {
            enterFullscreen();
            this.textContent = 'Exit Full Screen';
        }
    });
    if (closeOverlayBtn) closeOverlayBtn.addEventListener('click', function(){
        exitFullscreen();
        if (fullscreenBtn) fullscreenBtn.textContent = 'Full Screen';
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
                // Helper to resolve unit_id for a row, using decoded id or unit name
                const getUnitIdForRow = (row) => {
                    if (!row) return null;
                    if (row.id != null && didToUnitId[row.id] != null) return didToUnitId[row.id];
                    if (row.unit) {
                        const key = String(row.unit).trim().toUpperCase();
                        if (unitNameToUnitId[key] != null) return unitNameToUnitId[key];
                    }
                    return null;
                };
                const getPolygonColorForRow = (row) => {
                    const uid = getUnitIdForRow(row);
                    const der = uid != null ? (derajatMap[String(uid)]?.derajat_hubungan ?? null) : null;
                    return colorForDerajat(der);
                };
                let listHtml = '<div class="mt-2"><h6>Kebun dalam radius:</h6><ol class="small ps-3">';
                data.within_radius.forEach(row => {
                    const dist = (typeof row.distance_km === 'number') ? row.distance_km.toFixed(3) : row.distance_km;
                    const dtype = (row.distance_type || 'center');
                        // Badge: untuk 'bbox' sesuaikan warna dengan warna polygon (Derajat)
                        let badge;
                        if (dtype === 'bbox') {
                            const polyColor = getPolygonColorForRow(row);
                            badge = `<span class="badge" title="bbox" style="background:${polyColor};width:10px;height:10px;display:inline-block;border-radius:8px;padding:0;vertical-align:middle;"></span>`;
                        } else {
                            const badgeClass = dtype === 'edge' ? 'bg-success' : 'bg-secondary';
                            badge = `<span class="badge ${badgeClass}" title="${dtype}" style="width:10px;height:10px;display:inline-block;border-radius:8px;padding:0;vertical-align:middle;"></span>`;
                        }
                    const title = row.center_distance_km ? ` title="jarak ke center: ${row.center_distance_km} km"` : '';
                    listHtml += `<li${title}>${badge} ${row.unit || row.id} (${dist} km)</li>`;
                    if (row.center) {
                        // Ubah warna marker sesuai tipe jarak
                        const color = dtype === 'bbox' ? getPolygonColorForRow(row) : (dtype === 'edge' ? 'green' : 'purple');
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
            const tjslTextSel = (window.jQuery && $('#tjsl_select').length) ? ($('#tjsl_select').find(':selected').text() || '') : '';
            if (!no_log && (title || stakeholderId || tjslId)) {
                const tbody = document.querySelector('#logTable tbody');
                const row = document.createElement('tr');
                const idx = tbody.children.length + 1;
                const insideText = data.inside ? (data.inside.unit || data.inside.id) : '-';
                const nearestText = data.nearest ? `${data.nearest.unit || data.nearest.id} (${(data.nearest.distance_km ?? 0).toFixed(3)} km)` : '-';
                const tjslText = tjslTextSel || (tjslId || '-');
                row.innerHTML = `
                    <td>${idx}</td>
                    <td>${new Date().toLocaleString()}</td>
                    <td>${title || '-'}</td>
                    <td>${lat.toFixed(6)}, ${lng.toFixed(6)}</td>
                    <td>${radius_km ?? '-'}</td>
                    <td>${insideText}</td>
                    <td>${nearestText}</td>
                    <td>${$('#stakeholder_select').find(':selected').text() || '-'}</td>
                    <td>${tjslText}</td>
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

    // --- Pick from map feature ---
    const pickBtn = document.getElementById('pickFromMap');
    const coordsInput = document.getElementById('coords');
    const pickHelp = document.getElementById('pickHelp');
    const autoSubmitWrap = document.getElementById('autoSubmitWrap');
    const autoSubmitPick = document.getElementById('autoSubmitPick');
    let escHandlerAttached = false;
    let pickGhostMarker = null;

    function setPickUI(active){
        window.__pickFromMapActive = !!active;
        if (pickBtn) pickBtn.classList.toggle('btn-success', !!active);
        if (pickBtn) pickBtn.classList.toggle('btn-outline-secondary', !active);
        if (pickBtn) pickBtn.textContent = active ? 'Ambil: klik peta…' : 'Ambil dari Peta';
        if (pickHelp) pickHelp.classList.toggle('d-none', !active);
        if (autoSubmitWrap) autoSubmitWrap.classList.toggle('d-none', !active);
        // Toggle map cursor style
        const mapEl = document.getElementById('map');
        if (mapEl) mapEl.classList.toggle('is-picking', !!active);
        // Create/remove a ghost marker that follows the mouse
        if (active) {
            if (!pickGhostMarker) {
                const ghostIcon = L.divIcon({
                    className: 'pick-ghost-icon',
                    html: '<svg width="26" height="26" viewBox="0 0 24 24" fill="#e53935" xmlns="http://www.w3.org/2000/svg"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5a2.5 2.5 0 110-5 2.5 2.5 0 010 5z"/></svg>',
                    iconSize: [26, 26],
                    iconAnchor: [13, 24]
                });
                pickGhostMarker = L.marker(map.getCenter(), { icon: ghostIcon, interactive: false, keyboard: false, zIndexOffset: 1000 });
            }
            if (!map.hasLayer(pickGhostMarker)) pickGhostMarker.addTo(map);
            map.on('mousemove', ghostFollow);
        } else {
            if (pickGhostMarker && map.hasLayer(pickGhostMarker)) map.removeLayer(pickGhostMarker);
            map.off('mousemove', ghostFollow);
        }
        if (active && !escHandlerAttached) {
            escHandlerAttached = true;
            document.addEventListener('keydown', escCancelHandler);
        }
        if (!active && escHandlerAttached) {
            escHandlerAttached = false;
            document.removeEventListener('keydown', escCancelHandler);
        }
    }

    function escCancelHandler(e){
        if (e.key === 'Escape') {
            setPickUI(false);
        }
    }

    function ghostFollow(e){
        if (!pickGhostMarker) return;
        pickGhostMarker.setLatLng(e.latlng);
    }

    if (pickBtn) {
        pickBtn.addEventListener('click', function(){
            const nowActive = !window.__pickFromMapActive;
            setPickUI(nowActive);
        });
        // Fill on map click
        map.on('click', function(e){
            if (!window.__pickFromMapActive) return;
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);
            if (coordsInput) {
                coordsInput.value = `${lat},${lng}`;
                coordsInput.dispatchEvent(new Event('change'));
            }
            // Place or move a persistent marker so the placemark "sticks" on the map
            const latlng = [parseFloat(lat), parseFloat(lng)];
            try {
                if (marker && map.hasLayer(marker)) {
                    marker.setLatLng(latlng).unbindPopup().bindPopup('Titik dipilih').openPopup();
                } else {
                    marker = L.marker(latlng).addTo(map).bindPopup('Titik dipilih').openPopup();
                }
            } catch(_) { /* no-op */ }
            setPickUI(false);
            if (autoSubmitPick && autoSubmitPick.checked) {
                document.getElementById('checkPointForm').dispatchEvent(new Event('submit'));
            }
        });
    }

    // Init select2 for stakeholder async search - in same DOMContentLoaded
    jQuery(function($){
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

    // TJSL program Select2 async search by name/id
    $('#tjsl_select').select2({
        placeholder: 'Cari program TJSL…',
        allowClear: true,
        ajax: {
            url: '{{ route('tjsl.search') }}',
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
        $('#tjsl_id').val(e.params.data.id);
    }).on('select2:clear', function(){
        $('#tjsl_id').val('');
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
        if (active.tjsl_id) {
            $.get('{{ route('tjsl.search') }}', { q: active.tjsl_id }, function(items){
                if (Array.isArray(items) && items.length) {
                    const item = items[0];
                    const option = new Option(item.text, item.id, true, true);
                    $('#tjsl_select').append(option).trigger('change');
                    $('#tjsl_id').val(item.id);
                } else {
                    $('#tjsl_id').val(active.tjsl_id);
                }
            });
        }
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

        // Helper: replay from data without navigation
    function replayFromData({lat,lng,radius,title,shId,shText,tjslId}){
            if (typeof lat === 'number' && typeof lng === 'number' && !isNaN(lat) && !isNaN(lng)) {
                $('#coords').val(lat + ',' + lng);
            }
            if (radius !== null && radius !== undefined && radius !== '') {
                $('#radius_km').val(radius);
            } else {
                $('#radius_km').val('');
            }
            $('#title').val(title || '');
            if (tjslId !== null && tjslId !== undefined && tjslId !== '') {
                $.get('{{ route('tjsl.search') }}', { q: tjslId }, function(items){
                    if (Array.isArray(items) && items.length) {
                        const item = items[0];
                        const opt = new Option(item.text, item.id, true, true);
                        $('#tjsl_select').empty().append(opt).trigger('change');
                        $('#tjsl_id').val(item.id);
                    } else {
                        $('#tjsl_select').val(null).trigger('change');
                        $('#tjsl_id').val(tjslId);
                    }
                });
            } else {
                $('#tjsl_select').val(null).trigger('change');
                $('#tjsl_id').val('');
            }
            if (shId) {
                const option = new Option(shText || ('ID ' + shId), shId, true, true);
                $('#stakeholder_select').empty().append(option).trigger('change');
                $('#stakeholder_id').val(shId);
            } else {
                $('#stakeholder_select').val(null).trigger('change');
                $('#stakeholder_id').val('');
            }
            window._replayActiveLog = true;
            $('#checkPointForm').trigger('submit');
        }

        // View handler (no page reload): prefill and auto-submit
        $(document).on('click', '.btn-log-view', function(e){
            e.preventDefault();
            e.stopPropagation();
            const $btn = $(this);
            replayFromData({
                lat: parseFloat($btn.data('lat')),
                lng: parseFloat($btn.data('lng')),
                radius: $btn.data('radius'),
                title: $btn.data('title') || '',
                shId: $btn.data('stakeholder-id'),
                shText: $btn.data('stakeholder-text'),
                tjslId: $btn.data('tjsl-id')
            });
        });

        // Safety net: intercept any legacy anchor clicks to prevent URL changes
        $(document).on('click', '#logTable a[href*="/polygons/log/"]', function(e){
            e.preventDefault();
            e.stopPropagation();
            const $tr = $(this).closest('tr');
            replayFromData({
                lat: parseFloat($tr.data('lat')),
                lng: parseFloat($tr.data('lng')),
                radius: $tr.data('radius'),
                title: $tr.data('title') || '',
                shId: $tr.data('stakeholder-id'),
                shText: $tr.data('stakeholder-text'),
                tjslId: $tr.data('tjsl-id')
            });
        });
    }); // End jQuery ready
}); // End DOMContentLoaded
</script>
@endpush
@endsection
