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
UPDATE pratica_modelo SET pratica_modelo_nome='Exército 2012 500 pontos', pratica_modelo_ordem=7 WHERE pratica_modelo_id=7;

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

(99,'Tomar as principais decisões, assegurando o envolvimento de todas as partes interessadas.'),
(99,'Tomar as principais decisões, assegurando a transparência.'),
(99,'Tomar as principais decisões, assegurando a governabilidade.'),

(99,'Comunicar as principais decisões, assegurando o envolvimento de todas as partes interessadas.'),
(99,'Comunicar as principais decisões, assegurando a transparência.'),
(99,'Comunicar as principais decisões, assegurando a governabilidade.'),

(99,'Implementar as principais decisões, assegurando o envolvimento de todas as partes interessadas.'),
(99,'Implementar as principais decisões, assegurando a transparência.'),
(99,'Implementar as principais decisões, assegurando a governabilidade.'),

(100,'Estabelecer os valores e os princípios organizacionais necessários à criação de valor para todas as partes interessadas.'),
(100,'Estabelecer os valores e os princípios organizacionais necessários ao desenvolvimento sustentável.'),

(100,'Atualizar os valores e os princípios organizacionais necessários à criação de valor para todas as partes interessadas.'),
(100,'Atualizar os valores e os princípios organizacionais necessários ao desenvolvimento sustentável.'),

(101,'Identificar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Identificar os riscos organizacionais mais significativos que possam afetar a capacidade da organização de alcançar os seus objetivos estratégicos.'),
(101,'Identificar os riscos organizacionais mais significativos que possam afetar capacidade de realizar sua missão.'),

(101,'Classificar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Classificar os riscos organizacionais mais significativos que possam afetar a capacidade da organização de alcançar os seus objetivos estratégicos.'),
(101,'Classificar os riscos organizacionais mais significativos que possam afetar capacidade de realizar sua missão.'),

(101,'Analisar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Analisar os riscos organizacionais mais significativos que possam afetar a capacidade da organização de alcançar os seus objetivos estratégicos.'),
(101,'Analisar os riscos organizacionais mais significativos que possam afetar capacidade de realizar sua missão.'),

(101,'Tratar os riscos organizacionais mais significativos que possam afetar a governabilidade.'),
(101,'Tratar os riscos organizacionais mais significativos que possam afetar a capacidade da organização de alcançar os seus objetivos estratégicos.'),
(101,'Tratar os riscos organizacionais mais significativos que possam afetar a capacidade de realizar sua missão.'),

(102,'Prestar contas de seus atos a quem a elegeu ou designou.'),
(102,'Prestar contas de seus atos aos órgãos de controle.'),

(102,'Prestar contas dos resultados a quem a elegeu ou designou.'),
(102,'Prestar contas dos resultados aos órgãos de controle.'),

(103,'Disseminar os princípios da administração pública na organização.'),
(103,'Disseminar os valores da administração pública na organização.'),
(103,'Disseminar as diretrizes de governo na organização.'),

(103,'Internalizar os princípios da administração pública na organização.'),
(103,'Internalizar os valores da administração pública na organização.'),
(103,'Internalizar as diretrizes de governo na organização.'),

(104,'Estabelecer um exemplo a ser seguido.'),
(104,'Buscar novas oportunidades para a organização.'),
(104,'Promover o comprometimento com todas as partes interessadas.'),

(105,'Disseminar os princípios organizacionais à força de trabalho e, quando pertinente, às demais partes interessadas.'),
(105,'Internalizar os princípios organizacionais à força de trabalho e, quando pertinente, às demais partes interessadas.'),

(106,'Incentivar o comprometimento de todos com a cultura da excelência.'),

(107,'Avaliar os líderes atuais e potenciais em relação às competências desejadas pela organização.'),
(107,'Desenvolver os líderes atuais e potenciais em relação às competências desejadas pela organização.'),

(108,'Conduzir a implementação do sistema de gestão da organização, visando assegurar o atendimento aos requisitos de todas as partes interessadas.'),

(109,'Estimular o aprendizado na organização'),

(110,'Analisar as necessidades de informações comparativas para avaliar o desempenho da organização.'),

(111,'Analisar os desempenhos dos diversos níveis da organização, considerando as informações comparativas.'),
(111,'Analisar os desempenhos dos diversos níveis da organização, considerando o atendimento aos principais requisitos das partes interessadas.'),

(111,'Analisar o desempenho integrado de toda a organização, considerando as informações comparativas.'),
(111,'Analisar o desempenho integrado de toda a organização, considerando o atendimento aos principais requisitos das partes interessadas.'),

(112,'Avaliar o êxito das estratégias a partir das conclusões da análise do seu desempenho.'),
(112,'Avaliar o alcance dos respectivos objetivos da organização a partir das conclusões da análise do seu desempenho.'),

(113,'Comunicar as decisões decorrentes da análise do desempenho da organização à força de trabalho, em todos os níveis da organização.'),
(113,'Comunicar as decisões decorrentes da análise do desempenho da organização a outras partes interessadas, quando pertinente.'),

(114,'Acompanhar a implementação das decisões decorrentes da análise do desempenho da organização.'),

(115,'Formular os processos das políticas públicas, quando pertinente.'),

(116,'Formular os processos das estratégias da organização.'),

(117,'Considerar os aspectos relativos ao ambiente externo no processo de formulação das estratégias.'),

(118,'Realizar a análise do ambiente interno.'),

(119,'Avaliar as estratégias.'),
(119,'Selecionar as estratégias.'),

(120,'Envolver as áreas da organização nos processos de formulação de estratégias.'),
(120,'Envolver as partes interessadas, quando pertinente, nos processos de formulação de estratégias.'),

(121,'Comunicar as estratégias às partes interessadas pertinentes para o estabelecimento de compromissos mútuos.'),

(122,'Definir os indicadores para a avaliação da operacionalização das estratégias, e definir os respectivos planos de ação.'),
(122,'Estabelecer as metas de curto e longo prazos, e definir os respectivos planos de ação.'),

(123,'Desdobrar as metas estabelecidas para as áreas da organização, assegurando a coerência entre os indicadores utilizados na avaliação da implementação das estratégias e aqueles utilizados na avaliação do desempenho dos processos.'),

(124,'Desdobrar os planos de ação para as áreas da organização, assegurando a coerência com as estratégias selecionadas.'),
(124,'Desdobrar os planos de ação para as áreas da organização, assegurando a consistência entre os respectivos planos.'),

(125,'Alocar os diferentes recursos para assegurar a implementação dos planos de ação.'),

(126,'Comunicar as metas para a força de trabalho e, quando pertinente, para as demais partes interessadas.'),
(126,'Comunicar os indicadores para a força de trabalho e, quando pertinente, para as demais partes interessadas.'),
(126,'Comunicar os planos de ação para a força de trabalho e, quando pertinente, para as demais partes interessadas.'),

(127,'Realizar o monitoramento da implementação dos planos de ação.'),

(128,'Identificar os cidadãos-usuários da organização'),
(128,'Classificar os cidadãos-usuários da organização por tipos ou grupos.'),

(129,'Identificar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, e de ex-usuários, quando pertinente, para definição e melhoria dos produtos da organização.'),
(129,'Identificar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, e de ex-usuários, quando pertinente, para definição e melhoria dos serviços da organização.'),
(129,'Identificar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, e de ex-usuários, quando pertinente, para definição e melhoria processos da organização.'),

(130,'Divulgar os produtos e serviços da organização aos cidadãos de forma a criar credibilidade, confiança e imagem positiva.'),
(130,'Divulgar os padrões de atendimento da organização aos cidadãos de forma a criar credibilidade, confiança e imagem positiva.'),
(130,'Divulgar as ações de melhoria da organização aos cidadãos de forma a criar credibilidade, confiança e imagem positiva.'),

(131,'Identificar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus serviços.'),
(131,'Identificar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus produtos.'),
(131,'Identificar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e suas ações.'),

(131,'Avaliar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus serviços.'),
(131,'Avaliar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus produtos.'),
(131,'Avaliar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e suas ações.'),

(132,'Avaliar a imagem da organização perante os cidadãos-usuários.'),

(133,'Avaliar o atendimento ao universo potencial dos cidadãos-usuários identificados.'),

(134,'Definir aos cidadãos-usuários os principais canais de acesso para solicitarem informações ou esclarecimentos sobre os serviços e produtos.'),
(134,'Definir aos cidadãos-usuários os principais canais de acesso para comunicarem suas sugestões.'),
(134,'Definir aos cidadãos-usuários os principais canais de acesso para comunicarem suas reclamações.'),

(135,'Tratar as reclamações e sugestões, formais e informais dos cidadãos-usuários, visando assegurar a resposta rápida e eficaz e o seu aproveitamento por toda a organização.'),

(136,'Acompanhar os serviços, recentemente prestados, junto aos cidadãos-usuários para permitir à organização gerar soluções rápidas e eficazes.'),
(136,'Acompanhar os serviços, recentemente prestados, junto aos cidadãos-usuários para permitir à organização evitar problemas de relacionamento.'),
(136,'Acompanhar os serviços, recentemente prestados, junto aos cidadãos-usuários para permitir à organização atender às expectativas dos cidadãos-usuários.'),

(136,'Acompanhar os produtos, recentemente entregues, junto aos cidadãos-usuários para permitir à organização gerar soluções rápidas e eficazes.'),
(136,'Acompanhar os produtos, recentemente entregues, junto aos cidadãos-usuários para permitir à organização evitar problemas de relacionamento.'),
(136,'Acompanhar os produtos, recentemente entregues, junto aos cidadãos-usuários para permitir à organização atender às expectativas dos cidadãos-usuários.'),

(137,'Avaliar a satisfação e a insatisfação dos cidadãos-usuários em relação aos produtos da organização e aos da concorrência, quando pertinente.'),
(137,'Avaliar a satisfação e a insatisfação dos cidadãos-usuários em relação aos serviços da organização e aos da concorrência, quando pertinente.'),

(138,'Utilizar as informações obtidas dos cidadãos-usuários para melhorar o seu nível de satisfação.'),

(139,'Identificar os aspectos e tratar os impactos sociais e ambientais dos produtos, desde o projeto até a disposição final, sobre os quais tenha infuência.'),
(139,'Identificar os aspectos e tratar os impactos sociais e ambientais dos serviços, desde o projeto até a disposição final, sobre os quais tenha infuência.'),
(139,'Identificar os aspectos e tratar os impactos sociais e ambientais dos processos, desde o projeto até a disposição final, sobre os quais tenha infuência.'),
(139,'Identificar os aspectos e tratar os impactos sociais e ambientais das instalações, desde o projeto até a disposição final, sobre os quais tenha infuência.'),

(140,'Comunicar os impactos sociais e ambientais dos serviços, assim como as respectivas políticas, ações e resultados à sociedade.'),
(140,'Comunicar os impactos sociais e ambientais dos produtos, assim como as respectivas políticas, ações e resultados à sociedade.'),
(140,'Comunicar os impactos sociais e ambientais dos processos, assim como as respectivas políticas, ações e resultados à sociedade.'),
(140,'Comunicar os impactos sociais e ambientais das instalações, assim como as respectivas políticas, ações e resultados à sociedade.'),

(141,'Tratar as pendências ou eventuais sanções referentes aos requisitos legais, relatando as atualmente existentes.'),
(141,'Tratar as pendências ou eventuais sanções referentes aos requisitos regulamentares, relatando as atualmente existentes.'),
(141,'Tratar as pendências ou eventuais sanções referentes aos requisitos éticos ou contratuais, relatando as atualmente existentes.'),

(142,'Promover as ações que envolvam a conservação de recursos não-renováveis a preservação dos ecossistemas.'),
(142,'Promover as ações que envolvam a conservação de recursos não-renováveis a otimização do uso de recursos renováveis.'),

(143,'Conscientizar a força de trabalho nas questões relativas à responsabilidade socioambiental.'),
(143,'Conscientizar os fornecedores nas questões relativas à responsabilidade socioambiental.'),
(143,'Conscientizar as demais partes interessadas nas questões relativas à responsabilidade socioambiental.'),

(143,'Envolver a força de trabalho nas questões relativas à responsabilidade socioambiental.'),
(143,'Envolver os fornecedores nas questões relativas à responsabilidade socioambiental.'),
(143,'Envolver as demais partes interessadas nas questões relativas à responsabilidade socioambiental.'),

