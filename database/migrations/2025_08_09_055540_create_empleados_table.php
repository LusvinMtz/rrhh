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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_empleado')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('dpi', 13)->unique();
            $table->string('nit', 12)->nullable();
            $table->date('fecha_nacimiento');
            $table->enum('genero', ['M', 'F']);
            $table->enum('estado_civil', ['soltero', 'casado', 'divorciado', 'viudo', 'union_libre']);
            $table->string('telefono', 15)->nullable();
            $table->string('email')->unique()->nullable();
            $table->text('direccion');
            $table->string('departamento');
            $table->string('municipio');
            $table->string('puesto');
            $table->string('area_trabajo');
            $table->decimal('salario_base', 10, 2);
            $table->date('fecha_ingreso');
            $table->enum('tipo_contrato', ['indefinido', 'temporal', 'por_obra']);
            $table->enum('estado', ['activo', 'inactivo', 'suspendido']);
            $table->string('numero_igss')->nullable();
            $table->string('numero_irtra')->nullable();
            $table->string('contacto_emergencia_nombre')->nullable();
            $table->string('contacto_emergencia_telefono', 15)->nullable();
            $table->string('contacto_emergencia_relacion')->nullable();
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
