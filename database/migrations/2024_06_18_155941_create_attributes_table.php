<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attribute_catalogue_id')->default(0);
            $table->string('name');
            $table->string('canonical')->unique();
            $table->tinyInteger('publish')->default(2);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('follow')->default(0);
            $table->integer('order')->default(0);
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('attributes');
    }
}
