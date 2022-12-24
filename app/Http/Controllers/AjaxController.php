<?php

namespace App\Http\Controllers;

use App\Http\Requests\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
// use Illuminate\Support\Facades\HTML;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Kythera\Html\Facades\Html;
use Kythera\Models\DocumentEntity;
use Kythera\Models\FamilyPerson;
use Kythera\Models\HidrateDocument;
use Kythera\Models\Person;
use Kythera\Router\Facades\Router;


/**
 * @author virgilm
 *
 */

class AjaxController extends Controller
{

    public function __construct()
    {
        if (!config('app.debug') && !request()->ajax()) {
            return response('Nothing to see here', 500);
        }
    }


    /**
     * Return ajax search results as json for the typeahead searchbox
     */
    public function suggest()
    {
            $data  = [];
        if (isset($_GET['q'])) {
            $query = Input::get('q');
            if (strlen($query)>2) {
                //live search
                $items = DB::table('document_entities')
                    ->select('document_entities.created_at', 'document_attributes.*')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->where('document_entities.document_type_id', '!=', 23)
                    ->where('document_entities.enabled', '=', 1)
                    ->where('document_attributes.l', '=', \App::getLocale())
                    //->where('document_attributes.key', '=', 'title')
                    ->whereIn('document_attributes.key', ['title', 'content'])
                    ->where('document_attributes.value', 'LIKE', $this->query($query))
                    ->orderByRaw('document_entities.created_at DESC')
                    ->limit(10)
                    ->get();
                foreach ($items as $item) {
                    $crumbs = '';
                    if ($page = $this->getPage($item)) {
                        $crumbs = HTML::crumbs($page, ' &gt; ', true);
                    }
                    $url = $this->getPageByEntryID($item);
                    $path = parse_url($url, PHP_URL_PATH);

                    if ($item->key=='content') {
                        $c = $this->scount($item->value, $this->query($query));
                        $s = $this->cut($item->value, $this->query($query), 40);
                        $l = sprintf('<i class="c">%d occurence(s) in text</i>', $c);
                    } else {
                        $s = $item->value;
                        $l = sprintf('<i class="c">exact hit in title</i>');
                    }

                    if ($crumbs && $url && $path) {
                        $data[] = ['crumbs'=>$crumbs, 'year'=>date('m.Y', strtotime($item->created_at)), 'value'=>$s, 'url'=>$path, 'label'=>$l];
                    }
                }
            }
        } else {
            //prefetch
            if (config('cache.cacheable.prefetch', true)) {
                $items = Cache::remember('prefetch.'.App::getLocale(), 60, function () {
                    return DB::table('document_entities')
                    ->select('document_entities.created_at', 'document_attributes.*')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->where('document_entities.document_type_id', '!=', 23)
                    ->where('document_entities.enabled', '=', 1)
                    ->where('document_attributes.l', '=', \App::getLocale())
                    ->where('document_attributes.key', '=', 'title')
                    ->orderByRaw('document_entities.created_at DESC')
                    ->get();
                });
            } else {
                $items = DB::table('document_entities')
                    ->select('document_entities.created_at', 'document_attributes.*')
                    ->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
                    ->where('document_entities.document_type_id', '!=', 23)
                    ->where('document_entities.enabled', '=', 1)
                    ->where('document_attributes.l', '=', \App::getLocale())
                    ->where('document_attributes.key', '=', 'title')
                    ->orderByRaw('document_entities.created_at DESC')
                    ->get();
            }

            foreach ($items as $item) {
                $crumbs = '';
                if ($page = $this->getPage($item)) {
                    $crumbs = HTML::crumbs($page, ' &gt; ', true);
                }
                $url = $this->getPageByEntryID($item);
                $path = parse_url($url, PHP_URL_PATH);
                $value = $item->value;
                $label = sprintf('<i class="c">exact hit in title</i>');
                //             		echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';
                //             		echo __FILE__.__LINE__.'<pre>$crumbs='.htmlentities(print_r($crumbs,1)).'</pre>';
                //             		echo __FILE__.__LINE__.'<pre>$url='.htmlentities(print_r($url,1)).'</pre>';
                //             		echo __FILE__.__LINE__.'<pre>$path='.htmlentities(print_r($path,1)).'</pre>';die;
                if ($crumbs && $url && $path) {
                    $data[] = ['crumbs'=>$crumbs, 'year'=>date('m.Y', strtotime($item->created_at)), 'value'=>$value, 'url'=>$path, 'label'=>$label];
                }
            }
        }
            return Response::json($data);
    }

