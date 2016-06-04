<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/qualidade.class.php');
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar ver o plano de qualidade.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

$obj = new CQualidade();
$obj->load($projeto_id);
$sql = new BDConsulta();

if (!$dialogo) $Aplic->salvarPosicao();

$msg = '';

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_qualidade_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';

if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Plano de Qualidade d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste botão para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$projeto_id."\");");
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		if (!$obj->projeto_qualidade_usuario) $km->Add("inserir","inserir_qualidade",dica('Inserir Plano de Qualidade','Inserir os detalhes do plano de qualidade.').'Plano de Qualidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=qualidade_editar&projeto_id=".$projeto_id."\");");
		if ($obj->projeto_qualidade_usuario) $km->Add("inserir","inserir_qualidade",dica('Inserir Entregas no Plano de Qualidade','Inserir lista de entregas no plano de qualidade.').'Inserir Entregas'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=qualidade_entrega&projeto_id=".$projeto_id."\");");
		}	
	if ($obj->projeto_qualidade_usuario){
		$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
		if ($editar && $podeEditar) $km->Add("acao","editar_qualidade",dica('Editar Plano de Qualidade','Editar os detalhes do plano de qualidade.').'Editar Plano de Qualidade'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=qualidade_editar&projeto_id=".$projeto_id."\");");
		if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este plano de qualidade do sistema.').'Excluir Plano de Qualidade'.dicaF(), "javascript: void(0);' onclick='excluir()");
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
		$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Plano de Qualidade', 'Visualize os detalhes deste plano de qualidade.').' Detalhes do Plano de Qualidade'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=qualidade_imprimir&dialogo=1&projeto_id=".$projeto_id."\");");
		}	
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {	
	$botoesTitulo = new CBlocoTitulo('Plano de Qualidade d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	if ($editar && $podeEditar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=qualidade_editar&projeto_id='.$projeto_id, ($obj->projeto_qualidade_usuario ? 'editar' : 'inserir'),'',($obj->projeto_qualidade_usuario ? 'Editar' : 'Inserir').' Plano de Qualidade',($obj->projeto_qualidade_usuario ? 'Editar' : 'Inserir').' os detalhes do plano de qualidade.');
		if ($obj->projeto_qualidade_usuario) {
			$botoesTitulo->adicionaBotao('m=projetos&a=qualidade_entrega&projeto_id='.$projeto_id, 'inserir entregas','','Inserir Entregas no Plano de Qualidade','Inserir lista de entregas no plano de qualidade.');
			$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este qualidade.');
			}
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Plano de Qualidade', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o plano de qualidade.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=qualidade_imprimir&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}



echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';
if ($obj->projeto_qualidade_descricao) echo '<tr><td align="right">'.dica('Descrição', 'Descrição sobre o plano de qualidade').'Descrição:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_qualidade_descricao.'</td></tr>';
if ($obj->projeto_qualidade_usuario) echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'O responsável pelo plano de qualidade.').'Responsável:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.link_usuario($obj->projeto_qualidade_usuario, '','','esquerda').'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_qualidade', $obj->projeto_qualidade_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				
if ($obj->projeto_qualidade_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data em que o plano de qualidade foi criado ou editado').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_qualidade_data).'</td></tr>';

if (!$obj->projeto_qualidade_usuario) echo '<tr><td colspan=20 class="realce">Ainda não há dados cadastrados</td></tr>';







$sql->adTabela('projeto_qualidade_entrega');
$sql->adCampo('*');
$sql->adOnde('projeto_qualidade_entrega_projeto='.$projeto_id);
$sql->adOrdem('projeto_qualidade_entrega_ordem ASC');
$entregas=$sql->Lista();


if ($entregas && count($entregas)) echo '<tr><td>&nbsp</td><td><table class="tbl1" cellspacing=0 cellpadding=0 border=0><tr><th>&nbsp;Entrega'.(count($entregas)>1 ? 's':'').'&nbsp;</th><th>&nbsp;Critério'.(count($entregas)>1 ? 's':'').' de qualidade&nbsp;</th></tr>';
foreach ($entregas as $entrega) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Quem Inseriu</b></td><td>'.nome_funcao('', '', '', '',$entrega['projeto_qualidade_entrega_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($entrega['projeto_qualidade_entrega_data']).'</td></tr>';
	$dentro .= '</table>';
	echo '<tr><td>&nbsp;'.dica($entrega['projeto_qualidade_entrega_entrega'],$dentro).$entrega['projeto_qualidade_entrega_entrega'].'&nbsp;</td><td>&nbsp;'.$entrega['projeto_qualidade_entrega_criterio'].'&nbsp;</td></tr>';
	}
if ($entregas && count($entregas)) echo '</table></td></tr>';









		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este plano de qualidade')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_qualidade';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>