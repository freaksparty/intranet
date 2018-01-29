<?php
	include "./api/config.php";

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
	function listgames() {
		if (!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT * FROM games";
			$result=$conn->prepare($qry);
			$result->execute();
			echo "<table border='1'><tr><td>ID</td><td>Título</td><td>Descripción</td><td>Fecha</td><td>Día</td><td>Hora</td><td>Máx</td><td>Equipo mínimo</td><td>Equipo máximo</td><td>Fin registro</td><td>Seleccionar</td></tr><form method='post' action='http://localhost/intranet/admin.php?f=gamemngm'>";
			while ($eventos = $result->fetch()) {
				echo "<tr><td>".$eventos[0]."</td><td>".utf8_encode($eventos[2])."</td><td>".utf8_encode($eventos[3])."</td><td>".$eventos[4]."</td><td>".utf8_encode($eventos[5])."</td><td>".$eventos[6]."</td><td>".$eventos[7]."</td><td>".$eventos[8]."</td><td>".$eventos[9]."</td><td>".$eventos[10]."</td><td><input type='radio' name='evento' value='".$eventos[0]."'</tr>";
			}
			echo "</table>
				<br>
				<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='edit'>Modificar</button>
				<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='del'>Eliminar</button>'";
		}
		print_r($_COOKIE);
	}
	function confirmdel() {
		if (!no_permission(2)) {
			echo "<form method='post' action='admin.php?f=delgame'>
			<button type='submit' formmethod='post' class='btn btn-primary' name='confirm' value='del'>Confirmar</button>";
		}
	}
	function gamemngm() {
			if ($_POST['listgame']==='del') {
				print_r($_POST);
				$_COOKIE['evento']=$_POST['evento'];
				confirmdel();
			} elseif ($_POST['listgame']==='edit') {
				editgame();
				header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_juego");
			}
	}
	function editgame() {
		if (!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT * FROM games WHERE `id` = :id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_POST['evento']);
			$result->execute();
			$id=$result->fetch();
			echo "<table border='1'><tr><td>ID</td><td>Título</td><td>Descripción</td><td>Fecha</td><td>Día</td><td>Hora</td><td>Máx</td><td>Equipo mínimo</td><td>Equipo máximo</td><td>Fin registro</td></tr>";
			echo "<form method='post' action='http://localhost/intranet/admin.php?f=savegame'><tr><td><input type='text' name='id' readonly='readonly' value='".($id[0]);
			echo "'></td>
			<td><input type='text' name='a1' value='".utf8_encode($id[2])."'></td>
			<td><input type='text' name='a2' value='".utf8_encode($id[3])."'></td>
			<td><input type='text' name='a3' value='".$id[4]."'  placeholder='aaaa-mm-dd hh:mm:ss'></td>
			<td><input type='text' name='a4' value='".utf8_encode($id[5])."'></td>
			<td><input type='time' name='a5' value='".$id[6]."'></td>
			<td><input type='text' name='a6' value='".$id[7]."'></td>
			<td><input type='text' name='a7' value='".$id[8]."'></td>
			<td><input type='text' name='a8' value='".$id[9]."'></td>
			<td><input type='text' name='a9' value='".$id[10]."' placeholder='aaaa-mm-dd hh:mm:ss'></td></tr>
			<tr><td><button type='submit' formmethod='post' class='btn btn-primary'>Guardar</button></td></tr></table>";
			print_r($id);
		}
	}
	function savegame() {
		if (!no_permission(2)) {
			if(isset($_GET['f']) AND $_GET['f']=='savegame') {
				GLOBAL $conn;
				$qry="UPDATE games SET `title`=:a1 , `description`=:a2, `day_week`=:a4, `hour`=:a5, `max`=:a6, `min_team`=:a7, `max_team`=:a8, `date`=:a3, `date_end_reg`=:a9 WHERE `id`=:id";
				$result=$conn->prepare($qry);
				$a1=utf8_decode($_POST['a1']);
				$a2=utf8_decode($_POST['a2']);
				$a4=utf8_decode($_POST['a4']);
				$result->bindParam(':id',$_POST['id']);
				$result->bindParam(':a1',$a1);
				$result->bindParam(':a2',$a2);
				$result->bindParam(':a3',$_POST['a3']);
				$result->bindParam(':a4',$a4);
				$result->bindParam(':a5',$_POST['a5']);
				$result->bindParam(':a6',$_POST['a6']);
				$result->bindParam(':a7',$_POST['a7']);
				$result->bindParam(':a8',$_POST['a8']);
				$result->bindParam(':a9',$_POST['a9']);
				$result->execute();
			}
		}
	}
	function creategame() {
		if(!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT MAX(ID) FROM games";
			$result=$conn->prepare($qry);
			$result->execute();
			$id=$result->fetchColumn();
			echo "<table width='1000 border='1'><tr><td>ID</td><td>Título</td><td width='20%'>Descripción</td><td>Fecha</td><td>Día</td><td>Hora</td><td>Máx</td><td>Equipo mínimo</td><td>Equipo máximo</td><td>Fin registro</td></tr>";
			echo "<form method='post' action='http://localhost/intranet/admin.php?f=newgame'><tr><td><input type='text' name='id' readonly='readonly' value='".($id+1);
			echo "'></td>
			<td><input type='text' name='a1'></td>
			<td><input type='text' name='a2'></td>
			<td><input type='text' name='a3' placeholder='aaaa-mm-dd hh:mm:ss'></td>
			<td><input type='text' name='a4'></td>
			<td><input type='time' name='a5'></td>
			<td><input type='text' name='a6'></td>
			<td><input type='text' name='a7'></td>
			<td><input type='text' name='a8'></td>
			<td><input type='text' name='a9' placeholder='aaaa-mm-dd hh:mm:ss'></td></tr>
			<tr><td><button type='submit' formmethod='post' class='btn btn-primary'>Crear</button></td></tr></table>";
		}
	}
	function newgame() {
		if(!no_permission(2)) {
			if(isset($_GET['f']) AND $_GET['f']=='newgame') {
				GLOBAL $conn;
				$qry="INSERT INTO games (`id`, `title`, `description`, `day_week`, `hour`, `max`, `min_team`, `max_team`, `date`, `date_end_reg`) VALUES (:id, :a1, :a2, :a4, :a5, :a6, :a7, :a8, :a3, :a9)";
				$result=$conn->prepare($qry);
				$a1=utf8_decode($_POST['a1']);
				$a2=utf8_decode($_POST['a2']);
				$a4=utf8_decode($_POST['a4']);
				$result->bindParam(':id',$_POST['id']);
				$result->bindParam(':a1',$a1);
				$result->bindParam(':a2',$a2);
				$result->bindParam(':a3',$_POST['a3']);
				$result->bindParam(':a4',$a4);
				$result->bindParam(':a5',$_POST['a5']);
				$result->bindParam(':a6',$_POST['a6']);
				$result->bindParam(':a7',$_POST['a7']);
				$result->bindParam(':a8',$_POST['a8']);
				$result->bindParam(':a9',$_POST['a9']);
				$result->execute();
			}
		}
	}
	function delgame() {
		if (!no_permission(2)) {
			GLOBAL $conn, $_COOKIE;
			$qry="DELETE FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
		}
		//unset($_COOKIE, $_POST);
		header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_juego");
	}
?>