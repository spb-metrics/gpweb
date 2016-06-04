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
global $Aplic;

$df = '%d/%m/%Y';
$Aplic->carregarCalendarioJS();

$imprimindo = getParam($_REQUEST, 'imprimindo', 0);

$baseline_id = getParam($_REQUEST, 'baseline_id', 0);
$item = getParam($_REQUEST, 'item', 0);
$tarefa_id = getParam($_REQUEST, 'tarefa_id', 0);
$ordem = getParam($_REQUEST, 'ordem', 0);
$acao = getParam($_REQUEST, 'acao', '');
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
if (!$tarefa_id) $tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
if (!$item) $item = getParam($_REQUEST, 'item', 0);
if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');
$tarefa_custos_nome = getParam($_REQUEST, 'tarefa_custos_nome', '');
$tarefa_custos_tipo = getParam($_REQUEST, 'tarefa_custos_tipo', 0);
$tarefa_custos_nd = getParam($_REQUEST, 'tarefa_custos_nd', '');
$tarefa_custos_quantidade = float_americano(getParam($_REQUEST, 'tarefa_custos_quantidade', 0));
$tarefa_custos_custo = float_americano(getParam($_REQUEST, 'tarefa_custos_custo', 0));
$tarefa_custos_descricao = getParam($_REQUEST, 'tarefa_custos_descricao', '');
$tarefa_custos_data_limite= getParam($_REQUEST, 'tarefa_custos_data_limite', '');
$tarefa_custos_categoria_economica = getParam($_REQUEST, 'tarefa_custos_categoria_economica', '');
$tarefa_custos_grupo_despesa = getParam($_REQUEST, 'tarefa_custos_grupo_despesa', '');
$tarefa_custos_modalidade_aplicacao = getParam($_REQUEST, 'tarefa_custos_modalidade_aplicacao', '');
$tarefa_custos_pi = getParam($_REQUEST, 'tarefa_custos_pi', null);
$unidade= getSisValor('TipoUnidade');

$sql = new BDConsulta;
if ($Aplic->profissional){
	$sql->adTabela('pi');
	$sql->esqUnir('tarefas', 'tarefas', 'pi_projeto=tarefa_projeto');
	$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
	$sql->adCampo('pi_pi');
	$sql->adOrdem('pi_ordem');
	$pi=array(''=>'')+$sql->listaVetorChave('pi_pi','pi_pi');
	$sql->limpar();
	}
else $pi=array();


$sql->adTabela('tarefa_h_custos');
$sql->adCampo('count(h_custos_id)');
$sql->adOnde('h_custos_tarefa ='.(int)$tarefa_id);
$historico=$sql->Resultado();
$sql->limpar();

