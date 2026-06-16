<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_safety_category_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('storage_category_safety_id');
            $table->string('name');
            $table->foreignId('storage_safety_responsible_area_id');
            $table->string('configurationflag')->nullable();
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('schamaid', 10)->nullable();
            $table->string('statusid', 10)->nullable();
            $table->timestamps();

            $table->index(['storage_category_safety_id']);
            $table->index(['name']);
            $table->index(['storage_safety_responsible_area_id']);
            $table->index(['statusid']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_safety_category_details');
    }
};
