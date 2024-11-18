<?php
include_once('conexao1.php');
session_start();

if ((!isset($_SESSION['email']) || !isset($_SESSION['senha']))) {
    unset($_SESSION['email'], $_SESSION['senha']);
    header('Location: login.php');
    exit();
}

$email = $_SESSION['email'];
$username = $_SESSION['username'];
$user = $_SESSION['id'];

// Busca a foto do perfil
$sql = "SELECT nome_foto FROM tb_foto WHERE cd_usuario = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $user);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$perfil = isset($row['nome_foto']) ? $row['nome_foto'] : "img/profile.svg";
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/screen-cursos.css">
    <script src="./js/screen-cursos.js" defer></script>
    <title>Cursos disponíveis</title>
</head>
<body>
    <header>
        <div class="container">
            <a href="indexLogado.php"><img src="img/logo.png" alt="Logo" id="logo"></a> 
            <nav>
                <ul>
                    <a href="indexLogado.php"><li id="li-home">HOME</li></a>
                    <a href="screen-cursos.php"><li id="li-cursos">CURSOS</li></a>
                    <a href="conta.php"><li>MINHA CONTA</li></a>                      
                </ul>
            </nav>
            <div class="img-profile">
                <a href="#"><img src="<?php echo htmlspecialchars($perfil); ?>" alt="Foto do usuário" id="profile"></a>
            </div>
            <div class="dropdown">
                <div class="user">
                    <div class="informations">
                        <p><u id='name'><?php echo htmlspecialchars($username); ?></u></p>
                        <p><u id='email'><?php echo htmlspecialchars($email); ?></u></p>
                    </div>
                    <div class="infos">
                        <a href="conta.php"><p id="config"><span>⚙️</span> Configurações</p></a>
                        <a href="logOut.php"><p id="logout"><span>↩</span> Log out</p></a>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="main-courses-container">
            <h2>Cursos disponíveis:</h2>
            <div class="courses-container" id="cursos">

            </div>
        </div>
    </main>
</body>
</html>
