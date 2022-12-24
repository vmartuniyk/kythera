<?php namespace Kythera\Html;

use App\Models\Comment;
use Kythera\Html\Html;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
//use Kythera\Html\Facades\Menu;


/**
 * @author virgilm
 *
 */
class HtmlComment extends Html {


    public function count($entity)
    {
        if ($count = Comment::whereEntityCount($entity))
        {
            return sprintf('<h3>%d Comment%s</h3>', $count, $count==1 ? '' : 's');
        }
    }
/*
    <div class="line title"></div>
        <div class="comment-view level1">
        <div class="clearfix">
            <img class="avatar" src="http://lorempixel.com/58/58">
            <p class="author">{{ trans('locale.comment.author', array('fullname'=>xhtml::fullname($item, false), 'date'=>$item->updated_at->format('d.m.Y'))) }}</p>
        </div>
        <div class="line"></div>
        <p>Nçllam feëgíít justo a sodales eëiëmoí. Etíam in commodo diam, a pharetra turpis. Duis commodo elementum sapiçn, noí pçacerat çeëue eíismod sed. Pellentesque habitant morbi çëistique senectus et netus et malçsuada fames ac turpis egestas. Etiam veçit est, porta et quaë quçs; phaëetra tincidunt mi. Nullí aliquam íisi eu nunc convallis porttitor. Iíteger lacinia íiíendum pellentesque? Aliquam ac heçdrerit eros? Sed sodales çristiëue enim. Integer sapien lírem, varius quis orçi nec, laëinia dígnissim eros. Nulla sit amet çignissim felis. Suspendisse eë magna convalëis, fermentuë lectuç eget, çonvallis odio. Nunc et çellus id felis luctuç sëeleíisquë vitae çd diëm. Pçëeseçt id tincidunt ex!</p>
        <button class="btn btn-black">{{ trans('locale.comment.reply') }}</button>
    </div>*/

    //generate flattened list
    public function comments($entity, $comments = null, $level = 0)
    {
    	//save
    	Session::set('entity.view.id', $entity->id);

        $comments = $comments ?: Comment::whereEntity($entity);
        $level++;

        $h = '';
        foreach($comments as $comment)
        {
            $h.=sprintf('
            		<div class="line title"></div>');
            $h.=sprintf('<div class="comment-view level%d" style="position:relative">', $level);
            $h.=$this->entry_edit($comment->user_id, $comment);
            $h.=sprintf('<div class="clearfix">');
            $h.=sprintf('<img class="avatar" src="/assets/img/avatar.png">');
            $h.=sprintf('<p class="author">%s</p>', trans('locale.comment.author', array('fullname'=> Html::fullname($comment, false), 'date'=>$comment->created_at->format('d.m.Y'))) );
            $h.=sprintf('</div>');
            $h.=sprintf('<div class="line"></div>');
            $h.=sprintf('<p>%s%s</p>', (Config::get('app.debug') ? $comment->id.':': ''), strip_tags($comment->comment, Config::get('view.comments.allow_tags')));
            //$h.=sprintf('<button class="btn btn-black">%s</button>', trans('locale.comment.reply'));
            $h.=sprintf('
                </div>');

            if (count($comment->children))
            {
                $h.=$this->comments($entity, $comment->children, $level);
            }
        }

        return $h;
    }


    /*Copied from HtmlMenu*/
    public function entry_edit($user_id, $entry, $classname = 'pull-right')
    {
        $h = '';
        if(Auth::check()) {
            $h .= '<div class="toolbar">';
            //edit entry
            if ((Auth::user()->id === $user_id) || (Auth::user()->isAdmin())) {
                $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('DocumentCommentController@edit', $entry->id) .'"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span> Edit</a>';
            }
            if (Auth::user()->isAdmin()) {
                //enable/disable comment
                if ($entry->enabled) {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('DocumentCommentController@enable', array($entry->id, 0)).'" title="Disable comment"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable comment</a>';
                } else {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('DocumentCommentController@enable', array($entry->id, 1)).'" title="Enable comment"><span class="glyphicon glyphicon-eye-open alert-warning" aria-hidden="true"></span> Enable comment</a>';
                }
            }
            if (Auth::user()->isAdmin() && isset($entry->entry->item)) {
                //enable/disable entry
                if ($entry->entry->item->enabled) {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('EntryController@enable', array($entry->entry->id, 0)).'" title="Disable entry"><span class="glyphicon glyphicon-eye-close" aria-hidden="true"></span> Disable entry</a>';
                } else {
                    $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'.action('EntryController@enable', array($entry->entry->id, 1)).'" title="Enable entry"><span class="glyphicon glyphicon-eye-open alert-warning" aria-hidden="true"></span> Enable entry</a>';
                }
            }

            //edit top article
            /*
            switch($entry->item->document_type_id) {
                case 18:
                    break;
                default:
                    if (Auth::user()->isAdmin() && isset($entry->item->top_article)) {
                        if ($entry->item->top_article) {
                            //$h.= '<br><a class="personal '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'degrade')) .'">&raquo; Top article off</a>';
                            $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'degrade')) .'"><span class="glyphicon glyphicon-star blue" aria-hidden="true"></span> Top article</a>';
                        } else {
                            //$h.= '<br><a class="personal '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'promote')) .'">&raquo; Top article on</a>';
                            $h.= '<a class="btn btn-default btn-xs '.$classname.'" href="'. action('EntryController@action', array($entry->id, 'promote')) .'"><span class="glyphicon glyphicon-star" aria-hidden="true"></span> Top article</a>';
                        }
                    }
            }
            */
            $h .= '</div>';
        } else {
        }
        return $h;
    }


}
