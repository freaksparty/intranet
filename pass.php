<!DOCTYPE html>
<html>
    <?php
        include "comun/vars.php";
        include ROOT."comun/head.php";
    ?>
    <body>
        <div class="container">
            <div class="row">
                <div class="col-sm-2 col-xs-12"></div>
                <div class="col-sm-8 col-xs-12">
                    <form id="setpass" method="POST" action="api/Users.php?f=setpass">
                        <div class="form-group pass">
                            <label for="pass">Establecer contraseña(mínimo 4 caracteres)</label>
                            <input type="password" class="form-control" pattern=".{4,}" required name="pass" id="pass_ppal" placeholder="Introduce tu contraseña(sin restricciones)">
                        </div>
                        <div class="form-group pass-rep">
                            <input type="password" class="form-control" required id="pass-rep_ppal" placeholder="Repite tu contraseña">
                        </div>
                        <input type="text" name="cryp" value="<?=$_GET['wawawa']?>" hidden/>
                        <button type="submit" class="btn btn-primary" disabled>Confirmar</button>
                    </form>
                </div>
                <div class="col-sm-2 col-xs-12"></div>
            </div>
        </div>
    </body>
    <?php
        include ROOT."comun/libs.php";
    ?>
</html>