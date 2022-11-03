<?php
require_once "vendor/autoload.php";
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
require_once('./helpers/Response.php');
require_once('./models/EmployeeModel.php');

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

    private function verifyToken($token){
        $response = new Response();
        try {
            return JWT::decode($token, new Key($this->secretPass, 'HS256'));
        } catch (Exception $e) {
            http_response_code(401);
            echo $response->error401("Token invalido");
            die();
        }
    }

    public function verifyTokenAndGetIdUserFromRequest(){
        $response = new Response();
        if (!isset(getallheaders()['access-token'])) {
            http_response_code(401);
            echo $response->error401("No hay un token presente");
            die();
        }
        $idOfUser = $this->verifyToken(getallheaders()['access-token'])->data->idUser;
        return $idOfUser;
    }

    public function verifyTokenAndValidateEmployeeUser(){
        $idOfUser = $this->verifyTokenAndGetIdUserFromRequest();
        $employeeRole = EmployeeModel::getRoleEmployeeById($idOfUser);
        if(!$employeeRole){
            return false;
        }else{
            return $employeeRole['employee_role'];
        }
    }

}
