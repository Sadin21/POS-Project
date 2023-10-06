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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code');
            $table->string('photo')->nullable();
            $table->string('sale_price')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('available_qty')->nullable();
            $table->foreignId('category_id')->constrained('categories');
            $table->timestamps();

            // BlameableObserver::blameableSchema($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
