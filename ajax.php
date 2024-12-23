<?php
include('conexao1.php');
session_start();

function exibirCursos($contexto) {
    global $conexao, $user; // Usando variáveis globais necessárias

    $lista = getCursos();
    $retorno = '';
    $contador = 0;

    while ($curso = $lista->fetch_array(MYSQLI_ASSOC)) {
        if ($contexto == 'index' && $contador >= 3) {
            break; // Para o loop após exibir 3 cursos
        }

        $primeiraAulaId = getPrimeiraAulaId($curso['id_curso']);

        $sqlConclusao = "SELECT conclusao_curso FROM tb_cursos WHERE id_curso = ?";
        $stmtConclusao = $conexao->prepare($sqlConclusao);
        $stmtConclusao->bind_param("i", $curso['id_curso']);
        $stmtConclusao->execute();
        $resultadoConclusao = $stmtConclusao->get_result();
        $conclusao = $resultadoConclusao->fetch_assoc()['conclusao_curso'];

        if ($conclusao === '100.00') {
            $botaoTexto = "FINALIZADO";
        } else {
            $aulaConcluida = ($conclusao > 0 && $conclusao < 100) || temAulaConcluida($conexao, $curso['id_curso'], $user);
            $botaoTexto = $aulaConcluida ? 'CONTINUAR' : 'INICIAR';
        }

        if ($contexto == 'index') {
            $retorno .= '<div class="curso-card" data-index="'.htmlspecialchars($curso['id_curso']).'">
                            <div class="curso-nivel">
                                <span>' . $curso['icone_curso'] . '</span>
                            </div>
                            <h3>' . htmlspecialchars($curso['nome_curso']) . '</h3>
                            <p>' . htmlspecialchars($curso['descricao_curso']) . '</p>
                            <a href="curso.php?cursoId=' . htmlspecialchars($curso['id_curso']) . '&aulaId=' . htmlspecialchars($primeiraAulaId) . '">
                                <button class="iniciar-button">' . htmlspecialchars($botaoTexto) . '</button>
                            </a>
                        </div>';
        } elseif ($contexto == 'screenCursos') {
            $retorno .= '<div class="course-card">
                            <div class="course-header">
                                <span class="course-icon">' . $curso['icone_curso'] . '</span>
                                <h3>' . htmlspecialchars($curso['nome_curso']) . '</h3>
                                <p>' . htmlspecialchars($curso['descricao_curso']) . '</p>
                            </div>
                            <div class="card-button">
                                <a href="curso.php?cursoId=' . htmlspecialchars($curso['id_curso']) . '&aulaId=' . htmlspecialchars($primeiraAulaId) . '">
                                    <button class="free-label">' . htmlspecialchars($botaoTexto) . '</button>
                                </a>
                            </div>
                        </div>';
        }

        $contador++; // Incrementa o contador
    }

    echo $retorno; // Exibe os resultados
}

if (isset($_GET['carrossel'])) {
    exibirCursos('index');
    exit;
}

if (isset($_GET['cursos'])) {
    exibirCursos('screenCursos');
    exit;
}

// Exibir usuários
if (isset($_GET['users'])) {
    $lista = getUsers();
    $retorno = '';

    while ($user = $lista->fetch_array()) {
        $retorno .= '<tr>
                        <td>' . htmlspecialchars($user['id_usuario']) . '</td>
                        <td>' . htmlspecialchars($user['email_usuario']) . '</td>
                        <td>' . htmlspecialchars($user['username_usuario']) . '</td>
                        <td>' . $user['senha_usuario'] . '</td>
                        <td><button 
                                class="delete-btn" 
                                id="' . htmlspecialchars($user['id_usuario']) . '" 
                                data-username="' . htmlspecialchars($user['username_usuario']) . '" 
                                onclick="del(this)">
                                Excluir
                            </button>
                        </td>
                    </tr>';
    }

    echo $retorno; // Exibe os resultados
    exit;
}

// Excluir usuário
if (isset($_GET['delUser'])) {
    excluirUser($_GET['delUser']);
    exit;
}

