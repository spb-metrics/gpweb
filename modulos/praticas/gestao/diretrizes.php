<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if ($editarPG) {
    $Aplic->carregarCKEditorJS();
	}

$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$pg_diretriz_id= getParam($_REQUEST, 'pg_diretriz_id', '0');
$pg_diretriz_nome=getParam($_REQUEST, 'pg_diretriz_nome', '0');


$excluirdiretriz=getParam($_REQUEST, 'excluirdiretriz', '0');
$editardiretriz=getParam($_REQUEST, 'editardiretriz', '0');
$mudar_pg_diretriz_id=getParam($_REQUEST, 'mudar_pg_diretriz_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_diretriz_id" value="" />';
echo '<input type="hidden" name="mudar_pg_diretriz_id" value="" />';
echo '<input type="hidden" name="excluirdiretriz" value="" />';
echo '<input type="hidden" name="editardiretriz" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($inserir && !$mudar_pg_diretriz_id && !$cancelar){
 	$sql->adTabela('plano_gestao_diretrizes');
	$sql->adCampo('count(pg_diretriz_id) AS soma');
	$sql->adOnde('pg_diretriz_pg_id ='.(int)$pg_id);
  $soma_total = 1+(int)$sql->Resultado();
  $sql->Limpar();
	$sql->adTabela('plano_gestao_diretrizes');
	$sql->adInserir('pg_diretriz_pg_id', $pg_id);
	$sql->adInserir('pg_diretriz_nome', $pg_diretriz_nome);
	$sql->adInserir('pg_diretriz_ordem', $soma_total);
	$sql->adInserir('pg_diretriz_data', date('Y-m-d H:i:s'));
	$sql->adInserir('pg_diretriz_usuario', $Aplic->usuario_id);
	$sql->exec();
	$pg_diretriz_id=$bd->Insert_ID('plano_gestao_diretrizes','pg_diretriz_id');
	$sql->Limpar();
	}
if ($alterar && $mudar_pg_diretriz_id && !$cancelar){
	$sql->adTabela('plano_gestao_diretrizes');
	$sql->adAtualizar('pg_diretriz_nome', $pg_diretriz_nome);
	$sql->adAtualizar('pg_diretriz_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('pg_diretriz_usuario', $Aplic->usuario_id);
	$sql->adOnde('pg_diretriz_id ='.(int)$mudar_pg_diretriz_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirdiretriz){
	$sql->setExcluir('plano_gestao_diretrizes');
	$sql->adOnde('pg_diretriz_id='.(int)$pg_diretriz_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela plano_gestao_diretrizes!'.$bd->stderr(true));
	$sql->limpar();
	}


//ordenar diretrizes
if($direcao&&$pg_diretriz_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_diretrizes');
		$sql->adOnde('pg_diretriz_id !='.(int)$pg_diretriz_id);
		$sql->adOnde('pg_diretriz_pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_diretriz_ordem');
		$principios = $sql->Lista();
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
			$novo_ui_ordem = count($principios) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($principios) + 1)) {
			$sql->adTabela('plano_gestao_diretrizes');
			$sql->adAtualizar('pg_diretriz_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_diretriz_id ='.(int)$pg_diretriz_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($principios as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_diretrizes');
					$sql->adAtualizar('pg_diretriz_ordem', $idx);
					$sql->adOnde('pg_diretriz_id ='.(int)$acao['pg_diretriz_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('plano_gestao_diretrizes');
					$sql->adAtualizar('pg_diretriz_ordem', $idx + 1);
					$sql->adOnde('pg_diretriz_id ='.(int)$acao['pg_diretriz_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}


echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Lista de Diretrizes Internas</h1></td></tr>';


//diretrizs

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Diretriz</b></td><td></td></tr>';
	if ($editardiretriz) {
		$sql->adTabela('plano_gestao_diretrizes');
		$sql->adCampo('pg_diretriz_nome');
		$sql->adOnde('pg_diretriz_id='.(int)$pg_diretriz_id);
		$principio=$sql->Linha();

		echo '<tr>';
		echo '<td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_diretriz_nome" id="pg_diretriz_nome" style="width:800px; max-width:800px;">'.$principio['pg_diretriz_nome'].'</textarea></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.alterar.value=1; env.mudar_pg_diretriz_id.value='.(int)$pg_diretriz_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Altera��es', 'Clique neste �cone '.imagem('icones/ok.png').' para aceitar a altera��o inserida � esquerda.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Altera��es', 'Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar as altera��es � esquerda.').'</a></td>';

		}
	else {
		echo '<tr><td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_diretriz_nome" id="pg_diretriz_nome" ></textarea></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Inserir Diretriz', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar o texto � esquerda.').'</a></td>';
		}
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_diretrizes');
$sql->adCampo('*');
$sql->adOnde('pg_diretriz_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_diretriz_ordem ASC');
$principios=$sql->Lista();

if ($principios && count($principios)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="810"><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($principios) >1 ? 'Diretrizes':'Diretriz').'&nbsp;</th>'.($editarPG ? '<th width="32"></th>' : '').'</tr>';
foreach ($principios as $principio) {
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_diretriz_ordem'].'; env.pg_diretriz_id.value='.(int)$principio['pg_diretriz_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_diretriz_ordem'].'; env.pg_diretriz_id.value='.(int)$principio['pg_diretriz_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_diretriz_ordem'].'; env.pg_diretriz_id.value='.(int)$principio['pg_diretriz_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$principio['pg_diretriz_ordem'].'; env.pg_diretriz_id.value='.(int)$principio['pg_diretriz_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$principio['pg_diretriz_nome'].'</td>';
	if ($editarPG) {
		echo '<td><a href="javascript: void(0);" onclick="env.editardiretriz.value=1; env.pg_diretriz_id.value='.(int)$principio['pg_diretriz_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Diretriz', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o princ�pio, cren�a ou valor.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este principio, cren�a ou valor?\')) {env.excluirdiretriz.value=1; env.pg_diretriz_id.value='.(int)$principio['pg_diretriz_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Diretriz', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir o princ�pio, cren�a ou valor.').'</a></td>';
		}
	echo '</tr>';
	}
if ($principios && count($principios)) echo '</table></td></tr>';



echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'diretrizes_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\'perspectivas\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';
?>
