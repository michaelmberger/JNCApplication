<?php

include_once 'LCBOService.php';
$orderby = $_GET['orderby'];
$tags = $_GET['tags'];
if ($orderby == 'discountamount') {
    $orderby = 'limited_time_offer_savings_in_cents';
}else if ($orderby == 'currentprice') {
    $orderby = 'price_in_cents';
} else {
    $orderby ='';
}
$service = new LCBOService();
$returnval = $service->getProducts('has_limited_time_offer', $orderby);
$returnedString = '';
if(isset($tags) && strlen($tags)) {
    $tags = explode(',', $tags);
} else {
    $tags = [];
}
foreach ($returnval->result as $value) {
    if (count($tags)) {
        $productTags = explode(' ', $value->tags);
        if (count(array_intersect($tags, $productTags))) {
            $returnedString .= '<tr><td>' . $value->name . '</td>';
            $returnedString .= '<td>' . $value->regular_price_in_cents . '</td>';
            $returnedString .= '<td>' . $value->price_in_cents . '</td></tr>';
        }
    } else {
        $returnedString .= '<tr><td>' . $value->name . '</td>';
        $returnedString .= '<td>' . $value->regular_price_in_cents . '</td>';
        $returnedString .= '<td>' . $value->price_in_cents . '</td></tr>';
    }
}
echo $returnedString;
?>

