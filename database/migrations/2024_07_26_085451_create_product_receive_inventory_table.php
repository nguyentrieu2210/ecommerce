<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductReceiveInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_receive_inventory', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('receive_inventory_id');
            $table->unsignedBigInteger('product_variant_id')->nullable();
            $table->string('name');
            $table->string('name_variant')->nullable();
            $table->string('sku');
            $table->integer('quantity');
            $table->integer('quantity_rejected')->default(0);
            $table->text('rejection_reason')->nullable();
            $table->decimal('cost_price', 15, 2); //gía nhập (được cung cấp bởi nhà cung cấp (thường là giá vốn) sau khi đã thêm chiết khấu trên từng sản phẩm)
            $table->decimal('discount_value', 15, 2)->nullable();//chiết khấu trên từng sản phẩm nhập
            $table->string('discount_type', 20)->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('receive_inventory')->references('id')->on('receive_inventories')->onDelete('cascade');
            $table->foreign('product_variant_id')->references('id')->on('product_variants');
            $table->string('image')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_receive_inventory');
    }
}
