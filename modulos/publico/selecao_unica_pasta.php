<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$mostrar_todos = getParam($_REQUEST, 'mostrar_todos', 0);

$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$arquivo_pasta_id = getParam($_REQUEST, 'arquivo_pasta_id', null);

$arquivo = getParam($_REQUEST, 'arquivo', null);
$uuid = getParam($_REQUEST, 'uuid', null);
$arquivo_id = getParam($_REQUEST, 'arquivo_id', null);

$edicao = getParam($_REQUEST, 'edicao', null);

$campo=getParam($_REQUEST, 'campo', null);
$chamarVolta = getParam($_REQUEST, 'chamar_volta', null);

$tarefa_id = getParam($_REQUEST, 'tarefa_id', null);
$projeto_id = getParam($_REQUEST, 'projeto_id', null);
$pg_perspectiva_id = getParam($_REQUEST, 'pg_perspectiva_id', null);
$tema_id = getParam($_REQUEST, 'tema_id', null);
$pg_objetivo_estrategico_id = getParam($_REQUEST, 'pg_objetivo_estrategico_id', null);
$pg_fator_critico_id = getParam($_REQUEST, 'pg_fator_critico_id', null);
$pg_estrategia_id = getParam($_REQUEST, 'pg_estrategia_id', null);
$pg_meta_id = getParam($_REQUEST, 'pg_meta_id', null);
$pratica_id = getParam($_REQUEST, 'pratica_id', null);
$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', null);
$plano_acao_id = getParam($_REQUEST, 'plano_acao_id', null);
$canvas_id = getParam($_REQUEST, 'canvas_id', null);
$risco_id = getParam($_REQUEST, 'risco_id', null);
$risco_resposta_id = getParam($_REQUEST, 'risco_resposta_id', null);
$calendario_id = getParam($_REQUEST, 'calendario_id', null);
$monitoramento_id = getParam($_REQUEST, 'monitoramento_id', null);
$ata_id = getParam($_REQUEST, 'ata_id', null);
$swot_id = getParam($_REQUEST, 'swot_id', null);
$operativo_id = getParam($_REQUEST, 'operativo_id', null);
$instrumento_id = getParam($_REQUEST, 'instrumento_id', null);
$recurso_id = getParam($_REQUEST, 'recurso_id', null);
$problema_id = getParam($_REQUEST, 'problema_id', null);
$demanda_id = getParam($_REQUEST, 'demanda_id', null);
$programa_id = getParam($_REQUEST, 'programa_id', null);
$licao_id = getParam($_REQUEST, 'licao_id', null);
$evento_id = getParam($_REQUEST, 'evento_id', null);
$link_id = getParam($_REQUEST, 'link_id', null);
$avaliacao_id = getParam($_REQUEST, 'avaliacao_id', null);
$tgn_id = getParam($_REQUEST, 'tgn_id', null);
$brainstorm_id = getParam($_REQUEST, 'brainstorm_id', null);
$gut_id = getParam($_REQUEST, 'gut_id', null);
$causa_efeito_id = getParam($_REQUEST, 'causa_efeito_id', null);
$arquivo_id = getParam($_REQUEST, 'arquivo_id', null);
$forum_id = getParam($_REQUEST, 'forum_id', null);
$checklist_id = getParam($_REQUEST, 'checklist_id', null);
$agenda_id = getParam($_REQUEST, 'agenda_id', null);
$agrupamento_id = getParam($_REQUEST, 'agrupamento_id', null);
$patrocinador_id = getParam($_REQUEST, 'patrocinador_id', null);
$template_id = getParam($_REQUEST, 'template_id', null);
$painel_id = getParam($_REQUEST, 'painel_id', null);
$painel_odometro_id = getParam($_REQUEST, 'painel_odometro_id', null);
$painel_composicao_id = getParam($_REQUEST, 'painel_composicao_id', null);
$tr_id = getParam($_REQUEST, 'tr_id', null);

$usuario=getParam($_REQUEST, 'usuario', null);


$nenhum_filtro=(
	!$tarefa_id &&
	!$projeto_id &&
	!$pg_perspectiva_id &&
	!$tema_id &&
	!$pg_objetivo_estrategico_id &&
	!$pg_fator_critico_id &&
	!$pg_estrategia_id &&
	!$pg_meta_id &&
	!$pratica_id &&
	!$pratica_indicador_id &&
	!$plano_acao_id &&
	!$canvas_id &&
	!$risco_id &&
	!$risco_resposta_id &&
	!$calendario_id &&
	!$monitoramento_id &&
	!$ata_id &&
	!$swot_id &&
	!$operativo_id &&
	!$instrumento_id &&
	!$recurso_id &&
	!$problema_id &&
	!$demanda_id &&
	!$programa_id &&
	!$licao_id &&
	!$evento_id &&
	!$link_id &&
	!$avaliacao_id &&
	!$tgn_id &&
	!$brainstorm_id &&
	!$gut_id &&
	!$causa_efeito_id &&
	!$arquivo_id &&
	!$forum_id &&
	!$checklist_id &&
	!$agenda_id &&
	!$agrupamento_id &&
	!$patrocinador_id &&
	!$template_id &&
	!$painel_id &&
	!$painel_odometro_id &&
	!$painel_composicao_id &&
	!$tr_id &&
	!$usuario
	);

//ver($_REQUEST);
$sql = new BDConsulta;

$sem_nada = null;

