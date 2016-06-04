<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


global  $podeEditar, $usuario_id, 
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
	$tr_id,
	$me_id;

$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql = new BDConsulta;

$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_acesso, pratica_indicador_externo, pratica_indicador_nome, pratica_indicador_unidade, pratica_indicador_acumulacao, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_agrupar');

$sql->adCampo('pratica_indicador_requisito_descricao, pratica_indicador_requisito_oque, pratica_indicador_requisito_onde, pratica_indicador_requisito_quando, pratica_indicador_requisito_como, pratica_indicador_requisito_porque,
	pratica_indicador_requisito_quanto, pratica_indicador_requisito_quem, pratica_indicador_requisito_melhorias');

if ($usuario_id) {
	$sql->esqUnir('pratica_indicador_usuarios','pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->adOnde('pratica_indicador_responsavel IN ('.$usuario_id.') OR pratica_indicador_usuarios.usuario_id IN ('.$usuario_id.')');
	}
if ($Aplic->profissional){
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	if ($tarefa_id) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
	elseif ($plano_acao_id) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);
	}	
else{
	if ($tarefa_id) $sql->adOnde('pratica_indicador_tarefa = '.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('pratica_indicador_projeto = '.(int)$projeto_id);
	elseif ($pratica_id) $sql->adOnde('pratica_indicador_pratica = '.(int)$pratica_id);
	elseif ($plano_acao_id) $sql->adOnde('pratica_indicador_acao = '.(int)$plano_acao_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('pratica_indicador_perspectiva = '.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('pratica_indicador_tema = '.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('pratica_indicador_objetivo_estrategico = '.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('pratica_indicador_fator = '.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('pratica_indicador_estrategia = '.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('pratica_indicador_meta = '.(int)$pg_meta_id);
	}

$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$indicadores = $sql->lista();
$sql->limpar();

$detalhe_projeto=1;

if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';
$ata_ativo=$Aplic->modulo_ativo('atas');
if ($ata_ativo) require_once BASE_DIR.'/modulos/atas/funcoes.php';
$swot_ativo=$Aplic->modulo_ativo('swot');
if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
$operativo_ativo=$Aplic->modulo_ativo('operativo');
if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
$problema_ativo=$Aplic->modulo_ativo('problema');
if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');
if($agrupamento_ativo) require_once BASE_DIR.'/modulos/agrupamento/funcoes.php';
$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');
if($patrocinador_ativo) require_once BASE_DIR.'/modulos/patrocinadores/patrocinadores.class.php';
$tr_ativo=$Aplic->modulo_ativo('tr');



include_once BASE_DIR.'/modulos/praticas/indicadores_ver_idx.php';