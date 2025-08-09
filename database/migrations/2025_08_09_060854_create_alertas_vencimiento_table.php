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
        Schema::create('alertas_vencimiento', function (Blueprint $table) {
            $table->id();
            $table->morphs('alertable'); // Para contratos, contratos_renglon, etc.
            $table->enum('tipo_alerta', ['contrato_vencimiento', 'contrato_renglon_vencimiento', 'permiso_vencimiento', 'documento_vencimiento']);
            $table->string('titulo');
            $table->text('descripcion');
            $table->date('fecha_vencimiento');
            $table->integer('dias_anticipacion')->default(30);
            $table->date('fecha_alerta');
            $table->enum('prioridad', ['baja', 'media', 'alta', 'critica']);
            $table->enum('estado', ['pendiente', 'notificada', 'atendida', 'vencida']);
            $table->json('destinatarios')->nullable(); // emails de notificación
            $table->timestamp('fecha_notificacion')->nullable();
            $table->text('acciones_tomadas')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alertas_vencimiento');
    }
};