if ($Aplic->profissional && $arquivo){
	$sql->adTabela('arquivo_gestao');
	$sql->adCampo('arquivo_gestao.*');
	if ($arquivo_id) $sql->adOnde('arquivo_gestao_arquivo ='.(int)$arquivo_id);
	else $sql->adOnde('arquivo_gestao_uuid =\''.$uuid.'\'');
	$lista = $sql->lista();
	$sql->limpar();
	$vetor=array();
	$filtro='';
	$sem_nada='(arquivo_pasta_gestao_tarefa IS NULL
	AND arquivo_pasta_gestao_projeto IS NULL
	AND arquivo_pasta_gestao_perspectiva IS NULL
	AND arquivo_pasta_gestao_tema IS NULL
	AND arquivo_pasta_gestao_objetivo IS NULL
	AND arquivo_pasta_gestao_fator IS NULL
	AND arquivo_pasta_gestao_estrategia IS NULL
	AND arquivo_pasta_gestao_meta IS NULL
	AND arquivo_pasta_gestao_pratica IS NULL
	AND arquivo_pasta_gestao_acao IS NULL
	AND arquivo_pasta_gestao_canvas IS NULL
	AND arquivo_pasta_gestao_risco IS NULL
	AND arquivo_pasta_gestao_risco_resposta IS NULL
	AND arquivo_pasta_gestao_indicador IS NULL
	AND arquivo_pasta_gestao_calendario IS NULL
	AND arquivo_pasta_gestao_monitoramento IS NULL
	AND arquivo_pasta_gestao_ata IS NULL
	AND arquivo_pasta_gestao_swot IS NULL
	AND arquivo_pasta_gestao_operativo IS NULL
	AND arquivo_pasta_gestao_instrumento IS NULL
	AND arquivo_pasta_gestao_recurso IS NULL
	AND arquivo_pasta_gestao_problema IS NULL
	AND arquivo_pasta_gestao_demanda IS NULL
	AND arquivo_pasta_gestao_programa IS NULL
	AND arquivo_pasta_gestao_licao IS NULL
	AND arquivo_pasta_gestao_evento IS NULL
	AND arquivo_pasta_gestao_link IS NULL
	AND arquivo_pasta_gestao_avaliacao IS NULL
	AND arquivo_pasta_gestao_tgn IS NULL
	AND arquivo_pasta_gestao_brainstorm IS NULL
	AND arquivo_pasta_gestao_gut IS NULL
	AND arquivo_pasta_gestao_causa_efeito IS NULL
	AND arquivo_pasta_gestao_forum IS NULL
	AND arquivo_pasta_gestao_checklist IS NULL
	AND arquivo_pasta_gestao_agenda IS NULL
	AND arquivo_pasta_gestao_agrupamento IS NULL
	AND arquivo_pasta_gestao_patrocinador IS NULL
	AND arquivo_pasta_gestao_template IS NULL
	AND arquivo_pasta_gestao_painel IS NULL
	AND arquivo_pasta_gestao_painel_odometro IS NULL
	AND arquivo_pasta_gestao_painel_composicao IS NULL
	AND arquivo_pasta_gestao_tr IS NULL
	AND arquivo_pasta_gestao_usuario IS NULL)';
	foreach($lista as $linha){
		if ($linha['arquivo_gestao_tarefa']) $vetor[]='arquivo_pasta_gestao_tarefa='.(int)$linha['arquivo_gestao_tarefa'];
		elseif ($linha['arquivo_gestao_projeto']) $vetor[]='arquivo_pasta_gestao_projeto='.(int)$linha['arquivo_gestao_projeto'];
		elseif ($linha['arquivo_gestao_perspectiva']) $vetor[]='arquivo_pasta_gestao_perspectiva='.(int)$linha['arquivo_gestao_perspectiva'];
		elseif ($linha['arquivo_gestao_tema']) $vetor[]='arquivo_pasta_gestao_tema='.(int)$linha['arquivo_gestao_tema'];
		elseif ($linha['arquivo_gestao_objetivo']) $vetor[]='arquivo_pasta_gestao_objetivo='.(int)$linha['arquivo_gestao_objetivo'];
		elseif ($linha['arquivo_gestao_fator']) $vetor[]='arquivo_pasta_gestao_fator='.(int)$linha['arquivo_gestao_fator'];
		elseif ($linha['arquivo_gestao_estrategia']) $vetor[]='arquivo_pasta_gestao_estrategia='.(int)$linha['arquivo_gestao_estrategia'];
		elseif ($linha['arquivo_gestao_meta']) $vetor[]='arquivo_pasta_gestao_meta='.(int)$linha['arquivo_gestao_meta'];
		elseif ($linha['arquivo_gestao_pratica']) $vetor[]='arquivo_pasta_gestao_pratica='.(int)$linha['arquivo_gestao_pratica'];
		elseif ($linha['arquivo_gestao_acao']) $vetor[]='arquivo_pasta_gestao_acao='.(int)$linha['arquivo_gestao_acao'];
		elseif ($linha['arquivo_gestao_canvas']) $vetor[]='arquivo_pasta_gestao_canvas='.(int)$linha['arquivo_gestao_canvas'];
		elseif ($linha['arquivo_gestao_risco']) $vetor[]='arquivo_pasta_gestao_risco='.(int)$linha['arquivo_gestao_risco'];
		elseif ($linha['arquivo_gestao_risco_resposta']) $vetor[]='arquivo_pasta_gestao_risco_resposta='.(int)$linha['arquivo_gestao_risco_resposta'];
		elseif ($linha['arquivo_gestao_indicador']) $vetor[]='arquivo_pasta_gestao_indicador='.(int)$linha['arquivo_gestao_indicador'];
		elseif ($linha['arquivo_gestao_calendario']) $vetor[]='arquivo_pasta_gestao_calendario='.(int)$linha['arquivo_gestao_calendario'];
		elseif ($linha['arquivo_gestao_monitoramento']) $vetor[]='arquivo_pasta_gestao_monitoramento='.(int)$linha['arquivo_gestao_monitoramento'];
		elseif ($linha['arquivo_gestao_ata']) $vetor[]='arquivo_pasta_gestao_ata='.(int)$linha['arquivo_gestao_ata'];
		elseif ($linha['arquivo_gestao_swot']) $vetor[]='arquivo_pasta_gestao_swot='.(int)$linha['arquivo_gestao_swot'];
		elseif ($linha['arquivo_gestao_operativo']) $vetor[]='arquivo_pasta_gestao_operativo='.(int)$linha['arquivo_gestao_operativo'];
		elseif ($linha['arquivo_gestao_instrumento']) $vetor[]='arquivo_pasta_gestao_instrumento='.(int)$linha['arquivo_gestao_instrumento'];
		elseif ($linha['arquivo_gestao_recurso']) $vetor[]='arquivo_pasta_gestao_recurso='.(int)$linha['arquivo_gestao_recurso'];
		elseif ($linha['arquivo_gestao_problema']) $vetor[]='arquivo_pasta_gestao_problema='.(int)$linha['arquivo_gestao_problema'];
		elseif ($linha['arquivo_gestao_demanda']) $vetor[]='arquivo_pasta_gestao_demanda='.(int)$linha['arquivo_gestao_demanda'];
		elseif ($linha['arquivo_gestao_programa']) $vetor[]='arquivo_pasta_gestao_programa='.(int)$linha['arquivo_gestao_programa'];
		elseif ($linha['arquivo_gestao_licao']) $vetor[]='arquivo_pasta_gestao_licao='.(int)$linha['arquivo_gestao_licao'];
		elseif ($linha['arquivo_gestao_evento']) $vetor[]='arquivo_pasta_gestao_evento='.(int)$linha['arquivo_gestao_evento'];
		elseif ($linha['arquivo_gestao_link']) $vetor[]='arquivo_pasta_gestao_link='.(int)$linha['arquivo_gestao_link'];
		elseif ($linha['arquivo_gestao_avaliacao']) $vetor[]='arquivo_pasta_gestao_avaliacao='.(int)$linha['arquivo_gestao_avaliacao'];
		elseif ($linha['arquivo_gestao_tgn']) $vetor[]='arquivo_pasta_gestao_tgn='.(int)$linha['arquivo_gestao_tgn'];
		elseif ($linha['arquivo_gestao_brainstorm']) $vetor[]='arquivo_pasta_gestao_brainstorm='.(int)$linha['arquivo_gestao_brainstorm'];
		elseif ($linha['arquivo_gestao_gut']) $vetor[]='arquivo_pasta_gestao_gut='.(int)$linha['arquivo_gestao_gut'];
		elseif ($linha['arquivo_gestao_causa_efeito']) $vetor[]='arquivo_pasta_gestao_causa_efeito='.(int)$linha['arquivo_gestao_causa_efeito'];
		elseif ($linha['arquivo_gestao_forum']) $vetor[]='arquivo_pasta_gestao_forum='.(int)$linha['arquivo_gestao_forum'];
		elseif ($linha['arquivo_gestao_checklist']) $vetor[]='arquivo_pasta_gestao_checklist='.(int)$linha['arquivo_gestao_checklist'];
		elseif ($linha['arquivo_gestao_agenda']) $vetor[]='arquivo_pasta_gestao_agenda='.(int)$linha['arquivo_gestao_agenda'];
		elseif ($linha['arquivo_gestao_agrupamento']) $vetor[]='arquivo_pasta_gestao_agrupamento='.(int)$linha['arquivo_gestao_agrupamento'];
		elseif ($linha['arquivo_gestao_patrocinador']) $vetor[]='arquivo_pasta_gestao_patrocinador='.(int)$linha['arquivo_gestao_patrocinador'];
		elseif ($linha['arquivo_gestao_template']) $vetor[]='arquivo_pasta_gestao_template='.(int)$linha['arquivo_gestao_template'];
		elseif ($linha['arquivo_gestao_painel']) $vetor[]='arquivo_pasta_gestao_painel='.(int)$linha['arquivo_gestao_painel'];
		elseif ($linha['arquivo_gestao_painel_odometro']) $vetor[]='arquivo_pasta_gestao_painel_odometro='.(int)$linha['arquivo_gestao_painel_odometro'];
		elseif ($linha['arquivo_gestao_painel_composicao']) $vetor[]='arquivo_pasta_gestao_painel_composicao='.(int)$linha['arquivo_gestao_painel_composicao'];
		elseif ($linha['arquivo_gestao_tr']) $vetor[]='arquivo_pasta_gestao_tr='.(int)$linha['arquivo_gestao_tr'];
		elseif ($linha['arquivo_gestao_usuario']) $vetor[]='arquivo_pasta_gestao_usuario='.(int)$linha['arquivo_gestao_usuario'];
		}
	$filtro=implode(' OR ', $vetor);
	}
