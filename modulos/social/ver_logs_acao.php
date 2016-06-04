<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\ver_logs.php		

Exibe os registros relativos a ação ou prática de gestão																																						
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
include_once BASE_DIR.'/modulos/social/acao.class.php';
global $Aplic, $social_id, $podeExcluir, $df, $m, $tab;

$sql = new BDConsulta;

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$codigo_custo = getParam($_REQUEST, 'codigo_custo', '0');

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', 0));
$usuario_id = $Aplic->getEstado('usuario_id') ? $Aplic->getEstado('usuario_id') : 0;


$nd=array(0 => '');
$nd+= getSisValorND();
$RefRegistroAcao = getSisValor('RefRegistroTarefa');
$RefRegistroAcaoImagem = getSisValor('RefRegistroTarefaImagem');
$ordenar = getParam($_REQUEST, 'ordenar', 'social_acao_log_data');
$ordem = getParam($_REQUEST, 'ordem', '0');

$ordenacao=$ordenar.($ordem ? ' DESC' : ' ASC');

echo '<form name="frmExcluir2" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_acao" />';
echo '<input type="hidden" name="social_acao_log_id" value="" />';
echo '<input type="hidden" name="social_id" value="'.$social_id.'" />';
echo '<input type="hidden" name="del" value="1" />';
echo '</form>';

echo '<form name="frmFiltro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="social_id" value="'.$social_id.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';

echo '<table border=0 cellpadding=0 cellspacing=0 width="100%" class="std2"><tr><td><table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
echo '<th width="98%">&nbsp;</th>';



echo '<th width="1%" nowrap="nowrap">'.dica('Filtro', 'Selecione de qual '.$config['usuario'].' deseja ver os registros de cadastrados.').'Filtro'.dicaF().'</th><th><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /></th><th><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></th>';


echo '</tr>';
echo '</table></form>';
echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.dica('Referência', 'A forma como se chegou aos dados que estão registrandos.').($ordenar=='social_acao_log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').'Ref.'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Título', 'Título do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Título'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_criador&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pela inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_criador' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_horas&ordem='.($ordem ? '0' : '1').'\');">'.dica('Horas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_horas' ? imagem('icones/'.$seta[$ordem]) : '').'Horas'.dicaF().'</a></th>';
echo '<th width="100%"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Comentários', 'Comentários sobre o registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Comentários'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_nd&ordem='.($ordem ? '0' : '1').'\');">'.dica('ND', 'Número de Despesa já empenhado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_nd' ? imagem('icones/'.$seta[$ordem]) : '').'ND'.dicaF().'</a></th>';
echo '<th width="100"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$m.'&tab='.$tab.'&social_id='.$social_id.'&ordenar=social_acao_log_custo&ordem='.($ordem ? '0' : '1').'\');">'.dica('Custo', 'Custo extras gasto n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').($ordenar=='social_acao_log_custo' ? imagem('icones/'.$seta[$ordem]) : '').'Custos'.dicaF().'</a></th>';
echo '<th>&nbsp;</th><th>&nbsp;</th></tr>';


$sql->adTabela('social_acao_log');
$sql->adCampo('social_acao_log.*');
$sql->adUnir('usuarios', 'usuarios', 'usuario_id = social_acao_log_criador');
$sql->adOnde('social_acao_log_social = '.$social_id);
if ($usuario_id) $sql->adOnde('social_acao_log_criador = '.$usuario_id);

$sql->adOrdem($ordenacao);
$logs = $sql->Lista();

$hrs = 0;
$qnt=0;
$custo=array();

