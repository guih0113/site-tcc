<?php
include_once('conexao1.php');
session_start();

if (!isset($_SESSION['email']) || !isset($_SESSION['senha'])) {
    unset($_SESSION['email']);
    unset($_SESSION['senha']);
    header('Location: login.php');
    exit;
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];
$user = $_SESSION['id'];

// Busca a foto do usuário
$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$res = $stmt->get_result();
$row = $res->fetch_assoc();
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
$aulaConcluida = false; // Variável para controlar se a aula está concluída

if ($cursoId && $aulaId) {
    // Verifica se a aula já foi concluída pelo usuário
    $sqlConcluida = "SELECT concluida FROM tb_usuario_aulas WHERE cd_usuario = ? AND cd_aula = ?";
    $stmtConcluida = $conexao->prepare($sqlConcluida);
    $stmtConcluida->bind_param("ii", $user, $aulaId);
    $stmtConcluida->execute();
    $resConcluida = $stmtConcluida->get_result();
    if ($resConcluida && $resConcluida->num_rows > 0) {
        $rowConcluida = $resConcluida->fetch_assoc();
        if ($rowConcluida['concluida'] == 1) {
            $aulaConcluida = true; // Marca como concluída
        }
    }

    // Consulta para obter o título do curso
    $sql = "SELECT descricao_curso FROM tb_cursos WHERE id_curso = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $cursoId);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $curso = $resultado->fetch_assoc();
        $value .= htmlspecialchars($curso['descricao_curso']);

        // Consulta para obter as informações da aula atual, próxima e anterior
        $sqlAula = "
            SELECT a.id_aula, a.nome_aula, a.conteudo_aula, m.nome_modulo,
                   (SELECT id_aula FROM tb_aulas WHERE id_aula < a.id_aula ORDER BY id_aula DESC LIMIT 1) AS aula_anterior,
                   (SELECT id_aula FROM tb_aulas WHERE id_aula > a.id_aula ORDER BY id_aula ASC LIMIT 1) AS proxima_aula
            FROM tb_aulas a
            INNER JOIN tb_modulos_aulas ma ON a.id_aula = ma.cd_aula
            INNER JOIN tb_modulos m ON ma.cd_modulo = m.id_modulo
            INNER JOIN tb_cursos_modulos cm ON m.id_modulo = cm.cd_modulo
            WHERE cm.cd_curso = ?
            ORDER BY m.id_modulo, a.id_aula ASC
        ";

        $stmtAula = $conexao->prepare($sqlAula);
        $stmtAula->bind_param("i", $cursoId);
        $stmtAula->execute();
        $resultadoAula = $stmtAula->get_result();

        if ($resultadoAula && $resultadoAula->num_rows > 0) {
            while ($aula = $resultadoAula->fetch_assoc()) {
                if ($aula['id_aula'] == $aulaId) {
                    $primeiraAula = htmlspecialchars($aula['nome_aula']);
                    $nomeModulo = htmlspecialchars($aula['nome_modulo']);
                    $videoUrl = htmlspecialchars($aula['conteudo_aula']);
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
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/curso.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="./js/curso.js" defer></script>
    <title>Estude para o Futuro</title>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
        const cursoId = <?php echo json_encode($cursoId); ?>;
        const aulaId = <?php echo json_encode($aulaId); ?>;
        const proximaAulaId = <?php echo json_encode($proximaAulaId); ?>;
        const aulaAnteriorId = <?php echo json_encode($aulaAnteriorId); ?>;
        const aulaConcluida = <?php echo json_encode($aulaConcluida); ?>;

        const btnMarcarConcluido = document.getElementById('btnMarcarConcluido');
        const btnProximaAula = document.querySelector('.conclude-button.next');
        const btnAulaAnterior = document.querySelector('.conclude-button.previous');
        const video = document.getElementById('video');

        // Inicialização: Controle do estado dos botões com base no status de conclusão da aula
        btnMarcarConcluido.disabled = true;
        btnProximaAula.disabled = true;

        if (aulaConcluida) {
            btnMarcarConcluido.innerHTML = 'Aula Concluída!';
            btnMarcarConcluido.disabled = true;
            btnMarcarConcluido.style.backgroundColor = '#4caf50';
            btnProximaAula.disabled = false;
        } else {
            btnMarcarConcluido.innerHTML = 'Marcar como Concluída';
            btnMarcarConcluido.disabled = false;
        }

        // Desbloquear o botão "Marcar como Concluído" quando o vídeo termina
        video.addEventListener('ended', () => {
            btnMarcarConcluido.disabled = false;
        });

        // Função para marcar a aula como concluída via AJAX
        btnMarcarConcluido.addEventListener('click', () => {
            $.ajax({
                type: 'POST',
                url: 'ajax.php',
                data: {
                    action: 'marcar_concluido',
                    aulaId: aulaId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        btnMarcarConcluido.innerHTML = 'Aula Concluída!';
                        btnMarcarConcluido.disabled = true;
                        btnMarcarConcluido.style.backgroundColor = '#4caf50';
                        btnProximaAula.disabled = false;
                    } else {
                        alert("Erro ao marcar a aula como concluída: " + response.error);
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erro na requisição AJAX: ", textStatus, errorThrown);
                }
            });
        });

        // Função para navegar entre aulas
        function navigateToAula(aulaId) {
            window.location.href = `curso.php?cursoId=${cursoId}&aulaId=${aulaId}`;
        }

        // Botão "Próxima Aula"
        if (btnProximaAula && proximaAulaId) {
            btnProximaAula.addEventListener('click', () => {
                navigateToAula(proximaAulaId);
            });
        }

        // Botão "Aula Anterior"
        if (btnAulaAnterior && aulaAnteriorId) {
            btnAulaAnterior.addEventListener('click', () => {
                navigateToAula(aulaAnteriorId);
            });
        }

        // Atualização da URL e do conteúdo ao clicar nas aulas da sidebar
        const aulaLinks = document.querySelectorAll('.aula-link');
        aulaLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const clickedAulaId = this.getAttribute('data-aulaId');
                const videoUrl = this.getAttribute('data-video');
                const tituloAula = this.textContent;
                const nomeModulo = this.closest('.module').querySelector('h2').textContent;

                // Atualizar o título da aula e o vídeo
                document.getElementById('titulo-aula').innerHTML = `<span id="modulo-aula">${nomeModulo}</span> | ${tituloAula}`;
                document.getElementById('video').setAttribute('src', videoUrl);

                // Atualizar a URL sem recarregar a página
                const novaUrl = `${window.location.pathname}?cursoId=${cursoId}&aulaId=${clickedAulaId}`;
                history.pushState(null, null, novaUrl);

                // Marcar a aula como concluída (via AJAX) quando for clicada
                fetch('ajax.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: new URLSearchParams({
                        'action': 'marcar_concluido',
                        'aulaId': clickedAulaId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.parentElement.classList.add('completed');
                    } else {
                        console.error('Erro ao marcar aula como concluída:', data.error);
                    }
                })
                .catch(error => console.error('Erro na requisição:', error));
            });
        });

        // Atualização da sidebar com os módulos e aulas
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

                    const dropdownModules = document.querySelectorAll('.dropdown-toggle');
                    dropdownModules.forEach(dropdownModule => {
                        const dropdownContent = dropdownModule.nextElementSibling;

                        dropdownModule.addEventListener('click', () => {
                            dropdownContent.classList.toggle('active');
                            dropdownContent.style.height = dropdownContent.classList.contains('active') ? dropdownContent.scrollHeight + 'px' : '0';
                        });
                    });

                    document.querySelectorAll('.aula-link').forEach(aula => {
                        aula.addEventListener('click', (event) => {
                            event.preventDefault();

                            const tituloAula = aula.textContent;
                            const moduloElement = aula.closest('.module');
                            const nomeModulo = moduloElement.querySelector('h2').textContent;

                            document.getElementById('titulo-aula').innerHTML = `<span id="modulo-aula">${nomeModulo}</span> | ${tituloAula}`;
                            const videoUrl = aula.getAttribute('data-video');
                            document.getElementById('video').setAttribute('src', videoUrl);

                            const clickedAulaId = aula.getAttribute('data-aulaId');
                            const novaUrl = `${window.location.pathname}?cursoId=${cursoId}&aulaId=${clickedAulaId}`;
                            window.location.href = novaUrl;
                        });
                    });
                }
            });
            btnMarcarConcluido.disabled = true;
        }
    });

    </script>
