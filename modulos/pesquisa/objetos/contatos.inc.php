<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class contatos extends pesquisa {
	var $tabela = 'contatos';
	var $tabela_modulo = 'contatos';
	var $tabela_chave = 'contato_id';
	var $tabela_link = 'index.php?m=contatos&a=ver&contato_id=';
	var $tabela_titulo = 'Contatos';
	var $tabela_ordem_por = 'contato_nomeguerra,contato_posto';
	var $buscar_campos = array('contato_posto', 'contato_nomeguerra', 'contato_nomecompleto', 'contato_arma', 'contato_cia', 'contato_tipo', 'contato_email', 'contato_email2', 'contato_endereco1', 'contato_endereco2', 'contato_cidade', 'contato_estado', 'contato_cep', 'contato_pais', 'contato_notas');
	var $mostrar_campos = array('contato_posto', 'contato_nomeguerra', 'contato_nomecompleto', 'contato_arma', 'contato_cia', 'contato_tipo', 'contato_email', 'contato_email2', 'contato_endereco1', 'contato_endereco2', 'contato_cidade', 'contato_estado', 'contato_cep', 'contato_pais', 'contato_notas');
  var $funcao='contato';
	}
?>