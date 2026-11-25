<?php

declare(strict_types=1);

use Illuminate\Database\SqlServerConnection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

it('compiles the incremental migration and locks for SQL Server without touching a database', function (): void {
    $connection = new SqlServerConnection(
        static fn () => throw new RuntimeException('A schema compilation test must not open a connection.'),
        'schema_preview',
        '',
        ['driver' => 'sqlsrv'],
    );
    DB::extend('schema_preview', fn () => $connection);
    config(['database.connections.schema_preview' => ['driver' => 'schema_preview']]);
    $original = DB::getDefaultConnection();
    DB::setDefaultConnection('schema_preview');
    Schema::clearResolvedInstance('db.schema');

    try {
        $queries = $connection->pretend(function (): void {
            (require database_path('migrations/2026_11_25_105202_add_expected_line_references_to_receipt_verifications.php'))->up();
        });
        $sql = implode("\n", array_column($queries, 'query'));
        expect($sql)->toContain('decimal(18, 4)')
            ->toContain('uniqueidentifier')
            ->toContain('rv_stock_identity_fk')
            ->toContain('on delete no action on update no action')
            ->toContain('WHERE client_operation_id IS NOT NULL')
            ->toContain('INCLUDE (quantity)')
            ->toContain('rv_line_source_check');
        expect($connection->table('receiptasn')->where('id', 1)->lockForUpdate()->toSql())
            ->toContain('with(rowlock,updlock,holdlock)');
    } finally {
        DB::setDefaultConnection($original);
        Schema::clearResolvedInstance('db.schema');
    }
});
