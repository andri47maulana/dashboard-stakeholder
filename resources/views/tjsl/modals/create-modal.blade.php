<!-- Modal Input Data TJSL Komprehensif -->
<div class="modal fade" id="tjslModal" tabindex="-1" role="dialog" aria-labelledby="tjslModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tjslModalLabel">
                    <i class="fas fa-plus-circle"></i> Tambah Program TJSL Lengkap
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="tjslForm" action="{{ route('tjsl.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="tjslTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="program-tab" data-toggle="tab" href="#program"
                                role="tab">
                                <i class="fas fa-info-circle"></i> Data Program
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="biaya-tab" data-toggle="tab" href="#biaya" role="tab">
                                <i class="fas fa-money-bill"></i> Biaya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="publikasi-tab" data-toggle="tab" href="#publikasi"
                                role="tab">
                                <i class="fas fa-newspaper"></i> Publikasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="dokumentasi-tab" data-toggle="tab" href="#dokumentasi"
                                role="tab">
                                <i class="fas fa-file-alt"></i> Dokumentasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="feedback-tab" data-toggle="tab" href="#feedback"
                                role="tab">
                                <i class="fas fa-comments"></i> Feedback
                            </a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content mt-3" id="tjslTabContent">
                        <!-- Tab Data Program -->
                        <div class="tab-pane fade show active" id="program" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="nama_program" class="form-label">Nama Program <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="nama_program"
                                            name="nama_program" required>
                                        <div class="invalid-feedback">
                                            Nama program wajib diisi.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit_id" class="form-label">Unit/Kebun <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="unit_id" name="unit_id" required>
                                            <option value="">Pilih Unit</option>
                                            @foreach ($units as $unit)
                                                <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Unit/Kebun wajib dipilih.
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="pilar_id" class="form-label">Pilar <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="pilar_id" name="pilar_id" required>
                                            <option value="">Pilih Pilar</option>
                                            @foreach ($pilars as $pilar)
                                                <option value="{{ $pilar->id }}">{{ $pilar->pilar }}</option>
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Pilar wajib dipilih.
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="program_unggulan_id" class="form-label">Program Unggulan</label>
                                        <select class="form-control" id="program_unggulan_id"
                                            name="program_unggulan_id">
                                            <option value="">Pilih Program Unggulan</option>
                                            @php
                                                // Jika controller belum mengirimkan $programUnggulans, ambil di sini
                                                $programUnggulans = \App\Models\ProgramUnggulan::all();
                                            @endphp
                                            @foreach ($programUnggulans as $pu)
                                                <option value="{{ $pu->id }}"
                                                    data-subpilars='@json($pu->sub_pilar)'>
                                                    {{ $pu->program_unggulan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sub_pilar" class="form-label">TPB</label>
                                        <select class="form-control" id="sub_pilar" name="sub_pilar[]" multiple>
                                            @foreach ($subpilars as $subPilar)
                                                <option value="{{ $subPilar->id }}">
                                                    {{ $subPilar->id }}.{{ $subPilar->sub_pilar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Pilih satu atau lebih sub pilar</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi Program</label>
                                <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <label class="form-label small d-block mb-2">Lokasi Program</label>
                                    <div class="border rounded p-3">
                                        <div class="row">
                                            <!-- Kolom kiri: Provinsi + Kabupaten/Kota -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label small">Provinsi</label>
                                                    <select id="lokasi_provinsi" name="lokasi_provinsi"
                                                        class="form-control"></select>
                                                    <small class="text-muted">Pilih provinsi</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small">Kabupaten/Kota</label>
                                                    <select id="lokasi_kabupaten" name="lokasi_kabupaten"
                                                        class="form-control" disabled></select>
                                                    <small class="text-muted">Pilih kabupaten/kota berdasarkan
                                                        provinsi</small>
                                                </div>
                                            </div>

                                            <!-- Kolom kanan: Kecamatan + Desa/Kelurahan -->
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label small">Kecamatan</label>
                                                    <select id="lokasi_kecamatan" name="lokasi_kecamatan"
                                                        class="form-control" disabled></select>
                                                    <small class="text-muted">Pilih kecamatan berdasarkan
                                                        kabupaten/kota</small>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label small">Desa/Kelurahan</label>
                                                    <select id="lokasi_desa" name="lokasi_desa" class="form-control"
                                                        disabled></select>
                                                    <small class="text-muted">Pilih desa/kelurahan berdasarkan
                                                        kecamatan</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Nilai gabungan nama lokasi untuk dikirim ke server -->
                                        <input type="hidden" id="lokasi_program" name="lokasi_program">

                                        <!-- Input Koordinat -->
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div class="mb-2">
                                                    <label class="form-label small" for="koordinat">Koordinat
                                                        (Latitude, Longitude)</label>
                                                    <input type="text" class="form-control" id="koordinat"
                                                        name="koordinat" placeholder="-6.2, 106.8">
                                                    <small class="text-muted">Format: latitude, longitude (contoh:
                                                        -6.2, 106.8)</small>
                                                </div>
                                            </div>
                                            <!-- Hidden fields untuk latitude dan longitude terpisah -->
                                            <input type="hidden" id="latitude" name="latitude">
                                            <input type="hidden" id="longitude" name="longitude">
                                        </div>

                                        <!-- Peta Klik untuk Koordinat -->
                                        <div class="mt-2">
                                            <div id="lokasiMap"
                                                style="height: 300px; border: 1px solid #ddd; border-radius: 6px;">
                                            </div>
                                        </div>

                                        <!-- Gabungan koordinat untuk backend (format: lat,lng) -->
                                        <input type="hidden" id="koordinat" name="koordinat">
                                    </div>
                                </div>
                            </div>

                            <!-- Penerima Dampak dipindahkan tepat di bawah bingkai lokasi -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="penerima_dampak" class="form-label">Penerima Dampak</label>
                                        <input type="text" class="form-control" id="penerima_dampak"
                                            name="penerima_dampak">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="tanggal_mulai"
                                            name="tanggal_mulai">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="tanggal_akhir"
                                            name="tanggal_akhir">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="1">Proposed</option>
                                            <option value="2">Active</option>
                                            <option value="3">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="tpb" class="form-label">TPB</label>
                                        <input type="text" class="form-control" id="tpb" name="tpb"
                                            placeholder="Contoh: 1,2,3">
                                    </div>
                                </div> --}}
                            </div>
                        </div>

                        <!-- Tab Biaya TJSL -->
                        <div class="tab-pane fade" id="biaya" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-money-bill text-success"></i> Data Biaya TJSL</h6>
                            </div>
                            <div id="biayaContainer">
                                <div class="biaya-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Sub Pilar/TPB</label>
                                            <select class="form-control biaya-sub-pilar"
                                                name="biaya[0][sub_pilar_id]">
                                                <option value="">Pilih Sub Pilar</option>
                                                @foreach ($subpilars as $subPilar)
                                                    <option value="{{ $subPilar->id }}">
                                                        {{ $subPilar->id }}.{{ $subPilar->sub_pilar }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Anggaran (Rp)</label>
                                            <input type="number" class="form-control" name="biaya[0][anggaran]"
                                                step="0.01" placeholder="0.00">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Realisasi (Rp)</label>
                                            <input type="number" class="form-control" name="biaya[0][realisasi]"
                                                step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Publikasi -->
                        <div class="tab-pane fade" id="publikasi" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-newspaper text-info"></i> Data Publikasi TJSL</h6>
                                <button type="button" class="btn btn-sm btn-info" id="addPublikasi">
                                    <i class="fas fa-plus"></i> Tambah Publikasi
                                </button>
                            </div>
                            <div id="publikasiContainer">
                                <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Media</label>
                                            <input type="text" class="form-control" name="publikasi[0][media]"
                                                placeholder="Nama Media">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Link</label>
                                            <input type="url" class="form-control" name="publikasi[0][link]"
                                                placeholder="https://...">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-publikasi"
                                                disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Dokumentasi -->
                        <div class="tab-pane fade" id="dokumentasi" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-file-alt text-warning"></i> Data Dokumentasi TJSL</h6>

                            </div>
                            <div id="dokumentasiContainer">
                                <!-- Proposal (PDF) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Proposal (PDF)</label>
                                            <input type="file" class="form-control" name="proposal"
                                                accept=".pdf">
                                            <small class="form-text text-muted">Format: PDF</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Izin Prinsip (PDF) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Izin Prinsip (PDF)</label>
                                            <input type="file" class="form-control" name="izin_prinsip"
                                                accept=".pdf">
                                            <small class="form-text text-muted">Format: PDF</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Survei Feedback (PDF) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Survei Feedback (PDF)</label>
                                            <input type="file" class="form-control" name="survei_feedback"
                                                accept=".pdf">
                                            <small class="form-text text-muted">Format: PDF</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Foto (JPG, PNG) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Foto (JPG, PNG)</label>
                                            <input type="file" class="form-control" name="foto"
                                                accept=".jpg,.jpeg,.png">
                                            <small class="form-text text-muted">Format: JPG, PNG</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Feedback -->
                        <div class="tab-pane fade" id="feedback" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-comments text-primary"></i> Data Feedback TJSL</h6>
                            </div>
                            <div id="feedbackContainer">
                                <div class="feedback-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="feedback[0][sangat_puas]" id="sangat_puas_0"
                                                    value="1">
                                                <label class="form-check-label" for="sangat_puas_0">
                                                    Sangat Puas
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="feedback[0][puas]" id="puas_0" value="1">
                                                <label class="form-check-label" for="puas_0">
                                                    Puas
                                                </label>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox"
                                                    name="feedback[0][kurang_puas]" id="kurang_puas_0"
                                                    value="1">
                                                <label class="form-check-label" for="kurang_puas_0">
                                                    Kurang Puas
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-md-12">
                                            <label class="form-label">Saran</label>
                                            <textarea class="form-control" name="feedback[0][saran]" rows="2" placeholder="Masukkan saran..."></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        <i class="fas fa-save"></i> Simpan Program
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Function to compose lokasi_program from selected wilayah
    function composeLokasiProgram() {
        const provKode = $('#lokasi_provinsi').val() || '';
        const kabKode = $('#lokasi_kabupaten').val() || '';
        const kecKode = $('#lokasi_kecamatan').val() || '';
        const desaKode = $('#lokasi_desa').val() || '';

        // Set lokasi_program based on the most specific location selected
        if (desaKode) {
            $('#lokasi_program').val(desaKode); // Use desa code (most specific)
        } else if (kecKode) {
            $('#lokasi_program').val(kecKode); // Use kecamatan code
        } else if (kabKode) {
            $('#lokasi_program').val(kabKode); // Use kabupaten code
        } else if (provKode) {
            $('#lokasi_program').val(provKode); // Use provinsi code
        } else {
            $('#lokasi_program').val('');
        }
    }

    // Bind change events to all location selectors
    $('#lokasi_provinsi, #lokasi_kabupaten, #lokasi_kecamatan, #lokasi_desa').on('change', function() {
        composeLokasiProgram();
    });
});
</script>