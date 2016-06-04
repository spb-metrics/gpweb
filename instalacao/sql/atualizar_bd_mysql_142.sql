SET FOREIGN_KEY_CHECKS=0;
UPDATE versao SET versao_codigo='8.2.0'; 
UPDATE versao SET ultima_atualizacao_bd='2013-01-20'; 
UPDATE versao SET ultima_atualizacao_codigo='2013-01-20'; 
UPDATE versao SET versao_bd=142;

UPDATE msg_usuario SET pasta_id=null WHERE status < 2;

DROP VIEW v_lista_id_msg_arquivadas;
DROP VIEW v_lista_id_msg_enviadas;
DROP VIEW v_lista_id_msg_pendentes;
DROP VIEW v_lista_id_msg_recebidas;


