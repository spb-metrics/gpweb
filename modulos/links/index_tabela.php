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

global $Aplic, $negar1, $podeAcessar, $podeEditar,  $dialogo, $estilo_interface, $usuario_id, $cia_id, $dept_id, $lista_depts, $lista_cias, $mostrarProjeto, $m, $tab,$filtro_prioridade_link,
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

$pagina = getParam($_REQUEST, 'pagina', 1);
$pesquisa = getParam($_REQUEST, 'search', '');

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

if (!isset($projeto_id)) $projeto_id = getParam($_REQUEST, 'projeto_id', 0);
if (!isset($pratica_indicador_id)) $pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', 0);
if (!isset($pratica_id)) $pratica_id = getParam($_REQUEST, 'pratica_id', 0);
if (!isset($mostrarProjeto)) $mostrarProjeto = true;

$xpg_tamanhoPagina = $config['qnt_links'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$ordenar = getParam($_REQUEST, 'ordenar', 'link_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');


$link_tipos = getSisValor('TipoLink');
$sql = new BDConsulta();

$sql->adTabela('links');

if ($usuario_id) {
	$sql->esqUnir('link_usuarios','link_usuarios','link_usuarios.link_id=links.link_id');	
	$sql->adOnde('link_dono = '.(int)$usuario_id.' OR link_usuarios.usuario_id='.(int)$usuario_id); 
	}
if ($filtro_prioridade_link){
		$sql->esqUnir('priorizacao', 'priorizacao', 'links.link_id=priorizacao_link');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_link.')');
		}
