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
$pg_estrategia_id= getParam($_REQUEST, 'pg_estrategia_id', '0');
$excluirestrategia=getParam($_REQUEST, 'excluirestrategia', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');

echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_estrategia_id" value="" />';
echo '<input type="hidden" name="excluirestrategia" value="" />';


//salvar dados na tabela
if ($inserir){
 	
 	//checar se j� n�o existe
 	$sql->adTabela('plano_gestao_estrategias');
	$sql->adCampo('count(pg_estrategia_id) AS soma');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
 	$sql->adOnde('pg_estrategia_id ='.(int)$pg_estrategia_id);	
 	$existe=$sql->resultado();
 	$sql->Limpar();
 	
 	if (!$existe){
	 	$sql->adTabela('plano_gestao_estrategias');
		$sql->adCampo('count(pg_estrategia_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('plano_gestao_estrategias');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('pg_estrategia_id', $pg_estrategia_id);
		$sql->adInserir('pg_estrategia_ordem', $soma_total);
		$sql->exec();
		$pg_estrategia_id=$bd->Insert_ID('plano_gestao_estrategias','pg_estrategia_id');
		$sql->Limpar();
		}
	else ver2('J� existe esta estrategia estrat�gica!');
	}

if ($excluirestrategia){
	$sql->setExcluir('plano_gestao_estrategias');
	$sql->adOnde('pg_estrategia_id='.(int)$pg_estrategia_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela estrategias!'.$bd->stderr(true));
	$sql->limpar();	
	}


//ordenar estrategiaes
if($direcao&&$pg_estrategia_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_estrategias');
		$sql->adOnde('pg_estrategia_id !='.(int)$pg_estrategia_id);
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_estrategia_ordem');
		$estrategias = $sql->Lista();
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
			$novo_ui_ordem = count($estrategias) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($estrategias) + 1)) {
			$sql->adTabela('plano_gestao_estrategias');
			$sql->adAtualizar('pg_estrategia_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_estrategia_id ='.(int)$pg_estrategia_id);
			$sql->adOnde('pg_id ='.(int)$pg_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($estrategias as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_estrategias');
					$sql->adAtualizar('pg_estrategia_ordem', $idx);
					$sql->adOnde('pg_id ='.(int)$pg_id);
					$sql->adOnde('pg_estrategia_id ='.(int)$acao['pg_estrategia_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_gestao_estrategias');
					$sql->adAtualizar('pg_estrategia_ordem', $idx + 1);
					$sql->adOnde('pg_id ='.(int)$pg_id);
					$sql->adOnde('pg_estrategia_id ='.(int)$acao['pg_estrategia_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}

	


echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de Iniciativas Estrat�gicas</h1></td></tr>'; 
//estrategias

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Iniciativa</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="pg_estrategia_nome" id="pg_estrategia_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popEstrategia();">'.imagem('icones/estrategia_p.gif','Selecionar '.ucfirst($config['iniciativa']),'Clique neste �cone '.imagem('icones/estrategia_p.gif').' para selecionar '.($config['genero_iniciativa']=='o' ? 'um' : 'uma').' '.$config['iniciativa'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_estrategias');
$sql->esqUnir('estrategias', 'estrategias', 'plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
$sql->adCampo('pg_estrategia_nome, pg_estrategia_cor, plano_gestao_estrategias.pg_estrategia_ordem, estrategias.pg_estrategia_id');
$sql->adOnde('plano_gestao_estrategias.pg_id='.(int)$pg_id);
$sql->adOrdem('pg_estrategia_ordem ASC');
$estrategias=$sql->Lista();

if ($estrategias && count($estrategias)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="810"><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($estrategias) >1 ? 'Iniciativas':'Iniciativa').'&nbsp;</th>'.($editarPG ? '<th width="16"></th>' : '').'</tr>';
foreach ($estrategias as $estrategia) {
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posi��o', 'Clique neste �cone '.imagem('icones/2setacima.gif').' para mover para a primeira posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$estrategia['pg_estrategia_ordem'].'; env.pg_estrategia_id.value='.(int)$estrategia['pg_estrategia_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste �cone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$estrategia['pg_estrategia_ordem'].'; env.pg_estrategia_id.value='.(int)$estrategia['pg_estrategia_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste �cone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$estrategia['pg_estrategia_ordem'].'; env.pg_estrategia_id.value='.(int)$estrategia['pg_estrategia_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posi��o', 'Clique neste �cone '.imagem('icones/2setabaixo.gif').' para mover para a �ltima posi��o').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$estrategia['pg_estrategia_ordem'].'; env.pg_estrategia_id.value='.(int)$estrategia['pg_estrategia_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td style="background-color: #'.$estrategia['pg_estrategia_cor'].'">'.$estrategia['pg_estrategia_nome'].'</td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta estrategia estrat�gica?\')) {env.excluirestrategia.value=1; env.pg_estrategia_id.value='.(int)$estrategia['pg_estrategia_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Iniciativa', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir a estrategia estrat�gica.').'</a></td>';

	echo '</tr>';
	}
if ($estrategias && count($estrategias)) echo '</table></td></tr>';




echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'estrategias_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>'.botao('pr�ximo', 'Pr�ximo', 'Ir para a pr�xima tela.','','carregar(\'metas_geral\');').'</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script language="javascript">
	
function popEstrategia() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["iniciativa"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, window.setEstrategia, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setEstrategia&tabela=estrategias&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["iniciativa"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}
	
function setEstrategia(chave, valor){
	document.env.pg_estrategia_id.value = chave;
	document.env.pg_estrategia_nome.value = valor;
	document.env.inserir.value =1;
	env.submit();
	}	
	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_estrategia_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_estrategia_cor.value;
	}
</script>