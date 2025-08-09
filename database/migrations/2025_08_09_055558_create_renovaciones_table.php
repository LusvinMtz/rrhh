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
        Schema::create('renovaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->string('numero_renovacion')->unique();
            $table->date('fecha_renovacion');
            $table->date('nueva_fecha_fin');
            $table->decimal('nuevo_salario', 10, 2)->nullable();
            $table->string('nuevo_puesto')->nullable();
            $table->string('nueva_area_trabajo')->nullable();
            $table->text('cambios_realizados')->nullable();
            $table->text('justificacion');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada']);
            $table->date('fecha_aprobacion')->nullable();
            $table->string('aprobado_por')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('renovaciones');
    }
};
