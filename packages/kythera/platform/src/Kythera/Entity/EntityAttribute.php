<?php
namespace Kythera\Entity;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class EntityAttributeException extends \Exception {};

class EntityAttribute
{

    protected $id;


    protected $key;


    protected $value;


    protected $locale;


    protected $dirty = false;


    public function __construct($id, $key, $value, $locale = 'en', $dirty = false)
    {
        $this->id = $id;
        $this->key = $key;
        $this->value = $value ? $value : '';
        $this->locale = $locale;
        if ($dirty)
        $this->dirty = $dirty;
    }


    public function insertAndSetId(Builder $query, Entity $entity)
    {
        return $this->id = $query->insertGetId(array(
            'document_type_id' => $entity->document_type_id,
            'document_entity_id' => $entity->id,
            'l' => $this->locale,
            'key' => $this->key,
            'value' => $this->value
        ));
    }


    public function select(Builder $query, Entity $entity)
    {
        if ($values = $query->select('id', 'value')
                        //->where('document_type_id', '=', $entity->document_type_id)
                        ->where('document_entity_id', '=', $entity->id)
                        ->where('l', '=', $this->locale)
                        ->where('key', '=', $this->key)
                        ->first())
        {
            $this->id    = $values->id;
            $this->value = $values->value;
        }
    }


    public function update(Builder $query, Entity $entity, $locale = 'en')
    {
        if ($this->dirty)
        {
        	/*
        	$q = sprintf("REPLACE INTO %s SET document_type_id=%d, document_entity_id=%d, l='%s', `key`='%s', `value`='%s';",
        			$entity->attributes_table,
        			$entity->document_type_id,
        			$entity->id,
        			$this->locale,
        			$this->key,
        			$this->getValue()
        	);
        	$this->dirty != DB::statement($q);
        	*/

          // PageEntity (pages_attributes table) doesn't have document_type_id column
          if (isset($entity->document_type_id) && $entity->document_type_id == 10000) {

              $q = sprintf("REPLACE INTO %s (document_entity_id, l, `key`, `value`) VALUES (?, ?, ?, ?);", $entity->attributes_table);

              $this->dirty != DB::statement($q, array(
            			$entity->id,
            			$this->locale,
            			$this->key,
            			$this->getValue()
            	));

          } else {

              $q = sprintf("REPLACE INTO %s (document_type_id, document_entity_id, l, `key`, `value`) VALUES (?, ?, ?, ?, ?);", $entity->attributes_table);

              $this->dirty != DB::statement($q, array(
            			$entity->document_type_id,
            			$entity->id,
            			$this->locale,
            			$this->key,
            			$this->getValue()
            	));
          }


        	/*
            if ($this->id)
            {
                $this->dirty =! $query->where('id', $this->id)
                                      ->where('l', '=', $this->locale)
                                      ->update(array('value' => $this->value));
            } else

                throw new EntityAttributeException('Cannot update entity attribute: missing id.');
            */

        }
    }


    /**
     * Delete attribute
     * @param Builder $query
     */
    public function delete(Builder $query)
    {
    	$query
    		->where('id', $this->id)
    		->delete();
    }


    public function setValue($value)
    {
        if (!$this->dirty)
        $this->dirty = $this->value != $value;
        $this->value = $value;
    }


    public function getValue($key = 'value')
    {
    	return $this->{$key};
    }


    public function __toString()
    {
        return $this->value;
    }


}
