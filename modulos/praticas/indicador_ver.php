<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pratica_indicador_acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));

$msg = '';

if (isset($_REQUEST['tab'])) $Aplic->setEstado('IndicadorVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('IndicadorVerTab') !== null ? $Aplic->getEstado('IndicadorVerTab') : 0;

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('DISTINCT pratica_indicador_requisito_ano');
$sql->adOnde('pratica_indicador_requisito_indicador='.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_requisito_ano');
$anos=array(''=>'')+$sql->listaVetorChave('pratica_indicador_requisito_ano','pratica_indicador_requisito_ano');
$sql->limpar();

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos_pauta=array(''=>'')+$sql->listaVetorChave('pratica_modelo_id', 'pratica_modelo_nome');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) ? $Aplic->getEstado('IdxIndicadorAno') : null);

$sql->adTabela('pratica_indicador');
if (!$ano) $sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
else {
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adOnde('pratica_indicador_requisito_ano='.(int)$ano);
	}

$sql->adCampo('pratica_indicador.*, pratica_indicador_requisito.*');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();

if (!($podeAcessar && permiteAcessarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
//tendencia
$tendencia=0;
$obj = new Indicador($pratica_indicador_id, $ano);

$valor=$obj->Valor_atual($pratica_indicador['pratica_indicador_agrupar'], $ano);

$editar=($Aplic->checarModulo('praticas', 'editar', null, 'indicador') && permiteEditarIndicador($pratica_indicador['pratica_indicador_acesso'],$pratica_indicador_id));
$permite_inserir_valor=($pratica_indicador['pratica_indicador_acesso']==4	? ($Aplic->checarModulo('praticas', 'editar', null, 'indicador') && permiteEditarIndicador(1,$pratica_indicador_id)) : $editar);

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="indicador_ver" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '<input type="hidden" name="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="pratica_indicador_nome" value="" />';


$botoesTitulo = new CBlocoTitulo('Detalhes do Indicador', 'indicador.gif', $m, $m.'.'.$a);


if (!$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos_pauta, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto"', $pratica_modelo_id).'</td></tr><tr><td>'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados do indicador inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxIndicadorAno', 'onchange="env.submit()" class="texto"', $ano).'</td></tr></table>');
	
	if ($editar){
		//$botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Indicador', 'Criar um novo indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=indicador_editar\');" ><span>indicador</span></a>'.dicaF().'</td></tr></table>');
		$botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar '.$config['acao'].' relacionad'.$config['genero_acao'].' relacionad'.$config['genero_acao'].' a este indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_indicador='.(int)$pratica_indicador_id.'\');" ><span>'.strtolower($config['acao']).'</span></a>'.dicaF().'</td></tr></table>');
		if(!$config['termo_abertura_obrigatorio'] && $Aplic->checarModulo('projetos', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Criar nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].' a este indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=editar&projeto_indicador='.(int)$pratica_indicador_id.'\');" ><span>'.strtolower($config['projeto']).'</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Arquivo', 'Inserir um novo arquivo relacionado a este indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=arquivos&a=editar&arquivo_indicador='.(int)$pratica_indicador_id.'\');" ><span>arquivo</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Evento', 'Criar um novo evento relacionado a este indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=calendario&a=editar&evento_indicador='.(int)$pratica_indicador_id.'\');" ><span>evento</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Link', 'Inserir um novo link relacionado a este indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=links&a=editar&link_indicador='.(int)$pratica_indicador_id.'\');" ><span>link</span></a>'.dicaF().'</td></tr></table>');
		}
	$botoesTitulo->adicionaBotao('m=praticas&a=indicador_lista', 'lista','','Lista de Indicadores','Visualizar a lista de todos os indicadores.');
	if ($pratica_indicador['pratica_indicador_tarefa'])$botoesTitulo->adicionaBotao('m=tarefas&a=ver&tarefa_id='.(int)$pratica_indicador['pratica_indicador_tarefa'], strtolower($config['tarefa']),'',ucfirst($config['tarefa']),'Visualizar '.$config['genero_tarefa'].' '.$config['tarefa'].' d'.$config['genero_tarefa'].' qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_projeto'])$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.(int)$pratica_indicador['pratica_indicador_projeto'], strtolower($config['projeto']),'',ucfirst($config['projeto']),'Visualizar '.$config['genero_projeto'].' '.$config['projeto'].' d'.$config['genero_projeto'].' qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_tema'])$botoesTitulo->adicionaBotao('m=praticas&a=tema_ver&tema_id='.(int)$pratica_indicador['pratica_indicador_tema'], 'tema','',ucfirst($config['tema']),'Visualizar '.$config['genero_tema'].' '.$config['tema'].' do qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_objetivo_estrategico'])$botoesTitulo->adicionaBotao('m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.(int)$pratica_indicador['pratica_indicador_objetivo_estrategico'], $config['objetivo'],'',ucfirst($config['objetivo']),'Visualizar '.$config['genero_objetivo'].' '.$config['objetivo'].' do qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_acao'])$botoesTitulo->adicionaBotao('m=praticas&a=plano_acao_ver&plano_acao_id='.(int)$pratica_indicador['pratica_indicador_acao'], strtolower($config['acao']),'',ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].' '.$config['acao'].' d'.$config['genero_acao'].' qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_fator'])$botoesTitulo->adicionaBotao('m=praticas&a=fator_ver&pg_fator_critico_id='.(int)$pratica_indicador['pratica_indicador_fator'], $config['fator'],'',ucfirst($config['fator']),'Visualizar '.$config['genero_fator'].' '.$config['fator'].' d'.$config['genero_fator'].' qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_estrategia'])$botoesTitulo->adicionaBotao('m=praticas&a=estrategia_ver&pg_estrategia_id='.(int)$pratica_indicador['pratica_indicador_estrategia'], $config['iniciativa'],'',ucfirst($config['iniciativa']),'Visualizar '.$config['genero_iniciativa'].' '.$config['iniciativa'].' que este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_perspectiva'])$botoesTitulo->adicionaBotao('m=praticas&a=perspectiva_ver&pg_perspectiva_id='.(int)$pratica_indicador['pratica_indicador_perspectiva'], strtolower($config['perspectiva']),'',ucfirst($config['perspectiva']),'Visualizar '.$config['genero_perspectiva'].' '.$config['perspectiva'].' d'.$config['genero_perspectiva'].' qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_meta'])$botoesTitulo->adicionaBotao('m=praticas&a=meta_ver&pg_meta_id='.(int)$pratica_indicador['pratica_indicador_meta'], 'meta','','Meta','Visualizar a meta da qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_pratica'])$botoesTitulo->adicionaBotao('m=praticas&a=pratica_ver&pratica_id='.(int)$pratica_indicador['pratica_indicador_pratica'], strtolower($config['pratica']),'',ucfirst($config['pratica']),'Visualizar '.$config['genero_pratica'].' '.$config['pratica'].' d'.$config['genero_pratica'].' qual este indicador faz parte.');
	elseif ($pratica_indicador['pratica_indicador_checklist'])$botoesTitulo->adicionaBotao('m=praticas&a=checklist_ver&checklist_id='.(int)$pratica_indicador['pratica_indicador_checklist'],'checklist','','Checklist','Visualizar o checklist do qual este indicador faz parte.');
	
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas&a=indicador_editar&pratica_indicador_id='.(int)$pratica_indicador_id, 'editar','','Editar este Indicador','Editar os detalhes deste indicador.');
		if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao']) $botoesTitulo->adicionaBotao('m=praticas&a=indicador_editar_valor&pratica_indicador_id='.(int)$pratica_indicador_id, 'inserir valor','','Inserir Valor','Inserir valor neste indicador.');
		elseif ($pratica_indicador['pratica_indicador_checklist']) $botoesTitulo->adicionaBotao('m=praticas&a=checklist_editar_valor&pratica_indicador_id='.(int)$pratica_indicador_id, 'preencher checklist','','Preencher Checklist','Inserir respostas para o checklist deste indicador.');
		if ($Aplic->checarModulo('praticas', 'excluir', null, 'indicador')) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir este Indicador','Excluir este indicador do sistema.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Indicador', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o indicador.').'<a href="javascript: void(0);" onclick="window.open(\'index.php?m=praticas&a=imprimir_indicador&dialogo=1&sem_cabecalho=1&tipo=1&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'imprimir_indicador\',\'width=900, height=900, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());

	}

