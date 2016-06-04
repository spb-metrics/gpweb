<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $Aplic, $usuario_id, $podeEditar, $podeExcluir, $tab;

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');
$sql = new BDConsulta;
$sql->adTabela('perfil_usuario');
$sql->esqUnir('perfil','perfil', 'perfil_id=perfil_usuario_perfil');
$sql->adCampo('perfil_id, perfil_nome');
$sql->adOnde('perfil_usuario_usuario='.(int)$usuario_id);
$usuario_perfis=$sql->listaVetorChave('perfil_id','perfil_nome');
$sql->Limpar();

$sql->adTabela('perfil');
$sql->adCampo('perfil.*');
$perfis=$sql->lista();
$sql->Limpar();
$perfis_arr = array();
$i=0;
$perfis_arr[0]='';
foreach ($perfis as $perfil) {
	if ($i++ || $Aplic->usuario_super_admin) $perfis_arr[$perfil['perfil_id']] = $perfil['perfil_nome'];
	}

echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
echo '<tr><td width="50%" valign="top">';
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><th width="100%" colspan=2>'.dica('Perfil', 'O perfil de acesso que foi atribuido a'.$config['genero_usuario'].' '.$config['usuario'].'.').'Perfil'.dicaF().'</th></tr>';
foreach ($usuario_perfis as $chave => $nome) echo '<tr><td width="100%">'.$nome.'</td><td nowrap>'.($podeEditar && !(isset($config['restrito']) && $config['restrito']) ? dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este perfil de acesso.').'<a href="javascript:excluir('.$chave.');" >'.imagem('icones/remover.png').'</a>'.dicaF() : '').'</td></tr>';
		
echo '</table></td>';
echo '<td width="50%" valign="top">';
if ($podeEditar) { 
	echo'<form name="frmPerms" method="post">';
	echo'<input type="hidden" name="m" value="admin" />';
	echo'<input type="hidden" name="del" value="0" />';
	echo '<input type="hidden" name="u" value="" />';
	echo'<input type="hidden" name="fazerSQL" value="fazer_usuario_funcao_aed" />';
	echo'<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
	echo'<input type="hidden" name="perfil_id" value="" />';
	echo'<input type="hidden" name="dialogo" value="1" />';
	echo'<table cellspacing=0 cellpadding=0 width="100%">';
	echo'<tr><th colspan="2" align="center">'.dica('Adicionar Nível de Acesso', 'Selecione na caixa de opção abaixo qual o nível de acesso deseja acrescentar.').'Adicionar Nível de Acesso'.dicaF().'</th></tr>';
		
	echo'<tr><td colspan="2" align="center"><table cellspacing=0 cellpadding=0><tr><td align=right>'.selecionaVetor($perfis_arr, 'usuario_perfil', 'style="width:200px;" size="1" class="texto"', '').'</td><td>'.(!(isset($config['restrito']) && $config['restrito']) ? '<a href="javascript:void(0);" onclick="frmPerms.submit();">'.imagem('icones/adicionar.png', 'Adicionar', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar o perfil de acesso selecionado.').'</a>': '').'</td></tr></table></td></tr>';
	
	
	echo'<tr><td align="center"><table><tr><td>';
	if (!count($usuario_perfis)) echo 'Notificar ao nov'.$config['genero_usuario'].' '.$config['usuario'].'<input type="checkbox" name="notificar_novo_usuario" /></td><td>';
	echo '</td></tr></table></td></tr></table></form>';
	}
echo '</td></tr></table>';
?>

<script language="javascript">
function excluir(id) {
	if (confirm( 'Deseja excluir este perfil de acesso?' )) {
		var f = document.frmPerms;
		f.del.value = 1;
		f.perfil_id.value = id;
		f.submit();
		}
	}
</script>