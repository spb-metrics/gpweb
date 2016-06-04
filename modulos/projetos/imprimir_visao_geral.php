<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/


global $Aplic, $config;

$tipo = getParam($_REQUEST, 'tipo', 0);
$projeto_id = getParam($_REQUEST, 'projeto_id', 0);
$baseline_id = getParam($_REQUEST, 'baseline_id', 0);
$tarefa_id = getParam($_REQUEST, 'tarefa_id', 0);

$podeAcessarTarefas = $Aplic->checarModulo('tarefas', 'acesso');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql = new BDConsulta;

$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos','projetos');
$sql->esqUnir('cias','cias','cias.cia_id=projeto_cia');
$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
$sql->adCampo('cia_cabacalho, projeto_responsavel, projeto_supervisor, projeto_nome, municipio_nome AS cia_cidade');
$sql->adOnde('projeto_id='.$projeto_id);
if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
$dados_projeto=$sql->Linha();
$sql->limpar();

$portfolio_lista = null;
if($Aplic->profissional && $projeto_id){
  $sql->adTabela(($baseline_id ? 'baseline_' : '').'projeto_portfolio');
  $sql->adCampo('projeto_portfolio_filho');
  $sql->adOnde('projeto_portfolio_pai = '.(int)$projeto_id);
  $lista=$sql->listaVetorChave('projeto_portfolio_filho','projeto_portfolio_filho');
  if($lista) $portfolio_lista = implode(',',$lista);
  $sql->limpar();
  }

if($portfolio_lista){
  $genero_projeto = $config['genero_portfolio'];
  $label_projeto = $config['portfolio'];
  }
else{
  $genero_projeto = $config['genero_projeto'];
  $label_projeto = $config['projeto'];
  }

$objp = new CProjeto();
$objp->load($projeto_id);
$codigo=$objp->getCodigo();







if (!$Aplic->profissional){
	echo '<table width=1060 cellspacing=0 cellpadding=0 align="left" border=0>';
	echo '<tr><td align="center" style="padding-bottom: 10px;"><img src="'.$Aplic->gpweb_brasao.'" alt=""></td></tr>';
	if(isset($dados_projeto['cia_cabacalho'])) echo '<tr><td align="center" style="padding-bottom: 5px; padding-top: 5px;">'.$dados_projeto['cia_cabacalho'].'</td></tr>';
	echo '<tr><td align="center" style="padding-bottom: 5px; padding-top: 5px;"><b>VISÃO GERAL D'.strtoupper($genero_projeto.' '.$label_projeto.'<br>'.$dados_projeto['projeto_nome']).($codigo ? '<br>'.$codigo : '').'</b></td></tr>';

	echo corpo_cabecalho();
	}
else {



	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';

	$titulo_cabecalho='VISÃO GERAL D'.strtoupper($genero_projeto.' '.$label_projeto);

	if ($Aplic->profissional) {
		$barra=codigo_barra('projeto', $projeto_id, $baseline_id);
		if ($barra['cabecalho']) echo $barra['imagem'];
		}


	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos', 'projetos');
	$sql->adCampo('projeto_id, projeto_cia, projeto_nome, projeto_codigo');
	$sql->adOnde('projeto_id = '.(int)$projeto_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$dados = $sql->Linha();
	$sql->limpar();

	$dados['titulo_cabecalho']='VISÃO GERAL D'.strtoupper($genero_projeto.' '.$label_projeto);


	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_padrao_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);

	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);

	echo '<table align="left" cellspacing=0 cellpadding=0 width=1060><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		}
	echo $tpl->exibir($modelo->edicao);
	echo '</td></tr>';
	if ($Aplic->profissional && $barra['rodape']) echo '<tr><td>'.$barra['imagem'].'</td></tr>';
	echo '</table>';
	}


