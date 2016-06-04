<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$pg_meta_id = intval(getParam($_REQUEST, 'pg_meta_id', 0));

require_once (BASE_DIR.'/modulos/praticas/meta.class.php');
$obj=new CMeta();
$obj->load($pg_meta_id);

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

$meta_projeto = getParam($_REQUEST, 'meta_projeto', null);
$meta_tarefa = getParam($_REQUEST, 'meta_tarefa', null);
$meta_perspectiva = getParam($_REQUEST, 'meta_perspectiva', null);
$meta_tema = getParam($_REQUEST, 'meta_tema', null);
$meta_objetivo = getParam($_REQUEST, 'meta_objetivo', null);
$meta_fator = getParam($_REQUEST, 'meta_fator', null);
$meta_estrategia = getParam($_REQUEST, 'meta_estrategia', null);
$meta_meta2 = getParam($_REQUEST, 'meta_meta2', null);
$meta_pratica = getParam($_REQUEST, 'meta_pratica', null);
$meta_acao = getParam($_REQUEST, 'meta_acao', null);
$meta_canvas = getParam($_REQUEST, 'meta_canvas', null);
$meta_risco = getParam($_REQUEST, 'meta_risco', null);
$meta_risco_resposta = getParam($_REQUEST, 'meta_risco_resposta', null);
$meta_indicador = getParam($_REQUEST, 'meta_indicador', null);
$meta_calendario = getParam($_REQUEST, 'meta_calendario', null);
$meta_monitoramento = getParam($_REQUEST, 'meta_monitoramento', null);
$meta_ata = getParam($_REQUEST, 'meta_ata', null);
$meta_swot = getParam($_REQUEST, 'meta_swot', null);
$meta_operativo = getParam($_REQUEST, 'meta_operativo', null);
$meta_instrumento = getParam($_REQUEST, 'meta_instrumento', null);
$meta_recurso = getParam($_REQUEST, 'meta_recurso', null);
$meta_problema = getParam($_REQUEST, 'meta_problema', null);
$meta_demanda = getParam($_REQUEST, 'meta_demanda', null);
$meta_programa = getParam($_REQUEST, 'meta_programa', null);
$meta_licao = getParam($_REQUEST, 'meta_licao', null);
$meta_evento = getParam($_REQUEST, 'meta_evento', null);
$meta_link = getParam($_REQUEST, 'meta_link', null);
$meta_avaliacao = getParam($_REQUEST, 'meta_avaliacao', null);
$meta_tgn = getParam($_REQUEST, 'meta_tgn', null);
$meta_brainstorm = getParam($_REQUEST, 'meta_brainstorm', null);
$meta_gut = getParam($_REQUEST, 'meta_gut', null);
$meta_causa_efeito = getParam($_REQUEST, 'meta_causa_efeito', null);
$meta_arquivo = getParam($_REQUEST, 'meta_arquivo', null);
$meta_forum = getParam($_REQUEST, 'meta_forum', null);
$meta_checklist = getParam($_REQUEST, 'meta_checklist', null);
$meta_agenda = getParam($_REQUEST, 'meta_agenda', null);
$meta_agrupamento = getParam($_REQUEST, 'meta_agrupamento', null);
$meta_patrocinador = getParam($_REQUEST, 'meta_patrocinador', null);
$meta_template = getParam($_REQUEST, 'meta_template', null);
$meta_painel = getParam($_REQUEST, 'meta_painel', null);
$meta_painel_odometro = getParam($_REQUEST, 'meta_painel_odometro', null);
$meta_painel_composicao = getParam($_REQUEST, 'meta_painel_composicao', null);
$meta_tr = getParam($_REQUEST, 'meta_tr', null);
$meta_me = getParam($_REQUEST, 'meta_me', null);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if ($Aplic->profissional && (
	$meta_projeto ||
	$meta_tarefa ||
	$meta_perspectiva ||
	$meta_tema ||
	$meta_objetivo ||
	$meta_fator ||
	$meta_estrategia ||
	$meta_meta2 ||
	$meta_pratica ||
	$meta_acao ||
	$meta_canvas ||
	$meta_risco ||
	$meta_risco_resposta ||
	$meta_indicador ||
	$meta_calendario ||
	$meta_monitoramento ||
	$meta_ata ||
	$meta_swot ||
	$meta_operativo ||
	$meta_instrumento ||
	$meta_recurso ||
	$meta_problema ||
	$meta_demanda ||
	$meta_programa ||
	$meta_licao ||
	$meta_evento ||
	$meta_link ||
	$meta_avaliacao ||
	$meta_tgn ||
	$meta_brainstorm ||
	$meta_gut ||
	$meta_causa_efeito ||
	$meta_arquivo ||
	$meta_forum ||
	$meta_checklist ||
	$meta_agenda ||
	$meta_agrupamento ||
	$meta_patrocinador ||
	$meta_template||
	$meta_painel ||
	$meta_painel_odometro ||
	$meta_painel_composicao	||
	$meta_tr ||
	$meta_me
	)){
	$sql->adTabela('cias');
	if ($meta_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($meta_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($meta_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($meta_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($meta_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($meta_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($meta_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($meta_meta2) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($meta_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($meta_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($meta_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($meta_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($meta_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($meta_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($meta_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($meta_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($meta_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($meta_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($meta_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($meta_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($meta_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($meta_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($meta_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($meta_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($meta_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($meta_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($meta_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($meta_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($meta_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($meta_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($meta_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($meta_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($meta_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($meta_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($meta_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($meta_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($meta_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($meta_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($meta_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($meta_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($meta_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($meta_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($meta_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
	elseif ($meta_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');
	
	if ($meta_tarefa) $sql->adOnde('tarefa_id = '.(int)$meta_tarefa);
	elseif ($meta_projeto) $sql->adOnde('projeto_id = '.(int)$meta_projeto);
	elseif ($meta_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$meta_perspectiva);
	elseif ($meta_tema) $sql->adOnde('tema_id = '.(int)$meta_tema);
	elseif ($meta_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$meta_objetivo);
	elseif ($meta_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$meta_fator);
	elseif ($meta_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$meta_estrategia);
	elseif ($meta_meta2) $sql->adOnde('pg_meta_id = '.(int)$meta_meta2);
	elseif ($meta_pratica) $sql->adOnde('pratica_id = '.(int)$meta_pratica);
	elseif ($meta_acao) $sql->adOnde('plano_acao_id = '.(int)$meta_acao);
	elseif ($meta_canvas) $sql->adOnde('canvas_id = '.(int)$meta_canvas);
	elseif ($meta_risco) $sql->adOnde('risco_id = '.(int)$meta_risco);
	elseif ($meta_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$meta_risco_resposta);
	elseif ($meta_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$meta_indicador);
	elseif ($meta_calendario) $sql->adOnde('calendario_id = '.(int)$meta_calendario);
	elseif ($meta_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$meta_monitoramento);
	elseif ($meta_ata) $sql->adOnde('ata_id = '.(int)$meta_ata);
	elseif ($meta_swot) $sql->adOnde('swot_id = '.(int)$meta_swot);
	elseif ($meta_operativo) $sql->adOnde('operativo_id = '.(int)$meta_operativo);
	elseif ($meta_instrumento) $sql->adOnde('instrumento_id = '.(int)$meta_instrumento);
	elseif ($meta_recurso) $sql->adOnde('recurso_id = '.(int)$meta_recurso);
	elseif ($meta_problema) $sql->adOnde('problema_id = '.(int)$meta_problema);
	elseif ($meta_demanda) $sql->adOnde('demanda_id = '.(int)$meta_demanda);
	elseif ($meta_programa) $sql->adOnde('programa_id = '.(int)$meta_programa);
	elseif ($meta_licao) $sql->adOnde('licao_id = '.(int)$meta_licao);
	elseif ($meta_evento) $sql->adOnde('evento_id = '.(int)$meta_evento);
	elseif ($meta_link) $sql->adOnde('link_id = '.(int)$meta_link);
	elseif ($meta_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$meta_avaliacao);
	elseif ($meta_tgn) $sql->adOnde('tgn_id = '.(int)$meta_tgn);
	elseif ($meta_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$meta_brainstorm);
	elseif ($meta_gut) $sql->adOnde('gut_id = '.(int)$meta_gut);
	elseif ($meta_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$meta_causa_efeito);
	elseif ($meta_arquivo) $sql->adOnde('arquivo_id = '.(int)$meta_arquivo);
	elseif ($meta_forum) $sql->adOnde('forum_id = '.(int)$meta_forum);
	elseif ($meta_checklist) $sql->adOnde('checklist_id = '.(int)$meta_checklist);
	elseif ($meta_agenda) $sql->adOnde('agenda_id = '.(int)$meta_agenda);
	elseif ($meta_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$meta_agrupamento);
	elseif ($meta_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$meta_patrocinador);
	elseif ($meta_template) $sql->adOnde('template_id = '.(int)$meta_template);
	elseif ($meta_painel) $sql->adOnde('painel_id = '.(int)$meta_painel);
	elseif ($meta_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$meta_painel_odometro);
	elseif ($meta_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$meta_painel_composicao);
	elseif ($meta_tr) $sql->adOnde('tr_id = '.(int)$meta_tr);
	elseif ($meta_me) $sql->adOnde('me_id = '.(int)$meta_me);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}

if (!$Aplic->profissional && !$pg_meta_id) {
	$obj->pg_meta_perspectiva=getParam($_REQUEST, 'pg_meta_perspectiva', null);
	$obj->pg_meta_tema=getParam($_REQUEST, 'pg_meta_tema', null);
	$obj->pg_meta_objetivo_estrategico=getParam($_REQUEST, 'pg_meta_objetivo_estrategico', null);
	$obj->pg_meta_estrategia=getParam($_REQUEST, 'pg_meta_estrategia', null);
	$obj->pg_meta_fator=getParam($_REQUEST, 'pg_meta_fator', null);
	$cia_id=0;
	if ($obj->pg_meta_objetivo_estrategico || $obj->pg_meta_tema || $obj->pg_meta_estrategia || $obj->pg_meta_fator || $obj->pg_meta_perspectiva){
		$sql->adTabela('cias');
		if ($obj->pg_meta_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
		if ($obj->pg_meta_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
		if ($obj->pg_meta_objetivo_estrategico) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
		if ($obj->pg_meta_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
		if ($obj->pg_meta_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
		if ($obj->pg_meta_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');

		if ($obj->pg_meta_tema) $sql->adOnde('tema_id = '.$obj->pg_meta_tema);
		elseif ($obj->pg_meta_perspectiva) $sql->adOnde('pg_perspectiva_id = '.$obj->pg_meta_perspectiva);
		elseif ($obj->pg_meta_objetivo_estrategico) $sql->adOnde('pg_objetivo_estrategico_id = '.$obj->pg_meta_objetivo_estrategico);
		elseif ($obj->pg_meta_estrategia) $sql->adOnde('pg_estrategia_id = '.$obj->pg_meta_estrategia);
		elseif ($obj->pg_meta_fator) $sql->adOnde('pg_fator_critico_id = '.$obj->pg_meta_fator);
		elseif ($obj->pg_meta_meta) $sql->adOnde('pg_meta_id = '.$obj->pg_meta_meta);
		$sql->adCampo('cia_id');
		$cia_id = $sql->Resultado();
		$sql->limpar();
		}
	if (!$cia_id) $cia_id = $Aplic->usuario_cia;
	}


$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;



if(!($podeEditar&& permiteEditarMeta($obj->pg_meta_acesso,$pg_meta_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');


$pg_meta_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

if ((!$podeEditar && $pg_meta_id > 0) || (!$podeAdicionar && $pg_meta_id == 0)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';

$botoesTitulo = new CBlocoTitulo(($pg_meta_id ? 'Editar ' : 'Criar ').ucfirst($config['meta']), 'meta.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$cias_selecionadas = array();
$usuarios_selecionados =array();
$depts_selecionados = array();
if ($pg_meta_id) {
	$sql->adTabela('metas_usuarios', 'metas_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('pg_meta_id = '.(int)$pg_meta_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('metas_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('pg_meta_id ='.(int)$pg_meta_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('meta_cia');
		$sql->adCampo('meta_cia_cia');
		$sql->adOnde('meta_cia_meta = '.(int)$pg_meta_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="meta_fazer_sql" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="pg_meta_id" id="pg_meta_id" value="'.$pg_meta_id.'" />';
echo '<input name="metas_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pg_meta_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="meta_cias"  id="meta_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

echo '<input type="hidden" name="uuid" id="uuid" value="'.($pg_meta_id ? null : uuid()).'" />';
echo '<input type="hidden" name="pg_meta_tipo_pontuacao_antigo" value="'.$obj->pg_meta_tipo_pontuacao.'" />';
echo '<input type="hidden" name="pg_meta_percentagem_antigo" value="'.$obj->pg_meta_percentagem.'" />';

if ($Aplic->profissional) {
	$sql->adTabela('meta_media');
	$sql->adCampo('meta_media_projeto AS projeto, meta_media_acao AS acao, meta_media_peso AS peso, meta_media_ponto AS ponto');
	$sql->adOnde('meta_media_meta='.(int)$pg_meta_id);
	$sql->adOnde('meta_media_tipo=\''.$obj->pg_meta_tipo_pontuacao.'\'');
	$lista=$sql->Lista();
	$sql->limpar();
	echo "<input type='hidden' name='meta_media' value='".serialize($lista)."' />";
	}


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'meta\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right" width=125>'.dica('Nome', 'Tod'.$config['genero_meta'].' '.$config['meta'].' necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="pg_meta_nome" value="'.$obj->pg_meta_nome.'" style="width:284px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om(($obj->pg_meta_cia ? $obj->pg_meta_cia : $cia_id), 'pg_meta_cia', 'class=texto size=1 style="width:284px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
if ($Aplic->profissional) {
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
			$saida_cias.= '<tr><td>'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			}
	else $saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por est'.($config['genero_meta']=='a' ? 'a' : 'e').' '.$config['meta'].'.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pg_meta_dept" id="pg_meta_dept" value="'.($pg_meta_id ? $obj->pg_meta_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pg_meta_id ? $obj->pg_meta_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
$saida_depts='';
if (count($depts_selecionados)) {
		$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
		$qnt_lista_depts=count($depts_selecionados);
		if ($qnt_lista_depts > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';
				$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
				}
		$saida_depts.= '</td></tr></table>';
		}
else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap" width="100">'.dica('Responsável', 'Tod'.$config['genero_meta'].' '.$config['meta'].' deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pg_meta_responsavel" name="pg_meta_responsavel" value="'.($obj->pg_meta_responsavel ? $obj->pg_meta_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pg_meta_responsavel ? $obj->pg_meta_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

$saida_usuarios='';
if (count($usuarios_selecionados)) {
		$saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_usuarios.= '<tr><td>'.link_usuario($usuarios_selecionados[0],'','','esquerda');
		$qnt_lista_usuarios=count($usuarios_selecionados);
		if ($qnt_lista_usuarios > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';
				$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
				}
		$saida_usuarios.= '</td></tr></table>';
		}
else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


if (!$Aplic->profissional){
	$tipos=array(
	''=>'', 
	'perspectiva' => $config['perspectiva'], 
	'tema' => $config['tema'], 
	'objetivo' => $config['objetivo'], 
	'fator' => $config['fator'], 
	'estrategia' => $config['iniciativa']
	);
	if ($obj->pg_meta_objetivo_estrategico) $tipo='objetivo';
	elseif ($obj->pg_meta_perspectiva) $tipo='perspectiva';
	elseif ($obj->pg_meta_tema) $tipo='tema';
	elseif ($obj->pg_meta_fator) $tipo='fator';
	elseif ($obj->pg_meta_estrategia) $tipo='estrategia';
	else $tipo='';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionado','A qual parte do '.$config['gpweb'].' está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'class="texto" onchange="mostrar2()"', $tipo).'<td></tr>';
	
	
	echo '<tr '.($obj->pg_meta_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específica de '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' perspectiva.').''.ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_meta_perspectiva" value="'.$obj->pg_meta_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($obj->pg_meta_perspectiva).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva2();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']).'','Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='a' ? 'uma' : 'um').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($obj->pg_meta_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específica de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_meta_tema" value="'.$obj->pg_meta_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($obj->pg_meta_tema).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema2();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($obj->pg_meta_objetivo_estrategico ? '' : 'style="display:none"').' id="objetivo"><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específica de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').''.ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_meta_objetivo_estrategico" id="pg_meta_objetivo_estrategico" value="'.$obj->pg_meta_objetivo_estrategico.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($obj->pg_meta_objetivo_estrategico).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo2();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($obj->pg_meta_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específica de um iniciativa, neste campo deverá constar o nome da iniciativa.').'Iniciativa:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_meta_estrategia" value="'.$obj->pg_meta_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($obj->pg_meta_estrategia).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia2();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($obj->pg_meta_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específica de um fator crítico do sucesso, neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pg_meta_fator" value="'.$obj->pg_meta_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($obj->pg_meta_fator).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator2();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
	

	echo '<input type="hidden" name="meta_projeto" value="" id="projeto" /><input type="hidden" id="projeto_nome" name="projeto_nome" value="">';
	echo '<input type="hidden" name="meta_tarefa" value="" id="tarefa" /><input type="hidden" id="tarefa_nome" name="tarefa_nome" value="">';
	echo '<input type="hidden" name="meta_pratica" value="" id="pratica" /><input type="hidden" id="pratica_nome" name="pratica_nome" value="">';
	echo '<input type="hidden" name="meta_acao" value="" id="acao" /><input type="hidden" id="acao_nome" name="acao_nome" value="">';
	echo '<input type="hidden" name="meta_indicador" value="" id="indicador" /><input type="hidden" id="indicador_nome" name="indicador_nome" value="">';
	echo '<input type="hidden" name="meta_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';
	echo '<input type="hidden" name="meta_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';
	echo '<input type="hidden" name="meta_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
	echo '<input type="hidden" name="meta_calendario" value="" id="calendario" /><input type="hidden" id="calendario_nome" name="calendario_nome" value="">';
	echo '<input type="hidden" name="meta_instrumento" value="" id="instrumento" /><input type="hidden" id="instrumento_nome" name="instrumento_nome" value="">';
	echo '<input type="hidden" name="meta_recurso" value="" id="recurso" /><input type="hidden" id="recurso_nome" name="recurso_nome" value="">';
	echo '<input type="hidden" name="meta_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
	echo '<input type="hidden" name="meta_demanda" value="" id="demanda" /><input type="hidden" id="demanda_nome" name="demanda_nome" value="">';
	echo '<input type="hidden" name="meta_programa" value="" id="programa" /><input type="hidden" id="programa_nome" name="programa_nome" value="">';
	echo '<input type="hidden" name="meta_licao" value="" id="licao" /><input type="hidden" id="licao_nome" name="licao_nome" value="">';
	echo '<input type="hidden" name="meta_evento" value="" id="evento" /><input type="hidden" id="evento_nome" name="evento_nome" value="">';
	echo '<input type="hidden" name="meta_link" value="" id="link" /><input type="hidden" id="link_nome" name="link_nome" value="">';
	echo '<input type="hidden" name="meta_avaliacao" value="" id="avaliacao" /><input type="hidden" id="avaliacao_nome" name="avaliacao_nome" value="">';
	echo '<input type="hidden" name="meta_brainstorm" value="" id="brainstorm" /><input type="hidden" id="brainstorm_nome" name="brainstorm_nome" value="">';
	echo '<input type="hidden" name="meta_gut" value="" id="gut" /><input type="hidden" id="gut_nome" name="gut_nome" value="">';
	echo '<input type="hidden" name="meta_causa_efeito" value="" id="causa_efeito" /><input type="hidden" id="causa_efeito_nome" name="causa_efeito_nome" value="">';
	echo '<input type="hidden" name="meta_arquivo" value="" id="arquivo" /><input type="hidden" id="arquivo_nome" name="arquivo_nome" value="">';
	echo '<input type="hidden" name="meta_forum" value="" id="forum" /><input type="hidden" id="forum_nome" name="forum_nome" value="">';
	echo '<input type="hidden" name="meta_checklist" value="" id="checklist" /><input type="hidden" id="checklist_nome" name="checklist_nome" value="">';
	echo '<input type="hidden" name="meta_agenda" value="" id="agenda" /><input type="hidden" id="agenda_nome" name="agenda_nome" value="">';
	echo '<input type="hidden" name="meta_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
	echo '<input type="hidden" name="meta_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
	echo '<input type="hidden" name="meta_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';
	echo '<input type="hidden" name="meta_monitoramento" value="" id="monitoramento" /><input type="hidden" id="monitoramento_nome" name="monitoramento_nome" value="">';
	echo '<input type="hidden" name="meta_tgn" value="" id="tgn" /><input type="hidden" id="tgn_nome" name="tgn_nome" value="">';
	echo '<input type="hidden" name="meta_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
	echo '<input type="hidden" name="meta_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
	echo '<input type="hidden" name="meta_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
	echo '<input type="hidden" name="meta_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
	echo '<input type="hidden" name="meta_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
	echo '<input type="hidden" name="meta_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
	echo '<input type="hidden" name="meta_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
	echo '<input type="hidden" name="meta_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
	echo '<input type="hidden" name="meta_meta2" value="" id="meta_meta2" /><input type="hidden" id="meta2_nome" name="meta2_nome" value="">';
	}
else{
	$tipos=array(
		''=>'',
		'projeto' => ucfirst($config['projeto']),
		'perspectiva'=> ucfirst($config['perspectiva']),
		'tema'=> ucfirst($config['tema']),
		'objetivo'=> ucfirst($config['objetivo']),
		'estrategia'=> ucfirst($config['iniciativa']),
		'meta2'=>ucfirst($config['meta']),
		'acao'=> ucfirst($config['acao']),
		'pratica' => ucfirst($config['pratica']),
		'indicador' => 'Indicador',
		);
	if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) $tipos['fator']=ucfirst($config['fator']);	
	if ($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	if ($ata_ativo) $tipos['ata']='Ata de Reunião';
	if ($swot_ativo) $tipos['swot']='Campo SWOT';
	if ($operativo_ativo) $tipos['operativo']='Plano Operativo';

	$tipos['canvas']=ucfirst($config['canvas']);
	$tipos['risco']=ucfirst($config['risco']);
	$tipos['risco_resposta']=ucfirst($config['risco_resposta']);
	$tipos['calendario']='Agenda';
	$tipos['monitoramento']='Monitoramento';
	$tipos['instrumento']=ucfirst($config['instrumento']);
	$tipos['recurso']=ucfirst($config['recurso']);
	if ($problema_ativo) $tipos['problema']=ucfirst($config['problema']);
	$tipos['demanda']='Demanda';
	$tipos['programa']=ucfirst($config['programa']);
	$tipos['licao']=ucfirst($config['licao']);
	$tipos['evento']='Evento';
	$tipos['link']='Link';
	$tipos['avaliacao']='Avaliação';
	$tipos['tgn']=ucfirst($config['tgn']);
	$tipos['brainstorm']='Brainstorm';
	$tipos['gut']='Matriz G.U.T.';
	$tipos['causa_efeito']='Diagrama de Causa-Efeito';
	$tipos['arquivo']='Arquivo';
	$tipos['forum']='Fórum';
	$tipos['checklist']='Checklist';
	$tipos['agenda']='Compromisso';
	if ($agrupamento_ativo) $tipos['agrupamento']='Agrupamento';
	if ($patrocinador_ativo) $tipos['patrocinador']='Patrocinador';
	$tipos['template']='Modelo';
	$tipos['painel']='Painel de Indicador';
	$tipos['painel_odometro']='Odômetro de Indicador';
	$tipos['painel_composicao']='Composição de Painéis';
	if ($tr_ativo) $tipos['tr']=ucfirst($config['tr']);

	asort($tipos);

	if ($meta_projeto) $tipo='projeto';
	elseif ($meta_pratica) $tipo='pratica';
	elseif ($meta_acao) $tipo='acao';
	elseif ($meta_objetivo) $tipo='objetivo';
	elseif ($meta_tema) $tipo='tema';
	elseif ($meta_fator) $tipo='fator';
	elseif ($meta_estrategia) $tipo='estrategia';
	elseif ($meta_meta2) $tipo='meta2';
	elseif ($meta_perspectiva) $tipo='perspectiva';
	elseif ($meta_canvas) $tipo='canvas';
	elseif ($meta_risco) $tipo='risco';
	elseif ($meta_risco_resposta) $tipo='risco_resposta';
	elseif ($meta_indicador) $tipo='meta_indicador';
	elseif ($meta_swot) $tipo='swot';
	elseif ($meta_ata) $tipo='ata';
	elseif ($meta_monitoramento) $tipo='monitoramento';
	elseif ($meta_calendario) $tipo='calendario';
	elseif ($meta_operativo) $tipo='operativo';
	elseif ($meta_instrumento) $tipo='instrumento';
	elseif ($meta_recurso) $tipo='recurso';
	elseif ($meta_problema) $tipo='problema';
	elseif ($meta_demanda) $tipo='demanda';
	elseif ($meta_programa) $tipo='programa';
	elseif ($meta_licao) $tipo='licao';
	elseif ($meta_evento) $tipo='evento';
	elseif ($meta_link) $tipo='link';
	elseif ($meta_avaliacao) $tipo='avaliacao';
	elseif ($meta_tgn) $tipo='tgn';
	elseif ($meta_brainstorm) $tipo='brainstorm';
	elseif ($meta_gut) $tipo='gut';
	elseif ($meta_causa_efeito) $tipo='causa_efeito';
	elseif ($meta_arquivo) $tipo='arquivo';
	elseif ($meta_forum) $tipo='forum';
	elseif ($meta_checklist) $tipo='checklist';
	elseif ($meta_agenda) $tipo='agenda';
	elseif ($meta_agrupamento) $tipo='agrupamento';
	elseif ($meta_patrocinador) $tipo='patrocinador';
	elseif ($meta_template) $tipo='template';
	elseif ($meta_painel) $tipo='painel';
	elseif ($meta_painel_odometro) $tipo='painel_odometro';
	elseif ($meta_painel_composicao) $tipo='painel_composicao';
	elseif ($meta_tr) $tipo='tr';
	elseif ($meta_me) $tipo='me';
	else $tipo='';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionad'.$config['genero_meta'],'A qual parte do sistema '.$config['genero_meta'].' '.$config['meta'].' está relacionad'.$config['genero_meta'].'.').'Relacionad'.$config['genero_meta'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:284px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
	echo '<tr '.($meta_projeto || $meta_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_projeto" value="'.$meta_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($meta_projeto).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
	echo '<tr '.($meta_projeto || $meta_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_tarefa" value="'.$meta_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($meta_tarefa).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_pratica" value="'.$meta_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($meta_pratica).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_acao" value="'.$meta_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($meta_acao).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_perspectiva" value="'.$meta_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($meta_perspectiva).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_tema" value="'.$meta_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($meta_tema).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_objetivo" value="'.$meta_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($meta_objetivo).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_estrategia" value="'.$meta_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($meta_estrategia).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
	
	echo '<tr '.($meta_meta2 ? '' : 'style="display:none"').' id="meta2" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']).' Relacionad'.$config['genero_meta'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_meta']=='o' ? 'um outro' : 'uma outra').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_meta2" id="meta_meta2" value="'.$meta_meta2.'" /><input type="text" id="meta2_nome" name="meta2_nome" value="'.nome_meta($meta_meta2).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta2();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';

	
	
	if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) echo '<tr '.($meta_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_fator" value="'.$meta_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($meta_fator).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_fator" value="" id="fator" /><input type="hidden" id="fator_nome" name="fator_nome" value="">';
	
	echo '<tr '.($meta_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" nowrap="nowrap">'.dica('Indicador', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_indicador" value="'.$meta_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($meta_indicador).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_monitoramento" value="'.$meta_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($meta_monitoramento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';

	if ($agrupamento_ativo) echo '<tr '.($meta_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_agrupamento" value="'.$meta_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($meta_agrupamento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

	if ($patrocinador_ativo) echo '<tr '.($meta_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_patrocinador" value="'.$meta_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($meta_patrocinador).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';

	echo '<tr '.($meta_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_template" value="'.$meta_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($meta_template).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';

	echo '<tr '.($meta_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_calendario" value="'.$meta_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($meta_calendario).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_instrumento" value="'.$meta_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($meta_instrumento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_recurso" value="'.$meta_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($meta_recurso).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
	if ($problema_ativo) echo '<tr '.($meta_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_problema" value="'.$meta_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($meta_problema).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
	echo '<tr '.($meta_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_demanda" value="'.$meta_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($meta_demanda).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_programa" value="'.$meta_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($meta_programa).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_licao" value="'.$meta_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($meta_licao).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_evento" value="'.$meta_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($meta_evento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_link" value="'.$meta_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($meta_link).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_avaliacao" value="'.$meta_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($meta_avaliacao).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_tgn" value="'.$meta_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($meta_tgn).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_brainstorm" value="'.$meta_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($meta_brainstorm).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_gut" value="'.$meta_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($meta_gut).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_causa_efeito" value="'.$meta_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($meta_causa_efeito).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_arquivo" value="'.$meta_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($meta_arquivo).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_forum" value="'.$meta_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($meta_forum).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_checklist" value="'.$meta_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($meta_checklist).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_agenda" value="'.$meta_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($meta_agenda).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';

	echo '<tr '.($meta_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_risco" value="'.$meta_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($meta_risco).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_risco_resposta" value="'.$meta_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($meta_risco_resposta).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_canvas" value="'.$meta_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($meta_canvas).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" nowrap="nowrap">'.dica('Painel de Indicador', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_painel" value="'.$meta_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($meta_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" nowrap="nowrap">'.dica('Odômetro de Indicador', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_painel_odometro" value="'.$meta_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($meta_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" nowrap="nowrap">'.dica('Composição de Painéis', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_painel_composicao" value="'.$meta_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($meta_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/painel_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/painel_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($meta_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_tr" value="'.$meta_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($meta_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
	
	if ($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me'))echo '<tr '.($meta_me ? '' : 'style="display:none"').' id="me" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_me" value="'.$meta_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($meta_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_me" value="" id="meta_me" /><input type="hidden" id="me_nome" name="me_nome" value="">';

	if ($swot_ativo) echo '<tr '.(isset($meta_swot) && $meta_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_swot" value="'.(isset($meta_swot) ? $meta_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($meta_swot) ? $meta_swot : null)).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
	if ($ata_ativo) echo '<tr '.(isset($meta_ata) && $meta_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_ata" value="'.(isset($meta_ata) ? $meta_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($meta_ata) ? $meta_ata : null)).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
	if ($operativo_ativo) echo '<tr '.($meta_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso '.$config['genero_meta'].' '.$config['meta'].' seja específic'.$config['genero_meta'].' de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="meta_operativo" value="'.$meta_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($meta_operativo).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="meta_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';

	$sql->adTabela('meta_gestao');
	$sql->adCampo('meta_gestao.*');
	$sql->adOnde('meta_gestao_meta ='.(int)$pg_meta_id);
	$sql->adOrdem('meta_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['meta_gestao_ordem'].', '.$gestao_data['meta_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['meta_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['meta_gestao_tarefa']).'</td>';
		elseif ($gestao_data['meta_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['meta_gestao_projeto']).'</td>';
		elseif ($gestao_data['meta_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['meta_gestao_pratica']).'</td>';
		elseif ($gestao_data['meta_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['meta_gestao_acao']).'</td>';
		elseif ($gestao_data['meta_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['meta_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['meta_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['meta_gestao_tema']).'</td>';
		elseif ($gestao_data['meta_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['meta_gestao_objetivo']).'</td>';
		elseif ($gestao_data['meta_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['meta_gestao_fator']).'</td>';
		elseif ($gestao_data['meta_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['meta_gestao_estrategia']).'</td>';
		elseif ($gestao_data['meta_gestao_meta2']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['meta_gestao_meta2']).'</td>';
		elseif ($gestao_data['meta_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['meta_gestao_canvas']).'</td>';
		elseif ($gestao_data['meta_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['meta_gestao_risco']).'</td>';
		elseif ($gestao_data['meta_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['meta_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['meta_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['meta_gestao_indicador']).'</td>';
		elseif ($gestao_data['meta_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['meta_gestao_calendario']).'</td>';
		elseif ($gestao_data['meta_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['meta_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['meta_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['meta_gestao_ata']).'</td>';
		elseif ($gestao_data['meta_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['meta_gestao_swot']).'</td>';
		elseif ($gestao_data['meta_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['meta_gestao_operativo']).'</td>';
		elseif ($gestao_data['meta_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['meta_gestao_instrumento']).'</td>';
		elseif ($gestao_data['meta_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['meta_gestao_recurso']).'</td>';
		elseif ($gestao_data['meta_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['meta_gestao_problema']).'</td>';
		elseif ($gestao_data['meta_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['meta_gestao_demanda']).'</td>';
		elseif ($gestao_data['meta_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['meta_gestao_programa']).'</td>';
		elseif ($gestao_data['meta_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['meta_gestao_licao']).'</td>';
		elseif ($gestao_data['meta_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['meta_gestao_evento']).'</td>';
		elseif ($gestao_data['meta_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['meta_gestao_link']).'</td>';
		elseif ($gestao_data['meta_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['meta_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['meta_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['meta_gestao_tgn']).'</td>';
		elseif ($gestao_data['meta_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['meta_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['meta_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['meta_gestao_gut']).'</td>';
		elseif ($gestao_data['meta_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['meta_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['meta_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['meta_gestao_arquivo']).'</td>';
		elseif ($gestao_data['meta_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['meta_gestao_forum']).'</td>';
		elseif ($gestao_data['meta_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['meta_gestao_checklist']).'</td>';
		elseif ($gestao_data['meta_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['meta_gestao_agenda']).'</td>';
		elseif ($gestao_data['meta_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['meta_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['meta_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['meta_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['meta_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['meta_gestao_template']).'</td>';
		elseif ($gestao_data['meta_gestao_painel']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['meta_gestao_painel']).'</td>';
		elseif ($gestao_data['meta_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['meta_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['meta_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['meta_gestao_painel_composicao']).'</td>';
		elseif ($gestao_data['meta_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['meta_gestao_tr']).'</td>';
		elseif ($gestao_data['meta_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['meta_gestao_me']).'</td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['meta_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';

	}



if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_meta = '.(int)$pg_meta_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}
else{
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_meta = '.(int)$pg_meta_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	}

if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_meta'].' '.$config['meta'].' o mais representativo da situação geral da mesma.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'pg_meta_principal_indicador', 'class="texto" style="width:267px;"', $obj->pg_meta_principal_indicador).'</td></tr>';

if ($Aplic->profissional) include_once (BASE_DIR.'/modulos/praticas/meta_editar_pro.php');

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pg_meta_cor" value="'.($obj->pg_meta_cor ? $obj->pg_meta_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pg_meta_cor ? $obj->pg_meta_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', ucfirst($config['genero_meta']).' '.$config['meta'].' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar '.$config['genero_meta'].' '.$config['meta'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para '.$config['genero_meta'].' '.$config['meta'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados para '.$config['genero_meta'].' '.$config['meta'].' ver e editar '.$config['genero_meta'].' '.$config['meta'].'</li><li><b>Privado</b> - Somente o responsável e os designados para '.$config['genero_meta'].' '.$config['meta'].' podem ver a mesma, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($pg_meta_acesso, 'pg_meta_acesso', 'class="texto"', ($pg_meta_id ? $obj->pg_meta_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';


echo '<tr><td align="right" width="100">'.dica('Ativ'.$config['genero_meta'], 'Caso '.$config['genero_meta'].' '.$config['meta'].' ainda esteja ativ'.$config['genero_meta'].' deverá estar marcado este campo.').'Ativ'.$config['genero_meta'].':'.dicaF().'</td><td><input type="checkbox" value="1" name="pg_meta_ativo" '.($obj->pg_meta_ativo || !$pg_meta_id ? 'checked="checked"' : '').' /></td></tr>';

if ($exibir['pg_meta_descricao'])  echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descrição', 'Descrição sobre '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].'.').'Descrição:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_meta_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->pg_meta_descricao.'</textarea></td></tr>';





echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Valor da Meta','Lista de valores da metas.').'&nbsp;<b>Valor da Meta</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><fieldset><table cellspacing=0 cellpadding=0>';

echo '<tr><td align="right" nowrap="nowrap" width="90">'.dica('Meta', 'Qual o valor a ser alcançado pelo indicador para que seje considerado excelente.').'Meta'.dicaF().':</td><td width="100%" colspan="2"><input type="text" id="meta_meta_valor_meta" name="meta_meta_valor_meta" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
if ($Aplic->profissional){
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível Bom', 'Qual o valor do indicador é aceitável com bom.').'Nível bom:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" id="meta_meta_valor_meta_boa" name="meta_meta_valor_meta_boa" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível Regular', 'Qual o valor do indicador é aceitável com regulr.').'Nível regular:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" id="meta_meta_valor_meta_regular" name="meta_meta_valor_meta_regular" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível Ruim', 'Qual o valor do indicador é considerado ruim.').'Nível ruim:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" id="meta_meta_valor_meta_ruim" name="meta_meta_valor_meta_ruim" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
	}
else {
	echo '<input type="hidden" id="meta_meta_valor_meta_boa" name="meta_meta_valor_meta_boa" value="" />';
	echo '<input type="hidden" id="meta_meta_valor_meta_regular" name="meta_meta_valor_meta_regular" value="" />';
	echo '<input type="hidden" id="meta_meta_valor_meta_ruim" name="meta_meta_valor_meta_ruim" value="" />';
	}
echo '<tr><td align="right" nowrap="nowrap">'.dica('Início da Meta', 'Qual a data estipulada para iniciar a utilização da meta.').'Início da meta'.dicaF().':</td><td width="100%" colspan="2"><input type="hidden" name="meta_meta_data_inicio" id="meta_meta_data_inicio" value="'.date('Y').'-01-01" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\',\'meta_meta_data_inicio\');" value="01/01/'.date('Y').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data em que a meta entrará em vigor.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data Limite para a Meta', 'Qual a data estipulada para alcançar a meta.').'Data limite da meta'.dicaF().':</td><td width="100%" colspan="2"><input type="hidden" name="meta_meta_data_fim" id="meta_meta_data_fim" value="'.date('Y').'-12-31" /><input type="text" name="data_fim" style="width:70px;" id="data_fim" onchange="setData(\'env\', \'data_fim\',\'meta_meta_data_fim\');" value="31/12/'.date('Y').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data em que a meta deverá ser alcançada.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '</table></fieldset></td>';

echo '<input type="hidden" id="meta_meta_id" name="meta_meta_id" value="" /></table></td><td id="adicionar_meta" style="display:" width=50 align=center><a href="javascript: void(0);" onclick="incluir_meta();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a meta.').'</a></td>';
echo '<td id="confirmar_meta" style="display:none" width=50 align=center><a href="javascript: void(0);" onclick="limpar_meta(); document.getElementById(\'adicionar_meta\').style.display=\'\';	document.getElementById(\'confirmar_meta\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da meta .').'</a><a href="javascript: void(0);" onclick="incluir_meta();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da meta.').'</a></td></tr></table></td></tr>';
echo '</table></td></tr>';


$sql->adTabela('meta_meta');
$sql->adCampo('formatar_data(meta_meta_data_inicio, "%d/%m/%Y") AS data, formatar_data(meta_meta_data_fim, "%d/%m/%Y") AS data_meta');
$sql->adCampo('meta_meta_id, meta_meta_valor_meta, meta_meta_valor_meta_boa, meta_meta_valor_meta_regular, meta_meta_valor_meta_ruim');
$sql->adOnde('meta_meta_meta = '.(int)$pg_meta_id);
$sql->adOrdem('meta_meta_data_inicio');
$metas = $sql->lista();
$sql->limpar();
echo '<tr><td colspan=20 align=center><div id="metas">';
if (count($metas)){
	echo '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th></th></tr>';
	foreach($metas as $linha) {
		echo '<tr>';
		echo '<td align=right>'.number_format($linha['meta_meta_valor_meta'], 2, ',', '.').'</td>';
		if ($Aplic->profissional){

			echo '<td align=right>'.($linha['meta_meta_valor_meta_boa'] != null ? number_format($linha['meta_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '<td align=right>'.($linha['meta_meta_valor_meta_regular'] != null ? number_format($linha['meta_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '<td align=right>'.($linha['meta_meta_valor_meta_ruim'] != null ? number_format($linha['meta_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
			}
		echo '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';

		echo '<td><a href="javascript: void(0);" onclick="editar_meta('.$linha['meta_meta_id'].');">'.imagem('icones/editar.gif', 'Editar Meta', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta meta.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta meta?\')) {excluir_meta('.$linha['meta_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir Meta', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta meta.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';
echo '</table></fieldset></td></tr>';






$cincow2h=($exibir['pg_meta_oque'] && $exibir['pg_meta_quem'] && $exibir['pg_meta_quando'] && $exibir['pg_meta_onde'] && $exibir['pg_meta_porque'] && $exibir['pg_meta_como'] && $exibir['pg_meta_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['pg_meta_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que', 'Sumário sobre o que se trata '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].'.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_oque.'</textarea></td></tr>';
if ($exibir['pg_meta_quem']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].'.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_quem.'</textarea></td></tr>';
if ($exibir['pg_meta_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quando', 'Quando '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].' é executad'.$config['genero_meta'].'.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_quando.'</textarea></td></tr>';
if ($exibir['pg_meta_onde']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Onde', 'Onde '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].' é executad'.$config['genero_meta'].'.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_onde.'</textarea></td></tr>';
if ($exibir['pg_meta_porque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Por Que', 'Por que '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].' será executad'.$config['genero_meta'].'.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_porque.'</textarea></td></tr>';
if ($exibir['pg_meta_como']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Como', 'Como '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].' é executad'.$config['genero_meta'].'.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_como.'</textarea></td></tr>';
if ($exibir['pg_meta_quanto']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Quanto', 'Custo para executar '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].'.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="pg_meta_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->pg_meta_quanto.'</textarea></td></tr>';
if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}
$bsc=($exibir['pg_meta_desde_quando'] && $exibir['pg_meta_controle'] && $exibir['pg_meta_metodo_aprendizado'] && $exibir['pg_meta_melhorias']);
if ($bsc){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>BSC</b></a></td></tr>';
	echo '<tr id="bsc" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}
if ($exibir['pg_meta_desde_quando']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Desde Quando é Feit'.$config['genero_meta'], 'Desde quando '.$config['genero_meta'].' '.$config['meta'].' é executad'.$config['genero_meta'].'.').'Desde quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_meta_desde_quando" cols="60" rows="2" class="textarea">'.$obj->pg_meta_desde_quando.'</textarea></td></tr>';
if ($exibir['pg_meta_controle'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Controle', 'Como '.$config['genero_meta'].' '.$config['meta'].' é controlad'.$config['genero_meta'].'.').'Controle:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_meta_controle" cols="60" rows="2" class="textarea">'.$obj->pg_meta_controle.'</textarea></td></tr>';
if ($exibir['pg_meta_metodo_aprendizado'])echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Método de Aprendizado', 'Como é realizado o aprendizado d'.$config['genero_meta'].' '.$config['meta'].'.').'Aprendizado:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_meta_metodo_aprendizado" cols="60" rows="2" class="textarea">'.$obj->pg_meta_metodo_aprendizado.'</textarea></td></tr>';
if ($exibir['pg_meta_melhorias']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Melhorias Efetuadas n'.$config['genero_meta'].' '.ucfirst($config['meta']), 'Quais as melhorias realizadas n'.$config['genero_meta'].' '.$config['meta'].' após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pg_meta_melhorias" cols="60" rows="2" class="textarea">'.$obj->pg_meta_melhorias.'</textarea></td></tr>';
if ($bsc) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}


$campos_customizados = new CampoCustomizados('metas', $pg_meta_id, 'editar');
$campos_customizados->imprimirHTML();








echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($pg_meta_id > 0 ? 'modificação' : 'criação').' d'.$config['genero_meta'].' '.$config['meta'].'.').'Notificar:'.dicaF().'</td>';
echo '<td>';

echo '<input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Responsável', 'Caso esta caixa esteja selecionada, um e-mail será enviado para o responsável.').'<label for="email_responsavel">Responsável</label>'.dicaF();
echo '<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados', 'Caso esta caixa esteja selecionada, um e-mail será enviado para os designados.').'<label for="email_designados">Designados</label>'.dicaF();
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<table cellspacing=0 cellpadding=0><tr><td>';
if ($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso')) echo botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail sobre este registro d'.$config['genero_meta'].' '.$config['meta'].'.','','popEmailContatos()');
echo '</td>'.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Destinatários extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'</tr></table></td></tr>';
echo '<tr><td colspan="2" valign="bottom" align="right"></td></tr>';


echo '<tr><td colspan=2><table cellspacing=0 cellpadding=0 width="100%"><tr><td >'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pg_meta_id ? 'edição' : 'criação').' d'.$config['genero_meta'].' '.$config['meta'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

?>
<script language="javascript">

function editar_meta(meta_meta_id){
	xajax_editar_meta(meta_meta_id);
	document.getElementById('adicionar_meta').style.display="none";
	document.getElementById('confirmar_meta').style.display="";
	}

function limpar_meta(){
	document.getElementById('meta_meta_id').value=null;
	document.getElementById('meta_meta_valor_meta').value='';
	document.getElementById('meta_meta_valor_meta_boa').value='';
	document.getElementById('meta_meta_valor_meta_regular').value='';
	document.getElementById('meta_meta_valor_meta_ruim').value='';
	}

function excluir_meta(meta_meta_id){
	xajax_excluir_meta(meta_meta_id,  document.getElementById('pg_meta_id').value, document.getElementById('uuid').value);
	}

function incluir_meta(){
	xajax_incluir_meta(
		document.getElementById('meta_meta_id').value,
	  document.getElementById('pg_meta_id').value,
	  document.getElementById('uuid').value,
	  document.getElementById('meta_meta_data_inicio').value,
	  document.getElementById('meta_meta_valor_meta').value,
	  document.getElementById('meta_meta_valor_meta_boa').value,
	  document.getElementById('meta_meta_valor_meta_regular').value,
	  document.getElementById('meta_meta_valor_meta_ruim').value,
	  document.getElementById('meta_meta_data_fim').value
		);
	limpar_meta();
	document.getElementById('adicionar_meta').style.display="";
	document.getElementById('confirmar_meta').style.display="none";
	}


function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pg_meta_cia').value+'&cias_id_selecionadas='+document.getElementById('meta_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.meta_cias.value = organizacao_id_string;
	document.getElementById('meta_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('meta_cias').value);
	__buildTooltip();
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_meta_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pg_meta_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.metas_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_meta_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pg_meta_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pg_meta_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}




function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_meta_dept').value+'&cia_id='+document.getElementById('pg_meta_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pg_meta_dept').value+'&cia_id='+document.getElementById('pg_meta_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pg_meta_cia').value=cia_id;
	document.getElementById('pg_meta_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

function esconder_tipo2(){
	document.getElementById('objetivo').style.display='none';
	document.getElementById('estrategia').style.display='none';
	document.getElementById('fator').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	}

function limpar_tudo2(){
	document.env.pg_meta_objetivo_estrategico.value = null;
	document.env.objetivo_nome.value = '';
	document.env.pg_meta_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.pg_meta_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.pg_meta_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.pg_meta_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	}

function mostrar2(){
	limpar_tudo2();
	esconder_tipo2();
	document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
	}
function popPerspectiva2() {
	window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva2&tabela=perspectivas&cia_id='+document.getElementById('pg_meta_cia').value, 'Perspectiva','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo2();
	document.env.pg_meta_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	}
function popTema2() {
	window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema2&tabela=tema&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema2(chave, valor){
	limpar_tudo2();
	document.env.pg_meta_tema.value = chave;
	document.env.tema_nome.value = valor;
	}

function popEstrategia2() {
	window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia2&tabela=estrategias&cia_id='+document.getElementById('pg_meta_cia').value, 'Iniciativas','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia2(chave, valor){
	limpar_tudo2();
	document.env.pg_meta_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	}


function popMeta2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('pg_meta_cia').value, window.setMeta2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta2&tabela=metas&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta2(chave, valor){
	limpar_tudo();
	document.env.meta_meta2.value = chave;
	document.env.meta2_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popObjetivo2() {
	window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo2&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_meta_cia').value, 'Objetivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo2(chave, valor){
	limpar_tudo();
	document.env.pg_meta_objetivo_estrategico.value = chave;
	document.env.objetivo_nome.value = valor;
	}

function popFator2() {
	window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator2&tabela=fatores_criticos&cia_id='+document.getElementById('pg_meta_cia').value, 'Fator','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator2(chave, valor){
	limpar_tudo();
	document.env.pg_meta_fator.value = chave;
	document.env.fator_nome.value = valor;
	}



function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
	var objetivo_emails = document.getElementById('metas_usuarios');
	var lista_email = email_outro.value.split(',');
	lista_email.sort();
	var vetor_saida = new Array();
	var ultimo_elem = -1;
	for (var i = 0, i_cmp = lista_email.length; i < i_cmp; i++) {
		if (lista_email[i] == ultimo_elem) continue;
		ultimo_elem = lista_email[i];
		vetor_saida.push(lista_email[i]);
		}
	email_outro.value = vetor_saida.join();
	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_meta_cia').value+'&usuario_id='+document.getElementById('pg_meta_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pg_meta_cia').value+'&usuario_id='+document.getElementById('pg_meta_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('pg_meta_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function mudar_om(){
	var cia_id=document.getElementById('pg_meta_cia').value;
	xajax_selecionar_om_ajax(cia_id,'pg_meta_cia','combo_cia', 'class="texto" size=1 style="width:284px;" onchange="javascript:mudar_om();"');
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='meta_fazer_sql';
		f.modulo.value='objetivo';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_meta_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_meta_cor.value;
	}


function enviarDados() {
	var f = document.env;

	if (f.pg_meta_nome.value.length < 3) {
		alert('Escreva um nome válido');
		f.pg_meta_nome.focus();
		}
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

























function mostrar(){
	limpar_tudo();
	esconder_tipo();
	if (document.getElementById('tipo_relacao').value){
		document.getElementById(document.getElementById('tipo_relacao').value).style.display='';
		if (document.getElementById('tipo_relacao').value=='projeto') document.getElementById('tarefa').style.display='';
		}
	}

function esconder_tipo(){
	document.getElementById('projeto').style.display='none';
	document.getElementById('tarefa').style.display='none';
	document.getElementById('pratica').style.display='none';
	document.getElementById('indicador').style.display='none';
	document.getElementById('acao').style.display='none';
	document.getElementById('objetivo').style.display='none';
	document.getElementById('estrategia').style.display='none';
	document.getElementById('meta2').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('tema').style.display='none';
	document.getElementById('calendario').style.display='none';
	document.getElementById('monitoramento').style.display='none';
	document.getElementById('instrumento').style.display='none';
	document.getElementById('recurso').style.display='none';
	document.getElementById('problema').style.display='none';
	document.getElementById('demanda').style.display='none';
	document.getElementById('programa').style.display='none';
	document.getElementById('licao').style.display='none';
	document.getElementById('evento').style.display='none';
	document.getElementById('link').style.display='none';
	document.getElementById('avaliacao').style.display='none';
	document.getElementById('tgn').style.display='none';
	document.getElementById('brainstorm').style.display='none';
	document.getElementById('gut').style.display='none';
	document.getElementById('causa_efeito').style.display='none';
	document.getElementById('arquivo').style.display='none';
	document.getElementById('forum').style.display='none';
	document.getElementById('checklist').style.display='none';
	document.getElementById('agenda').style.display='none';
	document.getElementById('template').style.display='none';
	document.getElementById('painel').style.display='none';
	document.getElementById('painel_odometro').style.display='none';
	document.getElementById('painel_composicao').style.display='none';
	<?php
	if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) echo 'document.getElementById(\'fator\').style.display=\'none\';';
	if ($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.getElementById(\'me\').style.display=\'none\';';
	if($agrupamento_ativo) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
	if($patrocinador_ativo) echo 'document.getElementById(\'patrocinador\').style.display=\'none\';';
	if($swot_ativo) echo 'document.getElementById(\'swot\').style.display=\'none\';';
	if($ata_ativo) echo 'document.getElementById(\'ata\').style.display=\'none\';';
	if($operativo_ativo) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
	if($tr_ativo) echo 'document.getElementById(\'tr\').style.display=\'none\';';
	?>
	}


<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('pg_meta_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('pg_meta_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.meta_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('pg_meta_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('pg_meta_cia').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.meta_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('pg_meta_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('pg_meta_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.meta_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('pg_meta_cia').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('pg_meta_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.meta_painel.value = chave;
		document.env.painel_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('pg_meta_cia').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('pg_meta_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.meta_painel_odometro.value = chave;
		document.env.painel_odometro_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('pg_meta_cia').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('pg_meta_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.meta_painel_composicao.value = chave;
		document.env.painel_composicao_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('pg_meta_cia').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.meta_tr.value = chave;
		document.env.tr_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('pg_meta_cia').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.meta_me.value = chave;
		document.env.me_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('pg_meta_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.meta_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.meta_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.meta_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.meta_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.meta_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pg_meta_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.meta_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pg_meta_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.meta_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_meta_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.meta_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('pg_meta_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.meta_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('pg_meta_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.meta_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('pg_meta_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.meta_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('pg_meta_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('pg_meta_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.meta_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('pg_meta_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.meta_acao.value = chave;
	document.env.acao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('pg_meta_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.meta_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('pg_meta_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.meta_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('pg_meta_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.meta_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('pg_meta_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('pg_meta_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.meta_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('pg_meta_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('pg_meta_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.meta_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('pg_meta_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.meta_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('pg_meta_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.meta_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('pg_meta_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('pg_meta_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.meta_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('pg_meta_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('pg_meta_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.meta_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('pg_meta_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('pg_meta_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.meta_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('pg_meta_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.meta_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('pg_meta_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('pg_meta_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.meta_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('pg_meta_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.meta_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('pg_meta_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.meta_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('pg_meta_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('pg_meta_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.meta_evento.value = chave;
	document.env.evento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('pg_meta_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('pg_meta_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.meta_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('pg_meta_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('pg_meta_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.meta_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('pg_meta_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('pg_meta_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.meta_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('pg_meta_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('pg_meta_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.meta_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('pg_meta_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('pg_meta_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.meta_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('pg_meta_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('pg_meta_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.meta_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('pg_meta_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('pg_meta_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.meta_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('pg_meta_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('pg_meta_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.meta_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pg_meta_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pg_meta_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.meta_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('pg_meta_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('pg_meta_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.meta_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.meta_projeto.value = null;
		}
	document.env.meta_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.meta_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.meta_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.meta_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.meta_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.meta_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	
	document.env.meta_meta2.value = null;
	document.env.meta2_nome.value = '';
	
	document.env.meta_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.meta_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.meta_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.meta_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.meta_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.meta_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.meta_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.meta_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.meta_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.meta_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.meta_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.meta_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.meta_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.meta_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.meta_link.value = null;
	document.env.link_nome.value = '';
	document.env.meta_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.meta_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.meta_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.meta_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.meta_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.meta_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.meta_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.meta_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.meta_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.meta_template.value = null;
	document.env.template_nome.value = '';
	document.env.meta_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.meta_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.meta_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	
	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.meta_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.meta_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.meta_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.meta_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.meta_patrocinador.value = null;';
	if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.meta_tr.value = null;';
	if($Aplic->profissional && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.meta_me.value = null;';
	if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) echo 'document.env.fator_nome.value = \'\';	document.env.meta_fator.value = null;';
	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('pg_meta_id').value,
	document.getElementById('uuid').value,
	f.meta_projeto.value,
	f.meta_tarefa.value,
	f.meta_perspectiva.value,
	f.meta_tema.value,
	f.meta_objetivo.value,
	f.meta_fator.value,
	f.meta_estrategia.value,
	f.meta_meta2.value,
	f.meta_pratica.value,
	f.meta_acao.value,
	f.meta_canvas.value,
	f.meta_risco.value,
	f.meta_risco_resposta.value,
	f.meta_indicador.value,
	f.meta_calendario.value,
	f.meta_monitoramento.value,
	f.meta_ata.value,
	f.meta_swot.value,
	f.meta_operativo.value,
	f.meta_instrumento.value,
	f.meta_recurso.value,
	f.meta_problema.value,
	f.meta_demanda.value,
	f.meta_programa.value,
	f.meta_licao.value,
	f.meta_evento.value,
	f.meta_link.value,
	f.meta_avaliacao.value,
	f.meta_tgn.value,
	f.meta_brainstorm.value,
	f.meta_gut.value,
	f.meta_causa_efeito.value,
	f.meta_arquivo.value,
	f.meta_forum.value,
	f.meta_checklist.value,
	f.meta_agenda.value,
	f.meta_agrupamento.value,
	f.meta_patrocinador.value,
	f.meta_template.value,
	f.meta_painel.value,
	f.meta_painel_odometro.value,
	f.meta_painel_composicao.value,
	f.meta_tr.value,
	f.meta_me.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(meta_gestao_id){
	xajax_excluir_gestao(document.getElementById('pg_meta_id').value, document.getElementById('uuid').value, meta_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, meta_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, meta_gestao_id, direcao, document.getElementById('pg_meta_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}





function setData( frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
  	else{
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "meta_meta_data_inicio",
	date :  <?php echo date('Y').'-01-01'?>,
	selection: <?php echo date('Y').'-01-01'?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("meta_meta_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal1.hide();
	}
});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "meta_meta_data_fim",
	date : <?php echo date('Y').'-12-31'?>,
	selection : <?php echo date('Y').'-12-31'?>,
  onSelect : function(cal2) {
  var date = cal2.selection.get();
  if (date){
    date = Calendario.intToDate(date);
    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("meta_meta_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal2.hide();
	}
});




<?php if (!$pg_meta_id && (
	$meta_projeto ||
	$meta_tarefa ||
	$meta_perspectiva ||
	$meta_tema ||
	$meta_objetivo ||
	$meta_fator ||
	$meta_estrategia ||
	$meta_pratica ||
	$meta_acao ||
	$meta_canvas ||
	$meta_risco ||
	$meta_risco_resposta ||
	$meta_indicador ||
	$meta_calendario ||
	$meta_monitoramento ||
	$meta_ata ||
	$meta_swot ||
	$meta_operativo ||
	$meta_instrumento ||
	$meta_recurso ||
	$meta_problema ||
	$meta_demanda ||
	$meta_programa ||
	$meta_licao ||
	$meta_evento ||
	$meta_link ||
	$meta_avaliacao ||
	$meta_tgn ||
	$meta_brainstorm ||
	$meta_gut ||
	$meta_causa_efeito ||
	$meta_arquivo ||
	$meta_forum ||
	$meta_checklist ||
	$meta_agenda ||
	$meta_agrupamento ||
	$meta_patrocinador ||
	$meta_template||
	$meta_painel ||
	$meta_painel_odometro ||
	$meta_painel_composicao ||
	$meta_tr ||
	$meta_me
	)) echo 'incluir_relacionado();';
	?>


<?php if ($Aplic->profissional) echo 'mudar_sistema();' ?>
</script>

