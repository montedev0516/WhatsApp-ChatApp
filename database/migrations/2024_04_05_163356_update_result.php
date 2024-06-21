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
        Schema::create('UpdateResult', function (Blueprint $table) {

            $table->id();
            $table->string('user');
            $table->string('type');
            $table->string('value');
            $table->string('phonnumber');
            $table->string('time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('UpdateResult');
    }
};
