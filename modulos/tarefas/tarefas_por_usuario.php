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

$Aplic->carregarCalendarioJS();
if (!$Aplic->checarModulo('tarefas', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');

if (!$dialogo) $Aplic->salvarPosicao();


$painel_filtro = $Aplic->getEstado('painel_filtro') !== null ? $Aplic->getEstado('painel_filtro') : 0;


if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = ($Aplic->getEstado('usuario_id') ? $Aplic->getEstado('usuario_id') : $Aplic->usuario_id);

$fazer_relatorio = getParam($_REQUEST, 'fazer_relatorio', false);
$reg_data_inicio = getParam($_REQUEST, 'reg_data_inicio', 0);
$reg_data_fim = getParam($_REQUEST, 'reg_data_fim', 0);
$log_tudo = getParam($_REQUEST, 'log_tudo', true);
$ver_todos_projetos = getParam($_REQUEST, 'ver_todos_projetos', true);
$usar_periodo = getParam($_REQUEST, 'usar_periodo', 0);
$max_niveis = getParam($_REQUEST, 'max_nivels', 'max');
$log_filtroUsuario = getParam($_REQUEST, 'log_filtroUsuario', '');
$cia_id = getParam($_REQUEST, 'cia_id', 0);
$projeto_id = getParam($_REQUEST, 'projeto_id', 0);

$ver_todos_projetos = true;
$df = '%d/%m/%Y'; 

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input type="hidden" name="a" value="tarefas_por_usuario" />';
echo '<input type="hidden" name="fazer_relatorio" id="fazer_relatorio" value="" />';
echo '<input type="hidden" name="projeto_id" value="'.$projeto_id.'" />';

if ($Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Lista de '.ucfirst($config['tarefas']).' por '.ucfirst($config['usuario']).' Designado', 'tarefa.png', $m, $m.'.'.$a);

	$saida='<div id="filtro_container" style="border: 1px solid #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; margin-bottom: 2px; -webkit-border-radius: 4px; border-radius:4px; -moz-border-radius: 4px;">';
  $saida.=dica('Filtros e Ações','Clique nesta barra para esconder/mostrar os filtros e as ações permitidas.').'<div id="filtro_titulo" style="background-color: #'.($estilo_interface=='metro' ? '006fc2' : 'a6a6a6').'; font-size: 8pt; font-weight: bold;" onclick="$jq(\'#filtro_content\').toggle(); xajax_painel_filtro(document.getElementById(\'filtro_content\').style.display);"><a class="aba" href="javascript:void(0);">'.imagem('icones/tarefa_p.gif').'&nbsp;Filtros e Ações</a></div>'.dicaF();
  $saida.='<div id="filtro_content" style="display:'.($painel_filtro ? '' : 'none').'">';
  $saida.='<table cellspacing=0 cellpadding=0>';
	$vazio='<tr><td colspan=2>&nbsp;</td></tr>';

	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td></td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica('Designado', 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').'Designado:'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$filtro_projeto='<tr><td align=right>'.dica(ucfirst($config['projeto']), 'Filtrar por '.($config['genero_projeto']=='o' ? 'um' : 'uma').' '.$config['projeto'].' específic'.$config['genero_projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><input type="text" id="projeto_nome" style="width:250px;" name="projeto_nome" value="'.nome_projeto($projeto_id).'" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';

	$botoesTitulo->adicionaBotao('m=tarefas', 'lista de  '.$config['tarefas'], '', 'Lista de '.ucfirst($config['tarefa']), 'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' dos divers'.$config['genero_projeto'].'s '.$config['projetos'].'.');

	$saida.='<tr><td><table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$filtro_projeto.'</table></td></tr></table>';
	$saida.= '</div></div>';
	$botoesTitulo->adicionaCelula($saida);
	$botoesTitulo->mostrar();
	$saida='';
	}
elseif (!$Aplic->profissional){
	$botoesTitulo = new CBlocoTitulo('Lista de '.ucfirst($config['tarefas']).' por '.ucfirst($config['usuario']).' Designado', 'tarefa.png', $m, $m.'.'.$a);
	$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td></td></tr>';
	$procurar_usuario='<tr><td align=right>'.dica('Designado', 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').'Designado:'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
	$filtro_projeto='<tr><td align=right>'.dica(ucfirst($config['projeto']), 'Filtrar por '.($config['genero_projeto']=='o' ? 'um' : 'uma').' '.$config['projeto'].' específic'.$config['genero_projeto'].'.').ucfirst($config['projeto']).':'.dicaF().'</td><td align="left"><input type="text" id="projeto_nome" style="width:250px;" name="projeto_nome" value="'.nome_projeto($projeto_id).'" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popProjeto();">'.imagem('icones/projeto_p.gif','Selecionar '.ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para selecionar um'.($config['genero_projeto']=='a' ? 'a' : '').' '.$config['projeto'].'.').'</a></td></tr>';
	$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.$filtro_projeto.'</table>');
	$botoesTitulo->adicionaBotao('m=tarefas', 'lista de  '.$config['tarefas'], '', 'Lista de '.ucfirst($config['tarefa']), 'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' dos divers'.$config['genero_projeto'].'s '.$config['projetos'].'.');
	$botoesTitulo->mostrar();
	}

$ver_min = false;

if (!$dialogo) $Aplic->salvarPosicao();


if($Aplic->profissional){
    $Aplic->carregarComboMultiSelecaoJS();
	}

$tipoDuracao = getSisValor('TipoDuracaoTarefa');
$prioridadeTarefa = getSisValor('PrioridadeTarefa');
$cabecalho_tabela = '';
$linhas_tabela = '';
$data_inicio = intval($reg_data_inicio) ? new CData($reg_data_inicio) : new CData(date('Y').'-01-01');
$data_fim = intval($reg_data_fim) ? new CData($reg_data_fim) : new CData(date('Y').'-12-31');
$agora = new CData();
if (!$reg_data_inicio) $data_inicio->subtrairIntervalo(new Data_Intervalo('14,0,0,0'));
$data_fim->setTime(23, 59, 59);
$tempoTarefa = new CTarefa();
$usuarioDesig = $tempoTarefa->getDesignacao('usuario_id', null, true);



echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding="3" class="std"><tr><td><table>';
echo '<tr><td align="left" nowrap="nowrap">';
echo (isset($saida) ? $saida.'<br>': '').dica('Data Inicial', 'Digite ou escolha no calendário a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atribuídas a '.$config['usuarios'].'.').'De:'.dicaF().'<input type="hidden" name="reg_data_inicio" id="reg_data_inicio" value="'.($data_inicio ? $data_inicio->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\');" value="'.($data_inicio ? $data_inicio->format($df) : '').'" class="texto" size="9" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de início da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);"><img id="f_btn1" src="'.acharImagem('calendario.gif').'" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().dica('Data Final', 'Digite ou escolha no calendário a data final da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atribuídas a '.$config['usuarios'].'.').'Até:'.dicaF().'<input type="hidden" name="reg_data_fim" id="reg_data_fim" value="'.($data_fim ? $data_fim->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\');" value="'.($data_fim ? $data_fim->format($df) : '').'" class="texto" size="9" />'.dica('Data Final', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término da pesquisa d'.$config['genero_tarefa'].'s '.$config['tarefas'].' atribuídas a '.$config['usuarios'].'.').'<a href="javascript: void(0);"><img id="f_btn2" src="'.acharImagem('calendario.gif').'" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF();

echo '<input type="hidden" name="log_filtroUsuario" id="log_filtroUsuario" value="'.$usuario_id.'" />';
echo dica('Filtro por '.ucfirst($config['projeto']), 'Selecione na caixa à direita para qual '.$config['projeto'].' deseja visualizar os resultados.').' '.ucfirst($config['projeto']).':'.dicaF();
echo '</td></tr><tr><td>';
echo '&nbsp;&nbsp;<input type="checkbox" name="usar_periodo" id="usar_periodo" '.($usar_periodo ? 'checked="checked"':'').' />'.dica('Usar o Período', 'Selecione esta caixa para exibir o resultado da pesquisa na faixa de tempo selecionada.').'<label for="usar_periodo">Usar o período</label>'.dicaF();
echo '</td></tr></table></td>';
echo '<td align="right" width="120">'.botao('exibir', 'Exibir', 'Exibir o resultado da pesquisa.','','env.fazer_relatorio.value=1; env.submit()').'</td>';
echo '</tr></table></form>';



if ($fazer_relatorio) {
	echo estiloFundoCaixa();
	echo '<br />';
	echo estiloTopoCaixa();
	$usuario_lista = getListaUsuariosaLinha();
	$ss = '\''.$data_inicio->format(FMT_TIMESTAMP_MYSQL).'\'';
	$se = '\''.$data_fim->format(FMT_TIMESTAMP_MYSQL).'\'';
	$and = false;
	$onde = false;


	$q = new BDConsulta;
	$q->adTabela('projetos', 'projetos');
	$q->adCampo('projeto_id');
	if ($cia_id)	$q->adOnde('projeto_cia = '.(int)$cia_id);
	if ($projeto_id)	$q->adOnde('projeto_id = '.(int)$projeto_id);
	$projetos = $q->listaVetorChave('projeto_id','projeto_id');
	$q->limpar();


	$q->adTabela('tarefas', 't');
	$q->esqUnir('projetos', 'pr', 'pr.projeto_id = t.tarefa_projeto');
	$q->esqUnir('tarefa_designados', 'ut', 't.tarefa_id = ut.tarefa_id');
	$q->adCampo('DISTINCT t.tarefa_id');
	$q->adOnde('pr.projeto_ativo = 1');
	$q->adOnde('pr.projeto_template = 0');
	if ($usar_periodo) $q->adOnde('(( tarefa_inicio >= '.$ss.' AND tarefa_inicio <= '.$se.' ) OR '.' ( tarefa_fim <= '.$se.' AND tarefa_fim >= '.$ss.' ))');
	$q->adOnde('(tarefa_percentagem < 100)');
	if ($projeto_id) $q->adOnde('t.tarefa_projeto='.(int)$projeto_id);
	if ($cia_id)	$q->adOnde('pr.projeto_cia = '.(int)$cia_id);
	if ($log_filtroUsuario) $q->adOnde('ut.usuario_id = '.(int)$log_filtroUsuario);
	$q->adOrdem('tarefa_projeto');
	$q->adOrdem('tarefa_fim');
	$q->adOrdem('tarefa_inicio');
	$lista_tarefas_hash = $q->carregarColuna();
	$q->limpar();
	$lista_tarefas = array();
	$tarefa_designado_usuarios = array();
	$usuario_designado_tarefas = array();

	foreach ($lista_tarefas_hash as $tarefa_id){
		$tarefa = new CTarefa();
		$tarefa->load($tarefa_id);
		$tarefa_usuarios = $tarefa->getUsuariosDesignados_Linha();
		foreach (array_keys($tarefa_usuarios) as $chave => $uid) $usuario_designado_tarefas[$uid][] = $tarefa_id;
		$tarefa->tarefa_designado_usuarios = $tarefa_usuarios;
		$lista_tarefas[$tarefa_id] = $tarefa;

		}
	if (count($lista_tarefas) == 0) echo '<table width="100%" border=0 cellpadding="2" cellspacing="1" class="std"><tr><td nowrap="nowrap"><p>Nenhum'.$config['genero_tarefa'].' '.$config['tarefa'].' encontrada.</p></td></tr></table>';
	else {
		echo'<center><table width="100%" cellpadding="2" cellspacing=0 class="std">';
		echo '<tr><td bgcolor="#a6a6a6" align="center" nowrap="nowrap">'. dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']), 'Cada '.$config['tarefa'].' tem um nome para facilitar a identificação da mesma.<br><br>Clique em cima d'.$config['genero_tarefa'].' '.$config['tarefa'].' para visualizar detalhes da mesma.') .'<b>Tarefa</b>'.dicaF().'&nbsp;'.dica('Prioridade', 'A prioridade para fins de filtragem.').'<b>(P)</b>'.dicaF().'</td>'.($projeto_id ? '' : '<td bgcolor="#a6a6a6" align="center" nowrap="nowrap">'. dica('Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']), 'A qual '.$config['projeto'].' '.$config['genero_tarefa'].' '.$config['tarefa'].' pertence.') .'<b>'.ucfirst($config['projeto']).'</b>'.dicaF().'</td>').'<td bgcolor="#a6a6a6" align="center" nowrap="nowrap">'.dica('Duração', '<ul><li>A duração d'.$config['genero_tarefa'].' '.$config['tarefa'].' é inserida diretamente, ou calculada subtraindo a data final da inicial, depois desconta-se os fins-de-semana e por fim multiplica-se pela carga horária diária.<li>Caso tenha sido inserido para um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].', tanto a data final quanto o número de horas à serem trabalhadas, sem utilizar fórmula do Sistema, possívelmente será diferente do tempo calculado pela duração.</ul>').'<b>Duração</b>'.dicaF().'</td><td bgcolor="#a6a6a6" align="center" nowrap="nowrap">'.dica('Data de Início', 'Data de início d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'<b>Início</b>'.dicaF().'</td><td bgcolor="#a6a6a6" align="center" nowrap="nowrap">'.dica('Término em Dias', 'Número de dias para terminar '.$config['genero_tarefa'].' '.$config['tarefa'].', à partir da data de início.').'<b>Fim[d]</b>'.dicaF().'</td><td bgcolor="#a6a6a6" align="left" nowrap="nowrap">'.dica(ucfirst($config['usuarios']).' Designados', 'Havendo mais de um designado para '.$config['genero_tarefa'].' '.$config['tarefa'].', o porcentual indica o nível de comprometimento de cada um com a mesma.<br><br>Exemplo: Estando designado à 25% de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' de 4 horas implica que trabalhará 1 hora.').'<b>Designados</b>'.dicaF().'</td></tr>';

		foreach($lista_tarefas as $tarefa_id => $tarefa) echo mostrarTarefa_por_usuario($lista_tarefas, $tarefa, $usuario_id);


		echo '</table></center>';
		}

	}
echo estiloFundoCaixa();


function mostrarTarefa_por_usuario($lista, $tarefa, $usuario_id) {
	global $Aplic, $config, $df, $tipoDuracao, $log_filtroUsuario_usuarios, $agora, $prioridade, $sistema_usuarios, $z, $zi, $x, $usuarioDesig, $projetos, $projeto_id;
	$tmp ='';
	if (permiteAcessar($tarefa->tarefa_acesso, $tarefa->tarefa_projeto, $tarefa->tarefa_id)){
		$podeEditar = $Aplic->checarModulo('tarefas','editar');
		$permiteEditar=permiteEditar($tarefa->tarefa_acesso, $tarefa->tarefa_projeto, $tarefa->tarefa_id);
		$editar=($podeEditar&&$permiteEditar);
		if (!(key_exists($tarefa->tarefa_projeto, $projetos))) return;
		$zi++;
		$usuarios = $tarefa->tarefa_designado_usuarios;
		$tarefa->usuarioPrioridade = $tarefa->getPrioridadeTarefaUsuarioEspecifico($usuario_id);
		$projeto = $tarefa->getProjeto();

		$clique='selecionar_caixa(\'selecionado_tarefa\', \''.$tarefa->tarefa_id.'\', \'p_'.$projeto['projeto_id'].'_t_'.$tarefa->tarefa_id.'_u_'.$usuario_id.'\',\'frmDesignar'.$usuario_id.'\',\''.$usuario_id.'\')';
		$tmp .='<tr id="p_'.$projeto['projeto_id'].'_t_'.$tarefa->tarefa_id.'_u_'.$usuario_id.'" onclick="'.$clique.'" onmouseover="iluminar_tds(this, true, '.$tarefa->tarefa_id.', '.$usuario_id.')" onmouseout="iluminar_tds(this, false, '.$tarefa->tarefa_id.', '.$usuario_id.')">';
		$tmp .= '<td align="left" nowrap="nowrap">';
		if ($tarefa->tarefa_marco == true) $tmp .='<b>';
		$tmp .= link_tarefa($tarefa->tarefa_id);
		if ($tarefa->tarefa_marco == true) $tmp .= '</b>';
		$tmp .= ' '.prioridade($tarefa->tarefa_prioridade, true).'</td>';
		if (!$projeto_id) $tmp .= '<td align="center">'.link_projeto($tarefa->tarefa_projeto, 'cor', 'curto').'</td>';
		$tmp .= '<td align="center" nowrap="nowrap">'.$tarefa->tarefa_duracao.'&nbsp;'.substr($tipoDuracao[$tarefa->tarefa_duracao_tipo],0,1).'</td>';
		$tmp .= '<td align="center" nowrap="nowrap">';
			$dt = new CData($tarefa->tarefa_inicio);
			$tmp .= $dt->format($df);
			$tmp .= '&#160&#160&#160</td>';
			$tmp .= '<td align="center" nowrap="nowrap">';
			$ed = new CData($tarefa->tarefa_fim);
			$dt = $agora->dataDiferenca($ed);
			$sinal = $agora->compare($ed, $agora);
			if (!$dt) $sinal=1;
			$tmp .= ($dt * $sinal);
		$tmp .= '</td>';

		$q = new BDConsulta;
		$q->adTabela('tarefa_designados');
		$q->adCampo('usuario_id');
		$q->adOnde('tarefa_id = '.(int)$tarefa->tarefa_id);
		$lista_usuarios = $q->Lista();
		$q->limpar();


		$total=count($lista_usuarios)	;
		$s = '<td align="left" nowrap="nowrap">&nbsp;'.link_usuario($lista_usuarios[0]['usuario_id'],'','','esquerda').' ('.$usuarios[$lista_usuarios[0]['usuario_id']]['perc_designado'].'%)&nbsp;';
		if ($total> 1) {
			$lista='';
			for ($i = 1, $i_cmp = $total; $i < $i_cmp; $i++){
				$id=$lista_usuarios[$i]['usuario_id'];
				$lista.= $usuarios[$id]['contato_posto'].' '.$usuarios[$id]['contato_nomeguerra'].' ('.$usuarios[$id]['perc_designado'].'%)<br>';
				}
			$s .= dica('Outros '.ucfirst($config['usuarios']).' Designados', $lista).' <a href="javascript: void(0);" onclick="ativar_usuarios(\'t_'.$tarefa->tarefa_id.'_u_'.$usuario_id.'\'); '.$clique.'">(+'.($total- 1).')</a>'.dicaF().'<span style="display: none" id="t_'.$tarefa->tarefa_id.'_u_'.$usuario_id.'">';
			$a_u_vetor_tmp[] = $usuarios[$lista_usuarios[0]['usuario_id']]['contato_posto'].' '.$usuarios[$lista_usuarios[0]['usuario_id']]['contato_nomeguerra'];
			for ($i = 1, $i_cmp = $total; $i < $i_cmp; $i++) {
				$id=$lista_usuarios[$i]['usuario_id'];
				$s .= '<br />&nbsp;'.link_usuario($id, '','','esquerda').' ('.$usuarios[$id]['perc_designado'].'%)';
				}
			$s .= '</span>';
			}
		$s .= '</td>';
		$tmp .=$s;
		$tmp .= '</tr>';
		}
	return $tmp;
	}

function serTarefaSubordinada($tarefa) {
	return $tarefa->tarefa_id != $tarefa->tarefa_superior;
	}

function atoi($a) {
	return $a + 0;
	}

function datasSemana($mostrar_horas_alocadas, $doPeriodo, $atePeriodo) {
	if ($doPeriodo == -1) return '';
	if (!$mostrar_horas_alocadas) return '';
	$s = new CData($doPeriodo);
	$e = new CData($atePeriodo);
	$sw = getInicioSemana($s);
	$dw = ceil($e->dataDiferenca($s) / 7);
	$ew = $sw + $dw;
	$linha = '';
	for ($i = $sw; $i <= $ew; $i++) {
		$wn = $s->getSemanadoAno() % 52;
		$wn = ($wn != 0) ? $wn : 52;
		$sun = 6;
		$atual_dia=$wn;
		$dias_ate_domingo = $sun - $atual_dia;
		$ts_start = strtotime("-$atual_dia dias");
		$ts_end = strtotime("+$dias_ate_domingo dias");
		$linha .= '<th nowrap="nowrap">'.dica($wn.'ª Semana de '.date('Y',$ts_start),'De '.date('d/m/Y',$ts_start).' Dom à '.date('d/m/Y',$ts_end).' Sab.').$wn.dicaF().'</th>';
		$s->adSegundos(168 * 3600);
		}
	return $linha;
	}

function celulasSemana($mostrar_horas_alocadas, $doPeriodo, $atePeriodo) {
	if ($doPeriodo == -1) return 0;
	if (!$mostrar_horas_alocadas) return 0;
	$s = new CData($doPeriodo);
	$e = new CData($atePeriodo);
	$sw = getInicioSemana($s);
	$dw = ceil($e->dataDiferenca($s) / 7);
	$ew = $sw + $dw;
	return $ew - $sw + 1;
	}

function mostrarSemanas($lista, $tarefa, $nivel, $doPeriodo, $atePeriodo) {
	if ($doPeriodo == -1) return '';
	$s = new CData($doPeriodo);
	$e = new CData($atePeriodo);
	$sw = getInicioSemana($s);
	$dw = ceil($e->dataDiferenca($s) / 7);
	$ew = $sw + $dw;
	$st = new CData($tarefa->tarefa_inicio);
	$et = new CData($tarefa->tarefa_fim);
	$semanaInicioTarefa = getInicioSemana($st);
	$dtw = ceil($et->dataDiferenca($st) / 7);
	$semanaFimTarefa = $semanaInicioTarefa + $dtw;
	$linha = '';
	for ($i = $sw; $i <= $ew; $i++) {
		if ($i >= $semanaInicioTarefa and $i < $semanaFimTarefa) {
			$cor = 'blue';
			if ($nivel == 0 and temSubordinada($lista, $tarefa)) $cor = '#C0C0FF';
			elseif ($nivel == 1 and temSubordinada($lista, $tarefa)) $cor = '#9090FF';
			$linha .= '<td  id="ignore_td_" nowrap="nowrap" bgcolor="'.$cor.'">';
			}
		else $linha .= '<td nowrap="nowrap">';
		$linha .= '&#160&#160</td>';
		}
	return $linha;
	}

function getInicioSemana($d) {
	$dn = intval($d->Format('%w'));
	$dd = new CData($d);
	$dd->subtrairSegundos($dn * 24 * 3600);
	return intval($dd->Format('%U'));
	}

function getFimSemana($d) {
	$dn = intval($d->Format('%w'));
	if ($dn > 0) $dn = 7 - $dn;
	$dd = new CData($d);
	$dd->adSegundos($dn * 24 * 3600);
	return intval($dd->Format('%U'));
	}

function temSubordinada($lista, $tarefa) {
	foreach ($lista as $t) {
		if ($t->tarefa_superior == $tarefa->tarefa_id) return true;
		}
	return false;
	}


	function getTarefasOrfans($tval) {
		return (sizeof($tval->tarefa_designado_usuarios) > 0) ? null : $tval;
		}





?>
<script language="javascript">


function popProjeto() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["projeto"])?>', 630, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&aceita_portfolio=1&tabela=projetos&cia_id='+document.getElementById('cia_id').value, window.setProjeto, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setProjeto&tabela=projetos&cia_id='+document.getElementById('cia_id').value, 'Projetos','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}

function setProjeto(chave, valor){
	document.env.projeto_id.value = chave;
	document.env.projeto_nome.value = valor;
	}


function mudar_om(){
	var cia_id=document.getElementById('cia_id').value;
	xajax_selecionar_om_ajax(cia_id,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}

function mudar_usuario(){
	var cia_id=document.getElementById('cia_id').value;
	var usuario_id=document.getElementById('usuario_id').value;
	xajax_mudar_usuario_ajax(cia_id, usuario_id, 'usuario_id','combo_usuario', 'class="texto" size=1 style="width:250px;" onchange="escolheu_usuario();"', 'tarefas', 'tarefas.tarefa_dono=usuarios.usuario_id');
	}

function escolheu_usuario(){
	document.frmUsuario.cia_id.value=document.frmCia.cia_id.value;
	document.frmUsuario.submit();
	}


  var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "reg_data_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) {
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_inicio").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide();
  	}
  });

	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "reg_data_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) {
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("reg_data_fim").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal2.hide();
  	}
  });


