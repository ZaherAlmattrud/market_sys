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
        Schema::create('sell_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('sell_id')->nullable();
            $table->double('total')->nullable();
            $table->string('description')->nullable();
            $table->string('quantity')->nullable();
            $table->double('price')->nullable();
            $table->double('pr')->nullable();
            $table->date('date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sell_details');
    }
};