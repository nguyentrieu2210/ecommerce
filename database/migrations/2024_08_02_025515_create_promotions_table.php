<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->longText('detail');
            $table->timestamp('start_date');
            $table->timestamp('end_date')->nullable();
            $table->tinyInteger('never_end_date')->default(1);
            $table->tinyInteger('publish')->default(2);
            $table->string('method');
            $table->string('model');
            $table->timestamp('deleted_at')->nullable();
            $table->decimal('discount_value', 15, 2)->nullable();
            $table->string('discount_type')->nullable();
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
        Schema::dropIfExists('promotions');
    }
}
