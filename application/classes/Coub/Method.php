<?php

defined('SYSPATH') or die('No direct script access.');

abstract class Coub_Method {

    protected $method = 'search';
    protected $availableParams = array(
        Coub::PARAM_QUERY,
        Coub::PARAM_ORDER_BY,
        Coub::PARAM_PAGE
    );
    protected $availableOrderBy = array(
        Coub::ORDER_BY_LIKES_COUNT,
        Coub::ORDER_BY_NEWEST,
        Coub::ORDER_BY_NEWEST_POPULAR,
        Coub::ORDER_BY_OLDES,
        Coub::ORDER_BY_VIEWS_COUNT
    );

    public function getResult() {
        $url='http://coub.com/api/v2/'.$this->method;
        $params=array();
        
        if(isset($this->query) && $this->query!==NULL){
            $params['q']=  $this->query;
        }
        if(isset($this->title) && $this->title!==NULL){
            $params['title']=  $this->title;
        }
        if(isset($this->orderBy) && $this->orderBy!==NULL){
            $params['order_by']=  $this->orderBy;
        }
        if(isset($this->page) && $this->page!==NULL){
            $params['page']=  $this->page;
        }
        
        $res=  Request::factory($url)->query($params)->execute();
        return json_decode($res->body());
              
    }

}

// End Coub
