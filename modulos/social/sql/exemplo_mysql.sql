SET FOREIGN_KEY_CHECKS=0;

DELETE FROM social_acao_conceder;

INSERT INTO social_acao_conceder (social_acao_conceder_id, social_acao_conceder_acao, social_acao_conceder_campo, social_acao_conceder_situacao) VALUES 
  (1,1,'social_familia_nis','IS NOT NULL'),
  (2,1,'social_familia_renda_valor','< 200');
  
DELETE FROM social;

INSERT INTO social (social_id, social_nome, social_cia, social_responsavel, social_descricao, social_cor, social_ativo, social_acesso, social_tipo) VALUES 
  (1,'Água para Todos',1,1,'O Programa Água para Todos representa um importante passo em direção a melhoria da qualidade de vida dos brasileiros. O programa articula a implementação de um conjunto de ações que garantirá tanto o acesso à água com qualidade e em quantidade, como sua permanência ao longo do tempo.','ffffd6',1,0,NULL);

DELETE FROM social_acao;

INSERT INTO social_acao (social_acao_id, social_acao_social, social_acao_nome, social_acao_responsavel, social_acao_descricao, social_acao_inicial, social_acao_adquirido, social_acao_final, social_acao_instalado, social_acao_instalar, social_acao_cor, social_acao_produto, social_acao_orgao, social_acao_financiador, social_acao_codigo, social_acao_declaracao) VALUES 
  (1,1,'Cisterna de consumo',1,'300.000 cisternas, de 20.000 litros cada, a serem intaladas nas residencias carentes sem acesso à água.','Demanda inicial de cisternas','Cisternas adquiridas','Demanda final de cisternas','Cisternas instaladas','Cisternas a instalar','9bf1fe','da Cisterna','Codevasf','Ministério da Integração Nacional','Cisterna Nº','Declaro que recebi do Programa Água Para Todos (01) uma Cisterna de Polietileno com capacidade de 16.000 litros com todos os seus componentes e em perfeito estado para funcionamento.');


DELETE FROM social_acao_lista;

