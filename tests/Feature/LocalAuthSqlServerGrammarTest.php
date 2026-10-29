<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\SqlServerConnection;

it('compiles session security columns and transaction locks using SQL Server grammar without a connection', function (): void {
    $connection = new SqlServerConnection(static fn () => throw new RuntimeException('No live database is permitted.'));
    $connection->useDefaultSchemaGrammar();
    $blueprint = new Blueprint($connection, 'token_metadata');
    $blueprint->dateTime('session_expires_at', 6)->nullable();
    $blueprint->string('session_family_id', 36)->nullable();
    $blueprint->index(['user_id', 'session_family_id'], 'token_metadata_user_family_index');
    $statements = implode("\n", $blueprint->toSql());
    expect($statements)->toContain('datetime2(6)', 'nvarchar(36)', 'token_metadata_user_family_index');
    $query = $connection->table('users')->where('id', 1)->lockForUpdate()->toSql();
    expect($query)->toContain('with(rowlock,updlock,holdlock)');
});
