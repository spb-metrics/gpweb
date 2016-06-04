<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

function getEventoLinks($evento_filtro, $periodo_todo=false, $inicioPeriodo, $fimPeriodo, $links, $strMaxLarg, $minutoiCal=false, $usuario_id=null, $cia_id=null, $dept_id=null, 
	$tarefa_id=null,  
	$projeto_id=null,  
	$pg_perspectiva_id=null,  
	$tema_id=null,  
	$pg_objetivo_estrategico_id=null,  
	$pg_fator_critico_id=null,  
	$pg_estrategia_id=null, 
	$pg_meta_id=null,  
	$pratica_id=null,  
	$pratica_indicador_id=null,  
	$plano_acao_id=null,  
	$canvas_id=null,  
	$risco_id=null, 
	$risco_resposta_id=null, 
	$calendario_id=null,  
	$monitoramento_id=null,  
	$ata_id=null,  
	$swot_id=null,  
	$operativo_id=null, 
	$instrumento_id=null, 
	$recurso_id=null, 
	$problema_id=null, 
	$demanda_id=null, 
	$programa_id=null, 
	$licao_id=null, 
	$link_id=null, 
	$avaliacao_id=null, 
	$tgn_id=null, 
	$brainstorm_id=null, 
	$gut_id=null, 
	$causa_efeito_id=null, 
	$arquivo_id=null, 
	$forum_id=null, 
	$checklist_id=null, 
	$agenda_id=null, 
	$agrupamento_id=null, 
	$patrocinador_id=null, 
	$template_id=null,
	$painel_id=null,
	$painel_odometro_id=null,
	$painel_composicao_id=null,
	$tr_id=null,
	$me_id=null) {
	global $Aplic;
	$eventos = CEvento::getEventoParaPeriodo($inicioPeriodo, $fimPeriodo, $evento_filtro, $usuario_id, $cia_id, $dept_id,
		$tarefa_id, 
		$projeto_id, 
		$pg_perspectiva_id, 
		$tema_id, 
		$pg_objetivo_estrategico_id, 
		$pg_fator_critico_id, 
		$pg_estrategia_id,
		$pg_meta_id, 
		$pratica_id, 
		$pratica_indicador_id, 
		$plano_acao_id, 
		$canvas_id, 
		$risco_id,
		$risco_resposta_id,
		$calendario_id, 
		$monitoramento_id, 
		$ata_id, 
		$swot_id, 
		$operativo_id,
		$instrumento_id,
		$recurso_id,
		$problema_id,
		$demanda_id,
		$programa_id,
		$licao_id,
		$link_id,
		$avaliacao_id,
		$tgn_id,
		$brainstorm_id,
		$gut_id,
		$causa_efeito_id,
		$arquivo_id,
		$forum_id,
		$checklist_id,
		$agenda_id,
		$agrupamento_id,
		$patrocinador_id,
		$template_id,
		$painel_id,
		$painel_odometro_id,
		$painel_composicao_id,
		$tr_id,
		$me_id);
		
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	$cwd = explode(',', $GLOBALS['config']['cal_dias_uteis']);
	foreach ($eventos as $linha) {
		$inicio = new CData($linha['evento_inicio']);
		$fim = new CData($linha['evento_fim']);
		$data = $inicio;
		for ($i = 0, $i_cmp = $inicio->dataDiferenca($fim); $i <= $i_cmp; $i++) {
			$meio=false;
			if ($data->format($df)==$inicio->format($df) && $data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif').'<br>'.imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
			elseif ($data->format($df)==$inicio->format($df)) $inicio_fim=imagem('icones/inicio.gif').$inicio->format($tf).imagem('icones/vazio.gif');
			elseif ($data->format($df)==$fim->format($df)) $inicio_fim=imagem('icones/vazio.gif').$fim->format($tf).imagem('icones/fim.gif');
			else {
				$meio=true;
				$inicio_fim=imagem('icones/vazio.gif').'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.imagem('icones/vazio.gif');
				}
			$texto='<tr valign=middle><td>'.$inicio_fim.'</td><td>'. '<img src="'.acharImagem('evento'.$linha['evento_tipo'].'.png').'" style="vertical-align:middle" width="16" height="16" border=0 />'.$linha['evento_titulo'].'</td></tr>';
			if ($minutoiCal) $link = array('evento' => true, 'texto_mini' => $texto);
			else {
				$link['texto'] = '<tr><td nowrap=nowrap><a href="javascript:void(0);" onclick="url_passar(0, \'m=calendario&a=ver&evento_id='.$linha['evento_id'].'\');">'.$inicio_fim.'</a></td><td valign=middle>'.link_evento($linha['evento_id'],'',true).'</td></tr>';
				$link['texto_mini'] =$texto;
				}	
			if ($periodo_todo || !$meio) $links[$data->format('%Y%m%d')][] = $link;

			$data = $data->getNextDay();
			}
		}
	return $links;
	}

?>