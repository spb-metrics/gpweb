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

global $a, $mostrarProjRespPertenceDept, $Aplic, $buffer, $cia_id, $secao, $dept_ids, $ver_min, $m, $prioridade, $projetos, $tab, $usuario_id;

$df = '%d/%m/%Y';
$projStatus = getSisValor('StatusProjeto');
if (isset($_REQUEST['proFiltro'])) $Aplic->setEstado('DeptProjetoIdxFiltro', getParam($_REQUEST, 'proFiltro', null));
$proFiltro = $Aplic->getEstado('DeptProjetoIdxFiltro') !== null ? $Aplic->getEstado('DeptProjetoIdxFiltro') : '-3';
$projFiltro = unirVetores(array('-1' => 'Tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']), $projStatus);
$projFiltro = unirVetores(array('-2' => 'Todos exceto os em execu��o'), $projFiltro);
$projFiltro = unirVetores(array('-3' => 'Todos exceto os arquivados'), $projFiltro);
natsort($projFiltro);
require_once ($Aplic->getClasseModulo('cias'));
if (isset($_REQUEST['mostrar_form'])) $Aplic->setEstado('adProjRespPertenceDept', getParam($_REQUEST, 'mostrarProjRespPertenceDept', 0));
$mostrarProjRespPertenceDept = $Aplic->getEstado('adProjRespPertenceDept') ? $Aplic->getEstado('adProjRespPertenceDept') : 0;
$extraGet = '&usuario_id='.$usuario_id;
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td align="right" nowrap="nowrap">
<form method="post" name="checkPwOiD"><input type="hidden" name="m" value="depts" /><input type="hidden" name="a" value="ver" /><input type="hidden" name="dept_id" value="'.$dept_id.'" /><input type="hidden" name="tab" value="'.$tab.'" /><input type="checkbox" name="mostrarProjRespPertenceDept" id="mostrarProjRespPertenceDept" onclick="document.checkPwOiD.submit()" '.($mostrarProjRespPertenceDept ? 'checked="checked"' : '').' /><label for="mostrarProjRespPertenceDept">'.dica('Respons�vel d'.$config['genero_dept'].' '.strtolower($config['departamento']), 'Marque esta op��o caso s� deseje ver o Gr�fico Gantt d'.$config['genero_projeto'].'s '.$config['projetos'].' em que a responsabilidade seja de um membro d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.').'Respons�vel d'.$config['genero_dept'].' '.strtolower($config['departamento']).'&nbsp;&nbsp;&nbsp;</label><input type="hidden" name="mostrar_form" value="1" /></form></td></tr></table>';
$ver_min = true;
require (BASE_DIR.'/modulos/projetos/ver_gantt.php');
?>