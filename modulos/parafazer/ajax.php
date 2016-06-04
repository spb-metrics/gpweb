<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

set_error_handler('meuControladorErro');
set_exception_handler('meuControladorExcessao');

require_once('init.php');
require_once('lang/class.default.php');
require_once('lang/'.$config['lang'].'.php');

session_start();
$usuario=$_SESSION["usuario"];

$sql = new BDConsulta;

if(isset($_REQUEST['carregarListas'])){
	$sql->adTabela('parafazer_listas');
	$sql->adCampo('id, nome, usuario_id');
	$sql->adOnde('usuario_id= '.(int)$usuario);
	$sql->adOrdem('id ASC');
	$resultado = $sql->Lista();
	$sql->limpar();
	
	$t = array();
	$t['total'] = 0;
	foreach ($resultado as $r) {
		$t['total']++;
		$t['list'][] = array('id'=> $r['id'], 'name' => htmlarray(utf8_encode($r['nome'])));
		}
	echo json_encode($t); 
	exit;
	}
elseif(isset($_REQUEST['carregarTarefas'])){
	stop_gpc($_REQUEST);
	$listId = (int)_get('list');
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('DISTINCT id, d, lista_id, compl, titulo, nota, prio, ow, parafazer_chave, datafinal, datafinal IS NULL AS ddn');
	$sql->adOnde('lista_id='. $listId);
	if(_get('compl') == 0) $sql->adOnde('compl=0'); 
	$inner = '';
	$tag = trim(_get('t'));
	if($tag != '') {
		$palavra_chave_id = get_palavra_chave_id($tag, $listId);
		$sql->internoUnir('parafazer_chave_tarefa', 'parafazer_chave_tarefa', 'id=parafazer_chave_tarefa.tarefa_id');
		$sql->adOnde('palavra_chave_id='.$palavra_chave_id); 
		}
	$s = trim(_get('s'));
	if($s != '') $sql->adOnde('(titulo LIKE '. $sql->quoteParecido("%%%s%%",previnirXSS(utf8_decode($s))).' OR nota LIKE '.$sql->quoteParecido("%%%s%%",previnirXSS(utf8_decode($s))).')');
	$sort = (int)_get('sort');
	$sql->adOrdem('compl ASC');
	if($sort == 1) $sql->adOrdem('prio DESC, ddn ASC, datafinal ASC, ow ASC'); 
	elseif($sort == 2) $sql->adOrdem('ddn ASC, datafinal ASC, prio DESC, ow ASC'); 
	else $sql->adOrdem('ow ASC');
	$tz = (int)_get('tz');
	if((isset($config['autotz']) && $config['autotz']==0) || $tz<-720 || $tz>720 || $tz%30!=0) $tz = null;
	$t = array();
	$t['total'] = 0;
	$t['list'] = array();
	$resultado = $sql->Lista();
	$sql->limpar();
	foreach ($resultado as $r) {
		$t['total']++;
		$t['list'][] = prepararLinhaTarefa($r, $tz);
		}
	echo json_encode($t); 
	exit;
	}
