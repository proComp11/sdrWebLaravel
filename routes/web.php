<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomAuthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('dashboard', [CustomAuthController::class, 'dashboard'])->name('dashboard');
Route::get('uploadmodule',[CustomAuthController::class,'uploadmodule'])->name('sdr-upload');
Route::get('headerlist', [CustomAuthController::class, 'headerlist'])->name('header-list');
Route::get('headermatch', [CustomAuthController::class, 'headermatch'])->name('header-match');
Route::post('getHeaders',[CustomAuthController::class,'getHeaders'])->name('get-Headers');
Route::post('uploadFile',[CustomAuthController::class,'uploadFile'])->name('upload-file');
Route::post('saveSdrInfo',[CustomAuthController::class, 'saveSdrInfo'])->name('save-sdrinfo');
Route::post('updateInformation', [CustomAuthController::class,'updateInformation'])->name('update-info');
Route::post('udateFinal',[CustomAuthController::class, 'updateFinal'])->name('update-final');
Route::post('completeProcess',[CustomAuthController::class, 'completeProcess'])->name('complete-process');
Route::post('editHeaders',[CustomAuthController::class, 'editHeaders'])->name('edit-headers');
Route::get('/', [CustomAuthController::class, 'index'])->name('login');
Route::post('custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
Route::get('registration', [CustomAuthController::class, 'registration'])->name('register-user');
Route::post('custom-registration', [CustomAuthController::class, 'customRegistration'])->name('register.custom');
Route::get('signout', [CustomAuthController::class, 'signOut'])->name('signout');

