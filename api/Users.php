<?php
	session_start();
	include "config.php";

	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

	class Users{
		function is_generated($nick){
			global $conn;
			$qry="SELECT cryp FROM users WHERE nick LIKE :nick";
			$result=$conn->prepare($qry);
			$result->bindParam(':nick', $nick);
			$result->execute();

			if($result->rowCount()>0){
				print_r( $result->fetchAll()[0]['cryp'] );
			}
			else{
				echo "Error";
			}
		}

		function send_email($nick){
			global $conn;
			$qry="SELECT email FROM users WHERE nick LIKE :nick";
			$result=$conn->prepare($qry);
			$result->bindParam(':nick', $nick);
			$result->execute();

			$email=$result->fetchAll()[0]['email'];

			$crypted=md5($email.$nick."FICONLAN");

			$upd="UPDATE users SET cryp=:crypted WHERE nick LIKE :nick";
			$prep=$conn->prepare($upd);
			$prep->bindParam(':crypted', $crypted, PDO::PARAM_STR);
			$prep->bindParam(':nick', $nick, PDO::PARAM_STR);
			$prep->execute();

			$title    = 'Crear contraseña para la intranet de la FicOnLan';
			$mensaje   = '
			<!DOCTYPE html>
			<html lang="es">
			<head>
				<meta charset="UTF-8">
				<title>Intranet</title>
			</head>
			<body>
				<p>Hola '.$nick.',</p>
				<p>Si has llegado hasta aquí es que estoy haciendo las cosas bien (OLEEÉ). Pulsa en este enlace para establecer tu contraseña y así disfrutar de este evento.</p>
				<p><a href="http://localhost/intranet/pass.php?wawawa='.$crypted.'" target="_blank">NO PULSAR</a></p>
				<p>Muchas gracias '.$nick.'!!</p>
			</body>
			</html>
			';
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "From: FicOnLan < no-responder@ficonlan.es>\r\n";

			echo mail($email, $title, $mensaje, $headers);
		}

		function setpass($pass, $cryp){
			global $conn;
			$qry="UPDATE users SET pass=:pass WHERE cryp LIKE :cryp";
			$result=$conn->prepare($qry);
			$result->bindParam(':pass', md5($pass));
			$result->bindParam(':cryp', $cryp);
			$result->execute();

			header("Location: http://localhost/intranet/login.php");
		}

		function login($nick, $pass){
			global $conn;
			$qry="SELECT id FROM users WHERE nick LIKE :nick AND pass LIKE :pass";
			$result=$conn->prepare($qry);
			$result->bindParam(':nick', $nick);
			$pass=md5($pass);
			$result->bindParam(':pass', $pass);
			$result->execute();

			$user=$result->fetch();

			setcookie('user_id', $user['id'], time()+(3600*24*4), "/");
			setcookie('user_nick', $nick, time()+(3600*24*4), "/");

			header("Location: http://localhost/intranet/");
		}

		function register_game($user, $game){
			global $conn;
			$qry="SELECT COUNT(*) as num FROM participants WHERE game = :game GROUP BY game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();

			$inscribed=$result->fetch()['num'];
			if($inscribed==null) $inscribed=0;
			$qry="SELECT max, date_max FROM games WHERE id = :game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();
			
			$game_info=$result->fetch();
			$max=$game_info['max'];
			$date_end=strtotime($game_info['date_max']);
			if($inscribed<$max){
				$now=strtotime(date("Y-m-d H:i:s"));
				if($date_end>$now){
					$qry="INSERT INTO participants(game, user) VALUES (:game, :user)";
					$result=$conn->prepare($qry);
					$result->bindParam(':game', $game);
					$result->bindParam(':user', $user);
					$result->execute();

					echo 0;
				}
				else{
					echo -2;
				}
			}
			else{
				echo -1;
			}
		}

		function register_reto($user, $game){
			global $conn;
			$qry="SELECT COUNT(*) as num FROM participants_retos WHERE reto = :game GROUP BY reto";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();

			$inscribed=$result->fetch()['num'];
			if($inscribed==null) $inscribed=0;
			$qry="SELECT max_participants, deadline FROM retos WHERE idx = :game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();
			
			$game_info=$result->fetch();
			$max=$game_info['max_participants'];
			$date_end=strtotime($game_info['deadline']);
			if($inscribed<$max){
				$now=strtotime(date("Y-m-d H:i:s"));
				if($date_end>$now){
					$qry="INSERT INTO participants_retos(reto, user) VALUES (:game, :user)";
					$result=$conn->prepare($qry);
					$result->bindParam(':game', $game);
					$result->bindParam(':user', $user);
					$result->execute();

					echo 0;
				}
				else{
					echo -2;
				}
			}
			else{
				echo -1;
			}
		}

		function register_game_team($game, $users, $team){
			global $conn;
			$qry="SELECT COUNT(*) as num FROM participants WHERE game = :game GROUP BY game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();

			$inscribed=$result->fetch()['num'];
			if($inscribed==null) $inscribed=0;
			$qry="SELECT max, date_max, min FROM games WHERE id = :game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();
			
			$game_info=$result->fetch();
			$max=$game_info['max']*$game_info['min'];
			$date_end=strtotime($game_info['date_max']);
			if($inscribed<$max){
				$now=strtotime(date("Y-m-d H:i:s"));
				if($date_end>$now){
					$qry="INSERT INTO teams_participants(name) VALUES (:team)";
						$result=$conn->prepare($qry);
						$result->bindParam(':team', $team);
						$result->execute();
						$team_id=$conn->lastInsertId();
					foreach($users as $user){	
						$qry="INSERT INTO participants(game, user, team) VALUES (:game, :user, :team)";
						$result=$conn->prepare($qry);
						$result->bindParam(':game', $game);
						$result->bindParam(':user', $user);
						$result->bindParam(':team', $team_id);
						$result->execute();
					}

					echo 0;
				}
				else{
					echo -2;
				}
			}
			else{
				echo -1;
			}
		}
	}

	$user=new Users();
	$pet=$_GET['f'];

	switch($pet){
		case "is_generated": $user->is_generated($_POST['nick']);break;
		case "send_email": $user->send_email($_POST['nick']);break;
		case "setpass": $user->setpass($_POST['pass'], $_POST['cryp']);break;
		case "login": $user->login($_POST['nick'], $_POST['pass']);break;
		case "register_game": $user->register_game($_POST['id_user'], $_POST['id_game']);break;
		case "register_reto": $user->register_reto($_POST['id_user'], $_POST['id_game']);break;
		case "register_game_team": 
			$users=array();
			$users[]=$_POST["user_id"];
			$i=1;
			while(isset($_POST["part_".$i])){	
				$users[]=$_POST["part_".$i];
				$i++;
			}
			
			$user->register_game_team($_POST['game_id'], $users, $_POST["name_team"]);
		break;
		default: break;
	}
?>