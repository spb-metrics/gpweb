<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$pg_fator_critico_id = intval(getParam($_REQUEST, 'pg_fator_critico_id', 0));

require_once (BASE_DIR.'/modulos/praticas/fator.class.php');
$obj= new CFator();
$obj->load($pg_fator_critico_id);

$sql = new BDConsulta;


if (!permiteAcessarFator($obj->pg_fator_critico_acesso,$pg_fator_critico_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['tab'])) $Aplic->setEstado('FatorVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('FatorVerTab') !== null ? $Aplic->getEstado('FatorVerTab') : 0;
$msg = '';

$editar=($podeEditar&& permiteEditarFator($obj->pg_fator_critico_acesso,$pg_fator_critico_id));

if (!$dialogo && !$Aplic->profissional){
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'fator.gif', $m, $m.'.'.$a);
	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Evento', 'Criar um novo evento.<br><br>Os eventos são atividades com data e hora específicas.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=calendario&a=editar&evento_fator='.$pg_fator_critico_id.'\');" ><span>evento</span></a>'.dicaF().'</td></tr></table>');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Arquivo', 'Inserir um novo arquivo relacionado a '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=arquivos&a=editar&arquivo_fator='.$pg_fator_critico_id.'\');" ><span>arquivo</span></a>'.dicaF().'</td></tr></table>');
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Link', 'Inserir um novo link relacionado a '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=links&a=editar&link_fator='.$pg_fator_critico_id.'\');" ><span>link</span></a>'.dicaF().'</td></tr></table>');
	if ($podeAdicionar)  {
		$botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Novo Indicador', 'Criar um novo indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=indicador_editar&pratica_indicador_fator='.$pg_fator_critico_id.'\');" ><span>indicador</span></a>'.dicaF().'</td></tr></table>');
		$botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar '.$config['acao'].' relacionad'.$config['genero_acao'].' a '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_fator='.$pg_fator_critico_id.'\');" ><span>'.strtolower($config['acao']).'</span></a>'.dicaF().'</td></tr></table>');
		if(!$config['termo_abertura_obrigatorio'] && $Aplic->checarModulo('projetos', 'adicionar')) $botoesTitulo->adicionaCelula('<table cellpadding=1 cellspacing=0><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Criar nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].' a '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=projetos&a=editar&projeto_fator='.$pg_fator_critico_id.'\');" ><span>'.strtolower($config['projeto']).'</span></a>'.dicaF().'</td></tr></table>');
		}
	$botoesTitulo->adicionaBotao('m=praticas&a=fator_lista', 'lista','','Lista de '.ucfirst($config['fatores']),'Clique neste botão para visualizar a lista de '.$config['fatores'].'.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=praticas&a=fator_editar&pg_fator_critico_id='.$pg_fator_critico_id, 'editar','','Editar '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'','Editar os detalhes d'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.');
		if ($podeExcluir) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' do sistema.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir '.$config['genero_fator'].' '.ucfirst($config['fator']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir '.$config['genero_fator'].' '.$config['fator'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m='.$m.'&a='.$a.'&dialogo=1&pg_fator_critico_id='.$pg_fator_critico_id.'\');">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="fator_ver" />';
echo '<input type="hidden" name="pg_fator_critico_id" value="'.$pg_fator_critico_id.'" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '</form>';	

if (!$dialogo && $Aplic->profissional){	
	$Aplic->salvarPosicao();
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_fator'].' '.ucfirst($config['fator']), 'fator.gif', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['fatores']),'Clique neste botão para visualizar a lista de '.$config['fatores'].'.').'Lista de '.ucfirst($config['fatores']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_lista\");");
	if ($editar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Criar um nov'.$config['genero_fator'].' '.$config['fator'].'.').'Nov'.$config['genero_fator'].' '.ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar\");");
		
		$km->Add("inserir","inserir_registro",dica('Registro de Ocorrência','Inserir um novo registro de ocorrência.').'Registro de Ocorrência'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=log_editar_pro&pg_fator_critico_id=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'adicionar')) $km->Add("inserir","inserir_evento",dica('Novo Evento', 'Criar um novo evento relacionado.').'Evento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=calendario&a=editar&evento_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'adicionar')) $km->Add("inserir","inserir_arquivo",dica('Novo Arquivo', 'Inserir um novo arquivo relacionado.').'Arquivo'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=arquivos&a=editar&arquivo_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'adicionar')) $km->Add("inserir","inserir_link",dica('Novo Link', 'Inserir um novo link relacionado.').'Link'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=links&a=editar&link_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'adicionar')) $km->Add("inserir","inserir_forum",dica('Novo Fórum', 'Inserir um novo forum relacionado.').'Fórum'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=foruns&a=editar&forum_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'indicador')) 	$km->Add("inserir","inserir_indicador",dica('Novo Indicador', 'Inserir um novo indicador relacionado.').'Indicador'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=indicador_editar&pratica_indicador_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'plano_acao')) $km->Add("inserir","inserir_acao",dica('Nov'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Criar nov'.$config['genero_acao'].' '.$config['acao'].' relacionad'.$config['genero_acao'].'.').ucfirst($config['acao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=plano_acao_editar&plano_acao_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'adicionar')) $km->Add("inserir","inserir_projeto", dica('Nov'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Inserir nov'.$config['genero_projeto'].' '.$config['projeto'].' relacionad'.$config['genero_projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=editar&projeto_fator=".$pg_fator_critico_id."\");");	
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'adicionar')) $km->Add("inserir","inserir_mensagem",dica('Nov'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']), 'Inserir '.($config['genero_mensagem']=='a' ? 'uma' : 'um').' nov'.$config['genero_mensagem'].' '.$config['mensagem'].' relacionad'.$config['genero_mensagem'].'.').ucfirst($config['mensagem']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=email&a=nova_mensagem_pro&msg_fator=".$pg_fator_critico_id."\");");
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
				foreach($modelos as $rs) $km->Add("criar_documentos","novodocumento",$rs['modelo_tipo_nome'].'&nbsp;&nbsp;&nbsp;&nbsp;',	"javascript: void(0);' onclick='url_passar(0, \"m=email&a=modelo_editar&editar=1&novo=1&modelo_id=0&modelo_tipo_id=".$rs['modelo_tipo_id']."&modelo_fator=".$pg_fator_critico_id."\");", ($rs['imagem'] ? "estilo/rondon/imagens/icones/".$rs['imagem'] : ''));
				}
			}
		if ($Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'adicionar')) $km->Add("inserir","inserir_ata",dica('Nova Ata de Reunião', 'Inserir uma nova ata de reunião relacionada.').'Ata de reunião'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=atas&a=ata_editar&ata_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'adicionar')) $km->Add("inserir","inserir_problema",dica('Nov'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Inserir um'.($config['genero_problema']=='a' ? 'a' : '').' nov'.$config['genero_problema'].' '.$config['problema'].' relacionad'.$config['genero_problema'].'.').ucfirst($config['problema']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=problema&a=problema_editar&problema_fator=".$pg_fator_critico_id."\");");
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'adicionar', null, 'risco')) $km->Add("inserir","inserir_risco", dica('Nov'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Inserir um'.($config['genero_risco']=='a' ? 'a' : '').' nov'.$config['genero_risco'].' '.$config['risco'].' relacionad'.$config['genero_risco'].'.').ucfirst($config['risco']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=risco_pro_editar&risco_fator=".$pg_fator_critico_id."\");");

		}	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar) $km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['fator']),'Editar os detalhes d'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Editar '.ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=praticas&a=fator_editar&pg_fator_critico_id=".$pg_fator_critico_id."\");");
	if ($podeExcluir &&$editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' do sistema.').'Excluir '.ucfirst($config['fator']).dicaF(), "javascript: void(0);' onclick='excluir()");
	
	if ($obj->pg_fator_critico_tipo_pontuacao) $km->Add("acao","acao_percentagem",dica('Recalcular Progresso','Recalcula o progresso '.($config['genero_fator']=='a' ? 'desta' : 'deste').' '.$config['fator'].' para os caso de haver suspeita que o progresso não esteja calculado corretamente.').'Recalcular Progresso'.dicaF(), "javascript: void(0);' onclick='recalcular(".$pg_fator_critico_id.",".$obj->pg_fator_critico_percentagem.")");
	
	
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'], 'Visualize os detalhes d'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').' Detalhes d'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&pg_fator_critico_id=".$pg_fator_critico_id."\");");
	$km->Add("acao_imprimir","acao_imprimir1",dica('Valores', 'Visualize os valores envolvidos n'.$config['genero_projeto'].'s '.$config['projetos'].' subordinad'.$config['genero_projeto'].'s '.($config['genero_fator']=='a' ? 'à' : 'ao').' '.$config['fator'].'.').' Valores envolvidos'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=fator_valor_pizza_pro&dialogo=1&jquery=1&pg_fator_critico_id=".$pg_fator_critico_id."\");");


	
	echo $km->Render();
	echo '</td></tr></table>';
	}	
	
	
echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'class="std" ' : '').' width="100%"  >';

if (!$Aplic->profissional){
	$sql->adTabela('causa_efeito_fatores');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito.causa_efeito_id=causa_efeito_fatores.causa_efeito_id');
	$sql->adCampo('causa_efeito_fatores.causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_fatores.pg_fator_critico_id='.(int)$pg_fator_critico_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.$causa_efeito['causa_efeito_id'].'&pg_fator_critico_id='.$pg_fator_critico_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}
else{
	$sql->adTabela('causa_efeito_gestao');
	$sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_id=causa_efeito_gestao_causa_efeito');
	$sql->adCampo('causa_efeito_id, causa_efeito_nome, causa_efeito_acesso');
	$sql->adOnde('causa_efeito_gestao_fator='.(int)$pg_fator_critico_id);
	$causa_efeitos=$sql->Lista();
	$sql->limpar();
	$saida_causa_efeito='';
	foreach($causa_efeitos as $causa_efeito) if (permiteAcessarCausa_efeito($causa_efeito['causa_efeito_acesso'],$causa_efeito['causa_efeito_id'])) $saida_causa_efeito.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=causa_efeito&dialogo=1&causa_efeito_id='.$causa_efeito['causa_efeito_id'].'&pg_fator_critico_id='.$pg_fator_critico_id.'\', \'Causa_Efeito\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/causaefeito_p.png',$causa_efeito['causa_efeito_nome'],'Clique neste ícone '.imagem('icones/causaefeito_p.png').' para visualizar o diagrama de causa-efeito.').'</a>';
	}



