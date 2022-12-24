<?php
namespace Kythera\Models;

use App\Models\Translation;
use Kythera\Entity\Entity;
use Kythera\Models;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Config;
use Kythera\Router\Facades\Router;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DocumentEntityException extends \Exception {}

class DocumentEntity extends Entity
{

    //todo: replace constant with PageEntity:
    const DT_GUEST_BOOK = 23;


    //todo: replace constant with PageEntity:
    const DT_FAMILY_TREE_PERSON = 63;


    protected $table = 'document_entities';


    protected $fillable = array('document_type_id', 'enabled', 'persons_id', 'top_article', 'related_village_id');


    protected $default_attributes = array('id', 'document_type_id', 'enabled', 'persons_id', 'created_at', 'updated_at', 'top_article', 'related_village_id');


    protected $entity_attributes = array(
        'uri' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'title' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'content' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
    );


    public static $rules_create = array(
        //todo: |unique:document_attributes,value dependend on category
        'title' => 'required|min:3|max:255',
        'content' => 'required',
        'uri' => 'unique:document_attributes,value',
    	'cats' => 'required'
    );


    public static $rules_next = array(
        't' =>'required',
        'p' =>'required',
    );


    public static $messages = array(
        't.required' => 'The terms of use must be accepted.',
        'cats.required' => 'Please select at least one category for this entry.'
    );


    public $original_language = 'en';


    public $category_uri;


    /**
     * Register to model's events
     */
    public static function boot()
    {
        parent::boot();

        static::saving(function($model)
        {
            $model->blacklist();
        });
    }


    public static function build($data)
    {
    	$class = 'Kythera\Models\\'. str_replace('Controller', '', $data->controller);
    	if (class_exists($class)) {
    	    return $class::find($data->id);
    	} else {
    		throw new DocumentEntityException('Invalid controller type given: '.$class);
    	}
    }


    /**
     * Depricated in favor of Router::getTermsOfUseURI()
     * @return string
     */
    public static function getTermsAndUseUri()
    {
    	return sprintf('/en/terms-of-use');
    }


    /**
     * Detect entity type by assigned controller
     * @param unknown $entity
     * @return string
     */
    public static function getType($entity)
    {
    	$result = ($controller = Router::getEntityController($entity)) ? $controller : 'DocumentText';

    	//detect by media
    	if ($query = \DB::table('document_images')
    			->where('entry_id', $entity->id)
    			->first()) {
    				$result = 'DocumentImage';
    			}
    	if ($query = \DB::table('document_audio')
    			->where('entry_id', $entity->id)
    			->first()) {
    				$result = 'DocumentAudio';
    			}
    	if ($query = \DB::table('document_video')
    			->where('entry_id', $entity->id)
    			->first()) {
    				$result = 'DocumentVideo';
    			}

    	return $result;
    }


    public static function uniqueUri($entity)
    {
    	if ($query = \DB::table('document_attributes')
    			->where('document_entity_id', '!=', $entity->id)
    			//->where('document_type_id', $entity->document_type_id)
    			->where('key', 'uri')
    			->where('value', $entity->uri)
    			->first()) {
    				$entity->uri = $entity->uri .'-'. $entity->id;
    				$entity->save();
    	}
    	return $entity;
    }



    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function categories()
    {
    	//http://www.developed.be/2013/08/30/laravel-4-pivot-table-example-attach-and-detach/
    	return $this->belongsToMany('Kythera\Models\DocumentCategory', 'document_categories', 'document_id', 'category_id');
    }


    public function images()
    {
        return \DB::table('document_images')
                        ->where('entry_id', $this->id)
                        ->get();
    }


    /**
     * Depricated
     */
    /*
    public function files()
    {
    	$images = \DB::table('document_images')->where('entry_id', $this->id)->get();
    	$files  = \DB::table('document_file')->select(array('*', 'file_name as image_name', 'file_path as image_path'))->where('entry_id', $this->id)->get();

    	return array_merge($images, $files);
    }
    */


