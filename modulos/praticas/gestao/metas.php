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
$pg_meta_id= getParam($_REQUEST, 'pg_meta_id', '0');
$excluirmeta=getParam($_REQUEST, 'excluirmeta', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');

echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="pg_arquivos_id" value="" />';
echo '<input type="hidden" name="pg_meta_id" value="" />';
echo '<input type="hidden" name="excluirmeta" value="" />';


//salvar dados na tabela
if ($inserir){
 	
 	//checar se já não existe
 	$sql->adTabela('plano_gestao_metas');
	$sql->adCampo('count(pg_meta_id) AS soma');
	$sql->adOnde('pg_id ='.(int)$pg_id);	
 	$sql->adOnde('pg_meta_id ='.(int)$pg_meta_id);	
 	$existe=$sql->resultado();
 	$sql->Limpar();
 	
 	if (!$existe){
	 	$sql->adTabela('plano_gestao_metas');
		$sql->adCampo('count(pg_meta_id) AS soma');
		$sql->adOnde('pg_id ='.(int)$pg_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('plano_gestao_metas');
		$sql->adInserir('pg_id', $pg_id);
		$sql->adInserir('pg_meta_id', $pg_meta_id);
		$sql->adInserir('pg_meta_ordem', $soma_total);
		$sql->exec();
		$pg_meta_id=$bd->Insert_ID('plano_gestao_metas','pg_meta_id');
		$sql->Limpar();
		}
	else ver2('Já existe esta meta!');
	}

if ($excluirmeta){
	$sql->setExcluir('plano_gestao_metas');
	$sql->adOnde('pg_meta_id='.(int)$pg_meta_id);
	$sql->adOnde('pg_id='.(int)$pg_id);
	if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela metas!'.$bd->stderr(true));
	$sql->limpar();	
	}


//ordenar metaes
if($direcao&&$pg_meta_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('plano_gestao_metas');
		$sql->adOnde('pg_meta_id !='.(int)$pg_meta_id);
		$sql->adOnde('pg_id ='.(int)$pg_id);
		$sql->adOrdem('pg_meta_ordem');
		$metas = $sql->Lista();
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
			$novo_ui_ordem = count($metas) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($metas) + 1)) {
			$sql->adTabela('plano_gestao_metas');
			$sql->adAtualizar('pg_meta_ordem', $novo_ui_ordem);
			$sql->adOnde('pg_meta_id ='.(int)$pg_meta_id);
			$sql->adOnde('pg_id ='.(int)$pg_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($metas as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('plano_gestao_metas');
					$sql->adAtualizar('pg_meta_ordem', $idx);
					$sql->adOnde('pg_id ='.(int)$pg_id);
					$sql->adOnde('pg_meta_id ='.(int)$acao['pg_meta_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('plano_gestao_metas');
					$sql->adAtualizar('pg_meta_ordem', $idx + 1);
					$sql->adOnde('pg_id ='.(int)$pg_id);
					$sql->adOnde('pg_meta_id ='.(int)$acao['pg_meta_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}

	


echo '<table width="100%" >';  
echo '<tr><td colspan=2 align="left"><h1>Lista de Metas</h1></td></tr>'; 
//metas

if ($editarPG){
	echo '<tr><td colspan=2 align="left"><table cellpadding=0 cellspacing="2"><tr><td><b>Meta</b></td><td></td></tr>';

	echo '<tr><td><input type="text" name="pg_meta_nome" id="pg_meta_nome" style="width:400px;" class="texto" value=""></td>';
	echo '<td><a href="javascript: void(0);" onclick="popMeta();">'.imagem('icones/meta_p.gif','Selecionar '.ucfirst($config['meta']),'Clique neste ícone '.imagem('icones/meta_p.gif').' para selecionar '.($config['genero_meta']=='o' ? 'um' : 'uma').' '.$config['meta'].'.').'</a></td>';
	
	echo '</tr></table></td></tr>';
	}


$sql->adTabela('plano_gestao_metas');
$sql->esqUnir('metas', 'metas', 'plano_gestao_metas.pg_meta_id=metas.pg_meta_id');
$sql->adCampo('pg_meta_nome, pg_meta_cor, plano_gestao_metas.pg_meta_ordem, metas.pg_meta_id');
$sql->adOnde('plano_gestao_metas.pg_id='.(int)$pg_id);
$sql->adOrdem('pg_meta_ordem ASC');
$metas=$sql->Lista();

if ($metas && count($metas)) echo '<tr><td colspan=2><table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="810"><tr>'.($editarPG ? '<th></th>' : '').'<th>&nbsp;'.(count($metas) >1 ? ucfirst($config['metas']) : ucfirst($config['meta'])).'&nbsp;</th>'.($editarPG ? '<th width="16"></th>' : '').'</tr>';
foreach ($metas as $meta) {
	echo '<tr>';
	if ($editarPG) {
			echo '<td nowrap="nowrap" width="40" align="center">';
			echo dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$meta['pg_meta_ordem'].'; env.pg_meta_id.value='.(int)$meta['pg_meta_id'].'; env.cmd.value=\'moverPrimeiro\' ;env.submit();"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$meta['pg_meta_ordem'].'; env.pg_meta_id.value='.(int)$meta['pg_meta_id'].'; env.cmd.value=\'moverParaCima\' ;env.submit();"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$meta['pg_meta_ordem'].'; env.pg_meta_id.value='.(int)$meta['pg_meta_id'].'; env.cmd.value=\'moverParaBaixo\' ;env.submit();"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			echo dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:env.ordem.value='.(int)$meta['pg_meta_ordem'].'; env.pg_meta_id.value='.(int)$meta['pg_meta_id'].'; env.cmd.value=\'moverUltimo\' ;env.submit();"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			echo '</td>';
			}
	echo '<td style="background-color: #'.$meta['pg_meta_cor'].'">'.$meta['pg_meta_nome'].'</td>';
	if ($editarPG) echo '<td><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir?\')) {env.excluirmeta.value=1; env.pg_meta_id.value='.(int)$meta['pg_meta_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir.').'</a></td>';

	echo '</tr>';
	}
if ($metas && count($metas)) echo '</table></td></tr>';




echo '<tr><td colspan=2 align="center"><table width="100%"><tr><td>'.botao('anterior', 'Anterior', 'Ir para a tela anterior.','','carregar(\'metas_geral\');').'</td><td width="40%">&nbsp;</td><td>&nbsp;</td><td width="40%">&nbsp;</td><td>&nbsp;</td></tr></table></td></tr>';

echo '</table>';
echo '</td></tr></table>';



?>

<script language="javascript">
	
function popMeta() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["meta"])?>', 500, 500, 'm=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, window.setMeta, window);
	else window.open('./index.php?m=publico&a=selecionar&dialogo=1&chamar_volta=setMeta&tabela=metas&cia_id='+document.getElementById('cia_id').value, '<?php echo ucfirst($config["meta"])?>','left=0,top=0,height=600,width=600,scrollbars=yes, resizable=yes');
	}	
function setMeta(chave, valor){
	document.env.pg_meta_id.value = chave;
	document.env.pg_meta_nome.value = valor;
	document.env.inserir.value =1;
	env.submit();
	}	
	
	
function setCor(cor) {
	var f = document.env;
	if (cor) f.pg_meta_cor.value = cor;
	document.getElementById('teste').style.background = '#' + f.pg_meta_cor.value;
	}
</script>