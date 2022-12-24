<?php

namespace App\Http\Controllers;

// use Illuminate\Support\Facades\Html;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Kythera\Html\Facades\Html;
use Kythera\Models\DocumentEntity;
use Kythera\Models\HidrateDocument;
use Kythera\Models\SearchModel;
use Kythera\Router\Facades\Router;
use xhtml;

/**
 * @author virgilm
 *
 */


class ElasticSearchPageController extends PageController
{

    public function getIndex()
    {
        if (Input::has('q')) {
            Session::set('query', trim(Input::get('q')));
        }

        //handle ordering
        if (isset($_GET['po'])) {
            Session::set('paginate_order', max(1, min(5, Input::get('po'))));
        }
        $paginate_order  = Session::get('paginate_order', 1);

        //handle category filter
        if (isset($_GET['c'])) {
            Session::set('category_filter', Input::get('c'));
        }
        $category_filter = Session::get('category_filter', 0);

        //handle author filter
        if (isset($_GET['a'])) {
            Session::set('author_filter', Input::get('a'));
        }
        $author_filter = Session::get('author_filter', 0);

        $results = $this->doSearch(Session::get('query'));

        return $this->view('index')
                ->with('pages', $results['pages'])
                ->with('related', $results['related'])
                ->with('items', $results['items'])
                ->with('search', $results['search']);
    }

    /**
     * Make view wrapper to make the current request page available in all templates.
     * @param string $view
     * @return View
     */
    protected function view($view = 'site.page.search')
    {
        switch ($this->getCurrentPage()->controller) {
            case 'ElasticSearchPageController':
                $view = 'site.page.search.'.$view;
                break;
        }

        return view($view)
            ->with('page', $this->getCurrentPage());
    }



    private function doSearch($query)
    {
        /*
		 * in category
		 * in section
		 * in author
		 *
		 * sort by newest first
		 * sort by newset last
		 * sort document type
		 * sort by alphabet
		 * sort by author
		 * sort by relevance
		 */


        $result = [
            'pages' => [],
            'related' => [],
            'items' => [],
            'search' => []
        ];

        if ($query) {
            $data   = [];
            $items  = [];

            //set search options
            $keys = $this->query($query);
            $orders = [
                1 => ['field'=>'created_at', 'direction'=>'desc'],
                2 => ['field'=>'created_at', 'direction'=>'asc'],
                3 => ['field'=>'title.original', 'direction'=>'asc'],
                4 => ['field'=>'lastname', 'direction'=>'asc'],
                5 => ['field'=>'lastname', 'direction'=>'desc']
            ];
            $orders = [
                1 => ['field'=>'created_at', 'direction'=>'desc'],
                2 => ['field'=>'created_at', 'direction'=>'asc'],
                3 => ['field'=>'title.original', 'direction'=>'asc'],
                4 => ['field'=>'lastname', 'direction'=>'asc'],//submitter
                5 => ['field'=>'relevance', 'direction'=>'asc']//relevance
            ];
            $sort = $orders[Session::get('paginate_order', 1)];
            $filter = [
                'c' => Session::get('category_filter', 0),
                'a' => Session::get('author_filter', 0)
            ];
            $operator = HidrateDocument::SEARCH_AND;
            $search = HidrateDocument::search($query, $operator, $filter, $sort);
            $scores = $search->all();
            if ($scores) {
                $items = DocumentEntity::user()
                    ->where('enabled', 1)
                    ->whereIn('document_entities.id', array_keys($scores))
                    ->orderByRaw('FIELD(document_entities.id, '.implode(',', array_keys($scores)).')')
                    ->paginate(10);
                foreach ($items as $i => $entry) {
                    $crumbs = '';
                    if ($page = $this->getPage($entry)) {
                        $crumbs = HTML::crumbs($page, ' &gt; ', true);
                    }
                    $url = $this->getPageByEntryID($entry);
                    $path = parse_url($url, PHP_URL_PATH);
                    $path2 = parse_url(Router::getItemUrl($entry), PHP_URL_PATH);
                    $t = (string)$entry->title;
                    $s = $this->cut($entry->content, $this->query($query, false), 100, true);
                    //$s = (string)$entry->content;
                    $l = $scores[$entry->id]['score'];
                    $a = xhtml::fullname($entry, false);

                    //insert highlights
                    $highlight = $scores[$entry->id]['highlight'];
                    if (isset($highlight['content'][0])) {
                        $s = $highlight['content'][0].'...';
                    }
                    if (isset($highlight['title'][0])) {
                        $t = $highlight['title'][0];
                    }
                    if (isset($highlight['lastname'][0])) {
                        $a = str_ireplace($keys[0], $highlight['lastname'][0], $a);
                    }

                    if ($crumbs && $url && $path) {
                        $data[] = [
                            'id'=>$entry->id,
                            'crumbs'=>$crumbs,
                            'title'=>$t,
                            'year'=>date('d.m.Y', strtotime($entry->updated_at)),
                            'value'=>$s,
                            'url'=>$path,
                            'url2'=>$path2,
                            'label'=>$l,
                            'author'=>$a,
                        ];
                    }
                }
            }

            //keep history
            //$hits = SearchModel::history($this->query($query));
            $hits = [];

            //related
            //$related = SearchModel::related($hits, $query, 10);
            $related = [];

            $result = [
                'pages' => $items,
                'related' => $related,
                'items' => $data,
                'search' => $search
            ];
        }

        return $result;
    }




    /**
     *
     * Search Helper functions
     *
     */

    private function query($q, $lower = false)
    {
        $result = [];
        foreach (explode(' ', $q) as $p) {
            $p = trim($p);
            if ($lower) {
                $p = strtolower($p);
            }
            if (!$p) {
                continue;
            }
            $result[] = $p;
        }
        return $result;
    }


    private function cut($s, $query, $size = 20, $highlight = false)
    {
        //$q = explode('%', $query);
        $q = $query;
        $s = strip_tags($s);
        $p = stripos($s, $q[0]);

        if ($p!==false) {
            $e = max($p-$size, 0);
            $s = substr($s, $e);
            $s = \str_limit($s, $size*2);
            if ($highlight) {
                //foreach (array_slice($q,1,count($q)-2) as $key) {
                foreach ($q as $key) {
                    $s = str_ireplace($key, '<span class="highlight">'.$key.'</span>', $s);
                }
            }
            if ($e>0) {
                $s = '...'.$s;
            }
        } else {
            $s = Str::words($s, 50);
        }

        return $s;
    }


    private function getPageByEntryID($item)
    {
        if ($page = Router::find(null, ['key'=>'controller_id', 'val'=>$item->document_type_id])) {
            return route($page->name.'.id', $item->document_entity_id);
        }
        return false;
    }


    private function getPage($item)
    {
        if ($page = Router::find(null, ['key'=>'controller_id', 'val'=>$item->document_type_id])) {
            return $page;
        }
        return false;
    }
}
