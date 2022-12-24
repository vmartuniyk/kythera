<?php

namespace App\Http\Controllers;

// use Barryvdh\Debugbar\Facade;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Kythera\Models\PeopleName;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Kythera\Router\Facades\Router;

/**
 * @author virgilm
 *
 */
class PeopleNameController extends PageController
{

    /**
     * fixme: Move to composer?
     */
    public function getIndexData()
    {
        // Debugbar::startMeasure('render', 'PeopleNameController->getIndexData()');

        #find all used letters
        $letters = PeopleName::getAlphabet();

        #get all names per letter
        foreach ($letters as $letter) {
            if ($items = PeopleName::where('name', 'like', $letter.'%')
                                        ->where('character_set_id', App::getLocale() == 'en' ? 'latin' : 'greek')
                                        ->where('visible', 1)
                                        // ->remember(60)
                                        ->get()) {
                $names[$letter] = $items;
            }

            foreach ($names[$letter] as $i => $name) {
                $name->count = PeopleName::getDocumentCount($name);
            }
        }

        // Debugbar::stopMeasure('render');
        return $names;
    }


    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getIndex()
    {
        //config('cache.cacheable')['names'] = false;

        if (config('cache.cacheable')['names']) {
            $names = Cache::remember('peoplenames.'.App::getLocale(), 60, function () {
                return $this->getIndexData();
            });
        } else {
            $names = $this->getIndexData();
        }

        return $this->view('index')
                        ->with('names', $names);
    }


    /**
     * Display one resource.
     *
     * @return Response
     */
    public function getEntry($entry = null)
    {
        if ($entry = PeopleName::whereName($entry)
                                    ->first()) {
            if (!config('app.debug')) {
                $categories = Cache::remember('peoplename.'.$entry->id.'.'.App::getLocale(), 60*24*30, function () use ($entry) {
                    return $entry->getDocuments();
                });
            } else {
                $categories = $entry->getDocuments();
            }

            $categories_count = 0;
            foreach ($categories as $category) {
                $category->page = Router::getControllerPage($category->controllerID);
                $categories_count += $category->docCount;
            }

            return $this->view('view')
                        ->with('entry', $entry)
                        ->with('count', $categories_count)
                        ->with('categories', $categories);
        } else {
            throw new NotFoundHttpException();
        }
    }
}