function corpo_cabecalho(){
	global $obj, $baseline_id, $m, $a, $config, $projeto_id, $sql, $Aplic, $label_projeto, $objp, $dados_projeto, $portfolio_lista, $codigo;

	$saida='';


	if ($Aplic->profissional) {
	  $barra=codigo_barra('projeto', $projeto_id, $baseline_id);
	  if ($barra['cabecalho']) $saida.= $barra['imagem'];
	  }


	$PrioridadeProjeto = getSisValor('PrioridadeProjeto');
	$corPrioridadeProjeto = getSisValor('CorPrioridadeProjeto');
	$projStatus = getSisValor('StatusProjeto');
	$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);






	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->adCampo('COUNT(distinct tarefas.tarefa_id) AS total_tarefas');
	if($portfolio_lista) $sql->adOnde('tarefa_projeto IN ('.$portfolio_lista.')');
	else $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$temTarefas = $sql->Resultado();
	$sql->limpar();

	$obj = new CProjeto(($baseline_id ? true : false));
	$obj->load($projeto_id, true, $baseline_id);

	if (!$obj) {
	  $Aplic->setMsg('Projeto');
	  $Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	  $Aplic->redirecionar('m=projetos');
	  }

	if ($temTarefas) {
	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_log','tarefa_log');
	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	  $sql->adCampo('ROUND(SUM(tarefa_log_horas),2)');
	  $sql->adOnde('tarefa_log_tarefa = tarefa_id AND tarefa_projeto = '.(int)$projeto_id);
	  if ($baseline_id){
	    $sql->adOnde('tarefa_log.baseline_id='.(int)$baseline_id);
	    $sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	    }
	  $horas_trabalhadas = $sql->Resultado();
	  $sql->limpar();

	  $horas_trabalhadas = rtrim($horas_trabalhadas, '.');
	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	  $sql->adCampo('ROUND(SUM(tarefa_duracao),2)');
	  $sql->adOnde('tarefa_projeto = '.(int)$projeto_id.' AND tarefa_duracao_tipo = 24 AND tarefa_dinamica != 1');
	  if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	  $dias = $sql->Resultado();
	  $sql->limpar();

	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	  $sql->adCampo('ROUND(SUM(tarefa_duracao),2)');
	  $sql->adOnde('tarefa_projeto = '.(int)$projeto_id.' AND tarefa_duracao_tipo = 1 AND tarefa_dinamica != 1');
	  if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	  $horas = $sql->Resultado();
	  $sql->limpar();

	  $totalHoras = $dias * $config['horas_trab_diario'] + $horas;
	  $totalHoras_projeto = 0;

	  $q2 = new BDConsulta;
	  $q2->adTabela(($baseline_id ? 'baseline_' : '').'tarefas', 't');
	  $q2->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados', 'u', 't.tarefa_id = u.tarefa_id');
	  $q2->adCampo('ROUND(SUM(t.tarefa_duracao*u.perc_designado/100),2)');
	  $q2->adOnde('t.tarefa_projeto = '.(int)$projeto_id.' AND t.tarefa_duracao_tipo = 1 AND t.tarefa_dinamica != 1');
	  if ($baseline_id){
	    $sql->adOnde('t.baseline_id='.(int)$baseline_id);
	    $sql->adOnde('u.baseline_id='.(int)$baseline_id);
	    }
	  $totalHoras_projeto = $sql->Resultado() * $config['horas_trab_diario'] + $q2->Resultado();
	  $sql->limpar();

	  $q2->limpar();
	  }
	else $horas_trabalhadas = $totalHoras = $totalHoras_projeto = 0.00;
	$prioridades = getSisValor('PrioridadeTarefa');
	$tipos = getSisValor('TipoTarefa');
	include_once ($Aplic->getClasseModulo('tarefas'));
	global $tarefa_acesso;
	$extra = array(0 => '(nenhum)', 1 => 'Marco', 2 => ucfirst($config['tarefa']).' Dinâmic'.$config['genero_tarefa'], 3 => ucfirst($config['tarefa']).' Inativ'.$config['genero_tarefa']);


	$numero=0;
	$tarefas[][]=array();
	$usuarios=array();
	$nd=array(0 => '');
	$nd+= getSisValorND();
	$lista_tarefas=array();
	$unidade= getSisValor('TipoUnidade');
	$departamentos=array();
	$tarefas_dep[][]=array();
	$df = '%d/%m/%Y';





	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos', 'pr');
	$sql->adCampo('concatenar_tres(ct.contato_posto, \' \', ct.contato_nomeguerra) AS nome_responsavel');
	$sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
	$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
	$sql->adOnde('u.usuario_id='.(int)$obj->projeto_responsavel);
	if ($baseline_id) $sql->adOnde('baseline_id='.(int)$baseline_id);
	$resultado = $sql->Resultado();
	$sql->limpar();

	$saida.= '<table width=1060 cellspacing=0 cellpadding=0 align="left" border=0>';

	if ($resultado) $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. GERENTE DO '.strtoupper($label_projeto).'</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$resultado.'</font></td></tr>';
	if ($obj->projeto_descricao) $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. O QUE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->projeto_descricao.'</font></td></tr>';
	if ($obj->projeto_objetivos) $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. POR QUE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->projeto_objetivos.'</font></td></tr>';
	if ($obj->projeto_como) $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. COMO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->projeto_como.'</font></td></tr>';
	if ($obj->projeto_localizacao) $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. ONDE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj->projeto_localizacao.'</font></td></tr>';
	if ($obj->projeto_data_inicio && $obj->projeto_data_fim) $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. QUANDO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>De '.retorna_data($obj->projeto_data_inicio, false).' até '.retorna_data($obj->projeto_data_fim, false).'</font></td></tr>';
	if (empty($texto_consulta)) $texto_consulta = '?m='.$m.'&a='.$a;
	$cols = 13;

	$tipoDuracao = getSisValor('TipoDuracaoTarefa');
	$prioridadeTarefa = getSisValor('PrioridadeTarefa');
	$tarefa_projeto = $projeto_id;


	if (isset($_REQUEST['mostrar_tarefa_options'])) $Aplic->setEstado('ListaTarefasMostrarIncompletas', getParam($_REQUEST, 'mostrar_incompleta', 0));
	$mostrarIncompleta = $Aplic->getEstado('ListaTarefasMostrarIncompletas', 0);


	$projeto = new CProjeto;
	$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos','projetos');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 't1', 'projetos.projeto_id = t1.tarefa_projeto');
	$sql->esqUnir('cias', 'c', 'cia_id = projeto_cia');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projeto_depts', 'projeto_depts', 'projetos.projeto_id = projeto_depts.projeto_id OR projeto_depts.projeto_id IS NULL');
	$sql->esqUnir('depts', 'depts', 'depts.dept_id = projeto_depts.departamento_id OR dept_id IS NULL');
	$sql->adCampo('projetos.projeto_id, projeto_cor, projeto_nome, projeto_percentagem, projeto_status');
	$sql->adCampo('cia_nome');
	$sql->adOnde('t1.tarefa_id = t1.tarefa_superior');
	if($portfolio_lista) $sql->adOnde('projetos.projeto_id IN ('.$portfolio_lista.')');
	else $sql->adOnde('projetos.projeto_id='.$projeto_id);

	if ($baseline_id){
	   $sql->adOnde('projetos.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('t1.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('projeto_depts.baseline_id='.(int)$baseline_id);
	  }
	$sql->adGrupo('projetos.projeto_id');
	$projetos = $sql->ListaChaveSimples('projeto_id');
	$sql->limpar();

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projetos', 'projetos', 'projetos.projeto_id = tarefa_projeto');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'projeto_depts', 'projeto_depts', 'projetos.projeto_id = projeto_depts.projeto_id OR projeto_depts.projeto_id IS NULL');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_designados', 'ut', 'ut.tarefa_id = tarefas.tarefa_id');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefa_log', 'tlog', 'tlog.tarefa_log_tarefa = tarefas.tarefa_id AND tlog.tarefa_log_problema > 0');
	$sql->esqUnir('usuarios', 'usuarioNomes', 'tarefa_dono = usuarioNomes.usuario_id');
	$sql->esqUnir('usuarios', 'designados', 'designados.usuario_id = ut.usuario_id');
	$sql->esqUnir('contatos', 'co', 'co.contato_id = usuarioNomes.usuario_contato');
	$sql->esqUnir('arquivos', 'f', 'tarefas.tarefa_id = f.arquivo_tarefa');
	$sql->esqUnir('usuario_tarefa_marcada', 'marcada', 'tarefas.tarefa_id = marcada.tarefa_id AND marcada.usuario_id = '.(int)$Aplic->usuario_id);
	$sql->esqUnir('evento_recorrencia', 'evtq', 'tarefas.tarefa_id = evtq.recorrencia_id_origem AND evtq.recorrencia_modulo = \'tarefas\'');
	$sql->esqUnir('depts', 'depts', 'depts.dept_id = projeto_depts.departamento_id OR dept_id IS NULL');
	$sql->adCampo('DISTINCT tarefas.tarefa_id, tarefa_superior, tarefa_nome');
	$sql->adCampo('tarefa_inicio, tarefa_fim, tarefa_dinamica');
	$sql->adCampo('count(tarefas.tarefa_superior) as subordinada');
	$sql->adCampo('tarefa_marcada, marcada.usuario_id as usuario_tarefa_marcada, tarefa_prioridade, tarefa_percentagem, tarefa_duracao, tarefa_duracao_tipo, tarefa_projeto, tarefa_acesso, tarefa_tipo');
	$sql->adCampo('tarefa_descricao, tarefa_dono, tarefa_status');
	$sql->adCampo('usuarioNomes.usuario_login, usuarioNomes.usuario_id');
	$sql->adCampo('designados.usuario_login as designado_usuarioNome');
	$sql->adCampo('count(distinct designados.usuario_id) as designado_contagem');
	$sql->adCampo('co.contato_posto, co.contato_nomeguerra');
	$sql->adCampo('tarefa_marco');
	$sql->adCampo('count(distinct f.arquivo_tarefa) as nr_arquivos');
	$sql->adCampo('tlog.tarefa_log_problema');
	$sql->adCampo('evtq.recorrencia_id');
	if($portfolio_lista) $sql->adOnde('tarefa_projeto IN ('.$portfolio_lista.')');
	else $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
	if ($baseline_id){
	   $sql->adOnde('projetos.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('ut.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('tlog.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('projeto_depts.baseline_id='.(int)$baseline_id);
	  }
	$sql->adOrdem(($portfolio_lista ? 'tarefa_projeto,' : '').($Aplic->profissional ? 'tarefa_numeracao, ':'').'tarefa_inicio');
	if($portfolio_lista) $sql->adGrupo('tarefa_projeto');
	$sql->adGrupo('tarefa_id');
	$tarefas = $sql->Lista();
	$sql->limpar();


	foreach ($tarefas as $linha) {
	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_designados', 'ut');
	  $sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 't','ut.tarefa_id=t.tarefa_id');
	  $sql->esqUnir('usuarios', 'u', 'u.usuario_id = ut.usuario_id');
	  $sql->esqUnir('contatos', 'c', 'u.usuario_contato = c.contato_id');
	  $sql->adCampo('ut.usuario_id,  u.usuario_login, tarefa_dono, contato_email, ut.perc_designado, contato_posto, contato_nomeguerra');
	  $sql->adOnde('ut.tarefa_id = '.(int)$linha['tarefa_id']);
	  $sql->adOrdem('perc_designado desc, contato_nomeguerra');
	  if ($baseline_id){
	     $sql->adOnde('ut.baseline_id='.(int)$baseline_id);
	    $sql->adOnde('t.baseline_id='.(int)$baseline_id);
	    }
	  $valor=$sql->Lista();
	  $sql->limpar();

	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_depts', 't');
	  $sql->esqUnir('depts', 'd', 't.departamento_id = d.dept_id');
	  $sql->adCampo('DISTINCT dept_id, dept_nome');
	  $sql->adOnde('t.tarefa_id = '.(int)$linha['tarefa_id']);
	  if ($baseline_id)  $sql->adOnde('t.baseline_id='.(int)$baseline_id);
	  $depts = $sql->Lista();
	  $sql->limpar();

	  foreach ($depts as $departamento){
	    $tarefas_dep[$linha['tarefa_id']][$departamento['dept_id']]=1;
	    if (!array_key_exists($departamento['dept_id'], $departamentos)) {
	      $departamentos[$departamento['dept_id']]=$departamento['dept_nome'];
	      }
	    }
	  foreach ($valor as $tarefa){
	    $tarefas[$linha['tarefa_id']][$tarefa['usuario_id']]=1;
	    $lista_tarefas[$linha['tarefa_id']] = $linha;
	    if (!array_key_exists($tarefa['usuario_id'], $usuarios)) $usuarios[$tarefa['usuario_id']]=$tarefa['contato_posto'].' '.$tarefa['contato_nomeguerra'];
	    if (!array_key_exists($tarefa['tarefa_dono'], $usuarios)) $usuarios[$tarefa['tarefa_dono']]=nome_usuario($tarefa['tarefa_dono']);
	    $tarefas[$linha['tarefa_id']][$tarefa['tarefa_dono']]=1;
	    }

	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas');
	  $sql->adCampo('count(DISTINCT tarefa_id) as subordinada');
	  $sql->adOnde('tarefa_superior = '.(int)$linha['tarefa_id']);
	  $sql->adOnde('tarefa_id != tarefa_superior');
	  if ($baseline_id)  $sql->adOnde('baseline_id='.(int)$baseline_id);
	  $linha['subordinada'] = $sql->Resultado();
	  $sql->limpar();

	  $linha['style'] = tarefaEstilo_pd($linha);
	  $i = (isset($projetos[$linha['tarefa_projeto']]['tarefas']) ? count($projetos[$linha['tarefa_projeto']]['tarefas']) : 0) + 1;
	  $linha['tarefa_number'] = $i;
	  $linha['node_id'] = 'node_'.$i.'-'.$linha['tarefa_id'];
	  if (strpos($linha['tarefa_duracao'], '.') && $linha['tarefa_duracao_tipo'] == 1) $linha['tarefa_duracao'] = floor($linha['tarefa_duracao']).':'.round(60 * ($linha['tarefa_duracao'] - floor($linha['tarefa_duracao'])));
	  $projetos[$linha['tarefa_projeto']]['tarefas'][] = $linha;
	  }
	$mostrarCaixachecarEditar = isset($podeEditarTarefas) && $podeEditarTarefas || $Aplic->checarModulo('admin', 'acesso');
	$tipoDuracao = getSisValor('TipoDuracaoTarefa');
	$tempoTarefa = new CTarefa();
	$usuarioDesig = $tempoTarefa->getDesignacao('usuario_id');

	if (count($usuarios)){
	  $saida.= '<tr><td>';
	  $saida.= '<table width=1060>';
	  $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. QUEM</b></font><br>&nbsp;</td></tr>';
	  $saida.= '<tr><td align="left"><table width=1060 cellspacing=0 cellpadding=0 border=0><tr><td></td>';
	  $col=-1;
	  foreach ($usuarios as $usuario){
	    $col++;
	    $saida.= '<td valign="bottom" align="center" style="border-color:#000000;border-style:solid; border-width:2px 2px 0px '.(!$col ? '2' : '0').'px;padding:1px;line-height:10px">';
	    for ($i=0; $i< strlen($usuario); $i++) {
	      if (isset($usuario[($i+1)]) && ($usuario[($i+1)] =='º' || $usuario[($i+1)]=='ª')) $saida.= $usuario[$i].$usuario[(++$i)].'<br>';
	      else $saida.= $usuario[$i].'<br>';
	      }
	    $saida.= '</td>';
	    }
	  $saida.= '</tr>';
	  $linha=-1;
	  $projeto_corrente = 0;
	  foreach ($lista_tarefas as $tarefa){
	    $linha++;
	    $tarefa_id = $tarefa['tarefa_id'];
	    if($portfolio_lista && $projeto_corrente != $tarefa['tarefa_projeto']){
	      $projeto_corrente = $tarefa['tarefa_projeto'];
	      $saida.= '<tr><td colspan=100 style="font-weight: bold;background-color: #F2F0EC;border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 2px;">'.$projetos[$projeto_corrente]['projeto_nome'].'</td></tr>';
	      }
	    $saida.= '<tr><td style="border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 2px;">'.$tarefa['tarefa_nome'].'</td>';
	    foreach ($usuarios as $usuario_id => $usuario)  $saida.= (isset($tarefas[$tarefa_id][$usuario_id]) && $tarefas[$tarefa_id][$usuario_id] ? '<td align=center style="border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 0px;background-color:#000000; color:#000000;">&#9608;</td>': '<td style="border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 0px;">&nbsp;</td>');
	    $saida.= '</tr>';
	    }
	  $saida.= '</table></td></tr></table></td></tr>';
	  }

	if (count($departamentos)){
	  $saida.= '<tr><td>';
	  $saida.= '<table width=1060>';
	  $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. '.strtoupper($config['departamentos']).' ENVOLVID'.strtoupper($config['genero_dept']).'S NAS ATIVIDADES</b></font><br>&nbsp;</td></tr>';
	  $saida.= '<tr><td align="left"><table width=1060 cellspacing=0 cellpadding=0 border=0 ><tr><td></td>';
	  $col=-1;
	  foreach ($departamentos as $departamento){
	    $col++;
	    $saida.= '<td valign="bottom" align="center" style="border-color:#000000;border-style:solid; border-width:2px 2px 0px '.(!$col ? '2' : '0').'px;padding:1px;line-height:10px">';
	    for ($i=0; $i< strlen($departamento); $i++) {
	      if (isset($departamento[($i+1)]) && ($departamento[($i+1)] =='º' || $departamento[($i+1)]=='ª')) $saida.= $departamento[$i].$departamento[(++$i)].'<br>';
	      else $saida.= $departamento[$i].'<br>';
	      }
	    $saida.= '</td>';
	    }
	  $saida.= '</tr>';
	  $linha=-1;
	  $projeto_corrente = 0;
	  foreach ($lista_tarefas as $tarefa){
	    $linha++;
	    if($portfolio_lista && $projeto_corrente != $tarefa['tarefa_projeto']){
	      $projeto_corrente = $tarefa['tarefa_projeto'];
	      $saida.= '<tr><td colspan=100 style="font-weight: bold;background-color: #F2F0EC;border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 2px;">'.$projetos[$projeto_corrente]['projeto_nome'].'</td></tr>';
	      }
	    $tarefa_id = $tarefa['tarefa_id'];
	    $saida.= '<tr><td style="border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 2px;">'.$tarefa['tarefa_nome'].'</td>';
	    foreach ($departamentos as $dept_id => $departamento)  $saida.= (isset($tarefas_dep[$tarefa_id][$dept_id]) && $tarefas_dep[$tarefa_id][$dept_id] ? '<td align=center style="border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 0px;background-color:#000000; color:#000000;">&#9608;</td>': '<td style="border-color:#000000;border-style:solid; border-width:'.(!$linha ? '2' : '0').'px 2px 2px 0px;">&nbsp;</td>');
	    $saida.= '</tr>';
	    }
	  $saida.= '</table></td></tr></table></td></tr>';
	}

	$custo_estimado=floatval($objp->custo_estimado());
	$gasto_efetuado=floatval($objp->gasto_efetuado());
	$gasto_registro=floatval($objp->gasto_registro());


	$subnumero=0;
	if ($custo_estimado > 0 || $gasto_efetuado > 0 || $gasto_registro > 0 ) $saida.= '<tr><td><table width=1060><tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. QUANTO</b></font></td></tr></table></td></tr>';

	if ($objp->projeto_meta_custo > 0)  {
		$saida.= '<tr><td><table width=1060><tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.$numero.'.'.(++$subnumero).'. ESTIMATIVA INICIAL</b></font></td></tr>';
		$saida.= '<tr><td align="left" style="padding-top: 5px;">'.$config['simbolo_moeda'].' '.number_format($objp->projeto_meta_custo, 2, ',', '.').'</td></tr></table></td></tr>';
		}

	if ($custo_estimado > 0){
	  $saida.= '<tr><td>';
	  $saida.= '<table width=1060><tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.$numero.'.'.(++$subnumero).'. CUSTOS PLANEJADOS</b></font></td></tr>';
	  $saida.= '<tr><td align="left">';
	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_custos', 't');
	  $sql->adCampo('t.*, ((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	  $sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas','tarf','tarf.tarefa_id=t.tarefa_custos_tarefa');
	  $sql->adCampo('tarf.tarefa_nome');
	  if($portfolio_lista){
	    $sql->adCampo('tarf.tarefa_projeto');
	    $sql->adOnde('t.tarefa_custos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto IN('.$portfolio_lista.'))');
	    }
	  else $sql->adOnde('t.tarefa_custos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$projeto_id.')');
	  $sql->adOrdem(($portfolio_lista?'tarf.tarefa_projeto,':'').'tarefa_custos_tarefa, tarefa_custos_ordem');
	  if ($baseline_id)  $sql->adOnde('baseline_id='.(int)$baseline_id);
		if ($Aplic->profissional && $config['aprova_custo']) $sql->adOnde('tarefa_custos_aprovado = 1');
	  $linhas= $sql->Lista();
	  $sql->limpar();

	  $qnt=0;
	  $saida.= '<table width=1060 align="center" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
	  $saida.= '<tr><th>Nome</th><th>Descrição</th><th>Unidade</th><th>Qnt.</th><th>Valor ('.$config['simbolo_moeda'].')</th><th>ND</th><th>Total ('.$config['simbolo_moeda'].')</th><th>Responsável</th></tr>';
	  $total=0;
	  $custo=array();
	  $tarefa=0;
	  $projeto_corrente = 0;
	  foreach ($linhas as $dado) {
	    if($portfolio_lista && $projeto_corrente != $dado['tarefa_projeto']){
	      $projeto_corrente = $dado['tarefa_projeto'];
	      $saida.= '<tr><td colspan=100 style="font-weight: bold;background-color: #F2F0EC;">'.$projetos[$projeto_corrente]['projeto_nome'].'</td></tr>';
	      }
	    if ($tarefa!=$dado['tarefa_custos_tarefa']){
	      $saida.= '<tr><td align="left" colspan="8"><i>'.$dado['tarefa_nome'].'</i></td></tr>';
	      $tarefa=$dado['tarefa_custos_tarefa'];
	      $qnt=0;
	      }
	    $saida.= '<tr align="center"><td align="left">'.++$qnt.' - '.$dado['tarefa_custos_nome'].'</td>';
	    $saida.= '<td align="left">'.($dado['tarefa_custos_descricao'] ? $dado['tarefa_custos_descricao'] :'&nbsp;').'</td>';
	    $saida.= '<td>'.(isset($unidade[$dado['tarefa_custos_tipo']]) ? $unidade[$dado['tarefa_custos_tipo']] : '&nbsp;').'</td>';
	    $saida.= '<td>'.$dado['tarefa_custos_quantidade'].'</td>';
	    $saida.= '<td align="right">'.number_format($dado['tarefa_custos_custo'], 2, ',', '.').'</td>';
	    $saida.= '<td>'.dica('Natureza da Despesa', (isset($nd[$dado['tarefa_custos_nd']]) ? $nd[$dado['tarefa_custos_nd']] : '')).$dado['tarefa_custos_nd'].dicaF().'</td>';
	    $saida.= '<td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td>';
	    $saida.= '<td align="left" >'.nome_usuario($dado['tarefa_custos_usuario'],'','','esquerda').'</td></tr>';
	    if (isset($custo[$dado['tarefa_custos_nd']])) $custo[$dado['tarefa_custos_nd']] += (float)$dado['valor'];
	    else $custo[$dado['tarefa_custos_nd']] = (float)$dado['valor'];
	    $total+=$dado['valor'];
	    }
	  if ($qnt) {
	    if ($total) {
	      $saida.= '<tr><td colspan="6" class="std" align="right">';
	      foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.(isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND');
	      $saida.= '<br><b>Total</b></td><td align="right">';
	      foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.number_format($somatorio, 2, ',', '.');
	      $saida.= '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="2">&nbsp;</td></tr>';
	      }
	    }
	  else $saida.= '<tr><td colspan="8" class="std" align="left"><b>Nenhum item encontrado.</b></td></tr>';
	  $saida.= '</table></td></tr></table></td></tr>';
	  }

	if ($gasto_efetuado > 0){
	  $saida.= '<tr><td>';
	  $saida.= '<table width=1060>';
	  $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.$numero.'.'.(++$subnumero).'. CUSTOS EFETIVADOS</b></font></td></tr>';
	  $saida.= '<tr><td align="left">';
	  $sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_gastos', 't');
	  $sql->adCampo('t.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	  $sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas','tarf','tarf.tarefa_id=t.tarefa_gastos_tarefa');
	  if ($Aplic->profissional && $config['aprova_gasto']) $sql->adOnde('tarefa_gastos_aprovado = 1');
	  $sql->adCampo('tarf.tarefa_nome');
	  if($portfolio_lista){
	    $sql->adCampo('tarf.tarefa_projeto');
	    $sql->adOnde('t.tarefa_gastos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto IN('.$portfolio_lista.'))');
	    }
	  else $sql->adOnde('t.tarefa_gastos_tarefa IN (select tarefa_id from tarefas WHERE tarefa_projeto='.$projeto_id.')');
	  if ($baseline_id)  $sql->adOnde('baseline_id='.(int)$baseline_id);
	  $sql->adOrdem(($portfolio_lista?'tarf.tarefa_projeto,':'').'tarefa_gastos_tarefa, tarefa_gastos_ordem');
	  $linhas= $sql->Lista();
	  $sql->limpar();
	  $qnt=0;
	  $saida.= '<table width=1060 align="center"  border=0 cellpadding="2" cellspacing=0 class="tbl1">';
	  $saida.= '<tr><th>Nome</th><th>Descrição</th><th>Unidade</th><th>Qnt.</th><th>Valor ('.$config['simbolo_moeda'].')</th><th>ND</th><th>Total ('.$config['simbolo_moeda'].')</th><th>Responsável</th></tr>';
	  $total=0;
	  $custo=array();
	  $tarefa=0;
	  $projeto_corrente = 0;
	  foreach ($linhas as $dado) {
	    if($portfolio_lista && $projeto_corrente != $dado['tarefa_projeto']){
	      $projeto_corrente = $dado['tarefa_projeto'];
	      $saida.= '<tr><td colspan=100 style="font-weight: bold;background-color: #F2F0EC;">'.$projetos[$projeto_corrente]['projeto_nome'].'</td></tr>';
	      }
	    if ($tarefa!=$dado['tarefa_gastos_tarefa']){
	      $saida.= '<tr><td align="left" colspan="8"><i>'.$dado['tarefa_nome'].'</i></td></tr>';
	      $tarefa=$dado['tarefa_gastos_tarefa'];
	      $qnt=0;
	      }
	    //if (isset($dado['tarefa_gastos_data_inicio'])) $data = new CData($dado['tarefa_gastos_data_inicio']);
	    $saida.= '<tr align="center">';
	    $saida.= '<td align="left">'. ++$qnt . ' - '.$dado['tarefa_gastos_nome'].'</td>';
	    $saida.= '<td align="left">'.$dado['tarefa_gastos_descricao'].'</td>';
	    $saida.= '<td>'.$unidade[$dado['tarefa_gastos_tipo']].'</td>';
	    $saida.= '<td>'.$dado['tarefa_gastos_quantidade'].'</td>';
	    $saida.= '<td align="right">'.number_format($dado['tarefa_gastos_custo'], 2, ',', '.').'</td>';
	    $saida.= '<td>'.$dado['tarefa_gastos_nd'].'</td>';
	    $saida.= '<td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td>';
	    $saida.= '<td align="left">'.nome_usuario($dado['tarefa_gastos_usuario'],'','','esquerda').'</td>';
	    $saida.= '</tr>';

	    if (isset($custo[$dado['tarefa_gastos_nd']])) $custo[$dado['tarefa_gastos_nd']] += (float)$dado['valor'];
	    else $custo[$dado['tarefa_gastos_nd']] = (float)$dado['valor'];
	    $total+=$dado['valor'];
	    }

	  if ($qnt) {
	    if ($total) {
	      $saida.= '<tr><td colspan="6" class="std" align="right">';
	      foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.(isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND');
	      $saida.= '<br><b>Total</b></td><td align="right">';
	      foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) $saida.= '<br>'.number_format($somatorio, 2, ',', '.');
	      $saida.= '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="2">&nbsp;</td></tr>';
	      }
	    }
	  else $saida.= '<tr><td colspan="8" class="std" align="left"><b>Nenhum item encontrado.</b></td></tr>';
	  $saida.= '</table></td></tr></table></td></tr>';
	  }

	if ($objp->projeto_meta_custo > 0 || $gasto_efetuado > 0)  {
	  $per = 0;
	  if($gasto_efetuado > 0.00 && $objp->projeto_meta_custo > 0){
	    $per = ($gasto_efetuado / $objp->projeto_meta_custo) * 100;
	    }
	  $saida.= '<tr><td>';
	    $saida.= '<table width=1060>';
	      $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.$numero.'.'.(++$subnumero).'. ORÇADO x REALIZADO</b></font></td></tr>';
	      $saida.= '<tr><td>';
	        $saida.= '<table border=0 cellpadding=0 cellspacing=0 class="tbl1">';
	          $saida.= '<tr style="text-align: center;"><th>&nbsp;Orçado ('.$config['simbolo_moeda'].')&nbsp;</th><th>&nbsp;Realizado ('.$config['simbolo_moeda'].')&nbsp;</th><th>&nbsp;Variação ('.$config['simbolo_moeda'].')&nbsp;</th><th>&nbsp;%&nbsp;</th></tr>';
	          $saida.= '<tr style="text-align: center;">';
	            $saida.= '<td>&nbsp;'.number_format($objp->projeto_meta_custo, 2, ',', '.').'&nbsp;</td>';
	            $saida.= '<td>&nbsp;'.number_format($gasto_efetuado, 2, ',', '.').'&nbsp;</td>';
	            $saida.= '<td>&nbsp;'.number_format($objp->projeto_meta_custo - $gasto_efetuado, 2, ',', '.').'&nbsp;</td>';
	            $saida.= '<td>&nbsp;'.number_format($per, 2, ',', '.').'&nbsp;</td>';
	          $saida.= '</tr>';
	        $saida.= '</table>';
	      $saida.= '</td></tr>';
	    $saida.= '</table>';
	  $saida.= '</td></tr>';
	  }

	if (count($tarefas)){
	  $saida.= '<tr><td>';
	  $saida.= '<table width=1060><tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. CRONOGRAMA DAS ATIVIDADES</b></font><br>&nbsp;</td></tr>';
	  $saida.= '<tr><td>';
	  $saida.= '<table width=1060 class="prjImprimir" border="1" cellpadding="1" cellspacing=0>';
	  $saida.= '<tr><th>&nbsp;</th><th style="font-weight:bold; font-size:11px">Início</th><th style="font-weight:bold; font-size:11px">Término</th><th style="font-weight:bold; font-size:11px">Nome d'.$config['genero_tarefa'].' '.$config['tarefa'].'</th><th style="font-weight:bold; font-size:11px">Descrição</th><th style="font-weight:bold; font-size:11px">Responsável</th><th style="font-weight:bold; font-size:11px">Feito</th></tr>';
	  reset($projetos);
	  $projeto_corrente = 0;
	  foreach ($projetos as $k => $p) {
	    $tnums = count($p['tarefas']);
	    if($portfolio_lista && $projeto_corrente != $k){
	      $projeto_corrente = $k;
	      $saida.= '<tr><td colspan=100 style="font-weight: bold;background-color: #F2F0EC;">'.$p['projeto_nome'].'</td></tr>';
	      }
	    if ($tnums > 0 || $projeto_id == $p['projeto_id']) {
	      for ($i = 0; $i < $tnums; $i++) {
	        $t = $p['tarefas'][$i];
	        if ($t['tarefa_superior'] == $t['tarefa_id'] || !$t['tarefa_superior']) {
	          $saida.=mostrarTarefa_peg1($t, 0);
	          $saida.=acharSubordinada_peg1($p['tarefas'], $t['tarefa_id']);
	          }
	        }
	      }
	    }
	  $saida.= '</table><table width=1060><tr><td>&nbsp; &nbsp;</td><td style="border-style:solid;border-width:1px;background-color:#ffffff; color:#ffffff;">&#9608;</td><td>'.ucfirst($config['tarefa']).' futura</td><td>&nbsp; &nbsp;</td><td style="border-style:solid;border-width:1px;background-color:#e6eedd; color:#e6eedd;">&#9608;</td><td>Iniciada e dentro do prazo</td><td>&nbsp; &nbsp;</td><td style="border-style:solid;border-width:1px;background-color:#ffeebb; color:#ffeebb;">&#9608;</td><td>Deveria ter iniciada</td><td>&nbsp; &nbsp;</td><td style="border-style:solid;border-width:1px; background-color:#cc6666; color:#cc6666;">&#9608;</td><td>Em atraso</td><td>&nbsp; &nbsp;</td><td align=center style="border-style:solid;border-width:1px; background-color:#000000; color:#000000;">&#9608;</td><td>Feita</td></tr></table></td></tr></table></td></tr>';
	  }

	if($portfolio_lista){
	  $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. STATUS</b></font>';
	  foreach($projetos as $projeto){
	    $saida.= '<br>&nbsp;&nbsp;&nbsp;<font size=2>';
	    $saida.= $projeto['projeto_nome'];
	    $saida.= ':&nbsp;';
	    $saida.= (isset($projStatus[$projeto['projeto_status']]) ? $projStatus[$projeto['projeto_status']] : '');
	    $saida.= '</font>';
	  }
	  $saida.='</td></tr>';
	  }
	else $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. STATUS</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.(isset($projStatus[$obj->projeto_status]) ? $projStatus[$obj->projeto_status] : '').'</font></td></tr>';

	if($portfolio_lista) $obj->projeto_percentagem=portfolio_porcentagem($projeto_id);
	$saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. PROGRESSO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.number_format((float)$obj->projeto_percentagem, 2, ',', '.').'</font></td></tr>';

	if (count($tarefas)){
	  $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. GRÁFICO GANTT DO PROJETO</b></font></td></tr>';
	  if($portfolio_lista){
          $src = BASE_URL.($Aplic->pdf_print ? '/pdfimg.php' : '/index.php').'?m=projetos&a=gantt&sem_cabecalho=1&portfolio='.$projeto_id.'&mostrarLegendas=0&ordenarTarefasPorNome=0&mostrarInativo=1&mostrarTodoGantt=0&width=1060';
      }
	  else{
          $src = BASE_URL.($Aplic->pdf_print ? '/pdfimg.php' : '/index.php').'?m=tarefas&a=gantt&sem_cabecalho=1&mostrarLegendas=1&proFiltro=&mostrarInativo=1&mostrarTodoGantt=1&projeto_id='.$projeto_id.'&width=1060';
      }

	  $saida.= '<tr><td align="left"><img src="'.$src.'" alt=""></td></tr>';
	  }

	$nd=array(0 => '');
	$nd+= getSisValorND();
	$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
	$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
	$projeto = new CProjeto;

	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_log','tarefa_log');
	$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 't', 'tarefa_log_tarefa = t.tarefa_id');
	$sql->adCampo('tarefa_log.*, tarefa_nome, tarefa_id, tarefa_projeto');
	if($portfolio_lista) $sql->adOnde('tarefa_projeto IN ('.$portfolio_lista.')');
	else $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
	if ($baseline_id){
	   $sql->adOnde('tarefa_log.baseline_id='.(int)$baseline_id);
	  $sql->adOnde('t.baseline_id='.(int)$baseline_id);
	  }
	$sql->adOrdem(($portfolio_lista?'tarefa_projeto,':'').'tarefa_log_data');
	$logs = $sql->Lista();
	$sql->Limpar();
	$hrs = 0;
	$qnt=0;
	$custo=array();

	if (count($logs)){
	  $saida.= '<tr><td>';

	  $saida.= '<table width=1060>';
	  $saida.= '<tr><td align="left" style="padding-top: 5px;"><font size=2><b>'.++$numero.'. REGISTROS DAS TAREFAS</b></font></td></tr>';
	  $saida.= '<tr><td>';

	  $saida.= '<table width=1060 border=0 cellpadding="2" cellspacing=0 class="tbl1">';
	  $s = '<tr><th>Data</th><th>Tarefa</th><th>Ref.</th><th>Título</th><th>Responsável</th><th>Horas</th><th>Comentários</th><th>ND</th><th>Custos</th></tr>';
	  $projeto_corrente = 0;

	  foreach ($logs as $linha) {
	    $qnt++;
	    if($portfolio_lista && $projeto_corrente != $linha['tarefa_projeto']){
	      $projeto_corrente = $linha['tarefa_projeto'];
	      $s .= '<tr><td colspan=100 style="font-weight: bold;background-color: #F2F0EC;">'.$projetos[$projeto_corrente]['projeto_nome'].'</td></tr>';
	      }
	    $tarefa_log_data = intval($linha['tarefa_log_data']) ? new CData($linha['tarefa_log_data']) : null;
	    $estilo = $linha['tarefa_log_problema'] ? 'background-color:#cc6666;color:#ffffff' : '';
	    $s .= '<tr bgcolor="white" valign="top">';
	    $s .= '<td>'.($tarefa_log_data ? $tarefa_log_data->format($df) : '&nbsp;').'</td>';
	    $s .= '<td>'.nome_tarefa($linha['tarefa_id']).'</td>';
	    $imagem_referencia = '-';
	    if ($linha['tarefa_log_referencia'] > 0) {
	      if (isset($RefRegistroTarefaImagem[$linha['tarefa_log_referencia']])) $imagem_referencia = imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']], imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']]).' '.$RefRegistroTarefa[$linha['tarefa_log_referencia']], 'Forma pela qual foram obtidos os dados para efetuar este registro de trabalho.');
	      elseif (isset($RefRegistroTarefa[$linha['tarefa_log_referencia']])) $imagem_referencia = $RefRegistroTarefa[$linha['tarefa_log_referencia']];
	      }
	    $s .= '<td align="center" valign="middle">'.$imagem_referencia.'</td>';
	    $s .= '<td style="'.$estilo.'">'.$linha['tarefa_log_nome'].'</td>';
	    $s .= '<td>'.nome_usuario($linha['tarefa_log_criador'],'','','esquerda').'</td>';
	    $s .= '<td align="right">'.sprintf('%.2f', $linha['tarefa_log_horas']).'</td>';
	    $s .= '<td>'.str_replace("\n", '<br />', $linha['tarefa_log_descricao']).'</td>';
	   	$nd=($linha['tarefa_log_categoria_economica'] && $linha['tarefa_log_grupo_despesa'] && $linha['tarefa_log_modalidade_aplicacao'] ? $linha['tarefa_log_categoria_economica'].'.'.$linha['tarefa_log_grupo_despesa'].'.'.$linha['tarefa_log_modalidade_aplicacao'].'.' : '').$linha['tarefa_log_nd'];
	    $s .= '<td align="center" valign="middle">'.($linha['tarefa_log_custo']!=0 ? $nd : '&nbsp;').'</td>';
	    $s .= '<td align="right">'.number_format($linha['tarefa_log_custo'], 2, ',', '.').'</td>';
	    $s .= '</tr>';
	    $hrs += (float)$linha['tarefa_log_horas'];
	    if (isset($custo[$nd])) $custo[$nd] += (float)$linha['tarefa_log_custo'];
	    else $custo[$nd] = (float)$linha['tarefa_log_custo'];
	    }
	  if (!$qnt) $s = '<tr><td bgcolor="white"><p>Nenhum registro de '.$config['tarefa'].' encontrado.</p></td></tr></table>';
	  else {
	    $s .= '<tr bgcolor="white" valign="top">';
	    $s .= '<td colspan="5" align="right" valign="middle"><b>Total de Horas:</b></td>';
	    $minutos = (int)(($hrs - ((int)$hrs)) * 60);
	    $minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	    $s .= '<td align="right" valign="middle"><b>'.(int)$hrs.':'.$minutos.'</b></td>';
	    $s .= '<td align="right" colspan="2"><b>Custos</b>';
	    foreach ($custo as $nd => $somatorio) {
	      if ($somatorio > 0) $s .= '<br>'.$nd;
	      }
	    $s .= '<br><b>Total Geral</b>';
	    $s .='</td>';
	    $s .= '<td align="right">';
	    $somatorio_total=0;
	    foreach ($custo as $nd => $somatorio) {
	      if ($somatorio > 0) $s .= '<br>'.number_format($somatorio, 2, ',', '.');
	      $somatorio_total+=$somatorio;
	      }
	    $s .= '<br><b>'.number_format($somatorio_total, 2, ',', '.').'</b></td>';
	    $s .= '</tr>';
	    $s .= '</table>';
	    $s .= '<table width=1060>';
	    $s .= '<tr><td>&nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;Legenda</td><td>&nbsp; &nbsp;</td><td bgcolor="#ffffff" style="border-style:solid;  border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>Registro Normal&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td bgcolor="#cc6666" style="border-style:solid;  border-width:1px 1px 1px 1px;">&nbsp; &nbsp;</td><td>Registro de Problema</td></tr>';
	    $s .= '</table>';
	    }
	  $saida.= $s;
	  $saida.= '</td></tr></table>';
	  }

	//assinatura
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'projetos','projetos');
	$sql->esqUnir('usuarios','u2','u2.usuario_id=projetos.projeto_responsavel');
	$sql->esqUnir('contatos','c2','c2.contato_id=u2.usuario_contato');
	$sql->adCampo('concatenar_tres(c2.contato_posto, \' \', c2.contato_nomeguerra) AS nome_gerente, contato_nomecompleto, contato_posto');
	$sql->adOnde('projeto_id='.$projeto_id);
	if ($baseline_id)  $sql->adOnde('baseline_id='.(int)$baseline_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$saida.= '<tr><td style="height:30px;">&nbsp;</td></tr>';
	$data = new CData();

	$saida.= '<tr><td align="center">'.($dados_projeto['cia_cidade'] ? $dados_projeto['cia_cidade'].', ' : '').retorna_data_extenso($data->format(FMT_DATA_MYSQL)).'</td></tr>';
	if ($linha['nome_gerente']){
	  $saida.= '<tr><td style="height:50px;">&nbsp;</td></tr>';
	  $saida.= '<tr><td align="center">__________________________________________</td></tr>';
	  $saida.= '<tr><td align="center">'.($linha['contato_nomecompleto'] ? $linha['contato_nomecompleto'].($linha['contato_posto'] ? ' - '.$linha['contato_posto']:''): $linha['nome_gerente']).'</td></tr>';
	  $saida.= '<tr><td align="center">'.ucfirst($config['gerente']).' d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).'</td></tr>';
	  }

	if (!$Aplic->profissional && $config['barra_projeto'] && $codigo) $saida.= '<tr><td><table width=1060><tr><td align=center><img src="'.BASE_URL.'/lib/barras/barcode.php?quality=75&barcode='.$codigo.'\" alt=""></td></tr></table></td></tr>';
	$saida.= '</table>';

	if(!$Aplic->pdf_print) $saida.= '<script type="text/javascript">self.print();</script>';


	return $saida;
	}