INSERT INTO social_acao_lista (social_acao_lista_id, social_acao_lista_acao_id, social_acao_lista_tipo, social_acao_lista_ordem, social_acao_lista_descricao, social_acao_lista_justificativa, social_acao_lista_peso, social_acao_lista_data, social_acao_lista_usuario, social_acao_lista_final, social_acao_lista_parcial) VALUES 
  (1,1,0,1,'Capacitação',NULL,1.000,'2012-02-10 11:58:48',1,0,0),
  (2,1,0,2,'Escavação',NULL,1.000,'2012-02-10 11:59:20',1,0,0),
  (3,1,0,3,'Telhado pronto',NULL,1.000,'2012-02-10 11:59:39',1,0,0),
  (4,1,0,4,'Transporte até a família',NULL,1.000,'2012-02-10 11:59:47',1,0,0),
  (5,1,0,5,'Calha',NULL,1.000,'2012-02-10 12:07:04',1,0,0),
  (6,1,0,6,'Tubos condutores',NULL,1.000,'2012-02-10 12:07:04',1,0,0),
  (7,1,0,7,'Base de solo cimento',NULL,1.000,'2012-02-10 12:07:04',1,0,0),
  (8,1,0,8,'Bomba',NULL,1.000,'2012-02-10 12:07:04',1,0,0),
  (9,1,0,9,'Assentar cisterna',NULL,1.000,'2012-02-10 12:07:04',1,0,0),
  (10,1,0,10,'Teste',NULL,1.000,'2012-02-10 12:07:04',1,0,0),
  (11,1,0,11,'Termo de recebimento',NULL,1.000,'2012-05-13 21:33:33',1,1,0),
  (12,1,4,1,'Treinamento de opera&ccedil;&atilde;o de cisterna',NULL,1.000,'2012-02-20 11:45:46',1,0,0),
  (13,1,4,2,'Capacita&ccedil;&atilde;o na utiliza&ccedil;&atilde;o do GP-Web para monitorar as atividades',NULL,1.000,'2012-02-20 11:46:19',1,0,0),
  (14,1,4,3,'Palestra de sensibiliza&ccedil;&atilde;o do programa &Aacute;gua para Todos',NULL,1.000,'2012-02-20 11:46:59',1,0,0),
  (15,1,3,1,'Treinamento de opera&ccedil;&atilde;o de cisterna',NULL,1.000,'2012-02-20 11:47:33',1,0,0),
  (16,1,3,2,'Capacita&ccedil;&atilde;o na utiliza&ccedil;&atilde;o do GP-Web para monitorar as atividades',NULL,1.000,'2012-02-20 11:47:41',1,0,0),
  (17,1,3,3,'Palestra de sensibiliza&ccedil;&atilde;o do programa &Aacute;gua para Todos',NULL,1.000,'2012-02-20 11:47:50',1,0,0),
  (18,1,2,1,'Treinamento de opera&ccedil;&atilde;o de cisterna',NULL,1.000,'2012-02-20 11:48:09',1,0,0),
  (19,1,2,2,'Capacita&ccedil;&atilde;o na utiliza&ccedil;&atilde;o do GP-Web para monitorar as atividades',NULL,1.000,'2012-02-20 11:48:15',1,0,0),
  (20,1,2,3,'Palestra de sensibiliza&ccedil;&atilde;o do programa &Aacute;gua para Todos',NULL,1.000,'2012-02-20 11:48:22',1,0,0),
  (21,1,1,1,'Treinamento de opera&ccedil;&atilde;o de cisterna',NULL,1.000,'2012-02-20 11:48:51',1,0,0),
  (22,1,1,2,'Capacita&ccedil;&atilde;o na utiliza&ccedil;&atilde;o do GP-Web para monitorar as atividades',NULL,1.000,'2012-02-20 11:48:59',1,0,0),
  (23,1,1,3,'Palestra de sensibiliza&ccedil;&atilde;o do programa &Aacute;gua para Todos',NULL,1.000,'2012-02-20 11:49:08',1,0,0);

DELETE FROM social_acao_negacao;

INSERT INTO social_acao_negacao (social_acao_negacao_id, social_acao_negacao_acao_id, social_acao_negacao_ordem, social_acao_negacao_justificativa) VALUES 
  (1,1,1,'Já possui acesso à agua potável - Poço'),
  (2,1,2,'Já possui acesso à agua potável - Cisterna'),
  (3,1,3,'Demonstrou ter renda superior ao estabelecido pelo Programa');

DELETE FROM social_acao_problema;

INSERT INTO social_acao_problema (social_acao_problema_id, social_acao_problema_acao_id, social_acao_problema_tipo, social_acao_problema_ordem, social_acao_problema_descricao) VALUES
	(1, 1, 0, 1, 'Sem dinheiro para consertar telhado'),
	(2, 1, 0, 2, 'Sem condições físicas de os integrantes da família de cavarem o buraco '),
	(3, 1, 0, 3, 'Beneficiário vive em abrigo provisório'),
	(4, 1, 4, 1, 'Não foi satisfatório o aprendizado sobre operação de cisterna'),
	(5, 1, 4, 2, 'Integrantes não se encontram motivados'),
	(6, 1, 4, 3, 'Não há acesso à internet para acompanhar as atividades no GP-Web'),
	(7, 1, 3, 1, 'Não foi satisfatório o aprendizado sobre operação de cisterna	'),
	(8, 1, 3, 2, 'Integrantes não se encontram motivados'),
	(9, 1, 3, 3, 'Não há acesso à internet para acompanhar as atividades no GP-Web'),
	(10, 1, 2, 1, 'Integrantes não se encontram motivados'),
	(11, 1, 2, 2, 'Não foi satisfatório o aprendizado sobre operação de cisterna'),
	(12, 1, 2, 3, 'Não há acesso à internet para acompanhar as atividades no GP-Web'),
	(13, 1, 1, 1, 'Integrantes não se encontram motivados'),
	(14, 1, 1, 2, 'Não foi satisfatório o aprendizado sobre operação de cisterna'),
	(15, 1, 1, 3, 'Não há acesso à internet para acompanhar as atividades no GP-Web');

