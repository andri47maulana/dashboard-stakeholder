<?php $public = ""; ?>

 
<?php $__env->startSection('content'); ?>
<style>
    body {
        /* background-color: #add8e645; */
    }
    .content-body .container {
        margin: 0px;
        max-width: 100%!important;
        height:99%;
    }
    .content-body {
        padding: 3px!important;
    }
    .marker {
  position: absolute;
  width: 12px;
  height: 12px;
  background: #e74c3c;
  border: 2px solid #fff;
  border-radius: 50%;
  box-shadow: 0 0 5px rgba(0,0,0,0.3);
  cursor: pointer;
  z-index: 3;
}

.marker::after {
  content: "";
  position: absolute;
  top: 50%; left: 50%;
  width: 12px; height: 12px;
  border-radius: 50%;
  background: rgba(231, 76, 60, 0.5);
  transform: translate(-50%, -50%);
  animation: pulse 1.5s infinite;
}

@keyframes pulse {
  0%   { transform: translate(-50%, -50%) scale(1); opacity: 0.8; }
  100% { transform: translate(-50%, -50%) scale(2.5); opacity: 0; }
}
.modal-body {
    max-height: 70vh;   /* tinggi maksimal modal */
    overflow-y: auto;   /* scroll jika konten tinggi */
}

.dataTables_wrapper .dataTables_scroll {
    overflow: auto;     /* scroll horizontal DataTable */
}


</style>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- DataTales Example -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-primary text-uppercase mb-1">
                            REGIONAL</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800" id="cardRegional">PTPN I</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-success text-uppercase mb-1">
                            STAKEHOLDER</div>
                        <div class="h3 mb-0 font-weight-bold text-gray-800" id="cardStakeholder"><?php echo e($datastakeholderall); ?></div>
                        <span class="badge badge-success" id="cardStakeholder2"><u><i>List Stakeholder</i></u></span> 
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-warning text-uppercase mb-1">
                            KATEGORI</div>
                        <div class="h7 mb-0 font-weight-bold text-gray-800" id="cardGov"><?php echo e($persen_datagovernance); ?>% GOVERNANCE</div>
                        <div class="h7 mb-0 font-weight-bold text-gray-800" id="cardNonGov"><?php echo e($persen_datanongovernance); ?>% NON GOVERNANCE</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div class="card shadow mb-4" style="position: relative; overflow: hidden;">
    <!-- background image -->
    <img src="<?php echo e(url('/')); ?><?php echo e($public); ?>/kebunteh.jpg"
         alt="Kebun Teh"
         style="display:block; width:100%; height:auto; filter: brightness(30%);">

    <!-- overlay peta -->
    <img src="<?php echo e(url('/')); ?><?php echo e($public); ?>/id.svg"
         alt="Peta Indonesia"
         style="position:absolute; top:0; left:0; width:100%; height:100%; 
                object-fit:contain; z-index:2; opacity:0.9;">

    <div class="marker" style="
        position:absolute; 
        top:30%; left:11%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 1"
        title="Regional 1 - Sumatera Utara">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:61%; left:25%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        data-region="PTPN I Regional 2"
        
        title="Regional 2 - Jawa Barat">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:62%; left:34%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 3"
        title="Regional 3 - Jawa Tengah">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:65%; left:40%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 4"
        title="Regional 4 - Jawa Timur">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:65%; left:42%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 5"
        title="Regional 5 - Jawa Timur">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:26%; left:8%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 6"
        title="Regional 6 - Aceh">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:56%; left:23%;  
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 7"
        title="Regional 7 - Lampung">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:56%; left:53%;  
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        
        data-region="PTPN I Regional 8"
        title="Regional 8 - Makasar">
    </div>
    <div class="marker" style="
        position:absolute; 
        top:60%; left:26%; 
        width:20px; height:20px; 
        background:#e74c3c;         /* warna merah */
        border:2px solid #fff;      /* outline putih biar kontras */
        border-radius:50%; 
        box-shadow:0 0 5px rgba(0,0,0,0.3); /* bayangan halus */
        cursor:pointer; 
        z-index:3;"
        data-region="PTPN I HO"
        
        title="Regional 2 - Jawa Barat">
    </div>
