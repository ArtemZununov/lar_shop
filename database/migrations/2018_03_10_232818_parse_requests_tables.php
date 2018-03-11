<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ParseRequestsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parse_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('is_saved')->default(false);
            $table->string('csv_file_link')->nullable();
            $table->timestamps();
        });
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parse_request_id')->unsigned();
            $table->foreign('parse_request_id')->references('id')->on('parse_requests');
            $table->string('title');
            $table->longText('content');
            $table->boolean('is_bold');
            $table->string('link');
            $table->timestamp('publish_date');
        });
        Schema::create('articles_tags', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('article_id')->unsigned();
            $table->foreign('article_id')->references('id')->on('articles');
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parse_requests');
        Schema::dropIfExists('articles');
        Schema::dropIfExists('articles_tags');
    }
}
