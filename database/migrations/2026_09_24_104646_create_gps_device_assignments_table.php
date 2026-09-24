<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_device_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gps_device_id')->constrained('gps_devices');
            $table->foreignId('receiptunit_id')->constrained('receiptunits');
            $table->timestamp('assigned_at');
            $table->timestamp('unassigned_at')->nullable();
            $table->timestamps();

            $table->index(['gps_device_id', 'unassigned_at']); // para saber si un GPS está libre
            $table->index(['receiptunit_id', 'unassigned_at']); // para saber el GPS activo de una unidad en patio
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('gps_device_assignments');
    }
};
