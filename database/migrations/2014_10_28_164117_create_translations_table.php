<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * @author virgilm
 *
 */
class CreateTranslationsTable extends Migration
{
    
    /**
     * Disable record change date fields
     *
     */
    public $timestamps = false;
    

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('translations', function (Blueprint $table) {
            $table->integer('id'); //row id of a table
            $table->string('t', 64); //table name
            $table->string('l', 2); //language
            $table->string('k'); //key
            $table->string('v'); //small value
            $table->text('m')->nullable(); //big value
            $table->primary(['id','t','l','k']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists("translations");
    }
}

/*
INSERT INTO `translations` (`id`, `t`, `l`, `k`, `v`, `m`) VALUES
(1, 'folders', 'gr', 'title', 'Κορυφαία μενού', NULL),
(2, 'folders', 'gr', 'title', 'Κύριο μενού', NULL),
(3, 'folders', 'gr', 'title', 'Υπο μενού', NULL),
(4, 'folders', 'gr', 'title', 'Κάτω μενού', NULL),
(13, 'pages', 'gr', 'title', 'Ονόματα', NULL),
(13, 'pages', 'gr', 'uri', 'Ονόματα', NULL),
(17, 'pages', 'gr', 'title', 'Άνθρωποι', NULL),
(17, 'pages', 'gr', 'uri', 'Άνθρωποι', NULL);
*/
