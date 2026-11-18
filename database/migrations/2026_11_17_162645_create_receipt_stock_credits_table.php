<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('receipt_stock_credits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('receiptasn_id')->constrained('receiptasn')->cascadeOnDelete();
            $table->foreignId('inventorypartnumber_id')->constrained('inventorypartnumbers');
            $table->decimal('quantity', 18, 4);
            $table->decimal('rotary', 18, 4);
            $table->decimal('definitive', 18, 4);
            $table->foreignId('inventoryunitsmeasure_id')->constrained('inventoryunitsmeasure');
            $table->timestamps();
            $table->index(['receiptasn_id']);
            $table->index(['inventorypartnumber_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('receipt_stock_credits');
    }
};
