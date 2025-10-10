@extends('layouts.app')
 
@section('content')
{{-- <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" /> --}}
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
    .modal-dialog-scrollable .modal-body {
    max-height: calc(100vh - 200px); /* biar tidak melebihi layar */
    overflow-y: auto; /* aktifkan scroll vertikal */
}

</style>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">Menu Stakeholder</h1>
    <div class="card shadow mb-4">
        <div class="card-body row" style="font-size: 0.85em;">
            @if(Auth::user()->hakakses =='Admin')
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
                    <label for="kategori" class="col-md-4 control-label" style="text-align: right"><strong>Kategori</strong></label>
                    <select name="kategori" id="kategori" class="col-sm-7 form-control">
                    @if($searchkategori!="")
                        <option value="">Pilih..</option>
                        <option value="Governance" @if($searchkategori=="Governance") selected @endif>Governance</option>
                        <option value="Non Governance" @if($searchkategori=="Non Governance") selected @endif>Non Governance</option>
                    @else
                        <option value="">Pilih..</option>
                        <option value="Governance">Governance</option>
                        <option value="Non Governance">Non Governance</option>
                    @endif                                   
                        
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                
                <div class="form-group row">
                    <label for="kebun" class="col-md-4 control-label" style="text-align: right"><strong>Kebun</strong></label>
                    <select name="kebun" id="kebun" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    @foreach($datakebun as $kebun)
                    <option value="{{$kebun->kebun}}" @if($searchkebun==$kebun->kebun) selected @endif>{{$kebun->kebun}}</option>
                    @endforeach
                        
                    </select>
                </div>
            </div>
            <!--
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="desa" class="col-md-4 control-label" style="text-align: right"><strong>Desa</strong></label>
                    <select name="desa" id="desa" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    @foreach($datadesa as $desa)
                    <option value="{{$desa->desa}}" @if($searchdesa==$desa->desa) selected @endif>{{$desa->desa}}</option>
                    @endforeach
                        
                    </select>
                </div>
            </div>
            -->
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
            <!-- <a href="{{url('/dash/form_stakeholder_add')}}">
                <button class="btn btn-primary btn-rounded add_user">
                <i class="fas fa-fw fa-plus"></i> Tambah Data
                </button>
            </a> -->
            <button type="button" class="btn btn-primary tambahdata">
              <i class="fas fa-fw fa-plus"></i> Tambah Data
            </button>
            <a href="
                {{url('/exportstakeholder')}}?region={{$searchregion}}&kategori={{$searchkategori}}&kebun={{$searchkebun}}
            ">
                <button class="btn btn-success btn-rounded add_user">
                <i class="fas fa-fw fa-download"></i> Export
                </button>
            </a>
            
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th style="text-align:center">Stakeholder<br>Instansi</th>
                            <th style="text-align:center">Identitas PIC</th>
                            <th style="text-align:center">Jabatan PIC</th>
                            <th style="text-align:center">Daerah</th>
                            <th style="text-align:center">Tanggal Input</th>
                            <th style='text-align:center;'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        @foreach($dataalluser as $key)
                        <tr>
                            <td>{{$i++}}</td>
                            <td ><a href="{{url('/dash/form_stakeholder_detail')}}/{{$key->id}}"><i class="fas fa-fw fa-search"></i></a> {{$key->nama_instansi}}</td>
                            <td >{{$key->nama_pic}} <br><span>{{$key->nomorkontak_pic}}</td>
                            <td >{{$key->jabatan_pic}}</td>
                            <td >{{$key->daerah_instansi}}</td>
                            <td style="text-align:center">{{$key->input_date}}</td>
                            <td style="text-align:center" width="100">
                                <btn class="btn btn-warning btn-sm editdata" id="{{$key->id}}"><i class="fas fa-fw fa-edit"></i></btn>
                                <a href="{{url('/dash/deletestakeholder')}}/{{$key->id}}">
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
    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
            <div class="modal-content shadow">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Tambah Data Stakeholder</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ url('/dash/storestakeholder') }}" enctype="multipart/form-data" method="post">
                @csrf
                <div class="modal-body">
                <div class="row">
                    <!-- Kolom Kiri -->
                    <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-info text-white">
                        <i class="fas fa-building"></i> Data Wilayah
                        </div>
                        <div class="card-body" style="font-size:0.9em;">
                        @if(Auth::user()->hakakses == 'Admin')
                        <div class="form-group row">
                            <label for="region" class="col-md-4 col-form-label">Region</label>
                            <div class="col-md-8">
                            <select name="region" id="regionclass" class="form-control" required>
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
                            </select>
                            </div>
                        </div>
                        @else
                        <div class="form-group row">
                            <label for="region" class="col-md-4 col-form-label">Region</label>
                            <div class="col-md-8">
                            <select name="region" id="region" class="form-control" required>
                                <option value="{{ Auth::user()->region }}">{{ Auth::user()->region }}</option>
                            </select>
                            </div>
                        </div>
                        @endif

                        <div class="form-group row">
                            <label for="kebun" class="col-md-4 col-form-label">Kebun</label>
                            <div class="col-md-8">
                                <select name="kebun" id="kebunclass" class="form-control" required>
                                    <option value="">Pilih Kebun/Unit</option>
                                </select>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kategori</label>
                            <div class="col-md-8 d-flex align-items-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori" id="governance" value="Governance" required>
                                    <label class="form-check-label" for="governance">Governance</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="kategori" id="non_governance" value="Non Governance" required>
                                    <label class="form-check-label" for="non_governance">Non Governance</label>
                                </div>
                            </div>
                        </div>


                        <div class="form-group row">
                            <label for="nama_instansi" class="col-md-4 col-form-label">Nama Instansi</label>
                            <div class="col-md-8">
                            <input type="text" class="form-control" id="nama_instansi" name="nama_instansi" placeholder="Nama Instansi/Stakeholder..." required>
                            </div>
                        </div>

                        
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Provinsi</label>
                            <div class="select2-container col-md-8">
                                <select name="provinsi" id="provinsi" class="form-control" data-width="100%"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kabupaten/Kota</label>
                            <div class="col-md-8">
                                <select name="kabupaten" id="kabupaten" class="form-control" data-width="100%"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label">Kecamatan</label>
                            <div class="col-md-8">
                                <select name="kecamatan" id="kecamatan" class="form-control" data-width="100%"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label" >Desa</label>
                            <div class="col-md-8">
                                <select name="desaw" id="desaw" class="form-control" data-width="100%"></select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="latlong" class="col-md-4 col-form-label">Lat, Long</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="latlong" name="latlong" placeholder="-6.200000,106.816666">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dokumenpendukung" class="col-md-4 col-form-label">Dokumen Pendukung</label>
                            <div class="col-md-8">
                            <input type="file" class="form-control" id="dokumenpendukung" name="dokumenpendukung" required>
                            </div>
                        </div>

                        </div>
                    </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="col-md-6">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-success text-white">
                        <i class="fas fa-user"></i> Identitas PIC
                        </div>
                        <div class="card-body" style="font-size:0.9em;">
                        <div class="form-group row">
                            <label for="nama_pic" class="col-md-4 col-form-label">Nama PIC</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="nama_pic" name="nama_pic" placeholder="Nama PIC..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jabatan_pic" class="col-md-4 col-form-label">Jabatan PIC</label>
                            <div class="col-md-8">
                            <input type="text" class="form-control" id="jabatan_pic" name="jabatan_pic" placeholder="Jabatan PIC..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nomorkontak_pic" class="col-md-4 col-form-label">Nomor Kontak</label>
                            <div class="col-md-8">
                            <input type="number" class="form-control" id="nomorkontak_pic" name="nomorkontak_pic" placeholder="Nomor Kontak..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">Email / Sosial Media</label>
                            <div class="col-md-8">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email / Media Sosial..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nama_pic" class="col-md-4 col-form-label">Nama PIC 2</label>
                            <div class="col-md-8">
                                <input type="text" class="form-control" id="nama_pic2" name="nama_pic2" placeholder="Nama PIC..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="jabatan_pic" class="col-md-4 col-form-label">Jabatan PIC 2</label>
                            <div class="col-md-8">
                            <input type="text" class="form-control" id="jabatan_pic2" name="jabatan_pic2" placeholder="Jabatan PIC..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nomorkontak_pic" class="col-md-4 col-form-label">Nomor Kontak</label>
                            <div class="col-md-8">
                            <input type="number" class="form-control" id="nomorkontak_pic2" name="nomorkontak_pic2" placeholder="Nomor Kontak..." required>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label">Email / Sosial Media</label>
                            <div class="col-md-8">
                            <input type="text" class="form-control" id="email2" name="email2" placeholder="Email / Media Sosial..." required>
                            </div>
                        </div>
                        <br><br>
                        </div>
                    </div>
                    </div>

                    <!-- Full Width -->
                    <div class="col-md-12">
                    <div class="card mb-3 shadow-sm">
                        <div class="card-header bg-secondary text-white">
                        <i class="fas fa-chart-bar"></i> Data Analisis Hubungan Stakeholder
                        </div>
                        <div class="card-body" style="font-size:0.9em;">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="skala_kepentingan" class="col-md-4 col-form-label">Skala Kepentingan</label>
                                            <div class="col-md-8">
                                            <select name="skala_kepentingan" id="skala_kepentingan" class="form-control">
                                                <option value="">Pilih..</option>
                                                @for ($i = 1; $i <= 15; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="skala_pengaruh" class="col-md-4 col-form-label">Skala Pengaruh</label>
                                            <div class="col-md-8">
                                            <select name="skala_pengaruh" id="skala_pengaruh" class="form-control" required>
                                                <option value="">Pilih..</option>
                                                @for ($i = 1; $i <= 15; $i++)
                                                    <option value="{{ $i }}">{{ $i }}</option>
                                                @endfor
                                            </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="control-label"><b>Hasil Kuadran</b></label>
                                            <p id="hasil_keterangan" class="fw-bold text-primary mb-0"></p>
                                            <input type="hidden" name="keterangan_kuadran" id="keterangan_kuadran">
                                            <br>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="row">
                                    <!-- Kolom Kiri -->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="curent_condition" class="col-md-4 control-label">Current Condition</label>
                                            <div class="col-md-8">
                                                <select name="curent_condition" id="curent_condition" class="form-control" required>
                                                    <option value="Sangat Baik">Sangat Baik</option>
                                                    <option value="Baik">Baik</option>
                                                    <option value="Cukup Baik">Cukup Baik</option>
                                                    <option value="Kurang Baik">Kurang Baik</option>
                                                    <option value="Tidak Baik">Tidak Baik</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="ekspektasi_stakeholder" class="col-md-4 control-label">Ekspektasi Stakeholder</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" id="ekspektasi_stakeholder" name="ekspektasi_stakeholder" rows="7"></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Kolom Kanan -->
                                    <div class="col-md-6">
                                        <div class="form-group row">
                                            <label for="ekspektasi_ptpn" class="col-md-4 control-label">Ekspektasi PTPN</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" id="ekspektasi_ptpn" name="ekspektasi_ptpn" rows="4"></textarea>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="saran_bagi_manajemen" class="col-md-4 control-label">Saran Bagi Manajemen</label>
                                            <div class="col-md-8">
                                                <textarea class="form-control" id="saran_bagi_manajemen" name="saran_bagi_manajemen" rows="4"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                    </div>
                    </div>

                </div> <!-- row -->
                </div> <!-- modal-body -->

                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                <button type="submit" name="submit" class="btn btn-primary"><i class="fas fa-fw fa-plus"></i> Tambah</button>
                </div>
            </form>

            </div>
        </div>
    </div>

    <!-- Modal Edit Stakeholder -->
<div class="modal fade" id="modalEditStakeholder" tabindex="-1" role="dialog" aria-labelledby="modalEditStakeholderLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable" role="document">
        <div class="modal-content shadow">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditStakeholderLabel">Edit Data Stakeholder</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="formEditStakeholder" action="{{ url('/dash/updatestakeholder') }}" enctype="multipart/form-data" method="post">
                @csrf
                <input type="hidden" name="id" id="edit_id">

                <div class="modal-body">
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-info text-white">
                                    <i class="fas fa-building"></i> Data Wilayah
                                </div>
                                <div class="card-body" style="font-size:0.9em;">
                                    
                                    {{-- Region --}}
                                    @if(Auth::user()->hakakses == 'Admin')
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">Region</label>
                                            <div class="col-md-8">
                                                <select name="edit_region" id="edit_region" class="form-control" required>
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
                                                </select>
                                            </div>
                                        </div>
                                    @else
                                        <div class="form-group row">
                                            <label class="col-md-4 col-form-label">Region</label>
                                            <div class="col-md-8">
                                                <select name="edit_region" id="edit_region" class="form-control" required>
                                                    <option value="{{ Auth::user()->region }}">{{ Auth::user()->region }}</option>
                                                </select>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Kebun --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Kebun</label>
                                        <div class="col-md-8">
                                            <select name="edit_kebun" id="edit_kebun" class="form-control" required>
                                                <option value="">Pilih Kebun/Unit</option>
                                            </select>
                                        </div>
                                    </div>

                                    {{-- Kategori --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Kategori</label>
                                        <div class="col-md-8 d-flex align-items-center">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="edit_kategori" id="edit_governance" value="Governance">
                                                <label class="form-check-label" for="edit_governance">Governance</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="edit_kategori" id="edit_non_governance" value="Non Governance">
                                                <label class="form-check-label" for="edit_non_governance">Non Governance</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Nama Instansi --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Nama Instansi</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_nama_instansi" id="edit_nama_instansi" required>
                                        </div>
                                    </div>

                                    {{-- Provinsi - Desa --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Provinsi</label>
                                        <div class="col-md-8">
                                            <select name="edit_provinsi" id="edit_provinsi" class="form-control" data-width="100%"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Kabupaten/Kota</label>
                                        <div class="col-md-8">
                                            <select name="edit_kabupaten" id="edit_kabupaten" class="form-control" data-width="100%"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Kecamatan</label>
                                        <div class="col-md-8">
                                            <select name="edit_kecamatan" id="edit_kecamatan" class="form-control" data-width="100%"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Desa</label>
                                        <div class="col-md-8">
                                            <select name="edit_desaw" id="edit_desaw" class="form-control" data-width="100%"></select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Lat, Long</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="latlong" id="edit_latlong" placeholder="-6.200000,106.816666">
                                        </div>
                                    </div>

                                    {{-- Dokumen --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Dokumen Pendukung</label>
                                        <div class="col-md-8">
                                            <input type="file" class="form-control" name="edit_dokumenpendukung" id="edit_dokumenpendukung">
                                            <small class="text-muted">Kosongkan jika tidak diganti</small>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-success text-white">
                                    <i class="fas fa-user"></i> Identitas PIC
                                </div>
                                <div class="card-body" style="font-size:0.9em;">

                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Nama PIC</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_nama_pic" id="edit_nama_pic" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Jabatan PIC</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_jabatan_pic" id="edit_jabatan_pic" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Nomor Kontak</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" name="edit_nomorkontak_pic" id="edit_nomorkontak_pic" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Email / Sosial Media</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_email" id="edit_email" required>
                                        </div>
                                    </div>

                                    {{-- PIC 2 --}}
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Nama PIC 2</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_nama_pic2" id="edit_nama_pic2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Jabatan PIC 2</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_jabatan_pic2" id="edit_jabatan_pic2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Nomor Kontak 2</label>
                                        <div class="col-md-8">
                                            <input type="number" class="form-control" name="edit_nomorkontak_pic2" id="edit_nomorkontak_pic2">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-md-4 col-form-label">Email / Sosial Media 2</label>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" name="edit_email2" id="edit_email2">
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <br>
                                </div>
                            </div>
                        </div>

                        <!-- Full Width -->
                        <div class="col-md-12">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-secondary text-white">
                                    <i class="fas fa-chart-bar"></i> Data Analisis Hubungan Stakeholder
                                </div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Skala Kepentingan</label>
                                                <div class="col-md-8">
                                                <select name="edit_skala_kepentingan" id="edit_skala_kepentingan" class="form-control">
                                                    <option value="">Pilih..</option>
                                                    @for ($i = 1; $i <= 15; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label">Skala Pengaruh</label>
                                                <div class="col-md-8">
                                                <select name="edit_skala_pengaruh" id="edit_skala_pengaruh" class="form-control">
                                                    <option value="">Pilih..</option>
                                                    @for ($i = 1; $i <= 15; $i++)
                                                        <option value="{{ $i }}">{{ $i }}</option>
                                                    @endfor
                                                </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label><b>Hasil Kuadran</b></label>
                                        <p id="edit_hasil_keterangan" class="fw-bold text-primary mb-0"></p>
                                        <input type="hidden" name="edit_keterangan_kuadran" id="edit_keterangan_kuadran">
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">Current Condition</label>
                                                <div class="col-md-8">
                                                    <select name="edit_curent_condition" id="edit_curent_condition" class="form-control">
                                                        <option value="Sangat Baik">Sangat Baik</option>
                                                        <option value="Baik">Baik</option>
                                                        <option value="Cukup Baik">Cukup Baik</option>
                                                        <option value="Kurang Baik">Kurang Baik</option>
                                                        <option value="Tidak Baik">Tidak Baik</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">Ekspektasi Stakeholder</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="edit_ekspektasi_stakeholder" id="edit_ekspektasi_stakeholder" rows="7"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">Ekspektasi PTPN</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="edit_ekspektasi_ptpn" id="edit_ekspektasi_ptpn" rows="4"></textarea>
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <label class="col-md-4 control-label">Saran Bagi Manajemen</label>
                                                <div class="col-md-8">
                                                    <textarea class="form-control" name="edit_saran_bagi_manajemen" id="edit_saran_bagi_manajemen" rows="4"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div> <!-- row -->
                </div> <!-- modal-body -->

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-warning"><i class="fas fa-save"></i> Update</button>
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
        // console.log(modaladddata);
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
            url: "{{url('/dash/get_data_stakeholder')}}/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                
                $('#idstakeholder').val(id);
                $('#regionclass').val(response.region);
                $('#kebunclass').val(response.kebun);
                $('#desaclass').val(response.desa);
                $('#curent_condition').val(response.curent_condition);
                $('#nama_instansi').val(response.nama_instansi);
                $('#daerah_instansi').val(response.daerah_instansi);
                $('#nama_pic').val(response.nama_pic);
                $('#jabatan_pic').val(response.jabatan_pic);
                $('#nomorkontak_pic').val(response.nomorkontak_pic);
                $('#derajat_hubungan').val(response.derajat_hubungan);
                if(response.derajat_hubungan=="Tipe A"){
                    $('.ket_derajat_hubungan').html('Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan');
                }
                else if(response.derajat_hubungan=="Tipe B"){
                    $('.ket_derajat_hubungan').html('Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.');
                }
                else if(response.derajat_hubungan=="Tipe C"){
                    $('.ket_derajat_hubungan').html('Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.');
                }
                else{
                    $('.ket_derajat_hubungan').html('');
                }
                if(response.kategori=="Governance"){
                    $('#governance').prop('checked', true);
                }
                if(response.kategori=="Non Governance"){
                    $('#non_governance').prop('checked', true);
                }
                // $('#tipe_stakeholder').val(response.tipe_stakeholder);
                // if(response.tipe_stakeholder=="Moderat"){
                //     $('.ket_tipe').html('Stakeholder atau instansi memiliki tingkat kepentingan menengah terhadap perusahaan');
                // }
                // else if(response.tipe_stakeholder=="Prioritas"){
                //     $('.ket_tipe').html('Pihak stakeholder atau instansi memiliki tingkat keutamaan yang tinggi dan wajib dimiliki oleh perusahaan');
                // }
                $('#skala_kekuatan').val(response.skala_kekuatan);
                $('#skala_kepentingan').val(response.skala_kepentingan);
                // else{
                //     $('.ket_tipe').html('');
                // }
                $('#email').val(response.email);
                $('#ekspektasi_ptpn').val(response.ekspektasi_ptpn);
                $('#ekspektasi_stakeholder').val(response.ekspektasi_stakeholder);
                // latlong (jika tersedia di response)
                $('#edit_latlong').val(response.latlong || '');
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    // $("#region, #kategori, #desa, #kebun").select2({});
    $('.cari').click(function(){
        var region = $('#region').find(":selected").val();
        var kategori = $('#kategori').find(":selected").val();
        var kebun = $('#kebun').find(":selected").val();
        var desa = $('#desa').find(":selected").val();

        $.cookie("region", region, { expires : 3600 });
        $.cookie("kategori", kategori, { expires : 3600 });
        $.cookie("kebun", kebun, { expires : 3600 });
        $.cookie("desa", desa, { expires : 3600 });
        location.reload();
    });
    $('.cancelsearch').click(function(){
        $.cookie("region", "", { expires : 3600 });
        $.cookie("kategori", "", { expires : 3600 });
        $.cookie("desa", "", { expires : 3600 });
        $.cookie("kebun", "", { expires : 3600 });
        location.reload();
    })
    $(document).ready(function() {
       
        $(document).on('change', '.modaladddata #derajat_hubungan', function() {
            var tipe = $(this).val();
            if(tipe=="Tipe A"){
                $('.ket_derajat_hubunganadd').html('Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan');
            }
            else if(tipe=="Tipe B"){
                $('.ket_derajat_hubunganadd').html('Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.');
            }
            else if(tipe=="Tipe C"){
                $('.ket_derajat_hubunganadd').html('Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.');
            }
            else{
                $('.ket_derajat_hubunganadd').html('');
            }
            
        });
        $(document).on('change', '.modaleditdata #derajat_hubungan', function() {
            var tipe = $(this).val();
            if(tipe=="Tipe A"){
                $('.ket_derajat_hubungan').html('Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan');
            }
            else if(tipe=="Tipe B"){
                $('.ket_derajat_hubungan').html('Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.');
            }
            else if(tipe=="Tipe C"){
                $('.ket_derajat_hubungan').html('Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.');
            }
            else{
                $('.ket_derajat_hubungan').html('');
            }
        });
    });
    // $('.nav_sdm').addClass('active');
</script>
{{-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> --}}
<script>
    $(function () {
        // Set modal sebagai parent dropdown
        let modalParent = $('#exampleModal'); // ganti sesuai ID modal Anda

        // Provinsi
        $('#provinsi').select2({
            placeholder: "Pilih Provinsi",
            allowClear: true,
            dropdownParent: modalParent,
            ajax: {
                url: '/get-wilayah',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        level: 'provinsi',
                        q: params.term
                    };
                },
                processResults: function (data) {
                    console.log("Provinsi:", data); // debug
                    return {
                        results: data.map(v => ({
                            id: v.kode,
                            text: v.nama
                        }))
                    };
                }
            }
        });

        // Kabupaten
        $('#kabupaten').select2({
            placeholder: "Pilih Kabupaten/Kota",
            allowClear: true,
            dropdownParent: modalParent,
            ajax: {
                url: '/get-wilayah',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        level: 'kabupaten',
                        parent: $('#provinsi').val(),
                        q: params.term
                    };
                },
                processResults: function (data) {
                    console.log("Kabupaten:", data); // debug
                    return {
                        results: data.map(v => ({
                            id: v.kode,
                            text: v.nama
                        }))
                    };
                }
            }
        });

        // Kecamatan
        $('#kecamatan').select2({
            placeholder: "Pilih Kecamatan",
            allowClear: true,
            dropdownParent: modalParent,
            ajax: {
                url: '/get-wilayah',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        level: 'kecamatan',
                        parent: $('#kabupaten').val(),
                        q: params.term
                    };
                },
                processResults: function (data) {
                    console.log("Kecamatan:", data); // debug
                    return {
                        results: data.map(v => ({
                            id: v.kode,
                            text: v.nama
                        }))
                    };
                }
            }
        });

        // Desa
        $('#desaw').select2({
            placeholder: "Pilih Desa",
            allowClear: true,
            dropdownParent: modalParent,
            ajax: {
                url: '/get-wilayah',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        level: 'desa',
                        parent: $('#kecamatan').val(),
                        q: params.term
                    };
                },
                processResults: function (data) {
                    console.log("Desa:", data); // debug
                    return {
                        results: data.map(v => ({
                            id: v.kode,
                            text: v.nama
                        }))
                    };
                }
            }
        });

        // Reset saat parent berubah
        $('#provinsi').on('change', function () {
            $('#kabupaten').val(null).trigger('change');
            $('#kecamatan').val(null).trigger('change');
            $('#desaw').val(null).trigger('change');
        });

        $('#kabupaten').on('change', function () {
            $('#kecamatan').val(null).trigger('change');
            $('#desaw').val(null).trigger('change');
        });

        $('#kecamatan').on('change', function () {
            $('#desaw').val(null).trigger('change');
        });
    });

