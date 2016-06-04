<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);

function mudar_campo_artefato($artefato_tipo='', $campo='', $posicao='', $script=''){
	$vetor=vetor_campo_sistema('ArtefatoCampo',$artefato_tipo, true);
	$saida=selecionaVetor($vetor, $campo, $script, $artefato_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_campo_artefato");	






function alterar_html($artefato_tipo_id, $artefato_tipo_html=null){
	global $bd, $Aplic;
		
	$sql = new BDConsulta;
	$artefato_tipo_html=previnirXSS(utf8_decode($artefato_tipo_html));
	if ($artefato_tipo_id){
		$sql->adTabela('artefatos_tipo');
		$sql->adAtualizar('artefato_tipo_html', $artefato_tipo_html);	
		$sql->adOnde('artefato_tipo_id ='.(int)$artefato_tipo_id);
		$sql->exec();
	  $sql->Limpar();
		
		
		$sql->adTabela('artefatos_tipo');
		$sql->adCampo('artefato_tipo_nome, artefato_tipo_descricao, artefato_tipo_imagem, artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_arquivo, artefato_tipo_html');
		$sql->adOnde('artefato_tipo_id='.$artefato_tipo_id);
		$rs = $sql->Linha();
		$sql->Limpar();
	
		$arquivo_modelo = str_replace('src="imagens/', 'src="./'.$rs['artefato_tipo_endereco'].'/imagens/', $rs['artefato_tipo_html']);
		$arquivo_modelo = str_replace('src="./'.$rs['artefato_tipo_endereco'].'/imagens/brasao_republica.gif','src="'.$Aplic->gpweb_brasao,  $arquivo_modelo);
	
		$objResposta = new xajaxResponse();
		$objResposta->assign("combo_modelo","innerHTML", utf8_encode($arquivo_modelo));
		return $objResposta;
		
		
		
		}

	
	}
$xajax->registerFunction("alterar_html");	











$xajax->processRequest();
?>