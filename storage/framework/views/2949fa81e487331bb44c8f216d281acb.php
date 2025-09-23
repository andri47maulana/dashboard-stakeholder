 
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
<h1 class="h3 mb-2 text-gray-800">Master Data Sertifikasi</h1>
    <p class="mb-4">Master Data Sertifikasi PT Perkebunan Nusantara I.</p>
    
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
            <a href="<?php echo e(url('/exportsertifikasi')); ?>">
                <button class="btn btn-success btn-rounded add_user">
                <i class="fas fa-fw fa-download"></i> Export
                </button>
            </a>
            
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">Nama</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $dataalluser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td ><a href="<?php echo e(url('/masterdata/form_sertifikasi_detail')); ?>/<?php echo e($key->id); ?>"><i class="fas fa-fw fa-search"></i></a> <?php echo e($key->nama); ?></td>
                            <td style="text-align:center" width="100">
                                <btn class="btn btn-warning btn-sm editdata" id="<?php echo e($key->id); ?>"><i class="fas fa-fw fa-edit"></i></btn>
                                <a href="<?php echo e(url('/masterdata/deletesertifikasi')); ?>/<?php echo e($key->id); ?>">
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
            <h1 class="h3 mb-2 text-gray-800"> Tambah Data Sertifikasi</h1>
            <br>
            <form action="<?php echo e(url('/masterdata/storesertifikasi')); ?>" method="post">
            <?php echo csrf_field(); ?>
            
                        
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 control-label">Nama</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama" name="nama" aria-describedby="nama" value=""
                                    placeholder="Nama..." required>
                            </div>                              
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-primary float-right"><i class="fas fa-fw fa-plus"></i> Submit </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <div class="modaleditdata">
        <div class="card shadow mb-4 modal modalpopup" id="editdataModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" style="padding:15px;">   
            <div class="card-body ">  
            <h1 class="h3 mb-2 text-gray-800"> Edit Data Sertifikasi</h1>
            <br>
            
            <form action="<?php echo e(url('/masterdata/updatesertifikasi')); ?>" method="post">
                <input type="hidden" name="id" id="idsertifikasi" required="required" 
                    value="">
            <?php echo csrf_field(); ?>
            
            <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="nama" class="col-sm-3 control-label">Nama</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama" name="nama" aria-describedby="nama" value=""
                                    placeholder="Nama..." required>
                            </div>                                                       
                        </div>
                    </div>
                    
                    <!-- /.card-body -->
                    <div class="card-footer modal-footer">
                        <button type="button" class="btn btn-secondary float-right" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" class="btn btn-success float-right"><i class="fas fa-fw fa-edit"></i> Update </button>
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
        console.log(modaladddata);
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
            url: "<?php echo e(url('/masterdata/get_data_sertifikasi')); ?>/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                
                $('#idsertifikasi').val(id);
                $('#nama').val(response.nama);
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    $("#nama").select2({});
    $('.cari').click(function(){
        var nama = $('#nama').find(":selected").val();

        $.cookie("nama", region, { expires : 3600 });
        location.reload();
    });
    $('.cancelsearch').click(function(){
        $.cookie("nama", "", { expires : 3600 });
        location.reload();
    })
    // $('.nav_sdm').addClass('active');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\APP\dashboard-stakeholder\resources\views/masterdata/sertifikasi.blade.php ENDPATH**/ ?>