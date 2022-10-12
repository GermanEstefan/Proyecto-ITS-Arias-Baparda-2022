<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/RoleModel.php");
include_once("./models/EmployeeModel.php");

class RoleController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfRole($roleData){
        if( !isset($roleData['name']) 
        ||  !isset($roleData['description']))
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $roleData;
    }
    //ALTA
    public function saveRole($roleData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfRole($roleData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $roleData['name'];
        $description = $roleData['description'];
        //Valido que no exista el talle x el nombre (unique)
        $roleExist = RoleModel::getRoleByName($name);
        if($roleExist){
            echo $this->response->error203("El Rol con el nombre $name ya existe");
            die();
        }
        $role = new RoleModel($name, $description);
        $result = $role->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Rol creado con exito");
    }
    //CONSULTAS
    public function getRoles(){
        $roles = RoleModel::getAllRoles();
        echo $this->response->successfully("Roles Obtenidos:",$roles);
        die();
        }
    public function getRoleName($name){
        $role = RoleModel::getRoleByName($name);
        if(!$role){
            echo $this->response->error203("El rol con nombre $name no existe");
            die();
        }
        echo $this->response->successfully("Role encontrado:", $role);  
    }
    //ACTUALIZAR
    public function updateRole($nameRole,$roleData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfRole($roleData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $name = $roleData['name'];
        $descriptionRole = $roleData['description'];
                
        $existRole = RoleModel::getRoleByName($nameRole);
        if (!$existRole){
            echo $this->response->error203('El Rol indicado no existe');
            die();
        }
        
        $result = RoleModel::updateDescriptionRole($name,$descriptionRole);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Descripcion delRol actualizado con exito");
    }
    //ELIMINAR
    public function deleteRole($nameRole){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        //Valido que el Rol exista
        $existRole = RoleModel::getRoleByName($nameRole);
        if (!$existRole){
            echo $this->response->error203('Rol indicado no existe');
            die();
        }
        //Valido que el Rol no se este usando por un empleado
        $asignedRole = EmployeeModel::getEmployeesByRole($nameRole);
        if ($asignedRole){
            echo $this->response->error203("Error- El rol: $nameRole esta asignado a uno o mas empleados");
            die();
        }

        $result = RoleModel::deleteRole($nameRole);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Rol eliminado exitosamente");
    }
}

?>