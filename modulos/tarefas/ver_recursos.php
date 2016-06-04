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
echo '<tr><th>'.dica('Tipo', 'Neste campo consta o tipo de recurso alocado').'Tipo'.dicaF().'</th><th>'.dica('Recurso', 'Neste campo consta o nome do recurso alocado').'Recurso'.dicaF().'</th><th>'.dica('Alocado', 'Neste campo consta a percentagem do recurso alocado').'Alocado'.dicaF().'</th><th>'.dica('Alerta', 'Neste campo consta o recursos superalocados.<br><br>Isto acontece quando o n�mero de aloca��es de um recurso em um determinado momento excede sua capacidade m�xima.').'Alerta'.dicaF().'</th></tr>';
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
