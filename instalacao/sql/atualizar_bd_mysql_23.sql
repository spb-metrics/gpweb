UPDATE versao SET versao_bd=23; 

ALTER TABLE projetos MODIFY projeto_percentagem FLOAT DEFAULT '0'; 
ALTER TABLE projetos MODIFY projeto_meta_custo FLOAT DEFAULT '0'; 
ALTER TABLE projetos MODIFY projeto_custo_atual FLOAT DEFAULT '0'; 
ALTER TABLE tarefas MODIFY tarefa_custo_almejado FLOAT DEFAULT '0'; 