</div>

<!-- Modal Stakeholder -->
<!-- Modal Stakeholder -->
<div class="modal fade" id="modalStakeholder" tabindex="-1" role="dialog" aria-labelledby="modalStakeholderLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalStakeholderLabel">List Stakeholder</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <table id="tableStakeholder" class="table table-bordered" width="100%" cellspacing="0">
          <thead>
            <tr>
              <th>No</th>
              <th>Stakeholder</th>
              <th>Kategori</th>
              <th>Identitas PIC</th>
              <th>Jabatan PIC</th>
              <th>Daerah</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<!-- Modal Detail Instansi -->
<div class="modal fade" id="modalDetailInstansi" tabindex="-1" role="dialog" aria-labelledby="modalDetailInstansiLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="modalDetailInstansiLabel">Detail Stakeholder</h5>
        <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body" id="modalDetailContent">
        <div class="text-center py-5">
          <i class="fas fa-spinner fa-spin fa-2x"></i>
          <p>Loading data...</p>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>



<script>
    document.querySelectorAll('.marker').forEach(marker => {
        marker.addEventListener('click', function () {
            let region = this.dataset.region;

            // Update card "REGIONAL" dulu
            document.getElementById('cardRegional').textContent = region;

            // AJAX request ke backend
            fetch(`/get-data-dashboard?region=${region}`)
                .then(res => res.json())
                .then(data => {
                    // update card sesuai response
                    document.getElementById('cardStakeholder').textContent = data.datastakeholderall;
                    document.getElementById('cardGov').textContent = data.persen_datagovernance + '% GOVERNANCE';
                    document.getElementById('cardNonGov').textContent = data.persen_datanongovernance + '% NON GOVERNANCE';
                })
                .catch(err => console.error(err));
        });
    });

