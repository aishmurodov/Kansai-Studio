<?php

// ini_set('display_errors', 1);
// error_reporting(E_ALL);
ini_set('display_errors', 0);
error_reporting(0);

function debug($str) {
	echo '<pre>';
	var_dump($str);
	echo '</pre>';
	exit;
}