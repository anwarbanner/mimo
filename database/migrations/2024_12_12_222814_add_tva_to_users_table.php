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
        $table->string('tva')->nullable()->after('register_number'); // Add the tva column
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('tva');
    });
}

};
