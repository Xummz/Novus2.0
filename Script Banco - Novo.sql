#-- ****************** SqlDBM: MySQL ******************;
#-- ***************************************************;

#--Criando o Banco
CREATE DATABASE Banco_Novus
CHARACTER SET utf8
COLLATE utf8_unicode_ci;

#--Usando o Banco criado
USE Banco_Novus;

#-- ************************************** CadMaq

CREATE TABLE CadMaq
(
 Id                    int NOT NULL AUTO_INCREMENT ,
 Nome                  varchar(50) NOT NULL ,
 Descricao             varchar(255) NOT NULL ,
 Caracteristicas       varchar(255) NOT NULL ,
 Patrimonio            varchar(255) NOT NULL ,
 PeriodoManutencaoDays decimal NOT NULL ,
 AvisoAntesDays        decimal NOT NULL ,
 EnderecoEmailAviso    varchar(255) NOT NULL ,

PRIMARY KEY (Id)
);

#-- ************************************** PecasReposicao

CREATE TABLE PecasReposicao
(
 Id          int NOT NULL AUTO_INCREMENT ,
 Nome        varchar(50) NOT NULL ,
 Descricao   varchar(255) NOT NULL ,
 QtdeEstoque int NOT NULL ,

PRIMARY KEY (Id),
UNIQUE KEY UK_Nome (Nome)
);

#-- ************************************** Arquivo

CREATE TABLE Arquivo
(
 Id           int NOT NULL AUTO_INCREMENT ,
 CadMaqId     int NOT NULL ,
 File         longblob NOT NULL ,
 Descricao    varchar(255) NOT NULL ,
 DataInclusao datetime NOT NULL ,
 Extensao     varchar(20) NOT NULL ,
 NomeOriginal varchar(255) NOT NULL ,

PRIMARY KEY (Id),
KEY fkIdx_39 (CadMaqId),
CONSTRAINT FK_39 FOREIGN KEY fkIdx_39 (CadMaqId) REFERENCES CadMaq (Id)
);


#-- ****************** SqlDBM: MySQL ******************;
#-- ***************************************************;


#-- ************************************** CadMaqPecasReposicao

CREATE TABLE CadMaqPecasReposicao
(
 Id               int NOT NULL AUTO_INCREMENT ,
 CadMaqId         int NOT NULL ,
 PecasReposicaoId int NOT NULL ,
 QtdeMinima       int NOT NULL ,

PRIMARY KEY (Id),
KEY fkIdx_121 (CadMaqId),
CONSTRAINT FK_121 FOREIGN KEY fkIdx_121 (CadMaqId) REFERENCES CadMaq (Id),
KEY fkIdx_124 (PecasReposicaoId),
CONSTRAINT FK_124 FOREIGN KEY fkIdx_124 (PecasReposicaoId) REFERENCES PecasReposicao (Id)
);



#-- ****************** SqlDBM: MySQL ******************;
#-- ***************************************************;


#-- ************************************** HistMov

CREATE TABLE HistMov
(
 Id        varchar(20) NOT NULL ,
 Nome      varchar(50) NOT NULL ,
 Descricao varchar(255) NOT NULL ,

PRIMARY KEY (Id)
);

#--Preenchendo a tabela de histórico de movimentos com alguns registros mínimos
#--GO
INSERT INTO HistMov (Id, Nome, Descricao)
VALUES
    ('CRIACAO','Criação','Criação de um novo registro.'),
    ('ALTERACAO','Alteração','Alteração/modificação de um registro.'),
    ('MANUTENCAO','Manutenção','Manutenção de um máquina.');




#-- ************************************** ContatoResp

CREATE TABLE ContatoResp
(
 Id            int NOT NULL AUTO_INCREMENT ,
 Nome          varchar(50) NOT NULL ,
 Email         varchar(50) NOT NULL ,
 Telefone      varchar(20) NOT NULL ,
 InfoAdicional varchar(255) NOT NULL ,

PRIMARY KEY (Id),
UNIQUE KEY UK_Nome (Nome)
);


#-- ************************************** TipoManutencao

CREATE TABLE TipoManutencao
(
 Id        varchar(20) NOT NULL ,
 Nome      varchar(50) NOT NULL ,
 Descricao varchar(255) NOT NULL ,

PRIMARY KEY (Id)
);

#--Preenchendo a tabela de tipos de manutenção com os registros
#--GO
INSERT INTO TipoManutencao (Id, Nome, Descricao)
VALUES
    ('P','Preventiva','Manutenção preventiva da máquina.'),
    ('C','Corretiva','Manutenção corretiva da máquina.');


#-- ************************************** TipoPermissao

CREATE TABLE TipoPermissao
(
 Id        varchar(20) NOT NULL ,
 Nome      varchar(50) NOT NULL ,
 Descricao varchar(255) NOT NULL ,

PRIMARY KEY (Id)
);

#--Preenchendo a tabela de tipos de permissão com os registros
#--GO
INSERT INTO TipoPermissao (Id, Nome, Descricao)
VALUES
    ('CONSULTAR','Consultar','Permissão para consultar/visualizar registros.'),
    ('CRIAR','Criar','Permissão para criar registros.'),
    ('EDITAR','Editar','Permissão para editar registros.'),
    ('EXCLUIR','Excluir','Permissão para excluir registros.');



#-- ************************************** TipoUsuario


CREATE TABLE TipoUsuario
(
 Id        varchar(20) NOT NULL ,
 Nome      varchar(50) NOT NULL ,
 Descricao varchar(255) NOT NULL ,

PRIMARY KEY (Id),
UNIQUE KEY UK_Nome (Nome)
);

