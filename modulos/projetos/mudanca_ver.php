<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/mudanca.class.php');

$projeto_mudanca_id = intval(getParam($_REQUEST, 'projeto_mudanca_id', null));

$sql = new BDConsulta();

$obj = new CMudanca();
$obj->load($projeto_mudanca_id);

$acessar=permiteAcessarMudanca($obj->projeto_mudanca_acesso, $obj->projeto_mudanca_id);
$editar=permiteEditarMudanca($obj->projeto_mudanca_acesso,$projeto_mudanca_id);

if (getParam($_REQUEST, 'aprovar', null) && ($obj->projeto_mudanca_autoridade==$Aplic->usuario_id) && !$obj->projeto_mudanca_data_aprovacao){

	$sql->adTabela('projeto_mudanca');
	$sql->adAtualizar('projeto_mudanca_data_aprovacao', date('Y-m-d H:i:s'));
	$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
	if (!$sql->exec()) die('Não foi possível alterar a data de aprovação da mudança.');
	$sql->limpar();
	
	$obj->load($projeto_mudanca_id);

	}




if (!$projeto_mudanca_id) {
	$Aplic->setMsg('Não foi passado um ID correto ao tentar ver uma solicitação de mudanças.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}







if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';

if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Solicitação de Mudanças d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste botão para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->projeto_mudanca_projeto."\");");
	
	$km->Add("ver","ver_mudancas",dica('Lista de Solicitações de Mudanças','Ver a lista da solicitação de mudanças dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').'Lista de Solicitações de Mudanças'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_lista&projeto_id=".$obj->projeto_mudanca_projeto."\");");
	
	
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_mudanca",dica('Inserir Solicitação de Mudanças','Inserir os detalhes da solicitação de mudanças.').'Solicitação de Mudanças'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_editar&projeto_id=".$obj->projeto_mudanca_projeto."\");");
		}	

	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar && $podeEditar) $km->Add("acao","editar_mudanca",dica('Editar Solicitação de Mudanças','Editar os detalhes da solicitação de mudanças.').'Editar Solicitação de Mudanças'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_editar&projeto_mudanca_id=".$obj->projeto_mudanca_id."&projeto_id=".$obj->projeto_mudanca_projeto."\");");
	if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir esta solicitação de mudanças do sistema.').'Excluir Solicitação de Mudanças'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes da Solicitação de Mudanças', 'Visualize os detalhes desta solicitação de mudanças.').' Detalhes da Solicitação de Mudanças'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=mudanca_imprimir&dialogo=1&projeto_id=".$obj->projeto_mudanca_projeto."&projeto_mudanca_id=".$projeto_mudanca_id."\");");

	echo $km->Render();
	echo '</td></tr></table>';
	}