elseif(isset($_REQUEST['novaTarefa'])){
	stop_gpc($_REQUEST);
	$t = array();
	$t['total'] = 0;
	$listId = (int)_post('list');
	$titulo = trim(_post('titulo'));
	$prio = 0;
	$parafazer_chave = '';
	if(!isset($config['smartsyntax']) || $config['smartsyntax'] != 0){
		$a = formatar_inteligente($titulo);
		if($a === false) {
			echo json_encode($t);
			exit;
			}
		$titulo = $a['titulo'];
		$prio = $a['prio'];
		$parafazer_chave = $a['parafazer_chave'];
		}
	if($titulo == '') {
		echo json_encode($t);
		exit;
		}
	if(isset($config['autotag']) && $config['autotag']) $parafazer_chave .= ','._post('tag');
	$tz = (int)_post('tz');
	if( (isset($config['autotz']) && $config['autotz']==0) || $tz<-720 || $tz>720 || $tz%30!=0 ) $d = strftime("%Y-%m-%d %H:%M:%S");
	else $d = gmdate("Y-m-d H:i:s", time()+$tz*60);
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('MAX(ow)');
	$sql->adOnde('lista_id= '.$listId);
	$sql->adOnde('compl=0 ');
	$resultado = $sql->Resultado();
	$sql->limpar();
	$ow = 1 + (int)$resultado;
	$sql->adTabela('parafazer_tarefa');
	$sql->adInserir('lista_id', $listId);
	$sql->adInserir('titulo', previnirXSS(utf8_decode($titulo)));
	$sql->adInserir('d', $d);
	$sql->adInserir('ow', $ow);
	$sql->adInserir('prio', $prio);
	if (!$sql->exec()) die('Não foi possivel inserir na tabela parafazer_tarefa!'.$bd->stderr(true));
	$id = $bd->Insert_ID('parafazer_tarefa','id');
	$sql->limpar();
	if($parafazer_chave){
		$palavra_chave_ids = preparar_parafazer_chave($parafazer_chave, $listId);
		if($palavra_chave_ids) {
			atualizar_chave_tarefa_parafazer($id, $palavra_chave_ids);
			$sql->adTabela('parafazer_tarefa');
			$sql->adAtualizar('parafazer_chave', previnirXSS(utf8_decode($parafazer_chave)));
			$sql->adOnde('id= '.$id);
			if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('id, d, lista_id, compl, titulo, nota, prio, ow, parafazer_chave, datafinal');
	$sql->adOnde('id='. $id);
	$r = $sql->Linha();
	$sql->limpar();
	$t['list'][] = prepararLinhaTarefa($r);
	$t['total'] = 1;
	echo json_encode($t); 
	exit;
	}
elseif(isset($_REQUEST['novaTarefaCompleta'])){
	stop_gpc($_REQUEST);
	$listId = (int)_post('list');
	$titulo = trim(_post('titulo'));
	$nota = str_replace("\r\n", "\n", trim(_post('nota')));
	$prio = (int)_post('prio');
	if($prio < -1) $prio = -1;
	elseif($prio > 2) $prio = 2;
	$datafinal = formatar_datafinal(trim(_post('datafinal')));
	$t = array();
	$t['total'] = 0;
	if($titulo == '') {
		echo json_encode($t);
		exit;
		}
	$parafazer_chave = trim(_post('parafazer_chave'));
	if(isset($config['autotag']) && $config['autotag']) $parafazer_chave .= ','._post('tag');
	$tz = (int)_post('tz');
	$d = date("Y-m-d H:i:s");
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('MAX(ow)');
	$sql->adOnde('lista_id= '.$listId);
	$sql->adOnde('compl=0 ');
	$resultado = $sql->Resultado();
	$sql->limpar();
	$ow = 1 + $resultado;
	$sql->adTabela('parafazer_tarefa');
	$sql->adInserir('lista_id', $listId);
	$sql->adInserir('titulo', previnirXSS(utf8_decode($titulo)));
	$sql->adInserir('d', $d);
	$sql->adInserir('ow', $ow);
	$sql->adInserir('prio', $prio);
	$sql->adInserir('nota', previnirXSS(utf8_decode($nota)));
	if (!is_null($datafinal)) $sql->adInserir('datafinal', $datafinal);
	if (!$sql->exec()) die('Não foi possivel inserir na tabela parafazer_tarefa!'.$bd->stderr(true));
	$id = $bd->Insert_ID('parafazer_tarefa','id');
	$sql->limpar();
	if($parafazer_chave){
		$palavra_chave_ids = preparar_parafazer_chave($parafazer_chave, $listId);
		if($palavra_chave_ids) {
			atualizar_chave_tarefa_parafazer($id, $palavra_chave_ids);
			$sql->adTabela('parafazer_tarefa');
			$sql->adAtualizar('parafazer_chave', previnirXSS(utf8_decode($parafazer_chave)));
			$sql->adOnde('id= '.$id);
			if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
			$sql->limpar();
			}
		}
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('id, d, lista_id, compl, titulo, nota, prio, ow, parafazer_chave, datafinal');
	$sql->adOnde('id='. $id);
	$r = $sql->Linha();
	$sql->limpar();
	$t['list'][] = prepararLinhaTarefa($r);
	$t['total'] = 1;
	echo json_encode($t); 
	exit;
	}
elseif(isset($_REQUEST['excluirTarefa'])){
	$id = (int)$_REQUEST['excluirTarefa'];
	$parafazer_chave = get_tarefa_parafazer_chave($id);
	if($parafazer_chave) {
		$s = implode(',', $parafazer_chave);
		$sql->setExcluir('parafazer_chave_tarefa');
		$sql->adOnde('tarefa_id ='.$id);
		if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_chave_tarefa!'.$bd->stderr(true));
		$sql->limpar();	
		$sql->adTabela('parafazer_chave');
		$sql->adAtualizar('cont_palavra_chave', 'cont_palavra_chave-1');
		$sql->adOnde('id IN ('.$s.')');
		if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
		$sql->limpar();
		$sql->setExcluir('parafazer_chave');
		$sql->adOnde('cont_palavra_chave < 1');
		if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_chave!'.$bd->stderr(true));
		$sql->limpar();	
		}
	$sql->setExcluir('parafazer_tarefa');
	$sql->adOnde('id ='.$id);
	if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_tarefa!'.$bd->stderr(true));
	$sql->limpar();	
	$afetados = $bd->Affected_Rows();
	$t = array();
	$t['total'] = $afetados;
	$t['list'][] = array('id'=>$id);
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['tarefaFeita'])){
	$id = (int)$_REQUEST['tarefaFeita'];
	$compl = _get('compl') ? 1 : 0;
	if($compl) 	{
		$sql->adTabela('parafazer_tarefa');
		$sql->adCampo('MAX(ow)');
		$sql->adOnde('compl=1');
		$resultado = $sql->Resultado();
		$sql->limpar();
		$ow = 1 + (int)$resultado;
		}
	else {
		$sql->adTabela('parafazer_tarefa');
		$sql->adCampo('MAX(ow)');
		$sql->adOnde('compl=0');
		$resultado = $sql->Resultado();
		$sql->limpar();
		$ow = 1 + (int)$resultado;
		}
	$sql->adTabela('parafazer_tarefa');
	$sql->adAtualizar('compl', $compl);
	$sql->adAtualizar('ow', $ow);
	$sql->adOnde('id= '.$id);
	if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
	$sql->limpar();
	$t = array();
	$t['total'] = 1;
	$t['list'][] = array('id' => $id, 'compl' => $compl, 'ow' => $ow);
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['editarNota'])){
	$id = (int)$_REQUEST['editarNota'];
	stop_gpc($_REQUEST);
	$nota = str_replace("\r\n", "\n", trim(_post('nota')));
	$sql->adTabela('parafazer_tarefa');
	$sql->adAtualizar('nota', previnirXSS(utf8_decode($nota)));
	$sql->adOnde('id= '.$id);
	if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
	$sql->limpar();
	$t = array();
	$t['total'] = 1;
	$t['list'][] = array('id' => $id, 'nota' => nl2br(htmlarray($nota)), 'notaText' => (string)$nota);
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['getTarefa'])){
	$id = (int)$_REQUEST['getTarefa'];
	$t = array();
	$t['total'] = 0;
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('id, d, lista_id, compl, titulo, nota, prio, ow, parafazer_chave, datafinal');
	$sql->adOnde('id='. $id);
	$r = $sql->Linha();
	$sql->limpar();
	if($r) {
		$t['list'][] = prepararLinhaTarefa($r);
		$t['total'] = 1;
		}
	echo json_encode($t); 
	exit;
	}
