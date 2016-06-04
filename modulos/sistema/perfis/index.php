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

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;
$sql->adTabela('perfil');
$sql->adCampo('perfil.*');
$perfis=$sql->lista();
$sql->Limpar();

$perfil_id = getParam($_REQUEST, 'perfil_id', 0);
$q = new BDConsulta;

$botoesTitulo = new CBlocoTitulo('Perfis de Acesso', 'cadeado.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
$blocos = array();
$blocos['m=sistema'] = 'Sistema';

echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std2"><tr><th>&nbsp;</th>';
echo '<th>'.dica('Nome do Perfil', 'Nome do perfil de acesso.').'Nome do Perfil'.dicaF().'</th>';
echo '<th>'.dica('Descrição', 'Descrição do perfil de acesso.').'Descrição'.dicaF().'</th><th>&nbsp;</th></tr>';
$s = '';

foreach ($perfis as $linha) echo mostrarLinha($linha);
if ($perfil_id == 0) echo mostrarLinha();
echo '</table>';
echo estiloFundoCaixa();

function mostrarLinha($perfil = null) {
	global $podeEditar, $podeExcluir, $perfil_id, $Aplic;
	$id = $perfil['perfil_id'];
	$nome = $perfil['perfil_nome'];
	$descricao = $perfil['perfil_descricao'];
	$s = '';
	if (($perfil_id == $id || $id == 0) && $podeEditar) {
		$s .= '<form name="perfilFrm" method="post" action="?m=sistema&u=perfis">';
		$s .= '<input type="hidden" name="m" value="sistema" />';
		$s .= '<input type="hidden" name="u" value="perfis" />';
		$s .= '<input type="hidden" name="fazerSQL" value="fazer_perfil_aed" />';
		$s .= '<input type="hidden" name="del" value="0" />';
		$s .= '<input type="hidden" name="perfil_id" value="'.$id.'" />';
		$s .= '<tr><td>&nbsp;</td>';
		$s .= '<td valign="middle"><input type="text" style="width:150px;" name="perfil_nome" value="'.$nome.'" class="texto" /></td>';
		$s .= '<td valign="middle"><input type="text" style="width:550px;" name="perfil_descricao" class="texto" value="'.$descricao.'"></td>';
		$s .= '<td>'.dica(($id ? 'Confirmar' : 'Adicionar'),($id ? 'Confirmar a alteração do' : 'Adicionar o').' perfil de acesso.<br>É necessário preencher os dois campos da esquerda com o nome e a descrição do perfil respectivamente.').'<a class="botao" href="javascript: void(0)" onclick="javascript:if (perfilFrm.perfil_nome.value!=\'\' && perfilFrm.perfil_descricao.value!=\'\') perfilFrm.submit(); else alert(\'Preencha tanto o nome quanto a descrição do perfil\')" ><span>'.($id ? 'confirmar' : 'adicionar').'</span></a>';
		if ($id) $s .=dica('Cancelar', 'Cancelar a '.($id ? 'alteração' : 'adição').'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=sistema&u=perfis\');"><span>cancelar</span></a>';
		$s .= '</td>';
		} 
	else {
		$s .= '<tr><td width="50" valign="top">';
		if ($podeEditar) $s .= dica('Editar Nome', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o nome e descrição deste perfil de acesso.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=perfis&perfil_id='.$id.'\');">'.imagem('icones/editar.gif').'</a>'.dicaF().dica('Editar Perfil de Acesso', 'Clique neste ícone '.imagem('icones/cadeado.gif').' para editar os níveis de acesso deste perfil.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=perfis&a=ver_perfil&perfil_id='.$id.'\');" >'.imagem('icones/cadeado.gif').'</a>'.dicaF();
		if ($podeExcluir) $s .= dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este perfil de acesso.').'<a href=\'javascript:excluir('.$id.')\'>'.imagem('icones/remover.png').'</a>'.dicaF();
		$s .= '</td><td valign="top">'.$nome.'</td><td valign="top">'.$descricao.'</td><td valign="top" width="16">&nbsp;</td>';
		}
	$s .= '</tr>';
	return $s;
	}
?>
<script language="javascript">
function excluir(id) {
	if (confirm('Tem certeza que deseja excluir?')) {
		f = document.perfilFrm;
		f.del.value = 1;
		f.perfil_id.value = id;
		f.submit();
	}
}
</script>
