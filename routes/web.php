<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\CategoryController;
use App\Http\Livewire\SubCategoryController;
use App\Http\Livewire\ProductController;
use App\Http\Livewire\CoinController;
use App\Http\Livewire\PosController;
use App\Http\Livewire\PurchaseController;
use App\Http\Livewire\RolesController;
use App\Http\Livewire\PermisosController;
use App\Http\Livewire\AsignacionesController;
use App\Http\Livewire\UsersController;
use App\Http\Livewire\CashOutController;
use App\Http\Livewire\ReportsController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AjaxController;
use App\Http\Controllers\BarcodeController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register'=> false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(['auth'])->group(function () {
    Route::get('categories', CategoryController::class);
    Route::get('subcategories', SubCategoryController::class);
    Route::get('products', ProductController::class);
    Route::get('coins', CoinController::class);
    Route::get('pos', PosController::class);
    Route::get('purchase', PurchaseController::class);
    Route::get('roles', RolesController::class);
    Route::get('permissions', PermisosController::class);
    Route::get('assigments', AsignacionesController::class);
    Route::get('users', UsersController::class);
    Route::get('cashout', CashOutController::class);
    Route::get('reports', ReportsController::class);

    //peticiones ajax
    Route::get('getproviders',[AjaxController::class, 'getproviders'])->name('getProviders');
    Route::get('getcustomers',[AjaxController::class, 'getcustomers'])->name('getCustomers');
});

//reportes PDF
Route::get('report/pdf/{user}/{type}/{f1}/{f2}',[ExportController::class,'reportPDF']);
Route::get('report/pdf/{user}/{type}',[ExportController::class,'reportPDF']);

//reportes Excel
Route::get('reporte/excel/{user}/{type}/{f1}/{f2}',[ExportController::class,'reportExcel']);
Route::get('reporte/excel/{user}/{type}',[ExportController::class,'reportExcel']);

//importar Productos
Route::get('/import', [ImportController::class,'import']);

//Barcode
Route::get('/printfbarcode/{array?}', [BarcodeController::class,'printbarcode'])
       ->name('printBarcode');
Route::get('/printcodeall',[BarcodeController::class,'printallcodes'])->name('printBarcodeAll');
Route::get('/barcode', [BarcodeController::class,'pdf']);