<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$pg_pessoal_id= getParam($_REQUEST, 'pg_pessoal_id', '0');
$pg_pessoal_posto=getParam($_REQUEST, 'pg_pessoal_posto', '0');
$pg_pessoal_previsto=getParam($_REQUEST, 'pg_pessoal_previsto', '0');
$pg_pessoal_existente=getParam($_REQUEST, 'pg_pessoal_existente', '0');
$excluirpessoa=getParam($_REQUEST, 'excluirpessoa', '0');
$editarpessoal=getParam($_REQUEST, 'editarpessoal', '0');
$mudar_pg_pessoal_id=getParam($_REQUEST, 'mudar_pg_pessoal_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_pessoal_id" value="" />';
echo '<input type="hidden" name="mudar_pg_pessoal_id" value="" />';
echo '<input type="hidden" name="excluirpessoa" value="" />';
echo '<input type="hidden" name="editarpessoal" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($inserir && !$mudar_pg_pessoal_id && !$cancelar){
 	$sql->adTabela('plano_gestao_pessoal');
	$sql->adCampo('count(pg_pessoal_id) AS soma');
	$sql->adOnde('pg_pessoal_pg_id ='.(int)$pg_id);	
  $soma_total = 1+(int)$sql->Resultado();
  $sql->Limpar();
	$sql->adTabela('plano_gestao_pessoal');
	$sql->adInserir('pg_pessoal_pg_id', $pg_id);
	$sql->adInserir('pg_pessoal_posto', $pg_pessoal_posto);
	$sql->adInserir('pg_pessoal_ordem', $soma_total);
	$sql->adInserir('pg_pessoal_previsto', $pg_pessoal_previsto);
	$sql->adInserir('pg_pessoal_existente', $pg_pessoal_existente);
	$sql->adInserir('pg_pessoal_data', date('Y-m-d H:i:s'));
	$sql->adInserir('pg_pessoal_usuario', $Aplic->usuario_id);
	$sql->exec();
	$pg_pessoal_id=$bd->Insert_ID('plano_gestao_pessoal','pg_pessoal_id');
	$sql->Limpar();
	}
