<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();


//$xajax->setFlag('debug',true);
//$xajax->setFlag('outputEntities',true);
	
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
	
	/*
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
	$sql->adCampo('chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');
	*/	
	
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
	$sql->adCampo('chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo = 1');	
	
	if ($grupo_id != -1) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
	else $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));

	$sql->adGrupo('usuarios.usuario_id, chave_publica_id, contato_posto, contato_nomeguerra, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');

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
	$sql->esqUnir('chaves_publicas','chaves_publicas','chave_publica_usuario=usuarios.usuario_id');
	$sql->adCampo('chave_publica_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	$sql->adOnde('usuariogrupo.grupo_id IN ('.implode(',',$grupos).')'.(!$tem_protegido ? ' OR contato_cia='.(int)$Aplic->usuario_cia : ''));

	//EUZ Postgrs UPPER() e grupo
	if ($pesquisa) $sql->adOnde('UPPER( contato_nomeguerra ) LIKE \'%'.strtoupper($pesquisa).'%\' OR UPPER( contato_nomecompleto ) LIKE \'%'.strtoupper($pesquisa).'%\' OR UPPER( contato_funcao ) LIKE \'%'.strtoupper($pesquisa).'%\'');	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$sql->adGrupo('usuarios.usuario_id, chaves_publicas.chave_publica_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor, cias.cia_nome');
	//EUZ

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
$xajax->registerFunction("mudar_usuario_pesquisa_ajax");
$xajax->processRequest();

?>