#--Preenchendo a tabela de tipos de permissão com os registros
#--GO
INSERT INTO TipoUsuario (Id, Nome, Descricao)
VALUES
    ('ADMIN','Administrador','Usuário administrador.'),
    ('FUNCIONARIO','Funcionário','Usuário é um funcionário.'),
    ('VISITANTE','Visitante','Usuário é um visitante.');



#-- ************************************** Usuario

CREATE TABLE Usuario
(
 Id            int NOT NULL AUTO_INCREMENT ,
 Usuario       varchar(50) NOT NULL ,
 Senha         varchar(50) NOT NULL ,
 Nome          varchar(50) NOT NULL ,
 Email         varchar(50) NOT NULL ,
 TipoUsuarioId varchar(20) NOT NULL ,

PRIMARY KEY (Id),
UNIQUE KEY UK_Usuario (Usuario),
CONSTRAINT FK_175 FOREIGN KEY (TipoUsuarioId) REFERENCES TipoUsuario (Id)
);

#-- ************************************** CadMaqContatoResp

CREATE TABLE CadMaqContatoResp
(
 Id            int NOT NULL AUTO_INCREMENT ,
 CadMaqId      int NOT NULL ,
 ContatoRespId int NOT NULL ,

PRIMARY KEY (Id),

CONSTRAINT FK_134 FOREIGN KEY (CadMaqId) REFERENCES CadMaq (Id),

CONSTRAINT FK_137 FOREIGN KEY (ContatoRespId) REFERENCES ContatoResp (Id)
);


#-- ************************************** MovMaq

CREATE TABLE MovMaq
(
 Id               int NOT NULL AUTO_INCREMENT ,
 CadMaqId         int NOT NULL ,
 HistMovId        varchar(20) NOT NULL ,
 Descricao        varchar(255) NOT NULL ,
 DtMovto          datetime NOT NULL ,
 TipoManutencaoId varchar(20) NULL ,
 DtManutencao     datetime NULL ,

PRIMARY KEY (Id),

CONSTRAINT FK_153 FOREIGN KEY (CadMaqId) REFERENCES CadMaq (Id),

CONSTRAINT FK_164 FOREIGN KEY (HistMovId) REFERENCES HistMov (Id),

CONSTRAINT FK_192 FOREIGN KEY (TipoManutencaoId) REFERENCES TipoManutencao (Id)
);

#-- ************************************** MovMaqPecasReposicaoManutencao

CREATE TABLE MovMaqPecasReposicaoManutencao
(
 Id               integer NOT NULL AUTO_INCREMENT ,
 MovMaqId         int NOT NULL ,
 PecasReposicaoId int NOT NULL ,
 QtdeUtilizada    int NOT NULL ,

PRIMARY KEY (Id),

CONSTRAINT FK_118 FOREIGN KEY (PecasReposicaoId) REFERENCES PecasReposicao (Id),

CONSTRAINT FK_199 FOREIGN KEY (MovMaqId) REFERENCES MovMaq (Id)
);

#-- ************************************** TipoPermissaoTipoUsuario

CREATE TABLE TipoPermissaoTipoUsuario
(
 Id              int NOT NULL AUTO_INCREMENT ,
 TipoUsuarioId   varchar(20) NOT NULL ,
 TipoPermissaoId varchar(20) NOT NULL ,

PRIMARY KEY (Id),

CONSTRAINT FK_183 FOREIGN KEY (TipoUsuarioId) REFERENCES TipoUsuario (Id),

CONSTRAINT FK_186 FOREIGN KEY (TipoPermissaoId) REFERENCES TipoPermissao (Id)
);

#--Vinculando a tabela de tipos de permissão com os tipos de usuários
#--GO
INSERT INTO TipoPermissaoTipoUsuario (TipoUsuarioId, TipoPermissaoId)
VALUES
    ('ADMIN','CONSULTAR'),
    ('ADMIN','CRIAR'),
    ('ADMIN','EDITAR'),
    ('ADMIN','EXCLUIR'),
    ('VISITANTE','CONSULTAR');


#--Preenchendo a tabela de usuários com alguns registros mínimos
#--GO
INSERT INTO Usuario (Usuario, Senha, Nome, Email, TipoUsuarioId)
VALUES
    ('Mateus','admin', 'Mateus Eduardo Dotto', 'mateus.dotto0555@unilasalle.edu.br', 'ADMIN'),
    ('Visitante','1234', 'Visitante Genérico', 'emailgenerico@gmail.com', 'VISITANTE');


#--Tabela de Movimento do Cadastro da Maquina. Guarda o Histórico dos dados para poder excluir movimentos e restaurar os dados.
#--GO

CREATE TABLE MovCadMaq
(
 Id                    int NOT NULL AUTO_INCREMENT ,
 MovMaqId              int NOT NULL ,
 Nome                  varchar(50) NOT NULL ,
 Descricao             varchar(255) NOT NULL ,
 Caracteristicas       varchar(255) NOT NULL ,
 Patrimonio            varchar(255) NOT NULL ,
 PeriodoManutencaoDays decimal NOT NULL ,
 AvisoAntesDays        decimal NOT NULL ,
 EnderecoEmailAviso    varchar(255) NOT NULL ,

PRIMARY KEY (Id),
KEY fkIdx_207 (MovMaqId),
CONSTRAINT FK_207 FOREIGN KEY (MovMaqId) REFERENCES MovMaq (Id)
);
