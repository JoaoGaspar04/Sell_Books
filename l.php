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

// Botao Saida
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: login_register.php"); 
    exit();
}

// Adicionar 
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['id'];
    $quantity = 1; 

    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id] += $quantity;
    } else {
        $_SESSION['cart'][$id] = $quantity;
    }

    echo "<script>alert('Livro adicionado ao carrinho!');</script>";
}

// Liimpar
if (isset($_POST['clear_cart'])) {
    unset($_SESSION['cart']);
}
//Ver Livros
$sql = "SELECT * FROM livros"; 
$stmt = $conn->prepare($sql);
$stmt->execute();
$livros = $stmt->fetchAll(PDO::FETCH_ASSOC); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StoreName - Books</title>
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
        .livro {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            display: flex;
            align-items: center;
        }
        .livro img {
            max-width: 100px;
            margin-right: 20px;
        }
        .livro-details {
            flex: 1;
        }
        .livro-actions {
            text-align: right;
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
        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
        .cart-icon {
            position: fixed;
            right: 20px;
            top: 20px;
            cursor: pointer;
            font-size: 24px;
            color: #333;
        }
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgb(0,0,0); 
            background-color: rgba(0,0,0,0.4); 
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto; 
            padding: 20px;
            border: 1px solid #888;
            width: 80%; 
            max-width: 500px;
            border-radius: 8px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
        .cart-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        .cart-item img {
            max-width: 50px;
            margin-right: 10px;
        }
        .total {
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <!--Menu  -->
    <div class="navbar">
        <a href="index.php">Home</a>
        <a href="l.php">Livros</a>
        <a href="lr.php">Login/Register</a>

    <!-- Carrinho  -->

    <div class="container">
        <h2>NomedaLoja- Livros</h2>

        <?php foreach ($livros as $livro): ?>
            <div class="livro">
                <img src="uploads/<?php echo htmlspecialchars($livro['imagem']); ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                <div class="livro-details">
                    <h3><?php echo htmlspecialchars($livro['titulo']); ?></h3>
                    <p><strong>Autor:</strong> <?php echo htmlspecialchars($livro['autor']); ?></p>
                    <p><strong>Preço:</strong> €<?php echo number_format($livro['preco'], 2); ?></p>
                    <p><?php echo htmlspecialchars($livro['descricao']); ?></p>
                </div>
                <div class="livro-actions">
                    <form action="" method="POST">
                        <input type="hidden" name="id" value="<?php echo $livro['id']; ?>">
                        <button type="submit" name="add_to_cart">Adicionar ao Carrinho</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="cart-icon" onclick="document.getElementById('cartModal').style.display='block'">
        🛒
    </div>

<div id="cartModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="document.getElementById('cartModal').style.display='none'">&times;</span>
        <h2>Itens no Carrinho</h2>
        <div id="cartItems">
            <?php if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
                <?php 
                $total = 0;
                foreach ($_SESSION['cart'] as $id => $quantity): 
                    $sql = "SELECT * FROM livros WHERE id = :id"; 
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':id', $id);
                    $stmt->execute();
                    $livro = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($livro) { 
                        $totalPrice = $livro['preco'] * $quantity;
                        $total += $totalPrice;
                ?>
                    <div class="cart-item">
                        <img src="uploads/<?php echo htmlspecialchars($livro['imagem']); ?>" alt="<?php echo htmlspecialchars($livro['titulo']); ?>">
                        <div>
                            <strong><?php echo htmlspecialchars($livro['titulo']); ?></strong> - Quantidade: <?php echo $quantity; ?> - Preço: €<?php echo number_format($totalPrice, 2); ?>
                        </div>
                    </div>
                <?php 
                    }
                endforeach; 
                ?>
                <div class="total">Valor Total: €<?php echo number_format($total, 2); ?></div>
                <div class="total">Frete: €5.00</div> 
                <div class="total">Total com Frete: €<?php echo number_format($total + 5.00, 2); ?></div>
                <form action="" method="POST">
                    <button type="submit" name="clear_cart" style="margin-top: 20px; padding: 10px 20px; background-color: #dc3545; border: none; border-radius: 5px; color: white; cursor: pointer;">Limpar Carrinho</button>
                </form>
                <div style="margin-top: 20px;">
                    <form action="ccc.php" method="GET" style="display:inline;">
                        <button type="submit" style="padding: 10px 20px; background-color: #007bff; border: none; border-radius: 5px; color: white; cursor: pointer;">Continuar como Convidado</button>
                    </form>
                    <form action="lr.php" method="GET" style="display:inline;">
                        <button type="submit" style="padding: 10px 20px; background-color: #007bff; border: none; border-radius: 5px; color: white; cursor: pointer;">Continuar como Utilizador</button>
                    </form>
                </div>
            <?php else: ?>
                <p>Seu carrinho está vazio.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

    <footer>
        <p>© 2024 Livraria Online. Todos os direitos reservados.</p>
    </footer>

    <script>
        window.onclick = function(event) {
            const modal = document.getElementById('cartModal');
            if (event.target === modal) {
                modal.style.display = "none";
            }
        }
    </script>
</body>
</html>