</script>
<script>
    function tentukanKuadran(kepentingan, pengaruh) {
    let tingkatKepentingan = kepentingan >= 9 ? "Tinggi" : "Rendah";
    let tingkatPengaruh = pengaruh >= 9 ? "Tinggi" : "Rendah";

    if (tingkatPengaruh === "Tinggi" && tingkatKepentingan === "Tinggi") {
        return {
            kuadran: "Kuadran I",
            matrix: "High Influence High Interest",
            keterangan: "Stakeholder yang memiliki pengaruh tinggi dan memiliki kepentingan tinggi terhadap arah bisnis perusahaan."
        };
    } else if (tingkatPengaruh === "Tinggi" && tingkatKepentingan === "Rendah") {
        return {
            kuadran: "Kuadran II",
            matrix: "High Influence Low Interest",
            keterangan: "Stakeholder yang memiliki pengaruh tinggi, namun memiliki kepentingan rendah terhadap arah bisnis perusahaan."
        };
    } else if (tingkatPengaruh === "Rendah" && tingkatKepentingan === "Tinggi") {
        return {
            kuadran: "Kuadran III",
            matrix: "Low Influence High Interest",
            keterangan: "Stakeholder yang memiliki kepentingan dengan perusahaan, namun tidak memiliki pengaruh langsung terhadap arah bisnis perusahaan."
        };
    } else {
        return {
            kuadran: "Kuadran IV",
            matrix: "Low Influence Low Interest",
            keterangan: "Stakeholder yang tidak memiliki pengaruh dan tidak memiliki kepentingan langsung terhadap arah bisnis perusahaan."
        };
    }
}

