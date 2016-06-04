<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

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