</head>

<body>
    <header>
        <div class="container">
            <a href="indexLogado.php"><img src="img/logo.png" alt="Logo" id="logo"></a>
            <nav>
                <ul>
                    <a href="indexLogado.php"><li>HOME</li></a>
                    <a href="screen-cursos.php"><li>CURSOS</li></a>
                    <a href="conta.php"><li>MINHA CONTA</li></a>
                </ul>
            </nav>
            <div class="img-profile">
                <a href="#"><img src="<?php echo htmlspecialchars($perfil); ?>" alt="Foto do usuário" id="profile"></a>
            </div>

            <div class="dropdown">
                <div class="user">
                    <div class="informations">
                        <p><u id='name'><?php echo htmlspecialchars($username); ?></u></p>
                        <p><u id='email'><?php echo htmlspecialchars($email); ?></u></p>
                    </div>

                    <div class="infos">
                        <a href="conta.php"><p id="config"><span>⚙️</span> Configurações</p></a>
                        <a href="logOut.php"><p id="logout"><span>↩</span> Log out</p></a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main>
        <div id="title">
            <h1><?php echo $value; ?></h1>
            <div class="progress-bar-container">
                <div class="progress-bar">65%</div>
            </div>
            <div class="progress-text">65% Concluído</div>
        </div>

        <div class="main-container">
            <div class="sidebar" id="sidebar"></div>
            <div class="content">
                <div class="video-player">
                    <video id="video" width="100%" controls>
                        <source src="<?php echo htmlspecialchars($videoUrl); ?>" type="video/mp4">
                        Seu navegador não suporta a reprodução de vídeo.
                    </video>
                </div>
                <div class="lesson-info">
                    <h3 id="titulo-aula"><span id="modulo-aula"><?php echo htmlspecialchars($nomeModulo); ?></span> | <?php echo htmlspecialchars($primeiraAula); ?></h3>
                    <div class="button-container">
                        <button class="conclude-button previous">Aula anterior <</button>
                        <button id="btnMarcarConcluido" class="conclude-button">Marcar como concluído</button>
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
