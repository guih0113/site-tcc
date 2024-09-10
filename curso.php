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
$user = $_SESSION['id'];

$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = $user";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$perfil = isset($row['nome_foto']) ? $row['nome_foto'] : "img/profile.svg";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/curso.css">
    <script src="./js/curso.js" defer></script>
    <title>Estude para o Futuro</title>
</head>

<body>
    <header>
        <div class="container">
            <a href="indexLogado.php"><img src="img/Logo.png" alt="Logo" id="logo"></a>
            <!-- Logo header -->
            <nav>
                <ul>
                    <a href="screen-cursos.php">
                        <li>CURSOS</li>
                    </a>
                    <a href="conta.php">
                        <li>MINHA CONTA</li>
                    </a>
                </ul>
            </nav>
            <!--Nav header -->
            <div class="img-profile">
                <a href="#"><img src="<?php echo $perfil; ?>" alt="Foto do usuário" id="profile"></a>
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
                        <a href="conta.php">
                            <p id="config"><span>⚙️</span> Configurações</p>
                        </a>

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
            <div class="module" data-modulo="Módulo 1">
                <h2 class="dropdown-toggle">Módulo 1</h2>
                <ul class="dropdown-content">
                    <li><a href="#" class="aula-link" data-titulo="Aula 1: XXXXXXXXXX">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 2: XXXXXXXXXX">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 3: XXXXXXXXXX">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 4: XXXXXXXXXX">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 5: XXXXXXXXXX">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 6: XXXXXXXXXX">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 7: XXXXXXXXXX">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 8: XXXXXXXXXX">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>
            <div class="module" data-modulo="Módulo 2">
                <h2 class="dropdown-toggle">Módulo 2</h2>
                <ul class="dropdown-content">
                    <li><a href="#" class="aula-link" data-titulo="Aula 1: XXXXXXXXXX">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 2: XXXXXXXXXX">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 3: XXXXXXXXXX">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 4: XXXXXXXXXX">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 5: XXXXXXXXXX">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 6: XXXXXXXXXX">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 7: XXXXXXXXXX">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 8: XXXXXXXXXX">Aula 8: xxxxxxxxxxxx</a></li>
                </ul>
            </div>
            <div class="module" data-modulo="Módulo 3">
                <h2 class="dropdown-toggle">Módulo 3</h2>
                <ul class="dropdown-content">
                    <li><a href="#" class="aula-link" data-titulo="Aula 1: XXXXXXXXXX">Aula 1: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 2: XXXXXXXXXX">Aula 2: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 3: XXXXXXXXXX">Aula 3: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 4: XXXXXXXXXX">Aula 4: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 5: XXXXXXXXXX">Aula 5: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 6: XXXXXXXXXX">Aula 6: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 7: XXXXXXXXXX">Aula 7: xxxxxxxxxxxx</a></li>
                    <li><a href="#" class="aula-link" data-titulo="Aula 8: XXXXXXXXXX">Aula 8: xxxxxxxxxxxx</a></li>
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
                    Abaixo podemos usar controles nativos ou personalizados, aqui usamos nativos para simplicidade
                </div>
            </div>
            <div class="lesson-info">
                <h3 id="titulo-aula"><span id="modulo-aula">Módulo 1</span> | Aula 1: xxxxxxxxxxxx</h3>
                <button class="download-button">Baixe o material</button>
            </div>
        </div>
    </main>
</body>
</html>