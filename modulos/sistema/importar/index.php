<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!$Aplic->checarModulo('projetos', 'adicionar')) $Aplic->redirecionar('m=publico&a=acesso_negado');
require_once(BASE_DIR.'/modulos/sistema/importar/importar.class.php');

echo '<div id="import_pro"></div><div id="clear_pro">';
$botoesTitulo = new CBlocoTitulo('Importar do MS Project ou WBS Chart Pro', 'ms_project.jpg');
if ($Aplic->usuario_super_admin) $botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
else $botoesTitulo->adicionaBotao('m=projetos', 'lista','','Lista de Projetos','Ir paraa lista de projetos.');
$botoesTitulo->mostrar();
if (!$dialogo) $Aplic->salvarPosicao();

echo estiloTopoCaixa();	
echo '<table border=0 cellpadding=0 cellspacing=2 width="100%" class="std">';

$acao = getParam($_REQUEST, 'acao', '');
$arquivotipo = getParam($_REQUEST, 'filetype', null);

$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);


$meuLimiteMemoria = ini_get('memory_limit');
$meuLimiteMemoria = intval(substr($meuLimiteMemoria, 0, strlen($meuLimiteMemoria) - 1));
$tamMaxArquivo = substr(ini_get('memory_limit'), 0, strlen(ini_get('memory_limit') - 1)) * 1024* 1000;

if ($meuLimiteMemoria < 256) {
  ini_set('memory_limit', '256M');
  ini_set('post_max_size', '256M');
  ini_set('upload_max_filesize', '256M');
	}

