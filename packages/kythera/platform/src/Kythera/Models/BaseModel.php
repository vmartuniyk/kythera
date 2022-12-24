<?php
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;

abstract class BaseModel extends Eloquent {
    
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
