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
<h1 class="h3 mb-2 text-gray-800">Menu Perizinan</h1>
    <div class="card shadow mb-4">
        <div class="card-body row" style="font-size: 0.85em;">
            @if(Auth::check() && Auth::user()->hakakses =='Admin')
            <div class="col-md-3">
                
                <div class="form-group row">
                    <label for="region" class="col-md-4 control-label" style="text-align: right"><strong>Region</strong></label>
                    <select name="region" id="region" class="col-sm-7 form-control">
                    @if($searchregion!="")
                    
                        <option value="">Pilih..</option>
                        <option value="PTPN I HO" @if($searchregion=="PTPN I HO") selected @endif>PTPN I HO</option>
                        <option value="PTPN I Regional 1" @if($searchregion=="PTPN I Regional 1") selected @endif>PTPN I Regional 1</option>
                        <option value="PTPN I Regional 2" @if($searchregion=="PTPN I Regional 2") selected @endif>PTPN I Regional 2</option>
                        <option value="PTPN I Regional 3" @if($searchregion=="PTPN I Regional 3") selected @endif>PTPN I Regional 3</option>
                        <option value="PTPN I Regional 4" @if($searchregion=="PTPN I Regional 4") selected @endif>PTPN I Regional 4</option>
                        <option value="PTPN I Regional 5" @if($searchregion=="PTPN I Regional 5") selected @endif>PTPN I Regional 5</option>
                        <option value="PTPN I Regional 6" @if($searchregion=="PTPN I Regional 6") selected @endif>PTPN I Regional 6</option>
                        <option value="PTPN I Regional 7" @if($searchregion=="PTPN I Regional 7") selected @endif>PTPN I Regional 7</option>
                        <option value="PTPN I Regional 8" @if($searchregion=="PTPN I Regional 8") selected @endif>PTPN I Regional 8</option>
                    @else
                        <option value="">Pilih..</option>
                        <option value="PTPN I HO">PTPN I HO</option>
                        <option value="PTPN I Regional 1">PTPN I Regional 1</option>
                        <option value="PTPN I Regional 2">PTPN I Regional 2</option>
                        <option value="PTPN I Regional 3">PTPN I Regional 3</option>
                        <option value="PTPN I Regional 4">PTPN I Regional 4</option>
                        <option value="PTPN I Regional 5">PTPN I Regional 5</option>
                        <option value="PTPN I Regional 6">PTPN I Regional 6</option>
                        <option value="PTPN I Regional 7">PTPN I Regional 7</option>
                        <option value="PTPN I Regional 8">PTPN I Regional 8</option>
                    @endif
                        
                    </select>
                </div>
            </div>
            @endif
            <div class="col-md-3">
                
                <div class="form-group row">
                    <label for="kebun" class="col-md-4 control-label" style="text-align: right"><strong>Kebun</strong></label>
                    <select name="kebun" id="kebun" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    @foreach($datakebunall as $kebun)
                    <option value="{{$kebun->nama_kebun}}" @if($searchkebun==$kebun->nama_kebun) selected @endif>{{$kebun->nama_kebun}}</option>
                    @endforeach
                        
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="jenis_perizinan" class="col-md-4 control-label" style="text-align: right"><strong>Jenis Perizinan</strong></label>
                    <select name="jenis_perizinan" id="jenis_perizinan" class="col-sm-7 form-control">
                    @if($searchjenisperizinan!="")
                    
                        <option value="">Pilih..</option>
                        <option value="Perizinan" @if($searchjenisperizinan=="Perizinan") selected @endif>Perizinan</option>
                        <option value="Perizinan Lingkungan" @if($searchjenisperizinan=="Perizinan Lingkungan") selected @endif>Perizinan Lingkungan</option>
                        <option value="Perizinan Lainnya" @if($searchjenisperizinan=="Perizinan Lainnya") selected @endif>Perizinan Lainnya</option>
                        <option value="HAKI" @if($searchjenisperizinan=="HAKI") selected @endif>HAKI</option>
                        <option value="SLO" @if($searchjenisperizinan=="SLO") selected @endif>SLO</option>
                        <option value="BPOM" @if($searchjenisperizinan=="BPOM") selected @endif>BPOM</option>
                    @else
                        <option value="">Pilih..</option>
                        <option value="Perizinan">Perizinan</option>
                        <option value="Perizinan Lingkungan">Perizinan Lingkungan</option>
                        <option value="Perizinan Lainnya">Perizinan Lainnya</option>
                        <option value="HAKI">HAKI</option>
                        <option value="SLO">SLO</option>
                        <option value="BPOM">BPOM</option>
                    @endif
                        
                    </select>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <!-- <a href="{{url('/')}}/dash/stakeholder"> -->
            <button class="btn btn-outline-warning float-right btn-sm cancelsearch" style="margin-right: 10px;"><i class="fas fa-fw fa-stop"></i> Batalkan </button>
            <!-- </a> -->
            &nbsp;
            <button type="submit" name="submit" class="btn btn-outline-success float-right btn-sm cari" style="margin-right: 10px;"><i class="fas fa-fw fa-filter"></i> Filter </button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        
        <div class="card-body">       
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">Kebun/Pabrik</th>
                            <?php
                                foreach ($m_perizinan as $keyizin){
                                    echo "<th style='text-align:center'>".$keyizin->nama."</th>";
                                }
                            ?>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        @foreach($datakebunall as $key)
                        <tr>
                            
                            <td ><a href="{{url('/dokumen/form_perizinan_detail')}}/{{$key->kode_plant}}"><i class="fas fa-fw fa-search"></i></a> {{$key->nama_kebun}}</td>
                            <?php
                                
                                foreach ($m_perizinan as $keyizin){
                                    $tanggalakhir = "-";
                                    foreach ($dataalluser as $keyallizin){
                                        if($keyizin->id == $keyallizin->id_perizinan && $key->nama_kebun == $keyallizin->kebun){
                                            $tanggalakhir = $keyallizin->tanggal_end;
                                        }
                                    }
                            ?>
                                <td style="text-align:center">{{$tanggalakhir}}</td>
                            <?php } ?>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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
            url: "{{url('/dokumen/get_data_perjanjiankerjasama')}}/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                
                $('#idperjanjiankerjasama').val(id);
                $('#regionclass').val(response.region);
                $('#kebunclass').val(response.kebun);
                $('#desaclass').val(response.desa);
                $('#nama_instansi').val(response.nama_instansi);
                $('#nomor_mou_pks').val(response.nomor_mou_pks);
                $('#tanggal_mou_pks').val(response.tanggal_mou_pks);
                if(response.kategori=="Governance"){
                    $('#governance').prop('checked', true);
                }
                if(response.kategori=="Non Governance"){
                    $('#non_governance').prop('checked', true);
                }
                $('#masa_berlaku').val(response.masa_berlaku);
                $('#satuan_kerja').val(response.satuan_kerja);
                $('#perihal').val(response.perihal);          
                $('#ekspektasi_tujuan').val(response.ekspektasi_tujuan);
                $('#pic_lembaga').val(response.pic_lembaga);
                $('#pejabat_yang_menandatangani').val(response.pejabat_yang_menandatangani);
                $('#pejabat_yang_menggantikan').val(response.pejabat_yang_menggantikan);
                $('#dokumen').val(response.dokumen);
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    $("#region, #kategori, #desa, #kebun, #jenis_perizinan").select2({});
    $('.cari').click(function(){
        var region = $('#region').find(":selected").val();
        var kebun = $('#kebun').find(":selected").val();
        var jenis_perizinan = $('#jenis_perizinan').find(":selected").val();
        console.log(jenis_perizinan);

        $.cookie("region", region, { expires : 3600 });
        $.cookie("kebun", kebun, { expires : 3600 });
        $.cookie("jenis_perizinan", jenis_perizinan, { expires : 3600 });
        location.reload();
    });
    $('.cancelsearch').click(function(){
        $.cookie("region", "", { expires : 3600 });
        $.cookie("kebun", "", { expires : 3600 });
        $.cookie("jenis_perizinan",  "", { expires : 3600 });
        location.reload();
    })
    // $('.nav_sdm').addClass('active');
</script>
@endsection