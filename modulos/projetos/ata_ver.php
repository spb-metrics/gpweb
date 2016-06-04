<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
require_once (BASE_DIR.'/modulos/projetos/ata.class.php');

$ata_id = intval(getParam($_REQUEST, 'ata_id', null));

$sql = new BDConsulta();

$obj = new CAta();
$obj->load($ata_id);



$podeAcessar=permiteAcessarAta($obj->ata_acesso, $obj->ata_id);
$podeEditar=permiteEditarAta($obj->ata_acesso,$ata_id);
if (!$ata_id) {
	$Aplic->setMsg('Não foi passado um ID correto ao tentar ver a Ata.', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=lista'); 
	exit();
	}

if (!($podeAcessar && $podeAcessar)) {
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}







if (!$dialogo) $Aplic->salvarPosicao();


$msg = '';
$botoesTitulo = new CBlocoTitulo('Ata de Reunião', 'anexo_projeto.png', $m, $m.'.'.$a);


$botoesTitulo->adicionaBotao('m=projetos&a=ata_lista&projeto_id='.$obj->ata_projeto, 'lista','','Lista','Ver a lista da ata de reunião.');	
	
if ($podeEditar) {
	$botoesTitulo->adicionaBotao('m=projetos&a=ata_editar&ata_id='.$obj->ata_id.'&projeto_id='.$obj->ata_projeto, 'editar','','Editar Pauta de Reunião','Editar os detalhes da ata de reunião.');
	$botoesTitulo->adicionaBotao('m=projetos&a=ata_pauta&tipo=pauta&ata_id='.$obj->ata_id.'&projeto_id='.$obj->ata_projeto, 'editar pauta','','Editar Pauta','Inserir e editar pauta da reunião.');
	$botoesTitulo->adicionaBotao('m=projetos&a=ata_acao&ata_id='.$obj->ata_id.'&projeto_id='.$obj->ata_projeto, 'editar ações','','Editar Ações','Inserir e editar as ações da pauta da reunião.');
	if ($obj->ata_proxima_data_inicio) $botoesTitulo->adicionaBotao('m=projetos&a=ata_pauta&tipo=proxima&ata_id='.$obj->ata_id.'&projeto_id='.$obj->ata_projeto, 'editar próxima pauta','','Editar Próxima Pauta','Inserir e editar pauta da próxima reunião.');
	$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir esta ata de reunião.');
	}
$botoesTitulo->adicionaBotao('m=projetos&a=ver&projeto_id='.$obj->ata_projeto, $config['projeto'],'',ucfirst($config['projeto']),'Ver os detalhes deste '.$config['projeto'].'.');	
$botoesTitulo->adicionaCelula(dica('Imprimir a Ata de Reunião', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a ata de reunião.').'<a href="javascript: void(0);" onclick ="window.open(\'index.php?m=projetos&a=ata_imprimir&dialogo=1&projeto_id='.$obj->ata_projeto.'&ata_id='.$ata_id.'\', \'imprimir\',\'width=800, height=800, menubar=1, scrollbars=1\')">'.imagem('imprimir_p.png').'</a>'.dicaF());
$botoesTitulo->mostrar();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="ata_projeto" value="'.$obj->ata_projeto.'" />';
echo '<input type="hidden" name="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="aprovar" value="" />';
echo '<input type="hidden" name="fazerSQL" value="" />';
echo '<input type="hidden" name="dialogo" value="" />';
echo '</form>';

echo estiloTopoCaixa();
echo '<table id="tblObjetivos" cellpadding=1 cellspacing=1 width="100%" class="std">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Número', 'Número desta entegra').'Número:'.dicaF().'</td><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->ata_cor.'"><font color="'.melhorCor($obj->ata_cor).'"><b>'.($obj->ata_numero<100 ? '0' : '').($obj->ata_numero<10 ? '0' : '').$obj->ata_numero.'<b></font></td></tr>';
if ($obj->ata_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável', 'Responsável por executar a mudança.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_usuario($obj->ata_responsavel, '','','esquerda').'</td></tr>';		

$sql->adTabela('ata_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=ata_usuario_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('ata_usuario_ata = '.(int)$ata_id);
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
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Equipe', 'Quais '.$config['usuarios'].' estarão na equipe desta ata de reunião.').'Equipe:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';
if ($obj->ata_data_inicio) echo '<tr><td align="right" nowrap="nowrap">'.dica('Data', 'A data desta ata de reunião').'Data:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->ata_data_inicio).($obj->ata_data_fim ? ' - '.substr($obj->ata_data_fim, 11, 5) : '').'</td></tr>';
if ($obj->ata_local) echo '<tr><td align="right" nowrap="nowrap">'.dica('Local', 'O local da reunião.').'Local:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->ata_local.'</td></tr>';
if ($obj->ata_relato) echo '<tr><td align="right" nowrap="nowrap">'.dica('Relato', 'Deliberar sobre a aprovação da mudança.').'Relato:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->ata_relato.'</td></tr>';


$saida='';
$sql->adTabela('ata_pauta');
$sql->adCampo('*');
$sql->adOnde('ata_pauta_ata='.(int)$ata_id);
$sql->adOnde('ata_pauta_tipo!=1');
$sql->adOrdem('ata_pauta_ordem ASC');
$pautas=$sql->Lista();
$sql->limpar();	
if ($pautas && count($pautas)) $saida.= '<table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>Item</th><th>Pauta</th></tr>';
$qnt=0;
foreach ($pautas as $pauta) {
	$qnt++;
	$saida.='<tr>';
	$saida.='<td align="right">'.$qnt.'</td>';
	$saida.='<td>'.$pauta['ata_pauta_texto'].'</td>';
	$saida.='</tr>';
	}
if ($pautas && count($pautas)) $saida.='</table>';
if ($saida) echo '<tr><td align="right">'.dica('Pauta', 'A pauta da reunião.').'Pauta:'.dicaF().'</td><td>'.$saida.'</td></tr>';


$saida='';
$sql->adTabela('ata_acao');
$sql->adCampo('*');
$sql->adOnde('ata_acao_ata='.(int)$ata_id);
$sql->adOrdem('ata_acao_ordem ASC');
$acaos=$sql->Lista();
$sql->limpar();	
$qnt=0;
if ($acaos && count($acaos)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0><tr><th>Item</th><th>&nbsp;Ação&nbsp;</th><th>&nbsp;Data Limite&nbsp;</th><th>&nbsp;Responsável&nbsp;</th></tr>';
foreach ($acaos as $acao) {
	$qnt++;
	$saida.='<tr>';
	$saida.='<td align="right">'.$qnt.'</td>';
	$saida.='<td>&nbsp;'.$acao['ata_acao_texto'].'&nbsp;</td>';
	$saida.='<td>&nbsp;'.retorna_data($acao['ata_acao_fim']).'&nbsp;</td>';
	$saida.='<td>&nbsp;'.link_usuario($acao['ata_acao_responsavel'], '','','esquerda').'&nbsp;</td>';
	$saida.='</tr>';
	}
if ($acaos && count($acaos)) $saida.='</table>';
if ($saida) echo '<tr><td align="right"  nowrap="nowrap">'.dica('Ações', 'As ações a serem desenvolvidas.').'Ações:'.dicaF().'</td><td>'.$saida.'</td></tr>';



if ($obj->ata_proxima_data_inicio) echo '<tr><td align="right" nowrap="nowrap">'.dica('Próxima Reunião', 'A data da próxima reunião').'Próxima reunião:'.dicaF().'</td><td class="realce" width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.retorna_data($obj->ata_proxima_data_inicio).($obj->ata_proxima_data_fim ? ' - '.substr($obj->ata_proxima_data_fim, 11, 5) : '').'</td></tr>';
if ($obj->ata_proxima_local) echo '<tr><td align="right" nowrap="nowrap">'.dica('Local da Próxima', 'O local da próxima reunião.').'Local da próxima:'.dicaF().'</td><td class="realce" width="100%" >'.$obj->ata_proxima_local.'</td></tr>';


$saida='';
$sql->adTabela('ata_pauta');
$sql->adCampo('*');
$sql->adOnde('ata_pauta_ata='.(int)$ata_id);
$sql->adOnde('ata_pauta_tipo=1');
$sql->adOrdem('ata_pauta_ordem ASC');
$pautas=$sql->Lista();
$sql->limpar();	
if ($pautas && count($pautas)) $saida.= '<table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>Item</th><th>Pauta</th></tr>';
$qnt=0;
foreach ($pautas as $pauta) {
	$qnt++;
	$saida.='<tr>';
	$saida.='<td align="right">'.$qnt.'</td>';
	$saida.='<td>'.$pauta['ata_pauta_texto'].'</td>';
	$saida.='</tr>';
	}
if ($pautas && count($pautas)) $saida.='</table>';
if ($saida) echo '<tr><td align="right"  nowrap="nowrap">'.dica('Pauta da Próxima', 'A pauta da próxima reunião.').'Pauta da próxima:'.dicaF().'</td><td>'.$saida.'</td></tr>';


require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('ata', $obj->ata_id, 'ver');
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
	if (confirm('Tem certeza que deseja excluir esta ata de reunião')) {
		var f = document.env;
		f.excluir.value=1;
		f.fazerSQL.value='fazer_sql_ata';
		f.a.value='vazio';
		f.dialogo.value=1;
		f.submit();
		}
	}

function aprovar(){
	if (confirm('Tem certeza que deseja aprovar esta ata de reunião')) {
		url_passar(0, 'm=projetos&a=ata_ver&aprovar=1&ata_id=<?php echo $ata_id?>&projeto_id=<?php echo $obj->ata_projeto?>');
		}

}	
	
	
</script>