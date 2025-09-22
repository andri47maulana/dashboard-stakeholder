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
    .dt-length label{
        display:none;
    }
    .modalpopup {
        width: 80%;
        margin-top: 3%;
        margin-left: 10%;
        height: auto;
    }
    .form-group {
        margin-bottom: 0.5rem!important;
    }
    .form-control {
        font-size: 1em!important;
    }

</style>
    <h1 class="h3 mb-2 text-gray-800"> DETAIL SERTIFIKASI</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="{{url('/dokumen/perizinan')}}" method="get">              
            @csrf
                    <style>
                        .form-group {
                            margin-bottom: 0.5rem!important;
                        }
                        .form-control {
                            font-size: 1.1em!important;
                        }
                    </style>
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                        <div class="form-group row">
                                <label for="kebun" class="col-sm-3 control-label">Nama Kebun</label>
                                <label for="kebun" class="col-sm-6 control-label"> : {{isset($datauser->nama_kebun)?$datauser->nama_kebun:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="region" class="col-sm-3 control-label">Region</label>
                                <label for="region" class="col-sm-6 control-label"> : {{isset($datauser->regional)?$datauser->regional:''}}</label>
                            </div>                                                  
                        </div>
                    </div>
                    <div class="card shadow mb-4">
        
        <div class="card-body">       
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">No</th>
                            <th style="text-align:center">Nama Perizinan</th>
                            <th style="text-align:center">Nomor Perizinan</th>
                            <th style="text-align:center">Tanggal Mulai</th>
                            <th style="text-align:center">Tanggal Berakhir</th>
                            <th style="text-align:center">Dokumen</th>
                            <th style="text-align:center">Aksi</th>
                            
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        @foreach($m_perizinan as $keyperizinan)
                            <?php 
                                $tgl_start = "-";
                                $tgl_end = "-";
                                $dokumen_str = '-';
                                $nama_perizinan = '-';
                                $nomor = '-';
                                foreach($perizinan as $dokumen){
                                    
                                        
                                    if($dokumen->id_perizinan == $keyperizinan->id){
                                        $tgl_start = date('Y-m-d', strtotime($dokumen->tanggal_start));
                                        $tgl_end = date('Y-m-d', strtotime($dokumen->tanggal_end));
                                        $dokumen_str = $dokumen->dokumen;
                                        $nama_perizinan = $dokumen->nama_perizinan;
                                        $nomor = $dokumen->nomor;
                                    }
                                }
                            ?>
                        <tr>
                            <td>{{$i++}}</td>
                            <td style="text-align:center">{{$keyperizinan->nama}}</td>
                            <td style="text-align:center">{{$nomor}}</td>
                            <td style="text-align:center">{{$tgl_start}}</td>
                            <td style="text-align:center">{{$tgl_end}}</td>
                            <td style="text-align:center">{{$dokumen_str}}</td>
                            <td style="text-align:center">
                                <btn class="btn btn-warning btn-sm editdata" id="{{$keyperizinan->id}}"><i class="fas fa-fw fa-edit"></i></btn>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
                
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-success float-right">
                    Kembali
                    </button>
                </div>
              </form>
        </div>
    </div>
    <div class="modaleditdata">
        <div class="card shadow mb-4 modal modalpopup" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
                <h1 class="h3 mb-2 text-gray-800"> Edit Data Perizinan</h1>
                <br>
            
                <form action="{{url('/dokumen/updateperizinan')}}" method="post">
                    <input type="hidden" name="id" id="idperizinan" required="required" 
                        value="">
                    @csrf
                
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="jenis_perizinan" class="col-sm-3 control-label">Jenis Perizinan</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="jenis_perizinan" name="jenis_perizinan" aria-describedby="jenis_perizinan" value="" readonly="readonly"
                                    placeholder="Jenis Perizinan..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nama_perizinan" class="col-sm-3 control-label">Nama Perizinan</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama_perizinan" name="nama_perizinan" aria-describedby="nama_perizinan" value="" readonly="readonly"
                                    placeholder="Nama Perizinan..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nomor" class="col-sm-3 control-label">Nomor Perizinan</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nomor" name="nomor" aria-describedby="nomor" value=""
                                    placeholder="Nomor..." required>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_start" class="col-sm-3 control-label">Tanggal Mulai</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="tanggal_start" name="tanggal_start" aria-describedby="tanggal_start" value=""
                                    placeholder="Tanggal Mulai..." required>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_end" class="col-sm-3 control-label">Tanggal Berakhir</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="tanggal_end" name="tanggal_end" aria-describedby="tanggal_end" value=""
                                    placeholder="Tanggal Berakhir..." required>
                            </div>
                            <div class="form-group row">
                                <label for="dokumen" class="col-sm-3 control-label">Link Dokumen</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="dokumen" name="dokumen" aria-describedby="dokumen" value=""
                                    placeholder="Link Dokumen..." required>
                            </div>                                                               
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Edit </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    var modaladddata = $('.modaladddata').detach();
    var modaleditdata = $('.modaleditdata').detach();

    @if($errors->any())
        Swal.fire({
            title: "Error",
            text: "@foreach ($errors->all() as $error){{ $error }},@endforeach",
            icon: "error"
        });
        
    @endif
    @if(session('suksesdelete'))
        Swal.fire({
            title: "Data ini telah dihapus!",
            text: "Berhasil.",
            icon: "success"
        });
    @endif
    
    @if(session('sukses'))
        Swal.fire({
            title: "Sukses",
            text: "{{session('sukses')}}",
            icon: "success"
        });
    @endif
    $('.deletedata').click(function(e){
        e.preventDefault();
        var href = $(this).parent('a').attr('href');
        Swal.fire({
            title: "Apakah anda yakin ingin menghapus data?",
            text: "Anda tidak dapat mengembalikan data kembali!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Kembali"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
    $('.tambahdata').click(function(e){
        console.log(modaladddata);
        $('.modaladddata').remove();
        $('.modaleditdata').remove();
        $('body').append(modaladddata);
        $('#exampleModal').modal('show');
    })

    $('.editdata').click(function(e){
        $('.modaladddata').remove();
        $('.modaleditdata').remove();
        $('body').append(modaleditdata);
        
        
        var id = $(this).attr('id');
        console.log(id);
        $.ajax({
            url: "{{url('/dokumen/get_data_perizinan')}}/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                
                $('#idperizinan').val(id);
                $('#jenis_perizinan').val(response.jenis_perizinan);
                $('#nama_perizinan').val(response.nama_perizinan);
                $('#nomor').val(response.nomor);
                $('#tanggal_start').val(response.tanggal_start);
                $('#tanggal_end').val(response.tanggal_end);
                $('#dokumen').val(response.dokumen);
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    
    // $('.nav_sdm').addClass('active');
</script>
@endsection