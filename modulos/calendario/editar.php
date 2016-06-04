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
global $Aplic, $config, $cal_sdf;

$sql = new BDConsulta;

$grupo_id=getParam($_REQUEST, 'grupo_id', $Aplic->usuario_prefs['grupoid']);
$grupo_id2=getParam($_REQUEST, 'grupo_id2', $Aplic->usuario_prefs['grupoid2']);

$evento_id = getParam($_REQUEST, 'evento_id',null);
$eh_conflito = isset($_SESSION['evento_eh_conflito']) ? $_SESSION['evento_eh_conflito'] : false;

$Aplic->carregarCKEditorJS();
$Aplic->carregarCalendarioJS();


if (!$grupo_id && !$grupo_id2) {
	$grupo_id=$Aplic->usuario_prefs['grupoid'];
	$grupo_id2=$Aplic->usuario_prefs['grupoid2'];
	}


$sql->adTabela('grupo');
$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp1 WHERE gp1.grupo_id=grupo.grupo_id) AS protegido, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp2 WHERE gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id='.(int)$Aplic->usuario_id.') AS pertence');
$sql->adOnde('grupo_usuario IS NULL');
$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$achados=$sql->Lista();
$sql->limpar();

$grupos=array();
$grupos[0]='';
$tem_protegido=0;
foreach($achados as $linha) {
	if ($linha['protegido']) $tem_protegido=1;
	if (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence']) )$grupos[$linha['grupo_id']]=$linha['grupo_descricao'];
	}
//verificar se há grupo privado da cia, se houver não haverá opção de ver todos o usuários da cia
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) $grupos=$grupos+array('-1'=>'Todos '.$config['genero_usuario'].'s '.$config['usuarios'].' d'.$config['genero_organizacao'].' '.$config['organizacao']);
if ($tem_protegido && $grupo_id==-1 && !$Aplic->usuario_super_admin && !$Aplic->usuario_admin) $grupo_id=0;

$niveis_acesso=getSisValor('NivelAcesso','','','sisvalor_id');

$direcao = getParam($_REQUEST, 'cmd', '');
$evento_arquivo_id = getParam($_REQUEST, 'evento_arquivo_id', '0');
$ordem = getParam($_REQUEST, 'ordem', '0');
$salvaranexo = getParam($_REQUEST, 'salvaranexo', 0);
$excluiranexo = getParam($_REQUEST, 'excluiranexo', 0);


if (!$podeAdicionar && !$evento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$podeEditar && $evento_id) $Aplic->redirecionar('m=publico&a=acesso_negado');

$data = getParam($_REQUEST, 'data', null);
$obj = new CEvento();

$vazio=array();

//vindo de conflito
$objeto=getParam($_REQUEST, 'objeto', null);
if ($objeto) {
	$_REQUEST=unserialize(base64_decode($objeto));
	$obj->join($_REQUEST);

	if ($obj->evento_inicio) {
		$data_inicio = new CData($obj->evento_inicio.getParam($_REQUEST, 'inicio_hora', null));
		$obj->evento_inicio = $data_inicio->format('%Y-%m-%d %H:%M:%S');
		}
	if ($obj->evento_fim) {
		$data_fim = new CData($obj->evento_fim.getParam($_REQUEST, 'fim_hora', null));
		$obj->evento_fim = $data_fim->format('%Y-%m-%d %H:%M:%S');
		}

	if ($obj->evento_id) $evento_id=$obj->evento_id;
	}
else $obj->load($evento_id);

$tipos = getSisValor('TipoEvento');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'evento\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

//vindo do adicionar evento no plano de comunicacoes
$projeto_comunicacao_evento_id = getParam($_REQUEST, 'projeto_comunicacao_evento_id', null);

$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;


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

$evento_projeto = getParam($_REQUEST, 'evento_projeto', null);
$evento_tarefa = getParam($_REQUEST, 'evento_tarefa', null);
$evento_perspectiva = getParam($_REQUEST, 'evento_perspectiva', null);
$evento_tema = getParam($_REQUEST, 'evento_tema', null);
$evento_objetivo = getParam($_REQUEST, 'evento_objetivo', null);
$evento_fator = getParam($_REQUEST, 'evento_fator', null);
$evento_estrategia = getParam($_REQUEST, 'evento_estrategia', null);
$evento_meta = getParam($_REQUEST, 'evento_meta', null);
$evento_pratica = getParam($_REQUEST, 'evento_pratica', null);
$evento_acao = getParam($_REQUEST, 'evento_acao', null);
$evento_canvas = getParam($_REQUEST, 'evento_canvas', null);
$evento_risco = getParam($_REQUEST, 'evento_risco', null);
$evento_risco_resposta = getParam($_REQUEST, 'evento_risco_resposta', null);
$evento_indicador = getParam($_REQUEST, 'evento_indicador', null);
$evento_calendario = getParam($_REQUEST, 'evento_calendario', null);
$evento_monitoramento = getParam($_REQUEST, 'evento_monitoramento', null);
$evento_ata = getParam($_REQUEST, 'evento_ata', null);
$evento_swot = getParam($_REQUEST, 'evento_swot', null);
$evento_operativo = getParam($_REQUEST, 'evento_operativo', null);
$evento_instrumento = getParam($_REQUEST, 'evento_instrumento', null);
$evento_recurso = getParam($_REQUEST, 'evento_recurso', null);
$evento_problema = getParam($_REQUEST, 'evento_problema', null);
$evento_demanda = getParam($_REQUEST, 'evento_demanda', null);
$evento_programa = getParam($_REQUEST, 'evento_programa', null);
$evento_licao = getParam($_REQUEST, 'evento_licao', null);
$evento_link = getParam($_REQUEST, 'evento_link', null);
$evento_avaliacao = getParam($_REQUEST, 'evento_avaliacao', null);
$evento_tgn = getParam($_REQUEST, 'evento_tgn', null);
$evento_brainstorm = getParam($_REQUEST, 'evento_brainstorm', null);
$evento_gut = getParam($_REQUEST, 'evento_gut', null);
$evento_causa_efeito = getParam($_REQUEST, 'evento_causa_efeito', null);
$evento_arquivo = getParam($_REQUEST, 'evento_arquivo', null);
$evento_forum = getParam($_REQUEST, 'evento_forum', null);
$evento_checklist = getParam($_REQUEST, 'evento_checklist', null);
$evento_agenda = getParam($_REQUEST, 'evento_agenda', null);
$evento_agrupamento = getParam($_REQUEST, 'evento_agrupamento', null);
$evento_patrocinador = getParam($_REQUEST, 'evento_patrocinador', null);
$evento_template = getParam($_REQUEST, 'evento_template', null);
$evento_painel = getParam($_REQUEST, 'evento_painel', null);
$evento_painel_odometro = getParam($_REQUEST, 'evento_painel_odometro', null);
$evento_painel_composicao = getParam($_REQUEST, 'evento_painel_composicao', null);
$evento_tr = getParam($_REQUEST, 'evento_tr', null);
$evento_me = getParam($_REQUEST, 'evento_me', null);
if (
	$evento_projeto ||
	$evento_tarefa ||
	$evento_perspectiva ||
	$evento_tema ||
	$evento_objetivo ||
	$evento_fator ||
	$evento_estrategia ||
	$evento_meta ||
	$evento_pratica ||
	$evento_acao ||
	$evento_canvas ||
	$evento_risco ||
	$evento_risco_resposta ||
	$evento_indicador ||
	$evento_calendario ||
	$evento_monitoramento ||
	$evento_ata ||
	$evento_swot ||
	$evento_operativo ||
	$evento_instrumento ||
	$evento_recurso ||
	$evento_problema ||
	$evento_demanda ||
	$evento_programa ||
	$evento_licao ||
	$evento_link ||
	$evento_avaliacao ||
	$evento_tgn ||
	$evento_brainstorm ||
	$evento_gut ||
	$evento_causa_efeito ||
	$evento_arquivo ||
	$evento_forum ||
	$evento_checklist ||
	$evento_agenda ||
	$evento_agrupamento ||
	$evento_patrocinador ||
	$evento_template ||
	$evento_painel ||
	$evento_painel_odometro ||
	$evento_painel_composicao	||
	$evento_tr ||
	$evento_me
	){
	$sql->adTabela('cias');
	if ($evento_tarefa) $sql->esqUnir('tarefas','tarefas','tarefas.tarefa_cia=cias.cia_id');
	elseif ($evento_projeto) $sql->esqUnir('projetos','projetos','projetos.projeto_cia=cias.cia_id');
	elseif ($evento_perspectiva) $sql->esqUnir('perspectivas','perspectivas','pg_perspectiva_cia=cias.cia_id');
	elseif ($evento_tema) $sql->esqUnir('tema','tema','tema_cia=cias.cia_id');
	elseif ($evento_objetivo) $sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','pg_objetivo_estrategico_cia=cias.cia_id');
	elseif ($evento_fator) $sql->esqUnir('fatores_criticos','fatores_criticos','pg_fator_critico_cia=cias.cia_id');
	elseif ($evento_estrategia) $sql->esqUnir('estrategias','estrategias','pg_estrategia_cia=cias.cia_id');
	elseif ($evento_meta) $sql->esqUnir('metas','metas','pg_meta_cia=cias.cia_id');
	elseif ($evento_pratica) $sql->esqUnir('praticas','praticas','praticas.pratica_cia=cias.cia_id');
	elseif ($evento_acao) $sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_cia=cias.cia_id');
	elseif ($evento_canvas) $sql->esqUnir('canvas','canvas','canvas_cia=cias.cia_id');
	elseif ($evento_risco) $sql->esqUnir('risco','risco','risco_cia=cias.cia_id');
	elseif ($evento_risco_resposta) $sql->esqUnir('risco_resposta','risco_resposta','risco_resposta_cia=cias.cia_id');
	elseif ($evento_indicador) $sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_cia=cias.cia_id');
	elseif ($evento_calendario) $sql->esqUnir('calendario','calendario','calendario_cia=cias.cia_id');
	elseif ($evento_monitoramento) $sql->esqUnir('monitoramento','monitoramento','monitoramento_cia=cias.cia_id');
	elseif ($evento_ata) $sql->esqUnir('ata','ata','ata_cia=cias.cia_id');
	elseif ($evento_swot) $sql->esqUnir('swot','swot','swot_cia=cias.cia_id');
	elseif ($evento_operativo) $sql->esqUnir('operativo','operativo','operativo_cia=cias.cia_id');
	elseif ($evento_instrumento) $sql->esqUnir('instrumento','instrumento','instrumento_cia=cias.cia_id');
	elseif ($evento_recurso) $sql->esqUnir('recursos','recursos','recurso_cia=cias.cia_id');
	elseif ($evento_problema) $sql->esqUnir('problema','problema','problema_cia=cias.cia_id');
	elseif ($evento_demanda) $sql->esqUnir('demandas','demandas','demanda_cia=cias.cia_id');
	elseif ($evento_programa) $sql->esqUnir('programa','programa','programa_cia=cias.cia_id');
	elseif ($evento_licao) $sql->esqUnir('licao','licao','licao_cia=cias.cia_id');
	elseif ($evento_link) $sql->esqUnir('links','links','link_cia=cias.cia_id');
	elseif ($evento_avaliacao) $sql->esqUnir('avaliacao','avaliacao','avaliacao_cia=cias.cia_id');
	elseif ($evento_tgn) $sql->esqUnir('tgn','tgn','tgn_cia=cias.cia_id');
	elseif ($evento_brainstorm) $sql->esqUnir('brainstorm','brainstorm','brainstorm_cia=cias.cia_id');
	elseif ($evento_gut) $sql->esqUnir('gut','gut','gut_cia=cias.cia_id');
	elseif ($evento_causa_efeito) $sql->esqUnir('causa_efeito','causa_efeito','causa_efeito_cia=cias.cia_id');
	elseif ($evento_arquivo) $sql->esqUnir('arquivos','arquivos','arquivo_cia=cias.cia_id');
	elseif ($evento_forum) $sql->esqUnir('foruns','foruns','forum_cia=cias.cia_id');
	elseif ($evento_checklist) $sql->esqUnir('checklist','checklist','checklist_cia=cias.cia_id');
	elseif ($evento_agenda) $sql->esqUnir('agenda','agenda','agenda_cia=cias.cia_id');
	elseif ($evento_agrupamento) $sql->esqUnir('agrupamento','agrupamento','agrupamento_cia=cias.cia_id');
	elseif ($evento_patrocinador) $sql->esqUnir('patrocinadores','patrocinadores','patrocinador_cia=cias.cia_id');
	elseif ($evento_template) $sql->esqUnir('template','template','template_cia=cias.cia_id');
	elseif ($evento_painel) $sql->esqUnir('painel','painel','painel_cia=cias.cia_id');
	elseif ($evento_painel_odometro) $sql->esqUnir('painel_odometro','painel_odometro','painel_odometro_cia=cias.cia_id');
	elseif ($evento_painel_composicao) $sql->esqUnir('painel_composicao','painel_composicao','painel_composicao_cia=cias.cia_id');
	elseif ($evento_tr) $sql->esqUnir('tr','tr','tr_cia=cias.cia_id');
	elseif ($evento_me) $sql->esqUnir('me','me','me_cia=cias.cia_id');

	if ($evento_tarefa) $sql->adOnde('tarefa_id = '.(int)$evento_tarefa);
	elseif ($evento_projeto) $sql->adOnde('projeto_id = '.(int)$evento_projeto);
	elseif ($evento_perspectiva) $sql->adOnde('pg_perspectiva_id = '.(int)$evento_perspectiva);
	elseif ($evento_tema) $sql->adOnde('tema_id = '.(int)$evento_tema);
	elseif ($evento_objetivo) $sql->adOnde('pg_objetivo_estrategico_id = '.(int)$evento_objetivo);
	elseif ($evento_fator) $sql->adOnde('pg_fator_critico_id = '.(int)$evento_fator);
	elseif ($evento_estrategia) $sql->adOnde('pg_estrategia_id = '.(int)$evento_estrategia);
	elseif ($evento_meta) $sql->adOnde('pg_meta_id = '.(int)$evento_meta);
	elseif ($evento_pratica) $sql->adOnde('pratica_id = '.(int)$evento_pratica);
	elseif ($evento_acao) $sql->adOnde('plano_acao_id = '.(int)$evento_acao);
	elseif ($evento_canvas) $sql->adOnde('canvas_id = '.(int)$evento_canvas);
	elseif ($evento_risco) $sql->adOnde('risco_id = '.(int)$evento_risco);
	elseif ($evento_risco_resposta) $sql->adOnde('risco_resposta_id = '.(int)$evento_risco_resposta);
	elseif ($evento_indicador) $sql->adOnde('pratica_indicador_id = '.(int)$evento_indicador);
	elseif ($evento_calendario) $sql->adOnde('calendario_id = '.(int)$evento_calendario);
	elseif ($evento_monitoramento) $sql->adOnde('monitoramento_id = '.(int)$evento_monitoramento);
	elseif ($evento_ata) $sql->adOnde('ata_id = '.(int)$evento_ata);
	elseif ($evento_swot) $sql->adOnde('swot_id = '.(int)$evento_swot);
	elseif ($evento_operativo) $sql->adOnde('operativo_id = '.(int)$evento_operativo);
	elseif ($evento_instrumento) $sql->adOnde('instrumento_id = '.(int)$evento_instrumento);
	elseif ($evento_recurso) $sql->adOnde('recurso_id = '.(int)$evento_recurso);
	elseif ($evento_problema) $sql->adOnde('problema_id = '.(int)$evento_problema);
	elseif ($evento_demanda) $sql->adOnde('demanda_id = '.(int)$evento_demanda);
	elseif ($evento_programa) $sql->adOnde('programa_id = '.(int)$evento_programa);
	elseif ($evento_licao) $sql->adOnde('licao_id = '.(int)$evento_licao);
	elseif ($evento_link) $sql->adOnde('link_id = '.(int)$evento_link);
	elseif ($evento_avaliacao) $sql->adOnde('avaliacao_id = '.(int)$evento_avaliacao);
	elseif ($evento_tgn) $sql->adOnde('tgn_id = '.(int)$evento_tgn);
	elseif ($evento_brainstorm) $sql->adOnde('brainstorm_id = '.(int)$evento_brainstorm);
	elseif ($evento_gut) $sql->adOnde('gut_id = '.(int)$evento_gut);
	elseif ($evento_causa_efeito) $sql->adOnde('causa_efeito_id = '.(int)$evento_causa_efeito);
	elseif ($evento_arquivo) $sql->adOnde('arquivo_id = '.(int)$evento_arquivo);
	elseif ($evento_forum) $sql->adOnde('forum_id = '.(int)$evento_forum);
	elseif ($evento_checklist) $sql->adOnde('checklist_id = '.(int)$evento_checklist);
	elseif ($evento_agenda) $sql->adOnde('agenda_id = '.(int)$evento_agenda);
	elseif ($evento_agrupamento) $sql->adOnde('agrupamento_id = '.(int)$evento_agrupamento);
	elseif ($evento_patrocinador) $sql->adOnde('patrocinador_id = '.(int)$evento_patrocinador);
	elseif ($evento_template) $sql->adOnde('template_id = '.(int)$evento_template);
	elseif ($evento_painel) $sql->adOnde('painel_id = '.(int)$evento_painel);
	elseif ($evento_painel_odometro) $sql->adOnde('painel_odometro_id = '.(int)$evento_painel_odometro);
	elseif ($evento_painel_composicao) $sql->adOnde('painel_composicao_id = '.(int)$evento_painel_composicao);
	elseif ($evento_tr) $sql->adOnde('tr_id = '.(int)$evento_tr);
	elseif ($evento_me) $sql->adOnde('me_id = '.(int)$evento_me);
	$sql->adCampo('cia_id');
	$cia_id = $sql->Resultado();
	$sql->limpar();
	}


