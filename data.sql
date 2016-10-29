--
-- Base de Dados: `fatecjd11`
--

-- --------------------------------------------------------

--
-- Alunos
--

-- a senha de todo mundo ta como 123456 em hash sha-256

INSERT INTO `alunos` (`ra`, `nome`, `email2`, `senha`) VALUES ('141b22', 'Leonardo Medeiros Leao', 'leomleao@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

INSERT INTO `alunos` (`ra`, `nome`, `email2`, `senha`) VALUES ('141b12', 'Felipe Fonseca', 'felipe_e_f@hotmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

INSERT INTO `alunos` (`ra`, `nome`, `email2`, `senha`) VALUES ('141b21', 'Leonardo Augusto', 'augusto.c.m@live.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

INSERT INTO `alunos` (`ra`, `nome`, `email2`, `senha`) VALUES ('141b24', 'Lucas Cirilo', 'lucas_cirilo_@hotmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

INSERT INTO `alunos` (`ra`, `nome`, `email2`, `senha`) VALUES ('141b25', 'Marcos Esteves Jr.', 'marco.esteves.junior@gmail.com', '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92');

--
-- 
--

-- --------------------------------------------------------

--
-- Disciplinas 2016 - 2
--

-- --------------------------------------------------------

--
-- 
--


INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('1', 'Estágio Supervisionado','6');

INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('2', 'Gestão e Governança de Tecnologia da Informação','6');

INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('3', 'Inteligência Artificial','6');

INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('4', 'Trabalho de Graduação II','6');

--
-- Disciplinas 2016 - 1
--


INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('5', 'Ética e Responsabilidade Profissional','5');

INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('6', 'Laboratório de Engenharia de Software','5');

INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('7', 'Tópicos Especiais em Informática','5');

INSERT INTO `disciplinas` (`coddisciplina`, `disciplina`, `semestre`) VALUES ('8', 'Trabalho de Graduação I','5');

--
-- 
--

-- --------------------------------------------------------

--
-- Horarios 2016 - 2
--

-- --------------------------------------------------------

--
-- 
--


--   'Estágio Supervisionado'
INSERT INTO `horario` (`codhorario`, `ano`, `semestre`, `periodo`, `horario`, `coddisciplina`, `periododia`) VALUES ('1', '2016', '2', '7', '09:20 / 10:10', '1', '3');

--   'Gestão e Governança de Tecnologia da Informação'
INSERT INTO `horario` (`codhorario`, `ano`, `semestre`, `periodo`, `horario`, `coddisciplina`, `periododia`) VALUES ('2', '2016', '2', '6', '19:00 / 22:30', '2', '3');

--   'Inteligência Artificial'
INSERT INTO `horario` (`codhorario`, `ano`, `semestre`, `periodo`, `horario`, `coddisciplina`, `periododia`) VALUES ('3', '2016', '2', '4', '19:00 / 22:30', '3', '3');

--   'Trabalho de Graduação II'
INSERT INTO `horario` (`codhorario`, `ano`, `semestre`, `periodo`, `horario`, `coddisciplina`, `periododia`) VALUES ('4', '2016', '2', '7', '07:30 / 08:20', '4', '3');


--
-- 
--

-- --------------------------------------------------------

--
-- Historico 2016 - 2
--

-- --------------------------------------------------------

--
-- 
--

--
--  Estagio Supervisionado
--

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','1','2','2016','0',NULL,NULL,NULL);

-- --------------------------------------------------------

--
--  Gestão e Governança de Tecnologia da Informação	
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','2','2','2016','4',NULL,'5.0',NULL);

-- --------------------------------------------------------

--
--  Inteligência Artificial	
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','3','2','2016','8','6.0',NULL,NULL);


-- --------------------------------------------------------

--
--  Trabalho de Graduação II	
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','4','2','2016','0','0',NULL,NULL);



-- --------------------------------------------------------

--
--  Ética e Responsabilidade Profissional	
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','5','1','2016','0','9.3','9.5','9.0');



-- --------------------------------------------------------

--
--  Laboratório de Engenharia de Software
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','6','1','2016','8','8.5','8.0','9.0');



-- --------------------------------------------------------

--
--  Tópicos Especiais em Informática		
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','7','1','2016','0','8.0','7.5','8.6');



-- --------------------------------------------------------

--
--  Trabalho de Graduação I
--

-- --------------------------------------------------------

INSERT INTO `historico`(
 `ra`,
 `coddisciplina`,
 `semestre`,
 `ano`,
 `faltastot`,
 `media`,
 `notas1`,
 `notas2`
) VALUES ('141b22','8','2','2016','1','7.5','7.5','7.5');




