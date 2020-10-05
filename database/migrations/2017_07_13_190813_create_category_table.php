<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('categories');

        Schema::create('categories', function (Blueprint $table) {
           $table->increments('id');
           $table->string('title');
           $table->integer('parent_id');
           $table->string('image_name',255)->nullable();
           $table->string('icon_name',255)->nullable();
           $table->enum('status', ['Active', 'Inactive']);
           $table->enum('featured', ['Yes', 'No'])->default("Yes");
           $table->enum('browse', ['Yes', 'No'])->default("No");
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
        Schema::drop("categories");
    }
}
