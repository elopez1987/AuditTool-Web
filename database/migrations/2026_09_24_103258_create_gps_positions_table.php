<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gps_device_id')->constrained('gps_devices');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->decimal('speed', 6, 2)->nullable();
            $table->decimal('heading', 5, 2)->nullable();
            $table->decimal('altitude')->nullable();
            $table->unsignedTinyInteger('satellites')->nullable();
            $table->unsignedTinyInteger('battery_level')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
            $table->index(['gps_device_id', 'recorded_at']); // clave para consultas de última posición / histórico
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('gps_positions');
    }
};
