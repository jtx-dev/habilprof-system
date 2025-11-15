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
        Schema::create('prtut', function (Blueprint $table) {
            $table->string('id_habilitacion')->primary();
            $table->timestamps();

            // Foreign key con habilitacion_profesional
            $table->foreign('id_habilitacion')
                  ->references('id_habilitacion')
                  ->on('habilitacion_profesional')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prtut');
    }
};