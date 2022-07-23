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
        $productNameEdit = $_POST['productNameEdit'];
        $productCategoryEdit = $_POST['productCategoryEdit'];
        $productCostEdit = $_POST['productCostEdit'];
        $productDescriptionEdit = $_POST['productDescriptionEdit'];
        $productStockEdit = $_POST['productStockEdit'];
  

        if( 
            isset($productNameEdit) && is_string($productNameEdit) &&
            isset($productCategoryEdit) && is_string($productCategoryEdit) &&
            isset($productCostEdit) && is_numeric($productCostEdit) &&
            isset($productDescriptionEdit) && isset($productStockEdit) &&
            is_numeric($productStockEdit)
        ){
            echo "<h1>Producto editado con exito</h1>";
            echo "<a href='/'>Volver</a>";
        }else{
            echo "Campos invalidos";
        }
    ?>
</body>
</html>