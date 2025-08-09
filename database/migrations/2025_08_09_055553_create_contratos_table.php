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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->string('numero_contrato')->unique();
            $table->enum('tipo_contrato', ['indefinido', 'temporal', 'por_obra']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->decimal('salario', 10, 2);
            $table->string('puesto');
            $table->string('area_trabajo');
            $table->text('descripcion_puesto')->nullable();
            $table->enum('jornada_laboral', ['completa', 'parcial', 'por_horas']);
            $table->integer('horas_semanales')->default(40);
            $table->text('clausulas_especiales')->nullable();
            $table->enum('estado', ['activo', 'vencido', 'terminado', 'suspendido']);
            $table->date('fecha_firma')->nullable();
            $table->string('lugar_trabajo')->nullable();
            $table->text('beneficios')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