else if ($Aplic->profissional && ($arquivo_pasta_id || $uuid)){

	$sql->adTabela('arquivo_pasta_gestao');
	$sql->adCampo('arquivo_pasta_gestao.*');
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta_gestao_pasta ='.(int)$arquivo_pasta_id);
	else $sql->adOnde('arquivo_pasta_gestao_uuid =\''.$uuid.'\'');
	$lista = $sql->lista();
	$sql->limpar();
	$vetor=array();
	$filtro='';
	$sem_nada='(arquivo_pasta_gestao_tarefa IS NULL
		AND arquivo_pasta_gestao_projeto IS NULL
		AND arquivo_pasta_gestao_perspectiva IS NULL
		AND arquivo_pasta_gestao_tema IS NULL
		AND arquivo_pasta_gestao_objetivo IS NULL
		AND arquivo_pasta_gestao_fator IS NULL
		AND arquivo_pasta_gestao_estrategia IS NULL
		AND arquivo_pasta_gestao_meta IS NULL
		AND arquivo_pasta_gestao_pratica IS NULL
		AND arquivo_pasta_gestao_acao IS NULL
		AND arquivo_pasta_gestao_canvas IS NULL
		AND arquivo_pasta_gestao_risco IS NULL
		AND arquivo_pasta_gestao_risco_resposta IS NULL
		AND arquivo_pasta_gestao_indicador IS NULL
		AND arquivo_pasta_gestao_calendario IS NULL
		AND arquivo_pasta_gestao_monitoramento IS NULL
		AND arquivo_pasta_gestao_ata IS NULL
		AND arquivo_pasta_gestao_swot IS NULL
		AND arquivo_pasta_gestao_operativo IS NULL
		AND arquivo_pasta_gestao_instrumento IS NULL
		AND arquivo_pasta_gestao_recurso IS NULL
		AND arquivo_pasta_gestao_problema IS NULL
		AND arquivo_pasta_gestao_demanda IS NULL
		AND arquivo_pasta_gestao_programa IS NULL
		AND arquivo_pasta_gestao_licao IS NULL
		AND arquivo_pasta_gestao_evento IS NULL
		AND arquivo_pasta_gestao_link IS NULL
		AND arquivo_pasta_gestao_avaliacao IS NULL
		AND arquivo_pasta_gestao_tgn IS NULL
		AND arquivo_pasta_gestao_brainstorm IS NULL
		AND arquivo_pasta_gestao_gut IS NULL
		AND arquivo_pasta_gestao_causa_efeito IS NULL
		AND arquivo_pasta_gestao_forum IS NULL
		AND arquivo_pasta_gestao_checklist IS NULL
		AND arquivo_pasta_gestao_agenda IS NULL
		AND arquivo_pasta_gestao_agrupamento IS NULL
		AND arquivo_pasta_gestao_patrocinador IS NULL
		AND arquivo_pasta_gestao_template IS NULL
		AND arquivo_pasta_gestao_painel IS NULL
		AND arquivo_pasta_gestao_painel_odometro IS NULL
		AND arquivo_pasta_gestao_painel_composicao IS NULL
		AND arquivo_pasta_gestao_tr IS NULL
		AND arquivo_pasta_gestao_usuario IS NULL)';

	foreach($lista as $linha){
		if ($linha['arquivo_pasta_gestao_tarefa']) $vetor[]='arquivo_pasta_gestao_tarefa='.(int)$linha['arquivo_pasta_gestao_tarefa'];
		elseif ($linha['arquivo_pasta_gestao_projeto']) $vetor[]='arquivo_pasta_gestao_projeto='.(int)$linha['arquivo_pasta_gestao_projeto'];
		elseif ($linha['arquivo_pasta_gestao_perspectiva']) $vetor[]='arquivo_pasta_gestao_perspectiva='.(int)$linha['arquivo_pasta_gestao_perspectiva'];
		elseif ($linha['arquivo_pasta_gestao_tema']) $vetor[]='arquivo_pasta_gestao_tema='.(int)$linha['arquivo_pasta_gestao_tema'];
		elseif ($linha['arquivo_pasta_gestao_objetivo']) $vetor[]='arquivo_pasta_gestao_objetivo='.(int)$linha['arquivo_pasta_gestao_objetivo'];
		elseif ($linha['arquivo_pasta_gestao_fator']) $vetor[]='arquivo_pasta_gestao_fator='.(int)$linha['arquivo_pasta_gestao_fator'];
		elseif ($linha['arquivo_pasta_gestao_estrategia']) $vetor[]='arquivo_pasta_gestao_estrategia='.(int)$linha['arquivo_pasta_gestao_estrategia'];
		elseif ($linha['arquivo_pasta_gestao_meta']) $vetor[]='arquivo_pasta_gestao_meta='.(int)$linha['arquivo_pasta_gestao_meta'];
		elseif ($linha['arquivo_pasta_gestao_pratica']) $vetor[]='arquivo_pasta_gestao_pratica='.(int)$linha['arquivo_pasta_gestao_pratica'];
		elseif ($linha['arquivo_pasta_gestao_acao']) $vetor[]='arquivo_pasta_gestao_acao='.(int)$linha['arquivo_pasta_gestao_acao'];
		elseif ($linha['arquivo_pasta_gestao_canvas']) $vetor[]='arquivo_pasta_gestao_canvas='.(int)$linha['arquivo_pasta_gestao_canvas'];
		elseif ($linha['arquivo_pasta_gestao_risco']) $vetor[]='arquivo_pasta_gestao_risco='.(int)$linha['arquivo_pasta_gestao_risco'];
		elseif ($linha['arquivo_pasta_gestao_risco_resposta']) $vetor[]='arquivo_pasta_gestao_risco_resposta='.(int)$linha['arquivo_pasta_gestao_risco_resposta'];
		elseif ($linha['arquivo_pasta_gestao_indicador']) $vetor[]='arquivo_pasta_gestao_indicador='.(int)$linha['arquivo_pasta_gestao_indicador'];
		elseif ($linha['arquivo_pasta_gestao_calendario']) $vetor[]='arquivo_pasta_gestao_calendario='.(int)$linha['arquivo_pasta_gestao_calendario'];
		elseif ($linha['arquivo_pasta_gestao_monitoramento']) $vetor[]='arquivo_pasta_gestao_monitoramento='.(int)$linha['arquivo_pasta_gestao_monitoramento'];
		elseif ($linha['arquivo_pasta_gestao_ata']) $vetor[]='arquivo_pasta_gestao_ata='.(int)$linha['arquivo_pasta_gestao_ata'];
		elseif ($linha['arquivo_pasta_gestao_swot']) $vetor[]='arquivo_pasta_gestao_swot='.(int)$linha['arquivo_pasta_gestao_swot'];
		elseif ($linha['arquivo_pasta_gestao_operativo']) $vetor[]='arquivo_pasta_gestao_operativo='.(int)$linha['arquivo_pasta_gestao_operativo'];
		elseif ($linha['arquivo_pasta_gestao_instrumento']) $vetor[]='arquivo_pasta_gestao_instrumento='.(int)$linha['arquivo_pasta_gestao_instrumento'];
		elseif ($linha['arquivo_pasta_gestao_recurso']) $vetor[]='arquivo_pasta_gestao_recurso='.(int)$linha['arquivo_pasta_gestao_recurso'];
		elseif ($linha['arquivo_pasta_gestao_problema']) $vetor[]='arquivo_pasta_gestao_problema='.(int)$linha['arquivo_pasta_gestao_problema'];
		elseif ($linha['arquivo_pasta_gestao_demanda']) $vetor[]='arquivo_pasta_gestao_demanda='.(int)$linha['arquivo_pasta_gestao_demanda'];
		elseif ($linha['arquivo_pasta_gestao_programa']) $vetor[]='arquivo_pasta_gestao_programa='.(int)$linha['arquivo_pasta_gestao_programa'];
		elseif ($linha['arquivo_pasta_gestao_licao']) $vetor[]='arquivo_pasta_gestao_licao='.(int)$linha['arquivo_pasta_gestao_licao'];
		elseif ($linha['arquivo_pasta_gestao_evento']) $vetor[]='arquivo_pasta_gestao_evento='.(int)$linha['arquivo_pasta_gestao_evento'];
		elseif ($linha['arquivo_pasta_gestao_link']) $vetor[]='arquivo_pasta_gestao_link='.(int)$linha['arquivo_pasta_gestao_link'];
		elseif ($linha['arquivo_pasta_gestao_avaliacao']) $vetor[]='arquivo_pasta_gestao_avaliacao='.(int)$linha['arquivo_pasta_gestao_avaliacao'];
		elseif ($linha['arquivo_pasta_gestao_tgn']) $vetor[]='arquivo_pasta_gestao_tgn='.(int)$linha['arquivo_pasta_gestao_tgn'];
		elseif ($linha['arquivo_pasta_gestao_brainstorm']) $vetor[]='arquivo_pasta_gestao_brainstorm='.(int)$linha['arquivo_pasta_gestao_brainstorm'];
		elseif ($linha['arquivo_pasta_gestao_gut']) $vetor[]='arquivo_pasta_gestao_gut='.(int)$linha['arquivo_pasta_gestao_gut'];
		elseif ($linha['arquivo_pasta_gestao_causa_efeito']) $vetor[]='arquivo_pasta_gestao_causa_efeito='.(int)$linha['arquivo_pasta_gestao_causa_efeito'];
		elseif ($linha['arquivo_pasta_gestao_forum']) $vetor[]='arquivo_pasta_gestao_forum='.(int)$linha['arquivo_pasta_gestao_forum'];
		elseif ($linha['arquivo_pasta_gestao_checklist']) $vetor[]='arquivo_pasta_gestao_checklist='.(int)$linha['arquivo_pasta_gestao_checklist'];
		elseif ($linha['arquivo_pasta_gestao_agenda']) $vetor[]='arquivo_pasta_gestao_agenda='.(int)$linha['arquivo_pasta_gestao_agenda'];
		elseif ($linha['arquivo_pasta_gestao_agrupamento']) $vetor[]='arquivo_pasta_gestao_agrupamento='.(int)$linha['arquivo_pasta_gestao_agrupamento'];
		elseif ($linha['arquivo_pasta_gestao_patrocinador']) $vetor[]='arquivo_pasta_gestao_patrocinador='.(int)$linha['arquivo_pasta_gestao_patrocinador'];
		elseif ($linha['arquivo_pasta_gestao_template']) $vetor[]='arquivo_pasta_gestao_template='.(int)$linha['arquivo_pasta_gestao_template'];
		elseif ($linha['arquivo_pasta_gestao_painel']) $vetor[]='arquivo_pasta_gestao_painel='.(int)$linha['arquivo_gestao_painel'];
		elseif ($linha['arquivo_pasta_gestao_painel_odometro']) $vetor[]='arquivo_pasta_gestao_painel_odometro='.(int)$linha['arquivo_gestao_painel_odometro'];
		elseif ($linha['arquivo_pasta_gestao_painel_composicao']) $vetor[]='arquivo_pasta_gestao_painel_composicao='.(int)$linha['arquivo_gestao_painel_composicao'];
		elseif ($linha['arquivo_pasta_gestao_tr']) $vetor[]='arquivo_pasta_gestao_tr='.(int)$linha['arquivo_gestao_tr'];
		elseif ($linha['arquivo_pasta_gestao_usuario']) $vetor[]='arquivo_pasta_gestao_usuario='.(int)$linha['arquivo_pasta_gestao_usuario'];
		}
	$filtro=implode(' OR ', $vetor);
	}
