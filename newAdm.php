<?php
    include_once('conexao1.php');
    session_start();

    $message = ""; // Variável para armazenar a mensagem
    $alertType = ""; // Variável para armazenar o tipo de alerta (error ou success)
    $adm = $_SESSION['idAdm'];

    if (isset($_POST['update_info'])) {

        $username = mysqli_real_escape_string($conexao, $_POST['username']);
        $password = mysqli_real_escape_string($conexao, $_POST['password']);

        if (strlen($username) < 7) {
            $message = "O nome de usuário deve ter pelo menos 7 caracteres.";
            $alertType = "error";
        } else {
            $query_adm = "SELECT username_adm FROM tb_adm WHERE username_adm = '$username' AND id_adm != $adm";
            $result_queryAdm = mysqli_query($conexao, $query_adm);

            if (mysqli_num_rows($result_queryAdm) > 0) {
                $message = "Já existe um administrador com esse username.";
                $alertType = "error";
            } else {
                $sql = "INSERT INTO tb_adm(username_adm, senha_adm) VALUES('$username', '$password')";
                if (mysqli_query($conexao, $sql)) {
                    $message = 'Novo administrador cadastrado!';
                    $alertType = "success";
                } else {
                    $message = "Erro ao cadastrar: " . mysqli_error($conexao);
                    $alertType = "error";
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
    <link rel="stylesheet" href="./css/adm.css">
    <script src="./js/newAdm.js" defer></script>
    <title>Administrador</title>
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
    <div class="container-main">
        <h1>Administrador</h1>
        <div class="row">
            <nav class="sidebar">
                <ul class="nav-list">
                    <li class="list"><a href="adm.php">Usuários</a></li>
                    <li class="list"><a href="newAdm.php" class="active">Novo administrador</a></li>
                    <a href="logOutAdm.php">
                        <li>Sair</li>
                    </a>
                </ul>
            </nav>
            <main class="content">
                <section class="form-section">
                    <div class="form-container">
                        <h2>Cadastro de Administrador</h2>
                        <form action="#" method="POST" class="admin-form" id="cadastrar">
                            <div class="input-group">
                                <label for="username">Username</label>
                                <div class="input-field">
                                    <span class="input-icon">👤</span>
                                    <input type="text" id="username" name="username" placeholder="@username" required>
                                </div>
                                <div class="msg-error">Já existe alguém com este nome de usuário cadastrado, tente outro.</div>
                            </div>

                            <div class="input-group">
                                <label for="password">Password</label>
                                <div class="input-field">
                                    <span class="input-icon">🔒</span>
                                    <input type="password" id="password" name="password" placeholder="Password" required>
                                    <div class="password-strength">Strong</div>
                                </div>
                                <div class="msg-recommended">* Sua senha precisa conter letras minúsculas e maiúsculas, números e caracteres especiais.</div>
                                <div class="msg-alert">Insira uma senha mais forte</div>
                            </div>

                            <button type="submit" class="submit-btn" name="update_info">Cadastrar</button>
                            <div class="alert">Informações atualizadas com sucesso!</div>
                        </form>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>
