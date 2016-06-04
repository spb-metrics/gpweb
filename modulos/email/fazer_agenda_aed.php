<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $config;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);

require_once (BASE_DIR.'/modulos/email/email.class.php');
$obj = new CAgenda();

$msg = '';
$del = getParam($_REQUEST, 'del', 0);
$nao_eh_novo = getParam($_REQUEST, 'agenda_id', null);
$agenda_id = getParam($_REQUEST, 'agenda_id', null);
if (!$obj->join($_REQUEST)) {
	$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
	$Aplic->redirecionar('m=email&a=ver_mes');
	}

if ($obj->agenda_inicio) {
	$data_inicio = new CData($obj->agenda_inicio.getParam($_REQUEST, 'inicio_hora', null));
	$obj->agenda_inicio = $data_inicio->format('%Y-%m-%d %H:%M:%S');
	}
if ($obj->agenda_fim) {
	$data_fim = new CData($obj->agenda_fim.getParam($_REQUEST, 'fim_hora', null));
	$obj->agenda_fim = $data_fim->format('%Y-%m-%d %H:%M:%S');
	}	
	
if (!$del && $data_inicio->compare($data_inicio, $data_fim) >= 0) {
	$Aplic->setMsg('Data de in�cio maior ou igual a data de t�rmino, por favor modifique', UI_MSG_ERRO);
	$Aplic->redirecionar('m=email&a=ver_mes');
	exit;
	}
if (!$obj->agenda_recorrencias) $obj->agenda_nr_recorrencias = 0;
$Aplic->setMsg('Compromisso');
$fazer_redirecionar = true;
require_once $Aplic->getClasseSistema('CampoCustomizados');
if ($del) {
	if (!$obj->podeExcluir($agenda_id)) {
		$Aplic->setMsg('N�o lhe pertence o compromisso', UI_MSG_ERRO);
		$Aplic->redirecionar('m=email&a=ver_mes');
		}
	if (($msg = $obj->excluir())) $Aplic->setMsg($msg, UI_MSG_ERRO);
	else $Aplic->setMsg('exclu�do', UI_MSG_OK, true);
	
	$sql = new BDConsulta;
	$sql->adTabela('agenda_arquivos');
	$sql->adCampo('agenda_arquivo_endereco');
	$sql->adOnde('agenda_arquivo_agenda_id='.$agenda_id);
	$caminhos=$sql->Lista();
	$sql->limpar();
	foreach ($caminhos as $caminho){
		@unlink($base_dir.'/arquivos/agendas/'.$caminho['agenda_arquivo_endereco']);
		}
	@rmdir($base_dir.'/arquivos/agendas/'.$agenda_id);	
	$sql->setExcluir('agenda_arquivos');
	$sql->adOnde('agenda_arquivo_agenda_id='.(int)$agenda_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela agenda_arquivos!'.$bd->stderr(true));
	$sql->limpar();	
	$Aplic->redirecionar('m=email&a=ver_mes');
	} 
else {
	if (!$nao_eh_novo) $obj->agenda_dono = $Aplic->usuario_id;
	if ($_REQUEST['agenda_designado'] > '' && ($conflito = $obj->checarConflito(getParam($_REQUEST, 'agenda_designado', null)))) {
		$Aplic->redirecionar('m=email&a=conflito&conflito='.implode(',',$conflito).'&objeto='.base64_encode(serialize($_REQUEST)));
		exit();
		} 
	else {
		if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
		else {
			$campos_customizados = new CampoCustomizados('agenda', $obj->agenda_id, 'editar');
			$campos_customizados->join($_REQUEST);
			$sql = $campos_customizados->armazenar($obj->agenda_id); 
			$Aplic->setMsg($nao_eh_novo ? 'atualizado' : 'adicionado', UI_MSG_OK, true);
			if (isset($_REQUEST['agenda_designado'])) $obj->atualizarDesignados(explode(',', getParam($_REQUEST, 'agenda_designado', null)));
			if (isset($_REQUEST['email_convidado'])) $obj->notificar(getParam($_REQUEST, 'agenda_designado', null), $nao_eh_novo);
			$obj->adLembrete();

			
			
			//arquivo
			grava_arquivo_agenda($obj->agenda_id);
			}
		}
	}
if ($fazer_redirecionar) $Aplic->redirecionar('m=email&a=ver_mes');
?>