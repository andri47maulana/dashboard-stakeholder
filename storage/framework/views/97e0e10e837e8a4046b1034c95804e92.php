 
<?php $__env->startSection('content'); ?>
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
<h1 class="h3 mb-2 text-gray-800">Menu Stakeholder</h1>
    <div class="card shadow mb-4">
        <div class="card-body row" style="font-size: 0.85em;">
            <?php if(Auth::user()->hakakses =='Admin'): ?>
            <div class="col-md-3">
                
                <div class="form-group row">
                    <label for="region" class="col-md-4 control-label" style="text-align: right"><strong>Region</strong></label>
                    <select name="region" id="region" class="col-sm-7 form-control">
                    <?php if($searchregion!=""): ?>
                    
                        <option value="">Pilih..</option>
                        <option value="PTPN I HO" <?php if($searchregion=="PTPN I HO"): ?> selected <?php endif; ?>>PTPN I HO</option>
                        <option value="PTPN I Regional 1" <?php if($searchregion=="PTPN I Regional 1"): ?> selected <?php endif; ?>>PTPN I Regional 1</option>
                        <option value="PTPN I Regional 2" <?php if($searchregion=="PTPN I Regional 2"): ?> selected <?php endif; ?>>PTPN I Regional 2</option>
                        <option value="PTPN I Regional 3" <?php if($searchregion=="PTPN I Regional 3"): ?> selected <?php endif; ?>>PTPN I Regional 3</option>
                        <option value="PTPN I Regional 4" <?php if($searchregion=="PTPN I Regional 4"): ?> selected <?php endif; ?>>PTPN I Regional 4</option>
                        <option value="PTPN I Regional 5" <?php if($searchregion=="PTPN I Regional 5"): ?> selected <?php endif; ?>>PTPN I Regional 5</option>
                        <option value="PTPN I Regional 6" <?php if($searchregion=="PTPN I Regional 6"): ?> selected <?php endif; ?>>PTPN I Regional 6</option>
                        <option value="PTPN I Regional 7" <?php if($searchregion=="PTPN I Regional 7"): ?> selected <?php endif; ?>>PTPN I Regional 7</option>
                        <option value="PTPN I Regional 8" <?php if($searchregion=="PTPN I Regional 8"): ?> selected <?php endif; ?>>PTPN I Regional 8</option>
                    <?php else: ?>
                        <option value="">Pilih..</option>
                        <option value="PTPN I HO">PTPN I HO</option>
                        <option value="PTPN I Regional 1">PTPN I Regional 1</option>
                        <option value="PTPN I Regional 2">PTPN I Regional 2</option>
                        <option value="PTPN I Regional 3">PTPN I Regional 3</option>
                        <option value="PTPN I Regional 4">PTPN I Regional 4</option>
                        <option value="PTPN I Regional 5">PTPN I Regional 5</option>
                        <option value="PTPN I Regional 6">PTPN I Regional 6</option>
                        <option value="PTPN I Regional 7">PTPN I Regional 7</option>
                        <option value="PTPN I Regional 8">PTPN I Regional 8</option>
                    <?php endif; ?>
                        
                    </select>
                </div>
            </div>
            <?php endif; ?>
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="kategori" class="col-md-4 control-label" style="text-align: right"><strong>Kategori</strong></label>
                    <select name="kategori" id="kategori" class="col-sm-7 form-control">
                    <?php if($searchkategori!=""): ?>
                        <option value="">Pilih..</option>
                        <option value="Governance" <?php if($searchkategori=="Governance"): ?> selected <?php endif; ?>>Governance</option>
                        <option value="Non Governance" <?php if($searchkategori=="Non Governance"): ?> selected <?php endif; ?>>Non Governance</option>
                    <?php else: ?>
                        <option value="">Pilih..</option>
                        <option value="Governance">Governance</option>
                        <option value="Non Governance">Non Governance</option>
                    <?php endif; ?>                                   
                        
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                
                <div class="form-group row">
                    <label for="kebun" class="col-md-4 control-label" style="text-align: right"><strong>Kebun</strong></label>
                    <select name="kebun" id="kebun" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    <?php $__currentLoopData = $datakebun; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kebun): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($kebun->kebun); ?>" <?php if($searchkebun==$kebun->kebun): ?> selected <?php endif; ?>><?php echo e($kebun->kebun); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </select>
                </div>
            </div>
            <!--
            <div class="col-md-3">
                <div class="form-group row">
                    <label for="desa" class="col-md-4 control-label" style="text-align: right"><strong>Desa</strong></label>
                    <select name="desa" id="desa" class="col-sm-7 form-control">
                    <option value="">Pilih..</option>
                    <?php $__currentLoopData = $datadesa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $desa): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($desa->desa); ?>" <?php if($searchdesa==$desa->desa): ?> selected <?php endif; ?>><?php echo e($desa->desa); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        
                    </select>
                </div>
            </div>
            -->
        </div>
        <div class="card-footer">
            <!-- <a href="<?php echo e(url('/')); ?>/dash/stakeholder"> -->
            <button class="btn btn-outline-warning float-right btn-sm cancelsearch" style="margin-right: 10px;"><i class="fas fa-fw fa-stop"></i> Batalkan </button>
            <!-- </a> -->
            &nbsp;
            <button type="submit" name="submit" class="btn btn-outline-success float-right btn-sm cari" style="margin-right: 10px;"><i class="fas fa-fw fa-filter"></i> Filter </button>
        </div>
    </div>
    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        
        <div class="card-body">
            <!-- <a href="<?php echo e(url('/dash/form_stakeholder_add')); ?>">
                <button class="btn btn-primary btn-rounded add_user">
                <i class="fas fa-fw fa-plus"></i> Tambah Data
                </button>
            </a> -->
            <button type="button" class="btn btn-primary tambahdata">
              <i class="fas fa-fw fa-plus"></i> Tambah Data
            </button>
            <a href="
                <?php echo e(url('/exportstakeholder')); ?>?region=<?php echo e($searchregion); ?>&kategori=<?php echo e($searchkategori); ?>&kebun=<?php echo e($searchkebun); ?>

            ">
                <button class="btn btn-success btn-rounded add_user">
                <i class="fas fa-fw fa-download"></i> Export
                </button>
            </a>
            
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th style="text-align:center">Stakeholder<br>Instansi</th>
                            <th style="text-align:center">Identitas PIC</th>
                            <th style="text-align:center">Jabatan PIC</th>
                            <th style="text-align:center">Daerah</th>
                            
                            <th style='text-align:center;'>Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $dataalluser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td ><a href="<?php echo e(url('/dash/form_stakeholder_detail')); ?>/<?php echo e($key->id); ?>"><i class="fas fa-fw fa-search"></i></a> <?php echo e($key->nama_instansi); ?></td>
                            <td ><?php echo e($key->nama_pic); ?> <br><span><?php echo e($key->nomorkontak_pic); ?></td>
                            <td ><?php echo e($key->jabatan_pic); ?></td>
                            <td ><?php echo e($key->daerah_instansi); ?></td>
                            
                            <td style="text-align:center" width="100">
                                <btn class="btn btn-warning btn-sm editdata" id="<?php echo e($key->id); ?>"><i class="fas fa-fw fa-edit"></i></btn>
                                <a href="<?php echo e(url('/dash/deletestakeholder')); ?>/<?php echo e($key->id); ?>">
                                    <btn class="btn btn-danger btn-sm deletedata"><i class="fas fa-fw fa-trash"></i></btn>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modaladddata">
        <div class="card shadow mb-4 modal modalpopup" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
            <h1 class="h3 mb-2 text-gray-800"> Tambah Data Stakeholder</h1>
            <br>
            <form action="<?php echo e(url('/dash/storestakeholder')); ?>" enctype="multipart/form-data" method="post">
            <?php echo csrf_field(); ?>
            
                        
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-5">
                        <?php if(Auth::user()->hakakses =='Admin'): ?>
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="regionclass" class="col-sm-7 form-control" required>
                                
                                    <option value="PTPN I HO">PTPN I HO</option>
                                    <option value="PTPN I Regional 1">PTPN I Regional 1</option>
                                    <option value="PTPN I Regional 2">PTPN I Regional 2</option>
                                    <option value="PTPN I Regional 3">PTPN I Regional 3</option>
                                    <option value="PTPN I Regional 4">PTPN I Regional 4</option>
                                    <option value="PTPN I Regional 5">PTPN I Regional 5</option>
                                    <option value="PTPN I Regional 6">PTPN I Regional 6</option>
                                    <option value="PTPN I Regional 7">PTPN I Regional 7</option>
                                    <option value="PTPN I Regional 8">PTPN I Regional 8</option>
                                    
                                </select>
                            </div>
                        <?php else: ?>
                        <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="region" class="col-sm-7 form-control" required>
                                    <option value="<?php echo e(Auth::user()->region); ?>"><?php echo e(Auth::user()->region); ?></option>
                                </select>
                            </div>
                        <?php endif; ?>
                            <div class="form-group row">
                                <label for="kebun" class="col-sm-3 control-label">Kebun</label>
                                <input type="text" class="col-sm-7 form-control form-control-user"
                                    id="kebunclass" name="kebun" aria-describedby="kebun" value=""
                                    placeholder="Nama Kebun..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi /Stakeholder</label>
                                <input type="text" class="col-sm-7 form-control form-control-user"
                                    id="nama_instansi" name="nama_instansi" aria-describedby="nama_instansi" value=""
                                    placeholder="Nama Instansi/Stakeholder..." required>
                            </div>
                            <div class="form-group row">
                                <label for="daerah_instansi" class="col-sm-3 control-label">Daerah Instansi</label>
                                <input type="text" class="col-sm-7 form-control form-control-user"
                                    id="daerah_instansi" name="daerah_instansi" aria-describedby="daerah_instansi" value=""
                                    placeholder="Daerah Instansi..." required>
                            </div>
                            <div class="form-group row">
                                <label for="desa" class="col-sm-3 control-label">Desa</label>
                                <input type="text" class="col-sm-7 form-control form-control-user"
                                    id="desaclass" name="desa" aria-describedby="desa" value=""
                                    placeholder="Nama Desa..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nama_pic" class="col-sm-3 control-label">Nama PIC</label>
                                    <textarea required class="col-sm-7 form-control form-control-user"
                                    id="nama_pic" name="nama_pic" aria-describedby="nama_pic"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="jabatan_pic" class="col-sm-3 control-label">Jabatan PIC</label>
                                    <textarea required class="col-sm-7 form-control form-control-user"
                                    id="jabatan_pic" name="jabatan_pic" aria-describedby="jabatan_pic"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="nomorkontak_pic" class="col-sm-3 control-label">Nomor Kontak PIC</label>
                                <input type="number" class="col-sm-7 form-control form-control-user"
                                    id="nomorkontak_pic" name="nomorkontak_pic" aria-describedby="nomorkontak_pic" value=""
                                    placeholder="Nomor Kontak PIC..." required>
                            </div>
                            <div class="form-group row">
                                <label for="dokumenpendukung" class="col-sm-3 control-label">Dokumen Pendukung</label>
                                <input type="file" class="col-sm-7 form-control form-control-user"
                                    id="dokumenpendukung" name="dokumenpendukung" aria-describedby="dokumenpendukung" value=""
                                    placeholder="Dokumen Pendukung" required>
                            </div>
                                            
                        </div>
                        <div class="col-md-7">
                            <div class="form-group row">
                                <label for="derajat_hubungan" class="col-md-3 control-label">Derajat Hubungan</label>
                                <select name="derajat_hubungan" id="derajat_hubungan" class="col-sm-5 form-control" required>
                                    <option value="Tipe A">Tipe A</option>
                                    <option value="Tipe B">Tipe B</option>
                                    <option value="Tipe C">Tipe C</option>                                   
                                </select>
                                <span class="ket_derajat_hubunganadd col-md-4" style="font-size: 0.8em">
                                    Pihak stakeholder atau instansi memiliki tingkat keutamaan yang tinggi dan wajib dimiliki oleh perusahaan
                                </span>
                            </div>          
                            <div class="form-group row">
                                <label for="curent_condition" class="col-md-3 control-label">Curent Condition</label>
                                <select name="curent_condition" id="curent_condition" class="col-sm-5 form-control" required>
                                
                                    <option value="Sangat Baik">Sangat Baik</option>
                                    <option value="Baik">Baik</option>
                                    <option value="Cukup Baik">Cukup Baik</option>
                                    <option value="Kurang Baik">Kurang Baik</option>
                                    <option value="Tidak Baik">Tidak Baik</option>                               
                                </select>
                            </div>
                            <div class="form-group row">
                                <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                <div class="col-sm-5">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="governance" value="Governance" required>
                                        <label class="form-check-label" for="governance">
                                            Governance
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="kategori" id="non_governance" value="Non Governance" required>
                                        <label class="form-check-label" for="non_governance">
                                            Non Governance
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label for="skala_kekuatan" class="col-md-3 control-label">Skala Kekuatan</label>
                                <select name="skala_kekuatan" id="skala_kekuatan" class="col-sm-5 form-control">
                                <?php if(isset($datauser->skala_kekuatan)): ?>
                                    <option value="1"  <?php if($datauser->skala_kekuatan=="1"): ?> selected <?php endif; ?>>1</option>
                                    <option value="2"  <?php if($datauser->skala_kekuatan=="2"): ?> selected <?php endif; ?>>2</option>
                                    <option value="3"  <?php if($datauser->skala_kekuatan=="3"): ?> selected <?php endif; ?>>3</option>
                                    <option value="4"  <?php if($datauser->skala_kekuatan=="4"): ?> selected <?php endif; ?>>4</option>
                                    <option value="5"  <?php if($datauser->skala_kekuatan=="5"): ?> selected <?php endif; ?>>5</option>
                                <?php else: ?>
                                    <option value="">Pilih..</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                <?php endif; ?>                                    
                                </select>
                                <span class="ket_kekuatan col-md-4" style="font-size: 0.8em">
                                    Evaluasi sejauh mana stakeholder memiliki pengaruh terhadap proyek atau aktivitas organisasi.
                                </span>
                            </div>
                            <div class="form-group row">
                                <label for="skala_kepentingan" class="col-md-3 control-label">Skala Kepentingan</label>
                                <select name="skala_kepentingan" id="skala_kepentingan" class="col-sm-5 form-control">
                                <?php if(isset($datauser->skala_kepentingan)): ?>
                                    <option value="1"  <?php if($datauser->skala_kepentingan=="1"): ?> selected <?php endif; ?>>1</option>
                                    <option value="2"  <?php if($datauser->skala_kepentingan=="2"): ?> selected <?php endif; ?>>2</option>
                                    <option value="3"  <?php if($datauser->skala_kepentingan=="3"): ?> selected <?php endif; ?>>3</option>
                                    <option value="4"  <?php if($datauser->skala_kepentingan=="4"): ?> selected <?php endif; ?>>4</option>
                                    <option value="5"  <?php if($datauser->skala_kepentingan=="5"): ?> selected <?php endif; ?>>5</option>
                                <?php else: ?>
                                    <option value="">Pilih..</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                <?php endif; ?>                                    
                                </select>
                                <span class="ket_kepentingan col-md-4" style="font-size: 0.8em">
                                    Evaluasi sejauh mana stakeholder tertarik pada proyek atau aktivitas organisasi.
                                </span>
                            </div>
                            <div class="form-group row">
                                <label for="email" class="col-sm-3 control-label">Email / Media Sosial PIC</label>
                                <input type="text" class="col-sm-5 form-control form-control-user"
                                    id="email" name="email" aria-describedby="email" value=""
                                    placeholder="Email / Media Sosial PIC..." required>
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_ptpn" class="col-sm-3 control-label">Ekspektasi PTPN</label>
                                
                                <textarea class="col-sm-5 form-control form-control-user "
                                    id="ekspektasi_ptpn" name="ekspektasi_ptpn" aria-describedby="ekspektasi_ptpn"></textarea>
                            </div>
                            <div class="form-group row">
                                <label for="ekspektasi_stakeholder" class="col-sm-3 control-label">Ekspektasi Stakeholder</label>
                                
                                    <textarea class="col-sm-5 form-control form-control-user "
                                    id="ekspektasi_stakeholder" name="ekspektasi_stakeholder" aria-describedby="ekspektasi_stakeholder"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Tambah </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="modaleditdata">
        <div class="card shadow mb-4 modal modalpopup" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
            <h1 class="h3 mb-2 text-gray-800"> Edit Data Stakeholder</h1>
            <br>
            
            <form action="<?php echo e(url('/dash/updatestakeholder')); ?>" enctype="multipart/form-data" method="post">
                <input type="hidden" name="id" id="idstakeholder" required="required" 
                    value="">
            <?php echo csrf_field(); ?>
            
                        <div class="row" style="font-size: 0.85em;">
                            <div class="col-md-5">
                            <?php if(Auth::user()->hakakses =='Admin'): ?>
                                <div class="form-group row">
                                    <label for="region" class="col-md-3 control-label">Region</label>
                                    <select name="region" id="regionclass" class="col-sm-7 form-control" required>
                                    
                                        <option value="PTPN I HO">PTPN I HO</option>
                                        <option value="PTPN I Regional 1">PTPN I Regional 1</option>
                                        <option value="PTPN I Regional 2">PTPN I Regional 2</option>
                                        <option value="PTPN I Regional 3">PTPN I Regional 3</option>
                                        <option value="PTPN I Regional 4">PTPN I Regional 4</option>
                                        <option value="PTPN I Regional 5">PTPN I Regional 5</option>
                                        <option value="PTPN I Regional 6">PTPN I Regional 6</option>
                                        <option value="PTPN I Regional 7">PTPN I Regional 7</option>
                                        <option value="PTPN I Regional 8">PTPN I Regional 8</option>
                                        
                                    </select>
                                </div>
                            <?php else: ?>
                            <div class="form-group row">
                                    <label for="region" class="col-md-3 control-label">Region</label>
                                    <select name="region" id="region" class="col-sm-7 form-control" required>
                                        <option value="<?php echo e(Auth::user()->region); ?>"><?php echo e(Auth::user()->region); ?></option>
                                    </select>
                                </div>
                            <?php endif; ?>
                                <div class="form-group row">
                                    <label for="kebun" class="col-sm-3 control-label">Kebun</label>
                                    <input type="text" class="col-sm-7 form-control form-control-user"
                                        id="kebunclass" name="kebun" aria-describedby="kebun" value=""
                                        placeholder="Nama Kebun..." required>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_instansi" class="col-sm-3 control-label">Nama Instansi /Stakeholder</label>
                                    <input type="text" class="col-sm-7 form-control form-control-user"
                                        id="nama_instansi" name="nama_instansi" aria-describedby="nama_instansi" value=""
                                        placeholder="Nama Instansi/Stakeholder..." required>
                                </div>
                                <div class="form-group row">
                                    <label for="daerah_instansi" class="col-sm-3 control-label">Daerah Instansi</label>
                                    <input type="text" class="col-sm-7 form-control form-control-user"
                                        id="daerah_instansi" name="daerah_instansi" aria-describedby="daerah_instansi" value=""
                                        placeholder="Daerah Instansi..." required>
                                </div>
                                <div class="form-group row">
                                    <label for="desa" class="col-sm-3 control-label">Desa</label>
                                    <input type="text" class="col-sm-7 form-control form-control-user"
                                        id="desaclass" name="desa" aria-describedby="desa" value=""
                                        placeholder="Nama Desa..." required>
                                </div>
                                <div class="form-group row">
                                    <label for="nama_pic" class="col-sm-3 control-label">Nama PIC</label>
                                        <textarea required class="col-sm-7 form-control form-control-user"
                                        id="nama_pic" name="nama_pic" aria-describedby="nama_pic"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="jabatan_pic" class="col-sm-3 control-label">Jabatan PIC</label>
                                        <textarea required class="col-sm-7 form-control form-control-user"
                                        id="jabatan_pic" name="jabatan_pic" aria-describedby="jabatan_pic"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="nomorkontak_pic" class="col-sm-3 control-label">Nomor Kontak PIC</label>
                                    <input type="number" class="col-sm-7 form-control form-control-user"
                                        id="nomorkontak_pic" name="nomorkontak_pic" aria-describedby="nomorkontak_pic" value=""
                                        placeholder="Nomor Kontak PIC..." required>
                                </div>
                                <div class="form-group row">
                                    <label for="dokumenpendukung" class="col-sm-3 control-label">Dokumen Pendukung</label>
                                    <input type="file" class="col-sm-7 form-control form-control-user"
                                        id="dokumenpendukung" name="dokumenpendukung" aria-describedby="dokumenpendukung" value=""
                                        placeholder="Dokumen Pendukung">
                                </div>                
                            </div>
                            <div class="col-md-7">
                                <div class="form-group row">
                                    <label for="derajat_hubungan" class="col-md-3 control-label">Derajat Hubungan</label>
                                    <select name="derajat_hubungan" id="derajat_hubungan" class="col-sm-5 form-control" required>
                                        <option value="Tipe A">Tipe A</option>
                                        <option value="Tipe B">Tipe B</option>
                                        <option value="Tipe C">Tipe C</option>                                   
                                    </select>
                                    <span class="ket_derajat_hubungan col-md-4" style="font-size: 0.8em">Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan</span>
                                
                                </div>          
                                <div class="form-group row">
                                    <label for="curent_condition" class="col-md-3 control-label">Curent Condition</label>
                                    <select name="curent_condition" id="curent_condition" class="col-sm-5 form-control" required>
                                    
                                        <option value="Sangat Baik">Sangat Baik</option>
                                        <option value="Baik">Baik</option>
                                        <option value="Cukup Baik">Cukup Baik</option>
                                        <option value="Kurang Baik">Kurang Baik</option>
                                        <option value="Tidak Baik">Tidak Baik</option>                               
                                    </select>
                                </div>
                                <div class="form-group row">
                                    <label for="kategori" class="col-md-3 control-label">Kategori</label>
                                    <div class="col-sm-5">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kategori" id="governance" value="Governance" required>
                                            <label class="form-check-label" for="governance">
                                                Governance
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="kategori" id="non_governance" value="Non Governance" required>
                                            <label class="form-check-label" for="non_governance">
                                                Non Governance
                                            </label>
                                        </div> 
                                    </div>
                                </div>
                                
                                <div class="form-group row">
                                    <label for="skala_kekuatan" class="col-md-3 control-label">Skala Kekuatan</label>
                                    <select name="skala_kekuatan" id="skala_kekuatan" class="col-sm-5 form-control">
                                    <?php if(isset($datauser->skala_kekuatan)): ?>
                                        <option value="1"  <?php if($datauser->skala_kekuatan=="1"): ?> selected <?php endif; ?>>1</option>
                                        <option value="2"  <?php if($datauser->skala_kekuatan=="2"): ?> selected <?php endif; ?>>2</option>
                                        <option value="3"  <?php if($datauser->skala_kekuatan=="3"): ?> selected <?php endif; ?>>3</option>
                                        <option value="4"  <?php if($datauser->skala_kekuatan=="4"): ?> selected <?php endif; ?>>4</option>
                                        <option value="5"  <?php if($datauser->skala_kekuatan=="5"): ?> selected <?php endif; ?>>5</option>
                                    <?php else: ?>
                                        <option value="">Pilih..</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    <?php endif; ?>                                    
                                    </select>
                                    <span class="ket_kekuatan col-md-4" style="font-size: 0.8em">
                                        Evaluasi sejauh mana stakeholder memiliki pengaruh terhadap proyek atau aktivitas organisasi.
                                    </span>
                                </div>
                                <div class="form-group row">
                                    <label for="skala_kepentingan" class="col-md-3 control-label">Skala Kepentingan</label>
                                    <select name="skala_kepentingan" id="skala_kepentingan" class="col-sm-5 form-control">
                                    <?php if(isset($datauser->skala_kepentingan)): ?>
                                        <option value="1"  <?php if($datauser->skala_kepentingan=="1"): ?> selected <?php endif; ?>>1</option>
                                        <option value="2"  <?php if($datauser->skala_kepentingan=="2"): ?> selected <?php endif; ?>>2</option>
                                        <option value="3"  <?php if($datauser->skala_kepentingan=="3"): ?> selected <?php endif; ?>>3</option>
                                        <option value="4"  <?php if($datauser->skala_kepentingan=="4"): ?> selected <?php endif; ?>>4</option>
                                        <option value="5"  <?php if($datauser->skala_kepentingan=="5"): ?> selected <?php endif; ?>>5</option>
                                    <?php else: ?>
                                        <option value="">Pilih..</option>
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3">3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    <?php endif; ?>                                    
                                    </select>
                                    <span class="ket_kepentingan col-md-4" style="font-size: 0.8em">
                                        Evaluasi sejauh mana stakeholder tertarik pada proyek atau aktivitas organisasi.
                                    </span>
                                </div>
                                <div class="form-group row">
                                    <label for="email" class="col-sm-3 control-label">Email / Media Sosial PIC</label>
                                    <input type="text" class="col-sm-5 form-control form-control-user"
                                        id="email" name="email" aria-describedby="email" value=""
                                        placeholder="Email / Media Sosial PIC..." required>
                                </div>
                                <div class="form-group row">
                                    <label for="ekspektasi_ptpn" class="col-sm-3 control-label">Ekspektasi PTPN</label>
                                    
                                    <textarea class="col-sm-5 form-control form-control-user "
                                        id="ekspektasi_ptpn" name="ekspektasi_ptpn" aria-describedby="ekspektasi_ptpn"></textarea>
                                </div>
                                <div class="form-group row">
                                    <label for="ekspektasi_stakeholder" class="col-sm-3 control-label">Ekspektasi Stakeholder</label>
                                        <textarea class="col-sm-5 form-control form-control-user "
                                        id="ekspektasi_stakeholder" name="ekspektasi_stakeholder" aria-describedby="ekspektasi_stakeholder"></textarea>
                                </div>
                            </div>
                        </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Tutup</button>
                        <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Ubah </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script>
    var modaladddata = $('.modaladddata').detach();
    var modaleditdata = $('.modaleditdata').detach();

    <?php if($errors->any()): ?>
        Swal.fire({
            title: "Error",
            text: "<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($error); ?>,<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>",
            icon: "error"
        });
        
    <?php endif; ?>
    <?php if(session('suksesdelete')): ?>
        Swal.fire({
            title: "Data ini telah dihapus!",
            text: "Berhasil.",
            icon: "success"
        });
    <?php endif; ?>
    
    <?php if(session('sukses')): ?>
        Swal.fire({
            title: "Sukses",
            text: "<?php echo e(session('sukses')); ?>",
            icon: "success"
        });
    <?php endif; ?>
    
    

    $('.deletedata').click(function(e){
        e.preventDefault();
        var href = $(this).parent('a').attr('href');
        Swal.fire({
            title: "Apakah anda yakin ingin menghapus data?",
            text: "Anda tidak dapat mengembalikan data kembali!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus",
            cancelButtonText: "Kembali"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
    $('.tambahdata').click(function(e){
        // console.log(modaladddata);
        $('.modaladddata').remove();
        $('.modaleditdata').remove();
        $('body').append(modaladddata);
        $('#exampleModal').modal('show');
    })

    $('.editdata').click(function(e){
        $('.modaladddata').remove();
        $('.modaleditdata').remove();
        $('body').append(modaleditdata);
        
        
        var id = $(this).attr('id');
        console.log(id);
        $.ajax({
            url: "<?php echo e(url('/dash/get_data_stakeholder')); ?>/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                
                $('#idstakeholder').val(id);
                $('#regionclass').val(response.region);
                $('#kebunclass').val(response.kebun);
                $('#desaclass').val(response.desa);
                $('#curent_condition').val(response.curent_condition);
                $('#nama_instansi').val(response.nama_instansi);
                $('#daerah_instansi').val(response.daerah_instansi);
                $('#nama_pic').val(response.nama_pic);
                $('#jabatan_pic').val(response.jabatan_pic);
                $('#nomorkontak_pic').val(response.nomorkontak_pic);
                $('#derajat_hubungan').val(response.derajat_hubungan);
                if(response.derajat_hubungan=="Tipe A"){
                    $('.ket_derajat_hubungan').html('Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan');
                }
                else if(response.derajat_hubungan=="Tipe B"){
                    $('.ket_derajat_hubungan').html('Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.');
                }
                else if(response.derajat_hubungan=="Tipe C"){
                    $('.ket_derajat_hubungan').html('Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.');
                }
                else{
                    $('.ket_derajat_hubungan').html('');
                }
                if(response.kategori=="Governance"){
                    $('#governance').prop('checked', true);
                }
                if(response.kategori=="Non Governance"){
                    $('#non_governance').prop('checked', true);
                }
                // $('#tipe_stakeholder').val(response.tipe_stakeholder);
                // if(response.tipe_stakeholder=="Moderat"){
                //     $('.ket_tipe').html('Stakeholder atau instansi memiliki tingkat kepentingan menengah terhadap perusahaan');
                // }
                // else if(response.tipe_stakeholder=="Prioritas"){
                //     $('.ket_tipe').html('Pihak stakeholder atau instansi memiliki tingkat keutamaan yang tinggi dan wajib dimiliki oleh perusahaan');
                // }
                $('#skala_kekuatan').val(response.skala_kekuatan);
                $('#skala_kepentingan').val(response.skala_kepentingan);
                // else{
                //     $('.ket_tipe').html('');
                // }
                $('#email').val(response.email);
                $('#ekspektasi_ptpn').val(response.ekspektasi_ptpn);
                $('#ekspektasi_stakeholder').val(response.ekspektasi_stakeholder);
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    $("#region, #kategori, #desa, #kebun").select2({});
    $('.cari').click(function(){
        var region = $('#region').find(":selected").val();
        var kategori = $('#kategori').find(":selected").val();
        var kebun = $('#kebun').find(":selected").val();
        var desa = $('#desa').find(":selected").val();

        $.cookie("region", region, { expires : 3600 });
        $.cookie("kategori", kategori, { expires : 3600 });
        $.cookie("kebun", kebun, { expires : 3600 });
        $.cookie("desa", desa, { expires : 3600 });
        location.reload();
    });
    $('.cancelsearch').click(function(){
        $.cookie("region", "", { expires : 3600 });
        $.cookie("kategori", "", { expires : 3600 });
        $.cookie("desa", "", { expires : 3600 });
        $.cookie("kebun", "", { expires : 3600 });
        location.reload();
    })
    $(document).ready(function() {
        // Delegating from the document to handle dynamically added '.modaladddata h1'
        // $(document).on('change', '.modaladddata #tipe_stakeholder', function() {
        //     var tipe = $(this).val();
        //     if(tipe=="Moderat"){
        //         $('.ket_tipeadd').html('Stakeholder atau instansi memiliki tingkat kepentingan menengah terhadap perusahaan');
        //     }
        //     else if(tipe=="Prioritas"){
        //         $('.ket_tipeadd').html('Pihak stakeholder atau instansi memiliki tingkat keutamaan yang tinggi dan wajib dimiliki oleh perusahaan');
        //     }
        //     else{
        //         $('.ket_tipeadd').html('');
        //     }
        // });
        // $(document).on('change', '.modaleditdata #tipe_stakeholder', function() {
        //     var tipe = $(this).val();
        //     if(tipe=="Moderat"){
        //         $('.ket_tipe').html('Stakeholder atau instansi memiliki tingkat kepentingan menengah terhadap perusahaan');
        //     }
        //     else if(tipe=="Prioritas"){
        //         $('.ket_tipe').html('Pihak stakeholder atau instansi memiliki tingkat keutamaan yang tinggi dan wajib dimiliki oleh perusahaan');
        //     }
        //     else{
        //         $('.ket_tipe').html('');
        //     }
        // });
        $(document).on('change', '.modaladddata #derajat_hubungan', function() {
            var tipe = $(this).val();
            if(tipe=="Tipe A"){
                $('.ket_derajat_hubunganadd').html('Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan');
            }
            else if(tipe=="Tipe B"){
                $('.ket_derajat_hubunganadd').html('Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.');
            }
            else if(tipe=="Tipe C"){
                $('.ket_derajat_hubunganadd').html('Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.');
            }
            else{
                $('.ket_derajat_hubunganadd').html('');
            }
            
        });
        $(document).on('change', '.modaleditdata #derajat_hubungan', function() {
            var tipe = $(this).val();
            if(tipe=="Tipe A"){
                $('.ket_derajat_hubungan').html('Regulator yang disebut sebagai pihak stakeholder atau instansi yang memiliki pengaruh penting terhadap perusahaan');
            }
            else if(tipe=="Tipe B"){
                $('.ket_derajat_hubungan').html('Perusahaan memiliki tingkat kepentingan yang tinggi terhadap stakeholder atau instansi.');
            }
            else if(tipe=="Tipe C"){
                $('.ket_derajat_hubungan').html('Stakeholder atau instansi tidak memiliki kepentingan terhadap perusahaan tetapi memiliki hubungan yang harus dibina dengan perusahaan.');
            }
            else{
                $('.ket_derajat_hubungan').html('');
            }
        });
    });
    // $('.nav_sdm').addClass('active');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\APP\dashboard-stakeholder\resources\views/stakeholder/stakeholder.blade.php ENDPATH**/ ?>