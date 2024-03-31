<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileSettings;
use App\Http\Controllers\BusController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SaisonController;
use App\Http\Controllers\BundeslanderController;
use App\Http\Controllers\LeadsController;
use App\Http\Controllers\StammdatenController;
use App\Http\Controllers\BusPartnerController;
use App\Http\Controllers\AbwicklungController;
use App\Http\Controllers\CoronController;

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
    return redirect('/home');
});
Route::get('/Buspreiskalkulation', [App\Http\Controllers\FrontEndController::class, 'index']);
Route::post('/Estimated-Price', [App\Http\Controllers\FrontEndController::class, 'calculation']);


Auth::routes();
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Profile Settings
Route::get('/profile-settings', [ProfileSettings::class, 'settings']);
Route::post('/profile-settings/update', [ProfileSettings::class, 'update_profile']);
Route::post('/profile-settings/update-password', [ProfileSettings::class, 'update_password']);


// Bus type
Route::get('/Bus-Type', [BusController::class, 'view'])->name('bus.type');
Route::get('/Bus-Type/Add', [BusController::class, 'add'])->name('bus.type.add')->middleware('isAdminDispatcher');
Route::post('/Bus-Type/Save', [BusController::class, 'save'])->name('bus.type.save')->middleware('isAdminDispatcher');
Route::get('/Bus-Type/Edit/{id}', [BusController::class, 'edit'])->name('bus.type.edit')->middleware('isAdminDispatcher');
Route::post('/Bus-Type/Update/{id}', [BusController::class, 'update'])->name('bus.type.update')->middleware('isAdminDispatcher');
Route::get('/Bus-Type/delete/{id}', [BusController::class, 'delete'])->name('bus.type.delete')->middleware('isAdminDispatcher');
Route::get('/bus_details/{id}', [BusController::class, 'bus_details']);

// Employee
Route::get('/Employees', [UserController::class, 'view'])->middleware('isAdminDispatcher');
Route::get('/Employee/add', [UserController::class, 'add'])->middleware('isAdmin');
Route::post('/Employee/save', [UserController::class, 'save'])->middleware('isAdmin');
Route::get('/Employee/edit/{id}', [UserController::class, 'edit'])->middleware('isAdmin');
Route::post('/Employee/update/{id}', [UserController::class, 'update'])->middleware('isAdmin');
Route::post('/Employee/update-password/{id}', [UserController::class, 'update_password'])->middleware('isAdmin');
Route::get('/Employee/delete/{id}', [UserController::class, 'delete'])->middleware('isAdmin');

// Saison
Route::get('/Saison', [SaisonController::class, 'view']);
Route::post('/Saison/save', [SaisonController::class, 'save'])->middleware('isAdminDispatcher');
Route::get('/Saison/edit/{id}', [SaisonController::class, 'edit'])->middleware('isAdminDispatcher');
Route::post('/Saison/update/{id}', [SaisonController::class, 'update'])->middleware('isAdminDispatcher');
Route::get('/Saison/delete/{id}', [SaisonController::class, 'delete'])->middleware('isAdminDispatcher');

// Saison
Route::get('/Bundesland', [BundeslanderController::class, 'view']);
Route::post('/Bundesland/save', [BundeslanderController::class, 'save'])->middleware('isAdminDispatcher');
Route::get('/bundesland/edit/{id}', [BundeslanderController::class, 'edit'])->middleware('isAdminDispatcher');
Route::post('/Bundesland/update/{id}', [BundeslanderController::class, 'update'])->middleware('isAdminDispatcher');
Route::get('/bundesland/delete/{id}', [BundeslanderController::class, 'delete'])->middleware('isAdminDispatcher');
Route::get('/Download/Files/{id}', [BundeslanderController::class, 'downloadFiles'])->middleware('isAdminDispatcher');

Route::post('/Edit/image', [BundeslanderController::class, 'updateImg'])->middleware('isAdminDispatcher');
Route::get('/Delete/image/{id}', [BundeslanderController::class, 'deleteImg'])->middleware('isAdminDispatcher');

Route::get('/Link', [App\Http\Controllers\HomeController::class, 'setting'])->middleware("isAdmin");
Route::post('/Link/save', [App\Http\Controllers\HomeController::class, 'settingSave'])->middleware("isAdmin");


Route::get('/Leads', [LeadsController::class, 'view'])->middleware('isAdminDispatcher');
Route::get('/Coron-Leads', [CoronController::class, 'getLeads']);
// Route::get('/Employee/add', [UserController::class, 'add'])->middleware('isAdmin');
// Route::post('/Employee/save', [UserController::class, 'save'])->middleware('isAdmin');
// Route::get('/Employee/edit/{id}', [UserController::class, 'edit'])->middleware('isAdmin');
// Route::post('/Employee/update/{id}', [UserController::class, 'update'])->middleware('isAdmin');
// Route::post('/Employee/update-password/{id}', [UserController::class, 'update_password'])->middleware('isAdmin');
// Route::get('/Employee/delete/{id}', [UserController::class, 'delete'])->middleware('isAdmin');


Route::get('/Stammdaten', [StammdatenController::class, 'view'])->middleware('isAdminDispatcher');
// Route::get('/Employee/add', [UserController::class, 'add'])->middleware('isAdmin');
// Route::post('/Employee/save', [UserController::class, 'save'])->middleware('isAdmin');
// Route::get('/Employee/edit/{id}', [UserController::class, 'edit'])->middleware('isAdmin');
// Route::post('/Employee/update/{id}', [UserController::class, 'update'])->middleware('isAdmin');
// Route::post('/Employee/update-password/{id}', [UserController::class, 'update_password'])->middleware('isAdmin');
// Route::get('/Employee/delete/{id}', [UserController::class, 'delete'])->middleware('isAdmin');


Route::get('/Bus-Partner', [BusPartnerController::class, 'view'])->middleware('isAdminDispatcher');
Route::post('/Bus-Partner/save', [BusPartnerController::class, 'save'])->middleware('isAdmin');
Route::get('/Bus-Partner/edit/{id}', [BusPartnerController::class, 'edit'])->middleware('isAdmin');
Route::post('/Bus-Partner/update/{id}', [BusPartnerController::class, 'update'])->middleware('isAdmin');
Route::get('/Bus-Partner/delete/{id}', [BusPartnerController::class, 'delete'])->middleware('isAdmin');

Route::get('/Abwicklung',[AbwicklungController::class, 'view'])->middleware('isAdminDispatcher');
