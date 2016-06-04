<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic;
$item = getParam($_REQUEST, 'item', 0);

$baseline_id = getParam($_REQUEST, 'baseline_id', 0);
$imprimindo = getParam($_REQUEST, 'imprimindo', 0);
$tarefa_id = getParam($_REQUEST, 'tarefa_id', 0);
$ordem = getParam($_REQUEST, 'ordem', 0);
$acao = getParam($_REQUEST, 'acao', '');
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
if (!$tarefa_id) $tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
if (!$item) $item = getParam($_REQUEST, 'item', 0);
if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado&err=noedit');
$tarefa_gastos_nome = getParam($_REQUEST, 'tarefa_gastos_nome', '');
$tarefa_gastos_tipo = getParam($_REQUEST, 'tarefa_gastos_tipo', 0);
$tarefa_gastos_nd = getParam($_REQUEST, 'tarefa_gastos_nd', 0);
$tarefa_gastos_quantidade = float_americano(getParam($_REQUEST, 'tarefa_gastos_quantidade', 0));
$tarefa_gastos_custo = float_americano(getParam($_REQUEST, 'tarefa_gastos_custo', 0));
$tarefa_gastos_descricao = getParam($_REQUEST, 'tarefa_gastos_descricao', '');
$tarefa_gastos_categoria_economica = getParam($_REQUEST, 'tarefa_gastos_categoria_economica', '');
$tarefa_gastos_grupo_despesa = getParam($_REQUEST, 'tarefa_gastos_grupo_despesa', '');
$tarefa_gastos_modalidade_aplicacao = getParam($_REQUEST, 'tarefa_gastos_modalidade_aplicacao', '');

$nd=array(0 => '');
$nd+= getSisValorND();
$unidade= getSisValor('TipoUnidade');

$sql = new BDConsulta;
$sql->adTabela('tarefa_h_gastos');
$sql->adCampo('count(h_gastos_id)');
$sql->adOnde('h_gastos_tarefa ='.(int)$tarefa_id);
$historico= $sql->Resultado();
$sql->limpar();