else {
	$botoesTitulo = new CBlocoTitulo('Solicitação de Mudanças', 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$obj->projeto_mudanca_projeto, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	$botoesTitulo->adicionaBotao('m=projetos&a=mudanca_lista&projeto_id='.$obj->projeto_mudanca_projeto, 'lista','','Lista','Ver a lista da solicitação de mudanças.');	
	if (($obj->projeto_mudanca_autoridade==$Aplic->usuario_id) && !$obj->projeto_mudanca_data_aprovacao) {
		$botoesTitulo->adicionaBotao('', 'aprovar','','Aprovar','Aprovar esta solicitação de mudanças.','aprovar();');		
		}
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=mudanca_editar&projeto_mudanca_id='.$obj->projeto_mudanca_id.'&projeto_id='.$obj->projeto_mudanca_projeto, 'editar','','Editar Solucitação de Mudanças','Editar os detalhes da solicitação de mudança.');
		$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta solicitação de mudanças.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir a Solicitação de Mudança', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a solicitação de mudança.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=mudanca_imprimir&dialogo=1&projeto_id='.$obj->projeto_mudanca_projeto.'&projeto_mudanca_id='.$projeto_mudanca_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	
	echo estiloTopoCaixa();
	}

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_mudanca_projeto" value="'.$obj->projeto_mudanca_projeto.'" />';
echo '<input type="hidden" name="projeto_mudanca_id" value="'.$projeto_mudanca_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="100%" class="std">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Número', 'Número desta entegra').'Número:'.dicaF().'</td><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_mudanca_cor.'"><font color="'.melhorCor($obj->projeto_mudanca_cor).'"><b>'.($obj->projeto_mudanca_numero<100 ? '0' : '').($obj->projeto_mudanca_numero<10 ? '0' : '').$obj->projeto_mudanca_numero.'<b></font></td></tr>';
if ($obj->projeto_mudanca_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Esta solicitação de mudanças é específica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" class="realce" width="100%">'.link_tarefa($obj->projeto_mudanca_tarefa).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Decisão do Requisitante', 'A requisição de mudança foi aprovada ou não pelo requisitante.').'Decisão do Requisitante:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_mudanca_requisitante_aprovada ? 'Aprovada' : 'Reprovada').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Decisão da Administração', 'A requisição de mudança foi aprovada ou não pela administração.').'Decisão da administração:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_mudanca_administracao_aprovada ? 'Aprovada' : 'Reprovada').'</td></tr>';
if ($obj->projeto_mudanca_cliente) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Demandante', 'O contato que solicitou a mudança.').'Demandante:'.dicaF().'</td><td class="realce" width="100%">'.link_contato($obj->projeto_mudanca_cliente, '','','esquerda').'</td></tr>';	

if ($obj->projeto_mudanca_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', 'Responsável por executar a mudança.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_mudanca_responsavel, '','','esquerda').'</td></tr>';		
if ($obj->projeto_mudanca_autoridade) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Autoridade', 'Autoridade que aprova a solicitação de mudança').'Autoridade:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_mudanca_autoridade, '','','esquerda').'</td></tr>';		

$sql->adTabela('projeto_mudanca_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=projeto_mudanca_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_mudanca_id = '.(int)$projeto_mudanca_id);
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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Equipe', 'Quais '.$config['usuarios'].' estarão na equipe desta solicitação de mudança.').'Equipe:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';

if ($obj->projeto_mudanca_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Solicitação', 'A data desta solicitação de mudança').'Data da solicitação:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_mudanca_data, false).'</td></tr>';
if ($obj->projeto_mudanca_data_aprovacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Aprovação', 'A data em que a solicitaçaõ de mudança foi aprovada pela autoridade.').'Data da aprovação:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_mudanca_data_aprovacao





).'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessidade/Justificativa', 'Descrição de forma clara a necessidade, a motivação, custo e prazo estimado da mudança no projeto.').'Necessidade/justificativa:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_justificativa.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer Técnico', 'Avaliar tecnicamente se a mudança é pertinente').'Parecer técnico:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_parecer_tecnico.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Soluções Possíveis', 'Avaliar todas as possíveis soluções para solução da mudança proposta.').'Soluções possíveis:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_solucoes.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impacto no Cronograma', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Impacto no cronograma:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_impacto_cronograma.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impactos no Custo', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Impactos no custo:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_impacto_custo.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Novos Riscos', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Novos riscos:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_novo_risco.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Outros Impactos', 'Descrever o impacto da mudança no tempo, custo e riscos.').'Outros impactos:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_outros_impactos.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Solução Indicada', 'Dentre as soluções possíveis levantada pela equipe de projeto o gerente de projeto deve avaliar o impacto no projeto como um todo e indicar a melhor solução a ser adotada.').'Solução indicada:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_solucao.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer', 'Deliberar sobre a aprovação da mudança.').'Parecer:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_parecer.'</td></tr>';








require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_mudanca', $obj->projeto_mudanca_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		

		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
		
function excluir() {
	if (confirm('Tem certeza que deseja excluir esta solicitação de mudanças ')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_recebimento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

function aprovar(){
	if (confirm('Tem certeza que deseja aprovar esta solicitação de mudanças ')) {
		url_passar(0, 'm=projetos&a=mudanca_ver&aprovar=1&projeto_mudanca_id=<?php echo $projeto_mudanca_id?>&projeto_id=<?php echo $obj->projeto_mudanca_projeto?>');
		}

}	
	
	
</script>