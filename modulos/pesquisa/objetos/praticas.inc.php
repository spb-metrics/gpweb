<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class praticas extends pesquisa {
	var $tabela = 'praticas';
	var $tabela_apelido = 'praticas';
	var $tabela_modulo = 'praticas';
	var $tabela_chave = 'praticas.pratica_id';
	var $tabela_link = 'index.php?m=praticas&a=pratica_ver&pratica_id=';
	var $tabela_titulo = 'praticas';
	var $tabela_ordem_por = 'pratica_nome';
	var $buscar_campos = array('pratica_nome', 'pratica_oque', 'pratica_onde', 'pratica_quando', 'pratica_como', 'pratica_porque', 'pratica_quem', 'pratica_quanto', 'pratica_justificativa_controlada', 'pratica_justificativa_proativa', 'pratica_justificativa_abrangencia', 'pratica_justificativa_continuada', 'pratica_justificativa_coerente', 'pratica_justificativa_interrelacionada', 'pratica_justificativa_cooperacao');
	var $mostrar_campos = array('pratica_nome', 'pratica_oque', 'pratica_onde', 'pratica_quando', 'pratica_como', 'pratica_porque', 'pratica_quem', 'pratica_quanto', 'pratica_justificativa_controlada', 'pratica_justificativa_proativa', 'pratica_justificativa_abrangencia', 'pratica_justificativa_continuada', 'pratica_justificativa_coerente', 'pratica_justificativa_interrelacionada', 'pratica_justificativa_cooperacao');
	var $tabela_unioes = array(array('tabela' => 'pratica_requisito', 'apelido' => 'pratica_requisito', 'unir' => 'praticas.pratica_id = pratica_requisito.pratica_id'));
	var $tabela_agruparPor = 'praticas.pratica_id';
	var $funcao='pratica';
	}
?>