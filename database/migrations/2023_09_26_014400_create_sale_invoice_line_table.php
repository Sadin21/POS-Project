<?php

use App\Observers\BlameableObserver;
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
        Schema::create('sale_invoice_line', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hdr_id')->constrained('sale_invoice_hdr');
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->bigInteger('sale_price');
            $table->integer('qty');
            $table->bigInteger('subtotal');
            $table->timestamps();

            // BlameableObserver::blameableSchema($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoice_line');
    }
};
