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

global $plano_acao_id, $perms, $periodo_todo, $este_dia, $primeiraData, $ultimaData, $dept_id, $cia_id, $plano_acao_item_filtro, $plano_acao_item_filtro_lista, $projeto_id, $Aplic, $titulo, $usuario_id, $cia_id, 
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
	$ata_id, 
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
	$me_id;


$db_inicio = $primeiraData->format('%Y-%m-%d %H:%M:%S');
$db_fim = $ultimaData->format('%Y-%m-%d %H:%M:%S');


$tipos = getSisValor('TipoEvento');
$links = array();

$sql = new BDConsulta;
$sql->adTabela('plano_acao_item','pai');
$sql->esqUnir('plano_acao', 'pa', 'pa.plano_acao_id = pai.plano_acao_item_acao');
if ($usuario_id) $sql->esqUnir('plano_acao_item_designados', 'id', 'id.plano_acao_item_id=pai.plano_acao_item_id');
if ($dept_id){
	$sql->esqUnir('plano_acao_item_depts', 'paid', 'pai.plano_acao_item_id = paid.plano_acao_item_id');
	$sql->esqUnir('depts', 'depts', 'depts.dept_id = paid.dept_id');
	}
$sql->adCampo('DISTINCT pai.plano_acao_item_id, plano_acao_item_nome, plano_acao_nome, plano_acao_item_acesso, plano_acao_item_inicio, plano_acao_item_fim, plano_acao_cor AS cor, plano_acao_item_oque, plano_acao_id');


if ($periodo_todo){
	$sql->adOnde('plano_acao_item_inicio <= \''.$db_fim.'\'');
	$sql->adOnde('plano_acao_item_fim >= \''.$db_inicio. '\'');
	} 
else $sql->adOnde('(plano_acao_item_inicio >= \''.$db_inicio.'\' AND plano_acao_item_inicio <=\''.$db_fim.'\') OR (plano_acao_item_fim >= \''.$db_inicio.'\' AND plano_acao_item_fim <=\''.$db_fim.'\')');

$sql->adOnde('plano_acao_item_inicio IS NOT NULL');
$sql->adOnde('plano_acao_item_fim IS NOT NULL');

