<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\HomeController;

use App\Http\Controllers\Admin\CameraReportController;
use App\Http\Controllers\Admin\UsuarioController;
use App\Http\Controllers\Admin\LogoutController;
use App\Http\Controllers\Admin\ClientCategoryController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\CurrentAccountController;
use App\Http\Controllers\Admin\DebtsController;
use App\Http\Controllers\Admin\CartaPorteController;
use App\Http\Controllers\Admin\CostCenterController;
use App\Http\Controllers\Admin\DownloadTicketController;
use App\Http\Controllers\Admin\GrainCategoryController;
use App\Http\Controllers\Admin\GrainController;
use App\Http\Controllers\Admin\GrainParamsController;
use App\Http\Controllers\Admin\GrainPercentageController;
use App\Http\Controllers\Admin\LogController;
use App\Http\Controllers\Admin\PaymentController;
use App\Http\Controllers\Admin\ProviderController;
use App\Http\Controllers\Admin\RateController;
use App\Http\Controllers\Admin\InvoiceController;

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
Auth::routes();

Route::get('/', function () {
    return redirect('admin/home');
})->name('home');
Route::get('/admin', function () {
    return redirect('admin/home');
})->name('home');
Route::get('/register', function () {
    return redirect('/admin');
})->name('home');

Route::name('admin.')->prefix('admin')->middleware('auth')->group( function () {
    Route::get('logout', [LogoutController::class, 'index'])->name('admin/logout');
});

