<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/sistema/calendario/calendario.class.php');

$sql = new BDConsulta;

$_REQUEST['calendario_ativo']=(isset($_REQUEST['calendario_ativo']) ? 1 : 0);

$calendario_id = getParam($_REQUEST, 'calendario_id', null);

$del = intval(getParam($_REQUEST, 'del', 0));

$obj = new CCalendario();
if ($calendario_id) $obj->_mensagem = 'atualizada';
else $obj->_mensagem = 'adicionada';

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=sistema&u=calendario&a=calendario_lista');
	}
$Aplic->setMsg('Agenda Coletiva');
if ($del) {
	$obj->load($calendario_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id);
		} 
	else {
		$Aplic->setMsg('excluída', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=sistema&u=calendario&a=calendario_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$obj->notificar($_REQUEST);
	$Aplic->setMsg($calendario_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
	
	
$Aplic->redirecionar('m=sistema&u=calendario&a=calendario_ver&calendario_id='.$obj->calendario_id);

?>