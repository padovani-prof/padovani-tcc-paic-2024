drop schema sgrp;

create schema sgrp;
CREATE TABLE sgrp.usuario(
    codigo int not null auto_increment primary key,
    nome varchar(100) not null,
    email varchar(100) not null,
    senha varchar(64) not null
);

insert into `sgrp`.`usuario`(nome, email, senha) values('SGRP', 'sgrp@uea.edu.br', sha2('123', 256));
insert into `sgrp`.`usuario`(nome, email, senha) values('Maria AGP', 'maria@uea.edu.br', sha2('123', 256));
insert into `sgrp`.`usuario`(nome, email, senha) values('Kleber', 'padovani@uea.edu.br', sha2('123', 256));
insert into `sgrp`.`usuario`(nome, email, senha) values('Joao', 'joao@uea.edu.br', sha2('123', 256));

CREATE TABLE IF NOT EXISTS `sgrp`.`perfil_usuario` (
  `codigo` INT NOT NULL auto_increment,
  `nome` VARCHAR(30) NOT NULL,
  `descricao` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;

insert into `sgrp`.`perfil_usuario`(nome, descricao) values('Acesso total', 'Usuário master');
insert into `sgrp`.`perfil_usuario`(nome, descricao) values('AGP', 'Usuários AGP');
insert into `sgrp`.`perfil_usuario`(nome, descricao) values('Administrativo', 'Usuário administrativo');
insert into `sgrp`.`perfil_usuario`(nome, descricao) values('Professores', 'Usuário professor');

CREATE TABLE IF NOT EXISTS `sgrp`.`usuario_perfil` (
  `codigo_perfil` INT NOT NULL,
  `codigo_usuario` INT NOT NULL,
  PRIMARY KEY (`codigo_perfil`, `codigo_usuario`),
  CONSTRAINT `fk_perfil_usuario_has_usuario_perfil_usuario1`
    FOREIGN KEY (`codigo_perfil`)
    REFERENCES `sgrp`.`perfil_usuario` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_perfil_usuario_has_usuario_usuario1`
    FOREIGN KEY (`codigo_usuario`)
    REFERENCES `sgrp`.`usuario` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


insert into `sgrp`.`usuario_perfil`(codigo_perfil, codigo_usuario) values(1, 1);
insert into `sgrp`.`usuario_perfil`(codigo_perfil, codigo_usuario) values(2, 2);
insert into `sgrp`.`usuario_perfil`(codigo_perfil, codigo_usuario) values(3, 4);
insert into `sgrp`.`usuario_perfil`(codigo_perfil, codigo_usuario) values(4, 3);
insert into `sgrp`.`usuario_perfil`(codigo_perfil, codigo_usuario) values(4, 4);

CREATE TABLE IF NOT EXISTS `sgrp`.`categoria_recurso` (
  `codigo` INT NOT NULL auto_increment,
  `nome` VARCHAR(50) NOT NULL,
  `descricao` VARCHAR(100) NOT NULL,
  `ambiente_fisico` CHAR(1) NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;

insert into `sgrp`.`categoria_recurso`(nome, descricao, ambiente_fisico)values('Projetores', 'Datashows e afins', 'N');
insert into `sgrp`.`categoria_recurso`(nome, descricao, ambiente_fisico)values('Laboratórios', 'Laboratórios', 'N');

CREATE TABLE IF NOT EXISTS `sgrp`.`recurso` (
  `codigo` INT NOT NULL auto_increment,
  `nome` VARCHAR(50) NOT NULL,
  `descricao` VARCHAR(100) NOT NULL,
  `codigo_categoria` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_recurso_categoria_recurso`
    FOREIGN KEY (`codigo_categoria`)
    REFERENCES `sgrp`.`categoria_recurso` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

insert into `sgrp`.`recurso`(nome, descricao, codigo_categoria) values('Datashow 1', 'Datashow', 1);
insert into `sgrp`.`recurso`(nome, descricao, codigo_categoria) values('Datashow 2', 'Datashow', 1);
insert into `sgrp`.`recurso`(nome, descricao, codigo_categoria) values('Datashow 3', 'Datashow', 1);
insert into `sgrp`.`recurso`(nome, descricao, codigo_categoria) values('Lab. 1', 'Laboratório de informática 1', 2);
insert into `sgrp`.`recurso`(nome, descricao, codigo_categoria) values('Lab. DI', 'Laboratório de Design 2', 2);

CREATE TABLE IF NOT EXISTS `sgrp`.`acesso_recurso` (
  `codigo` INT NOT NULL auto_increment,
  `codigo_recurso` INT NOT NULL,
  `codigo_perfil` INT NOT NULL,
  `hr_inicial` TIME NOT NULL,
  `hr_final` TIME NOT NULL,
  `dias_semana` CHAR(7) NOT NULL,
  `dt_inicial` DATE NOT NULL,
  `dt_final` DATE NULL,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_acesso_recurso_recurso1`
    FOREIGN KEY (`codigo_recurso`)
    REFERENCES `sgrp`.`recurso` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_acesso_recurso_perfil_usuario1`
    FOREIGN KEY (`codigo_perfil`)
    REFERENCES `sgrp`.`perfil_usuario` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

insert into `sgrp`.`acesso_recurso`(codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) 
    values(1, 1, '08:00', '18:00', 'NSNSSNS', '2024-09-25', '2024-12-31');
insert into `sgrp`.`acesso_recurso`(codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) 
    values(2, 2, '08:00', '18:00', 'NSNSSNS', '2024-09-25', null);
insert into `sgrp`.`acesso_recurso`(codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) 
    values(4, 3, '08:00', '18:00', 'NSNSSNS', '2024-09-25', '2024-10-01');
insert into `sgrp`.`acesso_recurso`(codigo_recurso, codigo_perfil, hr_inicial, hr_final, dias_semana, dt_inicial, dt_final) 
    values(5, 4, '08:00', '18:00', 'NSNSSNS', '2024-09-25', null);
