<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('securityaccessorigins', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->foreignId('storageunittype_id')->constrained('storageunittypes');
            $table->string('username', 20);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('securityaccessorigins');
    }
};
