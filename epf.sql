-- Criando o banco de dados e selecionando-o
CREATE DATABASE epf;
USE epf;

-- Tabela de Usuários
CREATE TABLE tb_usuario (
    id_usuario int primary key auto_increment not null,
    username_usuario varchar(45) not null,
    email_usuario varchar(60) not null,
    senha_usuario varchar(30) not null
);

-- Tabela de Cursos
CREATE TABLE tb_cursos (
    id_curso int primary key auto_increment not null,
    nome_curso varchar(40) not null,
    descricao_curso varchar(200) not null,
    icone_curso varchar(50) not null
);

-- Tabela de Módulos
CREATE TABLE tb_modulos (
    id_modulo int primary key auto_increment not null,
    nome_modulo varchar(45) not null
);

-- Tabela de Relação entre Cursos e Módulos (Muitos para Muitos)
CREATE TABLE tb_cursos_modulos (
    id_cursomodulo int primary key auto_increment not null,
    cd_curso int not null,
    foreign key (cd_curso) references tb_cursos(id_curso),
    cd_modulo int not null,
    foreign key (cd_modulo) references tb_modulos(id_modulo)
);

-- Tabela de Aulas
CREATE TABLE tb_aulas (
    id_aula int primary key auto_increment not null,
    nome_aula varchar(45) not null,
    conteudo_aula varchar(100) not null
);

-- Tabela de Relação entre Módulos e Aulas (Muitos para Muitos)
CREATE TABLE tb_modulos_aulas (
    id_moduloaula int primary key auto_increment not null,
    cd_modulo int,
    foreign key(cd_modulo) references tb_modulos(id_modulo),
    cd_aula int,
    foreign key(cd_aula) references tb_aulas(id_aula)
);

-- Tabela de Fotos dos Usuários
CREATE TABLE tb_foto (
    id_foto int primary key auto_increment not null,
    nome_foto varchar(100) not null,
    cd_usuario int unique,
    foreign key(cd_usuario) references tb_usuario(id_usuario)
);

-- Tabela de Administradores
CREATE TABLE tb_adm (
    id_adm int primary key auto_increment not null,
    username_adm varchar(45) not null,
    senha_adm varchar(30) not null
);

-- Inserindo os cursos
INSERT INTO tb_cursos VALUES
(default,'INICIANTE','Curso de Educação Financeira para iniciantes','&#11088;'),
(default,'INTERMEDIÁRIO','Curso de Educação Financeira para intermediários','&#11088;&#11088;'),
(default,'EXPERIENTES','Curso de Educação Financeira para experientes','&#11088;&#11088;&#11088;');

-- Inserindo os módulos
INSERT INTO tb_modulos VALUES
(default,'Módulo 1 - Introdução à Educação Financeira'),
(default,'Módulo 2 - Planejamento e Orçamento'),
(default,'Módulo 3 - Investimentos Iniciais'),
(default,'Módulo 4 - Teste'),
(default,'Módulo 5 - Testando');

-- Associando os cursos aos módulos
INSERT INTO tb_cursos_modulos VALUES (default, 1, 1); -- Curso INICIANTE - Módulo 1
INSERT INTO tb_cursos_modulos VALUES (default, 1, 2); -- Curso INICIANTE - Módulo 2
INSERT INTO tb_cursos_modulos VALUES (default, 1, 3); -- Curso INICIANTE - Módulo 3

INSERT INTO tb_cursos_modulos VALUES (default, 2, 2); -- Curso INTERMEDIÁRIO - Módulo 2
INSERT INTO tb_cursos_modulos VALUES (default, 2, 4); -- Curso INTERMEDIÁRIO - Módulo 4

INSERT INTO tb_cursos_modulos VALUES (default, 3, 5); -- Curso EXPERIÊNTE - Módulo 5

-- Inserindo as aulas (a numeração continua de um módulo para o próximo)
INSERT INTO tb_aulas VALUES
(default,'Aula 1 - Introdução','Conteúdo da aula 1'),
(default,'Aula 2 - Conceitos Financeiros','Conteúdo da aula 2'),
(default,'Aula 3 - Poupança e Renda Fixa','Conteúdo da aula 3'),
(default,'Aula 4 - Planejamento Pessoal','Conteúdo da aula 4'),
(default,'Aula 5 - Orçamento Familiar','Conteúdo da aula 5'),
(default,'Aula 6 - Introdução a Investimentos','Conteúdo da aula 6'),
(default,'Aula 7 - Fundos de Investimento','Conteúdo da aula 7'),
(default,'Aula 8 - Testando Conceitos','Conteúdo da aula 8');

-- Associando as aulas aos módulos
INSERT INTO tb_modulos_aulas VALUES (default, 1, 1); -- Módulo 1 - Aula 1
INSERT INTO tb_modulos_aulas VALUES (default, 1, 2); -- Módulo 1 - Aula 2
INSERT INTO tb_modulos_aulas VALUES (default, 1, 3); -- Módulo 1 - Aula 3

INSERT INTO tb_modulos_aulas VALUES (default, 2, 4); -- Módulo 2 - Aula 4
INSERT INTO tb_modulos_aulas VALUES (default, 2, 5); -- Módulo 2 - Aula 5
INSERT INTO tb_modulos_aulas VALUES (default, 2, 6); -- Módulo 2 - Aula 6

INSERT INTO tb_modulos_aulas VALUES (default, 3, 7); -- Módulo 3 - Aula 7
INSERT INTO tb_modulos_aulas VALUES (default, 3, 8); -- Módulo 3 - Aula 8

-- Inserindo usuários
INSERT INTO tb_usuario VALUES
(default,'guih_0113','gui.henriquess13@gmail.com','guilherme1301');

-- Inserindo administradores
INSERT INTO tb_adm VALUES
(default,'guilherme','12345');

-- Consultas para verificar se os dados foram inseridos corretamente
SELECT * FROM tb_adm;
SELECT * FROM tb_aulas;
SELECT * FROM tb_modulos_aulas;
