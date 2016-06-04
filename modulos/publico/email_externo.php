<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $m, $obj, $config;

$Aplic->carregarCKEditorJS();
$contato_id = getParam($_REQUEST, 'contato_id', 0);
$usuario_id = getParam($_REQUEST, 'usuario_id', 0);


//verificar email externo
$sql = new BDConsulta;
$sql->adTabela('contatos');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_contato=contatos.contato_id');
$sql->adCampo('contato_id, usuario_id, contato_email, contato_email2, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
if($contato_id) $sql->adOnde('contato_id = '.$contato_id);
else $sql->adOnde('usuario_id = '.$usuario_id);
$linha = $sql->Linha();
$sql->limpar();

$emails=array();
if (isset($linha['contato_email']) && $linha['contato_email'])$emails[]=$linha['contato_email'];
if (isset($linha['contato_email2']) && $linha['contato_email2'])$emails[]=$linha['contato_email2'];
if (!$usuario_id) $usuario_id=(isset($linha['usuario_id']) && $linha['usuario_id'] ? $linha['usuario_id'] : 0);
if (!$contato_id) $contato_id=(isset($linha['contato_id']) && $linha['contato_id'] ? $linha['contato_id'] : 0);


if (getParam($_REQUEST, 'enviado', 0)){
	$ok=0;
	$titulo = getParam($_REQUEST, 'titulo', '');
	$mensagem = getParam($_REQUEST, 'mensagem', '');


	foreach ($emails as $email) {
		if($config['email_ativo'] && $config['email_externo_auto']) {
			$saida=msg_email_externo($email, $Aplic->usuario_nome.': '.$titulo, $mensagem);
			$ok=($ok||$saida);
			}
		}
		if ($usuario_id) {
			$saida=msg_email_interno('' , $titulo, $mensagem, '', $usuario_id);
			$ok=($ok||(!$saida));
			}
	if ($ok) echo '<script>alert("E-mail enviado.");</script>';
	else echo '<script>alert("Não foi possível enviar o e-mail.");</script>';
    //não fechar janela em modo depuração
	if(!config('email_debug', false)){
        echo '<script>window.opener = window;	window.close();</script>';
        }
	}

echo '<form name="env" id="env" method="post" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="publico">';
echo '<input type=hidden name="a" value="email_externo">';
echo '<input type=hidden name="dialogo" value="1">';
echo '<input type=hidden name="enviado" id="enviado" value="0">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">';
echo '<input type=hidden name="contato_id" id="contato_id" value="'.$contato_id.'">';
echo estiloTopoCaixa();
echo '<table width="100%" cellspacing=0 cellpadding="3" border=0 class="std">';
echo '<tr><td><table width="100%"><tr><td width="25%"></td><td align="center" width="50%"><b>E-mail</b></td><td width="25%" align=right>'.botao('enviar', '', '','','envia()').'</td></tr></table></td></tr>';
echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td align="right" width="80">De: </td><td width="300" class="texto" style="background: #ffffff">'.$Aplic->usuario_nome.'</td></tr></table></td></tr>';
echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td align="right" width="80">Para: </td><td width="300" class="texto" style="background: #ffffff"><span>'.link_contato($contato_id).'<span></td></tr></table></td></tr>';
echo '<tr><td><table cellspacing=0 cellpadding=0><tr><td align="right" width="80">Título: </td><td><input type="text" class="texto" name="titulo" id="titulo" maxlength="255" size="100" /></td></tr></table></td></tr>';
echo '<tr><td style="background:#ffffff; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="15" name="mensagem" id="mensagem"></textarea></td></tr>';
echo '<tr><td colspan="3" align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();"><b>Anexar arquivos</b></a></td></tr>';
echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';

if ($contato_id && $config['email_ativo'] && count($emails) && !$config['email_externo_auto']) echo '<tr><td colspan=2 align=center>Cópia para E-mail externo<input type="checkbox" name="externo" id="externo" value="1" '.(!$usuario_id ? 'checked="checked" disabled' :'').'></td></tr>';


echo '</form>';
echo '</table>';
echo estiloFundoCaixa();
?>
<script language="javascript">

function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');
	var ta = document.createTextNode('Tipo:');
	myselect = document.createElement("select");
	myselect.className="texto";
	myselect.style.width="80px";
	myselect.name="doc_tipo[]";
	ca.appendChild(ta);
	<?php
	foreach (getSisValor('tipo_anexo','','','sisvalor_id ASC') as $chave => $valor){
		echo 'theOption=document.createElement("OPTION");';
		echo 'theText=document.createTextNode("'.$valor.'");';
		echo 'theOption.setAttribute("value","'.$chave.'");';
		echo 'theOption.appendChild(theText);';
		echo 'myselect.appendChild(theOption);';
		}
	?>
	ca.appendChild(myselect);

	var ta = document.createTextNode(' Nº:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc_nr[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=5;
	campo.className="texto";
	ca.appendChild(campo);

	var ta = document.createTextNode(' Nome:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'nome_fantasia[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=20;
	campo.className="texto";
	ca.appendChild(campo);

	var ta = document.createTextNode(' Arq:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=30;
	campo.className="texto";
	ca.appendChild(campo);

	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}


function envia(){
	document.getElementById('enviado').value=1;
	document.getElementById('env').submit();
	}
</script>
