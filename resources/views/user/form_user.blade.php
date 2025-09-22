@extends('layouts.app')
@section('content')
@if(isset($datauser->id))
    <h1 class="h3 mb-2 text-gray-800"> EDIT DATA USER</h1>
    @else
    <h1 class="h3 mb-2 text-gray-800"> TAMBAH DATA USER</h1>
    @endif
    <p class="mb-4">PT Perkebunan Nusantara I</p> 
    <div class="modaladddata">       
        <div class="card shadow mb-4">   
        <div class="card-body">     
            <!-- --------------------------------------------------------------------------------------- -->
        @if(isset($datauser->id))
            <form action="{{url('/user/updateuser')}}" method="post">
                <input type="hidden" name="id" required="required" 
                value="@if(isset($datauser->id)){{$datauser->id}}@endif">
                @else
            <form action="{{url('/user/storeuser')}}" method="post">
        @endif
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
                            <label for="name" class="col-sm-3 control-label">Nama</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="name" name="name" aria-describedby="name" value="{{isset($datauser->name)?$datauser->name:''}}"
                                placeholder="Nama User">
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-3 control-label">Username</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="username" name="username" aria-describedby="username" value="{{isset($datauser->username)?$datauser->username:''}}"
                                placeholder="Username...">
                        </div>
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
                        <div class="form-group row">
                            <label for="hakakses" class="col-md-3 control-label">Hak Akses</label>
                            <select name="hakakses" id="hakakses" class="col-sm-6 form-control">
                            @if(isset($datauser->hakakses))
                                <option value="Admin" @if($datauser->hakakses=="Admin") selected @endif>Admin</option>
                                <option value="Member" @if($datauser->hakakses=="Member") selected @endif>Member</option>
                            @else
                                <option value="Admin">Admin</option>
                                <option value="Member">Member</option>
                            @endif                                    
                            </select>
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
    </div>
    @if(isset($datauser->id))
    <div class="modalupdatepassword">
        <div class="card shadow mb-4">   
        <div class="card-body">     
            <!-- --------------------------------------------------------------------------------------- -->
        
            <h3>Ubah Password</h3>
            <form action="{{url('/user/updatepassword')}}" method="post">
                <input type="hidden" name="id" required="required" 
                value="@if(isset($datauser->id)){{$datauser->id}}@endif">
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
                            <label for="password" class="col-sm-3 control-label">Password</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="password" name="password" aria-describedby="password" value=""
                                placeholder="Password...">
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 control-label">re-Password</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="password2" name="password2" aria-describedby="password2" value=""
                                placeholder="re-Password...">
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
    </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
    //var modaladddata = $('.modaladddata').detach();
    //var modaleditdata = $('.modalupdatepassword').detach();

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
    </script>
@endsection