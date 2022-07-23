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
        $productCategory = $_POST['productCategory'];
        $productName = $_POST['productName'];
        $productCost = $_POST['productCost'];
        $productDescription = $_POST['productDescription'];
        $productStock = $_POST['productStock'];
        if( 
            isset($productCategory) && is_string($productCategory) &&
            isset($productName) && is_string($productName) &&
            isset($productCost) && is_numeric($productCost) &&
            isset($productDescription) && isset($productStock) &&
            is_numeric($productStock)
        ){
            $productos = fopen("productos.txt", "a");
            $producto = $productCategory . ":" .  $productName . ":" . $productCost . ":" . $productDescription . ":" . $productStock ."\n";
            fputs($productos, $producto);
            echo "<h1>Producto dado de alta con exito</h1>";
            echo "<a href='/'>Volver</a>";
        }else{
            echo "Campos invalidos";
        }
    ?>
</body>
</html>