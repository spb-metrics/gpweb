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

if ($Aplic->profissional) include_once BASE_DIR.'/modulos/praticas/pratica_editar_ajax_pro.php';


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

function marcar_evidencia($pratica_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;

	if (!$marcado){
		$sql->setExcluir('pratica_evidencia');
		if ($uuid) $sql->adOnde('pratica_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_evidencia_pratica = '.(int)$pratica_id);
		if ($ano) $sql->adOnde('pratica_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_evidencia_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que não ira marcar duas vezes
		$sql->adTabela('pratica_evidencia');
		$sql->adCampo('count(pratica_evidencia_id)');
		if ($uuid) $sql->adOnde('pratica_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_evidencia_pratica = '.(int)$pratica_id);
		if ($ano) $sql->adOnde('pratica_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_evidencia_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_evidencia');
			if ($uuid) $sql->adInserir('pratica_evidencia_uuid', $uuid);
			else $sql->adInserir('pratica_evidencia_pratica', (int)$pratica_id);
			if ($ano) $sql->adInserir('pratica_evidencia_ano', (int)$ano);
			$sql->adInserir('pratica_evidencia_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_evidencia");	


function marcar_complemento($pratica_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	if (!$marcado){
		$sql->setExcluir('pratica_complemento');
		if ($uuid) $sql->adOnde('pratica_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_complemento_pratica = '.(int)$pratica_id);
		$sql->adOnde('pratica_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_complemento_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que nso ira marcar duas vezes
		$sql->adTabela('pratica_complemento');
		$sql->adCampo('count(pratica_complemento_id)');
		if ($uuid) $sql->adOnde('pratica_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_complemento_pratica = '.(int)$pratica_id);
		$sql->adOnde('pratica_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_complemento_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_complemento');
			if ($uuid) $sql->adInserir('pratica_complemento_uuid', $uuid);
			else $sql->adInserir('pratica_complemento_pratica', (int)$pratica_id);
			$sql->adInserir('pratica_complemento_ano', (int)$ano);
			$sql->adInserir('pratica_complemento_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_complemento");	




function marcar_marcador($pratica_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_nos_marcadores');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica = '.(int)$pratica_id);
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('marcador = '.(int)$marcador_id);
	$sql->exec();
	$sql->limpar();

	if ($marcado){
		$sql->adTabela('pratica_nos_marcadores');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica', (int)$pratica_id);
		$sql->adInserir('ano', (int)$ano);
		$sql->adInserir('marcador', (int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	}
	
$xajax->registerFunction("marcar_marcador");	


function marcar_verbo($pratica_id=0, $uuid='', $marcador_id=0, $verbo_id=0, $marcado=false, $ano=0){
	
	$sql = new BDConsulta;
	
	//contar quantas o pai tinha antes
	$sql->adTabela('pratica_nos_verbos');
	$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_verbo_id=verbo');
	$sql->adCampo('count(verbo)');
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('pratica_verbo_marcador='.(int)$marcador_id);
	if ($uuid) $sql->adOnde('uuid=\''.$uuid.'\'');
	else $sql->adOnde('pratica='.(int)$pratica_id);
	$quantidade=$sql->Resultado();
	$sql->limpar();

	$sql->adTabela('pratica_nos_verbos');
	$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_nos_verbos.verbo=pratica_verbo_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
	
	$sql->setExcluir('pratica_nos_verbos');
	if ($uuid) $sql->adOnde('uuid=\''.$uuid.'\'');
	else $sql->adOnde('pratica='.(int)$pratica_id);
	$sql->adOnde('ano='.(int)$ano);
	$sql->adOnde('verbo='.(int)$verbo_id);
	$sql->exec();
	$sql->limpar();

	if ($marcado){
		$sql->adTabela('pratica_nos_verbos');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica', (int)$pratica_id);
		$sql->adInserir('verbo', (int)$verbo_id);
		$sql->adInserir('ano', (int)$ano);
		$sql->exec();
		$sql->limpar();
		}
	
	//preciso marcar o pai
	if ($marcado && !$quantidade){
		marcar_marcador($pratica_id, $uuid, $marcador_id, true, $ano);
		$objResposta = new xajaxResponse();
		$objResposta->assign('caixa_'.$marcador_id, "style.backgroundColor",	"#FFFF00");
		$objResposta->assign('checagem_'.$marcador_id, "checked",	"checked");
	
		return $objResposta;	
		}
	
	//preciso desmarcar o pai
	if (!$marcado && $quantidade==1){
		marcar_marcador($pratica_id, $uuid, $marcador_id, false, $ano);
		$objResposta = new xajaxResponse();
		$objResposta->assign('caixa_'.$marcador_id, "style.backgroundColor",	"#f8f7f5");
		$objResposta->assign('checagem_'.$marcador_id, "checked",	"");
		return $objResposta;	
		}	
		

	}
	
$xajax->registerFunction("marcar_verbo");	


function mudar_pauta($pratica_id=0, $uuid='', $pratica_modelo_id=0, $ano=0){
	global $config;
	
	$sql = new BDConsulta;
	
	$sql->adTabela('pratica_criterio');
	$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
	$sql->limpar();
	
	$sql->adTabela('pratica_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs, pratica_item_orientacao, pratica_item_oculto');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$itens=$sql->ListaChaveSimples('pratica_item_id');
	$sql->limpar();
	
	$sql->adTabela('pratica_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra, pratica_marcador_evidencia, pratica_marcador_orientacao');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('pratica_nos_marcadores');
	$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica = '.(int)$pratica_id);
	$sql->adOnde('ano='.(int)$ano);
	$atuais_marcadores=$sql->carregarColuna();
	$sql->limpar();
	
	
	
	$sql->adTabela('pratica_complemento');
	$sql->esqUnir('praticas', 'praticas', 'pratica_complemento_pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_complemento_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_complemento_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_complemento_pratica = '.(int)$pratica_id);
	$sql->adOnde('pratica_complemento_ano='.(int)$ano);
	$atuais_complementos=$sql->carregarColuna();
	$sql->limpar();
	
	
	$sql->adTabela('pratica_evidencia');
	$sql->esqUnir('praticas', 'praticas', 'pratica_evidencia_pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_evidencia_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_evidencia_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_evidencia_pratica = '.(int)$pratica_id);
	$sql->adOnde('pratica_evidencia_ano='.(int)$ano);
	$atuais_evidencias=$sql->carregarColuna();
	$sql->limpar();
	
	
	
	
	
	
	$sql->adTabela('pratica_verbo');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
	$sql->adCampo('pratica_verbo_id, pratica_verbo_texto, pratica_verbo_marcador');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=0');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$sql->adOrdem('pratica_verbo_id');
	$lista_verbos=$sql->Lista();
	$sql->limpar();
	$verbo=array();
	foreach($lista_verbos as $linha) $verbo[$linha['pratica_verbo_marcador']][$linha['pratica_verbo_id']]=$linha['pratica_verbo_texto']; 

	
	$sql->adTabela('pratica_nos_verbos');
	$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_nos_verbos.verbo=pratica_verbo_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
	$sql->adCampo('verbo');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica = '.(int)$pratica_id);
	$sql->adOnde('ano='.(int)$ano);
	$atuais_verbos=$sql->carregarColuna();
	$sql->limpar();
	

	$criterio_atual='';
	$item_atual='';
	
	$saida='<table border=0 cellpadding=0 cellspacing=1 width="100%">';
	if ($marcadores && count($marcadores)) $saida.='<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>'.ucfirst($config['marcadores']).' atendid'.$config['genero_marcador'].'s pel'.$config['genero_pratica'].' '.$config['pratica'].'<b></p></td></tr>';
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			if ($criterio_atual) $saida.='</table></td></tr>';
			$criterio_atual=$dado['pratica_criterio_id'];
			$saida.='<tr><td align="left" colspan=2 nowrap="nowrap"><b>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</b></td></tr>';
			$saida.='<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding=0 cellspacing=0 width="100%">';
			}
			
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) $saida.='<tr><td align="left" colspan=20 nowrap="nowrap">'.($itens[$dado['pratica_item_id']]['pratica_item_orientacao'] ? dica('Orientações', $itens[$dado['pratica_item_id']]['pratica_item_orientacao']) : '').'<b>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</b>'.($itens[$dado['pratica_item_id']]['pratica_item_orientacao'] ? dicaF() : '').'</td></tr>';
			}
		
		$marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_marcadores));
		
		$complemento_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_complementos));
		$evidencia_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_evidencias));
		
		$saida.='<tr><td align="right" nowrap="nowrap" valign="top" width=40><input name="pratica_marcador_id[]" value="'.$dado['pratica_marcador_id'].'" id="checagem_'.$dado['pratica_marcador_id'].'" type="checkbox" DISABLED style="vertical-align:middle"  onclick="marcar_marcador('.$dado['pratica_marcador_id'].');" '.($marcado ? 'checked="checked"' : '').' /><b>'.$dado['pratica_marcador_letra'].'.</b></td><td><table cellpadding=0 cellspacing=0><tr style="line-height: 18px;"><td id="caixa_'.$dado['pratica_marcador_id'].'" '.($marcado ? ' style="vertical-align:top; background-color:#FFFF00;"' : 'style="vertical-align:top"').'>'.($dado['pratica_marcador_orientacao'] ? dica('Orientações', $dado['pratica_marcador_orientacao']) : '').$dado['pratica_marcador_texto'].($dado['pratica_marcador_orientacao'] ? dicaF() : '').'</td></tr></table></td></tr>';
		
		if ($dado['pratica_marcador_extra']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Complementos para a Excelência','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos dos complementos para a excelência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_complemento_id[]" '.($complemento_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="complemento_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_complemento('.$dado['pratica_marcador_id'].');" /></td><td id="caixa3_'.$dado['pratica_marcador_id'].'" '.($complemento_marcado ? 'checked="checked" style="background-color:#abfeff;"' : '').'>'.$dado['pratica_marcador_extra'].'</td></tr></table>'.dicaF().'</td></tr>';
		if ($dado['pratica_marcador_evidencia']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Evidências','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos da evidência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_evidencia_id[]" '.($evidencia_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="evidencia_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_evidencia('.$dado['pratica_marcador_id'].');" /></td><td  id="caixa4_'.$dado['pratica_marcador_id'].'" '.($evidencia_marcado ? 'checked="checked" style="background-color:#abffaf;"' : '').'>'.$dado['pratica_marcador_evidencia'].'</td></tr></table>'.dicaF().'</td></tr>';
		
		
		if (isset($verbo[$dado['pratica_marcador_id']])){
			foreach($verbo[$dado['pratica_marcador_id']] as $chave => $texto) {
				$marcado=in_array($chave, $atuais_verbos);
				$saida.='<tr><td align="left" valign="top">&nbsp;</td><td><table cellpadding=0 cellspacing=0><tr><td>&nbsp;&nbsp;&nbsp;&nbsp;<input name="pratica_verbo_id[]" '.($marcado ? 'checked="checked"' : '').' value="'.$chave.'" id="verbo_'.$chave.'" type="checkbox" style="vertical-align:middle" onclick="marcar_verbo('.$chave.', '.$dado['pratica_marcador_id'].');" /></td><td id="caixa2_'.$chave.'" '.($marcado ? 'checked="checked" style="background-color:#ffddab;"' : '').'>'.$texto.'</td></tr></table></td></tr>';
				}
			$saida.='<tr><td colspan=2>&nbsp;</td></tr>';	
			}
		
		
		}
	if ($criterio_atual) $saida.='</table>';	
	$saida.='</table>';
	
	
	$saida=utf8_encode($saida);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_pauta',"innerHTML", $saida);
	
	
	//mudar as legendas
	$sql->adTabela('pratica_regra_campo');
	$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
	$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_regra_campo_resultado=0 OR pratica_regra_campo_resultado IS NULL');
	$sql->adOrdem('pratica_regra_campo_id');
	$lista=$sql->Lista();
	$sql->limpar();
	
	$vetor_existe=array(
		'pratica_controlada',
		'pratica_proativa',
		'pratica_abrange_pertinentes',
		'pratica_continuada',
		'pratica_refinada',
		'pratica_melhoria_aprendizado',
		'pratica_coerente',
		'pratica_interrelacionada',
		'pratica_cooperacao',
		'pratica_cooperacao_partes',
		'pratica_arte',
		'pratica_inovacao',
		'pratica_gerencial',
		'pratica_agil',
		'pratica_refinada_implantacao',
		'pratica_incoerente'
		);
		
		
	$original=array();	
	$usou=array();	
	foreach($lista as $linha){	
		if (in_array($linha['pratica_regra_campo_nome'], $vetor_existe)){
			$campo=utf8_encode(dica($linha['pratica_regra_campo_texto'], $linha['pratica_regra_campo_descricao']).'<b>'.$linha['pratica_regra_campo_texto'].'</b>:'.dicaF());
			$objResposta->assign('legenda_'.$linha['pratica_regra_campo_nome'],"innerHTML", $campo);
			$usou[$linha['pratica_regra_campo_nome']]=1;
			}
		}
		
	if (!isset($usou['pratica_controlada'])) $original['pratica_controlada']=dica('Controlad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' é controlad'.$config['genero_pratica'].'.').'Controlad'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_proativa'])) $original['pratica_proativa']=dica('Proativ'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem a capacidade de antecipar-se aos fatos, a fim de prevenir a ocorrência de situações potencialmente indesejáveis e aumentar a confiança e a previsibilidade dos processos gerenciais.').'Proativ'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_abrange_pertinentes'])) $original['pratica_abrange_pertinentes']=dica('Abrangente',($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem cobertura ou escopo suficientes, horizontal ou vertical, conforme pertinente a cada processo gerencial requerido pelas áreas, processos, produtos ou partes interessadas, considerando-se o perfil d'.$config['genero_organizacao'].' '.$config['organizacao'].' e estratégias.').'Abrangente:'.dicaF();
	if (!isset($usou['pratica_continuada'])) $original['pratica_continuada']=dica('Uso Continuado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem utilização periódica e ininterrupta, considerando-se a realização de pelo menos um ciclo completo.').'Uso Continuado:'.dicaF();
	if (!isset($usou['pratica_refinada'])) $original['pratica_refinada']=dica('Refinad'.$config['genero_pratica'], ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta aperfeiçoamento decorrente dos processos de melhoria e inovação.<br><br>Em estágios avançados de refinamento, esse subfator exige processos gerenciais atendidos por '.$config['praticas'].' no estado da arte e que incorporam alguma inovação.').'Refinad'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_melhoria_aprendizado'])) $original['pratica_melhoria_aprendizado']=dica('Melhorias Decorrentes do Aprendizado', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' apresenta melhorias decorrentes do aprendizado.').'Melhorias pelo aprendizado:'.dicaF();
	if (!isset($usou['pratica_coerente'])) $original['pratica_coerente']=dica('Coerente', ($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem relação harmônica com as estratégias e objetivos d'.$config['genero_organizacao'].' '.$config['organizacao'].', incluindo valores e princípios.').'Coerente:'.dicaF();
	if (!isset($usou['pratica_interrelacionada'])) $original['pratica_interrelacionada']=dica('Inter-relacionad'.$config['genero_pratica'],($config['genero_pratica']=='a' ? 'A ': 'O ').$config['pratica'].' tem implementação de modo complementar com outr'.($config['genero_pratica']=='a' ? 'as ': 'os ').$config['praticas'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].', onde apropriado.').'Inter-relacionad'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_cooperacao'])) $original['pratica_cooperacao']=dica('Cooperativ'.$config['genero_pratica'],'Há colaboração entre as áreas d'.$config['genero_organizacao'].' '.$config['organizacao'].' na implementação – planejamento, execução, controle ou aperfeiçoamento – n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cooperativ'.$config['genero_pratica'].':'.dicaF();
	if (!isset($usou['pratica_cooperacao_partes'])) $original['pratica_cooperacao_partes']=dica('Cooperação com as Partes Interessadas','Há colaboração com as partes interessadas pertinentes a cada processo gerencial requerido.').'Cooperação com interessados:'.dicaF();
	if (!isset($usou['pratica_arte'])) $original['pratica_arte']=dica('Estado-de-Arte',($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' espelha o estado-da-arte.').'Estado-de-arte:'.dicaF();
	if (!isset($usou['pratica_inovacao'])) $original['pratica_inovacao']=dica('Inovador'.($config['genero_pratica']=='a' ? 'a': ''),($config['genero_pratica']=='a' ? 'Esta ': 'Este ').$config['pratica'].' apresenta uma inovação de ruptura representando um novo benchmark.').'Inovador'.($config['genero_pratica']=='a' ? 'a': '').':'.dicaF();
	if (!isset($usou['pratica_gerencial'])) $original['pratica_gerencial']=dica('Padrão gerencial','Há padrão gerencial suficiente que oriente a execução adequada d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Padrão gerencial:'.dicaF();
	if (!isset($usou['pratica_agil'])) $original['pratica_agil']=dica('Agilidade','Há agilidade suficiente nos processos gerenciais exigidos no Critério, incorporados n'.$config['genero_pratica'].' '.$config['pratica'].'.').'Agilidade:'.dicaF();
	if (!isset($usou['pratica_agil'])) $original['refinada_implantacao']=dica('Aperfeiçoamento em Implantação','<p>'.$config['genero_pratica'].' '.$config['pratica'].' incorpora ou representa um aperfeiçoamento em implantação.').'Aperfeiçoamento em implantação:'.dicaF();
	if (!isset($usou['pratica_agil'])) $original['pratica_incoerente']=dica('Incoerência grave','Existe incoerência grave entre os valores, princípios, estratégias e objetivos organizacionais, na realização d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Incoerência grave:'.dicaF();
	foreach($original as $chave => $valor) $objResposta->assign('legenda_'.$chave,"innerHTML", utf8_encode($valor));
		
	return $objResposta;
	}

$xajax->registerFunction("mudar_pauta");		
	
	

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