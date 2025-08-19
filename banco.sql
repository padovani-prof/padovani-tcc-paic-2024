drop schema if exists sgrp;

create schema sgrp;
CREATE TABLE sgrp.usuario(
    codigo int not null auto_increment primary key,
    nome varchar(100) not null,
    email varchar(100) not null,
    senha varchar(64) not null
)ENGINE = InnoDB;

insert into `sgrp`.`usuario`(nome, email, senha) values('SGRP', 'sgrp@uea.edu.br', sha2('123', 256));


CREATE TABLE IF NOT EXISTS `sgrp`.`perfil_usuario` (
  `codigo` INT NOT NULL auto_increment,
  `nome` VARCHAR(30) NOT NULL,
  `descricao` VARCHAR(200) NOT NULL,
   `codigo_criador_perfil` int not null,
   FOREIGN KEY (`codigo_criador_perfil`)
   REFERENCES `sgrp`.`usuario` (`codigo`),
   PRIMARY KEY (`codigo`))
ENGINE = InnoDB;

insert into `sgrp`.`perfil_usuario`(nome, descricao, codigo_criador_perfil) values('Acesso total', 'Usuário master', 1);


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


CREATE TABLE IF NOT EXISTS `sgrp`.`categoria_recurso` (
  `codigo` INT NOT NULL auto_increment,
  `nome` VARCHAR(50) NOT NULL,
  `descricao` VARCHAR(100) NOT NULL,
  `ambiente_fisico` CHAR(1) NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;



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


    
CREATE TABLE IF NOT EXISTS `sgrp`.`checklist` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `item` VARCHAR(50) NOT NULL,
  `codigo_recurso` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_checklist_recurso1`
    FOREIGN KEY (`codigo_recurso`)
    REFERENCES `sgrp`.`recurso` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



CREATE TABLE IF NOT EXISTS `sgrp`.`periodo` (
  `codigo` INT NOT NULL auto_increment,
  `nome` VARCHAR(10) NOT NULL,
  `dt_inicial` DATE NOT NULL,
  `dt_final` DATE NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;



CREATE TABLE IF NOT EXISTS `sgrp`.`disciplina` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(150) NOT NULL,
  `curso` VARCHAR(50) NOT NULL,
  `codigo_periodo` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_disciplina_periodo1`
    FOREIGN KEY (`codigo_periodo`)
    REFERENCES `sgrp`.`periodo` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



CREATE TABLE IF NOT EXISTS `sgrp`.`funcionalidade` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `sigla` VARCHAR(30) NOT NULL,
  `nome` VARCHAR(60) NOT NULL,
  `descricao` VARCHAR(200) NOT NULL,
  `ativa` CHAR(1) NOT NULL,
  PRIMARY KEY (`codigo`))
ENGINE = InnoDB;

