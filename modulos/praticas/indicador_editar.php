<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$Aplic->carregarCalendarioJS();
$Aplic->carregarCKEditorJS();

$pratica_indicador_id = getParam($_REQUEST, 'pratica_indicador_id', null);


if ((!$podeEditar && $pratica_indicador_id) || (!$podeAdicionar && !$pratica_indicador_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');



$salvar = getParam($_REQUEST, 'salvar', 0);
$excluir = getParam($_REQUEST, 'excluir', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);

$sql = new BDConsulta;

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

$pratica_indicador_projeto = getParam($_REQUEST, 'pratica_indicador_projeto', null);
$pratica_indicador_tarefa = getParam($_REQUEST, 'pratica_indicador_tarefa', null);
$pratica_indicador_perspectiva = getParam($_REQUEST, 'pratica_indicador_perspectiva', null);
$pratica_indicador_tema = getParam($_REQUEST, 'pratica_indicador_tema', null);
$pratica_indicador_objetivo_estrategico = getParam($_REQUEST, 'pratica_indicador_objetivo_estrategico', null);
$pratica_indicador_fator = getParam($_REQUEST, 'pratica_indicador_fator', null);
$pratica_indicador_estrategia = getParam($_REQUEST, 'pratica_indicador_estrategia', null);
$pratica_indicador_meta = getParam($_REQUEST, 'pratica_indicador_meta', null);
$pratica_indicador_pratica = getParam($_REQUEST, 'pratica_indicador_pratica', null);
$pratica_indicador_acao = getParam($_REQUEST, 'pratica_indicador_acao', null);
$pratica_indicador_canvas = getParam($_REQUEST, 'pratica_indicador_canvas', null);
$pratica_indicador_risco = getParam($_REQUEST, 'pratica_indicador_risco', null);
$pratica_indicador_risco_resposta = getParam($_REQUEST, 'pratica_indicador_risco_resposta', null);
$pratica_indicador_calendario = getParam($_REQUEST, 'pratica_indicador_calendario', null);
$pratica_indicador_monitoramento = getParam($_REQUEST, 'pratica_indicador_monitoramento', null);
$pratica_indicador_ata = getParam($_REQUEST, 'pratica_indicador_ata', null);
$pratica_indicador_swot = getParam($_REQUEST, 'pratica_indicador_swot', null);
$pratica_indicador_operativo = getParam($_REQUEST, 'pratica_indicador_operativo', null);
$pratica_indicador_instrumento = getParam($_REQUEST, 'pratica_indicador_instrumento', null);
$pratica_indicador_recurso = getParam($_REQUEST, 'pratica_indicador_recurso', null);
$pratica_indicador_problema = getParam($_REQUEST, 'pratica_indicador_problema', null);
$pratica_indicador_demanda = getParam($_REQUEST, 'pratica_indicador_demanda', null);
$pratica_indicador_programa = getParam($_REQUEST, 'pratica_indicador_programa', null);
$pratica_indicador_licao = getParam($_REQUEST, 'pratica_indicador_licao', null);
$pratica_indicador_evento = getParam($_REQUEST, 'pratica_indicador_evento', null);
$pratica_indicador_link = getParam($_REQUEST, 'pratica_indicador_link', null);
$pratica_indicador_avaliacao = getParam($_REQUEST, 'pratica_indicador_avaliacao', null);
$pratica_indicador_tgn = getParam($_REQUEST, 'pratica_indicador_tgn', null);
$pratica_indicador_brainstorm = getParam($_REQUEST, 'pratica_indicador_brainstorm', null);
$pratica_indicador_gut = getParam($_REQUEST, 'pratica_indicador_gut', null);
$pratica_indicador_causa_efeito = getParam($_REQUEST, 'pratica_indicador_causa_efeito', null);
$pratica_indicador_arquivo = getParam($_REQUEST, 'pratica_indicador_arquivo', null);
$pratica_indicador_forum = getParam($_REQUEST, 'pratica_indicador_forum', null);
$pratica_indicador_checklist = getParam($_REQUEST, 'pratica_indicador_checklist', null);
$pratica_indicador_agenda = getParam($_REQUEST, 'pratica_indicador_agenda', null);
$pratica_indicador_agrupamento = getParam($_REQUEST, 'pratica_indicador_agrupamento', null);
$pratica_indicador_patrocinador = getParam($_REQUEST, 'pratica_indicador_patrocinador', null);
$pratica_indicador_template = getParam($_REQUEST, 'pratica_indicador_template', null);
$pratica_indicador_painel = getParam($_REQUEST, 'pratica_indicador_painel', null);
$pratica_indicador_painel_odometro = getParam($_REQUEST, 'pratica_indicador_painel_odometro', null);
$pratica_indicador_painel_composicao = getParam($_REQUEST, 'pratica_indicador_painel_composicao', null);
$pratica_indicador_tr = getParam($_REQUEST, 'pratica_indicador_tr', null);
$pratica_indicador_me = getParam($_REQUEST, 'pratica_indicador_me', null);

if (
	$pratica_indicador_projeto ||
	$pratica_indicador_tarefa ||
	$pratica_indicador_perspectiva ||
	$pratica_indicador_tema ||
	$pratica_indicador_objetivo_estrategico ||
	$pratica_indicador_fator ||
	$pratica_indicador_estrategia ||
	$pratica_indicador_meta ||
	$pratica_indicador_pratica ||
	$pratica_indicador_acao ||
	$pratica_indicador_canvas ||
	$pratica_indicador_risco ||
	$pratica_indicador_risco_resposta ||
	$pratica_indicador_calendario ||
	$pratica_indicador_monitoramento ||
	$pratica_indicador_ata ||
	$pratica_indicador_swot ||
	$pratica_indicador_operativo ||
	$pratica_indicador_instrumento ||
	$pratica_indicador_recurso ||
	$pratica_indicador_problema ||
	$pratica_indicador_demanda ||
	$pratica_indicador_programa ||
	$pratica_indicador_licao ||
	$pratica_indicador_evento ||
	$pratica_indicador_link ||
	$pratica_indicador_avaliacao ||
	$pratica_indicador_tgn ||
	$pratica_indicador_brainstorm ||
	$pratica_indicador_gut ||
	$pratica_indicador_causa_efeito ||
	$pratica_indicador_arquivo ||
	$pratica_indicador_forum ||
	$pratica_indicador_checklist ||
	$pratica_indicador_agenda ||
	$pratica_indicador_agrupamento ||
	$pratica_indicador_patrocinador ||
	$pratica_indicador_template ||
	$pratica_indicador_painel ||
	$pratica_indicador_painel_odometro ||
	$pratica_indicador_painel_composicao ||
	$pratica_indicador_tr ||
	$pratica_indicador_me
	){
	$sql->adTabela('cias');
	if ($pratica_indicador_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($pratica_indicador_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($pratica_indicador_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($pratica_indicador_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($pratica_indicador_objetivo_estrategico) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($pratica_indicador_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($pratica_indicador_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($pratica_indicador_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($pratica_indicador_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($pratica_indicador_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($pratica_indicador_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($pratica_indicador_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($pratica_indicador_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($pratica_indicador_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($pratica_indicador_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($pratica_indicador_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($pratica_indicador_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($pratica_indicador_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($pratica_indicador_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($pratica_indicador_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($pratica_indicador_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($pratica_indicador_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($pratica_indicador_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($pratica_indicador_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($pratica_indicador_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($pratica_indicador_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($pratica_indicador_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($pratica_indicador_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($pratica_indicador_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($pratica_indicador_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($pratica_indicador_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($pratica_indicador_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($pratica_indicador_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($pratica_indicador_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($pratica_indicador_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($pratica_indicador_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($pratica_indicador_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($pratica_indicador_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($pratica_indicador_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($pratica_indicador_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($pratica_indicador_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($pratica_indicador_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
	elseif ($pratica_indicador_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');

	if ($pratica_indicador_tarefa) $sql->adOnde('tarefa_id = '.(int)$pratica_indicador_tarefa);
	elseif ($pratica_indicador_projeto) $sql->adOnde('projeto_id = '.(int)$pratica_indicador_projeto);
	elseif ($pratica_indicador_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$pratica_indicador_perspectiva);
	elseif ($pratica_indicador_tema) $sql->adOnde('tema_id = '.(int)$pratica_indicador_tema);
	elseif ($pratica_indicador_objetivo_estrategico) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$pratica_indicador_objetivo_estrategico);
	elseif ($pratica_indicador_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$pratica_indicador_fator);
	elseif ($pratica_indicador_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$pratica_indicador_estrategia);
	elseif ($pratica_indicador_meta) $sql->adOnde('pg_meta_id = '.(int)$pratica_indicador_meta);
	elseif ($pratica_indicador_pratica) $sql->adOnde('pratica_id = '.(int)$pratica_indicador_pratica);
	elseif ($pratica_indicador_acao) $sql->adOnde('plano_acao_id = '.(int)$pratica_indicador_acao);
	elseif ($pratica_indicador_canvas) $sql->adOnde('canvas_id = '.(int)$pratica_indicador_canvas);
	elseif ($pratica_indicador_risco) $sql->adOnde('risco_id = '.(int)$pratica_indicador_risco);
	elseif ($pratica_indicador_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$pratica_indicador_risco_resposta);
	elseif ($pratica_indicador_calendario) $sql->adOnde('calendario_id = '.(int)$pratica_indicador_calendario);
	elseif ($pratica_indicador_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$pratica_indicador_monitoramento);
	elseif ($pratica_indicador_ata) $sql->adOnde('ata_id = '.(int)$pratica_indicador_ata);
	elseif ($pratica_indicador_swot) $sql->adOnde('swot_id = '.(int)$pratica_indicador_swot);
	elseif ($pratica_indicador_operativo) $sql->adOnde('operativo_id = '.(int)$pratica_indicador_operativo);
	elseif ($pratica_indicador_instrumento) $sql->adOnde('instrumento_id = '.(int)$pratica_indicador_instrumento);
	elseif ($pratica_indicador_recurso) $sql->adOnde('recurso_id = '.(int)$pratica_indicador_recurso);
	elseif ($pratica_indicador_problema) $sql->adOnde('problema_id = '.(int)$pratica_indicador_problema);
	elseif ($pratica_indicador_demanda) $sql->adOnde('demanda_id = '.(int)$pratica_indicador_demanda);
	elseif ($pratica_indicador_programa) $sql->adOnde('programa_id = '.(int)$pratica_indicador_programa);
	elseif ($pratica_indicador_licao) $sql->adOnde('licao_id = '.(int)$pratica_indicador_licao);
	elseif ($pratica_indicador_evento) $sql->adOnde('evento_id = '.(int)$pratica_indicador_evento);
	elseif ($pratica_indicador_link) $sql->adOnde('link_id = '.(int)$pratica_indicador_link);
	elseif ($pratica_indicador_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$pratica_indicador_avaliacao);
	elseif ($pratica_indicador_tgn) $sql->adOnde('tgn_id = '.(int)$pratica_indicador_tgn);
	elseif ($pratica_indicador_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$pratica_indicador_brainstorm);
	elseif ($pratica_indicador_gut) $sql->adOnde('gut_id = '.(int)$pratica_indicador_gut);
	elseif ($pratica_indicador_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$pratica_indicador_causa_efeito);
	elseif ($pratica_indicador_arquivo) $sql->adOnde('arquivo_id = '.(int)$pratica_indicador_arquivo);
	elseif ($pratica_indicador_forum) $sql->adOnde('forum_id = '.(int)$pratica_indicador_forum);
	elseif ($pratica_indicador_checklist) $sql->adOnde('checklist_id = '.(int)$pratica_indicador_checklist);
	elseif ($pratica_indicador_agenda) $sql->adOnde('agenda_id = '.(int)$pratica_indicador_agenda);
	elseif ($pratica_indicador_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$pratica_indicador_agrupamento);
	elseif ($pratica_indicador_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$pratica_indicador_patrocinador);
	elseif ($pratica_indicador_template) $sql->adOnde('template_id = '.(int)$pratica_indicador_template);
	elseif ($pratica_indicador_painel) $sql->adOnde('painel_id = '.(int)$pratica_indicador_painel);
	elseif ($pratica_indicador_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$pratica_indicador_painel_odometro);
	elseif ($pratica_indicador_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$pratica_indicador_painel_composicao);
	elseif ($pratica_indicador_tr) $sql->adOnde('tr_id = '.(int)$pratica_indicador_tr);
	elseif ($pratica_indicador_me) $sql->adOnde('me_id = '.(int)$pratica_indicador_me);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}




$qnt_anos=0;
$ultimo_ano=0;
if ($pratica_indicador_id){
	//lista de anos existentes
	$sql->adTabela('pratica_indicador_requisito');
	$sql->adCampo('DISTINCT pratica_indicador_requisito_ano');
	$sql->adOnde('pratica_indicador_requisito_indicador='.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_requisito_ano');
	$anos=$sql->listaVetorChave('pratica_indicador_requisito_ano','pratica_indicador_requisito_ano');
	$sql->limpar();
	$ultimo_ano=$anos;
	$qnt_anos=count($ultimo_ano);
	$ultimo_ano=array_pop($ultimo_ano);
	}

for ($i=((int)date('Y'))-30; $i<=(int)date('Y')+30; $i++) $anos[$i]=$i;
asort($anos);


if (isset($_REQUEST['IdxIndicadorAno'])) $Aplic->setEstado('IdxIndicadorAno', getParam($_REQUEST, 'IdxIndicadorAno', null));
$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null && isset($anos[$Aplic->getEstado('IdxIndicadorAno')]) && $pratica_indicador_id ? $Aplic->getEstado('IdxIndicadorAno') : ($ultimo_ano ? $ultimo_ano : date('Y')));


$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador.pratica_indicador_acesso');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$acesso=$sql->resultado();
$sql->limpar();
if (!($podeEditar && permiteAcessarIndicador($acesso,$pratica_indicador_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$sql->adTabela('pratica_modelo');
$sql->adCampo('pratica_modelo_id, pratica_modelo_nome');
$sql->adOrdem('pratica_modelo_ordem');
$modelos=array(''=>'')+$sql->ListaChave();
$sql->limpar();


$pratica_indicador_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

$tipo_grafico = array('linha' => 'Linha', 'barra' => 'Barra', 'barra_sombra' => 'Barra com sombra', 'area' => 'Área da linha');
$tipo_agrupamento=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'Mês','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano', 'nenhum' => 'Nenhum agrupamento');
$tipo_acumulacao=array('media_simples' => 'Média simples dos valores do período', 'soma' => 'Soma dos valores do período', 'saldo' => 'Último valor do período');


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="indicador_fazer_sql" />';

$botoesTitulo = new CBlocoTitulo(($pratica_indicador_id ? 'Editar indicador' : 'Criar indicador'), 'indicador.gif', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


$obj = new CIndicador();
$obj->load($pratica_indicador_id);

$sql->adTabela('pratica_indicador_requisito');
$sql->adCampo('pratica_indicador_requisito.*');
if (!$ano) $sql->adOnde('pratica_indicador_requisito_id = '.(int)$obj->pratica_indicador_requisito);
else $sql->adOnde('pratica_indicador_requisito_ano = '.(int)$ano);
$sql->adOnde('pratica_indicador_requisito_indicador = '.(int)$pratica_indicador_id);
$requisito = $sql->linha();
$sql->limpar();

$usuarios_selecionados =array();
$depts_selecionados = array();
$cias_selecionadas = array();
if ($pratica_indicador_id) {
	$sql->adTabela('pratica_indicador_usuarios', 'pratica_indicador_usuarios');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_indicador_usuarios.usuario_id');
	$sql->adCampo('usuarios.usuario_id');
	$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('pratica_indicador_depts', 'pd');
	$sql->adTabela('depts', 'deps');
	$sql->adCampo('deps.dept_id');
	$sql->adOnde('pratica_indicador_id ='.(int)$pratica_indicador_id);
	$sql->adOnde('pd.dept_id = deps.dept_id');
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('indicador_cia');
		$sql->adCampo('indicador_cia_cia');
		$sql->adOnde('indicador_cia_indicador = '.(int)$pratica_indicador_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}

$data = new CData();
$data_desde = ($obj->pratica_indicador_desde_quando ? new CData($obj->pratica_indicador_desde_quando) : new CData());
echo '<input type="hidden" name="pratica_indicador_id" id="pratica_indicador_id" value="'.$pratica_indicador_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($pratica_indicador_id ? null : uuid()).'" />';
echo '<input name="pratica_indicador_usuarios" type="hidden" value="'.implode(',', $usuarios_selecionados).'" />';
echo '<input name="pratica_indicador_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="indicador_cias"  id="indicador_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';

echo '<input type="hidden" name="qnt_metas" id="qnt_metas" value="" />';


echo '<input type="hidden" id="pratica_indicador_superior" name="pratica_indicador_superior" value="'.$obj->pratica_indicador_superior.'" />';
echo '<input type="hidden" id="pratica_indicador_calculo" name="pratica_indicador_calculo" value="'.$obj->pratica_indicador_calculo.'" />';

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'indicador\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';

echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td width="50%" valign="top"><table cellspacing=0 cellpadding=0 border=0zz>';
echo '<tr><td align="right">'.dica('Nome do Indicador', 'Todo indicador necessita ter um nome para identificação pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="pratica_indicador_nome" value="'.$obj->pratica_indicador_nome.'" style="width:280px;" maxlength="512" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'A qual '.$config['organizacao'].' pertence este indicador.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td width="100%" nowrap="nowrap" colspan="2"><div id="combo_cia">'.selecionar_om(($obj->pratica_indicador_cia ? $obj->pratica_indicador_cia : $cia_id), 'pratica_indicador_cia', 'class=texto size=1 style="width:284px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este indicador.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="pratica_indicador_dept" id="pratica_indicador_dept" value="'.($pratica_indicador_id ? $obj->pratica_indicador_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($pratica_indicador_id ? $obj->pratica_indicador_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:280px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:284px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pelo Indicador', 'Todo indicador deve ter um responsável.').'Responsável:'.dicaF().'</td><td colspan="2"><input type="hidden" id="pratica_indicador_responsavel" name="pratica_indicador_responsavel" value="'.($obj->pratica_indicador_responsavel ? $obj->pratica_indicador_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->pratica_indicador_responsavel ? $obj->pratica_indicador_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:280px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';



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
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:284px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


$indicadores_tipos=vetor_campo_sistema('IndicadorTipo',$obj->pratica_indicador_tipo);
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Indicador', 'Definir o tipo de indicador para fins de filtragem.').'Tipo de indicador:'.dicaF().'</td><td><div id="combo_indicador_tipo">'.selecionaVetor($indicadores_tipos, 'pratica_indicador_tipo', 'class="texto" size=1 style="width:284px;" onchange="mudar_indicador_tipo();"', $obj->pratica_indicador_tipo).'</div></td></tr>';

if (!$Aplic->profissional){
	if ($obj->pratica_indicador_projeto) $pratica_indicador_projeto=$obj->pratica_indicador_projeto;
	if ($obj->pratica_indicador_tarefa) $pratica_indicador_tarefa=$obj->pratica_indicador_tarefa;
	elseif ($obj->pratica_indicador_fator) $pratica_indicador_fator=$obj->pratica_indicador_fator;
	elseif ($obj->pratica_indicador_estrategia) $pratica_indicador_estrategia=$obj->pratica_indicador_estrategia;
	elseif ($obj->pratica_indicador_meta) $pratica_indicador_meta=$obj->pratica_indicador_meta;
	elseif ($obj->pratica_indicador_objetivo_estrategico) $pratica_indicador_objetivo_estrategico=$obj->pratica_indicador_objetivo_estrategico;
	elseif ($obj->pratica_indicador_perspectiva) $pratica_indicador_perspectiva=$obj->pratica_indicador_perspectiva;
	elseif ($obj->pratica_indicador_acao) $pratica_indicador_acao=$obj->pratica_indicador_acao;
	elseif ($obj->pratica_indicador_pratica) $pratica_indicador_pratica=$obj->pratica_indicador_fator;
	elseif ($obj->pratica_indicador_tema) $pratica_indicador_tema=$obj->pratica_indicador_tema;
	}

$tipos=array(
	''=>'',
	'projeto' => ucfirst($config['projeto']),
	'perspectiva'=> ucfirst($config['perspectiva']),
	'tema'=> ucfirst($config['tema']),
	'objetivo'=> ucfirst($config['objetivo']),
	'estrategia'=> ucfirst($config['iniciativa']),
	'meta'=>ucfirst($config['meta']),
	'acao'=> ucfirst($config['acao']),
	'pratica' => ucfirst($config['pratica']),
	);
if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) $tipos['fator']=ucfirst($config['fator']);		
if ($ata_ativo) $tipos['ata']='Ata de Reunião';
if ($swot_ativo) $tipos['swot']='Campo SWOT';
if ($operativo_ativo) $tipos['operativo']='Plano Operativo';
if ($Aplic->profissional) {
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
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	}
asort($tipos);

if ($pratica_indicador_projeto) $tipo='projeto';
elseif ($pratica_indicador_pratica) $tipo='pratica';
elseif ($pratica_indicador_acao) $tipo='acao';
elseif ($pratica_indicador_objetivo_estrategico) $tipo='objetivo';
elseif ($pratica_indicador_tema) $tipo='tema';
elseif ($pratica_indicador_fator) $tipo='fator';
elseif ($pratica_indicador_estrategia) $tipo='estrategia';
elseif ($pratica_indicador_perspectiva) $tipo='perspectiva';
elseif ($pratica_indicador_canvas) $tipo='canvas';
elseif ($pratica_indicador_risco) $tipo='risco';
elseif ($pratica_indicador_risco_resposta) $tipo='risco_resposta';
elseif ($pratica_indicador_meta) $tipo='meta';
elseif ($pratica_indicador_swot) $tipo='swot';
elseif ($pratica_indicador_ata) $tipo='ata';
elseif ($pratica_indicador_monitoramento) $tipo='monitoramento';
elseif ($pratica_indicador_calendario) $tipo='calendario';
elseif ($pratica_indicador_operativo) $tipo='operativo';
elseif ($pratica_indicador_instrumento) $tipo='instrumento';
elseif ($pratica_indicador_recurso) $tipo='recurso';
elseif ($pratica_indicador_problema) $tipo='problema';
elseif ($pratica_indicador_demanda) $tipo='demanda';
elseif ($pratica_indicador_programa) $tipo='programa';
elseif ($pratica_indicador_licao) $tipo='licao';
elseif ($pratica_indicador_evento) $tipo='evento';
elseif ($pratica_indicador_link) $tipo='link';
elseif ($pratica_indicador_avaliacao) $tipo='avaliacao';
elseif ($pratica_indicador_tgn) $tipo='tgn';
elseif ($pratica_indicador_brainstorm) $tipo='brainstorm';
elseif ($pratica_indicador_gut) $tipo='gut';
elseif ($pratica_indicador_causa_efeito) $tipo='causa_efeito';
elseif ($pratica_indicador_arquivo) $tipo='arquivo';
elseif ($pratica_indicador_forum) $tipo='forum';
elseif ($pratica_indicador_checklist) $tipo='checklist';
elseif ($pratica_indicador_agenda) $tipo='agenda';
elseif ($pratica_indicador_agrupamento) $tipo='agrupamento';
elseif ($pratica_indicador_patrocinador) $tipo='patrocinador';
elseif ($pratica_indicador_template) $tipo='template';
elseif ($pratica_indicador_painel) $tipo='painel';
elseif ($pratica_indicador_painel_odometro) $tipo='painel_odometro';
elseif ($pratica_indicador_painel_composicao) $tipo='painel_composicao';
elseif ($pratica_indicador_tr) $tipo='tr';
elseif ($pratica_indicador_me) $tipo='me';
else $tipo='';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionado','A qual parte do sistema o indicador está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:284px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o indicador seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_projeto" value="'.$pratica_indicador_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($pratica_indicador_projeto).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_projeto || $pratica_indicador_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionada', 'Caso o indicador seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tarefa" value="'.$pratica_indicador_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($pratica_indicador_tarefa).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o indicador seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_pratica" value="'.$pratica_indicador_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($pratica_indicador_pratica).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso o indicador seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_acao" value="'.$pratica_indicador_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($pratica_indicador_acao).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso o indicador seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_perspectiva" value="'.$pratica_indicador_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($pratica_indicador_perspectiva).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso o indicador seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tema" value="'.$pratica_indicador_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($pratica_indicador_tema).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_objetivo_estrategico ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso o indicador seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_objetivo_estrategico" value="'.$pratica_indicador_objetivo_estrategico.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($pratica_indicador_objetivo_estrategico).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso o indicador seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_estrategia" value="'.$pratica_indicador_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($pratica_indicador_estrategia).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso o indicador seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_fator" value="'.$pratica_indicador_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($pratica_indicador_fator).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso o indicador seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_meta" value="'.$pratica_indicador_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($pratica_indicador_meta).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';

if ($agrupamento_ativo) echo '<tr '.($pratica_indicador_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso o indicador seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agrupamento" value="'.$pratica_indicador_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($pratica_indicador_agrupamento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

if ($patrocinador_ativo) echo '<tr '.($pratica_indicador_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso o indicador seja específico de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_patrocinador" value="'.$pratica_indicador_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($pratica_indicador_patrocinador).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';

echo '<tr '.($pratica_indicador_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso o indicador seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_calendario" value="'.$pratica_indicador_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($pratica_indicador_calendario).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso o indicador seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_instrumento" value="'.$pratica_indicador_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($pratica_indicador_instrumento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso o indicador seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_recurso" value="'.$pratica_indicador_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($pratica_indicador_recurso).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
if ($problema_ativo) echo '<tr '.($pratica_indicador_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso o indicador seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_problema" value="'.$pratica_indicador_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($pratica_indicador_problema).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
echo '<tr '.($pratica_indicador_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso o indicador seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_demanda" value="'.$pratica_indicador_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($pratica_indicador_demanda).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso o indicador seja específico de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_licao" value="'.$pratica_indicador_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($pratica_indicador_licao).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso o indicador seja específico de um evento, neste campo deverá constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_evento" value="'.$pratica_indicador_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($pratica_indicador_evento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso o indicador seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_link" value="'.$pratica_indicador_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($pratica_indicador_link).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso o indicador seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_avaliacao" value="'.$pratica_indicador_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($pratica_indicador_avaliacao).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso o indicador seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_brainstorm" value="'.$pratica_indicador_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($pratica_indicador_brainstorm).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso o indicador seja específico de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_gut" value="'.$pratica_indicador_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($pratica_indicador_gut).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso o indicador seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_causa_efeito" value="'.$pratica_indicador_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($pratica_indicador_causa_efeito).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso o indicador seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_arquivo" value="'.$pratica_indicador_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($pratica_indicador_arquivo).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso o indicador seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_forum" value="'.$pratica_indicador_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($pratica_indicador_forum).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso o indicador seja específico de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_checklist2" value="'.$pratica_indicador_checklist.'" /><input type="text" id="checklist_nome2" name="checklist_nome2" value="'.nome_checklist($pratica_indicador_checklist).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist2();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($pratica_indicador_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso o indicador seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_agenda" value="'.$pratica_indicador_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($pratica_indicador_agenda).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
if (!$Aplic->profissional) {
	echo '<input type="hidden" name="pratica_indicador_monitoramento" value="" id="monitoramento" /><input type="hidden" id="monitoramento_nome" name="monitoramento_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_tgn" value="" id="tgn" /><input type="hidden" id="tgn_nome" name="tgn_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_programa" value="" id="programa" /><input type="hidden" id="programa_nome" name="programa_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
	echo '<input type="hidden" name="pratica_indicador_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
	}
else {
	echo '<tr '.($pratica_indicador_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso o indicador seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_monitoramento" value="'.$pratica_indicador_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($pratica_indicador_monitoramento).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso o indicador seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_template" value="'.$pratica_indicador_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($pratica_indicador_template).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso o indicador seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tgn" value="'.$pratica_indicador_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($pratica_indicador_tgn).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso o indicador seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_programa" value="'.$pratica_indicador_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($pratica_indicador_programa).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso o indicador seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco" value="'.$pratica_indicador_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($pratica_indicador_risco).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso o indicador seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_risco_resposta" value="'.$pratica_indicador_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($pratica_indicador_risco_resposta).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso o indicador seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_canvas" value="'.$pratica_indicador_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($pratica_indicador_canvas).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" nowrap="nowrap">'.dica('Painel de Indicador', 'Caso o indicador seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel" value="'.$pratica_indicador_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($pratica_indicador_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" nowrap="nowrap">'.dica('Odômetro de Indicador', 'Caso o indicador seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_odometro" value="'.$pratica_indicador_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($pratica_indicador_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" nowrap="nowrap">'.dica('Composição de Painéis', 'Caso o indicador seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_painel_composicao" value="'.$pratica_indicador_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($pratica_indicador_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/painel_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/painel_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($pratica_indicador_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_tr" value="'.$pratica_indicador_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($pratica_indicador_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr '.($pratica_indicador_me ? '' : 'style="display:none"').' id="me" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_me" value="'.$pratica_indicador_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($pratica_indicador_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="pratica_indicador_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';

	}
if ($swot_ativo) echo '<tr '.(isset($pratica_indicador_swot) && $pratica_indicador_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso o indicador seja específico de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_swot" value="'.(isset($pratica_indicador_swot) ? $pratica_indicador_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($pratica_indicador_swot) ? $pratica_indicador_swot : null)).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
if ($ata_ativo) echo '<tr '.(isset($pratica_indicador_ata) && $pratica_indicador_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso o indicador seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_ata" value="'.(isset($pratica_indicador_ata) ? $pratica_indicador_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($pratica_indicador_ata) ? $pratica_indicador_ata : null)).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
if ($operativo_ativo) echo '<tr '.($pratica_indicador_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso o indicador seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="pratica_indicador_operativo" value="'.$pratica_indicador_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($pratica_indicador_operativo).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';



if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador_gestao');
	$sql->adCampo('pratica_indicador_gestao.*');
	$sql->adOnde('pratica_indicador_gestao_indicador ='.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['pratica_indicador_gestao_ordem'].', '.$gestao_data['pratica_indicador_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['pratica_indicador_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['pratica_indicador_gestao_tarefa']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['pratica_indicador_gestao_projeto']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['pratica_indicador_gestao_pratica']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['pratica_indicador_gestao_acao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['pratica_indicador_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['pratica_indicador_gestao_tema']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['pratica_indicador_gestao_objetivo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['pratica_indicador_gestao_fator']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['pratica_indicador_gestao_estrategia']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['pratica_indicador_gestao_meta']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['pratica_indicador_gestao_canvas']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['pratica_indicador_gestao_risco']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['pratica_indicador_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['pratica_indicador_gestao_calendario']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['pratica_indicador_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['pratica_indicador_gestao_ata']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['pratica_indicador_gestao_swot']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['pratica_indicador_gestao_operativo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['pratica_indicador_gestao_instrumento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['pratica_indicador_gestao_recurso']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['pratica_indicador_gestao_problema']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['pratica_indicador_gestao_demanda']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['pratica_indicador_gestao_programa']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['pratica_indicador_gestao_licao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['pratica_indicador_gestao_evento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['pratica_indicador_gestao_link']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['pratica_indicador_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['pratica_indicador_gestao_tgn']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['pratica_indicador_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['pratica_indicador_gestao_gut']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['pratica_indicador_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['pratica_indicador_gestao_arquivo']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['pratica_indicador_gestao_forum']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['pratica_indicador_gestao_checklist']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['pratica_indicador_gestao_agenda']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['pratica_indicador_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['pratica_indicador_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['pratica_indicador_gestao_template']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_painel']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['pratica_indicador_gestao_painel']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['pratica_indicador_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['pratica_indicador_gestao_painel_composicao']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['pratica_indicador_gestao_tr']).'</td>';
		elseif ($gestao_data['pratica_indicador_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['pratica_indicador_gestao_me']).'</td>';


		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['pratica_indicador_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';
	}








echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="pratica_indicador_cor" value="'.($obj->pratica_indicador_cor ? $obj->pratica_indicador_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->pratica_indicador_cor ? $obj->pratica_indicador_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Os indicadores podem ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar o indicador.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados para o indicador podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar e os designados inserir valores quando for o caso.</li><li><b>Participante</b> - Somente o responsável e os designados para o indicador podem ver e editar o mesmo</li><li><b>Privado</b> - Somente o responsável e os designados para o indicador podem ver o mesmo, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($pratica_indicador_acesso, 'pratica_indicador_acesso', 'class="texto"', ($pratica_indicador_id ? $obj->pratica_indicador_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

if ($Aplic->profissional){

	if ($exibir['ano']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'Qual ano iniciou-se a utilização deste indicador.').'Ano inicial:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="pratica_indicador_ano" value="'.($obj->pratica_indicador_ano ? $obj->pratica_indicador_ano : date('Y')).'" size="4" class="texto" /></td></tr>';

	if ($obj->pratica_indicador_id) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano das Informações', 'Utilize esta opção para visualizar ou registrar os dados do indicador inseridos no ano selecionado.').'Ano das Informações:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($anos, 'IdxIndicadorAno', 'onchange="mudar_ano(this.value);" class="texto"', $ano).($Aplic->profissional && $qnt_anos ? '<a href="javascript: void(0)" onclick="popImportar();">'.imagem('icones/importar_p.png', 'Importar' , 'Clique neste ícone '.imagem('importar_p.png').' para importar os dados de texto do indicador registrados noutro ano.').'</a>'.dicaF(): '').'</td></tr>';



	if ($exibir['codigo']) echo '<tr><td align="right">'.dica('Código', 'Escreva, caso exista, o código do indicador.').'Código:'.dicaF().'</td><td><input type="text" style="width:280px;" class="texto" name="pratica_indicador_codigo" value="'.(isset($obj->pratica_indicador_codigo) ? $obj->pratica_indicador_codigo : '').'" size="30" maxlength="255" /></td></tr>';



	$setor = array('' => '') + getSisValor('IndicadorSetor');
	if ($exibir['setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce o indicador.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'pratica_indicador_setor', 'style="width:284px;" class="texto" onchange="mudar_segmento();"', $obj->pratica_indicador_setor).'</td></tr>';
	$segmento=array('' => '');
	if ($obj->pratica_indicador_setor){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo="IndicadorSegmento"');
		$sql->adOnde('sisvalor_chave_id_pai="'.$obj->pratica_indicador_setor.'"');
		$sql->adOrdem('sisvalor_valor');
		$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce o indicador.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'pratica_indicador_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao();"', $obj->pratica_indicador_segmento).'</div></td></tr>';
	$intervencao=array('' => '');
	if ($obj->pratica_indicador_segmento){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo="IndicadorIntervencao"');
		$sql->adOnde('sisvalor_chave_id_pai="'.$obj->pratica_indicador_segmento.'"');
		$sql->adOrdem('sisvalor_valor');
		$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce o indicador.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'pratica_indicador_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao();"', $obj->pratica_indicador_intervencao).'</div></td></tr>';

	$tipo_intervencao=array('' => '');
	if ($obj->pratica_indicador_intervencao){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo="IndicadorTipoIntervencao"');
		$sql->adOnde('sisvalor_chave_id_pai="'.$obj->pratica_indicador_intervencao.'"');
		$sql->adOrdem('sisvalor_valor');
		$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence o indicador.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'pratica_indicador_tipo_intervencao', 'style="width:284px;" class="texto"', $obj->pratica_indicador_tipo_intervencao).'</div></td></tr>';

	}


echo '<tr><td align="right" nowrap="nowrap">'.dica('Composição de Pontuação', 'Os indicadores de composição são resultados da média ponderada da pontuação obtida de outros indicadores.').'Composição:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.pratica_indicador_composicao.checked) {limpar_botoes(); env.pratica_indicador_composicao.checked=true; document.getElementById(\'botao_composicao\').style.display=\'\'; } else {document.getElementById(\'botao_composicao\').style.display=\'none\'; }" class="texto" name="pratica_indicador_composicao" value="1" '.($obj->pratica_indicador_composicao ? 'checked="checked"' : '').' /></td><td id="botao_composicao" '.($obj->pratica_indicador_composicao ? 'style="display:"' : 'style="display:none"').'>'.botao('composição', 'Composição','Abrir uma janela onde poderá selecionar quais são os indicadores que compoem este ora selecionado.','','popComposicao()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Fórmula de Indicadores', 'Os indicadores oriundo de fórmulas de indicadoeres fazem uso de outros indicadores, atravéz de uma fórmula pré-estabelecida para obterem seus valores.<br><br>Particularmente útil quando se tratar de indicadores que não tem existencia própria( ex: lucro líquido, que é o lucro bruto abatido do total das despesas).').'Fórmula de indicadores:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.pratica_indicador_formula.checked) {limpar_botoes(); env.pratica_indicador_formula.checked=true; document.getElementById(\'botao_formula\').style.display=\'\'; document.getElementById(\'texto_formula\').style.display=\'\';} else {document.getElementById(\'botao_formula\').style.display=\'none\'; document.getElementById(\'texto_formula\').style.display=\'none\';}" class="texto" name="pratica_indicador_formula" value="1" '.(isset($obj->pratica_indicador_formula) && $obj->pratica_indicador_formula ? 'checked="checked"' : '').' /></td><td id="botao_formula" '.($obj->pratica_indicador_formula ? 'style="display:"' : 'style="display:none"').'>'.botao('fórmula', 'Fórmula Matemática','Abrir uma janela onde poderá compor a fórmula matemática, utilizando outros indicadores, que fornecerá o valor deste indicador.','','popFormula()').'</td></tr></table></td></tr>';
echo '<tr><td></td><td id="texto_formula" class="realce">'.$obj->pratica_indicador_calculo.'</td></tr>';

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap" >'.dica('Fórmula de Variáveis', 'Os indicadores oriundo de fórmulas de variáveis retornam um cálculo em cima de um grupo de valores inseridos.').'Fórmula de variáveis:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.pratica_indicador_formula_simples.checked) {limpar_botoes(); env.pratica_indicador_formula_simples.checked=true; document.getElementById(\'botao_formula_simples\').style.display=\'\'; document.getElementById(\'texto_formula_simples\').style.display=\'\';} else {document.getElementById(\'botao_formula_simples\').style.display=\'none\'; document.getElementById(\'texto_formula_simples\').style.display=\'none\';}" class="texto" name="pratica_indicador_formula_simples" value="1" '.(isset($obj->pratica_indicador_formula_simples) && $obj->pratica_indicador_formula_simples ? 'checked="checked"' : '').' /></td><td id="botao_formula_simples" '.($obj->pratica_indicador_formula_simples ? 'style="display:"' : 'style="display:none"').'>'.botao('fórmula', 'Fórmula de Variáveis','Abrir uma janela onde poderá compor a fórmula de variáveis, utilizando um grupo de valore inseridos.','','popFormulaSimples()').'</td></tr></table></td></tr>';
echo '<tr><td></td><td id="texto_formula_simples" class="realce" '.($obj->pratica_indicador_formula_simples ? 'style="display:"' : 'style="display:none"').'>'.$obj->pratica_indicador_calculo.'</td></tr>';

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Externo', 'Os indicadores cujos valores são obtidos de bases SQL externas.').'Indicador externo:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.pratica_indicador_externo.checked) {limpar_botoes(); env.pratica_indicador_externo.checked=true;  document.getElementById(\'botao_externo\').style.display=\'\'; } else {document.getElementById(\'botao_externo\').style.display=\'none\'; }" class="texto" name="pratica_indicador_externo" value="1" '.(isset($obj->pratica_indicador_externo) && $obj->pratica_indicador_externo ? 'checked="checked"' : '').' /></td><td id="botao_externo" '.($obj->pratica_indicador_externo ? 'style="display:"' : 'style="display:none"').'>'.botao('externo', 'Indicador Externo','Abrir uma janela onde poderá configurar a conexão SQL da base externa para este indicador.','','popExterno()').'</td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Checklist', 'Os indicadores oriundo de checklist retiram seus valores dos resultados dos checklist executados.').'Checklist:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" onclick="if (env.checklist.checked) {limpar_botoes(); env.checklist.checked=true; document.getElementById(\'botao_checklist\').style.display=\'\'; document.getElementById(\'valor_checklist\').style.display=\'\';} else {document.getElementById(\'botao_checklist\').style.display=\'none\'; document.getElementById(\'valor_checklist\').style.display=\'none\';}" class="texto" name="checklist" value="1" '.($obj->pratica_indicador_checklist ? 'checked="checked"' : '').' /></td><td id="botao_checklist" '.($obj->pratica_indicador_checklist ? 'style="display:"' : 'style="display:none"').'><table><tr><td><input type="hidden" id="pratica_indicador_checklist" name="pratica_indicador_checklist" value="'.($obj->pratica_indicador_checklist ? $obj->pratica_indicador_checklist : null).'" /><input type="text" id="nome_checklist" name="nome_checklist" value="'.nome_checklist($obj->pratica_indicador_checklist).'" style="width:255px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr></table></td></tr>';

if ($Aplic->profissional) echo '<tr id="valor_checklist" '.($obj->pratica_indicador_checklist ? 'style="display:"' : 'style="display:none"').'><td align="right" nowrap="nowrap">'.dica('Valor do Checklist', 'Caso esteja marcado, em vez do resultado do checklist ser a porcentagem de respostas marcadas como sim, o retorno será a soma dos pesos das linhas marcadas como sim.').'Valor do checklist:'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" class="texto" name="pratica_indicador_checklist_valor" value="1" '.(isset($obj->pratica_indicador_checklist_valor) && $obj->pratica_indicador_checklist_valor ? 'checked="checked"' : '').' /></td></td></tr></table></td></tr>';
else echo '<input type="hidden" name="pratica_indicador_checklist_valor" value="" id="valor_checklist" />';


$social=$Aplic->modulo_ativo('social');
if ($Aplic->profissional){

	$sql->adTabela('campo_formulario');
	$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
	$sql->adOnde('campo_formulario_tipo = \'tarefa\'');
	$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
	$exibir_tarefa = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
	$sql->limpar();

	$parametros_projeto = array(
		'' => '',
		'fisico_previsto' => 'Físico - Previsto até a data atual',
		'fisico_velocidade' => 'Físico - Velocidade',
		'progresso' => 'Físico - Percentagem executada',
		'financeiro_previsto' => 'Financeiro - Previsto até a data atual',
		'total_estimado_total' => 'Financeiro - Previsto até o final',
		'financeiro_velocidade' => 'Financeiro - Velocidade',
		'recurso_previsto' => 'Recursos - Custo até a data atual',
		'recurso_valor_agregado' => 'Recursos - Valor agregado',
		'recurso_ept' => 'Recursos - Estimativa para Terminar (EPT)',
		'recurso_previsto_total' => 'Recursos - Custo até o final',
		'recurso_gasto' => 'Recursos - Gasto',
		'mo_previsto' => 'Mão de obra - Custo previsto até a data atual',
		'mo_valor_agregado' => 'Mão de obra - Valor agregado',
		'mao_obra_ept' => 'Mão de obra - Estimativa para Terminar (EPT)',
		'mo_previsto_total' => 'Mão de obra - Custo até o final',
		'mo_gasto' => 'Mão de obra - Gasto',
		'custo_estimado_hoje' => 'Planilha de custo - Até a data atual',
		'custo_valor_agregado' => 'Planilha de custo - Valor agregado',
		'custo_ept' => 'Planilha de custo - Estimativa para Terminar (EPT)',
		'custo_estimado' => 'Planilha de custo -  Até o final',
		'valor_agregado' => 'Valor agregado',
		'ept' => 'Estimativa para Terminar (EPT)',
		'idc' => 'Índice de Desempenho de Custos (IDC)',
		'idpt' => 'Índice de desempenho para Término (IDPT)',
		'total_recursos' => 'Recursos financeiros alocados',
		'gasto_efetuado' => 'Planilha de Gastos - Total',
		'gasto_registro' => 'Gastos extras' ,
		'homem_hora' => 'Homem/Hora',
		'ata_acao' => 'Deliberações de atas de reunião concluídas'
		);
	if ($exibir_tarefa['empregos_execucao']) $parametros_projeto['emprego_obra']='Empregos gerados durante a execução';
	if ($exibir_tarefa['empregos_diretos']) $parametros_projeto['emprego_direto']='Empregos diretos após a conclusão';
	if ($exibir_tarefa['empregos_indiretos']) $parametros_projeto['emprego_indireto']='Empregos indiretos após a conclusão';
	if ($exibir_tarefa['adquirido']) $parametros_projeto['quantidade_adquirida']='Quantidade adquirida';
	if ($exibir_tarefa['previsto']) $parametros_projeto['quantidade_prevista']='Quantidade prevista';
	if ($exibir_tarefa['realizado']) $parametros_projeto['quantidade_realizada']='Quantidade realizada';
	if ($exibir_tarefa['previsto'] && $exibir_tarefa['realizado']) $parametros_projeto['realizada_prevista']='Quantidade realizada pela prevista (%)';
	if ($exibir_tarefa['previsto'] && $exibir_tarefa['adquirido']) $parametros_projeto['adquirida_prevista']='Quantidade adquirida pela prevista (%)';



	$parametros_tarefa = array(
		'' => '',
		'fisico_previsto' => 'Físico - Previsto até a data atual',
		'fisico_velocidade' => 'Físico - Velocidade',
		'financeiro_previsto' => 'Financeiro - Previsto até a data atual',
		'total_estimado_total' => 'Financeiro - Previsto até o final',
		'financeiro_velocidade' => 'Financeiro - Velocidade',
		'recurso_previsto' => 'Recursos - Custo até a data atual',
		'recurso_valor_agregado' => 'Recursos - Valor agregado',
		'recurso_ept' => 'Recursos - Estimativa para Terminar (EPT)',
		'recurso_previsto_total' => 'Recursos - Custo até o final',
		'recurso_gasto' => 'Recursos - Gasto',
		'mo_previsto' => 'Mão de obra - Custo previsto até a data atual',
		'mo_valor_agregado' => 'Mão de obra - Valor agregado',
		'mao_obra_ept' => 'Mão de obra - Estimativa para Terminar (EPT)',
		'mo_previsto_total' => 'Mão de obra - Custo até o final',
		'mo_gasto' => 'Mão de obra - Gasto',
		'custo_estimado_hoje' => 'Planilha de custo - Até a data atual',
		'custo_valor_agregado' => 'Planilha de custo - Valor agregado',
		'custo_ept' => 'Planilha de custo - Estimativa para Terminar (EPT)',
		'custo_estimado' => 'Planilha de custo -  Até o final',
		'valor_agregado' => 'Valor agregado',
		'ept' => 'Estimativa para Terminar (EPT)',
		'idc' => 'Índice de Desempenho de Custos (IDC)',
		'idpt' => 'Índice de desempenho para Término (IDPT)',
		'progresso' => 'Percentagem executada',
		'total_recursos' => 'Recursos financeiros alocados',
		'gasto_efetuado' => 'Planilha de Gastos - Total',
		'gasto_registro' => 'Gastos extras',
		'ata_acao' => 'Deliberações de atas de reunião concluídas'
		);
	if ($exibir_tarefa['empregos_execucao']) $parametros_tarefa['emprego_obra']='Empregos gerados durante a execução';
	if ($exibir_tarefa['empregos_diretos']) $parametros_tarefa['emprego_direto']='Empregos diretos após a conclusão';
	if ($exibir_tarefa['empregos_indiretos']) $parametros_tarefa['emprego_indireto']='Empregos indiretos após a conclusão';
	if ($exibir_tarefa['adquirido']) $parametros_tarefa['quantidade_adquirida']='Quantidade adquirida';
	if ($exibir_tarefa['previsto']) $parametros_tarefa['quantidade_prevista']='Quantidade prevista';
	if ($exibir_tarefa['realizado']) $parametros_tarefa['quantidade_realizada']='Quantidade realizada';
	if ($exibir_tarefa['previsto'] && $exibir_tarefa['realizado']) $parametros_tarefa['realizada_prevista']='Quantidade realizada pela prevista (%)';
	if ($exibir_tarefa['previsto'] && $exibir_tarefa['adquirido']) $parametros_tarefa['adquirida_prevista']='Quantidade adquirida pela prevista (%)';

	$parametros_acao = array(
		'' => '',
		'progresso' => 'Físico - Percentagem executada'
		);

	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor de '.ucfirst($config['projeto']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_projeto'].' '.$config['projeto'].'.').'Valor de '.$config['projeto'].':'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" name="pratica_indicador_campo_projeto" value="1" '.($obj->pratica_indicador_campo_projeto ? 'checked="checked"' : '').' onclick="if (env.pratica_indicador_campo_projeto.checked) {limpar_botoes(); env.pratica_indicador_campo_projeto.checked=true; document.getElementById(\'combo_filtro\').style.display=\'\'; document.getElementById(\'botao_pratica_indicador_campo_projeto\').style.display=\'\';} else {document.getElementById(\'combo_filtro\').style.display=\'none\'; document.getElementById(\'botao_pratica_indicador_campo_projeto\').style.display=\'none\';}" class="texto"  /></td><td id="botao_pratica_indicador_campo_projeto" '.($obj->pratica_indicador_campo_projeto ? 'style="display:"' : 'style="display:none"').'><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($parametros_projeto, 'pratica_indicador_parametro_projeto', 'class="texto" style="width:310px;"', $obj->pratica_indicador_parametro_projeto).'</td></tr></table></td></tr></table></td></tr>';

	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor de '.ucfirst($config['tarefa']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Valor de '.$config['tarefa'].':'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" name="pratica_indicador_campo_tarefa" value="1" '.($obj->pratica_indicador_campo_tarefa ? 'checked="checked"' : '').' onclick="if (env.pratica_indicador_campo_tarefa.checked) {limpar_botoes(); env.pratica_indicador_campo_tarefa.checked=true; document.getElementById(\'botao_pratica_indicador_campo_tarefa\').style.display=\'\';} else {document.getElementById(\'botao_pratica_indicador_campo_tarefa\').style.display=\'none\';}" class="texto"  /></td><td id="botao_pratica_indicador_campo_tarefa" '.($obj->pratica_indicador_campo_tarefa ? 'style="display:"' : 'style="display:none"').'><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($parametros_tarefa, 'pratica_indicador_parametro_tarefa', 'class="texto" style="width:310px;"', $obj->pratica_indicador_parametro_tarefa).'</td></tr></table></td></tr></table></td></tr>';

	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor de '.ucfirst($config['acao']), 'Este indicador retira seu valor automaticamente de um dos campos d'.$config['genero_acao'].' '.$config['acao'].'.').'Valor de '.$config['acao'].':'.dicaF().'</td><td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td><input type="checkbox" name="pratica_indicador_campo_acao" value="1" '.($obj->pratica_indicador_campo_acao ? 'checked="checked"' : '').' onclick="if (env.pratica_indicador_campo_acao.checked) {limpar_botoes(); env.pratica_indicador_campo_acao.checked=true; document.getElementById(\'botao_pratica_indicador_campo_acao\').style.display=\'\';} else {document.getElementById(\'botao_pratica_indicador_campo_acao\').style.display=\'none\';}" class="texto"  /></td><td id="botao_pratica_indicador_campo_acao" '.($obj->pratica_indicador_campo_acao ? 'style="display:"' : 'style="display:none"').'><table cellspacing=0 cellpadding=0><tr><td>'.selecionaVetor($parametros_acao, 'pratica_indicador_parametro_acao', 'class="texto" style="width:310px;"', $obj->pratica_indicador_parametro_acao).'</td></tr></table></td></tr></table></td></tr>';

	echo '<tr><td id="combo_filtro" colspan=2 '.($obj->pratica_indicador_campo_projeto ? 'style="display:"' : 'style="display:none"').'><fieldset><legend class=texto style="color: black;">'.dica('Filtros','Lista de filtros que podem ser aplicados n'.$config['genero_tarefa'].'s '.$config['tarefas'].' d'.$config['genero_projeto'].' '.$config['projeto'].'.').'&nbsp;<b>Filtros</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';

	echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';

	$status_tarefa = array(0=>'')+getSisValor('StatusTarefa');
	echo '<tr><td align="right" nowrap="nowrap" width="126">'.dica('Status', ucfirst($config['genero_tarefa']).' '.$config['tarefa'].' deve ter um status que reflita sua situação atual.').'Status:'.dicaF().'</td><td>'.selecionaVetor($status_tarefa, 'pratica_indicador_filtro_status', 'size="1" class="texto" style="width:284px;"').'</td></tr>';
	$tarefa_tipos=vetor_campo_sistema('TipoTarefa', null);
	if ($exibir_tarefa['tipo']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de '.ucfirst($config['tarefa']), 'Definir o tipo d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Tipo de '.$config['tarefa'].':'.dicaF().'</td><td><div id="combo_tarefa_tipo">'.selecionaVetor($tarefa_tipos, 'pratica_indicador_filtro_tipo', 'class="texto" size=1 style="width:284px;" onchange="mudar_tarefa_tipo();"').'</div></td></tr>';
	$prioridade_tarefa = array(0=>'')+getSisValor('PrioridadeTarefa');
	echo '<tr><td align="right" nowrap="nowrap" width="126">'.dica('Prioridade', 'A prioridade para fins de filtragem.').'Prioridade:'.dicaF().'</td><td>'.selecionaVetor($prioridade_tarefa, 'pratica_indicador_filtro_prioridade', 'size="1" class="texto" style="width:284px;"').'</td></tr>';
	$setor = array('' => '') + getSisValor('TarefaSetor');
	if ($exibir_tarefa['setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'pratica_indicador_filtro_setor', 'style="width:284px;" class="texto" onchange="mudar_segmento_tarefa();"').'</td></tr>';
	$segmento=array('' => '');
	if ($exibir_tarefa['segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento_tarefa">'.selecionaVetor($segmento, 'pratica_indicador_filtro_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao_tarefa();"').'</div></td></tr>';
	$intervencao=array('' => '');
	if ($exibir_tarefa['intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao_tarefa">'.selecionaVetor($intervencao, 'pratica_indicador_filtro_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao_tarefa();"').'</div></td></tr>';
	$tipo_intervencao=array('' => '');
	if ($exibir_tarefa['tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao_tarefa">'.selecionaVetor($tipo_intervencao, 'pratica_indicador_filtro_tipo_intervencao', 'style="width:284px;" class="texto"').'</div></td></tr>';


	if ($social) {
		require_once BASE_DIR.'/modulos/social/social.class.php';
		$lista_programas=array('' => '');
		$sql->adTabela('social');
		$sql->adCampo('social_id, social_nome');
		$sql->adOrdem('social_nome');
		$lista_programas+= $sql->listaVetorChave('social_id', 'social_nome');
		$sql->limpar();
		echo '<tr><td nowrap="nowrap" align="right">'.dica('Programa Social', 'A qual programa social pertence '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Programa:'.dicaF().'</td><td nowrap="nowrap" align="left">'. selecionaVetor($lista_programas, 'pratica_indicador_filtro_social', 'size="1" style="width:284px;" class="texto" onchange="mudar_acao_tarefa()"') .'</td></tr>';
		echo '<tr><td align="right">'.dica('Ação Social', 'Escolha a ação social d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Ação:'.dicaF().'</td><td nowrap="nowrap" align="left"><div id="acao_combo_tarefa">'.selecionar_acao_para_ajax(null, 'pratica_indicador_filtro_acao', 'size="1" style="width:284px;" class="texto"', '', null, false).'</div></td></tr>';
		}
	else {
		echo '<input type="hidden" name="pratica_indicador_filtro_social" id="pratica_indicador_filtro_social" value="" />';
		echo '<input type="hidden" name="pratica_indicador_filtro_acao" id="pratica_indicador_filtro_acao" value="" />';
		echo '<input type="hidden" name="pratica_indicador_filtro_comunidade" id="pratica_indicador_filtro_comunidade" value="" />';
		}

	$estado=array('' => '');
	$sql->adTabela('estado');
	$sql->adCampo('estado_sigla, estado_nome');
	$sql->adOrdem('estado_nome');
	$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
	$sql->limpar();
	echo '<tr><td align="right">'.dica('Estado', 'Escolha na caixa de opção à direita o Estado d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Estado:'.dicaF().'</td><td>'.selecionaVetor($estado, 'pratica_indicador_filtro_estado', 'size="1" class="texto" onchange="mudar_cidades_tarefa();" style="width:284px;"').'</td></tr>';
	echo '<tr><td align="right">'.dica('Município', 'Escreva o município d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Município:'.dicaF().'</td><td><div id="combo_cidade_tarefa">'.selecionar_cidades_para_ajax(null, 'pratica_indicador_filtro_cidade', 'class="texto" '.($social ? 'onchange="mudar_comunidades_tarefa()"' : '').' style="width:284px;"', '', null, true, false).'</div></td></tr>';
	if ($social) {
		$comunidades=array(''=>'');
		echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade onde se aplica '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade_tarefa">'.selecionar_comunidade_para_ajax(null,'pratica_indicador_filtro_comunidade', 'class="texto" style="width:284px;"', '', null, false).'</div></td></tr>';
		}


	echo '<tr><td align="right">'.dica('Texto', 'Escreva um texto para filtragem.').'Texto:'.dicaF().'</td><td><input type="text" style="width:284px;" class="texto" name="pratica_indicador_filtro_texto" id="pratica_indicador_filtro_texto" value="" size="30" maxlength="255" /></td></tr>';
	echo '</table></td><td id="adicionar_filtro" style="display:" width=50 align=center><a href="javascript: void(0);" onclick="incluir_filtro();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir o filtro.').'</a></td>';
	echo '<td id="confirmar_filtro" style="display:none" width=50 align=center><a href="javascript: void(0);" onclick="limpar_filtro(); document.getElementById(\'adicionar_filtro\').style.display=\'\';	document.getElementById(\'confirmar_filtro\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição do filtro .').'</a><a href="javascript: void(0);" onclick="incluir_filtro();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição do filtro.').'</a></td></tr></table></td></tr>';
	echo '<tr><td colspan=20 align=center><div id="combo_filtros"></div></td></tr>';
	echo '</table></fieldset></td></tr>';
	echo '<input type="hidden" id="pratica_indicador_filtro_id" name="pratica_indicador_filtro_id" value="" />';
	}


echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Superior', 'Escolha, caso exista, um indicador superior à este.').'Superior:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($obj->pratica_indicador_superior).'" style="width:280px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';


$dentro_referencial='';
if ($obj->pratica_indicador_trava_data_meta){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('cias','cias','cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_nome, cia_nome');
	if ($obj->pratica_indicador_trava_meta) $sql->adOnde('pratica_indicador_id='.$obj->pratica_indicador_trava_data_meta);
	else $sql->adOnde('pratica_indicador_id='.$obj->pratica_indicador_trava_referencial);
	$linha=$sql->Linha();
	$sql->limpar();
	$dentro_data = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro_data .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Indicador</b></td><td>'.$linha['pratica_indicador_nome'].'</td></tr>';
	$dentro_data .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['organizacao'].'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
	$dentro_data .= '</table>';
	}



if ($obj->pratica_indicador_trava_meta){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('cias','cias','cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_nome, cia_nome');
	$sql->adOnde('pratica_indicador_id='.$obj->pratica_indicador_trava_meta);
	$linha=$sql->Linha();
	$sql->limpar();
	$dentro_meta = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro_meta .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Indicador</b></td><td>'.$linha['pratica_indicador_nome'].'</td></tr>';
	$dentro_meta .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['organizacao'].'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
	$dentro_meta .= '</table>';
	}

if ($obj->pratica_indicador_trava_meta) $dentro=$dentro_meta;
else $dentro=$dentro_referencial;

if ($obj->pratica_indicador_trava_referencial){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('cias','cias','cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_nome, cia_nome');
	$sql->adOnde('pratica_indicador_id='.$obj->pratica_indicador_trava_referencial);
	$linha=$sql->Linha();
	$sql->limpar();
	$dentro_referencial = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro_referencial .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Indicador</b></td><td>'.$linha['pratica_indicador_nome'].'</td></tr>';
	$dentro_referencial .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['organizacao'].'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
	$dentro_referencial .= '</table>';
	}


$tipo_polaridade=array(0 => 'Melhor se menor', 1 => 'Melhor se maior', 2 => 'Melhor se no centro');
echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Polaridade', 'Qual a polaridade dos valores do indicador.').'Polaridade'.($obj->pratica_indicador_trava_referencial || $obj->pratica_indicador_trava_meta ? imagem('icones/cadeado.gif','Travado','Este campo foi travado por um indicador superior do qual este faz parte dentro de uma composição.'.$dentro) : '').':'.dicaF().'</td><td width="100%" colspan=2>'.selecionaVetor($tipo_polaridade, 'pratica_indicador_sentido', 'class="texto"'.($obj->pratica_indicador_trava_referencial || $obj->pratica_indicador_trava_meta ? ' disabled=disabled' : ''), ($pratica_indicador_id ? $obj->pratica_indicador_sentido : 1)).'</td></tr>';

$tolerancia=getSisValor('IndicadorTolerancia','','','sisvalor_id');

echo '<tr><td align="right" nowrap="nowrap">'.dica('Tolerância', 'Qual a tolerância da pontuação obtida em relação à meta.<br>Ex:Com a tolerância de 15% uma pontuação final de 85% seria visualizada como 100%.').'Tolerância'.dicaF().':</td><td width="100%" colspan="2">'.selecionaVetor($tolerancia, 'pratica_indicador_tolerancia', 'size="1" class="texto"', $obj->pratica_indicador_tolerancia).'%</td><tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Unidade dos Valor', 'Qual a unidade utilizada para os valor. Ex: %, Unidades, R$, etc.').'Unidade utilizada'.dicaF().($obj->pratica_indicador_trava_referencial || $obj->pratica_indicador_trava_meta ? imagem('icones/cadeado.gif','Travado','O referencial foi travado por um indicador superior do qual este faz parte dentro de uma composição.'.$dentro) : '').':</td><td width="100%" colspan="2"><input type="text" name="pratica_indicador_unidade" value="'.($obj->pratica_indicador_unidade ? $obj->pratica_indicador_unidade : '').'" class="texto" '.($obj->pratica_indicador_trava_referencial || $obj->pratica_indicador_trava_meta ? 'readonly="readonly"': '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Gráfico', 'Escolha qual a melhor representação gráfica para os valores do indicador.').'Tipo de gráfico:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($tipo_grafico, 'pratica_indicador_tipografico', 'class="texto"', ($obj->pratica_indicador_tipografico ? $obj->pratica_indicador_tipografico : 'barra')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Acumulação', 'Escolha qual a forma de acumulação dos valores do indicador inseridos no período.').'Tipo de acumulação:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($tipo_acumulacao, 'pratica_indicador_acumulacao', 'class="texto"', ($obj->pratica_indicador_acumulacao ? $obj->pratica_indicador_acumulacao : 'media_simples')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Período', 'Escolha qual o melhor período para agrupar valores.').'Período:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($tipo_agrupamento, 'pratica_indicador_agrupar', 'class="texto"', ($obj->pratica_indicador_agrupar ? $obj->pratica_indicador_agrupar : 'ano')).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Pontuação do Período Anterior', 'Caso se vertifica a pontuação apenas do período anterior (pois o atual não fecha até o final do mesmo) é importante ser marcado este campo.').'Pont. período anterior:'.dicaF().'</td><td><input type="checkbox" value="1" name="pratica_indicador_periodo_anterior" '.($obj->pratica_indicador_periodo_anterior ? 'checked="checked"' : '').' /></td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Mostrar Valores', 'Marque caso queira que o gráfico mostre legenda com os valores de cada ponto marcado.').'Mostrar valores:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" class="texto" name="pratica_indicador_mostrar_valor" value="1" '.($obj->pratica_indicador_mostrar_valor ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Mostrar título', 'Marque caso queira que o gráfico apresente no título o nome do indicador.').'Mostrar título:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" class="texto" name="pratica_indicador_mostrar_titulo" value="1" '.($obj->pratica_indicador_mostrar_titulo ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Mostrar Máximos e Mínimos', 'Marque caso queira que o gráfico apresente a os valores extremos para cada ponto.').'Mostrar máx. e mín.:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" class="texto" name="pratica_indicador_max_min" value="1" '.($obj->pratica_indicador_max_min ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Número de Pontos à Plotar', 'Qual a quantidade de valores distintos a plotar no gráfico.<br><br>Ex: gráfico mensal de janeiro à dezembro teria 12 pontos.').'Nr de pontos:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="pratica_indicador_nr_pontos" style="width:30px;" value="'.($obj->pratica_indicador_nr_pontos ? $obj->pratica_indicador_nr_pontos : '3').'" class="texto" /></td></tr>';


echo '<tr><td colspan=2><fieldset><legend class=texto style="color: black;">'.dica('Meta','Lista de metas vinculadas a este indicador.').'&nbsp;<b>Metas</b>&nbsp'.dicaF().'</legend><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><table cellspacing=0 cellpadding=0>';
echo '<tr><td><fieldset><table cellspacing=0 cellpadding=0>';

echo '<tr><td align="right" nowrap="nowrap" width="90">'.dica('Meta', 'Qual o valor a ser alcançado pelo indicador para que seje considerado excelente.').'Meta'.dicaF().':</td><td width="100%" colspan="2"><input type="text" id="pratica_indicador_meta_valor_meta" name="pratica_indicador_meta_valor_meta" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
if ($Aplic->profissional){
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Usar Valor de Ciclo Anterior como Meta', 'Marque caso queira que a meta seja uma percentagem do périodo anterior.<br><br>Ex: Caso se deseje que a meta seje 10% superior ao conseguido no último ciclo, o campo meta deverá ter o valor 1,1 (10% normalizado) e esta caixa deverá estar marcada.').'Usar ciclo anterior:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" class="texto" id="pratica_indicador_meta_proporcao"  name="pratica_indicador_meta_proporcao" value="1" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível Bom', 'Qual o valor do indicador é aceitável com bom.').'Nível bom:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" id="pratica_indicador_meta_valor_meta_boa" name="pratica_indicador_meta_valor_meta_boa" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível Regular', 'Qual o valor do indicador é aceitável com regulr.').'Nível regular:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" id="pratica_indicador_meta_valor_meta_regular" name="pratica_indicador_meta_valor_meta_regular" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível Ruim', 'Qual o valor do indicador é considerado ruim.').'Nível ruim:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" id="pratica_indicador_meta_valor_meta_ruim" name="pratica_indicador_meta_valor_meta_ruim" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
	}
else {
	echo '<input type="hidden" id="pratica_indicador_meta_proporcao" name="pratica_indicador_meta_proporcao" value="" />';
	echo '<input type="hidden" id="pratica_indicador_meta_valor_meta_boa" name="pratica_indicador_meta_valor_meta_boa" value="" />';
	echo '<input type="hidden" id="pratica_indicador_meta_valor_meta_regular" name="pratica_indicador_meta_valor_meta_regular" value="" />';
	echo '<input type="hidden" id="pratica_indicador_meta_valor_meta_ruim" name="pratica_indicador_meta_valor_meta_ruim" value="" />';
	}
echo '<tr><td align="right" nowrap="nowrap">'.dica('Início da Meta', 'Qual a data estipulada para iniciar a utilização da meta.').'Início da meta'.dicaF().':</td><td width="100%" colspan="2"><input type="hidden" name="pratica_indicador_meta_data" id="pratica_indicador_meta_data" value="'.date('Y').'-01-01" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\',\'pratica_indicador_meta_data\');" value="01/01/'.date('Y').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data em que a meta entrará em vigor.').'<a href="javascript: void(0);" ><img id="f_btn3" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data Limite para a Meta', 'Qual a data estipulada para alcançar a meta.').'Data limite da meta'.dicaF().':</td><td width="100%" colspan="2"><input type="hidden" name="pratica_indicador_meta_data_meta" id="pratica_indicador_meta_data_meta" value="'.date('Y').'-12-31" /><input type="text" name="data" style="width:70px;" id="data" onchange="setData(\'env\', \'data\',\'pratica_indicador_meta_data_meta\');" value="31/12/'.date('Y').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data em que a meta deverá ser alcançada.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor do Referencial Comparativo', 'Qual o valor do referencial comparativo.').'Valor do referencial'.dicaF().':</td><td width="100%" colspan="2"><input type="text" name="pratica_indicador_meta_valor_referencial" id="pratica_indicador_meta_valor_referencial" onkeypress="return entradaNumerica(event, this, true, true);" value="" class="texto" /></td></tr>';
echo '</table></fieldset></td>';

echo '<input type="hidden" id="pratica_indicador_meta_id" name="pratica_indicador_meta_id" value="" /></table></td><td id="adicionar_meta" style="display:" width=50 align=center><a href="javascript: void(0);" onclick="incluir_meta();">'.imagem('icones/adicionar.png','Incluir','Clique neste ícone '.imagem('icones/adicionar.png').' para incluir a meta.').'</a></td>';
echo '<td id="confirmar_meta" style="display:none" width=50 align=center><a href="javascript: void(0);" onclick="limpar_meta(); document.getElementById(\'adicionar_meta\').style.display=\'\';	document.getElementById(\'confirmar_meta\').style.display=\'none\';">'.imagem('icones/cancelar.png','Cancelar','Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar a edição da meta .').'</a><a href="javascript: void(0);" onclick="incluir_meta();">'.imagem('icones/ok.png','Confirmar','Clique neste ícone '.imagem('icones/ok.png').' para confirmar a edição da meta.').'</a></td></tr></table></td></tr>';
echo '</table></td></tr>';


$sql->adTabela('pratica_indicador_meta');
$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
$sql->adCampo('pratica_indicador_meta_id, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_valor_meta, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim, pratica_indicador_meta_proporcao');
$sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_meta_data');
$metas = $sql->lista();
$sql->limpar();
echo '<tr><td colspan=20 align=center><div id="metas">';
if (count($metas)){
	echo '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Ciclo Anterior</th><th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th>Referencial</th><th></th></tr>';
	foreach($metas as $linha) {
		echo '<tr>';
		echo '<td align=right>'.number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.').'</td>';
		if ($Aplic->profissional){
			echo '<td align=center>'.($linha['pratica_indicador_meta_proporcao'] ? 'X' : '&nbsp;').'</td>';
			echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_boa'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_regular'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
			echo '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_ruim'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
			}
		echo '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';
		echo '<td>'.($linha['pratica_indicador_meta_valor_referencial'] != null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : '&nbsp;').'</td>';
		echo '<td><a href="javascript: void(0);" onclick="editar_meta('.$linha['pratica_indicador_meta_id'].');">'.imagem('icones/editar.gif', 'Editar Meta', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta meta.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta meta?\')) {excluir_meta('.$linha['pratica_indicador_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir Meta', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta meta.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';
echo '</table></fieldset></td></tr>';

echo '<tr><td align="right">'.dica('Ativo', 'Caso o indicador ainda esteja ativo deverá estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="pratica_indicador_ativo" '.($obj->pratica_indicador_ativo || !$pratica_indicador_id ? 'checked="checked"' : '').' /></td></tr>';
if ($Aplic->profissional) echo '<tr><td nowrap="nowrap" align="right">'.dica('Alerta Ativo', 'Caso esteja marcado, o indicador será incluído no sistema de alertas automáticos (precisa ser executado em background o arquivo server/alertas/alertas_pro.php).').'Alerta ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="pratica_indicador_alerta" '.($obj->pratica_indicador_alerta ? 'checked="checked"' : '').' /></td></tr>';


echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador de Resultado','Marque este campo se este indicador é específico de '.($config['genero_marcador']=='a'? 'uma' : 'um').' '.$config['marcador'].' de resultado.').'Resultado:'.dicaF().'</td><td width="100%" colspan="2"><input type="checkbox" onclick="if (env.pratica_indicador_resultado.checked) {document.getElementById(\'caixa_pratica_indicador_resultado\').style.display=\'\'; mudar_pauta();} else document.getElementById(\'caixa_pratica_indicador_resultado\').style.display=\'none\';" class="texto" name="pratica_indicador_resultado" value="1" '.($obj->pratica_indicador_resultado ? 'checked="checked"' : '').' /></td></tr>';


echo '</table></td>';

echo '<td width="50%" valign="top"><table cellspacing=0 cellpadding=0 width="100%">';

if ($exibir['pratica_indicador_descricao']) echo '<tr><td align="right" nowrap="nowrap" style="width:100px">'.dica('Descrição', 'A descrição do indicador.').'Descrição:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_descricao" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_descricao']) ? $requisito['pratica_indicador_requisito_descricao'] : '').'</textarea></td></tr>';

$cincow2h=($exibir['pratica_indicador_oque'] && $exibir['pratica_indicador_quem'] && $exibir['pratica_indicador_quando'] && $exibir['pratica_indicador_onde'] && $exibir['pratica_indicador_porque'] && $exibir['pratica_indicador_como'] && $exibir['pratica_indicador_quanto']);
if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['pratica_indicador_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:115px">'.dica('O Que Fazer', 'Sumário sobre o que se trata este indicador.').'O que:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_oque" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_oque']) ? $requisito['pratica_indicador_requisito_oque'] : '').'</textarea></td></tr>';
if ($exibir['pratica_indicador_porque']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que o indicador será mensurado.').'Por que:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_porque" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_porque']) ? $requisito['pratica_indicador_requisito_porque'] : '').'</textarea></td></tr>';
if ($exibir['pratica_indicador_onde']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Onde Fazer', 'Onde o indicador é mensurado.').'Onde:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_onde" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_onde']) ? $requisito['pratica_indicador_requisito_onde'] : '').'</textarea></td></tr>';
if ($exibir['pratica_indicador_quando']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quando Fazer', 'Quando o indicador é mensurado.').'Quando:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_quando" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_quando']) ? $requisito['pratica_indicador_requisito_quando'] : '').'</textarea></td></tr>';
if ($exibir['pratica_indicador_como']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Como Fazer', 'Como o indicador é mensurado.').'Como:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_como" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_como']) ? $requisito['pratica_indicador_requisito_como'] : '').'</textarea></td></tr>';
if ($exibir['pratica_indicador_quanto']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para mensurar o indicador.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_quanto" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_quanto']) ? $requisito['pratica_indicador_requisito_quanto'] : '').'</textarea></td></tr>';
if ($exibir['pratica_indicador_quem']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão atualizando este indicador.').'Quem:'.dicaF().'</td><td colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_quem" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_quem']) ? $requisito['pratica_indicador_requisito_quem'] : '').'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}


echo '<tr><td style="height:3px;"></td></tr>';
echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'bsc\').style.display) document.getElementById(\'bsc\').style.display=\'\'; else document.getElementById(\'bsc\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>BSC</b></a></td></tr>';
echo '<tr id="bsc" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Referencial Comparativo', 'Qual o referencial comparativo para este indicador.').'Referencial comparativo'.dicaF().':</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_referencial" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_referencial']) ? $requisito['pratica_indicador_requisito_referencial'] : '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Melhorias efetuadas no Indicador', 'Quais as melhorias realizadas no indicador após girar o círculo PDCA.').'Melhorias:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_melhorias" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_melhorias']) ? $requisito['pratica_indicador_requisito_melhorias'] : '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Desde Quando', 'Desde quando o indicador é mensurado.').'Desde quando:'.dicaF().'</td><td width="100%" colspan="2"><input type="hidden" name="pratica_indicador_desde_quando" id="pratica_indicador_desde_quando" value="'.($data_desde ? $data_desde->format("%Y-%m-%d") : '').'" /><input type="text" name="data_desde" style="width:70px;" id="data_desde" onchange="setData(\'env\', \'data_desde\' , \'pratica_indicador_desde_quando\');" value="'.($data_desde ? $data_desde->format('%d/%m/%Y') : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_tendencia">'.dica('Tem Tendência','Este indicador tem tendência.').'Tem tendência:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_tendencia" value="1" '.(isset($requisito['pratica_indicador_requisito_tendencia']) && $requisito['pratica_indicador_requisito_tendencia'] || !$pratica_indicador_id ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_tendencia" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_tendencia']) ? $requisito['pratica_indicador_requisito_justificativa_tendencia']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_favoravel">'.dica('Tendência Favorável','Este indicador tem tendência favorável.').'Tendência favorável:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_favoravel" value="1" '.(isset($requisito['pratica_indicador_requisito_favoravel']) && $requisito['pratica_indicador_requisito_favoravel'] || !$pratica_indicador_id ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_favoravel" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_favoravel']) ? $requisito['pratica_indicador_requisito_justificativa_favoravel']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_superior">'.dica('Superior ao Referêncial','Este indicador é superior ao referêncial comparativo.').'Superior ao referêncial:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_superior" value="1" '.(isset($requisito['pratica_indicador_requisito_superior']) && $requisito['pratica_indicador_requisito_superior'] || !$pratica_indicador_id ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_superior" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_superior']) ? $requisito['pratica_indicador_requisito_justificativa_superior']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_relevante">'.dica('Relevante','O grau do resultado apresentado por este indicador é importante para o alcance de '.$config['genero_objetivo'].'s ou operacional d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Relevante:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_relevante" value="1" '.(isset($requisito['pratica_indicador_requisito_relevante']) && $requisito['pratica_indicador_requisito_relevante'] || !$pratica_indicador_id ? 'checked="checked"' : '').' /></td><td><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_relevante" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_relevante']) ? $requisito['pratica_indicador_requisito_justificativa_relevante']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_atendimento">'.dica('Atende a Requisitos','O nível do resultado demonstra o atendimento aos principais requisitos relacionados com necessidades e expectativas de partes interessadas.').'Atende a requisitos:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_atendimento" value="1" '.(isset($requisito['pratica_indicador_requisito_atendimento']) && $requisito['pratica_indicador_requisito_atendimento'] || !$pratica_indicador_id ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_atendimento" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_atendimento']) ? $requisito['pratica_indicador_requisito_justificativa_atendimento']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_lider">'.dica('Liderança','O nível do resultado deste indicador demonstra '.$config['genero_organizacao'].' '.$config['organizacao'].' ser líder do mercado ou do setor de atuação.').'Liderança:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_lider" value="1" '.(isset($requisito['pratica_indicador_requisito_lider']) && $requisito['pratica_indicador_requisito_lider'] ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_lider" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_lider']) ? $requisito['pratica_indicador_requisito_justificativa_lider']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_excelencia">'.dica('Referência de Excelência','O nível do resultado deste indicador demonstra ser referencial de excelência.').'Referência de excelência:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_excelencia" value="1" '.(isset($requisito['pratica_indicador_requisito_excelencia']) && $requisito['pratica_indicador_requisito_excelencia'] ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_excelencia" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_excelencia']) ? $requisito['pratica_indicador_requisito_justificativa_excelencia']: '').'</textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap"><div id="legenda_pratica_indicador_estrategico">'.dica('Estratégico','Este indicador é estrátégico.').'Estratégico:'.dicaF().'</div></td><td><input type="checkbox"  class="texto" name="pratica_indicador_requisito_estrategico" value="1" '.(isset($requisito['pratica_indicador_requisito_estrategico']) && $requisito['pratica_indicador_requisito_estrategico'] ? 'checked="checked"' : '').' /></td><td width="100%"><textarea data-gpweb-cmp="ckeditor" name="pratica_indicador_requisito_justificativa_estrategico" cols="60" rows="2" class="textarea" style="width:100%">'.(isset($requisito['pratica_indicador_requisito_justificativa_estrategico']) ? $requisito['pratica_indicador_requisito_justificativa_estrategico']: '').'</textarea></td></tr>';


echo '</table></fieldset></td></tr>';
echo '<tr><td style="height:3px;"></td></tr>';

$campos_customizados = new CampoCustomizados('indicadores', $pratica_indicador_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td align="right" nowrap="nowrap">'.dica('Notificar por e-mail', 'Um aviso da '.($pratica_indicador_id ? 'modificação' : 'criação').' '.($config['genero_pratica']=='a' ? 'desta ': 'deste ').$config['pratica'].' poderá ser enviados por e-mail').'Notificar por e-mail:'.dicaF().'</td><td width="100%" colspan="2" valign="top"><table cellspacing=0 cellpadding=0><tr>';

echo '<td>'.dica('Responsável pelo Indicador', 'Ao selecionar esta opção, o responsável pelo indicador será informado '.($pratica_indicador_id ? 'das alterações realizadas no indicador.' : 'da criação do indicador.')).'Responsável'.dicaF().'</td><td><input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" /></td>';
echo '<td>'.dica('Designados  d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Ao selecionar esta opção, os designados deste indicador serão informado '.($pratica_indicador_id ? 'das alterações realizadas no mesmo.' : 'da criação do mesmo.')).'Designados'.dicaF().'</td><td><input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' value=1 /></td>';
echo '</table></td></tr>';
echo '<input type="hidden" name="email_outro" id="email_outro" value="" />';
echo '<tr><td></td><td colspan="2"><table cellspacing=0 cellpadding=0><tr><td valign="top">'.($Aplic->ModuloAtivo('contatos') && $Aplic->checarModulo('contatos', 'acesso') ?  botao('outros contatos', 'Outros Contatos','Abrir uma caixa de diálogo onde poderá selecionar outras pessoas que serão informadas por e-mail.','','popEmailContatos()') : '').'</td>'.($config['email_ativo'] ? ''.($config['email_ativo'] ? '<td>'.dica('Destinatários Extra', 'Preencha neste campo os e-mail, separados por vírgula, dos destinatários extras que serão avisados.').'Extra:'.dicaF().'<input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td>' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'' : '<input type="hidden" name="email_extras" id="email_extras" value="" />').'<td><input type="text" class="texto" name="email_extras" maxlength="255" size="30" /></td></tr></table></td></tr>';

echo '</table></td></tr></table></td></tr>';

echo '<tr id="caixa_pratica_indicador_resultado" style="display:'.($obj->pratica_indicador_resultado ? '' : 'none').';"><td colspan=20><table width="100%">';
echo '<tr><td nowrap="nowrap" align="right">'.dica('Seleção de Pauta de Pontuação', 'Utilize esta opção para filtrar '.$config['genero_marcador'].'s '.$config['marcadores'].' pela pauta de pontuação de sua preferência.').'Pauta:'.dicaF().'</td><td align="left">'.selecionaVetor($modelos, 'pratica_modelo_id', 'onchange="mudar_pauta();" class="texto"', $pratica_modelo_id).'</td><td width="100%">&nbsp;</td></tr>';
echo '<tr><td colspan=20><div id="combo_pauta"></div></td></tr></table></td></tr>';
echo '<tr><td><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td>'.(!$dialogo ? '<td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($pratica_indicador_id > 0 ? 'edição' : 'criação').' do indicador.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td>':'').'</tr></table></td></tr>';

echo '</table></form>';

echo estiloFundoCaixa();


function cia_superior($cia_id){
	global $sql;
	$sql->adTabela('cias');
	$sql->adCampo('cia_superior');
	$sql->adOnde('cia_id = '.$cia_id);
	$sql->adOnde('cia_superior NOT NULL OR (cia_id!=1 AND cia_superior!=1)');
	$cia_indicador = $sql->resultado();
	$saida=$cia_indicador;
	if ($cia_indicador) {
		$superiores=cia_superior($cia_indicador);
		if ($superiores) $saida.=','.$superiores;
		}
	$sql->limpar();
	return $saida;
	}


?>
<script language="javascript">

function marcar_complemento(marcador_id){
 	if (document.getElementById('complemento_'+marcador_id).checked) document.getElementById('caixa3_'+marcador_id).style.backgroundColor='#abfeff';
 	else document.getElementById('caixa3_'+marcador_id).style.backgroundColor='#f8f7f5';
 	xajax_marcar_complemento(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, marcador_id, document.getElementById('complemento_'+marcador_id).checked, document.getElementById('IdxIndicadorAno').value);
	}


function marcar_evidencia(marcador_id){
 	if (document.getElementById('evidencia_'+marcador_id).checked) document.getElementById('caixa4_'+marcador_id).style.backgroundColor='#abffaf';
 	else document.getElementById('caixa4_'+marcador_id).style.backgroundColor='#f8f7f5';
 	xajax_marcar_evidencia(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, marcador_id, document.getElementById('evidencia_'+marcador_id).checked, document.getElementById('IdxIndicadorAno').value);
	}

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 630, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('pratica_indicador_cia').value+'&cias_id_selecionadas='+document.getElementById('indicador_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.indicador_cias.value = organizacao_id_string;
	document.getElementById('indicador_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('indicador_cias').value);
	__buildTooltip();
	}


var social=<?php echo ($social ? 1 : 0)	?>;



function editar_filtro(pratica_indicador_filtro_id){
	document.getElementById('adicionar_filtro').style.display="none";
	document.getElementById('confirmar_filtro').style.display="";
	xajax_editar_filtro(pratica_indicador_filtro_id);
	}

function excluir_filtro(pratica_indicador_filtro_id){
	xajax_excluir_filtro(pratica_indicador_filtro_id, document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value);
	}

function incluir_filtro(){

	var filtro_id=document.getElementById('pratica_indicador_filtro_id').value;
  var indicador=document.getElementById('pratica_indicador_id').value;
  var uuid=document.getElementById('uuid').value;
  var filtro_status=document.getElementById('pratica_indicador_filtro_status').value;
  var prioridade=document.getElementById('pratica_indicador_filtro_prioridade').value;
  var tipo=document.getElementById('pratica_indicador_filtro_tipo').value;
  var setor=document.getElementById('pratica_indicador_filtro_setor').value;
  var segmento=document.getElementById('pratica_indicador_filtro_segmento').value;
  var intervencao=document.getElementById('pratica_indicador_filtro_intervencao').value;
  var tipo_intervencao=document.getElementById('pratica_indicador_filtro_tipo_intervencao').value;
  var social=document.getElementById('pratica_indicador_filtro_social').value;
  var acao=document.getElementById('pratica_indicador_filtro_acao').value;
  var estado=document.getElementById('pratica_indicador_filtro_estado').value;
  var cidade=document.getElementById('pratica_indicador_filtro_cidade').value;
  var comunidade=document.getElementById('pratica_indicador_filtro_comunidade').value;
  var texto=document.getElementById('pratica_indicador_filtro_texto').value;

	xajax_incluir_filtro(filtro_id, indicador, uuid, filtro_status, prioridade, tipo, setor, segmento, intervencao, tipo_intervencao, social, acao, estado, cidade, comunidade, texto);

	}

function mudar_tarefa_tipo(){
	xajax_mudar_tarefa_tipo(document.getElementById('pratica_indicador_filtro_tipo').value, 'pratica_indicador_filtro_tipo', 'combo_tarefa_tipo','class=texto size=1 style="width:284px;" onchange="mudar_tarefa_tipo();"');
	}

function mudar_cidades_tarefa(){
	document.getElementById('pratica_indicador_filtro_cidade').length=0;
	var estado=document.getElementById('pratica_indicador_filtro_estado').value;
	<?php
	echo "if (estado) {xajax_selecionar_cidades_ajax(estado,'pratica_indicador_filtro_cidade','combo_cidade_tarefa', \"class='texto' size=1 style='width:284px;' ".($social ? "onchange='mudar_comunidades_tarefa()'" : '')."\",'');}";
	if ($social) echo "document.getElementById('pratica_indicador_filtro_comunidade').length=0;";
	?>
	}

<?php	if ($social){	?>
function mudar_comunidades_tarefa(){
	var municipio_id=document.getElementById('pratica_indicador_filtro_cidade').value;
	xajax_selecionar_comunidade_ajax(municipio_id, 'pratica_indicador_filtro_comunidade', 'combo_comunidade_tarefa', 'class="texto" size=1 style="width:284px;"', '', '');
	}
<?php } ?>

function mudar_acao_tarefa(){
	xajax_acao_ajax(document.getElementById('pratica_indicador_filtro_social').value, 0);
	}



function mudar_segmento_tarefa(){
	document.getElementById('pratica_indicador_filtro_intervencao').length=0;
	document.getElementById('pratica_indicador_filtro_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('pratica_indicador_filtro_setor').value, 'TarefaSegmento', 'pratica_indicador_filtro_segmento','combo_segmento_tarefa', 'style="width:284px;" class="texto" size=1 onchange="mudar_intervencao_tarefa();"');
	}

function mudar_intervencao_tarefa(){
	document.getElementById('pratica_indicador_filtro_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('pratica_indicador_filtro_segmento').value, 'TarefaIntervencao', 'pratica_indicador_filtro_intervencao','combo_intervencao_tarefa', 'style="width:284px;" class="texto" size=1 onchange="mudar_tipo_intervencao_tarefa();"');
	}

function mudar_tipo_intervencao_tarefa(){
	xajax_mudar_ajax(document.getElementById('pratica_indicador_filtro_intervencao').value, 'TarefaTipoIntervencao', 'pratica_indicador_filtro_tipo_intervencao','combo_tipo_intervencao_tarefa', 'style="width:284px;" class="texto" size=1');
	}



function mudar_segmento(){
	document.getElementById('pratica_indicador_intervencao').length=0;
	document.getElementById('pratica_indicador_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('pratica_indicador_setor').value, 'IndicadorSegmento', 'pratica_indicador_segmento','combo_segmento', 'style="width:284px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('pratica_indicador_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('pratica_indicador_segmento').value, 'IndicadorIntervencao', 'pratica_indicador_intervencao','combo_intervencao', 'style="width:284px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('pratica_indicador_intervencao').value, 'IndicadorTipoIntervencao', 'pratica_indicador_tipo_intervencao','combo_tipo_intervencao', 'style="width:284px;" class="texto" size=1');
	}

function limpar_botoes(){
	<?php if ($Aplic->profissional){ ?>

		env.pratica_indicador_externo.checked=false;
		document.getElementById('botao_externo').style.display='none';

		env.pratica_indicador_campo_projeto.checked=false;
		document.getElementById('botao_pratica_indicador_campo_projeto').style.display='none';

		env.pratica_indicador_campo_tarefa.checked=false;
		document.getElementById('botao_pratica_indicador_campo_tarefa').style.display='none';

		env.pratica_indicador_campo_acao.checked=false;
		document.getElementById('botao_pratica_indicador_campo_acao').style.display='none';

		env.pratica_indicador_formula_simples.checked=false;
		document.getElementById('botao_formula_simples').style.display='none';
		document.getElementById('texto_formula_simples').style.display='none';

		document.getElementById('valor_checklist').style.display='none';
	<?php } ?>

	env.nome_checklist.value='';
	env.pratica_indicador_checklist.value=null;
	env.checklist.checked=false;
	document.getElementById('botao_checklist').style.display='none';

	env.pratica_indicador_composicao.checked=false;
	document.getElementById('botao_composicao').style.display='none';

	env.pratica_indicador_formula.checked=false;
	document.getElementById('botao_formula').style.display='none';
	document.getElementById('texto_formula').style.display='none';
	}


function editar_meta(pratica_indicador_meta_id){
	xajax_editar_meta(pratica_indicador_meta_id);
	document.getElementById('adicionar_meta').style.display="none";
	document.getElementById('confirmar_meta').style.display="";
	}

function limpar_meta(){
	document.getElementById('pratica_indicador_meta_id').value=null;
	document.getElementById('pratica_indicador_meta_valor_referencial').value='';
	document.getElementById('pratica_indicador_meta_valor_meta').value='';
	document.getElementById('pratica_indicador_meta_valor_meta_boa').value='';
	document.getElementById('pratica_indicador_meta_valor_meta_regular').value='';
	document.getElementById('pratica_indicador_meta_valor_meta_ruim').value='';
	}

function excluir_meta(pratica_indicador_meta_id){
	xajax_excluir_meta(pratica_indicador_meta_id,  document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value);
	}

function incluir_meta(){
	xajax_incluir_meta(
		document.getElementById('pratica_indicador_meta_id').value,
	  document.getElementById('pratica_indicador_id').value,
	  document.getElementById('uuid').value,
	  document.getElementById('pratica_indicador_meta_data').value,
	  document.getElementById('pratica_indicador_meta_valor_referencial').value,
	  document.getElementById('pratica_indicador_meta_valor_meta').value,
	  document.getElementById('pratica_indicador_meta_proporcao').checked,
	  document.getElementById('pratica_indicador_meta_valor_meta_boa').value,
	  document.getElementById('pratica_indicador_meta_valor_meta_regular').value,
	  document.getElementById('pratica_indicador_meta_valor_meta_ruim').value,
	  document.getElementById('pratica_indicador_meta_data_meta').value
		);
	limpar_meta();
	document.getElementById('adicionar_meta').style.display="";
	document.getElementById('confirmar_meta').style.display="none";
	}

function popEmailContatos() {
	atualizarEmailContatos();
	var email_outro = document.getElementById('email_outro');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Contatos', 630, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, window.setEmailContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setEmailContatos&contatos_id_selecionados='+ email_outro.value, 'contatos','height=500,width=500,resizable,scrollbars=yes');
	}

function setEmailContatos(contato_id_string) {
	if (!contato_id_string) contato_id_string = '';
	document.getElementById('email_outro').value = contato_id_string;
	}

function atualizarEmailContatos() {
	var email_outro = document.getElementById('email_outro');
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

function popImportar(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Importar', 630, 500, 'm=praticas&a=indicador_importar_pro&dialogo=1&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value+'&ano='+document.getElementById('IdxIndicadorAno').value, null, window);
	else window.open('./index.php?m=praticas&a=indicador_importar_pro&dialogo=1&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value+'&ano='+document.getElementById('IdxIndicadorAno').value, 'Importar','left=0,top=0,height=250,width=250,scrollbars=no, resizable=no');
	}

function entradaNumerica(event, campo, virgula, menos) {
  var unicode = event.charCode;
  var unicode1 = event.keyCode;
	if(virgula && campo.value.indexOf(",")!=campo.value.lastIndexOf(",")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf(",")) + campo.value.substr(campo.value.lastIndexOf(",")+1);
			}
	if(menos && campo.value.indexOf("-")!=campo.value.lastIndexOf("-")){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
	if(menos && campo.value.lastIndexOf("-") > 0){
			campo.value=campo.value.substr(0,campo.value.lastIndexOf("-")) + campo.value.substr(campo.value.lastIndexOf("-")+1);
			}
  if (navigator.userAgent.indexOf("Firefox") != -1 || navigator.userAgent.indexOf("Safari") != -1) {
    if (unicode1 != 8) {
       if ((unicode >= 48 && unicode <= 57) || unicode1 == 37 || unicode1 == 39 || unicode1 == 35 || unicode1 == 36 || unicode1 == 9 || unicode1 == 46) return true;
       else if((virgula && unicode == 44) || (menos && unicode == 45))	return true;
       return false;
      }
  	}
  if (navigator.userAgent.indexOf("MSIE") != -1 || navigator.userAgent.indexOf("Opera") == -1) {
    if (unicode1 != 8) {
      if (unicode1 >= 48 && unicode1 <= 57) return true;
      else {
      	if( (virgula && unicode == 44) || (menos && unicode == 45))	return true;
      	return false;
      	}
    	}
  	}
	}


function marcar_marcador(pratica_marcador_id){
	if (document.getElementById('checagem_'+pratica_marcador_id).checked) document.getElementById('caixa_'+pratica_marcador_id).style.backgroundColor='#FFFF00';
	else document.getElementById('caixa_'+pratica_marcador_id).style.backgroundColor='#f2f0ec';
	xajax_marcar_marcador(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, pratica_marcador_id, document.getElementById('checagem_'+pratica_marcador_id).checked, document.getElementById('IdxIndicadorAno').value);
	}


function mudar_ano(ano){
	document.env.a.value='indicador_editar';
	document.env.fazerSQL.value='';
	document.env.submit();
	}



function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Indicador', 630, 500, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&valor='+document.getElementById('pratica_indicador_superior').value+'&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&valor='+document.getElementById('pratica_indicador_superior').value+'&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Indicadores','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	document.env.pratica_indicador_superior.value = chave;
	document.env.indicador_nome.value = valor;
	}









function mudar_indicador_tipo(){
	xajax_mudar_indicador_tipo_ajax(document.getElementById('pratica_indicador_tipo').value, 'pratica_indicador_tipo', 'combo_indicador_tipo','class=texto size=1 style="width:300px;" onchange="mudar_indicador_tipo();"');
	}

var pauta_atual=document.getElementById('pratica_modelo_id').value;

function mudar_pauta(){
	xajax_mudar_pauta(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, document.getElementById('pratica_modelo_id').value, document.getElementById('IdxIndicadorAno').value);
	__buildTooltip();
	}


function popComposicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição', 800, 600, 'm=praticas&a=indicador_composicao&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value, null, window);
	else window.open('./index.php?m=praticas&a=indicador_composicao&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value, 'Composição','height=600,width=900,resizable,scrollbars=yes, left=0, top=0');
	}


function popFormula() {
	calculo=document.getElementById('pratica_indicador_calculo').value;
	calculo_modificado=calculo.replace(/\+/g, '@');
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórmula', 800, 600, 'm=praticas&a=indicador_formula&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value+'&pratica_indicador_calculo='+calculo_modificado, window.SetFormula, window);
	else window.open('./index.php?m=praticas&a=indicador_formula&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value+'&pratica_indicador_calculo='+calculo_modificado, 'Formula','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
	}

function SetFormula(calculo){
	document.getElementById('pratica_indicador_calculo').value=calculo;
	document.getElementById('texto_formula').innerHTML=calculo;
	document.getElementById('texto_formula_simples').innerHTML=calculo;
	}

function popFormulaSimples() {
	calculo=document.getElementById('pratica_indicador_calculo').value;
	calculo_modificado=calculo.replace("+", "@")
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórmula Simples', 800, 500, 'm=praticas&a=indicador_formula_pro&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value+'&pratica_indicador_calculo='+calculo_modificado, window.SetFormulaSimples, window);
	else window.open('./index.php?m=praticas&a=indicador_formula_pro&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value+'&pratica_indicador_calculo='+calculo_modificado, 'Fórmula','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
	}

function SetFormulaSimples(calculo){
	document.getElementById('pratica_indicador_calculo').value=calculo;
	document.getElementById('texto_formula').innerHTML=calculo;
	document.getElementById('texto_formula_simples').innerHTML=calculo;
	}

function popExterno() {
	parent.gpwebApp.popUp('Indicador Externo', 750, 400, 'm=praticas&a=indicador_externo_pro&dialogo=1&cia_id='+document.getElementById('pratica_indicador_cia').value+'&pratica_indicador_id='+document.getElementById('pratica_indicador_id').value+'&uuid='+document.getElementById('uuid').value, window.SetExterno, window);
	}

function SetExterno(tipo, conexao, string_sql){

	}


function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 630, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pratica_indicador_cia').value+'&usuario_id='+document.getElementById('pratica_indicador_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('pratica_indicador_cia').value+'&usuario_id='+document.getElementById('pratica_indicador_responsavel').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('pratica_indicador_responsavel').value=usuario_id;
	document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}

function mudar_om(){
	var pratica_indicador_cia=document.getElementById('pratica_indicador_cia').value;
	xajax_selecionar_om_ajax(pratica_indicador_cia,'pratica_indicador_cia','combo_cia', 'class="texto" size=1 style="width:284px;" onchange="javascript:mudar_om();"');
	}

var cal3 = Calendario.setup({
	trigger    : "f_btn3",
  inputField : "pratica_indicador_meta_data",
	date :  <?php echo date('Y').'-01-01'?>,
	selection: <?php echo date('Y').'-01-01'?>,
  onSelect: function(cal3) {
    var date = cal3.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pratica_indicador_meta_data").value = Calendario.printDate(date, "%Y-%m-%d");
      CompararDatasMeta();
      }
  	cal3.hide();
  	}
  });

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "pratica_indicador_meta_data_meta",
	date :  <?php echo date('Y').'-12-31'?>,
	selection: <?php echo date('Y').'-12-31'?>,
  onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pratica_indicador_meta_data_meta").value = Calendario.printDate(date, "%Y-%m-%d");
      CompararDatasMeta();
      }
  	cal1.hide();
  	}
  });

function CompararDatasMeta(){
	var str1 = document.getElementById('data_inicio').value;
  var str2 = document.getElementById('data').value;
  var dt1  = parseInt(str1.substring(0,2),10);
  var mon1 = parseInt(str1.substring(3,5),10);
  var yr1  = parseInt(str1.substring(6,10),10);
  var dt2  = parseInt(str2.substring(0,2),10);
  var mon2 = parseInt(str2.substring(3,5),10);
  var yr2  = parseInt(str2.substring(6,10),10);
  var date1 = new Date(yr1, mon1, dt1);
  var date2 = new Date(yr2, mon2, dt2);

  if(date2 < date1){
    document.getElementById('data').value=document.getElementById('data_inicio').value;
    document.getElementById('pratica_indicador_meta_data_meta').value=document.getElementById('pratica_indicador_meta_data').value;
  	}
 }

var cal2 = Calendario.setup({
	trigger    : "f_btn2",
  inputField : "pratica_indicador_desde_quando",
	date :  <?php echo $data_desde->format("%Y-%m-%d")?>,
	selection: <?php echo $data_desde->format("%Y-%m-%d")?>,
  onSelect: function(cal2) {
  var date = cal2.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data_desde").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("pratica_indicador_desde_quando").value = Calendario.printDate(date, "%Y-%m-%d");
    }
	cal2.hide();
	}
});

function setData( frm_nome, f_data , f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real);
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
    	}
    else {
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
      CompararDatasMeta();
			}
		}
	else campo_data_real.value = '';
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir este indicador?")) {
		var f = document.env;
		f.del.value=1;
		f.a.value='indicador_fazer_sql';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.pratica_indicador_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pratica_indicador_cor.value;
	}

function enviarDados(){
	var f = document.env;

	xajax_qnt_metas(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value);


<?php if (!$Aplic->profissional) { ?>

	if (document.getElementById('projeto').style.display=='' && f.pratica_indicador_projeto.value<1)	{
		alert('Escolha <?php echo ($config["genero_projeto"]=="a" ? "uma ": "um ").$config["projeto"] ?>');
		return;
		}
	if (document.getElementById('pratica').style.display=='' && f.pratica_indicador_pratica.value<1)	{
		alert('Escolha <?php echo ($config["genero_pratica"]=="a" ? "uma ": "um ").$config["pratica"] ?>');
		return;
		}
	if (document.getElementById('acao').style.display=='' && f.pratica_indicador_acao.value<1)	{
		alert("Escolha <?php echo ($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao']?>");
		return;
		}

	if (document.getElementById('perspectiva').style.display=='' && f.pratica_indicador_perspectiva.value<1)	{
		alert("Escolha <?php echo ($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva']?>");
		return;
		}

	if (document.getElementById('tema').style.display=='' && f.pratica_indicador_tema.value<1)	{
		alert("Escolha <?php echo ($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema']?>");
		return;
		}

	if (document.getElementById('objetivo').style.display=='' && f.pratica_indicador_objetivo_estrategico.value<1)	{
		alert("Escolha <?php echo ($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo']?>");
		return;
		}

	if (document.getElementById('fator').style.display=='' && f.pratica_indicador_fator.value<1)	{
		alert("Escolha <?php echo ($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator']?>");
		return;
		}

	if (document.getElementById('estrategia').style.display=='' && f.pratica_indicador_estrategia.value<1)	{
		alert("Escolha <?php echo ($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa']?>");
		return;
		}


	if (document.getElementById('meta').style.display=='' && f.pratica_indicador_meta.value<1)	{
		alert('Escolha uma meta');
		return;
		}

<?php } ?>

	if (f.pratica_indicador_nome.value.length < 3) {
		alert('Escreva um nome para o indicador válido');
		f.pratica_indicador_nome.focus();
		}
	else if (f.pratica_indicador_cia.options[f.pratica_indicador_cia.selectedIndex].value < 1) {
		alert('Necessário escolher <?php echo $config["genero_organizacao"]." ".$config["organizacao"]?> responsável');
		f.pratica_indicador_cia.focus();
		}
	else if(document.getElementById('qnt_metas').value < 1) {
		alert('Necessário cadastrar ao menos uma meta');
		f.pratica_indicador_valor_meta.focus();
		}
		
		
	else if((f.pratica_indicador_formula.checked || f.pratica_indicador_formula_simples.checked) && !document.getElementById('pratica_indicador_calculo').value) {
		alert('Necessário cadastrar uma fórmula');
		}	
	else {
		f.salvar.value=1;
		f.submit();
		}
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}

function readOnlyCheckBox() {
  return false;
	}

var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 630, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_indicador_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('pratica_indicador_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.pratica_indicador_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 630, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pratica_indicador_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('pratica_indicador_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.pratica_indicador_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 630, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pratica_indicador_dept').value+'&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('pratica_indicador_dept').value+'&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('pratica_indicador_cia').value=cia_id;
	document.getElementById('pratica_indicador_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
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
	document.getElementById('acao').style.display='none';
	document.getElementById('objetivo').style.display='none';
	document.getElementById('estrategia').style.display='none';
	document.getElementById('fator').style.display='none';
	document.getElementById('perspectiva').style.display='none';
	document.getElementById('canvas').style.display='none';
	document.getElementById('risco').style.display='none';
	document.getElementById('risco_resposta').style.display='none';
	document.getElementById('meta').style.display='none';
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
	if($agrupamento_ativo) echo 'document.getElementById(\'agrupamento\').style.display=\'none\';';
	if($patrocinador_ativo) echo 'document.getElementById(\'patrocinador\').style.display=\'none\';';
	if($swot_ativo) echo 'document.getElementById(\'swot\').style.display=\'none\';';
	if($ata_ativo) echo 'document.getElementById(\'ata\').style.display=\'none\';';
	if($operativo_ativo) echo 'document.getElementById(\'operativo\').style.display=\'none\';';
	if($tr_ativo) echo 'document.getElementById(\'tr\').style.display=\'none\';';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.getElementById(\'me\').style.display=\'none\';';
	?>
	}


<?php  if ($Aplic->profissional) { ?>

	function popAgrupamento() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_painel.value = chave;
		document.env.painel_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_painel_odometro.value = chave;
		document.env.painel_odometro_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_painel_composicao.value = chave;
		document.env.painel_composicao_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_tr.value = chave;
		document.env.tr_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.pratica_indicador_me.value = chave;
		document.env.me_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.pratica_indicador_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.pratica_indicador_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.pratica_indicador_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_objetivo_estrategico.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_meta.value = chave;
	document.env.meta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_acao.value = chave;
	document.env.acao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 630, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_evento.value = chave;
	document.env.evento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('pratica_indicador_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}


function popChecklist() {
	
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');

	
	
	
	//if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 630, 500, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setChecklist&tabela=checklist&valor='+document.getElementById('pratica_indicador_checklist').value+'&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setChecklist, window);
	//else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setChecklist&tabela=checklist&valor='+document.getElementById('pratica_indicador_checklist').value+'&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	document.getElementById('pratica_indicador_checklist').value=(chave > 0 ? chave : null);
	document.getElementById('nome_checklist').value=valor;
	}



function popChecklist2() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setChecklist2, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist2&tabela=checklist&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist2(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_checklist2.value = chave;
	document.env.checklist_nome2.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('pratica_indicador_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('pratica_indicador_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.pratica_indicador_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.pratica_indicador_projeto.value = null;
		}
	document.env.pratica_indicador_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.pratica_indicador_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.pratica_indicador_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.pratica_indicador_objetivo_estrategico.value = null;
	document.env.objetivo_nome.value = '';
	document.env.pratica_indicador_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.pratica_indicador_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.pratica_indicador_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.pratica_indicador_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.pratica_indicador_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.pratica_indicador_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.pratica_indicador_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.pratica_indicador_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.pratica_indicador_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.pratica_indicador_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.pratica_indicador_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.pratica_indicador_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.pratica_indicador_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.pratica_indicador_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.pratica_indicador_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.pratica_indicador_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.pratica_indicador_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.pratica_indicador_link.value = null;
	document.env.link_nome.value = '';
	document.env.pratica_indicador_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.pratica_indicador_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.pratica_indicador_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.pratica_indicador_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.pratica_indicador_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.pratica_indicador_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.pratica_indicador_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.pratica_indicador_checklist2.value = null;
	document.env.checklist_nome2.value = '';
	document.env.pratica_indicador_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.pratica_indicador_template.value = null;
	document.env.template_nome.value = '';
	document.env.pratica_indicador_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.pratica_indicador_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.pratica_indicador_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';

	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.pratica_indicador_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.pratica_indicador_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.pratica_indicador_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.pratica_indicador_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.pratica_indicador_patrocinador.value = null;';
	if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.pratica_indicador_tr.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.pratica_indicador_me.value = null;';
	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('pratica_indicador_id').value,
	document.getElementById('uuid').value,
	f.pratica_indicador_projeto.value,
	f.pratica_indicador_tarefa.value,
	f.pratica_indicador_perspectiva.value,
	f.pratica_indicador_tema.value,
	f.pratica_indicador_objetivo_estrategico.value,
	f.pratica_indicador_fator.value,
	f.pratica_indicador_estrategia.value,
	f.pratica_indicador_meta.value,
	f.pratica_indicador_pratica.value,
	f.pratica_indicador_acao.value,
	f.pratica_indicador_canvas.value,
	f.pratica_indicador_risco.value,
	f.pratica_indicador_risco_resposta.value,
	f.pratica_indicador_calendario.value,
	f.pratica_indicador_monitoramento.value,
	f.pratica_indicador_ata.value,
	f.pratica_indicador_swot.value,
	f.pratica_indicador_operativo.value,
	f.pratica_indicador_instrumento.value,
	f.pratica_indicador_recurso.value,
	f.pratica_indicador_problema.value,
	f.pratica_indicador_demanda.value,
	f.pratica_indicador_programa.value,
	f.pratica_indicador_licao.value,
	f.pratica_indicador_evento.value,
	f.pratica_indicador_link.value,
	f.pratica_indicador_avaliacao.value,
	f.pratica_indicador_tgn.value,
	f.pratica_indicador_brainstorm.value,
	f.pratica_indicador_gut.value,
	f.pratica_indicador_causa_efeito.value,
	f.pratica_indicador_arquivo.value,
	f.pratica_indicador_forum.value,
	f.pratica_indicador_checklist2.value,
	f.pratica_indicador_agenda.value,
	f.pratica_indicador_agrupamento.value,
	f.pratica_indicador_patrocinador.value,
	f.pratica_indicador_template.value,
	f.pratica_indicador_painel.value,
	f.pratica_indicador_painel_odometro.value,
	f.pratica_indicador_painel_composicao.value,
	f.pratica_indicador_tr.value,
	f.pratica_indicador_me.value
	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(pratica_indicador_gestao_id){
	xajax_excluir_gestao(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value, pratica_indicador_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, pratica_indicador_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, pratica_indicador_gestao_id, direcao, document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$pratica_indicador_id && (
	$pratica_indicador_projeto ||
	$pratica_indicador_tarefa ||
	$pratica_indicador_perspectiva ||
	$pratica_indicador_tema ||
	$pratica_indicador_objetivo_estrategico ||
	$pratica_indicador_fator ||
	$pratica_indicador_estrategia ||
	$pratica_indicador_meta ||
	$pratica_indicador_pratica ||
	$pratica_indicador_acao ||
	$pratica_indicador_canvas ||
	$pratica_indicador_risco ||
	$pratica_indicador_risco_resposta ||
	$pratica_indicador_calendario ||
	$pratica_indicador_monitoramento ||
	$pratica_indicador_ata ||
	$pratica_indicador_swot ||
	$pratica_indicador_operativo ||
	$pratica_indicador_instrumento ||
	$pratica_indicador_recurso ||
	$pratica_indicador_problema ||
	$pratica_indicador_demanda ||
	$pratica_indicador_programa ||
	$pratica_indicador_licao ||
	$pratica_indicador_evento ||
	$pratica_indicador_link ||
	$pratica_indicador_avaliacao ||
	$pratica_indicador_tgn ||
	$pratica_indicador_brainstorm ||
	$pratica_indicador_gut ||
	$pratica_indicador_causa_efeito ||
	$pratica_indicador_arquivo ||
	$pratica_indicador_forum ||
	$pratica_indicador_checklist ||
	$pratica_indicador_agenda ||
	$pratica_indicador_agrupamento ||
	$pratica_indicador_patrocinador ||
	$pratica_indicador_template ||
	$pratica_indicador_painel ||
	$pratica_indicador_painel_odometro ||
	$pratica_indicador_painel_composicao ||
	$pratica_indicador_tr	 ||
	$pratica_indicador_me
	)) echo 'incluir_relacionado();';
	?>


mudar_pauta();
xajax_filtros(document.getElementById('pratica_indicador_id').value, document.getElementById('uuid').value);
</script>