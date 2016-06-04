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

global $Aplic, $tarefa_id, $obj;
require_once $Aplic->getClasseModulo('recursos');
$recorrencia = new CRecurso;
$q = new BDConsulta;
$q->adTabela('recursos', 'a');
$q->esqUnir('recurso_tarefas', 'b', 'b.recurso_id = a.recurso_id');
$q->esqUnir('sisvalores', 'c', 'c.sisvalor_titulo="tipoRecurso" AND c.sisvalor_valor_id= a.recurso_tipo');
$q->adCampo('a.*');
$q->adCampo('b.percentual_alocado');
$q->adCampo('c.sisvalor_valor AS recurso_tipo_nome');
$q->adOnde('b.tarefa_id = '.(int)$tarefa_id);
$recursos = $q->ListaChave('recurso_id');


$recorrencia_tarefas = array();
if (count($recursos)) {
	$q->limpar();
	$q->adTabela('tarefas', 'a');
	$q->esqUnir('recurso_tarefas', 'b', 'b.tarefa_id = a.tarefa_id');
	$q->adCampo('b.recurso_id, sum(b.percentual_alocado) as total_allocated');
	$q->adOnde('b.recurso_id IN ('.implode(',', array_keys($recursos)).')');
	$q->adOnde('tarefa_inicio <= \''.$obj->tarefa_fim.'\'');
	$q->adOnde('tarefa_fim >= \''.$obj->tarefa_inicio.'\'');
	$q->adGrupo('recurso_id');
	$recorrencia_tarefas = $q->ListaChave();
	}
echo '<table class="tbl1" width="100%" cellpadding=0 cellspacing=1>';
echo '<tr><th>'.dica('Tipo', 'Neste campo consta o tipo de recurso alocado').'Tipo'.dicaF().'</th><th>'.dica('Recurso', 'Neste campo consta o nome do recurso alocado').'Recurso'.dicaF().'</th><th>'.dica('Alocado', 'Neste campo consta a percentagem do recurso alocado').'Alocado'.dicaF().'</th><th>'.dica('Alerta', 'Neste campo consta o recursos superalocados.<br><br>Isto acontece quando o número de alocações de um recurso em um determinado momento excede sua capacidade máxima.').'Alerta'.dicaF().'</th></tr>';
foreach ($recursos as $res) {
	$saida = '<tr><td class="realce" style="text-align: justify;">'.$res['recurso_tipo_nome'].'</td>
	<td class="realce" style="text-align: justify;">'.$res['recurso_nome'].'</td>
	<td class="realce" style="text-align: justify;">'.$res['percentual_alocado'].'%</td><td class="warning">';
	if (isset($recorrencia_tarefas[$res['recurso_id']]) && $recorrencia_tarefas[$res['recurso_id']] > $res['recurso_max_alocacao']) $saida .= 'SUPERALOCADO';
	$saida .= '&nbsp;</td></tr>';
	echo $saida;
	}
if (!count($recursos)) echo '<tr><td colspan=4><p>Nenhum recurso encontrado.</p></td></tr>';	
echo '</table>';
?>
