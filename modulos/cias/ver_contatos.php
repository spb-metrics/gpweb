<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMAo GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cia_id, $obj, $config, $tab;
require_once $Aplic->getClasseModulo('contatos');

$ordenar = getParam($_REQUEST, 'ordenar', 'contato_posto_valor DESC, contato_nomeguerra ASC');
$ordem = getParam($_REQUEST, 'ordem', '0');
if ($ordenar=='email') $ordenar='contato_email'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='telefone') $ordenar='contato_tel'.($ordem ? ' DESC' : ' ASC' ); 
if ($ordenar=='secao') $ordenar=($ordem ? 'dept_nome DESC, contato_posto_valor DESC, contato_nomeguerra DESC' : 'dept_nome ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
if ($ordenar=='nome') $ordenar=($ordem ? 'contato_posto_valor DESC, contato_nomeguerra DESC' : ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
$sql = new BDConsulta;
$sql->adTabela('contatos');
$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
$sql->esqUnir('depts', 'depts', 'dept_id = contato_dept');
$sql->adCampo('contato_id, contato_nomeguerra, contato_tel, contato_dept, contato_email, dept_nome');
$sql->adOnde('contato_cia ='.(int)$obj->cia_id);
$sql->adOnde('(contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.$Aplic->usuario_id.')	OR contato_dono IS NULL)');
$sql->adOrdem($ordenar);

if (!($linhas = $sql->Lista())) {
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><td><p>Nenhum contato encontrado.</p></td></tr>'.$Aplic->getMsg();
	} 
else {
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=nome&ordem='.($ordem ? '0' : '1').'\');">'. dica('Nome', 'Clique para ordenar os contatos pelo nome dos mesmos.') .'Nome'.dicaF().'</a></th>';	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=email&ordem='.($ordem ? '0' : '1').'\');">'. dica('e-mail', 'Clique para ordenar os contatos pelo e-mail dos mesmos.') .'e-mail'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=telefone&ordem='.($ordem ? '0' : '1').'\');">'. dica('Telefone', 'Clique para ordenar os contatos pelo telefone dos mesmos.') .'Telefone'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=cias&a=ver&cia_id='.(int)$cia_id.'&tab='.$tab.'&ordenar=secao&ordem='.($ordem ? '0' : '1').'\');">'. dica(ucfirst($config['departamento']), 'Clique para ordenar os contatos pel'.$config['genero_dept'].'s '.strtolower($config['departamentos']).' dos mesmos.') .$config['dept'].dicaF().'</a></th>';
	echo '</tr>';
	foreach ($linhas as $linha) {
		echo '<tr>';
		echo '<td>'.link_contato($linha['contato_id']).'</td>';
		echo '<td>'.link_email($linha['contato_email'],$linha['contato_id']).'</td>';
		echo '<td>'.($linha['contato_tel'] ? $linha['contato_tel'] : '&nbsp;').'</td>';
		echo '<td>'.$linha['dept_nome'].'</td></tr>';
		}
	}
echo '</table>';

?>