// Untuk modal tambah
document.getElementById('skala_kepentingan').addEventListener('change', tampilkanHasilTambah);
document.getElementById('skala_pengaruh').addEventListener('change', tampilkanHasilTambah);

function tampilkanHasilTambah() {
    let kepentingan = parseInt(document.getElementById('skala_kepentingan').value);
    let pengaruh = parseInt(document.getElementById('skala_pengaruh').value);

    if (!isNaN(kepentingan) && !isNaN(pengaruh)) {
        let hasil = tentukanKuadran(kepentingan, pengaruh);
        let teks = `Stakeholder ini masuk dalam ${hasil.kuadran} atau ${hasil.matrix}. ${hasil.keterangan}`;

        document.getElementById('hasil_keterangan').innerHTML =
            `Stakeholder ini masuk dalam <b>${hasil.kuadran}</b> atau <b>${hasil.matrix}</b>.<br>${hasil.keterangan}`;

        document.getElementById('keterangan_kuadran').value = teks;
    } else {
        document.getElementById('hasil_keterangan').innerHTML = "";
        document.getElementById('keterangan_kuadran').value = "";
    }
}

// Untuk modal edit
document.getElementById('edit_skala_kepentingan').addEventListener('change', tampilkanHasilEdit);
document.getElementById('edit_skala_pengaruh').addEventListener('change', tampilkanHasilEdit);

