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
        Schema::create('contingencies', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('branches');

            $table->string('uuid_hacienda', 255);

            $table->dateTime('start_date');
            $table->dateTime('end_date')->nullable();

            $table->unsignedBigInteger('contingency_types_id');
            $table->foreign('contingency_types_id')->references('id')->on('contingency_types');

            $table->string('contingency_motivation', 255)->nullable();

            $table->tinyInteger('is_close')->nullable();

            $table->timestamps(); // created_at, updated_at (nullable por defecto)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contingencies');
    }
};
