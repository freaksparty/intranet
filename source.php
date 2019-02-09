
<?php
    include "comun/vars.php";
    require_once "api/functions.php"
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
                    <?php
                        if(isset($_GET['user_id'])) {
                            points_source2($_GET['user_id']);
                        } else if (isset($_GET['team'])){
                            team_points_sources($_GET['team']);
                        } else {
                            points_source2($_COOKIE['user_id']);
                        }
                    ?>
                </div>
            </div>
        </div>
    </body>
    <?php
        include ROOT."comun/libs.php";
    ?>
</html>