<?php
namespace Kythera\Entity;

use Illuminate\Database\Eloquent\Builder as Builder;
use DB;
use App;

trait EntityAttributesTrait {


    public $attributes_table = 'document_attributes';

    /**
     * Attributes we treat as normal
     * @var unknown
     */
    protected $default_attributes = array('id', 'enabled', 'created_at', 'updated_at');

    /**
     * Keep track of assigned entity attributes on this model
     * @var unknown
     */
    protected $entity_attributes = array();


    /**
     * Add new attribute key/value pair to internal list.
     *
     * @param unknown $key
     * @param unknown $value
     */
    public function setAttribute($key, $value, $entity_attribute = 'Kythera\Entity\EntityAttributeString')
    {
        //keep normal behaviour
        if (in_array($key, $this->default_attributes))
        {
    		// First we will check for the presence of a mutator for the set operation
    		// which simply lets the developers tweak the attribute as it is set on
    		// the model, such as "json_encoding" an listing of data for storage.
    		if ($this->hasSetMutator($key))
    		{
    			$method = 'set'.studly_case($key).'Attribute';

    			return $this->{$method}($value);
    		}

    		// If an attribute is listed as a "date", we'll convert it from a DateTime
    		// instance into a form proper for storage on the database tables using
    		// the connection grammar's date format. We will auto set the values.
    		elseif (in_array($key, $this->getDates()) && $value)
    		{
    			$value = $this->fromDateTime($value);
    		}

    		$this->attributes[$key] = $value;
        }
        else
        {
            if ($this->entity_attributes[$key] instanceof $entity_attribute)
            {
            	//set our entity attribute list key value
                $this->entity_attributes[$key]->setValue($value);
            }
            else
            {
            	//initialize and add to our entity attribute list
                $this->entity_attributes[$key] = new $entity_attribute(null, $key, $value, App::getLocale());
            }
        }
    }


	/**
	 * Get an entity attribute from the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
	    //keep normal behaviour
	    //if (in_array($key, $this->default_attributes))
	    if (!array_key_exists($key, $this->entity_attributes))
	    {
    		$inAttributes = array_key_exists($key, $this->attributes);

    		// If the key references an attribute, we can just go ahead and return the
    		// plain attribute value from the model. This allows every attribute to
    		// be dynamically accessed through the _get method without accessors.
    		if ($inAttributes || $this->hasGetMutator($key))
    		{
    			return $this->getAttributeValue($key);
    		}

    		// If the key already exists in the relationships array, it just means the
    		// relationship has already been loaded, so we'll just return it out of
    		// here because there is no need to query within the relations twice.
    		if (array_key_exists($key, $this->relations))
    		{
    			return $this->relations[$key];
    		}

    		// If the "attribute" exists as a method on the model, we will just assume
    		// it is a relationship and will load and return results from the query
    		// and hydrate the relationship's value on the "relationships" array.
    		$camelKey = camel_case($key);

    		if (method_exists($this, $camelKey))
    		{
    			return $this->getRelationshipFromMethod($key, $camelKey);
    		}
	    } else {

	        //return from our entity attribute list
	        $inAttributes = array_key_exists($key, $this->entity_attributes);

	        if ($inAttributes || $this->hasGetMutator($key))
	        {
	            return $this->getAttributeValue($key);
	        }

	    }
	}


	/**
	 * Get a plain entity attribute (not a relationship).
     * We remove the mutator feature
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttributeValue($key)
	{

	    //keep normal behaviour
	    //if (in_array($key, $this->default_attributes))
        if (!array_key_exists($key, $this->entity_attributes))
	    {
    		$value = $this->getAttributeFromArray($key);

    		// If the attribute has a get mutator, we will call that then return what
    		// it returns as the value, which is useful for transforming values on
    		// retrieval from the model to a form that is more useful for usage.
    		if ($this->hasGetMutator($key))
    		{
    			return $this->mutateAttribute($key, $value);
    		}

    		// If the attribute is listed as a date, we will convert it to a DateTime
    		// instance on retrieval, which makes it quite convenient to work with
    		// date fields without having to create a mutator for each property.
    		elseif (in_array($key, $this->getDates()))
    		{
    			if ($value) return $this->asDateTime($value);
    		}

    		return $value;

	    } else {

	        $value = $this->getEntityAttributeFromArray($key);

	        if ($this->hasGetMutator($key))
	        {
	        	return $this->mutateAttribute($key, $value);
	        }

	        return $value;

	    }

	}


	/**
	 * Get an entity attribute from the $entity_attributes array.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	protected function getEntityAttributeFromArray($key)
	{
		if (array_key_exists($key, $this->entity_attributes))
		{
			return $this->entity_attributes[$key];
		}
	}


	public function fill(array $attributes)
	{
		$totallyGuarded = $this->totallyGuarded();

		foreach ($this->fillableFromArray($attributes) as $key => $value)
		{
			$key = $this->removeTableFromKey($key);

			// The developers may choose to place some attributes in the "fillable"
			// array, which means only those attributes may be set through mass
			// assignment to the model, and all others will just be ignored.
			if ($this->isFillable($key))
			{
				$this->setAttribute($key, $value);
			}
			elseif ($totallyGuarded)
			{
				throw new MassAssignmentException($key);
			}
		}

		//set entity attributes
		foreach ($this->entity_attributes as $key => $attribute)
		{
		    if (array_key_exists($key, $attributes))
		    {
		        $this->setAttribute($key, $attributes[$key]);
		    }
		    else
		    {
		        //set default value
		        $this->setAttribute($key, null);
		    }
		}

		return $this;
	}


	/**
	 * Insert the given attributes and set the ID on the model.
	 * And insert entity attributes
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  array  $attributes
	 * @return void
	 */

