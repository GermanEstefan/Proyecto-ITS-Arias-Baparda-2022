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
    $employeeCiLogin = $_POST["employeeCiLogin"];
    $employeePasswordLogin = $_POST["employeePasswordLogin"];
    //Validamos que los campos no esten vacios y las contaseñas coincidan.
    if( 
        isset($employeeCiLogin) && is_numeric($employeeCiLogin) &&
        isset($employeePasswordLogin)
    ){
        $users = fopen("usuarios.txt","r");
        while(!feof($users)){
            $user = fgets($users);
            $userFields = explode(":", $user);
            $userExist = false;
            if($employeeCiLogin == $userFields[0] && $employeePasswordLogin == $userFields[1] ){
                $userExist = true;
                echo "<h1>Bienvienido: " . "<span style='color:red'>" . $userFields[0] . "</span></h1>"; 
                echo "<h2>Rol: " . "<span style='color: blueviolet'>". $userFields[2] ."</span></h2>";
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
