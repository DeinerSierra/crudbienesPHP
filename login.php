<?php
    require 'includes/config/db.php';
    require './includes/funciones.php';
    $db = conectarDB();
    $errores = [];
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        $email =mysqli_real_escape_string($db,filter_var( $_POST['email'],FILTER_VALIDATE_EMAIL));
        $password =mysqli_real_escape_string($db, $_POST['password']);
        if (!$email) {
            $errores[]='Debes añadir un email valido';
        }
        if (!$password) {
            $errores[]='La contraseña es obligatoria';
        }
        if (empty($errores)) {
            //revisar la existencia del user
            $query = "SELECT * FROM usuarios WHERE email='{$email}'";
            $res = mysqli_query($db, $query);
            if ($res->num_rows) {
                //verificar el password
                $usuarios = mysqli_fetch_assoc($res);
                $auth = password_verify($password, $usuarios['password']);
                if ($auth) {
                    //iniciamos la sesion
                    session_start();
                    //llenamos la superglobal session con info del usuario
                    $_SESSION['usuario'] = $usuarios['email'];
                    $_SESSION['login'] = true;
                    //redireccion
                    header('Location: /admin');

                } else {
                    $errores[]='La contraseña es incorrecta';
                }
                
            }else {
                $errores[]='Usuario no existe verifica tu email';
            }
        }
    }
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Iniciar Sesion</h1>
        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <p><?php echo $error ;?> </p>
            </div>
        <?php endforeach; ?>
        <form class="formulario" method="POST">
            <fieldset>
                <legend>Email y Password</legend>

                <label for="email">E-mail</label>
                <input type="email" name="email" placeholder="Tu Email" id="email" require>

                <label for="password">Password</label>
                <input type="password" name="password" placeholder="Tu password" id="password" require>

            </fieldset>
            <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
        </form>
    </main>
<?php
    incluirTemplate('footer');
?>