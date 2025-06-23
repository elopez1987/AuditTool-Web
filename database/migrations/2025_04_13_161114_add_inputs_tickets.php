<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('storagereceiptunitticket', function (Blueprint $table) {
            $table->bigInteger('responsibleid',)->nullable()->after('weightout');
            $table->bigInteger('areaid')->nullable()->after('responsibleid');
            $table->foreign('responsibleid')->references('id')->on('users');
            $table->foreign('areaid')->references('id')->on('storageareas');
        });
    }

    public function down(): void
    {
        Schema::table('storagereceiptunitticket', function (Blueprint $table) {
            //
        });
    }
};
