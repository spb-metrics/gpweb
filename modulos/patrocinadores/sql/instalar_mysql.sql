SET FOREIGN_KEY_CHECKS=0;

DELETE FROM preferencia_modulo WHERE preferencia_modulo_modulo='patrocinadores';
INSERT INTO preferencia_modulo (preferencia_modulo_modulo, preferencia_modulo_arquivo, preferencia_modulo_descricao) VALUES 
 ('patrocinadores','index','Lista de patrocinadores');