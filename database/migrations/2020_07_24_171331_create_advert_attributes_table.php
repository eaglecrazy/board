<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertAttributesTable extends Migration
{
    public function up()
    {
        Schema::create('advert_attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id');
            $table->string('name', 255);
            $table->string('type', 255);
            $table->boolean('required');
            $table->json('variants');
            $table->integer('sort');
        });

        Schema::table('advert_attributes', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')->on('advert_categories')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::dropIfExists('advert_attributes');
    }
}
