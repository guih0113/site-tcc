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

if (isset($_GET['cursoId'])) {
    $cursoId = $_GET['cursoId'];

    // Consulta direta para obter o título do curso
    $sql = "SELECT descricao_curso FROM tb_cursos WHERE id_curso = $cursoId";
    $resultado = $conexao->query($sql); // Executa a consulta
    
    $value = ''; // Inicializa a variável

    if ($resultado && $resultado->num_rows > 0) {
        $curso = $resultado->fetch_assoc(); // Obtém o resultado como array associativo
        $value .= $curso['descricao_curso']; // Armazena a descrição do curso
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/curso.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/curso.js" defer></script>
    <title>Estude para o Futuro</title>

    <script>
        $(document).ready(function() {
            // ID do curso passado via GET
            const cursoId = <?php echo isset($cursoId) ? $cursoId : 0; ?>;
            
            if (cursoId) {
                // Carregar os módulos via AJAX
                $.ajax({
                    url: 'ajax.php',
                    type: 'GET',
                    data: {
                        sidebar: true,
                        cursoId: cursoId
                    },
                    success: function(response) {
                        // Insere o conteúdo retornado no sidebar
                        $('#sidebar').html(response);

                        // Reaplicar o event listener após carregar o conteúdo via AJAX
                        const dropdownModules = document.querySelectorAll('.dropdown-toggle');
                        dropdownModules.forEach((dropdownModule) => {
                            const dropdownContent = dropdownModule.nextElementSibling;
                            
                            dropdownModule.addEventListener('click', () => {
                                dropdownContent.classList.toggle('active');
                                if (dropdownContent.classList.contains('active')) {
                                    dropdownContent.style.height = dropdownContent.scrollHeight + 'px';
                                } else {
                                    dropdownContent.style.height = '0';
                                }
                            });
                        });
                    }
                });
            }
        });
    </script>
</head>

<body>
    <header>
        <div class="container">
            <a href="indexLogado.php"><img src="img/Logo.png" alt="Logo" id="logo"></a>
            <nav>
                <ul>
                    <a href="indexLogado.php">
                        <li>HOME</li>
                    </a>
                    <a href="screen-cursos.php">
                        <li>CURSOS</li>
                    </a>
                    <a href="conta.php">
                        <li>MINHA CONTA</li>
                    </a>
                </ul>
            </nav>
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
        <div id="title">
            <h1><?php echo $value; ?></h1>

            <!-- Barra de progresso -->
            <div class="progress-bar-container">
                <div class="progress-bar">65%</div>
            </div>

            <div class="progress-text">
                65% Concluído
            </div>
        </div>

        <div class="main-container">
            <div class="sidebar" id="sidebar">
                
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
        </div>
    </main>
</body>
</html>

