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
	
if ($Aplic->profissional) include_once BASE_DIR.'/modulos/projetos/editar_ajax_pro.php';

function exibir_contatos($contatos){
	global $config;
	$contatos_selecionados=explode(',', $contatos);
	$saida_contatos='';
	if (count($contatos_selecionados)) {
			$saida_contatos.= '<table cellpadding=0 cellspacing=0>';
			$saida_contatos.= '<tr><td class="texto" style="width:400px;">'.link_contato($contatos_selecionados[0],'','','esquerda');
			$qnt_lista_contatos=count($contatos_selecionados);
			if ($qnt_lista_contatos > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_contatos; $i < $i_cmp; $i++) $lista.=link_contato($contatos_selecionados[$i],'','','esquerda').'<br>';		
					$saida_contatos.= dica('Outr'.$config['genero_contato'].'s '.ucfirst($config['contatos']), 'Clique para visualizar '.$config['genero_contato'].'s demais '.strtolower($config['contatos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_contatos\');">(+'.($qnt_lista_contatos - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_contatos"><br>'.$lista.'</span>';
					}
			$saida_contatos.= '</td></tr></table>';
			} 
	else $saida_contatos.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_contatos',"innerHTML", utf8_encode($saida_contatos));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_contatos");	

function exibir_municipios($municipios){
	global $config;
	$municipios_selecionados=explode(',', $municipios);
	$saida_municipios='';
	if (count($municipios_selecionados)) {
			$saida_municipios.= '<table cellpadding=0 cellspacing=0>';
			$saida_municipios.= '<tr><td class="texto" style="width:400px;">'.link_municipio($municipios_selecionados[0]);
			$qnt_lista_municipios=count($municipios_selecionados);
			if ($qnt_lista_municipios > 1) {		
					$lista='';
					for ($i = 1, $i_cmp = $qnt_lista_municipios; $i < $i_cmp; $i++) $lista.=link_municipio($municipios_selecionados[$i]).'<br>';		
					$saida_municipios.= dica('Outros municípios', 'Clique para visualizar os demais municípios.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_municipios\');">(+'.($qnt_lista_municipios - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_municipios"><br>'.$lista.'</span>';
					}
			$saida_municipios.= '</td></tr></table>';
			} 
	else $saida_municipios.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_municipios',"innerHTML", utf8_encode($saida_municipios));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_municipios");


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
	else 	$saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';	
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_depts',"innerHTML", utf8_encode($saida_depts));
	return $objResposta;				
	}
$xajax->registerFunction("exibir_depts");

function projeto_existe($nome='', $projeto_id=0){
	$nome=previnirXSS(utf8_decode($nome));
	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adCampo('count(projeto_id)');
	$sql->adOnde('projeto_nome = "'.$nome.'"');
	if ($projeto_id) $sql->adOnde('projeto_id != '.(int)$projeto_id);
	$existe=$sql->Resultado();
	$sql->Limpar();
	$objResposta = new xajaxResponse();
	$objResposta->assign("existe_projeto","value", (int)$existe);
	return $objResposta;
	}
	
$xajax->registerFunction("projeto_existe");	

function acao_ajax($social_id=0){
	$sql = new BDConsulta;	
	$lista_acoes=array('' => '');
	$sql->adTabela('social_acao');
	$sql->adCampo('social_acao_id, social_acao_nome');
	$sql->adOnde('social_acao_social='.(int)$social_id);
	$sql->adOrdem('social_acao_nome');
	$lista=$sql->Lista();
	$sql->limpar();
	foreach ($lista as $linha) $lista_acoes[$linha['social_acao_id']]=utf8_encode($linha['social_acao_nome']);
	$saida=selecionaVetor($lista_acoes, 'projeto_social_acao', 'size="1" class="texto" style="width:284px;"');

	$objResposta = new xajaxResponse();
	$objResposta->assign("acao_combo","innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("acao_ajax");		

function selecionar_comunidade_ajax($municipio_id='', $campo='', $posicao='', $script='', $vazio='', $projeto_comunidade=0){
	//$saida=selecionar_comunidade_para_ajax($municipio_id, $campo, $script, $vazio, $projeto_comunidade);
	
	$sql = new BDConsulta;
	$sql->adTabela('social_comunidade');
	$sql->adCampo('social_comunidade_id, social_comunidade_nome');
	$sql->adOrdem('social_comunidade_nome ASC');
	$sql->adOnde('social_comunidade_municipio="'.$municipio_id.'"');
	$comunidades=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$vetor['']=$vazio;
	foreach($comunidades as $linha) $vetor[utf8_encode($linha['social_comunidade_id'])]=utf8_encode($linha['social_comunidade_nome']);
	$saida=selecionaVetor($vetor, $campo, $script);
	
	
	
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("selecionar_comunidade_ajax");		
	
function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}		
$xajax->registerFunction("selecionar_cidades_ajax");	

function mudar_ajax($superior='', $sisvalor_titulo='', $campo='', $posicao, $script, $projeto_id=null){
	$sql = new BDConsulta;	
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo="'.$sisvalor_titulo.'"');
	$sql->adOnde('sisvalor_chave_id_pai="'.$superior.'"');
	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id); 
	else  $sql->adOnde('sisvalor_projeto IS NULL');
	
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
	
function mudar_posicao_envolvido_ajax($ordem, $projeto_contato_id, $direcao, $projeto_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao && $projeto_contato_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_contatos');
		$sql->adOnde('projeto_contato_id != '.$projeto_contato_id);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
		$sql->adOrdem('ordem');
		$membros = $sql->Lista();
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
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('projeto_contatos');
			$sql->adAtualizar('ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_contato_id = '.$projeto_contato_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_contatos');
					$sql->adAtualizar('ordem', $idx);
					$sql->adOnde('projeto_contato_id = '.$acao['projeto_contato_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_contatos');
					$sql->adAtualizar('ordem', $idx + 1);
					$sql->adOnde('projeto_contato_id = '.$acao['projeto_contato_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_envolvidos($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("envolvidos","innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_envolvido_ajax");		

function incluir_envolvido_ajax($projeto_id=0, $uuid='', $contato_id, $envolvimento, $perfil){
	$envolvimento=previnirXSS(utf8_decode($envolvimento));
	$perfil=previnirXSS(utf8_decode($perfil));
	$sql = new BDConsulta;
	//verificar se já existe
	$sql->adTabela('projeto_contatos');
	$sql->adCampo('count(projeto_contato_id) AS soma');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_id ='.$projeto_id);	
	$sql->adOnde('contato_id ='.$contato_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->Limpar();

	if ($ja_existe){
		$sql->adTabela('projeto_contatos');
		$sql->adAtualizar('envolvimento', $envolvimento);	
		$sql->adAtualizar('perfil', $perfil);	
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
		$sql->adOnde('contato_id ='.$contato_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('projeto_contatos');
		$sql->adCampo('count(projeto_contato_id) AS soma');
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('projeto_contatos');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', $soma_total);
		$sql->adInserir('envolvimento', $envolvimento);
		$sql->adInserir('perfil', $perfil);
		$sql->adInserir('contato_id', $contato_id);
		$sql->exec();
		}
	$saida=atualizar_envolvidos($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("envolvidos","innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("incluir_envolvido_ajax");	

function excluir_envolvido_ajax($projeto_contato_id, $projeto_id=0, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_contatos');
	$sql->adOnde('projeto_contato_id='.$projeto_contato_id);
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_id='.$projeto_id);
	$sql->exec();
	$saida=atualizar_envolvidos($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("envolvidos","innerHTML", $saida);
	return $objResposta;
	}
	
$xajax->registerFunction("excluir_envolvido_ajax");	

function atualizar_envolvidos($projeto_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('projeto_contatos', 'pc');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
	if ($uuid) $sql->adOnde('pc.uuid = \''.$uuid.'\'');
	else $sql->adOnde('pc.projeto_id = '.$projeto_id);
	$sql->adCampo('cia_nome, projeto_contato_id, contato_funcao, envolvimento, perfil, pc.contato_id, ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
	$sql->adOrdem('ordem');
	$contatos=$sql->ListaChave('contato_id');
	$sql->limpar();
	$saida='';
	if (count($contatos)) {
		$saida.='<table cellspacing=0 cellpadding=0 class="tbl1" align=left>';
		$saida.= '<tr><th></th><th>Nome</th><th>'.utf8_encode($config['organizacao']).'</th><th>'.utf8_encode('Função').'</th><th>'.utf8_encode('Relevância').'</th><th>'.utf8_encode('Característica/Perfil').'</th><th></th></tr>';
		foreach ($contatos as $contato_id => $contato_data) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_envolvido('.$contato_data['ordem'].', '.$contato_data['projeto_contato_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left" nowrap="nowrap">'.utf8_encode($contato_data['nome_contato']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['cia_nome']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['contato_funcao']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['envolvimento']).'</td>';
			$saida.= '<td align="left">'.utf8_encode($contato_data['perfil']).'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_envolvido('.$contato_data['projeto_contato_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este envolvido?\')) {excluir_envolvido('.$contato_data['projeto_contato_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}

function mudar_posicao_integrante_ajax($ordem, $projeto_integrantes_id, $direcao, $projeto_id=0, $uuid=''){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$projeto_integrantes_id) {
		$novo_ui_ordem = $ordem;
		$sql->adTabela('projeto_integrantes');
		$sql->adOnde('projeto_integrantes_id != '.$projeto_integrantes_id);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
		$sql->adOrdem('ordem');
		$membros = $sql->Lista();
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
			$novo_ui_ordem = count($membros) + 1;
			}
		if ($novo_ui_ordem && ($novo_ui_ordem <= count($membros) + 1)) {
			$sql->adTabela('projeto_integrantes');
			$sql->adAtualizar('ordem', $novo_ui_ordem);
			$sql->adOnde('projeto_integrantes_id = '.$projeto_integrantes_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_ordem) {
					$sql->adTabela('projeto_integrantes');
					$sql->adAtualizar('ordem', $idx);
					$sql->adOnde('projeto_integrantes_id = '.$acao['projeto_integrantes_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('projeto_integrantes');
					$sql->adAtualizar('ordem', $idx + 1);
					$sql->adOnde('projeto_integrantes_id = '.$acao['projeto_integrantes_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_integrantes($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("integrantes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
	
$xajax->registerFunction("mudar_posicao_integrante_ajax");	

function incluir_integrante_ajax($projeto_id=0, $uuid='', $contato_id, $projeto_integrante_competencia, $projeto_integrante_atributo='', $projeto_integrantes_situacao='', $projeto_integrantes_necessidade=''){
	$sql = new BDConsulta;
	$projeto_integrante_competencia=previnirXSS(utf8_decode($projeto_integrante_competencia));
	$projeto_integrante_atributo=previnirXSS(utf8_decode($projeto_integrante_atributo));
	$projeto_integrantes_situacao=previnirXSS(utf8_decode($projeto_integrantes_situacao));
	$projeto_integrantes_necessidade=previnirXSS(utf8_decode($projeto_integrantes_necessidade));
	//verificar se já existe
	$sql->adTabela('projeto_integrantes');
	$sql->adCampo('count(projeto_integrantes_id) AS soma');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('projeto_id ='.(int)$projeto_id);	
	$sql->adOnde('contato_id ='.(int)$contato_id);	
  $ja_existe = (int)$sql->Resultado();
  $sql->Limpar();

	if ($ja_existe){
		$sql->adTabela('projeto_integrantes');
		$sql->adAtualizar('projeto_integrante_competencia', $projeto_integrante_competencia);	
		$sql->adAtualizar('projeto_integrante_atributo', $projeto_integrante_atributo);
		$sql->adAtualizar('projeto_integrantes_situacao', $projeto_integrantes_situacao);
		$sql->adAtualizar('projeto_integrantes_necessidade', $projeto_integrantes_necessidade);
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id = '.$projeto_id);
		$sql->adOnde('contato_id ='.$contato_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('projeto_integrantes');
		$sql->adCampo('count(projeto_integrantes_id) AS soma');
		if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
		else $sql->adOnde('projeto_id ='.$projeto_id);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('projeto_integrantes');
		if ($uuid) $sql->adInserir('uuid', $uuid);
		else $sql->adInserir('projeto_id', $projeto_id);
		$sql->adInserir('ordem', $soma_total);
		$sql->adInserir('projeto_integrante_competencia', $projeto_integrante_competencia);
		$sql->adInserir('projeto_integrante_atributo', $projeto_integrante_atributo);
		$sql->adInserir('projeto_integrantes_situacao', $projeto_integrantes_situacao);
		$sql->adInserir('projeto_integrantes_necessidade', $projeto_integrantes_necessidade);
		$sql->adInserir('contato_id', $contato_id);
		$sql->exec();
		}
	$saida=atualizar_integrantes($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("integrantes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}
$xajax->registerFunction("incluir_integrante_ajax");

function excluir_integrante_ajax($projeto_integrantes_id, $projeto_id, $uuid=''){
	$sql = new BDConsulta;
	$sql->setExcluir('projeto_integrantes');
	$sql->adOnde('projeto_integrantes_id='.$projeto_integrantes_id);
	$sql->exec();
	$saida=atualizar_integrantes($projeto_id, $uuid);
	$objResposta = new xajaxResponse();
	$objResposta->assign("integrantes","innerHTML", utf8_encode($saida));
	return $objResposta;
	}

$xajax->registerFunction("excluir_integrante_ajax");	

function atualizar_integrantes($projeto_id=0, $uuid=''){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('projeto_integrantes', 'pc');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = pc.contato_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = c.contato_cia');
	if ($uuid) $sql->adOnde('uuid = \''.$uuid.'\'');
	else $sql->adOnde('pc.projeto_id = '.$projeto_id);
	$sql->adCampo('cia_nome, projeto_integrantes_id, contato_funcao, projeto_integrante_competencia, projeto_integrante_atributo, projeto_integrantes_situacao, projeto_integrantes_necessidade, pc.contato_id, ordem, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_contato');
	$sql->adOrdem('ordem');
	$integrantes=$sql->ListaChave('contato_id');
	$sql->limpar();
	$saida='';
	if (count($integrantes)) {
		$saida.= '<table cellspacing=0 cellpadding=0 class="tbl1" align=left><tr><th></th><th>'.dica('Nome', 'Nome do contato d'.$config['genero_projeto'].' '.$config['projeto'].' que tem envolvimento. No caso de inserção de dados n'.$config['genero_projeto'].' '.$config['projeto'].' poderão ser informados automaticamente por e-mail.').'Nome'.dicaF().'</th><th>'.$config['organizacao'].'</th><th>Função</th><th>Competência</th><th>Atributos</th><th>Situação</th><th>Necessidade</th><th></th></tr>';
		foreach ($integrantes as $contato_id => $integrante) {
			$saida.= '<tr align="center">';
			$saida.= '<td>';
			$saida.= dica('Mover para Primeira Posição', 'Clique neste ícone '.imagem('icones/2setacima.gif').' para mover para a primeira posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Cima', 'Clique neste ícone '.imagem('icones/1setacima.gif').' para mover acima').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para Baixo', 'Clique neste ícone '.imagem('icones/1setabaixo.gif').' para mover abaixo').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= dica('Mover para a Ultima Posição', 'Clique neste ícone '.imagem('icones/2setabaixo.gif').' para mover para a última posição').'<a href="javascript:void(0);" onclick="javascript:mudar_posicao_integrante('.$integrante['ordem'].', '.$integrante['projeto_integrantes_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>'.dicaF();
			$saida.= '</td>';
			$saida.= '<td align="left" nowrap="nowrap">'.$integrante['nome_contato'].'</td>';
			$saida.= '<td align="left">'.$integrante['cia_nome'].'</td>';
			$saida.= '<td align="left">'.$integrante['contato_funcao'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrante_competencia'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrante_atributo'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrantes_situacao'].'</td>';
			$saida.= '<td align="left">'.$integrante['projeto_integrantes_necessidade'].'</td>';
			$saida.= '<td><a href="javascript: void(0);" onclick="editar_integrante('.$integrante['projeto_integrantes_id'].');">'.imagem('icones/editar.gif', 'Editar Integrante', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o contato integrante com '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este integrante?\')) {excluir_integrante('.$integrante['projeto_integrantes_id'].');}">'.imagem('icones/remover.png', 'Excluir Integrante', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o contato integrante com '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table>';
		}
	return $saida;
	}





function editar_integrante($projeto_integrantes_id){
	global $config, $Aplic;
	$objResposta = new xajaxResponse();
	$sql = new BDConsulta;
	$sql->adTabela('projeto_integrantes');
	$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = projeto_integrantes.contato_id');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao, projeto_integrante_competencia, projeto_integrantes.contato_id, projeto_integrante_atributo, projeto_integrantes_situacao, projeto_integrantes_necessidade');
	$sql->adOnde('projeto_integrantes_id = '.(int)$projeto_integrantes_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$nome=$linha['nome'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao'] : '').($linha['cia_nome'] && $Aplic->getPref('om_usuario') ? ' - '.$linha['cia_nome'] : '');
	
	$objResposta->assign("projeto_integrantes_id","value", $projeto_integrantes_id);
	$objResposta->assign("nome_integrante","value", utf8_encode($nome));
	$objResposta->assign("integrante_id","value", $linha['contato_id']);	
	$objResposta->assign("projeto_integrante_atributo","value", utf8_encode($linha['projeto_integrante_atributo']));	
	$objResposta->assign("apoio1","value", utf8_encode($linha['projeto_integrante_atributo']));	
	
	$objResposta->assign("projeto_integrantes_situacao","value", utf8_encode($linha['projeto_integrantes_situacao']));	
	$objResposta->assign("apoio2","value", utf8_encode($linha['projeto_integrantes_situacao']));	
	
	$objResposta->assign("projeto_integrantes_necessidade","value", utf8_encode($linha['projeto_integrantes_necessidade']));	
	$objResposta->assign("apoio3","value", utf8_encode($linha['projeto_integrantes_necessidade']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_integrante");	



function editar_envolvido($projeto_contato_id){
	global $config, $Aplic;

	$sql = new BDConsulta;
	$sql->adTabela('projeto_contatos');
	$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = projeto_contatos.contato_id');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao, envolvimento, perfil, projeto_contatos.contato_id');
	$sql->adOnde('projeto_contato_id = '.(int)$projeto_contato_id);
	$linha=$sql->Linha();
	$sql->limpar();

	$nome=$linha['nome'].($linha['contato_funcao'] ? ' - '.$linha['contato_funcao'] : '').($linha['cia_nome'] && $Aplic->getPref('om_usuario') ? ' - '.$linha['cia_nome'] : '');
	
	$objResposta = new xajaxResponse();
	$objResposta->assign("projeto_contato_id","value", $projeto_contato_id);
	$objResposta->assign("nome_envolvido","value", utf8_encode($nome));
	$objResposta->assign("envolvimento","value", utf8_encode($linha['envolvimento']));
	$objResposta->assign("envolvido_id","value", $linha['contato_id']);	
	$objResposta->assign("perfil","value", utf8_encode($linha['perfil']));	
	$objResposta->assign("apoio1","value", utf8_encode($linha['perfil']));	
	return $objResposta;
	}	
$xajax->registerFunction("editar_envolvido");	



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
		
function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");			


$xajax->processRequest();
?>