    /**
     * Return ajax search results as json for the typeahead searchbox
     */
    public function esuggest($minlength = 1)
    {
            $data  = [];
        if (isset($_GET['q'])) {
            $query = Input::get('q');
            if (strlen($query)>=$minlength) {
                $search = HidrateDocument::suggest($query);
                $scores = $search->all();
                if ($scores) {
                    foreach ($scores as $id => $hit) {
                        //echo __FILE__.__LINE__.'<pre>$hit='.htmlentities(print_r($hit,1)).'</pre>';die;
                        $source = $hit['source'];
                        $crumbs = '';
                        if ($page = $this->getPageByControllerID($source['document_type_id'])) {
                            $crumbs = HTML::crumbs($page, ' &gt; ', true);
                        }
                        $url = $source['url'];
                        $path = parse_url($url, PHP_URL_PATH);
                        $t = $source['title'];
                        $s = $source['title'];
                        $f = 'title';
                        $highlight = $hit['highlight'];
                        if (isset($highlight['content'][0])) {
                            $s = $highlight['content'][0];
                            $f = 'content';
                            $s.='...';
                        }
                        if (isset($highlight['title'][0])) {
                            $s = $highlight['title'][0];
                            $f = 'title';
                            $s = '';
                        }
                        if (isset($highlight['user.lastname'][0])) {
                            //$s = str_ireplace($keys[0], $highlight['user.lastname'][0], $a);
                            $s = $highlight['user.lastname'][0];
                            $f = 'user';
                            $s = '';
                        }
                        $l = sprintf('<i class="c">(%s in %s)</i>', $hit['score'], $f);

                        if ($t==$s) {
                            $s='';
                        }

                        if ($crumbs && $url && $path) {
                            $data[] = ['crumbs'=>$crumbs, 'year'=>date('m.Y', strtotime($source['updated_at'])), 'title'=>$t, 'value'=>$s, 'url'=>$path, 'label'=>$l];
                        }
                    }
                }
            }
            //echo __FILE__.__LINE__.'<pre>$data='.htmlentities(print_r($data,1)).'</pre>';
        } else {
            //prefetch
        }
            return Response::json($data);
    }


    public function getFamilyTreePersonInfo($persons_id)
    {
        return Person::AjaxGetPersonInfo($persons_id);
    }


    /**
     *
     * Ajax Helper functions
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


    private function getPageByControllerID($controller_id)
    {
        if ($page = Router::find(null, ['key'=>'controller_id', 'val'=>$controller_id])) {
            return $page;
        }
        return false;
    }


    private function query($q)
    {
        $result = [];
        foreach (explode(' ', $q) as $p) {
            if (!trim($p)) {
                continue;
            }
            $result[] = trim($p);
        }
        return '%'.implode('%', $result).'%';
    }


    private function cut($s, $query, $size = 20)
    {
        $q = explode('%', $query);
        $s = strip_tags($s);
        $p = stripos($s, $q[1]);
        $s = substr($s, $p-$size);
        $s = \str_limit($s, $size*2);
        return '...'.$s;
    }


    private function scount($s, $query)
    {
        $c = 0;
        foreach (explode('%', $query) as $q) {
            if (!(trim($q))) {
                continue;
            }
            $c += substr_count(strtolower($s), strtolower($q));
        }
        return $c;
    }
}
