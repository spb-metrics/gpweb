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

$social=$Aplic->modulo_ativo('social');
if ($social) require_once BASE_DIR.'/modulos/social/social.class.php';

if ($Aplic->profissional) require_once BASE_DIR.'/modulos/praticas/indicador_editar_ajax_pro.php';

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

function editar_filtro($pratica_indicador_filtro_id=null){
	$social=$Aplic->modulo_ativo('social');
	
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_filtro');
	$sql->adCampo('pratica_indicador_filtro.*');
	$sql->adOnde('pratica_indicador_filtro_id = '.(int)$pratica_indicador_filtro_id);
	$linha = $sql->linha();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	
	$objResposta->assign("pratica_indicador_filtro_id","value", ($pratica_indicador_filtro_id ? $pratica_indicador_filtro_id : null));
	$objResposta->assign("pratica_indicador_filtro_status","value", ($linha['pratica_indicador_filtro_status'] ? utf8_encode($linha['pratica_indicador_filtro_status']) : ''));
	$tarefa_tipos=vetor_campo_sistema('TipoTarefa', $linha['pratica_indicador_filtro_tipo']);
	$objResposta->assign("combo_tarefa_tipo","innerHTML", utf8_encode(selecionaVetor($tarefa_tipos, 'pratica_indicador_filtro_tipo', 'class="texto" size=1 style="width:284px;" onchange="mudar_tarefa_tipo();"', $linha['pratica_indicador_filtro_tipo'])));
	$objResposta->assign("pratica_indicador_filtro_prioridade","value", ($linha['pratica_indicador_filtro_prioridade'] ? utf8_encode($linha['pratica_indicador_filtro_prioridade']) : ''));
	$objResposta->assign("pratica_indicador_filtro_setor","value",  ($linha['pratica_indicador_filtro_setor'] ? utf8_encode($linha['pratica_indicador_filtro_setor']) : ''));
	
	$segmento=array('' => '');
	if ($linha['pratica_indicador_filtro_segmento']){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaSegmento\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$linha['pratica_indicador_filtro_setor'].'\'');
		$sql->adOrdem('sisvalor_valor');
		$segmento+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	$objResposta->assign("combo_segmento_tarefa","innerHTML", utf8_encode(selecionaVetor($segmento, 'pratica_indicador_filtro_segmento', 'style="width:284px;" class="texto" onchange="mudar_intervencao_tarefa();"', $linha['pratica_indicador_filtro_segmento'])));

	$intervencao=array('' => '');
	if ($linha['pratica_indicador_filtro_intervencao']){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaIntervencao\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$linha['pratica_indicador_filtro_segmento'].'\'');
		$sql->adOrdem('sisvalor_valor');
		$intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	$objResposta->assign("combo_intervencao_tarefa","innerHTML", utf8_encode(selecionaVetor($intervencao, 'pratica_indicador_filtro_intervencao', 'style="width:284px;" class="texto" onchange="mudar_tipo_intervencao_tarefa();"', $linha['pratica_indicador_filtro_intervencao'])));

	$tipo_intervencao=array('' => '');
	if ($linha['pratica_indicador_filtro_tipo_intervencao']){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_titulo=\'TarefaTipoIntervencao\'');
		$sql->adOnde('sisvalor_chave_id_pai=\''.$linha['pratica_indicador_filtro_intervencao'].'\'');
		$sql->adOrdem('sisvalor_valor');
		$tipo_intervencao+=$sql->listaVetorChave('sisvalor_valor_id', 'sisvalor_valor');
		$sql->limpar();
		}
	$objResposta->assign("combo_tipo_intervencao_tarefa","innerHTML", utf8_encode(selecionaVetor($tipo_intervencao, 'pratica_indicador_filtro_tipo_intervencao', 'style="width:284px;" class="texto"', $linha['pratica_indicador_filtro_tipo_intervencao'])));

	if ($social) {
		$objResposta->assign("pratica_indicador_filtro_social","value", ($linha['pratica_indicador_filtro_social'] ? utf8_encode($linha['pratica_indicador_filtro_social']) : ''));
		require_once BASE_DIR.'/modulos/social/social.class.php';
		$objResposta->assign("acao_combo_tarefa","innerHTML", utf8_encode(selecionar_acao_para_ajax($linha['pratica_indicador_filtro_social'], 'pratica_indicador_filtro_acao', 'size="1" style="width:284px;" class="texto"', '', $linha['pratica_indicador_filtro_acao'], false)));
		}

	$objResposta->assign("pratica_indicador_filtro_estado","value", ($linha['pratica_indicador_filtro_estado'] ? utf8_encode($linha['pratica_indicador_filtro_estado']) : ''));
	$objResposta->assign("combo_cidade_tarefa","innerHTML", utf8_encode(selecionar_cidades_para_ajax($linha['pratica_indicador_filtro_estado'], 'pratica_indicador_filtro_cidade', 'class="texto" '.($social ? 'onchange="mudar_comunidades_tarefa()"' : '').' style="width:284px;"', '', $linha['pratica_indicador_filtro_cidade'], true, false)));
	if ($social) $objResposta->assign("combo_comunidade_tarefa","innerHTML", utf8_encode(selecionar_comunidade_para_ajax($linha['pratica_indicador_filtro_cidade'],'pratica_indicador_filtro_comunidade', 'class="texto" style="width:284px;"', '', $linha['pratica_indicador_filtro_comunidade'], false)));
	$objResposta->assign("pratica_indicador_filtro_texto","value", ($linha['pratica_indicador_filtro_texto'] ? utf8_encode($linha['pratica_indicador_filtro_texto']) : ''));

	return $objResposta;
	}
