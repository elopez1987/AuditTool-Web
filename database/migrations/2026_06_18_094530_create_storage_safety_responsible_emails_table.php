<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_safety_responsible_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_safety_responsible_area_id');
            $table->string('name', 100);
            $table->string('email', 100);
            $table->string('phone')->nullable();
            $table->integer('level');
            $table->integer('attempts')->nullable();

            $table->index('storage_safety_responsible_area_id');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_safety_responsible_emails');
    }
};
