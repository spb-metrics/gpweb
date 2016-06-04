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

global $Aplic, $m, $a;
if (!$Aplic->checarModulo('sistema', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$usuario_permissoes = array();
if (!$dialogo) $Aplic->salvarPosicao();
if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = ($Aplic->getEstado('usuario_id') != null ? $Aplic->getEstado('usuario_id') : $Aplic->usuario_id);
if (!$usuario_id) $usuario_id=$Aplic->usuario_id;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

$nome_modulo=getParam($_REQUEST, 'modulo', '');



$modulos=array();
$modulos['admin']='Módulos administrativos';
$modulos['nao_admin']='Módulos não-Administrativos';
$modulos['todos']='Todos os módulos';
$modulos['tarefa_log']='Registros d'.$config['genero_tarefa'].'s '.$config['tarefas'];
$modulos['usuarios']=ucfirst($config['usuarios']);
$modulos['eventos']='Eventos';
$sql = new BDConsulta;
$sql->adTabela('modulos');
$sql->adCampo('mod_diretorio, mod_nome');
$modulos=$modulos+$sql->listaVetorChave('mod_diretorio','mod_nome');
$sql->limpar();

$modulos = array('' => 'Mostrar tudo') + $modulos;

$acoes = array(0 => 'Todas as ações', 'acesso' => 'acessar', 'adicionar' => 'adicionar', 'excluir' => 'excluir', 'editar' => 'editar', 'aprovar' => 'Aprovar');
$usuario=0;

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';

$botoesTitulo = new CBlocoTitulo('Permissões', 'cadeado.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').'</a></td></tr>';
$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.'</table>');
$botoesTitulo->mostrar();
echo estiloTopoCaixa();

echo '<table class="std" width="100%" cellspacing="1" cellpadding="2" border=0>';
echo '<tr><td colspan="9">'.dica('Filtro por Módulo', 'Selecione para qual módulo deseja visualizar as permissões de acesso.').'Módulo: '.dicaF().selecionaVetor($modulos, 'modulo', 'class="texto" onchange="javascript:document.env.submit()"', $nome_modulo).'</td></tr>';
echo '<tr><td colspan="9" align="center"><b>Tabela das Permissões</b></td></tr></table>';
echo '<table class="tbl1" width="100%" cellspacing=0 cellpadding="2" border=0><th>'.dica('Módulo', 'Módulo do sistema.').'Módulo'.dicaF().'</th><th>'.dica('Item', 'Item do módulo.').'Item'.dicaF().'</th><th>'.dica('Ação', 'Ação no item do módulo.').'Ação'.dicaF().'</th></tr>';



if ($nome_modulo) $modulos=array($nome_modulo => $modulos[$nome_modulo]);
else array_shift($modulos);


foreach ($modulos as $chave => $nome) {
	$permissoes=listaPermissoes($chave, null, $usuario_id);

	echo '<tr><td>'.$nome.'</td><td>&nbsp;</td>';
	
	$opcoes=array();
	if ($permissoes[0]) $opcoes[]=$acoes['acesso'];
	if ($permissoes[1]) $opcoes[]=$acoes['editar'];
	if ($permissoes[2]) $opcoes[]=$acoes['adicionar'];
	if ($permissoes[3]) $opcoes[]=$acoes['excluir'];
	if ($permissoes[4]) $opcoes[]=$acoes['aprovar'];
	$opcoes=implode('</br>',$opcoes);
	echo '<td>'.($opcoes ? $opcoes : '&nbsp;').'</td><tr>'; 	
	
	}
echo '</table>';


echo '</form>';
echo estiloFundoCaixa();
?>
<script language="javascript">

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	env.submit();
	}

function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}
	
function mudar_usuario(){	
	xajax_mudar_usuario_ajax(document.getElementById('cia_id').value, document.getElementById('usuario_id').value, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="escolheu_usuario();"'); 	
	}	
	
function escolheu_usuario(){
	document.frmUsuario.cia_id.value=document.frmCia.cia_id.value; 
	document.frmUsuario.submit();
	}	
</script>