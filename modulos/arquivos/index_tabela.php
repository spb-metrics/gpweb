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

global $tab, $dialogo, $estilo_interface, $arquivo_pasta, $usuario_id, $arquivo_upload, $arquivo_participante, $pesquisar_texto, $arquivo_categoria, $lista_depts, $lista_cias, $Aplic, $podeEditar, $negar1, $podeAcessar, $podeAdmin, $perms, $cia_id, $dept_id,$m, $a,$filtro_prioridade_arquivo, $arquivo_pasta_id,
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
	$arquivo_usuario,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;	

echo '<form name="frm_arquivos" id="frm_arquivos" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';

$ordenar = getParam($_REQUEST, 'ordenar', 'arquivo_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

include_once BASE_DIR.'/modulos/arquivos/arquivos.class.php';

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

$pagina = getParam($_REQUEST, 'pagina', 1);
$xtamanhoPagina = ($dialogo ? 90000 : $config['qnt_arquivos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 


$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$arquivo_tipos = getSisValor('TipoArquivo');

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


$sql = new BDConsulta;
$sql->adTabela('arquivos', 'arquivos');
$sql->adCampo('count(DISTINCT arquivo_id)');
if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta='.(int)$arquivo_pasta_id);
if ($filtro_prioridade_arquivo){
		$sql->esqUnir('priorizacao', 'priorizacao', 'arquivos.arquivo_id=priorizacao_arquivo');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_arquivo.')');
		}
if ($Aplic->profissional){
	$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivos.arquivo_id');
	if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('arquivo_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('arquivo_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('arquivo_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('arquivo_gestao_template='.(int)$template_id);	
	elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('arquivo_gestao_me='.(int)$me_id);
	
	else if ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	}
else {
	if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
	else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
	else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
	else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
	else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
	else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
	else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
	else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_objetivo = '.(int)$pg_objetivo_estrategico_id);
	else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
	else if ($pg_fator_critico_id) $sql->adOnde('arquivo_fator = '.(int)$pg_fator_critico_id);
	else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
	else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
	else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
	else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
	else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
	else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
	else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
	//else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
	}

if ($arquivo_pasta) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta);
if ($arquivo_categoria > -1) $sql->adOnde('arquivo_categoria = '.(int)$arquivo_categoria);

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('arquivo_dept','arquivo_dept', 'arquivo_dept.arquivo_dept_arquivo=arquivos.arquivo_id');
	$sql->adOnde('arquivo_dept='.(int)$dept_id.' OR arquivo_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('arquivo_dept','arquivo_dept', 'arquivo_dept.arquivo_dept_arquivo=arquivos.arquivo_id');
	$sql->adOnde('arquivo_dept IN ('.$lista_depts.') OR arquivo_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('arquivo_cia', 'arquivo_cia', 'arquivos.arquivo_id=arquivo_cia_arquivo');
	$sql->adOnde('arquivo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR arquivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('arquivo_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('arquivo_cia IN ('.$lista_cias.')');	
	
if ($pesquisar_texto) $sql->adOnde('arquivo_nome LIKE \'%'.$pesquisar_texto.'%\' OR arquivo_descricao LIKE \'%'.$pesquisar_texto.'%\'');	
if ($tab==0 || $tab==3) $sql->adOnde('arquivo_ativo=1');
elseif ($tab==1) $sql->adOnde('arquivo_ativo!=1 OR arquivo_ativo IS NULL');	
if ($usuario_id) {
	$sql->esqUnir('arquivo_usuario', 'arquivo_usuario', 'arquivo_usuario_arquivo = arquivos.arquivo_id');
	$sql->adOnde('arquivo_dono = '.(int)$usuario_id.' OR arquivo_usuario_usuario = '.(int)$arquivo_participante);
	}
$xtotalregistros=$sql->resultado();
$sql->Limpar();	



$arquivos = array();
$arquivo_versoes = array();

$sql->adTabela('arquivos');
$sql->esqUnir('arquivo_pasta', 'arquivo_pasta', 'arquivo_pasta_id = arquivo_pasta');
$sql->adCampo('DISTINCT arquivo_id, arquivo_acesso, arquivo_nome, arquivo_tipo, arquivo_categoria, arquivo_descricao, arquivo_versao, arquivo_dono, arquivo_usuario_upload, arquivo_tamanho, arquivo_data, arquivo_pasta_nome, arquivo_pasta_id');
if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta='.(int)$arquivo_pasta_id);
if (!$Aplic->profissional) $sql->adCampo('arquivo_projeto, arquivo_tarefa, arquivo_pratica, arquivo_acao, arquivo_indicador, arquivo_objetivo, arquivo_perspectiva, arquivo_tema, arquivo_fator, arquivo_estrategia, arquivo_meta, arquivo_demanda, arquivo_instrumento, arquivo_calendario, arquivo_ata, arquivo_canvas, arquivo_usuario');

$sql->adCampo('(SELECT count(arquivo_saida_id) FROM arquivo_saida WHERE arquivo_saida_arquivo=arquivos.arquivo_id) AS saida');
if ($filtro_prioridade_arquivo){
		$sql->esqUnir('priorizacao', 'priorizacao', 'arquivos.arquivo_id=priorizacao_arquivo');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_arquivo = arquivos.arquivo_id AND priorizacao_modelo IN ('.$filtro_prioridade_arquivo.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_arquivo = arquivos.arquivo_id AND priorizacao_modelo IN ('.$filtro_prioridade_arquivo.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_arquivo.')');
		}
if ($Aplic->profissional){
	$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivos.arquivo_id');
	if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('arquivo_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('arquivo_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('arquivo_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('arquivo_gestao_template='.(int)$template_id);	
	elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('arquivo_gestao_me='.(int)$me_id);
	
	else if ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	}
else {
	if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
	else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
	else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
	else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
	else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
	else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
	else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
	else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_objetivo = '.(int)$pg_objetivo_estrategico_id);
	else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
	else if ($pg_fator_critico_id) $sql->adOnde('arquivo_fator = '.(int)$pg_fator_critico_id);
	else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
	else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
	else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
	else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
	else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
	else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
	else if ($arquivo_usuario) $sql->adOnde('arquivos.arquivo_usuario = '.(int)$Aplic->usuario_id);
	//else $sql->adOnde('arquivos.arquivo_usuario=0 OR arquivos.arquivo_usuario IS NULL OR arquivos.arquivo_usuario = '.(int)$Aplic->usuario_id);
	}
if ($arquivo_pasta) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta);
if ($arquivo_categoria > -1) $sql->adOnde('arquivo_categoria = '.(int)$arquivo_categoria);

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('arquivo_dept','arquivo_dept', 'arquivo_dept.arquivo_dept_arquivo=arquivos.arquivo_id');
	$sql->adOnde('arquivo_dept='.(int)$dept_id.' OR arquivo_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('arquivo_dept','arquivo_dept', 'arquivo_dept.arquivo_dept_arquivo=arquivos.arquivo_id');
	$sql->adOnde('arquivo_dept IN ('.$lista_depts.') OR arquivo_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('arquivo_cia', 'arquivo_cia', 'arquivos.arquivo_id=arquivo_cia_arquivo');
	$sql->adOnde('arquivo_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR arquivo_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('arquivo_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('arquivo_cia IN ('.$lista_cias.')');	

if ($pesquisar_texto) $sql->adOnde('arquivo_nome LIKE \'%'.$pesquisar_texto.'%\' OR arquivo_descricao LIKE \'%'.$pesquisar_texto.'%\'');	
if ($tab==0 || $tab==3) $sql->adOnde('arquivo_ativo=1');
elseif ($tab==1) $sql->adOnde('arquivo_ativo!=1 OR arquivo_ativo IS NULL');	
if ($usuario_id) {
	$sql->esqUnir('arquivo_usuario', 'arquivo_usuario', 'arquivo_usuario_arquivo = arquivos.arquivo_id');
	$sql->adOnde('arquivo_dono = '.(int)$usuario_id.' OR arquivo_usuario_usuario = '.(int)$arquivo_participante);
	}
$sql->setLimite($xmin, $xtamanhoPagina);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$arquivos = $sql->Lista();
$sql->Limpar();


$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Arquivo', 'Arquivos','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
	echo '<th width=16">&nbsp;</th>';
	
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome', 'Clique para ordenar pelo nome dos arquivos.<br><br>Todo arquivo enviado para o Sistema deverá ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').($ordenar=='arquivo_nome' ? imagem('icones/'.$seta[$ordem]) : '').'Nome do Arquivo'.dicaF().'</a></th>';
	if ($filtro_prioridade_arquivo) echo '<th><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordem]) : '').'Priorização'.dicaF().'</a></th>';

	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Descrição', 'Clique para ordenar pela descrição dos arquivos.<br><br>Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreensão do rquivo e facilitar futuras pesquisas.').($ordenar=='arquivo_descricao' ? imagem('icones/'.$seta[$ordem]) : '').'Descrição'.dicaF().'</th>';
	echo '<th>'.dica('Relacionado', 'A área da gestão a qual o arquivo está relacionado .').'Relacionado'.dicaF().'</th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_versao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Versão', 'Clique para ordenar pela versão dos arquivos').($ordenar=='arquivo_versao' ? imagem('icones/'.$seta[$ordem]) : '').'Versão'.dicaF().'</th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_categoria&ordem='.($ordem ? '0' : '1').'\');">'.dica('Categoria do Arquivo', 'Clique para ordenar pela categoria dos arquivos.<br><br>Os arquivos podem ser :<ul><li>Documento - normalmente textos e imagens.</li><li>Arquivos - normalmente aplicativos executaveis.</li></ul>').($ordenar=='arquivo_categoria' ? imagem('icones/'.$seta[$ordem]) : '').'Categoria'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_pasta&ordem='.($ordem ? '0' : '1').'\');">'.dica('Pasta', 'Clique para ordenar pela pasta onde está armazenado o arquivo').($ordenar=='arquivo_pasta' ? imagem('icones/'.$seta[$ordem]) : '').'Pasta'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_dono&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Clique para ordenar pelo nome d'.$config['genero_usuario'].'s '.$config['usuarios'].'responsáveis').($ordenar=='arquivo_dono' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_usuario_upload&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável upload', 'Clique para ordenar pelo nome d'.$config['genero_usuario'].'s '.$config['usuarios'].' que enviaram os arquivos').($ordenar=='arquivo_usuario_upload' ? imagem('icones/'.$seta[$ordem]) : '').'Responsável Upload'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_tamanho&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tamanho', 'Clique para ordenar pelo tamanho dos arquivos.<br><br>O tamanho do arquivo é em bytes').($ordenar=='arquivo_tamanho' ? imagem('icones/'.$seta[$ordem]) : '').'Tamanho'.dicaF().'</a></th>';
	echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&ordenar=arquivo_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data de Inclusão', 'Clique para ordenar pela data em que os arquivos foram inseridos no Sistema.').($ordenar=='arquivo_data' ? imagem('icones/'.$seta[$ordem]) : '').'Data Inclusão'.dicaF().'</a></th>';
	echo '<th width=16>'.dica('Histórico', 'Os arquivos que tiverem um histórico de retiradas apresentarão o ícone '.imagem('icones/info.gif').' que mostrará um sumário das retiradas.').'H'.dicaF().'</th>';
	echo '<th>'.dica('Saída de Arquivos', 'Quando um arquivo lhe for destinado, ao inves de clicar no nome do arquivo para fazer o <i>download</i>, utilize o botão '.imagem('icones/acima.png').' para ficar registrado no sistema que já o retirou.').'S'.dicaF().'</th>';
	echo '<th>&nbsp;</th>';
echo '</tr>';

$ultimo = null;

$id = 0;
$qnt=0;

foreach ($arquivos as $linha) {

	if (permiteAcessarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])){	
		$qnt++;
		$editar=($podeEditar && permiteEditarArquivo($linha['arquivo_acesso'], $linha['arquivo_id']));

		echo '<tr>';
		echo '<td align="center">'.($editar ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&arquivo_id='.$linha['arquivo_id'].'\');">'.imagem('icones/editar.gif', 'Editar Arquivo', 'Ao clicar neste ícone '.imagem('icones/editar.gif').' será possivel editar o arquivo.').'</a>' :'&nbsp;').'</td>';
		
		//nome
		echo '<td>';
		$fnomeTamanho = 32;
		$nomeArquivo = $linha['arquivo_nome'];
		if (strlen($linha['arquivo_nome']) > $fnomeTamanho + 9) {
			$ext = substr($nomeArquivo, strrpos($nomeArquivo, '.') + 1);
			$nomeArquivo = substr($nomeArquivo, 0, $fnomeTamanho);
			$nomeArquivo .= '[...].'.$ext;
			}
		$arquivo_icone = getIcone($linha['arquivo_tipo']);
		echo dica($nomeArquivo, $linha['arquivo_descricao']).'<a href="./codigo/arquivo_visualizar.php?arquivo_id='.$linha['arquivo_id'].'"><img border=0 width="16" heigth="16" src="'.acharImagem($arquivo_icone).'" />&nbsp;'.$nomeArquivo.dicaF().'</a></td>';
		
		
		if ($filtro_prioridade_arquivo) echo '<td align="right" width=50>'.($linha['priorizacao']).'</td>';

		//descrição
		echo '<td>'.($linha['arquivo_descricao'] ? $linha['arquivo_descricao'] : '&nbsp;').'</td>';
		
		if ($Aplic->profissional){
			echo '<td align="left">';
			$sql->adTabela('arquivo_gestao');
			$sql->adCampo('arquivo_gestao.*');
			$sql->adOnde('arquivo_gestao_arquivo ='.(int)$linha['arquivo_id']);
			$sql->adOrdem('arquivo_gestao_ordem');	
			$lista = $sql->Lista();
			$sql->Limpar();
			$qnt_gestao=0;
			if (count($lista)){	
				foreach($lista as $gestao_data){	
					if ($gestao_data['arquivo_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['arquivo_gestao_tarefa']);
					elseif ($gestao_data['arquivo_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['arquivo_gestao_projeto']);
					elseif ($gestao_data['arquivo_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['arquivo_gestao_pratica']);
					elseif ($gestao_data['arquivo_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['arquivo_gestao_acao']);
					elseif ($gestao_data['arquivo_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['arquivo_gestao_perspectiva']);
					elseif ($gestao_data['arquivo_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['arquivo_gestao_tema']);
					elseif ($gestao_data['arquivo_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['arquivo_gestao_objetivo']);
					elseif ($gestao_data['arquivo_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['arquivo_gestao_fator']);
					elseif ($gestao_data['arquivo_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['arquivo_gestao_estrategia']);
					elseif ($gestao_data['arquivo_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['arquivo_gestao_meta']);
					elseif ($gestao_data['arquivo_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['arquivo_gestao_canvas']);
					elseif ($gestao_data['arquivo_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['arquivo_gestao_risco']);
					elseif ($gestao_data['arquivo_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['arquivo_gestao_risco_resposta']);
					elseif ($gestao_data['arquivo_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['arquivo_gestao_indicador']);
					elseif ($gestao_data['arquivo_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['arquivo_gestao_calendario']);
					elseif ($gestao_data['arquivo_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['arquivo_gestao_monitoramento']);
					elseif ($gestao_data['arquivo_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['arquivo_gestao_ata']);
					elseif ($gestao_data['arquivo_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['arquivo_gestao_swot']);
					elseif ($gestao_data['arquivo_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['arquivo_gestao_operativo']);
					elseif ($gestao_data['arquivo_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['arquivo_gestao_instrumento']);
					elseif ($gestao_data['arquivo_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['arquivo_gestao_recurso']);
					elseif ($gestao_data['arquivo_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['arquivo_gestao_problema']);
					elseif ($gestao_data['arquivo_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['arquivo_gestao_demanda']);
					elseif ($gestao_data['arquivo_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['arquivo_gestao_programa']);
					elseif ($gestao_data['arquivo_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['arquivo_gestao_licao']);
					elseif ($gestao_data['arquivo_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['arquivo_gestao_evento']);
					elseif ($gestao_data['arquivo_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['arquivo_gestao_link']);
					elseif ($gestao_data['arquivo_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['arquivo_gestao_avaliacao']);
					elseif ($gestao_data['arquivo_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['arquivo_gestao_tgn']);
					elseif ($gestao_data['arquivo_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['arquivo_gestao_brainstorm']);
					elseif ($gestao_data['arquivo_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['arquivo_gestao_gut']);
					elseif ($gestao_data['arquivo_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['arquivo_gestao_causa_efeito']);
					elseif ($gestao_data['arquivo_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['arquivo_gestao_forum']);
					elseif ($gestao_data['arquivo_gestao_checklist']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['arquivo_gestao_checklist']);
					elseif ($gestao_data['arquivo_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['arquivo_gestao_agenda']);
					elseif ($gestao_data['arquivo_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['arquivo_gestao_agrupamento']);
					elseif ($gestao_data['arquivo_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['arquivo_gestao_patrocinador']);
					elseif ($gestao_data['arquivo_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['arquivo_gestao_template']);
					elseif ($gestao_data['arquivo_gestao_usuario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/usuario_p.gif').link_usuario($gestao_data['arquivo_gestao_usuario']);
					elseif ($gestao_data['arquivo_gestao_painel']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['arquivo_gestao_painel']);
					elseif ($gestao_data['arquivo_gestao_painel_odometro']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['arquivo_gestao_painel_odometro']);
					elseif ($gestao_data['arquivo_gestao_painel_composicao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['arquivo_gestao_painel_composicao']);
					elseif ($gestao_data['arquivo_gestao_tr']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['arquivo_gestao_tr']);
					elseif ($gestao_data['arquivo_gestao_me']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['arquivo_gestao_me']);
					}
				}	
			echo '</td>';
			}
		else {	
			echo '<td>';
			$qnt_gestao=0;
			if ($linha['arquivo_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($linha['arquivo_tarefa']);
			else if ($linha['arquivo_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($linha['arquivo_projeto']);
			if ($linha['arquivo_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($linha['arquivo_perspectiva']);
			if ($linha['arquivo_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($linha['arquivo_tema']);
			if ($linha['arquivo_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($linha['arquivo_meta']);
			if ($linha['arquivo_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($linha['arquivo_acao']);
			if ($linha['arquivo_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($linha['arquivo_fator']);
			if ($linha['arquivo_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($linha['arquivo_objetivo']);
			if ($linha['arquivo_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($linha['arquivo_pratica']);
			if ($linha['arquivo_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($linha['arquivo_estrategia']);
			if ($linha['arquivo_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($linha['arquivo_indicador']);
			if ($linha['arquivo_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($linha['arquivo_canvas']);
			if ($linha['arquivo_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($linha['arquivo_calendario']);
			if ($linha['arquivo_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($linha['arquivo_demanda']);
			if ($linha['arquivo_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($linha['arquivo_instrumento']);
			if ($linha['arquivo_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/ata_p.png').link_ata($linha['arquivo_ata']);
			if ($linha['arquivo_usuario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/usuario_p.gif').'Particular';
			echo '</td>';
			}
		
		echo '<td align="right">'.number_format($linha['arquivo_versao'], 2, ',', '.').'</td>';
		echo '<td align="left">'.(isset($arquivo_tipos[$linha['arquivo_categoria']]) ? $arquivo_tipos[$linha['arquivo_categoria']] : '&nbsp;').'</td> ';
		echo '<td align="left">'.($linha['arquivo_pasta_nome'] ? dica('Abrir Pasta', 'Clique neste ícone '.imagem('icones/pasta.png').' para abrir esta pasta e visualizar quais arquivos a mesma contêm.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&tab=3&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');">'.imagem('icones/pasta.png').$linha['arquivo_pasta_nome'].'</a>'.dicaF() : 'Raiz').'</td>';
		echo '<td>'.link_usuario($linha['arquivo_dono'],'','','esquerda').'</td>';
		echo '<td>'.link_usuario($linha['arquivo_usuario_upload'],'','','esquerda').'</td>';
		echo '<td align="right">'.arquivo_tamanho(intval($linha["arquivo_tamanho"])).'</td>';
		echo '<td align="center">'.retorna_data($linha['arquivo_data']).'</td>';
		
		
		echo '<td>'.($linha['saida'] ? '<a href="javascript:void(0);" onclick="ver_historico('.$linha['arquivo_id'].')">'.imagem('icones/info.gif', 'Histórico', 'Ao clicar neste ícone será possivel ler o histórico de saída do arquivo.').'</a>' : '&nbsp;').'</td>';
		echo '<td align="center">';
	 	echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=saida&arquivo_id='.$linha['arquivo_id'].'\');">'.imagem('icones/acima.png', 'Marcar Saída Arquivo', 'Registre que retirou o arquivo que estava na caixa de entrada').'</a>';
		echo '</td>';
		echo '<td width="16" align="center">';
		echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=ver&arquivo_id='.$linha['arquivo_id'].'\');">'.imagem('icones/gnome-mime-application-vnd.ms-powerpoint.png', 'Ver Detalhes', 'Ao clicar neste ícone '.imagem('icones/gnome-mime-application-vnd.ms-powerpoint.png').' será possivel visualizar o detalhamento do arquivo.').'</a>';
		
		echo '</td></tr>';
		}
	} 
if (!count($arquivos)) echo '<tr><td colspan="13"><p>Nenhum arquivo encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="13"><p>Não tem autorização para visualizar nenhum dos arquivos.</p></td></tr>';			
echo '</table>';

echo '</form>';
?>

<script type="text/JavaScript">


function ver_historico(arquivo_id){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Histórico de Retirada', 800, 600, 'm=arquivos&a=ver_historico_pro&arquivo_id='+arquivo_id, null, window);
	else window.open('./index.php?m=arquivos&a=ver_historico_pro&arquivo_id='+arquivo_id, 'Histórico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

	
function numero_arquivos_selecionado(){
	var f = eval('document.frm_arquivos');
	var qnt=0;
	for (var i=0, i_cmp=f.elements.length; i<i_cmp; i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked && e.name.substring(0, 13)=='vetor_arquivo') qnt++;
		}
	return qnt; 	
	}	
	
function string_arquivos_selecionado(){
	var f = eval('document.frm_arquivos');
	var saida='';
	for (var i=0, i_cmp=f.elements.length; i<i_cmp; i++) {
		var e = f.elements[i];
		if(e.type == 'checkbox' && e.checked && e.name.substring(0, 13)=='vetor_arquivo') saida+=(saida ? ',' :'')+e.value;
		}
	return saida; 	
	}		
	
	
function clonar_arquivos(){
	if (numero_arquivos_selecionado() == 0) alert('Selecione ao menos um arquivo');
	else {
		var arquivos=string_arquivos_selecionado();
		parent.gpwebApp.popUp('Seleção de repositório dos arquivos selecionados', 500, 500, 'm=arquivos&a=selecionar_clone_pro&dialogo=1&arquivos='+arquivos, window.setClonar_arquivos, window);
		}
	}		
	
function marca_sel_todas() {
  with(document.getElementById('frm_arquivos')) {
	  for(i=0;i<elements.length;i++) {
			thiselm = elements[i];
			thiselm.checked = !thiselm.checked
      }
    }
  }	
	
function expandir(id){
  var elemento = document.getElementById(id);
  elemento.style.display = (elemento.style.display == 'none') ? '' : 'none';
	}
</script>
