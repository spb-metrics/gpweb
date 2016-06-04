<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pg_meta_id = intval(getParam($_REQUEST, 'pg_meta_id', 0));

require_once (BASE_DIR.'/modulos/praticas/meta.class.php');
$obj=new CMeta();
$obj->load($pg_meta_id);

$sql = new BDConsulta;

if (!permiteAcessarMeta($obj->pg_meta_acesso,$pg_meta_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');



if (isset($_REQUEST['tab'])) $Aplic->setEstado('VerMetaTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('VerMetaTab') !== null ? $Aplic->getEstado('VerMetaTab') : 0;
$msg = '';
$editar=($podeEditar&& permiteEditarMeta($obj->pg_meta_acesso,$pg_meta_id));

if (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_meta'].' '.ucfirst($config['meta']), 'meta.gif', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=praticas&a=meta_lista', 'lista','','Lista de '.ucfirst($config['metas']),'Clique neste botão para visualizar a lista de '.$config['metas'].'.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas&a=meta_editar&pg_meta_id='.$pg_meta_id, 'editar','','Editar','Editar os detalhes d'.$config['genero_meta'].' '.$config['meta'].'.');
		$botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Indicador', 'Criar um novo indicador relacionado a est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=indicador_editar&pratica_indicador_meta='.$pg_meta_id.'\');" ><span>indicador</span></a>'.dicaF().'</td></tr></table>');
		$botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar '.$config['acao'].' relacionad'.$config['genero_acao'].' relacionad'.$config['genero_acao'].' a est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_meta='.$pg_meta_id.'\');" ><span>'.strtolower($config['acao']).'</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Evento', 'Criar um novo evento.<br><br>Os eventos são atividades com data e hora específicas.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=calendario&a=editar&evento_meta='.$pg_meta_id.'\');" ><span>evento</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Arquivo', 'Inserir um novo arquivo relacionado a est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=arquivos&a=editar&arquivo_meta='.$pg_meta_id.'\');" ><span>arquivo</span></a>'.dicaF().'</td></tr></table>');
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Link', 'Inserir um novo link relacionado a est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=links&a=editar&link_meta='.$pg_meta_id.'\');" ><span>link</span></a>'.dicaF().'</td></tr></table>');
		if ($podeExcluir) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' do sistema.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir a Meta', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_meta'].' '.$config['meta'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&pg_meta_id='.$pg_meta_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


if (!$dialogo && $Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_meta'].' '.ucfirst($config['meta']).'', 'meta.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['metas']),'Clique neste botão para visualizar a lista de '.$config['metas'].'.').'Lista de '.ucfirst($config['metas']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_lista\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Criar um nov'.$config['genero_meta'].' '.$config['meta'].'.').'Nov'.$config['genero_meta'].' '.ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&meta_id=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_meta=".$pg_meta_id."\");");	
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_meta=".$pg_meta_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&meta_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_meta=".$pg_meta_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_meta=".$pg_meta_id."\");");
		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) $km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['meta']),'Editar os detalhes d'.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].'.').'Editar '.ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=meta_editar&pg_meta_id=".$pg_meta_id."\");");
	if ($podeExcluir &&$editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].' do sistema.').'Excluir '.ucfirst($config['meta']).dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_meta'].' '.$config['meta'].'.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&pg_meta_id=".$pg_meta_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="meta_ver" />';
echo '<input type="hidden" name="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';
	
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%"  >';

