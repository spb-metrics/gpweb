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
$sql = new BDConsulta;
include_once BASE_DIR.'/modulos/tarefas/funcoes.php';
require_once ($Aplic->getClasseSistema('CampoCustomizados'));	
$wbs=getParam($_REQUEST, 'wbs', 0);

$cache = null;
if($Aplic->profissional){
	include_once BASE_DIR.'/modulos/projetos/tarefa_cache.class_pro.php';
	$cache = CTarefaCache::getInstance();	
	}

if (isset($_REQUEST['tarefa_previsto'])) $_REQUEST['tarefa_previsto']=float_americano(getParam($_REQUEST, 'tarefa_previsto', null));
if (isset($_REQUEST['tarefa_realizado'])) $_REQUEST['tarefa_realizado']=float_americano(getParam($_REQUEST, 'tarefa_realizado', null));
if (isset($_REQUEST['tarefa_duracao'])) $_REQUEST['tarefa_duracao']=getParam($_REQUEST, 'tarefa_duracao', null)*$config['horas_trab_diario'];


if (isset($_REQUEST['tarefa_percentagem']) && (($_REQUEST['tarefa_percentagem']!=$_REQUEST['tarefa_percentagem_antiga']) || !$_REQUEST['tarefa_id'])) $_REQUEST['tarefa_percentagem_data']=date('Y-m-d H:i:s');


$tarefa_inicio_calculado=getParam($_REQUEST, 'tarefa_inicio_calculado', 0);
$del=getParam($_REQUEST, 'del', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', null);
$listaDesignados=getParam($_REQUEST, 'listaDesignados', 0);
$hperc_designado=getParam($_REQUEST, 'hperc_designado', 0);
$hdependencias=getParam($_REQUEST, 'hdependencias', '');
$hdependencias_tipo=getParam($_REQUEST, 'hdependencias_tipo', '');
$notificar=getParam($_REQUEST, 'tarefa_notificar', 0);
$notificar_novos=getParam($_REQUEST, 'tarefa_notificar_novos', 0);
$notificar_responsavel=getParam($_REQUEST, 'tarefa_notificar_responsavel', 0);
$notificar_contatos=getParam($_REQUEST, 'tarefa_notificar_contatos', 0);
$comentario=getParam($_REQUEST, 'email_comentario', '');
$comentario_responsavel=getParam($_REQUEST, 'email_comentario_responsavel', '');
$nao_eh_novo=getParam($_REQUEST, 'tarefa_id', null);
$recursos_quantidade = null;
$recursos_inicio = null;
$recursos_fim = null;
$projeto_id=($tarefa_id ? projeto_id($tarefa_id) :0);
$diferentes=array();
$vetor_customizado=array();

//verificar permissões	
if ($del && !$podeExcluir) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif ($nao_eh_novo && !$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
elseif (!$nao_eh_novo && !$podeAdicionar) $Aplic->redirecionar('m=publico&a=acesso_negado');


if ($notificar_novos && $tarefa_id){
	$sql->adTabela('tarefa_designados');
	$sql->adCampo('usuario_id');
	$sql->adOnde('tarefa_id='.(int)$tarefa_id);
	$lista_designados_antigo=$sql->carregarColuna();
	$sql->limpar();
	
	}
else $lista_designados_antigo=array();


$obj=new CTarefa(isset($baseline_id) ? $baseline_id : 0);
$obj->load($tarefa_id);

if (isset($_REQUEST)){
	if (!$obj->join($_REQUEST)) {
		$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
		$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.(int)$projeto_id);
		}
	}

$codigo=$obj->getCodigo();
if ($codigo) $obj->tarefa_codigo=$codigo;
	
if ($del) {
	if(isset($cache)){
		if(!$cache->excluirTarefa($tarefa_id, false)) $Aplic->redirecionar('m=projetos&a=ver&projeto_id='.(int)$projeto_id);
		else{
			$Aplic->setMsg(ucfirst($config['tarefa']).' excluid'.$config['genero_tarefa']);
			if (!$wbs) $Aplic->redirecionar('m=projetos&a=ver&projeto_id='.(int)$projeto_id);
			else echo '<script language="javascript">window.close();</script>';
			}
		exit();			
	}
	else{
		if (($msg = $obj->excluir())) {
			$Aplic->setMsg($msg, UI_MSG_ERRO);
			$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.(int)$projeto_id);
			exit();
			} 
		else {
			if ($Aplic->profissional) {
				$sql->adTabela('tarefas');
				$sql->adCampo('tarefa_superior');
				$sql->adOnde('tarefa_id='.(int)$tarefa_id);
				$tarefa_superior=$sql->Resultado();
				$sql->limpar();
				renumerar_tarefas_apos_exclusao($tarefa_id, $projeto_id, $tarefa_superior);
				}

			atualizar_percentagem($projeto_id);
			}
			$Aplic->setMsg(ucfirst($config['tarefa']).' excluid'.$config['genero_tarefa']);
			if (!$wbs) $Aplic->redirecionar('m=projetos&a=ver&projeto_id='.(int)$projeto_id);
			else echo '<script language="javascript">window.close();</script>';
			exit();
	 	}
	}
	


