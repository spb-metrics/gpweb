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

$tarefa_id = intval(getParam($_REQUEST, 'tarefa_id', 0));

if (!$tarefa_id) {
	$Aplic->setMsg('ID d'.$config['genero_tarefa'].' '.$config['tarefa'].' não foi passado', UI_MSG_ERRO);
	$Aplic->redirecionar('m=projetos&a=index');
	exit();
	}

$sql = new BDConsulta;

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', intval(getParam($_REQUEST, 'cia_id', 0)));
$cia_id = $Aplic->getEstado('cia_id', $Aplic->usuario_cia);

if (isset($_REQUEST['ver_subordinadas'])) $Aplic->setEstado('ver_subordinadas', getParam($_REQUEST, 'ver_subordinadas', null));
$ver_subordinadas = ($Aplic->getEstado('ver_subordinadas') !== null ? $Aplic->getEstado('ver_subordinadas') : (($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) ? $Aplic->usuario_prefs['ver_subordinadas'] : 0));

if (isset($_REQUEST['recurso_responsavel'])) $Aplic->setEstado('recurso_responsavel', intval(getParam($_REQUEST, 'recurso_responsavel', 0)));
$recurso_responsavel = ($Aplic->getEstado('recurso_responsavel')!== null ? $Aplic->getEstado('recurso_responsavel') : 0);

if (isset($_REQUEST['tipo_recurso'])) $Aplic->setEstado('tipo_recurso', intval(getParam($_REQUEST, 'tipo_recurso', 5)));
$tipo_recurso = ($Aplic->getEstado('tipo_recurso')!== null ? $Aplic->getEstado('tipo_recurso') : 5);

if (isset($_REQUEST['recurso_ano'])) $Aplic->setEstado('recurso_ano', getParam($_REQUEST, 'recurso_ano', ''));
$recurso_ano = ($Aplic->getEstado('recurso_ano')!== null ? $Aplic->getEstado('recurso_ano') : '');

if (isset($_REQUEST['recurso_ugr'])) $Aplic->setEstado('recurso_ugr', getParam($_REQUEST, 'recurso_ugr', ''));
$recurso_ugr = ($Aplic->getEstado('recurso_ugr')!== null ? $Aplic->getEstado('recurso_ugr') : '');

if (isset($_REQUEST['recurso_ptres'])) $Aplic->setEstado('recurso_ptres', getParam($_REQUEST, 'recurso_ptres', ''));
$recurso_ptres = ($Aplic->getEstado('recurso_ptres')!== null ? $Aplic->getEstado('recurso_ptres') : '');

if (isset($_REQUEST['dept_id'])) $Aplic->setEstado('dept_id', intval(getParam($_REQUEST, 'dept_id', 0)));
$dept_id = ($Aplic->getEstado('dept_id') !== null ? ($Aplic->getEstado('dept_id') ? $Aplic->getEstado('dept_id') : null) : 0);
if ($dept_id) $ver_subordinadas = null;

if (isset($_REQUEST['recurso_credito_adicional'])) $Aplic->setEstado('recurso_credito_adicional', getParam($_REQUEST, 'recurso_credito_adicional', null));
$recurso_credito_adicional = ($Aplic->getEstado('recurso_credito_adicional') !== null ? $Aplic->getEstado('recurso_credito_adicional') : '');

if (isset($_REQUEST['recurso_movimentacao_orcamentaria'])) $Aplic->setEstado('recurso_movimentacao_orcamentaria',getParam($_REQUEST, 'recurso_movimentacao_orcamentaria', null));
$recurso_movimentacao_orcamentaria = ($Aplic->getEstado('recurso_movimentacao_orcamentaria') !== null ? $Aplic->getEstado('recurso_movimentacao_orcamentaria') : '');

if (isset($_REQUEST['recurso_identificador_uso'])) $Aplic->setEstado('recurso_identificador_uso', getParam($_REQUEST, 'recurso_identificador_uso', null));
$recurso_identificador_uso = ($Aplic->getEstado('recurso_identificador_uso') !== null ? $Aplic->getEstado('recurso_identificador_uso') : '');

