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
        Schema::create('arresteds', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->double('total');
            $table->date('date');
            $table->string('notes')->nullable();
            $table->unsignedBigInteger('account_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('arresteds');
    }
};
