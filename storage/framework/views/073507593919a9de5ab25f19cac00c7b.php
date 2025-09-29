
<?php $__env->startSection('content'); ?>
    <h1 class="h3 mb-2 text-gray-800"> DETAIL SERTIFIKASI</h1>
    <p class="mb-4">PT Perkebunan Nusantara I</p>     
    <div class="card shadow mb-4">   
        <div class="card-body">    
            <!-- --------------------------------------------------------------------------------------- -->
            <form action="<?php echo e(url('/masterdata/kebun')); ?>" method="get">              
            <?php echo csrf_field(); ?>
                    <style>
                        .form-group {
                            margin-bottom: 0.5rem!important;
                        }
                        .form-control {
                            font-size: 1.1em!important;
                        }
                    </style>
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                        <div class="form-group row">
                                <label for="nama" class="col-sm-3 control-label">Nama</label>
                                <label for="nama" class="col-sm-6 control-label"> : <?php echo e(isset($datauser->nama)?$datauser->nama:''); ?></label>
                            </div>                                
                        </div>
                    </div>
                   
                
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" name="submit" class="btn btn-success float-right">
                    Back
                    </button>
                </div>
              </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/masterdata/detail_sertifikasi.blade.php ENDPATH**/ ?>