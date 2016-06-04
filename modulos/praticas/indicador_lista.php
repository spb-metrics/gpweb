<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


$sql = new BDConsulta();

$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;

if (isset($_REQUEST['tab'])) $Aplic->setEstado('IndicadorListaTab', getParam($_REQUEST, 'tab', null));
$tab = ($Aplic->getEstado('IndicadorListaTab') !== null ? $Aplic->getEstado('IndicadorListaTab') : 0);

if (isset($_REQUEST['indicadortextobusca'])) $Aplic->setEstado('indicadortextobusca', getParam($_REQUEST, 'indicadortextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('indicadortextobusca') ? $Aplic->getEstado('indicadortextobusca') : '');

if (isset($_REQUEST['favorito_id'])) $Aplic->setEstado('indicador_favorito', getParam($_REQUEST, 'favorito_id', null));
$favorito_id = $Aplic->getEstado('indicador_favorito') !== null ? $Aplic->getEstado('indicador_favorito') : 0;


if (isset($_REQUEST['pratica_indicador_tipo'])) $Aplic->setEstado('pratica_indicador_tipo', getParam($_REQUEST, 'pratica_indicador_tipo', null));
$pratica_indicador_tipo = $Aplic->getEstado('pratica_indicador_tipo') !== null ? $Aplic->getEstado('pratica_indicador_tipo') : 0;


if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

if (isset($_REQUEST['cia_dept']) && $_REQUEST['cia_dept'])	$Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_dept', null));
else if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = $Aplic->getEstado('dept_id') !== null || isset($_REQUEST['dept_id']) ? $Aplic->getEstado('dept_id') : $Aplic->usuario_dept;

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));
if ($dept_id) $ver_subordinadas = null;

$anos=array(null=>'');
for ($i=date('Y'); $i > date('Y')-50; $i--) $anos[$i]=(int)$i;

if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) ? $Aplic->getEstado('IdxIndicadorAno') : null);





$lista_cias='';
if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}

if (isset($_REQUEST['ver_dept_subordinados'])) $Aplic->setEstado('ver_dept_subordinados', getParam($_REQUEST, 'ver_dept_subordinados', null));
$ver_dept_subordinados = ($Aplic->getEstado('ver_dept_subordinados') !== null ? $Aplic->getEstado('ver_dept_subordinados') : (($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) ? $Aplic->usuario_prefs['ver_dept_subordinados'] : 0));
if ($ver_subordinadas) $ver_dept_subordinados=0;

$lista_depts='';
if ($ver_dept_subordinados){
	$vetor_depts=array();
	lista_depts_subordinados($dept_id, $vetor_depts);
	$vetor_depts[]=$dept_id;
	$lista_depts=implode(',',$vetor_depts);
	}

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = $Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0;


$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$indicador_expandido = getParam($_REQUEST, 'indicador_expandido', 0);

if (isset($_REQUEST['somente_superiores'])) $Aplic->setEstado('somente_superiores', getParam($_REQUEST, 'somente_superiores', null));
$somente_superiores = $Aplic->getEstado('somente_superiores') !== null ? $Aplic->getEstado('somente_superiores') : 0;

if (isset($_REQUEST['tarefa_id'])) $Aplic->setEstado('tarefa_id', getParam($_REQUEST,'tarefa_id', null));
$tarefa_id  = $Aplic->getEstado('tarefa_id', null);

if (isset($_REQUEST['projeto_id'])) $Aplic->setEstado('projeto_id', getParam($_REQUEST,'projeto_id', null));
$projeto_id  = $Aplic->getEstado('projeto_id', null);

if (isset($_REQUEST['pg_perspectiva_id'])) $Aplic->setEstado('pg_perspectiva_id', getParam($_REQUEST,'pg_perspectiva_id', null));
$pg_perspectiva_id  = $Aplic->getEstado('pg_perspectiva_id', null);

if (isset($_REQUEST['tema_id'])) $Aplic->setEstado('tema_id', getParam($_REQUEST,'tema_id', null));
$tema_id  = $Aplic->getEstado('tema_id', null);

if (isset($_REQUEST['pg_objetivo_estrategico_id'])) $Aplic->setEstado('pg_objetivo_estrategico_id', getParam($_REQUEST,'pg_objetivo_estrategico_id', null));
$pg_objetivo_estrategico_id  = $Aplic->getEstado('pg_objetivo_estrategico_id', null);

if (isset($_REQUEST['pg_fator_critico_id'])) $Aplic->setEstado('pg_fator_critico_id', getParam($_REQUEST,'pg_fator_critico_id', null));
$pg_fator_critico_id  = $Aplic->getEstado('pg_fator_critico_id', null);

if (isset($_REQUEST['pg_estrategia_id'])) $Aplic->setEstado('pg_estrategia_id', getParam($_REQUEST,'pg_estrategia_id', null));
$pg_estrategia_id = $Aplic->getEstado('pg_estrategia_id', null);

if (isset($_REQUEST['pg_meta_id'])) $Aplic->setEstado('pg_meta_id', getParam($_REQUEST,'pg_meta_id', null));
$pg_meta_id  = $Aplic->getEstado('pg_meta_id', null);

if (isset($_REQUEST['pratica_id'])) $Aplic->setEstado('pratica_id', getParam($_REQUEST,'pratica_id', null));
$pratica_id  = $Aplic->getEstado('pratica_id', null);

if (isset($_REQUEST['plano_acao_id'])) $Aplic->setEstado('plano_acao_id', getParam($_REQUEST,'plano_acao_id', null));
$plano_acao_id  = $Aplic->getEstado('plano_acao_id', null);

if (isset($_REQUEST['canvas_id'])) $Aplic->setEstado('canvas_id', getParam($_REQUEST,'canvas_id', null));
$canvas_id  = $Aplic->getEstado('canvas_id', null);

if (isset($_REQUEST['risco_id'])) $Aplic->setEstado('risco_id', getParam($_REQUEST,'risco_id', null));
$risco_id = $Aplic->getEstado('risco_id', null);

if (isset($_REQUEST['risco_resposta_id'])) $Aplic->setEstado('risco_resposta_id', getParam($_REQUEST,'risco_resposta_id', null));
$risco_resposta_id = $Aplic->getEstado('risco_resposta_id', null);

if (isset($_REQUEST['calendario_id'])) $Aplic->setEstado('calendario_id', getParam($_REQUEST,'calendario_id', null));
$calendario_id  = $Aplic->getEstado('calendario_id', null);

if (isset($_REQUEST['monitoramento_id'])) $Aplic->setEstado('monitoramento_id', getParam($_REQUEST,'monitoramento_id', null));
$monitoramento_id  = $Aplic->getEstado('monitoramento_id', null);

if (isset($_REQUEST['ata_id'])) $Aplic->setEstado('ata_id', getParam($_REQUEST,'ata_id', null));
$ata_id  = $Aplic->getEstado('ata_id', null);

if (isset($_REQUEST['swot_id'])) $Aplic->setEstado('swot_id', getParam($_REQUEST,'swot_id', null));
$swot_id  = $Aplic->getEstado('swot_id', null);

if (isset($_REQUEST['operativo_id'])) $Aplic->setEstado('operativo_id', getParam($_REQUEST,'operativo_id', null));
$operativo_id = $Aplic->getEstado('operativo_id', null);

if (isset($_REQUEST['instrumento_id'])) $Aplic->setEstado('instrumento_id', getParam($_REQUEST,'instrumento_id', null));
$instrumento_id = $Aplic->getEstado('instrumento_id', null);

if (isset($_REQUEST['recurso_id'])) $Aplic->setEstado('recurso_id', getParam($_REQUEST,'recurso_id', null));
$recurso_id = $Aplic->getEstado('recurso_id', null);

if (isset($_REQUEST['problema_id'])) $Aplic->setEstado('problema_id', getParam($_REQUEST,'problema_id', null));
$problema_id = $Aplic->getEstado('problema_id', null);

