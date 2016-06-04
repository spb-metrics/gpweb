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

function mudar_lista_ajax($cia_id=0, $campo, $contato=0, $nome_completo=0, $usuario_id=0, $contato_id=0, $pesquisar='', $estado_sigla='', $municipio_id='', $grupo_id=null, $grupo_id2=null, $exibicao_simples=false, $vetor_cha=array()){
	global $config;
	$sql = new BDConsulta;
	
	
	$contatos_grupos='';
	if ($grupo_id || $grupo_id2){
		$sql->adTabela('usuarios');
		$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('DISTINCT contatos.contato_id');
		$sql->adOnde('usuario_ativo=1');	
		$sql->adOnde('usuariogrupo.grupo_id='.(int)($grupo_id ? $grupo_id : $grupo_id2));
		ver5($sql->comando_sql());
		$contatos_grupos = $sql->carregarColuna();
		$sql->limpar();
		$contatos_grupos=implode(',',$contatos_grupos);
		}
	
	$lista_cha='';
	if (count($vetor_cha)){
		$qnt=0;
		$sql_cha=array();
		foreach($vetor_cha as $vetor_cha_id => $linha){
			if ($vetor_cha_id && $linha[0] && ($linha[1]!=null)) {
				$qnt++;
				$sql_cha[]='(uc'.$qnt.'.usuario_cha_modelo = '.(int)$vetor_cha_id.' AND uc'.$qnt.'.usuario_cha_valor '.$linha[0].' '.$linha[1].')';
				}
			}	
		$sql->adTabela('usuario_cha', 'uc1');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = uc1.usuario_cha_usuario');
		$sql->esqUnir('contatos', 'contatos', 'usuario_contato = contato_id');
		for($i=2; $i <=$qnt ; $i++) $sql->esqUnir('usuario_cha', 'uc'.$i, 'uc'.($i-1).'.usuario_cha_usuario=uc'.$i.'.usuario_cha_usuario');	
		$sql->adCampo('DISTINCT uc1.usuario_cha_usuario');	
		$sql->adOnde('contato_cia	='.(int)$cia_id);
		$sql->adOnde(implode(' AND ', $sql_cha));	
		$lista_cha=$sql->carregarColuna();
		$sql->limpar();
		$lista_cha=implode(',',$lista_cha);
		if (!$lista_cha) $lista_cha='-1';
		}
	$pesquisar=previnirXSS(utf8_decode($pesquisar));
	$saida='<table>';
	$saida.='<tr><td><input type="checkbox" name="usuario[]" id="usuario_0" value="0" onClick="setUsuario(0, \'\',\'\', \'\', \''.$campo.'\', \'\' );" /><label for="usuario_0">Nenhum '.($contato ? 'contato' : $config['usuario']).'</label>';
	if (!$pesquisar  && !$exibicao_simples && !$grupo_id && !$grupo_id2){
		$sql->adTabela('depts');
		$sql->adCampo('dept_nome, dept_id');
		$sql->adOnde('dept_cia = '.(int)$cia_id);
		$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
		$sql->adOrdem('dept_ordem, dept_nome');
		$depts = $sql->ListaChave('dept_id');
		$sql->limpar();
		foreach ((array)$depts as $dept_id => $secao_data){ 
			$saida.='<tr><td><b>'.$secao_data['dept_nome'].'</b></td></tr>';

			$sql->adTabela('contatos', 'a');
			$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
			$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
			$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
			$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
			if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
			if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
			if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
			if(!$contato) $sql->adOnde('usuario_ativo=1');
			if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
			$sql->adOnde('dept_id = '.(int)$dept_id);
			
			$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
			$linhas = $sql->Lista();
			$sql->Limpar();	
			
				
			foreach ((array)$linhas as $linha) {
				if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
				else $contato_cia = $linha['cia_nome'];
				
				if($contato && $linha['usuario_ativo']!=0) $saida.='<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$linha['contato_id'].'" value="'.$linha['contato_id'].'" ' .($linha['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['contato_id'].', \''.$linha['contato_posto'].'\',\''.($nome_completo && $linha['contato_nomecompleto'] ? $linha['contato_nomecompleto']: $linha['contato_nomeguerra']).'\', \''.$linha['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).'</label></td></tr>';
				elseif($linha['usuario_ativo']!=0) $saida.='<tr><td>&nbsp;&nbsp;&nbsp;<input type="checkbox" name="usuario[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" ' .($linha['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['usuario_id'].', \''.$linha['contato_posto'].'\',\''.($nome_completo && $linha['contato_nomecompleto'] ? $linha['contato_nomecompleto']: $linha['contato_nomeguerra']).'\', \''.$linha['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).'</label></td></tr>';
				}
			$saida.=subniveis_ajax($dept_id, '&nbsp;&nbsp;&nbsp;', $estado_sigla, $municipio_id, $contato, $lista_cha, $pesquisar);
			}
		
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
		if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
		if ($cia_id) $sql->adOnde('contato_dept = 0 OR contato_dept IS NULL');
		if ($cia_id) $sql->adOnde('contato_cia = '.(int)$cia_id);
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if(!$contato) $sql->adOnde('usuario_ativo=1');
		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->Lista();
		$sql->Limpar();	
			
		if (count($usuarios)){
			if ($cia_id) $saida.='<tr><td><b>Em '.($config['genero_dept']=='o' ? 'nenhum ': 'nenhuma ').' '.strtolower($config['departamento']).'</b></td></tr>';
			foreach ($usuarios as $usuario) {
				if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
				else $contato_cia = $usuario['cia_nome'];
				
				if($contato && $usuario['usuario_ativo']!=0) $saida.='<tr><td>'.($cia_id ? '&nbsp;&nbsp;&nbsp;' : '').'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" ' .($usuario['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['contato_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
				elseif($usuario['usuario_ativo']!=0) $saida.='<tr><td>'.($cia_id ? '&nbsp;&nbsp;&nbsp;' : '').'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" ' .($usuario['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['usuario_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
				}
			}
		}	
	else{
	//com filtro
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
		if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
		if ($cia_id) $sql->adOnde('contato_cia = '.(int)$cia_id);
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if(!$contato)$sql->adOnde('usuario_ativo=1');
		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
		if ($contatos_grupos) $sql->adOnde('contato_id IN ('.$contatos_grupos.')');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$linhas = $sql->Lista();
		$sql->Limpar();	
	
		foreach ($linhas as $linha) {
			if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
			else $contato_cia = $linha['cia_nome'];
			if($contato) $saida.='<tr><td><input type="checkbox" name="usuario[]" id="usuario_'.$linha['contato_id'].'" value="'.$linha['contato_id'].'" ' .($linha['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['contato_id'].', \''.$linha['contato_posto'].'\',\''.($nome_completo && $linha['contato_nomecompleto'] ? $linha['contato_nomecompleto']: $linha['contato_nomeguerra']).'\', \''.$linha['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).($linha['dept_nome'] ? ' - '.$linha['dept_nome'] : '').($contatos_grupos ? ' - '.$linha['cia_nome'] : '').'</label></td></tr>';
			else $saida.='<tr><td><input type="checkbox" name="usuario[]" id="usuario_'.$linha['usuario_id'].'" value="'.$linha['usuario_id'].'" ' .($linha['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$linha['usuario_id'].', \''.$linha['contato_posto'].'\',\''.($nome_completo && $linha['contato_nomecompleto'] ? $linha['contato_nomecompleto']: $linha['contato_nomeguerra']).'\', \''.$linha['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$linha['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']).($linha['dept_nome'] ? ' - '.$linha['dept_nome'] : '').($contatos_grupos ? ' - '.$linha['cia_nome'] : '').'</label></td></tr>';
			}
		}	
	$saida.='</table>';		
	$saida=utf8_encode($saida);
	$objResposta = new xajaxResponse();
	$objResposta->assign('lista_usuarios',"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("mudar_lista_ajax");	


function subniveis_ajax($dept_id, $subnivel, $estado_sigla, $municipio_id, $contato=0, $lista_cha='', $pesquisar=''){
	global $config, $departamento, $chamarVolta, $contato_id, $usuario_id, $nome_completo, $campo;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id, dept_nome');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOrdem('dept_ordem, dept_nome');
	$subordinados = $sql->lista();
	$sql->limpar();
	
	$saida='';
	
	foreach((array)$subordinados as $linha){
		$saida.='<tr><td>'.$subnivel.'<b>'.$linha['dept_nome'].'</b></td></tr>';
		
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = a.contato_id');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('DISTINCT contato_id, usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_cia, cia_nome, usuario_grupo_dept, dept_nome, usuario_ativo');
		if ($pesquisar) $sql->adOnde('contato_posto like \'%'.$pesquisar.'%\' OR contato_nomeguerra  like \'%'.$pesquisar.'%\' OR concatenar_tres(contato_posto, \' \', contato_nomeguerra)  like \'%'.$pesquisar.'%\' OR cia_nome like \'%'.$pesquisar.'%\' OR contato_notas  like \'%'.$pesquisar.'%\' OR contato_email like \'%'.$pesquisar.'%\'');
		if ($estado_sigla) $sql->adOnde('contato_estado="'.$estado_sigla.'"');
		if ($municipio_id) $sql->adOnde('contato_cidade="'.$municipio_id.'"');
		if(!$contato)$sql->adOnde('usuario_ativo=1');
		if ($lista_cha) $sql->adOnde('usuarios.usuario_id IN ('.$lista_cha.')');
		
		$sql->adOnde('dept_id = '.(int)$linha['dept_id']);
		
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->Lista();
		$sql->Limpar();	
		
		foreach ((array)$usuarios as $usuario) {
			if (!$usuario['cia_nome']) $contato_cia = $usuario['contato_cia'];
			else $contato_cia = $usuario['cia_nome'];
			
			if($contato) $saida.='<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['contato_id'].'" value="'.$usuario['contato_id'].'" ' .($usuario['contato_id']==$contato_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['contato_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['contato_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			else $saida.='<tr><td>&nbsp;&nbsp;&nbsp;'.$subnivel.'<input type="checkbox" name="usuario[]" id="usuario_'.$usuario['usuario_id'].'" value="'.$usuario['usuario_id'].'" ' .($usuario['usuario_id']==$usuario_id ? 'checked="checked"' :''). ' onClick="setUsuario('.$usuario['usuario_id'].', \''.$usuario['contato_posto'].'\',\''.($nome_completo && $usuario['contato_nomecompleto'] ? $usuario['contato_nomecompleto']: $usuario['contato_nomeguerra']).'\', \''.$usuario['contato_funcao'].'\', \''.$campo.'\', \''.$contato_cia.'\' );" /><label for="usuario_'.$usuario['usuario_id'].'">'.nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']).'</label></td></tr>';
			}

		$saida.=subniveis_ajax($linha['dept_id'], $subnivel.'&nbsp;&nbsp;&nbsp;', $estado_sigla, $municipio_id, $contato, $lista_cha, $pesquisar);
		}
	return $saida;
	}


function selecionar_om_ajax($cia_id=1, $campo, $posicao, $script,  $vazio='', $acesso=0, $externo=0 ){
	$saida=selecionar_om_para_ajax($cia_id, $campo, $script,  $vazio, $acesso, $externo);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}
$xajax->registerFunction("selecionar_om_ajax");

function selecionar_cidades_ajax($estado_sigla='', $campo, $posicao, $script, $cidade=''){
	$saida=selecionar_cidades_para_ajax($estado_sigla, $campo, $script, '', $cidade, true);
	$objResposta = new xajaxResponse();
	$objResposta->assign($posicao,"innerHTML", $saida);
	return $objResposta;
	}		
	
$xajax->registerFunction("selecionar_cidades_ajax");		


$xajax->processRequest();

?>