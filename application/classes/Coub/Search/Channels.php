<?php defined('SYSPATH') or die('No direct script access.');

class Coub_Search_Channel extends Coub_Method{

	protected $method = 'search/channels';
	protected $orderBy;
	protected $query;
	protected $page = 1;

	/**
	 * @return mixed
	 */
	public function getOrderBy()
	{
		return $this->orderBy;
	}

	/**
	 * @param mixed $orderBy
	 * @return $this
	 */
	public function setOrderBy($orderBy)
	{
		$this->orderBy = $orderBy;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getQuery()
	{
		return $this->query;
	}

	/**
	 * @param mixed $query
	 * @return $this
	 */
	public function setQuery($query)
	{
		$this->query = $query;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getPage()
	{
		return $this->page;
	}

	/**
	 * @param int $page
	 * @return $this
	 */
	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}


} // End Coub