INSERT INTO `sgrp`.`funcionalidade` (sigla, nome, descricao, ativa)
VALUES

  ('alt_perfil','Perfil de usuário: alterar','O usuário pode alterar as informações do perifl do sistema','S'),
  ('apag_perfil','Perfil de usuário: apagar','O usuário pode apagar perfis de usuários do sistema','S'),
  ('list_perfil','Perfil de usuário: listar','O usuário ver a listagem de perfis de usuários do sistema','S'),
  ('cad_perfil','Perfil de usuário: cadastrar','O usuário pode cadastrar novos perfis de usuário no sistema','S'),

  ('list_usuario','Usuário: listar','O usuário ver a listagem de usuários do sistema','S'),
  ('cad_usuario','Usuário: cadastrar','O usuário pode cadastrar um novo usuário no sistema','S'),
  ('alt_usuario','Usuário: alterar','O usuário pode alterar usuários do sistema','S'),
  ('apag_usuario','Usuário: apagar','O usuário pode apagar usuários do sistema','S'),

  ('cad_categoria_rec','Categoria de recurso: cadastrar','O usuário pode cadastrar a categoria de cada recursos no sistema','S'),
  ('alt_categoria_rec','Categoria de recurso: alterar','O usuário pode alterar as informações da categoria de cada recusros do sistema','S'),
  ('apag_categoria_rec','Categoria de recurso: apagar','O usuário pode apagar categorias de recursos do sistema','S'),
  ('list_categoria_rec','Categoria de recurso: listar','O usuário ver a listagem de categorias do sistema','S'),

  ('list_recurso','Recurso: listar','O usuário ver a listagem de recursos do sistema','S'),
  ('cad_recurso','Recurso: cadastrar','O usuário pode cadastrar novos recusros no sistema','S'),
  ('alt_recurso','Recurso: alterar','O usuário pode alterar as informções do recursos do sistema','S'),
  ('apag_recurso','Recurso: apagar','O usuário pode apagar recursos do sistema','S'),
  ('adm_perm_recurso','Recurso: administrar permissão','O usuário pode administrar permissão dos recursos do sistema','S'),
  ('adm_checklist_rec','Recurso: administrar checklist','O usuário pode administrar as checklista de cada recurso do sistema','S'),

  ('cad_retir_devoluc','Retirada e devolução: cadastrar','O usuário pode cadastrar novas retiradas e devoluções no sistema','S'),

  ('cons_disponibilidade','Disponibilidade: consultar','O usuário consulta as disponibiliadades e faser reservas conjuntas no sistema','S'),


  ('list_reserva','Reserva: listar','O usuário ver a listagem de reserva do sistema','S'),
  ('cad_reserva','Reserva: cadastrar','O usuário pode cadastrar novas reservas no sistema','S'),
  ('apag_reserva','Reserva: apagar','O usuário pode apagar reservas do sistema','S'),

  ('list_ensalamento','Ensalamento: listar','O usuário ver a listagem de ensalamentos do sistema','S'),
  ('cad_ensalamento','Ensalamento: cadastrar','O usuário pode cadastrar novos ensalamentos no sistema','S'),
  ('apag_ensalamento','Ensalamento: apagar','O usuário pode apagar os ensalamentos do sistema','S'),

  ('list_periodo','Período: listar','O usuário ver a listagem de periodos do sistema','S'),
  ('cad_periodo','Período: cadastrar','O usuário pode cadastrar novos períodos no sistema','S'),
  ('alt_periodo','Período: alterar','O usuário pode alterar periodos dentro do sistema','S'),
  ('apag_periodo','Período: apagar','O usuário pode apagar períodos dentro do sistema','S'),

  ('list_disciplina','Disciplina: listar','O usuário ver a listagem de disciplinas do sistema','S'),
  ('cad_disciplina','Disciplina: cadastrar','O usuário pode cadastrar novas disciplinas no sistema','S'),
  ('alt_disciplina','Disciplina: alterar','O usuário alterar as disciplinas do sistema','S'),
  ('apag_disciplina','Disciplina: apagar','O usuário pode apagar as disciplinas do sistema','S'),
  ('cancela_retirada','Retirada: cancelar','O usuário pode cancelar retiradas do sistema','S'),
  ('cancela_devolucao','Devolução: cancelar','O usuário pode cancelar devoluções do sistema','S'),

  ('perfisalheios_usuario','Usuário: perfis alheios','O usuário pode visualizar (e adicionar ao usuário cadastrado) perfil de usuários criados por outros usuário (se não tiver essa permissão, ele visualizará apenas seus próprios perfis criados)','S'),
  ('func_perfil','Perfil de usuário: funcionalidades','O usuário pode acrescentar funcionalidades ao perfil de usuário','S')
  
  
  ;




  

