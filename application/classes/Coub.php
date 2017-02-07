<?php defined('SYSPATH') or die('No direct script access.');

class Coub {

	const SEARCH_METHOD_DEFAULT = 'default';
	const SEARCH_METHOD_CHANNELS = 'channels';
	const SEARCH_METHOD_COUBS = 'coubs';
	const SEARCH_METHOD_TAGS = 'tags';

	const ORDER_BY_LIKES_COUNT = 'likes_count';
	const ORDER_BY_VIEWS_COUNT = 'views_count';
	const ORDER_BY_NEWEST = 'newest';
	const ORDER_BY_OLDES = 'oldest';
	const ORDER_BY_NEWEST_POPULAR = 'newest_popular';
	
	const PARAM_QUERY = 'q';
	const PARAM_ORDER_BY = 'order_by';
	const PARAM_PAGE = 'page';
	const PARAM_TITLE = 'title';

	public static function factory($method) {
		$classname = sprintf('Coub_%s', ucfirst($method));              
		if(!class_exists($classname)) {
			$message = sprintf('Class with name %s not loaded!', $classname);
			Kohana::$log->add(Log::ERROR, $message);
			throw new Exception($message);
		}
		return new sprintf('Coub_%s', ucfirst($method));
	}

} // End Coub
