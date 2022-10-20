<?php

use App\Http\Controllers\AdministratorController;
use App\Http\Controllers\ExamSummaryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PdfController;
use App\Http\Livewire\Aula\Informacion;
use App\Http\Livewire\Evaluacion\Partials\ConfiguracionRespuestas;
use App\Http\Livewire\Mantenimiento\Usuarios\Usuarios;
use App\Models\ExamSummary;
use App\Repository\ClassroomRepository;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
//Route::get('temp/{id}', 'IncidenteController@temp'); // Id de incidente

Route::get('/', [AdministratorController::class, 'showLoginForm']);
// Route::get('/quertium/{dni}', [AdministratorController::class, 'quertium']);

Auth::routes();

Route::get('/main', [HomeController::class, 'index'])->name('dashboard');

Route::middleware(['auth'])->prefix('matricula')->group(function () {
    // Listos
    Route::get('/nueva', [HomeController::class, 'nuevaMatriculaView'])->name('matricula.nueva');
    Route::get('/informacion-alumno', [HomeController::class, 'informacionAlumnoView'])->name('matricula.informacion-alumno');


    // Pendientes
    Route::get('/buscar', function () {
        return "buscar";
    })->name('matricula.buscar');

    Route::get('/pagos', function () {
        return "pagos";
    })->name('matricula.pagos');
    Route::get('/alumnos', function () {
        return "alumnos";
    })->name('matricula.alumno');
    Route::get('/apoderados', function () {
        return "apoderados";
    })->name('matricula.apoderado');
});

Route::middleware(['auth'])->prefix('mantenimiento')->group(function () {
    Route::get('/ciclos-y-aulas', [HomeController::class, 'ciclosAulas'])->name('mantenimiento.ciclos-y-aulas');
});
/*
mantenimiento.ciclos-aulas.index */


Route::middleware(['auth'])->prefix('evaluaciones')->group(function () {
    Route::get('/configuracion-examenes', [HomeController::class, 'configuracion'])->name('evaluaciones.configuracion');
    Route::get('/carga-revision', [HomeController::class, 'revision'])->name('evaluaciones.revision');
    Route::get('/resultados-reporte', [HomeController::class, 'reporte'])->name('evaluaciones.reporte');

    Route::get('/configuracion-examenes/respuestas-configuracion/{examen_id?}', ConfiguracionRespuestas::class )->name('evaluaciones.configuracion.respuestas');
});


Route::middleware(['auth'])->prefix('mantenimiento')->group(function () {
    Route::get('/ciclos-aulas', [HomeController::class, 'mantenimiento'])->name('mantenimiento.aulas');
    Route::get('/alumnos', [HomeController::class, 'mantenimiento'])->name('mantenimiento.alumnos');

    Route::get('/usuarios', Usuarios::class  )->name('mantenimiento.usuarios');

});

Route::middleware(['auth'])->prefix('reportes')->group(function () {
    Route::get('/', [HomeController::class, 'reportes'])->name('reportes');

    Route::get('/notas/resultados-general-examen/{examen_id}', [PdfController::class, 'reporteNotasGeneralesPorExamen'])->name('reportes.notas.resultados-general');
});

Route::middleware(['auth'])->prefix('aulas')->group(function () {
    Route::get('/', [HomeController::class, 'aulasIndexView'])->name('aulas.index');
    Route::get('informacion/{aula_id}', [HomeController::class, 'aulasInformacionView'] )->name('aulas.informacion');


    Route::get('/resultados/examen-detallado/{data_json}', [ExamSummaryController::class, 'generarDetalleExamenes'] )->name('reporte.evaluacion.detallado');
    Route::get('/resultados/examen-detallado-cartas/{classroom_id}', [ExamSummaryController::class, 'generarCartasExamen'] )->name('reporte.evaluacion.cartas');
    Route::get('/resultados/lista-alumnos/{classroom_id}', [PdfController::class, 'generarListaAlumnos'] )->name('reporte.lista.alumnos');

    /* Route::get('/resultados/examen-detallado-individual', function(){
        return ( new ClassroomRepository )->buildModelExamenReport(null, [1,2], [141,142, 100]);
    }); */


   /*  Route::get('/resultados/examen-detallado-masivo/{data_json}', [ExamSummaryController::class, 'generarDetalleExamenesAula'] )->name('reporte-aula.evaluacion.detallado'); */
/*     Route::get('prueba', Informacion::class ); */


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
