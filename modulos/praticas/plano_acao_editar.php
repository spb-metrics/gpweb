<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este plano_acao � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este plano de a��o diretamente.');

global $Aplic, $cal_sdf;
require_once BASE_DIR.'/modulos/praticas/plano_acao.class.php';

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();

$plano_acao_id = getParam($_REQUEST, 'plano_acao_id', null);
$salvar = getParam($_REQUEST, 'salvar', 0);
$plano_acao_superior = getParam($_REQUEST, 'plano_acao_superior', null);



$inicio = 0;
$fim = 24;
$inc = 1;
$horas = array();
for ($atual = $inicio; $atual < $fim + 1; $atual++) {
	if ($atual < 10) $chave_atual = "0".$atual;
	else $chave_atual = $atual;
	if (stristr($Aplic->getPref('formatohora'), '%p')) $horas[$chave_atual] = ($atual > 12 ? $atual - 12 : $atual);
	else 	$horas[$chave_atual] = $atual;
	}
$minutos = array();
$minutos['00'] = '00';
for ($atual = 0 + $inc; $atual < 60; $atual += $inc) $minutos[($atual < 10 ? '0' : '').$atual] = ($atual < 10 ? '0' : '').$atual;



$sql = new BDConsulta;

$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');

$obj = new CPlanoAcao();
$obj->load($plano_acao_id);


if($plano_acao_id && !($podeEditar && permiteEditarPlanoAcao($obj->plano_acao_acesso ,$plano_acao_id))) $Aplic->redirecionar('m=publico&a=acesso_negado');



if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;

$plano_acao_acesso = getSisValor('NivelAcesso','','','sisvalor_id');

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

$plano_acao_projeto = getParam($_REQUEST, 'plano_acao_projeto', null);
$plano_acao_tarefa = getParam($_REQUEST, 'plano_acao_tarefa', null);
$plano_acao_perspectiva = getParam($_REQUEST, 'plano_acao_perspectiva', null);
$plano_acao_tema = getParam($_REQUEST, 'plano_acao_tema', null);
$plano_acao_objetivo = getParam($_REQUEST, 'plano_acao_objetivo', null);
$plano_acao_fator = getParam($_REQUEST, 'plano_acao_fator', null);
$plano_acao_estrategia = getParam($_REQUEST, 'plano_acao_estrategia', null);
$plano_acao_meta = getParam($_REQUEST, 'plano_acao_meta', null);
$plano_acao_pratica = getParam($_REQUEST, 'plano_acao_pratica', null);
$plano_acao_canvas = getParam($_REQUEST, 'plano_acao_canvas', null);
$plano_acao_risco = getParam($_REQUEST, 'plano_acao_risco', null);
$plano_acao_risco_resposta = getParam($_REQUEST, 'plano_acao_risco_resposta', null);
$plano_acao_indicador = getParam($_REQUEST, 'plano_acao_indicador', null);
$plano_acao_calendario = getParam($_REQUEST, 'plano_acao_calendario', null);
$plano_acao_monitoramento = getParam($_REQUEST, 'plano_acao_monitoramento', null);
$plano_acao_ata = getParam($_REQUEST, 'plano_acao_ata', null);
$plano_acao_swot = getParam($_REQUEST, 'plano_acao_swot', null);
$plano_acao_operativo = getParam($_REQUEST, 'plano_acao_operativo', null);
$plano_acao_instrumento = getParam($_REQUEST, 'plano_acao_instrumento', null);
$plano_acao_recurso = getParam($_REQUEST, 'plano_acao_recurso', null);
$plano_acao_problema = getParam($_REQUEST, 'plano_acao_problema', null);
$plano_acao_demanda = getParam($_REQUEST, 'plano_acao_demanda', null);
$plano_acao_programa = getParam($_REQUEST, 'plano_acao_programa', null);
$plano_acao_licao = getParam($_REQUEST, 'plano_acao_licao', null);
$plano_acao_evento = getParam($_REQUEST, 'plano_acao_evento', null);
$plano_acao_link = getParam($_REQUEST, 'plano_acao_link', null);
$plano_acao_avaliacao = getParam($_REQUEST, 'plano_acao_avaliacao', null);
$plano_acao_tgn = getParam($_REQUEST, 'plano_acao_tgn', null);
$plano_acao_brainstorm = getParam($_REQUEST, 'plano_acao_brainstorm', null);
$plano_acao_gut = getParam($_REQUEST, 'plano_acao_gut', null);
$plano_acao_causa_efeito = getParam($_REQUEST, 'plano_acao_causa_efeito', null);
$plano_acao_arquivo = getParam($_REQUEST, 'plano_acao_arquivo', null);
$plano_acao_forum = getParam($_REQUEST, 'plano_acao_forum', null);
$plano_acao_checklist = getParam($_REQUEST, 'plano_acao_checklist', null);
$plano_acao_agenda = getParam($_REQUEST, 'plano_acao_agenda', null);
$plano_acao_agrupamento = getParam($_REQUEST, 'plano_acao_agrupamento', null);
$plano_acao_patrocinador = getParam($_REQUEST, 'plano_acao_patrocinador', null);
$plano_acao_template = getParam($_REQUEST, 'plano_acao_template', null);
$plano_acao_painel = getParam($_REQUEST, 'plano_acao_painel', null);
$plano_acao_painel_odometro = getParam($_REQUEST, 'plano_acao_painel_odometro', null);
$plano_acao_painel_composicao = getParam($_REQUEST, 'plano_acao_painel_composicao', null);
$plano_acao_tr = getParam($_REQUEST, 'plano_acao_tr', null);
$plano_acao_me = getParam($_REQUEST, 'plano_acao_me', null);

