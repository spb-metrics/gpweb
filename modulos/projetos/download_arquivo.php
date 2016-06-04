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

/********************************************************************************************
		
gpweb\modulos\praticas\download_arquivo.php		

Rotina para poder abrir um arquivo anexado ao clinar no link do mesmo																																								
																																												
********************************************************************************************/

$sql = new BDConsulta;

$sql->adTabela('projeto_anexo_arquivos');
$sql->adCampo('pa_arquivos_id, pa_arquivo_endereco, pa_arquivo_nome');
$sql->adOnde('pa_arquivos_id ='.$_REQUEST['pa_arquivos_id']);	
$rs=$sql->Linha();	
$sql->Limpar();		
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$caminho_completo = $base_dir.'/arquivos/anexos_projeto/'.$rs['pa_arquivo_endereco'];
if (file_exists ($caminho_completo) && !empty($rs['pa_arquivo_nome'])){
  $tamanho = filesize ($caminho_completo);
  header("Content-Type: application/open");
	header("Content-Length: ".$tamanho);
	header("Content-Disposition: attachment; filename=".$rs['pa_arquivo_nome']);
	header("Content-Transfer-Encoding: binary");
  readfile($caminho_completo);
  } 
else echo 'Arquivo não encontrado';
?>


