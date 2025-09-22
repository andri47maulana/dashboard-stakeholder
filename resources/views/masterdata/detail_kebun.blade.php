@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-2 text-gray-800"> DETAIL KEBUN</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="{{url('/masterdata/kebun')}}" method="get">              
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
                                <label for="nama_kebun" class="col-sm-3 control-label">Nama Kebun</label>
                                <label for="nama_kebun" class="col-sm-6 control-label"> : {{isset($datauser->nama_kebun)?$datauser->nama_kebun:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="region" class="col-sm-3 control-label">Region</label>
                                <label for="region" class="col-sm-6 control-label"> : {{isset($datauser->region)?$datauser->region:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="provinsi" class="col-md-3 control-label">Provinsi</label>
                                <label for="provinsi" class="col-sm-6 control-label"> : {{isset($datauser->provinsi)?$datauser->provinsi:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="kabupaten" class="col-sm-3 control-label">Kabupaten</label>
                                <label for="kabupaten" class="col-sm-6 control-label"> : {{isset($datauser->kabupaten)?$datauser->kabupaten:''}}</label>
                            </div>                                        
                        </div>
                    </div>
                   
                
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-success float-right">
                    Back
                    </button>
                </div>
              </form>
        </div>
    </div>
@endsection