SET FOREIGN_KEY_CHECKS=0;
UPDATE modulos SET mod_versao=5 WHERE mod_diretorio='social';

ALTER TABLE social_log DROP KEY social_log_social;
ALTER TABLE social_log DROP FOREIGN KEY social_log_fk1;
ALTER TABLE social_log ADD CONSTRAINT social_log_fk1 FOREIGN KEY (social_log_social) REFERENCES social (social_id) ON DELETE CASCADE ON UPDATE CASCADE;

