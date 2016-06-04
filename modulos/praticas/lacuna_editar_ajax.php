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

function exibir_cias($cias){
	global $config;
	$cias_selecionadas=explode(',', $cias);
	$saida_cias='';
	if (count($cias_selecionadas)) {
			$saida_cias.= '<table cellpadding=0 cellspacing=0>';
			$saida_cias.= '<tr><td class="texto" style="width:400px;">'.link_cia($cias_selecionadas[0]);
			$qnt_lista_cias=count($cias_selecionadas);
			if ($qnt_lista_cias > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_cias; $i < $i_cmp; $i++) $lista.=link_cia($cias_selecionadas[$i]).'<br>';		
					$saida_cias.= dica('Outr'.$config['genero_organizacao'].'s '.ucfirst($config['organizacoes']), 'Clique para visualizar '.$config['genero_organizacao'].'s demais '.strtolower($config['organizacoes']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_cias\');">(+'.($qnt_lista_cias - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_cias"><br>'.$lista.'</span>';
					}
			$saida_cias.= '</td></tr></table>';
			} 
	else 	$saida_cias.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_cias',"innerHTML", utf8_encode($saida_cias));
	return $objResposta;				
	}
	
$xajax->registerFunction("exibir_cias");	



function exibir_usuarios($usuarios){
	global $config;
	$usuarios_selecionados=explode(',', $usuarios);
	$saida_usuarios='';
	if (count($usuarios_selecionados)) {
			$saida_usuarios.= '<table cellpadding=0 cellspacing=0>';
			$saida_usuarios.= '<tr><td class="texto" style="width:400px;">'.link_usuario($usuarios_selecionados[0],'','','esquerda');
			$qnt_lista_usuarios=count($usuarios_selecionados);
			if ($qnt_lista_usuarios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_usuarios; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i],'','','esquerda').'<br>';		
					$saida_usuarios.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_usuarios\');">(+'.($qnt_lista_usuarios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_usuarios"><br>'.$lista.'</span>';
					}
			$saida_usuarios.= '</td></tr></table>';
			} 
	else $saida_usuarios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_usuarios',"innerHTML", utf8_encode($saida_usuarios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_usuarios");

function exibir_depts($depts){
	global $config;
	$depts_selecionados=explode(',', $depts);
	$saida_depts='';
	if (count($depts_selecionados)) {
			$saida_depts.= '<table cellpadding=0 cellspacing=0>';
			$saida_depts.= '<tr><td class="texto" style="width:400px;">'.link_secao($depts_selecionados[0]);
			$qnt_lista_depts=count($depts_selecionados);
			if ($qnt_lista_depts > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
					$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
					}
			$saida_depts.= '</td></tr></table>';
			} 
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");

function marcar_marcador($indicador_lacuna_id=0, $uuid='', $pratica_marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	
	$sql->setExcluir('indicador_lacuna_nos_marcadores');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('pratica_marcador_id = '.(int)$pratica_marcador_id);
	$sql->exec();
	$sql->limpar();

	if ($marcado){
		$sql->adTabela('indicador_lacuna_nos_marcadores');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('indicador_lacuna_id', (int)$indicador_lacuna_id);
		$sql->adInserir('ano', (int)$ano);
		$sql->adInserir('pratica_marcador_id', (int)$pratica_marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	}
$xajax->registerFunction("marcar_marcador");		
	
	
function mudar_indicador_tipo_ajax($indicador_tipo='', $campo='', $posicao='', $script=''){
	$vetor=vetor_campo_sistema('IndicadorTipo',$indicador_tipo, true);
	$saida=selecionaVetor($vetor, $campo, $script, $indicador_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_indicador_tipo_ajax");	

function mudar_pauta($indicador_lacuna_id=0, $uuid='', $pratica_modelo_id=0, $ano=0){
	global $config;
	
	$sql = new BDConsulta;
	
	$sql->adTabela('pratica_criterio');
	$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
	$sql->limpar();

	$sql->adTabela('pratica_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_oculto');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$itens=$sql->ListaChaveSimples('pratica_item_id');
	$sql->limpar();
	
	$sql->adTabela('pratica_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador.pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('indicador_lacuna_nos_marcadores');
	$sql->esqUnir('indicador_lacuna', 'indicador_lacuna', 'indicador_lacuna_nos_marcadores.indicador_lacuna_id=indicador_lacuna.indicador_lacuna_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=indicador_lacuna_nos_marcadores.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador.pratica_marcador_id');
	
	if ($uuid) $sql->adOnde('indicador_lacuna_nos_marcadores.uuid = \''.$uuid.'\'');
	else $sql->adOnde('indicador_lacuna_nos_marcadores.indicador_lacuna_id = '.(int)$indicador_lacuna_id);	
	$sql->adOnde('indicador_lacuna_nos_marcadores.ano='.(int)$ano);
	$lista_marcadores=$sql->Lista();
	$sql->limpar();
	

	$atuais=array();
	foreach($lista_marcadores as $chave => $valor) $atuais[]=$valor['pratica_marcador_id'];
	
	$criterio_atual='';
	$item_atual='';

	$saida='<table border=0 cellpadding=1 cellspacing=0 width="100%">';

	if ($marcadores && count($marcadores)) $saida.= '<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>'.ucfirst($config['marcadores']).' atendid'.$config['genero_marcador'].'s pelo indicador</b></p></td></tr>';
	
		
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			if ($criterio_atual) $saida.='</table></td></tr>';
			$criterio_atual=$dado['pratica_criterio_id'];
		
			$saida.= '<tr><td align="left" colspan=2 nowrap="nowrap"><a href="javascript: void(0);" onclick="expandir_colapsar(\'criterio_'.$criterio_atual.'\')">'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</a></td></tr>';
			$saida.='<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding=0 cellspacing=0 width="100%">';
			}
			
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) $saida.='<tr><td align="left" colspan=2 nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</td></tr>';
			}
		$marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais));
		$saida.='<tr><td align="left" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="pratica_marcador_id[]" value="'.$dado['pratica_marcador_id'].'" id="checagem_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:middle" onclick="marcar_marcador('.$dado['pratica_marcador_id'].')" '.($marcado ? 'checked="checked"' : '').' /><b>'.$dado['pratica_marcador_letra'].'</b>&nbsp;</td><td id="caixa_'.$dado['pratica_marcador_id'].'" width="100%" '.($marcado ? 'style="background-color:#FFFF00;"' : '').' valign="middle">'.$dado['pratica_marcador_texto'].($dado['pratica_marcador_extra'] ? '<br>'.$dado['pratica_marcador_extra'] : '<br>&nbsp;').'</td></tr>';
		}
	if ($criterio_atual) $saida.='</table>';	
	$saida.='</table>';
	$saida=utf8_encode($saida);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_pauta',"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_pauta");	

function exibir_combo($posicao, $tabela, $chave='', $campo='', $onde='', $ordem='', $script='', $campo_id='', $campoatual='', $campobranco=true, $tabela2='', $uniao2='', $tabela3='', $uniao3=''){
		$sql = new BDConsulta;
		$sql->adTabela($tabela);
		if ($tabela2) $sql->esqUnir($tabela2, $tabela2, $uniao2);
		if ($tabela3) $sql->esqUnir($tabela3, $tabela3, $uniao3);
		if ($chave) $sql->adCampo($chave);
		if ($campo) $sql->adCampo($campo);
		if ($onde) $sql->adOnde($onde);
		if ($ordem) $sql->adOrdem($onde);
		$linhas=$sql->Lista();
		$sql->limpar();
		$vetor=array();
		$chave=explode('.',$chave); 
		$chave = array_pop($chave);
		if ($campobranco) $vetor[]='';
		foreach($linhas as $linha)$vetor[$linha[$chave]]=utf8_encode($linha[$campo]);
		$saida=selecionaVetor($vetor, $campo_id, $script, $campoatual);
		$objResposta = new xajaxResponse();
		$objResposta->assign($posicao,"innerHTML", $saida);
		return $objResposta;
		}
$xajax->registerFunction("exibir_combo");	
	

function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");
		


function mudar_usuario_ajax($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao=''){
	global $Aplic, $config;

	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	if ($segunda_tabela && $condicao){
		$sql->esqUnir($segunda_tabela,$segunda_tabela,$condicao);
		}
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();	
	$vetor[0]='';
	foreach((array)$linhas as $linha) {
		$vetor[$linha['usuario_id']]=utf8_encode($linha['nome_usuario']);
		}
		
	if (count($vetor)==1) $vetor[-1]='';
	$saida=selecionaVetor($vetor, $campo, $script, $usuario_id);

	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");


$xajax->processRequest();

?>