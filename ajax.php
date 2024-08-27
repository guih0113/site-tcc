<?php
    include('conexao1.php');

    if(isset($_GET['cursos'])){
        $lista = getCursos();
        $retorno = '';

        while($curso = $lista->fetch_array()){
            $retorno .='<div class="course-card">
                            <div class="course-header">
                                <span class="course-icon">'.$curso['icone_curso'].'</span>
                                <h3>'.$curso['nome_curso'].'</h3>
                                <p>'.$curso['descricao_curso'].'</p>
                            </div>
                            <div class="card-button">
                                <a href="curso.php"><button class="free-label">INICIAR</button></a>
                            </div>
                        </div>';
        }

        echo $retorno; //exibe os resultados
    }
?>