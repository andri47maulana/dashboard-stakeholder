@extends('layouts.app')
@section('content')
    <h1 class="h3 mb-2 text-gray-800"> DETAIL PERIZINAN</h1>
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
                                <label for="nama" class="col-sm-3 control-label">Nama</label>
                                <label for="nama" class="col-sm-6 control-label"> : {{isset($datauser->nama)?$datauser->nama:''}}</label>
                            </div>
                            <div class="form-group row">
                                <label for="jenis_perizinan" class="col-sm-3 control-label">Jenis Perizinan</label>
                                <label for="jenis_perizinan" class="col-sm-6 control-label"> : {{isset($datauser->jenis_perizinan)?$datauser->jenis_perizinan:''}}</label>
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