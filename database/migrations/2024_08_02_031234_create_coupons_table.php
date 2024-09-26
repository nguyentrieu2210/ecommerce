<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->longText('detail');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->tinyInteger('never_end_date')->default(1);
            $table->tinyInteger('publish')->default(2);
            $table->string('method');
            $table->timestamp('deleted_at')->nullable();
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->string('discount_type')->nullable();
            $table->decimal('max_discount', 15, 2)->nullable();
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
        Schema::dropIfExists('coupons');
    }
}
