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

global $Aplic, $usuarios, $tarefa_id, $tarefa_projeto, $obj, $projTarefasComDatasFinais, $tab;
if (!$tarefa_id) $designado_perc = array($Aplic->usuario_id => array('contato_nome' => $Aplic->usuario_nome.($Aplic->usuario_funcao ? ' - '.$Aplic->usuario_funcao : ''), 'perc_designado' => '100'));
else {
	$q = new BDConsulta;
	$q->adTabela('tarefa_designados');
	$q->esqUnir('usuarios', '', 'usuarios.usuario_id = tarefa_designados.usuario_id');
	$q->esqUnir('contatos', '', 'contatos.contato_id = usuarios.usuario_contato');
	$q->adCampo('tarefa_designados.usuario_id, perc_designado, concatenar_tres(contato_posto, \' \',contato_nomeguerra) AS contato_nome');
	$q->adOnde('tarefa_id = '.(int)$tarefa_id);
	$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$designado_perc = $q->ListaChave('usuario_id');
	$q->limpar();
	}
$initPercAsignment = '';
$designado = array();
foreach ($designado_perc as $usuario_id => $data) {
	$designado[$usuario_id] = $data['contato_nome'].' ['.(int)$data['perc_designado'].'%]';
	$initPercAsignment .= "$usuario_id={$data['perc_designado']};";
	}
?>
<script language="javascript">
<?php
echo "var projTarefasComDatasFinais=new Array();\n";
$chaves = array_keys($projTarefasComDatasFinais);
for ($i = 1, $i_cmp = sizeof($chaves); $i < $i_cmp; $i++) echo 'projTarefasComDatasFinais['.$chaves[$i]."]=new Array(\"".$projTarefasComDatasFinais[$chaves[$i]][1]."\", \"".$projTarefasComDatasFinais[$chaves[$i]][2]."\", \"".$projTarefasComDatasFinais[$chaves[$i]][3]."\");\n";
?>

function comprometimento(){
	if (document.getElementById('lista_usuarios').selectedIndex >-1){
		var usuario_id=document.getElementById('lista_usuarios').options[document.getElementById('lista_usuarios').selectedIndex].value;
		if (window.parent.gpwebApp) parent.gpwebApp.popUp("Comprometimento", 800, 300, 'm=calendario&a=sobrecarga&dialogo=1&cia_id=<?php echo $cia_id ?>&usuario_id='+usuario_id+'&editar=1&data='+document.getElementById('oculto_data_inicio').value, window.setSupervisor, window);
		else window.open('./index.php?m=calendario&a=sobrecarga&dialogo=1&cia_id=<?php echo $cia_id ?>&usuario_id='+usuario_id+'&editar=1', 'Comprometimento', 'height=620,width=820,resizable,scrollbars=yes');
		}
	else alert('Precisa selecionar um <?php echo $config["usuario"]?>.');
	}


function mudar_om_designados(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1); 	
	}
	
	
function mudar_usuarios_designados(){	
	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'lista_usuarios','combo_usuario_tarefa', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="adUsuario();"'); 	
	}	

</script>
<?php


echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_tarefa_aed" />';
echo '<input name="hperc_designado" id="hperc_designado" type="hidden" value="'.$initPercAsignment.'"/>';
echo '<table width="100%" border=0 cellpadding=0 cellspacing=1 class="std">';
echo '<tr><td colspan=20><table><tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td width="50%"><div id="combo_cia_designados">'.selecionar_om((!$obj->tarefa_cia ? $projeto_cia : $obj->tarefa_cia), 'cia_designados', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';

echo '<tr><td width="50%">'.dica(ucfirst($config['usuarios']).' Disponíveis', 'Importante salientar que à <i>priori</i> todos '.$config['genero_usuario'].'s '.$config['usuarios'].' ainda não designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' aparecerão aqui, por isso é importante verificar se '.$config['genero_usuario'].' '.$config['usuario'].' designado já não está envolvido em um número excessivo de  '.$config['tarefas'].'.').ucfirst($config['usuarios']).' Disponíveis:'.dicaF().'</td><td  width="50%">'.dica('Designados para '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Lista d'.$config['genero_usuario'].'s '.$config['usuarios'].' designados para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' com o comprometimento de cada um expresso em porcentagem. Os designados terão um nível de acesso maior '.($config['genero_tarefa']=='a' ?  'a' : 'ao').' '.$config['tarefa'].' e terão seus desempenhos monitorados.').'Designados para '.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).':'.dicaF().'</td></tr>';
echo '<tr><td valign="top"><div id="combo_usuario_tarefa">'.mudar_usuario_em_dept(false, $cia_id, 0, 'lista_usuarios','combo_usuario_tarefa', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="adUsuario();"').'</div></td><td>'.selecionaVetor($designado, 'designado', 'style="width:100%;" size="10" class="texto" multiple="multiple" ondblclick="removerUsuario()"').'</td>';


echo '<tr><td colspan="2" align="center"><table width="100%">';
echo '<tr><td align="left"><table cellpadding=0 cellspacing=0 border=0 width=100%><tr><td>'.botao('&nbsp;&gt;&nbsp;', 'Adicionar', 'Utilize este botão para adicionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' à lista dos designados para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'. </p>Caso deseja inserir multipl'.$config['genero_usuario'].'s '.$config['usuarios'].' de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse n'.$config['genero_usuario'].'s '.$config['usuarios'].' da lista acima.','','adUsuario()','','',0).'</td><td width="10">&nbsp;</td><td align="center" valign="top">'.dica('Nível de Engajamento', 'Utilize esta opção para fazer um controle sobre '.$config['usuarios'].' sobrecarregados. As porcentagens de todos '.$config['genero_tarefa'].'s '.$config['tarefas'].' que os mesmos estão designados é somada e poderemos verificar os ociosos ou aqueles exageradamente sobrecarregados e fazer as redistribuições de missões apropriadas.').'<select name="percentagem_designar" id="percentagem_designar" class="texto">';
	for ($i = 5; $i <= 100; $i += 1) echo '<option '.($i == 100 ? 'selected="true"' : '').' value="'.$i.'">'.$i.'%</option>';
echo '</select>'.dicaF().'</td><td>'.($config['checar_comprometimento'] ? '<td width="10">&nbsp;</td><td>'.botao('comprometimento', 'Comprometimento','Visualizar o grau de comprometimento, por dia, de '.$config['usuario'].' n'.$config['genero_tarefa'].'s '.$config['tarefas'].' em que já esteja designado.<br><br>Ao verificar a disponibilidade de '.$config['usuario'].' que deseja designar, estará evitando a ocorrência de sobrecarga.','','comprometimento()','','',0) : '').'</td><td align="right" width="90%">'.botao('&nbsp;&lt;&nbsp;', 'Descomissionar', 'Utilize este botão para retirar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' da lista dos designados para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'. </p>Caso deseja descomissionar multipl'.$config['genero_usuario'].'s '.$config['usuarios'].' de uma única vez, mantenha o botão <i>CTRL</i> apertado enquanto clica com o botão esquerdo do mouse n'.$config['genero_usuario'].'s '.$config['usuarios'].' da lista acima.','','removerUsuario()','','',0).'</td></tr></table></td></tr>';
echo '</table></td></tr>';


echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr><td><table cellpadding=0 cellspacing=0><tr><td align="right">'.dica('Notificar Todos', 'Os '.$config['usuarios'].' comissionados para '.$config['genero_tarefa'].' '.$config['tarefa'].' receberão uma mensagem informando da desiginação dos mesmos').'Notificar todos:'.dicaF().'</td><td><input type="checkbox" name="tarefa_notificar" id="tarefa_notificar" value="1" /></td></tr><tr><td align="right">'.dica('Notificar Novos', 'Os novos '.$config['usuarios'].' comissionados para '.$config['genero_tarefa'].' '.$config['tarefa'].' receberão uma mensagem informando da desiginação dos mesmos').'Notificar novos:'.dicaF().'</td><td><input type="checkbox" name="tarefa_notificar_novos" id="tarefa_notificar_novos" value="1" /></td></tr></table></td><td align="left">'.dica('Texto da Mensagem', 'Os dados básicos d'.$config['genero_tarefa'].' '.$config['tarefa'].' são automaticamente acrescentado na mensagem, porem caso deseja enviar outras informações junto, escreva na caixa de texto a direita.').'Texto:'.dicaF().'</td><td><textarea name="email_comentario" class="textarea" cols="60" rows="2"></textarea></td></tr></table></td></tr>';

echo '</table>';
echo '<input type="hidden" name="listaDesignados" id="listaDesignados" value="" />';
?>

