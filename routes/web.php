<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;

//comment

Route::get('/', function () {
    return view('auth/login');
});

Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');

    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
  
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
 
   
 
    Route::get('/profile', [App\Http\Controllers\AuthController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

});

Route::resource('patients', PatientController::class);


Route::resource('rdvs', RdvController::class);
Route::get('fullcalender', [FullCalenderController::class, 'index']);

Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);

Route::resource('products', ProductController::class);


// Route::post('/questions/storeResponses', [QuestionController::class, 'storeResponses'])->name('questions.storeResponses');
// Route::resource('questions', QuestionController::class);
// Route::get('/questions/save-all-responses', [QuestionController::class, 'saveAllResponses'])->name('questions.saveAllResponses');


Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
Route::post('/questions/store-responses', [QuestionController::class, 'storeResponses'])->name('questions.storeResponses');
Route::get('/questions/save-all-responses', [QuestionController::class, 'saveAllResponses'])->name('questions.saveAllResponses');
Route::get('/questions/completed', [QuestionController::class, 'completed'])->name('questions.completed');
//Route::post('/questions/previous', [QuestionController::class, 'previousQuestion'])->name('questions.previousQuestion');
Route::get('/completed', [QuestionController::class, 'completed'])->name('questions.completed');
Route::post('/questions/confirm-responses', [QuestionController::class, 'confirmResponses'])->name('questions.confirmResponses');
Route::get('/questions/merci', function() {
    return view('questions.merci');
})->name('questions.merci');