// Exibir módulos e aulas na sidebar
if (isset($_GET['sidebar']) && isset($_GET['cursoId'])) {
    $cursoId = filter_input(INPUT_GET, 'cursoId', FILTER_SANITIZE_NUMBER_INT);
    $userId = $_SESSION['id']; // Assumindo que o ID do usuário está na sessão

    if ($cursoId) {
        $lista = getModules($cursoId);
        $retorno = '';

        if ($lista && $lista instanceof mysqli_result && $lista->num_rows > 0) {
            while ($module = $lista->fetch_assoc()) { 
                $moduloId = $module['id_modulo'];

                // Adicionar o HTML para o módulo
                $retorno .= '<div class="module" data-modulo="' . $moduloId . '">
                                    <h2 class="dropdown-toggle" id="' . $moduloId . '">' . htmlspecialchars($module['nome_modulo']) . '</h2>
                                    <ul class="dropdown-content">';

                // Buscar as aulas associadas ao módulo atual
                $aulas = getAulas($moduloId);
                if ($aulas && $aulas instanceof mysqli_result && $aulas->num_rows > 0) {
                    while ($aula = $aulas->fetch_assoc()) {
                        $aulaId = $aula['id_aula'];

                        // Verifica se a aula foi concluída pelo usuário
                        $concluidaQuery = "SELECT concluida FROM tb_usuario_aulas WHERE cd_usuario = $userId AND cd_aula = $aulaId";
                        $concluidaResult = mysqli_query($conexao, $concluidaQuery);
                        $concluida = $concluidaResult ? mysqli_fetch_assoc($concluidaResult) : null;

                        // Adiciona uma classe CSS 'completed' se a aula foi concluída
                        $completedClass = ($concluida && $concluida['concluida'] == 1) ? 'completed' : '';

                        // Adicionar as aulas ao HTML com o link correto contendo cursoId e aulaId
                        $retorno .= '<li class="' . $completedClass . '">
                                            <a href="curso.php?cursoId=' . $cursoId . '&aulaId=' . $aulaId . '" class="aula-link" data-aulaId="' . $aulaId . '" data-video="' . htmlspecialchars($aula['conteudo_aula']) . '" data-titulo="' . htmlspecialchars($aula['nome_aula']) . '">'
                            . htmlspecialchars($aula['nome_aula']) .
                            ($completedClass ? ' <i class="fas fa-check" style="color: green;"></i>' : '') .
                            '</a>
                                        </li>';
                    }
                } else {
                    $retorno .= '<li>Nenhuma aula disponível.</li>';
                }

                $retorno .= '</ul></div>';
            }
        } else {
            $retorno = '<p>Nenhum módulo encontrado.</p>';
        }

        echo $retorno;
    } else {
        echo '<p>Curso não encontrado.</p>';
    }
    exit;
}


// Buscar aulas de um módulo específico
if (isset($_GET['getAulas']) && isset($_GET['moduloId'])) {
    $moduloId = filter_input(INPUT_GET, 'moduloId', FILTER_SANITIZE_NUMBER_INT);
    $aulas = getAulas($moduloId);

    $result = [];
    while ($aula = $aulas->fetch_assoc()) {
        $result[] = [
            'nome_aula' => $aula['nome_aula'],
            'conteudo_aula' => $aula['conteudo_aula']
        ];
    }

    echo json_encode($result);
    exit;
}

// Marcar aula como concluída
if (isset($_POST['action']) && $_POST['action'] == 'marcar_concluido') {
    $aulaId = filter_input(INPUT_POST, 'aulaId', FILTER_SANITIZE_NUMBER_INT);
    $user = $_SESSION['id']; // Assumindo que o ID do usuário está na sessão

    if ($aulaId && $user) {
        // Verifica se a aula já está marcada como concluída
        $sqlVerificar = "SELECT concluida FROM tb_usuario_aulas WHERE cd_usuario = ? AND cd_aula = ?";
        $stmtVerificar = $conexao->prepare($sqlVerificar);
        $stmtVerificar->bind_param("ii", $user, $aulaId);
        $stmtVerificar->execute();
        $resultadoVerificar = $stmtVerificar->get_result();

        if ($resultadoVerificar->num_rows > 0) {
            // Se a aula já estiver concluída, apenas retorna sucesso
            echo json_encode(['success' => true]);
        } else {
            // Caso contrário, insere a conclusão da aula
            $sqlInserir = "INSERT INTO tb_usuario_aulas (cd_usuario, cd_aula, concluida) VALUES (?, ?, 1)";
            $stmtInserir = $conexao->prepare($sqlInserir);
            $stmtInserir->bind_param("ii", $user, $aulaId);

            if ($stmtInserir->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Erro ao inserir no banco de dados']);
            }
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Parâmetros inválidos']);
    }
    exit;
}
