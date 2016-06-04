<?php
include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();
$xajax->configure('defaultMode', 'synchronous');
//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);


function incluir_campo_ajax($sisvalor_titulo='', $sisvalor_id=null, $sisvalor_valor, $sisvalor_valor_id='', $sisvalor_chave_id_pai=''){
	$sql = new BDConsulta;
	$sisvalor_valor=previnirXSS(utf8_decode($sisvalor_valor));
	$sisvalor_valor_id=previnirXSS(utf8_decode($sisvalor_valor_id));
	$sisvalor_chave_id_pai=previnirXSS(utf8_decode($sisvalor_chave_id_pai));
	if ($sisvalor_id){
		$sql->adTabela('sisvalores');
		$sql->adAtualizar('sisvalor_valor', $sisvalor_valor);	
		$sql->adAtualizar('sisvalor_valor_id', $sisvalor_valor_id);
		$sql->adAtualizar('sisvalor_chave_id_pai', $sisvalor_chave_id_pai);
		$sql->adOnde('sisvalor_id = '.(int)$sisvalor_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('sisvalores');
		$sql->adCampo('count(projeto_campos_id) AS soma');
		$sql->adInserir('sisvalor_valor', $sisvalor_valor);	
		$sql->adInserir('sisvalor_valor_id', $sisvalor_valor_id);
		$sql->adInserir('sisvalor_chave_id_pai', $sisvalor_chave_id_pai);
		$sql->adInserir('sisvalor_titulo', $sisvalor_titulo);
		$sql->exec();
		}
	$saida=atualizar_campos($sisvalor_titulo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("campos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_campo_ajax");

function excluir_campo_ajax($sisvalor_id, $sisvalor_titulo=''){
	$sql = new BDConsulta;
	$sql->setExcluir('sisvalores');
	$sql->adOnde('sisvalor_id='.(int)$sisvalor_id);
	$sql->exec();
	$saida=atualizar_campos($sisvalor_titulo);
	$objResposta = new xajaxResponse();
	$objResposta->assign("campos","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_campo_ajax");	

function atualizar_campos($sisvalor_titulo=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('sisvalores');
	$sql->adOnde('sisvalor_titulo = \''.$sisvalor_titulo.'\'');
	$sql->adCampo('sisvalores.*');
	$sql->adOrdem('sisvalor_id');
	$campos=$sql->Lista();
	$sql->limpar();

	$saida='';
	
	
	if (count($campos)) {
		$saida.= '<table cellpadding=0 cellspacing=0 class="tbl1" align=left><tr><th>'.dica('Texto', 'Texto apresentado ao usuário.').'Texto'.dicaF().'</th><th>'.dica('Chave', 'Chave interna que é utilizada ao selecionar o texto da opção.').'Chave'.dicaF().'</th><th>'.dica('Chave do Pai', 'Chave interna do campo pai que é utilizada como filtro.').'Chave do Pai'.dicaF().'</th><th></th></tr>';
		foreach ($campos as $campo) {
			$saida.= '<tr align="center">';
			$saida.= '<td align="left">'.$campo['sisvalor_valor'].'</td>';
			$saida.= '<td align="left">'.$campo['sisvalor_valor_id'].'</td>';
			$saida.= '<td align="left">'.$campo['sisvalor_chave_id_pai'].'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_campo('.$campo['sisvalor_id'].');">'.imagem('icones/editar.gif', 'Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o campo.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este campo?\')) {excluir_campo('.$campo['sisvalor_id'].');}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o campo.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}

	return $saida;
	}





function editar_campo($sisvalor_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalores.*');
	$sql->adOnde('sisvalor_id = '.(int)$sisvalor_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$objResposta->assign("sisvalor_id","value", $sisvalor_id);	
	$objResposta->assign("sisvalor_valor","value", utf8_encode($linha['sisvalor_valor']));	
	$objResposta->assign("sisvalor_valor_id","value", utf8_encode($linha['sisvalor_valor_id']));	
	$objResposta->assign("sisvalor_chave_id_pai","value", utf8_encode($linha['sisvalor_chave_id_pai']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_campo");	




$xajax->processRequest();
?>