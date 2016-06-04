SET FOREIGN_KEY_CHECKS=0;

UPDATE versao SET versao_codigo='8.4.13'; 
UPDATE versao SET ultima_atualizacao_bd='2014-04-03'; 
UPDATE versao SET ultima_atualizacao_codigo='2014-04-03'; 
UPDATE versao SET versao_bd=216;

DROP FUNCTION IF EXISTS extrair;
DROP FUNCTION IF EXISTS tempo_unix;
DROP FUNCTION IF EXISTS em_dias;
DROP FUNCTION IF EXISTS dia;
DROP FUNCTION IF EXISTS semana_ano;
DROP FUNCTION IF EXISTS ano;
DROP FUNCTION IF EXISTS mes;
DROP FUNCTION IF EXISTS dia_semana;
DROP FUNCTION IF EXISTS adiciona_data;
DROP FUNCTION IF EXISTS diferenca_data;
DROP FUNCTION IF EXISTS diferenca_tempo;
DROP FUNCTION IF EXISTS tamanho_caractere;
DROP FUNCTION IF EXISTS tempo_em_segundos;
DROP FUNCTION IF EXISTS formatar_data;
DROP FUNCTION IF EXISTS concatenar_dois;
DROP FUNCTION IF EXISTS concatenar_tres;
DROP FUNCTION IF EXISTS concatenar_quatro;
DROP FUNCTION IF EXISTS concatenar_cinco;
DROP FUNCTION IF EXISTS strmes;