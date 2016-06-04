UPDATE versao SET versao_bd=27; 
UPDATE versao SET versao_codigo='4.7'; 
DELETE FROM config WHERE config_nome='exibe_sem_classificacao_em_pesquisa';

ALTER TABLE grupo change criadorID criador_id INTEGER(100) UNSIGNED DEFAULT '0';

ALTER TABLE grupo ADD COLUMN protegido TINYINT(1) DEFAULT '0';