if ($acao=='excluir'&& $item){
	$sql->adTabela('tarefa_custos', 'tc');
	$sql->adCampo('tc.*');
	$sql->adOnde('tc.tarefa_custos_id ='.$item);
	$excluido= $sql->Linha();
	$sql->limpar();
	$sql->adTabela('tarefa_h_custos');
	$sql->adInserir('h_custos_tarefa_custos_id', (int)$item);
	$sql->adInserir('h_custos_nome1', $excluido['tarefa_custos_nome']);
	$sql->adInserir('h_custos_tarefa', $excluido['tarefa_custos_tarefa']);
	$sql->adInserir('h_custos_tipo1', $excluido['tarefa_custos_tipo']);
	$sql->adInserir('h_custos_quantidade1', (float)$excluido['tarefa_custos_quantidade']);
	$sql->adInserir('h_custos_custo1', (float)$excluido['tarefa_custos_custo']);
	$sql->adInserir('h_custos_nd1', $excluido['tarefa_custos_nd']);
	$sql->adInserir('h_custos_categoria_economica1', $excluido['tarefa_custos_categoria_economica']);
	$sql->adInserir('h_custos_grupo_despesa1', $excluido['tarefa_custos_grupo_despesa']);
	$sql->adInserir('h_custos_modalidade_aplicacao1', $excluido['tarefa_custos_modalidade_aplicacao']);
	$sql->adInserir('h_custos_descricao1', $excluido['tarefa_custos_descricao']);
	$sql->adInserir('h_custos_usuario1', $excluido['tarefa_custos_usuario']);
	$sql->adInserir('h_custos_usuario2', $Aplic->usuario_id);
	$sql->adInserir('h_custos_excluido', 1);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('h_custos_data1', $excluido['tarefa_custos_data']);
	$sql->adInserir('h_custos_data2', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item '.$excluido['tarefa_custos_nome'].'")</script>';
	$sql->limpar();
	$sql->setExcluir('tarefa_custos');
	$sql->adOnde('tarefa_custos_id='.(int)$item);
	$sql->exec();
	$sql->limpar();
	}
if ($acao=='c_editar' && $item){
		$sql->adTabela('tarefa_custos', 'tc');
	$sql->adCampo('tc.*');
	$sql->adOnde('tc.tarefa_custos_id ='.$item);
	$editado= $sql->Linha();
	$sql->limpar();
	$sql->adTabela('tarefa_h_custos');
	$sql->adInserir('h_custos_tarefa_custos_id', (int)$item);
	$sql->adInserir('h_custos_nome1', $editado['tarefa_custos_nome']);
	$sql->adInserir('h_custos_tarefa', $editado['tarefa_custos_tarefa']);
	$sql->adInserir('h_custos_tipo1', $editado['tarefa_custos_tipo']);
	$sql->adInserir('h_custos_quantidade1', (float)$editado['tarefa_custos_quantidade']);
	$sql->adInserir('h_custos_custo1', (float)$editado['tarefa_custos_custo']);
	$sql->adInserir('h_custos_nd1', $editado['tarefa_custos_nd']);
	$sql->adInserir('h_custos_categoria_economica1', $editado['tarefa_custos_categoria_economica']);
	$sql->adInserir('h_custos_grupo_despesa1', $editado['tarefa_custos_grupo_despesa']);
	$sql->adInserir('h_custos_modalidade_aplicacao1', $editado['tarefa_custos_modalidade_aplicacao']);
	$sql->adInserir('h_custos_descricao1', $editado['tarefa_custos_descricao']);
	$sql->adInserir('h_custos_usuario1', $editado['tarefa_custos_usuario']);
	$sql->adInserir('h_custos_usuario2', $Aplic->usuario_id);
	$sql->adInserir('h_custos_nome2', $tarefa_custos_nome);
	$sql->adInserir('h_custos_tipo2', $tarefa_custos_tipo);
	$sql->adInserir('h_custos_quantidade2', (float)$tarefa_custos_quantidade);
	$sql->adInserir('h_custos_custo2', (float)$tarefa_custos_custo);
	$sql->adInserir('h_custos_nd2', $tarefa_custos_nd);
	$sql->adInserir('h_custos_categoria_economica2', $tarefa_custos_categoria_economica);
	$sql->adInserir('h_custos_grupo_despesa2', $tarefa_custos_grupo_despesa);
	$sql->adInserir('h_custos_modalidade_aplicacao2', $tarefa_custos_modalidade_aplicacao);
	$sql->adInserir('h_custos_descricao2', $tarefa_custos_descricao);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('h_custos_data1', $editado['tarefa_custos_data']);
	$sql->adInserir('h_custos_data2', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item editado '.$editado['tarefa_custos_nome'].'")</script>';
	$sql->limpar();
	$sql->adTabela('tarefa_custos');
	$sql->adAtualizar('tarefa_custos_nome', $tarefa_custos_nome);
	$sql->adAtualizar('tarefa_custos_tipo', $tarefa_custos_tipo);
	$sql->adAtualizar('tarefa_custos_quantidade', (float)$tarefa_custos_quantidade);
	$sql->adAtualizar('tarefa_custos_custo', (float)$tarefa_custos_custo);
	$sql->adAtualizar('tarefa_custos_nd', $tarefa_custos_nd);
	$sql->adAtualizar('tarefa_custos_categoria_economica', $tarefa_custos_categoria_economica);
	$sql->adAtualizar('tarefa_custos_grupo_despesa', $tarefa_custos_grupo_despesa);
	$sql->adAtualizar('tarefa_custos_modalidade_aplicacao', $tarefa_custos_modalidade_aplicacao);
	$sql->adAtualizar('tarefa_custos_descricao', $tarefa_custos_descricao);
	$sql->adAtualizar('tarefa_custos_usuario', $Aplic->usuario_id);
	$sql->adAtualizar('tarefa_custos_data_limite', $tarefa_custos_data_limite);
	$sql->adAtualizar('tarefa_custos_pi', $tarefa_custos_pi);
	
	$data=date('Y-m-d H:m:s');
	$sql->adAtualizar('tarefa_custos_data', $data);
	$sql->adOnde('tarefa_custos_id = '.$item);
	if (!$sql->exec()) echo '<script>alert("Não foi possível alterar os dados do item '.$tarefa_custos_nome.'")</script>';
	$sql->limpar();
	
	}			
if ($acao=='c_inserir'&& $tarefa_id){	
	$sql->adTabela('tarefa_custos', 'tc');
	$sql->adCampo('max(tarefa_custos_ordem) AS ultimo');
	$sql->adOnde('tc.tarefa_custos_tarefa ='.$tarefa_id);
	$ultimo= $sql->Resultado();
	if ($ultimo) $ultimo++;
	else $ultimo=1;
	$sql->limpar();	
	$sql->adTabela('tarefa_custos');
	$sql->adInserir('tarefa_custos_nome', $tarefa_custos_nome);
	$sql->adInserir('tarefa_custos_tarefa', $tarefa_id);
	$sql->adInserir('tarefa_custos_tipo', $tarefa_custos_tipo);
	$sql->adInserir('tarefa_custos_quantidade', (float)$tarefa_custos_quantidade);
	$sql->adInserir('tarefa_custos_custo', (float)$tarefa_custos_custo);
	$sql->adInserir('tarefa_custos_nd', $tarefa_custos_nd);
	$sql->adInserir('tarefa_custos_categoria_economica', $tarefa_custos_categoria_economica);
	$sql->adInserir('tarefa_custos_grupo_despesa', $tarefa_custos_grupo_despesa);
	$sql->adInserir('tarefa_custos_modalidade_aplicacao', $tarefa_custos_modalidade_aplicacao);
	$sql->adInserir('tarefa_custos_descricao', $tarefa_custos_descricao);
	$sql->adInserir('tarefa_custos_ordem', $ultimo);
	$sql->adInserir('tarefa_custos_usuario', $Aplic->usuario_id);
	$sql->adInserir('tarefa_custos_data_limite', $tarefa_custos_data_limite);
	$sql->adInserir('tarefa_custos_pi', $tarefa_custos_pi);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('tarefa_custos_data', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir os dados do item '.$tarefa_custos_nome.'")</script>';
	$sql->limpar();
			
	}
if ($acao=='acima' && $item && ($ordem > 1)){
	$sql->adTabela('tarefa_custos');
	$sql->adCampo('tarefa_custos_id');
	$sql->adOnde('tarefa_custos_ordem ='.($ordem-1));
	$anterior= $sql->Resultado();
	$sql->limpar();
	if ($anterior){
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', $ordem);
		$sql->adOnde('tarefa_custos_id = '.$anterior);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição anterior")</script>';
		$sql->limpar();
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', ($ordem-1));
		$sql->adOnde('tarefa_custos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição anterior")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='primeira' && $item && ($ordem > 1)){
	$sql->adTabela('tarefa_custos');
	$sql->adCampo('tarefa_custos_id');
	$sql->adOnde('tarefa_custos_ordem <'.$ordem);
	$sql->adOrdem('tarefa_custos_ordem');
	$anteriores= $sql->Lista();
	$sql->limpar();
	$posicao=2;
	foreach ($anteriores as $dado) {
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', $posicao);
		$sql->adOnde('tarefa_custos_id = '.$dado['tarefa_custos_id']);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a primeira posição.")</script>';
		$sql->limpar();
		$posicao++;
		}
	if ($posicao > 2){
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', 1);
		$sql->adOnde('tarefa_custos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a primeira posição.")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='ultima' && $item){
	$sql->adTabela('tarefa_custos');
	$sql->adCampo('tarefa_custos_id');
	$sql->adOnde('tarefa_custos_ordem >'.$ordem);
	$sql->adOrdem('tarefa_custos_ordem');
	$anteriores= $sql->Lista();
	$sql->limpar();
	$posicao=$ordem;
	foreach ($anteriores as $dado) {
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', $posicao);
		$sql->adOnde('tarefa_custos_id = '.$dado['tarefa_custos_id']);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a ultima posição.")</script>';
		$sql->limpar();
		$posicao++;
		}
	if ($posicao != $ordem){
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', $posicao);
		$sql->adOnde('tarefa_custos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a ultima posição.")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='abaixo' && $item){
	$sql->adTabela('tarefa_custos');
	$sql->adCampo('tarefa_custos_id');
	$sql->adOnde('tarefa_custos_ordem ='.($ordem+1));
	$proximo= $sql->Resultado();
	$sql->limpar();
	if ($proximo){
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', $ordem);
		$sql->adOnde('tarefa_custos_id = '.$proximo);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição posterior.")</script>';
		$sql->limpar();
		$sql->adTabela('tarefa_custos');
		$sql->adAtualizar('tarefa_custos_ordem', ($ordem+1));
		$sql->adOnde('tarefa_custos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição posterior.")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='editar' && $item){	
	$sql->adTabela('tarefa_custos', 'tc');
	$sql->adCampo('tc.*, ((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$sql->adOnde('tc.tarefa_custos_id ='.$item);
	$atual= $sql->Linha();
	$sql->limpar();	
	}
if ($acao=='inserir') $ir='c_inserir';
elseif ($acao=='editar') $ir='c_editar';
else 	$ir='';


if (in_array($acao, array('excluir', 'importar', 'c_inserir', 'c_editar'))){
	//passar o total para a tarefa e projeto, para acelerar consultas
	
	$sql->adTabela('tarefa_custos');
	$sql->adCampo('SUM((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS total');
	$sql->adOnde('tarefa_custos_tarefa ='.$tarefa_id);
	$total=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_custo',($total));
	$sql->adOnde('tarefa_id = '.$tarefa_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_projeto');
	$sql->adOnde('tarefa_id ='.$tarefa_id);
	$projeto_id=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adCampo('SUM(tarefa_custo) AS total');
	$sql->adOnde('tarefa_projeto ='.$projeto_id);
	$total=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_custo',$total);
	$sql->adOnde('projeto_id = '.$projeto_id);
	$sql->exec();
	$sql->limpar();
	$acao='';
	}


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input type="hidden" name="a" value="estimado" />';
if ($ir) echo '<input type="hidden" name="acao" value="'.$ir.'" />';
echo '<input type="hidden" name="item" value="'.$item.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="baseline_id" value="'.$baseline_id.'" />';
echo '<table width="100%" cellpadding=0 cellspacing=0 ><tr><td width='.($historico && !$imprimindo ? 32 : 16).'>&nbsp;</td><td align="center"><center><h1>Custos Estimados - '.link_tarefa($tarefa_id, '', true).'</h1></center></td>'.($historico && !$imprimindo ? '<td width=16><a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=historico&dialogo=1&tarefa_id='.$tarefa_id.'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/informacao.gif', 'Histórico da Planilha', 'Clique neste ícone '.imagem('icones/informacao.gif').' para visualizar as alterações na planilha de custos estimados.').'</a></td>' : '').'<td align="right" width=16>'.(!$imprimindo ? '<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=estimado&imprimindo=1&dialogo=1&tarefa_id='.$tarefa_id.'&tipo=estimado\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/imprimir_p.png', 'Imprimir a Planilha', 'Clique neste ícone '.imagem('icones/imprimir_p.png').' para imprimir a planilha.').'</a>' : '').'</td></tr></table>';

if (!$imprimindo) echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=0 cellspacing=0 class="std2">';
if ($acao!='inserir' && $acao!='editar'){

	echo '<tr><td valign="top" align="center">';
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_custos', 'tc');
	$sql->adCampo('tc.*, ((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$sql->adOnde('tc.tarefa_custos_tarefa ='.$tarefa_id);
	if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOrdem('tarefa_custos_ordem');
	$linhas= $sql->Lista();
	$qnt=0;
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th><th>'.dica('Unidade', 'A unidade de referência para o item.').'Unidade'.dicaF().'</th><th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th><th>'.dica('Valor em '.$config['simbolo_moeda'], 'O valor de uma unidade do item.').'Valor ('.$config['simbolo_moeda'].')'.dicaF().'</th><th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th><th>'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total é o preço unitário multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th><th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th><th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.(count($pi)>1 ? '<th>'.dica('PI', 'PI do item.').'PI'.dicaF().'</th>' : '').(!$imprimindo ? '<th></th>' : '').'</tr>';
	$total=0;
	$custo=array();
	foreach ($linhas as $dado) {
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$dado['tarefa_custos_nome'].'</td>';
		echo '<td align="left">'.($dado['tarefa_custos_descricao'] ? $dado['tarefa_custos_descricao'] : '&nbsp;').'</td>';
		echo '<td>'.$unidade[$dado['tarefa_custos_tipo']].'</td><td>'.number_format($dado['tarefa_custos_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right">'.number_format($dado['tarefa_custos_custo'], 2, ',', '.').'</td>';
		
		$nd=($dado['tarefa_custos_categoria_economica'] && $dado['tarefa_custos_grupo_despesa'] && $dado['tarefa_custos_modalidade_aplicacao'] ? $dado['tarefa_custos_categoria_economica'].'.'.$dado['tarefa_custos_grupo_despesa'].'.'.$dado['tarefa_custos_modalidade_aplicacao'].'.' : '').$dado['tarefa_custos_nd'];
		
		echo '<td>'.$nd.'</td>';
		echo '<td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td>';
		echo '<td align="left" nowrap="nowrap">'.link_usuario($dado['tarefa_custos_usuario'],'','','esquerda').'</td>';
		echo '<td>'.($dado['tarefa_custos_data_limite']? retorna_data($dado['tarefa_custos_data_limite'],false) : '&nbsp;').'</td>';
		if (count($pi)>1) echo '<td align="center">'.$dado['tarefa_custos_pi'].'</td>';
		if (!$imprimindo) {
			echo '<td width="72" align="right">';
			echo dica('Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição o item '.$dado['tarefa_custos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=estimado&acao=primeira&ordem='.$dado['tarefa_custos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_custos_id'].'\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover Acima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima o item '.$dado['tarefa_custos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=estimado&acao=acima&ordem='.$dado['tarefa_custos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_custos_id'].'\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover Abaixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo o item '.$dado['tarefa_custos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=estimado&acao=abaixo&ordem='.$dado['tarefa_custos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_custos_id'].'\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Última Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição o item '.$dado['tarefa_custos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=estimado&acao=ultima&ordem='.$dado['tarefa_custos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_custos_id'].'\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Editar Item', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$dado['tarefa_custos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=estimado&acao=editar&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_custos_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
			echo dica('Excluir Item', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$dado['tarefa_custos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=estimado&acao=excluir&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_custos_id'].'\');">'.imagem('icones/remover.png').'</a>'.dicaF();
			echo '</td>';
			}
		echo '</tr>';
		if (isset($custo[$nd])) $custo[$nd] += (float)$dado['valor'];
		else $custo[$nd] =(float)$dado['valor'];
		$total+=$dado['valor'];
		}
	if ($total) {
			echo '<tr><td colspan="6" class="std" align="right">';
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.($indice_nd ? $indice_nd : 'Sem ND');
			echo '<br><b>Total</td><td align="right">';	
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
			echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
			}
	if (!$qnt) echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';			
	echo '</table></td></tr>';
	if (!$imprimindo) echo '<tr><td><table width="100%"><tr><td>'.botao('inserir', 'Inserir','Inserir um novo item.','','url_passar(0, \'m=tarefas&a=estimado&acao=inserir&tarefa_id='.$tarefa_id.'\');').'</td><td align="right">'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$tarefa_id.'\');').'</td></tr></table></td></tr>';
	}
else {	
	echo '<tr><td><table width="100%" border=0 cellpadding=0 cellspacing=0>';
	echo '<tr><td align="center" nowrap="nowrap" colspan="2"><h1>'.($item ? 'Editar Item' : 'Inserir Item').'</h1></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_custos_nome" value="'.(isset($atual['tarefa_custos_nome']) ? $atual['tarefa_custos_nome']:'').'" maxlength="255" size="40" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida deste item.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'tarefa_custos_tipo', 'class=texto size=1', (isset($atual['tarefa_custos_tipo']) ? $atual['tarefa_custos_tipo']:'')).'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();"name="tarefa_custos_quantidade" id="tarefa_custos_quantidade" value="'.(isset($atual['tarefa_custos_quantidade']) ? number_format($atual['tarefa_custos_quantidade'], 2, ',', '.'):'').'" maxlength="255" size="10" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor', 'Insira o valor deste item.').'Valor:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="tarefa_custos_custo" id="tarefa_custos_custo" value="'.(isset($atual['tarefa_custos_custo']) ? number_format($atual['tarefa_custos_custo'], 2, ',', '.'):'').'" size="40" /></td></tr>';
	
	$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'tarefa_custos_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="env.tarefa_custos_nd.value=\'\'; mudar_nd();"',(isset($atual['tarefa_custos_categoria_economica']) ?  $atual['tarefa_custos_categoria_economica'] : '')).'</td></tr>';

	$GrupoND=array(''=>'')+getSisValor('GrupoND');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'tarefa_custos_grupo_despesa', 'class=texto size=1 style="width:395px;"  onchange="env.tarefa_custos_nd.value=\'\'; mudar_nd();"',(isset($atual['tarefa_custos_grupo_despesa']) ?  $atual['tarefa_custos_grupo_despesa'] : '')).'</td></tr>';
	
	$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'tarefa_custos_modalidade_aplicacao', 'class=texto size=1 style="width:395px;"  onchange="env.tarefa_custos_nd.value=\'\'; mudar_nd();"', (isset($atual['tarefa_custos_modalidade_aplicacao']) ?  $atual['tarefa_custos_modalidade_aplicacao'] : '')).'</td></tr>';
	
	
	$nd=vetor_nd((isset($atual['tarefa_custos_nd']) ? $atual['tarefa_custos_nd'] : ''), null, null, 3 ,(isset($atual['tarefa_custos_categoria_economica']) ?  $atual['tarefa_custos_categoria_economica'] : ''), (isset($atual['tarefa_custos_grupo_despesa']) ?  $atual['tarefa_custos_grupo_despesa'] : ''), (isset($atual['tarefa_custos_modalidade_aplicacao']) ?  $atual['tarefa_custos_modalidade_aplicacao'] : ''));
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'tarefa_custos_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', (isset($atual['tarefa_custos_nd']) ?  $atual['tarefa_custos_nd'] : '')).'</div></td></tr>';
	
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Total', 'O valor total do item.').'Total:'.dicaF().'</td><td><div id="total"></div></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Insira a descrição deste item.').'Descrição:'.dicaF().'</td><td><textarea cols="70" rows="5" class="textarea" name="tarefa_custos_descricao">'.(isset($atual['tarefa_custos_descricao']) ? $atual['tarefa_custos_descricao']:'').'</textarea></td></tr>';

	if (count($pi)>1) echo '<tr><td align="right" nowrap="nowrap">'.dica('PI', 'Escolha o PI deste item.').'PI:'.dicaF().'</td><td>'.selecionaVetor($pi, 'tarefa_custos_pi', 'class=texto size=1 style="width:395px;"', (isset($atual['tarefa_custos_pi']) ?  $atual['tarefa_custos_pi'] : '')).'</td></tr>';
	else echo '<input type="hidden" name="tarefa_custos_pi" value="" />';

	$data = (isset($atual['tarefa_custos_data_limite']) ? $atual['tarefa_custos_data_limite'] : '');
	$data_texto = intval($data) ? new CData($data) : new CData();
	echo '<tr><td align="right">'.dica('Data','Data limite para o recebimento do ítem.').'Data:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="tarefa_custos_data_limite" id="tarefa_custos_data_limite" value="'.($data_texto ? $data_texto->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_texto"  id="data_texto" style="width:70px;" onchange="setData(\'env\', \'data_texto\', \'tarefa_custos_data_limite\');" value="'.($data_texto ? $data_texto->format($df) : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite para o recebimento do ítem.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
	
	echo '<tr><td colspan="2"><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar a '.($item ? 'edição' : 'inserção').' do item.','','env.submit()').'</td><td align="right">'.botao('cancelar', 'Cancelar','Cancelar a '.($item ? 'edição' : 'inserção').' do item.','','url_passar(0, \'m=tarefas&a=estimado&tarefa_id='.$tarefa_id.'\');').'</td></tr></table></td></tr>';
	echo '</table></td></tr>';
	}
echo '</td></tr></table></form>';
if (!$imprimindo) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';
?>

<script language="javascript">
function mudar_nd(){
	xajax_mudar_nd_ajax(env.tarefa_custos_nd.value, 'tarefa_custos_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, env.tarefa_custos_categoria_economica.value, env.tarefa_custos_grupo_despesa.value, env.tarefa_custos_modalidade_aplicacao.value);
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


<?php if ($acao=='inserir' || $acao=='editar') { ?>	

var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "tarefa_custos_data_limite",
  	date :  <?php echo $data_texto->format("%Y%m%d")?>,
  	selection: <?php echo $data_texto->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("tarefa_custos_data_limite").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });	

function setData( env_nome, f_data, f_data_real) {
	campo_data = eval( 'document.'+env_nome+'.'+f_data);
	campo_data_real = eval( 'document.'+env_nome+'.'+f_data_real);
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
	
function valor(){
	var custo=moeda2float(document.getElementById('tarefa_custos_custo').value);	
	var qnt=moeda2float(document.getElementById('tarefa_custos_quantidade').value);	
	
	if (custo=='') custo=0;
	if (valor=='') valor=0;
	document.getElementById('total').innerHTML ='<b><?php echo $config["simbolo_moeda"]?>'+float2moeda(custo*qnt)+'</b>';
	}	



valor();

<?php } ?>	
</script>


