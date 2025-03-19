<?php
session_start();
if (!isset($_SESSION['nickname'])) {
    header("Location: sesion.php"); 
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "usuarios";


$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}


$nickname = $_SESSION['nickname'];

$mensaje = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $sql_check = "SELECT pass FROM usuario WHERE nickname = ?";
    $stmt_check = $conn->prepare($sql_check);
    $stmt_check->bind_param("s", $nickname);
    $stmt_check->execute();
    $result = $stmt_check->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($current_password, $user['pass'])) {

            if ($new_password === $confirm_password) {

                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);


                $sql_update = "UPDATE usuario SET pass = ? WHERE nickname = ?";
                $stmt_update = $conn->prepare($sql_update);
                $stmt_update->bind_param("ss", $hashed_password, $nickname);

                if ($stmt_update->execute()) {
                    $mensaje = "Contraseña actualizada exitosamente.";
                } else {
                    $mensaje = "Error al actualizar la contraseña.";
                }

                $stmt_update->close();
            } else {
                $mensaje = "Las contraseñas nuevas no coinciden.";
            }
        } else {
            $mensaje = "La contraseña actual es incorrecta.";
        }
    } else {
        $mensaje = "Usuario no encontrado.";
    }

    $stmt_check->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cambiar Contraseña</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            color: #333;
        }
        header {
            background-color: #b71c1c;
            color: white;
            padding: 1em 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        header .logo {
            font-size: 1.8em;
            font-weight: bold;
        }
        nav ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
        }
        nav ul li {
            margin: 0 15px;
        }
        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1em;
            transition: color 0.3s;
        }
        nav ul li a:hover {
            color: #ffccbc;
            border-bottom: 2px solid #ffccbc;
        }
        form {
            max-width: 400px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        input[type="password"], input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #b71c1c;
            color: white;
            font-weight: bold;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        input[type="submit"]:hover {
            background-color: #d32f2f;
        }
        .mensaje {
            max-width: 400px;
            margin: 20px auto;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .mensaje.exito {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        footer {
            background-color: #b71c1c;
            color: white;
            text-align: center;
            padding: 15px;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">Sucemonjo</div>
    <nav>
        <ul>
            <li><a href="admin.php">Mi espacio</a></li>
            <li><a href="c.php">Consulta</a></li>
            <li><a href="na.php">Nuevo</a></li>
            <li><a href="cc.php">Cambiar Contraseña</a></li>
            <li><a href="index.html">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>

<form action="" method="POST">
    <label for="current_password">Contraseña Actual</label>
    <input type="password" id="current_password" name="current_password" required>

    <label for="new_password">Nueva Contraseña</label>
    <input type="password" id="new_password" name="new_password" required>

    <label for="confirm_password">Confirmar Nueva Contraseña</label>
    <input type="password" id="confirm_password" name="confirm_password" required>

    <input type="submit" value="Cambiar Contraseña">
</form>

<?php if (!empty($mensaje)): ?>
    <div class="mensaje <?php echo strpos($mensaje, 'Error') !== false || strpos($mensaje, 'incorrecta') !== false ? 'error' : 'exito'; ?>">
        <?php echo $mensaje; ?>
    </div>
<?php endif; ?>

<footer>
    <p>&copy; 2024 Sucemonjo. Todos los derechos reservados.</p>
</footer>

</body>
</html>
