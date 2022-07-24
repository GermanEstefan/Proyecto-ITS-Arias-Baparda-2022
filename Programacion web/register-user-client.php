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
    $clientEmailRegister = $_POST['clientEmailRegister'];
    $clientPasswordRegister = $_POST['clientPasswordRegister'];
    $clientPasswordConfirmRegister = $_POST['clientPasswordConfirmRegister'];
    $clientNameRegister = $_POST['clientNameRegister'];
    $clientSurnameRegister = $_POST['clientSurnameRegister'];
   
    if( 
        isset($clientEmailRegister) && 
        isset($clientPasswordRegister) && isset($clientPasswordConfirmRegister) &&
        isset($clientNameRegister) && is_string($clientNameRegister) && 
        isset($clientSurnameRegister) && is_string($clientSurnameRegister) &&
        $clientPasswordRegister === $clientPasswordConfirmRegister
    ){
        $archivo = fopen("usuarios-clientes.txt", "a");
        $linea = $clientEmailRegister . ":" .  $clientPasswordRegister . ":" . $clientNameRegister . ":" . $clientSurnameRegister . "\n";
        fputs($archivo, $linea);
        echo "Usuario dado de alta con exito";
        echo "<br>";
        echo "<a href='/Programacion web'>Volver</a>";
    }else{
        echo "ERROR";
    }
    ?>
</body>

</html>