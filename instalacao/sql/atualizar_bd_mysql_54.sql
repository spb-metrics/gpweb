UPDATE versao SET versao_bd=54; 
UPDATE versao SET versao_codigo='7.7.5'; 

DROP TABLE IF EXISTS pratica_indicador_log;

CREATE TABLE pratica_indicador_log (
  pratica_indicador_log_id INTEGER(100) UNSIGNED NOT NULL AUTO_INCREMENT,
  pratica_indicador_log_horas FLOAT DEFAULT NULL,
  pratica_indicador_log_pratica_indicador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_log_descricao TEXT,
  pratica_indicador_log_custo FLOAT(100,3) DEFAULT 0,
  pratica_indicador_log_codigo_custo VARCHAR(11) DEFAULT NULL,
  pratica_indicador_log_problema TINYINT(1) DEFAULT '0',
  pratica_indicador_log_criador INTEGER(100) UNSIGNED DEFAULT NULL,
  pratica_indicador_log_referencia INTEGER(11) DEFAULT NULL,
  pratica_indicador_log_nome VARCHAR(200) DEFAULT NULL,
  pratica_indicador_log_data DATETIME DEFAULT NULL,
  pratica_indicador_log_url_relacionada VARCHAR(250) DEFAULT NULL,
  pratica_indicador_log_acesso INTEGER(100) DEFAULT '0',
  PRIMARY KEY (pratica_indicador_log_id)
)ENGINE=InnoDB;


ALTER TABLE tarefa_log CHANGE COLUMN tarefa_log_codigo_custo tarefa_log_nd VARCHAR(11) NOT NULL DEFAULT '';
ALTER TABLE plano_gestao_estrategias_log CHANGE COLUMN pg_estrategia_log_codigo_custo pg_estrategia_log_nd VARCHAR(11) NOT NULL DEFAULT '';
ALTER TABLE plano_gestao_objetivos_estrategicos_log CHANGE COLUMN pg_objetivo_estrategico_log_codigo_custo pg_objetivo_estrategico_log_nd VARCHAR(11) NOT NULL DEFAULT '';
ALTER TABLE pratica_log CHANGE COLUMN pratica_log_codigo_custo pratica_log_nd VARCHAR(11) NOT NULL DEFAULT '';
ALTER TABLE pratica_indicador_log CHANGE COLUMN pratica_indicador_log_codigo_custo pratica_indicador_log_nd VARCHAR(11) NOT NULL DEFAULT '';


ALTER TABLE tarefa_log ADD COLUMN tarefa_log_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_log ADD COLUMN tarefa_log_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_log ADD COLUMN tarefa_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

ALTER TABLE plano_gestao_estrategias_log ADD COLUMN pg_estrategia_log_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE plano_gestao_estrategias_log ADD COLUMN pg_estrategia_log_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE plano_gestao_estrategias_log ADD COLUMN pg_estrategia_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

ALTER TABLE plano_gestao_objetivos_estrategicos_log ADD COLUMN pg_objetivo_estrategico_log_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE plano_gestao_objetivos_estrategicos_log ADD COLUMN pg_objetivo_estrategico_log_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE plano_gestao_objetivos_estrategicos_log ADD COLUMN pg_objetivo_estrategico_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

ALTER TABLE pratica_indicador_log ADD COLUMN pratica_indicador_log_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE pratica_indicador_log ADD COLUMN pratica_indicador_log_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE pratica_indicador_log ADD COLUMN pratica_indicador_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

ALTER TABLE pratica_log ADD COLUMN pratica_log_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE pratica_log ADD COLUMN pratica_log_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE pratica_log ADD COLUMN pratica_log_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;


ALTER TABLE recursos ADD COLUMN recurso_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE recursos ADD COLUMN recurso_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;


ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_custos ADD COLUMN tarefa_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_gastos ADD COLUMN tarefa_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;


ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_categoria_economica1 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_grupo_despesa1 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_categoria_economica2 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_grupo_despesa2 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_gastos ADD COLUMN h_gastos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL;

ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_categoria_economica1 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_grupo_despesa1 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_modalidade_aplicacao1 VARCHAR(2) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_categoria_economica2 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_grupo_despesa2 VARCHAR(1) DEFAULT NULL;
ALTER TABLE tarefa_h_custos ADD COLUMN h_custos_modalidade_aplicacao2 VARCHAR(2) DEFAULT NULL;

ALTER TABLE projetos ADD COLUMN projeto_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE projetos ADD COLUMN projeto_ano VARCHAR(4) DEFAULT NULL;


ALTER TABLE tarefas ADD COLUMN tarefa_emprego_obra INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE tarefas ADD COLUMN tarefa_emprego_direto INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE tarefas ADD COLUMN tarefa_emprego_indireto INTEGER(100) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE tarefas ADD COLUMN tarefa_populacao_atendida VARCHAR(100) DEFAULT NULL;
ALTER TABLE tarefas ADD COLUMN tarefa_forma_implantacao VARCHAR(100) DEFAULT NULL;

ALTER TABLE baseline_projetos ADD COLUMN projeto_setor VARCHAR(2) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_segmento VARCHAR(4) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_intervencao VARCHAR(6) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_tipo_intervencao VARCHAR(9) DEFAULT NULL;
ALTER TABLE baseline_projetos ADD COLUMN projeto_ano VARCHAR(4) DEFAULT NULL;

ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_custos ADD COLUMN tarefa_custos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_categoria_economica VARCHAR(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_grupo_despesa VARCHAR(1) DEFAULT NULL;
ALTER TABLE baseline_tarefa_gastos ADD COLUMN tarefa_gastos_modalidade_aplicacao VARCHAR(2) DEFAULT NULL;

update baseline_tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '33390.', '');
update baseline_tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '34490.', '');
update baseline_tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '33391.', '');
update baseline_tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '33350.', '');

update baseline_tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '33390.', '');
update baseline_tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '34490.', '');
update baseline_tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '33391.', '');
update baseline_tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '33350.', '');




ALTER TABLE sisvalores DROP COLUMN sisvalor_chave_id;

ALTER TABLE sisvalores ADD COLUMN sisvalor_chave_id_pai VARCHAR(255) DEFAULT NULL;

ALTER TABLE sisvalores MODIFY sisvalor_valor_id VARCHAR(255) DEFAULT '0';

ALTER TABLE cias ADD COLUMN cia_qnt_nr INTEGER(20) UNSIGNED DEFAULT '0';  

ALTER TABLE cias ADD COLUMN cia_prefixo VARCHAR(30) DEFAULT NULL;

ALTER TABLE cias ADD COLUMN cia_sufixo VARCHAR(30) DEFAULT NULL; 

DELETE FROM config WHERE config_nome='titulo_pagina';

update sisvalores set sisvalor_valor=REPLACE(sisvalor_valor, '33390.', '');
update sisvalores set sisvalor_valor_id=REPLACE(sisvalor_valor_id, '33390.', '');
update sisvalores set sisvalor_valor=REPLACE(sisvalor_valor, '34490.', '');
update sisvalores set sisvalor_valor_id=REPLACE(sisvalor_valor_id, '34490.', '');
update sisvalores set sisvalor_valor=REPLACE(sisvalor_valor, '33391.', '');
update sisvalores set sisvalor_valor_id=REPLACE(sisvalor_valor_id, '33391.', '');
update sisvalores set sisvalor_valor=REPLACE(sisvalor_valor, '33350.', '');
update sisvalores set sisvalor_valor_id=REPLACE(sisvalor_valor_id, '33350.', '');



update tarefa_log set tarefa_log_nd=REPLACE(tarefa_log_nd, '33390.', '');
update tarefa_log set tarefa_log_nd=REPLACE(tarefa_log_nd, '34490.', '');
update tarefa_log set tarefa_log_nd=REPLACE(tarefa_log_nd, '33391.', '');
update tarefa_log set tarefa_log_nd=REPLACE(tarefa_log_nd, '33350.', '');


update tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '33390.', '');
update tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '34490.', '');
update tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '33391.', '');
update tarefa_gastos set tarefa_gastos_nd=REPLACE(tarefa_gastos_nd, '33350.', '');

update tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '33390.', '');
update tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '34490.', '');
update tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '33391.', '');
update tarefa_custos set tarefa_custos_nd=REPLACE(tarefa_custos_nd, '33350.', '');


update recursos set recurso_nd=REPLACE(recurso_nd, '33390.', '');
update recursos set recurso_nd=REPLACE(recurso_nd, '34490.', '');
update recursos set recurso_nd=REPLACE(recurso_nd, '33391.', '');
update recursos set recurso_nd=REPLACE(recurso_nd, '33350.', '');