else if ($Aplic->profissional && !$nenhum_filtro){

	$filtro='';
	$sem_nada='(arquivo_pasta_gestao_tarefa IS NULL
		AND arquivo_pasta_gestao_projeto IS NULL
		AND arquivo_pasta_gestao_perspectiva IS NULL
		AND arquivo_pasta_gestao_tema IS NULL
		AND arquivo_pasta_gestao_objetivo IS NULL
		AND arquivo_pasta_gestao_fator IS NULL
		AND arquivo_pasta_gestao_estrategia IS NULL
		AND arquivo_pasta_gestao_meta IS NULL
		AND arquivo_pasta_gestao_pratica IS NULL
		AND arquivo_pasta_gestao_acao IS NULL
		AND arquivo_pasta_gestao_canvas IS NULL
		AND arquivo_pasta_gestao_risco IS NULL
		AND arquivo_pasta_gestao_risco_resposta IS NULL
		AND arquivo_pasta_gestao_indicador IS NULL
		AND arquivo_pasta_gestao_calendario IS NULL
		AND arquivo_pasta_gestao_monitoramento IS NULL
		AND arquivo_pasta_gestao_ata IS NULL
		AND arquivo_pasta_gestao_swot IS NULL
		AND arquivo_pasta_gestao_operativo IS NULL
		AND arquivo_pasta_gestao_instrumento IS NULL
		AND arquivo_pasta_gestao_recurso IS NULL
		AND arquivo_pasta_gestao_problema IS NULL
		AND arquivo_pasta_gestao_demanda IS NULL
		AND arquivo_pasta_gestao_programa IS NULL
		AND arquivo_pasta_gestao_licao IS NULL
		AND arquivo_pasta_gestao_evento IS NULL
		AND arquivo_pasta_gestao_link IS NULL
		AND arquivo_pasta_gestao_avaliacao IS NULL
		AND arquivo_pasta_gestao_tgn IS NULL
		AND arquivo_pasta_gestao_brainstorm IS NULL
		AND arquivo_pasta_gestao_gut IS NULL
		AND arquivo_pasta_gestao_causa_efeito IS NULL
		AND arquivo_pasta_gestao_forum IS NULL
		AND arquivo_pasta_gestao_checklist IS NULL
		AND arquivo_pasta_gestao_agenda IS NULL
		AND arquivo_pasta_gestao_agrupamento IS NULL
		AND arquivo_pasta_gestao_patrocinador IS NULL
		AND arquivo_pasta_gestao_template IS NULL
		AND arquivo_pasta_gestao_painel IS NULL
		AND arquivo_pasta_gestao_painel_odometro IS NULL
		AND arquivo_pasta_gestao_painel_composicao IS NULL
		AND arquivo_pasta_gestao_tr IS NULL
		AND arquivo_pasta_gestao_usuario IS NULL)';

	if ($tarefa_id) $filtro='arquivo_pasta_gestao_tarefa='.(int)$tarefa_id;
	elseif ($projeto_id) $filtro='arquivo_pasta_gestao_projeto='.(int)$projeto_id;
	elseif ($pg_perspectiva_id) $filtro='arquivo_pasta_gestao_perspectiva='.(int)$pg_perspectiva_id;
	elseif ($tema_id) $filtro='arquivo_pasta_gestao_tema='.(int)$tema_id;
	elseif ($pg_objetivo_estrategico_id) $filtro='arquivo_pasta_gestao_objetivo='.(int)$pg_objetivo_estrategico_id;
	elseif ($pg_fator_critico_id) $filtro='arquivo_pasta_gestao_fator='.(int)$pg_fator_critico_id;
	elseif ($pg_estrategia_id) $filtro='arquivo_pasta_gestao_estrategia='.(int)$pg_estrategia_id;
	elseif ($pg_meta_id) $filtro='arquivo_pasta_gestao_meta='.(int)$pg_meta_id;
	elseif ($pratica_id) $filtro='arquivo_pasta_gestao_pratica='.(int)$pratica_id;
	elseif ($plano_acao_id) $filtro='arquivo_pasta_gestao_acao='.(int)$plano_acao_id;
	elseif ($canvas_id) $filtro='arquivo_pasta_gestao_canvas='.(int)$canvas_id;
	elseif ($risco_id) $filtro='arquivo_pasta_gestao_risco='.(int)$risco_id;
	elseif ($risco_resposta_id) $filtro='arquivo_pasta_gestao_risco_resposta='.(int)$risco_resposta_id;
	elseif ($pratica_indicador_id) $filtro='arquivo_pasta_gestao_indicador='.(int)$pratica_indicador_id;
	elseif ($calendario_id) $filtro='arquivo_pasta_gestao_calendario='.(int)$calendario_id;
	elseif ($monitoramento_id) $filtro='arquivo_pasta_gestao_monitoramento='.(int)$monitoramento_id;
	elseif ($ata_id) $filtro='arquivo_pasta_gestao_ata='.(int)$ata_id;
	elseif ($swot_id) $filtro='arquivo_pasta_gestao_swot='.(int)$swot_id;
	elseif ($operativo_id) $filtro='arquivo_pasta_gestao_operativo='.(int)$operativo_id;
	elseif ($instrumento_id) $filtro='arquivo_pasta_gestao_instrumento='.(int)$instrumento_id;
	elseif ($recurso_id) $filtro='arquivo_pasta_gestao_recurso='.(int)$recurso_id;
	elseif ($problema_id) $filtro='arquivo_pasta_gestao_problema='.(int)$problema_id;
	elseif ($demanda_id) $filtro='arquivo_pasta_gestao_demanda='.(int)$demanda_id;
	elseif ($programa_id) $filtro='arquivo_pasta_gestao_programa='.(int)$programa_id;
	elseif ($licao_id) $filtro='arquivo_pasta_gestao_licao='.(int)$licao_id;
	elseif ($evento_id) $filtro='arquivo_pasta_gestao_evento='.(int)$evento_id;
	elseif ($link_id) $filtro='arquivo_pasta_gestao_link='.(int)$link_id;
	elseif ($avaliacao_id) $filtro='arquivo_pasta_gestao_avaliacao='.(int)$avaliacao_id;
	elseif ($tgn_id) $filtro='arquivo_pasta_gestao_tgn='.(int)$tgn_id;
	elseif ($brainstorm_id) $filtro='arquivo_pasta_gestao_brainstorm='.(int)$brainstorm_id;
	elseif ($gut_id) $filtro='arquivo_pasta_gestao_gut='.(int)$gut_id;
	elseif ($causa_efeito_id) $filtro='arquivo_pasta_gestao_causa_efeito='.(int)$causa_efeito_id;
	elseif ($forum_id) $filtro='arquivo_pasta_gestao_forum='.(int)$forum_id;
	elseif ($checklist_id) $filtro='arquivo_pasta_gestao_checklist='.(int)$checklist_id;
	elseif ($agenda_id) $filtro='arquivo_pasta_gestao_agenda='.(int)$agenda_id;
	elseif ($agrupamento_id) $filtro='arquivo_pasta_gestao_agrupamento='.(int)$agrupamento_id;
	elseif ($patrocinador_id) $filtro='arquivo_pasta_gestao_patrocinador='.(int)$patrocinador_id;
	elseif ($template_id) $filtro='arquivo_pasta_gestao_template='.(int)$template_id;
	elseif ($painel_id) $filtro='arquivo_pasta_gestao_painel='.(int)$painel_id;
	elseif ($painel_odometro_id) $filtro='arquivo_pasta_gestao_painel_odometro='.(int)$painel_odometro_id;
	elseif ($painel_composicao_id) $filtro='arquivo_pasta_gestao_painel_composicao='.(int)$painel_composicao_id;
	elseif ($tr_id) $filtro='arquivo_pasta_gestao_tr='.(int)$tr_id;
	elseif ($usuario) $filtro='arquivo_pasta_gestao_usuario='.(int)$usuario;
	}




