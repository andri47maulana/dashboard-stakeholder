
 
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
</style>
<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800">User Management</h1>
    <p class="mb-4">Daftar User Aplikasi TJSL PT Perkebunan Nusantara I.</p>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        
        <div class="card-body">
            <a href="<?php echo e(url('/user/form_user_add')); ?>"><button class="btn btn-primary btn-rounded add_user">
            <i class="fas fa-fw fa-plus"></i> Tambah Data
            </button></a>
            <br><br>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">Nama</th>
                            <th style="text-align:center">Username</th>
                            <th style="text-align:center">Region</th>
                            <th style="text-align:center">Hak Akses</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $dataalluser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($key->name); ?></td>
                            <td style="text-align:center"><?php echo e($key->username); ?></td>
                            <td style="text-align:center"><?php echo e($key->region); ?></td>
                            <td style="text-align:center"><?php echo e($key->hakakses); ?></td>
                            <td style="text-align:center">
                                <a href="<?php echo e(url('/user/form_user_edit')); ?>/<?php echo e($key->id); ?>">
                                <btn class="btn btn-warning btn-sm"><i class="fas fa-fw fa-edit"></i> Edit</btn>
                                </a>
                                <a href="<?php echo e(url('/user/deleteuser')); ?>/<?php echo e($key->id); ?>">
                                <btn class="btn btn-danger btn-sm deletedata"><i class="fas fa-fw fa-trash"></i> Hapus<btn>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>
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
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\laragon\www\dashboard-stakeholder\resources\views/user/user.blade.php ENDPATH**/ ?>