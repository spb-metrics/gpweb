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
	
function inserir_tipo($projeto_risco_tipo_id, $projeto_id, $projeto_risco_tipo_descricao=null, $projeto_risco_tipo_categoria=null, $projeto_risco_tipo_tipo=null, $projeto_risco_tipo_consequencia=null,$projeto_risco_tipo_probabilidade=null,$projeto_risco_tipo_impacto=null,$projeto_risco_tipo_acao=null, $projeto_risco_tipo_gatilho=null,$projeto_risco_tipo_resposta=null, $projeto_risco_tipo_usuario=null,$projeto_risco_tipo_status=null){
	global $Aplic;
	$sql = new BDConsulta;
	
	$severidade=(int)$projeto_risco_tipo_probabilidade+(int)$projeto_risco_tipo_impacto;
	
	if ($severidade<4) $severidade='Baixa';
	elseif ($severidade==4) $severidade='Média';
	else $severidade='Alta';
	
	if (!$projeto_risco_tipo_id){
	 	$sql->adTabela('projeto_risco_tipo');
		$sql->adCampo('count(projeto_risco_tipo_id) AS soma');
		$sql->adOnde('projeto_risco_tipo_projeto ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('projeto_risco_tipo');
		$sql->adInserir('projeto_risco_tipo_ordem', $soma_total);
		$sql->adInserir('projeto_risco_tipo_projeto', $projeto_id);
		$sql->adInserir('projeto_risco_tipo_data', date('Y-m-d H:i:s'));
		if ($projeto_risco_tipo_usuario) $sql->adInserir('projeto_risco_tipo_usuario', $projeto_risco_tipo_usuario);
		$sql->adInserir('projeto_risco_tipo_descricao', previnirXSS(utf8_decode($projeto_risco_tipo_descricao)));
		$sql->adInserir('projeto_risco_tipo_categoria', previnirXSS(utf8_decode($projeto_risco_tipo_categoria)));
		$sql->adInserir('projeto_risco_tipo_tipo', previnirXSS(utf8_decode($projeto_risco_tipo_tipo)));
		$sql->adInserir('projeto_risco_tipo_consequencia', previnirXSS(utf8_decode($projeto_risco_tipo_consequencia)));
		$sql->adInserir('projeto_risco_tipo_probabilidade', previnirXSS(utf8_decode($projeto_risco_tipo_probabilidade)));
		$sql->adInserir('projeto_risco_tipo_impacto', previnirXSS(utf8_decode($projeto_risco_tipo_impacto)));
		$sql->adInserir('projeto_risco_tipo_severidade', $severidade);
		$sql->adInserir('projeto_risco_tipo_acao', previnirXSS(utf8_decode($projeto_risco_tipo_acao)));
		$sql->adInserir('projeto_risco_tipo_gatilho', previnirXSS(utf8_decode($projeto_risco_tipo_gatilho)));
		$sql->adInserir('projeto_risco_tipo_resposta', previnirXSS(utf8_decode($projeto_risco_tipo_resposta)));
		$sql->adInserir('projeto_risco_tipo_status', previnirXSS(utf8_decode($projeto_risco_tipo_status)));
		$sql->exec();
		$sql->Limpar();
		}
	else{
		$sql->adTabela('projeto_risco_tipo');
		$sql->adAtualizar('projeto_risco_tipo_data', date('Y-m-d H:i:s'));
		$sql->adAtualizar('projeto_risco_tipo_usuario', ($projeto_risco_tipo_usuario ? $projeto_risco_tipo_usuario : null));
		$sql->adAtualizar('projeto_risco_tipo_descricao', previnirXSS(utf8_decode($projeto_risco_tipo_descricao)));
		$sql->adAtualizar('projeto_risco_tipo_categoria', previnirXSS(utf8_decode($projeto_risco_tipo_categoria)));
		$sql->adAtualizar('projeto_risco_tipo_tipo', previnirXSS(utf8_decode($projeto_risco_tipo_tipo)));
		$sql->adAtualizar('projeto_risco_tipo_consequencia', previnirXSS(utf8_decode($projeto_risco_tipo_consequencia)));
		$sql->adAtualizar('projeto_risco_tipo_probabilidade', previnirXSS(utf8_decode($projeto_risco_tipo_probabilidade)));
		$sql->adAtualizar('projeto_risco_tipo_impacto', previnirXSS(utf8_decode($projeto_risco_tipo_impacto)));
		$sql->adAtualizar('projeto_risco_tipo_severidade', $severidade);
		$sql->adAtualizar('projeto_risco_tipo_acao', previnirXSS(utf8_decode($projeto_risco_tipo_acao)));
		$sql->adAtualizar('projeto_risco_tipo_gatilho', previnirXSS(utf8_decode($projeto_risco_tipo_gatilho)));
		$sql->adAtualizar('projeto_risco_tipo_resposta', previnirXSS(utf8_decode($projeto_risco_tipo_resposta)));
		$sql->adAtualizar('projeto_risco_tipo_status', previnirXSS(utf8_decode($projeto_risco_tipo_status)));
		$sql->adOnde('projeto_risco_tipo_id = '.$projeto_risco_tipo_id);
		$sql->exec();
		$sql->Limpar();
		}
	return true;
	}
$xajax->registerFunction("inserir_tipo");	

function lista_artefatos($projeto_id){
	global $config;
	$probabilidade=array(1=>'Baixa', 2=>'Média', 3=>'Alta');
	$impacto=array(1=>'Baixo', 2=>'Médio', 3=>'Alto');
	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('projeto_risco_tipo');
	$sql->esqUnir('usuarios','usuarios','projeto_risco_tipo_usuario=usuario_id');
	$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
	$sql->adCampo('projeto_risco_tipo.*, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
	$sql->adOnde('projeto_risco_tipo_projeto='.(int)$projeto_id);
	$sql->adOrdem('projeto_risco_tipo_ordem ASC');
	$tipos=$sql->Lista();
	if ($tipos && count($tipos)) {
		$saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%"><tr><th></th>';
		$saida.='<td style="background-color:#a6a6a6"><b>'.utf8_encode('Descrição').'</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Categoria</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Tipo</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>'.utf8_encode('Consequência').'</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Probabilidade</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Impacto</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Severidade</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>'.utf8_encode('Ação').'</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Gatilho</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Resposta ao Risco</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>'.utf8_encode('Responsável').'</b></td>';
		$saida.='<td style="background-color:#a6a6a6"><b>Status</b></td>';
		$saida.='<th></th></tr>';
		}
	foreach ($tipos as $tipo) {
		$saida.='<tr>';
		$saida.='<td nowrap="nowrap" width="40" align="center">';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_risco_tipo_ordem'].', '.$tipo['projeto_risco_tipo_id'].',\'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_risco_tipo_ordem'].', '.$tipo['projeto_risco_tipo_id'].',\'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_risco_tipo_ordem'].', '.$tipo['projeto_risco_tipo_id'].',\'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$tipo['projeto_risco_tipo_ordem'].', '.$tipo['projeto_risco_tipo_id'].',\'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.='</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_descricao'] ? utf8_encode($tipo['projeto_risco_tipo_descricao']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_categoria'] ? utf8_encode($tipo['projeto_risco_tipo_categoria']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_tipo'] ? utf8_encode($tipo['projeto_risco_tipo_tipo']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_consequencia'] ? utf8_encode($tipo['projeto_risco_tipo_consequencia']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_probabilidade'] ? utf8_encode($probabilidade[$tipo['projeto_risco_tipo_probabilidade']]) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_impacto'] ? utf8_encode($impacto[$tipo['projeto_risco_tipo_impacto']]) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_severidade'] ? utf8_encode($tipo['projeto_risco_tipo_severidade']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_acao'] ? utf8_encode($tipo['projeto_risco_tipo_acao']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_gatilho'] ? utf8_encode($tipo['projeto_risco_tipo_gatilho']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_resposta'] ? utf8_encode($tipo['projeto_risco_tipo_resposta']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_usuario'] ? utf8_encode($tipo['nome']) : '&nbsp;').'</td>';
		$saida.='<td>'.($tipo['projeto_risco_tipo_status'] ? utf8_encode($tipo['projeto_risco_tipo_status']) : '&nbsp;').'</td>';
		$saida.='<td width="32" align="center"><a href="javascript: void(0);" onclick="editar_tipo('.$tipo['projeto_risco_tipo_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta tipo do gerenciamento de risco?').'\')) {excluir_tipo('.$tipo['projeto_risco_tipo_id'].');}">'.imagem('icones/remover.png').'</a></td>';
		$saida.='</tr>';
		}
	if ($tipos && count($tipos)) $saida.='</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_tipos',"innerHTML", $saida);
	return $objResposta;	
	}
