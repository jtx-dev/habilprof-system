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
        Schema::create('realiza', function (Blueprint $table) {
            $table->id();
            $table->string('id_habilitacion');
            $table->integer('rut_empresa');
            $table->timestamps();

            // Foreign keys
            $table->foreign('id_habilitacion')
                  ->references('id_habilitacion')
                  ->on('prtut')
                  ->onDelete('cascade');
                  
            $table->foreign('rut_empresa')
                  ->references('rut_empresa')
                  ->on('empresa')
                  ->onDelete('cascade');

            // Unique constraint
            $table->unique(['id_habilitacion', 'rut_empresa']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('realiza');
    }
};