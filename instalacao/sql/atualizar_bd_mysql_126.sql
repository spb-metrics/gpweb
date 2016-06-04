SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.39'; 
UPDATE versao SET ultima_atualizacao_bd='2012-10-24'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-10-24'; 
UPDATE versao SET versao_bd=126; 

ALTER TABLE pratica_modelo ADD COLUMN pratica_modelo_ordem INTEGER(100) UNSIGNED DEFAULT NULL;

UPDATE pratica_modelo SET pratica_modelo_nome='FNQ 2012 250 pontos', pratica_modelo_ordem=1 WHERE pratica_modelo_id=5;
UPDATE pratica_modelo SET pratica_modelo_nome='FNQ 2012 500 pontos', pratica_modelo_ordem=2 WHERE pratica_modelo_id=3;
UPDATE pratica_modelo SET pratica_modelo_nome='FNQ 2012 1000 pontos', pratica_modelo_ordem=3 WHERE pratica_modelo_id=8;
UPDATE pratica_modelo SET pratica_modelo_nome='PQGF 2012 250 pontos', pratica_modelo_ordem=4 WHERE pratica_modelo_id=6;
UPDATE pratica_modelo SET pratica_modelo_nome='PQGF 2012 500 pontos', pratica_modelo_ordem=5 WHERE pratica_modelo_id=4;
UPDATE pratica_modelo SET pratica_modelo_nome='PQGF 2012 1000 pontos', pratica_modelo_ordem=6 WHERE pratica_modelo_id=2;
UPDATE pratica_modelo SET pratica_modelo_nome='Ex�rcito 2012 500 pontos', pratica_modelo_ordem=7 WHERE pratica_modelo_id=7;

DELETE FROM pratica_modelo WHERE pratica_modelo_id=1;

DROP TABLE IF EXISTS pratica_verbo;

