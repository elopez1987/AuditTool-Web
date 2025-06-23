<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storagereceiptperiods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centerid');
            $table->foreignId('warehouseid');
            $table->string('name');
            $table->date('startdate');
            $table->date('finishdate');
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->bigint('useid');
            $table->foreignId('schemaid');
            $table->foreignId('statusid');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storagereceiptperiods');
    }
};
