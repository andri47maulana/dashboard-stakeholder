
@extends('layouts.app')

@section('content')
<style>
    .content-body .container {
        margin: 0px;
        max-width: 100%!important;
        height:99%;
    }
    .content-body {
        padding: 3px!important;
    }
    .form-group { margin-bottom: 0.5rem!important; }
    .form-control { font-size: 1em!important; }

    /* Legend */
    .map-legend {
        background: white;
        padding: 10px;
        border-radius: 5px;
        line-height: 1.5em;
        font-size: 0.9em;
    }
    .legend-color {
        display: inline-block;
        width: 16px;
        height: 16px;
        margin-right: 5px;
        vertical-align: middle;
    }
</style>

<h1 class="h3 mb-2 text-gray-800">Master Data Kebun</h1>
<p class="mb-4">Master Data Kebun PT Perkebunan Nusantara I.</p>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
        <h2>Detail Unit: {{ $unit->unit }} ({{ $unit->region }})</h2>
        <a href="{{ route('units.list') }}" class="btn btn-secondary mb-3">Kembali</a>

        <!-- Tombol lihat seluruh map -->
        @if($kebunJsons->count())
            <button class="btn btn-success mb-3 lihat-mapall">
                Lihat Seluruh Map Unit
            </button>
        @endif
        <!-- Tombol Tambah Polygon -->
        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#addPolygonModal">
            Tambah Polygon
        </button>
        @forelse($kebunJsons as $kebun)
            <div class="card mb-3">
                <div class="card-body">
                    <h5>{{ $kebun->decoded['name'] ?? '-' }}</h5>
                    <p><strong>ID:</strong> {{ $kebun->decoded['id'] ?? '-' }}</p>
                    <p><strong>Geometry Type:</strong> {{ $kebun->decoded['geometrytype'] ?? '-' }}</p>
                    <p><strong>Tile URL:</strong>
                        <a href="{{ $kebun->decoded['tileurl'] }}" target="_blank">{{ $kebun->decoded['tileurl'] }}</a>
                    </p>
                    <p><strong>Bounds:</strong> {{ json_encode($kebun->decoded['bounds'] ?? '-') }}</p>

                    <!-- tombol lihat 1 kebun -->
                    <button class="btn btn-primary lihat-map" data-index="{{ $loop->index }}">
                        Lihat Map
                    </button>
                    <button class="btn btn-warning btn-edit-polygon" data-id="{{ $kebun->id }}" 
                            data-judul="{{ $kebun->decoded['name'] ?? '' }}"
                            data-json='@json($kebun->decoded)'>
                        Edit
                    </button>

                    <form method="POST" action="{{ route('kebun_json.destroy', $kebun->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus polygon ini?');">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger">Delete</button>
                    </form>

                </div>
            </div>
        @empty
            <div class="alert alert-warning">Tidak ada data kebun_json untuk unit ini.</div>
        @endforelse
    </div>
</div>
    </div>
    </div>
<!-- Modal Single Kebun -->
<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="max-width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Map Kebun</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <div id="mapContainer" style="height:600px; width:100%"></div>
      </div>
    </div>
  </div>
</div>

<!-- Modal Semua Kebun -->
<div class="modal fade" id="mapModalAll" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl" style="max-width:90%">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Map Seluruh Kebun Unit</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <div id="mapContainerAll" style="height:600px; width:100%"></div>
      </div>
    </div>
  </div>
</div>




<!-- Modal Tambah Polygon -->
<div class="modal fade" id="addPolygonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Polygon Kebun</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <form id="polygonForm" method="POST" action="{{ route('kebun_json.store') }}">
            @csrf
            <input type="hidden" name="unit_id" value="{{ $unit->id }}">
            
            <div class="mb-3">
                <label class="form-label">Region</label>
                <input type="text" class="form-control" value="{{ $unit->region }}" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" value="{{ $unit->unit }}" readonly>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" name="judul" placeholder="Masukkan judul polygon" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Metode Input Polygon</label>
                <select class="form-select" id="polygonType" name="polygon_type">
                    <option value="url">Dari URL</option>
                    <option value="json">Isi JSON Langsung</option>
                </select>
            </div>

            <!-- URL -->
            <div class="mb-3 polygon-url">
                <label class="form-label">URL Polygon (Protobuf/GeoJSON)</label>
                <input type="text" class="form-control" name="polygon_url" placeholder="Masukkan URL polygon">
            </div>

            <!-- JSON -->
            <div class="mb-3 polygon-json d-none">
                <label class="form-label">Isi Data JSON Polygon</label>
                <textarea class="form-control" name="polygon_json" rows="6" placeholder="Masukkan JSON polygon"></textarea>
            </div>

            <button type="submit" class="btn btn-success">Simpan Polygon</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Edit Polygon -->
<div class="modal fade" id="editPolygonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit Polygon Kebun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <form id="editPolygonForm" method="POST" action="">
            @csrf
            @method('PUT')
            
            <div class="mb-3">
                <label class="form-label">Region</label>
                <input type="text" class="form-control" id="editRegion" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Unit</label>
                <input type="text" class="form-control" id="editUnit" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label">Judul</label>
                <input type="text" class="form-control" id="editJudul" name="judul_edit" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Metode Input Polygon</label>
                <select class="form-select" id="editPolygonType" name="polygon_type_edit">
                    <option value="url">Dari URL</option>
                    <option value="json">Isi JSON Langsung</option>
                </select>
            </div>

            <!-- URL -->
            <div class="mb-3 edit-polygon-url">
                <label class="form-label">URL Polygon (Protobuf/GeoJSON)</label>
                <input type="text" class="form-control" name="polygon_url_edit" id="editPolygonUrl">
            </div>

            <!-- JSON -->
            <div class="mb-3 edit-polygon-json d-none">
                <label class="form-label">Isi Data JSON Polygon</label>
                <textarea class="form-control" name="polygon_json_edit" id="editPolygonJson" rows="6"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Polygon</button>
        </form>
      </div>
    </div>
  </div>
