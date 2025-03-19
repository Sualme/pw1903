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

$sql = "SELECT nombre, ape_p, ape_m, nivel, telefono, correo, direccion_p, mun_p, escuela, mun_e, estado, estatus, periodo, plan, promedio 
        FROM usuario WHERE nickname = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $nickname);
$stmt->execute();
$result = $stmt->get_result();


if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No se encontraron datos para este usuario.";
    exit();
}

$stmt->close();
$conn->close();
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración</title>
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
                <li><a href="normal.php">Mi espacio</a></li>
                <li><a href="a.php">alumnos</a></li>
                <li><a href="na2.php">Nuevo alumno </a></li>
                <li><a href="index.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <div style="max-width: 800px; margin: 50px auto; padding: 20px; background-color: white; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); border-radius: 10px;">
        <h2>Bienvenido, <?php echo $user['nombre'] . ' ' . $user['ape_p'] . ' ' . $user['ape_m']; ?>!</h2>
        <p>Estado: <?php echo $user['estado']; ?></p>
        <p>Teléfono: <?php echo $user['telefono']; ?></p>
        <p>Correo: <?php echo $user['correo']; ?></p>
        <p>Dirección: <?php echo $user['direccion_p']; ?></p>
        <p>Municipio: <?php echo $user['mun_p']; ?></p>
        <p>Escuela: <?php echo $user['escuela']; ?></p>
        <p>Municipio de Escuela: <?php echo $user['mun_e']; ?></p>
        <p>Promedio: <?php echo $user['promedio']; ?></p>
        <p>Periodo: <?php echo $user['periodo']; ?></p>
        <p>Plan: <?php echo $user['plan']; ?></p>
        <p>Nivel: <?php echo $user['nivel']; ?></p>
    </div>

    <footer>
        <p>&copy; 2024 Sucemonjo. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
