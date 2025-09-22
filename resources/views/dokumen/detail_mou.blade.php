@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-2 text-gray-800"> DETAIL NOTA KESEPAHAMAN</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="{{url('/dokumen/perjanjiankerjasama')}}" method="get">              
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
                                <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi/Lembaga</label>
                                <label for="nama_instansi" class="col-sm-6 control-label"> : {{isset($datauser->nama_instansi)?$datauser->nama_instansi:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="desa" class="col-sm-3 control-label">Desa</label>
                                <label for="desa" class="col-sm-6 control-label"> : {{isset($datauser->desa)?$datauser->desa:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="nomor_mou_pks" class="col-md-3 control-label">Nomor PKS</label>
                                <label for="nomor_mou_pks" class="col-sm-6 control-label"> : {{isset($datauser->nomor_mou_pks)?$datauser->nomor_mou_pks:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="tanggal_mou_pks" class="col-sm-3 control-label">Tanggal PKS</label>
                                <label for="tanggal_mou_pks" class="col-sm-6 control-label"> : {{isset($datauser->tanggal_mou_pks)?$datauser->tanggal_mou_pks:''}}</label>
                            </div>
                            
                            <div class="form-group row">
                                <label for="satuan_kerja" class="col-sm-3 control-label">Satuan Kerja/Contact Person</label>
                                <label for="satuan_kerja" class="col-sm-6 control-label"> : {{isset($datauser->satuan_kerja)?$datauser->satuan_kerja:''}}</label>
                            </div>
                            
                            <div class="form-group row">
                                <label for="perihal" class="col-sm-3 control-label">Perihal</label>
                                <label for="perihal" class="col-sm-6 control-label"> : {{isset($datauser->perihal)?$datauser->perihal:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_tujuan" class="col-sm-3 control-label">Ekspektasi/Tujuan</label>
                                <label for="ekspektasi_tujuan" class="col-sm-6 control-label"> : {{isset($datauser->ekspektasi_tujuan)?$datauser->ekspektasi_tujuan:''}}</label>
                            </div>
                                                   
                        </div>
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="region" class="col-sm-3 control-label">Region</label>
                                <label for="region" class="col-sm-6 control-label"> : {{isset($datauser->region)?$datauser->region:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="kebun" class="col-md-3 control-label">Kebun</label>
                                <label for="kebun" class="col-sm-6 control-label"> : {{isset($datauser->kebun)?$datauser->kebun:''}}</label>
                            </div>   
                            <div class="form-group row">
                                <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                <label for="kategori" class="col-sm-6 control-label"> : {{isset($datauser->kategori)?$datauser->kategori:''}}</label>
                            </div> 
                            <div class="form-group row">
                                <label for="masa_berlaku" class="col-md-3 control-label">Masa Berlaku</label>
                                <label for="masa_berlaku" class="col-sm-6 control-label"> : {{isset($datauser->masa_berlaku)?$datauser->masa_berlaku:''}}</label>
                            </div>                     
                            <div class="form-group row">
                                <label for="pic_lembaga" class="col-md-3 control-label">PIC Lembaga/Instansi</label>
                                <label for="pic_lembaga" class="col-sm-6 control-label"> : {{isset($datauser->pic_lembaga)?$datauser->pic_lembaga:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="pejabat_yang_menandatangani" class="col-sm-3 control-label">Pejabat Yang Menandatangani</label>
                                <label for="pejabat_yang_menandatangani" class="col-sm-6 control-label"> : {{isset($datauser->pejabat_yang_menandatangani)?$datauser->pejabat_yang_menandatangani:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="pejabat_yang_menggantikan" class="col-sm-3 control-label">Pejabat Yang Menggantikan</label>
                                <label for="pejabat_yang_menggantikan" class="col-sm-6 control-label"> : {{isset($datauser->pejabat_yang_menggantikan)?$datauser->pejabat_yang_menggantikan:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="dokumen" class="col-sm-3 control-label">Dokumen</label>
                                <label for="dokumen" class="col-sm-6 control-label"> : {{isset($datauser->dokumen)?$datauser->dokumen:''}}</label>
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
@endsection