
<?php $__env->startSection('content'); ?>
    <h1 class="h3 mb-2 text-gray-800"> DETAIL DATA STAKEHOLDER</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="<?php echo e(url('/dash/stakeholder')); ?>" method="get">              
            <?php echo csrf_field(); ?>
                    <style>
                        .form-group {
                            margin-bottom: 0.5rem!important;
                        }
                        .form-control {
                            font-size: 1.1em!important;
                        }
                    </style>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-info text-white"><i class="fas fa-building"></i> Instansi</div>
                                <div class="card-body" style="font-size:0.9em;">                                    
                                    <p><strong>Region:</strong> <?php echo e($datauser->region); ?></p>
                                    <p><strong>Kategori:</strong> <span class="badge badge-success"><?php echo e($datauser->kategori); ?></span></p>
                                    <p><strong>Kebun/Unit:</strong> <?php echo e($datauser->kebun); ?></p>
                                    <p><strong>Instansi/Lembaga:</strong> <?php echo e($datauser->nama_instansi); ?></p>

                                    <p><strong>Daerah:</strong> Prov. <?php echo e($datauser->prov_nama); ?>, Kab./Kota <?php echo e($datauser->kab_nama); ?>, Kec. <?php echo e($datauser->kec_nama); ?>, Desa/Kel. <?php echo e($datauser->desa_nama); ?></p>
                                    <p><strong>Dokumen Pendukung:</strong> <a href="<?php echo e(asset('pdf/'.$datauser->dokumenpendukung)); ?>" target="_blank"><u><i>Klik untuk melihat</i></u></a></p>
                                    
                                    <br>
                                    <br>
                                </div>
                            </div>
                            

                            
                        </div>

                        <div class="col-md-6">
                        <div class="card mb-3 shadow-sm">
                                <div class="card-header bg-success text-white"><i class="fas fa-user"></i> PIC</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <p><strong>Nama PIC:</strong> <?php echo e($datauser->nama_pic); ?></p>
                                    <p><strong>Jabatan:</strong> <?php echo e($datauser->jabatan_pic); ?></p>
                                    <p><strong>Kontak:</strong> <?php echo e($datauser->nomorkontak_pic); ?></p>
                                    <p><strong>Email / Sosial Media:</strong> <?php echo e($datauser->email ?? '-'); ?></p>
                                    <p><strong>Nama PIC 2:</strong> <?php echo e($datauser->nama_pic2 ?? '-'); ?></p>
                                    <p><strong>Jabatan PIC 2:</strong> <?php echo e($datauser->jabatan_pic2 ?? '-'); ?></p>
                                    <p><strong>Kontak PIC 2:</strong> <?php echo e($datauser->nomorkontak_pic2 ?? '-'); ?></p>
                                    <p><strong>Email / Sosial Media PIC 2:</strong> <?php echo e($datauser->email ?? '-'); ?></p>
                                </div>
                            </div>

                            
                        </div>
                        <div class="col-md-12">
                            <div class="card mb-3 shadow-sm">
                                
                                <div class="card-header bg-secondary text-white"><i class="fas fa-chart-bar"></i> Data Analisis Hubungan Stakeholder</div>
                                <div class="card-body" style="font-size:0.9em;">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Skala Kepentingan: </strong><?php echo e($datauser->skala_kepentingan ?? '-'); ?></p>
                                                <p><strong>Skala Pengaruh: </strong><?php echo e($datauser->skala_pengaruh ?? '-'); ?></p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Curent Condition: </strong> <?php echo e($datauser->curent_condition ?? '-'); ?></p>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <p><strong>Note:  </strong><?php echo e($datauser->hasil_skala ?? '-'); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div style="overflow-x:auto;">
                                                <table border="1" cellpadding="10" style="border-collapse: collapse; width: 100%; min-width: 600px;">
                                                    <tr style="background-color: #007BFF; color: white; text-align: center;">
                                                        <th style="width: 33%;">Ekspektasi Stakeholder</th>
                                                        <th style="width: 33%;">Ekspektasi Perusahaan</th>
                                                        <th style="width: 33%;">Saran Bagi Manajemen</th>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo e($datauser->ekspektasi_stakeholder ?? '-'); ?></td>
                                                        <td><?php echo e($datauser->ekspektasi_ptpn ?? '-'); ?></td>
                                                        <td><?php echo e($datauser->saranbagimanajemen ?? '-'); ?></td>
                                                    </tr>
                                                </table>
                                            </div>


                                        </div>
                                    </div>               
                                </div>
                            </div>
                            
                        </div>

                    </div>
                    
                
                <!-- /.card-body -->
                
                  <button type="submit" name="submit" class="btn btn-success float-right">
                    Kembali
                    </button>
                
              </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/stakeholder/detail_stakeholder.blade.php ENDPATH**/ ?>