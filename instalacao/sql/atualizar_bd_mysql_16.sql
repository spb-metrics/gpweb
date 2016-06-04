UPDATE versao SET versao_bd=16; 

ALTER TABLE causa_efeito DROP COLUMN causa_efeito_dept;
ALTER TABLE causa_efeito DROP COLUMN causa_efeito_projeto_id;
ALTER TABLE causa_efeito DROP COLUMN causa_efeito_tarefa_id;
ALTER TABLE causa_efeito DROP COLUMN causa_efeito_pratica_id;
ALTER TABLE causa_efeito DROP COLUMN causa_efeito_indicador_id;
ALTER TABLE causa_efeito DROP COLUMN causa_efeito_brainstorm;