switch($acao) {
    case 'importar':
	    if ($_FILES['upload_file']['size'] == 0) {
	      unset($acao);
	      echo '<tr><td><br /><b>Falhou!</b> Precisa selecionar um arquivo para enviar, ou o arquivo enviado tem um tamanho superior ao limite configurado no servidor. Verifique as constantes upload_max_filesize e post_max_size no arquivo php.ini no servidor e aumente os valores.</td></tr>';
	      if ($_FILES['upload_file']['error'] !== UPLOAD_ERR_OK) echo '<tr><td><br /><b>Erro:</b> '.uploadError($_FILES['upload_file']['error']).'<br/></td></tr>';
	      echo '<form enctype="multipart/form-data" name="env" method="post">';
	      echo '<input type="hidden" name="m" value="sistema" />';
				echo '<input type="hidden" name="a" value="index" />';
				echo '<input type="hidden" name="u" value="importar" />';
	     	echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$tamMaxArquivo.'" />';
	      echo '<input type="hidden" name="acao" value="importar" />';
	      echo '<tr><td colspan=20><input type="radio" checked="checked" name="formatacao" value="8859">ISO-8859-1<input type="radio" name="formatacao" value="utf">UTF-8</td></tr>';
	      echo '<tr><td><input type="file" class="arquivo" class="texto" name="upload_file" size="60" /></td><td><input type="submit" name="submit" value="Importar dados" /></td></tr>';
	      echo '</form>';
	      break;
	    	} 
	    else {
	      $arquivoext = substr($_FILES['upload_file']['name'], -4);
	      $importar = CImportar::resolverTipoArquivo($arquivoext);
	      $importar->formatacao=getParam($_REQUEST, 'formatacao', '8859');
	      if (($arquivoext == '.xml') || ($arquivoext == '.wbs')) $acao = 'visualizar';
	      else $acao = '';
	      if ($acao && !$importar->loadFile($Aplic)) {
	        unset($acao);
	        echo '<tr><td><b>Erro!</b> Não há nenhuma tarefa neste arquivo. Caso você esteja tentando importar um .wbs e esse projeto estiver associado ao Microsoft Project, salve seu projeto como .xml no Microsoft Project e utilize este arquivo</td></tr>';
	        break;
	    		}
	  		}

    case 'visualizar':
    	if($acao && $Aplic->profissional){
				$importar->formatacao=getParam($_REQUEST, 'formatacao', '8859');
				$importar->visualizar();
    		}
    	else{
				echo '<form name="env" id="env" method="post">';
		    echo '<input type="hidden" name="m" value="sistema" />';
				echo '<input type="hidden" name="a" value="index" />';
				echo '<input type="hidden" name="u" value="importar" />';
				echo '<input type="hidden" name="acao" value="save">';
				if ($acao) echo '<input type="hidden" name="filetype" value="'.$importar->fileType.'">';
				$importar->formatacao=getParam($_REQUEST, 'formatacao', '8859');
		    echo '<tr><td colspan=20><table width="100%" cellpadding=0 cellspacing=0><tr>'.($acao ? '<td>'.botao('importar', 'Importar', 'Importar os dados para o banco de dados.','','if (env.new_project.value==\'\') {alert(\'coloque um nome no projeto\'); env.new_project.focus();} else env.submit();').'</td>' : '').'<td align=right>'.botao('cancelar', 'Cancelar', 'Cancelar a importação de dados.','','env.acao.value=\'cancelar\'; env.submit();').'</td></tr></table></td></tr>';
		    if ($acao) echo $importar->visualizar();
    		if (!$acao) echo '<tr><td colspan=20>Formato não compatível para importação.</td></tr>';
    		echo '</form>';
    		}
	    
	    break;
    case 'save':
      $importar = CImportar::resolverTipoArquivo(getParam($_REQUEST, 'filetype', null));

      echo $importar->import($Aplic);
      if (isset($erro)) echo '<tr><td>Falha:'.$erro.'</td></tr>';
     	else echo '<tr><td>Sucesso!<br><br>Edite o projeto para inserir as informações que não estavam contidas no arquivo importado</td></tr>';

      unset($acao);
      break;
    case 'cancelar':
      echo '<tr><td>Processo abortado.'.(isset($erro) && $erro  ?  'Motivo:'.$erro : '').'</td></tr>';
      unset($acao);
      break;
    default:
    
      echo '<tr><td colspan=20>Tanto o arquivo do MS Project 2003 quanto do WBS Chart Pro necessitam estar no formato XML para serem importados.</td></tr>';
			$upload_mb = min((int)(ini_get('upload_max_filesize')), (int)(ini_get('post_max_size')), (int)(ini_get('memory_limit')));
			echo '<tr><td colspan=20>'.dica('Aumentar o Tamanho', 'Caso necessite aumentar o tamanho do limite de arquivo a ser importado, basta editar php.ini e mudar ou inserir, se for o caso, as seguintes linhas:<br><br>post_max_size = 100M<br>upload_max_filesize = 100M<br>memory_limit = 100M').'Tamanho máximo do upload permitido: '.$upload_mb.' Mb'.dicaF().'</td></tr>';      
      echo '<form enctype="multipart/form-data" name="env" method="post">';
      echo '<input type="hidden" name="m" value="sistema" />';
			echo '<input type="hidden" name="a" value="index" />';
			echo '<input type="hidden" name="u" value="importar" />';
    	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr><td>'.dica('Selecionar '.$config['organizacao'], 'Selecionar em qual '.$config['organizacao'].' irá inserir o projeto importado.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($Aplic->usuario_cia, 'cia_escolhida', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td></tr></table></td></tr>';
      echo '<input type="hidden" name="MAX_FILE_SIZE" value="'.$tamMaxArquivo.'" />';
      echo '<input type="hidden" name="acao" value="importar" />';
      echo '<input type="hidden" id="cia_id" name="cia_id" value="" />';
      echo '<tr><td colspan=20><input type="radio" name="formatacao" checked="checked" value="utf">UTF-8<input type="radio" name="formatacao" value="8859">ISO-8859-1</td></tr>';
     	echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0><tr><td><input type="file" class="arquivo" name="upload_file" size="60" /></td><td>'.botao('carregar', 'Carregar', 'Carregar no sistema o arquivo selecionado no campo à esquerda.','','carregar();').'</td></tr></table></td></tr>';
      echo '</form>';
		}
echo '</table>';
echo estiloFundoCaixa();
echo '</div>';

function uploadError($cod){
	switch($cod){
		case UPLOAD_ERR_INI_SIZE:
			return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
		case UPLOAD_ERR_FORM_SIZE:
			return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
		case UPLOAD_ERR_PARTIAL:
			return 'The uploaded file was only partially uploaded.';
		case UPLOAD_ERR_NO_FILE:
			return 'No file was uploaded.';
		case UPLOAD_ERR_NO_TMP_DIR:
			return 'Missing a temporary folder.';
		case UPLOAD_ERR_CANT_WRITE:
			return ' Failed to write file to disk.';
		case UPLOAD_ERR_EXTENSION:
			return 'A PHP extension stopped the file upload.'; 
		default:
			return 'Erro indefinido ('.$cod.')';
	}
}

?>

<script language="javascript">
	function carregar(){
		document.getElementById('cia_id').value=document.getElementById('cia_escolhida').value;
		env.submit();
		
		}
	
	function mudar_om(){
		xajax_selecionar_om_ajax(document.getElementById('cia_escolhida').value,'cia_escolhida','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"');
		}
</script>

	