<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$del = isset($_REQUEST['del']) ? getParam($_REQUEST, 'del', null) : 0;
$rm = isset($_REQUEST['rm']) ? getParam($_REQUEST, 'rm', null) : 0;
$listaDesignados = getParam($_REQUEST, 'listaDesignados', null);
$htarefas = getParam($_REQUEST, 'htarefas', null);
$armazenar = getParam($_REQUEST, 'store', 0);
$chUTP = getParam($_REQUEST, 'chUTP', 0);
$percentagem_designar = getParam($_REQUEST, 'percentagem_designar');
$usuario_tarefa_prioridade = getParam($_REQUEST, 'usuario_tarefa_prioridade');
$usuario_id = getParam($_REQUEST, 'usuario_id', null);
$hperc_designado_ar = array();
if (isset($listaDesignados)) {
	$tarr = explode(',', $listaDesignados);
	foreach ($tarr as $uid) {
		if (intval($uid) > 0) $hperc_designado_ar[$uid] = $percentagem_designar;
		}
	}
$htarefas_ar = array();
if (isset($htarefas)) {
	$tarr = explode(',', $htarefas);
	foreach ($tarr as $tid) {
		if (intval($tid) > 0) $htarefas_ar[] = $tid;
		}
	}
$sizeof = count($htarefas_ar);
for ($i = 0; $i <= $sizeof; $i++) {
	$_REQUEST['tarefa_id'] = $htarefas_ar[$i];
	if ($_REQUEST['tarefa_id'] > 0) {
		$obj = new CTarefa();
		if (!$obj->join($_REQUEST)) {
			$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
			$Aplic->redirecionar('m=tarefas&a=ver&tarefa_id='.getParam($_REQUEST, 'tarefa_id', null));
			}
		if ($rm && $del) {
			$sobrecarregado = $obj->atualizarDesignados($listaDesignados, $hperc_designado_ar, true, true);
			if ($sobrecarregado) $Aplic->setMsg('Alguns '.$config['usuarios'].' não fora possíveis de serem descomissionados d'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_ERRO);
			} 
		elseif (($rm || $del)) {
			if (($msg = $obj->removerDesignado($usuario_id))) $Aplic->setMsg($msg, UI_MSG_ERRO);
			else $Aplic->setMsg(ucfirst($config['usuario']).' descomissionado d'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_OK);
			}
		if (isset($listaDesignados) && !$del == 1) {
			$sobrecarregado = $obj->atualizarDesignados($listaDesignados, $hperc_designado_ar, false, false);
			if ($sobrecarregado) {
				$Aplic->setMsg('O seguinte '.$config['usuario'].' não foi designado, para poder previnir sobrecarga de designações:', UI_MSG_ERRO);
				$Aplic->setMsg('<br>'.$sobrecarregado, UI_MSG_ERRO, true);
				} 
			else $Aplic->setMsg(ucfirst($config['usuario']).'(s) designado(s) par'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_OK);
			}
		if ($chUTP == 1) {
			$obj->atualizarUsuarioPrioridadeTarefa($usuario_tarefa_prioridade, $usuario_id);
			$Aplic->setMsg('Prioridade específica d'.$config['genero_tarefa'].' '.$config['tarefa'].' para '.$config['genero_usuario'].' '.$config['usuario'].' foi atualizada', UI_MSG_OK, true);
			}
		if ($armazenar == 1) {
			if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO, true);
			else $Aplic->setMsg('Tarefa(s) atualizada(s)', UI_MSG_OK, true);
			}
		}
	}
if ($rm && $del) $Aplic->setMsg(ucfirst($config['usuario']).'(s) descomissionado(s) d'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_OK);
$Aplic->redirecionar('m=tarefas&a=ver&tarefa_id='.getParam($_REQUEST, 'tarefa_id', null));
?>