<?php
session_start();

if((!isset($_SESSION['email']) == true) and (!isset($_SESSION['senha']) == true)){
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/screen-principal.css">
    <title>Estude para o Futuro</title>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/Logo.png" alt="Logo"> 
            <!-- Logo header -->
            <nav>
                <ul>
                    <li>HOME</li>
                    <li>SOBRE</li>
                    <li>CURSOS</li>
                    <li>DÚVIDAS</li>
                    <li>CONTATO</li>                  
                </ul>
            </nav>
            <!--Nav header -->
            <div class="dropdown">
                <a href="login.php"><input type="submit" value="Log in"></a>
                <a href="cadastro.php"><input type="submit" value="Cadastre-se"></a>
            </div>
            <!-- <BUtton>Login</BUtton> -->

            <!-- buttom -->
        </div>
    </header>
    <?php
        echo "<h1>Bem vindo <u>$username</u></h1>";
    ?>
</body>
</html>