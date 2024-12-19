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
        Schema::create('visite_images', function (Blueprint $table) {
            $table->id();
            $table->longblob('images');
            $table->unsignedBigInteger('visite_id');
            $table->text('description');
            $table->timestamps();
            $table->foreign('visite_id')->references('id')->on('visites')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visite_images');
    }
};