elseif(isset($_REQUEST['editarTarefa'])){
	$id = (int)$_REQUEST['editarTarefa'];
	stop_gpc($_REQUEST);
	$listId = (int)_post('list');
	$titulo = trim(_post('titulo'));
	$designados = trim(_post('designados'));
	
	if ($designados){
		$sql->setExcluir('parafazer_usuarios');
		$sql->adOnde('usuario_id NOT IN( '.$designados.')');
		$sql->adOnde('id ='.$id);
		if (!$sql->exec()) die('Não foi possivel excluir da tabela parafazer_usuarios!'.$bd->stderr(true));
		$sql->limpar();
		}
	$vetor_designados = explode(',', $designados);

	if (is_array($vetor_designados) && count($vetor_designados)) {
		foreach ($vetor_designados as $uid) {
			if ($uid && $uid!=$usuario) {
				//checar se já foi inserido
				$sql->adTabela('parafazer_usuarios');
				$sql->adOnde('id = '.$id);
				$sql->adOnde('usuario_id = '.$uid);
				$sql->adCampo('usuario_id');
				$ja_tem=$sql->Resultado();
				$sql->limpar();
				if (!$ja_tem){
					$sql->adTabela('parafazer_usuarios');
					$sql->adInserir('id', $id);
					$sql->adInserir('usuario_id', $uid);
					$sql->exec();
					$sql->limpar();
					}
				}
			}
		}
	$nota = str_replace("\r\n", "\n", trim(_post('nota')));
	
	$prio = (int)_post('prio');
	if($prio < -1) $prio = -1;
	elseif($prio > 2) $prio = 2;
	$datafinal = formatar_datafinal(trim(_post('datafinal')));
	$t = array();
	$t['total'] = 0;
	if($titulo == '') {
		echo json_encode($t);
		exit;
		}

	
	$parafazer_chave = trim(_post('parafazer_chave'));
	$palavra_chave_ids = preparar_parafazer_chave($parafazer_chave, $listId); 
	$cur_ids = get_tarefa_parafazer_chave($id);
	if($cur_ids) {
		$ids = implode(',', $cur_ids);
		$sql->setExcluir('parafazer_chave_tarefa');
		$sql->adOnde('tarefa_id ='.$id);
		if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_chave_tarefa!'.$bd->stderr(true));
		$sql->limpar();	
		$sql->adTabela('parafazer_chave');
		$sql->adAtualizar('cont_palavra_chave', 'cont_palavra_chave-1');
		$sql->adOnde('id IN ('.$ids.')');
		if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_chave!'.$bd->stderr(true));
		$sql->limpar();
		}
	if($palavra_chave_ids) atualizar_chave_tarefa_parafazer($id, $palavra_chave_ids);
	$sql->adTabela('parafazer_tarefa');
	$sql->adAtualizar('titulo', previnirXSS(utf8_decode($titulo)));
	$sql->adAtualizar('nota', previnirXSS(utf8_decode($nota)));
	$sql->adAtualizar('prio', $prio);
	$sql->adAtualizar('parafazer_chave', previnirXSS(utf8_decode($parafazer_chave)));
	$sql->adAtualizar('datafinal', (is_null($datafinal) ? NULL : $datafinal));
	$sql->adOnde('id= '.$id);
	if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
	$sql->limpar();
	$sql->adTabela('parafazer_tarefa');
	$sql->adCampo('id, d, lista_id, compl, titulo, nota, prio, ow, parafazer_chave, datafinal');
	$sql->adOnde('id='. $id);
	$r = $sql->Linha();
	$sql->limpar();
	if($r) {
		$t['list'][] = prepararLinhaTarefa($r);
		$t['total'] = 1;
		}
	echo json_encode($t); 
	exit;
	}
