<?php
    $server = 'localhost';
    $user = 'root';
    $pass = 'usbw';
    $db = 'epf';
    
    $conexao = new mysqli($server,$user,$pass,$db);
    $conexao->query("set character set utf8");

    if(!$conexao){
        echo "Erro ao conectar: ".$conexao->error;
    }

    function Login($email,$senha){
        session_start();
        $comando = 'SELECT * FROM tb_usuario WHERE email_usuario = "'.$email.'" AND senha_usuario = "'.$senha.'"';
        $retorno = $GLOBALS['conexao']->query($comando);
        $usuario = $retorno->fetch_array();

        if($retorno->num_rows > 0){
            $_SESSION['email'] = $usuario['email_usuario'];
            $_SESSION['username'] = $usuario['username_usuario'];
            $_SESSION['senha'] = $usuario['senha_usuario'];
            header('Location: screen-cursos.php');
        }

        else{
            unset($_SESSION['email']);
            unset($_SESSION['senha']);
            echo "<script>
                    const msgError = document.querySelector('.msg-alert');
                    msgError.style.display = 'block';
                  </script>";
        }
    }
?>