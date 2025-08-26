<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->decimal('buy_price', 10, 2)->after('sale_price');
            $table->decimal('discount', 10, 2)->default(0)->after('buy_price');
            $table->decimal('unit_price', 10, 2)->after('discount');
            $table->decimal('subtotal', 12, 2)->after('unit_price');
            $table->decimal('item_profit', 12, 2)->after('subtotal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('invoice_products', function (Blueprint $table) {
            $table->dropColumn([
                'buy_price',
                'discount',
                'unit_price',
                'subtotal',
                'item_profit',
            ]);
        });
    }
};
