<?php

class LCBOService {
    var $api_key = '';
    const URL = 'https://lcboapi.com/';
    const STORES = 'stores';
    const PRODUCTS = 'products';

    public function __construct(){
        $string = file_get_contents('./config.json');
        $json = json_decode($string, true);
        //print_r($json);
        $this->api_key = $json['key'];
    }

    private function callAPI($additionalFilters) {
        $process = curl_init();
        $additionalHeaders = 'Authorization: Token ' . $this->api_key;
        curl_setopt($process, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $additionalHeaders));
        curl_setopt($process, CURLOPT_HEADER, FALSE);
        curl_setopt($process, CURLOPT_TIMEOUT, 30);
        curl_setopt($process, CURLOPT_POST, 0);
        curl_setopt($process, CURLOPT_URL, LCBOService::URL . $additionalFilters);
        curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);
        $return = curl_exec($process);
        curl_close($process);
        return $return;
    }
    function getStores(){
        return json_decode($this->callAPI(LCBOService::STORES));
        
    }
    function getProducts($where, $orderby){
        $hasCriteria = false;
        if(!is_array($where)){
            $where = [$where];
        }
        if(!is_array($orderby)){
            $orderby = [$orderby];
        }
        if (count($where) > 0) {
            $filters = LCBOService::PRODUCTS.'?where='.implode(',', $where);    
            $hasCriteria = true;
        } else {
            $filters = LCBOService::PRODUCTS;
        }
        if(count($orderby)) {
            if ($hasCriteria) {
                $filters .= '&order='.implode(',', $orderby);
            } else {
                $filters .= '?order='.implode(',', $orderby);
            }
            
        }
        
        $products = $this->callAPI($filters);
        $products = json_decode($products);
        return $products;
    }

}
