<?php
include("./models/Usuario.php");
include("./models/Funcionario.php");
include("./models/Cliente.php");
require_once("./database/Conexion.php");
$bd = new Conexion();

//Este valor identifica cuando es un usuario CLIENTE(1) o FUNCIONARIO(2)
$typeOfUser = $_GET['typeUser'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bodyOfRequest = file_get_contents('php://input');
    $userData = json_decode($bodyOfRequest);
    
    $email = $userData->email;
    $nombre = $userData->nombre;
    $apellido = $userData->apellido;
    $telefono = $userData->telefono;
    $password = $userData->password;
    $direccion = $userData->direccion;

    //Valida que ningun dato venga nulo y que no sean string vacios.
    foreach ($userData as $value) {
        if (is_null($value) || empty($value)) {
            echo "Campos vacios";
            die();
        }
    }

    //Validamos que sea un email valido
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalido";
        die();
    }

    //Validamos que sea realmente un nombre o un apellido con expresiones regulares
    if (!preg_match("/^[a-zA-z]*$/", $nombre) || !preg_match("/^[a-zA-z]*$/", $apellido)) {
        echo "Nombre o apellido invalido";
    }

    //Validamos que el telefono sea un entero
    if (!is_int($telefono)) {
        echo "Telefono invalido";
        die();
    }

    //Validamos que la contraseña sea mayor a 5 digitos
    if (strlen($password) < 6) {
        echo "Contra invalida";
        die();
    }

    //Validamos que la direccion sea un string
    if (!is_string($direccion)) {
        echo "Direccion invalida";
        die();
    }

    if ($typeOfUser == 1) {

        //Validar estos campos
        $tipoCli = $userData->tipoCli;
        $nRut = $userData->nRut;
        $empresa = $userData->empresa;

        //$query = "INSERT INTO cliente (mail, nombre, apellido, telefono, direccion, password) VALUES ($email, $nombre, $apellido, $telefono, $direccion, $tipoCli, $nRut, $empresa)";
        /*$resultOfQuery = $bd->getData($query);
            echo $resultOfQuery;*/

    } elseif ($typeOfUser == 2) {

        //Validar estos campos
        $sueldo = $userData->sueldo;
        $estado = $userData->estado;
        $rol = $userData->rol;

        $query = "INSERT INTO funcionario (mail, nombre, apellido, telefono, direccion, password) VALUES ($email, $nombre, $apellido, $telefono, $direccion, $sueldo, $estado, $rol)";
        /*$resultOfQuery = $bd->getData($query);
            echo $resultOfQuery;*/

    } else {
        echo "Error";
    }
}
?>
