<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/CategoryModel.php");

class CategoryController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfCategory($categoryData){
        if( !isset($categoryData['name']) ||  !isset($categoryData['description']) ) return false;
        return $categoryData;
    }
    
    public function saveCategory($categoryData){
        /*
            En este metodo no precisamos el ID del usuario, lo unico que validamos es que tenga un token y sea valido. 
            Si no tiene token, no pasa de la funcion para abajo por que el metodo mismo le niega el acceso.
        */
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfCategory($categoryData);
        if(!$bodyIsValid) echo $this->response->error400();

        $name = $categoryData['name'];
        $description = $categoryData['description'];

        $categoryExist = CategoryModel::getCategoryByName($name);
        if($categoryExist){
            echo $this->response->error200("La categoria con el nombre $name ya existe");
            die();
        }
        $category = new CategoryModel($name, $description);
        $result = $category->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Categoria dada de alta con exito");
    }

    public function getCategorys(){
        $cateogrysToJson = json_encode(CategoryModel::getAllCategorys()); 
        echo $cateogrysToJson;
    }

    public function getCategory($name){
        $category = CategoryModel::getCategoryByName($name);
        if(!$category){
            echo $this->response->error200("La categoria con el nombre $name no existe");
            die();
        }
        echo json_encode($category);  
    }

    public function updateCategory($idCategory,$categoryData){
        $this->jwt->verifyTokenAndGetIdUserFromRequest(); 
        $bodyIsValid = $this->validateBodyOfCategory($categoryData);
        if(!$bodyIsValid) echo $this->response->error400();

        $nameCategory = $categoryData['name'];
        $descriptionCategory = $categoryData['description'];
        
        $existCategory = CategoryModel::getCategoryById($idCategory);
        if (!$existCategory){
            echo $this->response->error200('El id de la categoria enviado no existe');
            die();
        }

        $existName = CategoryModel::getCategoryByName($nameCategory);
        if ($existName){
            echo $this->response->error200('El nombre de la categoria ya existe');
            die();
        }

        $result = CategoryModel::updateCategory($idCategory,$nameCategory,$descriptionCategory);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Categoria actualizada con exito");
    }
    public function deleteCategory($idCategory){
        $this->jwt->verifyTokenAndGetIdUserFromRequest();
        $existSize = CategoryModel::getCategoryById($idCategory);
        if (!$existSize){
            echo $this->response->error200('El id de la categoria enviada no existe');
            die();
        }

        $result = CategoryModel::deleteCategory($idCategory);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Category eliminada exitosamente");
    }
}

?>