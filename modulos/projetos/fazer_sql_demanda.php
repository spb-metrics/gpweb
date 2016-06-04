<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');

$sql = new BDConsulta;

$_REQUEST['demanda_ativa']=(isset($_REQUEST['demanda_ativa']) ? 1 : 0);
$_REQUEST['demanda_supervisor_ativo']=(isset($_REQUEST['demanda_supervisor_ativo']) ? 1 : 0);
$_REQUEST['demanda_autoridade_ativo']=(isset($_REQUEST['demanda_autoridade_ativo']) ? 1 : 0);
$_REQUEST['demanda_cliente_ativo']=(isset($_REQUEST['demanda_cliente_ativo']) ? 1 : 0);


$excluir = intval(getParam($_REQUEST, 'excluir', 0));
$demanda_id = getParam($_REQUEST, 'demanda_id', null);
$comentario=getParam($_REQUEST, 'email_comentario', '');

$obj = new CDemanda();
if ($demanda_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';

unset($obj->incluir_subordinadas);
unset($obj->demandas_subordinadas);

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=demanda_lista');
	}
$Aplic->setMsg('Demanda');
if ($excluir) {
	$obj->load($demanda_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=demanda_ver&demanda_id='.$demanda_id);
		} 
	else {
		$Aplic->setMsg('excluída', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=projetos&a=demanda_lista');
		}
	}

$codigo=$obj->getCodigo();
if ($codigo) $obj->demanda_codigo=$codigo;


if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	if (getParam($_REQUEST, 'email_responsavel', 0)) $obj->notificarResponsavel($comentario, $demanda_id);
	if (getParam($_REQUEST, 'email_designados', 0)) $obj->notificarDesignados($comentario, $demanda_id);
	if (getParam($_REQUEST, 'email_contatos', 0)) $obj->notificarContatos($comentario, $demanda_id);
	$Aplic->setMsg($demanda_id ? 'atualizada' : 'adicionada', UI_MSG_OK, true);
	}
	
$obj->setSequencial();	

$Aplic->redirecionar('m=projetos&a=demanda_ver&demanda_id='.$obj->demanda_id);

?>