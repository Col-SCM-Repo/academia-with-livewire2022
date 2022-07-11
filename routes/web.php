<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PaymentController;
use App\Http\Livewire\Mantenimiento\CiclosYAulas;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//Route::get('temp/{id}', 'IncidenteController@temp'); // Id de incidente

Route::get('/', [AdministratorController::class, 'showLoginForm']);

Route::get('/dni/{dni}', [AdministratorController::class, 'dni']);
Route::get('/quertium/{dni}', [AdministratorController::class, 'quertium']);

Auth::routes();

Route::get('/main', [HomeController::class, 'dashboard'])->name('dashboard');

Route::middleware(['auth'])->prefix('matricula')->group(function () {
    Route::get('/', [HomeController::class, 'matriculas'])->name('matricula');
    Route::get('/nueva', [HomeController::class, 'matriculas'])->name('matricula.nueva');
});

Route::middleware(['auth'])->prefix('mantenimiento')->group(function () {
    Route::get('/ciclos-aulas', [HomeController::class, 'mantenimiento'])->name('mantenimiento');
    Route::get('/alumnos', [HomeController::class, 'mantenimiento'])->name('mantenimiento');
});

Route::middleware(['auth'])->prefix('reportes')->group(function () {
    Route::get('/', [HomeController::class, 'reportes'])->name('reportes');
});

Route::middleware(['auth'])->prefix('incidencias')->group(function () {
    Route::get('/', [HomeController::class, 'incidencias'])->name('incidencias');
});





// php info
Route::get('/phpinfo', function () {
    phpinfo();
});

//Clear Cache facade value:
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function () {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function () {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function () {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function () {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
