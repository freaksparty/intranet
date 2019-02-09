<?php
	include "comun/vars.php";
	include "api/config.php";

	/*$qry="SELECT 
		id, nick, team, (SELECT name FROM teams_users as tu WHERE u.team=tu.id) as teamname, 
		(SELECT 
			SUM(points) 
			FROM points_users as pu 
			WHERE pu.user=u.id) as points_other,
		(SELECT 
			SUM(points) 
			FROM points_games as pg
			JOIN participants as p ON p.game = pg.id
			WHERE p.user=u.id AND pg.type_game=1 AND p.position != NULL) as points_games,
		(SELECT 
			SUM(points) 
			FROM points_games as pg
			JOIN participants as p ON p.game = pg.id
			WHERE p.user=u.id AND pg.type_game=2) as points_prod,
		(SELECT 
			SUM(points) 
			FROM points_games as pg
			JOIN participants as p ON p.game = pg.id
			WHERE p.user=u.id AND pg.type_game=3) as points_mini
		FROM users as u ORDER BY (points_other+points_games+points_prod+points_mini) DESC";
    $result=$conn->prepare($qry);
	$result->execute();
	
	$users=$result->fetchAll();

	$pointsTeam=array();*/
	$qry="SELECT id, nick, team,(SELECT name FROM teams_users as tu WHERE users.team=tu.id) as teamname FROM users";
	$result=$conn->prepare($qry);
	$result->execute();
	$users=$result->fetchAll();
	$pointsTeam=array();

	//print_r($users);

	foreach ($users as $user => $value) {
		$qry="SELECT sum(tt.points) as total
		FROM ( SELECT points 
		FROM points_games as pg, participants as p, games as g 
		WHERE pg.id=g.id AND p.game=pg.id AND g.type=1 AND p.user=:user AND p.position=pg.position) as tt";
		$result=$conn->prepare($qry);
		$result->bindParam(":user", $users[$user]['id']);
		$result->execute();
		$users[$user]['points_games']=$result->fetchColumn(0);
		//print_r($users[$user]['points_games']);
		$qry="SELECT sum(tt.points) 
   		FROM ( SELECT points 
        	FROM points_games as pg, participants as p, games as g 
        	WHERE pg.id=g.id AND p.game=pg.id AND g.type=2 AND p.user=:user AND p.position=pg.position) as tt";
		$result=$conn->prepare($qry);
		$result->bindParam(":user", $users[$user]['id']);
		$result->execute();
		$users[$user]['points_prod']=$result->fetchColumn(0);
		$qry="SELECT sum(tt.points) 
   		FROM ( SELECT points 
        	FROM points_games as pg, participants as p, games as g 
        	WHERE pg.id=g.id AND p.game=pg.id AND g.type=3 AND p.user=:user AND p.position=pg.position) as tt";
		$result=$conn->prepare($qry);
		$result->bindParam(":user", $users[$user]['id']);
		$result->execute();
		$users[$user]['points_mini']=$result->fetchColumn(0);
		$qry="SELECT sum(points) FROM points_users as pu WHERE user=:user";
		$result=$conn->prepare($qry);
		$result->bindParam(":user", $users[$user]['id']);
		$result->execute();
		$users[$user]['points_other']=$result->fetchColumn(0);
		//print_r($users[$user]);
		$users[$user]['total']=$users[$user]['points_mini']+$users[$user]['points_prod']+$users[$user]['points_games']+$users[$user]['points_other'];
	}
	//print_r($users);
	foreach ($users as $key => $row) {
  		$aux[$key] = $row['total'];
	}
	array_multisort($aux, SORT_DESC, $users);
?>
<!DOCTYPE html>
<html lang="es">
	<?php
		include ROOT."comun/head.php";
	?>
	<body>
		<?php
			include ROOT."comun/nav.php";
		?>
		<div class="container">
			<div class="row">
				<div class="col-sm-1 col-xs-12"></div>
				<div class="col-sm-10 col-xs-12">
					<div class="apuntar">
						<button user="3" game="2" class="pull-right btn btn-fol" id="change_view">Equipos</button>
						<a href="source.php"><button user="3" game="2" class="pull-right btn btn-fol" id="change_view">Mis puntos</button></a>
					</div>
					<h2 class='title'>CLASIFICACIÓN</h2>
					<table id="table-users" class="table table-hover table-striped table-responsive">
						<thead>
							<th></th>
							<th>Usuario</th>
							<th>Equipo</th>
							<th>Torneos</th>
							<th>Producciones</th>
							<th>Minijuegos</th>
							<th>Tongo</th>
							<th>Total</th>
						</thead>
						<tbody>
							<?php
								$i=1;
								foreach($users as $user){
									if(!isset($pointsTeam[$user['team']])){
										$pointsTeam[$user['team']]['games']=0;
										$pointsTeam[$user['team']]['prod']=0;
										$pointsTeam[$user['team']]['mini']=0;
										$pointsTeam[$user['team']]['other']=0;
									}
									$pointsTeam[$user['team']]['name']=$user['teamname'];
									$pointsTeam[$user['team']]['games']+=$user['points_games'];
									$pointsTeam[$user['team']]['prod']+=$user['points_prod'];
									$pointsTeam[$user['team']]['mini']+=$user['points_mini'];
									$pointsTeam[$user['team']]['other']+=$user['points_other'];
									if($user['id']==$_COOKIE['user_id']){
										$user['nick']="<strong>".$user['nick']."</strong>";
									}
							?>
							<tr class="source" user="<?=$user['id']?>">
								<td><?=$i?></td>
								<td><?=$user['nick']?></td>
								<td><?=$user['teamname']?></td>
								<td><?=$user['points_games']?></td>
								<td><?=$user['points_prod']?></td>
								<td><?=$user['points_mini']?></td>
								<td><?=$user['points_other']?></td>
								<td><?=$user['points_other']+$user['points_games']+$user['points_prod']+$user['points_mini']?></td>
							</tr>
							<?php
								$i++;
								}
							?>
						</tbody>
					</table>
					<table id="table-teams" class="table table-hover table-striped table-responsive hidden">
						<thead>
							<th></th>
							<th>Equipo</th>
							<th>Torneos</th>
							<th>Producciones</th>
							<th>Minijuegos</th>
							<th>Tongo</th>
							<th>Total</th>
						</thead>
						<tbody>
							<?php
								$i=1;
								foreach($pointsTeam as $team){
							?>
							<tr class="tsource" team="<?=$team['name']?>">
								<td><?=$i?></td>
								<td><?=$team['name']?></td>
								<td><?=$team['games']?></td>
								<td><?=$team['prod']?></td>
								<td><?=$team['mini']?></td>
								<td><?=$team['other']?></td>
								<td><?=$team['other']+$team['games']+$team['prod']+$team['mini']?></td>
							</tr>
							<?php
								$i++;
								}
							?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</body>
	<?php
		include ROOT."comun/libs.php";
	?>
</html>