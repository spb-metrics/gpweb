<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $projeto_id, $df, $editar, $m, $tab, $dialogo, $perms, $podeExcluir;
$sql = new BDConsulta;
$sql->adCampo('projeto_cia');
$sql->adTabela('projetos');
$sql->adOnde('projeto_id = '.(int)$projeto_id);
$cia_id = $sql->Resultado();
$sql->limpar();

$codigo_custo = getParam($_REQUEST, 'codigo_custo', '0');
if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('ProjetosTarefaLogsUsuarioFiltro', getParam($_REQUEST, 'usuario_id', 0));
$usuario_id = $Aplic->getEstado('ProjetosTarefaLogsUsuarioFiltro') ? $Aplic->getEstado('ProjetosTarefaLogsUsuarioFiltro') : 0;
if (isset($_REQUEST['esconder_inativo'])) $Aplic->setEstado('ProjetosTarefaLogsEsconderArquivados', true);
else $Aplic->setEstado('ProjetosTarefaLogsEsconderArquivados', false);
$esconder_inativo = $Aplic->getEstado('ProjetosTarefaLogsEsconderArquivados');
if (isset($_REQUEST['esconder_completado'])) $Aplic->setEstado('ProjetosTarefaLogsEsconderCompletados', true);
else $Aplic->setEstado('ProjetosTarefaLogsEsconderCompletados', false);
$esconder_completado = $Aplic->getEstado('ProjetosTarefaLogsEsconderCompletados');
$nd=array(0 => '');
$nd+= getSisValorND();
$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
$ordenar = getParam($_REQUEST, 'ordenar', 'tarefa_log_data');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
if (!$projeto_id) $projeto_id = getParam($_REQUEST, 'projeto_id', 0);



echo '<form name="frmExcluir2" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_log_tarefa" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="tarefa_log_id" value="0" />';
echo '</form>';

echo '<table cellpadding=0 cellspacing=0 width="100%">';

echo '<tr><td><table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';


echo '<form name="frmFiltro" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="tab" value="'.$tab.'" />';

if (!$dialogo){
	echo '<tr><th colspan=20><table cellpadding=0 cellspacing=0 width="100%">';
	echo '<th width="98%">&nbsp;</th>';
	echo '<th width="1%" nowrap="nowrap"><input type="checkbox" name="esconder_inativo" id="esconder_inativo" '.($esconder_inativo ? 'checked="checked"' : '').' onchange="document.frmFiltro.submit()" /></th><th width="1%" nowrap="nowrap"><label for="esconder_inativo">'.dica('Esconder os Registros d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).' Inativ'.$config['genero_tarefa'].'s', 'Selecione esta opção caso não deseja ver os registros d'.$config['genero_tarefa'].'s '.$config['tarefas'].' inativ'.$config['genero_tarefa'].'s.').' Esconder inativ'.$config['genero_tarefa'].'s'.dicaF().'</label></th>';
	echo '<th width="1%" nowrap="nowrap"><input type="checkbox" name="esconder_completado" id="esconder_completado" '.($esconder_completado ? 'checked="checked"' : '').' onchange="document.frmFiltro.submit()" /></th><th width="1%" nowrap="nowrap"><label for="esconder_completado">'.dica('Esconder os Registros d'.$config['genero_tarefa'].'s '.ucfirst($config['tarefas']).' Completad'.$config['genero_tarefa'].'s', 'Selecione esta opção caso não deseja ver os registros d'.$config['genero_tarefa'].'s '.$config['tarefas'].' já completadas.').' Esconder completad'.$config['genero_tarefa'].'s'.dicaF().'</label></th>';
	echo '<th width="1%" nowrap="nowrap">'.dica('Filtro', 'Selecione de qual '.$config['usuario'].' deseja ver os registros de  '.$config['tarefas'].' cadastrados.').'Filtro'.dicaF().'</th><th><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_usuario" name="nome_usuario" value="'.nome_om($usuario_id,$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /></th><th><a href="javascript: void(0);" onclick="popUsuario();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></th>';
	echo '</tr></table></th></tr>';
	}
echo '</form>';


echo '<tr>';
if (!$dialogo) echo '<th></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_data&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_data' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Data de inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Data'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_tarefa&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_tarefa' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['tarefa']),ucfirst($config['tarefa']).' em que foi inserido o registro.').ucfirst($config['tarefa']).dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_referencia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_referencia' ? imagem('icones/'.$seta[$ordem]) : '').dica('Referência', 'A forma como se chegou aos dados que estão registrandos.').'Ref.'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Título', 'Título do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Título'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_url_relacionada&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_url_relacionada' ? imagem('icones/'.$seta[$ordem]) : '').dica('Endereço Eletrônico da Referência', 'Caso exista um link para página ou arquivo na rede que faça referência ao registro.').'URL'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_criador&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_criador' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'Responsável pela inserção do registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Responsável'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_horas&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_horas' ? imagem('icones/'.$seta[$ordem]) : '').dica('Horas', 'Horas trabalhadas n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Horas'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Comentários', 'Comentários sobre o registro d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Comentários'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_nd&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_nd' ? imagem('icones/'.$seta[$ordem]) : '').dica('ND', 'Número de Despesa já empenhado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'ND'.dicaF().'</a></th>';
echo '<th><a class="aba" href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a='.(!$dialogo ? 'ver' : 'ver_logs').'&tab='.$tab.'&projeto_id='.$projeto_id.'&ordenar=tarefa_log_custo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='tarefa_log_custo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Custo', 'Custo extras gasto n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Custos'.dicaF().'</a></th>';
if (!$dialogo) echo '<th></th>';
echo '</tr>';


