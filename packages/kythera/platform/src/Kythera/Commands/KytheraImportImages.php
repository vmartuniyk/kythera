<?php namespace Kythera\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;

class KytheraImportImages extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'kythera:import_images';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Import images';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct ();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		//
		$limit = $this->argument ( 'limit' );
		$this->import ( $limit );
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments() {
		return array (
			array (
				'limit',
				InputArgument::OPTIONAL,
				'Total records to import.',
				'ALL'
			)
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions() {
		// ./artisan kythera:import_images
		return array (
				array (
						'dump',
						null,
						InputOption::VALUE_OPTIONAL,
						'Dump queries.',
						null
				)
		);
	}


	private function verifyPath($path)
	{
		return str_replace('//', '/', $path.'/');
	}


	private function verifyDate($date)
	{
		$result = ($date != '0000-00-00' && ($date != '')) ? $date : 'NULL';
		//$this->info(sprintf(" %s   >>     %s", $date, $result));
		return $result;
	}

	protected function import($limit) {
		#options
		DB::disableQueryLog ();
		#rsync -av -e 'ssh -p 10022' --exclude=bb-download/ --exclude=bb-download/ root@87.106.242.9:/srv/www/vhosts/kythera-family.net/httpdocs ./
		$src_path = Config::get('app.local') ? '/home/virgil/html/kytherafamilynet/html/'
				                             : '/var/www/vhosts/kythera-family.net/import.kythera-family.net/httpdocs/';
		$dst_path = public_path().'/';


		#fetch
		if ($limit == 'ALL') {
			$rows = DB::connection ( 'import' )->select ( DB::raw ( "
                            SELECT
                                *
                            FROM
                              imagedocuments
                            " ) );
		} else {
			$rows = DB::connection ( 'import' )->select ( DB::raw ( sprintf ( "
                            SELECT
                                *
                            FROM
                              imagedocuments
                            LIMIT %d;
                            ", $limit ) ) );
		}

		$i = 0;
		if ($c = count ( $rows )) {
			$this->info ( sprintf ( "Image records to be imported: %d", $c ) );

			// truncate
			DB::table ( "document_images" )->truncate ();

			// insert records
			$errors = array ();
			$infos = array ();
			$queries = array ();
			foreach ( $rows as $row ) {
				$date = $this->verifyDate($row->taken);

				$query = sprintf( "
	                    INSERT INTO document_images
	                    SET
	                        id=%d,
	                        entry_id=%d,
	                        original_image_name=%s,
	                        image_name='%s',
	                        image_path='%s',
	                        taken='%s',
	                        photographer_id=%s,
	                        album_id=%s
	                        ;
	                    ", $row->id, $row->entry_id,
						$row->original_image_name ? DB::getPdo ()->quote ( $row->original_image_name ) : 'NULL',
						$row->image_name, $this->verifyPath($row->image_path),
						$date,
						$row->photographer_id ?  : 'NULL', $row->album_id ?  : 'NULL' );
				$queries [] = $query;

				if (DB::statement ( $query )) {
					$i++;
					if ($i%1000==0)
					$this->info ( sprintf ( "    (%d-%d) inserted id:%d", $i, $c, $row->id ) );

					//copy file
					$src = sprintf('%s%s%s', $src_path, $this->verifyPath($row->image_path), $row->image_name);
					$dst = sprintf('%s%s%s', $dst_path, $this->verifyPath($row->image_path), $row->image_name);
					@mkdir(sprintf('%s%s', $dst_path, $this->verifyPath($row->image_path)));
					if (file_exists($src)) {
						if (!file_exists($dst)) {
							copy($src, $dst);
							$file = sprintf('    Copied %s to %s', $row->image_name, $dst);
							$this->info('  '.$file);

							//copy related
							/*
							* originalSize_
							* mediumSize_
							* textInclude_
							* thumb_
							*/
							$related_src = sprintf('%s%soriginalSize_%s', $src_path, $this->verifyPath($row->image_path), $row->image_name);
							if (file_exists($related_src)) {
								$dst = sprintf('%s%soriginalSize_%s', $dst_path, $this->verifyPath($row->image_path), $row->image_name);
								copy($related_src, $dst);
								$this->info('      '.$related_src);
							}
							$related_src = sprintf('%s%smediumSize_%s', $src_path, $this->verifyPath($row->image_path), $row->image_name);
							if (file_exists($related_src)) {
								$dst = sprintf('%s%smediumSize_%s', $dst_path, $this->verifyPath($row->image_path), $row->image_name);
								copy($related_src, $dst);
								$this->info('      '.$related_src);
							}
							$related_src = sprintf('%s%stextInclude_%s', $src_path, $this->verifyPath($row->image_path), $row->image_name);
							if (file_exists($related_src)) {
								$dst = sprintf('%s%stextInclude_%s', $dst_path, $this->verifyPath($row->image_path), $row->image_name);
								copy($related_src, $dst);
								$this->info('      '.$related_src);
							}
							$related_src = sprintf('%s%sthumb_%s', $src_path, $this->verifyPath($row->image_path), $row->image_name);
							if (file_exists($related_src)) {
								$dst = sprintf('%s%sthumb_%s', $dst_path, $this->verifyPath($row->image_path), $row->image_name);
								copy($related_src, $dst);
								$this->info('      '.$related_src);
							}
						}
					} else {
						$errors[]=sprintf("Error copying %s --> %s: %d", $src, $dst, $row->id);
					}
				} else {
					$errors [] = sprintf ( "Error inserting record " . $row ['id'] );
				}
			}

			$this->info ( sprintf ( "DONE: inserted %d from %d records", count ( $queries ), $c ) );
			$this->error ( implode ( chr ( 13 ) . chr ( 10 ), $errors ) );

			if ($this->option ( 'dump' )) {
				$this->info ( 'QUERIES:' );
				$this->info ( implode ( chr ( 13 ) . chr ( 10 ), $queries ) );
			}
		} else {
			$this->error ( "Import images failed: original table is empty." );
		}
	}
}
