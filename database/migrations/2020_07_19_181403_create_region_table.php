<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegionTable extends Migration
{
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 255)->index();
            $table->string('slug', 255);
            $table->boolean('important')->default(false);
            $table->unsignedInteger('parent_id')->nullable();
            $table->unique(['parent_id', 'slug']);
            $table->unique(['parent_id', 'name']);
            $table->timestamps();
        });

        Schema::table('regions', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('regions')->onDelete('CASCADE');
        });
    }




    public function down()
    {
        Schema::table('regions', function (Blueprint $table) {
            Schema::dropIfExists('regions');
        });
    }
}
