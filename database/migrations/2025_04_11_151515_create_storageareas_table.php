<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('storageareas', function (Blueprint $table) {
            $table->id();
            $table->string('centerid',15);
            $table->string('warehouseid',15);
            $table->string('slug',150)->unique();
            $table->string('name',100);
            $table->string('configurationflag')->nullable();
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('schemaid',10)->nullable();
            $table->string('statusid',10)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('centerid')->references('centerid')->on('systemcenterdistributions')->onDelete('cascade');
            $table->foreign('warehouseid')->references('warehouseid')->on('systemwarehouses');
            $table->foreign('statusid')->references('statusid')->on('systemstatus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('storageareas');
    }
};
