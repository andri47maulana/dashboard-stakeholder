

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
                <div class="container">
    
    <!-- Import Excel -->
    

    <!-- Tombol tambah -->
    <button class="btn btn-primary mb-2" data-toggle="modal" data-target="#modalAdd">Tambah Data</button>
    
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
                            Lihat Detail
                        </button>

                        <button class="btn btn-sm btn-success isuBtn"
                            data-id="<?php echo e($row->id); ?>"
                            data-unit="<?php echo e($row->id_unit); ?>"
                            data-tahun="<?php echo e($row->tahun); ?>"
                            data-toggle="modal"
                            data-target="#modalIsu">
                            Input Isu
                        </button>

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
        <button type="button" class="btn-close" data-dismiss="modal"></button>
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
      <form id="formIsu" method="POST" action="<?php echo e(route('isu.store')); ?>">
        <?php echo csrf_field(); ?>
        <div class="modal-header">
          <h5 class="modal-title" id="modalIsuLabel">Input Isu & Desa</h5>
          <button type="button" class="btn-close" data-dismiss="modal">X</button>
        </div>
        <div class="modal-body">
            
            <!-- Container Input Dinamis -->
            <div id="isu-container">
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
                </div>
            </div>
            <!-- Tombol tambah -->
            <button type="button" class="btn btn-primary mt-2" id="btn-add">+ Tambah Isu</button>

            <div id="isu-container">
                <div class="row g-2 isu-row mb-2">
                    <div class="col-md-3">
                        <label>Instansi</label>
                        <select name="instansi[]" class="form-control" required>
                            <option value="">Pilih Instansi</option>
                            <option value="Instansi 1">Instansi 1</option>
                            <option value="Instansi 2">Instansi 2</option>
                            <option value="Instansi 3">Instansi 3</option>
                        </select>
                    </div>
                    <div class="col-md-8">
                        <label>Keterangan</label>
                        <textarea name="keterangan[]" class="form-control" required rows="2"></textarea>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-remove">-</button>
                    </div>
                </div>
            </div>
            <!-- Tombol tambah -->
            <button type="button" class="btn btn-primary mt-2" id="btn-add">+ Tambah Isu</button>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
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

    // Hapus row (minimal 1)
    $(document).on("click", ".btn-remove", function(){
        if ($(".isu-row").length > 1) {
            $(this).closest(".isu-row").remove();
        } else {
            alert("Minimal harus ada 1 inputan isu.");
        }
    });
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

// function hitungSocmap() {
//     let lingkungan = parseFloat(document.getElementById("lingkungan").value) || 0;
//     let ekonomi = parseFloat(document.getElementById("ekonomi").value) || 0;
//     let pendidikan = parseFloat(document.getElementById("pendidikan").value) || 0;
//     let sosial = parseFloat(document.getElementById("sosial_kesesjahteraan").value) || 0;
//     let okupasi = parseFloat(document.getElementById("okupasi").value) || 0;

//     let jumlah = (lingkungan + ekonomi + pendidikan + sosial + okupasi);

//     document.getElementById("skor_socmap").value = jumlah.toFixed(2); // tampilkan
//     updateNarasi();
// }

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
    if (jumlah === 0) {
        prioritas = "Null";
    } else if (jumlah >= 1 && jumlah <= 25) {
        prioritas = "P1";
    } else if (jumlah >= 26 && jumlah <= 50) {
        prioritas = "P2";
    } else if (jumlah >= 51 && jumlah <= 75) {
        prioritas = "P3";
    } else if (jumlah >= 76) {
        prioritas = "P4";
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
  document.querySelectorAll(".detailBtn").forEach(btn => {
    btn.addEventListener("click", function() {
      let item = {
        unit: this.dataset.unit,
        region: this.dataset.region,
        tahun: this.dataset.tahun,
        prioritas_socmap: this.dataset.prioritas,
        indeks_kepuasan: this.dataset.indeks,
        derajat_hubungan: this.dataset.derajat,
        kepuasan: this.dataset.kepuasan,
        kontribusi: this.dataset.kontribusi,
        komunikasi: this.dataset.komunikasi,
        kepercayaan: this.dataset.kepercayaan,
        keterlibatan: this.dataset.keterlibatan,
        lingkungan: this.dataset.lingkungan,
        ekonomi: this.dataset.ekonomi,
        pendidikan: this.dataset.pendidikan,
        sosial_kesesjahteraan: this.dataset.sosial,
        okupasi: this.dataset.okupasi,
        skor_socmap: this.dataset.socmap,
        deskripsi: this.dataset.deskripsi
      };

      let bgHubungan = `bg-${item.derajat_hubungan}`;
      let bgKepuasan = `bg-${item.derajat_hubungan}`;
      let bgSocmap   = `bg-${item.prioritas_socmap}`;

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
              <table style="font-size:0.9em;" class="table table-sm">
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
              <table style="font-size:0.9em;" class="table table-sm">
                <tr><td>Lingkungan</td><td>${item.lingkungan ?? '-'}</td></tr>
                <tr><td>Ekonomi</td><td>${item.ekonomi ?? '-'}</td></tr>
                <tr><td>Pendidikan</td><td>${item.pendidikan ?? '-'}</td></tr>
                <tr><td>Sosial</td><td>${item.sosial_kesesjahteraan ?? '-'}</td></tr>
                <tr><td>Okupasi</td><td>${item.okupasi ?? '-'}</td></tr>
                <tr><td><b>Total</b></td><td><b>${item.skor_socmap ?? '-'}</b></td></tr>
              </table>
              <div class="${bgSocmap} text-center mt-2"><h4 class="mb-0">${item.prioritas_socmap ?? '-'}</h4></div>
            </div>
          </div>
        </div>
      </div>
      `;
      document.getElementById("detailContent").innerHTML = html;
    });
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