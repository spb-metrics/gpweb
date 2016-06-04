<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

function getCompromissoLinks($periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal = false, $usuario_id=0, $agenda_tipo_id=0) {
	global $agenda_filtro,$Aplic;
	$compromissos = CAgenda::getCompromissoParaPeriodo($inicioPeriodo, $fimPeriodo, $agenda_filtro, $usuario_id, $agenda_tipo_id);
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	foreach ($compromissos as $linha) {
		$inicio = new CData($linha['agenda_inicio']);
		$fim = new CData($linha['agenda_fim']);
		$data = $inicio;
		$cwd = explode(',', $GLOBALS['config']['cal_dias_uteis']);
		for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
			$meio=false;
			if ($data->format($df)==$inicio->format($df) && $data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
			elseif ($data->format($df)==$inicio->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif');
			elseif ($data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
			else {
				$meio=true;
				$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
				}
			$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.$linha['agenda_titulo'].'</td></tr>';
			if ($minutoiCal) $link = array('texto_mini' => $texto);
			else {
				$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=ver_compromisso&agenda_id='.$linha['agenda_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.link_compromisso($linha['agenda_id'],'',true).'</td></tr>';
				$link['texto_mini'] =$texto;
				}	
			if ($periodo_todo || !$meio) $links[$data->format(FMT_TIMESTAMP_DATA)][] = $link;
			
			$data = $data->getNextDay();
			}
		}
	

	$db_inicio= $inicioPeriodo->format(FMT_DATA_MYSQL);
	$db_fim = $fimPeriodo->format(FMT_DATA_MYSQL);	
	$q = new BDConsulta;
	$q->adTabela('parafazer_tarefa', 'parafazer_tarefa');
	$q->esqUnir('parafazer_listas', 'parafazer_listas', 'parafazer_tarefa.lista_id=parafazer_listas.id');
	$q->adCampo('parafazer_tarefa.id, d, lista_id, datafinal, titulo, nota, nome, prio');
	$q->adOrdem('datafinal ASC');	
	$q->adOnde('(datafinal <= \''.$db_fim.'\' AND datafinal >= \''.$db_inicio.'\')');
	$q->adOnde('parafazer_tarefa.compl=0');
	$q->adOnde('usuario_id='.$Aplic->usuario_id);	
	$parafazer=$q->Lista();

	foreach ($parafazer as $linha) {
		$inicio = new CData($linha['datafinal']);
		$fim = new CData($linha['datafinal']);
		$data = $inicio;
		$cwd = explode(',', $GLOBALS['config']['cal_dias_uteis']);
		for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {

	
			$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
			$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'.imagem('icones/todo.gif').$linha['titulo'].'</td></tr>';
			if ($linha['prio']==2) $prioridade='muito alta'; 
			elseif ($linha['prio']==1) $prioridade='alta'; 
			elseif ($linha['prio']==0) $prioridade='normal'; 
			elseif ($linha['prio']<0) $prioridade='baixa'; 
			
			if ($minutoiCal) $link = array('texto_mini' => $texto);
			else {
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td colspan=2><b>Atividade Para Fazer</b></td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Lista</b></td><td>'.$linha['nome'].'</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Prioridade</b></td><td>'.$prioridade.'</td></tr>';
				if ($linha['nota']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Nota</b></td><td>'.$linha['nota'].'</td></tr>';
				$dentro .= '</table>';
				$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="lista_todo('.$linha['lista_id'].');">'.$inicio_fim.'</a></td><td valign=middle><a href="javascript:void(0);" onclick="lista_todo('.$linha['lista_id'].');">'.imagem('icones/todo.gif').dica($linha['titulo'],$dentro).$linha['titulo'].dicaF().'</a></td></tr>';
				//$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="lista_todo('.$linha['lista_id'].');">'.imagem('icones/todo.gif').dica($linha['titulo'],$dentro).$linha['titulo'].dicaF().'</td></tr>';
				$link['texto_mini'] =$texto;
				}	
			$links[$data->format(FMT_TIMESTAMP_DATA)][] = $link;

			$data = $data->getNextDay();
			}
		}
	
	
	return $links;
	}

?>