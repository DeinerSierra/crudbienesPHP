<?php
require 'app.php';
function incluirTemplate($template,$inicio = false){
    include TEMPLATES_URL."/{$template}.php";
}
function autenticado(){
    session_start();
    $auth = $_SESSION['login'] ?? false;
    if ($auth) {
        return true;
    }
    return false;
    
}