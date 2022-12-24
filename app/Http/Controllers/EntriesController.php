<?php

namespace App\Http\Controllers;

use Event;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentUploader;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class EntriesController extends EntryController
{

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */

    public function create($catId = null)
    {
        //check if there are files already uploaded (accessed through back button)
        $files = DocumentUploader::setUploaderTempFiles();
        return view('site.document.multi.create')
                ->with('files', $files);
    }


    /**
     * Create a detail form for every submitted image.
     *
     * @return Response
     */
    public function next($id = null)
    {
        $files = DocumentUploader::getUploaderFiles($_POST);
        if (!$files) {
            return $this->create();
        }

        return view('site.document.multi.next');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        /*
		//set in PageComposer.php
		if (!$page = Session::get('page')) {
			//happens when we request a page not passed through our router (static page)
			throw new Exception('Could not get Page object from session.');
		}
		*/

        //validate all entries
        $validated  = true;
        $validators = [];
        foreach (Input::get('entries') as $i => $entry) {
            $validator  = Validator::make($entry, DocumentImage::$rules, DocumentImage::$messages);
            $validated &= $validator->passes();
            $validators[$i] = $validator;
        }

        //show errors, if any
        if (!$validated) {
            $response = Redirect::route('entries.next')
                ->with('global', "Error saving entries.")
                ->withInput();
            foreach ($validators as $i => $validator) {
                $response->withErrors($validator, $i);
            }
            return $response;
        }


        $first = null;
        $saved = 0;
        $entries = DocumentUploader::getUploaderFiles2(Input::get('entries'));
        foreach ($entries as $type => $types) {
            foreach ($types as $data) {
                switch ($type) {
                    case 'image':
                        if ($entity = DocumentImage::add($data, null)) {
                            $entity->copyright = $data['c'];
                            $entity->save();

                            if ($id = $entity->setImage($entity, $data)) {
                                if (!$saved++) {
                                    $first = $entity;
                                }

                                //notify
                                Event::fire('entity.created', $entity);
                            }
                        }
                        break;
                }
            }
        }

        if ($first && $saved===count(Input::get('entries'))) {
            DocumentUploader::clean();
            return redirect(Router::getItemUrl($first))
                ->with('global', "Successfully saved entries: {$saved}");
        }

        //error
        return Redirect::route('entries.next')
            ->with('global', "Error saving entries.")
            ->withInput();
    }
}