function mostrarTarefa_peg1(&$a, $nivel = 0, $visao_hoje = false) {
	global $Aplic, $config, $done, $texto_consulta, $tipoDuracao, $usuarioDesig, $mostrarCaixachecarEditar;
	global $tarefa_acesso, $tarefa_prioridade;
	$tipos = getSisValor('TipoTarefa');
	$agora = new CData();
	$tf = $Aplic->getPref('formatohora');
	$df = '%d/%m/%Y';
	$fdf = $df.' '.$tf;
	$done[] = $a['tarefa_id'];
	$data_inicio = intval($a['tarefa_inicio']) ? new CData($a['tarefa_inicio']) : null;
	$data_fim = intval($a['tarefa_fim']) ? new CData($a['tarefa_fim']) : null;
	$ultima_atualizacao = isset($a['last_update']) && intval($a['last_update']) ? new CData($a['last_update']) : null;
	$sinal = 1;
	$estilo = '';
	if ($data_inicio && !$data_fim) $data_fim = new CData();
	$dias = $data_fim ? $agora->dataDiferenca($data_fim) * $sinal : null;
	if ($agora->after($data_inicio) && $a['tarefa_percentagem'] == 0 && $agora->before($data_fim)) $estilo = 'background-color:#ffeebb';
	else if ($agora->after($data_inicio) && $a['tarefa_percentagem'] < 100 && $agora->before($data_fim)) $estilo = 'background-color:#e6eedd';
	else if ($a['tarefa_percentagem'] == 100) $estilo = 'background-color:#aaddaa; color:#00000';
	else if ($agora->after($data_fim) && $a['tarefa_percentagem'] < 100 ) $estilo = 'background-color:#cc6666;color:#ffffff';
	if ($agora->after($data_fim)) $sinal = -1;
	$dias = $agora->dataDiferenca($data_fim)*$sinal;
	$s = '<tr>';
  $s .= '<td nowrap="nowrap" align="center" style="'.$estilo.'">&nbsp;&nbsp;</td>';
	$s .= '<td nowrap="nowrap" align="center" style="font-size:11px" >'.($data_inicio ? $data_inicio->format($df ) : '&nbsp;').'</td>';
	$s .= '<td nowrap="nowrap" align="center" style="font-size:11px" >'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td>';
	$s .= '<td style="font-size:11px">';
	for ($y = 0; $y < $nivel; $y++) {
		if ($y + 1 == $nivel) $s .= '<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0 alt="">';
		else $s .= '<img src="'.acharImagem('shim.gif').'" width="16" height="12" border=0 alt="">';
		}
	$alt = $a['tarefa_descricao'];
	$alt = str_replace('"', "&quot;", $alt);
	$alt = str_replace("\n\r", '<br>', $alt);
	$alt = str_replace("\r\n", '<br>', $alt);
	$alt = str_replace("\r", '<br>', $alt);
	$alt = str_replace("\n", '<br>', $alt);
	if (!$alt)$alt ='&nbsp;';
	$abrir_link = imagem('icones/colapsar.gif');
	if ($a['tarefa_marco'] > 0) $s .= '&nbsp;<b>'.$a["tarefa_nome"].'</b><img src="'.acharImagem('icones/marco.gif').'" border=0 alt="">';
	elseif ($a['tarefa_dinamica'] == '1') $s .= $abrir_link.'<b>'.$a['tarefa_nome'].'</b>';
	else $s .= $a['tarefa_nome'];
	$s .='</td>';
	$s .= '<td style="font-size:11px" align="left" width="400">'.$alt.'</td>';
	$s .= '<td style="font-size:11px" align="left" >'.($a['contato_posto']||$a['contato_nomeguerra']? $a['contato_posto'].' '.$a['contato_nomeguerra'] : '&nbsp;').'</td>';
	$s .= '<td style="font-size:11px" align="left">'.intval($a['tarefa_percentagem']).'%</td>';
	$s .= '</tr>';
	return $s;
	}


function acharSubordinada_peg1(&$tarr, $superior, $nivel = 0) {
	global $projetos, $saida;
	$nivel = $nivel + 1;
	$n = count($tarr);
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['tarefa_superior'] == $superior && $tarr[$x]['tarefa_superior'] != $tarr[$x]['tarefa_id']) {
			mostrarTarefa_peg1($tarr[$x], $nivel);
			acharSubordinada_peg1($tarr, $tarr[$x]['tarefa_id'], $nivel);
			}
		}
	}


?>