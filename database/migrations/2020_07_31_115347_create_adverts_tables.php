<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertsTables extends Migration
{
    public function up()
    {
        Schema::create('advert_adverts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('category_id')->nullable()->default(null);
            $table->unsignedInteger('region_id')->nullable()->default(null);
            $table->string('title', 255);
            $table->integer('price');
            $table->text('address')->nullable()->default(null);
            $table->text('content');
            $table->string('status', 16);
            $table->text('reject_reason')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
            $table->timestamp('expires_at')->nullable();
        });

        Schema::table('advert_adverts', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('category_id')->references('id')->on('advert_categories')->onDelete('SET NULL');
            $table->foreign('region_id')->references('id')->on('regions')->onDelete('SET NULL');
        });


        Schema::create('advert_attributes_values', function (Blueprint $table) {
            $table->unsignedBigInteger('advert_id');
            $table->unsignedInteger('attribute_id');
            $table->string('value', 255);
            $table->primary(['advert_id', 'attribute_id']);
        });

        Schema::table('advert_attributes_values', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('advert_adverts')->onDelete('CASCADE');
            $table->foreign('attribute_id')->references('id')->on('advert_attributes')->onDelete('CASCADE');
        });


        Schema::create('advert_photos', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('advert_id');
            $table->string('file', 255);
        });

        Schema::table('advert_photos', function (Blueprint $table) {
            $table->foreign('advert_id')->references('id')->on('advert_adverts')->onDelete('CASCADE');
        });
    }

    public function down()
    {
        Schema::table('advert_photos', function (Blueprint $table) {
            $table->dropForeign(['advert_id']);
        });
        Schema::dropIfExists('advert_photos');

        Schema::table('advert_attributes_values', function (Blueprint $table) {
            $table->dropForeign(['advert_id']);
            $table->dropForeign(['attribute_id']);
        });
        Schema::dropIfExists('advert_attributes_values');

        Schema::table('advert_adverts', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['region_id']);
        });
        Schema::dropIfExists('advert_adverts');








    }
}