$xajax->registerFunction("lista_artefatos");
	
function mudar_ordem($ordem, $projeto_risco_tipo_id, $direcao, $projeto_id){
	
		$sql = new BDConsulta;
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_risco_tipo');
		$sql->adOnde('projeto_risco_tipo_id != '.(int)$projeto_risco_tipo_id);
		$sql->adOnde('projeto_risco_tipo_projeto = '.(int)$projeto_id);
		$sql->adOrdem('projeto_risco_tipo_ordem');
		$tipos = $sql->Lista();
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
			$novo_ui_ordem = count($tipos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($tipos) + 1)) {
			$sql->adTabela('projeto_risco_tipo');
			$sql->adAtualizar('projeto_risco_tipo_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_risco_tipo_id = '.(int)$projeto_risco_tipo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($tipos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_risco_tipo');
					$sql->adAtualizar('projeto_risco_tipo_ordem', $idx);
					$sql->adOnde('projeto_risco_tipo_id = '.(int)$acao['projeto_risco_tipo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_risco_tipo');
					$sql->adAtualizar('projeto_risco_tipo_ordem', $idx + 1);
					$sql->adOnde('projeto_risco_tipo_id = '.(int)$acao['projeto_risco_tipo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
	return true;
	}	
$xajax->registerFunction("mudar_ordem");	
	
function excluir_tipo($projeto_risco_tipo_id){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_risco_tipo');
	$sql->adOnde('projeto_risco_tipo_id='.(int)$projeto_risco_tipo_id);
	$sql->exec();
	$sql->limpar();	
	return true;
	}	
$xajax->registerFunction("excluir_tipo");	
	
function editar_tipo($projeto_risco_tipo_id){
	global $config;
	$saida='';
	$RiscoCategoria = getSisValor('RiscoCategoria');
	$categoria=array();
	foreach($RiscoCategoria as $chave => $valor) $categoria[utf8_encode($chave)]=utf8_encode($valor);
	$tipo_risco=array('Negativo'=>'Negativo', 'Positivo'=>'Positivo');
	$probabilidade=array(1=>'Baixa', 2 => utf8_encode('Média'), 3=>'Alta');
	$impacto=array(1=>'Baixo', 2 => utf8_encode('Médio'), 3=>'Alto');

	$sql = new BDConsulta;
	$sql->adTabela('projeto_risco_tipo');
	$sql->esqUnir('usuarios','usuarios','projeto_risco_tipo_usuario=usuario_id');
	$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
	$sql->adCampo('projeto_risco_tipo.*, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
	$sql->adOnde('projeto_risco_tipo_id='.(int)$projeto_risco_tipo_id);
	$tipo=$sql->linha();
	$sql->limpar();	

	$saida.= '<table class="std" cellspacing=0 cellpadding=0  width="100%"><tr>';
	$saida.='<td ><b>'.utf8_encode('Descrição').'</b></td>';
	$saida.='<td ><b>Categoria</b></td>';
	$saida.='<td ><b>Tipo</b></td>';
	$saida.='<td ><b>'.utf8_encode('Consequência').'</b></td>';
	$saida.='<td ><b>Probabilidade</b></td>';
	$saida.='<td ><b>Impacto</b></td>';
	$saida.='<td ><b>'.utf8_encode('Ação').'</b></td>';
	$saida.='<td ><b>Gatilho</b></td>';
	$saida.='<td ><b>Resposta ao Risco</b></td>';
	$saida.='<td ><b>'.utf8_encode('Responsável').'</b></td>';
	$saida.='<td ><b>Status</b></td>';
	$saida.='<td></td></tr><tr>';
		
	$saida.='<td valign=top><textarea name="projeto_risco_tipo_descricao" id="projeto_risco_tipo_descricao" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_risco_tipo_descricao']).'</textarea></td>';
	$saida.='<td valign=top>'.selecionaVetor($categoria, 'projeto_risco_tipo_categoria', 'size="1" class="texto"',$tipo['projeto_risco_tipo_categoria']).'</td>';
	$saida.='<td valign=top>'.selecionaVetor($tipo_risco, 'projeto_risco_tipo_tipo', 'size="1" class="texto"',$tipo['projeto_risco_tipo_tipo']).'</td>';
	$saida.='<td valign=top><textarea name="projeto_risco_tipo_consequencia" id="projeto_risco_tipo_consequencia" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_risco_tipo_consequencia']).'</textarea></td>';
	$saida.='<td valign=top>'.selecionaVetor($probabilidade, 'projeto_risco_tipo_probabilidade', 'size="1" class="texto"',$tipo['projeto_risco_tipo_probabilidade']).'</td>';
	$saida.='<td valign=top>'.selecionaVetor($impacto, 'projeto_risco_tipo_impacto', 'size="1" class="texto"',$tipo['projeto_risco_tipo_impacto']).'</td>';
	$saida.='<td valign=top><textarea name="projeto_risco_tipo_acao" id="projeto_risco_tipo_acao" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_risco_tipo_acao']).'</textarea></td>';
	$saida.='<td valign=top><textarea name="projeto_risco_tipo_gatilho" id="projeto_risco_tipo_gatilho" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_risco_tipo_gatilho']).'</textarea></td>';
	$saida.='<td valign=top><textarea name="projeto_risco_tipo_resposta" id="projeto_risco_tipo_resposta" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_risco_tipo_resposta']).'</textarea></td>';
	$saida.='<td valign=top><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="projeto_risco_tipo_usuario" name="projeto_risco_tipo_usuario" value="'.$tipo['projeto_risco_tipo_usuario'].'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.utf8_encode($tipo['nome']).'" style="width:120px;" class="texto" READONLY /></td><td valign=top><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr></table></td>';
	$saida.='<td valign=top><textarea name="projeto_risco_tipo_status" id="projeto_risco_tipo_status" class="textarea" style="width:100%">'.utf8_encode($tipo['projeto_risco_tipo_status']).'</textarea></td>';

	$saida.='<td><a href="javascript:void(0);" onclick="javascript:inserir_tipo('.$projeto_risco_tipo_id.');">'.imagem('icones/ok.png').'</a><a href="javascript:void(0);" onclick="javascript:cancelar_edicao();">'.imagem('icones/cancelar.png').'</a></td></tr>';
	$saida.='</table>';
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}	
$xajax->registerFunction("editar_tipo");	





