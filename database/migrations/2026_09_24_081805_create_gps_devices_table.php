<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gps_devices', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->string('imei')->unique();
            $table->string('model');
            $table->string('sim_number')->nullable();
            $table->string('value01')->nullable();
            $table->string('value02')->nullable();
            $table->string('value03')->nullable();
            $table->string('value04')->nullable();
            $table->string('value05')->nullable();
            $table->string('schemaid', 10)->default('STS');
            $table->string('statusid', 10)->default('E');
            $table->timestamps();

            $table->index('centerid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gps_devices');
    }
};