$sql->adTabela('tarefa_log');
$sql->esqUnir('tarefa_log', 'log_corrigiu', 'log_corrigiu.tarefa_log_id=tarefa_log.tarefa_log_correcao');
$sql->esqUnir('tarefa_log', 'log_corrigido', 'log_corrigido.tarefa_log_correcao=tarefa_log.tarefa_log_id');
$sql->esqUnir('tarefas','t', 'tarefa_log.tarefa_log_tarefa=t.tarefa_id');
$sql->esqUnir('usuarios', '', 'tarefa_log.tarefa_log_criador = usuario_id');
$sql->esqUnir('contatos', 'ct', 'contato_id = usuario_contato');
$sql->adCampo('tarefa_log.*, usuario_login, contato_id, tarefa_projeto, tarefa_id');
$sql->adCampo('concatenar_tres(formatar_data(log_corrigiu.tarefa_log_data, "%d/%m/%Y"), \' - \', log_corrigiu.tarefa_log_nome) AS corrigido, concatenar_tres(formatar_data(log_corrigido.tarefa_log_data, "%d/%m/%Y"), \' - \', log_corrigido.tarefa_log_nome) AS corrigiu');

$sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
if ($usuario_id > 0) $sql->adOnde('tarefa_log.tarefa_log_criador='.(int)$usuario_id);
if ($esconder_completado) $sql->adOnde('tarefa_percentagem < 100');
if ($codigo_custo != '0') $sql->adOnde('tarefa_log_nd = \''.$codigo_custo.'\'');
$sql->adOrdem($ordenar.($ordem ? ' ASC' : ' DESC'));
$logs = $sql->Lista();
$sql->limpar();
$hrs = 0;
$qnt=0;
$custo=array();
$podeEditar = $Aplic->checarModulo('tarefa_log', 'editar') && $editar;
foreach ($logs as $linha) {


	if ($linha['tarefa_log_correcao']) $estilo='background-color:#a1fb99;color:#000000';
	else if ($linha['corrigiu'])	$estilo='background-color:#e9ea87;color:#000000';
	else if ($linha['tarefa_log_problema']) $estilo='background-color:#cc6666;color:#ffffff';
	else $estilo='';

	$qnt++;
	$tarefa_log_data = intval($linha['tarefa_log_data']) ? new CData($linha['tarefa_log_data']) : null;
	echo '<tr bgcolor="white" valign="top">';
	if (!$dialogo && !$Aplic->profissional) echo '<td width=16>'.($podeEditar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$linha['tarefa_id'].'&tab=1&tarefa_log_id='.$linha['tarefa_log_id'].'\');">'.imagem('icones/editar.gif')."\n\t\t</a>" : '&nbsp;').'</td>';
	if (!$dialogo && $Aplic->profissional) echo '<td width=16>'.($podeEditar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver_log_atualizar_pro&tarefa_id='.$linha['tarefa_id'].'&tab=1&tarefa_log_id='.$linha['tarefa_log_id'].'\');">'.imagem('icones/editar.gif')."\n\t\t</a>" : '&nbsp;').'</td>';
	echo '<td nowrap="nowrap" >'.($tarefa_log_data ? $tarefa_log_data->format('%d/%m/%Y') : '&nbsp;').'</td>';
	echo '<td >'.link_tarefa($linha['tarefa_id']).'</td>';
	$imagem_referencia = '-';
	if ($linha['tarefa_log_referencia'] > 0) {
		if (isset($RefRegistroTarefaImagem[$linha['tarefa_log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']], imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']]).' '.$RefRegistroTarefa[$linha['tarefa_log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
		elseif (isset($RefRegistroTarefa[$linha['tarefa_log_referencia']])) $imagem_referencia = $RefRegistroTarefa[$linha['tarefa_log_referencia']];
		}
	echo '<td align="center" valign="middle" width=16>'.$imagem_referencia.'</td>';

	if ($Aplic->profissional){

		$sql->adTabela('tarefa_log_arquivo');
		$sql->adCampo('count(tarefa_log_arquivo_id)');
		$sql->adOnde('tarefa_log_arquivo_tarefa_log_id='.(int)$linha['tarefa_log_id']);
		$arquivos=$sql->Resultado();
		$sql->limpar();

		$plural=($arquivos > 1 ? 's' : '');

		$anexo=($arquivos ? '<a href="javascript:popArquivos('.$linha['tarefa_log_id'].');" >'.imagem('icones/clip.png','Anexo'.$plural,'Clique neste ícone '.imagem('icones/clip.png').' para visualizar o'.$plural.($plural=='s' ? ' '.$arquivos : '').' anexo'.$plural.'.').'</a>' : '');
		}
	else $anexo='';



	echo '<td valign="middle" style="'.$estilo.'">'.($linha['tarefa_log_correcao'] ? dica('Registro em que Solucionou Problema', $linha['corrigido']) : '').($linha['corrigiu'] ? dica('Registro Responsável pela Solução', $linha['corrigiu']) : '').$linha['tarefa_log_nome'].($linha['corrigiu'] || $linha['corrigido'] ? dicaF() : '').$anexo.'</td>';

	echo !empty($linha['tarefa_log_url_relacionada']) ? '<td align="center" valign="middle">'.dica('Link', 'Clique neste ícone '.imagem('icones/link.png').' para  acessar:<ul><li>'.$linha['tarefa_log_url_relacionada'].'</ul>').'<a href="'.$linha['tarefa_log_url_relacionada'].'">'.imagem('icones/link.png').'</a>'.dicaF().'</td>' : '<td>&nbsp;</td>';
	echo '<td>'.link_contato($linha['contato_id'],'','','esquerda').'</td>';
	echo '<td width="100" align="right">'.sprintf('%.2f', $linha['tarefa_log_horas']).'</td>';
	echo '<td>'.str_replace("\n", '<br />', $linha['tarefa_log_descricao']).'</td>';
	$nd=($linha['tarefa_log_categoria_economica'] && $linha['tarefa_log_grupo_despesa'] && $linha['tarefa_log_modalidade_aplicacao'] ? $linha['tarefa_log_categoria_economica'].'.'.$linha['tarefa_log_grupo_despesa'].'.'.$linha['tarefa_log_modalidade_aplicacao'].'.' : '').$linha['tarefa_log_nd'];
	echo '<td align="center" valign="middle">'.($linha['tarefa_log_custo']!=0 ? $nd : '&nbsp;').'</td>';
	echo '<td width="100" align="right">'.number_format( $linha['tarefa_log_custo'], 2, ',', '.').'</td>';
	if (!$dialogo) echo '<td width=16>'.($podeExcluir ? dica('Excluir Registro', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este registro.').'<a href="javascript:excluir2('.$linha['tarefa_log_id'].');" >'.imagem('icones/remover.png').'</a>' : '&nbsp;').'</td></tr>';

	$hrs += (float)$linha['tarefa_log_horas'];
	if (isset($custo[$nd]))$custo[$nd] += (float)$linha['tarefa_log_custo'];
	else $custo[$nd] = (float)$linha['tarefa_log_custo'];
	}

if (!$qnt) echo '<tr><td bgcolor="white" colspan=20><p>Nenhum registro de '.$config['tarefa'].' encontrado.</p></td></tr></table></td></tr>';
else {
	echo '<tr bgcolor="white" valign="top">';
	echo '<td colspan="'.(!$dialogo ? 7 : 6).'" align="right" valign="middle"><b>Total de Horas:</b></td>';
	$minutos = (int)(($hrs - ((int)$hrs)) * 60);
	$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	echo '<td align="right" valign="middle"><b>'.(int)$hrs.':'.$minutos.'</b></td>';
	echo '<td align="right" colspan="2"><b>Custos</b>';
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

	echo '</table></td></tr>';

	if (!$dialogo){
		echo '<tr><td><table width="100%" class="std2"><tr><td><table><tr>';

		echo '<td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro Normal', 'Todos os registros que não forem marcados como tendo problema serão considerados normais.').'Normal'.dicaF().'&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>';
		echo '<td bgcolor="#cc6666" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Registro de Problema', 'Todos os registros que forem marcados como tendo problema aparecerão com o sumário na cor vermelha.').'Problema&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
		echo '<td bgcolor="#e9ea87" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Problema Solucionado', 'Todos os registros marcados como problema em que outro registro tenha sido marcado como tendo solicionado estes problemas.').'Problema Solucionado&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';
		echo '<td bgcolor="#a1fb99" style="border-style:solid;	border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>'.dica('Solucionou Problema', 'Todos os registros que forem marcados como tendo solucionado problema de outro registro.').'Solucionou Problema&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dicaF().'</td>';



		echo '<td align="right">'.dica('Imprimir os Registros d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a lista de registros d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<a href="javascript: void(0);" onclick ="imprimir_registros('.$projeto_id.');"><img src="'.acharImagem('imprimir.png').'" border=0 width="16" heigth="16" /></a></td>';
		if($Aplic->profissional) echo '<td align="right">'.dica('Baixar arquivos', 'Clique neste ícone '.imagem('zip32.png').' para compactar e transferir todos os arquivos anexados aos registros deste projeto.').'<a href="javascript: void(0);" onclick="download_arquivos_registros('.$projeto_id.','.$usuario_id.','.($esconder_inativo?0:1).','.($esconder_completado?0:1).','.$codigo_custo.');"><img src="'.acharImagem('zip32.png').'" border=0 width="22" heigth="22" /></a></td>';
		echo '</tr></table></td></tr></table></td></tr></table></td></tr>';
		}



	}

echo '</table>';
?>
<script language="JavaScript">

function excluir2(id) {
	if (confirm('Tem certeza que deseja excluir o registro d<?php echo $config["genero_tarefa"]?> <?php echo $config["tarefa"]?>')) {
		document.frmExcluir2.tarefa_log_id.value = id;
		document.frmExcluir2.submit();
		}
	}

function imprimir_registros(projeto_id) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Registros', 1020, 500, 'm=projetos&a=ver_logs&dialogo=1&projeto_id='+projeto_id, null, window);
	else window.open('./index.php?m=projetos&a=ver_logs&dialogo=1&projeto_id='+projeto_id, 'Registros','height=500,width=1020,resizable,scrollbars=yes');
	}

function popUsuario(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, window.setUsuario, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setUsuario&usuario_id='+document.getElementById('usuario_id').value, 'Usuário','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setUsuario(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_usuario').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	frmFiltro.submit();
	}

function popArquivos(tarefa_log_id){
    parent.gpwebApp.popUp("Arquivos", 400, 400, "m=tarefas&a=ver_log_anexos_pro&dialogo=1&tarefa_log_id="+tarefa_log_id, null, window);
    }

function download_arquivos_registros(projeto_id, usuario_id, inativos, completados, codigoCusto){
	url_passar(1, 'm=projetos&a=projeto_log_pro_download&sem_cabecalho=1&projeto_id='+projeto_id+'&usuario_id='+usuario_id+'&completados='+completados+'&inativos='+inativos+'&codigo_cuso='+codigoCusto );
	}
</script>