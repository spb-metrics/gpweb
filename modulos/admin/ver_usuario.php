<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


global $adPcT, $cia_id, $dept_ids, $secao, $ver_min, $m, $a, $podeAcessar;

$usuario_id = isset($_REQUEST['usuario_id']) ? getParam($_REQUEST, 'usuario_id', 0) : 0;

//feito para resolver bug no menu geral
if (!$usuario_id) $usuario_id = isset($_REQUEST['filtro_id_responsavel']) ? getParam($_REQUEST, 'filtro_id_responsavel', 0) : 0;

if (!($usuario_id==$Aplic->usuario_id || $podeAcessar || $Aplic->usuario_super_admin || $Aplic->usuario_admin)){
	$Aplic->redirecionar('m=publico&a=acesso_negado'); 
	exit();
	}

if (!$dialogo) $Aplic->salvarPosicao();

if (isset($_REQUEST['mostrar_form'])) {
	$adicionar_pct = getParam($_REQUEST, 'adProjComTarefas', 0);
	$Aplic->setEstado('adicionarProjComTarefas', $adicionar_pct);
	} 
else 	$Aplic->setEstado('adicionarProjComTarefas', false);
$adPcT = $Aplic->getEstado('adicionarProjComTarefas') ? $Aplic->getEstado('adicionarProjComTarefas') : 0;
$cia_id = $Aplic->getEstado('Usrcia_id') !== null ? $Aplic->getEstado('Usrcia_id') : $Aplic->usuario_cia;
$cia_prefixo = 'cia_';
if (isset($_REQUEST['secao'])) {
	$Aplic->setEstado('UsrProjIdxDept', getParam($_REQUEST, 'secao', null));
	unset($cia_id);
	}
$secao = $Aplic->getEstado('UsrProjIdxDept') !== null ? $Aplic->getEstado('UsrProjIdxDept') : $cia_prefixo.$Aplic->usuario_cia;
if (!(strpos($secao, $cia_prefixo) === false)) {
	$cia_id = substr($secao, strlen($cia_prefixo));
	$Aplic->setEstado('Usrcia_id', $cia_id);
	unset($secao);
	}
