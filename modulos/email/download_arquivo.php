<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
global $config;

$sql = new BDConsulta;

if (isset($_REQUEST['modelo_id']) && $_REQUEST['modelo_id']){

	$sql->adTabela('modelos_anexos');
	$sql->adCampo('nome, caminho');
	$sql->adOnde('modelo_anexo_id ='.(int)getParam($_REQUEST, 'anexo', null));	
	$rs=$sql->Linha();	
	$sql->Limpar();
	
	$sql->adTabela('modelo_leitura');
	$sql->adInserir('datahora_leitura', date('Y-m-d H:i:s'));
	$sql->adInserir('usuario_id', $Aplic->usuario_id);
	$sql->adInserir('modelo_id', getParam($_REQUEST, 'modelo_id', null));
	$sql->adInserir('download', 1);
	$sql->exec();
	$sql->limpar();
			
	}
else{
	$sql->adTabela('anexos');
	$sql->adCampo('msg_id, nome, caminho');
	$sql->adOnde('anexo_id ='.(int)getParam($_REQUEST, 'anexo', null));	
	$rs=$sql->Linha();	
	$sql->Limpar();		
	$msg_id = $rs['msg_id'];
	$sql->adTabela('msg_usuario');
	$sql->adCampo('count(de_id)');
	$sql->adOnde('msg_id ='.$msg_id);	
	$sql->adOnde('(de_id='.$Aplic->usuario_id.' OR para_id='.$Aplic->usuario_id.')');	
	$achado=$sql->Resultado();	
	$sql->Limpar();		
	if (!$achado && !$Aplic->usuario_admin) exit('Acesso negado.');
	else{
		$sql->adTabela('anexo_leitura');
		$sql->adInserir('datahora_leitura', date('Y-m-d H:i:s'));
		$sql->adInserir('usuario_id', $Aplic->usuario_id);
		$sql->adInserir('anexo_id', getParam($_REQUEST, 'anexo', null));
		$sql->adInserir('download', 1);
		$sql->exec();
		$sql->limpar();
		}
	}
	
	
$caminho = $rs['caminho'];
$nome = $rs['nome'];
$nome = removerSimbolos($nome);
$nome = removerSimbolos($nome);
$nome = removerSimbolos($nome);

$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
 
$caminho_completo=$base_dir.'/'.($config['pasta_anexos'] ? $config['pasta_anexos'].(isset($_REQUEST['modelo_id']) &&  $_REQUEST['modelo_id'] ? '_modelos' : '').'/':'').$caminho;

if (file_exists ($caminho_completo) && !empty($nome)){
  $tamanho = filesize ($caminho_completo);
  header("Content-Type: application/open");
	header("Content-Length: ".$tamanho);
	header("Content-Disposition: attachment; filename=".$nome);
	header("Content-Transfer-Encoding: binary");
  readfile($caminho_completo);
  } 
else echo 'Arquivo n�o encontrado';
?>


