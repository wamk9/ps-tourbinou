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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->bigInteger('hour_id')->unsigned();
            $table->bigInteger('destination_id')->unsigned();
            $table->string('description', 260)->nullable();

            $table->foreign('hour_id')->references('id')->on('hours')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreign('destination_id')->references('id')->on('destinations')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
