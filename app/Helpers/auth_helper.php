<?php 

function checkToken($token){
    $apiKey = getenv('API_KEY');

    if($apiKey == $token){
        return true;
    } else {
        return false;
    }
}