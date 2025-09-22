@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-2 text-gray-800"> DETAIL DATA STAKEHOLDER</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="{{url('/dash/stakeholder')}}" method="get">              
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
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <label for="region" class="col-sm-6 control-label"> : {{isset($datauser->region)?$datauser->region:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="kebun" class="col-sm-3 control-label">Kebun</label>
                                <label for="kebun" class="col-sm-6 control-label"> : {{isset($datauser->kebun)?$datauser->kebun:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi/Stakeholder</label>
                                <label for="nama_instansi" class="col-sm-6 control-label"> : {{isset($datauser->nama_instansi)?$datauser->nama_instansi:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="daerah_instansi" class="col-sm-3 control-label">Daerah Instansi</label>
                                <label for="daerah_instansi" class="col-sm-6 control-label"> : {{isset($datauser->daerah_instansi)?$datauser->daerah_instansi:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="desa" class="col-sm-3 control-label">Desa</label>
                                <label for="desa" class="col-sm-6 control-label"> : {{isset($datauser->desa)?$datauser->desa:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="nama_pic" class="col-sm-3 control-label">Nama PIC</label>
                                <label for="nama_pic" class="col-sm-6 control-label"> : {{isset($datauser->nama_pic)?$datauser->nama_pic:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="jabatan_pic" class="col-sm-3 control-label">Jabatan PIC</label>
                                <label for="jabatan_pic" class="col-sm-6 control-label"> : {{isset($datauser->jabatan_pic)?$datauser->jabatan_pic:''}}</label>
                            </div>
                                                   
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nomorkontak_pic" class="col-sm-3 control-label">Nomor Kontak PIC</label>
                                <label for="nomorkontak_pic" class="col-sm-6 control-label"> : {{isset($datauser->nomorkontak_pic)?$datauser->nomorkontak_pic:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="derajat_hubungan" class="col-md-3 control-label">Derajat Hubungan</label>
                                <div class="derajat_hubungan col-sm-6 control-label"> : {{isset($datauser->derajat_hubungan)?$datauser->derajat_hubungan:''}}
                                    @if($datauser->derajat_hubungan=="Tipe A")
                                    - Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan
                                    @elseif($datauser->derajat_hubungan=="Tipe B")
                                    - Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.
                                    @elseif($datauser->derajat_hubungan=="Tipe C")
                                    - Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.
                                    @endif
                                </div>
                            </div>   
                            <div class="form-group row">
                                <label for="curent_condition" class="col-md-3 control-label">Curent Condition</label>
                                <label for="curent_condition" class="col-sm-6 control-label"> : {{isset($datauser->curent_condition)?$datauser->curent_condition:''}}</label>
                            </div> 
                            <div class="form-group row">
                                <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                <div class="kategori col-sm-6 control-label"> : {{isset($datauser->kategori)?$datauser->kategori:''}}</div>
                            </div>                     
                            <div class="form-group row">
                                <label for="skala_kekuatan" class="col-md-3 control-label">Skala Kekuatan</label>
                                <div class="skala_kekuatan col-sm-6 control-label"> : {{isset($datauser->skala_kekuatan)?$datauser->skala_kekuatan:''}}
                                    - Evaluasi sejauh mana stakeholder memiliki pengaruh terhadap proyek atau aktivitas organisasi.
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="skala_kepentingan" class="col-md-3 control-label">Skala Kepentingan</label>
                                <div class="skala_kepentingan col-sm-6 control-label"> : {{isset($datauser->skala_kepentingan)?$datauser->skala_kepentingan:''}}
                                    - Evaluasi sejauh mana stakeholder tertarik pada proyek atau aktivitas organisasi.
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 control-label">Email / Media Sosial PIC</label>
                                <label for="email" class="col-sm-6 control-label"> : {{isset($datauser->email)?$datauser->email:''}}</label>
                            </div>
                            
                        </div>
                    </div>
                    <br>
                    <br>
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="ekspektasi_ptpn" class="col-sm-12 control-label"><strong>Ekspektasi PTPN :</strong></label>
                                <label for="ekspektasi_ptpn" class="col-sm-12 control-label">{{isset($datauser->ekspektasi_ptpn)?$datauser->ekspektasi_ptpn:''}}</label>
                            </div>
                            
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="ekspektasi_stakeholder" class="col-sm-12 control-label"><strong>Ekspektasi Stakeholder :</strong></label>
                                <label for="ekspektasi_stakeholder" class="col-sm-12 control-label">{{isset($datauser->ekspektasi_stakeholder)?$datauser->ekspektasi_stakeholder:''}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-12">
                            <div class="form-group row">
                                <label for="pdf" class="col-sm-3 control-label"><strong>Dokumen Pendukung</strong></label>
                                <div class="col-sm-12">
                                    @if(isset($datauser->dokumenpendukung))
                                        <embed src="{{ asset('pdf/' . $datauser->dokumenpendukung) }}" type="application/pdf" width="100%" height="600px">
                                            <br>
                                        
                                    @else
                                        Tidak ada dokumen pendukung.
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                
                <!-- /.card-body -->
                {{-- <div class="card-footer"> --}}
                  <button type="submit" name="submit" class="btn btn-success float-right">
                    Kembali
                    </button>
                {{-- </div> --}}
              </form>
        </div>
    </div>
@endsection