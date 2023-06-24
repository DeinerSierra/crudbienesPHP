<?php
    $id = $_GET['id'];
    
    $id = filter_var($id, FILTER_VALIDATE_INT);
    if (!$id) {
        header('Location: /');
    }
    require 'includes/config/db.php';
    // Utilizar la función de conexión
    $db = conectarDB();
  //escribir el query
    $consulta = "SELECT * FROM propiedades WHERE id = {$id}";
  //hacer la consulta a la db
    $res = mysqli_query($db, $consulta);
    if ($res->num_rows === 0) {
        header('Location: /');
    }
    $propiedad = mysqli_fetch_assoc($res);
    require './includes/funciones.php';
    incluirTemplate('header');
?>

    <main class="contenedor seccion contenido-centrado">
        <h1>Casa en Venta frente al bosque</h1>

    
        <img loading="lazy" src="/imagenes/<?php echo $propiedad['imagen']; ?>" alt="imagen de la propiedad">
        

        <div class="resumen-propiedad">
            <p class="precio">$<?php echo $propiedad['precio']; ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propiedad['wc']; ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propiedad['estacionamiento']; ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php echo $propiedad['habitaciones']; ?></p>
                </li>
            </ul>

            <p><?php echo $propiedad['descripcion']; ?></p>

            
        </div>
    </main>

<?php
    incluirTemplate('footer');
?>