$sql->adTabela('arquivo_pasta');
if ($Aplic->profissional){
	$sql->esqUnir('arquivo_pasta_gestao', 'arquivo_pasta_gestao', 'arquivo_pasta_gestao_pasta=arquivo_pasta.arquivo_pasta_id');
	if (!$nenhum_filtro && $filtro) $sql->adOnde($filtro);
	elseif ($nenhum_filtro && $sem_nada) $sql->adOnde($sem_nada.($filtro ? ' OR '.$filtro : ''));
	}
else {
	if ($tarefa_id) $sql->adOnde('arquivo_pasta_tarefa = '.$tarefa_id.' OR arquivo_pasta_tarefa IS NULL');
	if ($projeto_id) $sql->adOnde('arquivo_pasta_projeto = '.$projeto_id);
	else if ($pratica_id) $sql->adOnde('arquivo_pasta_pratica = '.$pratica_id);
	else if ($plano_acao_id) $sql->adOnde('arquivo_pasta_acao = '.$plano_acao_id);
	else if ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_indicador = '.$pratica_indicador_id);
	else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_pasta_objetivo = '.$pg_objetivo_estrategico_id);
	else if ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_estrategia = '.$pg_estrategia_id);
	else if ($pg_fator_critico_id) $sql->adOnde('arquivo_pasta_fator = '.$pg_fator_critico_id);
	else if ($pg_meta_id) $sql->adOnde('arquivo_pasta_meta = '.$pg_meta_id);
	else if ($calendario_id) $sql->adOnde('arquivo_pasta_calendario = '.$calendario);
	else if ($ata_id) $sql->adOnde('arquivo_pasta_ata = '.$ata_id);
	else if ($demanda_id) $sql->adOnde('arquivo_pasta_demanda = '.$demanda_id);
	else if ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_perspectiva = '.$pg_perspectiva_id);
	else if ($tema_id) $sql->adOnde('arquivo_pasta_tema = '.$tema_id);
	else if ($nenhum_filtro) $sql->adOnde('arquivo_pasta_usuario = '.$Aplic->usuario_id);
	}
