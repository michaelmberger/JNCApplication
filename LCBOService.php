<?php

class LCBOService {

    const API_KEY = 'MDowMGVhNmFhMC1mOGJkLTExZTgtOWU0Ny02N2JlYTY4YjE3MzA6d0NtRWZqR3daVlQ2d2U2cEl1RlZzQXNhQ1RNd1hTcFJ3d1Qw';
    const URL = 'https://lcboapi.com/';
    const STORES = 'stores';
    const PRODUCTS = 'products';

    

    private function callAPI($additionalFilters) {
        $process = curl_init();
        $additionalHeaders = 'Authorization: Token ' . LCBOService::API_KEY;
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
