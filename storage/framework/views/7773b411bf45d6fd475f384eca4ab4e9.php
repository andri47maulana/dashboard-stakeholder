

<?php $__env->startSection('content'); ?>
<style>
  /* Warna di dropdown */
  select.priority-select option[value="Null"] { background-color: #6d6d6d; color: #ffffff; } /* Abu */
  select.priority-select option[value="P1"] { background-color: #dc3545; color: #fff; } /* Merah */
  select.priority-select option[value="P2"] { background-color: #fd7e14; color: #fff; } /* Oranye */
  select.priority-select option[value="P3"] { background-color: #ffff00; color: #000; } /* Kuning */
  select.priority-select option[value="P4"] { background-color: #28a745; color: #fff; } /* Hijau */

  /* Warna setelah dipilih */
  .bg-P1 { background-color: #dc3545 !important; color: #fff !important; }
  .bg-P2 { background-color: #fd7e14 !important; color: #fff !important; }
  .bg-P3 { background-color: #ffff00 !important; color: #000 !important; }
  .bg-P4 { background-color: #28a745 !important; color: #fff !important; }
  .bg-Null { background-color: #6d6d6d !important; color: #fff !important; }
</style>
<div class="toast-container position-fixed top-0 end-0 p-2" style="z-index: 2000; right: 0; top: 0;"></div>


<h4>Data Derajat Hubungan</h4>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                
    
    <!-- Import Excel -->
    

    <!-- Tombol tambah -->
    <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAdd">Tambah Data</button>
    <div class="table-responsive">
    <!-- Tabel -->
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>No</th>
                <th>Unit</th>
                <?php if(Auth::user()->region == 'PTPN I HO'): ?>
                    <th>Region</th>
                <?php endif; ?>
                
                <th>Tahun</th>
                <th>Prioritas Socmap</th>
                <th>Derajat Kepuasan</th>
                <th>Derajat Hubungan</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr>
                <td><?php echo e($loop->iteration); ?></td>
                <td><?php echo e($row->unitx->unit); ?></td>
                 <?php if(Auth::user()->region == 'PTPN I HO'): ?>
                <td><?php echo e($row->unitx->region); ?></td>
                <?php endif; ?>
                <td><?php echo e($row->tahun); ?></td>
                <td><?php echo e($row->prioritas_socmap); ?></td>
                <td><?php echo e($row->derajat_kepuasan); ?></td>
                <td><?php echo e($row->derajat_hubungan); ?></td>
                <td>
                        
                        <button class="btn btn-sm btn-warning editBtn"
                            data-id="<?php echo e($row->id); ?>"
                            data-unit="<?php echo e($row->id_unit); ?>"
                            data-tahun="<?php echo e($row->tahun); ?>"
                            data-lingkungan="<?php echo e($row->lingkungan); ?>"
                            data-ekonomi="<?php echo e($row->ekonomi); ?>"
                            data-pendidikan="<?php echo e($row->pendidikan); ?>"
                            data-sosial="<?php echo e($row->sosial_kesesjahteraan); ?>"
                            data-okupasi="<?php echo e($row->okupasi); ?>"
                            data-kepuasan="<?php echo e($row->kepuasan); ?>"
                            data-kontribusi="<?php echo e($row->kontribusi); ?>"
                            data-komunikasi="<?php echo e($row->komunikasi); ?>"
                            data-kepercayaan="<?php echo e($row->kepercayaan); ?>"
                            data-keterlibatan="<?php echo e($row->keterlibatan); ?>"
                            data-skor="<?php echo e($row->skor_socmap); ?>"
                            data-prioritas="<?php echo e($row->prioritas_socmap); ?>"
                            data-indeks="<?php echo e($row->indeks_kepuasan); ?>"
                            data-derajatkepuasan="<?php echo e($row->derajat_kepuasan); ?>"
                            data-derajathubungan="<?php echo e($row->derajat_hubungan); ?>"
                            data-deskripsi="<?php echo e($row->deskripsi); ?>"
                            data-narasi="<?php echo e($row->narasi); ?>"
                            data-toggle="modal"
                            data-target="#modalEdit">
                            Edit
                        </button>

                        <button class="btn btn-info btn-sm detailBtn" 
                            data-id="<?php echo e($row->id); ?>"
                            data-unit="<?php echo e($row->unitx->unit); ?>"
                            data-region="<?php echo e($row->unitx->region); ?>"
                            data-tahun="<?php echo e($row->tahun); ?>"
                            data-prioritas="<?php echo e($row->prioritas_socmap); ?>"
                            data-indeks="<?php echo e($row->indeks_kepuasan); ?>"
                            data-derajat="<?php echo e($row->derajat_hubungan); ?>"
                            data-kepuasan="<?php echo e($row->kepuasan); ?>"
                            data-kontribusi="<?php echo e($row->kontribusi); ?>"
                            data-komunikasi="<?php echo e($row->komunikasi); ?>"
                            data-kepercayaan="<?php echo e($row->kepercayaan); ?>"
                            data-keterlibatan="<?php echo e($row->keterlibatan); ?>"
                            data-lingkungan="<?php echo e($row->lingkungan); ?>"
                            data-ekonomi="<?php echo e($row->ekonomi); ?>"
                            data-pendidikan="<?php echo e($row->pendidikan); ?>"
                            data-sosial="<?php echo e($row->sosial_kesesjahteraan); ?>"
                            data-okupasi="<?php echo e($row->okupasi); ?>"
                            data-socmap="<?php echo e($row->skor_socmap); ?>"
                            data-deskripsi="<?php echo e($row->deskripsi); ?>"
                            data-toggle="modal" 
                            data-target="#modalDetail">
                            Detail
                        </button>

                        
                        <?php if(
    $row->isu_detail_count > 0 ||
    $row->isu_desa_count > 0 ||
    $row->isu_instansi_count > 0 ||
    $row->isu_okupasi_count > 0
): ?>
    <!-- Kalau ada data isu -->
    <button class="btn btn-sm btn-warning editIsuBtn"
        data-id="<?php echo e($row->id); ?>"
        data-idunit="<?php echo e($row->id_unit); ?>"
        data-tahun="<?php echo e($row->tahun); ?>"
        data-unit="<?php echo e($row->unitx->unit); ?>"
        data-region="<?php echo e($row->unitx->region); ?>"
        data-toggle="modal"
        data-target="#modalEditIsu">
        Edit Isu
    </button>

    <button class="btn btn-sm btn-info detailIsuBtn"
        data-id="<?php echo e($row->id); ?>"
        data-idunit="<?php echo e($row->id_unit); ?>"
        data-tahun="<?php echo e($row->tahun); ?>"
        data-unit="<?php echo e($row->unitx->unit); ?>"
        data-region="<?php echo e($row->unitx->region); ?>"
        data-toggle="modal"
        data-target="#modalDetailIsu">
        Detail Isu
    </button>
<?php else: ?>
    <!-- Kalau belum ada data isu -->
    <button class="btn btn-sm btn-success isuBtn"
        data-id="<?php echo e($row->id); ?>"
        data-idunit="<?php echo e($row->id_unit); ?>"
        data-tahun="<?php echo e($row->tahun); ?>"
        data-unit="<?php echo e($row->unitx->unit); ?>"
        data-region="<?php echo e($row->unitx->region); ?>"
        data-toggle="modal"
        data-target="#modalIsu">
        Input Isu
    </button>
<?php endif; ?>

                    <button class="btn btn-danger btn-sm deleteBtn" data-id="<?php echo e($row->id); ?>">Delete</button>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </tbody>
    </table>
    
</div>
            

<!-- Modal Detail -->
<div class="modal fade" id="modalDetail" tabindex="-1">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header bg-info text-white">
        <h5 class="modal-title">Detail Analisis Hubungan Stakeholder</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body" id="detailContent">
        <!-- Isi detail akan di-render lewat JS -->
      </div>
    </div>
  </div>
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalAdd">
  <div class="modal-dialog modal-xl">
    <form id="formAdd" class="modal-content">
      <?php echo csrf_field(); ?>
      <div class="modal-header">
        <h5 class="modal-title">Tambah Data Derajat Hubungan</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>

      <div class="modal-body">
        <div class="row g-2">
          <!-- Baris 1 -->
          <div class="col-md-6">
            <label>Unit</label>
            <select name="id_unit" id="id_unit" class="form-control select2" required>
              <option value="">Pilih Unit</option>
              <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($u->id); ?>"><?php echo e($u->unit); ?> - <?php echo e($u->region); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Tahun</label>
            <input type="number" name="tahun" class="form-control" required>
          </div>
        </div>

        <!-- Baris 2: Lingkungan - Okupasi -->
        <div class="row g-2 mt-2">
          <div class="col-md-2">
            <label>Lingkungan</label>
            <input type="number" id="lingkungan" name="lingkungan" class="form-control" oninput="hitungSocmap()">
          </div>
          <div class="col-md-2">
            <label>Ekonomi</label>
            <input type="number" id="ekonomi" name="ekonomi" class="form-control" oninput="hitungSocmap()">
          </div>
          <div class="col-md-2">
            <label>Pendidikan</label>
            <input type="number" id="pendidikan" name="pendidikan" class="form-control" oninput="hitungSocmap()">
          </div>
          <div class="col-md-3">
            <label>Sosial Kesejahteraan</label>
            <input type="number" id="sosial_kesesjahteraan" name="sosial_kesesjahteraan" class="form-control" oninput="hitungSocmap()">
          </div>
          <div class="col-md-3">
            <label>Okupasi</label>
            <input type="number" id="okupasi" name="okupasi" class="form-control" oninput="hitungSocmap()">
          </div>
        </div>

        <!-- Baris 3: Kepuasan - Keterlibatan -->
        <div class="row g-2 mt-2">
          <div class="col-md-2">
            <label>Kepuasan</label>
            <input type="number" name="kepuasan" class="form-control">
          </div>
          <div class="col-md-2">
            <label>Kontribusi</label>
            <input type="number" name="kontribusi" class="form-control">
          </div>
          <div class="col-md-2">
            <label>Komunikasi</label>
            <input type="number" name="komunikasi" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Kepercayaan</label>
            <input type="number" name="kepercayaan" class="form-control">
          </div>
          <div class="col-md-3">
            <label>Keterlibatan</label>
            <input type="number" name="keterlibatan" class="form-control">
          </div>
        </div>

        <!-- Baris 4: Scor, Prioritas, Indeks, Derajat -->
        <div class="row g-2 mt-2">
          <div class="col-md-3">
            <label>Scor Socmap</label>
            <input type="text" id="skor_socmap" name="skor_socmap" class="form-control" readonly>
          </div>
          <div class="col-md-3">
            <label>Prioritas SOCMAP</label>
            <select name="prioritas_socmap" id="prioritas_socmap" class="form-control priority-select">
                <option value="">Pilih Prioritas</option>
                <option value="Null">Null</option>
                <option value="P1">P1</option>
                <option value="P2">P2</option>
                <option value="P3">P3</option>
                <option value="P4">P4</option>
            </select>
            </div>
          <div class="col-md-3">
            <label>Indeks Kepuasan</label>
            <input type="text" name="indeks_kepuasan" id="indeks_kepuasan" class="form-control" >
          </div>
          <div class="col-md-3">
            <label>Derajat Kepuasan</label>
            <select name="derajat_kepuasan" id="derajat_kepuasan" class="form-control priority-select">
                <option value="">Pilih Derajat</option>
                <option value="Null">Null</option>
                <option value="P1">P1</option>
                <option value="P2">P2</option>
                <option value="P3">P3</option>
                <option value="P4">P4</option>
            </select>
            </div>
          <div class="col-md-12">
            <label>Derajat Hubungan</label>
            <select name="derajat_hubungan" id="derajat_hubungan" class="form-control priority-select">
                <option value="">Pilih Derajat</option>
                <option value="Null">Null</option>
                <option value="P1">P1</option>
                <option value="P2">P2</option>
                <option value="P3">P3</option>
                <option value="P4">P4</option>
            </select>
            </div>
        </div>
        
        <!-- Baris 5: Deskripsi -->
        <div class="row g-2 mt-2">
          <div class="col-md-12">
            <label>Deskripsi</label>
            <textarea name="deskripsi1" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="row g-2 mt-2">
          <div class="col-md-12"> 
            <label>Narasi</label>
            <textarea name="deskripsi" id="deskripsi" class="form-control" rows="6" readonly></textarea>
            </div>
        </div>
        <!-- hidden field -->
        <input type="hidden" name="input_date" value="<?php echo e(now()); ?>">
        <input type="hidden" name="modified_date" value="<?php echo e(now()); ?>">
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>



<!-- Modal Edit -->
<div class="modal fade" id="modalEdit">
  <div class="modal-dialog modal-xl">
    <form id="formEdit" class="modal-content">
      <?php echo csrf_field(); ?>
      <input type="hidden" name="id" id="edit_id">
      <div class="modal-header">
        <h5 class="modal-title">Edit Data Derajat Hubungan</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>

      <div class="modal-body">
        <div class="row g-2">
          <!-- Baris 1 -->
          <div class="col-md-6">
            <label>Unit</label>
            <select name="id_unit" id="edit_unit" class="form-control select2" required disabled>
              <option value="">Pilih Unit</option>
              <?php $__currentLoopData = $units; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $u): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($u->id); ?>"><?php echo e($u->unit); ?> - <?php echo e($u->region); ?></option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
          </div>
          <div class="col-md-6">
            <label>Tahun</label>
            <input type="number" name="tahun" id="edit_tahun" class="form-control" required readonly>
          </div>
        </div>

        <!-- Baris 2 -->
        <div class="row g-2 mt-2">
          <div class="col-md-2"><label>Lingkungan</label><input type="number" id="edit_lingkungan" name="lingkungan" class="form-control" oninput="hitungSocmap()"></div>
          <div class="col-md-2"><label>Ekonomi</label><input type="number" id="edit_ekonomi" name="ekonomi" class="form-control" oninput="hitungSocmap()"></div>
          <div class="col-md-2"><label>Pendidikan</label><input type="number" id="edit_pendidikan" name="pendidikan" class="form-control" oninput="hitungSocmap()"></div>
          <div class="col-md-3"><label>Sosial Kesejahteraan</label><input type="number" id="edit_sosial" name="sosial_kesesjahteraan" class="form-control" oninput="hitungSocmap()"></div>
          <div class="col-md-3"><label>Okupasi</label><input type="number" id="edit_okupasi" name="okupasi" class="form-control" oninput="hitungSocmap()"></div>
        </div>

        <!-- Baris 3 -->
        <div class="row g-2 mt-2">
          <div class="col-md-2"><label>Kepuasan</label><input type="number" id="edit_kepuasan" name="kepuasan" class="form-control"></div>
          <div class="col-md-2"><label>Kontribusi</label><input type="number" id="edit_kontribusi" name="kontribusi" class="form-control"></div>
          <div class="col-md-2"><label>Komunikasi</label><input type="number" id="edit_komunikasi" name="komunikasi" class="form-control"></div>
          <div class="col-md-3"><label>Kepercayaan</label><input type="number" id="edit_kepercayaan" name="kepercayaan" class="form-control"></div>
          <div class="col-md-3"><label>Keterlibatan</label><input type="number" id="edit_keterlibatan" name="keterlibatan" class="form-control"></div>
        </div>

        <!-- Baris 4 -->
        <div class="row g-2 mt-2">
          <div class="col-md-3"><label>Scor Socmap</label><input type="text" id="edit_skor_socmap" name="skor_socmap" class="form-control" readonly></div>
          <div class="col-md-3">
            <label>Prioritas SOCMAP</label>
            <select name="prioritas_socmap" id="edit_prioritas" class="form-control priority-select">
              <option value="">Pilih Prioritas</option>
              <option value="Null">Null</option>
              <option value="P1">P1</option>
              <option value="P2">P2</option>
              <option value="P3">P3</option>
              <option value="P4">P4</option>
            </select>
          </div>
          <div class="col-md-3"><label>Indeks Kepuasan</label><input type="text" id="edit_indeks_kepuasan" name="indeks_kepuasan" class="form-control" ></div>
          <div class="col-md-3">
            <label>Derajat Kepuasan</label>
            <select name="derajat_kepuasan" id="edit_derajat_kepuasan" class="form-control priority-select">
              <option value="">Pilih Derajat</option>
              <option value="Null">Null</option>
              <option value="P1">P1</option>
              <option value="P2">P2</option>
              <option value="P3">P3</option>
              <option value="P4">P4</option>
            </select>
          </div>
          <div class="col-md-12 mt-2">
            <label>Derajat Hubungan</label>
            <select name="derajat_hubungan" id="edit_derajat_hubungan" class="form-control priority-select">
              <option value="">Pilih Derajat</option>
              <option value="Null">Null</option>
              <option value="P1">P1</option>
              <option value="P2">P2</option>
              <option value="P3">P3</option>
              <option value="P4">P4</option>
            </select>
          </div>
        </div>

        <!-- Baris 5 -->
        <div class="row g-2 mt-2">
          <div class="col-md-12">
            <label>Deskripsi</label>
            <textarea name="deskripsi1" id="edit_deskripsi1" class="form-control" rows="4"></textarea>
          </div>
        </div>
        <div class="row g-2 mt-2">
          <div class="col-md-12">
            <label>Narasi</label>
            <textarea name="deskripsi" id="edit_deskripsi" class="form-control" rows="6" readonly></textarea>
          </div>
        </div>
      </div>

      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Update</button>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
      </div>
    </form>
  </div>
</div>




<!-- Modal Input Isu -->
<div class="modal fade" id="modalIsu" tabindex="-1" aria-labelledby="modalIsuLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formIsu" action="<?php echo e(route('isu.store')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalIsuLabel">Input Isu & Desa</h5>
          <button type="button" class="btn-close" data-dismiss="modal">X</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="derajat_id" id="derajat_id">
          <!-- ISU -->
          <div class="card mb-3 shadow-sm">
              <div class="card-header bg-light fw-bold">📌 Isu</div>
              <div class="card-body">
                  <div id="isu-container">
                      <div class="row g-2 isu-row mb-2">
                          <div class="col-md-3">
                              <label class="form-label">Isu</label>
                              <select name="isu[]" class="form-control" required>
                                  <option value="">Pilih Isu</option>
                                  <option value="Ekonomi">Ekonomi</option>
                                  <option value="Sosial">Sosial & Kesejahteraan</option>
                                  <option value="Lingkungan">Lingkungan</option>
                                  <option value="Pendidikan">Pendidikan</option>
                                  <option value="Hubungan Stakeholder">Hubungan Stakeholder</option>
                              </select>
                          </div>
                          <div class="col-md-8">
                              <label class="form-label">Keterangan</label>
                              <textarea name="keterangan[]" class="form-control" required rows="2"></textarea>
                          </div>
                          <div class="col-md-1 d-flex align-items-end">
                              <button type="button" class="btn btn-danger btn-remove">-</button>
                          </div>
                      </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-add">+ Tambah Isu</button>
              </div>
          </div>

          <!-- DESA -->
          <div class="card mb-3 shadow-sm">
              <div class="card-header bg-light fw-bold">🏠 Desa</div>
              <div class="card-body">
                  <div id="desa-container">
                      <div class="row g-2 desa-row mb-2">
                          <div class="col-md-8">
                              <label class="form-label">Desa</label>
                              <select name="desa[]" class="form-control select2-desa" style="width:100%" required></select>
                          </div>
                          <div class="col-md-3">
                              <label class="form-label">Isu</label>
                              <select name="isu_utama[]" class="form-control" required>
                                  <option value="">Pilih Isu</option>
                                  <option value="Ekonomi">Ekonomi</option>
                                  <option value="Sosial">Sosial & Kesejahteraan</option>
                                  <option value="Lingkungan">Lingkungan</option>
                                  <option value="Pendidikan">Pendidikan</option>
                                  <option value="Hubungan Stakeholder">Hubungan Stakeholder</option>
                              </select>
                          </div>
                          <div class="col-md-1 d-flex align-items-end">
                              <button type="button" class="btn btn-danger btn-remove-desa">-</button>
                          </div>
                      </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-add-desa">+ Tambah Desa</button>
              </div>
          </div>

          <!-- INSTANSI -->
          <div class="card mb-3 shadow-sm">
              <div class="card-header bg-light fw-bold">🏢 Instansi</div>
              <div class="card-body">
                  <div id="instansi-container">
                      <div class="row g-2 instansi-row mb-2">
                          <div class="col-md-5">
                              <label class="form-label">Instansi</label>
                              <select name="instansi[]" id="instansiisu" class="form-control select2" required>
                                  <option value="">Pilih Instansi</option>
                                  <?php $__currentLoopData = $stake; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stakeholder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <option value="<?php echo e($stakeholder->id); ?>"><?php echo e($stakeholder->nama_instansi); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                          </div>
                          <div class="col-md-6">
                              <label class="form-label">Program</label>
                              <textarea name="program[]" class="form-control" required rows="2"></textarea>
                          </div>
                          <div class="col-md-1 d-flex align-items-end">
                              <button type="button" class="btn btn-danger btn-remove-instansi">-</button>
                          </div>
                      </div>
                  </div>
                  <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-add-instansi">+ Tambah Instansi</button>
              </div>
          </div>

          <!-- OKUPASI -->
          <div class="card mb-3 shadow-sm">
              <div class="card-header bg-light fw-bold">👥 Okupasi</div>
              <div class="card-body">
                  <div class="row g-2 okupasi-row mb-2">
                      <div class="col-md-3">
                          <label class="form-label">Okupasi</label>
                          <select name="okupasi" class="form-control" required>
                              <option value="">Pilih Okupasi</option>
                              <option value="Rendah">Rendah</option>
                              <option value="Sedang">Sedang</option>
                              <option value="Tinggi">Tinggi</option>
                          </select>
                      </div>
                      <div class="col-md-8">
                          <label class="form-label">Keterangan</label>
                          <textarea name="keterangan_okupasi" class="form-control" required rows="2"></textarea>
                      </div>
                  </div>
              </div>
          </div>

      </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal Edit Isu -->
<div class="modal fade" id="modalEditIsu" tabindex="-1" aria-labelledby="modalEditIsuLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <form id="formEditIsu" action="<?php echo e(route('isu.update')); ?>" method="POST">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <input type="hidden" name="derajat_id" id="edit_derajat_id">

        <div class="modal-header">
          <h5 class="modal-title" id="modalEditIsuLabel">Edit Isu & Desa</h5>
          <button type="button" class="btn-close" data-dismiss="modal">X</button>
        </div>

        <div class="modal-body">
          <!-- ISU -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light fw-bold">📌 Isu</div>
            <div class="card-body" id="edit-isu-container">
              <!-- data isu akan di-load via JS -->
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-add-edit-isu">+ Tambah Isu</button>
          </div>

          <!-- DESA -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light fw-bold">🏠 Desa</div>
            <div class="card-body" id="edit-desa-container">
              <!-- data desa akan di-load via JS -->
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-add-edit-desa">+ Tambah Desa</button>
          </div>

          <!-- INSTANSI -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light fw-bold">🏢 Instansi</div>
            <div class="card-body" id="edit-instansi-container">
              <!-- data instansi akan di-load via JS -->
            </div>
            <button type="button" class="btn btn-sm btn-primary mt-2" id="btn-add-edit-instansi">+ Tambah Instansi</button>
          </div>

          <!-- OKUPASI -->
          <div class="card mb-3 shadow-sm">
            <div class="card-header bg-light fw-bold">👥 Okupasi</div>
            <div class="card-body" id="edit-okupasi-container">
              <!-- data okupasi akan di-load via JS -->
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Update</button>
        </div>
      </form>
    </div>
  </div>
</div>




<!-- Modal Detail Derajat Hubungan -->
<div class="modal fade" id="derajatDetailModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="derajatDetailModalLabel">Detail Isu</h5>
        <button type="button" class="btn-close" data-dismiss="modal">X</button>
      </div>
      <div class="modal-body">
        <div class="row">
          <!-- MAP -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div id="mapContainerAll" style="height:300px; width:100%"></div>
            </div>
          </div>

          <!-- Deskripsi & Isu -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Deskripsi</div>
              <div class="card-body" style="font-size:0.9em; height:250px; display:flex; flex-direction:column; overflow-y:auto;">
                <p style="text-align:justify; margin-top:8px;" id="deskripsi_isu"></p>
                <table class="table table-bordered table-sm" id="isuCardTable">
                  <thead>
                    <tr>
                      <th>Isu</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Data akan diisi JS -->
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Desa Sasaran Utama -->
          <div class="col-md-3">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Desa Sasaran Utama</div>
              <div class="card-body" style="font-size:0.9em; height:150px; display:flex; flex-direction:column; overflow-y:auto;">
                <table class="table table-bordered table-sm" id="sasarandesa">
                  <thead>
                    <tr>
                      <th>Desa</th>
                      <th>Isu Utama</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Prioritas Lembaga/Instansi -->
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Prioritas Lembaga/Instansi</div>
              <div class="card-body" style="font-size:0.9em; height:150px; display:flex; flex-direction:column; overflow-y:auto;">
                <table class="table table-bordered table-sm" id="isuLembagaTable">
                  <thead>
                    <tr>
                      <th>Lembaga/Instansi</th>
                      <th>Program</th>
                    </tr>
                  </thead>
                  <tbody></tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Okupasi -->
          <div class="col-md-3">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Okupasi</div>
              <div class="card-body flex-grow-1 overflow-auto" id="okupasiCardBody" style="font-size:0.9em; height:150px; display:flex; flex-direction:column; overflow-y:auto;">
                <!-- Data akan diisi JS -->
              </div>
            </div>
          </div>

        </div>
      </div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.vectorgrid/dist/Leaflet.VectorGrid.bundled.js"></script>
<script>
  let mapAll = null;
$(document).ready(function(){
  // let mapAll = null;
    // Event klik tombol Detail Isu
    $('.detailIsuBtn').on('click', function() {
        let derajatId = $(this).data('id');
        let unitName = $(this).data('unit');
        let tahun = $(this).data('tahun');
        let region = $(this).data('region');
        // let mapAll = null;
        // Kosongkan konten modal sebelum isi
        $('#deskripsi_isu').text('');
        $('#isuCardTable tbody').empty();
        $('#sasarandesa tbody').empty();
        $('#isuLembagaTable tbody').empty();
        $('#okupasiCardBody').empty();

        // Bisa diisi header modal, misal nama unit/tahun
        // $('#unitName').text(unitName);
        // $('#unitYear').text(tahun);

        // AJAX ambil data
        $.ajax({
            url: '/isu/show/' + derajatId,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                // 1️⃣ Deskripsi dari derajat (jika ada)
                // derajatDetailModalLabel
                $('#derajatDetailModalLabel').text(unitName+' Tahun '+tahun);
                // console.log(data.derajatHubungan.deskripsi);
                if(data.derajatHubungan){
                    $('#deskripsi_isu').text(data.derajatHubungan.deskripsi || '');
                }

                // 2️⃣ IsuDetail
                if(data.isu && data.isu.length > 0){
                    data.isu.forEach(function(item){
                        $('#isuCardTable tbody').append(
                            `<tr>
                                <td>${item.isu || ''}</td>
                                <td>${item.keterangan || ''}</td>
                            </tr>`
                        );
                    });
                }

                // 3️⃣ Desa Sasaran Utama
                if(data.desa && data.desa.length > 0){
                    data.desa.forEach(function(item){
                        $('#sasarandesa tbody').append(
                            `<tr>
                                <td>${item.desa_nama || item.desa_id}</td>
                                <td>${item.isu_utama || ''}</td>
                            </tr>`
                        );
                    });
                }

                // 4️⃣ Instansi / Program
                if(data.instansi && data.instansi.length > 0){
                    data.instansi.forEach(function(item){
                        $('#isuLembagaTable tbody').append(
                            `<tr>
                                <td>${item.instansi || ''}</td>
                                <td>${item.program || ''}</td>
                            </tr>`
                        );
                    });
                }
                // console.log(data.okupasi);
                // 5️⃣ Okupasi
                if(data.okupasi){
                  let bg = (data.okupasi.okupasi=="Tinggi")?"bg-danger text-white":(data.okupasi.okupasi=="Sedang")?"bg-warning text-dark":(data.okupasi.okupasi=="Rendah")?"bg-success text-white":"bg-secondary text-white";
                    $('#okupasiCardBody').append(
                        `<div class="${bg} p-2 rounded mb-2"><h4 class="text-center mb-0">${data.okupasi.okupasi}</h4></div>
                        <p style="text-align:justify; margin-top:8px;">${data.okupasi.keterangan||'-'}</p>`
                    );
                }
                // let mapAll = null;
                const kebunJsons = data.kebunJsons;

            let color = "#0084ff"; 
            if (data.derajatHubungan && data.derajatHubungan.derajat_hubungan) {
                switch (data.derajatHubungan.derajat_hubungan) {
                    case "P1": color = "#dc3545"; break;
                    case "P2": color = "#fd7e14"; break;
                    case "P3": color = "#ffff00"; break;
                    case "P4": color = "#28a745"; break;
                }
            }

            if (mapAll) { 
                mapAll.remove(); 
                mapAll = null; 
            }
            mapAll = L.map('mapContainerAll');
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapAll);

            var allBounds = L.latLngBounds([]);

            // render polygon jika ada
            kebunJsons.forEach(json=>{
                L.vectorGrid.protobuf(json.tileurl, {
                    vectorTileLayerStyles: {
                        [json.id]: {
                            weight: 3, color: color, fill: true, fillColor: color, fillOpacity: 0.5
                        }
                    }, interactive: false
                }).addTo(mapAll);

                if (json.bounds?.length === 4) {
                    allBounds.extend([
                        [json.bounds[1], json.bounds[0]],
                        [json.bounds[3], json.bounds[2]]
                    ]);
                }
            });

            // cek apakah ada polygon valid
            if (allBounds.isValid()) {
                mapAll.fitBounds(allBounds, { padding:[50,50], maxZoom:12 });
                mapAll.setZoom(mapAll.getZoom()-1);
            } else {
                // fallback ke peta Indonesia
                mapAll.fitBounds([[-11,95],[6,141]],{ padding:[50,50], maxZoom:5 });
                mapAll.setZoom(mapAll.getZoom()-1);
            }


            // legend
            var legendHtml = `<div class="map-legend"><b>Legend:</b><br>
                <div><span class="legend-color" style="background:${color}"></span> Semua Polygon</div></div>`;
            var legend = L.control({position:'topright'});
            legend.onAdd = function(){ let div = L.DomUtil.create('div','map-legend'); div.innerHTML=legendHtml; return div; };
            legend.addTo(mapAll);
            mapAll.invalidateSize();
            setTimeout(() => mapAll.invalidateSize(), 300);

                // Tampilkan modal
                $('#derajatDetailModal').modal('show');
            },
            error: function(xhr, status, error){
                alert('Gagal mengambil data: ' + error);
            }
        });

    });

});
</script>




<script>
  $(document).on('click', '.editIsuBtn', function () {
    let id = $(this).data('id');
    let unit = $(this).data('unit');
    let region = $(this).data('region');
    let tahun = $(this).data('tahun');

    // set hidden input
    $('#edit_derajat_id').val(id);

    // ubah judul modal
    $('#modalEditIsuLabel').text('Edit Isu & Desa - ' + region + ' | ' + unit + ' (' + tahun + ')');

    // AJAX get data isu
    $.get("<?php echo e(url('isu/show')); ?>/" + id, function (res) {
        // === ISU ===
        let isuHtml = '';
        res.isu.forEach(function (item) {
            isuHtml += `
            <div class="row g-2 isu-row mb-2">
                <div class="col-md-3">
                    <label>Isu</label>
                    <select name="isu[]" class="form-control" required>
                        <option value="">Pilih Isu</option>
                        <option value="Ekonomi" ${item.isu == 'Ekonomi' ? 'selected':''}>Ekonomi</option>
                        <option value="Sosial" ${item.isu == 'Sosial' ? 'selected':''}>Sosial & Kesejahteraan</option>
                        <option value="Lingkungan" ${item.isu == 'Lingkungan' ? 'selected':''}>Lingkungan</option>
                        <option value="Pendidikan" ${item.isu == 'Pendidikan' ? 'selected':''}>Pendidikan</option>
                        <option value="Hubungan Stakeholder" ${item.isu == 'Hubungan Stakeholder' ? 'selected':''}>Hubungan Stakeholder</option>
                    </select>
                </div>
                <div class="col-md-8">
                    <label>Keterangan</label>
                    <textarea name="keterangan[]" class="form-control" required rows="2">${item.keterangan}</textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove">-</button>
                </div>
            </div>`;
        });
        $('#edit-isu-container').html(isuHtml);

        // === DESA ===
        let desaHtml = '';
        res.desa.forEach(function (item) {
            desaHtml += `
            <div class="row g-2 desa-row mb-2">
                <div class="col-md-8">
                    <label>Desa</label>
                    <select name="desa[]" class="form-control select2-desa" style="width:100%" required></select>
                </div>
                <div class="col-md-3">
                    <label>Isu</label>
                    <select name="isu_utama[]" class="form-control" required>
                        <option value="">Pilih Isu</option>
                        <option value="Ekonomi" ${item.isu_utama == 'Ekonomi' ? 'selected':''}>Ekonomi</option>
                        <option value="Sosial" ${item.isu_utama == 'Sosial' ? 'selected':''}>Sosial & Kesejahteraan</option>
                        <option value="Lingkungan" ${item.isu_utama == 'Lingkungan' ? 'selected':''}>Lingkungan</option>
                        <option value="Pendidikan" ${item.isu_utama == 'Pendidikan' ? 'selected':''}>Pendidikan</option>
                        <option value="Hubungan Stakeholder" ${item.isu_utama == 'Hubungan Stakeholder' ? 'selected':''}>Hubungan Stakeholder</option>
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-desa">-</button>
                </div>
            </div>`;
        });
        $('#edit-desa-container').html(desaHtml);

        
// Inisialisasi Select2 untuk semua select desa
$('#edit-desa-container').find('.select2-desa').each(function (i, el) {
    $(el).select2({
        dropdownParent: $('#modalEditIsu'),
        ajax: {
            url: '<?php echo e(route("wilayah.desa")); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            }
        }
    });

    // === inject option terpilih dari controller (res.desa) ===
    let desaId   = res.desa[i].desa_id;
    let desaText = res.desa[i].desa_nama; // <- pastikan controller sudah kirim
    if (desaId && desaText) {
        let option = new Option(desaText, desaId, true, true);
        $(el).append(option).trigger('change');
    }
});

        // initSelect2Desa($('#edit-desa-container').find('.select2-desa'));

        // === INSTANSI ===
        let instansiHtml = '';
        res.instansi.forEach(function (item) {
            instansiHtml += `
            <div class="row g-2 instansi-row mb-2">
                <div class="col-md-5">
                    <label>Instansi</label>
                    <select name="instansi[]" class="form-control select2-instansi" required>
                        <option value="">Pilih Instansi</option>
                        <?php $__currentLoopData = $stake; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stakeholder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($stakeholder->id); ?>" ${item.instansi_id == <?php echo e($stakeholder->id); ?> ? 'selected':''}><?php echo e($stakeholder->nama_instansi); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label>Program</label>
                    <textarea name="program[]" class="form-control" required rows="2">${item.program}</textarea>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-remove-instansi">-</button>
                </div>
            </div>`;
        });
        $('#edit-instansi-container').html(instansiHtml);
        $('#edit-instansi-container').find('.select2-instansi').select2({
            dropdownParent: $('#modalEditIsu'),
            width: '100%'
        });

        // === OKUPASI ===
        let okupasiHtml = `
        <div class="row g-2 okupasi-row mb-2">
            <div class="col-md-3">
                <label>Okupasi</label>
                <select name="okupasi" class="form-control" required>
                    <option value="">Pilih Okupasi</option>
                    <option value="Rendah" ${res.okupasi.okupasi == 'Rendah' ? 'selected':''}>Rendah</option>
                    <option value="Sedang" ${res.okupasi.okupasi == 'Sedang' ? 'selected':''}>Sedang</option>
                    <option value="Tinggi" ${res.okupasi.okupasi == 'Tinggi' ? 'selected':''}>Tinggi</option>
                </select>
            </div>
            <div class="col-md-8">
                <label>Keterangan</label>
                <textarea name="keterangan_okupasi" class="form-control" required rows="2">${res.okupasi.keterangan}</textarea>
            </div>
        </div>`;
        $('#edit-okupasi-container').html(okupasiHtml);
    });

});

</script>

<script>
  $(document).ready(function () {
    // Intercept submit form
    $("#formIsu").on("submit", function (e) {
        e.preventDefault(); // cegah reload

        let form = $(this);
        let url = form.attr("action");
        let formData = form.serialize();

        $.ajax({
          url: url,
          method: "POST",
          data: formData,
          success: function (data) {
              if (data.success) {
                  $("#modalIsu").modal("hide");
                  showToast("Data berhasil disimpan!", "success");
                  setTimeout(() => location.reload(), 1500);
              } else {
                  // tampilkan pesan dari server
                  showToast(data.message || "Data gagal disimpan!", "danger");
              }
          },
          error: function () {
              showToast("Terjadi kesalahan server!", "danger");
          }
      });

    });
});

// Toast function bootstrap 5
function showToast(message, type = "success") {
    let toastId = "toast-" + Date.now();
    let toastHtml = `
    <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0"
         role="alert" aria-live="assertive" aria-atomic="true" data-bs-delay="3000"
         style="position: fixed; top: 20px; right: 20px; z-index: 9999;">
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto"
                    data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
    </div>`;

    $("body").append(toastHtml);
    let toastEl = new bootstrap.Toast(document.getElementById(toastId));
    toastEl.show();

    // auto remove setelah hide
    document.getElementById(toastId).addEventListener("hidden.bs.toast", function () {
        $(this).remove();
    });
}

</script>
<script>
  $(document).on('click', '.isuBtn', function () {
    let unit   = $(this).data('unit');
    let region = $(this).data('region');
    let tahun  = $(this).data('tahun');
    let id   = $(this).data('id');
    $('#derajat_id').val(id);
    // ubah judul modal
    $('#modalIsuLabel').text('Input Isu & Desa - ' + region + ' | ' + unit + ' (' + tahun + ') ');
});

$(document).ready(function(){
    // Tambah row baru
    $("#btn-add").click(function(){
        let newRow = `
        <div class="row g-2 isu-row mb-2">
            <div class="col-md-3">
                <label>Isu</label>
                <select name="isu[]" class="form-control" required>
                    <option value="">Pilih Isu</option>
                    <option value="Ekonomi">Ekonomi</option>
                    <option value="Sosial">Sosial & Kesejahteraan</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Hubungan Stakeholder">Hubungan Stakeholder</option>
                </select>
            </div>
            <div class="col-md-8">
                <label>Keterangan</label>
                <textarea name="keterangan[]" class="form-control" required rows="2"></textarea>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove">-</button>
            </div>
        </div>`;
        $("#isu-container").append(newRow);
    });

    $("#btn-add-instansi").click(function(){
        let newRow = `
          <div class="row g-2 instansi-row mb-2">
              <div class="col-md-5">
                  <label class="form-label">Instansi</label>
                  <select name="instansi[]" class="form-control select2-instansi" required>
                      <option value="">Pilih Instansi</option>
                      <?php $__currentLoopData = $stake; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stakeholder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                          <option value="<?php echo e($stakeholder->id); ?>"><?php echo e($stakeholder->nama_instansi); ?></option>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                  </select>
              </div>
              <div class="col-md-6">
                  <label>Program</label>
                  <textarea name="program[]" class="form-control" required rows="2"></textarea>
              </div>
              <div class="col-md-1 d-flex align-items-end">
                  <button type="button" class="btn btn-danger btn-remove-instansi">-</button>
              </div>
    </div>`;

    let $newRow = $(newRow);
    $("#instansi-container").append($newRow);

    // aktifkan select2 untuk select baru
    $newRow.find('.select2-instansi').select2({
        dropdownParent: $('#modalIsu'),
        placeholder: "Pilih Instansi",
        allowClear: true,
        width: '100%'
    });
});
    // Hapus row (minimal 1)
    $(document).on("click", ".btn-remove", function(){
        if ($(".isu-row").length > 1) {
            $(this).closest(".isu-row").remove();
        } else {
            alert("Minimal harus ada 1 inputan isu.");
        }
    });
    $(document).on("click", ".btn-remove-instansi", function(){
        if ($(".instansi-row").length > 1) {
            $(this).closest(".instansi-row").remove();
        } else {
            alert("Minimal harus ada 1 inputan instansi.");
        }
    });
});

function initSelect2Desa(el) {
    el.select2({
        placeholder: 'Cari Desa...',
        ajax: {
            url: '<?php echo e(route("wilayah.desa")); ?>',
            dataType: 'json',
            delay: 250,
            data: function (params) {
                return { q: params.term };
            },
            processResults: function (data) {
                return { results: data.results };
            },
            cache: true
        },
        minimumInputLength: 2,
        dropdownParent: $('#modalIsu') // modal tempat select berada
        // e.stopPropagation();
    });
}

$(document).ready(function () {
    // Inisialisasi awal untuk select yang sudah ada
    initSelect2Desa($('.select2-desa'));

    // Tambah baris baru
    $("#btn-add-desa").click(function () {
        let newRow = `
        <div class="row g-2 desa-row mb-2">
            <div class="col-md-8">
                <label>Desa</label>
                <select name="desa[]" class="form-control select2-desa" style="width:100%" required></select>
            </div>
            <div class="col-md-3">
                <label>Isu</label>
                <select name="isu_utama[]" class="form-control" required>
                    <option value="">Pilih Isu</option>
                    <option value="Ekonomi">Ekonomi</option>
                    <option value="Sosial">Sosial & Kesejahteraan</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Hubungan Stakeholder">Hubungan Stakeholder</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove-desa">-</button>
            </div>
        </div>`;

        let $newRow = $(newRow);
        $("#desa-container").append($newRow);

        // Aktifkan select2 di baris baru
        initSelect2Desa($newRow.find('.select2-desa'));
    });

    // Hapus baris
    $(document).on("click", ".btn-remove-desa", function () {
        $(this).closest(".desa-row").remove();
    });
});
$('#modalIsu').on('hidden.bs.modal', function () {
    // reset form
    $('#formIsu')[0].reset();

    // kosongkan semua select2
    $(this).find('.select2-desa').val(null).trigger('change');

    // kalau kamu pakai dynamic row, reset ke kondisi awal (1 baris default)
    $('#isu-container').html(`
        <div class="row g-2 isu-row mb-2">
            <div class="col-md-3">
                <label class="form-label">Isu</label>
                <select name="isu[]" class="form-control" required>
                    <option value="">Pilih Isu</option>
                    <option value="Ekonomi">Ekonomi</option>
                    <option value="Sosial">Sosial & Kesejahteraan</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Hubungan Stakeholder">Hubungan Stakeholder</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Keterangan</label>
                <textarea name="keterangan[]" class="form-control" required rows="2"></textarea>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove">-</button>
            </div>
        </div>
    `);

    $('#desa-container').html(`
        <div class="row g-2 desa-row mb-2">
            <div class="col-md-8">
                <label class="form-label">Desa</label>
                <select name="desa[]" class="form-control select2-desa" style="width:100%" required></select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Isu</label>
                <select name="isu_utama[]" class="form-control" required>
                    <option value="">Pilih Isu</option>
                    <option value="Ekonomi">Ekonomi</option>
                    <option value="Sosial">Sosial & Kesejahteraan</option>
                    <option value="Lingkungan">Lingkungan</option>
                    <option value="Pendidikan">Pendidikan</option>
                    <option value="Hubungan Stakeholder">Hubungan Stakeholder</option>
                </select>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove-desa">-</button>
            </div>
        </div>
    `);
    initSelect2Desa($('.select2-desa'));

    $('#instansi-container').html(`
        <div class="row g-2 instansi-row mb-2">
            <div class="col-md-5">
                              <label class="form-label">Instansi</label>
                              <select name="instansi[]" id="instansiisu2" class="form-control select2-instansi" required>
                                  <option value="">Pilih Instansi</option>
                                  <?php $__currentLoopData = $stake; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stakeholder): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                      <option value="<?php echo e($stakeholder->id); ?>"><?php echo e($stakeholder->nama_instansi); ?></option>
                                  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                              </select>
                          </div>
            <div class="col-md-6">
                <label class="form-label">Program</label>
                <textarea name="program[]" class="form-control" required rows="2"></textarea>
            </div>
            <div class="col-md-1 d-flex align-items-end">
                <button type="button" class="btn btn-danger btn-remove-instansi">-</button>
            </div>
        </div>
    `);
});

</script>







<script>
$(document).ready(function() {
    // Select2 untuk Unit
    $('#id_unit').select2({
        dropdownParent: $('#modalAdd'), // supaya muncul di dalam modal, bukan di luar
        placeholder: "Pilih Unit",
        allowClear: true,
        width: '100%'
    });
    $('#edit_unit').select2({
        dropdownParent: $('#modalEdit'),
        placeholder: "Pilih Unit",
        allowClear: true,
        width: '100%'
    });
    $('#instansiisu').select2({
        dropdownParent: $('#modalIsu'),
        placeholder: "Pilih Instansi",
        allowClear: true,
        width: '100%'
    });

});

</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tambah
    document.getElementById('formAdd').addEventListener('submit', function(e) {
        e.preventDefault();
        fetch("<?php echo e(route('derajat.store')); ?>", {
            method: "POST",
            body: new FormData(this),
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                $("#modalAdd").modal("hide");
                showToast("Data berhasil disimpan!", "success");
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast("Gagal menyimpan data!", "danger");
            }
        })
        .catch(() => showToast("Terjadi kesalahan server!", "danger"));

    });

    $(document).on('click', '.editBtn', function() {
    // ambil data dari atribut
    let btn = $(this);

    let fullDesc = btn.data('deskripsi') || "";

    // Regex untuk pisah berdasarkan "Prioritas ..."
    let splitPattern = /(Prioritas [^\.\n]+\.)([\s\S]*)/;
    let match = fullDesc.match(splitPattern);

    let bagian1 = "";
    let bagian2 = "";

    if (match) {
        bagian1 = match[1].trim(); // otomatis (sampai "Prioritas ...")
        bagian2 = match[2].trim(); // sisanya
    } else {
        bagian2 = fullDesc; // fallback, kalau tidak sesuai pola
    }

    $('#edit_id').val(btn.data('id'));
    $('#edit_unit').val(btn.data('unit')).trigger("change");
    $('#edit_tahun').val(btn.data('tahun'));

    // bidang socmap
    $('#edit_lingkungan').val(btn.data('lingkungan'));
    $('#edit_ekonomi').val(btn.data('ekonomi'));
    $('#edit_pendidikan').val(btn.data('pendidikan'));
    $('#edit_sosial').val(btn.data('sosial'));
    $('#edit_okupasi').val(btn.data('okupasi'));

    // bidang kepuasan
    $('#edit_kepuasan').val(btn.data('kepuasan'));
    $('#edit_kontribusi').val(btn.data('kontribusi'));
    $('#edit_komunikasi').val(btn.data('komunikasi'));
    $('#edit_kepercayaan').val(btn.data('kepercayaan'));
    $('#edit_keterlibatan').val(btn.data('keterlibatan'));

    // total & prioritas
    $('#edit_skor_socmap').val(btn.data('skor'));
    $('#edit_prioritas').val(btn.data('prioritas')).trigger("change");
    $('#edit_indeks_kepuasan').val(btn.data('indeks'));
    $('#edit_derajat_kepuasan').val(btn.data('derajatkepuasan')).trigger("change");
    $('#edit_derajat_hubungan').val(btn.data('derajathubungan')).trigger("change");

    // teks
    // $('#edit_deskripsi1').val(btn.data('deskripsi'));
    $("#edit_deskripsi1").val(bagian2);  // editable
    $('#edit_deskripsi').val(btn.data('narasi'));

    // buka modal
    $('#modalEdit').modal('show');
});


   
    document.getElementById('formEdit').addEventListener('submit', function(e) {
        e.preventDefault();

        let id = document.getElementById('edit_id').value;
        let formData = new FormData(this);

        // kalau route pakai PUT / PATCH di Laravel
        // formData.append('_method', 'PUT');

        fetch("/derajat-hubungan/update/" + id, {
            method: "POST",
            body: formData,
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                $("#modalEdit").modal("hide");
                showToast("Data berhasil diperbarui!", "success");
                setTimeout(() => location.reload(), 1500);
            } else {
                showToast("Gagal memperbarui data!", "danger");
            }
        })
        .catch(() => showToast("Terjadi kesalahan server!", "danger"));
    });


    // Delete
    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', function() {
            if(confirm("Yakin hapus data?")) {
                fetch("/derajat-hubungan/delete/" + this.dataset.id, {
                    method: "DELETE",
                    headers: {'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"}
                }).then(() => location.reload());
            }
        });
    });
});
</script>
<script>
function hitungIndeks() {
    let kepuasan    = parseFloat($("input[name='kepuasan']").val())    || 0;
    let kontribusi  = parseFloat($("input[name='kontribusi']").val())  || 0;
    let komunikasi  = parseFloat($("input[name='komunikasi']").val())  || 0;
    let kepercayaan = parseFloat($("input[name='kepercayaan']").val()) || 0;
    let keterlibatan= parseFloat($("input[name='keterlibatan']").val())|| 0;

    let indeks = ((kepuasan * 0.30) + 
                  (kontribusi * 0.20) + 
                  (komunikasi * 0.20) + 
                  (kepercayaan * 0.15) + 
                  (keterlibatan * 0.15)) / 4 ;

    $("#indeks_kepuasan").val(indeks.toFixed(2));
    updateNarasi();
}

