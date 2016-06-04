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
$tema_id= getParam($_REQUEST, 'tema_id', '0');
$tema_nome=getParam($_REQUEST, 'tema_nome', '0');
$tema_cor=getParam($_REQUEST, 'tema_cor', 'FFFFFF');

$excluirtema=getParam($_REQUEST, 'excluirtema', '0');
$editartema=getParam($_REQUEST, 'editartema', '0');
$mudar_tema_id=getParam($_REQUEST, 'mudar_tema_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');

$inserir=getParam($_REQUEST, 'inserir', '0');

echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="tema_id" value="" />';
echo '<input type="hidden" name="mudar_tema_id" value="" />';
echo '<input type="hidden" name="excluirtema" value="" />';
echo '<input type="hidden" name="editartema" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';



//salvar dados na tabela
if ($inserir && !$mudar_tema_id && !$cancelar){
 	
 	//checar se j� n�o existe
 	$sql->adTabela('plano_gestao_tema');
	$sql->adCampo('count(tema_id) AS soma');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
 	$sql->adOnde('tema_id ='.(int)$tema_id);	
 	$existe=$sql->resultado();
 	$sql->Limpar();
 	
 	if (!$existe){
	 	$sql->adTabela('plano_gestao_tema');
		$sql->adCampo('count(tema_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('plano_gestao_tema');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('tema_id', $tema_id);
		$sql->adInserir('tema_ordem', $soma_total);
		$sql->exec();
		$sql->Limpar();
		}
	else ver2('J� existe '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'!');
	}

if ($excluirtema){
	$sql->setExcluir('plano_gestao_tema');
	$sql->adOnde('tema_id='.(int)$tema_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela tema!'.$bd->stderr(true));
	$sql->limpar();	
	}


//ordenar temaes
if($direcao&&$tema_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_tema');
		$sql->adOnde('tema_id !='.(int)$tema_id);
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$sql->adOrdem('tema_ordem');
		$tema = $sql->Lista();
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
			$novo_ui_ordem = count($tema) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($tema) + 1)) {
			$sql->adTabela('plano_gestao_tema');
			$sql->adAtualizar('tema_ordem', $novo_ui_ordem);
			$sql->adOnde('tema_id ='.(int)$tema_id);
			$sql->adOnde('pg_id ='.(int)$pg_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($tema as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_tema');
					$sql->adAtualizar('tema_ordem', $idx);
					$sql->adOnde('pg_id ='.(int)$pg_id);
					$sql->adOnde('tema_id ='.(int)$acao['tema_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_gestao_tema');
					$sql->adAtualizar('tema_ordem', $idx + 1);
					$sql->adOnde('pg_id ='.(int)$pg_id);
					$sql->adOnde('tema_id ='.(int)$acao['tema_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}

	


echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de '.ucfirst($config['temas']).'</h1></td></tr>'; 
//tema

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>'.ucfirst($config['tema']).'</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="tema_nome" id="tema_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popTema();">'.imagem('icones/tema_p.png','Inserir '.ucfirst($config['tema']),'Clique neste �cone '.imagem('icones/tema_p.png').' para selecionar '.($config['genero_tema']=='o' ? 'um' : 'uma').' '.$config['tema'].' a ser inserid'.$config['genero_tema'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_tema');
$sql->esqUnir('tema', 'tema', 'plano_gestao_tema.tema_id=tema.tema_id');
$sql->adCampo('tema_nome, tema_cor, plano_gestao_tema.tema_ordem, tema.tema_id');
$sql->adOnde('plano_gestao_tema.pg_id='.(int)$pg_id);
$sql->adOrdem('tema_ordem ASC');
$tema=$sql->Lista();

if ($tema && count($tema)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="810"><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($tema) >1 ? ucfirst($config['temas']):ucfirst($config['tema'])).'&nbsp;</th>'.($editarPG ? '<th width="16"></th>' : '').'</tr>';
foreach ($tema as $tema) {
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$tema['tema_ordem'].'; env.tema_id.value='.(int)$tema['tema_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$tema['tema_ordem'].'; env.tema_id.value='.(int)$tema['tema_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$tema['tema_ordem'].'; env.tema_id.value='.(int)$tema['tema_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$tema['tema_ordem'].'; env.tema_id.value='.(int)$tema['tema_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td style="background-color: #'.$tema['tema_cor'].'">'.$tema['tema_nome'].'</td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir '.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'?\')) {env.excluirtema.value=1; env.tema_id.value='.(int)$tema['tema_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir '.ucfirst($config['tema']), 'Clique neste �cone '.imagem('icones/remover.png').' para excluir a tema.').'</a></td>';

	echo '</tr>';
	}
if ($tema && count($tema)) echo '</table></td></tr>';




echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'perspectivas\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\'objetivos_estrategicos_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script language="javascript">
	
function popTema() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["tema"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, window.setTema, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setTema&tabela=tema&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["tema"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	
	
function setTema(chave, valor){
	document.env.tema_id.value = chave;
	document.env.tema_nome.value = valor;
	document.env.inserir.value =1;
	env.submit();
	}	
	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.tema_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.tema_cor.value;
	}
</script>