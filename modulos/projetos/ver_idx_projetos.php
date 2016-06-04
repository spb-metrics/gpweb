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

global $estilo_interface, $projeto_tipos, $podeEditar, $ordemDir, $ordenarPor, $projeto_id, $responsavel, $supervisor, $autoridade, $cliente, $nao_apenas_superiores,$lista_cias, $projeto_expandido, $favorito_id, $dept_id, $lista_depts, $pesquisar_texto, $projeto_tipo,  $projetostatus, $Aplic, $cia_id, $tab, $projStatus, $projetos_status, $projeto_setor, $projeto_segmento, $projeto_intervencao, $projeto_tipo_intervencao, $estado_sigla, $municipio_id, $dialogo, $filtro_area, $filtro_criterio, $filtro_opcao, $filtro_prioridade, $filtro_perspectiva, $filtro_tema, $filtro_objetivo, $filtro_fator, $filtro_estrategia, $filtro_meta, $filtro_canvas, $campos_extras,
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
			$me_id,
			$xpg_totalregistros_projetos,
      $xpg_totalregistros_recebidos;

$seta=array('asc'=>'seta-cima.gif', 'desc'=>'seta-baixo.gif');
//ordenação dos projetos



$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = ($dialogo ? 900000 : $config['qnt_projetos']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);
$mostrar_todos_projetos = false;

$vetorStatus =array(''=>'')+$projStatus;
$mover=array();
$mover[]='';