// jalankan otomatis kalau ada perubahan input
$(document).on("input", "input[name='kepuasan'], input[name='kontribusi'], input[name='komunikasi'], input[name='kepercayaan'], input[name='keterlibatan']", function() {
    hitungIndeks();
});

function applyPriorityColor(select) {
  select.classList.remove("bg-P1", "bg-P2", "bg-P3", "bg-P4", "bg-Null");
  if (select.value) {
    select.classList.add("bg-" + select.value);
  }
}

document.querySelectorAll(".priority-select").forEach(select => {
  // Saat ada perubahan
  select.addEventListener("change", function() {
    applyPriorityColor(this);
  });

  // Terapkan warna awal (misalnya saat modal edit dibuka)
  applyPriorityColor(select);
});

// kalau modal dibuka reset dulu indeks
$('#modalAdd, #modalEdit').on('shown.bs.modal', function () {
    hitungIndeks();
    document.querySelectorAll('#modalEdit .priority-select').forEach(select => {
    applyPriorityColor(select);
  });
});

function hitungSocmapDetail() {
    let lingkungan = parseFloat(document.getElementById("lingkungan").value) || 0;
    let ekonomi = parseFloat(document.getElementById("ekonomi").value) || 0;
    let pendidikan = parseFloat(document.getElementById("pendidikan").value) || 0;
    let sosial = parseFloat(document.getElementById("sosial_kesesjahteraan").value) || 0;
    let okupasi = parseFloat(document.getElementById("okupasi").value) || 0;

    let jumlah = (lingkungan + ekonomi + pendidikan + sosial + okupasi);

    document.getElementById("skor_socmap").value = jumlah.toFixed(2); // tampilkan
    updateNarasi();
}

