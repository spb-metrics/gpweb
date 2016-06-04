<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
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
	if (!$sql->exec()) die('N�o foi poss�vel alterar a data de aprova��o da mudan�a.');
	$sql->limpar();
	
	$obj->load($projeto_mudanca_id);

	}




if (!$projeto_mudanca_id) {
	$Aplic->setMsg('N�o foi passado um ID correto ao tentar ver uma solicita��o de mudan�as.', UI_MSG_ERRO);
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
	$botoesTitulo = new CBlocoTitulo('Solicita��o de Mudan�as d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de op��es de visualiza��o').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste bot�o para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->projeto_mudanca_projeto."\");");
	
	$km->Add("ver","ver_mudancas",dica('Lista de Solicita��es de Mudan�as','Ver a lista da solicita��o de mudan�as dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').'Lista de Solicita��es de Mudan�as'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_lista&projeto_id=".$obj->projeto_mudanca_projeto."\");");
	
	
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de op��es').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_mudanca",dica('Inserir Solicita��o de Mudan�as','Inserir os detalhes da solicita��o de mudan�as.').'Solicita��o de Mudan�as'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_editar&projeto_id=".$obj->projeto_mudanca_projeto."\");");
		}	

	$km->Add("root","acao",dica('A��o','Menu de a��es.').'A��o'.dicaF(), "javascript: void(0);'");
	if ($editar && $podeEditar) $km->Add("acao","editar_mudanca",dica('Editar Solicita��o de Mudan�as','Editar os detalhes da solicita��o de mudan�as.').'Editar Solicita��o de Mudan�as'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=mudanca_editar&projeto_mudanca_id=".$obj->projeto_mudanca_id."&projeto_id=".$obj->projeto_mudanca_projeto."\");");
	if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir esta solicita��o de mudan�as do sistema.').'Excluir Solicita��o de Mudan�as'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste �cone '.imagem('imprimir_p.png').' para visualizar as op��es de relat�rios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes da Solicita��o de Mudan�as', 'Visualize os detalhes desta solicita��o de mudan�as.').' Detalhes da Solicita��o de Mudan�as'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=mudanca_imprimir&dialogo=1&projeto_id=".$obj->projeto_mudanca_projeto."&projeto_mudanca_id=".$projeto_mudanca_id."\");");

	echo $km->Render();
	echo '</td></tr></table>';
	}
