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
    Schema::table('users', function (Blueprint $table) {
        $table->string('cie')->nullable(); // Corporate Identification Entity
        $table->string('fiscal_id')->nullable(); // Fiscal Identifier
        $table->string('register_number')->nullable(); // Registration Number
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['cie', 'fiscal_id', 'register_number']);
    });
}

};
