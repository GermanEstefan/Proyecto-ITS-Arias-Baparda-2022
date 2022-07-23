<?php

//asunto
$title = isset($_POST['title']) ? $_POST['title'] : false;
//mensaje
$mesage = isset($_POST['mesage']) ? $_POST['mesage'] : false;
//email
$email = isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : false;

if($title && $mesage && $email){//Validar que los campos no este vacios, y el email
    //Escribir datos en un archivo
    $archivo = fopen("formRegistro.txt", "a");
    $linea = $title . ":" . $mesage . ":" . $email . "\n";
    fputs($archivo, $linea);    
}else{
    "<p>Error en algun dato del formulario, error en el email o tiene campos vacios</p>";
}