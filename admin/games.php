<?php
	include './api/config.php';
	require_once "admin/users.php";
	require_once "admin/config.php";

	function gettypes($id) {
		if (!no_permission(2)) {	
			GLOBAL $conn;
			$qry="SELECT name FROM type_games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $id);
			$result->execute();
			$type=$result->fetchColumn();
			return $type;
		}
	}
	function selecttype($id) {
		if(!no_permission(2)){
			GLOBAL $conn;
			$qry="SELECT * FROM type_games";
			$result=$conn->prepare($qry);
			$result->execute();

			$select="<select form='edit' name='a4'>";
			while ($types = $result->fetch()) {
				if (isset($id)) {
					if (gettypes($id)==$types[0]) {
						$select.="<option selected value='".$types[0]."'>".$types[1]."</option>";
					} else {
						$select.="<option value='".$types[0]."'>".$types[1]."</option>";
					}
				} else {
					$select.="<option value='".$types[0]."'>".$types[1]."</option>";
				}
			}
			$select.="</select>";
			return $select;
			unset($select);
		}
	}
	function getclasses($id) {
		if (!no_permission(2))
			GLOBAL $conn;
			$qry="SELECT name FROM class_games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $id);
			$result->execute();
			$class=$result->fetchColumn();
			return $class;
	}
	function selectclass($id) {
		if (!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT * FROM class_games";
			$result=$conn->prepare($qry);
			$result->execute();

			$select="<select form='edit' name='a5'>";
			while ($classes = $result->fetch()) {
				if (isset($id)) {
					if (getclasses($id)==$classes[0]) {
						$select.="<option selected value='".$classes[0]."'>".$classes[1]."</option>";
					} else {
						$select.="<option value='".$classes[0]."'>".$classes[1]."</option>";
					}
				} else {
					$select.="<option value='".$classes[0]."'>".$classes[1]."</option>";
				}
			}
			$select.="</select>";
			return $select;
			unset($select);
		}
	}
	function listgames() {
		if (!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT * FROM games";
			$result=$conn->prepare($qry);
			$result->execute();
			echo 
				"<table border='1'>
					<tr>
						<td>ID</td>
						<td>Título</td>
						<td>Descripción</td>
						<td>Imagen</td>
						<td>Tipo</td>
						<td>Clase</td>
						<td>Equipo mínimo</td>
						<td>Equipo máximo</td>
						<td>Fecha</td>
						<td>Fin registro</td>
						<td>Seleccionar</td>
					</tr>
					<form method='post' action='http://localhost/intranet/admin.php?f=gamemngm'>";
			while ($eventos = $result->fetch()) {
				echo "
				<tr>
					<td>".$eventos[0]."</td>
					<td>".utf8_encode($eventos[1])."</td>
					<td>".utf8_encode($eventos[2])."</td>
					<td>".$eventos[3]."</td>
					<td>".utf8_encode(gettypes($eventos[4]))."</td>
					<td>".utf8_encode(getclasses($eventos[5]))."</td>
					<td>".$eventos[6]."</td>
					<td>".$eventos[7]."</td>
					<td>".$eventos[8]."</td>
					<td>".$eventos[9]."</td>
					<td><input type='radio' name='evento' value='".$eventos[0]."'</td>
				</tr>";
			}
			echo "</table>
				<br>
				<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='points'>Modificar Puntos</button>
				<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='edit'>Modificar</button>
				<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='del'>Eliminar</button>'";
		}
	}
	function confirmdel() {
		if (!no_permission(2)) {
			echo "<form method='post' action='admin.php?f=delgame'><script>alert('Estás a punto de eliminar el evento número ".$_COOKIE['evento']."!')</script>
			<button type='submit' formmethod='post' class='btn btn-primary' name='confirm' value='del'>Confirmar</button></form>";
		}
	}
	function gamemngm() {
		if (!no_permission(2)) {
			if ($_POST['listgame']==='del') {
				setcookie("evento", $_POST['evento']);
				confirmdel();
			} elseif ($_POST['listgame']==='edit') {
				setcookie("evento", $_POST['evento']);
				editgame();
			} elseif ($_POST['listgame']==='points') {
				setcookie("evento", $_POST['evento'], time()+30);
				PointsXGame();
			}
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
			echo 
				"<table border='1'>
					<tr>
						<td>ID</td>
						<td>Título</td>
						<td>Descripción</td>
						<td>Imagen</td>
						<td>Tipo</td>
						<td>Clase</td>
						<td>Equipo mínimo</td>
						<td>Equipo máximo</td>
						<td>Fecha</td>
						<td>Fin registro</td>
					</tr>";
			echo "<form method='post' id='edit' action='http://localhost/intranet/admin.php?f=savegame'><tr><td><input type='text' name='id' readonly='readonly' value='".($id[0]);
			echo "'></td>
			<script>alert('Es muy importante que no la pifies en el formato de las fechas! (AAAA-MM-DD HH:MM:SS)')</script>
			<td><input type='text' name='a1' value='".utf8_encode($id[1])."'></td>
			<td><input type='text' name='a2' value='".utf8_encode($id[2])."'></td>
			<td><input type='text' name='a3' value='".$id[3]."'></td>
			<td>".selecttype($id[4])."</td>
			<td>".selectclass($id[5])."</td>
			<td><input type='text' name='a6' value='".$id[6]."'></td>
			<td><input type='text' name='a7' value='".$id[7]."'></td>
			<td><input type='text' name='a8' placeholder='AAAA-MM-DD HH:MM:SS' value='".$id[8]."'></td>
			<td><input type='text' name='a9' placeholder='AAAA-MM-DD HH:MM:SS' value='".$id[9]."'></td>
			</table>
			<br>
			<button type='submit' formmethod='post' class='btn btn-primary'>Guardar</button>";

		}
	}
	function savegame() {
		if (!no_permission(2)) {
			if(isset($_GET['f']) AND $_GET['f']=='savegame') {
				GLOBAL $conn;
				$qry="UPDATE games SET `name`=:a1 , `description`=:a2, `image`=:a3, `type`=:a4, `class`=:a5, `max`=:a6, `min`=:a7, `date_event`=:a8, `date_max`=:a9 WHERE `id`=:id";
				$result=$conn->prepare($qry);
				$a1=utf8_decode($_POST['a1']);
				$a2=utf8_decode($_POST['a2']);
				$result->bindParam(':id', $_POST['id']);
				$result->bindParam(':a1', $a1);
				$result->bindParam(':a2', $a2);
				$result->bindParam(':a3', $_POST['a3']);
				$result->bindParam(':a4', $_POST['a4']);
				$result->bindParam(':a5', $_POST['a5']);
				$result->bindParam(':a6', $_POST['a6']);
				$result->bindParam(':a7', $_POST['a7']);
				$result->bindParam(':a8', $_POST['a8']);
				$result->bindParam(':a9', $_POST['a9']);
				$result->execute();
			}
			PointsXGamePrepare();
			setcookie("evento", null, -1);
			header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_juego");
		}
	}
	function creategame() {
		if(!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT MAX(ID) FROM games";
			$result=$conn->prepare($qry);
			$result->execute();
			$id=$result->fetchColumn();
			echo 
				"<table border='1'>
					<tr>
						<td>ID</td>
						<td>Título</td>
						<td>Descripción</td>
						<td>Imagen</td>
						<td>Tipo</td>
						<td>Clase</td>
						<td>Equipo mínimo</td>
						<td>Equipo máximo</td>
						<td>Fecha</td>
						<td>Fin registro</td>
					</tr>";
			echo "<form method='post' id='edit' action='admin.php?f=newgame'><tr><td><input type='text' name='id' readonly='readonly' value='".($id+1);
			echo "'></td>
			<script>alert('Es muy importante que no la pifies en el formato de las fechas! (AAAA-MM-DD HH:MM:SS)')</script>
			<td><input type='text' name='a1'></td>
			<td><input type='text' name='a2'></td>
			<td><input type='text' name='a3'></td>
			<td>".selecttype(1)."</td>
			<td>".selectclass(1)."</td>
			<td><input type='text' name='a6'></td>
			<td><input type='text' name='a7'></td>
			<td><input type='text' name='a8' placeholder='AAAA-MM-DD HH:MM:SS'></td>
			<td><input type='text' name='a9' placeholder='AAAA-MM-DD HH:MM:SS'></td>
			</table>
			<br>
			<button type='submit' formmethod='post' class='btn btn-primary'>Crear</button>";
		}
	}
	function newgame() {
		if(!no_permission(2)) {
			if(isset($_GET['f']) AND $_GET['f']=='newgame') {
				GLOBAL $conn;
				$qry="INSERT INTO games (`id`, `name`, `description`, `image`, `type`, `class`, `max`, `min`, `date_event`, `date_max`) VALUES (:id, :a1, :a2, :a3, :a4, :a5, :a6, :a7, :a8, :a9)";
				$result=$conn->prepare($qry);
				$a1=utf8_decode($_POST['a1']);
				$a2=utf8_decode($_POST['a2']);
				$result->bindParam(':id',$_POST['id']);
				$result->bindParam(':a1',$a1);
				$result->bindParam(':a2',$a2);
				$result->bindParam(':a3',$_POST['a3']);
				$result->bindParam(':a4',$_POST['a4']);
				$result->bindParam(':a5',$_POST['a5']);
				$result->bindParam(':a6',$_POST['a6']);
				$result->bindParam(':a7',$_POST['a7']);
				$result->bindParam(':a8',$_POST['a8']);
				$result->bindParam(':a9',$_POST['a9']);
				$result->execute();
			}
			header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_juego");
		}
	}
	function delgame() {
		if (!no_permission(2)) {
			GLOBAL $conn;
			$qry="DELETE FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
		}
		setcookie('evento', null, -1);
		unset($_POST);
		header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_juego");
	}
	function PointsXGame() {
		if(!no_permission(2)) {
			PointsXGamePrepare();
			GLOBAL $conn;
			$qry="SELECT * FROM points_games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$PTable=$result->fetch();
			print_r($_COOKIE['evento']);
			print_r($PTable[0]);

			$qry="SELECT name FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$EName=$result->fetchColumn();

			$qry="SELECT type FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$type=$result->fetchColumn();

			echo "Editar posiciones de: ".$EName;
			echo "
				<table>
					<tr>
						<td>
							Posición
						</td>
						<td>
							Tipo
						</td>
						<td>
							Puntos
						</td>
					</tr><form method='post' action='./admin.php?f=PointsXGameSave'>
			";

			foreach ($PTable as $elem) {
				echo "
					<tr>
						<td>
							<input type='text' readonly='readonly' name='a0' value='".$elem[2]."'>
						</td>
						<td>
							<input type='text' readonly='readonly' value='".gettypes($elem[1])."'>
						</td>
						<td>
							<input type='text' name=':a1' value='".$elem[3]."'>
						</td>
					</tr>		
				";	
			}
			echo "</table><br><button type='submit' formmethod='post' class='btn btn-primary'>Guardar</button></form>";
		}
	}
	function PointsXGameSave() {
		if (!no_permission(2)) {
			$qry="UPDATE points_games SET `points`=:pts WHERE position=:pos AND id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':pts', $_POST[':a1']);
			$result->bindParam(':pos', $_POST[':a2']);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
		}
		setcookie('evento', null, -1);
	}
	function PointsXGamePrepare() {
		if (!no_permission(2)) {
			GLOBAL $conn;
			$qry="SELECT * FROM points_games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
			$PTable=$result->fetchAll();

			$qry="SELECT max FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
			$max=$result->fetchColumn();

			$qry="SELECT type FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
			$ntype=$result->fetchColumn();

			$pos=count(array_keys($PTable))+1;
			if (($max+1)>$pos) {
				while (($max+1)>$pos) {
					$qry="INSERT INTO points_games (`id`, `position`) VALUES (:id, :pos)";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $_COOKIE['evento']);
					$result->bindParam(':pos', $pos);
					$result->execute();
					echo "asd";
					$pos=$pos+1;
				}
			} elseif (($max+1)==$pos) {
				echo "shiut";
			} elseif (($max+1)<$pos) {
				$qry="DELETE FROM points_games WHERE position>:max AND `id`=:id";
				$result=$conn->prepare($qry);
				$nmax=$max;
				$result->bindParam(':max', $nmax);
				$result->bindParam(':id', $_COOKIE['evento']);
				$result->execute();
				echo "shist";
			}

			$qry="SELECT * FROM points_games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
			$PTable=$result->fetchAll();

			$qry="SELECT max FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
			$max=$result->fetchColumn();

			$qry="SELECT type FROM games WHERE id=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->execute();
			$ntype=$result->fetchColumn();

			$qry="UPDATE points_games SET `type_game`=:type WHERE `id`=:id";
			$result=$conn->prepare($qry);
			$result->bindParam(':id', $_COOKIE['evento']);
			$result->bindParam(':type', $ntype);
			$result->execute();
		}
	}
?>