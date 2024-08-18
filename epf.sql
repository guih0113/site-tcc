CREATE DATABASE epf;
USE epf;

CREATE TABLE tb_usuario (
id_usuario int primary key auto_increment not null,
username_usuario varchar(45) not null,
email_usuario varchar(60) not null,
senha_usuario varchar(30) not null
);