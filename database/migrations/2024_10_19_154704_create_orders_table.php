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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_type')->constrained('document_types')->cascadeOnDelete();
            $table->foreignId('branch_id')->constrained('branches')->cascadeOnDelete();
            $table->foreignId('seller_id')->constrained('employees')->cascadeOnDelete();
            $table->foreignId('document_type')->constrained('document_types');
            $table->string('order_type')->nullable();
            $table->string('order_status')->nullable();
            $table->string('order_payment_status')->nullable();
            $table->string('cashbox_operation_id')->nullable();
            $table->string('order_payment_reference')->nullable();
//            $table->sstrin

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
