<?php
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/modulos/calendario/jornada.class.php';


$jornada_id = intval(getParam($_REQUEST, 'jornada_id', 0));
$del = intval(getParam($_REQUEST, 'del', 0));


$nao_eh_novo = getParam($_REQUEST, 'jornada_id', null);

//calcular as dura��este

for ($i=1; $i < 8; $i++){
	$inicio=strtotime(getParam($_REQUEST, 'jornada_'.$i.'_inicio', null));
	$fim=strtotime(getParam($_REQUEST, 'jornada_'.$i.'_fim', null));
	$almoco_inicio=strtotime(getParam($_REQUEST, 'jornada_'.$i.'_almoco_inicio', null));
	$almoco_fim=strtotime(getParam($_REQUEST, 'jornada_'.$i.'_almoco_fim', null));
	

	$duracao=0;
	
	if ($almoco_fim <= $inicio) {
		$duracao=($fim-$inicio)/3600;
		}
	else if ($almoco_inicio >= $fim) {
		$duracao=($fim-$inicio)/3600;
		}
	else if (($almoco_inicio <= $inicio) && ($almoco_fim <= $fim)) {
		$duracao=($fim-$almoco_fim)/3600;
		}
	else if (($almoco_fim >= $fim) && ($almoco_inicio <= $fim)) {
		$duracao=($almoco_inicio-$inicio)/3600;
		}
	elseif (($inicio <= $almoco_inicio) && ($almoco_fim <= $fim))	{
		$duracao=(($almoco_inicio-$inicio)+($fim-$almoco_fim))/3600;
		}
	else $duracao=($fim-$inicio)/3600;
	
	$_REQUEST['jornada_'.$i.'_duracao']=$duracao;
	}


$obj = new CJornadaPadrao();
if ($jornada_id) $obj->_mensagem = 'atualizado';
else $obj->_mensagem = 'adicionado';


if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=calendario&a=jornada_novo_lista');
	}
$Aplic->setMsg('Calnd�rio');
if ($del) {
	$obj->load($jornada_id);
	if (($msg = $obj->excluir())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=calendario&a=jornada_novo_lista');
		} 
	else {
		$Aplic->setMsg('exclu�do', UI_MSG_ALERTA, true);
		$Aplic->redirecionar('m=calendario&a=jornada_novo_lista');
		}
	}

if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
else {
	$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
	}
$Aplic->redirecionar('m=calendario&a=jornada&jornada_id='.$obj->jornada_id);


?>