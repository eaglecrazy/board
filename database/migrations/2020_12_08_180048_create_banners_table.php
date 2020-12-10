<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBannersTable extends Migration
{
    public function up()
    {
        Schema::create('banners_banners', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('status', 16);
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('region_id')->nullable();
            $table->string('url');
            $table->integer('views')->nullable();
            $table->integer('limit');
            $table->integer('clicks')->nullable();
            $table->integer('cost')->nullable();
            $table->string('format');
            $table->string('file');
            $table->string('reject_reason')->nullable();
            $table->timestamps();
            $table->timestamp('published_at')->nullable();
        });

        Schema::table('banners_banners', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('users')->onDelete('CASCADE');
            $table->foreign('category_id')->references('id')->on('advert_categories');
            $table->foreign('region_id')->references('id')->on('regions');
        });
    }

    public function down()
    {
        Schema::dropIfExists('banners_banners');
    }
}
