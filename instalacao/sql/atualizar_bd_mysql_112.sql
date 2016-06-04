SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.28'; 
UPDATE versao SET ultima_atualizacao_bd='2012-07-01'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-07-01'; 
UPDATE versao SET versao_bd=112;
UPDATE config SET config_valor='49f2d4' WHERE config_valor='0096ff';


INSERT INTO config (config_nome, config_valor, config_grupo, config_tipo) VALUES 
  ('ldap_charset','iso88591','ldap','select'),
	('porcentagem_100_110','49f2d4','cor','combo_cor'),
	('porcentagem_110_120','49f2f0','cor','combo_cor'),
	('porcentagem_120_130','49e4f2','cor','combo_cor'),
	('porcentagem_130_140','3fd0ef','cor','combo_cor'),
	('porcentagem_140_150','3fbbef','cor','combo_cor'),
	('porcentagem_150_160','3fa2ef','cor','combo_cor'),
	('porcentagem_160_170','3f79ef','cor','combo_cor'),
	('porcentagem_170_180','3f4fef','cor','combo_cor'),
	('porcentagem_180_190','753fef','cor','combo_cor'),
	('porcentagem_190_200','923fef','cor','combo_cor'),
	('porcentagem_200','a23fef','cor','combo_cor'),
	('porcentagem_maxima','100','cor','text');


INSERT INTO config_lista (config_nome, config_lista_nome) VALUES 
  ('ldap_charset','iso88591'),
  ('ldap_charset','utf8');

ALTER TABLE recursos ADD COLUMN recurso_hora_custo DECIMAL(20,3) UNSIGNED DEFAULT 0;

ALTER TABLE expediente ADD COLUMN recurso_id INTEGER(100) UNSIGNED DEFAULT NULL;
ALTER TABLE expediente ADD KEY recurso_id (recurso_id);
ALTER TABLE expediente ADD CONSTRAINT expediente_fk4 FOREIGN KEY (recurso_id) REFERENCES recursos (recurso_id) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE artefatos_tipo ADD COLUMN artefato_tipo_endereco VARCHAR(200);
ALTER TABLE artefatos_tipo ADD COLUMN artefato_tipo_arquivo VARCHAR(30);


ALTER TABLE pratica_indicador MODIFY pratica_indicador_parametro_projeto VARCHAR(100);

UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='demanda.html' WHERE artefato_tipo_id=1;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='estudo_viabilidade.html' WHERE artefato_tipo_id=2;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='termo_abertura.html' WHERE artefato_tipo_id=3;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='embasamento_projeto.html' WHERE artefato_tipo_id=4;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='plano_qualidade.html' WHERE artefato_tipo_id=5;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='plano_comunicacoes.html' WHERE artefato_tipo_id=6;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='gerenciamento_risco.html' WHERE artefato_tipo_id=7;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='plano_gerenciamento.html' WHERE artefato_tipo_id=8;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='licoes_aprendidas.html' WHERE artefato_tipo_id=9;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='termo_encerramento.html' WHERE artefato_tipo_id=10;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='termo_recebimento.html' WHERE artefato_tipo_id=11;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='solicitacao_mudanca.html' WHERE artefato_tipo_id=12;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='ata_reuniao.html' WHERE artefato_tipo_id=13;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='mensuracao_projeto.html' WHERE artefato_tipo_id=14;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='matriz_indicadores.html' WHERE artefato_tipo_id=30;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/mpog',artefato_tipo_arquivo='arvore_problema.html' WHERE artefato_tipo_id=31;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='demanda.html' WHERE artefato_tipo_id=15;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='estudo_viabilidade.html' WHERE artefato_tipo_id=16;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='termo_abertura.html' WHERE artefato_tipo_id=17;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='embasamento_projeto.html' WHERE artefato_tipo_id=18;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='plano_qualidade.html' WHERE artefato_tipo_id=19;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='plano_comunicacoes.html' WHERE artefato_tipo_id=20;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='gerenciamento_risco.html' WHERE artefato_tipo_id=21;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='plano_gerenciamento.html' WHERE artefato_tipo_id=22;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='licoes_aprendidas.html' WHERE artefato_tipo_id=23;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='termo_encerramento.html' WHERE artefato_tipo_id=24;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='termo_recebimento.html' WHERE artefato_tipo_id=25;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='solicitacao_mudanca.html' WHERE artefato_tipo_id=26;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='ata_reuniao.html' WHERE artefato_tipo_id=27;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='mensuracao_projeto.html' WHERE artefato_tipo_id=28;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='matriz_indicadores.html' WHERE artefato_tipo_id=29;
UPDATE artefatos_tipo SET artefato_tipo_endereco='modulos/projetos/artefatos/cnj',artefato_tipo_arquivo='arvore_problema.html' WHERE artefato_tipo_id=32;  
  
	