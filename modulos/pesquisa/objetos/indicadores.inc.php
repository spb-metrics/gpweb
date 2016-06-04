<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class indicadores extends pesquisa {
	var $tabela = 'pratica_indicador';
	var $tabela_apelido = 'pratica_indicador';
	var $tabela_modulo = 'praticas';
	var $tabela_chave = 'pratica_indicador.pratica_indicador_id';
	var $tabela_link = 'index.php?m=praticas&a=indicador_ver&pratica_indicador_id=';
	var $tabela_titulo = 'Indicadores';
	var $tabela_ordem_por = 'pratica_indicador_nome';
	var $buscar_campos = array('pratica_indicador_nome', 'pratica_indicador_requisito_oque', 'pratica_indicador_requisito_onde', 'pratica_indicador_requisito_quando', 'pratica_indicador_requisito_como', 'pratica_indicador_requisito_porque', 'pratica_indicador_requisito_quanto', 'pratica_indicador_requisito_quem');
	var $mostrar_campos = array('pratica_indicador_nome', 'pratica_indicador_requisito_oque', 'pratica_indicador_requisito_onde', 'pratica_indicador_requisito_quando', 'pratica_indicador_requisito_como', 'pratica_indicador_requisito_porque', 'pratica_indicador_requisito_quanto', 'pratica_indicador_requisito_quem');
	var $tabela_unioes = array(array('tabela' => 'pratica_indicador_requisito', 'apelido' => 'pratica_indicador_requisito', 'unir' => 'pratica_indicador.pratica_indicador_requisito = pratica_indicador_requisito.pratica_indicador_requisito_id'));
	var $tabela_agruparPor = 'pratica_indicador.pratica_indicador_id';
	var $funcao='indicador';
	}
?>