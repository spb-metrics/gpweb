<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $Aplic, $usuario_id, $podeEditar, $podeExcluir, $tab;

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
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
foreach ($usuario_perfis as $chave => $nome) echo '<tr><td width="100%">'.$nome.'</td><td nowrap>'.($podeEditar && !(isset($config['restrito']) && $config['restrito']) ? dica('Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir este perfil de acesso.').'<a href="javascript:excluir('.$chave.');" >'.imagem('icones/remover.png').'</a>'.dicaF() : '').'</td></tr>';
		
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
	echo'<tr><th colspan="2" align="center">'.dica('Adicionar N�vel de Acesso', 'Selecione na caixa de op��o abaixo qual o n�vel de acesso deseja acrescentar.').'Adicionar N�vel de Acesso'.dicaF().'</th></tr>';
		
	echo'<tr><td colspan="2" align="center"><table cellspacing=0 cellpadding=0><tr><td align=right>'.selecionaVetor($perfis_arr, 'usuario_perfil', 'style="width:200px;" size="1" class="texto"', '').'</td><td>'.(!(isset($config['restrito']) && $config['restrito']) ? '<a href="javascript:void(0);" onclick="frmPerms.submit();">'.imagem('icones/adicionar.png', 'Adicionar', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar o perfil de acesso selecionado.').'</a>': '').'</td></tr></table></td></tr>';
	
	
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