<?php

session_start(); 



// Verifica se o utilizador tem o login feito

if (!isset($_SESSION['username'])) {

    header("Location: l.php"); // caso nao vai para a pagina de login 

    exit();

}



// BD

$host = ''; 

$dbname = '';    

$username = '';        

$password = '';       



try {

    // Ligar a BD

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die('Connection error: ' . $e->getMessage()); // erro de conexao a BD

}



// Adicion morada

if (isset($_POST['add'])) {

    $address = $_POST['morada'];

    $userId = $_SESSION['user_id']; // Armazenar o id do utilizador



    $sql = "INSERT INTO addresses (user_id, address) VALUES (:user_id, :address)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':user_id', $userId);

    $stmt->bindParam(':address', $address);

    $stmt->execute();

}



// Obter moradas do utilizador

$userId = $_SESSION['user_id']; // Armazenar o id do utilizador

$sql = "SELECT * FROM addresses WHERE user_id = :user_id";

$stmt = $conn->prepare($sql);

$stmt->bindParam(':user_id', $userId);

$stmt->execute();

$addresses = $stmt->fetchAll(PDO::FETCH_ASSOC);



// Logout

if (isset($_POST['logout'])) {

    session_destroy();

    header("Location: l.php"); // Redireciona para a página de login

    exit();

}

?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StoreName - Address</title>

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

        .address-form {

            background-color: white;

            padding: 20px;

            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.1);

            margin-bottom: 20px;

        }

        .address {

            background-color: white;

            padding: 20px;

            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.1);

            margin-bottom: 20px;

        }

        input[type="text"] {

            width: 100%;

            padding: 10px;

            margin: 5px 0;

            border-radius: 5px;

            border: 1px solid #ccc;

        }

        button {

            padding: 10px 20px;

            background-color: #28a745;

            color: white;

            border: none;

            border-radius: 5px;

            cursor: pointer;

        }

        button:hover {

            background-color: #218838;

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

        <a href="dash.php">Home</a> <!-- Dashboard Pagina -->

        <form action="" method="POST" style="display:inline;">

            <button type="submit" name="logout" class="logout-button">Logout</button>  <!-- Botão de sair  -->

        </form>

    </div>



    <div class="container"> <!-- Moradas -->

        <h2>Gerenciar Moradas</h2>

        

        <div class="address-form">  <!-- Adiicionar Morada -->

            <h3>Adicionar Morada</h3>

            <form action="" method="POST">

                <input type="text" name="morada" placeholder="Digite sua morada" required>

                <button type="submit" name="add">Adicionar Morada</button>

            </form>

        </div>



        <h3>Suas Moradas</h3>

        <?php if (count($addresses) > 0): ?>  <!--Ver Moradas Existentes  -->

            <?php foreach ($addresses as $address): ?>

                <div class="address">

                    <p><?php echo htmlspecialchars($address['address']); ?></p>

                </div>

            <?php endforeach; ?>

        <?php else: ?> <!-- Caso nao encontre moradas-->

            <p>Nenhuma morada encontrada.</p>

        <?php endif; ?>

    </div>



</body>

</html>

