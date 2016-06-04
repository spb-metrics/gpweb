<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

class demandas extends pesquisa {
	var $tabela = 'demandas';
	var $tabela_apelido = 'demandas';
	var $tabela_modulo = 'projetos';
	var $tabela_chave = 'demandas.demanda_id';
	var $tabela_link = 'index.php?m=projetos&a=demanda_ver&demanda_id=';
	var $tabela_titulo = 'Demandas';
	var $tabela_ordem_por = 'demanda_nome';
	var $buscar_campos = array('demanda_nome', 'demanda_identificacao', 'demanda_justificativa', 'demanda_resultados', 'demanda_alinhamento', 'demanda_fonte_recurso', 'demanda_observacao', 'demanda_prazo', 'demanda_custos', 'demanda_codigo', 'demanda_cliente_obs', 'demanda_supervisor_obs', 'demanda_autoridade_obs', 'demanda_descricao', 'demanda_objetivos', 'demanda_como', 'demanda_localizacao', 'demanda_beneficiario', 'demanda_objetivo', 'demanda_objetivo_especifico', 'demanda_escopo', 'demanda_nao_escopo', 'demanda_premissas', 'demanda_restricoes', 'demanda_orcamento', 'demanda_beneficio', 'demanda_produto', 'demanda_requisito');
	var $mostrar_campos = array('demanda_nome', 'demanda_identificacao', 'demanda_justificativa', 'demanda_resultados', 'demanda_alinhamento', 'demanda_fonte_recurso', 'demanda_observacao', 'demanda_prazo', 'demanda_custos', 'demanda_codigo', 'demanda_cliente_obs', 'demanda_supervisor_obs', 'demanda_autoridade_obs', 'demanda_descricao', 'demanda_objetivos', 'demanda_como', 'demanda_localizacao', 'demanda_beneficiario', 'demanda_objetivo', 'demanda_objetivo_especifico', 'demanda_escopo', 'demanda_nao_escopo', 'demanda_premissas', 'demanda_restricoes', 'demanda_orcamento', 'demanda_beneficio', 'demanda_produto', 'demanda_requisito');
	var $tabela_agruparPor = 'demandas.demanda_id';
	var $funcao='demanda';
	}
?>