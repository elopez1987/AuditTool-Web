<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storageshipmentchecktotal', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centerid');
            $table->foreignId('warehouseid');
            $table->foreignId('orderid');
            $table->foreignId('locationid');
            $table->integer('quantity');
            $table->string('accessories')->nullable();
            $table->string('changes')->nullable();
            $table->smalldatetime('startprocess')->nullable();
            $table->smalldatetime('finishprocess')->nullable();
            $table->foreignId('userid');
            $table->string('value01')->nullable();
            $table->string('value02')->nullable();
            $table->string('value03')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storageshipmentchecktotal');
    }
};
