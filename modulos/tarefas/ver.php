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

if (isset($_REQUEST['baseline_id'])) $Aplic->setEstado('baseline_id', getParam($_REQUEST, 'baseline_id', null));
$baseline_id = ($Aplic->getEstado('baseline_id') !== null ? $Aplic->getEstado('baseline_id') : null);
$sql = new BDConsulta;
$sql->adTabela('demanda_config');
$sql->adCampo('demanda_config.*');
$linha = $sql->linha();
$sql->Limpar();
if ($baseline_id){
	$sql->adTabela('baseline');
	$sql->adCampo('baseline_data');
	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$hoje=$sql->resultado();
	$sql->limpar();
	}
else $hoje=date('Y-m-d H:i:s');

$tarefa_id = intval(getParam($_REQUEST, 'tarefa_id', 0));
$tarefa_log_id = intval(getParam($_REQUEST, 'tarefa_log_id', 0));
$lembrar = intval(getParam($_REQUEST, 'lembrar', 0));
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
$tarefaStatus = getSisValor('StatusTarefa');
$social=$Aplic->modulo_ativo('social');
$paises = getPais('Paises');



$sql->adTabela('municipios_coordenadas');
$sql->adCampo('count(municipio_id)');
$tem_coordenadas=$sql->resultado();
$sql->limpar();

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();


$tarefa_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$msg = '';
$obj = new CTarefa(($baseline_id ? true : false), true);
$obj->load($tarefa_id);

//tarefa não existe mais
if(!$obj->tarefa_id){
	$Aplic->redirecionar('m=publico&a=nao_existe&campo='.$config['tarefa'].'&masculino='.$config['genero_tarefa']);
	}

$projeto_id=$obj->tarefa_projeto;

$sql->adTabela('baseline');
$sql->adCampo('baseline_id, concatenar_tres(formatar_data(baseline_data, "%d/%m/%Y %H:%i"), \' - \', baseline_nome) AS nome');
$sql->adOnde('baseline_projeto_id = '.(int)$projeto_id);
$baselines=array(0=>'')+$sql->listaVetorChave('baseline_id','nome');
$sql->limpar();

$selecionar_baseline=(count($baselines)> 1 && $Aplic->profissional ? '<tr><td align="right">'.dica('Baseline', 'Escolha na caixa de opção à direita a baseline que deseja visualizar.').'Baseline:'.dicaF().'</td><td>'.selecionaVetor($baselines, 'baseline_id', 'class="texto" style="width:200px;" size="1" onchange="mudar_baseline();"', $baseline_id).'</td></tr>' : '');

$editar=$obj->podeEditar($Aplic->usuario_id);

if (!$obj) {
	$Aplic->setMsg('Tarefa');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=tarefas');
	}
else $Aplic->salvarPosicao();

