<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProposalController;
use App\Http\Controllers\LaporanController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('admin/register', [AuthController::class, 'adminRegister']);
Route::post('admin/login',[AuthController::class, 'adminLogin'])->name('adminLogin');
Route::post('user/register', [AuthController::class, 'userRegister']);
Route::post('user/login',[AuthController::class, 'userLogin'])->name('userLogin');
Route::post('perusahaan/register', [AuthController::class, 'perusahaanRegister']);
Route::post('perusahaan/login',[AuthController::class, 'perusahaanLogin'])->name('perusahaanLogin');

Route::get('/export', [ProposalController::class, 'export_excel']);
Route::get('/exportditerima', [ProposalController::class, 'export_proposal_diterima']);
Route::get('/exportditolak', [ProposalController::class, 'export_proposal_ditolak']);
Route::get('/exportlaporan', [LaporanController::class, 'export_excel']);






// GET CURRENT USER
Route::middleware('auth:user-api')->get('currentUser', [AuthController::class, 'currentUser']);

// GET CURRENT USER PERUSAHAAN
Route::middleware('auth:perusahaan-api')->get('currentUserPerusahaan', [AuthController::class, 'currentUserPerusahaan']);

// GET CURRENT USER PERUSAHAAN
Route::middleware('auth:admin-api')->get('currentUserAdmin', [AuthController::class, 'currentUserAdmin']);

//USER (DINAS)
Route::group(['prefix' => 'user', 'middleware' => ['auth:user-api']], function () {

    // ADMIN
    Route::get('dashboard',[AuthController::class, 'userDashboard'])->name('userDashboard');
     Route::get('dashboardperusahaan',[AuthController::class, 'perusahaanDashboard'])->name('perusahaanDashboard');
    Route::delete('hapususer/{id}', [AuthController::class, 'userDestroy']);
    Route::post('edituser/{id}', [AuthController::class, 'userEdit']);
    Route::get('detailuser/{id}', [AuthController::class, 'userDetail']);
    // Proposal
    Route::get('/getproposal', [ProposalController::class, 'getProposal']);
    Route::post('/proposal', [ProposalController::class, 'store']);
    Route::post('/proposal/{id}', [ProposalController::class, 'update']);
    Route::delete('/proposal/{id}', [ProposalController::class, 'destroy']);
    Route::middleware('auth:user-api')->get('/roleproposal', [ProposalController::class, 'showProposalRole']);
    Route::middleware('auth:user-api')->get('/roleproposaldashboard', [ProposalController::class, 'showProposalRoleDashboard']);
    Route::get('/countallproposaldashboard', [ProposalController::class, 'countDataAllDashboard']);
    Route::get('/countidonedashboard', [ProposalController::class, 'countDatabyStatusOneDashboard']);
    Route::get('/countidtwodashboard', [ProposalController::class, 'countDatabyStatusTwoDashboard']);
    Route::get('/countidthreedashboard', [ProposalController::class, 'countDatabyStatusThreeDashboard']);
    Route::get('/countidfourdashboard', [ProposalController::class, 'countDatabyStatusFourDashboard']);
    Route::get('/countidfivedashboard', [ProposalController::class, 'countDatabyStatusFiveDashboard']);
    // Laporan

    Route::get('/getlaporan', [LaporanController::class, 'getLaporan']);
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::post('/editlaporan/{id}', [LaporanController::class, 'update']);
    Route::middleware('auth:user-api')->get('/rolelaporan', [LaporanController::class, 'showLaporanRole']);
    Route::get('/countalllaporandashboard', [LaporanController::class, 'countDataAllDashboard']);
    Route::get('/countidonelaporandashboard', [LaporanController::class, 'countDatabyStatusOneDashboard']);
    Route::get('/countidtwolaporandashboard', [LaporanController::class, 'countDatabyStatusTwoDashboard']);
    Route::get('/countidthreelaporandashboard', [LaporanController::class, 'countDatabyStatusThreeDashboard']);
    Route::get('/editlaporan/{id}', [LaporanController::class, 'showLaporan']);
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']);
});

