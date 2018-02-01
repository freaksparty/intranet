<?php
	include './api/config.php';
	include './api/functions.php';
	require_once "admin/games.php";
	require_once "admin/config.php";

	function listusers() {
		if (!no_permission(2)) {
			global $conn;
			$qry="SELECT * FROM  users";
			$result=$conn->prepare($qry);
			$result->execute();
			$users=$result->fetchALL();

			$qry="SELECT * FROM  roles";
			$result=$conn->prepare($qry);
			$result->execute();
			$roles=$result->fetchALL();

			$qry="SELECT * FROM  teams_users";
			$result=$conn->prepare($qry);
			$result->execute();
			$teams=$result->fetchALL();

			$pt=points_total();

			echo "
				<table border='1'>
				<tr>
					<td>
						ID
					</td>
					<td>
						Nick
					</td>
					<td>
						Email
					</td>
					<td>
						Equipo
					</td>
					<td>
						Rol
					</td>
					<td>
						FICOnPuntos
					</td>
					<td>
						Seleccionar
					</td>
				</tr>
			";
			echo "<form method='post' action='./admin.php?f=usermngm'>";
			foreach ($users as $user) {
				echo "
					<tr>
						<td>
							".$user[0]."
						</td>
						<td>
							".$user[1]."
						</td>
						<td>
							".$user[2]."
						</td>
						<td class=".$teams[$user[4]-1][2].">
							".$teams[$user[4]-1][1]."
						</td>
						<td>
							".$roles[$user[5]-1][1]."
						</td>
						<td>
							".$pt[$user[0]]."
						</td>
						<td>
							<input type='checkbox' name='user[]' value='".$user[0]."'>
						</td>
					</tr>	
				";
			}
			echo "</table>";
			echo "<button type='submit' formmethod='post' class='btn btn-primary' name='listuser' value='FOP'>Dar puntos</button></form>";
			//echo "<button type='submit' formmethod='post' class='btn btn-primary' name='listuser' value='edit'>Editar</button>";
			//echo "<button type='submit' formmethod='post' class='btn btn-primary' name='listuser' value='edit'>Editar</button>";
		}
	}
	function usermngm() {
		if (!no_permission(2)) {
			if (isset($_POST['listuser']) && $_POST['listuser']==='FOP') {
				$USERS=serialize($_POST['user']);
				setcookie("user", $USERS, time()+3600);
				$_COOKIE['user']=$USERS;
				FOP();
			}
		}
	}
	function FOP() {
		if (!no_permission(2)) {
			GLOBAL $conn;
			print_r($_COOKIE);
			$USERS=unserialize($_COOKIE['user']);
			if (is_array($USERS)) {
				foreach ($USERS as $id) {
					$qry="SELECT nick FROM users WHERE id=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $id);
					$result->execute();
					$nick[$id]=$result->fetchColumn();
				}
			} else {
				$id=$USERS;
				$qry="SELECT nick FROM users WHERE id=:id";
				$result=$conn->prepare($qry);
				$result->bindParam(':id', $id);
				$result->execute();
				$nick[$id]=$result->fetchColumn();
			}

			echo "
				<table>
					<tr>
						<td>
							ID
						</td>
						<td>
							Nick
						</td>
						<td>
							Cantidad de puntos
						</td>
						<td>
							Motivo
						</td>
					</tr>
			";
			
			echo "<form method='post' action='http://localhost/intranet/admin.php?f=give_FOP'>";
			if (is_array($USERS))	{
				foreach ($USERS as $id) {
					echo "
						<tr>
							<td>
								<input type='text' name='id[]' readonly='readonly' value='".$id."'>
							</td>
							<td>
								<input type='text' name='a3[]' readonly='readonly' value='".$nick[$id]."'>
							</td>
							<td>
								<input type='text' name='a1[]'>
							</td>
							<td>
								<input type='text' name='a2'>
							</td>
						</tr>
					";
				}
			} else {
				echo "
					<tr>
						<td>
							<input type='text' name='id' readonly='readonly' value='".$id."'>
						</td>
						<td>
							<input type='text' name='a3' readonly='readonly' value='".$nick[$id]."'>
						</td>
						<td>
							<input type='text' name='a1'>
						</td>
						<td>
							<input type='text' name='a2'>
						</td>
					</tr>
				";
			}
			echo "</table><br><button type='submit' formmethod='post' class='btn btn-primary'>Dar puntos</button></form>";
			setcookie("user", null, -1);
		}
	}
	function give_FOP() {
		if(!no_permission(2)) {
			if (!is_array($_POST['id'])) {
				GLOBAL $conn;
				$qry="INSERT INTO points_users (`user`, `points`, `description`) VALUES (:id, :a1, :a2)";
				$result=$conn->prepare($qry);
				$result->bindParam(':id', $_POST['id']);
				$result->bindParam(':a1', $_POST['a1']);
				$result->bindParam(':a2', $_POST['a2']);
				$result->execute();
				header("Refresh:0; url=http://localhost/intranet/admin.php?f=listusers");
			} else {
				foreach ($_POST['id'] as $k=>$val) {
					GLOBAL $conn;
					$qry="INSERT INTO points_users (`user`, `points`, `description`) VALUES (:id, :a1, :a2)";
					$result=$conn->prepare($qry);
					$a1=$_POST['a1'][$k];
					$a2=$_POST['a2'][$k];
					$result->bindParam(':id', $val);
					$result->bindParam(':a1', $a1);
					$result->bindParam(':a2', $a1);
					$result->execute();
					header("Refresh:0; url=http://localhost/intranet/admin.php?f=listusers");
				}
			}
		}
	}
?>