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
    $employeeCiRegister = $_POST['employeeCiRegister'];
    $employeePasswordRegister = $_POST['employeePasswordRegister'];
    $employeePasswordConfirmRegister = $_POST['employeePasswordConfirmRegister'];
    $employeeRolRegister = $_POST['employeeRolRegister'];
    if( 
        isset($employeeCiRegister) && is_numeric($employeeCiRegister) &&
        isset($employeePasswordRegister) && isset($employeePasswordConfirmRegister) &&
        isset($employeeRolRegister) && is_string($employeeRolRegister) &&
        $employeePasswordRegister === $employeePasswordConfirmRegister
    ){
        $archivo = fopen("usuarios.txt", "a");
        $linea = $employeeCiRegister . ":" .  $employeePasswordRegister . ":" . $employeeRolRegister . "\n";
        fputs($archivo, $linea);
        echo "Usuario dado de alta con exito";
        echo "<br>";
        echo "<a href='/'>Volver</a>";
    }else{
        echo "ERROR";
    }
    ?>
</body>

</html>