// PERUSAHAAN
Route::group(['prefix' => 'perusahaan', 'middleware' => ['auth:perusahaan-api']], function () {

    // ADMIN
    Route::get('dashboard',[AuthController::class, 'perusahaanDashboard'])->name('perusahaanDashboard');
    Route::get('dashboarduser',[AuthController::class, 'userDashboard'])->name('userDashboard');
    Route::get('dashboardperusahaan',[AuthController::class, 'perusahaanDashboard'])->name('perusahaanDashboard');
    Route::delete('hapusperusahaan/{id}', [AuthController::class, 'perusahaanDestroy']);
    Route::post('editperusahaan/{id}', [AuthController::class, 'perusahaanEdit']);

     // Proposal
      Route::middleware('auth:perusahaan-api')->get('/roleproposalperusahaan', [ProposalController::class, 'showProposalRolePerusahaan']);
    Route::middleware('auth:perusahaan-api')->get('/roleproposaldashboardperusahaan', [ProposalController::class, 'showProposalRoleDashboardPerusahaan']);
    Route::get('/countallproposaldashboardperusahaan', [ProposalController::class, 'countDataAllDashboardPerusahaan']);
    Route::get('/countidonedashboardperusahaan', [ProposalController::class, 'countDatabyStatusOneDashboardPerusahaan']);
    Route::get('/countidtwodashboardperusahaan', [ProposalController::class, 'countDatabyStatusTwoDashboardPerusahaan']);
    Route::get('/countidthreedashboardperusahaan', [ProposalController::class, 'countDatabyStatusThreeDashboardPerusahaan']);
    Route::get('/countidfourdashboardperusahaan', [ProposalController::class, 'countDatabyStatusFourDashboardPerusahaan']);
    Route::get('/countidfivedashboardperusahaan', [ProposalController::class, 'countDatabyStatusFiveDashboardPerusahaan']);
    Route::get('/getproposal', [ProposalController::class, 'getProposal']);
    Route::post('/proposal', [ProposalController::class, 'store']);
    Route::post('/proposal/{id}', [ProposalController::class, 'updateperusahaan']);
    Route::get('/getterimaproposal', [ProposalController::class, 'getTerima']);
    Route::get('/getproposalditerima', [ProposalController::class, 'getTerimaProposal']);
    Route::middleware('auth:perusahaan-api')->get('/roleproposalditerimaperusahaan', [ProposalController::class, 'showProposalDiterimaRolePerusahaan']);
    Route::get('/proposal/{id}', [ProposalController::class, 'showProposal']);
    Route::delete('/proposal/{id}', [ProposalController::class, 'destroy']);

     // Laporan
    Route::middleware('auth:perusahaan-api')->get('/rolelaporanperusahaan', [LaporanController::class, 'showLaporanRolePerusahaan']);
    Route::middleware('auth:perusahaan-api')->get('/rolelaporanditerimaperusahaan', [LaporanController::class, 'showLaporanDiterimaRolePerusahaan']);
    Route::middleware('auth:perusahaan-api')->get('/rolelaporanditolakperusahaan', [LaporanController::class, 'showLaporanDitolakRolePerusahaan']);
    Route::get('/countalllaporandashboardperusahaan', [LaporanController::class, 'countDataAllDashboardPerusahaan']);
    Route::get('/countidonelaporandashboardperusahaan', [LaporanController::class, 'countDatabyStatusOneDashboardPerusahaan']);
    Route::get('/countidtwolaporandashboardperusahaan', [LaporanController::class, 'countDatabyStatusTwoDashboardPerusahaan']);
    Route::get('/countidthreelaporandashboardperusahaan', [LaporanController::class, 'countDatabyStatusThreeDashboardPerusahaan']);
    Route::get('/getlaporan', [LaporanController::class, 'getLaporan']);
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::post('/laporan/{id}', [LaporanController::class, 'update']);
    Route::post('/laporantolak/{id}', [LaporanController::class, 'updatetolak']);
    Route::get('/getterima', [LaporanController::class, 'getTerima']);
    Route::get('/getlaporanditerima', [LaporanController::class, 'getStatusTwo']);
    Route::get('/laporan/{id}', [LaporanController::class, 'showLaporan']);
    Route::get('/getbystatus', [LaporanController::class, 'getStatus']);
    Route::delete('laporan/{id}', [LaporanController::class, 'destroy']);
});


