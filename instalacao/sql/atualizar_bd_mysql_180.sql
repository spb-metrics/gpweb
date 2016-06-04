SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.3.19'; 
UPDATE versao SET ultima_atualizacao_bd='2013-09-15'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-09-15'; 
UPDATE versao SET versao_bd=180;

ALTER TABLE perfil_acesso ADD COLUMN perfil_acesso_negar INTEGER(1) DEFAULT 0;

INSERT INTO artefato_campo (artefato_campo_arquivo, artefato_campo_campo, artefato_campo_descricao) VALUES 
	('cabecalho_padrao_pro.html','projeto_cia','organização do projeto'),
	('cabecalho_padrao_pro.html','projeto_nome','nome do projeto'),
	('cabecalho_padrao_pro.html','projeto_codigo','código do projeto'),
	('cabecalho_padrao_pro.html','titulo_cabecalho','Título do artefato'),
	('cabecalho_padrao_pro.html','corpo_cabecalho','Corpo do artefato');
