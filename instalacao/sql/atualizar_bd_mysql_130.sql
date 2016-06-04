SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.1.0'; 
UPDATE versao SET ultima_atualizacao_bd='2012-11-15'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-11-15'; 
UPDATE versao SET versao_bd=130; 


ALTER TABLE demandas ADD COLUMN demanda_prazo TEXT;
ALTER TABLE demandas ADD COLUMN demanda_custos TEXT;
ALTER TABLE demandas ADD COLUMN demanda_observacao TEXT;

ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_tempo TEXT;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_custo TEXT;
ALTER TABLE projeto_viabilidade ADD COLUMN projeto_viabilidade_observacao TEXT;

ALTER TABLE projeto_abertura ADD COLUMN projeto_abertura_observacao TEXT;

INSERT INTO campo_formulario (campo_formulario_tipo, campo_formulario_campo, campo_formulario_descricao, campo_formulario_ativo) VALUES 
	('demanda','demanda_prazo','Prazo',0),
	('demanda','demanda_custos','Custos',0),
	('demanda','demanda_observacao','Observações',0),
	('viabilidade','projeto_viabilidade_tempo','Tempo',0),
	('viabilidade','projeto_viabilidade_custo','Custos',0),
	('viabilidade','projeto_viabilidade_observacao','Observações',0),
	('abertura','projeto_abertura_observacao','Observações',0);