if (isset($_REQUEST['demanda_id'])) $Aplic->setEstado('demanda_id', getParam($_REQUEST,'demanda_id', null));
$demanda_id = $Aplic->getEstado('demanda_id', null);

if (isset($_REQUEST['programa_id'])) $Aplic->setEstado('programa_id', getParam($_REQUEST,'programa_id', null));
$programa_id = $Aplic->getEstado('programa_id', null);

if (isset($_REQUEST['licao_id'])) $Aplic->setEstado('licao_id', getParam($_REQUEST,'licao_id', null));
$licao_id = $Aplic->getEstado('licao_id', null);

if (isset($_REQUEST['evento_id'])) $Aplic->setEstado('evento_id', getParam($_REQUEST,'evento_id', null));
$evento_id = $Aplic->getEstado('evento_id', null);

if (isset($_REQUEST['link_id'])) $Aplic->setEstado('link_id', getParam($_REQUEST,'link_id', null));
$link_id = $Aplic->getEstado('link_id', null);

if (isset($_REQUEST['avaliacao_id'])) $Aplic->setEstado('avaliacao_id', getParam($_REQUEST,'avaliacao_id', null));
$avaliacao_id = $Aplic->getEstado('avaliacao_id', null);

if (isset($_REQUEST['tgn_id'])) $Aplic->setEstado('tgn_id', getParam($_REQUEST,'tgn_id', null));
$tgn_id = $Aplic->getEstado('tgn_id', null);

if (isset($_REQUEST['brainstorm_id'])) $Aplic->setEstado('brainstorm_id', getParam($_REQUEST,'brainstorm_id', null));
$brainstorm_id = $Aplic->getEstado('brainstorm_id', null);

if (isset($_REQUEST['gut_id'])) $Aplic->setEstado('gut_id', getParam($_REQUEST,'gut_id', null));
$gut_id = $Aplic->getEstado('gut_id', null);

if (isset($_REQUEST['causa_efeito_id'])) $Aplic->setEstado('causa_efeito_id', getParam($_REQUEST,'causa_efeito_id', null));
$causa_efeito_id = $Aplic->getEstado('causa_efeito_id', null);

if (isset($_REQUEST['arquivo_id'])) $Aplic->setEstado('arquivo_id', getParam($_REQUEST,'arquivo_id', null));
$arquivo_id = $Aplic->getEstado('arquivo_id', null);

if (isset($_REQUEST['forum_id'])) $Aplic->setEstado('forum_id', getParam($_REQUEST,'forum_id', null));
$forum_id = $Aplic->getEstado('forum_id', null);

if (isset($_REQUEST['checklist_id'])) $Aplic->setEstado('checklist_id', getParam($_REQUEST,'checklist_id', null));
$checklist_id = $Aplic->getEstado('checklist_id', null);

if (isset($_REQUEST['agenda_id'])) $Aplic->setEstado('agenda_id', getParam($_REQUEST,'agenda_id', null));
$agenda_id = $Aplic->getEstado('agenda_id', null);

if (isset($_REQUEST['agrupamento_id'])) $Aplic->setEstado('agrupamento_id', getParam($_REQUEST,'agrupamento_id', null));
$agrupamento_id = $Aplic->getEstado('agrupamento_id', null);

if (isset($_REQUEST['patrocinador_id'])) $Aplic->setEstado('patrocinador_id', getParam($_REQUEST,'patrocinador_id', null));
$patrocinador_id = $Aplic->getEstado('patrocinador_id', null);

if (isset($_REQUEST['template_id'])) $Aplic->setEstado('template_id', getParam($_REQUEST,'template_id', null));
$template_id = $Aplic->getEstado('template_id', null);

if (isset($_REQUEST['painel_id'])) $Aplic->setEstado('painel_id', getParam($_REQUEST,'painel_id', null));
$painel_id = $Aplic->getEstado('painel_id', null);

if (isset($_REQUEST['painel_odometro_id'])) $Aplic->setEstado('painel_odometro_id', getParam($_REQUEST,'painel_odometro_id', null));
$painel_odometro_id = $Aplic->getEstado('painel_odometro_id', null);

if (isset($_REQUEST['painel_composicao_id'])) $Aplic->setEstado('painel_composicao_id', getParam($_REQUEST,'painel_composicao_id', null));
$painel_composicao_id = $Aplic->getEstado('painel_composicao_id', null);

if (isset($_REQUEST['tr_id'])) $Aplic->setEstado('tr_id', getParam($_REQUEST,'tr_id', null));
$tr_id = $Aplic->getEstado('tr_id', null);

if (isset($_REQUEST['me_id'])) $Aplic->setEstado('me_id', getParam($_REQUEST,'me_id', null));
$me_id = $Aplic->getEstado('me_id', null);

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();

$criterio=getParam($_REQUEST, 'criterio',0);
$item=getParam($_REQUEST, 'item',0);

