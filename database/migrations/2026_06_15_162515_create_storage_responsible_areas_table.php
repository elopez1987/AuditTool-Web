<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_safety_responsible_areas', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->string('name');
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('configurationflag')->nullable();
            $table->string('schemaid', 10);
            $table->string('statusid', 10);
            $table->timestamps();

            $table->index(['centerid']);
            $table->index(['name']);
            $table->index(['statusid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_safety_responsible_areas');
    }
};
