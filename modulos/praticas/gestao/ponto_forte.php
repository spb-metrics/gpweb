<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if ($editarPG) {
    $Aplic->carregarCKEditorJS();
	}
$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$pg_ponto_forte_id= getParam($_REQUEST, 'pg_ponto_forte_id', '0');
$pg_ponto_forte_nome=getParam($_REQUEST, 'pg_ponto_forte_nome', '0');


$excluirponto_forte=getParam($_REQUEST, 'excluirponto_forte', '0');
$editarponto_forte=getParam($_REQUEST, 'editarponto_forte', '0');
$mudar_pg_ponto_forte_id=getParam($_REQUEST, 'mudar_pg_ponto_forte_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');
echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_ponto_forte_id" value="" />';
echo '<input type="hidden" name="mudar_pg_ponto_forte_id" value="" />';
echo '<input type="hidden" name="excluirponto_forte" value="" />';
echo '<input type="hidden" name="editarponto_forte" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($inserir && !$mudar_pg_ponto_forte_id && !$cancelar){
 	$sql->adTabela('plano_gestao_pontosfortes');
	$sql->adCampo('count(pg_ponto_forte_id) AS soma');
	$sql->adOnde('pg_ponto_forte_pg_id ='.(int)$pg_id);
  $soma_total = 1+(int)$sql->Resultado();
  $sql->Limpar();
	$sql->adTabela('plano_gestao_pontosfortes');
	$sql->adInserir('pg_ponto_forte_pg_id', $pg_id);
	$sql->adInserir('pg_ponto_forte_nome', $pg_ponto_forte_nome);
	$sql->adInserir('pg_ponto_forte_ordem', $soma_total);
	$sql->adInserir('pg_ponto_forte_data', date('Y-m-d H:i:s'));
	$sql->adInserir('pg_ponto_forte_usuario', $Aplic->usuario_id);
	$sql->exec();
	$pg_ponto_forte_id=$bd->Insert_ID('plano_gestao_pontosfortes','pg_ponto_forte_id');
	$sql->Limpar();
	}
