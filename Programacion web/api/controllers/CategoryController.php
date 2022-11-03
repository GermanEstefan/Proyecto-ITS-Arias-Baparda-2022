<?php

include_once('./helpers/Response.php');
include_once("./helpers/Token.php");
include_once("./models/CategoryModel.php");
include_once("./models/ProductModel.php");

class CategoryController {

    private $response;
    private $jwt;
    
    function __construct()
    {
        $this->response = new Response();
        $this->jwt = new Token();
    }

    private function validateBodyOfCategory($categoryData){
        if( !isset($categoryData['name']) 
        ||  !isset($categoryData['description']) 
        || !isset($categoryData['picture'])) 
        return false;
        //aca tenemos que validar mas cosas como que tenga un largo especifico (se pueden enviar nombre de ctegoria con valor " ")
        return $categoryData;
    }
    //ALTA
    public function saveCategory($categoryData){
        //Verificamos el token y si es valido, obtenemos el id de usuario.
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }
        $bodyIsValid = $this->validateBodyOfCategory($categoryData);
        if(!$bodyIsValid){
             echo $this->response->error400('Error en los datos enviados');
        die();
        }
        
        $name = $categoryData['name'];
        $description = $categoryData['description'];
        $pictureCategory = $categoryData['picture'];

        $categoryExist = CategoryModel::getCategoryByName($name);
        if($categoryExist){
            echo $this->response->error203("La categoria con el nombre $name ya existe");
            die();
        }
        $category = new CategoryModel($name, $description, $pictureCategory);
        $result = $category->save();
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Categoria dada de alta con exito");
    }
    //CONSULTAS
    
    public function getCategorys(){
        $categorys = CategoryModel::getAllCategorys();
        echo $this->response->successfully('Categorias obtenidas:',$categorys);
        die();
    }    
    public function getCategoryName($name){
        $category = CategoryModel::getCategoryByName($name);
        if(!$category){
            echo $this->response->error203("La categoria con el nombre $name no existe");
            die();
        }
        echo $this->response->successfully("Categoria obtenida:", $category); 
    }
    public function getCategoryId($idCategory){
        $category = CategoryModel::getCategoryById($idCategory);
        if(!$category){
            echo $this->response->error203("La categoria con el id $idCategory no existe");
            die();
        }
        echo $this->response->successfully("Categoria obtenida:", $category); 
    }
    //MODIFICACIONES
    
    public function updateCategory($idCategory,$categoryData){
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }
 
        $bodyIsValid = $this->validateBodyOfCategory($categoryData);
        if(!$bodyIsValid) {
        echo $this->response->error400('Error en los datos enviados');
        die();
        }

        $nameCategory = $categoryData['name'];
        $descriptionCategory = $categoryData['description'];
        $pictureCategory = $categoryData['picture'];
        //Validamos que exista la categoria
        $existCategory = CategoryModel::getCategoryById($idCategory);
        if (!$existCategory){
            echo $this->response->error203('La categoria indicada no es correcta');
            die();
        }
        //Validamos que solo quiera actualizar el nombre
        $notChangeName = CategoryModel::getNameByIdCategory($idCategory);
        if ($notChangeName['name'] == $nameCategory){
            $result = CategoryModel::updateCategoryNotName($idCategory,$descriptionCategory,$pictureCategory);
            echo $this->response->successfully("Atributos de la categoria actualizados");
            die();
        }
        $nameInUse = CategoryModel::getCategoryByName($nameCategory);
        if ($nameInUse){
            echo $this->response->error203("La categoria $nameCategory ya existe");
            die();
        }

        $result = CategoryModel::updateCategory($idCategory,$nameCategory,$descriptionCategory,$pictureCategory);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Categoria actualizada con exito");
    }
    //ELIMINAR
    public function deleteCategory($idCategory){
        $employeeRole = $this->jwt->verifyTokenAndValidateEmployeeUser();
        if(!$employeeRole){
            echo $this->response->error203("PERMISO DENEGADO");
            die();
        }        
        //Valido que exista la categoria
        $existCategory = CategoryModel::getCategoryById($idCategory);
        if (!$existCategory){
            echo $this->response->error203('La categoria indicada no es correcta');
            die();
        }
        //Valido que la categoria no se este usando por un producto
        $prodUsaCategory = ProductModel::getProductsByIdCategory($idCategory);
        if ($prodUsaCategory){
            echo $this->response->error203("Error La categoria $idCategory esta siendo usado en un producto");
            die();
        }

        $result = CategoryModel::deleteCategory($idCategory);
        if(!$result){
            echo $this->response->error500();
            die();
        }
        echo $this->response->successfully("Categoria eliminada exitosamente");
    }
}

?>