for ($i=1;$i<=12;$i++) $mover['m'.$i]='+'.($i < 10 ? '0':'').$i.' mes'.($i>1 ? 'es' : '');
for ($i=1;$i<=5;$i++) $mover['s'.$i]='+'.($i < 10 ? '0':'').$i.' semana'.($i>1 ? 's' : '');
for ($i=1;$i<=30;$i++) $mover['d'.$i]='+'.($i < 10 ? '0':'').$i.' dia'.($i>1 ? 's' : '');
for ($i=-1;$i>=-12;$i--) $mover['m'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' mes'.($i<-1 ? 'es' : '');
for ($i=-1;$i>=-5;$i--) $mover['s'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' semana'.($i<-1 ? 's' : '');
for ($i=-1;$i>=-30;$i--) $mover['d'.$i]='-'.($i > -10 ? '0':'').(-1*$i).' dia'.($i<-1 ? 's' : '');

$df = '%d/%m/%Y';

$projetoStatus = getSisValor('StatusProjeto');

$sql = new BDConsulta;

$exibir = array();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'projetos\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

if ($Aplic->profissional){
	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'projetos\'');
	$sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
	$exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

  $diff = array_diff_key($exibir, $exibir2);
  if($diff) $exibir = array_merge($exibir2, $diff);
  else $exibir = $exibir2;
	}



$xpg_totalregistros = $xpg_totalregistros_projetos;

$filtrosBuilder = new FiltrosProjetoBuilder();
$filtrosBuilder->setUsuarioId($responsavel)
    ->setSupervisor($supervisor)
    ->setAutoridade($autoridade)
    ->setCliente($cliente)
    ->setCiaId($cia_id)
    ->setOrdenarPor($ordenarPor)
    ->setOrdemDir($ordemDir)
    ->setProjetoTipo($projeto_tipo)
    ->setProjetoSetor($projeto_setor)
    ->setProjetoSegmento($projeto_segmento)
    ->setProjetoIntervencao($projeto_intervencao)
    ->setProjetoTipoIntervencao($projeto_tipo_intervencao)
    ->setEstadoSigla($estado_sigla)
    ->setMunicipioId($municipio_id)
    ->setPesquisarTexto($pesquisar_texto)
    ->setMostrarProjRespPertenceDept(false)
    ->setRecebido(false)
    ->setDeptId($lista_depts ? $lista_depts : $dept_id)
    ->setFavoritoId($favorito_id)
    ->setListaCias($lista_cias)
    ->setProjetoStatus($projetostatus)
    ->setProjetoExpandido($projeto_expandido)
    ->setNaoApenasSuperiores($nao_apenas_superiores)
    ->setExibir($exibir)
    ->setPortfolio(false)
    ->setTemplate(false)
    ->setPortfolioPai($projeto_id)
    ->setDataInicio(null)
    ->setDataTermino(null)
    ->setFiltroArea($filtro_area)
    ->setFiltroCriterio($filtro_criterio)
    ->setFiltroOpcao($filtro_opcao)
    ->setFiltroPrioridade($filtro_prioridade)
    ->setFiltroPerspectiva($filtro_perspectiva)
    ->setFiltroTema($filtro_tema)
    ->setFiltroObjetivo($filtro_objetivo)
    ->setFiltroFator($filtro_fator)
    ->setFiltroEstrategia($filtro_estrategia)
    ->setFiltroMeta($filtro_meta)
    ->setFiltroCanvas($filtro_canvas)
    ->setFiltroExtra($campos_extras)
    ->setPgPerspectivaId($pg_perspectiva_id)
    ->setTemaId($tema_id)
    ->setPgObjetivoEstrategicoId($pg_objetivo_estrategico_id)
    ->setPgFatorCriticoId($pg_fator_critico_id)
    ->setPgEstrategiaId($pg_estrategia_id)
    ->setPgMetaId($pg_meta_id)
    ->setPraticaId($pratica_id)
    ->setPraticaIndicadorId($pratica_indicador_id)
    ->setPlanoAcaoId($plano_acao_id)
    ->setCanvasId($canvas_id)
    ->setRiscoId($risco_id)
    ->setRiscoRespostaId($risco_resposta_id)
    ->setCalendarioId($calendario_id)
    ->setMonitoramentoId($monitoramento_id)
    ->setAtaId($ata_id)
    ->setSwotId($swot_id)
    ->setOperativoId($operativo_id)
    ->setInstrumentoId($instrumento_id)
    ->setRecursoId($recurso_id)
    ->setProblemaId($problema_id)
    ->setDemandaId($demanda_id)
    ->setProgramaId($programa_id)
    ->setLicaoId($licao_id)
    ->setEventoId($evento_id)
    ->setLinkId($link_id)
    ->setAvaliacaoId($avaliacao_id)
    ->setTgnId($tgn_id)
    ->setBrainstormId($brainstorm_id)
    ->setGutId($gut_id)
    ->setCausaEfeitoId($causa_efeito_id)
    ->setArquivoId($arquivo_id)
    ->setForumId($forum_id)
    ->setChecklistId($checklist_id)
    ->setAgendaId($agenda_id)
    ->setAgrupamentoId($agrupamento_id)
    ->setPatrocinadorId($patrocinador_id)
    ->setTemplateId($template_id)
    ->setPainelId($painel_id)
    ->setPainelOdometroId($painel_odometro_id)
    ->setPainelComposicaoId($painel_composicao_id)
    ->setTrId($tr_id)
    ->setMeId($me_id)
    ->setPontoInicio(($pagina - 1) * $xpg_tamanhoPagina)
    ->setLimite($xpg_tamanhoPagina);

if(!$tab || $a=='ver') {
	$projetos=projetos_inicio_data($filtrosBuilder);
	}
else {
    $xpg_totalregistros = $xpg_totalregistros_recebidos;
    $filtrosBuilder->setRecebido(true);
	$projetos=projetos_inicio_data($filtrosBuilder);
	}

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;

if ($xpg_total_paginas > 1){
	mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['projeto'], $config['projetos'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
	}

echo '<form name="frm" id="frm" method="POST">';
echo '<input type="hidden" name="a" id="a" value="index" />';
echo '<input type="hidden" name="m" id="m" value="projetos" />';
echo '<input type="hidden" name="tab" id="tab" value="'.$tab.'" />';

echo '<table width="100%" border=0 cellpadding=0 cellspacing=0><tr><td>';
echo '<table id="tblProjetos" width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
if ($exibir['cor']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cor&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Cor', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por cor.<br>Para facilitar a visualização d'.$config['genero_projeto'].'s '.$config['projetos'].' é conveniente escolher cores distintas para cada um.').($ordenarPor=='projeto_cor' ? imagem('icones/'.$seta[$ordemDir]) : '').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_nome&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo nome dos mesmos.').($ordenarPor=='projeto_nome' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['projeto']). dicaF().'</a></th>';
if ($exibir['fisico']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_percentagem&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Físico Executado', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo físico executado.').($ordenarPor=='projeto_percentagem' ? imagem('icones/'.$seta[$ordemDir]) : '').'%'.dicaF().'</a></th>';
if ($filtro_prioridade) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=priorizacao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Priorização', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela priorização.').($ordenarPor=='priorizacao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Priorização'.dicaF().'</a></th>';
if ($exibir['prioridade']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_prioridade&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Prioridade', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' por prioridade.').($ordenarPor=='projeto_prioridade' ? imagem('icones/'.$seta[$ordemDir]) : '').'P'.dicaF().'</a></th>';
if ($exibir['cia']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cia&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['organizacao']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').($ordenarPor=='projeto_cia' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['organizacao']).dicaF().'</a></th>';
if (isset($exibir['cias']) && $exibir['cias']) echo '<th nowrap="nowrap">'.dica(ucfirst($config['organizacoes']), strtoupper($config['genero_organizacao']).'s '.strtolower($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s n'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['organizacoes']).dicaF().'</th>';
if ($exibir['depts']) echo '<th nowrap="nowrap">'.dica(ucfirst($config['departamentos']), strtoupper($config['genero_dept']).'s '.strtolower($config['departamentos']).' envolvid'.$config['genero_dept'].'s n'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['departamentos']).dicaF().'</th>';
if ($exibir['inicio']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_data_inicio&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Início', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de início.').($ordenarPor=='projeto_data_inicio' ? imagem('icones/'.$seta[$ordemDir]) : '').'Início'.dicaF().'</a></th>';
if ($exibir['termino']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_data_fim&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Término', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pela data de término.').($ordenarPor=='projeto_data_fim' ? imagem('icones/'.$seta[$ordemDir]) : '').'Término'.dicaF().'</a></th>';
if ($exibir['provavel_inicio']) echo '<th nowrap="nowrap">'.dica('Início real', 'Data de início baseada na data inicial d'.$config['genero_tarefa'].' primeir'.$config['genero_tarefa'].' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projetos'].'.').'Início real'.dicaF().'</th>';
if ($exibir['provavel_termino']) echo '<th nowrap="nowrap">'.dica('Término real ', 'Data de término baseada na data final d'.$config['genero_tarefa'].' últim'.$config['genero_tarefa'].' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projetos'].'.').'Término real'.dicaF().'</th>';
if ($exibir['problema']) echo '<th nowrap="nowrap">'.dica('Registros de Problemas', ucfirst($config['projetos']).' com registros de problemas.').'RP'.dicaF().'</th>';
if ($exibir['responsavel']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_responsavel&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.ucfirst($config['projetos']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_gerente'].'s '.$config['gerente'].'.').($ordenarPor=='projeto_responsavel' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['gerente']).dicaF().'</a></th>';
if ($exibir['supervisor']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_supervisor&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['supervisor']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_supervisor'].' '.$config['supervisor'].'.').($ordenarPor=='nome_supervisor' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['supervisor']).dicaF().'</a></th>';
if ($exibir['autoridade']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_autoridade&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['autoridade']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_autoridade'].' '.$config['autoridade'].'.').($ordenarPor=='nome_autoridade' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['autoridade']).dicaF().'</a></th>';
if ($exibir['cliente']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_cliente&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica(ucfirst($config['cliente']), 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pel'.$config['genero_cliente'].' '.$config['cliente'].'.').($ordenarPor=='nome_cliente' ? imagem('icones/'.$seta[$ordemDir]) : '').ucfirst($config['cliente']).dicaF().'</a></th>';
if ($exibir['custo']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_custo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Custo', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo custo d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_custo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Custo ('.$config['simbolo_moeda'].')'.dicaF().'</a></th>';
if ($exibir['gasto']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_gasto&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Gasto', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelo gasto d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_gasto' ? imagem('icones/'.$seta[$ordemDir]) : '').'Gasto ('.$config['simbolo_moeda'].')'.dicaF().'</a></th>';
if ($exibir['recursos']) echo '<th nowrap="nowrap">'.dica('Recursos Financeiro', 'Somatório dos recursos financeiros alocados n'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Recursos ('.$config['simbolo_moeda'].')'.dicaF().'</th>';
if ($exibir['codigo']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_codigo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Código', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos códigos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_codigo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Código'.dicaF().'</a></th>';
if ($exibir['ano']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_ano&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Ano', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos anos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_ano' ? imagem('icones/'.$seta[$ordemDir]) : '').'Ano'.dicaF().'</a></th>';
if ($exibir['setor']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_setor&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Setor', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos setores d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_setor' ? imagem('icones/'.$seta[$ordemDir]) : '').'Setor'.dicaF().'</a></th>';
if ($exibir['segmento']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_segmento&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Segmento', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos segmentos d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_segmento' ? imagem('icones/'.$seta[$ordemDir]) : '').'Segmento'.dicaF().'</a></th>';
if ($exibir['intervencao']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_intervencao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Intervenção', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelas intervenções  n'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_intervencao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Intervenção'.dicaF().'</a></th>';
if ($exibir['tipo_intervencao']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_tipo_intervencao&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Tipo de Intervenção', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos tipos de Intervenção d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_tipo_intervencao' ? imagem('icones/'.$seta[$ordemDir]) : '').'Tipo de Intervenção'.dicaF().'</a></th>';
if ($exibir['categoria']) {
	echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_tipo&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Categoria', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelas categorias d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_tipo' ? imagem('icones/'.$seta[$ordemDir]) : '').'Categoria'.dicaF().'</a></th>';
	if (!is_array($projeto_tipos)){
		$projeto_tipos=array();
		if(!$Aplic->profissional) $projeto_tipos[-1] = '';
		$projeto_tipos += getSisValor('TipoProjeto');
		}
	}
if ($exibir['url']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_url&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Link', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos links d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_url' ? imagem('icones/'.$seta[$ordemDir]) : '').'Link'.dicaF().'</a></th>';
if ($exibir['www']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_url_externa&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Endereço Web', 'Clique para ordenar '.$config['genero_projeto'].'s '.$config['projetos'].' pelos endereços Web d'.$config['genero_projeto'].'s mesm'.$config['genero_projeto'].'s.').($ordenarPor=='projeto_url_externa' ? imagem('icones/'.$seta[$ordemDir]) : '').'Endereço Web'.dicaF().'</a></th>';
if ($exibir['integrantes']) echo '<th nowrap="nowrap">'.dica('Integrantes', 'Lista de integrantes d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Integrantes'.dicaF().'</th>';
if ($exibir['partes']) echo '<th nowrap="nowrap">'.dica('Partes Interessadas', 'Partes interessadas d'.$config['genero_projeto'].'s '.$config['projetos'].'.').'Partes Interessadas'.dicaF().'</th>';
if ($exibir['status']) echo '<th nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($tab ? '&tab='.$tab : '').($projeto_id ? '&projeto_id='.$projeto_id : '').'&a='.$a.'&ordemPor=projeto_status&ordemDir='.$ordemDir.($projeto_expandido ? '&projeto_expandido='.$projeto_expandido : '').'\');" class="hdr">'.dica('Status d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Visualizar os Status d'.$config['genero_projeto'].'s '.$config['projetos'].'.').($ordenarPor=='projeto_status' ? imagem('icones/'.$seta[$ordemDir]) : '').'Status'.dicaF().'</a></th>';
if ($Aplic->profissional){
	$obj = new CProjeto();
	if ($exibir['relacionado']) echo '<th nowrap="nowrap">'.dica('Relacionad'.$config['genero_projeto'], 'Áreas que '.($config['genero_projeto']=='o' ? 'estes' : 'estas').' '.$config['projetos'].' estão relacionad'.$config['genero_projeto'].'s.').'Relacionad'.$config['genero_projeto'].dicaF().'</th>';
	if ($exibir['fisico_previsto']) echo '<th nowrap="nowrap">'.dica('Físico Previsto', 'A execução física prevista para a data atual.').'Físico Previsto'.dicaF().'</th>';
	if ($exibir['fisico_velocidade']) echo '<th nowrap="nowrap">'.dica('Vel.Físico', 'Velocidade do cronograma físico.').'Vel.Físico'.dicaF().'</th>';
	if ($exibir['emprego_obra'])  echo '<th nowrap="nowrap">'.dica('Emprego Durante', 'Empregos gerados durante a execução.').'Emprego Durante'.dicaF().'</th>';
	if ($exibir['emprego_direto']) echo '<th nowrap="nowrap">'.dica('Emprego Após', 'Empregos diretos após a conclusão.').'Emprego Após'.dicaF().'</th>';
	if ($exibir['emprego_indireto']) echo '<th nowrap="nowrap">'.dica('Emprego Indireto', 'Empregos indiretos após a conclusão.').'Emprego Indireto'.dicaF().'</th>';
	if ($exibir['gasto_registro']) echo '<th nowrap="nowrap">'.dica('Total Extra', 'Total de gastos extras.').'Total Extra'.dicaF().'</th>';
	if ($exibir['financeiro_previsto']) echo '<th nowrap="nowrap">'.dica('Financeiro Atual', 'Cronograma financeiro previsto até a data atual.').'Financeiro Atual'.dicaF().'</th>';
	if ($exibir['financeiro_velocidade']) echo '<th nowrap="nowrap">'.dica('Vel. Financeiro', 'Velocidade do cronograma financeiro.').'Vel. Financeiro'.dicaF().'</th>';
	if ($exibir['recurso_previsto']) echo '<th nowrap="nowrap">'.dica('Custo Recursos Atual', 'Custo de recursos alocados até a data atual.').'Custo Recursos Atual'.dicaF().'</th>';
	if ($exibir['recurso_previsto_total']) echo '<th nowrap="nowrap">'.dica('Custo Recursos Final', 'Custo de recursos alocados até o final.').'Custo Recursos Final'.dicaF().'</th>';
	if ($exibir['mo_previsto']) echo '<th nowrap="nowrap">'.dica('Custo M.O. Atual', 'Custo de mão de obra prevista até a data atual.').'Custo M.O. Atual'.dicaF().'</th>';
	if ($exibir['mo_previsto_total']) echo '<th nowrap="nowrap">'.dica('Custo M.O. Final', 'Custo de mão de obra prevista até o final.').'Custo M.O. Final'.dicaF().'</th>';
	if ($exibir['total_estimado']) echo '<th nowrap="nowrap">'.dica('Custo Total Atual', 'Custo de M.O., planilhas de custo e recursos até a data atual.').'Custo Total Atual'.dicaF().'</th>';
	if ($exibir['total_estimado_total']) echo '<th nowrap="nowrap">'.dica('Custo Total Final', 'Custo de M.O., planilhas de custo e recursos até o final.').'Custo Total Final'.dicaF().'</th>';

  $exibir_customizado = array();

  $sql->adTabela('campo_formulario');
  $sql->adCampo('campo_formulario_campo, campo_formulario_customizado_id');
  $sql->adOnde('campo_formulario_tipo = \'projetos_ex\'');

  $sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
  $sql->adOnde('campo_formulario_customizado_id IS NOT NULL AND campo_formulario_customizado_id != 0');
  $sql->adOnde('campo_formulario_ativo != 0');
  $exibir_customizado = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_customizado_id');
  $sql->limpar();

  $sql->adTabela('campo_formulario');
  $sql->adCampo('campo_formulario_campo, campo_formulario_customizado_id');
  $sql->adOnde('campo_formulario_tipo = \'projetos_ex\'');
  $sql->adOnde('campo_formulario_usuario ='.$Aplic->usuario_id);
  $sql->adOnde('campo_formulario_customizado_id IS NOT NULL AND campo_formulario_customizado_id != 0');
  $sql->adOnde('campo_formulario_ativo != 0');
  $exibir2 = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_customizado_id');
  $sql->limpar();

  $diff = array_diff_key($exibir_customizado, $exibir2);
  if($diff) $exibir_customizado = array_merge($exibir2, $diff);
  else $exibir_customizado = $exibir2;

  foreach($exibir_customizado as $cmp){
    $campo_id = (int) $cmp;
    if(isset($campos_extras[$campo_id])){
      $campo = $campos_extras[$campo_id];
      $desc = $campo['campo_descricao'];
      echo '<th nowrap="nowrap">'.dica($desc, 'Campo customizado - '.$desc).$desc.dicaF().'</th>';
      }
    }
	}


echo '<th nowrap="nowrap" width=32>'.dica(ucfirst($config['tarefas']), 'Quantidade de  '.$config['tarefas'].'.').'T'.dicaF().dica('Minhas '.ucfirst($config['tarefa']), 'Quantidade de  '.$config['tarefas'].' designadas para mim.').' M'.dicaF().'</th>';

echo '<th nowrap="nowrap" width=16>'.dica('Selecionar '.ucfirst($config['projetos']), 'Utilize as caixas abaixo para selecionar '.$config['genero_projeto'].'s '.$config['projetos'].' em que se deseja alterar o Status dos mesmos.').'S'.dicaF().'</th>';
echo '</tr>';


$nenhum = true;
foreach ($projetos as $linha){
	if ($Aplic->usuario_super_admin || permiteAcessar($linha['projeto_acesso'], $linha['projeto_id'])){
		$nenhum = false;
		$editar = ($podeEditar && permiteEditar($linha['projeto_acesso'], $linha['projeto_id']));
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		
		$estilo = (($linha['projeto_fim_atualizado'] > $linha['projeto_data_fim']) && !empty($linha['projeto_data_fim'])) ? 'style="color:red; font-weight:bold"' : '';
		$estilo2 = (($linha['projeto_inicio_atualizado'] > $linha['projeto_data_inicio']) && !empty($linha['projeto_data_inicio'])) ? 'style="color:red; font-weight:bold"' : '';
		echo '<tr id="projeto_'.$linha['projeto_id'].'" onmouseover="iluminar_tds(this, true, '.$linha['projeto_id'].')" onmouseout="iluminar_tds(this, false, '.$linha['projeto_id'].')" onclick="selecionar_projeto('.$linha['projeto_id'].')">';

		if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['projeto']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_projeto'].' '.$config['projeto'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=editar&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';


		if ($exibir['cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">&nbsp;&nbsp;</font></td>';
		echo '<td>';

		$icone='';
		if ($projeto_expandido!=$linha['projeto_id']){
			$sql->adTabela('projetos');
			$sql->adCampo('count(projeto_id)');
			$sql->adOnde('projeto_superior='.$linha['projeto_id']);
			$sql->adOnde('projeto_id!='.$linha['projeto_id']);
			$subordinados=$sql->Resultado();
			$sql->limpar();
			$icone=($subordinados > 0 ? ($projeto_expandido ? imagem('icones/subnivel.gif') : '').'<a href="javascript:void(0);" onclick="env.projeto_expandido.value='.$linha['projeto_id'].'; env.submit();">'.imagem('icones/expandir.gif', 'Ver Subordinados', 'Clique neste ícone '.imagem('icones/expandir.gif').' para expandir os projetos subordinados a este').'</a>' : ( $projeto_expandido ? imagem('icones/subnivel.gif') : ''));
			}
		else{
			$sql->adTabela('projetos');
			$sql->adCampo('projeto_superior');
			$sql->adOnde('projeto_id='.$linha['projeto_id'].' AND projeto_superior!=projeto_id');
			$superior=$sql->Resultado();
			$sql->limpar();
			$icone='<a href="javascript:void(0);" onclick="env.projeto_expandido.value='.($superior ? $superior : 0).'; env.submit();">'.imagem('icones/colapsar.gif', 'Colapsar Subordinados', 'Clique neste ícone '.imagem('icones/colapsar.gif').' para colapsar os projetos subordinados a este').'</a>';
			}
		echo $icone.link_projeto($linha["projeto_id"],'','','','','',true);
		echo '</td>';
		if ($exibir['fisico']) echo '<td width="45" align="right">'.sprintf('%.1f%%', $linha['projeto_percentagem']).'</td>';

		if ($filtro_prioridade) echo '<td align="right">'.($linha['priorizacao']).'</td>';

		if ($exibir['prioridade']) echo '<td align="center">'.prioridade($linha['projeto_prioridade'], true).'</td>';
		if ($exibir['cia']) echo '<td>'.link_cia($linha['projeto_cia']).'</td>';

		if (isset($exibir['cias']) && $exibir['cias']){
			$sql->adTabela('projeto_cia');
			$sql->adCampo('projeto_cia_cia');
			$sql->adOnde('projeto_cia_projeto = '.(int)$linha['projeto_id']);
			$cias = $sql->carregarColuna();
			$sql->limpar();
			$saida_cias='';
			if (isset($cias) && count($cias)) {
				$plural=(count($cias)>1 ? 's' : '');
				$saida_cias.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_cias.= '<tr><td style="border:0px;">'.link_cia($cias[0]);
				$qnt_cias=count($cias);
				if ($qnt_cias > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_cias; $j < $i_cmp; $j++) $lista.=link_cia($cias[$j]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.$config['organizacoes'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_cias_'.$linha['projeto_id'].'\');">(+'.($qnt_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_cias.= '</td></tr></table>';
				$plural=(count($cias)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_cias ? $saida_cias : '&nbsp;').'</td>';
			}
		if ($exibir['depts']){
			$sql->adTabela('projeto_depts');
			$sql->adCampo('departamento_id');
			$sql->adOnde('projeto_id = '.(int)$linha['projeto_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
			$saida_depts='';
			if (isset($depts) && count($depts)) {
				$plural=(count($depts)>1 ? 's' : '');
				$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_depts.= '<tr><td style="border:0px;">'.link_secao($depts[0]);
				$qnt_depts=count($depts);
				if ($qnt_depts > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_depts; $j < $i_cmp; $j++) $lista.=link_secao($depts[$j]).'<br>';
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamento'.$plural]), 'Clique para visualizar '.$config['genero_dept'].'s demais '.$config['departamento'.$plural].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_depts_'.$linha['projeto_id'].'\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_depts.= '</td></tr></table>';
				$plural=(count($depts)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_depts ? $saida_depts : '&nbsp;').'</td>';
			}
		if ($exibir['inicio']) echo '<td width="80px" nowrap="nowrap" align="center">'.($data_inicio ? $data_inicio->format($df) : '&nbsp;').'</td>';
		if ($exibir['termino']) echo '<td width="80px" nowrap="nowrap" align="center">'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td>';
		if ($exibir['provavel_inicio']) echo '<td width="80px" nowrap="nowrap" align="center">'.($linha['projeto_inicio_atualizado'] ? dica('Início Calculado', 'Clique para visualizar quais '.$config['tarefas'].' estão alterando a data de início prevista.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$linha['critica_inicio_tarefa'].'\');"><span '.$estilo2.'>'.retorna_data($linha['projeto_inicio_atualizado'], false).'</span></a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['provavel_termino']) echo '<td width="80px" nowrap="nowrap" align="center">'.($linha['projeto_fim_atualizado'] ? dica('Término Calculado', 'Clique para visualizar quais '.$config['tarefas'].' estão alterando a data de término prevista.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$linha['critica_tarefa'].'\');"><span '.$estilo.'>'.retorna_data($linha['projeto_fim_atualizado'], false).'</span></a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['problema']) echo '<td align="center">'.($linha['tarefa_log_problema'] ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m=projetos&a=ver&tab=2&projeto_id='.$linha['projeto_id'].'\');">'.imagem('icones/aviso.gif', 'Problema', 'Foi registrado ao menos um problema em uma d'.$config['genero_tarefa'].'s '.$config['tarefas'].'. Clique para ver os registros.').'</a>' : '&nbsp;').'</td>';
		if ($exibir['responsavel']) echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_responsavel'],'','','esquerda').'</td>';
		if ($exibir['supervisor']) echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_supervisor'],'','','esquerda').'</td>';
		if ($exibir['autoridade']) echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_autoridade'],'','','esquerda').'</td>';
		if ($exibir['cliente']) echo '<td nowrap="nowrap">'.link_usuario($linha['projeto_cliente'],'','','esquerda').'</td>';
		if ($exibir['custo'] || $exibir['gasto']){
			if ($config['popup_detalhado']){
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Custo</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['projeto_custo'], 2, ',', '.').'</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Gasto</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['projeto_gasto'], 2, ',', '.').'</td></tr>';
				if ((int)$linha['projeto_percentagem']!=100 && (float)$linha['projeto_percentagem']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Estimativa Final</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['projeto_gasto']*100/$linha['projeto_percentagem'], 2, ',', '.').'</td></tr>';
				$dentro .= '</table>';
				}
			}
		if ($exibir['custo'])	echo '<td align="right" nowrap="nowrap">'.($config['popup_detalhado'] ? dica('Valores', $dentro).number_format($linha['projeto_custo'], 2, ',', '.').dicaF() : number_format($linha['projeto_custo'], 2, ',', '.')).'</td>';
		if ($exibir['gasto'])	echo '<td align="right" nowrap="nowrap">'.($config['popup_detalhado'] ? dica('Valores', $dentro).number_format($linha['projeto_gasto'], 2, ',', '.').dicaF() : number_format($linha['projeto_gasto'], 2, ',', '.')).'</td>';
		if ($exibir['recursos']) echo '<td align="right" nowrap="nowrap">'.($config['popup_detalhado'] ? dica('Valores', $dentro).number_format($linha['total_recursos'], 2, ',', '.').dicaF() : number_format($linha['total_recursos'], 2, ',', '.')).'</td>';
		if ($exibir['codigo']) echo '<td>'.($linha['projeto_codigo'] ? $linha['projeto_codigo'] : '&nbsp;').'</td>';
		if ($exibir['ano']) echo '<td>'.($linha['projeto_ano'] ? $linha['projeto_ano'] : '&nbsp;').'</td>';
		if ($exibir['setor']) echo '<td>'.($linha['projeto_setor'] ? getSisValorCampo('Setor',$linha['projeto_setor']) : '&nbsp;').'</td>';
		if ($exibir['segmento']) echo '<td>'.($linha['projeto_segmento'] ? getSisValorCampo('Segmento',$linha['projeto_segmento']) : '&nbsp;').'</td>';
		if ($exibir['intervencao']) echo '<td>'.($linha['projeto_intervencao'] ? getSisValorCampo('Intervencao',$linha['projeto_intervencao']) : '&nbsp;').'</td>';
		if ($exibir['tipo_intervencao']) echo '<td>'.($linha['projeto_tipo_intervencao'] ? getSisValorCampo('TipoIntervencao',$linha['projeto_tipo_intervencao']) : '&nbsp;').'</td>';

		if ($exibir['categoria']) echo '<td>'.(isset($projeto_tipos[$linha['projeto_tipo']]) ? $projeto_tipos[$linha['projeto_tipo']] : '&nbsp;').'</td>';

		if ($exibir['url']) echo '<td>'.($linha['projeto_url'] ? $linha['projeto_url'] : '&nbsp;').'</td>';
		if ($exibir['www']) echo '<td>'.($linha['projeto_url_externa'] ? $linha['projeto_url_externa'] : '&nbsp;').'</td>';

		if ($exibir['integrantes']){
			$sql->adTabela('projeto_integrantes');
			$sql->adCampo('contato_id');
			$sql->adOnde('projeto_id = '.(int)$linha['projeto_id']);
			$sql->adOrdem('ordem');
			$integrantes = $sql->carregarColuna();
			$sql->limpar();
			$saida_integrantes='';
			if (isset($integrantes) && count($integrantes)) {
				$plural=(count($integrantes)>1 ? 's' : '');
				$saida_integrantes.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_integrantes.= '<tr><td style="border:0px;">'.link_contato($integrantes[0]);
				$qnt_integrantes=count($integrantes);
				if ($qnt_integrantes > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_integrantes; $j < $i_cmp; $j++) $lista.=link_contato($integrantes[$j]).'<br>';
					$saida_integrantes.= dica('Outros Integrantes', 'Clique para visualizar os demais integrantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_integrantes_'.$linha['projeto_id'].'\');">(+'.($qnt_integrantes - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_integrantes_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_integrantes.= '</td></tr></table>';
				$plural=(count($integrantes)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_integrantes ? $saida_integrantes : '&nbsp;').'</td>';
			}



		if ($exibir['partes']){
			$sql->adTabela('projeto_contatos');
			$sql->adCampo('contato_id');
			$sql->adOnde('projeto_id = '.(int)$linha['projeto_id']);
			$sql->adOrdem('ordem');
			$partes = $sql->carregarColuna();
			$sql->limpar();
			$saida_partes='';
			if (isset($partes) && count($partes)) {
				$plural=(count($partes)>1 ? 's' : '');
				$saida_partes.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_partes.= '<tr><td style="border:0px;">'.link_contato($partes[0]);
				$qnt_partes=count($partes);
				if ($qnt_partes > 1) {
					$lista='';
					for ($j = 1, $i_cmp = $qnt_partes; $j < $i_cmp; $j++) $lista.=link_contato($partes[$j]).'<br>';
					$saida_partes.= dica('Outras Partes Interessadas', 'Clique para visualizar as demais partes interessadas.').' <a href="javascript: void(0);" onclick="expandir_colapsar_item(\'lista_partes_'.$linha['projeto_id'].'\');">(+'.($qnt_partes - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_partes_'.$linha['projeto_id'].'"><br>'.$lista.'</span>';
					}
				$saida_partes.= '</td></tr></table>';
				$plural=(count($partes)>1 ? 's' : '');
				}
			echo '<td align="center">'.($saida_partes ? $saida_partes : '&nbsp;').'</td>';
			}
		if ($exibir['status']) echo '<td id="ignore_td_'.$linha['projeto_id'].'" style="background: '.$linha['projeto_situacao'].'" align="center" nowrap="nowrap">'.(isset($vetorStatus[$linha['projeto_status']]) ? $vetorStatus[$linha['projeto_status']] : '&nbsp;').'</td>';


		//campos da versão Pro

		if ($Aplic->profissional){

			$obj->projeto_id=(int)$linha['projeto_id'];
			if (isset($exibir['relacionado']) && $exibir['relacionado'])  {
				$sql->adTabela('projeto_gestao');
				$sql->adCampo('projeto_gestao.*');
				$sql->adOnde('projeto_gestao_projeto ='.(int)$linha['projeto_id']);
				$sql->adOrdem('projeto_gestao_ordem');
			  $gestao = $sql->Lista();
			  $sql->Limpar();
			  $usado=0;
				echo '<td align="left">';
				foreach($gestao as $gestao_data){
					if ($gestao_data['projeto_gestao_pratica']) echo ($usado++? '<br>' : '').imagem('icones/pratica_p.gif').link_pratica($gestao_data['projeto_gestao_pratica']);
					elseif ($gestao_data['projeto_gestao_acao']) echo ($usado++? '<br>' : '').imagem('icones/plano_acao_p.gif').link_acao($gestao_data['projeto_gestao_acao']);
					elseif ($gestao_data['projeto_gestao_perspectiva']) echo ($usado++? '<br>' : '').imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['projeto_gestao_perspectiva']);
					elseif ($gestao_data['projeto_gestao_tema']) echo ($usado++? '<br>' : '').imagem('icones/tema_p.png').link_tema($gestao_data['projeto_gestao_tema']);
					elseif ($gestao_data['projeto_gestao_objetivo']) echo ($usado++? '<br>' : '').imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['projeto_gestao_objetivo']);
					elseif ($gestao_data['projeto_gestao_fator']) echo ($usado++? '<br>' : '').imagem('icones/fator_p.gif').link_fator($gestao_data['projeto_gestao_fator']);
					elseif ($gestao_data['projeto_gestao_estrategia']) echo ($usado++? '<br>' : '').imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['projeto_gestao_estrategia']);
					elseif ($gestao_data['projeto_gestao_meta']) echo ($usado++? '<br>' : '').imagem('icones/meta_p.gif').link_meta($gestao_data['projeto_gestao_meta']);
					elseif ($gestao_data['projeto_gestao_canvas']) echo ($usado++? '<br>' : '').imagem('icones/canvas_p.png').link_canvas($gestao_data['projeto_gestao_canvas']);
					elseif ($gestao_data['projeto_gestao_risco']) echo ($usado++? '<br>' : '').imagem('icones/risco_p.png').link_risco($gestao_data['projeto_gestao_risco']);
					elseif ($gestao_data['projeto_gestao_risco_resposta']) echo ($usado++? '<br>' : '').imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['projeto_gestao_risco_resposta']);
					elseif ($gestao_data['projeto_gestao_indicador']) echo ($usado++? '<br>' : '').imagem('icones/indicador_p.gif').link_indicador($gestao_data['projeto_gestao_indicador']);
					elseif ($gestao_data['projeto_gestao_calendario']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_calendario($gestao_data['projeto_gestao_calendario']);
					elseif ($gestao_data['projeto_gestao_monitoramento']) echo ($usado++? '<br>' : '').imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['projeto_gestao_monitoramento']);
					elseif ($gestao_data['projeto_gestao_ata']) echo ($usado++? '<br>' : '').imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['projeto_gestao_ata']);
					elseif ($gestao_data['projeto_gestao_swot']) echo ($usado++? '<br>' : '').imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['projeto_gestao_swot']);
					elseif ($gestao_data['projeto_gestao_operativo']) echo ($usado++? '<br>' : '').imagem('icones/operativo_p.png').link_operativo($gestao_data['projeto_gestao_operativo']);
					elseif ($gestao_data['projeto_gestao_instrumento']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_instrumento($gestao_data['projeto_gestao_instrumento']);
					elseif ($gestao_data['projeto_gestao_recurso']) echo ($usado++? '<br>' : '').imagem('icones/recursos_p.gif').link_recurso($gestao_data['projeto_gestao_recurso']);
					elseif ($gestao_data['projeto_gestao_problema']) echo ($usado++? '<br>' : '').imagem('icones/problema_p.png').link_problema_pro($gestao_data['projeto_gestao_problema']);
					elseif ($gestao_data['projeto_gestao_demanda']) echo ($usado++? '<br>' : '').imagem('icones/demanda_p.gif').link_demanda($gestao_data['projeto_gestao_demanda']);
					elseif ($gestao_data['projeto_gestao_programa']) echo ($usado++? '<br>' : '').imagem('icones/programa_p.png').link_programa($gestao_data['projeto_gestao_programa']);
					elseif ($gestao_data['projeto_gestao_licao']) echo ($usado++? '<br>' : '').imagem('icones/licoes_p.gif').link_licao($gestao_data['projeto_gestao_licao']);
					elseif ($gestao_data['projeto_gestao_evento']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_evento($gestao_data['projeto_gestao_evento']);
					elseif ($gestao_data['projeto_gestao_link']) echo ($usado++? '<br>' : '').imagem('icones/links_p.gif').link_link($gestao_data['projeto_gestao_link']);
					elseif ($gestao_data['projeto_gestao_avaliacao']) echo ($usado++? '<br>' : '').imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['projeto_gestao_avaliacao']);
					elseif ($gestao_data['projeto_gestao_tgn']) echo ($usado++? '<br>' : '').imagem('icones/tgn_p.png').link_tgn($gestao_data['projeto_gestao_tgn']);
					elseif ($gestao_data['projeto_gestao_brainstorm']) echo ($usado++? '<br>' : '').imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['projeto_gestao_brainstorm']);
					elseif ($gestao_data['projeto_gestao_gut']) echo ($usado++? '<br>' : '').imagem('icones/gut_p.gif').link_gut_pro($gestao_data['projeto_gestao_gut']);
					elseif ($gestao_data['projeto_gestao_causa_efeito']) echo ($usado++? '<br>' : '').imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['projeto_gestao_causa_efeito']);
					elseif ($gestao_data['projeto_gestao_arquivo']) echo ($usado++? '<br>' : '').imagem('icones/arquivo_p.png').link_arquivo($gestao_data['projeto_gestao_arquivo']);
					elseif ($gestao_data['projeto_gestao_forum']) echo ($usado++? '<br>' : '').imagem('icones/forum_p.gif').link_forum($gestao_data['projeto_gestao_forum']);
					elseif ($gestao_data['projeto_gestao_checklist']) echo ($usado++? '<br>' : '').imagem('icones/todo_list_p.png').link_checklist($gestao_data['projeto_gestao_checklist']);
					elseif ($gestao_data['projeto_gestao_agenda']) echo ($usado++? '<br>' : '').imagem('icones/calendario_p.png').link_agenda($gestao_data['projeto_gestao_agenda']);
					elseif ($gestao_data['projeto_gestao_agrupamento']) echo ($usado++? '<br>' : '').imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['projeto_gestao_agrupamento']);
					elseif ($gestao_data['projeto_gestao_patrocinador']) echo ($usado++? '<br>' : '').imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['projeto_gestao_patrocinador']);
					elseif ($gestao_data['projeto_gestao_template']) echo ($usado++? '<br>' : '').imagem('icones/instrumento_p.png').link_template($gestao_data['projeto_gestao_template']);
					elseif ($gestao_data['projeto_gestao_painel']) echo ($usado++ ? '<br>' : '').imagem('icones/indicador_p.gif').link_painel($gestao_data['projeto_gestao_painel']);
					elseif ($gestao_data['projeto_gestao_painel_odometro']) echo ($usado++ ? '<br>' : '').imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['projeto_gestao_painel_odometro']);
					elseif ($gestao_data['projeto_gestao_painel_composicao']) echo ($usado++ ? '<br>' : '').imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['projeto_gestao_painel_composicao']);
					elseif ($gestao_data['projeto_gestao_tr']) echo ($usado++ ? '<br>' : '').imagem('icones/tr_p.png').link_tr($gestao_data['projeto_gestao_tr']);
					elseif ($gestao_data['projeto_gestao_me']) echo ($usado++ ? '<br>' : '').imagem('icones/me_p.png').link_me($gestao_data['projeto_gestao_me']);
					}
				echo '</td>';
				}


			if ($exibir['fisico_previsto'])  {
				echo '<td align="right">'.number_format($obj->fisico_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['fisico_velocidade'])  {
				echo '<td align="right">'.number_format($obj->fisico_velocidade(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['emprego_obra']) {
				echo '<td align="right">'.(int)$obj->getEmpregosObra().'</td>';
				}

			if ($exibir['emprego_direto'])  {
				echo '<td align="right">'.(int)$obj->getEmpregosDiretos().'</td>';
				}

			if ($exibir['emprego_indireto'])  {
				echo '<td align="right">'.(int)$obj->getEmpregosIndiretos().'</td>';
				}


			if ($exibir['gasto_registro'])  {
				echo '<td align="right">'.number_format($obj->gasto_registro(true), 2, ',', '.').'</td>';
				}

			if ($exibir['financeiro_previsto'])  {
				echo '<td align="right">'.number_format($obj->custo_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['financeiro_velocidade'])  {
				echo '<td align="right">'.number_format($obj->financeiro_velocidade(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['recurso_previsto'])  {
				echo '<td align="right">'.number_format($obj->recurso_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['recurso_previsto_total'])  {
				echo '<td align="right">'.number_format($obj->recurso_previsto(date('Y-m-d H:i:s'), false), 2, ',', '.').'</td>';
				}

			if ($exibir['mo_previsto'])  {
				echo '<td align="right">'.number_format($obj->mao_obra_previsto(date('Y-m-d H:i:s')), 2, ',', '.').'</td>';
				}

			if ($exibir['mo_previsto_total'])  {
				echo '<td align="right">'.number_format($obj->mao_obra_previsto(date('Y-m-d H:i:s'), false), 2, ',', '.').'</td>';
				}

			if ($exibir['total_estimado'])  {
				echo '<td align="right">'.number_format(($obj->mao_obra_previsto(date('Y-m-d H:i:s'))+$obj->recurso_previsto(date('Y-m-d H:i:s'))+$obj->custo_previsto(date('Y-m-d H:i:s'))+$obj->gasto_registro(true)), 2, ',', '.').'</td>';
				}

			if ($exibir['total_estimado_total'])  {
				echo '<td align="right">'.number_format(($obj->mao_obra_previsto(date('Y-m-d H:i:s'), '', false)+$obj->recurso_previsto(date('Y-m-d H:i:s'), '', false)+$obj->custo_estimado(true)+$obj->gasto_registro(true)), 2, ',', '.').'</td>';
				}


      foreach($exibir_customizado as $cmp){
        $campo_id = (int) $cmp;
        if(isset($campos_extras[$campo_id])){
          $campo = $campos_extras[$campo_id];
          $sql->adTabela('campos_customizados_valores');
          $sql->adCampo('valor_caractere, valor_inteiro');
          $sql->adOnde('valor_campo_id = '.$campo_id);
          $sql->adOnde('valor_objeto_id = '.$obj->projeto_id);
          $valor = $sql->linha();
          $sql->limpar();
          if(!empty($valor)){
            switch($campo['campo_tipo_html']){
              case 'textinput':
              case 'textarea':
                echo '<td>'.$valor['valor_caractere'].'</td>';
                break;
              case 'select':
                $res = '';
                if(isset($campo['lista']) && isset($campo['lista'][$valor['valor_inteiro']])){
                  $res = $campo['lista'][$valor['valor_inteiro']];
                }
                echo '<td>'.$res.'</td>';
                break;
              case 'checkbox':
                $checado = (int)$valor['valor_inteiro'];
                echo '<td style="text-align:center;">'.($checado ? 'X' : '').'</td>';
                break;
              default:
                echo '<td></td>';
              }
            }
          else{
            echo '<td></td>';
            }
          }
        }

			}


		echo '<td align="center" nowrap="nowrap">'.$linha['total_tarefas'].($linha['minhas_tarefas'] ? ' ('.$linha['minhas_tarefas'].')' : '').($linha['total_tarefas'] ? '': '&nbsp;').'</td>';
		echo '<td align="center">'.($editar ? dica('Selecionar '.ucfirst($config['projeto']), 'Marque esta caixa, caso deseje mudar o valores, status ou deslocar '.$config['genero_projeto'].' '.$config['projeto'].'.<ul><li>Após ter terminado de marcar '.$config['genero_projeto'].'s '.$config['projetos'].' selecione a opção nas caixas de opção no canto inferior.</ul>').'<input type="checkbox" name="projeto_id[]" value="'.$linha['projeto_id'].'" onclick="selecionar_projeto( '.$linha['projeto_id'].')" onfocus="estah_marcado=true;" onblur="estah_marcado=false;" id="selecao_projeto_'.$linha['projeto_id'].'" />'.dicaF() : '&nbsp;').'</td>';
		echo '</tr>';
		}
	}


if ($nenhum){
	echo '<tr><td colspan="20"><p>'.($config['genero_projeto']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['projeto'].' encontrad'.$config['genero_projeto'].'.</p></td></tr></table></td></tr>';

	if (!$dialogo && $Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'recebe_cia')) {
		$sql->adTabela('projeto_observado');
		$sql->adCampo('count(projeto_id)');
		$sql->adOnde('aprovado = 0');
		$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
		$resultado=$sql->Resultado();
		$sql->limpar();
		if ($resultado) echo '<tr><td colspan=20><table width="100%" class="std2"><tr><td>'.botao('receber ('.$resultado.')', 'Receber '.$resultado.' '.ucfirst(($resultado>1 ? $config['projetos']: $config['projeto'])),'Clique neste botão receber '.$resultado.' '.($resultado>1 ? $config['projetos']: $config['projeto']).' de outra'.($resultado>1 ? 's '.$config['organizacoes']: ' '.$config['organizacao']).'.','','document.frm.a.value=\'receber_projeto\'; document.frm.tab.value=0; document.frm.submit();').'</td></tr></table></td></tr>';
		}

	}
else {
		echo '</table></td></tr>';

		if (!$dialogo){
			echo '<tr><td colspan=20><table width="100%" class="std2" cellspacing=0 cellpadding=0>';
			echo '<tr><td align=right><table cellspacing=0 cellpadding=0><tr>';
			if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'envia_cia')) echo '<td>'.botao('enviar', 'Enviar Para Outr'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste botão para enviar '.$config['genero_projeto'].'s '.$config['projetos'].' acima selecionad'.$config['genero_projeto'].'s para outr'.$config['genero_organizacao'].' '.$config['organizacao'].'.','','enviar()').'</td>';

			if ($Aplic->profissional) echo '<td>'.botao('valores', 'Mudar Valores d'.$config['genero_projeto'].'s '.ucfirst($config['projetos']), ucfirst($config['genero_projeto']).'s '.$config['projetos'].' selecionad'.$config['genero_projeto'].'s poderão ter valores de campos como responsável, duração, ínicio, término, etc. modificados todos de uma única vez.','','valores_projetos();').'</td>';


			if ($Aplic->checarModulo('projetos', 'acesso', $Aplic->usuario_id, 'recebe_cia')) {
				$sql->adTabela('projeto_observado');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('aprovado = 0');
				$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
				$resultado=$sql->Resultado();
				$sql->limpar();
				if ($resultado) echo '<td>'.botao('receber ('.$resultado.')', 'Receber '.$resultado.' '.ucfirst(($resultado>1 ? $config['projetos']: $config['projeto'])),'Clique neste botão receber '.$resultado.' '.($resultado>1 ? $config['projetos']: $config['projeto']).' de outra'.($resultado>1 ? 's '.$config['organizacoes']: ' '.$config['organizacao']).'.','','document.frm.a.value=\'receber_projeto\'; document.frm.tab.value=0; document.frm.submit();').'</td>';

				if ((count($projetos_status)==$tab)){
					$sql->adTabela('projeto_observado');
					$sql->adCampo('count(projeto_id)');
					$sql->adOnde('aprovado = 1');
					$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
					$resultado=$sql->Resultado();
					$sql->limpar();
					if ($resultado) echo '<td>'.botao('administrar recebidos','Administrar Recebidos' ,'Clique neste botão para administrar '.($resultado>1 ? $config['genero_projeto'].'s '.$config['projetos'].' recebid'.$config['genero_projeto'].'s de outr'.$config['genero_organizacao'].'s '.$config['organizacoes'].'.': $config['genero_projeto'].' '.$config['projeto'].' recebid'.$config['genero_projeto'].' de outr'.$config['genero_organizacao'].' '.$config['organizacao']),'','document.frm.a.value=\'administrar_projetos\'; document.frm.tab.value=0; document.frm.submit();').'</td>';
					}

				}

			//echo '<td align="right">'.dica('Modificar o Status', 'Modificar o status d'.$config['genero_projeto'].'s '.$config['projetos'].' acima selecionad'.$config['genero_projeto'].'s').'Status: '.dicaF().selecionaVetor($vetorStatus, 'projeto_status', 'size="1" class="texto" onChange="mudarStatus();"').'&nbsp;&nbsp;&nbsp;&nbsp;';
			echo '<td align="right">'.dica('Deslocar no Tempo '.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Deslocar todas as datas d'.$config['genero_projeto'].'s '.$config['projetos'].' acima selecionad'.$config['genero_projeto'].'s com '.$config['genero_tarefa'].'s respectiv'.$config['genero_tarefa'].'s '.$config['tarefas'].'.').'Deslocar: '.dicaF().selecionaVetor($mover, 'mover_semanas', 'size="1" class="texto" onChange="deslocar();"');
			echo '<input type="hidden" name="atualizar_projeto_status" id="atualizar_projeto_status" value="" />';
			echo '<input type="hidden" name="modificar_datas_projeto" id="modificar_datas_projeto" value="" />';
			echo'</td></tr></table></td></tr>';

			echo '</td></tr></table>';
			}
		}

if (!$dialogo){
	echo '<tr><td colspan="20"><table border=0 cellpadding=0 cellspacing=0 class="std2" width="100%"><tr>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffffff;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Previsto', ucfirst($config['projeto']).' previsto é quando a data de ínicio d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' ainda não passou.').'&nbsp;'.ucfirst($config['projeto']).' para o futuro'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #e6eedd;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Iniciad'.$config['genero_projeto'].' e Dentro do Prazo', ucfirst($config['projeto']).' iniciad'.$config['genero_projeto'].' e dentro do prazo é quando a data de ínicio d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' já ocorreu, e '.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' já está acima de 0% executad'.$config['genero_projeto'].', entretanto ainda não se chegou na data de término.').'&nbsp;Iniciad'.$config['genero_projeto'].' e dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffeebb;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['projeto']).' que Deveria ter Iniciad'.$config['genero_projeto'], ucfirst($config['projeto']).' deveria ter iniciad'.$config['genero_projeto'].' é quando a data de ínicio d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' já ocorreu, entretanto ainda se encontra em 0% executad'.$config['genero_projeto'].'.').'&nbsp;Deveria ter iniciad'.$config['genero_projeto'].dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #cc6666;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['projeto']).' em Atraso', ucfirst($config['projeto']).' em atraso é quando a data de término d'.$config['genero_projeto'].' mesm'.$config['genero_projeto'].' já ocorreu, entretanto ainda não se encontra em 100% executad'.$config['genero_projeto'].'.').'&nbsp;Em atraso'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
	echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #aaddaa;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Terminad'.$config['genero_projeto'], ucfirst($config['projeto']).' terminad'.$config['genero_projeto'].' é quando está 100% executad'.$config['genero_projeto'].'.').'&nbsp;Terminado'.dicaF().'</td>';
	echo '<td width="100%">&nbsp;</td>';
	echo '</tr></table>';
	echo '</td></tr>';
	}
echo '</table>';
echo '</form>';



echo '</table>';

?>
<script>

function valores_projetos(){
	if (verifica_selecao()>0){
		document.frm.m.value='projetos';
		document.frm.a.value='projetos_valores_pro';
		document.frm.submit();
		}
	}



function mudarStatus(){
	if (verifica_selecao()>0){
		if(confirm('Tem certeza que deseja modificar o status?')){
			document.getElementById('atualizar_projeto_status').value=1;
			document.frm.submit();
			}
		}
	}

function deslocar(){
	if (verifica_selecao()>0){
		if(confirm('Tem certeza que deseja deslocar <?php echo $config["genero_projeto"]." ".$config["projeto"]." com su".$config["genero_tarefa"]."s ".$config["tarefas"]?> no tempo?')){
			document.getElementById('modificar_datas_projeto').value=1;
			document.frm.submit();
			}
		}
	}

function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('frm').elements.length;i++) {
		if (document.getElementById('frm').elements[i].checked) j++;
		}
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um <?php echo $config['projeto']?>!");
		return 0;
		}
	}

function expandir_colapsar_item(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function enviar(){
	var achado=0;
	with(document.getElementById('frm')) {
		  for(i=0; i<elements.length; i++) if (elements[i].checked == true) {achado=1; break;};
      }
	if (achado){
		document.frm.a.value='enviar_projeto';
		document.frm.submit();
		}
	else alert('Necessita selecionar ao menos um<?php echo ($config["genero_projeto"]=="a" ? "a" : "")." ".$config["projeto"]?>');
	}

</script>