if (isset($_REQUEST['recurso_pesquisa'])) $Aplic->setEstado('recurso_pesquisa', getParam($_REQUEST, 'recurso_pesquisa', null));
$recurso_pesquisa = ($Aplic->getEstado('recurso_pesquisa') !== null ? $Aplic->getEstado('recurso_pesquisa') : '');

if ($ver_subordinadas){
	$vetor_cias=array();
	lista_cias_subordinadas($cia_id, $vetor_cias);
	$vetor_cias[]=$cia_id;
	$lista_cias=implode(',',$vetor_cias);
	}
else $lista_cias=$cia_id;

$listaTipo=array(''=>'')+getSisValor('TipoRecurso');


$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ano');
$sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$anos = $sql->listaVetorChave('recurso_ano','recurso_ano');
$sql->limpar();
$anos =array(''=>'')+$anos;


$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ugr');
$sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$lista_ugrs = $sql->listaVetorChave('recurso_ugr','recurso_ugr');
$sql->limpar();
$lista_ugrs =array(''=>'')+$lista_ugrs;

$sql->adTabela('recursos');
$sql->adCampo('DISTINCT recurso_ptres');
$sql->adOnde('recurso_cia IN ('.$lista_cias.')');
$listaPtres = $sql->listaVetorChave('recurso_ptres','recurso_ptres');
$sql->limpar();
$listaPtres =array(''=>'')+$listaPtres;


$MovimentacaoOrcamentaria=array(''=>'')+getSisValor('MovimentacaoOrcamentaria');
$CreditoAdicional=array(''=>'')+getSisValor('CreditoAdicional');
$IdentificadorUso=array(''=>'')+getSisValor('IdentificadorUso');

$sql = new BDConsulta;
$sql->adTabela('tarefas');
$sql->esqUnir('projetos', 'projetos', 'projetos.projeto_id = tarefas.tarefa_projeto');
$sql->adCampo('tarefa_inicio, tarefa_fim, projeto_cia, tarefa_nome');
$sql->adOnde('tarefa_id ='.(int)$tarefa_id);
$tarefa=$sql->linha();
$sql->limpar();

$recurso_tipos = getSisValor('TipoRecurso');
$sql->adTabela('recursos');
$sql->adCampo('recurso_id, recurso_nome, recurso_tipo, recurso_nivel_acesso');
if ($tipo_recurso)$sql->adOnde('recurso_tipo='.(int)$tipo_recurso);
$sql->adOnde('recurso_cia='.(int)$tarefa['projeto_cia']);
$sql->adOrdem('recurso_tipo', 'recurso_nome');
$res = $sql->Lista();
$sql->limpar();
$todos_recursos = array();
foreach ($res as $linha) {
	if (permiteEditarRecurso($linha['recurso_nivel_acesso'], $linha['recurso_id'])) $todos_recursos[$linha['recurso_id']] = $linha['recurso_nome'].' ('.$recurso_tipos[$linha['recurso_tipo']].')';
	}


$recursos = array();


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="salvar" value="0" />';
echo '<input type="hidden" name="dialogo" value="'.$dialogo.'" />';
echo '<input type="hidden" name="recurso_tipo" id="recurso_tipo" value="" />';
echo '<input type="hidden" name="ver_subordinadas" value="'.$ver_subordinadas.'" />';
echo '<input type="hidden" name="lista_cias" id="lista_cias" value="'.$lista_cias.'" />';

echo '<input type="hidden" id="dept_id" name="dept_id" value="'.$dept_id.'" />';

echo '<input type="hidden" id="tarefa_id" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" id="qnt_maxima" name="qnt_maxima" value="" />';
echo '<input type="hidden" id="recurso_id" name="recurso_id" value="" />';
echo '<input type="hidden" id="uuid" name="uuid" value="" />';
$lista_tipo=array(''=>'')+$recurso_tipos;

echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="std">';
echo '<tr><td colspan=20 align=center><h1>'.$tarefa['tarefa_nome'].'</h1></td></tr>';
echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr><td><table cellpadding=0 cellspacing=0>';
echo '<tr><td align="right">'.dica('Tipo', 'Selecione qual o tipo de recurso.').'Tipo:'.dicaF().'</td><td align="left">'.selecionaVetor($listaTipo, 'tipo_recurso', 'style="width:250px;" onchange="ver_orcamentario(); ver_recursos();" class="texto"', $tipo_recurso).'</td></tr>';
echo '<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td>'.(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && !$ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=1; document.env.submit();">'.imagem('icones/organizacao_p.gif','Incluir Subordinadas','Clique neste ícone '.imagem('icones/organizacao_p.gif').' para incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').(($Aplic->usuario_pode_outra_cia || $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias) && $ver_subordinadas ? '<td><a href="javascript:void(0);" onclick="document.env.ver_subordinadas.value=0; document.env.submit();">'.imagem('icones/nao_sub_om.gif','Não Incluir Subordinadas','Clique neste ícone '.imagem('icones/nao_sub_om.gif').' para deixar de incluir '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinadas à selecionada.').'</a></td>' : '').'<td nowrap="nowrap"><table cellpadding=0 cellspacing=0><tr id="combo_dept" '.($dept_id ? '' : 'style="display:none"').'><td>'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Filtrar pel'.$config['genero_dept'].' '.strtolower($config['departamento']).' envolvid'.$config['genero_dept'].'.').$config['dept'].':<input type="text" class="texto" name="nome_dept" id="nome_dept" value="'.nome_dept($dept_id).'"></td></tr></table></td><td><a href="javascript:void(0);" onclick="escolher_dept();">'.imagem('icones/secoes_p.gif','Filtrar pel'.$config['genero_dept'].' '.$config['departamento'],'Clique neste ícone '.imagem('icones/secoes_p.gif').' para filtrar pel'.$config['genero_dept'].' '.$config['departamento'].' envolvid'.$config['genero_dept'].'.').'</a></td></tr>';
echo '<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="recurso_responsavel" name="recurso_responsavel" value="'.$recurso_responsavel.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($recurso_responsavel).'" style="width:245px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td><td colspan=2>&nbsp;</td></tr>';
echo '<tr id="identificador" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Identificador de Uso', 'O uso deste recurso.').'Idt:'.dicaF().'</td><td>'.selecionaVetor($IdentificadorUso, 'recurso_identificador_uso', 'class=texto size=1 style="width:250px;"', $recurso_identificador_uso).'</td></tr>';
echo '<tr id="credito_adicional" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Crédito Adicional', 'Caso seja monetário, seleciona o crédito adicional deste recurso, se for o caso.').'Crédito adicional:'.dicaF().'</td><td>'.selecionaVetor($CreditoAdicional, 'recurso_credito_adicional', 'style="width:250px;" class="texto"', $recurso_credito_adicional).'</td></tr>';
echo '<tr id="movimentacao" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Movimentação Orcamentária', 'Caso seja monetário, seleciona a movimentação orcamentária deste recurso, se for o caso.').'Movimentação:'.dicaF().'</td><td>'.selecionaVetor($MovimentacaoOrcamentaria, 'recurso_movimentacao_orcamentaria', 'style="width:250px;" class="texto"', $recurso_movimentacao_orcamentaria).'</td></tr>';
echo '<tr id="ptres" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" nowrap="nowrap">'.dica('Plano de Trabalho Resumido', 'Insira o plano de trabalho resumido deste recurso.').'PTRES:'.dicaF().'</td><td>'.selecionaVetor($listaPtres, 'recurso_ptres', 'style="width:250px;" class="texto"', $recurso_ptres).'</td></tr>';
echo '<tr id="combo_ano" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" >'.dica('Ano', 'Insira o ano deste recurso.').'Ano:'.dicaF().'</td><td>'.selecionaVetor($anos, 'recurso_ano', 'style="width:250px;" class="texto"', $recurso_ano).'</td></tr>';
echo '<tr id="ugrs" '.($tipo_recurso!=5 ? 'style="display:none"' : '').' ><td align="right" >'.dica('Unidade Gestora do Recurso', 'A unidade gestora do recurso.').'UGR:'.dicaF().'</td><td>'.selecionaVetor($lista_ugrs, 'recurso_ugr', 'style="width:250px;" class="texto"', $recurso_ugr).'</td></tr>';
echo '<tr><td nowrap="nowrap" align="right">'.dica('Pesquisa', 'Pesquisar pelo nome e campos de descrição').'Pesquisar:'.dicaF().'</td><td colspan=2><table cellpadding=0 cellspacing=0><tr><td><input type="text" class="texto" style="width:245px;" id="recurso_pesquisa" name="recurso_pesquisa" value="'.$recurso_pesquisa.'" /></td><td><a href="javascript:void(0);" onclick="env.recurso_pesquisa.value=\'\';">'.imagem('icones/limpar_p.gif','Limpar Pesquisa', 'Clique neste ícone '.imagem('icones/limpar_p.gif').' para limpar a caixa texto de pesquisa.').'</a></td></tr></table></td></tr>';