function ativar_usuarios(id){
  var element = document.getElementById(id);
  element.style.display = (element.style.display == '' || element.style.display == "none") ? "inline" : "none";
	}

function setData( frm_nome, f_data ) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + 'reg_' + f_data );
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
  		}
		}
	else campo_data_real.value = '';
	}

var estah_marcado=null;

function iluminar_tds(linha,high,id, usuario){
	if(document.getElementsByTagName){
		var tcs=linha.getElementsByTagName('td');
		var nome_celula='';
		var checado=document.getElementById('selecionado_tarefa_'+id+'_u_'+usuario).checked;
		for(var j=0,j_cmp=tcs.length;j<j_cmp;j+=1){
			nome_celula=eval('tcs['+j+'].id');
			if(!(nome_celula.indexOf('ignore_td_')>=0)){
				if((high==3||!high) && !checado) tcs[j].style.background='#f2f0ec';
				else if(high==2 || checado) tcs[j].style.background='#FFCCCC';
				else if(high==1) tcs[j].style.background='#FFFFCC';
				else tcs[j].style.background='#f2f0ec';
				}
			}
		}
	}

function selecionar_caixa(box,id,linha_id,nome_formulario, usuario){
	var f=eval('document.'+nome_formulario);
	var check=eval('f.'+box+'_'+id+'_u_'+usuario+'.checked');
	boxObj=eval('f.elements["'+box+'_'+id+'_u_'+usuario+'"]');
	if((estah_marcado&&boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&!boxObj.checked&&!boxObj.disabled)){linha=document.getElementById(linha_id);
		boxObj.checked=true;
		iluminar_tds(linha,2,id, usuario);
		}
	else if((estah_marcado&&!boxObj.checked&&!boxObj.disabled)||(!estah_marcado&&boxObj.checked&&!boxObj.disabled)){
		linha=document.getElementById(linha_id);
		boxObj.checked=false;
		iluminar_tds(linha,3,id, usuario);
		}
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	}
</script>
