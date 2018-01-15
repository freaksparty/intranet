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
                    <form id="login" method="POST" action="api/Users.php?f=login">
                        <div class="form-group nick">
                            <label for="nick">Login</label>
                            <input type="text" class="form-control" name="nick" id="nick_login" placeholder="Introduce tu nick">
                            <div class="invalid-feedback hidden">
                                No existe este nick.
                            </div>
                        </div>
                        <div class="form-group pass hidden">
                            <input type="password" class="form-control" name="pass" id="pass_login" placeholder="Introduce tu contraseña">
                            <div class="invalid-feedback hidden">
                                La contraseña es incorrecta
                            </div>
                        </div>
                        <button id="next_login" class="btn btn-primary">Siguiente</button>
                        <button type="submit" id="action_login" class="btn btn-warning hidden">Entrar</button>
                    </form>
                </div>
                <div class="col-sm-2 col-xs-12"></div>
            </div>
        </div>
        <div class="modal fade">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Recuperar contraseña</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>¿Es esta la primera vez que entras en la intranet? Si es así bienvenido, pulsa en continuar para que te enviemos al correo las instrucciones para conseguir tu contraseña. Si no... UPS!! La hemos liao pollito. Dirígete a información y pregunta por quien programó esta mierda.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="continue-login" class="btn btn-primary">Continuar</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Protestar</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
    <?php
        include ROOT."comun/libs.php";
    ?>
</html>