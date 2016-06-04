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

global $Aplic, $cia_id,$perms, $podeEditar, $podeEditarDept;

$q = new BDConsulta;
$q->adTabela('depts');
$q->esqUnir('contatos', 'c', 'c.contato_dept = dept_id');
$q->adCampo('dept_id, COUNT(c.contato_dept) AS dept_usuarios, dept_superior, dept_acesso');
$q->adOnde('dept_cia = '.(int)$cia_id);
$q->adGrupo('dept_id');
$q->adOrdem('dept_superior, dept_ordem, dept_nome');
$s = '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
$s .= '<tr>';
$linhas = $q->Lista();
$q->limpar();


if (count($linhas)) $s .= '<th>&nbsp;</th><th width="100%">Nome</th><th>'.ucfirst($config['usuarios']).'</th>';
else $s .= '<td><p>Nenhum'.($config['genero_dept']=='a' ? 'a' : '').' '.strtolower($config['departamento']).' encontrad'.$config['genero_dept'].'.</p></td>';
$s .= '</tr>';
echo $s;
foreach ($linhas as $linha) {
	if ($linha['dept_superior'] == 0) {
		mostrarDeptSubordinado_comp($linha);
		acharDeptSubordinado_comp($linhas, $linha['dept_id']);
		}
	}
echo '</table>';


function mostrarDeptSubordinado_comp(&$a, $nivel = 0) {
	global $Aplic, $config, $podeEditar, $podeEditarDept;
	$s = '<td>'.($podeEditarDept && permiteEditarDept($a['dept_acesso'], $a['dept_id']) ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=depts&a=editar&dept_id='.$a["dept_id"].'\');" >'.imagem('icones/editar.gif','Editar '.$config['departamento'], 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).'.').'</a>' : '&nbsp;').'</td>';
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