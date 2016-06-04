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

if (!$Aplic->checarModulo('tarefas', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$proj = getParam($_REQUEST, 'projeto', 0);
$usuarioFilter = getParam($_REQUEST, 'usuarioFilter', false);
$q = new BDConsulta();
$q->adCampo('t.tarefa_id, t.tarefa_nome');
$q->adTabela('tarefas', 't');
if ($usuarioFilter) {
	$q->adUnir('tarefa_designados', 'ut', 'ut.tarefa_id = t.tarefa_id');
	$q->adOnde('ut.usuario_id = '.(int)$Aplic->usuario_id);
	}
if ($proj != 0) $q->adOnde('tarefa_projeto = '.(int)$proj);
$tarefas = $q->Lista();
$q->limpar();
?>
<script language="JavaScript">
function loadTarefas() {
	var tarefas = new Array();
	var sel = parent.document.forms['form'].new_tarefa;
	while (sel.options.length) sel.options[0] = null;
	sel.options[0] = new Option('[tarefa superior]', 0);
  <?php
	$i = 0;
	foreach ($tarefas as $tarefa) {
	++$i;
  echo 'sel.options['.$i.'] = new Option("'.$tarefa['tarefa_nome'].'", '.$tarefa['tarefa_id'].');';
	}
	?>
	}
loadTarefas();
</script>