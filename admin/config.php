<?php
	include './api/config.php';
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	session_start();
	$userid=$_SESSION['user_id'];

	function get_rol_level() {
		GLOBAL $conn, $userid;
		$qry="SELECT role FROM users WHERE id = :id";
		$result=$conn->prepare($qry);
		$result->bindParam(':id',$userid);
		$result->execute();
		$rol=$result->fetchColumn();
		if (!isset($_SESSION['user_id'])) {
			$rol=3;
		}
		return $rol;
	}
	function no_permission($rol) {
		if (get_rol_level()>$rol) {
			echo "Parece que te equivocaste, pero no tienes permiso para estar aquí.";
			echo "<script>alert('CORRE INSENSATO!')</script>";
			header("Refresh:0; url=http://localhost/intranet");
			return 0;
		}
	}
?>