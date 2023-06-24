<?php
function conectarDB(){
    $host = 'containers-us-west-196.railway.app'; // Por lo general, 'localhost'
    $usuario = 'root';
    $contrasena = 'MfGzb4reK7hp1QrH736S';
    $nombreBaseDatos = 'railway';
     // Crear una conexión
     $db = new mysqli($host, $usuario, $contrasena, $nombreBaseDatos);

     // Verificar si hay errores en la conexión
     if ($db->connect_error) {
         die("Error de conexión: " . $db->connect_error);
     }
 
     // Devolver la conexión
     return $db;
}