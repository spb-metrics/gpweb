SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=4 WHERE mod_diretorio='social';

ALTER TABLE social_familia ADD COLUMN social_familia_cnpj VARCHAR(18) DEFAULT NULL;
UPDATE modulos SET mod_menu='Programas Sociais:social_p.gif::Menu de programas sociais.;Lista de programas sociais:social_p.gif:m=social&a=index:Lista de programas sociais cadastrados.;Lista de ações sociais:acao_p.png:m=social&a=acao_lista:Lista de ações sociais, que são parte de programas sociais, cadastradas.;Lista de comitês:comite_p.gif:m=social&a=comite_lista:Lista de comitês cadastrados.;Lista de comunidades:comunidade_p.gif:m=social&a=comunidade_lista:Lista de comunidades cadastradas.;Lista de beneficiários:familia_p.gif:m=social&a=familia_lista:Lista de beneficiários cadastrados.;Lista de problemas:problema_p.gif:m=social&a=problema_lista:Lista de problemas relacionados com a execução das ações sociais nas famílias.;Relatórios:relatorio_p.gif:m=social&a=relatorio_lista:Lista de relatórios relacionados com a execução das ações sociais.' WHERE mod_diretorio='social';
ALTER TABLE social_comite_log MODIFY social_comite_log_horas DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_comite_log MODIFY social_comite_log_custo DECIMAL(20,3) DEFAULT 0;
ALTER TABLE social_acao_lista MODIFY social_acao_lista_peso DECIMAL(9,3) DEFAULT NULL;
ALTER TABLE social_acao_log MODIFY social_acao_log_horas DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_acao_log MODIFY social_acao_log_custo DECIMAL(20,3) DEFAULT 0;
ALTER TABLE social_comunidade_log MODIFY social_comunidade_log_horas DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_comunidade_log MODIFY social_comunidade_log_custo DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_familia MODIFY social_familia_latitude DECIMAL(10,6) DEFAULT NULL;
ALTER TABLE social_familia MODIFY social_familia_longitude DECIMAL(10,6) DEFAULT NULL;
ALTER TABLE social_familia MODIFY social_familia_distancia DECIMAL(10,3) DEFAULT NULL;
ALTER TABLE social_familia MODIFY social_familia_comprimento DECIMAL(10,3) DEFAULT 0;
ALTER TABLE social_familia MODIFY social_familia_largura DECIMAL(10,3) DEFAULT 0;
ALTER TABLE social_familia MODIFY social_familia_distancia_agua DECIMAL(10,3) DEFAULT 0;
ALTER TABLE social_familia MODIFY social_familia_area_propriedade DECIMAL(10,3) DEFAULT 0;
ALTER TABLE social_familia MODIFY social_familia_area_producao DECIMAL(10,3) DEFAULT 0;
ALTER TABLE social_familia_irrigacao MODIFY social_familia_irrigacao_area DECIMAL(10,3) DEFAULT 0;
ALTER TABLE social_familia_log MODIFY social_familia_log_horas DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_familia_log MODIFY social_familia_log_custo DECIMAL(20,3) DEFAULT 0;
ALTER TABLE social_familia_producao MODIFY social_familia_producao_quantidade DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_log MODIFY social_log_horas DECIMAL(20,3) DEFAULT NULL;
ALTER TABLE social_log MODIFY social_log_custo DECIMAL(20,3) DEFAULT 0;

