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
        $comando = 'SELECT * FROM tb_usuario WHERE email_usuario = "'.$email.'" AND senha_usuario = "'.$senha.'"';
        $retorno = $GLOBALS['conexao']->query($comando);
        $usuario = $retorno->fetch_array();

        if($retorno->num_rows > 0){
            $_SESSION['id'] = $usuario['id_usuario']; 
            $user = $_SESSION['id'];
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

    function LoginAdm($user, $senha){
        $comando = 'SELECT * FROM tb_adm WHERE username_adm = "'.$user.'" AND senha_adm = "'.$senha.'"';
        $retorno = $GLOBALS['conexao']->query($comando);
        $usuario = $retorno->fetch_array();

        if($retorno->num_rows > 0){         
            header('Location: index.html');
        }

        else{
            echo "<script>
                    const msgError = document.querySelector('.msg-alert');
                    msgError.style.display = 'block';
                  </script>";
        }
    }

    function getCursos(){
        $sql = 'SELECT * FROM tb_cursos';
    
        $retorno = $GLOBALS['conexao']->query($sql);
        return $retorno;
    }
?>