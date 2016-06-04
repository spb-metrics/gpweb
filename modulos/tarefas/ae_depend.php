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

global $Aplic, $config, $opcoes_tarefa_superior, $carregarDeTab, $obj, $tipoDuracao, $tarefa_projeto, $tarefa_id, $tab, $wbs_completo;
$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = '0'.$atual;
	else $chave_atual = $atual;
	if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
	else $horas[$chave_atual] = $atual;
	}

$q = new BDConsulta;
$q->adTabela('tarefa_dependencias');
$q->esqUnir('tarefas', 'tarefas','tarefas.tarefa_id=tarefa_dependencias.dependencias_req_tarefa_id');
$q->adCampo('tarefas.tarefa_id, tarefa_nome, tipo_dependencia, latencia, tipo_latencia, tarefa_dinamica');
if($Aplic->profissional) $q->adCampo('dependencia_forte');
$q->adOnde('dependencias_tarefa_id = '.(int)$tarefa_id);
$tarefaDependencias = $q->Lista();
$q->limpar();

$tipo_latencia=array('d'=>'dias', 'h'=>'horas', 's'=>'semanas', 'm'=>'meses');
$tipos_dependencias=array('II'=>'Início - Início', 'IT'=>'Início - Término', 'TI'=>'Término - Início', 'TT'=>'Término - Término');
$tipos_latencias=array('h'=>'horas', 'd'=>'dias', 's'=>'semanas', 'm'=>'meses');
$latencia=array();
for($i=0; $i<=30; $i++)$latencia[$i]=$i; 
for($i=-1; $i>=-30; $i--) $latencia[$i]=$i;
$tarefaDep =array();
$tarefaDep_tipo =array();


foreach ($tarefaDependencias as $linha){
	$tarefaDep[$linha['tarefa_id']]=$linha['tarefa_nome'].' : '.strtoupper($linha['tipo_dependencia']).($linha['latencia']!=0 ? ' '.$linha['latencia'].' '.$tipo_latencia[$linha['tipo_latencia']] : '');
	$tarefaDep_tipo[$linha['tarefa_id']]=$linha['tipo_dependencia'].':'.$linha['tipo_latencia'].$linha['latencia'];
	if($Aplic->profissional && $linha['dependencia_forte']){
		$tarefaDep[$linha['tarefa_id']] .= ' : [Distância]';
		$tarefaDep_tipo[$linha['tarefa_id']] .= ':*';
		} 
	}
$q->limpar();
echo '<input name="fazerSQL" type="hidden" value="fazer_tarefa_aed" />';
echo '<input name="tarefa_id" type="hidden" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="hdependencias" id="hdependencias" />';
echo '<input type="hidden" name="hdependencias_tipo" id="hdependencias_tipo" />';
echo '<input type="hidden" name="tarefa_dinamica" id="tarefa_dinamica" value="0" />';
echo '<table width="100%" border=0 cellpadding=0 cellspacing=1 class="std">';

echo '<tr><td>'.dica('Todas as '.ucfirst($config['tarefa']), 'Lista abaixo de todas '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Tod'.$config['genero_tarefa'].'s '.$config['genero_tarefa'].'s '.$config['tarefas'].':'.dicaF().'</td><td>'.dica('Predecessoras da '.ucfirst($config['tarefa']), 'Lista abaixo de todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' já inclus'.$config['genero_tarefa'].'s de que est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' depende para ser executad'.$config['genero_tarefa'].'.').'Predecessoras d'.$config['genero_tarefa'].' '.$config['tarefa'].':'.dicaF().'</td><td width="100%"></td></tr>';

echo '<tr><td style="width:50%"><select name="todas_tarefas" class="texto" style="width:100%" size="10" class="texto" multiple="multiple" ondblclick="adicionarDependenciaTarefa(document.frmEditar, document.frmEditar)" '.($wbs_completo ? 'disabled="disabled"': '' ).'>'.str_replace('selected', '', $opcoes_tarefa_superior).'</select></td><td style="width:50%">'.selecionaVetor($tarefaDep, 'tarefa_dependencias', 'style="width:100%" size="10" class="texto" multiple="multiple" ondblclick="removerDependenciaTarefa(document.frmEditar, document.frmEditar)" '.($wbs_completo ? 'disabled="disabled"': '' ), null).'</td><td></td></tr>';

echo '<tr style="display:none"><td align="left" colspan=20>'.selecionaVetor($tarefaDep_tipo, 'tarefa_dependencias_tipo', 'style="width:100%" size="10" class="texto" multiple="multiple" '.($wbs_completo ? 'disabled="disabled"': '' )).'</td></tr>';

echo '<tr><td align="left" colspan=20>'.dica('Tipo de predecessoras','<ul>
<li>Início - Início : a data de início '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].' dependerá da data de início d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima;</li>
<li>Início - Fim : a data de término '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].'dependerá da data de início d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima;</li>
<li>Fim - Início : a data de ínicio '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].' dependerá da data de término d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima;</li>
<li>Fim - Fim : a data de término '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].' dependerá da data de término d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima.</li>
</ul>').'Tipo:'.dicaF().selecionaVetor($tipos_dependencias, 'tipos_dependencias', 'class="texto" '.($wbs_completo ? 'disabled="disabled"': '' ), 'TI').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Latência','O intervalo que deverá haver entre '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].' e '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima.').'Latência:'.dicaF().selecionaVetor($latencia, 'latencia', 'class="texto" '.($wbs_completo ? 'disabled="disabled"': '' ), '0').selecionaVetor($tipos_latencias, 'tipos_latencias', 'class="texto"'.($wbs_completo ? 'disabled="disabled"': '' ), 'd');
if($Aplic->profissional){
	echo '<input type="checkbox" name="dependencia_forte" id="dependencia_forte" style="vertical-align:middle;">'.dica('Menor Distância', 'Caso esteja marcada, '.$config['genero_tarefa'].'s '.$config['tarefas'].' buscarão as menores distâncias permitidas entre '.($config['genero_tarefa']=='a' ? 'as mesmas' : 'os mesmos').', baseado nas dependências e latências estipuldas.').'Menor distância'.dicaF();
	}
echo '</td></tr>';
if (!$wbs_completo) echo '<tr><td align="left">'.botao('&nbsp;&gt;&nbsp;', 'Inserir '.$config['tarefa'].' como predecessora', 'Ao clicar neste botão, '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecionadas na caixa da esquerda (Ctrl+nome d'.$config['genero_tarefa'].'s '.$config['tarefas'].') serão adicionadas na lista d'.$config['genero_tarefa'].'s predecessor'.$config['genero_tarefa'].'s d'.$config['genero_tarefa'].' '.$config['tarefa'].'.','','adicionarDependenciaTarefa(document.frmEditar, document.frmEditar)').'</td><td align="right">'.botao('&nbsp;&lt;&nbsp;', 'Excluir '.$config['tarefa'].' do grupo de predecessoras', 'Ao clicar neste botão, '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecionadas na caixa da direita (Ctrl+nome d'.$config['genero_tarefa'].'s '.$config['tarefas'].') serão excluidas da lista d'.$config['genero_tarefa'].'s predecessor'.$config['genero_tarefa'].'s d'.$config['genero_tarefa'].' '.$config['tarefa'].' acima definid'.$config['genero_tarefa'],'','removerDependenciaTarefa(document.frmEditar, document.frmEditar)').'</td><td></td></tr>';
echo '</table>';
?>

