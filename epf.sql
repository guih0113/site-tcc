CREATE DATABASE epf;
USE epf;

CREATE TABLE tb_usuario (
id_usuario int primary key auto_increment not null,
username_usuario varchar(45) not null,
email_usuario varchar(60) not null,
senha_usuario varchar(30) not null
);

CREATE TABLE tb_cursos (
id_curso int primary key auto_increment not null,
nome_curso varchar(40) not null,
descricao_curso varchar(200) not null,
icone_curso varchar(50) not null
);

INSERT INTO tb_cursos VALUES
(default,'INICIANTE','CURSO DE EDUCAÇÂO FINANCEIRA PARA INICIANTES','&#11088;'),
(default,'INTERMEDIÁRIO','CURSO DE EDUCAÇÂO FINANCEIRA PARA INTERMEDIÁRIOS','&#11088;&#11088;'),
(default,'EXPERIENTES','CURSO DE EDUCAÇÂO FINANCEIRA PARA EXPERIENTES','&#11088;&#11088;&#11088;');

SELECT * FROM tb_usuario;