$sql->adCampo('arquivo_pasta_id, arquivo_pasta_nome, arquivo_pasta_acesso');
$sql->adOnde('arquivo_pasta_superior = arquivo_pasta_id OR arquivo_pasta_superior IS NULL');
$pastas_superiores = $sql->lista();
$sql->limpar();


echo '<form method="post" name="env">';
echo '<input type="hidden" name="m" value="publico" />';
echo '<input type="hidden" name="a" value="selecao_unica_pasta" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="chamar_volta" value="'.$chamarVolta.'" />';
echo '<input type="hidden" name="campo" value="'.$campo.'" />';
echo '<input type="hidden" name="edicao" value="'.$edicao.'" />';




echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
echo '<input type="hidden" name="pg_objetivo_estrategico_id" id="pg_objetivo_estrategico_id" value="'.$pg_objetivo_estrategico_id.'" />';
echo '<input type="hidden" name="pg_fator_critico_id" id="pg_fator_critico_id" value="'.$pg_fator_critico_id.'" />';
echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="canvas_id" id="canvas_id" value="'.$canvas_id.'" />';
echo '<input type="hidden" name="risco_id" id="risco_id" value="'.$risco_id.'" />';
echo '<input type="hidden" name="risco_resposta_id" id="risco_resposta_id" value="'.$risco_resposta_id.'" />';
echo '<input type="hidden" name="calendario_id" id="calendario_id" value="'.$calendario_id.'" />';
echo '<input type="hidden" name="monitoramento_id" id="monitoramento_id" value="'.$monitoramento_id.'" />';
echo '<input type="hidden" name="ata_id" id="ata_id" value="'.$ata_id.'" />';
echo '<input type="hidden" name="swot_id" id="swot_id" value="'.$swot_id.'" />';
echo '<input type="hidden" name="operativo_id" id="operativo_id" value="'.$operativo_id.'" />';
echo '<input type="hidden" name="instrumento_id" id="instrumento_id" value="'.$instrumento_id.'" />';
echo '<input type="hidden" name="recurso_id" id="recurso_id" value="'.$recurso_id.'" />';
echo '<input type="hidden" name="problema_id" id="problema_id" value="'.$problema_id.'" />';
echo '<input type="hidden" name="demanda_id" id="demanda_id" value="'.$demanda_id.'" />';
echo '<input type="hidden" name="programa_id" id="programa_id" value="'.$programa_id.'" />';
echo '<input type="hidden" name="licao_id" id="licao_id" value="'.$licao_id.'" />';
echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="link_id" id="link_id" value="'.$link_id.'" />';
echo '<input type="hidden" name="avaliacao_id" id="avaliacao_id" value="'.$avaliacao_id.'" />';
echo '<input type="hidden" name="tgn_id" id="tgn_id" value="'.$tgn_id.'" />';
echo '<input type="hidden" name="brainstorm_id" id="brainstorm_id" value="'.$brainstorm_id.'" />';
echo '<input type="hidden" name="gut_id" id="gut_id" value="'.$gut_id.'" />';
echo '<input type="hidden" name="causa_efeito_id" id="causa_efeito_id" value="'.$causa_efeito_id.'" />';
echo '<input type="hidden" name="arquivo_id" id="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="forum_id" id="forum_id" value="'.$forum_id.'" />';
echo '<input type="hidden" name="checklist_id" id="checklist_id" value="'.$checklist_id.'" />';
echo '<input type="hidden" name="agenda_id" id="agenda_id" value="'.$agenda_id.'" />';
echo '<input type="hidden" name="agrupamento_id" id="agrupamento_id" value="'.$agrupamento_id.'" />';
echo '<input type="hidden" name="patrocinador_id" id="patrocinador_id" value="'.$patrocinador_id.'" />';
echo '<input type="hidden" name="template_id" id="template_id" value="'.$template_id.'" />';
echo '<input type="hidden" name="painel_id" id="painel_id" value="'.$painel_id.'" />';
echo '<input type="hidden" name="painel_odometro_id" id="painel_odometro_id" value="'.$painel_odometro_id.'" />';
echo '<input type="hidden" name="painel_composicao_id" id="painel_composicao_id" value="'.$painel_composicao_id.'" />';
echo '<input type="hidden" name="tr_id" id="tr_id" value="'.$tr_id.'" />';