if (!$Aplic->profissional){
	$sql->adTabela('gut_fatores');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_fatores.gut_id');
	$sql->adCampo('gut_fatores.gut_id, gut_nome, gut_acesso');
	$sql->adOnde('pg_fator_critico_id='.(int)$pg_fator_critico_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.$gut['gut_id'].'&pg_fator_critico_id='.$pg_fator_critico_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}
else{
	$sql->adTabela('gut_gestao');
	$sql->esqUnir('gut','gut','gut.gut_id=gut_gestao_gut');
	$sql->adCampo('gut_id, gut_nome, gut_acesso');
	$sql->adOnde('gut_gestao_fator='.(int)$pg_fator_critico_id);
	$guts=$sql->Lista();
	$sql->limpar();
	$saida_gut='';
	foreach($guts as $gut) if (permiteAcessarGUT($gut['gut_acesso'],$gut['gut_id'])) $saida_gut.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=gut&dialogo=1&gut_id='.$gut['gut_id'].'&pg_fator_critico_id='.$pg_fator_critico_id.'\', \'gut\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/gut_p.gif',$gut['gut_nome'],'Clique neste ícone '.imagem('icones/gut_p.gif').' para visualizar a matriz G.U.T.').'</a>';
	}	


if (!$Aplic->profissional){
	$sql->adTabela('brainstorm_fatores');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_fatores.brainstorm_id');
	$sql->adCampo('brainstorm_fatores.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('pg_fator_critico_id='.(int)$pg_fator_critico_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.$brainstorm['brainstorm_id'].'&pg_fator_critico_id='.$pg_fator_critico_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';
	}
else{
	$sql->adTabela('brainstorm_gestao');
	$sql->esqUnir('brainstorm','brainstorm','brainstorm.brainstorm_id=brainstorm_gestao_brainstorm');
	$sql->adCampo('brainstorm.brainstorm_id, brainstorm_nome, brainstorm_acesso');
	$sql->adOnde('brainstorm_gestao_fator='.(int)$pg_fator_critico_id);
	$brainstorms=$sql->Lista();
	$sql->limpar();
	$saida_brainstorm='';
	foreach($brainstorms as $brainstorm) if (permiteAcessarBrainstorm($brainstorm['brainstorm_acesso'],$brainstorm['brainstorm_id'])) $saida_brainstorm.='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=brainstorm&dialogo=1&brainstorm_id='.$brainstorm['brainstorm_id'].'&pg_fator_critico_id='.$pg_fator_critico_id.'\', \'Brainstorm\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/brainstorm_p.gif',$brainstorm['brainstorm_nome'],'Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para visualizar o brainstorm.').'</a>';

	}	

