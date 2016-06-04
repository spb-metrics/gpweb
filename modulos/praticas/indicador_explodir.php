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

$ano=getParam($_REQUEST, 'ano', null);
$pratica_indicador_id=getParam($_REQUEST, 'pratica_indicador_id', 0);
$cor_pontuacao=array(0=>'ff3d3d', 10=>'ff813d', 20=>'ffa63d', 30=>'ffc63d', 40=>'ffdd3d', 50=>'fff83d', 60=>'eaff3d', 70=>'d4ff3d', 80=>'c1ff3d', 90=>'8bf22f', 100=>'51d529');


$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_acumulacao, pratica_indicador_agrupar, pratica_indicador_periodo_anterior');
$sql->adOnde('pratica_indicador_id='.(int)$pratica_indicador_id);
$linha=$sql->Linha();
$sql->limpar();

vetor_arvore($pratica_indicador_id, true, null, $linha['pratica_indicador_acumulacao'], $linha['pratica_indicador_agrupar'], $linha['pratica_indicador_periodo_anterior']);

//nova tabela
echo '<table id="geral" width="100%" cellspacing="0" cellpadding="0">';
	
echo '<tr><td colspan=20>'.$arvore->Render().'</td></tr></table>';
	
echo '<script>treeview.expandAll();</script>';	

function vetor_arvore($pratica_indicador_composicao_pai, $inicio=false, $pai='', $pratica_indicador_acumulacao=null, $pratica_indicador_agrupar=null, $pratica_indicador_periodo_anterior=null){
	global $tipo, $arvore, $Aplic, $ano;
	$sql = new BDConsulta;
	$saida='';
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('cias','cias','cia_id=pratica_indicador_cia');
	$sql->adCampo('pratica_indicador_nome, cia_nome, cia_id');
	$sql->adOnde('pratica_indicador_id='.$pratica_indicador_composicao_pai);
	$atual=$sql->Linha();
	$sql->limpar();
	$obj_indicador = new Indicador($pratica_indicador_composicao_pai, $ano, null, null, null, $pratica_indicador_acumulacao, $pratica_indicador_agrupar, $pratica_indicador_periodo_anterior);

	$sql->adTabela('pratica_indicador_composicao');
	$sql->esqUnir('pratica_indicador','pratica_indicador','pratica_indicador_composicao_filho=pratica_indicador_id');
	$sql->adCampo('pratica_indicador_composicao_filho');
	$sql->adOnde('pratica_indicador_composicao_pai='.(int)$pratica_indicador_composicao_pai);
	$sql->adOrdem('pratica_indicador_composicao_ordem');
	$linhas=$sql->Lista();
	$sql->limpar();	
	
	
	if ($inicio){
		$root = $arvore->getRootNode();
		$pontuacao=$obj_indicador->Pontuacao($ano);
		$root->text=$atual['pratica_indicador_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : '').'&nbsp;&nbsp;&nbsp;'.number_format((float)$pontuacao, 2, ',', '.');
		$root->expand=true;
		$root->image=retornar_cor(((int)($pontuacao/10))*10).".gif";
		$root->addData("id", $pratica_indicador_composicao_pai);
		foreach ((array)$linhas as $valor) {
			vetor_arvore($valor['pratica_indicador_composicao_filho'], false, 'root', $pratica_indicador_acumulacao, $pratica_indicador_agrupar, $pratica_indicador_periodo_anterior);
			}	
		}
	else{
		$pontuacao=$obj_indicador->Pontuacao($ano);
		$nodulo=$arvore->Add($pai, $pratica_indicador_composicao_pai, $atual['pratica_indicador_nome'].($atual['cia_id']!=$Aplic->usuario_cia ? ' - '.$atual['cia_nome'] : '').'&nbsp;&nbsp;&nbsp;'.number_format((float)$pontuacao, 2, ',', '.'),false, retornar_cor(((int)($pontuacao/10))*10).".gif");
		$nodulo->addData("id", $pratica_indicador_composicao_pai);
		foreach ((array)$linhas as $valor) {
			vetor_arvore($valor['pratica_indicador_composicao_filho'],false, $pratica_indicador_composicao_pai, $pratica_indicador_acumulacao, $pratica_indicador_agrupar, $pratica_indicador_periodo_anterior);	
			}
		}
	}


?>
<script type="text/javascript">

function nodeSelect_handle(sender,arg){	
		var treenode = treeview.getNode(arg.NodeId);
		var indicador=treenode.getData("id");	
		
		if (indicador >0) url_passar(Math.random(), "m=praticas&a=indicador_ver&pratica_indicador_id="+indicador);
    }
treeview.registerEvent("OnSelect",nodeSelect_handle);
</script>