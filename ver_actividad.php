<?php
    session_start();
    include "api/config.php";
    $qry="SELECT *, 
            (SELECT user FROM participants as p WHERE p.user=:user AND p.game=:id) as user,
            (SELECT COUNT(*) FROM participants as p WHERE p.game=g.id GROUP BY game) as inscribed
            FROM games as g
            WHERE id=:id";
    $result=$conn->prepare($qry);
    $result->bindParam(':user', $_COOKIE['user_id']);
    $result->bindParam(':id', $_GET['id']);
    $result->execute();

    $game=$result->fetch();

    $class=$game['class'];
    $pByTeams=$game['min'];

    $query="SELECT image FROM games WHERE id=:id";
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
                        <div class='col-xs-12 descr'><p><?=utf8_encode($game['description'])?></p></div>
                        <?php
                            $now=strtotime(date("Y-m-d H:i:s"));
                            $date_game=strtotime($game['date_max']);
                            if($game['inscribed']==null) $game['inscribed']=0;
                            if($game['user']==null){
                                $disabled="";
                                if($date_game<$now) $disabled="disabled";
                        ?>
                        <div class="apuntar">
                            <?php
                                if($class==1 || $class==2)
                                    echo '<button user="'.$_COOKIE['user_id'].'" game="'.$game['id'].'" class="pull-right btn btn-fol" id="register_game" '.$disabled.'>APUNTARME</button>';
                                else
                                echo '<button user="'.$_COOKIE['user_id'].'" game="'.$game['id'].'" class="pull-right btn btn-fol" id="register_game_team" '.$disabled.'>APUNTARNOS</button>';
                            ?>
                        </div>
                        <?php
                            }
                            if($game['user']!=null || $date_game<$now){
                        ?>
                        <div class='col-xs-12'>
                            <h4>Usuarios apuntados</h4>
                            <ul>
                                <?php
                                    $qry="SELECT u.nick as nick, (SELECT name FROM teams_participants as tp WHERE tp.id=p.team) as teamname, p.team as team
                                            FROM participants as p
                                            JOIN users as u ON u.id=p.user
                                            WHERE game=:id
                                            ORDER BY p.team";
                                    $result=$conn->prepare($qry);
                                    $result->bindParam(':id', $_GET['id']);
                                    $result->execute();
                                
                                    $users=$result->fetchAll();
                                    $teamant="";
                                    foreach($users as $user){
                                        if($class!=1 && $class!=2){
                                            if($teamant!=$user['team']){
                                                if($teamant!=""){
                                                    echo "</ul></li>";
                                                }
                                                echo "<li>".$user['teamname']."<ul>";
                                            }
                                        }
                                        echo "<li>".$user['nick']."</li>";
                                        $teamant=$user['team'];
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

<?php
global $conn;
$qryUsers="SELECT id, nick
        FROM users
        WHERE id!=:id AND id NOT IN (SELECT id FROM participants WHERE game=:game)";
$resultUsers=$conn->prepare($qryUsers);
$resultUsers->bindParam(':id', $_COOKIE['user_id']);
$resultUsers->bindParam(':game', $game['id']);
$resultUsers->execute();

$parts=$resultUsers->fetchAll();
?>

        <div class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Apuntar equipo</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Este torneo se jugará por equipos, escoge a tu/s compañero/s y se le/s apuntará automáticamente. Si tienes algún problema, avisa en información:</p>
                        <form id="team_games_parts" action="register_game_team.php" method="POST">
                            <div class="form-group name_team">
                                <label for="name_team">Nombre de equipo</label>
                                <input type="text" class="form-control" name="name_team" id="name_team" placeholder="Introduce el nombre del equipo" required>
                            </div>
                            <?php
                                for($i=0; $i<$pByTeams; $i++){
                            ?>
                            <div class="form-group part_team">
                                <label for="part_team">Compañero</label>
                                <select class="list-users" name="part_<?php echo ($i+1);?>" required>
                                    <option selected disabled>Nº <?php echo ($i+1);?></option>
                                    <?php
                                        foreach($parts as $part){
                                    ?>
                                            <option value="<?php echo $part['id'];?>"><?php echo $part['nick'];?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                            <?php
                                }
                            ?>
                            <input name="user_id" value="<?php echo $_COOKIE['user_id'] ?>" hidden/>
                            <input name="game_id" value="<?php echo $_GET['id'] ?>" hidden/>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="register_game_by_team" class="btn btn-primary">Confirmar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php
        include "comun/libs.php";
    ?>
</html>