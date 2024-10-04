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

if(isset($_POST['update-password'])){
    $senha_atual = $_POST['this-password'];
    $nova_senha = mysqli_real_escape_string($conexao, $_POST['new-password']);
    $confirmar_senha = $_POST['confirm-password'];

    $verifica_senha_atual = "SELECT senha_usuario FROM tb_usuario WHERE senha_usuario = '$senha_atual' AND id_usuario = $user";
    $result_verifica_atual = mysqli_query($conexao, $verifica_senha_atual);

    if(mysqli_num_rows($result_verifica_atual) > 0){

        if($nova_senha == $confirmar_senha){
            $atualizar_senha = "UPDATE tb_usuario SET senha_usuario = '$nova_senha' WHERE id_usuario = $user";
            if(mysqli_query($conexao, $atualizar_senha)){
                $_SESSION['senha'] = $nova_senha;
                $message = "Senha atualizada com sucesso!";
                $alertType = "success";
            }

        } else{
            $message = "Confirmação incorreta, tente novamente.";
            $alertType = "error";
        }

    } else{
        $message = "A senha atual está incorreta, tente novamente.";
        $alertType = "error";
    }
}

$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = $user";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$perfil = isset($row['nome_foto']) ? $row['nome_foto'] : "img/profile.svg";
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/senha.css">
    <script src="./js/senha.js" defer></script>
    <title>Estude para o Futuro</title>
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
                    <a href="indexLogado.php">
                        <li id="li-home">HOME</li>
                    </a>
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
                    <li><a href="conta.php">Geral</a></li>
                    <li><a href="senha.php" class="active">Mudar Senha</a></li>
                </ul>
            </nav>
            <main class="content">
                <!-- Seção Mudar Senha -->
                <section id="account-change-password" class="tab-content">
                    <form method="post" action="#account-change-password" id="newPassword">
                        <h1>Mudar Senha</h1>
                        <div class="form-group">
                            <label for="current-password">Senha Atual</label>
                            <input type="password" name="this-password" id="current-password" class="form-control">
                        </div>
                        <div class="form-group">
                            <label for="new-password">Nova Senha</label>
                            <div class="inputPass">
                                <input type="password" name="new-password" id="new-password" class="form-control" >
                                <div class="password-strength">Strong</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirmar Nova Senha</label>
                                <input type="password" name="confirm-password" id="confirm-password" class="form-control">
                        </div>
                        <div class="msg-recommended">* Sua senha precisa conter letras minúsculas e maiúsculas, números e caracteres especiais.</div>
                        <div class="action-buttons">
                            <button type="submit" name="update-password" class="btn btn-primary">Salvar Alterações</button>
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