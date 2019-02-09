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
	function get_user_nick($id) {
					//if (!no_permission(2)) {
						GLOBAL $conn;
						$qry="SELECT nick FROM users WHERE id=:id";
						$result=$conn->prepare($qry);
						$result->bindParam(":id", $id);
						$result->execute();
						$nick=$result->fetchColumn();

						return $nick;
					}
				//}
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
	function get_game_name2($id) {
				GLOBAL $conn;
				$qry="SELECT name FROM games WHERE id=:id";
				$result=$conn->prepare($qry);
				$result->bindParam(":id", $id);
				$result->execute();
				$name=$result->fetchColumn();
				return $name;
		}

		function points_source2($USERID) {
				if (isset($_POST['user'])) {
					$users=$_POST['user'];
				} else {
					$users[0]=$USERID;
				}


				foreach ($users as $user)
					GLOBAL $conn;
					$qry="SELECT pg.points, p.game, p.position FROM participants as p, points_games as pg WHERE ((p.position=pg.position AND pg.points>0) AND p.user=:id) AND p.game=pg.id ";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $user);
					$result->execute();

					echo "
						<table class='table table-striped'>
							<thead>
								<tr>
									<th>
										Usuario
									</th>
									<th>
										Juego o Motivo
									</th>
									<th>
										Posición
									</th>
									<th>
										Cantidad
									</th>
								</tr>
							</thead>
							<tbody>
						";
					while ($res=$result->fetch()) {
						echo "
							<tr>
								<th scope='row'>
									".get_user_nick($user)."
								</th>
								<td>
									<b>".get_game_name2($res['game'])."</b>
								</td>
								<td>
									<b>".$res['position']."</b>
								</td>
								<td>
									".$res["points"]."
								</td>
							</tr>
						";
					}
					GLOBAL $conn;
					$qry="SELECT * FROM points_users WHERE user=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $user);
					$result->execute();
					
					while ($res=$result->fetch()) {
						echo "<tr>
							<th scope='row'>
								".get_user_nick($user)."
							</th>
							<td>
								".$res['description']."
							</td>
							<td>
								 TONGO
							</td>
							<td>
								".$res["points"]."
							</td>
						</tr>
						";
					}
					echo "</tbody></table>";
		}
		function team_points_sources($team) {
			GLOBAL $conn;
			$qry="SELECT u.id FROM users u, (SELECT tu.id FROM teams_users tu WHERE tu.name=:team) t WHERE t.id=u.team;";
			$tusers=$conn->prepare($qry);
			$tusers->bindParam(':team', $team);
			$tusers->execute();

			echo "
				<table class='table table-striped'>
					<thead>
						<tr>
							<th>
								Usuario
							</th>
							<th>
								Juego o Motivo
							</th>
							<th>
								Posición
							</th>
							<th>
								Cantidad
							</th>
						</tr>
					</thead>
					<tbody>
				";
			while($user=$tusers->fetch()['id']) {
				GLOBAL $conn;
				$qry="SELECT pg.points, p.game, p.position FROM participants as p, points_games as pg WHERE ((p.position=pg.position AND pg.points>0) AND p.user=:id) AND p.game=pg.id ";
				$result=$conn->prepare($qry);
				$result->bindParam(':id', $user);
				$result->execute();
				while ($res=$result->fetch()) {
					echo "
						<tr>
							<th scope='row'>
								".get_user_nick($user)."
							</th>
							<td>
								<b>".get_game_name2($res['game'])."</b>
							</td>
							<td>
								<b>".$res['position']."</b>
							</td>
							<td>
								".$res["points"]."
							</td>
						</tr>
					";
				}
				GLOBAL $conn;
					$qry="SELECT * FROM points_users WHERE user=:id";
					$result=$conn->prepare($qry);
					$result->bindParam(':id', $user);
					$result->execute();
					
					while ($res=$result->fetch()) {
						echo "<tr>
							<th scope='row'>
								".get_user_nick($user)."
							</th>
							<td>
								".$res['description']."
							</td>
							<td>
								 TONGO
							</td>
							<td>
								".$res["points"]."
							</td>
						</tr>
						";
					}
			}
		}
?>