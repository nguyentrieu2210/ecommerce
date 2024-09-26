<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceiveInventoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_inventories', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('reference_code')->nullable();//mã tham chiếu
            $table->text('description')->nullable();
            $table->decimal('price_total', 15, 2);
            $table->decimal('discount_value', 15, 2)->nullable();//chiết khấu cho đơn đặt hàng nhập
            $table->string('discount_type', 20)->nullable();//kiểu chiết khấu 1:amount, 2:percent
            $table->string('status_returned')->nullable();
            $table->text('import_fee')->nullable();
            $table->string('status_receive_inventory')->default('pending');
            $table->string('status_payment')->default('pending');
            $table->unsignedBigInteger('purchase_order_id')->nullable();
            $table->foreign('purchase_order_id')->references('id')->on('purchase_orders');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->unsignedBigInteger('warehouse_id');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
            $table->dateTime('expected_day')->nullable();
            $table->integer('quantity_total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('receive_inventories');
    }
}
