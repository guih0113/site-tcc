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
    $query = "SELECT * FROM tb_cursos WHERE id_curso = $id_curso";
    $result = mysqli_query($conexao, $query);

    if ($curso = mysqli_fetch_assoc($result)) {
        echo json_encode($curso);
    } else {
        echo json_encode(["error" => "Curso não encontrado"]);
    }
    exit; // Para interromper a execução em uma requisição AJAX
}

// Defina o curso selecionado na sessão
if (isset($_POST['course-select']) && !empty($_POST['course-select'])) {
    $_SESSION['id_curso'] = $_POST['course-select'];
}

// Atualização de curso
if (isset($_POST['update-course']) && isset($_SESSION['id_curso'])) {
    $novo_nome = mysqli_real_escape_string($conexao, $_POST['nome']);
    $nova_desc = mysqli_real_escape_string($conexao, $_POST['descricao']);
    $novo_icone = mysqli_real_escape_string($conexao, $_POST['icone']);
    $id_curso = $_SESSION['id_curso'];

    $query_atualiza = "UPDATE tb_cursos SET nome_curso = '$novo_nome', descricao_curso = '$nova_desc', icone_curso = '$novo_icone' WHERE id_curso = $id_curso";
    if (mysqli_query($conexao, $query_atualiza)) {
        $_SESSION['nomeCurso'] = $novo_nome;
        $_SESSION['descCurso'] = $nova_desc;
        $_SESSION['iconeCurso'] = $novo_icone;
        $message = "Informações atualizadas com sucesso!";
        $alertType = "success";
    } else {
        $message = "Erro ao atualizar informações: " . mysqli_error($conexao);
        $alertType = "error";
    }
}

// Exclusão de curso
if (isset($_POST['delete_course']) && isset($_SESSION['id_curso'])) {
    $id_curso = $_SESSION['id_curso'];

    // Exclui registros relacionados na tabela `tb_cursos_modulos`
    $deleteRelations = "DELETE FROM tb_cursos_modulos WHERE cd_curso = $id_curso";
    mysqli_query($conexao, $deleteRelations);

    // Exclui o curso
    $delete = "DELETE FROM tb_cursos WHERE id_curso = $id_curso";
    if (mysqli_query($conexao, $delete)) {
        $message = "Curso excluído com sucesso!";
        $alertType = "success";
        unset($_SESSION['id_curso']); // Limpa o ID do curso da sessão após exclusão
    } else {
        $message = "Erro ao excluir curso: " . mysqli_error($conexao);
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
    <script src="./js/editarCurso.js" defer></script>
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
            if (message) {
                alertDiv.innerHTML = message;
                alertDiv.classList.add(alertType);
                alertDiv.style.display = "block";
            }
            setTimeout(function() {
                alertDiv.style.display = "none";
            }, 5000); // Ocultar após 5 segundos
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
                    <li class="list"><a href="editarCurso.php" class="active">Editar curso</a></li>
                    <li class="list"><a href="editarConteudo.php">Editar módulos e aulas</a></li>
                    <a href="logOutAdm.php">
                        <li>Sair</li>
                    </a>
                </ul>
            </nav>
            <main class="content">
                <section id="account-change-password" class="tab-content">
                    <form action="editarCurso.php" method="POST" class="form">
                        <h1>Editar Curso</h1>

                        <div class="form-group">
                            <label for="course-select">Selecione o Curso</label>
                            <select id="course-select" name="course-select" class="form-control">
                                <option value="">Selecione um curso</option>
                                <?php
                                $query = "SELECT id_curso, nome_curso FROM tb_cursos";
                                $result = mysqli_query($conexao, $query);

                                while ($curso = mysqli_fetch_assoc($result)) {
                                    echo "<option value='{$curso['id_curso']}'>{$curso['nome_curso']}</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="course-name">Nome do Curso</label>
                            <input type="text" name="nome" id="course-name" class="form-control" value="" required>
                        </div>

                        <div class="form-group">
                            <label for="course-description">Descrição</label>
                            <textarea name="descricao" id="course-description" class="form-control" rows="4" required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="course-category">Ícone</label>
                            <input type="text" name="icone" id="course-category" class="form-control" value="">
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary" name="update-course">Salvar Alterações</button>
                            <button type="button" class="btn btn-secondary">Cancelar</button>
                            <button type="submit" class="btn btn-danger" name="delete_course">Excluir Curso</button>
                        </div>
                        <div class="alert"></div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
