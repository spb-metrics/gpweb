<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf, $cia_id, $ano, $usuario_id, $pratica_modelo_id, $dialogo;


include_once BASE_DIR.'/modulos/praticas/pauta.class.php';
$pauta=new Cpauta($cia_id, $pratica_modelo_id, $ano);
$sql = new BDConsulta;

if (!$dialogo) echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Pontuação em Forma de Árvore Hierárquica</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
else echo '<table width="750"><tr><td align="center"><font size="4"><center>Pontuação em Forma de Árvore Hierárquica</center></font></td></tr></table>';		


echo '<table width="100%" border=0 cellpadding="2" cellspacing=0><tr><td><table><tr>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_0'].'">&nbsp; &nbsp;</td><td>'.dica('0%','Pontuação em 0%').'0'.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_0_10'].'">&nbsp; &nbsp;</td><td>'.dica('0% - 10%','Pontuação entre 0% aberto e 10% aberto').']0,10['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_10_20'].'">&nbsp; &nbsp;</td><td>'.dica('10% - 20%','Pontuação entre 10% fechado e 20% aberto').'[10,20['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_20_30'].'">&nbsp; &nbsp;</td><td>'.dica('20% - 30%','Pontuação entre 20% fechado e 30% aberto').'[20,30['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_30_40'].'">&nbsp; &nbsp;</td><td>'.dica('30% - 40%','Pontuação entre 30% fechado e 40% aberto').'[30,40['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_40_50'].'">&nbsp; &nbsp;</td><td>'.dica('40% - 50%','Pontuação entre 40% fechado e 50% aberto').'[40,50['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_50_60'].'">&nbsp; &nbsp;</td><td>'.dica('50% - 60%','Pontuação entre 50% fechado e 60% aberto').'[50,60['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_60_70'].'">&nbsp; &nbsp;</td><td>'.dica('60% - 70%','Pontuação entre 60% fechado e 70% aberto').'[60,70['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_70_80'].'">&nbsp; &nbsp;</td><td>'.dica('70% - 80%','Pontuação entre 70% fechado e 80% aberto').'[70,80['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_80_90'].'">&nbsp; &nbsp;</td><td>'.dica('80% - 90%','Pontuação entre 80% fechado e 90% aberto').'[80,90['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_90_100'].'">&nbsp; &nbsp;</td><td>'.dica('90% - 100%','Pontuação entre 90% fechado e 100% aberto').'[90,100['.dicaF().'</td><td>&nbsp;</td>';
echo '<td style="border-style:solid;border-width:1px" bgcolor="#'.$config['porcentagem_100'].'">&nbsp; &nbsp;</td><td>'.dica('100%','Pontuação em 100%').'100'.dicaF().'</td><td>&nbsp;</td>';
echo '</tr></table></td></tr></table>';
	






require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = false;
$arvore->DragAndDropEnable=true;
$arvore->multipleSelectEnable = true;

$root = $arvore->getRootNode();
$root->text='Gestão';
$root->addData("observacao", ((int)(($pauta->pontuacao_final/$pauta->pontuacao_maxima)*10))*10);
$root->expand=true;
$root->image=retornar_cor(((int)(($pauta->pontuacao_final/$pauta->pontuacao_maxima)*10))*10).".gif";

				
$i=0;

//campos utilizados na regua específica	
$sql->adTabela('pratica_regra');
$sql->esqUnir('pratica_regra_campo', 'pratica_regra_campo', 'pratica_regra_campo_nome=pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_resultado');
$sql->adOnde('pratica_modelo_id='.(int)$pratica_modelo_id);
$sql->adOrdem('subitem ASC, pratica_regra_ordem');
$sql->adGrupo('pratica_regra_campo_nome');
$vetor_campos=$sql->ListaChave('pratica_regra_campo_nome');
$sql->limpar();


foreach($pauta->criterios as $criterio_id => $criterio) {
	$nodulo=$arvore->Add('root', 'c'.$criterio_id, $criterio['pratica_criterio_numero'].'.'.$criterio['pratica_criterio_nome'], false, retornar_cor($pauta->porcentagem_criterio[$criterio_id]).'.gif');
	$nodulo->addData('endereco', 'm=praticas&a='.($criterio['pratica_criterio_resultado'] ? 'indicador_lista' : 'pratica_lista').'&tab=1&criterio='.$chave);
	$nodulo->addData('popup', '0');
	}

foreach($pauta->itens as $item_id => $item) {
	$nodulo=$arvore->Add('c'.$item['pratica_item_criterio'], 'i'.$item_id, $pauta->criterios[$item['pratica_item_criterio']]['pratica_criterio_numero'].'.'.$item['pratica_item_numero'].'.'.$item['pratica_item_nome'],false, retornar_cor($pauta->porcentagem_item[$item_id]).'.gif');
	$nodulo->addData('endereco', 'm=praticas&a='.($pauta->criterios[$item['pratica_item_criterio']]['pratica_criterio_resultado'] ? 'indicador_lista' : 'pratica_lista').'&tab=1&criterio='.$item['pratica_item_criterio'].'&item='.$chave);
	$nodulo->addData('popup', '0');
	
	//colocar os marcadores
	
	$vetor=($pauta->criterios[$item['pratica_item_criterio']]['pratica_criterio_resultado'] > 0 ? $pauta->resultados[$item_id] : $pauta->praticas[$item_id]);
	
	foreach($vetor as $campo => $valor) {
		if (isset($vetor_campos[$campo])){
			$nodulo=$arvore->Add('i'.$item_id, ++$i, $vetor_campos[$campo]['pratica_regra_campo_texto'],false, retornar_cor($valor).'.gif');
			if ($campo!='pratica_adequada') {
				$nodulo->addData('endereco', './index.php?m=praticas&u=relatorios&a=marcador_lista&dialogo=1&ano='.$ano.'&item='.$item_id.'&resultado='.$pauta->criterios[$item['pratica_item_criterio']]['pratica_criterio_resultado'].'&campo='.$campo);
				$nodulo->addData('popup', '1');
				}
			else $nodulo->addData('popup', '-1');	
			}
		}
	}

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0" style="display:'.(1 ? '' : 'none').'">';
	
		//botoes para criar
	echo '<tr id="botaos_criar" style="display:'.(!$dialogo ? '' : 'none').'"><td><table>';
		echo '<tr>';
		echo '<td>'.botao('contrair tudo','Contrair','Contrair todos os nódulo','','contrair_nodulos();').'</td>';
		echo '<td>'.botao('expandir tudo','Expandir Tudo','Expandir todos os nódulos','','expandir_nodulos();').'</td>';
		echo '</tr></table>';
	echo '</td></tr>';
	
	
	
	echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
	
if ($dialogo) echo '<script>treeview.expandAll(); self.print();</script>';	


function texto_vertical1($legenda, $titulo='', $texto=''){
	$saida='';
	for ($i=0; $i< strlen($legenda); $i++) $saida.=$legenda[$i].'<br>';
	return dica($titulo, $texto).$saida.dicaF();
	}
?>
<script language="javascript">

function expandir_nodulos(){
	treeview.expandAll();
	}

function contrair_nodulos(){
	treeview.collapseAll();
	}
	
function nodeSelect_handle(sender,arg){	
		var treenode = treeview.getNode(arg.NodeId);
		
		var endereco = treenode.getData("endereco");
		var popup = treenode.getData("popup");
		if (popup==1) window.open(endereco, 'Detalhes','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
		else if(popup==0) url_passar(0, endereco);
    }

treeview.registerEvent("OnSelect",nodeSelect_handle);


function exibir(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
</script>

