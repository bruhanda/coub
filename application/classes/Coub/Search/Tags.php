<?php defined('SYSPATH') or die('No direct script access.');

class Coub_Search_Tags extends Coub_Method{

	protected $method = 'tags/search';
	protected $availableParams = array(Coub::PARAM_TITLE);
	protected $availableOrderBy = array();
	protected $title;

	/**
	 * @return mixed
	 */
	public function getTitle()
	{
		return $this->title;
	}

	/**
	 * @param mixed $title
	 * @return $this
	 */
	public function setTitle($title)
	{
		$this->title = $title;
		return $this;
	}

} // End Coub_Search_Tags
