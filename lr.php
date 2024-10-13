<?php
//BD 

$host = '';
$dbname = '';    
$username = '';        
$password = '';         

//Ligar BD

try {

    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {

    die('Connection error: ' . $e->getMessage());

}

//Utilizador

function registerUser($conn, $username, $password, $email) {

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, email) VALUES (:username, :password, :email)";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':username', $username);

    $stmt->bindParam(':password', $hashedPassword);

    $stmt->bindParam(':email', $email);

    $stmt->execute();

}

//Utilizador

function loginUser($conn, $username, $password) {

    $sql = "SELECT * FROM users WHERE username = :username";

    $stmt = $conn->prepare($sql);

    $stmt->bindParam(':username', $username);

    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);


// Verificar 

    if ($user && password_verify($password, $user['password'])) {

        session_start();

        $_SESSION['user_id'] = $user['id'];

        $_SESSION['username'] = $user['username'];

        return true;

    }

    return false;

}



if (isset($_POST['register'])) {

    $username = $_POST['reg_username'];

    $password = $_POST['reg_password'];

    $email = $_POST['reg_email'];



    registerUser($conn, $username, $password, $email);

    echo "<script>alert('User registered successfully!');</script>";

}



if (isset($_POST['login'])) {

    $username = $_POST['login_username'];

    $password = $_POST['login_password'];



    if (loginUser($conn, $username, $password)) {

        header("Location: dash.php"); 

        exit();

    } else {

        echo "<script>alert('Invalid username or password!');</script>";

    }

}

?>



<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login and Registration</title>

    <style>

        body {

            font-family: Arial, sans-serif;

            background: #f9f9f9;

            display: flex;

            flex-direction: column;

            min-height: 100vh;

            margin: 0;

        }

        .container {

            flex: 1; 

            display: flex;

            justify-content: center;

            align-items: center;

            padding: 20px;

        }

        .form-wrapper {

            display: flex;

            justify-content: space-between;

            width: 800px; 

        }

        .form-section {

            width: 350px; 

            background-color: white;

            border-radius: 8px;

            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);

            padding: 20px;

            margin: 10px; 
        }

        h2 {

            text-align: center;

        }

        input[type="text"], input[type="password"], input[type="email"] {

            width: 100%;

            padding: 10px;

            margin: 10px 0;

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

            width: 100%;

        }

        button:hover {

            background-color: #218838;

        }

        footer {

            text-align: center;

            margin-top: 20px;

            color: #777;

            padding: 10px;

            background-color: #fff; 

            position: relative; 

            bottom: 0;

            width: 100%;

        }

    </style>

</head>

<body>



    <div class="container">

        <div class="form-wrapper">

            <div class="form-section"><!-- Formulários -->

                <h2>Login</h2>   

                <form action="" method="POST">

                    <input type="text" name="login_username" placeholder="Username" required>

                    <input type="password" name="login_password" placeholder="Password" required>

                    <button type="submit" name="login">Log In</button>

                </form>

            </div>

            <div class="form-section">

                <h2>Register</h2>

                <form action="" method="POST">

                    <input type="text" name="reg_username" placeholder="Username" required>

                    <input type="password" name="reg_password" placeholder="Password" required>

                    <input type="email" name="reg_email" placeholder="Email" required>

                    <button type="submit" name="register">Register</button>

                </form>

            </div>

        </div>

    </div>



    <footer>

        <p>&copy; 2024 Online Bookstore. All rights reserved.</p>

    </footer>



</body>

</html>

