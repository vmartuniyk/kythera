<?php namespace Kythera\Commands;
/*
 *
SET FOREIGN_KEY_CHECKS=0;
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

DROP TABLE IF EXISTS `document_categories`;
CREATE TABLE IF NOT EXISTS `document_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `document_id` unsigned int(11) NOT NULL,
  `category_id` unsigned int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `dc` (`document_id`,`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci ;
SET FOREIGN_KEY_CHECKS=1;
 *
 * */

/*
 * ./artisan kythera:import_categories
 * */


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;

class Helper extends \Illuminate\Database\Eloquent\Model {

    protected $table = 'document_attributes';

    public $timestamps = false;

}

class KytheraFixContent extends Command {


    static public $counter = 0;
    static public $fixed = 0;

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:fixcontent';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Fix content';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
		$this->process();
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

	protected function process()
    {

        $this->info('');
        $this->info('Dst db: '. DB::connection()->getDatabaseName());
        $this->info('');
        if (!$this->confirm('Do you wish to continue? [y|N]')) {
            exit;
        }


        #options
	    DB::disableQueryLog();


        #fetch all content values
        //Helper::where('key', 'content')->orderBy('document_entity_id')->chunk(200, function($rows) {
        Helper::where('key', 'content')->chunk(200, function($rows) {

            //$this->info(sprintf('CHUNK #%d---------------------------------------------------', ++self::$counter));

            foreach($rows as $i=>$row) {

                if (stripos($row->value, 'dev.kythera-family.net')!==FALSE) {
                    ++self::$fixed;

                    $row->value = str_ireplace('dev.kythera-family.net', 'www.kythera-family.net', $row->value);
                    //$row->value = str_ireplace('http://dev.kythera-family.net', 'http://kfn.laravel.debian.mirror.virtec.org', $row->value);
                    $row->save();
                    $this->info(sprintf("FIXED %d %d", self::$fixed, $row->document_entity_id ));

                    /*
                    if (self::$fixed == 2) {
                        echo __FILE__ . __LINE__ . '<pre>$row->value=' . htmlentities(print_r($row, 1)) . '</pre>';die;
                    }
                    */



                }

            }

        });

	}

}