else {
	$botoesTitulo = new CBlocoTitulo('Solicita��o de Mudan�as', 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$obj->projeto_mudanca_projeto, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	$botoesTitulo->adicionaBotao('m=projetos&a=mudanca_lista&projeto_id='.$obj->projeto_mudanca_projeto, 'lista','','Lista','Ver a lista da solicita��o de mudan�as.');	
	if (($obj->projeto_mudanca_autoridade==$Aplic->usuario_id) && !$obj->projeto_mudanca_data_aprovacao) {
		$botoesTitulo->adicionaBotao('', 'aprovar','','Aprovar','Aprovar esta solicita��o de mudan�as.','aprovar();');		
		}
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=projetos&a=mudanca_editar&projeto_mudanca_id='.$obj->projeto_mudanca_id.'&projeto_id='.$obj->projeto_mudanca_projeto, 'editar','','Editar Solucita��o de Mudan�as','Editar os detalhes da solicita��o de mudan�a.');
		$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta solicita��o de mudan�as.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir a Solicita��o de Mudan�a', 'Clique neste �cone '.imagem('imprimir_p.png').' para imprimir a solicita��o de mudan�a.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=mudanca_imprimir&dialogo=1&projeto_id='.$obj->projeto_mudanca_projeto.'&projeto_mudanca_id='.$projeto_mudanca_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
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

echo '<tr><td align="right" nowrap="nowrap">'.dica('N�mero', 'N�mero desta entegra').'N�mero:'.dicaF().'</td><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_mudanca_cor.'"><font color="'.melhorCor($obj->projeto_mudanca_cor).'"><b>'.($obj->projeto_mudanca_numero<100 ? '0' : '').($obj->projeto_mudanca_numero<10 ? '0' : '').$obj->projeto_mudanca_numero.'<b></font></td></tr>';
if ($obj->projeto_mudanca_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Esta solicita��o de mudan�as � espec�fica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" class="realce" width="100%">'.link_tarefa($obj->projeto_mudanca_tarefa).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Decis�o do Requisitante', 'A requisi��o de mudan�a foi aprovada ou n�o pelo requisitante.').'Decis�o do Requisitante:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_mudanca_requisitante_aprovada ? 'Aprovada' : 'Reprovada').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Decis�o da Administra��o', 'A requisi��o de mudan�a foi aprovada ou n�o pela administra��o.').'Decis�o da administra��o:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_mudanca_administracao_aprovada ? 'Aprovada' : 'Reprovada').'</td></tr>';
if ($obj->projeto_mudanca_cliente) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Demandante', 'O contato que solicitou a mudan�a.').'Demandante:'.dicaF().'</td><td class="realce" width="100%">'.link_contato($obj->projeto_mudanca_cliente, '','','esquerda').'</td></tr>';	

if ($obj->projeto_mudanca_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Respons�vel', 'Respons�vel por executar a mudan�a.').'Respons�vel:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_mudanca_responsavel, '','','esquerda').'</td></tr>';		
if ($obj->projeto_mudanca_autoridade) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Autoridade', 'Autoridade que aprova a solicita��o de mudan�a').'Autoridade:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_mudanca_autoridade, '','','esquerda').'</td></tr>';		

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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Equipe', 'Quais '.$config['usuarios'].' estar�o na equipe desta solicita��o de mudan�a.').'Equipe:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';

if ($obj->projeto_mudanca_data) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Solicita��o', 'A data desta solicita��o de mudan�a').'Data da solicita��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_mudanca_data, false).'</td></tr>';
if ($obj->projeto_mudanca_data_aprovacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Aprova��o', 'A data em que a solicita�a� de mudan�a foi aprovada pela autoridade.').'Data da aprova��o:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_mudanca_data_aprovacao





).'</td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessidade/Justificativa', 'Descri��o de forma clara a necessidade, a motiva��o, custo e prazo estimado da mudan�a no projeto.').'Necessidade/justificativa:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_justificativa.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer T�cnico', 'Avaliar tecnicamente se a mudan�a � pertinente').'Parecer t�cnico:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_parecer_tecnico.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Solu��es Poss�veis', 'Avaliar todas as poss�veis solu��es para solu��o da mudan�a proposta.').'Solu��es poss�veis:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_solucoes.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impacto no Cronograma', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Impacto no cronograma:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_impacto_cronograma.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Impactos no Custo', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Impactos no custo:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_impacto_custo.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Novos Riscos', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Novos riscos:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_novo_risco.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Outros Impactos', 'Descrever o impacto da mudan�a no tempo, custo e riscos.').'Outros impactos:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_outros_impactos.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Solu��o Indicada', 'Dentre as solu��es poss�veis levantada pela equipe de projeto o gerente de projeto deve avaliar o impacto no projeto como um todo e indicar a melhor solu��o a ser adotada.').'Solu��o indicada:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_solucao.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Parecer', 'Deliberar sobre a aprova��o da mudan�a.').'Parecer:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->projeto_mudanca_parecer.'</td></tr>';








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
	if (confirm('Tem certeza que deseja excluir esta solicita��o de mudan�as ')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_recebimento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

function aprovar(){
	if (confirm('Tem certeza que deseja aprovar esta solicita��o de mudan�as ')) {
		url_passar(0, 'm=projetos&a=mudanca_ver&aprovar=1&projeto_mudanca_id=<?php echo $projeto_mudanca_id?>&projeto_id=<?php echo $obj->projeto_mudanca_projeto?>');
		}

}	
	
	
</script>