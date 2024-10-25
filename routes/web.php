<?php

use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\RdvController;
use Illuminate\Support\Facades\Route;


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

Route::get('/', function () {
    return view('welcome');
});

Route::resource('patients', PatientController::class);

Route::resource('rdvs', RdvController::class);
Route::get('fullcalender', [FullCalenderController::class, 'index']);

Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);