if (!$obj->tarefa_dono) $obj->tarefa_dono = $Aplic->usuario_id;

if (isset($_REQUEST['nova_tarefa_projeto']) && $_REQUEST['nova_tarefa_projeto'] && ($obj->tarefa_projeto != $_REQUEST['nova_tarefa_projeto'])) {
	
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_superior = '.$obj->tarefa_id.' AND tarefa_id!='.(int)$obj->tarefa_id);
	$lista_tarefas=$sql->carregarColuna();
	$sql->Limpar();

	foreach($lista_tarefas as $tarefa_mudar){
		mudar_subordinada($tarefa_mudar, getParam($_REQUEST, 'nova_tarefa_projeto', null));
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_projeto', getParam($_REQUEST, 'nova_tarefa_projeto', null));
		$sql->adOnde('tarefa_id='.(int)$tarefa_mudar);
		$sql->exec();
		$sql->Limpar();
		}
	//Falta os sql de atualizar arquivos, etc,
	$obj->tarefa_projeto = getParam($_REQUEST, 'nova_tarefa_projeto', null);
	$obj->tarefa_superior = $obj->tarefa_id;
	}


$tmp_ar = explode(';', $hperc_designado);
$hperc_designado_ar = array();
for ($i = 0, $i_cmp = sizeof($tmp_ar); $i < $i_cmp; $i++) {
	$tmp = explode('=', $tmp_ar[$i]);
	if (count($tmp) > 1) $hperc_designado_ar[$tmp[0]] = $tmp[1];
	else $hperc_designado_ar[$tmp[0]] = 100;
	}

if ($obj->tarefa_inicio) {
	$data = new CData($obj->tarefa_inicio);
	$obj->tarefa_inicio = $data->format(FMT_TIMESTAMP_MYSQL);
	}

//estranho o código abaixo	
$data_fim = null;
if ($obj->tarefa_fim){
	if (strpos($obj->tarefa_fim, '2400') !== false) $obj->tarefa_fim = str_replace('2400', '2359', $obj->tarefa_fim);
	$data_fim = new CData($obj->tarefa_fim);
	$obj->tarefa_fim = $data_fim->format(FMT_TIMESTAMP_MYSQL);
	}
	
	
