<?php
$server = 'localhost';
$user = 'root';
$pass = 'usbw';
$db = 'epf';

$conexao = new mysqli($server, $user, $pass, $db);
$conexao->query("set character set utf8");

if (!$conexao) {
    echo "Erro ao conectar: " . $conexao->error;
}

function Login($email, $senha)
{
    $comando = 'SELECT * FROM tb_usuario WHERE email_usuario = "' . $email . '" AND senha_usuario = "' . $senha . '"';
    $retorno = $GLOBALS['conexao']->query($comando);
    $usuario = $retorno->fetch_array();

    if ($retorno->num_rows > 0) {
        $_SESSION['id'] = $usuario['id_usuario'];
        $_SESSION['email'] = $usuario['email_usuario'];
        $_SESSION['username'] = $usuario['username_usuario'];
        $_SESSION['senha'] = $usuario['senha_usuario'];
        header('Location: screen-cursos.php');
    } else {
        unset($_SESSION['email']);
        unset($_SESSION['senha']);
        echo "<script>
                    const msgError = document.querySelector('.msg-alert');
                    msgError.style.display = 'block';
                  </script>";
    }
}

function LoginAdm($user, $senha)
{
    $comando = 'SELECT * FROM tb_adm WHERE username_adm = "' . $user . '" AND senha_adm = "' . $senha . '"';
    $retorno = $GLOBALS['conexao']->query($comando);
    $adm = $retorno->fetch_array();

    if ($retorno->num_rows > 0) {
        $_SESSION['idAdm'] = $adm['id_adm'];
        $_SESSION['userAdm'] = $adm['username_adm'];
        $_SESSION['senhaAdm'] = $adm['senha_adm'];
        header('Location: adm.php');
    } else {
        echo "<script>
                    const msgError = document.querySelector('.msg-alert');
                    msgError.style.display = 'block';
                  </script>";
    }
}

function getCursos()
{
    $sql = 'SELECT * FROM tb_cursos';
    $retorno = $GLOBALS['conexao']->query($sql);

    if ($retorno && $retorno->num_rows > 0) {
        // Armazena as informações do primeiro curso na sessão, se desejado
        $curso = $retorno->fetch_assoc(); // Armazena o primeiro curso
        $_SESSION['idCurso'] = $curso['id_curso'];
        $_SESSION['nomeCurso'] = $curso['nome_curso'];
        $_SESSION['descCurso'] = $curso['descricao_curso'];
        $_SESSION['iconeCurso'] = $curso['icone_curso'];
        
        // Reinicia o ponteiro do resultado para garantir que todos os cursos sejam lidos no loop
        $retorno->data_seek(0); 
    }

    return $retorno;
}

function getUsers()
{
    $sql = 'SELECT * FROM tb_usuario';

    $retorno = $GLOBALS['conexao']->query($sql);
    return $retorno;
}

function excluirUser($id)
{
    $sql = 'DELETE FROM tb_usuario WHERE id_usuario=' . $id;
    $resultado = $GLOBALS['conexao']->query($sql);

    if ($resultado) { //garante que o registro foi excluído
        echo "Excluído com sucesso";
    } else {
        echo "Falha ao excluir: " . $sql;
    }
}

function getModules($idCurso)
{
    $sql = 'SELECT * FROM tb_modulos 
                INNER JOIN tb_cursos_modulos ON tb_modulos.id_modulo = tb_cursos_modulos.cd_modulo 
                WHERE tb_cursos_modulos.cd_curso = ?';

    $retorno = $GLOBALS['conexao']->query($sql);

    if($retorno && $retorno->num_rows > 0){
        // Armazena as informações do primeiro curso na sessão, se desejado
        $modulo = $retorno->fetch_assoc(); // Armazena o primeiro curso
        $_SESSION['idModulo'] = $modulo['id_modulo'];
        $_SESSION['nomeModulo'] = $modulo['nome_modulo'];
        
        // Reinicia o ponteiro do resultado para garantir que todos os cursos sejam lidos no loop
        $retorno->data_seek(0); 
    }

    return $retorno;
}

function getAulas($idModulo)
{
    // Assegure que $idModulo seja um inteiro para evitar SQL Injection
    $idModulo = intval($idModulo);

    // Consulta para buscar as aulas associadas ao módulo
    $sql = "
            SELECT a.id_aula, a.nome_aula, a.conteudo_aula
            FROM tb_modulos_aulas ma
            INNER JOIN tb_aulas a ON ma.cd_aula = a.id_aula
            WHERE ma.cd_modulo = $idModulo
        ";

    $retorno = $GLOBALS['conexao']->query($sql);

    if($retorno && $retorno->num_rows > 0){
        $aula = $retorno->fetch_assoc();
        $_SESSION['idAula'] = $aula['id_aula'];
        $_SESSION['nomeAula'] = $aula['nome_aula'];
        $_SESSION['conteudoAula'] = $aula['conteudo_aula'];
    }

    return $retorno;
}

function getPrimeiraAulaId($cursoId)
{
    // Query para pegar o ID da primeira aula do curso
    $query = "SELECT a.id_aula
                  FROM tb_cursos_modulos cm
                  JOIN tb_modulos m ON cm.cd_modulo = m.id_modulo
                  JOIN tb_modulos_aulas ma ON ma.cd_modulo = m.id_modulo
                  JOIN tb_aulas a ON ma.cd_aula = a.id_aula
                  WHERE cm.cd_curso = ?
                  ORDER BY a.id_aula ASC
                  LIMIT 1";

    $stmt = $GLOBALS['conexao']->prepare($query);
    $stmt->bind_param('i', $cursoId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    return $row['id_aula']; // Retorna o id da primeira aula do curso
}

function temAulaConcluida($conexao, $cursoId, $userId)
{
    $sql = "
        SELECT COUNT(*) AS aulas_concluidas
        FROM tb_usuario_aulas ua
        INNER JOIN tb_aulas a ON ua.cd_aula = a.id_aula
        INNER JOIN tb_modulos_aulas ma ON a.id_aula = ma.cd_aula
        INNER JOIN tb_cursos_modulos cm ON ma.cd_modulo = cm.cd_modulo
        WHERE ua.cd_usuario = ? AND cm.cd_curso = ? AND ua.concluida = 1
    ";

    if ($stmt = $conexao->prepare($sql)) {
        $stmt->bind_param("ii", $userId, $cursoId);

        if ($stmt->execute()) {
            $resultado = $stmt->get_result();

            if ($row = $resultado->fetch_assoc()) {
                return $row['aulas_concluidas'] > 0; // Retorna true se houver aulas concluídas
            }
        }
    }

    // Retorna false se a query falhar ou não houver resultados
    return false;
}

function editarUrl($url, $destino) {
    $conteudo = file_get_contents($url);
    if ($conteudo) {
        $nomeArquivo = basename($url); // Nome do arquivo a partir da URL
        $caminhoDestino = $destino . '/' . $nomeArquivo;
        file_put_contents($caminhoDestino, $conteudo);
        return $caminhoDestino; // Retorna o caminho completo para salvar no banco
    }
    return false; // Retorna falso se o download falhar
}



