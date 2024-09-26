<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_catalogue_id');
            $table->string('name');
            $table->string('code', 50)->nullable();
            $table->string('barcode', 100)->nulable();
            $table->float('weight')->default(0);
            $table->tinyInteger('mass')->default(0);
            $table->string('measure', 50)->nullable();
            $table->text('specifications')->nullable();
            $table->text('questions_answers')->nullable();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('canonical')->unique();
            $table->tinyInteger('allow_to_sell')->default(0);
            $table->tinyInteger('publish')->default(2);
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->text('album')->nullable();
            $table->text('attribute_catalogue')->nullable();
            $table->text('attribute')->nullable();
            $table->text('variant')->nullable();
            $table->tinyInteger('follow')->default(1);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id');
            $table->decimal('price', 15, 2)->nullable();
            $table->string('input_tax', 15)->nullable();
            $table->string('output_tax', 15)->nullable();
            $table->tinyInteger('tax_status')->default(1);
            $table->timestamp('deleted_at')->nullable();
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
        Schema::dropIfExists('products');
    }
}
