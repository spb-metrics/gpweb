<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!($usuario_id = getParam($_REQUEST, 'usuario_id', 0))) $usuario_id = $Aplic->usuario_id;
global $config;

$q = new BDConsulta;

if ($usuario_id) {
	$senhaAntiga = db_escape(trim(getParam($_REQUEST, 'senhaAntiga', null)));
	$senhaNova1 = db_escape(trim(getParam($_REQUEST, 'senhaNova1', null)));
	$senhaNova2 = db_escape(trim(getParam($_REQUEST, 'senhaNova2', null)));

	if ($senhaNova1 && $senhaNova2 && ($senhaNova1 == $senhaNova2)) {
		$antigo_md5 = md5($senhaAntiga);


		$q->adTabela('usuarios');
		$q->adCampo('usuario_id, usuario_login');
		if (!$Aplic->usuario_admin) $q->adOnde('usuario_senha = \''.$antigo_md5.'\'');
		$q->adOnde('usuario_id = '.(int)$usuario_id);
		$resultado=$q->Linha();
		$q->limpar();
		if ($Aplic->usuario_admin || $resultado['usuario_id'] == $usuario_id) {
			require_once ($Aplic->getClasseModulo('admin'));
			$q->adTabela('usuarios');
			$q->adAtualizar('usuario_senha', md5($senhaNova1));
			$q->adOnde('usuario_id = '.$usuario_id);
			if (!$q->exec()) die('Não foi possível alterar a senha.');
			$q->limpar();
			echo '<h1>Mudar Senha de '.ucfirst($config['usuario']).'</h1>';
			echo estiloTopoCaixa();
			echo '<table width="100%" cellspacing=0 cellpadding="4" border=0 class="std"><tr><td>A sua senha foi alterada</td></tr></table>';
			}
		else {
			echo '<h1>Mudar Senha</h1>';
			echo estiloTopoCaixa();
			echo '<table width="100%" cellspacing=0 cellpadding="4" border=0 class="std"><tr><td>A sua senha não está correta</td></tr></table>';
			}
		}
	else {
		echo '<h1>Mudar Senha de '.ucfirst($config['usuario']).'</h1>';
		echo estiloTopoCaixa();
		echo '<form name="frmEditar" method="post" onsubmit="return false">';
		echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
		echo '<table width="100%" cellspacing=0 cellpadding="4" border=0 class="std">';
		if (!$Aplic->usuario_admin) echo '<tr><td align="right" nowrap="nowrap">Senha Atual</td><td><input type="password" name="senhaAntiga" class="texto" /></td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">Nova Senha</td><td><input type="password" name="senhaNova1" class="texto" /></td></tr>';
		echo '<tr><td align="right" nowrap="nowrap">Repita Nova Senha</td><td><input type="password" name="senhaNova2" class="texto" onkeypress="return submitenter(this, event)" /></td></tr>';
		echo '<tr><td>&nbsp;</td><td align="right" nowrap="nowrap">'.botao('confirmar', '', '','','enviarDados()').'</td></tr>';
		echo '<form></table>';
		}
	}
else {
	echo '<h1>Mudar Senha de '.ucfirst($config['usuario']).'</h1>';
	echo estiloTopoCaixa();
	echo '<table width="100%" cellspacing=0 cellpadding="4" border=0 class="std"><tr><td>'.ucfirst($config['usuario']).' não existe</td></tr></table>';
	}
echo estiloFundoCaixa();
?>
<script language="javascript">
function enviarDados() {
    console.log('enviando dados');
	var f = document.frmEditar;
	var msg = '';
	if (f.senhaNova1.value.length < 3) {
    msg += "Por favor insira uma nova senha válida ";
		f.senhaNova1.focus();
		}
	if (f.senhaNova1.value != f.senhaNova2.value) {
		msg += "\nSenha Incorreta";
		f.senhaNova2.focus();
		}
	if (msg.length < 1)	f.submit();
	else alert(msg);
	}


function submitenter(campo,e){
	var codigo;
	if (window.event) codigo = window.event.keyCode;
	else if (e) codigo = e.which;
	else return true;

	if (codigo == 13) {
	   enviarDados();
	   return false;
	   }
	else return true;
	}

</script>
