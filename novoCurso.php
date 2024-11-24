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

// Função para cadastrar um novo curso
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update-course'])) {
    // Captura os dados do formulário
    $nomeCurso = $_POST['nome'];
    $descricaoCurso = $_POST['descricao'];
    $iconeCurso = $_POST['icone'];

    // Verifica se todos os campos foram preenchidos
    if (!empty($nomeCurso) && !empty($descricaoCurso) && !empty($iconeCurso)) {
        // Prepara a query para inserir o curso no banco de dados
        $stmtCurso = $conexao->prepare("INSERT INTO tb_cursos (nome_curso, descricao_curso, icone_curso) VALUES (?, ?, ?)");
        $stmtCurso->bind_param("sss", $nomeCurso, $descricaoCurso, $iconeCurso);

        if ($stmtCurso->execute()) {
            $message = "Curso cadastrado com sucesso!";
            $alertType = "success";
        } else {
            $message = "Erro ao cadastrar o curso.";
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
                    <li class="list"><a href="editarCurso.php">Editar curso</a></li>
                    <li class="list"><a href="editarConteudo.php">Editar módulos e aulas</a></li>
                    <li class="list"><a href="adicionarConteudo.php">Adicionar módulos</a></li>
                    <li class="list"><a href="adicionarAulas.php">Adicionar aulas</a></li>
                    <li class="list"><a href="novoCurso.php" class="active">Novo Curso</a></li>
                    <a href="logOutAdm.php">
                        <li>Sair</li>
                    </a>
                </ul>
            </nav>
            <main class="content">
                <section id="account-change-password" class="tab-content">
                    <form action="novoCurso.php" method="POST" class="form">
                        <h1>Novo Curso</h1>

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
                        </div>
                        <div class="alert"></div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
