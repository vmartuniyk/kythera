<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ExportNicknames extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:export';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Export nicknames.';

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
        $output_path = $this->argument('file');


        $headers = array('id', 'village');
        $rows = $this->getData();

        if ($output_path) {
            $h = fopen($output_path, 'w');
            if ($this->option('headers')) {
                fputcsv($h, $headers);
            }
            foreach ($rows as $row) {
                fputcsv($h, $row);
            }
            fclose($h);
            $this->info('Data exported to '. $output_path);
        } else {
            $table = $this->getHelperSet()->get('table');
            $table->setHeaders($headers)->setRows($rows);
            $table->render($this->getOutput());
        }
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('file', InputArgument::OPTIONAL, 'Output filename.'),
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
			array('headers', null, InputOption::VALUE_NONE, 'Display headers?', null),
		);
	}

	protected function getData() {
	    $items = DB::connection('import')
                ->table('villages')
                ->leftJoin('village_properties', 'villages.id', '=', 'village_properties.village_id')
                ->get();
	    foreach ($items as $item) {
	        $output[] = array($item->id, $item->village_name);
	    }
	    return $output;
	}
}