if (isset($_REQUEST['tab'])) $Aplic->setEstado('UsuarioVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('UsuarioVerTab') !== null ? $Aplic->getEstado('UsuarioVerTab') : 0;
$sql = new BDConsulta;
$sql->adTabela('usuarios', 'u');
$sql->adCampo('u.*');
$sql->adCampo('con.*, cia_id, cia_nome, dept_nome, contato_id, dept_id');
$sql->esqUnir('contatos', 'con', 'usuario_contato = contato_id');
$sql->esqUnir('cias', 'com', 'contato_cia = cia_id');
$sql->esqUnir('depts', 'dep', 'dept_id = contato_dept');
$sql->adOnde('u.usuario_id = '.(int)$usuario_id);
$usuario = $sql->Linha();
$sql->limpar();

if (!$usuario) {
	$botoesTitulo = new CBlocoTitulo('ID d'.$config['genero_usuario'].' '.$config['usuario'].' inválida', 'usuario.png', $m, "$m.$a");
	$botoesTitulo->adicionaBotao('m=admin', 'lista de '.$config['usuarios'],'','Lista de '.ucfirst($config['usuarios']),'Visualizar a lista de '.$config['usuarios'].' do Sistema.');
	$botoesTitulo->mostrar();
	} 
else {
	$paises = getPais('Paises');
	
	if (!$dialogo && !$Aplic->profissional){
		$botoesTitulo = new CBlocoTitulo('Detalhes do '.ucfirst($config['usuario']), 'usuario.png', $m, "$m.$a");
		if ($podeAcessar) $botoesTitulo->adicionaBotao('m=admin', 'lista de '.$config['usuarios'],'','Lista de '.ucfirst($config['usuarios']),'Visualizar a lista de '.$config['usuarios'].' do Sistema.');
		if ($podeEditar || $usuario_id == $Aplic->usuario_id || $Aplic->usuario_admin) {
			$botoesTitulo->adicionaBotao('m=admin&a=editar_usuario&usuario_id='.$usuario_id, 'editar cadastro','','Editar Cadastro','Editar as informações cadastradas d'.$config['genero_usuario'].' '.$config['usuario'].'.');
			$botoesTitulo->adicionaBotao('m=sistema&a=editarpref&usuario_id='.$usuario_id,'editar preferências','','Editar Preferências','Editar as preferências com'.$config['genero_usuario'].' '.$config['usuario'].'.');
			if (!(isset($config['restrito']) && $config['restrito'])) $botoesTitulo->adicionaBotaoDireita(dica('Mudar a Senha', 'Mudar a senha de entrada no Sistema.').'<a style="float:right;" href="javascript: void(0);" class="botao" onclick="popMudarSenha();return false"><span>mudar a senha</span></a>'.dicaF());
			}
		if ($podeEditar || $Aplic->usuario_admin) $botoesTitulo->adicionaBotaoDireita(dica('Adicionar '.ucfirst($config['usuario']), 'Adicionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' no Sistema.').'<a style="float:right;" href="javascript: void(0);" class="botao" onclick="url_passar(0, \'m=admin&a=editar_usuario\');"><span>adicionar '.$config['usuario'].'</span></a>'.dicaF());
		$botoesTitulo->mostrar();
		echo estiloTopoCaixa();
		}
	
	
	if (!$dialogo && $Aplic->profissional){	
		$botoesTitulo = new CBlocoTitulo('Detalhes do '.ucfirst($config['usuario']), 'usuario.png', $m, $m.'.'.$a);
		$botoesTitulo->mostrar();
		echo estiloTopoCaixa();
		echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
		echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
		require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
		$km = new CoolMenu("km");
		$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
		$km->styleFolder="default";
		$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
		if ($podeAcessar) $km->Add("ver","ver_lista",dica('Lista de '.ucfirst($config['usuarios']),'Clique neste botão para visualizar a lista de '.$config['usuarios'].'.').'Lista de '.ucfirst($config['usuarios']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin\");");
		
		$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
		if ($podeEditar || $usuario_id == $Aplic->usuario_id || $Aplic->usuario_admin) {
			$km->Add("acao","acao_editar",dica('Editar Cadastro','Editar as informações cadastradas d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Editar Cadastro'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=admin&a=editar_usuario&usuario_id=".$usuario_id."\");");
			$km->Add("acao","acao_preferencia",dica('Editar Preferências','Editar as preferências com'.$config['genero_usuario'].' '.$config['usuario'].'.').'Editar Preferências'.dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=sistema&a=editarpref&usuario_id=".$usuario_id."\");");
			if (!(isset($config['restrito']) && $config['restrito'])) $km->Add("acao","acao_senhar",dica('Mudar a Senha', 'Mudar a senha de entrada no Sistema.').'Mudar a Senha'.dicaF(), "javascript: void(0);' onclick='popMudarSenha();return false");
			$km->Add("acao","acao_cha",dica('Editar Conhecimentos, Habilidades e Atitudes do '.ucfirst($config['usuario']), 'Adicionar conhecimentos, habilidades e atitudes d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Editar Conhecimentos, Habilidades e Atitudes'.dicaF(), "javascript: void(0);'  onclick='url_passar(0, \"m=admin&a=usuario_cha_pro&usuario_id=".$usuario_id."\");");
			$km->Add("acao","acao_adicionar",dica('Adicionar '.ucfirst($config['usuario']), 'Adicionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].' no Sistema.').'Adicionar '.ucfirst($config['usuario']).dicaF(), "javascript: void(0);'  onclick='url_passar(0, \"m=admin&a=editar_usuario\");");
			$km->Add("acao","acao_imprimir",dica('Imprimir', 'Para imprimir o cadastro d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Imprimir Cadastro'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&usuario_id=".$usuario_id."\");");
			}
		echo $km->Render();
		echo '</td></tr></table>';
		}

	
	echo '<table cellpadding=0 cellspacing=1 '.(!$dialogo ? 'width="100%" class="std"' : 'width=750').' >';
	
	if ($dialogo) echo '<tr><td colspan=2 style="font-weight: bold; font-size: 150%">Detalhes do '.ucfirst($config['usuario']).'<br>&nbsp;</td></tr>';
	
		if ($usuario['usuario_grupo_dept']){
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Conta de Grupo', 'Este '.$config['usuario'].' é uma conta de grupo.').'Conta de grupo:'.dicaF().'</td><td class="realce" width="100%">Sim</td></tr>';
		
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome', 'Nome do grupo.').'Nome:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['contato_nomeguerra'].'</td></tr>';
		if ($usuario['contato_nomecompleto']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome Completo', 'Nome completo do grupo.').'Nome completo:'.dicaF().'</td><td class="realce" >'.$usuario['contato_nomecompleto'].'</td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Login', 'Login do grupo.').'Login:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['usuario_login'].'</td></tr>';
		
		if ($usuario['contato_cia'])	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' do grupo.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce"  nowrap="nowrap">'.link_cia($usuario['contato_cia']).'</td></tr>';
		if ($usuario['dept_nome']) echo '<tr><td align="right">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' do grupo dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td class="realce"  nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m=depts&a=ver&dept_id='.$usuario['contato_dept'].'\');">'.$usuario['dept_nome'].'</a></td></tr>';
		if ($usuario['contato_funcao']) echo '<tr><td align="right">'.dica('Cargo/Função', 'O Cargo/Função ddo grupo dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Função:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$usuario['contato_funcao'].'</td></tr>';
		
		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_usuario');
		$sql->adOnde('usuario_grupo_pai = '.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_usuario >0');
		$usuarios_selecionados = $sql->carregarColuna();
		$sql->limpar();
		
		
		$sql->adTabela('usuario_grupo');
		$sql->adCampo('usuario_grupo_dept');
		$sql->adOnde('usuario_grupo_pai = '.(int)$usuario_id);
		$sql->adOnde('usuario_grupo_dept >0');
		$depts_selecionados = $sql->carregarColuna();
		$sql->limpar();
		
		$saida_quem='';
		if (count($usuarios_selecionados)) {
				$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_quem.= '<tr><td>'.link_usuario($usuarios_selecionados[0], '','','esquerda');
				$qnt_usuarios_selecionados=count($usuarios_selecionados);
				if ($qnt_usuarios_selecionados > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_usuarios_selecionados; $i < $i_cmp; $i++) $lista.=link_usuario($usuarios_selecionados[$i], '','','esquerda').'<br>';		
						$saida_quem.= dica('Outr'.$config['genero_usuario'].'s '.ucfirst($config['usuarios']), 'Clique para visualizar '.$config['genero_usuario'].'s demais '.strtolower($config['usuarios']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'usuarios_selecionados\');">(+'.($qnt_usuarios_selecionados - 1).')</a>'.dicaF(). '<span style="display: none" id="usuarios_selecionados"><br>'.$lista.'</span>';
						}
				$saida_quem.= '</td></tr></table>';
				} 
		if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['usuarios']).' Envolvid'.$config['genero_usuario'].'s', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').ucfirst($config['usuarios']).' envolvid'.$config['genero_usuario'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_quem.'</td></tr>';
		
		
		$saida_depts='';
		if (count($depts_selecionados)) {
				$saida_depts.= '<table cellpadding=0 cellspacing=0 width=100%>';
				$saida_depts.= '<tr><td>'.link_secao($depts_selecionados[0]);
				$qnt_lista_depts=count($depts_selecionados);
				if ($qnt_lista_depts > 1) {		
						$lista='';
						for ($i = 1, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts_selecionados[$i]).'<br>';		
						$saida_depts.= dica('Outr'.$config['genero_dept'].'s '.ucfirst($config['departamentos']), 'Clique para visualizar '.$config['genero_dept'].'s demais '.strtolower($config['departamentos']).'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'lista_depts\');">(+'.($qnt_lista_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="lista_depts"><br>'.$lista.'</span>';
						}
				$saida_depts.= '</td></tr></table>';
				} 
		else $saida_depts.= '<table cellpadding=0 cellspacing=0 class="texto" width=100%><tr><td>&nbsp;</td></tr></table>';			
		if ($saida_depts) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamentos']).' Envolvid'.$config['genero_dept'].'s', 'Quais '.strtolower($config['departamentos']).' estão envolvid'.$config['genero_dept'].'s.').ucfirst($config['departamentos']).' envolvid'.$config['genero_dept'].'s:'.dicaF().'</td><td width="100%" colspan="2" class="realce">'.$saida_depts.'</td></tr>';

		
		}
	else {
		echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['usuario']), 'Nome do '.ucfirst($config['usuario']).'.').'<b>'.ucfirst($config['usuario']).':</b>'.dicaF().'</td><td class="realce" width="100%">'.($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']).'</td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">'.dica('Login', 'Login do '.ucfirst($config['usuario']).' no '.$config['gpweb'].'.').'Login:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['usuario_login'].'</td></tr>';
		if ($usuario['contato_nomecompleto']) echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome Completo', 'Nome completo d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Nome completo:'.dicaF().'</td><td class="realce" >'.$usuario['contato_nomecompleto'].'</td></tr>';
		if ($usuario['contato_cia'])	echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' d'.$config['genero_usuario'].' '.$config['usuario'].'.').ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce"  nowrap="nowrap">'.link_cia($usuario['contato_cia']).'</td></tr>';
		if ($usuario['dept_nome']) echo '<tr><td align="right">'.dica(ucfirst($config['departamento']), strtoupper($config['genero_dept']).' '.strtolower($config['departamento']).' d'.$config['genero_usuario'].' '.$config['usuario'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td class="realce"  nowrap="nowrap"><a href="javascript:void(0);" onclick="url_passar(0, \'m=depts&a=ver&dept_id='.$usuario['contato_dept'].'\');">'.$usuario['dept_nome'].'</a></td></tr>';
		if ($usuario['contato_funcao']) echo '<tr><td align="right">'.dica('Cargo/Função', 'O Cargo/Função d'.$config['genero_usuario'].' '.$config['usuario'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Cargo/Função:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$usuario['contato_funcao'].'</td></tr>';
		if ($usuario['contato_arma']) echo '<tr><td align="right">'.dica('Arma/Quadro/Sv', 'A Arma/Quadro/Sv d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Arma/Quadro/Sv:'.dicaF().'</td><td class="realce" >'.$usuario['contato_arma'].'</td></tr>';
		if ($usuario['contato_tipo']) echo '<tr><td align="right">'.dica('Tipo', 'O tipo d'.$config['genero_usuario'].' '.$config['usuario'].'.<br><br>Não tem relevância para o '.$config['gpweb'].' mas pode facilitar na catalogação d'.$config['genero_usuario'].'s '.$config['usuarios'].'.').'Tipo:'.dicaF().'</td><td class="realce" >'.$usuario['contato_tipo'].'</td></tr>';
		if ($usuario['contato_identidade']) echo '<tr><td align="right">'.dica('Identidade', 'A identidade d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Identidade:'.dicaF().'</td><td class="realce" >'.$usuario['contato_identidade'].'</td></tr>';
		if ($usuario['contato_cpf']) echo '<tr><td align="right">'.dica('CPF', 'O CPF d'.$config['genero_usuario'].' '.$config['usuario'].'.').'CPF:'.dicaF().'</td><td class="realce" >'.$usuario['contato_cpf'].'</td></tr>';
		if ($usuario['contato_matricula']) echo '<tr><td align="right">'.dica('Matrícula', 'A matrícula d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Matrícula:'.dicaF().'</td><td class="realce" >'.$usuario['contato_matricula'].'</td></tr>';
		if ($usuario['contato_tel']) echo '<tr><td nowrap="nowrap" align="right">'.dica('Telefone Comercial', 'O telefone comercial d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Tel. Comercial:'.dicaF().'</td><td class="realce" style="text-align: justify;">('.$usuario['contato_dddtel'].')'.$usuario['contato_tel'].'</td></tr>';
		if ($usuario['contato_tel2']) echo '<tr><td nowrap="nowrap" align="right">'.dica('Telefone Residencial', 'O telefone residencial d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Tel. Residencial:'.dicaF().'</td><td class="realce" >('.$usuario['contato_dddtel2'].')'.$usuario['contato_tel2'].'</td></tr>';
		if ($usuario['contato_cel']) echo '<tr><td nowrap="nowrap" align="right">'.dica('Celular', 'O celular d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Celular:'.dicaF().'</td><td class="realce" >'.$usuario['contato_cel'].'</td></tr>';
		if ($usuario['contato_endereco1'] ||$usuario['contato_endereco2'] ) echo '<tr valign="top"><td align="right" nowrap="nowrap">'.dica('Endereço', 'O endereço d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Endereço:'.dicaF().'</td><td class="realce" width="100%">'.$usuario['contato_endereco1'].($usuario['contato_endereco1'] ? '<br />' :'').$usuario['contato_endereco2'].($usuario['contato_endereco2'] ? '<br />' :'').$usuario['contato_cidade'].' '.$usuario['contato_estado'].' '.$usuario['contato_cep'].($usuario['contato_cidade'] || $usuario['contato_estado'] || $usuario['contato_cep'] ? '<br />' :'').(isset($paises[$usuario['contato_pais']]) ? $paises[$usuario['contato_pais']] : $usuario['contato_pais']).'</td></tr>';
		$data = new CData($usuario['contato_nascimento']);
		if ($usuario['contato_nascimento'] && $usuario['contato_nascimento']!='0000-00-00') echo '<tr><td align="right">'.dica('Aniversário', 'O aniversário d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Aniversário:'.dicaF().'</td><td class="realce"  nowrap="nowrap">'.$data->format('%d/%m/%Y').'</td></tr>';		
		if ($usuario['contato_icq']) echo '<tr><td align="right">'.dica('ICQ', 'A conta ICQ d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'ICQ:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="http://web.icq.com/whitepages/message_me?uin="'.$usuario['contato_icq'].'"&action=message">'.$usuario['contato_icq'].'</a></td></tr>';
		if ($usuario['contato_msn']) echo '<tr><td align="right">'.dica('MSN', 'A conta MSN d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'MSN:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="msnim:chat?contact='.$usuario['contato_msn'].'">'.$usuario['contato_msn'].'</a></td></tr>';
		if ($usuario['contato_yahoo']) echo '<tr><td align="right">'.dica('Yahoo', 'A conta Yahoo d'.$config['genero_usuario'].' '.$config['usuario'].', para entrar em '.$config['usuario'].' com o mesmo.').'Yahoo:'.dicaF().'</td><td class="realce" ><a href="ymsgr:sendim?'.$usuario['contato_yahoo'].'">'.$usuario['contato_yahoo'].'</a></td></tr>';
		if ($usuario['contato_skype']) echo '<tr><td align="right">'.dica('Skype', 'A conta Skype d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Skype:'.dicaF().'</td><td class="realce" style="text-align: justify;"><a href="skype:'.$usuario['contato_skype'].'?call">'.$usuario['contato_skype'].'</a></td></tr>';
		if ($usuario['contato_jabber']) echo '<tr><td align="right">'.dica('Jabber', 'A conta Jabber d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'Jabber:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$usuario['contato_jabber'].'</td></tr>';
		if ($usuario['contato_email'])  echo '<tr><td align="right">'.dica('e-mail', 'O e-mail d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'e-mail:'.dicaF().'</td><td class="realce" nowrap="nowrap">'.link_email($usuario['contato_email'],$usuario['contato_id']).'</td></tr>';
		if ($usuario['contato_email2']) echo '<tr><td align="right">'. dica('e-mail Alternativo', 'O e-mail alternativo d'.$config['genero_usuario'].' '.$config['usuario'].', para se comunicar com o mesmo.').'e-mail:'.dicaF().'</td><td class="realce" nowrap="nowrap">'.link_email($usuario['contato_email2'],$usuario['contato_id']).'</td></tr>';
		if ($usuario['usuario_rodape'])echo '<tr><td align="right">Rodapé:</td><td class="realce" style="text-align: justify;">'.str_replace(chr(10),'<br />', $usuario['usuario_rodape']).'</td></tr>';
		if ($usuario['contato_hora_custo'] > 0 && $Aplic->checarModulo('usuarios', 'acesso', $Aplic->usuario_id, 'hora_custo')) echo '<tr><td align="right">'.dica('Custo da Hora', 'O custo da hora de trabalho d'.$config['genero_usuario'].' '.$config['usuario'].'.').'Custo hora:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$config["simbolo_moeda"].' '.number_format($usuario['contato_hora_custo'], 2, ',', '.').'</td></tr>';
		
		
		
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('usuario', $usuario_id, 'ver');
		if ($campos_customizados->count()) {
				echo '<tr><td colspan="2">';
				$campos_customizados->imprimirHTML();
				echo '</td></tr>';
				}	
		
		if ($Aplic->profissional) include_once BASE_DIR.'/modulos/admin/ver_usuario_pro.php';
	
		}
	echo '</table>';
	if (!$dialogo){	
		$ver_min = true;
		$caixaTab = new CTabBox('m=admin&a=ver_usuario&usuario_id='.$usuario_id, '', $tab);
		if ($Aplic->checarModulo('admin', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_registro_usuarios', 'Acesso',null,null,'Registro de Acesso','Visualizar as datas e horas de entrada e saída do Sistema para este '.$config['usuario'].'.');
		$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_nivel_acesso_usuario', 'Perfil Acesso',null,null,'Perfil de Acesso','Visualizar o perfil de acesso deste '.$config['usuario'].'.<br><br>Cada perfil de acesso permite acessar diferentes funcionalidades do Sistema.');
		if ($Aplic->modulo_ativo('calendario') && $Aplic->checarModulo('calendario', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionado com este '.$config['usuario'].'.');
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/tab_usuario.ver.compromissos', 'Compromissos',null,null,'Compromissos','Visualizar os compromissos em que esteja envolvido.');	
		if ($Aplic->modulo_ativo('tarefas') && $Aplic->checarModulo('tarefas', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/tarefas', ucfirst($config['tarefas']),null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' que este '.$config['usuario'].' é responsável ou foi designado.');
		if ($Aplic->modulo_ativo('projetos') && $Aplic->checarModulo('projetos', 'acesso')) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_projetos', ucfirst($config['projetos']),null,null,ucfirst($config['projetos']),'Visualizar '.$config['genero_projeto'].'s '.$config['projetos'].' em que este '.$config['usuario'].' é o responsável.');
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_gantt', 'Gantt',null,null,'Gantt','Visualizar os períodos em que '.$config['genero_usuario'].' '.$config['usuario'].' esteja designado para '.$config['tarefas'].', em forma de gráfico Gantt.');
			if ($config['checar_comprometimento']) $caixaTab->adicionar(BASE_DIR.'/modulos/calendario/sobrecarga', 'Comprometimento',null,null,'Comprometimento','Visualizar o grau de comprometimento d'.$config['genero_usuario'].' '.$config['usuario'].' n'.$config['genero_tarefa'].'s '.$config['tarefas'].'.');
			}
			
		if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso')) {
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_indicadores', 'Indicadores',null,null,'Indicadores','Visualizar os indicadores que este '.$config['usuario'].' seja responsável ou foi designado.');	
			$caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_praticas', ucfirst($config['praticas']),null,null,ucfirst($config['praticas']),'Visualizar '.$config['genero_pratica'].'s '.$config['praticas'].' que este '.$config['usuario'].' seja responsável ou foi designado.');	
			if ($Aplic->modulo_ativo('praticas') && $Aplic->checarModulo('praticas', 'acesso', null, 'plano_acao')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_planos_acao', ucfirst($config['acoes']),null,null,ucfirst($config['acoes']),'Visualizar '.$config['genero_acao'].'s '.$config['acoes'].' relacionad'.$config['genero_acao'].'s.');
			}
		if ($Aplic->modulo_ativo('email') && $Aplic->checarModulo('email', 'acesso')) $caixaTab->adicionar(BASE_DIR.'/modulos/admin/ver_grupos_msg', 'Grupos Msg.',null,null,'Grupos de '.ucfirst($config['mensagem']),'Visualizar os grupos de destinatários de '.$config['mensagens'].' que este '.$config['usuario'].' é participante, ou que tem autorização para visualizar.');	
		$caixaTab->mostrar('','','','',true);
		}
	if (!$dialogo) echo estiloFundoCaixa();
	else echo '<script language="javascript">self.print();</script>';	
	}

?>
<script language="javascript">

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
	
function popMudarSenha() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Mudar Senha', 350,220,'m=publico&a=checarsenha&dialogo=1&usuario_id=<?php echo $usuario["usuario_id"]; ?>', null, window);
	else window.open( './index.php?m=publico&a=checarsenha&dialogo=1&usuario_id=<?php echo $usuario["usuario_id"]; ?>', 'checarsenha', 'top=250,left=250,width=350, height=220, scrollbars=no');
	

	}
</script>