echo '</table></td>';
echo '<td valign="middle"><a href="javascript:void(0);" onclick="ver_recursos();">'.imagem('icones/recursos_p.gif','Atualizar Recursos','Clique neste ícone '.imagem('icones/recursos_p.gif').' para atualizar a lista de recursos pelos parâmetros selecionados.').'</a></td>';
echo '</tr></table></td></tr>';

echo '<tr><td>'.dica('Recursos Disponíveis', 'Importante salientar que à <i>priori</i> todos os recursos ainda não designados para '.$config['genero_tarefa'].' '.$config['tarefa'].' aparecerão aqui, por isso é importante verificar se o recurso designado já não está envolvido em um número excessivo de  '.$config['tarefas'].'.'). '<b>Recursos Disponíveis</b>'.dicaF().'</td></tr>';
echo '<tr>';
echo '<td><div id="combo_recursos">'.selecionaVetor($todos_recursos, 'mat_recursos', 'style="width:350px;" size="10" class="texto" onclick="selecionar_recurso(this.value);" ondblclick="if(checar_quantidade() && checar_podeEditarRecurso(mat_recursos.value)) incluir_recurso()"', null).'</div></td>';
echo '</tr>';
echo '<tr><td><table cellpadding=0 cellspacing=0><tr>';
echo '<td align="left"><span id="disponibilidade"><a href="javascript: void(0);" onclick="alocacao()">'.imagem('icones/calendario_p.png', 'Disponibilidade','Visualizara disponibilidade, por dia, do recurso selecionado n'.$config['genero_tarefa'].'s '.$config['tarefas'].' em que já esteja designado.').'</a></span></td>';

