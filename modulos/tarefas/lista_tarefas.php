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