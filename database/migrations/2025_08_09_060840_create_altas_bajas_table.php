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
        Schema::create('altas_bajas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->enum('tipo_movimiento', ['alta', 'baja', 'reingreso']);
            $table->date('fecha_movimiento');
            $table->enum('motivo', ['contratacion', 'renuncia', 'despido', 'jubilacion', 'defuncion', 'abandono', 'fin_contrato', 'reingreso']);
            $table->text('descripcion')->nullable();
            $table->string('documento_soporte')->nullable();
            $table->string('autorizado_por');
            $table->date('fecha_efectiva');
            $table->enum('estado', ['pendiente', 'aprobado', 'ejecutado']);
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('altas_bajas');
    }
};