</script>
<script>
function hitungSocmap() {
    let lingkungan = parseFloat(document.getElementById("lingkungan").value) || 0;
    let ekonomi = parseFloat(document.getElementById("ekonomi").value) || 0;
    let pendidikan = parseFloat(document.getElementById("pendidikan").value) || 0;
    let sosial = parseFloat(document.getElementById("sosial_kesesjahteraan").value) || 0;
    let okupasi = parseFloat(document.getElementById("okupasi").value) || 0;

    // Hitung total skor
    let jumlah = lingkungan + ekonomi + pendidikan + sosial + okupasi;
    document.getElementById("skor_socmap").value = jumlah.toFixed(2);

    // Tentukan prioritas berdasarkan skor
    let prioritas = "";
    // if (jumlah === 0) {
    //     prioritas = "Null";
    // } else if (jumlah >= 0 && jumlah <= 7) {
    //     prioritas = "P4";
    // } else if (jumlah >= 8 && jumlah <= 15) {
    //     prioritas = "P3";
    // } else if (jumlah >= 16 && jumlah <= 24) {
    //     prioritas = "P2";
    // } else if (jumlah > 24) {
    //     prioritas = "P1";
    // }
    if (jumlah >= 0 && jumlah <= 7) {
        prioritas = "P4";
    } else if (jumlah >= 8 && jumlah <= 15) {
        prioritas = "P3";
    } else if (jumlah >= 16 && jumlah <= 24) {
        prioritas = "P2";
    } else if (jumlah > 24) {
        prioritas = "P1";
    }

    // Auto-select dropdown
    let prioritasSelect = document.getElementById("prioritas_socmap");
    prioritasSelect.value = prioritas;

    // Terapkan warna sesuai pilihan
    applyPriorityColor(prioritasSelect);

    // Update narasi jika ada
    updateNarasi();
}
</script>