if (!$dialogo){
	$Aplic->salvarPosicao();
	$sql->adTabela('favoritos');
	$sql->adCampo('favorito_id, descricao');
	$sql->adOnde('indicador=1');
	$sql->adOnde('criador_id='.(int)$Aplic->usuario_id);
	$vetor_favoritos=$sql->ListaChave();
	$sql->limpar();

	$favoritos='';
	if (count($vetor_favoritos)) {
		$vetor_favoritos[0]='';
		$favoritos=selecionaVetor($vetor_favoritos, 'favorito_id', 'onchange="document.env.submit()" class="texto" style="width:250px;"', $favorito_id);
		}

	if ($favorito_id) $indicador_expandido=0;
	echo '<form name="env" id="env" method="post">';
	echo '<input type="hidden" name="m" value="'.$m.'" />';
	echo '<input type="hidden" name="a" value="'.$a.'" />';
	echo '<input type="hidden" name="u" value="" />';
	echo '<input type="hidden" name="cia_dept" value="" />';
	echo '<input type="hidden" name="indicador_expandido" value="'.$indicador_expandido.'" />';
	echo '<input type="hidden" name="somente_superiores" value="'.$somente_superiores.'" />';
	echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
	echo '<input type="hidden" name="ver_dept_subordinados" value="'.$ver_dept_subordinados.'" />';	
	
	
	echo '<input type="hidden" name="tarefa_id" id="tarefa_id" value="'.$tarefa_id.'" />';
	echo '<input type="hidden" name="projeto_id" id="projeto_id" value="'.$projeto_id.'" />';
	echo '<input type="hidden" name="pg_perspectiva_id" id="pg_perspectiva_id" value="'.$pg_perspectiva_id.'" />';
	echo '<input type="hidden" name="tema_id" id="tema_id" value="'.$tema_id.'" />';
	echo '<input type="hidden" name="pg_objetivo_estrategico_id" id="pg_objetivo_estrategico_id" value="'.$pg_objetivo_estrategico_id.'" />';
	echo '<input type="hidden" name="pg_fator_critico_id" id="pg_fator_critico_id" value="'.$pg_fator_critico_id.'" />';
	echo '<input type="hidden" name="pg_estrategia_id" id="pg_estrategia_id" value="'.$pg_estrategia_id.'" />';
	echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
	echo '<input type="hidden" name="pratica_id" id="pratica_id" value="'.$pratica_id.'" />';
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
	echo '<input type="hidden" name="me_id" id="me_id" value="'.$me_id.'" />';
	
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

$tipos=array(
		''=>'', 
		'popProjeto' => ucfirst($config['projeto']), 
		'popPerspectiva'=> ucfirst($config['perspectiva']), 
		'popTema'=> ucfirst($config['tema']), 
		'popObjetivo'=> ucfirst($config['objetivo']), 
		'popFator'=> ucfirst($config['fator']), 
		'popEstrategia'=> ucfirst($config['iniciativa']), 
		'popMeta'=>ucfirst($config['meta']),
		'popAcao'=> ucfirst($config['acao']),
		'popPratica' => ucfirst($config['pratica']),
		'popIndicador' => 'Indicador',
		);
	if ($ata_ativo) $tipos['popAta']='Ata de Reunião';	
	if ($swot_ativo) $tipos['popSWOT']='Campo SWOT';
	if ($operativo_ativo) $tipos['popOperativo']='Plano Operativo';
	if ($Aplic->profissional)  {
		$tipos['popCanvas']=ucfirst($config['canvas']);
		$tipos['popRisco']=ucfirst($config['risco']);
		$tipos['popRiscoResposta']=ucfirst($config['risco_resposta']);
		$tipos['popCalendario']='Agenda';
		$tipos['popMonitoramento']='Monitoramento';
		$tipos['popInstrumento']=ucfirst($config['instrumento']);
		$tipos['popRecurso']=ucfirst($config['recurso']);
		if ($problema_ativo) $tipos['popProblema']=ucfirst($config['problema']);
		$tipos['popDemanda']='Demanda';
		$tipos['popPrograma']=ucfirst($config['programa']);
		$tipos['popLicao']=ucfirst($config['licao']);
		$tipos['popEvento']='Evento';
		$tipos['popLink']='Link';
		$tipos['popAvaliacao']='Avaliação';
		$tipos['popTgn']=ucfirst($config['tgn']);
		$tipos['popBrainstorm']='Brainstorm';
		$tipos['popGut']='Matriz G.U.T.';
		$tipos['popCausa_efeito']='Diagrama de Causa-Efeito';
		$tipos['popArquivo']='Arquivo';
		$tipos['popForum']='Fórum';
		$tipos['popChecklist']='Checklist';
		$tipos['popAgenda']='Compromisso';
		if ($agrupamento_ativo) $tipos['popAgrupamento']='Agrupamento';
		if ($patrocinador_ativo) $tipos['popPatrocinador']='Patrocinador';
		$tipos['popTemplate']='Modelo';
		$tipos['popPainel']='Painel de Indicador';
		$tipos['popOdometro']='Odômetro de Indicador';
		$tipos['popComposicaoPaineis']='Composição de Painéis';
		if ($tr_ativo) $tipos['popTR']=ucfirst($config['tr']);
		if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) $tipos['popMe']=ucfirst($config['me']);	
		
		}	
	asort($tipos);


	if($plano_acao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Filtrar os indicadores pel'.$config['genero_acao'].' '.$config['acao'].' que estão relacionados.').ucfirst($config['acao']).':'.dicaF();
		$nome=nome_acao($plano_acao_id);
		}
	elseif($pratica_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Filtrar os indicadores pel'.$config['genero_pratica'].' '.$config['pratica'].' que estão relacionados.').ucfirst($config['pratica']).':'.dicaF();
		$nome=nome_pratica($pratica_id);
		}
	elseif($calendario_id){
		$legenda_filtro=dica('Filtrar pela Agenda', 'Filtrar os indicadores pela agenda que estão relacionados.').'Agenda:'.dicaF();
		$nome=nome_calendario($calendario_id);
		}
	elseif($projeto_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'Filtrar os indicadores pel'.$config['genero_projeto'].' '.$config['projeto'].' que estão relacionados.').ucfirst($config['projeto']).':'.dicaF();
		$nome=nome_projeto($projeto_id);
		}
	elseif($pg_objetivo_estrategico_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_objetivo'].' '.ucfirst($config['objetivo']).'', 'Filtrar os indicadores pel'.$config['genero_objetivo'].' '.$config['objetivo'].' que estão relacionados.').''.ucfirst($config['objetivo']).':'.dicaF();
		$nome=nome_objetivo($pg_objetivo_estrategico_id);
		}
	elseif($tema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tema'].' '.ucfirst($config['tema']).'', 'Filtrar os indicadores pel'.$config['genero_tema'].' '.$config['tema'].' que estão relacionados.').ucfirst($config['tema']).':'.dicaF();
		$nome=nome_tema($tema_id);
		}
	elseif($pg_estrategia_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_iniciativa'].' '.ucfirst($config['iniciativa']), 'Filtrar os indicadores pel'.$config['genero_iniciativa'].' '.$config['iniciativa'].' que estão relacionados.').ucfirst($config['iniciativa']).':'.dicaF();
		$nome=nome_estrategia($pg_estrategia_id);
		}
	elseif($pg_perspectiva_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_perspectiva'].' '.ucfirst($config['perspectiva']), 'Filtrar os indicadores pel'.$config['genero_perspectiva'].' '.$config['perspectiva'].' que estão relacionados.').ucfirst($config['perspectiva']).':'.dicaF();
		$nome=nome_perspectiva($pg_perspectiva_id);
		}
	elseif($canvas_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_canvas'].' '.ucfirst($config['canvas']), 'Filtrar os indicadores pel'.$config['genero_canvas'].' '.$config['canvas'].' que estão relacionados.').ucfirst($config['canvas']).':'.dicaF();
		$nome=nome_canvas($canvas_id);
		}
	elseif($pg_fator_critico_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_fator'].' '.ucfirst($config['fator']), 'Filtrar os indicadores pel'.$config['genero_fator'].' '.$config['fator'].' que estão relacionados.').ucfirst($config['fator']).':'.dicaF();
		$nome=nome_fator($pg_fator_critico_id);
		}
	elseif($pg_meta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Filtrar os indicadores pel'.$config['genero_meta'].' '.$config['meta'].' que estão relacionados.').ucfirst($config['meta']).':'.dicaF();
		$nome=nome_meta($pg_meta_id);
		}	
	elseif($risco_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco'].' '.ucfirst($config['risco']), 'Filtrar os indicadores pel'.$config['genero_risco'].' '.$config['risco'].' que estão relacionados.').ucfirst($config['risco']).':'.dicaF();
		$nome=nome_risco($risco_id);
		}
	elseif($risco_resposta_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_risco_resposta'].' '.ucfirst($config['risco_resposta']), 'Filtrar os indicadores pel'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].' que estão relacionados.').ucfirst($config['risco_resposta']).':'.dicaF();
		$nome=nome_risco_resposta($risco_resposta_id);
		}	
	elseif($monitoramento_id){
		$legenda_filtro=dica('Filtrar pelo Monitoramento', 'Filtrar os indicadores pelo monitoramento que estão relacionados.').'Monitoramento:'.dicaF();
		$nome=nome_monitoramento($monitoramento_id);
		}		
	elseif($swot_id){
		$legenda_filtro=dica('Filtrar pela Matriz SWOT', 'Filtrar os indicadores pela matriz SWOT que estão relacionados.').'Matriz SWOT:'.dicaF();
		$nome=nome_swot($swot_id);
		}	
	elseif($operativo_id){
		$legenda_filtro=dica('Filtrar pelo Plano Operativo', 'Filtrar os indicadores pelo plano operativo que estão relacionados.').'Plano Operativo:'.dicaF();
		$nome=nome_operativo($operativo_id);
		}			
	elseif($instrumento_id){
		$legenda_filtro=dica('Filtrar pelo Instrumento Jurídico', 'Filtrar os indicadores pelo instrumento jurídico que estão relacionados.').'Instrumento Jurídico:'.dicaF();
		$nome=nome_instrumento($instrumento_id);
		}	
	elseif($recurso_id){
		$legenda_filtro=dica('Filtrar pelo Recurso', 'Filtrar os indicadores pelo recurso que estão relacionados.').'Recurso:'.dicaF();
		$nome=nome_recurso($recurso_id);
		}	
	elseif($problema_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_problema'].' '.ucfirst($config['problema']), 'Filtrar os indicadores pel'.$config['genero_problema'].' '.$config['problema'].' que estão relacionados.').ucfirst($config['problema']).':'.dicaF();
		$nome=nome_problema($problema_id);
		}	
	elseif($demanda_id){
		$legenda_filtro=dica('Filtrar pela Demanda', 'Filtrar os indicadores pela demanda que estão relacionados.').'Demanda:'.dicaF();
		$nome=nome_demanda($demanda_id);
		}
	elseif($programa_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_programa'].' '.ucfirst($config['programa']), 'Filtrar os indicadores pel'.$config['genero_programa'].' '.$config['programa'].' que estão relacionados.').ucfirst($config['programa']).':'.dicaF();
		$nome=nome_programa($programa_id);
		}	
	elseif($licao_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_licao'].' '.ucfirst($config['licao']), 'Filtrar os indicadores pel'.$config['genero_licao'].' '.$config['licao'].' que estão relacionados.').ucfirst($config['licao']).':'.dicaF();
		$nome=nome_licao($licao_id);
		}	
	elseif($evento_id){
		$legenda_filtro=dica('Filtrar pelo Evento', 'Filtrar os indicadores pelo evento que estão relacionados.').'Evento:'.dicaF();
		$nome=nome_evento($evento_id);
		}		
	elseif($link_id){
		$legenda_filtro=dica('Filtrar pelo Link', 'Filtrar os indicadores pelo link que estão relacionados.').'Link:'.dicaF();
		$nome=nome_link($link_id);
		}
	elseif($avaliacao_id){
		$legenda_filtro=dica('Filtrar pela Avaliação', 'Filtrar os indicadores pela avaliação que estão relacionados.').'Avaliação:'.dicaF();
		$nome=nome_avaliacao($avaliacao_id);
		}
	elseif($tgn_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tgn'].' '.ucfirst($config['tgn']), 'Filtrar os indicadores pel'.$config['genero_tgn'].' '.$config['tgn'].' que estão relacionados.').ucfirst($config['tgn']).':'.dicaF();
		$nome=nome_tgn($tgn_id);
		}	
	elseif($brainstorm_id){
		$legenda_filtro=dica('Filtrar pelo Brainstorm', 'Filtrar os indicadores pelo brainstorm que estão relacionados.').'Brainstorm:'.dicaF();
		$nome=nome_brainstorm($brainstorm_id);
		}	
	elseif($gut_id){
		$legenda_filtro=dica('Filtrar pela Matriz GUT', 'Filtrar os indicadores pela matriz GUT que estão relacionados.').'Matriz GUT:'.dicaF();
		$nome=nome_gut($gut_id);
		}		
	elseif($causa_efeito_id){
		$legenda_filtro=dica('Filtrar pelo Diagrama de Causa-Efeito', 'Filtrar os indicadores pelo diagrama de causa-efeito que estão relacionados.').'Diagrama de Causa-Efeito:'.dicaF();
		$nome=nome_causa_efeito($causa_efeito_id);
		}		
	elseif($arquivo_id){
		$legenda_filtro=dica('Filtrar pelo Arquivo', 'Filtrar os indicadores pelo arquivo que estão relacionados.').'Arquivo:'.dicaF();
		$nome=nome_arquivo($arquivo_id);
		}	
	elseif($forum_id){
		$legenda_filtro=dica('Filtrar pelo Fórum', 'Filtrar os indicadores pelo fórum que estão relacionados.').'Fórum:'.dicaF();
		$nome=nome_forum($forum_id);
		}	
	elseif($checklist_id){
		$legenda_filtro=dica('Filtrar pelo Checklist', 'Filtrar os indicadores pelo checklist que estão relacionados.').'Checklist:'.dicaF();
		$nome=nome_checklist($checklist_id);
		}	
	elseif($agenda_id){
		$legenda_filtro=dica('Filtrar pelo Compromisso', 'Filtrar os indicadores pelo compromisso que estão relacionados.').'Compromisso:'.dicaF();
		$nome=nome_compromisso($agenda_id);
		}	
	elseif($agrupamento_id){
		$legenda_filtro=dica('Filtrar pelo Agrupamento', 'Filtrar os indicadores pelo agrupamento que estão relacionados.').'Agrupamento:'.dicaF();
		$nome=nome_agrupamento($agrupamento_id);
		}
	elseif($patrocinador_id){
		$legenda_filtro=dica('Filtrar pelo Patrocinador', 'Filtrar os indicadores pelo patrocinador que estão relacionados.').'Patrocinador:'.dicaF();
		$nome=nome_patrocinador($patrocinador_id);
		}
	elseif($template_id){
		$legenda_filtro=dica('Filtrar pelo Modelo', 'Filtrar os indicadores pelo modelo que estão relacionados.').'Modelo:'.dicaF();
		$nome=nome_template($template_id);
		}		
	elseif($painel_id){
		$legenda_filtro=dica('Filtrar pelo Painel', 'Filtrar pelo painel de indicador relacionado.').'Painel:'.dicaF();
		$nome=nome_painel($painel_id);
		}		
	elseif($painel_odometro_id){
		$legenda_filtro=dica('Filtrar pelo Odômetro', 'Filtrar pelo odômetro de indicador relacionado.').'Odômetro:'.dicaF();
		$nome=nome_painel_odometro($painel_odometro_id);
		}		
	elseif($painel_composicao_id){
		$legenda_filtro=dica('Filtrar pela Composição de Painéis', 'Filtrar pela composição de painéis relacionada.').'Composição de Painéis:'.dicaF();
		$nome=nome_painel_composicao($painel_composicao_id);
		}	
	elseif($tr_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_tr'].' '.ucfirst($config['tr']), 'Filtrar pel'.$config['genero_tr'].' '.$config['tr'].' que estão relacionados.').ucfirst($config['tr']).':'.dicaF();
		$nome=nome_tr($tr_id);
		}	
	elseif($me_id){
		$legenda_filtro=dica('Filtrar pel'.$config['genero_me'].' '.ucfirst($config['me']), 'Filtrar pel'.$config['genero_me'].' '.$config['me'].' relacionad'.$config['genero_me'].'.').ucfirst($config['me']).':'.dicaF();
		$nome=nome_me($me_id);
		}					
	else{
		$nome='';
		$legenda_filtro=dica('Filtrar', 'Selecione um campo para filtrar o indicadores.').'Filtro:'.dicaF();
		}

	$icone_limpar='<td><a href="javascript:void(0);" onclick="limpar_tudo(); env.submit();">'.imagem('icones/limpar_p.gif','Cancelar Filtro', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para cancelar o filtro aplicado.').'</a></td>';
	$filtros=($nome ? '<tr><td nowrap="nowrap" align="right">'.$legenda_filtro.'</td><td><input type="text" id="nome" name="nome" value="'.$nome.'" style="width:250px;" class="texto" READONLY /></td>'.$icone_limpar.'</tr>' : '');
	$popFiltro='<tr><td align="right" nowrap="nowrap">'.dica('Relacionado','A qual parte do sistema aos indicadores estão relacionados.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:250px;" class="texto" onchange="popRelacao(this.value)"').'</td></tr>';



	}

