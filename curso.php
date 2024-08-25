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
    <link rel="stylesheet" href="css/curso.css">
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
                    <a href="screen-cursos.php">
                        <li>CURSOS</li>
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
        <div class="sidebar">
            <div class="module">
                <h2 class="dropdown-toggle">Módulo 1 &#x25BC;</h2>
                <ul class="dropdown-content">
                    <li><a href="#">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>
            <div class="module">
                <h2 class="dropdown-toggle">Módulo 2 &#x25BC;</h2>
                <ul class="dropdown-content">
                    <li><a href="#">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>
            <div class="module">
                <h2 class="dropdown-toggle">Módulo 3 &#x25BC;</h2>
                <ul class="dropdown-content">
                    <li><a href="#">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>
            <div class="module">
                <h2 class="dropdown-toggle">Módulo 4 &#x25BC;</h2>
                <ul class="dropdown-content">
                    <li><a href="#">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>

            <div class="module">
                <h2 class="dropdown-toggle">Módulo 5 &#x25BC;</h2>
                <ul class="dropdown-content">
                    <li><a href="#">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>
        </div>
        <div class="content">
            <div class="video-player">
                <video id="video" width="100%" controls>
                    <source src="img/video.mp4" type="video/mp4">
                    Seu navegador não suporta a reprodução de vídeo.
                </video>
                <div class="video-controls">
                    Abaixo podemos usar controles nativos ou personalizados, aqui usamos nativos para simplicidade -->
                </div>
            </div>
            <div class="lesson-info">
                <h3>Aula 1: xxxxxxxxxxxx</h3>
                <button class="download-button">Baixe o material</button>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
            </div>
        </div>
    </main>
</body>