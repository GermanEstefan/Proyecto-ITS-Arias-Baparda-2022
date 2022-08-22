<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/CustomerModel.php");
include_once("./controllers/UserController.php");
include_once("./models/UserModel.php");


class CustomerController
{
   
    private $response;
    private $jwt;
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfRegisterCustomer($userData)
    {
        $bodyOfRequest = UserController::validateBodyOfRegisterUser($userData);
        if (!$bodyOfRequest || !isset($userData['company']) || !isset($userData['nRut'])) {
            return false;
        } else {
            return $userData;
        }
    }

    public function registerCustomer($userData)
    {

        $bodyIsValid = $this->validateBodyOfRegisterCustomer($userData);
        if (!$bodyIsValid) {
            http_response_code(400);
            $this->response->error400();
            die();
        }

        $email = $userData['email'];
        $name = $userData['name'];
        $surname = $userData['surname'];
        $phone = $userData['phone'];
        $password = $userData['password'];
        $address = $userData['address'];
        $company = $userData['company'];
        $nRut = $userData['nRut'];

        $customerExist = CustomerModel::getCustomerByEmail($email);
        if ($customerExist) {
            http_response_code(200);
            echo $this->response->error200("Ya existe un cliente ingresado con el email: " . $customerExist['email']);
            die();
        }

        $newCustomer = new CustomerModel($email, $name, $surname, $phone, $password, $address, $company, $nRut);
        $idOfUserSaved = $newCustomer->save(); //Si se guarda con exito, devuelve el ID del customer dado de alta.
        if ($idOfUserSaved) {
            $userToken = $this->jwt->generateToken($idOfUserSaved);
            $bodyResponse = array(
                "token" => $userToken
            );
            http_response_code(200);
            echo $this->response->successfully("Registro realizado con exito", $bodyResponse);
        } else {
            http_response_code(500);
            echo $this->response->error500();
        }
    }

    public function loginCustomer($userData)
    {

        if (!isset($userData['email']) || !isset($userData['password'])) {
            http_response_code(400);
            echo $this->response->error400();
            die();
        }

        $email = $userData['email'];
        $password = $userData['password'];
        $customerExistInDatabase = UserModel::getUserByEmail($email);

        if (!$customerExistInDatabase) {
            http_response_code(200);
            echo $this->response->error200("El cliente con el email: $email no existe");
            die();
        }

        $customerInDatabaseState = $customerExistInDatabase['state'];
        if($customerInDatabaseState == 0){
            http_response_code(401);
            echo $this->response->error401("Este usuario se encuentra dado de baja, contacte con el administrador.");
            die();
        }

        $customerInDatabasePassword = $customerExistInDatabase['password'];
        if (!($password == $customerInDatabasePassword)) {
            http_response_code(401);
            echo $this->response->error401('Credenciales incorrectas');
            die();
        }

        $customerInDatabaseId = $customerExistInDatabase['id_user'];
        $userToken = $this->jwt->generateToken($customerInDatabaseId);
        $bodyResponse = array(
            "token" => $userToken,
            "email" => $customerExistInDatabase['email'],
            "name" => $customerExistInDatabase['name'],
            "surname" => $customerExistInDatabase['surname'],
            "phone" => $customerExistInDatabase['phone'],
            "address" => $customerExistInDatabase['address']
        );
        
        echo $this->response->successfully("Autenticacion realizada con exito", $bodyResponse);
        die();
    }
}
