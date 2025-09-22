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
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Master Data Perizinan</h1>
    <p class="mb-4">Master Data Perizinan PT Perkebunan Nusantara I.</p>
    <div class="card shadow mb-4">
        <div class="card-body row" style="font-size: 0.85em;">
            <div class="col-md-3">          
                <div class="form-group row">
                    <label for="jenis_perizinan" class="col-md-4 control-label" style="text-align: right"><strong>Jenis Perizinan</strong></label>
                    <select name="jenis_perizinan" id="jenis_perizinan" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    @foreach($datajenisperizinan as $perizinan)
                    <option value="{{$perizinan->jenis_perizinan}}" @if($searchperizinan==$perizinan->jenis_perizinan) selected @endif>{{$perizinan->jenis_perizinan}}</option>
                    @endforeach
                        
                    </select>
                </div>
            </div>
            
        </div>
        <div class="card-footer">
            <!-- <a href="{{url('/')}}/dash/stakeholder"> -->
            <button class="btn btn-outline-warning float-right btn-sm cancelsearch" style="margin-right: 10px;"><i class="fas fa-fw fa-stop"></i> Cancel </button>
            <!-- </a> -->
            &nbsp;
            <button type="submit" name="submit" class="btn btn-outline-success float-right btn-sm cari" style="margin-right: 10px;"><i class="fas fa-fw fa-filter"></i> Filter </button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        
        <div class="card-body">
            <!-- <a href="{{url('/dash/form_stakeholder_add')}}">
                <button class="btn btn-primary btn-rounded add_user">
                <i class="fas fa-fw fa-plus"></i> Tambah Data
                </button>
            </a> -->
            <button type="button" class="btn btn-primary tambahdata">
              <i class="fas fa-fw fa-plus"></i> Tambah Data
            </button>
            <a href="{{url('/exportperizinan')}}">
                <button class="btn btn-success btn-rounded add_user">
                <i class="fas fa-fw fa-download"></i> Export
                </button>
            </a>
            
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">Nama</th>
                            <th style="text-align:center">Jenis Perizinan</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        @foreach($dataalluser as $key)
                        <tr>
                            <td ><a href="{{url('/masterdata/form_perizinan_detail')}}/{{$key->id}}"><i class="fas fa-fw fa-search"></i></a> {{$key->nama}}</td>
                            <td style="text-align:center">{{$key->jenis_perizinan}}</td>
                            <td style="text-align:center" width="100">
                                <btn class="btn btn-warning btn-sm editdata" id="{{$key->id}}"><i class="fas fa-fw fa-edit"></i></btn>
                                <a href="{{url('/masterdata/deleteperizinan')}}/{{$key->id}}">
                                    <btn class="btn btn-danger btn-sm deletedata"><i class="fas fa-fw fa-trash"></i></btn>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modaladddata">
        <div class="card shadow mb-4 modal modalpopup" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
            <h1 class="h3 mb-2 text-gray-800"> Tambah Data Perizinan</h1>
            <br>
            <form action="{{url('/masterdata/storeperizinan')}}" method="post">
            @csrf
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 control-label">Nama</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama" name="nama" aria-describedby="nama" value=""
                                    placeholder="Nama..." required>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_perizinan" class="col-md-3 control-label">Jenis Perizinan</label>
                                <select name="jenis_perizinan" id="jenis_perizinan" class="col-sm-6 form-control" required>
                                
                                    <option value="Perizinan">Perizinan</option>
                                    <option value="Perizinan Lingkungan">Perizinan Lingkungan</option>
                                    <option value="Perizinan Lainnya">Perizinan Lainnya</option>
                                    <option value="HAKI">HAKI</option>
                                    <option value="SLO">SLO</option>
                                    <option value="BPOM">BPOM</option>
                                    
                                </select>
                            </div>                               
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Submit </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="modaleditdata">
        <div class="card shadow mb-4 modal modalpopup" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
            <h1 class="h3 mb-2 text-gray-800"> Edit Data Perizinan</h1>
            <br>
            
            <form action="{{url('/masterdata/updateperizinan')}}" method="post">
                <input type="hidden" name="id" id="idperizinan" required="required" 
                    value="">
            @csrf
            
            <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="namaedit" class="col-sm-3 control-label">Nama</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="namaedit" name="namaedit" aria-describedby="namaedit" value=""
                                    placeholder="Nama..." required>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_perizinan" class="col-md-3 control-label">Jenis Perizinan</label>
                                <select name="jenis_perizinan" id="jenis_perizinan" class="col-sm-6 form-control" required>
                                
                                    <option value="Perizinan">Perizinan</option>
                                    <option value="Perizinan Lingkungan">Perizinan Lingkungan</option>
                                    <option value="Perizinan Lainnya">Perizinan Lainnya</option>
                                    <option value="HAKI">HAKI</option>
                                    <option value="SLO">SLO</option>
                                    <option value="BPOM">BPOM</option>
                                    
                                </select>
                            </div>                                                        
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Update </button>
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
            title: "Deleted!",
            text: "Your file has been deleted.",
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
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
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
            url: "{{url('/masterdata/get_data_perizinan')}}/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                console.log(response.nama);
                $('#idperizinan').val(id);
                $('#namaedit').val(response.nama);
                $('#jenis_perizinan').val(response.jenis_perizinan);
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    $("#jenis_perizinan, #nama").select2({});
    $('.cari').click(function(){
        var jenis_perizinan = $('#jenis_perizinan').find(":selected").val();

        $.cookie("jenis_perizinan", jenis_perizinan, { expires : 3600 });
        location.reload();
    });
    $('.cancelsearch').click(function(){
        $.cookie("jenis_perizinan", "", { expires : 3600 });
        location.reload();
    })
    // $('.nav_sdm').addClass('active');
</script>
@endsection