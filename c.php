<?php
session_start();

// Verifica si el usuario está autenticado
if (!isset($_SESSION['nickname'])) {
    header("Location: sesion.php"); // Redirige a login si no está autenticado
    exit();
}

// Conectar a la base de datos
$servername = "localhost";  // Ajusta el servidor de la base de datos
$username = "root";         // Ajusta el nombre de usuario
$password = "";             // Ajusta la contraseña
$dbname = "usuarios";       // Ajusta el nombre de la base de datos

// Crea la conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verifica la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Eliminar un usuario
if (isset($_GET['eliminar_id'])) {
    $idEliminar = $_GET['eliminar_id'];
    $deleteSql = "DELETE FROM usuario WHERE ID = $idEliminar";
    if ($conn->query($deleteSql) === TRUE) {
        echo "Usuario eliminado correctamente";
    } else {
        echo "Error al eliminar el usuario: " . $conn->error;
    }
}

// Actualizar usuario
if (isset($_POST['actualizar'])) {
    $idActualizar = $_POST['id'];
    $nombre = $_POST['nombre'];
    $ape_p = $_POST['ape_p'];
    $ape_m = $_POST['ape_m'];
    $nivel = $_POST['nivel'];
    $telefono = $_POST['telefono'];
    $correo = $_POST['correo'];
    $direccion_p = $_POST['direccion_p'];
    $mun_p = $_POST['mun_p'];
    $escuela = $_POST['escuela'];
    $estado = $_POST['estado'];
    $estatus = $_POST['estatus'];
    $periodo = $_POST['periodo'];
    $plan = $_POST['plan'];
    $promedio = $_POST['promedio'];
    $nickname = $_POST['nickname'];
    $pass = $_POST['pass'];
    $id_escu = $_POST['id_escu'];
    

    $updateSql = "UPDATE usuario SET nombre='$nombre', ape_p='$ape_p', ape_m='$ape_m', nivel='$nivel', telefono='$telefono', correo='$correo', direccion_p='$direccion_p', mun_p='$mun_p', escuela='$escuela', estado='$estado', estatus='$estatus', periodo='$periodo', plan='$plan', promedio='$promedio',nickname='$nickname',pass='$pass', id_escu='$id_escu'   WHERE ID=$idActualizar";
    
    if ($conn->query($updateSql) === TRUE) {
        echo "Usuario actualizado correctamente";
    } else {
        echo "Error al actualizar el usuario: " . $conn->error;
    }
}

// Filtro
$whereClause = "";
if (isset($_GET['nombre']) && !empty($_GET['nombre'])) {
    $nombre = $conn->real_escape_string($_GET['nombre']);
    $whereClause = " WHERE nombre LIKE '%$nombre%'";
}


// Consulta para obtener los usuarios filtrados
$sql = "SELECT * FROM usuario $whereClause"; // Aplica el filtro si se proporciona
$result = $conn->query($sql);

// Cierra la conexión
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
            width: 90%; /* Reducir el ancho de la tabla */
            margin: 20px auto;
            border-collapse: collapse;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px; /* Reducir el padding para hacerlo más pequeño */
            text-align: left;
            font-size: 0.9em; /* Reducir el tamaño de fuente */
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
                <li><a href="admin.php">Mi espacio</a></li>
                <li><a href="c.php">Consulta</a></li>
                <li><a href="na.php">Nuevo </a></li>
                <li><a href="cc.php">Cambiar Contraseña</a></li>
                <li><a href="index.html">Cerrar Sesión</a></li>
            </ul>
        </nav>
    </header>

    <h2 style="text-align: center; padding-top: 20px;">Consulta de Usuarios</h2>

    <!-- Formulario para el filtro -->
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
        // Mostrar los datos de cada usuario
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

    // Mostrar el formulario de actualización si se seleccionó un usuario para editar
    if (isset($_GET['editar_id'])) {
        $idEditar = $_GET['editar_id'];

        // Consultar los datos del usuario seleccionado
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
        <input type="password" name="pass" value="<?php echo $usuario['pass']; ?>"><br>
        
        <label>id_escuela:</label><br>
        <input type="text" name="id_escu" value="<?php echo $usuario['id_escu']; ?>"><br>

        <input type="submit" name="actualizar" value="Actualizar">
    </form>
    <?php } ?>
</body>
</html>
