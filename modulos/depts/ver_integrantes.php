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

global $dept_id, $dept, $cia_id;
echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr><th>Nome</th><th>E-mail</th><th>Telefone</th></tr>';
$q = new BDConsulta;
$q->adTabela('contatos', 'con');
$q->esqUnir('usuarios', 'usuarios', 'usuario_contato=contato_id');
$q->adCampo('usuario_id, contato_email, contato_tel, contato_dddtel, contato_dddtel2, contato_tel2, contato_dddcel, contato_cel');
$q->adOnde('contato_dept = '.(int)$dept_id);
$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$usuarios = $q->Lista();
$s = '';
$qnt=0;
foreach ($usuarios as $usuario) {
	$qnt++;
	
	if ($usuario['contato_tel']) $telefone=($usuario['contato_dddtel'] ? '('.$usuario['contato_dddtel'].') ' : '').$usuario['contato_tel'];
	elseif ($usuario['contato_tel2']) $telefone=($usuario['contato_dddtel2'] ? '('.$usuario['contato_dddtel2'].') ' : '').$usuario['contato_tel2'];
	elseif ($usuario['contato_cel']) $telefone=($usuario['contato_dddcel'] ? '('.$usuario['contato_dddcel'].') ' : '').$usuario['contato_cel'];
	else  $telefone='&nbsp;';
	$s .= '<tr><td>'.link_usuario($usuario['usuario_id']).'</td><td>'.link_email($usuario['contato_email'],'', $usuario['usuario_id']).'</td><td>'.$telefone.'</td></tr>';
	}
if (!$qnt) echo '<tr><td colspan="3"><p>Nenhum integrante encontrado.</p></td></tr>';	
echo $s;
echo '</table>';
?>