if (!$Aplic->profissional){
	$sql->adTabela('causa_efeito_metas');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito.causa_efeito_id=causa_efeito_metas.causa_efeito_id');
	$sql->adCampo('causa_efeito_metas.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_metas.pg_meta_id='.$pg_meta_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.$causa_efeito['causa_efeito_id'].'&pg_meta_id='.$pg_meta_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}
else{
	$sql->adTabela('causa_efeito_gestao');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_id=causa_efeito_gestao_causa_efeito');
	$sql->adCampo('causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_gestao_meta='.$pg_meta_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.$causa_efeito['causa_efeito_id'].'&pg_meta_id='.$pg_meta_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}




if (!$Aplic->profissional){
	$sql->adTabela('gut_metas');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_metas.gut_id');
	$sql->adCampo('gut_metas.gut_id, gut_nome, gut_acesso');
	$sql->adOnde('pg_meta_id='.$pg_meta_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.$gut['gut_id'].'&pg_meta_id='.$pg_meta_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}
else{
	$sql->adTabela('gut_gestao');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_gestao_gut');
	$sql->adCampo('gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_gestao_meta='.$pg_meta_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.$gut['gut_id'].'&pg_meta_id='.$pg_meta_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}


if (!$Aplic->profissional){
	$sql->adTabela('brainstorm_metas');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_metas.brainstorm_id');
	$sql->adCampo('brainstorm_metas.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('pg_meta_id='.$pg_meta_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.$brainstorm['brainstorm_id'].'&pg_meta_id='.$pg_meta_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';
	}
else{
	$sql->adTabela('brainstorm_gestao');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_gestao_brainstorm');
	$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_gestao_meta='.$pg_meta_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.$brainstorm['brainstorm_id'].'&pg_meta_id='.$pg_meta_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';

	}		

$cor_indicador=cor_indicador('meta', null, null, null, null, $obj->pg_meta_principal_indicador);

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->pg_meta_cor.'" colspan="2"><font color="'.melhorCor($obj->pg_meta_cor).'"><b>'.$obj->pg_meta_nome.'<b></font>'.$cor_indicador.$saida_brainstorm.$saida_causa_efeito.$saida_gut.'</td></tr>';






if ($obj->pg_meta_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->pg_meta_cia).'</td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('meta_cia');
	$sql->adCampo('meta_cia_cia');
	$sql->adOnde('meta_cia_meta = '.(int)$pg_meta_id);
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

$sql->adTabela('metas_depts');
$sql->adCampo('dept_id');
$sql->adOnde('pg_meta_id = '.(int)$pg_meta_id);
$departamentos = $sql->Lista();
$sql->limpar();

if ($obj->pg_meta_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->pg_meta_dept).'</td></tr>';
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
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

if ($obj->pg_meta_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável por gerenciar '.$config['genero_meta'].' '.$config['meta'].'.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->pg_meta_responsavel, '','','esquerda').'</td></tr>';		

$sql->adTabela('metas_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=metas_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('pg_meta_id = '.(int)$pg_meta_id);
$designados = $sql->Lista();
$sql->limpar();
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


if ($obj->pg_meta_descricao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Descrição d'.$config['genero_meta'].' '.$config['meta'].'.').'Descrição:'.dicaF().'</td><td class="realce">'.$obj->pg_meta_descricao.'</td></tr>';



if ($Aplic->profissional){
	$tipo_pontuacao=array(
		''=>'Manual',
		'media_ponderada'=>'Média ponderada das percentagens dos objetos relacionados',
		'pontos_completos'=>'Pontos dos objetos relacionados desde que com percentagens completas',
		'pontos_parcial'=>'Pontos dos objetos relacionados mesmo com percentagens incompletas',
		'indicador'=>'Indicador principal'
		);
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Sistema de Percentagem', 'Qual forma a percentagem d'.$config['genero_meta'].' '.$config['meta'].' é calculada.').'Sistema de percentagem:'.dicaF().'</td><td class="realce">'.(isset($tipo_pontuacao[$obj->pg_meta_tipo_pontuacao]) ? $tipo_pontuacao[$obj->pg_meta_tipo_pontuacao] : '').'</td></tr>';
	if ($obj->pg_meta_tipo_pontuacao && $obj->pg_meta_tipo_pontuacao!='indicador'){
		$sql->adTabela('meta_media');
		$sql->adOnde('meta_media_meta = '.(int)$pg_meta_id);
		$sql->adOnde('meta_media_tipo = \''.$obj->pg_meta_tipo_pontuacao.'\'');
		$sql->adCampo('meta_media.*');
		$sql->adOrdem('meta_media_ordem');
		$medias=$sql->Lista();
		$sql->limpar();
		
		$tipo=$obj->pg_meta_tipo_pontuacao;
		$inserir_objeto=(($tipo=='media_ponderada' || $tipo=='pontos_completos' || $tipo=='pontos_parcial') ? true : false);
		$inserir_pontuacao=(($tipo=='pontos_completos' || $tipo=='pontos_parcial') ? true : false);
		$inserir_peso=($tipo=='media_ponderada' ? true : false);

		if (count($medias)) {
			echo '<tr><td align="right" nowrap="nowrap"></td><td><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th>Objeto</th>'.($inserir_peso ? '<th>Peso</th>' : '').($inserir_pontuacao ? '<th>Pontuação</th>' : '').'</tr>';
			foreach ($medias as $contato_id => $media) {
				echo '<tr align="center">';
				if ($inserir_objeto && $media['meta_media_projeto']) echo '<td align="left">'.imagem('icones/projeto_p.gif').link_projeto($media['meta_media_projeto']).'</td>';
				elseif ($inserir_objeto && $media['meta_media_acao']) echo '<td align="left">'.imagem('icones/plano_acao_p.gif').link_acao($media['meta_media_acao']).'</td>';
				if ($inserir_peso) echo '<td align="right">'.number_format($media['meta_media_peso'], 2, ',', '.').'</td>';
				if ($inserir_pontuacao) echo '<td align="right">'.number_format($media['meta_media_ponto'], 2, ',', '.').'</td>';
				echo '</tr>';
				}
			echo '</table></td></tr>';
			}
		}
		
	if ($obj->pg_meta_tipo_pontuacao=='pontos_completos' || $obj->pg_meta_tipo_pontuacao=='pontos_parcial') echo '<tr><td align=right>'.dica('Pontuação Alvo', 'A pontuação necessária da soma das filhas para que '.$config['genero_meta'].' '.$config['meta'].' fique com progresso em 100%.').'Pontuação Alvo:'.dicaF().'</td><td class="realce">'.number_format((float)$obj->pg_meta_ponto_alvo, 2, ',', '.').'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Progresso', $config['meta'].' pode ir de 0% (não iniciado) até 100% (completado).').'Progresso:'.dicaF().'</td><td class="realce">'.number_format((float)$obj->pg_meta_percentagem, 2, ',', '.').'%</td></tr>';
	}	


if (!$Aplic->profissional){
	if ($obj->pg_meta_estrategia) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Iniciativa', 'A iniciativa a qual est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' atende.').'Iniciativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_estrategia($obj->pg_meta_estrategia).'</td></tr>';
	elseif ($obj->pg_meta_fator)	echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['fator']), ucfirst($config['genero_fator']).' '.$config['fator'].' que est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' atende, por meio da iniciativa.').ucfirst($config['fator']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_fator($obj->pg_meta_fator).'</td></tr>';
	elseif ($obj->pg_meta_objetivo_estrategico) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), ucfirst($config['genero_objetivo']).' '.$config['objetivo'].' ao qual est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' atende, por meio do fator crítico ao sucesso.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_objetivo($obj->pg_meta_objetivo_estrategico).'</td></tr>';
	elseif ($obj->pg_meta_tema) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['tema']), ucfirst($config['genero_tema']).' '.$config['tema'].' ao qual atende.').ucfirst($config['tema']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_tema($obj->pg_meta_tema).'</td></tr>';
	elseif ($obj->pg_meta_perspectiva) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']), ucfirst($config['genero_perspectiva']).' '.$config['perspectiva'].' que est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' atende.').''.ucfirst($config['perspectiva']).':'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.link_perspectiva($obj->pg_meta_perspectiva).'</td></tr>';
	}


if ($Aplic->profissional){
	$sql->adTabela('meta_gestao');
	$sql->adCampo('meta_gestao.*');
	$sql->adOnde('meta_gestao_meta ='.(int)$pg_meta_id);
	$sql->adOrdem('meta_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
  if (count($lista)) {
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
		echo '<tr><td  align="right" nowrap="nowrap">'.dica('Relacionad'.$config['genero_meta'],'A qual parte do sistema '.$config['genero_meta'].' '.$config['meta'].' está relacionad'.$config['genero_meta'].'.').'Relacionad'.$config['genero_meta'].':'.dicaF().'</td><td width="100%" class="realce">';
		$qnt=0;
		foreach($lista as $gestao_data){
			if ($gestao_data['meta_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['meta_gestao_tarefa']);
			elseif ($gestao_data['meta_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['meta_gestao_projeto']);
			elseif ($gestao_data['meta_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['meta_gestao_pratica']);
			elseif ($gestao_data['meta_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['meta_gestao_acao']);
			elseif ($gestao_data['meta_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['meta_gestao_perspectiva']);
			elseif ($gestao_data['meta_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['meta_gestao_tema']);
			elseif ($gestao_data['meta_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['meta_gestao_objetivo']);
			elseif ($gestao_data['meta_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['meta_gestao_fator']);
			elseif ($gestao_data['meta_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['meta_gestao_estrategia']);
			elseif ($gestao_data['meta_gestao_meta2']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['meta_gestao_meta2']);
			elseif ($gestao_data['meta_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['meta_gestao_canvas']);
			elseif ($gestao_data['meta_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['meta_gestao_risco']);
			elseif ($gestao_data['meta_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['meta_gestao_risco_resposta']);
			elseif ($gestao_data['meta_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['meta_gestao_indicador']);
			elseif ($gestao_data['meta_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['meta_gestao_calendario']);
			elseif ($gestao_data['meta_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['meta_gestao_monitoramento']);
			elseif ($gestao_data['meta_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['meta_gestao_ata']);
			elseif ($gestao_data['meta_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['meta_gestao_swot']);
			elseif ($gestao_data['meta_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['meta_gestao_operativo']);
			elseif ($gestao_data['meta_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['meta_gestao_instrumento']);
			elseif ($gestao_data['meta_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['meta_gestao_recurso']);
			elseif ($gestao_data['meta_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['meta_gestao_problema']);
			elseif ($gestao_data['meta_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['meta_gestao_demanda']);
			elseif ($gestao_data['meta_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['meta_gestao_programa']);
			elseif ($gestao_data['meta_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['meta_gestao_licao']);
			elseif ($gestao_data['meta_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['meta_gestao_evento']);
			elseif ($gestao_data['meta_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['meta_gestao_link']);
			elseif ($gestao_data['meta_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['meta_gestao_avaliacao']);
			elseif ($gestao_data['meta_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['meta_gestao_tgn']);
			elseif ($gestao_data['meta_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['meta_gestao_brainstorm']);
			elseif ($gestao_data['meta_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['meta_gestao_gut']);
			elseif ($gestao_data['meta_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['meta_gestao_causa_efeito']);
			elseif ($gestao_data['meta_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['meta_gestao_arquivo']);
			elseif ($gestao_data['meta_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['meta_gestao_forum']);
			elseif ($gestao_data['meta_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['meta_gestao_checklist']);
			elseif ($gestao_data['meta_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['meta_gestao_agenda']);
			elseif ($gestao_data['meta_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['meta_gestao_agrupamento']);
			elseif ($gestao_data['meta_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['meta_gestao_patrocinador']);
			elseif ($gestao_data['meta_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['meta_gestao_template']);
			elseif ($gestao_data['meta_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['meta_gestao_painel']);
			elseif ($gestao_data['meta_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['meta_gestao_painel_odometro']);
			elseif ($gestao_data['meta_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['meta_gestao_painel_composicao']);
			elseif ($gestao_data['meta_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['meta_gestao_tr']);	
			elseif ($gestao_data['meta_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['meta_gestao_me']);	
			}
		echo '</td></tr>';
		}
	}


if ($obj->pg_meta_principal_indicador) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Dentre os indicadores da meta o mais representativo da situação geral da mesma.').'Indicador principal:'.dicaF().'</td><td width="100%" class="realce">'.link_indicador($obj->pg_meta_principal_indicador).'</td></tr>';





$objetivo_estrategico_id=0;
$fator_critico_id=0;

if ($obj->pg_meta_oque) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').'O Que:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_oque.'</td></tr>';
if ($obj->pg_meta_porque) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' será executada.').'Por que:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_porque.'</td></tr>';
if ($obj->pg_meta_onde) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Onde Fazer', 'Onde est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' é executada.').'Onde:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_onde.'</td></tr>';
if ($obj->pg_meta_quando) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quando Fazer', 'Quando est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].' é executada.').'Quando:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_quando.'</td></tr>';
if ($obj->pg_meta_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').'Quem:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_quem.'</td></tr>';
if ($obj->pg_meta_como) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Como Fazer', 'Como a meta é executada.').'Como:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_como.'</td></tr>';
if ($obj->pg_meta_quanto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para executar a meta.').'Quanto:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_quanto.'</td></tr>';
if ($obj->pg_meta_desde_quando || $obj->pg_meta_controle || $obj->pg_meta_metodo_aprendizado || $obj->pg_meta_melhorias){	
	echo '<tr><td colspan=20><b>Controle</b></td></tr>';
	if ($obj->pg_meta_desde_quando) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Desde Quando é Feita', 'Desde quando a meta é executada.').'Desde quando:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_desde_quando.'</td></tr>';
	if ($obj->pg_meta_controle) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Método de Controle', 'Como a meta é controlada.').'Controle:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_controle.'</td></tr>';
	if ($obj->pg_meta_metodo_aprendizado) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado da meta.').'Aprendizado:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_metodo_aprendizado.'</td></tr>';
	if ($obj->pg_meta_melhorias) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Melhorias Efetuadas n'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Quais as melhorias realizadas na meta após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_meta_melhorias.'</td></tr>';
	}



$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.(isset($acesso[$obj->pg_meta_acesso]) ? $acesso[$obj->pg_meta_acesso] : '').'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativa', 'Se a meta se encontra ativa.').'Ativa:'.dicaF().'</td><td  class="realce" width="100%">'.($obj->pg_meta_ativo ? 'Sim' : 'Não').'</td></tr>';
	
	
			
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('metas', $obj->pg_meta_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		

				
$sql->adTabela('meta_meta');
$sql->adCampo('formatar_data(meta_meta_data_inicio, "%d/%m/%Y") AS data, formatar_data(meta_meta_data_fim, "%d/%m/%Y") AS data_meta');
$sql->adCampo('meta_meta_id, meta_meta_valor_meta, meta_meta_valor_meta_boa, meta_meta_valor_meta_regular, meta_meta_valor_meta_ruim');
$sql->adOnde('meta_meta_meta = '.(int)$pg_meta_id);
$sql->adOrdem('meta_meta_data_inicio');
$metas = $sql->lista();
$sql->limpar();
if (count($metas)){
	echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Valor da Meta','Lista de valores da meta.').'&nbsp;<b>Valor da Meta</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
	echo '<tr><td colspan=20 align=center><div id="metas">';
	if (count($metas)){
		echo '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Início</th><th>Fim</th><th>Meta</th>'.($Aplic->profissional ? '<th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'</tr>';
		foreach($metas as $linha) {
			echo '<tr>';
			echo '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';	
			echo '<td align=right>'.number_format($linha['meta_meta_valor_meta'], 2, ',', '.').'</td>';
			if ($Aplic->profissional){
				echo '<td align=right>'.($linha['meta_meta_valor_meta_boa'] != null ? number_format($linha['meta_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['meta_meta_valor_meta_regular'] != null ? number_format($linha['meta_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
				echo '<td align=right>'.($linha['meta_meta_valor_meta_ruim'] != null ? number_format($linha['meta_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
				}
			echo '</tr>';
			}
		echo '</table>';
		}
	echo '</div></td></tr>';
	echo '</table></fieldset></td></tr>';
	}

		
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script language="javascript">self.print();</script>';

if (!$dialogo) {
	$caixaTab = new CTabBox('m=praticas&a=meta_ver&pg_meta_id='.$pg_meta_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	else {
		$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/meta_log_ver', 'Registros das Ocorrências',null,null,'Registros das Ocorrências','Visualizar os registros das ocorrências.<br><br>O registro é a forma padrão dos designados das ações informarem sobre o andamento e avisarem sobre problemas.');
		if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/meta_log_editar', 'Registrar',null,null,'Registrar','Inserir uma ocorrência.');
		}
	if ($Aplic->checarModulo('calendario', 'acesso') && $Aplic->modulo_ativo('calendario')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos dest'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.');
	if ($Aplic->checarModulo('arquivos', 'acesso') && $Aplic->modulo_ativo('arquivos')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados com est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.');
	if ($Aplic->checarModulo('links', 'acesso') && $Aplic->modulo_ativo('links')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados com est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.');
	if ($Aplic->checarModulo('foruns', 'acesso') && $Aplic->modulo_ativo('foruns')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fórus relacionados com est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados com est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.');
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
	}
?>
<script language="javascript">
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
	if (confirm( "Tem certeza que deseja excluir?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='meta_fazer_sql';
		f.modulo.value='objetivo';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>