<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\gut_ajax.php		

Funções Ajax utilizadas em gpweb\modulos\praticas\gut.php																																							
																																												
********************************************************************************************/
global $Aplic;

	include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
	$xajax = new xajax();
	
	$xajax->configure('defaultMode', 'synchronous');
//	$xajax->setFlag('debug',true);
//	$xajax->setFlag('outputEntities',true);
	
	function excluir_linha_ajax($gut_id=0, $gut_linha_id=0){
		$sql = new BDConsulta;
		$sql->setExcluir('gut_linha');
		$sql->adOnde('gut_linha_id = '.(int)$gut_linha_id);
		$sql->exec();
		$sql->limpar();
		return exibir_matriz_ajax($gut_id,0, true);
		}
	
	
	function inserir_linha_ajax($gut_id=0, $gut_texto='', $gut_g=0, $gut_t=0, $gut_u=0, $gut_linha_id=0){
		$sql = new BDConsulta;
		if($gut_linha_id){
			$sql->adTabela('gut_linha');
			$sql->adAtualizar('gut_id', (int)$gut_id);
			$sql->adAtualizar('gut_g', (int)$gut_g);
			$sql->adAtualizar('gut_t', (int)$gut_t);
			$sql->adAtualizar('gut_u', (int)$gut_u);
			$sql->adAtualizar('gut_texto', previnirXSS(utf8_decode($gut_texto)));
			$sql->adOnde('gut_linha_id='.(int)$gut_linha_id);
			$sql->exec();
			$sql->limpar();
			}
		else{
			$sql->adTabela('gut_linha');
			$sql->adInserir('gut_id', (int)$gut_id);
			$sql->adInserir('gut_g', (int)$gut_g);
			$sql->adInserir('gut_t', (int)$gut_t);
			$sql->adInserir('gut_u', (int)$gut_u);
			$sql->adInserir('gut_texto', previnirXSS(utf8_decode($gut_texto)));
			$sql->exec();
			$sql->limpar();
			}
		

		return exibir_matriz_ajax($gut_id,0, true);
		}
	
	
	function exibir_matriz_ajax($gut_id, $gut_linha_id=0, $editar=false){
		
		$sql = new BDConsulta;

		$saida='';
		
		$saida.= '<table cellspacing=0 cellpadding=2 border=1><tr><td style="background-color:#9bbb59">Risco/Problema</td><td style="background-color:#4f81bd">Gravidade</td><td style="background-color:#c0504d">'.utf8_encode('Urgência').'</td><td style="background-color:#f79646">'.utf8_encode('Tendência').'</td><td style="background-color:#4bacc6">Prioridade</td>'.($editar ? '<td style="background-color:#d0d0d0"></td>' : '').'</tr>';
		

		$opcoes=array(1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5);
		if ($gut_linha_id && $editar){
			$sql->adTabela('gut_linha');
			$sql->adCampo('gut_linha_id, gut_texto, gut_g, gut_u, gut_t, (gut_g+gut_u+gut_t) as prioridade');
			$sql->adOnde('gut_linha_id='.(int)$gut_linha_id);
			$linha=$sql->Linha();
			$sql->limpar();
			$saida.='<tr><td align=center style="background-color:#d8e4bc"><input type="text" style="width:200px" class="texto" name="gut_texto" value="'.utf8_encode($linha['gut_texto']).'" /></td><td align=center style="background-color:#b8cce4">'.selecionaVetor($opcoes, 'gut_g', 'size="1" class="texto" onchange="calcular();"', $linha['gut_g']).'</td><td align=center style="background-color:#e6b8b7">'.selecionaVetor($opcoes, 'gut_u', 'size="1" class="texto" onchange="calcular();"', $linha['gut_u']).'</td><td align=center style="background-color:#fcd5b4">'.selecionaVetor($opcoes, 'gut_t', 'size="1" class="texto" onchange="calcular();"', $linha['gut_t']).'</td><td align=center style="background-color:#b7dee8"><input type="text" style="width:30px" class="texto" id="prioridade" name="prioridade" value="'.($linha['prioridade'] ? $linha['prioridade']  : '3').'" disabled="disabled" /></td><td><a href="javascript: void(0);" onclick="xajax_inserir_linha_ajax('.$gut_id.', frm_gut.gut_texto.value, frm_gut.gut_g.value, frm_gut.gut_u.value, frm_gut.gut_t.value, '.$gut_linha_id.')">'.imagem('icones/ok.png').'</a><a href="javascript: void(0);" onclick="xajax_exibir_matriz_ajax('.$gut_id.',0, true);">'.imagem('icones/cancelar.png').'</a></td></tr>';
			}
		else if($editar) $saida.='<tr><td align=center style="background-color:#d8e4bc"><input type="text" style="width:200px" class="texto" name="gut_texto" value="" /></td><td align=center style="background-color:#b8cce4">'.selecionaVetor($opcoes, 'gut_g', 'size="1" class="texto" onchange="calcular();"', 0).'</td><td align=center style="background-color:#e6b8b7">'.selecionaVetor($opcoes, 'gut_u', 'size="1" class="texto" onchange="calcular();"', 0).'</td><td align=center style="background-color:#fcd5b4">'.selecionaVetor($opcoes, 'gut_t', 'size="1" class="texto" onchange="calcular();"', 0).'</td><td align=center style="background-color:#b7dee8"><input type="text" style="width:30px" class="texto" id="prioridade" name="prioridade" value="3" disabled="disabled" /></td><td><a href="javascript: void(0);" onclick="if (frm_gut.gut_texto.value.length>2) {xajax_inserir_linha_ajax('.$gut_id.', frm_gut.gut_texto.value, frm_gut.gut_g.value, frm_gut.gut_u.value, frm_gut.gut_t.value, 0);} else alert(\''.utf8_encode('Insira um risco/problema válido').'\');">'.imagem('icones/adicionar.png').'</a></td></tr>';
		
		$sql->adTabela('gut_linha');
		$sql->adOnde('gut_id='.(int)$gut_id);
		$sql->adCampo('gut_linha_id, gut_texto, gut_g, gut_u, gut_t, (gut_g+gut_u+gut_t) as prioridade');
		$sql->adOrdem('prioridade DESC');
		$linhas=$sql->Lista();
		$sql->limpar();
		
		foreach($linhas as $linha) {
			$saida.= '<tr><td align=left style="background-color:#d8e4bc">'.utf8_encode($linha['gut_texto']).'</td><td align=center style="background-color:#b8cce4">'.$linha['gut_g'].'</td><td align=center style="background-color:#e6b8b7">'.$linha['gut_u'].'</td><td align=center style="background-color:#fcd5b4">'.$linha['gut_t'].'</td><td align=center style="background-color:#b7dee8">'.$linha['prioridade'].'</td>'.($editar ? '<td><a href="javascript: void(0);" onclick="xajax_exibir_matriz_ajax('.$gut_id.', '.$linha['gut_linha_id'].', true)">'.imagem('icones/editar.gif').'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este problema?\')) {xajax_excluir_linha_ajax('.$gut_id.', '.$linha['gut_linha_id'].')}">'.imagem('icones/remover.png').'</a></td>' : '').'</tr>';
			}
		$saida.='</table>';
		

		$objResposta = new xajaxResponse();
		$objResposta->assign('campo_matriz',"innerHTML", $saida);
		return $objResposta;	
		}
		
	
	
	
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

	function exibir_dept($posicao, $cia_id='', $script='', $campo_id='', $campoatual='', $campobranco=true){
		global $Aplic;
		require_once ($Aplic->getClasseModulo('depts'));
		$sql = new BDConsulta;
		$sql->adTabela('depts');
		$sql->adCampo('dept_id, dept_nome, dept_superior');
		$sql->adOnde('dept_cia='.(int)$cia_id);
		$sql->adOrdem('dept_superior, dept_nome');
		$depts = $sql->carregarListaVetor();
		$sql->limpar();
		$depts['0'] = array(0, '', -1);
		$vetor=array();
		foreach($depts as $dept) $vetor[$dept[0]]=array($dept[0], utf8_encode($dept[1]), $dept[2]);
		$saida=selecionaVetorArvore($vetor, $campo_id, $script, $campoatual);	
		$objResposta = new xajaxResponse();
		$objResposta->assign($posicao,"innerHTML", $saida);
		return $objResposta;
		}
	
