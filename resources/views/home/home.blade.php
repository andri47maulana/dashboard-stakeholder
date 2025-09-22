<?php $public = ""; ?>
@extends('layouts.app')
 
@section('content')
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

</style>
<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
</div>

<!-- DataTales Example -->
<div class="row">
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-primary text-uppercase mb-1">
                            REGION</div>
                        <div class="h1 mb-0 font-weight-bold text-gray-800">8</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-success text-uppercase mb-1">
                            STAKEHOLDER</div>
                        <div class="h1 mb-0 font-weight-bold text-gray-800">{{isset($datastakeholderall)?$datastakeholderall:''}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Pending Requests Card Example -->
    <div class="col-xl-4 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-warning text-uppercase mb-1">
                            KATEGORI</div>
                        <div class="h7 mb-0 font-weight-bold text-gray-800">{{isset($persen_datagovernance)?$persen_datagovernance:''}} % GOVERNANCE</div>
                        <div class="h7 mb-0 font-weight-bold text-gray-800">{{isset($persen_datanongovernance)?$persen_datanongovernance:''}} % NON GOVERNANCE</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DASHBOARD DOKUMEN -->
<!--
<div class="row">
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
            <a href="{{url('/')}}/dokumen/perizinan_expired">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-primary text-uppercase mb-1">
                            PERIZINAN AKAN BERAKHIR</div>
                        <div class="h1 mb-0 font-weight-bold text-gray-800">{{isset($dataperizinanexpired)?$dataperizinanexpired:''}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-calendar fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
            </a>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <a href="{{url('/')}}/dokumen/sertifikasi_expired">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-success text-uppercase mb-1">
                            SERTIFIKASI AKAN BERAKHIR</div>
                        <div class="h1 mb-0 font-weight-bold text-gray-800">{{isset($datasertifikasiexpired)?$datasertifikasiexpired:''}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>



    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-warning shadow h-100 py-2">
            <div class="card-body">
                <a href="{{url('/')}}/dokumen/perjanjiankerjasama_expired">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-warning text-uppercase mb-1">
                            PERJANJIAN KERJASAMA AKAN BERAKHIR</div>
                            <div class="h1 mb-0 font-weight-bold text-gray-800">{{isset($dataperjanjiankerjasamaexpired)?$dataperjanjiankerjasamaexpired:''}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-comments fa-2x text-gray-300"></i>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <a href="{{url('/')}}/dokumen/mou_expired">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text font-weight-bold text-info text-uppercase mb-1">
                            MOU AKAN BERAKHIR</div>
                            <div class="h1 mb-0 font-weight-bold text-gray-800">{{isset($datamouexpired)?$datamouexpired:''}}</div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-box fa-2x text-gray-300"></i>
                    </div>
                </div>
                </a>
            </div>
        </div>
    </div>
</div>
-->

<div class="card shadow mb-4">
    <img src="{{url('/')}}{{$public}}/kebunteh.jpg" style="width: 100%; height: auto;">
    <!-- <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">REGION</h6>
    </div>
    <div class="card-body">
        <p>Informasi total region dalam database stakeholder. Terdapat 8 region yaitu, Region 1, Region 2, Region 3, Region 4, Region 5, Region 6, Region 7 dan Region 8</p>
    </div> -->
</div>
<!-- <div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-success">STAKEHOLDER</h6>
    </div>
    <div class="card-body">
        <p>Informasi total data Instansi/Stakeholder keseluruhan region yang ada dalam database</p>
    </div>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-warning">KATEGORI</h6>
    </div>
    <div class="card-body">
        <p>Informasi total persentase data Stakeholder Governance atau Non Governance dari keseluruhan total data</p>
    </div>
</div> -->



<script>
    $('.nav_sdm').addClass('active');
</script>
@endsection