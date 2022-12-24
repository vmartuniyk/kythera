<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUserTypeToAssignedRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assigned_roles', function (Blueprint $table) {
            $table->string('user_type')->default('App\\\Models\\\User');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assigned_roles', function (Blueprint $table) {
            $table->dropColumn('user_type');
        });
    }
}