if (($msg = $obj->armazenar())){
	$Aplic->setMsg($msg, UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=ver&projeto_id='.(int)$projeto_id);
	exit();
	} 
else{
	$tarefa_fim = new CData($obj->tarefa_fim);

	if (!$Aplic->profissional) {
		if (empty($tarefa_fim) || (!empty($data_fim) && $tarefa_fim->dataDiferenca($data_fim))) $obj->adLembrete();
		}
	
	$Aplic->setMsg($tarefa_id ? ucfirst($config['tarefa']).' atualizad'.$config['genero_tarefa'] : ucfirst($config['tarefa']).' adiciond'.$config['genero_tarefa'], UI_MSG_OK);
	}


if (isset($listaDesignados)) $obj->atualizarDesignados($listaDesignados, $hperc_designado_ar);
else {
	$sql = new BDConsulta;
	$sql->setExcluir('tarefa_designados');
	$sql->adOnde('tarefa_id = '.(int)$obj->tarefa_id);
	$sql->exec();
	$sql->limpar();
	}
	
if(isset($_REQUEST['hdependencias']) && !$_REQUEST['hdependencias']){
	$sql = new BDConsulta;
	$sql->setExcluir('tarefa_dependencias');
	$sql->adOnde('dependencias_tarefa_id = '.(int)$obj->tarefa_id);
	$sql->exec();
	$sql->limpar();
	}
elseif ($_REQUEST['hdependencias'] && $_REQUEST['hdependencias_tipo']){
	if(isset($cache)) $cache->mudarDependencias((int)$obj->tarefa_id, getParam($_REQUEST, 'hdependencias', null), getParam($_REQUEST, 'hdependencias_tipo', null));
	else $obj->mudar_dependencia(getParam($_REQUEST, 'hdependencias', null), getParam($_REQUEST, 'hdependencias_tipo', null));
	} 

if(isset($cache)){
	$cache->load($projeto_id);
	$cache->verificaDependencias($obj->tarefa_id, true);
	}
else{
	verifica_dependencias($obj->tarefa_id);
	calcular_superior($obj->tarefa_id);	
	}


//verificar a superior anterior à mudança
if (isset($tarefa_antes['tarefa_superior']) && $tarefa_antes['tarefa_superior']!=$obj->tarefa_id && $tarefa_antes['tarefa_superior']!=$obj->tarefa_superior){
	if(isset($cache)) $cache->calcularTarefaSuperior($tarefa_antes['tarefa_superior']);
	else calcular_superior($tarefa_antes['tarefa_superior']);
	}

if(isset($cache)) $cache->flush();

if (isset($post_salvar)) {
	foreach ($post_salvar as $post_funcao_salvar) $post_funcao_salvar();
	}
	
if ($notificar) {
	if ($msg = $obj->notificar($comentario, $nao_eh_novo)) $Aplic->setMsg($msg, UI_MSG_ERRO);
	}

if ($notificar_novos) {
	if ($msg = $obj->notificar_novos($comentario, $nao_eh_novo, $lista_designados_antigo)) $Aplic->setMsg($msg, UI_MSG_ERRO);
	}


if ($notificar_contatos) {
	if ($msg = $obj->notificarContatos($comentario_responsavel, $nao_eh_novo)) $Aplic->setMsg($msg, UI_MSG_ERRO);
	}
	
if ($notificar_responsavel) {
	if ($msg = $obj->notificarResponsavel($comentario_responsavel, $nao_eh_novo)) $Aplic->setMsg($msg, UI_MSG_ERRO);
	}	

if (!$projeto_id) $projeto_id=projeto_id($obj->tarefa_id);

//Se for programa social recalcular
if ($obj->tarefa_acao){
	$sql = new BDConsulta;
	//achar o campo realizado
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$obj->tarefa_acao);
	$sql->adOnde('social_acao_lista_final=1');
	$final_id=$sql->Resultado();
	$sql->limpar();
	
	
	$sql->adTabela('social_familia_acao');
	$sql->esqUnir('social_familia', 'social_familia', 'social_familia_acao_familia=social_familia_id');
	$sql->dirUnir('social_familia_lista', 'social_familia_lista', 'social_familia_lista_familia=social_familia_id AND social_familia_lista_lista='.(int)$final_id);
	$sql->adCampo('count(social_familia_acao_familia)');
	$sql->adOnde('social_familia_acao_acao='.(int)$obj->tarefa_acao);
	if ($obj->tarefa_estado) $sql->adOnde('social_familia_estado="'.$obj->tarefa_estado.'"');
	if ($obj->tarefa_cidade) $sql->adOnde('social_familia_municipio="'.(int)$obj->tarefa_cidade.'"');
	if ($obj->tarefa_comunidade) $sql->adOnde('social_familia_comunidade='.(int)$obj->tarefa_comunidade);
	$concluido=$sql->Resultado();
	$sql->limpar();
	$sql->adTabela('social_familia_acao');
	$sql->esqUnir('social_familia', 'social_familia', 'social_familia_acao_familia=social_familia_id');
	$sql->adCampo('count(social_familia_acao_familia)');
	$sql->adOnde('social_familia_acao_acao='.(int)$obj->tarefa_acao);
	if ($obj->tarefa_estado) $sql->adOnde('social_familia_estado="'.$obj->tarefa_estado.'"');
	if ($obj->tarefa_cidade) $sql->adOnde('social_familia_municipio="'.(int)$obj->tarefa_cidade.'"');
	if ($obj->tarefa_comunidade) $sql->adOnde('social_familia_comunidade='.(int)$obj->tarefa_comunidade);
	$total= $sql->Resultado();
	$sql->limpar();
	$porcentagem=($total!=0 ? ($concluido/$total)*100 : 0);
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_percentagem', $porcentagem);
	$sql->adAtualizar('tarefa_percentagem_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('tarefa_realizado', $concluido);
	$sql->adAtualizar('tarefa_previsto', $total);
	$sql->adOnde('tarefa_id='.(int)$obj->tarefa_id);
	$sql->exec();
	$sql->limpar();
	}


atualizar_percentagem($projeto_id);

//acertar o marco
if (!$obj->tarefa_marco && !$obj->tarefa_dinamica && !($obj->tarefa_duracao > 0)){
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_marco', 1);
	$sql->adOnde('tarefa_id='.(int)$obj->tarefa_id);
	$sql->exec();
	$sql->limpar();
	}
elseif ($obj->tarefa_marco && (($obj->tarefa_duracao > 0) || $obj->tarefa_dinamica)){
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_marco', 0);
	$sql->adOnde('tarefa_id='.(int)$obj->tarefa_id);
	$sql->exec();
	$sql->limpar();
	}


$obj->setSequencial();

if ($Aplic->profissional && !$obj->tarefa_numeracao){
	$tarefa_numeracao=numeracao_nova_tarefa($obj->tarefa_projeto, ($obj->tarefa_superior!=$obj->tarefa_id ? $obj->tarefa_superior : null));
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_numeracao', $tarefa_numeracao);
	$sql->adOnde('tarefa_id = '.(int)$obj->tarefa_id);
	$sql->exec();
	$sql->limpar();
	} 

//alerta de tarefa TI antecessora completada
if ($Aplic->profissional && $config['aviso_TI'] && $_REQUEST['tarefa_percentagem_antes']!=$obj->tarefa_percentagem){
	require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
	require_once BASE_DIR.'/modulos/tarefas/funcoes_pro.php';
	alerta_sucessora_TI($obj->tarefa_id, getParam($_REQUEST, 'tarefa_percentagem_antes', null), $obj->tarefa_percentagem);
	}
	
if (!$wbs) $Aplic->redirecionar('m=tarefas&a=ver&tarefa_id='.$obj->tarefa_id);
else echo '<script language="javascript">window.close();</script>';

$Aplic->redirecionar('m=tarefas');


function mudar_subordinada($tarefa_id, $projeto_id){
	global $sql;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_superior = '.$tarefa_id.' AND tarefa_id!='.$tarefa_id);
	$lista_tarefas=$sql->carregarColuna();
	$sql->Limpar();

	foreach($lista_tarefas as $tarefa_mudar){
		mudar_subordinada($tarefa_mudar, $projeto_id);
		$sql->adTabela('tarefas');
		$sql->adAtualizar('tarefa_projeto', $projeto_id);
		$sql->adOnde('tarefa_id='.$tarefa_mudar);
		$sql->exec();
		$sql->Limpar();
		}
	}


function projeto_id($tarefa_id){
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_projeto');
	$sql->adOnde('tarefa_id ='.$tarefa_id);
	$projeto_id=$sql->Resultado();
	$sql->limpar();
	return $projeto_id;
	}
	
function acharTabModulos($modulo, $arquivo = null) {
	$listaModulos = array();
	if (!isset($_SESSION['todas_tabs']) || !isset($_SESSION['todas_tabs'][$modulo])) return $listaModulos;
	if (isset($arquivo)) {
		if (isset($_SESSION['todas_tabs'][$modulo][$arquivo]) && is_array($_SESSION['todas_tabs'][$modulo][$arquivo])) $vetor_tabs = &$_SESSION['todas_tabs'][$modulo][$arquivo];
		else return $listaModulos;
		} 
	else $vetor_tabs = &$_SESSION['todas_tabs'][$modulo];
	foreach ($vetor_tabs as $tab) {
		if (isset($tab['modulo'])) $listaModulos[] = $tab['modulo'];
		}
	return array_unique($listaModulos);
	}	



	
	
?>