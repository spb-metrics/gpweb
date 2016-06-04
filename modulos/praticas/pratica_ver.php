<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pratica_id = intval(getParam($_REQUEST, 'pratica_id', 0));



$sql = new BDConsulta;
$sql->adTabela('pratica_requisito');
$sql->adCampo('DISTINCT ano');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);
asort($anos);

if (isset($_REQUEST['IdxPraticaAno'])) $Aplic->setEstado('IdxPraticaAno', getParam($_REQUEST, 'IdxPraticaAno', null));
$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')])  ? $Aplic->getEstado('IdxPraticaAno') : $ultimo_ano);

$sql->adTabela('praticas');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id = praticas.pratica_id');
$sql->adOnde('praticas.pratica_id='.(int)$pratica_id);
if ($ano) $q->adOnde('ano = '.(int)$ano);
$pratica=$sql->Linha();
$sql->limpar();

if (!($Aplic->checarModulo('praticas', 'acesso', null, 'pratica') && permiteAcessarPratica($pratica['pratica_acesso'],$pratica_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


if (isset($_REQUEST['tab'])) $Aplic->setEstado('PraticaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('PraticaVerTab') !== null ? $Aplic->getEstado('PraticaVerTab') : 0;
$msg = '';
$editar=($Aplic->checarModulo('praticas', 'editar', null, 'pratica') && permiteEditarPratica($pratica['pratica_acesso'],$pratica_id));

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);
$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos_pauta=$sql->ListaChave();
$sql->limpar();
$modelos[0]='';


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="pratica_ver" />';
echo '<input type="hidden" name="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';

//verifica se já é melhor pratica
$sql->adTabela('melhores_praticas');
$sql->adCampo('count(pratica_id)');
$sql->adOnde('pratica_id='.(int)$pratica_id);
$existe_melhor_pratica=$sql->resultado();
$sql->limpar();

//campos utilizados na regua específica	
$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=0 OR pratica_regra_campo_resultado IS NULL');
$vetor_campos=$sql->carregarColuna();
$sql->limpar();

$ponto_forte=0;	
foreach($vetor_campos as $campo) {
	if (isset($pratica[$campo]) && $pratica[$campo]) $ponto_forte++;
	}

$oim=0;	
foreach($vetor_campos as $campo) {
	if (isset($pratica[$campo]) && !$pratica[$campo])$oim++;
	}


if (!$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'pratica.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td>'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td>'.selecionaVetor($modelos_pauta, 'pratica_modelo_id', 'onchange="mudar_pauta()" class="texto"', $pratica_modelo_id).'</td></tr><tr><td>'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados d'.$config['genero_pratica'].' '.$config['pratica'].' inseridos no ano selecionado.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'IdxPraticaAno', 'onchange="mudar_ano()" class="texto"', $ano).'</td></tr></table>');
	if ($editar) {
		$botoesTitulo->adicionaCelula(dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relecionad'.$config['genero_acao'].' a esta pratica.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_pratica='.(int)$pratica_id.'\');" ><span>'.strtolower($config['acao']).'</span></a>'.dicaF());
		if(!$config['termo_abertura_obrigatorio'] && $Aplic->checarModulo('projetos', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Criar nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].' a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=editar&projeto_pratica='.(int)$pratica_id.'\');" ><span>'.strtolower($config['projeto']).'</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $botoesTitulo->adicionaCelula(dica('Novo Evento', 'Criar um novo evento.<br><br>Os eventos são atividades com data e hora específicas podendo estar relacionados com praticas, '.$config['tarefas'].' e '.$config['usuarios'].' específicos').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=calendario&a=editar&evento_pratica='.(int)$pratica_id.'\');" ><span>evento</span></a>'.dicaF());
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $botoesTitulo->adicionaCelula(dica('Novo Arquivo', 'Inserir um novo arquivo relacionado a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=arquivos&a=editar&arquivo_pratica='.(int)$pratica_id.'\');" ><span>arquivo</span></a>'.dicaF());
		$botoesTitulo->adicionaCelula(dica('Novo Indicador', 'Criar um novo indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=indicador_editar&pratica_indicador_pratica='.(int)$pratica_id.'\');" ><span>indicador</span></a>'.dicaF());
		//$botoesTitulo->adicionaCelula(dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Criar '.($config['genero_pratica']=='a' ? 'uma nova ': 'um novo ').$config['pratica'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=pratica_editar\');" ><span>'.$config['pratica'].'</span></a>'.dicaF());
		if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=atas&a=ata_editar&ata_pratica='.(int)$pratica_id.'\');" ><span>ata</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' '.($config['genero_pratica']=='a' ? 'nesta ': 'neste ').$config['pratica'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=problema&a=problema_editar&problema_pratica='.(int)$pratica_id.'\');" ><span>problema</span></a>'.dicaF().'</td></tr></table>');
		}
	$botoesTitulo->adicionaBotao('m=praticas&a=pratica_lista', 'lista','','Lista de '.ucfirst($config['praticas']),'Visualizar a lista de tod'.($config['genero_pratica']=='a' ? 'as as ': 'os os ').$config['praticas'].'.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas&a=pratica_editar&IdxPraticaAno='.(int)$ano.'&pratica_id='.(int)$pratica_id, 'editar','','Editar '.($config['genero_pratica']=='a' ? 'esta ': 'este ').ucfirst($config['pratica']),'Editar os detalhes d'.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.');
		if ($Aplic->checarModulo('praticas', 'excluir', null, 'pratica') && $editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir '.$config['genero_pratica'].' '.ucfirst($config['pratica']),'Excluir '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].' do sistema.<br><br>Todas as ações pertencentes a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].' também serão excluídas.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir '.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a pratica.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=praticas&a=imprimir_pratica&dialogo=1&sem_cabecalho=1&tipo=1&pratica_id='.(int)$pratica_id.'\', \'imprimir_pratica\',\'width=1200, height=600, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}

if ($Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'pratica.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['praticas']),'Clique neste botão para visualizar a lista de '.$config['praticas'].'.').'Lista de '.ucfirst($config['praticas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_lista\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Criar um nov'.$config['genero_pratica'].' '.$config['pratica'].'.').'Nov'.$config['genero_pratica'].' '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar\");");
	
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&pratica_id=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_pratica=".$pratica_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_pratica=".$pratica_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_pratica=".$pratica_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_pratica=".$pratica_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_pratica=".$pratica_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) $km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['pratica']),'Editar os detalhes d'.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').'Editar '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_editar&pratica_id=".$pratica_id."\");");
	
	
	if (!$oim && $editar && !$existe_melhor_pratica && $pratica_modelo_id) $km->Add("acao","acao_editar",dica('Inserir Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' pertença ao pool das melhores.').'Inserir Melhor '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_melhores_editar&pratica_id=".$pratica_id."\");");
	elseif (!$oim && $editar && $existe_melhor_pratica) $km->Add("acao","acao_editar",dica('Editar Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja editar a justificativa para que esta '.$config['pratica'].' pertença ao pool das melhores.').'Editar Melhor '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=pratica_melhores_editar&pratica_id=".$pratica_id."\");");
	elseif ($oim && $editar && $existe_melhor_pratica) 	$km->Add("acao","acao_editar",dica('Remover Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' seja removida do pool das melhores, por ter oportunidade de inovação e melhoria.').'Remover Melhor '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='popRemoverMelhorPratica()");

	
	if ($podeExcluir &&$editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].' do sistema.').'Excluir '.ucfirst($config['pratica']).dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_pratica'].' '.$config['pratica'].'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=praticas&a=imprimir_pratica&dialogo=1&sem_cabecalho=1&tipo=1&pratica_id=".$pratica_id."\");");
	echo $km->Render();
	echo '<td  style="background-color: #e6e6e6" nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().selecionaVetor($modelos_pauta, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto"', $pratica_modelo_id).'&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados d'.$config['genero_pratica'].' '.$config['genero_pratica'].' inserid'.$config['genero_pratica'].'s no ano selecionado.').'Ano:'.dicaF().selecionaVetor($anos, 'IdxPraticaAno', 'onchange="env.submit()" class="texto"', $ano).'</td></tr>';
	}




echo '</form>';


echo '<table id="tblPraticas" cellpadding=0 cellspacing=0 width="100%" class="std">';

if (!$Aplic->profissional){
	$sql->adTabela('causa_efeito_praticas');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito.causa_efeito_id=causa_efeito_praticas.causa_efeito_id');
	$sql->adCampo('causa_efeito_praticas.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_praticas.pratica_id='.(int)$pratica_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.(int)$causa_efeito['causa_efeito_id'].'&pratica_id='.(int)$pratica_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}
else{
	$sql->adTabela('causa_efeito_gestao');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_id=causa_efeito_gestao_causa_efeito');
	$sql->adCampo('causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_gestao_pratica='.(int)$pratica_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.(int)$causa_efeito['causa_efeito_id'].'&pratica_id='.(int)$pratica_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}



if (!$Aplic->profissional){
	$sql->adTabela('gut_praticas');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_praticas.gut_id');
	$sql->adCampo('gut_praticas.gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_praticas.pratica_id='.(int)$pratica_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.(int)$gut['gut_id'].'&pratica_id='.(int)$pratica_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}
else{
	$sql->adTabela('gut_gestao');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_gestao_gut');
	$sql->adCampo('gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_gestao_pratica='.(int)$pratica_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.(int)$gut['gut_id'].'&pratica_id='.(int)$pratica_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}


if (!$Aplic->profissional){
	$sql->adTabela('brainstorm_praticas');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_praticas.brainstorm_id');
	$sql->adCampo('brainstorm_praticas.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_praticas.pratica_id='.(int)$pratica_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.(int)$brainstorm['brainstorm_id'].'&pratica_id='.(int)$pratica_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';
	}
else{
	$sql->adTabela('brainstorm_gestao');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_gestao_brainstorm');
	$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_gestao_pratica='.(int)$pratica_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.(int)$brainstorm['brainstorm_id'].'&pratica_id='.(int)$pratica_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';

	}	
	
	
$cor_indicador=cor_indicador('pratica', null, null, null, null, $pratica['pratica_principal_indicador']);

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$pratica['pratica_cor'].'" colspan="2"><font color="'.melhorCor($pratica['pratica_cor']).'"><b>'.$pratica['pratica_nome'].'<b></font>'.$cor_indicador.$saida_brainstorm.$saida_causa_efeito.$saida_gut.'</td></tr>';

$sql->adTabela('pratica_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$designados = $sql->Lista();
$sql->limpar();

$sql->adTabela('pratica_depts');
$sql->adCampo('pratica_depts.dept_id');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$departamentos = $sql->Lista();
$sql->limpar();


echo '<tr><td width="50%" valign="top"><table cellspacing=1 cellpadding=0 border=0 width="100%">';





if ($pratica['pratica_cia']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($pratica['pratica_cia']).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_cia');
	$sql->adCampo('pratica_cia_cia');
	$sql->adOnde('pratica_cia_pratica = '.(int)$pratica_id);
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

if ($pratica['pratica_dept']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($pratica['pratica_dept']).'</td></tr>';

$saida_depts='';
if ($departamentos && count($departamentos)) {
		$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_depts.= '<tr><td>'.link_secao($departamentos[0]['dept_id']);
		$qnt_lista_depts=count($departamentos);
		if ($qnt_lista_depts > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($departamentos[$i]['dept_id']).'<br>';		
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		} 
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';


if ($pratica['pratica_responsavel']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), ucfirst($config['usuario']).' responsável por gerenciar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($pratica['pratica_responsavel'], '','','esquerda').'</td></tr>';		


$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_secao($designados[0]['contato_dept']) : '');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_secao($designados[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';






if ($pratica['pratica_descricao']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Descrição', 'Descrição d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Descrição:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_descricao'].'</td></tr>';




if ($pratica['pratica_composicao']){
	$sql->adTabela('pratica_composicao');
	$sql->esqUnir('praticas','praticas','pratica_id=pc_pratica_filho');
	$sql->adCampo('pc_pratica_filho, pratica_nome');
	$sql->adOnde('pc_pratica_pai ='.(int)$pratica_id);
	$composicao = $sql->Lista();
	$sql->limpar();
	$saida_composicao='';
	if ($composicao && count($composicao)) {
		$saida_composicao.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_composicao.= '<tr><td>'.link_pratica($composicao[0]['pc_pratica_filho']);
		$qnt_composicao=count($composicao);
		if ($qnt_composicao > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_composicao; $i < $i_cmp; $i++) $lista.=link_pratica($composicao[$i]['pc_pratica_filho']).'<br>';		
				$saida_composicao.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'composicao\');">(+'.($qnt_composicao - 1).')</a>'.dicaF(). '<span style="display: none" id="composicao"><br>'.$lista.'</span>';
				}
		$saida_composicao.= '</td></tr></table>';
		} 
	if ($saida_composicao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Composição', 'Este pratica é composto da média ponderada de outros praticas.').'Composição:'.dicaF().'</td><td class="realce" width="100%"><table cellspacing=0 cellpadding=0><tr><td>'.$saida_composicao.'</td><td><a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=pratica_explodir&dialogo=1&pratica_id='.(int)$pratica_id.'\', \'Composição\',\'height=630,width=830,scrollbars=no\')">'.imagem('icones/pratica_exp_p.png','Composição','Clique neste ícone '.imagem('icones/pratica_exp_p.png').' para visualizar a composição de praticas.').'</a></td></tr></table></td></tr>';
	}


if ($pratica['pratica_principal_indicador']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores d'.$config['genero_pratica'].' '.$config['pratica'].' o mais representativo da situação geral d'.$config['genero_pratica'].' mesm'.$config['genero_pratica'].'.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($pratica['pratica_principal_indicador']).'</td></tr>';


if ($pratica['pratica_oque']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'O Que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_oque'].'</td></tr>';
if ($pratica['pratica_porque']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que '.$config['genero_pratica'].' '.$config['pratica'].' será executad'.$config['genero_pratica'].'.').'Por que:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_porque'].'</td></tr>';
if ($pratica['pratica_onde']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Onde Fazer', 'Onde '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Onde:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_onde'].'</td></tr>';
if ($pratica['pratica_quando']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quando Fazer', 'Quando '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Quando:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_quando'].'</td></tr>';
if ($pratica['pratica_quem']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Quem:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$pratica['pratica_quem'].'</td></tr>';
if ($pratica['pratica_como']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Como Fazer', 'Como '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Como:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_como'].'</td></tr>';
if ($pratica['pratica_quanto']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para executar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Quanto:'.dicaF().'</td><td class="realce" width="100%">'.$pratica['pratica_quanto'].'</td></tr>';

echo '</table></td>';
echo '<td width="50%" rowspan="1" valign="top"><table cellspacing=1 cellpadding=0 width="100%">';



$sql->adTabela('pratica_requisito');
$sql->adCampo('pratica_requisito.*');
$sql->adOnde('pratica_id = '.(int)$pratica_id);
$sql->adOnde('ano = '.(int)$ano);
$requisito = $sql->linha();
$sql->limpar();

$sql->adTabela('pratica_nos_verbos');
$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_verbo_id=verbo');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_verbos.pratica=praticas.pratica_id');
$sql->adCampo('COUNT(verbo)');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('praticas.pratica_id='.(int)$pratica_id);
$sql->adOnde('pratica_nos_verbos.ano='.(int)$ano);
$adequacao=$sql->Resultado();
$sql->limpar();

$pratica['pratica_adequada']=(int)$adequacao;







$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=0 OR pratica_regra_campo_resultado IS NULL');
$sql->adOrdem('pratica_regra_campo_id');
$lista=$sql->Lista();
$sql->limpar();

$vetor_existe=array(
	'pratica_controlada',
	'pratica_proativa',
	'pratica_abrange_pertinentes',
	'pratica_continuada',
	'pratica_refinada',
	'pratica_melhoria_aprendizado',
	'pratica_coerente',
	'pratica_interrelacionada',
	'pratica_cooperacao',
	'pratica_cooperacao_partes',
	'pratica_arte',
	'pratica_inovacao',
	'pratica_gerencial',
	'pratica_agil',
	'pratica_refinada_implantacao',
	'pratica_incoerente'
	);
	
	
$original=array();	
foreach($lista as $linha){	
	if (in_array($linha['pratica_regra_campo_nome'], $vetor_existe))	$original[$linha['pratica_regra_campo_nome']]=dica($linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).$linha['pratica_regra_campo_texto'].':'.dicaF();
	}
if (in_array('pratica_controlada', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_controlada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_controlada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_controlada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_controlada'].'</td></tr></table></td></tr>';
if (in_array('pratica_proativa', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_proativa'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_proativa'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_proativa'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_proativa'].'</td></tr></table></td></tr>';
if (in_array('pratica_abrange_pertinentes', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_abrange_pertinentes'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_abrange_pertinentes'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_abrangencia'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_abrangencia'].'</td></tr></table></td></tr>';
if (in_array('pratica_continuada', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_continuada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_continuada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_continuada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_continuada'].'</td></tr></table></td></tr>';
if (in_array('pratica_refinada', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_refinada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_refinada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_refinada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_refinada'].'</td></tr></table></td></tr>';
if (in_array('pratica_refinada_implantacao', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_refinada_implantacao'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_refinada_implantacao'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_refinada_implantacao'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_refinada_implantacao'].'</td></tr></table></td></tr>';
if (in_array('pratica_melhoria_aprendizado', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_melhoria_aprendizado'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_melhoria_aprendizado'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_melhoria_aprendizado'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_melhoria_aprendizado'].'</td></tr></table></td></tr>';
if (in_array('pratica_coerente', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_coerente'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_coerente'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_coerente'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_coerente'].'</td></tr></table></td></tr>';
if (in_array('pratica_incoerente', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_incoerente'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_incoerente'] ? 'color: #ff0000;">Sim' : 'color: #2d8937;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_incoerente'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_incoerente'].'</td></tr></table></td></tr>';
if (in_array('pratica_interrelacionada', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_interrelacionada'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_interrelacionada'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_interrelacionada'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_interrelacionada'].'</td></tr></table></td></tr>';
if (in_array('pratica_cooperacao', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_cooperacao'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_cooperacao'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_cooperacao'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_cooperacao'].'</td></tr></table></td></tr>';
if (in_array('pratica_cooperacao_partes', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_cooperacao_partes'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_cooperacao_partes'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_cooperacao_partes'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_cooperacao_partes'].'</td></tr></table></td></tr>';
if (in_array('pratica_arte', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_arte'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_arte'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_arte'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_arte'].'</td></tr></table></td></tr>';
if (in_array('pratica_inovacao', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_inovacao'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_inovacao'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_inovacao'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_inovacao'].'</td></tr></table></td></tr>';
if (in_array('pratica_gerencial', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_gerencial'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_gerencial'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_gerencial'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_gerencial'].'</td></tr></table></td></tr>';
if (in_array('pratica_agil', $vetor_campos)) echo '<tr><td align="right" nowrap="nowrap">'.$original['pratica_agil'].'</td><td><table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" style="width:35px; '.($requisito['pratica_agil'] ? 'color: #2d8937;">Sim' : 'color: #ff0000;">Não').'</td><td width="100%" '.($requisito['pratica_justificativa_agil'] ? 'class="realce"' : '').'>'.$requisito['pratica_justificativa_agil'].'</td></tr></table></td></tr>';





if ($existe_melhor_pratica)  echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Melhor '.ucfirst($config['pratica']), 'Pertence ao pool das melhores '.$config['pratica'].'.').'Melhor '.$config['pratica'].':'.dicaF().'</td><td class="realce" width="100%">Sim</td></tr>';
if (!$Aplic->profissional){
	if (!$oim && $editar && !$existe_melhor_pratica && $pratica_modelo_id) echo '<tr><td colspan=2>'.botao('inserir melhor '.$config['pratica'], 'Inserir Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' pertença ao pool das melhores.','','url_passar(0, \'m=praticas&a=pratica_melhores_editar&pratica_id='.(int)$pratica_id.'\');').'</td></tr>';
	elseif (!$oim && $editar && $existe_melhor_pratica) echo '<tr><td colspan=2>'.botao('editar melhor '.$config['pratica'], 'Editar Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja editar a justificativa para que esta '.$config['pratica'].' pertença ao pool das melhores.','','url_passar(0, \'m=praticas&a=pratica_melhores_editar&pratica_id='.(int)$pratica_id.'\');').'</td></tr>';
	elseif ($oim && $editar && $existe_melhor_pratica) echo '<tr><td colspan=2>'.botao('remover melhor '.$config['pratica'], 'Remover Melhor '.ucfirst($config['pratica']), 'Pressione este botão caso deseja que esta '.$config['pratica'].' seja removida do pool das melhores, por ter oportunidade de inovação e melhoria.','','popRemoverMelhorPratica()').'</td></tr>';
	}
	


require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('praticas',$pratica['pratica_id'], 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
		
	
echo '<tr><td align="right">'.dica('Ativ'.$config['genero_pratica'], 'Caso '.$config['genero_pratica'].' '.$config['pratica'].' ainda esteja ativ'.$config['genero_pratica'].' deverá estar marcado este campo.').'Ativ'.$config['genero_pratica'].':'.dicaF().'</td><td class="realce" width="100%">'.($pratica['pratica_ativa'] ? 'Sim' : 'Não').'</td></tr>';	
		
echo '</table></td></tr></table>';

echo estiloFundoCaixa();

$caixaTab = new CTabBox('m=praticas&a=pratica_ver&pratica_id='.(int)$pratica_id, '', $tab);
$texto_consulta = '?m=praticas&a=pratica_ver&pratica_id='.(int)$pratica_id;
if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
else {
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/pratica_log_ver', 'Registros',null,null,'Registros de Ocorrências','Visualizar os registros de ocorrências.<br><br>O registro é a forma padrão dos designados das ações informarem sobre o andamento e avisarem sobre problemas.');
	if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/pratica_log_editar', 'Registrar',null,null,'Registrar','Inserir uma ocorrência.');
	}
$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_marcadores', ucfirst($config['marcadores']),null,null,ucfirst($config['marcadores']),'Visualizar '.$config['genero_marcador'].'s '.$config['marcadores'].' atendid'.$config['genero_marcador'].'s por '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.');
if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/pratica_legislacao_pro', 'Legislação',null,null,'Legislação','Visualizar as normas relacionados a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.');
if ($Aplic->checarModulo('calendario', 'acesso') && $Aplic->modulo_ativo('calendario')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos d'.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.');
if ($Aplic->checarModulo('arquivos', 'acesso') && $Aplic->modulo_ativo('arquivos')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados com '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.');
if ($Aplic->checarModulo('links', 'acesso') && $Aplic->modulo_ativo('links')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados com '.$config['genero_pratica'].' '.$config['pratica'].'.');
if ($Aplic->checarModulo('foruns', 'acesso') && $Aplic->modulo_ativo('foruns')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fórus relacionados com '.$config['genero_pratica'].' '.$config['pratica'].'.');
$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados a '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.');
$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acao']),null,null,ucfirst($config['acao']),'Visualizar '.$config['genero_acao'].'s '.$config['acao'].' vinculad'.$config['genero_acao'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('projetos')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');


$f = 'todos';
$ver_min = true;
$caixaTab->mostrar('','','','',true);
echo estiloFundoCaixa('','', $tab);
?>
<script language="javascript">
	
function mudar_ano(){
	document.env.submit();
	}	

function mudar_pauta(){
	document.env.submit();
	}		
	
function expandir_multipratica(id, tabelaNome) {
  var trs = document.getElementsByTagName('tr');
  for (var i=0, i_cmp=trs.length;i < i_cmp;i++) {
    var tr_nome = trs.item(i).id;
    if (tr_nome.indexOf(id) >= 0) {
     	var tr = document.getElementById(tr_nome);
     	tr.style.visibility = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'visible' : 'colapsar';
     	var img_expandir = document.getElementById(id+'_expandir');
     	var img_colapsar = document.getElementById(id+'_colapsar');
     	img_colapsar.style.display = (tr.style.visibility == 'visible') ? 'inline' : 'none';
     	img_expandir.style.display = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'inline' : 'none';
			}
		}
	}

function excluir() {
	if (confirm('Tem certeza que deseja excluir <?php echo ($config["genero_pratica"]=="a" ? "esta ": "este ").$config["pratica"]?>?')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='pratica_fazer_sql';
		f.dialogo.value=1;
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>