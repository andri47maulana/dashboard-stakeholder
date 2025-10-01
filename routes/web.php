<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StakeholderController;
use App\Http\Controllers\DokumenController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WilayahController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PetaController;
use App\Http\Controllers\DerajatHubunganController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
    // return view('login');
Route::get('/',[AuthController::class,'index'])->name('login');
// });

Route::prefix('login')->group(function(){
    Route::get('/',[AuthController::class,'index'])->name('login');
    Route::post('/func_login',[AuthController::class,'func_login']);
});

Route::get('/func_logout',[AuthController::class,'func_logout']);



//Route::group(['middleware'=>['auth']},function()]
Route::group(['middleware' => 'auth'], function () {
    Route::get('/dashboard', [HomeController::class, 'index']);
    Route::get('/get-data-dashboard', [HomeController::class, 'getRegionalData']);
    Route::get('/get-stakeholder', [HomeController::class, 'getStakeholderByRegion']);
    Route::get('/get-detail-instansi/{id}', [HomeController::class, 'getDetailInstansi']);
    // routes/web.php
    Route::get('/get-wilayah', [WilayahController::class, 'getWilayah']);
    Route::get('/get-kebun-by-region', [StakeholderController::class, 'getKebunByRegion']);

    

    Route::get('/exportstakeholder', [StakeholderController::class, 'exportstakeholder']);
    Route::prefix('user')->group(function(){
        Route::get('/index', [UserController::class, 'index']);
        Route::post('/storeuser', [UserController::class, 'func_storeuser']);
        Route::post('/updateuser', [UserController::class, 'func_updateuser']);
        Route::post('/updatepassword', [UserController::class, 'func_updatepassword']);
        Route::get('/form_user_add', [UserController::class, 'view_form_user']);
        Route::get('/form_user_edit/{id}', [UserController::class, 'view_form_user']);
        Route::get('/deleteuser/{id}', [UserController::class, 'func_deleteuser']);
        
        
    });
    Route::prefix('dash')->group(function(){
        Route::get('/stakeholder', [StakeholderController::class, 'dashstakeholder']);
        Route::post('/storestakeholder', [StakeholderController::class, 'func_storestakeholder']);
        Route::post('/updatestakeholder', [StakeholderController::class, 'func_updatestakeholder']);
        Route::get('/form_stakeholder_add', [StakeholderController::class, 'view_form_stakeholder']);
        Route::get('/form_stakeholder_edit/{id}', [StakeholderController::class, 'view_form_stakeholder']);
        Route::get('/form_stakeholder_detail/{id}', [StakeholderController::class, 'view_detail_stakeholder']);
        Route::get('/deletestakeholder/{id}', [StakeholderController::class, 'func_deletestakeholder']);
        Route::get('/get_data_stakeholder/{id}', [StakeholderController::class, 'get_data_stakeholder'])->name('get_data_stakeholder');
    });
    Route::prefix('dokumen')->group(function(){
        Route::get('/perizinan', [DokumenController::class, 'dashperizinan']);
        Route::get('/perizinan_expired', [DokumenController::class, 'dashperizinan_expired']);
        Route::post('/storeperizinan', [DokumenController::class, 'func_storeperizinan']);
        Route::post('/updateperizinan', [DokumenController::class, 'func_updateperizinan']);
        Route::get('/form_perizinan_add', [DokumenController::class, 'view_form_perizinan']);
        Route::get('/form_perizinan_edit/{id}', [DokumenController::class, 'view_form_perizinan']);
        Route::get('/form_perizinan_detail/{id}', [DokumenController::class, 'view_detail_perizinan']);
        Route::get('/deleteperizinan/{id}', [DokumenController::class, 'func_deleteperizinan']);
        Route::get('/get_data_perizinan/{id}', [DokumenController::class, 'get_data_perizinan'])->name('get_data_perizinan');

        Route::get('/sertifikasi', [DokumenController::class, 'dashsertifikasi']);
        Route::get('/sertifikasi_expired', [DokumenController::class, 'dashsertifikasi_expired']);
        Route::post('/storesertifikasi', [DokumenController::class, 'func_storesertifikasi']);
        Route::post('/updatesertifikasi', [DokumenController::class, 'func_updatesertifikasi']);
        Route::get('/form_sertifikasi_add', [DokumenController::class, 'view_form_sertifikasi']);
        Route::get('/form_sertifikasi_edit/{id}', [DokumenController::class, 'view_form_sertifikasi']);
        Route::get('/form_sertifikasi_detail/{id}', [DokumenController::class, 'view_detail_sertifikasi']);
        Route::get('/deletesertifikasi/{id}', [DokumenController::class, 'func_deletesertifikasi']);
        Route::get('/get_data_sertifikasi/{id}', [DokumenController::class, 'get_data_sertifikasi'])->name('get_data_sertifikasi');

        Route::get('/perjanjiankerjasama', [DokumenController::class, 'dashperjanjiankerjasama']);
        Route::get('/perjanjiankerjasama_expired', [DokumenController::class, 'dashperjanjiankerjasama_expired']);
        Route::post('/storeperjanjiankerjasama', [DokumenController::class, 'func_storeperjanjiankerjasama']);
        Route::post('/updateperjanjiankerjasama', [DokumenController::class, 'func_updateperjanjiankerjasama']);
        Route::get('/form_perjanjiankerjasama_add', [DokumenController::class, 'view_form_perjanjiankerjasama']);
        Route::get('/form_perjanjiankerjasama_edit/{id}', [DokumenController::class, 'view_form_perjanjiankerjasama']);
        Route::get('/form_perjanjiankerjasama_detail/{id}', [DokumenController::class, 'view_detail_perjanjiankerjasama']);
        Route::get('/deleteperjanjiankerjasama/{id}', [DokumenController::class, 'func_deleteperjanjiankerjasama']);
        Route::get('/get_data_perjanjiankerjasama/{id}', [DokumenController::class, 'get_data_perjanjiankerjasama'])->name('get_data_perjanjiankerjasama');

        Route::get('/mou', [DokumenController::class, 'dashmou']);
        Route::get('/mou_expired', [DokumenController::class, 'dashmou_expired']);
        Route::post('/storemou', [DokumenController::class, 'func_storemou']);
        Route::post('/updatemou', [DokumenController::class, 'func_updatemou']);
        Route::get('/form_mou_add', [DokumenController::class, 'view_form_mou']);
        Route::get('/form_mou_edit/{id}', [DokumenController::class, 'view_form_mou']);
        Route::get('/form_mou_detail/{id}', [DokumenController::class, 'view_detail_mou']);
        Route::get('/deletemou/{id}', [DokumenController::class, 'func_deletemou']);
        Route::get('/get_data_mou/{id}', [DokumenController::class, 'get_data_mou'])->name('get_data_mou');
    });
    
    Route::prefix('masterdata')->group(function(){
        Route::get('/kebun', [MasterDataController::class, 'dashkebun']);
        Route::get('/data_kebun', [MasterDataController::class, 'data_kebun'])->name('units.list');
        Route::get('/data_kebun/{id}/detail', [MasterDataController::class, 'detail_unit'])->name('units.detail');
        Route::post('/kebun_json/store', [MasterDataController::class, 'store'])->name('kebun_json.store');
        Route::put('/kebun_json/{id}', [MasterDataController::class, 'update'])->name('kebun_json.update');
        Route::delete('/kebun_json/{id}', [MasterDataController::class, 'destroy'])->name('kebun_json.destroy');


        Route::post('/storekebun', [MasterDataController::class, 'func_storekebun']);
        Route::post('/updatekebun', [MasterDataController::class, 'func_updatekebun']);
        Route::get('/form_kebun_add', [MasterDataController::class, 'view_form_kebun']);
        Route::get('/form_kebun_edit/{id}', [MasterDataController::class, 'view_form_kebun']);
        Route::get('/form_kebun_detail/{id}', [MasterDataController::class, 'view_detail_kebun']);
        Route::get('/deletekebun/{id}', [MasterDataController::class, 'func_deletekebun']);
        Route::get('/get_data_kebun/{id}', [MasterDataController::class, 'get_data_kebun'])->name('get_data_kebun');

        Route::get('/perizinan', [MasterDataController::class, 'dashperizinan']);
        Route::post('/storeperizinan', [MasterDataController::class, 'func_storeperizinan']);
        Route::post('/updateperizinan', [MasterDataController::class, 'func_updateperizinan']);
        Route::get('/form_perizinan_add', [MasterDataController::class, 'view_form_perizinan']);
        Route::get('/form_perizinan_edit/{id}', [MasterDataController::class, 'view_perizinan_kebun']);
        Route::get('/form_perizinan_detail/{id}', [MasterDataController::class, 'view_perizinan_kebun']);
        Route::get('/deleteperizinan/{id}', [MasterDataController::class, 'func_deleteperizinan']);
        Route::get('/get_data_perizinan/{id}', [MasterDataController::class, 'get_data_perizinan'])->name('get_data_perizinan');

        Route::get('/sertifikasi', [MasterDataController::class, 'dashsertifikasi']);
        Route::post('/storesertifikasi', [MasterDataController::class, 'func_storesertifikasi']);
        Route::post('/updatesertifikasi', [MasterDataController::class, 'func_updatesertifikasi']);
        Route::get('/form_sertifikasi_add', [MasterDataController::class, 'view_form_sertifikasi']);
        Route::get('/form_sertifikasi_edit/{id}', [MasterDataController::class, 'view_form_sertifikasi']);
        Route::get('/form_sertifikasi_detail/{id}', [MasterDataController::class, 'view_detail_sertifikasi']);
        Route::get('/deletesertifikasi/{id}', [MasterDataController::class, 'func_deletesertifikasi']);
        Route::get('/get_data_sertifikasi/{id}', [MasterDataController::class, 'get_data_sertifikasi'])->name('get_data_sertifikasi');
    });

    Route::prefix('peta')->group(function(){
        Route::get('/peta', [PetaController::class, 'index']);
        Route::get('/polygons/{unitId}', [PetaController::class, 'getPolygons']);
        Route::get('/peta_region/{region}', [PetaController::class, 'peta_region']);
        Route::get('/peta_region/data/{region}/{tahun}', [PetaController::class,'dataByYear']);
        Route::get('/unit/detail/{unit}/{tahun}', [PetaController::class,'unitDetail']);
    });

    Route::get('/derajat-hubungan', [DerajatHubunganController::class, 'index'])->name('derajat.index');
    Route::post('/derajat-hubungan/store', [DerajatHubunganController::class, 'store'])->name('derajat.store');
    Route::post('/derajat-hubungan/update/{id}', [DerajatHubunganController::class, 'update'])->name('derajat.update');
    Route::delete('/derajat-hubungan/delete/{id}', [DerajatHubunganController::class, 'destroy'])->name('derajat.delete');
    Route::post('/derajat-hubungan/import', [DerajatHubunganController::class, 'import'])->name('derajat.import');
    Route::post('/isu/store', [DerajatHubunganController::class, 'isu_store'])->name('isu.store');
    Route::get('/wilayah/desa', [DerajatHubunganController::class, 'getDesa'])->name('wilayah.desa');
    Route::get('/isu/show/{id}', [DerajatHubunganController::class, 'show'])->name('isu.show');
    Route::put('/isu/update', [DerajatHubunganController::class, 'update_isu'])->name('isu.update');


});

