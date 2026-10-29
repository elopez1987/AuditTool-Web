<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('users')->select('username')->whereNotNull('username')
            ->groupBy('username')->havingRaw('COUNT(*) > 1')->exists()
            || DB::table('securitydeviceforuser')->select(['username', 'serialnumber'])
                ->groupBy(['username', 'serialnumber'])->havingRaw('COUNT(*) > 1')->exists()) {
            throw new RuntimeException('Resolve duplicate usernames or device assignments before adding security indexes.');
        }

        // Preserve withTrashed login semantics: each non-null username identifies exactly one account.
        DB::statement('CREATE UNIQUE INDEX [users_local_username_unique] ON [users] ([username]) WHERE [username] IS NOT NULL');
        Schema::table('securitydeviceforuser', function (Blueprint $table): void {
            $table->unique(['username', 'serialnumber'], 'securitydeviceforuser_user_serial_unique');
        });
    }

    public function down(): void
    {
        Schema::table('securitydeviceforuser', function (Blueprint $table): void {
            $table->dropUnique('securitydeviceforuser_user_serial_unique');
        });
        Schema::table('users', function (Blueprint $table): void {
            $table->dropIndex('users_local_username_unique');
        });
    }
};
