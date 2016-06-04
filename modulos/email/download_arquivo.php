<?php 
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
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
else echo 'Arquivo não encontrado';
?>


