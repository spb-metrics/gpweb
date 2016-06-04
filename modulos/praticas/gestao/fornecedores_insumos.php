<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$pg_fornecedor_id= getParam($_REQUEST, 'pg_fornecedor_id', '0');
$pg_fornecedor_nome=getParam($_REQUEST, 'pg_fornecedor_nome', '0');
$pg_fornecedor_insumo=getParam($_REQUEST, 'pg_fornecedor_insumo', '0');
$excluirfornecedor=getParam($_REQUEST, 'excluirfornecedor', '0');
$editarfornecedor=getParam($_REQUEST, 'editarfornecedor', '0');
$mudar_pg_fornecedor_id=getParam($_REQUEST, 'mudar_pg_fornecedor_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');

echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';

echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_fornecedor_id" value="" />';
echo '<input type="hidden" name="mudar_pg_fornecedor_id" value="" />';
echo '<input type="hidden" name="excluirfornecedor" value="" />';
echo '<input type="hidden" name="editarfornecedor" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($inserir && $pg_fornecedor_insumo && !$mudar_pg_fornecedor_id && !$cancelar){
 	$sql->adTabela('plano_gestao_fornecedores');
	$sql->adCampo('count(pg_fornecedor_id) AS soma');
	$sql->adOnde('pg_fornecedor_pg_id ='.(int)$pg_id);	
  $soma_total = 1+(int)$sql->Resultado();
  $sql->Limpar();
	$sql->adTabela('plano_gestao_fornecedores');
	$sql->adInserir('pg_fornecedor_pg_id', $pg_id);
	$sql->adInserir('pg_fornecedor_nome', $pg_fornecedor_nome);
	$sql->adInserir('pg_fornecedor_ordem', $soma_total);
	$sql->adInserir('pg_fornecedor_insumo', $pg_fornecedor_insumo);
	$sql->adInserir('pg_fornecedor_data', date('Y-m-d H:i:s'));
	$sql->adInserir('pg_fornecedor_usuario', $Aplic->usuario_id);
	$sql->exec();
	$pg_fornecedor_id=$bd->Insert_ID('plano_gestao_fornecedores','pg_fornecedor_id');
	$sql->Limpar();
	}
