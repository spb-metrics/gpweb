UPDATE versao SET versao_bd=53; 
UPDATE versao SET versao_codigo='7.7.4'; 

ALTER TABLE pratica_indicador MODIFY pratica_indicador_nome varchar(255) DEFAULT NULL;
ALTER TABLE pratica_acao MODIFY pratica_acao_nome varchar(255) DEFAULT NULL;
ALTER TABLE plano_gestao_perspectivas MODIFY pg_perspectiva_nome varchar(255) DEFAULT NULL;
ALTER TABLE praticas MODIFY pratica_nome varchar(255) DEFAULT NULL;
