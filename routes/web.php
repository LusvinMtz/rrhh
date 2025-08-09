<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReporteController;

Route::get('/', function () {
    return redirect('/admin');
});

// Ruta para descargar reportes
Route::get('/reportes/{id}/download', [ReporteController::class, 'download'])
    ->name('reportes.download')
    ->middleware('auth');

// Redirigir cualquier ruta no encontrada a admin
Route::fallback(function () {
    return redirect('/admin');
});
