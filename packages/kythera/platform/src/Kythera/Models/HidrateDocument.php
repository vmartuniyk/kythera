<?php
namespace Kythera\Models;

use Kythera\Models\DocumentEntity;
use Kythera\Support\SearchCollection;
use Elasticquent\ElasticquentTrait;
//use Elasticquent\ElasticquentResultCollection;

class HidrateDocument extends DocumentEntity
{
	use ElasticquentTrait;

	const SEARCH_OR  = 'or';
	const SEARCH_AND = 'and';

	/**
	 * ES index name
	 *
	 * @var string
	 */
	const ES_INDEX_NAME = 'kfn';

	/**
	 * ES type name
	 *
	 * @var string
	 */
	const ES_TYPE_NAME = 'document';

	/**
	 * The accessors to append to the model's array form.
	 *
	 * @var array
	 */
	protected $appends = array('url', 'cats', 'village', 'names');

	/**
	 * The relations to eager load on every query.
	 *
	 * @var array
	*/
	//protected $with = array('user', 'categories');
	protected $with = array('user');

	/**
	 * ES field mappings
	 *
	 * @var array
	*/
	protected $mappingProperties = array(
		'id' => array(
			'type' => 'long',
		),
		'document_type_id' => array(
			'type' => 'long',
		),
		'persons_id' => array(
			'type' => 'long',
		),
		'enabled' => array(
			'type' => 'integer',
		),
		'top_article' => array(
			'type' => 'boolean',
		),
		'created_at' => array(
			//"2003-05-21 16:03:01",
			'type' => 'date',
			'format' => 'yyyy-MM-DD HH:mm:ss'
		),
		'updated_at' => array(
			'type' => 'date',
			'format' => 'yyyy-MM-DD HH:mm:ss'
		),
		'uri' => array(
			'type' => 'multi_field',
			'fields' => array(
				'uri' => array(
					'type' => 'string'
				),
				'uri.original' => array(
					'type' => 'string',
					'index' => 'not_analyzed',
				),
			)
		),
		'title' => array(
			'type' => 'multi_field',
			'fields' => array(
				'title' => array(
					'type' =>'string',
					'analyzer' => 'title_analyzer'
				),
				'title.original' => array(
					'type' => 'string',
					'index' => 'not_analyzed',
				),
			)
		),
		'content' => array(
			'type' => 'string',
			//'analyzer' => 'greek',
		)
	);

	protected $analyzer = array(
		'analyzer' => array(
			'title_analyzer' => array(
				'type'      => 'custom',
				'tokenizer' => 'standard',
				'filter'    => array(
					'lowercase',
					'title_filter'
				),
			),
		),
		'filter' =>  array(
			'title_filter' => array(
				'type' => 'nGram',
				'min_gram' => 2,
				'max_gram' => 10,
				'token_chars' => array(
					'letter',
					'digit',
					'symbol'
				),
			),
		),
	);

	public function getAnalyzer()
	{
		return $this->analyzer;
	}

	/**
	 * Set custom index name
	 *
	 * @return string
	*/
	public function getIndexName()
	{
		return HidrateDocument::ES_INDEX_NAME;
	}

	/**
	 * Set custom type name
	 *
	 * @return string
	 */
	public function getTypeName()
	{
		return HidrateDocument::ES_TYPE_NAME;
	}

	/**
	 * Override convertion of the model instance to an array.
	 *
	 * @return array
	 */
	public function toArray()
	{
		$attributes = $this->attributesToArray();

		$related_attributes = $this->relationsToArray();

		$entity_attributes = $this->entityAttributesToArray();

		$result = array_merge($attributes, $related_attributes, $entity_attributes);

		return $result;
	}

	/**
	 * Convert entity attributes to array
	 *
	 * @return array
	 */
	protected function entityAttributesToArray()
	{
		$result = array();

		foreach ($this->entity_attributes as $key=>$attribute) {
			//strip_tags($value, '<br><br/>');
			$value = strip_tags($attribute->getValue());
			$value = (preg_replace('#\[\[picture:"([^"]+)" ID:([0-9]+)]]#', '', $value));
			$result[$key] = $value;
		}

		return $result;
	}

