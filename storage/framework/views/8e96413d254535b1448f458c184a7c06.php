 
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
<h1 class="h3 mb-2 text-gray-800">Master Data Kebun</h1>
    <p class="mb-4">Master Data Kebun PT Perkebunan Nusantara I.</p>
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
            
        </div>
        <div class="card-footer">
            <!-- <a href="<?php echo e(url('/')); ?>/dash/stakeholder"> -->
            <button class="btn btn-outline-warning float-right btn-sm cancelsearch" style="margin-right: 10px;"><i class="fas fa-fw fa-stop"></i> Cancel </button>
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
            <a href="<?php echo e(url('/exportstakeholder')); ?>">
                <button class="btn btn-success btn-rounded add_user">
                <i class="fas fa-fw fa-download"></i> Export
                </button>
            </a>
            
            <br><br>   
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr style='font-size: 0.85em;'>
                            <th style="text-align:center">Kode Plant</th>
                            <th style="text-align:center">Nama Kebun</th>
                            <th style="text-align:center">Regional</th>
                            <th style="text-align:center">Provinsi</th>
                            <th style="text-align:center">Kabupaten</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody style='font-size: 0.85em;'>
                        <?php $i = 1; ?>
                        <?php $__currentLoopData = $dataalluser; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td ><a href="<?php echo e(url('/masterdata/form_kebun_detail')); ?>/<?php echo e($key->id); ?>"><i class="fas fa-fw fa-search"></i></a> <?php echo e($key->kode_plant); ?></td>
                            <td style="text-align:center"><?php echo e($key->nama_kebun); ?></td>
                            <td style="text-align:center"><?php echo e($key->regional); ?></td>
                            <td style="text-align:center"><?php echo e($key->provinsi); ?></td>
                            <td style="text-align:center"><?php echo e($key->kabupaten); ?></td>
                            <td style="text-align:center" width="100">
                                <btn class="btn btn-warning btn-sm editdata" id="<?php echo e($key->id); ?>"><i class="fas fa-fw fa-edit"></i></btn>
                                <a href="<?php echo e(url('/masterdata/deletekebun')); ?>/<?php echo e($key->id); ?>">
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
            <h1 class="h3 mb-2 text-gray-800"> Tambah Data Kebun</h1>
            <br>
            <form action="<?php echo e(url('/masterdata/storekebun')); ?>" method="post">
            <?php echo csrf_field(); ?>
            
                        
                    <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="kode_plant" class="col-sm-3 control-label">Kode Plant</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kode_plant" name="kode_plant" aria-describedby="kode_plant" value=""
                                    placeholder="Kode Plant..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nama_kebun" class="col-sm-3 control-label">Nama Kebun</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama_kebun" name="nama_kebun" aria-describedby="nama_kebun" value=""
                                    placeholder="Nama Kebun..." required>
                            </div>
                            <?php if(Auth::user()->hakakses =='Admin'): ?>
                            <div class="form-group row">
                                <label for="region" class="col-md-3 control-label">Regional</label>
                                <select name="region" id="regionclass" class="col-sm-6 form-control" required>
                                
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
                                <label for="region" class="col-md-3 control-label">Regional</label>
                                <select name="region" id="region" class="col-sm-6 form-control" required>
                                    <option value="<?php echo e(Auth::user()->region); ?>"><?php echo e(Auth::user()->region); ?></option>
                                </select>
                            </div>
                        <?php endif; ?>
                            <div class="form-group row">
                                <label for="provinsi" class="col-sm-3 control-label">Provinsi</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="provinsi" name="provinsi" aria-describedby="provinsi" value=""
                                    placeholder="Nama Provinsi..." required>
                            </div>
                            <div class="form-group row">
                                <label for="kabupaten" class="col-sm-3 control-label">Kabupaten</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kabupaten" name="kabupaten" aria-describedby="kabupaten" value=""
                                    placeholder="Nama Kabupaten..." required>
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
            <h1 class="h3 mb-2 text-gray-800"> Edit Data Kebun</h1>
            <br>
            
            <form action="<?php echo e(url('/masterdata/updatekebun')); ?>" method="post">
                <input type="hidden" name="id" id="idkebun" required="required" 
                    value="">
            <?php echo csrf_field(); ?>
            
            <div class="row" style="font-size: 0.85em;">
                        <div class="col-md-6">
                            <div class="form-group row">
                                <label for="kode_plant" class="col-sm-3 control-label">Kode Plant</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kode_plant" name="kode_plant" aria-describedby="kode_plant" value=""
                                    placeholder="Kode Plant..." required>
                            </div>
                            <div class="form-group row">
                                <label for="nama_kebun" class="col-sm-3 control-label">Nama Kebun</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="nama_kebun" name="nama_kebun" aria-describedby="nama_kebun" value=""
                                    placeholder="Nama Kebun..." required>
                            </div>
                            <?php if(Auth::user()->hakakses =='Admin'): ?>
                            <div class="form-group row">
                                <label for="regionclass" class="col-md-3 control-label">Regional</label>
                                <select name="regionclass" id="regionclass" class="col-sm-6 form-control" required>
                                
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
                                <label for="regionclass" class="col-md-3 control-label">Regional</label>
                                <select name="regionclass" id="regionclass" class="col-sm-6 form-control" required>
                                    <option value="<?php echo e(Auth::user()->region); ?>"><?php echo e(Auth::user()->region); ?></option>
                                </select>
                            </div>
                        <?php endif; ?>
                            <div class="form-group row">
                                <label for="provinsi" class="col-sm-3 control-label">Provinsi</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="provinsi" name="provinsi" aria-describedby="provinsi" value=""
                                    placeholder="Nama Provinsi..." required>
                            </div>
                            <div class="form-group row">
                                <label for="kabupaten" class="col-sm-3 control-label">Kabupaten</label>
                                <input type="text" class="col-sm-6 form-control form-control-user"
                                    id="kabupaten" name="kabupaten" aria-describedby="kabupaten" value=""
                                    placeholder="Nama Kabupaten..." required>
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
            url: "<?php echo e(url('/masterdata/get_data_kebun')); ?>/"+id,
            type: "GET",
            dataType: "json",
            success: function(response) {
            // Populate the modal with the data from the response
            // For example, if you have an input field with id "name" in the modal:
                
                $('#idkebun').val(id);
                $('#kode_plant').val(response.kode_plant);
                $('#nama_kebun').val(response.nama_kebun);
                $('#regionclass').val(response.region);
                $('#provinsi').val(response.provinsi);
                $('#kabupaten').val(response.kabupaten);
                $('#editdataModal').modal('show');
            // Repeat this for other fields you want to populate in the modal
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText);
            }
        });
    });
    

    $("#region, #kebun").select2({});
    $('.cari').click(function(){
        var region = $('#region').find(":selected").val();

        $.cookie("region", region, { expires : 3600 });
        location.reload();
    });
    $('.cancelsearch').click(function(){
        $.cookie("region", "", { expires : 3600 });
        location.reload();
    })
    // $('.nav_sdm').addClass('active');
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\APP\dashboard-stakeholder\resources\views/masterdata/kebun.blade.php ENDPATH**/ ?>