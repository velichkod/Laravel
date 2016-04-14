<?php
/**
 * @param $string
 * @return string
 */
function encrypt($string){
    return urlencode(Crypt::encrypt($string));
}

/**
 * @param $encrypted
 * @return string
 */
function decrypt($encrypted){
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
function sysUrl($url){
    return url('webpanel/'.$url);
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