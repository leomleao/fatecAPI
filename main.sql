-- phpMyAdmin SQL Dump
-- version 4.0.10.12
-- http://www.phpmyadmin.net
--
-- Máquina: mysql02.fatecjd.edu.br
-- Data de Criação: 02-Set-2016 às 20:31
-- Versão do servidor: 5.1.54-rel12.6-log
-- versão do PHP: 5.6.24-0+deb8u1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `fatecjd11`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `xrequests`
--


CREATE TABLE IF NOT EXISTS `xrequests` (
  `id` int(11) NOT NULL,
  `originip` varchar(45) NOT NULL DEFAULT '',
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB AUTO_INCREMENT=85 DEFAULT CHARSET=utf8 COMMENT='Requests from remote IPs';

ALTER TABLE `xrequests`
 ADD PRIMARY KEY (`id`), ADD KEY `ts` (`ts`), ADD KEY `originip` (`originip`);

ALTER TABLE `xrequests`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;


-- --------------------------------------------------------

--
-- Estrutura das tabelas do `oAuth`
--

CREATE TABLE oauth_clients (
  client_id VARCHAR(80) NOT NULL,
  client_secret VARCHAR(80),
  redirect_uri VARCHAR(2000) NOT NULL,
  grant_types VARCHAR(80),
  scope VARCHAR(100), user_id VARCHAR(80),
CONSTRAINT clients_client_id_pk PRIMARY KEY (client_id));

CREATE TABLE oauth_access_tokens (
  access_token VARCHAR(40) NOT NULL,
  client_id VARCHAR(80) NOT NULL,
  user_id VARCHAR(255),
  expires TIMESTAMP NOT NULL,
  scope VARCHAR(2000),
CONSTRAINT access_token_pk PRIMARY KEY (access_token));

CREATE TABLE oauth_authorization_codes (
  authorization_code VARCHAR(40) NOT NULL,
  client_id VARCHAR(80) NOT NULL,
  user_id VARCHAR(255),
  redirect_uri VARCHAR(2000),
  expires TIMESTAMP NOT NULL,
  scope VARCHAR(2000),
CONSTRAINT auth_code_pk PRIMARY KEY (authorization_code));

CREATE TABLE oauth_refresh_tokens (refresh_token VARCHAR(40) NOT NULL,
  client_id VARCHAR(80) NOT NULL,
  user_id VARCHAR(255),
  expires TIMESTAMP NOT NULL,
  scope VARCHAR(2000),
CONSTRAINT refresh_token_pk PRIMARY KEY (refresh_token));

CREATE TABLE oauth_scopes (scope TEXT,
  is_default BOOLEAN);

CREATE TABLE oauth_jwt (client_id VARCHAR(80) NOT NULL,
  subject VARCHAR(80),
  public_key VARCHAR(2000),
CONSTRAINT jwt_client_id_pk PRIMARY KEY (client_id));


-- --------------------------------------------------------




--
-- Estrutura da tabela `alunos`
--

CREATE TABLE IF NOT EXISTS `alunos` (
  `ra` char(13) NOT NULL,
  `situacao` char(2) DEFAULT NULL,
  `nome` char(70) NOT NULL,
  `datanascimento` date DEFAULT NULL,
  `sexo` char(1) DEFAULT NULL,
  `estadocivil` char(20) DEFAULT NULL,
  `naturalidade` char(30) DEFAULT NULL,
  `estado` char(2) DEFAULT NULL,
  `certmilitar` char(13) DEFAULT NULL,
  `militartexto` char(50) DEFAULT NULL,
  `militarexp` char(3) DEFAULT NULL,
  `militardata` date DEFAULT NULL,
  `rg` char(20) DEFAULT NULL,
  `rgexp` char(10) DEFAULT NULL,
  `rgdata` date DEFAULT NULL,
  `eleitor` char(15) DEFAULT NULL,
  `secao` char(4) DEFAULT NULL,
  `zona` char(5) DEFAULT NULL,
  `pai` char(50) DEFAULT NULL,
  `mae` char(50) DEFAULT NULL,
  `endereco` char(40) DEFAULT NULL,
  `numero` char(5) DEFAULT NULL,
  `complemento` char(20) DEFAULT NULL,
  `bairro` char(30) DEFAULT NULL,
  `cep` char(9) DEFAULT NULL,
  `email` char(80) DEFAULT NULL,
  `email2` varchar(80) NOT NULL,
  `estabelecimento` char(150) DEFAULT NULL,
  `cidade` char(30) DEFAULT NULL,
  `estabestado` char(2) DEFAULT NULL,
  `anoconclusao` char(4) DEFAULT NULL,
  `vestibular` char(10) DEFAULT NULL,
  `pontos` char(7) DEFAULT NULL,
  `mesano` char(7) DEFAULT NULL,
  `curso` char(2) DEFAULT NULL,
  `origemtransf` char(100) DEFAULT NULL,
  `datatransf` date DEFAULT NULL,
  `nacionalidade` char(20) DEFAULT NULL,
  `conclusao` date DEFAULT NULL,
  `colacao` date DEFAULT NULL,
  `diploma` date DEFAULT NULL,
  `registro` char(30) DEFAULT NULL,
  `titulo` char(30) DEFAULT NULL,
  `matricula` date DEFAULT NULL,
  `municipio` char(30) DEFAULT NULL,
  `observacao` varchar(5000) DEFAULT NULL,
  `login` char(10) DEFAULT NULL,
  `dataalteracao` date DEFAULT NULL,
  `horaalteracao` time DEFAULT NULL,
  `tipodoc` char(6) DEFAULT NULL,
  `senha` varchar(256) DEFAULT NULL,
  `m` char(1) DEFAULT NULL,
  `prazoconclusao` char(2) DEFAULT NULL,
  `afro` char(1) DEFAULT NULL,
  `escola` char(3) DEFAULT NULL,
  `formaIngresso` char(2) DEFAULT NULL,
  `ano` char(4) DEFAULT NULL,
  `semestre` char(2) DEFAULT NULL,
  `id_inep` char(12) NOT NULL,
  `id_inepOLD` char(12) NOT NULL,
  `cpf` char(16) DEFAULT NULL,
  `enade` varchar(200) NOT NULL,
  `nomeSocial` char(70) NOT NULL,
  `previsao` char(1) NOT NULL,
  `cor` varchar(10) NOT NULL,
  PRIMARY KEY (`ra`),
  KEY `situacao` (`situacao`),
  KEY `curso` (`curso`),
  KEY `cpf` (`cpf`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `curso`
--

CREATE TABLE IF NOT EXISTS `curso` (
  `codcurso` char(2) NOT NULL,
  `curso` char(80) DEFAULT NULL,
  `cargahoraria` char(7) DEFAULT NULL,
  `ch_hr` int(11) NOT NULL,
  `semestres` char(2) DEFAULT NULL,
  `alunos` char(3) DEFAULT NULL,
  `periodo` char(10) DEFAULT NULL,
  `modalidade` char(30) DEFAULT NULL,
  `observacao` varchar(300) DEFAULT NULL,
  `login` char(10) DEFAULT NULL,
  `dataalteracao` date DEFAULT NULL,
  `horaalteracao` time DEFAULT NULL,
  `sigla` char(8) DEFAULT NULL,
  `prazominimo` char(2) DEFAULT NULL,
  `prazomaximo` char(2) DEFAULT NULL,
  `horasEstagio` char(4) NOT NULL,
  `pg` double NOT NULL,
  `id_inep` char(20) NOT NULL,
  `acc` int(11) NOT NULL COMMENT 'Horas de atividade cientif  cult, ...',
  `chTotPPhoras` char(4) NOT NULL,
  PRIMARY KEY (`codcurso`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `disciplinas`
--

CREATE TABLE IF NOT EXISTS `disciplinas` (
  `coddisciplina` char(5) NOT NULL,
  `sigla` char(15) DEFAULT NULL,
  `disciplina` char(60) NOT NULL,
  `cargahoraria` char(3) DEFAULT NULL,
  `tipo` char(4) DEFAULT NULL,
  `semestre` char(2) DEFAULT NULL,
  `chsemanal` char(4) DEFAULT NULL,
  `codgrupo` int(11) DEFAULT NULL,
  `codprofessor` char(10) DEFAULT NULL,
  `codcurso` char(2) DEFAULT NULL,
  `login` char(10) DEFAULT NULL,
  `dataalteracao` date DEFAULT NULL,
  `horaalteracao` time DEFAULT NULL,
  `tcc` char(1) NOT NULL,
  `equivalente` char(8) NOT NULL,
  `extra` char(1) NOT NULL,
  `antiga` char(1) NOT NULL,
  `grade` char(2) NOT NULL,
  `codProf2` char(10) NOT NULL,
  `estagio` char(1) NOT NULL,
  `aap` char(1) NOT NULL,
  `aacc` char(1) NOT NULL,
  `presencial` char(1) NOT NULL,
  PRIMARY KEY (`coddisciplina`),
  KEY `sigla` (`sigla`),
  KEY `codprofessor` (`codprofessor`),
  KEY `codcurso` (`codcurso`),
  KEY `equivalente` (`equivalente`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico`
--

CREATE TABLE IF NOT EXISTS `historico` (
  `codhistorico` int(11) NOT NULL AUTO_INCREMENT,
  `ra` char(13) DEFAULT NULL,
  `coddisciplina` char(5) DEFAULT NULL,
  `semestre` char(1) DEFAULT NULL,
  `ano` char(4) DEFAULT NULL,
  `cargahoraria` char(3) DEFAULT NULL,
  `conceito` char(1) DEFAULT NULL,
  `faltas1` char(2) NOT NULL DEFAULT '0',
  `faltas2` char(2) NOT NULL DEFAULT '0',
  `faltas3` char(2) NOT NULL DEFAULT '0',
  `faltas4` char(2) NOT NULL DEFAULT '0',
  `faltas5` char(3) NOT NULL DEFAULT '0',
  `faltastot` char(2) DEFAULT NULL,
  `media` char(4) DEFAULT NULL,
  `login` char(10) DEFAULT NULL,
  `dataalteracao` date DEFAULT NULL,
  `horaalteracao` time DEFAULT NULL,
  `periodoreserva` char(5) DEFAULT NULL,
  `codobservacao` char(3) NOT NULL,
  `datamatricula` date DEFAULT NULL,
  `notas1` char(4) DEFAULT '0',
  `notas2` char(4) DEFAULT NULL,
  `notas3` char(4) DEFAULT NULL,
  `notas4` char(4) DEFAULT NULL,
  `notas5` char(4) DEFAULT NULL,
  `codEq` char(5) NOT NULL,
  `codEq2` char(5) NOT NULL,
  `codEq1p2` char(5) NOT NULL,
  `ip` char(15) NOT NULL,
  PRIMARY KEY (`codhistorico`),
  UNIQUE KEY `ra_2` (`ra`,`coddisciplina`,`semestre`,`ano`),
  KEY `ra` (`ra`),
  KEY `coddisciplina` (`coddisciplina`),
  KEY `semestre` (`semestre`),
  KEY `ano` (`ano`),
  KEY `periodoreserva` (`periodoreserva`),
  KEY `codEq` (`codEq`),
  KEY `codEq2` (`codEq2`),
  KEY `codEq1p2` (`codEq1p2`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=338062 ;

--
-- Acionadores `historico`
--
DROP TRIGGER IF EXISTS `histatua`;
DELIMITER //
CREATE TRIGGER `histatua` AFTER UPDATE ON `historico`
 FOR EACH ROW BEGIN

IF (NEW.notas1 <> OLD.notas1 or NEW.notas2 <> OLD.notas2 or NEW.media <> OLD.media or NEW.codobservacao <> OLD.codobservacao) THEN
        INSERT INTO trk_hist 
            (`codhistorico` , `ra` , `coddisciplina` , `semestre`,`ano`,`cargahoraria`,`faltas1`,`faltas2`,`faltas3`,`faltas4`,`faltas5`,`faltastot`,`media`,`login`,`dataalteracao`,`horaalteracao`,`codobservacao`,`notas1`,`notas2`,`ip`, `dia`, `obs` ) 
        VALUES 
            (OLD.codhistorico , OLD.ra , OLD.coddisciplina , OLD.semestre,OLD.ano,OLD.cargahoraria,OLD.faltas1,OLD.faltas2,OLD.faltas3,OLD.faltas4,OLD.faltas5,OLD.faltastot,OLD.media,OLD.login,OLD.dataalteracao,OLD.horaalteracao,OLD.codobservacao,OLD.notas1,OLD.notas2,OLD.ip, now(), 'ant');

		INSERT INTO trk_hist 
            (`codhistorico` , `ra` , `coddisciplina` , `semestre`,`ano`,`cargahoraria`,`faltas1`,`faltas2`,`faltas3`,`faltas4`,`faltas5`,`faltastot`,`media`,`login`,`dataalteracao`,`horaalteracao`,`codobservacao`,`notas1`,`notas2`,`ip`, `dia`, `obs` ) 
        VALUES 
            (NEW.codhistorico , NEW.ra , NEW.coddisciplina , NEW.semestre,NEW.ano,NEW.cargahoraria,NEW.faltas1,NEW.faltas2,NEW.faltas3,NEW.faltas4,NEW.faltas5,NEW.faltastot,NEW.media,NEW.login,NEW.dataalteracao,NEW.horaalteracao,NEW.codobservacao,NEW.notas1,NEW.notas2,NEW.ip, now(), 'dep');
END IF;
END
//
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `horario`
--

CREATE TABLE IF NOT EXISTS `horario` (
  `codhorario` int(11) NOT NULL AUTO_INCREMENT,
  `codcurso` char(2) DEFAULT NULL,
  `ano` char(4) DEFAULT NULL,
  `semestre` char(1) DEFAULT NULL,
  `periodo` char(2) DEFAULT NULL,
  `horario` char(13) DEFAULT NULL,
  `coddisciplina` char(5) DEFAULT NULL,
  `linha` int(11) DEFAULT NULL,
  `login` char(10) DEFAULT NULL,
  `dataalteracao` date DEFAULT NULL,
  `horaalteracao` time DEFAULT NULL,
  `periododia` char(1) DEFAULT NULL,
  PRIMARY KEY (`codhorario`),
  KEY `codcurso` (`codcurso`),
  KEY `ano` (`ano`),
  KEY `semestre` (`semestre`),
  KEY `coddisciplina` (`coddisciplina`),
  KEY `linha` (`linha`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=57627 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `periodo`
--

CREATE TABLE IF NOT EXISTS `periodo` (
  `ano` char(4) NOT NULL,
  `semestre` char(1) NOT NULL,
  `mesFalta` char(2) NOT NULL,
  `prova` char(2) NOT NULL,
  `status_prova` char(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Estrutura da tabela `professores`
--

CREATE TABLE IF NOT EXISTS `professores` (
  `codprofessor` char(10) CHARACTER SET latin1 NOT NULL DEFAULT '',
  `nome` char(50) CHARACTER SET latin1 DEFAULT NULL,
  `login` char(25) CHARACTER SET latin1 DEFAULT NULL,
  `dataalteracao` date DEFAULT NULL,
  `horaalteracao` time DEFAULT NULL,
  `nomecurto` char(20) CHARACTER SET latin1 DEFAULT NULL,
  `senha` varchar(256) CHARACTER SET latin1 NOT NULL,
  `tel1` char(30) CHARACTER SET latin1 NOT NULL,
  `tel2` char(30) CHARACTER SET latin1 NOT NULL,
  `email1` char(50) CHARACTER SET latin1 NOT NULL,
  `email2` char(50) CHARACTER SET latin1 NOT NULL,
  `grad1` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemg1` char(20) CHARACTER SET latin1 NOT NULL,
  `grad2` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemg2` char(20) CHARACTER SET latin1 NOT NULL,
  `grad3` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemg3` char(20) CHARACTER SET latin1 NOT NULL,
  `pos1` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemp1` char(20) CHARACTER SET latin1 NOT NULL,
  `pos2` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemp2` char(20) CHARACTER SET latin1 NOT NULL,
  `pos3` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemp3` char(20) CHARACTER SET latin1 NOT NULL,
  `mest1` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemmest1` char(20) CHARACTER SET latin1 NOT NULL,
  `mest2` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemmest2` char(20) CHARACTER SET latin1 NOT NULL,
  `dr1` varchar(256) CHARACTER SET latin1 NOT NULL,
  `anosemdr1` char(20) CHARACTER SET latin1 NOT NULL,
  `linklattes` varchar(80) CHARACTER SET latin1 NOT NULL,
  `mtitulacao` varchar(50) CHARACTER SET latin1 NOT NULL,
  `categoria` char(30) CHARACTER SET latin1 NOT NULL,
  `ativo` char(1) CHARACTER SET latin1 NOT NULL,
  `staf` char(1) CHARACTER SET latin1 NOT NULL,
  `stafCurso` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `tratamento` varchar(30) CHARACTER SET latin1 NOT NULL,
  `apelido` char(50) CHARACTER SET latin1 NOT NULL,
  `rg` char(15) CHARACTER SET latin1 DEFAULT NULL,
  `matricula` char(5) CHARACTER SET latin1 DEFAULT NULL,
  `cpf` char(11) CHARACTER SET latin1 NOT NULL,
  `nasc` date NOT NULL,
  `sexo` char(1) CHARACTER SET latin1 NOT NULL,
  `cor_raca` char(1) CHARACTER SET latin1 NOT NULL,
  `nacionalidade` int(11) NOT NULL,
  `pais_origem` char(20) CHARACTER SET latin1 NOT NULL,
  `numero` char(3) CHARACTER SET latin1 NOT NULL,
  `letra` char(1) CHARACTER SET latin1 NOT NULL,
  PRIMARY KEY (`codprofessor`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
