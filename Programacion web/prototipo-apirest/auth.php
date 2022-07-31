<?php
include("./models/User.php");
include("./models/Employee.php");
include("./models/Customer.php");

//Este valor identifica cuando es un usuario CLIENTE(1) o FUNCIONARIO(2)
$typeOfUser = $_GET['typeUser'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $bodyOfRequest = file_get_contents('php://input');
    $userData = json_decode($bodyOfRequest);
    
    $mail = $userData->mail;
    $name = $userData->name;
    $surname = $userData->surname;
    $phone = $userData->phone;
    $password = $userData->password;
    $address = $userData->address;

    //Valida que ningun dato venga nulo y que no sean string vacios.
    foreach ($userData as $value) {
        if (is_null($value) || empty($value)) {
            echo "Campos vacios";
            die();
        }
    }

    //Validamos que sea un email valido
    if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
        echo "Email invalido";
        die();
    }

    //Validamos que sea realmente un nombre o un apellido con expresiones regulares
    if (!preg_match("/^[a-zA-z]*$/", $name) || !preg_match("/^[a-zA-z]*$/", $surname)) {
        echo "Nombre o apellido invalido";
    }

    //Validamos que el telefono sea un entero
    if (!is_int($phone)) {
        echo "Telefono invalido";
        die();
    }

    //Validamos que la contraseña sea mayor a 5 digitos
    if (strlen($password) < 6) {
        echo "Contra invalida";
        die();
    }

    //Validamos que la direccion sea un string
    if (!is_string($address)) {
        echo "Direccion invalida";
        die();
    }

    if ($typeOfUser == 1) {

        //Validar estos campos
        $typeCustomer = $userData->typeCustomer;
        $company = $userData->company;
        $nRut = $userData->nRut;

        //$query = "INSERT INTO cliente (mail, nombre, apellido, telefono, direccion, password) VALUES ($email, $nombre, $apellido, $telefono, $direccion, $tipoCli, $nRut, $empresa)";
        /*$resultOfQuery = $bd->getData($query);
            echo $resultOfQuery;*/

    } elseif ($typeOfUser == 2) {

        //Validar estos campos
        $ci = $userData->ci;
        $salary = $userData->salary;
        $rol = $userData->rol;
        
        //Validamos que sea un rol valido.
        //(1: VENDEDOR, 2: COMPRADOR, 3:JEFE)
        $validRols = array(1,2,3);
        if(!in_array($rol, $validRols)){
            echo "Rol invalido";
            die();
        }

        if(!is_int($ci)){
            echo "CI invalida";
            die();
        }
        $newEmployee = new Employee($mail, $name, $surname, $phone, $password, $address, $salary, $rol, $ci);
        echo $newEmployee->save();

    } else {
        echo "Error";
    }
}
?>
