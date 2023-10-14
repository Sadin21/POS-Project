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
        Schema::create('sale_invoice_hdr', function (Blueprint $table) {
            $table->id();
            $table->string('sale_no');
            $table->bigInteger('subtotal');
            $table->bigInteger('discount')->nullable();
            $table->bigInteger('grandtotal');
            $table->integer('total_qty');
            $table->string('payment')->nullable();
            $table->bigInteger('cash_amount')->nullable();
            $table->bigInteger('change_amount')->nullable();
            $table->string('status')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // BlameableObserver::blameableSchema($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_invoice_hdr');
    }
};
