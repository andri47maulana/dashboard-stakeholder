<!-- Modal Edit Program TJSL -->
<div class="modal fade" id="editTjslModal" tabindex="-1" role="dialog" aria-labelledby="editTjslModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editTjslModalLabel">
                    <i class="fas fa-edit"></i> Edit Program TJSL
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="editTjslForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="editTjslTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="edit-program-tab" data-toggle="tab" href="#edit-program"
                                role="tab">
                                <i class="fas fa-info-circle"></i> Data Program
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-biaya-tab" data-toggle="tab" href="#edit-biaya"
                                role="tab">
                                <i class="fas fa-money-bill"></i> Biaya
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-publikasi-tab" data-toggle="tab" href="#edit-publikasi"
                                role="tab">
                                <i class="fas fa-newspaper"></i> Publikasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-dokumentasi-tab" data-toggle="tab" href="#edit-dokumentasi"
                                role="tab">
                                <i class="fas fa-file-alt"></i> Dokumentasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-feedback-tab" data-toggle="tab" href="#edit-feedback"
                                role="tab">
                                <i class="fas fa-comments"></i> Feedback
                            </a>
                        </li>
                    </ul>

                    <!-- Tab content -->
                    <div class="tab-content mt-3" id="editTjslTabContent">
                        <!-- Tab Data Program -->
                        <div class="tab-pane fade show active" id="edit-program" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="edit_nama_program" class="form-label">Nama Program <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="edit_nama_program"
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
                                        <label for="edit_unit_id" class="form-label">Unit/Kebun <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="edit_unit_id" name="unit_id" required>
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
                                        <label for="edit_pilar_id" class="form-label">Pilar <span
                                                class="text-danger">*</span></label>
                                        <select class="form-control" id="edit_pilar_id" name="pilar_id" required>
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
                                        <label for="edit_program_unggulan_id" class="form-label">Program
                                            Unggulan</label>
                                        <select class="form-control" id="edit_program_unggulan_id"
                                            name="program_unggulan_id">
                                            <option value="">Pilih Program Unggulan</option>
                                            @php
                                                $programUnggulans =
                                                    $programUnggulans ?? \App\Models\ProgramUnggulan::all();
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
                                        <label for="edit_sub_pilar" class="form-label">TPB</label>
                                        <select class="form-control" id="edit_sub_pilar" name="sub_pilar[]" multiple>
                                            @foreach ($subpilars as $subPilar)
                                                <option value="{{ $subPilar->id }}">{{ $subPilar->sub_pilar }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <small class="form-text text-muted">Pilih satu atau lebih sub pilar (gunakan
                                            Ctrl+Click untuk memilih multiple)</small>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit_deskripsi" class="form-label">Deskripsi Program</label>
                                <textarea class="form-control" id="edit_deskripsi" name="deskripsi" rows="3"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label class="form-label">Lokasi Program</label>
                                        <div class="border rounded p-3">
                                            <div class="row">
                                                <!-- Kolom kiri: Provinsi + Kabupaten/Kota -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label small">Provinsi</label>
                                                        <select id="edit_lokasi_provinsi" name="edit_lokasi_provinsi"
                                                            class="form-control"></select>
                                                        <small class="text-muted">Pilih provinsi</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small">Kabupaten/Kota</label>
                                                        <select id="edit_lokasi_kabupaten"
                                                            name="edit_lokasi_kabupaten" class="form-control"
                                                            disabled></select>
                                                        <small class="text-muted">Pilih kabupaten/kota berdasarkan
                                                            provinsi</small>
                                                    </div>
                                                </div>

                                                <!-- Kolom kanan: Kecamatan + Desa/Kelurahan -->
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label class="form-label small">Kecamatan</label>
                                                        <select id="edit_lokasi_kecamatan"
                                                            name="edit_lokasi_kecamatan" class="form-control"
                                                            disabled></select>
                                                        <small class="text-muted">Pilih kecamatan berdasarkan
                                                            kabupaten/kota</small>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label small">Desa/Kelurahan</label>
                                                        <select id="edit_lokasi_desa" name="edit_lokasi_desa"
                                                            class="form-control" disabled></select>
                                                        <small class="text-muted">Pilih desa/kelurahan berdasarkan
                                                            kecamatan</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Nilai gabungan nama lokasi untuk dikirim ke server -->
                                            <input type="hidden" id="edit_lokasi_program" name="lokasi_program">


                                            <!-- Section Peta Lokasi Program -->
                                            <div class="mb-3">

                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label for="edit_koordinat_display"
                                                            class="form-label">Koordinat (Latitude, Longitude)</label>
                                                        <input type="text" class="form-control"
                                                            id="edit_koordinat_display" placeholder="-6.2, 106.8">
                                                        <small class="text-muted">Format: latitude, longitude (contoh:
                                                            -6.2, 106.8)</small>
                                                    </div>
                                                    <!-- Hidden fields untuk latitude dan longitude terpisah -->
                                                    <input type="hidden" id="edit_latitude" name="latitude">
                                                    <input type="hidden" id="edit_longitude" name="longitude">
                                                </div>
                                                <div class="mt-2">
                                                    <div id="editMapContainer"
                                                        style="height: 400px; border: 1px solid #ddd; border-radius: 5px;">
                                                    </div>
                                                    <small class="form-text text-muted">Klik pada peta untuk memilih
                                                        lokasi program.
                                                        Gunakan layer control di pojok kanan atas untuk mengubah
                                                        tampilan peta.</small>
                                                </div>
                                                <!-- Hidden field untuk koordinat -->
                                                <input type="hidden" id="edit_koordinat" name="koordinat">
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Penerima Dampak dipindahkan tepat di bawah bingkai lokasi -->
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <div class="mb-3">
                                        <label for="edit_penerima_dampak" class="form-label">Penerima Dampak</label>
                                        <input type="text" class="form-control" id="edit_penerima_dampak"
                                            name="penerima_dampak">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tanggal_mulai" class="form-label">Tanggal Mulai</label>
                                        <input type="date" class="form-control" id="edit_tanggal_mulai"
                                            name="tanggal_mulai">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                        <input type="date" class="form-control" id="edit_tanggal_akhir"
                                            name="tanggal_akhir">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_status" class="form-label">Status</label>
                                        <select class="form-control" id="edit_status" name="status">
                                            <option value="1">Proposed</option>
                                            <option value="2">Active</option>
                                            <option value="3">Completed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="edit_tpb" class="form-label">TPB</label>
                                        <input type="text" class="form-control" id="edit_tpb" name="tpb">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Biaya -->
                        <div class="tab-pane fade" id="edit-biaya" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-money-bill text-success"></i> Data Biaya TJSL</h6>
                            </div>
                            <div id="editBiayaContainer">
                                <div class="biaya-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Sub Pilar/TPB</label>
                                            <select class="form-control biaya-sub-pilar" name="biaya[0][sub_pilar_id]" id="edit_biaya_sub_pilar">
                                                <option value="">Pilih Sub Pilar</option>
                                                @foreach ($subpilars as $subPilar)
                                                    <option value="{{ $subPilar->id }}">
                                                        {{ $subPilar->id }}.{{ $subPilar->sub_pilar }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Anggaran (Rp)</label>
                                            <input type="number" class="form-control" name="biaya[0][anggaran]" id="edit_biaya_anggaran"
                                                step="0.01" placeholder="0.00">
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Realisasi (Rp)</label>
                                            <input type="number" class="form-control" name="biaya[0][realisasi]" id="edit_biaya_realisasi"
                                                step="0.01" placeholder="0.00">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Publikasi -->
                        <div class="tab-pane fade" id="edit-publikasi" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-newspaper text-info"></i> Data Publikasi TJSL</h6>
                                <button type="button" class="btn btn-sm btn-info" id="editAddPublikasi">
                                    <i class="fas fa-plus"></i> Tambah Publikasi
                                </button>
                            </div>
                            <div id="editPublikasiContainer">
                                <div class="publikasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label class="form-label">Media</label>
                                            <input type="text" class="form-control" name="publikasi[0][media]" id="edit_publikasi_media"
                                                placeholder="Nama Media">
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">Link</label>
                                            <input type="url" class="form-control" name="publikasi[0][link]" id="edit_publikasi_link"
                                                placeholder="https://...">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="button" class="btn btn-danger btn-sm remove-edit-publikasi" disabled>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Dokumentasi -->
                        <div class="tab-pane fade" id="edit-dokumentasi" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-file-alt text-warning"></i> Data Dokumentasi TJSL</h6>
                            </div>
                            <div id="editDokumentasiContainer">
                                <!-- Proposal (PDF) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Proposal (PDF)</label>
                                            <input type="file" class="form-control" name="proposal" accept=".pdf">
                                            <small class="form-text text-muted">Format: PDF</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">File Saat Ini</label>
                                            <div class="current-file" id="current_proposal">
                                                <span class="text-muted">Tidak ada file</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Izin Prinsip (PDF) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Izin Prinsip (PDF)</label>
                                            <input type="file" class="form-control" name="izin_prinsip" accept=".pdf">
                                            <small class="form-text text-muted">Format: PDF</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">File Saat Ini</label>
                                            <div class="current-file" id="current_izin_prinsip">
                                                <span class="text-muted">Tidak ada file</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Survei Feedback (PDF) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Survei Feedback (PDF)</label>
                                            <input type="file" class="form-control" name="survei_feedback" accept=".pdf">
                                            <small class="form-text text-muted">Format: PDF</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">File Saat Ini</label>
                                            <div class="current-file" id="current_survei_feedback">
                                                <span class="text-muted">Tidak ada file</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Foto (JPG, PNG) -->
                                <div class="dokumentasi-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label">Foto (JPG, PNG)</label>
                                            <input type="file" class="form-control" name="foto" accept=".jpg,.jpeg,.png">
                                            <small class="form-text text-muted">Format: JPG, PNG</small>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">File Saat Ini</label>
                                            <div class="current-file" id="current_foto">
                                                <span class="text-muted">Tidak ada file</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Feedback -->
                        <div class="tab-pane fade" id="edit-feedback" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6><i class="fas fa-comments text-success"></i> Data Feedback TJSL</h6>
                            </div>
                            <div id="editFeedbackContainer">
                                <div class="feedback-item border p-3 mb-3 rounded bg-light">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="form-label">Tingkat Kepuasan</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="sangat_puas" id="edit_sangat_puas" value="1">
                                                <label class="form-check-label" for="edit_sangat_puas">
                                                    Sangat Puas
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="puas" id="edit_puas" value="1">
                                                <label class="form-check-label" for="edit_puas">
                                                    Puas
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="kurang_puas" id="edit_kurang_puas" value="1">
                                                <label class="form-check-label" for="edit_kurang_puas">
                                                    Kurang Puas
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-12">
                                            <label class="form-label">Saran</label>
                                            <textarea class="form-control" name="saran" rows="3" placeholder="Masukkan saran..."></textarea>
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
                    <button type="submit" class="btn btn-primary" id="editSubmitBtn">
                        <i class="fas fa-save"></i> Update Program TJSL
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>