function tampilkanHasilEdit() {
    let kepentingan = parseInt(document.getElementById('edit_skala_kepentingan').value);
    let pengaruh = parseInt(document.getElementById('edit_skala_pengaruh').value);

    if (!isNaN(kepentingan) && !isNaN(pengaruh)) {
        let hasil = tentukanKuadran(kepentingan, pengaruh);
        let teks = `Stakeholder ini masuk dalam ${hasil.kuadran} atau ${hasil.matrix}. ${hasil.keterangan}`;

        document.getElementById('edit_hasil_keterangan').innerHTML =
            `Stakeholder ini masuk dalam <b>${hasil.kuadran}</b> atau <b>${hasil.matrix}</b>.<br>${hasil.keterangan}`;

        document.getElementById('edit_keterangan_kuadran').value = teks;
    } else {
        document.getElementById('edit_hasil_keterangan').innerHTML = "";
        document.getElementById('edit_keterangan_kuadran').value = "";
    }
}

</script>
<script>
$(document).ready(function () {
    // event change untuk region di modal tambah
    $('#regionclass').on('change', function() {
        let region = $(this).val();
        $('#kebunclass').empty().append('<option value="">Loading...</option>');

        if(region) {
            $.ajax({
                url: '/get-kebun-by-region',
                type: 'GET',
                data: { region: region },
                success: function(data) {
                    $('#kebunclass').empty().append('<option value="">Pilih Kebun/Unit</option>');
                    $.each(data, function(key, kebun) {
                        $('#kebunclass').append('<option value="'+ kebun.unit +'">'+ kebun.unit +'</option>');
                    });
                }
            });
        } else {
            $('#kebunclass').empty().append('<option value="">Pilih Kebun/Unit</option>');
        }
    });

    // event change untuk region di modal edit
    $('#edit_region').on('change', function() {
        let region = $(this).val();
        $('#edit_kebun').empty().append('<option value="">Loading...</option>');

        if(region) {
            $.ajax({
                url: '/get-kebun-by-region',
                type: 'GET',
                data: { region: region },
                success: function(data) {
                    $('#edit_kebun').empty().append('<option value="">Pilih Kebun/Unit</option>');
                    $.each(data, function(key, kebun) {
                        $('#edit_kebun').append('<option value="'+ kebun.unit +'">'+ kebun.unit +'</option>');
                    });
                }
            });
        } else {
            $('#edit_kebun').empty().append('<option value="">Pilih Kebun/Unit</option>');
        }
    });
});

