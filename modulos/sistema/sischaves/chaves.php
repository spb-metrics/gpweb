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

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
$q = new BDConsulta;
$q->adTabela('sischaves');
$q->adCampo('*');
$q->adOrdem('sischave_nome');
$chaves = $q->Lista();
$q->limpar();
$sischave_id = isset($_REQUEST['sischave_id']) ? getParam($_REQUEST, 'sischave_id', 0) : 0;
$botoesTitulo = new CBlocoTitulo('Chaves de Campos do Sistema', 'opcoes.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&a=index', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table border=0 cellpadding="2" cellspacing="1" width="100%" class="std"><tr>	<td>&nbsp;</td>';
echo '<td align="left">'.dica('Nome da Chave', 'As diversas chaves devem ter nomes dististos daqueles já utilizados pelo Sistema.').'<b>Nome</b>'.dicaF().'</td>';
echo '<td align="left" colspan="2">'.dica('Valores da Chave', 'As diversas chaves devem ter os valores inseridos para que sejam efetivas.').'<b>Valores</b>'.dicaF().'</td><td>&nbsp;</d></tr>';
$s = '';
foreach ($chaves as $linha) echo mostrarLinha($linha['sischave_id'], $linha['sischave_nome'], $linha['sischave_legenda']);
if ($sischave_id == 0) echo mostrarLinha();
echo '</table>';
echo estiloFundoCaixa();


function mostrarLinha($id = 0, $nome = '', $legenda = '') {
	global $podeEditar, $sischave_id, $CR, $Aplic;
	$s = '';
	if ($sischave_id == $id && $podeEditar) {
		$s .= '<form name="frmChavesSis" method="post">';
		$s .= '<input type="hidden" name="m" value="sistema" />';
		$s .= '<input type="hidden" name="a" value="fazer_sischave_aed" />';
		$s .= '<input type="hidden" name="u" value="sischaves" />';
		$s .= '<input type="hidden" name="del" value="0" />';
		$s .= '<input type="hidden" name="sischave_id" value="'.$id.'" />';
		$s .= '<tr><td>&nbsp;</td>';
		$s .= '<td><input type="text" name="sischave_nome" value="'.$nome.'" class="texto" /></td>';
		$s .= '<td><textarea name="sischave_legenda" class="pequeno" rows="2" cols="40">'.$legenda.'</textarea></td>';
		$s .= '<td><table><tr><td>'.botao(($id ? 'alterar' : 'adicionar'), ($id ? 'Confirmar Alteração' : 'Confirmar a Adição'), 'Confirmar '.($id ? ' a alteração.' : 'a adição.'),'','if (frmChavesSis.sischave_nome.value.length<1 || frmChavesSis.sischave_legenda.value.length<1) alert(\'Precisa preencher os valores!\'); else frmChavesSis.submit()').'</td><td>'.($id ? botao('cancelar', 'Cancelar', 'Cancelar a edição.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td>';
		$s .= '<td>&nbsp;</td>';
		} 
	else {
		$s .= '<tr><td width="12">';
		if ($podeEditar) {
			$s .= dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este campo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves&a=chaves&sischave_id='.$id.'\');"><img src="'.acharImagem('icones/editar.gif').'" alt="editar" border=0></a>'.dicaF();
			$s .= '</td>'.$CR;
			}
		$s .= '<td>'.$nome.'</td>'.$CR.'<td colspan="2">'.$legenda.'</td>'.$CR.'<td width="16">'.($podeEditar ? dica('Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este campo.').'<a href="javascript:excluir('.$id.')"><img align="absmiddle" src="'.acharImagem('icones/remover.png').'" width="16" height="16" alt="excluir" border=0></a>'.dicaF() : '').'</td>'.$CR;
		}
	$s .= '</tr>'.$CR;
	return $s;
	}
?>
<script language="javascript">
function excluir(id) {
	if (confirm( 'Tem certeza que deseja excluir?' )) {
		f = document.frmChavesSis;
		f.del.value = 1;
		f.sischave_id.value = id;
		f.submit();
	}
}
</script>

