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
        Schema::table('receipt_stock_credits', function (Blueprint $table): void {
            $table->decimal('quantity_missing', 18, 4)->nullable();
            $table->unique(['id', 'receiptasn_id', 'inventorypartnumber_id', 'inventoryunitsmeasure_id'], 'rsc_line_identity_unique');
            $table->index(['receiptasn_id', 'inventorypartnumber_id', 'inventoryunitsmeasure_id'], 'rsc_receipt_product_unit_index');
        });
        Schema::table('receiptstock', function (Blueprint $table): void {
            $table->unique(['id', 'receiptid', 'partnumberid', 'unitmeasureid'], 'rs_line_identity_unique');
            $table->index(['receiptid', 'partnumberid', 'unitmeasureid'], 'rs_receipt_product_unit_index');
        });
        Schema::table('receipt_verifications', function (Blueprint $table): void {
            $table->decimal('quantity', 18, 4)->change();
            $table->foreignId('receiptstock_id')->nullable();
            $table->foreignId('receipt_stock_credit_id')->nullable();
            $table->uuid('client_operation_id')->nullable();
            $table->string('request_fingerprint', 64)->nullable();
            $table->json('balance_snapshot')->nullable();
            $table->foreign(['receiptstock_id', 'receiptasn_id', 'inventorypartnumber_id', 'inventoryunitsmeasure_id'], 'rv_stock_identity_fk')
                ->references(['id', 'receiptid', 'partnumberid', 'unitmeasureid'])
                ->on('receiptstock')->noActionOnDelete()->noActionOnUpdate();
            $table->foreign(['receipt_stock_credit_id', 'receiptasn_id', 'inventorypartnumber_id', 'inventoryunitsmeasure_id'], 'rv_credit_identity_fk')
                ->references(['id', 'receiptasn_id', 'inventorypartnumber_id', 'inventoryunitsmeasure_id'])
                ->on('receipt_stock_credits')->noActionOnDelete()->noActionOnUpdate();
        });

        if (DB::getDriverName() === 'sqlsrv') {
            DB::statement('CREATE UNIQUE INDEX rv_operation_unique ON receipt_verifications (receiptasn_id, client_operation_id) WHERE client_operation_id IS NOT NULL');
            DB::statement('CREATE INDEX rv_stock_balance_index ON receipt_verifications (receiptstock_id, type_id) INCLUDE (quantity) WHERE receiptstock_id IS NOT NULL');
            DB::statement('CREATE INDEX rv_credit_balance_index ON receipt_verifications (receipt_stock_credit_id, type_id) INCLUDE (quantity) WHERE receipt_stock_credit_id IS NOT NULL');
            DB::statement('ALTER TABLE receipt_verifications ADD CONSTRAINT rv_line_source_check CHECK (
                (receiptstock_id IS NULL AND receipt_stock_credit_id IS NULL AND client_operation_id IS NULL)
                OR
                (inventoryunitsmeasure_id IS NOT NULL AND
                    ((receiptstock_id IS NOT NULL AND receipt_stock_credit_id IS NULL)
                    OR (receiptstock_id IS NULL AND receipt_stock_credit_id IS NOT NULL)))
            )');
            DB::statement("ALTER TABLE receipt_verifications ADD CONSTRAINT rv_capture_check CHECK (
                client_operation_id IS NULL OR (
                    quantity > 0 AND type_id IN ('TRP-01', 'TRP-02')
                    AND request_fingerprint IS NOT NULL
                    AND (productiondate IS NULL OR expiredate IS NULL OR expiredate >= productiondate)
                )
            )");
        } else {
            Schema::table('receipt_verifications', function (Blueprint $table): void {
                $table->unique(['receiptasn_id', 'client_operation_id'], 'rv_operation_unique');
                $table->index(['receiptstock_id', 'type_id'], 'rv_stock_balance_index');
                $table->index(['receipt_stock_credit_id', 'type_id'], 'rv_credit_balance_index');
            });
        }
    }

    /**
     * Decimal captures and historical references cannot be safely reduced to the old schema.
     */
    public function down(): void
    {
        throw new RuntimeException('Esta migración conserva trazabilidad y cantidades decimales. Use una migración correctiva; no se permite un rollback destructivo.');
    }
};
