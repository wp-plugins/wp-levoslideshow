<?php
/**
* @Copyright Copyright (C) 2011 - xml/swf
* @license GNU/GPL http://www.gnu.org/copyleft/gpl.html
**/ 

/// function for finding defining image exist or not
if (!function_exists('getResCode')) {
function getResCode($url)
	{
	    $ch = curl_init(trim($url));
		curl_setopt($ch, CURLOPT_NOBODY, true);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_exec($ch);
		return $info = curl_getinfo($ch);
		curl_close($ch);

    }
}

if (!function_exists('_isHavingAccess')) {

function _isHavingAccess() 
	{


		if  (in_array  ('curl', get_loaded_extensions())) {

			$curl_enabled = 2;
			return $curl_enabled;
		}
		else{
			
			$allow_url_open = ini_get('allow_url_fopen');

			return $allow_url_open;

		}
	}
}

if (!function_exists('isImage')) {
function isImage($url)
  {
     $params = array('http' => array(
                  'method' => 'HEAD'
               ));
     $ctx = stream_context_create($params);
     $fp = @fopen($url, 'rb', false, $ctx);
     if (!$fp) 
        return false;  // Problem with url

    $meta = stream_get_meta_data($fp);
    if ($meta === false)
    {
        fclose($fp);
        return false;  // Problem reading data from url
    }

    $wrapper_data = $meta["wrapper_data"];
	    if(is_array($wrapper_data)){
        foreach($wrapper_data as $key=>$value){
          if (substr($wrapper_data[$key], 0, 19) == "Content-Type: image") // strlen("Content-Type: image") == 19 
			  {
				fclose($fp);
				return 1;
			  }		  
 }
    }

    fclose($fp);
    return false;
  }
}

if (!function_exists('http_file_exists')) {
	function http_file_exists($url, $followRedirects = true)
	{
			$url_parsed = parse_url($url);
			extract($url_parsed);
			if (!@$scheme) $url_parsed = parse_url('http://'.$url);
			extract($url_parsed);
			if(!@$port) $port = 80;
			if(!@$path) $path = '/';
			if(@$query) $path .= '?'.$query;
			$out = "HEAD $path HTTP/1.0\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Connection: Close\r\n\r\n";

			if(!$fp = @fsockopen($host, $port, $es, $en, 5))
				{
					return false;
				}

			fwrite($fp, $out);
			while (!feof($fp)) {
				$s = fgets($fp, 128);
				if(($followRedirects) && (preg_match('/^Location:/i', $s) != false)){
				fclose($fp);
				return http_file_exists(trim(preg_replace("/Location:/i", "", $s)));
				}
				if(preg_match('/^HTTP(.*?)200/i', $s)){
				fclose($fp);
				return true;
				}
			}

			fclose($fp);
			return false;
	}
}

if (!function_exists('getCurUrl')) {

	function getCurUrl($existed_url){

    //$protocol = $_SERVER['HTTPS'] == 'on' ? 'https' : 'http';
	$pageURL = 'http'; 

	 if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}} 

	 $protocol = $pageURL;

    $current_host = $protocol.'://'.$_SERVER['HTTP_HOST'];

	if(substr_count($current_host, 'www'))
		{
		
			if(substr_count($existed_url, 'www')){

			  return $existed_url;

			}else{
			 
			return $existed_url = str_replace("http://","http://www.",$existed_url);

			}

	
	 }else{

		 	if(substr_count($existed_url, 'www')){

			  return $existed_url = str_replace("http://www.","http://",$existed_url);

			}else{
			 
				return $existed_url;

			}

	}

	}

}

?>