elseif(isset($_REQUEST['mudarOrdem'])){
	stop_gpc($_REQUEST);
	$s = _post('order');
	parse_str($s, $order);
	$t = array();
	$t['total'] = 0;
	if($order){
		$ad = array();
		foreach($order as $id => $diff) $ad[(int)$diff][] = (int)$id;
		foreach($ad as $diff => $ids) {
			if($diff >=0) $set = "ow+".$diff;
			else $set = "ow-".abs($diff);
			$sql->adTabela('parafazer_tarefa');
			$sql->adAtualizar('ow', $set);
			$sql->adOnde('id IN ('.implode(',',$ids).')');
			$sql->sem_aspas();
			if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
			$sql->limpar();
			}
		$t['total'] = 1;
		}
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['sugerirChaves'])){
	$listId = (int)_get('list');
	$begin = trim(_get('q'));
	$limite = (int)_get('limit');
	if($limite<1) $limite = 8;
	$sql->adTabela('parafazer_chave');
	$sql->adCampo('nome,id');
	$sql->adOnde('lista_id='. $listId);
	$sql->adOnde('nome LIKE \'%'.previnirXSS(utf8_decode($begin)).'%\'');
	$sql->adOnde('cont_palavra_chave>0');
	$sql->adOrdem('nome');
	$sql->setLimite($limite);
	$resultados=$sql->Lista();
	$sql->limpar();
		$s = '';
	foreach($resultados as $r) $s .= $r['nome'].'|'.$r['id']."\n";
	echo htmlarray($s);
	exit; 
	}
