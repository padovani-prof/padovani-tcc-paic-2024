create schema sgrp;
CREATE TABLE sgrp.usuario(
    codigo int not null auto_increment primary key,
    nome varchar(100) not null,
    email varchar(100) not null,
    senha varchar(64) not null
);
