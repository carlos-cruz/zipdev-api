<?php
	//error_reporting(E_ALL); ini_set('display_errors',1);
	header('Content-Type: application/json');

	include '../DB.class.php';
	include '../Contact.php';
	$config = include '../dbconfig.php';

	$operation = $_SERVER['REQUEST_METHOD'];
		
	$db = new DB($config);
	$contact = new Contact($db);	

	$id = null;

	if (isset($_SERVER['PATH_INFO'])) {
		$id = filter_var($_SERVER['PATH_INFO'],FILTER_SANITIZE_NUMBER_INT);
	}else if(isset($_GET['id'])){
		$id = $_GET['id'];
	}

 	switch ($operation) {
 		case 'GET':
 			if (isset($_GET['search'])) {
 				$result = $contact->search($_GET['search']);
 			}else{
 				$result = ($id !== null) ?  $contact->read($id) : $contact->read();
 			}
 			echo $result;
 			break;

 		case 'POST':
 			$result = ($id !== null) ? $contact->update($id) : $contact->create();
 			echo $result;
 			break;
 		
 		case 'DELETE':
 			$result = $contact->delete($id);

 			echo $result;
 			break;
 	}

?>