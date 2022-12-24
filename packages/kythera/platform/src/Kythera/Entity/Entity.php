<?php
namespace Kythera\Entity;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class Entity extends Eloquent
{

    use EntityAttributesTrait;


    const ENTITY_ATTRIBUTE_STRING = 'Kythera\Entity\EntityAttributeString';


    public $original_language;

    /**
     * Cater for update/create rules
     *
     * @param string $id
     * @return array
     */
    public static function getRules($id = null) {
    	$rules = static::$rules;
    	if ($id) {
    		foreach ($rules as $field => $rule) {
    			if (preg_match('#unique#', $rule))
    				$rules[$field] = $rule.",id,{$id}";
    		}
    	}
    	return $rules;
    }

}
