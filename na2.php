<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "usuarios";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }
    $nombre = $_POST['nombre'];
    $ape_p = $_POST['ape_p'];
    $ape_m = $_POST['ape_m'];
    $nivel = $_POST['nivel'];
    $correo = $_POST['correo'];
    $estado = $_POST['estado'];
    $estatus = $_POST['estatus'];
    $nickname = $_POST['nickname'];
    $pass = password_hash($_POST['pass'], PASSWORD_DEFAULT); 
    $escuela = isset($_SESSION['id_escu']) ? $_SESSION['id_escu'] : '';
    $sql = "INSERT INTO usuario (nombre, ape_p, ape_m, nivel, correo, estado, estatus, nickname, pass, escuela) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssssss", $nombre, $ape_p, $ape_m, $nivel, $correo, $estado, $estatus, $nickname, $pass, $escuela);

    if ($stmt->execute()) {
        $mensaje = "Usuario registrado exitosamente.";
    } else {
        $mensaje = "Error al registrar usuario: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Usuario</title>
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
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        header .logo {
            font-size: 1.5em;
            font-weight: bold;
        }
        header nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        header nav ul li {
            display: inline;
        }
        header nav ul li a {
            color: white;
            text-decoration: none;
            font-weight: bold;
            padding: 5px 10px;
            transition: background-color 0.3s ease;
        }
        header nav ul li a:hover {
            background-color: #ffccbc;
            color: #b71c1c;
            border-radius: 5px;
        }
        form {
            margin: 2em auto;
            padding: 20px;
            max-width: 600px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            display: block;
            margin: 10px 0 5px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        input[type="submit"] {
            background-color: #b71c1c;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            width: 100%;
        }
        input[type="submit"]:hover {
            background-color: #ffccbc;
        }
        .mensaje {
            margin: 20px auto;
            padding: 10px;
            max-width: 600px;
            text-align: center;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
        }
        .mensaje.error {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body>

<header>
    <div class="logo">Sucemonjo</div>
    <nav>
        <ul>
                <li><a href="normal.php">Mi espacio</a></li>
                <li><a href="a.php">alumnos</a></li>
                <li><a href="na2.php">Nuevo alumno </a></li>
                <li><a href="index.html">Cerrar Sesión</a></li>
        </ul>
    </nav>
</header>

<main>
    <?php if (!empty($mensaje)): ?>
        <div class="mensaje <?php echo strpos($mensaje, 'Error') !== false ? 'error' : ''; ?>">
            <?php echo $mensaje; ?>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="nombre">Nombre</label>
        <input type="text" id="nombre" name="nombre" placeholder="Ingrese el nombre" required>

        <label for="ape_p">Apellido Paterno</label>
        <input type="text" id="ape_p" name="ape_p" placeholder="Ingrese el apellido paterno" required>

        <label for="ape_m">Apellido Materno</label>
        <input type="text" id="ape_m" name="ape_m" placeholder="Ingrese el apellido materno" required>

        <label for="nivel">Nivel</label>
        <input type="text" id="nivel" name="nivel" placeholder="Ingrese el nivel" required>

        <label for="correo">Correo</label>
        <input type="email" id="correo" name="correo" placeholder="Ingrese el correo" required>

        <label for="estado">Estado</label>
        <input type="text" id="estado" name="estado" placeholder="Ingrese el estado" required>

        <label for="estatus">Estatus</label>
        <input type="text" id="estatus" name="estatus" placeholder="Ingrese el estatus" required>

        <label for="nickname">Nickname</label>
        <input type="text" id="nickname" name="nickname" placeholder="Ingrese el nickname" required>

        <label for="pass">Contraseña</label>
        <input type="password" id="pass" name="pass" placeholder="Ingrese la contraseña" required>

        <label for="escuela">ID Escuela</label>
        <input type="text" id="escuela" name="escuela" placeholder="ID de la escuela" value="<?php echo htmlspecialchars($escuela); ?>" required>

        <input type="submit" value="Registrar Usuario">
</main>

</body>
</html>
