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

$uuid = getParam($_REQUEST, 'uuid', '');
$item = getParam($_REQUEST, 'item', 0);
$plano_acao_item_gastos_plano_acao_item = (isset($_REQUEST['id']) ? getParam($_REQUEST, 'id', 0)  : getParam($_REQUEST, 'plano_acao_item_gastos_plano_acao_item', 0));
$ordem = getParam($_REQUEST, 'ordem', 0);
$acao = getParam($_REQUEST, 'acao', '');
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');

if (!$item) $item = getParam($_REQUEST, 'item', 0);

$plano_acao_item_gastos_nome = getParam($_REQUEST, 'plano_acao_item_gastos_nome', '');
$plano_acao_item_gastos_tipo = getParam($_REQUEST, 'plano_acao_item_gastos_tipo', 0);
$plano_acao_item_gastos_nd = getParam($_REQUEST, 'plano_acao_item_gastos_nd', '');
$plano_acao_item_gastos_quantidade = float_americano(getParam($_REQUEST, 'plano_acao_item_gastos_quantidade', 0));
$plano_acao_item_gastos_custo = float_americano(getParam($_REQUEST, 'plano_acao_item_gastos_custo', 0));
$plano_acao_item_gastos_descricao = getParam($_REQUEST, 'plano_acao_item_gastos_descricao', '');
$plano_acao_item_gastos_data_recebido= getParam($_REQUEST, 'plano_acao_item_gastos_data_recebido', '');
$plano_acao_item_gastos_categoria_economica = getParam($_REQUEST, 'plano_acao_item_gastos_categoria_economica', '');
$plano_acao_item_gastos_grupo_despesa = getParam($_REQUEST, 'plano_acao_item_gastos_grupo_despesa', '');
$plano_acao_item_gastos_modalidade_aplicacao = getParam($_REQUEST, 'plano_acao_item_gastos_modalidade_aplicacao', '');