<script>
  document.querySelectorAll(".priority-select").forEach(select => {
    select.addEventListener("change", function() {
      this.classList.remove("bg-P1", "bg-P2", "bg-P3", "bg-P4", "bg-Null");
      if (this.value) {
        this.classList.add("bg-" + this.value);
      }
    });
  });
</script>
<script>
  function updateNarasi() {
    const unitSelect   = document.querySelector('[name="id_unit"]');
    const unitText     = unitSelect.options[unitSelect.selectedIndex]?.text || "";
    const unitName     = unitText.split(" - ")[0] || "";
    const regionName   = unitText.split(" - ")[1] || "";

    const indeks       = document.getElementById("indeks_kepuasan").value || 0;
    const socmap       = document.getElementById("skor_socmap").value || 0;
    const derajat      = document.getElementById("derajat_hubungan").value || "";
    const deskripsi1    = document.querySelector('[name="deskripsi1"]').value || "";

    if(unitName && regionName && indeks && socmap && derajat) {
      let text = `Unit ${unitName} mendapat skor Indeks Kepuasan Stakeholder di ${indeks} dan skor Social Mapping di ${socmap} yang menempatkan ${unitName} di kategori ${derajat} Prioritas ${regionName}.`;
      if (deskripsi1.trim() !== "") {
          text += ` ${deskripsi1}`;
      }
      document.getElementById("deskripsi").value = text;
    } else {
      document.getElementById("deskripsi").value = "";
    }
}

  // Jalankan ketika field berubah
  document.querySelector('[name="id_unit"]').addEventListener("change", updateNarasi);
  document.getElementById("indeks_kepuasan").addEventListener("input", updateNarasi);
  document.getElementById("skor_socmap").addEventListener("input", updateNarasi);
  document.getElementById("derajat_hubungan").addEventListener("change", updateNarasi);
  document.querySelector('[name="deskripsi1"]').addEventListener("input", updateNarasi);

  function showToast(message, type = "success") {
    let bgClass = "bg-success";
    if (type === "danger") bgClass = "bg-danger";
    if (type === "warning") bgClass = "bg-warning text-dark";
    if (type === "info") bgClass = "bg-info text-dark";

    let toastId = "toast-" + Date.now();
    let toastHTML = `
      <div id="${toastId}" class="toast align-items-center text-white ${bgClass} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="d-flex">
          <div class="toast-body">${message}</div>
          <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
      </div>`;

    document.querySelector(".toast-container").insertAdjacentHTML("beforeend", toastHTML);

    let toastEl = document.getElementById(toastId);
    let bsToast = new bootstrap.Toast(toastEl, { delay: 3000 });
    bsToast.show();

    // hapus setelah hilang
    toastEl.addEventListener("hidden.bs.toast", () => toastEl.remove());
}

