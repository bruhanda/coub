<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

    public function action_index() {
        $coubs = Coub_Search::method('Coubs')
            ->setQuery('Cats')
            ->setOrderBy(Coub::ORDER_BY_NEWEST_POPULAR)
            ->setPage(1)
            ->getResult();
        echo Debug::vars($coubs);
        
    }

}

// End Welcome
