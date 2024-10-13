<?php

session_start(); 

// BD

$host = ''; 

$dbname = '';    

$username = '';        

$password = '';       


// Ligar BD
try {

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die('Connection error: ' . $e->getMessage());

}



// Adiciona Livros

if (isset($_POST['add'])) {

    $title = $_POST['titulo'];

    $author = $_POST['autor'];

    $price = $_POST['preco'];

    $description = $_POST['descricao'];

// Inserior Livros na BD

    $sql = "INSERT INTO livros (titulo, autor, preco, descricao) VALUES (:titulo, :autor, :preco, :descricao)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':titulo', $title);

    $stmt->bindParam(':autor', $author);

    $stmt->bindParam(':preco', $price);

    $stmt->bindParam(':descricao', $description);

    $stmt->execute();

}



// Apagar Livros

if (isset($_POST['delete'])) {

    $id = $_POST['id'];

    $sql = "DELETE FROM livros WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':id', $id);

    $stmt->execute();

}



// Editar info dos Livros

if (isset($_POST['modify'])) {

    $id = $_POST['id'];

    $title = $_POST['titulo'];

    $author = $_POST['autor'];

    $price = $_POST['preco'];

    $description = $_POST['descricao'];


//Atualizar Dados
    $sql = "UPDATE livros SET titulo = :titulo, autor = :autor, preco = :preco, descricao = :descricao WHERE id = :id";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':titulo', $title);

    $stmt->bindParam(':autor', $author);

    $stmt->bindParam(':preco', $price);

    $stmt->bindParam(':descricao', $description);

    $stmt->bindParam(':id', $id);

    $stmt->execute();

}



// Botao de sair

if (isset($_POST['logout'])) {

    session_destroy();

    $conn = null; 

    header("Location: l.php"); 

    exit();

}



// ver todos os livros 

$sql = "SELECT * FROM livros";

$stmt = $conn->prepare($sql);

$stmt->execute();

$books = $stmt->fetchAll();

?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>StoreName - Dashboard</title>

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

        .submenu {

            text-align: center;

            margin: 10px 0;

        }

        .submenu button {

            background-color: #444;

            margin: 0 10px;

            padding: 10px 15px;

            border: none;

            color: white;

            cursor: pointer;

        }

        .submenu button:hover {

            background-color: #555;

        }

        .container {

            max-width: 1200px;

            margin: auto;

            padding: 20px;

        }

        .welcome-message {

            text-align: center;

            margin-bottom: 20px;

            font-size: 18px;

        }

        .book {

            background-color: white;

            padding: 20px;

            margin-bottom: 20px;

            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.1);

        }

        .book-actions {

            text-align: right;

        }

        .add-book-form {

            margin-bottom: 30px;

            background-color: white;

            padding: 20px;

            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0,0,0,0.1);

        }

        input[type="text"], input[type="number"], textarea {

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

            background-color: #dc3545; 

        }

        .logout-button:hover {

            background-color: #c82333; 

        }

    </style>

</head>

<body>



    <div class="navbar">

        <form action="" method="POST" style="display:inline;">

            <button type="submit" name="logout" class="logout-button">Logout</button>  <!-- Boitao de sair -->

        </form>

    </div>



    <div class="submenu">

        <form action="profile.php" method="GET" style="display:inline;">

            <button type="submit">Profile</button>  <!-- Botao perfil-->

        </form>

        <form action="address.php" method="GET" style="display:inline;">

            <button type="submit">Address</button>  <!--Botao morada-->

        </form>

    </div>



    <div class="container">



        <div class="welcome-message">

            <?php if (isset($_SESSION['username'])): ?>

                <h2>Bem-vindo, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>  <!-- Suadação -->

            <?php else: ?>

                <h2>Bem-vindo!</h2>

            <?php endif; ?>

        </div>



        <div class="add-book-form">  <!-- Adicionar Livros -->

            <h2>Books</h2> 

            <form action="" method="POST">

                <input type="text" name="titulo" placeholder="Book Title" required>

                <input type="text" name="autor" placeholder="Book Author" required>

                <input type="number" step="0.01" name="preco" placeholder="Price" required>

                <textarea name="descricao" placeholder="Book Description" required></textarea>

                <button type="submit" name="add">Add Book</button>

            </form>

        </div>



        <?php foreach ($books as $book): ?>  <!-- Editar/Eliminar Livros -->

            <div class="book">

                <h3><?php echo htmlspecialchars($book['titulo']); ?></h3>

                <p><strong>Author:</strong> <?php echo htmlspecialchars($book['autor']); ?></p>

                <p><strong>Price:</strong> €<?php echo htmlspecialchars($book['preco']); ?></p>

                <p><?php echo htmlspecialchars($book['descricao']); ?></p>

                <div class="book-actions">

                    <form action="" method="POST" style="display:inline;">

                        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

                        <button type="submit" name="delete">Delete</button>

                    </form>

                    <form action="" method="POST" style="display:inline;">

                        <input type="hidden" name="id" value="<?php echo $book['id']; ?>">

                        <input type="text" name="titulo" value="<?php echo htmlspecialchars($book['titulo']); ?>" required>

                        <input type="text" name="autor" value="<?php echo htmlspecialchars($book['autor']); ?>" required>

                        <input type="number" step="0.01" name="preco" value="<?php echo htmlspecialchars($book['preco']); ?>" required>

                        <textarea name="descricao" required><?php echo htmlspecialchars($book['descricao']); ?></textarea>

                        <button type="submit" name="modify">Modify</button>

                    </form>

                </div>

            </div>

        <?php endforeach; ?>



    </div>



</body>

</html>

