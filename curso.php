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

// Busca a foto do usuário
$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = $user";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$perfil = isset($row['nome_foto']) ? $row['nome_foto'] : "img/profile.svg";

$cursoId = filter_input(INPUT_GET, 'cursoId', FILTER_SANITIZE_NUMBER_INT);
$aulaId = filter_input(INPUT_GET, 'aulaId', FILTER_SANITIZE_NUMBER_INT);

// Inicializa as variáveis
$value = '';
$primeiraAula = '';
$nomeModulo = '';
$videoUrl = 'img/video.mp4'; // URL padrão para o vídeo
$proximaAulaId = null;
$aulaAnteriorId = null;

if ($cursoId) {
    // Consulta para obter o título do curso
    $sql = "SELECT descricao_curso FROM tb_cursos WHERE id_curso = $cursoId";
    $resultado = $conexao->query($sql);

    if ($resultado && $resultado->num_rows > 0) {
        $curso = $resultado->fetch_assoc();
        $value .= $curso['descricao_curso'];

        // Consulta para obter as informações da aula atual, próxima e anterior
        $sqlAula = "
            SELECT a.id_aula, a.nome_aula, a.conteudo_aula, m.nome_modulo,
                   (SELECT id_aula FROM tb_aulas WHERE id_aula < a.id_aula ORDER BY id_aula DESC LIMIT 1) AS aula_anterior,
                   (SELECT id_aula FROM tb_aulas WHERE id_aula > a.id_aula ORDER BY id_aula ASC LIMIT 1) AS proxima_aula
            FROM tb_aulas a
            INNER JOIN tb_modulos_aulas ma ON a.id_aula = ma.cd_aula
            INNER JOIN tb_modulos m ON ma.cd_modulo = m.id_modulo
            INNER JOIN tb_cursos_modulos cm ON m.id_modulo = cm.cd_modulo
            WHERE cm.cd_curso = $cursoId
            ORDER BY m.id_modulo, a.id_aula ASC
        ";

        $resultadoAula = $conexao->query($sqlAula);

        if ($resultadoAula && $resultadoAula->num_rows > 0) {
            while ($aula = $resultadoAula->fetch_assoc()) {
                if ($aulaId == null || $aula['id_aula'] == $aulaId) {
                    $primeiraAula = $aula['nome_aula'];
                    $nomeModulo = $aula['nome_modulo'];
                    $videoUrl = $aula['conteudo_aula'];
                    $proximaAulaId = $aula['proxima_aula'];
                    $aulaAnteriorId = $aula['aula_anterior'];
                    break;
                }
            }
        }
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
        document.addEventListener('DOMContentLoaded', () => {
            const cursoId = <?php echo isset($cursoId) ? $cursoId : 0; ?>;
            const aulaId = <?php echo isset($aulaId) ? $aulaId : 0; ?>;
            const proximaAulaId = <?php echo isset($proximaAulaId) ? $proximaAulaId : 'null'; ?>;
            const aulaAnteriorId = <?php echo isset($aulaAnteriorId) ? $aulaAnteriorId : 'null'; ?>;

            if (cursoId) {
                $.ajax({
                    url: 'ajax.php',
                    type: 'GET',
                    data: {
                        sidebar: true,
                        cursoId: cursoId
                    },
                    success: function(response) {
                        $('#sidebar').html(response);

                        // Dropdown dos módulos
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

                        // Adicionar listeners aos links das aulas
                        document.querySelectorAll('.aula-link').forEach((aula) => {
                            aula.addEventListener('click', (event) => {
                                event.preventDefault();

                                // Pegar o título da aula
                                const tituloAula = aula.textContent;

                                // Pegar o elemento do módulo pai
                                const moduloElement = aula.closest('.module');

                                // Pegar o nome do módulo a partir do h2 dentro do módulo atual
                                const nomeModulo = moduloElement.querySelector('h2').textContent;

                                // Atualizar o conteúdo do h3#titulo-aula com o nome do módulo e da aula
                                document.getElementById('titulo-aula').innerHTML = `<span id="modulo-aula">${nomeModulo}</span> | ${tituloAula}`;
                                
                                // Atualizar a URL do vídeo
                                const videoUrl = aula.getAttribute('data-video');
                                document.getElementById('video').setAttribute('src', videoUrl);
                            });
                        });
                    }
                });
            }

            // Função para navegar entre as aulas
            function navigateToAula(aulaId) {
                window.location.href = `curso.php?cursoId=${cursoId}&aulaId=${aulaId}`;
            }

            // Adiciona o listener para o botão de próxima aula
            const btnProximaAula = document.querySelector('.conclude-button.next');
            if (btnProximaAula && proximaAulaId) {
                btnProximaAula.addEventListener('click', () => {
                    navigateToAula(proximaAulaId);
                });
            }

            // Adiciona o listener para o botão de aula anterior
            const btnAulaAnterior = document.querySelector('.conclude-button.previous');
            if (btnAulaAnterior && aulaAnteriorId) {
                btnAulaAnterior.addEventListener('click', () => {
                    navigateToAula(aulaAnteriorId);
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
                        <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/mp4">
                        Seu navegador não suporta a reprodução de vídeo.
                    </video>
                    <div class="video-controls">
                        Abaixo podemos usar controles nativos ou personalizados, aqui usamos nativos para simplicidade
                    </div>
                </div>
                <div class="lesson-info">
                    <h3 id="titulo-aula"><span id="modulo-aula"><?php echo htmlspecialchars($nomeModulo); ?></span> | <?php echo htmlspecialchars($primeiraAula); ?></h3>
                    <div class="button-container">
                        <button class="conclude-button previous">< Aula anterior</button>
                        <button class="conclude-button">Marcar como concluído</button>
                        <button class="conclude-button next">Próxima aula ></button>
                    </div>
                </div>

                <div class="alerts">
                    <h3>ATENÇÃO: O botão de concluir aula só fica clicável depois que toda a aula é assistida.</h3>
                    <p>* Para avançar no andamento dos cursos você precisará assistir o vídeo até o final, e em seguida marcar a aula como concluída.</p>
                </div>
            </div>
        </div>
    </main>
</body>
</html>

