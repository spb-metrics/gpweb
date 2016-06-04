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
$sql = new BDConsulta;

if(getParam($_REQUEST, 'excluir', 0)){
	$sql->adTabela('usuarios');
	$sql->adAtualizar('usuario_senha2', '');
	$sql->adAtualizar('usuario_login2', '');
	$sql->adOnde('usuario_id = '.$usuario_id);
	$sql->exec();
	$sql->limpar();
	ver2('Login e senha excluidos.');
	echo '<script language="javascript">if(parent && parent.gpwebApp) parent.gpwebApp._popupCallback(null, ""); else window.close();</script>';
	exit();
	}
elseif(getParam($_REQUEST, 'inserir', 0)){
	$usuario_login2=getParam($_REQUEST, 'usuario_login2', '');
	$usuario_senha2=md5(getParam($_REQUEST, 'usuario_senha2', ''));
	$sql->adTabela('usuarios', 'usuarios');
	$sql->adCampo('usuario_id');
	$sql->adOnde('usuario_login = \''.$usuario_login2.'\'');
	$sql->adOnde('usuario_senha = \''.$usuario_senha2.'\'');
	$achado = $sql->Resultado();
	$sql->limpar();
	
	if ($achado){
		$sql->adTabela('usuarios');
		$sql->adAtualizar('usuario_senha2', $usuario_senha2);
		$sql->adAtualizar('usuario_login2', $usuario_login2);
		$sql->adOnde('usuario_id = '.$usuario_id);
		$sql->exec();
		$sql->limpar();
		ver2('Login e senha cadastrados.');
		}
	else ver2('O Login e senha não conferem');
	
	echo '<script language="javascript">if(parent && parent.gpwebApp) parent.gpwebApp._popupCallback(null, ""); else window.close();</script>';
	exit();
	}

$sql->adTabela('usuarios', 'usuarios');
$sql->adCampo('usuario_login2');
$sql->adOnde('usuario_id = '.(int)$usuario_id);

$login2 = $sql->Resultado();
$sql->limpar();

echo estiloTopoCaixa();
echo '<form method="post" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="publico">';
echo '<input type=hidden id="a" name="a" value="segunda_conta">';	
echo '<input type="hidden" name="usuario_id" value="'.$usuario_id.'" />';
echo '<input type="hidden" name="inserir" value="0" />';
echo '<input type="hidden" name="excluir" value="0" />';
echo '<input type="hidden" name="dialogo" value="1" />';

echo '<table width="100%" cellspacing=0 cellpadding="4" border=0 class="std">';
echo '<tr><td colspan=2 align="center" nowrap="nowrap"><h1>Segunda conta de '.$config['usuario'].'</h1></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Login</td><td><input type="text" name="usuario_login2" class="texto" value="'.$login2.'"/></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Senha</td><td><input type="password" name="usuario_senha2" class="texto" /></td></tr>';
echo '<tr><td colspan=2 align="center"><table><tr><td>'.botao('confirmar', '', '','','enviarDados()').'</td><td>'.botao('excluir', '', '','','env.excluir.value=1; env.submit();').'</td>'.(!$Aplic->profissional ? '<td>'.botao('cancelar', '', '','','self.close();').'</td>' : '').'</tr></table></td></tr>';
echo '</table><form>';

echo estiloFundoCaixa();	
?>
<script language="javascript">
function enviarDados() {
	var f = document.env;
	var msg = '';
	if (f.usuario_senha2.value.length < 3) {	
    msg += "Por favor insira uma nova senha válida. ";
		f.usuario_senha2.focus();
		}
	else if (f.usuario_login2.value.length < 1) {	
    msg += "Por favor insira uma login válido. ";
		f.usuario_login2.focus();
		}
	if (msg.length < 1)	{
		f.inserir.value=1;
		f.submit();
		}
	else alert(msg);
	}
</script>
