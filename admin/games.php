<?php
	
	//////////////////////////////////
	///////////// BASICS /////////////
	//////////////////////////////////

		include './api/config.php';
		require_once "admin/users.php";
		require_once "admin/config.php";

	///////////////////////////////////////////////
	/////////////// TYPE/CLASS MENU ///////////////
	///////////////////////////////////////////////

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
		function getclasses($id) {
			if (!no_permission(2)) {
				GLOBAL $conn;
				$qry="SELECT name FROM class_games WHERE id=:id";
				$result=$conn->prepare($qry);
				$result->bindParam(':id', $id);
				$result->execute();
				$class=$result->fetchColumn();
				return $class;
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

	///////////////////////////////////////////////
	//////////// LIST GAME AND OPTIONS ////////////
	///////////////////////////////////////////////

		///////////////////////////////////
		///////////// GENERAL /////////////
		///////////////////////////////////

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
							<td>Equipo máximo</td>
							<td>Equipo mínimo</td>
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
					<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='positions'>Tabla de posiciones</button>
					<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='edit'>Modificar</button>
					<button type='submit' formmethod='post' class='btn btn-primary' name='listgame' value='del'>Eliminar</button>'";
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
					setcookie("evento", $_POST['evento']);
					$_COOKIE['evento']=$_POST['evento'];
					PointsXGame();
				} elseif ($_POST['listgame']==='positions') {
					$game=$_POST['evento'];
					user_position_table($game);
				}
			}
		}

	///////////////////////////////////////////////
	//////////// MANAGE GAME FUNCTIONS ////////////
	///////////////////////////////////////////////

		///////////////////////////////////////
		///////////// DELETE GAME /////////////
		///////////////////////////////////////

			function confirmdel() {
				if (!no_permission(2)) {
					echo "<form method='post' action='admin.php?f=delgame'><script>alert('Estás a punto de eliminar el evento número ".$_COOKIE['evento']."!')</script>
					<button type='submit' formmethod='post' class='btn btn-primary' name='confirm' value='del'>Confirmar</button></form>";
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

		/////////////////////////////////
		/////////// EDIT GAME ///////////
		/////////////////////////////////

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
								<td>Equipo máximo</td>
								<td>Equipo mínimo</td>
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

		///////////////////////////////////
		/////////// CREATE GAME ///////////
		///////////////////////////////////

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
								<td>Equipo máximo</td>
								<td>Equipo mínimo</td>
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
	
		//////////////////////////////////////
		/////////// POINTS PER GAME///////////
		//////////////////////////////////////

			function PointsXGame() {
				if(!no_permission(2)) {
					PointsXGamePrepare();
					GLOBAL $conn;
					$qry="SELECT * FROM points_games WHERE id=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $_COOKIE['evento']);
					$result->execute();
					$PTable=$result->fetchAll();

					$qry="SELECT name FROM games WHERE id=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $_COOKIE['evento']);
					$result->execute();
					$EName=$result->fetchColumn();

					$qry="SELECT type FROM games WHERE id=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $_COOKIE['evento']);
					$result->execute();
					$type=$result->fetchColumn();

					echo "Editar posiciones de: <b>".$EName."</b>";
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
									<input type='text' readonly='readonly' name='a0[]' value='".$elem[2]."'>
								</td>
								<td>
									<input type='text' readonly='readonly' value='".gettypes($elem[1])."'>
								</td>
								<td>
									<input type='text' name='a1[]' value='".$elem[3]."'>
								</td>
							</tr>       
						";  
					}
					echo "</table><br><button type='submit' formmethod='post' class='btn btn-primary'>Guardar</button></form>";
				}
			}
			function PointsXGameSave() {
				if (!no_permission(2)) {
					$i=0;
					$tpost['a0']=$_POST['a0'];
					$tpost['a1']=$_POST['a1'];
					$j=count(array_keys($_POST['a0']))+1;
					while ($i<$j) {
						GLOBAL $conn;
						$qry="UPDATE points_games SET `points`=:pts WHERE position=:pos AND id=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(':pts', $tpost['a1'][$i]);
						$result->bindParam(':pos', $tpost['a0'][$i]);
						$result->bindParam(':id', $_COOKIE['evento']);
						$result->execute();
						$i=$i+1;
					}
				}
				unset($_COOKIE['evento']);
				header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_juego");
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
							$pos=$pos+1;
						}
					} elseif (($max+1)==$pos) {
					} elseif (($max+1)<$pos) {
						$qry="DELETE FROM points_games WHERE position>:max AND `id`=:id";
						$result=$conn->prepare($qry);
						$nmax=$max;
						$result->bindParam(':max', $nmax);
						$result->bindParam(':id', $_COOKIE['evento']);
						$result->execute();
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

		//////////////////////////////////////
		/////////////  POSITIONS /////////////
		//////////////////////////////////////

			///////////////////////////////////
			///////////// GENERAL /////////////
			///////////////////////////////////

				function user_position_table($event) {
					if (!no_permission(2)) {
						GLOBAL $conn;
						$qry="SELECT class FROM games WHERE id=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(":id", $event);
						$result->execute();
						$type=$result->fetchColumn();

						$equipos=explode("por equipos", $type);
						if (is_array($equipos)) {
							short_by_teams($event);
						} else {
							short_by_users($event);
						}
					}
				}

			///////////////////////////////////
			///////////// BY USER /////////////
			///////////////////////////////////

				function get_user_nick($id) {
					if (!no_permission(2)) {
						GLOBAL $conn;
						$qry="SELECT nick FROM users WHERE id=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(":id", $id);
						$result->execute();
						$nick=$result->fetchColumn();

						return $nick;
					}
				}
				function short_by_users($id) {
					if (!no_permission(2)) {
						GLOBAL $conn;
						$qry="SELECT * FROM participants WHERE game=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(":id", $id);
						$result->execute();
						$res=$result->fetchAll();

						echo "<form method='post' action='admin.php?f=edit_positions_users'>";
						foreach ( $res as $result) {
							echo "
								<ul>
									<li>
										".get_user_nick($result[1])."    <input type='text' name='id' hidden='hidden' readonly='readonly' value='".$id."'> <input type='text' name='a".$result[1]."' hidden='hidden' readonly='readonly' value='".$result[1]."'>    Posicion: <input type='text' name='b".$result[1]."'>
									</li>
								</ul>
							";
						}
						echo "<br><button type='submit' formmethod='post' class='btn btn-primary'>Guardar</button></form>";
					}
				}
				function edit_positions_users() {
					if (!no_permission(2)) {
						GLOBAL $conn;

						$i=1;
						$id=$_POST['id'];
						$keys=array_keys($_POST);

						print_r($keys);
						$pos=count(array_keys($_POST));
						$keys=array_keys($_POST);

						while ($i<$pos) {
							if ($i%2==1) {
								$truedata['user'][$i]=$_POST[$keys[$i]];
							} else {
								$truedata['pos'][$i]=$_POST[$keys[$i]];
							}
							$i++;
						}

						$i=0;
						while ($i<$pos) {
							$qry="UPDATE participants SET position=:pos WHERE game=:id AND team=:user ";
							$result=$conn->prepare($qry);
							$result->bindParam(':pos', $truedata['pos'][$i]);
							$result->bindParam(':user', $truedata['user'][$i-1]);
							$result->bindParam(':id', $id);
							$result->execute();
							$i++;
						}
					}
			
			///////////////////////////////////
			///////////// BY TEAM /////////////         
			///////////////////////////////////

				function get_team_name($id) {
					if (!no_permission(2)) {
						GLOBAL $conn;
						$qry="SELECT name FROM teams_participants WHERE id=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(":id", $id);
						$result->execute();
						$teamnick=$result->fetchColumn();

						return $teamnick;
					}
				}
				function short_by_teams($id) {
					if (!no_permission(2)) {
						GLOBAL $conn;
						$qry="SELECT DISTINCT team FROM participants WHERE game=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(":id", $id);
						$result->execute();

						echo "<form method='post' action='admin.php?f=edit_positions_teams'>";

						$i=0;
						while  (($res=$result->fetchColumn($i))!=false) {
							$qry="SELECT * FROM participants WHERE team=:team";
							$final=$conn->prepare($qry);
							$final->bindParam(":team", $res);
							$final->execute();
							$res=$final->fetchAll();

							echo "
								<ul>
									<li> ".get_team_name($res[$i][2])."
										<ul>
							";
							foreach ($res as $resu) {
								echo "
									<li>
										".get_user_nick($resu[1])."  <input type='text' name='id' hidden='hidden' readonly='readonly' value='".$id."'><input type='text' name='a".$resu[1]."' hidden='hidden' readonly='readonly' value='".$resu[1]."'>     Posicion: <input type='text' name='b".$resu[1]."'>
									</li>
							";
							}
							echo "
										</ul>
									</li>
								</ul>
							";
							$i++;
						}
						echo "<br><button type='submit' formmethod='post' class='btn btn-primary'>Guardar</button></form>";
					}
				}
				function edit_positions_teams () {
					if (!no_permission(2)) {
						GLOBAL $conn;

						$i=1;
						$id=$_POST['id'];
						$keys=array_keys($_POST);

						print_r($keys);
						$pos=count(array_keys($_POST));
						$keys=array_keys($_POST);

						while ($i<$pos) {
							if ($i%2==1) {
								$truedata['team'][$i]=$_POST[$keys[$i]];
							} else {
								$truedata['pos'][$i]=$_POST[$keys[$i]];
							}
							$i++;
						}

						$i=0;
						while ($i<$pos) {
							$qry="UPDATE participants SET position=:pos WHERE game=:id AND team=:team ";
							$result=$conn->prepare($qry);
							$result->bindParam(':pos', $truedata['pos'][$i]);
							$result->bindParam(':team', $truedata['team'][$i-1]);
							$result->bindParam(':id', $id);
							$result->execute();
							$i++;
						}
					}
				}
	?>