	/**
	 * Get full url to entity
	 *
	 * @return string
	 */
	public function getUrlAttribute()
	{
		return \Route::getItemUrl($this);
	}

	/**
	 * Get related categories a simple array for indexing/filtering
	 *
	 * @return array
	 */
	public function getCatsAttribute()
	{
		$result = array();
		foreach ($this->categories as $cat) {
			$result[] = (int)$cat->id;
		}
		return $result;
	}

	/**
	 * Get related villages and their compound names.
	 *
	 * @return array
	 *
	 * "village": [
                        "Agia Anastasia",
                        "Αγία Αναστασία"
                    ]
	 */
	public function getVillageAttribute()
	{
		$result = array();

		if ($village = \DB::table('villages')->where('id', $this->related_village_id)->pluck('village_name')) {
			$result[] = $village;

			//get compounds
			if ($compounds = \DB::table('village_compounds')
					->select('village_name')
					->join('villages', 'id', '=', 'village2_id')
					->where('village1_id', $this->related_village_id)
					->get()) {
						foreach ($compounds as $compound) {
							$result[] = $compound->village_name;
						}
					}
		}

		return $result;
	}

	/**
	 * Get user's compound names.
	 *
	 * @return array
	 *
       "names": [
       		"Prineas",
            "Preneas",
            "Πρινέας"
                    ]
	 */
	public function getNamesAttribute()
	{
		$result = array();

		if ($names = \DB::table('name_compounds')->select('name')->join('namebox_entries', 'namebox_entries.id', '=', 'name2_id')->where('name1_id', $this->persons_id)->get()) {
			$result[] = $this->user->lastname;
			foreach ($names as $name) {
				$result[] = $name->name;
			}
		}

		return $result;
	}

	/**
	 * Get user details
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function user()
	{
		return $this->belongsTo('\Kythera\Models\HidrateUser', 'persons_id');
	}

	/*
	 * Get categories to which entity belongs
	 *
	 * @returns Illuminate\Database\Eloquent\Relations\BelongsToMany
	 */
	public function categories()
	{
		return $this->belongsToMany('\Kythera\Models\HidrateCategory', 'document_categories', 'document_id', 'category_id');
	}


	/**
	 * OVERRIDEN Create Index
	 *
	 * @param int $shards
	 * @param int $replicas
	 * @return array
	 */
	public static function createIndex($shards = null, $replicas = null, $analysis = null)
	{
		$instance = new static;

		$client = $instance->getElasticSearchClient();

		$index = array(
				'index'     => $instance->getIndexName()
		);

		if ($shards) {
			$index['body']['settings']['number_of_shards'] = $shards;
		}

		if ($replicas) {
			$index['body']['settings']['number_of_replicas'] = $replicas;
		}

		if ($analysis) {
			$index['body']['settings']['analysis'] = $analysis;
		}

		return $client->indices()->create($index);
	}

