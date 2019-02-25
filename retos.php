<?php
    session_start();
    include "comun/vars.php";

    if(!isset($_COOKIE['user_id'])){
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
                    <h2 class='title'>TUS RETOS</h2>
                    <div class="row">
                        <?php
                            include "api/config.php";
                            global $conn;
                            $qry="SELECT r.* FROM retos r, (SELECT distinct reto FROM participants_retos WHERE user=:user) p where r.idx=p.reto";
                            $result=$conn->prepare($qry);
                            $result->bindParam(':user', $_COOKIE['user_id']);
                            $result->execute();

                            $mygames= $result->fetchAll();

                            function day_of_week($date){
                                switch ($date){
                                    case 1: return "Lunes";break;
                                    case 2: return "Martes";break;
                                    case 3: return "Miércoles";break;
                                    case 4: return "Jueves";break;
                                    case 5: return "Viernes";break;
                                    case 6: return "Sábado";break;
                                    case 0: return "Domingo";break;
                                    default: return "";
                                } 
                            }
                            
                            foreach($mygames as $game){
                        ?>
                            <div class="col-md-4 col-sm-6 col-xs-12 reto text-center" reto="<?=$game['idx']?>">
                                <img src="images/<?=$game['img']?>"/>
                                <h3><?=utf8_encode($game['name'])?></h3>
                                <small><?=day_of_week(date('w', strtotime($game['deadline'])));?> a las <?=date('H:i', strtotime($game['deadline']));?></small>
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
                    <h2 class='title'>RETOS QUE TERMINAN HOY</h2>
                    <div class="row">
                        <?php
                            $qry="SELECT *
                                    FROM retos
                                    WHERE DAY(deadline)=DAY(NOW()) AND reg_enable=1
                                    ORDER BY deadline ASC";
                            $result=$conn->prepare($qry);
                            $result->execute();

                            $games= $result->fetchAll();
                            
                            foreach($games as $game){
                        ?>
                            <div class="col-md-4 col-sm-6 col-xs-12 reto text-center" reto="<?=$game['idx']?>">
                                <img src="images/<?=$game['img']?>"/>
                                <h3><?=$game['name']?></h3>
                                <small>Hora <?=date("H:i",strtotime($game['deadline']));?></small>
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
                    <h2 class='title'>TODOS LOS RETOS</h2>
                    <div class="row">
                        <?php
                            $qry="SELECT *
                                    FROM retos
                                    WHERE reg_enable=1
                                    ORDER BY deadline ASC";
                            $result=$conn->prepare($qry);
                            $result->execute();

                            $games= $result->fetchAll();
                            
                            foreach($games as $game){
                        ?>
                            <div class="col-md-4 col-sm-6 col-xs-12 reto text-center" reto="<?=$game['idx']?>">
                                <img src="images/<?=$game['img']?>"/>
                                <h3><?=$game['name']?></h3>
                                <small>Hora <?=date("H:i",strtotime($game['deadline']));?></small>
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