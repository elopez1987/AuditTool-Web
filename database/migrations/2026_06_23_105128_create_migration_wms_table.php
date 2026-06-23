<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('migration_wms', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->string('warehouseid', 15);
            $table->string('locationid', 20);
            $table->string('lpnizel', 30);
            $table->string('lpnsap', 30);
            $table->string('create_by');
            $table->string('photo');
            $table->string('value01')->nullable();
            $table->string('value02')->nullable();
            $table->string('value03')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('migration_wms');
    }
};
