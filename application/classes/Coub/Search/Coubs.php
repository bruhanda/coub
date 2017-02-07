<?php defined('SYSPATH') or die('No direct script access.');

class Coub_Search_Coubs extends Coub_Method{

	protected $method = 'search/coubs';
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
	 * @param $orderBy
	 * @return Coub_Search_Coubs $this
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
	 * @return Coub_Search_Coubs $this
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
	 * @return Coub_Search_Coubs $this
	 */
	public function setPage($page)
	{
		$this->page = $page;
		return $this;
	}
        


} // End Coub
