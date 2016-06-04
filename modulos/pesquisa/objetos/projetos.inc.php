<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
	
class projetos extends pesquisa {
	var $tabela = 'projetos';
	var $tabela_apelido = 'projetos';
	var $tabela_modulo = 'projetos';
	var $tabela_chave = 'projeto_id';
	var $tabela_link = 'index.php?m=projetos&a=ver&projeto_id=';
	var $tabela_titulo = 'projetos';
	var $tabela_ordem_por = 'projeto_nome';
	var $buscar_campos = array('projeto_nome', 'projeto_descricao', 'projeto_url', 'projeto_url_externa', 'projeto_codigo', 'projeto_objetivos', 'projeto_observacao', 'projeto_como', 'projeto_localizacao', 'projeto_beneficiario', 'projeto_justificativa', 'projeto_objetivo', 'projeto_objetivo_especifico', 'projeto_escopo', 'projeto_nao_escopo', 'projeto_premissas', 'projeto_restricoes', 'projeto_orcamento', 'projeto_beneficio', 'projeto_produto', 'projeto_requisito');
	var $mostrar_campos = array('projeto_nome', 'projeto_descricao', 'projeto_url', 'projeto_url_externa', 'projeto_codigo', 'projeto_objetivos', 'projeto_observacao', 'projeto_como', 'projeto_localizacao', 'projeto_beneficiario', 'projeto_justificativa', 'projeto_objetivo', 'projeto_objetivo_especifico', 'projeto_escopo', 'projeto_nao_escopo', 'projeto_premissas', 'projeto_restricoes', 'projeto_orcamento', 'projeto_beneficio', 'projeto_produto', 'projeto_requisito');
	var $funcao='projeto';

	}

?>