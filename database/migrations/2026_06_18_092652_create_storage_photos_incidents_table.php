<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_photos_incidents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_incidence_safety_id');
            $table->string('path');
            $table->foreignId('create_by');
            $table->timestamp('created_at');
            $table->index(['create_by']);
            $table->index(['storage_incidence_safety_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_photos_incidents');
    }
};
