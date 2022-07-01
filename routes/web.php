<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//Route::get('temp/{id}', 'IncidenteController@temp'); // Id de incidente

Route::get('/', 'AdministratorController@showLoginForm');

Route::get('/dni/{dni}', 'AdministratorController@dni');
Route::get('/quertium/{dni}', 'AdministratorController@quertium');


Auth::routes();

// Rutas Generales
Route::get('/main', 'EnrollmentController@main')->middleware('auth');

Route::get('/test', 'PaymentController@test')->middleware('auth');



/********************** Busquedas (aoutocompletado) *********************/
Route::get('/search-ie', 'SchoolController@search_ie')->middleware('auth');
Route::get('/search-career', 'CareerController@search_career')->middleware('auth');
Route::get('/search-relative', 'RelativeController@search_relative')->middleware('auth');
Route::get('/search-classroom', 'ClassroomController@search_classroom')->middleware('auth');
Route::get('/search-district', 'AdministratorController@search_district')->middleware('auth');
Route::get('/search-student', 'StudentController@search_student')->middleware('auth');


/******************************* Enrollemnt **************************/
Route::get('/nueva-matricula', 'EnrollmentController@create')->middleware('auth');
Route::post('/registrar-matricula', 'EnrollmentController@store')->middleware('auth');
Route::get('/buscar-matricula/{param}', 'EnrollmentController@search_enrollment')->middleware('auth');
Route::get('/editar-matricula/{id}', 'EnrollmentController@edit')->middleware('auth');
//Route::post('/actualizar-matricula', 'EnrollmentController@update')->middleware('auth');
Route::get('/buscar-matricula/{param}', 'EnrollmentController@search_enrollment')->middleware('auth');

Route::post('/retirar', 'EnrollmentController@cancel')->middleware('auth');


/******************* Modulo de  Matricula  *******************/
Route::middleware(['auth'])->prefix('matricula')->group(function () {
    Route::post('informacion-matricula', 'EnrollmentController@getEnrollment');
    Route::post('updateMatricula/{id}', 'EnrollmentController@updateMatricula');
    Route::post('updateAlumno/{matriculaId}', 'EnrollmentController@updateAlumno');
    Route::post('updateApoderado/{matriculaId}', 'EnrollmentController@updateApoderado');
    Route::get('installment-info/{matriculaId}', 'InstallmentController@installmentsInfo');
});


Route::get('/control-cuotas/{enrollment_id}', 'InstallmentController@installments_control_view')->middleware('auth');
Route::get('/agregar-pago/{installment_id}', 'PaymentController@create')->middleware('auth');

Route::post('/registrar-pago', 'PaymentController@store')->middleware('auth');

Route::get('/historial-pago/{installment_id}', 'PaymentController@history')->middleware('auth');

Route::get('/documento-de-pago', 'PaymentController@payment_document_pdf')->middleware('auth');

Route::get('/ciclos', 'PeriodController@index')->middleware('auth');
Route::get('/ciclos-listado', 'PeriodController@listing')->middleware('auth');
Route::get('/nuevo-ciclo', 'PeriodController@create')->middleware('auth');
Route::post('/registrar-ciclo', 'PeriodController@store')->middleware('auth');
Route::get('/ciclos/editar/{id}', 'PeriodController@edit')->middleware('auth');
Route::post('/actualizar-ciclo', 'PeriodController@update')->middleware('auth');

Route::post('/cambiar-estado-ciclo', 'PeriodController@status')->middleware('auth');



Route::get('/aulas/listado/{level_id}', 'ClassroomController@listing')->middleware('auth');
Route::post('/aulas/eliminar', 'ClassroomController@destroy')->middleware('auth');

Route::get('/aulas/nueva/{level_id}', 'ClassroomController@create')->middleware('auth');
Route::post('/aulas/registrar', 'ClassroomController@store')->middleware('auth');


Route::get('/ie/nueva/', 'SchoolController@create')->middleware('auth');
Route::post('/ie/registrar', 'SchoolController@store')->middleware('auth');


Route::get('/levels/filter-by-period', 'PeriodController@level_per_period')->middleware('auth');

Route::get('/classrooms/filter-by-level', 'ClassroomController@classroom_per_level')->middleware('auth');

/************************************ API - AULAS ************************************/
Route::middleware(['auth'])->prefix('info-general')->group(function () {
    Route::get('niveles', 'PeriodController@nivelesGet');
    Route::get('aulas/{id}', 'PeriodController@aulasGet');
    //Route::get('/niveles', 'StudentController@otro');
});

/************************************ Reportes ************************************/
Route::middleware(['auth'])->prefix('reportes')->group(function () {
    Route::get('/estudiantes-por-aula', 'StudentController@classroom_students_report');
    Route::post('/estudiantes-por-aula/buscar', 'StudentController@do_classroom_students');

    Route::get('/recaudo-por-usuario', 'PaymentController@users_collection_report');
    Route::post('/recaudo-por-usuario/buscar', 'PaymentController@do_users_collection');

    Route::get('/Alumnos-por-ciclo', 'EnrollmentController@period_enrollments_report');
    Route::post('/Alumnos-por-ciclo/buscar', 'EnrollmentController@do_period_enrollments');

    //Route::get('/reportes/prueba','HomeController@prueba')->middleware('auth');
    Route::get('/ficha-matricula-download/{id}', 'ReportController@fichaMatricula');

    Route::get('/info-alumnos-apoderados', 'ReportController@alumnos_y_apoderados');
    Route::get('/descargar-info-alumnos-apoderados/{id}/{descargar?}', 'ReportController@show_alumnos_y_apoderados');

    //:::::::::::::::::: Reportes Incidentes ::::::::::::::::::
    Route::get('/incidentes', 'ReportController@reportIncidentes');
    Route::post('/incidentes/general', 'ReportController@incidentesGeneral');
    Route::post('/incidentes/especifico', 'ReportController@incidentesEspecifico');
});




/********** Rutas modulo de Incidencias **********/
Route::middleware(['auth'])->prefix('incidentes')->group(function () {
    Route::get('/search-incidentes', 'IncidenteController@search');
    Route::get('/search-incidentes/{param}', 'IncidenteController@search_enrollment');
    Route::get('/info-incidentes/{id_enrollment}', 'IncidenteController@infoIncidentes');
    Route::post('/report-download', 'IncidenteController@reportIncidentes');

    Route::get('/{id}', 'IncidenteController@index');
    Route::post('/', 'IncidenteController@store');
    Route::put('/{id}', 'IncidenteController@update');
    Route::delete('/{id}', 'IncidenteController@destroy');
});

/********** Rutas modulo de Incidencias - evidencias **********/
Route::middleware(['auth'])->prefix('evidencias')->group(function () {
    Route::post('/', 'EvidenciaController@store');
    Route::put('/{id}', 'EvidenciaController@update');
    Route::delete('/{id}', 'EvidenciaController@destroy');
    Route::get('/{id}', 'EvidenciaController@index'); // Id de incidente
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
