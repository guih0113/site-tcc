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
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="css/mainLogado.css">
    <script src="js/indexLogado.js" defer></script>
    <title>Estude para o Futuro</title>
</head>
<body>
    <header>
        <div class="container">
            <a href="indexLogado.php"><img src="img/logo.png" alt="Logo" id="logo"></a> 
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
                        <li>MINHA CONTA</li> 
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
    <!-- Fim header -->

    <section class="s-hero" id="home">
        <div class="container">
            <div class="left">
                <img src="img/Group 2.png" alt="celular">
            </div>
            <!-- Lado esquerdo section -->
            <div class="right">
                <h1>Construa seu futuro
                    financeiro com
                    conhecimento e
                    planejamento</h1>
                <p>Criamos um metodo para você se divertir e aprender um pouco mais sobre.</p>

                <a href="screen-cursos.php"><input type="submit" value="Começar"></a>
            </div>
            <!-- Lado Direito section -->
        </div>
    </section>

    <section class="s-hero" id="quemsomos">
        <div class="container">
            <!-- <div class="left">
                <img src="img/Group 2.png" alt="celular">
            </div> -->
            <!-- Lado esquerdo section -->
            <div class="right" id="section2">
                <h1>Quem somos?</h1>
                <p>Somos uma plataforma que tem por objetivo ensinar jovens e adultos sobre como administrar e cuidar de
                    suas vidas financeiras. Nosso objetivo para com a população é diminuir a discrepante base de
                    desinformação existente na área das finanças, disponibilizando um conteúdo de ótima qualidade à
                    todos que estiverem interessados em aprender.</p>
            </div>
            <div class="left" id="container-tablet">
                <img src="img/tablet.png" id="tablet" alt="celular">
            </div>
            <!-- Lado Direito section -->
        </div>
    </section>

    <section class="cursos">
        <h2>Cursos disponíveis:</h2>
        <div class="carrossel-container">
            <button class="prev"><</button>
            <div class="carrossel">
                <!-- Card de cursos aqui -->
                <div class="curso-card" data-index="0">
                    <div class="curso-nivel">
                        <span>&#11088;</span>
                    </div>
                    <h3>INICIANTE</h3>
                    <p>Curso de educação financeira para iniciantes</p>
                    <a href="curso.php?cursoId=1&aulaId=1"><button class="iniciar-button">INICIAR</button></a>
                </div>
                <div class="curso-card" data-index="1">
                    <div class="curso-nivel">
                        <span>&#11088;&#11088;</span>
                    </div>
                    <h3>INTERMEDIÁRIO</h3>
                    <p>Curso de educação financeira para intermediários</p>
                    <a href="curso.php?cursoId=2&aulaId=1"><button class="iniciar-button">INICIAR</button></a>
                </div>
                <div class="curso-card" data-index="2">
                    <div class="curso-nivel">
                        <span>&#11088;&#11088;&#11088;</span>
                    </div>
                    <h3>EXPERIENTES</h3>
                    <p>Curso de educação financeira para experientes</p>
                    <a href="curso.php?cursoId=3&aulaId=1"><button class="iniciar-button">INICIAR</button></a>
                </div>
            </div>
            <button class="next">></button>
        </div>
    </section>
    
    <!-- Fim section -->
    <footer id="contato">
        <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-pinterest-p"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
        </div>
        <nav class="footer-nav">
            <a href="#home">Home</a>
            <a href="#quemsomos">Quem somos</a>
            <a href="#">Cursos</a>
            <a href="#contato">Contato</a>
        </nav>
        <div class="footer-bottom">
            <p>&copy;Estude para o Futuro | 2024</p>
        </div>
    </footer>
</body>
</html>