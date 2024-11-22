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
    Schema::table('invoices', function (Blueprint $table) {
        $table->unsignedBigInteger('patient_id')->nullable()->after('id'); // Nullable to allow existing data
        $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('invoices', function (Blueprint $table) {
        $table->dropForeign(['patient_id']);
        $table->dropColumn('patient_id');
    });
}

};
