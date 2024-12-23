<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SoinController;
use App\Http\Controllers\RdvController;
use App\Http\Controllers\ReponseController;
use App\Http\Controllers\QuestionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\VisiteController;
use App\Http\Controllers\VisiteImageController;

Route::get('/', function () {
    return view('auth/login');
});

// Authentication routes
Route::controller(AuthController::class)->group(function () {
    Route::get('register', 'register')->name('register');
    Route::post('register', 'registerSave')->name('register.save');
    Route::get('login', 'login')->name('login');
    Route::post('login', 'loginAction')->name('login.action');
    Route::get('logout', 'logout')->middleware('auth')->name('logout');
});

// Protected routes for authenticated users only
Route::middleware('auth')->group(function () {
    Route::get('dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [AuthController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');
    
    Route::get('/patients/{patient}/reponses', [ReponseController::class, 'show'])->name('reponses.show');
    Route::put('/reponses/{patient}', [ReponseController::class, 'update'])->name('reponses.update');

    Route::resource('patients', PatientController::class);



    Route::resource('rdvs', RdvController::class);
    Route::get('fullcalender', [FullCalenderController::class, 'index']);

    Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);

    Route::resource('products', ProductController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoices.index');

    Route::get('invoices/{invoice}/download-pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.download-pdf');
    //soins
    Route::resource('soins', SoinController::class);
    //patients
    Route::resource('patients', PatientController::class);
    Route::get('/dashboard', [PatientController::class, 'dashboard'])->name('dashboard');

    //Rendez vous
    Route::resource('rdvs', RdvController::class);
    Route::get('fullcalender', [FullCalenderController::class, 'index']);
    Route::post('fullcalenderAjax', [FullCalenderController::class, 'ajax']);
    Route::resource('visites', VisiteController::class);
    //produits
    Route::resource('products', ProductController::class);
    Route::get('/rdvs', [RdvController::class, 'index'])->name('rdvs.index');

    //question
    Route::get('/questions', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/store-responses', [QuestionController::class, 'storeResponses'])->name('questions.storeResponses');
    Route::get('/questions/save-all-responses', [QuestionController::class, 'saveAllResponses'])->name('questions.saveAllResponses');
    // Route for questions completed
    Route::get('/questions/completed', [QuestionController::class, 'completed'])->name('questions.completed');
    Route::get('/questions', [QuestionController::class, 'indexAll'])->name('questions.index');

    // Renamed route for completed (you can change the name to something else)
    Route::get('/completed', [QuestionController::class, 'completed'])->name('completed.questions');
    Route::get('/questions/next', [QuestionController::class, 'getNextQuestion'])->name('questions.getNextQuestion');

    Route::post('/questions/confirm-responses', [QuestionController::class, 'confirmResponses'])->name('questions.confirmResponses');
    Route::get('/questions/merci', function () {
        return view('questions.merci');
    })->name('questions.merci');
    Route::get('/patients/{patient}/start-questionnaire', [QuestionController::class, 'startForPatient'])
        ->name('patients.startQuestionnaire');
    //mailing
    Route::post('/appointments/{appointmentId}/send-confirmation-email', [RdvController::class, 'sendConfirmationEmail']);
    
    //images de visite
    Route::post('/visite-images', [VisiteImageController::class, 'store']);
    Route::delete('/visite-images/{id}', [VisiteImageController::class, 'destroy']);

});
Route::get('/invoices/{invoiceId}/generate-and-share-pdf', [InvoiceController::class, 'generateAndSharePDF']);
Route::get('/invoices/{invoiceId}/generate-pdf', [InvoiceController::class, 'generatePDF']);



// Fallback route to handle unauthorized access
Route::fallback(function () {
    return redirect()->route('login')->with('error', 'Vous pouvez etre connecte pour acceder a cette page.');
});
