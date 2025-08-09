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
        Schema::create('contratos_renglon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_renglon_id')->constrained('empleados_renglon')->onDelete('cascade');
            $table->string('numero_contrato_renglon')->unique();
            $table->enum('tipo_contrato', ['temporal', 'por_servicios', 'consultoria']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->decimal('monto_total', 12, 2);
            $table->decimal('monto_mensual', 10, 2);
            $table->text('objeto_contrato');
            $table->text('productos_entregables');
            $table->string('fuente_financiamiento');
            $table->string('programa_presupuestario');
            $table->enum('estado', ['borrador', 'activo', 'vencido', 'terminado', 'cancelado']);
            $table->date('fecha_firma')->nullable();
            $table->string('supervisor_contrato');
            $table->text('clausulas_especiales')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos_renglon');
    }
};
