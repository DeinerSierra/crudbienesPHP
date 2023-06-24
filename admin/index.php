<?php
    require '../includes/funciones.php';
    //importar la conexion db
    require '../includes/config/db.php'; 
    $auth = autenticado();
    if (!$auth) {
        header('Location: /');
    }
   
    // Utilizar la función de conexión
    $db = conectarDB();
//escribir el query
    $consulta = "SELECT * FROM propiedades";
//hacer la consulta a la db
    $res = mysqli_query($db, $consulta);
    $resultado = $_GET['resultado'] ?? null;
    if ($_SERVER['REQUEST_METHOD'] ==='POST') {
        $id = $_POST['id'];
        $id = filter_var($id,FILTER_VALIDATE_INT);
        if ($id) {
            //eliminar el archivo
            $query = "SELECT imagen FROM propiedades WHERE id = {$id}";
            $resultado = mysqli_query($db,$query);
            $propiedad = mysqli_fetch_assoc($resultado);
            unlink('../imagenes/'.$propiedad['imagen']);
            //eliminar la propiedad
            $query = "DELETE FROM propiedades WHERE id={$id}";
            $resultado = mysqli_query($db,$query);
            if ($resultado) {
                header('Location: /admin?resultado=3');
            }
        }
    }
    
    incluirTemplate('header');
?>

    <main class="contenedor seccion">
        <h1>Adminstrador</h1>
        <?php if ($resultado == 1) {?>
            <p class="alerta exito">Anuncio Creado Correctamente</p>
        <?php } ?>
        <?php if ($resultado == 2) {?>
            <p class="alerta exito">Anuncio Actualizado Correctamente</p>
        <?php } ?>
        <?php if ($resultado == 3) {?>
            <p class="alerta exito">Anuncio Eliminado Correctamente</p>
        <?php } ?>
        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Crear Nueva Propiedad</a>
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>TITULO</th>
                    <th>IMAGEN</th>
                    <th>PRECIO</th>
                    <th>ACCIONES</th>
                </tr>
            </thead>
            <tbody><!--Iterar los resultados-->
                <?php while($propiedad = mysqli_fetch_assoc($res)): ?>
                <tr>
                    <td><?php echo $propiedad['id'];?></td>
                    <td><?php echo $propiedad['titulo'];?></td>
                    <td>
                        <img src="/imagenes/<?php echo $propiedad['imagen'];?>" alt="img" class="imagen-tabla">
                    </td>
                    <td>$ <?php echo $propiedad['precio'];?></td>
                    <td>
                        <form action="" method="POST" class="w-100">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id'];?>">
                            <input type="submit" class="boton-rojo-block" value="Eliminar">
                        </form>
                        
                        <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad['id'];?>" class="boton-amarillo-block">Actualizar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        
    </main>
<?php
    //cerrar la conexion 
    mysqli_close($db);
    incluirTemplate('footer');
?>