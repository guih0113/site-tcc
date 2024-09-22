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
        $adm = $retorno->fetch_array();

        if($retorno->num_rows > 0){     
            $_SESSION['idAdm'] = $adm['id_adm']; 
            $_SESSION['userAdm'] = $adm['username_adm']; 
            $_SESSION['senhaAdm'] = $adm['senha_adm']; 
            header('Location: adm.php');
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

    function getUsers(){
        $sql = 'SELECT * FROM tb_usuario';

        $retorno = $GLOBALS['conexao']->query($sql);
        return $retorno;
    }

    function excluirUser($id){
        $sql = 'DELETE FROM tb_usuario WHERE id_usuario=' .$id;
        $resultado = $GLOBALS['conexao']->query($sql);
    
        if($resultado){ //garante que o registro foi excluído
          echo "Excluído com sucesso";
        }
    
        else{
          echo "Falha ao excluir: " .$sql;
        }
    }

    function getModules($idCurso) {
        $sql = 'SELECT * FROM tb_modulos 
                INNER JOIN tb_cursos_modulos ON tb_modulos.id_modulo = tb_cursos_modulos.cd_modulo 
                WHERE tb_cursos_modulos.cd_curso = ?';
                
        $stmt = $GLOBALS['conexao']->prepare($sql);
        $stmt->bind_param('i', $idCurso); // Segurança contra SQL injection
        $stmt->execute();
        return $stmt->get_result();
    }
    
    function getAulas($idModulo) {
        // Assegure que $idModulo seja um inteiro para evitar SQL Injection
        $idModulo = intval($idModulo);
    
        // Consulta para buscar as aulas associadas ao módulo
        $sql = "
            SELECT a.id_aula, a.nome_aula, a.conteudo_aula
            FROM tb_modulos_aulas ma
            INNER JOIN tb_aulas a ON ma.cd_aula = a.id_aula
            WHERE ma.cd_modulo = $idModulo
        ";
    
        // Executa a consulta
        $retorno = $GLOBALS['conexao']->query($sql);
    
        // Verifique se a consulta foi bem-sucedida
        if (!$retorno) {
            // Exibe o erro SQL e interrompe a execução
            die("Erro na consulta: " . $GLOBALS['conexao']->error);
        }
    
        // Retorna o resultado da consulta
        return $retorno;
    }
    
    
    
    
