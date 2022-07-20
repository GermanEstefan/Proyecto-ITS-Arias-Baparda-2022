<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
    $employeeUserLogin = $_POST["employeeUserLogin"];
    $employeePasswordLogin = $_POST["employeePasswordLogin"];
    $employeePasswordConfirmLogin = $_POST["employeePasswordConfirmLogin"];
    //Validamos que los campos no esten vacios y las contaseñas coincidan.
    if( 
        isset($employeeUserLogin) && is_numeric($employeeUserLogin) &&
        isset($employeePasswordLogin) && isset($employeePasswordConfirmLogin) &&
        $employeePasswordLogin === $employeePasswordConfirmLogin
    ){
        /*
            Hacemos una consulta a la B.D preguntando por las credenciales...
            Obtenemos el objeto de usuario y verificamos el valor del ROL
        */
        $userRol = 'Vendedor'; //El valor de esta variable proviene de la B.D
        switch ($userRol) {
            case 'Vendedor':
                echo "UI DE VENDEDOR";
                break;
            case 'Comprador':
                echo "UI DE COMPRADOR";
                break;
            case 'Jefe':
                echo "UI de Jefe";
            default:
                echo "ERROR DE ROL";
        }
    }else{
        echo "Error al ingreso de datos";
    }
?>
</body>
</html>
