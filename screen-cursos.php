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
    <link rel="stylesheet" href="css/screen-cursos.css">
    <script src="./js/screen-cursos.js" defer></script>
    <title>Estude para o Futuro</title>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/Logo.png" alt="Logo" id="logo"> 
            <!-- Logo header -->
            <nav>
                <ul>
                    <a href="">
                        <li id="cursos">CURSOS</li>
                    </a>
                    <a href="">
                        <li>MINHA CONTA</li> 
                    </a>                      
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
                            echo "<p><u id='name'>$username</u></p>
                                  <p><u id='email'>$email</u></p>";
                        ?>
                    </div>
    
                    <div class="infos">
                            <p><span>⚙️</span> Configurações</p>

                        <a href="logOut.php">
                            <p id="logout"><span>↩</span> Log out</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
    <div class="courses-container">
        <h2>Cursos disponíveis:</h2>
        <div class="course-card">
            <div class="course-header">
                <span class="course-icon">⭐</span> <!-- Placeholder for the icon -->
                <a href="curso.php"><button class="free-label">INICIAR</button></a>
            </div>
            <h3>INICIANTE</h3>
            <p>CURSO DE EDUCAÇÃO FINAINCEIRA PARA INCIANTES</p>
        </div>
        <div class="course-card">
            <div class="course-header">
                <span class="course-icon">⭐⭐</span> <!-- Placeholder for the icon -->
                <a href="curso.php"><button class="free-label">INICIAR</button></a>
            </div>
            <h3>INTERMEDIÁRIO</h3>
            <p>CURSO DE EDUCAÇÃO FINANCEIRA PARA INTERMEDIARIOS</p>
        </div>
        <div class="course-card">
            <div class="course-header">
                <span class="course-icon">⭐⭐⭐</span> <!-- Placeholder for the icon -->
                <a href="curso.php"><button class="free-label">INICIAR</button></a>
            </div>
            <h3>EXPERIENTE</h3>
            <p>CURSO DE EDUCAÇÃO FINANCEIRA PARA EXPERIENTES</p>
        </div>
    </div>
    </main>
</body>
</html>