function cancelar_edicao(){	
	$RiscoCategoria = array('' => '') + getSisValor('RiscoCategoria');
	$tipo=array('Negativo'=>'Negativo', 'Positivo'=>'Positivo');
	$probabilidade=array(1=>'Baixa', 2=>'Média', 3=>'Alta');
	$impacto=array(1=>'Baixo', 2=>'Médio', 3=>'Alto');
	$objResposta = new xajaxResponse();
	$saida=utf8_encode('<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Descrição</b></td><td><b>Categoria</b></td><td><b>Tipo</b></td><td><b>Consequência</b></td><td><b>Probabilidade</b></td><td><b>Impacto</b></td><td><b>Ação</b></td><td><b>Gatilho</b></td><td><b>Resposta ao Risco</b></td><td><b>Responsável</b></td><td><b>Status</b></td><td></td></tr><tr><td valign=top><textarea name="projeto_risco_tipo_descricao" id="projeto_risco_tipo_descricao" class="textarea" style="width:100%"></textarea></td><td valign=top>'.selecionaVetor($RiscoCategoria, 'projeto_risco_tipo_categoria', 'size="1" class="texto"').'</td><td valign=top>'.selecionaVetor($tipo, 'projeto_risco_tipo_tipo', 'size="1" class="texto"').'</td><td valign=top><textarea name="projeto_risco_tipo_consequencia" id="projeto_risco_tipo_consequencia" class="textarea" style="width:100%"></textarea></td><td valign=top>'.selecionaVetor($probabilidade, 'projeto_risco_tipo_probabilidade', 'size="1" class="texto"').'</td><td valign=top>'.selecionaVetor($impacto, 'projeto_risco_tipo_impacto', 'size="1" class="texto"').'</td><td valign=top><textarea name="projeto_risco_tipo_acao" id="projeto_risco_tipo_acao" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_risco_tipo_gatilho" id="projeto_risco_tipo_gatilho" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_risco_tipo_resposta" id="projeto_risco_tipo_resposta" class="textarea" style="width:100%"></textarea></td><td valign=top><table cellspacing=0 cellpadding=0><tr><td><input type="hidden" id="projeto_risco_tipo_usuario" name="projeto_risco_tipo_usuario" value="" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="" style="width:120px;" class="texto" READONLY /></td><td valign=top><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif').'</a></td></tr></table></td><td valign=top><textarea name="projeto_risco_tipo_status" id="projeto_risco_tipo_status" class="textarea" style="width:100%"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_tipo(0);">'.imagem('icones/adicionar.png').'</a></td></tr></table>');
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;	
	}
$xajax->registerFunction("cancelar_edicao");	


$xajax->processRequest();

?>