if ($alterar && $mudar_pg_pessoal_id && !$cancelar){
 	
	$sql->adTabela('plano_gestao_pessoal');
	$sql->adAtualizar('pg_pessoal_posto', $pg_pessoal_posto);
	$sql->adAtualizar('pg_pessoal_previsto', $pg_pessoal_previsto);
	$sql->adAtualizar('pg_pessoal_existente', $pg_pessoal_existente);
	$sql->adAtualizar('pg_pessoal_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('pg_pessoal_usuario', $Aplic->usuario_id);
	$sql->adOnde('pg_pessoal_id ='.(int)$mudar_pg_pessoal_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirpessoa){
	$sql->setExcluir('plano_gestao_pessoal');
	$sql->adOnde('pg_pessoal_id='.(int)$pg_pessoal_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela plano_gestao_pessoal!'.$bd->stderr(true));
	$sql->limpar();	
	}


//ordenar fornecedores
if($direcao&&$pg_pessoal_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_pessoal');
		$sql->adOnde('pg_pessoal_id !='.(int)$pg_pessoal_id);
		$sql->adOnde('pg_pessoal_pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_pessoal_ordem');
		$pessoal = $sql->Lista();
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
			$novo_ui_ordem = count($pessoal) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($pessoal) + 1)) {
			$sql->adTabela('plano_gestao_pessoal');
			$sql->adAtualizar('pg_pessoal_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_pessoal_id ='.(int)$pg_pessoal_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($pessoal as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_pessoal');
					$sql->adAtualizar('pg_pessoal_ordem', $idx);
					$sql->adOnde('pg_pessoal_id ='.(int)$acao['pg_pessoal_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_gestao_pessoal');
					$sql->adAtualizar('pg_pessoal_ordem', $idx + 1);
					$sql->adOnde('pg_pessoal_id ='.(int)$acao['pg_pessoal_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}

	


echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de Pessoal</h1></td></tr>'; 


//quadro de servidores militares

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Função</b></td><td><b>Previsto</b></td><td><b>Existente</b></td></tr>';
	if ($editarpessoal) {
		$sql->adTabela('plano_gestao_pessoal');
		$sql->adCampo('pg_pessoal_posto, pg_pessoal_previsto, pg_pessoal_existente');
		$sql->adOnde('pg_pessoal_id='.(int)$pg_pessoal_id);
		$pessoa=$sql->Linha();
		echo '<tr>';
		echo '<td><input type="text" name="pg_pessoal_posto" size="40" maxlength="50" class="texto" value="'.$pessoa['pg_pessoal_posto'].'" /></td>';
		echo '<td><input type="text" name="pg_pessoal_previsto" size="10" maxlength="10" class="texto" value="'.$pessoa['pg_pessoal_previsto'].'" /></td>';
		echo '<td><input type="text" name="pg_pessoal_existente" size="10" maxlength="10" class="texto" value="'.$pessoa['pg_pessoal_existente'].'" /></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.alterar.value=1; env.mudar_pg_pessoal_id.value='.(int)$pg_pessoal_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Alterações', 'Clique neste ícone '.imagem('icones/ok.png').' para aceitar as alterações inseridas nos dados à esquerda.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Alterações', 'Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar as alterações nos dados à esquerda.').'</a></td>';
		}
	else {
		echo '<td><input type="text" name="pg_pessoal_posto" size="40" maxlength="50" class="texto" /></td>';
		echo '<td><input type="text" name="pg_pessoal_previsto" size="10" maxlength="10" class="texto" /></td>';
		echo '<td><input type="text" name="pg_pessoal_existente" size="10" maxlength="10" class="texto" /></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Inserir os Dados', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar os dados à esquerda.').'</a></td>';
		}
	echo '</tr></table></td></tr>';
	}
$sql->adTabela('plano_gestao_pessoal');
$sql->adCampo('*');
$sql->adOnde('pg_pessoal_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_pessoal_ordem ASC');
$pessoal=$sql->Lista();
if ($pessoal && count($pessoal)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($pessoal)>1 ? 'Funções': 'Função').'&nbsp;</th><th>&nbsp;Previsto&nbsp;</th><th>&nbsp;Existe&nbsp;</th><th>&nbsp;Diferença&nbsp;</th>'.($editarPG ? '<th></th>' : '').'</tr>';
foreach ($pessoal as $pessoa) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Quem Inseriu</b></td><td>'.nome_funcao('', '', '', '',$pessoa['pg_pessoal_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($pessoa['pg_pessoal_data']).'</td></tr>';
	$dentro .= '</table>';
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$pessoa['pg_pessoal_ordem'].'; env.pg_pessoal_id.value='.(int)$pessoa['pg_pessoal_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$pessoa['pg_pessoal_ordem'].'; env.pg_pessoal_id.value='.(int)$pessoa['pg_pessoal_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$pessoa['pg_pessoal_ordem'].'; env.pg_pessoal_id.value='.(int)$pessoa['pg_pessoal_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$pessoa['pg_pessoal_ordem'].'; env.pg_pessoal_id.value='.(int)$pessoa['pg_pessoal_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	$diferença=(int)($pessoa['pg_pessoal_existente']-$pessoa['pg_pessoal_previsto']);		
	echo '<td>&nbsp;'.dica($pessoa['pg_pessoal_posto'],$dentro).$pessoa['pg_pessoal_posto'].'&nbsp;</td><td align="center">&nbsp;'.(int)$pessoa['pg_pessoal_previsto'].'&nbsp;</td><td align="center">&nbsp;'.(int)$pessoa['pg_pessoal_existente'].'&nbsp;</td><td align="center">&nbsp;'.($diferença>0 ? '+':'').$diferença.'&nbsp;</td>';
	if ($editarPG) {
		echo '<td><a href="javascript: void(0);" onclick="env.editarpessoal.value=1; env.pg_pessoal_id.value='.(int)$pessoa['pg_pessoal_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Fornecedor e Insumo', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o fornecedor com o seu respectivo insumo.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este fornecedor e insumo?\')) {env.excluirpessoa.value=1; env.pg_pessoal_id.value='.(int)$pessoa['pg_pessoal_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Fornecedor e Insumo', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o fornecedor com o seu respectivo insumo.').'</a></td>';
		}
	echo '</tr>';
	}
if ($pessoal && count($pessoal)) echo '</table></td></tr>';

	
	

echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'quadropessoal_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'programasacoes\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';

?>
