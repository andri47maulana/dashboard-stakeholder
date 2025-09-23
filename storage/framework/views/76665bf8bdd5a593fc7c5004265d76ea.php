
<!DOCTYPE html>
<html lang="en">
    <!-- Asset path for Laravel development server -->
<?php $public = ""; ?>
<head>    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dashboard Stakeholder - Login</title>

    <!-- Custom fonts for this template-->
    <link href="<?php echo e(url('/')); ?><?php echo e($public); ?>/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?php echo e(url('/')); ?><?php echo e($public); ?>/css/sb-admin-2.min.css" rel="stylesheet">

</head>
<style>
    
    .field-icon {
        float: right;
        /* margin-left: 12%; */
        margin-top: -9%;
        position: relative;
        z-index: 2;
        left: -6%;
        cursor:pointer;
    }
</style>

<body class="bg-gradient-primary" style="background-image: url(<?php echo e(url('/')); ?><?php echo e($public); ?>/kebunteh.jpg);">
    <div class="container" >

        <!-- Outer Row -->
        <div class="row justify-content-center">

            <div class="col-xl-10 col-lg-12 col-md-9">

                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block" style="text-align:center">
                                <img src="<?php echo e(url('/')); ?><?php echo e($public); ?>/ptpn.png" style="width: 60%; height: auto; margin-top: 20%;">
                            </div>
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Dashboard Stakeholder</h1>
                                    </div>
                                    <form class="user" action = "<?php echo e(url('/login/func_login')); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-user"
                                                name="username" id="username" aria-describedby="emailHelp"
                                                placeholder="Masukkan Username...">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" class="form-control form-control-user"
                                            name="password" id="password" placeholder="Masukkan Password...">
                                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"></span>
                                        </div>
                                        <div class="form-group">
                                            <div class="custom-control custom-checkbox small">
                                                <input type="checkbox" class="custom-control-input" id="customCheck">
                                                <label class="custom-control-label" for="customCheck">Tetap Masuk</label>
                                            </div>
                                        </div>
                                        <button class="btn btn-primary btn-user btn-block">
                                            Masuk
                                        </button>
                                        <hr>
                                        <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="text-danger"><?php echo e($message); ?></span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        <?php if(session()->get('error')): ?>
                                        <span class="text-danger"><?php echo e(session()->get('error')); ?></span>
                                        <?php endif; ?>
                                    </form>
                                    <!-- <hr> -->
                                    <br>
                                    <br>
                                    <!-- <div class="text-center">
                                        <a class="small" href="forgot-password.html">Forgot Password?</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="register.html">Create an Account!</a>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo e(url('/')); ?><?php echo e($public); ?>/vendor/jquery/jquery.min.js"></script>
    <script src="<?php echo e(url('/')); ?><?php echo e($public); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="<?php echo e(url('/')); ?><?php echo e($public); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="<?php echo e(url('/')); ?><?php echo e($public); ?>/js/sb-admin-2.min.js"></script>
    <script>
        $(".toggle-password").click(function() {

          $(this).toggleClass("fa-eye fa-eye-slash");
          var input = $($(this).attr("toggle"));
          if (input.attr("type") == "password") {
            input.attr("type", "text");
          } else {
            input.attr("type", "password");
          }
        });
    </script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script>    
    <?php if(session('gagal')): ?>
        Swal.fire({
            title: "Gagal Login",
            text: "<?php echo e(session('gagal')); ?>",
            icon: "error"
        });
    <?php endif; ?>
    </script>
</body>

</html><?php /**PATH D:\APP\dashboard-stakeholder\resources\views/login/index.blade.php ENDPATH**/ ?>