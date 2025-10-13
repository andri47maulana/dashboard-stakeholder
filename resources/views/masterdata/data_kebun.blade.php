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
                        <td class="d-flex gap-1">
                            <a href="{{ route('units.detail', $unit->id) }}" class="btn btn-sm btn-primary">Detail</a>
                            <button type="button"
                                    class="btn btn-sm btn-warning btn-edit-unit"
                                    data-id="{{ $unit->id }}"
                                    data-unit="{{ $unit->unit }}"
                                    data-region="{{ $unit->region }}">
                                Edit
                            </button>
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

<!-- Modal Edit Unit/Kebun -->
<div class="modal fade" id="editUnitModal" tabindex="-1" aria-labelledby="editUnitLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editUnitLabel">Edit Unit/Kebun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="editUnitForm" method="POST">
        @csrf
        <div class="modal-body">
            <input type="hidden" name="id" id="edit_id">
            <div class="mb-3">
                <label for="edit_unit" class="form-label">Nama Unit/Kebun</label>
                <input type="text" class="form-control" id="edit_unit" name="unit" required>
            </div>
            <div class="mb-3">
                <label for="edit_region" class="form-label">Region</label>
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
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
 </div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    const modalEl = document.getElementById('editUnitModal');
    const form = document.getElementById('editUnitForm');
    const inputId = document.getElementById('edit_id');
    const inputUnit = document.getElementById('edit_unit');
    const inputRegion = document.getElementById('edit_region');

    // Delegate click to Edit buttons
    document.addEventListener('click', function(e){
        const btn = e.target.closest('.btn-edit-unit');
        if (!btn) return;
        const id = btn.getAttribute('data-id');
        const unit = btn.getAttribute('data-unit') || '';
        const region = btn.getAttribute('data-region') || '';

        inputId.value = id;
        inputUnit.value = unit;
    // Preselect region in dropdown
    const regionSelect = document.getElementById('edit_region');
    Array.from(regionSelect.options).forEach(opt => { opt.selected = (opt.value === region); });
        // Set form action to update route
        form.setAttribute('action', `{{ url('/masterdata/data_kebun') }}/${id}/update`);

        // Show modal (Bootstrap 5)
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    });
});
</script>
@endsection