<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

function getDespachoLinks($inicioPeriodo, $fimPeriodo, $links) {
	global $evento_filtro,$Aplic, $config;
	$despachos = CAgenda::getDespachoParaPeriodo($inicioPeriodo, $fimPeriodo);
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	foreach ($despachos as $linha) {
		$inicio = new CData($linha['data_limite']);
		$fim = new CData($linha['data_limite']);
		$data = $inicio;
		$sql = new BDConsulta;
		$sql->adTabela('msg_usuario');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS despacho_dono');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = msg_usuario.de_id');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adOnde('msg_usuario.msg_usuario_id = '.$linha['msg_usuario_id']);
		$nome = $sql->Resultado();
		$sql->limpar();
		$cwd = explode(',', $GLOBALS['config']['cal_dias_uteis']);
		for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
			$texto='<tr><td colspan=2>'.imagem('icones/msg10010.gif').' Despacho de '.$nome.'</td></tr>';
			$link['texto'] = '<tr><td colspan=2>'.link_despacho($linha['msg_usuario_id']).'</td></tr>';
			$link['texto_mini'] =$texto;
			$links[$data->format(FMT_TIMESTAMP_DATA)][] = $link;
			$data = $data->getNextDay();
			}
		}
	return $links;
	}


function getMsg_TarefaLinks($inicioPeriodo, $fimPeriodo, $links) {
	global $evento_filtro,$Aplic, $config;
	$tarefas = CAgenda::getMsg_TarefaParaPeriodo($inicioPeriodo, $fimPeriodo);
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	foreach ($tarefas as $linha) {
		$inicio = new CData($linha['tarefa_data']);
		$fim = new CData($linha['tarefa_data']);
		$data = $inicio;
		$sql = new BDConsulta;
		$sql->adTabela('msg_usuario');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS despacho_dono');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = msg_usuario.de_id');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adOnde('msg_usuario.msg_usuario_id = '.$linha['msg_usuario_id']);
		$nome = $sql->Resultado();
		$sql->limpar();
		$cwd = explode(',', $GLOBALS['config']['cal_dias_uteis']);
		for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
			$texto='<tr><td colspan=2>'.imagem('icones/task_p.png').' Msg do tipo atividade de '.$nome.'</td></tr>';
			$link['texto'] = '<tr><td colspan=2>'.link_msg_tarefa($linha['msg_usuario_id']).'</td></tr>';
			$link['texto_mini'] =$texto;
			$links[$data->format(FMT_TIMESTAMP_DATA)][] = $link;
			$data = $data->getNextDay();
			}
		}
	return $links;
	}


function getDespachoModeloLinks($inicioPeriodo, $fimPeriodo, $links) {
	global $evento_filtro,$Aplic, $config;
	$despachos = CAgenda::getDespachoModeloParaPeriodo($inicioPeriodo, $fimPeriodo);
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	foreach ($despachos as $linha) {
		$inicio = new CData($linha['data_limite']);
		$fim = new CData($linha['data_limite']);
		$data = $inicio;
		$sql = new BDConsulta;
		$sql->adTabela('modelo_usuario');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS despacho_dono');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = modelo_usuario.de_id');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adOnde('modelo_usuario.modelo_usuario_id = '.$linha['modelo_usuario_id']);
		$nome = $sql->Resultado();
		$sql->limpar();
		$cwd = explode(',', $GLOBALS['config']['cal_dias_uteis']);
		for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
			$texto='<tr><td colspan=2>'.imagem('icones/msg10010.gif').' Despacho de '.$nome.'</td></tr>';
			$link['texto'] = '<tr><td colspan=2>'.link_modelodespacho($linha['modelo_usuario_id']).'</td></tr>';
			$link['texto_mini'] =$texto;
			$links[$data->format(FMT_TIMESTAMP_DATA)][] = $link;
			$data = $data->getNextDay();
			}
		}
	return $links;
	}

?>