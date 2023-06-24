<?php
function conectarDB(){
    $host = 'localhost'; // Por lo general, 'localhost'
    $usuario = 'root';
    $contrasena = '123456';
    $nombreBaseDatos = 'bienesraices2023_crud';
     // Crear una conexión
     $db = new mysqli($host, $usuario, $contrasena, $nombreBaseDatos);

     // Verificar si hay errores en la conexión
     if ($db->connect_error) {
         die("Error de conexión: " . $db->connect_error);
     }
 
     // Devolver la conexión
     return $db;
}