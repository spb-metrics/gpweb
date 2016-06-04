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
if (!$Aplic->checarModulo('recursos', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

global $estilo_interface, $sql, $perms, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $usuario_id, $pesquisar_texto, $dialogo, $podeEditar, $podeExcluir, $instrumento_ano,  $instrumento_tipo, $instrumento_situacao,
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
	$template_id;


$pagina = getParam($_REQUEST, 'pagina', 1);
$pesquisa = getParam($_REQUEST, 'pesquisa', '');
$ordenar = getParam($_REQUEST, 'ordenar', 'instrumento_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');


$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_instrumentos']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$TipoInstrumento = getSisValor('TipoInstrumento');
$ModalidadeLicitacao = getSisValor('ModalidadeLicitacao');
$SituacaoInstrumento = getSisValor('SituacaoInstrumento');

$sql = new BDConsulta();



$sql->adTabela('instrumento');
$sql->adCampo('count(DISTINCT instrumento.instrumento_id)');

if ($Aplic->profissional && $instrumento_ano!=-1 && $instrumento_ano) $sql->adOnde('instrumento_ano IN ('.$instrumento_ano.')');
if ($Aplic->profissional && $instrumento_tipo!=-1 && $instrumento_tipo) $sql->adOnde('instrumento_tipo IN ('.$instrumento_tipo.')');
if ($Aplic->profissional && $instrumento_situacao!=-1 && $instrumento_situacao) $sql->adOnde('instrumento_situacao IN ('.$instrumento_situacao.')');

if ($pesquisar_texto) $sql->adOnde('(instrumento_nome LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_objeto LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_numero LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_entidade LIKE \'%'.$pesquisar_texto.'%\')');

if ($tab==0) $sql->adOnde('instrumento_ativo=1');
elseif ($tab==1) $sql->adOnde('instrumento_ativo!=1 OR instrumento_ativo IS NULL');	

if($usuario_id) {
	$sql->esqUnir('instrumento_designados','instrumento_designados', 'instrumento_designados.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_designados.usuario_id ='.(int)$usuario_id.' OR instrumento_responsavel='.(int)$usuario_id);
	}
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept='.(int)$dept_id.' OR instrumento_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept IN ('.$lista_depts.') OR instrumento_depts.dept_id IN ('.$lista_depts.')');
	}
if ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('instrumento_cia', 'instrumento_cia', 'instrumento.instrumento_id=instrumento_cia_instrumento');
	$sql->adOnde('instrumento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR instrumento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('instrumento_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('instrumento_cia IN ('.$lista_cias.')');

if(!$Aplic->profissional && $projeto_id){
	$sql->esqUnir('instrumento_recursos', 'instrumento_recursos', 'instrumento_recursos.instrumento_id = instrumento.instrumento_id');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = instrumento_recursos.recurso_id');
	$sql->esqUnir('recurso_tarefas', 'recurso_tarefas', 'recurso_tarefas.recurso_id = recursos.recurso_id');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_tarefas.tarefa_id');
	$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
	if ($projeto_id) $sql->adOnde('projetos.projeto_id = '.(int)$projeto_id);
	}
if ($Aplic->profissional){
	$sql->esqUnir('instrumento_gestao','instrumento_gestao','instrumento_gestao_instrumento = instrumento.instrumento_id');
	if ($tarefa_id) $sql->adOnde('instrumento_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('instrumento_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('instrumento_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('instrumento_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('instrumento_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('instrumento_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('instrumento_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('instrumento_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('instrumento_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('instrumento_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('instrumento_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('instrumento_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('instrumento_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('instrumento_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('instrumento_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('instrumento_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('instrumento_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('instrumento_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('instrumento_gestao_operativo='.(int)$operativo_id);
	elseif ($recurso_id) $sql->adOnde('instrumento_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('instrumento_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('instrumento_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('instrumento_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('instrumento_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('instrumento_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('instrumento_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('instrumento_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('instrumento_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('instrumento_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('instrumento_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('instrumento_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('instrumento_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('instrumento_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('instrumento_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('instrumento_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('instrumento_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('instrumento_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('instrumento_gestao_template='.(int)$template_id);
	}		
$xtotalregistros = $sql->Resultado();
$sql->limpar();



$sql->adTabela('instrumento');
$sql->adCampo('DISTINCT instrumento_id, instrumento_nome, instrumento_tipo, instrumento_objeto, instrumento_licitacao, instrumento_situacao, instrumento_valor, instrumento_data_inicio, instrumento_acesso, instrumento_cor, instrumento_responsavel');

if ($Aplic->profissional && $instrumento_ano!=-1 && $instrumento_ano) $sql->adOnde('instrumento_ano IN ('.$instrumento_ano.')');
if ($Aplic->profissional && $instrumento_tipo!=-1 && $instrumento_tipo) $sql->adOnde('instrumento_tipo IN ('.$instrumento_tipo.')');
if ($Aplic->profissional && $instrumento_situacao!=-1 && $instrumento_situacao) $sql->adOnde('instrumento_situacao IN ('.$instrumento_situacao.')');


if ($pesquisar_texto) $sql->adOnde('(instrumento_nome LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_objeto LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_numero LIKE \'%'.$pesquisar_texto.'%\' OR instrumento_entidade LIKE \'%'.$pesquisar_texto.'%\')');

if($usuario_id) {
	$sql->esqUnir('instrumento_designados','instrumento_designados', 'instrumento_designados.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_designados.usuario_id ='.(int)$usuario_id.' OR instrumento_responsavel='.(int)$usuario_id);
	}
if ($dept_id && !$lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept='.(int)$dept_id.' OR instrumento_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('instrumento_depts','instrumento_depts', 'instrumento_depts.instrumento_id=instrumento.instrumento_id');
	$sql->adOnde('instrumento_dept IN ('.$lista_depts.') OR instrumento_depts.dept_id IN ('.$lista_depts.')');
	}
if ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('instrumento_cia', 'instrumento_cia', 'instrumento.instrumento_id=instrumento_cia_instrumento');
	$sql->adOnde('instrumento_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR instrumento_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('instrumento_cia='.(int)$cia_id);
elseif ($lista_cias) $sql->adOnde('instrumento_cia IN ('.$lista_cias.')');

if ($tab==0) $sql->adOnde('instrumento_ativo=1');
elseif ($tab==1) $sql->adOnde('instrumento_ativo!=1 OR instrumento_ativo IS NULL');	

if(!$Aplic->profissional && $projeto_id){
	$sql->esqUnir('instrumento_recursos', 'instrumento_recursos', 'instrumento_recursos.instrumento_id = instrumento.instrumento_id');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id = instrumento_recursos.recurso_id');
	$sql->esqUnir('recurso_tarefas', 'recurso_tarefas', 'recurso_tarefas.recurso_id = recursos.recurso_id');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = recurso_tarefas.tarefa_id');
	$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
	if ($projeto_id) $sql->adOnde('projetos.projeto_id = '.(int)$projeto_id);
	}
if ($Aplic->profissional){
	$sql->esqUnir('instrumento_gestao','instrumento_gestao','instrumento_gestao_instrumento = instrumento.instrumento_id');
	if ($tarefa_id) $sql->adOnde('instrumento_gestao_tarefa='.$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('instrumento_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('instrumento_gestao_perspectiva='.$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('instrumento_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('instrumento_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('instrumento_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('instrumento_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('instrumento_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('instrumento_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('instrumento_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('instrumento_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('instrumento_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('instrumento_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('instrumento_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('instrumento_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('instrumento_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('instrumento_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('instrumento_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('instrumento_gestao_operativo='.(int)$operativo_id);
	elseif ($recurso_id) $sql->adOnde('instrumento_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('instrumento_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('instrumento_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('instrumento_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('instrumento_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('instrumento_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('instrumento_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('instrumento_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('instrumento_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('instrumento_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('instrumento_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('instrumento_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('instrumento_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('instrumento_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('instrumento_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('instrumento_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('instrumento_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('instrumento_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('instrumento_gestao_template='.(int)$template_id);
	}	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$instrumentos = $sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Instrumento', 'Instrumentos','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));



echo '<table width='.($dialogo ? '1050' : '"100%"').' cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
echo '<th nowrap="nowrap">&nbsp;</th>';

echo '<th width=16 nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Instrumento', 'Neste campo fica a cor de identificação do instrumento.').'Cor'.dicaF().'</a></th>';



echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Instrumento', 'Neste campo fica um nome para identificação do instrumento.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_tipo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_tipo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo de Instrumento', 'Neste campo fica o tipo do instrumento.').'Tipo'.dicaF().'</a></th>';

if ($Aplic->profissional) echo '<th nowrap="nowrap">'.dica('Relacionado', 'A que área este instrumento está relacionado.').'Relacionado'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_objeto&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_objeto' ? imagem('icones/'.$seta[$ordem]) : '').dica('Objeto do Instrumento', 'Caso exista um instrumento para página ou arquivo na rede que faça referência ao registro.').'Objeto'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_licitacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_licitacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Tipo de Licitação', 'Neste campo fica o tipo de licitação do instrumento.').'Licitação'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_situacao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_situacao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Situação  do Instrumento', 'Neste campo fica a situação do instrumento.').'Situação'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_valor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_valor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Valor', 'Neste campo fica o valor do instrumento.').'Valor'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_data_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_data_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data', 'Neste campo fica a data de início do instrumento.').'Data'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').'&ordenar=instrumento_data_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='instrumento_data_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'Neste campo fica o responsável pelo instrumento.').'Responsável'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;
foreach ($instrumentos as $linha) {
	if (permiteAcessarInstrumento($linha['instrumento_acesso'])){	
		$qnt++;
		$permiteEditar=permiteEditarInstrumento($linha['instrumento_acesso']);
		$editar=($podeEditar&&$permiteEditar);
		echo '<tr>';
		echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Instrumento', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o instrumento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=instrumento_editar&instrumento_id='.$linha['instrumento_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['instrumento_cor'].'">&nbsp;&nbsp;</td>';
		echo '<td>'.dica($linha['instrumento_nome'], 'Clique para visualizar os detalhes deste instrumento.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=recursos&a=instrumento_ver&instrumento_id='.$linha['instrumento_id'].'\');">'.$linha['instrumento_nome'].'</a>'.dicaF().'</td>';
		echo '<td align="center">'.(isset($TipoInstrumento[$linha['instrumento_tipo']]) ? $TipoInstrumento[$linha['instrumento_tipo']] : '&nbsp;').'</td>';
		
		
		if ($Aplic->profissional){
			$sql->adTabela('instrumento_gestao');
			$sql->adCampo('instrumento_gestao.*');
			$sql->adOnde('instrumento_gestao_instrumento ='.(int)$linha['instrumento_id']);
			$sql->adOrdem('instrumento_gestao_ordem');
		  $lista = $sql->Lista();
		  $sql->Limpar();
		  echo '<td  nowrap="nowrap">';
		  if (count($lista)) {
			  
				
				$qnt=0;
				foreach($lista as $gestao_data){
					if ($gestao_data['instrumento_gestao_tarefa']) echo ($qnt++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['instrumento_gestao_tarefa']);
					elseif ($gestao_data['instrumento_gestao_projeto']) echo ($qnt++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['instrumento_gestao_projeto']);
					elseif ($gestao_data['instrumento_gestao_pratica']) echo ($qnt++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['instrumento_gestao_pratica']);
					elseif ($gestao_data['instrumento_gestao_acao']) echo ($qnt++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['instrumento_gestao_acao']);
					elseif ($gestao_data['instrumento_gestao_perspectiva']) echo ($qnt++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['instrumento_gestao_perspectiva']);
					elseif ($gestao_data['instrumento_gestao_tema']) echo ($qnt++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['instrumento_gestao_tema']);
					elseif ($gestao_data['instrumento_gestao_objetivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['instrumento_gestao_objetivo']);
					elseif ($gestao_data['instrumento_gestao_fator']) echo ($qnt++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['instrumento_gestao_fator']);
					elseif ($gestao_data['instrumento_gestao_estrategia']) echo ($qnt++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['instrumento_gestao_estrategia']);
					elseif ($gestao_data['instrumento_gestao_meta']) echo ($qnt++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['instrumento_gestao_meta']);
					elseif ($gestao_data['instrumento_gestao_canvas']) echo ($qnt++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['instrumento_gestao_canvas']);
					elseif ($gestao_data['instrumento_gestao_risco']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['instrumento_gestao_risco']);
					elseif ($gestao_data['instrumento_gestao_risco_resposta']) echo ($qnt++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['instrumento_gestao_risco_resposta']);
					elseif ($gestao_data['instrumento_gestao_indicador']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['instrumento_gestao_indicador']);
					elseif ($gestao_data['instrumento_gestao_calendario']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['instrumento_gestao_calendario']);
					elseif ($gestao_data['instrumento_gestao_monitoramento']) echo ($qnt++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['instrumento_gestao_monitoramento']);
					elseif ($gestao_data['instrumento_gestao_ata']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['instrumento_gestao_ata']);
					elseif (isset($gestao_data['instrumento_gestao_swot']) && $gestao_data['instrumento_gestao_swot']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['instrumento_gestao_swot']);
					elseif (isset($gestao_data['instrumento_gestao_operativo']) && $gestao_data['instrumento_gestao_operativo']) echo ($qnt++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['instrumento_gestao_operativo']);
					elseif ($gestao_data['instrumento_gestao_recurso']) echo ($qnt++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['instrumento_gestao_recurso']);
					elseif ($gestao_data['instrumento_gestao_problema']) echo ($qnt++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['instrumento_gestao_problema']);
					elseif ($gestao_data['instrumento_gestao_demanda']) echo ($qnt++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['instrumento_gestao_demanda']);
					elseif ($gestao_data['instrumento_gestao_programa']) echo ($qnt++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['instrumento_gestao_programa']);
					elseif ($gestao_data['instrumento_gestao_licao']) echo ($qnt++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['instrumento_gestao_licao']);
					elseif ($gestao_data['instrumento_gestao_evento']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['instrumento_gestao_evento']);
					elseif ($gestao_data['instrumento_gestao_link']) echo ($qnt++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['instrumento_gestao_link']);
					elseif ($gestao_data['instrumento_gestao_avaliacao']) echo ($qnt++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['instrumento_gestao_avaliacao']);
					elseif ($gestao_data['instrumento_gestao_tgn']) echo ($qnt++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['instrumento_gestao_tgn']);
					elseif ($gestao_data['instrumento_gestao_brainstorm']) echo ($qnt++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['instrumento_gestao_brainstorm']);
					elseif ($gestao_data['instrumento_gestao_gut']) echo ($qnt++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['instrumento_gestao_gut']);
					elseif ($gestao_data['instrumento_gestao_causa_efeito']) echo ($qnt++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['instrumento_gestao_causa_efeito']);
					elseif ($gestao_data['instrumento_gestao_arquivo']) echo ($qnt++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['instrumento_gestao_arquivo']);
					elseif ($gestao_data['instrumento_gestao_forum']) echo ($qnt++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['instrumento_gestao_forum']);
					elseif ($gestao_data['instrumento_gestao_checklist']) echo ($qnt++ ? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['instrumento_gestao_checklist']);
					elseif ($gestao_data['instrumento_gestao_agenda']) echo ($qnt++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['instrumento_gestao_agenda']);
					elseif ($gestao_data['instrumento_gestao_agrupamento']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['instrumento_gestao_agrupamento']);
					elseif ($gestao_data['instrumento_gestao_patrocinador']) echo ($qnt++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['instrumento_gestao_patrocinador']);
					elseif ($gestao_data['instrumento_gestao_template']) echo ($qnt++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['instrumento_gestao_template']);
					elseif ($gestao_data['instrumento_gestao_painel']) echo ($qnt++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['instrumento_gestao_painel']);
					elseif ($gestao_data['instrumento_gestao_painel_odometro']) echo ($qnt++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['instrumento_gestao_painel_odometro']);
					elseif ($gestao_data['instrumento_gestao_painel_composicao']) echo ($qnt++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['instrumento_gestao_painel_composicao']);		
					elseif ($gestao_data['instrumento_gestao_tr']) echo ($qnt++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['instrumento_gestao_tr']);	
					}
				
				}
			echo '</td>';	
			}
				
		echo '<td>'.($linha['instrumento_objeto'] ? $linha['instrumento_objeto'] : '&nbsp;').'</td>';
		echo '<td align="center">'.(isset($ModalidadeLicitacao[$linha['instrumento_licitacao']]) ? $ModalidadeLicitacao[$linha['instrumento_licitacao']] : '&nbsp;').'</td>';
		echo '<td align="center">'.(isset($SituacaoInstrumento[$linha['instrumento_situacao']]) ? $SituacaoInstrumento[$linha['instrumento_situacao']] : '&nbsp;').'</td>';
		echo '<td align="right">'.number_format($linha['instrumento_valor'], 2, ',', '.').'</td>';
		echo '<td align="center">'.retorna_data($linha['instrumento_data_inicio'], false).'</td>';
		echo '<td align="left">'.link_usuario($linha['instrumento_responsavel'],'','','esquerda').'</td>';
		echo '</tr>';
		}
	}
	
if (!count($instrumentos)) echo '<tr><td colspan="20"><p>Nenhum instrumento encontrado.</p></td></tr>';
elseif (!$qnt) echo '<tr><td colspan="20"><p>Não tem autorização para visualizar nenhum dos instrumentos.</p></td></tr>';		
echo '</table>';
?>