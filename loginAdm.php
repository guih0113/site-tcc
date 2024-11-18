<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
    <title>Log-in</title>
    <link rel="stylesheet" href="./css/login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="./js/login.js" defer></script>
<body>
    <div class="back-button">
        <a href="index.html"><i class="fas fa-arrow-left"></i></a>
    </div>
    <div class="signup-container">
        <div class="signup-image">
            <img src="favicon_io/favicon.ico" alt="Signup Illustration" id="icon">
        </div>
        <div class="signup-form">
            <h1>Seja bem vindo!</h1>
            <p>Seja bem vindo de volta, sentimos sua falta!</p>
            <form method="POST" id="contactForm">
                <div class="input-group">
                    <label for="username">Username</label>
                    <div class="input-field">
                        <span class="input-icon">👤</span>
                        <input type="text" id="email" name="email" placeholder="@username" required>
                    </div>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="input-field">
                        <span class="input-icon">🔒</span>
                        <input type="password" id="password" name="password" placeholder="Password" required>
                        <div class="eye-icon">👁️</div>
                    </div>
                </div>
                <div class="msg-alert">Dados inválidos!</div>
                <input type="submit" class="sign-up-btn" value="Sign in" name="submit">
                <?php
                    include('conexao1.php');
                    
                    if($_POST){
                        LoginAdm($_POST['email'],$_POST['password']);
                    }
                ?>
            </form>
            <div class="social-signup" style="height: 60px;">

            </div>
        </div>
    </div>
</body>
</html>
