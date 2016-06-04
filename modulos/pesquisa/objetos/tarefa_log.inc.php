<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class tarefa_log extends pesquisa {
	var $tabela = 'tarefa_log';
	var $tabela_modulo = 'tarefas';
	var $tabela_chave = 'tarefa_log_tarefa';
	var $tabela_chave2 = 'tarefa_log_id';
	var $tabela_extra = 'tarefa_log_tarefa != 0';
	var $tabela_link = 'index.php?m=tarefas&a=ver&tarefa_id=';
	var $tabela_link2 = '&tab=0&tarefa_log_id=';
	var $tabela_titulo = 'Registros das tarefa';
	var $tabela_ordem_por = 'tarefa_log_nome';
	var $buscar_campos = array('tarefa_log_nome', 'tarefa_log_descricao', 'tarefa_log_tarefa');
	var $mostrar_campos = array('tarefa_log_nome', 'tarefa_log_descricao', 'tarefa_log_tarefa');
	var $funcao='registro';
	}
?>