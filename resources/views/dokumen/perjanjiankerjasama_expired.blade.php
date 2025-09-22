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
<h1 class="h3 mb-2 text-gray-800">Menu Perjanjian Kerjasama Yang Akan Berakhir</h1>
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
            <a href="{{url('/exportstakeholder')}}">
                <button class="btn btn-success btn-rounded add_user">
                <i class="fas fa-fw fa-download"></i> Export
                </button>
            </a>
            
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="text-align:center">Nama Instansi<br>Lembaga</th>
                            <th style="text-align:center">Nomor Perjanjian<br>Kerjasama</th>
                            <th style="text-align:center">Tanggal Perjanjian<br>Kerjasama</th>
                            <th style="text-align:center">Perihal</th>
                            <th style="text-align:center">Masa Berlaku</th>
                            <th style="text-align:center">PIC Lembaga</th>
                            <th style="text-align:center">Dokumen</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        @foreach($dataalluser as $key)
                        <tr>
                            <td ><a href="{{url('/dokumen/form_perjanjiankerjasama_detail')}}/{{$key->id}}"><i class="fas fa-fw fa-search"></i></a> {{$key->nama_instansi}}</td>
                            <td style="text-align:center">{{$key->nomor_mou_pks}}</td>
                            <td style="text-align:center">{{$key->tanggal_mou_pks}}</td>
                            <td >{{$key->perihal}}</td>
                            <td style="text-align:center">{{$key->masa_berlaku}}</td>
                            <td style="text-align:center">{{$key->pic_lembaga}}</td>
                            <a href="{{$key->dokumen}}"><td style="text-align:center">{{$key->dokumen}}</td></a>
                            <td style="text-align:center" width="100">
                                <btn class="btn btn-warning btn-sm editdata" id="{{$key->id}}"><i class="fas fa-fw fa-edit"></i></btn>
                                <a href="{{url('/dokumen/deleteperjanjiankerjasama')}}/{{$key->id}}">
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
            <h1 class="h3 mb-2 text-gray-800"> Tambah Data Perjanjian Kerjasama</h1>
            <br>
            <form action="{{url('/dokumen/storeperjanjiankerjasama')}}" method="post">
            @csrf
            
                        
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi / Lembaga</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama_instansi" name="nama_instansi" aria-describedby="nama_instansi" value=""
                                    placeholder="Nama Instansi/Lembaga..." required>
                            </div>
                            <div class="form-group row">
                                <label for="desa" class="col-sm-3 control-label">Desa</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="desaclass" name="desa" aria-describedby="desa" value=""
                                    placeholder="Nama Desa..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nomor_mou_pks" class="col-sm-3 control-label">Nomor PKS</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nomor_mou_pks" name="nomor_mou_pks" aria-describedby="nomor_mou_pks" value=""
                                    placeholder="Nomor PKS..." required>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_mou_pks" class="col-sm-3 control-label">Tanggal PKS</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="tanggal_mou_pks" name="tanggal_mou_pks" aria-describedby="tanggal_mou_pks" value=""
                                    placeholder="Tanggal PKS..." required>
                            </div>
                            <div class="form-group row">
                                <label for="satuan_kerja" class="col-sm-3 control-label">Satuan Kerja/Contact Person</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="satuan_kerja" name="satuan_kerja" aria-describedby="satuan_kerja" value=""
                                    placeholder="Satuan Kerja..." required>
                            </div>
                            <div class="form-group row">
                                <label for="perihal" class="col-sm-3 control-label">Perihal</label>
                                    <textarea required class="col-sm-6 form-control form-control-user"
                                    id="perihal" name="perihal" aria-describedby="perihal"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_ptpn" class="col-sm-3 control-label">Ekspektasi/Tujuan</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="ekspektasi_tujuan" name="ekspektasi_tujuan" aria-describedby="ekspektasi_tujuan"></textarea>
                            </div>                                            
                        </div>
                        <div class="col-md-6">
                        @if(Auth::user()->hakakses =='Admin')
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="regionclass" class="col-sm-6 form-control" required>
                                
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
                        @else
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="region" class="col-sm-6 form-control" required>
                                    <option value="{{Auth::user()->region}}">{{Auth::user()->region}}</option>
                                </select>
                            </div>
                        @endif
                            <div class="form-group row">
                                <label for="kebun" class="col-sm-3 control-label">Kebun</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kebunclass" name="kebun" aria-describedby="kebun" value=""
                                    placeholder="Nama Kebun..." required>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="governance" value="Governance" required>
                                        <label class="form-check-label" for="governance">
                                            Governance
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="non_governance" value="Non Governance" required>
                                        <label class="form-check-label" for="non_governance">
                                            Non Governance
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="masa_berlaku" class="col-sm-3 control-label">Masa Berlaku</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="masa_berlaku" name="masa_berlaku" aria-describedby="masa_berlaku" value=""
                                    placeholder="Masa Berlaku..." required>
                            </div>
                            <div class="form-group row">
                                <label for="pic_lembaga" class="col-sm-3 control-label">PIC Lembaga/Instansi</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="pic_lembaga" name="pic_lembaga" aria-describedby="pic_lembaga"></textarea>
                            </div> 
                            <div class="form-group row">
                                <label for="pejabat_yang_menandatangani" class="col-sm-3 control-label">Pejabat Yang Menandatangani</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="pejabat_yang_menandatangani" name="pejabat_yang_menandatangani" aria-describedby="pejabat_yang_menandatangani"></textarea>
                            </div> 
                            <div class="form-group row">
                                <label for="pejabat_yang_menggantikan" class="col-sm-3 control-label">Pejabat Yang Menggantikan</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="pejabat_yang_menggantikan" name="pejabat_yang_menggantikan" aria-describedby="pejabat_yang_menggantikan"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="dokumen" class="col-sm-3 control-label">Dokumen</label>                              
                                    <textarea required class="col-sm-6 form-control form-control-user "
                                    id="dokumen" name="dokumen" aria-describedby="dokumen"></textarea>
                            </div> 
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Tambah </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="modaleditdata">
        <div class="card shadow mb-4 modal modalpopup" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
            <h1 class="h3 mb-2 text-gray-800"> Edit Data Stakeholder</h1>
            <br>
            
            <form action="{{url('/dokumen/updateperjanjiankerjasama')}}" method="post">
                <input type="hidden" name="id" id="idperjanjiankerjasama" required="required" 
                    value="">
            @csrf
            
            <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi / Lembaga</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama_instansi" name="nama_instansi" aria-describedby="nama_instansi" value=""
                                    placeholder="Nama Instansi/Lembaga..." required>
                            </div>
                            <div class="form-group row">
                                <label for="desa" class="col-sm-3 control-label">Desa</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="desaclass" name="desa" aria-describedby="desa" value=""
                                    placeholder="Nama Desa..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nomor_mou_pks" class="col-sm-3 control-label">Nomor PKS</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nomor_mou_pks" name="nomor_mou_pks" aria-describedby="nomor_mou_pks" value=""
                                    placeholder="Nomor PKS..." required>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_mou_pks" class="col-sm-3 control-label">Tanggal PKS</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="tanggal_mou_pks" name="tanggal_mou_pks" aria-describedby="tanggal_mou_pks" value=""
                                    placeholder="Tanggal PKS..." required>
                            </div>
                            <div class="form-group row">
                                <label for="satuan_kerja" class="col-sm-3 control-label">Satuan Kerja/Contact Person</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="satuan_kerja" name="satuan_kerja" aria-describedby="satuan_kerja" value=""
                                    placeholder="Satuan Kerja..." required>
                            </div>
                            <div class="form-group row">
                                <label for="perihal" class="col-sm-3 control-label">Perihal</label>
                                    <textarea required class="col-sm-6 form-control form-control-user"
                                    id="perihal" name="perihal" aria-describedby="perihal"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_ptpn" class="col-sm-3 control-label">Ekspektasi/Tujuan</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="ekspektasi_tujuan" name="ekspektasi_tujuan" aria-describedby="ekspektasi_tujuan"></textarea>
                            </div>                                            
                        </div>
                        <div class="col-md-6">
                        @if(Auth::user()->hakakses =='Admin')
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="regionclass" class="col-sm-6 form-control" required>
                                
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
                        @else
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="region" class="col-sm-6 form-control" required>
                                    <option value="{{Auth::user()->region}}">{{Auth::user()->region}}</option>
                                </select>
                            </div>
                        @endif
                            <div class="form-group row">
                                <label for="kebun" class="col-sm-3 control-label">Kebun</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kebunclass" name="kebun" aria-describedby="kebun" value=""
                                    placeholder="Nama Kebun..." required>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                <div class="col-sm-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="governance" value="Governance" required>
                                        <label class="form-check-label" for="governance">
                                            Governance
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="non_governance" value="Non Governance" required>
                                        <label class="form-check-label" for="non_governance">
                                            Non Governance
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="masa_berlaku" class="col-sm-3 control-label">Masa Berlaku</label>
                                <input type="date" class="col-sm-6 form-control form-control-user"
                                    id="masa_berlaku" name="masa_berlaku" aria-describedby="masa_berlaku" value=""
                                    placeholder="Masa Berlaku..." required>
                            </div>
                            <div class="form-group row">
                                <label for="pic_lembaga" class="col-sm-3 control-label">PIC Lembaga/Instansi</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="pic_lembaga" name="pic_lembaga" aria-describedby="pic_lembaga"></textarea>
                            </div> 
                            <div class="form-group row">
                                <label for="pejabat_yang_menandatangani" class="col-sm-3 control-label">Pejabat Yang Menandatangani</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="pejabat_yang_menandatangani" name="pejabat_yang_menandatangani" aria-describedby="pejabat_yang_menandatangani"></textarea>
                            </div> 
                            <div class="form-group row">
                                <label for="pejabat_yang_menggantikan" class="col-sm-3 control-label">Pejabat Yang Menggantikan</label>                        
                                <textarea required class="col-sm-6 form-control form-control-user "
                                    id="pejabat_yang_menggantikan" name="pejabat_yang_menggantikan" aria-describedby="pejabat_yang_menggantikan"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="dokumen" class="col-sm-3 control-label">Dokumen</label>                              
                                    <textarea required class="col-sm-6 form-control form-control-user "
                                    id="dokumen" name="dokumen" aria-describedby="dokumen"></textarea>
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
    

    $("#region, #kategori, #desa, #kebun").select2({});
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
    // $('.nav_sdm').addClass('active');
</script>
@endsection