if (!$dialogo && $Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Indicadores', 'indicador.gif', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/indicador_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';
	$filtrar='<tr><td align=right><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelas opções selecionadas à esquerda.').'</a></td></tr>';
	$procuraBuffer ='<tr><td align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><input type="text" class="texto" style="width:250px;" name="indicadortextobusca" id="indicadortextobusca" onChange="document.env.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_lista&indicadortextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr>';

	$indicadores_tipos=vetor_campo_sistema('IndicadorTipo',$pratica_indicador_tipo);

	if (!$indicador_expandido){
		if (!$somente_superiores) $filtro_expandido='<tr><td><a href="javascript: void(0);" onclick ="env.somente_superiores.value=1; env.submit();">'.imagem('icones/indicador_superior.gif','Indicadores Superiores', 'Clique neste ícone '.imagem('icones/indicador_superior.gif').' para exibir apenas os indicadores superiores.').'</a></td></tr>';
		else $filtro_expandido='<tr><td><a href="javascript: void(0);" onclick ="env.somente_superiores.value=0; env.submit();">'.imagem('icones/indicador_superior_cancela.gif','Todos os Indicadores', 'Clique neste ícone '.imagem('icones/indicador_superior_cancela.gif').' para exibir todos os indicadores.').'</a></td></tr>';
		}
	else $filtro_expandido='';
	
	$pesquisa_pauta='<tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto" style="width:250px;"', $pratica_modelo_id).'</td></tr>';

	$tipo='<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Definir o tipo de indicador.').'Tipo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_indicador_tipo">'.selecionaVetor($indicadores_tipos, 'pratica_indicador_tipo', 'class="texto" size=1 style="width:250px;" onchange="mudar_indicador_tipo();"', $pratica_indicador_tipo).'</div></td></tr></table></td></tr>';
	
	

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	
	$novo_indicador=($podeAdicionar ? '<tr><td align=right>'.dica('Novo Indicador', 'Criar um novo indicador.').'<a href="javascript: void(0)" onclick="javascript:env.a.value=\'indicador_editar\'; env.submit();" ><img src="'.acharImagem('indicador_novo.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');
	
	$botao_favorito=(!$favoritos ? '<tr><td nowrap="nowrap" align=right>'.dica('Favoritos', 'Criar ou editar um grupo de indicadores favoritos, para uma rápida filtragem.').'<a href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&indicador=1\');"><img src="'.acharImagem('favorito_p.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');
	
	$combo_favorito=($favoritos ? '<tr><td align="right" nowrap="nowrap">'.dica('Favoritos', 'Selecionar um grupo de indicadores favoritos.').'Favoritos:'.dicaF().'</td><td>'.$favoritos.'</td><td nowrap="nowrap" align=right>'.dica('Favoritos', 'Criar ou editar um grupo de indicadores favoritos, para uma rápida filtragem.').'<a href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&indicador=1\');"><img src="'.acharImagem('favorito_p.png').'" border=0 width="16" heigth="16" /></a>'.dicaF().'</td></tr>' : '');
	
	$botao_excel=($Aplic->profissional ? '<tr><td align=right><a href="javascript: void(0)" onclick="exportar_excel();">'.imagem('icones/excel_p.gif', 'Exportar para Excel' , 'Clique neste ícone '.imagem('icones/excel_p.gif').' para exportar a lista de indicadores para o formato excel.').'</a>'.dicaF().'</td></tr>' : '');
	$botao_imprimir='<tr><td align=right><a href="javascript: void(0);" onclick ="imprimir_indicadores();">'.imagem('imprimir_p.png', 'Imprimir', 'Imprimir a lista de indicadores.').'</a></td></tr>';
	
	$botao_campos=($Aplic->profissional ? '<tr><td align=right><a href="javascript: void(0)" onclick="popCamposExibir();">'.imagem('icones/campos_p.gif', 'Campos' , 'Clique neste ícone '.imagem('campos_p.gif').' para escolha quais campos dos indicadores deseja exibir.').'</a>'.dicaF().'</td></tr>' : '');

	$selecao_ano='<tr><td align=right>'.dica('Seleção do Ano', 'Utilize esta opção para visualizar os dados dos indicadores no ano selecionado.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'IdxIndicadorAno', 'style="width:250px;" onchange="env.submit()" class="texto"', $ano).'</td></tr>';

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$pesquisa_pauta.$procuraBuffer.$selecao_ano.$tipo.$combo_favorito.$popFiltro.$filtros.'</table></td><td align=right><table cellspacing=0 cellpadding=0>'.$filtrar.$novo_indicador.$botao_imprimir.$filtro_expandido.$botao_favorito.$botao_excel.$botao_campos.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();

	echo '</form>';
	}
elseif (!$dialogo && !$Aplic->profissional){
	$procuraBuffer = '<td>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" style="width:250px;" name="indicadortextobusca" id="indicadortextobusca" onChange="document.env.submit();" value="'.$pesquisar_texto.'"/></td><td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_lista&indicadortextobusca=\');">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td>';
	$botoesTitulo = new CBlocoTitulo('Indicadores', 'indicador.gif', $m, $m.'.'.$a);
	if (!$indicador_expandido){
		if (!$somente_superiores) $filtro_expandido='<tr><td><a href="javascript: void(0);" onclick ="env.somente_superiores.value=1; env.submit();">'.imagem('icones/indicador_superior.gif','Indicadores Superiores', 'Clique neste ícone '.imagem('icones/indicador_superior.gif').' para exibir apenas os indicadores superiores.').'</a></td></tr>';
		else $filtro_expandido='<tr><td><a href="javascript: void(0);" onclick ="env.somente_superiores.value=0; env.submit();">'.imagem('icones/indicador_superior_cancela.gif','Todos os Indicadores', 'Clique neste ícone '.imagem('icones/indicador_superior_cancela.gif').' para exibir todos os indicadores.').'</a></td></tr>';
		}
	else $filtro_expandido=''; 	
	$indicadores_tipos=vetor_campo_sistema('IndicadorTipo',$pratica_indicador_tipo);
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td nowrap="nowrap" align="left">'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="document.env.submit()" class="texto"', $pratica_modelo_id).'</td></tr><tr>'.$procuraBuffer.'</tr><tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Definir o tipo de indicador.').'Tipo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><div id="combo_indicador_tipo">'.selecionaVetor($indicadores_tipos, 'pratica_indicador_tipo', 'class="texto" size=1 style="width:250px;" onchange="mudar_indicador_tipo();"', $pratica_indicador_tipo).'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pelo Tipo','Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pelo tipo de indicador.').'</a></td></tr></table></td></tr></table>', '', '', '');
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionad'.$config['genero_organizacao'].' a esquerda.').'</a></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.dept_id.value=\'\';  document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_organizacao'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s '.($config['genero_organizacao']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_organizacao'].'.').'</a></td>' : '').($Aplic->profissional ? '<td><input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />'.(!$dept_id ? '<a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a>' : '').'</td>' : '<input type="hidden" name="dept_id" id="dept_id" value="'.$dept_id.'" />').'</tr>'.
	($dept_id ? '<tr><td align=right>'.dica(ucfirst($config['departamento']), 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').ucfirst($config['departamento']).':</td><td><input type="text" style="width:250px;" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept($dept_id).'"></td>'.($dept_id ? '<td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif',ucfirst($config['departamento']),'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].' ou don'.$config['genero_dept'].'.').'</a></td>'.(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && !$ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '').(($Aplic->usuario_pode_dept_subordinado || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todos_depts) && $ver_dept_subordinados ? '<td><a href="javascript:void(0);" onclick="document.env.ver_dept_subordinados.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinad'.$config['genero_dept'].'s','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_dept'].'s '.$config['departamentos'].' subordinad'.$config['genero_dept'].'s '.($config['genero_dept']=='a' ? 'à' : 'ao').' selecionad'.$config['genero_dept'].'.').'</a></td>' : '') : '').'</tr>' : '');
	$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$popFiltro.$filtros.'</table>');
	if ($podeAdicionar) $botoesTitulo->adicionaCelula();
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0><tr><td>'.dica('Novo Indicador', 'Criar um novo indicador.').'<a class="botao" href="javascript: void(0)" onclick="javascript:env.a.value=\'indicador_editar\'; env.submit();" ><span>novo</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap">'.dica('Favoritos', 'Criar ou editar um grupo de indicadores favoritos, para uma rápida filtragem.').'<a class="botao" href="javascript: void(0)" onclick="url_passar(0, \'m=publico&a=favoritos&indicador=1\');"><span>favoritos</span></a>'.dicaF().'</td></tr><tr><td nowrap="nowrap">'.$favoritos.'</td></tr></table>');
	$botao_excel=($Aplic->profissional ? '<tr><td><a href="javascript: void(0)" onclick="exportar_excel();">'.imagem('icones/excel_p.gif', 'Exportar para Excel' , 'Clique neste ícone '.imagem('icones/excel_p.gif').' para exportar a lista de indicadores para o formato excel.').'</a>'.dicaF().'</td></tr>' : '');
	$botao_imprimir='<tr><td align=right><a href="javascript: void(0);" onclick ="imprimir_indicadores();">'.imagem('icones/imprimir_p.png', 'Imprimir', 'Clique neste ícone '.imagem('icones/imprimir_p.png').' para imprimir a lista de indicadores.').'</td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$botao_imprimir.$botao_excel.$filtro_expandido.'</table>');
	$botoesTitulo->mostrar();
	echo '</form>';
	}


$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_resultado');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
if ($criterio) $sql->adOnde('pratica_criterio_id='.(int)$criterio);
$praticas_criterios=$sql->Lista();
$sql->limpar();

$titulo=array();
$nomes_criterios=array();

$todos=array();
foreach ((array)$praticas_criterios as $chave => $criterio) {
	$total[$chave] = 0;
	if (!$criterio['pratica_criterio_resultado']){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		}
	else{
		$sql->adTabela('pratica_indicador_nos_marcadores');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_indicador_nos_marcadores.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		if ($item) $sql->adOnde('pratica_item_id='.(int)$item);
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('pratica_indicador_resultado=1');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		}
	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pratica_indicador.pratica_indicador_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id='.(int)$favorito_id);
		}
	elseif ($Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).') OR pratica_indicador_depts.dept_id IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}		
	elseif (!$Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}			
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
		$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	elseif ($cia_id && $lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');	


		
	if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
	$sql->adOnde('pratica_criterio_id='.(int)$criterio['pratica_criterio_id']);
	
	if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_indicador_requisito_descricao LIKE \'%'.$pesquisar_texto.'%\'');
	else if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\'');
	
	if ($Aplic->profissional){
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
		elseif ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
		elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
		elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
		elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
		elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
		elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
		elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);
		}
	else {
		if ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_projeto='.(int)$projeto_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_acao='.(int)$plano_acao_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_canvas='.(int)$canvas_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_meta='.(int)$pg_meta_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_fator='.(int)$pg_fator_critico_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_tema='.(int)$tema_id);
		}
	
	if ($pratica_indicador_tipo) $sql->adOnde('pratica_indicador_tipo="'.$pratica_indicador_tipo.'"');
	if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);
	$soma=$sql->Resultado();
	$sql->limpar();
	$nomes_criterios[] = array( 0 => (strlen($criterio['pratica_criterio_nome'])>14 ? substr($criterio['pratica_criterio_nome'], 0, 13).'.' : $criterio['pratica_criterio_nome']).($soma ? ' '.$soma : '') , 1=> $criterio['pratica_criterio_nome'].($soma ? ' '.$soma : ''));
	}
