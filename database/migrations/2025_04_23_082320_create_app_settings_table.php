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
        Schema::create('app_settings', function (Blueprint $table) {
            $table->char('id', 36)->primary(); // en MariaDB usar char(36) en vez de uuid()

            $table->string('tab')->nullable();
            $table->string('key')->nullable();      // palabra reservada
            $table->longText('default')->nullable(); // palabra reservada
            $table->longText('value')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