if ($Aplic->profissional){
		$sql->esqUnir('plano_acao_gestao', 'plano_acao_gestao', 'pa.plano_acao_id = plano_acao_gestao_acao');
		if ($tarefa_id) $sql->adOnde('plano_acao_gestao_tarefa='.(int)$tarefa_id);
		elseif ($projeto_id) $sql->adOnde('plano_acao_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id) $sql->adOnde('plano_acao_gestao_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($tema_id) $sql->adOnde('plano_acao_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id) $sql->adOnde('plano_acao_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id) $sql->adOnde('plano_acao_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id) $sql->adOnde('plano_acao_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id) $sql->adOnde('plano_acao_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id) $sql->adOnde('plano_acao_gestao_pratica='.(int)$pratica_id);
		elseif ($pratica_indicador_id) $sql->adOnde('plano_acao_gestao_indicador='.(int)$pratica_indicador_id);
		elseif ($canvas_id) $sql->adOnde('plano_acao_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id) $sql->adOnde('plano_acao_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id) $sql->adOnde('plano_acao_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id) $sql->adOnde('plano_acao_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id) $sql->adOnde('plano_acao_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id) $sql->adOnde('plano_acao_gestao_ata='.(int)$ata_id);
		elseif ($swot_id) $sql->adOnde('plano_acao_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id) $sql->adOnde('plano_acao_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id) $sql->adOnde('plano_acao_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id) $sql->adOnde('plano_acao_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id) $sql->adOnde('plano_acao_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id) $sql->adOnde('plano_acao_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id) $sql->adOnde('plano_acao_gestao_programa='.(int)$programa_id);
		elseif ($licao_id) $sql->adOnde('plano_acao_gestao_licao='.(int)$licao_id);
		elseif ($link_id) $sql->adOnde('plano_acao_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id) $sql->adOnde('plano_acao_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id) $sql->adOnde('plano_acao_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id) $sql->adOnde('plano_acao_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id) $sql->adOnde('plano_acao_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id) $sql->adOnde('plano_acao_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id) $sql->adOnde('plano_acao_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id) $sql->adOnde('plano_acao_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id) $sql->adOnde('plano_acao_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id) $sql->adOnde('plano_acao_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id) $sql->adOnde('plano_acao_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id) $sql->adOnde('plano_acao_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id) $sql->adOnde('plano_acao_gestao_template='.(int)$template_id);
		elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('plano_acao_gestao_me='.(int)$me_id);
		}
	else{
	if ($projeto_id) $sql->adOnde('plano_acao_projeto='.(int)$projeto_id);
	if ($pratica_id) $sql->adOnde('plano_acao_pratica='.(int)$pratica_id);
	if ($pratica_indicador_id) $sql->adOnde('plano_acao_indicador='.(int)$pratica_indicador_id);
	if ($tema_id) $sql->adOnde('plano_acao_tema='.(int)$tema_id);
	if ($pg_objetivo_estrategico_id) $sql->adOnde('plano_acao_objetivo='.(int)$pg_objetivo_estrategico_id);
	if ($pg_estrategia_id) $sql->adOnde('plano_acao_estrategia='.(int)$pg_estrategia_id);
	if ($pg_meta_id) $sql->adOnde('plano_acao_meta='.(int)$pg_meta_id);
	if ($pg_fator_critico_id) $sql->adOnde('plano_acao_fator='.(int)$pg_fator_critico_id);
	}
if ($usuario_id) $sql->adOnde('(id.usuario_id='.(int)$usuario_id.' OR plano_acao_item_responsavel='.(int)$usuario_id.')');
if ($cia_id) $sql->adOnde('plano_acao_cia = '.(int)$cia_id);
if ($plano_acao_id) $sql->adOnde('pa.plano_acao_id = '.(int)$plano_acao_id);
if ($dept_id) $sql->adOnde('paid.dept_id = '.(int)$dept_id);
$sql->adOrdem('plano_acao_item_inicio');
$itens = $sql->Lista();
$sql->limpar();

$itens2 = array();
$inicio_hora = $config['cal_dia_inicio'];
$fim_hora = $config['cal_dia_fim'];
$data=getParam($_REQUEST, 'data', '');

//MUDAR DEPOIS POIS NÃO ESTÁ CONSIDERANDO EXPEDIENTE POSSIVELMENTE CADASTRADO
foreach ($itens as $linha) {
	if(permiteAcessarPlanoAcaoItem($linha['plano_acao_item_acesso'], $linha['plano_acao_item_id'])){	

		$inicio = new CData($linha['plano_acao_item_inicio']);
		$fim = new CData($linha['plano_acao_item_fim']);
		
		//preciso arredondar os minutos para exibição
		if ($inicio->minuto <= 7) $inicio->minuto=0;
		elseif ($inicio->minuto <= 22) $inicio->minuto=15;
		elseif ($inicio->minuto <= 37) $inicio->minuto=30;
		elseif ($inicio->minuto <= 52) $inicio->minuto=45;
		else {
			$inicio->minuto=0;
			if ($inicio->hora < 23) $fim->hora++;
			}
		
		if ($fim->minuto <= 7) $fim->minuto=0;
		elseif ($fim->minuto <= 22) $fim->minuto=15;
		elseif ($fim->minuto <= 37) $fim->minuto=30;
		elseif ($fim->minuto <= 52) $fim->minuto=45;
		else {
			$fim->minuto=0;
			if ($fim->hora < 23) $fim->hora++;
			}
		
		
		
		//inicia e termina no mesmo dia
		if ($inicio->format("%Y%m%d")==$fim->format("%Y%m%d")){
			$itens2[$inicio->format('%H%M%S')][] = $linha;
			if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
			if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
			}
		elseif($data==$inicio->format("%Y%m%d")){  
			// estou no 1o dia
			if ($inicio_hora > $inicio->format('%H')) $inicio_hora = $inicio->format('%H');
			$linha['plano_acao_item_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
			$itens2[$inicio->format('%H%M%S')][] = $linha;
			}
		elseif($data==$fim->format("%Y%m%d")){ 
			// estou no ultimo dia
			$linha['plano_acao_item_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
			$itens2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
			if ($fim_hora < $fim->format('%H')) $fim_hora = $fim->format('%H');
			}
		else{
			//um dia no meio
			$linha['plano_acao_item_fim']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_fim']< 10 ? '0' : '').$config['cal_dia_fim'].':00:00';
			$linha['plano_acao_item_inicio']=$fim->format("%Y-%m-%d").' '.($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].':00:00';
			$itens2[($config['cal_dia_inicio']< 10 ? '0' : '').$config['cal_dia_inicio'].'0000'][] = $linha;
			}
		}
	}

	
$tf = $Aplic->getPref('formatohora');
$diaFormato = $este_dia->format(FMT_TIMESTAMP_DATA);
$inicio=0;
$fim=24;
$inc=15;


$este_dia->setTime($inicio, 0, 0);
$saida = '<table cellpadding=0 cellspacing=0 class="tbl1" width="100%" style="background-color:#ffffff">';
$linhas = 0;
for ($i = 0, $n = (($fim - $inicio) * 60 / $inc); $i <= $n; $i++) {
	$saida .= '<tr>';
	$tm = $este_dia->format($tf);
	$saida .= '<td width="1%" align="right" nowrap="nowrap">'.($este_dia->getMinute() ? $tm : '<b>'.$tm.'</b>').'</td>';
	$formato_horas = $este_dia->format('%H%M%S');
	if (isset($itens2[$formato_horas]) && $itens2[$formato_horas]) {
		$quantidade = count($itens2[$formato_horas]);
		for ($j = 0; $j < $quantidade; $j++) {
			
			$linha = $itens2[$formato_horas][$j];
			$et = new CData($linha['plano_acao_item_fim']);
			$linhas = ((($et->getHour() * 60 + $et->getMinute()) - ($este_dia->getHour() * 60 + $este_dia->getMinute())) / $inc)+1;
			$saida .= '<td style="color:#'.melhorCor($linha['cor']).';background-color:#'.$linha['cor'].'" rowspan="'.$linhas.'" valign="top">';
			$saida .= link_acao_item($linha['plano_acao_item_id']).'</td>';
			}
		} 
	elseif (--$linhas <= 0) $saida .= '<td>&nbsp;</td>';
	$saida .= '</tr>';
	$este_dia->adSegundos(60 * $inc);
	}
$saida .= '</table>';
echo $saida;
?>