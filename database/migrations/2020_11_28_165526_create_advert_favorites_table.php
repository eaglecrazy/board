<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertFavoritesTable extends Migration
{
    public function up()
    {
        Schema::create('advert_favorites', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('advert_id');
            $table->primary(['user_id', 'advert_id']);
        });

        Schema::table('advert_favorites', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('advert_id')->references('id')->on('advert_adverts')->onDelete('CASCADE');
        });
    }


    public function down()
    {
        Schema::table('advert_favorites', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['advert_id']);
        });
        Schema::dropIfExists('advert_favorites');
    }
}
