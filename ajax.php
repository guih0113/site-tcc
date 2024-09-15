<?php
    include('conexao1.php');

    //exibir cursos na tela screenCursos
    if (isset($_GET['cursos'])) {
        $lista = getCursos();
        $retorno = '';
    
        while ($curso = $lista->fetch_array()) {
            $retorno .= '<div class="course-card">
                            <div class="course-header">
                                <span class="course-icon">' . $curso['icone_curso'] . '</span>
                                <h3>' . $curso['nome_curso'] . '</h3>
                                <p>' . $curso['descricao_curso'] . '</p>
                            </div>
                            <div class="card-button">
                                <a href="curso.php?cursoId=' . $curso['id_curso'] . '#">
                                    <button class="free-label">INICIAR</button>
                                </a>
                            </div>
                        </div>';
        }
    
        echo $retorno; // exibe os resultados
    }    


    //exibir usuarios
    if(isset($_GET['users'])){
        $lista = getUsers();
        $retorno = '';

        while($user = $lista->fetch_array()){
            $retorno .= '<tr>
                            <td>'.$user['id_usuario'].'</td>
                            <td>'.$user['email_usuario'].'</td>
                            <td>'.$user['username_usuario'].'</td>
                            <td>'.$user['senha_usuario'].'</td>
                            <td><button class="delete-btn" id="'.$user['id_usuario'].'" onclick="del(this)">Excluir</button></td>
                         </tr>';
        }

        echo $retorno; //exibe os resultados
    }
    
    if(isset($_GET['delUser'])){
        excluirUser($_GET['delUser']);
    }


