<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storageusagedataforklift', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('centerid');
            $table->foreignId('serialnumber');
            $table->string('horometro');
            $table->string('distance');
            $table->string('stationary');
            $table->bigint('userid');
            $table->string('value01')->nullable();
            $table->string('value02')->nullable();
            $table->string('value03')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storageusagedataforklift');
    }
};