</script>
<script>
// Buka modal Stakeholder
document.getElementById('cardStakeholder2').addEventListener('click', function () {
    let region = document.getElementById('cardRegional').textContent;

    $('#modalStakeholder').modal('show');
    $('#modalStakeholderLabel').text(`List Stakeholder ${region}`);

    // Destroy DataTable lama jika ada
    if ($.fn.DataTable.isDataTable('#tableStakeholder')) {
        $('#tableStakeholder').DataTable().destroy();
    }

    // Load DataTable
    let table = $('#tableStakeholder').DataTable({
        ajax: `/get-stakeholder?region=${region}`,
        columns: [
            { data: null },
            { 
                data: 'nama_instansi',
                render: function(data, type, row) {
                    return `<a href="javascript:void(0);" class="detail-instansi" data-id="${row.id}">
                                ${data} <i class="fas fa-fw fa-search"></i>
                            </a>`;
                }
            },
            { data: 'kategori' },
            { 
                data: null,
                render: function (data, type, row) {
                    return row.nama_pic + ' - ' + row.nomorkontak_pic;
                }
            },
            { data: 'jabatan_pic' },
            { data: 'daerah_instansi' }
        ],
        columnDefs: [
            {
                targets: 0,
                render: function (data, type, row, meta) { return meta.row + 1; }
            }
        ],
        responsive: true,
        scrollX: true,
        autoWidth: false,
        destroy: true
    });

    // Klik link detail instansi
    // Klik link detail instansi
$('#tableStakeholder').off('click', '.detail-instansi').on('click', '.detail-instansi', function() {
    let id = $(this).data('id');
    $('#modalDetailInstansi').modal('show');
    $('#modalDetailContent').html(`
        <div class="text-center py-5">
            <i class="fas fa-spinner fa-spin fa-2x"></i>
            <p>Loading data...</p>
        </div>
    `);

    $.ajax({
        url: `/get-detail-instansi/${id}`,
        method: 'GET',
        success: function(data) {
            let html = `
                <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-info text-white"><i class="fas fa-building"></i> Instansi</div>
                                <div class="card-body" style="font-size:0.9em;">                                    
                                    <p><strong>Region:</strong> ${data.region}</p>
                                    <p><strong>Kategori:</strong> <span class="badge badge-success">${data.kategori}</span></p>
                                    <p><strong>Kebun/Unit:</strong> ${data.kebun}</p>
                                    <p><strong>Instansi/Lembaga:</strong> ${data.nama_instansi}</p>

                                    <p><strong>Daerah:</strong> Prov. ${data.prov_nama}, Kab./Kota ${data.kab_nama}, Kec. ${data.kec_nama}, Desa/Kel. ${data.desa_nama}</p>
                                    <p><strong>Dokumen Pendukung:</strong> 
                                    ${
                                        data.dokumenpendukung 
                                        ? `<a href="<?php echo e(asset('pdf')); ?>/${data.dokumenpendukung}" target="_blank">
                                            <u><i>Klik untuk melihat</i></u>
                                        </a>`
                                        : '-'
                                    }
                                </p>
                                    
                                    
                                    <br>
                                    <br>
                                </div>
                            </div>
                            

                            
                        </div>

                        <div class="col-md-6">
                        <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-success text-white"><i class="fas fa-user"></i> PIC</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <p><strong>Nama PIC:</strong> ${data.nama_pic}</p>
                                    <p><strong>Jabatan:</strong> ${data.jabatan_pic}</p>
                                    <p><strong>Kontak:</strong> ${data.nomorkontak_pic}</p>
                                    <p><strong>Email / Sosial Media:</strong> ${data.email ?? '-'}</p>
                                    <p><strong>Nama PIC 2:</strong> ${data.nama_pic2 ?? '-'}</p>
                                    <p><strong>Jabatan PIC 2:</strong> ${data.jabatan_pic2 ?? '-'}</p>
                                    <p><strong>Kontak PIC 2:</strong> ${data.nomorkontak_pic2 ?? '-'}</p>
                                    <p><strong>Email / Sosial Media PIC 2:</strong> ${data.email ?? '-'}</p>
                                </div>
                            </div>

                            
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-3 shadow-sm">
                                
                                <div class="card-header bg-secondary text-white"><i class="fas fa-chart-bar"></i> Data Analisis Hubungan Stakeholder</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Skala Kepentingan: </strong>${data.skala_kepentingan ?? '-' }</p>
                                                <p><strong>Skala Pengaruh: </strong>${data.skala_pengaruh ?? '-' }</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Curent Condition: </strong> ${data.curent_condition ?? '-'}</p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Note:  </strong>${data.hasil_skala ?? '-' }</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x:auto;">
                                                <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%; min-width: 600px;">
                                                    <tr style="background-color: #007BFF; color: white; text-align: center;">
                                                        <th style="width: 33%;">Ekspektasi Stakeholder</th>
                                                        <th style="width: 33%;">Ekspektasi Perusahaan</th>
                                                        <th style="width: 33%;">Saran Bagi Manajemen</th>
                                                    </tr>
                                                    <tr>
                                                        <td>${data.ekspektasi_stakeholder ?? '-' }</td>
                                                        <td>${data.ekspektasi_ptpn ?? '-' }</td>
                                                        <td>${data.saranbagimanajemen ?? '-' }</td>
                                                    </tr>
                                                </table>
                                            </div>


                                        </div>
                                    </div>               
                                </div>
                            </div>
                            
                        </div>

                    </div>
            `;
            $('#modalDetailContent').html(html);
        },
        error: function() {
            $('#modalDetailContent').html('<p class="text-danger text-center">Data tidak ditemukan.</p>');
        }
    });
});

});

// Fix scroll after nested modal close
$('#modalDetailInstansi').on('hidden.bs.modal', function () {
    if ($('.modal.show').length) {
        $('body').addClass('modal-open');
    }
});

</script>

<script>
    $('.nav_sdm').addClass('active');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\APP\dashboard-stakeholder\resources\views/home/home.blade.php ENDPATH**/ ?>