<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class template extends pesquisa {
	var $tabela = 'template';
	var $tabela_apelido = 'template';
	var $tabela_modulo = 'template';
	var $tabela_chave = 'template.template_id';
	var $tabela_link = 'index.php?m=template&a=template_ver&template_id=';
	var $tabela_titulo = 'Modelos';
	var $tabela_ordem_por = 'template_nome';
	var $buscar_campos = array('template_nome', 'template_descricao', 'template_tipo');
	var $mostrar_campos = array('template_nome', 'template_descricao', 'template_tipo');
	var $tabela_agruparPor = 'template.template_id';
	var $funcao='template';
	}
?>