update tarefa_h_custos set h_custos_nd1=REPLACE(h_custos_nd1, '33390.', '');
update tarefa_h_custos set h_custos_nd1=REPLACE(h_custos_nd1, '34490.', '');
update tarefa_h_custos set h_custos_nd1=REPLACE(h_custos_nd1, '33391.', '');
update tarefa_h_custos set h_custos_nd1=REPLACE(h_custos_nd1, '33350.', '');
update tarefa_h_custos set h_custos_nd2=REPLACE(h_custos_nd2, '33390.', '');
update tarefa_h_custos set h_custos_nd2=REPLACE(h_custos_nd2, '34490.', '');
update tarefa_h_custos set h_custos_nd2=REPLACE(h_custos_nd2, '33391.', '');
update tarefa_h_custos set h_custos_nd2=REPLACE(h_custos_nd2, '33350.', '');

update tarefa_h_gastos set h_gastos_nd1=REPLACE(h_gastos_nd1, '33390.', '');
update tarefa_h_gastos set h_gastos_nd1=REPLACE(h_gastos_nd1, '34490.', '');
update tarefa_h_gastos set h_gastos_nd1=REPLACE(h_gastos_nd1, '33391.', '');
update tarefa_h_gastos set h_gastos_nd1=REPLACE(h_gastos_nd1, '33350.', '');
update tarefa_h_gastos set h_gastos_nd2=REPLACE(h_gastos_nd2, '33390.', '');
update tarefa_h_gastos set h_gastos_nd2=REPLACE(h_gastos_nd2, '34490.', '');
update tarefa_h_gastos set h_gastos_nd2=REPLACE(h_gastos_nd2, '33391.', '');
update tarefa_h_gastos set h_gastos_nd2=REPLACE(h_gastos_nd2, '33350.', '');


DELETE FROM sisvalores WHERE sisvalor_titulo='ND';