</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  // gunakan event delegation
  document.addEventListener("click", function(e) {
    if (e.target.classList.contains("detailBtn")) {
      let btn = e.target;

      let item = {
        unit: btn.dataset.unit,
        region: btn.dataset.region,
        tahun: btn.dataset.tahun,
        prioritas_socmap1: btn.dataset.prioritas,
        indeks_kepuasan: btn.dataset.indeks,
        derajat_hubungan: btn.dataset.derajat,
        kepuasan: btn.dataset.kepuasan,
        kontribusi: btn.dataset.kontribusi,
        komunikasi: btn.dataset.komunikasi,
        kepercayaan: btn.dataset.kepercayaan,
        keterlibatan: btn.dataset.keterlibatan,
        lingkungan: btn.dataset.lingkungan,
        ekonomi: btn.dataset.ekonomi,
        pendidikan: btn.dataset.pendidikan,
        sosial_kesesjahteraan: btn.dataset.sosial,
        okupasi: btn.dataset.okupasi,
        skor_socmap: btn.dataset.socmap,
        deskripsi: btn.dataset.deskripsi
      };

      console.log(item);

      // reset isi modal dulu
      document.getElementById("detailContent").innerHTML = "";

      let bgHubungan = `bg-${item.derajat_hubungan}`;
      let bgKepuasan = `bg-${item.derajat_hubungan}`;
      let bgSocmap   = `bg-${item.prioritas_socmap1}`;

      let html = `
        <div class="row">
          <div class="col-md-12">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Data Analisis Hubungan Stakeholder</div>
              <div class="card-body" style="font-size:0.9em;">
                <h6 class="text-center">Derajat Hubungan</h6>
                <div class="${bgHubungan}"><h4 class="text-center mb-0">${item.derajat_hubungan}</h4></div>
                <p style="text-align:justify; margin-top:8px;">${item.deskripsi || '-'}</p>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Indeks Kepuasan Stakeholder</div>
              <div class="card-body" style="font-size:0.9em;">
                <table class="table table-sm">
                  <tr><td>Kepuasan</td><td>${item.kepuasan ?? '-'}</td></tr>
                  <tr><td>Kontribusi</td><td>${item.kontribusi ?? '-'}</td></tr>
                  <tr><td>Komunikasi</td><td>${item.komunikasi ?? '-'}</td></tr>
                  <tr><td>Kepercayaan</td><td>${item.kepercayaan ?? '-'}</td></tr>
                  <tr><td>Keterlibatan</td><td>${item.keterlibatan ?? '-'}</td></tr>
                  <tr><td><b>Total</b></td><td><b>${item.indeks_kepuasan ?? '-'}</b></td></tr>
                </table>
                <div class="${bgKepuasan} text-center mt-2"><h4 class="mb-0">${item.derajat_hubungan ?? '-'}</h4></div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-3 shadow-sm">
              <div class="card-header bg-info text-white text-center">Social Mapping</div>
              <div class="card-body" style="font-size:0.9em;">
                <table class="table table-sm">
                  <tr><td>Lingkungan</td><td>${item.lingkungan ?? '-'}</td></tr>
                  <tr><td>Ekonomi</td><td>${item.ekonomi ?? '-'}</td></tr>
                  <tr><td>Pendidikan</td><td>${item.pendidikan ?? '-'}</td></tr>
                  <tr><td>Sosial</td><td>${item.sosial_kesesjahteraan ?? '-'}</td></tr>
                  <tr><td>Okupasi</td><td>${item.okupasi ?? '-'}</td></tr>
                  <tr><td><b>Total</b></td><td><b>${item.skor_socmap ?? '-'}</b></td></tr>
                </table>
                <div class="${bgSocmap} text-center mt-2"><h4 class="mb-0">${item.prioritas_socmap1 ?? '-'}</h4></div>
              </div>
            </div>
          </div>
        </div>
      `;

      document.getElementById("detailContent").innerHTML = html;
    }
  });
});


