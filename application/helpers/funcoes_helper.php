<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function now() {
    return date('Y-m-d H:i:s');
}

function insertTag($tag, $file, &$data) {
	$url = (substr($file, 0, 7) === "http://" || substr($file, 0, 8) === "https://") ? $file : asset_url($tag . '/' . $file);

	array_splice($data[$tag], count($data[$tag]) - 1, 0, $url);
}

function asset_url($url = '') {
    return base_url(ASSETS_DIR . $url);
}

function removeAcentos($x) {
		$replace = array('/Â|À|Á|Ä|Ã|â|ã|à|á|ä/' => "a",'/Ê|È|É|Ë|ê|è|é|ë/' => "e",'/Î|Í|Ì|Ï|î|í|ì|ï/' => "i",'/Ô|Õ|Ò|Ó|Ö|ô|õ|ò|ó|ö/' => "o",'/Û|Ù|Ú|Ü|û|ú|ù|ü/' => "u",'/ç|Ç/' => "c");
        return preg_replace(array_keys($replace),array_values($replace),$x);
}

function convertData($data = '', $formatInput = 'html' ,$formatOutput = 'mysql') {
	if($data)
	{
		if($formatInput == 'html' && $formatOutput == 'mysql')
		{
			$day = substr($data,0,2);
			$month = substr($data,3,2);
			$year = substr($data,6,4);
			$time = substr($data,11,8);
			$data = $year . '-' . $month . '-' . $day . ' ' . $time;
		}
		else if($formatInput == 'mysql' && $formatOutput == 'html')
		{
			$year = substr($data,0,4);
			$month = substr($data,5,2);
			$day = substr($data,8,2);
			$time = substr($data,11,8);
			$data = $day . '/' . $month . '/' . $year . ' ' . $time;
		}
	}

	return $data;
}

function current_full_url() {
    $CI =& get_instance();

    $url = $CI->config->site_url($CI->uri->uri_string());
    return $_SERVER['QUERY_STRING'] ? $url.'?'.$_SERVER['QUERY_STRING'] : $url;
}

function tokenGenerate($length = 8) {
	$characters = '23456789BbCcDdFfGgHhJjKkMmNnPpQqRrSsTtVvWwXxYyZz';
	$count = mb_strlen($characters);

	for ($i = 0, $token = ''; $i < $length; $i++)
	{
		$index = rand(0, $count - 1);
		$token .= mb_substr($characters, $index, 1);
	}
	return $token;
}

function get_value($obj, $value){
	return isset($obj->$value) ? $obj->$value : "";
}