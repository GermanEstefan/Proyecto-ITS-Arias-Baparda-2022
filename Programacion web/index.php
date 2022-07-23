<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
        }

        h1,
        h2 {
            font-weight: normal;
        }

        h1 {
            text-align: center;
        }

        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 50px;
        }

        .form-container>form {
            display: flex;
            flex-direction: column;
            width: 300px;
        }

        .form-container>form>input,
        select {
            margin-bottom: 20px;
            border-radius: 3px;
            padding: 3px;
        }
    </style>
    <title>Programacion web - Formularios </title>
</head>

<body>
    <main>

        <h1>Formularios de administracion del sistema (R.R.H.H)</h1>

        <div class="form-container">
            <h2>Login</h2>
            <form action="./login-user.php" method="POST" autocomplete="off">
                <label for="">C.I:</label>
                <input type="text" name="employeeCiLogin" placeholder="C.I" required>
                <label for="">Contraseña:</label>
                <input type="password" name="employeePasswordLogin" placeholder="Contraseña" required>
                <button>Iniciar sesion</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Registro de nuevo usuario (Realizado por el Jefe)</h2>
            <form action="./register-user.php" method="POST">
                <label for="">C.I:</label>
                <input type="text" name="employeeCiRegister" placeholder="Usuario" required>
                <label for="">Contraseña:</label>
                <input type="text" name="employeePasswordRegister" placeholder="Contraseña" required>
                <label for="">Confirmar contraseña:</label>
                <input type="text" name="employeePasswordConfirmRegister" placeholder="Confirmar contraseña" required>
                <label for="rol">Rol:</label>
                <select name="employeeRolRegister" id="rol" required>
                    <option value="vendedor">Vendedor</option>
                    <option value="compradores">Compradores</option>
                    <option value="jefe">Jefe</option>
                </select>
                <button>Registrar usuario</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Alta de un nuevo producto</h2>
            <form action="./add-product.php" method="POST">
                <label for="categoria">Categoria:</label>
                <select name="productCategory">
                    <option value="guantes">Guantes</option>
                    <option value="zapatos">Zapatos</option>
                    <option value="lentes">Lentes</option>
                </select>
                <label for="">Nombre: </label>
                <input type="text" name="productName" placeholder="Nombre" required>
                <label for="">Costo:</label>
                <input type="text" name="productCost" placeholder="Costo" required>
                <label for="">Descripcion:</label>
                <input type="text" name="productDescription" placeholder="Descripcion" required>
                <label for="">Stock:</label>
                <input type="number" name="productStock" placeholder="Stock" required>
                <label for="">Imagen:</label>
                <input type="file" name="productImage" required>
                <button>Alta</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Editar producto</h2>
            <form action="./edit-product.php" method="POST">
                <label for="">Seleccione el producto</label>
                <select name="productNameEdit" required>
                    <option value="guantes">Guantes azules constru</option>
                    <option value="zapatos">Zapatos de obra</option>
                    <option value="lentes">Lentes de sol</option>
                </select>
                <label for="">Categoria:</label>
                <select name="productCategoryEdit" required>
                    <option value="guantes">Guantes</option>
                    <option value="zapatos">Zapatos</option>
                    <option value="lentes">Lentes</option>
                </select>
                <label for="">Costo</label>
                <input type="number" name="productCostEdit" placeholder="Costo" required value="123">
                <label for="">Descripcion</label>
                <input type="text" name="productDescriptionEdit" placeholder="Descripcion" required value="Descripcion de prueba" >
                <label for="">Stock</label>
                <input type="number" name="productStockEdit" placeholder="Stock" required value="123">
                <label for="">Imagen:</label>
                <input type="file" required name="productImageEdit">
                <button>Editar</button>
            </form>
        </div>

        <h1>Formularios de usuarios web (Clientes del cliente)</h1>
        <div class="form-container">
            <h2>Login</h2>
            <form action="./login-user-client.php" method="POST" autocomplete="off">
                <label for="">Email:</label>
                <input type="email" name="clientEmailLogin" placeholder="Email" required>
                <label for="">Contraseña:</label>
                <input type="password" name="clientPasswordLogin" placeholder="Contraseña" required>
                <button>Iniciar sesion</button>
            </form>
        </div>

        <div class="form-container">
            <h2>Registro</h2>
            <form action="./register-user-client.php" method="POST" autocomplete="off">
                <label for="">Email:</label>
                <input type="email" name="clientEmailRegister" placeholder="Email" required>
                <label for="">Contraseña:</label>
                <input type="password" name="clientPasswordRegister" placeholder="Contraseña" required>
                <label for="">Confirmar contraseña:</label>
                <input type="password" name="clientPasswordConfirmRegister" placeholder="Confirmar Contraseña" required>
                <label for="">Nombre:</label>
                <input type="text" name="clientNameRegister" placeholder="Nombre">
                <label for="">Apellido:</label>
                <input type="text" name="clientSurnameRegister" placeholder="Apellido">
                <button>Iniciar sesion</button>
            </form>
        </div>

    </main>
</body>

</html>