	protected function insertAndSetId(Builder $query, $attributes)
	{
	    //normal behaviour
	    $id = $query->insertGetId($attributes, $keyName = $this->getKeyName());

	    $this->setAttribute($keyName, $id);

	    //insert entity attributes
	    foreach ($this->entity_attributes as $attribute)
	    {
	        $attribute->insertAndSetId(DB::table($this->attributes_table), $this);
	    }
	}


	/**
	 * fixme: implement dirty for entity attributes too??
	 */
	protected function performUpdate(Builder $query, array $options = [])
	{
	    //normal
		$dirty = $this->getDirty();

		if (count($dirty) > 0)
		{
			// If the updating event returns false, we will cancel the update operation so
			// developers can hook Validation systems into their models and cancel this
			// operation if the model does not pass validation. Otherwise, we update.
			if ($this->fireModelEvent('updating') === false)
			{
				return false;
			}

			// First we need to create a fresh query instance and touch the creation and
			// update timestamp on the model which are maintained by us for developer
			// convenience. Then we will just continue saving the model instances.
			if ($this->timestamps && array_get($options, 'timestamps', true))
			{
				$this->updateTimestamps();
			}

			// Once we have run the update operation, we will fire the "updated" event for
			// this model instance. This will allow developers to hook into these after
			// models are updated, giving them a chance to do any special processing.
			$dirty = $this->getDirty();

			if (count($dirty) > 0)
			{
				$this->setKeysForSaveQuery($query)->update($dirty);

				$this->fireModelEvent('updated', false);
			}
		}

		//entity attributes
		foreach ($this->entity_attributes as $attribute)
		{
		    $attribute->update(DB::table($this->attributes_table), $this);
		}

		return true;
	}


	/**
	 * Set the array of model attributes. No checking is done.
	 *
	 * @param  array  $attributes
	 * @param  bool   $sync
	 * @return void
	 */
	public function setRawAttributes(array $attributes, $sync = false)
	{
		$this->attributes = $attributes;

		if ($sync) $this->syncOriginal();

		//fetch entity attributes
		//fixme: fetch with model
		$this->setRawEntityAttributes();
	}


	public function setRawEntityAttributes($entity_attribute = 'Kythera\Entity\EntityAttributeString')
	{
		$dirty = false;

		//load request language values
        $attributes = DB::table($this->attributes_table)
		    //->where('document_type_id', $this->document_type_id)
		    ->where('document_entity_id', $this->id)
		    ->where('l', App::getLocale())
		    ->get(array('id', 'key', 'value'));

        //load original language if requested is not available
        if (!$attributes && isset($this->original_language)) {
        	$attributes = DB::table($this->attributes_table)
        		//->where('document_type_id', $this->document_type_id)
	        	->where('document_entity_id', $this->id)
	        	->where('l', $this->original_language)
	        	->get(array('id', 'key', 'value'));

        	$dirty = true;
        }

        foreach ($attributes as $attribute)
        {
        	$this->entity_attributes[$attribute->key] = new $entity_attribute($attribute->id, $attribute->key, $attribute->value, App::getLocale(), $dirty);
        }
	}


	/**
	 * Perform the actual delete query on this model instance.
	 *
	 * @return void
	 */
	protected function performDeleteOnModel()
	{
		/*
		//delete entity attributes per attribute (do we want soft deletes??)
		foreach ($this->entity_attributes as $attribute)
		{
			$attribute->delete(DB::table($this->attributes_table));
		}
		*/

		//OR just delete all attributes
		DB::table($this->attributes_table)
			->where('document_entity_id', $this->id)
			->delete();

		$this->newQuery()->where($this->getKeyName(), $this->getKey())->delete();
	}


	public function __isset($name)
	{
	    if (array_key_exists($name, $this->entity_attributes)) {
            return true;
	    } else {
	        return parent::__isset($name);
	    }
	}


}
