<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/encerramento.class.php');
$projeto_id = intval(getParam($_REQUEST, 'projeto_id', 0));

$objProjeto = new CProjeto();
$objProjeto->load($projeto_id);
$acessar=permiteAcessar($objProjeto->projeto_acesso,$objProjeto->projeto_id);
$editar=permiteEditar($objProjeto->projeto_acesso,$objProjeto->projeto_id);

if (!$projeto_id) {
	$Aplic->setMsg('Não foi passado um ID de '.$config['projeto'].' ao tentar ver o encerramento.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


$obj = new CEncerramento();
$obj->load($projeto_id);
$sql = new BDConsulta();


if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';
if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Termo de Encerramento d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
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
	if ($editar && $podeEditar && !$obj->projeto_encerramento_responsavel){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_encerramento",dica('Inserir Termo de Encerramento','Inserir os detalhes do termo de encerramento.').'Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=encerramento_editar&projeto_id=".$projeto_id."\");");
		}	
	if ($obj->projeto_encerramento_responsavel){
		$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
		if ($editar && $podeEditar) $km->Add("acao","editar_encerramento",dica('Editar Termo de Encerramento','Editar os detalhes do termo de encerramento.').'Editar Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=encerramento_editar&projeto_id=".$projeto_id."\");");
		if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este termo de encerramento do sistema.').'Excluir Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='excluir()");
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
		$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Termo de Encerramento', 'Visualize os detalhes deste termo de encerramento.').' Detalhes do Termo de Encerramento'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=encerramento_imprimir&dialogo=1&projeto_id=".$projeto_id."\");");
		}	
	echo $km->Render();
	echo '</td></tr></table>';
	}
else {
	$botoesTitulo = new CBlocoTitulo('Termo de Encerramento d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$projeto_id, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	if ($podeEditar && $editar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=encerramento_editar&projeto_id='.$projeto_id, ($obj->projeto_encerramento_responsavel ? 'editar' : 'inserir'),'',($obj->projeto_encerramento_responsavel ? 'Editar' : 'Inserir').' Termo de Encerramento',($obj->projeto_encerramento_responsavel ? 'Editar' : 'Inserir').' o termo de encerramento.');
		if (!$obj->projeto_encerramento_responsavel) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este encerramento.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Termo de Encerramento', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o termo de encerramento.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=encerramento_imprimir&dialogo=1&projeto_id='.$projeto_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_encerramento_projeto" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';
if ($obj->projeto_encerramento_encerrado || $obj->projeto_encerramento_encerrado_ressalvas || $obj->projeto_encerramento_nao_encerrado) {
	echo '<tr><td align="right">'.dica('Decisão', 'Decisão quanto à encerrar ou não '.$config['genero_projeto'].' '.$config['projeto'].'.').'Decisão:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">';
 	if($obj->projeto_encerramento_encerrado) echo 'Projeto encerrado';
	elseif ($obj->projeto_encerramento_encerrado_ressalvas) echo 'Projeto encerrado com ressalvas';
	else echo 'Projeto não encerrado';
	echo '</td></tr>';
	}
if ($obj->projeto_encerramento_justificativa) echo '<tr><td align="right">'.dica('Justificativa', 'Justificativa do encerramento ou não encerramento do projeto.').'Justificativa:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_encerramento_justificativa.'</td></tr>';
if ($obj->projeto_encerramento_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', ucfirst($config['usuario']).' responsável pelo termo de encerramento.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_encerramento_responsavel, '','','esquerda').'</td></tr>';		
if ($obj->projeto_encerramento_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data em que o termo de encerramento foi criado ou editado').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_encerramento_data).'</td></tr>';





require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_encerramento', $obj->projeto_encerramento_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				

if (!$obj->projeto_encerramento_responsavel) echo '<tr><td colspan=20 class="realce">Ainda não foi inserido um termo de encerramento</td></tr>';

		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">
	
function excluir() {
	if (confirm('Tem certeza que deseja excluir este encerramento?')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_encerramento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>