(144,'Direcionar os esforços para o fortalecimento da sociedade executando projetos sociais, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento local, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(144,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos sociais, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade apoiaando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento local, quando pertinente.'),
(144,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(145,'Divulgar ofcialmente os atos e as informações sobre os planos da organização.'),
(145,'Divulgar ofcialmente os atos e as informações sobre os programas da organização.'),
(145,'Divulgar ofcialmente os atos e as informações sobre os projetos da organização.'),

(146,'Tornar públicas as informações relativas à execução física da organização.'),
(146,'Tornar públicas as informações relativas à execução orçamentária da organização.'),
(146,'Tornar públicas as informações relativas à execução financeira da organização.'),
(146,'Tornar públicas as informações relativas à gestão da organização.'),

(146,'Democratizar o acesso as informações relativas à execução física da organização.'),
(146,'Democratizar o acesso as informações relativas à execução orçamentária da organização.'),
(146,'Democratizar o acesso as informações relativas à execução financeira da organização.'),
(146,'Democratizar o acesso as informações relativas à gestão da organização.'),

(147,'Estimular a sociedade a participar no controle dos resultados organizacionais.'),
(147,'Orientar a sociedade a participar no controle dos resultados organizacionais.'),

(148,'Estimular o exercício da responsabilidade social da força de trabalho, no cumprimento de seu papel de agente público.'),
(148,'Estimular o exercício da responsabilidade social da força de trabalho, no comportamento ético em todos os níveis.'),

(149,'Disponibilizar os canais de comunicação para receber eventuais denúncias de violação da ética.'),

(150,'Identificar as necessidades da sociedade em relação ao setor de atuação'),

(150,'Identificar as necessidades da sociedade em relação ao setor de atuação da organização e transformar em requisitos para a formulação das políticas públicas, quando pertinente.'),
(150,'Identificar as necessidades da sociedade em relação ao setor de atuação da organização e transformar em requisitos para execução das políticas públicas, quando pertinente.'),

(151,'Contribuir na formulação das políticas públicas do seu setor.'),
(151,'Atuar na execução das políticas públicas do seu setor.'),

(152,'Divulgar as políticas públicas e seus respectivos objetivos para a sociedade.'),

(153,'Monitorar a execução das políticas públicas, em seu nível de atuação.'),
(153,'Avaliar a execução das políticas públicas, em seu nível de atuação.'),

(154,'Avaliar a satisfação da sociedade e das demais partes interessadas com a implementação das políticas públicas, em seu nível de atuação.'),

(155,'Identificar as necessidades de coleta e tratamento, e guardar informações para apoiar a gestão organizacional.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informações para apoiar as operações diárias.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informações para apoiar as as estratégias.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informações para apoiar o progresso dos planos de ação.'),
(155,'Identificar as necessidades de coleta e tratamento, e guardar informações para subsidiar a tomada de decisão em todos os níveis e áreas da organização.'),

(156,'Definir os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(156,'Definir os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),

(156,'Desenvolver os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(156,'Desenvolver os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),

(156,'Implantar os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(156,'Implantar os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),

(156,'Atualizar os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(156,'Atualizar os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),

(157,'Estabelecer a memória administrativa da organização.'),
(157,'Manter a memória administrativa da organização.'),

(158,'Utilizar a gestão da informação para apoiar o cumprimento da missão institucional e promover a integração da organização com seus cidadãos-usuários.'),
(158,'Utilizar a gestão da informação para apoiar o cumprimento da missão institucional e promover a integração da organização com a sociedade.'),
(158,'Utilizar a gestão da informação para apoiar o cumprimento da missão institucional e promover a integração da organização com seus fornecedores.'),
(158,'Utilizar a gestão da informação para apoiar o cumprimento da missão institucional e promover a integração da organização com seus parceiros.'),

(159,'Colocar à disposição dos públicos internos e externos as informações necessárias à organização, incluindo cidadãos-usuários.'),
(159,'Colocar à disposição dos públicos internos e externos as informações necessárias à organização, incluindo fornecedores.'),
(159,'Colocar à disposição dos públicos internos e externos as informações necessárias à organização, incluindo parceiros.'),

(160,'Gerenciar a segurança das informações.'),

(161,'Identificat as organizações consideradas como um referencial comparativo pertinente.'),

(162,'Identificar as fontes, obtidas e manter atualizadas as informações comparativas.'),

(163,'Promover melhorias no desempenho.'),

(163,'Utilizar as informações obtidas para melhorar o conhecimento dos processos organizacionais.'),
(163,'Utilizar as informações obtidas para estabelecer metas ousadas.'),
(163,'Utilizar as informações obtidas para promover melhorias no desempenho da organização.'),

(164,'Desenvolver o conhecimento na organização.'),
(164,'Compartilhar o conhecimento na organização.'),

(165,'Manter o conhecimento.'),
(165,'Proteger o conhecimento.'),

(166,'Assegurar que a gestão do conhecimento seja utilizada para melhorar os processos da organização.'),

(166,'Assegurar que a gestão do conhecimento seja utilizada para melhorar produtos da organização.'),

(166,'Assegurar que a gestão do conhecimento seja utilizada para melhorar serviços da organização.'),

(167,'Identificar os ativos intangíveis da organização.'),
(167,'Desenvolver os ativos intangíveis da organização.'),
(167,'Mensurar os ativos intangíveis da organização.'),

(168,'Definir a organização do trabalho, visando o alto desempenho da organização.'),
(168,'Implementar a organização do trabalho, visando o alto desempenho da organização.'),

(169,'Selecionar as pessoas para preenchimento de cargos e funções, em consonância com as estratégias da organização.'),
(169,'Selecionar as pessoas para preenchimento de cargos e funções, em consonância com os objetivos da organização.'),
(169,'Selecionar as pessoas para preenchimento de cargos e funções, em consonância com a missão da organização.'),

(170,'Definir os canais de interlocução com a força de trabalho da organização, quando pertinente.'),
(170,'Definir a negociação com a força de trabalho da organização, quando pertinente.'),
(170,'Disponibilizar os canais de interlocução com a força de trabalho da organização, quando pertinente.'),
(170,'Disponibilizar a negociação com a força de trabalho da organização, quando pertinente.'),

(171,'Estimular a integração das pessoas e das equipes.'),
(171,'Estimular a cooperação das pessoas e das equipes.'),

(172,'Gerenciar o desempenho das pessoas, de forma a estimular a obtenção de metas de alto desempenho.'),
(172,'Gerenciar o desempenho das pessoas, de forma a estimular a cultura da excelência na organização.'),
(172,'Gerenciar o desempenho das pessoas, de forma a estimular o desenvolvimento profssional.'),

(172,'Gerenciar o desempenho das equipes, de forma a estimular a obtenção de metas de alto desempenho.'),
(172,'Gerenciar o desempenho das equipes, de forma a estimular a cultura da excelência na organização.'),
(172,'Gerenciar o desempenho das equipes, de forma a estimular o desenvolvimento profssional.'),

(173,'Estimular o alcance de metas de alto desempenho através do sistema de remuneração para as pessoas.'),
(173,'Estimular o aprendizado através do sistema de remuneração para as pessoas.'),
(173,'Estimular a cultura da excelência através do sistema de remuneração para as pessoas.'),

(173,'Estimular o alcance de metas de alto desempenho através do sistema de reconhecimento para as pessoas.'),
(173,'Estimular o aprendizado através do sistema de reconhecimento para as pessoas.'),
(173,'Estimular a cultura da excelência através do sistema de reconhecimento para as pessoas.'),

(173,'Estimular o alcance de metas de alto desempenho através do sistema de incentivos para as pessoas.'),
(173,'Estimular o aprendizado através do sistema de incentivos para as pessoas.'),
(173,'Estimular a cultura da excelência através do sistema de incentivos para as pessoas.'),

(174,'Identificar as necessidades de capacitação'),
(174,'Identificar as necessidades de desenvolvimento'),

(175,'Compatibilizar as necessidades de capacitação das pessoas com as necessidades da organização, para efeito da definição dos programas de capacitação.'),
(175,'Compatibilizar as necessidades de capacitação das pessoas com as necessidades da organização, para efeito da definição dos programas de desenvolvimento.'),

(175,'Compatibilizar as necessidades de desenvolvimento das pessoas com as necessidades da organização, para efeito da definição dos programas de capacitação.'),
(175,'Compatibilizar as necessidades de desenvolvimento das pessoas com as necessidades da organização, para efeito da definição dos programas de desenvolvimento.'),

(176,'Abordar a cultura da excelência através dos programas de capacitação.'), 
(176,'Contribuir para consolidar o aprendizado organizacional através dos programas de capacitação.'), 

(177,'Conceber a forma de realização dos programas de capacitação considerando as necessidades da organização.'),
(177,'Conceber a forma de realização dos programas de capacitação considerando as necessidades das pessoas.'),
(177,'Conceber a forma de realização dos programas de capacitação considerando os recursos disponíveis.'),

(177,'Conceber a forma de realização dos programas de desenvolvimento considerando as necessidades da organização.'),
(177,'Conceber a forma de realização dos programas de desenvolvimento considerando as necessidades das pessoas..'),
(177,'Conceber a forma de realização dos programas de desenvolvimento considerando os recursos disponíveis.'),


(178,'Avaliar as habilidades em relação à sua utilidade na execução do trabalho da organização.'),
(178,'Avaliar os conhecimentos adquiridos em relação à sua utilidade na execução do trabalho da organização.'),

(178,'Avaliar as habilidades em relação à sua efcácia na consecução das estratégias da organização.'),
(178,'Avaliar os conhecimentos adquiridos em relação à sua efcácia na consecução das estratégias da organização.'),

(179,'Promover o desenvolvimento integral das pessoas, como indivíduos, cidadãos e profssionais.'),

(180,'Identificar os perigos relacionados à saúde ocupacional.'),
(180,'Identificar os perigos relacionados à segurança.'),
(180,'Identificar os perigos relacionados à ergonomia.'),

(180,'Tratar os riscos relacionados à saúde ocupacional.'),
(180,'Tratar os riscos relacionados à segurança.'),
(180,'Tratar os riscos relacionados à ergonomia.'),

(181,'Identificar os fatores que afetam o bem-estar considerando os diferentes grupos de pessoas.'),
(181,'Identificar os fatores que afetam a satisfação considerando os diferentes grupos de pessoas.'),
(181,'Identificar os fatores que afetam a motivação considerando os diferentes grupos de pessoas.'),

(182,'Tratar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(182,'Tratar os fatores que afetam a satisfação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(182,'Tratar os fatores que afetam a motivação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),

(183,'Colaborar para a melhoria da qualidade de vida das pessoas e respectivas famílias fora do ambiente de trabalho.'),

(184,'Avaliar os fatores que afetam o bem-estar.'),
(184,'Avaliar os fatores que afetam a satisfação.'),
(184,'Avaliar os fatores que afetam a motivação.'),

(185,'Identificar os processos de apoio, considerando a missão institucional da organização.'),
(185,'Determinar os processos de apoio, considerando a missão institucional da organização.'),

(185,'Identificar os processos finalísticos, considerando a missão institucional da organização.'),
(185,'Determinar os processos finalísticos, considerando a missão institucional da organização.'),

(186,'Traduzir as necessidades dos cidadãos-usuários em requisitos aos projetos de serviços ou produtos e aos processos finalísticos.'),
(186,'Traduzir as necessidades da sociedade em requisitos aos projetos de serviços ou produtos e aos processos finalísticos.'),

(186,'Incorporar as necessidades dos cidadãos-usuários em requisitos aos projetos de serviços ou produtos e aos processos finalísticos.'),
(186,'Incorporar as necessidades da sociedade em requisitos e incorporar aos projetos de serviços ou produtos e aos processos finalísticos.'),

(187,'Projetar os processos finalísticos visando o cumprimento dos requisitos definidos.'),
(187,'Projetar os processos de apoio visando o cumprimento dos requisitos definidos.'),

(188,'Controlar os processos finalísticos.'),
(188,'Controlar os processos de apoio.'),

(189,'Refinar os processos finalísticos.'),
(189,'Refinar os processos de apoio.'),

(190,'Identificar potenciais fornecedores visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(190,'Identificar potenciais fornecedores visando melhorar o desempenho.'),
(190,'Identificar potenciais fornecedores visando assegurar o desenvolvimento sustentável de sua cadeia de suprimentos.'),

(190,'Desenvolver sua cadeia de suprimentos visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(190,'Desenvolver sua cadeia de suprimentos visando melhorar o seu desempenho.'),
(190,'Desenvolver sua cadeia de suprimentos visando assegurar o desenvolvimento sustentável de sua cadeia de suprimentos.'),

(191,'Realizar o processo de aquisição de bens de forma a assegurar a transparência.'),
(191,'Realizar o processo de aquisição de materiais de forma a assegurar a transparência.'),
(191,'Realizar o processo de aquisição de serviços de forma a assegurar a transparência.'),

(191,'Realizar o processo de aquisição de bens de forma a atender a legislação.'),
(191,'Realizar o processo de aquisição de materiais de forma a atender a legislação.'),
(191,'Realizar o processo de aquisição de serviços de forma a atender a legislação.'),

(192,'Assegurar a qualidade dos bens adquiridos.'),
(192,'Assegurar a qualidade dos produtos adquiridos.'),
(192,'Assegurar a qualidade dos serviços adquiridos.'),

(193,'Realizar a gestão dos bens materiais quando for pertinente.'),
(193,'Realizar a gestão dos bens patrimoniais quando for pertinente.'),
(193,'Realizar a gestão dos estoques, quando for pertinente.'),

(194,'Administrar o relacionamento com os fornecedores.'),

(195,'Avaliar os fornecedores e prontamente informar sobre seu desempenho.'),

(196,'Minimizar os custos associados à gestão do fornecimento.'),

(197,'Envolver os fornecedores que atuam diretamente nos processos da organização com os princípios organizacionais relativos à responsabilidade socioambiental, incluindo os aspectos da segurança e saúde.'),
(197,'Comprometer os fornecedores que atuam diretamente nos processos da organização com os princípios organizacionais relativos à responsabilidade socioambiental, incluindo os aspectos da segurança e saúde.'),

(198,'Elaborar a proposta orçamentária mais significativa que possa vir a afetar a execução de suas atividades.'),
(198,'Tratar as restrições e liberações de orçamento mais significativas que possam vir a afetar a execução de suas atividades.'),

(199,'Gerenciar os processos orçamentários para suportar as necessidades estratégicas da organização.'),
(199,'Gerenciar os processos orçamentários para suportar as necessidades operacionais da organização.'),

(199,'Gerenciar os processos financeiros para suportar as necessidades operacionais da organização.'),
(199,'Gerenciar os processos financeiros para suportar as necessidades estratégicas da organização.'),

(200,'Monitorar a execução orçamentária e os possíveis realinhamentos entre o orçamento, estratégias e objetivos da organização.'),
(200,'Monitorar a execução financeira e os possíveis realinhamentos entre o orçamento, estratégias e objetivos da organização.'),

(201,'Selecionar as melhores opções de investimentos e aplicações de recursos financeiros, quando pertinente.'),
(201,'Realizar captações de recursos financeiros, quando pertinente.'),

(202,'Acompanhar as operações que geram receita.'),

(203,'Administrar os  parâmetros orçamentários.'),
(203,'Administrar os  parâmetros financeiros.'),

(204,'Apresentar os resultados dos principais indicadores relativos aos cidadãos-usuários. Estratificar por grupos de cidadãos-usuários, segmentos de mercado ou tipos de produtos, quando aplicável.'),

(205,'Apresentar os resultados dos principais indicadores relativos à sociedade, incluindo os relativos à atuação socioambiental, à ética, ao controle social e às políticas públicas. Estratificar os resultados por instalações, quando aplicável.'),

(206,'Apresentar os resultados dos principais indicadores relativos à gestão orçamentária e financeira. Estratificar os resultados por unidades ou fliais, quando aplicável.'),

(207,'Apresentar os resultados dos principais indicadores relativos às pessoas, incluindo os relativos aos sistemas de trabalho, à capacitação e ao desenvolvimento e à qualidade de vida. Estratificar os resultados por grupos de pessoas da força de trabalho, funções na organização e, quando aplicável, por instalações.'),

(208,'Apresentar os resultados dos principais indicadores relativos aos produtos adquiridos e à gestão de relacionamento com os fornecedores. estratificar os resultados por grupos de fornecedores ou tipos de produtos adquiridos, quando aplicável.'),

(209,'Apresentar os resultados dos indicadores relativos ao produto/serviço e à gestão dos processos finalísticos e de apoio.'),

(210,'Identificar os riscos empresariais mais significativos que possam afetar o negócio.'),

(211,'Revisar os valores necessários à promoção da excelência.'),
(211,'Revisar os valores necessários à criação de valor para todas as partes interessadas.'),

(211,'Revisar os princípios organizacionais necessários à promoção da excelência.'),
(211,'Revisar os princípios organizacionais necessários à criação de valor para todas as partes interessadas.'),

(212,'Tratar as questões éticas nos relacionamentos internos da organização.'),
(212,'Tratar as questões éticas nos relacionamentos externos da organização.'),

(213,'Tomar as principais decisões.'),
(213,'Comunicar as principais decisões.'),
(213,'Implementar as principais decisões.'),

(214,'Prestar conta das suas ações a quem a elegeu, nomeou ou designou.'),
(214,'Prestar conta dos resultados alcançados a quem a elegeu, nomeou ou designou.'),

(215,'Exercer a liderança com as partes interessadas buscando a mobilização de todos para o êxito das estratégias.'),
(215,'Demonstrar comprometimento com os valores organizacionais estabelecidos, buscando a mobilização de todos para o êxito das estratégias.'),
(215,'Demonstrar comprometimento com os princípios organizacionais estabelecidos, buscando a mobilização de todos para o êxito das estratégias.'),

(216,'Comunicar os valores organizacionais para força de trabalho e, quando pertinentes, às demais partes interessadas.'),
(216,'Comunicar os princípios organizacionais para força de trabalho e, quando pertinentes, às demais partes interessadas.'),

(217,'Identificar  as pessoas com potencial de liderança para o exercício da liderança.'),
(217,'Preparar as pessoas com potencial de liderança para o exercício da liderança.'),

(218,'Avaliar os líderes atuais em relação às competências desejadas pela organização.'),
(218,'Desenvolver os líderes atuais em relação às competências desejadas pela organização.'),

(219,'Estabelecer os principais padrões de trabalho que orientam a execução adequada das práticas de gestão.'),

(220,'Verificar o cumprimento dos principais padrões de trabalho, promovendo o controle.'),

(221,'Avaliar as práticas de gestão, promovendo o aprendizado.'),
(221,'Melhorar as práticas de gestão, promovendo o aprendizado.'),

(221,'Avaliar os respectivos padrões de trabalho, promovendo o aprendizado.'),
(221,'Melhorar os respectivos padrões de trabalho, promovendo o aprendizado.'),

(222,'Identificar  as necessidades de informações comparativas para analisar o desempenho operacional da organização.'),
(222,'Identificar  as necessidades de informações comparativas para analisar o desempenho estratégico da organização.'),

(223,'Analisar o desempenho operacional considerando as informações comparativas.'),
(223,'Analisar o desempenho operacional considerando o atendimento aos principais requisitos das partes interessadas.'),
(223,'Analisar o desempenho operacional considerando as variáveis dos ambientes interno e externo.'),

(223,'Analisar o desempenho estratégico considerando as informações comparativas.'),
(223,'Analisar o desempenho estratégico considerando o atendimento aos principais requisitos das partes interessadas.'),
(223,'Analisar o desempenho estratégico considerando as variáveis dos ambientes interno e externo.'),

(224,'Comunicar as decisões decorrentes da análise do desempenho da organização à força de trabalho, em todos os níveis da organização e a outras partes interessadas, quando pertinentes.'),

(225,'Acompanhar a implementação das decisões decorrentes da análise do desempenho da organização.'),

(226,'Realizar a análise do ambiente externo.'),

(227,'Realizar a análise do ambiente interno.'),

(228,'Definir as estratégias.'),

(229,'Envolver as diversas áreas da organização nos processos de formulação das estratégias.'),

(230,'Definir os indicadores para a avaliação da implementação das estratégias e dos respectivos planos de ação.'),
(230,'Estabelecer as metas de curto e longo prazos e dos respectivos planos de ação.'),

(231,'Alocar os recursos para assegurar a implementação dos planos de ação.'),

(232,'Comunicar as estratégias às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(232,'Comunicar as metas às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(232,'Comunicar os planos de ação às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),

(233,'Realizar o monitoramento da implementação dos planos de ação.'),

(234,'Segmentar o mercado e definir os clientes-alvo nesses segmentos.'),

(235,'Identificar as necessidades dos clientes-alvo.'),
(235,'Tratar as necessidades dos clientes-alvo.'),

(235,'Identificar as expectativas dos clientes-alvo.'),
(235,'Tratar as expectativas dos clientes-alvo.'),

(236,'Divulgar os produtos da organização aos clientes e ao mercado de forma a criar credibilidade.'),
(236,'Divulgar os produtos da organização aos clientes e ao mercado de forma a criar confiança.'),
(236,'Divulgar os produtos da organização aos clientes e ao mercado de forma a criar imagem positiva.'),

(236,'Divulgar as marcas da organização aos clientes e ao mercado de forma a criar credibilidade.'),
(236,'Divulgar as marcas da organização aos clientes e ao mercado de forma a criar confiança.'),
(236,'Divulgar as marcas da organização aos clientes e ao mercado de forma a criar imagem positiva.'),


(237,'Avaliar a imagem da organização perante os clientes.'),

(238,'Definir os canais de relacionamento, considerando eventuais diferenças nos perfis dos clientes.'),
(238,'Divulgar os canais de relacionamento, considerando eventuais diferenças nos perfis dos clientes.'),

(239,'Tratar as solicitações, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(239,'Tratar as reclamações, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(239,'Tratar as sugestões, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),

(240,'Realizar acompanhamento das transações com novos clientes.'),
(240,'Realizar acompanhamento das transações com novos produtos entregues.'),

(241,'Avaliar a satisfação dos clientes e utilizar essas informações para promover ações de melhoria.'),
(241,'Avaliar a insatisfação dos clientes e utilizar essas informações para promover ações de melhoria.'),

(242,'Identificar os aspectos e tratar os impactos sociais adversos de seus produtos.'),
(242,'Identificar os aspectos e tratar os impactos sociais adversos de seus processos'),
(242,'Identificar os aspectos e tratar os impactos sociais adversos de suas instalações.'),

(242,'Identificar os aspectos e tratar os impactos ambientais adversos de seus produtos.'),
(242,'Identificar os aspectos e tratar os impactos ambientais adversos de seus processos'),
(242,'Identificar os aspectos e tratar os impactos ambientais adversos de suas instalações.'),

(243,'Comunicar à sociedade, incluindo as comunidades vizinhas, os impactos sociais e ambientais dos produtos da organização.'),
(243,'Comunicar à sociedade, incluindo as comunidades vizinhas, os impactos sociais e ambientais dos processos organização.'),
(243,'Comunicar à sociedade, incluindo as comunidades vizinhas, informações relativas à responsabilidade socio ambiental consideradas relevantes.'),

(244,'Identificar  os requisitos legais, aplicáveis a questões socioambientais.'),
(244,'Identificar  os requisitos regulamentares aplicáveis a questões socioambientais.'),
(244,'Identificar  os requisitos contratuais, aplicáveis a questões socioambientais.'),

(244,'Analisar os requisitos legais, aplicáveis a questões socioambientais.'),
(244,'Analisar os requisitos regulamentares aplicáveis a questões socioambientais.'),
(244,'Analisar os requisitos contratuais, aplicáveis a questões socioambientais.'),

(245,'Selecionar voluntariamente ações com vistas à preservação do ecossistemas.'),
(245,'Promover voluntariamente ações com vistas à preservação do ecossistemas.'),

(246,'Conscientizar as pessoas da força de trabalho das questões relativas à responsabilidade socioambiental.'),
(246,'Envolver as pessoas da força de trabalho das questões relativas à responsabilidade socioambiental.'),

(247,'Identificar  as necessidades da sociedade, incluindo as comunidades vizinhas em relação às instalações da organização.'),
(247,'Identificar  as expectativas da sociedade, incluindo as comunidades vizinhas em relação às instalações da organização.'),

(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento nacional.'),
(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento regional.'),
(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento local.'),
(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento setorial.'),

(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento nacional.'),
(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento regional.'),
(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento local.'),
(248,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, executando projetos sociais voltados para o desenvolvimento setorial.'),

(249,'Estimular a força de trabalho na implementação dos seus projetos sociais.'),
(249,'Estimular seus parceiros na implementação dos seus projetos sociais.'),

(249,'Estimular a força de trabalho no apoio aos seus projetos sociais.'),
(249,'Estimular seus parceiros no apoio aos seus projetos sociais.'),

(249,'Envolver a força de trabalho na implementação dos seus projetos sociais.'),
(249,'Envolver seus parceiros na implementação dos seus projetos sociais.'),

(249,'Envolver a força de trabalho no apoio aos seus projetos sociais.'),
(249,'Envolver seus parceiros no apoio aos seus projetos sociais.'),

(250,'Identificar as necessidades de informações para apoiar as operações diárias.'),
(250,'Identificar as necessidades de informações para apoiar a tomada de decisão em todos os níveis e áreas da organização.'),

(250,'Definir os sistemas de informação para apoiar as operações diárias.'),
(250,'Definir os sistemas de informação para apoiar a tomada de decisão em todos os níveis e áreas da organização.'),

(251,'Colocar as informações necessárias à disposição dos usuários.'),

(252,'Gerenciar a segurança das informações.'),

(253,'Identificar  as fontes de informações comparativas.'),

(254,'Obter as informações comparativas.'),
(254,'Manter atualizadas as informações comparativas.'),

(255,'Analisar as informações comparativas obtidas, visando sua adaptação à realidade da organização.'),

(256,'Identificar  os ativos intangíveis da organização.'),

(257,'Desenvolver os principais ativos intangíveis da organização.'),
(257,'Proteger os principais ativos intangíveis da organização.'),

(258,'Compartilhar o conhecimento da organização.'),
(258,'Reter o conhecimento da organização.'),

(259,'Definir a organização do trabalho.'),
(259,'Implementar a organização do trabalho.'),

(260,'Selecionar e contratar, internamente, pessoas para a força de trabalho.'),
(260,'Selecionar e contratar, externamente, pessoas para a força de trabalho.'),

(261,'Realizar a integração dos novos membros da força de trabalho, visando prepará-los para a execução das suas funções.'),

(262,'Avaliar o desempenho das pessoas da força de trabalho.'),

(263,'Estimular a busca por melhores resultados, através da remuneração.'),
(263,'Estimular a busca por melhores resultados, através do reconhecimento.'),
(263,'Estimular a busca por melhores resultados, através dos incentivos.'),

(264,'Identificar as necessidades de desenvolvimento, considerando as estratégias da organização.'),
(264,'Identificar as necessidades de desenvolvimento, considerando as necessidades das pessoas.'),

(265,'Conceber a forma de realização dos programas de desenvolvimento, considerando as necessidades identificadas.'),
(265,'Conceber a forma de realização dos programas de capacitação, considerando as necessidades identificadas.'),

(266,'Avaliar a eficácia dos programas de desenvolvimento.'),
(266,'Avaliar a eficácia dos programas de capacitação.'),

(267,'Identificar os perigos relacionados à saúde ocupacional.'),
(267,'Identificar os perigos relacionados à segurança.'),
(267,'Identificar os perigos relacionados à ergonomia.'),

(267,'Tratar os riscos relacionados à saúde ocupacional.'),
(267,'Tratar os riscos relacionados à segurança.'),
(267,'Tratar os riscos relacionados à ergonomia.'),

(268,'Identificar  os fatores que afetam o bem-estar das pessoas.'),
(268,'Identificar  os fatores que afetam a satisfação das pessoas.'),
(268,'Identificar  os fatores que afetam o comprometimento das pessoas.'),

(269,'Tratar os fatores que afetam o bem-estar das pessoas.'),
(269,'Tratar os fatores que afetam a satisfação das pessoas.'),
(269,'Tratar os fatores que afetam o comprometimento das pessoas.'),

(270,'Colaborar para a melhoria da qualidade de vida da sua força de trabalho fora do ambiente da organização.'),

(271,'Avaliar a satisfação das pessoas.'),

(272,'Determinar os requisitos aplicáveis aos processos principais do negócio, a partir das necessidades dos clientes.'),
(272,'Determinar os requisitos aplicáveis aos processos principais do negócio, a partir das necessidades das demais partes interessadas.'),

(272,'Determinar os requisitos aplicáveis aos processos principais do negócio, a partir das expectativas dos clientes.'),
(272,'Determinar os requisitos aplicáveis aos processos principais do negócio, a partir das expectativas das demais partes interessadas.'),

(273,'Projetar os processos principais do negócio, visando ao cumprimento dos requisitos estabelecidos.'),
(273,'Projetar os processos de apoio do negócio, visando ao cumprimento dos requisitos estabelecidos.'),

(274,'Controlar os processos principais do negócio, visando assegurar o atendimento dos requisitos aplicáveis.'),
(274,'Controlar os processos de apoio do negócio, visando assegurar o atendimento dos requisitos aplicáveis.'),

(275,'Analisar os processos principais do negócio.'),
(275,'Melhorar os processos principais do negócio.'),

(276,'Qualificar os fornecedores, considerando requisitos de desempenho.'),
(276,'Selecionar os fornecedores, considerando requisitos de desempenho.'),

(277,'Avaliar os fornecedores e seu desempenho.'),
(277,'Informar prontamente os fornecedores sobre seu desempenho.'),

(278,'Envolver os fornecedores que atuam diretamente nos processos da organização com os valores organizacionais, incluindo os aspectos relativos à segurança.'),
(278,'Envolver os fornecedores que atuam diretamente nos processos da organização com os valores organizacionais, incluindo os aspectos relativos à saúde.'),

(278,'Envolver os fornecedores que atuam diretamente nos processos da organização com os princípios organizacionais, incluindo os aspectos relativos à segurança.'),
(278,'Envolver os fornecedores que atuam diretamente nos processos da organização com os princípios organizacionais, incluindo os aspectos relativos à saúde.'),

(278,'Comprometer os fornecedores que atuam diretamente nos processos da organização com os valores organizacionais, incluindo os aspectos relativos à segurança.'),
(278,'Comprometer os fornecedores que atuam diretamente nos processos da organização com os valores organizacionais, incluindo os aspectos relativos à saúde.'),

(278,'Comprometer os fornecedores que atuam diretamente nos processos da organização com os princípios organizacionais, incluindo os aspectos relativos à segurança.'),
(278,'Comprometer os fornecedores que atuam diretamente nos processos da organização com os princípios organizacionais, incluindo os aspectos relativos à saúde.'),

(279,'Gerenciar os aspectos que causam impacto na sustentabilidade econômica do negócio.'),

(280,'Assegurar os recursos financeiros necessários para atender às necessidades operacionais.'),
(280,'Assegurar os recursos financeiros necessários para manter o fluxo financeiro equilibrado.'),

(281,'Definir os recursos financeiros, visando suportar as estratégias.'),
(281,'Definir os recursos financeiros, visando suportar os planos de ação.'),

(281,'Avaliar os investimentos necessários, visando suportar as estratégias.'),
(281,'Avaliar os investimentos necessários, visando suportar os planos de ação.'),

(282,'Elaborar o orçamento da organização.'),
(282,'Controlar o orçamento organização.'),

(283,'Apresentar os resultados relativos à gestão econômico-financeira.'),

(284,'Apresentar os resultados relativos aos clientes.'),
(284,'Apresentar os resultados relativos ao mercado.'),

(285,'Apresentar os resultados relativos à responsabilidade socioambiental.'),
(285,'Apresentar os resultados relativos à ética.'),
(285,'Apresentar os resultados relativos ao desenvolvimento social.'),

(286,'Apresentar os resultados relativos ao sistema de trabalho.'),
(286,'Apresentar os resultados relativos à capacitação e desenvolvimento.'),
(286,'Apresentar os resultados relativos à qualidade de vida.'),

(287,'Apresentar os resultados relativos aos produtos.'),
(287,'Apresentar os resultados relativos à gestão dos processos principais do negócio.'),
(287,'Apresentar os resultados relativos aos processos de apoio do negócio.'),

(288,'Apresentar os resultados dos principais indicadores relativos aos produtos adquiridos.'),
(288,'Apresentar os resultados dos principais indicadores relativos à gestão de relacionamento com os fornecedores.'),

(289,'Disseminar os princípios da administração pública na organização.'),
(289,'Disseminar os valores da administração pública na organização.'),
(289,'Disseminar as diretrizes de governo na organização.'),

(289,'Internalizar os princípios da administração pública na organização.'),
(289,'Internalizar os valores da administração pública na organização.'),
(289,'Internalizar as diretrizes de governo na organização.'),

(290,'Tomar as principais decisões da Alta Administração assegurando a transparência.'),
(290,'comunicar as principais decisões da Alta Administração assegurando a transparência.'),
(290,'Implementar as principais decisões da Alta Administração assegurando a transparência.'),

(290,'Tomar as principais decisões pela da Alta Administração assegurando o envolvimento de todas as partes interessadas.'),
(290,'Comunicar as principais decisões da Alta Administração assegurando o envolvimento de todas as partes interessadas.'),
(290,'Implementar as principais decisões da Alta Administração assegurando o envolvimento de todas as partes interessadas.'),

(291,'Identificar os riscos organizacionais mais significativos que possam afetar o desempenho.'),
(291,'Tratar os riscos organizacionais mais significativos que possam afetar o desempenho.'),

(292,'Prestar conta de seus atos para os órgãos de controle.'),
(292,'Prestar conta dos resultados alcançados para os órgãos de controle.'),

(292,'Prestar conta de seus atos para a sociedade.'),
(292,'Prestar conta dos resultados alcançados para a sociedade.'),

(293,'Estabelecer um exemplo a ser seguido.'),
(293,'Atuar pessoalmente para buscar novas oportunidades para a organização.'),
(293,'Atuar pessoalmente para promover o comprometimento com todas as partes interessadas.'),

(294,'Estabelecer os princípios organizacionais necessários à criação de valor para todas as partes interessadas.'),
(294,'Estabelecer os valores organizacionais necessários à criação de valor para todas as partes interessadas.'),

(294,'Atualizar os valores organizacionais necessários à criação de valor para todas as partes interessadas.'),
(294,'Atualizar os princípios organizacionais necessários à criação de valor para todas as partes interessadas.'),

(295,'Incentivar o comprometimento de todos com a cultura da excelência.'),

(296,'Definir as habilidades de liderança nos líderes.'),
(296,'Identificar as habilidades de liderança nos líderes.'),
(296,'Desenvolver as habilidades de liderança nos líderes.'),

(297,'Estabelecer os principais padrões de trabalho que orientam a execução adequada das práticas de gestão da organização.'),
(297,'Estabelecer os principais padrões de trabalho que orientam os métodos para verificar o seu cumprimento na organização.'),

(298,'Estimular o aprendizado organizacional.'),

(299,'Analisar as necessidades de informações comparativas para avaliar o desempenho da organização.'),

(300,'Analisar o desempenho estratégico, considerando as informações comparativas do ambiente interno e externo.'),
(300,'Analisar o desempenho operacional, considerando as informações comparativas do ambiente interno e externo.'),

(301,'Avaliar o êxito das estratégias e o alcance dos respectivos objetivos da organização a partir das conclusões da análise do seu desempenho.'),

(302,'Comunicar as decisões decorrentes da análise do desempenho da organização à força de trabalho, quando pertinente.'),
(302,'Comunicar as decisões decorrentes da análise do desempenho da organização a outras partes interessadas, quando pertinente.'),

(303,'Acompanhar a implementação das decisões decorrentes da análise do desempenho da organização.'),

(304,'Formular as políticas públicas, quando pertinente.'),

(305,'Formular as estratégias da organização.'),

(306,'Considerar os aspectos relativos ao ambiente externo no processo de formulação das estratégias.'),

(307,'Analisar o ambiente interno da organização.'),

(308,'Avaliar as estratégias da organização.'),

(308,'Selecionar as estratégias da organização.'),

(309,'Envolver as áreas da organização no processo de formulação das estratégias.'),

(310,'Comunicar as estratégias às partes interessadas pertinentes para o estabelecimento de compromissos mútuos.'),

(311,'Definir os indicadores para a avaliação da operacionalização das estratégias e definir os respectivos planos de ação.'),
(311,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de ação.'),
 
(312,'Desdobrar as metas para as áreas da organização, assegurando a coerência com as estratégias selecionadas.'),
(312,'Desdobrar os planos de ação para as áreas da organização, assegurando a coerência com as estratégias selecionadas.'),

(312,'Desdobrar as metas para as áreas da organização, assegurando a consistência entre os respectivos planos.'),
(312,'Desdobrar os planos de ação para as áreas da organização, assegurando consistência entre os respectivos planos.'),

(313,'Alocar os diferentes recursos financeiros e não-financeiros para assegurar a implementação dos planos de ação.'),

(314,'Comunicar as metas para a força de trabalho e, quando pertinente, para as demais partes interessadas.'),
(314,'Comunicar os indicadores para a força de trabalho e, quando pertinente, para as demais partes interessadas.'),
(314,'Comunicar os planos de ação para a força de trabalho e, quando pertinente, para as demais partes interessadas.'),

(315,'Realizar o monitoramento da implementação dos planos de ação.'),

(316,'Identificar os seus cidadãos-usuários e classificar por tipos.'),
(316,'Identificar os seus cidadãos-usuários e classificar por grupos.'),

(317,'Identificar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos produtos da organização.'),
(317,'Analisar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos produtos da organização.'),
(317,'Compreender as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos produtos da organização.'),
(317,'Utilizar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos produtos da organização.'),

(317,'Identificar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos serviços da organização.'),
(317,'Analisar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos serviços da organização.'),
(317,'Compreender as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos serviços da organização.'),
(317,'Utilizar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos serviços da organização.'),

(317,'Identificar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos processos da organização.'),
(317,'Analisar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos processos da organização.'),
(317,'Compreender as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos processos da organização.'),
(317,'Utilizar as necessidades e expectativas dos cidadãos-usuários, atuais e potenciais, para definição e melhoria dos processos da organização.'),

(318,'Divulgar os produtos e serviços da organização aos cidadãos e à sociedade de forma a criar credibilidade.'),
(318,'Divulgar os produtos e serviços da organização aos cidadãos e à sociedade de forma a criar confiança.'),
(318,'Divulgar os produtos e serviços da organização aos cidadãos e à sociedade de forma a criar imagem positiva.'),

(318,'Divulgar os padrões de atendimento da organização aos cidadãos e à sociedade de forma a criar credibilidade.'),
(318,'Divulgar os padrões de atendimento da organização aos cidadãos e à sociedade de forma a criar confiança.'),
(318,'Divulgar os padrões de atendimento da organização aos cidadãos e à sociedade de forma a criar imagem positiva.'),

(318,'Divulgar as ações de melhoria da organização aos cidadãos e à sociedade de forma a criar credibilidade.'),
(318,'Divulgar as ações de melhoria da organização aos cidadãos e à sociedade de forma a criar confiança.'),
(318,'Divulgar as ações de melhoria da organização aos cidadãos e à sociedade de forma a criar imagem positiva.'),

(319,'Identificar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus produtos.'),
(319,'Identificar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus serviços.'),
(319,'Identificar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e suas ações.'),

(319,'Avaliar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus produtos.'),
(319,'Avaliar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e seus serviços.'),
(319,'Avaliar os níveis de conhecimento do universo potencial de cidadãos-usuários sobre a organização e suas ações.'),

(320,'Avaliar a imagem da organização perante os cidadãos-usuários.'),

(321,'Avaliar o atendimento ao universo potencial dos cidadãos-usuários identificados.'),

(322,'Definir e divulgar aos cidadãos-usuários os principais canais de acesso para solicitarem informações sobre os serviços e produtos.'),
(322,'Definir e divulgar aos cidadãos-usuários os principais canais de acesso para solicitarem esclarecimentos sobre os serviços e produtos.'),
(322,'Definir e divulgar aos cidadãos-usuários os principais canais de acesso para comunicarem suas sugestões.'),
(322,'Definir e divulgar aos cidadãos-usuários os principais canais de acesso para comunicarem suas reclamações.'),

(323,'Tratar as reclamações, formais e informais dos cidadãos-usuários, visando assegurar a resposta rápida e eficaz e o seu aproveitamento por toda a organização.'),
(323,'Tratar as sugestões, formais e informais dos cidadãos-usuários, visando assegurar a resposta rápida e eficaz e o seu aproveitamento por toda a organização.'),

(324,'Acompanhar os serviços, recentemente prestados ou entregues, junto aos cidadãos-usuários para permitir à organização gerar soluções rápidas e eficazes.'),
(324,'Acompanhar os serviços, recentemente prestados ou entregues, junto aos cidadãos-usuários para permitir à organização evitar problemas de relacionamento.'),
(324,'Acompanhar os serviços, recentemente prestados ou entregues, junto aos cidadãos-usuários para permitir à organização atender as expectativas dos cidadãos-usuários.'),

(324,'Acompanhar os produtos, recentemente prestados ou entregues, junto aos cidadãos-usuários para permitir à organização gerar soluções rápidas e eficazes.'),
(324,'Acompanhar os produtos, recentemente prestados ou entregues, junto aos cidadãos-usuários para permitir à organização evitar problemas de relacionamento.'),
(324,'Acompanhar os produtos, recentemente prestados ou entregues, junto aos cidadãos-usuários para permitir à organização atender as expectativas dos cidadãos-usuários.'),

(325,'Avaliar a satisfação dos cidadãos-usuários em relação aos seus produtos ou serviços e aos da concorrência, quando pertinente.'),
(325,'Avaliar a insatisfação dos cidadãos-usuários em relação aos seus produtos ou serviços e aos da concorrência, quando pertinente.'),

(326,'Utilizar as informações obtidas dos cidadãos-usuários para melhorar o seu nível de satisfação.'),

(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de seus produtos, desde o projeto até a disposição final, sobre os quais tenha influência.'),
(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de seus serviços, desde o projeto até a disposição final, sobre os quais tenha influência.'),
(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de seus processos desde o projeto até a disposição final, sobre os quais tenha influência.'),
(327,'Identificar os aspectos e tratar os impactos sociais e ambientais de suas instalações, desde o projeto até a disposição final, sobre os quais tenha influência.'),

(328,'Comunicar os impactos sociais e ambientais dos serviços, assim como as respectivas políticas, ações e resultados à sociedade.'),
(328,'Comunicar os impactos sociais e ambientais dos produtos, assim como as respectivas políticas, ações e resultados à sociedade.'),
(328,'Comunicar os impactos sociais e ambientais dos processos, assim como as respectivas políticas, ações e resultados à sociedade.'),
(328,'Comunicar os impactos sociais e ambientais de suas instalações, assim como as respectivas políticas, ações e resultados à sociedade.'),

(329,'Tratar as pendências referentes aos requisitos legais, regulamentares, éticos ou contratuais, relatando as atualmente existentes.'),
(329,'Tratar as eventuais sanções referentes aos requisitos legais, regulamentares, éticos ou contratuais, relatando as atualmente existentes.'),

(330,'Promover ações que envolvam a conservação de recursos não-renováveis.'),
(330,'Promover ações que envolvam a preservação do ecossistema.'),
(330,'Promover ações que envolvam a otimização do uso de recursos renováveis.'),

(331,'Conscientizar a força de trabalho nas questões relativas à responsabilidade socioambiental.'),
(331,'Conscientizar os fornecedores nas questões relativas à responsabilidade socioambiental.'),
(331,'Conscientizar as demais partes interessadas nas questões relativas à responsabilidade socioambiental.'),

(331,'Envolver a força de trabalho nas questões relativas à responsabilidade socioambiental.'),
(331,'Envolver os fornecedores nas questões relativas à responsabilidade socioambiental.'),
(331,'Envolver as demais partes interessadas nas questões relativas à responsabilidade socioambiental.'),

(332,'Direcionar os esforços para o fortalecimento da sociedade executando projetos sociais, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento local, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade executando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(332,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos sociais, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento nacional, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento regional, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento local, quando pertinente.'),
(332,'Direcionar os esforços para o fortalecimento da sociedade apoiando projetos voltados para o desenvolvimento setorial, quando pertinente.'),

(333,'Divulgar oficialmente os seus atos e informações sobre seus planos, programas e projetos.'),

(334,'Tornar público o acesso as informações relativas a execução física.'),
(334,'Tornar público o acesso as informações relativas a execução orçamentária.'),
(334,'Tornar público o acesso as informações relativas a execução financeira.'),
(334,'Tornar público o acesso as informações relativas à gestão.'),

(334,'Democratizar o acesso as informações relativas a execução física.'),
(334,'Democratizar o acesso as informações relativas a execução orçamentária.'),
(334,'Democratizar o acesso as informações relativas a execução financeira.'),
(334,'Democratizar o acesso as informações relativas à gestão.'),

(335,'Orientar a sociedade a participar no controle dos seus resultados institucionais.'),
(335,'Estimular a sociedade a participar no controle dos seus resultados institucionais.'),

(336,'Estimular o exercício da responsabilidade social da força de trabalho, no cumprimento de seu papel de agente público.'),
(336,'Estimular o exercício da responsabilidade social da força de trabalho, no comportamento ético em todos os níveis.'),

(336,'Estimular o exercício da responsabilidade social da força de trabalho.'),
(336,'Estimular o cumprimento de seu papel de agente público.'),
(336,'Estimular o comportamento ético em todos os níveis.'),

(337,'Disponibilizar canais de comunicação para receber eventuais denúncias de violação da ética e atuar para minimizar esses acontecimentos e seus efeitos.'),

(338,'Identificar as necessidades da sociedade em relação ao seu setor de atuação e transformar em requisitos para a formulação e execução das políticas públicas, quando pertinente.'),


(339,'Contribuir na formulação de execuções políticas públicas do seu setor.'),
(339,'Atuar na execução das políticas públicas do seu setor.'),

(340,'Divulgar as políticas públicas e seus respectivos objetivos para a sociedade.'),

(341,'Monitorar a execução das políticas públicas em seu nível de atuação.'),
(341,'Avaliar a execução das políticas públicas em seu nível de atuação.'),

(342,'Avaliar a satisfação da sociedade com a implementação das políticas públicas.'),
(342,'Avaliar a satisfação das demais partes interessadas com a implementação das políticas públicas.'),

(343,'Identificar os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(343,'Definir os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(343,'Desenvolver os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(343,'Implantar os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),
(343,'Atualizar os principais sistemas de informação, visando atender às necessidades identificadas da organização.'),

(343,'Identificar os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),
(343,'Definir os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),
(343,'Desenvolver os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),
(343,'Implantar os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),
(343,'Atualizar os principais sistemas de informação, visando atender às necessidades identificadas dos usuários.'),

(344,'Estabelecer a memória administrativa da organização.'),
(344,'Manter a memória administrativa da organização.'),

(345,'Apoiar o cumprimento da missão institucional.'),
(345,'Promover o cumprimento da missão institucional.'),
(345,'Apoiar a integração da organização com seus cidadãos usuários, sociedade, fornecedores e parceiros.'),
(345,'Promover a integração da organização com seus cidadãos usuários, sociedade, fornecedores e parceiros.'),

(346,'Colocar as informações necessárias à disposição dos públicos internos e externos, incluindo cidadãos-usuários.'),
(346,'Colocar as informações necessárias à disposição dos públicos internos e externos, incluindo fornecedores.'),
(346,'Colocar as informações necessárias à disposição dos públicos internos e externos, incluindo parceiros.'),

(347,'Gerenciar a segurança das informações.'),

(348,'Identificar as organizações consideradas como um referencial comparativo pertinente.'),

(349,'Identificar as fontes de informações comparativas.'),
(349,'Obter as fontes de informações comparativas.'),
(349,'Manter as fontes de informações comparativas.'),

(350,'Melhorar o conhecimento dos processos organizacionais.'),
(350,'Estabelecer metas ousadas.'),
(350,'Promover melhorias no desempenho da organização.'),

(351,'Desenvolver o conhecimento na organização.'),
(351,'Compartilhar o conhecimento na organização.'),

(352,'Manter o conhecimento protegido.'),

(353,'Assegurar que a gestão do conhecimento seja utilizada para melhorar os processos.'),
(353,'Assegurar que a gestão do conhecimento seja utilizada para melhorar os produtos.'),
(353,'Assegurar que a gestão do conhecimento seja utilizada para melhorar os serviços.'),

(354,'Identificar os ativos intangíveis da organização'),
(354,'Desenvolver os ativos intangíveis da organização'),
(354,'Mensurar os ativos intangíveis da organização'),

(355,'Denifir a organização do trabalho visando o alto desempenho da organização.'),
(355,'Implementar a organização do trabalho visando o alto desempenho da organização.'),

(356,'Selecionar pessoas para o preenchimento de cargos e funções em consonância com as estratégias da organização.'),
(356,'Selecionar pessoas para o preenchimento de cargos e funções em consonância com os objetivos da organização.'),
(356,'Selecionar pessoas para o preenchimento de cargos e funções em consonância com a missão da organização.'),

(357,'Definir os canais de interlocução e a negociação com a força de trabalho da organização, quando pertinente.'),
(357,'Disponibilizar os canais de interlocução e a negociação com a força de trabalho da organização, quando pertinente.'),

(358,'Estimular a integração das pessoas e das equipes.'),
(358,'Estimular a integração das equipes.'),

(358,'Estimular a cooperação das pessoas.'),
(358,'Estimular a cooperação das equipes.'),

(359,'Gerenciar o desempenho das pessoas e das equipes, de forma a estimular a obtenção de metas de alto desempenho.'),
(359,'Gerenciar o desempenho das pessoas e das equipes, de forma a estimular a cultura da excelência na organização.'),
(359,'Gerenciar o desempenho das pessoas e das equipes, de forma a estimular o desenvolvimento profissional.'),


(360,'Estimular o alcance de metas de alto desempenho através do sistema de remuneração.'),
(360,'Estimular o alcance de metas de alto desempenho através do sistema de reconhecimento.'),
(360,'Estimular o alcance de metas de alto desempenho através do sistema de incentivos para as pessoas.'),

(360,'Estimular o alcance de metas de aprendizado através do sistema de remuneração.'),
(360,'Estimular o alcance de metas de aprendizado através do sistema de reconhecimento.'),
(360,'Estimular o alcance de metas de aprendizado através do sistema de incentivos para as pessoas.'),

(361,'Identificar as necessidades de capacitação e desenvolvimento.'),

(362,'Compatibilizar as necessidades de capacitação e de desenvolvimento das pessoas com as necessidades da organização, para efeito da definição dos programas de capacitação.'),
(362,'Compatibilizar as necessidades de capacitação e de desenvolvimento das pessoas com as necessidades da organização, para efeito da definição dos programas de desenvolvimento.'),

(363,'Abordar a cultura da excelência através dos programas de capacitação.'),
(363,'Abordar a cultura da excelência através dos programas de desenvolvimento.'),

(363,'Contribuir para consolidar o aprendizado organizacional, através dos programas de capacitação.'),
(363,'Contribuir para consolidar o aprendizado organizacional, através dos programas de desenvolvimento.'),

(364,'Conceber a forma de realização dos programas de capacitação considerando as necessidades da organização.'),
(364,'Conceber a forma de realização dos programas de capacitação considerando as necessidades das pessoas.'),
(364,'Conceber a forma de realização dos programas de capacitação considerando os recursos disponíveis.'),

(364,'Conceber a forma de realização dos programas de desenvolvimento considerando as necessidades da organização.'),
(364,'Conceber a forma de realização dos programas de desenvolvimento considerando as necessidades das pessoas..'),
(364,'Conceber a forma de realização dos programas de desenvolvimento considerando os recursos disponíveis.'),

(365,'Avaliar as habilidades em relação à sua utilidade na execução do trabalho da organização.'),
(365,'Avaliar as habilidades em relação à sua eficácia na consecução das estratégias da organização.'),

(365,'Avaliar os conhecimentos adquiridos em relação à sua utilidade na execução do trabalho da organização.'),
(365,'Avaliar os conhecimentos adquiridos em relação à sua eficácia na consecução das estratégias da organização.'),

(366,'Promover o desenvolvimento integral das pessoas, como indivíduos.'),
(366,'Promover o desenvolvimento integral das pessoas, como cidadãos.'),
(366,'Promover o desenvolvimento integral das pessoas, como profissionais.'),

(367,'Identificar os perigos relacionados à saúde ocupacional.'),
(367,'Identificar os perigos relacionados à segurança.'),
(367,'Identificar os perigos relacionados à ergonomia.'),

(367,'Tratar os riscos relacionados à saúde ocupacional.'),
(367,'Tratar os riscos relacionados à segurança.'),
(367,'Tratar os riscos relacionados à ergonomia.'),

(368,'Identificar os fatores que afetam o bem-estar considerando os diferentes grupos de pessoas.'),
(368,'Identificar os fatores que afetam a satisfação considerando os diferentes grupos de pessoas.'),
(368,'Identificar os fatores que afetam a motivação considerando os diferentes grupos de pessoas.'),

(369,'Tratar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(369,'Tratar os fatores que afetam a satisfação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(369,'Tratar os fatores que afetam a motivação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),

(370,'Colaborar para a melhoria da qualidade de vida das pessoas fora do ambiente de trabalho.'),

(371,'Avaliar os fatores que afetam o bem-estar, considerando os diferentes grupos de pessoas.'),
(371,'Avaliar os fatores que afetam a satisfação, considerando os diferentes grupos de pessoas.'),
(371,'Avaliar os fatores que afetam a motivação, considerando os diferentes grupos de pessoas.'),

(372,'Identificar os processos finalísticos, considerando a missão institucional da organização.'),
(372,'Identificar os processos de apoio, considerando a missão institucional da organização.'),

(372,'Determinar os processos finalísticos, considerando a missão institucional da organização.'),
(372,'Determinar os processos de apoio, considerando a missão institucional da organização.'),

(373,'Traduzir em requisitos as necessidades dos cidadãos-usuários e da sociedade.'),
(373,'Incorporar aos projetos de serviços ou produtos as necessidades dos cidadãos-usuários e da sociedade.'),
(373,'Incorporar aos processos finalísticos as necessidades dos cidadãos-usuários e da sociedade.'),

(374,'Projetar os processos de apoio, visando o cumprimento dos requisitos definidos'),
(374,'Projetar os processos finalísticos, visando o cumprimento dos requisitos definidos'),

(375,'Controlar os processos finalísticos.'),
(375,'Controlar os processos de apoio.'),

(376,'Refinar os processos finalísticos.'),
(376,'Refinar os processos de apoio.'),

(377,'Identificar potenciais fornecedores visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(377,'Identificar potenciais fornecedores visando melhorar o desempenho.'),
(377,'Identificar potenciais fornecedores visando assegurar o desenvolvimento sustentável de sua cadeia de suprimentos.'),

(377,'Desenvolver sua cadeia de suprimentos visando assegurar a disponibilidade de fornecimento a longo prazo.'),
(377,'Desenvolver sua cadeia de suprimentos visando melhorar o seu desempenho.'),
(377,'Desenvolver sua cadeia de suprimentos visando assegurar o desenvolvimento sustentável de sua cadeia de suprimentos.'),

(378,'Como é realizado o processo de aquisição de bens, de materiais e de serviços de forma a assegurar a transparência do processo e o atendimento à legislação.'),


(378,'Realizar o processo de aquisição de bens de forma a assegurar a transparência do processo.'),
(378,'Realizar o processo de aquisição de materiais de forma a assegurar a transparência do processo.'),
(378,'Realizar o processo de aquisição de serviços de forma a assegurar a transparência do processo.'),

(378,'Realizar o processo de aquisição de bens de forma a atender a legislação.'),
(378,'Realizar o processo de aquisição de materiais de forma a atender a legislação.'),
(378,'Realizar o processo de aquisição de serviços de forma a atender a legislação.'),


(379,'Assegurar a qualidade dos bens adquiridos.'),
(379,'Assegurar a qualidade dos produtos adquiridos.'),
(379,'Assegurar a qualidade dos serviços adquiridos.'),

(380,'Realizar a gestão dos bens materiais quando for pertinente.'),
(380,'Realizar a gestão dos bens patrimoniais quando for pertinente.'),
(380,'Realizar a gestão dos estoques, quando for pertinente.'),

(381,'Administrar o relacionamento com os fornecedores.'),

(382,'Avaliar os fornecedores e prontamente informar sobre seu desempenho.'),

(383,'Minimizar os custos associados a gestão do fornecimento.'),

(384,'Envolver os fornecedores que atuam diretamente nos processos da organização com os princípios Organizacionais relativos à responsabilidade socioambiental, incluindo os aspectos da segurança e saúde.'),
(384,'Comprometer os fornecedores que atuam diretamente nos processos da organização com os princípios Organizacionais relativos à responsabilidade socioambiental, incluindo os aspectos da segurança e saúde.'),

(385,'Elaborar a proposta orçamentária mais significativa que possa vir a afetar a execução de suas atividades.'),
(385,'Tratar as restrições e liberações de orçamento mais significativas que possam vir a afetar a execução de suas atividades.'),

(386,'Gerenciar os processos orçamentários para suportar as necessidades estratégicas da organização.'),
(386,'Gerenciar os processos orçamentários para suportar as necessidades operacionais da organização.'),

(387,'Monitorar a execução orçamentária e os possíveis realinhamentos entre o orçamento, estratégias e objetivos da organização.'),
(387,'Monitorar a execução financeira e os possíveis realinhamentos entre o orçamento, estratégias e objetivos da organização.'),

(388,'Selecionar as melhores opções de investimentos e aplicações de recursos financeiros, quando pertinente.'),
(388,'Realizar captações de recursos financeiros, quando pertinente.'),

(389,'Acompanhar as operações da organização em termos orçamentários e financeiros.'),
(389,'Administrar os parâmetros orçamentários e financeiros.'),

(390,'Apresentar os resultados dos principais indicadores relativos aos cidadãosusuários.Estratificar por grupos de cidadãos-usuários, segmentos de mercado ou tipos de produtos, quando aplicável.Incluir os níveis de desempenho de organizações consideradas como referencial comparativo pertinente;Explicar, resumidamente, os resultados apresentados, esclarecendo eventuais tendências adversas e comparações desfavoráveis.'),

(391,'Apresentar os resultados dos principais indicadores relativos à sociedade, incluindo os relativos à atuação socioambiental, à ética, ao controle social e às políticas públicas. Estratificar os resultados por instalações, quando aplicável. Incluir os níveis de desempenho de organizações consideradas como referencial comparativo pertinente; explicar, resumidamente, os resultados apresentados, esclarecendo eventuais tendências adversas e comparações desfavoráveis.'),

(392,'Apresentar os resultados dos principais indicadores relativos à gestão orçamentária e financeira. Estratificar os resultados por unidades ou filiais, quando aplicável.'),

(393,'Apresentar os resultados dos principais indicadores relativos às pessoas, incluindo os relativos aos sistemas de trabalho, à capacitação e desenvolvimento e à qualidade de vida. Estratificar os resultados por grupos de pessoas da força de trabalho, funções na organização e, quando aplicável, por instalações.'),

(394,'Apresentar os resultados dos principais indicadores relativos aos processos de suprimento.'),

(395,'Apresentar os resultados dos principais indicadores relativos aos processos.'),

(396,'Estabelecer os valores necessários à promoção da excelência para todas as partes interessadas.'),
(396,'Estabelecer os valores necessários à criação de valor para todas as partes interessadas.'),

(396,'Estabelecer os princípios organizacionais necessários à promoção da excelência para todas as partes interessadas.'),
(396,'Estabelecer os princípios organizacionais necessários à criação de valor para todas as partes interessadas.'),

(397,'Comunicar os valores organizacionais para força de trabalho e, quando pertinentes, às demais partes interessadas.'),
(397,'Comunicar os princípios organizacionais para força de trabalho e, quando pertinentes, às demais partes interessadas.'),

(398,'Tratar as questões éticas nos relacionamentos internos.'),
(398,'Tratar as questões éticas nos relacionamentos externos.'),

(399,'Comunicar as principais decisões.'),
(399,'Tomar as principais decisões.'),
(399,'Implementar principais decisões.'),

(400,'Exercer a liderança.'),
(400,'Interagir com as partes interessadas.'),

(401,'Verificar o cumprimento dos principais padrões de trabalho.'),

(402,'Avaliar as práticas de gestão e respectivos padrões de trabalho.'),
(402,'Melhorar as práticas de gestão e respectivos padrões de trabalho.'),

(403,'Analisar o desempenho estratégico, considerando as informações comparativas do ambiente interno.'),
(403,'Analisar o desempenho estratégico, considerando as informações comparativas do ambiente externo.'),

(403,'Analisar o desempenho operacional, considerando as informações comparativas do ambiente interno.'),
(403,'Analisar o desempenho operacional, considerando as informações comparativas do ambiente externo.'),

(404,'Definir as estratégias da organização, considerando o ambiente externo.'),
(404,'Definir as estratégias da organização, considerando o ambiente interno.'),

(405,'Definir os indicadores para a avaliação da implementação das estratégias e definir os respectivos planos de ação.'),
(405,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de ação.'),

(406,'Comunicar as estratégias às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'), 
(406,'Comunicar as metas às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'), 
(406,'Comunicar os planos de ação às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'), 

(407,'Realizar o monitoramento da implementação dos planos de ação.'),

(408,'Definir os clientes-alvo em relação a segmentação do mercado.'),

(409,'Identificar as necessidades dos clientes-alvo.'),
(409,'Identificar as expectativas dos clientes-alvo.'),
(409,'Analisar as necessidades dos clientes-alvo.'),
(409,'Analisar as expectativas dos clientes-alvo.'),
(409,'Compreender as necessidades dos clientes-alvo.'),
(409,'Compreender as expectativas dos clientes-alvo.'),

(410,'Divulgar os nprodutos da organização aos clientes e ao mercado.'),
(410,'Divulgar a marca da organização aos clientes e ao mercado.'),

(411,'Tratar as reclamações, formais ou informais, dos clientes visando assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(411,'Tratar as sugestões, formais ou informais, dos clientes visando assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),

(412,'Avaliar a satisfação dos clientes e utilizar essas informações para promover ações de melhoria.'),

(413,'Tratar os impactos sociais adversos de produtos, processos e instalações.'),
(413,'Tratar os impactos ambientais adversos de produtos, processos e instalações.'),

(414,'Identificar  os requisitos legais, aplicáveis a questões socioambientais.'),
(414,'Identificar  os requisitos regulamentares aplicáveis a questões socioambientais.'),
(414,'Identificar  os requisitos contratuais, aplicáveis a questões socioambientais.'),

(414,'Analisar os requisitos legais, aplicáveis a questões socioambientais.'),
(414,'Analisar os requisitos regulamentares aplicáveis a questões socioambientais.'),
(414,'Analisar os requisitos contratuais, aplicáveis a questões socioambientais.'),

(415,'Conscientizar as pessoas da força de trabalho das questões relativas à responsabilidade socioambiental.'),
(415,'Envolver as pessoas da força de trabalho das questões relativas à responsabilidade socioambiental.'),


(416,'Selecionar projetos sociais voltados para o desenvolvimento nacional.'),
(416,'Selecionar projetos sociais voltados para o desenvolvimento regional.'),
(416,'Selecionar projetos sociais voltados para o desenvolvimento local.'),
(416,'Selecionar projetos sociais voltados para o desenvolvimento setorial.'),

(416,'Desenvolver projetos sociais voltados para o desenvolvimento nacional.'),
(416,'Desenvolver projetos sociais voltados para o desenvolvimento regional.'),
(416,'Desenvolver projetos sociais voltados para o desenvolvimento local.'),
(416,'Desenvolver projetos sociais voltados para o desenvolvimento setorial.'),

(417,'Definir os sistemas de informação para apoiar as operações diárias.'),
(417,'Definir os sistemas de informação para apoiar a tomada de decisão em todos os níveis e áreas da organização.'),

(418,'Tratar a segurança das informações para assegurar sua atualização.'),
(418,'Tratar a segurança das informações para assegurar sua confidencialidade.'),
(418,'Tratar a segurança das informações para assegurar sua integridade.'),
(418,'Tratar a segurança das informações para assegurar sua disponibilidade.'),

(419,'Obter as informações comparativas.'),
(419,'Manter as informações comparativas.'),

(420,'Compartilhar os conhecimentos geradores de diferenciais.'),
(420,'Reter os conhecimentos geradores de diferenciais.'),

(421,'Definir a organização do trabalho.'),
(421,'Implementar a oganização do trabalho.'),

(422,'Selecionar internamente pessoas para a força de trabalho.'),
(422,'Selecionar externamente pessoas para a força de trabalho.'),

(422,'Contratar internamente pessoas para a força de trabalho.'),
(422,'Contratar externamente pessoas para a força de trabalho.'),

(423,'Identificar as necessidades de capacitação, considerando as estratégias da organização.'),
(423,'Identificar as necessidades de capacitação, considerando as necessidades das pessoas.'),

(423,'Identificar as necessidades de desenvolvimento, considerando as estratégias da organização.'),
(423,'Identificar as necessidades de desenvolvimento, considerando as necessidades das pessoas.'),

(424,'Definir os programas de capacitação, considerando as necessidades identificadas.'),
(424,'Definir os programas de desenvolvimento, considerando as necessidades identificadas.'),

(425,'Identificar os perigos relacionados à saúde ocupacional.'),
(425,'Identificar os perigos relacionados à segurança.'),
(425,'Identificar os perigos relacionados à ergonomia.'),

(425,'Tratar os riscos relacionados à saúde ocupacional.'),
(425,'Tratar os riscos relacionados à segurança.'),
(425,'Tratar os riscos relacionados à ergonomia.'),

(426,'Avaliar a satisfação das pessoas.'),

(427,'Determinar os requisitos aplicáveis aos processos principais do negócio, a partir das necessidades dos clientes.'),
(427,'Determinar os requisitos aplicáveis aos processos principais do negócio, a partir das necessidades das demais partes interessadas.'),

(427,'Determinar os requisitos aplicáveis aos processos de apoio do negócio, a partir das expectativas dos clientes.'),
(427,'Determinar os requisitos aplicáveis aos processos de apoio do negócio, a partir das expectativas das demais partes interessadas.'),


(428,'Controlar os processos principais do negócio, visando assegurar o atendimento dos requisitos aplicáveis.'),
(428,'Controlar os processos de apoio do negócio, visando assegurar o atendimento dos requisitos aplicáveis.'),

(429,'Analisar os processos principais do negócio.'),
(429,'Melhorar os processos principais do negócio.'),

(429,'Analisar os processos de apoio do negócio.'),
(429,'Melhorar os processos de apoio do negócio.'),

(430,'Selecionar os fornecedores, considerando requisitos de desempenho.'),

(431,'Envolver os fornecedores, que atuam diretamente nos processos da organização, nos processos da organização'),
(431,'Envolver os fornecedores, que atuam diretamente nos processos da organização, com os valores da organização'),
(431,'Envolver os fornecedores, que atuam diretamente nos processos da organização, com os princípios organizacionais'),

(432,'Elaborar o orçamento.'),
(432,'Controlar o orçamento.'),
(432,'Manter o fluxo financeiro equilibrado.'),

(431,'Como os fornecedores que atuam diretamente nos processos da organização são envolvidos e comprometidos com os valores e os princípios organizacionais, incluindo os aspectos relativos à segurança e à saúde.'),

(432,'Como é elaborado e controlado o orçamento e mantido o fluxo financeiro equilibrado.'),

(433,'Apresentar os resultados relativos à gestão econômico-financeira.'),

(434,'Apresentar os resultados relativos aos clientes e aos mercados.'),

(435,'Apresentar os resultados relativos à sociedade.'),

(436,'Apresentar os resultados relativos às pessoas.'),

(437,'Apresentar os resultados relativos ao produto e à gestão dos processos principais do negócio e dos processos de apoio.'),

(438,'Apresentar os resultados relativos aos fornecedores.'),

(439,'Exercer a liderança, interagindo com todas as partes interessadas.'),
(439,'Exercer a liderança, promovendo o comprometimento com todas as partes interessadas.'),

(440,'Tomar as principais decisões da Alta Administração.'),
(440,'Comunicar as principais decisões da Alta Administração.'),
(440,'Implementar as principais decisões da Alta Administração.'),

(441,'Internalizar os Princípios e Valores da Administração Pública na organização.'),
(441,'Internalizar as Diretrizes do Governo na organização.'),
(441,'Internalizar os Princípios Organizacionais na organização.'),

(441,'Disseminar os Princípios e Valores da Administração Pública na organização.'),
(441,'Disseminar as Diretrizes do Governo na organização.'),
(441,'Disseminar os Princípios Organizacionais na organização.'),

(442,'Conduzir a implementação do sistema de gestão, visando assegurar o atendimento das necessidades e expectativas de todas as partes interessadas.'),
(442,'Assegurar o atendimento das necessidades, visando assegurar o atendimento das necessidades e expectativas de todas as partes interessadas.'),

(443,'Analisar criticamente o desempenho por meio de indicadores.'),
(443,'Acompanhar a implementação das decisões decorrentes da análise dos indicadores.'),

(444,'Avaliar as práticas de gestão e seus respectivos padrões.'),
(444,'Melhorar as práticas de gestão e seus respectivos padrões.'),

(445,'Definir as estratégias da organização considerando-se as necessidades das partes interessadas.'),
(445,'Definir as estratégias da organização considerando-se as demandas do governo.'),
(445,'Definir as estratégias da organização considerando-se as informações internas.'),

(446,'Definir os indicadores para a avaliação da operacionalização das estratégias e definir os respectivos planos de ação.'),
(446,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de ação.'),

(447,'Alocar os recursos para assegurar a implementação dos planos de ação.'),

(448,'Comunicar as estratégias às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(448,'Comunicar as metas às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(448,'Comunicar os planos de ação às pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),

(449,'Monitorar a implementação dos planos de ação.'),

(450,'Traduzir as necessidades dos cidadãos-usuários em requisitos e incorporar aos projetos de serviços.'),
(450,'Traduzir as necessidades dos cidadãos-usuários em requisitos e incorporar produtos.'),
(450,'Traduzir as necessidades dos cidadãos-usuários em requisitos e incorporar processos finalísticos.'),

(450,'Traduzir as necessidades da sociedade em requisitos e incorporar aos projetos de serviços.'),
(450,'Traduzir as necessidades da sociedade em requisitos e incorporar produtos.'),
(450,'Traduzir as necessidades da sociedade em requisitos e incorporar processos finalísticos.'),

(451,'divulgar os produtos e serviços da organização  aos cidadãos e à sociedade.'),
(451,'divulgar os padrões de atendimento da organização  aos cidadãos e à sociedade.'),
(451,'divulgar as ações de melhoria da organização aos cidadãos e à sociedade.'),


(452,'Tratar as solicitações, formais ou informais, dos cidadãos-usuários visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(452,'Tratar as reclamações, formais ou informais, dos cidadãos-usuários visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(452,'Tratar as sugestões, formais ou informais, dos cidadãos-usuários visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),

(453,'Avaliar a satisfação dos cidadãos-usuários em relação aos seus serviços.'),
(453,'Avaliar a satisfação dos cidadãos-usuários em relação aos seus produtos.'),

(454,'Identificar os impactos sociais adversos decorrentes da atuação da organização.'),
(454,'Identificar os impacos ambientais adversos decorrentes da atuação da organização.'),
(454,'Tratar os impactos sociais adversos decorrentes da atuação da organização.'),
(454,'Tratar os impacos ambientais adversos decorrentes da atuação da organização.'),

(455,'Estimular a força de trabalho nas questões relativas à responsabilidade socioambiental.'),
(455,'Envolver a força de trabalho nas questões relativas à responsabilidade socioambiental.'),

(455,'Estimular seus parceiros nas questões relativas à responsabilidade socioambiental.'),
(455,'Envolver seus parceiros nas questões relativas à responsabilidade socioambiental.'),

(456,'Orientar a sociedade a exercer o controle social.'),
(456,'Estimular a sociedade a exercer o controle social.'),

(457,'Estimular o exercício da responsabilidade social da força de trabalho, no cumprimento de seu papel de agente público.'), 
(457,'Estimular o exercício da responsabilidade social da força de trabalho, no cumprimento do comportamento ético em todos os níveis.'), 

(458,'Identificar as necessidades da sociedade em relação ao seu setor de atuação e transformar em requisitos para a formulação e execução de políticas públicas, quando pertinente.'),

(459,'Identificar as necessidades em relação aos sistemas de informações para apoiar as operações diárias.'),
(459,'Definir os sistemas de informações para apoiar as operações diárias.'),
(459,'Implantar os sistemas de informações para apoiar as operações diárias.'),

(459,'Identificar as necessidades em relação aos sistemas de informações para a tomada de decisão em todos os níveis e áreas da organização.'),
(459,'Definir os sistemas de informações para a tomada de decisão em todos os níveis e áreas da organização.'),
(459,'Implantar os sistemas de informações para a tomada de decisão em todos os níveis e áreas da organização.'),

(460,'Tratar a segurança das informações para assegurar sua atualização.'),
(460,'Tratar a segurança das informações para assegurar sua confidencialidade.'),
(460,'Tratar a segurança das informações para assegurar sua integridade.'),
(460,'Tratar a segurança das informações para assegurar sua disponibilidade.'),

(461,'Estabelecer a memória administrativa.'),
(461,'Manter a memória administrativa.'),

(462,'Utilizar outras organizações como referencial comparativo.'),

(463,'Desenvolver o conhecimento na organização.'), 
(463,'Proteger o conhecimento na organização.'),
(463,'Compartilhar o conhecimento na organização.'),

(464,'Definir organização do trabalho.'),
(464,'Implementar organização do trabalho.'),

(465,'Gerenciar o desempenho das pessoas, de forma a estimular a busca por melhores resultados.'),
(465,'Gerenciar o desempenho das equipes, de forma a estimular a busca por melhores resultados.'),

(466,'Identificar as necessidades de capacitação considerando as estratégias.'),
(466,'Identificar as necessidades de capacitação considerando as necessidades das pessoas.'),

(466,'Identificar as necessidades de desenvolvimento considerando as estratégias.'),
(466,'Identificar as necessidades de desenvolvimento considerando as necessidades das pessoas.'),

(467,'Conceber a forma de realização dos programas de capacitação considerando as necessidades identificadas.'),
(467,'Conceber a forma de realização dos programas de desenvolvimento considerando as necessidades identificadas.'),

(468,'Identificar os perigos relacionados a saúde ocupacional.'),
(468,'Identificar os perigos relacionados a segurança.'),
(468,'Identificar os perigos relacionados a ergonomia.'),

(468,'Tratar os perigos relacionados a saúde ocupacional.'),
(468,'Tratar os perigos relacionados a segurança.'),
(468,'Tratar os perigos relacionados a ergonomia.'),

(469,'Identificar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(469,'Identificar os fatores que afetam a satisfação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(469,'Identificar os fatores que afetam a motivação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),

(469,'Tratar os fatores que afetam o bem-estar das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(469,'Tratar os fatores que afetam a satisfação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),
(469,'Tratar os fatores que afetam a motivação das pessoas e manter um clima organizacional favorável ao alto desempenho.'),

(470,'Avaliar a satisfação das pessoas.'),

(471,'Projetar processos de apoio, visando ao cumprimento dos requisitos aplicáveis.'),
(471,'Projetar processos finalísticos, visando ao cumprimento dos requisitos aplicáveis.'),

(472,'Controlar os processos de apoio, visando ao cumprimento dos requisitos aplicáveis.'),
(472,'Controlar os processos finalísticos, visando ao cumprimento dos requisitos aplicáveis.'),

(473,'Analisar os processos finalísticos.'),
(473,'Analisar os processos de apoio.'),
(473,'Melhorar os processos finalísticos.'),
(473,'Melhorar os processos de apoio.'),

(474,'Selecionar os fornecedores.'),

(475,'Avaliar o desempenho de fornecedores.'),
(475,'Prontamente informar o desempenho aos fornecedores.'),

(476,'Elaborar o orçamento.'),
(476,'Gerenciar o orçamento.'),

(477,'Apresentar os resultados relativos aos cidadãos-usuários.'),

(478,'Apresentar os resultados relativos à sociedade.'),

(479,'Apresentar os resultados orçamentários.'),
(479,'Apresentar os resultados financeiros.'),

(480,'Apresentar os resultados relativos às pessoas.'),

(481,'Apresentar os resultados relativos aos processos de suprimento.'),

(482,'Apresentar os resultados dos processos finalísticos.'),
(482,'Apresentar os resultados dos processos de apoio.'),

(483,'Tomar decisões relativas aos principais processos.'),

(484,'Tomar decisões relativas ao plano de gestão.'),

(485,'Comunicar as principais decisões tomadas, de caráter ostensivo, a todos os escalões e integrantes da OM'),

(486,'Implementar decisões em cada escalão da OM.'),

(487,'Controlar resultado das seções do EM ou equivalentes.'),

(488,'Prestar conta dos resultados organizacionais ao escalão enquadrante.'),

(489,'Estabelecer os valores, contidos no Plano de Gestão, necessários ao cumprimento de sua Missão.'),

(490,'Atualizar os valores, contidos no Plano de Gestão, necessários ao cumprimento de sua Missão.'),

(491,'Disseminar os princípios da Administração Pública (legalidade, impessoalidade, moralidade, publicidade e eficiência) para todos os integrantes da OM.'),

(492,'Descrever a atuação do Comando junto ao Escalão Superior para melhorar as condições de cumprimento da missão.'),
(492,'Descrever a atuação do Comando junto e ao Escalão de Apoio para melhorar as condições de cumprimento da missão.'),

(493,'Verificar se os valores são aplicados pelos integrantes da OM.'),

(494,'Estabelecer os principais padrões de trabalho para regular as atividades internas da OM.'),
(494,'Estabelecer os principais padrões de trabalho para regular as atividades externas da OM.'),

(495,'Verificar o cumprimento dos padrões de trabalho das atividades da OM.'),
(495,'Verificar o cumprimento das regras de funcionamento das atividades da OM.'),

(496,'Apresentar os Indicadores de Desempenho utilizados para possibilitar as ações corretivas em cada processo.'),

(497,'Descrever a atuação do Comando na busca de parcerias para facilitar o cumprimento da missão da OM e do EB.'),
(497,'Descrever a atuação do Comando na busca de parcerias para facilitar a capacitação do pessoal da OM e do EB.'),
(497,'Descrever a atuação do Comando na busca de parcerias para melhorar a imagem da OM e do EB.'),

(498,'Disseminar os valores da OM pelo Comando aos seus integrantes.'),
(499,'Utilizar os valores da OM pelos seus integrantes.'),

(500,'Atuar para verificar se os valores são de conhecimento de todos os integrantes da OM.'),

(624,'Assegurar a equidade entre sócios, mantenedores ou instituidores.'),
(624,'Proteger os direitos das partes interessadas.'),
(625,'Estabelecer os valores e princípios organizacionais necessários à promoção da excelência.'), 
(625,'Atualizar os valores e princípios organizacionais necessários à promoção da excelência.'),
(625,'Estabelecer valor para todas as partes interessadas e ao desenvolvimento sustentável.'),
(625,'Atualizar os valores para todas as partes interessadas e ao desenvolvimento sustentável.'),
(626,'Estabelecer regras de conduta para os integrantes da sua administração.'),
(626,'Estabelecer regras de conduta para a força de trabalho.'),
(626,'Tratar as questões éticas.'),
(626,'Buscar  um relacionamento ético com concorrentes e com as partes interessadas.'),
(626,'Assegurar um relacionamento ético com concorrentes e com as partes interessadas.'),
(627,'Identificar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organização de alcançar os objetivos estratégicos do negócio.'),
(627,'Classificar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organização de alcançar os objetivos estratégicos do negócio.'),
(627,'Analisar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organização de alcançar os objetivos estratégicos do negócio.'),
(627,'Tratar os riscos empresariais mais significativos, que possam afetar a imagem e a capacidade da organização de alcançar os objetivos estratégicos do negócio.'),
(628,'Tomar as principais decisões para assegurar a transparência levando em consideração o envolvimento dos principais interessados nos temas tratados.'),
(628,'Comunicar as principais decisões para assegurar a transparência levando em consideração o envolvimento dos principais interessados nos temas tratados.'),
(628,'Implementar as principais decisões para assegurar a transparência levando em consideração o envolvimento dos principais interessados nos temas tratados.'),
(629,'Comunicar prontamente os fatos relevantes à sociedade.'),
(629,'Comunicar prontamente os fatos relevantes às demais partes interessadas.'),
(630,'Prestar contas das suas ações a quem a elegeu, nomeou ou designou.'),
(630,'Prestar contas dos resultados alcançados a quem a elegeu, nomeou ou designou.'),
(631,'Exercer a liderança com as partes interessadas, identificando suas expectativas.'),
(631,'Exercer a liderança com as partes interessadas, buscando o alinhamento de interesses.'),
(631,'Interagir com as partes interessadas, identificando suas expectativas.'),
(631,'Interagir com as partes interessadas, buscando o alinhamento de interesses.'),
(632,'Identificar as mudanças culturais necessárias para a internalização dos valores princípios organizacionais e para o êxito das estratégias.'),
(632,'Desenvolver as mudanças culturais necessárias para a internalização dos valores princípios organizacionais e para o êxito das estratégias.'),
(633,'Comunicar os valores à força de trabalho e demais partes interessadas.'),
(633,'Comunicar os princípios organizacionais à força de trabalho e demais partes interessadas.'),
(634,'Avaliar nos líderes, as competências necessárias para o exercício da liderança.'),
(634,'Desenvolver nos líderes, as competências necessárias para o exercício da liderança.'),
(635,'Estabelecer os principais e padrões de trabalho para os processos gerenciais.'),
(635,'Verificar o cumprimento dos principais padrões de trabalho para os processos gerenciais.'),
(636,'Refinar os processos gerenciais por meio do aprendizado.'), 
(636,'Inovar os processos gerenciais por meio do aprendizado.'),
(637,'Investigar as boas práticas de gestão das organizações de referência para apoiar o aprendizado.'),
(638,'Identificar as necessidades de informações comparativas para analisar o desempenho operacional.'), 
(638,'Identificar as necessidades de informações comparativas para analisar o desempenho estratégico da organização.'),
(639,'Avaliar o desempenho operacional da organização, visando ao desenvolvimento sustentável.'),
(639,'Avaliar o desempenho estratégico da organização, visando ao desenvolvimento sustentável.'),
(640,'Comunicar à força de trabalho as decisões decorrentes da análise do desempenho da organização.'),
(640,'Comunicar em todos os níveis da organização as decisões decorrentes da análise do desempenho da organização.'),
(640,'Comunicar a outras partes interessadas as decisões decorrentes da análise do desempenho da organização.'),
(641,'Acompanhar a implementação das decisões decorrentes da análise do desempenho da organização.'),
(642,'Analisar o macroambiente e as características do setor de atuação da organização e suas tendências.'),
(642,'Analisar o macroambiente e as características do setor de atuação da organização e suas tendências.'),
(642,'Identificar o macroambiente e as características do setor de atuação da organização e suas tendências.'),
(643,'Analisar o mercado de atuação da organização.'), 
(643,'Analisar as tendências do mercado de atuação da organização.'),
(644,'Analisar o ambiente interno da organização.'),
(645,'Avaliar as alternativas decorrentes das análises dos ambientes.'),
(645,'Definir as estratégias da organização.'),
(646,'Avaliar o modelo de negócio em consonância com a definição das estratégias, visando à potencialização de seu êxito.'),
(647,'Definir os indicadores para a avaliação da implementação das estratégias e definir os respectivos planos de ação.'),
(647,'Estabelecer as metas de curto e longo prazo e definir os respectivos planos de ação.'),
(648,'Desdobar as metas e os planos de ação nas áreas responsáveis pelos processos principais do negócio e processos de apoio.'),
(648,'Assegurar a coerência das metas e dos planos resultantes com as estratégias e também entre si.'),
(648,'Manter o alinhamento entre os indicadores utilizados na avaliação do desempenho estratégico e aqueles utilizados na avaliação do desempenho operacional.'),
(649,'Alocar os recursos para assegurar a implementação dos principais planos de ação.'),
(650,'Comunicar as estratégias para as pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(650,'Comunicar as metas para as pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(650,'Comunicar os planos de ação para as pessoas da força de trabalho e para as demais partes interessadas, quando pertinente.'),
(651,'Realizar o monitoramento da implementação dos planos de ação.'),
(652,'Segmentar o mercado.'),
(653,'Definir os clientes-alvo nso segmentos da organização, considerando-se, inclusive, os clientes da concorrência, quando existirem, e os clientes e mercados potenciais.'),
(654,'Identificar as necessidades e as expectativas dos clientes, atuais e potenciais, de ex-clientes e de usuários para definir a melhoria dos produtos e processos da organização.'),
(654,'Analisar as necessidades e as expectativas dos clientes, atuais e potenciais, de ex-clientes e de usuários para definir a melhoria dos produtos e processos da organização.'),
(654,'Utilizar as necessidades e as expectativas dos clientes, atuais e potenciais, de ex-clientes e de usuários para definir a melhoria dos produtos e processos da organização.'),
(655,'Divulgar para os clientes as marcas, os produtos, incluindo os cuidados necessários ao seu uso e os riscos envolvidos, e também as ações de melhoria da organização de forma a criar credibilidade.'),
(655,'Divulgar para os clientes as marcas, os produtos, incluindo os cuidados necessários ao seu uso e os riscos envolvidos, e também as ações de melhoria da organização de forma a criar confiança.'),
(655,'Divulgar para os clientes as marcas, os produtos, incluindo os cuidados necessários ao seu uso e os riscos envolvidos, e também as ações de melhoria da organização de forma a criar imagem positiva.'),
(656,'Identificar os níveis de conhecimento dos clientes e mercados a respeito das marcas da organização.'),
(656,'Identificar os níveis de conhecimento dos clientes e mercados a respeito dos produtos da organização.'),
(656,'Avaliar os níveis de conhecimento dos clientes a respeito das marcas da organização.'),
(656,'Avaliar os níveis de conhecimento dos clientes a respeito dos produtos da organização.'),
(656,'Avaliar os níveis de conhecimento mercados a respeito dos produtos da organização.'),
(656,'Avaliar os níveis de conhecimento mercados a respeito dos produtos da organização.'),
(657,'Avaliar a imagem da organização perante os clientes.'),
(657,'Avaliar a imagem da organização perante os mercados.'),
(658,'Definir os canais de relacionamento com os clientes, considerando-se a segmentação do mercado.'),
(658,'Definir os canais de relacionamento com os clientes, considerando-se o agrupamento de clientes utilizado.'),
(658,'Divulgar para os clientes os canais de relacionamento, considerando-se a segmentação do mercado.'),
(658,'Divulgar para os clientes os canais de relacionamento, considerando-se o agrupamento de clientes utilizado.'),
(659,'Tratar as solicitações, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(659,'Tratar as reclamações, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(659,'Tratar as sugestões, formais ou informais, dos clientes visando a assegurar que sejam pronta e eficazmente atendidas ou solucionadas.'),
(660,'Acompanhar as transações com os clientes, de forma a permitir à organização gerar soluções rápidas e eficazes.'),
(660,'Acompanhar as transações com os clientes, de forma a permitir à organização evitar problemas de relacionamento.'),
(660,'Acompanhar as transações com os clientes, de forma a permitir à organização atender às expectativas dos clientes.'),
(661,'Avaliar a satisfação dos clientes, inclusive em relação aos clientes dos concorrentes ou, quando não houver concorrência, de outras organizações de referência.'),
(661,'Avaliar a fidelidade, inclusive em relação aos clientes dos concorrentes ou, quando não houver concorrência, de outras organizações de referência.'),
(661,'Avaliar a insatisfação dos clientes, inclusive em relação aos clientes dos concorrentes ou, quando não houver concorrência, de outras organizações de referência.'),
(662,'Analisar as informações obtidas dos clientes para intensificar a sua satisfação.'),
(662,'Analisar as informações obtidas dos clientes para torná-los fiéis.'),
(662,'Analisar as informações obtidas dos clientes para incentivá-los que recomendem os produtos da organização.'),
(662,'Analisar as informações obtidas dos clientes para que sejam desenvolvolvidos processos e produtos.'),
(663,'Identificar parcerias com clientes, visando à manutenção ou ao aumento da competitividade da organização.'),
(663,'Identificar parcerias com distribuidores, visando à manutenção ou ao aumento da competitividade da organização.'),
(663,'Identificar parcerias com revendedores visando à manutenção ou ao aumento da competitividade da organização.'),
(663,'Desenvolver parcerias com clientes visando à manutenção ou ao aumento da competitividade da organização.'),
(663,'Desenvolver parcerias com distribuidores visando à manutenção ou ao aumento da competitividade da organização.'),
(663,'Desenvolver parcerias revendedores visando à manutenção ou ao aumento da competitividade da organização.'),
(664,'Identificar os aspectos e tratar os impactos sociais adversos de seus produtos.'),
(664,'Identificar os aspectos e tratar os impactos sociais adversos de seus processos.'),
(664,'Identificar os aspectos e tratar os impactos sociais adversos de suas instalações.'),
(665,'Manter organização preparada para prevenir acidentes, visando prevenir ou mitigar os seus impactos adversos na sociedade, incluindo aqueles em comunidades potencialmente impactadas.'),
(665,'Responder às eventuais situações de emergência, visando prevenir ou mitigar os seus impactos adversos na sociedade, incluindo aqueles em comunidades potencialmente impactadas.'),
(666,'Comunicar os impactos sociais e ambientais dos produtos, as ações e os resultados relativos à responsabilidade socioambiental à sociedade, incluindo as comunidades potencialmente impactadas.'),
(666,'Comunicar os impactos sociais e ambientais dos processos e instalações, assim como as políticas, as ações e os resultados relativos à responsabilidade socioambiental à sociedade, incluindo as comunidades potencialmente impactadas.'),
(667,'Identificar os requisitos legais aplicáveis a questões socioambientais e implementar ações de melhoria visando ao seu pleno atendimento.'),
(667,'Identificar os requisitos regulamentares aplicáveis a questões socioambientais e implementar ações de melhoria visando ao seu pleno atendimento.'),
(667,'Identificar os requisitos contratuais aplicáveis a questões socioambientais e implementar ações de melhoria visando ao seu pleno atendimento.'),
(667,'Analisar os requisitos legais aplicáveis a questões socioambientais e implementar ações de melhoria visando ao seu pleno atendimento.'),
(667,'Analisar os requisitos regulamentares aplicáveis a questões socioambientais e implementar ações de melhoria visando ao seu pleno atendimento.'),
(667,'Analisar os requisitos contratuais aplicáveis a questões socioambientais e implementar ações de melhoria visando ao seu pleno atendimento.'),
(668,'Propiciar a acessibilidade aos produtos da organização.'),
(668,'Propiciar a acessibilidade as instalações da organização.'),
(669,'Selecionar, de forma voluntária, ações com vista ao desenvolvimento sustentável.'),
(669,'Promover, de forma voluntária, ações com vista ao desenvolvimento sustentável.'),
(670,'Identificar as necessidades da sociedade, incluindo comunidades vizinhas às instalações da organização.'),
(670,'Analisar as necessidades da sociedade, incluindo comunidades vizinhas às instalações da organização.'),
(670,'Utilizar as necessidades da sociedade, incluindo comunidades vizinhas às instalações da organização.'),
(670,'Identificar as expectativas da sociedade, incluindo comunidades vizinhas às instalações da organização.'),
(670,'Analisar as expectativas da sociedade, incluindo comunidades vizinhas às instalações da organização.'),
(670,'Utilizar as expectativas da sociedade, incluindo comunidades vizinhas às instalações da organização.'),
(671,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento nacional.'),
(671,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento regional.'),
(671,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento local.'),
(671,'Direcionar os esforços para o fortalecimento da sociedade, incluindo as comunidades vizinhas, apoiando projetos sociais voltados para o desenvolvimento setorial.'),
(672,'Avaliar o grau de satisfação da sociedade, incluindo comunidades vizinhas, em relação à organização.'),
(673,'Analisar as informações obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfação e para aperfeiçoar projetos sociais.'),
(673,'Analisar as informações obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfação e para desenvolver projetos sociais.'),
(673,'Utilizar as informações obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfação e para aperfeiçoar projetos sociais.'),
(673,'Utilizar as informações obtidas da sociedade, incluindo comunidades vizinhas, para intensificar a sua satisfação e para desenvolver projetos sociais.'),
(674,'Avaliar sua imagem perante a sociedade, incluindo comunidades vizinhas.'),
(674,'Zelar por sua imagem perante a sociedade, incluindo comunidades vizinhas.'),
(675,'Identificar as necessidades de informações e de seu tratamento para apoiar as operações diárias.'),
(675,'Acompanhar o progresso dos planos de ação.'),
(675,'Subsidiar a tomada de decisões em todos os níveis e áreas da organização.'),
(676,'Definir os principais sistemas de informação, visando atender às necessidades identificadas.'),
(676,'Desenvolver os principais sistemas de informação, visando atender às necessidades identificadas.'),
(676,'Implantar os principais sistemas de informação, visando atender às necessidades identificadas.'),
(676,'Melhorar os principais sistemas de informação, visando atender às necessidades identificadas.'),
(677,'Analisar a tecnologia de informação para alavancar o negócio.'),
(677,'Analisar a tecnologia de informação para promover a integração da organização com as partes interessadas.'),
(677,'Utilizar a tecnologia de informação para alavancar o negócio.'),
(677,'Utilizar a tecnologia de informação para promover a integração da organização com as partes interessadas.'),
(678,'Compatibilizar a infraestrutura para a disponibilização das informações aos usuários, internos e externos à organização, com o crescimento do negócio.'),
(678,'Compatibilizar a infraestrutura para a disponibilização das informações aos usuários, internos e externos à organização, com a demanda por informações.'),
(679,'Garantir a segurança das informações.'),
(680,'Identificar os ativos intangíveis que mais agregam valor ao negócio, gerando um diferencial competitivo para a organização. '),
(681,'Desenvolver os atívos intangíveis.'),
(681,'Proteger os atívos intangíveis.'),
(682,'Identificar os conhecimentos que sustentam o desenvolvimento das estratégias.'),
(682,'Identificar os conhecimentos que sustentam o desenvolvimento das operações.'),
(682,'Desenvolver os conhecimentos que sustentam o desenvolvimento das estratégias.'),
(682,'Desenvolver os conhecimentos que sustentam o desenvolvimento das operações.'),
(683,'Compartilhar os conhecimentos da organização.'),
(683,'Reter os conhecimentos da organização.'),
(684,'Elaborar a organização do trabalho em alinhamento com o modelo de negócio, visando ao alto desempenho e à inovação.'),
(684,'Elaborar a organização do trabalho em alinhamento com os processos visando ao alto desempenho e à inovação.'),
(684,'Elaborar a organização do trabalho em alinhamento com os valores visando ao alto desempenho e à inovação.'),
(684,'Elaborar a organização do trabalho em alinhamento com a estratégia da organização, visando ao alto desempenho e à inovação.'),
(684,'Implementar a organização do trabalho em alinhamento com o modelo de negócio, visando ao alto desempenho e à inovação.'),
(684,'Implementar a organização do trabalho em alinhamento com os processos visando ao alto desempenho e à inovação.'),
(684,'Implementar a organização do trabalho em alinhamento com os valores visando ao alto desempenho e à inovação.'),
(684,'Implementar a organização do trabalho em alinhamento com a estratégia da organização, visando ao alto desempenho e à inovação.'),
(685,'Selecionar e contratar, internamente, pessoas para a força de trabalho.'),
(685,'Selecionar e contratar, externamente, pessoas para a força de trabalho.'),
(686,'Integrar pessoas recém-contratadas à cultura organizacional, visando prepará-las para o pleno exercício das suas funções.'),
(687,'Avaliar o desempenho das pessoas e das equipes de modo a estimular a obtenção de metas de alto desempenho.'),
(687,'Avaliar o desempenho das pessoas e das equipes de modo a estimular a cultura da excelência na organização.'),
(687,'Avaliar o desempenho das pessoas e das equipes de modo a estimular o desenvolvimento profissional das mesmas.'),
(688,'Estimular o alcance de metas, através da remuneração.'),
(688,'Estimular o alcance de metas, através do reconhecimento.'),
(688,'Estimular o alcance de metas, através dos incentivos.'),
(689,'Identificar as necessidades de capacitação e desenvolvimento das pessoas, visando ao êxito de estratégias.'),
(689,'Identificar as necessidades de capacitação e desenvolvimento das pessoas, visando à formação da cultura da excelência.'),
(689,'Identificar as necessidades de capacitação e desenvolvimento das pessoas, visando à melhoria do desempenho individual.'),
(690,'Conceber a forma de realização dos programas de desenvolvimento, considerando as necessidades identificadas.'),
(690,'Conceber a forma de realização dos programas de capacitação, considerando as necessidades identificadas.'),
(691,'Avaliar a eficácia dos programas de capacitação em relação ao alcance dos objetivos estratégicos da organização.'),
(691,'Avaliar a eficácia dos programas de capacitação em relação ao alcance dos objetivos operacionais da organização.'),
(692,'Promover o desenvolvimento integral das pessoas como indivíduos.'),
(692,'Promover o desenvolvimento integral das pessoas como cidadãos.'),
(692,'Promover o desenvolvimento integral das pessoas como profissionais.'),
(693,'Identificar os perigos relacionados à saúde ocupacional.'),
(693,'Identificar os perigos relacionados à segurança.'),
(693,'Tratar os riscos relacionados à saúde ocupacional.'),
(693,'Tratar os riscos relacionados à segurança.'),
(694,'Identificar as necessidades das pessoas da força de trabalho e do mercado para o desenvolvimento de políticas e programas de pessoal e dos benefícios a elas oferecidos.'),
(694,'Identificar as expectativas das pessoas da força de trabalho e do mercado para o desenvolvimento de políticas e programas de pessoal e dos benefícios a elas oferecidos.'),
(694,'Analisar as necessidades das pessoas da força de trabalho e do mercado para o desenvolvimento de políticas e programas de pessoal e dos benefícios a elas oferecidos.'),
(694,'Analisar as expectativas das pessoas da força de trabalho e do mercado para o desenvolvimento de políticas e programas de pessoal e dos benefícios a elas oferecidos.'),
(694,'Utilizar as necessidades das pessoas da força de trabalho e do mercado para o desenvolvimento de políticas e programas de pessoal e dos benefícios a elas oferecidos.'),
(694,'Utilizar as expectativas das pessoas da força de trabalho e do mercado para o desenvolvimento de políticas e programas de pessoal e dos benefícios a elas oferecidos.'),
(695,'Avaliar o bem-estar das pessoas.'),
(695,'Avaliar a satisfação das pessoas.'),
(695,'Avaliar o comprometimento das pessoas.'),
(695,'Desenvolver o bem-estar das pessoas.'),
(695,'Desenvolver a satisfação das pessoas.'),
(695,'Desenvolver o comprometimento das pessoas.'),
(696,'Manter um clima organizacional favorável à criatividade das pessoas.'),
(696,'Manter um clima organizacional favorável à inovação das pessoas.'),
(696,'Manter um clima organizacional favorável à excelência no desenpenho das pessoas.'),
(696,'Manter um clima organizacional favorável ao desenvolvimento profissional das pessoas.'),
(696,'Manter um clima organizacional favorável à criatividade das equipes.'),
(696,'Manter um clima organizacional favorável à inovação das equipes.'),
(696,'Manter um clima organizacional favorável à excelência no desenpenho das equipes.'),
(696,'Manter um clima organizacional favorável ao desenvolvimento profissional das equipes.'),
(697,'Colaborar para a melhoria da qualidade de vida das pessoas fora do ambiente de trabalho.'),
(698,'Determinar os requisitos aplicáveis aos produtos considerando-se as necessidades dos clientes, e sua importância relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplicáveis aos processos principais do negócio considerando-se as necessidades dos clientes, e sua importância relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplicáveis aos processos de apoio do negócio considerando-se as necessidades dos clientes, e sua importância relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplicáveis aos produtos considerando-se as expectativas dos clientes, e sua importância relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplicáveis aos processos principais do negócio considerando-se as expectativas dos clientes, e sua importância relativa, e de outras partes interessadas.'),
(698,'Determinar os requisitos aplicáveis aos processos de apoio considerando-se as expectativas dos clientes, e sua importância relativa, e de outras partes interessadas.'),
(699,'Desenvolver novos produtos visando ao atendimento de requisitos estabelecidos.'),
(699,'Desenvolver à superação de requisitos estabelecidos ao atendimento ou à superação de requisitos estabelecidos.'),
(700,'Projetar os processos principais do negócio, visando ao atendimento de requisitos estabelecidos.'),
(700,'Projetar os processos de apoio do negócio, visando ao atendimento de requisitos estabelecidos.'),
(700,'Projetar os processos principais do negócio, visando à superação de requisitos estabelecidos.'),
(700,'Projetar os processos de apoio do negócio, visando à superação de requisitos estabelecidos.'),
(701,'Avaliar o potencial de idéias criativas para que convertam-se em inovações.'),
(701,'Avaliar o potencial de idéias criativas para que convertam-se em produtos.'),
(701,'Avaliar o potencial de idéias criativas para que convertam-se em processos.'),
(702,'Assegurar o atendimento dos requisitos aplicáveis aos processos principais do negócio.'),
(702,'Assegurar o atendimento dos requisitos aplicáveis aos processos de apoio do negócio.'),
(703,'Analisar os produtos.'),
(703,'Analisar os os processos principais do negócio.'),
(703,'Analisar os processos de apoio do negócio.'),
(703,'Melhorar os produtos.'),
(703,'Melhorar os os processos principais do negócio.'),
(703,'Melhorar os processos de apoio do negócio.'),
(704,'Investigar as características de produtos de concorrentes ou de outras organizações de referência para melhorar os próprios.'),
(704,'Investigar as características de processos principais do negócio de concorrentes ou de outras organizações de referência para melhorar os próprios.'),
(704,'Investigar as características de processos de apoio de concorrentes ou de outras organizações de referência para melhorar os próprios.'),
(705,'Desenvolver a sua cadeia de suprimentos imediata e nela identificar potenciais fornecedores e parceiros visando assegurar a continuidade de fornecimento no longo prazo e agregar valor ao negócio.'),
(705,'Desenvolver a sua cadeia de suprimentos imediata e nela identificar potenciais fornecedores e parceiros visando melhorar o desempenho e agregar valor ao negócio.'),
(705,'Desenvolver a sua cadeia de suprimentos imediata e nela identificar potenciais fornecedores e parceiros visando promover o desenvolvimento sustentável da própria cadeia e agregar valor ao negócio.'),
(706,'Identificar as necessidades e expectativas dos fornecedores, para a definição e a melhoria das políticas e dos programas relativos aos fornecedores.'),
(706,'Analisar as necessidades e expectativas dos fornecedores para a definição e a melhoria das políticas e dos programas relativos aos fornecedores.'),
(706,'Utilizar as necessidades e expectativas dos fornecedores para a definição e a melhoria das políticas e dos programas relativos aos fornecedores.'),
(707,'Qualificar os fornecedores.'),
(707,'Selecionar os fornecedores.'),
(708,'Assegurar o atendimento aos requisitos da organização por parte dos fornecedores.'),
(709,'Estimular a melhoria nos processos de suprimento e nos produtos supridos pelos fornecedores.'),
(709,'Estimular a inovação nos processos de suprimento e nos produtos supridos pelos fornecedores.'),
(710,'Envolver os fornecedores que atuam diretamente nos processos da organização com os valores e princípios organizacionais, incluindo os relativos à responsabilidade socioambiental.'),
(710,'Envolver os fornecedores que atuam diretamente nos processos da organização com os valores e princípios organizacionais, incluindo os relativos à saúde.'),
(710,'Envolver os fornecedores que atuam diretamente nos processos da organização com os valores e princípios organizacionais, incluindo os relativos à segurança.'),
(710,'Comprometer os fornecedores que atuam diretamente nos processos da organização  com os valores e princípios organizacionais, incluindo os relativos à responsabilidade socioambiental.'),
(710,'Comprometer os fornecedores que atuam diretamente nos processos da organização  com os valores e princípios organizacionais, incluindo os relativos à saúde.'),
(710,'Comprometer os fornecedores que atuam diretamente nos processos da organização  com os valores e princípios organizacionais, incluindo os relativos à segurança.'),
(710,'Envolver fornecedores com os valores da organização'),
(710,'Envolver fornecedores com os princípios organizacionais.'),
(711,'Determinar os requisitos de desempenho econômico-financeiro da organização.'),
(711,'Gerenciar os aspectos que causam impacto na sustentabilidade econômica do negócio.'),
(712,'Assegurar os recursos financeiros necessários para atender às necessidades operacionais.'),
(713,'Definir os recursos financeiros visando a dar suporte para as estratégias.'),
(713,'Definir os recursos financeiros visando a dar suporte para os planos de ação.'),
(713,'Avaliar os recursos financeiros visando a dar suporte para as estratégias.'),
(713,'Avaliar os recursos financeiros visando a dar suporte para os planos de ação.'),
(714,'Quantificar os riscos financeiros da organização.'),
(714,'Monitorar os riscos financeiros da organização.'),
(715,'Elaborar o orçamento visando assegurar o atendimento dos níveis esperados de desempenho.'),
(715,'Controlar o orçamento visando assegurar o atendimento dos níveis esperados de desempenho.'),
(716,'Apresentar os resultados dos principais indicadores relativos à gestão econômico-financeira, classificando-os segundo os grupos de:<br>estrutura, liquidez, atividade e rentabilidade. Estratificar os resultados por unidades ou filiais, quando aplicáveis.'),
(717,'Apresentar os resultados dos principais indicadores relativos aos clientes e aos mercados, incluindo os referentes à imagem da organização. Estratificar por grupos de clientes, segmentos de mercado ou tipos de produtos, quando aplicáveis.'),
(718,'Apresentar os resultados dos principais indicadores relativos à sociedade, incluindo os relativos à responsabilidade socioambiental e ao desenvolvimento social. Estratificar os resultados por instalações ou por comunidades, quando aplicável.'),
(719,'Apresentar os resultados dos principais indicadores relativos às pessoas, incluindo os relativos aos sistemas de trabalho, à capacitação e desenvolvimento e à qualidade de vida e os de liderança de pessoas e de promoção da cultura da excelência. Estratificar os resultados por grupos de pessoas da força de trabalho, funções na organização e, quando aplicável, por instalações.'),
(720,'Apresentar os resultados dos indicadores relativos aos produtos, de processos principais do negócio e processos de apoio e de processos de gestão transversais não pertinentes aos demais itens.'),
(721,'Apresentar os resultados dos principais indicadores relativos aos produtos recebidos dos fornecedores e à gestão de fornecedores. Estratificar os resultados por grupos de fornecedores ou tipos de produtos adquiridos, quando aplicáveis.');