$cor_indicador=cor_indicador('fator', null, null, null, null, $obj->pg_fator_critico_principal_indicador);

echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->pg_fator_critico_cor.'" colspan="2"><font color="'.melhorCor($obj->pg_fator_critico_cor).'"><b>'.$obj->pg_fator_critico_nome.'<b></font>'.$cor_indicador.$saida_brainstorm.$saida_causa_efeito.$saida_gut.'</td></tr>';


$sql->adTabela('fatores_criticos_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=fatores_criticos_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('pg_fator_critico_id = '.(int)$pg_fator_critico_id);
$designados = $sql->Lista();
$sql->limpar();

$sql->adTabela('fatores_criticos_depts');
$sql->adCampo('dept_id');
$sql->adOnde('pg_fator_critico_id = '.(int)$pg_fator_critico_id);
$departamentos = $sql->Lista();
$sql->limpar();


if ($obj->pg_fator_critico_cia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' responsável.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->pg_fator_critico_cia).'</td></tr>';
if ($Aplic->profissional){
	$sql->adTabela('fator_cia');
	$sql->adCampo('fator_cia_cia');
	$sql->adOnde('fator_cia_fator = '.(int)$pg_fator_critico_id);
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
if ($obj->pg_fator_critico_dept) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', ucfirst($config['genero_dept']).' '.$config['departamento'].' responsável por '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_secao($obj->pg_fator_critico_dept).'</td></tr>';

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
if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' está envolvid'.$config['genero_dept'].' com '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';




if ($obj->pg_fator_critico_usuario) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', ucfirst($config['usuario']).' responsável por gerenciar '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->pg_fator_critico_usuario, '','','esquerda').'</td></tr>';		

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td class="realce">'.$saida_quem.'</td></tr>';

if ($obj->pg_fator_critico_descricao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Descrição d'.$config['genero_fator'].' '.$config['fator'].'.').'Descrição:'.dicaF().'</td><td class="realce">'.$obj->pg_fator_critico_descricao.'</td></tr>';


if ($Aplic->profissional){
	$tipo_pontuacao=array(
		''=>'Manual',
		'media_ponderada'=>'Média ponderada das percentagens dos objetos relacionados',
		'pontos_completos'=>'Pontos dos objetos relacionados desde que com percentagens completas',
		'pontos_parcial'=>'Pontos dos objetos relacionados mesmo com percentagens incompletas',
		'indicador'=>'Indicador principal'
		);
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Sistema de Percentagem', 'Qual forma a percentagem d'.$config['genero_tema'].' '.$config['tema'].' é calculada.').'Sistema de percentagem:'.dicaF().'</td><td class="realce">'.(isset($tipo_pontuacao[$obj->pg_fator_critico_tipo_pontuacao]) ? $tipo_pontuacao[$obj->pg_fator_critico_tipo_pontuacao] : '').'</td></tr>';
	if ($obj->pg_fator_critico_tipo_pontuacao && $obj->pg_fator_critico_tipo_pontuacao!='indicador'){
		$sql->adTabela('fator_media');
		$sql->adOnde('fator_media_fator = '.(int)$pg_fator_critico_id);
		$sql->adOnde('fator_media_tipo = \''.$obj->pg_fator_critico_tipo_pontuacao.'\'');
		$sql->adCampo('fator_media.*');
		$sql->adOrdem('fator_media_ordem');
		$medias=$sql->Lista();
		$sql->limpar();
		
		$tipo=$obj->pg_fator_critico_tipo_pontuacao;
		$inserir_pontuacao=(($tipo=='pontos_completos' || $tipo=='pontos_parcial') ? true : false);
		$inserir_peso=($tipo=='media_ponderada' ? true : false);

		if (count($medias)) {
			echo '<tr><td align="right" nowrap="nowrap"></td><td><table cellspacing=0 cellpadding=0 border=0 class="tbl1" align=left><tr><th>Objeto</th>'.($inserir_peso ? '<th>Peso</th>' : '').($inserir_pontuacao ? '<th>Pontuação</th>' : '').'</tr>';
			foreach ($medias as $contato_id => $media) {
				echo '<tr align="center">';
				if ($media['fator_media_projeto']) echo '<td align="left">'.imagem('icones/projeto_p.gif').link_projeto($media['fator_media_projeto']).'</td>';
				elseif ($media['fator_media_acao']) echo '<td align="left">'.imagem('icones/plano_acao_p.gif').link_acao($media['fator_media_acao']).'</td>';
				elseif ($media['fator_media_estrategia']) echo '<td align="left">'.imagem('icones/estrategia_p.gif').link_estrategia($media['fator_media_estrategia']).'</td>';
				if ($inserir_peso) echo '<td align="right">'.number_format($media['tema_media_peso'], 2, ',', '.').'</td>';
				if ($inserir_pontuacao) echo '<td align="right">'.number_format($media['tema_media_ponto'], 2, ',', '.').'</td>';
				echo '</tr>';
				}
			echo '</table></td></tr>';
			}
		}
		
	if ($obj->pg_fator_critico_tipo_pontuacao=='pontos_completos' || $obj->pg_fator_critico_tipo_pontuacao=='pontos_parcial') echo '<tr><td align=right>'.dica('Pontuação Alvo', 'A pontuação necessária da soma das filhas para que '.$config['genero_tema'].' '.$config['tema'].' fique com progresso em 100%.').'Pontuação Alvo:'.dicaF().'</td><td class="realce">'.number_format((float)$obj->pg_fator_critico_ponto_alvo, 2, ',', '.').'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Progresso', $config['tema'].' pode ir de 0% (não iniciado) até 100% (completado).').'Progresso:'.dicaF().'</td><td class="realce">'.number_format((float)$obj->pg_fator_critico_percentagem, 2, ',', '.').'%</td></tr>';
	}




$sql->adTabela('fator_objetivo');
$sql->adCampo('fator_objetivo_objetivo');
$sql->adOnde('fator_objetivo_fator ='.(int)$pg_fator_critico_id);
$sql->adOrdem('fator_objetivo_ordem');
$lista_objetivos = $sql->carregarColuna();
$sql->limpar();
$saida_objetivo='';
if ($lista_objetivos && count($lista_objetivos)) {
	$saida_objetivo.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
	$saida_objetivo.= '<tr><td>'.link_objetivo($lista_objetivos[0]);
	$qnt_lista_objetivos=count($lista_objetivos);
	if ($qnt_lista_objetivos > 1) {		
		$lista='';
		for ($i = 1, $i_cmp = $qnt_lista_objetivos; $i < $i_cmp; $i++) $lista.=link_objetivo($lista_objetivos[$i]).'<br>';		
		$saida_objetivo.= dica('Outr'.$config['genero_objetivo'].' '.ucfirst($config['objetivos']).'', 'Clique para visualizar '.$config['genero_objetivo'].'s demais '.$config['objetivos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_objetivo\');">(+'.($qnt_lista_objetivos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_objetivo"><br>'.$lista.'</span>';
		}
	$saida_objetivo.= '</td></tr></table>';
	} 
if ($saida_objetivo) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['objetivo']), ' Qual '.$config['objetivo'].' '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].' atende.').ucfirst($config['objetivo']).':'.dicaF().'</td><td class="realce" width="100%">'.$saida_objetivo.'</td></tr>';









$sql->adTabela('estrategia_fator');
$sql->adCampo('estrategia_fator_estrategia');
$sql->adOnde('estrategia_fator_fator ='.(int)$pg_fator_critico_id);
$sql->adOrdem('estrategia_fator_ordem');
$lista_estrategias = $sql->carregarColuna();
$sql->limpar();

$saida_estrategia='';
if ($lista_estrategias && count($lista_estrategias)) {
		$saida_estrategia.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_estrategia.= '<tr><td>'.link_estrategia($lista_estrategias[0]);
		$qnt_lista_estrategias=count($lista_estrategias);
		if ($qnt_lista_estrategias > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_estrategias; $i < $i_cmp; $i++) $lista.=link_estrategia($lista_estrategias[$i]).'<br>';		
				$saida_estrategia.= dica('Outr'.$config['genero_iniciativa'].'s '.ucfirst($config['iniciativas']), 'Clique para visualizar '.$config['genero_iniciativa'].'s demais '.$config['iniciativas'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_estrategia\');">(+'.($qnt_lista_estrategias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_estrategia"><br>'.$lista.'</span>';
				}
		$saida_estrategia.= '</td></tr></table>';
		} 

if ($saida_estrategia) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativas']).' Relacionad'.$config['genero_iniciativa'].'s', 'Quais '.$config['iniciativas'].' estão relacionad'.$config['genero_iniciativa'].'s a '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').ucfirst($config['iniciativas']).' filh'.$config['genero_iniciativa'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_estrategia.'</td></tr>';





if ($obj->pg_fator_critico_oque) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'O Que:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_oque.'</td></tr>';
if ($obj->pg_fator_critico_porque) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' será executad'.$config['genero_fator'].'.').'Por que:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_porque.'</td></tr>';
if ($obj->pg_fator_critico_onde) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Onde Fazer', 'Onde '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Onde:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_onde.'</td></tr>';
if ($obj->pg_fator_critico_quando) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quando Fazer', 'Quando '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Quando:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_quando.'</td></tr>';
if ($obj->pg_fator_critico_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Quem:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_quem.'</td></tr>';



if ($obj->pg_fator_critico_como) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Como Fazer', 'Como '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Como:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_como.'</td></tr>';
if ($obj->pg_fator_critico_quanto) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para executar '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Quanto:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_quanto.'</td></tr>';




	
if ($obj->pg_fator_critico_desde_quando || $obj->pg_fator_critico_controle || $obj->pg_fator_critico_metodo_aprendizado || $obj->pg_fator_critico_melhorias){	
	echo '<tr><td colspan=20><b>Controle</b></td></tr>';
	if ($obj->pg_fator_critico_desde_quando) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Desde Quando é Feita', 'Desde quando '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é executad'.$config['genero_fator'].'.').'Desde quando:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_desde_quando.'</td></tr>';
	if ($obj->pg_fator_critico_controle) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Método de Controle', 'Como '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' é controlad'.$config['genero_fator'].'.').'Controle:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_controle.'</td></tr>';
	if ($obj->pg_fator_critico_metodo_aprendizado) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado d'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.').'Aprendizado:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_metodo_aprendizado.'</td></tr>';
	if ($obj->pg_fator_critico_melhorias) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Melhorias Efetuadas n'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Quais as melhorias realizadas n'.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].' após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->pg_fator_critico_melhorias.'</td></tr>';
	}




$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.(isset($acesso[$obj->pg_fator_critico_acesso]) ? $acesso[$obj->pg_fator_critico_acesso] : '').'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativ'.$config['genero_fator'], 'Se '.$config['genero_fator'].' '.$config['fator'].' se encontra ativ'.$config['genero_fator'].'.').'Ativ'.$config['genero_fator'].':'.dicaF().'</td><td  class="realce" width="100%">'.($obj->pg_fator_critico_ativo ? 'Sim' : 'Não').'</td></tr>';


		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('fatores', $obj->pg_fator_critico_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
		
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();
else echo '<script language="javascript">self.print();</script>';

$objetivo_estrategico_id=null;
$perspectiva_id=null;
$tema_id=null;

if (!$dialogo) {
	$caixaTab = new CTabBox('m=praticas&a=fator_ver&pg_fator_critico_id='.$pg_fator_critico_id, '', $tab);
	if ($Aplic->profissional) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/log_ver_pro', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.');
	else {
		$caixaTab->adicionar(BASE_DIR.'/modulos/praticas/fator_log_ver', 'Registros',null,null,'Registros','Visualizar os registros das ocorrências.<br><br>O registro é a forma padrão dos designados das ações informarem sobre o andamento e avisarem sobre problemas.');
		if ($editar) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/fator_log_editar', 'Registrar',null,null,'Registrar','Inserir uma ocorrência.');
		}
	if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados.');
	if ($Aplic->modulo_ativo('arquivos') && $Aplic->checarModulo('arquivos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados.');
	if ($Aplic->modulo_ativo('links') && $Aplic->checarModulo('links', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/links/index_tabela', 'Links',null,null,'Links','Visualizar os links relacionados.');
	if ($Aplic->modulo_ativo('foruns') && $Aplic->checarModulo('foruns', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/foruns/forum_tabela', 'Fóruns',null,null,'Fóruns','Visualizar os fóruns relacionados.');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'indicador')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/indicadores_ver', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores relacionados.');
	if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/plano_acao_ver_idx', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/projetos/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' relacionad'.$config['genero_projeto'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) {
		$caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_msg_pro', ucfirst($config['mensagens']),null,null,ucfirst($config['mensagens']),ucfirst($config['genero_mensagem']).'s '.$config['mensagens'].' relacionad'.$config['genero_mensagem'].'s.');
		if ($config['doc_interno']) $caixaTab->adicionar(BASE_DIR.'/modulos/email/ver_modelo_pro', 'Documentos',null,null,'Documentos','Os documentos relacionados.');
		}
	if ($Aplic->profissional && $Aplic->modulo_ativo('atas') && $Aplic->checarModulo('atas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/atas/ata_tabela', 'Atas',null,null,'Atas','Visualizar as atas de reunião relacionadas.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('problema') && $Aplic->checarModulo('problema', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/problema/problema_tabela', ucfirst($config['problemas']),null,null,ucfirst($config['problemas']),'Visualizar '.$config['genero_problema'].'s '.$config['problemas'].' relacionad'.$config['genero_problema'].'s.');
	if ($Aplic->profissional && $Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'risco')) $caixaTab->adicionar(BASE_DIR.'/modulos/praticas/risco_pro_ver_idx', ucfirst($config['riscos']),null,null,ucfirst($config['riscos']),'Visualizar '.$config['genero_risco'].'s '.$config['riscos'].' relacionad'.$config['genero_risco'].'s.');

	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
?>
<script language="javascript">
	
function recalcular(pg_fator_critico_id, percentagem){
	parent.gpwebApp.popUp('Recalculo de Progresso do Físico', 550, 100, 'm=praticas&a=fator_recalcular_fisico_pro&dialogo=1&pg_fator_critico_id='+pg_fator_critico_id+'&percentagem='+percentagem, null, window);
	}


function excluir() {
	if (confirm( "Tem certeza que deseja excluir <?php echo ($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator']?>?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fator_fazer_sql';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>