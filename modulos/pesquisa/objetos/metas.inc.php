<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');


class metas extends pesquisa {
	var $tabela = 'metas';
	var $tabela_apelido = 'metas';
	var $tabela_modulo = 'praticas';
	var $tabela_chave = 'metas.pg_meta_id';
	var $tabela_link = 'index.php?m=praticas&a=meta_ver&pg_meta_id=';
	var $tabela_titulo ='metas';
	var $tabela_ordem_por = 'pg_meta_nome';
	var $buscar_campos = array('pg_meta_nome', 'pg_meta_oque','pg_meta_descricao','pg_meta_onde','pg_meta_quando','pg_meta_como','pg_meta_porque','pg_meta_quanto','pg_meta_quem','pg_meta_controle','pg_meta_melhorias','pg_meta_metodo_aprendizado','pg_meta_desde_quando');
	var $mostrar_campos = array('pg_meta_nome', 'pg_meta_oque','pg_meta_descricao','pg_meta_onde','pg_meta_quando','pg_meta_como','pg_meta_porque','pg_meta_quanto','pg_meta_quem','pg_meta_controle','pg_meta_melhorias','pg_meta_metodo_aprendizado','pg_meta_desde_quando');
	var $funcao='meta';

	}
?>