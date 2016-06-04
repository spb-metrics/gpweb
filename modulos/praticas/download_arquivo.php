<?php 
/* 
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\download_arquivo.php		

Rotina para poder abrir um arquivo anexado ao clinar no link do mesmo																																								
																																												
********************************************************************************************/
global $config;
$sql = new BDConsulta;

$sql->adTabela('plano_gestao_arquivos');
$sql->adCampo('pg_arquivo_pg_id, pg_arquivo_endereco, pg_arquivo_nome');
$sql->adOnde('pg_arquivos_id ='.(int)getParam($_REQUEST, 'pg_arquivos_id', null));	
$rs=$sql->Linha();	
$sql->Limpar();		
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$caminho_completo = $base_dir.'/arquivos/gestao/'.$rs['pg_arquivo_endereco'];
if (file_exists ($caminho_completo) && !empty($rs['pg_arquivo_nome'])){
  $tamanho = filesize ($caminho_completo);
  header("Content-Type: application/open");
	header("Content-Length: ".$tamanho);
	header("Content-Disposition: attachment; filename=".$rs['pg_arquivo_nome']);
	header("Content-Transfer-Encoding: binary");
  readfile($caminho_completo);
  } 
else echo 'Arquivo n�o encontrado';
?>