	/**
	 * OVERRIDEN Search
	 *
	 * Simple search using a match _all query
	 *
	 * @param   string $term
	 * @return  ResultCollection
	 */
	public static function search($term = null, $operator = HidrateDocument::SEARCH_AND, $filters = array(), $sort = array('field'=>'_score', 'direction'=>'asc'))
	{
		//https://www.elastic.co/guide/en/elasticsearch/reference/current/_executing_searches.html

		$instance = new static;

		//options
		$params = $instance->getBasicEsParams(true, false, false, 300, null);
		//$params['body']['explain'] = true;

		//query
		$params['body']['query']['bool']['must'][]['match']['_all'] = array('query' => $term, 'operator' => $operator, 'fuzziness' => 0);

		foreach ($filters as $key=>$value) {
			switch($key) {
				case 'c':
					if ($value) $params['body']['query']['bool']['must'][]['match']['cats'] = (int)$value;
				break;
				case 'a':
					if ($value) $params['body']['query']['bool']['must'][]['match']['user.id'] = (int)$value;
				break;
			}
		}

		//highlight
		$params['body']['highlight']['fragment_size'] = 150;
		$params['body']['highlight']['fields']['uri']['number_of_fragments'] = 0;
		$params['body']['highlight']['fields']['title']['number_of_fragments'] = 0;
		$params['body']['highlight']['fields']['content']['fragment_size'] = 300;
		$params['body']['highlight']['fields']['content']['number_of_fragments'] = 1;
		$params['body']['highlight']['fields']['firstname']['number_of_fragments'] = 0;
		$params['body']['highlight']['fields']['lastname']['number_of_fragments'] = 0;

		//sorting
		switch($sort['field']) {
			case 'lastname':
			case 'title.original':
			case 'created_at':
				$params['body']['sort'][$sort['field']]['order'] = $sort['direction'];
			break;
			default:
				//_score
		}
		//echo __FILE__.__LINE__.'<pre>$params='.htmlentities(print_r($params,1)).'</pre>';
		//$j=json_encode($params);die($j);

		$result = $instance->getElasticSearchClient()->search($params);

		return new SearchCollection($result);
	}


	/**
	 * Suggest
	 *
	 * Simple search using a match phrase prefix
	 *
	 * @param   string $term
	 * @return  ResultCollection
	 */
	public static function suggest($term = null, $sort = '_score')
	{
		$instance = new static;

		$params = $instance->getBasicEsParams(true, false, false, 7, null);
		//$params['body']['explain'] = true;


		$params['body']['query']['match']['title'] = $term;
/*
		$params['body']['query']['bool']['must'][] = array('match'=>array('title'=>$term));
		$params['body']['query']['bool']['must'][] = array('match'=>array('content'=>$term));
*/

		$params['body']['highlight']['fragment_size'] = 150;
		$params['body']['highlight']['fields']['title']['number_of_fragments'] = 0;
		$params['body']['highlight']['fields']['content']['number_of_fragments'] = 1;
		$params['body']['highlight']['fields']['user.lastname']['number_of_fragments'] = 0;
		//$params['body']['highlight']['fields']['names']['number_of_fragments'] = 0;


		$params['body']['min_score'] = 1.5;
		$params['body']['_source'] = array('id', 'url', 'title', 'document_type_id', 'updated_at');

		$result = $instance->getElasticSearchClient()->search($params);

		return new SearchCollection($result);
	}


	/**
	 * Depricated.
	 *
	 * @param string $query
	 * @param string $aggregations
	 * @param string $sourceFields
	 * @param string $limit
	 * @param string $offset
	 * @param string $sort
	 * @return \Kythera\Models\SearchCollection
	 */
	public static function searchByQuery($query = null, $aggregations = null, $sourceFields = null, $limit = null, $offset = null, $sort = null)
	{
		/*
		//add suggesters based on compound names

		$instance = new static;

		$params = $instance->getBasicEsParams(true, true, true, $limit, $offset);

		if ($sourceFields) {
			$params['body']['_source']['include'] = $sourceFields;
		}

		if ($query) {
			$params['body']['query'] = $query;
		}

		if ($aggregations) {
			$params['body']['aggs'] = $aggregations;
		}

		if ($sort) {
			$params['body']['sort'] = $sort;
		}
		//$params['body']['_source'] = array('id');


		$params['body']['highlight']['fragment_size'] = 300;
		$params['body']['highlight']['fields']['title']['number_of_fragments'] = 0;
		$params['body']['highlight']['fields']['content']['number_of_fragments'] = 1;
		$params['body']['highlight']['fields']['user.lastname']['number_of_fragments'] = 0;

		$params['body']['explain'] = true;

		$result = $instance->getElasticSearchClient()->search($params);

		return new SearchCollection($result);
		*/
	}


}
