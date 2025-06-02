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
        Schema::create('items_purchase', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal('price', 10, 2);
            $table->integer('count')->default(0);
            $table->unsignedInteger('id_item');
            $table->unsignedInteger('user_id');
            $table->timestamps();
            $table->foreign('id_item')->references('id')->on('items')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items_purchase');
    }
};