if ($pratica_modelo_id && isset($praticas_criterios[$tab-2]['pratica_criterio_resultado']) && !$praticas_criterios[$tab-2]['pratica_criterio_resultado']){
	//um dos criterios menos resultado
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
	$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id=pratica_nos_marcadores.pratica');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pratica_indicador.pratica_indicador_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id='.(int)$favorito_id);
		}
	elseif ($Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).') OR pratica_indicador_depts.dept_id IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}		
	elseif (!$Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}			
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
		$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	elseif ($cia_id && $lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');	
	
		if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_indicador_requisito_descricao LIKE \'%'.$pesquisar_texto.'%\'');
	else if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\'');
	if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
	
	if ($Aplic->profissional){
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
		elseif ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
		elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
		elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
		elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
		elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
		elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
		elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);

		}
	else {
		if ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_projeto='.(int)$projeto_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_acao='.(int)$plano_acao_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_canvas='.(int)$canvas_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_meta='.(int)$pg_meta_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_fator='.(int)$pg_fator_critico_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_tema='.(int)$tema_id);
		}
	

	if ($pratica_indicador_tipo) $sql->adOnde('pratica_indicador_tipo="'.$pratica_indicador_tipo.'"');
	if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);
	if ($usuario_id) {
		$sql->esqUnir('pratica_indicador_usuarios', 'pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id = pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel = '.(int)$usuario_id.' OR pratica_indicador_usuarios.usuario_id='.(int)$usuario_id);
		}
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_acesso, pratica_indicador_nome, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_composicao, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_externo, pratica_indicador_agrupar, pratica_indicador_acumulacao, pratica_indicador_unidade');
	
	$sql->adCampo('pratica_indicador_requisito_descricao, pratica_indicador_requisito_oque, pratica_indicador_requisito_onde, pratica_indicador_requisito_quando, pratica_indicador_requisito_como, pratica_indicador_requisito_porque,
	pratica_indicador_requisito_quanto, pratica_indicador_requisito_quem, pratica_indicador_requisito_melhorias');
	
	
	$sql->adCampo('pratica_indicador_requisito_descricao'); 
	if ($tab > 1  && isset($praticas_criterios[$tab-2]['pratica_criterio_id'])) $sql->adOnde('pratica_criterio_id='.(int)$praticas_criterios[$tab-2]['pratica_criterio_id']);
	if ($indicador_expandido) $sql->adCampo($indicador_expandido.'=pratica_indicador.pratica_indicador_id AS pai');
	$sql->adOrdem(($indicador_expandido ? 'pai DESC, ' : '').$ordenar.($ordem ? ' DESC' : ' ASC'));
	if ($tab !=1) $sql->adOnde('pratica_indicador_ativo=1');
	else $sql->adOnde('pratica_indicador_ativo=0');
	$indicadores=$sql->Lista();
	$sql->limpar();
	}
