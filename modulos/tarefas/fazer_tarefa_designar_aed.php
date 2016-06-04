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
			if ($sobrecarregado) $Aplic->setMsg('Alguns '.$config['usuarios'].' n�o fora poss�veis de serem descomissionados d'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_ERRO);
			} 
		elseif (($rm || $del)) {
			if (($msg = $obj->removerDesignado($usuario_id))) $Aplic->setMsg($msg, UI_MSG_ERRO);
			else $Aplic->setMsg(ucfirst($config['usuario']).' descomissionado d'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_OK);
			}
		if (isset($listaDesignados) && !$del == 1) {
			$sobrecarregado = $obj->atualizarDesignados($listaDesignados, $hperc_designado_ar, false, false);
			if ($sobrecarregado) {
				$Aplic->setMsg('O seguinte '.$config['usuario'].' n�o foi designado, para poder previnir sobrecarga de designa��es:', UI_MSG_ERRO);
				$Aplic->setMsg('<br>'.$sobrecarregado, UI_MSG_ERRO, true);
				} 
			else $Aplic->setMsg(ucfirst($config['usuario']).'(s) designado(s) par'.$config['genero_tarefa'].' '.$config['tarefa'], UI_MSG_OK);
			}
		if ($chUTP == 1) {
			$obj->atualizarUsuarioPrioridadeTarefa($usuario_tarefa_prioridade, $usuario_id);
			$Aplic->setMsg('Prioridade espec�fica d'.$config['genero_tarefa'].' '.$config['tarefa'].' para '.$config['genero_usuario'].' '.$config['usuario'].' foi atualizada', UI_MSG_OK, true);
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