<?php
	
	//////////////////////
	/////// BASICS ///////
	//////////////////////

		include './api/config.php';
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		//session_start();

	//////////////////////////
	/////// PERMISSION ///////
	//////////////////////////
		
		function get_rol_level() {
			if (!isset($_COOKIE['user_id'])) {
				$rol=3;
			} else {
				GLOBAL $conn;
				$qry="SELECT role FROM users WHERE id = :id";
				$result=$conn->prepare($qry);
				$result->bindParam(':id', $_COOKIE['user_id']);
				$result->execute();
				$rol=$result->fetchColumn();
			}
			return $rol;
		}
		function no_permission($rol) {
			if (get_rol_level()>$rol) {
				echo "No tienes permisos para estar aquí.";
				echo "<script>alert('No tienes permisos para estar aquí.')</script>";
				header("Refresh:0; url=http://localhost/intranet/");
				die;
				return 0;
			}
		}
?>