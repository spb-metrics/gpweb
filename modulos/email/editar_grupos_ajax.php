<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
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


function mudar_posicao_grupo_ajax($grupo_ordem, $grupo_id, $direcao, $grupo_usuario=0){
	//ordenar membro da equipe
	$sql = new BDConsulta;
	if($direcao&&$grupo_id) {
		$novo_ui_grupo_ordem = $grupo_ordem;
		$sql->adTabela('grupo');
		$sql->adOnde('grupo_id != '.$grupo_id);
		$sql->adOnde('grupo_usuario = '.$grupo_usuario);
		$sql->adOrdem('grupo_ordem');
		$membros = $sql->Lista();
		$sql->limpar();
		
		if ($direcao == 'moverParaCima') {
			$outro_novo = $novo_ui_grupo_ordem;
			$novo_ui_grupo_ordem--;
			} 
		elseif ($direcao == 'moverParaBaixo') {
			$outro_novo = $novo_ui_grupo_ordem;
			$novo_ui_grupo_ordem++;
			} 
		elseif ($direcao == 'moverPrimeiro') {
			$outro_novo = $novo_ui_grupo_ordem;
			$novo_ui_grupo_ordem = 1;
			} 
		elseif ($direcao == 'moverUltimo') {
			$outro_novo = $novo_ui_grupo_ordem;
			$novo_ui_grupo_ordem = count($membros) + 1;
			}
		if ($novo_ui_grupo_ordem && ($novo_ui_grupo_ordem <= count($membros) + 1)) {
			$sql->adTabela('grupo');
			$sql->adAtualizar('grupo_ordem', $novo_ui_grupo_ordem);
			$sql->adOnde('grupo_id = '.$grupo_id);
			$sql->exec();
			$sql->limpar();
			$idx = 1;
			foreach ($membros as $acao) {
				if ((int)$idx != (int)$novo_ui_grupo_ordem) {
					$sql->adTabela('grupo');
					$sql->adAtualizar('grupo_ordem', $idx);
					$sql->adOnde('grupo_id = '.$acao['grupo_id']);
					$sql->exec();
					$sql->limpar();
					$idx++;
					} 
				else {
					$sql->adTabela('grupo');
					$sql->adAtualizar('grupo_ordem', $idx + 1);
					$sql->adOnde('grupo_id = '.$acao['grupo_id']);
					$sql->exec();
					$sql->limpar();
					$idx = $idx + 2;
					}
				}		
			}
		}
	
	$saida=atualizar_grupos($grupo_usuario);
	$objResposta = new xajaxResponse();
	$objResposta->assign("grupos","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_posicao_grupo_ajax");		
	

function incluir_grupo_ajax($grupo_usuario=0, $grupo_id=0, $grupo_descricao='', $usuarios=''){
	global $bd;
	
	$sql = new BDConsulta;
	$grupo_descricao=previnirXSS(utf8_decode($grupo_descricao));
	$usuarios=explode(',', $usuarios);

	if ($grupo_id){
		$sql->adTabela('grupo');
		$sql->adAtualizar('grupo_descricao', $grupo_descricao);
		$sql->adOnde('grupo_id ='.(int)$grupo_id);
		$sql->exec();
	  $sql->Limpar();
		}
	else {	
		$sql->adTabela('grupo');
		$sql->adCampo('count(grupo_id) AS soma');
		$sql->adOnde('grupo_usuario ='.(int)$grupo_usuario);	
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  
		$sql->adTabela('grupo');
		$sql->adInserir('grupo_usuario', (int)$grupo_usuario);
		$sql->adInserir('grupo_ordem', (int)$soma_total);
		$sql->adInserir('grupo_descricao', $grupo_descricao);
		$sql->exec();
		$grupo_id=$bd->Insert_ID('grupo','grupo_id');
		$sql->Limpar();
		}
		
	$sql->setExcluir('usuariogrupo');
	$sql->adOnde('grupo_id='.(int)$grupo_id);
	$sql->exec();	
	
	foreach($usuarios As $usuario_id) {
		$sql->adTabela('usuariogrupo');
		$sql->adInserir('usuario_id', (int)$usuario_id);
		$sql->adInserir('grupo_id', $grupo_id);
		$sql->exec();
		$sql->Limpar();
		}
		
		
	$saida=atualizar_grupos($grupo_usuario);
	$objResposta = new xajaxResponse();
	$objResposta->assign("grupos","innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("incluir_grupo_ajax");	


function excluir_grupo_ajax($grupo_id, $grupo_usuario){
	$sql = new BDConsulta;
	$sql->setExcluir('grupo');
	$sql->adOnde('grupo_id='.$grupo_id);
	$sql->exec();
	$saida=atualizar_grupos($grupo_usuario);
	$objResposta = new xajaxResponse();
	$objResposta->assign("grupos","innerHTML", $saida);
	return $objResposta;
	}

$xajax->registerFunction("excluir_grupo_ajax");	


function atualizar_grupos($grupo_usuario){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('grupo');
	$sql->adCampo('grupo.*');
	$sql->adOnde('grupo_usuario = '.(int)$grupo_usuario);
	$sql->adOrdem('grupo_ordem');
	$grupos=$sql->ListaChave('grupo_id');
	$sql->limpar();
	$saida='';
	if (count($grupos)) {
		$saida.= '<table cellpadding=0 cellspacing=0 class="tbl1" align=left width=250><tr><th></th><th>Nome</th><th></th></tr>';
		foreach ($grupos as $grupo_id => $linha) {
			$saida.= '<tr align="center">';
			$saida.= '<td nowrap="nowrap" width="40" align="center">';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverPrimeiro\');"><img src="'.acharImagem('icones/2setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverParaCima\');"><img src="'.acharImagem('icones/1setacima.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverParaBaixo\');"><img src="'.acharImagem('icones/1setabaixo.gif').'" border=0/></a>';
			$saida.= '<a href="javascript:void(0);" onclick="javascript:mudar_posicao_grupo('.$linha['grupo_ordem'].', '.$linha['grupo_id'].', \'moverUltimo\');"><img src="'.acharImagem('icones/2setabaixo.gif').'" border=0/></a>';
			$saida.= '</td>';
			$saida.= '<td align="left">'.utf8_encode($linha['grupo_descricao']).'</td>';
			$saida.= '<td width=32><a href="javascript: void(0);" onclick="editar_grupo('.$linha['grupo_id'].');">'.imagem('icones/editar.gif').'</a>';
			$saida.= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este grupo?\')) {excluir_grupo('.$linha['grupo_id'].');}">'.imagem('icones/remover.png').'</a></td>';
			$saida.= '</tr>';
			}
		$saida.= '</table></td></tr></table>';
		}
	return $saida;
	}	

$xajax->registerFunction("atualizar_grupos");		
	
function editar_grupo($grupo_id){
	global $config, $Aplic;
	
	$objResposta = new xajaxResponse();
	
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
	$sql->adCampo('MAX(chave_publica_id) AS chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	$sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$sql->adGrupo('usuarios.usuario_id, contato_posto, contato_nomeguerra, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$usuarios = $sql->Lista();
	$sql->limpar();

	$saida='<select name="ListaPARA[]" id="ListaPARA" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover2(env.ListaPARA, env.ListaDE); return false;">';
 	foreach ($usuarios as $rs) {
 		$nome=nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '');
 		$saida.='<option value="'.$rs['usuario_id'].'">'.$nome.'</option>';
		}
	$saida.='</select>';
	
	$sql->adTabela('grupo');
	$sql->adCampo('grupo_descricao');
	$sql->adOnde('grupo_id = '.(int)$grupo_id);
	$sql->adOrdem('grupo_ordem');
	$linha=$sql->Linha();
	$sql->limpar();

	
	$objResposta->assign('combo_para',"innerHTML", utf8_encode($saida));
	$objResposta->assign("grupo_id","value", $grupo_id);
	$objResposta->assign("grupo_descricao","value", utf8_encode($linha['grupo_descricao']));	
	return $objResposta;
	}	

$xajax->registerFunction("editar_grupo");	
















function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script, $acesso=0){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script, $acesso);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

function mudar_usuario_ajax($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao=''){
	global $Aplic;
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$saida=mudar_usuario_em_dept(true, $cia_id, 0, $campo, $posicao, $script);
	$objResposta = New xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}	
$xajax->registerFunction("mudar_usuario_ajax");	


	
function mudar_usuario_grupo_ajax($grupo_id=0){
	global $Aplic, $config;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
	$sql->adCampo('MAX(chave_publica_id) AS chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	if ($grupo_id != -1) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
	else $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$sql->adGrupo('usuarios.usuario_id, contato_posto, contato_nomeguerra, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) {
 		$nome=nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '');
 		$saida.='<option value="'.$rs['usuario_id'].'" style="color: '.($rs['chave_publica_id']? 'blue': 'black').';">'.utf8_encode($nome).'</option>';
		}
	$saida.='</select>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
	
$xajax->registerFunction("mudar_usuario_grupo_ajax");


function mudar_usuario_pesquisa_ajax($pesquisa=''){
	global $Aplic, $config;
	$sql = new BDConsulta;
	
	$pesquisa=previnirXSS(utf8_decode($pesquisa));
	
	$sql->adTabela('grupo');
	$sql->esqUnir('grupo_permissao','gp1','gp1.grupo_id = grupo.grupo_id');
	$sql->esqUnir('grupo_permissao','gp2','gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id = '.$Aplic->usuario_id);
	$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, grupo_cia');
	$sql->adCampo('COUNT(gp1.usuario_id) AS protegido');
	$sql->adCampo('COUNT(gp2.usuario_id) AS pertence');
	$sql->adOnde('grupo_usuario IS NULL');
	$sql->adOnde('grupo_cia IS NULL OR grupo_cia='.(int)$Aplic->usuario_cia);
	$sql->adOrdem('grupo_descricao ASC');
	$sql->adGrupo('grupo.grupo_id, grupo_descricao, grupo_cia');
	$achados=$sql->Lista();
	$sql->limpar();
	
	$grupos=array();
	$tem_protegido=0;
	foreach($achados as $linha) {
		if ($linha['protegido']) $tem_protegido=1;
		if ($linha['grupo_id'] && (!$linha['protegido'] || ($linha['protegido'] && $linha['pertence'])))$grupos[$linha['grupo_id']]=$linha['grupo_id'];
		}
	
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario = usuarios.usuario_id');
	$sql->adCampo('MAX( chave_publica_id ) AS chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	if($grupos) $sql->adOnde('usuariogrupo.grupo_id IN ('.implode(',',$grupos).')'.(!$tem_protegido ? ' OR contato_cia='.(int)$Aplic->usuario_cia : ''));
	else if(!$tem_protegido) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);

  if ($pesquisa) $sql->adOnde('UPPER( contato_nomeguerra ) LIKE \'%'.strtoupper($pesquisa).'%\' OR UPPER( contato_nomecompleto ) LIKE \'%'.strtoupper($pesquisa).'%\' OR UPPER( contato_funcao ) LIKE \'%'.strtoupper($pesquisa).'%\'');	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$sql->adGrupo('contato_posto, contato_nomeguerra, nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');

	$usuarios = $sql->Lista();
	$sql->limpar();
	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(env.ListaDE, env.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) {
 		$nome=nome_funcao('',$rs['nome_usuario'], $rs['contato_funcao']).($Aplic->getPref('om_usuario') && $rs['cia_nome'] ? ' - '.$rs['cia_nome']: '');
 		$saida.='<option value="'.$rs['usuario_id'].'" style="color: '.($rs['chave_publica_id']? 'blue': 'black').';">'.utf8_encode($nome).'</option>';
		}
	
	$saida.='</select>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	


$xajax->registerFunction("mudar_usuario_pesquisa_ajax");
$xajax->processRequest();
?>