<?php namespace Kythera\Html;

use App\Models\Comment;
use Kythera\Html\Html;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Kythera\Models\DocumentMessage;


/**
 * @author virgilm
 *
 */
class HtmlMessage extends Html
{

    //generate flattened list
    public function replies($entity, $messages = null, $level = 0)
    {
    	//echo __FILE__.__LINE__.'<pre>$entity='.htmlentities(print_r($entity,1)).'</pre>';

    	//save (so we can return in case of required login
    	Session::set('entity.view.id', $entity->id);

    	$level++;
        $h = '';
    	if ($items = DocumentMessage::getReplies($entity)) {
    		foreach ($items as $item) {
    			//echo __FILE__.__LINE__.'<pre>$item='.htmlentities(print_r($item,1)).'</pre>';die;


    			$h.=sprintf('<div class="comment-view level%d">', $level);
    			#add hashtag
    			$h.=sprintf('<a name="%d"></a>', $item->id);
    			$h.=sprintf('<div class="clearfix">');
    			$h.=sprintf('<img class="avatar" src="/assets/img/avatar.png">');
    			//$h.=\xmenu::entry_edit($item->user_id, $item);

    			$h.=sprintf('<p class="author">%s</p>', trans('locale.comment.author', array('fullname'=> Html::fullname($item, false), 'date'=>$item->created_at->format('d.m.Y'))) );
    			$h.=sprintf('</div>');
    			$h.=sprintf('<div class="line"></div>');

    			$h.=sprintf('<p>%s%s</p>', (Config::get('app.debug') ? $item->id.':': ''), strip_tags($item->content, Config::get('view.comments.allow_tags')));
    			//$h.=sprintf('<button class="btn btn-black">%s</button>', trans('locale.comment.reply'));
    			$h.=sprintf('</div>');

    			$h.=sprintf('
            		<div class="line title"></div>');

    		}
    	}

        return $h;
    }

}
