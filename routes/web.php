<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SincronizacionController;
use App\Http\Controllers\HabilitacionController;
use App\Http\Controllers\ReporteController;

// Rutas públicas
Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    // Rutas de sincronización
    Route::get('/sincronizacion', [SincronizacionController::class, 'showSincronizacion'])->name('sincronizacion');
    Route::post('/sincronizacion/ejecutar', [SincronizacionController::class, 'ejecutarSincronizacion'])->name('sincronizacion.ejecutar');
    Route::get('/sincronizacion/estadisticas', [SincronizacionController::class, 'verEstadisticas'])->name('sincronizacion.estadisticas');
    
    // Rutas de habilitaciones
    Route::get('/habilitaciones/create', [HabilitacionController::class, 'create'])->name('habilitaciones.create');
    Route::post('/habilitaciones', [HabilitacionController::class, 'store'])->name('habilitaciones.store');
    Route::get('/habilitaciones/alumno-info/{rut}', [HabilitacionController::class, 'getAlumnoInfo'])->name('habilitaciones.alumno-info');
    Route::get('/habilitaciones/profesor-info/{rut}', [HabilitacionController::class, 'getProfesorInfo'])->name('habilitaciones.profesor-info');
    
    // Rutas de gestión de habilitaciones (Función 3)
    Route::get('/habilitaciones', [HabilitacionController::class, 'index'])->name('habilitaciones.index');
    Route::get('/habilitaciones/{id}/edit', [HabilitacionController::class, 'edit'])->name('habilitaciones.edit');
    Route::put('/habilitaciones/{id}', [HabilitacionController::class, 'update'])->name('habilitaciones.update');
    Route::delete('/habilitaciones/{id}', [HabilitacionController::class, 'destroy'])->name('habilitaciones.destroy');
    
    // Rutas de reportes (Función 4)
    Route::get('/reportes', [ReporteController::class, 'index'])->name('reportes.index');
    Route::get('/reportes/por-semestre', [ReporteController::class, 'listadoPorSemestre'])->name('reportes.por-semestre');
    Route::get('/reportes/por-profesor', [ReporteController::class, 'listadoPorProfesor'])->name('reportes.por-profesor');
    Route::get('/reportes/estadisticas', [ReporteController::class, 'estadisticasGenerales'])->name('reportes.estadisticas');
});