    /**
     * TODO: controller_id will be depricated in favor of category ID
     * @param unknown $controller_id
     * @return \Illuminate\Database\Eloquent\static
     */
    public static function add($data, $controller_id = null)
    {
    	//determine controller
    	$cats = array_unique(array_filter($data['cats']));

    	if ($result = static::create( array(
			'title'              => $data['title'],
			'content'            => $data['content'],
			'uri'                => Translation::slug($data['title']),
			'enabled'            => 1,
			'persons_id'         => \Auth::user()->id,
			'document_type_id'   => $cats[0],// $controller_id
    		'related_village_id' => $data['v']
    	))) {
    		//update categories
    		$result->categories()->sync($cats);

    		//make uri unique
    		static::uniqueUri($result);
    	}
    	return $result;
    }


    public function set($data, $controller_id = null)
    {
    	//determine controller
    	$cats = array_unique(array_filter($data['cats']));

    	$this->title			  = $data['title'];
    	$this->content			  = $data['content'];
    	$this->uri 				  = Translation::slug($data['title']);
    	$this->document_type_id   = $cats[0];
    	$this->related_village_id = $data['v'];
    	//$this->updateTimestamps();
    	if ($result = $this->save()) {
    		//update categories
    		$this->categories()->sync($cats);

    		//make uri unique
    		static::uniqueUri($this);
    	}
    	return $result;
    }

    /**
     * Depricated
     * Process entry form files
     * TODO: Add language to entry
     * TODO: Move upload.php to framework
     * @param unknown $path
     * @param unknown $pluploader
     */
    /*
    public function storeFiles($path, $pluploader = array())
    {
    	$files = DocumentUploader::getUploaderFiles($pluploader);

    	foreach ($files as $i=>$file) {
    		if ( ($file['status']=='done') && ($file['custom']=='false') ) {
    			$src     = $path.$file['name'];
    			$caption = $file['caption'];
    			$ext     = strtolower(pathinfo($src, PATHINFO_EXTENSION));
    			switch($ext) {
//     				case 'mp3':
//     				case 'wav':
//     					$dst   = Config::get('cache.files.audio');
//     					$query = sprintf("INSERT INTO document_audio SET entry_id=%d, audio_name='%s', audio_path='audio/'", $this->id, $caption);
//     				break;
    				case 'gif':
    				case 'png':
    				case 'jpg':
    					$dst  = \Config::get('files.images');
    					$dst2 = $this->getRandomDirectory($dst);
    					$name = time().'.'.$ext;
    					$id = \DB::table('document_images')->insertGetId(array(
    							'entry_id' => $this->id,
    							'original_image_name' => $file['name'],
    							'image_name' => $name,
    							'image_path' => 'photos/'.$dst2
    							));
    					$files[$i]['kfnid'] = $id;
    					$dst .= $dst2.$name;
    				break;
    				default:
    					$dst  = \Config::get('files.files');
    					$dst2 = $this->getRandomDirectory($dst);
    					$name = $file['name'];
    					$id = \DB::table('document_file')->insertGetId(array(
    							'entry_id' => $this->id,
    							'file_caption' => $caption,
    							'file_name' => $file['name'],
    							'file_path' => 'files/'.$dst2
    					));
    					$files[$i]['kfnid'] = $id;
    					$dst .= $dst2.$name;
    			}
    			@rename($src, $dst);
    		}
    	}

    	//delete files
    	$this->deleteFiles($files);
    }
    */


