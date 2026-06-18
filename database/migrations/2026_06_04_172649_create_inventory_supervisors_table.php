<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_supervisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_head_id')->constrained('inventory_heads')->onDelete('cascade');
            $table->foreignId('user_id');

            $table->index(['inventory_head_id']);
            $table->index(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_supervisors');
    }
};
