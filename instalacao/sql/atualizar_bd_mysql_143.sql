SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.2.1'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-27'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-27'; 
UPDATE versao SET versao_bd=143;

DELETE FROM campo_formulario WHERE campo_formulario_tipo='cias';

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('cias','cia_projeto_ativos','Projetos ativos',0),
	('cias','cia_projeto_inativos','Projetos inativos',0),
	('cias','cia_codigo','Código',0),
	('cias','cia_cnpj','CNPJ',0),
	('cias','cia_tel1','Telefone',0),
	('cias','cia_tel2','Telefone 2',0),
	('cias','cia_fax','Fax',0),
	('cias','cia_endereco1','Endereço',0),
	('cias','cia_cidade','Município',0),
	('cias','cia_estado','Estado',0),
	('cias','cia_cep','CEP',0),
	('cias','cia_pais','País',0),
	('cias','cia_url','URL',0),
	('cias','cia_descricao','Descrição',0),
	('cias','cia_tipo','Tipo',0),
	('cias','cia_email','E-mail',0);

