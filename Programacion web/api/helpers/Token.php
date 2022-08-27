<?php
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('./helpers/Response.php');

class Token {

    private $secretPass = "Dasdgfdsdsad213fdkmsaMSSGgg";
        
    public function generateToken($idUser, $rol = null){
        $actualTime = time();
        $token = array(
            "iat" => $actualTime,
            "exp" => $actualTime + (60*60*24),
            "data" => [
                "idUser" => $idUser,
                "rol" => $rol
            ]
        );
        $jwt = JWT::encode($token, $this->secretPass, "HS256");
        return $jwt;
    }

    public function verifyToken($token){
        $response = new Response();
        try {
            return JWT::decode($token, new Key($this->secretPass, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo $response->error401("Token invalido");
            die();
        }
    }
}