elseif ($pratica_modelo_id && isset($praticas_criterios[$tab-2]['pratica_criterio_resultado']) && $praticas_criterios[$tab-2]['pratica_criterio_resultado']){
	//resultados
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_indicador_nos_marcadores.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	if ($item) $sql->adOnde('pratica_item_id='.(int)$item); // não sei
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_resultado=1');
	
	if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
	if ($usuario_id) {
		$sql->esqUnir('pratica_indicador_usuarios', 'pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id = pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel = '.(int)$usuario_id.' OR pratica_indicador_usuarios.usuario_id='.(int)$usuario_id);
		}
	if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_indicador_requisito_descricao LIKE \'%'.$pesquisar_texto.'%\'');
	else if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\'');

	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pratica_indicador.pratica_indicador_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id='.(int)$favorito_id);
		}
	elseif ($Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).') OR pratica_indicador_depts.dept_id IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}		
	elseif (!$Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}			
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
		$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	elseif ($cia_id && $lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');	
	
		
	if ($Aplic->profissional){
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
		elseif ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
		elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
		elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
		elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
		elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
		elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
		elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);
		}
	else {
		if ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_projeto='.(int)$projeto_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_acao='.(int)$plano_acao_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_canvas='.(int)$canvas_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_meta='.(int)$pg_meta_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_fator='.(int)$pg_fator_critico_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_tema='.(int)$tema_id);
		}
	
	
	if ($pratica_indicador_tipo) $sql->adOnde('pratica_indicador_tipo="'.$pratica_indicador_tipo.'"');
	if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_acesso,  pratica_indicador_nome, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_composicao, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_externo, pratica_indicador_agrupar, pratica_indicador_acumulacao, pratica_indicador_unidade');

	$sql->adCampo('pratica_indicador_requisito_descricao, pratica_indicador_requisito_oque, pratica_indicador_requisito_onde, pratica_indicador_requisito_quando, pratica_indicador_requisito_como, pratica_indicador_requisito_porque,
	pratica_indicador_requisito_quanto, pratica_indicador_requisito_quem, pratica_indicador_requisito_melhorias');
	
	
	if ($indicador_expandido) $sql->adCampo($indicador_expandido.'=pratica_indicador.pratica_indicador_id AS pai');
	if ($tab !=1) $sql->adOnde('pratica_indicador_ativo=1');
	else $sql->adOnde('pratica_indicador_ativo=0');
	$sql->adOrdem(($indicador_expandido ? 'pai DESC, ' : '').$ordenar.($ordem ? ' DESC' : ' ASC'));
	$indicadores=$sql->Lista();
	$sql->limpar();
	}
