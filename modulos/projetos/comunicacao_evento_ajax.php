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
	
function excluir_calendario($projeto_comunicacao_evento_id=null, $evento_id=null){
	if ($evento_id){
		$sql = new BDConsulta;
		$sql->adTabela('evento_arquivos');
		$sql->adCampo('evento_arquivo_endereco');
		$sql->adOnde('evento_arquivo_evento_id='.(int)$evento_id);
		$caminhos=$sql->Lista();
		$sql->limpar();
		foreach ($caminhos as $caminho){
			@unlink($base_dir.'/arquivos/eventos/'.$caminho['evento_arquivo_endereco']);
			}
		@rmdir($base_dir.'/arquivos/eventos/'.(int)$evento_id);	
		
		$sql->setExcluir('evento_arquivos');
		$sql->adOnde('evento_arquivo_evento_id='.(int)$evento_id);
		$sql->exec();
		$sql->limpar();	
			
		$sql->setExcluir('eventos');
		$sql->adOnde('evento_id='.(int)$evento_id);
		$sql->exec();
		$sql->limpar();	
		}
	
	
	//checar se tem eventos de calendario
	$sql->adTabela('projeto_comunicacao_evento_calendario');
	$sql->esqUnir('eventos','eventos', 'projeto_comunicacao_evento_calendario.evento_id=eventos.evento_id');
	$sql->adCampo('eventos.evento_id, evento_titulo,formatar_data(evento_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(evento_fim, \'%d/%m/%Y  %H:%i\') AS fim');
	$sql->adOnde('projeto_comunicacao_evento_id ='.(int)$projeto_comunicacao_evento_id);	
  $lista = $sql->Lista();
  $sql->Limpar();
  
  $saida='';
	if (count($lista)) $saida.='<table width=100% cellspacing=0 cellpadding=0>';
	foreach ($lista as $linha){
		$saida.='<tr><td width="40">&nbsp;</td>';
		$saida.='<td colspan=6><a href="javascript: void(0);" onclick="pop_evento('.$linha['evento_id'].');">'.$linha['inicio'].' - '.$linha['fim'].' - '.utf8_encode($linha['evento_titulo']).'</a></td>';
		$saida.='<td align=center width="48">';
		$saida.=imagem('icones/vazio16.gif');
		$saida.='<a href="javascript: void(0);" onclick="adicionar_evento_calendario('.$projeto_comunicacao_evento_id.', '.$linha['evento_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta evento de calendário do plano de comunicação?').'\')) {excluir_calendario('.$projeto_comunicacao_evento_id.', '.$linha['evento_id'].');}">'.imagem('icones/remover.png').'</a>';
		$saida.='</td>';
		$saida.='</tr>';
		}
	if (count($lista)) $saida.='</table>';
	if ($saida){
		$objResposta = new xajaxResponse();
		$objResposta->assign('evento_'.$projeto_comunicacao_evento_id,"innerHTML", $saida);
		return $objResposta;	
		}
	}	