// ADMIN
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin-api']], function () {

    // ADMIN
    Route::get('dashboard',[AuthController::class, 'adminDashboard'])->name('adminDashboard');
    Route::get('dashboarduser',[AuthController::class, 'userDashboard'])->name('userDashboard');
    Route::get('/getadmin', [AuthController::class, 'adminDashboard']);
    Route::delete('hapusadmin/{id}', [AuthController::class, 'adminDestroy']);
    Route::post('editadmin/{id}', [AuthController::class, 'adminEdit']);
    Route::get('editadmin/{id}', [AuthController::class, 'adminShow']);
    Route::post('/addadmin', [AuthController::class, 'adminRegister']);

     // Proposal
    Route::get('/getproposal', [ProposalController::class, 'getProposal']);
    Route::post('/proposal', [ProposalController::class, 'store']);

    Route::post('/proposal/{id}', [ProposalController::class, 'update']);
    Route::get('/proposal/{id}', [ProposalController::class, 'showProposal']);
    Route::get('/getbystatus', [ProposalController::class, 'getStatus']);
    Route::get('/gettolak', [ProposalController::class, 'getTolak']);
    Route::get('/getterimaproposal', [ProposalController::class, 'getTerimaProposal']);
    Route::delete('/proposal/{id}', [ProposalController::class, 'destroy']);
    Route::get('/countallproposal', [ProposalController::class, 'countDataAll']);
    Route::get('/countidone', [ProposalController::class, 'countDatabyStatusOne']);
    Route::get('/countidtwo', [ProposalController::class, 'countDatabyStatusTwo']);
    Route::get('/countidthree', [ProposalController::class, 'countDatabyStatusThree']);
    Route::get('/countidfour', [ProposalController::class, 'countDatabyStatusFour']);
    Route::get('/countidfive', [ProposalController::class, 'countDatabyStatusFive']);

      // Laporan
    Route::get('/getlaporan', [LaporanController::class, 'getLaporan']);
    Route::post('/laporan', [LaporanController::class, 'store']);
    Route::post('/laporan/{id}', [LaporanController::class, 'update']);
    Route::delete('/laporan/{id}', [LaporanController::class, 'destroy']);
    Route::get('/countalllaporan', [LaporanController::class, 'countDataAll']);
    Route::get('/countidonelaporan', [LaporanController::class, 'countDatabyStatusOne']);
    Route::get('/countidtwolaporan', [LaporanController::class, 'countDatabyStatusTwo']);
    Route::get('/countidthreelaporan', [LaporanController::class, 'countDatabyStatusThree']);

    // CRUD USER
    Route::get('/getuser', [AuthController::class, 'userDashboard']);
    Route::post('/adduser', [AuthController::class, 'userRegister']);
    Route::post('/edituser/{id}', [AuthController::class, 'userEdit']);
    Route::get('/edituser/{id}', [AuthController::class, 'userShow']);
    Route::delete('/user/{id}', [AuthController::class, 'userDestroy']);

    // CRUD PERUSAHAAN
    Route::get('/getperusahaan', [AuthController::class, 'perusahaanDashboard']);
    Route::post('/addperusahaan', [AuthController::class, 'perusahaanRegister']);
    Route::post('/editperusahaan/{id}', [AuthController::class,  'perusahaanEdit']);
    Route::get('/editperusahaan/{id}', [AuthController::class, 'perusahaanShow']);
    Route::delete('/perusahaan/{id}', [AuthController::class, 'perusahaanDestroy']);


});


