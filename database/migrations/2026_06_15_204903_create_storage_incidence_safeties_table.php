<?php

use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('storage_incidence_safeties', function (Blueprint $table) {
            $table->id();
            $table->string('centerid', 15);
            $table->foreignId('storage_category_safety_id');
            $table->foreignId('storage_safety_category_detail_id');
            $table->string('name')->nullable();
            $table->string('description');
            $table->string('priority')->nullable();
            $table->integer('created_by');
            $table->integer('closed_by')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->string('location')->nullable();
            $table->string('auxiliary01')->nullable();
            $table->string('auxiliary02')->nullable();
            $table->string('auxiliary03')->nullable();
            $table->string('schemaid', 10);
            $table->string('statusid', 10);
            $table->timestamps();

            $table->index(['centerid']);
            $table->index(['storage_category_safety_id']);
            $table->index(['storage_safety_category_detail_id']);
            $table->index(['statusid']);
        });

        SystemSchema::create([
            'schemaid' => 'IRS',
            'name' => 'Incidence Safety Register',
        ]);

        SystemStatus::create([
            'schemaid' => 'IRS',
            'statusid' => 'IRS-01',
            'name' => 'PENDIENTE',
        ]);
        SystemStatus::create([
            'schemaid' => 'IRS',
            'statusid' => 'IRS-02',
            'name' => 'EN PROCESO',
        ]);
        SystemStatus::create([
            'schemaid' => 'IRS',
            'statusid' => 'IRS-03',
            'name' => 'FINALIZADO',
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('storage_incidence_safeties');
    }
};
