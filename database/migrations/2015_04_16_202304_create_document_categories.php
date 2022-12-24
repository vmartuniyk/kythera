<?php
/*
 *
 * ./artisan migrate:make create_document_categories --create=document_categories
 * ./artisan migrate
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentCategories extends Migration
{
    public $timestamps = false;
    
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('document_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('document_id');
            $table->integer('category_id');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('document_categories');
    }
}
