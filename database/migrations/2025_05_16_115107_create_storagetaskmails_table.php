<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storagetaskmails', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('centerid');
            $table->foreignUuid('warehouseid');
            $table->string('modeltype');
            $table->string('modelid');
            $table->string('mailid');
            $table->string('value01')->nullable();
            $table->string('value02')->nullable();
            $table->string('value03')->nullable();
            $table->string('value04')->nullable();
            $table->string('value05')->nullable();
            $table->foreignId('schemaid');
            $table->foreignUuid('statusid')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storagetaskmails');
    }
};
