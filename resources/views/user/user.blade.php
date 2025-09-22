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
</style>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">User Management</h1>
    <p class="mb-4">Daftar User Aplikasi TJSL PT Perkebunan Nusantara I.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        
        <div class="card-body">
            <a href="{{url('/user/form_user_add')}}"><button class="btn btn-primary btn-rounded add_user">
            <i class="fas fa-fw fa-plus"></i> Tambah Data
            </button></a>
            <br><br>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">Nama</th>
                            <th style="text-align:center">Username</th>
                            <th style="text-align:center">Region</th>
                            <th style="text-align:center">Hak Akses</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        @foreach($dataalluser as $key)
                        <tr>
                            <td>{{$key->name}}</td>
                            <td style="text-align:center">{{$key->username}}</td>
                            <td style="text-align:center">{{$key->region}}</td>
                            <td style="text-align:center">{{$key->hakakses}}</td>
                            <td style="text-align:center">
                                <a href="{{url('/user/form_user_edit')}}/{{$key->id}}">
                                <btn class="btn btn-warning btn-sm"><i class="fas fa-fw fa-edit"></i> Edit</btn>
                                </a>
                                <a href="{{url('/user/deleteuser')}}/{{$key->id}}">
                                <btn class="btn btn-danger btn-sm deletedata"><i class="fas fa-fw fa-trash"></i> Hapus<btn>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
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