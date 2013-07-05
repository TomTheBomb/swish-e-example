<?php
	require_once('result.php');
	$files = json_decode($_GET['files']);
	foreach ($files as $key => $file) {
		$files[$key] = '/var/www/swish/files/' . $file;
	}

	$search = new ResultText($files, $_GET['term'], 10);
	
	header('Cache-Control: no-cache, must-revalidate');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Content-Type: application/json');
	echo json_encode($search->getResults());
