UPDATE versao SET versao_bd=22; 

ALTER TABLE tarefas ADD COLUMN tarefa_cia INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE foruns ADD COLUMN forum_cia INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE arquivos ADD COLUMN arquivo_cia INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE arquivo_pastas ADD COLUMN arquivo_pasta_cia INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE links ADD COLUMN link_cia INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE eventos ADD COLUMN evento_cia INTEGER(100) UNSIGNED DEFAULT '0';

DELETE FROM sisvalores WHERE sisvalor_titulo='Alpha3';

INSERT INTO sisvalores (sisvalor_chave_id, sisvalor_titulo, sisvalor_valor, sisvalor_valor_id) VALUES 
(1,	'Arma1','OTT','25'),
(1,	'Arma1','STT','26');


