<?php
//http://laravel.com/docs/4.2/schema

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ConfideMergeUsersPersonsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('hide')->after('id')->tinyInteger()->default(0);
            $table->enum('gender', ['U','M','F'])->after('id')->default('U');
            $table->dateTime('date_of_birth')->after('id')->nullable();
            $table->dateTime('date_of_death')->after('id')->nullable();
            $table->integer('place_of_birth')->after('id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['hide', 'gender', 'date_of_birth', 'date_of_death', 'place_of_birth']);
        });
    }
}
