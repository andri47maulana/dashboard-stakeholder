<?php $public = ""; ?>
@extends('layouts.app')
 
@section('content')
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
    width: 1.5%;  /* ukurannya juga responsif */
    aspect-ratio: 1/1; /* supaya tetap bulat */
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

/* Make SVG map more prominent but elegant */
.map-overlay {
    opacity: 0.98; /* slightly less than 1 to avoid full washout */
    /* Stronger color separation + layered shadows for 3D effect */
    filter: hue-rotate(45deg) saturate(1.6) contrast(1.25) brightness(1.05)
            drop-shadow(0 1px 0 rgba(255,255,255,0.55))
            drop-shadow(0 2px 6px rgba(0,0,0,0.55))
            drop-shadow(0 12px 20px rgba(0,0,0,0.2));
    /* Use normal blend to prevent the map from disappearing on bright areas */
    mix-blend-mode: normal;
    will-change: filter, transform;
    transform: translateZ(0);
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
                        <div class="h3 mb-0 font-weight-bold text-gray-800" id="cardStakeholder">{{ $datastakeholderall }}</div>
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
                        <div class="h7 mb-0 font-weight-bold text-gray-800" id="cardGov">{{ $persen_datagovernance }}% PEMERINTAH</div>
                        <div class="h7 mb-0 font-weight-bold text-gray-800" id="cardNonGov">{{ $persen_datanongovernance }}% NON PEMERINTAH</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


{{-- <div class="card shadow mb-4">
    <img src="{{url('/')}}{{$public}}/kebunteh.jpg" style="width: 100%; height: auto;">
</div> --}}
<div class="card shadow mb-4 relative overflow-hidden">
    <!-- background image -->
    <img src="{{ url('/') }}{{ $public }}/kebunteh.jpg" style="opacity: 0.7"
        alt="Kebun Teh"
        class="w-full h-auto brightness-50">

    <!-- overlay peta -->
    <div class="absolute inset-0">
    <img src="{{ url('/') }}{{ $public }}/id.svg"
     alt="Peta Indonesia"
     class="map-overlay"
     style="position:absolute; top:0; left:0; width:100%; height:100%; 
        object-fit:contain; z-index:2;">
        
        <!-- marker contoh -->
        <div class="marker" style="top:30%; left:11%;" data-region="PTPN I Regional 1" title="Regional 1 - Sumatera Utara"></div>
        <div class="marker" style="top:62.5%; left:28.5%;" data-region="PTPN I Regional 2" title="Regional 2 - Jawa Barat"></div>
        <div class="marker" style="top:62%; left:34%;" data-region="PTPN I Regional 3" title="Regional 3 - Jawa Tengah"></div>
        {{-- <div class="marker" style="top:65%; left:40%;" data-region="PTPN I Regional 4" title="Regional 4 - Jawa Timur"></div> --}}
        <div class="marker" style="top:65%; left:40%;" data-region="PTPN I Regional 5" title="Regional 5 - Jawa Timur"></div>
        <div class="marker" style="top:26%; left:8%;" data-region="PTPN I Regional 6 KSO" title="Regional 6 KSO - Aceh"></div>
        <div class="marker" style="top:56%; left:23%;" data-region="PTPN I Regional 7" title="Regional 7 - Lampung"></div>
        <div class="marker" style="top:56%; left:53%;" data-region="PTPN I Regional 8" title="Regional 8 - Makasar"></div>
        <div class="marker" style="top:60%; left:26.8%;" data-region="PTPN I HO" title="Head Office"></div>
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

                    // Show popup near marker
                    showMarkerPopup(this, region, data.datastakeholderall, data.persen_datagovernance, data.persen_datanongovernance);
                })
                .catch(err => console.error(err));
        });
    });

    // Helper to show popup near marker
    function showMarkerPopup(marker, region, stakeholder, persenGov, persenNonGov) {
        // Remove any existing popup
        let old = document.getElementById('markerPopup');
        if (old) old.remove();

        // Create popup element
        let popup = document.createElement('div');
        popup.id = 'markerPopup';
        popup.style.position = 'absolute';
        popup.style.zIndex = 9999;
        popup.style.background = 'rgba(255,255,255,0.97)';
        popup.style.border = '1px solid #ddd';
        popup.style.borderRadius = '8px';
        popup.style.boxShadow = '0 2px 12px rgba(0,0,0,0.15)';
        popup.style.padding = '12px 18px';
        popup.style.fontSize = '1em';
        popup.style.minWidth = '220px';
        popup.style.pointerEvents = 'auto';
        popup.innerHTML = `
            <div style="font-weight:bold; color:#2c3e50; margin-bottom:4px;">${region}</div>
            <div><span style="color:#16a085; font-weight:bold;">${stakeholder}</span> Stakeholder</div>
            <div style="margin-top:6px;">
                <span style="color:#f39c12;">${persenGov}% Pemerintah</span> &nbsp;|
                <span style="color:#3498db;">${persenNonGov}% Non Pemerintah</span>
            </div>
            <button id="closeMarkerPopup" style="margin-top:8px; float:right; background:#e74c3c; color:#fff; border:none; border-radius:4px; padding:2px 8px; cursor:pointer; font-size:0.9em;">Tutup</button>
        `;

        // Position popup near marker
        let rect = marker.getBoundingClientRect();
        let parentRect = marker.offsetParent.getBoundingClientRect();
        // Offset: below and right of marker
        popup.style.top = (marker.offsetTop + marker.offsetHeight + 8) + 'px';
        popup.style.left = (marker.offsetLeft + marker.offsetWidth + 8) + 'px';

        // Insert popup into overlay container
        marker.offsetParent.appendChild(popup);

        // Close button
        popup.querySelector('#closeMarkerPopup').onclick = function() {
            popup.remove();
        };
    }

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
                                        ? `<a href="{{ asset('pdf') }}/${data.dokumenpendukung}" target="_blank">
                                            <u><i>Klik untuk melihat</i></u>
                                        </a>`
                                        : '-'
                                    }
                                </p>
                                    {{-- <p><strong>Curent Condition:</strong> ${data.curent_condition ?? '-'}</p> --}}
                                    
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
@endsection