INSERT INTO sisvalores (sisvalor_titulo, sisvalor_valor, sisvalor_valor_id, sisvalor_chave_id_pai) VALUES 
('Setor','01 - Infraestrutura Hídrica','01',NULL),
		('Segmento','01 - Reservação de água','0101','01'),
			('Intervencao','01 - Construção','010101','0101'),
				('TipoIntervencao','001 - Barragem','010101001','010101'),
				('TipoIntervencao','002 - Açude','010101002','010101'),
				('TipoIntervencao','003 - Cisterna','010101003','010101'),
				('TipoIntervencao','004 - Poço','010101004','010101'),
			('Intervencao','02 - Recuperação','010102','0101'),
				('TipoIntervencao','001 - Barragem','010102001','010102'),
				('TipoIntervencao','002 - Açude','010102002','010102'),
				('TipoIntervencao','003 - Cisterna','010102003','010102'),
				('TipoIntervencao','004 - Poço','010102004','010102'),
			('Intervencao','03 - Ampliação','010103','0101'),
				('TipoIntervencao','001 - Barragem','010103001','010103'),
				('TipoIntervencao','002 - Açude','010103002','010103'),
				('TipoIntervencao','003 - Cisterna','010103003','010103'),
			('Intervencao','04 - Modernização','010104','0101'),
				('TipoIntervencao','001 - Barragem','010104001','010104'),
				('TipoIntervencao','002 - Açude','010104002','010104'),
				('TipoIntervencao','003 - Cisterna','010104003','010104'),	
			('Intervencao','06 - Instalação','010106','0101'),
				('TipoIntervencao','004 - Poço','010106004','010106'),
			('Intervencao','08 - Operação','010108','0101'),
				('TipoIntervencao','001 - Barragem','010108001','010108'),
				('TipoIntervencao','002 - Açude','010108002','010108'),
				('TipoIntervencao','003 - Cisterna','010108003','010108'),
		('Segmento','02 - Adução de água','0102','01'),
			('Intervencao','01 - Construção','010201','0102'),
				('TipoIntervencao','001 - Adutora','010201001','010201'),
			('Intervencao','02 - Recuperação','010202','0102'),
				('TipoIntervencao','001 - Adutora','010202001','010202'),
			('Intervencao','03 - Ampliação','010203','0102'),
				('TipoIntervencao','001 - Adutora','010203001','010203'),
		('Segmento','03 - Oferta de água de tratada','0103','01'),
			('Intervencao','01 - Construção','010301','0103'),
				('TipoIntervencao','001 - Sistema de abastecimento de água','010301001','010301'),
			('Intervencao','02 - Recuperação','010302','0103'),
				('TipoIntervencao','001 - Sistema de abastecimento de água','010302001','010302'),
			('Intervencao','03 - Ampliação','010303','0103'),
				('TipoIntervencao','001 - Sistema de abastecimento de água','010303001','010303'),
	('Setor','02 - Irrigação','02',NULL),
		('Segmento','01 - Perímetro de Irrigação','0201','02'),
			('Intervencao','01 - Construção','020101','0201'),	
				('TipoIntervencao','001 - Implantação de perímetro','020101001','020101'),
			('Intervencao','04 - Modernização','020104','0201'),
				('TipoIntervencao','002 - Transferência de gestão','020104002','020104'),
	('Setor','03 - Revitalização','03',NULL),
		('Segmento','01 - Saneamento básico','0301','03'),
			('Intervencao','01 - Construção','030101','0301'),
				('TipoIntervencao','001 - Esgotamento Sanitário','030101001','030101'),
				('TipoIntervencao','002 - Resíduos Sólidos','030101002','030101'),
				('TipoIntervencao','003 - Sistema de abastecimento de água','030101003','030101'),
			('Intervencao','02 - Recuperação','030102','0301'),
				('TipoIntervencao','001 - Esgotamento Sanitário','030102001','030102'),
				('TipoIntervencao','002 - Resíduos Sólidos','030102002','030102'),
				('TipoIntervencao','003 - Sistema de abastecimento de água','030102003','030102'),
			('Intervencao','03 - Ampliação','030103','0301'),	
				('TipoIntervencao','001 - Esgotamento Sanitário','030103001','030103'),
				('TipoIntervencao','002 - Resíduos Sólidos','030103002','030103'),
				('TipoIntervencao','003 - Sistema de abastecimento de água','030103003','030103'),
			('Intervencao','08 - Operação','030108','0301'),			
				('TipoIntervencao','001 - Esgotamento Sanitário','030108001','030108'),				
				('TipoIntervencao','002 - Resíduos Sólidos','030108002','030108'),
				('TipoIntervencao','003 - Sistema de abastecimento de água','030108003','030108'),
		('Segmento','02 - Processos Erosivos','0302','03'),
			('Intervencao','05 - Implantação','030205','0302'),
				('TipoIntervencao','001 - Desassoreamento','030205001','030205'),
				('TipoIntervencao','002 - Contenção de barrancas','030205002','030205'),
		('Segmento','03 - Reflorestamento','0303','03'),
			('Intervencao','05 - Implantação','030305','0303'),
				('TipoIntervencao','001 - Cerca para proteção de nascentes','030305001','030305'),
		('Segmento','04 - Monitoramento da qualidade da água','0304','03'),
			('Intervencao','05 - Implantação','030405','0304'),
				('TipoIntervencao','001 - Intrumentação de sistema de monitoramento da água','030405001','030405'),
	('Setor','04 - Estruturação de Cadeia Produtiva','04',NULL),
		('Segmento','01 - Apicultura','0401','04'),
			('Intervencao','07 - Estruturação','040107','0401'),	
				('TipoIntervencao','001 - Capacitação','040107001','040107'),
				('TipoIntervencao','002 - Equipamentos','040107002','040107'),
				('TipoIntervencao','003 - Infraestrutura','040107003','040107'),
				('TipoIntervencao','001 - Capacitação','040107001','040107'),
		('Segmento','02 - Artesanato','0402','04'),
			('Intervencao','07 - Estruturação','040207','0402'),
				('TipoIntervencao','002 - Equipamentos','040207002','040207'),
				('TipoIntervencao','003 - Infraestrutura','040207003','040207'),
		('Segmento','03 - Bovinocultura','0403','04'),
			('Intervencao','07 - Estruturação','040307','0403'),
				('TipoIntervencao','001 - Capacitação','040307001','040307'),
				('TipoIntervencao','002 - Equipamentos','040307002','040307'),
				('TipoIntervencao','003 - Infraestrutura','040307003','040307'),
		('Segmento','04 - Caprinocultura','0404','04'),
			('Intervencao','07 - Estruturação','040407','0404'),
				('TipoIntervencao','001 - Capacitação','040407001','040407'),
				('TipoIntervencao','002 - Equipamentos','040407002','040407'),
				('TipoIntervencao','003 - Infraestrutura','040407003','040407'),
		('Segmento','05 - Carcinocultura','0405','04'),
			('Intervencao','07 - Estruturação','040507','0405'),
				('TipoIntervencao','001 - Capacitação','040507001','040507'),
				('TipoIntervencao','002 - Equipamentos','040507002','040507'),
				('TipoIntervencao','003 - Infraestrutura','040507003','040507'),
		('Segmento','06 - Floricultura Tropical','0406','04'),
			('Intervencao','07 - Estruturação','040607','0406'),
				('TipoIntervencao','001 - Capacitação','040607001','040607'),
				('TipoIntervencao','002 - Equipamentos','040607002','040607'),
				('TipoIntervencao','003 - Infraestrutura','040607003','040607'),
		('Segmento','07 - Fruticultura','0407','04'),
			('Intervencao','07 - Estruturação','040707','0407'),
				('TipoIntervencao','001 - Capacitação','040707001','040707'),
				('TipoIntervencao','002 - Equipamentos','040707002','040707'),
				('TipoIntervencao','003 - Infraestrutura','040707003','040707'),
		('Segmento','08 - Inclusão Digital','0408','04'),
			('Intervencao','07 - Estruturação','040807','0408'),
				('TipoIntervencao','001 - Capacitação','040807001','040807'),
				('TipoIntervencao','002 - Equipamentos','040807002','040807'),
				('TipoIntervencao','003 - Infraestrutura','040807003','040807'),
		('Segmento','09 - Mandiocultura','0409','04'),
			('Intervencao','07 - Estruturação','040907','0409'),
				('TipoIntervencao','001 - Capacitação','040907001','040907'),
				('TipoIntervencao','002 - Equipamentos','040907002','040907'),
				('TipoIntervencao','003 - Infraestrutura','040907003','040907'),
		('Segmento','10 - Minhocultura','0410','04'),
			('Intervencao','07 - Estruturação','041007','0410'),	
				('TipoIntervencao','001 - Capacitação','041007001','041007'),
				('TipoIntervencao','002 - Equipamentos','041007002','041007'),
				('TipoIntervencao','003 - Infraestrutura','041007003','041007'),
		('Segmento','11 - Olericultura','0411','04'),
			('Intervencao','07 - Estruturação','041107','0411'),		
				('TipoIntervencao','001 - Capacitação','041107001','041107'),
				('TipoIntervencao','002 - Equipamentos','041107002','041107'),
				('TipoIntervencao','003 - Infraestrutura','041107003','041107'),
		('Segmento','12 - Ovinocultura','0412','04'),
			('Intervencao','07 - Estruturação','041207','0412'),			
				('TipoIntervencao','001 - Capacitação','041207001','041207'),
				('TipoIntervencao','002 - Equipamentos','041207002','041207'),
				('TipoIntervencao','003 - Infraestrutura','041207003','041207'),
		('Segmento','13 - Piscicultura','0413','04'),
			('Intervencao','07 - Estruturação','041307','0413'),	
				('TipoIntervencao','001 - Capacitação','041307001','041307'),
				('TipoIntervencao','002 - Equipamentos','041307002','041307'),
				('TipoIntervencao','003 - Infraestrutura','041307003','041307'),	
	('CategoriaEconomica','3 - Despesas Correntes','3',NULL),
	('CategoriaEconomica','4 - Despesas de Capital','4',NULL),
	('GrupoND','1 - Pessoal e Encargos Sociais','1',NULL),
	('GrupoND','2 - Juros e Encargos da Dívida','2',NULL),
	('GrupoND','3 - Outras Despesas Correntes','3',NULL),
	('GrupoND','4 - Investimentos','4',NULL),
	('GrupoND','5 - Inversões financeiras','5',NULL),
	('GrupoND','6 - Amortização da Dívida','6',NULL),
	('GrupoND','9 - Reserva de Contingência','9',NULL),
	('ModalidadeAplicacao','20 - Transferências à União','20',NULL),
	('ModalidadeAplicacao','30 - Transferências a Estados e ao Distrito Federal','30',NULL),
	('ModalidadeAplicacao','40 - Transferências a Municípios','40',NULL),
	('ModalidadeAplicacao','50 - Transferências a Instituições Privadas sem Fins Lucrativos','50',NULL),
	('ModalidadeAplicacao','60 - Transferências a Instituições Privadas com Fins Lucrativos','60',NULL),
	('ModalidadeAplicacao','70 - Transferências a Instituições Multigovernamentais','70',NULL),
	('ModalidadeAplicacao','71 - Transferências a Consórcios Públicos','71',NULL),
	('ModalidadeAplicacao','80 - Transferências ao Exterior','80',NULL),
	('ModalidadeAplicacao','90 - Aplicações Diretas','90',NULL),
	('ModalidadeAplicacao','91 - Aplicação Direta Decorrente de Operação entre Órgãos, Fundos e Entidades Integrantes dos Orçamentos Fiscal e da Seguridade Social','91',NULL),
	('ModalidadeAplicacao','99 - A Definir','99',NULL),
	('ResultadoPrimario','0 - Financeira','0',NULL),
	('ResultadoPrimario','1 - Primária obrigatória, ou seja, aquelas que constituem obrigações constitucionais ou legais da União e constem da Seção I do Anexo IV da LDO-200','1',NULL),
	('ResultadoPrimario','2 - Primária discricionária, assim consideradas aquelas não incluídas no anexo específico citado no item anterior','2',NULL),
	('ResultadoPrimario','3 - Despesas relativas ao Projeto-Piloto de Investimentos Públicos – PPI','3',NULL),
	('ResultadoPrimario','4 - Despesas constantes do orçamento de investimento das empresas estatais que não impactam o resultado primário','4',NULL),
	('OrigemRecurso','1 - Emenda Parlamentar','1',NULL),
	('OrigemRecurso','2 - Executivo','2',NULL),
	('OrigemRecurso','3 - Executivo/Emenda Parlamentar','3',NULL),
	('CreditoAdicional','1 - Suplementar','1',NULL),
	('CreditoAdicional','2 - Especial','2',NULL),
	('CreditoAdicional','3 - Extraordinário','3',NULL),
	('MovimentacaoOrcamentaria','1 - Destaque recebido','1',NULL),
	('MovimentacaoOrcamentaria','2 - Destaque concedido','2',NULL),
	('IdentificadorUso','0 - Recursos não destinados à contrapartida','0',NULL),
	('IdentificadorUso','1 - Contrapartida – Banco Internacional para a Reconstrução e o Desenvolvimento - BIRD','1',NULL),
	('IdentificadorUso','2 - Contrapartida – Banco Interamericano de Desenvolvimento - BID','2',NULL),
	('IdentificadorUso','3 - Contrapartida de empréstimos com enfoque setorial amplo','3',NULL),
	('IdentificadorUso','4 - Contrapartida de outros empréstimos','4',NULL),
	('IdentificadorUso','5 - Contrapartida de doações','5',NULL),
	('EsferaOrcamentaria','10 - Orçamento fiscal','10',NULL),
	('EsferaOrcamentaria','20 - Orçamento da Seguridade Social','20',NULL),
	('EsferaOrcamentaria','30 - Orçamento de investimento ','30',NULL),
	('ND','01.00 - APOSENTADORIAS E REFORMAS','01.00',NULL),
	('ND','03.00 - PENSÕES','03.00',NULL),
	('ND','04.00 - CONTRATAÇÃO POR TEMPO DETERMINADO','04.00',NULL),
	('ND','05.00 - OUTROS BENEFÍCIOS PREVIDENCIÁRIOS','05.00',NULL),
	('ND','06.00 - BENEFÍCIO MENSAL AO DEFICIENTE E AO IDOSO','06.00',NULL),
	('ND','07.00 - CONTRIBUIÇÃO A ENTIDADES FECHADAS DE PREVIDÊNCIA','07.00',NULL),
	('ND','08.00 - OUTROS BENEFÍCIOS ASSISTENCIAIS','08.00',NULL),
	('ND','09.00 - SALÁRIO-FAMÍLIA','09.00',NULL),
	('ND','10.00 - OUTROS BENEFÍCIOS DE NATUREZA SOCIAL','10.00',NULL),
	('ND','11.00 - VENCIMENTOS E VANTAGENS FIXAS – PESSOAL CIVIL','11.00',NULL),
	('ND','12.00 - VENCIMENTOS E VANTAGENS FIXAS – PESSOAL MILITAR','12.00',NULL),
	('ND','13.00 - OBRIGAÇÕES PATRONAIS','13.00',NULL),
	('ND','14.00 - DIÁRIAS – PESSOAL CIVIL','14.00',NULL),
	('ND','14.14 - DIARIAS NO PAIS','14.14','14.00'),
	('ND','14.16 - DIARIAS NO EXTERIOR','14.16','14.00'),
	('ND','14.17 - DIARIAS NAO COMPENSAVEIS','14.17','14.00'),
	('ND','15.00 - DIÁRIAS – PESSOAL MILITAR','15.00',NULL),
	('ND','16.00 - OUTRAS DESPESAS VARIÁVEIS – PESSOAL CIVIL','16.00',NULL),
	('ND','17.00 - OUTRAS DESPESAS VARIÁVEIS – PESSOAL MILITAR','17.00',NULL),
	('ND','18.00 - AUXÍLIO-FINANCEIRO A ESTUDANTES','18.00',NULL),
	('ND','18.01 - BOLSAS DE ESTUDO NO PAIS','18.01','18.00'),
	('ND','18.02 - BOLSAS DE ESTUDO NO EXTERIOR','18.02','18.00'),
	('ND','19.00 - AUXÍLIO-FARDAMENTO','19.00',NULL),
	('ND','20.00 - AUXÍLIO-FINANCEIRO A PESQUISADORES','20.00',NULL),
	('ND','20.01 - AUXILIO A PESQUISADORES','20.01','20.00'),
	('ND','21.00 - JUROS SOBRE A DÍVIDA POR CONTRATO','21.00',NULL),
	('ND','22.00 - OUTROS ENCARGOS SOBRE A DÍVIDA POR CONTRATO','22.00',NULL),
	('ND','23.00 - JUROS, DESÁGIOS E DESCONTOS DA DÍVIDA MOBILIÁRIA','23.00',NULL),
	('ND','24.00 - OUTROS ENCARGOS SOBRE A DÍVIDA MOBILIÁRIA','24.00',NULL),
	('ND','25.00 - ENCARGOS SOBRE OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA','25.00',NULL),
	('ND','26.00 - OBRIGAÇÕES DECORRENTES DE POLÍTICA MONETÁRIA','26.00',NULL),
	('ND','27.00 - ENCARGOS PELA HONRA DE AVAIS, GARANTIAS, SEGUROS E SIMILARES','27.00',NULL),
	('ND','28.00 - REMUNERAÇÃO DE COTAS DE FUNDOS AUTÁRQUICOS','28.00',NULL),
	('ND','30.00 - MATERIAL DE CONSUMO','30.00',NULL),
	('ND','30.01 - COMBUSTIVEIS E LUBRIFICANTES AUTOMOTIVOS','30.01','30.00'),
	('ND','30.02 - COMBUSTIVEIS E LUBRIFICANTES DE AVIACAO','30.02','30.00'),
	('ND','30.03 - COMBUSTIVEIS E LUBRIF. P/ OUTRAS FINALIDADES','30.03','30.00'),
	('ND','30.04 - GAS E OUTROS MATERIAIS ENGARRAFADOS','30.04','30.00'),
	('ND','30.05 - EXPLOSIVOS E MUNICOES','30.05','30.00'),
	('ND','30.06 - ALIMENTOS PARA ANIMAIS','30.06','30.00'),
	('ND','30.07 - GENEROS DE ALIMENTACAO','30.07','30.00'),
	('ND','30.08 - ANIMAIS PARA PESQUISA E ABATE','30.08','30.00'),
	('ND','30.09 - MATERIAL FARMACOLOGICO','30.09','30.00'),
	('ND','30.10 - MATERIAL ODONTOLOGICO','30.10','30.00'),
	('ND','30.11 - MATERIAL QUIMICO','30.11','30.00'),
	('ND','30.12 - MATERIAL DE COUDELARIA OU DE USO ZOOTECNICO','30.12','30.00'),
	('ND','30.13 - MATERIAL DE CACA E PESCA','30.13','30.00'),
	('ND','30.14 - MATERIAL EDUCATIVO E ESPORTIVO','30.14','30.00'),
	('ND','30.15 - MATERIAL P/ FESTIVIDADES E HOMENAGENS','30.15','30.00'),
	('ND','30.16 - MATERIAL DE EXPEDIENTE','30.16','30.00'),
	('ND','30.17 - MATERIAL DE PROCESSAMENTO DE DADOS','30.17','30.00'),
	('ND','30.18 - MATERIAIS E MEDICAMENTOS P/ USO VETERINARIO','30.18','30.00'),
	('ND','30.19 - MATERIAL DE ACONDICIONAMENTO E EMBALAGEM','30.19','30.00'),
	('ND','30.20 - MATERIAL DE CAMA, MESA E BANHO','30.20','30.00'),
	('ND','30.21 - MATERIAL DE COPA E COZINHA','30.21','30.00'),
	('ND','30.22 - MATERIAL DE LIMPEZA E PROD. DE HIGIENIZACAO','30.22','30.00'),
	('ND','30.23 - UNIFORMES, TECIDOS E AVIAMENTOS','30.23','30.00'),
	('ND','30.24 - MATERIAL P/ MANUT.DE BENS IMOVEIS/INSTALACOES','30.24','30.00'),
	('ND','30.25 - MATERIAL P/ MANUTENCAO DE BENS MOVEIS','30.25','30.00'),
	('ND','30.26 - MATERIAL ELETRICO E ELETRONICO','30.26','30.00'),
	('ND','30.27 - MATERIAL DE MANOBRA E PATRULHAMENTO','30.27','30.00'),
	('ND','30.28 - MATERIAL DE PROTECAO E SEGURANCA','30.28','30.00'),
	('ND','30.29 - MATERIAL P/ AUDIO, VIDEO E FOTO','30.29','30.00'),
	('ND','30.30 - MATERIAL PARA COMUNICACOES','30.30','30.00'),
	('ND','30.31 - SEMENTES, MUDAS DE PLANTAS E INSUMOS','30.31','30.00'),
	('ND','30.32 - SUPRIMENTO DE AVIACAO','30.32','30.00'),
	('ND','30.33 - MATERIAL P/ PRODUCAO INDUSTRIAL','30.33','30.00'),
	('ND','30.34 - SOBRESSAL. MAQ.E MOTORES NAVIOS E EMBARCACOES','30.34','30.00'),
	('ND','30.35 - MATERIAL LABORATORIAL','30.35','30.00'),
	('ND','30.36 - MATERIAL HOSPITALAR','30.36','30.00'),
	('ND','30.37 - SOBRESSALENTES DE ARMAMENTO','30.37','30.00'),
	('ND','30.38 - SUPRIMENTO DE PROTECAO AO VOO','30.38','30.00'),
	('ND','30.39 - MATERIAL P/ MANUTENCAO DE VEICULOS','30.39','30.00'),
	('ND','30.40 - MATERIAL BIOLOGICO','30.40','30.00'),
	('ND','30.41 - MATERIAL P/ UTILIZACAO EM GRAFICA','30.41','30.00'),
	('ND','30.42 - FERRAMENTAS','30.42','30.00'),
	('ND','30.43 - MATERIAL P/ REABILITACAO PROFISSIONAL','30.43','30.00'),
	('ND','30.44 - MATERIAL DE SINALIZACAO VISUAL E OUTROS','30.44','30.00'),
	('ND','30.45 - MATERIAL TECNICO P/ SELECAO E TREINAMENTO','30.45','30.00'),
	('ND','30.46 - MATERIAL BIBLIOGRAFICO','30.46','30.00'),
	('ND','30.47 - AQUISICAO DE SOFTWARES DE BASE','30.47','30.00'),
	('ND','30.48 - BENS MOVEIS NAO ATIVAVEIS','30.48','30.00'),
	('ND','30.49 - BILHETES DE PASSAGEM','30.49','30.00'),
	('ND','30.50 - BANDEIRAS, FLAMULAS E INSIGNIAS','30.50','30.00'),
	('ND','30.96 - MATERIAL DE CONSUMO - PAGTO ANTECIPADO','30.96','30.00'),
	('ND','31.00 - PREMIAÇÕES CULTURAIS, ARTÍSTICAS, CIENTÍFICAS, DESPORTIVAS E OUTRAS','31.00',NULL),
	('ND','32.00 - MATERIAL DE DISTRIBUIÇÃO GRATUITA','32.00',NULL),
	('ND','33.00 - PASSAGENS E DESPESAS COM LOCOMOÇÃO','33.00',NULL),
	('ND','33.01 - PASSAGENS PARA O PAÍS','33.01','33.00'),
	('ND','33.02 - PASSAGENS PARA O EXTERIOR','33.02','33.00'),
	('ND','33.03 - LOCACAO DE MEIOS DE TRANSPORTE','33.03','33.00'),
	('ND','33.04 - MUDANCAS EM OBJETO DE SERVICO','33.04','33.00'),
	('ND','33.05 - LOCOMOCAO URBANA','33.05','33.00'),
	('ND','33.06 - PASSAGENS E LOCOMOCAO NA SUPERVISAO DE VENDAS','33.06','33.00'),
	('ND','33.96 - PASSAGENS E DESP.C/LOCOMOCAO-PAGTO ANTECIPADO','33.96','33.00'),
	('ND','34.00 - OUTRAS DESPESAS DE PESSOAL DECORRENTES DE CONTRATOS DE TERCEIRIZAÇÃO','34.00',NULL),
	('ND','35.00 - SERVIÇOS DE CONSULTORIA','35.00',NULL),
	('ND','36.00 - OUTROS SERVIÇOS DE TERCEIROS – PESSOA FÍSICA','36.00',NULL),
	('ND','36.01 - CONDOMINIOS','36.01','36.00'),
	('ND','36.02 - DIARIAS A COLABORADORES EVENTUAIS NO PAIS','36.02','36.00'),
	('ND','36.03 - DIARIAS A COLABORADORES EVENTUAIS NO EXTERIOR','36.03','36.00'),
	('ND','36.04 - COMISSOES E CORRETAGENS','36.04','36.00'),
	('ND','36.05 - DIREITOS AUTORAIS','36.05','36.00'),
	('ND','36.06 - SERVICOS TECNICOS PROFISSIONAIS','36.06','36.00'),
	('ND','36.07 - ESTAGIARIOS','36.07','36.00'),
	('ND','36.08 - BOLSA DE INICIACAO AO TRABALHO','36.08','36.00'),
	('ND','36.09 - SALARIOS DE INTERNOS EM PENITENCIARIAS','36.09','36.00'),
	('ND','36.10 - PERICIAS TECNICAS JUSTICA GRATUITA','36.10','36.00'),
	('ND','36.11 - PRO-LABORE A CONSULTORES EVENTUAIS','36.11','36.00'),
	('ND','36.12 - CAPATAZIA, ESTIVA E PESAGEM','36.12','36.00'),
	('ND','36.13 - CONFERENCIAS, EXPOSICOES E ESPETACULOS','36.13','36.00'),
	('ND','36.14 - ARMAZENAGEM','36.14','36.00'),
	('ND','36.15 - LOCACAO DE IMOVEIS','36.15','36.00'),
	('ND','36.16 - LOCACAO DE BENS MOVEIS E INTANGIVEIS','36.16','36.00'),
	('ND','36.17 - TRIBUTOS A CONTA DO LOCATARIO OU CESSIONARIO','36.17','36.00'),
	('ND','36.18 - MANUTENCAO E CONSERV. DE EQUIPAMENTOS','36.18','36.00'),
	('ND','36.19 - VIGILANCIA OSTENSIVA','36.19','36.00'),
	('ND','36.20 - MANUTENCAO E CONSERV. DE VEICULOS','36.20','36.00'),
	('ND','36.21 - MANUT.E CONS.DE B.MOVEIS DE OUTRAS NATUREZAS','36.21','36.00'),
	('ND','36.22 - MANUTENCAO E CONSERV. DE BENS IMOVEIS','36.22','36.00'),
	('ND','36.23 - FORNECIMENTO DE ALIMENTACAO','36.23','36.00'),
	('ND','36.24 - SERVICOS DE CARATER SECRETO OU RESERVADO','36.24','36.00'),
	('ND','36.25 - SERVICOS DE LIMPEZA E CONSERVACAO','36.25','36.00'),
	('ND','36.26 - SERVICOS DOMESTICOS','36.26','36.00'),
	('ND','36.27 - SERVICOS DE COMUNICACAO EM GERAL','36.27','36.00'),
	('ND','36.28 - SERVICO DE SELECAO E TREINAMENTO','36.28','36.00'),
	('ND','36.29 - HONORARIOS ADVOCATICIOS - ONUS DA SUCUMBENCIA','36.29','36.00'),
	('ND','36.30 - SERVICOS MEDICOS E ODONTOLOGICOS','36.30','36.00'),
	('ND','36.31 - SERVICOS DE REABILITACAO PROFISSIONAL','36.31','36.00'),
	('ND','36.32 - SERVICOS DE ASSISTENCIA SOCIAL','36.32','36.00'),
	('ND','36.34 - SERVICOS DE PERICIAS MEDICAS POR BENEFICIOS','36.34','36.00'),
	('ND','36.35 - SERV. DE APOIO ADMIN., TECNICO E OPERACIONAL','36.35','36.00'),
	('ND','36.36 - SERV. DE CONSERV. E REBENEFIC. DE MERCADORIAS','36.36','36.00'),
	('ND','36.37 - CONFECCAO DE MATERIAL DE ACONDIC. E EMBALAGEM','36.37','36.00'),
	('ND','36.38 - CONFECCAO DE UNIFORMES, BANDEIRAS E FLAMULAS','36.38','36.00'),
	('ND','36.39 - FRETES E TRANSPORTES DE ENCOMENDAS','36.39','36.00'),
	('ND','36.40 - ENCARGOS FINANCEIROS DEDUTIVEIS','36.40','36.00'),
	('ND','36.41 - MULTAS DEDUTIVEIS','36.41','36.00'),
	('ND','36.42 - JUROS','36.42','36.00'),
	('ND','36.43 - ENCARGOS FINANCEIROS INDEDUTIVEIS','36.43','36.00'),
	('ND','36.44 - MULTAS INDEDUTIVEIS','36.44','36.00'),
	('ND','36.45 - JETONS A CONSELHEIROS','36.45','36.00'),
	('ND','36.46 - DIARIAS A CONSELHEIROS','36.46','36.00'),
	('ND','36.56 - VARIACAO CAMBIAL NEGATIVA.','36.56','36.00'),
	('ND','36.59 - SERVICOS DE AUDIO, VIDEO E FOTO','36.59','36.00'),
	('ND','36.96 - OUTROS SERV.DE TERCEIROS PF- PAGTO ANTECIPADO','36.96','36.00'),
	('ND','37.00 - LOCAÇÃO DE MÃO-DE-OBRA','37.00',NULL),
	('ND','37.01 - APOIO ADMINISTRATIVO, TECNICO E OPERACIONAL','37.01','37.00'),
	('ND','37.02 - LIMPEZA E CONSERVACAO','37.02','37.00'),
	('ND','37.03 - VIGILANCIA OSTENSIVA','37.03','37.00'),
	('ND','37.04 - MANUTENCAO E CONSERVACAO DE BENS IMOVEIS','37.04','37.00'),
	('ND','37.05 - SERVICOS DE COPA E COZINHA','37.05','37.00'),
	('ND','37.06 - MANUTENCAO E CONSERVACAO DE BENS MOVEIS','37.06','37.00'),
	('ND','37.96 - LOCACAO DE MAO-DE-OBRA - PAGTO ANTECIPADO','37.96','37.00'),
	('ND','38.00 - ARRENDAMENTO MERCANTIL','38.00',NULL),
	('ND','38.01 - MAQUINAS E APARELHOS','38.01','38.00'),
	('ND','38.02 - VEICULOS FERROVIARIOS','38.02','38.00'),
	('ND','38.03 - VEICULOS RODOVIARIOS','38.03','38.00'),
	('ND','38.04 - OUTROS BENS MOVEIS','38.04','38.00'),
	('ND','38.05 - BENS IMOVEIS','38.05','38.00'),
	('ND','38.96 - ARRENDAMENTO MERCANTIL - PAGTO ANTECIPADO','38.96','38.00'),
	('ND','39.00 - OUTROS SERVIÇOS DE TERCEIROS – PESSOA JURÍDICA','39.00',NULL),
	('ND','39.01 - ASSINATURAS DE PERIODICOS E ANUIDADES','39.01','39.00'),
	('ND','39.02 - CONDOMINIOS','39.02','39.00'),
	('ND','39.03 - COMISSOES E CORRETAGENS','39.03','39.00'),
	('ND','39.04 - DIREITOS AUTORAIS','39.04','39.00'),
	('ND','39.05 - SERVICOS TECNICOS PROFISSIONAIS','39.05','39.00'),
	('ND','39.06 - CAPATAZIA, ESTIVA E PESAGEM','39.06','39.00'),
	('ND','39.07 - DESCONTOS FINANCEIROS CONCEDIDOS','39.07','39.00'),
	('ND','39.08 - MANUTENCAO DE SOFTWARE','39.08','39.00'),
	('ND','39.09 - ARMAZENAGEM','39.09','39.00'),
	('ND','39.10 - LOCACAO DE IMOVEIS','39.10','39.00'),
	('ND','39.11 - LOCACAO DE SOFTWARES','39.11','39.00'),
	('ND','39.12 - LOCACAO DE MAQUINAS E EQUIPAMENTOS','39.12','39.00'),
	('ND','39.13 - PERICIAS TECNICAS JUSTICA GRATUITA','39.13','39.00'),
	('ND','39.14 - LOCACAO BENS MOV. OUT.NATUREZAS E INTANGIVEIS','39.14','39.00'),
	('ND','39.15 - TRIBUTOS A CONTA DO LOCATARIO OU CESSIONARIO','39.15','39.00'),
	('ND','39.16 - MANUTENCAO E CONSERV. DE BENS IMOVEIS','39.16','39.00'),
	('ND','39.17 - MANUT. E CONSERV. DE MAQUINAS E EQUIPAMENTOS','39.17','39.00'),
	('ND','39.18 - SERVICOS DE ESTACIONAMENTO DE VEICULOS','39.18','39.00'),
	('ND','39.19 - MANUTENCAO E CONSERV. DE VEICULOS','39.19','39.00'),
	('ND','39.20 - MANUT.E CONS.DE B.MOVEIS DE OUTRAS NATUREZAS','39.20','39.00'),
	('ND','39.21 - MANUTENCAO E CONSERV. DE ESTRADAS E VIAS','39.21','39.00'),
	('ND','39.22 - EXPOSICOES, CONGRESSOS E CONFERENCIAS','39.22','39.00'),
	('ND','39.23 - FESTIVIDADES E HOMENAGENS','39.23','39.00'),
	('ND','39.29 - HONORARIOS ADVOCATICIOS - ONUS DA SUCUMBENCIA','39.29','39.00'),
	('ND','39.34 - VARIACAO CAMBIAL NEGATIVA','39.34','39.00'),
	('ND','39.35 - VARIACAO CAMBIAL NEGATIVA','39.35','39.00'),
	('ND','39.36 - MULTAS INDEDUTIVEIS','39.36','39.00'),
	('ND','39.37 - JUROS','39.37','39.00'),
	('ND','39.38 - ENCARGOS FINANCEIROS DEDUTIVEIS','39.38','39.00'),
	('ND','39.39 - ENCARGOS FINANCEIROS INDEDUTIVEIS','39.39','39.00'),
	('ND','39.40 - PROGRAMA DE ALIMENTACAO DO TRABALHADOR','39.40','39.00'),
	('ND','39.41 - FORNECIMENTO DE ALIMENTACAO','39.41','39.00'),
	('ND','39.42 - SERVICOS DE CARATER SECRETO OU RESERVADO','39.42','39.00'),
	('ND','39.43 - SERVICOS DE ENERGIA ELETRICA','39.43','39.00'),
	('ND','39.44 - SERVICOS DE AGUA E ESGOTO','39.44','39.00'),
	('ND','39.45 - SERVICOS DE GAS','39.45','39.00'),
	('ND','39.46 - SERVICOS DOMESTICOS','39.46','39.00'),
	('ND','39.47 - SERVICOS DE COMUNICACAO EM GERAL','39.47','39.00'),
	('ND','39.48 - SERVICO DE SELECAO E TREINAMENTO','39.48','39.00'),
	('ND','39.49 - PRODUCOES JORNALISTICAS','39.49','39.00'),
	('ND','39.50 - SERV.MEDICO-HOSPITAL.,ODONTOL.E LABORATORIAIS','39.50','39.00'),
	('ND','39.51 - SERVICOS DE ANALISES E PESQUISAS CIENTIFICAS','39.51','39.00'),
	('ND','39.52 - SERVICOS DE REABILITACAO PROFISSIONAL','39.52','39.00'),
	('ND','39.53 - SERVICOS DE ASSISTENCIA SOCIAL','39.53','39.00'),
	('ND','39.54 - SERVICOS DE CRECHES E ASSIST. PRE-ESCOLAR','39.54','39.00'),
	('ND','39.55 - SERVICOS DE CONFECCAO SELOS CONTROLE FISCAL','39.55','39.00'),
	('ND','39.56 - SERV.DE PERICIA MEDICA/ODONTOLOG P/BENEFICIOS','39.56','39.00'),
	('ND','39.57 - SERVICOS DE PROC. DE DADOS','39.57','39.00'),
	('ND','39.58 - SERVICOS DE TELECOMUNICACOES','39.58','39.00'),
	('ND','39.59 - SERVICOS DE AUDIO, VIDEO E FOTO','39.59','39.00'),
	('ND','39.60 - SERVICOS DE MANOBRA E PATRULHAMENTO','39.60','39.00'),
	('ND','39.61 - SERVICOS DE SOCORRO E SALVAMENTO','39.61','39.00'),
	('ND','39.62 - SERVICOS DE PRODUCAO INDUSTRIAL','39.62','39.00'),
	('ND','39.63 - SERVICOS GRAFICOS E EDITORIAIS','39.63','39.00'),
	('ND','39.65 - SERVICOS DE APOIO AO ENSINO','39.65','39.00'),
	('ND','39.66 - SERVICOS JUDICIARIOS','39.66','39.00'),
	('ND','39.67 - SERVICOS FUNERARIOS','39.67','39.00'),
	('ND','39.68 - SERV. DE CONSERV. E REBENEF. DE MERCADORIAS','39.68','39.00'),
	('ND','39.69 - SEGUROS EM GERAL','39.69','39.00'),
	('ND','39.70 - CONFECCAO DE UNIFORMES, BANDEIRAS E FLAMULAS','39.70','39.00'),
	('ND','39.71 - CONFECCAO DE MATERIAL DE ACONDIC. E EMBALAGEM','39.71','39.00'),
	('ND','39.72 - VALE-TRANSPORTE','39.72','39.00'),
	('ND','39.73 - TRANSPORTE DE SERVIDORES','39.73','39.00'),
	('ND','39.74 - FRETES E TRANSP. DE ENCOMENDAS','39.74','39.00'),
	('ND','39.75 - SERVICO DE INCINERACAO/DESTRUICAO DE MATERIAL','39.75','39.00'),
	('ND','39.76 - CLASSIFICACAO DE PRODUTOS','39.76','39.00'),
	('ND','39.77 - VIGILANCIA OSTENSIVA/MONITORADA','39.77','39.00'),
	('ND','39.78 - LIMPEZA E CONSERVACAO','39.78','39.00'),
	('ND','39.79 - SERV. DE APOIO ADMIN., TECNICO E OPERACIONAL','39.79','39.00'),
	('ND','39.80 - HOSPEDAGENS','39.80','39.00'),
	('ND','39.81 - SERVICOS BANCARIOS','39.81','39.00'),
	('ND','39.82 - SERVICOS DE CONTROLE AMBIENTAL','39.82','39.00'),
	('ND','39.83 - SERVICOS DE COPIAS E REPRODUCAO DE DOCUMENTOS','39.83','39.00'),
	('ND','39.84 - INTEGRACAO DADOS ESTADOS E MUNICIPIOS - SAFEM','39.84','39.00'),
	('ND','39.85 - SERVICOS EM ITENS REPARAVEIS DE AVIACAO','39.85','39.00'),
	('ND','39.86 - PATROCINIOS','39.86','39.00'),
	('ND','39.87 - SERVICOS RELACIONADOS A INDUST. AEROESPACIAL','39.87','39.00'),
	('ND','39.89 - MANUTENCAO DE REPARTICOES DO SERV. EXTERIOR','39.89','39.00'),
	('ND','39.90 - SERVICOS DE PUBLICIDADE LEGAL','39.90','39.00'),
	('ND','39.91 - SERVICOS DE PUBLICIDADE MERCADOLOGICA','39.91','39.00'),
	('ND','39.92 - SERVICOS DE PUBLICIDADE INSTITUCIONAL','39.92','39.00'),
	('ND','39.93 - SERVICOS DE PUBLICIDADE DE UTILIDADE PUBLICA','39.93','39.00'),
	('ND','39.94 - AQUISICAO DE SOFTWARES DE APLICACAO.','39.94','39.00'),
	('ND','39.95 - MANUT.CONS.EQUIP. DE PROCESSAMENTO DE DADOS','39.95','39.00'),
	('ND','39.96 - OUTROS SERV.DE TERCEIROS PJ- PAGTO ANTECIPADO','39.96','39.00'),
	('ND','39.97 - DESPESAS DE TELEPROCESSAMENTO','39.97','39.00'),
	('ND','39.99 - OUTROS SERVICOS DE TERCEIROS-PESSOA JURIDICA','39.99','39.00'),
	('ND','41.00 - CONTRIBUIÇÕES','41.00',NULL),
	('ND','42.00 - AUXÍLIOS','42.00',NULL),
	('ND','43.00 - SUBVENÇÕES SOCIAIS','43.00',NULL),
	('ND','44.00 - SUBVENÇÕES ECONÔMICAS','44.00',NULL),
	('ND','45.00 - EQUALIZAÇÃO DE PREÇOS E TAXAS','45.00',NULL),
	('ND','46.00 - AUXÍLIO ALIMENTAÇÃO','46.00',NULL),
	('ND','46.01 - INDENIZACAO AUXILIO-ALIMENTACAO','46.01','46.00'),
	('ND','47.00 - OBRIGAÇÕES TRIBUTÁRIAS E CONTRIBUTIVAS','47.00',NULL),
	('ND','47.01 - IMPOSTO S/ PROPRIEDADE TERRITORIAL RURAL-ITR','47.01','47.00'),
	('ND','47.02 - IMPOSTO S/ PROP. PREDIAL E TERRIT.URBANA-IPTU','47.02','47.00'),
	('ND','47.03 - IMPOSTO DE RENDA','47.03','47.00'),
	('ND','47.04 - ADICIONAL DO IMPOSTO DE RENDA','47.04','47.00'),
	('ND','47.05 - IMPOSTO S/ PROPR.DE VEICULOS AUTOMOTORES-IPVA','47.05','47.00'),
	('ND','47.06 - IMPOSTO SOBRE PRODUTOS INDUSTRIALIZADOS - IPI','47.06','47.00'),
	('ND','47.07 - IMPOSTO S/ CIRC. DE MERCAD. E SERVICOS - ICMS','47.07','47.00'),
	('ND','47.08 - IMPOSTO S/SERVICOS DE QUALQUER NATUREZA-ISSQN','47.08','47.00'),
	('ND','47.09 - IMPOSTO SOBRE OPERACOES FINANCEIRAS - IOF','47.09','47.00'),
	('ND','47.10 - TAXAS','47.10','47.00'),
	('ND','47.11 - COFINS','47.11','47.00'),
	('ND','47.12 - CONTRIBUICAO P/ O PIS/PASEP','47.12','47.00'),
	('ND','47.13 - CONTRIBUICAO SOCIAL S/ LUCRO','47.13','47.00'),
	('ND','47.14 - CPMF','47.14','47.00'),
	('ND','47.15 - MULTAS DEDUTIVEIS','47.15','47.00'),
	('ND','47.16 - JUROS','47.16','47.00'),
	('ND','47.18 - CONTRIB.PREVIDENCIARIAS-SERVICOS DE TERCEIROS','47.18','47.00'),
	('ND','47.19 - INSS - DIARIAS','47.19','47.00'),
	('ND','47.20 - OBRIGACOES PATRONAIS S/ SERV. PESSOA JURIDICA','47.20','47.00'),
	('ND','47.21 - CONTRIBUICAO SINDICAL','47.21','47.00'),
	('ND','47.96 - OBRIGACOES TRIBUTARIAS - PAGTO ANTECIPADO','47.96','47.00'),
	('ND','48.00 - OUTROS AUXÍLIOS FINANCEIROS A PESSOAS FÍSICAS','48.00',NULL),
	('ND','49.00 - AUXÍLIO TRANSPORTE','49.00',NULL),
	('ND','51.00 - OBRAS E INSTALAÇÕES','51.00',NULL),
	('ND','51.80 - ESTUDOS E PROJETOS','51.80','51.00'),
	('ND','51.84 - INTEGRACAO DADOS ESTADOS E MUNICIPIOS - SAFEM','51.84','51.00'),
	('ND','51.90 - INTEGR. DADOS ORGAOS E ENTID. PARCIAIS SIAFI','51.90','51.00'),
	('ND','51.91 - OBRAS EM ANDAMENTO','51.91','51.00'),
	('ND','51.92 - INSTALACOES','51.92','51.00'),
	('ND','51.93 - BENFEITORIAS EM PROPRIEDADES DE TERCEIROS','51.93','51.00'),
	('ND','51.96 - ALMOXARIFADO DE OBRAS','51.96','51.00'),
	('ND','51.99 - OUTRAS OBRAS E INSTALACOES','51.99','51.00'),
	('ND','52.00 - EQUIPAMENTOS E MATERIAL PERMANENTE','52.00',NULL),
	('ND','52.02 - AERONAVES','52.02','52.00'),
	('ND','52.04 - APARELHOS DE MEDICAO E ORIENTACAO','52.04','52.00'),
	('ND','52.06 - APARELHOS E EQUIPAMENTOS DE COMUNICACAO','52.06','52.00'),
	('ND','52.08 - APAR.EQUIP.UTENS.MED.,ODONT,LABOR.HOSPIT.','52.08','52.00'),
	('ND','52.10 - APARELHOS E EQUIP. P/ ESPORTES E DIVERSOES','52.10','52.00'),
	('ND','52.12 - APARELHOS E UTENSILIOS DOMESTICOS','52.12','52.00'),
	('ND','52.14 - ARMAMENTOS','52.14','52.00'),
	('ND','52.18 - COLECOES E MATERIAIS BIBLIOGRAFICOS','52.18','52.00'),
	('ND','52.19 - DISCOTECAS E FILMOTECAS','52.19','52.00'),
	('ND','52.20 - EMBARCACOES','52.20','52.00'),
	('ND','52.22 - EQUIPAMENTOS DE MANOBRA E PATRULHAMENTO','52.22','52.00'),
	('ND','52.24 - EQUIPAMENTO DE PROTECAO, SEGURANCA E SOCORRO','52.24','52.00'),
	('ND','52.26 - INSTRUMENTOS MUSICAIS E ARTISTICOS','52.26','52.00'),
	('ND','52.28 - MAQUINAS E EQUIPAM. DE NATUREZA INDUSTRIAL','52.28','52.00'),
	('ND','52.30 - MAQUINAS E EQUIPAMENTOS ENERGETICOS','52.30','52.00'),
	('ND','52.32 - MAQUINAS E EQUIPAMENTOS GRAFICOS','52.32','52.00'),
	('ND','52.33 - EQUIPAMENTOS PARA AUDIO, VIDEO E FOTO','52.33','52.00'),
	('ND','52.34 - MAQUINAS, UTENSILIOS E EQUIPAMENTOS DIVERSOS','52.34','52.00'),
	('ND','52.35 - EQUIPAMENTOS DE PROCESSAMENTO DE DADOS','52.35','52.00'),
	('ND','52.36 - MAQUINAS, INSTALACOES E UTENS. DE ESCRITORIO','52.36','52.00'),
	('ND','52.38 - MAQ., FERRAMENTAS E UTENSILIOS DE OFICINA','52.38','52.00'),
	('ND','52.39 - EQUIP. E UTENSILIOS HIDRAULICOS E ELETRICOS','52.39','52.00'),
	('ND','52.40 - MAQUINAS E EQUIPAMENTOS AGRIC. E RODOVIARIOS','52.40','52.00'),
	('ND','52.42 - MOBILIARIO EM GERAL','52.42','52.00'),
	('ND','52.44 - OBRAS DE ARTE E PECAS PARA EXPOSICAO','52.44','52.00'),
	('ND','52.46 - SEMOVENTES E EQUIPAMENTOS DE MONTARIA','52.46','52.00'),
	('ND','52.48 - VEICULOS DIVERSOS','52.48','52.00'),
	('ND','52.50 - VEICULOS FERROVIARIOS','52.50','52.00'),
	('ND','52.51 - PECAS NAO INCORPORAVEIS A IMOVEIS','52.51','52.00'),
	('ND','52.52 - VEICULOS DE TRACAO MECANICA','52.52','52.00'),
	('ND','52.53 - CARROS DE COMBATE','52.53','52.00'),
	('ND','52.54 - EQUIPAMENTOS, PECAS E ACESSORIOS AERONAUTICOS','52.54','52.00'),
	('ND','52.56 - EQUIPAMENTOS, PECAS E ACES.DE PROTECAO AO VOO','52.56','52.00'),
	('ND','52.57 - ACESSORIOS PARA VEICULOS','52.57','52.00'),
	('ND','52.58 - EQUIPAMENTOS DE MERGULHO E SALVAMENTO','52.58','52.00'),
	('ND','52.60 - EQUIPAMENTOS, PECAS E ACESSORIOS MARITIMOS','52.60','52.00'),
	('ND','52.83 - EQUIPAMENTOS E SISTEMA DE PROT.VIG.AMBIENTAL','52.83','52.00'),
	('ND','52.84 - INTEGRACAO DADOS ESTADOS E MUNICIPIOS - SAFEM','52.84','52.00'),
	('ND','52.87 - MATERIAL DE CONSUMO DE USO DURADOURO','52.87','52.00'),
	('ND','52.89 - EQUIP.SOB.DE MAQ.MOTOR.DE NAVIOS DA ESQUADRA','52.89','52.00'),
	('ND','52.90 - INTEGR. DADOS ORGAOS E ENTID. PARCIAIS SIAFI','52.90','52.00'),
	('ND','52.96 - EQUIP. E MAT. PERMANENTE - PAGTO ANTECIPADO','52.96','52.00'),
	('ND','52.99 - OUTROS MATERIAIS PERMANENTES','52.99','52.00'),
	('ND','53.00 - INTEGRALIZAÇÃO DE FUNDOS ROTATIVOS','53.00',NULL),
	('ND','61.00 - AQUISIÇÃO DE IMÓVEIS','61.00',NULL),
	('ND','61.01 - EDIFICIOS - REALIZACAO DE OBRAS','61.01','61.00'),
	('ND','61.03 - TERRENOS','61.03','61.00'),
	('ND','61.06 - SALAS E ESCRITORIOS','61.06','61.00'),
	('ND','61.07 - CASAS E APARTAMENTOS','61.07','61.00'),
	('ND','61.08 - ARMAZENS E SILOS','61.08','61.00'),
	('ND','61.84 - INTEGRACAO DADOS ESTADOS E MUNICIPIOS - SAFEM','61.84','61.00'),
	('ND','61.90 - INTEGR. DADOS ORGAOS E ENTID. PARCIAIS SIAFI','61.90','61.00'),
	('ND','61.99 - OUTRAS AQUISICOES DE BENS IMOVEIS','61.99','61.00'),
	('ND','62.00 - AQUISIÇÃO DE PRODUTOS PARA REVENDA','62.00',NULL),
	('ND','63.00 - AQUISIÇÃO DE TÍTULOS DE CRÉDITO','63.00',NULL),
	('ND','64.00 - AQUISIÇÃO DE TÍTULOS REPRESENTATIVOS DE CAPITAL JÁ INTEGRALIZADO','64.00',NULL),
	('ND','65.00 - CONSTITUIÇÃO OU AUMENTO DE CAPITAL DE EMPRESAS','65.00',NULL),		
	('ND','66.00 - CONCESSÃO DE EMPRÉSTIMOS E FINANCIAMENTOS','66.00',NULL),
	('ND','67.00 - DEPÓSITOS COMPULSÓRIOS','67.00',NULL),
	('ND','67.01 - DEPOSITOS E CAUCOES','67.01','67.00'),
	('ND','67.02 - DEPOSITOS JUDICIAIS','67.02','67.00'),
	('ND','67.03 - DEPOSITOS PARA RECURSOS','67.03','67.00'),
	('ND','68.00 - TRANSFERÊNCIAS CONSTITUCIONAIS A MUNICÍPIOS','68.00',NULL),
	('ND','69.00 - TRANSFERÊNCIAS VOLUNTÁRIAS A MUNICÍPIOS','69.00',NULL),
	('ND','71.00 - PRINCIPAL DA DÍVIDA CONTRATUAL RESGATADO','71.00',NULL),
	('ND','72.00 - PRINCIPAL DA DÍVIDA MOBILIÁRIA RESGATADO','72.00',NULL),
	('ND','73.00 - CORREÇÃO MONETÁRIA E CAMBIAL DA DÍVIDA POR CONTRATO RESGATADA','73.00',NULL),
	('ND','74.00 - CORREÇÃO MONETÁRIA E CAMBIAL DA DÍVIDA MOBILIÁRIA RESGATADA','74.00',NULL),
	('ND','75.00 - CORREÇÃO MONETÁRIA DA DÍVIDA DE OPERAÇÕES DE CRÉDITO POR ANTECIPAÇÃO DA RECEITA','75.00',NULL),
	('ND','76.00 - PRINCIPAL CORRIGIDO DA DÍVIDA MOBILIÁRIA REFINANCIADO','76.00',NULL),
	('ND','77.00 - PRINCIPAL CORRIGIDO DA DÍVIDA CONTRATUAL REFINANCIADO','77.00',NULL),
	('ND','81.00 - DISTRIBUIÇÃO CONSTITUCIONAL OU LEGAL DE RECEITAS','81.00',NULL),
	('ND','91.00 - SENTENÇAS JUDICIAIS','91.00',NULL),
	('ND','91.01 - SENTENCAS JUDICIAIS TRANSITADAS EM JULGADO','91.01','91.00'),
	('ND','91.02 - PRECATORIOS INCLUIDOS NA LEI DO ORCAMENTO','91.02','91.00'),
	('ND','91.03 - LIMINARES EM MANDADOS DE SEGURANCA','91.03','91.00'),
	('ND','91.05 - SENTENCAS JUDICIAIS TRANSITADAS EM JULGADO','91.05','91.00'),
	('ND','91.03 - DECISOES JUDICIAIS','91.03','91.00'),
	('ND','91.84 - INTEGRACAO DADOS ESTADOS E MUNICIPIOS - SAFEM','91.84','91.00'),
	('ND','91.90 - INTEGR. DADOS ORGAOS E ENTID. PARCIAIS SIAFI','91.90','91.00'),
	('ND','91.99 - DIVERSAS SENTENCAS','91.99','91.00'),
	('ND','92.00 - DESPESAS DE EXERCÍCIOS ANTERIORES','92.00',NULL),
	('ND','92.01 - OBRAS E INSTALACOES','92.01','92.00'),
	('ND','92.02 - EQUIPAMENTOS E MATERIAL PERMANENTE','92.02','92.00'),
	('ND','92.03 - PENSOES','92.03','92.00'),
	('ND','92.04 - CONTRATACAO POR TEMPO DETERMINADO','92.04','92.00'),
	('ND','92.05 - OUTROS BENEFICIOS PREVIDENCIARIOS','92.05','92.00'),
	('ND','92.06 - BENEFICIO MENSAL AO DEFICIENTE E AO IDOSO','92.06','92.00'),
	('ND','92.07 - CONTRIB. A ENTIDADES FECHADAS DE PREVIDENCIA','92.07','92.00'),
	('ND','92.08 - OUTROS BENEFICIOS ASSISTENCIAIS','92.08','92.00'),
	('ND','92.10 - OUTROS BENEFICIOS DE NATUREZA SOCIAL','92.10','92.00'),
	('ND','92.14 - DIARIAS - PESSOAL CIVIL','92.14','92.00'),
	('ND','92.15 - DIARIAS - PESSOAL MILITAR','92.15','92.00'),
	('ND','92.18 - AUXILIO FINANCEIRO A ESTUDANTES','92.18','92.00'),
	('ND','92.31 - PREMIACOES CULT, CIENT, ART, DESP E OUTRAS','92.31','92.00'),
	('ND','92.32 - MATERIAL DE DISTRIBUICAO GRATUITA','92.32','92.00'),
	('ND','92.33 - PASSAGENS E DESPESAS COM LOCOMOCAO','92.33','92.00'),
	('ND','92.36 - SERVICOS DE TERCEIROS - PESSOA FISICA','92.36','92.00'),
	('ND','92.37 - SERVICOS DE TERCEIROS - PESSOA FISICA','92.37','92.00'),
	('ND','92.38 - SERVICOS DE TERCEIROS - PESSOA FISICA','92.38','92.00'),
	('ND','92.39 - SERVICOS DE TERCEIROS - PESSOA JURIDICA','92.39','92.00'),
	('ND','92.45 - EQUALIZACAO DE PRECOS','92.45','92.00'),
	('ND','92.46 - AUXILIO-ALIMENTACAO','92.46','92.00'),
	('ND','92.47 - OBRIGACOES TRIBUTARIAS E CONTRIBUTIVAS','92.47','92.00'),
	('ND','92.48 - OUTROS AUXÍLIOS FINANCEIROS A PESSOA FÍSICA','92.48','92.00'),
	('ND','92.49 - AUXILIO-TRANPORTE','92.49','92.00'),
	('ND','92.91 - SENTENCAS JUDICIAIS','92.91','92.00'),
	('ND','92.92 - MATERIAL DE CONSUMO','92.92','92.00'),
	('ND','92.93 - INDENIZACOES E RESTITUICOES','92.93','92.00'),
	('ND','92.99 - OUTRAS DESPESAS CORRENTES','92.99','92.00'),
	('ND','93.00 - INDENIZAÇÕES E RESTITUIÇÕES','93.00',NULL),
	('ND','93.01 - INDENIZACOES','93.01','93.00'),
	('ND','93.02 - RESTITUICOES','93.02','93.00'),
	('ND','93.03 - AJUDA DE CUSTO - PESSOAL CIVIL','93.03','93.00'),
	('ND','93.04 - COMPL. ATUALIZACAO MONETARIA - LC 110/01','93.04','93.00'),
	('ND','93.05 - INDENIZACAO DE TRANSPORTE - PESSOAL CIVIL','93.05','93.00'),
	('ND','93.07 - INDENIZACAO DE MORADIA - PESSOAL CIVIL','93.07','93.00'),
	('ND','93.08 - RESSARCIMENTO ASSISTENCIA MEDICA/ODONTOLOGICA','93.08','93.00'),
	('ND','93.09 - REMOCAO - PESSOAL CIVIL','93.09','93.00'),
	('ND','94.00 - INDENIZAÇÕES E RESTITUIÇÕES TRABALHISTAS','94.00',NULL),
	('ND','95.00 - INDENIZAÇÃO PELA EXECUÇÃO DE TRABALHOS DE CAMPO','95.00',NULL),
	('ND','96.00 - RESSARCIMENTO DE DESPESAS DE PESSOAL REQUISITADO','96.00',NULL),
	('PopulacaoAtendida','Urbana','Urbana',NULL),
	('PopulacaoAtendida','Rural','Rural',NULL),
	('FormaImplantacao','Direta','Direta',NULL),
	('FormaImplantacao','Indireta','Indireta',NULL),
	('FormaImplantacao','A definir','A definir',NULL);