$unidade= getSisValor('TipoUnidade');
$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela('plano_acao_item_h_gastos');
$sql->adCampo('count(h_gastos_id) as total');
if ($uuid) $sql->adOnde('uuid =\''.$uuid.'\'');
else $sql->adOnde('h_gastos_plano_acao_item ='.(int)$plano_acao_item_gastos_plano_acao_item);
$historico= $sql->Resultado();
$sql->limpar();
if ($acao=='excluir'&& $item){

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('*');
	$sql->adOnde('plano_acao_item_gastos_id ='.(int)$item);
	$excluido=$sql->Linha();
	$sql->limpar();
	
	$sql->adTabela('plano_acao_item_h_gastos');
	$sql->adInserir('h_gastos_plano_acao_item_gastos_id', (int)$item);
	$sql->adInserir('h_gastos_nome1', $excluido['plano_acao_item_gastos_nome']);
	$sql->adInserir('h_gastos_plano_acao_item', $excluido['plano_acao_item_gastos_plano_acao_item']);
	$sql->adInserir('h_gastos_tipo1', $excluido['plano_acao_item_gastos_tipo']);
	$sql->adInserir('h_gastos_quantidade1', $excluido['plano_acao_item_gastos_quantidade']);
	$sql->adInserir('h_gastos_custo1', $excluido['plano_acao_item_gastos_custo']);
	$sql->adInserir('h_gastos_nd1', $excluido['plano_acao_item_gastos_nd']);
	$sql->adInserir('h_gastos_categoria_economica1', $excluido['plano_acao_item_gastos_categoria_economica']);
	$sql->adInserir('h_gastos_grupo_despesa1', $excluido['plano_acao_item_gastos_grupo_despesa']);
	$sql->adInserir('h_gastos_modalidade_aplicacao1', $excluido['plano_acao_item_gastos_modalidade_aplicacao']);
	$sql->adInserir('h_gastos_descricao1', $excluido['plano_acao_item_gastos_descricao']);
	$sql->adInserir('h_gastos_usuario1', $excluido['plano_acao_item_gastos_usuario']);
	$sql->adInserir('h_gastos_usuario2', $Aplic->usuario_id);
	$sql->adInserir('h_gastos_excluido', 1);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('h_gastos_data1', $excluido['plano_acao_item_gastos_data']);
	$sql->adInserir('h_gastos_data2', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item '.$excluido['plano_acao_item_gastos_nome'].'")</script>';
	$sql->limpar();
	
	$sql->setExcluir('plano_acao_item_gastos');
	$sql->adOnde('plano_acao_item_gastos_id='.(int)$item);
	$sql->exec();
	$sql->limpar();
	}
if ($acao=='c_editar' && $item){

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('*');
	$sql->adOnde('plano_acao_item_gastos_id ='.(int)$item);
	$editado= $sql->Linha();
	$sql->limpar();
	
	$sql->adTabela('plano_acao_item_h_gastos');
	$sql->adInserir('h_gastos_plano_acao_item_gastos_id', (int)$item);
	$sql->adInserir('h_gastos_nome1', $editado['plano_acao_item_gastos_nome']);
	$sql->adInserir('h_gastos_plano_acao_item', $editado['plano_acao_item_gastos_plano_acao_item']);
	$sql->adInserir('h_gastos_tipo1', $editado['plano_acao_item_gastos_tipo']);
	$sql->adInserir('h_gastos_quantidade1', $editado['plano_acao_item_gastos_quantidade']);
	$sql->adInserir('h_gastos_custo1', $editado['plano_acao_item_gastos_custo']);
	$sql->adInserir('h_gastos_nd1', $editado['plano_acao_item_gastos_nd']);
	$sql->adInserir('h_gastos_categoria_economica1', $editado['plano_acao_item_gastos_categoria_economica']);
	$sql->adInserir('h_gastos_grupo_despesa1', $editado['plano_acao_item_gastos_grupo_despesa']);
	$sql->adInserir('h_gastos_modalidade_aplicacao1', $editado['plano_acao_item_gastos_modalidade_aplicacao']);
	$sql->adInserir('h_gastos_descricao1', $editado['plano_acao_item_gastos_descricao']);
	$sql->adInserir('h_gastos_usuario1', $editado['plano_acao_item_gastos_usuario']);
	$sql->adInserir('h_gastos_usuario2', $Aplic->usuario_id);
	$sql->adInserir('h_gastos_nome2', $plano_acao_item_gastos_nome);
	$sql->adInserir('h_gastos_tipo2', $plano_acao_item_gastos_tipo);
	$sql->adInserir('h_gastos_quantidade2', $plano_acao_item_gastos_quantidade);
	$sql->adInserir('h_gastos_custo2', $plano_acao_item_gastos_custo);
	$sql->adInserir('h_gastos_nd2', $plano_acao_item_gastos_nd);
	$sql->adInserir('h_gastos_categoria_economica2', $plano_acao_item_gastos_categoria_economica);
	$sql->adInserir('h_gastos_grupo_despesa2', $plano_acao_item_gastos_grupo_despesa);
	$sql->adInserir('h_gastos_modalidade_aplicacao2', $plano_acao_item_gastos_modalidade_aplicacao);
	$sql->adInserir('h_gastos_descricao2', $plano_acao_item_gastos_descricao);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('h_gastos_data1', $editado['plano_acao_item_gastos_data']);
	$sql->adInserir('h_gastos_data2', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir no histórico os dados do item editado '.$editado['plano_acao_item_gastos_nome'].'")</script>';
	$sql->limpar();
	
	$sql->adTabela('plano_acao_item_gastos');
	$sql->adAtualizar('plano_acao_item_gastos_nome', $plano_acao_item_gastos_nome);
	$sql->adAtualizar('plano_acao_item_gastos_tipo', $plano_acao_item_gastos_tipo);
	$sql->adAtualizar('plano_acao_item_gastos_quantidade', $plano_acao_item_gastos_quantidade);
	$sql->adAtualizar('plano_acao_item_gastos_custo', $plano_acao_item_gastos_custo);
	$sql->adAtualizar('plano_acao_item_gastos_nd', $plano_acao_item_gastos_nd);
	$sql->adAtualizar('plano_acao_item_gastos_categoria_economica', $plano_acao_item_gastos_categoria_economica);
	$sql->adAtualizar('plano_acao_item_gastos_grupo_despesa', $plano_acao_item_gastos_grupo_despesa);
	$sql->adAtualizar('plano_acao_item_gastos_modalidade_aplicacao', $plano_acao_item_gastos_modalidade_aplicacao);
	$sql->adAtualizar('plano_acao_item_gastos_descricao', $plano_acao_item_gastos_descricao);
	$sql->adAtualizar('plano_acao_item_gastos_usuario', $Aplic->usuario_id);
	$sql->adAtualizar('plano_acao_item_gastos_data_recebido', $plano_acao_item_gastos_data_recebido);
	$data=date('Y-m-d H:m:s');
	$sql->adAtualizar('plano_acao_item_gastos_data', $data);
	$sql->adOnde('plano_acao_item_gastos_id = '.$item);
	if (!$sql->exec()) echo '<script>alert("Não foi possível alterar os dados do item '.$plano_acao_item_gastos_nome.'")</script>';
	$sql->limpar();
	
	}			
if ($acao=='c_inserir' && ($plano_acao_item_gastos_plano_acao_item || $uuid)){	

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('max(plano_acao_item_gastos_ordem) AS ultimo');
	if ($uuid) $sql->adOnde('uuid =\''.$uuid.'\'');
	else $sql->adOnde('plano_acao_item_gastos_plano_acao_item ='.(int)$plano_acao_item_gastos_plano_acao_item);
	$ultimo= $sql->Resultado();
	if ($ultimo) $ultimo++;
	else $ultimo=1;
	$sql->limpar();	
	
	$sql->adTabela('plano_acao_item_gastos');
	$sql->adInserir('plano_acao_item_gastos_nome', $plano_acao_item_gastos_nome);
	if ($uuid) $sql->adInserir('uuid', $uuid);
	else $sql->adInserir('plano_acao_item_gastos_plano_acao_item', $plano_acao_item_gastos_plano_acao_item);
	$sql->adInserir('plano_acao_item_gastos_tipo', $plano_acao_item_gastos_tipo);
	$sql->adInserir('plano_acao_item_gastos_quantidade', $plano_acao_item_gastos_quantidade);
	$sql->adInserir('plano_acao_item_gastos_custo', $plano_acao_item_gastos_custo);
	$sql->adInserir('plano_acao_item_gastos_nd', $plano_acao_item_gastos_nd);
	$sql->adInserir('plano_acao_item_gastos_categoria_economica', $plano_acao_item_gastos_categoria_economica);
	$sql->adInserir('plano_acao_item_gastos_grupo_despesa', $plano_acao_item_gastos_grupo_despesa);
	$sql->adInserir('plano_acao_item_gastos_modalidade_aplicacao', $plano_acao_item_gastos_modalidade_aplicacao);
	$sql->adInserir('plano_acao_item_gastos_descricao', $plano_acao_item_gastos_descricao);
	$sql->adInserir('plano_acao_item_gastos_ordem', $ultimo);
	$sql->adInserir('plano_acao_item_gastos_usuario', $Aplic->usuario_id);
	$sql->adInserir('plano_acao_item_gastos_data_recebido', $plano_acao_item_gastos_data_recebido);
	$data=date('Y-m-d H:m:s');
	$sql->adInserir('plano_acao_item_gastos_data', $data);
	if (!$sql->exec()) echo '<script>alert("Não foi possível inserir os dados do item '.$plano_acao_item_gastos_nome.'")</script>';
	$sql->limpar();
			
	}
if ($acao=='acima' && $item && ($ordem > 1)){

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('plano_acao_item_gastos_id');
	$sql->adOnde('plano_acao_item_gastos_ordem ='.($ordem-1));
	$anterior= $sql->Resultado();
	$sql->limpar();
	
	if ($anterior){
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', $ordem);
		$sql->adOnde('plano_acao_item_gastos_id = '.$anterior);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição anterior")</script>';
		$sql->limpar();
		
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', ($ordem-1));
		$sql->adOnde('plano_acao_item_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição anterior")</script>';
		$sql->limpar();
		
		}
	}	
if ($acao=='primeira' && $item && ($ordem > 1)){

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('plano_acao_item_gastos_id');
	$sql->adOnde('plano_acao_item_gastos_ordem <'.$ordem);
	$sql->adOrdem('plano_acao_item_gastos_ordem');
	$anteriores= $sql->Lista();
	$sql->limpar();
	
	$posicao=2;
	foreach ($anteriores as $linha) {
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', $posicao);
		$sql->adOnde('plano_acao_item_gastos_id = '.$linha['plano_acao_item_gastos_id']);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a primeira posição.")</script>';
		$sql->limpar();
		
		$posicao++;
		}
	if ($posicao > 2){
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', 1);
		$sql->adOnde('plano_acao_item_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a primeira posição.")</script>';
		$sql->limpar();
		
		}
	}	
if ($acao=='ultima' && $item){

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('plano_acao_item_gastos_id');
	$sql->adOnde('plano_acao_item_gastos_ordem >'.$ordem);
	$sql->adOrdem('plano_acao_item_gastos_ordem');
	$anteriores= $sql->Lista();
	$sql->limpar();
	
	$posicao=$ordem;
	foreach ($anteriores as $linha) {
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', $posicao);
		$sql->adOnde('plano_acao_item_gastos_id = '.$linha['plano_acao_item_gastos_id']);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a ultima posição.")</script>';
		$sql->limpar();
		
		$posicao++;
		}
	if ($posicao != $ordem){
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', $posicao);
		$sql->adOnde('plano_acao_item_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a ultima posição.")</script>';
		$sql->limpar();
		
		}
	}	
if ($acao=='abaixo' && $item){

	$sql->adTabela('plano_acao_item_gastos');
	$sql->adCampo('plano_acao_item_gastos_id');
	$sql->adOnde('plano_acao_item_gastos_ordem ='.($ordem+1));
	$proximo= $sql->Resultado();
	$sql->limpar();
	
	if ($proximo){
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', $ordem);
		$sql->adOnde('plano_acao_item_gastos_id = '.$proximo);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição posterior.")</script>';
		$sql->limpar();
		
		$sql->adTabela('plano_acao_item_gastos');
		$sql->adAtualizar('plano_acao_item_gastos_ordem', ($ordem+1));
		$sql->adOnde('plano_acao_item_gastos_id = '.$item);
		if (!$sql->exec()) echo '<script>alert("Não foi possível mudar para a posição posterior.")</script>';
		$sql->limpar();
		
		}
	}	
if ($acao=='editar' && $item){	

	$sql->adTabela('plano_acao_item_gastos', 'tc');
	$sql->adCampo('tc.*, (plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo) AS valor ');
	$sql->adOnde('tc.plano_acao_item_gastos_id ='.(int)$item);
	$atual= $sql->Linha();
	$sql->limpar();	
	
	}
if ($acao=='inserir') $ir='c_inserir';
elseif ($acao=='editar') $ir='c_editar';
else 	$ir='';

echo '<form name="frm" id="frm" method="post">';
echo '<input type="hidden" name="m" value="praticas" />';
echo '<input type="hidden" name="a" value="gasto" />';
if ($ir) echo '<input type="hidden" name="acao" value="'.$ir.'" />';
echo '<input type="hidden" name="item" value="'.$item.'" />';
echo '<input type="hidden" name="plano_acao_item_gastos_plano_acao_item" value="'.$plano_acao_item_gastos_plano_acao_item.'" />';
echo '<input type="hidden" name="uuid" value="'.$uuid.'" />';

echo '<table width="100%"><tr><td width="15%">&nbsp;</td><td width="70% align="center"><center><h1>Gastos Efetuados</h1></center></td><td align="right" width="15%">'.($historico ? dica('Histórico da Planilha', 'Clique neste ícone '.imagem('informacao.gif').' para visualizar as alterações na planilha de gastos gastos.').'<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=historico&dialogo=1&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&tipo=gasto\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')"><img src="'.acharImagem('informacao.gif').'" border=0 width="22" heigth="22" /></a>'.dicaF() : '').dica('Imprimir a Planilha', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir a planilha.').'<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=planilha_impressa&sem_cabecalho=1&dialogo=1&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&tipo=gasto\', \'Planilha\',\'height=500,width=1024,resizable,scrollbars=yes\')">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr></table>';
echo estiloTopoCaixa();
echo '<table width="100%" border=0 cellpadding=0 cellspacing=0 class="std2">';
if ($acao!='inserir' && $acao!='editar'){
	$nd=getSisValorND();
	
	echo '<tr><td valign="top" align="center">';
	$sql->adTabela('plano_acao_item_gastos', 'tc');
	$sql->adCampo('tc.*, (plano_acao_item_gastos_quantidade*plano_acao_item_gastos_custo) AS valor ');
	if ($uuid) $sql->adOnde('uuid =\''.$uuid.'\'');
	else $sql->adOnde('tc.plano_acao_item_gastos_plano_acao_item ='.(int)$plano_acao_item_gastos_plano_acao_item);
	$sql->adOrdem('plano_acao_item_gastos_ordem');
	$linhas= $sql->Lista();
	$sql->limpar();
	$qnt=0;
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
	echo '<tr><th>'.dica('Nome', 'Nome do item.').'Nome'.dicaF().'</th>
	<th>'.dica('Descrição', 'Descrição do item.').'Descrição'.dicaF().'</th>
	<th>'.dica('Unidade', 'A unidade de referência para o item.').'Unidade'.dicaF().'</th>
	<th>'.dica('Quantidade', 'A quantidade demandada do ítem').'Qnt.'.dicaF().'</th>
	<th>'.dica('Valor Unitário', 'O valor unitário de uma unidade do item.').'Valor ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
	($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
	'<th>'.dica('Natureza da Despesa', 'A natureza de despesa (ND) do item.').'ND'.dicaF().'</th>
	<th>'.dica('Valor Total em '.$config['simbolo_moeda'], 'O valor total é o preço unitário multiplicado pela quantidade.').'Total ('.$config['simbolo_moeda'].')'.dicaF().'</th>'.
	(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
	(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
	(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
	'<th>'.dica('Responsável', 'O '.$config['usuario'].' que inseriu ou alterou o item.').'Responsável'.dicaF().'</th><th>'.dica('Data Limite', 'A data limite para receber o material com oportunidade.').'Data'.dicaF().'</th>'.
	(!$Aplic->profissional ? '<th></th>' : '').'</tr>';
	
	$total=0;
	$gasto=array();
	foreach ($linhas as $linha) {
		echo '<tr align="center">';
		echo '<td align="left">'.++$qnt.' - '.$linha['plano_acao_item_gastos_nome'].'</td>';
		echo '<td align="left">'.($linha['plano_acao_item_gastos_descricao'] ? $linha['plano_acao_item_gastos_descricao'] : '&nbsp;').'</td>';
		echo '<td>'.$unidade[$linha['plano_acao_item_gastos_tipo']].'</td><td>'.number_format($linha['plano_acao_item_gastos_quantidade'], 2, ',', '.').'</td>';
		echo '<td align="right">'.number_format($linha['plano_acao_item_gastos_custo'], 2, ',', '.').'</td>';
		
		if ($config['bdi']) echo '<td align="right">'.number_format($linha['plano_acao_item_gastos_bdi'], 2, ',', '.').'</td>';
		$nd=($linha['plano_acao_item_gastos_categoria_economica'] && $linha['plano_acao_item_gastos_grupo_despesa'] && $linha['plano_acao_item_gastos_modalidade_aplicacao'] ? $linha['plano_acao_item_gastos_categoria_economica'].'.'.$linha['plano_acao_item_gastos_grupo_despesa'].'.'.$linha['plano_acao_item_gastos_modalidade_aplicacao'].'.' : '').$linha['plano_acao_item_gastos_nd'];
		echo '<td>'.$nd.'</td>';
		echo '<td align="right">'.number_format($linha['valor'], 2, ',', '.').'</td>';
		
		if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($linha['plano_acao_item_gastos_codigo'] ? $linha['plano_acao_item_gastos_codigo'] : '&nbsp;').'</td>';
		if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($linha['plano_acao_item_gastos_fonte'] ? $linha['plano_acao_item_gastos_fonte'] : '&nbsp;').'</td>';
		if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($linha['plano_acao_item_gastos_regiao'] ? $linha['plano_acao_item_gastos_regiao'] : '&nbsp;').'</td>'; 
		
		echo '<td align="left" nowrap="nowrap">'.link_usuario($linha['plano_acao_item_gastos_usuario'],'','','esquerda').'</td>';
		

		echo '<td>'.($linha['plano_acao_item_gastos_data_recebido'] ? retorna_data($linha['plano_acao_item_gastos_data_recebido'],false) : '&nbsp;').'</td>';
		if (!$Aplic->profissional){
			echo '<td width="72" align="right">';
			echo dica('Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição o item '.$linha['plano_acao_item_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gasto&acao=primeira&ordem='.$linha['plano_acao_item_gastos_ordem'].'&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&item='.$linha['plano_acao_item_gastos_id'].'\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover Acima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima o item '.$linha['plano_acao_item_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gasto&acao=acima&ordem='.$linha['plano_acao_item_gastos_ordem'].'&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&item='.$linha['plano_acao_item_gastos_id'].'\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover Abaixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo o item '.$linha['plano_acao_item_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gasto&acao=abaixo&ordem='.$linha['plano_acao_item_gastos_ordem'].'&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&item='.$linha['plano_acao_item_gastos_id'].'\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Última Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição o item '.$linha['plano_acao_item_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gasto&acao=ultima&ordem='.$linha['plano_acao_item_gastos_ordem'].'&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&item='.$linha['plano_acao_item_gastos_id'].'\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Editar Item', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o item '.$linha['plano_acao_item_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gasto&acao=editar&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&uuid='.$uuid.'&item='.$linha['plano_acao_item_gastos_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
			echo dica('Excluir Item', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o item '.$linha['plano_acao_item_gastos_nome'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gasto&acao=excluir&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'&uuid='.$uuid.'&item='.$linha['plano_acao_item_gastos_id'].'\');">'.imagem('icones/remover.png').'</a>'.dicaF();
			echo '</td>';
			}
		echo '</tr>';
		if (isset($gasto[$linha['plano_acao_item_gastos_nd']])) $gasto[$linha['plano_acao_item_gastos_nd']] += (float)$linha['valor'];
		else $gasto[$linha['plano_acao_item_gastos_nd']] =(float)$linha['valor'];
		$total+=$linha['valor'];
		}
	if ($total) {
			echo '<tr><td colspan="6" class="std" align="right">';
			foreach ($gasto as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.(isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND');
			echo '<br><b>Total</td><td align="right">';	
			foreach ($gasto as $indice_nd => $somatorio) if ($somatorio > 0) echo '<br>'.number_format($somatorio, 2, ',', '.');
			echo '<br><b>'.number_format($total, 2, ',', '.').'</b></td><td colspan="20">&nbsp;</td></tr>';	
			}
	if (!$qnt) echo '<tr><td colspan="20" class="std" align="left"><p>Nenhum item encontrado.</p></td></tr>';			
	echo '</table></td></tr>';
	if (!$Aplic->profissional) echo '<tr><td><table width="100%"><tr><td>'.botao('inserir', 'Inserir','Inserir um novo item.','','url_passar(0, \'m=praticas&a=gasto&acao=inserir&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'\');').'</td><td align="right">'.($Aplic->profissional ? '' : botao('fechar', 'fechar', 'Fechar esta janela.','','self.close();')).'</td></tr></table></td></tr>';
	}
else {	
	echo '<tr><td><table width="100%" border=0 cellpadding="2" cellspacing=0>';
	echo '<tr><td align="center" nowrap="nowrap" colspan="2"><h1>'.($item ? 'Editar Item' : 'Inserir Item').'</h1></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Escreva o nome deste item.').'Nome:'.dicaF().'</td><td><input type="text" class="texto" name="plano_acao_item_gastos_nome" value="'.(isset($atual['plano_acao_item_gastos_nome']) ? $atual['plano_acao_item_gastos_nome']:'').'" maxlength="255" size="40" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Unidade de Medida', 'Escolha a unidade de medida deste item.').'Unidade de medida:'.dicaF().'</td><td>'.selecionaVetor($unidade, 'plano_acao_item_gastos_tipo', 'class=texto size=1', (isset($atual['plano_acao_item_gastos_tipo']) ? $atual['plano_acao_item_gastos_tipo']:'')).'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantidade', 'Insira a quantidade deste item.').'Quantidade:'.dicaF().'</td><td><input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();"name="plano_acao_item_gastos_quantidade" id="plano_acao_item_gastos_quantidade" value="'.(isset($atual['plano_acao_item_gastos_quantidade']) ? number_format($atual['plano_acao_item_gastos_quantidade'], 2, ',', '.'):'').'" maxlength="255" size="10" /></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Valor', 'Insira o valor deste item.').'Valor:'.dicaF().'</td><td>'.$config['simbolo_moeda'].'&nbsp;<input type="text" class="texto" onkeypress="return entradaNumerica(event, this, true, true);" onchange="javascript:valor();" onclick="javascript:valor();" name="plano_acao_item_gastos_custo" id="plano_acao_item_gastos_custo" value="'.(isset($atual['plano_acao_item_gastos_custo']) ? number_format($atual['plano_acao_item_gastos_custo'], 2, ',', '.'):'').'" size="40" /></td></tr>';
	
	$categoria_economica=array(''=>'')+getSisValor('CategoriaEconomica');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Categoria Econômica', 'Escolha a categoria econômica deste item.').'Categoria econômica:'.dicaF().'</td><td>'.selecionaVetor($categoria_economica, 'plano_acao_item_gastos_categoria_economica', 'class=texto size=1 style="width:395px;" onchange="frm.plano_acao_item_gastos_nd.value=\'\'; mudar_nd();"', (isset($atual['plano_acao_item_gastos_categoria_economica']) ? $atual['plano_acao_item_gastos_categoria_economica']:'')).'</td></tr>';
	
	$GrupoND=array(''=>'')+getSisValor('GrupoND');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Grupo de Despesa', 'Escolha o grupo de despesa deste item.').'Grupo de despesa:'.dicaF().'</td><td>'.selecionaVetor($GrupoND, 'plano_acao_item_gastos_grupo_despesa', 'class=texto size=1 style="width:395px;" onchange="frm.plano_acao_item_gastos_nd.value=\'\'; mudar_nd();"', (isset($atual['plano_acao_item_gastos_grupo_despesa']) ? $atual['plano_acao_item_gastos_grupo_despesa']:'')).'</td></tr>';
	
	$ModalidadeAplicacao=array(''=>'')+getSisValor('ModalidadeAplicacao');
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Modalidade de Aplicação', 'Escolha a modalidade de aplicação deste item.').'Modalidade de aplicação:'.dicaF().'</td><td>'.selecionaVetor($ModalidadeAplicacao, 'plano_acao_item_gastos_modalidade_aplicacao', 'class=texto size=1 style="width:395px;" onchange="frm.plano_acao_item_gastos_nd.value=\'\'; mudar_nd();"', (isset($atual['plano_acao_item_gastos_modalidade_aplicacao']) ? $atual['plano_acao_item_gastos_modalidade_aplicacao']:'')).'</td></tr>';
	
	$nd=vetor_nd((isset($atual['plano_acao_item_gastos_nd']) ? $atual['plano_acao_item_gastos_nd'] : ''), null, null, 3 ,(isset($atual['plano_acao_item_gastos_categoria_economica']) ?  $atual['plano_acao_item_gastos_categoria_economica'] : ''), (isset($atual['plano_acao_item_gastos_grupo_despesa']) ?  $atual['plano_acao_item_gastos_grupo_despesa'] : ''), (isset($atual['plano_acao_item_gastos_modalidade_aplicacao']) ?  $atual['plano_acao_item_gastos_modalidade_aplicacao'] : ''));
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Elemento de Despesa', 'Escolha o elemento de despesa (ED) deste item.').'Elemento de despesa:'.dicaF().'</td><td><div id="combo_nd">'.selecionaVetor($nd, 'plano_acao_item_gastos_nd', 'class=texto size=1 style="width:395px;" onchange="mudar_nd();"', (isset($atual['plano_acao_item_gastos_nd']) ? $atual['plano_acao_item_gastos_nd']: '')).'</div></td></tr>';
	
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Total', 'O valor total do item.').'Total:'.dicaF().'</td><td><div id="total"></div></td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Descrição', 'Insira a descrição deste item.').'Descrição:'.dicaF().'</td><td><textarea cols="70" rows="5" class="textarea" name="plano_acao_item_gastos_descricao">'.(isset($atual['plano_acao_item_gastos_descricao']) ? $atual['plano_acao_item_gastos_descricao']:'').'</textarea></td></tr>';

	$data = (isset($atual['plano_acao_item_gastos_data_recebido']) ? $atual['plano_acao_item_gastos_data_recebido'] : '');
	$data_texto = intval($data) ? new CData($data) : new CData();
	echo '<tr><td align="right">'.dica('Data','Data limite para o recebimento do ítem.').'Data:</td><td><table cellpadding=0 cellspacing=0><tr><td><td><input type="hidden" name="plano_acao_item_gastos_data_recebido" id="plano_acao_item_gastos_data_recebido" value="'.($data_texto ? $data_texto->format(FMT_TIMESTAMP_DATA) : '').'" /><input type="text" name="data_texto"  id="data_texto" style="width:70px;" onchange="setData(\'env\', \'data_texto\', \'plano_acao_item_gastos_data_recebido\');" value="'.($data_texto ? $data_texto->format($df) : '').'" class="texto" />'.dica('Data Limite', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data limite para o recebimento do ítem.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" width="18" height="12" alt="Calendário" border=0 /></a>'.dicaF().'</td></tr></table></td></tr>';
	
	echo '<tr><td colspan="2"><table width="100%"><tr><td>'.botao('salvar', 'Salvar','Salvar a '.($item ? 'edição' : 'inserção').' do item.','','frm.submit()').'</td><td align="right">'.botao('cancelar', 'Cancelar','Cancelar a '.($item ? 'edição' : 'inserção').' do item.','','url_passar(0, \'m=praticas&a=gasto&uuid='.$uuid.'&plano_acao_item_gastos_plano_acao_item='.$plano_acao_item_gastos_plano_acao_item.'\');').'</td></tr></table></td></tr>';
	echo '</table></td></tr>';
	}
echo '</td></tr></table></form>';
echo estiloFundoCaixa();

?>

<script language="javascript">
function mudar_nd(){
	xajax_mudar_nd_ajax(frm.plano_acao_item_gastos_nd.value, 'plano_acao_item_gastos_nd', 'combo_nd','class=texto size=1 style="width:395px;" onchange="mudar_nd();"', 3, frm.plano_acao_item_gastos_categoria_economica.value, frm.plano_acao_item_gastos_grupo_despesa.value, frm.plano_acao_item_gastos_modalidade_aplicacao.value);
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
    inputField : "plano_acao_item_gastos_data_recebido",
  	date :  <?php echo $data_texto->format("%Y%m%d")?>,
  	selection: <?php echo $data_texto->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_texto").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("plano_acao_item_gastos_data_recebido").value = Calendario.printDate(date, "%Y-%m-%d");
      }
  	cal1.hide(); 
  	}
  });	

function setData( frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.'+frm_nome+'.'+f_data);
	campo_data_real = eval( 'document.'+frm_nome+'.'+f_data_real);
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
	var gasto=moeda2float(document.getElementById('plano_acao_item_gastos_custo').value);	
	var qnt=moeda2float(document.getElementById('plano_acao_item_gastos_quantidade').value);	
	
	if (gasto=='') gasto=0;
	if (valor=='') valor=0;
	document.getElementById('total').innerHTML ='<b><?php echo $config["simbolo_moeda"]?>'+float2moeda(gasto*qnt)+'</b>';
	}	



valor();

<?php } ?>	
</script>


