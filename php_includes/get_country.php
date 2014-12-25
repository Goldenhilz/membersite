<?php

header('Content-Type: application/json');

if (!isset($_GET['query'])) {
	echo json_encode([]);
	exit();
}

$stack = array();
$json_array = '[';

$jsonData = file_get_contents("../js/countries.json");
$phpArray = json_decode($jsonData, true);
foreach ($phpArray as $key => $value) {
    foreach ($value as $k => $v) {
        foreach ($v as $sk => $sv) {
        	$subject = $sv;
        	$query = $_GET['query'];
			$pattern = '/^' . $query . '/i';
			if (preg_match($pattern, $subject) & ($sk === 'name')) {
				$temp = '{' .  '"' . $sk . '"' . ':' . '"' . $sv . '"' . '}' . ',';
				$json_array = $json_array . $temp;
			}
	    }
    }
}

$json_array =  rtrim($json_array, ",");

$json_array = $json_array . ']';

echo $json_array;

?>