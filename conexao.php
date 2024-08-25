<?php
session_start();

try {
  $conexao = new PDO("mysql:host=localhost;port=3306;dbname=epf", "root", "usbw");
  $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $conexao->query("set character set utf8");
} catch (PDOException $e) {
  echo "Erro: " . $e->getMessage();
  die();
}

//verificando a conexão
if (!$conexao) {
  echo "Erro ao conectar: " . $conexao->error;
}

?>
