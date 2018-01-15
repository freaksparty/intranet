<?php
    session_start();
    include "comun/vars.php";

    if(!isset($_SESSION['user_id'])){
        header("Location: ". ROOT . "login.php");
    }
    else{
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
                <div class="col-sm-2 col-xs-12"></div>
                <div class="col-sm-8 col-xs-12">
                    <h2 class='title'>TUS ACTIVIDADES</h2>
                    <div class="row">
                        <?php
                            include "api/config.php";
                            global $conn;
                            $qry="SELECT g.* 
                                    FROM games as g 
                                    JOIN game_users as gu ON g.id=gu.id_game 
                                    WHERE id_user=:user
                                    ORDER BY date ASC";
                            $result=$conn->prepare($qry);
                            $result->bindParam(':user', $_SESSION['user_id']);
                            $result->execute();

                            $mygames= $result->fetchAll();
                            
                            foreach($mygames as $game){
                        ?>
                            <div class="col-md-4 col-sm-6 col-xs-12 actividad text-center" actividad="<?=$game['id']?>">
                                <img src="images/<?=$game['image']?>"/>
                                <h3><?=utf8_encode($game['title'])?></h3>
                                <small><?=utf8_encode($game['day_week'])?> a las <?=$game['hour']?></small>
                            </div>
                        <?php
                            }
                        ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-2 col-xs-12"></div>
                <div class="col-sm-8 col-xs-12">
                    <h2 class='title'>ACTIVIDADES DE HOY</h2>
                    <div class="row">
                        <?php
                            $qry="SELECT *
                                    FROM games
                                    WHERE DAY(date)=DAY(NOW())
                                    ORDER BY date ASC";
                            $result=$conn->prepare($qry);
                            $result->execute();

                            $games= $result->fetchAll();
                            
                            foreach($games as $game){
                        ?>
                            <div class="col-md-4 col-sm-6 col-xs-12 actividad text-center" actividad="<?=$game['id']?>">
                                <img src="images/<?=$game['image']?>"/>
                                <h3><?=$game['title']?></h3>
                                <small>Hora <?=$game['hour']?></small>
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
        include ROOT."comun/libs.php";
    ?>
</html>

<?php } ?>