else{

	//todos
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');

	if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
	if ($usuario_id) {
		$sql->esqUnir('pratica_indicador_usuarios', 'pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id = pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel = '.(int)$usuario_id.' OR pratica_indicador_usuarios.usuario_id='.(int)$usuario_id);
		}
	if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_indicador_requisito_descricao LIKE \'%'.$pesquisar_texto.'%\'');
	else if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\'');
	
	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pratica_indicador.pratica_indicador_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id='.(int)$favorito_id);
		}
	elseif ($Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).') OR pratica_indicador_depts.dept_id IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}		
	elseif (!$Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}			
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
		$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	elseif ($cia_id && $lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');	

	if ($Aplic->profissional){
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
		elseif ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
		elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
		elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
		elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
		elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
		elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
		elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);
		}
	else {
		if ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_projeto='.(int)$projeto_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_acao='.(int)$plano_acao_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_canvas='.(int)$canvas_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_meta='.(int)$pg_meta_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_fator='.(int)$pg_fator_critico_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_tema='.(int)$tema_id);
		}
	
	if ($pratica_indicador_tipo) $sql->adOnde('pratica_indicador_tipo="'.$pratica_indicador_tipo.'"');
	if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);
	$q = new BDConsulta();
	$q->adTabela('pratica_indicador_nos_marcadores');
	$q->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
	$q->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$q->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$q->adCampo('COUNT(pratica_indicador_nos_marcadores.pratica_marcador_id)');
	$q->adOnde('pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$q->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_acesso, pratica_indicador_nome, pratica_indicador_cor, pratica_indicador_sentido, pratica_indicador_responsavel, ('.$q->prepare().') AS qnt_marcador, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_composicao, pratica_indicador_checklist, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_externo, pratica_indicador_agrupar, pratica_indicador_acumulacao, pratica_indicador_unidade');
	
	$sql->adCampo('pratica_indicador_requisito_descricao, pratica_indicador_requisito_oque, pratica_indicador_requisito_onde, pratica_indicador_requisito_quando, pratica_indicador_requisito_como, pratica_indicador_requisito_porque,
	pratica_indicador_requisito_quanto, pratica_indicador_requisito_quem, pratica_indicador_requisito_melhorias');
	
	if ($indicador_expandido) $sql->adCampo($indicador_expandido.'=pratica_indicador.pratica_indicador_id AS pai');
	$sql->adOrdem(($indicador_expandido ? 'pai DESC, ' : '').$ordenar.($ordem ? ' DESC' : ' ASC'));
	if ($tab !=1) $sql->adOnde('pratica_indicador_ativo=1');
	else $sql->adOnde('pratica_indicador_ativo=0');
	$indicadores=$sql->Lista();
	$sql->limpar();
	$q->limpar();
	}


if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}


if (!$dialogo){
	$caixaTab = new CTabBox('m=praticas&a=indicador_lista', BASE_DIR.'/modulos/praticas/', $tab);

	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pratica_indicador.pratica_indicador_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id='.(int)$favorito_id);
		}
	elseif ($Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).') OR pratica_indicador_depts.dept_id IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}		
	elseif (!$Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}			
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
		$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	elseif ($cia_id && $lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');	

	if ($usuario_id) {
		$sql->esqUnir('pratica_indicador_usuarios', 'pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id = pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel = '.(int)$usuario_id.' OR pratica_indicador_usuarios.usuario_id='.(int)$usuario_id);
		}
	if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_indicador_requisito_descricao LIKE \'%'.$pesquisar_texto.'%\'');
	else if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\'');
	
	if ($Aplic->profissional){
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
		elseif ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
		elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
		elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
		elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
		elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
		elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
		elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);
		}
	else {
		if ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_projeto='.(int)$projeto_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_acao='.(int)$plano_acao_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_canvas='.(int)$canvas_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_meta='.(int)$pg_meta_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_fator='.(int)$pg_fator_critico_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_tema='.(int)$tema_id);
		}
	if ($pratica_indicador_tipo) $sql->adOnde('pratica_indicador_tipo="'.$pratica_indicador_tipo.'"');
	if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);
	$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
	$sql->adOnde('pratica_indicador_ativo=0');
	$soma=$sql->Resultado();
	$sql->limpar();
	array_unshift($nomes_criterios, array(0 => 'Inativos'.($soma ? ' '.$soma : '') , 1=> 'Inativos'.($soma ? ' '.$soma : '')));


	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	if ($somente_superiores && !$indicador_expandido) $sql->adOnde('pratica_indicador_superior IS NULL OR pratica_indicador_superior=pratica_indicador.pratica_indicador_id');
	if ($favorito_id){
		$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pratica_indicador.pratica_indicador_id=favoritos_lista.campo_id');
		$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id=favoritos_lista.favorito_id');
		$sql->adOnde('favoritos.favorito_id='.(int)$favorito_id);
		}
	elseif ($Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).') OR pratica_indicador_depts.dept_id IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}		
	elseif (!$Aplic->profissional && ($dept_id || $lista_depts)) {
		$sql->adOnde('pratica_indicador_dept IN ('.($lista_depts ? $lista_depts  : $dept_id).')');
		}			
	elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
		$sql->esqUnir('indicador_cia', 'indicador_cia', 'pratica_indicador.pratica_indicador_id=indicador_cia_indicador');
		$sql->adOnde('pratica_indicador_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR indicador_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
		}	
	elseif ($cia_id && !$lista_cias) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	elseif ($cia_id && $lista_cias) $sql->adOnde('pratica_indicador_cia IN ('.$lista_cias.')');	

	if ($usuario_id) {
		$sql->esqUnir('pratica_indicador_usuarios', 'pratica_indicador_usuarios', 'pratica_indicador_usuarios.pratica_indicador_id = pratica_indicador.pratica_indicador_id');
		$sql->adOnde('pratica_indicador_responsavel = '.(int)$usuario_id.' OR pratica_indicador_usuarios.usuario_id='.(int)$usuario_id);
		}
	if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_indicador_requisito_descricao LIKE \'%'.$pesquisar_texto.'%\'');
	else if ($pesquisar_texto) $sql->adOnde('pratica_indicador_nome LIKE \'%'.$pesquisar_texto.'%\'');
	
	if ($Aplic->profissional){
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		if ($tarefa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tarefa='.$tarefa_id);
		elseif ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_perspectiva='.$pg_perspectiva_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_ata='.(int)$ata_id);
		elseif ($swot_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_programa='.(int)$programa_id);
		elseif ($licao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_licao='.(int)$licao_id);
		elseif ($evento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_evento='.(int)$evento_id);
		elseif ($link_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($arquivo_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_arquivo='.(int)$arquivo_id);
		elseif ($forum_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_template='.(int)$template_id);
		elseif ($painel_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_tr='.(int)$tr_id);
		elseif ($me_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_gestao_me='.(int)$me_id);
		}
	else {
		if ($projeto_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_projeto='.(int)$projeto_id);
		elseif ($pratica_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_pratica='.(int)$pratica_id);
		elseif ($plano_acao_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_acao='.(int)$plano_acao_id);
		elseif ($pg_objetivo_estrategico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_objetivo_estrategico='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_estrategia_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_perspectiva_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($canvas_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_canvas='.(int)$canvas_id);
		elseif ($pg_meta_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_meta='.(int)$pg_meta_id);
		elseif ($pg_fator_critico_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_fator='.(int)$pg_fator_critico_id);
		elseif ($tema_id && !$indicador_expandido) $sql->adOnde('pratica_indicador_tema='.(int)$tema_id);
		}
	if ($pratica_indicador_tipo) $sql->adOnde('pratica_indicador_tipo="'.$pratica_indicador_tipo.'"');
	if ($indicador_expandido) $sql->adOnde('pratica_indicador_superior='.(int)$indicador_expandido. ' OR pratica_indicador.pratica_indicador_id='.(int)$indicador_expandido);
	$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
	$sql->adOnde('pratica_indicador_ativo=1');
	$soma=$sql->Resultado();
	$sql->limpar();
	array_unshift($nomes_criterios, array(0 => 'Ativos'.($soma ? ' '.$soma : '') , 1=> 'Ativos'.($soma ? ' '.$soma : '')));
	
	foreach ($nomes_criterios as $nome_criterio) $caixaTab->adicionar('indicadores_ver_idx',  $nome_criterio[0], true,null,$nome_criterio[1],'Clique nesta aba para visualizar este grupo de indicadores.');
	$ver_min = true;
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa('','', $tab);
	}
else include_once (BASE_DIR.'/modulos/praticas/indicadores_ver_idx.php');

?>
<script type="text/JavaScript">

function popCamposExibir(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Campos", 500, 500, 'm=projetos&a=campos_projetos_pro&dialogo=1&campo_formulario_tipo=indicadores', window.setCamposExibir, window);
	else window.open('./index.php?m=projetos&a=campos_projetos_pro&dialogo=1&campo_formulario_tipo=indicadores', 'Campos','height=400,width=400,resizable,scrollbars=yes, left=0, top=0');
	}

function setCamposExibir(){
	url_passar(0, 'm=praticas&a=indicador_lista');
	}



function limpar_filtro(){
	limpar_tudo();
	env.submit();
	}

function mudar_indicador_tipo(){
	xajax_mudar_indicador_tipo_ajax(document.getElementById('pratica_indicador_tipo').value, 'pratica_indicador_tipo', 'combo_indicador_tipo','class=texto size=1 style="width:250px;" onchange="mudar_indicador_tipo();"');
	}

function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia_id, dept_id){
	document.getElementById('cia_id').value=cia_id;
	document.getElementById('dept_id').value=dept_id;
	env.submit();
	}


function imprimir_indicadores(){
	url_passar(1, 'm=praticas&a=indicador_lista&dialogo=1');
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=(usuario_id ? usuario_id : 0);
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	env.submit();
	}



function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}



function iluminar_tds(linha,alto,id){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		if(!id)check=false;
		else{
			var f=eval('document.frm');
			var check=eval('f.selecao_projeto_'+id+'.checked')
			}
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if(alto==3) tcs[j].style.background='#FFFFCC';
				else if(alto==2||check)
				tcs[j].style.background='#FFCCCC';
				else if(alto==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#FFFFFF';
				}
			}
		}
	}

var estah_marcado;

function selecionar_projeto(id){
	var f=eval('document.frm');
	var boxObj=eval('f.elements["selecao_projeto_'+id+'"]');
	if(boxObj.checked){
		var linha=document.getElementById('projeto_'+id);
		boxObj.checked=false;
		iluminar_tds(linha,2,id);
		}
	else if(!boxObj.checked){
		var linha=document.getElementById('projeto_'+id);
		boxObj.checked=true;
		iluminar_tds(linha,3,id);
		}
	}


var nomeTab="<?php echo $caixaTab->tabs[$tab][1] ?>";

function exportar_excel(){
  url_passar(1, 'm=praticas&a=indicadores_exportar_excel_pro&sem_cabecalho=1');
	}
	
function popRelacao(relacao){
	if(relacao) eval(relacao+'()'); 
	env.tipo_relacao.value='';
	}
		

function limpar_tudo(){
	document.env.projeto_id .value = null;
	document.env.pg_perspectiva_id .value = null;
	document.env.tema_id .value = null;
	document.env.pg_objetivo_estrategico_id .value = null;
	document.env.pg_fator_critico_id .value = null;
	document.env.pg_estrategia_id.value = null;
	document.env.pg_meta_id .value = null;
	document.env.pratica_id .value = null;
	document.env.plano_acao_id .value = null;
	document.env.canvas_id .value = null;
	document.env.risco_id.value = null;
	document.env.risco_resposta_id.value = null;
	document.env.calendario_id .value = null;
	document.env.monitoramento_id .value = null;
	document.env.instrumento_id.value = null;
	document.env.recurso_id.value = null;
	document.env.problema_id.value = null;
	document.env.demanda_id.value = null;
	document.env.programa_id.value = null;
	document.env.licao_id.value = null;
	document.env.evento_id.value = null;
	document.env.link_id.value = null;
	document.env.avaliacao_id.value = null;
	document.env.tgn_id.value = null;
	document.env.brainstorm_id.value = null;
	document.env.gut_id.value = null;
	document.env.causa_efeito_id.value = null;
	document.env.arquivo_id.value = null;
	document.env.forum_id.value = null;
	document.env.checklist_id.value = null;
	document.env.agenda_id.value = null;
	document.env.template_id.value = null;
	document.env.painel_id.value = null;
	document.env.painel_odometro_id.value = null;
	document.env.painel_composicao_id.value = null;
	<?php 
	if($swot_ativo) echo 'document.env.swot_id.value = null;';
	if($ata_ativo) echo 'document.env.ata_id.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_id.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_id.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_id.value = null;';
	if($tr_ativo) echo 'document.env.tr_id.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'acesso', null, 'me')) echo 'document.env.me_id.value = null;';
	?>
	}



function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.projeto_id.value = chave;
	env.submit();
	}

	
function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pg_perspectiva_id.value = chave;
	env.submit();
	}
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.tema_id.value = chave;
	env.submit();
	}	
	
function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.pg_objetivo_estrategico_id.value = chave;
	env.submit();
	}	
	
function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.pg_fator_critico_id.value = chave;
	env.submit();
	}
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pg_estrategia_id.value = chave;
	env.submit();
	}	
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pg_meta_id.value = chave;
	env.submit();
	}	
	
function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_id.value = chave;
	env.submit();
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.plano_acao_id.value = chave;
	env.submit();
	}	
	
