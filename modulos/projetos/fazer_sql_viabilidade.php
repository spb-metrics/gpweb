<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\fazer_sql.php		

Rotina chamada quando se exclui uma ação, prática ou indicador																																							
																																												
********************************************************************************************/
require_once (BASE_DIR.'/modulos/projetos/viabilidade.class.php');

$sql = new BDConsulta;

$_REQUEST['viabilidade_ativo']=(isset($_REQUEST['viabilidade_ativo']) ? 1 : 0);

$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$projeto_viabilidade_id = getParam($_REQUEST, 'projeto_viabilidade_id', null);

$obj = new CViabilidade();
if ($projeto_viabilidade_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=viabilidade_lista');
	}
$Aplic->setMsg('Estudo de viabilidade');
if ($excluir) {
	$obj->load($projeto_viabilidade_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id);
		} 
	else {
		$Aplic->setMsg('excluído', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=viabilidade_lista');
		}
	}
	
$codigo=$obj->getCodigo();
if ($codigo) $obj->projeto_viabilidade_codigo=$codigo;

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($projeto_viabilidade_id ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
$obj->setSequencial();	

$Aplic->redirecionar('m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$obj->projeto_viabilidade_id);

?>