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
    $clientEmailLogin = $_POST["clientEmailLogin"];
    $clientPasswordLogin = $_POST["clientPasswordLogin"];
   
    if( isset($clientEmailLogin) && isset($clientPasswordLogin) ){
        $usersClients = fopen("usuarios-clientes.txt","r");
        while(!feof($usersClients)){
            $user = fgets($usersClients);
            $userFields = explode(":", $user);
            $userExist = false;
            if($clientEmailLogin == $userFields[0] && $clientPasswordLogin == $userFields[1] ){
                $userExist = true;
                echo "<h1>Bienvienido: " . "<span style='color:red'>" . $userFields[0] . "</span></h1>"; 
                break;    
            }
        }
    }

    if(!$userExist){
        echo "<h1>Credenciales incorrectas</h1>";
    }
?>
</body>
</html>