<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.canvas_id.value = chave;
	env.submit();
	}
<?php }?>	

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRisco(chave, valor){
	limpar_tudo();
	document.env.risco_id.value = chave;
	env.submit();
	}
<?php }?>	

<?php  if (isset($config['risco_respostas'])) { ?>	
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('cia_id').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.risco_resposta_id.value = chave;
	env.submit();
	}
<?php }?>	
	

function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('cia_id').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.calendario_id.value = chave;
	env.submit();
	}
	
function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('cia_id').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.monitoramento_id.value = chave;
	env.submit();
	}	
	
function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('cia_id').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.ata_id.value = chave;
	env.submit();
	}	
	
function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('cia_id').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.swot_id.value = chave;
	env.submit();
	}	
	
function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('cia_id').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.operativo_id.value = chave;
	env.submit();
	}		
	
function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('cia_id').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.instrumento_id.value = chave;
	env.submit();
	}	
	
function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('cia_id').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.recurso_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.problema_id.value = chave;
	env.submit();
	}
<?php } ?>

function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('cia_id').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.demanda_id.value = chave;
	env.submit();
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.programa_id.value = chave;
	env.submit();
	}	
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.licao_id.value = chave;
	env.submit();
	}

function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('cia_id').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.evento_id.value = chave;
	env.submit();
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('cia_id').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.link_id.value = chave;
	env.submit();
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('cia_id').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.avaliacao_id.value = chave;
	env.submit();
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.tgn_id.value = chave;
	env.submit();
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('cia_id').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.brainstorm_id.value = chave;
	env.submit();
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('cia_id').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.gut_id.value = chave;
	env.submit();
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('cia_id').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.causa_efeito_id.value = chave;
	env.submit();
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('cia_id').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('cia_id').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.arquivo_id.value = chave;
	env.submit();
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('cia_id').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.forum_id.value = chave;
	env.submit();
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('cia_id').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.checklist_id.value = chave;
	env.submit();
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('cia_id').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.agenda_id.value = chave;
	env.submit();
	}
	
<?php  if ($Aplic->profissional) { ?>
	
	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('cia_id').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPainel(chave, valor){
		limpar_tudo();
		document.env.painel_id.value = chave;
		env.submit();
		}		
		
	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('cia_id').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.painel_odometro_id.value = chave;
		env.submit();
		}			
		
	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('cia_id').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.painel_composicao_id.value = chave;
		env.submit();
		}		
		
	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.tr_id.value = chave;
		env.submit();
		}
	
	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('cia_id').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.agrupamento_id.value = chave;
		env.submit();
		}
	
	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('cia_id').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.patrocinador_id.value = chave;
		env.submit();
		}
		
	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('cia_id').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}
	
	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.template_id.value = chave;
		env.submit();
		}		

	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.me_id.value = chave;
		env.submit();
		}	

<?php } ?>	
		

</script>
