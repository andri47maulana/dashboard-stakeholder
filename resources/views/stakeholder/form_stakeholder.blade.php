@extends('layouts.app')
@section('content')
@if(isset($datauser->id))
    <h1 class="h3 mb-2 text-gray-800"> EDIT DATA STAKEHOLDER</h1>
    @else
    <h1 class="h3 mb-2 text-gray-800"> TAMBAH DATA STAKEHOLDER</h1>
    @endif
    <p class="mb-4">PT Perkebunan Nusantara I</p>
    <div class="card shadow mb-4">   
        <div class="card-body">     
            <!-- --------------------------------------------------------------------------------------- -->
        @if(isset($datauser->id))
            <form action="{{url('/dash/updatestakeholder')}}" method="post">
                <input type="hidden" name="id" required="required" 
                value="@if(isset($datauser->id)){{$datauser->id}}@endif">
                @else
            <form action="{{url('/dash/storestakeholder')}}" method="post">
        @endif
        @csrf
        
                    <style>
                        .form-group {
                            margin-bottom: 0.5rem!important;
                        }
                        .form-control {
                            font-size: 1em!important;
                        }
                    </style>
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                        @if(Auth::user()->hakakses =='Admin')
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="region" class="col-sm-6 form-control">
                                @if(isset($datauser->region))
                                
                                   
                                    <option value="PTPN I HO" @if($datauser->region=="PTPN I HO") selected @endif>PTPN I HO</option>
                                    <option value="PTPN I Regional 1" @if($datauser->region=="PTPN I Regional 1") selected @endif>PTPN I Regional 1</option>
                                    <option value="PTPN I Regional 2" @if($datauser->region=="PTPN I Regional 2") selected @endif>PTPN I Regional 2</option>
                                    <option value="PTPN I Regional 3" @if($datauser->region=="PTPN I Regional 3") selected @endif>PTPN I Regional 3</option>
                                    <option value="PTPN I Regional 4" @if($datauser->region=="PTPN I Regional 4") selected @endif>PTPN I Regional 4</option>
                                    <option value="PTPN I Regional 5" @if($datauser->region=="PTPN I Regional 5") selected @endif>PTPN I Regional 5</option>
                                    <option value="PTPN I Regional 6" @if($datauser->region=="PTPN I Regional 6") selected @endif>PTPN I Regional 6</option>
                                    <option value="PTPN I Regional 7" @if($datauser->region=="PTPN I Regional 7") selected @endif>PTPN I Regional 7</option>
                                    <option value="PTPN I Regional 8" @if($datauser->region=="PTPN I Regional 8") selected @endif>PTPN I Regional 8</option>
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
                        @else
                        <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="region" class="col-sm-6 form-control">
                                    <option value="{{Auth::user()->region}}">{{Auth::user()->region}}</option>
                                </select>
                            </div>
                        @endif
                            <div class="form-group row">
                                <label for="kebun" class="col-sm-3 control-label">Kebun</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kebun" name="kebun" aria-describedby="kebun" value="{{isset($datauser->kebun)?$datauser->kebun:''}}"
                                    placeholder="Nama Kebun...">
                            </div>
                            <div class="form-group row">
                                <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi /Stakeholder</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama_instansi" name="nama_instansi" aria-describedby="nama_instansi" value="{{isset($datauser->nama_instansi)?$datauser->nama_instansi:''}}"
                                    placeholder="Nama Instansi/Stakeholder...">
                            </div>
                            <div class="form-group row">
                                <label for="daerah_instansi" class="col-sm-3 control-label">Daerah Instansi</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="daerah_instansi" name="daerah_instansi" aria-describedby="daerah_instansi" value="{{isset($datauser->daerah_instansi)?$datauser->daerah_instansi:''}}"
                                    placeholder="Daerah Instansi...">
                            </div>
                            <div class="form-group row">
                                <label for="desa" class="col-sm-3 control-label">Desa</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="desa" name="desa" aria-describedby="desa" value="{{isset($datauser->desa)?$datauser->desa:''}}"
                                    placeholder="Nama Desa...">
                            </div>
                            <div class="form-group row">
                                <label for="latlong" class="col-sm-3 control-label">Lat, Long</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="latlong" name="latlong" aria-describedby="latlong" value="{{isset($datauser->latlong)?$datauser->latlong:''}}"
                                    placeholder="-6.200000,106.816666">
                            </div>
                            <div class="form-group row">
                                <label for="nama_pic" class="col-sm-3 control-label">Nama PIC</label>
                                    <textarea class="col-sm-6 form-control form-control-user"
                                    id="nama_pic" name="nama_pic" aria-describedby="nama_pic">{{isset($datauser->nama_pic)?$datauser->nama_pic:''}}</textarea>
                            </div>
                            <div class="form-group row">
                                <label for="jabatan_pic" class="col-sm-3 control-label">Jabatan PIC</label>
                                    <textarea class="col-sm-6 form-control form-control-user"
                                    id="jabatan_pic" name="jabatan_pic" aria-describedby="jabatan_pic">{{isset($datauser->jabatan_pic)?$datauser->jabatan_pic:''}}</textarea>
                            </div>
                            <div class="form-group row">
                                <label for="nomorkontak_pic" class="col-sm-3 control-label">Nomor Kontak PIC</label>
                                <input type="number" class="col-sm-6 form-control form-control-user"
                                    id="nomorkontak_pic" name="nomorkontak_pic" aria-describedby="nomorkontak_pic" value="{{isset($datauser->nomorkontak_pic)?$datauser->nomorkontak_pic:''}}"
                                    placeholder="Nomor Kontak...">
                            </div>
                                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="derajat_hubungan" class="col-md-3 control-label">Derajat Hubungan</label>
                                <select name="derajat_hubungan" id="derajat_hubungan" class="col-sm-6 form-control">
                                @if(isset($datauser->derajat_hubungan))
                                    <option value="Tipe A" @if($datauser->derajat_hubungan=="Tipe A") selected @endif>Tipe A</option>
                                    <option value="Tipe B" @if($datauser->derajat_hubungan=="Tipe B") selected @endif>Tipe B</option>
                                    <option value="Tipe C" @if($datauser->derajat_hubungan=="Tipe C") selected @endif>Tipe C</option>
                                @else
                                    <option value="">Pilih..</option>
                                    <option value="Tipe A">Tipe A</option>
                                    <option value="Tipe B">Tipe B</option>
                                    <option value="Tipe C">Tipe C</option>
                                @endif                                    
                                </select>
                            </div>          
                            <div class="form-group row">
                                <label for="curent_condition" class="col-md-3 control-label">Curent Condition</label>
                                <select name="curent_condition" id="curent_condition" class="col-sm-6 form-control">
                                @if(isset($datauser->curent_condition))
                                    <option value="Sangat Baik" @if($datauser->curent_condition=="Sangat Baik") selected @endif>Sangat Baik</option>
                                    <option value="Baik" @if($datauser->curent_condition=="Baik") selected @endif>Baik</option>
                                    <option value="Cukup Baik" @if($datauser->curent_condition=="Cukup Baik") selected @endif>Cukup Baik</option>
                                    <option value="Kurang Baik" @if($datauser->curent_condition=="Kurang Baik") selected @endif>Kurang Baik</option>
                                    <option value="Tidak Baik" @if($datauser->curent_condition=="Tidak Baik") selected @endif>Tidak Baik</option>
                                @else
                                    <option value="Sangat Baik">Sangat Baik</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup Baik">Cukup Baik</option>
                                    <option value="Kurang Baik">Kurang Baik</option>
                                    <option value="Tidak Baik">Tidak Baik</option>
                                @endif                                    
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                <select name="kategori" id="kategori" class="col-sm-6 form-control">
                                @if(isset($datauser->kategori))
                                    <option value="Governance" @if($datauser->kategori=="Governance") selected @endif>Governance cik</option>
                                    <option value="Non Governance" @if($datauser->kategori=="Non Governance") selected @endif>Non Governance</option>
                                @else
                                    <option value="">Pilih..</option>
                                    <option value="Governance">Governance</option>
                                    <option value="Non Governance">Non Governance</option>
                                @endif                                    
                                </select>
                            </div>                     
                            <div class="form-group row">
                                <label for="skala_kekuatan" class="col-md-3 control-label">Skala Kekuatan</label>
                                <select name="skala_kekuatan" id="skala_kekuatan" class="col-sm-6 form-control">
                                @if(isset($datauser->skala_kekuatan))
                                    <option value="1"  @if($datauser->skala_kekuatan=="1") selected @endif>1</option>
                                    <option value="2"  @if($datauser->skala_kekuatan=="2") selected @endif>2</option>
                                    <option value="3"  @if($datauser->skala_kekuatan=="3") selected @endif>3</option>
                                    <option value="4"  @if($datauser->skala_kekuatan=="4") selected @endif>4</option>
                                    <option value="5"  @if($datauser->skala_kekuatan=="5") selected @endif>5</option>
                                @else
                                    <option value="">Pilih..</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                @endif                                    
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="skala_kepentingan" class="col-md-3 control-label">Skala Kepentingan</label>
                                <select name="skala_kepentingan" id="skala_kepentingan" class="col-sm-6 form-control">
                                @if(isset($datauser->skala_kepentingan))
                                    <option value="1"  @if($datauser->skala_kepentingan=="1") selected @endif>1</option>
                                    <option value="2"  @if($datauser->skala_kepentingan=="2") selected @endif>2</option>
                                    <option value="3"  @if($datauser->skala_kepentingan=="3") selected @endif>3</option>
                                    <option value="4"  @if($datauser->skala_kepentingan=="4") selected @endif>4</option>
                                    <option value="5"  @if($datauser->skala_kepentingan=="5") selected @endif>5</option>
                                @else
                                    <option value="">Pilih..</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                @endif                                    
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 control-label">Email / Media Sosial PIC</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="email" name="email" aria-describedby="email" value="{{isset($datauser->email)?$datauser->email:''}}"
                                    placeholder="Email / Media Sosial PIC...">
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_ptpn" class="col-sm-3 control-label">Ekspektasi PTPN</label>
                                <!-- <input type="text" class="col-sm-6 form-control form-control-user datepicker"
                                    id="ekspektasi_ptpn" name="ekspektasi_ptpn" aria-describedby="ekspektasi_ptpn" value="{{isset($datauser->ekspektasi_ptpn)?$datauser->ekspektasi_ptpn:''}}"
                                    placeholder="Ekspektasi PTPN..."> -->
                                <textarea class="col-sm-6 form-control form-control-user datepicker"
                                    id="ekspektasi_ptpn" name="ekspektasi_ptpn" aria-describedby="ekspektasi_ptpn">{{isset($datauser->ekspektasi_ptpn)?$datauser->ekspektasi_ptpn:''}}</textarea>
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_stakeholder" class="col-sm-3 control-label">Ekspektasi Stakeholder</label>
                                <!-- <input type="text" class="col-sm-6 form-control form-control-user datepicker"
                                    id="ekspektasi_stakeholder" name="ekspektasi_stakeholder" aria-describedby="ekspektasi_stakeholder" value="{{isset($datauser->ekspektasi_stakeholder)?$datauser->ekspektasi_stakeholder:''}}"
                                    placeholder="Ekspektasi Stakeholder..."> -->
                                    <textarea class="col-sm-6 form-control form-control-user datepicker"
                                    id="ekspektasi_stakeholder" name="ekspektasi_stakeholder" aria-describedby="ekspektasi_stakeholder">{{isset($datauser->ekspektasi_stakeholder)?$datauser->ekspektasi_stakeholder:''}}</textarea>
                            </div>
                        </div>
                    </div>
                
                <!-- /.card-body -->
                <div class="card-footer">
                  
                    @if(isset($datauser->id))
                    <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Update </button>
                    @else
                    <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Submit </button>
                    @endif
                </div>

              </form>
        </div>
    </div>
@endsection