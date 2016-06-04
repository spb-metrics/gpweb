SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.0.11'; 
UPDATE versao SET ultima_atualizacao_bd='2012-01-04'; 
UPDATE versao SET ultima_atualizacao_codigo='2012-01-04'; 
UPDATE versao SET versao_bd=95;

ALTER TABLE projetos MODIFY projeto_especial INTEGER(1) DEFAULT '0';
ALTER TABLE baseline_projetos MODIFY projeto_especial INTEGER(1) DEFAULT '0';

DELETE FROM sisvalores WHERE sisvalor_titulo='TipoRecurso' AND sisvalor_valor='Indefinido';