</script>
<script>
    function initWilayahSelect2(prefix, modalParent) {
    // Provinsi
    $('#' + prefix + '_provinsi').select2({
        placeholder: "Pilih Provinsi",
        allowClear: true,
        dropdownParent: modalParent,
        ajax: {
            url: '/get-wilayah',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { level: 'provinsi', q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(v => ({ id: v.kode, text: v.nama }))
                };
            }
        }
    });

    // Kabupaten
    $('#' + prefix + '_kabupaten').select2({
        placeholder: "Pilih Kabupaten/Kota",
        allowClear: true,
        dropdownParent: modalParent,
        ajax: {
            url: '/get-wilayah',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { level: 'kabupaten', parent: $('#' + prefix + '_provinsi').val(), q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(v => ({ id: v.kode, text: v.nama }))
                };
            }
        }
    });

    // Kecamatan
    $('#' + prefix + '_kecamatan').select2({
        placeholder: "Pilih Kecamatan",
        allowClear: true,
        dropdownParent: modalParent,
        ajax: {
            url: '/get-wilayah',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { level: 'kecamatan', parent: $('#' + prefix + '_kabupaten').val(), q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(v => ({ id: v.kode, text: v.nama }))
                };
            }
        }
    });

    // Desa
    $('#' + prefix + '_desaw').select2({
        placeholder: "Pilih Desa",
        allowClear: true,
        dropdownParent: modalParent,
        ajax: {
            url: '/get-wilayah',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { level: 'desa', parent: $('#' + prefix + '_kecamatan').val(), q: params.term };
            },
            processResults: function (data) {
                return {
                    results: data.map(v => ({ id: v.kode, text: v.nama }))
                };
            }
        }
    });

    // Reset cascading
    $('#' + prefix + '_provinsi').on('change', function () {
        $('#' + prefix + '_kabupaten').val(null).trigger('change');
        $('#' + prefix + '_kecamatan').val(null).trigger('change');
        $('#' + prefix + '_desaw').val(null).trigger('change');
    });
    $('#' + prefix + '_kabupaten').on('change', function () {
        $('#' + prefix + '_kecamatan').val(null).trigger('change');
        $('#' + prefix + '_desaw').val(null).trigger('change');
    });
    $('#' + prefix + '_kecamatan').on('change', function () {
        $('#' + prefix + '_desaw').val(null).trigger('change');
    });
}

    function setSelect2Value(selector, id, text) {
    if (id && text) {
        let newOption = new Option(text, id, true, true);
        $(selector).append(newOption).trigger('change');
    } else {
        $(selector).val(null).trigger('change');
    }
}
$(function () {
    initWilayahSelect2('add', $('#modalAddStakeholder'));   // untuk form tambah
    initWilayahSelect2('edit', $('#modalEditStakeholder')); // untuk form edit
});