elseif(isset($_REQUEST['setPrio'])){
	$id = (int)$_REQUEST['setPrio'];
	$prio = (int)_get('prio');
	if($prio < -1) $prio = -1;
	elseif($prio > 2) $prio = 2;
	$sql->adTabela('parafazer_tarefa');
	$sql->adAtualizar('prio', $prio);
	$sql->adOnde('id ='.$id);
	if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_tarefa!'.$bd->stderr(true));
	$sql->limpar();
	$t = array();
	$t['total'] = 1;
	$t['list'][] = array('id' => $id, 'prio' => $prio);
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['nuvemChave'])){
	$listId = (int)_get('list');
	$a = array();
	$sql->adTabela('parafazer_chave');
	$sql->adCampo('nome,cont_palavra_chave');
	$sql->adOnde('lista_id='. $listId);
	$sql->adOnde('cont_palavra_chave>0');
	$sql->adOrdem('cont_palavra_chave ASC');
	$resultados=$sql->Lista();
	$sql->limpar();
	foreach($resultados as $r) $a[utf8_encode($r['nome'])] = $r['cont_palavra_chave'];
	$t = array();
	$t['total'] = 0;
	$count = sizeof($a);
	if(!$count) {
		echo json_encode($t);
		exit;
		}
	$qmax = max(array_values($a));
	$qmin = min(array_values($a));
	if($count >= 10) $grades = 10;
	else $grades = $count;
	$step = ($qmax - $qmin)/$grades;
	foreach($a as $tag => $q) $t['cloud'][] = array('tag'=>htmlarray($tag), 'w'=> palavra_chave_tamanho($qmin,$q,$step));
	$t['total'] = $count;
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['adicionarLista'])){
	stop_gpc($_REQUEST);
	$t = array();
	$t['total'] = 0;
	$nome = str_replace(array('"',"'",'<','>','&'),array('','','','',''),trim(_post('name')));
	
	$sql->adTabela('parafazer_listas');
	$sql->adInserir('nome', $nome);
	$sql->adInserir('usuario_id', $usuario);
	if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_listas!'.$bd->stderr(true));
	$id = $bd->Insert_ID('parafazer_listas','id');
	$sql->limpar();
	$t['total'] = 1;
	$sql->adTabela('parafazer_listas');
	$sql->adCampo('id, nome, usuario_id');
	$sql->adOnde('id='. $id);
	$r=$sql->Linha();
	$sql->limpar();
	$t['list'][] = array('id' => $r['id'], 'name' => htmlarray(utf8_encode($r['nome'])));
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['renomearLista'])){
	stop_gpc($_REQUEST);
	$t = array();
	$t['total'] = 0;
	$id = (int)_post('id');
	$nome = str_replace(array('"',"'",'<','>','&'),array('','','','',''),trim(_post('name')));
	$sql->adTabela('parafazer_listas');
	$sql->adAtualizar('nome', previnirXSS(utf8_decode($nome)));
	$sql->adOnde('id ='.$id);
	if (!$sql->exec()) die('Não foi possivel atualizar a tabela parafazer_listas!'.$bd->stderr(true));
	$sql->limpar();
	$t['total'] = $bd->Affected_Rows();
	$sql->limpar();
	$sql->adTabela('parafazer_listas');
	$sql->adCampo('id, nome, usuario_id');
	$sql->adOnde('id='.$id);
	$r = $sql->Linha();
	$sql->limpar();
	$t['list'][] = array('id'=>$r['id'], 'name'=>htmlarray(utf8_encode($r['nome'])));
	echo json_encode($t);
	exit;
	}
elseif(isset($_REQUEST['excluiLista'])){
	stop_gpc($_REQUEST);
	$t = array();
	$t['total'] = 0;
	$id = (int)_post('id');
	$sql->setExcluir('parafazer_listas');
	$sql->adOnde('id ='.$id);
	if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_listas!'.$bd->stderr(true));
	$sql->limpar();	
	$t['total'] = $bd->Affected_Rows();
	if($t['total']) {
		$sql->setExcluir('parafazer_chave');
		$sql->adOnde('lista_id ='.$id);
		if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_chave!'.$bd->stderr(true));
		$sql->limpar();	
		$sql->setExcluir('parafazer_chave_tarefa');
		$sql->adOnde('tarefa_id IN (SELECT id FROM parafazer_tarefa WHERE lista_id='.$id.')');
		if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_chave!'.$bd->stderr(true));
		$sql->limpar();	
		$sql->setExcluir('parafazer_tarefa');
		$sql->adOnde('lista_id ='.$id);
		if (!$sql->exec()) die('Não foi possivel excluir os valores da tabela parafazer_chave!'.$bd->stderr(true));
		$sql->limpar();	
		}
	echo json_encode($t);
	exit;
	}