if ($Aplic->profissional){
	$sql->esqUnir('link_gestao','link_gestao','link_gestao_link = links.link_id');
	if ($tarefa_id) $sql->adOnde('link_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('link_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('link_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('link_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('link_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('link_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('link_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('link_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('link_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('link_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('link_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('link_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('link_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('link_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('link_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('link_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('link_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('link_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('link_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('link_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('link_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('link_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('link_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('link_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('link_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('link_gestao_evento='.(int)$evento_id);
	elseif ($avaliacao_id) $sql->adOnde('link_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('link_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('link_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('link_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('link_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('link_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('link_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('link_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('link_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('link_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('link_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('link_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('link_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('link_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('link_gestao_painel_composicao='.(int)$painel_composicao_id);	
	elseif ($tr_id) $sql->adOnde('link_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('link_gestao_me='.(int)$me_id);
	}
else {
	if ($tarefa_id) $sql->adOnde('link_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('link_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('link_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('link_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('link_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('link_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('link_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('link_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('link_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('link_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('link_acao='.(int)$plano_acao_id);
	}


if ($dept_id && !$lista_depts) {
	$sql->esqUnir('link_dept','link_dept', 'link_dept_link=links.link_id');
	$sql->adOnde('link_dept='.(int)$dept_id.' OR link_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('link_dept','link_dept', 'link_dept_link=links.link_id');
	$sql->adOnde('link_dept IN ('.$lista_depts.') OR link_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('link_cia', 'link_cia', 'links.link_id=link_cia_link');
	$sql->adOnde('link_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR link_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('link_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('link_cia IN ('.$lista_cias.')');	

if (!empty($pesquisa)) $sql->adOnde('(link_nome LIKE \'%'.$pesquisa.'%\' OR link_descricao LIKE \'%'.$pesquisa.'%\')');
if ($tab==0) $sql->adOnde('link_ativo=1');
$sql->adCampo('count(links.link_id)');
$xpg_totalregistros = $sql->resultado();
$sql->limpar();




$sql->adTabela('links');
if ($usuario_id) {
	$sql->esqUnir('link_usuarios','link_usuarios','link_usuarios.link_id=links.link_id');	
	$sql->adOnde('link_dono = '.(int)$usuario_id.' OR link_usuarios.usuario_id='.(int)$usuario_id); 
	} 
if ($filtro_prioridade_link){
		$sql->esqUnir('priorizacao', 'priorizacao', 'links.link_id=priorizacao_link');
		if ($config['metodo_priorizacao']) $sql->adCampo('(SELECT round(exp(sum(log(coalesce(priorizacao_valor,1))))) FROM priorizacao WHERE priorizacao_link = links.link_id AND priorizacao_modelo IN ('.$filtro_prioridade_link.')) AS priorizacao');
		else $sql->adCampo('(SELECT SUM(priorizacao_valor) FROM priorizacao WHERE priorizacao_link = links.link_id AND priorizacao_modelo IN ('.$filtro_prioridade_link.')) AS priorizacao');
		$sql->adOnde('priorizacao_modelo IN ('.$filtro_prioridade_link.')');
		}
if ($Aplic->profissional){
	$sql->esqUnir('link_gestao','link_gestao','link_gestao_link = links.link_id');
	if ($tarefa_id) $sql->adOnde('link_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('link_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('link_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('link_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('link_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('link_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('link_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('link_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('link_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('link_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('link_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('link_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('link_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('link_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('link_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('link_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('link_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('link_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('link_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('link_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('link_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('link_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('link_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('link_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('link_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('link_gestao_evento='.(int)$evento_id);
	elseif ($avaliacao_id) $sql->adOnde('link_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('link_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('link_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('link_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('link_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('link_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('link_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('link_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('link_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('link_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('link_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('link_gestao_template='.(int)$template_id);
	elseif ($painel_id) $sql->adOnde('link_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('link_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('link_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('link_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('link_gestao_me='.(int)$me_id);
	}
else {
	if ($tarefa_id) $sql->adOnde('link_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('link_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('link_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('link_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('link_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('link_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('link_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('link_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('link_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('link_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('link_acao='.(int)$plano_acao_id);
	}

if ($dept_id && !$lista_depts) {
	$sql->esqUnir('link_dept','link_dept', 'link_dept_link=links.link_id');
	$sql->adOnde('link_dept='.(int)$dept_id.' OR link_dept_dept='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('link_dept','link_dept', 'link_dept_link=links.link_id');
	$sql->adOnde('link_dept IN ('.$lista_depts.') OR link_dept_dept IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('link_cia', 'link_cia', 'links.link_id=link_cia_link');
	$sql->adOnde('link_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR link_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('link_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('link_cia IN ('.$lista_cias.')');	


if (!empty($pesquisa)) $sql->adOnde('(link_nome LIKE \'%'.$pesquisa.'%\' OR link_descricao LIKE \'%'.$pesquisa.'%\')');
if ($tab==0) $sql->adOnde('link_ativo=1');
elseif ($tab==1) $sql->adOnde('link_ativo!=1 OR link_ativo IS NULL');	
$sql->adCampo('DISTINCT link_id, link_nome, link_acesso, link_dono, link_descricao, link_categoria ');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));

$sql->setLimite($xpg_min, $xpg_tamanhoPagina);
$linhas = $sql->Lista();
$sql->limpar();

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

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1 && !$dialogo) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'Link', 'Links','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=link_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='link_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identificação do link.').'Nome'.dicaF().'</th>';
if ($filtro_prioridade_link) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').'&a='.$a.'&ordenar=priorizacao&ordem='.($ordem ? '0' : '1').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar pela priorização.').($ordenar=='priorizacao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Priorização'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=link_url&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='link_url' ? imagem('icones/'.$seta[$ordem]) : '').dica('Endereço Eletrônico da Referência', 'Caso exista um link para página ou arquivo na rede que faça referência ao registro.').'URL'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=link_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='link_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Neste campo fica a descrição pormenorizada do link.').'Descrição'.dicaF().'</th>';
if ($Aplic->profissional) echo '<th nowrap="nowrap">'.dica('Relacionado', 'Neste campo fica a quais áreas do sistema o link está relacionado.').'Relacionado'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=link_categoria&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='link_categoria' ? imagem('icones/'.$seta[$ordem]) : '').dica('Categoria', 'Neste campo fica a categoria do link.').'Categoria'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=link_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='link_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'Neste campo fica o nome d'.$config['genero_usuario'].' '.$config['usuario'].' responsável pela inserção do link.').'Responsável'.dicaF().'</th>';

echo '</tr>';

$qnt=0;
foreach ($linhas as $linha) {
	if (permiteAcessarLink($linha['link_acesso'],$linha['link_id'])) {
		$qnt++;
		$editar=($podeEditar && permiteEditarLink($linha['link_acesso'],$linha['link_id']));
		echo '<tr>';
		echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Link', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o link.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=links&a=editar&link_id='.$linha['link_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td nowrap="nowrap">'.dica($linha['link_nome'], 'Clique para visualizar os detalhes deste link.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=links&a=ver&link_id='.$linha['link_id'].'\');">'.$linha['link_nome'].'</a>'.dicaF().'</td>';
		if ($filtro_prioridade_link) echo '<td align="right" nowrap="nowrap" width=50>'.($linha['priorizacao']).'</td>';
		echo !empty($linha['link_url']) ? '<td align="center" valign="middle" width=16>'.dica('Link', 'Clique neste ícone '.imagem('icones/link.png').' para  acessar:<ul><li>'.$linha['link_url'].'</ul>').'<a href="'.$linha['link_url'].'" target="_blank">'.imagem('icones/link.png').'</a>'.dicaF().'</td>' : '<td>&nbsp;</td>';
		echo '<td>'.($linha['link_descricao'] ? $linha['link_descricao'] : '&nbsp;').'</td>';
		
		if ($Aplic->profissional){
			$sql->adTabela('link_gestao');
			$sql->adCampo('link_gestao.*');
			$sql->adOnde('link_gestao_link ='.(int)$linha['link_id']);
			$sql->adOrdem('link_gestao_ordem');
		  $lista = $sql->Lista();
		  $sql->Limpar();
		  echo '<td>';
		  if (count($lista)) {
				$qnt=0;
				foreach($lista as $gestao_data){
					if ($gestao_data['link_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['link_gestao_tarefa']);
					elseif ($gestao_data['link_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['link_gestao_projeto']);
					elseif ($gestao_data['link_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['link_gestao_pratica']);
					elseif ($gestao_data['link_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['link_gestao_acao']);
					elseif ($gestao_data['link_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['link_gestao_perspectiva']);
					elseif ($gestao_data['link_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['link_gestao_tema']);
					elseif ($gestao_data['link_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['link_gestao_objetivo']);
					elseif ($gestao_data['link_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['link_gestao_fator']);
					elseif ($gestao_data['link_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['link_gestao_estrategia']);
					elseif ($gestao_data['link_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['link_gestao_meta']);
					elseif ($gestao_data['link_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['link_gestao_canvas']);
					elseif ($gestao_data['link_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['link_gestao_risco']);
					elseif ($gestao_data['link_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['link_gestao_risco_resposta']);
					elseif ($gestao_data['link_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['link_gestao_indicador']);
					elseif ($gestao_data['link_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['link_gestao_calendario']);
					elseif ($gestao_data['link_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['link_gestao_monitoramento']);
					elseif ($gestao_data['link_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['link_gestao_ata']);
					elseif ($gestao_data['link_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['link_gestao_swot']);
					elseif ($gestao_data['link_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['link_gestao_operativo']);
					elseif ($gestao_data['link_gestao_instrumento']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['link_gestao_instrumento']);
					elseif ($gestao_data['link_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['link_gestao_recurso']);
					elseif ($gestao_data['link_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['link_gestao_problema']);
					elseif ($gestao_data['link_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['link_gestao_demanda']);
					elseif ($gestao_data['link_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['link_gestao_programa']);
					elseif ($gestao_data['link_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['link_gestao_licao']);
					elseif ($gestao_data['link_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['link_gestao_evento']);
					elseif ($gestao_data['link_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['link_gestao_avaliacao']);
					elseif ($gestao_data['link_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['link_gestao_tgn']);
					elseif ($gestao_data['link_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['link_gestao_brainstorm']);
					elseif ($gestao_data['link_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['link_gestao_gut']);
					elseif ($gestao_data['link_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['link_gestao_causa_efeito']);
					elseif ($gestao_data['link_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['link_gestao_arquivo']);
					elseif ($gestao_data['link_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['link_gestao_forum']);
					elseif ($gestao_data['link_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['link_gestao_checklist']);
					elseif ($gestao_data['link_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['link_gestao_agenda']);
					elseif ($gestao_data['link_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['link_gestao_agrupamento']);
					elseif ($gestao_data['link_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['link_gestao_patrocinador']);
					elseif ($gestao_data['link_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['link_gestao_template']);
					elseif ($gestao_data['link_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['link_gestao_painel']);
					elseif ($gestao_data['link_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['link_gestao_painel_odometro']);
					elseif ($gestao_data['link_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['link_gestao_painel_composicao']);
					elseif ($gestao_data['link_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['link_gestao_tr']);
					elseif ($gestao_data['link_gestao_me']) echo ($qnt++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['link_gestao_me']);
					}
				
				}
			echo '</td>';	
			}
		
		echo '<td nowrap="nowrap" align="center">'.(isset($link_tipos[$linha['link_categoria']]) ? $link_tipos[$linha['link_categoria']] : '&nbsp;').'</td>';
		echo '<td>'.link_usuario($linha['link_dono'],'','','esquerda').'</td>';
		
		echo '</tr>';
		}
	}
if (!count($linhas)) echo '<tr><td colspan=20><p>Nenhum link encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="8"><p>Não tem autorização para visualizar nenhum dos links.</p></td></tr>';		
echo '</table>';
?>