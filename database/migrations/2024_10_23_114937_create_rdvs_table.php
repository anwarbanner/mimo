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
        Schema::create('rdvs', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('patient_id')->unsigned()->nullable(); // Make nullable if not always required
            $table->string('title'); // Use 'title' instead of 'motif'
            $table->dateTime('start'); // Combine date and time into one column
            $table->dateTime('end'); // Combine date and time into one column
            $table->boolean('allDay')->default(false); // Add an allDay field
            $table->string('etat')->default('ouvert'); // Set default status to 'ouvert'
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade')->nullable();
            $table->timestamps();
        });


    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rdvs');
    }
};