DELETE FROM social_comunidade;

INSERT INTO social_comunidade (social_comunidade_id, social_comunidade_municipio, social_comunidade_estado, social_comunidade_nome, social_comunidade_responsavel, social_comunidade_descricao, social_comunidade_cor) VALUES 
  (1,'2507507','PB','Treze de Maio',1,NULL,'ffebeb'),
  (2,'2507507','PB','Roger',1,NULL,'faffeb'),
  (3,'2504009','PB','São José',1,NULL,'e0f1ff'),
  (4,'2611606','PE','Boa Vista',1,NULL,'e0f1ff');
  
DELETE FROM social_familia;

INSERT INTO social_familia (social_familia_id, social_familia_municipio, social_familia_comunidade, social_familia_nome, social_familia_conjuge, social_familia_latitude, social_familia_longitude, social_familia_distancia, social_familia_nascimento, social_familia_cpf, social_familia_nis, social_familia_rg, social_familia_estado_civil, social_familia_escolaridade, social_familia_filhos, social_familia_tipo_residencia, social_familia_tipo_coberta, social_familia_comprimento, social_familia_largura, social_familia_lixo, social_familia_esgoto, social_familia_eletrificacao, social_familia_sanitario, social_familia_tratamento_agua, social_familia_tratamento_agua_frequencia, social_familia_distancia_agua, social_familia_ocupacao, social_familia_principal_renda, social_familia_renda_periodo, social_familia_renda_valor, social_familia_uso_terra, social_familia_mao_familiar, social_familia_mao_contratada, social_familia_area_propriedade, social_familia_area_producao, social_familia_nr_familias_trabalhar, social_familia_irrigacao, social_familia_tipo_irrigacao, social_familia_assistencia_tecnica, social_familia_observacao, social_familia_data, social_familia_endereco1, social_familia_endereco2, social_familia_estado, social_familia_cep, social_familia_pais, social_familia_email, social_familia_dddtel, social_familia_tel, social_familia_dddtel2, social_familia_tel2, social_familia_dddcel, social_familia_cel, social_familia_cor, social_familia_ativo, social_familia_sexo, social_familia_chefe, social_familia_sessenta_cinco, social_familia_deficiente_mental, social_familia_bolsa, social_familia_necessita_bolsa, social_familia_sexo_chefe, social_familia_nome_chefe, social_familia_crianca_seis, social_familia_crianca_escola) VALUES 
  (1,'2507507',1,'Sergio Lima','Joana Lima',  -7.110566,-34.866432,500.000,'1974-10-01','523.190.383-37','6767634','2344515938','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(2,'2507507',1,'Roberto Silva','Maria Silva', -7.108688,-34.867443,500.000,'1974-10-01','853.465.597-95','3257375','01947656938','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(3,'2507507',1,'Marcos Cruz','Rita Cruz', -7.113574,-34.866711,500.000,'1974-10-01','611.277.232-94','7865432','786515938','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(4,'2507507',1,'Carlos Silva','Marta Silva', -7.110505,-34.863104,500.000,'1974-10-01','862.243.878-34','36954845','87656326','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(5,'2507507',1,'João Carvalho','Joana Carvalho', -7.106433,-34.867624,500.000,'1974-10-01','671.543.538-30','45676563','32133524','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(6,'2507507',2,'Ricardo Amazonas','Juliana Amazonas', -7.111126,-34.876313,500.000,'1974-10-01','362.186.517-97','3945245','8763126','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(7,'2507507',2,'Noel Schineider','Maria Schineider', -7.111425,-34.880770,500.000,'1974-10-01','472.510.072-23','735342','32498452','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(8,'2507507',2,'Lucas Lima','Juliana Lima', -7.114347,-34.880094,500.000,'1974-10-01','866.568.168-00','35275757','5468432','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(9,'2507507',2,'Pedro Salomão','Simone Salomão', -7.112952,-34.874990,500.000,'1974-10-01','677.846.324-44','78645321','65768951','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
  (10,'2507507',2,'Antônio Silva','Rita Silva', -7.109702,-34.874749,500.000,'1974-10-01','582.411.670-91','6767548','6579812','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(11,'2504009',3,'Francisco Wicker','Joana Wicker',  -7.226662,-35.889364,500.000,'1974-10-01','616.871.254-22','15276536','3215657','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(12,'2504009',3,'Tiago Pietro','Juliana Pietro',  -7.224801,-35.889101,500.000,'1974-10-01','332.346.421-41','5865442','655485','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(13,'2504009',3,'Mateus Lima','Marta Lima',  -7.227307,-35.887128,500.000,'1974-10-01','311.431.718-96','7865321','165498796','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(14,'2504009',3,'Pilatos Rocha','Maria Rocha',  -7.228144,-35.890655,500.000,'1974-10-01','087.556.935-80','954534','32457985','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(15,'2504009',3,'Platão Carvalho','Simone Carvalho',  -7.225840,-35.891210,500.000,'1974-10-01','686.288.944-38','324568','657489541','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PB',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(16,'2611606',4,'Socrates Silva','Marta Silva', -8.061898,-34.888889,500.000,'1974-10-01','869.127.543-09','245485645','654598','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PE',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(17,'2611606',4,'Descartes Amazonas','Joana Amazonas', -8.059695,-34.888280,500.000,'1974-10-01','271.417.859-65','89545345','65498777','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PE',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(18,'2611606',4,'Damião Raoms','Maria Amazonas', -8.066485,-34.891260,500.000,'1974-10-01','854.114.957-92','121545436','6516579857','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PE',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0),
	(19,'2611606',4,'Alberto Einstein','Rita Einstein', -8.062300,-34.892537,500.000,'1974-10-01','171.286.680-04','2453951','65461879','4','3',2,'1','1',40.000,20.000,'2',1,1,1,'1','2',300.000,'2','2','1','1','1',3,2,30.000,50.000,3,1,NULL,'1','Muito bom',NULL,'santa flora','1268','PE',NULL,NULL,'sergioreinert@hotmail.com','51','4360-9079','43','4360-9028','33','4360-9030',NULL,0,'1',1,4,4,1,1,NULL,NULL,0,0);

DELETE FROM social_familia_acao;

INSERT INTO social_familia_acao (social_familia_acao_familia, social_familia_acao_acao, social_familia_acao_concluido, social_familia_acao_observacao, social_familia_acao_data, social_familia_acao_usuario, social_familia_acao_data_conclusao, social_familia_acao_usuario_conclusao) VALUES 
  (1,1,1,NULL,'2012-02-20 13:15:52',1,'2012-02-20 13:19:13',1),
  (2,1,1,NULL,'2012-02-20 13:15:52',1,'2012-02-20 13:19:13',1),
  (3,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (4,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (5,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (6,1,1,NULL,'2012-02-20 13:19:50',1,'2012-02-20 13:19:13',1),
  (7,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (8,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (9,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (10,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (11,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (12,1,1,NULL,'2012-02-20 13:19:50',1,'2012-02-20 13:19:13',1),
  (13,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (14,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (15,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (16,1,1,NULL,'2012-02-20 13:19:50',1,'2012-02-20 13:19:13',1),
  (17,1,1,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (18,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL),
  (19,1,0,NULL,'2012-02-20 13:19:50',1,NULL,NULL);

DELETE FROM social_familia_lista;

INSERT INTO social_familia_lista (social_familia_lista_familia, social_familia_lista_lista, social_familia_lista_data, social_familia_lista_usuario) VALUES 
  (1,1,'2012-02-20 13:16:23',1),
  (1,2,'2012-02-20 13:16:25',1),
  (1,3,'2012-02-20 13:16:26',1),
  (1,4,'2012-02-20 13:16:27',1),
  (1,5,'2012-02-20 13:16:28',1),
  (1,6,'2012-02-20 13:16:30',1),
  (1,7,'2012-02-20 13:19:06',1),
  (1,8,'2012-02-20 13:19:06',1),
  (1,9,'2012-02-20 13:19:08',1),
  (1,10,'2012-02-20 13:16:29',1),
  (1,11,'2012-02-20 13:16:30',1),
  (2,1,'2012-02-20 13:16:23',1),
  (2,2,'2012-02-20 13:16:25',1),
  (2,3,'2012-02-20 13:16:26',1),
  (2,4,'2012-02-20 13:16:27',1),
  (2,5,'2012-02-20 13:16:28',1),
  (2,6,'2012-02-20 13:16:30',1),
  (2,7,'2012-02-20 13:19:06',1),
  (2,8,'2012-02-20 13:19:06',1),
  (2,9,'2012-02-20 13:19:08',1),
  (2,10,'2012-02-20 13:16:29',1),
  (2,11,'2012-02-20 13:16:30',1),
  (6,1,'2012-02-20 13:16:23',1),
  (6,2,'2012-02-20 13:16:25',1),
  (6,3,'2012-02-20 13:16:26',1),
  (6,4,'2012-02-20 13:16:27',1),
  (6,5,'2012-02-20 13:16:28',1),
  (6,6,'2012-02-20 13:16:30',1),
  (6,7,'2012-02-20 13:19:06',1),
  (6,8,'2012-02-20 13:19:06',1),
  (6,9,'2012-02-20 13:19:08',1),
  (6,10,'2012-02-20 13:16:29',1),
  (6,11,'2012-02-20 13:16:30',1),
  (12,1,'2012-02-20 13:16:23',1),
  (12,2,'2012-02-20 13:16:25',1),
  (12,3,'2012-02-20 13:16:26',1),
  (12,4,'2012-02-20 13:16:27',1),
  (12,5,'2012-02-20 13:16:28',1),
  (12,6,'2012-02-20 13:16:30',1),
  (12,7,'2012-02-20 13:19:06',1),
  (12,8,'2012-02-20 13:19:06',1),
  (12,9,'2012-02-20 13:19:08',1),
  (12,10,'2012-02-20 13:16:29',1),
  (12,11,'2012-02-20 13:16:30',1),
  (16,1,'2012-02-20 13:16:23',1),
  (16,2,'2012-02-20 13:16:25',1),
  (16,3,'2012-02-20 13:16:26',1),
  (16,4,'2012-02-20 13:16:27',1),
  (16,5,'2012-02-20 13:16:28',1),
  (16,6,'2012-02-20 13:16:30',1),
  (16,7,'2012-02-20 13:19:06',1),
  (16,8,'2012-02-20 13:19:06',1),
  (16,9,'2012-02-20 13:19:08',1),
  (16,10,'2012-02-20 13:16:29',1),
  (16,11,'2012-02-20 13:16:30',1),
  (17,1,'2012-02-20 13:16:23',1),
  (17,2,'2012-02-20 13:16:25',1),
  (17,3,'2012-02-20 13:16:26',1),
  (17,4,'2012-02-20 13:16:27',1),
  (17,5,'2012-02-20 13:16:28',1),
  (17,6,'2012-02-20 13:16:30',1),
  (17,7,'2012-02-20 13:19:06',1),
  (17,8,'2012-02-20 13:19:06',1),
  (17,9,'2012-02-20 13:19:08',1),
  (17,10,'2012-02-20 13:16:29',1),
  (17,11,'2012-02-20 13:16:30',1),
  (19,1,'2012-02-21 20:27:45',1),
  (19,2,'2012-02-21 20:27:46',1),
  (19,4,'2012-02-21 20:27:49',1),
  (10,1,'2012-02-21 20:28:28',1),
  (10,3,'2012-02-21 20:28:30',1),
  (10,4,'2012-02-21 20:28:31',1),
  (10,6,'2012-02-21 20:28:32',1),
  (10,5,'2012-02-21 20:28:33',1),
  (4,1,'2012-02-21 20:29:36',1),
  (4,3,'2012-02-21 20:29:37',1),
  (4,2,'2012-02-21 20:29:38',1),
  (4,4,'2012-02-21 20:29:39',1),
  (4,6,'2012-02-21 20:29:40',1),
  (4,5,'2012-02-21 20:29:40',1),
  (4,7,'2012-02-21 20:29:41',1),
  (4,8,'2012-02-21 20:29:42',1),
  (18,1,'2012-02-21 20:30:10',1),
  (11,1,'2012-02-21 20:31:52',1),
  (11,2,'2012-02-21 20:31:52',1),
  (11,3,'2012-02-21 20:31:53',1),
  (11,6,'2012-02-21 20:31:54',1),
  (11,7,'2012-02-21 20:31:55',1),
  (11,8,'2012-02-21 20:31:56',1),
  (5,1,'2012-02-21 20:32:12',1),
  (5,4,'2012-02-21 20:32:13',1),
  (5,6,'2012-02-21 20:32:14',1),
  (5,5,'2012-02-21 20:32:15',1),
  (5,2,'2012-02-21 20:32:16',1),
  (5,7,'2012-02-21 20:32:18',1),
  (8,1,'2012-02-21 20:32:52',1),
  (8,2,'2012-02-21 20:32:52',1),
  (8,3,'2012-02-21 20:32:53',1),
  (8,5,'2012-02-21 20:32:55',1),
  (8,4,'2012-02-21 20:32:55',1),
  (8,6,'2012-02-21 20:32:56',1),
  (8,7,'2012-02-21 20:32:57',1),
  (8,9,'2012-02-21 20:32:58',1),
  (8,10,'2012-02-21 20:32:58',1),
  (3,1,'2012-02-21 20:33:31',1),
  (3,3,'2012-02-21 20:33:39',1),
  (3,4,'2012-02-21 20:33:41',1),
  (3,5,'2012-02-21 20:33:42',1),
  (3,6,'2012-02-21 20:33:42',1),
  (3,7,'2012-02-21 20:33:46',1),
  (3,8,'2012-02-21 20:33:47',1),
  (13,1,'2012-02-21 20:34:28',1),
  (13,2,'2012-02-21 20:34:29',1),
  (13,6,'2012-02-21 20:34:30',1),
  (13,7,'2012-02-21 20:34:31',1),
  (7,1,'2012-02-21 20:34:43',1),
  (7,2,'2012-02-21 20:34:44',1),
  (7,5,'2012-02-21 20:34:45',1),
  (7,7,'2012-02-21 20:34:46',1),
  (7,6,'2012-02-21 20:34:48',1),
  (9,1,'2012-02-21 20:35:13',1),
  (9,2,'2012-02-21 20:35:15',1),
  (9,4,'2012-02-21 20:35:17',1),
  (9,5,'2012-02-21 20:35:18',1),
  (9,6,'2012-02-21 20:35:19',1),
  (9,7,'2012-02-21 20:35:20',1),
  (14,1,'2012-02-21 20:35:45',1),
  (14,2,'2012-02-21 20:35:46',1),
  (14,3,'2012-02-21 20:35:47',1),
  (14,4,'2012-02-21 20:35:48',1),
  (14,5,'2012-02-21 20:35:49',1),
  (14,6,'2012-02-21 20:35:51',1),
  (14,7,'2012-02-21 20:35:52',1),
  (14,8,'2012-02-21 20:35:53',1),
  (14,9,'2012-02-21 20:35:53',1),
  (14,10,'2012-02-21 20:35:54',1),
  (15,1,'2012-02-21 20:36:10',1),
  (15,2,'2012-02-21 20:36:10',1),
  (15,3,'2012-02-21 20:36:12',1),
  (15,4,'2012-02-21 20:36:12',1),
  (15,5,'2012-02-21 20:36:13',1);

DELETE FROM social_familia_problema;

INSERT INTO social_familia_problema (social_familia_problema_id, social_familia_problema_familia, social_familia_problema_acao, social_familia_problema_tipo, social_familia_problema_status, social_familia_problema_observacao, social_familia_problema_data_insercao, social_familia_problema_usuario_insercao, social_familia_problema_data_status, social_familia_problema_usuario_status) VALUES 
  (1,19,1,1,'2','Renda inferior a R$ 50,00','2012-02-21 20:27:13',1,'2012-02-21 20:37:51',6),
  (2,19,1,3,NULL,'Acampamento do MST','2012-02-21 20:27:28',1,NULL,NULL),
  (3,10,1,2,NULL,'Todos os integrante tem mais de 70 anos','2012-02-21 20:29:00',1,NULL,NULL),
  (4,18,1,3,'2','Encontram-se no acampamento do MST','2012-02-21 20:30:34',1,'2012-02-21 20:37:51',6),
  (5,5,1,1,NULL,'Necessita pequeno reparo','2012-02-21 20:32:32',1,NULL,NULL),
  (6,3,1,2,NULL,'Casal com mais de 65 anos','2012-02-21 20:34:07',1,NULL,NULL),
  (7,9,1,1,NULL,'Pequeno reparo','2012-02-21 20:35:28',1,NULL,NULL),
  (8,15,1,3,'3','área da residência da família em litígio','2012-02-21 20:36:42',1,'2012-02-21 20:38:06',7);

DELETE FROM social_familia_irrigacao;

INSERT INTO social_familia_irrigacao (social_familia_irrigacao_familia, social_familia_irrigacao_cultura, social_familia_irrigacao_sistema, social_familia_irrigacao_area) VALUES 
  (1,'1','1',20.000);

DELETE FROM social_familia_opcao;

INSERT INTO social_familia_opcao (social_familia_opcao_familia, social_familia_opcao_campo, social_familia_opcao_valor) VALUES 
  (1,'organizacao_social','1'),
  (1,'organizacao_social','2'),
  (1,'agua_beber','1'),
  (1,'agua_beber','3'),
  (1,'agua_banho','6'),
  (1,'agua_banho','7'),
  (1,'agua_cozinhar','3'),
  (1,'agua_cozinhar','5'),
  (1,'agua_lavar','4'),
  (1,'agua_lavar','5'),
  (1,'agua_agropecuaria','1'),
  (1,'agua_agropecuaria','3');

DELETE FROM social_familia_producao;

INSERT INTO social_familia_producao (social_familia_producao_familia, social_familia_producao_cultura, social_familia_producao_animal, social_familia_producao_finalidade, social_familia_producao_quantidade) VALUES 
  (1,'1',NULL,'1',30.000),
  (1,NULL,'3','1',22.000);

DELETE FROM social_comite;		

INSERT INTO social_comite (social_comite_id, social_comite_responsavel, social_comite_nome, social_comite_tipo, social_comite_estado, social_comite_municipio, social_comite_comunidade, social_comite_endereco1, social_comite_endereco2, social_comite_cep, social_comite_email, social_comite_dddtel, social_comite_tel, social_comite_dddtel2, social_comite_tel2, social_comite_dddcel, social_comite_cel, social_comite_cor, social_comite_observacao, social_comite_ativo) VALUES
(1, 6, 'Comite Nacional', 1, 'DF', '5300108', 5, 'Santa Flora', '1268, apto 303B', NULL, 'sergioreinert@hotmail.com', '41', '5353-3340', '32', '5353-3355', '12', '5353-3900', 'defeb9', '<p>\r\n	observação</p>\r\n', 1),
(2, 6, 'Comite Municipal - João Pessoa', 3, 'PB', '2507507', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ffffcc', NULL, 1),
(3, 7, 'Comite Municipal - Campina Grande', 3, 'PB', '2504009', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'f9d7ff', NULL, 1),
(4, 7, 'Comite Comunitário- Roger', 4, 'PB', '2507507', 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'fffacc', NULL, 1),
(5, 6, 'Comite Comunitário - Treze de Maio', 4, 'PB', '2507507', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ebf6ff', NULL, 1);

DELETE FROM social_comite_acao;

INSERT INTO social_comite_acao (social_comite_acao_comite, social_comite_acao_acao, social_comite_acao_concluido, social_comite_acao_observacao, social_comite_acao_data, social_comite_acao_usuario, social_comite_acao_data_conclusao, social_comite_acao_usuario_conclusao) VALUES
(2, 1, 0, NULL, '2012-02-22 11:09:34', 1, NULL, NULL),
(3, 1, 1, NULL, '2012-02-22 12:36:57', 1, '2012-02-22 12:37:05', 1),
(1, 1, 0, NULL, '2012-02-22 12:37:48', 1, NULL, NULL),
(5, 1, 1, NULL, '2012-02-22 12:41:23', 1, '2012-02-22 12:41:30', 1),
(4, 1, 0, NULL, '2012-02-22 12:43:06', 1, NULL, NULL);

DELETE FROM social_comite_lista;

INSERT INTO social_comite_lista (social_comite_lista_comite, social_comite_lista_lista, social_comite_lista_data, social_comite_lista_usuario) VALUES
(2, 21, '2012-02-22 11:12:46', 1),
(2, 22, '2012-02-22 11:45:50', 1),
(3, 21, '2012-02-22 12:37:01', 1),
(3, 22, '2012-02-22 12:37:02', 1),
(3, 23, '2012-02-22 12:37:03', 1),
(1, 21, '2012-02-22 12:37:52', 1),
(1, 23, '2012-02-22 12:37:53', 1),
(5, 22, '2012-02-22 12:41:27', 1),
(5, 23, '2012-02-22 12:41:27', 1),
(5, 21, '2012-02-22 12:41:28', 1),
(4, 21, '2012-02-22 12:43:09', 1);

DELETE FROM social_comite_problema;

INSERT INTO social_comite_problema (social_comite_problema_id, social_comite_problema_comite, social_comite_problema_acao, social_comite_problema_tipo, social_comite_problema_status, social_comite_problema_observacao, social_comite_problema_data_insercao, social_comite_problema_usuario_insercao, social_comite_problema_data_status, social_comite_problema_usuario_status) VALUES
(1, 2, 1, 14, NULL, 'Necessário mais um dia', '2012-02-22 11:16:21', 1, NULL, NULL),
(2, 2, 1, 15, NULL, 'Conseguir acesso por satélite', '2012-02-22 11:20:45', 1, NULL, NULL),
(3, 3, 1, 15, NULL, 'Necessário Wi-Fi', '2012-02-22 12:37:18', 1, NULL, NULL),
(4, 5, 1, 13, NULL, 'Necessária ação de comando do prefeito', '2012-02-22 12:41:54', 1, NULL, NULL),
(5, 4, 1, 15, NULL, 'Necessário adquirir computadores', '2012-02-22 12:43:28', 1, NULL, NULL);

DELETE FROM social_comite_membros;

INSERT INTO social_comite_membros (social_comite_id, contato_id) VALUES
(1, 4),
(1, 5),
(2, 5),
(2, 6),
(3, 2),
(3, 3),
(5, 4),
(5, 5);