<?php
namespace Kythera\Models;

use Kythera\Entity\Entity;
use App\Models\Translation;
use URL;

/**
 * @author virgilm
 *
 */
class DocumentQuotedText extends DocumentText
{

    protected $entity_attributes = array(
        'uri' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'title' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'content' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
        'source' => array('type'=>Entity::ENTITY_ATTRIBUTE_STRING),
    );

    public function set($data, $controller_id = null)
    {
        //determine controller
        $cats = array_unique(array_filter($data['cats']));

        $this->title			  = $data['title'];
        $this->content			  = $data['content'];
        $this->uri 				  = Translation::slug($data['title']);
        $this->source			  = $data['source'];
        $this->document_type_id   = $cats[0];
        $this->related_village_id = $data['v'];
        if ($result = $this->save()) {
            //update categories
            $this->categories()->sync($cats);

            //make uri unique
            static::uniqueUri($this);
        }
        return $result;
    }

    public static function add($data, $controller_id = null)
    {
        //determine controller
        $cats = array_unique(array_filter($data['cats']));

        if ($result = static::create( array(
            'title'              => $data['title'],
            'content'            => $data['content'],
            'uri'                => Translation::slug($data['title']),
            'source'             => $data['source'],
            'enabled'            => 1,
            'persons_id'         => \Auth::user()->id,
            'document_type_id'   => $cats[0],// $controller_id
            'related_village_id' => $data['v']
        ))) {
            //update categories
            $result->categories()->sync($cats);

            //make uri unique
            static::uniqueUri($result);
        }
        return $result;
    }


}
