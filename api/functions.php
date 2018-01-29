<?php
	include "config.php";

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	function ranking($type,$top){
		global $conn;
		$qry="SELECT * FROM ficonpuntos ORDER BY ficonpuntos DESC";
		for ($i = 1; $i <= 10; $i++) {
			$result=$conn->prepare($qry);
			$result->execute();
			$who=$result->fetchALL();
		}
		//print_r($who);
		$qry="SELECT * FROM users WHERE id = :id";
		$result=$conn->prepare($qry);
		$result->bindParam(':id', $who[$top-1]['user_id']);
		$result->execute();
		$nick=$result->fetch();
		//print_r($nick);
		switch($type){
			case "nick": echo $nick[1];break;
			case "fop": echo $who[$top-1]['ficonpuntos'];break;
			default: echo 'error'; break;
		}
		 return;
	}
?>