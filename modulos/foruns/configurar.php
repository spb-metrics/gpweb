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
if (!$dialogo) $Aplic->salvarPosicao();
$q = new BDConsulta;
if (isset($_REQUEST['forcarAcompanhar']) && isset($_REQUEST['forcarSubmeter'])) { 
	$q->adTabela('forum_acompanhar');
	$q->adInserir('acompanhar_usuario', 0);
	$q->adInserir('acompanhar_forum', 0);
	$q->adInserir('acompanhar_topico', 0);
	if (!$q->exec()) $Aplic->setMsg(db_error(), UI_MSG_ERRO);
	else $Aplic->setMsg('Acompanhamento forçado', UI_MSG_OK);
	$q->limpar();
	$Aplic->redirecionar('m=foruns&a=configurar');
	} 
elseif (isset($_REQUEST['forcarSubmeter']) && !isset($_REQUEST['forcarAcompanhar'])) { 
	$q->setExcluir('forum_acompanhar');
	$q->adOnde('acompanhar_usuario = 0 OR acompanhar_usuario IS NULL');
	$q->adOnde('acompanhar_forum = 0 OR acompanhar_forum IS NULL');
	$q->adOnde('acompanhar_topico = 0 OR acompanhar_topico IS NULL');
	if (!$q->exec()) $Aplic->setMsg(db_error(), UI_MSG_ERRO);
	else $Aplic->setMsg('Sem acompanhamento forçado', UI_MSG_OK);
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
$botoesTitulo = new CBlocoTitulo('Configurar Módulo Fóruns', 'forum.png', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema&a=vermods', 'voltar','','Voltar','Voltar à tela de administração de módulos.');
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
echo '<label for="forcarAcompanhar">Forçar acompanhamento de um fórum para todos '.$config['genero_usuario'].'s '.$config['usuarios'].' em todos os fóruns (possivelmente um problema de segurança, depende do seu uso)</label>';
echo '</form></td></tr>';
echo '<tr><td>'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \'m=sistema&a=vermods\');').'</td></tr></table>';
?>