echo estiloTopoCaixa();


echo '<table width="100%" class="std" cellspacing=0 cellpadding=0>';

echo '<tr><td><input type="checkbox" name="dept_id[]" id="secao0" value="" onChange="setPasta(null);" /><label for="pasta_0">Nenhuma pasta</label></td></tr>';



if ($edicao) {
	foreach($pastas_superiores as $linha)	if (permiteEditarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])) {
		echo '<tr><td><input type="checkbox" name="dept_id[]" id="secao'.$linha['arquivo_pasta_id'].'" value="'.$linha['arquivo_pasta_id'].'" '.($linha['arquivo_pasta_id']==$arquivo_pasta_id ? 'checked="checked"' : '').' onChange="setPasta('.$linha['arquivo_pasta_id'].', \''.$linha['arquivo_pasta_nome'].'\');" /><label for="pasta_'.$linha['arquivo_pasta_id'].'">'.$linha['arquivo_pasta_nome'].'</label></td></tr>';
		subniveis($linha['arquivo_pasta_id'], '&nbsp;&nbsp;&nbsp;');
		}	
	}
else {
	foreach($pastas_superiores as $linha) if (permiteAcessarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])) {
		echo '<tr><td><input type="checkbox" name="dept_id[]" id="secao'.$linha['arquivo_pasta_id'].'" value="'.$linha['arquivo_pasta_id'].'" '.($linha['arquivo_pasta_id']==$arquivo_pasta_id ? 'checked="checked"' : '').' onChange="setPasta('.$linha['arquivo_pasta_id'].', \''.$linha['arquivo_pasta_nome'].'\');" /><label for="pasta_'.$linha['arquivo_pasta_id'].'">'.$linha['arquivo_pasta_nome'].'</label></td></tr>';
		subniveis($linha['arquivo_pasta_id'], '&nbsp;&nbsp;&nbsp;');
		}
	}


