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

$message = ""; // Variável para armazenar a mensagem
$alertType = ""; // Variável para armazenar o tipo de alerta (error ou success)

// Verifica se uma nova imagem foi enviada
if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"]["name"])) {
    $imagem = "./img/" . basename($_FILES["imagem"]["name"]);
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagem)) {
        // Salva o caminho da imagem no banco de dados
        $query = "INSERT INTO tb_foto (nome_foto, cd_usuario) VALUES ('$imagem', '$user') ON DUPLICATE KEY UPDATE nome_foto = '$imagem'";
        mysqli_query($conexao, $query);
        $message = "Imagem atualizada com sucesso!";
        $alertType = "success";
    } else {
        $message = "Erro ao fazer upload da imagem.";
        $alertType = "error";
    }
}

// Remover foto de perfil
if (isset($_POST['remover_foto'])) {
    $sql_remover = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = $user";
    $res_remover = mysqli_query($conexao, $sql_remover);
    $row_remover = mysqli_fetch_assoc($res_remover);

    if ($row_remover && file_exists($row_remover['nome_foto'])) {
        unlink($row_remover['nome_foto']); // Remove a foto do diretório
    }

    $query_remover = "DELETE FROM tb_foto WHERE cd_usuario = $user";
    mysqli_query($conexao, $query_remover);
    $message = "Foto de perfil removida com sucesso!";
    $alertType = "success";
}

// Busca a imagem de perfil do usuário no banco de dados
$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = $user";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$perfil = isset($row['nome_foto']) ? $row['nome_foto'] : "img/profile.svg";

if (isset($_POST['update_info'])) {
    // Captura os dados do formulário
    $novo_email = mysqli_real_escape_string($conexao, $_POST['email']);
    $novo_username = mysqli_real_escape_string($conexao, $_POST['username']);
    
    // Verifica se o nome de usuário é menor que 7 caracteres
    if (strlen($novo_username) < 7) {
        $message = "O nome de usuário deve ter pelo menos 7 caracteres.";
        $alertType = "error";
    } else {
        // Verifica se o email é válido
        if (!filter_var($novo_email, FILTER_VALIDATE_EMAIL)) {
            $message = "O e-mail informado é inválido.";
            $alertType = "error";
        } else {
            // Verifica se o email já existe no banco para outro usuário
            $query_verifica_email = "SELECT id_usuario FROM tb_usuario WHERE email_usuario = '$novo_email' AND id_usuario != $user";
            $result_verifica_email = mysqli_query($conexao, $query_verifica_email);

            // Verifica se o nome de usuário já existe no banco para outro usuário
            $query_verifica_username = "SELECT id_usuario FROM tb_usuario WHERE username_usuario = '$novo_username' AND id_usuario != $user";
            $result_verifica_username = mysqli_query($conexao, $query_verifica_username);

            if (!$result_verifica_email || !$result_verifica_username) {
                die("Erro na consulta SQL: " . mysqli_error($conexao));
            }

            if (mysqli_num_rows($result_verifica_email) > 0) {
                $message = "O e-mail informado já está em uso.";
                $alertType = "error";
            } elseif (mysqli_num_rows($result_verifica_username) > 0) {
                $message = "O nome de usuário informado já está em uso.";
                $alertType = "error";
            } else {
                // Atualiza o email e o nome de usuário no banco de dados
                $query_atualiza = "UPDATE tb_usuario SET email_usuario = '$novo_email', username_usuario = '$novo_username' WHERE id_usuario = $user";
                if (mysqli_query($conexao, $query_atualiza)) {
                    // Atualiza as variáveis de sessão
                    $_SESSION['email'] = $novo_email;
                    $_SESSION['username'] = $novo_username;
                    $message = "Informações atualizadas com sucesso!";
                    $alertType = "success";
                } else {
                    $message = "Erro ao atualizar informações: " . mysqli_error($conexao);
                    $alertType = "error";
                }
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha conta</title>
    <link rel="stylesheet" href="./css/conta.css">
    <script src="./js/conta.js" defer></script>
    <style>
        div.alert {
            display: none;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
            color: white;
            border-radius: 5px;
        }
        div.alert.success {
            background-color: #40dc35; /* Verde para sucesso */
        }
        div.alert.error {
            background-color: rgba(255, 0, 0, 0.834); /* Vermelho para erro */
        }
    </style>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var message = "<?php echo $message; ?>";
            var alertType = "<?php echo $alertType; ?>";
            if (message) {
                var alertDiv = document.querySelector(".alert");
                alertDiv.innerHTML = message;
                alertDiv.classList.add(alertType); // Adiciona a classe correspondente ao tipo
                alertDiv.style.display = "block";
            }
        });
    </script>
</head>
<body>
    <header>
        <div class="container">
            <img src="img/Logo.png" alt="Logo" id="logo">
            <!-- Logo header -->
            <nav>
                <ul>
                    <a href="screen-cursos.php">
                        <li>CURSOS</li>
                    </a>
                    <a href="conta.php">
                        <li id="li-conta">MINHA CONTA</li>
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

    <div class="container-main">
        <h1>Configurações da Conta</h1>
        <div class="row">
            <nav class="sidebar">
                <ul class="nav-list">
                    <li><a href="conta.php" class="active">Geral</a></li>
                    <li><a href="senha.php">Mudar Senha</a></li>
                </ul>
            </nav>
            <main class="content">
                <!-- Seção Geral -->
                <section id="account-general" class="tab-content">
                    <form action="conta.php" method="post" enctype="multipart/form-data">
                        <div class="profile-header">
                            <img src="<?php echo $perfil; ?>" alt="Avatar" class="avatar">
                                <div class="photo-actions">
                                    <div class="btn-upload">
                                        <!-- O input type file é escondido -->
                                        <input type="file" id="file-input" style="display: none;" name="imagem" accept="image/*" class="file-input">
                                        <!-- O botão personalizado -->
                                        <label for="file-input" class="btn-choose-file">
                                            Escolher arquivo
                                        </label>
                                    </div>
                                    <button type="submit" name="remover_foto" class="btn">Remover foto</button>
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" value="<?php echo $email; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="username">Nome de Usuário</label>
                            <input type="text" id="username" name="username" class="form-control" value="<?php echo $username; ?>" required>
                        </div>
                        <div class="action-buttons">
                            <input type="submit" name="update_info" class="btn btn-primary" value="Salvar alterações">
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