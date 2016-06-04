<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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