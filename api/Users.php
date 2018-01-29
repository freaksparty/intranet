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
				<p>Si has llegado hasta aquí es que estoy haciendo las cosas bien (OLEEÉ). Pulsa en este enlace para establecer tu contraseña y así disfrutar de est evento.</p>
				<p><a href="http://localhost/intranet/pass.php?wawawa='.$crypted.'" target="_blank">NO PULSAR</a></p>
				<p>Muchas gracias '.$nick.'!!</p>
			</body>
			</html>
			';
			$headers = "MIME-Version: 1.0\r\n"; 
			$headers .= "Content-type: text/html; charset=utf-8\r\n";
			$headers .= "From: Freaksparty < info@freaksparty.com >\r\n";

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

			$_SESSION['user_id']=$user['id'];
			$_SESSION['user_nick']=$nick;

			header("Location: http://localhost/intranet");
		}

		function register_game($user, $game){
			global $conn;
			$qry="SELECT COUNT(*) as num FROM game_users WHERE id_game = :game GROUP BY id_game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();

			$inscribed=$result->fetch()['num'];
			if($inscribed==null) $inscribed=0;
			$qry="SELECT max, date_end_reg FROM games WHERE id = :game";
			$result=$conn->prepare($qry);
			$result->bindParam(':game', $game);
			$result->execute();

			$game_info=$result->fetch();
			$max=$game_info['max'];
			$date_end=strtotime($game_info['date_end_reg']);
			if($inscribed<$max){
				$now=strtotime(date("Y-m-d H:i:s"));
				if($date_end>$now){
					$qry="INSERT INTO game_users(id_game,id_user) VALUES (:game, :user)";
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
	}

	$user=new Users();
	$pet=$_GET['f'];

	switch($pet){
		case "is_generated": $user->is_generated($_POST['nick']);break;
		case "send_email": $user->send_email($_POST['nick']);break;
		case "setpass": $user->setpass($_POST['pass'], $_POST['cryp']);break;
		case "login": $user->login($_POST['nick'], $_POST['pass']);break;
		case "register_game": $user->register_game($_POST['id_user'], $_POST['id_game']);break;
		default: break;
	}
?>