CREATE TABLE IF NOT EXISTS `sgrp`.`funcionalidade_perfil` (
  `codigo_funcionalidade` INT NOT NULL,
  `codigo_perfil` INT NOT NULL,
  PRIMARY KEY (`codigo_funcionalidade`, `codigo_perfil`),
  CONSTRAINT `fk_funcionalidade_has_perfil_usuario_funcionalidade1`
    FOREIGN KEY (`codigo_funcionalidade`)
    REFERENCES `sgrp`.`funcionalidade` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_funcionalidade_has_perfil_usuario_perfil_usuario1`
    FOREIGN KEY (`codigo_perfil`)
    REFERENCES `sgrp`.`perfil_usuario` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
/*
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (1,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (2,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (3,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (4,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (5,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (6,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (7,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (8,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (9,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (10,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (11,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (12,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (13,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (14,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (15,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (16,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (17,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (18,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (19,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (20,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (21,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (22,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (23,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (24,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (25,1);
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil) values (26,1);

*/
insert into `sgrp`.`funcionalidade_perfil` (codigo_funcionalidade, codigo_perfil)SELECT codigo, 1 from `sgrp`.`funcionalidade`;


create table `sgrp`.`reserva`(
	codigo int not null auto_increment primary key,
	justificativa varchar(150) not null,
	codigo_usuario_agendador int not null,
	codigo_recurso int not null,
	codigo_usuario_utilizador int not null, 
	foreign key(codigo_usuario_utilizador) references usuario(codigo) ON DELETE NO ACTION ON UPDATE NO ACTION,
	foreign key(codigo_usuario_agendador) references usuario(codigo) ON DELETE NO ACTION ON UPDATE NO ACTION,
	foreign key(codigo_recurso) references recurso(codigo) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB;

create table `sgrp`.`data_reserva`(
	codigo int not null auto_increment primary key,
	data date not null,
	hora_inicial time not null, 
	hora_final time not null, 
	codigo_reserva int not null,
	foreign key(codigo_reserva)  references reserva(codigo) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE = InnoDB; 





CREATE TABLE IF NOT EXISTS `sgrp`.`ensalamento` (
  `codigo` INT NOT NULL auto_increment,
  `dias_semana` CHAR(7) NOT NULL,
  `hora_inicial` TIME NOT NULL,
  `hora_final` TIME NOT NULL,
  `codigo_disciplina` INT NOT NULL,
  `codigo_sala` INT NOT NULL,
  PRIMARY KEY (`codigo`), 
  FOREIGN KEY (`codigo_disciplina`) REFERENCES `sgrp`.`disciplina` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY (`codigo_sala`) REFERENCES `sgrp`.`recurso` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `sgrp`.`reserva_ensalamento` (
  `codigo` INT NOT NULL AUTO_INCREMENT,
  `codigo_reserva` INT NOT NULL,
  `codigo_ensalamento` INT NOT NULL,
  PRIMARY KEY (`codigo`),
  FOREIGN KEY (`codigo_reserva`) REFERENCES `sgrp`.`reserva` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY (`codigo_ensalamento`) REFERENCES `sgrp`.`ensalamento` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


CREATE TABLE IF NOT EXISTS `sgrp`.`retirada_devolucao` (
  `codigo` INT NOT NULL auto_increment,
  `codigo_usuario` INT NOT NULL,
  `codigo_recurso` INT NOT NULL,
  `datahora` DATETIME NOT NULL,
  `tipo` CHAR(1) NOT NULL,
  `ativo` CHAR(1) NOT NULL,
  `hora_final` TIME NOT NULL,
  `codigo_reserva` int,
  PRIMARY KEY (`codigo`),
  CONSTRAINT `fk_retirada_devolucao_reserva1`  FOREIGN KEY (`codigo_reserva`) REFERENCES `sgrp`.`reserva` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_retirada_devolucao_usuario1`  FOREIGN KEY (`codigo_usuario`) REFERENCES `sgrp`.`usuario` (`codigo`)
    
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_retirada_devolucao_recurso1`  FOREIGN KEY (`codigo_recurso`) REFERENCES `sgrp`.`recurso` (`codigo`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;
 CREATE TABLE IF NOT EXISTS `sgrp`.`devolucao_checklist` (

    codigo int PRIMARY KEY AUTO_INCREMENT not null,
    codigo_checklist int not null,
    codigo_retirada_devolucao int not null,
    retirado_devolvido char(1) not null,
    
    FOREIGN KEY (codigo_checklist)
    REFERENCES checklist(codigo),
    
    FOREIGN KEY (codigo_retirada_devolucao)
    REFERENCES retirada_devolucao(codigo)
    
)ENGINE = InnoDB;



