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
<h1 class="h3 mb-2 text-gray-800">Master Data Kebun</h1>
    <p class="mb-4">Master Data Kebun PT Perkebunan Nusantara I.</p>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
        {{-- <div class="container"> --}}
        <h2 class="mb-4">Daftar Unit</h2>
        <div class="table-responsive">
        <table class="table table-bordered table-striped" id="dataTable" width="100%" cellspacing="0">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Unit</th>
                    <th>Region</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($units as $unit)
                    <tr>
                        {{-- <td>{{ $unit->id }}</td> --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $unit->unit }}</td>
                        <td>{{ $unit->region }}</td>
                        <td> <a href="{{ route('units.detail', $unit->id) }}" class="btn btn-sm btn-primary">
                                Detail
                            </a></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center">Data tidak tersedia</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        </div>
    {{-- </div> --}}
    </div>
    </div>
    </div>
</div>
</script>
@endsection