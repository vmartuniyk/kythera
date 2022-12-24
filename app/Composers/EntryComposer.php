<?php

namespace App\Composers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Kythera\Models\DocumentEntity;
use Kythera\Models\DocumentImage;
use Kythera\Models\DocumentUploader;
use Kythera\Models\PageEntity;

/**
 * @author virgilm
 *
 */
class EntryComposer extends PageComposer
{

    public function compose($view)
    {
        parent::compose($view);

        switch ($view->getName()) {
            case 'site.document.create':
            case 'site.document.next':
                $view->with('categories', $categories = $this->getCategories());
                
                
                //$h = xmenu::categories($categories);            	echo __FILE__.__LINE__.'<pre>$h='.htmlentities(print_r($h,1)).'</pre>';die;
                 
                 
/*
                //temp fix: select default page if not set.
                if (!Session::get('page')) {
                	Session::set('page', $categories[0]);
                }
                $view->with('page', Session::get('page'));
 */
            case 'site.document.next':
                $view->with('categories', $categories = $this->getCategories());
/*
            	//temp fix: select default page if not set.
            	if (!Session::get('page')) {
            		Session::set('page', $categories[0]);
            	}
            	$view->with('page', Session::get('page'));
 */
                $files = DocumentUploader::getUploaderFiles($_POST);
                $view->with('files', $files);

                $view->with('villages', $this->getVillages());
                break;
            case 'site.document.next2':
                $files = DocumentUploader::getUploaderFiles($_POST);
                foreach ($files as $i => $file) {
                    $files[$i]['taken'] = '';
                    if (DocumentEntity::isImage($file['name'])) {
                        if ($data = \DB::table('document_images')->find($file['kfnid'])) {
                            //echo __FILE__.__LINE__.'<pre>='.htmlentities(print_r($data,1)).'</pre>';
                            //$date = !empty($data->taken) ? date('d/m/Y', strtotime($data->taken)) : null;
                            //echo __FILE__.__LINE__.'<pre>='.htmlentities(print_r($date,1)).'</pre>';

                            $date = DocumentEntity::convertToGreek($data->taken);
                            //echo __FILE__.__LINE__.'<pre>='.htmlentities(print_r($date,1)).'</pre>';die;

                            $files[$i]['taken'] = $date;
                        }
                    } else if (DocumentEntity::isMedia($file['name'])) {
                        if ($data = \DB::table('document_audio')->find($file['kfnid'])) {
                            //$date = !empty($data->recorded) ? date('d/m/Y', strtotime($data->recorded)) : null;
                            $date = DocumentEntity::convertToGreek($data->taken);
                            $files[$i]['taken'] = $date;
                        }
                    }
                }
                $view->with('files', $files);

                $view->with('categories', $this->getCategories());
                $view->with('villages', $this->getVillages());
                break;
            case 'site.document.details':
                //depricated
                break;
            case 'site.document.details2':
                //depricated
                break;
            case 'site.document.edit':
            case 'site.document.quoted.edit':
            //case 'site.document.image.edit':
                $categories = $this->getCategories();
                $view->with('categories', $categories = $this->getCategories());
/*
                //temp fix: select default page if not set.
                if (!Session::get('page')) {
                	Session::set('page', $categories[0]);
                }
                $view->with('page', Session::get('page'));
 */
                $view->with('villages', $this->getVillages());
                break;
            case 'site.document.multi.create':
            case 'site.document.multi.next':
//            	$view->with('page', Session::get('page'));
                $view->with('categories', $this->getCategories());
                $view->with('villages', $this->getVillages());

                $images = Input::old('entries') ? Input::old('entries')
                                                : DocumentUploader::getUploaderFiles($_POST);
                $view->with('images', $images);
            default:
        }
    }


    /**
     * Helper to fetch categories
     */
    private function getCategories()
    {
        return \Kythera\Router\Facades\Router::categories();
    }

    /*
    private function getCategories2()
    {
    	return PageEntity::select('controller_id', 'title')
		    	->where('controller_id', '>', 0)
		    	->where('title', '!=', '')
		    	->orderBy('title')
		    	->get();
    }
    */


    /**
     * Helper to fetch villages
     */
    private function getVillages()
    {
        return DB::table('villages')
                ->where('character_set_id', 'latin')
                ->where('visible', 1)
                ->where('village_name', '!=', '')
                ->orderBy('village_name')
                ->get();
    }
}