$botoesTitulo->mostrar();




$data = ($obj->pratica_indicador_data_meta!=null ? new CData($obj->pratica_indicador_data_meta) : new CData());
$data_desde = isset($pratica_indicador['pratica_indicador_desde_quando']) ? new CData($pratica_indicador['pratica_indicador_desde_quando']) : new CData();
$df = '%d/%m/%Y';


echo estiloTopoCaixa();


if ($Aplic->profissional){
	$Aplic->salvarPosicao();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Indicadores','Visualizar a lista de Indicadores.').'Lista de Indicadores'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_lista\");");
	
	
	if ($editar || $permite_inserir_valor) $km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
	if ($editar){
		$km->Add("inserir","inserir_novo",dica('Novo Indicador', 'Criar um novo indicador.').'Novo Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar\");");
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&pratica_indicador_id=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_indicador=".$pratica_indicador_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_indicador=".$pratica_indicador_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_indicador=".$pratica_indicador_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_indicador=".$pratica_indicador_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_indicador=".$pratica_indicador_id."\");");
		}
	
	if ($permite_inserir_valor){	
		if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao'] && !$pratica_indicador['pratica_indicador_externo']) $km->Add("inserir","inserir_valor",dica('Valor','Inserir valor neste indicador.').'Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");			
		elseif ($pratica_indicador['pratica_indicador_checklist']) $km->Add("inserir","inserir_valor",dica('Valor de Checklist','Inserir respostas para o checklist deste indicador.').'Valor de Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");	
		elseif ($pratica_indicador['pratica_indicador_formula_simples']) $km->Add("inserir","inserir_valor",dica('Valor','Inserir valor neste indicador.').'Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");					
		elseif ($pratica_indicador['pratica_indicador_externo']) $km->Add("inserir","inserir_valor",dica('Importar Valor','Importar valor de base externa para este indicador.').'Importar Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");					
		}
	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar){	
		$km->Add("acao","acao_editar",dica('Editar', 'Editar os detalhes deste indicador.').'Editar Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&&pratica_indicador_id=".(int)$pratica_indicador_id."\");");			
		if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao']) $km->Add("acao","acao_valor",dica('Inserir Valor','Inserir valor neste indicador.').'Inserir Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");			
		elseif ($pratica_indicador['pratica_indicador_checklist']) $km->Add("acao","acao_valor",dica('Valor de Checklist','Inserir respostas para o checklist deste indicador.').'Valor de Checklist'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=checklist_editar_valor&pratica_indicador_id=".(int)$pratica_indicador_id."\");");			
		elseif ($pratica_indicador['pratica_indicador_formula_simples']) $km->Add("acao","acao_valor",dica('Inserir Valor','Inserir valor neste indicador.').'Inserir Valor'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar_valor_pro&pratica_indicador_id=".(int)$pratica_indicador_id."\");");	
		$km->Add("acao","acao_valor",dica('Avaliação dos Resultados','Inserir as causas de sucesso ou fracasso do indicador.').'Avaliação dos Resultados'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_avaliacao&pratica_indicador_id=".(int)$pratica_indicador_id."\");");			
		
		$km->Add("acao","acao_duplicar",dica('Duplicar','Duplicar o indicador, criando uma cópia idêntica, mas sem qualquer valor inserido.').'Duplicar o Indicador'.dicaF(), "javascript: void(0);' onclick='duplicar_indicador();");			
		
		if ($podeExcluir) $km->Add("acao","acao_excluir",dica('Excluir este Indicador','Excluir este indicador do sistema.').'Excluir Indicador'.dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir os detalhes do indicador.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='imprimir(".$pratica_indicador_id.");");
	echo $km->Render();
	echo '<td  style="background-color: #e6e6e6" nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos_pauta, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto"', $pratica_modelo_id).'&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados do indicador inseridos no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxIndicadorAno', 'onchange="env.submit()" class="texto"', $ano).'</td></tr>';
	echo '</table>';
	}



echo '</form>';



if (!$Aplic->profissional){
	$sql->adTabela('causa_efeito_indicadores');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito.causa_efeito_id=causa_efeito_indicadores.causa_efeito_id');
	$sql->adCampo('causa_efeito_indicadores.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_indicadores.pratica_indicador_id='.(int)$pratica_indicador_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.(int)$causa_efeito['causa_efeito_id'].'&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}
else{
	$sql->adTabela('causa_efeito_gestao');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_id=causa_efeito_gestao_causa_efeito');
	$sql->adCampo('causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_gestao_indicador='.(int)$pratica_indicador_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.(int)$causa_efeito['causa_efeito_id'].'&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}



if (!$Aplic->profissional){
	$sql->adTabela('gut_indicadores');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_indicadores.gut_id');
	$sql->adCampo('gut_indicadores.gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_indicadores.pratica_indicador_id='.(int)$pratica_indicador_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.(int)$gut['gut_id'].'&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}
else{
	$sql->adTabela('gut_gestao');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_gestao_gut');
	$sql->adCampo('gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_gestao_indicador='.(int)$pratica_indicador_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.(int)$gut['gut_id'].'&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}

if (!$Aplic->profissional){
	$sql->adTabela('brainstorm_indicadores');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_indicadores.brainstorm_id');
	$sql->adCampo('brainstorm_indicadores.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_indicadores.pratica_indicador_id='.(int)$pratica_indicador_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.(int)$brainstorm['brainstorm_id'].'&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';
	}
else{
	$sql->adTabela('brainstorm_gestao');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_gestao_brainstorm');
	$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_gestao_indicador='.(int)$pratica_indicador_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.(int)$brainstorm['brainstorm_id'].'&pratica_indicador_id='.(int)$pratica_indicador_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';

	}	


echo '<table id="tblPraticas" border=0 cellpadding=0 cellspacing=1 width="100%" class="std"><tr><td style="border: outset #d1d1cd 1px;background-color:#'.$pratica_indicador['pratica_indicador_cor'].' !important;color:#'.melhorCor($pratica_indicador['pratica_indicador_cor']).' !important;" colspan=2 class="realce" onclick="if (document.getElementById(\'tblProjetos\').style.display) {document.getElementById(\'tblProjetos\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'contrair\').style.display=\'\'; document.getElementById(\'mostrar\').style.display=\'none\';} else {document.getElementById(\'tblProjetos\').style.display=\'none\'; document.getElementById(\'contrair\').style.display=\'none\'; document.getElementById(\'mostrar\').style.display=\'\';} if(window.onResizeDetalhesProjeto) window.onResizeDetalhesProjeto(); xajax_painel_indicador(document.getElementById(\'tblProjetos\').style.display);"><a href="javascript: void(0);"><span id="mostrar" style="display:none">'.imagem('icones/mostrar.gif', 'Mostrar Detalhes', 'Clique neste ícone '.imagem('icones/mostrar.gif').' para mostrar os detalhes do indicador.').'</span><span id="contrair">'.imagem('icones/contrair.gif', 'Ocultar Detalhes', 'Clique neste ícone '.imagem('icones/contrair.gif').' para ocultar os detalhes do indicador.').'</span><b>'.$pratica_indicador['pratica_indicador_nome'].'<b>'.$saida_brainstorm.$saida_causa_efeito.$saida_gut.'</td></tr></table>';





$painel_indicador = $Aplic->getEstado('painel_indicador') !== null ? $Aplic->getEstado('painel_indicador') : 1;
echo '<table id="tblProjetos" cellpadding=0 cellspacing=0 width="100%" class="std" style="display:'.($painel_indicador ? '' : 'none').'">';











echo '<tr><td width="50%" valign="top"><table cellspacing=1 cellpadding=0 border=0 width="100%">';
if ($pratica_indicador['pratica_indicador_cia']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável por este indicador.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($pratica_indicador['pratica_indicador_cia']).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('indicador_cia');
	$sql->adCampo('indicador_cia_cia');
	$sql->adOnde('indicador_cia_indicador = '.(int)$pratica_indicador_id);
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
				$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
				}
		$saida_cias.= '</td></tr></table>';
		}
	if ($saida_cias) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_cias.'</td></tr>';
	}
if ($pratica_indicador['pratica_indicador_dept']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por este indicador.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($pratica_indicador['pratica_indicador_dept']).'</td></tr>';
$sql->adTabela('pratica_indicador_depts');
$sql->adCampo('pratica_indicador_depts.dept_id');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$departamentos = $sql->carregarColuna();
$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$saida_depts.'</td></tr>';

if ($pratica_indicador['pratica_indicador_responsavel']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo Indicador', 'Todo indicador deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan=2 class="realce">'.link_usuario($pratica_indicador['pratica_indicador_responsavel'],'','','esquerda').'</td></tr>';


$sql->adTabela('pratica_indicador_usuarios', 'pratica_indicador_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_indicador_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, contato_dept');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$participantes = $sql->Lista();
$sql->limpar();

$saida_quem='';
if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$saida_quem.'</td></tr>';
	

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adCampo('pratica_indicador_gestao.*');
	$sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_gestao_ordem');
	$lista = $sql->Lista();
	$sql->Limpar();
	if (count($lista)){
		
		if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';
		$ata_ativo=$Aplic->modulo_ativo('atas');
		if ($ata_ativo) require_once BASE_DIR.'/modulos/atas/funcoes.php';
		$swot_ativo=$Aplic->modulo_ativo('swot');
		if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
		$operativo_ativo=$Aplic->modulo_ativo('operativo');
		if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
		$problema_ativo=$Aplic->modulo_ativo('problema');
		if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
		$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');
		if($agrupamento_ativo) require_once BASE_DIR.'/modulos/agrupamento/funcoes.php';
		$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');
		if($patrocinador_ativo) require_once BASE_DIR.'/modulos/patrocinadores/patrocinadores.class.php';
		
		$usado=0;
		echo '<tr><td align="right">'.dica('Relacionada','Áreas as quais este indicador está relacionada.').'Relacionado:'.dicaF().'</td><td class="realce" width="100%">';
		foreach($lista as $gestao_data){
			if ($gestao_data['pratica_indicador_gestao_tarefa']) echo ($usado++? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_indicador_gestao_tarefa']);
			elseif ($gestao_data['pratica_indicador_gestao_projeto']) echo ($usado++? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_indicador_gestao_projeto']);
			elseif ($gestao_data['pratica_indicador_gestao_pratica']) echo ($usado++? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_indicador_gestao_pratica']);
			elseif ($gestao_data['pratica_indicador_gestao_acao']) echo ($usado++? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_indicador_gestao_acao']);
			elseif ($gestao_data['pratica_indicador_gestao_perspectiva']) echo ($usado++? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_indicador_gestao_perspectiva']);
			elseif ($gestao_data['pratica_indicador_gestao_tema']) echo ($usado++? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['pratica_indicador_gestao_tema']);
			elseif ($gestao_data['pratica_indicador_gestao_objetivo']) echo ($usado++? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_indicador_gestao_objetivo']);
			elseif ($gestao_data['pratica_indicador_gestao_fator']) echo ($usado++? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_indicador_gestao_fator']);
			elseif ($gestao_data['pratica_indicador_gestao_estrategia']) echo ($usado++? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_indicador_gestao_estrategia']);
			elseif ($gestao_data['pratica_indicador_gestao_meta']) echo ($usado++? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_indicador_gestao_meta']);
			elseif ($gestao_data['pratica_indicador_gestao_canvas']) echo ($usado++? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_indicador_gestao_canvas']);
			elseif ($gestao_data['pratica_indicador_gestao_risco']) echo ($usado++? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['pratica_indicador_gestao_risco']);
			elseif ($gestao_data['pratica_indicador_gestao_risco_resposta']) echo ($usado++? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_indicador_gestao_risco_resposta']);
			elseif ($gestao_data['pratica_indicador_gestao_calendario']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['pratica_indicador_gestao_calendario']);
			elseif ($gestao_data['pratica_indicador_gestao_monitoramento']) echo ($usado++? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_indicador_gestao_monitoramento']);
			elseif ($gestao_data['pratica_indicador_gestao_ata']) echo ($usado++? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['pratica_indicador_gestao_ata']);
			elseif ($gestao_data['pratica_indicador_gestao_swot']) echo ($usado++? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['pratica_indicador_gestao_swot']);
			elseif ($gestao_data['pratica_indicador_gestao_operativo']) echo ($usado++? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_indicador_gestao_operativo']);
			elseif ($gestao_data['pratica_indicador_gestao_instrumento']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_indicador_gestao_instrumento']);
			elseif ($gestao_data['pratica_indicador_gestao_recurso']) echo ($usado++? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_indicador_gestao_recurso']);
			elseif ($gestao_data['pratica_indicador_gestao_problema']) echo ($usado++? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['pratica_indicador_gestao_problema']);
			elseif ($gestao_data['pratica_indicador_gestao_demanda']) echo ($usado++? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_indicador_gestao_demanda']);
			elseif ($gestao_data['pratica_indicador_gestao_programa']) echo ($usado++? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['pratica_indicador_gestao_programa']);
			elseif ($gestao_data['pratica_indicador_gestao_licao']) echo ($usado++? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_indicador_gestao_licao']);
			elseif ($gestao_data['pratica_indicador_gestao_evento']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_indicador_gestao_evento']);
			elseif ($gestao_data['pratica_indicador_gestao_link']) echo ($usado++? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['pratica_indicador_gestao_link']);
			elseif ($gestao_data['pratica_indicador_gestao_avaliacao']) echo ($usado++? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_indicador_gestao_avaliacao']);
			elseif ($gestao_data['pratica_indicador_gestao_tgn']) echo ($usado++? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_indicador_gestao_tgn']);
			elseif ($gestao_data['pratica_indicador_gestao_brainstorm']) echo ($usado++? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['pratica_indicador_gestao_brainstorm']);
			elseif ($gestao_data['pratica_indicador_gestao_gut']) echo ($usado++? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['pratica_indicador_gestao_gut']);
			elseif ($gestao_data['pratica_indicador_gestao_causa_efeito']) echo ($usado++? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['pratica_indicador_gestao_causa_efeito']);
			elseif ($gestao_data['pratica_indicador_gestao_arquivo']) echo ($usado++? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_indicador_gestao_arquivo']);
			elseif ($gestao_data['pratica_indicador_gestao_forum']) echo ($usado++? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_indicador_gestao_forum']);
			elseif ($gestao_data['pratica_indicador_gestao_checklist']) echo ($usado++? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_indicador_gestao_checklist']);
			elseif ($gestao_data['pratica_indicador_gestao_agenda']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['pratica_indicador_gestao_agenda']);
			elseif ($gestao_data['pratica_indicador_gestao_agrupamento']) echo ($usado++? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['pratica_indicador_gestao_agrupamento']);
			elseif ($gestao_data['pratica_indicador_gestao_patrocinador']) echo ($usado++? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_indicador_gestao_patrocinador']);
			elseif ($gestao_data['pratica_indicador_gestao_template']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['pratica_indicador_gestao_template']);
			elseif ($gestao_data['pratica_indicador_gestao_painel']) echo ($usado++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['pratica_indicador_gestao_painel']);
			elseif ($gestao_data['pratica_indicador_gestao_painel_odometro']) echo ($usado++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_indicador_gestao_painel_odometro']);
			elseif ($gestao_data['pratica_indicador_gestao_painel_composicao']) echo ($usado++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['pratica_indicador_gestao_painel_composicao']);		
			elseif ($gestao_data['pratica_indicador_gestao_tr']) echo ($usado++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['pratica_indicador_gestao_tr']);		
			elseif ($gestao_data['pratica_indicador_gestao_me']) echo ($usado++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['pratica_indicador_gestao_me']);		
			}
		echo '</td></tr>';
		}
	}

if ($pratica_indicador['pratica_indicador_requisito_descricao']) echo '<tr><td align="right">'.dica('Descrição', 'Descrição do indicador.').'Descrição:'.dicaF().'</td><td class="realce" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica_indicador['pratica_indicador_requisito_descricao'].'</td></tr>';
if ($Aplic->profissional){
	if ($pratica_indicador['pratica_indicador_ano']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano Inicial', 'O ano em que se iniciou a utilização deste indicador.').'Ano inicial:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$pratica_indicador['pratica_indicador_ano'].'</td></tr>';
	if ($pratica_indicador['pratica_indicador_codigo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'O código do indicador.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$pratica_indicador['pratica_indicador_codigo'].'</td></tr>';
	$obj_indicador = new CIndicador($pratica_indicador_id);
	$obj_indicador->load($pratica_indicador_id);
	if ($pratica_indicador['pratica_indicador_setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o indicador.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getSetor().'</td></tr>';
	if ($pratica_indicador['pratica_indicador_segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o indicador.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getSegmento().'</td></tr>';
	if ($pratica_indicador['pratica_indicador_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o indicador.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getIntervencao().'</td></tr>';
	if ($pratica_indicador['pratica_indicador_tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o indicador.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj_indicador->getTipoIntervencao().'</td></tr>';
	}



if ($pratica_indicador['pratica_indicador_composicao']){
	
	$sql->adTabela('pratica_indicador_composicao');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_id=pratica_indicador_composicao_filho');
	$sql->adCampo('pratica_indicador_composicao_filho, pratica_indicador_composicao_peso, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_composicao_pai ='.(int)$pratica_indicador_id);
	$composicao = $sql->Lista();
	$sql->limpar();
	
	$saida_composicao='';
	if ($composicao && count($composicao)) {
		$saida_composicao.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_composicao.= '<tr><td>'.number_format($composicao[0]['pratica_indicador_composicao_peso'], 2, '.', '').' - '.link_indicador($composicao[0]['pratica_indicador_composicao_filho']);
		$qnt_composicao=count($composicao);
		if ($qnt_composicao > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_composicao; $i < $i_cmp; $i++) $lista.=number_format($composicao[$i]['pratica_indicador_composicao_peso'], 2, '.', '').' - '.link_indicador($composicao[$i]['pratica_indicador_composicao_filho']).'<br>';		
				$saida_composicao.= dica('Outros Indicadores', 'Clique para visualizar os demais indicadores.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'composicao\');">(+'.($qnt_composicao - 1).')</a>'.dicaF(). '<span style="display: none" id="composicao"><br>'.$lista.'</span>';
				}
		$saida_composicao.= '</td></tr></table>';
		} 
	
	if ($saida_composicao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Composição', 'Este indicador é composto da média ponderada de outros indicadores.').'Composição:'.dicaF().'</td><td class="realce" width="100%"><table cellpadding=0 cellspacing=0><tr><td>'.$saida_composicao.'</td><td><a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Composição\', 830, 630, \'m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/indicador_exp_p.png','Composição','Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição do indicador.').'</a></td></tr></table></td></tr>';
	}

if ($pratica_indicador['pratica_indicador_requisito_oque']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata este indicador.').'O Que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_oque'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_porque']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que este indicador será mensurado.').'Por que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_porque'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_onde']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Onde Fazer', 'Onde o indicador é mensurado.').'Onde:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_onde'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quando']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quando Fazer', 'Quando o indicador é mensurado.').'Quando:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_quando'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_como']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Como Fazer', 'Como  o indicador é mensurado.').'Como:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_como'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quanto']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para mensurar o indicador.').'Quanto:'.dicaF().'</td><td class="realce" width="100%">'.$pratica_indicador['pratica_indicador_requisito_quanto'].'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quem']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão encarregados deste indicador.').'Quem:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$pratica_indicador['pratica_indicador_requisito_quem'].'</td></tr>';
	

		
if ($pratica_indicador['pratica_indicador_tipo']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Tipo', 'O tipo de indicador.').'Tipo:'.dicaF().'</td><td class="realce" width="100%">'.getSisValorCampo('IndicadorTipo',$pratica_indicador['pratica_indicador_tipo']).'</td></tr>';

echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Nível de Acesso', 'Os indicadores podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o indicador.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o indicador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar e os designados inserir valores quando for o caso.</li><li><b>Participante</b> - Somente o responsável e os designados para o indicador podem ver e editar o mesmo</li><li><b>Privado</b> - Somente o responsável e os designados para o indicador podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$pratica_indicador_acesso[$pratica_indicador['pratica_indicador_acesso']].'</td></tr>';



if ($pratica_indicador['pratica_indicador_formula'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula');
	$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador_id=pratica_indicador_formula_filho');
	$sql->esqUnir('cias','cias', 'cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_formula_filho, pratica_indicador_formula_ordem, cia_nome, pratica_indicador_formula_rocado');
	$sql->adOnde('pratica_indicador_formula_pai ='.(int)$pratica_indicador_id);
	$lista_formula = $sql->Lista();
	
	$saida_formula='';
	$lista='';
	if ($lista_formula && count($lista_formula)) {
			$saida_formula.= '<table cellspacing=0 cellpadding=0 width=100%>';
			$saida_formula.= '<tr><td>'.$pratica_indicador['pratica_indicador_calculo'];
			$qnt_lista_formula=count($lista_formula);
			foreach ($lista_formula as $formula) {
				$lista.='I'.($formula['pratica_indicador_formula_ordem']< 10 ? '0' : '').$formula['pratica_indicador_formula_ordem'].' - '.link_indicador($formula['pratica_indicador_formula_filho']).' - '.$formula['cia_nome'].($formula['pratica_indicador_formula_rocado'] ? ' - deslocado de '.$formula['pratica_indicador_formula_rocado']  : '').'<br>';		
				}
			
			$saida_formula.= dica('Legenda dos Indicadores', 'Clique para visualizar a legenda dos indicadores que compoem a fórmula.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_formula\');">('.($qnt_lista_formula).')</a>'.dicaF(). '<div style="display: none" id="lista_formula"><br>'.$lista.'</div>';
			$saida_formula.= '</td></tr></table>';
			} 
	echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Fórmula', 'Fórmula.').'Fórmula:'.dicaF().'</td><td class="realce" width="100%">'.$saida_formula.'</td></tr>';
	}
	

if ($pratica_indicador['pratica_indicador_formula_simples'] && $pratica_indicador['pratica_indicador_calculo']) {
	$sql->adTabela('pratica_indicador_formula_simples');
	$sql->adOnde('pratica_indicador_formula_simples_indicador = '.(int)$pratica_indicador_id);
	$sql->adCampo('pratica_indicador_formula_simples.*');
	$sql->adOrdem('ordem');
	$lista_formula = $sql->Lista();
	$sql->limpar();
	
	$saida_formula='';
	$lista='';
	if ($lista_formula && count($lista_formula)) {
			$saida_formula.= '<table cellspacing=0 cellpadding=0 border=0>';
			$saida_formula.= '<tr><td>'.$pratica_indicador['pratica_indicador_calculo'];
			$qnt_lista_formula=count($lista_formula);
			for ($i = 0, $i_cmp = $qnt_lista_formula; $i < $i_cmp; $i++) $lista.='I'.($lista_formula[$i]['ordem']< 10 ? '0' : '').$lista_formula[$i]['ordem'].' - '.$lista_formula[$i]['pratica_indicador_formula_simples_nome'].'<br>';		
			$saida_formula.= dica('Legenda das Variáveis', 'Clique para visualizar a legenda das variáveis que compoem a fórmula.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_formula_simples\');">('.($qnt_lista_formula).')</a>'.dicaF(). '<span style="display: none" id="lista_formula_simples"><br>'.$lista.'</span>';
			$saida_formula.= '</td></tr></table>';
			} 
	echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Fórmula', 'Fórmula.').'Fórmula:'.dicaF().'</td><td class="realce" width="100%">'.$saida_formula.'</td></tr>';
	}


if ($pratica_indicador['pratica_indicador_checklist']) {
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Checklist', 'O checklist utilizado para ste indicador.').'Checklist:'.dicaF().'</td><td class="realce" width="100%">'.link_checklist($pratica_indicador['pratica_indicador_checklist']).'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor do Checklist', 'Caso afirmativo, em vez do resultado do checklist ser a porcentagem de respostas marcadas como sim, o retorno será a soma dos pesos das linhas marcadas como sim.').'Valor do checklist:'.dicaF().'</td><td class="realce" width="100%">'.($pratica_indicador['pratica_indicador_checklist_valor'] ? 'Sim' : 'Não').'</td></tr>';
	}


if ($Aplic->profissional){
	
	$parametros_projeto = array(
		'' => '',
		'fisico_previsto' => 'Físico - Previsto até a data atual', 
		'fisico_velocidade' => 'Físico - Velocidade',  
		'financeiro_previsto' => 'Financeiro - Previsto até a data atual', 
		'total_estimado_total' => 'Financeiro - Previsto até o final', 
		'financeiro_velocidade' => 'Financeiro - Velocidade',  
		'recurso_previsto' => 'Recursos - Custo até a data atual', 
		'recurso_valor_agregado' => 'Recursos - Valor agregado',
		'recurso_ept' => 'Recursos - Estimativa para Terminar (EPT)',
		'recurso_previsto_total' => 'Recursos - Custo até o final', 
		'recurso_gasto' => 'Recursos - Gasto', 
		'mo_previsto' => 'Mão de obra - Custo previsto até a data atual', 
		'mo_valor_agregado' => 'Mão de obra - Valor agregado', 
		'mao_obra_ept' => 'Mão de obra - Estimativa para Terminar (EPT)', 
		'mo_previsto_total' => 'Mão de obra - Custo até o final',   
		'mo_gasto' => 'Mão de obra - Gasto',   
		'custo_estimado_hoje' => 'Planilha de custo - Até a data atual',
		'custo_valor_agregado' => 'Planilha de custo - Valor agregado',
		'custo_ept' => 'Planilha de custo - Estimativa para Terminar (EPT)',
		'custo_estimado' => 'Planilha de custo -  Até o final',
		'valor_agregado' => 'Valor agregado',
		'ept' => 'Estimativa para Terminar (EPT)',
		'idc' => 'Índice de Desempenho de Custos (IDC)',
		'idpt' => 'Índice de desempenho para Término (IDPT)',
		'progresso' => 'Percentagem executada',
		'total_recursos' => 'Recursos financeiros alocados',
		'gasto_efetuado' => 'Planilha de Gastos - Total',
		'gasto_registro' => 'Gastos extras' ,
		'emprego_obra' => 'Empregos gerados durante a execução',
		'emprego_direto' => 'Empregos diretos após a conclusão', 
		'emprego_indireto' => 'Empregos indiretos após a conclusão',
		'quantidade_adquirida' => 'Quantidade adquirida',
		'quantidade_prevista' => 'Quantidade prevista',
		'quantidade_realizada' => 'Quantidade realizada',
		'realizada_prevista' => 'Quantidade realizada pela prevista (%)',
		'adquirida_prevista' => 'Quantidade adquirida pela prevista (%)',
		'ata_acao' => 'Deliberações de atas de reunião concluídas'
		);
	
	if ($pratica_indicador['pratica_indicador_campo_projeto'] && $pratica_indicador['pratica_indicador_parametro_projeto'] && isset($parametros_projeto[$pratica_indicador['pratica_indicador_parametro_projeto']])) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Valor de '.ucfirst($config['projeto']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Valor de '.$config['projeto'].':'.dicaF().'</td><td class="realce" width="100%">'.$parametros_projeto[$pratica_indicador['pratica_indicador_parametro_projeto']].'</td></tr>';
	
		$parametros_tarefa = array(
		'' => '',
		'fisico_previsto' => 'Físico - Previsto até a data atual', 
		'fisico_velocidade' => 'Físico - Velocidade',  
		'financeiro_previsto' => 'Financeiro - Previsto até a data atual', 
		'total_estimado_total' => 'Financeiro - Previsto até o final', 
		'financeiro_velocidade' => 'Financeiro - Velocidade',  
		'recurso_previsto' => 'Recursos - Custo até a data atual', 
		'recurso_valor_agregado' => 'Recursos - Valor agregado',
		'recurso_ept' => 'Recursos - Estimativa para Terminar (EPT)',
		'recurso_previsto_total' => 'Recursos - Custo até o final', 
		'recurso_gasto' => 'Recursos - Gasto', 
		'mo_previsto' => 'Mão de obra - Custo previsto até a data atual', 
		'mo_valor_agregado' => 'Mão de obra - Valor agregado', 
		'mao_obra_ept' => 'Mão de obra - Estimativa para Terminar (EPT)', 
		'mo_previsto_total' => 'Mão de obra - Custo até o final',   
		'mo_gasto' => 'Mão de obra - Gasto',   
		'custo_estimado_hoje' => 'Planilha de custo - Até a data atual',
		'custo_valor_agregado' => 'Planilha de custo - Valor agregado',
		'custo_ept' => 'Planilha de custo - Estimativa para Terminar (EPT)',
		'custo_estimado' => 'Planilha de custo -  Até o final',
		'valor_agregado' => 'Valor agregado',
		'ept' => 'Estimativa para Terminar (EPT)',
		'idc' => 'Índice de Desempenho de Custos (IDC)',
		'idpt' => 'Índice de desempenho para Término (IDPT)',
		'progresso' => 'Percentagem executada',
		'total_recursos' => 'Recursos financeiros alocados',
		'gasto_efetuado' => 'Planilha de Gastos - Total',
		'gasto_registro' => 'Gastos extras' ,
		'emprego_obra' => 'Empregos gerados durante a execução',
		'emprego_direto' => 'Empregos diretos após a conclusão', 
		'emprego_indireto' => 'Empregos indiretos após a conclusão',
		'quantidade_adquirida' => 'Quantidade adquirida',
		'quantidade_prevista' => 'Quantidade prevista',
		'quantidade_realizada' => 'Quantidade realizada',
		'realizada_prevista' => 'Quantidade realizada pela prevista (%)',
		'adquirida_prevista' => 'Quantidade adquirida pela prevista (%)',
		'ata_acao' => 'Deliberações de atas de reunião concluídas'
		);
	
	$parametros_acao = array(
		'' => '',
		'progresso' => 'Percentagem executada'
		);
	
	if ($pratica_indicador['pratica_indicador_campo_tarefa'] && $pratica_indicador['pratica_indicador_parametro_tarefa'] && isset($parametros_tarefa[$pratica_indicador['pratica_indicador_parametro_tarefa']])) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Valor de '.ucfirst($config['tarefa']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Valor de '.$config['tarefa'].':'.dicaF().'</td><td class="realce" width="100%">'.$parametros_tarefa[$pratica_indicador['pratica_indicador_parametro_tarefa']].'</td></tr>';
	if ($pratica_indicador['pratica_indicador_campo_acao'] && $pratica_indicador['pratica_indicador_parametro_acao'] && isset($parametros_acao[$pratica_indicador['pratica_indicador_parametro_acao']])) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Valor de '.ucfirst($config['acao']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_acao'].' '.$config['acao'].'.').'Valor de '.$config['acao'].':'.dicaF().'</td><td class="realce" width="100%">'.$parametros_acao[$pratica_indicador['pratica_indicador_parametro_acao']].'</td></tr>';
	
	$sql->adTabela('pratica_indicador_filtro');
	$sql->adCampo('pratica_indicador_filtro.*');
	if ($pratica_indicador_id) $sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
	else $sql->adOnde('uuid = \''.$uuid.'\'');
	$filtros = $sql->lista();
	$sql->limpar();
	if (count($filtros)){
		
		echo '<tr><td align="right" valign="middle" nowrap="nowrap">'.dica('Filtros', 'Os filtros dos valores retirados automaticamente d'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Filtros:'.dicaF().'</td><td><table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
		$social=$Aplic->modulo_ativo('social');
		foreach($filtros as $linha) {
			echo '<tr><td><table cellpadding=0 cellspacing=0 width="100%">';
			if ($linha['pratica_indicador_filtro_status'] && !isset($status_tarefa)) $status_tarefa = getSisValor('StatusTarefa');
			if ($linha['pratica_indicador_filtro_status'] && isset($status_tarefa[$linha['pratica_indicador_filtro_status']])) echo'<tr><td align=right width="90">Status:</td><td>'.$status_tarefa[$linha['pratica_indicador_filtro_status']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo']) echo'<tr><td align=right width="90">Tipo de '.$config['tarefa'].':</td><td>'.getSisValorCampo('TipoTarefa', $linha['pratica_indicador_filtro_tipo']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_prioridade'] && !isset($prioridade_tarefa)) $prioridade_tarefa = getSisValor('PrioridadeTarefa');
			if ($linha['pratica_indicador_filtro_prioridade'] && isset($prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']])) echo'<tr><td align=right width="90">Prioridade:</td><td>'.$prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_setor']) echo'<tr><td align=right width="90">Setor:</td><td>'.getSisValorCampo('TarefaSetor', $linha['pratica_indicador_filtro_setor']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_segmento']) echo'<tr><td align=right width="90">Segmento:</td><td>'.getSisValorCampo('TarefaSegmento', $linha['pratica_indicador_filtro_segmento']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_intervencao']) echo'<tr><td align=right width="90">Intervenção:</td><td>'.getSisValorCampo('TarefaIntervencao', $linha['pratica_indicador_filtro_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo_intervencao']) echo'<tr><td align=right width="90">Tipo:</td><td>'.getSisValorCampo('TarefaTipoIntervencao', $linha['pratica_indicador_filtro_tipo_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_social'] && !isset($programa_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social', 'social', 'social_id=pratica_indicador_filtro_social');
				$sql->adCampo('social_id, social_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$programa_social = $sql->listaVetorChave('social_id', 'social_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_social'] && isset($programa_social[$linha['pratica_indicador_filtro_social']])) echo'<tr><td align=right width="90">Programa:</td><td>'.$programa_social[$linha['pratica_indicador_filtro_social']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_acao'] && !isset($acao_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=pratica_indicador_filtro_acao');
				$sql->adCampo('social_acao_id, social_acao_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$acao_social = $sql->listaVetorChave('social_acao_id', 'social_acao_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_acao'] && isset($acao_social[$linha['pratica_indicador_filtro_acao']])) echo'<tr><td align=right width="90">Ação:</td><td>'.$acao_social[$linha['pratica_indicador_filtro_acao']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_estado'] && !isset($estado)) {
				$sql->adTabela('estado');
				$sql->adCampo('estado_sigla, estado_nome');
				$sql->adOrdem('estado_nome');
				$estado=$sql->listaVetorChave('estado_sigla', 'estado_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_estado'] && isset($estado[$linha['pratica_indicador_filtro_estado']])) echo'<tr><td align=right width="90">Estado:</td><td>'.$estado[$linha['pratica_indicador_filtro_estado']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_cidade'] && !isset($municipio)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=pratica_indicador_filtro_cidade');
				$sql->adCampo('municipio_id, municipio_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$municipio = $sql->listaVetorChave('municipio_id', 'municipio_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_cidade'] && isset($municipio[$linha['pratica_indicador_filtro_cidade']])) echo'<tr><td align=right width="90">Município:</td><td>'.$municipio[$linha['pratica_indicador_filtro_cidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_comunidade'] && !isset($comunidade)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=pratica_indicador_filtro_comunidade');
				$sql->adCampo('social_comunidade_id, social_comunidade_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$comunidade = $sql->listaVetorChave('social_comunidade_id', 'social_comunidade_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_comunidade'] && isset($comunidade[$linha['pratica_indicador_filtro_comunidade']])) echo'<tr><td align=right width="90">Comunidade:</td><td>'.$comunidade[$linha['pratica_indicador_filtro_comunidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_texto']) echo'<tr><td align=right width="90">Texto:</td><td>'.$linha['pratica_indicador_filtro_texto'].'</td></tr>';
			echo '</table></td></tr>';
			}
		echo '</table></td></tr>';
		}
	
	}




echo '<tr><td align="right">'.dica('Ativo', 'Indica se o indicador ainda está ativo.').'Ativo:'.dicaF().'</td><td class="realce" width="100%">'.($pratica_indicador['pratica_indicador_ativo']  ? 'Sim' : 'Não').'</td></tr>';
if ($Aplic->profissional) echo '<tr><td nowrap="nowrap" align="right">'.dica('Alerta Ativo', 'Caso esteja marcado, o indicador será incluído no sistema de alertas automáticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td class="realce" width="100%">'.($pratica_indicador['pratica_indicador_alerta'] ? 'Sim' : 'Não').'</td></tr>';

	
	
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('indicadores', $pratica_indicador['pratica_indicador_id'], 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan=2>';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}			

	
	
	
if ($pratica_indicador['pratica_indicador_externo']) {
	$sql->adTabela('pratica_indicador_externo');
	$sql->adOnde('pratica_indicador_externo_indicador = '.(int)$pratica_indicador_id);
	$sql->adCampo('pratica_indicador_externo.*');
	$externo = $sql->linha();
	$sql->limpar();

	$tipo_base=array('mysql'=> 'MySQL', 'postgresql'=>'PostgreSQL', 'oracle11g'=>'Oracle 11g', 'oracle10'=>'Oracle 10g', 'sqlserver' => 'SQL Server');
	
	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Externo','Este indicador retira seus valores de uma base externa.').'&nbsp;<b>Externo</b>&nbsp'.dicaF().'</legend><table cellspacing=1 cellpadding=0 width=100%>';

	
	if (isset($tipo_base[$externo['pratica_indicador_externo_tipo']])) echo '<tr><td align="right" nowrap="nowrap" width=100>'.dica('SGBD', 'O sistema gerenciador de banco de dados.').'SGBD:'.dicaF().'</td><td class="realce">'.$tipo_base[$externo['pratica_indicador_externo_tipo']].'</td></tr>';
	if ($podeEditar && $externo['pratica_indicador_externo_usuario']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Login', 'O login para acessar o banco de dados.').'Login:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_usuario'].'</td></tr>';
	if ($podeEditar && $externo['pratica_indicador_externo_senha']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Senha', 'A senha para acessar o banco de dados.').'Senha:'.dicaF().'</td><td class="realce">'.str_repeat("*", strlen($externo['pratica_indicador_externo_senha'])).'</td></tr>';
	if ($externo['pratica_indicador_externo_base']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Banco de Dados', 'Nome do banco de dados onde se encontra a tabela ou a visão a ser lida.').'Banco de Dados:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_base'].'</td></tr>';
	if ($externo['pratica_indicador_externo_tabela']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Tabela', 'Nomre da tabela a ser consultada.').'Tabela:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_tabela'].'</td></tr>';
	if ($externo['pratica_indicador_externo_data']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo Data', 'Nome da coluna da tabela que representa a data do valor.').'Campo Data:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_data'].'</td></tr>';
	if ($externo['pratica_indicador_externo_valor']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Campo Valor', 'Nome da coluna da tabela que representa o valor.').'Campo Valor:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_valor'].'</td></tr>';
	if ($externo['pratica_indicador_externo_chave']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Chave de Pesquisa', 'Chave de filtragem da tabela pesquisada, para o comando SQL WHERE.<br>Ex: Em SELECT XX FROM YY WHERE <b>indicador</b>=25 seria indicador').'Chave de Pesquisa:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_chave'].'</td></tr>';
	if ($externo['pratica_indicador_externo_chave_valor']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor da Chave', 'O valor da chave de filtragem da tabela cacadastrada, para o comando SQL WHERE.<br>Ex: Em SELECT XX FROM YY WHERE indicador=<b>25</b> seria o valor 25').'Valor da Chave:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_chave_valor'].'</td></tr>';
	if ($externo['pratica_indicador_externo_charset']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Charset', 'Tipo de chatset da tabela a ser lida.').'Charset:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_charset'].'</td></tr>';
	if ($externo['pratica_indicador_externo_modo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Modo', 'Modo da tabela a ser lida.').'Modo:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_modo'].'</td></tr>';
	if ($externo['pratica_indicador_externo_string_conexao']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Conexão', 'String de conexão geral').'Conexão:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_string_conexao'].'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor Simples', 'Caso o objetivo do indicador é buscar apenas um único valor consolidado, em vez d um vetor de valores dispostos no tempo, deverá estar marcado como sim este cammpo').'Valor Simples:'.dicaF().'</td><td class="realce">'.($externo['pratica_indicador_externo_simples'] ? 'Sim' : 'Não').'</td></tr>';
	if ($externo['pratica_indicador_externo_sql']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Consulta SQL', 'Comando SQL de consulta para os casos de acesso a valor simples.').'Consulta SQL:'.dicaF().'</td><td class="realce">'.$externo['pratica_indicador_externo_sql'].'</td></tr>';
	
		
	echo '</table></fieldset></td></tr>';	
	}	
	









if (!$pratica_indicador['pratica_indicador_composicao']){
	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Meta','Lista de metas vinculadas a este indicador.').'&nbsp;<b>Metas</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
	$sql->adCampo('pratica_indicador_meta_id, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_valor_meta, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim, pratica_indicador_meta_proporcao');
	$sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_meta_data');
	$metas = $sql->lista();
	$sql->limpar();
	echo '<tr><td colspan=20 align=center><div id="metas">';
	if (count($metas)){
		echo '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Ciclo Anterior</th><th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th>Referencial</th></tr>';
		foreach($metas as $linha) {
			echo '<tr>';
			echo '<td align=right>'.number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.').'</td>';
			if ($Aplic->profissional){
				echo '<td align=center>'.($linha['pratica_indicador_meta_proporcao'] ? 'X' : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_boa'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_regular'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_ruim'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
				}
			echo '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';
			echo '<td>'.($linha['pratica_indicador_meta_valor_referencial'] != null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';
	echo '</table></fieldset></td></tr>';
	}

echo '</table></td>';



echo '<td width="50%" rowspan="1" valign="top"><table cellspacing=1 cellpadding=0 border=0 width="100%">';

//campos utilizados na regua específica	
$sql->adTabela('pratica_regra');
$sql->esqUnir('pratica_regra_campo', 'pratica_regra_campo', 'pratica_regra_campo_nome=pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=1');
$sql->adOrdem('subitem ASC, pratica_regra_ordem');
$sql->adGrupo('pratica_regra_campo_nome');
$vetor_campos=$sql->lista();
$sql->limpar();


if ($pratica_indicador['pratica_indicador_desde_quando']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Desde Quando é Mensurado', 'Desde quando o indicador é mensurado.').'Desde quando:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$data_desde->format($df).'</td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_melhorias']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Melhorias efetuadas no Indicador', 'Quais as melhorias realizadas no indicador após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.($pratica_indicador['pratica_indicador_requisito_melhorias']).'</td></tr>';

$tipo_polaridade=array(0 => 'Melhor se menor', 1 => 'Melhor se maior', 2 => 'Melhor se no centro');
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Polaridade', 'Qual a polaridade dos valores do indicador.').'Polaridade:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(isset($tipo_polaridade[$pratica_indicador['pratica_indicador_sentido']]) ? $tipo_polaridade[$pratica_indicador['pratica_indicador_sentido']] : 'não definido').'</td></tr>';	


if ($pratica_indicador['pratica_indicador_requisito_referencial']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Referencial Comparativo', 'Qual o referencial comparativo para este indicador.').'Referencial comparativo:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$pratica_indicador['pratica_indicador_requisito_referencial'].'</td></tr>';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Valor do Referencial Comparativo', 'Qual o valor do referencial comparativo.').'Valor do referencial'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_referencial, $config['casas_decimais'], ',', '.').'</td></tr>';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Meta', 'Qual o valor a ser alcançado pelo indicador para que seje considerado excelente.').'Meta:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(!$pratica_indicador['pratica_indicador_composicao'] ? number_format($obj->pratica_indicador_valor_meta, $config['casas_decimais'], ',', '.') : 100).($obj->pratica_indicador_meta_proporcao ? ' x período anterior' : '').($pratica_indicador['pratica_indicador_unidade'] ? ' '.$pratica_indicador['pratica_indicador_unidade'] : '').'</td></tr>';

if ($obj->pratica_indicador_valor_meta_boa!=null) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Nível Bom', 'Qual o valor do indicador é aceitável com bom.').'Nível bom'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_meta_boa, $config['casas_decimais'], ',', '.').'</td></tr>';
if ($obj->pratica_indicador_valor_meta_regular!=null) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Nível Regular', 'Qual o valor do indicador é aceitável com regulr.').'Nível regular'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_meta_regular, $config['casas_decimais'], ',', '.').'</td></tr>';
if ($obj->pratica_indicador_valor_meta_ruim!=null) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Nível Ruim', 'Qual o valor do indicador é considerado ruim.').'Nível ruim'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').':'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.number_format($obj->pratica_indicador_valor_meta_ruim, $config['casas_decimais'], ',', '.').'</td></tr>';
if ($obj->pratica_indicador_data_meta) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Data para a Meta', 'Qual a data estipulada para alcançar a meta.').'Data para meta:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.retorna_data($obj->pratica_indicador_data_meta, false).'</td></tr>';
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Valor do Período', ($pratica_indicador['pratica_indicador_composicao'] ? 'No caso de composição o valor é sempre a pontuação. A média ponderada da pontuação dos indicadores pertencentes a composição.<br><br>A pontuação de cada indicador componente é a razão entre o valor do indicador e a meta estipulada, em porcentagem. Quanto mais próximo de 100%, melhor.' : 'O último valor do período considerado.')).'Valor do Período:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.($valor !==null ? number_format($valor, $config['casas_decimais'], ',', '.') : 'sem valor').'</td></tr>';	
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Pontuação', ($pratica_indicador['pratica_indicador_composicao'] ? 'A média ponderada da pontuação dos indicadores pertencentes a composição.<br><br>A pontuação de cada indicador componente é a razão entre o valor do indicador e a meta estipulada, em porcentagem. Quanto mais próximo de 100%, melhor.' : 'A pontuação é a razão entre o valor do indicador e a meta estipulada, em porcentagem. Quanto mais próximo de 100%, melhor.')).'Pontuação:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$obj->Pontuacao($ano, null, null, true).'%</td></tr>';	
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Tipo de tendência', 'Qual a tendência dos valores deste indicador.').'Tipo de tendência:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.$obj->Tendencia().'</td></tr>';	
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tolerância', 'A tolerância da pontuação obtida em relação à meta.<br>Ex:Com a tolerância de 15% uma pontuação final de 85% seria visualizada como 100%.').'Tolerância'.dicaF().':</td><td width="100%" colspan=2 class="realce">'.number_format($pratica_indicador['pratica_indicador_tolerancia'], 2, ',', '.').'%</td><tr>';

$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano', 'nenhum' => 'Nenhum agrupamento');
$tipo_acumulacao=array('media_simples' => 'Média simples dos valores do período', 'soma' => 'Soma dos valores do período', 'saldo' => 'Último valor do período');
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Tipo de Acumulação', 'Qual a forma de acumulação dos valores do indicador inseridos no período.').'Tipo de acumulação:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(isset($tipo_acumulacao[$pratica_indicador['pratica_indicador_acumulacao']]) ? $tipo_acumulacao[$pratica_indicador['pratica_indicador_acumulacao']] : 'não definido').'</td></tr>';	
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Período', 'Qual o período de agrupamento dos valores</li></ul>').'Período:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.(isset($tipo_agrupamento[$pratica_indicador['pratica_indicador_agrupar']]) ? $tipo_agrupamento[$pratica_indicador['pratica_indicador_agrupar']] : 'não definido').'</td></tr>';	

echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Pontuação do Período Anterior', 'Caso positivo os cálculos consideram apenas do período anterior para trás (pois o atual não fecha até o final do mesmo).').'Pontuação do período anterior:'.dicaF().'</td><td width="100%" colspan=2 class="realce">'.($obj->pratica_indicador_periodo_anterior ? 'Sim' : 'Não').'</td></tr>';	


$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('pratica_indicador_requisito.*');
if (!$ano) $sql->adOnde('pratica_indicador_requisito_id = '.(int)$pratica_indicador['pratica_indicador_requisito']);
else $sql->adOnde('pratica_indicador_requisito_ano = '.(int)$ano);
$sql->adOnde('pratica_indicador_requisito_indicador = '.(int)$pratica_indicador_id);
$requisito = $sql->linha();
$sql->limpar();


$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=1');
$sql->adOrdem('pratica_regra_campo_id');
$lista=$sql->Lista();
$sql->limpar();

$vetor_existe=array(
	'pratica_indicador_tendencia',
	'pratica_indicador_favoravel',
	'pratica_indicador_superior',
	'pratica_indicador_relevante',
	'pratica_indicador_atendimento',
	'pratica_indicador_lider',
	'pratica_indicador_excelencia',
	'pratica_indicador_estrategico'
	);
	
	
$original=array();	
foreach($lista as $linha){	
	if (in_array($linha['pratica_regra_campo_nome'], $vetor_existe))	$original[$linha['pratica_regra_campo_nome']]=dica($linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).$linha['pratica_regra_campo_texto'].':'.dicaF();
	}
if (in_array('pratica_indicador_tendencia', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_tendencia'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_tendencia'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_tendencia'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_tendencia'].'</td></tr></table></td></tr>';	
if (in_array('pratica_indicador_favoravel', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_favoravel'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_favoravel'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_favoravel'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_favoravel'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_superior', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_superior'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_superior'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_superior'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_superior'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_relevante', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_relevante'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_relevante'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_relevante'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_relevante'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_atendimento', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_atendimento'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_atendimento'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_atendimento'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_atendimento'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_lider', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_lider'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_lider'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_lider'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_lider'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_excelencia', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_excelencia'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_excelencia'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_excelencia'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_excelencia'].'</td></tr></table></td></tr>';
if (in_array('pratica_indicador_estrategico', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_indicador_estrategico'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_indicador_requisito_estrategico'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_indicador_requisito_justificativa_estrategico'] ? 'class="realce"' : '').'>'.$requisito['pratica_indicador_requisito_justificativa_estrategico'].'</td></tr></table></td></tr>';









echo '</table></td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador_avaliacao');
	$sql->adCampo('pratica_indicador_avaliacao.*');
	$sql->adOnde('pratica_indicador_avaliacao_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_avaliacao_ordem');
	$causas=$sql->Lista();
	$sql->limpar();
	if (count($causas)) {
		echo '<tr><td colspan=20 align=left><b>Avaliação dos Resultados</b></td></tr>';
		echo '<tr><td colspan=20 align=left>';
		echo '<table cellspacing=0 cellpadding=2 class="tbl1" align=left width="100%">';
		echo '<tr><td style="font-weight:bold" align=center>Sucesso</td><td style="font-weight:bold" align=center>Insucesso</td><td style="font-weight:bold" align=center>Causa</td><td style="font-weight:bold" align=center>Medidas para Sanar</td></tr>';
		foreach ($causas as $causa) {
			echo '<tr align="center">';
			echo '<td align=center width=40>'.($causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
			echo '<td align=center width=40>'.(!$causa['pratica_indicador_avaliacao_sucesso'] ? '<b>X</b>' : '&nbsp;').'</td>';
			echo '<td align=left>'.($causa['pratica_indicador_avaliacao_causa'] ? $causa['pratica_indicador_avaliacao_causa'] : '&nbsp;').'</td>';
			echo '<td align=left>'.($causa['pratica_indicador_avaliacao_sanar'] ? $causa['pratica_indicador_avaliacao_sanar'] : '&nbsp;').'</td>';
			echo '</tr>';
			}
		echo '</table></td></tr>';
		}
	}


echo '</table>';


echo '</table>';


echo estiloFundoCaixa();

$caixaTab = new CTabBox('m=praticas&a=indicador_ver&pratica_indicador_id='.(int)$pratica_indicador_id, '', $tab);
$texto_consulta = '?m=praticas&a=indicador_ver&pratica_indicador_id='.(int)$pratica_indicador_id;
if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
else {
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_log_ver', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.<br><br>O registro é a forma padrão dos designados das ações informarem sobre o andamento e avisarem sobre problemas.');
	if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_log_editar', 'Registrar',null,null,'Registrar','Inserir uma ocorrência.');
	}

if (!$pratica_indicador['pratica_indicador_composicao'] && !$pratica_indicador['pratica_indicador_formula'] && !$pratica_indicador['pratica_indicador_formula_simples'] && !$pratica_indicador['pratica_indicador_checklist'] && !$pratica_indicador['pratica_indicador_campo_projeto'] && !$pratica_indicador['pratica_indicador_campo_tarefa'] && !$pratica_indicador['pratica_indicador_campo_acao']) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_lista_valor', 'Valores',null,null,'Valores','Visualizar a lista de inserções de valores neste indicador.');
elseif ($pratica_indicador['pratica_indicador_checklist']) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/checklist_lista_valor', 'Checklist',null,null,'Valores de Checklist','Visualizar a lista de resultados de checklist deste indicador.');
elseif ($pratica_indicador['pratica_indicador_formula_simples']) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_lista_valor_pro', 'Valores',null,null,'Valores','Visualizar a lista de inserções de valores neste indicador.');

$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_grafico', 'Gráfico',null,null,'Gráfico','Visualizar o gráfico do histórico deste indicador.');
$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_ver_marcadores', ucfirst($config['marcadores']),null,null,ucfirst($config['marcadores']),'Visualizar '.$config['genero_marcador'].'s '.$config['marcadores'].' atendid'.$config['genero_marcador'].'s pel'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' que utilizam este indicador.');
//$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicador_ver_praticas', ucfirst($config['praticas']),null,null,ucfirst($config['praticas']),'Visualizar '.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' que utilizam este indicador.');

if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fóruns relacionados.');
if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');


$ver_min = true;
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>
<script language="javascript">

function duplicar_indicador(pratica_indicador_id){
	var nome=prompt("Entre o nome do clone","<?php echo $pratica_indicador['pratica_indicador_nome']?>");
	document.env.pratica_indicador_nome.value=nome;
	document.env.a.value='indicador_duplicar';
	document.env.submit();
	}

function imprimir(pratica_indicador_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 900, 500, 'm=praticas&a=imprimir_indicador&dialogo=1&tipo=1&pratica_indicador_id='+pratica_indicador_id, null, window);
	else window.open('index.php?m=praticas&a=imprimir_indicador&dialogo=1&tipo=1&pratica_indicador_id='+pratica_indicador_id, '','width=900, height=900, menubar=1, scrollbars=1');
	
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir este indicador?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='indicador_fazer_sql';
		f.submit();
		}
	}



function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>