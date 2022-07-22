<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body{
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }
        h1,h2{
            font-weight: normal;
        }
        h1{
            text-align: center;
        }
        .form-container{
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }
        .form-container > form{
            display: flex;
            flex-direction: column;
            width: 300px;
        }
        .form-container > form > input,select {
            margin-bottom: 20px;
            border-radius: 3px;
            padding: 3px;
        }
    </style>
    <title>Programacion web - Formularios </title>
</head>

<body>
    <main>
        
        <h1>Formularios de administracion del sistema (R.H.H)</h1>
        
        <div class="form-container">
            <h2>Login</h2>
            <form action="./login-user.php" method="POST" autocomplete="off">
                <input type="text" name="employeeCiLogin" placeholder="C.I">
                <input type="password" name="employeePasswordLogin" placeholder="Contraseña">
                <button>Iniciar sesion</button>
            </form>
        </div>
        
        <div class="form-container">
            <h2>Registro de nuevo usuario (Realizado por el Jefe)</h2>
            <form action="./register-user.php" method="POST">
                <input type="text" name="employeeCiRegister" placeholder="Usuario">
                <input type="text" name="employeePasswordRegister" placeholder="Contraseña">
                <input type="text" name="employeePasswordConfirmRegister" placeholder="Confirmar contraseña">
                <label for="rol">Rol:</label>
                <select name="employeeRolRegister" id="rol">
                    <option value="vendedor">Vendedor</option>
                    <option value="compradores">Compradores</option>
                    <option value="jefe">Jefe</option>
                </select>
                <button>Registrar usuario</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Alta de un nuevo producto</h2>
            <form action="" method="POST">
                <label for="categoria">Categoria:</label>
                <select name="productCategory">
                    <option value="guantes">Guantes</option>
                    <option value="zapatos">Zapatos</option>
                    <option value="lentes">Lentes</option>
                </select>
                <input type="text" name="productName" placeholder="Nombre">
                <input type="text" name="productCost" placeholder="Costo">
                <input type="text" name="productDescription" placeholder="Descripcion">
                <input type="number" name="productStock" placeholder="Stock">
                <label for="">Imagen:</label>
                <input type="file" name="productImage">
                <button>Alta</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Aprovisionamiento de un producto existente</h2>
        </div>
        
    </main>
</body>

</html>