$xajax->registerFunction("editar_filtro");



function excluir_filtro($pratica_indicador_filtro_id, $pratica_indicador_id=null, $uuid=null){
	$sql = new BDConsulta;	
	$sql->setExcluir('pratica_indicador_filtro');
	$sql->adOnde('pratica_indicador_filtro_id = '.(int)$pratica_indicador_filtro_id);
	$sql->exec();
	$sql->limpar();
	$saida=exibir_filtros($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_filtros',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_filtro");

function filtros($pratica_indicador_id=null, $uuid=null){
	$saida=exibir_filtros($pratica_indicador_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_filtros',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("filtros");

function exibir_filtros($pratica_indicador_id=null, $uuid=null){
	global $Aplic, $config;
	if (!$Aplic->profissional) return;
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador_filtro');
	$sql->adCampo('pratica_indicador_filtro.*');
	if ($pratica_indicador_id) $sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
	else $sql->adOnde('uuid = \''.$uuid.'\'');
	$filtros = $sql->lista();
	$sql->limpar();
	$saida='';
	if (count($filtros)){
	
		$social=$Aplic->modulo_ativo('social');
	
		$saida.= '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Filtro</th><th></th></tr>';
		foreach($filtros as $linha) {
			$saida.= '<tr><td><table cellpadding=0 cellspacing=0 width="100%">';
			if ($linha['pratica_indicador_filtro_status'] && !isset($status_tarefa)) $status_tarefa = getSisValor('StatusTarefa');
			if ($linha['pratica_indicador_filtro_status'] && isset($status_tarefa[$linha['pratica_indicador_filtro_status']])) $saida.='<tr><td align=right width="90">Status:</td><td>'.$status_tarefa[$linha['pratica_indicador_filtro_status']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo']) $saida.='<tr><td align=right width="90">Tipo de '.$config['tarefa'].':</td><td>'.getSisValorCampo('TipoTarefa', $linha['pratica_indicador_filtro_tipo']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_prioridade'] && !isset($prioridade_tarefa)) $prioridade_tarefa = getSisValor('PrioridadeTarefa');
			if ($linha['pratica_indicador_filtro_prioridade'] && isset($prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']])) $saida.='<tr><td align=right width="90">Prioridade:</td><td>'.$prioridade_tarefa[$linha['pratica_indicador_filtro_prioridade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_setor']) $saida.='<tr><td align=right width="90">Setor:</td><td>'.getSisValorCampo('TarefaSetor', $linha['pratica_indicador_filtro_setor']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_segmento']) $saida.='<tr><td align=right width="90">Segmento:</td><td>'.getSisValorCampo('TarefaSegmento', $linha['pratica_indicador_filtro_segmento']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_intervencao']) $saida.='<tr><td align=right width="90">Intervenção:</td><td>'.getSisValorCampo('TarefaIntervencao', $linha['pratica_indicador_filtro_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_tipo_intervencao']) $saida.='<tr><td align=right width="90">Tipo:</td><td>'.getSisValorCampo('TarefaTipoIntervencao', $linha['pratica_indicador_filtro_tipo_intervencao']).'</td></tr>';
			if ($linha['pratica_indicador_filtro_social'] && !isset($programa_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social', 'social', 'social_id=pratica_indicador_filtro_social');
				$sql->adCampo('social_id, social_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$programa_social = $sql->listaVetorChave('social_id', 'social_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_social'] && isset($programa_social[$linha['pratica_indicador_filtro_social']])) $saida.='<tr><td align=right width="90">Programa:</td><td>'.$programa_social[$linha['pratica_indicador_filtro_social']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_acao'] && !isset($acao_social)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_acao', 'social_acao', 'social_acao_id=pratica_indicador_filtro_acao');
				$sql->adCampo('social_acao_id, social_acao_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$acao_social = $sql->listaVetorChave('social_acao_id', 'social_acao_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_acao'] && isset($acao_social[$linha['pratica_indicador_filtro_acao']])) $saida.='<tr><td align=right width="90">Ação:</td><td>'.$acao_social[$linha['pratica_indicador_filtro_acao']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_estado'] && !isset($estado)) {
				$sql->adTabela('estado');
				$sql->adCampo('estado_sigla, estado_nome');
				$sql->adOrdem('estado_nome');
				$estado=$sql->listaVetorChave('estado_sigla', 'estado_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_estado'] && isset($estado[$linha['pratica_indicador_filtro_estado']])) $saida.='<tr><td align=right width="90">Estado:</td><td>'.$estado[$linha['pratica_indicador_filtro_estado']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_cidade'] && !isset($municipio)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('municipios', 'municipios', 'municipios.municipio_id=pratica_indicador_filtro_cidade');
				$sql->adCampo('municipio_id, municipio_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$municipio = $sql->listaVetorChave('municipio_id', 'municipio_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_cidade'] && isset($municipio[$linha['pratica_indicador_filtro_cidade']])) $saida.='<tr><td align=right width="90">Município:</td><td>'.$municipio[$linha['pratica_indicador_filtro_cidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_comunidade'] && !isset($comunidade)) {
				$sql->adTabela('pratica_indicador_filtro', 'pratica_indicador_filtro');
				$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_comunidade_id=pratica_indicador_filtro_comunidade');
				$sql->adCampo('social_comunidade_id, social_comunidade_nome');
				$sql->adOnde('pratica_indicador_filtro_indicador = '.(int)$pratica_indicador_id);
				$comunidade = $sql->listaVetorChave('social_comunidade_id', 'social_comunidade_nome');
				$sql->limpar();
				}
			if ($linha['pratica_indicador_filtro_comunidade'] && isset($comunidade[$linha['pratica_indicador_filtro_comunidade']])) $saida.='<tr><td align=right width="90">Comunidade:</td><td>'.$comunidade[$linha['pratica_indicador_filtro_comunidade']].'</td></tr>';
			if ($linha['pratica_indicador_filtro_texto']) $saida.='<tr><td align=right width="90">Texto:</td><td>'.$linha['pratica_indicador_filtro_texto'].'</td></tr>';
			$saida.= '</table></td><td><a href="javascript: void(0);" onclick="editar_filtro('.$linha['pratica_indicador_filtro_id'].');">'.imagem('icones/editar.gif', 'Editar Filtro', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar este filtro.').'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este filtro?\')) {excluir_filtro('.$linha['pratica_indicador_filtro_id'].');}">'.imagem('icones/remover.png', 'Excluir Filtro', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir este filtro.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;	
	}


function incluir_filtro(
	$filtro_id=null, 
	$indicador=null, 
	$uuid=null, 
	$filtro_status=null, 
	$prioridade=null, 
	$tipo=null, 
	$setor=null, 
	$segmento=null, 
	$intervencao=null, 
	$tipo_intervencao=null, 
	$social=null, 
	$acao=null, 
	$estado=null, 
	$cidade=null, 
	$comunidade=null, 
	$texto=null){
	
	global $Aplic;
	
	if (!$Aplic->profissional) return;
	
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_filtro');
	if (!$filtro_id){
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica_indicador_filtro_indicador', (int)$indicador);
		
		if ($filtro_status) $sql->adInserir('pratica_indicador_filtro_status', previnirXSS(utf8_decode($filtro_status)));
		if ($prioridade) $sql->adInserir('pratica_indicador_filtro_prioridade', previnirXSS(utf8_decode($prioridade)));
		if ($tipo) $sql->adInserir('pratica_indicador_filtro_tipo', previnirXSS(utf8_decode($tipo)));
		if ($setor) $sql->adInserir('pratica_indicador_filtro_setor', previnirXSS(utf8_decode($setor)));
		if ($segmento) $sql->adInserir('pratica_indicador_filtro_segmento', previnirXSS(utf8_decode($segmento)));
		if ($intervencao) $sql->adInserir('pratica_indicador_filtro_intervencao', previnirXSS(utf8_decode($intervencao)));
		if ($tipo_intervencao) $sql->adInserir('pratica_indicador_filtro_tipo_intervencao', previnirXSS(utf8_decode($tipo_intervencao)));
		if ($social) $sql->adInserir('pratica_indicador_filtro_social', previnirXSS(utf8_decode($social)));
		if ($acao) $sql->adInserir('pratica_indicador_filtro_acao', previnirXSS(utf8_decode($acao)));
		if ($estado) $sql->adInserir('pratica_indicador_filtro_estado', previnirXSS(utf8_decode($estado)));
		if ($cidade) $sql->adInserir('pratica_indicador_filtro_cidade', previnirXSS(utf8_decode($cidade)));
		if ($comunidade) $sql->adInserir('pratica_indicador_filtro_comunidade', previnirXSS(utf8_decode($comunidade)));
		if ($texto) $sql->adInserir('pratica_indicador_filtro_texto', previnirXSS(utf8_decode($texto)));
		$sql->exec();
		$sql->limpar();
		}
	else{
		$sql->adAtualizar('pratica_indicador_filtro_status', previnirXSS(utf8_decode(($filtro_status ? $filtro_status : null))));
		$sql->adAtualizar('pratica_indicador_filtro_prioridade', previnirXSS(utf8_decode(($prioridade ? $prioridade : null))));
		$sql->adAtualizar('pratica_indicador_filtro_tipo', previnirXSS(utf8_decode(($tipo ? $tipo : null))));
		$sql->adAtualizar('pratica_indicador_filtro_setor', previnirXSS(utf8_decode(($setor ? $setor : null))));
		$sql->adAtualizar('pratica_indicador_filtro_segmento', previnirXSS(utf8_decode(($segmento ? $segmento : null))));
		$sql->adAtualizar('pratica_indicador_filtro_intervencao', previnirXSS(utf8_decode(($intervencao ? $intervencao : null))));
		$sql->adAtualizar('pratica_indicador_filtro_tipo_intervencao', previnirXSS(utf8_decode(($tipo_intervencao ? $tipo_intervencao : null))));
		$sql->adAtualizar('pratica_indicador_filtro_social', previnirXSS(utf8_decode(($social ? $social : null))));
		$sql->adAtualizar('pratica_indicador_filtro_acao', previnirXSS(utf8_decode(($acao ? $acao : null))));
		$sql->adAtualizar('pratica_indicador_filtro_estado', previnirXSS(utf8_decode(($estado ? $estado : null))));
		$sql->adAtualizar('pratica_indicador_filtro_cidade', previnirXSS(utf8_decode(($cidade ? $cidade : null))));
		$sql->adAtualizar('pratica_indicador_filtro_comunidade', previnirXSS(utf8_decode(($comunidade ? $comunidade : null))));
		$sql->adAtualizar('pratica_indicador_filtro_texto', previnirXSS(utf8_decode(($texto ? $texto : null))));
		$sql->adOnde('pratica_indicador_filtro_id = '.(int)$filtro_id);
		$sql->exec();
		$sql->limpar();
		}
	$saida=exibir_filtros($indicador, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_filtros',"innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_filtro");




function acao_ajax($social_id=0){
	$saida=selecionar_acao_para_ajax($social_id, 'pratica_indicador_filtro_acao', 'size="1" class="texto" style="width:284px;');
	$objResposta = new xajaxResponse();
	$objResposta->assign("acao_combo_tarefa","innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("acao_ajax");	

function selecionar_comunidade_ajax($municipio_id='', $campo='', $posicao='', $script='', $vazio='', $tarefa_comunidade=0){
	$saida=selecionar_comunidade_para_ajax($municipio_id, $campo, $script, $vazio, $tarefa_comunidade);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_comunidade_ajax");

function mudar_tarefa_tipo($tarefa_tipo='', $campo='', $posicao='', $script=''){
	$vetor=vetor_campo_sistema('TipoTarefa',$tarefa_tipo, true);
	$saida=selecionaVetor($vetor, $campo, $script, $tarefa_tipo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_tarefa_tipo");	

function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_cidades_ajax");	



function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	$sql->adOnde('sisvalor_projeto IS NULL');
	$sql->adOrdem('sisvalor_valor');
	
	if(get_magic_quotes_gpc()) $script = stripslashes($script);

	$lista=$sql->Lista();
	$sql->limpar();
	$vetor=array(0 => '&nbsp;');	
	foreach($lista as $linha) $vetor[utf8_encode($linha['sisvalor_valor_id'])]=utf8_encode($linha['sisvalor_valor']);	
	$saida=selecionaVetor($vetor, $campo, $script);

	$objResposta = new xajaxResponse(); 
	$objResposta->assign($posicao,"innerHTML", $saida); 
	return $objResposta; 
	}	
$xajax->registerFunction("mudar_ajax");

function qnt_metas($pratica_indicador_id=null, $uuid=''){
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('count(pratica_indicador_meta_id)');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
	$qnt = $sql->resultado();
	$sql->limpar();
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("qnt_metas","value", (int)$qnt);
	return $objResposta;
	}
$xajax->registerFunction("qnt_metas");

function editar_meta($pratica_indicador_meta_id=null){
	
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
	$sql->adCampo('pratica_indicador_meta.*');
	$sql->adOnde('pratica_indicador_meta_id = '.(int)$pratica_indicador_meta_id);
	$linha = $sql->linha();
	$sql->limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("pratica_indicador_meta_id","value", $pratica_indicador_meta_id);
	$objResposta->assign("pratica_indicador_meta_data","value", $linha['pratica_indicador_meta_data']);
	$objResposta->assign("data_inicio","value", $linha['data']);
	$objResposta->assign("pratica_indicador_meta_data_meta","value", $linha['pratica_indicador_meta_data_meta']);
	$objResposta->assign("data","value", $linha['data_meta']);
	$objResposta->assign("pratica_indicador_meta_valor_meta","value", ($linha['pratica_indicador_meta_valor_meta']!=null ? number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_proporcao","checked", ($linha['pratica_indicador_meta_proporcao'] ? true : false));
	$objResposta->assign("pratica_indicador_meta_valor_meta_boa","value", ($linha['pratica_indicador_meta_valor_meta_boa']!=null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_valor_meta_regular","value", ($linha['pratica_indicador_meta_valor_meta_regular']!=null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_valor_meta_ruim","value", ($linha['pratica_indicador_meta_valor_meta_ruim']!=null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : ''));
	$objResposta->assign("pratica_indicador_meta_valor_referencial","value", ($linha['pratica_indicador_meta_valor_referencial']!=null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : ''));
	return $objResposta;
	}
$xajax->registerFunction("editar_meta");

function excluir_meta($pratica_indicador_meta_id=null, $pratica_indicador_meta_indicador=null, $uuid=null){
	$sql = new BDConsulta;	
	$sql->setExcluir('pratica_indicador_meta');
	$sql->adOnde('pratica_indicador_meta_id = '.(int)$pratica_indicador_meta_id);
	$sql->exec();
	$sql->limpar();
	$saida=exibe_metas($pratica_indicador_meta_indicador, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("excluir_meta");

function incluir_meta(
	$pratica_indicador_meta_id=null, 
	$pratica_indicador_meta_indicador=null, 
	$uuid=null, 
	$pratica_indicador_meta_data=null, 
	$pratica_indicador_meta_valor_referencial=null, 
	$pratica_indicador_meta_valor_meta=null, 
	$pratica_indicador_meta_proporcao=null, 
	$pratica_indicador_meta_valor_meta_boa=null, 
	$pratica_indicador_meta_valor_meta_regular=null, 
	$pratica_indicador_meta_valor_meta_ruim=null, 
	$pratica_indicador_meta_data_meta=null){

	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	if (!$pratica_indicador_meta_id){
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica_indicador_meta_indicador', (int)$pratica_indicador_meta_indicador);
		if ($pratica_indicador_meta_valor_referencial != '') $sql->adInserir('pratica_indicador_meta_valor_referencial', float_americano($pratica_indicador_meta_valor_referencial));
		$sql->adInserir('pratica_indicador_meta_valor_meta', float_americano($pratica_indicador_meta_valor_meta));
		$sql->adInserir('pratica_indicador_meta_proporcao', ($pratica_indicador_meta_proporcao ? 1 : 0));
		if ($pratica_indicador_meta_valor_meta_boa != '') $sql->adInserir('pratica_indicador_meta_valor_meta_boa', float_americano($pratica_indicador_meta_valor_meta_boa));
		if ($pratica_indicador_meta_valor_meta_regular != '') $sql->adInserir('pratica_indicador_meta_valor_meta_regular', float_americano($pratica_indicador_meta_valor_meta_regular));
		if ($pratica_indicador_meta_valor_meta_ruim != '') $sql->adInserir('pratica_indicador_meta_valor_meta_ruim', float_americano($pratica_indicador_meta_valor_meta_ruim));
		$sql->adInserir('pratica_indicador_meta_data_meta', $pratica_indicador_meta_data_meta);
		$sql->adInserir('pratica_indicador_meta_data', $pratica_indicador_meta_data);
		$sql->exec();
		$sql->limpar();
		}
	else{
		$sql->adAtualizar('pratica_indicador_meta_valor_referencial', ($pratica_indicador_meta_valor_referencial !='' ? float_americano($pratica_indicador_meta_valor_referencial) : null));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta', float_americano($pratica_indicador_meta_valor_meta));
		$sql->adAtualizar('pratica_indicador_meta_proporcao', ($pratica_indicador_meta_proporcao ? 1 : 0));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta_boa', ($pratica_indicador_meta_valor_meta_boa !='' ? float_americano($pratica_indicador_meta_valor_meta_boa) : null));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta_regular', ($pratica_indicador_meta_valor_meta_regular !='' ? float_americano($pratica_indicador_meta_valor_meta_regular) : null));
		$sql->adAtualizar('pratica_indicador_meta_valor_meta_ruim', ($pratica_indicador_meta_valor_meta_ruim !='' ? float_americano($pratica_indicador_meta_valor_meta_ruim) : null));
		$sql->adAtualizar('pratica_indicador_meta_data_meta', $pratica_indicador_meta_data_meta);
		$sql->adAtualizar('pratica_indicador_meta_data', $pratica_indicador_meta_data);
		$sql->adOnde('pratica_indicador_meta_id = '.$pratica_indicador_meta_id);
		$sql->exec();
		$sql->limpar();
		}
	$saida=exibe_metas($pratica_indicador_meta_indicador, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("metas","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("incluir_meta");


function exibe_metas($pratica_indicador_id=null, $uuid=''){
	global $Aplic;
	$sql = new BDConsulta;	
	$sql->adTabela('pratica_indicador_meta');
	$sql->adCampo('formatar_data(pratica_indicador_meta_data, "%d/%m/%Y") as data, formatar_data(pratica_indicador_meta_data_meta, "%d/%m/%Y") as data_meta');
	$sql->adCampo('pratica_indicador_meta_id, pratica_indicador_meta_valor_referencial, pratica_indicador_meta_valor_meta, pratica_indicador_meta_proporcao, pratica_indicador_meta_valor_meta_boa, pratica_indicador_meta_valor_meta_regular, pratica_indicador_meta_valor_meta_ruim');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_meta_indicador = '.(int)$pratica_indicador_id);
	$sql->adOrdem('pratica_indicador_meta_data');
	$metas = $sql->lista();
	
	$sql->limpar();
	
	$saida='';
	if (count($metas)){
		$saida.= '<table class="tbl1" cellpadding=0 cellspacing=0><tr><th>Meta</th>'.($Aplic->profissional ? '<th>Ciclo Anterior</th><th>Bom</th><th>Regular</th><th>Ruim</th>' : '').'<th>Início</th><th>Limite</th><th>Referencial</th><th></th></tr>';
		foreach($metas as $linha) {
			$saida.= '<tr>';
			$saida.= '<td align=right>'.number_format($linha['pratica_indicador_meta_valor_meta'], 2, ',', '.').'</td>';
			if ($Aplic->profissional){
				$saida.= '<td align=center>'.($linha['pratica_indicador_meta_proporcao'] ? 'X' : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_boa'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_boa'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_regular'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_regular'], 2, ',', '.') : '&nbsp;').'</td>';
				$saida.= '<td align=right>'.($linha['pratica_indicador_meta_valor_meta_ruim'] != null ? number_format($linha['pratica_indicador_meta_valor_meta_ruim'], 2, ',', '.') : '&nbsp;').'</td>';
				}
			$saida.= '<td>'.$linha['data'].'</td><td>'.$linha['data_meta'].'</td>';
			$saida.= '<td>'.($linha['pratica_indicador_meta_valor_referencial'] != null ? number_format($linha['pratica_indicador_meta_valor_referencial'], 2, ',', '.') : '&nbsp;').'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_meta('.$linha['pratica_indicador_meta_id'].');">'.imagem('icones/editar.gif', 'Editar Meta', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta meta.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta meta?\')) {excluir_meta('.$linha['pratica_indicador_meta_id'].');}">'.imagem('icones/remover.png', 'Excluir Meta', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta meta.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.='</table>';
		}
	return $saida;
	}

function marcar_marcador($pratica_indicador_id=0, $uuid='', $pratica_marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	$sql->setExcluir('pratica_indicador_nos_marcadores');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
	$sql->adOnde('ano = '.(int)$ano);
	$sql->adOnde('pratica_marcador_id = '.(int)$pratica_marcador_id);
	$sql->exec();
	$sql->limpar();
	
	if ($marcado){
		$sql->adTabela('pratica_indicador_nos_marcadores');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('pratica_indicador_id', (int)$pratica_indicador_id);
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

function gravar_resultados($pratica_indicador_id=0, $pratica_modelo_id=0, $campos){
	$sql = new BDConsulta;
	$campos=explode (',', $campos);
	
	if ($pratica_indicador_id && $pratica_modelo_id){
		$sql->setExcluir('pratica_indicador_nos_marcadores');
		$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_modelo_id = '.(int)$pratica_modelo_id);
		$sql->exec();
		$sql->limpar();
		foreach($campos as $chave => $pratica_marcador_id){
			if($pratica_marcador_id){
				$sql->adTabela('pratica_indicador_nos_marcadores');
				$sql->adInserir('pratica_indicador_id', (int)$pratica_indicador_id);
				$sql->adInserir('pratica_marcador_id', (int)$pratica_marcador_id);
				$sql->adInserir('pratica_modelo_id', (int)$pratica_modelo_id);
				$sql->exec();
				$sql->limpar();
				}
			}
		}
	}
$xajax->registerFunction("gravar_resultados");	

function mudar_pauta($pratica_indicador_id=0, $uuid='', $pratica_modelo_id=0, $ano=''){
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
	$sql->adCampo('pratica_marcador.pratica_marcador_id, pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra, pratica_marcador_evidencia, pratica_marcador_orientacao');
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_criterio_resultado=1');
	$sql->adOrdem('pratica_criterio_numero');
	$sql->adOrdem('pratica_item_numero');
	$sql->adOrdem('pratica_marcador_letra');
	$marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador.pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_indicador_nos_marcadores.uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_nos_marcadores.pratica_indicador_id = '.(int)$pratica_indicador_id);	
	$sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
	$lista_marcadores=$sql->Lista();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_complemento');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_complemento_indicador=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_complemento_marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_indicador_complemento_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_complemento_indicador = '.(int)$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_complemento_ano='.(int)$ano);
	$atuais_complementos=$sql->carregarColuna();
	$sql->limpar();
	
	$sql->adTabela('pratica_indicador_evidencia');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_evidencia_indicador=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_evidencia_marcador');

	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('pratica_marcador_id');
	if ($uuid) $sql->adOnde('pratica_indicador_evidencia_uuid = \''.$uuid.'\'');
	else $sql->adOnde('pratica_indicador_evidencia_indicador = '.(int)$pratica_indicador_id);
	$sql->adOnde('pratica_indicador_evidencia_ano='.(int)$ano);
	$atuais_evidencias=$sql->carregarColuna();
	$sql->limpar();
	
	$atuais=array();
	foreach($lista_marcadores as $chave => $valor) $atuais[]=$valor['pratica_marcador_id'];
	$criterio_atual='';
	$item_atual='';
	$saida='<table cellpadding=0 cellspacing=1>';
	if ($marcadores && count($marcadores)) $saida.= '<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>'.ucfirst($config['marcadores']).' atendid'.$config['genero_marcador'].'s pelo indicador</b></p></td></tr>';
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			if ($criterio_atual) $saida.='</table></td></tr>';
			$criterio_atual=$dado['pratica_criterio_id'];
			$saida.= '<tr><td align="left" colspan=2 nowrap="nowrap"><a href="javascript: void(0);" onclick="expandir_colapsar(\'criterio_'.$criterio_atual.'\')">'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</a></td></tr>';
			$saida.='<tr id="criterio_'.$criterio_atual.'"><td colspan=2><table cellpadding=0 cellspacing=0>';
			}
		if ($dado['pratica_item_id']!=$item_atual){
			$item_atual=$dado['pratica_item_id'];
			if (!$itens[$dado['pratica_item_id']]['pratica_item_oculto']) $saida.='<tr><td align="left" colspan=2 nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</td></tr>';
			}
		$marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais));
		$complemento_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_complementos));
		$evidencia_marcado=(isset($dado['pratica_marcador_id']) && in_array($dado['pratica_marcador_id'], $atuais_evidencias));
		$saida.='<tr><td align="right" nowrap="nowrap" valign="top" width=40><input name="pratica_marcador_id[]" value="'.$dado['pratica_marcador_id'].'" id="checagem_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:middle"  onclick="marcar_marcador('.$dado['pratica_marcador_id'].');" '.($marcado ? 'checked="checked"' : '').' /><b>'.$dado['pratica_marcador_letra'].'.</b></td><td><table cellpadding=0 cellspacing=0><tr style="line-height: 18px;"><td id="caixa_'.$dado['pratica_marcador_id'].'" '.($marcado ? ' style="vertical-align:top; background-color:#FFFF00;"' : 'style="vertical-align:top"').'>'.($dado['pratica_marcador_orientacao'] ? dica('Orientações', $dado['pratica_marcador_orientacao']) : '').$dado['pratica_marcador_texto'].($dado['pratica_marcador_orientacao'] ? dicaF() : '').'</td></tr></table></td></tr>';
		
		if ($dado['pratica_marcador_extra']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Complementos para a Excelência','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos dos complementos para a excelência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_complemento_id[]" '.($complemento_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="complemento_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_complemento('.$dado['pratica_marcador_id'].');" /></td><td id="caixa3_'.$dado['pratica_marcador_id'].'" '.($complemento_marcado ? 'checked="checked" style="background-color:#abfeff;"' : '').'>'.$dado['pratica_marcador_extra'].'</td></tr></table>'.dicaF().'</td></tr>';
		if ($dado['pratica_marcador_evidencia']) $saida.='<tr><td></td><td align="left" valign="top">'.dica('Evidências','Deverá ser marcado caso '.$config['genero_pratica'].' '.$config['pratica'].' atende os requisitos da evidência.').'<table cellpadding=0 cellspacing=0><tr><td style="vertical-align:top"><input name="pratica_evidencia_id[]" '.($evidencia_marcado ? 'checked="checked"' : '').' value="'.$dado['pratica_marcador_id'].'" id="evidencia_'.$dado['pratica_marcador_id'].'" type="checkbox" style="vertical-align:top" onclick="marcar_evidencia('.$dado['pratica_marcador_id'].');" /></td><td  id="caixa4_'.$dado['pratica_marcador_id'].'" '.($evidencia_marcado ? 'checked="checked" style="background-color:#abffaf;"' : '').'>'.$dado['pratica_marcador_evidencia'].'</td></tr></table>'.dicaF().'</td></tr>';
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
	$sql->adOnde('pratica_regra_campo_resultado=1');
	$sql->adOrdem('pratica_regra_campo_id');
	$lista=$sql->Lista();
	$sql->limpar();
	$vetor_existe=array(
		'pratica_indicador_tendencia',
		'pratica_indicador_favoravel',
		'pratica_indicador_superior',
		'pratica_indicador_relevante',
		'pratica_indicador_atendimento',
		'pratica_indicador_lider',
		'pratica_indicador_excelencia',
		'pratica_indicador_estrategico'
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
	if (!isset($usou['pratica_indicador_tendencia'])) $original['pratica_indicador_tendencia']=dica('Tem Tendência','Este indicador tem tendência.').'Tem tendência:'.dicaF();	
	if (!isset($usou['pratica_indicador_favoravel'])) $original['pratica_indicador_favoravel']=dica('Tendência Favorável','Este indicador tem tendência favorável.').'Tendência favorável:'.dicaF();
	if (!isset($usou['pratica_indicador_superior'])) $original['pratica_indicador_superior']=dica('Superior ao Referêncial','Este indicador é superior ao referêncial comparativo.').'Superior ao referêncial:'.dicaF();
	if (!isset($usou['pratica_indicador_relevante'])) $original['pratica_indicador_relevante']=dica('Relevante','O grau do resultado apresentado por este indicador é importante para o alcance de '.$config['genero_objetivo'].'s ou operacional d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Relevante:'.dicaF();
	if (!isset($usou['pratica_indicador_atendimento'])) $original['pratica_indicador_atendimento']=dica('Atende a Requisitos','O nível do resultado demonstra o atendimento aos principais requisitos relacionados com necessidades e expectativas de partes interessadas.').'Atende a requisitos:'.dicaF();
	if (!isset($usou['pratica_indicador_lider'])) $original['pratica_indicador_lider']=dica('Liderança','O nível do resultado deste indicador demonstra '.$config['genero_organizacao'].' '.$config['organizacao'].' ser líder do mercado ou do setor de atuação.').'Liderança:'.dicaF();
	if (!isset($usou['pratica_indicador_excelencia'])) $original['pratica_indicador_excelencia']=dica('Referência de Excelência','O nível do resultado deste indicador demonstra ser referencial de excelência.').'Referência de excelência:'.dicaF();
	if (!isset($usou['pratica_indicador_estrategico'])) $original['pratica_indicador_estrategico']=dica('Estratégico','Este indicador é estrátégico.').'Estratégico:'.dicaF();
	foreach($original as $chave => $valor) $objResposta->assign('legenda_'.$chave,"innerHTML", utf8_encode($valor));
	return $objResposta;
	}

$xajax->registerFunction("mudar_pauta");	

function marcar_evidencia($pratica_indicador_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	if (!$marcado){
		$sql->setExcluir('pratica_indicador_evidencia');
		if ($uuid) $sql->adOnde('pratica_indicador_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_evidencia_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_evidencia_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que nso ira marcar duas vezes
		$sql->adTabela('pratica_indicador_evidencia');
		$sql->adCampo('count(pratica_indicador_evidencia_id)');
		if ($uuid) $sql->adOnde('pratica_indicador_evidencia_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_evidencia_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_evidencia_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_evidencia_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_indicador_evidencia');
			if ($uuid) $sql->adInserir('uuid', $uuid);
			else $sql->adInserir('pratica_indicador_evidencia_indicador', (int)$pratica_indicador_id);
			$sql->adInserir('pratica_indicador_evidencia_ano', (int)$ano);
			$sql->adInserir('pratica_indicador_evidencia_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_evidencia");	


function marcar_complemento($pratica_indicador_id=0, $uuid='', $marcador_id=0, $marcado=false, $ano=0){
	$sql = new BDConsulta;
	if (!$marcado){
		$sql->setExcluir('pratica_indicador_complemento');
		if ($uuid) $sql->adOnde('pratica_indicador_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_complemento_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_complemento_marcador = '.(int)$marcador_id);
		$sql->exec();
		$sql->limpar();
		}
	else{
		//garantir que nso ira marcar duas vezes
		$sql->adTabela('pratica_indicador_complemento');
		$sql->adCampo('count(pratica_indicador_complemento_id)');
		if ($uuid) $sql->adOnde('pratica_indicador_complemento_uuid = \''.$uuid.'\'');
		else $sql->adOnde('pratica_indicador_complemento_indicador = '.(int)$pratica_indicador_id);
		$sql->adOnde('pratica_indicador_complemento_ano = '.(int)$ano);
		$sql->adOnde('pratica_indicador_complemento_marcador = '.(int)$marcador_id);
		$existe=$sql->Resultado();
		$sql->limpar();
	
		if (!$existe){
			$sql->adTabela('pratica_indicador_complemento');
			if ($uuid) $sql->adInserir('uuid', $uuid);
			else $sql->adInserir('pratica_indicador_complemento_indicador', (int)$pratica_indicador_id);
			$sql->adInserir('pratica_indicador_complemento_ano', (int)$ano);
			$sql->adInserir('pratica_indicador_complemento_marcador', (int)$marcador_id);
			$sql->exec();
			$sql->limpar();
			}
		}
	}
$xajax->registerFunction("marcar_complemento");	



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