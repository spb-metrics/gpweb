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


$pg_objetivo_estrategico_id=getParam($_REQUEST, 'pg_objetivo_estrategico_id', 0);


vetor_arvore($pg_objetivo_estrategico_id, TRUE);

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0">';
	
echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
	
echo '<script>treeview.expandAll();</script>';	


function vetor_arvore($objetivo_pai, $inicio=false, $pai=''){
	global $tipo,  $cor_pontuacao, $arvore, $Aplic;
	$sql = new BDConsulta;
	$saida='';
	$sql->adTabela('objetivos_estrategicos');
	$sql->esqUnir('cias','cias','cia_id=pg_objetivo_estrategico_cia');
	$sql->adCampo('pg_objetivo_estrategico_nome, cia_nome, cia_id');
	$sql->adOnde('pg_objetivo_estrategico_id='.$objetivo_pai);
	$atual=$sql->Linha();
	$sql->limpar();

	$sql->adTabela('objetivos_estrategicos_composicao');
	$sql->esqUnir('objetivos_estrategicos','objetivos_estrategicos','objetivo_filho=pg_objetivo_estrategico_id');
	$sql->adCampo('objetivo_filho');
	$sql->adOnde('objetivo_pai='.$objetivo_pai);
	$sql->adOrdem('pg_objetivo_estrategico_nome');
	$linhas=$sql->Lista();
	$sql->limpar();	
	
	
	if ($inicio){
		$root = $arvore->getRootNode();
		$root->text=$atual['pg_objetivo_estrategico_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : '');
		$root->expand=true;
		$root->addData("id", $objetivo_pai);
		foreach ((array)$linhas as $valor) vetor_arvore($valor['objetivo_filho'],false, 'root');	
		}
	else{
		$nodulo=$arvore->Add($pai, $objetivo_pai, $atual['pg_objetivo_estrategico_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : ''),false);
		$nodulo->addData("id", $objetivo_pai);
		foreach ((array)$linhas as $valor) vetor_arvore($valor['objetivo_filho'],false, $objetivo_pai);	
		}
	}


?>
<script type="text/javascript">
function nodeSelect_handle(sender,arg){	
		var treenode = treeview.getNode(arg.NodeId);
		var objetivo=treenode.getData("id");	
		if (objetivo >0) window.opener.url_passar(0, "m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id="+objetivo);
    }
    treeview.registerEvent("OnSelect",nodeSelect_handle);
</script>