if (!$obj->podeAcessar($Aplic->usuario_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if ($lembrar) $obj->limparLembrete();
if (isset($_REQUEST['tab'])) $Aplic->setEstado('TarefaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('TarefaVerTab') !== null ? $Aplic->getEstado('TarefaVerTab') : 0;
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$data_inicio = intval($obj->tarefa_inicio) ? new CData($obj->tarefa_inicio) : null;
$data_fim = intval($obj->tarefa_fim) ? new CData($obj->tarefa_fim) : null;

$sql->adTabela('projetos');
$sql->adCampo('projeto_acesso, projeto_portfolio, projeto_aprova_registro');
$sql->adOnde('projeto_id='.(int)$projeto_id);
$projeto=$sql->linha();
$sql->limpar();

$podeAcessarProjeto = permiteAcessar($projeto['projeto_acesso'], $projeto_id);
$podeEditarProjeto = permiteEditar($projeto['projeto_acesso'], $projeto_id);

$sql->limpar();
$tipoDuracao = getSisValor('TipoDuracaoTarefa');


if (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'tarefa.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");

	if ($podeAcessarProjeto) $km->Add("ver","ver_projeto",dica('Ver '.ucfirst($config['projeto']), 'Visualizar os detalhes '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");

	if ($podeEditar || $Aplic->checarModulo('tarefa_log', 'adicionar')){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		if ($podeEditar && $Aplic->checarModulo('tarefas', 'adicionar'))	$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Criar uma nov'.$config['genero_tarefa'].' '.$config['tarefa'].' '.($config['genero_projeto']=='o' ? 'neste' : 'nesta').' '.$config['projeto'].'.').ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_projeto=".$projeto_id."\");");
		if ($podeEditar){
			if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_tarefa=".$tarefa_id.'&evento_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_tarefa=".$tarefa_id.'&arquivo_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_tarefa=".$tarefa_id.'&link_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_tarefa=".$tarefa_id.'&forum_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_tarefa=".$tarefa_id.'&pratica_indicador_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_tarefa=".$tarefa_id.'&plano_acao_projeto='.$obj->tarefa_projeto."\");");
			$km->Add("inserir","inserir_planilha_custo",dica('Planilha de Custos', 'Visualizar e editar a planilha de previsão de custos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=estimado&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_planilha_gasto",dica('Planilha de Gastos', 'Visualizar e editar a planilha de gastos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Gastos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=gasto&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_recurso",dica('Recurso', 'Alocar recurso '.($config['genero_tarefa']=='o' ? 'neste' : 'nesta').' '.$config['tarefa'].'.').'Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=recurso_alocar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_expediente",dica('Editar Expediente', 'Editar expediente relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Expediente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=jornada_editar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			}
		}


	if ($config['anexo_civil']) $km->Add("ver","artefatos", dica(ucfirst($config['artefatos']),'Visualizar '.$config['genero_artefato'].'s '.$config['artefatos'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['artefatos']).dicaF(), "javascript: void(0);' onclick='menu_anexos()");
	if ($Aplic->checarModulo('tarefas', 'adicionar')){
		$km->Add("ver","ver_eap",dica('Estrutura Analítica do Projeto - Work Breakdown Structure','Visualizar a estrutura analíticas do projeto.<br>É uma ferramenta de decomposição do trabalho d'.$config['genero_projeto'].' '.$config['projeto'].' em partes manejáveis. É estruturada em árvore exaustiva, hierárquica (de mais geral para mais específica) orientada às entregas que precisam ser feitas para completar '.($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'].'.').'EAP (WBS)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical&projeto_id=".$projeto_id."\");");
		$km->Add("ver","ver_rapido",dica('Gantt Interativo','Exibir interface de criação e edição de '.$config['projetos'],' utilizando gráfico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo&projeto_id=".$projeto_id."\");");
		}
	if ($Aplic->checarModulo('relatorios', 'acesso')) $km->Add("ver","ver_relatorios",dica('Relatórios','Visualizar a lista de relatórios.<br><br>Os relatórios são modos convenientes de se ter uma visão panorâmica de como as divers'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].' estão se desenvolvendo.').'Relatórios'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index&projeto_id=".$projeto_id."\");");
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar){
		$km->Add("acao","acao_editar",dica('Editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.ucfirst($config['tarefa']),'Editar os detalhes '.($config['genero_tarefa']=='o' ? 'deste' : 'desta').' '.$config['tarefa'].'.').'Editar '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_id=".$obj->tarefa_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']),'Excluir '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' do sistema.<br><br>Todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' pertencentes a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' também serão excluídas.').'Excluir '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['projetos']),'Visualizar a lista de '.($config['genero_projeto']=='o' ? 'todos os' : 'todas as').' '.$config['projetos'].'.').'Lista de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos\");");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir decumentos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir();");
	echo $km->Render();
	echo '</td></tr>';
	echo '</table>';
	
	
	
	}





if (!$dialogo && $Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'tarefa.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">';
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");

	if ($podeAcessarProjeto) $km->Add("ver","ver_projeto",dica('Ver '.ucfirst($config['projeto']), 'Visualizar os detalhes '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");

	if ($podeEditar || $Aplic->checarModulo('tarefa_log', 'adicionar')){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		if ($podeEditar && $Aplic->checarModulo('tarefas', 'adicionar'))	$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Criar uma nov'.$config['genero_tarefa'].' '.$config['tarefa'].' '.($config['genero_projeto']=='o' ? 'neste' : 'nesta').' '.$config['projeto'].'.').ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_projeto=".$projeto_id."\");");
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=ver_log_atualizar_pro&tarefa_id=".$tarefa_id.'&projeto_id='.$obj->tarefa_projeto."\");");
		if ($podeEditar){
			if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_tarefa=".$tarefa_id.'&evento_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_tarefa=".$tarefa_id.'&arquivo_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_tarefa=".$tarefa_id.'&link_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_tarefa=".$tarefa_id.'&forum_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_tarefa=".$tarefa_id.'&pratica_indicador_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_tarefa=".$tarefa_id.'&plano_acao_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_tarefa=".$tarefa_id.'&msg_projeto='.$obj->tarefa_projeto."\");");
			if ($config['doc_interno'] && $Aplic->checarModulo('email', 'adicionar', $Aplic->usuario_id, 'criar_modelo')){
			$sql->adTabela('modelos_tipo');
			$sql->esqUnir('modelo_cia', 'modelo_cia', 'modelo_cia_tipo=modelo_tipo_id');
			$sql->adCampo('modelo_tipo_id, modelo_tipo_nome, imagem');
			$sql->adOnde('organizacao='.(int)$config['militar']);
			$sql->adOnde('modelo_cia_cia='.(int)$Aplic->usuario_cia);
			$modelos = $sql->Lista();
			$sql->limpar();
			if (count($modelos)){
				$km->Add("inserir","criar_documentos","Documento");
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_tarefa=".$tarefa_id."&modelo_projeto=".$obj->tarefa_projeto."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
			if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_tarefa=".$tarefa_id.'&ata_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_tarefa=".$tarefa_id.'&problema_projeto='.$obj->tarefa_projeto."\");");
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_tarefa=".$tarefa_id.'&risco_projeto='.$obj->tarefa_projeto."\");");
			$km->Add("inserir","inserir_planilha_custo",dica('Planilha de Custos', 'Visualizar e editar a planilha de previsão de custos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=estimado_pro&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_planilha_gasto",dica('Planilha de Gastos', 'Visualizar e editar a planilha de gastos dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.').'Planilha de Gastos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=gasto_pro&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_gasto_mo",dica('Gasto de Mão de Obra','Acesse interface onde será possível inserir períodos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos designados.').'Gasto de Mão de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=folha_ponto_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_recurso",dica('Recurso', 'Alocar recurso '.($config['genero_tarefa']=='o' ? 'neste' : 'nesta').' '.$config['tarefa'].'.').'Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=recurso_alocar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_gasto_recurso",dica('Gasto com Recurso','Acesse interface onde será possível inserir períodos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' pelos recursos.').'Gasto com Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=recurso_ponto_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			//if ($custo_total) $km->Add("inserir","inserir_pagamento",dica('Pagamento', 'inseri um pagamento relacionado com custos '.($config['genero_tarefa']=='o' ? 'deste' : 'desta').' '.$config['tarefa'].'.').'Pagamento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=pagamento_editar_pro&pagamento_projeto=".$projeto_id."&pagamento_tarefa=".$obj->tarefa_id."\");");
			$km->Add("inserir","inserir_expediente",dica('Editar Expediente', 'Editar expediente relacionado a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').'Expediente'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=jornada_editar&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			}
		}

	if ($config['anexo_eb']){
		$km->Add("ver","negapeb",dica(ucfirst($config['anexo_eb_nome']),'Visualizar '.$config['genero_anexo_eb_nome'].' '.$config['anexo_eb_nome'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['anexo_eb_nome']).dicaF(), "javascript: void(0);");
		if ($linha['demanda_config_ativo_diretriz_iniciacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_diretriz_iniciacao']),ucfirst($linha['demanda_config_diretriz_iniciacao']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_diretriz_iniciacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=iniciacao_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_estudo_viabilidade']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_estudo_viabilidade']),ucfirst($linha['demanda_config_estudo_viabilidade']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_estudo_viabilidade']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=viabilidade_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_diretriz_implantacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_diretriz_implantacao']),ucfirst($linha['demanda_config_diretriz_implantacao']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_diretriz_implantacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=implantacao_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_declaracao_escopo']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_declaracao_escopo']),ucfirst($linha['demanda_config_declaracao_escopo']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_declaracao_escopo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=escopo_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_estrutura_analitica']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_estrutura_analitica']),ucfirst($linha['demanda_config_estrutura_analitica']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_estrutura_analitica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=estrutura_analitica_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_dicionario_eap']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_dicionario_eap']),ucfirst($linha['demanda_config_dicionario_eap']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_dicionario_eap']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=dicionario_eap_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_cronograma_fisico']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_cronograma_fisico']),ucfirst($linha['demanda_config_cronograma_fisico']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_cronograma_fisico']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=cronograma_financeiro_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_plano_projeto']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_plano_projeto']),ucfirst($linha['demanda_config_plano_projeto']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_plano_projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=plano_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_cronograma']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_cronograma']),ucfirst($linha['demanda_config_cronograma']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_cronograma']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=cronograma_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_planejamento_custo']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_planejamento_custo']),ucfirst($linha['demanda_config_planejamento_custo']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_planejamento_custo']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=custo_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_gerenciamento_humanos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_humanos']),ucfirst($linha['demanda_config_gerenciamento_humanos']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_humanos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=humano_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_gerenciamento_comunicacoes']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_comunicacoes']),ucfirst($linha['demanda_config_gerenciamento_comunicacoes']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_comunicacoes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=comunicacao_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_gerenciamento_partes']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_partes']),ucfirst($linha['demanda_config_gerenciamento_partes']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_partes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=interessado_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_gerenciamento_riscos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_riscos']),ucfirst($linha['demanda_config_gerenciamento_riscos']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_riscos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=risco_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_gerenciamento_qualidade']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_qualidade']),ucfirst($linha['demanda_config_gerenciamento_qualidade']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_qualidade']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=qualidade_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_gerenciamento_mudanca']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_gerenciamento_mudanca']),ucfirst($linha['demanda_config_gerenciamento_mudanca']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_gerenciamento_mudanca']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=mudanca_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_controle_mudanca']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_controle_mudanca']),ucfirst($linha['demanda_config_controle_mudanca']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_controle_mudanca']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=mudanca_controle_lista&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_aceite_produtos']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_aceite_produtos']),ucfirst($linha['demanda_config_aceite_produtos']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_aceite_produtos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=aceite_lista&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_relatorio_situacao']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_relatorio_situacao']),ucfirst($linha['demanda_config_relatorio_situacao']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_relatorio_situacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=situacao_ver&projeto_id=".$projeto_id."\");");
		if ($linha['demanda_config_ativo_termo_encerramento']) $km->Add("negapeb","eb_iniciacao",dica(ucfirst($linha['demanda_config_termo_encerramento']),ucfirst($linha['demanda_config_termo_encerramento']).' d'.($projeto['projeto_portfolio'] ? $config['genero_portfolio'].' '.$config['portfolio'] : $config['genero_projeto'].' '.$config['projeto']).'.').ucfirst($linha['demanda_config_termo_encerramento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&u=eb&a=encerramento_ver&projeto_id=".$projeto_id."\");");

		$km->Add("negapeb","eb_status",dica('Status dos Documentos','Visualizar o status dos documento d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Status dos documentos'.dicaF(), "javascript: void(0);' onclick='status_pro()");
		}
	if ($config['anexo_civil']) $km->Add("ver","artefatos", dica(ucfirst($config['artefatos']),'Visualizar '.$config['genero_artefato'].'s '.$config['artefatos'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['artefatos']).dicaF(), "javascript: void(0);' onclick='menu_anexos()");
	if ($Aplic->checarModulo('tarefas', 'adicionar')){
		$km->Add("ver","ver_eap",dica('Estrutura Analítica do Projeto - Work Breakdown Structure','Visualizar a estrutura analíticas do projeto.<br>É uma ferramenta de decomposição do trabalho d'.$config['genero_projeto'].' '.$config['projeto'].' em partes manejáveis. É estruturada em árvore exaustiva, hierárquica (de mais geral para mais específica) orientada às entregas que precisam ser feitas para completar '.($config['genero_projeto']=='a' ? 'uma' : 'um').' '.$config['projeto'].'.').'EAP (WBS)'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_vertical&projeto_id=".$projeto_id."\");");
		$km->Add("ver","ver_rapido",dica('Gantt Interativo','Exibir interface de criação e edição de '.$config['projetos'],' utilizando gráfico Gantt interativo.').'Gantt Interativo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=wbs_completo&projeto_id=".$projeto_id."\");");
		}
	if ($Aplic->checarModulo('relatorios', 'acesso')) $km->Add("ver","ver_relatorios",dica('Relatórios','Visualizar a lista de relatórios.<br><br>Os relatórios são modos convenientes de se ter uma visão panorâmica de como as divers'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].' estão se desenvolvendo.').'Relatórios'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=relatorios&a=index&projeto_id=".$projeto_id."\");");
	$km->Add("ver","ver_grafico",dica('Gráficos','Visualizar a ferramenta de gráficos customizados.').'Gráficos'.dicaF(), "javascript: void(0);' onclick='parent.gpwebApp.graficosProjeto(".$projeto_id.",".(isset($baseline_id) ? $baseline_id: 0).",\"".nome_projeto($projeto_id)."\");");
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar){
		$km->Add("acao","acao_editar",dica('Editar est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.ucfirst($config['tarefa']),'Editar os detalhes '.($config['genero_tarefa']=='o' ? 'deste' : 'desta').' '.$config['tarefa'].'.').'Editar '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=tarefas&a=editar&tarefa_id=".$obj->tarefa_id."\");");
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']),'Excluir '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' do sistema.<br><br>Todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' pertencentes a '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].' também serão excluídas.').'Excluir '.ucfirst($config['tarefa']).dicaF(), "javascript: void(0);' onclick='excluir()");
		$km->Add("acao","acao_aprovar_mo",dica('Aprovar Gasto de Mão de Obra','Acesse interface onde será possível aprovar períodos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrados.').'Aprovar Gasto com Mão de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_gasto_mo_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		$km->Add("acao","acao_aprovar_recurso",dica('Aprovar Gasto com Recurso','Acesse interface onde será possível aprovar períodos trabalhados n'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrados.').'Aprovar Gasto com Recurso'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_recurso_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		$km->Add("acao","acao_aprovar_custo",dica('Aprovar Planilha de Custo','Acesse interface onde será possível aprovar a planilha de custo d'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrada.').'Aprovar Planilha de Custo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_custos_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		$km->Add("acao","acao_aprovar_gasto",dica('Aprovar Planilha de Gasto','Acesse interface onde será possível aprovar a planilha de gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente registrada.').'Aprovar Planilha de Gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_gastos_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		
		if ($projeto['projeto_aprova_registro']) $km->Add("acao","acao_aprovar_registro",dica('Aprovar Registro de Ocorrência','Acesse interface onde será possível aprovar os registros de ocorrências d'.$config['genero_tarefa'].' '.$config['tarefa'].' previamente cadastrados.').'Aprovar Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=aprovar_registros_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
			
		$km->Add("acao","financeiro",dica('Definir Estágios da Despesa','Defina empenhado, liquidado e pago nos gastos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Definir Estágios da Despesa'.dicaF(), "javascript: void(0);");
		$km->Add("financeiro","financeiro_planilha",dica('Planilha de Gasto','Acesse interface onde será possível colocar as planilhas de gasto d'.$config['genero_tarefa'].' '.$config['tarefa'].' como empenhado, liquidado ou pago.').'Planilha de Gasto'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_planilha_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		$km->Add("financeiro","financeiro_recurso",dica('Recursos','Acesse interface onde será possível colocar os gastos com recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].' como empenhado, liquidado ou pago.').' Gasto com Recursos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_recurso_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		$km->Add("financeiro","financeiro_mo",dica('Mão de Obra','Acesse interface onde será possível colocar os gastos com mão de obra d'.$config['genero_tarefa'].' '.$config['tarefa'].' como empenhado, liquidado ou pago.').'Gasto com Mão de Obra'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=financeiro_mo_pro&projeto_id=".$projeto_id."&tarefa_id=".$obj->tarefa_id."\");");
		}
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['projetos']),'Visualizar a lista de '.($config['genero_projeto']=='o' ? 'todos os' : 'todas as').' '.$config['projetos'].'.').'Lista de '.ucfirst($config['projetos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos\");");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir decumentos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir();");
	$selecionar_baseline=(count($baselines)> 1 && $Aplic->profissional ? '<td align="right" nowrap="nowrap" style="background-color: #e6e6e6">'.dica('Baseline', 'Escolha na caixa de opção à direita a baseline que deseja visualizar.').'Baseline:'.dicaF().'</td><td nowrap="nowrap" style="background-color: #e6e6e6">'.selecionaVetor($baselines, 'baseline_id', 'class="texto" style="width:200px;" size="1" onchange="mudar_baseline();"', $baseline_id).'</td>' : '');
	echo $km->Render();
	echo '</td>'.$selecionar_baseline.'</tr>';
	echo '</table>';
	}



echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_tarefa_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '</form>';


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '</form>';


if (!$Aplic->profissional){
	$sql->adTabela('causa_efeito_tarefas');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito.causa_efeito_id=causa_efeito_tarefas.causa_efeito_id');
	$sql->adCampo('causa_efeito_tarefas.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_tarefas.tarefa_id='.$tarefa_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.$causa_efeito['causa_efeito_id'].'&tarefa_id='.$tarefa_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}
else{
	$sql->adTabela('causa_efeito_gestao');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_id=causa_efeito_gestao_causa_efeito');
	$sql->adCampo('causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_gestao_tarefa='.$tarefa_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.$causa_efeito['causa_efeito_id'].'&tarefa_id='.$tarefa_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}


if (!$Aplic->profissional){
	$sql->adTabela('gut_tarefas');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_tarefas.gut_id');
	$sql->adCampo('gut_tarefas.gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_tarefas.tarefa_id='.$tarefa_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.$gut['gut_id'].'&tarefa_id='.$tarefa_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}
else{
	$sql->adTabela('gut_gestao');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_gestao_gut');
	$sql->adCampo('gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_gestao_tarefa='.$tarefa_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.$gut['gut_id'].'&tarefa_id='.$tarefa_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}

if (!$Aplic->profissional){
	$sql->adTabela('brainstorm_tarefas');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_tarefas.brainstorm_id');
	$sql->adCampo('brainstorm_tarefas.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_tarefas.tarefa_id='.$tarefa_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.$brainstorm['brainstorm_id'].'&tarefa_id='.$tarefa_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';
	}
else{
	$sql->adTabela('brainstorm_gestao');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_gestao_brainstorm');
	$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_gestao_tarefa='.$tarefa_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.$brainstorm['brainstorm_id'].'&tarefa_id='.$tarefa_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';

	}


$cor_indicador=cor_indicador('tarefa', $tarefa_id);

$sql->adTabela('projetos');
$sql->adCampo('projeto_cor');
$sql->adOnde('projeto_id='.$obj->tarefa_projeto);
$projeto_cor=$sql->resultado();
$sql->limpar();



echo '<table cellpadding=0 cellspacing=0 width="100%"><tr><td style="border: outset #d1d1cd 1px;background-color:#'.$projeto_cor.'" colspan="2" onclick="if (document.getElementById(\'tblTarefa\').style.display){document.getElementById(\'tblTarefa\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'mostrar\').style.display=\'none\';} else {document.getElementById(\'tblTarefa\').style.display=\'none\'; document.getElementById(\'contrair\').style.display=\'none\'; document.getElementById(\'mostrar\').style.display=\'\';} if(window.resizeGrid) window.resizeGrid();"><a href="javascript: void(0);"><span id="mostrar" style="display:none">'.imagem('icones/mostrar.gif', 'Mostrar Detalhes', 'Clique neste ícone '.imagem('icones/mostrar.gif').' para mostrar os detalhes d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</span><span id="contrair">'.imagem('icones/contrair.gif', 'Ocultar Detalhes', 'Clique neste ícone '.imagem('icones/contrair.gif').' para ocultar os detalhes d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</span><font color="'.melhorCor($projeto_cor).'"><b>'.$obj->tarefa_nome.' </b>'.$cor_indicador.$saida_brainstorm.$saida_causa_efeito.$saida_gut.'</td></tr></table>';
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std" id="tblTarefa">';
echo '<tr valign="top"><td width="50%"><table width="100%" cellspacing=1 cellpadding=0>';

echo '<tr><td align="right" style="width:110px;">'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Tod'.$config['genero_tarefa'].' '.$config['tarefa'].' deve pertencer a um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td width="100%" class="realce">'.link_projeto($obj->tarefa_projeto).'</td></tr>';



if ($obj->tarefa_codigo) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Código', 'O código d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_codigo.'</td></tr>';
if ($Aplic->profissional){
	if (isset($obj->tarefa_setor) && $obj->tarefa_setor) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
	if (isset($obj->tarefa_segmento) && $obj->tarefa_segmento) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
	if (isset($obj->tarefa_intervencao) && $obj->tarefa_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
	if (isset($obj->tarefa_tipo_intervencao) && $obj->tarefa_tipo_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';
	}

if ($obj->tarefa_superior != $obj->tarefa_id)	echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica(ucfirst($config['tarefa']).' Superior', ucfirst($config['tarefa']).' de quem é sub'.$config['tarefa'].'.').ucfirst($config['tarefa']).' superior:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_tarefa($obj->tarefa_superior).'</td></tr>';

if ($obj->tarefa_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Mesmo que '.$config['genero_projeto'].' '.$config['projeto'].' seja em proveito de outr'.$config['genero_organizacao'].' '.$config['organizacao'].', deve-se selecionar '.$config['genero_organizacao'].' '.$config['organizacao'].' que será encarregada de liderar '.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;"> '.link_cia($obj->tarefa_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('tarefa_cia');
	$sql->adCampo('tarefa_cia_cia');
	$sql->adOnde('tarefa_cia_tarefa = '.(int)$tarefa_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();
	$saida_cias='';
	if (count($cias_selecionadas)) {
		$saida_cias.= '<table cellpadding=0 cellspacing=0 width=100%>';
		$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
		$qnt_lista_cias=count($cias_selecionadas);
		if ($qnt_lista_cias > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].' com '.($config['genero_tarefa']=='o' ? 'este' : 'esta').' '.$config['tarefa'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}


if ($obj->tarefa_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->tarefa_dept).'</td></tr>';
$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_depts','tarefa_depts');
$sql->adCampo('departamento_id');
$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$depts = $sql->carregarColuna();
$sql->limpar();
$saida_depts='';
if (isset($depts) && count($depts)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($depts[0]);
		$qnt_depts=count($depts);
		if ($qnt_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';
				$saida_depts.= dica('Outros '.ucfirst($config['departamentos']), 'Clique para visualizar os demais '.$config['departamentos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_depts\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';

		$plural=(count($depts)>1 ? 's' : '');
		}
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamento'.$plural]).' Envolvid'.$config['genero_dept'].$plural, ucfirst($config['departamento'.$plural]).' envolvid'.$config['genero_dept'].$plural.'  n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['departamento'.$plural]).' envolvid'.$config['genero_dept'].$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->tarefa_dono) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'], 'Tod'.$config['genero_tarefa'].' '.$config['tarefa'].' deve ter um responsável. O '.$config['usuario'].' responsável pel'.$config['genero_tarefa'].' '.$config['tarefa'].' deverá, preferencialmente, ser o encarregado de atualizar os dados no '.$config['gpweb'].', relativos a '.($config['genero_tarefa']=='a' ?  'sua' : 'seu').' '.$config['tarefa'].'.').'Responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;"> '.link_usuario($obj->tarefa_dono,'','','esquerda').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.prioridade($obj->tarefa_prioridade, false, true).'</td></tr>';
if (isset($tarefaStatus[$obj->tarefa_status])) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Status', 'O status d'.$config['genero_tarefa'].' '.$config['tarefa'].' define a situação atual da mesma').'Status:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$tarefaStatus[$obj->tarefa_status].'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Marco', '<ul><li>O marco pode ser vislumbrados como data chave de término de um grupo de  '.$config['tarefas'].'.</li><li>No gráfico Gantt será visualizado como um losângulo <font color="#FF0000">&loz;</font> vermelho.</li></ul>').'Marco:'.dicaF().'</td><td class="realce" width="300">'.($obj->tarefa_marco  ? 'Sim' : 'Não').'</td></tr>';

if ($obj->tarefa_principal_indicador && $Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'O indicador o mais representativo da situação geral d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->tarefa_principal_indicador).'</td></tr>';


//estranha a linha de baixo
if (!$obj->tarefa_marco) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Tempo Trabalhado', 'O sistema calcula o número de horas trabalhadas, baseado na percentagem concluida multiplicada pela carga horária total.').'Tempo trabalhado:'.dicaF().'</td><td class="realce" width="300">'.($obj->tarefa_horas_trabalhadas + @rtrim($obj->log_horas_trabalhadas, '0')).'</td></tr>';
if (isset($tarefa_acesso[$obj->tarefa_acesso])) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Acesso', ucfirst($config['genero_tarefa']).'s '.$config['tarefas'].' podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_tarefa'].' '.$config['tarefa'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os participantes d'.$config['genero_tarefa'].' '.$config['tarefa'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' podem ver e editar a mesma.</li><li><b>Privado</b> - Somente o responsável e '.$config['genero_usuario'].'s '.$config['usuarios'].' designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' podem ver, e o responsável editar.</li></ul>',TRUE).'Nível de acesso'.dicaF().'</td><td class="realce" width="300">'.$tarefa_acesso[$obj->tarefa_acesso].'</td></tr>';

if ($data_inicio) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Data de Início', 'Data provável de início d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Início:'.dicaF().'</td><td class="realce" width="300">'.$data_inicio->format($df.' '.$tf).'</td></tr>';
if ($data_fim) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Data de término', 'Data estimada de término d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Término:'.dicaF().'</td><td class="realce" width="300">'.$data_fim->format($df.' '.$tf).'</td></tr>';

if ($data_inicio && $data_fim && !$obj->tarefa_marco && $obj->tarefa_percentagem > 0 && $obj->tarefa_percentagem < 100){
	//Quantas horas desde  a data de início da tarefa
	$horas_faltando=((100-$obj->tarefa_percentagem)/100)*$obj->tarefa_duracao;
	$data=calculo_data_final_periodo($hoje, $horas_faltando, $obj->tarefa_cia, null, $projeto_id, null, $tarefa_id);
	echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Previsão de término calculada', 'Data estimada de término d'.$config['genero_tarefa'].' '.$config['tarefa'].' baseado na percentagem realizada até o momento.').'Previsão:'.dicaF().'</td><td class="realce" width="300">'.retorna_data($data).'</td></tr>';
	}

if ($obj->tarefa_duracao) echo '<tr><td align="right" nowrap="nowrap" valign="top" style="width:110px;">'.dica('Duração', 'A duração de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].'.').'Duração:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8), 2, ',', '.').' dia'.($obj->tarefa_duracao/($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8)  >= 2 ? 's' : '').'</td></tr>';
if ($obj->tarefa_tipo) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Categoria', 'A categoria d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Categoria:'.dicaF().'</td><td class="realce" width="300">'.getSisValorCampo('TipoTarefa',$obj->tarefa_tipo).'</td></tr>';
if ($obj->tarefa_emprego_obra) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Empregos Gerados Durante a Execução', 'O número de empregos gerados durante a execução d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos durante a execução:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_emprego_obra.'</td></tr>';
if ($obj->tarefa_emprego_direto) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Empregos Diretos Após a Conclusão', 'Onúmero de empregos diretos gerados após a conclusão d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos diretos após conclusão:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_emprego_direto.'</td></tr>';
if ($obj->tarefa_emprego_indireto) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Empregos Indiretos Após a Conclusão', 'Onúmero de empregos indiretos gerados após a conclusão d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Empregos indiretos após conclusão:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_emprego_indireto.'</td></tr>';
if ($obj->tarefa_forma_implantacao) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Forma de Implantação', 'A forma de implantação d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Forma de implantação:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_forma_implantacao.'</td></tr>';
if ($obj->tarefa_populacao_atendida) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('População atendida', 'O tipo de população atendida quando da conclusão d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'População atendida:'.dicaF().'</td><td class="realce" width="300">'.$obj->tarefa_populacao_atendida.'</td></tr>';
$unidade= getSisValor('TipoUnidade');
if ($obj->tarefa_adquirido!=0) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Quantidade Adquirida', 'A quantidade adquirida do item base para a execução d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade adquirida:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_adquirido, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
if ($obj->tarefa_previsto!=0) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Quantidade Prevista', 'A quantidade prevista a ser realizada baseado no tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade prevista:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_previsto, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
if ($obj->tarefa_realizado!=0) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Quantidade Realizada', 'A quantidade realizada baseado no tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade realizada:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_realizado, 2, ',', '.').($obj->tarefa_unidade && isset($unidade[$obj->tarefa_unidade]) ? ' '.$unidade[$obj->tarefa_unidade] : '').'</td></tr>';
if ($obj->tarefa_url_relacionada) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Link URL para '.$config['genero_tarefa'].' '.$config['tarefa'], 'O endereço URL dest'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'. O endereço URL normalmente estará contido na Intranet para consulta pelo público interno.').'Endereço URL:'.dicaF().'</td><td class="realce" width="300"><a href="'.$obj->tarefa_url_relacionada.'" target="tarefa'.$tarefa_id.'">'.$obj->tarefa_url_relacionada.'</a></td></tr>';





if ($obj->tarefa_descricao)	echo '<tr><td align="right" nowrap="nowrap" align="left" width="80">'.dica('O Que', 'Muito importante haver um breve resumo d'.$config['genero_tarefa'].' '.$config['tarefa'].', para servir de guia às atividades sucessoras e auxiliar na compreensão d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'O Que:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_descricao.'</td></tr>';
if ($obj->tarefa_porque)	echo '<tr><td align="right" nowrap="nowrap">'.dica('Por Que', 'Por que '.$config['genero_tarefa'].' '.$config['tarefa'].' será desenvolvid'.$config['genero_tarefa'].'.').'Por Que:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_porque.'</td></tr>';
if ($obj->tarefa_como)	echo '<tr><td align="right" nowrap="nowrap">'.dica('Como', 'Como '.$config['genero_tarefa'].' '.$config['tarefa'].' será desenvolvid'.$config['genero_tarefa'].'.').'Como:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_como.'</td></tr>';
if ($obj->tarefa_onde)	echo '<tr><td align="right" nowrap="nowrap">'.dica('Onde', 'Onde '.$config['genero_tarefa'].' '.$config['tarefa'].' será desenvolvid'.$config['genero_tarefa'].'.').'Onde:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_onde.'</td></tr>';
if ($obj->tarefa_situacao_atual)	echo '<tr><td align="right" nowrap="nowrap">'.dica('Situação Atual', 'Situação atual d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Situação Atual:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->tarefa_situacao_atual.'</td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_designados', 'tarefa_designados');
$sql->adCampo('usuario_id, perc_designado');
$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
$lista_designados = $sql->listaVetorChave('usuario_id', 'perc_designado');


$saida_designados='';
if (isset($lista_designados) && count($lista_designados)) {
	$designados=array();
	foreach($lista_designados as $chave => $valor) $designados[]=array('usuario_id'=> $chave, 'perc_designado'=> $valor);
		$saida_designados.= '<table cellspacing=0 cellpadding=0 width="100%">';
		$saida_designados.= '<tr><td>'.link_usuario($designados[0]['usuario_id'],'','','esquerda').' - '.number_format($designados[0]['perc_designado'], 2, ',', '.').'%';
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'],'','','esquerda').' - '.number_format($designados[$i]['perc_designado'], 2, ',', '.').'%<br>';
				$saida_designados.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_designados"><br>'.$lista.'</span>';
				}
		$saida_designados.= '</td></tr></table>';

		$plural=(count($designados)>1 ? 's' : '');
		}

if ($saida_designados) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designado'.$plural, 'Designado'.$plural.' para '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Designado'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_designados.'</td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_dependencias','tarefa_dependencias');
$sql->adCampo('dependencias_req_tarefa_id');
$sql->adOnde('dependencias_tarefa_id = '.(int)$tarefa_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$dependencias_tarefas = $sql->listaVetorChave('dependencias_req_tarefa_id','dependencias_req_tarefa_id');
$sql->limpar();


if (count($dependencias_tarefas)>1) echo '<tr><td align="right">'.dica('Predecessoras', ucfirst($config['tarefa']).' que necessitam serem cumpridas para que est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' seja executad'.$config['genero_tarefa'].'.').'Predecessoras:'.dicaF().'</td>';
else echo '<tr><td align="right">'.dica('Predecessora', ucfirst($config['tarefa']).' que necessita ser cumprida para que est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' seja executad'.$config['genero_tarefa'].'.').'Predecessora:'.dicaF().'</td>';
if (count($dependencias_tarefas)){
	$contar=0;
	echo '<td class="realce" style="text-align: justify;">';
	foreach ($dependencias_tarefas as $chave => $valor) echo ($contar++ ? '<br>' : '').link_tarefa($valor);
	}
else 	echo '<td class="realce" style="text-align: justify;">nenhuma';
echo '</td></tr>';


$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_dependencias','tarefa_dependencias');
$sql->adCampo('dependencias_tarefa_id');
$sql->adOnde('dependencias_req_tarefa_id = '.(int)$tarefa_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$tarefas_dependentes = $sql->listaVetorChave('dependencias_tarefa_id','dependencias_tarefa_id');
$sql->limpar();

if (count($tarefas_dependentes)>1) echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Sucessoras ', 'Lista de todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' que tenham est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' como predecessor'.$config['genero_tarefa'].'.').'Sucessoras:'.dicaF().'</td>';
else echo '<tr><td align="right">'.dica(ucfirst($config['tarefa']).' Sucessora ', ucfirst($config['tarefa']).'  que tenha est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' como predecessor'.$config['genero_tarefa'].'.').'Sucessora:'.dicaF().'</td>';
if (count($tarefas_dependentes)){
	$contar=0;
	echo '<td class="realce" style="text-align: justify;">';
	foreach ($tarefas_dependentes as $chave => $valor) echo ($contar++ ? '<br>' : '').link_tarefa($valor);
	}
else 	echo '<td class="realce" style="text-align: justify;">nenhuma';
echo '</td></tr>';


if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_contatos','tarefa_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('tarefa_id = '.(int)$obj->tarefa_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$contatos = $sql->carregarColuna();
	$sql->limpar();

	$saida_contatos='';
	if (isset($contatos) && count($contatos)) {
			$saida_contatos.= '<table cellspacing=0 cellpadding=0 width="100%">';
			$saida_contatos.= '<tr><td>'.link_contato($contatos[0],'','','esquerda');
			$qnt_contatos=count($contatos);
			if ($qnt_contatos > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos[$i],'','','esquerda').'<br>';
					$saida_contatos.= dica('Outros Contatos', 'Clique para visualizar os demais contatos.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_contatos\');">(+'.($qnt_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';

			$plural=(count($contatos)>1 ? 's' : '');
			}

	if ($saida_contatos) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Contato'.$plural, 'Contato'.$plural.' d'.$config['genero_tarefa'].' '.$config['tarefa'].'. No caso de inserção de dados n'.$config['genero_tarefa'].' '.$config['tarefa'].' o'.$plural.' contato'.$plural.' '.($plural ? 'poderão' : 'poderá').' ser informado'.$plural.' automaticamente por e-mail.').'Contato'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_contatos.'</td></tr>';
	}

if ($Aplic->ModuloAtivo('recursos') && $Aplic->checarModulo('recursos', 'acesso')) {
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'recurso_tarefas', 'rt');
	$sql->esqUnir('recursos', 'r', 'r.recurso_id = rt.recurso_id');
	$sql->adCampo('r.recurso_id, recurso_tipo,  rt.recurso_quantidade');
	$sql->adOnde('rt.tarefa_id = '.(int)$obj->tarefa_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOrdem('r.recurso_tipo');
	$recursos = $sql->Lista();
	$sql->limpar();

	if (count($recursos)) {
		if (count($recursos) > 1) echo '<tr><td align="right">'.dica('Recursos', 'Recursos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Recursos:'.dicaF().'</td>';
		else echo '<tr><td align="right">'.dica('Recurso', 'Recurso d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Recurso:'.dicaF().'</td>';
		$contar=0;
		echo '<td class="realce" style="text-align: justify;"><table cellspacing=0 cellpadding=0>';
		foreach ($recursos as $recurso_dados) {

			echo '<tr><td>'.link_recurso($recurso_dados['recurso_id']).' - '.(isset($recurso_dados['recurso_quantidade']) ? (isset($recurso_dados['recurso_tipo']) && $recurso_dados['recurso_tipo']==5 ? $config['simbolo_moeda'].' '.number_format($recurso_dados['recurso_quantidade'], 2, ',', '.'): number_format($recurso_dados['recurso_quantidade'], 2, ',', '.')) : '').'</td></tr>';
			}
		echo '</table></td></tr>';
		}
	}


if ($social){
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->esqUnir('social_comunidade', 'social_comunidade', 'tarefa_comunidade=social_comunidade_id');
	$sql->esqUnir('social', 'social', 'tarefa_social=social_id');
	$sql->esqUnir('social_acao', 'social_acao', 'tarefa_acao=social_acao_id');
	$sql->adOnde('tarefas.tarefa_id = '.(int)$obj->tarefa_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adCampo('social_acao_nome, social_nome, social_comunidade_nome');
	$linha = $sql->Linha();
	$sql->limpar();

	if ($linha['social_nome'])	echo '<tr><td align="right" nowrap="nowrap">'.dica('Programa Social', 'A qual programa social pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Programa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['social_nome'].'</td></tr>';
	if ($linha['social_acao_nome'])	echo '<tr><td align="right" nowrap="nowrap">'.dica('Ação Social', 'Escolha a ação social d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Ação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['social_acao_nome'].'</td></tr>';
	if ($linha['social_comunidade_nome'])	echo '<tr><td align="right" nowrap="nowrap">'.dica('Comunidade', 'A comunidade onde se aplica '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Comunidade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$linha['social_comunidade_nome'].'</td></tr>';
	}

if ($obj->tarefa_endereco1) echo '<tr valign="top"><td align="right" nowrap="nowrap">'.dica('Endereço', 'O enderço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique esta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'<a href="http://maps.google.com/maps?q='.$obj->tarefa_endereco1.'+'.$obj->tarefa_endereco2.'+'.$obj->tarefa_cidade.'+'.$obj->tarefa_estado.'+'.$obj->tarefa_cep.'+'.$obj->tarefa_pais.'" target="_blank"><img align="right" src="'.acharImagem('googlemaps.gif').'" width="60" height="22" alt="Achar no Google Maps" /></a>'.dicaF().$obj->tarefa_endereco1.(($obj->tarefa_endereco2) ? '<br />'.$obj->tarefa_endereco2 : '') .($obj->tarefa_cidade || $obj->tarefa_estado || $obj->tarefa_pais ? '<br>' : '').municipio_nome($obj->tarefa_cidade).($obj->tarefa_estado ? ' - ' : '').$obj->tarefa_estado.($obj->tarefa_pais ? ' - '.$paises[$obj->tarefa_pais] : '').(($obj->tarefa_cep) ? '<br />'.$obj->tarefa_cep : '').'</td></tr>';
if ($obj->tarefa_latitude && $obj->tarefa_longitude) echo '<tr><td align="right" nowrap="nowrap">'.dica('Coordenadas Geográficas', 'As coordenadas geográficas da localização d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Coordenadas:'.dicaF().'</td><td class="realce" width="100%">'.$obj->tarefa_latitude.'º '.$obj->tarefa_longitude.'º&nbsp;<a href="javascript: void(0);" onclick="popCoordenadas('.$obj->tarefa_latitude.','.$obj->tarefa_longitude.',0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Coordenadas', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa as coordenadas geográficas.').'</a></td></tr>';

$sql->adTabela(($baseline_id ? 'baseline_' : '').'municipio_lista','municipio_lista');
$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=municipio_lista.municipio_lista_municipio');
$sql->adCampo('DISTINCT municipios.municipio_id, municipio_nome, estado_sigla');
$sql->adOnde('municipio_lista_tarefa = '.(int)$obj->tarefa_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$sql->adOrdem('estado_sigla, municipio_nome');
$lista_municipios = $sql->Lista();
$sql->limpar();

$plural_municipio=(count($lista_municipios)>1 ? 's' : '');

$sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_area','projeto_area');
$sql->adCampo('projeto_area_id, projeto_area_nome, projeto_area_obs');
$sql->adOnde('projeto_area_tarefa IN ('.($obj->tarefas_subordinadas ? $obj->tarefas_subordinadas : $tarefa_id).')');
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$sql->adOrdem('projeto_area_nome ASC');
$lista_areas = $sql->Lista();
$sql->limpar();

$saida_areas='';
$todas_areas='';
if (isset($lista_areas) && count($lista_areas)) {
	$plural=(count($lista_areas)>1 ? 's' : '');
	$saida_areas.= '<table cellspacing=0 cellpadding=0 width="100%">';
	$saida_areas.= '<tr><td><a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$lista_areas[0]['projeto_area_id'].');">'.dica('Visualizar Área ou Ponto', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área ou ponto.'.($lista_areas[0]['projeto_area_obs'] ? '<br>'.$lista_areas[0]['projeto_area_obs'] : '')).imagem('icones/coordenadas_p.png').$lista_areas[0]['projeto_area_nome'].dicaF().'</a>';
	$qnt_lista_areas=count($lista_areas);
	if ($qnt_lista_areas > 1) {
		$lista='';
		for ($i = 1, $i_cmp = $qnt_lista_areas; $i < $i_cmp; $i++) $lista.=dica('Visualizar Área ou Ponto', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área ou ponto.'.($lista_areas[0]['projeto_area_obs'] ? '<br>'.$lista_areas[$i]['projeto_area_obs'] : '')).'<a href="javascript: void(0);" onclick="popCoordenadas(0,0,'.$lista_areas[$i]['projeto_area_id'].');">'.imagem('icones/coordenadas_p.png').$lista_areas[$i]['projeto_area_nome'].'</a>'.dicaF().'<br>';
		$saida_areas.= dica('Outras Áreas', 'Clique para visualizar as demais áreas.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_areas\');">(+'.($qnt_lista_areas - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_areas"><br>'.$lista.'</span>';
		$todas_areas=dica('Visualizar Todas as Áreas', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa todas as áreas.').'<a href="javascript: void(0);" onclick="popCoordenadas(0,0,0,'.$projeto_id.','.$tarefa_id.');">'.imagem('icones/coordenadas_p.png').'Todas as áreas</a>'.dicaF();
		}
	$saida_areas.= '</td></tr></table>';
	}
$plural=(count($lista_areas)>1 ? 's' : '');
if ($saida_areas || (count($lista_municipios) && $tem_coordenadas)) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Área'.$plural, 'Área'.$plural.' relacionada'.$plural.' com '.$config['genero_tarefa'].' '.$config['tarefa']).'Área'.$plural.':'.dicaF().'</td><td width="100%" colspan="2" class="realce"><table cellspacing=0 cellpadding=0><tr><td>'.$saida_areas.$todas_areas.(count($lista_municipios) && $tem_coordenadas ? '&nbsp;&nbsp;&nbsp;'.dica('Área'.$plural_municipio.' do'.$plural_municipio.' Município'.$plural_municipio, 'Visualizar a área do'.$plural_municipio.' município'.$plural_municipio.'.').'Município'.$plural_municipio.'<a href="javascript: void(0);" onclick="popAreaMunicipio(0,0,'.$tarefa_id.',0);">'.imagem('icones/coordenadas_p.png', 'Área'.$plural_municipio.' do'.$plural_municipio.' Município'.$plural_municipio, 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a'.$plural_municipio.' área'.$plural_municipio.' do'.$plural_municipio.' município'.$plural_municipio.' incluído'.$plural_municipio.' neste '.($config['genero_projeto']=='a' ? 'nesta': 'neste').' '.$config['projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';


$saida_municipios='';
if (isset($lista_municipios) && count($lista_municipios)) {
	$saida_municipios.= '<table cellspacing=0 cellpadding=0 width="100%">';
	$saida_municipios.= '<tr><td>'.$lista_municipios[0]['municipio_nome'].'-'.$lista_municipios[0]['estado_sigla'].($tem_coordenadas ? '<a href="javascript: void(0);" onclick="popAreaMunicipio('.$lista_municipios[0]['municipio_id'].',0,0);">'.imagem('icones/coordenadas_p.png', 'Visualizar Área do Município', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa a área do município.').'</a>' : '');
	$qnt_lista_municipios=count($lista_municipios);
	if ($qnt_lista_municipios > 1) {
		$lista='';
		for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=$lista_municipios[$i]['municipio_nome'].'-'.$lista_municipios[$i]['estado_sigla'].'<br>';
		$saida_municipios.= dica('Outros Municípios', 'Clique para visualizar os demais municípios.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
		}
	$saida_municipios.= '</td></tr></table>';
	}

if ($saida_municipios) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Município'.$plural_municipio, 'Município'.$plural_municipio.' relacionado'.$plural_municipio.' com '.$config['genero_tarefa'].' '.$config['tarefa']).'Município'.$plural_municipio.':'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_municipios.'</td></tr>';

if ($Aplic->profissional) echo '<tr><td nowrap="nowrap" align="right">'.dica('Alerta Ativo', 'Caso esteja marcado '.$config['genero_tarefa'].' '.$config['tarefa'].' será incluíd'.$config['genero_tarefa'].' no sistema de alertas automáticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.($obj->tarefa_alerta ? 'Sim' : 'Não').'</td></tr>';

require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados($m, $obj->tarefa_id, 'ver');

if ($campos_customizados->count()) {
	echo '<tr><td colspan="2">'.$campos_customizados->imprimirHTML().'</td></tr>';
	}


echo '</table></td>';
echo '<td width="50%"><table width="100%" cellspacing=1 cellpadding=0>';
if (!$obj->tarefa_marco) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Físico executado', ucfirst($config['genero_tarefa']).' '.$config['tarefa'].' pode ir de 0% (não iniciadas) até 100% (completadas).</p> No gráfico Gantt o progresso será visualizado como uma linha escura dentro do bloco horizontal d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Físico executado:'.dicaF().'</td><td class="realce" width="300">'.number_format($obj->tarefa_percentagem, 2, ',', '.').'%</td></tr>';
if ($Aplic->profissional && !$obj->tarefa_marco){
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Físico Planejado', 'O percentual d'.$config['genero_tarefa'].' '.$config['tarefa'].' previsto para a data atual.').'Físico planejado:'.dicaF().'</td><td class="realce" width="100%">'.number_format($obj->fisico_previsto($hoje, true, $baseline_id), 2, ',', '.').'%</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Velocidade do Físico', 'O razão entre o progresso e físico previsto d'.$config['genero_projeto'].' '.$config['projeto'].' para a data atual.').'Velocidade do físico:'.dicaF().'</td><td class="realce" width="100%">'.number_format($obj->fisico_velocidade($hoje, true, $baseline_id), 2, ',', '.').'</td></tr>';
	}


echo '<tr><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="exibir_financeiro();"><a href="javascript: void(0);" class="aba"><b>Financeiro</b></a></td></tr>';
echo '<tr><td colspan="3"><table width="100%" cellspacing=0 cellpadding=0 id="ver_financeiro" style="display:none"><tr><td><div id="combo_financeiro">';
echo '</td></tr></div></table></td></tr></table></td></tr>';




echo '</table></td></tr></table>';
$texto_consulta = '?m=tarefas&a=ver&tarefa_id='.$tarefa_id;
$caixaTab = new CTabBox('m=tarefas&a=ver&tarefa_id='.$tarefa_id, '', $tab);
if ($Aplic->checarModulo('tarefa_log', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_logs', 'Registros',null,null,'Registros d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']),'Visualizar os registros d'.$config['genero_tarefa'].' '.$config['tarefa'].'.');

if (($editar || $Aplic->checarModulo('tarefa_log', 'editar')) && !$Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_log_atualizar', 'Registrar',null,null,'Registrar','Inserir um novo registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.');

if (count($obj->getSubordinada()) > 0) {
	$f = 'subordinada';
	$ver_min = true;
	$_REQUEST['tarefa_status'] = $obj->tarefa_status;
	if($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas_projeto_pro', ucfirst($config['tarefa']).' Subordinadas',null,null,ucfirst($config['tarefa']).' Subordinadas','Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinadas (tarefas filho).');
	else $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/tarefas', ucfirst($config['tarefa']).' Subordinadas',null,null,ucfirst($config['tarefa']).' Subordinadas','Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' subordinadas (tarefas filho).');
	}
if (count($caixaTab->tabs)) $caixaTab_mostrar = 1;



if($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_gantt_pro', 'Gantt',null,null,'Gráfico Gantt','Visualizar o gráfico Gantt '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');
else $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/ver_gantt', 'Gráfico Gantt',null,null,'Gráfico Gantt','Visualizar o gráfico Gantt '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.');

if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/tarefas/entrega_lista_pro', 'Entregas',null,null,'Entregas','Visualizar as entregas a '.($config['genero_tarefa']=='a' ? 'esta ': 'este ').$config['tarefa'].'.');
if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fóruns relacionados.');
if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
	$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
	}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');




$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa();


?>

<script language="JavaScript">

function menu_anexos(){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Menu dos Artefatos", 500, 400, 'm=projetos&a=menu_anexos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value, window.url_passar, window);
	else window.open('./index.php?m=projetos&a=menu_anexos&dialogo=1&projeto_id='+document.getElementById('projeto_id').value, 'Menu dos Artefatos','height=400,width=500px,resizable,scrollbars=yes');
	}

function planilha_gasto_recurso(financeiro){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Recursos", 1024, 600, 'm=projetos&a=planilha_recurso&dialogo=1&baseline_id='+baseline_id+'&financeiro='+financeiro+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, null, window);
	else window.open('./index.php?m=projetos&a=planilha_recurso&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}

function planilha_custo_recurso(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
  if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Planilha de Recursos", 1024, 600, 'm=tarefas&a=lista_recursos&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, null, window);
	else window.open('./index.php?m=tarefas&a=lista_recursos&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, 'Planilha','height=500,width=1024,resizable,scrollbars=yes');
	}

function imprimir(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Imprimir', 1020, 500, 'm=tarefas&a=imprimir_selecionar_pro&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, null, window);
	else window.open('index.php?m=tarefas&a=imprimir_selecionar_pro&dialogo=1&baseline_id='+baseline_id+'&projeto_id='+document.getElementById('projeto_id').value+'&tarefa_id='+document.getElementById('tarefa_id').value, 'imprimir','width=1020, height=800, menubar=1, scrollbars=1');
	}

function mudar_baseline(){
	url_passar(0, 'm=tarefas&a=ver&tab=<?php echo $tab ?>&tarefa_id=<?php echo $tarefa_id ?>&baseline_id='+document.getElementById('baseline_id').value);
	}

function popAreaMunicipio(municipio_id, projeto_id, tarefa_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Área', 770, 467, 'm=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas_municipios&dialogo=1'+(municipio_id ? '&municipio_id='+municipio_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), 'Ver Área','height=467,width=770px,resizable,scrollbars=no');
	}

function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}




function popCoordenadas(latitude, longitude, projeto_area_id, projeto_id, tarefa_id){
	if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Ver Coordenada",  770, 467, 'm=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : '')+(projeto_area_id ? '&projeto_area_id='+projeto_area_id : '')+(projeto_id ? '&projeto_id='+projeto_id : '')+(tarefa_id ? '&tarefa_id='+tarefa_id : ''), 'Ver Coordenada','height=467,width=770px,resizable,scrollbars=no');
	}

function excluir() {
	if (confirm( 'Tem certeza que deseja excluir <?php echo $config["genero_tarefa"]." ".$config["tarefa"]?>?'))	document.frmExcluir.submit();
	}


var financeiro_carregado=0;

function exibir_financeiro(){
	var baseline_id = 0;
	if(document.getElementById('baseline_id')) baseline_id = document.getElementById('baseline_id').value;
	if (!financeiro_carregado) {
		xajax_exibir_financeiro(document.getElementById('tarefa_id').value, baseline_id);
		__buildTooltip();
		}

	if (document.getElementById('ver_financeiro').style.display) document.getElementById('ver_financeiro').style.display='';
	else document.getElementById('ver_financeiro').style.display='none';

	financeiro_carregado=1;

	}

</script>