foreach ($logs as $linha) {
	$qnt++;
	$social_acao_log_horas = intval($linha['social_acao_log_horas']) ? new CData($linha['social_acao_log_horas']) : null;
	$estilo = $linha['social_acao_log_problema'] ? 'background-color:#cc6666;color:#ffffff' : '';
	echo '<tr bgcolor="white" valign="top"><td>';
	echo($podeEditar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=social&a=social_ver&tab=1&social_id='.$social_id.'&social_acao_log_id='.$linha['social_acao_log_id'].'\');">'.imagem('icones/editar.gif').'</a>' : '&nbsp;');
	echo '</td><td nowrap="nowrap" >'.retorna_data($linha['social_acao_log_data'], false).'</td>';
	$imagem_referencia = '-';
	if ($linha['social_acao_log_referencia'] > 0) {
		if (isset($RefRegistroAcaoImagem[$linha['social_acao_log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroAcaoImagem[$linha['social_acao_log_referencia']], imagem('icones/'.$RefRegistroAcaoImagem[$linha['social_acao_log_referencia']]).' '.$RefRegistroAcao[$linha['social_acao_log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroAcao[$linha['social_acao_log_referencia']])) $imagem_referencia = $RefRegistroAcao[$linha['social_acao_log_referencia']];
		}
	echo '<td align="center" valign="middle">'.$imagem_referencia.'</td>';
	echo '<td nowrap="nowrap" style="'.$estilo.'">'.($linha['social_acao_log_nome'] ? $linha['social_acao_log_nome'] : '&nbsp;').'</td>';
	echo '<td nowrap="nowrap">'.link_usuario($linha['social_acao_log_criador'],'','','esquerda').'</td>';
	echo '<td width="100" align="right">'.($linha['social_acao_log_horas'] ? sprintf('%.2f', $linha['social_acao_log_horas']) : '&nbsp;').'</td>';
	echo '<td>'.($linha['social_acao_log_descricao'] ? str_replace("\n", '<br />', $linha['social_acao_log_descricao']) : '&nbsp;').'</td>';
	$nd=($linha['social_acao_log_categoria_economica'] && $linha['social_acao_log_grupo_despesa'] && $linha['social_acao_log_modalidade_aplicacao'] ? $linha['social_acao_log_categoria_economica'].'.'.$linha['social_acao_log_grupo_despesa'].'.'.$linha['social_acao_log_modalidade_aplicacao'].'.' : '').$linha['social_acao_log_nd'];
	echo '<td align="center" valign="middle">'.($linha['social_acao_log_custo']!=0 ? $nd : '&nbsp;').'</td>';
	echo '<td width="100" align="right">'.number_format( $linha['social_acao_log_custo'], 2, ',', '.').'</td>';
	echo '<td>&nbsp;</td>';
	echo '<td>'.($podeEditar ?  dica('Excluir Registro', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este registro.').'<a href="javascript:excluir2('.$linha['social_acao_log_id'].');" >'.imagem('icones/remover.png').'</a>' : '&nbsp;').'</td></tr>';
	$hrs += (float)$linha['social_acao_log_horas'];
	if (isset($custo[$nd]))	$custo[$nd] += (float)$linha['social_acao_log_custo'];
	else $custo[$nd] = (float)$linha['social_acao_log_custo'];
	}
if (!$qnt) echo '<tr><td bgcolor="white"><p>Nenhum registro de ocorrência encontrado.</p></td></tr></table>';	
else {
	echo '<tr bgcolor="white" valign="top">';
	echo '<td colspan="6" align="right" valign="middle"><b>Total de Horas:</b></td>';
	$minutos = (int)(($hrs - ((int)$hrs)) * 60);
	$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	echo '<td align="left" valign="middle"><b>'.(int)$hrs.':'.$minutos.'</b></td>';
	echo '<td align="right" colspan="2" nowrap="nowrap"><b>Custos</b>';
	foreach ($custo as $nd => $somatorio) {
		if ($somatorio > 0) echo '<br>'.$nd;
		}
	echo '<br><b>Total Geral</b>';
	echo'</td>';
	echo '<td align="right">';
	$somatorio_total=0;
	foreach ($custo as $nd => $somatorio) {
		if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
		$somatorio_total+=$somatorio;
		}
	 echo '<br><b>'.number_format($somatorio_total, 2, ',', '.').'</b></td>';	
	echo '<td></tr>';
	echo '</table></td></tr><tr><td><table><tr><td>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Legenda</td><td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro Normal', 'Todos os registros que não forem marcados como tendo problema serão considerados normais.').'Registro Normal'.dicaF().'&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro de Problema', 'Todos os registros que forem marcados como tendo problema aparecerão com o sumário na cor vermelha.').'Registro de Problema'.dicaF().'</td></tr></table>';
	}
echo '</table>';

?>



<script language="javascript">
	
function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	frmFiltro.submit();
	}	

	
function excluir2(id) {
	if (confirm( 'Tem certeza que deseja excluir o registro da ocorrência?' )) {
		document.frmExcluir2.social_acao_log_id.value = id;
		document.frmExcluir2.submit();
		}
	}
</script>
