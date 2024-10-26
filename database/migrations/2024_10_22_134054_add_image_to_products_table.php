<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImageToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('image')->nullable(); // Add a nullable image column
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('image'); // Drop the image column on rollback
        });
    }
}