</script>
<script>
function hitungIndeksEdit() {
    let kepuasan    = parseFloat($("#edit_kepuasan").val())    || 0;
    let kontribusi  = parseFloat($("#edit_kontribusi").val())  || 0;
    let komunikasi  = parseFloat($("#edit_komunikasi").val())  || 0;
    let kepercayaan = parseFloat($("#edit_kepercayaan").val()) || 0;
    let keterlibatan= parseFloat($("#edit_keterlibatan").val())|| 0;

    let indeks = ((kepuasan * 0.30) + 
                  (kontribusi * 0.20) + 
                  (komunikasi * 0.20) + 
                  (kepercayaan * 0.15) + 
                  (keterlibatan * 0.15)) / 4;

    $("#edit_indeks_kepuasan").val(indeks.toFixed(2));
    updateNarasiEdit();
}

// jalankan otomatis kalau ada perubahan input di modal Edit
$(document).on("input", "#edit_kepuasan, #edit_kontribusi, #edit_komunikasi, #edit_kepercayaan, #edit_keterlibatan", function() {
    hitungIndeksEdit();
});

function hitungSocmapEdit() {
    let lingkungan = parseFloat($("#edit_lingkungan").val()) || 0;
    let ekonomi    = parseFloat($("#edit_ekonomi").val())    || 0;
    let pendidikan = parseFloat($("#edit_pendidikan").val()) || 0;
    let sosial     = parseFloat($("#edit_sosial").val())     || 0;
    let okupasi    = parseFloat($("#edit_okupasi").val())    || 0;

    let jumlah = lingkungan + ekonomi + pendidikan + sosial + okupasi;
    $("#edit_skor_socmap").val(jumlah.toFixed(2));
    updateNarasiEdit();
}

