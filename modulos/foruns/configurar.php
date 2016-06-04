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

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
$q = new BDConsulta;
if (isset($_REQUEST['forcarAcompanhar']) && isset($_REQUEST['forcarSubmeter'])) { 
	$q->adTabela('forum_acompanhar');
	$q->adInserir('acompanhar_usuario', 0);
	$q->adInserir('acompanhar_forum', 0);
	$q->adInserir('acompanhar_topico', 0);
	if (!$q->exec()) $Aplic->setMsg(db_error(), UI_MSG_ERRO);
	else $Aplic->setMsg('Acompanhamento for�ado', UI_MSG_OK);
	$q->limpar();
	$Aplic->redirecionar('m=foruns&a=configurar');
	} 
elseif (isset($_REQUEST['forcarSubmeter']) && !isset($_REQUEST['forcarAcompanhar'])) { 
	$q->setExcluir('forum_acompanhar');
	$q->adOnde('acompanhar_usuario = 0 OR acompanhar_usuario IS NULL');
	$q->adOnde('acompanhar_forum = 0 OR acompanhar_forum IS NULL');
	$q->adOnde('acompanhar_topico = 0 OR acompanhar_topico IS NULL');
	if (!$q->exec()) $Aplic->setMsg(db_error(), UI_MSG_ERRO);
	else $Aplic->setMsg('Sem acompanhamento for�ado', UI_MSG_OK);
	$q->limpar();
	$Aplic->redirecionar('m=foruns&a=configurar');
	}
$q->adTabela('forum_acompanhar');
$q->adCampo('*');
$q->adOnde('acompanhar_usuario = 0 OR acompanhar_usuario IS NULL');
$q->adOnde('acompanhar_forum = 0 OR acompanhar_forum IS NULL');
$q->adOnde('acompanhar_topico = 0 OR acompanhar_topico IS NULL');
$resTodos = $q->exec();
if (db_num_rows($resTodos) >= 1) $acompanharTodos = true; else $acompanharTodos=false;
$q->limpar();
$botoesTitulo = new CBlocoTitulo('Configurar M�dulo F�runs', 'forum.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&a=vermods', 'voltar','','Voltar','Voltar � tela de administra��o de m�dulos.');
$botoesTitulo->mostrar();
?>
<script language="javascript">
function enviar( frmName ) {
	eval('document.'+frmName+'.submit();');
	}
</script>
<?php
echo '<table class="std" width="100%"><tr><td>';
echo '<form name="frmForceAcompanhar" method="post">';
echo '<input type="hidden" name="m" value="foruns" />';
echo '<input type="hidden" name="a" value="configurar" />';
echo '<input type="hidden" name="forcarSubmeter" value="true" />';
echo '<input type="checkbox" name="forcarAcompanhar" id="forcarAcompanhar" value="dod" '.($acompanharTodos ? 'checked="checked"' : '').' onclick="javascript:enviar(\'frmForceAcompanhar\');" />';
echo '<label for="forcarAcompanhar">For�ar acompanhamento de um f�rum para todos '.$config['genero_usuario'].'s '.$config['usuarios'].' em todos os f�runs (possivelmente um problema de seguran�a, depende do seu uso)</label>';
echo '</form></td></tr>';
echo '<tr><td>'.botao('voltar', 'Voltar', 'Retornar � tela anterior.','','url_passar(0, \'m=sistema&a=vermods\');').'</td></tr></table>';
?>