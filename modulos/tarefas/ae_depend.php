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
$tipos_dependencias=array('II'=>'In�cio - In�cio', 'IT'=>'In�cio - T�rmino', 'TI'=>'T�rmino - In�cio', 'TT'=>'T�rmino - T�rmino');
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
		$tarefaDep[$linha['tarefa_id']] .= ' : [Dist�ncia]';
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

echo '<tr><td>'.dica('Todas as '.ucfirst($config['tarefa']), 'Lista abaixo de todas '.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Tod'.$config['genero_tarefa'].'s '.$config['genero_tarefa'].'s '.$config['tarefas'].':'.dicaF().'</td><td>'.dica('Predecessoras da '.ucfirst($config['tarefa']), 'Lista abaixo de todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' j� inclus'.$config['genero_tarefa'].'s de que est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].' depende para ser executad'.$config['genero_tarefa'].'.').'Predecessoras d'.$config['genero_tarefa'].' '.$config['tarefa'].':'.dicaF().'</td><td width="100%"></td></tr>';

echo '<tr><td style="width:50%"><select name="todas_tarefas" class="texto" style="width:100%" size="10" class="texto" multiple="multiple" ondblclick="adicionarDependenciaTarefa(document.frmEditar, document.frmEditar)" '.($wbs_completo ? 'disabled="disabled"': '' ).'>'.str_replace('selected', '', $opcoes_tarefa_superior).'</select></td><td style="width:50%">'.selecionaVetor($tarefaDep, 'tarefa_dependencias', 'style="width:100%" size="10" class="texto" multiple="multiple" ondblclick="removerDependenciaTarefa(document.frmEditar, document.frmEditar)" '.($wbs_completo ? 'disabled="disabled"': '' ), null).'</td><td></td></tr>';

echo '<tr style="display:none"><td align="left" colspan=20>'.selecionaVetor($tarefaDep_tipo, 'tarefa_dependencias_tipo', 'style="width:100%" size="10" class="texto" multiple="multiple" '.($wbs_completo ? 'disabled="disabled"': '' )).'</td></tr>';

echo '<tr><td align="left" colspan=20>'.dica('Tipo de predecessoras','<ul>
<li>In�cio - In�cio : a data de in�cio '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].' depender� da data de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima;</li>
<li>In�cio - Fim : a data de t�rmino '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].'depender� da data de in�cio d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima;</li>
<li>Fim - In�cio : a data de �nicio '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].' depender� da data de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima;</li>
<li>Fim - Fim : a data de t�rmino '.($config['genero_tarefa']=='a' ? 'desta' : 'deste').' '.$config['tarefa'].' depender� da data de t�rmino d'.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima.</li>
</ul>').'Tipo:'.dicaF().selecionaVetor($tipos_dependencias, 'tipos_dependencias', 'class="texto" '.($wbs_completo ? 'disabled="disabled"': '' ), 'TI').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Lat�ncia','O intervalo que dever� haver entre '.($config['genero_tarefa']=='a' ? 'esta' : 'este').' '.$config['tarefa'].' e '.$config['genero_tarefa'].' '.$config['tarefa'].' selecionada acima.').'Lat�ncia:'.dicaF().selecionaVetor($latencia, 'latencia', 'class="texto" '.($wbs_completo ? 'disabled="disabled"': '' ), '0').selecionaVetor($tipos_latencias, 'tipos_latencias', 'class="texto"'.($wbs_completo ? 'disabled="disabled"': '' ), 'd');
if($Aplic->profissional){
	echo '<input type="checkbox" name="dependencia_forte" id="dependencia_forte" style="vertical-align:middle;">'.dica('Menor Dist�ncia', 'Caso esteja marcada, '.$config['genero_tarefa'].'s '.$config['tarefas'].' buscar�o as menores dist�ncias permitidas entre '.($config['genero_tarefa']=='a' ? 'as mesmas' : 'os mesmos').', baseado nas depend�ncias e lat�ncias estipuldas.').'Menor dist�ncia'.dicaF();
	}
echo '</td></tr>';
if (!$wbs_completo) echo '<tr><td align="left">'.botao('&nbsp;&gt;&nbsp;', 'Inserir '.$config['tarefa'].' como predecessora', 'Ao clicar neste bot�o, '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecionadas na caixa da esquerda (Ctrl+nome d'.$config['genero_tarefa'].'s '.$config['tarefas'].') ser�o adicionadas na lista d'.$config['genero_tarefa'].'s predecessor'.$config['genero_tarefa'].'s d'.$config['genero_tarefa'].' '.$config['tarefa'].'.','','adicionarDependenciaTarefa(document.frmEditar, document.frmEditar)').'</td><td align="right">'.botao('&nbsp;&lt;&nbsp;', 'Excluir '.$config['tarefa'].' do grupo de predecessoras', 'Ao clicar neste bot�o, '.$config['genero_tarefa'].'s '.$config['tarefas'].' selecionadas na caixa da direita (Ctrl+nome d'.$config['genero_tarefa'].'s '.$config['tarefas'].') ser�o excluidas da lista d'.$config['genero_tarefa'].'s predecessor'.$config['genero_tarefa'].'s d'.$config['genero_tarefa'].' '.$config['tarefa'].' acima definid'.$config['genero_tarefa'],'','removerDependenciaTarefa(document.frmEditar, document.frmEditar)').'</td><td></td></tr>';
echo '</table>';
?>

