<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/demanda.class.php');
$demanda_id = intval(getParam($_REQUEST, 'demanda_id', 0));
$sql = new BDConsulta();
$obj = new CDemanda(true);
$obj->load($demanda_id);

$editar=permiteEditarDemanda($obj->demanda_acesso,$demanda_id);
if (!permiteAcessarDemanda($obj->demanda_acesso,$demanda_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();

if ($Aplic->profissional){
	$sql->adTabela('demanda_portfolio');
	$sql->adCampo('demanda_portfolio_filho');
	$sql->adOnde('demanda_portfolio_pai = '.(int)$demanda_id);
	$lista_portfolio = $sql->carregarColuna();
	$sql->limpar();
	$portfolio = count($lista_portfolio);
	}
else $portfolio = 0;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'demanda\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerDemandaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerDemandaTab') !== null ? $Aplic->getEstado('VerDemandaTab') : 0;
$msg = '';

$sql->adTabela('demanda_config');
$sql->adCampo('demanda_config.*');
$configuracao = $sql->linha();
$sql->Limpar();

if (!$dialogo && !$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Detalhes da Demanda', 'demanda.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=demanda_lista', 'lista','','Lista de Demandas','Clique neste botão para visualizar a lista de demanda.');
	if (($editar && $Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'demanda'))||$Aplic->usuario_super_admin) {
		$botoesTitulo->adicionaBotao('m=projetos&a=demanda_editar&demanda_id='.$demanda_id, 'editar','','Editar esta Demanda','Editar os detalhes desta demanda.');
		$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta demanda.');
		}
	$pode_analisar=true;
	if (($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'demanda') || $Aplic->usuario_super_admin) && !$obj->demanda_caracteristica_projeto) $botoesTitulo->adicionaBotao('m=projetos&a=demanda_analise&demanda_id='.$demanda_id, 'analisar&nbsp;demanda','','Analisar Demanda', 'Entrará na tela em que se marcará se esta demanda tem característica de '.$config['projeto'].', e caso afirmativo, qual o tamanho do mesmo.');
	if ($pode_analisar && (($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'viabilidade') || $Aplic->usuario_super_admin) && $obj->demanda_caracteristica_projeto && !$obj->demanda_viabilidade)) $botoesTitulo->adicionaBotao('m=projetos&a=viabilidade_editar&demanda_id='.$demanda_id, 'analisar&nbsp;viabilidade','','Analisar Viabilidade', 'Entrará na tela em que se marcará se esta demanda é um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' viável.');
	if ($obj->demanda_viabilidade){
		$sql->adTabela('projeto_viabilidade');
		$sql->adCampo('projeto_viabilidade_viavel');
		$sql->adOnde('projeto_viabilidade_id = '.(int)$obj->demanda_viabilidade);
		$viavel = $sql->resultado();
		$sql->limpar();
		}
	else $viavel=0;
	if ($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') && $viavel && !$obj->demanda_termo_abertura) $botoesTitulo->adicionaBotao('m=projetos&a=termo_abertura_editar&demanda_id='.$demanda_id, 'elaborar&nbsp;termo&nbsp;de&nbsp;abertura','','Elaborar Termo de Abertura', 'Entrará na tela em que se elaborará o termo de abertura d'.$config['genero_projeto'].' '.$config['projeto'].'.');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar') && $editar) $botoesTitulo->adicionaCelula('<table cellpadding=0 cellspacing=2><tr><td nowrap="nowrap">'.dica('Novo Arquivo', 'Inserir um novo arquivo relacionado a esta demanda.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=arquivos&a=editar&arquivo_demanda='.$demanda_id.'\');" ><span>arquivo</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaCelula(dica('Imprimir a Demanda', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a demanda.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=demanda_imprimir&dialogo=1&demanda_id='.$demanda_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}
	
if ($Aplic->profissional){	
	$sql->adTabela('assinatura');
	$sql->adCampo('count(assinatura_id)');
	$sql->adOnde('assinatura_demanda='.(int)$demanda_id);
	$assinaturas = $sql->resultado();
	$sql->Limpar();
	}

if (!$dialogo && $Aplic->profissional){	
	//Precisa das aprovações para Analisar a demanda

	$pode_analisar=true;
		
	$botoesTitulo = new CBlocoTitulo('Detalhes '.($portfolio ?  'd'.$config['genero_portfolio'].' '.ucfirst($config['portfolio']).' ': '').'da Demanda', 'demanda.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de Demandas','Clique neste botão para visualizar a lista de demandas.').'Lista de Demandas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_lista\");");

	
	if ($obj->demanda_viabilidade) $km->Add("ver","ver_viabilidade",dica('Estudo de Viabilidade','Clique neste botão para visualizar o estudo de viabilidade relacionado.').'Estudo de Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_ver&projeto_viabilidade_id=".$obj->demanda_viabilidade."\");");	
	if ($obj->demanda_termo_abertura) $km->Add("ver","ver_abertura",dica('Termo de Abertura','Clique neste botão para visualizar o termo de abertura relacionado.').'Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_ver&projeto_abertura_id=".$obj->demanda_termo_abertura."\");");	
	if ($obj->demanda_projeto) $km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste botão para visualizar d'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->demanda_projeto."\");");		
		
	$sql->adTabela('assinatura');
	$sql->adCampo('assinatura_id, assinatura_data, assinatura_aprova');
	$sql->adOnde('assinatura_usuario='.(int)$Aplic->usuario_id);
	$sql->adOnde('assinatura_demanda='.(int)$demanda_id);
	$assinar = $sql->linha();
	$sql->Limpar();
	
	
	
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nova Demanda', 'Criar um nova demanda.').'Nova Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar\");");
		if (!($obj->demanda_aprovado && $configuracao['demanda_config_trava_aprovacao']) && ($Aplic->checarModulo('projetos', 'adicionar', null, 'demanda_custo') || $Aplic->checarModulo('projetos', 'editar', null, 'demanda_custo'))) $km->Add("inserir","inserir_custo",dica('Planilha de Custos', 'Inserir a planilha de custos da demanda.').'Planilha de Custos'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_estimado_pro&demanda_id=".$demanda_id."\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&demanda_id=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_demanda=".$demanda_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_demanda=".$demanda_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_demanda=".$demanda_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_demanda=".$demanda_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_demanda=".$demanda_id."\");");

		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	
	
	
	if ($editar && !($obj->demanda_aprovado && $configuracao['demanda_config_trava_aprovacao'])) $km->Add("acao","acao_editar",dica('Editar Demanda','Editar os detalhes desta demanda.').'Editar Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_editar&demanda_id=".$demanda_id."\");");
	if (($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'demanda') || $Aplic->usuario_super_admin) && !$obj->demanda_caracteristica_projeto) $km->Add("acao","acao_analisar",dica('Analisar Demanda', 'Entrará na tela em que se marcará se esta demanda tem característica de '.$config['projeto'].', e caso afirmativo, qual o tamanho do mesmo.').'Analisar Demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_analise&demanda_id=".$demanda_id."\");");
	
	
	$bloquear=($obj->demanda_aprovado && $configuracao['demanda_config_trava_aprovacao'] && ($assinar['assinatura_aprova']==1));
	if ($assinar['assinatura_id'] && !$bloquear) $km->Add("acao","acao_assinar", ($assinar['assinatura_data'] ? dica('Mudar Assinatura', 'Entrará na tela em que se pode mudar a assinatura na demanda.').'Mudar Assinatura'.dicaF() : dica('Assinar', 'Entrará na tela em que se pode assinar a demanda.').'Assinar'.dicaF()), "javascript: void(0);' onclick='url_passar(0, \"m=tr&a=tr_assinar&demanda_id=".$demanda_id."\");"); 
		
	if ($pode_analisar && (($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'viabilidade') || $Aplic->usuario_super_admin) && $obj->demanda_caracteristica_projeto && !$obj->demanda_viabilidade)) $km->Add("acao","acao_analisar",dica('Analisar Viabilidade', 'Entrará na tela em que se marcará se esta demanda é um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' viável.').'Analisar Viabilidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=viabilidade_editar&demanda_id=".$demanda_id."\");");
	
	if ($obj->demanda_viabilidade){
		$sql->adTabela('projeto_viabilidade');
		$sql->adCampo('projeto_viabilidade_viavel');
		$sql->adOnde('projeto_viabilidade_id = '.(int)$obj->demanda_viabilidade);
		$viavel = $sql->resultado();
		$sql->limpar();
		}
	else $viavel=0;
	
	if ($Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'abertura') && $viavel && $obj->demanda_aprovado && !$obj->demanda_termo_abertura) $km->Add("acao","acao_analisar",dica('Elaborar Termo de Abertura', 'Entrará na tela em que se elaborará o termo de abertura d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Elaborar Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=termo_abertura_editar&demanda_id=".$demanda_id."\");");
	if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir esta demanda do sistema.').'Excluir Demanda'.dicaF(), "javascript: void(0);' onclick='excluir()");
	
	
	$sql->adTabela('demanda_custo');
	$sql->adCampo('count(demanda_custo_id)');
	$sql->adOnde('demanda_custo_demanda='.(int)$demanda_id);
	$tem_custo = $sql->resultado();
	$sql->Limpar();
	
	
	if ($tem_custo && $editar && $Aplic->checarModulo('projetos', 'aprovar', null, 'demanda_custo')) $km->Add("acao","acao_aprovar_custo",dica('Aprovar Planilha de Custo','Acesse interface onde será possível aprovar a planilha de custo desta demanda.').'Aprovar Planilha de Custo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=demanda_aprovar_custos_pro&demanda_id=".$demanda_id."\");");
	
	
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes desta Demanda', 'Visualize os detalhes desta demanda.').'Detalhes desta demanda'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&demanda_id=".$demanda_id."\");");
	
	if ($obj->demanda_termo_abertura){
		require_once (BASE_DIR.'/modulos/projetos/termo_abertura.class.php');
		$abertura = new CTermoAbertura();
		$abertura->load($obj->demanda_termo_abertura);
		
		if ($abertura->projeto_abertura_aprovado!=1 && ($Aplic->checarModulo('projetos', 'aprovar', $Aplic->usuario_id, 'abertura')) && ($Aplic->usuario_super_admin || $abertura->projeto_abertura_autoridade==$Aplic->usuario_id)) {
			$km->Add("acao","acao_aprovar_abertura",dica('Aprovar o Termo de Abertura', 'Ao pressionar este botão o termo de abertura será aprovado e um novo projeto automaticamente criado.').'Aprovar Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='aprovar_abertura()");
			$km->Add("acao","acao_reprovar_abertura",dica('Não Aprovar o Termo de Abertura', 'Ao pressionar este botão o termo de abertura não será aprovado.').'Não Aprovar o Termo de Abertura'.dicaF(), "javascript: void(0);' onclick='nao_aprovar_abertura()");
			}		
		}
	
	echo $km->Render();
	echo '</td></tr></table>';
	}

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" id="projeto_abertura_id" name="projeto_abertura_id" value="'.$obj->demanda_termo_abertura.'" />';
echo '</form>';

$sql->adTabela('demanda_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=demanda_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('demanda_id = '.(int)$demanda_id);
$participantes = $sql->Lista();
$sql->limpar();

$sql->adTabela('demanda_depts');
$sql->adCampo('dept_id');
$sql->adOnde('demanda_id = '.(int)$demanda_id);
$departamentos = $sql->Lista();
$sql->limpar();

$sql->adTabela('demanda_contatos');
$sql->adCampo('contato_id');
$sql->adOnde('demanda_id = '.(int)$demanda_id);
$lista_contatos = $sql->carregarColuna();
$sql->limpar();


echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->demanda_cor.'" colspan="2"><b>'.$obj->demanda_nome.'<b></td></tr>';
if ($obj->demanda_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', $config['organizacao'].' da demanda.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->demanda_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('demanda_cia');
	$sql->adCampo('demanda_cia_cia');
	$sql->adOnde('demanda_cia_demanda = '.(int)$demanda_id);
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

if ($obj->demanda_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável pela demanda.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->demanda_dept).'</td></tr>';


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



if ($obj->demanda_usuario) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Demanda', ucfirst($config['usuario']).' responsável por gerenciar a demanda.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->demanda_usuario, '','','esquerda').'</td></tr>';		

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';



$saida_contato='';
if ($lista_contatos && count($lista_contatos)) {
		$saida_contato.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_contato.= '<tr><td>'.link_contato($lista_contatos[0], '','','esquerda');
		$qnt_lista_contatos=count($lista_contatos);
		if ($qnt_lista_contatos > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($lista_contatos[$i], '','','esquerda').'<br>';		
				$saida_contato.= dica('Outros Contatos', 'Clique para visualizar os demais contatos.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contato.= '</td></tr></table>';
		} 
if ($saida_contato) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Contatos', 'Quem são os contatos d'.$config['genero_acao'].' '.$config['acao'].'.').'Contatos:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_contato.'</td></tr>';



if ($obj->demanda_supervisor) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['supervisor']), ucfirst($config['genero_supervisor']).' '.$config['supervisor'].' da demanda.').ucfirst($config['supervisor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->demanda_supervisor, '','','esquerda').'</td></tr>';

if ($obj->demanda_supervisor_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado pel'.$config['genero_supervisor'].' '.ucfirst($config['supervisor']), ucfirst($config['genero_supervisor']).' '.$config['supervisor'].' da demanda aprovou ou reprovou a mesma.').'Aprovado pel'.$config['genero_supervisor'].' '.$config['supervisor'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->demanda_supervisor_aprovado > 0 ? 'Sim - ': 'Não - ').retorna_data($obj->demanda_supervisor_data).'</td></tr>';
if ($obj->demanda_supervisor_obs) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação d'.$config['genero_supervisor'].' '.ucfirst($config['supervisor']), 'A observação redigida pel'.$config['genero_supervisor'].' '.$config['supervisor'].' da demanda.').'Observação d'.$config['genero_supervisor'].' '.$config['supervisor'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->demanda_supervisor_obs.'</td></tr>';

if ($obj->demanda_autoridade) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['autoridade']), ucfirst($config['genero_autoridade']).' '.$config['autoridade'].' da demanda.').ucfirst($config['autoridade']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->demanda_autoridade, '','','esquerda').'</td></tr>';
if ($obj->demanda_autoridade_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado pel'.$config['genero_autoridade'].' '.ucfirst($config['autoridade']), ucfirst($config['genero_autoridade']).' '.$config['autoridade'].' da demanda aprovou ou reprovou a mesma.').'Aprovado pel'.$config['genero_autoridade'].' '.$config['autoridade'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->demanda_autoridade_aprovado > 0 ? 'Sim - ': 'Não - ').retorna_data($obj->demanda_autoridade_data).'</td></tr>';
if ($obj->demanda_autoridade_obs) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação d'.$config['genero_autoridade'].' '.ucfirst($config['autoridade']), 'A observação redigida pel'.$config['genero_autoridade'].' '.$config['autoridade'].' da demanda.').'Observação d'.$config['genero_autoridade'].' '.$config['autoridade'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->demanda_autoridade_obs.'</td></tr>';

if ($obj->demanda_cliente) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['cliente']), ucfirst($config['genero_cliente']).' '.$config['cliente'].' da demanda.').ucfirst($config['cliente']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->demanda_cliente, '','','esquerda').'</td></tr>';
if ($obj->demanda_cliente_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovado pel'.$config['genero_cliente'].' '.ucfirst($config['cliente']), ucfirst($config['genero_cliente']).' '.$config['cliente'].' da demanda aprovou ou reprovou a mesma.').'Aprovado pel'.$config['genero_cliente'].' '.$config['cliente'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->demanda_cliente_aprovado > 0 ? 'Sim - ': 'Não - ').retorna_data($obj->demanda_cliente_data).'</td></tr>';
if ($obj->demanda_cliente_obs) echo '<tr><td align="right" nowrap="nowrap">'.dica('Observação d'.$config['genero_cliente'].' '.ucfirst($config['cliente']), 'A observação redigida pel'.$config['genero_cliente'].' '.$config['cliente'].' da demanda.').'Observação d'.$config['genero_cliente'].' '.$config['cliente'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->demanda_cliente_obs.'</td></tr>';

if ($obj->demanda_codigo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'O código da demanda.').'Código:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->demanda_codigo.'</td></tr>';
if ($obj->demanda_setor) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce a demanda.').ucfirst($config['setor']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSetor().'</td></tr>';
if ($obj->demanda_segmento) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce a demanda.').ucfirst($config['segmento']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getSegmento().'</td></tr>';
if ($obj->demanda_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce a demanda.').ucfirst($config['intervencao']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getIntervencao().'</td></tr>';
if ($obj->demanda_tipo_intervencao) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence a demanda.').ucfirst($config['tipo']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->getTipoIntervencao().'</td></tr>';
if ($obj->demanda_identificacao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Identificação', 'Descrição da demanda, contendo as informações necessárias para entendimento da necessidade.').'Identificação:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_identificacao.'</td></tr>';
if ($obj->demanda_justificativa) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Justificativa', 'Descrição da justificativa contendo um breve histórico e as motivações da demanda.').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_justificativa.'</td></tr>';
if ($obj->demanda_resultados) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Resultados a Serem Alcançados', 'Descrição dos resultados a serem alcançadas com o atendimento da demanda.').'Resultados:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_resultados.'</td></tr>';
if ($obj->demanda_alinhamento) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Alinhamento Estratégico', 'Descrição do alinhamento da demanda com os instrumentos de planejamento institucional.').'Alinhamento:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_alinhamento.'</td></tr>';
if ($obj->demanda_fonte_recurso) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Fonte de Recurso', 'Indicação da fonte de recursos para as despesas da demanda.').'Fonte de recursos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_fonte_recurso.'</td></tr>';
if ($obj->demanda_prazo) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Prazo', 'Informações sobre o prazo de execução.').'Prazo:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_prazo.'</td></tr>';
if ($obj->demanda_custos) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Custos', 'Informações sobre o custo de execução.').'Custos:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_custos.'</td></tr>';
if ($obj->demanda_observacao) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Observações', 'Informações gerais sobre a demanda').'Observações:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->demanda_observacao.'</td></tr>';



if ($Aplic->profissional){
	$sql->adTabela('demanda_gestao');
	$sql->adCampo('demanda_gestao.*');
	$sql->adOnde('demanda_gestao_demanda ='.(int)$demanda_id);	
	$sql->adOrdem('demanda_gestao_ordem');
	$lista = $sql->Lista();
	$sql->Limpar();
	$qnt=0;
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
	if (count($lista)) {
		echo '<tr><td align="right" nowrap="nowrap" valign="middle">'.dica('Relacionada', 'A que área este demanda está relacionada.').'Relacionada:'.dicaF().'</td></td><td class="realce">';	
		foreach($lista as $gestao_data){
			if ($gestao_data['demanda_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['demanda_gestao_tarefa']);
			elseif ($gestao_data['demanda_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['demanda_gestao_projeto']);
			elseif ($gestao_data['demanda_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['demanda_gestao_pratica']);
			elseif ($gestao_data['demanda_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['demanda_gestao_acao']);
			elseif ($gestao_data['demanda_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['demanda_gestao_perspectiva']);
			elseif ($gestao_data['demanda_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['demanda_gestao_tema']);
			elseif ($gestao_data['demanda_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['demanda_gestao_objetivo']);
			elseif ($gestao_data['demanda_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['demanda_gestao_fator']);
			elseif ($gestao_data['demanda_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['demanda_gestao_estrategia']);
			elseif ($gestao_data['demanda_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['demanda_gestao_meta']);
			elseif ($gestao_data['demanda_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['demanda_gestao_canvas']);
			elseif ($gestao_data['demanda_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['demanda_gestao_risco']);
			elseif ($gestao_data['demanda_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['demanda_gestao_risco_resposta']);
			elseif ($gestao_data['demanda_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['demanda_gestao_indicador']);
			elseif ($gestao_data['demanda_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['demanda_gestao_calendario']);
			elseif ($gestao_data['demanda_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['demanda_gestao_monitoramento']);
			elseif ($gestao_data['demanda_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['demanda_gestao_ata']);
			elseif (isset($gestao_data['demanda_gestao_swot']) && $gestao_data['demanda_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['demanda_gestao_swot']);
			elseif (isset($gestao_data['demanda_gestao_operativo']) && $gestao_data['demanda_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['demanda_gestao_operativo']);
			elseif ($gestao_data['demanda_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['demanda_gestao_instrumento']);
			elseif ($gestao_data['demanda_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['demanda_gestao_recurso']);
			elseif ($gestao_data['demanda_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['demanda_gestao_problema']);
			elseif ($gestao_data['demanda_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['demanda_gestao_programa']);
			elseif ($gestao_data['demanda_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['demanda_gestao_licao']);
			elseif ($gestao_data['demanda_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['demanda_gestao_evento']);
			elseif ($gestao_data['demanda_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['demanda_gestao_link']);
			elseif ($gestao_data['demanda_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['demanda_gestao_avaliacao']);
			elseif ($gestao_data['demanda_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['demanda_gestao_tgn']);
			elseif ($gestao_data['demanda_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['demanda_gestao_brainstorm']);
			elseif ($gestao_data['demanda_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['demanda_gestao_gut']);
			elseif ($gestao_data['demanda_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['demanda_gestao_causa_efeito']);
			elseif ($gestao_data['demanda_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['demanda_gestao_arquivo']);
			elseif ($gestao_data['demanda_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['demanda_gestao_forum']);
			elseif ($gestao_data['demanda_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['demanda_gestao_checklist']);
			elseif ($gestao_data['demanda_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['demanda_gestao_agenda']);
			elseif ($gestao_data['demanda_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['demanda_gestao_agrupamento']);
			elseif ($gestao_data['demanda_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['demanda_gestao_patrocinador']);
			elseif ($gestao_data['demanda_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['demanda_gestao_template']);
			elseif ($gestao_data['demanda_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['demanda_gestao_painel']);
			elseif ($gestao_data['demanda_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['demanda_gestao_painel_odometro']);
			elseif ($gestao_data['demanda_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['demanda_gestao_painel_composicao']);		
			elseif ($gestao_data['demanda_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['demanda_gestao_tr']);	
			}
		echo '</td></tr>';
		}	
	}
			

$custo_estimado=($Aplic->profissional ? $obj->custo_estimado() : 0);



if ($custo_estimado > 0) echo '<tr><td align="right" nowrap="nowrap" style="width:110px;">'.dica('Planilha de Custos', 'A planilha de custos é a soma dos valores dos itens.').'Planilha custos:'.dicaF().'</td><td class="realce" width="300">'.$config['simbolo_moeda'].' '.number_format($custo_estimado, 2, ',', '.').' '.'<a href="javascript: void(0);" onclick="javascript:window.parent.gpwebApp.popUp(\'Planilha\', 1024, 600, \'m=projetos&a=demanda_planilha&dialogo=1&demanda_id='.$demanda_id.'\', null, window);">'.dica('Planilha de Custos Estimados', 'Clique neste ícone '.imagem('icones/planilha_estimado.gif').' para visualizar a planilha de custos estimados.').imagem('icones/planilha_estimado.gif').dicaF().'</a></td></tr>';

if ($obj->demanda_caracteristica_projeto==-1 || $obj->demanda_caracteristica_projeto==1) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Tem Característica de '.ucfirst($config['projeto']), 'Após análise da demanda, a mesma tem característica de '.$config['projeto'].'.').'Característica de '.$config['projeto'].':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.(($editar && $Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'demanda'))||$Aplic->usuario_super_admin ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=demanda_analise&demanda_id='.$demanda_id.'\');">'.dica('Editar','Clique neste link para ver ou modificar a análise de demanda.') : '').($obj->demanda_caracteristica_projeto > 0 ? 'Sim' : 'Não').(($editar && $Aplic->checarModulo('projetos', 'adicionar', $Aplic->usuario_id, 'demanda'))||$Aplic->usuario_super_admin ? dicaF() : '').'</td></tr>';				
if ($obj->demanda_viabilidade) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Estudo de Viabilidade', 'Visualização dos detalhes do estudo de viabilidade desta demanda.').'Estudo de viabilidade:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_viabilidade($obj->demanda_viabilidade).'</td></tr>';				
if ($obj->demanda_termo_abertura) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Termo de Abertura', 'Visualização dos detalhes do termo de abertura.').'Termo de abertura:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_termo_abertura($obj->demanda_termo_abertura).'</td></tr>';				
if ($obj->demanda_projeto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['projeto']), 'Visualização dos detalhes do '.$config['projeto'].' que foi criado basead'.$config['genero_projeto'].' nesta demanda.').ucfirst($config['projeto']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_projeto($obj->demanda_projeto).'</td></tr>';				
		
	
	
if ($Aplic->profissional){
	$sql->adTabela('pi');
	$sql->adOnde('pi_demanda = '.(int)$demanda_id);
	$sql->adCampo('pi_pi');
	$sql->adOrdem('pi_ordem');
	$pi=$sql->carregarColuna();
	$sql->limpar();
	if (count($pi)) echo '<tr><td align="right">'.dica('PI', 'Os PI relacionados com '.($portfolio ? $config['genero_portfolio'].' '.$config['portfolio'].' ' : '').'da demanda.').'PI:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $pi).'</td></tr>';
	
	$sql->adTabela('ptres');
	$sql->adOnde('ptres_demanda = '.(int)$demanda_id);
	$sql->adCampo('ptres_ptres');
	$sql->adOrdem('ptres_ordem');
	$ptres=$sql->carregarColuna();
	$sql->limpar();
	if (count($ptres)) echo '<tr><td align="right" nowrap="nowrap">'.dica('PTRES', 'Os PTRES relacionados com '.($portfolio ? $config['genero_portfolio'].' '.$config['portfolio'].' ' : '').'da demanda.').'PTRES:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $ptres).'</td></tr>';
	
	
	if ($lista_portfolio){
		$saida_fiho=array();
		foreach($lista_portfolio as $demanda_filho) $saida_fiho[]=link_demanda($demanda_filho);
		echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['portfolio']).' de Demandas', ucfirst($config['genero_portfolio']).' '.$config['portfolio'].' de demandas incluídas').ucfirst($config['portfolio']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.implode('<br>', $saida_fiho).'</td></tr>';
		}
	}	

if ($obj->demanda_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->demanda_principal_indicador).'</td></tr>';
	
		
if ($Aplic->profissional  && count($assinaturas)) echo '<tr><td align="right" nowrap="nowrap">'.dica('Aprovada', 'Se a demanda se encontra aprovada.').'Aprovada:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->demanda_aprovado ? 'Sim' : '<span style="color:red; font-weight:bold">Não</span>').'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativa', 'Se a demanda se encontra ativa.').'Ativa:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->demanda_ativa ? 'Sim' : 'Não').'</td></tr>';
		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('demandas', $obj->demanda_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
	
	
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/demanda_ver_pro.php';		
		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

if($Aplic->checarModulo('arquivos', 'acesso') && $Aplic->modulo_ativo('arquivos')){
	$caixaTab = new CTabBox('m=projetos&a=demanda_ver&demanda_id='.$obj->demanda_id.'&projeto_id='.$obj->demanda_projeto, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fóruns relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
	if ($Aplic->profissional && $Aplic->profissional && $Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa();
	}
	
?>
<script language="javascript">

function  nao_aprovar_abertura(){
	url_passar(0, 'm=projetos&a=termo_abertura_nao_aprovar&projeto_abertura_id='+document.getElementById('projeto_abertura_id').value);
	}	
	
function  aprovar_abertura(){
	url_passar(0, 'm=projetos&a=termo_abertura_aprovar&projeto_abertura_id='+document.getElementById('projeto_abertura_id').value);
	}	

function excluir() {
	if (confirm('Tem certeza que deseja excluir este demanda')) {
		var f = document.env;
		f.excluir.value=1;
		f.a.value='fazer_sql_demanda';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>