echo '<td align="left"><span id="tipo_qnt">Qnt:</span><input text class="texto" style="width:120px;" id="qnt_recurso" name="qnt_recurso" value="" onkeypress="return entradaNumerica(event, this, true, true);"></td>';
$percentual=getSisValor('TarefaPorcentagem','','','sisvalor_id');
echo '<td align="right" nowrap="nowrap">'.dica('Percentual de Alocação', 'O porcentual de alocação do recurso n'.$config['genero_tarefa'].' '.$config['tarefa'].' pode ir de 0% até 100%.').'Percentual:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($percentual, 'percentual_alocado', 'size="1" class="texto"', 100).'% </td>';
echo '<td align="left"><a href="javascript: void(0);" onclick="if(checar_quantidade() && checar_podeEditarRecurso(mat_recursos.value)) incluir_recurso();">'.imagem('icones/adicionar.png', 'Adicionar', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar o recurso selecionado.').'</a></td>';
echo '</tr></table></td></tr>';
echo '<tr><td colspan=20 align=center><div id="detalhes_recurso"></div></td></tr>';

echo '<input type=hidden name="recurso_tarefa_id" id="recurso_tarefa_id" value="">';

if ($tarefa_id) {
	$sql->adTabela('recurso_tarefas');
	$sql->esqUnir('recursos', 'recursos', 'recursos.recurso_id=recurso_tarefas.recurso_id');
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$sql->adCampo('recurso_tarefa_id, recurso_tarefas.recurso_id, recurso_nome, recurso_tarefas.recurso_quantidade, percentual_alocado, recurso_tarefa_ordem');
	$sql->adOrdem('recurso_tarefa_ordem');
	$recurso=$sql->ListaChave('recurso_tarefa_id');
	$sql->limpar();
	}
else $recurso=null;
echo '<tr><td colspan=20 align=left><div id="lista_recursos">';
if (count($recurso)) {
	echo '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'O nome do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Nome'.dicaF().'</th><th>'.dica('Quantidade', 'A quantidade do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'Quantidade'.dicaF().'</th><th>'.dica('Porcentagm', 'A porcentagem de uso do recurso alocado n'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'%'.dicaF().'</th><th></th></tr>';
	foreach ($recurso as $recurso_tarefa_id => $linha) {
		echo '<tr align="center">';
		echo '<td nowrap="nowrap" width="40" align="center">';
		echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
		echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_recurso('.$linha['recurso_tarefa_ordem'].', '.$linha['recurso_tarefa_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
		echo '</td>';
		echo '<td align="left">'.$linha['recurso_nome'].'</td>';
		echo '<td align="right">'.number_format($linha['recurso_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right">'.$linha['percentual_alocado'].'</td>';
		echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este recurso?\')) {excluir_recurso('.$linha['recurso_tarefa_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o recurso d'.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</a></td>';
		echo '</tr>';
		}
	echo '</table>';
	}
echo '</div></td></tr>';







echo '<tr><td>'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
echo '</table>';
echo estiloFundoCaixa();
echo '</form>';

?>

<script language="javascript">

//recurso
function mudar_posicao_recurso(ordem, recurso_tarefa_id, direcao){
	xajax_mudar_posicao_recurso(ordem, recurso_tarefa_id, direcao, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


function incluir_recurso(){
	if (document.getElementById('recurso_id').value !=''){
		xajax_incluir_recurso(document.getElementById('tarefa_id').value, document.getElementById('uuid').value, document.getElementById('recurso_tarefa_id').value, document.getElementById('recurso_id').value, document.getElementById('qnt_recurso').value, document.getElementById('percentual_alocado').value);
		document.getElementById('recurso_tarefa_id').value=null;
		document.getElementById('recurso_id').value='';
		document.getElementById('qnt_recurso').value='';
		__buildTooltip();
		}
	else alert('escolha um recurso.');
	}

function excluir_recurso(recurso_tarefa_id){
	xajax_excluir_recurso(recurso_tarefa_id, document.getElementById('tarefa_id').value, document.getElementById('uuid').value);
	__buildTooltip();
	}


function ver_recursos(){
	var tipo=document.getElementById('tipo_recurso').value;
	var lista_cias=document.getElementById('lista_cias').value;
	var recurso_responsavel=document.getElementById('recurso_responsavel').value;
	var recurso_ano=document.getElementById('recurso_ano').value;
	var recurso_ugr=document.getElementById('recurso_ugr').value;
	var recurso_ptres=document.getElementById('recurso_ptres').value;
	var dept_id=document.getElementById('dept_id').value;
	var recurso_credito_adicional=document.getElementById('recurso_credito_adicional').value;
	var recurso_movimentacao_orcamentaria=document.getElementById('recurso_movimentacao_orcamentaria').value;
	var recurso_identificador_uso=document.getElementById('recurso_identificador_uso').value;
	var recurso_pesquisa=document.getElementById('recurso_pesquisa').value;
	xajax_ver_recursos_ajax(lista_cias, tipo, recurso_responsavel, recurso_ano, recurso_ugr, recurso_ptres, dept_id, recurso_credito_adicional, recurso_movimentacao_orcamentaria, recurso_identificador_uso, recurso_pesquisa);
	}

var recurso_tipo;

function checar_podeEditarRecurso(recurso_id){
	var permite=xajax_permite_editar(recurso_id);
	return permite;
	}


function detalhes_recurso(recurso_id){
	xajax_detalhes_recurso(recurso_id, 'detalhes_recurso');
	}


function quantidade_disponivel(recurso_id){
	var qnt=xajax_quantidade_disponivel(recurso_id, document.getElementById('tarefa_id').value);
	return qnt;
	}

function alocacao(){
	if (!document.getElementById('mat_recursos').value) alert ('É necessário escolher primeiramente um recurso.');
	else if (window.parent.gpwebApp) parent.gpwebApp.popUp('Alocação', 820, 500, 'm=recursos&a=alocacao&dialogo=1&cia_id=<?php echo $tarefa["projeto_cia"] ?>&recurso_id='+document.getElementById('mat_recursos').options[document.getElementById('mat_recursos').selectedIndex].value+'&editar=1', window.setResponsavel, window);
	else window.open('./index.php?m=recursos&a=alocacao&dialogo=1&cia_id=<?php echo $tarefa["projeto_cia"] ?>&recurso_id='+document.getElementById('mat_recursos').options[document.getElementById('mat_recursos').selectedIndex].value+'&editar=1', 'Alocação', 'height=620,width=820,resizable,scrollbars=yes');
	}


function selecionar_recurso(recurso_id){
	xajax_recurso_tipo(recurso_id);
	detalhes_recurso(recurso_id);
	document.getElementById('recurso_id').value=recurso_id;
	var qnt_disponivel=quantidade_disponivel(recurso_id);
	document.getElementById('qnt_recurso').value=float2moeda(qnt_disponivel);

	document.getElementById('qnt_maxima').value=qnt_disponivel;
	if(document.getElementById('recurso_tipo').value==5) {
		document.getElementById("tipo_qnt").innerHTML="<?php echo $config['simbolo_moeda']?>:";
		document.getElementById('disponibilidade').style.display='none';
		}
	else{
		document.getElementById("tipo_qnt").innerHTML="Qnt:";
		document.getElementById('disponibilidade').style.display='';
		}
	}

function checar_quantidade(){
	var qnt_disponivel=quantidade_disponivel(document.getElementById('mat_recursos').value);
	if(!document.getElementById('mat_recursos').value) {
		alert('Selecione primeiro um recurso.');
		document.getElementById('qnt_recurso').value='';
		return false;
		}
	else if (document.getElementById('qnt_recurso').value > qnt_disponivel){
		alert("A quantidade selecionda é superior a exitente!\nSó existe "+qnt_disponivel+" disponível");
		document.getElementById('qnt_recurso').value='';
		return false;
		}
	else if (!document.getElementById('qnt_recurso').value){
		alert('Necessita escolher a quantidade!');
		document.getElementById('qnt_recurso').value='';
		return false;
		}
	return true;
	}




function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
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




function ver_orcamentario(){


	if (document.getElementById('tipo_recurso').value==5){
		document.getElementById('combo_ano').style.display='';
		document.getElementById('identificador').style.display='';
		document.getElementById('credito_adicional').style.display='';
		document.getElementById('movimentacao').style.display='';
		document.getElementById('ptres').style.display='';
		document.getElementById('ugrs').style.display='';
		}
	else {
		document.getElementById('combo_ano').style.display='none';
		document.getElementById('identificador').style.display='none';
		document.getElementById('credito_adicional').style.display='none';
		document.getElementById('movimentacao').style.display='none';
		document.getElementById('ptres').style.display='none';
		document.getElementById('ugrs').style.display='none';
		}

	}

function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
	}


function escolher_dept(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp("<?php echo ucfirst($config['departamento']) ?>", 500, 500, 'm=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, window.filtrar_dept, window);
	else window.open('./index.php?m=publico&a=selecao_unico_dept&dialogo=1&chamar_volta=filtrar_dept&dept_id=<?php echo $dept_id ?>&cia_id='+document.getElementById('cia_id').value, 'Filtrar','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function filtrar_dept(cia, deptartamento, nome){
	env.dept_id.value=deptartamento;
	env.nome_dept.value=nome;

	if (deptartamento > 0) document.getElementById('combo_dept').style.display='';
	else document.getElementById('combo_dept').style.display='none';
	}


function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('recurso_responsavel').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('recurso_responsavel').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('recurso_responsavel').value=usuario_id;
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');
	ver_recursos();
	}

</script>
