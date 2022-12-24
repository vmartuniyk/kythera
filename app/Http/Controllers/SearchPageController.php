<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Kythera\Html\Facades\Html;
use Kythera\Models\DocumentEntity;
use Kythera\Router\Facades\Router;


/**
 * @author virgilm
 *
 */


class SearchPageController extends PageController
{

    //INSERT INTO `laravel_kfn`.`document_types` (`id`, `string_id`, `table_name`, `controller`, `label`, `group_label`) VALUES ('0', 'KFN_SEARCH', NULL, 'SearchPageController', NULL, NULL);
    public function getIndex()
    {
        if (Input::has('q')) {
            Session::set('query', trim(Input::get('q')));
        }

        $results = $this->doSearch(Session::get('query'));

        return $this->view('index')
                ->with('pages', $results['pages'])
                ->with('related', $results['related'])
                ->with('items', $results['items']);
    }


    /**
     * Make view wrapper to make the current request page available in all templates.
     * @param string $view
     * @return View
     */
    protected function view($view = 'site.page.search')
    {
        switch ($this->getCurrentPage()->controller) {
            case 'SearchPageController':
                $view = 'site.page.search.'.$view;
                break;
        }

        return view($view)
            ->with('page', $this->getCurrentPage());
    }



    private function doSearch($query)
    {
        $result = [
            'pages' => [],
            'related' => [],
            'items' => []
        ];

        if ($query) {
            $data = [];
            $items = SearchModel::search($this->query($query));
            foreach ($items as $item) {
                $entry = DocumentEntity::user()->find($item->document_entity_id);

                $crumbs = '';
                if ($page = $this->getPage($item)) {
                    $crumbs = HTML::crumbs($page, ' &gt; ', true);
                }
                $url = $this->getPageByEntryID($item);
                $path = parse_url($url, PHP_URL_PATH);
                $path2 = parse_url(Router::getItemUrl($entry), PHP_URL_PATH);

                if ($item->key=='content') {
                    $c = $this->scount($item->value, $this->query($query));
                    $s = $this->cut($item->value, $this->query($query, false), 100, true);
                    $l = sprintf('%d occurence(s) in text', $c);
                    $t = (string)$entry->title;
                    $t = $this->highlight($this->query($query), (string)$entry->title);
                } else {
                    $s = $this->highlight($this->query($query), $item->value);
                    $l = sprintf('exact hit in title');
                    $t = (string)$entry->title;
                    $t = $this->highlight($this->query($query), (string)$entry->title);
                    //$t = (string)$entry->title;
                }

                if ($crumbs && $url && $path) {
                    $data[] = [
                        'id'=>$entry->id,
                        'crumbs'=>$crumbs,
                        'title'=>$t,
                        'year'=>date('d.m.Y', strtotime($item->updated_at)),
                        'value'=>$s,
                        'url'=>$path,
                        'url2'=>$path2,
                        'label'=>$l,
                        'author'=>xhtml::fullname($entry, false)
                    ];
                }
            }

            //keep history
            $hits = SearchModel::history($this->query($query));

            //related
            $related = SearchModel::related($hits, $query);

            $result = [
                'pages' => $items,
                'related' => $related,
                'items' => $data
            ];
        }

        return $result;
    }


    /**
     *
     * Search Helper functions
     *
     */

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
        return '%'.implode('%', $result).'%';
    }


    private function cut($s, $query, $size = 20, $highlight = false)
    {
        //$q = explode('%', $query);
        $q = $query;
        $s = strip_tags($s);
        $p = stripos($s, $q[0]);
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
        return $s;
    }


    private function scount($s, $query)
    {
        $c = 0;
        //foreach(explode('%', $query) as $q) {
        foreach ($query as $q) {
            if (!(trim($q))) {
                continue;
            }
            $c += substr_count(strtolower($s), strtolower($q));
        }
        return $c;
    }


    private function highlight($q, $v)
    {
        foreach ($q as $key) {
            $s = str_ireplace($key, '<span class="highlight">'.$key.'</span>', $v);
        }
        return $s;
    }
}