function prepararLinhaTarefa($r, $tz=null){
	$dueA = preparar_datafinal($r['datafinal'], $tz);
	return array(
		'id' => $r['id'],
		'titulo' => htmlarray(utf8_encode($r['titulo'])),
		'date' => htmlarray($r['d']),
		'compl' => (int)$r['compl'],
		'prio' => $r['prio'],
		'nota' => nl2br(htmlarray(utf8_encode($r['nota']))),
		'notaText' => (string)utf8_encode($r['nota']),
		'ow' => (int)$r['ow'],
		'parafazer_chave' => htmlarray(utf8_encode($r['parafazer_chave'])),
		'datafinal' => $dueA['formatada'],
		'dueClass' => $dueA['class'],
		'dueStr' => htmlarray($dueA['str']),
		'dueInt' => dataEmInt($r['datafinal']),
		);
	}


function preparar_parafazer_chave(&$parafazer_chave_str, $listId){
	$palavra_chave_ids = array();
	$palavra_chave_nomes = array();
	$parafazer_chave = explode(',', $parafazer_chave_str);
	foreach($parafazer_chave as $v){ 
		$tag = str_replace(array('"',"'",'<','>','&'),array('','','','',''),trim($v));
		if($tag == '') continue;
		list($palavra_chave_id, $palavra_chave_nome) = pegar_ou_criar_chave($tag, $listId);
		if($palavra_chave_id && !in_array($palavra_chave_id, $palavra_chave_ids)) {
			$palavra_chave_ids[] = $palavra_chave_id;
			$palavra_chave_nomes[] = $palavra_chave_nome;
			}
		}
	$parafazer_chave_str = implode(',', $palavra_chave_nomes);
	return $palavra_chave_ids;
	}

function pegar_ou_criar_chave($nome, $listId){
	global $bd;
	$q = new BDConsulta;
	$q->adTabela('parafazer_chave');
	$q->adCampo('id, nome');
	$q->adOnde('lista_id= '.$listId);
	$q->adOnde('nome= \''.previnirXSS(utf8_decode($nome)).'\'');
	$tag = $q->Linha();
	$q->limpar();
	if($tag) return array($tag['id'], utf8_encode($tag['nome']));
	$q->adTabela('parafazer_chave');
	$q->adInserir('nome', previnirXSS(utf8_decode($nome)));
	$q->adInserir('lista_id', $listId);
	if (!$q->exec()) die('Não foi possivel atualizar a tabela parafazer_chave!'.$bd->stderr(true));
	$id = $bd->Insert_ID('parafazer_chave','id');
	$q->limpar();
	return array($id, $nome);
	}

function get_palavra_chave_id($tag, $listId){
	global $bd;
	$q = new BDConsulta;
	$q->adTabela('parafazer_chave');
	$q->adCampo('id');
	$q->adOnde('lista_id= '.$listId);
	$q->adOnde('nome=\''.previnirXSS(utf8_decode($tag)).'\'');
	$id = $q->Resultado();
	$q->limpar();
	return $id ? $id : 0;
	}

function get_tarefa_parafazer_chave($id){
	global $bd;
	$q = new BDConsulta;
	$q->adTabela('parafazer_chave_tarefa');
	$q->adCampo('palavra_chave_id');
	$q->adOnde('tarefa_id= '.$id);
	$resultado = $q->Lista();
	$q->limpar();
	$a = array();
	foreach ($resultado as $r) $a[] = $r['palavra_chave_id'];
	return $a;
	}

