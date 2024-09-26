<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddInfoToProductWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_warehouse', function (Blueprint $table) {
            //
            $table->integer('stock')->default(0); //số lượng hàng hóa đang giao dịch (nằm trong các đơn hàng)
            $table->integer('incoming')->default(0); //số lượng hàng hóa đang về (nằm trong các đơn nhập hàng)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_warehouse', function (Blueprint $table) {
            //
            $table->dropColumn('stock');
            $table->dropColumn('incoming');
        });
    }
}
