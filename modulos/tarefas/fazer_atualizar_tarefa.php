<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "LICENCA.txt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

require_once BASE_DIR.'/modulos/tarefas/funcoes.php';

$tarefa_id = getParam($_REQUEST, 'tarefa_id', 0);

$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->adCampo('tarefa_projeto');
$sql->adOnde('tarefa_id ='.$tarefa_id);
$projeto_id=$sql->Resultado();
$sql->limpar();

function limparTexto($texto) {
	$texto = utf8_decode($texto);
	$trade = array('�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'a', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'A', '�' => 'e', '�' => 'e', '�' => 'e', '�' => 'e', '�' => 'E', '�' => 'E', '�' => 'E', '�' => 'E', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'i', '�' => 'I', '�' => 'I', '�' => 'I', '�' => 'I', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'o', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'O', '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'u', '�' => 'U', '�' => 'U', '�' => 'U', '�' => 'U', '�' => 'N', '�' => 'n');
	$texto = strtr($texto, $trade);
	$texto = utf8_encode($texto);
	return $texto;
	}
$notificar_responsavel = getParam($_REQUEST, 'tarefa_log_notificar_responsavel', 0);

$del = getParam($_REQUEST, 'del', 0);
$nao_eh_novo = getParam($_REQUEST, 'tarefa_log_id', null);
if ($del) {
	if (!$Aplic->checarModulo('tarefa_log', 'excluir')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif ($nao_eh_novo) {
	if (!$Aplic->checarModulo('tarefa_log', 'editar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
	} 
elseif (!$Aplic->checarModulo('tarefa_log', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CTarefaLog();


if ($obj->tarefa_log_data) {
	$data = new CData($obj->tarefa_log_data.date('Hi'));
	$obj->tarefa_log_data = $data->format(FMT_TIMESTAMP_MYSQL);
	}
$dot = strpos($obj->tarefa_log_horas, ':');
if ($dot > 0) {
	$log_duracao_minutos = sprintf('%.3f', substr($obj->tarefa_log_horas, $dot + 1) / 60.0);
	$obj->tarefa_log_horas = floor($obj->tarefa_log_horas) + $log_duracao_minutos;
	}
$obj->tarefa_log_horas = round($obj->tarefa_log_horas, 3);

if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=tarefas');
	}

$Aplic->setMsg('Registro d'.$config['genero_tarefa'].' '.$config['tarefa']);
if ($del) {
	if ($msg = $obj->excluir()) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg('Registro excluido', UI_MSG_ALERTA);
	//atualizar_projeto($projeto_id, $tarefa_id);
	atualizar_percentagem($projeto_id);
	$Aplic->redirecionar('m=tarefas&a=ver&tab=0&tarefa_id='.$tarefa_id);
	} 
else {
	$obj->tarefa_log_nd = limparTexto($obj->tarefa_log_nd);
	$obj->tarefa_log_custo = limparTexto($obj->tarefa_log_custo);
	if (($msg = $obj->armazenar())) {
		$Aplic->setMsg($msg, UI_MSG_ERRO);
		$Aplic->redirecionar('m=tarefas&a=ver&tab=0&tarefa_id='.$tarefa_id);
		} 
	else $Aplic->setMsg($_REQUEST['tarefa_log_id'] ? 'atualizado' : 'inserido', UI_MSG_OK, true);
	}

$tarefa = new CTarefa();
$tarefa->load($obj->tarefa_log_tarefa);
$podeEditarTarefa = permiteEditar($tarefa->tarefa_acesso, $tarefa->tarefa_projeto, $tarefa->tarefa_acesso);
if ($podeEditarTarefa) {
	$tarefa->htmlDecodificar();
	$tarefa->check();
	$tarefa_fim = new CData($tarefa->tarefa_fim);
	$tarefa->tarefa_percentagem = getParam($_REQUEST, 'tarefa_percentagem', 0);
	
	if (getParam($_REQUEST, 'tarefa_fim', '')) {
		$tarefa->tarefa_fim = getParam($_REQUEST, 'tarefa_fim', '');
		}
	
	if (($msg = $tarefa->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO, true);
	$novo_fim_tarefa = new CData($tarefa->tarefa_fim);
	if (!$Aplic->profissional && $novo_fim_tarefa->dataDiferenca($tarefa_fim)) $tarefa->adLembrete();
	}
	
if ($notificar_responsavel) {
	if ($msg = $tarefa->notificarResponsavel()) $Aplic->setMsg($msg, UI_MSG_ERRO);
	}
$email_designados = getParam($_REQUEST, 'email_designados', null);
$email_tarefa_contatos = getParam($_REQUEST, 'email_tarefa_contatos', null);
$email_projeto_contatos = getParam($_REQUEST, 'email_projeto_contatos', null);
$email_outro = getParam($_REQUEST, 'email_outro', '');
$email_extras = getParam($_REQUEST, 'email_extras', null);

if ($tarefa->email_log($obj, $email_designados, $email_tarefa_contatos, $email_projeto_contatos, $email_outro, $email_extras)) $obj->armazenar(); 

atualizar_percentagem($projeto_id);



$Aplic->redirecionar('m=tarefas&a=ver&tarefa_id='.$obj->tarefa_log_tarefa.'&tab=0&tarefalog='.$obj->tarefa_log_id);


?>