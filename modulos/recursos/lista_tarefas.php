<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $recurso_id,$obj;

$ordenar = getParam($_REQUEST, 'ordenar', 'projeto_id, tarefa_id');
$ordem = getParam($_REQUEST, 'ordem', '0');
$ordenar=($ordem ? $ordenar.' ASC' :  $ordenar.' DESC');

$q = new BDConsulta();
$q->adTabela('recurso_tarefas');
$q->esqUnir('tarefas','tarefas', 'tarefas.tarefa_id=recurso_tarefas.tarefa_id');
$q->esqUnir('projetos','projetos', 'tarefas.tarefa_projeto=projetos.projeto_id');
$q->adCampo('recurso_tarefas.*, tarefa_inicio, tarefa_fim, projeto_id');
$q->adOnde('recurso_tarefas.recurso_id = '.(int)$recurso_id);
$q->adOrdem($ordenar);
$alocacoes = $q->Lista();	
$q->limpar();

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($recurso_id) && $recurso_id ? '&recurso_id='.$recurso_id : '').(isset($tab) && $tab ? '&tab='.$tab : '').'&ordenar=projeto_id&ordem='.($ordem ? '0' : '1').'\');">'.dica('Código de Identificação', 'Recomenda-se que todo recurso tenha um código para facilitar a catalogação.').ucfirst($config['projetos']).dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($recurso_id) && $recurso_id ? '&recurso_id='.$recurso_id : '').(isset($tab) && $tab ? '&tab='.$tab : '').'&ordenar=tarefa_id&ordem='.($ordem ? '0' : '1').'\');">'.dica(ucfirst($config['tarefas']), 'Todo recurso precisa de um nome para facilitar a identificação.').ucfirst($config['tarefas']).dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($recurso_id) && $recurso_id ? '&recurso_id='.$recurso_id : '').(isset($tab) && $tab ? '&tab='.$tab : '').'&ordenar=recurso_quantidade&ordem='.($ordem ? '0' : '1').'\');">'.dica('Total', 'Total deste recurso que foi disponibilizado para '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Valor/Qnt'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($recurso_id) && $recurso_id ? '&recurso_id='.$recurso_id : '').(isset($tab) && $tab ? '&tab='.$tab : '').'&ordenar=tarefa_inicio&ordem='.($ordem ? '0' : '1').'\');">'.dica('De', 'A partir de quando o recurso é utilizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'De'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').(isset($recurso_id) && $recurso_id ? '&recurso_id='.$recurso_id : '').(isset($tab) && $tab ? '&tab='.$tab : '').'&ordenar=tarefa_fim&ordem='.($ordem ? '0' : '1').'\');">'.dica('Até', 'Até quando o recurso é utilizado n'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Até'.dicaF().'</a></th>';
echo '</tr>';
$total=0;

foreach ($alocacoes as $alocacao) {
		echo '<tr>';
		echo '<td>'.link_projeto($alocacao['projeto_id']).'</td>';
		echo '<td>'.link_tarefa($alocacao['tarefa_id']).'</td>';
		echo '<td align="right">'.($obj->recurso_tipo==5 ? $config["simbolo_moeda"].' '.number_format($alocacao['recurso_quantidade'], 2, ',', '.'): number_format($alocacao['recurso_quantidade'], 2, ',', '.')).'</td>';
		echo '<td align="center">'.retorna_data($alocacao['tarefa_inicio'], false).'</td>';
		echo '<td align="center">'.retorna_data($alocacao['tarefa_fim'], false).'</td>';
	echo '</tr>';
	$total+=$alocacao['recurso_quantidade'];
	}
if (count($alocacoes) && $obj->recurso_tipo==5) {
	echo '<tr><td>&nbsp;</td><td align="right"><b>Total:</b></td><td align="right">'.$config["simbolo_moeda"].' '.number_format($total, 2, ',', '.').'</td><td colspan=2>&nbsp;</td></tr>';	
	echo '<tr><td>&nbsp;</td><td align="right"><b>Resto:</b></td><td align="right">'.$config["simbolo_moeda"].' '.number_format($obj->recurso_quantidade-$total, 2, ',', '.').'</td><td colspan=2>&nbsp;</td></tr>';
	}
if (!count($alocacoes)) echo '<tr><td colspan="20"><p>Este recurso não foi alocado em '.$config['tarefas'].'.</p></td></tr>';
echo '</table>';
?>