$xajax->registerFunction("excluir_calendario");		
	
	
function inserir_evento($projeto_comunicacao_evento_id, $projeto_id, $projeto_comunicacao_evento_evento, $projeto_comunicacao_evento_objetivo, $projeto_comunicacao_evento_responsavel, $projeto_comunicacao_evento_publico, $projeto_comunicacao_evento_canal, $projeto_comunicacao_evento_periodicidade){
	global $Aplic;
	$sql = new BDConsulta;
	if (!$projeto_comunicacao_evento_id){
	 	$sql->adTabela('projeto_comunicacao_evento');
		$sql->adCampo('count(projeto_comunicacao_evento_id) AS soma');
		$sql->adOnde('projeto_comunicacao_evento_projeto ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		$sql->adTabela('projeto_comunicacao_evento');
		$sql->adInserir('projeto_comunicacao_evento_ordem', $soma_total);
		$sql->adInserir('projeto_comunicacao_evento_data', date('Y-m-d H:i:s'));
		$sql->adInserir('projeto_comunicacao_evento_usuario', $Aplic->usuario_id);		
		$sql->adInserir('projeto_comunicacao_evento_projeto', $projeto_id);
		$sql->adInserir('projeto_comunicacao_evento_evento', previnirXSS(utf8_decode($projeto_comunicacao_evento_evento)));
		$sql->adInserir('projeto_comunicacao_evento_objetivo', previnirXSS(utf8_decode($projeto_comunicacao_evento_objetivo)));
		$sql->adInserir('projeto_comunicacao_evento_responsavel', previnirXSS(utf8_decode($projeto_comunicacao_evento_responsavel)));
		$sql->adInserir('projeto_comunicacao_evento_publico', previnirXSS(utf8_decode($projeto_comunicacao_evento_publico)));
		$sql->adInserir('projeto_comunicacao_evento_canal', previnirXSS(utf8_decode($projeto_comunicacao_evento_canal)));
		$sql->adInserir('projeto_comunicacao_evento_periodicidade', previnirXSS(utf8_decode($projeto_comunicacao_evento_periodicidade)));
		$sql->exec();
		$sql->Limpar();
		}
	else{
		$sql->adTabela('projeto_comunicacao_evento');
		$sql->adAtualizar('projeto_comunicacao_evento_evento', previnirXSS(utf8_decode($projeto_comunicacao_evento_evento)));
		$sql->adAtualizar('projeto_comunicacao_evento_objetivo', previnirXSS(utf8_decode($projeto_comunicacao_evento_objetivo)));
		$sql->adAtualizar('projeto_comunicacao_evento_responsavel', previnirXSS(utf8_decode($projeto_comunicacao_evento_responsavel)));
		$sql->adAtualizar('projeto_comunicacao_evento_publico', previnirXSS(utf8_decode($projeto_comunicacao_evento_publico)));
		$sql->adAtualizar('projeto_comunicacao_evento_canal', previnirXSS(utf8_decode($projeto_comunicacao_evento_canal)));
		$sql->adAtualizar('projeto_comunicacao_evento_periodicidade', previnirXSS(utf8_decode($projeto_comunicacao_evento_periodicidade)));
		$sql->adAtualizar('projeto_comunicacao_evento_data', date('Y-m-d H:i:s'));
		$sql->adAtualizar('projeto_comunicacao_evento_usuario', $Aplic->usuario_id);
		$sql->adOnde('projeto_comunicacao_evento_id = '.$projeto_comunicacao_evento_id);
		$sql->exec();
		$sql->Limpar();
		}
	return true;
	}
$xajax->registerFunction("inserir_evento");	

function adicionar_calendario($projeto_comunicacao_evento_id=null, $evento_id=null){
	$sql = new BDConsulta;
	$sql->adTabela('projeto_comunicacao_evento_calendario');
	$sql->adCampo('count(projeto_comunicacao_evento_calendario_id)');
	$sql->adOnde('projeto_comunicacao_evento_id = '.(int)$projeto_comunicacao_evento_id);
	$sql->adOnde('evento_id = '.(int)$evento_id);
	$existe = $sql->Resultado();
	$sql->limpar();
	
	if (!$existe){
		
		$sql->adTabela('projeto_comunicacao_evento_calendario');
		$sql->adCampo('count(projeto_comunicacao_evento_calendario_id) AS soma');
		$sql->adOnde('projeto_comunicacao_evento_id ='.(int)$projeto_comunicacao_evento_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
		
		$sql->adTabela('projeto_comunicacao_evento_calendario');
		$sql->adInserir('ordem', $soma_total);
		$sql->adInserir('projeto_comunicacao_evento_id', (int)$projeto_comunicacao_evento_id);
		$sql->adInserir('evento_id', (int)$evento_id);
		$sql->exec();
		$sql->Limpar();
		}
	
	

	

	//checar se tem eventos de calendario
	$sql->adTabela('projeto_comunicacao_evento_calendario');
	$sql->esqUnir('eventos','eventos', 'projeto_comunicacao_evento_calendario.evento_id=eventos.evento_id');
	$sql->adCampo('eventos.evento_id, evento_titulo,formatar_data(evento_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(evento_fim, \'%d/%m/%Y  %H:%i\') AS fim');
	$sql->adOnde('projeto_comunicacao_evento_id ='.(int)$projeto_comunicacao_evento_id);	
  $lista = $sql->Lista();
  $sql->Limpar();
  
  $saida='';
	if (count($lista)) $saida.='<table width=100% cellspacing=0 cellpadding=0>';
	foreach ($lista as $linha){
		$saida.='<tr><td width="40">&nbsp;</td>';
		$saida.='<td colspan=6><a href="javascript: void(0);" onclick="pop_evento('.$linha['evento_id'].');">'.$linha['inicio'].' - '.$linha['fim'].' - '.utf8_encode($linha['evento_titulo']).'</a></td>';
		$saida.='<td align=center width="48">';
		$saida.=imagem('icones/vazio16.gif');
		$saida.='<a href="javascript: void(0);" onclick="adicionar_evento_calendario('.$projeto_comunicacao_evento_id.', '.$linha['evento_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta evento de calendário do plano de comunicação?').'\')) {excluir_calendario('.$projeto_comunicacao_evento_id.', '.$linha['evento_id'].');}">'.imagem('icones/remover.png').'</a>';
		$saida.='</td>';
		$saida.='</tr>';
		}
	if (count($lista)) $saida.='</table>';
	if ($saida){
		$objResposta = new xajaxResponse();
		$objResposta->assign('evento_'.$projeto_comunicacao_evento_id,"innerHTML", $saida);
		return $objResposta;	
		}

	}
$xajax->registerFunction("adicionar_calendario");	

function lista_artefatos($projeto_id){
	global $Aplic;
	$saida='';

	$sql = new BDConsulta;
	$sql->adTabela('projeto_comunicacao_evento');
	$sql->adCampo('*');
	$sql->adOnde('projeto_comunicacao_evento_projeto='.(int)$projeto_id);
	$sql->adOrdem('projeto_comunicacao_evento_ordem ASC');
	$eventos=$sql->Lista();

	if ($eventos && count($eventos)) {
		$saida.= '<table class="tbl1" cellspacing=0 cellpadding=0 border=0 width="100%"><tr><th></th>';
		$saida.='<th>Evento</th>';
		$saida.='<th>Objetivo</th>';
		$saida.='<th>'.utf8_encode('Responsável').'</th>';
		$saida.='<th>'.utf8_encode('Público alvo').'</th>';
		$saida.='<th>Canal</th>';
		$saida.='<th>Periodicidade</th>';
		$saida.='<th nowrap="nowrap"></th></tr>';
		}
	foreach ($eventos as $evento) {
		$saida.='<tr>';
		$saida.='<td nowrap="nowrap" width="40" align="center">';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$evento['projeto_comunicacao_evento_ordem'].', '.$evento['projeto_comunicacao_evento_id'].',\'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$evento['projeto_comunicacao_evento_ordem'].', '.$evento['projeto_comunicacao_evento_id'].',\'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$evento['projeto_comunicacao_evento_ordem'].', '.$evento['projeto_comunicacao_evento_id'].',\'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
		$saida.='<a href="javascript:void(0);" onclick="mudar_ordem('.$evento['projeto_comunicacao_evento_ordem'].', '.$evento['projeto_comunicacao_evento_id'].',\'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
		$saida.='</td>';
		$saida.='<td>'.($evento['projeto_comunicacao_evento_evento'] ? utf8_encode($evento['projeto_comunicacao_evento_evento']) : '&nbsp;').'</td>';
		$saida.='<td>'.($evento['projeto_comunicacao_evento_objetivo'] ? utf8_encode($evento['projeto_comunicacao_evento_objetivo']) : '&nbsp;').'</td>';
		$saida.='<td>'.($evento['projeto_comunicacao_evento_responsavel'] ? utf8_encode($evento['projeto_comunicacao_evento_responsavel']) : '&nbsp;').'</td>';
		$saida.='<td>'.($evento['projeto_comunicacao_evento_publico'] ? utf8_encode($evento['projeto_comunicacao_evento_publico']) : '&nbsp;').'</td>';
		$saida.='<td>'.($evento['projeto_comunicacao_evento_canal'] ? utf8_encode($evento['projeto_comunicacao_evento_canal']) : '&nbsp;').'</td>';
		$saida.='<td>'.($evento['projeto_comunicacao_evento_periodicidade'] ? utf8_encode($evento['projeto_comunicacao_evento_periodicidade']) : '&nbsp;').'</td>';
		$saida.='<td width="'.($Aplic->profissional ? '48' : '32').'" align="center">';
		if ($Aplic->profissional) $saida.='<a href="javascript: void(0);" onclick="adicionar_evento_calendario('.$evento['projeto_comunicacao_evento_id'].', 0);">'.imagem('icones/adicionar_evento.png').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="editar_evento('.$evento['projeto_comunicacao_evento_id'].');">'.imagem('icones/editar.gif').'</a>';
		$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta evento do plano de comunicação?').'\')) {excluir_evento('.$evento['projeto_comunicacao_evento_id'].');}">'.imagem('icones/remover.png').'</a>';
		$saida.='</td>';
		$saida.='</tr>';
		
		if ($Aplic->profissional){
			$saida.='<tr><td colspan=20><div id="evento_'.$evento['projeto_comunicacao_evento_id'].'">';
			
			

			//checar se tem eventos de calendario
			$sql->adTabela('projeto_comunicacao_evento_calendario');
			$sql->esqUnir('eventos','eventos', 'projeto_comunicacao_evento_calendario.evento_id=eventos.evento_id');
			$sql->adCampo('eventos.evento_id, evento_titulo,formatar_data(evento_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(evento_fim, \'%d/%m/%Y  %H:%i\') AS fim');
			$sql->adOnde('projeto_comunicacao_evento_id ='.(int)$evento['projeto_comunicacao_evento_id']);	
		  $lista = $sql->Lista();
		  $sql->Limpar();

			if (count($lista)) $saida.='<table width=100% cellspacing=0 cellpadding=0>';
			
			foreach ($lista as $linha){
				$saida.='<tr><td width="40">&nbsp;</td>';
				$saida.='<td colspan=6><a href="javascript: void(0);" onclick="pop_evento('.$linha['evento_id'].');">'.$linha['inicio'].' - '.$linha['fim'].' - '.utf8_encode($linha['evento_titulo']).'</a></td>';
				$saida.='<td align=center width="48">';
				$saida.=imagem('icones/vazio16.gif');
				$saida.='<a href="javascript: void(0);" onclick="adicionar_evento_calendario('.$evento['projeto_comunicacao_evento_id'].', '.$linha['evento_id'].');">'.imagem('icones/editar.gif').'</a>';
				$saida.='<a href="javascript: void(0);" onclick="if (confirm(\''.utf8_encode('Tem certeza que deseja excluir esta evento de calendário do plano de comunicação?').'\')) {excluir_calendario('.$evento['projeto_comunicacao_evento_id'].', '.$linha['evento_id'].');}">'.imagem('icones/remover.png').'</a>';
				$saida.='</td>';
				$saida.='</tr>';
				}
			
			if (count($lista)) $saida.='</table>';	
			$saida.='</div></td></tr>';
			}
		}
	if ($eventos && count($eventos)) $saida.='</table>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_eventos',"innerHTML", $saida);
	return $objResposta;	
	}

$xajax->registerFunction("lista_artefatos");			
function mudar_ordem($ordem, $projeto_comunicacao_evento_id, $direcao, $projeto_id){
	
		$sql = new BDConsulta;
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_comunicacao_evento');
		$sql->adOnde('projeto_comunicacao_evento_id != '.(int)$projeto_comunicacao_evento_id);
		$sql->adOnde('projeto_comunicacao_evento_projeto = '.(int)$projeto_id);
		$sql->adOrdem('projeto_comunicacao_evento_ordem');
		$eventos = $sql->Lista();
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
			$novo_ui_ordem = count($eventos) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($eventos) + 1)) {
			$sql->adTabela('projeto_comunicacao_evento');
			$sql->adAtualizar('projeto_comunicacao_evento_ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_comunicacao_evento_id = '.(int)$projeto_comunicacao_evento_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($eventos as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_comunicacao_evento');
					$sql->adAtualizar('projeto_comunicacao_evento_ordem', $idx);
					$sql->adOnde('projeto_comunicacao_evento_id = '.(int)$acao['projeto_comunicacao_evento_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_comunicacao_evento');
					$sql->adAtualizar('projeto_comunicacao_evento_ordem', $idx + 1);
					$sql->adOnde('projeto_comunicacao_evento_id = '.(int)$acao['projeto_comunicacao_evento_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
	return true;
	}	
$xajax->registerFunction("mudar_ordem");		

function excluir_evento($projeto_comunicacao_evento_id){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_comunicacao_evento');
	$sql->adOnde('projeto_comunicacao_evento_id='.(int)$projeto_comunicacao_evento_id);
	$sql->exec();
	$sql->limpar();	
	return true;
	}	
$xajax->registerFunction("excluir_evento");	

function editar_evento($projeto_comunicacao_evento_id){
	$saida='';

	$sql = new BDConsulta;
	$sql->adTabela('projeto_comunicacao_evento');
	$sql->adCampo('projeto_comunicacao_evento.*');
	$sql->adOnde('projeto_comunicacao_evento_id='.(int)$projeto_comunicacao_evento_id);
	$evento=$sql->linha();
	$sql->limpar();	
	
	$objResposta = new xajaxResponse();
	
	$objResposta->assign("projeto_comunicacao_evento_evento","value", utf8_encode($evento['projeto_comunicacao_evento_evento']));
	$objResposta->assign("projeto_comunicacao_evento_objetivo","value", utf8_encode($evento['projeto_comunicacao_evento_objetivo']));
	$objResposta->assign("projeto_comunicacao_evento_responsavel","value", utf8_encode($evento['projeto_comunicacao_evento_responsavel']));
	$objResposta->assign("projeto_comunicacao_evento_publico","value", utf8_encode($evento['projeto_comunicacao_evento_publico']));
	$objResposta->assign("projeto_comunicacao_evento_canal","value", utf8_encode($evento['projeto_comunicacao_evento_canal']));
	$objResposta->assign("projeto_comunicacao_evento_periodicidade","value", utf8_encode($evento['projeto_comunicacao_evento_periodicidade']));
	
	$objResposta->assign("projeto_comunicacao_evento_id","value", $projeto_comunicacao_evento_id);
	

	return $objResposta;	
	}	
	
$xajax->registerFunction("editar_evento");		

function cancelar_edicao(){
	$saida=utf8_encode('<table cellpadding=0 cellspacing="2" width="100%"><tr><td><b>Evento</b></td><td><b>Objetivo</b></td><td><b>Responsável</b></td><td><b>Publico alvo</b></td><td><b>Canal</b></td><td><b>Periodicidade</b></td><td></td></tr><tr><td valign=top><textarea name="projeto_comunicacao_evento_evento" id="projeto_comunicacao_evento_evento" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_comunicacao_evento_objetivo" id="projeto_comunicacao_evento_objetivo" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_comunicacao_evento_responsavel" id="projeto_comunicacao_evento_responsavel" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_comunicacao_evento_publico" id="projeto_comunicacao_evento_publico" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_comunicacao_evento_canal" id="projeto_comunicacao_evento_canal" class="textarea" style="width:100%"></textarea></td><td valign=top><textarea name="projeto_comunicacao_evento_periodicidade" id="projeto_comunicacao_evento_periodicidade" class="textarea" style="width:100%"></textarea></td><td><a href="javascript:void(0);" onclick="javascript:inserir_evento();">'.imagem("icones/adicionar.png").'</a></td></tr></table>');
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_edicao',"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("cancelar_edicao");

$xajax->processRequest();

?>