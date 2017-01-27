<?php
/**
 * @param $string
 * @return string
 */
function encryptIt($string){
    return urlencode(Crypt::encrypt($string));
}

/**
 * @param $encrypted
 * @return string
 */
function decryptIt($encrypted){
    return Crypt::decrypt(urldecode($encrypted));
}

/**
 * @param $route
 * @param array $params
 * @return string
 */
function sysRoute($route, $params=array()){
    return route('webpanel.'.$route, $params);
}

/**
 * @param $url
 * @return string
 */
function sysUrl($url, $params = array()){
    return url('webpanel/'.$url, $params);
}

/**
 * @param $view
 * @param array $data
 * @return \Illuminate\View\View
 */
function sysView($view, $data = array()){
    return view('webpanel.'.$view, $data);
}


function frontView($view, $data = array()){
    return view('frontend.'.$view, $data);
}