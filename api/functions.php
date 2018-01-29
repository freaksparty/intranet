<?php
	include "config.php";

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	/*function ranking($type,$top){
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
	}*/
	function points_total() {
		$pg=points_games();
		$pu=points_users();
		$pgkeys=array_keys($pg);
		$pukeys=array_keys($pu);
		foreach ($pgkeys as $key) {
			if (isset($pu[$key])) {
				$pt[$key]=$pu[$key]+$pg[$key];
			} else {
				$pt[$key]=$pg[$key];
			}
		}
		unset($key);
		foreach ($pukeys as $key) {
			if (!isset($pt[$key])) {
				$pt[$key]=$pu[$key];
			}
		}
		return $pt;
	}
	function getnick($id) {
		global $conn;
		$qry="SELECT nick FROM users WHERE id=:id";
		$result=$conn->prepare($qry);
		$result->bindParam(':id', $id);
		$result->execute();
		$res=$result->fetchColumn();
		return $res;
	}
	function ranking($type, $top) {
		$pt=points_total();
		arsort($pt);
		$keys=array_keys($pt);
		$top=$top-1;
		switch($type){
			case "nick":
				echo getnick($keys[$top]);
			break;
			case "fop":
				echo $pt[$keys[$top]];
			break;
			default: 
				echo 'error'; 
			break;
		}
	}
	function points_games() {
		global $conn;
		$qry="SELECT p.user, k.points FROM participants AS p, (SELECT * FROM points_games) AS k WHERE p.position=k.position AND p.game=k.id";
		$result=$conn->prepare($qry);
		$result->execute();
		$res=$result->fetchALL();

		foreach ($res as $res2) {
			if(!isset($pg[$res2[0]])) {
				$pg[$res2[0]]=0;
			}
			$pg[$res2[0]]=$pg[$res2[0]]+$res2[1];
		}
		return $pg;
	}
	function points_users() {
		global $conn;
		$qry="SELECT user, points FROM points_users";
		$result=$conn->prepare($qry);
		$result->execute();
		$res=$result->fetchALL();

		foreach ($res as $res2) {
			if(!isset($pu[$res2[0]])) {
				$pu[$res2[0]]=0;
			}
			$pu[$res2[0]]=$pu[$res2[0]]+$res2[1];
		}
		return $pu;
	}
?>