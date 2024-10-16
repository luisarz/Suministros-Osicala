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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->foreignId('country_id')->constrained('countries', 'id');
            $table->foreignId('departamento_id')->constrained('departamentos', 'id');
            $table->foreignId('municipio_id')->constrained('distritos', 'id');
            $table->foreignId('distrito_id')->constrained('municipalities', 'id');
            $table->foreignId('economic_activities_id')->constrained('economic_activities', 'id');
            $table->foreignId('wherehouse_id')->constrained('branches', 'id');
//            $table->foreignId('seller_id')->nullable();
            $table->string('address')->nullable();
            $table->string('nrc')->nullable();
            $table->string('dui')->nullable();
            $table->string('nit')->nullable();
            $table->boolean('is_taxed')->default(true);//si es contribuyente o exento
            $table->boolean('is_active')->default(true);
            $table->boolean('is_credit_client')->default(false);
            $table->decimal('credit_limit',10,2)->nullable();
            $table->integer('credit_days')->nullable();
            $table->decimal('credit_balance',10,2)->nullable();
            $table->date('last_purched')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
