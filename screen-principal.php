<?php
include_once('conexao1.php');
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
    <link rel="stylesheet" href="css/screen-principal.css">
    <script src="./js/screen-principal.js" defer></script>
    <title>Estude para o Futuro</title>
</head>
<body>
    <?php
        echo "<h1>Bem vindo <u>$username</u></h1>";
    ?>
</body>
</html>