if (
	$plano_acao_projeto ||
	$plano_acao_tarefa ||
	$plano_acao_perspectiva ||
	$plano_acao_tema ||
	$plano_acao_objetivo ||
	$plano_acao_fator ||
	$plano_acao_estrategia ||
	$plano_acao_meta ||
	$plano_acao_pratica ||
	$plano_acao_canvas ||
	$plano_acao_risco ||
	$plano_acao_risco_resposta ||
	$plano_acao_indicador ||
	$plano_acao_calendario ||
	$plano_acao_monitoramento ||
	$plano_acao_ata ||
	$plano_acao_swot ||
	$plano_acao_operativo ||
	$plano_acao_instrumento ||
	$plano_acao_recurso ||
	$plano_acao_problema ||
	$plano_acao_demanda ||
	$plano_acao_programa ||
	$plano_acao_licao ||
	$plano_acao_evento ||
	$plano_acao_link ||
	$plano_acao_avaliacao ||
	$plano_acao_tgn ||
	$plano_acao_brainstorm ||
	$plano_acao_gut ||
	$plano_acao_causa_efeito ||
	$plano_acao_arquivo ||
	$plano_acao_forum ||
	$plano_acao_checklist ||
	$plano_acao_agenda ||
	$plano_acao_agrupamento ||
	$plano_acao_patrocinador ||
	$plano_acao_template||
	$plano_acao_painel ||
	$plano_acao_painel_odometro ||
	$plano_acao_painel_composicao	||
	$plano_acao_tr
	){
	$sql->adTabela('cias');
	if ($plano_acao_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($plano_acao_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($plano_acao_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($plano_acao_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($plano_acao_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($plano_acao_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($plano_acao_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($plano_acao_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($plano_acao_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($plano_acao_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($plano_acao_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($plano_acao_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($plano_acao_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($plano_acao_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($plano_acao_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($plano_acao_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($plano_acao_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($plano_acao_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($plano_acao_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($plano_acao_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($plano_acao_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($plano_acao_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($plano_acao_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($plano_acao_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($plano_acao_evento) $sql->esqUnir('eventos','eventos','evento_cia=cias.cia_id');
	elseif ($plano_acao_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($plano_acao_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($plano_acao_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($plano_acao_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($plano_acao_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($plano_acao_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($plano_acao_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($plano_acao_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($plano_acao_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($plano_acao_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($plano_acao_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($plano_acao_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($plano_acao_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($plano_acao_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($plano_acao_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($plano_acao_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($plano_acao_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');

	if ($plano_acao_tarefa) $sql->adOnde('tarefa_id = '.(int)$plano_acao_tarefa);
	elseif ($plano_acao_projeto) $sql->adOnde('projeto_id = '.(int)$plano_acao_projeto);
	elseif ($plano_acao_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$plano_acao_perspectiva);
	elseif ($plano_acao_tema) $sql->adOnde('tema_id = '.(int)$plano_acao_tema);
	elseif ($plano_acao_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$plano_acao_objetivo);
	elseif ($plano_acao_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$plano_acao_fator);
	elseif ($plano_acao_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$plano_acao_estrategia);
	elseif ($plano_acao_meta) $sql->adOnde('pg_meta_id = '.(int)$plano_acao_meta);
	elseif ($plano_acao_pratica) $sql->adOnde('pratica_id = '.(int)$plano_acao_pratica);
	elseif ($plano_acao_canvas) $sql->adOnde('canvas_id = '.(int)$plano_acao_canvas);
	elseif ($plano_acao_risco) $sql->adOnde('risco_id = '.(int)$plano_acao_risco);
	elseif ($plano_acao_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$plano_acao_risco_resposta);
	elseif ($plano_acao_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$plano_acao_indicador);
	elseif ($plano_acao_calendario) $sql->adOnde('calendario_id = '.(int)$plano_acao_calendario);
	elseif ($plano_acao_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$plano_acao_monitoramento);
	elseif ($plano_acao_ata) $sql->adOnde('ata_id = '.(int)$plano_acao_ata);
	elseif ($plano_acao_swot) $sql->adOnde('swot_id = '.(int)$plano_acao_swot);
	elseif ($plano_acao_operativo) $sql->adOnde('operativo_id = '.(int)$plano_acao_operativo);
	elseif ($plano_acao_instrumento) $sql->adOnde('instrumento_id = '.(int)$plano_acao_instrumento);
	elseif ($plano_acao_recurso) $sql->adOnde('recurso_id = '.(int)$plano_acao_recurso);
	elseif ($plano_acao_problema) $sql->adOnde('problema_id = '.(int)$plano_acao_problema);
	elseif ($plano_acao_demanda) $sql->adOnde('demanda_id = '.(int)$plano_acao_demanda);
	elseif ($plano_acao_programa) $sql->adOnde('programa_id = '.(int)$plano_acao_programa);
	elseif ($plano_acao_licao) $sql->adOnde('licao_id = '.(int)$plano_acao_licao);
	elseif ($plano_acao_evento) $sql->adOnde('evento_id = '.(int)$plano_acao_evento);
	elseif ($plano_acao_link) $sql->adOnde('link_id = '.(int)$plano_acao_link);
	elseif ($plano_acao_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$plano_acao_avaliacao);
	elseif ($plano_acao_tgn) $sql->adOnde('tgn_id = '.(int)$plano_acao_tgn);
	elseif ($plano_acao_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$plano_acao_brainstorm);
	elseif ($plano_acao_gut) $sql->adOnde('gut_id = '.(int)$plano_acao_gut);
	elseif ($plano_acao_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$plano_acao_causa_efeito);
	elseif ($plano_acao_arquivo) $sql->adOnde('arquivo_id = '.(int)$plano_acao_arquivo);
	elseif ($plano_acao_forum) $sql->adOnde('forum_id = '.(int)$plano_acao_forum);
	elseif ($plano_acao_checklist) $sql->adOnde('checklist_id = '.(int)$plano_acao_checklist);
	elseif ($plano_acao_agenda) $sql->adOnde('agenda_id = '.(int)$plano_acao_agenda);
	elseif ($plano_acao_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$plano_acao_agrupamento);
	elseif ($plano_acao_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$plano_acao_patrocinador);
	elseif ($plano_acao_template) $sql->adOnde('template_id = '.(int)$plano_acao_template);
	elseif ($plano_acao_painel) $sql->adOnde('painel_id = '.(int)$plano_acao_painel);
	elseif ($plano_acao_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$plano_acao_painel_odometro);
	elseif ($plano_acao_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$plano_acao_painel_composicao);
	elseif ($plano_acao_tr) $sql->adOnde('tr_id = '.(int)$plano_acao_tr);
	elseif ($plano_acao_me) $sql->adOnde('me_id = '.(int)$plano_acao_me);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}


if ((!$podeEditar && $plano_acao_id) || (!$podeAdicionar && !$plano_acao_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$df = '%d/%m/%Y';
$botoesTitulo = new CBlocoTitulo(($plano_acao_id ? 'Editar '.ucfirst($config['acao']) : 'Criar '.ucfirst($config['acao'])), 'plano_acao.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();

$usuarios_selecionados=array();
$depts_selecionados=array();
$indicadores=array();
$contatos_selecionados=array();
$cias_selecionadas=array();
if ($plano_acao_id) {
	$sql->adTabela('plano_acao_usuarios', 'plano_acao_usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('plano_acao_id = '.(int)$plano_acao_id);
	$usuarios_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('plano_acao_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('plano_acao_id ='.(int)$plano_acao_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	$sql->adTabela('plano_acao_cia');
	$sql->adCampo('plano_acao_cia_cia');
	$sql->adOnde('plano_acao_cia_plano_acao = '.(int)$plano_acao_id);
	$cias_selecionadas = $sql->carregarColuna();
	$sql->limpar();


	if ($Aplic->profissional){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
		$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
		$sql->adOnde('pratica_indicador_gestao_acao = '.(int)$plano_acao_id);
		$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
		$sql->limpar();
		}
	else{
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
		$sql->adOnde('pratica_indicador_acao = '.(int)$plano_acao_id);
		$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
		$sql->limpar();
		}

	$sql->adTabela('plano_acao_contatos');
	$sql->adCampo('contato_id');
	$sql->adOnde('plano_acao_id = '.(int)$plano_acao_id);
	$contatos_selecionados = $sql->carregarColuna();
	$sql->limpar();
	}





echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="plano_acao_fazer_sql" />';
echo '<input type="hidden" name="plano_acao_id" id="plano_acao_id" value="'.$plano_acao_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($plano_acao_id ? null : uuid()).'" />';
echo '<input name="plano_acao_usuarios" id="plano_acao_usuarios" type="hidden" value="'.implode(',',$usuarios_selecionados).'" />';
echo '<input name="plano_acao_depts" id="plano_acao_depts" type="hidden" value="'.implode(',',$depts_selecionados).'" />';
echo '<input name="plano_acao_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';
echo '<input name="plano_acao_contatos" id="plano_acao_contatos" type="hidden" value="'.implode(',',$contatos_selecionados).'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="del" value="" />';
echo '<input type="hidden" name="modulo" value="" />';
echo '<input type="hidden" name="existe_acao" id="existe_acao" value="" />';
echo '<input type="hidden" name="profissional" id="profissional" value="'.($Aplic->profissional ? 1 : 0).'" />';

echo '<input type="hidden" name="plano_acao_calculo_porcentagem_antigo" id="plano_acao_calculo_porcentagem_antigo" value="'.$obj->plano_acao_calculo_porcentagem.'" />';
echo '<input type="hidden" name="plano_acao_percentagem_antigo" id="plano_acao_percentagem_antigo" value="'.$obj->plano_acao_percentagem.'" />';


$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'acao\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

echo estiloTopoCaixa();
echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';
echo '<tr><td align="right">'.dica('Nome d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Tod'.$config['genero_acao'].' '.$config['acao'].' necessita ter um nome para identifica��o pel'.$config['genero_usuario'].'s '.$config['usuarios'].' do Sistema.').'Nome:'.dicaF().'</td><td><input type="text" name="plano_acao_nome" value="'.$obj->plano_acao_nome.'" style="width:286px;" class="texto" /> *</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Respons�vel', 'A qual '.$config['organizacao'].' pertence '.($config['genero_acao']=='o' ? 'este' : 'esta').' '.$config['acao'].'.').ucfirst($config['organizacao']).' respons�vel:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'plano_acao_cia', 'class=texto size=1 style="width:286px;" onchange="javascript:mudar_om();"').'</div></td></tr>';


if ($Aplic->profissional && isset($exibir['cias']) && $exibir['cias']) {
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
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' est�o envolvid'.$config['genero_organizacao'].' com '.($config['genero_acao']=='o' ? 'este' : 'esta').' '.$config['acao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}

if ($Aplic->profissional) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Respons�vel', 'Escolha pressionando o �cone � direita qual '.$config['genero_dept'].' '.$config['dept'].' respons�vel por '.($config['genero_acao']=='a' ? 'esta' : 'este').' '.$config['acao'].'.').ucfirst($config['departamento']).' respons�vel:'.dicaF().'</td><td><input type="hidden" name="plano_acao_dept" id="plano_acao_dept" value="'.($plano_acao_id ? $obj->plano_acao_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($plano_acao_id ? $obj->plano_acao_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:284px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' est�o envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';

echo '<tr><td align="right" nowrap="nowrap" width="100">'.dica('Respons�vel pela Iniciativa', 'Tod'.$config['genero_iniciativa'].' '.$config['iniciativa'].' deve ter um respons�vel.').'Respons�vel:'.dicaF().'</td><td colspan="2"><input type="hidden" id="plano_acao_responsavel" name="plano_acao_responsavel" value="'.($obj->plano_acao_responsavel ? $obj->plano_acao_responsavel : $Aplic->usuario_id).'" /><input type="text" id="nome_gerente" name="nome_gerente" value="'.nome_om(($obj->plano_acao_responsavel ? $obj->plano_acao_responsavel : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste �cone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';

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
echo '<tr><td align="right" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' est�o envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:288px;"><div id="combo_usuarios">'.$saida_usuarios.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['usuarios'].'.','popUsuarios()').'</td></tr></table></td></tr>';


$saida_contatos='';
if (count($contatos_selecionados)) {
		$saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%>';
		$saida_contatos.= '<tr><td>'.link_contato($contatos_selecionados[0],'','','esquerda');
		$qnt_lista_contatos=count($contatos_selecionados);
		if ($qnt_lista_contatos > 1) {
				$lista='';
				for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';
				$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.$config['contatos'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
				}
		$saida_contatos.= '</td></tr></table>';
		}
else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" style="width:288px;"><tr><td>&nbsp;</td></tr></table>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Contatos', 'Quais '.strtolower($config['contatos']).' est�o envolvid'.$config['genero_contato'].'s.').ucfirst($config['contatos']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:286px;"><div id="combo_contatos">'.$saida_contatos.'</div></td><td>'.botao_icone('usuarios.gif','Selecionar', 'selecionar '.$config['contatos'].'.','popContatos()').'</td></tr></table></td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Progresso', 'O plan '.$config['genero_acao'].' '.$config['acao'].' pode ir de 0% (n�o iniciado) at� 100% (completado).').'Progresso:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($percentual, 'plano_acao_percentagem', 'size="1" class="texto"'.($Aplic->profissional && $exibir['porcentagem_item'] && $obj->plano_acao_calculo_porcentagem ? ' disabled' : ''), (int)$obj->plano_acao_percentagem).'% </td></tr>';


if ($Aplic->profissional && $exibir['porcentagem_item']) echo '<tr><td align="right"  nowrap="nowrap">'.dica('Progresso Calculado', 'Caso esteja marcada, '.$config['genero_acao'].' '.$config['acao'].' ter� o progresso calculado baseado nos �tens d'.$config['genero_acao'].' mesm'.$config['genero_acao'].'.').'Progresso calculado:'.dicaF().'</td><td><input type="checkbox" value="1" onchange="desabilirar_porcentagem();" name="plano_acao_calculo_porcentagem" id="plano_acao_calculo_porcentagem" '.($obj->plano_acao_calculo_porcentagem || !$plano_acao_id ? 'checked="checked"' : '').' /></td></tr>';



if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores d'.$config['genero_acao'].' '.$config['acao'].' o mais representativo da situa��o geral do mesmo.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'plano_acao_principal_indicador', 'class="texto" style="width:267px;"', $obj->plano_acao_principal_indicador).'</td></tr>';


if ($exibir['plano_acao_descricao'])  echo '<tr><td align="right" nowrap="nowrap" >'.dica('Descri��o', 'Descri��o sobre '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.').'Descri��o:'.dicaF().'</td><td width="100%" colspan="2"><textarea data-gpweb-cmp="ckeditor" name="plano_acao_descricao" style="width:284px;" rows="2" class="textarea">'.$obj->plano_acao_descricao.'</textarea></td></tr>';

if (!$Aplic->profissional){
	if ($obj->plano_acao_projeto) $plano_acao_projeto=$obj->plano_acao_projeto;
	if ($obj->plano_acao_tarefa) $plano_acao_tarefa=$obj->plano_acao_tarefa;
	elseif ($obj->plano_acao_fator) $plano_acao_fator=$obj->plano_acao_fator;
	elseif ($obj->plano_acao_indicador) $plano_acao_indicador=$obj->plano_acao_indicador;
	elseif ($obj->plano_acao_estrategia) $plano_acao_estrategia=$obj->plano_acao_estrategia;
	elseif ($obj->plano_acao_meta) $plano_acao_meta=$obj->plano_acao_meta;
	elseif ($obj->plano_acao_objetivo) $plano_acao_objetivo=$obj->plano_acao_objetivo;
	elseif ($obj->plano_acao_perspectiva) $plano_acao_perspectiva=$obj->plano_acao_perspectiva;
	elseif ($obj->plano_acao_pratica) $plano_acao_pratica=$obj->plano_acao_fator;
	elseif ($obj->plano_acao_tema) $plano_acao_tema=$obj->plano_acao_tema;
	}


$tipos=array(
	''=>'',
	'projeto' => ucfirst($config['projeto']),
	'perspectiva'=> ucfirst($config['perspectiva']),
	'tema'=> ucfirst($config['tema']),
	'objetivo'=> ucfirst($config['objetivo']),
	'estrategia'=> ucfirst($config['iniciativa']),
	'meta'=>ucfirst($config['meta']),
	'pratica' => ucfirst($config['pratica']),
	'indicador' => 'Indicador',
	);
if (!$Aplic->profissional || ($Aplic->profissional && $config['exibe_fator'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'fator'))) $tipos['fator']=ucfirst($config['fator']);		
if ($ata_ativo) $tipos['ata']='Ata de Reuni�o';
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
	$tipos['avaliacao']='Avalia��o';
	$tipos['tgn']=ucfirst($config['tgn']);
	$tipos['brainstorm']='Brainstorm';
	$tipos['gut']='Matriz G.U.T.';
	$tipos['causa_efeito']='Diagrama de Causa-Efeito';
	$tipos['arquivo']='Arquivo';
	$tipos['forum']='F�rum';
	$tipos['checklist']='Checklist';
	$tipos['agenda']='Compromisso';
	if ($agrupamento_ativo) $tipos['agrupamento']='Agrupamento';
	if ($patrocinador_ativo) $tipos['patrocinador']='Patrocinador';
	$tipos['template']='Modelo';
	$tipos['painel']='Painel de Indicador';
	$tipos['painel_odometro']='Od�metro de Indicador';
	$tipos['painel_composicao']='Composi��o de Pain�is';
	if ($tr_ativo) $tipos['tr']=ucfirst($config['tr']);
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) $tipos['me']=ucfirst($config['me']);
	}
asort($tipos);

if ($plano_acao_projeto) $tipo='projeto';
elseif ($plano_acao_pratica) $tipo='pratica';
elseif ($plano_acao_objetivo) $tipo='objetivo';
elseif ($plano_acao_tema) $tipo='tema';
elseif ($plano_acao_fator) $tipo='fator';
elseif ($plano_acao_estrategia) $tipo='estrategia';
elseif ($plano_acao_perspectiva) $tipo='perspectiva';
elseif ($plano_acao_canvas) $tipo='canvas';
elseif ($plano_acao_risco) $tipo='risco';
elseif ($plano_acao_risco_resposta) $tipo='risco_resposta';
elseif ($plano_acao_meta) $tipo='meta';
elseif ($plano_acao_indicador) $tipo='plano_acao_indicador';
elseif ($plano_acao_swot) $tipo='swot';
elseif ($plano_acao_ata) $tipo='ata';
elseif ($plano_acao_monitoramento) $tipo='monitoramento';
elseif ($plano_acao_calendario) $tipo='calendario';
elseif ($plano_acao_operativo) $tipo='operativo';
elseif ($plano_acao_instrumento) $tipo='instrumento';
elseif ($plano_acao_recurso) $tipo='recurso';
elseif ($plano_acao_problema) $tipo='problema';
elseif ($plano_acao_demanda) $tipo='demanda';
elseif ($plano_acao_programa) $tipo='programa';
elseif ($plano_acao_licao) $tipo='licao';
elseif ($plano_acao_evento) $tipo='evento';
elseif ($plano_acao_link) $tipo='link';
elseif ($plano_acao_avaliacao) $tipo='avaliacao';
elseif ($plano_acao_tgn) $tipo='tgn';
elseif ($plano_acao_brainstorm) $tipo='brainstorm';
elseif ($plano_acao_gut) $tipo='gut';
elseif ($plano_acao_causa_efeito) $tipo='causa_efeito';
elseif ($plano_acao_arquivo) $tipo='arquivo';
elseif ($plano_acao_forum) $tipo='forum';
elseif ($plano_acao_checklist) $tipo='checklist';
elseif ($plano_acao_agenda) $tipo='agenda';
elseif ($plano_acao_agrupamento) $tipo='agrupamento';
elseif ($plano_acao_patrocinador) $tipo='patrocinador';
elseif ($plano_acao_template) $tipo='template';
elseif ($plano_acao_painel) $tipo='painel';
elseif ($plano_acao_painel_odometro) $tipo='painel_odometro';
elseif ($plano_acao_painel_composicao) $tipo='painel_composicao';
elseif ($plano_acao_tr) $tipo='tr';
elseif ($plano_acao_me) $tipo='me';

else $tipo='';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionad'.$config['genero_acao'],'A qual parte do sistema '.$config['genero_acao'].' '.$config['acao'].' est� relacionad'.$config['genero_acao'].'.').'Relacionad'.$config['genero_acao'].':'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:284px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
echo '<tr '.($plano_acao_projeto || $plano_acao_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo dever� constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_projeto" value="'.$plano_acao_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($plano_acao_projeto).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
echo '<tr '.($plano_acao_projeto || $plano_acao_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionad'.$config['genero_tarefa'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo dever� constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_tarefa" value="'.$plano_acao_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($plano_acao_tarefa).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste �cone '.imagem('icones/tarefa_p.gif').' escolher � qual '.$config['tarefa'].' o arquivo ir� pertencer.<br><br>Caso n�o escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo ser� d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo dever� constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_pratica" value="'.$plano_acao_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($plano_acao_pratica).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste �cone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo dever� constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_perspectiva" value="'.$plano_acao_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($plano_acao_perspectiva).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste �cone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo dever� constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_tema" value="'.$plano_acao_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($plano_acao_tema).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste �cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo dever� constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_objetivo" value="'.$plano_acao_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($plano_acao_objetivo).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste �cone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo dever� constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_estrategia" value="'.$plano_acao_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($plano_acao_estrategia).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste �cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo dever� constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_fator" value="'.$plano_acao_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($plano_acao_fator).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste �cone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo dever� constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_meta" value="'.$plano_acao_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($plano_acao_meta).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" nowrap="nowrap">'.dica('Indicador', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um indicador, neste campo dever� constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_indicador" value="'.$plano_acao_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($plano_acao_indicador).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste �cone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';

if ($agrupamento_ativo) echo '<tr '.($plano_acao_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um agrupamento, neste campo dever� constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_agrupamento" value="'.$plano_acao_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($plano_acao_agrupamento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste �cone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="plano_acao_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

if ($patrocinador_ativo) echo '<tr '.($plano_acao_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um patrocinador, neste campo dever� constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_patrocinador" value="'.$plano_acao_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($plano_acao_patrocinador).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste �cone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="plano_acao_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';


echo '<tr '.($plano_acao_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de uma agenda, neste campo dever� constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_calendario" value="'.$plano_acao_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($plano_acao_calendario).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo dever� constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_instrumento" value="'.$plano_acao_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($plano_acao_instrumento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste �cone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo dever� constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_recurso" value="'.$plano_acao_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($plano_acao_recurso).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste �cone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
if ($problema_ativo) echo '<tr '.($plano_acao_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo dever� constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_problema" value="'.$plano_acao_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($plano_acao_problema).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste �cone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="plano_acao_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
echo '<tr '.($plano_acao_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de uma demanda, neste campo dever� constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_demanda" value="'.$plano_acao_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($plano_acao_demanda).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste �cone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de uma li��o aprendida, neste campo dever� constar o nome da li��o aprendida.').'Li��o Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_licao" value="'.$plano_acao_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($plano_acao_licao).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Li��o Aprendida','Clique neste �cone '.imagem('icones/licoes_p.gif').' para selecionar uma li��o aprendida.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_evento ? '' : 'style="display:none"').' id="evento" ><td align="right" nowrap="nowrap">'.dica('Evento', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um evento, neste campo dever� constar o nome do evento.').'Evento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_evento" value="'.$plano_acao_evento.'" /><input type="text" id="evento_nome" name="evento_nome" value="'.nome_evento($plano_acao_evento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEvento();">'.imagem('icones/calendario_p.png','Selecionar Evento','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um evento.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um link, neste campo dever� constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_link" value="'.$plano_acao_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($plano_acao_link).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste �cone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avalia��o', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de uma avalia��o, neste campo dever� constar o nome da avalia��o.').'Avalia��o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_avaliacao" value="'.$plano_acao_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($plano_acao_avaliacao).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avalia��o','Clique neste �cone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avalia��o.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um brainstorm, neste campo dever� constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_brainstorm" value="'.$plano_acao_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($plano_acao_brainstorm).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste �cone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de uma matriz G.U.T., neste campo dever� constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_gut" value="'.$plano_acao_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($plano_acao_gut).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste �cone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um diagrama de causa-efeito, neste campo dever� constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_causa_efeito" value="'.$plano_acao_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($plano_acao_causa_efeito).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste �cone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um arquivo, neste campo dever� constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_arquivo" value="'.$plano_acao_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($plano_acao_arquivo).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste �cone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('F�rum', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um f�rum, neste campo dever� constar o nome do f�rum.').'F�rum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_forum" value="'.$plano_acao_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($plano_acao_forum).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar F�rum','Clique neste �cone '.imagem('icones/forum_p.gif').' para selecionar um f�rum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um checklist, neste campo dever� constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_checklist" value="'.$plano_acao_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($plano_acao_checklist).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste �cone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($plano_acao_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um compromisso, neste campo dever� constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_agenda" value="'.$plano_acao_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($plano_acao_agenda).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste �cone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
if (!$Aplic->profissional) {
	echo '<input type="hidden" name="plano_acao_programa" value="" id="programa" /><input type="hidden" id="programa_nome" name="programa_nome" value="">';
	echo '<input type="hidden" name="plano_acao_monitoramento" value="" id="monitoramento" /><input type="hidden" id="monitoramento_nome" name="monitoramento_nome" value="">';
	echo '<input type="hidden" name="plano_acao_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
	echo '<input type="hidden" name="plano_acao_tgn" value="" id="tgn" /><input type="hidden" id="tgn_nome" name="tgn_nome" value="">';
	echo '<input type="hidden" name="plano_acao_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
	echo '<input type="hidden" name="plano_acao_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
	echo '<input type="hidden" name="plano_acao_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
	echo '<input type="hidden" name="plano_acao_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
	echo '<input type="hidden" name="plano_acao_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
	echo '<input type="hidden" name="plano_acao_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
	echo '<input type="hidden" name="plano_acao_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
	echo '<input type="hidden" name="plano_acao_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
	}
else {
	echo '<tr '.($plano_acao_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo dever� constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_programa" value="'.$plano_acao_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($plano_acao_programa).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste �cone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um monitoramento, neste campo dever� constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_monitoramento" value="'.$plano_acao_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($plano_acao_monitoramento).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste �cone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um modelo, neste campo dever� constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_template" value="'.$plano_acao_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($plano_acao_template).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste �cone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo dever� constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_tgn" value="'.$plano_acao_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($plano_acao_tgn).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste �cone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo dever� constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_risco" value="'.$plano_acao_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($plano_acao_risco).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste �cone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo dever� constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_risco_resposta" value="'.$plano_acao_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($plano_acao_risco_resposta).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste �cone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo dever� constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_canvas" value="'.$plano_acao_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($plano_acao_canvas).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" nowrap="nowrap">'.dica('Painel de Indicador', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um painel de indicador, neste campo dever� constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_painel" value="'.$plano_acao_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($plano_acao_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste �cone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" nowrap="nowrap">'.dica('Od�metro de Indicador', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um od�metro de indicador, neste campo dever� constar o nome do od�metro.').'Od�metro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_painel_odometro" value="'.$plano_acao_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($plano_acao_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Od�metro','Clique neste �cone '.imagem('icones/odometro_p.png').' para selecionar um od�mtro.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" nowrap="nowrap">'.dica('Composi��o de Pain�is', 'Caso '.$config['genero_acao'].' '.$config['acao'].'o seja espec�fic'.$config['genero_acao'].' de uma composi��o de pain�is, neste campo dever� constar o nome da composi��o.').'Composi��o de Pain�is:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_painel_composicao" value="'.$plano_acao_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($plano_acao_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/painel_p.gif','Selecionar Composi��o de Pain�is','Clique neste �cone '.imagem('icones/painel_p.gif').' para selecionar uma composi��o de pain�is.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($plano_acao_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tr']), 'Caso seja espec�fico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo dever� constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_tr" value="'.$plano_acao_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($plano_acao_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr '.($plano_acao_me ? '' : 'style="display:none"').' id="me" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso seja espec�fico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo dever� constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_me" value="'.$plano_acao_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($plano_acao_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste �cone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="plano_acao_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';

	}
if ($swot_ativo) echo '<tr '.(isset($plano_acao_swot) && $plano_acao_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um campo da matriz SWOT neste campo dever� constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_swot" value="'.(isset($plano_acao_swot) ? $plano_acao_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($plano_acao_swot) ? $plano_acao_swot : null)).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste �cone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="plano_acao_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
if ($ata_ativo) echo '<tr '.(isset($plano_acao_ata) && $plano_acao_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reuni�o', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de uma ata de reuni�o neste campo dever� constar o nome da ata').'Ata de Reuni�o:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_ata" value="'.(isset($plano_acao_ata) ? $plano_acao_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($plano_acao_ata) ? $plano_acao_ata : null)).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reuni�o','Clique neste �cone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reuni�o.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="plano_acao_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
if ($operativo_ativo) echo '<tr '.($plano_acao_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso '.$config['genero_acao'].' '.$config['acao'].' seja espec�fic'.$config['genero_acao'].' de um plano operativo, neste campo dever� constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="plano_acao_operativo" value="'.$plano_acao_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($plano_acao_operativo).'" style="width:284px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste �cone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="plano_acao_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';
if ($Aplic->profissional){
	$sql->adTabela('plano_acao_gestao');
	$sql->adCampo('plano_acao_gestao.*');
	$sql->adOnde('plano_acao_gestao_acao ='.(int)$plano_acao_id);
	$sql->adOrdem('plano_acao_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['plano_acao_gestao_ordem'].', '.$gestao_data['plano_acao_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['plano_acao_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['plano_acao_gestao_tarefa']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['plano_acao_gestao_projeto']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['plano_acao_gestao_pratica']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['plano_acao_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['plano_acao_gestao_tema']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['plano_acao_gestao_objetivo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['plano_acao_gestao_fator']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['plano_acao_gestao_estrategia']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['plano_acao_gestao_meta']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['plano_acao_gestao_canvas']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['plano_acao_gestao_risco']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['plano_acao_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['plano_acao_gestao_indicador']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['plano_acao_gestao_calendario']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['plano_acao_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['plano_acao_gestao_ata']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['plano_acao_gestao_swot']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['plano_acao_gestao_operativo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['plano_acao_gestao_instrumento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['plano_acao_gestao_recurso']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['plano_acao_gestao_problema']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['plano_acao_gestao_demanda']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['plano_acao_gestao_programa']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['plano_acao_gestao_licao']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_evento']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_evento($gestao_data['plano_acao_gestao_evento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['plano_acao_gestao_link']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['plano_acao_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['plano_acao_gestao_tgn']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['plano_acao_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['plano_acao_gestao_gut']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['plano_acao_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['plano_acao_gestao_arquivo']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['plano_acao_gestao_forum']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['plano_acao_gestao_checklist']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['plano_acao_gestao_agenda']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['plano_acao_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['plano_acao_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['plano_acao_gestao_template']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_painel']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['plano_acao_gestao_painel']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['plano_acao_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['plano_acao_gestao_painel_composicao']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['plano_acao_gestao_tr']).'</td>';
		elseif ($gestao_data['plano_acao_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['plano_acao_gestao_me']).'</td>';

		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['plano_acao_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';
	}



$data_inicio = intval($obj->plano_acao_inicio) ? new CData($obj->plano_acao_inicio) :  new CData(date("Y-m-d H:i:s"));
$data_fim = intval($obj->plano_acao_fim) ? new CData($obj->plano_acao_fim) : new CData(date("Y-m-d H:i:s"));
echo '<input name="plano_acao_inicio" id="plano_acao_inicio" type="hidden" value="'.$data_inicio->format('%Y-%m-%d %H:%M:%S').'" />';
echo '<input name="plano_acao_fim" id="plano_acao_fim" type="hidden" value="'.$data_fim->format('%Y-%m-%d %H:%M:%S').'" />';


if ($Aplic->profissional){
	if ($exibir['plano_acao_codigo']) echo '<tr><td align="right">'.dica('C�digo', 'Escreva, caso exista, o c�digo d'.$config['genero_acao'].' '.$config['acao'].'.').'C�digo:'.dicaF().'</td><td><input type="text" style="width:284px;" class="texto" name="plano_acao_codigo" value="'.(isset($obj->plano_acao_codigo) ? $obj->plano_acao_codigo : '').'" size="30" maxlength="255" /></td></tr>';
	if ($exibir['ano']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Ano', 'A qual ano dever� '.$config['genero_acao'].' '.$config['acao'].' estar relacionad'.$config['genero_acao'].'.').'Ano:'.dicaF().'</td><td width="100%" colspan="2"><input type="text" name="plano_acao_ano" value="'.($obj->plano_acao_ano ? $obj->plano_acao_ano : date('Y')).'" size="4" class="texto" /></td></tr>';
	$setor = array('' => '') + getSisValor('AcaoSetor');
	if ($exibir['setor']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['setor']), 'A qual '.$config['setor'].' perternce '.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['setor']).':'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($setor, 'plano_acao_setor', 'style="width:286px;" class="texto" onchange="mudar_segmento();"', $obj->plano_acao_setor).'</td></tr>';
	$segmento=array('' => '');
	if ($obj->plano_acao_setor){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo="AcaoSegmento"');
		$sql->adOnde('sisvalor_chave_id_pai="'.$obj->plano_acao_setor.'"');
		$sql->adOrdem('sisvalor_valor');
		$segmento+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['segmento']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['segmento']), 'A qual '.$config['segmento'].' perternce '.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['segmento']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_segmento">'.selecionaVetor($segmento, 'plano_acao_segmento', 'style="width:286px;" class="texto" onchange="mudar_intervencao();"', $obj->plano_acao_segmento).'</div></td></tr>';
	$intervencao=array('' => '');
	if ($obj->plano_acao_segmento){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo="AcaoIntervencao"');
		$sql->adOnde('sisvalor_chave_id_pai="'.$obj->plano_acao_segmento.'"');
		$sql->adOrdem('sisvalor_valor');
		$intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['intervencao']), 'A qual '.$config['intervencao'].' perternce '.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['intervencao']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_intervencao">'.selecionaVetor($intervencao, 'plano_acao_intervencao', 'style="width:286px;" class="texto" onchange="mudar_tipo_intervencao();"', $obj->plano_acao_intervencao).'</div></td></tr>';

	$tipo_intervencao=array('' => '');
	if ($obj->plano_acao_intervencao){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo="AcaoTipoIntervencao"');
		$sql->adOnde('sisvalor_chave_id_pai="'.$obj->plano_acao_intervencao.'"');
		$sql->adOrdem('sisvalor_valor');
		$tipo_intervencao+= $sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	if ($exibir['tipo_intervencao']) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tipo']), 'A qual '.$config['tipo'].' pertence '.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['tipo']).':'.dicaF().'</td><td width="100%" colspan="2"><div id="combo_tipo_intervencao">'.selecionaVetor($tipo_intervencao, 'plano_acao_tipo_intervencao', 'style="width:286px;" class="texto"', $obj->plano_acao_tipo_intervencao).'</div></td></tr>';
	}






echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de In�cio', 'Digite ou escolha no calend�rio a data prov�vel de in�cio.').'Data de in�cio:'.dicaF().'</td><td nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="oculto_plano_acao_inicio" id="oculto_plano_acao_inicio" value="'.($data_inicio ? $data_inicio->format("%Y-%m-%d") : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'oculto_plano_acao_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data de In�cio', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de in�cio.').'<a href="javascript: void(0);" ><img src="'.acharImagem('calendario.gif').'" id="f_btn1" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" />'.dicaF().'</a>'.dica('Hora do In�cio', 'Selecione na caixa de sele��o a hora do �nicio d'.$config['genero_tarefa'].' '.$config['tarefa']). selecionaVetor($horas, 'inicio_hora', 'size="1" onchange="CompararDatas();" class="texto"', $data_inicio->getHour()).' : '.dica('Minutos do In�cio', 'Selecione na caixa de sele��o os minutos do �nicio.').selecionaVetor($minutos, 'inicio_minuto', 'size="1" class="texto" onchange="CompararDatas();"', $data_inicio->getMinute()).'</td></tr></table></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de t�rmino', 'Digite ou escolha no calend�rio a data prov�vel de t�rmino').'Data de t�rmino:</td><td nowrap="nowrap"><input type="hidden" name="oculto_plano_acao_fim" id="oculto_plano_acao_fim" value="'.($data_fim ? $data_fim->format("%Y-%m-%d") : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'oculto_plano_acao_fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" /><a href="javascript: void(0);" >'.dica('Meta de T�rmino', 'Clique neste �cone '.imagem('icones/calendario.gif').'  para abrir um calend�rio onde poder� selecionar a data prov�vel de t�rmino.').'<img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calend�rio" />'.dicaF().'</a>'.dica('Hora do T�rmino', 'Selecione na caixa de sele��o a hora do t�rmino.</p>Caso n�o saiba a hora prov�vel de t�rmino, deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($horas, 'fim_hora', 'size="1" onchange="CompararDatas();" class="texto"', $data_fim ? $data_fim->getHour() : $fim).' : '.dica('Minutos do T�rmino', 'Selecione na caixa de sele��o os minutos do t�rmino. </p>Caso n�o saiba os minutos prov�veis de t�rmino, deixe em branco este campo e clique no bot�o <b>Data de T�rmino</b>').selecionaVetor($minutos, 'fim_minuto', 'size="1" class="texto" onchange="CompararDatas();"', $data_fim ? $data_fim->getMinute() : '00').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualiza��o pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo � direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="plano_acao_cor" value="'.($obj->plano_acao_cor ? $obj->plano_acao_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualiza��o dos eventos pode-se escolher uma das 216 cores pr�-definidas, bastando clicar no ret�ngulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto � esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->plano_acao_cor ? $obj->plano_acao_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('N�vel de Acesso', 'Os '.$config['objetivos'].' podem ter cinco n�veis de acesso:<ul><li><b>P�blico</b> - Todos podem ver e editar '.$config['genero_acao'].' '.$config['acao'].'.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o respons�vel e os designados para '.$config['genero_acao'].' '.$config['acao'].' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o respons�vel pode editar.</li><li><b>Participante</b> - Somente o respons�vel e os designados para '.$config['genero_acao'].' '.$config['acao'].' ver e editar '.$config['genero_acao'].' '.$config['acao'].'</li><li><b>Privado</b> - Somente o respons�vel e os designados para '.$config['genero_acao'].' '.$config['acao'].' podem ver a mesma, e o respons�vel editar.</li></ul>').'N�vel de acesso:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($plano_acao_acesso, 'plano_acao_acesso', 'class="texto"', ($plano_acao_id ? $obj->plano_acao_acesso  : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" width="100">'.dica('Ativo', 'Caso '.$config['genero_acao'].' '.$config['acao'].' ainda esteja ativ'.$config['genero_acao'].' dever� estar marcado este campo.').'Ativo:'.dicaF().'</td><td><input type="checkbox" value="1" name="plano_acao_ativo" '.($obj->plano_acao_ativo || !$plano_acao_id ? 'checked="checked"' : '').' /></td></tr>';

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/plano_acao_editar_pro.php';

echo '<tr style="height:4px;"><td colspan=20></td></tr>';

echo '<tr><td width="100%" colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'notificar\').style.display) document.getElementById(\'notificar\').style.display=\'\'; else document.getElementById(\'notificar\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Notificar</b></a></td></tr>';
echo '<tr id="notificar" style="display:none"><td colspan=20><table width="100%" cellspacing=0 cellpadding=0>';

echo '<tr><td align="right" valign="top" nowrap="nowrap" width=120>'.dica('Notificar', 'Marque esta caixa para avisar sobre a '.($plano_acao_id > 0 ? 'modifica��o' : 'cria��o').' d'.$config['genero_acao'].' '.$config['acao'].'.').'Notificar:'.dicaF().'</td><td><input type="checkbox" name="email_responsavel" id="email_responsavel" '.($Aplic->getPref('informa_responsavel') ? 'checked="checked"' : '').' value="1" />'.dica('Respons�vel pel'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para o respons�vel pel'.$config['genero_acao'].' '.$config['acao'].'.').'<label for="email_responsavel">Respons�vel</label>'.dicaF().'<input type="checkbox" name="email_designados" id="email_designados" '.($Aplic->getPref('informa_designados') ? 'checked="checked"' : '').' />'.dica('Designados para '.$config['genero_acao'].' '.ucfirst($config['acao']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os designados para '.$config['genero_acao'].' '.$config['acao'].'.').'<label for="email_designados">Designados</label>'.dicaF().'<input type="checkbox" name="email_contatos" id="email_contatos" '.($Aplic->getPref('informa_contatos') ? 'checked="checked"' : '').' />'.dica('Contatos d'.$config['genero_acao'].' '.ucfirst($config['acao']), 'Caso esta caixa esteja selecionada, um e-mail ser� enviado para os contatos d'.$config['genero_acao'].' '.$config['acao'].'.').'<label for="email_contatos">Contatos</label>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Texto', 'Os dados b�sicos d'.$config['genero_acao'].' '.$config['acao'].' s�o automaticamente acrescentado nas mnsagens enviadas, porem escreva na caixa de texto caso deseja enviar outras informa��es junto com a mensagem.').'Texto:'.dicaF().'</td><td><textarea data-gpweb-cmp="ckeditor" name="email_comentario" class="textarea" style="width:284px;" rows="1"></textarea></td></tr>';

echo '</table></td></tr>';

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td>'.(!$dialogo ? '<td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($plano_acao_id ? 'edi��o' : 'cria��o').' do pratica.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td>':'').'</tr></table></td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa();

?>

<script language="javascript">

var contatos_id_selecionados = '<?php echo implode(",", $contatos_selecionados)?>';

function popContatos() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["contatos"])?>', 500, 500, 'm=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('plano_acao_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, window.setContatos, window);
	else window.open('./index.php?m=publico&a=selecao_contato&dialogo=1&chamar_volta=setContatos&cia_id='+document.getElementById('plano_acao_cia').value+'&contatos_id_selecionados='+contatos_id_selecionados, '<?php echo ucfirst($config["contatos"])?>','height=500,width=500,resizable,scrollbars=yes');
	}

function setContatos(contato_id_string){
	if(!contato_id_string) contato_id_string = '';
	document.env.plano_acao_contatos.value = contato_id_string;
	contatos_id_selecionados = contato_id_string;
	xajax_exibir_contatos(contatos_id_selecionados, 'combo_contatos');
	__buildTooltip();
	}



var usuarios_id_selecionados = '<?php echo implode(",", $usuarios_selecionados)?>';

function popUsuarios() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuarios"])?>', 500, 500, 'm=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('plano_acao_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, window.setUsuarios, window);
	else window.open('./index.php?m=publico&a=selecao_usuario&dialogo=1&chamar_volta=setUsuarios&cia_id='+document.getElementById('plano_acao_cia').value+'&usuarios_id_selecionados='+usuarios_id_selecionados, 'usuarios','height=500,width=500,resizable,scrollbars=yes');
	}

function setUsuarios(usuario_id_string){
	if(!usuario_id_string) usuario_id_string = '';
	document.env.plano_acao_usuarios.value = usuario_id_string;
	usuarios_id_selecionados = usuario_id_string;
	xajax_exibir_usuarios(usuarios_id_selecionados);
	__buildTooltip();
	}


var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamentos"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('plano_acao_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('plano_acao_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.plano_acao_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}

var cias_id_selecionadas = '<?php echo implode(',', $cias_selecionadas)?>';

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('plano_acao_cia').value+'&cias_id_selecionadas='+cias_id_selecionadas, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.plano_acao_cias.value = organizacao_id_string;
	cias_id_selecionadas = organizacao_id_string;
	xajax_exibir_cias(cias_id_selecionadas);
	__buildTooltip();
	}


function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('plano_acao_dept').value+'&cia_id='+document.getElementById('plano_acao_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('plano_acao_dept').value+'&cia_id='+document.getElementById('plano_acao_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('plano_acao_cia').value=cia_id;
	document.getElementById('plano_acao_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}



function desabilirar_porcentagem(){
	if (document.getElementById("plano_acao_calculo_porcentagem").checked) document.getElementById("plano_acao_percentagem").disabled=true;
	else document.getElementById("plano_acao_percentagem").disabled=false;
	}


function mudar_segmento(){
	document.getElementById('plano_acao_intervencao').length=0;
	document.getElementById('plano_acao_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('plano_acao_setor').value, 'AcaoSegmento', 'plano_acao_segmento','combo_segmento', 'style="width:286px;" class="texto" size=1 onchange="mudar_intervencao();"');
	}

function mudar_intervencao(){
	document.getElementById('plano_acao_tipo_intervencao').length=0;
	xajax_mudar_ajax(document.getElementById('plano_acao_segmento').value, 'AcaoIntervencao', 'plano_acao_intervencao','combo_intervencao', 'style="width:286px;" class="texto" size=1 onchange="mudar_tipo_intervencao();"');
	}

function mudar_tipo_intervencao(){
	xajax_mudar_ajax(document.getElementById('plano_acao_intervencao').value, 'AcaoTipoIntervencao', 'plano_acao_tipo_intervencao','combo_tipo_intervencao', 'style="width:286px;" class="texto" size=1');
	}


var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "oculto_plano_acao_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
	  var date = cal1.selection.get();
	  if (date){
	  	date = Calendario.intToDate(date);
	    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_plano_acao_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    }
		cal1.hide();
		}
	});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "oculto_plano_acao_fim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
	  var date = cal2.selection.get();
	  if (date){
	    date = Calendario.intToDate(date);
	    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
	    document.getElementById("oculto_plano_acao_fim").value = Calendario.printDate(date, "%Y-%m-%d");
	    CompararDatas();
	    }
		cal2.hide();
		}
	});


function setData( frm_nome, f_data,  f_data_real){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	  	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
	  	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
	    campo_data.style.backgroundColor = '';

			//data final fazer ao menos no mesmo dia da inicial
			CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}


function CompararDatas(){
    var str1 = document.getElementById("data_inicio").value;
    var str2 = document.getElementById("data_fim").value;
    var dt1  = parseInt(str1.substring(0,2),10);
    var mon1 = parseInt(str1.substring(3,5),10);
    var yr1  = parseInt(str1.substring(6,10),10);
    var dt2  = parseInt(str2.substring(0,2),10);
    var mon2 = parseInt(str2.substring(3,5),10);
    var yr2  = parseInt(str2.substring(6,10),10);
    var date1 = new Date(yr1, mon1, dt1);
    var date2 = new Date(yr2, mon2, dt2);
    if(date2 < date1){
      document.getElementById("data_fim").value=document.getElementById("data_inicio").value;
      document.getElementById("oculto_plano_acao_fim").value=document.getElementById("oculto_plano_acao_inicio").value;
    	}
   }

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Respons�vel', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('plano_acao_cia').value+'&usuario_id='+document.getElementById('plano_acao_responsavel').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('plano_acao_cia').value+'&usuario_id='+document.getElementById('plano_acao_responsavel').value, 'Respons�vel','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('plano_acao_responsavel').value=usuario_id;
		document.getElementById('nome_gerente').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
		}



function mudar_om(){
	var cia_id=document.getElementById('plano_acao_cia').value;
	xajax_selecionar_om_ajax(cia_id,'plano_acao_cia','combo_cia', 'class="texto" size=1 style="width:286px;" onchange="javascript:mudar_om();"');
	}

function excluir() {
	if (confirm( 'Tem certeza que deseja excluir este <?php echo $config["acao"] ?>?')) {
		var f = document.env;
		f.del.value=1;
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function setCor(cor) {
	var f = document.env;
	if (cor) f.plano_acao_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.plano_acao_cor.value;
	}


function enviarDados() {
	var f = document.env;

	xajax_acao_existe(f.plano_acao_nome.value, document.getElementById('plano_acao_id').value);

	if (!document.getElementById('profissional').value && document.getElementById('projeto').style.display=='' && f.plano_acao_projeto.value<1)	{
		alert('Escolha <?php echo ($config["genero_projeto"]=="a" ? "uma ": "um ").$config["projeto"] ?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('pratica').style.display=='' && f.plano_acao_pratica.value<1)	{
		alert('Escolha <?php echo ($config["genero_pratica"]=="a" ? "uma ": "um ").$config["pratica"] ?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('indicador').style.display=='' && f.plano_acao_indicador.value<1)	{
		alert('Escolha um indicador');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('objetivo').style.display=='' && f.plano_acao_objetivo.value<1)	{
		alert("Escolha <?php echo ($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('estrategia').style.display=='' && f.plano_acao_estrategia.value<1)	{
		alert("Escolha <?php echo ($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('fator').style.display=='' && f.plano_acao_fator.value<1)	{
		alert("Escolha <?php echo ($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('meta').style.display=='' && f.plano_acao_meta.value<1)	{
		alert('Escolha uma meta');
		return;
		}

	if (f.existe_acao.value > 0) {
		alert('J� existe outr<?php echo $config["genero_acao"]." ".$config["acao"]?> com o mesmo nome');
		f.plano_acao_nome.focus();
		return;
		}

	if (f.plano_acao_nome.value.length < 3) {
		alert('Escreva um nome para <?php echo $config["genero_acao"]." ".$config["acao"]?> v�lido');
		f.plano_acao_nome.focus();
		return;
		}

	document.getElementById('plano_acao_inicio').value=document.getElementById('oculto_plano_acao_inicio').value+' '+document.getElementById('inicio_hora').value+':'+document.getElementById('inicio_minuto').value+':00';
	document.getElementById('plano_acao_fim').value=document.getElementById('oculto_plano_acao_fim').value+' '+document.getElementById('fim_hora').value+':'+document.getElementById('fim_minuto').value+':00';

	f.salvar.value=1;
	f.submit();
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('plano_acao_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('plano_acao_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.plano_acao_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('plano_acao_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('plano_acao_cia').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.plano_acao_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('plano_acao_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('plano_acao_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.plano_acao_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('plano_acao_cia').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('plano_acao_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.plano_acao_painel.value = chave;
		document.env.painel_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Od�metro', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('plano_acao_cia').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('plano_acao_cia').value, 'Od�metro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.plano_acao_painel_odometro.value = chave;
		document.env.painel_odometro_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composi��o de Pain�is', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('plano_acao_cia').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('plano_acao_cia').value, 'Composi��o de Pain�is','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.plano_acao_painel_composicao.value = chave;
		document.env.painel_composicao_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('plano_acao_cia').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.plano_acao_tr.value = chave;
		document.env.tr_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}
<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('plano_acao_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.plano_acao_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.plano_acao_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.plano_acao_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.plano_acao_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.plano_acao_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('plano_acao_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.plano_acao_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('plano_acao_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.plano_acao_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('plano_acao_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.plano_acao_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('plano_acao_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.plano_acao_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('plano_acao_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.plano_acao_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('plano_acao_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.plano_acao_meta.value = chave;
	document.env.meta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('plano_acao_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.plano_acao_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('plano_acao_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('plano_acao_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.plano_acao_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('plano_acao_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.plano_acao_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('plano_acao_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.plano_acao_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('plano_acao_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.plano_acao_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('plano_acao_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('plano_acao_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.plano_acao_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('plano_acao_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('plano_acao_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.plano_acao_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reuni�o', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('plano_acao_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.plano_acao_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('plano_acao_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.plano_acao_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('plano_acao_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('plano_acao_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.plano_acao_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jur�dico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('plano_acao_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('plano_acao_cia').value, 'Instrumento Jur�dico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.plano_acao_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('plano_acao_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('plano_acao_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.plano_acao_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('plano_acao_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.plano_acao_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('plano_acao_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('plano_acao_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.plano_acao_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('plano_acao_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.plano_acao_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('plano_acao_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.plano_acao_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function popEvento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Evento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('plano_acao_cia').value, window.setEvento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEvento&tabela=eventos&cia_id='+document.getElementById('plano_acao_cia').value, 'Evento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEvento(chave, valor){
	limpar_tudo();
	document.env.plano_acao_evento.value = chave;
	document.env.evento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('plano_acao_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('plano_acao_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.plano_acao_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avalia��o', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('plano_acao_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('plano_acao_cia').value, 'Avalia��o','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.plano_acao_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('plano_acao_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.plano_acao_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


<?php  if (isset($config['me'])) { ?>
function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('plano_acao_cia').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('plano_acao_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.plano_acao_me.value = chave;
		document.env.me_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}
<?php } ?>


function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('plano_acao_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('plano_acao_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.plano_acao_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('plano_acao_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('plano_acao_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.plano_acao_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('plano_acao_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('plano_acao_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.plano_acao_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('plano_acao_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('plano_acao_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.plano_acao_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('F�rum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('plano_acao_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('plano_acao_cia').value, 'F�rum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.plano_acao_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('plano_acao_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('plano_acao_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.plano_acao_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('plano_acao_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('plano_acao_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.plano_acao_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.plano_acao_projeto.value = null;
		}
	document.env.plano_acao_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.plano_acao_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.plano_acao_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.plano_acao_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.plano_acao_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.plano_acao_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.plano_acao_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.plano_acao_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.plano_acao_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.plano_acao_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.plano_acao_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.plano_acao_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.plano_acao_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.plano_acao_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.plano_acao_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.plano_acao_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.plano_acao_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.plano_acao_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.plano_acao_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.plano_acao_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.plano_acao_evento.value = null;
	document.env.evento_nome.value = '';
	document.env.plano_acao_link.value = null;
	document.env.link_nome.value = '';
	document.env.plano_acao_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.plano_acao_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.plano_acao_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.plano_acao_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.plano_acao_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.plano_acao_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.plano_acao_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.plano_acao_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.plano_acao_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.plano_acao_template.value = null;
	document.env.template_nome.value = '';
	document.env.plano_acao_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.plano_acao_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.plano_acao_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.plano_acao_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.plano_acao_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.plano_acao_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.plano_acao_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.plano_acao_patrocinador.value = null;';
	if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.plano_acao_tr.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.plano_acao_me.value = null;';

	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('plano_acao_id').value,
	document.getElementById('uuid').value,
	f.plano_acao_projeto.value,
	f.plano_acao_tarefa.value,
	f.plano_acao_perspectiva.value,
	f.plano_acao_tema.value,
	f.plano_acao_objetivo.value,
	f.plano_acao_fator.value,
	f.plano_acao_estrategia.value,
	f.plano_acao_meta.value,
	f.plano_acao_pratica.value,
	f.plano_acao_canvas.value,
	f.plano_acao_risco.value,
	f.plano_acao_risco_resposta.value,
	f.plano_acao_indicador.value,
	f.plano_acao_calendario.value,
	f.plano_acao_monitoramento.value,
	f.plano_acao_ata.value,
	f.plano_acao_swot.value,
	f.plano_acao_operativo.value,
	f.plano_acao_instrumento.value,
	f.plano_acao_recurso.value,
	f.plano_acao_problema.value,
	f.plano_acao_demanda.value,
	f.plano_acao_programa.value,
	f.plano_acao_licao.value,
	f.plano_acao_evento.value,
	f.plano_acao_link.value,
	f.plano_acao_avaliacao.value,
	f.plano_acao_tgn.value,
	f.plano_acao_brainstorm.value,
	f.plano_acao_gut.value,
	f.plano_acao_causa_efeito.value,
	f.plano_acao_arquivo.value,
	f.plano_acao_forum.value,
	f.plano_acao_checklist.value,
	f.plano_acao_agenda.value,
	f.plano_acao_agrupamento.value,
	f.plano_acao_patrocinador.value,
	f.plano_acao_template.value,
	f.plano_acao_painel.value,
	f.plano_acao_painel_odometro.value,
	f.plano_acao_painel_composicao.value,
	f.plano_acao_tr.value,
	f.plano_acao_me.value

	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(plano_acao_gestao_id){
	xajax_excluir_gestao(document.getElementById('plano_acao_id').value, document.getElementById('uuid').value, plano_acao_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, plano_acao_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, plano_acao_gestao_id, direcao, document.getElementById('plano_acao_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$plano_acao_id && (
	$plano_acao_projeto ||
	$plano_acao_tarefa ||
	$plano_acao_perspectiva ||
	$plano_acao_tema ||
	$plano_acao_objetivo ||
	$plano_acao_fator ||
	$plano_acao_estrategia ||
	$plano_acao_meta ||
	$plano_acao_pratica ||
	$plano_acao_canvas ||
	$plano_acao_risco ||
	$plano_acao_risco_resposta ||
	$plano_acao_indicador ||
	$plano_acao_calendario ||
	$plano_acao_monitoramento ||
	$plano_acao_ata ||
	$plano_acao_swot ||
	$plano_acao_operativo ||
	$plano_acao_instrumento ||
	$plano_acao_recurso ||
	$plano_acao_problema ||
	$plano_acao_demanda ||
	$plano_acao_programa ||
	$plano_acao_licao ||
	$plano_acao_evento ||
	$plano_acao_link ||
	$plano_acao_avaliacao ||
	$plano_acao_tgn ||
	$plano_acao_brainstorm ||
	$plano_acao_gut ||
	$plano_acao_causa_efeito ||
	$plano_acao_arquivo ||
	$plano_acao_forum ||
	$plano_acao_checklist ||
	$plano_acao_agenda ||
	$plano_acao_agrupamento ||
	$plano_acao_patrocinador ||
	$plano_acao_template||
	$plano_acao_painel ||
	$plano_acao_painel_odometro ||
	$plano_acao_painel_composicao ||
	$plano_acao_tr ||
	$plano_acao_me
	)) echo 'incluir_relacionado();';
	?>

</script>