$botoesTitulo = new CBlocoTitulo(($evento_id ? 'Editar Evento' : 'Adicionar Evento'), 'calendario.png', $m, $m.'.'.$a);
if (!$dialogo) $botoesTitulo->adicionaBotao('m=calendario', 'visão mensal','','Visão Mensal','Visualizar o mês inteiro.');
if ($evento_id && !$dialogo) $botoesTitulo->adicionaBotao('m=calendario&a=ver&evento_id='.$evento_id, 'ver','','Ver Evento','Visualizar o evento.');
if ($evento_id) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, '','Excluir Evento','Excluir o evento.');
$botoesTitulo->mostrar();


$df = '%d/%m/%Y';
if ($evento_id) {
	$data_inicio = intval($obj->evento_inicio) ? new CData($obj->evento_inicio) :  new CData(date("Y-m-d H:i:s"));
	$data_fim = intval($obj->evento_fim) ? new CData($obj->evento_fim) : $data_inicio;
	}
else {
	$data_inicio = new CData($data);
	$expediente_inicio=explode(':',$config['expediente_inicio']);
	$min=0;
	if (isset($expediente_inicio[1])){
		for ($min=0; $min< $expediente_inicio[1]; $min+=1);
		if ($min > $expediente_inicio[1])$min-=1;
		}
	$data_inicio->setTime((int)$expediente_inicio[0], $min, 0);

	$data_fim = new CData($data);
	$expediente_fim=explode(':',$config['expediente_fim']);
	$min=0;
	if (isset($expediente_fim[1])){
		for ($min=0; $min< $expediente_fim[1]; $min+=1);
		if ($min > $expediente_fim[1])$min-=1;
		}
	$data_fim->setTime((int)$expediente_fim[0], $min, 0);
	}


$inc = 1;
if (!$evento_id && !$eh_conflito) {
	$seldata = new CData($data);
	if ($data == date('Ymd')) {
		$h = date('H');
		$minuto = intval(date('i') / $inc) + 1;
		$minuto *= $inc;
		if ($minuto > 60) {
			$minuto = 0;
			$h++;
			}
		}
	if (isset($h)&& $h && $h < config('cal_dia_fim')) {
		$seldata->setTime($h, $minuto, 0);
		$obj->evento_inicio = $seldata->format(FMT_TIMESTAMP);
		$seldata->adSegundos($inc * 60);
		$obj->evento_fim = $seldata->format(FMT_TIMESTAMP);
		}
	else {
		$seldata->setTime(config('cal_dia_inicio'), 0, 0);
		$obj->evento_inicio = $seldata->format(FMT_TIMESTAMP);
		$seldata->setTime(config('cal_dia_fim'), 0, 0);
		$obj->evento_fim = $seldata->format(FMT_TIMESTAMP);
		}
	}
$recorrencia = array(0 =>'Nunca', 2=> 'Diaria', 3=>'Semanalmente', 4=>'Quinzenal', 5=>'Mensal', 9=>'Bimestral', 10=>'Trimestral', 6=>'Quadrimestral', 7=>'Semestral', 8=>'Anual');
$lembrar = array(''=>'', '900' => '15 mins', '1800' => '30 mins', '3600' => '1 hora', '7200' => '2 horas', '14400' => '4 horas', '28800' => '8 horas', '56600' => '16 horas', '86400' => '1 dia', '172800' => '2 dias');
$horas = array();
$t = new CData();
$t->setTime(0, 0, 0);
for ($minutos = 0; $minutos < ((24 * 60) / $inc); $minutos++) {
	$horas[$t->format('%H:%M:%S')] = $t->format($Aplic->getPref('formatohora'));
	$t->adSegundos($inc * 60);
	}


echo '<form name="frmExcluir" method="post">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_evento_aed" />';
echo '<input type="hidden" name="del" value="1" />';
echo '<input type="hidden" name="evento_id" id="evento_id" value="'.$evento_id.'" />';


//vindo do adicionar evento no plano de comunicacoes
echo '<input type="hidden" name="projeto_comunicacao_evento_id" value="'.$projeto_comunicacao_evento_id.'" />';

echo '</form>';

$depts_selecionados=array();
$cias_selecionadas = array();
if ($evento_id) {
	$sql->adTabela('evento_depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('evento_id ='.(int)$evento_id);
	$depts_selecionados = $sql->carregarColuna();
	$sql->limpar();

	if ($Aplic->profissional){
		$sql->adTabela('evento_cia');
		$sql->adCampo('evento_cia_cia');
		$sql->adOnde('evento_cia_evento = '.(int)$evento_id);
		$cias_selecionadas = $sql->carregarColuna();
		$sql->limpar();
		}
	}



echo '<form name="env" method="post" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="calendario" />';
echo '<input name="a" type="hidden" value="vazio" />';
echo '<input name="u" type="hidden" value="" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_evento_aed" />';
echo '<input type="hidden" name="evento_id" value="'.$evento_id.'" />';
echo '<input type="hidden" name="uuid" id="uuid" value="'.($evento_id ? null : uuid()).'" />';
echo '<input type="hidden" name="profissional" id="profissional" value="'.($Aplic->profissional ? 1 : 0).'" />';
echo '<input type="hidden" name="evento_designado" value="" />';
echo '<input type="hidden" name="evento_designado_porcentagem" value="" />';
echo '<input type="hidden" name="evento_inicio_antigo" value="'.$obj->evento_inicio.'" />';
echo '<input type="hidden" name="evento_fim_antigo" value="'.$obj->evento_fim.'" />';
echo '<input type="hidden" name="evento_recorrencia_pai" value="'.$obj->evento_recorrencia_pai.'" />';
//vindo do adicionar evento no plano de comunicacoes
echo '<input type="hidden" name="projeto_comunicacao_evento_id" value="'.$projeto_comunicacao_evento_id.'" />';
echo '<input name="evento_depts" type="hidden" value="'.implode(',', $depts_selecionados).'" />';
echo '<input name="evento_cias"  id="evento_cias" type="hidden" value="'.implode(',', $cias_selecionadas).'" />';

echo estiloTopoCaixa();
echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('Nome do Evento', 'Qual o nome do evento.Cada evento deve ter um nome que facilite a compreensão do mesmo').'Nome do Evento:'.dicaF().'</td><td><input type="text" class="texto" style="width:400px;" name="evento_titulo" value="'.$obj->evento_titulo.'" maxlength="255" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo', 'Qual o tipo de evento.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'evento_tipo', 'style="width:400px;" size="1" class="texto"', $obj->evento_tipo).'</td></tr>';
echo '<tr><td align=right nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Responsável', 'Selecione '.$config['genero_organizacao'].' '.$config['organizacao'].' do evento.').ucfirst($config['organizacao']).' responsável:'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'evento_cia', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om();"').'</div></td></tr>';
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
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacoes']).' Envolvid'.$config['genero_organizacao'].'s', 'Quais '.strtolower($config['organizacoes']).' estão envolvid'.$config['genero_organizacao'].'.').ucfirst($config['organizacoes']).' envolvid'.$config['genero_organizacao'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:386px;"><div id="combo_cias">'.$saida_cias.'</div></td><td>'.botao_icone('organizacao_p.gif','Selecionar', 'selecionar '.$config['organizacoes'],'popCias()').'</td></tr></table></td></tr>';
	}
if ($Aplic->profissional) {
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Responsável', 'Escolha pressionando o ícone à direita qual '.$config['genero_dept'].' '.$config['dept'].' responsável por este evento.').ucfirst($config['departamento']).' responsável:'.dicaF().'</td><td><input type="hidden" name="evento_dept" id="evento_dept" value="'.($evento_id ? $obj->evento_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept)).'" /><input type="text" class="texto" name="dept_nome" id="dept_nome" value="'.nome_dept(($evento_id ? $obj->evento_dept : ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : $Aplic->usuario_dept))).'" style="width:384px;" READONLY />'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamento'],'popDept()').'</td></tr>';
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
	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].' com este evento.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td style="width:386px;"><div id="combo_depts">'.$saida_depts.'</div></td><td>'.botao_icone('secoes_p.gif','Selecionar', 'selecionar '.$config['departamentos'],'popDepts()').'</td></tr></table></td></tr>';
	}

echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'Todo evento deve ter um responsável.').'Responsável:'.dicaF().'</td><td><input type="hidden" id="evento_dono" name="evento_dono" value="'.($obj->evento_dono ? $obj->evento_dono : $Aplic->usuario_id).'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_om(($obj->evento_dono ? $obj->evento_dono : $Aplic->usuario_id),$Aplic->getPref('om_usuario')).'" style="width:384px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popGerente();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data de início do evento.').'Data de início:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="evento_inicio" id="evento_inicio" value="'.($data_inicio ? $data_inicio->format('%Y-%m-%d') : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de início deste evento.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora de Início', 'Digite a hora de início do evento.').'Hora:'.dicaF().selecionaVetor($horas, 'inicio_hora', 'size="1" class="texto" onchange="CompararHoras();"', $data_inicio->format('%H:%M:%S')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Término', 'Digite ou escolha no calendário a data de término do evento.').'Data de Término:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="evento_fim" id="evento_fim" value="'.($data_fim ? $data_fim->format('%Y-%m-%d') : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término deste evento.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário"" border=0 /></a>'.dicaF().dica('Hora de Término', 'Digite a hora de término do evento.').'Hora:'.dicaF().selecionaVetor($horas, 'fim_hora', 'size="1" class="texto" onchange="CompararHoras();"', $data_fim->format('%H:%M:%S')).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Recorrência', 'De quanto em quanto tempo este evento se repete.').'Recorrência:'.dicaF().'</td><td>'.selecionaVetor($recorrencia, 'evento_recorrencias', 'size="1" class="texto"', $obj->evento_recorrencias).dica('Número de Recorrencias', 'Escolha o número de vezes que a faixa de tempo escolhida repetirá.').'x'.dicaF().'<input type="text" class="texto" name="evento_nr_recorrencias" value="'.((isset($obj->evento_nr_recorrencias)) ? ($obj->evento_nr_recorrencias) : '1').'" maxlength="2" size="3" />'.dica('Número de Recorrencias', 'Escolha o número de vezes que a faixa de tempo escolhida repetirá.').'vezes'.dicaF().'</td></tr>';

