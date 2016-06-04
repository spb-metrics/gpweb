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

global $projeto_id;
global $st_projetos_arr;
$df = '%d/%m/%Y';
$PrioridadeProjeto = getSisValor('PrioridadeProjeto');
$projetoStatus = getSisValor('StatusProjeto');
$sp_obj = new CProjeto();
$sp_obj->load($projeto_id);
$original_projeto_id = $sp_obj->projeto_superior_original;
$projetosEstruturados = getProjetosEstruturados($original_projeto_id);
echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" ><tr><td><table border=0 cellpadding="5" cellspacing="1" bgcolor="black"><tr>';
echo '<th width="12">&nbsp;</th>';
echo '<th>'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nome dos mesmos.').ucfirst($config['projeto']). dicaF().'</th>';
echo '<th>'.dica('Prioridade', 'A prioridade para fins de filtragem.').'P'.dicaF().'</th>';
echo '<th>'.dica(ucfirst($config['organizacao']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_organizacao'].' '.$config['organizacao'].'.').$config['organizacao'].dicaF().'</th>';
echo '<th>'.dica('Respons�vel', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_usuario'].'s '.$config['usuarios'].' respons�veis pelos mesmos').'Respons�vel'.dicaF().'</th>';
echo '<th>'.dica('In�cio', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de in�cio.').'In�cio'.dicaF().'</th>';
echo '<th>'.dica('T�rmino', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de t�rmino.').'T�rmino'.dicaF().'</th>';
echo '<th>'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Visualizar os Status d'.$config['genero_projeto'].'s '.$config['projetos'].', que podem ser sete a saber: <ul><li>N�o definido - caso em que ainda n�o h� muitos dados concretos sobre o mesmo, ou que ainda n�o tem um respons�vel efetivo.</li><li>Proposto - quando j� tem um respons�vel efetivo definido, porem n�o iniciou ainda os trabalhos.</li><li>Em Planejamento - quando n�o foi iniciado nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', porem a equipe designada j� est�o realizando trabalhos preprat�rios</li><li>Em andamento - quando est� em execu��o, com ao menos algum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' com mais de 0% realizado e que n�o esteja em <b>espera</b>.</li><li>Em Espera - quando '.$config['genero_projeto'].' '.$config['projeto'].' iniciou, mas por algum motivo incontra-se interrompido. A interrup��o pode ser permanente ou provis�ria.</li><li>Completado - quando todas '.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].' atingiram 100% executadas.</li><li>Modelo - quando '.$config['genero_projeto'].' '.$config['projeto'].' e su'.$config['genero_tarefa'].'s '.$config['tarefas'].' sirvam apenas de refer�ncia, para outr'.$config['genero_projeto'].'s '.$config['projetos'].', n�o sendo um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].' real.</li></ul>.').'Status'.dicaF().'</th>';
echo '</tr>';
$s = '';
$x=0;
foreach ($st_projetos_arr as $projeto) {
	$linha = $projeto[0];
	$nivel = $projeto[1];
	if ($linha['projeto_id']) {
		$s_projeto = new CProjeto();
		$s_projeto->load($linha['projeto_id']);
		$data_inicio = intval($s_projeto->projeto_data_inicio) ? new CData($s_projeto->projeto_data_inicio) : null;
		$data_fim = intval($s_projeto->projeto_data_fim) ? new CData($s_projeto->projeto_data_fim) : null;
		$data_fim_atual = intval($s_projeto->projeto_fim_atualizado) ? new CData($s_projeto->projeto_fim_atualizado) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		$x++;
		$linha_class = ($x % 2) ? 'style="background:#fff;"' : 'style="background:#f0f0f0;"';
		$linha_classr = ($x % 2) ? 'style="background:#fff;text-align:right;"' : 'style="background:#f0f0f0;text-align:right;"';
		$s .= '<tr><td '.$linha_class.' align="center"><a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=editar&projeto_id='.$linha['projeto_id'].'\');">'.dica('Editar '.ucfirst($config['projeto']), 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.($config['genero_projeto']=='o' ? 'este' : 'esta').' '.$config['projeto'].'.').'<img src="'.acharImagem('icones/editar.gif').'" border=0 />'.dicaF().'</a></td>';
		if ($nivel) $sd = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', ($nivel - 1)).imagem('icones/subnivel.gif').'&nbsp;'.link_projeto($linha['projeto_id']);
		else $sd = link_projeto($linha['projeto_id']);
		$s .= '<td '.$linha_class.' align="left">'.$sd.'</td>';
		$s .= '<td '.$linha_class.' align="center">'.prioridade($s_projeto->projeto_prioridade, true).'</td>';
		$s .= '<td '.$linha_class.' align="center">'.link_cia($s_projeto->projeto_cia).'</td>';
		$s .= '<td '.$linha_class.' align="center">'.link_usuario($s_projeto->projeto_responsavel).'</td>';
		$s .= '<td '.$linha_class.' align="center">'.($data_inicio ? $data_inicio->format($df):'&nbsp;').'</td>';
		$s .= '<td '.$linha_class.' align="center">'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td>';
		$s .= '<td '.$linha_class.' align="center">'.$projetoStatus[$s_projeto->projeto_status].'</td></tr>';
		}
	}
echo $s;
echo '</table><table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td colspan="20">&nbsp;</td></tr><tr><td align="left" colspan="20">';
$src = "?m=projetos&a=ver_sub_projetos_gantt&sem_cabecalho=1&mostrarLegendas=1&proFiltro=&mostrarInativo=1mostrarTodoGantt=1&original_projeto_id=$original_projeto_id&width=' + ((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
echo "<script>document.write('<img src=\"$src\">')</script>";
echo '</td></tr></table></td></tr></table>';
?>
