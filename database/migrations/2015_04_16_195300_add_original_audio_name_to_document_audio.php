<?php
/*
 *
 * CREATE TABLE IF NOT EXISTS `document_audio` (
 * `id` int(11) NOT NULL AUTO_INCREMENT,
 * `entry_id` int(11) NOT NULL,
 * `audio_name` varchar(255) NOT NULL,
 * `audio_path` varchar(255) NOT NULL,
 * `recorded` date DEFAULT NULL,
 * PRIMARY KEY (`id`),
 * UNIQUE KEY `entry_id` (`entry_id`)
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
 *
 * ./artisan migrate:make add_original_audio_name_to_document_audio --table=document_audio
 * ./artisan kythera:import_audio
 * ./artisan migrate
 *
 */
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOriginalAudioNameToDocumentAudio extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // truncate first
        \DB::table('document_audio')->truncate();

        Schema::table('document_audio', function (Blueprint $table) {
            $table->string('original_audio_name')->after('entry_id')->nullable();
            $table->unique('audio_name', 'audio_name_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('document_audio', function (Blueprint $table) {
            $table->dropColumn(['original_audio_name']);
            $table->dropUnique('audio_name_unique');
        });
    }
}
