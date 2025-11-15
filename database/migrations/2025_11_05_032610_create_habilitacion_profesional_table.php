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
        Schema::create('habilitacion_profesional', function (Blueprint $table) {
            $table->string('id_habilitacion')->primary();
            $table->integer('rut_alumno');
            $table->text('descripcion');
            $table->enum('tipo', ['PrIng', 'PrInv', 'PrTut']);
            $table->decimal('nota_final', 3, 1)->nullable();
            $table->date('fecha_nota_final')->nullable();
            $table->integer('semestre_inicio');
            $table->integer('anhio');
            $table->timestamps();

            // Foreign key con alumno
            $table->foreign('rut_alumno')
                  ->references('rut_alumno')
                  ->on('alumno')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('habilitacion_profesional');
    }
};