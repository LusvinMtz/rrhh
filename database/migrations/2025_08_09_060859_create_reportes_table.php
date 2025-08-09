<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_reporte');
            $table->enum('tipo_reporte', ['general_interno', 'informacion_publica', 'historial_empleados', 'historial_permisos', 'historial_contratos', 'dashboard', 'personalizado']);
            $table->text('descripcion')->nullable();
            $table->json('parametros')->nullable(); // filtros, fechas, etc.
            $table->json('columnas')->nullable(); // columnas a incluir
            $table->string('formato', 10)->default('excel'); // excel, pdf, csv
            $table->string('generado_por');
            $table->timestamp('fecha_generacion');
            $table->string('archivo_path')->nullable();
            $table->integer('total_registros')->default(0);
            $table->enum('estado', ['generando', 'completado', 'error']);
            $table->text('mensaje_error')->nullable();
            $table->boolean('es_publico')->default(false);
            $table->date('fecha_desde')->nullable();
            $table->date('fecha_hasta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reportes');
    }
};
