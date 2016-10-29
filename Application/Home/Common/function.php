<?php

function get_recharge($type) {
    switch ($type){
        case 1  : return    '余额';     break;
        case 2  : return    '微信';     break;
        case 3  : return    '支付宝';     break;
        case 4  : return    '网银';     break;
        case 5  : return    '云支付';     break;
        case 6  : return    'PAYPAL';     break;
        default : return    false;      break;
    }
}
?>