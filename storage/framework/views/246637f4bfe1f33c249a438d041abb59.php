
<?php $__env->startSection('content'); ?>
<?php if(isset($datauser->id)): ?>
    <h1 class="h3 mb-2 text-gray-800"> EDIT DATA USER</h1>
    <?php else: ?>
    <h1 class="h3 mb-2 text-gray-800"> TAMBAH DATA USER</h1>
    <?php endif; ?>
    <p class="mb-4">PT Perkebunan Nusantara I</p> 
    <div class="modaladddata">       
        <div class="card shadow mb-4">   
        <div class="card-body">     
            <!-- --------------------------------------------------------------------------------------- -->
        <?php if(isset($datauser->id)): ?>
            <form action="<?php echo e(url('/user/updateuser')); ?>" method="post">
                <input type="hidden" name="id" required="required" 
                value="<?php if(isset($datauser->id)): ?><?php echo e($datauser->id); ?><?php endif; ?>">
                <?php else: ?>
            <form action="<?php echo e(url('/user/storeuser')); ?>" method="post">
        <?php endif; ?>
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
                            <label for="name" class="col-sm-3 control-label">Nama</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="name" name="name" aria-describedby="name" value="<?php echo e(isset($datauser->name)?$datauser->name:''); ?>"
                                placeholder="Nama User">
                        </div>
                        <div class="form-group row">
                            <label for="username" class="col-sm-3 control-label">Username</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="username" name="username" aria-describedby="username" value="<?php echo e(isset($datauser->username)?$datauser->username:''); ?>"
                                placeholder="Username...">
                        </div>
                        <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Region</label>
                                <select name="region" id="region" class="col-sm-6 form-control">
                                <?php if(isset($datauser->region)): ?>
                                
                                   
                                    <option value="PTPN I HO" <?php if($datauser->region=="PTPN I HO"): ?> selected <?php endif; ?>>PTPN I HO</option>
                                    <option value="PTPN I Regional 1" <?php if($datauser->region=="PTPN I Regional 1"): ?> selected <?php endif; ?>>PTPN I Regional 1</option>
                                    <option value="PTPN I Regional 2" <?php if($datauser->region=="PTPN I Regional 2"): ?> selected <?php endif; ?>>PTPN I Regional 2</option>
                                    <option value="PTPN I Regional 3" <?php if($datauser->region=="PTPN I Regional 3"): ?> selected <?php endif; ?>>PTPN I Regional 3</option>
                                    <option value="PTPN I Regional 4" <?php if($datauser->region=="PTPN I Regional 4"): ?> selected <?php endif; ?>>PTPN I Regional 4</option>
                                    <option value="PTPN I Regional 5" <?php if($datauser->region=="PTPN I Regional 5"): ?> selected <?php endif; ?>>PTPN I Regional 5</option>
                                    <option value="PTPN I Regional 6" <?php if($datauser->region=="PTPN I Regional 6"): ?> selected <?php endif; ?>>PTPN I Regional 6</option>
                                    <option value="PTPN I Regional 7" <?php if($datauser->region=="PTPN I Regional 7"): ?> selected <?php endif; ?>>PTPN I Regional 7</option>
                                    <option value="PTPN I Regional 8" <?php if($datauser->region=="PTPN I Regional 8"): ?> selected <?php endif; ?>>PTPN I Regional 8</option>
                                <?php else: ?>
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
                        <div class="form-group row">
                            <label for="hakakses" class="col-md-3 control-label">Hak Akses</label>
                            <select name="hakakses" id="hakakses" class="col-sm-6 form-control">
                            <?php if(isset($datauser->hakakses)): ?>
                                <option value="Admin" <?php if($datauser->hakakses=="Admin"): ?> selected <?php endif; ?>>Admin</option>
                                <option value="Member" <?php if($datauser->hakakses=="Member"): ?> selected <?php endif; ?>>Member</option>
                            <?php else: ?>
                                <option value="Admin">Admin</option>
                                <option value="Member">Member</option>
                            <?php endif; ?>                                    
                            </select>
                        </div>                           
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  
                    <?php if(isset($datauser->id)): ?>
                    <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Update </button>
                    <?php else: ?>
                    <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Submit </button>
                    <?php endif; ?>
                    
                </div>
              </form>
        </div>
        </div>
    </div>
    <?php if(isset($datauser->id)): ?>
    <div class="modalupdatepassword">
        <div class="card shadow mb-4">   
        <div class="card-body">     
            <!-- --------------------------------------------------------------------------------------- -->
        
            <h3>Ubah Password</h3>
            <form action="<?php echo e(url('/user/updatepassword')); ?>" method="post">
                <input type="hidden" name="id" required="required" 
                value="<?php if(isset($datauser->id)): ?><?php echo e($datauser->id); ?><?php endif; ?>">
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
                            <label for="password" class="col-sm-3 control-label">Password</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="password" name="password" aria-describedby="password" value=""
                                placeholder="Password...">
                        </div>
                        <div class="form-group row">
                            <label for="password" class="col-sm-3 control-label">re-Password</label>
                            <input type="text" class="col-sm-6 form-control form-control-user"
                                id="password2" name="password2" aria-describedby="password2" value=""
                                placeholder="re-Password...">
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  
                    <?php if(isset($datauser->id)): ?>
                    <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Update </button>
                    <?php else: ?>
                    <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Submit </button>
                    <?php endif; ?>
                    
                </div>
              </form>
        
        </div>
        </div>
    </div>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
    //var modaladddata = $('.modaladddata').detach();
    //var modaleditdata = $('.modalupdatepassword').detach();

    <?php if($errors->any()): ?>
        Swal.fire({
            title: "Error",
            text: "<?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><?php echo e($error); ?>,<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>",
            icon: "error"
        });
        
    <?php endif; ?>
    <?php if(session('suksesdelete')): ?>
        Swal.fire({
            title: "Deleted!",
            text: "Your file has been deleted.",
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
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = href;
            }
        });
    });
    </script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/user/form_user.blade.php ENDPATH**/ ?>