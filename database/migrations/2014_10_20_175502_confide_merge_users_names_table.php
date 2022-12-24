<?php
//php artisan migrate:make confide_merge_users_names_table --table="users"

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author virgilm
 *
 */
class ConfideMergeUsersNamesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('maidenname')->after('id')->nullable();
            $table->string('nickname')->after('id')->nullable();
            $table->string('lastname')->after('id');
            $table->string('middlename')->after('id')->nullable();
            $table->string('firstname')->after('id');
            $table->index('lastname');
            $table->index('nickname');
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
            $table->dropColumn(['firstname', 'middlename', 'lastname', 'nickname', 'maidenname']);
            //$table->dropIndex('lastname');
            //$table->dropIndex('nickname');
        });
    }
}
