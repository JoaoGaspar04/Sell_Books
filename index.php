<?php
//BD

$host = ''; 
$dbname = '';    
$username = '';        
$password ='';         
//ligar BD
try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Connection error: ' . $e->getMessage());
}
//Pesquisar Livros
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
    <title>StoreName - Home</title>
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
            max-width: 1200px;
            margin: auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .info {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .book {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        .book img {
            max-width: 100px;
            margin-right: 20px;
        }
        .book-details {
            flex: 1;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>
<body>

    <div class="navbar"> <!-- Menu-->
        <a href="index.php">Home</a>
        <a href="l.php">Books</a>
        <a href="lr.php">Login/Registration</a>
    </div>

    <div class="container">
        <h1>Welcome to StoreName!</h1>

        <div class="info"><!-- Introdução-->
            <h2>About Us</h2>
            <p>At StoreName, we offer a wide selection of books from different genres. Browse through our collection and find your next favorite!</p>
        </div>

        <h2>Our Books</h2>

        <?php foreach ($books as $book): ?>
            <div class="book">
                <img src="uploads/<?php echo htmlspecialchars($book['imagem']); ?>" alt="<?php echo htmlspecialchars($book['titulo']); ?>">
                <div class="book-details">
                    <h3><?php echo htmlspecialchars($book['titulo']); ?></h3>
                    <p><strong>Author:</strong> <?php echo htmlspecialchars($book['autor']); ?></p>
                    <p><strong>Price:</strong> €<?php echo htmlspecialchars($book['preco']); ?></p>
                    <p><?php echo htmlspecialchars($book['descricao']); ?></p>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <footer> <!-- Fooster-->
        <p>© 2024 StoreName. All rights reserved.</p>
    </footer>

</body>
</html>
