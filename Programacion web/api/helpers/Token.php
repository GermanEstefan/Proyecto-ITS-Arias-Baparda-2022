<?php
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class Token {

    private $secretPass = "Dasdgfdsdsad213fdkmsaMSSGgg";
        
    public function generateToken($emailUser, $rol = null){
        $actualTime = time();
        $token = array(
            "iat" => $actualTime,
            "exp" => $actualTime + (60*60*24),
            "data" => [
                "email" => $emailUser,
                "rol" => $rol
            ]
        );
        $jwt = JWT::encode($token, $this->secretPass, "HS256");
        return $jwt;
    }

    public function verifyToken($token){
        $isValid = JWT::decode($token, new Key($this->secretPass, 'HS256'));
        return $isValid;
    }
    

}