function atualizar_chave_tarefa_parafazer($id, $palavra_chave_ids){
	global $bd;
	$q = new BDConsulta;
	foreach($palavra_chave_ids as $v) {
		$q->adTabela('parafazer_chave_tarefa');
		$q->adInserir('tarefa_id', $id);
		$q->adInserir('palavra_chave_id', $v);
		if (!$q->exec()) die('Não foi possivel atualizar a tabela parafazer_chave_tarefa!'.$bd->stderr(true));
		$q->limpar();
		}
	$q->adTabela('parafazer_chave');
	$q->adCampo('cont_palavra_chave');
	$q->adOnde('id IN ('. implode(',', $palavra_chave_ids). ')');
	$resultado = $q->Resultado();
	$q->limpar();		
	$q->adTabela('parafazer_chave');
	$q->adAtualizar('cont_palavra_chave', $resultado+1);
	$q->adOnde('id IN ('. implode(',', $palavra_chave_ids). ')');
	if (!$q->exec()) die('Não foi possivel atualizar a tabela parafazer_chave!'.$bd->stderr(true));
	$q->limpar();	
	}

function formatar_inteligente($titulo){
	$a = array();
	if(!preg_match("|^(/([+-]{0,1}\d+)?/)?(.*?)(\s+/([^/]*)/$)?$|", $titulo, $m)) return false;
	$a['prio'] = isset($m[2]) ? (int)$m[2] : 0;
	$a['titulo'] = isset($m[3]) ? trim($m[3]) : '';
	$a['parafazer_chave'] = isset($m[5]) ? trim($m[5]) : '';
	if($a['prio'] < -1) $a['prio'] = -1;
	elseif($a['prio'] > 2) $a['prio'] = 2;
	return $a;
	}

function palavra_chave_tamanho($qmin, $q, $step){
	if($step == 0) return 1;
	$v = ceil(($q - $qmin)/$step);
	if($v == 0) return 0;
	else return $v-1;
	}

function formatar_datafinal($s){
	global $config;
	$y = $m = $d = 0;
	if(preg_match("|^(\d+)-(\d+)-(\d+)\b|", $s, $ma)) {
		$y = (int)$ma[1]; $m = (int)$ma[2]; $d = (int)$ma[3];
		}
	elseif(preg_match("|^(\d+)\/(\d+)\/(\d+)\b|", $s, $ma)){
		if($config['datafinalformat'] == 4) {
			$d = (int)$ma[1]; 
			$m = (int)$ma[2]; 
			$y = (int)$ma[3];
			} 
		else {
			$m = (int)$ma[1]; 
			$d = (int)$ma[2]; 
			$y = (int)$ma[3];
			}
		}
	elseif(preg_match("|^(\d+)\.(\d+)\.(\d+)\b|", $s, $ma)) {
		$d = (int)$ma[1]; 
		$m = (int)$ma[2]; 
		$y = (int)$ma[3];
		}
	elseif(preg_match("|^(\d+)\.(\d+)\b|", $s, $ma)) {
		$d = (int)$ma[1]; 
		$m = (int)$ma[2]; 
		$a = explode(',', date('Y,m,d'));
		if($m<(int)$a[1] || ($m==(int)$a[1] && $d<(int)$a[2])) $y = (int)$a[0]+1; 
		else $y = (int)$a[0];
		}
	elseif(preg_match("|^(\d+)\/(\d+)\b|", $s, $ma)){
		if($config['datafinalformat'] == 4) {
			$d = (int)$ma[1]; 
			$m = (int)$ma[2];
			} 
		else {
			$m = (int)$ma[1]; 
			$d = (int)$ma[2];
			}
		$a = explode(',', date('Y,m,d'));
		if($m<(int)$a[1] || ($m==(int)$a[1] && $d<(int)$a[2])) $y = (int)$a[0]+1; 
		else $y = (int)$a[0];
		}
	else return null;
	if($y < 100) $y = 2000 + $y;
	elseif($y < 1000 || $y > 2099) $y = 2000 + (int)substr((string)$y, -2);
	if($m > 12) $m = 12;
	$maxdays = diasNoMes($m,$y);
	if($m < 10) $m = '0'.$m;
	if($d > $maxdays) $d = $maxdays;
	elseif($d < 10) $d = '0'.$d;
	return "$y-$m-$d";
	}

