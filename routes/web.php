<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComplaintController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HearingController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\EmailVerificationController;

Route::get('/', function () {
    return view('dashboard');
});
Route::get('/complaint', function () {
    return view('complaint');
})->name('complaint');

Route::get('/Contacts', function () {
    return view('Contacts');
});
Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
Route::match(['get', 'post'], '/Track', [ComplaintController::class, 'track'])->name('complaints.track');
Route::get('/complaints/verify/{id}/{token}', [EmailVerificationController::class, 'verify'])->name('complaints.verify');
Route::post('/complaints/verify/send/{complaint}', [EmailVerificationController::class, 'sendVerification'])->name('complaints.send-verification');
Route::get('/Resources', [ResourceController::class, 'index'])->name('resources.index');
Route::get('/Resources/{slug}', [ResourceController::class, 'show'])->name('resources.show');

Auth::routes(); 

Route::middleware(['auth'])->group(function () { 
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/complaints', [AdminController::class, 'complaints'])->name('complaints');
            Route::get('/complaints/{id}/edit', [AdminController::class, 'editComplaint'])->name('complaints.edit');
            Route::put('/complaints/{id}', [AdminController::class, 'updateComplaint'])->name('complaints.update');
            Route::get('/complaints/{id}', [AdminController::class, 'showComplaint'])->name('complaints.show');
            Route::delete('/complaints/{id}', [AdminController::class, 'destroyComplaint'])->name('complaints.destroy');
        Route::get('/files', [AdminController::class, 'allFile'])->name('all_files');
        Route::get('/contacts', [AdminController::class, 'directContacts'])->name('direct_contacts');
    Route::get('/schedule', [AdminController::class, 'hearingSchedule'])->name('hearing_schedule');
    // Hearings CRUD (admin)
    Route::resource('hearings', HearingController::class);
        Route::post('/complaints/reset', [AdminController::class, 'resetComplaints'])->name('complaints.reset');
    });
    Route::get('/complaints/view', [ComplaintController::class, 'index'])->name('complaints.index');
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard'); 
}); 