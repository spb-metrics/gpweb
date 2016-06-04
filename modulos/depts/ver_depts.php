<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cia_id,$perms, $podeEditar, $administrador, $tab, $tipos, $procurar_string, $contato_id, $tipo_filtro;

$sql = new BDConsulta;
$sql->adTabela('depts');
$sql->esqUnir('contatos', 'contatos', 'contatos.contato_dept = depts.dept_id');
$sql->adCampo('distinct depts.dept_id, depts.dept_superior, depts.dept_acesso, depts.dept_ordem, depts.dept_nome, COUNT(contatos.contato_dept) AS dept_usuarios');
$sql->adOnde('depts.dept_cia = '.(int)$cia_id);
if ($procurar_string) $sql->adOnde('dept_nome LIKE \'%'.$procurar_string.'%\' OR dept_descricao LIKE \'%'.$procurar_string.'%\'');
if ($contato_id) $sql->adOnde('dept_responsavel = '.(int)$contato_id);
if ($cia_id) $sql->adOnde('dept_cia = '.(int)$cia_id);
if ($tab==0) $sql->adOnde('dept_ativo = 1');
if ($tab==1) $sql->adOnde('dept_ativo = 0');
$sql->adOrdem('depts.dept_superior, depts.dept_ordem, depts.dept_nome');
$sql->adGrupo('depts.dept_id');	

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
$linhas = $sql->Lista();
$sql->limpar();

if (count($linhas)) echo '<th>&nbsp;</th><th width="100%">Nome</th><th>'.ucfirst($config['usuarios']).'</th>';
else echo '<td><p>Nenhum'.($config['genero_dept']=='a' ? 'a' : '').' '.strtolower($config['departamento']).' encontrad'.$config['genero_dept'].'.</p></td>';
echo '</tr>';


foreach ($linhas as $linha) {

	if ($tipo_filtro[$tab] < 0 && $linha['dept_superior'] == 0) {
		mostrarDeptSubordinado_comp($linha);
		acharDeptSubordinado_comp($linhas, $linha['dept_id']);
		}
	elseif ($tipo_filtro[$tab]>=0) {
		echo '<tr><td>'.($podeEditar && permiteEditarDept($linha['dept_acesso'], $linha['dept_id']) ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=depts&a=editar&dept_id='.$linha["dept_id"].'\');" >'.imagem('icones/editar.gif','Editar '.$config['departamento'], 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).'.').'</a>' : '&nbsp;').'</td>';
		echo '<td>'.link_secao($linha['dept_id']).'</td>';
		echo '<td align="center">'.($linha['dept_usuarios'] ? $linha['dept_usuarios']:'&nbsp').'</td></tr>';
		}
	}
	
echo '</table>';


function mostrarDeptSubordinado_comp(&$a, $nivel = 0) {
	global $Aplic, $config, $podeEditar, $administrador, $podeEditar;
	$s = '<td>'.($podeEditar && permiteEditarDept($a['dept_acesso'], $a['dept_id']) ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=depts&a=editar&dept_id='.$a["dept_id"].'\');" >'.imagem('icones/editar.gif','Editar '.$config['departamento'], 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).'.').'</a>' : '&nbsp;').'</td>';
	$s .='<td>';
	for ($y = 0; $y < $nivel; $y++) {
		if ($y + 1 == $nivel) $s .= '<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0>';
		else $s .= '<img src="'.acharImagem('shim.gif').'" width="16" height="12" border=0>';
		}
	$s .= link_secao($a['dept_id']);
	$s .= '</td>';
	$s .= '<td align="center">'.($a['dept_usuarios'] ? $a['dept_usuarios']:'&nbsp').'</td>';
	echo '<tr>'.$s.'</tr>';
	}

function acharDeptSubordinado_comp(&$tarr, $superior, $nivel = 0) {
	$nivel = $nivel + 1;
	$n = count($tarr);
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['dept_superior'] == $superior && $tarr[$x]['dept_superior'] != $tarr[$x]['dept_id']) {
			mostrarDeptSubordinado_comp($tarr[$x], $nivel);
			acharDeptSubordinado_comp($tarr, $tarr[$x]['dept_id'], $nivel);
			}
		}
	}
?>