if ($acao=='excluir'&& $item){
	$sql->adTabela('tarefa_gastos', 'tg');
	$sql->adCampo('tg.*');
	$sql->adOnde('tg.tarefa_gastos_id ='.$item);
	$excluido= $sql->Linha();
	$sql->limpar();
	$sql->adTabela('tarefa_h_gastos');
	$sql->adInserir('h_gastos_tarefa_gastos_id', (int)$item);
	$sql->adInserir('h_gastos_nome1', $excluido['tarefa_gastos_nome']);
	$sql->adInserir('h_gastos_tarefa', $excluido['tarefa_gastos_tarefa']);
	$sql->adInserir('h_gastos_tipo1', $excluido['tarefa_gastos_tipo']);
	$sql->adInserir('h_gastos_quantidade1', (float)$excluido['tarefa_gastos_quantidade']);
	$sql->adInserir('h_gastos_custo1', (float)$excluido['tarefa_gastos_custo']);
	$sql->adInserir('h_gastos_nd1', $excluido['tarefa_gastos_nd']);
	$sql->adInserir('h_gastos_categoria_economica1', $excluido['tarefa_gastos_categoria_economica']);
	$sql->adInserir('h_gastos_grupo_despesa1', $excluido['tarefa_gastos_grupo_despesa']);
	$sql->adInserir('h_gastos_modalidade_aplicacao1', $excluido['tarefa_gastos_modalidade_aplicacao']);
	$sql->adInserir('h_gastos_descricao1', $excluido['tarefa_gastos_descricao']);
	$sql->adInserir('h_gastos_usuario1', $excluido['tarefa_gastos_usuario']);
	$sql->adInserir('h_gastos_usuario2', $Aplic->usuario_id);
	$sql->adInserir('h_gastos_excluido', 1);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('h_gastos_data1', $excluido['tarefa_gastos_data']);
	$sql->adInserir('h_gastos_data2', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item '.$excluido['tarefa_gastos_nome'].'")</script>';
	$sql->limpar();
	$sql->setExcluir('tarefa_gastos');
	$sql->adOnde('tarefa_gastos_id='.(int)$item);
	$sql->exec();
	$sql->limpar();
	}
if ($acao=='c_editar' && $item){
	$sql->adTabela('tarefa_gastos', 'tg');
	$sql->adCampo('tg.*');
	$sql->adOnde('tg.tarefa_gastos_id ='.$item);
	$editado= $sql->Linha();
	$sql->limpar();
	$sql->adTabela('tarefa_h_gastos');
	$sql->adInserir('h_gastos_tarefa_gastos_id', (int)$item);
	$sql->adInserir('h_gastos_nome1', $editado['tarefa_gastos_nome']);
	$sql->adInserir('h_gastos_tarefa', $editado['tarefa_gastos_tarefa']);
	$sql->adInserir('h_gastos_tipo1', $editado['tarefa_gastos_tipo']);
	$sql->adInserir('h_gastos_quantidade1', (float)$editado['tarefa_gastos_quantidade']);
	$sql->adInserir('h_gastos_custo1', (float)$editado['tarefa_gastos_custo']);
	$sql->adInserir('h_gastos_nd1', $editado['tarefa_gastos_nd']);
	$sql->adInserir('h_gastos_categoria_economica1', $editado['tarefa_gastos_categoria_economica']);
	$sql->adInserir('h_gastos_grupo_despesa1', $editado['tarefa_gastos_grupo_despesa']);
	$sql->adInserir('h_gastos_modalidade_aplicacao1', $editado['tarefa_gastos_modalidade_aplicacao']);
	$sql->adInserir('h_gastos_descricao1', $editado['tarefa_gastos_descricao']);
	$sql->adInserir('h_gastos_usuario1', $editado['tarefa_gastos_usuario']);
	$sql->adInserir('h_gastos_usuario2', $Aplic->usuario_id);
	$sql->adInserir('h_gastos_nome2', $tarefa_gastos_nome);
	$sql->adInserir('h_gastos_tipo2', $tarefa_gastos_tipo);
	$sql->adInserir('h_gastos_quantidade2', (float)$tarefa_gastos_quantidade);
	$sql->adInserir('h_gastos_custo2', (float)$tarefa_gastos_custo);
	$sql->adInserir('h_gastos_nd2', $tarefa_gastos_nd);
	$sql->adInserir('h_gastos_categoria_economica2', $tarefa_gastos_categoria_economica);
	$sql->adInserir('h_gastos_grupo_despesa2', $tarefa_gastos_grupo_despesa);
	$sql->adInserir('h_gastos_modalidade_aplicacao2', $tarefa_gastos_modalidade_aplicacao);
	$sql->adInserir('h_gastos_descricao2', $tarefa_gastos_descricao);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('h_gastos_data1', $editado['tarefa_gastos_data']);
	$sql->adInserir('h_gastos_data2', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item editado '.$editado['tarefa_gastos_nome'].'")</script>';
	$sql->limpar();

	$sql->adTabela('tarefa_gastos');
	$sql->adAtualizar('tarefa_gastos_nome', $tarefa_gastos_nome);
	$sql->adAtualizar('tarefa_gastos_tipo', $tarefa_gastos_tipo);
	$sql->adAtualizar('tarefa_gastos_quantidade', (float)$tarefa_gastos_quantidade);
	$sql->adAtualizar('tarefa_gastos_custo', (float)$tarefa_gastos_custo);
	$sql->adAtualizar('tarefa_gastos_nd', $tarefa_gastos_nd);
	$sql->adAtualizar('tarefa_gastos_categoria_economica', $tarefa_gastos_categoria_economica);
	$sql->adAtualizar('tarefa_gastos_grupo_despesa', $tarefa_gastos_grupo_despesa);
	$sql->adAtualizar('tarefa_gastos_modalidade_aplicacao', $tarefa_gastos_modalidade_aplicacao);
	$sql->adAtualizar('tarefa_gastos_descricao', $tarefa_gastos_descricao);
	$sql->adAtualizar('tarefa_gastos_usuario', $Aplic->usuario_id);
	$data=date('Y-m-d H:m:s');
	$sql->adAtualizar('tarefa_gastos_data', $data);
	$sql->adOnde('tarefa_gastos_id = '.$item);
	if (!$sql->exec()) echo '<script>alert("Não foi possível alterar os dados do item '.$tarefa_gastos_nome.'")</script>';
	$sql->limpar();
	}			
if ($acao=='c_inserir'&& $tarefa_id){	
	$sql->adTabela('tarefa_gastos', 'tc');
	$sql->adCampo('max(tarefa_gastos_ordem) AS ultimo');
	$sql->adOnde('tc.tarefa_gastos_tarefa ='.$tarefa_id);
	$ultimo= $sql->Resultado();
	if ($ultimo) $ultimo++;
	else $ultimo=1;
	$sql->limpar();	
	$sql->adTabela('tarefa_gastos');
	$sql->adInserir('tarefa_gastos_nome', $tarefa_gastos_nome);
	$sql->adInserir('tarefa_gastos_tarefa', $tarefa_id);
	$sql->adInserir('tarefa_gastos_tipo', $tarefa_gastos_tipo);
	$sql->adInserir('tarefa_gastos_quantidade', (float)$tarefa_gastos_quantidade);
	$sql->adInserir('tarefa_gastos_custo', (float)$tarefa_gastos_custo);
	$sql->adInserir('tarefa_gastos_nd', $tarefa_gastos_nd);
	$sql->adInserir('tarefa_gastos_categoria_economica', $tarefa_gastos_categoria_economica);
	$sql->adInserir('tarefa_gastos_grupo_despesa', $tarefa_gastos_grupo_despesa);
	$sql->adInserir('tarefa_gastos_modalidade_aplicacao', $tarefa_gastos_modalidade_aplicacao);
	$sql->adInserir('tarefa_gastos_descricao', $tarefa_gastos_descricao);
	$sql->adInserir('tarefa_gastos_ordem', $ultimo);
	$sql->adInserir('tarefa_gastos_usuario', $Aplic->usuario_id);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('tarefa_gastos_data', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir os dados do item '.$tarefa_gastos_nome.'")</script>';
	$sql->limpar();
	}
if ($acao=='acima' && $item && ($ordem > 1)){

	$sql->adTabela('tarefa_gastos');
	$sql->adCampo('tarefa_gastos_id');
	$sql->adOnde('tarefa_gastos_ordem ='.($ordem-1));
	$anterior= $sql->Resultado();
	$sql->limpar();
	if ($anterior){
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', $ordem);
		$sql->adOnde('tarefa_gastos_id = '.$anterior);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição anterior")</script>';
		$sql->limpar();
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', ($ordem-1));
		$sql->adOnde('tarefa_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição anterior")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='primeira' && $item && ($ordem > 1)){
	$sql->adTabela('tarefa_gastos');
	$sql->adCampo('tarefa_gastos_id');
	$sql->adOnde('tarefa_gastos_ordem <'.$ordem);
	$sql->adOrdem('tarefa_gastos_ordem');
	$anteriores= $sql->Lista();
	$sql->limpar();
	$posicao=2;
	foreach ($anteriores as $dado) {
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', $posicao);
		$sql->adOnde('tarefa_gastos_id = '.$dado['tarefa_gastos_id']);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a primeira posição.")</script>';
		$sql->limpar();
		$posicao++;
		}
	if ($posicao > 2){
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', 1);
		$sql->adOnde('tarefa_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a primeira posição.")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='ultima' && $item){
	$sql->adTabela('tarefa_gastos');
	$sql->adCampo('tarefa_gastos_id');
	$sql->adOnde('tarefa_gastos_ordem >'.$ordem);
	$sql->adOrdem('tarefa_gastos_ordem');
	$anteriores= $sql->Lista();
	$sql->limpar();
	$posicao=$ordem;
	foreach ($anteriores as $dado) {
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', $posicao);
		$sql->adOnde('tarefa_gastos_id = '.$dado['tarefa_gastos_id']);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a ultima posição.")</script>';
		$sql->limpar();
		$posicao++;
		}
	if ($posicao != $ordem){
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', $posicao);
		$sql->adOnde('tarefa_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a ultima posição.")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='abaixo' && $item){
	$sql->adTabela('tarefa_gastos');
	$sql->adCampo('tarefa_gastos_id');
	$sql->adOnde('tarefa_gastos_ordem ='.($ordem+1));
	$proximo= $sql->Resultado();
	$sql->limpar();
	if ($proximo){
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', $ordem);
		$sql->adOnde('tarefa_gastos_id = '.$proximo);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição posterior.")</script>';
		$sql->limpar();
		$sql->adTabela('tarefa_gastos');
		$sql->adAtualizar('tarefa_gastos_ordem', ($ordem+1));
		$sql->adOnde('tarefa_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição posterior.")</script>';
		$sql->limpar();
		}
	}	
if ($acao=='importar' && $tarefa_id){
	
	$com_qnt=getParam($_REQUEST, 'com_qnt', 0);
	
	$sql->adTabela('tarefa_custos', 'tc');
	$sql->adCampo('tc.*, ((tarefa_custos_quantidade*tarefa_custos_custo)*((100+tarefa_custos_bdi)/100)) AS valor ');
	$sql->adOnde('tc.tarefa_custos_tarefa ='.$tarefa_id);
	$sql->adOrdem('tarefa_custos_ordem');
	$linhas= $sql->Lista();
	$sql->limpar();
	$qnt=0;
	foreach ($linhas as $dado) {
		$sql->adTabela('tarefa_gastos');
		$sql->adInserir('tarefa_gastos_nome', $dado['tarefa_custos_nome']);
		$sql->adInserir('tarefa_gastos_tarefa', $tarefa_id);
		$sql->adInserir('tarefa_gastos_tipo', $dado['tarefa_custos_tipo']);
		$sql->adInserir('tarefa_gastos_quantidade', ($com_qnt ? $dado['tarefa_custos_quantidade'] : 0));
		$sql->adInserir('tarefa_gastos_custo', (float)$dado['tarefa_custos_custo']);
		$sql->adInserir('tarefa_gastos_descricao', $dado['tarefa_custos_descricao']);
		$sql->adInserir('tarefa_gastos_ordem', $dado['tarefa_custos_ordem']);
		$sql->adInserir('tarefa_gastos_nd', $dado['tarefa_custos_nd']);
		$sql->adInserir('tarefa_gastos_categoria_economica', $dado['tarefa_custos_categoria_economica']);
		$sql->adInserir('tarefa_gastos_grupo_despesa', $dado['tarefa_custos_grupo_despesa']);
		$sql->adInserir('tarefa_gastos_modalidade_aplicacao', $dado['tarefa_custos_modalidade_aplicacao']);
		
		$sql->adInserir('tarefa_gastos_usuario', $Aplic->usuario_id);
		$data=date('Y-m-d H:m:s');
		$sql->adInserir('tarefa_gastos_data', $data);
		if (!$sql->exec()) echo '<script>alert("Não foi possível importar dados do item '.$tarefa_gastos_nome.'")</script>';
		$sql->limpar();
		$qnt++;	
		}
	if ($qnt) echo '<script>alert("Importação dos itens da planilha de previsão de custos realizada com sucesso, com '.$qnt.' itens inseridos.")</script>';	
	else echo '<script>alert("Importação dos itens da planilha de previsão de custo não foi efetuada, pois não há uma planilha de previsão de custos para est'.($config['genero_tarefa']=='a' ?  'a' : 'e').' '.$config['tarefa'].'.")</script>';	
	}
if ($acao=='editar' && $item){	
	$sql->adTabela('tarefa_gastos', 'tc');
	$sql->adCampo('tc.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	$sql->adOnde('tc.tarefa_gastos_id ='.$item);
	$atual= $sql->Linha();
	$sql->limpar();	
	}
if ($acao=='inserir') $ir='c_inserir';
elseif ($acao=='editar') $ir='c_editar';
else 	$ir='';


if (in_array($acao, array('excluir', 'importar', 'c_inserir', 'c_editar'))){
	//passar o total para a tarefa e projeto, para acelerar consultas
	$sql->adTabela('tarefa_gastos');
	$sql->adCampo('SUM((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS total');
	$sql->adOnde('tarefa_gastos_tarefa ='.$tarefa_id);
	$total1=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefa_log');
	$sql->adCampo('SUM(tarefa_log_custo) AS total');
	$sql->adOnde('tarefa_log_tarefa ='.$tarefa_id);
	$total2=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adAtualizar('tarefa_gasto',($total1+$total2));
	$sql->adOnde('tarefa_id = '.$tarefa_id);
	$sql->exec();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_projeto');
	$sql->adOnde('tarefa_id ='.$tarefa_id);
	$projeto_id=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('tarefas');
	$sql->adCampo('SUM(tarefa_gasto) AS total');
	$sql->adOnde('tarefa_projeto ='.$projeto_id);
	$total=$sql->Resultado();
	$sql->limpar();
	
	$sql->adTabela('projetos');
	$sql->adAtualizar('projeto_gasto',$total);
	$sql->adOnde('projeto_id = '.$projeto_id);
	$sql->exec();
	$sql->limpar();
	
	$acao='';
	}


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="tarefas" />';
echo '<input type="hidden" name="a" value="gasto" />';
if ($ir) echo '<input type="hidden" name="acao" value="'.$ir.'" />';
echo '<input type="hidden" name="item" value="'.$item.'" />';
echo '<input type="hidden" name="tarefa_id" value="'.$tarefa_id.'" />';
echo '<input type="hidden" name="baseline_id" value="'.$baseline_id.'" />';

echo '<table width="100%" cellpadding=0 cellspacing=0 ><tr><td width='.($historico && !$imprimindo ? 32 : 16).'>&nbsp;</td><td align="center"><center><h1>Gastos - '.link_tarefa($tarefa_id, '', true).'</h1></center></td>'.($historico && !$imprimindo ? '<td width=16><a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=tarefas&a=historico&dialogo=1&tarefa_id='.$tarefa_id.'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/informacao.gif', 'Histórico da Planilha', 'Clique neste ícone '.imagem('icones/informacao.gif').' para visualizar as alterações na planilha de gastos.').'</a></td>' : '').'<td align="right" width=16>'.(!$imprimindo ? '<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m='.$m.'&a='.$a.'&imprimindo=1&dialogo=1&tarefa_id='.$tarefa_id.'&tipo=efetivo\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('icones/imprimir_p.png', 'Imprimir a Planilha', 'Clique neste ícone '.imagem('icones/imprimir_p.png').' para imprimir a planilha.').'</a>' : '').'</td></tr></table>';

if (!$imprimindo) echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
if ($acao!='inserir' && $acao!='editar'){
	$nd=getSisValorND();
	echo '<tr><td valign="top" align="center">';
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefa_gastos', 'tc');
	$sql->adCampo('tc.*, ((tarefa_gastos_quantidade*tarefa_gastos_custo)*((100+tarefa_gastos_bdi)/100)) AS valor ');
	$sql->adOnde('tc.tarefa_gastos_tarefa ='.$tarefa_id);
	if ($baseline_id)	$sql->adOnde('baseline_id='.(int)$baseline_id);
	$sql->adOrdem('tarefa_gastos_ordem');
	$linhas= $sql->Lista();
	$sql->limpar();	
	$qnt=0;
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th><th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th><th>'.dica('Unidade', 'A unidade de referência para o item.').'Unidade'.dicaF().'</th><th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th><th>'.dica('Valor em '.$config['simbolo_moeda'], 'O valor de uma unidade do item.').'Valor ('.$config['simbolo_moeda'].')'.dicaF().'</th><th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th><th>'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total é o preço unitário multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th><th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th><th>'.dica('Data do Recebimento', 'A data em que o material foi recebido.').'Data'.dicaF().'</th>'.(!$imprimindo ? '<th></th>' : '').'</tr>';
	$total=0;
	$custo=array();
	foreach ($linhas as $dado) {
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$dado['tarefa_gastos_nome'].'</td>';
		echo '<td align="left">'.($dado['tarefa_gastos_descricao']? $dado['tarefa_gastos_descricao'] : '&nbsp;').'</td>';
		echo '<td>'.$unidade[$dado['tarefa_gastos_tipo']].'</td><td>'.number_format($dado['tarefa_gastos_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right">'.number_format($dado['tarefa_gastos_custo'], 2, ',', '.').'</td>';
		echo '<td>'.dica('Natureza da Despesa', (isset($nd[$dado['tarefa_gastos_nd']]) ? $nd[$dado['tarefa_gastos_nd']] : 'Sem natureza de despesa')).($dado['tarefa_gastos_categoria_economica'] && $dado['tarefa_gastos_grupo_despesa'] && $dado['tarefa_gastos_modalidade_aplicacao'] ? $dado['tarefa_gastos_categoria_economica'].'.'.$dado['tarefa_gastos_grupo_despesa'].'.'.$dado['tarefa_gastos_modalidade_aplicacao'].'.' : '').$dado['tarefa_gastos_nd'].'</td>';
		echo '<td align="right">'.number_format($dado['valor'], 2, ',', '.').'</td>';
		echo '<td align="left">'.link_usuario($dado['tarefa_gastos_usuario'],'','','esquerda').'</td>';
		echo '<td>'.($dado['tarefa_gastos_data_recebido']? retorna_data($dado['tarefa_gastos_data_recebido'],false) : '&nbsp;').'</td>';
		if (!$imprimindo){
			echo '<td width=76>';
			echo dica('Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição o item '.$dado['tarefa_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=gasto&acao=primeira&ordem='.$dado['tarefa_gastos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_gastos_id'].'\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover Acima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima o item '.$dado['tarefa_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=gasto&acao=acima&ordem='.$dado['tarefa_gastos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_gastos_id'].'\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover Abaixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo o item '.$dado['tarefa_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=gasto&acao=abaixo&ordem='.$dado['tarefa_gastos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_gastos_id'].'\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Última Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição o item '.$dado['tarefa_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=gasto&acao=ultima&ordem='.$dado['tarefa_gastos_ordem'].'&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_gastos_id'].'\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Editar Item', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$dado['tarefa_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=gasto&acao=editar&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_gastos_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
			echo dica('Excluir Item', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$dado['tarefa_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tarefas&a=gasto&acao=excluir&tarefa_id='.$tarefa_id.'&item='.$dado['tarefa_gastos_id'].'\');">'.imagem('icones/remover.png').'</a>'.dicaF();
			echo '</td>';
			}
		echo '</tr>';
		if(isset($custo[$dado['tarefa_gastos_nd']])) $custo[$dado['tarefa_gastos_nd']] += (float)$dado['valor'];
		else $custo[$dado['tarefa_gastos_nd']] = (float)$dado['valor'];
		$total+=$dado['valor'];
		}
	if ($total) {
			echo '<tr><td colspan="6" class="std" align="right">';
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.(isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND');
			echo '<br><b>Total</td><td align="right">';	
			foreach ($custo as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
			echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
			}
	if (!$qnt) echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';	
	echo '</table></td></tr>';
	if (!$qnt) {
		$sql->adTabela('tarefa_custos');
		$sql->adCampo('count(tarefa_custos_id)');
		$sql->adOnde('tarefa_custos_tarefa ='.$tarefa_id);
		$existe_custo=$sql->Resultado();
		$sql->limpar();	
		if ($existe_custo) echo '<tr><td><table width="100%"><tr><td align="center">'.botao('importar sem quantidade', 'Importar Sem Quantidade','Pressione este botão caso deseje importar os dados da planilha de custos deixando o quantitativo em branco.','','url_passar(0, \'m=tarefas&a=gasto&acao=importar&com_qnt=0&tarefa_id='.$tarefa_id.'\');').'</td><td align="center">'.botao('importar com quantitativo', 'Importar com Quantitativo','Pressione este botão caso deseje importar os dados da planilha de custo.','','url_passar(0, \'m=tarefas&a=gasto&acao=importar&com_qnt=1&tarefa_id='.$tarefa_id.'\');').'</td></tr></table></td></tr>';
		}
	if (!$imprimindo) echo '<tr><td><table width="100%"><tr><td>'.botao('inserir', 'Inserir','Inserir um novo item.','','url_passar(0, \'m=tarefas&a=gasto&acao=inserir&tarefa_id='.$tarefa_id.'\');').'</td><td align="right">'.botao('voltar', 'Voltar', 'Retornar à tela anterior.','','url_passar(0, \'m=tarefas&a=ver&tarefa_id='.$tarefa_id.'\');').'</td></tr></table></td></tr>';
	}
else {
	echo '<tr><td><table width="100%" border=0 cellpadding=0 cellspacing=0>';
	echo '<tr><td align="center" nowrap="nowrap" colspan="2"><h1>'.($item ? 'Editar Item' : 'Inserir Item').'</h1></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="tarefa_gastos_nome" value="'.(isset($atual['tarefa_gastos_nome']) ? $atual['tarefa_gastos_nome']:'').'" maxlength="255" size="40" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida deste item.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'tarefa_gastos_tipo', 'class=texto size=1', (isset($atual['tarefa_gastos_tipo']) ? $atual['tarefa_gastos_tipo']:'')).'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();"name="tarefa_gastos_quantidade" id="tarefa_gastos_quantidade" value="'.(isset($atual['tarefa_gastos_quantidade']) ? number_format($atual['tarefa_gastos_quantidade'], 2, ',', '.'):'').'" maxlength="255" size="15" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor', 'Insira o valor deste item.').'Valor:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="tarefa_gastos_custo" id="tarefa_gastos_custo" value="'.(isset($atual['tarefa_gastos_custo']) ? number_format($atual['tarefa_gastos_custo'], 2, ',', '.'):'').'" size="40" /></td></tr>';
	$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'tarefa_gastos_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="env.tarefa_gastos_nd.value=\'\'; mudar_nd();"', (isset($atual['tarefa_gastos_categoria_economica']) ? $atual['tarefa_gastos_categoria_economica']:'')).'</td></tr>';
	$GrupoND=array(''=>'')+getSisValor('GrupoND');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'tarefa_gastos_grupo_despesa', 'class=texto size=1 style="width:395px;" onchange="env.tarefa_gastos_nd.value=\'\'; mudar_nd();"', (isset($atual['tarefa_gastos_grupo_despesa']) ? $atual['tarefa_gastos_grupo_despesa']:'')).'</td></tr>';
	$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'tarefa_gastos_modalidade_aplicacao', 'class=texto size=1 style="width:395px;" onchange="env.tarefa_gastos_nd.value=\'\'; mudar_nd();"', (isset($atual['tarefa_gastos_modalidade_aplicacao']) ? $atual['tarefa_gastos_modalidade_aplicacao']:'')).'</td></tr>';
	$nd=vetor_nd((isset($atual['tarefa_gastos_nd']) ? $atual['tarefa_gastos_nd'] : ''), null, null, 3 ,(isset($atual['tarefa_gastos_categoria_economica']) ?  $atual['tarefa_gastos_categoria_economica'] : ''), (isset($atual['tarefa_gastos_grupo_despesa']) ?  $atual['tarefa_gastos_grupo_despesa'] : ''), (isset($atual['tarefa_gastos_modalidade_aplicacao']) ?  $atual['tarefa_gastos_modalidade_aplicacao'] : ''));
	
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'tarefa_gastos_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', (isset($atual['tarefa_gastos_nd']) ? $atual['tarefa_gastos_nd']:'')).'</div></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Total', 'O valor total do item.').'Total:'.dicaF().'</td><td><div id="total"></div></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Insira a descrição deste item.').'Descrição:'.dicaF().'</td><td><textarea cols="70" rows="5" class="textarea" name="tarefa_gastos_descricao">'.(isset($atual['tarefa_gastos_descricao']) ? $atual['tarefa_gastos_descricao']:'').'</textarea></td></tr>';
	echo '<tr><td colspan="2"><table width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar a '.($item ? 'edição' : 'inserção').' do item.','','env.submit()').'</td><td align="right">'.botao('cancelar', 'Cancelar','Cancelar a '.($item ? 'edição' : 'inserção').' do item.','','url_passar(0, \'m=tarefas&a=gasto&tarefa_id='.$tarefa_id.'\');').'</td></tr></table></td></tr>';
	echo '</table></td></tr>';
	}
echo '</td></tr></table></form>';
if (!$imprimindo) echo estiloFundoCaixa();
else echo '<script>self.print();</script>';
?>
<script language="javascript">
	
function mudar_nd(){
	xajax_mudar_nd_ajax(env.tarefa_gastos_nd.value, 'tarefa_gastos_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3,env.tarefa_gastos_categoria_economica.value,env.tarefa_gastos_grupo_despesa.value,env.tarefa_gastos_modalidade_aplicacao.value);
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
	
		
function valor(){
	var custo=moeda2float(document.getElementById('tarefa_gastos_custo').value);	
	var qnt=moeda2float(document.getElementById('tarefa_gastos_quantidade').value);	
	if (custo=='') custo=0;
	if (valor=='') valor=0;
	document.getElementById('total').innerHTML ='<b><?php echo $config["simbolo_moeda"]?>'+float2moeda(custo*qnt)+'</b>';
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


<?php if ($acao=='inserir' || $acao=='editar') echo 'valor();'; ?>

</script>


