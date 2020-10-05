<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilePictureTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('profile_picture');
        
        Schema::create('profile_picture', function (Blueprint $table) {
            $table->integer('user_id')->unique()->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->text('src')->nullable();
            $table->text('cover_image_src')->nullable();
            $table->enum('photo_source',['Facebook', 'Google','Twitter', 'Local'])->nullable();
            $table->enum('upload_source',['Local', 'Cloudinary'])->default("Local");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_picture');
    }
}