$(document).on("click", ".editdata", function () {
    let id = $(this).attr("id");

    $.get("/dash/get_data_stakeholder/" + id, function (res) {
        // isi field hidden id
        $("#edit_id").val(res.id);

        // Region (jika admin pakai select2, kalau user biasa readonly)
        $("#edit_region").val(res.region).trigger("change");

        // Kebun
        
        if (res.kebun) {
            let option = new Option(res.kebun, res.kebun, true, true);
            $("#edit_kebun").append(option).trigger("change");
        }
        // handle dropdown kebun
        if (res.region) {
            // kosongkan dulu
            $("#edit_kebun").empty().append('<option value="">Loading...</option>');

            // ambil list kebun dari region
            $.ajax({
                url: "/get-kebun-by-region",
                type: "GET",
                data: { region: res.region },
                success: function (data) {
                    $("#edit_kebun").empty().append('<option value="">Pilih Kebun/Unit</option>');
                    $.each(data, function (key, kebun) {
                        let selected = (kebun.unit === res.kebun) ? "selected" : "";
                        $("#edit_kebun").append('<option value="' + kebun.unit + '" ' + selected + '>' + kebun.unit + '</option>');
                    });

                    // trigger select2 kalau pakai
                    $("#edit_kebun").trigger("change");
                }
            });
        }

        // Kategori (radio button)
        $("input[name='edit_kategori'][value='" + res.kategori + "']").prop("checked", true);

        // Nama Instansi
        $("#edit_nama_instansi").val(res.nama_instansi);

         // select2 wilayah
        setSelect2Value("#edit_provinsi", res.prov_id, res.prov_nama);
        setSelect2Value("#edit_kabupaten", res.kab_id, res.kab_nama);
        setSelect2Value("#edit_kecamatan", res.kec_id, res.kec_nama);
        setSelect2Value("#edit_desaw", res.desa_id, res.desa_nama);

        // PIC 1
        $("#edit_nama_pic").val(res.nama_pic);
        $("#edit_jabatan_pic").val(res.jabatan_pic);
        $("#edit_nomorkontak_pic").val(res.nomorkontak_pic);
        $("#edit_email").val(res.email);

        // PIC 2
        $("#edit_nama_pic2").val(res.nama_pic2);
        $("#edit_jabatan_pic2").val(res.jabatan_pic2);
        $("#edit_nomorkontak_pic2").val(res.nomorkontak_pic2);
        $("#edit_email2").val(res.email2);

        // Analisis
        $("#edit_skala_kepentingan").val(res.skala_kepentingan).trigger("change");
        $("#edit_skala_pengaruh").val(res.skala_pengaruh).trigger("change");
        $("#edit_curent_condition").val(res.curent_condition).trigger("change");
        $("#edit_ekspektasi_stakeholder").val(res.ekspektasi_stakeholder);
        $("#edit_ekspektasi_ptpn").val(res.ekspektasi_ptpn);
        $("#edit_saran_bagi_manajemen").val(res.saranbagimanajemen);

        // Kuadran (hidden + text)
        $("#edit_keterangan_kuadran").val(res.hasil_skala);
        $("#edit_hasil_keterangan").text(res.hasil_skala);

        // Tampilkan modal
        $("#modalEditStakeholder").modal("show");
    });
});

</script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script> --}}

@endsection