<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author virgilm
 *
 */
class CreateNamesTable extends Migration
{
    
    public $timestamps = false;

    public function up()
    {
        Schema::create("names", function ($table) {
            $table->increments("id");
            $table->string("firstname");
            $table->string("middlename");
            $table->string("lastname");
        });
    }

    public function down()
    {
        Schema::dropIfExists("names");
    }
}
