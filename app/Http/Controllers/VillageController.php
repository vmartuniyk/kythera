<?php

namespace App\Http\Controllers;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Kythera\Entity\Entity;
use Kythera\Html\Facades\Html;
use Kythera\Models\DocumentEntity;
use Kythera\Models\Village;
use Kythera\Router\Facades\Router;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @author virgilm
 *
 */


class VillageController extends PageController
{

    protected function getIndexData()
    {
// 		$users = DB::table('users')->remember(10)->get();
// 		$users = DB::table('users')->cacheTags(array('people', 'authors'))->remember(10)->get();

// 		$time_start = microtime(true);

        $villages = [];

        #find all used letters
        $letters = Village::getAlphabet();

        #get all villages per letter
        foreach ($letters as $letter) {
            if ($items = Village::where('village_name', 'like', $letter.'%')
                                ->where('character_set_id', App::getLocale() == 'en' ? 'latin' : 'greek')
                                ->where('visible', 1)
                                //->cacheTags(array('letters'))
                                //->remember(10)
                                ->get()) {
                $villages[$letter] = $items;
            }

            foreach ($villages[$letter] as $village) {
                $count = DB::table('document_entities')
                            ->where('related_village_id', $village->id)
                            ->where('enabled', 1)
                            //->cacheTags(array('villages'))
                            //->remember(10)
                            ->count();
                $village->count = $count;
            }
        }


        //$time = microtime(true) - $time_start;echo "Elapsed $time seconds.";die;

        return $villages;
    }


    public function getIndex()
    {

        $villages = $this->getIndexData();

        return $this->view('index')
                    ->with('villages', $villages);
    }


    /**
     * Display one resource.
     *
     * @return Response
     */
    public function getEntry($entry = null)
    {
        //solve space problems
        $entry = $this->slugToUri($entry);


        //get selected village
        if ($entity = Village::select('*', 'village_name as title')
                            ->where('village_name', $entry)
                            ->where('character_set_id', App::getLocale() == 'en' ? 'latin' : 'greek')
                            ->first()) {
            $total = 0;
            $items = [];
            $documents = [];

            //get grouped categories
            if ($categories = DocumentEntity::select('document_type_id', DB::raw('count(*) as count'))
                                      ->join('pages', 'document_entities.document_type_id', '=', 'pages.controller_id')
                                      ->where('related_village_id', $entity->id)
                                      ->where('enabled', 1)
                                      ->where('pages.active', 1)
                                      ->groupBy('document_type_id')
                                      ->orderBy('folder_id')
                                      ->orderBy('parent_id')
                                      ->get()) {
                foreach ($categories as $category) {
                    $route = Router::getEntityPage($category);
                    $total += $category->count;

                    //no controller installed
                    if (!is_object($route->page)) {
                        continue;
                    }

                    /*
    				//get documents
    				if ($documents = DocumentEntity::where('document_type_id', $category->document_type_id)
    												->where('enabled', 1)
    												->get())
    				{
    					$items[] = array(
    							'category' => Router::getEntityPage($category),
    							'documents' => $documents,
    							'n' => $category->count
    					);
    				}
    				*/

                    $items[] = [
                        'route' => $route,
                        'count' => $category->count
                    ];
                }
            }

            return $this->view('view')
                        ->with('item', $entity)
                        ->with('total', $total)
                        ->with('categories', $items);
        } else {
            throw new NotFoundHttpException();
        }
    }


    /**
     * Solve slug problems.
     * @param string $entry
     */
    private function slugToUri($entry)
    {
        if ($items = Village::select('id', 'village_name')
                ->where('character_set_id', App::getLocale() == 'en' ? 'latin' : 'greek')
                ->get()) {
            foreach ($items as $item) {
                if ($entry == Translation::slug($item->village_name)) {
                    return $item->village_name;
                }
            }
        }
        return $entry;
    }

    /**
     * Make view wrapper to make the current request page available in all templates.
     * @param string $view
     * @return View
     */
// 	protected function view($view = 'site.page.search')
// 	{
// 		switch($this->getCurrentPage()->controller) {
// 			case 'SearchPageController':
// 				$view = 'site.page.search.'.$view;
// 			break;
// 		}

// 		return view($view)
// 			->with('page', $this->getCurrentPage());
// 	}
}