function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
	
function mudar_campos_ajax($cia_id=0){
	global $Aplic;

	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_id, projeto_nome');
	$sql->adOnde('projeto_cia='.(int)$cia_id);
	$sql->adOrdem('projeto_nome');
	$linhas = $sql->Lista();
	$sql->limpar();
	$lista_projetos =array();
	foreach($linhas as $linha) $lista_projetos[$linha['projeto_id']]=utf8_encode($linha['projeto_nome']);

	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_id, pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$linhas = $sql->Lista();
	$sql->limpar();
	$lista_indicadores =array();
	foreach($linhas as $linha) $lista_indicadores[$linha['pratica_indicador_id']]=utf8_encode($linha['pratica_indicador_nome']);

	$sql->adTabela('praticas');
	$sql->adCampo('pratica_id, pratica_nome');
	$sql->adOnde('pratica_cia='.(int)$cia_id);
	$linhas = $sql->Lista();
	$sql->limpar();
	$lista_praticas =array();
	foreach($linhas as $linha) $lista_praticas[$linha['pratica_id']]=utf8_encode($linha['pratica_nome']);

	$saida_projeto=selecionaVetor($lista_projetos, 'lista_projetos', 'onchange=\'mudar_tarefa();\' style=\'width:250px;\' size=\'3\' class=\'texto\' ondblclick=\'Mover(document.codigo.lista_projetos, document.codigo.projetos_escolhidos);\'');
	$saida_indicador=selecionaVetor($lista_indicadores, 'lista_indicadores', 'style="width:250px;" size="3" class="texto" ondblclick=\'Mover(document.codigo.lista_indicadores, document.codigo.indicadores_escolhidos);\'');
	$saida_pratica=selecionaVetor($lista_praticas, 'lista_praticas', 'style="width:250px;" size="3" class="texto" ondblclick=\'Mover(document.codigo.lista_praticas, document.codigo.praticas_escolhidas);\'');
	
	$vazio=array();
	$saida_tarefa=selecionaVetor($vazio, 'lista_tarefas', 'style="width:250px;" size="3" class="texto"');
	
	$objResposta = new xajaxResponse();
	$objResposta->assign('gut_projeto',"innerHTML", $saida_projeto);
	$objResposta->assign('gut_indicador',"innerHTML", $saida_indicador);
	$objResposta->assign('gut_pratica',"innerHTML", $saida_pratica);
	$objResposta->assign('gut_tarefa',"innerHTML", $saida_tarefa);
	return $objResposta;
	}		


$xajax->registerFunction("exibir_matriz_ajax");
$xajax->registerFunction("inserir_linha_ajax");
$xajax->registerFunction("excluir_linha_ajax");


$xajax->registerFunction("mudar_campos_ajax");		
$xajax->registerFunction("selecionar_om_ajax");	
$xajax->registerFunction("exibir_combo");
$xajax->registerFunction("exibir_dept");	
$xajax->processRequest();
?>