<?php
    require '../../includes/funciones.php';
    require '../../includes/config/db.php';
    $auth = autenticado();
    if (!$auth) {
        header('Location: /');
    }
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header('Location: /admin');
    }
    
    // Utilizar la función de conexión
    $db = conectarDB();
    $consulta = "SELECT * FROM propiedades WHERE id={$id}";
    $resPropiedad = mysqli_query($db, $consulta);
    $propiedad = mysqli_fetch_assoc($resPropiedad);
    //consultar los vendedores
    $consulta = "SELECT * FROM vendedores";
    $res = mysqli_query($db, $consulta);
    
    $errores = [];
    //obtener los valores del form a traves de la super global POST
    $titulo = $propiedad['titulo'];
    $precio = $propiedad['precio'];
    $descripcion = $propiedad['descripcion'];
    $habitaciones = $propiedad['habitaciones'];
    $wc = $propiedad['wc'];
    $estacionamiento = $propiedad['estacionamiento'];
    $vendedores_id = $propiedad['vendedores_id'];
    $imagenPropiedad = $propiedad['imagen'];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //obtener los valores del form a traves de la super global POST
        //mysqli_real_escape_string sanitiza los datos
        $titulo =mysqli_real_escape_string($db,$_POST['titulo']);
        $precio =mysqli_real_escape_string($db,$_POST['precio']);
        $descripcion =mysqli_real_escape_string($db,$_POST['descripcion']);
        $habitaciones =mysqli_real_escape_string($db,$_POST['habitaciones']);
        $wc =mysqli_real_escape_string($db,$_POST['wc']);
        $estacionamiento =mysqli_real_escape_string($db,$_POST['estacionamiento']);
        $vendedores_id =mysqli_real_escape_string($db,$_POST['vendedor']);
        $creado = date('Y-m-d');
        //obtener los datos de la imagen
        $imagen = $_FILES['imagen'];
        //validar los campos que no esten vacios
        if (!$titulo) {
            $errores[] = 'Debes añadir un titulo';
        }
        if (!$precio) {
            $errores[] = 'Debes añadir un precio';
        }
        if (strlen($descripcion)<20) {
            $errores[] = 'Debes añadir una descripcion con minimo 20 caracteres';
        }
        if (!$wc) {
            $errores[] = 'Debes añadir al menos un baño';
        }
        if (!$estacionamiento) {
            $errores[] = 'Debes añadir al menos un estacionamiento';
        }
        if (!$vendedores_id) {
            $errores[] = 'Debes elegir al vendedor';
        }
        //validar la imagen y tamaño
        
        $medida = 1000 * 1000;
        if (!$imagen['size']>$medida) {
            $errores[] = 'El tamaño de la imagen es muy grande';
        }
        if (empty($errores)) {
            //subida de imagenes creacion de carpeta
            $carpetaImg = '../../imagenes/';
            if (!is_dir($carpetaImg)) {
                mkdir($carpetaImg);
            }
            $nombreImagen = '';
            //eliminar la imagen previa en la carpeta imagenes
            if ($imagen['name']) {
                unlink($carpetaImg . $propiedad['imagen']);
                //generar un nombre unico para la imagen
                $nombreImagen = md5(uniqid(rand(),true)) . '.jpg';
                //mover la imagen a la carpeta
                move_uploaded_file($imagen['tmp_name'], $carpetaImg . $nombreImagen );
            }else{
                $nombreImagen = $propiedad['imagen'];
            }
            
            
            //Insert 
            $query = "UPDATE propiedades SET titulo='{$titulo}',precio='{$precio}',imagen='{$nombreImagen}',descripcion='{$descripcion}',
            habitaciones={$habitaciones},wc={$wc},estacionamiento={$estacionamiento},vendedores_id={$vendedores_id} WHERE id={$id};";
            echo $query;
            $resultado = mysqli_query($db, $query);
            if ($resultado) {
                # redireccionar
                header('Location: /admin?resultado=2');
            }
        
        }
        
    }
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Actualizar Propiedad</h1>
        <a href="/admin" class="boton boton-verde">Volver</a>
        <?php foreach($errores as $error): ?>
            <div class="alerta error">
                <p><?php echo $error ;?> </p>
            </div>
        <?php endforeach; ?>
        <form method="POST" class="formulario" enctype="multipart/form-data">
            <fieldset>
                <legend>Informacion General</legend>
                <label for="titulo">Nombre de la propiedad</label>
                <input type="text" id="titulo" name="titulo" placeholder="Ingresa aqui el nombre de la propiedad ej: Finca en las Villas" value="<?php echo $titulo;?>">

                <label for="precio">Precio de la propiedad</label>
                <input type="number" id="precio" name="precio" min="10000" placeholder="Ingresa aqui el precio de la propiedad ej: 50.000.000" value="<?php echo $precio;?>">
                
                <label for="imagen">Imagen de la propiedad</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">
                <img src="/imagenes/<?php echo $imagenPropiedad; ?>" alt="img" class="imagen-small">

                <label for="descripcion">Descripcion de la propiedad</label>
                <textarea id="descripcion" name="descripcion"><?php echo $descripcion;?></textarea>  

            </fieldset>
            <fieldset>
                <legend>Informacion De la Propiedad</legend>
                <label for="habitaciones">Habitaciones de la propiedad</label>
                <input type="number" id="habitaciones" name="habitaciones" min="0" max="10" ma placeholder="Ingresa aqui la cantidad de habitaciones de la propiedad ej: 2" value="<?php echo $habitaciones;?>">

                <label for="wc">Baños de la propiedad</label>
                <input type="number" id="wc" name="wc" min="1" max="10" placeholder="Ingresa aqui cantidad de baños de la propiedad ej: 2" value="<?php echo $wc;?>">
                
                <label for="estacionamiento">Estacionamiento de la propiedad</label>
                <input type="number" id="estacionamiento" name="estacionamiento" min="1" max="10" placeholder="Ingresa aqui cantidad de estacionamientos de la propiedad ej: 2" value="<?php echo $estacionamiento;?>">
            </fieldset>
            <fieldset>
                <legend>Informacion Del Vendedor</legend>
                <label for="vendedor">Vendedor de la propiedad</label>
                <select type="number" id="vendedor" name="vendedor">
                    <option value="" selected>--Seleccione --</option>
                    <?php while($vendedor = mysqli_fetch_assoc($res)): ?>
                        <option <?php echo $vendedores_id === $vendedor['id'] ? 'selected':''; ?> value="<?php echo $vendedor['id']; ?>"><?php echo $vendedor['nombre'].' '.$vendedor['apellido']; ?></option>
                    <?php endwhile; ?>
                </select> 
            </fieldset>
            <input type="submit" value="Guardar Cambios" class="boton boton-verde">
        </form>
    </main>
<?php
    incluirTemplate('footer');
?>