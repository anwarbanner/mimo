<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visite_id')->constrained()->onDelete('cascade');
            $table->decimal('total_amount', 10, 2);
            $table->timestamps();
        });
    
        Schema::create('invoice_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
        });
    
        Schema::create('invoice_soin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->onDelete('cascade');
            $table->foreignId('soin_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