if (!$Aplic->profissional){
	if ($obj->evento_projeto) $evento_projeto=$obj->evento_projeto;
	if ($obj->evento_tarefa) $evento_tarefa=$obj->evento_tarefa;
	elseif ($obj->evento_fator) $evento_fator=$obj->evento_fator;
	elseif ($obj->evento_indicador) $evento_indicador=$obj->evento_indicador;
	elseif ($obj->evento_estrategia) $evento_estrategia=$obj->evento_estrategia;
	elseif ($obj->evento_meta) $evento_meta=$obj->evento_meta;
	elseif ($obj->evento_objetivo) $evento_objetivo=$obj->evento_objetivo;
	elseif ($obj->evento_perspectiva) $evento_perspectiva=$obj->evento_perspectiva;
	elseif ($obj->evento_acao) $evento_acao=$obj->evento_acao;
	elseif ($obj->evento_pratica) $evento_pratica=$obj->evento_fator;
	elseif ($obj->evento_tema) $evento_tema=$obj->evento_tema;
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
	'indicador' => 'Indicador',
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

if ($evento_projeto) $tipo='projeto';
elseif ($evento_pratica) $tipo='pratica';
elseif ($evento_acao) $tipo='acao';
elseif ($evento_objetivo) $tipo='objetivo';
elseif ($evento_tema) $tipo='tema';
elseif ($evento_fator) $tipo='fator';
elseif ($evento_estrategia) $tipo='estrategia';
elseif ($evento_perspectiva) $tipo='perspectiva';
elseif ($evento_canvas) $tipo='canvas';
elseif ($evento_risco) $tipo='risco';
elseif ($evento_risco_resposta) $tipo='risco_resposta';
elseif ($evento_meta) $tipo='meta';
elseif ($evento_indicador) $tipo='evento_indicador';
elseif ($evento_swot) $tipo='swot';
elseif ($evento_ata) $tipo='ata';
elseif ($evento_monitoramento) $tipo='monitoramento';
elseif ($evento_calendario) $tipo='calendario';
elseif ($evento_operativo) $tipo='operativo';
elseif ($evento_instrumento) $tipo='instrumento';
elseif ($evento_recurso) $tipo='recurso';
elseif ($evento_problema) $tipo='problema';
elseif ($evento_demanda) $tipo='demanda';
elseif ($evento_programa) $tipo='programa';
elseif ($evento_licao) $tipo='licao';
elseif ($evento_link) $tipo='link';
elseif ($evento_avaliacao) $tipo='avaliacao';
elseif ($evento_tgn) $tipo='tgn';
elseif ($evento_brainstorm) $tipo='brainstorm';
elseif ($evento_gut) $tipo='gut';
elseif ($evento_causa_efeito) $tipo='causa_efeito';
elseif ($evento_arquivo) $tipo='arquivo';
elseif ($evento_forum) $tipo='forum';
elseif ($evento_checklist) $tipo='checklist';
elseif ($evento_agenda) $tipo='agenda';
elseif ($evento_agrupamento) $tipo='agrupamento';
elseif ($evento_patrocinador) $tipo='patrocinador';
elseif ($evento_template) $tipo='template';
elseif ($evento_painel) $tipo='painel';
elseif ($evento_painel_odometro) $tipo='painel_odometro';
elseif ($evento_painel_composicao) $tipo='painel_composicao';
elseif ($evento_tr) $tipo='tr';
elseif ($evento_me) $tipo='me';
else $tipo='';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Relacionado','A qual parte do sistema o evento está relacionado.').'Relacionado:'.dicaF().'</td><td align="left">'.selecionaVetor($tipos, 'tipo_relacao', 'style="width:250px;" class="texto" onchange="mostrar()"', $tipo).'<td></tr>';
echo '<tr '.($evento_projeto || $evento_tarefa ? '' : 'style="display:none"').' id="projeto" align="right"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['projeto']).' Relacionad'.$config['genero_projeto'], 'Caso o evento seja específico de um'.($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto'].', neste campo deverá constar o nome d'.$config['genero_projeto'].' '.$config['projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_projeto" value="'.$evento_projeto.'" /><input type="text" id="projeto_nome" name="projeto_nome" value="'.nome_projeto($evento_projeto).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a>'.($Aplic->profissional ? '<a href="javascript: void(0);" onclick="incluir_relacionado();">'.imagem('icones/adicionar.png','Adicionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar '.$config['genero_projeto'].' '.$config['projeto'].' escolhid'.$config['genero_projeto'].'.').'</a>' : '').'</td></tr></table></td></tr>';
echo '<tr '.($evento_projeto || $evento_tarefa ? '' : 'style="display:none"').' id="tarefa"><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tarefa']).' Relacionad'.$config['genero_tarefa'], 'Caso o evento seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').ucfirst($config['tarefa']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tarefa" value="'.$evento_tarefa.'" /><input type="text" id="tarefa_nome" name="tarefa_nome" value="'.nome_tarefa($evento_tarefa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTarefa();">'.imagem('icones/tarefa_p.gif','Selecionar '.ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' escolher à qual '.$config['tarefa'].' o arquivo irá pertencer.<br><br>Caso não escolha um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', o arquivo será d'.$config['genero_projeto'].' '.$config['projeto'].' tod'.$config['genero_projeto'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_pratica ? '' : 'style="display:none"').' id="pratica" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['pratica']).' Relacionad'.$config['genero_pratica'], 'Caso o evento seja específico de '.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].', neste campo deverá constar o nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_pratica" value="'.$evento_pratica.'" /><input type="text" id="pratica_nome" name="pratica_nome" value="'.nome_pratica($evento_pratica).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPratica();">'.imagem('icones/pratica_p.gif','Selecionar '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/pratica_p.gif').' para selecionar um'.($config['genero_pratica']=='a' ? 'a' : '').' '.$config['pratica'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_acao ? '' : 'style="display:none"').' id="acao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['acao']).' Relacionad'.$config['genero_acao'], 'Caso o evento seja específico de '.($config['genero_acao']=='o' ? 'um' : 'uma').' '.$config['acao'].', neste campo deverá constar o nome d'.$config['genero_acao'].' '.$config['acao'].'.').ucfirst($config['acao']).':'.dicaF().'</td><td align="left" valign="top" nowrap="nowrap"><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_acao" value="'.$evento_acao.'" /><input type="text" id="acao_nome" name="acao_nome" value="'.nome_acao($evento_acao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAcao();">'.imagem('icones/plano_acao_p.gif','Selecionar Ação','Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para selecionar um plano de ação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_perspectiva ? '' : 'style="display:none"').' id="perspectiva" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['perspectiva']).' Relacionad'.$config['genero_perspectiva'], 'Caso o evento seja específico de '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].', neste campo deverá constar o nome d'.$config['genero_perspectiva'].' '.$config['perspectiva'].'.').ucfirst($config['perspectiva']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_perspectiva" value="'.$evento_perspectiva.'" /><input type="text" id="perspectiva_nome" name="perspectiva_nome" value="'.nome_perspectiva($evento_perspectiva).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPerspectiva();">'.imagem('icones/perspectiva_p.png','Selecionar '.ucfirst($config['perspectiva']),'Clique neste ícone '.imagem('icones/perspectiva_p.png').' para selecionar '.($config['genero_perspectiva']=='o' ? 'um' : 'uma').' '.$config['perspectiva'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_tema ? '' : 'style="display:none"').' id="tema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tema']).' Relacionad'.$config['genero_tema'], 'Caso o evento seja específico de '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].', neste campo deverá constar o nome d'.$config['genero_tema'].' '.$config['tema'].'.').ucfirst($config['tema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tema" value="'.$evento_tema.'" /><input type="text" id="tema_nome" name="tema_nome" value="'.nome_tema($evento_tema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Selecionar '.ucfirst($config['tema']),'Clique neste ícone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_objetivo ? '' : 'style="display:none"').' id="objetivo" ><td align="right" nowrap="nowrap">'.dica(''.ucfirst($config['objetivo']).' Relacionad'.$config['genero_objetivo'], 'Caso o evento seja específico de '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].', neste campo deverá constar o nome d'.$config['genero_objetivo'].' '.$config['objetivo'].'.').ucfirst($config['objetivo']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_objetivo" value="'.$evento_objetivo.'" /><input type="text" id="objetivo_nome" name="objetivo_nome" value="'.nome_objetivo($evento_objetivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popObjetivo();">'.imagem('icones/obj_estrategicos_p.gif','Selecionar '.ucfirst($config['objetivo']).'','Clique neste ícone '.imagem('icones/obj_estrategicos_p.gif').' para selecionar '.($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_estrategia ? '' : 'style="display:none"').' id="estrategia" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['iniciativa']).' Relacionad'.$config['genero_iniciativa'], 'Caso o evento seja específico de '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].', neste campo deverá constar o nome d'.$config['genero_iniciativa'].' '.$config['iniciativa'].'.').ucfirst($config['iniciativa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_estrategia" value="'.$evento_estrategia.'" /><input type="text" id="estrategia_nome" name="estrategia_nome" value="'.nome_estrategia($evento_estrategia).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste ícone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_fator ? '' : 'style="display:none"').' id="fator" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['fator']).' Relacionad'.$config['genero_fator'], 'Caso o evento seja específico de '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].', neste campo deverá constar o nome d'.$config['genero_fator'].' '.$config['fator'].'.').ucfirst($config['fator']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_fator" value="'.$evento_fator.'" /><input type="text" id="fator_nome" name="fator_nome" value="'.nome_fator($evento_fator).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popFator();">'.imagem('icones/fator_p.gif','Selecionar '.ucfirst($config['fator']),'Clique neste ícone '.imagem('icones/fator_p.gif').' para selecionar '.($config['genero_fator']=='o' ? 'um' : 'uma').' '.$config['fator'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_meta ? '' : 'style="display:none"').' id="meta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['meta']), 'Caso o evento seja específico de '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].', neste campo deverá constar o nome d'.$config['genero_meta'].' '.$config['meta'].'.').ucfirst($config['meta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_meta" value="'.$evento_meta.'" /><input type="text" id="meta_nome" name="meta_nome" value="'.nome_meta($evento_meta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_indicador ? '' : 'style="display:none"').' id="indicador" ><td align="right" nowrap="nowrap">'.dica('Indicador', 'Caso o evento seja específico de um indicador, neste campo deverá constar o nome do indicador.').'Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_indicador" value="'.$evento_indicador.'" /><input type="text" id="indicador_nome" name="indicador_nome" value="'.nome_indicador($evento_indicador).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popIndicador();">'.imagem('icones/indicador_p.gif','Selecionar Indicador','Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar um indicador.').'</a></td></tr></table></td></tr>';

if ($agrupamento_ativo) echo '<tr '.($evento_agrupamento ? '' : 'style="display:none"').' id="agrupamento" ><td align="right" nowrap="nowrap">'.dica('Agrupamento', 'Caso o evento seja específico de um agrupamento, neste campo deverá constar o nome do agrupamento.').'Agrupamento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_agrupamento" value="'.$evento_agrupamento.'" /><input type="text" id="agrupamento_nome" name="agrupamento_nome" value="'.nome_agrupamento($evento_agrupamento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgrupamento();">'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png','Selecionar agrupamento','Clique neste ícone '.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').' para selecionar um agrupamento.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="evento_agrupamento" value="" id="agrupamento" /><input type="hidden" id="agrupamento_nome" name="agrupamento_nome" value="">';

if ($patrocinador_ativo) echo '<tr '.($evento_patrocinador ? '' : 'style="display:none"').' id="patrocinador" ><td align="right" nowrap="nowrap">'.dica('Patrocinador', 'Caso o evento seja específico de um patrocinador, neste campo deverá constar o nome do patrocinador.').'Patrocinador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_patrocinador" value="'.$evento_patrocinador.'" /><input type="text" id="patrocinador_nome" name="patrocinador_nome" value="'.nome_patrocinador($evento_patrocinador).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPatrocinador();">'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif','Selecionar patrocinador','Clique neste ícone '.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').' para selecionar um patrocinador.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="evento_patrocinador" value="" id="patrocinador" /><input type="hidden" id="patrocinador_nome" name="patrocinador_nome" value="">';


echo '<tr '.($evento_calendario ? '' : 'style="display:none"').' id="calendario" ><td align="right" nowrap="nowrap">'.dica('Agenda', 'Caso o evento seja específico de uma agenda, neste campo deverá constar o nome da agenda.').'Agenda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_calendario" value="'.$evento_calendario.'" /><input type="text" id="calendario_nome" name="calendario_nome" value="'.nome_calendario($evento_calendario).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCalendario();">'.imagem('icones/calendario_p.png','Selecionar calendario','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um calendario.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_instrumento ? '' : 'style="display:none"').' id="instrumento" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['instrumento']), 'Caso o evento seja específico de '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].', neste campo deverá constar o nome d'.$config['genero_instrumento'].' '.$config['instrumento'].'.').ucfirst($config['instrumento']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_instrumento" value="'.$evento_instrumento.'" /><input type="text" id="instrumento_nome" name="instrumento_nome" value="'.nome_instrumento($evento_instrumento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popInstrumento();">'.imagem('icones/instrumento_p.png','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/instrumento_p.png').' para selecionar '.($config['genero_instrumento']=='o' ? 'um' : 'uma').' '.$config['instrumento'].'.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_recurso ? '' : 'style="display:none"').' id="recurso" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['recurso']), 'Caso o evento seja específico de '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].', neste campo deverá constar o nome d'.$config['genero_recurso'].' '.$config['recurso'].'.').ucfirst($config['recurso']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_recurso" value="'.$evento_recurso.'" /><input type="text" id="recurso_nome" name="recurso_nome" value="'.nome_recurso($evento_recurso).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRecurso();">'.imagem('icones/recursos_p.gif','Selecionar '.ucfirst($config['instrumento']),'Clique neste ícone '.imagem('icones/recursos_p.gif').' para selecionar '.($config['genero_recurso']=='o' ? 'um' : 'uma').' '.$config['recurso'].'.').'</a></td></tr></table></td></tr>';
if ($problema_ativo) echo '<tr '.($evento_problema ? '' : 'style="display:none"').' id="problema" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['problema']), 'Caso o evento seja específico de '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].', neste campo deverá constar o nome d'.$config['genero_problema'].' '.$config['problema'].'.').ucfirst($config['problema']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_problema" value="'.$evento_problema.'" /><input type="text" id="problema_nome" name="problema_nome" value="'.nome_problema($evento_problema).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProblema();">'.imagem('icones/problema_p.png','Selecionar '.ucfirst($config['problema']),'Clique neste ícone '.imagem('icones/problema_p.png').' para selecionar '.($config['genero_problema']=='o' ? 'um' : 'uma').' '.$config['problema'].'.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="evento_problema" value="" id="problema" /><input type="hidden" id="problema_nome" name="problema_nome" value="">';
echo '<tr '.($evento_demanda ? '' : 'style="display:none"').' id="demanda" ><td align="right" nowrap="nowrap">'.dica('Demanda', 'Caso o evento seja específico de uma demanda, neste campo deverá constar o nome da demanda.').'Demanda:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_demanda" value="'.$evento_demanda.'" /><input type="text" id="demanda_nome" name="demanda_nome" value="'.nome_demanda($evento_demanda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popDemanda();">'.imagem('icones/demanda_p.gif','Selecionar demanda','Clique neste ícone '.imagem('icones/demanda_p.gif').' para selecionar um demanda.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_licao ? '' : 'style="display:none"').' id="licao" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['licao']), 'Caso o evento seja específico de uma lição aprendida, neste campo deverá constar o nome da lição aprendida.').'Lição Aprendida:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_licao" value="'.$evento_licao.'" /><input type="text" id="licao_nome" name="licao_nome" value="'.nome_licao($evento_licao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLicao();">'.imagem('icones/licoes_p.gif','Selecionar Lição Aprendida','Clique neste ícone '.imagem('icones/licoes_p.gif').' para selecionar uma lição aprendida.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_link ? '' : 'style="display:none"').' id="link" ><td align="right" nowrap="nowrap">'.dica('link', 'Caso o evento seja específico de um link, neste campo deverá constar o nome do link.').'link:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_link" value="'.$evento_link.'" /><input type="text" id="link_nome" name="link_nome" value="'.nome_link($evento_link).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popLink();">'.imagem('icones/links_p.gif','Selecionar link','Clique neste ícone '.imagem('icones/links_p.gif').' para selecionar um link.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_avaliacao ? '' : 'style="display:none"').' id="avaliacao" ><td align="right" nowrap="nowrap">'.dica('Avaliação', 'Caso o evento seja específico de uma avaliação, neste campo deverá constar o nome da avaliação.').'Avaliação:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_avaliacao" value="'.$evento_avaliacao.'" /><input type="text" id="avaliacao_nome" name="avaliacao_nome" value="'.nome_avaliacao($evento_avaliacao).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAvaliacao();">'.imagem('icones/avaliacao_p.gif','Selecionar Avaliação','Clique neste ícone '.imagem('icones/avaliacao_p.gif').' para selecionar uma avaliação.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_brainstorm ? '' : 'style="display:none"').' id="brainstorm" ><td align="right" nowrap="nowrap">'.dica('Brainstorm', 'Caso o evento seja específico de um brainstorm, neste campo deverá constar o nome do brainstorm.').'Brainstorm:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_brainstorm" value="'.$evento_brainstorm.'" /><input type="text" id="brainstorm_nome" name="brainstorm_nome" value="'.nome_brainstorm($evento_brainstorm).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popBrainstorm();">'.imagem('icones/brainstorm_p.gif','Selecionar Brainstorm','Clique neste ícone '.imagem('icones/brainstorm_p.gif').' para selecionar um brainstorm.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_gut ? '' : 'style="display:none"').' id="gut" ><td align="right" nowrap="nowrap">'.dica('Matriz G.U.T.', 'Caso o evento seja específico de uma matriz G.U.T., neste campo deverá constar o nome da matriz G.U.T..').'Matriz G.U.T.:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_gut" value="'.$evento_gut.'" /><input type="text" id="gut_nome" name="gut_nome" value="'.nome_gut($evento_gut).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popGut();">'.imagem('icones/gut_p.gif','Selecionar Matriz G.U.T.','Clique neste ícone '.imagem('icones/gut_p.gif').' para selecionar um gut.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_causa_efeito ? '' : 'style="display:none"').' id="causa_efeito" ><td align="right" nowrap="nowrap">'.dica('Diagrama de Cusa-Efeito', 'Caso o evento seja específico de um diagrama de causa-efeito, neste campo deverá constar o nome do diagrama de causa-efeito.').'Diagrama de Cusa-Efeito:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_causa_efeito" value="'.$evento_causa_efeito.'" /><input type="text" id="causa_efeito_nome" name="causa_efeito_nome" value="'.nome_causa_efeito($evento_causa_efeito).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCausa_efeito();">'.imagem('icones/causaefeito_p.png','Selecionar Diagrama de Cusa-Efeito','Clique neste ícone '.imagem('icones/causaefeito_p.png').' para selecionar um diagrama de causa-efeito.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_arquivo ? '' : 'style="display:none"').' id="arquivo" ><td align="right" nowrap="nowrap">'.dica('Arquivo', 'Caso o evento seja específico de um arquivo, neste campo deverá constar o nome do arquivo.').'Arquivo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_arquivo" value="'.$evento_arquivo.'" /><input type="text" id="arquivo_nome" name="arquivo_nome" value="'.nome_arquivo($evento_arquivo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popArquivo();">'.imagem('icones/arquivo_p.png','Selecionar Arquivo','Clique neste ícone '.imagem('icones/arquivo_p.png').' para selecionar um arquivo.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_forum ? '' : 'style="display:none"').' id="forum" ><td align="right" nowrap="nowrap">'.dica('Fórum', 'Caso o evento seja específico de um fórum, neste campo deverá constar o nome do fórum.').'Fórum:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_forum" value="'.$evento_forum.'" /><input type="text" id="forum_nome" name="forum_nome" value="'.nome_forum($evento_forum).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popForum();">'.imagem('icones/forum_p.gif','Selecionar Fórum','Clique neste ícone '.imagem('icones/forum_p.gif').' para selecionar um fórum.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_checklist ? '' : 'style="display:none"').' id="checklist" ><td align="right" nowrap="nowrap">'.dica('Checklist', 'Caso o evento seja específico de um checklist, neste campo deverá constar o nome do checklist.').'checklist:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_checklist" value="'.$evento_checklist.'" /><input type="text" id="checklist_nome" name="checklist_nome" value="'.nome_checklist($evento_checklist).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popChecklist();">'.imagem('icones/todo_list_p.png','Selecionar Checklist','Clique neste ícone '.imagem('icones/todo_list_p.png').' para selecionar um checklist.').'</a></td></tr></table></td></tr>';
echo '<tr '.($evento_agenda ? '' : 'style="display:none"').' id="agenda" ><td align="right" nowrap="nowrap">'.dica('Compromisso', 'Caso o evento seja específico de um compromisso, neste campo deverá constar o nome do compromisso.').'Compromisso:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_agenda" value="'.$evento_agenda.'" /><input type="text" id="agenda_nome" name="agenda_nome" value="'.nome_agenda($evento_agenda).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAgenda();">'.imagem('icones/calendario_p.png','Selecionar Compromisso','Clique neste ícone '.imagem('icones/calendario_p.png').' para selecionar um compromisso.').'</a></td></tr></table></td></tr>';
if (!$Aplic->profissional) {
	echo '<input type="hidden" name="evento_template" value="" id="template" /><input type="hidden" id="template_nome" name="template_nome" value="">';
	echo '<input type="hidden" name="evento_programa" value="" id="programa" /><input type="hidden" id="programa_nome" name="programa_nome" value="">';
	echo '<input type="hidden" name="evento_tgn" value="" id="tgn" /><input type="hidden" id="tgn_nome" name="tgn_nome" value="">';
	echo '<input type="hidden" name="evento_monitoramento" value="" id="monitoramento" /><input type="hidden" id="monitoramento_nome" name="monitoramento_nome" value="">';
	echo '<input type="hidden" name="evento_canvas" value="" id="canvas" /><input type="hidden" id="canvas_nome" name="canvas_nome" value="">';
	echo '<input type="hidden" name="evento_risco" value="" id="risco" /><input type="hidden" id="risco_nome" name="risco_nome" value="">';
	echo '<input type="hidden" name="evento_risco_resposta" value="" id="risco_resposta" /><input type="hidden" id="risco_resposta_nome" name="risco_resposta_nome" value="">';
	echo '<input type="hidden" name="evento_painel" value="" id="painel" /><input type="hidden" id="painel_nome" name="painel_nome" value="">';
	echo '<input type="hidden" name="evento_painel_odometro" value="" id="painel_odometro" /><input type="hidden" id="painel_odometro_nome" name="painel_odometro_nome" value="">';
	echo '<input type="hidden" name="evento_painel_composicao" value="" id="painel_composicao" /><input type="hidden" id="painel_composicao_nome" name="painel_composicao_nome" value="">';
	echo '<input type="hidden" name="evento_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';
	echo '<input type="hidden" name="evento_tr" value="" id="tr" /><input type="hidden" id="tr_nome" name="tr_nome" value="">';
	}
