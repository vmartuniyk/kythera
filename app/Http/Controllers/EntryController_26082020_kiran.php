<?php

namespace App\Http\Controllers;

use Cache;
use Event;
use App;
use Auth;
use Input;
use Log;
use Redirect;
use Response;
use Session;
use URL;
use Validator;
use xhtml;
use Kythera\Entity\Entity;
use Kythera\Models\DocumentAudio;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentMessage;
use Kythera\Models\DocumentQuotedText;
use Kythera\Models\DocumentText;
use Kythera\Models\DocumentUploader;
use Kythera\Models\DocumentVideo;
use App\Models\User;
use Kythera\Router\Facades\Router;
use Mail;

use DB;

#use Facebook;
use Facebook\Facebook;
use Facebook\Exceptions\FacebookResponseException;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphUser;
use Mockery\CountValidator\Exception;
use Kythera\Support\ViewEntity;
session_start();
use Illuminate\Http\Request;
/**
 *
 * @author virgilm
 *
 */
class EntryController_26082020_kiran extends Controller
{
    protected $request;

    /**
     * Initializer.
     *
     * @access public
     * @return \BaseController
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Process ajax requests made by plupload.
     */
    public function upload()
    {
        return Response::json(DocumentUploader::processFile());
    }

    /**
     * Process ajax requests made by plupload.
     */
    public function delete()
    {
        DocumentUploader::deleteFile(Input::get('file'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($catId = null)
    {
        return view('site.document.create')
            ->with('selectedCatId', $catId);
    }

    public function index()
    {
        return view('site.document.index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return 'Display the specified resource.' . $id;
    }

    /**
     * Create a detail form for every submitted file.
     *
     * @return Response
     */
    public function next($id = null)
    {
        if ($id) {
            // edit
            $validator = Validator::make(Input::get('entry'), DocumentEntity::$rules_create, DocumentEntity::$messages);
            if ($validator->passes()) {
                if (($entity = DocumentEntity::find($id)) &&($editable = Auth::user()->isEditable($entity->persons_id))) {
                    $type = DocumentEntity::getType($entity);
                    switch ($type) {
                        case 'DocumentText':
                            $entry = DocumentText::find($entity->id);
                            break;
                        case 'DocumentQuotedText':
                            $entry = DocumentQuotedText::find($entity->id);
                            break;
                        case 'DocumentImage':
                            $entry = DocumentImage::find($entity->id);
                            break;
                        case 'DocumentAudio':
                            $entry = DocumentAudio::find($entity->id);
                            break;
                        case 'DocumentVideo':
                            $entry = DocumentVideo::find($entity->id);
                            break;
                    }
                }
                //$r=Input::all();echo __FILE__.__LINE__.'<pre>$r='.htmlentities(print_r($r,1)).'</pre>';				echo __FILE__.__LINE__.'<pre>$entry='.htmlentities(print_r($entry,1)).'</pre>';die;
                Session::set('data', $_POST ['entry']);
                return view('site.document.next2')
                    ->with('entry', $entry);
            }
        } else {
            $data = Input::get('entry');
            if (!DocumentEntity::hasCats($data['cats'])) {
                unset($data['cats']);
            }

            // create
            $validator = Validator::make($data, DocumentEntity::$rules_create, DocumentEntity::$messages);
            if ($validator->passes()) {
                Session::set('data', $_POST['entry']);
                return view('site.document.next');
            }
        }

        // error
        return Redirect::route('entry.create')
            ->with('global', "Error creating entry.")
            ->withErrors($validator)->withInput();
    }

    /**
     * Depricated
     * Create a detail form for every submitted file.
     *
     * @return Response
     */
    public function details($id = null)
    {
        if ($id) {
            // edit-next
            $validator = Validator::make(Input::get('entry'), DocumentEntity::$rules_create, DocumentEntity::$messages);
            if ($validator->passes()) {
                // temporarily save entry data
                Session::set('data', $_POST ['entry']);
                return view('site.document.next2')
                    ->with('entry_id', $id);
            }
        } else {
            // create-next
            $validator = Validator::make(Input::get('entry'), DocumentEntity::$rules_create, DocumentEntity::$messages);
            if ($validator->passes()) {
                // temporarily save entry data
                Session::set('data', $_POST ['entry']);
                return view('site.document.next');
            }
        }

        // error
        return Redirect::route('entry.create')->with('global', "Error creating entry.")->withErrors($validator)->withInput();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        /*
		// set in PageComposer.php
		// TODO: for now we use $page to select the document type/ controller id. This should be set to the first category.
		if(!$page = Session::get('page')) {
			// happens when we request a page not passed through our router(static page)
			throw new Exception('Could not get Page object from session.');
		}

		22-02-16: added type detection based on primary category. todo: apply to all different types.
		*/

        //redirect to add another language
        $language = Input::get('l', false);

        if(isset($_POST['g-recaptcha-response']) && !empty($_POST['g-recaptcha-response'])) {
            // Google reCAPTCHA API secret key
            $secretKey = env('INVISIBLE_RECAPTCHA_SECRETKEY');

            // Verify the reCAPTCHA response
            $verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $_POST['g-recaptcha-response']);

            // Decode json data
            $responseData = json_decode($verifyResponse);
            // If reCAPTCHA response is valid
            if ($responseData->success) {

                $data = Session::get('data');
                if ($entity_page = Router::getControllerPage($data['cats'][0])) {
                    $entity_classname = 'Kythera\Models\\'.str_ireplace('controller', '', $entity_page->controller);
                    if (class_exists($entity_classname)) {
                        $entity = new $entity_classname();
                    } else {
                        throw new Exception('Invalid classname: '. $entity_classname);
                    }
                } else {
                    return Redirect::route('entry.create')
                        ->with('global', 'Error saving entry because a controller is missing.');
                }

                switch ($entity_classname) {
                    case 'Kythera\Models\DocumentQuotedText':
                        $validator = Validator::make(Input::all(), $entity::$rules_next, $entity::$messages);
                        if ($validator->passes()) {
                            if ($entity = $entity::add(Session::get('data'), null)) {
                                //notify admin and hidrate
                                Event::fire('entity.created', $entity);

                                return $this->redirectOnSave($entity, $language);
                            }
                        }
                        break;
                }

                //detect document type based on file attached.
                $type = DocumentUploader::detectFileType(Input::all());
                $validator = Validator::make(Input::all(), DocumentEntity::$rules_next, DocumentEntity::$messages);
                switch ($type) {
                    case 'text':
                        $validator = Validator::make(Input::all(), DocumentText::$rules_next, DocumentText::$messages);
                        if ($validator->passes()) {
                            if ($entity = DocumentText::add(Session::get('data'), null)) {
                                $this->doSendEmails('entity.created' , $entity );
                                //notify admin
                                Event::fire('entity.created', $entity);

                                return $this->redirectOnSave($entity, $language);
                            }
                        }
                        break;
                    case 'image':
                        $validator = Validator::make(Input::all(), DocumentImage::$rules_next, DocumentText::$messages);
                        if ($validator->passes()) {
                            $uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                            foreach ($uploaded_files as $type => $files) {
                                foreach ($files as $file) {
                                    switch ($type) {
                                        case 'image':
                                            if ($entity = DocumentImage::add(Session::get('data'), null)) {
                                                $entity->copyright = $file['c'];
                                                $entity->save();

                                                if ($id = $entity->setImage($entity, $file)) {
                                                    /*
                                                     * $tag = sprintf('[[picture:"%s" ID:%d]]', $file['name'], $id);
                                                     * $entity->content = $tag .'<br/><br/>'. $entity->content;
                                                     * $entity->save();
                                                     */
                                                }
                                                $this->doSendEmails('entity.created' , $entity );
                                                //notify admin
                                                Event::fire('entity.created', $entity);

                                                return $this->redirectOnSave($entity, $language);
                                            }
                                            break;
                                    }
                                }
                            }
                        }
                        break;
                    case 'audio':
                    case 'video':
                        $validator = Validator::make(Input::all(), DocumentAudio::$rules_next, DocumentAudio::$messages);
                        if ($validator->passes()) {
                            $uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                            foreach ($uploaded_files as $type => $files) {
                                foreach ($files as $file) {
                                    switch ($type) {
                                        case 'audio':
                                            if ($entity = DocumentAudio::add(Session::get('data'), null)) {
                                                $entity->copyright = $file['c'];
                                                $entity->save();

                                                if ($id = $entity->setMedia($entity, $file)) {
                                                    /*
                                                     * $tag = sprintf('[[media:"%s" ID:%d]]', $file['name'], $id);
                                                     * $entity->content = $tag .'<br/><br/>'. $entity->content;
                                                     * $entity->save();
                                                     */
                                                }
                                                $this->doSendEmails('entity.created' , $entity );    
                                                //notify admin
                                                Event::fire('entity.created', $entity);

                                                return $this->redirectOnSave($entity, $language);
                                                /*
                                                return redirect(Router::getItemUrl($entity))
                                                    ->with('global', "Entry '" . $entity->title . "' successfully saved.");
                                                    */
                                            }
                                            break;
                                        case 'video':
                                            if ($entity = DocumentVideo::add(Session::get('data'), null)) {
                                                $entity->copyright = $file['c'];
                                                $entity->save();

                                                if ($id = $entity->setMedia($entity, $file)) {
                                                    /*
                                                     * $tag = sprintf('[[media:"%s" ID:%d]]', $file['name'], $id);
                                                     * $entity->content = $tag .'<br/><br/>'. $entity->content;
                                                     * $entity->save();
                                                     */
                                                }
                                                $this->doSendEmails('entity.created' , $entity );    
                                                //notify admin
                                                Event::fire('entity.created', $entity);

                                                return $this->redirectOnSave($entity, $language);
                                                /*
                                                return redirect(Router::getItemUrl($entity))
                                                    ->with('global', "Entry '" . $entity->title . "' successfully saved.");
                                                    */
                                            }
                                            break;
                                    }
                                }
                            }
                        }
                        break;
                }

                // error
                return Redirect::route('entry.create')
                    ->with('global', 'Error saving entry.')
                    ->withErrors($validator)
                    ->withInput(['entry' => Session::get('data')]);
            } else {
                return Redirect::route('entry.create')
                    ->with('global', 'Captcha verification failed, please try again!')
                    ->withInput(['entry' => Session::get('data')]);
            }
        } else {
            return Redirect::route('entry.create')
                ->with('global', 'Captcha verification failed, please try again!')
                ->withInput(['entry' => Session::get('data')]);
        }
    }


    public function redirectOnSave($entity, $language = false)
    {
        if ($language) {
            //get other language
            $uri = action('EntryController@edit', $entity->id);
            $l   = App::getLocale() == 'en' ? 'gr' : 'en';
            $uri = str_ireplace('/'.App::getLocale().'/', '/'.$l.'/', $uri);
        } else {
            $uri = Router::getItemUrl($entity);
        }
        //echo '<pre>'; print_r($entity);
        //echo '<pre>'; print_r($uri); die;
        return redirect($uri)
            ->with('global', "Entry '" . $entity->title . "' successfully saved.");
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

        /*
		// set in PageComposer.php
		if(! $page = Session::get('page')) {
			// happens when we request a page not passed through our router(static page)
			throw new Exception('Could not get Page object from session.');
		}
		*/

        // show edit form
        if (($entity = DocumentEntity::find($id)) &&($editable = Auth::user()->isEditable($entity->persons_id))) {
            /*
			//temp fix
			if($page = \DB::table('pages')->where('controller_id', $entity->document_type_id)->first()) {
				$cats = array(
					$page->controller_id,
					0,
					0
				);
			}
			*/

            $cats = [$entity->document_type_id,0,0];
            foreach ($entity->categories as $i => $cat) {
                $cats[$i] = $cat->pivot->category_id;
            }

            $type = DocumentEntity::getType($entity);
            $files = [];

            switch ($type) {

                case 'DocumentText':
                    if ($entry = DocumentText::find($entity->id)) {
                        return view('site.document.edit')
                            ->with('cats', $cats)
                            ->with('entry', $entry)
                            ->with('files', $files);
                    }
                    break;

                case 'DocumentQuotedText':
                    if ($entry = DocumentQuotedText::find($entity->id)) {
                        return view('site.document.quoted.edit')
                            ->with('cats', $cats)
                            ->with('entry', $entry)
                            ->with('files', $files);
                    }
                    break;

                case 'DocumentImage':
                    if ($entry = DocumentImage::find($entity->id)) {
                        $files = DocumentUploader::setUploaderFiles($entry->getFiles());
                        return view('site.document.edit')
                            ->with('cats', $cats)
                            ->with('entry', $entry)
                            ->with('files', $files);
                    }
                    break;

                case 'DocumentAudio':
                    if ($entry = DocumentAudio::find($entity->id)) {
                        $files = DocumentUploader::setUploaderFiles($entry->getFiles(), 'audio');
                        return view('site.document.edit')
                            ->with('cats', $cats)
                            ->with('entry', $entry)
                            ->with('files', $files);
                    }
                    break;

                case 'DocumentVideo':
                    if ($entry = DocumentVideo::find($entity->id)) {
                        $files = DocumentUploader::setUploaderFiles($entry->getFiles(), 'video');
                        return view('site.document.edit')
                            ->with('cats', $cats)
                            ->with('entry', $entry)
                            ->with('files', $files);
                    }
                    break;

                case 'DocumentMessage':
                    if ($entry = DocumentMessage::find($entity->id)) {
                        return view('site.document.message.edit')
                            ->with('entry', $entry);
                    }
                    break;
            }
        }

        // error
        return Redirect::action('EntryController@index')
            ->with('global', "Entry not accessable.");
    }


    public function update($id)
    {
        /*
		// set in PageComposer.php
		// TODO: for now we use $page to select the document type/ controller id. This should be set to the first category.
		if(! $page = Session::get('page')) {
			// happens when we request a page not passed through our router(static page)
			throw new Exception('Could not get Page object from session.');
		}
		*/

        //redirect to add another language
        $language = Input::get('l', false);

        // detect document type based on file attached.
        $type = DocumentUploader::detectFileType(Input::all());
       //  echo '<pre>'; print_r($type); die;
        if (($entity = DocumentEntity::find($id)) &&($editable = Auth::user()->isEditable($entity->persons_id))) {
            $current = DocumentEntity::getType($entity);
            switch ($current) {
                case 'DocumentText':

                    $validator = Validator::make(Input::all(), DocumentText::$rules_next, DocumentText::$messages);
                    if ($validator->passes()) {
                        $entity = DocumentImage::find($id);

                        if ($entity->set(Session::get('data'), null)) {
                            //notify admin

                            $posted_files = isset($_POST ['files']) ? $_POST ['files'] : [];

                            //$uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                            $uploaded_files = DocumentUploader::getUploaderFiles2($posted_files);

                            $n_image = count($uploaded_files['image']);
                            $n_audio = count($uploaded_files['audio']);
                            $n_video = count($uploaded_files['video']);
                            //delete file
                            if ($n_image==0) {
                                $entity->deleteImage();
                                $this->doSendEmails('entity.updated' , $entity );
                                Event::fire('entity.updated', $entity);

                                return $this->redirectOnSave($entity, $language);
                            }
                            //add/replace file
                            foreach ($uploaded_files as $type => $files) {
                                foreach ($files as $file) {
                                    switch ($type) {
                                        case 'image':
                                            //if($file_id = $entity->updateImage(Input::get('entry'), $file)) {
                                            if ($file_id = $entity->updateImage(null, $file)) {
                                                $entity->copyright = $file['c'];
                                                $entity->save();
                                                $this->doSendEmails('entity.updated' , $entity );    
                                                //notify admin
                                                Event::fire('entity.updated', $entity);

                                                return $this->redirectOnSave($entity, $language);
                                            }
                                            break;
                                        case 'audio':
                                        case 'video':
                                            break;
                                    }
                                }
                            }
                            print_r($posted_files);
                            die('Condition 1');
                        }
                    }
                    break;
                case 'DocumentQuotedText':
//                    die('Condition 2');
                    $validator = Validator::make(Input::all(), DocumentQuotedText::$rules_next, DocumentQuotedText::$messages);
                    if ($validator->passes()) {
                        $entity = DocumentImage::find($id);
                        if ($entry = DocumentQuotedText::find($entity->id)) {
                            if ($entry->set(Session::get('data'), null)) {
                                //notify admin
                                $posted_files = isset($_POST ['files']) ? $_POST ['files'] : [];
                                //$uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                                $uploaded_files = DocumentUploader::getUploaderFiles2($posted_files);

                                $n_image = count($uploaded_files['image']);
                                $n_audio = count($uploaded_files['audio']);
                                $n_video = count($uploaded_files['video']);
                                //delete file
                                if ($n_image==0) {
                                    $entity->deleteImage();
                                }
                                $this->doSendEmails('entity.updated' , $entity );
                                Event::fire('entity.updated', $entry);

                                return $this->redirectOnSave($entry, $language);

                            }
                        }
                    }
                    break;
                case 'DocumentImage':
                    //die('fine');
                    $validator = Validator::make(Input::all(), DocumentImage::$rules_next, DocumentImage::$messages);
                    if ($validator->passes()) {
                        if ($entity = DocumentImage::find($id)) {
                            if ($entity->set(Session::get('data'), null)) {
                                $posted_files = isset($_POST ['files']) ? $_POST ['files'] : [];
                                //$uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                                $uploaded_files = DocumentUploader::getUploaderFiles2($posted_files);

                                $n_image = count($uploaded_files['image']);
                                $n_audio = count($uploaded_files['audio']);
                                $n_video = count($uploaded_files['video']);

                                //delete file
                                //echo '<pre>'; print_r($uploaded_files);
                                
                                if ($n_image==0) {
                                    $entity->deleteImage();
                                    $this->doSendEmails('entity.updated' , $entity );    
                                    //notify admin
                                    Event::fire('entity.updated', $entity);

                                    return $this->redirectOnSave($entity, $language);
                                }

                                //add/replace file
                                foreach ($uploaded_files as $type => $files) {
                                	//echo '<pre>'; print_r($type); die;
                                    foreach ($files as $file) {
                                        switch ($type) {
                                            case 'image':
                                                //if($file_id = $entity->updateImage(Input::get('entry'), $file)) {
                                                if ($file_id = $entity->updateImage(Input::get('entry'), $file)) {
                                                    $entity->copyright = $file['c'];
                                                    $entity->save();

                                                    //notify admin
                                                    Event::fire('entity.updated', $entity);

                                                    return $this->redirectOnSave($entity, $language);
                                                }
                                                break;
                                            case 'audio':
                                            case 'video':
                                                break;
                                        }
                                    }
                                }
                                //echo '<pre>'; print_r($n_image); die;
                            }
                        }
                    }
                    break;
                case 'DocumentAudio':
                    $validator = Validator::make(Input::all(), DocumentAudio::$rules_next, DocumentAudio::$messages);
                    if ($validator->passes()) {
                        if ($entity = DocumentAudio::find($id)) {
                            if ($entity->set(Session::get('data'), null)) {
                                $uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                                foreach ($uploaded_files as $type => $files) {
                                    foreach ($files as $file) {
                                        switch ($type) {
                                            case 'image':
                                                if ($file_id = $entity->updateImage(Input::get('entry'), $file)) {
                                                    return $this->redirectOnSave($entity, $language);
                                                }
                                                break;
                                            case 'audio':
                                            case 'video':
                                                if ($file_id = $entity->updateMedia(Input::get('entry'), $file)) {
                                                    $entity->copyright = $file['c'];
                                                    $entity->save();
                                                    $this->doSendEmails('entity.updated' , $entity );     
                                                    //notify admin
                                                    Event::fire('entity.updated', $entity);

                                                    return $this->redirectOnSave($entity, $language);
                                                }
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
                case 'DocumentVideo':
                    $validator = Validator::make(Input::all(), DocumentAudio::$rules_next, DocumentAudio::$messages);
                    if ($validator->passes()) {
                        if ($entity = DocumentVideo::find($id)) {
                            if ($entity->set(Session::get('data'), null)) {
                                $uploaded_files = DocumentUploader::getUploaderFiles2($_POST ['files']);
                                foreach ($uploaded_files as $type => $files) {
                                    foreach ($files as $file) {
                                        switch ($type) {
                                            case 'image':
                                                if ($file_id = $entity->updateImage(Input::get('entry'), $file)) {
                                                    return $this->redirectOnSave($entity, $language);
                                                }
                                                break;
                                            case 'audio':
                                            case 'video':
                                                if ($file_id = $entity->updateMedia(Input::get('entry'), $file)) {
                                                    $entity->copyright = $file['c'];
                                                    $entity->save();
                                                    $this->doSendEmails('entity.updated' , $entity );     
                                                    //notify admin
                                                    Event::fire('entity.updated', $entity);

                                                    return $this->redirectOnSave($entity, $language);
                                                }
                                                break;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    break;
            }
        }

        // error
        $validator = Validator::make(Input::all(), DocumentEntity::$rules_next, DocumentEntity::$messages);
        return Redirect::action('EntryController@edit', $id)
            ->with('global', 'Information not saved. Please try again.')
            ->withErrors($validator)
            ->withInput(['entry' => Session::get('data')]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        if ($entity = DocumentEntity::find($id)) {
            if (Auth::user()->isAdmin($entity->persons_id)) {
                //notify
                Event::fire('entity.deleted', $entity);

                $entity->delete();

                return Redirect::action('PersonalPageController@getIndex')
                    ->with('global', 'Entry deleted.');
            }
        }

        // error
        return Redirect::action('PersonalPageController@getIndex')
            ->with('global', 'Entry not deleted.');
    }


    /**
     * Called from PersonalPageController.
     *
     * @param integer $id
     * @return string
     */
    public function enable($id, $value)
    {
        if ($entity = DocumentEntity::find($id)) {
            //echo "<pre>";
            //print_r($entity);
            //die("Comes here");
            if ($entity->enabled != $value) {
                $entity->enabled = $value;
                if ($result = $entity->save()) {
                    Cache::flush();
                }
            }
            return redirect(URL::previous());
        }
    }


    /**
     * Called from PersonalPageController.
     *
     * @param integer $id
     * @return string
     */
    public function promote($id, $value)
    {
        $value = ($value == 'promote') ? 1 : 0;
        if ($entity = DocumentEntity::find($id)) {
            if ($entity->top_article != $value) {
                $entity->top_article = $value;
                if ($result = $entity->save()) {
                }
            }
            return redirect(URL::previous());
        }
    }


    /**
     * Enable actions from notify emails.
     *
     * @param Entity $id
     * @param string $action
     */
    public function action($id, $action)
    {
        $result = false;
        switch ($action) {
            case 'disable':
                if ($entity = DocumentEntity::find($id)) {
                    if ($entity->enabled) {
                        $entity->enabled = 0;
                        if ($result = $entity->save()) {
                            return sprintf('The entry with id %d is disabled now.', $id);
                        }
                    }
                    return sprintf('The entry with id %d is already disabled.', $id);
                }
                break;
            case 'promote':
                if ($entity = DocumentEntity::find($id)) {
                    if (!$entity->top_article) {
                        $entity->top_article = 1;
                        if ($result = $entity->save()) {
                            return sprintf('The entry with id %d is in the Top-Article-List now.', $id);
                        }
                    }
                    return sprintf('The entry with id %d is already in the Top-Article-List.', $id);
                }
                break;
            case 'degrade':
                if ($entity = DocumentEntity::find($id)) {
                    if ($entity->top_article) {
                        $entity->top_article = 0;
                        if ($result = $entity->save()) {
                            return sprintf('The entry with id %d is removed from the Top-Article-List now.', $id);
                        }
                    }
                    return sprintf('The entry with id %d is already removed from the Top-Article-List.', $id);
                }
                break;
            case 'facebook':
                if ($entity = DocumentEntity::find($id)) {

                    //unset($_SESSION['facebook_access_token']);

                    $pages = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                        ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                        ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                        ->where('document_entities.document_type_id', '<>', 23) //guest book
                        ->where('document_entities.document_type_id', '<>', 63) //family tree
                        ->where('document_attributes.l', App::getLocale())
                        ->where('document_attributes.key', 'title')
                        ->where('document_entities.id', $id)
                        ->orderBy('created_at', 'DESC')
                        //->remember(60)
                        ->paginate(20);

                    $items = ViewEntity::build($pages->items());
                    $appId         = '435414210419369';//env('FACEBOOK_APP_ID'); //Facebook App ID
                    $appSecret     = 'e804a65e10ce42c9f344ecba750cb046';//env('FACEBOOK_APP_SECRET'); //Facebook App Secret
                    $redirectURL   = URL::to('en/').'/'.'entry/postRecord'; //Callback URL
                    $fbPermissions = array('manage_pages'); //Facebook permission
                    $page_id       = '122766677740487';//env('FACEBOOK_PAGE_ID'); //Page Id Kythera family
                    $fb = new Facebook([
                        'app_id' => $appId,
                        'app_secret' => $appSecret,
                        'default_graph_version' => 'v5.0'
                    ]);


                    $helper = $fb->getRedirectLoginHelper();


                    if (isset($_GET['state'])) {
                        $helper->getPersistentDataHandler()->set('state', $_GET['state']);
                    }
                    try {
                        if(isset($_SESSION['facebook_access_token'])){
                            $accessToken = $_SESSION['facebook_access_token'];
                        }else{
                            $accessToken = $helper->getAccessToken();
                        }
                    } catch(FacebookResponseException $e) {
                        'Graph returned an error: ' . $e->getMessage();
                        exit;
                    } catch(FacebookSDKException $e) {
                        'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }


                    if(isset($accessToken)){
                        if(isset($_SESSION['facebook_access_token'])){
                            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                        }else{
                            // Put short-lived access token in session
                            $_SESSION['facebook_access_token'] = (string) $accessToken;

                            // OAuth 2.0 client handler helps to manage access tokens
                            $oAuth2Client = $fb->getOAuth2Client();

                            // Exchanges a short-lived access token for a long-lived one
                            $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                            $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

                            // Set default access token to be used in script
                            $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                        }
                        //FB post content
                        $message     = 'Kythera Family';
                        $title       = ($entity->title) ? $entity->title:'Kythera';
                        //$link        =  'https://kythera-family.net/en/gravestones/gravestones/something-by-deepak';
                        $link        =  $items[0]->uri;
                        //echo $link;die;
                        $description = ($entity->content) ? $entity->content: 'Kythera';

                        if ($image = \DB::table('document_images')
                            ->where('entry_id', $id)
                            ->first()) {
                            $filename     = URL::to('/').'/'.$image->image_path.$image->image_name;
                            $picture      = $filename;
                            $attachment   = array(
                                'message'     => $description,
                                'name'        => $title,
                                'link'        => $link,
                                'description' => $description,
                                'picture'     => $filename,
                            );

                        }else{
                            $attachment = array(
                                'message'     => $description,
                                'name'        => $title,
                                'link'        => $link,
                                'description' => $description,
                            );
                        }

                        //get page access token


                        $ch = curl_init('https://graph.facebook.com/'. $page_id .'?fields=access_token&access_token='.$accessToken);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                        curl_setopt($ch, CURLOPT_HEADER, 1);
                        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        $return = curl_exec($ch);
                        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                        $header = substr($return, 0, $header_size);
                        $body = substr($return, $header_size);
                        $final = json_decode($body);
                        $page_aceess_token=  $final->access_token;

                        try{
                            // Post to Facebook
                            $fb->post($page_id.'/feed', $attachment, $page_aceess_token);

                            // Display post submission status
                            echo 'The post was published successfully to the Kythera Family Facebook timeline.';
                        }catch(FacebookResponseException $e){

                            echo 'Graph returned an error: ' . $e->getMessage();
                            exit;
                        }catch(FacebookSDKException $e){
                            echo 'Facebook SDK returned an error: ' . $e->getMessage();
                            exit;
                        }
                    }else{
                        // Get Facebook login URL
                        $fbLoginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);
                        $_SESSION['post_id']= $id;
                        // Redirect to Facebook login page
                        return redirect($fbLoginURL);

                    }
//                    echo '<pre>';

                    return sprintf('The entry with id %d has been posted on Facebook Page.', $id);
                }
                break;
            default:
        }
        Log::info('Action: ' . $action . ' for entry: ' . $id, [
            'entity.action',
            $result
        ]);
    }


    public function postRecord(){
        if (isset($_SESSION['post_id'])){
            $id =$_SESSION['post_id'];

            if ($entity = DocumentEntity::find($id)) {

                $pages = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
                    ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->where('document_entities.document_type_id', '<>', 23) //guest book
                    ->where('document_entities.document_type_id', '<>', 63) //family tree
                    ->where('document_attributes.l', App::getLocale())
                    ->where('document_attributes.key', 'title')
                    ->where('document_entities.id', $id)
                    ->orderBy('created_at', 'DESC')
                    //->remember(60)
                    ->paginate(20);

                $items = ViewEntity::build($pages->items());
                $appId         = '435414210419369';//env('FACEBOOK_AAP_id'); //Facebook App ID
                $appSecret     = 'e804a65e10ce42c9f344ecba750cb046';//env('FACEBOOK_AAP_Secret'); //Facebook App Secret
                $redirectURL   = URL::to('en/').'/'.'fb/login/'; //Callback URL
                $fbPermissions = array('manage_pages'); //Facebook permission
                $page_id       = '122766677740487';//env('FACEBOOK_PAGE_ID'); //Page Id Kythera family
                $fb = new Facebook([
                    'app_id' => $appId,
                    'app_secret' => $appSecret,
                    'default_graph_version' => 'v5.0'
                ]);

                $helper = $fb->getRedirectLoginHelper();


                if (isset($_GET['state'])) {
                    $helper->getPersistentDataHandler()->set('state', $_GET['state']);
                }
                try {
                    if(isset($_SESSION['facebook_access_token'])){
                        $accessToken = $_SESSION['facebook_access_token'];
                    }else{
                        $accessToken = $helper->getAccessToken();
                    }
                } catch(FacebookResponseException $e) {
                    'Graph returned an error: ' . $e->getMessage();
                    exit;
                } catch(FacebookSDKException $e) {
                    'Facebook SDK returned an error: ' . $e->getMessage();
                    exit;
                }

                if(isset($accessToken)){
                    if(isset($_SESSION['facebook_access_token'])){
                        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                    }else{
                        // Put short-lived access token in session
                        $_SESSION['facebook_access_token'] = (string) $accessToken;

                        // OAuth 2.0 client handler helps to manage access tokens
                        $oAuth2Client = $fb->getOAuth2Client();

                        // Exchanges a short-lived access token for a long-lived one
                        $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                        $_SESSION['facebook_access_token'] = (string) $longLivedAccessToken;

                        // Set default access token to be used in script
                        $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
                    }
                    //FB post content
                    $message     = 'Kythera Family';
                    $title       = ($entity->title) ? $entity->title:'Kythera';
                    //$link        =  'https://kythera-family.net/en/gravestones/gravestones/something-by-deepak';
                    $link        =  $items[0]->uri;
                    $description = ($entity->content) ? $entity->content: 'Kythera';

                    if ($image = \DB::table('document_images')
                        ->where('entry_id', $id)
                        ->first()) {
                        $filename     = URL::to('/').'/'.$image->image_path.$image->image_name;
                        $picture      = $filename;
                        $attachment   = array(
                            'message'     => $title,
                            'name'        => $title,
                            'link'        => $link,
                            'description' => $description,
                            'picture'     => $filename,
                        );

                    }else{
                        $attachment = array(
                            'message'     => $title,
                            'name'        => $title,
                            'link'        => $link,
                            'description' => $description,
                        );
                    }

                    //get page access token

                    $ch = curl_init('https://graph.facebook.com/'. $page_id .'?fields=access_token&access_token='.$accessToken);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                    curl_setopt($ch, CURLOPT_HEADER, 1);
                    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                    $return = curl_exec($ch);
                    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
                    $body = substr($return, $header_size);
                    $final = json_decode($body);
                    $page_aceess_token=  $final->access_token;

                    try{
                        // Post to Facebook
                        $fb->post($page_id.'/feed', $attachment, $page_aceess_token);

                        // Display post submission status
                        echo 'The post was published successfully to the Kythera Family Facebook timeline.';
                    }catch(FacebookResponseException $e){

                        echo 'Graph returned an error: ' . $e->getMessage();
                        exit;
                    }catch(FacebookSDKException $e){
                        echo 'Facebook SDK returned an error: ' . $e->getMessage();
                        exit;
                    }
                }else{
                    // Get Facebook login URL
                    $fbLoginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);

                    // Redirect to Facebook login page
                    return redirect($fbLoginURL);

                }
//                    echo '<pre>';

                return sprintf('The entry with id %d has been posted on Facebook Page.', $id);
            }
        }else{
            echo 'Jayesh Sarda';
        }
    }

    public function doSendEmails($type,$entity){
        switch ($type) {
            case 'entity.created':
                $subject = 'New Entry: ';
                break;
            case 'entity.updated':
                $subject = 'Changed Entry: ';
                break;
            case 'entity.deleted':
                $subject = 'Deleted Entry: ';
                break;
        }
        $entry = DocumentEntity::select('document_entities.*', 'users.firstname', 'users.middlename', 'users.lastname', 'users.id as user_id')
        ->leftJoin('users', 'document_entities.persons_id', '=', 'users.id')
        ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
        ->where('document_attributes.l', App::getLocale())
        ->where('document_attributes.key', 'title')
        ->where('document_entities.id', $entity->id)
        ->first();
        if ($images = $entry->images()) {
            if ($image = current($images)) {
                $data['image'] = sprintf('http://%s/%s%s', $this->request->server('HTTP_HOST'), $image->image_path, $image->image_name);
            }
        } 
        $data['author'] = ucfirst(trans('locale.submitted', [
          'fullname' => xhtml::fullname($entry, false),
          'date'     => $entry->updated_at->format('d/m/Y, H:i')
        ]) . ' from ' . $this->request->server('REMOTE_ADDR'));
        $data['title'] = (string)$entity->title;
        $data['content'] = (string)$entity->content;
        $data['view'] = urldecode(Router::getItemUrl($entity));

        $data['edit'] = action('EntryController@edit', $entity->id);
        $data['disable'] = action('EntryController@action', [$entity->id, 'disable']);
        $data['promote'] = action('EntryController@action', [$entity->id, 'promote']);
        $data['facebook'] = action('EntryController@action', [$entity->id, 'facebook']);
        $data['host'] = 'http://' . $this->request->server('HTTP_HOST');
        $data['server'] = $this->request->server();
        
        #send message to administrators
        $queue_id = Mail::send('emails.notify.document', $data, function ($message) use ($entity, $subject) {
            $message->from(config('app.administrator'), 'kythera-family.net administrator');
            $users = User::where('enable_email',1)->get(); 
            foreach( $users  as $user ){ 
                $message->to($user->email, 'kythera-family.net administrator');
            }    
            $message->bcc(config('app.developer'), 'developer')
                    ->subject('[KFN] '.$subject.$entity->title);
        });
    }

    public function test_job()
    {

       $query = DB::table('jobs');
       $result = $query->get();
       print_r($result);die();
    }
}
