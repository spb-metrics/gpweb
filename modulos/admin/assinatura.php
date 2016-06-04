<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
$usuario_id=getParam($_REQUEST, 'usuario_id', 0);

$incluir=getParam($_REQUEST, 'incluir', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);

$sql = new BDConsulta;

$sql->adTabela('usuarios');
$sql->adCampo('usuario_assinatura');
$sql->adOnde('usuario_id = '.$usuario_id);
$caminho=$sql->Resultado();
$sql->Limpar();

if ($incluir && isset($_FILES['assinatura']['name']) && file_exists($_FILES['assinatura']['tmp_name']) && !empty($_FILES['assinatura']['tmp_name'])){
	//apagar antigo
	if ($caminho)@unlink($base_dir.'/arquivos/assinaturas/'.$caminho);
	$caminho = $usuario_id.'_'.$_FILES['assinatura']['name'];
	$caminho_completo = $base_dir.'/arquivos/assinaturas/'.$caminho;
	move_uploaded_file($_FILES['assinatura']['tmp_name'], $caminho_completo);
	$sql->adTabela('usuarios');
	$sql->adAtualizar('usuario_assinatura', $caminho);
	$sql->adOnde('usuario_id = '.$usuario_id);
	$retorno=$sql->exec();
	$sql->Limpar();
	}

if ($excluir){
	if ($caminho)@unlink($base_dir.'/arquivos/assinaturas/'.$caminho);
	$sql->adTabela('usuarios');
	$sql->adAtualizar('usuario_assinatura', '');
	$sql->adOnde('usuario_id = '.$usuario_id);
	$retorno=$sql->exec();
	$sql->Limpar();
	$caminho='';
	}




$sql->adTabela('usuarios');
$sql->adCampo('usuario_assinatura');
$sql->adOnde('usuario_id = '.$usuario_id);
$caminho=$sql->Resultado();
$sql->Limpar();




echo '<form method="POST" id="env" name="env" enctype="multipart/form-data">';
echo '<input type=hidden id="m" name="m" value="admin">';
echo '<input type=hidden id="a" name="a" value="assinatura">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';
echo '<input type=hidden id="usuario_id" name="usuario_id" value="'.$usuario_id.'">';	
echo '<input type=hidden id="incluir" name="incluir" value="">';	
echo '<input type=hidden id="excluir" name="excluir" value="">';	
echo estiloTopoCaixa();
echo '<table width="100%" align="center" class="std" cellspacing="4" cellpadding="4" >';



if ($caminho) echo '<tr><td colspan=20><table><tr><td><img src="'.$base_url.'/arquivos/assinaturas/'.$caminho.'" /></td></tr><tr><td align=center>'.botao('excluir','','','','env.excluir.value=1; env.submit()').'</td></tr></table></td></tr>';


echo '<tr><td align="left"><b>Imagem:</b><input type="File" class="arquivo" name="assinatura" size="59" /></td></tr>';
echo '<tr><td align=center>'.botao(($caminho ? 'atualizar' : 'enviar'), '','','','env.incluir.value=1; env.submit()').'</td></tr>';

echo '</table>';

echo estiloFundoCaixa();
echo '</form>';
?>