else {
	echo '<tr '.($evento_template ? '' : 'style="display:none"').' id="template" ><td align="right" nowrap="nowrap">'.dica('Modelo', 'Caso o evento seja específico de um modelo, neste campo deverá constar o nome do modelo.').'Modelo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_template" value="'.$evento_template.'" /><input type="text" id="template_nome" name="template_nome" value="'.nome_template($evento_template).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTemplate();">'.imagem('icones/template_p.gif','Selecionar template','Clique neste ícone '.imagem('icones/template_p.gif').' para selecionar um template.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_programa ? '' : 'style="display:none"').' id="programa" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['programa']), 'Caso o evento seja específico de '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].', neste campo deverá constar o nome d'.$config['genero_programa'].' '.$config['programa'].'.').ucfirst($config['programa']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_programa" value="'.$evento_programa.'" /><input type="text" id="programa_nome" name="programa_nome" value="'.nome_programa($evento_programa).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPrograma();">'.imagem('icones/programa_p.png','Selecionar '.ucfirst($config['programa']),'Clique neste ícone '.imagem('icones/programa_p.png').' para selecionar '.($config['genero_programa']=='o' ? 'um' : 'uma').' '.$config['programa'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_tgn ? '' : 'style="display:none"').' id="tgn" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tgn']), 'Caso o evento seja específico de '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].', neste campo deverá constar o nome d'.$config['genero_tgn'].' '.$config['tgn'].'.').ucfirst($config['tgn']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tgn" value="'.$evento_tgn.'" /><input type="text" id="tgn_nome" name="tgn_nome" value="'.nome_tgn($evento_tgn).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTgn();">'.imagem('icones/tgn_p.png','Selecionar '.ucfirst($config['tgn']),'Clique neste ícone '.imagem('icones/tgn_p.png').' para selecionar '.($config['genero_tgn']=='o' ? 'um' : 'uma').' '.$config['tgn'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_monitoramento ? '' : 'style="display:none"').' id="monitoramento" ><td align="right" nowrap="nowrap">'.dica('Monitoramento', 'Caso o evento seja específico de um monitoramento, neste campo deverá constar o nome do monitoramento.').'Monitoramento:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_monitoramento" value="'.$evento_monitoramento.'" /><input type="text" id="monitoramento_nome" name="monitoramento_nome" value="'.nome_monitoramento($evento_monitoramento).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMonitoramento();">'.imagem('icones/monitoramento_p.gif','Selecionar monitoramento','Clique neste ícone '.imagem('icones/monitoramento_p.gif').' para selecionar um monitoramento.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_risco ? '' : 'style="display:none"').' id="risco" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco']).' Relacionad'.$config['genero_risco'], 'Caso o evento seja específico de '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].', neste campo deverá constar o nome d'.$config['genero_risco'].' '.$config['risco'].'.').ucfirst($config['risco']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_risco" value="'.$evento_risco.'" /><input type="text" id="risco_nome" name="risco_nome" value="'.nome_risco($evento_risco).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRisco();">'.imagem('icones/risco_p.png','Selecionar '.ucfirst($config['risco']),'Clique neste ícone '.imagem('icones/risco_p.png').' para selecionar '.($config['genero_risco']=='o' ? 'um' : 'uma').' '.$config['risco'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_risco_resposta ? '' : 'style="display:none"').' id="risco_resposta" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['risco_resposta']).' Relacionad'.$config['genero_risco_resposta'], 'Caso o evento seja específico de '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].', neste campo deverá constar o nome d'.$config['genero_risco_resposta'].' '.$config['risco_resposta'].'.').ucfirst($config['risco_resposta']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_risco_resposta" value="'.$evento_risco_resposta.'" /><input type="text" id="risco_resposta_nome" name="risco_resposta_nome" value="'.nome_risco_resposta($evento_risco_resposta).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popRiscoResposta();">'.imagem('icones/risco_resposta_p.png','Selecionar '.ucfirst($config['risco_resposta']),'Clique neste ícone '.imagem('icones/risco_resposta_p.png').' para selecionar '.($config['genero_risco_resposta']=='o' ? 'um' : 'uma').' '.$config['risco_resposta'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_canvas ? '' : 'style="display:none"').' id="canvas" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['canvas']).' Relacionad'.$config['genero_canvas'], 'Caso o evento seja específico de '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].', neste campo deverá constar o nome d'.$config['genero_canvas'].' '.$config['canvas'].'.').ucfirst($config['canvas']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_canvas" value="'.$evento_canvas.'" /><input type="text" id="canvas_nome" name="canvas_nome" value="'.nome_canvas($evento_canvas).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popCanvas();">'.imagem('icones/canvas_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/canvas_p.png').' para selecionar '.($config['genero_canvas']=='o' ? 'um' : 'uma').' '.$config['canvas'].'.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_painel ? '' : 'style="display:none"').' id="painel" ><td align="right" nowrap="nowrap">'.dica('Painel de Indicador', 'Caso o evento seja específico de um painel de indicador, neste campo deverá constar o nome do painel.').'Painel de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel" value="'.$evento_painel.'" /><input type="text" id="painel_nome" name="painel_nome" value="'.nome_painel($evento_painel).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popPainel();">'.imagem('icones/indicador_p.gif','Selecionar Painel','Clique neste ícone '.imagem('icones/indicador_p.gif').' para selecionar um painel.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_painel_odometro ? '' : 'style="display:none"').' id="painel_odometro" ><td align="right" nowrap="nowrap">'.dica('Odômetro de Indicador', 'Caso o evento seja específico de um odômetro de indicador, neste campo deverá constar o nome do odômetro.').'Odômetro de Indicador:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel_odometro" value="'.$evento_painel_odometro.'" /><input type="text" id="painel_odometro_nome" name="painel_odometro_nome" value="'.nome_painel_odometro($evento_painel_odometro).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOdometro();">'.imagem('icones/odometro_p.png','Selecionar Odômetro','Clique neste ícone '.imagem('icones/odometro_p.png').' para selecionar um odômtro.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_painel_composicao ? '' : 'style="display:none"').' id="painel_composicao" ><td align="right" nowrap="nowrap">'.dica('Composição de Painéis', 'Caso o evento seja específico de uma composição de painéis, neste campo deverá constar o nome da composição.').'Composição de Painéis:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_painel_composicao" value="'.$evento_painel_composicao.'" /><input type="text" id="painel_composicao_nome" name="painel_composicao_nome" value="'.nome_painel_composicao($evento_painel_composicao).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popComposicaoPaineis();">'.imagem('icones/painel_p.gif','Selecionar Composição de Painéis','Clique neste ícone '.imagem('icones/painel_p.gif').' para selecionar uma composição de painéis.').'</a></td></tr></table></td></tr>';
	echo '<tr '.($evento_tr ? '' : 'style="display:none"').' id="tr" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['tr']), 'Caso seja específico de '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].', neste campo deverá constar o nome d'.$config['genero_tr'].' '.$config['tr'].'.').ucfirst($config['tr']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_tr" value="'.$evento_tr.'" /><input type="text" id="tr_nome" name="tr_nome" value="'.nome_tr($evento_tr).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popTR();">'.imagem('icones/tr_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/tr_p.png').' para selecionar '.($config['genero_tr']=='o' ? 'um' : 'uma').' '.$config['tr'].'.').'</a></td></tr></table></td></tr>';
	if (isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo '<tr '.($evento_me ? '' : 'style="display:none"').' id="me" ><td align="right" nowrap="nowrap">'.dica(ucfirst($config['me']), 'Caso seja específico de '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].', neste campo deverá constar o nome d'.$config['genero_me'].' '.$config['me'].'.').ucfirst($config['me']).':'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_me" value="'.$evento_me.'" /><input type="text" id="me_nome" name="me_nome" value="'.nome_me($evento_me).'" style="width:288px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popMe();">'.imagem('icones/me_p.png','Selecionar '.ucfirst($config['canvas']),'Clique neste ícone '.imagem('icones/me_p.png').' para selecionar '.($config['genero_me']=='o' ? 'um' : 'uma').' '.$config['me'].'.').'</a></td></tr></table></td></tr>';
	else echo '<input type="hidden" name="evento_me" value="" id="me" /><input type="hidden" id="me_nome" name="me_nome" value="">';

	}
