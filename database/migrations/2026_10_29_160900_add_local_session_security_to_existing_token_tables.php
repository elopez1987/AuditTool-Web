<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('token_metadata', function (Blueprint $table): void {
            $table->string('session_family_id', 36)->nullable();
            $table->dateTime('session_started_at', 6)->nullable();
            $table->dateTime('session_expires_at', 6)->nullable();
            $table->string('credential_fingerprint', 64)->nullable();
            $table->bigInteger('device_id')->nullable();
            $table->index(['user_id', 'session_family_id'], 'token_metadata_user_family_index');
            $table->index(['user_id', 'device_id'], 'token_metadata_user_device_index');
        });

        Schema::table('oauth_refresh_tokens', function (Blueprint $table): void {
            $table->dateTime('consumed_at', 6)->nullable();
            $table->string('replaced_by_id', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('oauth_refresh_tokens', function (Blueprint $table): void {
            $table->dropColumn(['consumed_at', 'replaced_by_id']);
        });
        Schema::table('token_metadata', function (Blueprint $table): void {
            $table->dropIndex('token_metadata_user_family_index');
            $table->dropIndex('token_metadata_user_device_index');
            $table->dropColumn([
                'session_family_id', 'session_started_at', 'session_expires_at',
                'credential_fingerprint', 'device_id',
            ]);
        });
    }
};
