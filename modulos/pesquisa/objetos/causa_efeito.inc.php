<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class causa_efeito extends pesquisa {
	var $tabela = 'causa_efeito';
	var $tabela_apelido = 'causa_efeito';
	var $tabela_modulo = 'causa_efeito';
	var $tabela_chave = 'causa_efeito.causa_efeito_id';
	var $tabela_link = 'index.php?m=causa_efeito&a=causa_efeito_ver&causa_efeito_id=';
	var $tabela_titulo = 'Diagramas de Causa-Efeito';
	var $tabela_ordem_por = 'causa_efeito_nome';
	var $buscar_campos = array('causa_efeito_nome', 'causa_efeito_descricao');
	var $mostrar_campos = array('causa_efeito_nome', 'causa_efeito_descricao');
	var $tabela_agruparPor = 'causa_efeito.causa_efeito_id';
	var $funcao='causa_efeito';
	}
?>