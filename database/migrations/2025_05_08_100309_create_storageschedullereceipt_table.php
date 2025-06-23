<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storageschedullereceipt', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centerid');
            $table->foreignId('warehouseid');
            $table->date('workday');
            $table->foreignId('turnid')->nullable();
            $table->foreignId('secuence')->nullable();
            $table->foreignId('receiptid')->nullable();
            $table->string('value01')->nullable();
            $table->string('value02')->nullable();
            $table->string('value03')->nullable();
            $table->string('value04')->nullable();
            $table->string('value05')->nullable();
            $table->string('schemaid');
            $table->string('statusid');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storageschedullereceipt');
    }
};
