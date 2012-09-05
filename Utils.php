<?php

/**
 * Utils.php
 *
 * 2004 Nathan Vack <njvack@library.wisc.edu>
 *
 * This file contains a set of utility functions useful to all. Or, at least,
 * many. Mostly it's interface-slutting stuff.
 */

function gpwd($valName, $default = '') {
    return isset($_POST[$valName]) ? $_POST[$valName] : $default;
}

function grwd($valName, $default = '') {
    return isset($_REQUEST[$valName]) ? $_REQUEST[$valName] : $default;
}

// function to sanity check dates	
function makeDateSane($myDate) {
    $formattedDate = trim($myDate);
    if ($formattedDate == '') { return ''; }
    // possible delimiters
    $char = array(
        "/",
        "-",
        ".");
    
    foreach ($char as $testDelim) {
        $dateParts = explode($testDelim, $formattedDate);
        if (count($dateParts) == 3) { 
           $formattedDate = implode("/", $dateParts); 
        }	
    }
    
    if (strtotime($formattedDate) > 0) {
        $formattedDate = date('n/d/y g:i A', strtotime($formattedDate));
    }
    
    return $formattedDate;
}

function implode_assoc($inner_glue, $outer_glue, $array, $skip_empty=false, $urlencoded=false) {
   $output = array();
   foreach($array as $key=>$item) {
       if (!$skip_empty || isset($item)) {
           if ($urlencoded)
               $output[] = $key.$inner_glue.urlencode($item);
           else
               $output[] = $key.$inner_glue.$item;
       }
   }
   
   return implode($outer_glue, $output);
}

/**
 * This formats a search string into a format understandable to a
 * MATCH condition in mysql
 */
 function mySqlFulltextString($searchString) {
        return '+'. implode('+', tokenizedSearchString($searchString));
    }
	
    /**
     * This will tear apart the search string and build it into an array for our 
     * Boolean search hotlist 
     */
    function tokenizedSearchString($searchString) {
    
	$searchTokens = array();
	$currentToken = "";
	$add = true;
	
	$words = explode(" ", $searchString);
	
	foreach ($words as $word){
		if (substr($word, 0, 1) == '"'){
			$add = false;
			
		}
		
		if (substr($word, -1, 1) == '"'){
			$add = true;
			
		} 
		$currentToken .= $word . " ";
		if ($add){
			$searchTokens[] = $currentToken;
			$currentToken = "";
		}
	}
	
	if ($currentToken != ""){
		$searchTokens[] = $currentToken;
	}
	return $searchTokens;

}

function mTimeFloat() 
{
    list($usec, $sec) = explode(" ", microtime());
    return ((float)$usec + (float)$sec);
}

function getWelcomePage(){
  
  $host = $_SERVER['HTTP_HOST'];
  if (!$host) {
    $host = $_SERVER['SERVER_NAME'];
  }
	$cleanURI = str_replace('index.php', '', $_SERVER['REQUEST_URI']);
	if(substr($cleanURI, -1) != '/'){
		$cleanURI .= '/';
	}
	return "http://${host}${cleanURI}questionAddForm.do";
}

function getRemoteIp() {
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return $_SERVER['HTTP_X_FORWARDED_FOR'];
    } 
    else {
        return $_SERVER['REMOTE_ADDR'];
    }
}