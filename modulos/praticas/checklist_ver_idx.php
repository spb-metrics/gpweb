<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $sql, $perms, $Aplic, $cia_id, $lista_cias, $pesquisar_texto, $usuario_id, $dept_id, $lista_depts, $tab, $ordem, $ordenar, $favorito_id, $dialogo,
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
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id;	


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

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_checklist']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

$ordenar = getParam($_REQUEST, 'ordenar', 'checklist_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');


$sql->adTabela('checklist');

if ($Aplic->profissional){
	$sql->esqUnir('checklist_gestao','checklist_gestao','checklist_gestao_checklist = checklist.checklist_id');
	if ($tarefa_id) $sql->adOnde('checklist_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('checklist_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('checklist_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('checklist_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('checklist_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('checklist_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('checklist_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('checklist_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('checklist_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('checklist_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('checklist_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('checklist_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('checklist_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('checklist_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('checklist_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('checklist_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('checklist_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('checklist_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('checklist_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('checklist_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('checklist_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('checklist_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('checklist_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('checklist_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('checklist_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('checklist_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('checklist_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('checklist_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('checklist_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('checklist_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('checklist_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('checklist_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('checklist_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('checklist_gestao_forum='.(int)$forum_id);
	elseif ($agenda_id) $sql->adOnde('checklist_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('checklist_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('checklist_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('checklist_gestao_template='.(int)$template_id);	
	}
if ($usuario_id) {
	$sql->esqUnir('checklist_usuarios', 'checklist_usuarios', 'checklist_usuarios.checklist_id = checklist.checklist_id');
	$sql->adOnde('checklist_responsavel = '.(int)$usuario_id.' OR checklist_usuarios.usuario_id='.(int)$usuario_id);
	}	
	
if ($pesquisar_texto) $sql->adOnde('checklist_nome LIKE \'%'.$pesquisar_texto.'%\' OR checklist_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$sql->adCampo('count(DISTINCT checklist.checklist_id)');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'checklist.checklist_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('checklist_depts','checklist_depts', 'checklist_depts.checklist_id=checklist.checklist_id');
	$sql->adOnde('checklist_dept='.(int)$dept_id.' OR checklist_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('checklist_depts','checklist_depts', 'checklist_depts.checklist_id=checklist.checklist_id');
	$sql->adOnde('checklist_dept IN ('.$lista_depts.') OR checklist_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('checklist_cia', 'checklist_cia', 'checklist.checklist_id=checklist_cia_checklist');
	$sql->adOnde('checklist_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR checklist_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('checklist_cia='.$cia_id);
elseif ($lista_cias) $sql->adOnde('checklist_cia IN ('.$lista_cias.')');

if ($tab==0) $sql->adOnde('checklist_ativo=1');
elseif ($tab==1) $sql->adOnde('checklist_ativo!=1 OR checklist_ativo IS NULL'); 	
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('checklist');
if ($Aplic->profissional){
	$sql->esqUnir('checklist_gestao','checklist_gestao','checklist_gestao_checklist = checklist.checklist_id');
	if ($tarefa_id) $sql->adOnde('checklist_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('checklist_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('checklist_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('checklist_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('checklist_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('checklist_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('checklist_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('checklist_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('checklist_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('checklist_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('checklist_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('checklist_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('checklist_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('checklist_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('checklist_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('checklist_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('checklist_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('checklist_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('checklist_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('checklist_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('checklist_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('checklist_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('checklist_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('checklist_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('checklist_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('checklist_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('checklist_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('checklist_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('checklist_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('checklist_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('checklist_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('checklist_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($arquivo_id) $sql->adOnde('checklist_gestao_arquivo='.(int)$arquivo_id);
	elseif ($forum_id) $sql->adOnde('checklist_gestao_forum='.(int)$forum_id);
	elseif ($agenda_id) $sql->adOnde('checklist_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('checklist_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('checklist_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('checklist_gestao_template='.(int)$template_id);	
	}

if ($usuario_id) {
	$sql->esqUnir('checklist_usuarios', 'checklist_usuarios', 'checklist_usuarios.checklist_id = checklist.checklist_id');
	$sql->adOnde('checklist_responsavel = '.(int)$usuario_id.' OR checklist_usuarios.usuario_id='.(int)$usuario_id);
	}	
	
if ($pesquisar_texto) $sql->adOnde('checklist_nome LIKE \'%'.$pesquisar_texto.'%\' OR checklist_descricao LIKE \'%'.$pesquisar_texto.'%\'');

$sql->esqUnir('checklist_lista', 'checklist_lista','checklist_lista_checklist_id=checklist.checklist_id');
$sql->adCampo('DISTINCT checklist.checklist_id, checklist_nome, checklist_descricao, checklist_responsavel, checklist_cor, checklist_acesso, count(checklist_lista_id) AS qnt');

if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'checklist.checklist_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('checklist_depts','checklist_depts', 'checklist_depts.checklist_id=checklist.checklist_id');
	$sql->adOnde('checklist_dept='.(int)$dept_id.' OR checklist_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('checklist_depts','checklist_depts', 'checklist_depts.checklist_id=checklist.checklist_id');
	$sql->adOnde('checklist_dept IN ('.$lista_depts.') OR checklist_depts.dept_id IN ('.$lista_depts.')');
	}	
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('checklist_cia', 'checklist_cia', 'checklist.checklist_id=checklist_cia_checklist');
	$sql->adOnde('checklist_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR checklist_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}	
elseif ($cia_id && !$lista_cias) $sql->adOnde('checklist_cia='.$cia_id);
elseif ($lista_cias) $sql->adOnde('checklist_cia IN ('.$lista_cias.')');


if ($tab==0) $sql->adOnde('checklist_ativo=1');
elseif ($tab==1) $sql->adOnde('checklist_ativo!=1 OR checklist_ativo IS NULL'); 	
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$sql->adGrupo('checklist.checklist_id');
$checklists=$sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Checklist', 'Checklist','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="'.($dialogo ? '750' : '100%').'" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';

if (!$impressao) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=checklist_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='checklist_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Checklist', 'Neste campo fica a cor de identificação do checklist.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=checklist_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='checklist_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome do Checklist', 'Neste campo fica um nome para identificação do checklist.').'Nome'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=checklist_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='checklist_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição do Checklist', 'Neste campo fica a descrição do checklist.').'Descrição'.dicaF().'</a></th>';
if ($Aplic->profissional) echo '<th nowrap="nowrap">'.dica('Relacionado', 'A quais áreas do sistema está relacionado.').'Relacionado'.dicaF().'</th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=checklist_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='checklist_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pelo checklist.').'Responsável'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=qnt&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='qnt' ? imagem('icones/'.$seta[$ordem]) : '').dica('Quantidade', 'A quantidade de perguntas inseridas no checklist.').'<b>Qnt</b>'.dicaF().'</a></th>';
echo '</tr>';

$qnt=0;
foreach ($checklists as $linha) {
	if (permiteAcessarChecklist($linha['checklist_acesso'],$linha['checklist_id'])){
		$qnt++;
		$editar=permiteEditarChecklist($linha['checklist_acesso'],$linha['checklist_id']);
		echo '<tr>';
		if (!$impressao) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar Checklist', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o checklist.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=checklist_editar&checklist_id='.$linha['checklist_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['checklist_cor'].'"><font color="'.melhorCor($linha['checklist_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=checklist_ver&checklist_id='.$linha['checklist_id'].'\');">'.dica($linha['checklist_nome'],'Clique ver ver os detalhes deste checklist').$linha['checklist_nome'].dicaF().'</a></td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['checklist_descricao'] ? $linha['checklist_descricao']: '&nbsp;').'</td>';
		
		if ($Aplic->profissional) {
			echo '<td align="left">';
			$sql->adTabela('checklist_gestao');
			$sql->adCampo('checklist_gestao.*');
			$sql->adOnde('checklist_gestao_checklist ='.(int)$linha['checklist_id']);
			$sql->adOrdem('checklist_gestao_ordem');	
			$lista = $sql->Lista();
			$sql->Limpar();
			$qnt_gestao=0;
			if (count($lista)){	
				foreach($lista as $gestao_data){	
					if ($gestao_data['checklist_gestao_tarefa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['checklist_gestao_tarefa']);
					elseif ($gestao_data['checklist_gestao_projeto']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/projeto_p.gif').link_projeto($gestao_data['checklist_gestao_projeto']);
					elseif ($gestao_data['checklist_gestao_pratica']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['checklist_gestao_pratica']);
					elseif ($gestao_data['checklist_gestao_acao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['checklist_gestao_acao']);
					elseif ($gestao_data['checklist_gestao_perspectiva']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['checklist_gestao_perspectiva']);
					elseif ($gestao_data['checklist_gestao_tema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['checklist_gestao_tema']);
					elseif ($gestao_data['checklist_gestao_objetivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['checklist_gestao_objetivo']);
					elseif ($gestao_data['checklist_gestao_fator']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['checklist_gestao_fator']);
					elseif ($gestao_data['checklist_gestao_estrategia']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['checklist_gestao_estrategia']);
					elseif ($gestao_data['checklist_gestao_meta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['checklist_gestao_meta']);
					elseif ($gestao_data['checklist_gestao_canvas']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['checklist_gestao_canvas']);
					elseif ($gestao_data['checklist_gestao_risco']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['checklist_gestao_risco']);
					elseif ($gestao_data['checklist_gestao_risco_resposta']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['checklist_gestao_risco_resposta']);
					elseif ($gestao_data['checklist_gestao_indicador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['checklist_gestao_indicador']);
					elseif ($gestao_data['checklist_gestao_calendario']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['checklist_gestao_calendario']);
					elseif ($gestao_data['checklist_gestao_monitoramento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['checklist_gestao_monitoramento']);
					elseif ($gestao_data['checklist_gestao_ata']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['checklist_gestao_ata']);
					elseif (isset($gestao_data['checklist_gestao_swot']) && $gestao_data['checklist_gestao_swot']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['checklist_gestao_swot']);
					elseif (isset($gestao_data['checklist_gestao_operativo']) && $gestao_data['checklist_gestao_operativo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['checklist_gestao_operativo']);
					elseif ($gestao_data['checklist_gestao_instrumento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['checklist_gestao_instrumento']);
					elseif ($gestao_data['checklist_gestao_recurso']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['checklist_gestao_recurso']);
					elseif ($gestao_data['checklist_gestao_problema']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['checklist_gestao_problema']);
					elseif ($gestao_data['checklist_gestao_demanda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['checklist_gestao_demanda']);
					elseif ($gestao_data['checklist_gestao_programa']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['checklist_gestao_programa']);
					elseif ($gestao_data['checklist_gestao_licao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['checklist_gestao_licao']);
					elseif ($gestao_data['checklist_gestao_evento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['checklist_gestao_evento']);
					elseif ($gestao_data['checklist_gestao_link']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['checklist_gestao_link']);
					elseif ($gestao_data['checklist_gestao_avaliacao']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['checklist_gestao_avaliacao']);
					elseif ($gestao_data['checklist_gestao_tgn']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['checklist_gestao_tgn']);
					elseif ($gestao_data['checklist_gestao_brainstorm']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['checklist_gestao_brainstorm']);
					elseif ($gestao_data['checklist_gestao_gut']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['checklist_gestao_gut']);
					elseif ($gestao_data['checklist_gestao_causa_efeito']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['checklist_gestao_causa_efeito']);
					elseif ($gestao_data['checklist_gestao_arquivo']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['checklist_gestao_arquivo']);
					elseif ($gestao_data['checklist_gestao_forum']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['checklist_gestao_forum']);
					elseif ($gestao_data['checklist_gestao_agenda']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['checklist_gestao_agenda']);
					elseif ($gestao_data['checklist_gestao_agrupamento']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['checklist_gestao_agrupamento']);
					elseif ($gestao_data['checklist_gestao_patrocinador']) echo ($qnt_gestao++ ? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['checklist_gestao_patrocinador']);
					elseif ($gestao_data['checklist_gestao_template']) echo ($qnt_gestao++ ? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['checklist_gestao_template']);
					}
				}	
			echo '</td>';
			
			}
		
		echo '<td nowrap="nowrap">'.link_usuario($linha['checklist_responsavel'],'','','esquerda').'</td>';
		echo '<td width="25" align=center>'.$linha['qnt'].'</td>';
		echo '</tr>';
		}
	}
if (!count($checklists)) echo '<tr><td colspan=20><p>Nenhum checklist encontrado.</p></td></tr>';
elseif(count($checklists) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer dos checklists.</p></td></tr>';

echo '</table>';

if ($impressao) echo '<script language=Javascript>self.print();</script>';

?>