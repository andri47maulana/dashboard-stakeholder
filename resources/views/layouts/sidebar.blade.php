<?php $public = ""; ?>
<!-- Move the inline styles to a separate CSS file or <style> block -->
<style>
    .fixedstyle {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 9;
    }
    #content-wrapper {
        margin-left: 14rem;
    }
    .bg-gradient-primarys {
        background-color: #fff;
        background-image: linear-gradient(180deg, #fff 10%, #fff 100%);
        background-size: cover;
    }
    .sidebar-dark .nav-item .nav-link, .sidebar-dark .nav-item .nav-link i, .sidebar-dark .sidebar-brand {
        color: #000000!important;
    }
    .sidebar-dark .nav-item .nav-link:hover, .sidebar-dark .nav-item .nav-link i:hover {
        color: #1cc88a!important;
    }
    .sidebar-dark .nav-item.active .nav-link, .sidebar-dark .nav-item.active .nav-link i {
        color: #1cc88a!important;
    }
    .sidebar-dark hr.sidebar-divider {
        border-top: 1px solid rgba(0, 0, 0, .15);
        margin-bottom: 0;
    }
    .topbar {
        /* height: 4.375rem; */
        position: fixed;
        right: 0;
        left: 0;
        top: 0;
        z-index: 1;
    }
    .container-fluid {
        margin-top: 5.5rem;
    }
</style>
<ul class="fixedstyle navbar-nav bg-gradient-primarys sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{url('/')}}/">
        <div class="sidebar-brand-icon" style="width: 30%;">
            <img src="{{url('/')}}{{$public}}/ptpn.png" alt="Logo" style="width: 100%;">
        </div>
        <div class="sidebar-brand-text">
            PTPN 1
        </div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item <?php echo Request::is('dashboard') ? 'active' : ''; ?>">
        <a class="nav-link" href="{{url('/')}}/dashboard">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo Request::is('dash/stakeholder') ? 'active' : ''; ?>">
        <a class="nav-link" href="{{url('/')}}/dash/stakeholder">
            <i class="fas fa-fw fa-book"></i>
            <span>Stakeholder</span>
        </a>
    </li>
    <!-- Divider 
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
        aria-expanded="true" aria-controls="collapsePages">
        <i class="fas fa-fw fa-box"></i>
        <span>Dokumen</span>
        </a>
        <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{url('/')}}/dokumen/perizinan"><i class="fas fa-fw fa-file"></i> Perizinan</a>
                <a class="collapse-item" href="{{url('/')}}/dokumen/sertifikasi"><i class="fas fa-fw fa-certificate"></i> Sertifikasi</a>
                <a class="collapse-item" href="{{url('/')}}/dokumen/perjanjiankerjasama"><i class="fas fa-fw fa-table"></i> Perjanjian Kerjasama</a>
                <a class="collapse-item" href="{{url('/')}}/dokumen/mou"><i class="fas fa-fw fa-newspaper"></i> Nota Kesepahaman</a>
            </div>
        </div>
    </li>           
    -->
    <!-- Divider -->
    @if(Auth::user()->hakakses =='Admin')
    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMasterData"
        aria-expanded="true" aria-controls="collapseMasterData">
        <i class="fas fa-fw fa-bars"></i>
        <span>Master Data</span>
        </a>
        <div id="collapseMasterData" class="collapse" aria-labelledby="headingMasterData" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item" href="{{url('/')}}/masterdata/kebun"><i class="fas fa-fw fa-map-pin"></i>Kebun</a>
                <a class="collapse-item" href="{{url('/')}}/masterdata/perizinan"><i class="fas fa-fw fa-list"></i>Daftar Perizinan</a>
                <a class="collapse-item" href="{{url('/')}}/masterdata/sertifikasi"><i class="fas fa-fw fa-paperclip"></i>Daftar Sertifikasi</a>
            </div>
        </div>
    </li>       

    <hr class="sidebar-divider">
    <li class="nav-item <?php echo Request::is('user/index') ? 'active' : ''; ?>">
        <a class="nav-link" href="{{url('/')}}/user/index">
            <i class="fas fa-users"></i>
            <span>User Management</span>
        </a>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item <?php echo Request::is('func_logout') ? 'active' : ''; ?>">
        <a class="nav-link" href="{{url('/')}}/func_logout">
            <i class="fas fa-sign-out-alt"></i>
            <span>Keluar</span>
        </a>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->

    <!-- Nav Item - Utilities Collapse Menu -->


    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->


    <!-- Nav Item - Pages Collapse Menu -->


    <!-- Nav Item - Charts -->


    <!-- Nav Item - Tables -->


    <!-- Divider -->

    <!-- Sidebar Message -->
    <?php 
        $membercount = DB::table('users')
        ->where('hakakses','like','%member%')
        ->count();
        $admincount = DB::table('users')
        ->where('hakakses','like','%admin%')
        ->count();
    ?>
    <div class="sidebar-card d-none d-lg-flex" style="margin-top: 1rem; background-color: rgb(41 236 58 / 38%);">
        <p class="text-center mb-2" style="font-size:1.1em; color:#000; margin-bottom: 0 !important;"><strong >Team Member<br>{{$membercount}}</strong></p>
    </div>
    @if(Auth::user()->hakakses =='Admin')
    <div class="sidebar-card d-none d-lg-flex" style="background-color: rgb(229 41 236 / 38%);">
        <p class="text-center mb-2" style="font-size:1.1em; color:#000; margin-bottom: 0 !important;"><strong >Admin<br>{{$admincount}}</strong></p>
    </div>
    @endif
</ul>