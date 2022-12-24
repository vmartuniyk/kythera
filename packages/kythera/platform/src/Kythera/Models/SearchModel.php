<?php
/**
 * @author virgilm
 *
 */
namespace Kythera\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class SearchModel extends Model
{

	protected $table = 'document_entities';

	public static function search(array $query)
	{
		$select = static::query()
			->select('document_entities.updated_at', 'document_attributes.*')
			->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id');
		$select
			->where('document_entities.document_type_id', '!=', 23)
			->where('document_entities.enabled', '=', 1)
			->where('document_attributes.l', '=', \App::getLocale())
			->whereIn('document_attributes.key', array('title', 'content'));
		foreach($query as $q) {
			$select
				->where('document_attributes.value', 'LIKE', '%'.$q.'%');
		}
		$select
			->orderByRaw('document_attributes.key DESC');
		$select
			->orderByRaw('document_entities.updated_at DESC');

		return $select->paginate(10);
	}

	public static function history(array $query)
	{
		//keep history
		$forgetWildcards = true;
		$hasWildcards = false;

		$select = SearchModel::query()
			->select('document_attributes.document_entity_id')
			->leftJoin('document_attributes', 'document_entities.id', '=', 'document_attributes.document_entity_id')
			->where('document_entities.document_type_id', '!=', 23)
			->where('document_entities.enabled', '=', 1)
			->where('document_attributes.l', '=', \App::getLocale())
			->whereIn('document_attributes.key', array('title', 'content'));
		foreach($query as $q) {
			if (str_contains($q, '?') || str_contains($q, '*')) {
				$hasWildcards = true;
			}
			$select
		 		->where('document_attributes.value', 'LIKE', '%'.$q.'%');
		}
		$select
			->orderByRaw('document_entities.updated_at DESC')
			->get();

		$result = $select->lists('document_entity_id');
		//if (!Input::has('r')) {
		if (count($result))
			DB::statement('REPLACE INTO searches SET query = ?, entities = ?', array(implode(' ', array_unique($query)), json_encode($result, true)));
		//}

		return $result;
	}

	public static function related(array $hits, $query, $limit = 10)
	{
		$result = array();
		//if (!Input::has('r') && ($related = DB::table('searches')->where('query', '!=', $query)->get())) {
		if (($related = DB::table('searches')->where('query', '!=', $query)->limit($limit)->get())) {
			foreach ($related as $key) {
				if ($common = array_intersect($hits, json_decode($key->entities, true))) {
					$k = array_unique(explode(' ', strtolower($query) .' '. strtolower($key->query)));
					//echo __FILE__.__LINE__.'<pre>$k='.htmlentities(print_r($k,1)).'</pre>';
					$k = implode(' ', $k);
					if (stripos($query, $k) === false) {
						$result[$k] = $common;
					}
				}
			}
		}
		//echo __FILE__.__LINE__.'<pre>$result='.htmlentities(print_r($result,1)).'</pre>';
		return $result;
	}
}


