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

// Verifica se uma nova imagem foi enviada
if (isset($_FILES["imagem"]) && !empty($_FILES["imagem"]["name"])) {
    $imagem = "./img/" . basename($_FILES["imagem"]["name"]);
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $imagem)) {
        // Salva o caminho da imagem no banco de dados
        $query = "INSERT INTO tb_foto (nome_foto, cd_usuario) VALUES ('$imagem', '$user') ON DUPLICATE KEY UPDATE nome_foto = '$imagem'";
        mysqli_query($conexao, $query);
    } else {
        echo "Erro ao fazer upload da imagem.";
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
}

// Busca a imagem de perfil do usuário no banco de dados
$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = $user";
$res = mysqli_query($conexao, $sql);
$row = mysqli_fetch_assoc($res);
$perfil = isset($row['nome_foto']) ? $row['nome_foto'] : "img/profile.svg";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha conta</title>
    <link rel="stylesheet" href="./css/conta.css">
    <script src="./js/conta.js" defer></script>
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
                        <p id="config"><span>⚙️</span> Configurações</p>

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
                    <li><a href="#account-general" class="active">Geral</a></li>
                    <li><a href="#account-change-password">Mudar Senha</a></li>
                    <li><a href="#account-info">Informações</a></li>
                    <li><a href="#account-notifications">Notificações</a></li>
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
                                            <input type="file" name="imagem" accept="image/*" class="file-input" />
                                        </div>
                                    <button type="submit" name="remover_foto" class="btn">Remover foto</button>
                                </div>
                        </div>
                        <div class="form-group">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" class="form-control" value="<?php echo "$email" ?>">
                        </div>
                        <div class="form-group">
                            <label for="username">Nome de Usuário</label>
                            <input type="text" id="username" class="form-control" value="<?php echo "$username" ?>">
                        </div>
                        <div class="action-buttons">
                            <input type="submit" class="btn btn-primary" value="Salvar alterações">
                            <button type="button" class="btn btn-secondary">Cancelar</button>
                        </div>
                    </form>
                </section>

                <!-- Seção Mudar Senha -->
                <section id="account-change-password" class="tab-content">
                    <h1>Mudar Senha</h1>
                    <div class="form-group">
                        <label for="current-password">Senha Atual</label>
                        <input type="password" id="current-password" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="new-password">Nova Senha</label>
                        <input type="password" id="new-password" class="form-control" >
                    </div>
                    <div class="form-group">
                        <label for="confirm-password">Confirmar Nova Senha</label>
                        <input type="password" id="confirm-password" class="form-control">
                    </div>
                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary">Cancelar</button>
                    </div>
                </section>

                <!-- Seção Informações -->
                <section id="account-info" class="tab-content">
                    <h1>Informações</h1>
                    <div class="form-group">
                        <label for="address">Endereço</label>
                        <input type="text" id="address" class="form-control" value="Rua Exemplo, 123">
                    </div>
                    <div class="form-group">
                        <label for="phone">Telefone</label>
                        <input type="tel" id="phone" class="form-control" value="(11) 98765-4321">
                    </div>
                    <div class="form-group">
                        <label for="birthdate">Data de Nascimento</label>
                        <input type="date" id="birthdate" class="form-control" value="1985-05-15">
                    </div>
                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary">Cancelar</button>
                    </div>
                </section>

                <!-- Seção Notificações -->
                <section id="account-notifications" class="tab-content">
                    <h1>Notificações</h1>
                    <div class="form-group">
                        <label>
                            <input type="checkbox" checked> Receber notificações por e-mail
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox"> Receber notificações por SMS
                        </label>
                    </div>
                    <div class="form-group">
                        <label>
                            <input type="checkbox"> Receber notificações push
                        </label>
                    </div>
                    <div class="action-buttons">
                        <button type="button" class="btn btn-primary">Salvar Alterações</button>
                        <button type="button" class="btn btn-secondary">Cancelar</button>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
<?php
?>
</html>