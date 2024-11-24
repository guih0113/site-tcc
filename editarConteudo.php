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
        $query = "SELECT m.id_modulo, m.nome_modulo 
                FROM tb_modulos m
                JOIN tb_cursos_modulos cm ON cm.cd_modulo = m.id_modulo
                WHERE cm.cd_curso = $id_curso";
        $result = mysqli_query($conexao, $query);

        $modulos = [];
        while ($modulo = mysqli_fetch_assoc($result)) {
            $modulos[] = $modulo;
        }

        if (empty($modulos)) {
            echo json_encode(["error" => "Nenhum módulo encontrado"]);
        } else {
            echo json_encode($modulos);
        }
        exit; // Para interromper o resto da execução da página em uma requisição AJAX
    }

    // Função para carregar nome do módulo ao selecionar um módulo
    if (isset($_GET['id_modulo'])) {
        $id_modulo = $_GET['id_modulo'];
        $query = "SELECT nome_modulo FROM tb_modulos WHERE id_modulo = $id_modulo";
        $result = mysqli_query($conexao, $query);

        if ($modulo = mysqli_fetch_assoc($result)) {
            echo json_encode($modulo);
        } else {
            echo json_encode(["error" => "Módulo não encontrado"]);
        }
        exit; // Para interromper o resto da execução da página em uma requisição AJAX
    }

    if (isset($_GET['id_aula'])) {
        $id_aula = $_GET['id_aula'];
        $query = "SELECT nome_aula, conteudo_aula FROM tb_aulas WHERE id_aula = $id_aula";
        $result = mysqli_query($conexao, $query);

        if ($aula = mysqli_fetch_assoc($result)) {
            echo json_encode($aula); // Inclui 'nome_aula' e 'conteudo_aula'
        } else {
            echo json_encode(["error" => "Aula não encontrada"]);
        }
        exit;
    }

    // Função para carregar aulas do módulo selecionado
    if (isset($_GET['id_modulo_aulas'])) {
        $id_modulo = $_GET['id_modulo_aulas'];
        $query = "SELECT a.id_aula, a.nome_aula 
                FROM tb_aulas a
                JOIN tb_modulos_aulas ma ON ma.cd_aula = a.id_aula
                WHERE ma.cd_modulo = $id_modulo";
        $result = mysqli_query($conexao, $query);

        $aulas = [];
        while ($aula = mysqli_fetch_assoc($result)) {
            $aulas[] = $aula;
        }

        if (empty($aulas)) {
            echo json_encode(["error" => "Nenhuma aula encontrada"]);
        } else {
            echo json_encode($aulas);
        }
        exit; // Para interromper o resto da execução da página em uma requisição AJAX
    }

    if (isset($_POST['update-course'])) {
        // Coletar valores do formulário
        $novo_nomeModulo = isset($_POST['nome_modulo']) ? mysqli_real_escape_string($conexao, $_POST['nome_modulo']) : '';
        $novo_nomeAula = isset($_POST['nome_aula']) ? mysqli_real_escape_string($conexao, $_POST['nome_aula']) : '';
        $novo_conteudo = isset($_POST['conteudo']) ? mysqli_real_escape_string($conexao, $_POST['conteudo']) : '';
        
        // Obter IDs de módulo e aula do formulário diretamente
        $id_modulo = isset($_POST['module-select']) ? (int) $_POST['module-select'] : null;
        $id_aula = isset($_POST['class-select']) ? (int) $_POST['class-select'] : null;
    
        // Validar se os valores são válidos antes de atualizar
        if ($novo_nomeModulo && $novo_nomeAula && $novo_conteudo && $id_modulo && $id_aula) {
            $atualiza_modulo = "UPDATE tb_modulos SET nome_modulo = '$novo_nomeModulo' WHERE id_modulo = $id_modulo";
            $atualiza_aula = "UPDATE tb_aulas SET nome_aula = '$novo_nomeAula', conteudo_aula = '$novo_conteudo' WHERE id_aula = $id_aula";
    
            if (mysqli_query($conexao, $atualiza_modulo) && mysqli_query($conexao, $atualiza_aula)) {
                $message = "Informações atualizadas com sucesso!";
                $alertType = "success";
            } else {
                $message = "Erro ao atualizar informações: " . mysqli_error($conexao);
                $alertType = "error";
            }
        } else {
            $message = "Campos não preenchidos corretamente.";
            $alertType = "error";
        }
    }
    
    // Função de exclusão de módulo
    if (isset($_POST['delete-module'])) {
        $id_modulo = (int)$_POST['id_modulo']; // Obtém o ID do módulo diretamente do POST
    
        // Exclui os registros relacionados na tabela `tb_modulos_aulas` (relação entre módulos e aulas)
        $deleteModuleLessons = "DELETE FROM tb_modulos_aulas WHERE cd_modulo = $id_modulo";
        mysqli_query($conexao, $deleteModuleLessons);
    
        // Exclui os registros relacionados na tabela `tb_cursos_modulos` (relação entre cursos e módulos)
        $deleteCourseModules = "DELETE FROM tb_cursos_modulos WHERE cd_modulo = $id_modulo";
        mysqli_query($conexao, $deleteCourseModules);
    
        // Agora tenta excluir o módulo da tabela `tb_modulos`
        $deleteModule = "DELETE FROM tb_modulos WHERE id_modulo = $id_modulo";
        if (mysqli_query($conexao, $deleteModule)) {
            $message = "Módulo excluído com sucesso!";
            $alertType = "success";
        } else {
            $message = "Erro ao excluir módulo: " . mysqli_error($conexao);
            $alertType = "error";
        }
    }
    
    // Função de exclusão de aula
    if (isset($_POST['delete-class'])) {
        $id_aula = (int)$_POST['id_aula']; // Obtém o ID da aula diretamente do POST
    
        // Exclui os registros relacionados na tabela `tb_modulos_aulas` (relação entre módulos e aulas)
        $deleteModuleLessons = "DELETE FROM tb_modulos_aulas WHERE cd_aula = $id_aula";
        mysqli_query($conexao, $deleteModuleLessons);
    
        // Exclui os registros relacionados na tabela `tb_usuario_aulas` (conclusão da aula por usuário)
        $deleteUserLessons = "DELETE FROM tb_usuario_aulas WHERE cd_aula = $id_aula";
        mysqli_query($conexao, $deleteUserLessons);
    
        // Agora tenta excluir a aula da tabela `tb_aulas`
        $deleteClass = "DELETE FROM tb_aulas WHERE id_aula = $id_aula";
        if (mysqli_query($conexao, $deleteClass)) {
            $message = "Aula excluída com sucesso!";
            $alertType = "success";
        } else {
            $message = "Erro ao excluir aula: " . mysqli_error($conexao);
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
        <script src="./js/editarConteudo.js" defer></script>
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
                        <li class="list"><a href="editarConteudo.php" class="active">Editar módulos e aulas</a></li>
                        <li class="list"><a href="adicionarConteudo.php">Adicionar módulos</a></li>
                        <li class="list"><a href="adicionarAulas.php">Adicionar aulas</a></li>
                        <li class="list"><a href="novoCurso.php">Novo Curso</a></li>
                        <a href="logOutAdm.php">
                            <li>Sair</li>
                        </a>
                    </ul>
                </nav>
                <main class="content">
                    <section id="account-change-password" class="tab-content">
                        <form action="editarConteudo.php" method="POST" class="form">
                            <h1>Editar Módulos e Aulas</h1>

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
                                <label for="course-name">Nome do Módulo</label>
                                <input type="text" name="nome_modulo" id="course-name" class="form-control" value="" required>
                            </div>

                            <div class="form-group">
                                <label for="class-select">Selecione a Aula</label>
                                <input type="hidden" name="id_aula" id="hidden-class-id">
                                <select id="class-select" name="class-select" class="form-control">
                                    <option value="">Selecione uma aula</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="class-name">Nome da Aula</label>
                                <input type="text" name="nome_aula" id="class-name" class="form-control" value="" required>
                            </div>

                            <div class="form-group">
                                <label for="class-content">Conteúdo da Aula (URL)</label>
                                <input type="text" name="conteudo" id="class-content" class="form-control" value="" required>
                            </div>

                            <!-- Botões de ação -->
                            <div class="action-buttons">
                                <button type="submit" class="btn btn-primary" name="update-course">Salvar Alterações</button>
                                <button type="button" class="btn btn-secondary">Cancelar</button>
                                <button type="submit" class="btn btn-danger" name="delete-module">Excluir Módulo</button>
                                <button type="submit" class="btn btn-danger" id="danger1" name="delete-class">Excluir Aula</button>
                            </div>
                            <div class="alert">Informações atualizadas com sucesso!</div>
                        </form>
                    </section>
                </main>
            </div>
        </div>
    </body>

    </html>