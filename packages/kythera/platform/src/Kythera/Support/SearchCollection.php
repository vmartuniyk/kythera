<?php
namespace Kythera\Support;

use Elasticquent\ElasticquentResultCollection;

class SearchCollection extends \Illuminate\Database\Eloquent\Collection
{
	protected $took;
	protected $timed_out;
	protected $shards;
	protected $hits;
	protected $aggregations = null;

	/**
	 * _construct
	 *
	 * @param   $results elasticsearch results
	 * @param $instance
	 * @return \SearchCollection
	 */
	public function __construct($results)
	{
		// Take our result data and map it
		// to some class properties.
		$this->took         = $results['took'];
		$this->timed_out    = $results['timed_out'];
		$this->shards       = $results['_shards'];
		$this->hits         = $results['hits'];
		$this->aggregations = isset($results['aggregations']) ? $results['aggregations'] : array();

		//$this->items = $this->hitsToIDs();
		$this->items = $this->hitsToItems();
	}

	private function hitsToItems()
	{
		$items = array();

		foreach ($this->hits['hits'] as $hit) {

			$items[$hit['_id']] = array(
					'score' => $hit['_score'],
					'source' => $hit['_source'],
					'highlight' => @$hit['highlight']
			);

		}

		return $items;
	}

	private function hitsToIDs()
	{
		$items = array();

		foreach ($this->hits['hits'] as $hit) {

			$items[$hit['_id']] = $hit['_score'];

		}

		return $items;
	}

	/**
	 * Total Hits
	 *
	 * @return int
	 */
	public function totalHits()
	{
		return $this->hits['total'];
	}

	/**
	 * Max Score
	 *
	 * @return float
	 */
	public function maxScore()
	{
		return $this->hits['max_score'];
	}

	/**
	 * Took
	 *
	 * @return string
	 */
	public function took()
	{
		return $this->took;
	}

	/**
	 * Timed Out
	 *
	 * @return bool
	 */
	public function timedOut()
	{
		return (bool)$this->timed_out;
	}

	/**
	 * Get Hits
	 *
	 * Get the raw hits array from
	 * Elasticsearch results.
	 *
	 * @return array
	 */
	public function getHits()
	{
		return $this->hits;
	}

	/**
	 * Get aggregations
	 *
	 * Get the raw hits array from
	 * Elasticsearch results.
	 *
	 * @return array
	 */
	public function getAggregations()
	{
		return $this->aggregations;
	}

}