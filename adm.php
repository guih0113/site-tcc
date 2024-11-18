<?php
    include_once('conexao1.php');
    session_start();
    
    if((!isset($_SESSION['userAdm']) == true) and (!isset($_SESSION['senhaAdm']) == true)){
        unset($_SESSION['userAdm']);
        unset($_SESSION['senhaAdm']);
        header('Location: loginAdm.php');
    }
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="./css/adm.css">
    <script src="./js/adm.js" defer></script>
    <title>Administrador</title>
</head>
<body>
    <div class="container-main">
        <h1>Administrador</h1>
        <div class="row">
            <nav class="sidebar">
                <ul class="nav-list">
                    <li class="list"><a href="adm.php" class="active">Usuários</a></li>
                    <li class="list"><a href="newAdm.php">Novo administrador</a></li>
                    <li class="list"><a href="editarCurso.php">Editar Curso</a></li>
                    <li class="list"><a href="editarConteudo.php">Editar módulos e aulas</a></li>
                    <a href="logOutAdm.php">
                        <li>Sair</li>
                    </a>
                </ul>
            </nav>
            <main class="content">
                <section class="admin-section">
                    <div class="admin-container">
                        <h2>Usuários cadastrados</h2>
                        <div id="table-content">
                            <table class="user-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Email</th>
                                        <th>Username</th>
                                        <th>Senha</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody id="users">

                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>