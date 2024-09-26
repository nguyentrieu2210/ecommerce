<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostCataloguePostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_catalogue_post', function (Blueprint $table) {
            $table->unsignedBigInteger('post_catalogue_id');
            $table->unsignedBigInteger('post_id');
            $table->foreign('post_catalogue_id')->references('id')->on('post_catalogues')->onDelete('cascade');
            $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('post_catalogue_post');
    }
}
