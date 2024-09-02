<?php
    include('conexao1.php');

    if($_FILES){
        $destino = './galeria/'.$_POST['p'];

        if(!is_dir($destino)){
            mkdir($destino, 0777);
        }

        $destino.= "/".$_FILES['foto']['name'];//name é o nome do arquivo
        if(move_uploaded_file($_FILES['foto']['tmp_name'], $destino)){//tmp_name é o que chama o nome do arquivo

            $sql = 'INSERT INTO tb_foto (nome_foto, cd_usuario)
                    VALUES("'.$destino.'", '.$_POST['foto_user'].')';

            $res = $GLOBALS['conexao1']->query($sql);
        }
    }
?>