CREATE TABLE pratica_verbo (
  pratica_verbo_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_verbo_marcador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_verbo_texto TEXT,
  PRIMARY KEY (pratica_verbo_id),
  KEY pratica_verbo_marcador (pratica_verbo_marcador),
  CONSTRAINT pratica_verbo_fk FOREIGN KEY (pratica_verbo_marcador) REFERENCES pratica_marcador (pratica_marcador_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

DROP TABLE IF EXISTS pratica_nos_verbos;

CREATE TABLE pratica_nos_verbos (
  pratica INTEGER(100) UNSIGNED DEFAULT NULL,
  verbo INTEGER(100) UNSIGNED DEFAULT NULL,
  KEY pratica (pratica),
  KEY verbo (verbo),
  CONSTRAINT pratica_nos_verbos_fk1 FOREIGN KEY (pratica) REFERENCES praticas (pratica_id) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT pratica_nos_verbos_fk FOREIGN KEY (verbo) REFERENCES pratica_verbo (pratica_verbo_id) ON DELETE CASCADE ON UPDATE CASCADE
)ENGINE=InnoDB;

INSERT INTO pratica_verbo (pratica_verbo_marcador,pratica_verbo_texto) VALUES 

(99,'Tomar as principais decis�es, assegurando o envolvimento de todas as partes interessadas.'),
(99,'Tomar as principais decis�es, assegurando a transpar�ncia.'),
(99,'Tomar as principais decis�es, assegurando a governabilidade.'),

(99,'Comunicar as principais decis�es, assegurando o envolvimento de todas as partes interessadas.'),
(99,'Comunicar as principais decis�es, assegurando a transpar�ncia.'),
(99,'Comunicar as principais decis�es, assegurando a governabilidade.'),

(99,'Implementar as principais decis�es, assegurando o envolvimento de todas as partes interessadas.'),
(99,'Implementar as principais decis�es, assegurando a transpar�ncia.'),
(99,'Implementar as principais decis�es, assegurando a governabilidade.'),

(100,'Estabelecer os valores e os princ�pios organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),
(100,'Estabelecer os valores e os princ�pios organizacionais necess�rios ao desenvolvimento sustent�vel.'),

(100,'Atualizar os valores e os princ�pios organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),
(100,'Atualizar os valores e os princ�pios organizacionais necess�rios ao desenvolvimento sustent�vel.'),

(101,'Identificar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Identificar os riscos organizacionais mais significativos que possam afetar a capacidade da organiza��o de alcan�ar os seus objetivos estrat�gicos.'),
(101,'Identificar os riscos organizacionais mais significativos que possam afetar capacidade de realizar sua miss�o.'),

(101,'Classificar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Classificar os riscos organizacionais mais significativos que possam afetar a capacidade da organiza��o de alcan�ar os seus objetivos estrat�gicos.'),
(101,'Classificar os riscos organizacionais mais significativos que possam afetar capacidade de realizar sua miss�o.'),

(101,'Analisar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Analisar os riscos organizacionais mais significativos que possam afetar a capacidade da organiza��o de alcan�ar os seus objetivos estrat�gicos.'),
(101,'Analisar os riscos organizacionais mais significativos que possam afetar capacidade de realizar sua miss�o.'),

(101,'Tratar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Tratar os riscos organizacionais mais significativos que possam afetar a capacidade da organiza��o de alcan�ar os seus objetivos estrat�gicos.'),
(101,'Tratar os riscos organizacionais mais significativos que possam afetar a capacidade de realizar sua miss�o.'),

(102,'Prestar contas de seus atos a quem a elegeu ou designou.'),
(102,'Prestar contas de seus atos aos �rg�os de controle.'),

(102,'Prestar contas dos resultados a quem a elegeu ou designou.'),
(102,'Prestar contas dos resultados aos �rg�os de controle.'),

(103,'Disseminar os princ�pios da administra��o p�blica na organiza��o.'),
(103,'Disseminar os valores da administra��o p�blica na organiza��o.'),
(103,'Disseminar as diretrizes de governo na organiza��o.'),

(103,'Internalizar os princ�pios da administra��o p�blica na organiza��o.'),
(103,'Internalizar os valores da administra��o p�blica na organiza��o.'),
(103,'Internalizar as diretrizes de governo na organiza��o.'),

(104,'Estabelecer um exemplo a ser seguido.'),
(104,'Buscar novas oportunidades para a organiza��o.'),
(104,'Promover o comprometimento com todas as partes interessadas.'),

(105,'Disseminar os princ�pios organizacionais � for�a de trabalho e, quando pertinente, �s demais partes interessadas.'),
(105,'Internalizar os princ�pios organizacionais � for�a de trabalho e, quando pertinente, �s demais partes interessadas.'),

(106,'Incentivar o comprometimento de todos com a cultura da excel�ncia.'),

(107,'Avaliar os l�deres atuais e potenciais em rela��o �s compet�ncias desejadas pela organiza��o.'),
(107,'Desenvolver os l�deres atuais e potenciais em rela��o �s compet�ncias desejadas pela organiza��o.'),

(108,'Conduzir a implementa��o do sistema de gest�o da organiza��o, visando assegurar o atendimento aos requisitos de todas as partes interessadas.'),

(109,'Estimular o aprendizado na organiza��o'),

(110,'Analisar as necessidades de informa��es comparativas para avaliar o desempenho da organiza��o.'),

(111,'Analisar os desempenhos dos diversos n�veis da organiza��o, considerando as informa��es comparativas.'),
(111,'Analisar os desempenhos dos diversos n�veis da organiza��o, considerando o atendimento aos principais requisitos das partes interessadas.'),

(111,'Analisar o desempenho integrado de toda a organiza��o, considerando as informa��es comparativas.'),
(111,'Analisar o desempenho integrado de toda a organiza��o, considerando o atendimento aos principais requisitos das partes interessadas.'),

(112,'Avaliar o �xito das estrat�gias a partir das conclus�es da an�lise do seu desempenho.'),
(112,'Avaliar o alcance dos respectivos objetivos da organiza��o a partir das conclus�es da an�lise do seu desempenho.'),

(113,'Comunicar as decis�es decorrentes da an�lise do desempenho da organiza��o � for�a de trabalho, em todos os n�veis da organiza��o.'),
(113,'Comunicar as decis�es decorrentes da an�lise do desempenho da organiza��o a outras partes interessadas, quando pertinente.'),

(114,'Acompanhar a implementa��o das decis�es decorrentes da an�lise do desempenho da organiza��o.'),

(115,'Formular os processos das pol�ticas p�blicas, quando pertinente.'),

(116,'Formular os processos das estrat�gias da organiza��o.'),

(117,'Considerar os aspectos relativos ao ambiente externo no processo de formula��o das estrat�gias.'),

(118,'Realizar a an�lise do ambiente interno.'),

(119,'Avaliar as estrat�gias.'),
(119,'Selecionar as estrat�gias.'),

(120,'Envolver as �reas da organiza��o nos processos de formula��o de estrat�gias.'),
(120,'Envolver as partes interessadas, quando pertinente, nos processos de formula��o de estrat�gias.'),

(121,'Comunicar as estrat�gias �s partes interessadas pertinentes para o estabelecimento de compromissos m�tuos.'),

(122,'Definir os indicadores para a avalia��o da operacionaliza��o das estrat�gias, e definir os respectivos planos de a��o.'),
(122,'Estabelecer as metas de curto e longo prazos, e definir os respectivos planos de a��o.'),

(123,'Desdobrar as metas estabelecidas para as �reas da organiza��o, assegurando a coer�ncia entre os indicadores utilizados na avalia��o da implementa��o das estrat�gias e aqueles utilizados na avalia��o do desempenho dos processos.'),

(124,'Desdobrar os planos de a��o para as �reas da organiza��o, assegurando a coer�ncia com as estrat�gias selecionadas.'),
(124,'Desdobrar os planos de a��o para as �reas da organiza��o, assegurando a consist�ncia entre os respectivos planos.'),

(125,'Alocar os diferentes recursos para assegurar a implementa��o dos planos de a��o.'),

(126,'Comunicar as metas para a for�a de trabalho e, quando pertinente, para as demais partes interessadas.'),
(126,'Comunicar os indicadores para a for�a de trabalho e, quando pertinente, para as demais partes interessadas.'),
(126,'Comunicar os planos de a��o para a for�a de trabalho e, quando pertinente, para as demais partes interessadas.'),

(127,'Realizar o monitoramento da implementa��o dos planos de a��o.'),

(128,'Identificar os cidad�os-usu�rios da organiza��o'),
(128,'Classificar os cidad�os-usu�rios da organiza��o por tipos ou grupos.'),

(129,'Identificar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, e de ex-usu�rios, quando pertinente, para defini��o e melhoria dos produtos da organiza��o.'),
(129,'Identificar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, e de ex-usu�rios, quando pertinente, para defini��o e melhoria dos servi�os da organiza��o.'),
(129,'Identificar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, e de ex-usu�rios, quando pertinente, para defini��o e melhoria processos da organiza��o.'),

(130,'Divulgar os produtos e servi�os da organiza��o aos cidad�os de forma a criar credibilidade, confian�a e imagem positiva.'),
(130,'Divulgar os padr�es de atendimento da organiza��o aos cidad�os de forma a criar credibilidade, confian�a e imagem positiva.'),
(130,'Divulgar as a��es de melhoria da organiza��o aos cidad�os de forma a criar credibilidade, confian�a e imagem positiva.'),

(131,'Identificar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus servi�os.'),
(131,'Identificar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus produtos.'),
(131,'Identificar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e suas a��es.'),

(131,'Avaliar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus servi�os.'),
(131,'Avaliar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus produtos.'),
(131,'Avaliar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e suas a��es.'),

(132,'Avaliar a imagem da organiza��o perante os cidad�os-usu�rios.'),

(133,'Avaliar o atendimento ao universo potencial dos cidad�os-usu�rios identificados.'),

(134,'Definir aos cidad�os-usu�rios os principais canais de acesso para solicitarem informa��es ou esclarecimentos sobre os servi�os e produtos.'),
(134,'Definir aos cidad�os-usu�rios os principais canais de acesso para comunicarem suas sugest�es.'),
(134,'Definir aos cidad�os-usu�rios os principais canais de acesso para comunicarem suas reclama��es.'),

(135,'Tratar as reclama��es e sugest�es, formais e informais dos cidad�os-usu�rios, visando assegurar a resposta r�pida e eficaz e o seu aproveitamento por toda a organiza��o.'),

(136,'Acompanhar os servi�os, recentemente prestados, junto aos cidad�os-usu�rios para permitir � organiza��o gerar solu��es r�pidas e eficazes.'),
(136,'Acompanhar os servi�os, recentemente prestados, junto aos cidad�os-usu�rios para permitir � organiza��o evitar problemas de relacionamento.'),
(136,'Acompanhar os servi�os, recentemente prestados, junto aos cidad�os-usu�rios para permitir � organiza��o atender �s expectativas dos cidad�os-usu�rios.'),

(136,'Acompanhar os produtos, recentemente entregues, junto aos cidad�os-usu�rios para permitir � organiza��o gerar solu��es r�pidas e eficazes.'),
(136,'Acompanhar os produtos, recentemente entregues, junto aos cidad�os-usu�rios para permitir � organiza��o evitar problemas de relacionamento.'),
(136,'Acompanhar os produtos, recentemente entregues, junto aos cidad�os-usu�rios para permitir � organiza��o atender �s expectativas dos cidad�os-usu�rios.'),

(137,'Avaliar a satisfa��o e a insatisfa��o dos cidad�os-usu�rios em rela��o aos produtos da organiza��o e aos da concorr�ncia, quando pertinente.'),
(137,'Avaliar a satisfa��o e a insatisfa��o dos cidad�os-usu�rios em rela��o aos servi�os da organiza��o e aos da concorr�ncia, quando pertinente.'),

(138,'Utilizar as informa��es obtidas dos cidad�os-usu�rios para melhorar o seu n�vel de satisfa��o.'),

(139,'Identificar os aspectos e tratar os impactos sociais e ambientais dos produtos, desde o projeto at� a disposi��o final, sobre os quais tenha infu�ncia.'),
(139,'Identificar os aspectos e tratar os impactos sociais e ambientais dos servi�os, desde o projeto at� a disposi��o final, sobre os quais tenha infu�ncia.'),
(139,'Identificar os aspectos e tratar os impactos sociais e ambientais dos processos, desde o projeto at� a disposi��o final, sobre os quais tenha infu�ncia.'),
(139,'Identificar os aspectos e tratar os impactos sociais e ambientais das instala��es, desde o projeto at� a disposi��o final, sobre os quais tenha infu�ncia.'),

(140,'Comunicar os impactos sociais e ambientais dos servi�os, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),
(140,'Comunicar os impactos sociais e ambientais dos produtos, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),
(140,'Comunicar os impactos sociais e ambientais dos processos, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),
(140,'Comunicar os impactos sociais e ambientais das instala��es, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),

(141,'Tratar as pend�ncias ou eventuais san��es referentes aos requisitos legais, relatando as atualmente existentes.'),
(141,'Tratar as pend�ncias ou eventuais san��es referentes aos requisitos regulamentares, relatando as atualmente existentes.'),
(141,'Tratar as pend�ncias ou eventuais san��es referentes aos requisitos �ticos ou contratuais, relatando as atualmente existentes.'),

(142,'Promover as a��es que envolvam a conserva��o de recursos n�o-renov�veis a preserva��o dos ecossistemas.'),
(142,'Promover as a��es que envolvam a conserva��o de recursos n�o-renov�veis a otimiza��o do uso de recursos renov�veis.'),

(143,'Conscientizar a for�a de trabalho nas quest�es relativas � responsabilidade socioambiental.'),
(143,'Conscientizar os fornecedores nas quest�es relativas � responsabilidade socioambiental.'),
(143,'Conscientizar as demais partes interessadas nas quest�es relativas � responsabilidade socioambiental.'),

(143,'Envolver a for�a de trabalho nas quest�es relativas � responsabilidade socioambiental.'),
(143,'Envolver os fornecedores nas quest�es relativas � responsabilidade socioambiental.'),
(143,'Envolver as demais partes interessadas nas quest�es relativas � responsabilidade socioambiental.'),

(144,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos sociais, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento local, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(144,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos sociais, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade apoiaando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento local, quando pertinente.'),
(144,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(145,'Divulgar ofcialmente os atos e as informa��es sobre os planos da organiza��o.'),
(145,'Divulgar ofcialmente os atos e as informa��es sobre os programas da organiza��o.'),
(145,'Divulgar ofcialmente os atos e as informa��es sobre os projetos da organiza��o.'),

(146,'Tornar p�blicas as informa��es relativas � execu��o f�sica da organiza��o.'),
(146,'Tornar p�blicas as informa��es relativas � execu��o or�ament�ria da organiza��o.'),
(146,'Tornar p�blicas as informa��es relativas � execu��o financeira da organiza��o.'),
(146,'Tornar p�blicas as informa��es relativas � gest�o da organiza��o.'),

(146,'Democratizar o acesso as informa��es relativas � execu��o f�sica da organiza��o.'),
(146,'Democratizar o acesso as informa��es relativas � execu��o or�ament�ria da organiza��o.'),
(146,'Democratizar o acesso as informa��es relativas � execu��o financeira da organiza��o.'),
(146,'Democratizar o acesso as informa��es relativas � gest�o da organiza��o.'),

(147,'Estimular a sociedade a participar no controle dos resultados organizacionais.'),
(147,'Orientar a sociedade a participar no controle dos resultados organizacionais.'),

(148,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho, no cumprimento de seu papel de agente p�blico.'),
(148,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho, no comportamento �tico em todos os n�veis.'),

(149,'Disponibilizar os canais de comunica��o para receber eventuais den�ncias de viola��o da �tica.'),

(150,'Identificar as necessidades da sociedade em rela��o ao setor de atua��o'),

(150,'Identificar as necessidades da sociedade em rela��o ao setor de atua��o da organiza��o e transformar em requisitos para a formula��o das pol�ticas p�blicas, quando pertinente.'),
(150,'Identificar as necessidades da sociedade em rela��o ao setor de atua��o da organiza��o e transformar em requisitos para execu��o das pol�ticas p�blicas, quando pertinente.'),

(151,'Contribuir na formula��o das pol�ticas p�blicas do seu setor.'),
(151,'Atuar na execu��o das pol�ticas p�blicas do seu setor.'),

(152,'Divulgar as pol�ticas p�blicas e seus respectivos objetivos para a sociedade.'),

(153,'Monitorar a execu��o das pol�ticas p�blicas, em seu n�vel de atua��o.'),
(153,'Avaliar a execu��o das pol�ticas p�blicas, em seu n�vel de atua��o.'),

(154,'Avaliar a satisfa��o da sociedade e das demais partes interessadas com a implementa��o das pol�ticas p�blicas, em seu n�vel de atua��o.'),

(155,'Identificar as necessidades de coleta e tratamento, e guardar informa��es para apoiar a gest�o organizacional.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informa��es para apoiar as opera��es di�rias.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informa��es para apoiar as as estrat�gias.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informa��es para apoiar o progresso dos planos de a��o.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informa��es para subsidiar a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),

(156,'Definir os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(156,'Definir os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),

(156,'Desenvolver os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(156,'Desenvolver os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),

(156,'Implantar os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(156,'Implantar os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),

(156,'Atualizar os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(156,'Atualizar os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),

(157,'Estabelecer a mem�ria administrativa da organiza��o.'),
(157,'Manter a mem�ria administrativa da organiza��o.'),

(158,'Utilizar a gest�o da informa��o para apoiar o cumprimento da miss�o institucional e promover a integra��o da organiza��o com seus cidad�os-usu�rios.'),
(158,'Utilizar a gest�o da informa��o para apoiar o cumprimento da miss�o institucional e promover a integra��o da organiza��o com a sociedade.'),
(158,'Utilizar a gest�o da informa��o para apoiar o cumprimento da miss�o institucional e promover a integra��o da organiza��o com seus fornecedores.'),
(158,'Utilizar a gest�o da informa��o para apoiar o cumprimento da miss�o institucional e promover a integra��o da organiza��o com seus parceiros.'),

(159,'Colocar � disposi��o dos p�blicos internos e externos as informa��es necess�rias � organiza��o, incluindo cidad�os-usu�rios.'),
(159,'Colocar � disposi��o dos p�blicos internos e externos as informa��es necess�rias � organiza��o, incluindo fornecedores.'),
(159,'Colocar � disposi��o dos p�blicos internos e externos as informa��es necess�rias � organiza��o, incluindo parceiros.'),

(160,'Gerenciar a seguran�a das informa��es.'),

(161,'Identificat as organiza��es consideradas como um referencial comparativo pertinente.'),

(162,'Identificar as fontes, obtidas e manter atualizadas as informa��es comparativas.'),

(163,'Promover melhorias no desempenho.'),

(163,'Utilizar as informa��es obtidas para melhorar o conhecimento dos processos organizacionais.'),
(163,'Utilizar as informa��es obtidas para estabelecer metas ousadas.'),
(163,'Utilizar as informa��es obtidas para promover melhorias no desempenho da organiza��o.'),

(164,'Desenvolver o conhecimento na organiza��o.'),
(164,'Compartilhar o conhecimento na organiza��o.'),

(165,'Manter o conhecimento.'),
(165,'Proteger o conhecimento.'),

(166,'Assegurar que a gest�o do conhecimento seja utilizada para melhorar os processos da organiza��o.'),

(166,'Assegurar que a gest�o do conhecimento seja utilizada para melhorar produtos da organiza��o.'),

(166,'Assegurar que a gest�o do conhecimento seja utilizada para melhorar servi�os da organiza��o.'),

(167,'Identificar os ativos intang�veis da organiza��o.'),
(167,'Desenvolver os ativos intang�veis da organiza��o.'),
(167,'Mensurar os ativos intang�veis da organiza��o.'),

(168,'Definir a organiza��o do trabalho, visando o alto desempenho da organiza��o.'),
(168,'Implementar a organiza��o do trabalho, visando o alto desempenho da organiza��o.'),

(169,'Selecionar as pessoas para preenchimento de cargos e fun��es, em conson�ncia com as estrat�gias da organiza��o.'),
(169,'Selecionar as pessoas para preenchimento de cargos e fun��es, em conson�ncia com os objetivos da organiza��o.'),
(169,'Selecionar as pessoas para preenchimento de cargos e fun��es, em conson�ncia com a miss�o da organiza��o.'),

(170,'Definir os canais de interlocu��o com a for�a de trabalho da organiza��o, quando pertinente.'),
(170,'Definir a negocia��o com a for�a de trabalho da organiza��o, quando pertinente.'),
(170,'Disponibilizar os canais de interlocu��o com a for�a de trabalho da organiza��o, quando pertinente.'),
(170,'Disponibilizar a negocia��o com a for�a de trabalho da organiza��o, quando pertinente.'),

(171,'Estimular a integra��o das pessoas e das equipes.'),
(171,'Estimular a coopera��o das pessoas e das equipes.'),

(172,'Gerenciar o desempenho das pessoas, de forma a estimular a obten��o de metas de alto desempenho.'),
(172,'Gerenciar o desempenho das pessoas, de forma a estimular a cultura da excel�ncia na organiza��o.'),
(172,'Gerenciar o desempenho das pessoas, de forma a estimular o desenvolvimento profssional.'),

(172,'Gerenciar o desempenho das equipes, de forma a estimular a obten��o de metas de alto desempenho.'),
(172,'Gerenciar o desempenho das equipes, de forma a estimular a cultura da excel�ncia na organiza��o.'),
(172,'Gerenciar o desempenho das equipes, de forma a estimular o desenvolvimento profssional.'),

(173,'Estimular o alcance de metas de alto desempenho atrav�s do sistema de remunera��o para as pessoas.'),
(173,'Estimular o aprendizado atrav�s do sistema de remunera��o para as pessoas.'),
(173,'Estimular a cultura da excel�ncia atrav�s do sistema de remunera��o para as pessoas.'),

(173,'Estimular o alcance de metas de alto desempenho atrav�s do sistema de reconhecimento para as pessoas.'),
(173,'Estimular o aprendizado atrav�s do sistema de reconhecimento para as pessoas.'),
(173,'Estimular a cultura da excel�ncia atrav�s do sistema de reconhecimento para as pessoas.'),

(173,'Estimular o alcance de metas de alto desempenho atrav�s do sistema de incentivos para as pessoas.'),
(173,'Estimular o aprendizado atrav�s do sistema de incentivos para as pessoas.'),
(173,'Estimular a cultura da excel�ncia atrav�s do sistema de incentivos para as pessoas.'),

(174,'Identificar as necessidades de capacita��o'),
(174,'Identificar as necessidades de desenvolvimento'),

(175,'Compatibilizar as necessidades de capacita��o das pessoas com as necessidades da organiza��o, para efeito da defini��o dos programas de capacita��o.'),
(175,'Compatibilizar as necessidades de capacita��o das pessoas com as necessidades da organiza��o, para efeito da defini��o dos programas de desenvolvimento.'),

(175,'Compatibilizar as necessidades de desenvolvimento das pessoas com as necessidades da organiza��o, para efeito da defini��o dos programas de capacita��o.'),
(175,'Compatibilizar as necessidades de desenvolvimento das pessoas com as necessidades da organiza��o, para efeito da defini��o dos programas de desenvolvimento.'),

(176,'Abordar a cultura da excel�ncia atrav�s dos programas de capacita��o.'), 
(176,'Contribuir para consolidar o aprendizado organizacional atrav�s dos programas de capacita��o.'), 

(177,'Conceber a forma de realiza��o dos programas de capacita��o considerando as necessidades da organiza��o.'),
(177,'Conceber a forma de realiza��o dos programas de capacita��o considerando as necessidades das pessoas.'),
(177,'Conceber a forma de realiza��o dos programas de capacita��o considerando os recursos dispon�veis.'),

(177,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando as necessidades da organiza��o.'),
(177,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando as necessidades das pessoas..'),
(177,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando os recursos dispon�veis.'),


(178,'Avaliar as habilidades em rela��o � sua utilidade na execu��o do trabalho da organiza��o.'),
(178,'Avaliar os conhecimentos adquiridos em rela��o � sua utilidade na execu��o do trabalho da organiza��o.'),

(178,'Avaliar as habilidades em rela��o � sua efc�cia na consecu��o das estrat�gias da organiza��o.'),
(178,'Avaliar os conhecimentos adquiridos em rela��o � sua efc�cia na consecu��o das estrat�gias da organiza��o.'),

(179,'Promover o desenvolvimento integral das pessoas, como indiv�duos, cidad�os e profssionais.'),

(180,'Identificar os perigos relacionados � sa�de ocupacional.'),
(180,'Identificar os perigos relacionados � seguran�a.'),
(180,'Identificar os perigos relacionados � ergonomia.'),

(180,'Tratar os riscos relacionados � sa�de ocupacional.'),
(180,'Tratar os riscos relacionados � seguran�a.'),
(180,'Tratar os riscos relacionados � ergonomia.'),

(181,'Identificar os fatores que afetam o bem-estar considerando os diferentes grupos de pessoas.'),
(181,'Identificar os fatores que afetam a satisfa��o considerando os diferentes grupos de pessoas.'),
(181,'Identificar os fatores que afetam a motiva��o considerando os diferentes grupos de pessoas.'),

(182,'Tratar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(182,'Tratar os fatores que afetam a satisfa��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(182,'Tratar os fatores que afetam a motiva��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),

(183,'Colaborar para a melhoria da qualidade de vida das pessoas e respectivas fam�lias fora do ambiente de trabalho.'),

(184,'Avaliar os fatores que afetam o bem-estar.'),
(184,'Avaliar os fatores que afetam a satisfa��o.'),
(184,'Avaliar os fatores que afetam a motiva��o.'),

(185,'Identificar os processos de apoio, considerando a miss�o institucional da organiza��o.'),
(185,'Determinar os processos de apoio, considerando a miss�o institucional da organiza��o.'),

(185,'Identificar os processos final�sticos, considerando a miss�o institucional da organiza��o.'),
(185,'Determinar os processos final�sticos, considerando a miss�o institucional da organiza��o.'),

(186,'Traduzir as necessidades dos cidad�os-usu�rios em requisitos aos projetos de servi�os ou produtos e aos processos final�sticos.'),
(186,'Traduzir as necessidades da sociedade em requisitos aos projetos de servi�os ou produtos e aos processos final�sticos.'),

(186,'Incorporar as necessidades dos cidad�os-usu�rios em requisitos aos projetos de servi�os ou produtos e aos processos final�sticos.'),
(186,'Incorporar as necessidades da sociedade em requisitos e incorporar aos projetos de servi�os ou produtos e aos processos final�sticos.'),

(187,'Projetar os processos final�sticos visando o cumprimento dos requisitos definidos.'),
(187,'Projetar os processos de apoio visando o cumprimento dos requisitos definidos.'),

(188,'Controlar os processos final�sticos.'),
(188,'Controlar os processos de apoio.'),

(189,'Refinar os processos final�sticos.'),
(189,'Refinar os processos de apoio.'),

(190,'Identificar potenciais fornecedores visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(190,'Identificar potenciais fornecedores visando melhorar o desempenho.'),
(190,'Identificar potenciais fornecedores visando assegurar o desenvolvimento sustent�vel de sua cadeia de suprimentos.'),

(190,'Desenvolver sua cadeia de suprimentos visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(190,'Desenvolver sua cadeia de suprimentos visando melhorar o seu desempenho.'),
(190,'Desenvolver sua cadeia de suprimentos visando assegurar o desenvolvimento sustent�vel de sua cadeia de suprimentos.'),

(191,'Realizar o processo de aquisi��o de bens de forma a assegurar a transpar�ncia.'),
(191,'Realizar o processo de aquisi��o de materiais de forma a assegurar a transpar�ncia.'),
(191,'Realizar o processo de aquisi��o de servi�os de forma a assegurar a transpar�ncia.'),

(191,'Realizar o processo de aquisi��o de bens de forma a atender a legisla��o.'),
(191,'Realizar o processo de aquisi��o de materiais de forma a atender a legisla��o.'),
(191,'Realizar o processo de aquisi��o de servi�os de forma a atender a legisla��o.'),

(192,'Assegurar a qualidade dos bens adquiridos.'),
(192,'Assegurar a qualidade dos produtos adquiridos.'),
(192,'Assegurar a qualidade dos servi�os adquiridos.'),

(193,'Realizar a gest�o dos bens materiais quando for pertinente.'),
(193,'Realizar a gest�o dos bens patrimoniais quando for pertinente.'),
(193,'Realizar a gest�o dos estoques, quando for pertinente.'),

(194,'Administrar o relacionamento com os fornecedores.'),

(195,'Avaliar os fornecedores e prontamente informar sobre seu desempenho.'),

(196,'Minimizar os custos associados � gest�o do fornecimento.'),

(197,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios organizacionais relativos � responsabilidade socioambiental, incluindo os aspectos da seguran�a e sa�de.'),
(197,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios organizacionais relativos � responsabilidade socioambiental, incluindo os aspectos da seguran�a e sa�de.'),

(198,'Elaborar a proposta or�ament�ria mais significativa que possa vir a afetar a execu��o de suas atividades.'),
(198,'Tratar as restri��es e libera��es de or�amento mais significativas que possam vir a afetar a execu��o de suas atividades.'),

(199,'Gerenciar os processos or�ament�rios para suportar as necessidades estrat�gicas da organiza��o.'),
(199,'Gerenciar os processos or�ament�rios para suportar as necessidades operacionais da organiza��o.'),

(199,'Gerenciar os processos financeiros para suportar as necessidades operacionais da organiza��o.'),
(199,'Gerenciar os processos financeiros para suportar as necessidades estrat�gicas da organiza��o.'),

(200,'Monitorar a execu��o or�ament�ria e os poss�veis realinhamentos entre o or�amento, estrat�gias e objetivos da organiza��o.'),
(200,'Monitorar a execu��o financeira e os poss�veis realinhamentos entre o or�amento, estrat�gias e objetivos da organiza��o.'),

(201,'Selecionar as melhores op��es de investimentos e aplica��es de recursos financeiros, quando pertinente.'),
(201,'Realizar capta��es de recursos financeiros, quando pertinente.'),

(202,'Acompanhar as opera��es que geram receita.'),

(203,'Administrar os  par�metros or�ament�rios.'),
(203,'Administrar os  par�metros financeiros.'),

(204,'Apresentar os resultados dos principais indicadores relativos aos cidad�os-usu�rios. Estratificar por grupos de cidad�os-usu�rios, segmentos de mercado ou tipos de produtos, quando aplic�vel.'),

(205,'Apresentar os resultados dos principais indicadores relativos � sociedade, incluindo os relativos � atua��o socioambiental, � �tica, ao controle social e �s pol�ticas p�blicas. Estratificar os resultados por instala��es, quando aplic�vel.'),

(206,'Apresentar os resultados dos principais indicadores relativos � gest�o or�ament�ria e financeira. Estratificar os resultados por unidades ou fliais, quando aplic�vel.'),

(207,'Apresentar os resultados dos principais indicadores relativos �s pessoas, incluindo os relativos aos sistemas de trabalho, � capacita��o e ao desenvolvimento e � qualidade de vida. Estratificar os resultados por grupos de pessoas da for�a de trabalho, fun��es na organiza��o e, quando aplic�vel, por instala��es.'),

(208,'Apresentar os resultados dos principais indicadores relativos aos produtos adquiridos e � gest�o de relacionamento com os fornecedores. estratificar os resultados por grupos de fornecedores ou tipos de produtos adquiridos, quando aplic�vel.'),

(209,'Apresentar os resultados dos indicadores relativos ao produto/servi�o e � gest�o dos processos final�sticos e de apoio.'),

(210,'Identificar os riscos empresariais mais significativos que possam afetar o neg�cio.'),

(211,'Revisar os valores necess�rios � promo��o da excel�ncia.'),
(211,'Revisar os valores necess�rios � cria��o de valor para todas as partes interessadas.'),

(211,'Revisar os princ�pios organizacionais necess�rios � promo��o da excel�ncia.'),
(211,'Revisar os princ�pios organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),

(212,'Tratar as quest�es �ticas nos relacionamentos internos da organiza��o.'),
(212,'Tratar as quest�es �ticas nos relacionamentos externos da organiza��o.'),

(213,'Tomar as principais decis�es.'),
(213,'Comunicar as principais decis�es.'),
(213,'Implementar as principais decis�es.'),

(214,'Prestar conta das suas a��es a quem a elegeu, nomeou ou designou.'),
(214,'Prestar conta dos resultados alcan�ados a quem a elegeu, nomeou ou designou.'),

(215,'Exercer a lideran�a com as partes interessadas buscando a mobiliza��o de todos para o �xito das estrat�gias.'),
(215,'Demonstrar comprometimento com os valores organizacionais estabelecidos, buscando a mobiliza��o de todos para o �xito das estrat�gias.'),
(215,'Demonstrar comprometimento com os princ�pios organizacionais estabelecidos, buscando a mobiliza��o de todos para o �xito das estrat�gias.'),

(216,'Comunicar os valores organizacionais para for�a de trabalho e, quando pertinentes, �s demais partes interessadas.'),
(216,'Comunicar os princ�pios organizacionais para for�a de trabalho e, quando pertinentes, �s demais partes interessadas.'),

(217,'Identificar  as pessoas com potencial de lideran�a para o exerc�cio da lideran�a.'),
(217,'Preparar as pessoas com potencial de lideran�a para o exerc�cio da lideran�a.'),

(218,'Avaliar os l�deres atuais em rela��o �s compet�ncias desejadas pela organiza��o.'),
(218,'Desenvolver os l�deres atuais em rela��o �s compet�ncias desejadas pela organiza��o.'),

(219,'Estabelecer os principais padr�es de trabalho que orientam a execu��o adequada das pr�ticas de gest�o.'),

(220,'Verificar o cumprimento dos principais padr�es de trabalho, promovendo o controle.'),

(221,'Avaliar as pr�ticas de gest�o, promovendo o aprendizado.'),
(221,'Melhorar as pr�ticas de gest�o, promovendo o aprendizado.'),

(221,'Avaliar os respectivos padr�es de trabalho, promovendo o aprendizado.'),
(221,'Melhorar os respectivos padr�es de trabalho, promovendo o aprendizado.'),

(222,'Identificar  as necessidades de informa��es comparativas para analisar o desempenho operacional da organiza��o.'),
(222,'Identificar  as necessidades de informa��es comparativas para analisar o desempenho estrat�gico da organiza��o.'),

(223,'Analisar o desempenho operacional considerando as informa��es comparativas.'),
(223,'Analisar o desempenho operacional considerando o atendimento aos principais requisitos das partes interessadas.'),
(223,'Analisar o desempenho operacional considerando as vari�veis dos ambientes interno e externo.'),

(223,'Analisar o desempenho estrat�gico considerando as informa��es comparativas.'),
(223,'Analisar o desempenho estrat�gico considerando o atendimento aos principais requisitos das partes interessadas.'),
(223,'Analisar o desempenho estrat�gico considerando as vari�veis dos ambientes interno e externo.'),

(224,'Comunicar as decis�es decorrentes da an�lise do desempenho da organiza��o � for�a de trabalho, em todos os n�veis da organiza��o e a outras partes interessadas, quando pertinentes.'),

(225,'Acompanhar a implementa��o das decis�es decorrentes da an�lise do desempenho da organiza��o.'),

(226,'Realizar a an�lise do ambiente externo.'),

(227,'Realizar a an�lise do ambiente interno.'),

(228,'Definir as estrat�gias.'),

(229,'Envolver as diversas �reas da organiza��o nos processos de formula��o das estrat�gias.'),

(230,'Definir os indicadores para a avalia��o da implementa��o das estrat�gias e dos respectivos planos de a��o.'),
(230,'Estabelecer as metas de curto e longo prazos e dos respectivos planos de a��o.'),

(231,'Alocar os recursos para assegurar a implementa��o dos planos de a��o.'),

(232,'Comunicar as estrat�gias �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(232,'Comunicar as metas �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(232,'Comunicar os planos de a��o �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),

(233,'Realizar o monitoramento da implementa��o dos planos de a��o.'),

(234,'Segmentar o mercado e definir os clientes-alvo nesses segmentos.'),

(235,'Identificar as necessidades dos clientes-alvo.'),
(235,'Tratar as necessidades dos clientes-alvo.'),

(235,'Identificar as expectativas dos clientes-alvo.'),
(235,'Tratar as expectativas dos clientes-alvo.'),

(236,'Divulgar os produtos da organiza��o aos clientes e ao mercado de forma a criar credibilidade.'),
(236,'Divulgar os produtos da organiza��o aos clientes e ao mercado de forma a criar confian�a.'),
(236,'Divulgar os produtos da organiza��o aos clientes e ao mercado de forma a criar imagem positiva.'),

(236,'Divulgar as marcas da organiza��o aos clientes e ao mercado de forma a criar credibilidade.'),
(236,'Divulgar as marcas da organiza��o aos clientes e ao mercado de forma a criar confian�a.'),
(236,'Divulgar as marcas da organiza��o aos clientes e ao mercado de forma a criar imagem positiva.'),


(237,'Avaliar a imagem da organiza��o perante os clientes.'),

(238,'Definir os canais de relacionamento, considerando eventuais diferen�as nos perfis dos clientes.'),
(238,'Divulgar os canais de relacionamento, considerando eventuais diferen�as nos perfis dos clientes.'),

(239,'Tratar as solicita��es, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(239,'Tratar as reclama��es, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(239,'Tratar as sugest�es, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),

(240,'Realizar acompanhamento das transa��es com novos clientes.'),
(240,'Realizar acompanhamento das transa��es com novos produtos entregues.'),

(241,'Avaliar a satisfa��o dos clientes e utilizar essas informa��es para promover a��es de melhoria.'),
(241,'Avaliar a insatisfa��o dos clientes e utilizar essas informa��es para promover a��es de melhoria.'),

(242,'Identificar os aspectos e tratar os impactos sociais adversos de seus produtos.'),
(242,'Identificar os aspectos e tratar os impactos sociais adversos de seus processos'),
(242,'Identificar os aspectos e tratar os impactos sociais adversos de suas instala��es.'),

(242,'Identificar os aspectos e tratar os impactos ambientais adversos de seus produtos.'),
(242,'Identificar os aspectos e tratar os impactos ambientais adversos de seus processos'),
(242,'Identificar os aspectos e tratar os impactos ambientais adversos de suas instala��es.'),

(243,'Comunicar � sociedade, incluindo as comunidades vizinhas, os impactos sociais e ambientais dos produtos da organiza��o.'),
(243,'Comunicar � sociedade, incluindo as comunidades vizinhas, os impactos sociais e ambientais dos processos organiza��o.'),
(243,'Comunicar � sociedade, incluindo as comunidades vizinhas, informa��es relativas � responsabilidade socio ambiental consideradas relevantes.'),

(244,'Identificar  os requisitos legais, aplic�veis a quest�es socioambientais.'),
(244,'Identificar  os requisitos regulamentares aplic�veis a quest�es socioambientais.'),
(244,'Identificar  os requisitos contratuais, aplic�veis a quest�es socioambientais.'),

(244,'Analisar os requisitos legais, aplic�veis a quest�es socioambientais.'),
(244,'Analisar os requisitos regulamentares aplic�veis a quest�es socioambientais.'),
(244,'Analisar os requisitos contratuais, aplic�veis a quest�es socioambientais.'),

(245,'Selecionar voluntariamente a��es com vistas � preserva��o do ecossistemas.'),
(245,'Promover voluntariamente a��es com vistas � preserva��o do ecossistemas.'),

(246,'Conscientizar as pessoas da for�a de trabalho das quest�es relativas � responsabilidade socioambiental.'),
(246,'Envolver as pessoas da for�a de trabalho das quest�es relativas � responsabilidade socioambiental.'),

(247,'Identificar  as necessidades da sociedade, incluindo as comunidades vizinhas em rela��o �s instala��es da organiza��o.'),
(247,'Identificar  as expectativas da sociedade, incluindo as comunidades vizinhas em rela��o �s instala��es da organiza��o.'),

(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento nacional.'),
(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento regional.'),
(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento local.'),
(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento setorial.'),

(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento nacional.'),
(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento regional.'),
(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento local.'),
(248,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento setorial.'),

(249,'Estimular a for�a de trabalho na implementa��o dos seus projetos sociais.'),
(249,'Estimular seus parceiros na implementa��o dos seus projetos sociais.'),

(249,'Estimular a for�a de trabalho no apoio aos seus projetos sociais.'),
(249,'Estimular seus parceiros no apoio aos seus projetos sociais.'),

(249,'Envolver a for�a de trabalho na implementa��o dos seus projetos sociais.'),
(249,'Envolver seus parceiros na implementa��o dos seus projetos sociais.'),

(249,'Envolver a for�a de trabalho no apoio aos seus projetos sociais.'),
(249,'Envolver seus parceiros no apoio aos seus projetos sociais.'),

(250,'Identificar as necessidades de informa��es para apoiar as opera��es di�rias.'),
(250,'Identificar as necessidades de informa��es para apoiar a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),

(250,'Definir os sistemas de informa��o para apoiar as opera��es di�rias.'),
(250,'Definir os sistemas de informa��o para apoiar a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),

(251,'Colocar as informa��es necess�rias � disposi��o dos usu�rios.'),

(252,'Gerenciar a seguran�a das informa��es.'),

(253,'Identificar  as fontes de informa��es comparativas.'),

(254,'Obter as informa��es comparativas.'),
(254,'Manter atualizadas as informa��es comparativas.'),

(255,'Analisar as informa��es comparativas obtidas, visando sua adapta��o � realidade da organiza��o.'),

(256,'Identificar  os ativos intang�veis da organiza��o.'),

(257,'Desenvolver os principais ativos intang�veis da organiza��o.'),
(257,'Proteger os principais ativos intang�veis da organiza��o.'),

(258,'Compartilhar o conhecimento da organiza��o.'),
(258,'Reter o conhecimento da organiza��o.'),

(259,'Definir a organiza��o do trabalho.'),
(259,'Implementar a organiza��o do trabalho.'),

(260,'Selecionar e contratar, internamente, pessoas para a for�a de trabalho.'),
(260,'Selecionar e contratar, externamente, pessoas para a for�a de trabalho.'),

(261,'Realizar a integra��o dos novos membros da for�a de trabalho, visando prepar�-los para a execu��o das suas fun��es.'),

(262,'Avaliar o desempenho das pessoas da for�a de trabalho.'),

(263,'Estimular a busca por melhores resultados, atrav�s da remunera��o.'),
(263,'Estimular a busca por melhores resultados, atrav�s do reconhecimento.'),
(263,'Estimular a busca por melhores resultados, atrav�s dos incentivos.'),

(264,'Identificar as necessidades de desenvolvimento, considerando as estrat�gias da organiza��o.'),
(264,'Identificar as necessidades de desenvolvimento, considerando as necessidades das pessoas.'),

(265,'Conceber a forma de realiza��o dos programas de desenvolvimento, considerando as necessidades identificadas.'),
(265,'Conceber a forma de realiza��o dos programas de capacita��o, considerando as necessidades identificadas.'),

(266,'Avaliar a efic�cia dos programas de desenvolvimento.'),
(266,'Avaliar a efic�cia dos programas de capacita��o.'),

(267,'Identificar os perigos relacionados � sa�de ocupacional.'),
(267,'Identificar os perigos relacionados � seguran�a.'),
(267,'Identificar os perigos relacionados � ergonomia.'),

(267,'Tratar os riscos relacionados � sa�de ocupacional.'),
(267,'Tratar os riscos relacionados � seguran�a.'),
(267,'Tratar os riscos relacionados � ergonomia.'),

(268,'Identificar  os fatores que afetam o bem-estar das pessoas.'),
(268,'Identificar  os fatores que afetam a satisfa��o das pessoas.'),
(268,'Identificar  os fatores que afetam o comprometimento das pessoas.'),

(269,'Tratar os fatores que afetam o bem-estar das pessoas.'),
(269,'Tratar os fatores que afetam a satisfa��o das pessoas.'),
(269,'Tratar os fatores que afetam o comprometimento das pessoas.'),

(270,'Colaborar para a melhoria da qualidade de vida da sua for�a de trabalho fora do ambiente da organiza��o.'),

(271,'Avaliar a satisfa��o das pessoas.'),

(272,'Determinar os requisitos aplic�veis aos processos principais do neg�cio, a partir das necessidades dos clientes.'),
(272,'Determinar os requisitos aplic�veis aos processos principais do neg�cio, a partir das necessidades das demais partes interessadas.'),

(272,'Determinar os requisitos aplic�veis aos processos principais do neg�cio, a partir das expectativas dos clientes.'),
(272,'Determinar os requisitos aplic�veis aos processos principais do neg�cio, a partir das expectativas das demais partes interessadas.'),

(273,'Projetar os processos principais do neg�cio, visando ao cumprimento dos requisitos estabelecidos.'),
(273,'Projetar os processos de apoio do neg�cio, visando ao cumprimento dos requisitos estabelecidos.'),

(274,'Controlar os processos principais do neg�cio, visando assegurar o atendimento dos requisitos aplic�veis.'),
(274,'Controlar os processos de apoio do neg�cio, visando assegurar o atendimento dos requisitos aplic�veis.'),

(275,'Analisar os processos principais do neg�cio.'),
(275,'Melhorar os processos principais do neg�cio.'),

(276,'Qualificar os fornecedores, considerando requisitos de desempenho.'),
(276,'Selecionar os fornecedores, considerando requisitos de desempenho.'),

(277,'Avaliar os fornecedores e seu desempenho.'),
(277,'Informar prontamente os fornecedores sobre seu desempenho.'),

(278,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os valores organizacionais, incluindo os aspectos relativos � seguran�a.'),
(278,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os valores organizacionais, incluindo os aspectos relativos � sa�de.'),

(278,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios organizacionais, incluindo os aspectos relativos � seguran�a.'),
(278,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios organizacionais, incluindo os aspectos relativos � sa�de.'),

(278,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o com os valores organizacionais, incluindo os aspectos relativos � seguran�a.'),
(278,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o com os valores organizacionais, incluindo os aspectos relativos � sa�de.'),

(278,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios organizacionais, incluindo os aspectos relativos � seguran�a.'),
(278,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios organizacionais, incluindo os aspectos relativos � sa�de.'),

(279,'Gerenciar os aspectos que causam impacto na sustentabilidade econ�mica do neg�cio.'),

(280,'Assegurar os recursos financeiros necess�rios para atender �s necessidades operacionais.'),
(280,'Assegurar os recursos financeiros necess�rios para manter o fluxo financeiro equilibrado.'),

(281,'Definir os recursos financeiros, visando suportar as estrat�gias.'),
(281,'Definir os recursos financeiros, visando suportar os planos de a��o.'),

(281,'Avaliar os investimentos necess�rios, visando suportar as estrat�gias.'),
(281,'Avaliar os investimentos necess�rios, visando suportar os planos de a��o.'),

(282,'Elaborar o or�amento da organiza��o.'),
(282,'Controlar o or�amento organiza��o.'),

(283,'Apresentar os resultados relativos � gest�o econ�mico-financeira.'),

(284,'Apresentar os resultados relativos aos clientes.'),
(284,'Apresentar os resultados relativos ao mercado.'),

(285,'Apresentar os resultados relativos � responsabilidade socioambiental.'),
(285,'Apresentar os resultados relativos � �tica.'),
(285,'Apresentar os resultados relativos ao desenvolvimento social.'),

(286,'Apresentar os resultados relativos ao sistema de trabalho.'),
(286,'Apresentar os resultados relativos � capacita��o e desenvolvimento.'),
(286,'Apresentar os resultados relativos � qualidade de vida.'),

(287,'Apresentar os resultados relativos aos produtos.'),
(287,'Apresentar os resultados relativos � gest�o dos processos principais do neg�cio.'),
(287,'Apresentar os resultados relativos aos processos de apoio do neg�cio.'),

(288,'Apresentar os resultados dos principais indicadores relativos aos produtos adquiridos.'),
(288,'Apresentar os resultados dos principais indicadores relativos � gest�o de relacionamento com os fornecedores.'),

(289,'Disseminar os princ�pios da administra��o p�blica na organiza��o.'),
(289,'Disseminar os valores da administra��o p�blica na organiza��o.'),
(289,'Disseminar as diretrizes de governo na organiza��o.'),

(289,'Internalizar os princ�pios da administra��o p�blica na organiza��o.'),
(289,'Internalizar os valores da administra��o p�blica na organiza��o.'),
(289,'Internalizar as diretrizes de governo na organiza��o.'),

(290,'Tomar as principais decis�es da Alta Administra��o assegurando a transpar�ncia.'),
(290,'comunicar as principais decis�es da Alta Administra��o assegurando a transpar�ncia.'),
(290,'Implementar as principais decis�es da Alta Administra��o assegurando a transpar�ncia.'),

(290,'Tomar as principais decis�es pela da Alta Administra��o assegurando o envolvimento de todas as partes interessadas.'),
(290,'Comunicar as principais decis�es da Alta Administra��o assegurando o envolvimento de todas as partes interessadas.'),
(290,'Implementar as principais decis�es da Alta Administra��o assegurando o envolvimento de todas as partes interessadas.'),

(291,'Identificar os riscos organizacionais mais significativos que possam afetar o desempenho.'),
(291,'Tratar os riscos organizacionais mais significativos que possam afetar o desempenho.'),

(292,'Prestar conta de seus atos para os �rg�os de controle.'),
(292,'Prestar conta dos resultados alcan�ados para os �rg�os de controle.'),

(292,'Prestar conta de seus atos para a sociedade.'),
(292,'Prestar conta dos resultados alcan�ados para a sociedade.'),

(293,'Estabelecer um exemplo a ser seguido.'),
(293,'Atuar pessoalmente para buscar novas oportunidades para a organiza��o.'),
(293,'Atuar pessoalmente para promover o comprometimento com todas as partes interessadas.'),

(294,'Estabelecer os princ�pios organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),
(294,'Estabelecer os valores organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),

(294,'Atualizar os valores organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),
(294,'Atualizar os princ�pios organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),

(295,'Incentivar o comprometimento de todos com a cultura da excel�ncia.'),

(296,'Definir as habilidades de lideran�a nos l�deres.'),
(296,'Identificar as habilidades de lideran�a nos l�deres.'),
(296,'Desenvolver as habilidades de lideran�a nos l�deres.'),

(297,'Estabelecer os principais padr�es de trabalho que orientam a execu��o adequada das pr�ticas de gest�o da organiza��o.'),
(297,'Estabelecer os principais padr�es de trabalho que orientam os m�todos para verificar o seu cumprimento na organiza��o.'),

(298,'Estimular o aprendizado organizacional.'),

(299,'Analisar as necessidades de informa��es comparativas para avaliar o desempenho da organiza��o.'),

(300,'Analisar o desempenho estrat�gico, considerando as informa��es comparativas do ambiente interno e externo.'),
(300,'Analisar o desempenho operacional, considerando as informa��es comparativas do ambiente interno e externo.'),

(301,'Avaliar o �xito das estrat�gias e o alcance dos respectivos objetivos da organiza��o a partir das conclus�es da an�lise do seu desempenho.'),

(302,'Comunicar as decis�es decorrentes da an�lise do desempenho da organiza��o � for�a de trabalho, quando pertinente.'),
(302,'Comunicar as decis�es decorrentes da an�lise do desempenho da organiza��o a outras partes interessadas, quando pertinente.'),

(303,'Acompanhar a implementa��o das decis�es decorrentes da an�lise do desempenho da organiza��o.'),

(304,'Formular as pol�ticas p�blicas, quando pertinente.'),

(305,'Formular as estrat�gias da organiza��o.'),

(306,'Considerar os aspectos relativos ao ambiente externo no processo de formula��o das estrat�gias.'),

(307,'Analisar o ambiente interno da organiza��o.'),

(308,'Avaliar as estrat�gias da organiza��o.'),

(308,'Selecionar as estrat�gias da organiza��o.'),

(309,'Envolver as �reas da organiza��o no processo de formula��o das estrat�gias.'),

(310,'Comunicar as estrat�gias �s partes interessadas pertinentes para o estabelecimento de compromissos m�tuos.'),

(311,'Definir os indicadores para a avalia��o da operacionaliza��o das estrat�gias e definir os respectivos planos de a��o.'),
(311,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de a��o.'),
 
(312,'Desdobrar as metas para as �reas da organiza��o, assegurando a coer�ncia com as estrat�gias selecionadas.'),
(312,'Desdobrar os planos de a��o para as �reas da organiza��o, assegurando a coer�ncia com as estrat�gias selecionadas.'),

(312,'Desdobrar as metas para as �reas da organiza��o, assegurando a consist�ncia entre os respectivos planos.'),
(312,'Desdobrar os planos de a��o para as �reas da organiza��o, assegurando consist�ncia entre os respectivos planos.'),

(313,'Alocar os diferentes recursos financeiros e n�o-financeiros para assegurar a implementa��o dos planos de a��o.'),

(314,'Comunicar as metas para a for�a de trabalho e, quando pertinente, para as demais partes interessadas.'),
(314,'Comunicar os indicadores para a for�a de trabalho e, quando pertinente, para as demais partes interessadas.'),
(314,'Comunicar os planos de a��o para a for�a de trabalho e, quando pertinente, para as demais partes interessadas.'),

(315,'Realizar o monitoramento da implementa��o dos planos de a��o.'),

(316,'Identificar os seus cidad�os-usu�rios e classificar por tipos.'),
(316,'Identificar os seus cidad�os-usu�rios e classificar por grupos.'),

(317,'Identificar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos produtos da organiza��o.'),
(317,'Analisar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos produtos da organiza��o.'),
(317,'Compreender as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos produtos da organiza��o.'),
(317,'Utilizar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos produtos da organiza��o.'),

(317,'Identificar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos servi�os da organiza��o.'),
(317,'Analisar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos servi�os da organiza��o.'),
(317,'Compreender as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos servi�os da organiza��o.'),
(317,'Utilizar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos servi�os da organiza��o.'),

(317,'Identificar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos processos da organiza��o.'),
(317,'Analisar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos processos da organiza��o.'),
(317,'Compreender as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos processos da organiza��o.'),
(317,'Utilizar as necessidades e expectativas dos cidad�os-usu�rios, atuais e potenciais, para defini��o e melhoria dos processos da organiza��o.'),

(318,'Divulgar os produtos e servi�os da organiza��o aos cidad�os e � sociedade de forma a criar credibilidade.'),
(318,'Divulgar os produtos e servi�os da organiza��o aos cidad�os e � sociedade de forma a criar confian�a.'),
(318,'Divulgar os produtos e servi�os da organiza��o aos cidad�os e � sociedade de forma a criar imagem positiva.'),

(318,'Divulgar os padr�es de atendimento da organiza��o aos cidad�os e � sociedade de forma a criar credibilidade.'),
(318,'Divulgar os padr�es de atendimento da organiza��o aos cidad�os e � sociedade de forma a criar confian�a.'),
(318,'Divulgar os padr�es de atendimento da organiza��o aos cidad�os e � sociedade de forma a criar imagem positiva.'),

(318,'Divulgar as a��es de melhoria da organiza��o aos cidad�os e � sociedade de forma a criar credibilidade.'),
(318,'Divulgar as a��es de melhoria da organiza��o aos cidad�os e � sociedade de forma a criar confian�a.'),
(318,'Divulgar as a��es de melhoria da organiza��o aos cidad�os e � sociedade de forma a criar imagem positiva.'),

(319,'Identificar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus produtos.'),
(319,'Identificar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus servi�os.'),
(319,'Identificar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e suas a��es.'),

(319,'Avaliar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus produtos.'),
(319,'Avaliar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e seus servi�os.'),
(319,'Avaliar os n�veis de conhecimento do universo potencial de cidad�os-usu�rios sobre a organiza��o e suas a��es.'),

(320,'Avaliar a imagem da organiza��o perante os cidad�os-usu�rios.'),

(321,'Avaliar o atendimento ao universo potencial dos cidad�os-usu�rios identificados.'),

(322,'Definir e divulgar aos cidad�os-usu�rios os principais canais de acesso para solicitarem informa��es sobre os servi�os e produtos.'),
(322,'Definir e divulgar aos cidad�os-usu�rios os principais canais de acesso para solicitarem esclarecimentos sobre os servi�os e produtos.'),
(322,'Definir e divulgar aos cidad�os-usu�rios os principais canais de acesso para comunicarem suas sugest�es.'),
(322,'Definir e divulgar aos cidad�os-usu�rios os principais canais de acesso para comunicarem suas reclama��es.'),

(323,'Tratar as reclama��es, formais e informais dos cidad�os-usu�rios, visando assegurar a resposta r�pida e eficaz e o seu aproveitamento por toda a organiza��o.'),
(323,'Tratar as sugest�es, formais e informais dos cidad�os-usu�rios, visando assegurar a resposta r�pida e eficaz e o seu aproveitamento por toda a organiza��o.'),

(324,'Acompanhar os servi�os, recentemente prestados ou entregues, junto aos cidad�os-usu�rios para permitir � organiza��o gerar solu��es r�pidas e eficazes.'),
(324,'Acompanhar os servi�os, recentemente prestados ou entregues, junto aos cidad�os-usu�rios para permitir � organiza��o evitar problemas de relacionamento.'),
(324,'Acompanhar os servi�os, recentemente prestados ou entregues, junto aos cidad�os-usu�rios para permitir � organiza��o atender as expectativas dos cidad�os-usu�rios.'),

(324,'Acompanhar os produtos, recentemente prestados ou entregues, junto aos cidad�os-usu�rios para permitir � organiza��o gerar solu��es r�pidas e eficazes.'),
(324,'Acompanhar os produtos, recentemente prestados ou entregues, junto aos cidad�os-usu�rios para permitir � organiza��o evitar problemas de relacionamento.'),
(324,'Acompanhar os produtos, recentemente prestados ou entregues, junto aos cidad�os-usu�rios para permitir � organiza��o atender as expectativas dos cidad�os-usu�rios.'),

(325,'Avaliar a satisfa��o dos cidad�os-usu�rios em rela��o aos seus produtos ou servi�os e aos da concorr�ncia, quando pertinente.'),
(325,'Avaliar a insatisfa��o dos cidad�os-usu�rios em rela��o aos seus produtos ou servi�os e aos da concorr�ncia, quando pertinente.'),

(326,'Utilizar as informa��es obtidas dos cidad�os-usu�rios para melhorar o seu n�vel de satisfa��o.'),

(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de seus produtos, desde o projeto at� a disposi��o final, sobre os quais tenha influ�ncia.'),
(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de seus servi�os, desde o projeto at� a disposi��o final, sobre os quais tenha influ�ncia.'),
(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de seus processos desde o projeto at� a disposi��o final, sobre os quais tenha influ�ncia.'),
(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de suas instala��es, desde o projeto at� a disposi��o final, sobre os quais tenha influ�ncia.'),

(328,'Comunicar os impactos sociais e ambientais dos servi�os, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),
(328,'Comunicar os impactos sociais e ambientais dos produtos, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),
(328,'Comunicar os impactos sociais e ambientais dos processos, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),
(328,'Comunicar os impactos sociais e ambientais de suas instala��es, assim como as respectivas pol�ticas, a��es e resultados � sociedade.'),

(329,'Tratar as pend�ncias referentes aos requisitos legais, regulamentares, �ticos ou contratuais, relatando as atualmente existentes.'),
(329,'Tratar as eventuais san��es referentes aos requisitos legais, regulamentares, �ticos ou contratuais, relatando as atualmente existentes.'),

(330,'Promover a��es que envolvam a conserva��o de recursos n�o-renov�veis.'),
(330,'Promover a��es que envolvam a preserva��o do ecossistema.'),
(330,'Promover a��es que envolvam a otimiza��o do uso de recursos renov�veis.'),

(331,'Conscientizar a for�a de trabalho nas quest�es relativas � responsabilidade socioambiental.'),
(331,'Conscientizar os fornecedores nas quest�es relativas � responsabilidade socioambiental.'),
(331,'Conscientizar as demais partes interessadas nas quest�es relativas � responsabilidade socioambiental.'),

(331,'Envolver a for�a de trabalho nas quest�es relativas � responsabilidade socioambiental.'),
(331,'Envolver os fornecedores nas quest�es relativas � responsabilidade socioambiental.'),
(331,'Envolver as demais partes interessadas nas quest�es relativas � responsabilidade socioambiental.'),

(332,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos sociais, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento local, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(332,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos sociais, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento local, quando pertinente.'),
(332,'Direcionar os esfor�os para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(333,'Divulgar oficialmente os seus atos e informa��es sobre seus planos, programas e projetos.'),

(334,'Tornar p�blico o acesso as informa��es relativas a execu��o f�sica.'),
(334,'Tornar p�blico o acesso as informa��es relativas a execu��o or�ament�ria.'),
(334,'Tornar p�blico o acesso as informa��es relativas a execu��o financeira.'),
(334,'Tornar p�blico o acesso as informa��es relativas � gest�o.'),

(334,'Democratizar o acesso as informa��es relativas a execu��o f�sica.'),
(334,'Democratizar o acesso as informa��es relativas a execu��o or�ament�ria.'),
(334,'Democratizar o acesso as informa��es relativas a execu��o financeira.'),
(334,'Democratizar o acesso as informa��es relativas � gest�o.'),

(335,'Orientar a sociedade a participar no controle dos seus resultados institucionais.'),
(335,'Estimular a sociedade a participar no controle dos seus resultados institucionais.'),

(336,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho, no cumprimento de seu papel de agente p�blico.'),
(336,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho, no comportamento �tico em todos os n�veis.'),

(336,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho.'),
(336,'Estimular o cumprimento de seu papel de agente p�blico.'),
(336,'Estimular o comportamento �tico em todos os n�veis.'),

(337,'Disponibilizar canais de comunica��o para receber eventuais den�ncias de viola��o da �tica e atuar para minimizar esses acontecimentos e seus efeitos.'),

(338,'Identificar as necessidades da sociedade em rela��o ao seu setor de atua��o e transformar em requisitos para a formula��o e execu��o das pol�ticas p�blicas, quando pertinente.'),


(339,'Contribuir na formula��o de execu��es pol�ticas p�blicas do seu setor.'),
(339,'Atuar na execu��o das pol�ticas p�blicas do seu setor.'),

(340,'Divulgar as pol�ticas p�blicas e seus respectivos objetivos para a sociedade.'),

(341,'Monitorar a execu��o das pol�ticas p�blicas em seu n�vel de atua��o.'),
(341,'Avaliar a execu��o das pol�ticas p�blicas em seu n�vel de atua��o.'),

(342,'Avaliar a satisfa��o da sociedade com a implementa��o das pol�ticas p�blicas.'),
(342,'Avaliar a satisfa��o das demais partes interessadas com a implementa��o das pol�ticas p�blicas.'),

(343,'Identificar os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(343,'Definir os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(343,'Desenvolver os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(343,'Implantar os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),
(343,'Atualizar os principais sistemas de informa��o, visando atender �s necessidades identificadas da organiza��o.'),

(343,'Identificar os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),
(343,'Definir os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),
(343,'Desenvolver os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),
(343,'Implantar os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),
(343,'Atualizar os principais sistemas de informa��o, visando atender �s necessidades identificadas dos usu�rios.'),

(344,'Estabelecer a mem�ria administrativa da organiza��o.'),
(344,'Manter a mem�ria administrativa da organiza��o.'),

(345,'Apoiar o cumprimento da miss�o institucional.'),
(345,'Promover o cumprimento da miss�o institucional.'),
(345,'Apoiar a integra��o da organiza��o com seus cidad�os usu�rios, sociedade, fornecedores e parceiros.'),
(345,'Promover a integra��o da organiza��o com seus cidad�os usu�rios, sociedade, fornecedores e parceiros.'),

(346,'Colocar as informa��es necess�rias � disposi��o dos p�blicos internos e externos, incluindo cidad�os-usu�rios.'),
(346,'Colocar as informa��es necess�rias � disposi��o dos p�blicos internos e externos, incluindo fornecedores.'),
(346,'Colocar as informa��es necess�rias � disposi��o dos p�blicos internos e externos, incluindo parceiros.'),

(347,'Gerenciar a seguran�a das informa��es.'),

(348,'Identificar as organiza��es consideradas como um referencial comparativo pertinente.'),

(349,'Identificar as fontes de informa��es comparativas.'),
(349,'Obter as fontes de informa��es comparativas.'),
(349,'Manter as fontes de informa��es comparativas.'),

(350,'Melhorar o conhecimento dos processos organizacionais.'),
(350,'Estabelecer metas ousadas.'),
(350,'Promover melhorias no desempenho da organiza��o.'),

(351,'Desenvolver o conhecimento na organiza��o.'),
(351,'Compartilhar o conhecimento na organiza��o.'),

(352,'Manter o conhecimento protegido.'),

(353,'Assegurar que a gest�o do conhecimento seja utilizada para melhorar os processos.'),
(353,'Assegurar que a gest�o do conhecimento seja utilizada para melhorar os produtos.'),
(353,'Assegurar que a gest�o do conhecimento seja utilizada para melhorar os servi�os.'),

(354,'Identificar os ativos intang�veis da organiza��o'),
(354,'Desenvolver os ativos intang�veis da organiza��o'),
(354,'Mensurar os ativos intang�veis da organiza��o'),

(355,'Denifir a organiza��o do trabalho visando o alto desempenho da organiza��o.'),
(355,'Implementar a organiza��o do trabalho visando o alto desempenho da organiza��o.'),

(356,'Selecionar pessoas para o preenchimento de cargos e fun��es em conson�ncia com as estrat�gias da organiza��o.'),
(356,'Selecionar pessoas para o preenchimento de cargos e fun��es em conson�ncia com os objetivos da organiza��o.'),
(356,'Selecionar pessoas para o preenchimento de cargos e fun��es em conson�ncia com a miss�o da organiza��o.'),

(357,'Definir os canais de interlocu��o e a negocia��o com a for�a de trabalho da organiza��o, quando pertinente.'),
(357,'Disponibilizar os canais de interlocu��o e a negocia��o com a for�a de trabalho da organiza��o, quando pertinente.'),

(358,'Estimular a integra��o das pessoas e das equipes.'),
(358,'Estimular a integra��o das equipes.'),

(358,'Estimular a coopera��o das pessoas.'),
(358,'Estimular a coopera��o das equipes.'),

(359,'Gerenciar o desempenho das pessoas e das equipes, de forma a estimular a obten��o de metas de alto desempenho.'),
(359,'Gerenciar o desempenho das pessoas e das equipes, de forma a estimular a cultura da excel�ncia na organiza��o.'),
(359,'Gerenciar o desempenho das pessoas e das equipes, de forma a estimular o desenvolvimento profissional.'),


(360,'Estimular o alcance de metas de alto desempenho atrav�s do sistema de remunera��o.'),
(360,'Estimular o alcance de metas de alto desempenho atrav�s do sistema de reconhecimento.'),
(360,'Estimular o alcance de metas de alto desempenho atrav�s do sistema de incentivos para as pessoas.'),

(360,'Estimular o alcance de metas de aprendizado atrav�s do sistema de remunera��o.'),
(360,'Estimular o alcance de metas de aprendizado atrav�s do sistema de reconhecimento.'),
(360,'Estimular o alcance de metas de aprendizado atrav�s do sistema de incentivos para as pessoas.'),

(361,'Identificar as necessidades de capacita��o e desenvolvimento.'),

(362,'Compatibilizar as necessidades de capacita��o e de desenvolvimento das pessoas com as necessidades da organiza��o, para efeito da defini��o dos programas de capacita��o.'),
(362,'Compatibilizar as necessidades de capacita��o e de desenvolvimento das pessoas com as necessidades da organiza��o, para efeito da defini��o dos programas de desenvolvimento.'),

(363,'Abordar a cultura da excel�ncia atrav�s dos programas de capacita��o.'),
(363,'Abordar a cultura da excel�ncia atrav�s dos programas de desenvolvimento.'),

(363,'Contribuir para consolidar o aprendizado organizacional, atrav�s dos programas de capacita��o.'),
(363,'Contribuir para consolidar o aprendizado organizacional, atrav�s dos programas de desenvolvimento.'),

(364,'Conceber a forma de realiza��o dos programas de capacita��o considerando as necessidades da organiza��o.'),
(364,'Conceber a forma de realiza��o dos programas de capacita��o considerando as necessidades das pessoas.'),
(364,'Conceber a forma de realiza��o dos programas de capacita��o considerando os recursos dispon�veis.'),

(364,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando as necessidades da organiza��o.'),
(364,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando as necessidades das pessoas..'),
(364,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando os recursos dispon�veis.'),

(365,'Avaliar as habilidades em rela��o � sua utilidade na execu��o do trabalho da organiza��o.'),
(365,'Avaliar as habilidades em rela��o � sua efic�cia na consecu��o das estrat�gias da organiza��o.'),

(365,'Avaliar os conhecimentos adquiridos em rela��o � sua utilidade na execu��o do trabalho da organiza��o.'),
(365,'Avaliar os conhecimentos adquiridos em rela��o � sua efic�cia na consecu��o das estrat�gias da organiza��o.'),

(366,'Promover o desenvolvimento integral das pessoas, como indiv�duos.'),
(366,'Promover o desenvolvimento integral das pessoas, como cidad�os.'),
(366,'Promover o desenvolvimento integral das pessoas, como profissionais.'),

(367,'Identificar os perigos relacionados � sa�de ocupacional.'),
(367,'Identificar os perigos relacionados � seguran�a.'),
(367,'Identificar os perigos relacionados � ergonomia.'),

(367,'Tratar os riscos relacionados � sa�de ocupacional.'),
(367,'Tratar os riscos relacionados � seguran�a.'),
(367,'Tratar os riscos relacionados � ergonomia.'),

(368,'Identificar os fatores que afetam o bem-estar considerando os diferentes grupos de pessoas.'),
(368,'Identificar os fatores que afetam a satisfa��o considerando os diferentes grupos de pessoas.'),
(368,'Identificar os fatores que afetam a motiva��o considerando os diferentes grupos de pessoas.'),

(369,'Tratar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(369,'Tratar os fatores que afetam a satisfa��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(369,'Tratar os fatores que afetam a motiva��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),

(370,'Colaborar para a melhoria da qualidade de vida das pessoas fora do ambiente de trabalho.'),

(371,'Avaliar os fatores que afetam o bem-estar, considerando os diferentes grupos de pessoas.'),
(371,'Avaliar os fatores que afetam a satisfa��o, considerando os diferentes grupos de pessoas.'),
(371,'Avaliar os fatores que afetam a motiva��o, considerando os diferentes grupos de pessoas.'),

(372,'Identificar os processos final�sticos, considerando a miss�o institucional da organiza��o.'),
(372,'Identificar os processos de apoio, considerando a miss�o institucional da organiza��o.'),

(372,'Determinar os processos final�sticos, considerando a miss�o institucional da organiza��o.'),
(372,'Determinar os processos de apoio, considerando a miss�o institucional da organiza��o.'),

(373,'Traduzir em requisitos as necessidades dos cidad�os-usu�rios e da sociedade.'),
(373,'Incorporar aos projetos de servi�os ou produtos as necessidades dos cidad�os-usu�rios e da sociedade.'),
(373,'Incorporar aos processos final�sticos as necessidades dos cidad�os-usu�rios e da sociedade.'),

(374,'Projetar os processos de apoio, visando o cumprimento dos requisitos definidos'),
(374,'Projetar os processos final�sticos, visando o cumprimento dos requisitos definidos'),

(375,'Controlar os processos final�sticos.'),
(375,'Controlar os processos de apoio.'),

(376,'Refinar os processos final�sticos.'),
(376,'Refinar os processos de apoio.'),

(377,'Identificar potenciais fornecedores visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(377,'Identificar potenciais fornecedores visando melhorar o desempenho.'),
(377,'Identificar potenciais fornecedores visando assegurar o desenvolvimento sustent�vel de sua cadeia de suprimentos.'),

(377,'Desenvolver sua cadeia de suprimentos visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(377,'Desenvolver sua cadeia de suprimentos visando melhorar o seu desempenho.'),
(377,'Desenvolver sua cadeia de suprimentos visando assegurar o desenvolvimento sustent�vel de sua cadeia de suprimentos.'),

(378,'Como � realizado o processo de aquisi��o de bens, de materiais e de servi�os de forma a assegurar a transpar�ncia do processo e o atendimento � legisla��o.'),


(378,'Realizar o processo de aquisi��o de bens de forma a assegurar a transpar�ncia do processo.'),
(378,'Realizar o processo de aquisi��o de materiais de forma a assegurar a transpar�ncia do processo.'),
(378,'Realizar o processo de aquisi��o de servi�os de forma a assegurar a transpar�ncia do processo.'),

(378,'Realizar o processo de aquisi��o de bens de forma a atender a legisla��o.'),
(378,'Realizar o processo de aquisi��o de materiais de forma a atender a legisla��o.'),
(378,'Realizar o processo de aquisi��o de servi�os de forma a atender a legisla��o.'),


(379,'Assegurar a qualidade dos bens adquiridos.'),
(379,'Assegurar a qualidade dos produtos adquiridos.'),
(379,'Assegurar a qualidade dos servi�os adquiridos.'),

(380,'Realizar a gest�o dos bens materiais quando for pertinente.'),
(380,'Realizar a gest�o dos bens patrimoniais quando for pertinente.'),
(380,'Realizar a gest�o dos estoques, quando for pertinente.'),

(381,'Administrar o relacionamento com os fornecedores.'),

(382,'Avaliar os fornecedores e prontamente informar sobre seu desempenho.'),

(383,'Minimizar os custos associados a gest�o do fornecimento.'),

(384,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios Organizacionais relativos � responsabilidade socioambiental, incluindo os aspectos da seguran�a e sa�de.'),
(384,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o com os princ�pios Organizacionais relativos � responsabilidade socioambiental, incluindo os aspectos da seguran�a e sa�de.'),

(385,'Elaborar a proposta or�ament�ria mais significativa que possa vir a afetar a execu��o de suas atividades.'),
(385,'Tratar as restri��es e libera��es de or�amento mais significativas que possam vir a afetar a execu��o de suas atividades.'),

(386,'Gerenciar os processos or�ament�rios para suportar as necessidades estrat�gicas da organiza��o.'),
(386,'Gerenciar os processos or�ament�rios para suportar as necessidades operacionais da organiza��o.'),

(387,'Monitorar a execu��o or�ament�ria e os poss�veis realinhamentos entre o or�amento, estrat�gias e objetivos da organiza��o.'),
(387,'Monitorar a execu��o financeira e os poss�veis realinhamentos entre o or�amento, estrat�gias e objetivos da organiza��o.'),

(388,'Selecionar as melhores op��es de investimentos e aplica��es de recursos financeiros, quando pertinente.'),
(388,'Realizar capta��es de recursos financeiros, quando pertinente.'),

(389,'Acompanhar as opera��es da organiza��o em termos or�ament�rios e financeiros.'),
(389,'Administrar os par�metros or�ament�rios e financeiros.'),

(390,'Apresentar os resultados dos principais indicadores relativos aos cidad�osusu�rios.Estratificar por grupos de cidad�os-usu�rios, segmentos de mercado ou tipos de produtos, quando aplic�vel.Incluir os n�veis de desempenho de organiza��es consideradas como referencial comparativo pertinente;Explicar, resumidamente, os resultados apresentados, esclarecendo eventuais tend�ncias adversas e compara��es desfavor�veis.'),

(391,'Apresentar os resultados dos principais indicadores relativos � sociedade, incluindo os relativos � atua��o socioambiental, � �tica, ao controle social e �s pol�ticas p�blicas. Estratificar os resultados por instala��es, quando aplic�vel. Incluir os n�veis de desempenho de organiza��es consideradas como referencial comparativo pertinente; explicar, resumidamente, os resultados apresentados, esclarecendo eventuais tend�ncias adversas e compara��es desfavor�veis.'),

(392,'Apresentar os resultados dos principais indicadores relativos � gest�o or�ament�ria e financeira. Estratificar os resultados por unidades ou filiais, quando aplic�vel.'),

(393,'Apresentar os resultados dos principais indicadores relativos �s pessoas, incluindo os relativos aos sistemas de trabalho, � capacita��o e desenvolvimento e � qualidade de vida. Estratificar os resultados por grupos de pessoas da for�a de trabalho, fun��es na organiza��o e, quando aplic�vel, por instala��es.'),

(394,'Apresentar os resultados dos principais indicadores relativos aos processos de suprimento.'),

(395,'Apresentar os resultados dos principais indicadores relativos aos processos.'),

(396,'Estabelecer os valores necess�rios � promo��o da excel�ncia para todas as partes interessadas.'),
(396,'Estabelecer os valores necess�rios � cria��o de valor para todas as partes interessadas.'),

(396,'Estabelecer os princ�pios organizacionais necess�rios � promo��o da excel�ncia para todas as partes interessadas.'),
(396,'Estabelecer os princ�pios organizacionais necess�rios � cria��o de valor para todas as partes interessadas.'),

(397,'Comunicar os valores organizacionais para for�a de trabalho e, quando pertinentes, �s demais partes interessadas.'),
(397,'Comunicar os princ�pios organizacionais para for�a de trabalho e, quando pertinentes, �s demais partes interessadas.'),

(398,'Tratar as quest�es �ticas nos relacionamentos internos.'),
(398,'Tratar as quest�es �ticas nos relacionamentos externos.'),

(399,'Comunicar as principais decis�es.'),
(399,'Tomar as principais decis�es.'),
(399,'Implementar principais decis�es.'),

(400,'Exercer a lideran�a.'),
(400,'Interagir com as partes interessadas.'),

(401,'Verificar o cumprimento dos principais padr�es de trabalho.'),

(402,'Avaliar as pr�ticas de gest�o e respectivos padr�es de trabalho.'),
(402,'Melhorar as pr�ticas de gest�o e respectivos padr�es de trabalho.'),

(403,'Analisar o desempenho estrat�gico, considerando as informa��es comparativas do ambiente interno.'),
(403,'Analisar o desempenho estrat�gico, considerando as informa��es comparativas do ambiente externo.'),

(403,'Analisar o desempenho operacional, considerando as informa��es comparativas do ambiente interno.'),
(403,'Analisar o desempenho operacional, considerando as informa��es comparativas do ambiente externo.'),

(404,'Definir as estrat�gias da organiza��o, considerando o ambiente externo.'),
(404,'Definir as estrat�gias da organiza��o, considerando o ambiente interno.'),

(405,'Definir os indicadores para a avalia��o da implementa��o das estrat�gias e definir os respectivos planos de a��o.'),
(405,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de a��o.'),

(406,'Comunicar as estrat�gias �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'), 
(406,'Comunicar as metas �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'), 
(406,'Comunicar os planos de a��o �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'), 

(407,'Realizar o monitoramento da implementa��o dos planos de a��o.'),

(408,'Definir os clientes-alvo em rela��o a segmenta��o do mercado.'),

(409,'Identificar as necessidades dos clientes-alvo.'),
(409,'Identificar as expectativas dos clientes-alvo.'),
(409,'Analisar as necessidades dos clientes-alvo.'),
(409,'Analisar as expectativas dos clientes-alvo.'),
(409,'Compreender as necessidades dos clientes-alvo.'),
(409,'Compreender as expectativas dos clientes-alvo.'),

(410,'Divulgar os nprodutos da organiza��o aos clientes e ao mercado.'),
(410,'Divulgar a marca da organiza��o aos clientes e ao mercado.'),

(411,'Tratar as reclama��es, formais ou informais, dos clientes visando assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(411,'Tratar as sugest�es, formais ou informais, dos clientes visando assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),

(412,'Avaliar a satisfa��o dos clientes e utilizar essas informa��es para promover a��es de melhoria.'),

(413,'Tratar os impactos sociais adversos de produtos, processos e instala��es.'),
(413,'Tratar os impactos ambientais adversos de produtos, processos e instala��es.'),

(414,'Identificar  os requisitos legais, aplic�veis a quest�es socioambientais.'),
(414,'Identificar  os requisitos regulamentares aplic�veis a quest�es socioambientais.'),
(414,'Identificar  os requisitos contratuais, aplic�veis a quest�es socioambientais.'),

(414,'Analisar os requisitos legais, aplic�veis a quest�es socioambientais.'),
(414,'Analisar os requisitos regulamentares aplic�veis a quest�es socioambientais.'),
(414,'Analisar os requisitos contratuais, aplic�veis a quest�es socioambientais.'),

(415,'Conscientizar as pessoas da for�a de trabalho das quest�es relativas � responsabilidade socioambiental.'),
(415,'Envolver as pessoas da for�a de trabalho das quest�es relativas � responsabilidade socioambiental.'),


(416,'Selecionar projetos sociais voltados para o desenvolvimento nacional.'),
(416,'Selecionar projetos sociais voltados para o desenvolvimento regional.'),
(416,'Selecionar projetos sociais voltados para o desenvolvimento local.'),
(416,'Selecionar projetos sociais voltados para o desenvolvimento setorial.'),

(416,'Desenvolver projetos sociais voltados para o desenvolvimento nacional.'),
(416,'Desenvolver projetos sociais voltados para o desenvolvimento regional.'),
(416,'Desenvolver projetos sociais voltados para o desenvolvimento local.'),
(416,'Desenvolver projetos sociais voltados para o desenvolvimento setorial.'),

(417,'Definir os sistemas de informa��o para apoiar as opera��es di�rias.'),
(417,'Definir os sistemas de informa��o para apoiar a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),

(418,'Tratar a seguran�a das informa��es para assegurar sua atualiza��o.'),
(418,'Tratar a seguran�a das informa��es para assegurar sua confidencialidade.'),
(418,'Tratar a seguran�a das informa��es para assegurar sua integridade.'),
(418,'Tratar a seguran�a das informa��es para assegurar sua disponibilidade.'),

(419,'Obter as informa��es comparativas.'),
(419,'Manter as informa��es comparativas.'),

(420,'Compartilhar os conhecimentos geradores de diferenciais.'),
(420,'Reter os conhecimentos geradores de diferenciais.'),

(421,'Definir a organiza��o do trabalho.'),
(421,'Implementar a oganiza��o do trabalho.'),

(422,'Selecionar internamente pessoas para a for�a de trabalho.'),
(422,'Selecionar externamente pessoas para a for�a de trabalho.'),

(422,'Contratar internamente pessoas para a for�a de trabalho.'),
(422,'Contratar externamente pessoas para a for�a de trabalho.'),

(423,'Identificar as necessidades de capacita��o, considerando as estrat�gias da organiza��o.'),
(423,'Identificar as necessidades de capacita��o, considerando as necessidades das pessoas.'),

(423,'Identificar as necessidades de desenvolvimento, considerando as estrat�gias da organiza��o.'),
(423,'Identificar as necessidades de desenvolvimento, considerando as necessidades das pessoas.'),

(424,'Definir os programas de capacita��o, considerando as necessidades identificadas.'),
(424,'Definir os programas de desenvolvimento, considerando as necessidades identificadas.'),

(425,'Identificar os perigos relacionados � sa�de ocupacional.'),
(425,'Identificar os perigos relacionados � seguran�a.'),
(425,'Identificar os perigos relacionados � ergonomia.'),

(425,'Tratar os riscos relacionados � sa�de ocupacional.'),
(425,'Tratar os riscos relacionados � seguran�a.'),
(425,'Tratar os riscos relacionados � ergonomia.'),

(426,'Avaliar a satisfa��o das pessoas.'),

(427,'Determinar os requisitos aplic�veis aos processos principais do neg�cio, a partir das necessidades dos clientes.'),
(427,'Determinar os requisitos aplic�veis aos processos principais do neg�cio, a partir das necessidades das demais partes interessadas.'),

(427,'Determinar os requisitos aplic�veis aos processos de apoio do neg�cio, a partir das expectativas dos clientes.'),
(427,'Determinar os requisitos aplic�veis aos processos de apoio do neg�cio, a partir das expectativas das demais partes interessadas.'),


(428,'Controlar os processos principais do neg�cio, visando assegurar o atendimento dos requisitos aplic�veis.'),
(428,'Controlar os processos de apoio do neg�cio, visando assegurar o atendimento dos requisitos aplic�veis.'),

(429,'Analisar os processos principais do neg�cio.'),
(429,'Melhorar os processos principais do neg�cio.'),

(429,'Analisar os processos de apoio do neg�cio.'),
(429,'Melhorar os processos de apoio do neg�cio.'),

(430,'Selecionar os fornecedores, considerando requisitos de desempenho.'),

(431,'Envolver os fornecedores, que atuam diretamente nos processos da organiza��o, nos processos da organiza��o'),
(431,'Envolver os fornecedores, que atuam diretamente nos processos da organiza��o, com os valores da organiza��o'),
(431,'Envolver os fornecedores, que atuam diretamente nos processos da organiza��o, com os princ�pios organizacionais'),

(432,'Elaborar o or�amento.'),
(432,'Controlar o or�amento.'),
(432,'Manter o fluxo financeiro equilibrado.'),

(431,'Como os fornecedores que atuam diretamente nos processos da organiza��o s�o envolvidos e comprometidos com os valores e os princ�pios organizacionais, incluindo os aspectos relativos � seguran�a e � sa�de.'),

(432,'Como � elaborado e controlado o or�amento e mantido o fluxo financeiro equilibrado.'),

(433,'Apresentar os resultados relativos � gest�o econ�mico-financeira.'),

(434,'Apresentar os resultados relativos aos clientes e aos mercados.'),

(435,'Apresentar os resultados relativos � sociedade.'),

(436,'Apresentar os resultados relativos �s pessoas.'),

(437,'Apresentar os resultados relativos ao produto e � gest�o dos processos principais do neg�cio e dos processos de apoio.'),

(438,'Apresentar os resultados relativos aos fornecedores.'),

(439,'Exercer a lideran�a, interagindo com todas as partes interessadas.'),
(439,'Exercer a lideran�a, promovendo o comprometimento com todas as partes interessadas.'),

(440,'Tomar as principais decis�es da Alta Administra��o.'),
(440,'Comunicar as principais decis�es da Alta Administra��o.'),
(440,'Implementar as principais decis�es da Alta Administra��o.'),

(441,'Internalizar os Princ�pios e Valores da Administra��o P�blica na organiza��o.'),
(441,'Internalizar as Diretrizes do Governo na organiza��o.'),
(441,'Internalizar os Princ�pios Organizacionais na organiza��o.'),

(441,'Disseminar os Princ�pios e Valores da Administra��o P�blica na organiza��o.'),
(441,'Disseminar as Diretrizes do Governo na organiza��o.'),
(441,'Disseminar os Princ�pios Organizacionais na organiza��o.'),

(442,'Conduzir a implementa��o do sistema de gest�o, visando assegurar o atendimento das necessidades e expectativas de todas as partes interessadas.'),
(442,'Assegurar o atendimento das necessidades, visando assegurar o atendimento das necessidades e expectativas de todas as partes interessadas.'),

(443,'Analisar criticamente o desempenho por meio de indicadores.'),
(443,'Acompanhar a implementa��o das decis�es decorrentes da an�lise dos indicadores.'),

(444,'Avaliar as pr�ticas de gest�o e seus respectivos padr�es.'),
(444,'Melhorar as pr�ticas de gest�o e seus respectivos padr�es.'),

(445,'Definir as estrat�gias da organiza��o considerando-se as necessidades das partes interessadas.'),
(445,'Definir as estrat�gias da organiza��o considerando-se as demandas do governo.'),
(445,'Definir as estrat�gias da organiza��o considerando-se as informa��es internas.'),

(446,'Definir os indicadores para a avalia��o da operacionaliza��o das estrat�gias e definir os respectivos planos de a��o.'),
(446,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de a��o.'),

(447,'Alocar os recursos para assegurar a implementa��o dos planos de a��o.'),

(448,'Comunicar as estrat�gias �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(448,'Comunicar as metas �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(448,'Comunicar os planos de a��o �s pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),

(449,'Monitorar a implementa��o dos planos de a��o.'),

(450,'Traduzir as necessidades dos cidad�os-usu�rios em requisitos e incorporar aos projetos de servi�os.'),
(450,'Traduzir as necessidades dos cidad�os-usu�rios em requisitos e incorporar produtos.'),
(450,'Traduzir as necessidades dos cidad�os-usu�rios em requisitos e incorporar processos final�sticos.'),

(450,'Traduzir as necessidades da sociedade em requisitos e incorporar aos projetos de servi�os.'),
(450,'Traduzir as necessidades da sociedade em requisitos e incorporar produtos.'),
(450,'Traduzir as necessidades da sociedade em requisitos e incorporar processos final�sticos.'),

(451,'divulgar os produtos e servi�os da organiza��o  aos cidad�os e � sociedade.'),
(451,'divulgar os padr�es de atendimento da organiza��o  aos cidad�os e � sociedade.'),
(451,'divulgar as a��es de melhoria da organiza��o aos cidad�os e � sociedade.'),


(452,'Tratar as solicita��es, formais ou informais, dos cidad�os-usu�rios visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(452,'Tratar as reclama��es, formais ou informais, dos cidad�os-usu�rios visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(452,'Tratar as sugest�es, formais ou informais, dos cidad�os-usu�rios visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),

(453,'Avaliar a satisfa��o dos cidad�os-usu�rios em rela��o aos seus servi�os.'),
(453,'Avaliar a satisfa��o dos cidad�os-usu�rios em rela��o aos seus produtos.'),

(454,'Identificar os impactos sociais adversos decorrentes da atua��o da organiza��o.'),
(454,'Identificar os impacos ambientais adversos decorrentes da atua��o da organiza��o.'),
(454,'Tratar os impactos sociais adversos decorrentes da atua��o da organiza��o.'),
(454,'Tratar os impacos ambientais adversos decorrentes da atua��o da organiza��o.'),

(455,'Estimular a for�a de trabalho nas quest�es relativas � responsabilidade socioambiental.'),
(455,'Envolver a for�a de trabalho nas quest�es relativas � responsabilidade socioambiental.'),

(455,'Estimular seus parceiros nas quest�es relativas � responsabilidade socioambiental.'),
(455,'Envolver seus parceiros nas quest�es relativas � responsabilidade socioambiental.'),

(456,'Orientar a sociedade a exercer o controle social.'),
(456,'Estimular a sociedade a exercer o controle social.'),

(457,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho, no cumprimento de seu papel de agente p�blico.'), 
(457,'Estimular o exerc�cio da responsabilidade social da for�a de trabalho, no cumprimento do comportamento �tico em todos os n�veis.'), 

(458,'Identificar as necessidades da sociedade em rela��o ao seu setor de atua��o e transformar em requisitos para a formula��o e execu��o de pol�ticas p�blicas, quando pertinente.'),

(459,'Identificar as necessidades em rela��o aos sistemas de informa��es para apoiar as opera��es di�rias.'),
(459,'Definir os sistemas de informa��es para apoiar as opera��es di�rias.'),
(459,'Implantar os sistemas de informa��es para apoiar as opera��es di�rias.'),

(459,'Identificar as necessidades em rela��o aos sistemas de informa��es para a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),
(459,'Definir os sistemas de informa��es para a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),
(459,'Implantar os sistemas de informa��es para a tomada de decis�o em todos os n�veis e �reas da organiza��o.'),

(460,'Tratar a seguran�a das informa��es para assegurar sua atualiza��o.'),
(460,'Tratar a seguran�a das informa��es para assegurar sua confidencialidade.'),
(460,'Tratar a seguran�a das informa��es para assegurar sua integridade.'),
(460,'Tratar a seguran�a das informa��es para assegurar sua disponibilidade.'),

(461,'Estabelecer a mem�ria administrativa.'),
(461,'Manter a mem�ria administrativa.'),

(462,'Utilizar outras organiza��es como referencial comparativo.'),

(463,'Desenvolver o conhecimento na organiza��o.'), 
(463,'Proteger o conhecimento na organiza��o.'),
(463,'Compartilhar o conhecimento na organiza��o.'),

(464,'Definir organiza��o do trabalho.'),
(464,'Implementar organiza��o do trabalho.'),

(465,'Gerenciar o desempenho das pessoas, de forma a estimular a busca por melhores resultados.'),
(465,'Gerenciar o desempenho das equipes, de forma a estimular a busca por melhores resultados.'),

(466,'Identificar as necessidades de capacita��o considerando as estrat�gias.'),
(466,'Identificar as necessidades de capacita��o considerando as necessidades das pessoas.'),

(466,'Identificar as necessidades de desenvolvimento considerando as estrat�gias.'),
(466,'Identificar as necessidades de desenvolvimento considerando as necessidades das pessoas.'),

(467,'Conceber a forma de realiza��o dos programas de capacita��o considerando as necessidades identificadas.'),
(467,'Conceber a forma de realiza��o dos programas de desenvolvimento considerando as necessidades identificadas.'),

(468,'Identificar os perigos relacionados a sa�de ocupacional.'),
(468,'Identificar os perigos relacionados a seguran�a.'),
(468,'Identificar os perigos relacionados a ergonomia.'),

(468,'Tratar os perigos relacionados a sa�de ocupacional.'),
(468,'Tratar os perigos relacionados a seguran�a.'),
(468,'Tratar os perigos relacionados a ergonomia.'),

(469,'Identificar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(469,'Identificar os fatores que afetam a satisfa��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(469,'Identificar os fatores que afetam a motiva��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),

(469,'Tratar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(469,'Tratar os fatores que afetam a satisfa��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),
(469,'Tratar os fatores que afetam a motiva��o das pessoas e manter um clima organizacional favor�vel ao alto desempenho.'),

(470,'Avaliar a satisfa��o das pessoas.'),

(471,'Projetar processos de apoio, visando ao cumprimento dos requisitos aplic�veis.'),
(471,'Projetar processos final�sticos, visando ao cumprimento dos requisitos aplic�veis.'),

(472,'Controlar os processos de apoio, visando ao cumprimento dos requisitos aplic�veis.'),
(472,'Controlar os processos final�sticos, visando ao cumprimento dos requisitos aplic�veis.'),

(473,'Analisar os processos final�sticos.'),
(473,'Analisar os processos de apoio.'),
(473,'Melhorar os processos final�sticos.'),
(473,'Melhorar os processos de apoio.'),

(474,'Selecionar os fornecedores.'),

(475,'Avaliar o desempenho de fornecedores.'),
(475,'Prontamente informar o desempenho aos fornecedores.'),

(476,'Elaborar o or�amento.'),
(476,'Gerenciar o or�amento.'),

(477,'Apresentar os resultados relativos aos cidad�os-usu�rios.'),

(478,'Apresentar os resultados relativos � sociedade.'),

(479,'Apresentar os resultados or�ament�rios.'),
(479,'Apresentar os resultados financeiros.'),

(480,'Apresentar os resultados relativos �s pessoas.'),

(481,'Apresentar os resultados relativos aos processos de suprimento.'),

(482,'Apresentar os resultados dos processos final�sticos.'),
(482,'Apresentar os resultados dos processos de apoio.'),

(483,'Tomar decis�es relativas aos principais processos.'),

(484,'Tomar decis�es relativas ao plano de gest�o.'),

(485,'Comunicar as principais decis�es tomadas, de car�ter ostensivo, a todos os escal�es e integrantes da OM'),

(486,'Implementar decis�es em cada escal�o da OM.'),

(487,'Controlar resultado das se��es do EM ou equivalentes.'),

(488,'Prestar conta dos resultados organizacionais ao escal�o enquadrante.'),

(489,'Estabelecer os valores, contidos no Plano de Gest�o, necess�rios ao cumprimento de sua Miss�o.'),

(490,'Atualizar os valores, contidos no Plano de Gest�o, necess�rios ao cumprimento de sua Miss�o.'),

(491,'Disseminar os princ�pios da Administra��o P�blica (legalidade, impessoalidade, moralidade, publicidade e efici�ncia) para todos os integrantes da OM.'),

(492,'Descrever a atua��o do Comando junto ao Escal�o Superior para melhorar as condi��es de cumprimento da miss�o.'),
(492,'Descrever a atua��o do Comando junto e ao Escal�o de Apoio para melhorar as condi��es de cumprimento da miss�o.'),

(493,'Verificar se os valores s�o aplicados pelos integrantes da OM.'),

(494,'Estabelecer os principais padr�es de trabalho para regular as atividades internas da OM.'),
(494,'Estabelecer os principais padr�es de trabalho para regular as atividades externas da OM.'),

(495,'Verificar o cumprimento dos padr�es de trabalho das atividades da OM.'),
(495,'Verificar o cumprimento das regras de funcionamento das atividades da OM.'),

(496,'Apresentar os Indicadores de Desempenho utilizados para possibilitar as a��es corretivas em cada processo.'),

(497,'Descrever a atua��o do Comando na busca de parcerias para facilitar o cumprimento da miss�o da OM e do EB.'),
(497,'Descrever a atua��o do Comando na busca de parcerias para facilitar a capacita��o do pessoal da OM e do EB.'),
(497,'Descrever a atua��o do Comando na busca de parcerias para melhorar a imagem da OM e do EB.'),

(498,'Disseminar os valores da OM pelo Comando aos seus integrantes.'),
(499,'Utilizar os valores da OM pelos seus integrantes.'),

(500,'Atuar para verificar se os valores s�o de conhecimento de todos os integrantes da OM.'),

(624,'Assegurar a equidade entre s�cios, mantenedores ou instituidores.'),
(624,'Proteger os direitos das partes interessadas.'),
(625,'Estabelecer os valores e princ�pios organizacionais necess�rios � promo��o da excel�ncia.'), 
(625,'Atualizar os valores e princ�pios organizacionais necess�rios � promo��o da excel�ncia.'),
(625,'Estabelecer valor para todas as partes interessadas e ao desenvolvimento sustent�vel.'),
(625,'Atualizar os valores para todas as partes interessadas e ao desenvolvimento sustent�vel.'),
(626,'Estabelecer regras de conduta para os integrantes da sua administra��o.'),
(626,'Estabelecer regras de conduta para a for�a de trabalho.'),
(626,'Tratar as quest�es �ticas.'),
(626,'Buscar  um relacionamento �tico com concorrentes e com as partes interessadas.'),
(626,'Assegurar um relacionamento �tico com concorrentes e com as partes interessadas.'),
(627,'Identificar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organiza��o de alcan�ar os objetivos estrat�gicos do neg�cio.'),
(627,'Classificar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organiza��o de alcan�ar os objetivos estrat�gicos do neg�cio.'),
(627,'Analisar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organiza��o de alcan�ar os objetivos estrat�gicos do neg�cio.'),
(627,'Tratar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organiza��o de alcan�ar os objetivos estrat�gicos do neg�cio.'),
(628,'Tomar as principais decis�es para assegurar a transpar�ncia levando em considera��o o envolvimento dos principais interessados nos temas tratados.'),
(628,'Comunicar as principais decis�es para assegurar a transpar�ncia levando em considera��o o envolvimento dos principais interessados nos temas tratados.'),
(628,'Implementar as principais decis�es para assegurar a transpar�ncia levando em considera��o o envolvimento dos principais interessados nos temas tratados.'),
(629,'Comunicar prontamente os fatos relevantes � sociedade.'),
(629,'Comunicar prontamente os fatos relevantes �s demais partes interessadas.'),
(630,'Prestar contas das suas a��es a quem a elegeu, nomeou ou designou.'),
(630,'Prestar contas dos resultados alcan�ados a quem a elegeu, nomeou ou designou.'),
(631,'Exercer a lideran�a com as partes interessadas, identificando suas expectativas.'),
(631,'Exercer a lideran�a com as partes interessadas, buscando o alinhamento de interesses.'),
(631,'Interagir com as partes interessadas, identificando suas expectativas.'),
(631,'Interagir com as partes interessadas, buscando o alinhamento de interesses.'),
(632,'Identificar as mudan�as culturais necess�rias para a internaliza��o dos valores princ�pios organizacionais e para o �xito das estrat�gias.'),
(632,'Desenvolver as mudan�as culturais necess�rias para a internaliza��o dos valores princ�pios organizacionais e para o �xito das estrat�gias.'),
(633,'Comunicar os valores � for�a de trabalho e demais partes interessadas.'),
(633,'Comunicar os princ�pios organizacionais � for�a de trabalho e demais partes interessadas.'),
(634,'Avaliar nos l�deres, as compet�ncias necess�rias para o exerc�cio da lideran�a.'),
(634,'Desenvolver nos l�deres, as compet�ncias necess�rias para o exerc�cio da lideran�a.'),
(635,'Estabelecer os principais e padr�es de trabalho para os processos gerenciais.'),
(635,'Verificar o cumprimento dos principais padr�es de trabalho para os processos gerenciais.'),
(636,'Refinar os processos gerenciais por meio do aprendizado.'), 
(636,'Inovar os processos gerenciais por meio do aprendizado.'),
(637,'Investigar as boas pr�ticas de gest�o das organiza��es de refer�ncia para apoiar o aprendizado.'),
(638,'Identificar as necessidades de informa��es comparativas para analisar o desempenho operacional.'), 
(638,'Identificar as necessidades de informa��es comparativas para analisar o desempenho estrat�gico da organiza��o.'),
(639,'Avaliar o desempenho operacional da organiza��o, visando ao desenvolvimento sustent�vel.'),
(639,'Avaliar o desempenho estrat�gico da organiza��o, visando ao desenvolvimento sustent�vel.'),
(640,'Comunicar � for�a de trabalho as decis�es decorrentes da an�lise do desempenho da organiza��o.'),
(640,'Comunicar em todos os n�veis da organiza��o as decis�es decorrentes da an�lise do desempenho da organiza��o.'),
(640,'Comunicar a outras partes interessadas as decis�es decorrentes da an�lise do desempenho da organiza��o.'),
(641,'Acompanhar a implementa��o das decis�es decorrentes da an�lise do desempenho da organiza��o.'),
(642,'Analisar o macroambiente e as caracter�sticas do setor de atua��o da organiza��o e suas tend�ncias.'),
(642,'Analisar o macroambiente e as caracter�sticas do setor de atua��o da organiza��o e suas tend�ncias.'),
(642,'Identificar o macroambiente e as caracter�sticas do setor de atua��o da organiza��o e suas tend�ncias.'),
(643,'Analisar o mercado de atua��o da organiza��o.'), 
(643,'Analisar as tend�ncias do mercado de atua��o da organiza��o.'),
(644,'Analisar o ambiente interno da organiza��o.'),
(645,'Avaliar as alternativas decorrentes das an�lises dos ambientes.'),
(645,'Definir as estrat�gias da organiza��o.'),
(646,'Avaliar o modelo de neg�cio em conson�ncia com a defini��o das estrat�gias, visando � potencializa��o de seu �xito.'),
(647,'Definir os indicadores para a avalia��o da implementa��o das estrat�gias e definir os respectivos planos de a��o.'),
(647,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de a��o.'),
(648,'Desdobar as metas e os planos de a��o nas �reas respons�veis pelos processos principais do neg�cio e processos de apoio.'),
(648,'Assegurar a coer�ncia das metas e dos planos resultantes com as estrat�gias e tamb�m entre si.'),
(648,'Manter o alinhamento entre os indicadores utilizados na avalia��o do desempenho estrat�gico e aqueles utilizados na avalia��o do desempenho operacional.'),
(649,'Alocar os recursos para assegurar a implementa��o dos principais planos de a��o.'),
(650,'Comunicar as estrat�gias para as pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(650,'Comunicar as metas para as pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(650,'Comunicar os planos de a��o para as pessoas da for�a de trabalho e para as demais partes interessadas, quando pertinente.'),
(651,'Realizar o monitoramento da implementa��o dos planos de a��o.'),
(652,'Segmentar o mercado.'),
(653,'Definir os clientes-alvo nso segmentos da organiza��o, considerando-se, inclusive, os clientes da concorr�ncia, quando existirem, e os clientes e mercados potenciais.'),
(654,'Identificar as necessidades e as expectativas dos clientes, atuais e potenciais, de ex-clientes e de usu�rios para definir a melhoria dos produtos e processos da organiza��o.'),
(654,'Analisar as necessidades e as expectativas dos clientes, atuais e potenciais, de ex-clientes e de usu�rios para definir a melhoria dos produtos e processos da organiza��o.'),
(654,'Utilizar as necessidades e as expectativas dos clientes, atuais e potenciais, de ex-clientes e de usu�rios para definir a melhoria dos produtos e processos da organiza��o.'),
(655,'Divulgar para os clientes as marcas, os produtos, incluindo os cuidados necess�rios ao seu uso e os riscos envolvidos, e tamb�m as a��es de melhoria da organiza��o de forma a criar credibilidade.'),
(655,'Divulgar para os clientes as marcas, os produtos, incluindo os cuidados necess�rios ao seu uso e os riscos envolvidos, e tamb�m as a��es de melhoria da organiza��o de forma a criar confian�a.'),
(655,'Divulgar para os clientes as marcas, os produtos, incluindo os cuidados necess�rios ao seu uso e os riscos envolvidos, e tamb�m as a��es de melhoria da organiza��o de forma a criar imagem positiva.'),
(656,'Identificar os n�veis de conhecimento dos clientes e mercados a respeito das marcas da organiza��o.'),
(656,'Identificar os n�veis de conhecimento dos clientes e mercados a respeito dos produtos da organiza��o.'),
(656,'Avaliar os n�veis de conhecimento dos clientes a respeito das marcas da organiza��o.'),
(656,'Avaliar os n�veis de conhecimento dos clientes a respeito dos produtos da organiza��o.'),
(656,'Avaliar os n�veis de conhecimento mercados a respeito dos produtos da organiza��o.'),
(656,'Avaliar os n�veis de conhecimento mercados a respeito dos produtos da organiza��o.'),
(657,'Avaliar a imagem da organiza��o perante os clientes.'),
(657,'Avaliar a imagem da organiza��o perante os mercados.'),
(658,'Definir os canais de relacionamento com os clientes, considerando-se a segmenta��o do mercado.'),
(658,'Definir os canais de relacionamento com os clientes, considerando-se o agrupamento de clientes utilizado.'),
(658,'Divulgar para os clientes os canais de relacionamento, considerando-se a segmenta��o do mercado.'),
(658,'Divulgar para os clientes os canais de relacionamento, considerando-se o agrupamento de clientes utilizado.'),
(659,'Tratar as solicita��es, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(659,'Tratar as reclama��es, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(659,'Tratar as sugest�es, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(660,'Acompanhar as transa��es com os clientes, de forma a permitir � organiza��o gerar solu��es r�pidas e eficazes.'),
(660,'Acompanhar as transa��es com os clientes, de forma a permitir � organiza��o evitar problemas de relacionamento.'),
(660,'Acompanhar as transa��es com os clientes, de forma a permitir � organiza��o atender �s expectativas dos clientes.'),
(661,'Avaliar a satisfa��o dos clientes, inclusive em rela��o aos clientes dos concorrentes ou, quando n�o houver concorr�ncia, de outras organiza��es de refer�ncia.'),
(661,'Avaliar a fidelidade, inclusive em rela��o aos clientes dos concorrentes ou, quando n�o houver concorr�ncia, de outras organiza��es de refer�ncia.'),
(661,'Avaliar a insatisfa��o dos clientes, inclusive em rela��o aos clientes dos concorrentes ou, quando n�o houver concorr�ncia, de outras organiza��es de refer�ncia.'),
(662,'Analisar as informa��es obtidas dos clientes para intensificar a sua satisfa��o.'),
(662,'Analisar as informa��es obtidas dos clientes para torn�-los fi�is.'),
(662,'Analisar as informa��es obtidas dos clientes para incentiv�-los que recomendem os produtos da organiza��o.'),
(662,'Analisar as informa��es obtidas dos clientes para que sejam desenvolvolvidos processos e produtos.'),
(663,'Identificar parcerias com clientes, visando � manuten��o ou ao aumento da competitividade da organiza��o.'),
(663,'Identificar parcerias com distribuidores, visando � manuten��o ou ao aumento da competitividade da organiza��o.'),
(663,'Identificar parcerias com revendedores visando � manuten��o ou ao aumento da competitividade da organiza��o.'),
(663,'Desenvolver parcerias com clientes visando � manuten��o ou ao aumento da competitividade da organiza��o.'),
(663,'Desenvolver parcerias com distribuidores visando � manuten��o ou ao aumento da competitividade da organiza��o.'),
(663,'Desenvolver parcerias revendedores visando � manuten��o ou ao aumento da competitividade da organiza��o.'),
(664,'Identificar os aspectos e tratar os impactos sociais adversos de seus produtos.'),
(664,'Identificar os aspectos e tratar os impactos sociais adversos de seus processos.'),
(664,'Identificar os aspectos e tratar os impactos sociais adversos de suas instala��es.'),
(665,'Manter organiza��o preparada para prevenir acidentes, visando prevenir ou mitigar os seus impactos adversos na sociedade, incluindo aqueles em comunidades potencialmente impactadas.'),
(665,'Responder �s eventuais situa��es de emerg�ncia, visando prevenir ou mitigar os seus impactos adversos na sociedade, incluindo aqueles em comunidades potencialmente impactadas.'),
(666,'Comunicar os impactos sociais e ambientais dos produtos, as a��es e os resultados relativos � responsabilidade socioambiental � sociedade, incluindo as comunidades potencialmente impactadas.'),
(666,'Comunicar os impactos sociais e ambientais dos processos e instala��es, assim como as pol�ticas, as a��es e os resultados relativos � responsabilidade socioambiental � sociedade, incluindo as comunidades potencialmente impactadas.'),
(667,'Identificar os requisitos legais aplic�veis a quest�es socioambientais e implementar a��es de melhoria visando ao seu pleno atendimento.'),
(667,'Identificar os requisitos regulamentares aplic�veis a quest�es socioambientais e implementar a��es de melhoria visando ao seu pleno atendimento.'),
(667,'Identificar os requisitos contratuais aplic�veis a quest�es socioambientais e implementar a��es de melhoria visando ao seu pleno atendimento.'),
(667,'Analisar os requisitos legais aplic�veis a quest�es socioambientais e implementar a��es de melhoria visando ao seu pleno atendimento.'),
(667,'Analisar os requisitos regulamentares aplic�veis a quest�es socioambientais e implementar a��es de melhoria visando ao seu pleno atendimento.'),
(667,'Analisar os requisitos contratuais aplic�veis a quest�es socioambientais e implementar a��es de melhoria visando ao seu pleno atendimento.'),
(668,'Propiciar a acessibilidade aos produtos da organiza��o.'),
(668,'Propiciar a acessibilidade as instala��es da organiza��o.'),
(669,'Selecionar, de forma volunt�ria, a��es com vista ao desenvolvimento sustent�vel.'),
(669,'Promover, de forma volunt�ria, a��es com vista ao desenvolvimento sustent�vel.'),
(670,'Identificar as necessidades da sociedade, incluindo comunidades vizinhas �s instala��es da organiza��o.'),
(670,'Analisar as necessidades da sociedade, incluindo comunidades vizinhas �s instala��es da organiza��o.'),
(670,'Utilizar as necessidades da sociedade, incluindo comunidades vizinhas �s instala��es da organiza��o.'),
(670,'Identificar as expectativas da sociedade, incluindo comunidades vizinhas �s instala��es da organiza��o.'),
(670,'Analisar as expectativas da sociedade, incluindo comunidades vizinhas �s instala��es da organiza��o.'),
(670,'Utilizar as expectativas da sociedade, incluindo comunidades vizinhas �s instala��es da organiza��o.'),
(671,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento nacional.'),
(671,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento regional.'),
(671,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento local.'),
(671,'Direcionar os esfor�os para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento setorial.'),
(672,'Avaliar o grau de satisfa��o da sociedade, incluindo comunidades vizinhas, em rela��o � organiza��o.'),
(673,'Analisar as informa��es obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfa��o e para aperfei�oar projetos sociais.'),
(673,'Analisar as informa��es obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfa��o e para desenvolver projetos sociais.'),
(673,'Utilizar as informa��es obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfa��o e para aperfei�oar projetos sociais.'),
(673,'Utilizar as informa��es obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfa��o e para desenvolver projetos sociais.'),
(674,'Avaliar sua imagem perante a sociedade, incluindo comunidades vizinhas.'),
(674,'Zelar por sua imagem perante a sociedade, incluindo comunidades vizinhas.'),
(675,'Identificar as necessidades de informa��es e de seu tratamento para apoiar as opera��es di�rias.'),
(675,'Acompanhar o progresso dos planos de a��o.'),
(675,'Subsidiar a tomada de decis�es em todos os n�veis e �reas da organiza��o.'),
(676,'Definir os principais sistemas de informa��o, visando atender �s necessidades identificadas.'),
(676,'Desenvolver os principais sistemas de informa��o, visando atender �s necessidades identificadas.'),
(676,'Implantar os principais sistemas de informa��o, visando atender �s necessidades identificadas.'),
(676,'Melhorar os principais sistemas de informa��o, visando atender �s necessidades identificadas.'),
(677,'Analisar a tecnologia de informa��o para alavancar o neg�cio.'),
(677,'Analisar a tecnologia de informa��o para promover a integra��o da organiza��o com as partes interessadas.'),
(677,'Utilizar a tecnologia de informa��o para alavancar o neg�cio.'),
(677,'Utilizar a tecnologia de informa��o para promover a integra��o da organiza��o com as partes interessadas.'),
(678,'Compatibilizar a infraestrutura para a disponibiliza��o das informa��es aos usu�rios, internos e externos � organiza��o, com o crescimento do neg�cio.'),
(678,'Compatibilizar a infraestrutura para a disponibiliza��o das informa��es aos usu�rios, internos e externos � organiza��o, com a demanda por informa��es.'),
(679,'Garantir a seguran�a das informa��es.'),
(680,'Identificar os ativos intang�veis que mais agregam valor ao neg�cio, gerando um diferencial competitivo para a organiza��o. '),
(681,'Desenvolver os at�vos intang�veis.'),
(681,'Proteger os at�vos intang�veis.'),
(682,'Identificar os conhecimentos que sustentam o desenvolvimento das estrat�gias.'),
(682,'Identificar os conhecimentos que sustentam o desenvolvimento das opera��es.'),
(682,'Desenvolver os conhecimentos que sustentam o desenvolvimento das estrat�gias.'),
(682,'Desenvolver os conhecimentos que sustentam o desenvolvimento das opera��es.'),
(683,'Compartilhar os conhecimentos da organiza��o.'),
(683,'Reter os conhecimentos da organiza��o.'),
(684,'Elaborar a organiza��o do trabalho em alinhamento com o modelo de neg�cio, visando ao alto desempenho e � inova��o.'),
(684,'Elaborar a organiza��o do trabalho em alinhamento com os processos visando ao alto desempenho e � inova��o.'),
(684,'Elaborar a organiza��o do trabalho em alinhamento com os valores visando ao alto desempenho e � inova��o.'),
(684,'Elaborar a organiza��o do trabalho em alinhamento com a estrat�gia da organiza��o, visando ao alto desempenho e � inova��o.'),
(684,'Implementar a organiza��o do trabalho em alinhamento com o modelo de neg�cio, visando ao alto desempenho e � inova��o.'),
(684,'Implementar a organiza��o do trabalho em alinhamento com os processos visando ao alto desempenho e � inova��o.'),
(684,'Implementar a organiza��o do trabalho em alinhamento com os valores visando ao alto desempenho e � inova��o.'),
(684,'Implementar a organiza��o do trabalho em alinhamento com a estrat�gia da organiza��o, visando ao alto desempenho e � inova��o.'),
(685,'Selecionar e contratar, internamente, pessoas para a for�a de trabalho.'),
(685,'Selecionar e contratar, externamente, pessoas para a for�a de trabalho.'),
(686,'Integrar pessoas rec�m-contratadas � cultura organizacional, visando prepar�-las para o pleno exerc�cio das suas fun��es.'),
(687,'Avaliar o desempenho das pessoas e das equipes de modo a estimular a obten��o de metas de alto desempenho.'),
(687,'Avaliar o desempenho das pessoas e das equipes de modo a estimular a cultura da excel�ncia na organiza��o.'),
(687,'Avaliar o desempenho das pessoas e das equipes de modo a estimular o desenvolvimento profissional das mesmas.'),
(688,'Estimular o alcance de metas, atrav�s da remunera��o.'),
(688,'Estimular o alcance de metas, atrav�s do reconhecimento.'),
(688,'Estimular o alcance de metas, atrav�s dos incentivos.'),
(689,'Identificar as necessidades de capacita��o e desenvolvimento das pessoas, visando ao �xito de estrat�gias.'),
(689,'Identificar as necessidades de capacita��o e desenvolvimento das pessoas, visando � forma��o da cultura da excel�ncia.'),
(689,'Identificar as necessidades de capacita��o e desenvolvimento das pessoas, visando � melhoria do desempenho individual.'),
(690,'Conceber a forma de realiza��o dos programas de desenvolvimento, considerando as necessidades identificadas.'),
(690,'Conceber a forma de realiza��o dos programas de capacita��o, considerando as necessidades identificadas.'),
(691,'Avaliar a efic�cia dos programas de capacita��o em rela��o ao alcance dos objetivos estrat�gicos da organiza��o.'),
(691,'Avaliar a efic�cia dos programas de capacita��o em rela��o ao alcance dos objetivos operacionais da organiza��o.'),
(692,'Promover o desenvolvimento integral das pessoas como indiv�duos.'),
(692,'Promover o desenvolvimento integral das pessoas como cidad�os.'),
(692,'Promover o desenvolvimento integral das pessoas como profissionais.'),
(693,'Identificar os perigos relacionados � sa�de ocupacional.'),
(693,'Identificar os perigos relacionados � seguran�a.'),
(693,'Tratar os riscos relacionados � sa�de ocupacional.'),
(693,'Tratar os riscos relacionados � seguran�a.'),
(694,'Identificar as necessidades das pessoas da for�a de trabalho e do mercado para o desenvolvimento de pol�ticas e programas de pessoal e dos benef�cios a elas oferecidos.'),
(694,'Identificar as expectativas das pessoas da for�a de trabalho e do mercado para o desenvolvimento de pol�ticas e programas de pessoal e dos benef�cios a elas oferecidos.'),
(694,'Analisar as necessidades das pessoas da for�a de trabalho e do mercado para o desenvolvimento de pol�ticas e programas de pessoal e dos benef�cios a elas oferecidos.'),
(694,'Analisar as expectativas das pessoas da for�a de trabalho e do mercado para o desenvolvimento de pol�ticas e programas de pessoal e dos benef�cios a elas oferecidos.'),
(694,'Utilizar as necessidades das pessoas da for�a de trabalho e do mercado para o desenvolvimento de pol�ticas e programas de pessoal e dos benef�cios a elas oferecidos.'),
(694,'Utilizar as expectativas das pessoas da for�a de trabalho e do mercado para o desenvolvimento de pol�ticas e programas de pessoal e dos benef�cios a elas oferecidos.'),
(695,'Avaliar o bem-estar das pessoas.'),
(695,'Avaliar a satisfa��o das pessoas.'),
(695,'Avaliar o comprometimento das pessoas.'),
(695,'Desenvolver o bem-estar das pessoas.'),
(695,'Desenvolver a satisfa��o das pessoas.'),
(695,'Desenvolver o comprometimento das pessoas.'),
(696,'Manter um clima organizacional favor�vel � criatividade das pessoas.'),
(696,'Manter um clima organizacional favor�vel � inova��o das pessoas.'),
(696,'Manter um clima organizacional favor�vel � excel�ncia no desenpenho das pessoas.'),
(696,'Manter um clima organizacional favor�vel ao desenvolvimento profissional das pessoas.'),
(696,'Manter um clima organizacional favor�vel � criatividade das equipes.'),
(696,'Manter um clima organizacional favor�vel � inova��o das equipes.'),
(696,'Manter um clima organizacional favor�vel � excel�ncia no desenpenho das equipes.'),
(696,'Manter um clima organizacional favor�vel ao desenvolvimento profissional das equipes.'),
(697,'Colaborar para a melhoria da qualidade de vida das pessoas fora do ambiente de trabalho.'),
(698,'Determinar os requisitos aplic�veis aos produtos considerando-se as necessidades dos clientes, e sua import�ncia relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplic�veis aos processos principais do neg�cio considerando-se as necessidades dos clientes, e sua import�ncia relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplic�veis aos processos de apoio do neg�cio considerando-se as necessidades dos clientes, e sua import�ncia relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplic�veis aos produtos considerando-se as expectativas dos clientes, e sua import�ncia relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplic�veis aos processos principais do neg�cio considerando-se as expectativas dos clientes, e sua import�ncia relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplic�veis aos processos de apoio considerando-se as expectativas dos clientes, e sua import�ncia relativa, e de outras partes interessadas.'),
(699,'Desenvolver novos produtos visando ao atendimento de requisitos estabelecidos.'),
(699,'Desenvolver � supera��o de requisitos estabelecidos ao atendimento ou � supera��o de requisitos estabelecidos.'),
(700,'Projetar os processos principais do neg�cio, visando ao atendimento de requisitos estabelecidos.'),
(700,'Projetar os processos de apoio do neg�cio, visando ao atendimento de requisitos estabelecidos.'),
(700,'Projetar os processos principais do neg�cio, visando � supera��o de requisitos estabelecidos.'),
(700,'Projetar os processos de apoio do neg�cio, visando � supera��o de requisitos estabelecidos.'),
(701,'Avaliar o potencial de id�ias criativas para que convertam-se em inova��es.'),
(701,'Avaliar o potencial de id�ias criativas para que convertam-se em produtos.'),
(701,'Avaliar o potencial de id�ias criativas para que convertam-se em processos.'),
(702,'Assegurar o atendimento dos requisitos aplic�veis aos processos principais do neg�cio.'),
(702,'Assegurar o atendimento dos requisitos aplic�veis aos processos de apoio do neg�cio.'),
(703,'Analisar os produtos.'),
(703,'Analisar os os processos principais do neg�cio.'),
(703,'Analisar os processos de apoio do neg�cio.'),
(703,'Melhorar os produtos.'),
(703,'Melhorar os os processos principais do neg�cio.'),
(703,'Melhorar os processos de apoio do neg�cio.'),
(704,'Investigar as caracter�sticas de produtos de concorrentes ou de outras organiza��es de refer�ncia para melhorar os pr�prios.'),
(704,'Investigar as caracter�sticas de processos principais do neg�cio de concorrentes ou de outras organiza��es de refer�ncia para melhorar os pr�prios.'),
(704,'Investigar as caracter�sticas de processos de apoio de concorrentes ou de outras organiza��es de refer�ncia para melhorar os pr�prios.'),
(705,'Desenvolver a sua cadeia de suprimentos imediata e nela identificar potenciais fornecedores e parceiros visando assegurar a continuidade de fornecimento no longo prazo e agregar valor ao neg�cio.'),
(705,'Desenvolver a sua cadeia de suprimentos imediata e nela identificar potenciais fornecedores e parceiros visando melhorar o desempenho e agregar valor ao neg�cio.'),
(705,'Desenvolver a sua cadeia de suprimentos imediata e nela identificar potenciais fornecedores e parceiros visando promover o desenvolvimento sustent�vel da pr�pria cadeia e agregar valor ao neg�cio.'),
(706,'Identificar as necessidades e expectativas dos fornecedores, para a defini��o e a melhoria das pol�ticas e dos programas relativos aos fornecedores.'),
(706,'Analisar as necessidades e expectativas dos fornecedores para a defini��o e a melhoria das pol�ticas e dos programas relativos aos fornecedores.'),
(706,'Utilizar as necessidades e expectativas dos fornecedores para a defini��o e a melhoria das pol�ticas e dos programas relativos aos fornecedores.'),
(707,'Qualificar os fornecedores.'),
(707,'Selecionar os fornecedores.'),
(708,'Assegurar o atendimento aos requisitos da organiza��o por parte dos fornecedores.'),
(709,'Estimular a melhoria nos processos de suprimento e nos produtos supridos pelos fornecedores.'),
(709,'Estimular a inova��o nos processos de suprimento e nos produtos supridos pelos fornecedores.'),
(710,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os valores e princ�pios organizacionais, incluindo os relativos � responsabilidade socioambiental.'),
(710,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os valores e princ�pios organizacionais, incluindo os relativos � sa�de.'),
(710,'Envolver os fornecedores que atuam diretamente nos processos da organiza��o com os valores e princ�pios organizacionais, incluindo os relativos � seguran�a.'),
(710,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o  com os valores e princ�pios organizacionais, incluindo os relativos � responsabilidade socioambiental.'),
(710,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o  com os valores e princ�pios organizacionais, incluindo os relativos � sa�de.'),
(710,'Comprometer os fornecedores que atuam diretamente nos processos da organiza��o  com os valores e princ�pios organizacionais, incluindo os relativos � seguran�a.'),
(710,'Envolver fornecedores com os valores da organiza��o'),
(710,'Envolver fornecedores com os princ�pios organizacionais.'),
(711,'Determinar os requisitos de desempenho econ�mico-financeiro da organiza��o.'),
(711,'Gerenciar os aspectos que causam impacto na sustentabilidade econ�mica do neg�cio.'),
(712,'Assegurar os recursos financeiros necess�rios para atender �s necessidades operacionais.'),
(713,'Definir os recursos financeiros visando a dar suporte para as estrat�gias.'),
(713,'Definir os recursos financeiros visando a dar suporte para os planos de a��o.'),
(713,'Avaliar os recursos financeiros visando a dar suporte para as estrat�gias.'),
(713,'Avaliar os recursos financeiros visando a dar suporte para os planos de a��o.'),
(714,'Quantificar os riscos financeiros da organiza��o.'),
(714,'Monitorar os riscos financeiros da organiza��o.'),
(715,'Elaborar o or�amento visando assegurar o atendimento dos n�veis esperados de desempenho.'),
(715,'Controlar o or�amento visando assegurar o atendimento dos n�veis esperados de desempenho.'),
(716,'Apresentar os resultados dos principais indicadores relativos � gest�o econ�mico-financeira, classificando-os segundo os grupos de:<br>estrutura, liquidez, atividade e rentabilidade. Estratificar os resultados por unidades ou filiais, quando aplic�veis.'),
(717,'Apresentar os resultados dos principais indicadores relativos aos clientes e aos mercados, incluindo os referentes � imagem da organiza��o. Estratificar por grupos de clientes, segmentos de mercado ou tipos de produtos, quando aplic�veis.'),
(718,'Apresentar os resultados dos principais indicadores relativos � sociedade, incluindo os relativos � responsabilidade socioambiental e ao desenvolvimento social. Estratificar os resultados por instala��es ou por comunidades, quando aplic�vel.'),
(719,'Apresentar os resultados dos principais indicadores relativos �s pessoas, incluindo os relativos aos sistemas de trabalho, � capacita��o e desenvolvimento e � qualidade de vida e os de lideran�a de pessoas e de promo��o da cultura da excel�ncia. Estratificar os resultados por grupos de pessoas da for�a de trabalho, fun��es na organiza��o e, quando aplic�vel, por instala��es.'),
(720,'Apresentar os resultados dos indicadores relativos aos produtos, de processos principais do neg�cio e processos de apoio e de processos de gest�o transversais n�o pertinentes aos demais itens.'),
(721,'Apresentar os resultados dos principais indicadores relativos aos produtos recebidos dos fornecedores e � gest�o de fornecedores. Estratificar os resultados por grupos de fornecedores ou tipos de produtos adquiridos, quando aplic�veis.');