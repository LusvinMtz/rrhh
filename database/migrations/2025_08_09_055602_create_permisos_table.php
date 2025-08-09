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
        Schema::create('permisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empleado_id')->constrained('empleados')->onDelete('cascade');
            $table->enum('tipo_permiso', ['vacaciones', 'enfermedad', 'personal', 'maternidad', 'paternidad', 'estudio', 'duelo', 'otro']);
            $table->date('fecha_inicio');
            $table->date('fecha_fin');
            $table->integer('dias_solicitados');
            $table->text('motivo');
            $table->enum('estado', ['pendiente', 'aprobado', 'rechazado']);
            $table->date('fecha_solicitud');
            $table->date('fecha_aprobacion')->nullable();
            $table->string('aprobado_por')->nullable();
            $table->text('observaciones_empleado')->nullable();
            $table->text('observaciones_supervisor')->nullable();
            $table->boolean('con_goce_salario')->default(true);
            $table->string('documento_adjunto')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permisos');
    }
};
