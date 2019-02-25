<?php
	
	//////////////////////////////////
	///////////// BASICS /////////////
	//////////////////////////////////

		include './api/config.php';
		require_once "admin/users.php";
		require_once "admin/config.php";

	//////////////////////////////////
	///////// RETOS OPTIONS //////////
	//////////////////////////////////

		//////////////////////////////////
		//////////// COMUNES /////////////
		//////////////////////////////////
			function boolToText($state) {
				if($state)
					return "Sí";
				else
					return "No";
			}

		//////////////////////////////////
		////////// LIST RETOS ////////////
		//////////////////////////////////

			function listretos() {
				GLOBAL $conn;

				$qry="SELECT * FROM retos";
				$result=$conn->prepare($qry);
				$result->execute();

				echo "<table class='table table-striped'>
							<thead>
								<tr>
									<th>#</th>
									<th>Orga</th>
									<th>Título</th>
									<th>Descripción</th>
									<th>Imagen</th>
									<th>Registro</th>
									<th>Repetir</th>
									<th>Máximo participantes</th>
									<th>Máximo ganadores</th>
									<th>FicoPuntos</th>
									<th>Fecha</th>
									<th>Fin registro</th>
									<th>Seleccionar</th>
								</tr>
							</thead>
							<tbody>
								<form method='post' action='http://localhost/intranet/admin.php?f=retomngm'>";
				while($retos=$result->fetch()) {
					if(get_rol_level()==1 || $retos['orga']==$_COOKIE['user_id'])
						$disabled="";
					else 
						$disabled="disabled";

					echo "
						<tr>
						<th scope='row'>".$retos['idx']."</th>
						<td>".get_user_nick($retos['orga'])."</td>
						<td>".utf8_encode($retos['name'])."</td>
						<td>".utf8_encode($retos['info'])."</td>
						<td>".$retos['img']."</td>
						<td>".boolToText($retos['reg_enable'])."</td>
						<td>".boolToText($retos['repetir'])."</td>
						<td>".$retos['max_participants']."</td>
						<td>".$retos['max_winners']."</td>
						<td>".$retos['puntos']."</td>
						<td>".$retos['start_date']."</td>
						<td>".$retos['deadline']."</td>
						<td><input type='radio' name='reto' ".$disabled." value='".$retos['idx']."'</td>
					";
					echo "</tbody></table>
						<br>
						<button type='submit' formmethod='post' class='btn btn-primary' name='listreto' value='edit'>Modificar</button>
						<button type='submit' formmethod='post' class='btn btn-primary' name='listreto' value='del'>Eliminar</button>'
						<button type='submit' formmethod='post' class='btn btn-primary' name='listreto' value='part'>Participantes</button>

					";
				}
			}

			function retomngm() {
				if ($_POST['listreto']==='del') {
						setcookie("reto", $_POST['reto']);
						confirmdelreto();
				}  elseif ($_POST['listreto']==='edit') {
						setcookie("reto", $_POST['reto']);
						editreto();
				}
			}

		//////////////////////////////////
		/////////// DEL RETOS ////////////
		//////////////////////////////////

			function confirmdelreto() {
				if (!no_permission(2)) {
					echo "<form method='post' action='admin.php?f=delreto'><script>alert('Estás a punto de eliminar el evento número ".$_COOKIE['reto']."!')</script>
					<button type='submit' formmethod='post' class='btn btn-primary' name='confirm' value='del'>Confirmar</button></form>";
				}
			}
			function delreto() {
				if (!no_permission(2)) {
					GLOBAL $conn;
					$qry="DELETE FROM retos WHERE idx=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $_COOKIE['reto']);
					$result->execute();
				}
				setcookie('reto', null, -1);
				unset($_POST);
				header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_retos");
			}

		//////////////////////////////////
		////////// EDIT RETOS ////////////
		//////////////////////////////////

			function editreto() {
				if (!no_permission(2)) {
					GLOBAL $conn;
					$qry="SELECT * FROM retos WHERE `idx` = :id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $_POST['reto']);
					$result->execute();
					$id=$result->fetch();

					if($id['orga']==$_COOKIE['user_id'] || get_rol_level()==1)  {
						$disabled="";
					}
					else {
						$disabled="disabled";
					} 

					if(get_rol_level()==1)
						$asd="";
					else
						$asd="readonly";

					echo "
					<form method='post' id='edit' action='http://localhost/intranet/admin.php?f=savereto'>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>#</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='id' readonly='readonly' value='".($id[0])."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Organizador</label>
						<div class='col-10'>
							<input class='form-control' type='text' readonly='readonly' name='a13' value='".get_user_nick($id['orga'])."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Título</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a1' value='".utf8_encode($id['name'])."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Descripción</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a2' value='".utf8_encode($id['info'])."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Imagen</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a3' value='".$id['img']."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Estado del registro</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a10' value='".$id['reg_enable']."' ".$asd.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Permite repeticiones</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a11' value='".$id['repetir']."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>FicoPuntos</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a12' value='".$id['puntos']."' ".$asd.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Participantes máximos</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a6' value='".$id['max_participants']."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Ganadores máximos</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a7' value='".$id['max_winners']."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Fecha</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a8' placeholder='DD-MM-AAAA HH:MM:SS' value='".$id['start_date']."' ".$disabled.">
						</div>
					</div>
					<div class='form-group row'>
						<label for='example-text-input' class='col-2 col-form-label'>Fin registro</label>
						<div class='col-10'>
							<input class='form-control' type='text' name='a9' placeholder='DD-MM-AAAA HH:MM:SS' value='".$id['deadline']."' ".$disabled.">
						</div>
					</div>
					<button ".$disabled." type='submit' formmethod='post' class='btn btn-primary'>Guardar</button>
					</form>
					";

				}
			}

			function savereto() {
				if (!no_permission(2)) {
					if(isset($_GET['f']) AND $_GET['f']=='savegame') {
						GLOBAL $conn;
						$qry="UPDATE retos SET `name`=:a1 , `info`=:a2, `img`=:a3, `type`=:a4, `class`=:a5, `max_participants`=:a6, `max_winners`=:a7, `start_date`=:a8, `deadline`=:a9, `reg_enable`=:a10, `repetir`=:a11, `puntos`=:a12 WHERE `idx`=:id";
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
						$result->bindParam(':a10', $_POST['a10']);
						$result->bindParam(':a11', $_POST['a11']);
						$result->bindParam(':a12', $_POST['a12']);
						$result->execute();
					}
					setcookie("reto", null, -1);
					header("Refresh:0; url=http://localhost/intranet/admin.php?f=ver_retos");
				}
			}
 ?>

