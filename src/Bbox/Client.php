<?php

namespace Bstar\Bbox;
class Client extends Base
{
    public function __construct($client_id,$secret)
    {
        parent::__construct($client_id,$secret);
    }
    /**
    * Get list file from BBOX
    * @param {String} [$node] (node's id)
    * @param {String} [$page] (current page)
    * @return {Array} data
    **/
    public function getList($node = '',$page = 1){
        $url = $this->URL_LIST_FILE;
        if(!empty($node)){
            $url .= 'nodes/'.$node;
        }
        $list = $this->makeCurlPost($url,['page'=>$page],false);
        echo "<pre>";
        var_dump($list);
    }
}