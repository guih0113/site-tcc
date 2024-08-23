<?php
session_start();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Screen</title>
    <link rel="stylesheet" href="./css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="./js/login.js" defer></script>
<body>
    <div class="signup-container">
        <div class="signup-image">
        <img src="favicon_io/favicon.ico" alt="Signup Illustration" id="icon">
        </div>
        <div class="signup-form">
            <h1>Welcome Back!</h1>
            <p>Welcome back, we missed you!</p>
            <form method="POST" id="contactForm">
                <div class="input-group">
                    <label for="username">Email</label>
                    <div class="input-field">
                        <span class="input-icon">📧</span>
                        <input type="email" id="email" name="email" placeholder="yourname@gmail.com" required>
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
                        Login($_POST['email'],$_POST['password']);
                    }
                ?>
            </form>
            <div class="social-signup">
                <p>Or sign in with</p>
                <div class="social-icons">
                    <a href="#"><img src="img/google-icon.png" alt="Google"></a>
                    <a href="#"><img src="img/apple-icon.png" alt="Apple"></a>
                    <a href="#"><img src="img/facebook-icon.png" alt="Facebook"></a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