if ($alterar && $mudar_pg_ponto_forte_id && !$cancelar){
	$sql->adTabela('plano_gestao_pontosfortes');
	$sql->adAtualizar('pg_ponto_forte_nome', $pg_ponto_forte_nome);
	$sql->adAtualizar('pg_ponto_forte_data', date('Y-m-d H:i:s'));
	$sql->adAtualizar('pg_ponto_forte_usuario', $Aplic->usuario_id);
	$sql->adOnde('pg_ponto_forte_id ='.(int)$mudar_pg_ponto_forte_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirponto_forte){
	$sql->setExcluir('plano_gestao_pontosfortes');
	$sql->adOnde('pg_ponto_forte_id='.(int)$pg_ponto_forte_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela plano_gestao_pontosfortes!'.$bd->stderr(true));
	$sql->limpar();
	}


//ordenar ponto_fortees
if($direcao&&$pg_ponto_forte_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_pontosfortes');
		$sql->adOnde('pg_ponto_forte_id !='.(int)$pg_ponto_forte_id);
		$sql->adOnde('pg_ponto_forte_pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_ponto_forte_ordem');
		$pontos_fortes = $sql->Lista();
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
			$novo_ui_ordem = count($pontos_fortes) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($pontos_fortes) + 1)) {
			$sql->adTabela('plano_gestao_pontosfortes');
			$sql->adAtualizar('pg_ponto_forte_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_ponto_forte_id ='.(int)$pg_ponto_forte_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($pontos_fortes as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_pontosfortes');
					$sql->adAtualizar('pg_ponto_forte_ordem', $idx);
					$sql->adOnde('pg_ponto_forte_id ='.(int)$acao['pg_ponto_forte_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					}
				else {
					$sql->adTabela('plano_gestao_pontosfortes');
					$sql->adAtualizar('pg_ponto_forte_ordem', $idx + 1);
					$sql->adOnde('pg_ponto_forte_id ='.(int)$acao['pg_ponto_forte_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}
			}
		}


echo '<table width="100%" >';
echo '<tr><td colspan=2 align="left"><h1>Ambiente Interno - Lista de Forças</h1></td></tr>';

//ponto_fortes

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Força</b></td><td></td></tr>';
	if ($editarponto_forte) {
		$sql->adTabela('plano_gestao_pontosfortes');
		$sql->adCampo('pg_ponto_forte_nome');
		$sql->adOnde('pg_ponto_forte_id='.(int)$pg_ponto_forte_id);
		$ponto_forte=$sql->Linha();
		$sql->limpar();
		echo '<tr>';
		echo '<td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_ponto_forte_nome" id="pg_ponto_forte_nome" style="width:800px; max-width:800px;">'.$ponto_forte['pg_ponto_forte_nome'].'</textarea></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.alterar.value=1; env.mudar_pg_ponto_forte_id.value='.(int)$pg_ponto_forte_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Alterações', 'Clique neste ícone '.imagem('icones/ok.png').' para aceitar as alterações no ponto forte inserido à esquerda.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Alterações', 'Clique neste ícone '.imagem('icones/cancelar.png').' para cancelar as alterações no ponto forte inserido à esquerda.').'</a></td>';

		}
	else {
		echo '<tr><td width="810"><textarea data-gpweb-cmp="ckeditor" rows="3" name="pg_ponto_forte_nome" id="pg_ponto_forte_nome" ></textarea></td>';
		echo '<td><a href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Inserir Força', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar o ponto forte à esquerda.').'</a></td>';
		}
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_pontosfortes');
$sql->adCampo('*');
$sql->adOnde('pg_ponto_forte_pg_id='.(int)$pg_id);
$sql->adOrdem('pg_ponto_forte_ordem ASC');
$pontos_fortes=$sql->Lista();
$sql->limpar();

if ($pontos_fortes && count($pontos_fortes)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="810"><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($pontos_fortes)>1 ? 'Forças':'Força').'&nbsp;</th>'.($editarPG ? '<th width="32"></th>' : '').'</tr>';
foreach ($pontos_fortes as $ponto_forte) {
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Quem Inseriu</b></td><td>'.nome_funcao('', '', '', '',$ponto_forte['pg_ponto_forte_usuario']).'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($ponto_forte['pg_ponto_forte_data']).'</td></tr>';
	$dentro .= '</table>';
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$ponto_forte['pg_ponto_forte_ordem'].'; env.pg_ponto_forte_id.value='.(int)$ponto_forte['pg_ponto_forte_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$ponto_forte['pg_ponto_forte_ordem'].'; env.pg_ponto_forte_id.value='.(int)$ponto_forte['pg_ponto_forte_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$ponto_forte['pg_ponto_forte_ordem'].'; env.pg_ponto_forte_id.value='.(int)$ponto_forte['pg_ponto_forte_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$ponto_forte['pg_ponto_forte_ordem'].'; env.pg_ponto_forte_id.value='.(int)$ponto_forte['pg_ponto_forte_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.$ponto_forte['pg_ponto_forte_nome'].'</td>';
	if ($editarPG) {
		echo '<td><a href="javascript: void(0);" onclick="env.editarponto_forte.value=1; env.pg_ponto_forte_id.value='.(int)$ponto_forte['pg_ponto_forte_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Força', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o ponto_forte.').'</a>';
		echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta força?\')) {env.excluirponto_forte.value=1; env.pg_ponto_forte_id.value='.(int)$ponto_forte['pg_ponto_forte_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Força', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o ponto forte.').'</a></td>';
		}
	echo '</tr>';
	}
if ($pontos_fortes && count($pontos_fortes)) echo '</table></td></tr>';



echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'ponto_forte_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('próximo', 'Próximo', 'Ir para a próxima tela.','','carregar(\'oportunidade_melhoria_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';

?>
