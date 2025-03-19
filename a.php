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
$query = "SELECT id_escu FROM usuario WHERE nickname = '$nickname'";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $id_escu_logueado = $user['id_escu'];
} else {
    die("Usuario no encontrado.");
}
if (isset($_GET['eliminar_id'])) {
    $idEliminar = $_GET['eliminar_id'];
    $deleteSql = "DELETE FROM usuario WHERE ID = $idEliminar";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Usuario eliminado correctamente";
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }
}


if (isset($_POST['actualizar'])) {

}


$whereClause = "WHERE id_escu = $id_escu_logueado";

if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    $nombre = $conn->real_escape_string($_GET['nombre']);
    $whereClause .= " AND nombre LIKE '%$nombre%'";
}


$sql = "SELECT * FROM usuario $whereClause"; 
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consulta de Usuarios</title>
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
        header nav ul {
            list-style: none;
            display: flex;
            gap: 10px;
        }
        header nav ul li {
            display: inline;
        }
        header nav ul li a {
            text-decoration: none;
            color: white;
            font-size: 1.1em;
        }
        header nav ul li a:hover {
            color: #ffd600;
        }
        table {
            width: 90%; 
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
            font-size: 0.9em;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        a {
            color: #b71c1c;
            text-decoration: none;
            padding: 5px 10px;
            border: 1px solid #b71c1c;
            border-radius: 3px;
        }
        a:hover {
            background-color: #b71c1c;
            color: white;
        }
        input[type="text"], input[type="submit"], input[type="number"] {
            padding: 5px;
            margin: 10px 0;
            border-radius: 3px;
            border: 1px solid #ddd;
        }
        input[type="submit"] {
            background-color: #b71c1c;
            color: white;
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

    <h2 style="text-align: center; padding-top: 20px;">Consulta de Usuarios</h2>

    <form method="GET" style="text-align: center;">
        <input type="text" name="nombre" placeholder="Buscar por nombre" value="<?php echo isset($_GET['nombre']) ? $_GET['nombre'] : ''; ?>">
        <input type="submit" value="Filtrar">
    </form>

    <?php
    if ($result->num_rows > 0) {
        echo "<table>
                <tr>
                    <th>Nombre</th>
                    <th>Apellido Paterno</th>
                    <th>Apellido Materno</th>
                    <th>Nivel</th>
                    <th>Teléfono</th>
                    <th>Correo</th>
                    <th>Dirección</th>
                    <th>Municipio</th>
                    <th>Escuela</th>
                    <th>Estado</th>
                    <th>Estatus</th>
                    <th>Periodo</th>
                    <th>Plan</th>
                    <th>Promedio</th>
                    <th>Acciones</th>
                    
                    
                </tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>" . $row['nombre'] . "</td>
                    <td>" . $row['ape_p'] . "</td>
                    <td>" . $row['ape_m'] . "</td>
                    <td>" . $row['nivel'] . "</td>
                    <td>" . $row['telefono'] . "</td>
                    <td>" . $row['correo'] . "</td>
                    <td>" . $row['direccion_p'] . "</td>
                    <td>" . $row['mun_p'] . "</td>
                    <td>" . $row['escuela'] . "</td>
                    <td>" . $row['estado'] . "</td>
                    <td>" . $row['estatus'] . "</td>
                    <td>" . $row['periodo'] . "</td>
                    <td>" . $row['plan'] . "</td>
                    <td>" . $row['promedio'] . "</td>
                    
                    <td>
                        <a href='?editar_id=" . $row['ID'] . "'>Editar</a> | 
                        <a href='?eliminar_id=" . $row['ID'] . "' onclick='return confirm(\"¿Estás seguro de eliminar este usuario?\")'>Eliminar</a>
                    </td>
                </tr>";
        }
        echo "</table>";
    } else {
        echo "No se encontraron usuarios.";
    }

    if (isset($_GET['editar_id'])) {
        $idEditar = $_GET['editar_id'];
        $conn = new mysqli($servername, $username, $password, $dbname);
        $sql = "SELECT * FROM usuario WHERE ID = $idEditar";
        $result = $conn->query($sql);
        $usuario = $result->fetch_assoc();
        $conn->close();
    ?>

    <h3 style="text-align: center;">Actualizar Usuario</h3>
    <form method="POST" style="max-width: 600px; margin: auto;">
        <input type="hidden" name="id" value="<?php echo $usuario['ID']; ?>">
        <label>Nombre:</label><br>
        <input type="text" name="nombre" value="<?php echo $usuario['nombre']; ?>"><br>

        <label>Apellido Paterno:</label><br>
        <input type="text" name="ape_p" value="<?php echo $usuario['ape_p']; ?>"><br>

        <label>Apellido Materno:</label><br>
        <input type="text" name="ape_m" value="<?php echo $usuario['ape_m']; ?>"><br>

        <label>Nivel:</label><br>
        <input type="text" name="nivel" value="<?php echo $usuario['nivel']; ?>"><br>

        <label>Teléfono:</label><br>
        <input type="number" name="telefono" value="<?php echo $usuario['telefono']; ?>"><br>

        <label>Correo:</label><br>
        <input type="text" name="correo" value="<?php echo $usuario['correo']; ?>"><br>

        <label>Dirección:</label><br>
        <input type="text" name="direccion_p" value="<?php echo $usuario['direccion_p']; ?>"><br>

        <label>Municipio:</label><br>
        <input type="text" name="mun_p" value="<?php echo $usuario['mun_p']; ?>"><br>

        <label>Escuela:</label><br>
        <input type="text" name="escuela" value="<?php echo $usuario['escuela']; ?>"><br>

        <label>Estado:</label><br>
        <input type="text" name="estado" value="<?php echo $usuario['estado']; ?>"><br>

        <label>Estatus:</label><br>
        <input type="text" name="estatus" value="<?php echo $usuario['estatus']; ?>"><br>

        <label>Periodo:</label><br>
        <input type="text" name="periodo" value="<?php echo $usuario['periodo']; ?>"><br>

        <label>Plan:</label><br>
        <input type="text" name="plan" value="<?php echo $usuario['plan']; ?>"><br>

        <label>Promedio:</label><br>
        <input type="text" name="promedio" value="<?php echo $usuario['promedio']; ?>"><br>

        <label>Nickname:</label><br>
        <input type="text" name="nickname" value="<?php echo $usuario['nickname']; ?>"><br>

        <label>Contraseña:</label><br>
        <input type="text" name="pass" value="<?php echo $usuario['pass']; ?>"><br>

        <input type="submit" name="actualizar" value="Actualizar">
    </form>
    <?php } ?>
</body>
</html>
