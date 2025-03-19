<?php
session_start(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $host = 'localhost';
    $dbname = 'usuarios';
    $username = 'root';
    $password = '';  

    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $nickname = $_POST['nickname'];
        $pass = $_POST['pass'];  
        $stmt = $pdo->prepare("SELECT * FROM usuario WHERE nickname = :nickname AND pass = :pass");
        $stmt->bindParam(':nickname', $nickname);                              
        $stmt->bindParam(':pass', $pass);  
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['nickname'] = $nickname; 

            
            $nivel = $user['nivel'];  
            
            if ($nivel == 2) {
                header("Location: admin.php"); 
                exit();
            } elseif ($nivel == 3) {
                header("Location: normal.php"); 
                exit();
            } else {
                header("Location: sesion.php"); 
                exit();
            }
        } else {
            
            $error = "Nickname o password incorrectos";
        }
    } catch (PDOException $e) {
        echo "Error de conexión: " . $e->getMessage();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sucemonjo - Datos Escolares del Estado de México</title>
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
        .login-container {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            margin-top: 50px;
        }
        .login-container h2 {
            color: #b71c1c;
            text-align: center;
        }
        .login-container input {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }
        .login-container button {
            width: 100%;
            padding: 10px;
            background-color: #b71c1c;
            color: white;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-container button:hover {
            background-color: #ffccbc;
        }
        .error {
            color: red;
            text-align: center;
        }
        footer {
            background-color: #b71c1c;
            color: white;
            text-align: center;
            padding: 15px;
        }
        footer a {
            color: #ffccbc;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">Sucemonjo</div>
        <nav>
            <ul>
                <li><a href="index.html">Inicio</a></li>
                <li><a href="index.html">Servicios</a></li>
                <li><a href="index.html">Misión</a></li>
                <li><a href="index.html">Contacto</a></li>
                <li><a href="sesion.php">Iniciar sesión</a></li>
            </ul>
        </nav>
    </header>

    <section class="login-container">
        <h2>Iniciar Sesión</h2>
        <form method="POST" action="">
            <input type="text" name="nickname" placeholder="Usuario" required>
            <input type="password" name="pass" placeholder="Contraseña" required>
            <?php if (isset($error)) { echo "<p class='error'>$error</p>"; } ?>
            <button type="submit">Entrar</button>
        </form>
    </section>

    <footer>
        <p>&copy; 2024 Sucemonjo. Todos los derechos reservados. | <a href="index.html">Contáctanos</a></p>
    </footer>
</body>
</html>
