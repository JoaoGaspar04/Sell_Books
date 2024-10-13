<?php

session_start(); 



// Se oUtilizador exista

if (!isset($_SESSION['username'])) {

    header("Location: l.php"); // Caso não exisa

    exit();

}



// BD

$host = ''; 

$dbname = '';    

$username = '';        

$password = '';       



try {

    // Ligar BD

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die('Connection error: ' . $e->getMessage());

}



// Obtém informações 

$currentUsername = $_SESSION['username'];

$sql = "SELECT email FROM users WHERE username = :username"; 
$stmt = $conn->prepare($sql);

$stmt->bindParam(':username', $currentUsername);

$stmt->execute();

$userInfo = $stmt->fetch(PDO::FETCH_ASSOC); // Obtém as informações do Utilizador



// Botao Sair

if (isset($_POST['logout'])) {

    session_destroy();

    header("Location: l.php"); // Página de login

    exit();

}

?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StoreName - Profile</title>

    <style>

        body {

            font-family: Arial, sans-serif;

            margin: 0;

            padding: 0;

            background: #f9f9f9;

        }

        .navbar {

            background-color: #333;

            padding: 1rem;

            text-align: center;

        }

        .navbar a {

            color: white;

            text-decoration: none;

            padding: 14px 20px;

            font-size: 18px;

        }

        .navbar a:hover {

            background-color: #ddd;

            color: black;

        }

        .container {

            max-width: 800px;

            margin: auto;

            padding: 20px;

        }

        h2 {

            text-align: center;

        }

        .user-info {

            background-color: white;

            padding: 20px;

            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.1);

            margin-bottom: 20px;

        }

        .logout-button {

            display: block;

            margin: auto;

            padding: 10px 20px;

            background-color: #dc3545;

            color: white;

            border: none;

            border-radius: 5px;

            cursor: pointer;

            text-align: center;

        }

        .logout-button:hover {

            background-color: #c82333;

        }

    </style>

</head>

<body>



    <div class="navbar">

        <a href="dash.php">Home</a> <!-- Menu -->



            <div class="form-section"> <!-- Botao Sair-->
        <form action="" method="POST" style="display:inline;">

            <button type="submit" name="logout" class="logout-button">Logout</button>

        </form>

    </div>



    <div class="container"> <!-- Perfil -->

        <h2>Perfil do Utilizador</h2>

        <div class="user-info">

            <h3>Bem-vindo, <?php echo htmlspecialchars($currentUsername); ?>!</h3>

            <p><strong>Nome de Utilizador:</strong> <?php echo htmlspecialchars($currentUsername); ?></p>

            <p><strong>Email:</strong> <?php echo htmlspecialchars($userInfo['email']); ?></p>

        </div>

    </div>



</body>

</html>

