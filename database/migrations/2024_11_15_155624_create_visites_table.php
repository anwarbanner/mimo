<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('visites', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('id_rdv')->unique();
        $table->string('observation');
        $table->foreign('id_rdv')->references('id')->on('rdvs')->onDelete('cascade');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visites');
    }
};
