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
        Schema::create('supervisa', function (Blueprint $table) {
            $table->id();
            $table->integer('rut_profesor');
            $table->string('id_habilitacion');
            $table->enum('tipo_profesor', ['Prof_co_guia', 'Prof_guia', 'Prof_tutor', 'Prof_comision']);
            $table->timestamps();

            // Foreign keys
            $table->foreign('rut_profesor')
                  ->references('rut_profesor')
                  ->on('profesor_dinf')
                  ->onDelete('cascade');
                  
            $table->foreign('id_habilitacion')
                  ->references('id_habilitacion')
                  ->on('habilitacion_profesional')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['rut_profesor', 'id_habilitacion']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supervisa');
    }
};