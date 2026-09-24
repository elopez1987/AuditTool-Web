<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('geofences', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->string('name', 100);
            $table->string('type')->default('polygon');
            $table->string('schemaid', 10)->default('STS');
            $table->string('statusid', 10)->default('E');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('geofences');
    }
};
