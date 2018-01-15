<?php
    session_start();
    include "api/config.php";
    $qry="SELECT *, 
            (SELECT id_user FROM game_users as gu WHERE gu.id_game=g.id) as user,
            (SELECT COUNT(*) FROM game_users as gu WHERE gu.id_game=g.id GROUP BY id_game) as inscribed
            FROM games as g
            WHERE id=:id";
    $result=$conn->prepare($qry);
    $result->bindParam(':id', $_GET['id']);
    $result->execute();

    $game=$result->fetch();
?>

<!DOCTYPE html>
<html lang="es">
    <?php
        include "./comun/head.php";
    ?>
    <body>
        <?php
            include "./comun/nav.php";
        ?>
        <div class="alert"></div>
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-xs-12"></div>
                <div class="col-sm-8 col-xs-12">
                    <div class="row">
                        <div class="header-actividad">
                            <img src="img/ardilla.jpg"/>
                            <span class="text-center"><h2><?=$game['title']?></h2></span>
                        </div>
                        <div class='col-xs-12 descr'><p><?=$game['description']?></p></div>
                        <?php
                            $now=strtotime(date("Y-m-d H:i:s"));
                            $date_game=strtotime($game['date_end_reg']);
                            if($game['inscribed']==null) $game['inscribed']=0;
                            if($game['user']==null){
                                $disabled="";
                                if($date_game<$now || $game['max']<$game['inscribed']) $disabled="disabled";
                        ?>
                        <div class="apuntar">
                            <?php
                             echo '<button user="'.$_SESSION['user_id'].'" game="'.$game['id'].'" class="pull-right btn btn-fol" id="register_game" '.$disabled.'>APUNTARME</button>';
                            ?>
                        </div>
                        <?php
                            }
                            if($game['user']!=null || $date_game<$now || $game['max']<$game['inscribed']){
                        ?>
                        <div class='col-xs-12'>
                            <h4>Usuarios apuntados</h4>
                            <ul>
                                <?php
                                    $qry="SELECT u.nick as nick
                                            FROM game_users as gu
                                            JOIN users as u ON u.id=gu.id_user
                                            WHERE id_game=:id";
                                    $result=$conn->prepare($qry);
                                    $result->bindParam(':id', $_GET['id']);
                                    $result->execute();
                                
                                    $users=$result->fetchAll();
                                    foreach($users as $user){
                                        echo "<li>".$user['nick']."</li>";
                                    }
                                ?>
                            </ul>
                        </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php
        include "comun/libs.php";
    ?>
</html>