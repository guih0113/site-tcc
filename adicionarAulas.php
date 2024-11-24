<?php
include_once('conexao1.php');
session_start();

if ((!isset($_SESSION['userAdm']) == true) and (!isset($_SESSION['senhaAdm']) == true)) {
    unset($_SESSION['userAdm']);
    unset($_SESSION['senhaAdm']);
    header('Location: loginAdm.php');
}

$message = ""; // Variável para armazenar a mensagem
$alertType = ""; // Variável para armazenar o tipo de alerta (error ou success)

// Função de API interna para carregar dados do curso quando solicitado por AJAX
if (isset($_GET['id_curso'])) {
    $id_curso = $_GET['id_curso'];
    $stmt = $conexao->prepare("SELECT * FROM tb_cursos WHERE id_curso = ?");
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($curso = $result->fetch_assoc()) {
        echo json_encode($curso);
    } else {
        echo json_encode(["error" => "Curso não encontrado"]);
    }
    exit;
}

// Função para carregar módulos do curso selecionado
if (isset($_GET['id_curso_modulos'])) {
    $id_curso = $_GET['id_curso_modulos'];

    $stmt = $conexao->prepare("
            SELECT m.id_modulo, m.nome_modulo
            FROM tb_modulos m
            JOIN tb_cursos_modulos cm ON cm.cd_modulo = m.id_modulo
            WHERE cm.cd_curso = ?
        ");
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $result = $stmt->get_result();

    $modulos = [];
    while ($modulo = $result->fetch_assoc()) {
        $modulos[] = $modulo;
    }

    echo json_encode($modulos);
    exit; // Para interromper a execução da página após enviar a resposta
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados enviados pelo formulário
    $moduloId = $_POST['module-select'];
    $nomeAula = $_POST['nome_modulo'];
    $conteudoAula = $_POST['conteudo'];

    if (!empty($moduloId) && !empty($nomeAula) && !empty($conteudoAula)) {
        // Insere a aula na tabela tb_aulas
        $stmtAula = $conexao->prepare("INSERT INTO tb_aulas (nome_aula, conteudo_aula) VALUES (?, ?)");
        $stmtAula->bind_param("ss", $nomeAula, $conteudoAula);

        if ($stmtAula->execute()) {
            // Obtém o ID da aula recém-criada
            $aulaId = $stmtAula->insert_id;

            // Relaciona a aula ao módulo na tabela tb_modulos_aulas
            $stmtRelacionamento = $conexao->prepare("INSERT INTO tb_modulos_aulas (cd_modulo, cd_aula) VALUES (?, ?)");
            $stmtRelacionamento->bind_param("ii", $moduloId, $aulaId);

            if ($stmtRelacionamento->execute()) {
                $message = "Aula adicionada com sucesso!";
                $alertType = "success";
            } else {
                $message = "Erro ao relacionar a aula ao módulo.";
                $alertType = "error";
            }
        } else {
            $message = "Erro ao adicionar a aula.";
            $alertType = "error";
        }
    } else {
        $message = "Por favor, preencha todos os campos.";
        $alertType = "error";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/editarCurso.css">
    <script src="./js/adicionarConteudo.js" defer></script>
    <title>Estude para o Futuro</title>
    <style>
        div.alert {
            display: none;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            color: black;
            border-radius: 5px;
        }

        div.alert.success {
            background-color: #40dc35;
            /* Verde para sucesso */
        }

        div.alert.error {
            background-color: rgba(255, 0, 0, 0.834);
            /* Vermelho para erro */
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = "<?php echo $message; ?>";
            const alertType = "<?php echo $alertType; ?>";
            const alertDiv = document.querySelector('.alert');

            if (message && alertDiv) { // Confere se o alertDiv foi identificado corretamente
                alertDiv.innerHTML = message;
                alertDiv.classList.add(alertType);
                alertDiv.style.display = "block";
            }

            setTimeout(function() {
                alertDiv.style.display = "none";
            }, 5000);
        });
    </script>
</head>

<body>
    <div class="container-main">
        <h1>Administrador</h1>
        <div class="row">
            <nav class="sidebar">
                <ul class="nav-list">
                    <li class="list"><a href="adm.php">Usuários cadastrados</a></li>
                    <li class="list"><a href="newAdm.php">Novo administrador</a></li>
                    <li class="list"><a href="editarCurso.php">Editar curso</a></li>
                    <li class="list"><a href="editarConteudo.php">Editar módulos e aulas</a></li>
                    <li class="list"><a href="adicionarConteudo.php">Adicionar módulos</a></li>
                    <li class="list"><a href="adicionarAulas.php" class="active">Adicionar aulas</a></li>
                    <li class="list"><a href="novoCurso.php">Novo Curso</a></li>
                    <a href="logOutAdm.php">
                        <li>Sair</li>
                    </a>
                </ul>
            </nav>
            <main class="content">
                <section id="account-change-password" class="tab-content">
                    <form action="adicionarAulas.php" method="POST" class="form">
                        <h1>Adicionar Aulas</h1>

                        <div class="form-group">
                            <label for="course-select">Selecione o Curso</label>
                            <select id="course-select" name="course-select" class="form-control">
                                <option value="">Selecione um curso</option>
                                <?php
                                // Obter lista de cursos para o dropdown
                                $query = "SELECT id_curso, nome_curso FROM tb_cursos";
                                $result = mysqli_query($conexao, $query);

                                while ($curso = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$curso['id_curso']}'>{$curso['nome_curso']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="module-select">Selecione o Módulo</label>
                            <input type="hidden" name="id_modulo" id="hidden-module-id">
                            <select id="module-select" name="module-select" class="form-control">
                                <option value="">Selecione um módulo</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course-name">Nome da aula</label>
                            <input type="text" name="nome_modulo" id="class-name" class="form-control" value="" required>
                        </div>

                        <div class="form-group">
                            <label for="class-content">Conteúdo da Aula (URL)</label>
                            <input type="text" name="conteudo" id="class-content" class="form-control" value="" required>
                        </div>

                        <!-- Botões de ação -->
                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary" name="update-course">Salvar Alterações</button>
                            <button type="button" class="btn btn-secondary">Cancelar</button>
                        </div>
                        <div class="alert">Informações atualizadas com sucesso!</div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>

</html>