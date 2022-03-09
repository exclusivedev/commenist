<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('commenter_id');
            $table->morphs('commentable');
            $table->text('comment');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->integer('rating')->default(0);
            $table->foreign('parent_id')->references('id')->on('comments')->onDelete('cascade');
            $table->index('commenter_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comments');
    }
}