    /*
    private function deleteFiles($files)
    {
    	$filenames = array();
    	$tmp1 = array();
    	$tmp2 = array();
    	$current = $this->files();
    	foreach ($current as $file) {
    		$tmp1[] = $file->id;

    		$path = $file->image_path ? $file->image_path : $file->file_path;
    		$name = $file->image_name ? $file->image_name : $file->file_name;
    		$filenames[$file->id] = sprintf('%s/%s%s', public_path(), $path, $name);
    	}

    	foreach ($files as $file) {
    		$tmp2[] = $file['kfnid'];
    	}

    	foreach (array_diff($tmp1, $tmp2) as $id) {
    		\Log::debug("DELETE FROM document_images/document_file WHERE id={$id} AND entry_id={$this->id} -> {$filenames[$id]} ");
    		\DB::table('document_images')->where('entry_id', $this->id)->where('id', $id)->delete();
    		\DB::table('document_file')->where('entry_id', $this->id)->where('id', $id)->delete();
    		@unlink($filenames[$id]);
    	}
    }
    */


    /**
     * Depricated
     * Process multi entry form files
     * TODO: Add language to entry
     * @param unknown $path
     * @param unknown $pluploader
     */
    /*
    public function storeFile(array $entry)
    {
    	$src     = public_path().$entry['i'];
    	$caption = $entry['content'];
    	$ext     = strtolower(pathinfo($src, PATHINFO_EXTENSION));
    	switch($ext) {
//     				 case 'mp3':
//     				 case 'wav':
//     				 $dst   = Config::get('cache.files.audio');
//     				 $query = sprintf("INSERT INTO document_audio SET entry_id=%d, audio_name='%s', audio_path='audio/'", $this->id, $caption);
//     				 break;
    				case 'gif':
    				case 'png':
    				case 'jpg':
    					$dst  = \Config::get('files.images');
    					$dst2 = $this->getRandomDirectory($dst);
    					$name = time().'.'.$ext;
    					$id = \DB::table('document_images')->insertGetId(array(
    							'entry_id' => $this->id,
    							'original_image_name' => $entry['title'],
    							'image_name' => $name,
    							'image_path' => 'photos/'.$dst2,
    							'taken' => date('Y-m-d', strtotime($entry['d'])),
    							'photographer_id' => \Auth::user()->id
    					));
    					$dst .= $dst2.$name;
    					break;
    				default:
    					$dst  = \Config::get('files.files');
    					$dst2 = $this->getRandomDirectory($dst);
    					$name = basename($src);
    					$id = \DB::table('document_file')->insertGetId(array(
    							'entry_id' => $this->id,
    							'file_caption' => $entry['title'],
    							'file_name' => $name,
    							'file_path' => 'files/'.$dst2
    					));
    					$dst .= $dst2.$name;
    	}
    	\Log::debug("Copying $src to $dst..");
    	@rename($src, $dst);
    }
    */




	public static function getDocuments($controller = 'DocumentTextController', $limit = 4, $document_type_id = array())
	{
		$result = array();

		//and `document_entities`.`document_type_id` = 87
		//select full record instead of id's and feed it to ::build instead of executing full query a second time??
		$query = static::query()
				->select('document_entities.*', 'document_types.controller')
		        ->distinct('document_entities.id')
				->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
				->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id');

		if (count($document_type_id)) {
		    $query->whereIn('document_entities.document_type_id', $document_type_id);
		}

		$query->where('document_entities.enabled', 1);
        //$controller = 'DocumentImageController';
		if ($controller) {
			$query->where('document_types.controller', $controller);
		}

		//fixme: exclude guestbook entries for sidebar views because whe only have a list view and NOT a seperate VIEW
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_GUEST_BOOK);
		//fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);

		$query->where('document_attributes.l', \App::getLocale())
		      ->where('document_entities.top_article', 0)
              ->orderByRaw('document_entities.created_at DESC')
              ->limit($limit);

		if ($items = $query->get())
		{
			foreach($items as $item)
			{
				$result[] = static::build($item);
			}
		}

