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
    icone_curso varchar(50) not null,
    conclusao_curso decimal(10,2) not null
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

CREATE TABLE tb_usuario_aulas (
    id_usuario_aula INT AUTO_INCREMENT PRIMARY KEY,
    cd_usuario INT NOT NULL,
    cd_aula INT NOT NULL,
    concluida BOOLEAN DEFAULT 0,
    FOREIGN KEY (cd_usuario) REFERENCES tb_usuario(id_usuario),
    FOREIGN KEY (cd_aula) REFERENCES tb_aulas(id_aula),
    UNIQUE (cd_usuario, cd_aula) -- Garante que um usuário não tenha duplicidade para a mesma aula
);

-- Inserindo os cursos
INSERT INTO tb_cursos VALUES
(default,'INICIANTE','Curso de Educação Financeira para iniciantes','&#11088;', '0'),
(default,'INTERMEDIÁRIO','Curso de Educação Financeira para intermediários','&#11088;&#11088;', '0'),
(default,'EXPERIENTES','Curso de Educação Financeira para experientes','&#11088;&#11088;&#11088;', '0');

-- Inserindo os módulos
INSERT INTO tb_modulos (nome_modulo) VALUES
('Módulo 1 do Curso 1'),
('Módulo 2 do Curso 1'),
('Módulo 3 do Curso 1'),
('Módulo 1 do Curso 2'),
('Módulo 2 do Curso 2'),
('Módulo 3 do Curso 2'),
('Módulo 1 do Curso 3'),
('Módulo 2 do Curso 3'),
('Módulo 3 do Curso 3');

-- Associando os cursos aos módulos
INSERT INTO tb_cursos_modulos (cd_curso, cd_modulo) VALUES
(1, 1),
(1, 2),
(1, 3),
(2, 4),
(2, 5),
(2, 6),
(3, 7),
(3, 8),
(3, 9);

-- Criando 2 aulas para cada módulo
INSERT INTO tb_aulas (nome_aula, conteudo_aula) VALUES
('Aula 1 do Módulo 1 do Curso 1', 'img/video.mp4'),
('Aula 2 do Módulo 1 do Curso 1', 'img/video.mp4'),
('Aula 1 do Módulo 2 do Curso 1', 'img/video.mp4'),
('Aula 2 do Módulo 2 do Curso 1', 'img/video.mp4'),
('Aula 1 do Módulo 3 do Curso 1', 'img/video.mp4'),
('Aula 2 do Módulo 3 do Curso 1', 'img/video.mp4'),
('Aula 1 do Módulo 1 do Curso 2', 'img/video.mp4'),
('Aula 2 do Módulo 1 do Curso 2', 'img/video.mp4'),
('Aula 1 do Módulo 2 do Curso 2', 'img/video.mp4'),
('Aula 2 do Módulo 2 do Curso 2', 'img/video.mp4'),
('Aula 1 do Módulo 3 do Curso 2', 'img/video.mp4'),
('Aula 2 do Módulo 3 do Curso 2', 'img/video.mp4'),
('Aula 1 do Módulo 1 do Curso 3', 'img/video.mp4'),
('Aula 2 do Módulo 1 do Curso 3', 'img/video.mp4'),
('Aula 1 do Módulo 2 do Curso 3', 'img/video.mp4'),
('Aula 2 do Módulo 2 do Curso 3', 'img/video.mp4'),
('Aula 1 do Módulo 3 do Curso 3', 'img/video.mp4'),
('Aula 2 do Módulo 3 do Curso 3', 'img/video.mp4');

-- Relacionando as aulas aos módulos
INSERT INTO tb_modulos_aulas (cd_modulo, cd_aula) VALUES
(1, 1),
(1, 2),
(2, 3),
(2, 4),
(3, 5),
(3, 6),
(4, 7),
(4, 8),
(5, 9),
(5, 10),
(6, 11),
(6, 12),
(7, 13),
(7, 14),
(8, 15),
(8, 16),
(9, 17),
(9, 18);

-- Inserindo usuários
INSERT INTO tb_usuario VALUES
(default,'guih_0113','gui.henriquess13@gmail.com','guilherme1301'),
(default,'guilherme henrique','gui13@gmail.com','12345678'),
(default,'ricardo','ricardo@gmail.com','763876234'),
(default,'ric36_87','ric12@gmail.com','574784545'),
(default,'guilherme guedes','guiguedes@gmail.com','76237345'),
(default,'guedes','guedes3412@gmail.com','324575432'),
(default,'henrique','henrique12@gmail.com','096787444');

-- Inserindo administradores
INSERT INTO tb_adm VALUES
(default,'guihenrique','12345'),
(default,'guiguedes','12345'),
(default,'ricardo','12345');

-- Consultas para verificar se os dados foram inseridos corretamente
SELECT * FROM tb_adm;
SELECT * FROM tb_aulas;
SELECT * FROM tb_modulos_aulas;
SELECT * FROM tb_aulas;
SELECT * FROM tb_usuario_aulas;	
SELECT * FROM tb_usuario;
SELECT * FROM tb_cursos;