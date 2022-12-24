<?php

namespace App\Composers;

use App\Models\Document;

/**
 * @author virgilm
 *
 */
class PeopleNamesComposer extends PageComposer
{
    
    public function compose($view)
    {
        parent::compose($view);
        
        $data = $view->getData();
        $results = [];
        
        if ($entry = $data['entry']) {
            $items = Document::where('title', 'like', '% '.$entry->name.'%')
                ->whereOr('uri', 'like', '%'.$entry->name.'%')
                ->whereOr('content', 'like', '% '.$entry->name.'%')
                ->orderBy('document_type_id')
                ->get();
            
            foreach ($items as $item) {
                if ($page = Router::find(null, ['key'=>'controller_id', 'val'=>$item->document_type_id])) {
                    $uri  = route($page->name.'.entry', $item->uri);
                    
                    $result = new stdClass;
                    $result->page = $page;
                    $result->uri  = $uri;
                    $result->item = $item;
                    $results[$page->id][] = $result;
                } else {
                    throw new Exception('Page not found for item: '.$item->id);
                }
            }
        }
        
        $view->with('entry', $entry);
        $view->with('results', $results);
    }
}
