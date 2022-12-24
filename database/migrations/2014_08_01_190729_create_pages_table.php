<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author virgilm
 *
 */
class CreatePagesTable extends Migration
{
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("sort")->default(10);
            $table->tinyInteger("active")->default(1);
            $table->string("uri"); //default is nullable
            $table->string("title");
            $table->text("content");
            $table->string("meta_title");
            $table->string("meta_keywords");
            $table->string("meta_description");
            $table->timestamps();
            //$table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("pages");
    }
}
