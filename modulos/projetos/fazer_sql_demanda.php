<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
		$Aplic->setMsg('exclu�da', UI_MSG_ALERTA, true);
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