Route::name('admin.')->prefix('admin')->middleware(['auth', 'activo'])->group( function () {
    Route::get('home', [HomeController::class, 'index'])->name('admin/home');
    Route::get('logs', [LogController::class, 'index'])->name('logs.index');

    // Usuarios
    Route::resource('usuario', UsuarioController::class);
    Route::put('usuario/activar/{id}', [UsuarioController::class, 'activar'])->name('usuario.activar');
    Route::put('usuario/desactivar/{id}', [UsuarioController::class, 'desactivar'])->name('usuario.desactivar');
    Route::post('usuario/saveChangePassword', [UsuarioController::class, 'usersChangePassword'])->name('usuario.changePassword');

    Route::resource('client_category', ClientCategoryController::class)->except('show');

    Route::group(['prefix' => 'clients'], function() {
        // Clientes
        Route::get('/', [ClientController::class, 'index'])->name('clients.index');
        Route::get('/create', [ClientController::class, 'create'])->name('clients.create');
        Route::post('/store', [ClientController::class, 'store'])->name('clients.store');
        Route::get('/edit/{id}', [ClientController::class, 'edit'])->name('clients.edit');
        Route::get('/show/{id}', [ClientController::class, 'show'])->name('clients.show');
        Route::patch('/update/{id}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('/delete/{id}', [ClientController::class, 'destroy'])->name('clients.destroy');

        Route::post('disabled', [ClientController::class, 'disabled'])->name('clients.disabled'); 
        Route::post('enabled', [ClientController::class, 'enabled'])->name('clients.enabled'); 

        // Gestion
        Route::get('payment/index', [PaymentController::class, 'index'])->name('clients.payment.index');
        Route::get('payment/show/{id}', [PaymentController::class, 'show'])->name('clients.payment.show');
        Route::get('payment/create', [PaymentController::class, 'create'])->name('clients.payment.create');
        Route::get('payment/update/{id}', [PaymentController::class, 'edit'])->name('clients.payment.edit');
        Route::post('payment/save', [PaymentController::class, 'save'])->name('clients.payment.save');
        Route::get('payment/invoice/{numCP}', [PaymentController::class, 'getCPInvoice'])->name('clients.payment.getCPInvoice');
        Route::get('payment/invoiced-closed-cps/{clientId}', [PaymentController::class, 'getInvoicedAndClosedCPs'])->name('clients.payment.getInvoicedAndClosedCPs');

        // Cuenta corriente
        Route::get('current-account/index', [CurrentAccountController::class, 'index'])->name('clients.current-account.index');
        Route::get('current-account/pdf/{id}', [CurrentAccountController::class, 'downloadPDF'])->name('clients.current-account.downloadPDF');
        // Route::get('current-account/{id}', [CurrentAccountController::class, 'show'])->name('clients.current-account');

        // Reporte de deudas
        Route::get('debts/index', [DebtsController::class, 'index'])->name('clients.debts.index');
    });
    
    // Cartas de Porte    
    Route::group(['prefix' => 'carta-porte'], function() {
        Route::get('/auto', [CartaPorteController::class, 'indexAuto'])->name('carta_porte.indexAuto');
        Route::get('/ferro', [CartaPorteController::class, 'indexFerro'])->name('carta_porte.indexFerro');  
        Route::get('/reports', [CartaPorteController::class, 'getCPClosed'])->name('carta_porte.reports');  
        Route::get('/show/{id}', [CartaPorteController::class, 'show'])->name('carta_porte.show'); 
        Route::post('/force-save/auto', [CartaPorteController::class, 'forceSaveAuto'])->name('carta_porte.forceSaveAuto');
        Route::post('/force-save/ferro', [CartaPorteController::class, 'forceSaveFerro'])->name('carta_porte.forceSaveFerro');
        Route::post('/auto/generate', [CartaPorteController::class, 'saveAuto'])->name('carta_porte.auto.generate');
        Route::post('/ferro/generate', [CartaPorteController::class, 'saveFerro'])->name('carta_porte.ferro.generate');
        Route::post('/actualizar', [CartaPorteController::class, 'actualizar'])->name('carta_porte.actualizar');
        Route::post('/confirmar', [CartaPorteController::class, 'confirmar'])->name('carta_porte.confirmar');
        Route::post('/rechazo', [CartaPorteController::class, 'rechazo'])->name('carta_porte.rechazo');
        Route::post('/anular', [CartaPorteController::class, 'anular'])->name('carta_porte.anular');
        Route::post('/import-ctg', [CartaPorteController::class, 'importCTG'])->name('carta_porte.importCTG');
        Route::get('/create/{type}', [CartaPorteController::class, 'create'])->name('carta_porte.create');
        Route::post('/store', [CartaPorteController::class, 'store'])->name('carta_porte.store');
        Route::get('/localities/{codProvince}',[CartaPorteController::class, 'getLocalitiesByProvince'])->name('carta_porte.localities');
    });      


    Route::group(['prefix' => 'grains'], function() {        
        Route::get('/', [GrainController::class, 'index'])->name('grains.index');
        Route::post('/', [GrainController::class, 'store'])->name('grains.store');
        Route::get('/{id}', [GrainController::class, 'show'])->name('grains.show');
        Route::patch('/{id}', [GrainController::class, 'update'])->name('grains.update');
        Route::delete('/{id}', [GrainController::class, 'destroy'])->name('grains.destroy');
    });

    Route::group(['prefix' => 'cost-centers'], function() {        
        Route::get('/', [CostCenterController::class, 'index'])->name('cost-centers.index');
        Route::post('/', [CostCenterController::class, 'store'])->name('cost-centers.store');
        Route::get('/{id}', [CostCenterController::class, 'show'])->name('cost-centers.show');
        Route::patch('/{id}', [CostCenterController::class, 'update'])->name('cost-centers.update');
        Route::delete('/{id}', [CostCenterController::class, 'destroy'])->name('cost-centers.destroy');
    });

    Route::group(['prefix'=>'grain-percentages'], function(){        
        Route::get('/', [GrainPercentageController::class, 'index'])->name('grainPercentages.index');
        Route::post('/', [GrainPercentageController::class, 'store'])->name('grainPercentages.store');
        Route::post('/import', [GrainPercentageController::class, 'importPercentages'])->name('grainPercentages.import');
        Route::get('/example-import', [GrainPercentageController::class, 'downloadCsvExample'])->name('grainPercentages.exampleImport');
        Route::get('/{id}', [GrainPercentageController::class, 'show'])->name('grainPercentages.show');
        Route::patch('/{id}', [GrainPercentageController::class, 'update'])->name('grainPercentages.update');
        Route::delete('/{id}', [GrainPercentageController::class, 'destroy'])->name('grainPercentages.destroy');
    });

    Route::group(['prefix' => 'params'], function() {
        Route::get('/', [GrainParamsController::class, 'index'])->name('params.index');
        Route::post('/', [GrainParamsController::class, 'store'])->name('params.store');
        Route::get('/{id}', [GrainParamsController::class, 'show'])->name('params.show');
        Route::patch('/{id}', [GrainParamsController::class, 'update'])->name('params.update');
        Route::delete('/{id}', [GrainParamsController::class, 'destroy'])->name('params.destroy');
    });

    Route::group(['prefix' => 'categories'], function() {
        Route::get('/', [GrainCategoryController::class, 'index'])->name('categories.index');
        Route::post('/', [GrainCategoryController::class, 'store'])->name('categories.store');
        Route::post('/relate', [GrainCategoryController::class, 'relate'])->name('categories.relate');
        Route::get('/{id}', [GrainCategoryController::class, 'show'])->name('categories.show');
        Route::patch('/{id}', [GrainCategoryController::class, 'update'])->name('categories.update');
        Route::delete('/{id}', [GrainCategoryController::class, 'destroy'])->name('categories.destroy');
    });

    Route::group(['prefix' => 'rates'], function(){
        Route::get('/', [RateController::class, 'index'])->name('rates.index');
        Route::get('/history', [RateController::class, 'history'])->name('rates.history');
        Route::post('/', [RateController::class, 'store'])->name('rates.store');
        Route::get('/{id}', [RateController::class, 'show'])->name('rates.show');
        Route::patch('/{id}', [RateController::class, 'update'])->name('rates.update');
        Route::delete('/{id}', [RateController::class, 'destroy'])->name('rates.destroy');
    });

    Route::group(['prefix' => 'ticket'], function(){
        Route::get('/', [DownloadTicketController::class, 'index'])->name('ticket.index');
        Route::post('/', [DownloadTicketController::class, 'store'])->name('ticket.store');
        Route::get('/carta-porte/{id}', [DownloadTicketController::class, 'checkExistCP'])->name('ticket.checkCP');
        Route::get('/carta-porte/{id}/{value}', [DownloadTicketController::class, 'searchCP'])->name('ticket.search');
        Route::get('/grain/{grain_id}/categories', [DownloadTicketController::class, 'getGrainCategories'])->name('ticket.getGrainCategories');
        Route::get('/{id}', [DownloadTicketController::class, 'show'])->name('ticket.show');
        Route::delete('/{id}', [DownloadTicketController::class, 'destroy'])->name('ticket.destroy');
        Route::patch('/{id}', [DownloadTicketController::class, 'update'])->name('ticket.update');
    });

    Route::group(['prefix' => 'providers'], function(){
        Route::get('/', [ProviderController::class, 'index'])->name('provider.index');
        Route::post('/', [ProviderController::class, 'store'])->name('provider.store');
        Route::get('/{id}', [ProviderController::class, 'show'])->name('provider.show');
        Route::patch('/{id}', [ProviderController::class, 'update'])->name('provider.update');
        Route::delete('/{id}', [ProviderController::class, 'destroy'])->name('provider.destroy');
    });    
    
    Route::group(['prefix' => 'camera-report'], function(){
        Route::get('/', [CameraReportController::class, 'index'])->name('cameraReport.index');
        Route::post('/import', [CameraReportController::class, 'importCameraReportTXT'])->name('cameraReport.import');
        Route::get('/show/{id}', [CameraReportController::class, 'showReport'])->name('cameraReport.show'); 
    });

    Route::group(['prefix' => 'invoices'], function() {
        Route::get('/', [InvoiceController::class, 'index'])->name('invoices.index');
        Route::post('/create', [InvoiceController::class, 'createInvoice'])->name('invoices.create');
        Route::get('/download/{cp_id}', [InvoiceController::class, 'downloadPDF'])->name('invoices.download');
    });
});
