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
	
function inserir_acao($ata_acao_id, $ata_id, $ata_acao_texto=null, $data=null, $ata_acao_responsavel=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$ata_acao_fim=substr($data,6,4).'-'.substr($data,3,2).'-'.substr($data,0,2).' '.substr($data,11,5).':00';
	
	if (!$ata_acao_id){
	 	$sql->adTabela('ata_acao');
		$sql->adCampo('count(ata_acao_id) AS soma');
		$sql->adOnde('ata_id ='.$ata_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('ata_acao');
		$sql->adInserir('ata_id', $ata_id);
		$sql->adInserir('ata_acao_texto', previnirXSS(utf8_decode($ata_acao_texto)));
		$sql->adInserir('ata_acao_responsavel', $ata_acao_responsavel);
		$sql->adInserir('ata_acao_fim', $ata_acao_fim);
		$sql->adInserir('ata_acao_ordem', $soma_total);
		$sql->exec();
		$sql->Limpar();
		}
	else{
		$sql->adTabela('ata_acao');
		$sql->adAtualizar('ata_acao_texto', previnirXSS(utf8_decode($ata_acao_texto)));
		$sql->adAtualizar('ata_acao_responsavel', $ata_acao_responsavel);
		$sql->adAtualizar('ata_acao_fim', $ata_acao_fim);
		$sql->adOnde('ata_acao_id = '.$ata_acao_id);
		$sql->exec();
		$sql->Limpar();
		}
	return true;
	}
$xajax->registerFunction("inserir_acao");	

function lista_artefatos($ata_id){
	$saida='';

	$sql = new BDConsulta;
	$sql->adTabela('ata_acao');
	$sql->adCampo('*');
	$sql->adOnde('ata_id='.(int)$ata_id);
	$sql->adOrdem('ata_acao_ordem ASC');
	$acaos=$sql->Lista();

	if ($acaos && count($acaos)) $saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%"><tr><th></th><th>&nbsp;'.utf8_encode('A��es').'&nbsp;</th><th>&nbsp;Data Limite&nbsp;</th><th>&nbsp;'.utf8_encode('Respons�vel').'&nbsp;</th><th></th></tr>';
	foreach ($acaos as $acao) {
		$saida.='<tr>';
		$saida.='<td nowrap="nowrap" width="40" align="center">';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$acao['ata_acao_ordem'].', '.$acao['ata_acao_id'].',\'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$acao['ata_acao_ordem'].', '.$acao['ata_acao_id'].',\'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$acao['ata_acao_ordem'].', '.$acao['ata_acao_id'].',\'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$acao['ata_acao_ordem'].', '.$acao['ata_acao_id'].',\'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.='</td>';
		$saida.='<td>&nbsp;'.utf8_encode($acao['ata_acao_texto']).'&nbsp;</td>';
		$saida.='<td>&nbsp;'.utf8_encode(retorna_data($acao['ata_acao_fim'])).'&nbsp;</td>';
		$saida.='<td>&nbsp;'.utf8_encode(nome_funcao('','','','',$acao['ata_acao_responsavel'])).'&nbsp;</td>';
		$saida.='<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_acao('.$acao['ata_acao_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta a��o da ata de reuni�o?').'\')) {excluir_acao('.$acao['ata_acao_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.='</tr>';
		}
	if ($acaos && count($acaos)) $saida.='</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_acoes',"innerHTML", $saida);
	return $objResposta;	
	}

$xajax->registerFunction("lista_artefatos");	
	
function mudar_ordem($ordem, $ata_acao_id, $direcao, $ata_id){
	
		$sql = new BDConsulta;
		$novo_ui_ordem = $ordem;
		$sql->adTabela('ata_acao');
		$sql->adOnde('ata_acao_id != '.(int)$ata_acao_id);
		$sql->adOnde('ata_id = '.(int)$ata_id);
		$sql->adOrdem('ata_acao_ordem');
		$acaos = $sql->Lista();
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
			$novo_ui_ordem = count($acaos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($acaos) + 1)) {
			$sql->adTabela('ata_acao');
			$sql->adAtualizar('ata_acao_ordem', $novo_ui_ordem);
			$sql->adOnde('ata_acao_id = '.(int)$ata_acao_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($acaos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('ata_acao');
					$sql->adAtualizar('ata_acao_ordem', $idx);
					$sql->adOnde('ata_acao_id = '.(int)$acao['ata_acao_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('ata_acao');
					$sql->adAtualizar('ata_acao_ordem', $idx + 1);
					$sql->adOnde('ata_acao_id = '.(int)$acao['ata_acao_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
	return true;
	}	
$xajax->registerFunction("mudar_ordem");		

	
function excluir_acao($ata_acao_id){
	$sql = new BDConsulta;
	$sql->setExcluir('ata_acao');
	$sql->adOnde('ata_acao_id='.(int)$ata_acao_id);
	$sql->exec();
	$sql->limpar();	
	return true;
	}	
$xajax->registerFunction("excluir_acao");	

function editar_acao($ata_acao_id){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('ata_acao');
	$sql->esqUnir('usuarios','usuarios','ata_acao_responsavel=usuario_id');
	$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
	$sql->adCampo('ata_acao_texto, ata_acao_fim, ata_acao_responsavel');
	$sql->adOnde('ata_acao_id='.$ata_acao_id);
	$acao=$sql->Linha();

	$saida='<table cellpadding=0 cellspacing="2"><tr><td><b>'.utf8_encode('A��o').'</b></td><td><b>Data limite</b></td><td><b>'.utf8_encode('Respons�vel').'</b></td><td></td></tr><tr>';
	$saida.='<td valign=top><textarea name="ata_acao_texto" id="ata_acao_texto" style="width:600px;" class="textarea">'.utf8_encode($acao['ata_acao_texto']).'</textarea></td>';
	$saida.='<td valign=top><input class="texto" type="text" id="ini" value="'.retorna_data($acao['ata_acao_fim']).'" style="width:95px;" /><img src="./estilo/rondon/imagens/icones/cal.gif" onclick="javascript:NewCssCal(\'ini\',\'ddMMyyyy\',\'arrow\',true,\'24\')" style="cursor:pointer; vertical-align:middle"/></td>';
	$saida.='<td valign=top><table cellspacing=0 cellpadding=0><tr><td valign=top><input type="hidden" id="ata_acao_responsavel" name="ata_acao_responsavel" value="'.$acao['ata_acao_responsavel'].'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.utf8_encode($acao['nome']).'" style="width:200px;" class="texto" READONLY /></td><td valign=top><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr></table></td>';
	$saida.='<td valign=top><a href="javascript:void(0);" onclick="javascript:inserir_acao('.$ata_acao_id.');">'.imagem('icones/ok.png').'</a><a href="javascript:void(0);" onclick="javascript:cancelar_edicao();">'.imagem('icones/cancelar.png').'</a></td>';
	$saida.='</tr></table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}	
	
$xajax->registerFunction("editar_acao");		


function cancelar_edicao(){
	$saida='';
	$saida.='<table cellpadding=0 cellspacing=2>';
	$saida.='<tr><td><b>'.utf8_encode('A��o').'</b></td><td><b>'.utf8_encode('Data limite').'</b></td><td><b>'.utf8_encode('Respons�vel').'</b></td><td>&nbsp;</td></tr>';
	$saida.='<tr><td valign=top><textarea name="ata_acao_texto" id="ata_acao_texto" style="width:600px;" class="textarea"></textarea></td>';
	$saida.='<td valign=top><input class="texto" type="text" id="ini" value="" style="width:95px;" /><img src="./estilo/rondon/imagens/icones/cal.gif" onclick="javascript:NewCssCal(\'ini\',\'ddMMyyyy\',\'arrow\',true,\'24\')" style="cursor:pointer; vertical-align:middle"/></td>';
	$saida.='<td valign=top><table cellspacing=0 cellpadding=0><tr><td valign=top><input type="hidden" id="ata_acao_responsavel" name="ata_acao_responsavel" value="" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="" style="width:200px;" class="texto" READONLY /></td><td valign=top><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr></table></td>';
	$saida.='<td valign=top><a href="javascript:void(0);" onclick="javascript:inserir_acao(0);">'.imagem('icones/ok.png').'</a><a href="javascript:void(0);" onclick="javascript:cancelar_edicao();">'.imagem('icones/cancelar.png').'</a></td>';
	$saida.='</tr></table>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}
$xajax->registerFunction("cancelar_edicao");	


$xajax->processRequest();

?>