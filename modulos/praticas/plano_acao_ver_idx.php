<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $Aplic, $cia_id, $tab, $favorito_id, $dialogo, $usuario_id, $pesquisar_texto, $lista_cias, $dept_id, $lista_depts, $plano_acao_ano, $filtro_prioridade_acao,
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


if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';
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

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_plano_acao']);
$xmin = $xtamanhoPagina * ($pagina - 1); 
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$ordenar = getParam($_REQUEST, 'ordenar', 'plano_acao_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
if ($lista_depts) $lista_depts=(is_array($lista_depts) ? implode(',', $lista_depts) : $lista_depts);
if ($lista_cias) $lista_cias=(is_array($lista_cias) ? implode(',', $lista_cias) : $lista_cias);

$sql = new BDConsulta;
$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = "planos"');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



$sql->adTabela('plano_acao');
$sql->adCampo('count(plano_acao.plano_acao_id) as soma');
if ($filtro_prioridade_acao){
	$sql->esqUnir('priorizacao', 'priorizacao', 'plano_acao.plano_acao_id=priorizacao_acao');
	$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_acao.')');
	}
	
if ($a=='plano_acao_lista' && $favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'plano_acao.plano_acao_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('plano_acao_depts','plano_acao_depts', 'plano_acao_depts.plano_acao_id=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('plano_acao_depts','plano_acao_depts', 'plano_acao_depts.plano_acao_id=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept IN ('.$lista_depts.') OR plano_acao_depts.dept_id IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
	$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');

if ($plano_acao_ano) $sql->adOnde('plano_acao_ano = "'.$plano_acao_ano.'"');

if ($Aplic->profissional){
	$sql->esqUnir('plano_acao_gestao', 'plano_acao_gestao', 'plano_acao.plano_acao_id = plano_acao_gestao_acao');
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
	elseif ($evento_id) $sql->adOnde('plano_acao_gestao_evento='.(int)$evento_id);
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
	elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('plano_acao_gestao_me='.(int)$me_id);
	}
else{
	if ($pratica_id) $sql->adOnde('plano_acao_pratica = '.(int)$pratica_id);
	if ($pratica_indicador_id) $sql->adOnde('plano_acao_indicador = '.(int)$pratica_indicador_id);
	if ($tema_id) $sql->adOnde('plano_acao_tema = '.(int)$tema_id);
	if ($pg_objetivo_estrategico_id) $sql->adOnde('plano_acao_objetivo = '.(int)$pg_objetivo_estrategico_id);
	if ($pg_estrategia_id) $sql->adOnde('plano_acao_estrategia = '.(int)$pg_estrategia_id);
	if ($projeto_id) $sql->adOnde('plano_acao_projeto = '.(int)$projeto_id);
	if ($tarefa_id) $sql->adOnde('plano_acao_tarefa = '.(int)$tarefa_id);
	if ($pg_fator_critico_id) $sql->adOnde('plano_acao_fator = '.(int)$pg_fator_critico_id);
	if ($pg_meta_id) $sql->adOnde('plano_acao_meta = '.(int)$pg_meta_id);
	if ($pg_perspectiva_id) $sql->adOnde('plano_acao_perspectiva = '.(int)$pg_perspectiva_id);
	if ($canvas_id) $sql->adOnde('plano_acao_canvas = '.(int)$canvas_id);
	}
if ($usuario_id) {
	$sql->esqUnir('plano_acao_usuarios', 'plano_acao_usuarios', 'plano_acao_usuarios.plano_acao_id = plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_responsavel = '.(int)$usuario_id.' OR plano_acao_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($a=='plano_acao_lista'){
	if ($tab==0) $sql->adOnde('plano_acao_percentagem < 100');
	if ($tab==1) $sql->adOnde('plano_acao_percentagem = 100');
	
	if ($tab==2) $sql->adOnde('plano_acao_ativo = 0');
	else $sql->adOnde('plano_acao_ativo = 1');
	}	
elseif ($a=='parafazer'){
	$sql->adOnde('plano_acao_percentagem < 100');
	$sql->adOnde('plano_acao_ativo = 1');
	}	
	
		
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('plano_acao');
$sql->esqUnir('plano_acao_item', 'plano_acao_item','plano_acao_item_acao=plano_acao.plano_acao_id');
if ($filtro_prioridade_acao){
		$sql->esqUnir('priorizacao', 'priorizacao', 'plano_acao.plano_acao_id=priorizacao_acao');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_acao = plano_acao.plano_acao_id AND priorizacao_modelo IN ('.$filtro_prioridade_acao.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_acao = plano_acao.plano_acao_id AND priorizacao_modelo IN ('.$filtro_prioridade_acao.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_acao.')');
		}
$sql->adCampo('plano_acao.plano_acao_id, plano_acao_inicio, plano_acao_fim, plano_acao_percentagem, plano_acao_nome, plano_acao_descricao, plano_acao_responsavel, plano_acao_cor, plano_acao_acesso, count(plano_acao_item_id) AS qnt, plano_acao_ano, plano_acao_codigo, plano_acao_cia');

if ($a=='plano_acao_lista' && $favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'plano_acao.plano_acao_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('plano_acao_depts','plano_acao_depts', 'plano_acao_depts.plano_acao_id=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept='.(int)$dept_id.' OR plano_acao_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('plano_acao_depts','plano_acao_depts', 'plano_acao_depts.plano_acao_id=plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_dept IN ('.$lista_depts.') OR plano_acao_depts.dept_id IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('plano_acao_cia', 'plano_acao_cia', 'plano_acao.plano_acao_id=plano_acao_cia_plano_acao');
	$sql->adOnde('plano_acao_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR plano_acao_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('plano_acao_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('plano_acao_cia IN ('.$lista_cias.')');

if ($Aplic->profissional){
	$sql->esqUnir('plano_acao_gestao', 'plano_acao_gestao', 'plano_acao.plano_acao_id = plano_acao_gestao_acao');
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
	elseif ($evento_id) $sql->adOnde('plano_acao_gestao_evento='.(int)$evento_id);
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
	elseif ($painel_id) $sql->adOnde('plano_acao_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('plano_acao_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('plano_acao_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('plano_acao_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('plano_acao_gestao_me='.(int)$me_id);
	}
else{
	if ($pratica_id) $sql->adOnde('plano_acao_pratica = '.(int)$pratica_id);
	if ($pratica_indicador_id) $sql->adOnde('plano_acao_indicador = '.(int)$pratica_indicador_id);
	if ($tema_id) $sql->adOnde('plano_acao_tema = '.(int)$tema_id);
	if ($pg_objetivo_estrategico_id) $sql->adOnde('plano_acao_objetivo = '.(int)$pg_objetivo_estrategico_id);
	if ($pg_estrategia_id) $sql->adOnde('plano_acao_estrategia = '.(int)$pg_estrategia_id);
	if ($projeto_id) $sql->adOnde('plano_acao_projeto = '.(int)$projeto_id);
	if ($tarefa_id) $sql->adOnde('plano_acao_tarefa = '.(int)$tarefa_id);
	if ($pg_fator_critico_id) $sql->adOnde('plano_acao_fator = '.(int)$pg_fator_critico_id);
	if ($pg_meta_id) $sql->adOnde('plano_acao_meta = '.(int)$pg_meta_id);
	if ($pg_perspectiva_id) $sql->adOnde('plano_acao_perspectiva = '.(int)$pg_perspectiva_id);
	if ($canvas_id) $sql->adOnde('plano_acao_canvas = '.(int)$canvas_id);
	}

if ($usuario_id) {
	$sql->esqUnir('plano_acao_usuarios', 'plano_acao_usuarios', 'plano_acao_usuarios.plano_acao_id = plano_acao.plano_acao_id');
	$sql->adOnde('plano_acao_responsavel = '.(int)$usuario_id.' OR plano_acao_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('plano_acao_nome LIKE \'%'.$pesquisar_texto.'%\' OR plano_acao_descricao LIKE \'%'.$pesquisar_texto.'%\' OR plano_acao_codigo LIKE \'%'.$pesquisar_texto.'%\'');
if ($a=='plano_acao_lista'){
	if ($tab==0) $sql->adOnde('plano_acao_percentagem < 100');
	if ($tab==1) $sql->adOnde('plano_acao_percentagem = 100');
	
	if ($tab==2) $sql->adOnde('plano_acao_ativo = 0');
	else $sql->adOnde('plano_acao_ativo = 1');
	}
elseif ($a=='parafazer'){
	$sql->adOnde('plano_acao_percentagem < 100');
	$sql->adOnde('plano_acao_ativo = 1');
	}		
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('plano_acao.plano_acao_id');
$sql->setLimite($xmin, $xtamanhoPagina);

$plano_acoes=$sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, ucfirst($config['acao']), ucfirst($config['acoes']),'','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="'.($dialogo ? '1100' : '100%').'" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
if ($exibir['cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identificação d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Nome'.dicaF().'</a></th>';

if ($filtro_prioridade_acao) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Priorização'.dicaF().'</a></th>';


if ($exibir['descricao']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Neste campo fica a descrição d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Descrição'.dicaF().'</a></th>';
if ($Aplic->profissional) echo '<th nowrap="nowrap">'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';

if ($exibir['responsavel']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_acao'].'s '.$config['acoes'].'.').'Responsável'.dicaF().'</a></th>';
if ($exibir['designados']) echo '<th nowrap="nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_acao'].'s '.$config['acoes'].'.').'Designados'.dicaF().'</th>';
if ($exibir['dept']) echo '<th nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Neste campo fica '.$config['genero_dept'].'s '.$config['departamentos'].' envolvid'.$config['genero_dept'].'s n'.$config['genero_acao'].'s '.$config['acoes'].'.').$config['dept'].dicaF().'</th>';
if ($exibir['inicio']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Início', 'A data de ínicio d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Início'.dicaF().'</a></th>';
if ($exibir['fim']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('Término', 'A data de término d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Término'.dicaF().'</a></th>';
if ($exibir['percentagem']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem executada n'.$config['genero_acao'].'s '.$config['acoes'].'.').'%'.dicaF().'</a></th>';
if ($exibir['linhas']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=qnt&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='qnt' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quantidade', 'A quantidade de linhas inseridas n'.$config['genero_acao'].'s '.$config['acoes'].'.').'Qnt'.dicaF().'</a></th>';
if ($exibir['cia_id']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_cia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_cia' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']), 'As '.$config['organizacoes'].' d'.$config['genero_acao'].'s '.$config['acoes'].'.').$config['organizacao'].dicaF().'</a></th>';
if ($exibir['ano']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_ano&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_ano' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ano', 'O ano base dos'.$config['genero_acao'].'s '.$config['acoes'].'.').'Ano'.dicaF().'</a></th>';
if ($exibir['codigo']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').($tarefa_id ? '&tarefa_id='.$tarefa_id : '').'&ordenar=plano_acao_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='plano_acao_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Código', 'Os códigos d'.$config['genero_acao'].'s '.$config['acoes'].'.').'Código'.dicaF().'</a></th>';
echo '</tr>';
$qnt=0;
$agora =date('Y-m-d');


foreach ($plano_acoes as $linha) {
	if (permiteAcessarPlanoAcao($linha['plano_acao_acesso'],$linha['plano_acao_id'])){
		$editar=permiteEditarPlanoAcao($linha['plano_acao_acesso'],$linha['plano_acao_id']);
		$qnt++;
		$estilo ='';
		if($linha['plano_acao_inicio'] && $linha['plano_acao_fim']){
			if ($agora < $linha['plano_acao_inicio'] && $linha['plano_acao_percentagem'] < 100) $estilo = 'style="background-color:#ffffff"';
			if ($agora > $linha['plano_acao_inicio'] && $agora < $linha['plano_acao_fim'] && $linha['plano_acao_percentagem'] > 0 && $linha['plano_acao_percentagem'] < 100 ) $estilo = 'style="background-color:#e6eedd"';
			if ($agora > $linha['plano_acao_inicio'] && $agora < $linha['plano_acao_fim'] && $linha['plano_acao_percentagem'] == 0) $estilo = 'style="background-color:#ffeebb"';
			if ($agora > $linha['plano_acao_fim'] && $linha['plano_acao_percentagem'] < 100) $estilo = 'style="background-color:#cc6666"';
			elseif ($linha['plano_acao_percentagem'] == 100) $estilo = 'style="background-color:#aaddaa"';
			}

		echo '<tr>';
		if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['acao']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_acao'].'s '.$config['acoes'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_id='.$linha['plano_acao_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['plano_acao_cor'].'">&nbsp;&nbsp;</td>';
		echo '<td '.$estilo.' >'.link_acao($linha['plano_acao_id'],'','','','',true).'</a></td>';
		
		if ($filtro_prioridade_acao) echo '<td align="right" nowrap="nowrap" width=50>'.($linha['priorizacao']).'</td>';

		
		if ($exibir['descricao']) echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_descricao'] ? $linha['plano_acao_descricao']: '&nbsp;').'</td>';
		
		if ($Aplic->profissional) {
			echo '<td align="left">';
			$sql->adTabela('plano_acao_gestao');
			$sql->adCampo('plano_acao_gestao.*');
			$sql->adOnde('plano_acao_gestao_acao ='.(int)$linha['plano_acao_id']);
			$sql->adOrdem('plano_acao_gestao_ordem');	
			$lista = $sql->Lista();
			$sql->Limpar();
			$qnt_gestao=0;
			if (count($lista)){	
				foreach($lista as $gestao_data){	
					if ($gestao_data['plano_acao_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['plano_acao_gestao_tarefa']);
					elseif ($gestao_data['plano_acao_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['plano_acao_gestao_projeto']);
					elseif ($gestao_data['plano_acao_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['plano_acao_gestao_pratica']);
					elseif ($gestao_data['plano_acao_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['plano_acao_gestao_perspectiva']);
					elseif ($gestao_data['plano_acao_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['plano_acao_gestao_tema']);
					elseif ($gestao_data['plano_acao_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['plano_acao_gestao_objetivo']);
					elseif ($gestao_data['plano_acao_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['plano_acao_gestao_fator']);
					elseif ($gestao_data['plano_acao_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['plano_acao_gestao_estrategia']);
					elseif ($gestao_data['plano_acao_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['plano_acao_gestao_meta']);
					elseif ($gestao_data['plano_acao_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['plano_acao_gestao_canvas']);
					elseif ($gestao_data['plano_acao_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['plano_acao_gestao_risco']);
					elseif ($gestao_data['plano_acao_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['plano_acao_gestao_risco_resposta']);
					elseif ($gestao_data['plano_acao_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['plano_acao_gestao_indicador']);
					elseif ($gestao_data['plano_acao_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['plano_acao_gestao_calendario']);
					elseif ($gestao_data['plano_acao_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['plano_acao_gestao_monitoramento']);
					elseif ($gestao_data['plano_acao_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['plano_acao_gestao_ata']);
					elseif ($gestao_data['plano_acao_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['plano_acao_gestao_swot']);
					elseif ($gestao_data['plano_acao_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['plano_acao_gestao_operativo']);
					elseif ($gestao_data['plano_acao_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['plano_acao_gestao_instrumento']);
					elseif ($gestao_data['plano_acao_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['plano_acao_gestao_recurso']);
					elseif ($gestao_data['plano_acao_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['plano_acao_gestao_problema']);
					elseif ($gestao_data['plano_acao_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['plano_acao_gestao_demanda']);
					elseif ($gestao_data['plano_acao_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['plano_acao_gestao_programa']);
					elseif ($gestao_data['plano_acao_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['plano_acao_gestao_licao']);
					elseif ($gestao_data['plano_acao_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['plano_acao_gestao_evento']);
					elseif ($gestao_data['plano_acao_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['plano_acao_gestao_link']);
					elseif ($gestao_data['plano_acao_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['plano_acao_gestao_avaliacao']);
					elseif ($gestao_data['plano_acao_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['plano_acao_gestao_tgn']);
					elseif ($gestao_data['plano_acao_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['plano_acao_gestao_brainstorm']);
					elseif ($gestao_data['plano_acao_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['plano_acao_gestao_gut']);
					elseif ($gestao_data['plano_acao_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['plano_acao_gestao_causa_efeito']);
					elseif ($gestao_data['plano_acao_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['plano_acao_gestao_arquivo']);
					elseif ($gestao_data['plano_acao_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['plano_acao_gestao_forum']);
					elseif ($gestao_data['plano_acao_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['plano_acao_gestao_checklist']);
					elseif ($gestao_data['plano_acao_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['plano_acao_gestao_agenda']);
					elseif ($gestao_data['plano_acao_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['plano_acao_gestao_agrupamento']);
					elseif ($gestao_data['plano_acao_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['plano_acao_gestao_patrocinador']);
					elseif ($gestao_data['plano_acao_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['plano_acao_gestao_template']);
					elseif ($gestao_data['plano_acao_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['plano_acao_gestao_painel']);
					elseif ($gestao_data['plano_acao_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['plano_acao_gestao_painel_odometro']);
					elseif ($gestao_data['plano_acao_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['plano_acao_gestao_painel_composicao']);
					elseif ($gestao_data['plano_acao_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['plano_acao_gestao_tr']);
					elseif ($gestao_data['plano_acao_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['plano_acao_gestao_me']);
					}
				}	
			echo '</td>';
			}
		
		
		
		if ($exibir['responsavel']) echo '<td nowrap="nowrap">'.link_usuario($linha['plano_acao_responsavel'],'','','esquerda').'</td>';
		
		if ($exibir['designados']){ 
			$sql->adTabela('plano_acao_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('plano_acao_id = '.(int)$linha['plano_acao_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['plano_acao_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['plano_acao_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left" nowrap="nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
			
		if ($exibir['dept']){ 	
			$sql->adTabela('plano_acao_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('plano_acao_id = '.(int)$linha['plano_acao_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_dept='';
			if ($depts && count($depts)) {
					$saida_dept.= link_secao($depts[0]);
					$qnt_depts=count($depts);
					if ($qnt_depts > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';		
							$saida_dept.= dica('Outros Participantes', 'Clique para visualizar os demais depts.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'depts\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="depts"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left" nowrap="nowrap">'.($saida_dept ? $saida_dept : '&nbsp;').'</td>';
			}
			
		if ($exibir['inicio'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_inicio'] ? retorna_data($linha['plano_acao_inicio'], false): '&nbsp;').'</td>';
		if ($exibir['fim'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_fim'] ? retorna_data($linha['plano_acao_fim'], false): '&nbsp;').'</td>';
		if ($exibir['percentagem'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_percentagem'] ? number_format($linha['plano_acao_percentagem'], 1, ',', '.') : '&nbsp;').'</td>';
		if ($exibir['linhas'])echo '<td width="25" align=center>'.$linha['qnt'].'</td>';
		
		if ($exibir['cia_id'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_cia'] ? link_cia($linha['plano_acao_cia']): '&nbsp;').'</td>';
		if ($exibir['ano'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_ano'] ? $linha['plano_acao_ano'] : '&nbsp;').'</td>';
		if ($exibir['codigo'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['plano_acao_codigo'] ? $linha['plano_acao_codigo'] : '&nbsp;').'</td>';
		
		echo '</tr>';
		}
	}
if (!count($plano_acoes)) echo '<tr><td colspan="20"><p>Nenhum'.($config['genero_acao']=='a' ? 'a': '').' '.$config['acao'].' encontrad'.$config['genero_acao'].'.</p></td></tr>';
elseif(count($plano_acoes) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_acao'].'s '.$config['acoes'].'.</p></td></tr>';
echo '</table>';


echo '<table border=0 cellpadding=2 cellspacing=2 '.($dialogo ? '' : 'class="std"').' width="'.($dialogo ? '780' : '100%').'"><tr>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffffff;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['acao']).' Previsto', ucfirst($config['acao']).' a data de ínicio dd'.$config['genero_acao'].' mesm'.$config['genero_acao'].' ainda não passou.').'&nbsp;'.ucfirst($config['acao']).' para o futuro'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #e6eedd;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['acao']).' Iniciad'.$config['genero_acao'].' e Dentro do Prazo', ucfirst($config['acao']).' iniciad'.$config['genero_acao'].' e dentro do prazo é quando a data de ínicio d'.$config['genero_acao'].' mesm'.$config['genero_acao'].' já ocorreu, e '.$config['genero_acao'].' mesm'.$config['genero_acao'].' já está acima de 0% executad'.$config['genero_acao'].', entretanto ainda não se chegou na data de término.').'&nbsp;Iniciad'.$config['genero_acao'].' e dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffeebb;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['acao']).' que Deveria ter Iniciad'.$config['genero_acao'], ucfirst($config['acao']).' deveria ter iniciad'.$config['genero_acao'].' é quando a data de ínicio d'.$config['genero_acao'].' mesm'.$config['genero_acao'].' já ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_acao'].'.').'&nbsp;Deveria ter iniciad'.$config['genero_acao'].dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #cc6666;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['acao']).' em Atraso', ucfirst($config['acao']).' em atraso é quando a data de término d'.$config['genero_acao'].' mesm'.$config['genero_acao'].' já ocorreu, entretanto ainda não se encontra em 100% executad'.$config['genero_acao'].'.').'&nbsp;Em atraso'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #aaddaa;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['acao']).' Terminad'.$config['genero_acao'], ucfirst($config['acao']).' terminad'.$config['genero_acao'].' é quando está 100% executad'.$config['genero_acao'].'.').'&nbsp;Terminado'.dicaF().'</td>';
echo '<td width="100%">&nbsp;</td>';
echo '</tr></table>';


if ($dialogo) echo '<script language=Javascript>self.print();</script>';

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>	