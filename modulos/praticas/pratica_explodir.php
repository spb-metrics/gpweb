<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';


require "lib/coolcss/CoolControls/CoolTreeView/cooltreeview.php";
$arvore = new CoolTreeView("treeview");
$arvore->scriptFolder = "lib/coolcss/CoolControls/CoolTreeView";
$arvore->imageFolder="lib/coolcss/CoolControls/CoolTreeView/icons";
$arvore->styleFolder="default";
$arvore->showLines = true;
$arvore->EditNodeEnable = false;
$arvore->DragAndDropEnable=true;
$arvore->multipleSelectEnable = true;


$pratica_id=getParam($_REQUEST, 'pratica_id', 0);


vetor_arvore($pratica_id, TRUE);

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0">';
echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
echo '<script>treeview.expandAll();</script>';	


function vetor_arvore($pc_pratica_pai, $inicio=false, $pai=''){
	global $tipo,  $cor_pontuacao, $arvore, $Aplic;
	$sql = new BDConsulta;
	$saida='';
	$sql->adTabela('praticas');
	$sql->esqUnir('cias','cias','cia_id=pratica_cia');
	$sql->adCampo('pratica_nome, cia_nome, cia_id');
	$sql->adOnde('pratica_id='.$pc_pratica_pai);
	$atual=$sql->Linha();
	$sql->limpar();

	$sql->adTabela('pratica_composicao');
	$sql->esqUnir('praticas','praticas','pc_pratica_filho=pratica_id');
	$sql->adCampo('pc_pratica_filho');
	$sql->adOnde('pc_pratica_pai='.$pc_pratica_pai);
	$sql->adOrdem('pratica_nome');
	$linhas=$sql->Lista();
	$sql->limpar();	
	
	
	if ($inicio){
		$root = $arvore->getRootNode();
		$root->text=$atual['pratica_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : '');
		$root->expand=true;
		$root->addData("id", $pc_pratica_pai);
		foreach ((array)$linhas as $valor) vetor_arvore($valor['pc_pratica_filho'],false, 'root');	
		}
	else{
		$nodulo=$arvore->Add($pai, $pc_pratica_pai, $atual['pratica_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : ''),false);
		$nodulo->addData("id", $pc_pratica_pai);
		foreach ((array)$linhas as $valor) vetor_arvore($valor['pc_pratica_filho'],false, $pc_pratica_pai);	
		}
	}


?>
<script type="text/javascript">
function nodeSelect_handle(sender,arg){	
		var treenode = treeview.getNode(arg.NodeId);
		var pratica_id=treenode.getData("id");	
		if (pratica_id >0) window.opener.url_passar(0, "m=praticas&a=pratica_ver&pratica_id="+pratica_id);
    }
    treeview.registerEvent("OnSelect",nodeSelect_handle);
</script>