<?php
include_once('conexao1.php');
session_start();

if ((!isset($_SESSION['userAdm']) == true) and (!isset($_SESSION['senhaAdm']) == true)) {
    unset($_SESSION['userAdm']);
    unset($_SESSION['senhaAdm']);
    header('Location: loginAdm.php');
}

$message = ""; // Mensagem para feedback
$alertType = ""; // Tipo do alerta (success ou error)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Captura os dados enviados pelo formulário
    $cursoId = $_POST['course-select'];
    $moduloNome = $_POST['nome'];

    if (!empty($cursoId) && !empty($moduloNome)) {
        // Insere o módulo na tabela tb_modulos
        $stmtModulo = $conexao->prepare("INSERT INTO tb_modulos (nome_modulo) VALUES (?)");
        $stmtModulo->bind_param("s", $moduloNome);

        if ($stmtModulo->execute()) {
            // Obtém o ID do módulo recém-criado
            $moduloId = $stmtModulo->insert_id;

            // Relaciona o módulo ao curso na tabela tb_cursos_modulos
            $stmtRelacionamento = $conexao->prepare("INSERT INTO tb_cursos_modulos (cd_curso, cd_modulo) VALUES (?, ?)");
            $stmtRelacionamento->bind_param("ii", $cursoId, $moduloId);

            if ($stmtRelacionamento->execute()) {
                $message = "Módulo adicionado com sucesso!";
                $alertType = "success";
            } else {
                $message = "Erro ao relacionar o módulo ao curso.";
                $alertType = "error";
            }
        } else {
            $message = "Erro ao adicionar o módulo.";
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
        }

        div.alert.error {
            background-color: rgba(255, 0, 0, 0.834);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const message = "<?php echo $message; ?>";
            const alertType = "<?php echo $alertType; ?>";
            const alertDiv = document.querySelector('.alert');

            if (message && alertDiv) {
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
                    <li class="list"><a href="adicionarConteudo.php" class="active">Adicionar módulos</a></li>
                    <li class="list"><a href="adicionarAulas.php">Adicionar aulas</a></li>
                    <li class="list"><a href="novoCurso.php">Novo Curso</a></li>
                    <a href="logOutAdm.php">
                        <li>Sair</li>
                    </a>
                </ul>
            </nav>
            <main class="content">
                <section id="account-change-password" class="tab-content">
                    <form action="adicionarConteudo.php" method="POST" class="form">
                        <h1>Adicionar Módulos</h1>

                        <div class="form-group">
                            <label for="course-select">Selecione o Curso</label>
                            <select id="course-select" name="course-select" class="form-control" required>
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
                            <label for="course-name">Nome do Módulo</label>
                            <input type="text" name="nome" id="course-name" class="form-control" required>
                        </div>

                        <div class="action-buttons">
                            <button type="submit" class="btn btn-primary">Salvar</button>
                            <button type="reset" class="btn btn-secondary">Cancelar</button>
                        </div>
                        <div class="alert"></div>
                    </form>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
