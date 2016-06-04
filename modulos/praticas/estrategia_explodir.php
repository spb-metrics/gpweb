<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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


$pg_estrategia_id=getParam($_REQUEST, 'pg_estrategia_id', 0);


vetor_arvore($pg_estrategia_id, TRUE);

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0">';
	
echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
	
echo '<script>treeview.expandAll();</script>';	


function vetor_arvore($estrategia_pai, $inicio=false, $pai=''){
	global $tipo,  $cor_pontuacao, $arvore, $Aplic;
	$sql = new BDConsulta;
	$saida='';
	$sql->adTabela('estrategias');
	$sql->esqUnir('cias','cias','cia_id=pg_estrategia_cia');
	$sql->adCampo('pg_estrategia_nome, cia_nome, cia_id');
	$sql->adOnde('pg_estrategia_id='.$estrategia_pai);
	$atual=$sql->Linha();
	$sql->limpar();

	$sql->adTabela('estrategias_composicao');
	$sql->esqUnir('estrategias','estrategias','estrategia_filho=pg_estrategia_id');
	$sql->adCampo('estrategia_filho');
	$sql->adOnde('estrategia_pai='.$estrategia_pai);
	$sql->adOrdem('pg_estrategia_nome');
	$linhas=$sql->Lista();
	$sql->limpar();	
	
	
	if ($inicio){
		$root = $arvore->getRootNode();
		$root->text=$atual['pg_estrategia_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : '');
		$root->expand=true;
		$root->addData("id", $estrategia_pai);
		foreach ((array)$linhas as $valor) vetor_arvore($valor['estrategia_filho'],false, 'root');	
		}
	else{
		$nodulo=$arvore->Add($pai, $estrategia_pai, $atual['pg_estrategia_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : ''),false);
		$nodulo->addData("id", $estrategia_pai);
		foreach ((array)$linhas as $valor) vetor_arvore($valor['estrategia_filho'],false, $estrategia_pai);	
		}
	}


?>
<script type="text/javascript">
function nodeSelect_handle(sender,arg){	
		var treenode = treeview.getNode(arg.NodeId);
		var pg_estrategia_id=treenode.getData("id");	
		if (pg_estrategia_id >0) window.opener.url_passar(0, "m=praticas&a=estrategia_ver&pg_estrategia_id="+pg_estrategia_id);
    }
    treeview.registerEvent("OnSelect",nodeSelect_handle);
</script>