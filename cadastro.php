<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up Screen</title>
    <link rel="stylesheet" href="./css/cadastro.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="./js/cadastro.js" defer></script>
<body>
    <div class="signup-container">
        <div class="signup-image">
            <img src="imgs/favicon.ico" alt="Signup Illustration" id="loira">
        </div>
        <div class="signup-form">
            <h1>Get Started Free</h1>
            <p>Free Forever. No Credit Card Needed</p>
            <form action="#" method="POST" id="contactForm">
                <div class="input-group">
                    <label for="email">Email Address</label>
                    <div class="input-field">
                        <span class="input-icon">📧</span>
                        <input type="email" id="email" name="email" placeholder="yourname@gmail.com" required>
                    </div>
                    <div class="error-msg">Email inválido!</div>
                </div>
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
                <input type="submit" class="sign-up-btn" value="Sign up" name="submit">
            </form>
            <div class="social-signup">
                <p>Or sign up with</p>
                <div class="social-icons">
                    <a href="#"><img src="imgs/google-icon.png" alt="Google"></a>
                    <a href="#"><img src="imgs/apple-icon.png" alt="Apple"></a>
                    <a href="#"><img src="imgs/facebook-icon.png" alt="Facebook"></a>
                </div>
            </div>
        </div>
    </div>
</body>
<?php
    if(isset($_POST['submit'])){
        include_once('conexao.php');

        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $username = filter_input(INPUT_POST, 'username');
        $password = filter_input(INPUT_POST, 'password');

        if($email && $username && $password){
            //verificação do email
            $sql = $conexao->prepare("SELECT * FROM tb_usuario WHERE email_usuario = :email");
            $sql->bindValue(':email', $email);
            $sql->execute();
            
            //verificação do username
            $sqlM = $conexao->prepare("SELECT * FROM tb_usuario WHERE username_usuario = :username");
            $sqlM->bindValue(':username', $username);
            $sqlM->execute();

            //cadastro do usuário
            if($sql->rowCount() === 0 && $sqlM->rowCount() === 0){
                $sql = $conexao->prepare("INSERT INTO tb_usuario (email_usuario,username_usuario,senha_usuario) VALUES ('$email','$username','$password')");
                $sql->bindValue(':email', $email);
                $sql->bindValue(':username', $username);
                $sql->bindValue(':password', $password);
                $sql->execute();
                header('Location: login.php');
                exit;
            }

            else{
                if($sql->rowCount() !== 0){
                    echo   "<script>
                                const errorMsg = document.querySelector('.error-msg');
                                errorMsg.innerHTML = 'Já existe um usuário cadastrado com esse email';
                                errorMsg.style.display = 'block';
                            </script>";
                }
                else if($sqlM->rowCount() !== 0){
                    echo   "<script>
                                const errorMsg = document.querySelector('.msg-error');
                                errorMsg.style.display = 'block';
                            </script>";
                }
            }
        }
    }
?>
</html>
