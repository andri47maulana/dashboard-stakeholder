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

<!-- Alert Messages -->
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Sukses!</strong> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if($errors->any())
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error!</strong>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
        {{-- <div class="container"> --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Daftar Unit</h2>
            @if(Auth::check() && Auth::user()->hakakses == 'Admin')
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addUnitModal">
                <i class="fas fa-plus"></i> Tambah Data Unit
            </button>
            @endif
        </div>
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
                        <td class="d-flex gap-1">
                            <a href="{{ route('units.detail', $unit->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            @if(Auth::check() && Auth::user()->hakakses == 'Admin')
                            <button type="button"
                                    class="btn btn-sm btn-warning btn-edit-unit"
                                    data-id="{{ $unit->id }}"
                                    data-unit="{{ $unit->unit }}"
                                    data-region="{{ $unit->region }}">
                                Edit
                            </button>
                            <button type="button"
                                    class="btn btn-sm btn-danger btn-delete-unit"
                                    data-id="{{ $unit->id }}"
                                    data-unit="{{ $unit->unit }}">
                                Hapus
                            </button>
                            @endif
                        </td>
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

<!-- Modal Tambah Unit/Kebun -->
<div class="modal fade" id="addUnitModal" tabindex="-1" role="dialog" aria-labelledby="addUnitLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addUnitLabel">Tambah Unit/Kebun Baru</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('units.store') }}" method="POST">
        @csrf
        <div class="modal-body">
            <div class="form-group">
                <label for="add_unit">Nama Unit/Kebun <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="add_unit" name="unit" required>
            </div>
            <div class="form-group">
                <label for="add_region">Region <span class="text-danger">*</span></label>
                <select class="form-control" id="add_region" name="region" required>
                    <option value="" disabled selected>Pilih Region…</option>
                    @if(isset($regions))
                        @foreach($regions as $r)
                            <option value="{{ $r->region }}">{{ $r->region }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-success">Simpan Data</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Unit/Kebun -->
<div class="modal fade" id="editUnitModal" tabindex="-1" role="dialog" aria-labelledby="editUnitLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUnitLabel">Edit Unit/Kebun</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="editUnitForm" method="POST">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">
            <div class="form-group">
                <label for="edit_unit">Nama Unit/Kebun</label>
                <input type="text" class="form-control" id="edit_unit" name="unit" required>
            </div>
            <div class="form-group">
                <label for="edit_region">Region</label>
                <select class="form-control" id="edit_region" name="region" required>
                    <option value="" disabled selected>Pilih Region…</option>
                    @if(isset($regions))
                        @foreach($regions as $r)
                            <option value="{{ $r->region }}">{{ $r->region }}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
 </div>

@endsection

@push('scripts')
<script>
$(document).ready(function(){
    // Handle Edit button click
    $(document).on('click', '.btn-edit-unit', function(){
        var id = $(this).data('id');
        var unit = $(this).data('unit') || '';
        var region = $(this).data('region') || '';

        // Fill form fields
        $('#edit_id').val(id);
        $('#edit_unit').val(unit);
        $('#edit_region').val(region);

        // Set form action to update route
        $('#editUnitForm').attr('action', '{{ url("/masterdata/data_kebun") }}/' + id + '/update');

        // Show modal (Bootstrap 4)
        $('#editUnitModal').modal('show');
    });

    // Handle Delete button click
    $(document).on('click', '.btn-delete-unit', function(){
        var id = $(this).data('id');
        var unit = $(this).data('unit') || '';

        // Show confirmation dialog using SweetAlert2
        Swal.fire({
            title: 'Konfirmasi Hapus',
            text: 'Apakah Anda yakin ingin menghapus unit "' + unit + '"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create form and submit
                var form = $('<form>', {
                    'method': 'POST',
                    'action': '{{ url("/masterdata/data_kebun") }}/' + id + '/delete'
                });

                // Add CSRF token
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': '_token',
                    'value': '{{ csrf_token() }}'
                }));

                // Append to body and submit
                $('body').append(form);
                form.submit();
            }
        });
    });
});
</script>
@endpush