if ($alterar && $pg_fornecedor_insumo && $mudar_pg_fornecedor_id && !$cancelar){
 	
	$sql->adTabela('plano_gestao_fornecedores');
	$sql->adAtualizar('pg_fornecedor_nome', $pg_fornecedor_nome);
	$sql->adAtualizar('pg_fornecedor_insumo', $pg_fornecedor_insumo);
	$sql->adAtualizar('pg_fornecedor_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('pg_fornecedor_usuario', $Aplic->usuario_id);
	$sql->adOnde('pg_fornecedor_id ='.(int)$mudar_pg_fornecedor_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirfornecedor){
	$sql->setExcluir('plano_gestao_fornecedores');
	$sql->adOnde('pg_fornecedor_id='.(int)$pg_fornecedor_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela plano_gestao_fornecedores!'.$bd->stderr(true));
	$sql->limpar();	
	}


//ordenar fornecedores
if($direcao&&$pg_fornecedor_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_fornecedores');
		$sql->adOnde('pg_fornecedor_id !='.(int)$pg_fornecedor_id);
		$sql->adOnde('pg_fornecedor_pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_fornecedor_ordem');
		$fornecedores = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_ordem;
			$novo_ui_ordem = count($fornecedores) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($fornecedores) + 1)) {
			$sql->adTabela('plano_gestao_fornecedores');
			$sql->adAtualizar('pg_fornecedor_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_fornecedor_id ='.(int)$pg_fornecedor_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($fornecedores as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_fornecedores');
					$sql->adAtualizar('pg_fornecedor_ordem', $idx);
					$sql->adOnde('pg_fornecedor_id ='.(int)$acao['pg_fornecedor_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_gestao_fornecedores');
					$sql->adAtualizar('pg_fornecedor_ordem', $idx + 1);
					$sql->adOnde('pg_fornecedor_id ='.(int)$acao['pg_fornecedor_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}

	

echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de Fornecedores e Insumos</h1></td></tr>'; 

//fornecedores e insumos

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Fornecedor</b></td><td><b>Insumo</b></td><td></td></tr>';
	if ($editarfornecedor) {
		$sql->adTabela('plano_gestao_fornecedores');
		$sql->adCampo('pg_fornecedor_nome, pg_fornecedor_insumo');
		$sql->adOnde('pg_fornecedor_id='.(int)$pg_fornecedor_id);
		$fornecedor=$sql->Linha();
		
		echo '<tr>';
		echo '<td><input type="text" name="pg_fornecedor_nome" size="40" class="texto" value="'.$fornecedor['pg_fornecedor_nome'].'" /></td>';
		echo '<td><input type="text" name="pg_fornecedor_insumo" size="80" class="texto" value="'.$fornecedor['pg_fornecedor_insumo'].'" /></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.alterar.value=1; env.mudar_pg_fornecedor_id.value='.(int)$pg_fornecedor_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Altera��es', 'Clique neste �cone '.imagem('icones/ok.png').' para aceitar as altera��es no fornecedor e insumo inserido � esquerda.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Altera��es', 'Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar as altera��es no fornecedor e insumo inserido � esquerda.').'</a></td>';
		
		}
	else {
		echo '<tr><td><input type="text" name="pg_fornecedor_nome" value="" size="40" class="texto" /></td><td><input type="text" name="pg_fornecedor_insumo" value="" size="80" maxlength="200" class="texto" /></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Inserir Fornecedor e Insumo', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar o fornecedor e insumo inserido � esquerda.').'</a></td>';
		}
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_fornecedores');
$sql->adCampo('*');
$sql->adOnde('pg_fornecedor_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_fornecedor_ordem ASC');
$fornecedores=$sql->Lista();


if ($fornecedores && count($fornecedores)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;Fornecedor'.(count($fornecedores)>1 ? 'es':'').'&nbsp;</th><th>&nbsp;Insumo'.(count($fornecedores)>1 ? 's':'').'&nbsp;</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($fornecedores as $fornecedor) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Quem Inseriu</b></td><td>'.nome_funcao('', '', '', '',$fornecedor['pg_fornecedor_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($fornecedor['pg_fornecedor_data']).'</td></tr>';
	$dentro .= '</table>';
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$fornecedor['pg_fornecedor_ordem'].'; env.pg_fornecedor_id.value='.(int)$fornecedor['pg_fornecedor_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$fornecedor['pg_fornecedor_ordem'].'; env.pg_fornecedor_id.value='.(int)$fornecedor['pg_fornecedor_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$fornecedor['pg_fornecedor_ordem'].'; env.pg_fornecedor_id.value='.(int)$fornecedor['pg_fornecedor_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$fornecedor['pg_fornecedor_ordem'].'; env.pg_fornecedor_id.value='.(int)$fornecedor['pg_fornecedor_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td>&nbsp;'.dica($fornecedor['pg_fornecedor_nome'],$dentro).$fornecedor['pg_fornecedor_nome'].'&nbsp;</td><td>&nbsp;'.$fornecedor['pg_fornecedor_insumo'].'&nbsp;</td>';
	if ($editarPG) {
		echo '<td><a href="javascript: void(0);" onclick="env.editarfornecedor.value=1; env.pg_fornecedor_id.value='.(int)$fornecedor['pg_fornecedor_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Fornecedor e Insumo', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o fornecedor com o seu respectivo insumo.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este fornecedor e insumo?\')) {env.excluirfornecedor.value=1; env.pg_fornecedor_id.value='.(int)$fornecedor['pg_fornecedor_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Fornecedor e Insumo', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir o fornecedor com o seu respectivo insumo.').'</a></td>';
		}
	echo '</tr>';
	}
if ($fornecedores && count($fornecedores)) echo '</table></td></tr>';





echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'fornecedores_insumos_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\'processos_produtos_servicos\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';


?>
