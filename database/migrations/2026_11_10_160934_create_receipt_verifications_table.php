<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiptasn_id')->constrained('receiptasn')->onDelete('cascade');
            $table->foreignId('inventorypartnumber_id')->constrained('inventorypartnumbers');
            $table->integer('quantity');
            $table->string('type_id', 15);
            $table->foreignId('inventoryunitsmeasure_id')->constrained('inventoryunitsmeasure');
            $table->date('productiondate')->nullable();
            $table->date('expiredate')->nullable();
            $table->string('expirecode')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['receiptasn_id']);
            $table->index(['inventorypartnumber_id']);
            $table->index(['type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_verifications');
    }
};
