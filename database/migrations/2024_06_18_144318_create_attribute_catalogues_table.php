<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributeCataloguesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attribute_catalogues', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('canonical')->unique();
            $table->integer('level')->default(0);
            $table->nestedSet();
            $table->tinyInteger('publish')->default(2);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->tinyInteger('follow')->default(1);
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
        Schema::dropIfExists('attribute_catalogues');
    }
}
