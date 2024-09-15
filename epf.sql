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

CREATE TABLE tb_modulos (
id_modulo int primary key auto_increment not null,
numero_modulo int not null,
nome_modulo varchar(45) not null,
cd_curso int,
foreign key(cd_curso) references tb_cursos(id_curso)
);

CREATE TABLE tb_aulas (
id_aula int primary key auto_increment not null,
numero_aula int not null,
nome_aula varchar(45) not null,
conteudo_aula varchar(100) not null,
cd_modulo int,
foreign key(cd_modulo) references tb_modulos(id_modulo)
);

CREATE TABLE tb_foto (
id_foto int primary key auto_increment not null,
nome_foto varchar(100) not null,
cd_usuario int unique,
foreign key(cd_usuario) references tb_usuario(id_usuario)
);

CREATE TABLE tb_adm (
id_adm int primary key auto_increment not null,
username_adm varchar(45) not null,
senha_adm varchar(30) not null
);

INSERT INTO tb_cursos VALUES
(default,'INICIANTE','CURSO DE EDUCAÇÂO FINANCEIRA PARA INICIANTES','&#11088;'),
(default,'INTERMEDIÁRIO','CURSO DE EDUCAÇÂO FINANCEIRA PARA INTERMEDIÁRIOS','&#11088;&#11088;'),
(default,'EXPERIENTES','CURSO DE EDUCAÇÂO FINANCEIRA PARA EXPERIENTES','&#11088;&#11088;&#11088;');

INSERT INTO tb_usuario VALUES
(default,'guih_0113','gui.henriquess13@gmail.com','guilherme1301');
SELECT * FROM tb_usuario;
SELECT * FROM tb_foto;

INSERT INTO tb_adm VALUES
(default,'guilherme','12345');

SELECT * FROM tb_adm;