// jalankan otomatis kalau ada perubahan input di modal Edit
$(document).on("input", "#edit_lingkungan, #edit_ekonomi, #edit_pendidikan, #edit_sosial, #edit_okupasi", function() {
    hitungSocmapEdit();
});

function updateNarasiEdit() {
    const unitSelect   = document.querySelector('#edit_unit');
    const unitText     = unitSelect.options[unitSelect.selectedIndex]?.text || "";
    const unitName     = unitText.split(" - ")[0] || "";
    const regionName   = unitText.split(" - ")[1] || "";

    const indeks       = document.getElementById("edit_indeks_kepuasan").value || 0;
    const socmap       = document.getElementById("edit_skor_socmap").value || 0;
    const derajat      = document.getElementById("edit_derajat_hubungan").value || "";
    const deskripsi1   = document.getElementById("edit_deskripsi1").value || "";

    if(unitName && regionName && indeks && socmap && derajat) {
      let text = `Unit ${unitName} mendapat skor Indeks Kepuasan Stakeholder di ${indeks} dan skor Social Mapping di ${socmap} yang menempatkan ${unitName} di kategori ${derajat} Prioritas ${regionName}.`;
      if (deskripsi1.trim() !== "") {
          text += ` ${deskripsi1}`;
      }
      document.getElementById("edit_deskripsi").value = text;
    } else {
      document.getElementById("edit_deskripsi").value = "";
    }
}

// update narasi edit ketika input berubah
$("#edit_unit, #edit_derajat_hubungan, #edit_deskripsi1").on("change input", function() {
    updateNarasiEdit();
});

// kalau modal Edit dibuka, langsung hitung ulang
$('#modalEdit').on('shown.bs.modal', function () {
    hitungIndeksEdit();
    hitungSocmapEdit();
});
</script>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/derajat/index.blade.php ENDPATH**/ ?>