function preparar_datafinal($datafinal, $tz=null){
	global $config;

	$a = array( 'class'=>'', 'str'=>'', 'formatada'=>'' );
	if($datafinal == '') return $a;
	if(is_null($tz)) {
		$ad = explode('-', $datafinal);
		$at = explode('-', date('Y-m-d'));
		}
	else {
		$ad = explode('-', $datafinal);
		$at = explode('-', gmdate('Y-m-d',time() + $tz*60));
		}
	$diff = mktime(0,0,0,$ad[1],$ad[2],$ad[0]) - mktime(0,0,0,$at[1],$at[2],$at[0]);
	if($diff < -604800 && $ad[0] == $at[0])	{ $a['class'] = 'passado'; $a['str'] = formatarData3($config['dateformatshort'], (int)$ad[0], (int)$ad[1], (int)$ad[2]); }
	elseif($diff < -604800)	{ $a['class'] = 'passado'; $a['str'] = formatarData3($config['dateformat'], (int)$ad[0], (int)$ad[1], (int)$ad[2]); }
	elseif($diff < -86400)	{ $a['class'] = 'passado'; $a['str'] = sprintf("%d dias atras",ceil(abs($diff)/86400)); }
	elseif($diff < 0)		  	{ $a['class'] = 'passado'; $a['str'] = 'ontem'; }
	elseif($diff < 86400)		{ $a['class'] = 'hoje'; $a['str'] = 'hoje'; }
	elseif($diff < 172800)	{ $a['class'] = 'hoje'; $a['str'] = 'amanha'; }
	elseif($diff < 691200)	{ $a['class'] = 'breve'; $a['str'] = sprintf("em %d dias",ceil($diff/86400)); }
	elseif($ad[0] == $at[0]){ $a['class'] = 'futuro'; $a['str'] = formatarData3($config['dateformatshort'], (int)$ad[0], (int)$ad[1], (int)$ad[2]); }
	else {$a['class'] = 'futuro'; $a['str'] = formatarData3($config['dateformat'], (int)$ad[0], (int)$ad[1], (int)$ad[2]); }
	if($config['datafinalformat'] == 2) $a['formatada'] = (int)$ad[1].'/'.(int)$ad[2].'/'.$ad[0];
	elseif($config['datafinalformat'] == 3) $a['formatada'] = $ad[2].'.'.$ad[1].'.'.$ad[0];
	elseif($config['datafinalformat'] == 4) $a['formatada'] = $ad[2].'/'.$ad[1].'/'.$ad[0];
	else $a['formatada'] = $datafinal;
	return $a;
	}

function dataEmInt($d){
	if(!$d) return 33330000;
	$ad = explode('-', $d);
	$s = $ad[0];
	if(strlen($ad[1]) < 2) $s .= "0$ad[1]"; else $s .= $ad[1];
	if(strlen($ad[2]) < 2) $s .= "0$ad[2]"; else $s .= $ad[2];
	return (int)$s;
	}

function diasNoMes($m, $y=0){
	if($y == 0) $y = (int)date('Y');
	$a = array(1=>31,(($y-2000)%4?28:29),31,30,31,30,31,31,30,31,30,31);
	if(isset($a[$m])) return $a[$m]; else return 0;
	}

function meuControladorErro($errno, $errstr, $errfile, $errline){
	if($errno==E_ERROR || $errno==E_CORE_ERROR || $errno==E_COMPILE_ERROR || $errno==E_USER_ERROR || $errno==E_PARSE) $erro = 'Error';
	elseif($errno==E_WARNING || $errno==E_CORE_WARNING || $errno==E_COMPILE_WARNING || $errno==E_USER_WARNING || $errno==E_STRICT) {
		if(error_reporting() & $errno) $erro = 'Warning'; else return;
		}
	elseif($errno==E_NOTICE || $errno==E_USER_NOTICE) {
		if(error_reporting() & $errno) $erro = 'Notice'; else return;
		}
	elseif(defined('E_DEPRECATED') && ($errno==E_DEPRECATED || $errno==E_USER_DEPRECATED)) { # since 5.3.0
		if(error_reporting() & $errno) $erro = 'Notice'; else return;
		}
	else $erro = "Erro ($errno)";	
	throw new Exception("$erro: '$errstr' em $errfile:$errline", -1);
	}

function meuControladorExcessao($e){
	if(-1 == $e->getCode()) {
		echo $e->getMessage(); 
		exit;
		}
	echo 'Excessão: \''. $e->getMessage() .'\' em '. $e->getFile() .':'. $e->getLine();
	exit;
	}
?>