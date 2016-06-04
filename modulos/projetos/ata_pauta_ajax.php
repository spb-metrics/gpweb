<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);
	
function inserir_pauta($ata_pauta_id, $ata_pauta_ata, $ata_pauta_texto, $tipo){
	global $Aplic;
	$sql = new BDConsulta;
	if (!$ata_pauta_id){
	 	$sql->adTabela('ata_pauta');
		$sql->adCampo('count(ata_pauta_id) AS soma');
		$sql->adOnde('ata_pauta_ata ='.$ata_pauta_ata);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('ata_pauta');
		$sql->adInserir('ata_pauta_ata', $ata_pauta_ata);
		$sql->adInserir('ata_pauta_texto', previnirXSS(utf8_decode($ata_pauta_texto)));
		$sql->adInserir('ata_pauta_ordem', $soma_total);
		$sql->adInserir('ata_pauta_tipo', previnirXSS(utf8_decode($tipo)));
		$sql->exec();
		$sql->Limpar();
		}
	else{
		$sql->adTabela('ata_pauta');
		$sql->adAtualizar('ata_pauta_texto', previnirXSS(utf8_decode($ata_pauta_texto)));
		$sql->adOnde('ata_pauta_id = '.$ata_pauta_id);
		$sql->exec();
		$sql->Limpar();
		}
	return true;
	}
$xajax->registerFunction("inserir_pauta");	

function lista_artefatos($ata_pauta_ata,$tipo){
	$saida='';

	$sql = new BDConsulta;
	$sql->adTabela('ata_pauta');
	$sql->adCampo('*');
	$sql->adOnde('ata_pauta_ata='.(int)$ata_pauta_ata);
	$sql->adOnde('ata_pauta_tipo="'.$tipo.'"');
	$sql->adOrdem('ata_pauta_ordem ASC');
	$pautas=$sql->Lista();

	if ($pautas && count($pautas)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%"><tr><th></th><th>&nbsp;Pauta'.(count($pautas)>1 ? 's':'').'&nbsp;</th><th></th></tr>';
	foreach ($pautas as $pauta) {
		$saida.='<tr>';
		$saida.='<td nowrap="nowrap" width="40" align="center">';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$pauta['ata_pauta_ordem'].', '.$pauta['ata_pauta_id'].',\'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$pauta['ata_pauta_ordem'].', '.$pauta['ata_pauta_id'].',\'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$pauta['ata_pauta_ordem'].', '.$pauta['ata_pauta_id'].',\'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$pauta['ata_pauta_ordem'].', '.$pauta['ata_pauta_id'].',\'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.='</td>';
		$saida.='<td>&nbsp;'.utf8_encode($pauta['ata_pauta_texto']).'&nbsp;</td>';
		$saida.='<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_pauta('.$pauta['ata_pauta_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta pauta de ata de reunião?').'\')) {excluir_pauta('.$pauta['ata_pauta_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.='</tr>';
		}
	if ($pautas && count($pautas)) $saida.='</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_pautas',"innerHTML", $saida);
	return $objResposta;	
	}
$xajax->registerFunction("lista_artefatos");
	
function mudar_ordem($ordem, $ata_pauta_id, $direcao, $ata_pauta_ata, $tipo){
	
		$sql = new BDConsulta;
		$novo_ui_ordem = $ordem;
		$sql->adTabela('ata_pauta');
		$sql->adOnde('ata_pauta_id != '.(int)$ata_pauta_id);
		$sql->adOnde('ata_pauta_ata = '.(int)$ata_pauta_ata);
		$sql->adOnde('ata_pauta_tipo="'.$tipo.'"');
		$sql->adOrdem('ata_pauta_ordem');
		$pautas = $sql->Lista();
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
			$novo_ui_ordem = count($pautas) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($pautas) + 1)) {
			$sql->adTabela('ata_pauta');
			$sql->adAtualizar('ata_pauta_ordem', $novo_ui_ordem);
			$sql->adOnde('ata_pauta_id = '.(int)$ata_pauta_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($pautas as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('ata_pauta');
					$sql->adAtualizar('ata_pauta_ordem', $idx);
					$sql->adOnde('ata_pauta_id = '.(int)$acao['ata_pauta_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('ata_pauta');
					$sql->adAtualizar('ata_pauta_ordem', $idx + 1);
					$sql->adOnde('ata_pauta_id = '.(int)$acao['ata_pauta_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
	return true;
	}	
$xajax->registerFunction("mudar_ordem");		
	
function excluir_pauta($ata_pauta_id){
	$sql = new BDConsulta;
	$sql->setExcluir('ata_pauta');
	$sql->adOnde('ata_pauta_id='.(int)$ata_pauta_id);
	$sql->exec();
	$sql->limpar();	
	return true;
	}	
$xajax->registerFunction("excluir_pauta");	
	
function editar_pauta($ata_pauta_id){
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('ata_pauta');
	$sql->adCampo('ata_pauta_texto');
	$sql->adOnde('ata_pauta_id='.$ata_pauta_id);
	$pauta=$sql->Linha();
	$saida.='<table cellpadding=0 cellspacing="2"><tr><td><b>Pauta</b></td><td></td></tr>';
	$saida.='<tr><td valign=top><textarea name="ata_pauta_texto" id="ata_pauta_texto" style="width:750px;" class="textarea">'.utf8_encode($pauta['ata_pauta_texto']).'</textarea></td>';
	$saida.='<td><a href="javascript:void(0);" onclick="javascript:inserir_pauta('.$ata_pauta_id.');">'.imagem('icones/ok.png').'</a><a href="javascript:void(0);" onclick="javascript:cancelar_edicao();">'.imagem('icones/cancelar.png').'</a></td></tr>';
	$saida.='</table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}	
$xajax->registerFunction("editar_pauta");		
	
function cancelar_edicao(){
	$saida='<table cellpadding=0 cellspacing="2"><tr><td><b>Pauta</b></td><td></td></tr><tr><td valign=top><textarea name="ata_pauta_texto" id="ata_pauta_texto" style="width:750px;" class="textarea"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_pauta(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("cancelar_edicao");
	
	




$xajax->processRequest();

?>