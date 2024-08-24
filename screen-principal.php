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
    <link rel="stylesheet" href="css/screen-principal.css">
    <script src="./js/screen-principal.js" defer></script>
    <title>Estude para o Futuro</title>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/Logo.png" alt="Logo" id="logo"> 
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
            <div class="img-profile">
                <a href="#"><img src="img/profile.svg" alt="Foto do usuário" id="profile"></a>
            </div>

            <div class="dropdown">
                <div class="user">
                    <div class="informations">
                        <?php
                            echo "<p id='name'><u>$username</u></p>
                                  <p id='email'><u>$email</u></p>";
                        ?>
                    </div>
    
                    <div class="infos">
                        <p><span>⚙️</span> Configurações</p>
                        <p><span>↩</span> Log out</p>
                    </div>
                </div>
            </div>
        </div>
    </header>
</body>
</html>