		return $result;
	}


	public static function getEntriesSmallKeys($limit = 4, $document_type_id = array())
	{
		$result = array();

		//and `document_entities`.`document_type_id` = 87
		//select full record instead of id's and feed it to ::build instead of executing full query a second time??
		$query = static::query()
				->select('document_entities.*', 'document_types.controller')
		        ->distinct('document_entities.id')
				->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
				->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id');

		if (count($document_type_id))
		{
		    $query->whereIn('document_entities.document_type_id', $document_type_id);
		}

		//exclude guestbook entries because we only have a list view and NOT a seperate VIEW eg. {entry} route
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_GUEST_BOOK);
		//fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);

		$query->where('document_entities.enabled', 1)
              ->where('document_entities.top_article', 0)
		      ->where('document_attributes.l', \App::getLocale())
		      ->where('document_types.controller', 'DocumentImageController')
              ->orderByRaw('document_entities.created_at DESC')
              ->limit($limit);

		if ($items = $query->get())
		{
			foreach($items as $item)
			{
			    $result[] = static::build($item);
			}
		}

		return $result;
	}


	public static function getEntriesTopArticles($limit = 4, $document_type_id = array())
	{
		$result = array();

		//and `document_entities`.`document_type_id` = 87
		//select full record instead of id's and feed it to ::build instead of executing full query a second time??
		$query = static::query()
			->select('document_entities.*', 'document_types.controller')
			->distinct('document_entities.id')
			->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
			->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id');

		if (count($document_type_id))
		{
			$query->whereIn('document_entities.document_type_id', $document_type_id);
		}

		//exclude guestbook entries because we only have a list view and NOT a seperate VIEW eg. {entry} route
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_GUEST_BOOK);
		//fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);


		$query->where('document_entities.enabled', 1)
			->where('document_entities.top_article', 1)
			->where('document_attributes.l', \App::getLocale())
			->orderByRaw('document_entities.created_at DESC')
			->limit($limit);

		if ($items = $query->get())
		{
			foreach($items as $item)
			{
				$result[] = static::build($item);
			}
		}

		return $result;
	}


	public static function getEntriesTopArticlesRandom($limit = 4, $document_type_id = array())
	{
		$result = array();

        //select randomly every day, so cache result one day
        $items = \Cache::remember('random.articles.'.\App::getLocale(), 60*24, function() use($limit, $document_type_id)
        {
            $query = static::query()
                ->select('document_entities.*', 'document_types.controller')
                ->distinct('document_entities.id')
                ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
                ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id');

            if (count($document_type_id))
            {
                $query->whereIn('document_entities.document_type_id', $document_type_id);
            }

            //only image documents
            $query->where('document_types.controller', '=', 'DocumentImageController');
            //exclude guestbook entries because we only have a list view and NOT a seperate VIEW eg. {entry} route
            $query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_GUEST_BOOK);
            //fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
            $query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);

            $query->where('document_entities.enabled', 1)
                ->where('document_entities.top_article', 1)
                ->where('document_attributes.l', \App::getLocale());

            return $query
                    ->get()
                    ->random($limit);
        });

        foreach($items as $item)
        {
            $result[] = static::build($item);
        }

		return $result;
	}


	public static function getEntriesPhotos($limit = 4, $document_type_id = array(), $order = 'ASC')
	{
		$result = array();

		//and `document_entities`.`document_type_id` = 87
		//select full record instead of id's and feed it to ::build instead of executing full query a second time??
		$query = static::query()
				->select('document_entities.*', 'document_types.controller')
		        ->distinct('document_entities.id')
				->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
				->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
				->join('document_images', 'document_entities.id', '=', 'document_images.entry_id');

		if (count($document_type_id))
		{
		    $query->whereIn('document_entities.document_type_id', $document_type_id);
		}

		//exclude guestbook entries because we only have a list view and NOT a seperate VIEW eg. {entry} route
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_GUEST_BOOK);
		//fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);


		$query->where('document_entities.enabled', 1)
		      ->where('document_attributes.l', \App::getLocale())
              ->orderByRaw('document_images.taken '.$order)
              ->limit($limit);

		if ($items = $query->get())
		{
			foreach($items as $item)
			{
			    $result[] = static::build($item);
			}
		}

		return $result;
	}


	public static function getEntries($limit = 4, $document_type_id = array())
	{
		$result = array();

		//and `document_entities`.`document_type_id` = 87
		//select full record instead of id's and feed it to ::build instead of executing full query a second time??
		$query = static::query()
				->select('document_entities.*', 'document_types.controller')
		        ->distinct('document_entities.id')
				->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
				->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id');

		if (count($document_type_id))
		{
		    $query->whereIn('document_entities.document_type_id', $document_type_id);
		}

		//exclude guestbook entries because we only have a list view and NOT a seperate VIEW eg. {entry} route
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_GUEST_BOOK);
		//fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
		$query->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);



		$query->where('document_entities.enabled', 1)
              #->where('document_types.controller', $controller)
		      ->where('document_attributes.l', \App::getLocale())
		      ->where('document_entities.top_article', 0)
              ->orderByRaw('document_entities.created_at DESC')
              ->limit($limit);

		if ($items = $query->get())
		{
			foreach($items as $item)
			{
			    $result[] = static::build($item);
			}
		}

		return $result;
	}


	//public static function getUserEntries(\Illuminate\Auth\UserInterface $user, $document_type_id = array())
	public static function getUserEntries($user, $document_type_id = array(), $includeDisabled = false)
	{
		/*
		 * SELECT
		`pages`.`id`    AS `page_id`,
  pages_attributes.value as cat,
  count(*)        AS n
FROM `document_entities`
  LEFT JOIN `document_types` ON `document_entities`.`document_type_id` = `document_types`.`id`
  LEFT JOIN `pages` ON `document_entities`.`document_type_id` = `pages`.`controller_id`
  left join
    `pages_attributes` on pages.id = pages_attributes.document_entity_id

WHERE `document_entities`.`persons_id` = '3336'
	and pages_attributes.l='en'
	and pages_attributes.key='title'
GROUP BY `document_type_id`
ORDER BY cat
	*/
		$group = count($document_type_id)==0;

	    $query = static::query();

	    if ($group) {
	    	$query
	    		//->select('document_entities.*', 'document_types.controller', 'document_types.string_id', 'pages.title as cat', 'pages.id as page_id', 'pages.uri', \DB::raw('count(*) as n'));
	    		->select('document_entities.document_type_id', 'pages.id as page_id', 'pages_attributes.value as cat', \DB::raw('count(*) as n'));
	    } else {
	    	$query
	    		->select('document_entities.*', 'document_types.controller', 'document_types.string_id', 'pages.title as cat', 'pages.id as page_id', 'pages.uri');
	    }
	    $query
		    ->leftJoin('document_types', 'document_entities.document_type_id', '=', 'document_types.id')
		    ->leftJoin('pages', 'document_entities.document_type_id', '=', 'pages.controller_id')
		    ->leftJoin('pages_attributes', 'pages.id', '=', 'pages_attributes.document_entity_id');

	    if (!$group) {
	        $query
	        	->whereIn('document_entities.document_type_id', $document_type_id);
	    }

	    if (!$includeDisabled)
	    $query
	       ->where('document_entities.enabled', 1);

	    $query
	    	->where('document_entities.persons_id', $user->id);
	    $query
	    	->where('pages_attributes.l', \App::getLocale());
	    $query
	    	->where('pages_attributes.key', 'title');

	    if (!in_array(DocumentEntity::DT_FAMILY_TREE_PERSON, $document_type_id))
	    $query
	    	->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON);

	    if ($group) {
	    	$query
	    		->groupBy('document_type_id')
	    		->orderByRaw('cat');
	    } else {
	    	$query
	    		->orderByRaw('document_entities.created_at DESC');
	    }
	    return $query->get();
	}


	public static function getUsers()
	{
		return \User::select('firstname', 'lastname', 'email')
			->where('email', '<>', '')
			->where('hide', 0)
			->orderBy('lastname', 'asc')
			->orderBy('firstname', 'asc')
			->get();
	}


	public static function getCount()
    {
        if ($query = \DB::selectOne("
            SELECT
              COUNT(DISTINCT(document_entities.id)) as count
            FROM
              document_types
              LEFT JOIN document_entities ON document_types.id = document_entities.document_type_id
              LEFT JOIN document_images ON document_entities.id = document_images.entry_id
              LEFT JOIN document_audio ON document_entities.id = document_audio.entry_id
              /*LEFT JOIN messageboard ON documents.id = messageboard.documents_id*/
              /*LEFT JOIN messageboard2 ON documents.id = messageboard2.documents_id*/
              /*LEFT JOIN famous_people ON documents.id = famous_people.documents_id*/
              /*LEFT JOIN letters ON documents.id = letters.document_id*/
              /*LEFT JOIN individuum ON documents.id = individuum.entry_id*/
              /*LEFT JOIN persons as family_tree_persons ON individuum.persons_id = family_tree_persons.id*/
              /*LEFT JOIN document_dictionary1 ON documents.id = document_dictionary1.entry_id*/
              /*LEFT JOIN document_dictionary2 ON documents.id = document_dictionary2.entry_id*/
              /*LEFT JOIN document_dictionary3 ON documents.id = document_dictionary3.entry_id*/
              /*LEFT JOIN document_dictionary4 ON documents.id = document_dictionary4.entry_id*/
              /*LEFT JOIN document_dictionary5 ON documents.id = document_dictionary5.entry_id*/
              /*
            WHERE
              (document_dictionary5.id = 1 OR
               document_dictionary4.id = 1 OR
               document_dictionary3.id = 1 OR
               document_dictionary2.id = 1 OR
               document_dictionary1.id = 1 ) AND
              (document_dictionary1.language01 IS NOT NULL OR
               document_dictionary2.language01 IS NOT NULL OR
               document_dictionary3.language01 IS NOT NULL OR
               document_dictionary4.language01 IS NOT NULL OR
               document_dictionary5.language01 IS NOT NULL OR
               document_dictionary1.language02 IS NOT NULL OR
               document_dictionary2.language02 IS NOT NULL OR
               document_dictionary3.language02 IS NOT NULL OR
               document_dictionary4.language02 IS NOT NULL OR
               document_dictionary5.language02 IS NOT NULL OR
               document_dictionary1.language03 IS NOT NULL OR
               document_dictionary2.language03 IS NOT NULL OR
               document_dictionary3.language03 IS NOT NULL OR
               document_dictionary4.language03 IS NOT NULL OR
               document_dictionary5.language03 IS NOT NULL);*/
            "))
        {
            return number_format($query->count, 0, '', ',');
        }
    }

    /**
     * @param Builder $query
     * @param Page $page: We only need the controller_id attribute to profit from the table index.
     * @param string $value
     */
    public function scopeWhereUri(Builder $query, $page, $value)
    {
        return $query->leftJoin('document_attributes', function($join) use($page, $value)
        {
            $join->on('document_entities.id', '=', 'document_attributes.document_entity_id');
        })->where('document_entities.enabled', '=', 1)
          //->where('document_attributes.document_type_id', '=', $page->controller_id)
          ->where('document_attributes.l', '=', \App::getLocale())
          ->where('document_attributes.key', '=', 'uri')
          ->where('document_attributes.value', '=', $value);
    }


    /**
     * Include user data.
     * @param Builder $query
     */
    public function scopeWithUser(Builder $query)
    {
        return $query->leftJoin('users', function($join)
        {
            $join->on('document_entities.persons_id', '=', 'users.id');
        });
    }


    /**
     * Include user data.
     * @param Builder $query
     */
    public function scopeUser(Builder $query)
    {
    	return $query
    		->select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.email', 'users.id as user_id')
    		->leftJoin('users', 'document_entities.persons_id', '=', 'users.id');
    }


    /**
     * Select all entity attribuets on a model.
     * @param Builder $query
     * @param string $key: Attribute name
     */
    public function scopeAttribute(Builder $query, $key)
    {
        return $query->select('document_entities.*')->leftJoin('document_attributes', function($join) use($key, $value)
        {
            $join->on('document_entities.id', '=', 'document_attributes.document_entity_id')
                ->where('document_entities.enabled', '=', 1)
	            //->where('document_attributes.document_type_id', '=', $this->document_type_id)
	            ->where('document_attributes.l', '=', \App::getLocale())
	            ->where('document_attributes.key', '=', $key);
        });
    }


    public function scopeSearch(Builder $query, $keys, $value)
    {
    	return $query
    		//->select('document_entities.*')
    		->select('document_attributes.*')
    		->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')

    		->where('document_entities.document_type_id', '!=', DocumentEntity::DT_GUEST_BOOK)
    		//fixme: exclude familytree entries for sidebar views because whe only have a list view and NOT a seperate VIEW
    		->where('document_entities.document_type_id', '<>', DocumentEntity::DT_FAMILY_TREE_PERSON)


    		->where('document_entities.enabled', '=', 1)
    		->where('document_attributes.l', '=', \App::getLocale())
    		->whereIn('document_attributes.key', $keys)
    		->where('document_attributes.value', 'LIKE', $value);
    }


    /**
     * Generate random media directory
     * @param string $base
     * @return string $path
     */
    public static function getRandomDirectory($base) {
    	$UPLOAD_IMAGE_DIRECTORY_DEPTH = 1;
    	$UPLOAD_IMAGE_SUBDIRECTORY_COUNT_PER_DIRECTORY = 10;

    	$directory = $base;
    	for ($i=0; $i<max($UPLOAD_IMAGE_DIRECTORY_DEPTH,1); $i++) {
    		$result = rand(1, min($UPLOAD_IMAGE_SUBDIRECTORY_COUNT_PER_DIRECTORY, 10));
    		$directory.=$result;

    		if (!file_exists($directory))
    			mkdir($directory, 0775);

    		$directory.= '/';
    	}
    	return $directory;
    }


    public static function getRandomFilename($ext)
    {
    	return time().rand(1000, 10000).'.'.$ext;
    }


    public static function getExtension($file)
    {
    	return strtolower(pathinfo($file, PATHINFO_EXTENSION));
    }

    public static function isImage($file)
    {
    	return in_array( static::getExtension($file), Config::get('files.supported_images') );
    }

    public static function isAudio($file)
    {
    	return in_array( static::getExtension($file), Config::get('files.supported_audio') );
    }

    public static function isVideo($file)
    {
    	return in_array( static::getExtension($file), Config::get('files.supported_video') );
    }

    public static function isMedia($file)
    {
    	return static::isAudio($file) || static::isVideo($file);
    }

    public static function getRecent($limit = 4, $document_type_id = array())
    {
    	return static::getDocuments(null, $limit, $document_type_id);
    }


    public static function hasCats($cats)
    {
    	return $cats[0] > 0;
    }

    public function blacklist()
    {
        $replace   = 'XXX';
        $replace   = '';
        $blacklist = [];
        if ($words = \DB::table('blacklist')->select('word')->get())
        {
            foreach ($words as $word)
                $blacklist[] = $word->word;
        }

        //$r=get_class($this);echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';echo __FILE__.__LINE__.'<pre>$this='.htmlentities(print_r($this,1)).'</pre>';die;

        switch(get_class($this)) {
            case 'Kythera\Models\DocumentEntity':
                $this->uri = str_ireplace($blacklist, $replace, $this->uri);
                $this->title = str_ireplace($blacklist, $replace, $this->title);
                $this->content = str_ireplace($blacklist, $replace, $this->content);
            break;
            case 'Kythera\Models\DocumentAudio':
                $this->uri = str_ireplace($blacklist, $replace, $this->uri);
                $this->title = str_ireplace($blacklist, $replace, $this->title);
                $this->content = str_ireplace($blacklist, $replace, $this->content);
                //$this->copyright = str_ireplace($blacklist, $replace, $this->copyright);
            break;
            case 'Kythera\Models\DocumentImage':
                $this->uri = str_ireplace($blacklist, $replace, $this->uri);
                $this->title = str_ireplace($blacklist, $replace, $this->title);
                $this->content = str_ireplace($blacklist, $replace, $this->content);
                $this->copyright = str_ireplace($blacklist, $replace, $this->copyright);
            break;
            case 'Kythera\Models\DocumentGuestbook':
                $this->uri = str_ireplace($blacklist, $replace, $this->uri);
                $this->title = str_ireplace($blacklist, $replace, $this->title);
                $this->content = str_ireplace($blacklist, $replace, $this->content);
            break;
            case 'Kythera\Models\DocumentMessage':
                $this->uri = str_ireplace($blacklist, $replace, $this->uri);
                $this->title = str_ireplace($blacklist, $replace, $this->title);
                $this->content = str_ireplace($blacklist, $replace, $this->content);
            break;
            default:
                \Log::alert('Blacklist rules missing for '.get_class($this));
        }

    }


    public static function isEditable($entry, $redirect = '')
    {
    	/*
    	$r = DocumentPermission::remove(100, 200);
    	echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';die;
    	$r = DocumentPermission::add(100, 2001);
    	echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';die;
    	*/

    	/*
    	$e = ($entryId === Auth::user()->id);
    	echo __FILE__.__LINE__.'<pre>$e='.htmlentities(print_r($e,1)).'</pre>';
    	$e = DocumentPermission::can($entryId, Auth::user()->id);
    	echo __FILE__.__LINE__.'<pre>$e='.htmlentities(print_r($e,1)).'</pre>';die;
    	*/

    	return ($entry->persons_id === Auth::user()->id) || Auth::user()->isAdmin() || DocumentPermission::can($entry->id, Auth::user()->id);
    }


    public static function convertFromGreek($greekDate)
    {
        //$greekDate = '15/05/1990';
        //$greekDate = '05/1990';
        //$greekDate = '1990';
        //$greekDate = '';
        $date = null;
        if (preg_match('#([0-9]{2})/([0-9]{2})/([0-9]{4})#', $greekDate, $m)) {
            //echo __FILE__.__LINE__.'<pre>$dt='.htmlentities(print_r($m,1)).'</pre>';die;
            $date = sprintf('%d-%d-%d', $m[3], $m[2], $m[1]);
        } else
        if (preg_match('#([0-9]{2})/([0-9]{4})#', $greekDate, $m)) {
            $date = sprintf('%d-%d-00', $m[2], $m[1]);
            //echo __FILE__.__LINE__.'<pre>$dt='.htmlentities(print_r($m,1)).'</pre>';die;
        } else
        if (preg_match('#([0-9]{4})#', $greekDate, $m)) {
            $date = sprintf('%d-00-00', $m[1]);
            //echo __FILE__.__LINE__.'<pre>$dt='.htmlentities(print_r($m,1)).'</pre>';die;
        }
        return $date;
    }


    public static function convertToGreek($gregorianDate)
    {
        //$mysqlDate = '';
        //$mysqlDate = '1960-00-00';
        //$mysqlDate = '1960-05-00';
        //$mysqlDate = '1960-05-16';
        $date = null;
        if (preg_match('#([0-9]{4})-([0-9]{2})-([0-9]{2})#', $gregorianDate, $m)) {
            if (intval($m[3])>0) {
                $date = date('d/m/Y', mktime(0,0,0,$m[2],$m[3],$m[1]));
            } else
            if (intval($m[2])>0) {
                $date = date('m/Y', mktime(0,0,0,$m[2],1,$m[1]));
            } else
            if (intval($m[1])>0) {
                $date = $m[1];
            }
        }
        return $date;
    }



}
