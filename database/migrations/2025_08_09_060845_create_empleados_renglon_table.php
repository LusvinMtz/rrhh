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
        Schema::create('empleados_renglon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->string('numero_renglon', 10)->unique();
            $table->enum('tipo_renglon', ['011', '021', '022', '029', '031', '032', '033', '041', '051', 'otro']);
            $table->string('descripcion_renglon');
            $table->decimal('salario_renglon', 10, 2);
            $table->date('fecha_asignacion');
            $table->date('fecha_vigencia_inicio');
            $table->date('fecha_vigencia_fin')->nullable();
            $table->enum('estado', ['activo', 'inactivo', 'suspendido']);
            $table->string('dependencia');
            $table->string('unidad_ejecutora');
            $table->text('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados_renglon');
    }
};
