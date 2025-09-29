

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
<h1 class="h3 mb-2 text-gray-800">Master Data Kebun</h1>
<p class="mb-4">Klik unit pada tree menu untuk menampilkan polygon di atas peta.</p>

<div class="card shadow mb-4">
        
        <div class="card-body">
        </div>
</div>
<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row" >
            <div class="col-md-12">
                <label>Unit</label>
                <select name="id_unit" id="id_unit" class="form-control select2" required>
                <option value="">Pilih Unit</option>
                <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($u->id); ?>"><?php echo e($u->unit); ?> </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <br>
          </div>
        </div>
        <div class="row" >
            <div class="col-md-4" style="height: 350px;">
                <div class="map-container">
                    <div id="map"></div>
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
    var map = L.map('map', { zoomControl: false });
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{
        attribution: '&copy; OpenStreetMap contributors',
        maxZoom: 19
    }).addTo(map);
    
    // fokus awal Indonesia
    map.fitBounds([[-11,95],[6,141]]);
    L.control.zoom({ position: 'topright' }).addTo(map);
    
    var kebunJsons = <?php echo json_encode($kebunJsons->map(fn($k) => [
        'id' => $k->id, 'unit_id' => $k->unit_id, 'decoded' => $k->decoded
    ])) ?>;

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

<?php $__env->stopSection(); ?>





<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/peta/peta_region.blade.php ENDPATH**/ ?>