<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/recebimento.class.php');

$projeto_recebimento_id = intval(getParam($_REQUEST, 'projeto_recebimento_id', null));


$obj = new CRecebimento();
$obj->load($projeto_recebimento_id);

$acessar=permiteAcessarRecebimento($obj->projeto_recebimento_acesso, $obj->projeto_recebimento_id);
$editar=permiteEditarRecebimento($obj->projeto_recebimento_acesso,$projeto_recebimento_id);

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}


if (!$projeto_recebimento_id) {
	$Aplic->setMsg('Não foi passado um ID correto ao tentar ver recebimento de produtos/serviços.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=lista'); 
	exit();
	}

if (!($podeAcessar && $acessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}



$sql = new BDConsulta();



if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';

if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Recebimento de Produtos/Serviços', 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_projeto",dica(ucfirst($config['projeto']),'Clique neste botão para visualizar dest'.($config['genero_projeto']=='o' ? 'e' : 'a').' '.$config['projeto'].'.').ucfirst($config['projeto']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=ver&projeto_id=".$obj->projeto_recebimento_projeto."\");");
	$km->Add("ver","ver_lista",dica('Lista','Ver a lista de recebimento de produtos/serviços.').'Lista de Recebimentos de Produtos/Serviços'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=recebimento_lista&projeto_id=".$obj->projeto_recebimento_projeto."\");");
	
	if ($editar && $podeEditar){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_evento",dica('Inserir Produtos/Serviços','Inserir produtos ou serviços.').'Inserir Produtos/Serviços'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=recebimento_produtos&projeto_recebimento_id=".$projeto_recebimento_id."\");");
		}	
	
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($editar && $podeEditar) $km->Add("acao","editar_recebimento",dica('Editar Recebimento de Produtos/Serviços','Editar os detalhes do recebimento de produtos/serviços.').'Editar Recebimento de Produtos/Serviços'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=projetos&a=recebimento_editar&projeto_recebimento_id=".$obj->projeto_recebimento_id."&projeto_id=".$obj->projeto_recebimento_projeto."\");");
	if ($podeExcluir && $editar) $km->Add("acao","acao_excluir",dica('Excluir','Excluir este recebimento de produtos/serviços do sistema.').'Excluir Recebimento de Produtos/Serviços'.dicaF(), "javascript: void(0);' onclick='excluir()");
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes do Recebimento de Produtos/Serviços', 'Visualize os detalhes deste recebimento de produtos/serviços.').' Detalhes do Recebimento de Produtos/Serviços'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=projetos&a=recebimento_imprimir&dialogo=1&projeto_recebimento_id=".$projeto_recebimento_id."\");");

	echo $km->Render();
	echo '</td></tr></table>';
	}