</div>


<script>
    // Toggle input URL / JSON di modal edit
    $('#editPolygonType').on('change', function() {
        var val = $(this).val();
        if(val === 'url'){
            $('.edit-polygon-url').removeClass('d-none');
            $('.edit-polygon-json').addClass('d-none');
        } else {
            $('.edit-polygon-json').removeClass('d-none');
            $('.edit-polygon-url').addClass('d-none');
        }
    });

    // Tombol Edit Polygon
    $(document).on('click', '.btn-edit-polygon', function() {
        var id = $(this).data('id');
        var judul = $(this).data('judul');
        var json = $(this).data('json');
        var url = $(this).data('url'); // jika ada URL polygon

        $('#editRegion').val('{{ $unit->region }}');
        $('#editUnit').val('{{ $unit->unit }}');
        $('#editJudul').val(judul);

        if(url){
            $('#editPolygonType').val('url').trigger('change');
            $('#editPolygonUrl').val(url);
        } else {
            $('#editPolygonType').val('json').trigger('change');
            $('#editPolygonJson').val(JSON.stringify(json, null, 2));
        }

        // $('#editPolygonForm').attr('action', '/kebun_json/' + id);
        $('#editPolygonForm').attr('action', '{{ route("kebun_json.update", ":id") }}'.replace(':id', id));

        var editModal = new bootstrap.Modal(document.getElementById('editPolygonModal'));
        editModal.show();
    });


</script>
<script>
$(document).ready(function(){
    $('#polygonType').on('change', function(){
        let val = $(this).val();
        if(val === 'url'){
            $('.polygon-url').removeClass('d-none');
            $('.polygon-json').addClass('d-none');
        }else{
            $('.polygon-url').addClass('d-none');
            $('.polygon-json').removeClass('d-none');
        }
    });
});
</script>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>

<script>
var kebunJsons = @json($kebunJsons->map(fn($k) => $k->decoded));
var mapSingle, mapAll;
var colors = ["#FF5733","#33C1FF","#28A745","#FFC300","#9B59B6","#E67E22"];

function getColor(index){ return colors[index % colors.length]; }

// ===== Single Map per Kebun =====
$(document).on("click", ".lihat-map", function() {
    var index = $(this).data("index");
    var json = kebunJsons[index];
    var color = getColor(index);

    // Tutup modal All Map dulu kalau masih terbuka
    var modalAllEl = document.getElementById('mapModalAll');
    if ($(modalAllEl).hasClass('show')) {
        bootstrap.Modal.getInstance(modalAllEl).hide();
    }

    var mapModal = new bootstrap.Modal(document.getElementById('mapModal'), {
        backdrop: 'static',
        keyboard: true
    });
    mapModal.show();

    setTimeout(() => {
        if(mapSingle) mapSingle.remove();

        mapSingle = L.map('mapContainer');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapSingle);

        // Polygon dengan isi (fill) dan garis
        L.vectorGrid.protobuf(json.tileurl, {
            vectorTileLayerStyles: {
                [json.id || index]: {
                    weight: 3,        // garis
                        color: color,     // warna garis
                        fill: true,       // harus true
                        fillColor: color, // warna isi
                        fillOpacity: 0.5             // tebal garis
                }
            },
            interactive: false
        }).addTo(mapSingle);

        if(json.bounds?.length === 4){
            mapSingle.fitBounds([[json.bounds[1],json.bounds[0]], [json.bounds[3],json.bounds[2]]]);
        } else {
            mapSingle.setView([json.center[1], json.center[0]], 14);
        }
    }, 300);
});

// ===== Semua Map Kebun =====
$(document).on("click", ".lihat-mapall", function() {
    var mapModal = new bootstrap.Modal(document.getElementById('mapModalAll'), {
        backdrop: 'static',
        keyboard: true
    });
    mapModal.show();

    setTimeout(() => {
        if(mapAll) mapAll.remove();

        mapAll = L.map('mapContainerAll');
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAll);

        var allBounds = L.latLngBounds([]);
        var legendHtml = '<div class="map-legend"><b>Legend:</b><br>';

        kebunJsons.forEach((json, index) => {
            var color = getColor(index);

            L.vectorGrid.protobuf(json.tileurl, {
                vectorTileLayerStyles: {
                    [json.id || index]: {
                        weight: 3,        // garis
                        color: color,     // warna garis
                        fill: true,       // harus true
                        fillColor: color, // warna isi
                        fillOpacity: 0.5           // tebal garis
                    }
                },
                interactive: false
            }).addTo(mapAll);

            if(json.bounds?.length === 4){
                allBounds.extend([[json.bounds[1],json.bounds[0]], [json.bounds[3],json.bounds[2]]]);
            }

            legendHtml += `<div><span class="legend-color" style="background:${color}"></span>${json.name}</div>`;
        });

        if(allBounds.isValid()){
            mapAll.fitBounds(allBounds, { padding:[30,30] });
        }

        // Legend
        var legend = L.control({position: 'topright'});
        legend.onAdd = function() {
            var div = L.DomUtil.create('div', 'map-legend');
            div.innerHTML = legendHtml;
            return div;
        };
        legend.addTo(mapAll);

        mapAll.invalidateSize();
    }, 300);
});

</script>
@endsection