if ($swot_ativo) echo '<tr '.(isset($evento_swot) && $evento_swot ? '' : 'style="display:none"').' id="swot" ><td align="right" nowrap="nowrap">'.dica('Campo SWOT', 'Caso o evento seja específico de um campo da matriz SWOT neste campo deverá constar o nome do campo da matriz SWOT').'campo SWOT:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_swot" value="'.(isset($evento_swot) ? $evento_swot : '').'" /><input type="text" id="swot_nome" name="swot_nome" value="'.nome_swot((isset($evento_swot) ? $evento_swot : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popSWOT();">'.imagem('../../../modulos/swot/imagens/swot_p.png','Selecionar Campo SWOT','Clique neste ícone '.imagem('../../../modulos/swot/imagens/swot_p.png').' para selecionar um campo da matriz SWOT.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="evento_swot" value="" id="swot" /><input type="hidden" id="swot_nome" name="swot_nome" value="">';
if ($ata_ativo) echo '<tr '.(isset($evento_ata) && $evento_ata ? '' : 'style="display:none"').' id="ata" ><td align="right" nowrap="nowrap">'.dica('Ata de Reunião', 'Caso o evento seja específico de uma ata de reunião neste campo deverá constar o nome da ata').'Ata de Reunião:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_ata" value="'.(isset($evento_ata) ? $evento_ata : '').'" /><input type="text" id="ata_nome" name="ata_nome" value="'.nome_ata((isset($evento_ata) ? $evento_ata : null)).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popAta();">'.imagem('../../../modulos/atas/imagens/ata_p.png','Selecionar Ata de Reunião','Clique neste ícone '.imagem('../../../modulos/atas/imagens/ata_p.png').' para selecionar uma ata de reunião.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="evento_ata" value="" id="ata" /><input type="hidden" id="ata_nome" name="ata_nome" value="">';
if ($operativo_ativo) echo '<tr '.($evento_operativo ? '' : 'style="display:none"').' id="operativo" ><td align="right">'.dica('Plano operativo', 'Caso o evento seja específico de um plano operativo, neste campo deverá constar o nome do plano operativo.').'Operativo:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" name="evento_operativo" value="'.$evento_operativo.'" /><input type="text" id="operativo_nome" name="operativo_nome" value="'.nome_operativo($evento_operativo).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popOperativo();">'.imagem('icones/operativo_p.png','Selecionar Plano Operativo','Clique neste ícone '.imagem('icones/operativo_p.png').' para selecionar um plano operativo.').'</a></td></tr></table></td></tr>';
else echo '<input type="hidden" name="evento_operativo" value="" id="operativo" /><input type="hidden" id="operativo_nome" name="operativo_nome" value="">';
if ($Aplic->profissional){
	$sql->adTabela('evento_gestao');
	$sql->adCampo('evento_gestao.*');
	$sql->adOnde('evento_gestao_evento ='.(int)$evento_id);
	$sql->adOrdem('evento_gestao_ordem');
  $lista = $sql->Lista();
  $sql->Limpar();
	echo '<tr><td></td><td><div id="combo_gestao">';
	if (count($lista)) echo '<table class="tbl1" cellspacing=0 cellpadding=0>';
	foreach($lista as $gestao_data){
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_gestao('.$gestao_data['evento_gestao_ordem'].', '.$gestao_data['evento_gestao_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		if ($gestao_data['evento_gestao_tarefa']) echo '<td align=left>'.imagem('icones/tarefa_p.gif').link_tarefa($gestao_data['evento_gestao_tarefa']).'</td>';
		elseif ($gestao_data['evento_gestao_projeto']) echo '<td align=left>'.imagem('icones/projeto_p.gif').link_projeto($gestao_data['evento_gestao_projeto']).'</td>';
		elseif ($gestao_data['evento_gestao_pratica']) echo '<td align=left>'.imagem('icones/pratica_p.gif').link_pratica($gestao_data['evento_gestao_pratica']).'</td>';
		elseif ($gestao_data['evento_gestao_acao']) echo '<td align=left>'.imagem('icones/plano_acao_p.gif').link_acao($gestao_data['evento_gestao_acao']).'</td>';
		elseif ($gestao_data['evento_gestao_perspectiva']) echo '<td align=left>'.imagem('icones/perspectiva_p.png').link_perspectiva($gestao_data['evento_gestao_perspectiva']).'</td>';
		elseif ($gestao_data['evento_gestao_tema']) echo '<td align=left>'.imagem('icones/tema_p.png').link_tema($gestao_data['evento_gestao_tema']).'</td>';
		elseif ($gestao_data['evento_gestao_objetivo']) echo '<td align=left>'.imagem('icones/obj_estrategicos_p.gif').link_objetivo($gestao_data['evento_gestao_objetivo']).'</td>';
		elseif ($gestao_data['evento_gestao_fator']) echo '<td align=left>'.imagem('icones/fator_p.gif').link_fator($gestao_data['evento_gestao_fator']).'</td>';
		elseif ($gestao_data['evento_gestao_estrategia']) echo '<td align=left>'.imagem('icones/estrategia_p.gif').link_estrategia($gestao_data['evento_gestao_estrategia']).'</td>';
		elseif ($gestao_data['evento_gestao_meta']) echo '<td align=left>'.imagem('icones/meta_p.gif').link_meta($gestao_data['evento_gestao_meta']).'</td>';
		elseif ($gestao_data['evento_gestao_canvas']) echo '<td align=left>'.imagem('icones/canvas_p.png').link_canvas($gestao_data['evento_gestao_canvas']).'</td>';
		elseif ($gestao_data['evento_gestao_risco']) echo '<td align=left>'.imagem('icones/risco_p.png').link_risco($gestao_data['evento_gestao_risco']).'</td>';
		elseif ($gestao_data['evento_gestao_risco_resposta']) echo '<td align=left>'.imagem('icones/risco_resposta_p.png').link_risco_resposta($gestao_data['evento_gestao_risco_resposta']).'</td>';
		elseif ($gestao_data['evento_gestao_indicador']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_indicador($gestao_data['evento_gestao_indicador']).'</td>';
		elseif ($gestao_data['evento_gestao_calendario']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_calendario($gestao_data['evento_gestao_calendario']).'</td>';
		elseif ($gestao_data['evento_gestao_monitoramento']) echo '<td align=left>'.imagem('icones/monitoramento_p.gif').link_monitoramento($gestao_data['evento_gestao_monitoramento']).'</td>';
		elseif ($gestao_data['evento_gestao_ata']) echo '<td align=left>'.imagem('../../../modulos/atas/imagens/ata_p.png').link_ata_pro($gestao_data['evento_gestao_ata']).'</td>';
		elseif ($gestao_data['evento_gestao_swot']) echo '<td align=left>'.imagem('../../../modulos/swot/imagens/swot_p.png').link_swot($gestao_data['evento_gestao_swot']).'</td>';
		elseif ($gestao_data['evento_gestao_operativo']) echo '<td align=left>'.imagem('icones/operativo_p.png').link_operativo($gestao_data['evento_gestao_operativo']).'</td>';
		elseif ($gestao_data['evento_gestao_instrumento']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_instrumento($gestao_data['evento_gestao_instrumento']).'</td>';
		elseif ($gestao_data['evento_gestao_recurso']) echo '<td align=left>'.imagem('icones/recursos_p.gif').link_recurso($gestao_data['evento_gestao_recurso']).'</td>';
		elseif ($gestao_data['evento_gestao_problema']) echo '<td align=left>'.imagem('icones/problema_p.png').link_problema_pro($gestao_data['evento_gestao_problema']).'</td>';
		elseif ($gestao_data['evento_gestao_demanda']) echo '<td align=left>'.imagem('icones/demanda_p.gif').link_demanda($gestao_data['evento_gestao_demanda']).'</td>';
		elseif ($gestao_data['evento_gestao_programa']) echo '<td align=left>'.imagem('icones/programa_p.png').link_programa($gestao_data['evento_gestao_programa']).'</td>';
		elseif ($gestao_data['evento_gestao_licao']) echo '<td align=left>'.imagem('icones/licoes_p.gif').link_licao($gestao_data['evento_gestao_licao']).'</td>';
		elseif ($gestao_data['evento_gestao_link']) echo '<td align=left>'.imagem('icones/links_p.gif').link_link($gestao_data['evento_gestao_link']).'</td>';
		elseif ($gestao_data['evento_gestao_avaliacao']) echo '<td align=left>'.imagem('icones/avaliacao_p.gif').link_avaliacao($gestao_data['evento_gestao_avaliacao']).'</td>';
		elseif ($gestao_data['evento_gestao_tgn']) echo '<td align=left>'.imagem('icones/tgn_p.png').link_tgn($gestao_data['evento_gestao_tgn']).'</td>';
		elseif ($gestao_data['evento_gestao_brainstorm']) echo '<td align=left>'.imagem('icones/brainstorm_p.gif').link_brainstorm_pro($gestao_data['evento_gestao_brainstorm']).'</td>';
		elseif ($gestao_data['evento_gestao_gut']) echo '<td align=left>'.imagem('icones/gut_p.gif').link_gut_pro($gestao_data['evento_gestao_gut']).'</td>';
		elseif ($gestao_data['evento_gestao_causa_efeito']) echo '<td align=left>'.imagem('icones/causaefeito_p.png').link_causa_efeito_pro($gestao_data['evento_gestao_causa_efeito']).'</td>';
		elseif ($gestao_data['evento_gestao_arquivo']) echo '<td align=left>'.imagem('icones/arquivo_p.png').link_arquivo($gestao_data['evento_gestao_arquivo']).'</td>';
		elseif ($gestao_data['evento_gestao_forum']) echo '<td align=left>'.imagem('icones/forum_p.gif').link_forum($gestao_data['evento_gestao_forum']).'</td>';
		elseif ($gestao_data['evento_gestao_checklist']) echo '<td align=left>'.imagem('icones/todo_list_p.png').link_checklist($gestao_data['evento_gestao_checklist']).'</td>';
		elseif ($gestao_data['evento_gestao_agenda']) echo '<td align=left>'.imagem('icones/calendario_p.png').link_agenda($gestao_data['evento_gestao_agenda']).'</td>';
		elseif ($gestao_data['evento_gestao_agrupamento']) echo '<td align=left>'.imagem('../../../modulos/agrupamento/imagens/agrupamento_p.png').link_agrupamento($gestao_data['evento_gestao_agrupamento']).'</td>';
		elseif ($gestao_data['evento_gestao_patrocinador']) echo '<td align=left>'.imagem('../../../modulos/patrocinadores/imagens/patrocinador_p.gif').link_patrocinador($gestao_data['evento_gestao_patrocinador']).'</td>';
		elseif ($gestao_data['evento_gestao_template']) echo '<td align=left>'.imagem('icones/instrumento_p.png').link_template($gestao_data['evento_gestao_template']).'</td>';
		elseif ($gestao_data['evento_gestao_painel']) echo '<td align=left>'.imagem('icones/indicador_p.gif').link_painel($gestao_data['evento_gestao_painel']).'</td>';
		elseif ($gestao_data['evento_gestao_painel_odometro']) echo '<td align=left>'.imagem('icones/odometro_p.png').link_painel_odometro($gestao_data['evento_gestao_painel_odometro']).'</td>';
		elseif ($gestao_data['evento_gestao_painel_composicao']) echo '<td align=left>'.imagem('icones/painel_p.gif').link_painel_composicao($gestao_data['evento_gestao_painel_composicao']).'</td>';
		elseif ($gestao_data['evento_gestao_tr']) echo '<td align=left>'.imagem('icones/tr_p.png').link_tr($gestao_data['evento_gestao_tr']).'</td>';
		elseif ($gestao_data['evento_gestao_me']) echo '<td align=left>'.imagem('icones/me_p.png').link_me($gestao_data['evento_gestao_me']).'</td>';

		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {excluir_gestao('.$gestao_data['evento_gestao_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td></tr>';
		}
	if (count($lista)) echo '</table>';
	echo '</div></td></tr>';
	}


echo '<tr><td align="right" nowrap="nowrap">'.dica('Lembrar', 'Envio de E-mail para lembrar do evento.').'Lembrar:'.dicaF().'</td><td>'.selecionaVetor($lembrar, 'evento_lembrar', 'size="1" class="texto"', $obj->evento_lembrar).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Mostrar Somente em Dias Úteis', 'Marque esta caixa para que a faixa de tempo do evento não inclua os fim-de-semana.').'<label for="evento_diautil">Somente dias úteis:</label>'.dicaF().'</td><td><input type="checkbox" value="1" name="evento_diautil" id="evento_diautil" '.($obj->evento_diautil ? 'checked="checked"' : '').' /></td></tr>';

if ($Aplic->profissional){
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao','pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_gestao_evento = '.(int)$evento_id);
	$indicadores=array(''=>'')+$sql->listaVetorChave('pratica_indicador_id','pratica_indicador_nome');
	$sql->limpar();
	if (count($indicadores)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Indicador Principal', 'Escolha dentre os indicadores relacionados o mais representativo da situação geral.').'Indicador principal:'.dicaF().'</td><td width="100%" colspan="2">'.selecionaVetor($indicadores, 'evento_principal_indicador', 'class="texto" style="width:284px;"', $obj->evento_principal_indicador).'</td></tr>';
	}
	
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'O evento pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os (contatos/designados) podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os (contatos/designados) podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os (contatos/designados) podem ver, e o responsável editar.</li></ul>O responsável e (contatos/designados) citados acima são referentes ao tipo de evento (calendário, '.$config['projeto'].', '.$config['tarefa'].', indicador, '.$config['pratica'].' e '.$config['acao'].')').'Nível de acesso:'.dicaF().'</td><td colspan="2">'.selecionaVetor($niveis_acesso, 'evento_acesso', 'class="texto"', ($evento_id ? $obj->evento_acesso : $config['nivel_acesso_padrao'])).'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Cor', 'Para facilitar a visualização pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido na ponta direita. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto logo à direita.').'Cor:'.dicaF().'</td><td nowrap="nowrap" align="left"><input type="text" name="evento_cor" value="'.($obj->evento_cor ? $obj->evento_cor : 'FFFFFF').'" '.($config['selecao_cor_restrita'] ? 'readonly="readonly" ' : '').'size="10" maxlength="6" onblur="setCor();" class="texto" />&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript: void(0);" onclick="if (window.parent.gpwebApp) parent.gpwebApp.popUp(\'Cor\', 300, 290, \'m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', window.setCor, window); else newwin=window.open(\'./index.php?m=publico&a=selecao_cor&dialogo=1&chamar_volta=setCor\', \'calwin\', \'width=310, height=300, scrollbars=no\');">'.dica('Mudar Cor', 'Para facilitar a visualização dos eventos pode-se escolher uma das 216 cores pré-definidas, bastando clicar no retângulo colorido. Caso deseje uma cor inexistente na paleta de cores deste programa insira o valor Hexadecimal da mesma, na caixa de texto à esquerda.').'Mudar cor&nbsp;&nbsp;<span id="teste" style="border:solid;border-width:1;background:#'.($obj->evento_cor ? $obj->evento_cor : 'FFFFFF').';">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span></a>'.dicaF().'</td></tr>';


if ($exibir['evento_descricao']) echo '<tr><td align="right" valign="middle"  nowrap="nowrap">'.dica('Descrição', 'Um resumo sobre o evento.').'Descrição:'.dicaF().'</td><td><textarea class="textarea" name="evento_descricao" data-gpweb-cmp="ckeditor" rows="5" cols="45">'.$obj->evento_descricao.'</textarea></td></tr>';

require_once $Aplic->getClasseSistema('CampoCustomizados');
$campos_customizados = new CampoCustomizados('evento', $obj->evento_id, 'editar');
$campos_customizados->imprimirHTML();

$cincow2h=($exibir['evento_oque'] && $exibir['evento_quem'] && $exibir['evento_quando'] && $exibir['evento_onde'] && $exibir['evento_porque'] && $exibir['evento_como'] && $exibir['evento_quanto']);

if ($cincow2h){
	echo '<tr><td style="height:3px;"></td></tr>';
	echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'5w2h\').style.display) document.getElementById(\'5w2h\').style.display=\'\'; else document.getElementById(\'5w2h\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>5W2H</b></a></td></tr>';
	echo '<tr id="5w2h" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0 width="100%">';
	}

if ($exibir['evento_oque']) echo '<tr><td align="right" nowrap="nowrap" style="width:150px">'.dica('O Que Fazer', 'Sumário sobre o que se trata este evento.').'O Que:'.dicaF().'</td><td colspan="2"><textarea name="evento_oque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_oque.'</textarea></td></tr>';
if ($exibir['evento_quem']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quem', 'Quem executar o evento.').'Quem:'.dicaF().'</td><td colspan="2"><textarea name="evento_quem" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_quem.'</textarea></td></tr>';
if ($exibir['evento_quando']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quando Fazer', 'Quando o evento é executado.').'Quando:'.dicaF().'</td><td colspan="2"><textarea name="evento_quando" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_quando.'</textarea></td></tr>';
if ($exibir['evento_onde']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Onde Fazer', 'Onde o evento é executado.').'Onde:'.dicaF().'</td><td colspan="2"><textarea name="evento_onde" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_onde.'</textarea></td></tr>';
if ($exibir['evento_porque']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que o evento será executado.').'Por que:'.dicaF().'</td><td colspan="2"><textarea name="evento_porque" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_porque.'</textarea></td></tr>';
if ($exibir['evento_como']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Como Fazer', 'Como o evento é executado.').'Como:'.dicaF().'</td><td colspan="2"><textarea name="evento_como" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_como.'</textarea></td></tr>';
if ($exibir['evento_quanto']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para executar o evento.').'Quanto:'.dicaF().'</td><td colspan="2"><textarea name="evento_quanto" data-gpweb-cmp="ckeditor" cols="60" rows="2" class="textarea">'.$obj->evento_quanto.'</textarea></td></tr>';

if ($cincow2h) {
	echo '</table></fieldset></td></tr>';
	echo '<tr><td style="height:3px;"></td></tr>';
	}




echo '<tr><td colspan=20 style="background-color:#'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'" onclick="if (document.getElementById(\'participantes\').style.display) document.getElementById(\'participantes\').style.display=\'\'; else document.getElementById(\'participantes\').style.display=\'none\';"><a href="javascript: void(0);" class="aba"><b>Convidados</b></a></td></tr>';
echo '<tr id="participantes" style="display:none"><td colspan=20><table cellspacing=0 cellpadding=0>';

echo '<tr><td align=right>'.dica('Pesquisar', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" style="width:400px;" name="busca" id="busca" onchange="env.grupo_a.value=0; env.grupo_b.value=0; mudar_usuario_pesquisa();" value=""/></td><td><a href="javascript:void(0);" onclick="env.busca.value=\'\';">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td><tr>';
if (!$tem_protegido || $Aplic->usuario_super_admin || $Aplic->usuario_admin) echo '<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><table cellpadding=0 cellspacing=0><tr><td><div id="combo_cia_designados">'.selecionar_om($Aplic->usuario_cia, 'cia_designados', 'class=texto size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1).'</div></td><td><a href="javascript:void(0);" onclick="mudar_usuarios_designados()">'.imagem('icones/atualizar.png','Atualizar os '.ucfirst($config['usuarios']),'Clique neste ícone '.imagem('icones/atualizar.png').' para atualizar a lista de '.$config['usuarios']).'</a></td></tr></table></td></tr>';
echo '<tr><td align=right>'.dica('Selecionar Grupo','Clique uma vez para abrir a caixa de seleção e depois escolha um dos grupos abaixo, para selecionar os destinatário.<BR><BR>Este grupos são criados pelo administrador do Sistema.<BR><BR>Para criar grupos particulares utilize o botão GRUPOS.').'Grupo:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_a', 'size="1" style="width:400px" class="texto" onchange="env.grupo_b.value=0; mudar_grupo_id(\'grupo_a\');"',$grupo_id).'</td></tr>';
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao');
$sql->adOnde('grupo_usuario='.(int)$Aplic->usuario_id);
$sql->adOrdem('grupo_descricao ASC');
$grupos = $sql->listaVetorChave('grupo_id','grupo_descricao');
$sql->limpar();
$grupos=array('0'=>'') +$grupos;
echo '<tr><td align=right>'.dica('Selecionar Grupo Particular','Escolha '.$config['usuarios'].' incluídos em um dos seus grupos particulares.<BR><BR>Este grupos são criados por ti utilizando o botão <b>Grupos</b>.').'Particular:'.dicaF().'</td><td>'.selecionaVetor($grupos, 'grupo_b', 'style="width:400px" size="1" class="texto" onchange="env.grupo_a.value=0; mudar_grupo_id(\'grupo_b\');"',$grupo_id2).'</td></tr>';
echo '<tr><td colspan=20><table>';
echo '<tr><td style="text-align:center" width="50%">';
echo '<fieldset><legend class=texto style="color: black;">'.dica('Seleção de '.ucfirst($config['usuarios']),'Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para adiciona-lo à lista de destinatário.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão INCLUIR.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'&nbsp;'.ucfirst($config['usuarios']).'&nbsp</legend>';
echo '<div id="combo_de">';
if ($grupo_id==-1) echo mudar_usuario_em_dept(false, $cia_id, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
else {
	echo '<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';

	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		if ($grupo_id2) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id2);
		elseif ($grupo_id > 0) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
		elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
		$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
		$sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor');
		$usuarios = $sql->Lista();
		$sql->limpar();
   	foreach ($usuarios as $rs)	 echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'</option>';
    }
	echo '</select>';
	}
echo '</div></fieldset>';
echo '</td>';
echo '<td width="50%"><fieldset><legend class=texto style="color: black;">&nbsp;'.dica('Chamar','Dê um clique duplo em um d'.$config['genero_usuario'].'s '.$config['usuarios'].' nesta lista de seleção para remove-lo dos convidados.<BR><BR>Outra opção é selecionar '.$config['genero_usuario'].' '.$config['usuario'].' e clicar no botão Remover.<BR><BR>Para selecionar múltipl'.$config['genero_usuario'].'s '.$config['usuarios'].', clique nos mesmos mantendo a tecla CTRL apertada.').'Chamar&nbsp;</legend><select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';

if (!$evento_id){
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'con','usuario_contato=con.contato_id');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome, 100 AS percentual');
	$sql->adOnde('usuario_id = '.(int)$Aplic->usuario_id);
	$ListaPARA=$sql->Lista();
	$sql->limpar();
	}
else{
	$sql->adTabela('evento_usuarios', 'ue');
	$sql->esqUnir('usuarios', 'u', 'u.usuario_id=ue.usuario_id');
	$sql->esqUnir('contatos', 'con','u.usuario_contato=con.contato_id');
	$sql->esqUnir('cias', 'cias','contato_cia=cia_id');
	$sql->esqUnir('eventos', 'e','e.evento_id=ue.evento_id');
	$sql->adCampo('u.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' as nome_usuario, contato_funcao, cia_nome, percentual');
	$sql->adOnde('e.evento_id = '.(int)$evento_id);
	$ListaPARA=$sql->Lista();
	$sql->limpar();
	}

foreach($ListaPARA as $rs) echo '<option value="'.$rs['usuario_id'].'">'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').' - '.$rs['percentual'].'%</option>';
echo '</select></fieldset></td></tr>';


echo '<select name="ListaPARAnome[]" multiple id="ListaPARAnome" size=4 style="width:100%;display:none;">';
foreach($ListaPARA as $rs) echo '<option value="'.nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '').'">*</option>';
echo '</select>';

echo '<select name="ListaPARAporcentagem[]" multiple id="ListaPARAporcentagem" size=4 style="width:100%;display:none;">';
foreach($ListaPARA as $rs) echo '<option value="'.$rs['percentual'].'">'.$rs['percentual'].'</option>';
echo '</select>';



echo '<tr><td class=CampoJanela style="text-align:center"><table cellpadding=2 cellspacing=0><tr>
<td>'.botao('incluir >>', 'Incluir','Clique neste botão para incluir '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados na caixa de destinatários.','','Mover(env.ListaDE, env.ListaPARA)','','',0).'</td>
<td>'.botao('incluir todos', 'Incluir Todos','Clique neste botão para incluir todos '.$config['genero_usuario'].'s '.$config['usuarios'].'.','','btSelecionarTodos_onclick()','','',0).'</td>

<td>'.dica('Nível de Engajamento', 'Fazer um controle sobre '.$config['usuarios'].' sobrecarregados. As porcentagens de todos  os eventos e '.$config['tarefas'].' que os mesmos estão designados é somada, dia a dia, e poderemos verificar os ociosos ou aqueles exageradamente sobrecarregados e fazer as redistribuições de missões apropriadas.').'<select name="percentagem_designar" id="percentagem_designar" class="texto">';
	for ($i = 5; $i <= 100; $i += 5) echo '<option '.($i == 100 ? 'selected="true"' : '').' value="'.$i.'">'.$i.'%</option>';
echo '</select>'.dicaF().'</td>


<td>'.botao('responsável', 'Responsável','Clique neste botão para incluir o responsável na lista de '.$config['usuarios'].' a escolher.','','Responsavel()','','',0).'</td>
<td>'.botao('designados', 'Designados','Clique neste botão para incluir os designados  na lista de '.$config['usuarios'].' a escolher.','','Designados()','','',0).'</td>
<td>'.botao('comprometimento', 'Comprometimento','Visualizar o grau de comprometimento, por dia, d'.$config['genero_usuario'].' '.$config['usuario'].'.','','comprometimento()','','',0).'</td>
</tr></table></td><td style="text-align:center"><table><tr>
<td>'.botao('<< remover', 'Remover','Clique neste botão para remover os destinatários selecionados da caixa de destinatários.','','Mover2(env.ListaPARA, env.ListaDE)','','',0).'</td>
</tr></table></td></tr>';

echo '<tr><td align="right">'.dica('Notificar', 'Marque esta caixa para avisar '.$config['genero_usuario'].'s '.$config['usuarios'].' selecionados sobre a '.($evento_id > 0 ? 'modificação' : 'criação').' do evento.').'<label for="email_convidado">Notificar:</label>'.dicaF().'</td><td><input type="checkbox" name="email_convidado" id="email_convidado" '.($Aplic->usuario_prefs['tarefaemailreg']&8 ? 'checked="checked"' : '').' /></td></tr>';

echo '</table></td></tr>';


echo '</table></fieldset></td></tr>';


if (!$Aplic->profissional) echo'<tr><td colspan=2><table width="100%"><tr><td align="right">Arquivo:</td><td><input type="file" class="arquivo" name="arquivo[]" size="60"></td></tr>';
else{
	echo '<tr><td colspan=2 align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a este evento.<br>Caso necessite anexar multiplos arquivos basta clicar aqui sucessivamente para criar os campos necessários.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';
	echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';
	}


echo '<tr><td colspan="2"><div id="combo_arquivo"><table cellpadding=0 cellspacing=0>';
//arquivo anexo
$sql->adTabela('evento_arquivos');
$sql->adCampo('evento_arquivo_id, evento_arquivo_usuario, evento_arquivo_data, evento_arquivo_ordem, evento_arquivo_nome, evento_arquivo_endereco');
$sql->adOnde('evento_arquivo_evento_id='.(int)$evento_id);
$sql->adOrdem('evento_arquivo_ordem ASC');
$arquivos=$sql->Lista();
$sql->limpar();
if ($arquivos && count($arquivos))echo '<tr><td colspan=2>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</td></tr>';
foreach ($arquivos as $arquivo) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120">Remetente</td><td>'.nome_funcao('', '', '', '',$arquivo['evento_arquivo_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Anexado em</td><td>'.retorna_data($arquivo['evento_arquivo_data']).'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique neste link para fazer o download do arquivo ou visualizar o mesmo.';
	echo '<tr><td nowrap="nowrap" width="40" align="center">';
	echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
	echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_arquivo('.$arquivo['evento_arquivo_ordem'].', '.$arquivo['evento_arquivo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
	echo '</td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_evento\'; env.u.value=\'\'; env.sem_cabecalho.value=1; env.evento_arquivo_id.value='.$arquivo['evento_arquivo_id'].'; env.submit();">'.dica($arquivo['evento_arquivo_nome'],$dentro).$arquivo['evento_arquivo_nome'].'</a></td>';
	echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {excluir_arquivo('.$arquivo['evento_arquivo_id'].');}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a></td>';
	echo '</tr>';
	}


echo '</table></div></td></tr>';



echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Retorna à tela anterior.','','if(confirm(\'Tem certeza quanto à cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\'); }').'</td></tr>';
echo '</table></td></tr></table>';
echo '</form>';
echo estiloFundoCaixa();


?>
<script type="text/javascript">

function popCias() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['organizacoes']) ?>", 500, 500, 'm=publico&a=selecao_organizacoes&dialogo=1&chamar_volta=setCias&cia_id='+document.getElementById('evento_cia').value+'&cias_id_selecionadas='+document.getElementById('evento_cias').value, window.setCias, window);
	}

function setCias(organizacao_id_string){
	if(!organizacao_id_string) organizacao_id_string = '';
	document.env.evento_cias.value = organizacao_id_string;
	document.getElementById('evento_cias').value = organizacao_id_string;
	xajax_exibir_cias(document.getElementById('evento_cias').value);
	__buildTooltip();
	}

function excluir_arquivo(evento_arquivo_id){
	xajax_excluir_arquivo(document.getElementById('evento_id').value, evento_arquivo_id);
	__buildTooltip();
	}

function mudar_posicao_arquivo(ordem, evento_arquivo_id, direcao){
	xajax_mudar_posicao_arquivo(ordem, evento_arquivo_id, direcao, document.getElementById('evento_id').value);
	__buildTooltip();
	}



function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');
	var ta = document.createTextNode(' Arquivo:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'arquivo[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=80;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function popDept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('evento_dept').value+'&cia_id='+document.getElementById('evento_cia').value, window.setDept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=setDept&dept_id='+document.getElementById('evento_dept').value+'&cia_id='+document.getElementById('evento_cia').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setDept(cia_id, dept_id, dept_nome){
	document.getElementById('evento_cia').value=cia_id;
	document.getElementById('evento_dept').value=dept_id;
	document.getElementById('dept_nome').value=(dept_nome ? dept_nome : '');
	}

var depts_id_selecionados = '<?php echo implode(",", $depts_selecionados)?>';

function popDepts() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["departamento"])?>', 500, 500, 'm=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('evento_cia').value+'&depts_id_selecionados='+depts_id_selecionados, window.setDepts, window);
	else window.open('./index.php?m=publico&a=selecao_dept&dialogo=1&chamar_volta=setDepts&cia_id='+document.getElementById('evento_cia').value+'&depts_id_selecionados='+depts_id_selecionados, 'depts','height=500,width=500,resizable,scrollbars=yes');
	}

function setDepts(departamento_id_string){
	if(!departamento_id_string) departamento_id_string = '';
	document.env.evento_depts.value = departamento_id_string;
	depts_id_selecionados = departamento_id_string;
	xajax_exibir_depts(depts_id_selecionados);
	__buildTooltip();
	}


function mudar_om(){
	var cia_id=document.getElementById('evento_cia').value;
	xajax_selecionar_om_ajax(cia_id,'evento_cia','combo_cia', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om();"');
	}

function mudar_om_designados(){
	xajax_selecionar_om_ajax(document.getElementById('cia_designados').value,'cia_designados','combo_cia_designados', 'class="texto" size=1 style="width:400px;" onchange="javascript:mudar_om_designados();"','',1);
	}

function mudar_usuario_pesquisa() {
	xajax_mudar_usuario_pesquisa_ajax(document.getElementById('busca').value);
	}

function popGerente() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('evento_cia').value+'&usuario_id='+document.getElementById('evento_dono').value, window.setGerente, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setGerente&cia_id='+document.getElementById('evento_cia').value+'&usuario_id='+document.getElementById('evento_dono').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setGerente(usuario_id, posto, nome, funcao, campo, nome_cia){
		document.getElementById('evento_dono').value=usuario_id;
		document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?> ? ' - '+nome_cia : '');
		}

function comprometimento(){
	if (document.getElementById('ListaDE').selectedIndex >-1){
		var usuario_id=document.getElementById('ListaDE').options[document.getElementById('ListaDE').selectedIndex].value;
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Comprometimento', 820, 620, 'm=calendario&a=sobrecarga&dialogo=1&cia_id=<?php echo $cia_id ?>&usuario_id='+usuario_id+'&editar=1', null, window);
		else window.open('./index.php?m=calendario&a=sobrecarga&dialogo=1&cia_id=<?php echo $cia_id ?>&usuario_id='+usuario_id+'&editar=1', 'Comprometimento', 'height=620,width=820,resizable,scrollbars=yes');
		}
	else alert('Precisa selecionar um <?php echo $config["usuario"]?>.');
	}


function Responsavel(){
	var f=document.env;
	var chave=0;
	var tipo='';
	if(f.evento_tarefa.value >0){chave=f.evento_tarefa.value; tipo='tarefa';}
	else if(f.evento_projeto.value >0){chave=f.evento_projeto.value; tipo='projeto';}
	else if(f.evento_pratica.value >0){chave=f.evento_pratica.value; tipo='pratica';}
	else if(f.evento_indicador.value >0){chave=f.evento_indicador.value; tipo='indicador';}
	else if(f.evento_estrategia.value >0){chave=f.evento_estrategia.value; tipo='estrategia';}
	else if(f.evento_objetivo.value >0){chave=f.evento_objetivo.value; tipo='objetivo';}
	else if(f.evento_tema.value >0){chave=f.evento_tema.value; tipo='tema';}
	else if(f.evento_acao.value >0){chave=f.evento_acao.value; tipo='acao';}
	if (tipo || <?php echo ($Aplic->profissional ? 1 : 0)?>) {
		xajax_responsavel_ajax(tipo, chave, document.getElementById('evento_id').value, document.getElementById('uuid').value);
		env.grupo_b.value=0;
		env.grupo_a.value=0;
		}
	else alert('Precisa selecionar primeiro!');
	}


function Designados(){
	var f=document.env;
	var chave=0;
	var tipo='';
	if(f.evento_tarefa.value >0){chave=f.evento_tarefa.value; tipo='tarefa';}
	else if(f.evento_projeto.value >0){chave=f.evento_projeto.value; tipo='projeto';}
	else if(f.evento_pratica.value >0){chave=f.evento_pratica.value; tipo='pratica';}
	else if(f.evento_indicador.value >0){chave=f.evento_indicador.value; tipo='indicador';}
	else if(f.evento_estrategia.value >0){chave=f.evento_estrategia.value; tipo='estrategia';}
	else if(f.evento_objetivo.value >0){chave=f.evento_objetivo.value; tipo='objetivo';}
	else if(f.evento_tema.value >0){chave=f.evento_tema.value; tipo='tema';}
	else if(f.evento_acao.value >0){chave=f.evento_acao.value; tipo='acao';}
	else if(f.evento_calendario.value >0){chave=f.evento_calendario.value; tipo='calendario';}
	if (tipo || <?php echo ($Aplic->profissional ? 1 : 0)?>) {
		xajax_designados_ajax(tipo, chave, document.getElementById('evento_id').value, document.getElementById('uuid').value);
		env.grupo_b.value=0;
		env.grupo_a.value=0;
		}
	else alert('Precisa selecionar primeiro!');
	}

function CompararHoras(){
  var str1 = document.getElementById("inicio_hora").value;
  var str2 = document.getElementById("fim_hora").value;

  if(str2 < str1){
    document.getElementById("fim_hora").value=str1;
  	}
 }

var cal1 = Calendario.setup({
	trigger    : "f_btn1",
  inputField : "evento_inicio",
	date :  <?php echo $data_inicio->format("%Y-%m-%d")?>,
	selection: <?php echo $data_inicio->format("%Y-%m-%d")?>,
  onSelect: function(cal1) {
  var date = cal1.selection.get();
  if (date){
  	date = Calendario.intToDate(date);
    document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("evento_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
    CompararDatas();
    }
	cal1.hide();
	}
});

var cal2 = Calendario.setup({
	trigger : "f_btn2",
  inputField : "evento_fim",
	date : <?php echo $data_fim->format("%Y-%m-%d")?>,
	selection : <?php echo $data_fim->format("%Y-%m-%d")?>,
  onSelect : function(cal2) {
  var date = cal2.selection.get();
  if (date){
    date = Calendario.intToDate(date);
    document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("evento_fim").value = Calendario.printDate(date, "%Y-%m-%d");
    CompararDatas();
    }
	cal2.hide();
	}
});


function enviarDados(){
	var form = document.env;
	if (form.evento_titulo.value.length < 1) {
		alert('Insira o nome do evento');
		form.evento_titulo.focus();
		return;
		}
	if (form.evento_inicio.value.length < 1){
		alert('Insira a data de ínicio');
		form.evento_inicio.focus();
		return;
		}
	if (form.evento_fim.value.length < 1){
		alert('Insira a data de término');
		form.evento_fim.focus();
		return;
		}
	if ( (!(form.evento_nr_recorrencias.value>0))
		&& (form.evento_recorrencias[0].selected!=true) ) {
		alert('Insira o número de recorrências');
		form.evento_nr_recorrencias.value=1;
		form.evento_nr_recorrencias.focus();
		return;
		}



	if (!document.getElementById('profissional').value && document.getElementById('calendario_ver').style.display=='' && form.evento_calendario.value<1)	{
		alert('Escolha uma agenda');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('projeto').style.display=='' && form.evento_projeto.value<1)	{
		alert('Escolha <?php echo ($config["genero_projeto"]=="a" ? "uma ": "um ").$config["projeto"] ?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('pratica').style.display=='' && form.evento_pratica.value<1)	{
		alert('Escolha <?php echo ($config["genero_pratica"]=="a" ? "uma ": "um ").$config["pratica"] ?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('acao').style.display=='' && form.evento_acao.value<1)	{
		alert('Escolha <?php echo ($config["genero_acao"]=="o" ? "um" : "uma")." ".$config["acao"]?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('indicador').style.display=='' && form.evento_indicador.value<1)	{
		alert('Escolha um indicador');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('objetivo').style.display=='' && form.evento_objetivo.value<1)	{
		alert("Escolha <?php echo ($config['genero_objetivo']=='o' ? 'um' : 'uma').' '.$config['objetivo']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('tema').style.display=='' && form.evento_tema.value<1)	{
		alert("Escolha <?php echo ($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema']?>");
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('estrategia').style.display=='' && form.evento_estrategia.value<1)	{
		alert('Escolha <?php echo ($config["genero_iniciativa"]=="o" ? "um" : "uma")." ".$config["iniciativa"]?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('fator').style.display=='' && form.evento_fator.value<1)	{
		alert('Escolha <?php echo ($config["genero_fator"]=="o" ? "um" : "uma")." ".$config["fator"]?>');
		return;
		}
	if (!document.getElementById('profissional').value && document.getElementById('meta').style.display=='' && form.evento_meta.value<1)	{
		alert('Escolha uma meta');
		return;
		}


	var designadoporcentagem = form.ListaPARAporcentagem;
	var designado = form.ListaPARA;
	var len = designado.length;
	var usuarios = form.evento_designado;
	var porcentagem=form.evento_designado_porcentagem;
	porcentagem.value = '';
	usuarios.value = '';
	for (var i = 0; i < len; i++) {
		if (i) usuarios.value += ',';
		if (i) porcentagem.value += ',';
		porcentagem.value +=designadoporcentagem.options[i].value;
		usuarios.value += designado.options[i].value;
		}
	form.submit();
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
      document.getElementById("evento_fim").value=document.getElementById("evento_inicio").value;
    	}
   }

function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.'+frm_nome+'.data_'+f_data );
	campo_data_real = eval( 'document.'+frm_nome+'.evento_'+f_data );
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
			CompararDatas();
			}
		}
	else campo_data_real.value = '';
	}


function setCor(cor) {
	var f = document.env;
	if (cor) f.evento_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.evento_cor.value;
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir o evento?" )) document.frmExcluir.submit();
	}



function mudar_grupo_id(grupo) {
	if (document.getElementById(grupo).value!=-1) xajax_mudar_usuario_grupo_ajax(document.getElementById(grupo).value);
	else mudar_usuarios_designados();
	}


function mudar_usuarios_designados(){
	xajax_mudar_usuario_ajax(document.getElementById('cia_designados').value, 0, 'ListaDE','combo_de', 'class="texto" size="11" style="width:100%;" multiple="multiple" ondblclick="Mover(env.ListaDE, env.ListaPARA); return false;"');
	}

function Mover(ListaDE,ListaPARA) {
	//checar se já existe

	var perc=document.getElementById('percentagem_designar').options[document.getElementById('percentagem_designar').selectedIndex].value;

	for(var i=0; i<ListaDE.options.length; i++) {
		if (ListaDE.options[i].selected && ListaDE.options[i].value  > 0) {
			var no = new Option();
			no.value = ListaDE.options[i].value;
			no.text = ListaDE.options[i].text.replace(/(^[\s]+|[\s]+$)/g, '')+' - '+perc+'%';


			var existe=0;
			for(var j=0; j <ListaPARA.options.length; j++) {
				if (ListaPARA.options[j].value==no.value) {
					existe=1;
					break;
					}
				}
			if (!existe) {

				var no1 = new Option();
				no1.value = ListaDE.options[i].text;
				no1.text = ListaDE.options[i].text;
				ListaPARAnome.options[ListaPARAnome.options.length]=no1;

				ListaPARA.options[ListaPARA.options.length] = no;
				//ListaDE.options[i].value = "";
				//ListaDE.options[i].text = "";

				var no2 = new Option();
				no2.value = perc;
				no2.text = perc;
				ListaPARAporcentagem.options[ListaPARAporcentagem.options.length]=no2;


				}
			}
		}
	//LimpaVazios(ListaDE, ListaDE.options.length);
	}

function Mover2(ListaPARA,ListaDE) {
	for(var i=0; i < ListaPARA.options.length; i++) {
		if (ListaPARA.options[i].selected && ListaPARA.options[i].value > 0) {
			ListaPARA.options[i].value = ""
			ListaPARA.options[i].text = ""
			ListaPARAporcentagem.options[i].value = ""
			ListaPARAporcentagem.options[i].text = ""
			ListaPARAnome.options[i].value = ""
			ListaPARAnome.options[i].text = ""
			}
		}
	LimpaVazios(ListaPARA, ListaPARA.options.length);
	LimpaVazios(ListaPARAporcentagem, ListaPARAporcentagem.options.length);
	LimpaVazios(ListaPARAnome, ListaPARAnome.options.length);
	}

// Limpa Vazios
function LimpaVazios(box, box_len){
	for(var i=0; i<box_len; i++){
		if(box.options[i].value == ""){
			var ln = i;
			box.options[i] = null;
			break;
			}
		}
	if(ln < box_len){
		box_len -= 1;
		LimpaVazios(box, box_len);
		}
	}


// Seleciona todos os campos da lista de usuários
function btSelecionarTodos_onclick() {
	for (var i=0; i < env.ListaDE.length ; i++) {
		env.ListaDE.options[i].selected = true;
	}
	Mover(env.ListaDE, env.ListaPARA);
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
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Agrupamento', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('evento_cia').value, window.setAgrupamento, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgrupamento&tabela=agrupamento&cia_id='+document.getElementById('evento_cia').value, 'Agrupamento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setAgrupamento(chave, valor){
		limpar_tudo();
		document.env.evento_agrupamento.value = chave;
		document.env.agrupamento_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popPatrocinador() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Patrocinador', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('evento_cia').value, window.setPatrocinador, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPatrocinador&tabela=patrocinadores&cia_id='+document.getElementById('evento_cia').value, 'Patrocinador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPatrocinador(chave, valor){
		limpar_tudo();
		document.env.evento_patrocinador.value = chave;
		document.env.patrocinador_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTemplate() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Modelo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('evento_cia').value, window.setTemplate, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTemplate&tabela=template&cia_id='+document.getElementById('evento_cia').value, 'Modelo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTemplate(chave, valor){
		limpar_tudo();
		document.env.evento_template.value = chave;
		document.env.template_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}


<?php } ?>


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&edicao=1&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('evento_cia').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&aceita_portfolio=1&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["projeto"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	limpar_tudo();
	document.env.evento_projeto.value = chave;
	document.env.projeto_nome.value = valor;
	}

function popTarefa() {
	var f = document.env;
	if (f.evento_projeto.value == 0) alert( "Selecione primeiro um<?php echo ($config['genero_projeto']=='o' ? '' : 'a').' '.$config['projeto']?>" );
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tarefa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.evento_projeto.value, window.setTarefa, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTarefa&tabela=tarefas&tarefa_projeto=' + f.evento_projeto.value, '<?php echo ucfirst($config["tarefa"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setTarefa( chave, valor ) {
	limpar_tudo();
	document.env.evento_tarefa.value = chave;
	document.env.tarefa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPerspectiva() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["perspectiva"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('evento_cia').value, window.setPerspectiva, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPerspectiva&tabela=perspectivas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["perspectiva"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPerspectiva(chave, valor){
	limpar_tudo();
	document.env.evento_perspectiva.value = chave;
	document.env.perspectiva_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('evento_cia').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTema(chave, valor){
	limpar_tudo();
	document.env.evento_tema.value = chave;
	document.env.tema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popObjetivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["objetivo"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('evento_cia').value, window.setObjetivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setObjetivo&tabela=objetivos_estrategicos&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["objetivo"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setObjetivo(chave, valor){
	limpar_tudo();
	document.env.evento_objetivo.value = chave;
	document.env.objetivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popFator() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["fator"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('evento_cia').value, window.setFator, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setFator&tabela=fatores_criticos&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["fator"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setFator(chave, valor){
	limpar_tudo();
	document.env.evento_fator.value = chave;
	document.env.fator_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('evento_cia').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setEstrategia(chave, valor){
	limpar_tudo();
	document.env.evento_estrategia.value = chave;
	document.env.estrategia_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('evento_cia').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMeta(chave, valor){
	limpar_tudo();
	document.env.evento_meta.value = chave;
	document.env.meta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popPratica() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["pratica"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('evento_cia').value, window.setPratica, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPratica&tabela=praticas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["pratica"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setPratica(chave, valor){
	limpar_tudo();
	document.env.evento_pratica.value = chave;
	document.env.pratica_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popIndicador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Indicador", 900, 600, 'm=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('evento_cia').value, window.setIndicador, window);
	else window.open('./index.php?m=publico&a=selecionar_subnivel&dialogo=1&chamar_volta=setIndicador&tabela=pratica_indicador&cia_id='+document.getElementById('evento_cia').value, 'Indicador','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setIndicador(chave, valor){
	limpar_tudo();
	document.env.evento_indicador.value = chave;
	document.env.indicador_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAcao() {
	var f = document.env;
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["acao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('evento_cia').value, window.setAcao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAcao&tabela=plano_acao&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["acao"])?>','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setAcao(chave, valor){
	limpar_tudo();
	document.env.evento_acao.value = chave;
	document.env.acao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php if ($Aplic->profissional) { ?>

function popPainel() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Painel', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('evento_cia').value, window.setPainel, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPainel&tabela=painel&cia_id='+document.getElementById('evento_cia').value, 'Painel','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setPainel(chave, valor){
		limpar_tudo();
		document.env.evento_painel.value = chave;
		document.env.painel_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popOdometro() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Odômetro', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('evento_cia').value, window.setOdometro, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOdometro&tabela=painel_odometro&cia_id='+document.getElementById('evento_cia').value, 'Odômetro','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setOdometro(chave, valor){
		limpar_tudo();
		document.env.evento_painel_odometro.value = chave;
		document.env.painel_odometro_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popComposicaoPaineis() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('Composição de Painéis', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('evento_cia').value, window.setComposicaoPaineis, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setComposicaoPaineis&tabela=painel_composicao&cia_id='+document.getElementById('evento_cia').value, 'Composição de Painéis','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setComposicaoPaineis(chave, valor){
		limpar_tudo();
		document.env.evento_painel_composicao.value = chave;
		document.env.painel_composicao_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popTR() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tr"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('evento_cia').value, window.setTR, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTR&tabela=tr&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tr"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setTR(chave, valor){
		limpar_tudo();
		document.env.evento_tr.value = chave;
		document.env.tr_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}

	function popMe() {
		if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["me"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('evento_cia').value, window.setMe, window);
		else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMe&tabela=me&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["me"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
		}

	function setMe(chave, valor){
		limpar_tudo();
		document.env.evento_me.value = chave;
		document.env.me_nome.value = valor;
		<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
		}


<?php } ?>

<?php  if (isset($config['canvas'])) { ?>
function popCanvas() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["canvas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('evento_cia').value, window.setCanvas, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCanvas&tabela=canvas&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["canvas"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCanvas(chave, valor){
	limpar_tudo();
	document.env.evento_canvas.value = chave;
	document.env.canvas_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco'])) { ?>
function popRisco() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('evento_cia').value, window.setRisco, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRisco&tabela=risco&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["risco"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRisco(chave, valor){
	limpar_tudo();
	document.env.evento_risco.value = chave;
	document.env.risco_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>

<?php  if (isset($config['risco_respostas'])) { ?>
function popRiscoResposta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["risco_respostas"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('evento_cia').value, window.setRiscoResposta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRiscoResposta&tabela=risco_resposta&cia_id='+document.getElementById('evento_cia').value, '<?php echo $config["risco_respostas"]?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRiscoResposta(chave, valor){
	limpar_tudo();
	document.env.evento_risco_resposta.value = chave;
	document.env.risco_resposta_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php }?>


function popCalendario() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Agenda", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('evento_cia').value, window.setCalendario, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCalendario&tabela=calendario&cia_id='+document.getElementById('evento_cia').value, 'Agenda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCalendario(chave, valor){
	limpar_tudo();
	document.env.evento_calendario.value = chave;
	document.env.calendario_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popMonitoramento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("Monitoramento", 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('evento_cia').value, window.setMonitoramento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMonitoramento&tabela=monitoramento&cia_id='+document.getElementById('evento_cia').value, 'Monitoramento','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setMonitoramento(chave, valor){
	limpar_tudo();
	document.env.evento_monitoramento.value = chave;
	document.env.monitoramento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAta() {
	parent.gpwebApp.popUp('Ata de Reunião', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAta&tabela=ata&cia_id='+document.getElementById('evento_cia').value, window.setAta, window);
	}

function setAta(chave, valor){
	limpar_tudo();
	document.env.evento_ata.value = chave;
	document.env.ata_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popSWOT() {
	parent.gpwebApp.popUp('SWOT', 500, 500, 'm=swot&a=selecionar&dialogo=1&chamar_volta=setSWOT&tabela=swot&cia_id='+document.getElementById('evento_cia').value, window.setSWOT, window);
	}

function setSWOT(chave, valor){
	limpar_tudo();
	document.env.evento_swot.value = chave;
	document.env.swot_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popOperativo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Plano Operativo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('evento_cia').value, window.setOperativo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setOperativo&tabela=operativo&cia_id='+document.getElementById('evento_cia').value, 'Plano Operativo','left=0,top=0,height=600,width=350,scrollbars=yes, resizable=yes');
	}

function setOperativo(chave, valor){
	limpar_tudo();
	document.env.evento_operativo.value = chave;
	document.env.operativo_nome.value = valor;
	}

function popInstrumento() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Instrumento Jurídico', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('evento_cia').value, window.setInstrumento, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setInstrumento&tabela=instrumento&cia_id='+document.getElementById('evento_cia').value, 'Instrumento Jurídico','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setInstrumento(chave, valor){
	limpar_tudo();
	document.env.evento_instrumento.value = chave;
	document.env.instrumento_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popRecurso() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Recurso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('evento_cia').value, window.setRecurso, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setRecurso&tabela=recursos&cia_id='+document.getElementById('evento_cia').value, 'Recurso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setRecurso(chave, valor){
	limpar_tudo();
	document.env.evento_recurso.value = chave;
	document.env.recurso_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['problema'])) { ?>
function popProblema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["problema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('evento_cia').value, window.setProblema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProblema&tabela=problema&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["problema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProblema(chave, valor){
	limpar_tudo();
	document.env.evento_problema.value = chave;
	document.env.problema_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>


function popDemanda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Demanda', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('evento_cia').value, window.setDemanda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setDemanda&tabela=demandas&cia_id='+document.getElementById('evento_cia').value, 'Demanda','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setDemanda(chave, valor){
	limpar_tudo();
	document.env.evento_demanda.value = chave;
	document.env.demanda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

<?php  if (isset($config['programa'])) { ?>
function popPrograma() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["programa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('evento_cia').value, window.setPrograma, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setPrograma&tabela=programa&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["programa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setPrograma(chave, valor){
	limpar_tudo();
	document.env.evento_programa.value = chave;
	document.env.programa_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>

function popLicao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["licao"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('evento_cia').value, window.setLicao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLicao&tabela=licao&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["licao"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLicao(chave, valor){
	limpar_tudo();
	document.env.evento_licao.value = chave;
	document.env.licao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popLink() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Link', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('evento_cia').value, window.setLink, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setLink&tabela=links&cia_id='+document.getElementById('evento_cia').value, 'Link','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setLink(chave, valor){
	limpar_tudo();
	document.env.evento_link.value = chave;
	document.env.link_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAvaliacao() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Avaliação', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('evento_cia').value, window.setAvaliacao, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAvaliacao&tabela=avaliacao&cia_id='+document.getElementById('evento_cia').value, 'Avaliação','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAvaliacao(chave, valor){
	limpar_tudo();
	document.env.evento_avaliacao.value = chave;
	document.env.avaliacao_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php  if (isset($config['tgn'])) { ?>
function popTgn() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tgn"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('evento_cia').value, window.setTgn, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTgn&tabela=tgn&cia_id='+document.getElementById('evento_cia').value, '<?php echo ucfirst($config["tgn"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setTgn(chave, valor){
	limpar_tudo();
	document.env.evento_tgn.value = chave;
	document.env.tgn_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}
<?php } ?>
function popBrainstorm() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Brainstorm', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('evento_cia').value, window.setBrainstorm, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setBrainstorm&tabela=brainstorm&cia_id='+document.getElementById('evento_cia').value, 'Brainstorm','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setBrainstorm(chave, valor){
	limpar_tudo();
	document.env.evento_brainstorm.value = chave;
	document.env.brainstorm_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popGut() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Matriz G.U.T.', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('evento_cia').value, window.setGut, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setGut&tabela=gut&cia_id='+document.getElementById('evento_cia').value, 'Matriz G.U.T.','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setGut(chave, valor){
	limpar_tudo();
	document.env.evento_gut.value = chave;
	document.env.gut_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popCausa_efeito() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Diagrama de Causa-Efeito', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('evento_cia').value, window.setCausa_efeito, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setCausa_efeito&tabela=causa_efeito&cia_id='+document.getElementById('evento_cia').value, 'Diagrama de Causa-Efeito','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setCausa_efeito(chave, valor){
	limpar_tudo();
	document.env.evento_causa_efeito.value = chave;
	document.env.causa_efeito_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popArquivo() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Arquivo', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('evento_cia').value, window.setArquivo, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setArquivo&tabela=arquivos&cia_id='+document.getElementById('evento_cia').value, 'Arquivo','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setArquivo(chave, valor){
	limpar_tudo();
	document.env.evento_arquivo.value = chave;
	document.env.arquivo_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popForum() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Fórum', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('evento_cia').value, window.setForum, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setForum&tabela=foruns&cia_id='+document.getElementById('evento_cia').value, 'Fórum','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setForum(chave, valor){
	limpar_tudo();
	document.env.evento_forum.value = chave;
	document.env.forum_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popChecklist() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Checklist', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('evento_cia').value, window.setChecklist, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setChecklist&tabela=checklist&cia_id='+document.getElementById('evento_cia').value, 'Checklist','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setChecklist(chave, valor){
	limpar_tudo();
	document.env.evento_checklist.value = chave;
	document.env.checklist_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}

function popAgenda() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Compromisso', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('evento_cia').value, window.setAgenda, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setAgenda&tabela=agenda&cia_id='+document.getElementById('evento_cia').value, 'Compromisso','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setAgenda(chave, valor){
	limpar_tudo();
	document.env.evento_agenda.value = chave;
	document.env.agenda_nome.value = valor;
	<?php if ($Aplic->profissional) echo 'incluir_relacionado();' ?>
	}



function limpar_tudo(){
	if (document.getElementById('tipo_relacao').value!='projeto'){
		document.env.projeto_nome.value = '';
		document.env.evento_projeto.value = null;
		}
	document.env.evento_pratica.value = null;
	document.env.pratica_nome.value = '';
	document.env.evento_indicador.value = null;
	document.env.indicador_nome.value = '';
	document.env.evento_tarefa.value = null;
	document.env.tarefa_nome.value = '';
	document.env.evento_acao.value = null;
	document.env.acao_nome.value = '';
	document.env.evento_objetivo.value = null;
	document.env.objetivo_nome.value = '';
	document.env.evento_estrategia.value = null;
	document.env.estrategia_nome.value = '';
	document.env.evento_fator.value = null;
	document.env.fator_nome.value = '';
	document.env.evento_perspectiva.value = null;
	document.env.perspectiva_nome.value = '';
	document.env.evento_canvas.value = null;
	document.env.canvas_nome.value = '';
	document.env.evento_risco.value = null;
	document.env.risco_nome.value = '';
	document.env.evento_risco_resposta.value = null;
	document.env.risco_resposta_nome.value = '';
	document.env.evento_meta.value = null;
	document.env.meta_nome.value = '';
	document.env.evento_tema.value = null;
	document.env.tema_nome.value = '';
	document.env.evento_monitoramento.value = null;
	document.env.monitoramento_nome.value = '';
	document.env.evento_calendario.value = null;
	document.env.calendario_nome.value = '';
	document.env.evento_instrumento.value = null;
	document.env.instrumento_nome.value = '';
	document.env.evento_recurso.value = null;
	document.env.recurso_nome.value = '';
	document.env.evento_problema.value = null;
	document.env.problema_nome.value = '';
	document.env.evento_demanda.value = null;
	document.env.demanda_nome.value = '';
	document.env.evento_programa.value = null;
	document.env.programa_nome.value = '';
	document.env.evento_licao.value = null;
	document.env.licao_nome.value = '';
	document.env.evento_link.value = null;
	document.env.link_nome.value = '';
	document.env.evento_avaliacao.value = null;
	document.env.avaliacao_nome.value = '';
	document.env.evento_tgn.value = null;
	document.env.tgn_nome.value = '';
	document.env.evento_brainstorm.value = null;
	document.env.brainstorm_nome.value = '';
	document.env.evento_gut.value = null;
	document.env.gut_nome.value = '';
	document.env.evento_causa_efeito.value = null;
	document.env.causa_efeito_nome.value = '';
	document.env.evento_arquivo.value = null;
	document.env.arquivo_nome.value = '';
	document.env.evento_forum.value = null;
	document.env.forum_nome.value = '';
	document.env.evento_checklist.value = null;
	document.env.checklist_nome.value = '';
	document.env.evento_agenda.value = null;
	document.env.agenda_nome.value = '';
	document.env.evento_template.value = null;
	document.env.template_nome.value = '';
	document.env.evento_painel.value = null;
	document.env.painel_nome.value = '';
	document.env.evento_painel_odometro.value = null;
	document.env.painel_odometro_nome.value = '';
	document.env.evento_painel_composicao.value = null;
	document.env.painel_composicao_nome.value = '';
	<?php
	if($swot_ativo) echo 'document.env.swot_nome.value = \'\';	document.env.evento_swot.value = null;';
	if($ata_ativo) echo 'document.env.ata_nome.value = \'\';	document.env.evento_ata.value = null;';
	if($operativo_ativo) echo 'document.env.operativo_nome.value = \'\';	document.env.evento_operativo.value = null;';
	if($agrupamento_ativo) echo 'document.env.agrupamento_nome.value = \'\';	document.env.evento_agrupamento.value = null;';
	if($patrocinador_ativo) echo 'document.env.patrocinador_nome.value = \'\';	document.env.evento_patrocinador.value = null;';
	if($tr_ativo) echo 'document.env.tr_nome.value = \'\';	document.env.evento_tr.value = null;';
	if(isset($config['exibe_me']) && $config['exibe_me'] && $Aplic->checarModulo('praticas', 'adicionar', null, 'me')) echo 'document.env.me_nome.value = \'\';	document.env.evento_me.value = null;';
	?>
	}

function incluir_relacionado(){
	var f=document.env;
	xajax_incluir_relacionado(
	document.getElementById('evento_id').value,
	document.getElementById('uuid').value,
	f.evento_projeto.value,
	f.evento_tarefa.value,
	f.evento_perspectiva.value,
	f.evento_tema.value,
	f.evento_objetivo.value,
	f.evento_fator.value,
	f.evento_estrategia.value,
	f.evento_meta.value,
	f.evento_pratica.value,
	f.evento_acao.value,
	f.evento_canvas.value,
	f.evento_risco.value,
	f.evento_risco_resposta.value,
	f.evento_indicador.value,
	f.evento_calendario.value,
	f.evento_monitoramento.value,
	f.evento_ata.value,
	f.evento_swot.value,
	f.evento_operativo.value,
	f.evento_instrumento.value,
	f.evento_recurso.value,
	f.evento_problema.value,
	f.evento_demanda.value,
	f.evento_programa.value,
	f.evento_licao.value,
	f.evento_link.value,
	f.evento_avaliacao.value,
	f.evento_tgn.value,
	f.evento_brainstorm.value,
	f.evento_gut.value,
	f.evento_causa_efeito.value,
	f.evento_arquivo.value,
	f.evento_forum.value,
	f.evento_checklist.value,
	f.evento_agenda.value,
	f.evento_agrupamento.value,
	f.evento_patrocinador.value,
	f.evento_template.value,
	f.evento_painel.value,
	f.evento_painel_odometro.value,
	f.evento_painel_composicao.value,
	f.evento_tr.value,
	f.evento_me.value

	);
	limpar_tudo();
	__buildTooltip();
	}

function excluir_gestao(evento_gestao_id){
	xajax_excluir_gestao(document.getElementById('evento_id').value, document.getElementById('uuid').value, evento_gestao_id);
	__buildTooltip();
	}

function mudar_posicao_gestao(ordem, evento_gestao_id, direcao){
	xajax_mudar_posicao_gestao(ordem, evento_gestao_id, direcao, document.getElementById('evento_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


<?php if (!$evento_id && (
	$evento_projeto ||
	$evento_tarefa ||
	$evento_perspectiva ||
	$evento_tema ||
	$evento_objetivo ||
	$evento_fator ||
	$evento_estrategia ||
	$evento_meta ||
	$evento_pratica ||
	$evento_acao ||
	$evento_canvas ||
	$evento_risco ||
	$evento_risco_resposta ||
	$evento_indicador ||
	$evento_calendario ||
	$evento_monitoramento ||
	$evento_ata ||
	$evento_swot ||
	$evento_operativo ||
	$evento_instrumento ||
	$evento_recurso ||
	$evento_problema ||
	$evento_demanda ||
	$evento_programa ||
	$evento_licao ||
	$evento_link ||
	$evento_avaliacao ||
	$evento_tgn ||
	$evento_brainstorm ||
	$evento_gut ||
	$evento_causa_efeito ||
	$evento_arquivo ||
	$evento_forum ||
	$evento_checklist ||
	$evento_agenda ||
	$evento_agrupamento ||
	$evento_patrocinador ||
	$evento_template ||
	$evento_painel ||
	$evento_painel_odometro ||
	$evento_painel_composicao ||
	$evento_tr ||
	$evento_me
	)) echo 'incluir_relacionado();';
	?>

</script>