else {

	$botoesTitulo = new CBlocoTitulo('Recebimento de Produtos/Serviços', 'anexo_projeto.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$obj->projeto_recebimento_projeto, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
	$botoesTitulo->adicionaBotao('m=projetos&a=recebimento_lista&projeto_id='.$obj->projeto_recebimento_projeto, 'lista','','Lista','Ver a lista de recebimento de produtos/serviços.');	
	if ($editar) {
		if ($podeEditar) $botoesTitulo->adicionaBotao('m=projetos&a=recebimento_editar&projeto_recebimento_id='.$obj->projeto_recebimento_id.'&projeto_id='.$obj->projeto_recebimento_projeto, 'editar','','Editar Recebimento de Produtos/Serviços','Editar os detalhes do recebimento de produtos/serviços.');
		if ($podeAdicionar) $botoesTitulo->adicionaBotao('m=projetos&a=recebimento_produtos&projeto_recebimento_id='.$projeto_recebimento_id, 'inserir produtos/serviços','','Inserir Produtos/Serviços','Inserir produtos ou serviços.');
		if ($podeExcluir) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir este recebimento de produtos/serviços.');
		}
	$botoesTitulo->adicionaCelula(dica('Imprimir o Recebimento de Produtos/Serviços', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o recebimento de produtos/serviços.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=recebimento_imprimir&dialogo=1&projeto_recebimento_id='.$projeto_recebimento_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="projeto_recebimento_projeto" value="'.$obj->projeto_recebimento_projeto.'" />';
echo '<input type="hidden" name="projeto_recebimento_id" value="'.$projeto_recebimento_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';


echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Número', 'Número desta entegra de produtos/serviços').'Número:'.dicaF().'</td><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->projeto_recebimento_cor.'"><font color="'.melhorCor($obj->projeto_recebimento_cor).'"><b>'.($obj->projeto_recebimento_numero<100 ? '0' : '').($obj->projeto_recebimento_numero<10 ? '0' : '').$obj->projeto_recebimento_numero.'<b></font></td></tr>';

if ($obj->projeto_recebimento_observacao) echo '<tr><td align="right">'.dica('Descrição', 'Descrição sobre o recebimento de produtos/serviços').'Descrição:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$obj->projeto_recebimento_observacao.'</td></tr>';
if ($obj->projeto_recebimento_tarefa) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Esta entrega de produtos/serviços é específica de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" class="realce" width="100%">'.link_tarefa($obj->projeto_recebimento_tarefa).'</td></tr>';

echo '<tr><td align="right">'.dica('Recebimento', 'O recebimento de produtos/serviços pode ser provisório ou em definitivo').'Recebimento:'.dicaF().'</td><td class="realce" width="100%">'.($obj->projeto_recebimento_definitivo ? 'Em definitivo' : 'Provisório').'</td></tr>';

if ($obj->projeto_recebimento_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Entrega', 'Todo produtos/serviços deve ter um responsável pela entrega.').'Quem entrega:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_recebimento_responsavel, '','','esquerda').'</td></tr>';		
if ($obj->projeto_recebimento_cliente) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pelo Recebimento', 'Todo produtos/serviços deve ter um responsável pelo recebimento.').'Quem recebe:'.dicaF().'</td><td class="realce" width="100%">'.link_contato($obj->projeto_recebimento_cliente, '','','esquerda').'</td></tr>';	
if ($obj->projeto_recebimento_autoridade) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Aprovou', 'Quem aprovou esta entrega.').'Quem aprovou:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->projeto_recebimento_autoridade, '','','esquerda').'</td></tr>';		





$sql->adTabela('projeto_recebimento_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=projeto_recebimento_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('projeto_recebimento_id = '.(int)$projeto_recebimento_id);
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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Equipe', 'Quais '.$config['usuarios'].' estarão na equipe desta entrega de produtos/serviço.').'Equipe:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';


				
if ($obj->projeto_recebimento_data_prevista) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data Prevista', 'A data prevista para a entrega dos produtos/serviços').'Data prevista:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_recebimento_data_prevista, false).'</td></tr>';
if ($obj->projeto_recebimento_data_entrega) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Entrega', 'A data da entrega dos produtos/serviços').'Data da entrega:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_recebimento_data_entrega, false).'</td></tr>';
if ($obj->projeto_recebimento_data_aprovacao) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data da Aprovação', 'A data em que esta entrega foi aprovada.').'Data da aprovação:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->projeto_recebimento_data_entrega, false).'</td></tr>';



require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('projeto_recebimento', $obj->projeto_recebimento_projeto, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		



$sql->adTabela('projeto_recebimento_lista');
$sql->adCampo('*');
$sql->adOnde('projeto_recebimento_lista_recebimento_id='.$projeto_recebimento_id);
$sql->adOrdem('projeto_recebimento_lista_id ASC');
$lista=$sql->Lista();


if ($lista && count($lista)) echo '<tr><td>&nbsp</td><td><table class="tbl1" cellspacing=0 cellpadding=2 border=0><tr><th>Item</th><th>Descrição do produto/serviço</th></tr></tr>';
$qnt=0;
foreach ($lista as $produto) {
	$qnt++;
	echo '<tr><td>'.($qnt < 100 ? '0' : '').($qnt < 10 ? '0' : '').$qnt.'</td><td>'.$produto['projeto_recebimento_lista_produto'].'</td></tr>';
	}
if ($lista && count($lista)) echo '</table></td></tr>';





		
echo '</table></td></tr></table>';
echo estiloFundoCaixa();

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
		
function excluir() {
	if (confirm('Tem certeza que deseja excluir este recebimento de produtos/serviços')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_recebimento';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}
</script>