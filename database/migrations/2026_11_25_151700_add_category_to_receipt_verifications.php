<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('receipt_verifications', function (Blueprint $table) {
            $table->foreignId('catetory_incidence_id')->nullable()->constrained('storagecategoryincidences');
            $table->string('evidence_path')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('receipt_verifications', function (Blueprint $table) {
            //
        });
    }
};
