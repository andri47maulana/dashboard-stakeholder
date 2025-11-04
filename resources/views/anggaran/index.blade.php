@extends('layouts.app')

@section('title', 'Anggaran TJSL')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Data Anggaran TJSL</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#createAnggaranModal">
                                <i class="fas fa-plus"></i> Tambah Anggaran
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped" id="anggaranTable">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Regional</th>
                                        <th>Sub Pilar</th>
                                        <th>Tahun</th>
                                        <th>Anggaran</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($anggarans as $index => $anggaran)
                                        <tr>
                                            <td>{{ $anggarans->firstItem() + $index }}</td>
                                            <td>{{ $anggaran->regional_id }}
                                            </td>
                                            <td>{{ $anggaran->subPilar->id . ' - ' . $anggaran->subPilar->sub_pilar ?? '-' }}
                                            </td>
                                            <td>{{ $anggaran->tahun }}</td>
                                            <td>Rp {{ number_format($anggaran->anggaran, 0, ',', '.') }}</td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-warning edit-btn"
                                                    data-id="{{ $anggaran->id }}" data-toggle="modal"
                                                    data-target="#editAnggaranModal">
                                                    <i class="fas fa-edit"></i> Edit
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger delete-btn"
                                                    data-id="{{ $anggaran->id }}">
                                                    <i class="fas fa-trash"></i> Hapus
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data anggaran</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center">
                            {{ $anggarans->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createAnggaranModal" tabindex="-1" role="dialog"
        aria-labelledby="createAnggaranModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createAnggaranModalLabel">Tambah Anggaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="createAnggaranForm" method="POST" action="{{ route('anggaran.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="sub_pilar_id">Sub Pilar <span class="text-danger">*</span></label>
                            <select class="form-control" id="sub_pilar_id" name="sub_pilar_id" required>
                                <option value="">Pilih Sub Pilar</option>
                                @foreach ($subPilars as $subPilar)
                                    <option value="{{ $subPilar->id }}">
                                        {{ $subPilar->id }} - {{ $subPilar->sub_pilar }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Create Modal: field Regional -->
                        <div class="form-group">
                            <label for="regional_id">Regional <span class="text-danger">*</span></label>
                            <select class="form-control" id="regional_id" name="regional_id" required>
                                <option value="">Pilih Regional</option>
                                @foreach ($regionals as $regional)
                                    <option value="{{ $regional->regional_id }}">
                                        {{ $regional->regional_id }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Create Modal: field Tahun -->
                        <div class="form-group">
                            <label for="tahun">Tahun <span class="text-danger">*</span></label>
                            @php $currentYear = now()->year; @endphp
                            <select class="form-control" id="tahun" name="tahun" required>
                                @foreach (range($currentYear, $currentYear + 10) as $y)
                                    <option value="{{ $y }}"
                                        {{ (string) old('tahun', $currentYear) === (string) $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="anggaran">Anggaran <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="anggaran" name="anggaran"
                                placeholder="Masukkan jumlah anggaran" min="0" step="0.01" required>
                            <div class="invalid-feedback"></div>
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

    <!-- Edit Modal -->
    <div class="modal fade" id="editAnggaranModal" tabindex="-1" role="dialog"
        aria-labelledby="editAnggaranModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editAnggaranModalLabel">Edit Anggaran</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editAnggaranForm" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" id="edit_anggaran_id" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_sub_pilar_id">Sub Pilar <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_sub_pilar_id" name="sub_pilar_id" required>
                                <option value="">Pilih Sub Pilar</option>
                                @foreach ($subPilars as $subPilar)
                                    <option value="{{ $subPilar->id }}">
                                        {{ $subPilar->id }} - {{ $subPilar->sub_pilar }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Edit Modal: field Regional -->
                        <div class="form-group">
                            <label for="edit_regional_id">Regional <span class="text-danger">*</span></label>
                            <select class="form-control" id="edit_regional_id" name="regional_id" required>
                                <option value="">Pilih Regional</option>
                                @foreach ($regionals as $regional)
                                    <option value="{{ $regional->regional_id }}">
                                        {{ $regional->regional_id }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>

                        <!-- Edit Modal: field Tahun -->
                        <div class="form-group">
                            <label for="edit_tahun">Tahun <span class="text-danger">*</span></label>
                            @php $currentYear = now()->year; @endphp
                            <select class="form-control" id="edit_tahun" name="tahun" required>
                                @foreach (range($currentYear, $currentYear + 10) as $y)
                                    <option value="{{ $y }}">{{ $y }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="form-group">
                            <label for="edit_anggaran">Anggaran <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="edit_anggaran" name="anggaran"
                                placeholder="Masukkan jumlah anggaran" min="0" step="1" required>
                            <div class="invalid-feedback"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="{{ asset('admin/plugins/sweetalert2/sweetalert2.all.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Create Anggaran (AJAX tetap dipakai; ada fallback form POST)
            $('#createAnggaranForm').on('submit', function(e) {
                e.preventDefault();

                // Clear previous validation states
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // // Debug: Log form data
                // console.log('=== FORM SUBMIT DEBUG ===');
                // console.log('Form data:', $(this).serialize());
                // console.log('CSRF Token:', $('meta[name="csrf-token"]').attr('content'));

                $.ajax({
                    url: '{{ route('anggaran.store') }}',
                    method: 'POST',
                    data: $(this).serialize(),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    success: function(response) {
                        // console.log('=== SUCCESS RESPONSE ===');
                        // console.log('Response:', response);
                        if (response.success) {
                            $('#createAnggaranModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        // console.log('=== ERROR RESPONSE ===');
                        // console.log('Status:', xhr.status);
                        // console.log('Response Text:', xhr.responseText);
                        // console.log('Response JSON:', xhr.responseJSON);

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const input = $(`[name="${field}"]`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat menyimpan data'
                            });
                        }
                    }
                });
            });

            // Edit Button Click
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');

                $.ajax({
                    // Perbaiki: gunakan route yang sesuai untuk show
                    url: `{{ url('anggaran/show') }}/${id}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            $('#edit_anggaran_id').val(data.id);
                            $('#edit_sub_pilar_id').val(data.sub_pilar_id);

                            // Set Regional pada form edit
                            $('#edit_regional_id').val(data.regional_id);

                            // Pastikan dropdown tahun memiliki opsi tahun data yang mungkin di luar range
                            const $editTahun = $('#edit_tahun');
                            const tahunVal = String(data.tahun);
                            if ($editTahun.find(`option[value="${tahunVal}"]`).length === 0) {
                                $editTahun.append(
                                    `<option value="${tahunVal}">${tahunVal}</option>`);
                            }
                            $editTahun.val(tahunVal);

                            $('#edit_anggaran').val(data.anggaran);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data anggaran'
                        });
                    }
                });
            });

            // Update Anggaran
            $('#editAnggaranForm').on('submit', function(e) {
                e.preventDefault();

                const id = $('#edit_anggaran_id').val();

                // Clear previous validation states
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');

                // Bangun payload eksplisit dan pastikan anggaran integer bersih
                const payload = {
                    sub_pilar_id: $('#edit_sub_pilar_id').val(),
                    regional_id: $('#edit_regional_id').val(),
                    tahun: $('#edit_tahun').val(),
                    anggaran: $('#edit_anggaran').val().replace(/[^0-9]/g, '')
                };

                $.ajax({
                    url: `{{ url('anggaran/update') }}/${id}`,
                    method: 'PUT',
                    data: payload,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            $('#editAnggaranModal').modal('hide');
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil!',
                                text: response.message,
                                showConfirmButton: false,
                                timer: 1500
                            }).then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            $.each(errors, function(field, messages) {
                                const input = $(`#edit_${field}`);
                                input.addClass('is-invalid');
                                input.siblings('.invalid-feedback').text(messages[0]);
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Terjadi kesalahan saat memperbarui data'
                            });
                        }
                    }
                });
            });

            // Filter input anggaran menjadi angka murni (tanpa titik/desimal)
            $('#anggaran, #edit_anggaran').on('input', function() {
                let value = $(this).val();
                value = value.replace(/[^0-9]/g, ''); // Hapus semua selain digit
                $(this).val(value);
            });

            // Delete Button Click
            $('.delete-btn').on('click', function() {
                const id = $(this).data('id');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data anggaran akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `{{ url('anggaran/delete') }}/${id}`,
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                                    'content'),
                                'Content-Type': 'application/x-www-form-urlencoded'
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil!',
                                        text: response.message,
                                        showConfirmButton: false,
                                        timer: 1500
                                    }).then(() => {
                                        window.location.reload();
                                    });
                                }
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'Terjadi kesalahan saat menghapus data'
                                });
                            }
                        });
                    }
                });
            });

            // Clear form when modal is closed
            $('#createAnggaranModal').on('hidden.bs.modal', function() {
                $('#createAnggaranForm')[0].reset();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });

            $('#editAnggaranModal').on('hidden.bs.modal', function() {
                $('#editAnggaranForm')[0].reset();
                $('.form-control').removeClass('is-invalid');
                $('.invalid-feedback').text('');
            });

            // Format number input for anggaran
            $('#anggaran, #edit_anggaran').on('input', function() {
                let value = $(this).val();
                // Remove non-numeric characters except decimal point
                value = value.replace(/[^0-9.]/g, '');
                $(this).val(value);
            });

            // Validate year input
            $('#tahun, #edit_tahun').on('input', function() {
                let value = $(this).val();
                // Only allow numbers and limit to 4 characters
                value = value.replace(/[^0-9]/g, '').substring(0, 4);
                $(this).val(value);
            });
        });
    </script>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Edit Button Click
            $('.edit-btn').on('click', function() {
                const id = $(this).data('id');

                $.ajax({
                    url: `{{ url('anggaran/show') }}/${id}`,
                    method: 'GET',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.success) {
                            const data = response.data;
                            $('#edit_anggaran_id').val(data.id);
                            $('#edit_sub_pilar_id').val(data.sub_pilar_id);

                            // Pastikan dropdown tahun memiliki opsi tahun data yang mungkin di luar range
                            const $editTahun = $('#edit_tahun');
                            const tahunVal = String(data.tahun);
                            if ($editTahun.find(`option[value="${tahunVal}"]`).length === 0) {
                                $editTahun.append(
                                    `<option value="${tahunVal}">${tahunVal}</option>`);
                            }
                            $editTahun.val(tahunVal);

                            $('#edit_anggaran').val(data.anggaran);
                        }
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'Gagal mengambil data anggaran'
                        });
                    }
                });
            });
        });
    </script>
@endpush