echo '</table>';
echo estiloFundoCaixa();
echo '</form>';

function subniveis($pasta_id, $subnivel){
	global $Aplic, $edicao, $arquivo_pasta_id, $chamarVolta, $campo, $sql,$sem_nada, $filtro,
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
	$evento_id,
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
	$tr_id;
	$sql->adTabela('arquivo_pasta');
	$sql->adCampo('arquivo_pasta_id, arquivo_pasta_nome, arquivo_pasta_acesso');
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_pasta_gestao', 'arquivo_pasta_gestao', 'arquivo_pasta_gestao_pasta=arquivo_pasta.arquivo_pasta_id');
		if($sem_nada) $sql->adOnde($sem_nada.($filtro ? ' OR '.$filtro : ''));
		}
	else {
		
		
		if ($tarefa_id) $sql->adOnde('arquivo_pasta_tarefa = '.$tarefa_id.' OR arquivo_pasta_tarefa IS NULL');
		if ($projeto_id) $sql->adOnde('arquivo_pasta_projeto = '.$projeto_id);
		else if ($pratica_id) $sql->adOnde('arquivo_pasta_pratica = '.$pratica_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_pasta_acao = '.$plano_acao_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_indicador = '.$pratica_indicador_id);
		else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_pasta_objetivo = '.$pg_objetivo_estrategico_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_estrategia = '.$pg_estrategia_id);
		else if ($pg_fator_critico_id) $sql->adOnde('arquivo_pasta_fator = '.$pg_fator_critico_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_pasta_meta = '.$pg_meta_id);
		else if ($calendario_id) $sql->adOnde('arquivo_pasta_calendario = '.$calendario);
		else if ($ata_id) $sql->adOnde('arquivo_pasta_ata = '.$ata_id);
		else if ($demanda_id) $sql->adOnde('arquivo_pasta_demanda = '.$demanda_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_perspectiva = '.$pg_perspectiva_id);
		else if ($tema_id) $sql->adOnde('arquivo_pasta_tema = '.$tema_id);
		else if ($nenhum_filtro) $sql->adOnde('arquivo_pasta_usuario = '.$Aplic->usuario_id);
		}
	$sql->adOnde('arquivo_pasta_superior = '.(int)$pasta_id);
	$subordinados = $sql->lista();
	$sql->limpar();

	foreach($subordinados as $linha){
		if ($edicao) {
			if (permiteEditarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])) {
				echo '<tr><td>'.$subnivel.'<input type="checkbox" name="arquivo_pasta_id[]" id="secao'.$linha['arquivo_pasta_id'].'" value="'.$linha['arquivo_pasta_id'].'" '.($linha['arquivo_pasta_id']==$arquivo_pasta_id ? 'checked="checked"' : '').' onChange="setPasta('.$linha['arquivo_pasta_id'].', \''.$linha['arquivo_pasta_nome'].'\');" /><label for="pasta_'.$linha['arquivo_pasta_id'].'">'.$linha['arquivo_pasta_nome'].'</label></td></tr>';
				subniveis($linha['arquivo_pasta_id'], $subnivel.'&nbsp;&nbsp;&nbsp;');
				}	
			}
		else {
			if (permiteAcessarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])) {
				echo '<tr><td>'.$subnivel.'<input type="checkbox" name="arquivo_pasta_id[]" id="secao'.$linha['arquivo_pasta_id'].'" value="'.$linha['arquivo_pasta_id'].'" '.($linha['arquivo_pasta_id']==$arquivo_pasta_id ? 'checked="checked"' : '').' onChange="setPasta('.$linha['arquivo_pasta_id'].', \''.$linha['arquivo_pasta_nome'].'\');" /><label for="pasta_'.$linha['arquivo_pasta_id'].'">'.$linha['arquivo_pasta_nome'].'</label></td></tr>';
				subniveis($linha['arquivo_pasta_id'], $subnivel.'&nbsp;&nbsp;&nbsp;');
				}
			}
		}
	}



function remover_invalido($arr) {
	$resultado = array();
	foreach ($arr as $val) if (!empty($val) && trim($val)) $resultado[] = $val;
	return $resultado;
	}
?>
<script language="javascript">


function setPasta(arquivo_pasta_id, arquivo_pasta_nome) {
	if(parent && parent.gpwebApp){
		parent.gpwebApp._popupCallback(arquivo_pasta_id, arquivo_pasta_nome);
		return;
		}
	window.opener.<?php echo $chamarVolta?>(arquivo_pasta_id, arquivo_pasta_nome);
	self.close();
	}

function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:300px;" onchange="javascript:mudar_om();"');
	}


</script>
