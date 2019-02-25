<?php
    session_start();
    include "api/config.php";
    $qry="SELECT *, 
            (SELECT user FROM participants_retos as p WHERE p.user=:user AND p.reto=:id) as user,
            (SELECT done FROM participants_retos as p WHERE p.user=:user AND p.reto=:id) as done,
            (SELECT gana FROM participants_retos as p WHERE p.user=:user AND p.reto=:id) as gana,
            (SELECT enabled FROM participants_retos as p WHERE p.user=:user AND p.reto=:id) as enabled,
            (SELECT COUNT(*) FROM participants_retos as p WHERE p.reto=g.idx GROUP BY reto) as inscribed
            FROM retos as g
            WHERE idx=:id";
    $result=$conn->prepare($qry);
    $result->bindParam(':user', $_COOKIE['user_id']);
    $result->bindParam(':id', $_GET['id']);
    $result->execute();

    $game=$result->fetch();

    /*$class=$game['class'];
    $pByTeams=$game['min'];*/

    $query="SELECT img FROM retos WHERE idx=:id";
    $resa=$conn->prepare($query);
    $resa->bindParam(":id", $_GET['id']);
    $resa->execute();
    $image=$resa->fetchColumn();
    $img="images/".$image;
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
                            <img src=<?=$img?>>
                            <span class="text-center"><h2><?=$game['name']?></h2></span>
                        </div>
                        <div class='col-xs-12 descr'><p><?=utf8_encode($game['info'])?></p></div>
                        <?php
                            $now=strtotime(date("Y-m-d H:i:s"));
                            $date_game=strtotime($game['deadline']);
                            if($game['inscribed']==null) $game['inscribed']=0;
                            if((($game['done']==1 && $game['repetir']==1 && $game['gana']==0) || $game['user']==null) && $game['enabled']==1){
                                $disabled="";
                                if($date_game<$now) $disabled="disabled";
                        ?>
                        <div class="apuntar">
                            <?php
                                /*if($class==1 || $class==2)*/
                                   echo '<button user="'.$_COOKIE['user_id'].'" game="'.$game['idx'].'" class="pull-right btn btn-fol" id="register_reto" '.$disabled.'>APUNTARME</button>';
                                /*else
                                echo '<button user="'.$_COOKIE['user_id'].'" game="'.$game['idx'].'" class="pull-right btn btn-fol" id="register_game_team" '.$disabled.'>APUNTARNOS</button>';*/
                            ?>
                        </div>
                        <?php
                            }
                            if(true){
                        ?>
                        <div class='col-xs-12'>
                            <h4>Usuarios apuntados</h4>
                            <ul>
                                <?php
                                    $qry="SELECT u.nick, u.id, p.prioridad FROM users u, (SELECT user, prioridad, done, reg_date, gana FROM participants_retos WHERE reto=:id) p WHERE p.user=u.id AND p.gana=0 AND p.done=0 ORDER BY p.prioridad ASC, p.reg_date ASC;";
                                    $result=$conn->prepare($qry);
                                    $result->bindParam(':id', $_GET['id']);
                                    $result->execute();
                                
                                    $users=$result->fetchAll();
                                    foreach($users as $user){
                                        echo "<li>".$user["nick"]." (Intentos: ".$user["prioridad"].")</li>"; 
                                    }
                                ?>
                            </ul>
                            <h4>Perdedores</h4>
                            <ul>
                                <?php
                                    $qry="SELECT u.nick, u.id, p.prioridad FROM users u, (SELECT user, prioridad, done, reg_date, gana FROM participants_retos WHERE reto=:id) p WHERE p.user=u.id AND p.gana=0 AND p.done=1 ORDER BY p.prioridad ASC, p.reg_date ASC;";
                                    $result=$conn->prepare($qry);
                                    $result->bindParam(':id', $_GET['id']);
                                    $result->execute();
                                
                                    $users=$result->fetchAll();
                                    foreach($users as $user){
                                        $intento=$user["prioridad"]+1;
                                        echo "<li>".$user["nick"]." (Intentos: ".$intento.")</li>"; 
                                    }
                                ?>
                            </ul>
                            <h4>Ganadores</h4>
                            <ol>
                                <?php
                                    $qry="SELECT u.nick, u.id, p.prioridad FROM users u, (SELECT user, prioridad, idx, reg_date, gana FROM participants_retos WHERE reto=:id) p WHERE p.user=u.id AND p.gana!=0 ORDER BY p.prioridad ASC, p.gana ASC;";
                                    $result=$conn->prepare($qry);
                                    $result->bindParam(':id', $_GET['id']);
                                    $result->execute();
                                
                                    $users=$result->fetchAll();
                                    foreach($users as $user){
                                        $intento=$user["prioridad"]+1;
                                        echo "<li>".$user["nick"]." (Intentos: ".$intento.")</li>"; 
                                    }
                                ?>
                            </ol>
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