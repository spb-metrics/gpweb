<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

if (!defined('BASE_DIR')) die('Você deveria acessar este arquivo diretamente.');

//funções extras
if (file_exists (BASE_DIR.'/incluir/funcoes_extra_pro.php')) include_once BASE_DIR.'/incluir/funcoes_extra_pro.php';

class CContadorTempo{
	private $start_time;

	public function __construct(){
		$time = microtime();
		$time = explode(" ", $time);
		$time = $time[1] + $time[0];
		$this->start_time = $time;
		}

	public function get()	{
		$time = microtime();
		$time = explode(" ", $time);
		$time = $time[1] + $time[0];
		$finish = $time;
		$totaltime = ($finish - $this->start_time);
		return sprintf("Transcorreu %f segundos.", $totaltime);
		}
	}

function faixaLetras($fim_coluna, $primeira_letra = ''){
	$colunas = array();
	$tamanho = strlen($fim_coluna);
	$letras = range('a', 'z');
	foreach ($letras as $letra) {
		$coluna = $primeira_letra.$letra;
		$colunas[] = $coluna;
		if ($coluna == $fim_coluna) return $colunas;
		}
	foreach ($colunas as $coluna) {
		if (!in_array($fim_coluna, $colunas) && strlen($coluna) < $tamanho) {
			$novas_colunas = faixaLetras($fim_coluna, $coluna);
			$colunas = array_merge($colunas, $novas_colunas);
			}
		}
	return $colunas;
	}

function removeAcentos($string, $slug = false) {
  $string = strtolower($string);
  $ascii['a'] = range(224, 230);
  $ascii['e'] = range(232, 235);
  $ascii['i'] = range(236, 239);
  $ascii['o'] = array_merge(range(242, 246), array(240, 248));
  $ascii['u'] = range(249, 252);
  $ascii['b'] = array(223);
  $ascii['c'] = array(231);
  $ascii['d'] = array(208);
  $ascii['n'] = array(241);
  $ascii['y'] = array(253, 255);
  foreach ($ascii as $key=>$item) {
    $acentos = '';
    foreach ($item AS $codigo) $acentos .= chr($codigo);
    $troca[$key] = '/['.$acentos.']/i';
  	}
  $string = preg_replace(array_values($troca), array_keys($troca), $string);
  if ($slug) {
    $string = preg_replace('/[^a-z0-9]/i', $slug, $string);
    $string = preg_replace('/' . $slug . '{2,}/i', $slug, $string);
    $string = trim($string, $slug);
  	}
  return $string;
	}


function horasSQL($tempo){
	$tempo=$tempo*3600;
	$horas = floor($tempo / 3600);
  $minutos = ($tempo % 3600);
	$segundos=$tempo-(($horas*3600)+($minutos*60));
	return (($horas < 10 ? '0': '').$horas.':'.($minutos < 10 ? '0': '').$minutos.':'.($segundos < 10 ? '0': '').$segundos);
	}

function numero_formato($valor, $casas, $decimal, $milhar){
	if ($valor==(int)$valor) return (int)$valor;
	return number_format($valor, $casas, $decimal, $milhar);
	}


function botoes_ckeditor(){
	global $config;
	if ($config['caixa_texto_padrao']=='caixa_texto_padrao0') return ", {toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],	'/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Link', 'Unlink'],['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']]}";
	elseif ($config['caixa_texto_padrao']=='caixa_texto_padrao1') return ", {toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],	'/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Link', 'Unlink'],['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']]}";
	elseif ($config['caixa_texto_padrao']=='caixa_texto_padrao2') return ", {toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],	'/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']]}";
	else return ", {toolbar: []}";

	}

function botoesCKEditor( $toolbar = null ){
  global $config;
  if( $toolbar === 'caixa_texto_padrao0' || $config['caixa_texto_padrao']=='caixa_texto_padrao0'){
    return "{toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],    '/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Link', 'Unlink'],['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']]}";
    }
  else if($toolbar === 'caixa_texto_padrao1' || $config['caixa_texto_padrao']=='caixa_texto_padrao1'){
    return "{toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],    '/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Link', 'Unlink'],['Image', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']]}";
    }
  else if($toolbar === 'caixa_texto_padrao2' || $config['caixa_texto_padrao']=='caixa_texto_padrao2'){
    return "{toolbar: [['Styles', 'Format', 'Font', 'FontSize'],['TextColor', 'BGColor'],['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo'],['Find', 'Replace', '-', 'SelectAll', '-', 'Scayt'],    '/',['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'RemoveFormat'],['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl'],['Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak']]}";
    }
  else{
      return "{toolbar: []}";
      }
  }

function link_ata_pro($ata_id){
	global $Aplic,$config, $dialogo;
	if (!$ata_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('ata');
		$sql->esqUnir('usuarios', 'usuarios', 'ata_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('ata_titulo, ata_numero, ata_relato');
		$sql->adOnde('ata_id = '.$ata_id);
		$ata = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes da Ata de Reunião</b></td></tr>';
		if ($ata['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$ata['responsavel'].'</td></tr>';
		if ($ata['ata_relato']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Relato</b></td><td>'.$ata['ata_relato'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver os detalhes desta ata de reunião.';
		return dica('Ata', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=atas&a=ata_ver&ata_id='.$ata_id.'\');">'.$ata['ata_titulo'].'</a>'.dicaF();
		}

function link_painel($painel_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $descricao=false) {
	global $Aplic,$config;
	if (!$painel_id) return '&nbsp';
	if (popup_ativado('painel')){
		$sql = new BDConsulta;
		$sql->adTabela('painel', 'p');
		$sql->esqUnir('usuarios', 'usuarios', 'painel_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = painel_cia');
		$sql->adCampo('cia_nome, p.painel_nome, p.painel_id, p.painel_descricao, painel_cor, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adOnde('p.painel_id = '.(int)$painel_id);
		$p = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do painel</b></td></tr>';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
		if ($p['painel_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['painel_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes deste painel.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>'.dicaF();
		elseif ($sem_texto) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');">';
		elseif ($cor && $curto) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');" style="background-color:#'.$p['painel_cor'].'; color:#'.melhorCor($p['painel_cor']).'">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>'.dicaF();
		elseif ($cor) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');" style="background-color:#'.$p['painel_cor'].'; color:#'.melhorCor($p['painel_cor']).'">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>'.dicaF();
		else return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('painel', 'p');
		$sql->adCampo('painel_cor, painel_nome');
		$sql->adOnde('p.painel_id = '.(int)$painel_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');" style="background-color:#'.$p['painel_cor'].'; color:#'.melhorCor($p['painel_cor']).'">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');" style="background-color:#'.$p['painel_cor'].'; color:#'.melhorCor($p['painel_cor']).'">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_pro_ver&jquery=1&painel_id='.$painel_id.'\');">'.$p['painel_nome'].($descricao && $p['painel_nome'] && $p['painel_descricao'] ? ' - ' : '').($descricao && $p['painel_descricao'] ? $p['painel_descricao'] : '').'</a>';
		}
	}




function permiteEditarPainel($acesso=0, $painel_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('painel_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_usuario_usuario)');
			$sql->adOnde('painel_usuario_usuario='.$Aplic->usuario_id.' AND painel_usuario_painel='.$painel_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.$painel_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 2:
			// participante
			$sql->adTabela('painel_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_usuario_usuario)');
			$sql->adOnde('painel_usuario_usuario='.$Aplic->usuario_id.' AND painel_usuario_painel='.$painel_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.$painel_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.$painel_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		case 4:
			// protegido II
			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.$painel_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPainel($acesso=0, $painel_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_id) return true;//sem painel e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('painel_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_usuario_usuario)');
			$sql->adOnde('painel_usuario_usuario='.$Aplic->usuario_id.' AND painel_usuario_painel='.$painel_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.$painel_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_usuario_usuario)');
			$sql->adOnde('painel_usuario_usuario='.$Aplic->usuario_id.' AND painel_usuario_painel='.$painel_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel');
			$sql->adCampo('painel_responsavel');
			$sql->adOnde('painel_id = '.$painel_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		}
	return $valorRetorno;
	}


function link_painel_slideshow($painel_slideshow_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $descricao=false) {
	global $Aplic,$config;
	if (!$painel_slideshow_id) return '&nbsp';
	if (popup_ativado('painel_slideshow')){
		$sql = new BDConsulta;
		$sql->adTabela('painel_slideshow', 'p');
		$sql->esqUnir('usuarios', 'usuarios', 'painel_slideshow_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = painel_slideshow_cia');
		$sql->adCampo('cia_nome, p.painel_slideshow_nome, p.painel_slideshow_id, p.painel_slideshow_descricao, painel_slideshow_cor, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adOnde('p.painel_slideshow_id = '.(int)$painel_slideshow_id);
		$p = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do painel de composição</b></td></tr>';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
		if ($p['painel_slideshow_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['painel_slideshow_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes deste painel de composição.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>'.dicaF();
		elseif ($sem_texto) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');">';
		elseif ($cor && $curto) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');" style="background-color:#'.$p['painel_slideshow_cor'].'; color:#'.melhorCor($p['painel_slideshow_cor']).'">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>'.dicaF();
		elseif ($cor) return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');" style="background-color:#'.$p['painel_slideshow_cor'].'; color:#'.melhorCor($p['painel_slideshow_cor']).'">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>'.dicaF();
		else return dica('Painel',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('painel_slideshow', 'p');
		$sql->adCampo('painel_slideshow_cor, painel_slideshow_nome');
		$sql->adOnde('p.painel_slideshow_id = '.(int)$painel_slideshow_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');" style="background-color:#'.$p['painel_slideshow_cor'].'; color:#'.melhorCor($p['painel_slideshow_cor']).'">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');" style="background-color:#'.$p['painel_slideshow_cor'].'; color:#'.melhorCor($p['painel_slideshow_cor']).'">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_slideshow_pro_ver&jquery=1&painel_slideshow_id='.(int)$painel_slideshow_id.'\');">'.$p['painel_slideshow_nome'].($descricao && $p['painel_slideshow_nome'] && $p['painel_slideshow_descricao'] ? ' - ' : '').($descricao && $p['painel_slideshow_descricao'] ? $p['painel_slideshow_descricao'] : '').'</a>';
		}
	}




function permiteEditarPainelSlideShow($acesso=0, $painel_slideshow_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_slideshow_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('painel_slideshow_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_slideshow_usuario_usuario)');
			$sql->adOnde('painel_slideshow_usuario_usuario='.(int)$Aplic->usuario_id.' AND painel_slideshow_usuario_slideshow='.(int)$painel_slideshow_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$painel_slideshow_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 2:
			// participante
			$sql->adTabela('painel_slideshow_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_slideshow_usuario_usuario)');
			$sql->adOnde('painel_slideshow_usuario_usuario='.(int)$Aplic->usuario_id.' AND painel_slideshow_usuario_slideshow='.(int)$painel_slideshow_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$painel_slideshow_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$painel_slideshow_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		case 4:
			// protegido II
			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$painel_slideshow_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPainelSlideShow($acesso=0, $painel_slideshow_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_slideshow_id) return true;//sem painel_slideshow e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('painel_slideshow_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_slideshow_usuario_usuario)');
			$sql->adOnde('painel_slideshow_usuario_usuario='.(int)$Aplic->usuario_id.' AND painel_slideshow_usuario_slideshow='.(int)$painel_slideshow_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$painel_slideshow_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_slideshow_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_slideshow_usuario_usuario)');
			$sql->adOnde('painel_slideshow_usuario_usuario='.(int)$Aplic->usuario_id.' AND painel_slideshow_usuario_slideshow='.(int)$painel_slideshow_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_slideshow');
			$sql->adCampo('painel_slideshow_responsavel');
			$sql->adOnde('painel_slideshow_id = '.(int)$painel_slideshow_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		}
	return $valorRetorno;
	}


function link_painel_composicao($painel_composicao_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $descricao=false) {
	global $Aplic,$config;
	if (!$painel_composicao_id) return '&nbsp';
	if (popup_ativado('painel_composicao')){
		$sql = new BDConsulta;
		$sql->adTabela('painel_composicao', 'p');
		$sql->esqUnir('usuarios', 'usuarios', 'painel_composicao_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = painel_composicao_cia');
		$sql->adCampo('cia_nome, p.painel_composicao_nome, p.painel_composicao_id, p.painel_composicao_descricao, painel_composicao_cor, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adOnde('p.painel_composicao_id = '.(int)$painel_composicao_id);
		$p = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do painel de composição</b></td></tr>';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
		if ($p['painel_composicao_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['painel_composicao_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes desta composição de painéis.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica('Composição de Painéis',$dentro,'','',true).'<a href="javascript:void(0);">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>'.dicaF();
		elseif ($sem_texto) return dica('Composição de Painéis',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');">';
		elseif ($cor && $curto) return dica('Composição de Painéis',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');" style="background-color:#'.$p['painel_composicao_cor'].'; color:#'.melhorCor($p['painel_composicao_cor']).'">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>'.dicaF();
		elseif ($cor) return dica('Composição de Painéis',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');" style="background-color:#'.$p['painel_composicao_cor'].'; color:#'.melhorCor($p['painel_composicao_cor']).'">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>'.dicaF();
		else return dica('Composição de Painéis',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('painel_composicao', 'p');
		$sql->adCampo('painel_composicao_cor, painel_composicao_nome');
		$sql->adOnde('p.painel_composicao_id = '.(int)$painel_composicao_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');" style="background-color:#'.$p['painel_composicao_cor'].'; color:#'.melhorCor($p['painel_composicao_cor']).'">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');" style="background-color:#'.$p['painel_composicao_cor'].'; color:#'.melhorCor($p['painel_composicao_cor']).'">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=painel_composicao_pro_ver&jquery=1&painel_composicao_id='.$painel_composicao_id.'\');">'.$p['painel_composicao_nome'].($descricao && $p['painel_composicao_nome'] && $p['painel_composicao_descricao'] ? ' - ' : '').($descricao && $p['painel_composicao_descricao'] ? $p['painel_composicao_descricao'] : '').'</a>';
		}
	}




function permiteEditarPainelComposicao($acesso=0, $painel_composicao_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_composicao_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('painel_composicao_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_composicao_usuario_usuario)');
			$sql->adOnde('painel_composicao_usuario_usuario='.$Aplic->usuario_id.' AND painel_composicao_usuario_painel_composicao='.$painel_composicao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 2:
			// participante
			$sql->adTabela('painel_composicao_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_composicao_usuario_usuario)');
			$sql->adOnde('painel_composicao_usuario_usuario='.$Aplic->usuario_id.' AND painel_composicao_usuario_painel_composicao='.$painel_composicao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		case 4:
			// protegido II
			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPainelComposicao($acesso=0, $painel_composicao_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_composicao_id) return true;//sem painel_composicao e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('painel_composicao_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_composicao_usuario_usuario)');
			$sql->adOnde('painel_composicao_usuario_usuario='.$Aplic->usuario_id.' AND painel_composicao_usuario_painel_composicao='.$painel_composicao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_composicao_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_composicao_usuario_usuario)');
			$sql->adOnde('painel_composicao_usuario_usuario='.$Aplic->usuario_id.' AND painel_composicao_usuario_painel_composicao='.$painel_composicao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_composicao');
			$sql->adCampo('painel_composicao_responsavel');
			$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		}
	return $valorRetorno;
	}


function link_painel_odometro($painel_odometro_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $descricao=false) {
	global $Aplic,$config;
	if (!$painel_odometro_id) return '&nbsp';
	if (popup_ativado('painel_odometro')){
		$sql = new BDConsulta;
		$sql->adTabela('painel_odometro', 'p');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador','pratica_indicador_id=painel_odometro_indicador');
		$sql->esqUnir('usuarios', 'usuarios', 'painel_odometro_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = painel_odometro_cia');
		$sql->adCampo('cia_nome, painel_odometro_nome, pratica_indicador_nome, p.painel_odometro_id, painel_odometro_cor, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono, painel_odometro_descricao');
		$sql->adOnde('painel_odometro_id = '.(int)$painel_odometro_id);
		$p = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do odômetro</b></td></tr>';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
	 	if ($p['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes deste odômetro.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica('Odômetro',$dentro,'','',true).'<a href="javascript:void(0);">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>'.dicaF();
		elseif ($sem_texto) return dica('Odômetro',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');">';
		elseif ($cor && $curto) return dica('Odômetro',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');" style="background-color:#'.$p['painel_odometro_cor'].'; color:#'.melhorCor($p['painel_odometro_cor']).'">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>'.dicaF();
		elseif ($cor) return dica('Odômetro',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');" style="background-color:#'.$p['painel_odometro_cor'].'; color:#'.melhorCor($p['painel_odometro_cor']).'">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>'.dicaF();
		else return dica('Odômetro',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('painel_odometro', 'p');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador','pratica_indicador_id=painel_odometro_indicador');
		$sql->adCampo('painel_odometro_cor, pratica_indicador_nome');
		$sql->adOnde('p.painel_odometro_id = '.(int)$painel_odometro_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');" style="background-color:#'.$p['painel_odometro_cor'].'; color:#'.melhorCor($p['painel_odometro_cor']).'">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');" style="background-color:#'.$p['painel_odometro_cor'].'; color:#'.melhorCor($p['painel_odometro_cor']).'">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=odometro_pro_ver&jquery=1&painel_odometro_id='.$painel_odometro_id.'\');">'.($p['painel_odometro_nome'] ? $p['painel_odometro_nome'] : $p['pratica_indicador_nome']).'</a>';
		}
	}




function permiteEditarOdometro($acesso=0, $painel_odometro_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_odometro_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('painel_odometro_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_odometro_usuario_usuario)');
			$sql->adOnde('painel_odometro_usuario_usuario='.$Aplic->usuario_id.' AND painel_odometro_usuario_painel_odometro='.$painel_odometro_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 2:
			// participante
			$sql->adTabela('painel_odometro_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_odometro_usuario_usuario)');
			$sql->adOnde('painel_odometro_usuario_usuario='.$Aplic->usuario_id.' AND painel_odometro_usuario_painel_odometro='.$painel_odometro_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		case 4:
			// protegido II
			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel == $Aplic->usuario_id);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarOdometro($acesso=0, $painel_odometro_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$painel_odometro_id) return true;//sem painel_odometro e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('painel_odometro_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_odometro_usuario_usuario)');
			$sql->adOnde('painel_odometro_usuario_usuario='.$Aplic->usuario_id.' AND painel_odometro_usuario_painel_odometro='.$painel_odometro_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		case 3:
			// privado
			$sql->adTabela('painel_odometro_usuario');
			$sql->adCampo('COUNT(DISTINCT painel_odometro_usuario_usuario)');
			$sql->adOnde('painel_odometro_usuario_usuario='.$Aplic->usuario_id.' AND painel_odometro_usuario_painel_odometro='.$painel_odometro_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('painel_odometro');
			$sql->adCampo('painel_odometro_responsavel');
			$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel == $Aplic->usuario_id);
			break;
		}
	return $valorRetorno;
	}



function link_agenda($agenda_id){
	global $Aplic,$config;
	if (!$agenda_id) return '&nbsp';
		$q = new BDConsulta;
		$q->adTabela('agenda');
		$q->esqUnir('usuarios', 'usuarios', 'agenda_dono = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$q->adCampo('agenda_titulo, agenda_descricao');
		$q->adOnde('agenda_id = '.$agenda_id);
		$agenda = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($agenda['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$agenda['responsavel'].'</td></tr>';
		if ($agenda['agenda_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$agenda['agenda_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este agenda.';
		return dica('Compromisso', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=agenda_ver&agenda_id='.$agenda_id.'\');">'.$agenda['agenda_titulo'].'</a>'.dicaF();
		}

function link_checklist($checklist_id){
	global $Aplic,$config;
	if (!$checklist_id) return '&nbsp';
		$q = new BDConsulta;
		$q->adTabela('checklist');
		$q->esqUnir('usuarios', 'usuarios', 'checklist_responsavel = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$q->adCampo('checklist_nome, checklist_descricao');
		$q->adOnde('checklist_id = '.$checklist_id);
		$checklist = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($checklist['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$checklist['responsavel'].'</td></tr>';
		if ($checklist['checklist_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$checklist['checklist_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este checklist.';
		return dica('Checklist', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=checklist_ver&checklist_id='.$checklist_id.'\');">'.$checklist['checklist_nome'].'</a>'.dicaF();
		}

function link_brainstorm_pro($brainstorm_id){
	global $Aplic,$config;
	if (!$brainstorm_id) return '&nbsp';
		$q = new BDConsulta;
		$q->adTabela('brainstorm');
		$q->esqUnir('usuarios', 'usuarios', 'brainstorm_responsavel = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$q->adCampo('brainstorm_nome, brainstorm_descricao');
		$q->adOnde('brainstorm_id = '.$brainstorm_id);
		$brainstorm = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($brainstorm['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$brainstorm['responsavel'].'</td></tr>';
		if ($brainstorm['brainstorm_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$brainstorm['brainstorm_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este brainstorm.';
		return dica('Brainstorm', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=brainstorm_pro_ver&brainstorm_id='.$brainstorm_id.'\');">'.$brainstorm['brainstorm_nome'].'</a>'.dicaF();
		}

function link_causa_efeito_pro($causa_efeito_id){
	global $Aplic,$config;
	if (!$causa_efeito_id) return '&nbsp';
		$q = new BDConsulta;
		$q->adTabela('causa_efeito');
		$q->esqUnir('usuarios', 'usuarios', 'causa_efeito_responsavel = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$q->adCampo('causa_efeito_nome, causa_efeito_descricao');
		$q->adOnde('causa_efeito_id = '.$causa_efeito_id);
		$causa_efeito = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($causa_efeito['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$causa_efeito['responsavel'].'</td></tr>';
		if ($causa_efeito['causa_efeito_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$causa_efeito['causa_efeito_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver os detalhes desta causa-efeito.';
		return dica('Diagrama de Causa-Efeito', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=causa_efeito_pro_ver&causa_efeito_id='.$causa_efeito_id.'\');">'.$causa_efeito['causa_efeito_nome'].'</a>'.dicaF();
		}

function link_gut_pro($gut_id){
	global $Aplic,$config;
	if (!$gut_id) return '&nbsp';
		$q = new BDConsulta;
		$q->adTabela('gut');
		$q->esqUnir('usuarios', 'usuarios', 'gut_responsavel = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$q->adCampo('gut_nome, gut_descricao');
		$q->adOnde('gut_id = '.$gut_id);
		$gut = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($gut['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$gut['responsavel'].'</td></tr>';
		if ($gut['gut_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$gut['gut_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver os detalhes desta matriz GUT.';
		return dica('Matriz G.U.T.', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=gut_pro_ver&gut_id='.$gut_id.'\');">'.$gut['gut_nome'].'</a>'.dicaF();
		}

function enviar_email($usuario_id, $contato_id, $contato_email, $titulo='', $corpo='', $endereco=''){
	global $config, $Aplic, $localidade_tipo_caract;

	if (!$usuario_id && !$contato_id && !$contato_email) return false;

	if ($contato_id && !$contato_email){
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->adOnde('contato_id='.(int)$contato_id);
		$sql->adCampo('contato_email');
		$contato_email=$sql->Resultado();
		$sql->limpar();
		if (!$contato_email) return false;
		}

	elseif ($usuario_id && !$contato_email){
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'usuario_contato=contato_id');
		$sql->adOnde('usuario_id='.(int)$usuario_id);
		$sql->adCampo('contato_email');
		$contato_email=$sql->Resultado();
		$sql->limpar();
		if (!$contato_email) return false;
		}

	if (!$usuario_id && $contato_id){
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->adOnde('usuario_contato='.(int)$contato_id);
		$sql->adCampo('usuario_id');
		$usuario_id=$sql->Resultado();
		$sql->limpar();
		}

	if ($config['email_ativo'] && $config['email_externo_auto']) {
		if ($Aplic->profissional){
			require_once ($Aplic->getClasseSistema('libmail'));
			require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
			$email = new Mail;
			$email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                $email->ResponderPara($Aplic->usuario_email);
                }
            else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                $email->ResponderPara($Aplic->usuario_email2);
                }

			if ($email->EmailValido($contato_email)) {
				$email->Assunto($titulo, ($localidade_tipo_caract ? $localidade_tipo_caract : 'iso-8859-1'));
				$endereco=(link_email_externo($usuario_id, $endereco));
				$corpo_email1=$corpo.($usuario_id ? '<br><a href="'.$endereco.'"><b>Clique para acessar</b></a>' : '');
				$email->Corpo($corpo_email1, ($localidade_tipo_caract ? $localidade_tipo_caract : 'iso-8859-1'));
				$email->Para($contato_email, true);
				$email->Enviar();
				}
			}
		else{
			$email = new Mail;
            $email->De($config['email'], $Aplic->usuario_nome);

            if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
                $email->ResponderPara($Aplic->usuario_email);
                }
            else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
                $email->ResponderPara($Aplic->usuario_email2);
                }

			$email->Assunto($titulo, $localidade_tipo_caract);
			$email->Corpo($corpo_email, ($localidade_tipo_caract ? $localidade_tipo_caract : 'iso-8859-1'));
			if ($email->EmailValido($contato_email)) $email->Para($contato_email, true);
			$email->Enviar();
			}
		}
	}

function tarefas_subordinadas($tarefa_id, &$vetor=array()){
	global $baseline_id;
	$vetor[$tarefa_id]=$tarefa_id;
	$sql = new BDConsulta;
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	if ($baseline_id) $sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_id !='.(int)$tarefa_id);
	$sql->adOnde('tarefa_superior ='.(int)$tarefa_id);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $valor) tarefas_subordinadas($valor, $vetor);
	}

function tarefas_subordinadas_sem_pai($tarefa_id, &$vetor=array()){
	global $baseline_id;
	$sql = new BDConsulta;
	$sql->adTabela(($baseline_id ? 'baseline_' : '').'tarefas','tarefas');
	if ($baseline_id) $sql->adOnde('tarefas.baseline_id='.(int)$baseline_id);
	$sql->adCampo('tarefa_id');
	$sql->adOnde('tarefa_id !='.(int)$tarefa_id);
	$sql->adOnde('tarefa_superior ='.(int)$tarefa_id);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $valor) {
		$vetor[$valor]=$valor;
		tarefas_subordinadas_sem_pai($valor, $vetor);
		}
	}



function inserir_historico($comando_sql, $tipo){
	global $Aplic, $m, $a, $u, $_SERVER, $config;

    $reg = $config['registrar_mudancas'];
    $config['registrar_mudancas'] = false;

  //isto acontece quando do update quando registrando historico, pois update é sem passar pelo index
  if(!isset($Aplic)) return;

	$sql = new BDConsulta;
	$sql->adTabela('registro');
	$sql->adInserir('registro_acao', $tipo);
	$sql->adInserir('registro_sql', $comando_sql);
	if (isset($Aplic->usuario_id) && $Aplic->usuario_id) $sql->adInserir('registro_usuario', $Aplic->usuario_id);
	if (isset($Aplic->usuario_cia) && $Aplic->usuario_cia) $sql->adInserir('registro_cia', $Aplic->usuario_cia);
	$sql->adInserir('registro_m', $m);
	$sql->adInserir('registro_a', $a);
	$sql->adInserir('registro_u', $u);
	$sql->adInserir('registro_data', date('Y-m-d H:i:s'));
	if (isset($_SERVER['REMOTE_ADDR'])) $sql->adInserir('registro_ip', previnirXSS($_SERVER['REMOTE_ADDR']));
	$sql->exec();
	$sql->Limpar();
    $config['registrar_mudancas'] = $reg;
	}


function br2nl($texto){
	return str_replace("<br />", "", $texto);
	}

function logo_organizacao($cia_id=0){
	global $Aplic, $config;
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	$sql->adTabela('cias');
	$sql->adCampo('cia_logo');
	$sql->adOnde('cia_id = '.(int)$cia_id);
	$endereco=$sql->resultado();
	$sql->Limpar();
	return ($endereco ? '<img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/organizacoes/'.$endereco.'" alt="" border=0 />' : '');
	}

function verificaAdministrador($usuario_id = null){
  global $Aplic;
  if (!$usuario_id) $usuario_id = $Aplic->usuario_id;
	$sql = new BDConsulta();

	/*
  //se foi negado acesso a alguma parte não é mais superusuário

  $sql->adTabela('perfil_acesso');
  $sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
  $sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
  $sql->adCampo('count(perfil_acesso_id)');
  $sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
  $sql->adOnde('perfil_acesso_negar = 1');
  $negado=$sql->Resultado();
  $sql->limpar();
  if ($negado) return false;
	*/

  //verifica se pode editar modulo de sistema
  $sql->adTabela('perfil_acesso');
  $sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
  $sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
  $sql->adCampo('perfil_acesso_editar');
  $sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\'');
  $sql->adOnde('perfil_acesso_modulo = \'sistema\' OR perfil_acesso_modulo =\'todos\' OR perfil_acesso_modulo = \'admin\'');
  $sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
  $sql->adOnde('perfil_acesso_negar = 0');
  $achado=$sql->carregarColuna();
  $sql->Limpar();
  return in_array(1, $achado);
  }


function checarModulo($m=null, $acesso='acesso', $usuario_id=null, $submodulo=null){
	global $Aplic;
  $superadmin = false;
  if (!$usuario_id){
    $usuario_id = $Aplic->usuario_id;
    $superadmin = $Aplic->usuario_super_admin;
    }
  else $superadmin = verificaAdministrador($usuario_id);
  if($superadmin) return true;
	$sql = new BDConsulta;
	//checar se é negado
	$sql->adTabela('perfil_acesso');
	$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
	$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
	$sql->adCampo('perfil_acesso_'.$acesso);
	$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
	$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
	$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
	$sql->adOnde('perfil_acesso_negar = 1');
	$achado=$sql->carregarColuna();
	$sql->Limpar();
	if (in_array(1, $achado)) return false;
	$sql->adTabela('perfil_acesso');
	$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
	$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
	$sql->adCampo('perfil_acesso_'.$acesso);
	$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
	$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
	$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
	$sql->adOnde('perfil_acesso_negar = 0');
	$achado=$sql->carregarColuna();
	$sql->Limpar();
	return in_array(1, $achado);
	}

function listaPermissoes($m='', $submodulo=null, $usuario_id=null){
	global $Aplic;
	$superadmin = false;

  if (!$usuario_id){
    $usuario_id = $Aplic->usuario_id;
    $superadmin = $Aplic->usuario_super_admin;
    }
  else $superadmin = verificaAdministrador($usuario_id);

  if($superadmin) return array(true, true, true, true, true);

	$acesso=$Aplic->checarModulo($m, 'acesso', $usuario_id, $submodulo);
	$editar=$Aplic->checarModulo($m, 'editar', $usuario_id, $submodulo);
	$adicionar=$Aplic->checarModulo($m, 'adicionar', $usuario_id, $submodulo);
	$excluir=$Aplic->checarModulo($m, 'excluir', $usuario_id, $submodulo);
	$aprovar=$Aplic->checarModulo($m, 'aprovar', $usuario_id, $submodulo);
	return array($acesso, $editar, $adicionar, $excluir, $aprovar);


	/*
	$sql = new BDConsulta;
	$sql->adTabela('perfil_acesso');
	$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
	$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
	$sql->adCampo('perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
	$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
	$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
	$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
	$sql->adOnde('perfil_acesso_negar = 1');
	$negados=$sql->lista();
	$sql->Limpar();

	$negado=array();
	foreach($negados as $linha) {
		if ($linha['acesso']) $negado['acesso']=true;
		if ($linha['editar'])$negado['editar']=true;
		if ($linha['adicionar']) $negado['adicionar']=true;
		if ($linha['excluir']) $negado['excluir']=true;
		if ($linha['aprovar']) $negado['aprovar']=true;
		}
	$sql->adTabela('perfil_acesso');
	$sql->esqUnir('perfil','perfil','perfil_id=perfil_acesso_perfil');
	$sql->esqUnir('perfil_usuario','perfil_usuario','perfil_usuario_perfil=perfil_id');
	$sql->adCampo('perfil_acesso_acesso AS acesso, perfil_acesso_editar AS editar, perfil_acesso_adicionar AS adicionar, perfil_acesso_excluir AS excluir, perfil_acesso_aprovar AS aprovar');
	$sql->adOnde('perfil_acesso_objeto IS NULL OR perfil_acesso_objeto=\'\''.($submodulo ? ' OR perfil_acesso_objeto = \''.$submodulo.'\'' : ''));
	$sql->adOnde('perfil_acesso_modulo = \''.$m.'\' OR perfil_acesso_modulo =\'todos\''.($m!='admin' && $m!='sistema' ? ' OR perfil_acesso_modulo =\'nao_admin\'' : ' OR perfil_acesso_modulo =\'admin\''));
	$sql->adOnde('perfil_usuario_usuario = '.(int)$usuario_id);
	$sql->adOnde('perfil_acesso_negar = 0');
	$achados=$sql->lista();
	$sql->Limpar();
	$saida=array(false, false, false, false, false);
	foreach($achados as $linha) {
		if ($linha['acesso'] && !isset($negado['acesso'])) $saida[0]=true;
		if ($linha['editar'] && !isset($negado['editar'])) $saida[1]=true;
		if ($linha['adicionar'] && !isset($negado['adicionar'])) $saida[2]=true;
		if ($linha['excluir'] && !isset($negado['excluir'])) $saida[3]=true;
		if ($linha['aprovar'] && !isset($negado['aprovar'])) $saida[4]=true;
		}
	return $saida;
	*/
	}


function idioma($texto=''){
	global $dicionario;
	return (isset($dicionario[$texto]) ? $dicionario[$texto] : $texto);
	}

function uuid() {
   return sprintf('%04x%04x-%04x-%03x4-%04x-%04x%04x%04x', mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 4095), bindec(substr_replace(sprintf('%016b', mt_rand(0, 65535)), '01', 6, 2)), mt_rand(0, 65535), mt_rand(0, 65535), mt_rand(0, 65535));
	}

function retornar_cor($pontos){
	global $config;

	if ($pontos>=200) $cor=$config['porcentagem_200'];
	elseif ($pontos >= 190) $cor=$config['porcentagem_190_200'];
	elseif ($pontos >= 180) $cor=$config['porcentagem_180_190'];
	elseif ($pontos >= 170) $cor=$config['porcentagem_170_180'];
	elseif ($pontos >= 160) $cor=$config['porcentagem_160_170'];
	elseif ($pontos >= 150) $cor=$config['porcentagem_150_160'];
	elseif ($pontos >= 140) $cor=$config['porcentagem_140_150'];
	elseif ($pontos >= 130) $cor=$config['porcentagem_130_140'];
	elseif ($pontos >= 120) $cor=$config['porcentagem_120_130'];
	elseif ($pontos >= 110) $cor=$config['porcentagem_110_120'];
	elseif ($pontos > 100) $cor=$config['porcentagem_100_110'];
	elseif ($pontos==100) $cor=$config['porcentagem_100'];
	elseif ($pontos >= 90) $cor=$config['porcentagem_90_100'];
	elseif ($pontos >= 80) $cor=$config['porcentagem_80_90'];
	elseif ($pontos >= 70) $cor=$config['porcentagem_70_80'];
	elseif ($pontos >= 60) $cor=$config['porcentagem_60_70'];
	elseif ($pontos >= 50) $cor=$config['porcentagem_50_60'];
	elseif ($pontos >= 40) $cor=$config['porcentagem_40_50'];
	elseif ($pontos >= 30) $cor=$config['porcentagem_30_40'];
	elseif ($pontos >= 20) $cor=$config['porcentagem_20_30'];
	elseif ($pontos >= 10) $cor=$config['porcentagem_10_20'];
	elseif ($pontos > 0) $cor=$config['porcentagem_0_10'];
	else $cor=$config['porcentagem_0'];
	return $cor;
	}


function municipio_nome($municipio_id=''){
	if ($municipio_id){
		$sql = new BDConsulta;
		$sql->adTabela('municipios');
		$sql->adCampo('municipio_nome');
		$sql->adOnde('municipio_id = "'.$municipio_id.'"');
		return $sql->Resultado();
		$sql->limpar();
		}
	else	return '&nbsp;';
}

function inserir_NUP($numero, $organizacao){
	if (strlen($numero)<=6 && strlen($organizacao)==5){
		$v11=array(0=>'1', 1=>'0', 2=>'9', 3=>'8', 4=>'7', 5=>'6', 6=>'5', 7=>'4', 8=>'3', 9=>'2', 10=>'1');
		for($i=strlen($numero); $i<6;$i++)$numero='0'.$numero;
		$vetor=$organizacao.$numero.date('Y');
		$valor=0;
		for($i=0; $i<15 ; $i++) $valor=$valor+((int)$vetor[(14-$i)]*($i+2));
		$verificador1=$v11[($valor % 11)];
		$vetor.=$v11[($valor % 11)];
		$valor=0;
		for($i=0; $i<16 ; $i++) {
			$valor=$valor+((int)$vetor[(15-$i)]*($i+2));
			}
		$verificador2=$v11[($valor % 11)];
		return $organizacao.'.'.$numero.'/'.date('Y').'-'.$verificador1.$verificador2;
		}
	else return '_______';
	}

function transforma_vazio_em_nulo(&$vetor){
	foreach ($vetor as $chave=> $valor){
		if ($valor==='') $vetor[$chave]=null;
		}
	}

function vetor_com_pai_generico($tabela='', $campo_chave='', $campo_nome='', $campo_pai='', $chave_id='', $cia_id=0, $campo_cia='', $mostrar_cia=false, $ajax=FALSE, $campo_acesso='', $tipo='', $vazio='', $diferente_de=false, $filtro=array(), $esqUnir='', $esqOnde=''){
	$sql = new BDConsulta;
	$vetor=array();
	$espacamento='';
	if ($chave_id) {
		//procurar o pai
		$sql->adTabela($tabela);
		$sql->adCampo($campo_pai);
		$sql->adOnde($campo_chave.'='.$chave_id);
		$id_pai=$sql->resultado();
		$sql->limpar();
		if ($id_pai && $id_pai!=$chave_id){
			$sql->adTabela($tabela);
			if ($mostrar_cia){
				$sql->esqUnir('cias','cias',$campo_cia.'=cia_id');
				$sql->adCampo($campo_chave);
				$sql->adCampo('concatenar_tres('.$campo_nome.', \' - \', cia_nome) AS nome');
				}
			else $sql->adCampo($campo_nome.' AS nome');
			$sql->adCampo($campo_chave);
			$sql->adOnde($campo_chave.'='.$id_pai);
			if ($diferente_de) $sql->adOnde($campo_chave.'!='.$diferente_de);
			$linha=$sql->Linha();
			$sql->limpar();
			if (isset($linha['nome']) && isset($linha[$campo_chave])) $vetor[$linha[$campo_chave]]=($ajax ? utf8_encode($linha['nome']) : $linha['nome']);
			else $vetor['']=($ajax ? utf8_encode('Retornar à lista superior') : 'Retornar à lista superior');
			}
		else $vetor['']=($ajax ? utf8_encode('Retornar à lista superior') : 'Retornar à lista superior');
		$espacamento='&nbsp;&nbsp;';
		}
	else $vetor[null]=$vazio;


	if ($chave_id){
		$sql->adTabela($tabela);
		if ($mostrar_cia){
			$sql->esqUnir('cias','cias',$campo_cia.'=cia_id');
			$sql->adCampo($campo_chave);
			$sql->adCampo('concatenar_tres('.$campo_nome.', \' - \', cia_nome) AS nome');
			}
		else $sql->adCampo($campo_nome.' AS nome');
		$sql->adCampo($campo_chave);
		$sql->adOnde($campo_chave.'='.$chave_id);
		$linha=$sql->Linha();
		$sql->limpar();
		if (isset($linha['nome']) && isset($linha[$campo_chave])) $vetor[$linha[$campo_chave]]=$espacamento.($ajax ? utf8_encode($linha['nome']) : $linha['nome']);
		}

	$sql->adTabela($tabela);
	$sql->adCampo($campo_chave);
	if ($mostrar_cia){
			$sql->esqUnir('cias','cias',$campo_cia.'=cia_id');
			$sql->adCampo($campo_chave);
			$sql->adCampo('concatenar_tres('.$campo_nome.', \' - \', cia_nome) AS nome');
			}
	else $sql->adCampo($campo_nome.' AS nome');
	if ($campo_acesso) $sql->adCampo($campo_acesso);
	if (!$chave_id) $sql->adOnde($campo_pai.' IS NULL OR '.$campo_pai.'=0 OR '.$campo_pai.'='.$campo_chave);
	else $sql->adOnde($campo_chave.'!=\''.$chave_id.'\' AND '.$campo_pai.'='.$chave_id);
	if ($diferente_de) $sql->adOnde($campo_chave.'!='.$diferente_de);
	$sql->adOnde($campo_cia.'='.$cia_id);
	
	if ($esqUnir && $esqOnde) $sql->esqUnir($esqUnir, $esqUnir, $esqOnde);
	if (count($filtro)) foreach ($filtro as $chave => $valor) if ($valor) $sql->adOnde($valor);
	$sql->adOrdem('nome ASC');
	$lista=$sql->Lista();
	$sql->limpar();

	foreach((array)$lista as $linha) {
		if ($tipo=='indicador') $permite=permiteAcessarIndicador($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='checklist') $permite=permiteAcessarChecklist($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='pratica') $permite=permiteAcessarPratica($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='estrategia') $permite=permiteAcessarEstrategia($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='objetivo') $permite=permiteAcessarObjetivo($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='meta') $permite=permiteAcessarMeta($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='dept') $permite=permiteAcessarDept($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='cia') $permite=permiteAcessarCia($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='projeto') $permite=permiteAcessar($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='plano_acao') $permite=permiteAcessarPlanoAcao($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='gut') $permite=permiteAcessarGUT($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='instrumento') $permite=permiteAcessarInstrumento($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='causa_efeito') $permite=permiteAcessarCausa_efeito($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='brainstorm') $permite=permiteAcessarBrainstorm($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='calendario') $permite=permiteAcessarCalendario($chave_id);
		elseif ($tipo=='fator') $permite=permiteAcessarFator($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='demanda') $permite=permiteAcessarDemanda($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='viabilidade') $permite=permiteAcessarViabilidade($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='termo_abertura') $permite=permiteAcessarTermoAbertura($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='perspectiva') $permite=permiteAcessarPerspectiva($linha[$campo_acesso],$linha[$campo_chave]);
		elseif ($tipo=='recurso') $permite=permiteAcessarRecurso($linha[$campo_acesso],$linha[$campo_chave]);
		else $permite=1;
		if ($permite) $vetor[$linha[$campo_chave]]=$espacamento.($chave_id ? '&nbsp;&nbsp;&nbsp;' : '').($ajax ? utf8_encode($linha['nome']) : $linha['nome']);
		}
	return $vetor;
	}


function lista_cias_subordinadas($cia_id, &$vetor=array()){
	$sql = new BDConsulta;
	$sql->adTabela('cias');
	$sql->adCampo('cia_id');
	$sql->adOnde('cia_superior = '.(int)$cia_id);
	$sql->adOnde('cia_id != '.(int)$cia_id);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $cia){
		$vetor[]=$cia;
		 lista_cias_subordinadas($cia, $vetor);
		}
	}

function lista_depts_subordinados($dept_id, &$vetor=array()){
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOnde('dept_id != '.(int)$dept_id);
	$lista=$sql->carregarColuna();
	$sql->limpar();
	foreach($lista as $dept){
		$vetor[]=$dept;
		 lista_depts_subordinados($dept, $vetor);
		}
	}

function cor($tipo='projeto', $id=0) {
	$sql = new BDConsulta;
	switch ($tipo) {
		case 'projeto':
			$sql->adTabela('projetos');
			$sql->adCampo('projeto_cor AS cor');
			$sql->adOnde('projeto_id = '.(int)$id);
			break;
		case 'pratica':
			$sql->adTabela('praticas');
			$sql->adCampo('pratica_cor AS cor');
			$sql->adOnde('pratica_id = '.(int)$id);
			break;
		case 'indicador':
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_cor AS cor');
			$sql->adOnde('pratica_indicador_id = '.(int)$id);
			break;
		case 'estrategia':
			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_cor AS cor');
			$sql->adOnde('pg_estrategia_id = '.(int)$id);
			break;
		case 'perspectiva':
			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_cor AS cor');
			$sql->adOnde('pg_perspectiva_id = '.(int)$id);
			break;
		case 'tema':
			$sql->adTabela('tema');
			$sql->adCampo('tema_cor AS cor');
			$sql->adOnde('tema_id = '.(int)$id);
			break;
		case 'objetivo':
			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_cor AS cor');
			$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$id);
			break;
		case 'acao':
			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_cor AS cor');
			$sql->adOnde('plano_acao_id = '.(int)$id);
			break;
		case 'fator':
			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_cor AS cor');
			$sql->adOnde('pg_fator_critico_id = '.(int)$id);
			break;
		case 'meta':
			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_cor AS cor');
			$sql->adOnde('pg_meta_id = '.(int)$id);
			break;
		case 'demanda':
			$sql->adTabela('demandas');
			$sql->adCampo('demanda_cor AS cor');
			$sql->adOnde('demanda_id = '.(int)$id);
			break;
		}
	$cor = $sql->Resultado();
	$sql->limpar();
	return ($cor ? $cor : 'f4fefe');
	}


function mostratConfigModulo($config) {
	$s = '<table cellspacing="2" cellpadding="2" border="0" class="std" width="50%">';
	$s .= '<tr><th colspan="2">Configuração do Módulo</th></tr>';
	foreach ($config as $k => $v) $s .= '<tr><td width="50%">'.$k.'</td><td width="50%" class="realce">'.$v.'</td></tr>';
	$s .= '</table>';
	return ($s);
	}

function tarefaEstilo_pd($tarefa) {
	$agora = new CData();
	$data_inicio = intval($tarefa['tarefa_inicio']) ? new CData($tarefa['tarefa_inicio']) : null;
	$data_fim = intval($tarefa['tarefa_fim']) ? new CData($tarefa['tarefa_fim']) : null;
	if ($data_inicio && !$data_fim) {
		$data_fim = $data_inicio;
		$data_fim->adSegundos($tarefa['tarefa_duracao'] * $tarefa['tarefa_duracao_tipo'] * SEG_HORA);
		}
	else if (!$data_inicio) return '';
	$estilo = 'class=';
	if ($tarefa['tarefa_percentagem'] == 0) $estilo .= (($agora->before($data_inicio)) ? '"tarefa_futura"' : '"tarefa_naoiniciada"');
	else if ($tarefa['tarefa_percentagem'] == 100) {
			$t = new CTarefa();
			$t->load($tarefa['tarefa_id']);
			$data_fim_atual = new CData(get_data_fim_atual_pd($t->tarefa_id, $t));
			$estilo .= (($data_fim_atual->after($data_fim)) ? '"tarefa_atrasada"' : '"tarefa_feita"');
			}
	else $estilo .= (($agora->after($data_fim)) ? '"tarefa_alemprevisto"' : '"tarefa_iniciada"');
	return $estilo;
	}


function get_data_fim_atual_pd($tarefa_id, $tarefa) {
	global $Aplic;
	$sql = new BDConsulta;
	$mods = $Aplic->getModulosAtivos();
	if (!empty($mods['historico']) && $Aplic->checarModulo('historico', 'acesso')) {
		$sql->adCampo('MAX(historico_data) as data_fim_atual');
		$sql->adTabela('historico');
		$sql->adOnde('historico_tabela=\'tarefas\' AND historico_item='.(int)$tarefa_id);
		}
	else {
		$sql->adCampo('MAX(tarefa_log_data) AS data_fim_atual');
		$sql->adTabela('tarefa_log');
		$sql->adOnde('tarefa_log_tarefa = '.(int)$tarefa_id);
		}
	$tarefa_reg_data_fim = $sql->Resultado();
	$edata = $tarefa_reg_data_fim;
	$edata = ($edata > $tarefa->tarefa_fim || $tarefa->tarefa_percentagem == 100) ? $edata : $tarefa->tarefa_fim;
	return $edata;
	}

function mostrarTarefa_peg(&$a, $nivel = 0, $visao_hoje = false) {
	global $Aplic, $config, $done, $texto_consulta, $tipoDuracao, $usuarioDesig, $mostrarCaixachecarEditar;
	global $tarefa_acesso, $tarefa_prioridade;
	$tipos = getSisValor('TipoTarefa');
	$agora = new CData();
	$tf = $Aplic->getPref('formatohora');
	$df = '%d/%m/%Y';
	$fdf = $df.' '.$tf;
	$done[] = $a['tarefa_id'];
	$data_inicio = intval($a['tarefa_inicio']) ? new CData($a['tarefa_inicio']) : null;
	$data_fim = intval($a['tarefa_fim']) ? new CData($a['tarefa_fim']) : null;
	$ultima_atualizacao = isset($a['last_update']) && intval($a['last_update']) ? new CData($a['last_update']) : null;
	$sinal = 1;
	$estilo = '';
	if ($data_inicio && !$data_fim) $data_fim = new CData();
	$dias = $data_fim ? $agora->dataDiferenca($data_fim) * $sinal : null;
	if ($agora->after($data_inicio) && $a['tarefa_percentagem'] == 0 && $agora->before($data_fim)) $estilo = 'background-color:#ffeebb';
	else if ($agora->after($data_inicio) && $a['tarefa_percentagem'] < 100 && $agora->before($data_fim)) $estilo = 'background-color:#e6eedd';
	else if ($a['tarefa_percentagem'] == 100) $estilo = 'background-color:#aaddaa; color:#00000';
	else if ($agora->after($data_fim) && $a['tarefa_percentagem'] < 100 ) $estilo = 'background-color:#cc6666;color:#ffffff';
	if ($agora->after($data_fim)) $sinal = -1;
	$dias = $agora->dataDiferenca($data_fim)*$sinal;
	$s = '<tr>';
  $s .= '<td nowrap="nowrap" align="center" style="'.$estilo.'">&nbsp;&nbsp;</td>';
	$s .= '<td nowrap="nowrap" align="center" style="font-size:11px" >'.($data_inicio ? $data_inicio->format($df ) : '&nbsp;').'</td>';
	$s .= '<td nowrap="nowrap" align="center" style="font-size:11px" >'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td>';
	$s .= '<td style="font-size:11px">';
	for ($y = 0; $y < $nivel; $y++) {
		if ($y + 1 == $nivel) $s .= '<img src="'.acharImagem('subnivel.gif').'" width="16" height="12" border=0 alt="">';
		else $s .= '<img src="'.acharImagem('shim.gif').'" width="16" height="12" border=0 alt="">';
		}
	$alt = $a['tarefa_descricao'];
	$alt = str_replace('"', "&quot;", $alt);
	$alt = str_replace("\n\r", '<br>', $alt);
	$alt = str_replace("\r\n", '<br>', $alt);
	$alt = str_replace("\r", '<br>', $alt);
	$alt = str_replace("\n", '<br>', $alt);
	if (!$alt)$alt ='&nbsp;';
	$abrir_link = imagem('icones/colapsar.gif');
	if ($a['tarefa_marco'] > 0) $s .= '&nbsp;<b>'.$a["tarefa_nome"].'</b><img src="'.acharImagem('icones/marco.gif').'" border=0 alt="">';
	elseif ($a['tarefa_dinamica'] == '1') $s .= $abrir_link.'<b>'.$a['tarefa_nome'].'</b>';
	else $s .= $a['tarefa_nome'];
	$s .='</td>';
	$s .= '<td style="font-size:11px" align="left" width="400">'.$alt.'</td>';
	$s .= '<td style="font-size:11px" align="left" >'.($a['contato_posto']||$a['contato_nomeguerra']? $a['contato_posto'].' '.$a['contato_nomeguerra'] : '&nbsp;').'</td>';
	$s .= '<td style="font-size:11px" align="left">'.intval($a['tarefa_percentagem']).'%</td>';
	$s .= '</tr>';
	echo $s;
	}


function acharSubordinada_peg(&$tarr, $superior, $nivel = 0) {
	global $projetos;
	$nivel = $nivel + 1;
	$n = count($tarr);
	for ($x = 0; $x < $n; $x++) {
		if ($tarr[$x]['tarefa_superior'] == $superior && $tarr[$x]['tarefa_superior'] != $tarr[$x]['tarefa_id']) {
			mostrarTarefa_peg($tarr[$x], $nivel);
			acharSubordinada_peg($tarr, $tarr[$x]['tarefa_id'], $nivel);
			}
		}
	}


function urlLimpar($str){
	$acentos = array(
	'a' => '/à|á|â|ã|ä|å/',
	'c' => '/ç/',
	'e' => '/è|é|ê|ë/',
	'i' => '/ì|í|î|ï/',
	'n' => '/ñ/',
	'o' => '/ò|ó|ô|õ|ö/',
	'u' => '/ù|ú|û|ü/',
	'y' => '/ý|ÿ/',
	'a.' => '/ª/',
	'o.' => '/º/',
	'A' => '/À|Á|Â|Ã|Ä|Å/',
	'C' => '/Ç/',
	'E' => '/È|É|Ê|Ë/',
	'I' => '/Ì|Í|Î|Ï/',
	'N' => '/Ñ/',
	'O' => '/Ò|Ó|Ô|Õ|Ö/',
	'U' => '/Ù|Ú|Û|Ü/',
	'Y' => '/Ý|Ÿ/',
	'a.' => '/ª/',
	'o.' => '/º/',
	);
	return preg_replace($acentos, array_keys($acentos), $str);
	}

function converte_texto_grafico($texto){
	global $localidade_tipo_caract;
	$texto=nl2br($texto);
	$texto=html_entity_decode($texto, ENT_COMPAT, $localidade_tipo_caract);
	$texto=str_replace('<p>','', $texto);
	$texto=str_replace('</p>','', $texto);
	$texto=str_replace('<br />','', $texto);
	$texto=str_replace('<br>','', $texto);
	$texto=str_replace('"','', $texto);
	$texto=str_replace("\n",'', $texto);
	$texto=str_replace("\r",'', $texto);
		$texto=str_replace('\'','', $texto);
	return $texto;
	}

function float_americano($valor){
	$valor=str_replace('.', '',$valor);
	$valor=str_replace(',', '.',$valor);
	return $valor;
	}

function float_brasileiro($valor){
	$valor=str_replace('.', ',',$valor);
	return $valor;
	}

function vetor_campo_sistema($campo='', $chave_id='', $ajax=FALSE, $projeto_id=null){
	$sql = new BDConsulta;
	$vetor=array();
	if ($chave_id) $vetor['']=($ajax ? utf8_encode('Retornar à lista superior') : 'Retornar à lista superior');
	else $vetor['']='';
	if ($chave_id){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_valor_id=\''.$chave_id.'\'');
		$sql->adOnde('sisvalor_titulo=\''.$campo.'\'');

		if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
		else  $sql->adOnde('sisvalor_projeto IS NULL');

		$linha=$sql->Linha();
		$sql->limpar();
		if (isset($linha['sisvalor_valor_id']) && isset($linha['sisvalor_valor'])) $vetor[$linha['sisvalor_valor_id']]=($ajax ? utf8_encode($linha['sisvalor_valor']) : $linha['sisvalor_valor']);
		}
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	if (!$chave_id) $sql->adOnde('sisvalor_chave_id_pai IS NULL OR sisvalor_chave_id_pai=sisvalor_valor_id');
	else $sql->adOnde('sisvalor_valor_id!=\''.$chave_id.'\' AND sisvalor_chave_id_pai=\''.$chave_id.'\'');
	$sql->adOnde('sisvalor_titulo=\''.$campo.'\'');

	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
	else  $sql->adOnde('sisvalor_projeto IS NULL');

	$sql->adOrdem('sisvalor_valor_id ASC');
	$lista=$sql->Lista();
	$sql->limpar();
	foreach((array)$lista as $linha) $vetor[$linha['sisvalor_valor_id']]=($chave_id ? '&nbsp;&nbsp;&nbsp;' : '').($ajax ? utf8_encode($linha['sisvalor_valor']) : $linha['sisvalor_valor']);
	return $vetor;
	}


function vetor_nd($nd_item_subitem='', $ajax=FALSE, $projeto_id=null, $nd_classe=3, $nd_grupo='', $nd_subgrupo='', $nd_elemento_subelemento='', $chave_nd_id=false){
	global $Aplic;
	$sql = new BDConsulta;
	if ($Aplic->profissional){
		$sql->adTabela('nd');
		if ($chave_nd_id) $sql->adCampo('nd_id AS sisvalor_valor_id, concatenar_tres(nd_item_subitem, \' - \', nd_texto) AS sisvalor_valor');
		else $sql->adCampo('nd_item_subitem AS sisvalor_valor_id, concatenar_tres(nd_item_subitem, \' - \', nd_texto) AS sisvalor_valor');

		if (!$nd_item_subitem) $sql->adOnde('nd_pai IS NULL');
		else $sql->adOnde('nd_item=\''.substr($nd_item_subitem,0,2).'\'');
		$sql->adOnde('nd_classe='.(int)$nd_classe);
		$sql->adOnde('nd_grupo='.(int)$nd_grupo);
		$sql->adOnde('nd_subgrupo='.(int)$nd_subgrupo);
		$sql->adOnde('nd_elemento_subelemento=\''.$nd_elemento_subelemento.'\'');
		$sql->adOrdem('nd_item_subitem ASC');
		$lista=$sql->Lista();
		$sql->limpar();
		}
	else {
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
		if (!$nd_item_subitem) $sql->adOnde('sisvalor_chave_id_pai IS NULL OR sisvalor_chave_id_pai=sisvalor_valor_id');
		else $sql->adOnde('sisvalor_valor_id=\''.substr($nd_item_subitem,0,2).'.00\' OR sisvalor_chave_id_pai=\''.substr($nd_item_subitem,0,2).'.00\'');
		$sql->adOnde('sisvalor_titulo=\'ND\'');
		if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
		else  $sql->adOnde('sisvalor_projeto IS NULL');
		$sql->adOrdem('sisvalor_valor_id ASC');
		$lista=$sql->Lista();
		$sql->limpar();
		}

	$vetor=array();
	if ($nd_item_subitem && count($lista)) $vetor['']='Retornar a lista de elementos de despesa';
	else $vetor['']='';
	foreach($lista as $linha) $vetor[$linha['sisvalor_valor_id']]=($ajax ? utf8_encode($linha['sisvalor_valor']) : $linha['sisvalor_valor']);
	return $vetor;
	}


function getSisValorND(){
	global $Aplic;
	if ($Aplic->profissional){
		$sql = new BDConsulta;
		$sql->adTabela('nd');
		$sql->adCampo('nd_item_subitem, concatenar_tres(nd_item_subitem, \' - \', nd_texto) AS sisvalor_valor');
		$sql->adOnde('nd_classe=3');
		$sql->adOrdem('nd_item_subitem ASC');
		$retorno=$sql->listaVetorChave('nd_item_subitem','sisvalor_valor');
		$sql->limpar();
		}
	else $retorno=getSisValor('ND');

	return $retorno;
	}

function vetor_chavepai($tipo='', $chave='', $ajax=FALSE, $permite_vazio=false, $projeto_id=null){
	$sql = new BDConsulta;
	$vetor=array();

	if (!$chave || $permite_vazio) $vetor['']='';
	$espaco='';
	if($chave){
		$sql->adTabela('sisvalores');
		$sql->adCampo('sisvalor_chave_id_pai, sisvalor_valor_id, sisvalor_valor');
		$sql->adOnde('sisvalor_valor_id=\''.$chave.'\'');
		$sql->adOnde('sisvalor_titulo=\''.$tipo.'\'');

		if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
		else  $sql->adOnde('sisvalor_projeto IS NULL');

		$atual=$sql->linha();
		$sql->limpar();

		//pai
		if ($atual['sisvalor_chave_id_pai']){
			$sql->adTabela('sisvalores');
			$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
			$sql->adOnde('sisvalor_valor_id=\''.$atual['sisvalor_chave_id_pai'].'\'');
			$sql->adOnde('sisvalor_titulo=\''.$tipo.'\'');

			if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
			else  $sql->adOnde('sisvalor_projeto IS NULL');

			$sql->adOrdem('sisvalor_valor_id ASC');
			$lista=$sql->Lista();
			$sql->limpar();
			$espaco='&nbsp;&nbsp;&nbsp;';
			foreach($lista as $linha) $vetor[$linha['sisvalor_valor_id']]=($ajax ? utf8_encode($linha['sisvalor_valor']) : $linha['sisvalor_valor']);
			}
		$vetor[$atual['sisvalor_valor_id']]=$espaco.($ajax ? utf8_encode($atual['sisvalor_valor']) : $atual['sisvalor_valor']);
		}

	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	if (!$chave) $sql->adOnde('sisvalor_chave_id_pai IS NULL OR sisvalor_chave_id_pai=sisvalor_valor_id');
	else $sql->adOnde('sisvalor_chave_id_pai=\''.$chave.'\'');
	$sql->adOnde('sisvalor_titulo=\''.$tipo.'\'');

	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
	else  $sql->adOnde('sisvalor_projeto IS NULL');

	$sql->adOrdem('sisvalor_valor_id ASC');
	$lista=$sql->Lista();
	$sql->limpar();
	foreach($lista as $linha) $vetor[$linha['sisvalor_valor_id']]=$espaco.'&nbsp;&nbsp;&nbsp;'.($ajax ? utf8_encode($linha['sisvalor_valor']) : $linha['sisvalor_valor']);
	return $vetor;
	}


function selecionar_om($cia_id=0, $campo, $script='', $vazio='', $acesso=0, $externo=0){
	return selecionar_om_para_ajax($cia_id, $campo, $script, $vazio, $acesso, $externo, 0);
	}



function selecionar_om_para_ajax($cia_id=0, $campo, $script, $vazio='', $acesso=0, $externo=0, $ajax=1){
global $Aplic, $config;
	$sql = new BDConsulta;
	if (!$cia_id && !$vazio && $Aplic->usuario_cia) $cia_id=$Aplic->usuario_cia;

	$administrador=($externo ? 1 : $Aplic->usuario_super_admin || $Aplic->usuario_pode_todas_cias);

	$vetor=array();

	//só pode ser a atual
	if (!$Aplic->usuario_pode_outra_cia && !$Aplic->usuario_pode_superior && !$Aplic->usuario_pode_lateral && !$externo && !$administrador){
		$sql->adTabela('cias');
		if ($config['cia_abreviatura']) $sql->adCampo('cia_nome AS nome');
		else $sql->adCampo('cia_nome_completo AS nome');
		$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
		$cia_nome=$sql->Resultado();
		$sql->limpar();

		if ($vazio) $vetor[null]=($ajax ? utf8_encode($vazio) : $vazio);
		$vetor[$Aplic->usuario_cia]=($ajax ? utf8_encode($cia_nome) : $cia_nome);
		return selecionaVetor($vetor, $campo, $script, $Aplic->usuario_cia);
		}

	if ($vazio) $vetor[null]=($ajax ? utf8_encode($vazio) : $vazio);

	if (!$cia_id) {
		$sql->adTabela('cias');
		if ($config['cia_abreviatura']) $sql->adCampo('cia_nome');
		else $sql->adCampo('cia_nome_completo');
		$sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
		$nome_cia=$sql->Resultado();
		$sql->limpar();
		$vetor[$Aplic->usuario_cia]=($ajax ? utf8_encode($nome_cia) : $nome_cia);
		return selecionaVetor($vetor, $campo, $script, $cia_id);
		}

	$sql->adTabela('cias','cias');
	$sql->esqUnir('cias','cia2','cia2.cia_id=cias.cia_superior');
	$sql->adCampo('cias.cia_id, cia2.cia_id AS cia_id_superior');
	if ($config['cia_abreviatura']) $sql->adCampo('cias.cia_nome AS nome, cia2.cia_nome AS nome_superior');
	else $sql->adCampo('cias.cia_nome_completo AS nome, cia2.cia_nome_completo AS nome_superior');
	$sql->adCampo('cias.cia_acesso');
	$sql->adOnde('cias.cia_id='.(int)$cia_id);
	$cia_superior=$sql->Linha();
	$sql->limpar();

	$sql->adTabela('cias');
	$sql->adCampo('cia_superior');
	$sql->adOnde('cias.cia_id='.(int)$Aplic->usuario_cia);
	$cia_superior_usuario=$sql->resultado();
	$sql->limpar();

	if (!$acesso && !$administrador){
		//checa se $cia_id é superior
		$lista_superiores=cias_superiores($cia_id);
		$lista_superiores=explode(',',$lista_superiores);
		if (in_array($Aplic->usuario_cia, $lista_superiores) || ($Aplic->usuario_pode_superior && (in_array($cia_superior_usuario, $lista_superiores)))) $acesso=1;
		}
	elseif ($administrador) $acesso=1;

	if ($acesso) $vetor[$cia_superior['cia_id_superior']]=($ajax ? utf8_encode($cia_superior['nome_superior']) : $cia_superior['nome_superior']);
	$vetor[$cia_superior['cia_id']]='&nbsp;&nbsp;'.($ajax ? utf8_encode($cia_superior['nome']) : $cia_superior['nome']);

	if ($administrador || $cia_id==$cia_superior_usuario || $Aplic->usuario_pode_outra_cia){
		$sql->adTabela('cias');
		$sql->adCampo('cia_id');
		if ($config['cia_abreviatura']) $sql->adCampo('cia_nome AS nome');
		else $sql->adCampo('cia_nome_completo AS nome');
		$sql->adOnde('cia_superior='.(int)$cia_id);
		if (($cia_id==$cia_superior_usuario) && !$Aplic->usuario_super_admin && !$Aplic->usuario_pode_todas_cias && !$externo && !$Aplic->usuario_pode_lateral) $sql->adOnde('cia_id='.(int)$Aplic->usuario_cia);
		$sql->adOrdem('nome ASC');
		$linhas=$sql->Lista();
		$sql->limpar();
		foreach($linhas as $linha)$vetor[$linha['cia_id']]='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.($ajax ? utf8_encode($linha['nome']) : $linha['nome']);
		}

	if ($Aplic->usuario_pode_lateral && (!$Aplic->usuario_pode_superior || !$Aplic->usuario_pode_outra_cia)){
		$sql->adTabela('cias');
		$sql->adCampo('cia_id');
		if ($config['cia_abreviatura']) $sql->adCampo('cia_nome AS nome');
		else $sql->adCampo('cia_nome_completo AS nome');
		$sql->adOnde('cia_superior='.(int)$cia_superior_usuario);
		if ($cia_id==$Aplic->usuario_cia) $sql->adOnde('cia_id!='.(int)$Aplic->usuario_cia);
		$sql->adOnde('cia_id!='.(int)$cia_superior_usuario);
		$sql->adOnde('cia_id!='.(int)$cia_id);
		$sql->adOrdem('nome ASC');
		$linhas=$sql->Lista();
		$sql->limpar();
		foreach($linhas as $linha)$vetor[$linha['cia_id']]='&nbsp;&nbsp;'.($ajax ? utf8_encode($linha['nome']) : $linha['nome']);
		}
	return selecionaVetor($vetor, $campo, $script, $cia_id);
	}

function selecionar_cidades_para_ajax($estado_sigla='', $campo, $script, $vazio='', $cidade='', $id_municipio=false, $ajax=true){
	global $Aplic;
	$sql = new BDConsulta;
	if ($id_municipio){
		$sql->adTabela('municipios');
		$sql->adCampo('municipio_id, municipio_nome');
		$sql->adOrdem('municipio_nome ASC');
		$sql->adOnde('estado_sigla= \''.$estado_sigla.'\'');
		$cidades=$sql->Lista();
		$sql->limpar();
		$vetor=array();
		$vetor['']='';
		if ($ajax) foreach($cidades as $linha) $vetor[utf8_encode($linha['municipio_id'])]=utf8_encode($linha['municipio_nome']);
		else foreach($cidades as $linha) $vetor[$linha['municipio_id']]=$linha['municipio_nome'];
		}
	else {
		$sql->adTabela('municipios');
		$sql->adCampo('municipio_nome');
		$sql->adOrdem('municipio_nome ASC');
		$sql->adOnde('estado_sigla=\''.$estado_sigla.'\'');
		$cidades=$sql->Lista();
		$sql->limpar();
		$vetor=array();
		$vetor['']='';
		if ($ajax) foreach($cidades as $linha) $vetor[utf8_encode($linha['municipio_nome'])]=utf8_encode($linha['municipio_nome']);
		else foreach($cidades as $linha) $vetor[$linha['municipio_nome']]=$linha['municipio_nome'];
		}
	$saida=selecionaVetor($vetor, $campo, $script, $cidade);
	return $saida;
	}


function cias_superiores($cia_id){
	global $Aplic;

	$saida='';
	$sql = new BDConsulta;
	$sql->adTabela('cias');
	$sql->adCampo('cia_superior');
	$sql->adOnde('cia_id='.(int)$cia_id);
	$sql->adOnde('cia_superior!=cia_id AND cia_superior!=0 AND cia_superior IS NOT NULL');
	$cia_superior=$sql->resultado();
	$sql->limpar();

	if ($cia_superior){
		$saida.=','.$cia_superior;
		$saida.=cias_superiores($cia_superior);
		}
	return $saida;
	}


function mudar_usuario($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao='', $mostrar_cia=false, $mostrar_funcao=false){
	global $Aplic, $config;
	$sql = new BDConsulta;
	if (!$cia_id && !$usuario_id) $cia_id=$Aplic->usuario_cia;
	elseif (!$cia_id && $usuario_id){
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
		$sql->esqUnir('cias','cias','contatos.contato_cia=cias.cia_id');
		$sql->adCampo('cia_id');
		$sql->adOnde('usuarios.usuario_id='.(int)$usuario_id);
		$cia_id=$sql->Resultado();
		$sql->limpar();
		}
	$sql->adTabela('usuarios');
	if ($segunda_tabela && $condicao){
		$sql->esqUnir($segunda_tabela,$segunda_tabela,$condicao);
		}
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	if ($Aplic->getPref('om_usuario')){
		$sql->esqUnir('cias','cias','contatos.contato_cia=cias.cia_id');
		$sql->adCampo('cia_nome');
		}
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOnde('usuarios.usuario_ativo=1');
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$vetor[0]='';
	foreach((array)$linhas as $linha) {
		$vetor[$linha['usuario_id']]=nome_funcao('',$linha['nome_usuario'], $linha['contato_funcao']).($Aplic->getPref('om_usuario') && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: '');
		}
	if (count($vetor)==1) $vetor[-1]='';
	return selecionaVetor($vetor, $campo, $script, $usuario_id);
	}


function mudar_usuario_para_ajax($cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao='', $mostrar_cia=false, $mostrar_funcao=false){
	global $Aplic, $config;
	$sql = new BDConsulta;
	if (!$cia_id && !$usuario_id) $cia_id=$Aplic->usuario_cia;
	elseif (!$cia_id && $usuario_id){
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
		$sql->esqUnir('cias','cias','contatos.contato_cia=cias.cia_id');
		$sql->adCampo('cia_id');
		$sql->adOnde('usuarios.usuario_id='.(int)$usuario_id);
		$cia_id=$sql->Resultado();
		$sql->limpar();
		}
	$sql->adTabela('usuarios');
	if ($segunda_tabela && $condicao)	$sql->esqUnir($segunda_tabela,$segunda_tabela,$condicao);
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	if ($mostrar_cia){
		$sql->esqUnir('cias','cias','contatos.contato_cia=cias.cia_id');
		$sql->adCampo('cia_nome');
		}
	$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$vetor[0]='';
	foreach((array)$linhas as $linha)	$vetor[$linha['usuario_id']]=utf8_encode($linha['nome_usuario'].($mostrar_funcao && $linha['contato_funcao'] ? ' - '.$linha['contato_funcao']: '').($mostrar_cia && $linha['cia_nome'] ? ' - '.$linha['cia_nome']: ''));
	if (count($vetor)==1) $vetor[-1]='';
	$saida=selecionaVetor($vetor, $campo, $script, $usuario_id);
	return $saida;
	}





function mudar_usuario_em_dept($ajax=false, $cia_id=0, $usuario_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao='', $mostrar_cia=false, $mostrar_funcao=false){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_nome, dept_id');
	$sql->adOnde('dept_cia = '.(int)$cia_id);
	$sql->adOnde('dept_superior IS NULL OR dept_superior=0');
	$sql->adOrdem('dept_ordem, dept_nome');
	$depts = $sql->ListaChave('dept_id');
	$sql->limpar();
	$vetor=array();
	$estilo=array();
	$qnt=0;
	foreach ($depts as $dept_id => $secao_data){
		$vetor[--$qnt]=($ajax ? utf8_encode($secao_data['dept_nome']) : $secao_data['dept_nome']);
		$estilo[$qnt]='font-weight:bold;';
		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		$sql->adOnde('dept_id = '.$dept_id);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$linhas = $sql->ListaChave('usuario_id');
		$sql->Limpar();
		foreach ($linhas as $linha) {
			if (!$linha['cia_nome']) $contato_cia = $linha['contato_cia'];
			else $contato_cia = $linha['cia_nome'];
			$vetor[$linha['usuario_id']]='&nbsp;&nbsp;&nbsp;'.($ajax ? utf8_encode(nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao'])) : nome_funcao(($config['militar'] < 10 ? $linha['contato_posto'].' '.$linha['contato_nomeguerra'] : $linha['contato_nomeguerra']),'',$linha['contato_funcao']));
			$estilo[$linha['usuario_id']]='font-weight:normal;';
			}
		mudar_usuario_em_dept_subniveis($dept_id, '&nbsp;&nbsp;&nbsp;', $vetor, $qnt, $estilo, $ajax);
		}

	$sql->adTabela('contatos', 'a');
	$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
	$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
	$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
	$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome');
	$sql->adOnde('usuario_ativo=1');
	$sql->adOnde('contato_dept = 0 OR contato_dept IS NULL');
	$sql->adOnde('contato_cia = '.(int)$cia_id);
	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$usuarios = $sql->ListaChave('usuario_id');
	$sql->Limpar();
	if (count($usuarios)){
		$vetor[--$qnt]=($ajax ? utf8_encode('Em '.($config['genero_dept']=='o' ? 'nenhum ': 'nenhuma ').' '.strtolower($config['departamento'])) : 'Em '.($config['genero_dept']=='o' ? 'nenhum ': 'nenhuma ').' '.strtolower($config['departamento']));
		$estilo[$qnt]='font-weight:bold;';
		foreach ($usuarios as $usuario) {
			$nome=
			$vetor[$usuario['usuario_id']]='&nbsp;&nbsp;&nbsp;'.($ajax ? utf8_encode(nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao'])) : nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']));
			$estilo[$usuario['usuario_id']]='font-weight:normal;';
			}
		}


	$saida=selecionaVetor($vetor, $campo, $script, $usuario_id,'','',$estilo);
	return $saida;
	}

function mudar_usuario_em_dept_subniveis($dept_id, $subnivel, &$vetor, &$qnt, &$estilo, $ajax){
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_id, dept_nome');
	$sql->adOnde('dept_superior = '.(int)$dept_id);
	$sql->adOrdem('dept_ordem, dept_nome');
	$subordinados = $sql->lista();
	$sql->limpar();
	foreach($subordinados as $linha){
		$vetor[--$qnt]=$subnivel.($ajax ? utf8_encode($linha['dept_nome']) : $linha['dept_nome']);
		$estilo[$qnt]='font-weight:bold;';

		$sql->adTabela('contatos', 'a');
		$sql->esqUnir('cias', 'b', 'cia_id = contato_cia');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
		$sql->esqUnir('depts', 'c', 'dept_id = contato_dept');
		$sql->adCampo('usuario_id, contato_posto, contato_nomeguerra, contato_funcao, contato_nomecompleto, contato_cia, cia_nome');
		$sql->adOnde('usuario_ativo=1');
		$sql->adOnde('dept_id = '.$linha['dept_id']);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$usuarios = $sql->ListaChave('usuario_id');
		$sql->Limpar();
		foreach ($usuarios as $usuario) {
			$vetor[$usuario['usuario_id']]='&nbsp;&nbsp;&nbsp;'.$subnivel.($ajax ? utf8_encode(nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao'])) : nome_funcao(($config['militar'] < 10 ? $usuario['contato_posto'].' '.$usuario['contato_nomeguerra'] : $usuario['contato_nomeguerra']),'',$usuario['contato_funcao']));
			$estilo[$usuario['usuario_id']]='font-weight:normal;';
			}
		mudar_usuario_em_dept_subniveis($linha['dept_id'], $subnivel.'&nbsp;&nbsp;&nbsp;', $vetor, $qnt, $estilo, $ajax);
		}
	}







function mudar_contato($cia_id=0, $contato_id=0, $campo='', $posicao='', $script='', $segunda_tabela='', $condicao=''){
	global $Aplic, $config;
	if (!$cia_id) $cia_id=$Aplic->usuario_cia;
	$sql = new BDConsulta;
	$sql->adTabela('contatos');
	if ($segunda_tabela && $condicao) $sql->esqUnir($segunda_tabela,$segunda_tabela,$condicao);
	$sql->adCampo('contato_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
	$linhas=$sql->Lista();
	$sql->limpar();
	$vetor=array();
	$vetor[0]='';
	foreach((array)$linhas as $linha) {
		$vetor[$linha['contato_id']]=$linha['nome_usuario'];
		}
	if (count($vetor)==1) $vetor[-1]='';
	return selecionaVetor($vetor, $campo, $script, $contato_id);
	}


function mjson_decode($json){
  if(get_magic_quotes_gpc()){
    $json = stripslashes($json);
    }
  return json_decode(removerVirgulas(utf8_encode($json)), true);
  }

function removerVirgulas($json){
  $json=preg_replace('/,\s*([\]}])/m', '$1', $json);
  return $json;
  }

function carregar_objeto($hash, &$obj, $checarAspas = true) {
		is_array($hash) or die('unirLinhaAoObjeto : hash esperado');
		is_object($obj) or die('unirLinhaAoObjeto : objeto esperado');
		foreach (get_object_vars($obj) as $k => $v) {
			if (isset($hash[$k])) $obj->$k = decodificarHTML($hash[$k]);
			}
		}



function grupo_msg($mover){
	$sql = new BDConsulta;
	$sql->adTabela('msg_usuario');
	$sql->adCampo('DISTINCT msg_id');
	$sql->adOnde('msg_usuario_id IN ('.$mover.')');
	return implode(',', $sql->listaVetorChave('msg_id','msg_id'));
	}

function grupo_doc($mover){
	$sql = new BDConsulta;
	$sql->adTabela('modelo_usuario');
	$sql->adCampo('DISTINCT modelo_id');
	$sql->adOnde('modelo_usuario_id IN ('.$mover.')');
	return implode(',', $sql->listaVetorChave('modelo_id','modelo_id'));
	}

function vetor_grupo_msg($vetor_msg_usuario){
	$sql = new BDConsulta;
	$sql->adTabela('msg_usuario');
	$sql->adCampo('DISTINCT msg_id');
	$sql->adOnde('msg_usuario_id IN ('.implode(',', $vetor_msg_usuario).')');
	return $sql->listaVetorChave('msg_id','msg_id');
	}

function vetor_grupo_doc($vetor_msg_usuario){
	$sql = new BDConsulta;
	$sql->adTabela('modelo_usuario');
	$sql->adCampo('DISTINCT modelo_id');
	$sql->adOnde('modelo_usuario_id IN ('.implode(',', $vetor_msg_usuario).')');
	return $sql->listaVetorChave('modelo_id','modelo_id');
	}

function nome_om($usuario_id=0, $cia=true, $funcao=true, $contato=false){
	global $Aplic, $config;
	if (!$usuario_id) return '&nbsp';
	$sql = new BDConsulta;
	if ($contato){
		$sql->adTabela('contatos');
		$sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao');
		$sql->adOnde('contato_id = '.$usuario_id);
		}
	else {
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cia_id = contato_cia');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, cia_nome, contato_funcao');
		$sql->adOnde('usuarios.usuario_id = '.$usuario_id);
		}
	$linha = $sql->Linha();
	$sql->limpar();
	return $linha['nome'].($linha['contato_funcao'] && $funcao ? ' - '.$linha['contato_funcao'] :'').($cia && $linha['cia_nome'] ? ' - '.$linha['cia_nome'] :'');
	}


function nome_funcao($nome1='', $nome2='', $funcao1='', $funcao2='', $id_usuario=0, $travar_ordem=false, $mostrar_cia=false){
	global $Aplic;
	$cia_nome='';
	if ($mostrar_cia) $cia_nome=cia_usuario($id_usuario);
	if ($id_usuario){
		$nome=($nome1 ? $nome1 : nome_usuario($id_usuario));
		$funcao=($funcao1 ? $funcao1 : funcao_usuario($id_usuario));
		}
	else {
		$nome=($nome1 ? $nome1 : $nome2);
		$funcao=($funcao1 ? $funcao1 : $funcao2);
		}
	if (!$travar_ordem) $saida=($Aplic->usuario_prefs['nomefuncao'] ? $nome.($Aplic->usuario_prefs['exibenomefuncao']&& $nome && $funcao ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $funcao : '') :	$funcao.($Aplic->usuario_prefs['exibenomefuncao']&& $nome && $funcao ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $nome : '')).($cia_nome ? ' - '.$cia_nome : '');
	else $saida=$nome.($funcao ? ' - '.$funcao :'').($cia_nome ? ' - '.$cia_nome : '');
	return ($saida ? $saida :'&nbsp;');
	}

function aviso_leitura ($para_id, $msg_usuario_id, $data){
	global $config, $Aplic, $bd;

	$sql = new BDConsulta;
	$sql->adTabela('msg_usuario');
	$sql->adUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.de_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('msg_usuario.msg_id, referencia, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adOnde('aviso_leitura=1');
	$sql->adOnde('msg_usuario.msg_usuario_id='.(int)$msg_usuario_id);
	$rs=$sql->Linha();
	$sql->Limpar();

	$texto_msg=ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' <a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_id='.$rs['msg_id'].'\');">Nr '.$rs['msg_id'].' ('.$rs['referencia'].')</a> foi lida por '.nome_funcao($rs['nome_usuario'],'',$rs['contato_funcao']).'.';
	$sql->adTabela('msg');
	$sql->adInserir('referencia', 'Aviso de leitura da Msg Nr '.$rs['msg_id'].' ('.$rs['referencia'].')');
	$sql->adInserir('de_id', $Aplic->usuario_id);
	$sql->adInserir('texto', $texto_msg);
	$sql->adInserir('data_envio', $data);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de msgs!');
	$msg_id=$bd->Insert_ID('msg','msg_id');
	$sql->Limpar();
	$sql->adTabela('msg_usuario');
	$sql->adInserir('de_id', $Aplic->usuario_id);
	$sql->adInserir('para_id', $para_id);
	$sql->adInserir('msg_id', $msg_id);
	$sql->adInserir('datahora', $data);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	$sql->adInserir('nome_para', nome_usuario($para_id));
	$sql->adInserir('funcao_para', funcao_usuario($para_id));
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
	$sql->Limpar();
	}


function aviso_leitura_modelo($para_id, $modelo_usuario_id, $data){
	global $config, $Aplic, $bd;
	$sql = new BDConsulta;
	$sql->adTabela('modelo_usuario');
	$sql->adUnir('modelo','modelo','modelo.modelo_id=modelo_usuario.modelo_id');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.de_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('assunto, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adOnde('aviso_leitura=1');
	$sql->adOnde('modelo_usuario.modelo_usuario_id='.(int)$modelo_usuario_id);
	$rs=$sql->Linha();
	$sql->Limpar();

	$texto_msg=ucfirst($config['genero_mensagem']).' '.$config['mensagem'].' <a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=modelo_editar&modelo_id='.$modelo_id.'\');">Nr '.$modelo_id.' ('.$rs["assunto"].")</a> foi lida por ".($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['nome_usuario'] : '')).".";
	$sql->adTabela('msg');
	$sql->adInserir('referencia', 'Aviso de leitura do documento Nr '.$modelo_id.' ('.$rs['assunto'].')');
	$sql->adInserir('de_id', $Aplic->usuario_id);
	$sql->adInserir('texto', $texto_msg);
	$sql->adInserir('data_envio', $data);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de msgs!');
	$msg_id=$bd->Insert_ID('msg','msg_id');
	$sql->Limpar();

	$sql->adTabela('msg_usuario');
	$sql->adInserir('de_id', $Aplic->usuario_id);
	$sql->adInserir('para_id', $para_id);
	$sql->adInserir('msg_id', $msg_id);
	$sql->adInserir('datahora', $data);
	$sql->adInserir('nome_de', $Aplic->usuario_nome);
	$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
	$sql->adInserir('nome_para', nome_usuario($para_id));
	$sql->adInserir('funcao_para', funcao_usuario($para_id));
	if (!$sql->exec()) die('Não foi possível inserir os dados na tabela msg_usuario!');
	$sql->Limpar();
	}


function texto_msg_email($msg_usuario_id, $status=0, $usuario_id=null, $senha=null){
	global $Aplic, $config, $bd;
	$saida='';
	$saida2='';
	if (!$usuario_id) $usuario_id=$Aplic->usuario_id;
	$tipos_status=array('' => 'indefinido') + getSisValor('status');
	$precedencia=getSisValor('precedencia');
	$class_sigilosa=getSisValor('class_sigilosa');
	//impede ver mensagens de outro usuario se não for CM ou administrador
	if (!$Aplic->usuario_admin && $Aplic->usuario_acesso_email!=1) $usuario_id = $Aplic->usuario_id;

	$sql = new BDConsulta;
	$sql->adTabela('msg');
	$sql->adUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
	$sql->adCampo('msg_usuario_id');
	$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
	$sql->adOnde('msg.class_sigilosa <= '.$Aplic->usuario_acesso_email);
	$permitido = $sql->Resultado();
	$sql->limpar();
	if (!$permitido) {
		return 'Não tem permissão de acesso a esta Msg';
		}
	//dados básicos da mensagem
	$sql->adTabela('msg_usuario');
	$sql->adUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
	$sql->esqUnir('msg_cripto', 'msg_cripto', 'msg_usuario.msg_cripto_id=msg_cripto.msg_cripto_id');
	$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'msg.chave_publica = chave_publica_id');
	$sql->adCampo('msg_usuario.msg_cripto_id, chave_publica_chave AS publica');
	$sql->adCampo('msg_usuario.anotacao_id, msg_usuario.tipo, msg_usuario.aviso_leitura, msg_usuario.datahora_leitura, msg.data_envio, msg.msg_id, data_retorno, data_limite, resposta_despacho, msg.cm, assinatura, msg_usuario_id, msg.precedencia, msg.class_sigilosa, msg.referencia, msg.texto, msg.cripto, datahora, msg_usuario.de_id');
	$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
	$rs = $sql->Linha();
	$sql->limpar();
	$msg_id=$rs['msg_id'];
	if ($rs['cripto']) $msg_id_cripto=$msg_id;
	else $msg_id_cripto=0;
	if ($rs['cripto']==1){
		$sql->adTabela('msg_cripto');
		$sql->adCampo('texto, chave_envelope');
		$sql->adOnde('msg_cripto_msg = '.$msg_id);
		$sql->adOnde('msg_cripto_para = '.$usuario_id);
		$linha_cripto = $sql->Linha();
		$sql->limpar();
		openssl_open(base64_decode($linha_cripto['texto']), $em_claro, base64_decode($linha_cripto['chave_envelope']), $Aplic->chave_privada);
		$rs['texto']=$em_claro;
		}
	elseif ($rs['cripto']==2){
		$sql->adTabela('msg_cripto');
		$sql->adCampo('texto');
		$sql->adOnde('msg_cripto_id = '.$rs['msg_cripto_id']);
		$linha_cripto = $sql->Resultado();
		$sql->limpar();
		require_once BASE_DIR.'/classes/cifra.class.php';
		$cifra = new cifra;
		$cifra->set_key($senha);
		$rs['texto']=$cifra->decriptar($linha_cripto);
		}
	$assinado='';
	//verificar dados originais da 1a mensagem
	$sql->adTabela('msg');
	$sql->esqUnir('chaves_publicas', 'chaves_publicas', 'msg.chave_publica = chave_publica_id');
	$sql->adCampo('precedencia, class_sigilosa, referencia, de_id, texto, cripto, cm, data_envio, assinatura, chave_publica_chave');
	$sql->adOnde('msg_id = '.$msg_id);
	$original = $sql->Linha();
	$sql->limpar();
	if (function_exists('openssl_sign') && $rs['assinatura']){
		$identificador=$original['precedencia'].$original['class_sigilosa'].$original['referencia'].$original['de_id'].$rs['texto'].$original['cripto'].$original['cm'].$original['data_envio'];
		$ok = openssl_verify($identificador, base64_decode($original['assinatura']), $original['chave_publica_chave'], OPENSSL_ALGO_SHA1);
		if (!$ok) $assinado='&nbsp;'.'<img src="'.acharImagem('icones/assinatura_erro.gif').'" style="vertical-align:top" width="15" height="13" />';
		else $assinado='&nbsp;<img src="'.acharImagem('icones/assinatura.gif').'" style="vertical-align:top" width="15" height="13" />';
		}
	//marcar como lida
	if ($status ==1 && !$rs['datahora_leitura']) {
		$data = date('Y-m-d H:i:s');
		$sql->adTabela('msg_usuario');
		$sql->adAtualizar('datahora_leitura', $data);
		$sql->adAtualizar('status', 1);
		$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
		$retorno=$sql->exec();
		$sql->limpar();
		if ($rs['aviso_leitura']==1 && $Aplic->usuario_id==$usuario_id) aviso_leitura ($rs_leitura['de_id'], $msg_usuario_id, $data);
		}
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
	$sql->adOnde('usuario_id ='.(int)$Aplic->usuario_id);
	$cor=$sql->Linha();
	$sql->limpar();
	if (!isset($cor['cor_msg'])) {
		$sql->adTabela('preferencia_cor');
		$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
		$sql->adOnde('usuario_id = 0 OR usuario_id IS NULL');
		$cor=$sql->Linha();
		$sql->limpar();
	 	}
	$saida.='<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
	$saida.='<table align="center" cellspacing=0 width="770" cellpadding=0>';
	$saida.='<tr width="100%"><td colspan="3" align="center" style="font-size:10pt;  padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].'"><b>MENSAGEM '.$msg_id.'</b></td></tr>';
	$saida.='<tr style="background-color: #'.$cor['cor_msg'].'">';
	$saida.='<td align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;" >Precedência</td>';
	$saida.='<td align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;" >Class Sigilosa</td>';
	$saida.='<td align="left" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px;" >Referência / Assunto</td></tr>';
	$saida.='<tr style="background-color: #'.$cor['cor_fundo'].'">';
	$saida.='<td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.(isset($precedencia[$rs['precedencia']]) ? $precedencia[$rs['precedencia']] : 'sem precedência').'</td>';
	$saida.='<td align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.(isset($class_sigilosa[$rs['class_sigilosa']]) ? $class_sigilosa[$rs['class_sigilosa']] : 'sem precedência').'</td>';
	$saida.='<td align="left" style="font-size:10pt; padding-left: 5px; padding-right: 5px;">'.$rs['referencia'].'</td></tr>';
	$saida.='<tr><td align="right" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].'">De:</td>';
	$saida.='<td colspan="2" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].';">';
	//todos os remetentes
	$sql->adTabela('msg_usuario');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.de_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de');
	$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	$sql->adOnde(($status==5 ? 'de_id=' : 'para_id=').$usuario_id);
	$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
	$sql->adGrupo('usuarios.usuario_id');
	$remetentes = $sql->lista();
	$sql->limpar();
	$i=0;
	if (isset($remetentes[0])) $saida.=nome_funcao($remetentes[0]['nome_de'],$remetentes[0]['nome_usuario'], $remetentes[0]['funcao_de'], $remetentes[0]['contato_funcao']);
	$sql->adTabela('anotacao');
	$sql->adCampo('usuario_id, nome_de, funcao_de');
	$sql->adOnde('msg_id = '.$msg_id);
	$sql->adOnde('tipo = 3');
	$rs_para=$sql->Linha();
	$sql->limpar();
	if (isset($rs_para['usuario_id'])) $saida.=' - (CM: enviada por '.nome_funcao($rs_para['nome_de'], '', $rs_para['funcao_de'], '', $rs_para['usuario_id']);
	$saida.='</td></tr>';
	$saida.='<tr><td align="right" style="font-size:10pt;font-weight:Bold;  padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].';" >Para:</td>';
	$saida.='<td colspan="2" style="font-size:9pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].';">';
	//todos os destinatários
	$sql->adTabela('msg_usuario');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=msg_usuario.para_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adCampo('msg_usuario_id, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao');
	$sql->adCampo('para_id');
	$sql->adOnde('msg_usuario.msg_id ='.(int)$msg_id);
	$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
	$sql->adOnde('msg_usuario.para_id>0');
	$sql->adGrupo('usuarios.usuario_id');
	$destinatarios = $sql->Lista();
	$sql->limpar();
	foreach($destinatarios as $chave => $destinatario){
		if ($destinatario['para_id']==$usuario_id) {
			$apoio=$destinatarios[0];
			$destinatarios[0]=$destinatarios[$chave];
			$destinatarios[$chave]=$apoio;
			}
		}
	//todos os destinatários extras
	$sql->adTabela('msg_usuario_ext');
	$sql->adCampo('para');
	$sql->adOnde('msg_id ='.(int)$msg_id);
	$sql->adOnde('datahora =\''.$rs['datahora'].'\'');
	$sql->adGrupo('para');
	$destinatarios_extras = $sql->Lista();
	$sql->limpar();
	if (isset($destinatarios[0]) && $destinatarios[0]) $saida.= formata_destinatario5($destinatarios[0]);
	elseif(isset($destinatarios_extras[0]) && $destinatarios_extras[0]) $saida.=' '.$destinatarios_extras[0]['para'].' ';
	$qnt_destinatario=count($destinatarios)+count($destinatarios_extras);
	if ($qnt_destinatario > 1) {
			$lista='';
			for ($i = 1, $i_cmp = count($destinatarios); $i < $i_cmp; $i++) $lista.= formata_destinatario5($destinatarios[$i]).'<br>';
			for ($i = 1, $i_cmp = count($destinatarios_extras); $i < $i_cmp; $i++) $lista.= ' '.$destinatarios_extras[$i]['para'].' <br>';
			$saida.= ' <a href="javascript: void(0);" onclick="if (document.getElementById(\'destinatario\').style.display==\'none\') document.getElementById(\'destinatario\').style.display=\'\';	else document.getElementById(\'destinatario\').style.display=\'none\';">(+'.($qnt_destinatario - 1).')</a><span style="display: none" id="destinatario"><br>'.$lista.'</span>';
			}
	$saida.='</td></tr>';
	$saida.='<tr><td colspan="3" align="center" style="font-size:10pt;font-weight:Bold; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_msg'].'; max-width:770px;" >Texto d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']).'</td></tr>';
	$saida.='<tr><td colspan="3" width="100%"  style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].';">'.$rs['texto'].'<p></td></tr>';
	$saida.='<tr style="background-color: #'.$cor['cor_msg'].'">';
	$saida.='<td colspan="2" height="1" style="font-size:10pt; padding-left: 5px; padding-right: 5px;"><b>Crpt: </b>'.($rs['cripto'] ? 'Sim' : 'Não').$assinado.'</td>';
	$saida.='<td style="font-size:10pt; padding-left: 5px; padding-right: 5px;"><b>Data de Envio: </b> '.retorna_data($rs['datahora']).'</td></tr></table>';
	$saida.='</td></tr></table>';
	$saida.=sombra_baixo();
	//historico
 	$sql->adTabela('msg');
	$sql->adCampo('data_envio,nome_de, funcao_de');
	$sql->adOnde('msg_id = '.$msg_id);
	$msg = $sql->Linha();
	$sql->limpar();
	$saida2.='<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="770" cellpadding=0>';
	$saida2.='<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Histórico</b></td></tr>';
	$saida2.='<tr><td align=center><table align="center" style="background-color: #'.$cor['cor_fundo'].'" cellspacing=0 width="100%" cellpadding=0>';
	$saida2.='<tr align=center><td><b>'.ucfirst($config['usuario']).'</b></td><td><b>Ação</b></td><td><b>Data</b></td></tr>';
	$saida2.='<tr align=center><td>'.nome_funcao($msg['nome_de'],'',$msg['funcao_de']).'</td><td>Criou</td><td>'.retorna_data($msg['data_envio']).'</td></tr>';
	$saida2.='</table></td></tr>';
	$saida2.='<tr><td>&nbsp;</td></tr>';
	$saida2.='</table>';
	$saida2.=sombra_baixo('', 770);
	$sql->adTabela('anotacao');
	$sql->adUnir('usuarios','usuarios','anotacao.usuario_id = usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adCampo('anotacao_usuarios, anotacao.datahora, anotacao.usuario_id, anotacao.nome_de, anotacao.funcao_de, anotacao.texto, anotacao.tipo, contato_funcao, anotacao_id');
	$sql->adOnde('msg_id = '.$msg_id);
	$sql->adOrdem('anotacao_id DESC');
	$sql_resultadosb = $sql->Lista();
	$sql->limpar();
	$outros_despachos=array();
	foreach ($sql_resultadosb as $rs_anot){
		if ($rs_anot['tipo'] == 1 ) {
			//despacho
			$vetor_destinatarios=array();
			$saida2 = '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
			$saida2.= '<table align="center" cellspacing=0 width="770" cellpadding=0>';
			$saida2.= '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'" ><a href="javascript:void(0);" onclick="if (document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display==\'none\') document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display=\'\';else document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display=\'none\';">Despacho de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
			$saida2.= '<tr id="linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.nl2br($rs_anot['texto']).'</td></tr>';
			$saida2.= '<tr id="2linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:8pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'"><table cellspacing=0 cellpadding=0><tr><td><b>Para</b>:</td><td>';
			$sql->adTabela('msg_usuario');
			$sql->adUnir('usuarios','usuarios','msg_usuario.para_id = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
			$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, contato_funcao');
			$sql->adOnde('msg_id = '.$msg_id);
			$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
			$sql->adOnde('msg_usuario.datahora=\''.$rs_anot['datahora'].'\'');
			$sql->adGrupo('para_id');
			$destinatarios_despacho = $sql->Lista();
			$sql->limpar();
		  $quant=0;
		  $primeira_linha=0;
			if (!count($destinatarios_despacho)){
		  	$sql->adTabela('msg_usuario');
				$sql->adUnir('usuarios','usuarios','msg_usuario.para_id = usuarios.usuario_id');
				$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
				$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
				$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, contato_funcao');
				$sql->adOnde('msg_id = '.$msg_id);
				$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
				$sql->adOnde('msg_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
				$sql->adGrupo('para_id');
				$destinatarios_despacho = $sql->Lista();
				$sql->limpar();
		  	}
		  if (isset($destinatarios_despacho[0]['para_id']) && $destinatarios_despacho[0]['para_id']) $vetor_destinatarios[]=$destinatarios_despacho[0]['para_id'];
			if (isset($destinatarios_despacho[0])) $saida2.= formata_despacho5($destinatarios_despacho[0]);
			$qnt_destinatario=count($destinatarios_despacho);
			if ($qnt_destinatario > 1) {
					$lista='';
					for ($i = 1, $i_cmp = $qnt_destinatario; $i < $i_cmp; $i++) {
						$lista.= formata_despacho5($destinatarios_despacho[$i]).'<br>';
						$vetor_destinatarios[]=$destinatarios_despacho[$i]['para_id'];
						}
					$saida2.= ' <a href="javascript: void(0);" onclick="if (document.getElementById(\'despacho_'.$rs_anot['anotacao_id'].'\').style.display==\'none\') document.getElementById(\'despacho_'.$rs_anot['anotacao_id'].'\').style.display=\'\';else document.getElementById(\'despacho_'.$rs_anot['anotacao_id'].'\').style.display=\'none\';">(+'.($qnt_destinatario - 1).')</a><span style="display: none" id="despacho_'.$rs_anot['anotacao_id'].'"><br>'.$lista.'</span>';
					}
			$saida2.= '</td></tr></table></td></tr></table>';
			$saida2.= '</td></tr></table>';
			if (in_array($Aplic->usuario_id, $vetor_destinatarios) || $rs_anot['usuario_id']==$Aplic->usuario_id) $saida2.=$saida2;
			else $outros_despachos[]=$saida2;
			}
		else if ($rs_anot['tipo'] == 2 ){
			$saida2.='<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
			$saida2.='<table align="center" cellspacing=0 width="770" cellpadding=0>';
		  $saida2.='<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_resposta'].'" ><a href="javascript:void(0);" onclick="if (document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display==\'none\') document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display=\'\';else document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display=\'none\';">Resposta de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao'])." em ".retorna_data($rs_anot['datahora']).'</a></td></tr>';
		  $saida2.='<tr id="linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;  background-color: #'.$cor['cor_fundo'].'">'.nl2br($rs_anot['texto']).'</td></tr></table>';
			$saida2.='</td></tr></table>';
			}
		else if ($rs_anot['tipo'] == 4 ){
			$pode_ver=0;
			if (!$rs_anot['anotacao_usuarios'] || $rs_anot['usuario_id']==$Aplic->usuario_id) $pode_ver=1;
			else {
				$sql->adTabela('anotacao_usuarios');
				$sql->adOnde('usuario_id');
				$sql->adOnde('anotacao_id = '.$rs_anot['anotacao_id']);
				$sql->adOnde('usuario_id='.(int)$Aplic->usuario_id);
				$pode_ver= $sql->Resultado();
				$sql->limpar();
				}
			if ($pode_ver){
				$saida2.='<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
			  $saida2.='<table align="center" cellspacing=0 width="770" cellpadding=0>';
			  $saida2.='<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_anotacao'].'" ><a href="javascript:void(0);" onclick="if (document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display==\'none\') document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display=\'\';else document.getElementById(\'linha1_'.$rs_anot['anotacao_id'].'\').style.display=\'none\';">Nota de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
			  $saida2.='<tr id="linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.nl2br($rs_anot['texto']).'</td></tr></table>';
			  $saida2.='</td></tr></table>';
				}
		  }
		}
	if (count($sql_resultadosb)) $saida2.=sombra_baixo('', 770);


	if (count($outros_despachos))	{
		$saida2.='<table align="center"><tr><td><a href="javascript:void(0);" onclick="if (document.getElementById(\'outros_despacho\').style.display==\'none\') document.getElementById(\'outros_despacho\').style.display=\'\';else document.getElementById(\'outros_despacho\').style.display=\'none\';" style="padding-left: 5px; font-size:10pt; font-weight:Bold;">Outros despachos ('.count($outros_despachos).')</a></td></tr></table>';
		$saida2.='<span style="display: none" id="outros_despacho">';
		foreach($outros_despachos as $outro) $saida2.=$outro;
		$saida2.='</span>';
		}
	$sql->adTabela('msg_usuario');
	$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adCampo('msg_usuario_id, data_retorno, data_limite, resposta_despacho, msg_usuario.tipo, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
	$sql->adOnde('msg_id = '.$msg_id);
	$sql->adOnde('msg_usuario.para_id>0');
	$sql_resultadosf = $sql->Lista();
	$sql->limpar();
	//todos os destinatários extras
	$sql->adTabela('msg_usuario_ext');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
	$sql->adCampo('para, tipo, datahora');
	$sql->adOnde('msg_id ='.(int)$msg_id);
	$sql->adGrupo('para');
	$destinatarios_extras = $sql->Lista();
	$sql->limpar();
	$tipo=array('0'=>'envio', '1'=>'despacho', '2'=>'resposta', '3'=>'encaminhamento', '4'=>'nota');
	$objeto_data = new CData();
	$agora=$objeto_data->format('%Y-%m-%d %H:%M:%S');
	if (($sql_resultadosf && count($sql_resultadosf)) || count($destinatarios_extras)){
		$saida2.='<br><table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="770" cellpadding=0>';
		$saida2.='<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Tramitação d'.$config['genero_mensagem'].' '.$config['mensagem'].'</b></td></tr>';
		$saida2.='<tr><td><table align="center" style="background-color: #'.$cor['cor_fundo'].'" cellspacing=0 width="100%" cellpadding=0>';
		$saida2.='<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Tipo</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>De</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Para</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Envio</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Leitura</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Status</b></td></tr>';
		foreach ($sql_resultadosf as $rs_enc){
		  if (($rs_enc['copia_oculta'] !=1) || ($rs_enc['de_id']==$Aplic->usuario_id || $rs_enc['para_id']==$Aplic->usuario_id )) {
		    if ($rs_enc['tipo']==1 && !$rs_enc['data_limite']) $cor_campo='FFFFFF';
		    elseif ($rs_enc['tipo']==1 && (($rs_enc['data_retorno']> $rs_enc['data_limite']) || ($rs_enc['data_limite']< $agora && !$rs_enc['data_retorno']))) $cor_campo='FFCCCC';
		    elseif ($rs_enc['tipo']==1 && ($rs_enc['data_retorno']<= $rs_enc['data_limite'])) $cor_campo='CCFFCC';
		    else $cor_campo='FFFFFF';
		    $saida2.='<tr>';
		    $saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.$tipo[$rs_enc['tipo']].($rs_enc['resposta_despacho'] ? '<a href="javascript: void(0);" onclick="if (document.getElementById(\'despacho_'.$rs_enc['msg_usuario_id'].'\').style.display==\'none\') document.getElementById(\'despacho_'.$rs_enc['msg_usuario_id'].'\').style.display=\'\';else document.getElementById(\'despacho_'.$rs_enc['msg_usuario_id'].'\').style.display=\'none\';">ver</a>' :'').'</td>';
		    $saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.nome_funcao($rs_enc['nome_de'], '', $rs_enc['funcao_de'], '').'</td>';
		    $saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.formata_destinatario5($rs_enc).'</td>';
		    $saida2.="<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".retorna_data($rs_enc['datahora']).'</td>';
		    $saida2.="<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>";
				if (!$rs_enc['datahora_leitura'] || empty($rs_enc['datahora_leitura']))	$saida2.='Não Lida';
				else $saida2.=retorna_data($rs_enc['datahora_leitura']).($rs_enc['cm'] == 1 ? '(CM:'.nome_usuario($rs_enc['cm']).' por '.$rs_enc['meio'].')' : '');
				$saida2.='</td>';
				$saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$tipos_status[$rs_enc['status']].'</td>';
				$saida2.='</tr>';
				if ($rs_enc['resposta_despacho']) $saida2.='<tr id="despacho_'.$rs_enc['msg_usuario_id'].'" style="display:none;"><td colspan=20>'.$rs_enc['resposta_despacho'].'</td></tr>';
				}
			}
		foreach ($destinatarios_extras as $extra){
			$saida2.='<tr>';
			$saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$tipo[$extra['tipo']].'</td>';
			$saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.nome_funcao($extra['nome_usuario'], '', $extra['contato_funcao'], '').'</td>';
			$saida2.='<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;"> '.$extra['para'].' </td>';
			$saida2.='<td nowrap="nowrap" style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.retorna_data($extra['datahora']).'</td>';
			$saida2.='<td colspan=2>&nbsp;</td>';
			$saida2.='</tr>';
			}
		$saida2.='</table></td></tr><tr><td>&nbsp;</td></tr></table>';
		$saida2.=sombra_baixo('', 770);
		}
	return  $saida.'<br>'.$saida2;
	}

function formata_despacho5 ($rs_anotf=array()){
	global $Aplic;
	$saida='';
	if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '<b>';
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
  if ($rs_anotf['copia_oculta'] !=1 || ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= nome_funcao($rs_anotf['nome_para'], $rs_anotf['nome_usuario'], $rs_anotf['funcao_para'], $rs_anotf['contato_funcao'])."&nbsp;&nbsp;";
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 )) $saida.= '</i>';
  if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '</b>';
  return $saida;
	}


function formata_destinatario5($rs_para=array()){
	global $Aplic,$tipos_status;
	$saida='';
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
	if ($rs_para['copia_oculta'] !=1|| $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3) $saida.= nome_funcao($rs_para['nome_para'], $rs_para['nome_usuario'], $rs_para['funcao_para'], $rs_para['contato_funcao']);
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3)) $saida.= '</i>';
	if ($rs_para['copia_oculta'] !=1 || $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3){
		if (!$rs_para['datahora_leitura']){
			$saida.= ' - não lida';
			}
		else{
			$saida.= ' - '.$tipos_status[$rs_para['status']].' em '.retorna_data($rs_para['datahora_leitura']);
			if ($rs_para['cm'] > 1 ) $saida.= ' - (CM: '.nome_usuario($rs_para['cm']).' por '.$rs_para['meio'].') ';
			}
		}
	return $saida;
	}

function formata_destinatario_mail_externo($rs_para=array()){
	global $Aplic,$tipos_status;
	$saida='';
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
	if ($rs_para['copia_oculta'] !=1|| $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3) $saida.= nome_funcao($rs_para['nome_para'], $rs_para['nome_usuario'], $rs_para['funcao_para'], $rs_para['contato_funcao']);
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3)) $saida.= '</i>';
	if ($rs_para['copia_oculta'] !=1 || $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3){
		if (!$rs_para['datahora_leitura']){
			$saida.= ' - não lida';
			}
		else{
			$saida.= ' - '.$tipos_status[$rs_para['status']].' em '.retorna_data($rs_para['datahora_leitura']);
			if ($rs_para['cm'] > 1 ) $saida.= ' - (CM: '.nome_usuario($rs_para['cm']).' por '.$rs_para['meio'].') ';
			}
		}
	return $saida;
	}

function grava_arquivo_pg($pg_arquivo_pg_id=0, $campo, $pg_arquivo_campo=''){
	global $config, $Aplic;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	if(isset($_FILES[$campo]['name']) && file_exists($_FILES[$campo]['tmp_name']) && !empty($_FILES[$campo]['tmp_name'])){
	  //consulta quantos anexos já tem
	  $tipo=strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
	  $permitido=getSisValor('downloadPermitido');
	  $proibido=getSisValor('downloadProibido');
	  $verificar_malicioso=explode('.',$_FILES[$campo]['name']);
	 	$malicioso=false;
	 	foreach($verificar_malicioso as $extensao) {
	 		if (in_array(strtolower($extensao), $proibido)) {
	 			$malicioso=$extensao;
	 			break;
	 			}
	 		}
	 	if ($malicioso) {
	  	ver2('Extensão '.$malicioso.' não é permitida!');
	  	return false;
	  	}
	  elseif (!in_array($tipo, $permitido)) {
	  	ver2('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido');
	  	return false;
	  	}
		$sql = new BDConsulta;
	 	$sql->adTabela('plano_gestao_arquivos');
		$sql->adCampo('count(pg_arquivos_id) AS soma');
		$sql->adOnde('pg_arquivo_pg_id ='.(int)$pg_arquivo_pg_id);
		$sql->Limpar();
	  $soma_total = 1+(int)$sql->Resultado();
	  $caminho = 'pg'.$soma_total.'_'.$_FILES[$campo]['name'];
	  $caminho = removerSimbolos($caminho);
	  $caminho = removerSimbolos($caminho);
	  $caminho = removerSimbolos($caminho);

	 	if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/gestao')){
			$res = mkdir($base_dir.'/arquivos/gestao', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\gestao.', UI_MSG_ALERTA);
				return false;
				}
			}


	 	if (!is_dir($base_dir.'/arquivos/gestao/'.$pg_arquivo_pg_id)){
			$res = mkdir($base_dir.'/arquivos/gestao/'.$pg_arquivo_pg_id, 0777);
			if (!$res) {
				$Aplic->setMsg('A pasta para arquivos d'.$config['genero_plano_gestao'].' '.$config['plano_gestao'].' não foi configurada para receber arquivos - mude as permissões no arquivos\gestao.', UI_MSG_ALERTA);
				return false;
				}
			}
	  // move o arquivo para o destino
	  $caminho_completo = $base_dir.'/arquivos/gestao/'.$pg_arquivo_pg_id.'/'.$caminho;
	  move_uploaded_file($_FILES[$campo]['tmp_name'], $caminho_completo);
	  if (file_exists($caminho_completo)) {
	  	$tipo=explode('/',$_FILES[$campo]['type']);
	  	$sql->adTabela('plano_gestao_arquivos');
			$sql->adInserir('pg_arquivo_pg_id', $pg_arquivo_pg_id);
			$sql->adInserir('pg_arquivo_nome', $_FILES[$campo]['name']);
			$sql->adInserir('pg_arquivo_endereco', $pg_arquivo_pg_id.'/'.$caminho);
			$sql->adInserir('pg_arquivo_usuario', $Aplic->usuario_id);
			$sql->adInserir('pg_arquivo_data', date('Y-m-d H:i:s'));
			$sql->adInserir('pg_arquivo_campo', $pg_arquivo_campo);
			$sql->adInserir('pg_arquivo_ordem', $soma_total);
			$sql->adInserir('pg_arquivo_tipo', $tipo[0]);
			$sql->adInserir('pg_arquivo_extensao', $tipo[1]);
			if (!$sql->exec()) echo ('Não foi possível inserir o anexos na tabela plano_gestao_arquivos!');
			$sql->Limpar();
	  	}
		return true;
		}
	return false;
	}

function grava_arquivo_agenda($agenda_arquivo_agenda_id=0, $campo='arquivo'){
	global $config, $Aplic;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	if(isset($_FILES[$campo]['name']) && file_exists($_FILES[$campo]['tmp_name']) && !empty($_FILES[$campo]['tmp_name'])){
	  //consulta quantos anexos já tem
	  $tipo=strtolower(pathinfo($_FILES[$campo]['name'], PATHINFO_EXTENSION));
	  $permitido=getSisValor('downloadPermitido');
	  $proibido=getSisValor('downloadProibido');
	  $verificar_malicioso=explode('.',$_FILES[$campo]['name']);
	 	$malicioso=false;
	 	foreach($verificar_malicioso as $extensao) {
	 		if (in_array(strtolower($extensao), $proibido)) {
	 			$malicioso=$extensao;
	 			break;
	 			}
	 		}
	 	if ($malicioso) {
	  	ver2('Extensão '.$malicioso.' não é permitida!');
	  	return false;
	  	}
	  elseif (!in_array($tipo, $permitido)) {
	  	ver2('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido');
	  	return false;
	  	}
		$sql = new BDConsulta;
	 	$sql->adTabela('agenda_arquivos');
		$sql->adCampo('count(agenda_arquivo_id) AS soma');
		$sql->adOnde('agenda_arquivo_agenda_id ='.(int)$agenda_arquivo_agenda_id);
	  $soma_total = 1+(int)$sql->Resultado();
	  $sql->Limpar();
	  $caminho = $soma_total.'_'.$_FILES[$campo]['name'];
	  $caminho = removerSimbolos($caminho);
	  $caminho = removerSimbolos($caminho);
	  $caminho = removerSimbolos($caminho);

	  if (!is_dir($base_dir)){
			$res = mkdir($base_dir, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
				return false;
				}
			}

	  if (!is_dir($base_dir.'/arquivos')){
			$res = mkdir($base_dir.'/arquivos', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/agendas')){
			$res = mkdir($base_dir.'/arquivos/agendas', 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\agendas.', UI_MSG_ALERTA);
				return false;
				}
			}

	 	if (!is_dir($base_dir.'/arquivos/agendas/'.$agenda_arquivo_agenda_id)){
			$res = mkdir($base_dir.'/arquivos/agendas/'.$agenda_arquivo_agenda_id, 0777);
			if (!$res) {
				$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\agendas\.', UI_MSG_ALERTA);
				return false;
				}
			}


	  // move o arquivo para o destino
	  $caminho_completo = $base_dir.'/arquivos/agendas/'.$agenda_arquivo_agenda_id.'/'.$caminho;
	  move_uploaded_file($_FILES[$campo]['tmp_name'], $caminho_completo);
	  if (file_exists($caminho_completo)) {
	  	$tipo=explode('/',$_FILES[$campo]['type']);
	  	$sql->adTabela('agenda_arquivos');
			$sql->adInserir('agenda_arquivo_agenda_id', $agenda_arquivo_agenda_id);
			$sql->adInserir('agenda_arquivo_nome', $_FILES[$campo]['name']);
			$sql->adInserir('agenda_arquivo_endereco', $agenda_arquivo_agenda_id.'/'.$caminho);
			$sql->adInserir('agenda_arquivo_usuario', $Aplic->usuario_id);
			$sql->adInserir('agenda_arquivo_data', date('Y-m-d H:i:s'));
			$sql->adInserir('agenda_arquivo_ordem', $soma_total);
			$sql->adInserir('agenda_arquivo_tipo', $tipo[0]);
			$sql->adInserir('agenda_arquivo_extensao', $tipo[1]);
			if (!$sql->exec()) echo ('Não foi possível inserir o anexos na tabela plano_gestao_arquivos!');
			$sql->Limpar();
	  	}
		return true;
		}
	return false;
	}

function grava_arquivo_evento($evento_arquivo_evento_id=0, $campo='arquivo'){
	global $config, $Aplic;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	if(isset($_FILES[$campo]['tmp_name'])){
		$data = date('Y-m-d H:i:s');
		foreach($_FILES[$campo]['tmp_name'] as $chave=> $valor){
			if(isset($_FILES[$campo]['name']) && file_exists($_FILES[$campo]['tmp_name'][$chave]) && !empty($_FILES[$campo]['tmp_name'][$chave])){
			  //consulta quantos anexos já tem
			  $tipo=strtolower(pathinfo($_FILES[$campo]['name'][$chave], PATHINFO_EXTENSION));
			  $permitido=getSisValor('downloadPermitido');
			  $proibido=getSisValor('downloadProibido');
			  $verificar_malicioso=explode('.',$_FILES[$campo]['name'][$chave]);
			 	$malicioso=false;
			 	foreach($verificar_malicioso as $extensao) {
			 		if (in_array(strtolower($extensao), $proibido)) {
			 			$malicioso=$extensao;
			 			break;
			 			}
			 		}
			 	if ($malicioso) {
			  	ver2('Extensão '.$malicioso.' não é permitida!');
			  	return false;
			  	}
			  elseif (!in_array($tipo, $permitido)) {
			  	ver2('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido');
			  	return false;
			  	}
				$sql = new BDConsulta;
			 	$sql->adTabela('evento_arquivos');
				$sql->adCampo('count(evento_arquivo_id) AS soma');
				$sql->adOnde('evento_arquivo_evento_id ='.(int)$evento_arquivo_evento_id);
			  $soma_total = 1+(int)$sql->Resultado();
			  $sql->Limpar();
			  $caminho = $soma_total.'_'.$_FILES[$campo]['name'][$chave];
			  $caminho = removerSimbolos($caminho);
			  $caminho = removerSimbolos($caminho);
			  $caminho = removerSimbolos($caminho);

			  if (!is_dir($base_dir)){
					$res = mkdir($base_dir, 0777);
					if (!$res) {
						ver2('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
						return false;
						}
					}

			  if (!is_dir($base_dir.'/arquivos')){
					$res = mkdir($base_dir.'/arquivos', 0777);
					if (!$res) {
						ver2('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
						return false;
						}
					}

			 	if (!is_dir($base_dir.'/arquivos/eventos')){
					$res = mkdir($base_dir.'/arquivos/eventos', 0777);
					if (!$res) {
						ver2('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos.', UI_MSG_ALERTA);
						return false;
						}
					}

			 	if (!is_dir($base_dir.'/arquivos/eventos/'.$evento_arquivo_evento_id)){
					$res = mkdir($base_dir.'/arquivos/eventos/'.$evento_arquivo_evento_id, 0777);
					if (!$res) {
						ver2('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\arquivos\eventos\.', UI_MSG_ALERTA);
						return false;
						}
					}
			  // move o arquivo para o destino
			  $caminho_completo = $base_dir.'/arquivos/eventos/'.$evento_arquivo_evento_id.'/'.$caminho;
			  move_uploaded_file($_FILES[$campo]['tmp_name'][$chave], $caminho_completo);
			  if (file_exists($caminho_completo)) {
			  	$tipo=explode('/',$_FILES[$campo]['type'][$chave]);
			  	$sql->adTabela('evento_arquivos');
					$sql->adInserir('evento_arquivo_evento_id', $evento_arquivo_evento_id);
					$sql->adInserir('evento_arquivo_nome', $_FILES[$campo]['name'][$chave]);
					$sql->adInserir('evento_arquivo_endereco', $evento_arquivo_evento_id.'/'.$caminho);
					$sql->adInserir('evento_arquivo_usuario', $Aplic->usuario_id);
					$sql->adInserir('evento_arquivo_data', date('Y-m-d H:i:s'));
					$sql->adInserir('evento_arquivo_ordem', $soma_total);
					$sql->adInserir('evento_arquivo_tipo', $tipo[0]);
					$sql->adInserir('evento_arquivo_extensao', $tipo[1]);
					if (!$sql->exec()) echo ('Não foi possível inserir o anexos na tabela plano_gestao_arquivos!');
					$sql->Limpar();
			  	}
				}
			}
		}
	}

function grava_anexo($doc_nr='', $campo_tipo='', $campo='', $nome_fantasia='', $id_mensagem=''){
	global $config, $msg_id, $Aplic;
	 $base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	//para mensagem de popup
	if ($id_mensagem)$msg_id=$id_mensagem;


	if(isset($_FILES[$campo]['tmp_name'])){
		$data = date('Y-m-d H:i:s');
		foreach($_FILES[$campo]['tmp_name'] as $chave=> $valor){
			if(isset($_FILES[$campo]['name']) && file_exists($_FILES[$campo]['tmp_name'][$chave]) && !empty($_FILES[$campo]['tmp_name'][$chave])){
			 	$tipo=strtolower(pathinfo($_FILES[$campo]['name'][$chave], PATHINFO_EXTENSION));
			  $permitido=getSisValor('downloadPermitido');

			  $proibido=getSisValor('downloadProibido');
			  $verificar_malicioso=explode('.',$_FILES[$campo]['name'][$chave]);
			 	$malicioso=false;
			 	foreach($verificar_malicioso as $extensao) {
			 		if (in_array(strtolower($extensao), $proibido)) {
			 			$malicioso=$extensao;
			 			break;
			 			}
			 		}
			 	if ($malicioso) {
			  	ver2('Extensão '.$malicioso.' não é permitida!');
			  	return false;
			  	}
			  elseif (!in_array($tipo, $permitido)) {
			  	ver2('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido');
			  	return false;
			  	}



			  //consulta quantos anexos já tem
				$sql = new BDConsulta;
			 	$sql->adTabela('anexos');
				$sql->adCampo('count(anexo_id) AS soma');
				$sql->adOnde('msg_id ='.(int)$msg_id);
				$sql->adGrupo('msg_id');
			  $soma_total = 1+(int)$sql->Resultado();
			  $sql->Limpar();
			  $caminho = 'M'.$msg_id.'_'.$soma_total.'_'.$_FILES[$campo]['name'][$chave];
			  $caminho = removerSimbolos($caminho);
			  $caminho = removerSimbolos($caminho);
			  $caminho = removerSimbolos($caminho);
			  //verifica se existe a pasta de anexos

			  if ($config['pasta_anexos']) {

					if (!is_dir($base_dir)){
						$res = mkdir($base_dir, 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
							return false;
							}
						}

					if (!is_dir($base_dir.'/'.$config['pasta_anexos'])){
						$res = mkdir($base_dir.'/'.$config['pasta_anexos'], 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
							return false;
							}
						@copy(BASE_DIR.'/modulos/index.php',$base_dir.'/'.$config['pasta_anexos'].'/index.php');
						}
				 	}
				$pasta=($config['pasta_anexos'] ? $config['pasta_anexos'].'/':'');
			  $ano = date('Y');
			  $ano_c = $base_dir.'/'.$pasta.$ano;
			  //verifica se existe a pasta ano
			  if( !file_exists($ano_c) ) {
			    mkdir($ano_c,0777);
			    @copy(BASE_DIR.'/modulos/index.php',$ano_c.'/index.php');
			  	}
			  //verifica se existe a pasta mes
			  $mes = date('m');
			  $mes_c = $ano_c.'/'.$mes;
			  if( !file_exists($mes_c) ) {
			    mkdir($mes_c,0777);
			    @copy(BASE_DIR.'/modulos/index.php',$mes_c.'/index.php');
			  	}
			  // move o arquivo para o destino
			  $caminho_completo = $base_dir.'/'.$pasta.$ano.'/'.$mes.'/'.$caminho;
			  move_uploaded_file($_FILES[$campo]['tmp_name'][$chave], $caminho_completo);
			  if (file_exists($caminho_completo)) {
			  $assinatura='';
				if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
					$identificador=$msg_id.$_FILES[$campo]['name'][$chave].($ano.'/'.$mes.'/'.$caminho).$Aplic->usuario_id.$campo_tipo[$chave].$doc_nr[$chave].$data;
					openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
					}
			  	$sql->adTabela('anexos');
					$sql->adInserir('msg_id', $msg_id);
					$sql->adInserir('nome', $_FILES[$campo]['name'][$chave]);
					$sql->adInserir('caminho', $ano.'/'.$mes.'/'.$caminho);
					$sql->adInserir('usuario_id', $Aplic->usuario_id);
					$sql->adInserir('tipo_doc', $campo_tipo[$chave]);
					$sql->adInserir('doc_nr', $doc_nr[$chave]);
					$sql->adInserir('nome_de', $Aplic->usuario_nome);
					$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
					$sql->adInserir('data_envio', $data);
					$sql->adInserir('assinatura', base64_encode($assinatura));
					if ($Aplic->chave_publica_id) $sql->adInserir('chave_publica', $Aplic->chave_publica_id);
					$sql->adInserir('nome_fantasia', $nome_fantasia[$chave]);
					if (!$sql->exec()) echo ('Não foi possível inserir os anexos na tabela anexos!');
					$sql->Limpar();
			  	}
				}
			}
		}
	}

function grava_anexo_modelo($modelo_id='', $idunico='', $campo='', $doc_nr='', $campo_tipo='',  $nome_fantasia=''){
	global $config, $modelo_id,  $Aplic;
	$data = date('Y-m-d H:i:s');
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	if (isset($_FILES[$campo]['tmp_name'])){
		foreach($_FILES[$campo]['tmp_name'] as $chave=> $valor){

			if(isset($_FILES[$campo]['name'][$chave]) && file_exists($_FILES[$campo]['tmp_name'][$chave]) && !empty($_FILES[$campo]['tmp_name'][$chave])){
			  $tipo=strtolower(pathinfo($_FILES[$campo]['name'][$chave], PATHINFO_EXTENSION));
			  $permitido=getSisValor('downloadPermitido');

			  $proibido=getSisValor('downloadProibido');
			  $verificar_malicioso=explode('.',$_FILES[$campo]['name'][$chave]);
			 	$malicioso=false;
			 	foreach($verificar_malicioso as $extensao) {
			 		if (in_array(strtolower($extensao), $proibido)) {
			 			$malicioso=$extensao;
			 			break;
			 			}
			 		}
			 	if ($malicioso) {
			  	ver2('Extensão '.$malicioso.' não é permitida!');
			  	return false;
			  	}
			  elseif (!in_array($tipo, $permitido)) {
			  	ver2('Extensão '.$tipo.' não é permitida! Precisa ser '.implode(', ',$permitido).'. Para incluir nova extensão o administrador precisa ir em Menu=>Sistema=>Valores de campos do sistema=>downloadPermitido');
			  	return false;
			  	}

			  //consulta quantos anexos já tem
				$sql = new BDConsulta;
			 	$sql->adTabela('modelos_anexos');
				$sql->adCampo('count(modelo_anexo_id) AS soma');
				if ($modelo_id) {
					$sql->adOnde('modelo_id = '.(int)$modelo_id);
					$sql->adGrupo('modelo_id');
					}
				else {
					$sql->adOnde('idunico = "'.$idunico.'"');
					$sql->adGrupo('idunico');
					}
			  $soma_total = 1+(int)$sql->Resultado();
			  $sql->Limpar();
			  $caminho = 'M'.(int)$modelo_id.'_'.$soma_total.'_'.$_FILES[$campo]['name'][$chave];
			  $caminho = removerSimbolos($caminho);
			  $caminho = removerSimbolos($caminho);
			  $caminho = removerSimbolos($caminho);



			  //verifica se existe a pasta de anexos
			  if ($config['pasta_anexos']) {


			  	if (!is_dir($base_dir)){
						$res = mkdir($base_dir, 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões na raiz de '.$base_dir, UI_MSG_ALERTA);
							return false;
							}
						}

			  	if (!is_dir($base_dir.'/'.$config['pasta_anexos'].'_modelos')){
						$res = mkdir($base_dir.'/'.$config['pasta_anexos'].'_modelos', 0777);
						if (!$res) {
							$Aplic->setMsg('Não foi possível criar a pasta para receber o arquivo - mude as permissões em '.$base_dir.'\.', UI_MSG_ALERTA);
							return false;
							}
						@copy(BASE_DIR.'/modulos/index.php', $base_dir.'/'.$config['pasta_anexos'].'_modelos/index.php');
						}
				 	}
				$pasta=($config['pasta_anexos'] ? $config['pasta_anexos'].'_modelos/':'');
			  $ano = date('Y');
			  $ano_c =  $base_dir.'/'.$pasta.$ano;
			  //verifica se existe a pasta ano
			  if( !file_exists($ano_c) ) {
			    mkdir($ano_c,0777);
			    copy(BASE_DIR.'/modulos/index.php',$ano_c.'/index.php');
			  	}
			  //verifica se existe a pasta mes
			  $mes = date('m');
			  $mes_c = $ano_c.'/'.$mes;
			  if( !file_exists($mes_c)) {
			    mkdir($mes_c,0777);
			    @copy(BASE_DIR.'/modulos/index.php',$mes_c.'/index.php');
			  	}
			  // move o arquivo para o destino
			  $caminho_completo = $base_dir.'/'.$pasta.$ano.'/'.$mes.'/'.$caminho;
			  move_uploaded_file($_FILES[$campo]['tmp_name'][$chave], $caminho_completo);
			  if (file_exists($caminho_completo)) {
			  $assinatura='';
				if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
					$identificador=$_FILES[$campo]['name'][$chave].($ano.'/'.$mes.'/'.$caminho).$Aplic->usuario_id.$campo_tipo[$chave].$doc_nr[$chave].$data.$modelo_id;
					openssl_sign($identificador, $assinatura, $Aplic->chave_privada);
					}
			  	$sql->adTabela('modelos_anexos');
					if ($modelo_id) $sql->adInserir('modelo_id', $modelo_id);
					else $sql->adInserir('idunico', $idunico);
					$sql->adInserir('nome', $_FILES[$campo]['name'][$chave]);
					$sql->adInserir('caminho', $ano.'/'.$mes.'/'.$caminho);
					$sql->adInserir('usuario_id', $Aplic->usuario_id);
					$sql->adInserir('tipo_doc', $campo_tipo[$chave]);
					$sql->adInserir('doc_nr', $doc_nr[$chave]);
					$sql->adInserir('nome_de', $Aplic->usuario_nome);
					$sql->adInserir('funcao_de', $Aplic->usuario_funcao);
					$sql->adInserir('data_envio', $data);
					$sql->adInserir('assinatura', base64_encode($assinatura));
					if ($Aplic->chave_publica_id) $sql->adInserir('chave_publica', $Aplic->chave_publica_id);
					$sql->adInserir('nome_fantasia', $nome_fantasia[$chave]);
					if (!$sql->exec()) echo ('Não foi possível inserir os anexos na tabela anexos!');
					$sql->Limpar();
			  	}
				}
			}
		}
	}

function checa_cpf ($cpf){

    $cpf=preg_replace('/[^0-9]/', '', $cpf);
		if(strlen($cpf) != 11) {
			echo '<script>alert("Tamanho do CPF está incorreto")</script>';
			return false;
			}
		else $cpf_dv = substr($cpf, 9,2);
		for($i=0; $i<=8; $i++) $digito[$i] = substr($cpf, $i,1);
		$posicao = 10;
		$soma = 0;
		for($i=0; $i<=8; $i++){
			$soma = $soma + $digito[$i] * $posicao;
			$posicao = $posicao - 1;
			}
		$digito[9] = $soma % 11;
		if($digito[9] < 2) $digito[9] = 0;
		else $digito[9] = 11 - $digito[9];
		$posicao = 11;
		$soma = 0;
		for ($i=0; $i<=9; $i++)	{
			$soma = $soma + $digito[$i] * $posicao;
			$posicao = $posicao - 1;
			}
		$digito[10] = $soma % 11;
		if ($digito[10] < 2) $digito[10] = 0;
		else $digito[10] = 11 - $digito[10];
		$dv = $digito[9] * 10 + $digito[10];
		if ($dv != $cpf_dv)	{
			echo '<script>alert ("Este CPF não é válido")</script>';
			return false;
			}
		else return true;
		}

	function checa_cnpj($cnpj){
		if ((!is_numeric($cnpj)) or (strlen($cnpj) != 14)) return 2;
		else {
			$i = 0;
			while ($i < 14){
				$cnpj_d[$i] = substr($cnpj,$i,1);
				$i++;
				}
			$dv_ori = $cnpj[12].$cnpj[13];
			$soma1 = 0;
			$soma1 = $soma1 + ($cnpj[0] * 5);
			$soma1 = $soma1 + ($cnpj[1] * 4);
			$soma1 = $soma1 + ($cnpj[2] * 3);
			$soma1 = $soma1 + ($cnpj[3] * 2);
			$soma1 = $soma1 + ($cnpj[4] * 9);
			$soma1 = $soma1 + ($cnpj[5] * 8);
			$soma1 = $soma1 + ($cnpj[6] * 7);
			$soma1 = $soma1 + ($cnpj[7] * 6);
			$soma1 = $soma1 + ($cnpj[8] * 5);
			$soma1 = $soma1 + ($cnpj[9] * 4);
			$soma1 = $soma1 + ($cnpj[10] * 3);
			$soma1 = $soma1 + ($cnpj[11] * 2);
			$rest1 = $soma1 % 11;
			if ($rest1 < 2)	$dv1 = 0;
			else $dv1 = 11 - $rest1;
			$soma2 = $soma2 + ($cnpj[0] * 6);
			$soma2 = $soma2 + ($cnpj[1] * 5);
			$soma2 = $soma2 + ($cnpj[2] * 4);
			$soma2 = $soma2 + ($cnpj[3] * 3);
			$soma2 = $soma2 + ($cnpj[4] * 2);
			$soma2 = $soma2 + ($cnpj[5] * 9);
			$soma2 = $soma2 + ($cnpj[6] * 8);
			$soma2 = $soma2 + ($cnpj[7] * 7);
			$soma2 = $soma2 + ($cnpj[8] * 6);
			$soma2 = $soma2 + ($cnpj[9] * 5);
			$soma2 = $soma2 + ($cnpj[10] * 4);
			$soma2 = $soma2 + ($cnpj[11] * 3);
			$soma2 = $soma2 + ($dv1 * 2);
			$rest2 = $soma2 % 11;
			if ($rest2 < 2)	$dv2 = 0;
			else $dv2 = 11 - $rest2;
			$dv_calc = $dv1.$dv2;
			if ($dv_ori == $dv_calc) return 0;
			else return 1;
			}
		}

function link_email_interno($msg_usuario_id){
	global $Aplic,$config, $precedencia,$class_sigilosa, $status;
	if (!$msg_usuario_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$precedencia=getSisValor('precedencia');
		$class_sigilosa=getSisValor('class_sigilosa');
		$sql = new BDConsulta();
		$sql->adTabela('msg_usuario');
	  $sql->adUnir('msg','msg','msg_usuario.msg_id = msg.msg_id');
	  $sql->esqUnir('usuarios','usuarios', 'msg_usuario.de_id=usuarios.usuario_id');
	 	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	  $sql->adCampo('contatos.contato_funcao, msg.precedencia, msg.data_envio, msg.class_sigilosa, msg.referencia, msg_usuario.status, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.msg_id, datahora');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	  $sql->adGrupo('msg.msg_id, msg_usuario.status');
		$linha = $sql->Linha();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Assunto</b></td><td>'.$linha['referencia'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Remetente</b></td><td>'.nome_funcao('',$linha['nome_usuario'], '', $linha['contato_funcao']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data de criação</b></td><td>'.retorna_data($linha['data_envio']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data de envio</b></td><td>'.retorna_data($linha['datahora']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Precedência</b></td><td>'.$precedencia[$linha['precedencia']].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Sigiloso</b></td><td>'.$class_sigilosa[$linha['class_sigilosa']].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver os detalhes d'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.';
		return dica('<b>'.ucfirst($config['mensagem']).' Nº '.$linha['msg_id'].'</b>', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_usuario_id='.$msg_usuario_id.'\');">'.$linha['msg_id'].'</a>'.dicaF();
		}
	else {

		$sql = new BDConsulta();
		$sql->adTabela('msg_usuario');
	  $sql->adCampo('msg_id');
		$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	  $sql->adGrupo('msg_id');
		$linha = $sql->Linha();

		return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_usuario_id='.$msg_usuario_id.'\');">'.$linha['msg_id'].'</a>';
		}
	}

function link_documento_interno($modelo_id){
	global $Aplic,$config, $status;
	if (!$modelo_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$sql = new BDConsulta();
		$sql->adTabela('modelos');
	  $sql->adUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
	  $sql->esqUnir('usuarios','usuarios', 'modelo_usuario.'.($status==5 ? 'de_id' : 'para_id').'=usuarios.usuario_id');
	  $sql->esqUnir('modelos_tipo', 'modelos_tipo', 'modelo_tipo_id = modelo_tipo');
	 	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	  $sql->adCampo('modelo_criador_original, modelo_tipo_nome, contatos.contato_funcao, modelo_data, modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.modelo_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		if ($status && $status!=5) $sql->adOnde('modelo_usuario.para_id = '.$Aplic->usuario_id);
		$sql->adOnde('modelos.modelo_id = '.$modelo_id);
		$sql->adGrupo('modelo_usuario.modelo_id, modelo_usuario.status, modelos.modelo_criador_original, modelos_tipo.modelo_tipo_nome, contatos.contato_funcao, modelo_data, modelo_assunto, modelo_usuario.status, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.modelo_id, contatos.contato_posto, contatos.contato_nomeguerra');
		$linha = $sql->Linha();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$linha['modelo_tipo_nome'].'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Assunto</b></td><td>'.$linha['modelo_assunto'].'</td></tr>';
		if ($linha['nome_usuario']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Remetente</b></td><td>'.nome_funcao($linha['nome_usuario'],'',$linha['contato_funcao']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Criador</b></td><td>'.nome_funcao('','','','',$linha['modelo_criador_original']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data de criação</b></td><td>'.retorna_data($linha['modelo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este documento.';
		return dica('<b>Documento Nº '.$modelo_id.'</b>', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=modelo_editar&modelo_id='.$modelo_id.'\');">'.$modelo_id.'</a>'.dicaF();
		}
	else return '<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=modelo_editar&modelo_id='.$modelo_id.'\');">'.$modelo_id.'</a>';
	}

function relacao_mensagens(){
	global $vetor_msg_usuario, $modelo_usuario_id, $config;
	$saida='';
	if (!count($vetor_msg_usuario) && $modelo_usuario_id) $vetor_msg_usuario[]=$modelo_usuario_id;
	$tamanho = sizeof($vetor_msg_usuario);
	$saida= ($tamanho > 1 ? $config['genero_mensagem'].'s '.$config['mensagens'] : $config['genero_mensagem'].' '.$config['mensagem']);
	$saida.='<table width="80" class="tbl1"><tr><td align="center">';
	if ($tamanho > 0) {
		$contar=0;
		foreach((array)$vetor_msg_usuario as $chave => $valor) {
			if ($valor)	$saida.= ($contar++ ? '<br>' : '').link_email_interno($valor);
			}
		}
	$saida.='</td></tr></table>';
	return $saida;
	}

function relacao_documentos(){
	global $modeloID, $modelo_id, $vetor_modelo_msg_usuario;
	$saida='';
	if ((isset($vetor_modelo_msg_usuario) && $vetor_modelo_msg_usuario) || (isset($modelo_usuario_id) && $modelo_usuario_id)){
		if (!isset($vetor_modelo_msg_usuario)) $vetor_modelo_msg_usuario[]=$modelo_usuario_id;
		$modeloID=array();
		$sql = new BDConsulta;
		foreach((array)$vetor_modelo_msg_usuario as $chave => $valor) {
			$sql->adTabela('modelo_usuario');
			$sql->adCampo('modelo_id');
			$sql->adOnde('modelo_usuario_id = '.$valor);
			$modeloID[]=$sql->Resultado();
			$sql->limpar();
			}
		}
	if (!count($modeloID) && $modelo_id) $modeloID[]=$modelo_id;
	$tamanho = sizeof($modeloID);
	$saida= ($tamanho > 1 ?'os documentos' : 'o documento');
	$saida.='<table width="80" class="tbl1"><tr><td align="center">';
	if ($tamanho > 0) {
		$contar=0;
		foreach((array)$modeloID as $chave => $valor) {
			if ($valor)	$saida.= ($contar++ ? '<br>' : '').link_documento_interno($valor);
			}
		}
	$saida.='</td></tr></table>';
	return $saida;
	}

function sombra_baixo ($link='', $largura='770'){
	global $estilo, $a;
	if ($a=='modelo_exibir') return '<table align="center" border=0 cellspacing=0 width="'.$largura.'" cellpadding=0><td align="left">&nbsp;</td></table>';
	else return '<table align="center" border=0 cellspacing=0 width="'.$largura.'" cellpadding=0><th style="background: url('.$link.'estilo/rondon/imagens/nav_sombra.jpg);" align="left">&nbsp;</th></table>';
	}

function removerSimbolos($Msg)
	{
  $a = array(
	'/[ÂÀÁÄÃ]/'=>'A',
	'/[âãàáäª]/'=>'a',
	'/[ÊÈÉË]/'=>'E',
	'/[êèéë]/'=>'e',
	'/[ÎÍÌÏ]/'=>'I',
	'/[îíìï]/'=>'i',
	'/[ÔÕÒÓÖ]/'=>'O',
	'/[ôõòóöº°]/'=>'o',
	'/[ÛÙÚÜ]/'=>'U',
	'/[ûúùü]/'=>'u',
	'/ç/'=>'c',
	'/Ç/'=> 'C',
  '/ /'=> '_',
  '/  /'=> '_',
  '/-/'=> '_',
  '/\+/'=> '_',
  '/__/'=> '_',
  '/\'/'=> '_',
  '/\"/'=> '_',
  '/\(/'=> '_',
  '/\)/'=> '_',
  '/\[/'=> '_',
  '/\]/'=> '_',
 	'/,/'=> '_',
  '/\*/'=> '_'
	);
  return preg_replace(array_keys($a), array_values($a), $Msg);
	}

function retorna_encaminha ($usuario){
	if ($usuario){
		$sql = new BDConsulta;
		$sql->adTabela('preferencia');
		$sql->adCampo('encaminhar');
		$sql->adOnde('usuario = '.(int)$usuario);
		return $sql->Resultado();
		$sql->limpar();
		}
	else	return '';
	}

function retornar_cores ($status){
	global $Aplic;
	//status == 2 msg não lida
	if ($status == 2) return $linha = 'align=center bgcolor="#'.$Aplic->cor_msg_nao_lida.'" onmouseover="style.backgroundColor=\'#'.$Aplic->cor_msg_realce.'\'" onmouseout="style.backgroundColor=\'#'.$Aplic->cor_msg_nao_lida.'\'"';
	if ($status == 1) return $linha = 'align=center bgcolor="#e0dede" onmouseover="style.backgroundColor=\'#'.$Aplic->cor_msg_realce.'\'" onmouseout="style.backgroundColor=\'#e0dede\'"';
	return 'align=center bgcolor="#cccccc" onmouseover="style.backgroundColor=\'#'.$Aplic->cor_msg_realce.'\'" onmouseout="style.backgroundColor=\'#cccccc\'"';
	}

function retorna_tira_duas_linhas($texto){
	global $Aplic;
	if ($Aplic->profissional) return $texto;

	$texto = str_replace("\r\n", "<br>", $texto);
  $texto = str_replace("\n", "<br>", $texto);
  $texto = nl2br($texto);
  $texto = str_replace('<br >', "", $texto);
  return ($texto);
	}

function retorna_data ($valor, $hora=true, $permite_branco=false, $formato=false){
	global $Aplic;
	if (!$valor) return '';
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	$data = new CData($valor);
	if ((!$permite_branco || $valor) && !$formato) $saida=($hora ? $data->format($df.' '.$tf) : $data->format($df));
	else if ((!$permite_branco || $valor)) $saida=$data->format($formato);
	else $saida='&nbsp;';
  return $saida;
	}

function retorna_data_extenso($valor){
	global $Aplic;
	$nome_meses=array('01'=>'janeiro', '02'=>'fevereiro', '03'=>'março', '04'=>'abril', '05'=>'maio', '06'=>'junho', '07'=>'julho', '08'=>'agosto', '09'=>'setembro', '10'=>'outubro', '11'=>'novembro', '12'=>'dezembro');
	$data=explode('-',$valor);
  return $data[2].' de '.$nome_meses[$data[1]].' de '.$data[0];
	}

function ver($valor){
	exit(var_dump($valor));
	}

function ver2($valor){
	echo '<script type="text/javascript">alert("'.$valor.'")</script>'."\n";
	}

function ver3($valor){
	exit($valor->comando_sql());
	}

function ver4($valor){
	return ($valor->comando_sql());
	}

function ver5($valor){
	$valor="\n\n".date('d/m/Y H:i:s')."\n************************************************\n".$valor;
	$arquivo=BASE_DIR.'/log_'.date('Y_m_d').'.txt';
	if (!file_exists($arquivo)){
		$fp = fopen($arquivo, 'w');
		fwrite($fp, $valor);
		fclose($fp);
		return;
		}
	if (is_writable($arquivo)) {
		if (!$handle = fopen($arquivo, 'a')) {
			ver2("Não foi possível abrir o arquivo ($arquivo)");
			return;
			}
    if (fwrite($handle, $valor) === FALSE) {
			ver2("Não foi possível escrever no arquivo ($arquivo)");
			return;
			}
    fclose($handle);
		}
	}

function botao($legenda, $titulo = '', $texto='', $href='', $clicando='', $prefixo = '', $sufixo = '', $espacamento=5, $icone = '', $classe='botao') {
		return '<table cellspacing='.$espacamento.' cellpadding=0 border=0><tr>'.($prefixo ? '<td nowrap="nowrap">'.$prefixo.'</td>':'').'<td nowrap="nowrap">'. dica($titulo, $texto). '<a class="'.$classe.'" href="'.($href ? $href : 'javascript: void(0);').'" '.($clicando ? ' onclick="'.$clicando.'" ':'').'><span>'.($icone ? imagem($icone):'').str_ireplace(' ','&nbsp;', $legenda).'</span></a>'.dicaF().'</td>'.($sufixo ? '<td nowrap="nowrap">'.$sufixo.'</td>':'').'</tr></table>';
		}

function botao_icone($icone, $titulo = '', $texto='',  $clicando='') {
		return '<a href="javascript:void(0);" onclick="'.$clicando.'">'.imagem('icones/'.$icone, $titulo, 'Clique neste ícone '.imagem('icones/'.$icone).' para '.$texto).'</a>';
		}


function selecionaDept($campo, $extras='', $escolhido=0, $nenhumaOpcao='', $cia_id){
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	$sql->adCampo('dept_nome, dept_id, dept_acesso');
	$sql->adOnde('dept_cia='.(int)$cia_id);
	$sql->adOrdem('dept_nome ASC');
	$linhas = $sql->Lista();
	$sql->limpar();
	$listagem_dept=array();
	$listagem_dept = array(0 => $nenhumaOpcao)+$listagem_dept;
	if ($Aplic->checarModulo('depts', 'acesso')){
		foreach ($linhas as $dept) if (permiteAcessarDept($dept['dept_acesso'], $dept['dept_id'])) $listagem_dept[$dept['dept_id']]=$dept['dept_nome'];
		}
	return selecionaVetor($listagem_dept, $campo, $extras, $escolhido);
	}

function mostrarBarraNav($totalregistros, $tamanhoPagina, $total_paginas, $pagina, $tipo='Registro', $tipos='Registros', $classe='', $extra='', $cor_fundo='') {
	global $Aplic, $m, $a, $projeto_id, $tarefa_id;

	if ($cor_fundo=='006fc2') $estilo='style="color: #ffffff; background-color: #'.$cor_fundo.'"';
	elseif ($cor_fundo) $estilo='style="background-color: #'.$cor_fundo.'"';
	else $estilo='';

	$parar = false;
	$pag_ant = $pag_prox = 0;

	$saida='';
	$qnt=0;
	foreach($_REQUEST as $chave => $valor){
		if ($chave!='pagina'){
			if(!is_array($valor)) $saida.=($qnt++ ? '&' : '').$chave.'='.$valor;
			else{
				foreach($valor as $v){
					$saida.=($qnt++ ? '&' : '').$chave.'[]='.$v;
					}
				}
			}
		}
	$s = '<table width="100%" cellspacing=0 cellpadding=0 border=0 '.$classe.' '.$estilo.'><tr>';
	if ($totalregistros > $tamanhoPagina) {
		$pag_ant = $pagina - 1;
		$pag_prox = $pagina + 1;
		if ($pag_ant > 0)	$s .= '<td align="left" width="15%"><a href="javascript:void(0);" onclick="url_passar(0, \''.$saida.'&pagina=1'.$extra.'\');">'.dica('Primeira Página', 'Clique neste ícone '.imagem('icones/'.($cor_fundo=='006fc2' ? 'navPrimeira_metro.png' : 'navPrimeira.gif')).' para ir até a primeira página.').'<img src="'.acharImagem(($cor_fundo=='006fc2' ? 'navPrimeira_metro.png' : 'navPrimeira.gif')).'" border=0>'.dicaF().'</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="url_passar(0, \''.$saida.'&pagina='.$pag_ant.$extra.'\');">'.dica('Página Anterior', 'Clique neste ícone '.imagem('icones/'.($cor_fundo=='006fc2' ? 'navAnterior_metro.png' : 'navPrimeira.gif')).' para ir até a página anterior.').'<img src="'.acharImagem(($cor_fundo=='006fc2' ? 'navAnterior_metro.png' : 'navAnterior.gif')).'" border=0 >'.dicaF().'</a></td>';
		else $s .= '<td width="15%">&nbsp;</td>';
		$s .= '<td align="center" width="70%">'.$totalregistros.' '.($totalregistros > 1 ? $tipos : $tipo).' ('.$total_paginas.' página'.($total_paginas > 1 ? 's': '').')</td>';
		if ($pag_prox <= $total_paginas) $s .= '<td align="right" width="15%"><a href="javascript:void(0);" onclick="url_passar(0, \''.$saida.'&pagina='.$pag_prox.$extra.'\');">'.dica('Próxima Página', 'Clique neste ícone '.imagem('icones/'.($cor_fundo=='006fc2' ? 'navProximo_metro.png' : 'navProximo.gif')).' para ir até a próxima página.').'<img src="'.acharImagem(($cor_fundo=='006fc2' ? 'navProximo_metro.png' : 'navProximo.gif')).'" border=0>'.dicaF().'</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="url_passar(0, \''.$saida.'&pagina='.$total_paginas.$extra.'\');">'.dica('Última Página', 'Clique neste ícone '.imagem('icones/'.($cor_fundo=='006fc2' ? 'navUltima_metro.png' : 'navUltima.gif')).' para ir até a última página.').'<img src="'.acharImagem(($cor_fundo=='006fc2' ? 'navUltima_metro.png' : 'navUltima.gif')).'" border=0>'.dicaF().'</a></td>';
		else $s .= '<td width="15%">&nbsp;</td></tr>';
		$s .= '<tr><td colspan="3" align="center" > [ ';
		for ($n = $pagina > 16 ? $pagina - 16 : 1; $n <= $total_paginas; $n++) {
			if ($n == $pagina) $s .= '<b>'.$n.'</b></a>';
			else 	$s .= dica($n.'ª Página','Clique para visualizar a '.$n.'ª página.').'<a '.($cor_fundo=='006fc2' ? 'class="aba" ' : '').'href="javascript:void(0);" onclick="url_passar(0, \''.$saida.'&pagina='.$n.$extra.'\');">'.$n.'</a>'.dicaF();
			if ($n >= 30 + $pagina - 15) {
				$parar = true;
				break;
				}
			elseif ($n < $total_paginas) $s .= ' | ';
			}
		if (!isset($parar)) {
			if ($n == $pagina) $s .= '<'.$n.'</a>';
			else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \''.$saida.'&pagina='.$total_paginas.$extra.'\');">'.$n.'</a>';
			}
		$s .= ' ] </td></tr>';
		}
	else {
		$s .= '<td align="center">';
		if ($pag_prox > $total_paginas) $s .= $sqlrecs.' '.$m.' ';
		$s .= '</td></tr>';
		}
	$s .= '</table>';
	echo $s;
	}

function mostrarBarraNav2($totalregistros, $tamanhoPagina, $total_paginas, $pagina, $tipo='Registro', $tipos='Registros', $classe='', $frm='env', $cor_fundo='') {
	global $Aplic, $m, $a;

	if ($cor_fundo=='006fc2') $estilo='style="color: #ffffff; background-color: #'.$cor_fundo.'"';
	else $estilo='';

	$saida='';
	$qnt=0;
	foreach($_REQUEST as $chave => $valor){
		if ($chave!='pagina') $saida.=($qnt++ ? '&' : '').$chave.'='.$valor;
		}

	$parar = false;
	$pag_ant = $pag_prox = 0;
	$s = '<table width="100%" cellspacing=0 cellpadding=0 border=0 '.$classe.' '.$estilo.'><tr>';
	if ($totalregistros > $tamanhoPagina) {
		$pag_ant = $pagina - 1;
		$pag_prox = $pagina + 1;
		if ($pag_ant > 0)	$s .= '<td align="left" width="15%"><a href="javascript:void(0);" onclick="javascript:'.$frm.'.pagina.value=1; '.$frm.'.submit();">'.dica('Primeira Página', 'Clique neste ícone '.imagem('icones/navPrimeira'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).' para ir até a primeira página.').'<img src="'.acharImagem('navPrimeira'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).'" border=0>'.dicaF().'</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="javascript:'.$frm.'.pagina.value='.$pag_ant.'; '.$frm.'.submit();">'.dica('Página Anterior', 'Clique neste ícone '.imagem('icones/navAnterior'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).' para ir até a página anterior.').'<img src="'.acharImagem('navAnterior'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).'" border=0 >'.dicaF().'</a></td>';
		else $s .= '<td width="15%">&nbsp;</td>';
		$s .= '<td align="center" width="70%">'.$totalregistros.' '.($totalregistros > 1 ? $tipos : $tipo).' ('.$total_paginas.' página'.($total_paginas > 1 ? 's': '').')</td>';
		if ($pag_prox <= $total_paginas) $s .= '<td align="right" width="15%"><a href="javascript:void(0);" onclick="javascript:'.$frm.'.pagina.value='.$pag_prox.'; '.$frm.'.submit();">'.dica('Próxima Página', 'Clique neste ícone '.imagem('icones/navProximo'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).' para ir até a próxima página.').'<img src="'.acharImagem('navProximo'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).'" border=0>'.dicaF().'</a>&nbsp;&nbsp;<a href="javascript:void(0);" onclick="javascript:'.$frm.'.pagina.value='.$total_paginas.'; '.$frm.'.submit();">'.dica('Última Página', 'Clique neste ícone '.imagem('icones/navUltima'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).' para ir até a última página.').'<img src="'.acharImagem('navUltima'.($cor_fundo=='006fc2' ? '_metro.png' : '.gif')).'" border=0>'.dicaF().'</a></td>';
		else $s .= '<td width="15%">&nbsp;</td></tr>';
		$s .= '<tr><td colspan="3" align="center"> [ ';
		for ($n = $pagina > 16 ? $pagina - 16 : 1; $n <= $total_paginas; $n++) {
			if ($n == $pagina) $s .= '<b>'.$n.'</b></a>';
			else 	$s .= dica($n.'ª Página','Clique para visualizar a '.$n.'ª página.').'<a '.($cor_fundo=='006fc2' ? 'class="aba" ' : '').'href="javascript:void(0);" onclick="javascript:'.$frm.'.pagina.value='.$n.'; '.$frm.'.submit();">'.$n.'</a>'.dicaF();
			if ($n >= 30 + $pagina - 15) {
				$parar = true;
				break;
				}
			elseif ($n < $total_paginas) $s .= ' | ';
			}
		if (!isset($parar)) {
			if ($n == $pagina) $s .= '<'.$n.'</a>';
			else $s .= '<a href="javascript:void(0);" onclick="javascript:'.$frm.'.pagina.value='.$total_paginas.'; '.$frm.'.submit();">'.$n.'</a>';
			}
		$s .= ' ] </td></tr>';
		}
	else {
		$s .= '<td align="center">';
		if ($pag_prox > $total_paginas) $s .= $sqlrecs.' '.$m.' ';
		$s .= '</td></tr>';
		}
	$s .= '</table>';
	echo $s;
	}
/*
function permiteAcessarCia($cia_id, $acesso=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 3:
			// privado
			$sql->limpar();
			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno=($Aplic->usuario_cia==$cia_id || $responsavel);
			break;
		default:
			$valorRetorno=true;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarCia($cia_id, $acesso=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	//precisa ser ao menos administrador de usuario da organizacao em tela
	if (!($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin)) return false;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->limpar();
			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno=($Aplic->usuario_cia==$cia_id  || $responsavel);
			break;
		case 3:
			// privado
			$sql->limpar();
			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno=$responsavel;
			break;
		case 4:
			// protegido II
			$sql->limpar();
			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno=$responsavel;
			break;
		default:
			$valorRetorno=true;
			break;
		}
	return $valorRetorno;
	}
*/

function permiteAcessarCia($acesso=0, $cia_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$cia_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('cia_usuario');
			$sql->adCampo('COUNT(DISTINCT cia_usuario_usuario)');
			$sql->adOnde('cia_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND cia_usuario_cia='.(int)$cia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('cia_usuario');
			$sql->adCampo('COUNT(DISTINCT cia_usuario_usuario)');
			$sql->adOnde('cia_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND cia_usuario_cia='.(int)$cia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarCia($acesso=0, $cia_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$cia_id) return true;
	elseif (!($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin)) return false;
	
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('cia_usuario');
			$sql->adCampo('COUNT(DISTINCT cia_usuario_usuario)');
			$sql->adOnde('cia_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND cia_usuario_cia='.(int)$cia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('cia_usuario');
			$sql->adCampo('COUNT(DISTINCT cia_usuario_usuario)');
			$sql->adOnde('cia_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND cia_usuario_cia='.(int)$cia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('cias');
			$sql->adCampo('cia_responsavel');
			$sql->adOnde('cia_id = '.$cia_id);
			$sql->adOnde('cia_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarDept($acesso=0, $dept_id=null) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participantes
			$sql->limpar();
			$sql->adTabela('depts');
			$sql->adCampo('dept_responsavel');
			$sql->adOnde('dept_id = '.$dept_id);
			$sql->adOnde('dept_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$dept_responsavel = $sql->Resultado();
			$valorRetorno=($Aplic->usuario_dept==$dept_id  || $dept_responsavel);
			break;
		case 3:
			// privado
			$sql->limpar();
			$sql->adTabela('depts');
			$sql->adCampo('dept_responsavel');
			$sql->adOnde('dept_id = '.$dept_id);
			$sql->adOnde('dept_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$dept_responsavel = $sql->Resultado();
			$valorRetorno=($Aplic->usuario_dept==$dept_id  || $dept_responsavel);
			break;
		default:
			$valorRetorno=true;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarDept($acesso=0, $dept_id=null) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->limpar();
			$sql->adTabela('depts');
			$sql->adCampo('dept_responsavel');
			$sql->adOnde('dept_id = '.$dept_id);
			$sql->adOnde('dept_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$dept_responsavel = $sql->Resultado();
			$valorRetorno=($Aplic->usuario_dept==$dept_id || $dept_responsavel);
			break;
		case 2:
			// participantes
			$sql->limpar();
			$sql->adTabela('depts');
			$sql->adCampo('dept_responsavel');
			$sql->adOnde('dept_id = '.$dept_id);
			$sql->adOnde('dept_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$dept_responsavel = $sql->Resultado();
			$valorRetorno=($Aplic->usuario_dept==$dept_id || $dept_responsavel);
			break;
		case 3:
			// privado
			$sql->limpar();
			$sql->adTabela('depts');
			$sql->adCampo('dept_responsavel');
			$sql->adOnde('dept_id = '.$dept_id);
			$sql->adOnde('dept_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$dept_responsavel = $sql->Resultado();
			$valorRetorno=$dept_responsavel;
			break;
		case 4:
			// protegido II
			$sql->limpar();
			$sql->adTabela('depts');
			$sql->adCampo('dept_responsavel');
			$sql->adOnde('dept_id = '.$dept_id);
			$sql->adOnde('dept_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$dept_responsavel = $sql->Resultado();
			$valorRetorno=$dept_responsavel;
			break;
		default:
			$valorRetorno=true;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessar($acesso=0, $projeto_id=0, $tarefa_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;

	elseif (!($projeto_id || $tarefa_id)) return true;//sem projeto e tarefa desconsidera
	elseif ($tarefa_id){
		switch ($acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$valorRetorno = true;
				break;
			case 4:
				// protegido II
				$valorRetorno = true;
				break;
			case 2:
				// participante
				$sql->adTabela('tarefa_designados');
				$sql->adCampo('COUNT(tarefa_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tarefa_id='.(int)$tarefa_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefa_designados', 'ut');
				$sql->adUnir('tarefas', 't', 't.tarefa_id = ut.tarefa_id');
				$sql->adCampo('COUNT(DISTINCT ut.usuario_id)');
				$sql->adOnde('ut.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND t.tarefa_projeto='.(int)$projeto_id);
				$quantidade2 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade3 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_contatos');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade4 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$tarefa_dono = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $quantidade3 > 0 || $quantidade4 > 0 || $tarefa_dono || $membro);
				break;
			case 3:
				// privado
				$sql->adTabela('tarefa_designados');
				$sql->adCampo('COUNT(usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tarefa_id='.(int)$tarefa_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();
				$sql->adTabela('tarefa_designados', 'ut');
				$sql->adUnir('tarefas', 't', 't.tarefa_id = ut.tarefa_id');
				$sql->adCampo('COUNT(DISTINCT ut.usuario_id)');
				$sql->adOnde('ut.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND t.tarefa_projeto='.(int)$projeto_id);
				$quantidade2 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade3 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_contatos');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade4 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$tarefa_dono = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $quantidade3 > 0 || $quantidade4 > 0 || $tarefa_dono || $membro);
				break;
			}
		return $valorRetorno;
		}
	else{ //projeto
		switch ($acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$valorRetorno = true;
				break;
			case 2:
				// participantes
				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefa_designados');
				$sql->esqUnir('tarefas','tarefas','tarefas.tarefa_id=tarefa_designados.tarefa_id');
				$sql->adCampo('COUNT(tarefa_designados.tarefa_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tarefa_projeto='.(int)$projeto_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefa_designados', 'ut');
				$sql->adUnir('tarefas', 't', 't.tarefa_id = ut.tarefa_id');
				$sql->adCampo('COUNT(DISTINCT ut.usuario_id)');
				$sql->adOnde('ut.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND t.tarefa_projeto='.(int)$projeto_id);
				$quantidade2 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade3 = $sql->Resultado();
				$sql->limpar();


				$sql->adTabela('projeto_contatos');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade4 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_projeto = '.$projeto_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$quantidade5 = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $quantidade3 > 0 || $quantidade4 > 0 || $quantidade5 > 0 || $membro);
				break;
			case 3:
				// privado
				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefa_designados');
				$sql->esqUnir('tarefas','tarefas','tarefas.tarefa_id=tarefa_designados.tarefa_id');
				$sql->adCampo('COUNT(tarefa_designados.tarefa_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tarefa_projeto='.(int)$projeto_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefa_designados', 'ut');
				$sql->adUnir('tarefas', 't', 't.tarefa_id = ut.tarefa_id');
				$sql->adCampo('COUNT(DISTINCT ut.usuario_id)');
				$sql->adOnde('ut.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND t.tarefa_projeto='.(int)$projeto_id);
				$quantidade2 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade3 = $sql->Resultado();
				$sql->limpar();


				$sql->adTabela('projeto_contatos');
				$sql->adUnir('usuarios', 'usuarios', 'usuario_contato = contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.(int)$projeto_id);
				$quantidade4 = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_projeto = '.$projeto_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$quantidade5 = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $quantidade3 > 0 || $quantidade4 > 0 || $quantidade5 > 0 || $membro);
				break;
			}
		return $valorRetorno;
		}
	}

function permiteAcessarLink($acesso=0, $link_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$link_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('link_usuarios');
			$sql->adCampo('COUNT(DISTINCT usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND link_id='.(int)$link_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.$link_id);
			$sql->adOnde('link_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('link_usuarios');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND link_id='.(int)$link_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.$link_id);
			$sql->adOnde('link_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarLink($acesso=0, $link_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$link_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('link_usuarios');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND link_id='.(int)$link_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.$link_id);
			$sql->adOnde('link_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('link_usuarios');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND link_id='.(int)$link_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.$link_id);
			$sql->adOnde('link_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.$link_id);
			$sql->adOnde('link_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $link_usuario;
			break;
		case 4:
			// protegido II
			$sql->adTabela('links');
			$sql->adCampo('link_dono');
			$sql->adOnde('link_id = '.$link_id);
			$sql->adOnde('link_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $link_usuario;
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPlanoGestao($acesso=0, $pg_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('plano_gestao_usuario');
			$sql->adCampo('COUNT(DISTINCT plano_gestao_usuario_usuario)');
			$sql->adOnde('plano_gestao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND plano_gestao_usuario_plano='.(int)$pg_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$pg_id);
			$sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('plano_gestao_usuario');
			$sql->adCampo('COUNT(plano_gestao_usuario_usuario)');
			$sql->adOnde('plano_gestao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND plano_gestao_usuario_plano='.(int)$pg_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$pg_id);
			$sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarPlanoGestao($acesso=0, $pg_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('plano_gestao_usuario');
			$sql->adCampo('COUNT(plano_gestao_usuario_usuario)');
			$sql->adOnde('plano_gestao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND plano_gestao_usuario_plano='.(int)$pg_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$pg_id);
			$sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('plano_gestao_usuario');
			$sql->adCampo('COUNT(plano_gestao_usuario_usuario)');
			$sql->adOnde('plano_gestao_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND plano_gestao_usuario_plano='.(int)$pg_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$pg_id);
			$sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $link_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$pg_id);
			$sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $link_usuario;
			break;
		case 4:
			// protegido II
			$sql->adTabela('plano_gestao');
			$sql->adCampo('pg_usuario');
			$sql->adOnde('pg_id = '.(int)$pg_id);
			$sql->adOnde('pg_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$link_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $link_usuario;
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPratica($acesso=0, $pratica_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pratica_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participantes
			$sql->adTabela('praticas');
			$sql->adCampo('pratica_responsavel');
			$sql->adOnde('pratica_id = '.$pratica_id);
			$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_responsavel = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('pratica_usuarios');
			$sql->adCampo('COUNT(DISTINCT usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_id='.(int)$pratica_id);
			$quantidade =$sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pratica_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('praticas');
			$sql->adCampo('pratica_responsavel');
			$sql->adOnde('pratica_id = '.$pratica_id);
			$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_responsavel = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('pratica_usuarios');
			$sql->adCampo('COUNT(DISTINCT usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_id='.(int)$pratica_id);
			$quantidade =$sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pratica_responsavel);
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarPlanoAcao($acesso=0, $plano_acao_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$plano_acao_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('plano_acao_usuarios');
			$sql->adCampo('COUNT(DISTINCT plano_acao_usuarios.usuario_id)');
			$sql->adOnde('plano_acao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_usuarios.plano_acao_id='.(int)$plano_acao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_item_designados');
			$sql->esqUnir('plano_acao_item', 'plano_acao_item','plano_acao_item.plano_acao_item_id=plano_acao_item_designados.plano_acao_item_id');
			$sql->adCampo('COUNT(DISTINCT plano_acao_item_designados.usuario_id)');
			$sql->adOnde('plano_acao_item_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_item_acao='.(int)$plano_acao_id);
			$quantidade2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.$plano_acao_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$plano_acao_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $plano_acao_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('plano_acao_usuarios');
			$sql->adCampo('COUNT(DISTINCT plano_acao_usuarios.usuario_id)');
			$sql->adOnde('plano_acao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_usuarios.plano_acao_id='.(int)$plano_acao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_item_designados');
			$sql->esqUnir('plano_acao_item', 'plano_acao_item','plano_acao_item.plano_acao_item_id=plano_acao_item_designados.plano_acao_item_id');
			$sql->adCampo('COUNT(DISTINCT plano_acao_item_designados.usuario_id)');
			$sql->adOnde('plano_acao_item_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_item_acao='.(int)$plano_acao_id);
			$quantidade2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.$plano_acao_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$plano_acao_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $plano_acao_usuario);
			break;
		}
	return $valorRetorno;
	}

function permiteEditarPlanoAcao($acesso=0, $plano_acao_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$plano_acao_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('plano_acao_usuarios');
			$sql->adCampo('COUNT(DISTINCT plano_acao_usuarios.usuario_id)');
			$sql->adOnde('plano_acao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_usuarios.plano_acao_id='.(int)$plano_acao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_item_designados');
			$sql->esqUnir('plano_acao_item', 'plano_acao_item','plano_acao_item.plano_acao_item_id=plano_acao_item_designados.plano_acao_item_id');
			$sql->adCampo('COUNT(DISTINCT plano_acao_item_designados.usuario_id)');
			$sql->adOnde('plano_acao_item_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_item_acao='.(int)$plano_acao_id);
			$quantidade2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.$plano_acao_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$plano_acao_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $plano_acao_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('plano_acao_usuarios');
			$sql->adCampo('COUNT(DISTINCT plano_acao_usuarios.usuario_id)');
			$sql->adOnde('plano_acao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_usuarios.plano_acao_id='.(int)$plano_acao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_item_designados');
			$sql->esqUnir('plano_acao_item', 'plano_acao_item','plano_acao_item.plano_acao_item_id=plano_acao_item_designados.plano_acao_item_id');
			$sql->adCampo('COUNT(DISTINCT plano_acao_item_designados.usuario_id)');
			$sql->adOnde('plano_acao_item_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND plano_acao_item_acao='.(int)$plano_acao_id);
			$quantidade2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.$plano_acao_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$plano_acao_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $plano_acao_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.$plano_acao_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$plano_acao_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($plano_acao_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_id = '.$plano_acao_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$plano_acao_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($plano_acao_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPlanoAcaoItem($acesso=0, $plano_acao_item_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$plano_acao_item_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('plano_acao_item_designados');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
			$sql->adOnde('plano_acao_item_id='.$plano_acao_item_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_usuarios');
			$sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_id=plano_acao_usuarios.plano_acao_id');
			$sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao.plano_acao_id=plano_acao_item_acao');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
			$sql->adOnde('plano_acao_item_id='.$plano_acao_item_id);
			$quantidade2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_item');
			$sql->adCampo('plano_acao_item_responsavel');
			$sql->adOnde('plano_acao_item_id = '.$plano_acao_item_id);
			$sql->adOnde('plano_acao_item_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao.plano_acao_id=plano_acao_item_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_item_id='.$plano_acao_item_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel2 = $sql->Resultado();
			$sql->limpar();

			$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $responsavel || $responsavel2);

			break;
		case 3:
			// privado
			$sql->adTabela('plano_acao_item_designados');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
			$sql->adOnde('plano_acao_item_id='.$plano_acao_item_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_usuarios');
			$sql->esqUnir('plano_acao','plano_acao','plano_acao.plano_acao_id=plano_acao_usuarios.plano_acao_id');
			$sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao.plano_acao_id=plano_acao_item_acao');
			$sql->adCampo('COUNT(usuario_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.')');
			$sql->adOnde('plano_acao_item_id='.$plano_acao_item_id);
			$quantidade2 = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao_item');
			$sql->adCampo('plano_acao_item_responsavel');
			$sql->adOnde('plano_acao_item_id = '.$plano_acao_item_id);
			$sql->adOnde('plano_acao_item_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('plano_acao');
			$sql->esqUnir('plano_acao_item','plano_acao_item','plano_acao.plano_acao_id=plano_acao_item_acao');
			$sql->adCampo('plano_acao_responsavel');
			$sql->adOnde('plano_acao_item_id='.$plano_acao_item_id);
			$sql->adOnde('plano_acao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel2 = $sql->Resultado();
			$sql->limpar();

			$valorRetorno = ($quantidade > 0 || $quantidade2 > 0 || $responsavel || $responsavel2);
			break;
		}
	return $valorRetorno;
	}





function permiteAcessarGUT($acesso=0, $gut_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$gut_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('gut_usuarios');
			$sql->adCampo('COUNT(DISTINCT gut_usuarios.usuario_id)');
			$sql->adOnde('gut_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND gut_usuarios.gut_id='.$gut_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.$gut_id);
			$sql->adOnde('gut_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$gut_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $gut_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('gut_usuarios');
			$sql->adCampo('COUNT(DISTINCT gut_usuarios.usuario_id)');
			$sql->adOnde('gut_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND gut_usuarios.gut_id='.$gut_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.$gut_id);
			$sql->adOnde('gut_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$gut_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $gut_responsavel);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarGUT($acesso=0, $gut_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$gut_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('gut_usuarios');
			$sql->adCampo('COUNT(DISTINCT gut_usuarios.usuario_id)');
			$sql->adOnde('gut_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND gut_usuarios.gut_id='.$gut_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.$gut_id);
			$sql->adOnde('gut_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$gut_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $gut_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('gut_usuarios');
			$sql->adCampo('COUNT(DISTINCT gut_usuarios.usuario_id)');
			$sql->adOnde('gut_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND gut_usuarios.gut_id='.$gut_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.$gut_id);
			$sql->adOnde('gut_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$gut_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $gut_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.$gut_id);
			$sql->adOnde('gut_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$gut_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($gut_responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('gut');
			$sql->adCampo('gut_responsavel');
			$sql->adOnde('gut_id = '.$gut_id);
			$sql->adOnde('gut_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$gut_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $gut_responsavel;
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarInstrumento($acesso=0, $instrumento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	//EUZ adiciondo @ para ocultar Warning
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$instrumento_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('instrumento_designados');
			$sql->adCampo('COUNT(DISTINCT instrumento_designados.usuario_id)');
			$sql->adOnde('instrumento_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND instrumento_designados.instrumento_id='.$instrumento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.$instrumento_id);
			$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$instrumento_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $instrumento_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('instrumento_designados');
			$sql->adCampo('COUNT(DISTINCT instrumento_designados.usuario_id)');
			$sql->adOnde('instrumento_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND instrumento_designados.instrumento_id='.$instrumento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.$instrumento_id);
			$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$instrumento_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $instrumento_responsavel);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarInstrumento($acesso=0, $instrumento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$instrumento_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('instrumento_designados');
			$sql->adCampo('COUNT(DISTINCT instrumento_designados.usuario_id)');
			$sql->adOnde('instrumento_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND instrumento_designados.instrumento_id='.$instrumento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.$instrumento_id);
			$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$instrumento_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $instrumento_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('instrumento_designados');
			$sql->adCampo('COUNT(DISTINCT instrumento_designados.usuario_id)');
			$sql->adOnde('instrumento_designados.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND instrumento_designados.instrumento_id='.$instrumento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.$instrumento_id);
			$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$instrumento_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $instrumento_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.$instrumento_id);
			$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$instrumento_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($instrumento_responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_responsavel');
			$sql->adOnde('instrumento_id = '.$instrumento_id);
			$sql->adOnde('instrumento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$instrumento_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($instrumento_responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarCausa_efeito($acesso=0, $causa_efeito_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$causa_efeito_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('causa_efeito_usuarios');
			$sql->adCampo('COUNT(DISTINCT causa_efeito_usuarios.usuario_id)');
			$sql->adOnde('causa_efeito_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND causa_efeito_usuarios.causa_efeito_id='.$causa_efeito_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
			$sql->adOnde('causa_efeito_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$causa_efeito_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $causa_efeito_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('causa_efeito_usuarios');
			$sql->adCampo('COUNT(DISTINCT causa_efeito_usuarios.usuario_id)');
			$sql->adOnde('causa_efeito_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND causa_efeito_usuarios.causa_efeito_id='.$causa_efeito_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
			$sql->adOnde('causa_efeito_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$causa_efeito_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $causa_efeito_responsavel);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarCausa_efeito($acesso=0, $causa_efeito_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$causa_efeito_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('causa_efeito_usuarios');
			$sql->adCampo('COUNT(DISTINCT causa_efeito_usuarios.usuario_id)');
			$sql->adOnde('causa_efeito_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND causa_efeito_usuarios.causa_efeito_id='.$causa_efeito_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
			$sql->adOnde('causa_efeito_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$causa_efeito_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $causa_efeito_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('causa_efeito_usuarios');
			$sql->adCampo('COUNT(DISTINCT causa_efeito_usuarios.usuario_id)');
			$sql->adOnde('causa_efeito_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND causa_efeito_usuarios.causa_efeito_id='.$causa_efeito_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
			$sql->adOnde('causa_efeito_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$causa_efeito_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $causa_efeito_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
			$sql->adOnde('causa_efeito_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$causa_efeito_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($causa_efeito_responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_responsavel');
			$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
			$sql->adOnde('causa_efeito_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$causa_efeito_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($causa_efeito_responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarBrainstorm($acesso=0, $brainstorm_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$brainstorm_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('brainstorm_usuarios');
			$sql->adCampo('COUNT(DISTINCT brainstorm_usuarios.usuario_id)');
			$sql->adOnde('brainstorm_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND brainstorm_usuarios.brainstorm_id='.$brainstorm_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.$brainstorm_id);
			$sql->adOnde('brainstorm_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$brainstorm_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $brainstorm_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('brainstorm_usuarios');
			$sql->adCampo('COUNT(DISTINCT brainstorm_usuarios.usuario_id)');
			$sql->adOnde('brainstorm_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.')'.' AND brainstorm_usuarios.brainstorm_id='.$brainstorm_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.$brainstorm_id);
			$sql->adOnde('brainstorm_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$brainstorm_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $brainstorm_responsavel);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarBrainstorm($acesso=0, $brainstorm_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$brainstorm_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('brainstorm_usuarios');
			$sql->adCampo('COUNT(DISTINCT brainstorm_usuarios.usuario_id)');
			$sql->adOnde('brainstorm_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND brainstorm_usuarios.brainstorm_id='.$brainstorm_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.$brainstorm_id);
			$sql->adOnde('brainstorm_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$brainstorm_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $brainstorm_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('brainstorm_usuarios');
			$sql->adCampo('COUNT(DISTINCT brainstorm_usuarios.usuario_id)');
			$sql->adOnde('brainstorm_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND brainstorm_usuarios.brainstorm_id='.$brainstorm_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.$brainstorm_id);
			$sql->adOnde('brainstorm_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$brainstorm_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $brainstorm_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.$brainstorm_id);
			$sql->adOnde('brainstorm_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$brainstorm_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $brainstorm_responsavel;
			break;
		case 4:
			// protegido II
			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_responsavel');
			$sql->adOnde('brainstorm_id = '.$brainstorm_id);
			$sql->adOnde('brainstorm_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$brainstorm_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = $brainstorm_responsavel;
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarIndicador($acesso=0, $pratica_indicador_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pratica_indicador_id) return true;//sem pratica e acao desconsidera
	$valorRetorno = true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('pratica_indicador_usuarios');
			$sql->adCampo('COUNT(DISTINCT pratica_indicador_usuarios.usuario_id)');
			$sql->adOnde('pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_indicador_usuarios.pratica_indicador_id='.$pratica_indicador_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_indicador_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pratica_indicador_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('pratica_indicador_usuarios');
			$sql->adCampo('COUNT(DISTINCT pratica_indicador_usuarios.usuario_id)');
			$sql->adOnde('pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_indicador_usuarios.pratica_indicador_id='.$pratica_indicador_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_indicador_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pratica_indicador_responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarLacuna($acesso=0, $indicador_lacuna_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$indicador_lacuna_id) return true;//sem pratica e acao desconsidera
	$valorRetorno = true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('indicador_lacuna_usuarios');
			$sql->adCampo('COUNT(DISTINCT indicador_lacuna_usuarios.usuario_id)');
			$sql->adOnde('indicador_lacuna_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND indicador_lacuna_usuarios.indicador_lacuna_id='.$indicador_lacuna_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('indicador_lacuna');
			$sql->adCampo('indicador_lacuna_responsavel');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$sql->adOnde('indicador_lacuna_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$indicador_lacuna_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $indicador_lacuna_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('indicador_lacuna_usuarios');
			$sql->adCampo('COUNT(DISTINCT indicador_lacuna_usuarios.usuario_id)');
			$sql->adOnde('indicador_lacuna_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND indicador_lacuna_usuarios.indicador_lacuna_id='.$indicador_lacuna_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('indicador_lacuna');
			$sql->adCampo('indicador_lacuna_responsavel');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$sql->adOnde('indicador_lacuna_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$indicador_lacuna_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $indicador_lacuna_responsavel);
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarObjetivo($acesso=0, $pg_objetivo_estrategico_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_objetivo_estrategico_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('objetivos_estrategicos_usuarios');
			$sql->adCampo('COUNT(DISTINCT objetivos_estrategicos_usuarios.usuario_id)');
			$sql->adOnde('objetivos_estrategicos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND objetivos_estrategicos_usuarios.pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_usuario');
			$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
			$sql->adOnde('pg_objetivo_estrategico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_objetivo_estrategico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_objetivo_estrategico_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('objetivos_estrategicos_usuarios');
			$sql->adCampo('COUNT(DISTINCT objetivos_estrategicos_usuarios.usuario_id)');
			$sql->adOnde('objetivos_estrategicos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND objetivos_estrategicos_usuarios.pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_usuario');
			$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
			$sql->adOnde('pg_objetivo_estrategico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_objetivo_estrategico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_objetivo_estrategico_usuario);
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarRisco($acesso=0, $risco_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$risco_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('risco_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_usuarios.usuario_id)');
			$sql->adOnde('risco_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_usuarios.risco_id='.$risco_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.$risco_id);
			$sql->adOnde('risco_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('risco_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_usuarios.usuario_id)');
			$sql->adOnde('risco_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_usuarios.risco_id='.$risco_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.$risco_id);
			$sql->adOnde('risco_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarRisco($acesso=0, $risco_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$risco_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('risco_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_usuarios.usuario_id)');
			$sql->adOnde('risco_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_usuarios.risco_id='.$risco_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.$risco_id);
			$sql->adOnde('risco_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('risco_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_usuarios.usuario_id)');
			$sql->adOnde('risco_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_usuarios.risco_id='.$risco_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.$risco_id);
			$sql->adOnde('risco_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.$risco_id);
			$sql->adOnde('risco_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($risco_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('risco');
			$sql->adCampo('risco_usuario');
			$sql->adOnde('risco_id = '.$risco_id);
			$sql->adOnde('risco_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($risco_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarRiscoResposta($acesso=0, $risco_resposta_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$risco_resposta_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('risco_resposta_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_resposta_usuarios.usuario_id)');
			$sql->adOnde('risco_resposta_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_resposta_usuarios.risco_resposta_id='.$risco_resposta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
			$sql->adOnde('risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_resposta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_resposta_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('risco_resposta_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_resposta_usuarios.usuario_id)');
			$sql->adOnde('risco_resposta_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_resposta_usuarios.risco_resposta_id='.$risco_resposta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
			$sql->adOnde('risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_resposta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_resposta_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarRiscoResposta($acesso=0, $risco_resposta_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$risco_resposta_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('risco_resposta_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_resposta_usuarios.usuario_id)');
			$sql->adOnde('risco_resposta_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_resposta_usuarios.risco_resposta_id='.$risco_resposta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
			$sql->adOnde('risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_resposta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_resposta_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('risco_resposta_usuarios');
			$sql->adCampo('COUNT(DISTINCT risco_resposta_usuarios.usuario_id)');
			$sql->adOnde('risco_resposta_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND risco_resposta_usuarios.risco_resposta_id='.$risco_resposta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
			$sql->adOnde('risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_resposta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $risco_resposta_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
			$sql->adOnde('risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_resposta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($risco_resposta_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('risco_resposta');
			$sql->adCampo('risco_resposta_usuario');
			$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
			$sql->adOnde('risco_resposta_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$risco_resposta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($risco_resposta_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarTgn($acesso=0, $tgn_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$tgn_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('tgn_usuario');
			$sql->adCampo('COUNT(DISTINCT tgn_usuario_usuario)');
			$sql->adOnde('tgn_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tgn_usuario_tgn='.(int)$tgn_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.$tgn_id);
			$sql->adOnde('tgn_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tgn_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tgn_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('tgn_usuario');
			$sql->adCampo('COUNT(DISTINCT tgn_usuario_usuario)');
			$sql->adOnde('tgn_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tgn_usuario_tgn='.(int)$tgn_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.$tgn_id);
			$sql->adOnde('tgn_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tgn_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tgn_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarTgn($acesso=0, $tgn_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$tgn_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('tgn_usuario');
			$sql->adCampo('COUNT(DISTINCT tgn_usuario_usuario)');
			$sql->adOnde('tgn_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tgn_usuario_tgn='.(int)$tgn_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.$tgn_id);
			$sql->adOnde('tgn_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tgn_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tgn_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('tgn_usuario');
			$sql->adCampo('COUNT(DISTINCT tgn_usuario_usuario)');
			$sql->adOnde('tgn_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tgn_usuario_tgn='.(int)$tgn_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.$tgn_id);
			$sql->adOnde('tgn_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tgn_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tgn_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.$tgn_id);
			$sql->adOnde('tgn_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tgn_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($tgn_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('tgn');
			$sql->adCampo('tgn_usuario');
			$sql->adOnde('tgn_id = '.$tgn_id);
			$sql->adOnde('tgn_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tgn_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($tgn_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarCanvas($acesso=0, $canvas_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$canvas_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('canvas_usuario');
			$sql->adCampo('COUNT(DISTINCT canvas_usuario_usuario)');
			$sql->adOnde('canvas_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND canvas_usuario_canvas='.(int)$canvas_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.$canvas_id);
			$sql->adOnde('canvas_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$canvas_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $canvas_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('canvas_usuario');
			$sql->adCampo('COUNT(DISTINCT canvas_usuario_usuario)');
			$sql->adOnde('canvas_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND canvas_usuario_canvas='.(int)$canvas_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.$canvas_id);
			$sql->adOnde('canvas_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$canvas_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $canvas_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarCanvas($acesso=0, $canvas_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$canvas_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('canvas_usuario');
			$sql->adCampo('COUNT(DISTINCT canvas_usuario_usuario)');
			$sql->adOnde('canvas_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND canvas_usuario_canvas='.(int)$canvas_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.$canvas_id);
			$sql->adOnde('canvas_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$canvas_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $canvas_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('canvas_usuario');
			$sql->adCampo('COUNT(DISTINCT canvas_usuario_usuario)');
			$sql->adOnde('canvas_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND canvas_usuario_canvas='.(int)$canvas_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.$canvas_id);
			$sql->adOnde('canvas_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$canvas_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $canvas_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.$canvas_id);
			$sql->adOnde('canvas_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$canvas_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($canvas_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('canvas');
			$sql->adCampo('canvas_usuario');
			$sql->adOnde('canvas_id = '.$canvas_id);
			$sql->adOnde('canvas_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$canvas_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($canvas_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarForum($acesso=0, $forum_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$forum_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('forum_usuario');
			$sql->adCampo('COUNT(DISTINCT forum_usuario_usuario)');
			$sql->adOnde('forum_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND forum_usuario_forum='.(int)$forum_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('foruns');
			$sql->adCampo('forum_moderador');
			$sql->adOnde('forum_id = '.$forum_id);
			$sql->adOnde('forum_moderador IN ('.$Aplic->usuario_lista_grupo.')');
			$forum_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $forum_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('forum_usuario');
			$sql->adCampo('COUNT(DISTINCT forum_usuario_usuario)');
			$sql->adOnde('forum_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND forum_usuario_forum='.(int)$forum_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('foruns');
			$sql->adCampo('forum_moderador');
			$sql->adOnde('forum_id = '.$forum_id);
			$sql->adOnde('forum_moderador IN ('.$Aplic->usuario_lista_grupo.')');
			$forum_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $forum_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarForum($acesso=0, $forum_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$forum_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('forum_usuario');
			$sql->adCampo('COUNT(DISTINCT forum_usuario_usuario)');
			$sql->adOnde('forum_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND forum_usuario_forum='.(int)$forum_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('foruns');
			$sql->adCampo('forum_moderador');
			$sql->adOnde('forum_id = '.$forum_id);
			$sql->adOnde('forum_moderador IN ('.$Aplic->usuario_lista_grupo.')');
			$forum_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $forum_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('forum_usuario');
			$sql->adCampo('COUNT(DISTINCT forum_usuario_usuario)');
			$sql->adOnde('forum_usuario_usuariov AND forum_usuario_forum='.(int)$forum_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('foruns');
			$sql->adCampo('forum_moderador');
			$sql->adOnde('forum_id = '.$forum_id);
			$sql->adOnde('forum_moderador IN ('.$Aplic->usuario_lista_grupo.')');
			$forum_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $forum_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('foruns');
			$sql->adCampo('forum_moderador');
			$sql->adOnde('forum_id = '.$forum_id);
			$sql->adOnde('forum_moderador IN ('.$Aplic->usuario_lista_grupo.')');
			$forum_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($forum_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('foruns');
			$sql->adCampo('forum_moderador');
			$sql->adOnde('forum_id = '.$forum_id);
			$sql->adOnde('forum_moderador IN ('.$Aplic->usuario_lista_grupo.')');
			$forum_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($forum_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarArquivo($acesso=0, $arquivo_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$arquivo_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('arquivo_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_usuario_usuario)');
			$sql->adOnde('arquivo_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_usuario_arquivo='.(int)$arquivo_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.$arquivo_id);
			$sql->adOnde('arquivo_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('arquivo_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_usuario_usuario)');
			$sql->adOnde('arquivo_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_usuario_arquivo='.(int)$arquivo_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.$arquivo_id);
			$sql->adOnde('arquivo_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarArquivo($acesso=0, $arquivo_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$arquivo_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('arquivo_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_usuario_usuario)');
			$sql->adOnde('arquivo_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_usuario_arquivo='.(int)$arquivo_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.$arquivo_id);
			$sql->adOnde('arquivo_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('arquivo_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_usuario_usuario)');
			$sql->adOnde('arquivo_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_usuario_arquivo='.(int)$arquivo_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.$arquivo_id);
			$sql->adOnde('arquivo_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.$arquivo_id);
			$sql->adOnde('arquivo_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($arquivo_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_dono');
			$sql->adOnde('arquivo_id = '.$arquivo_id);
			$sql->adOnde('arquivo_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($arquivo_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarPasta($acesso=0, $arquivo_pasta_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$arquivo_pasta_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('arquivo_pasta_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_pasta_usuario_usuario)');
			$sql->adOnde('arquivo_pasta_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_pasta_usuario_pasta='.(int)$arquivo_pasta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivo_pasta');
			$sql->adCampo('arquivo_pasta_dono');
			$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
			$sql->adOnde('arquivo_pasta_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_pasta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_pasta_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('arquivo_pasta_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_pasta_usuario_usuario)');
			$sql->adOnde('arquivo_pasta_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_pasta_usuario_pasta='.(int)$arquivo_pasta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivo_pasta');
			$sql->adCampo('arquivo_pasta_dono');
			$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
			$sql->adOnde('arquivo_pasta_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_pasta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_pasta_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarPasta($acesso=0, $arquivo_pasta_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$arquivo_pasta_id) return true;
	

	
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('arquivo_pasta_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_pasta_usuario_usuario)');
			$sql->adOnde('arquivo_pasta_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_pasta_usuario_pasta='.(int)$arquivo_pasta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivo_pasta');
			$sql->adCampo('arquivo_pasta_dono');
			$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
			$sql->adOnde('arquivo_pasta_dono IN ('.$Aplic->usuario_lista_grupo.')');
			
			$arquivo_pasta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_pasta_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('arquivo_pasta_usuario');
			$sql->adCampo('COUNT(DISTINCT arquivo_pasta_usuario_usuario)');
			$sql->adOnde('arquivo_pasta_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND arquivo_pasta_usuario_pasta='.(int)$arquivo_pasta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('arquivo_pasta');
			$sql->adCampo('arquivo_pasta_dono');
			$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
			$sql->adOnde('arquivo_pasta_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_pasta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $arquivo_pasta_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('arquivo_pasta');
			$sql->adCampo('arquivo_pasta_dono');
			$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
			$sql->adOnde('arquivo_pasta_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_pasta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($arquivo_pasta_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('arquivo_pasta');
			$sql->adCampo('arquivo_pasta_dono');
			$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
			$sql->adOnde('arquivo_pasta_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$arquivo_pasta_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($arquivo_pasta_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarMonitoramento($acesso=0, $monitoramento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$monitoramento_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('monitoramento_usuarios');
			$sql->adCampo('COUNT(DISTINCT monitoramento_usuarios.usuario_id)');
			$sql->adOnde('monitoramento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND monitoramento_usuarios.monitoramento_id='.$monitoramento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.$monitoramento_id);
			$sql->adOnde('monitoramento_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$monitoramento_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $monitoramento_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('monitoramento_usuarios');
			$sql->adCampo('COUNT(DISTINCT monitoramento_usuarios.usuario_id)');
			$sql->adOnde('monitoramento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND monitoramento_usuarios.monitoramento_id='.$monitoramento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.$monitoramento_id);
			$sql->adOnde('monitoramento_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$monitoramento_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $monitoramento_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.$monitoramento_id);
			$sql->adOnde('monitoramento_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$monitoramento_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($monitoramento_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.$monitoramento_id);
			$sql->adOnde('monitoramento_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$monitoramento_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($monitoramento_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}



function permiteAcessarMonitoramento($acesso=0, $monitoramento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$monitoramento_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('monitoramento_usuarios');
			$sql->adCampo('COUNT(DISTINCT monitoramento_usuarios.usuario_id)');
			$sql->adOnde('monitoramento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND monitoramento_usuarios.monitoramento_id='.$monitoramento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.$monitoramento_id);
			$sql->adOnde('monitoramento_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$monitoramento_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $monitoramento_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('monitoramento_usuarios');
			$sql->adCampo('COUNT(DISTINCT monitoramento_usuarios.usuario_id)');
			$sql->adOnde('monitoramento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND monitoramento_usuarios.monitoramento_id='.$monitoramento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_usuario');
			$sql->adOnde('monitoramento_id = '.$monitoramento_id);
			$sql->adOnde('monitoramento_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$monitoramento_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $monitoramento_usuario);
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarChecklist($acesso=0, $checklist_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$checklist_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('checklist_usuarios');
			$sql->adCampo('COUNT(DISTINCT checklist_usuarios.usuario_id)');
			$sql->adOnde('checklist_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND checklist_usuarios.checklist_id='.$checklist_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.$checklist_id);
			$sql->adOnde('checklist_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$checklist_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $checklist_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('checklist_usuarios');
			$sql->adCampo('COUNT(DISTINCT checklist_usuarios.usuario_id)');
			$sql->adOnde('checklist_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND checklist_usuarios.checklist_id='.$checklist_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.$checklist_id);
			$sql->adOnde('checklist_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$checklist_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $checklist_usuario);
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarEstrategia($acesso=0, $pg_estrategia_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_estrategia_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('COUNT(DISTINCT estrategias_usuarios.usuario_id)');
			$sql->adOnde('estrategias_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND estrategias_usuarios.pg_estrategia_id='.$pg_estrategia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('estrategias');
			$sql->adCampo('estrategias_usuario');
			$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
			$sql->adOnde('estrategias_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$estrategias_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $estrategias_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('COUNT(DISTINCT estrategias_usuarios.usuario_id)');
			$sql->adOnde('estrategias_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND estrategias_usuarios.pg_estrategia_id='.$pg_estrategia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('estrategias');
			$sql->adCampo('estrategias_usuario');
			$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
			$sql->adOnde('estrategias_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$estrategias_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $estrategias_usuario);
			break;
		}
	return $valorRetorno;
	}




function permiteAcessarCalendario($acesso=0, $calendario_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$calendario_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('calendario_usuario');
			$sql->adCampo('COUNT(DISTINCT calendario_usuario_usuario)');
			$sql->adOnde('calendario_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND calendario_usuario_calendario='.$calendario_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.$calendario_id);
			$sql->adOnde('calendario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('calendario_usuario');
			$sql->adCampo('COUNT(DISTINCT calendario_usuario_usuario)');
			$sql->adOnde('calendario_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND calendario_usuario_calendario='.$calendario_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.$calendario_id);
			$sql->adOnde('calendario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarCalendario($acesso=0, $calendario_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$calendario_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('calendario_usuario');
			$sql->adCampo('COUNT(DISTINCT calendario_usuario_usuario)');
			$sql->adOnde('calendario_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND calendario_usuario_calendario='.$calendario_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.$calendario_id);
			$sql->adOnde('calendario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('calendario_usuario');
			$sql->adCampo('COUNT(DISTINCT calendario_usuario_usuario)');
			$sql->adOnde('calendario_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND calendario_usuario_calendario='.$calendario_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.$calendario_id);
			$sql->adOnde('calendario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.$calendario_id);
			$sql->adOnde('calendario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('calendario');
			$sql->adCampo('calendario_usuario');
			$sql->adOnde('calendario_id = '.$calendario_id);
			$sql->adOnde('calendario_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteEditarIndicador($acesso=0, $pratica_indicador_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pratica_indicador_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('pratica_indicador_usuarios');
			$sql->adCampo('COUNT(DISTINCT pratica_indicador_usuarios.usuario_id)');
			$sql->adOnde('pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_indicador_usuarios.pratica_indicador_id='.$pratica_indicador_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_indicador_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pratica_indicador_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('pratica_indicador_usuarios');
			$sql->adCampo('COUNT(DISTINCT pratica_indicador_usuarios.usuario_id)');
			$sql->adOnde('pratica_indicador_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_indicador_usuarios.pratica_indicador_id='.$pratica_indicador_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_indicador_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pratica_indicador_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_indicador_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pratica_indicador_responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('pratica_indicador');
			$sql->adCampo('pratica_indicador_responsavel');
			$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
			$sql->adOnde('pratica_indicador_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$pratica_indicador_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pratica_indicador_responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}




function permiteEditarLacuna($acesso=0, $indicador_lacuna_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$indicador_lacuna_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('indicador_lacuna_usuarios');
			$sql->adCampo('COUNT(DISTINCT indicador_lacuna_usuarios.usuario_id)');
			$sql->adOnde('indicador_lacuna_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND indicador_lacuna_usuarios.indicador_lacuna_id='.$indicador_lacuna_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('indicador_lacuna');
			$sql->adCampo('indicador_lacuna_responsavel');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$sql->adOnde('indicador_lacuna_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$indicador_lacuna_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $indicador_lacuna_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('indicador_lacuna_usuarios');
			$sql->adCampo('COUNT(DISTINCT indicador_lacuna_usuarios.usuario_id)');
			$sql->adOnde('indicador_lacuna_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND indicador_lacuna_usuarios.indicador_lacuna_id='.$indicador_lacuna_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('indicador_lacuna');
			$sql->adCampo('indicador_lacuna_responsavel');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$sql->adOnde('indicador_lacuna_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$indicador_lacuna_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $indicador_lacuna_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('indicador_lacuna');
			$sql->adCampo('indicador_lacuna_responsavel');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$sql->adOnde('indicador_lacuna_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$indicador_lacuna_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($indicador_lacuna_responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('indicador_lacuna');
			$sql->adCampo('indicador_lacuna_responsavel');
			$sql->adOnde('indicador_lacuna_id = '.$indicador_lacuna_id);
			$sql->adOnde('indicador_lacuna_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$indicador_lacuna_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($indicador_lacuna_responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarEstrategia($acesso=0, $pg_estrategia_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_estrategia_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('COUNT(DISTINCT estrategias_usuarios.usuario_id)');
			$sql->adOnde('estrategias_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND estrategias_usuarios.pg_estrategia_id='.$pg_estrategia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_usuario');
			$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
			$sql->adOnde('pg_estrategia_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_estrategia_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_estrategia_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('COUNT(DISTINCT estrategias_usuarios.usuario_id)');
			$sql->adOnde('estrategias_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND estrategias_usuarios.pg_estrategia_id='.$pg_estrategia_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_usuario');
			$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
			$sql->adOnde('pg_estrategia_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_estrategia_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_estrategia_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_usuario');
			$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
			$sql->adOnde('pg_estrategia_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_estrategia_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pg_estrategia_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_usuario');
			$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
			$sql->adOnde('pg_estrategia_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_estrategia_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pg_estrategia_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarChecklist($acesso=0, $checklist_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$checklist_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('checklist_usuarios');
			$sql->adCampo('COUNT(DISTINCT checklist_usuarios.usuario_id)');
			$sql->adOnde('checklist_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND checklist_usuarios.checklist_id='.$checklist_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.$checklist_id);
			$sql->adOnde('checklist_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$checklist_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $checklist_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('checklist_usuarios');
			$sql->adCampo('COUNT(DISTINCT checklist_usuarios.usuario_id)');
			$sql->adOnde('checklist_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND checklist_usuarios.checklist_id='.$checklist_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.$checklist_id);
			$sql->adOnde('checklist_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$checklist_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $checklist_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.$checklist_id);
			$sql->adOnde('checklist_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$checklist_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($checklist_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('checklist');
			$sql->adCampo('checklist_responsavel');
			$sql->adOnde('checklist_id = '.$checklist_id);
			$sql->adOnde('checklist_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$checklist_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($checklist_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarObjetivo($acesso=0, $pg_objetivo_estrategico_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_objetivo_estrategico_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('objetivos_estrategicos_usuarios');
			$sql->adCampo('COUNT(DISTINCT objetivos_estrategicos_usuarios.usuario_id)');
			$sql->adOnde('objetivos_estrategicos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND objetivos_estrategicos_usuarios.pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_usuario');
			$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
			$sql->adOnde('pg_objetivo_estrategico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_objetivo_estrategico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_objetivo_estrategico_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('objetivos_estrategicos_usuarios');
			$sql->adCampo('COUNT(DISTINCT objetivos_estrategicos_usuarios.usuario_id)');
			$sql->adOnde('objetivos_estrategicos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND objetivos_estrategicos_usuarios.pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_usuario');
			$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
			$sql->adOnde('pg_objetivo_estrategico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_objetivo_estrategico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_objetivo_estrategico_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_usuario');
			$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
			$sql->adOnde('pg_objetivo_estrategico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_objetivo_estrategico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pg_objetivo_estrategico_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_usuario');
			$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
			$sql->adOnde('pg_objetivo_estrategico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_objetivo_estrategico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pg_objetivo_estrategico_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarMe($acesso=0, $me_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$me_id) return true;//sem me e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('me_usuario');
			$sql->adCampo('COUNT(DISTINCT me_usuario_usuario)');
			$sql->adOnde('me_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND me_usuario_me='.$me_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$me_id);
			$sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$me_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $me_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('me_usuario');
			$sql->adCampo('COUNT(DISTINCT me_usuario_usuario)');
			$sql->adOnde('me_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND me_usuario_me='.$me_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$me_id);
			$sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$me_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $me_usuario);
			break;
		}
	return $valorRetorno;
	}

function permiteEditarMe($acesso=0, $me_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$me_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('me_usuario');
			$sql->adCampo('COUNT(DISTINCT me_usuario_usuario)');
			$sql->adOnde('me_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND me_usuario_me='.$me_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$me_id);
			$sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$me_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $me_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('me_usuario');
			$sql->adCampo('COUNT(DISTINCT me_usuario_usuario)');
			$sql->adOnde('me_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND me_usuario_me='.$me_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$me_id);
			$sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$me_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $me_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$me_id);
			$sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$me_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($me_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('me');
			$sql->adCampo('me_usuario');
			$sql->adOnde('me_id = '.(int)$me_id);
			$sql->adOnde('me_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$me_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($me_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarTema($acesso=0, $tema_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$tema_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('tema_usuarios');
			$sql->adCampo('COUNT(DISTINCT tema_usuarios.usuario_id)');
			$sql->adOnde('tema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tema_usuarios.tema_id='.$tema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.$tema_id);
			$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tema_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tema_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('tema_usuarios');
			$sql->adCampo('COUNT(DISTINCT tema_usuarios.usuario_id)');
			$sql->adOnde('tema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tema_usuarios.tema_id='.$tema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.$tema_id);
			$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tema_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tema_usuario);
			break;
		}
	return $valorRetorno;
	}

function permiteEditarTema($acesso=0, $tema_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$tema_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('tema_usuarios');
			$sql->adCampo('COUNT(DISTINCT tema_usuarios.usuario_id)');
			$sql->adOnde('tema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tema_usuarios.tema_id='.$tema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.$tema_id);
			$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tema_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tema_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('tema_usuarios');
			$sql->adCampo('COUNT(DISTINCT tema_usuarios.usuario_id)');
			$sql->adOnde('tema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tema_usuarios.tema_id='.$tema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.$tema_id);
			$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tema_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $tema_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.$tema_id);
			$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tema_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($tema_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('tema');
			$sql->adCampo('tema_usuario');
			$sql->adOnde('tema_id = '.$tema_id);
			$sql->adOnde('tema_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$tema_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($tema_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditar($acesso=0, $projeto_id=0, $tarefa_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!($projeto_id || $tarefa_id)) return true;//sem projeto e tarefa desconsidera
	elseif ($tarefa_id){
		switch ($acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$sql->adTabela('tarefa_designados');
				$sql->adCampo('COUNT(tarefa_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tarefa_id='.$tarefa_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$tarefa_dono = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();



				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = projeto_integrantes.contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.$projeto_id);
				$quantidade2 = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade || $tarefa_dono || $membro || $quantidade2);
				break;
			case 2:
				// participante
				$sql->adTabela('tarefa_designados');
				$sql->adCampo('COUNT(tarefa_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND tarefa_id='.$tarefa_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$tarefa_dono = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = projeto_integrantes.contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.$projeto_id);
				$quantidade2 = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade || $tarefa_dono || $membro || $quantidade2);
				break;
			case 3:
				// privado
				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$tarefa_dono = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($tarefa_dono || $membro);
				break;
			case 4:
				// protegido II
				$sql->adTabela('tarefas');
				$sql->adCampo('count(tarefa_dono)');
				$sql->adOnde('tarefa_id = '.$tarefa_id);
				$sql->adOnde('tarefa_dono IN ('.$Aplic->usuario_lista_grupo.')');
				$tarefa_dono = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($tarefa_dono || $membro);
				break;
			default:
				$valorRetorno = false;
				break;
			}
		return $valorRetorno;
		}
	else {
		switch ($acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = projeto_integrantes.contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.$projeto_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();



				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade|| $membro);
				break;
			case 2:
				// participante

				$sql->adTabela('projeto_integrantes');
				$sql->adUnir('usuarios', 'usuarios', 'usuarios.usuario_contato = projeto_integrantes.contato_id');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_id='.$projeto_id);
				$quantidade = $sql->Resultado();
				$sql->limpar();

				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();

				$valorRetorno = ($quantidade|| $membro);
				break;
			case 3:
				// privado
				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = $membro;
				break;
			case 4:
				// protegido II
				$sql->adTabela('projetos');
				$sql->adCampo('count(projeto_id)');
				$sql->adOnde('projeto_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_supervisor IN ('.$Aplic->usuario_lista_grupo.') OR projeto_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_cliente IN ('.$Aplic->usuario_lista_grupo.')');
				$sql->adOnde('projeto_id = '.$projeto_id);
				$membro = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = $membro;
				break;
			default:
				$valorRetorno = false;
				break;
			}
		return $valorRetorno;
		}
	}

function permiteAcessarFator($acesso=0, $pg_fator_critico_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_fator_critico_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('fatores_criticos_usuarios');
			$sql->adCampo('COUNT(DISTINCT fatores_criticos_usuarios.usuario_id)');
			$sql->adOnde('fatores_criticos_usuarios.usuario_id='.$Aplic->usuario_id);
			$sql->adOnde('fatores_criticos_usuarios.pg_fator_critico_id='.$pg_fator_critico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_usuario');
			$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
			$sql->adOnde('pg_fator_critico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$fatores_criticos_usuario = $sql->Resultado();
			$sql->limpar();


			$valorRetorno = ($quantidade > 0 || $fatores_criticos_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('fatores_criticos_usuarios');
			$sql->adCampo('COUNT(DISTINCT fatores_criticos_usuarios.usuario_id)');
			$sql->adOnde('fatores_criticos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND fatores_criticos_usuarios.pg_fator_critico_id='.$pg_fator_critico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_usuario');
			$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
			$sql->adOnde('pg_fator_critico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$fatores_criticos_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $fatores_criticos_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarFator($acesso=0, $pg_fator_critico_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_fator_critico_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('fatores_criticos_usuarios');
			$sql->adCampo('COUNT(DISTINCT fatores_criticos_usuarios.usuario_id)');
			$sql->adOnde('fatores_criticos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND fatores_criticos_usuarios.pg_fator_critico_id='.$pg_fator_critico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_usuario');
			$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
			$sql->adOnde('pg_fator_critico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_fator_critico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_fator_critico_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('fatores_criticos_usuarios');
			$sql->adCampo('COUNT(DISTINCT fatores_criticos_usuarios.usuario_id)');
			$sql->adOnde('fatores_criticos_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND fatores_criticos_usuarios.pg_fator_critico_id='.$pg_fator_critico_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_usuario');
			$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
			$sql->adOnde('pg_fator_critico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_fator_critico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $pg_fator_critico_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_usuario');
			$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
			$sql->adOnde('pg_fator_critico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_fator_critico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pg_fator_critico_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_usuario');
			$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
			$sql->adOnde('pg_fator_critico_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$pg_fator_critico_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($pg_fator_critico_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarDemanda($acesso=0, $demanda_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$demanda_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('demanda_usuarios');
			$sql->adCampo('COUNT(DISTINCT demanda_usuarios.usuario_id)');
			$sql->adOnde('demanda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND demanda_usuarios.demanda_id='.$demanda_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.$demanda_id);
			$sql->adOnde('demanda_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$demanda_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $demanda_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('demanda_usuarios');
			$sql->adCampo('COUNT(DISTINCT demanda_usuarios.usuario_id)');
			$sql->adOnde('demanda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND demanda_usuarios.demanda_id='.$demanda_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.$demanda_id);
			$sql->adOnde('demanda_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$demanda_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $demanda_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarDemanda($acesso=0, $demanda_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$demanda_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('demanda_usuarios');
			$sql->adCampo('COUNT(DISTINCT demanda_usuarios.usuario_id)');
			$sql->adOnde('demanda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND demanda_usuarios.demanda_id='.$demanda_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.$demanda_id);
			$sql->adOnde('demanda_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$demanda_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $demanda_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('demanda_usuarios');
			$sql->adCampo('COUNT(DISTINCT demanda_usuarios.usuario_id)');
			$sql->adOnde('demanda_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND demanda_usuarios.demanda_id='.$demanda_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.$demanda_id);
			$sql->adOnde('demanda_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$demanda_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $demanda_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.$demanda_id);
			$sql->adOnde('demanda_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$demanda_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($demanda_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('demandas');
			$sql->adCampo('demanda_usuario');
			$sql->adOnde('demanda_id = '.$demanda_id);
			$sql->adOnde('demanda_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$demanda_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($demanda_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarViabilidade($acesso=0, $projeto_viabilidade_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_viabilidade_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_viabilidade_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_viabilidade_usuarios.usuario_id)');
			$sql->adOnde('projeto_viabilidade_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_viabilidade_usuarios.projeto_viabilidade_id='.$projeto_viabilidade_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
			$sql->adOnde('projeto_viabilidade_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_viabilidade_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_viabilidade_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_viabilidade_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_viabilidade_usuarios.usuario_id)');
			$sql->adOnde('projeto_viabilidade_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_viabilidade_usuarios.projeto_viabilidade_id='.$projeto_viabilidade_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
			$sql->adOnde('projeto_viabilidade_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_viabilidade_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_viabilidade_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarViabilidade($acesso=0, $projeto_viabilidade_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_viabilidade_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('projeto_viabilidade_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_viabilidade_usuarios.usuario_id)');
			$sql->adOnde('projeto_viabilidade_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_viabilidade_usuarios.projeto_viabilidade_id='.$projeto_viabilidade_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
			$sql->adOnde('projeto_viabilidade_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_viabilidade_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_viabilidade_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_viabilidade_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_viabilidade_usuarios.usuario_id)');
			$sql->adOnde('projeto_viabilidade_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_viabilidade_usuarios.projeto_viabilidade_id='.$projeto_viabilidade_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
			$sql->adOnde('projeto_viabilidade_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_viabilidade_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_viabilidade_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
			$sql->adOnde('projeto_viabilidade_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_viabilidade_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($projeto_viabilidade_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('projeto_viabilidade');
			$sql->adCampo('projeto_viabilidade_responsavel');
			$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
			$sql->adOnde('projeto_viabilidade_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_viabilidade_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($projeto_viabilidade_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarTermoAbertura($acesso=0, $projeto_abertura_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_abertura_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_abertura_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_abertura_usuarios.usuario_id)');
			$sql->adOnde('projeto_abertura_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_abertura_usuarios.projeto_abertura_id='.$projeto_abertura_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_id');
			$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
			$sql->adOnde('projeto_abertura_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_gerente_projeto IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_abertura_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_abertura_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_abertura_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_abertura_usuarios.usuario_id)');
			$sql->adOnde('projeto_abertura_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_abertura_usuarios.projeto_abertura_id='.$projeto_abertura_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_id');
			$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
			$sql->adOnde('projeto_abertura_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_gerente_projeto IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_abertura_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_abertura_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarTermoAbertura($acesso=0, $projeto_abertura_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_abertura_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('projeto_abertura_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_abertura_usuarios.usuario_id)');
			$sql->adOnde('projeto_abertura_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_abertura_usuarios.projeto_abertura_id='.$projeto_abertura_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_id');
			$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
			$sql->adOnde('projeto_abertura_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_gerente_projeto IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_abertura_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_abertura_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_abertura_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_abertura_usuarios.usuario_id)');
			$sql->adOnde('projeto_abertura_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_abertura_usuarios.projeto_abertura_id='.$projeto_abertura_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_id');
			$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
			$sql->adOnde('projeto_abertura_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_gerente_projeto IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_abertura_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $projeto_abertura_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_id');
			$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
			$sql->adOnde('projeto_abertura_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_gerente_projeto IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_abertura_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($projeto_abertura_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('projeto_abertura');
			$sql->adCampo('projeto_abertura_id');
			$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
			$sql->adOnde('projeto_abertura_responsavel IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_autoridade IN ('.$Aplic->usuario_lista_grupo.') OR projeto_abertura_gerente_projeto IN ('.$Aplic->usuario_lista_grupo.')');
			$projeto_abertura_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($projeto_abertura_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}



function permiteAcessarMeta($acesso=0, $pg_meta_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_meta_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('metas_usuarios');
			$sql->adCampo('COUNT(DISTINCT metas_usuarios.usuario_id)');
			$sql->adOnde('metas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND metas_usuarios.pg_meta_id='.$pg_meta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.$pg_meta_id);
			$sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('metas_usuarios');
			$sql->adCampo('COUNT(DISTINCT metas_usuarios.usuario_id)');
			$sql->adOnde('metas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND metas_usuarios.pg_meta_id='.$pg_meta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.$pg_meta_id);
			$sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarMeta($acesso=0, $pg_meta_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_meta_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('metas_usuarios');
			$sql->adCampo('COUNT(DISTINCT metas_usuarios.usuario_id)');
			$sql->adOnde('metas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND metas_usuarios.pg_meta_id='.$pg_meta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.$pg_meta_id);
			$sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('metas_usuarios');
			$sql->adCampo('COUNT(DISTINCT metas_usuarios.usuario_id)');
			$sql->adOnde('metas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND metas_usuarios.pg_meta_id='.$pg_meta_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.$pg_meta_id);
			$sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.$pg_meta_id);
			$sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_responsavel');
			$sql->adOnde('pg_meta_id = '.$pg_meta_id);
			$sql->adOnde('pg_meta_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarRecebimento($acesso=0, $projeto_recebimento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_recebimento_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_recebimento_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_recebimento_usuarios.usuario_id)');
			$sql->adOnde('projeto_recebimento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_recebimento_usuarios.projeto_recebimento_id='.$projeto_recebimento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_recebimento');
			$sql->adCampo('projeto_recebimento_responsavel');
			$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
			$sql->adOnde('projeto_recebimento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_recebimento_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_recebimento_usuarios.usuario_id)');
			$sql->adOnde('projeto_recebimento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_recebimento_usuarios.projeto_recebimento_id='.$projeto_recebimento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_recebimento');
			$sql->adCampo('projeto_recebimento_responsavel');
			$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
			$sql->adOnde('projeto_recebimento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarRecebimento($acesso=0, $projeto_recebimento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_recebimento_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('projeto_recebimento_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_recebimento_usuarios.usuario_id)');
			$sql->adOnde('projeto_recebimento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_recebimento_usuarios.projeto_recebimento_id='.$projeto_recebimento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_recebimento');
			$sql->adCampo('projeto_recebimento_responsavel');
			$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
			$sql->adOnde('projeto_recebimento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_recebimento_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_recebimento_usuarios.usuario_id)');
			$sql->adOnde('projeto_recebimento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_recebimento_usuarios.projeto_recebimento_id='.$projeto_recebimento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_recebimento');
			$sql->adCampo('projeto_recebimento_responsavel');
			$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
			$sql->adOnde('projeto_recebimento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_recebimento');
			$sql->adCampo('projeto_recebimento_responsavel');
			$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
			$sql->adOnde('projeto_recebimento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('projeto_recebimento');
			$sql->adCampo('projeto_recebimento_responsavel');
			$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
			$sql->adOnde('projeto_recebimento_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarMudanca($acesso=0, $projeto_mudanca_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_mudanca_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_mudanca_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_mudanca_usuarios.usuario_id)');
			$sql->adOnde('projeto_mudanca_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_mudanca_usuarios.projeto_mudanca_id='.$projeto_mudanca_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_mudanca');
			$sql->adCampo('projeto_mudanca_responsavel');
			$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
			$sql->adOnde('projeto_mudanca_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_mudanca_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_mudanca_usuarios.usuario_id)');
			$sql->adOnde('projeto_mudanca_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_mudanca_usuarios.projeto_mudanca_id='.$projeto_mudanca_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_mudanca');
			$sql->adCampo('projeto_mudanca_responsavel');
			$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
			$sql->adOnde('projeto_mudanca_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarMudanca($acesso=0, $projeto_mudanca_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$projeto_mudanca_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('projeto_mudanca_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_mudanca_usuarios.usuario_id)');
			$sql->adOnde('projeto_mudanca_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_mudanca_usuarios.projeto_mudanca_id='.$projeto_mudanca_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_mudanca');
			$sql->adCampo('projeto_mudanca_responsavel');
			$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
			$sql->adOnde('projeto_mudanca_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('projeto_mudanca_usuarios');
			$sql->adCampo('COUNT(DISTINCT projeto_mudanca_usuarios.usuario_id)');
			$sql->adOnde('projeto_mudanca_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND projeto_mudanca_usuarios.projeto_mudanca_id='.$projeto_mudanca_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('projeto_mudanca');
			$sql->adCampo('projeto_mudanca_responsavel');
			$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
			$sql->adOnde('projeto_mudanca_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('projeto_mudanca');
			$sql->adCampo('projeto_mudanca_responsavel');
			$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
			$sql->adOnde('projeto_mudanca_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('projeto_mudanca');
			$sql->adCampo('projeto_mudanca_responsavel');
			$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
			$sql->adOnde('projeto_mudanca_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarAta($acesso=0, $ata_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$ata_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('ata_usuario');
			$sql->adCampo('COUNT(DISTINCT ata_usuario_usuario)');
			$sql->adOnde('ata_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND ata_usuario_ata='.$ata_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.$ata_id);
			$sql->adOnde('ata_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('ata_usuario');
			$sql->adCampo('COUNT(DISTINCT ata_usuario_usuario)');
			$sql->adOnde('ata_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND ata_usuario_ata='.$ata_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.$ata_id);
			$sql->adOnde('ata_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarAta($acesso=0, $ata_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$ata_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('ata_usuario');
			$sql->adCampo('COUNT(DISTINCT ata_usuario_usuario)');
			$sql->adOnde('ata_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND ata_usuario_ata='.$ata_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.$ata_id);
			$sql->adOnde('ata_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('ata_usuario');
			$sql->adCampo('COUNT(DISTINCT ata_usuario_usuario)');
			$sql->adOnde('ata_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND ata_usuario_ata='.$ata_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.$ata_id);
			$sql->adOnde('ata_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.$ata_id);
			$sql->adOnde('ata_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('ata');
			$sql->adCampo('ata_responsavel');
			$sql->adOnde('ata_id = '.$ata_id);
			$sql->adOnde('ata_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarProblema($acesso=0, $problema_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$problema_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('problema_usuarios');
			$sql->adCampo('COUNT(DISTINCT problema_usuarios.usuario_id)');
			$sql->adOnde('problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND problema_usuarios.problema_id='.$problema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.$problema_id);
			$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('problema_usuarios');
			$sql->adCampo('COUNT(DISTINCT problema_usuarios.usuario_id)');
			$sql->adOnde('problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND problema_usuarios.problema_id='.$problema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.$problema_id);
			$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarProblema($acesso=0, $problema_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$problema_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('problema_usuarios');
			$sql->adCampo('COUNT(DISTINCT problema_usuarios.usuario_id)');
			$sql->adOnde('problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND problema_usuarios.problema_id='.$problema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.$problema_id);
			$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('problema_usuarios');
			$sql->adCampo('COUNT(DISTINCT problema_usuarios.usuario_id)');
			$sql->adOnde('problema_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND problema_usuarios.problema_id='.$problema_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.$problema_id);
			$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.$problema_id);
			$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('problema');
			$sql->adCampo('problema_responsavel');
			$sql->adOnde('problema_id = '.$problema_id);
			$sql->adOnde('problema_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarTR($acesso=0, $tr_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$tr_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('tr_usuario');
			$sql->adCampo('COUNT(DISTINCT tr_usuario_usuario)');
			$sql->adOnde('tr_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tr_usuario_tr='.$tr_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.$tr_id);
			$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('tr_usuario');
			$sql->adCampo('COUNT(DISTINCT tr_usuario_usuario)');
			$sql->adOnde('tr_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tr_usuario_tr='.$tr_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.$tr_id);
			$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarTR($acesso=0, $tr_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$tr_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('tr_usuario');
			$sql->adCampo('COUNT(DISTINCT tr_usuario_usuario)');
			$sql->adOnde('tr_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tr_usuario_tr='.$tr_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.$tr_id);
			$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('tr_usuario');
			$sql->adCampo('COUNT(DISTINCT tr_usuario_usuario)');
			$sql->adOnde('tr_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND tr_usuario_tr='.$tr_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.$tr_id);
			$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.$tr_id);
			$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('tr');
			$sql->adCampo('tr_responsavel');
			$sql->adOnde('tr_id = '.$tr_id);
			$sql->adOnde('tr_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function link_tr($tr_id){
	global $Aplic,$config;
	if (!$tr_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('tr');
	$sql->esqUnir('usuarios', 'usuarios', 'tr_responsavel = usuarios.usuario_id');
	$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
	$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
	$sql->adCampo('tr_nome, tr_numero');
	$sql->adOnde('tr_id = '.$tr_id);
	$ata = $sql->Linha();
	$sql->limpar();
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td valign="top" colspan="2"><b>Detalhes do tr</b></td></tr>';
	if ($ata['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$ata['responsavel'].'</td></tr>';
	if ($ata['tr_numero']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Número</b></td><td>'.$ata['tr_numero'].'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique para ver os detalhes.';
	return dica('tr', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=tr&a=tr_ver&tr_id='.$tr_id.'\');">'.$ata['tr_nome'].'</a>'.dicaF();
	}

function permiteAcessarLicao($acesso=0, $licao_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$licao_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('licao_usuarios');
			$sql->adCampo('COUNT(DISTINCT licao_usuarios.usuario_id)');
			$sql->adOnde('licao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND licao_usuarios.licao_id='.$licao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.$licao_id);
			$sql->adOnde('licao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('licao_usuarios');
			$sql->adCampo('COUNT(DISTINCT licao_usuarios.usuario_id)');
			$sql->adOnde('licao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND licao_usuarios.licao_id='.$licao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.$licao_id);
			$sql->adOnde('licao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarLicao($acesso=0, $licao_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$licao_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('licao_usuarios');
			$sql->adCampo('COUNT(DISTINCT licao_usuarios.usuario_id)');
			$sql->adOnde('licao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND licao_usuarios.licao_id='.$licao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.$licao_id);
			$sql->adOnde('licao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('licao_usuarios');
			$sql->adCampo('COUNT(DISTINCT licao_usuarios.usuario_id)');
			$sql->adOnde('licao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND licao_usuarios.licao_id='.$licao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.$licao_id);
			$sql->adOnde('licao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.$licao_id);
			$sql->adOnde('licao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('licao');
			$sql->adCampo('licao_responsavel');
			$sql->adOnde('licao_id = '.$licao_id);
			$sql->adOnde('licao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarAvaliacao($acesso=0, $avaliacao_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$avaliacao_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('avaliacao_usuarios');
			$sql->adCampo('COUNT(DISTINCT avaliacao_usuarios.usuario_id)');
			$sql->adOnde('avaliacao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND avaliacao_usuarios.avaliacao_id='.$avaliacao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.$avaliacao_id);
			$sql->adOnde('avaliacao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('avaliacao_usuarios');
			$sql->adCampo('COUNT(DISTINCT avaliacao_usuarios.usuario_id)');
			$sql->adOnde('avaliacao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND avaliacao_usuarios.avaliacao_id='.$avaliacao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.$avaliacao_id);
			$sql->adOnde('avaliacao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarAvaliacao($acesso=0, $avaliacao_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$avaliacao_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('avaliacao_usuarios');
			$sql->adCampo('COUNT(DISTINCT avaliacao_usuarios.usuario_id)');
			$sql->adOnde('avaliacao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND avaliacao_usuarios.avaliacao_id='.$avaliacao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.$avaliacao_id);
			$sql->adOnde('avaliacao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('avaliacao_usuarios');
			$sql->adCampo('COUNT(DISTINCT avaliacao_usuarios.usuario_id)');
			$sql->adOnde('avaliacao_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND avaliacao_usuarios.avaliacao_id='.$avaliacao_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.$avaliacao_id);
			$sql->adOnde('avaliacao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.$avaliacao_id);
			$sql->adOnde('avaliacao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('avaliacao');
			$sql->adCampo('avaliacao_responsavel');
			$sql->adOnde('avaliacao_id = '.$avaliacao_id);
			$sql->adOnde('avaliacao_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteAcessarPerspectiva($acesso=0, $pg_perspectiva_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_perspectiva_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('perspectivas_usuarios');
			$sql->adCampo('COUNT(DISTINCT perspectivas_usuarios.usuario_id)');
			$sql->adOnde('perspectivas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND perspectivas_usuarios.pg_perspectiva_id='.$pg_perspectiva_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
			$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('perspectivas_usuarios');
			$sql->adCampo('COUNT(DISTINCT perspectivas_usuarios.usuario_id)');
			$sql->adOnde('perspectivas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND perspectivas_usuarios.pg_perspectiva_id='.$pg_perspectiva_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
			$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarPerspectiva($acesso=0, $pg_perspectiva_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pg_perspectiva_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('perspectivas_usuarios');
			$sql->adCampo('COUNT(DISTINCT perspectivas_usuarios.usuario_id)');
			$sql->adOnde('perspectivas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND perspectivas_usuarios.pg_perspectiva_id='.$pg_perspectiva_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
			$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('perspectivas_usuarios');
			$sql->adCampo('COUNT(DISTINCT perspectivas_usuarios.usuario_id)');
			$sql->adOnde('perspectivas_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND perspectivas_usuarios.pg_perspectiva_id='.$pg_perspectiva_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
			$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
			$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_usuario');
			$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
			$sql->adOnde('pg_perspectiva_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarCompromisso($agenda_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	$sql->adTabela('agenda', 'agenda');
	$sql->adCampo('COUNT(agenda_id)');
	$sql->adOnde('agenda_dono IN ('.$Aplic->usuario_lista_grupo.') AND agenda_id='.$agenda_id);
	$quantidade = $sql->Resultado();
	$sql->Limpar();
	return $quantidade;
	}



function permiteExcluirCompromisso($agenda_id=0) {
	global $Aplic;
	$sql = new BDConsulta;
	$sql->adTabela('agenda', 'agenda');
	$sql->adCampo('COUNT(agenda_id)');
	$sql->adOnde('agenda_dono IN ('.$Aplic->usuario_lista_grupo.') AND agenda_id='.$agenda_id);
	$quantidade = $sql->Resultado();
	$sql->Limpar();

	return $quantidade;
	}



function permiteEditarPratica($acesso=0, $pratica_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$pratica_id) return true;//sem pratica
		switch ($acesso) {
			case 0:
				// publico
				$valorRetorno = true;
				break;
			case 1:
				// protegido
				$sql->adTabela('pratica_usuarios');
				$sql->adCampo('COUNT(DISTINCT usuario_id)');
				$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND pratica_id='.$pratica_id);
				$quantidade=$sql->Resultado();
				$sql->limpar();
				$sql->adTabela('praticas');
				$sql->adCampo('pratica_responsavel');
				$sql->adOnde('pratica_id = '.$pratica_id);
				$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
				$pratica_responsavel = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($quantidade > 0 || $pratica_responsavel);
				break;
			case 2:
				// participante
				$sql->adTabela('praticas');
				$sql->adCampo('pratica_responsavel');
				$sql->adOnde('pratica_id = '.$pratica_id);
				$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
				$pratica_responsavel = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($pratica_responsavel);
				break;
			case 3:
				// privado
				$sql->adTabela('praticas');
				$sql->adCampo('pratica_responsavel');
				$sql->adOnde('pratica_id = '.$pratica_id);
				$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
				$pratica_responsavel = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($pratica_responsavel);
				break;
			case 4:
				// protegido II
				$sql->adTabela('praticas');
				$sql->adCampo('pratica_responsavel');
				$sql->adOnde('pratica_id = '.$pratica_id);
				$sql->adOnde('pratica_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
				$pratica_responsavel = $sql->Resultado();
				$sql->limpar();
				$valorRetorno = ($pratica_responsavel);
				break;
			default:
				$valorRetorno = false;
				break;
			}
		return $valorRetorno;

	}




function permiteAcessarRecurso($recurso_nivel_acesso=0, $recurso_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	switch ($recurso_nivel_acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('recurso_usuarios');
			$sql->adCampo('COUNT(recurso_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND recurso_id='.$recurso_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.$recurso_id);
			$sql->adOnde('recurso_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$recurso_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = (($quantidade > 0) || $recurso_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('recurso_usuarios');
			$sql->adCampo('COUNT(recurso_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND recurso_id='.$recurso_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.$recurso_id);
			$sql->adOnde('recurso_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$recurso_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = (($quantidade > 0) || $recurso_responsavel);
			break;
		}
	return $valorRetorno;
	}

function permiteEditarRecurso($recurso_nivel_acesso=0, $recurso_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	switch ($recurso_nivel_acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('recurso_usuarios');
			$sql->adCampo('COUNT(recurso_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND recurso_id='.$recurso_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.$recurso_id);
			$sql->adOnde('recurso_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$recurso_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $recurso_responsavel);
			break;
		case 2:
			// participante
			$sql->adTabela('recurso_usuarios');
			$sql->adCampo('COUNT(recurso_id)');
			$sql->adOnde('usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND recurso_id='.$recurso_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.$recurso_id);
			$sql->adOnde('recurso_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$recurso_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $recurso_responsavel);
			break;
		case 3:
			// privado
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.$recurso_id);
			$sql->adOnde('recurso_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$recurso_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($recurso_responsavel);
			break;
		case 4:
			// protegido II
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_responsavel');
			$sql->adOnde('recurso_id = '.$recurso_id);
			$sql->adOnde('recurso_responsavel IN ('.$Aplic->usuario_lista_grupo.')');
			$recurso_responsavel = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($recurso_responsavel);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function permiteEditarEvento($acesso=0, $evento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if (@$Aplic->usuario_super_admin) return true;
	elseif (!$evento_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('evento_usuarios');
			$sql->adCampo('COUNT(DISTINCT evento_usuarios.usuario_id)');
			$sql->adOnde('evento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND evento_usuarios.evento_id='.$evento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('eventos');
			$sql->adCampo('evento_dono');
			$sql->adOnde('evento_id = '.$evento_id);
			$sql->adOnde('evento_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$evento_dono = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $evento_dono);
			break;
		case 2:
			// participante
			$sql->adTabela('evento_usuarios');
			$sql->adCampo('COUNT(DISTINCT evento_usuarios.usuario_id)');
			$sql->adOnde('evento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND evento_usuarios.evento_id='.$evento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('eventos');
			$sql->adCampo('evento_dono');
			$sql->adOnde('evento_id = '.$evento_id);
			$sql->adOnde('evento_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$evento_dono = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $evento_dono);
			break;
		case 3:
			// privado
			$sql->adTabela('eventos');
			$sql->adCampo('evento_dono');
			$sql->adOnde('evento_id = '.$evento_id);
			$sql->adOnde('evento_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$evento_dono = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($evento_dono);
			break;
		case 4:
			// protegido II
			$sql->adTabela('eventos');
			$sql->adCampo('evento_dono');
			$sql->adOnde('evento_id = '.$evento_id);
			$sql->adOnde('evento_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$evento_dono = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($evento_dono);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}


function permiteAcessarEvento($acesso=0, $evento_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if (@$Aplic->usuario_super_admin) return true;
	elseif (!$evento_id) return true;//sem pratica e acao desconsidera
	$valorRetorno = true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('evento_usuarios');
			$sql->adCampo('COUNT(DISTINCT evento_usuarios.usuario_id)');
			$sql->adOnde('evento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND evento_usuarios.evento_id='.$evento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('eventos');
			$sql->adCampo('evento_dono');
			$sql->adOnde('evento_id = '.$evento_id);
			$sql->adOnde('evento_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$evento_dono = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $evento_dono);
			break;
		case 3:
			// privado
			$sql->adTabela('evento_usuarios');
			$sql->adCampo('COUNT(DISTINCT evento_usuarios.usuario_id)');
			$sql->adOnde('evento_usuarios.usuario_id IN ('.$Aplic->usuario_lista_grupo.') AND evento_usuarios.evento_id='.$evento_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('eventos');
			$sql->adCampo('evento_dono');
			$sql->adOnde('evento_id = '.$evento_id);
			$sql->adOnde('evento_dono IN ('.$Aplic->usuario_lista_grupo.')');
			$evento_dono = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $evento_dono);
			break;
		}
	return $valorRetorno;
	}



function popup_ativado($opcao=''){
	global $config;
	if ($opcao && $config['popup_detalhado']) return true;
	elseif (isset($config['popup_ativado']) && $config['popup_ativado']) return true;
	else return false;
	}

function selecao_calendarios($data_inicio=null, $data_fim=null, $projeto_id=0, $cal_extra='', $oculto_inicio='oculto_data_inicio', $oculto_fim='oculto_data_fim', $executar_funcao='', $executar_funcao_inicio='', $executar_funcao_fim=''){
    $saida="\n".'<script type="text/javascript">'."\n";
    $saida.='var INFO_DATA = {';
    /*if ($projeto_id){
        $sql = new BDConsulta;
        $sql->adTabela('tarefas', 't');
        $sql->adCampo('tarefa_nome, tarefa_inicio, tarefa_fim');
        $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
        $sql->setLimite(0,1000);
        $tarefas = $sql->Lista();
        $sql->limpar();
        $qnt_t=count($tarefas);
        $qnt=0;
        $vetor=array();
        foreach ($tarefas as $valor) {
            $qnt++;
            $data_tarefa = new CData($valor['tarefa_inicio']);
            $indice1=$data_tarefa->format("%Y%m%d");
            $data_tarefa = new CData($valor['tarefa_fim']);
            $indice2=$data_tarefa->format("%Y%m%d");
            $tarefa_nome = htmlspecialchars($valor['tarefa_nome']);
            if ($indice1==$indice2){
                if (isset($vetor[$indice1]) && $vetor[$indice1]) {
                    $vetor[$indice1].='<br><img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                    $cor='calen_misto';
                    }
                else{
                    $vetor[$indice1]='<img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                    $cor='calen_mesmodia';
                    }
                $saida.=$indice1.': { klass: "'.$cor.'", tooltip: "'.$vetor[$indice1].'"}, ';
                }
            else{
                if (isset($vetor[$indice1]) && $vetor[$indice1]) {
                    $vetor[$indice1].='<br><img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /> '.$tarefa_nome;
                    $cor='calen_misto';
                    }
                else{
                    $vetor[$indice1]='<img src=\'./estilo/rondon/imagens/icones/inicio.gif\' /><img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /> '.$tarefa_nome;
                    $cor='calen_tarefa_ini';
                    }
                $saida.=$indice1.': { klass: "'.$cor.'", tooltip: "'.$vetor[$indice1].'"}, ';
                if(isset($vetor[$indice2]) && $vetor[$indice2]) {
                    $vetor[$indice2].='<br><img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                    $cor='calen_misto';
                    }
                else{
                    $vetor[$indice2]='<img src=\'./estilo/rondon/imagens/icones/vazio.gif\' /><img src=\'./estilo/rondon/imagens/icones/fim.gif\' /> '.$tarefa_nome;
                    $cor='calen_tarefa_fim';
                    }
                $saida.=$indice2.': { klass: "'.$cor.'", tooltip: "'.$vetor[$indice2].'"}'.($qnt_t !=1 && $qnt !=$qnt_t ? ', ' : '');
                }
            }
        }*/
    $saida.='};';

    $saida.="\n".'function getInfoData(date, wantsClassName) {
        var como_numero = Calendario.dateToInt(date);
        return INFO_DATA[como_numero];
    };';

    $saida.="\n".'var cal1'.$cal_extra.' = Calendario.setup({
    trigger    : "f_btn1'.$cal_extra.'",
    inputField : "'.$oculto_inicio.$cal_extra.'",
    dateInfo : getInfoData,
    '.($data_inicio ? 'date : '.$data_inicio->format("%Y%m%d").',selection: '.$data_inicio->format("%Y%m%d").',': '').'
    onSelect: function(cal1'.$cal_extra.') {
    var date = cal1'.$cal_extra.'.selection.get();
    if (date){
    date = Calendario.intToDate(date);
    document.getElementById("data_inicio'.$cal_extra.'").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("'.$oculto_inicio.$cal_extra.'").value = Calendario.printDate(date, "%Y-%m-%d");
    '.$executar_funcao.$executar_funcao_inicio.'
    }
    cal1'.$cal_extra.'.hide();
    }
    });';

    $saida.="\n".'var cal2'.$cal_extra.' = Calendario.setup({
    trigger : "f_btn2'.$cal_extra.'",
    inputField : "'.$oculto_fim.$cal_extra.'",
    dateInfo : getInfoData,
    '.($data_fim ? 'date : '.$data_fim->format("%Y%m%d").',selection: '.$data_fim->format("%Y%m%d").',': '').'
    onSelect : function(cal2'.$cal_extra.') {
    var date = cal2'.$cal_extra.'.selection.get();
    if (date){
    date = Calendario.intToDate(date);
    document.getElementById("data_fim'.$cal_extra.'").value = Calendario.printDate(date, "%d/%m/%Y");
    document.getElementById("'.$oculto_fim.$cal_extra.'").value = Calendario.printDate(date, "%Y-%m-%d");
    '.$executar_funcao.$executar_funcao_fim.'
    }
    cal2'.$cal_extra.'.hide();
    }
    });';
    $saida.='</script>';
    return $saida;
	}

function modulo_ativo($nome){
	if (!$nome) return '';
	$sql = new BDConsulta;
	$sql->adTabela('modulos');
	$sql->adCampo('mod_ativo');
	$sql->adOnde('mod_diretorio = \''.$nome.'\'');
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function modulo_instalado($nome){
	if (!$nome) return '';
	$sql = new BDConsulta;
	$sql->adTabela('modulos');
	$sql->adCampo('mod_id');
	$sql->adOnde('mod_diretorio = \''.$nome.'\'');
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}


function nome_municipio($municipio_id){
	if (!$municipio_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('municipios');
	$sql->adCampo('municipio_nome');
	$sql->adOnde('municipio_id = '.$municipio_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_cia($cia_id){
	if (!$cia_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('cias');
	$sql->adCampo('cia_nome');
	$sql->adOnde('cia_id = '.$cia_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_dept($dept_id, $cia_nome=false){
	if (!$dept_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('depts');
	if ($cia_nome) {
		$sql->esqUnir('cias','cias','cia_id=dept_cia');
		$sql->adCampo('concatenar_tres(dept_nome, \' - \', cia_nome) AS nome');
		}
	else $sql->adCampo('dept_nome');
	$sql->adOnde('dept_id = '.$dept_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_usuario($usuario_id, $nome_completo=false){
	global $config;
	if (!$usuario_id) return '&nbsp;';
	$sql = new BDConsulta;

	$sql->adTabela('usuarios');
	$sql->adCampo('usuario_id, usuario_grupo_dept');
	$sql->adOnde('usuario_id IN ('.$usuario_id.')');
	$usuario_grupo_dept = $sql->lista();
	$sql->limpar();
	$nome=array();
	foreach($usuario_grupo_dept as $linha){
		if (!$linha['usuario_grupo_dept']){
			$sql->adTabela('usuarios','u');
			if ($nome_completo) $sql->adCampo('contato_nomecompleto');
			$sql->adCampo('contato_posto, contato_nomeguerra');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
			$sql->adOnde('u.usuario_id = '.(int)$linha['usuario_id']);
			$resultado=$sql->linha();
			$nome[]=($config['militar'] < 10 ? $resultado['contato_posto'].' ' : '').($nome_completo && $resultado['contato_nomecompleto'] ? $resultado['contato_nomecompleto'] : $resultado['contato_nomeguerra']);
			$sql->limpar();
			}
		else {
			$sql->adTabela('usuarios','u');
			$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
			$sql->adCampo('contato_nomeguerra');
			$sql->adOnde('u.usuario_id = '.(int)$linha['usuario_id']);
			$resultado=$sql->linha();
			$nome[]=$resultado['contato_nomeguerra'];
			$sql->limpar();
			}
		}
	return implode(';',$nome);
	}

function nome_contato($contato_id){
	global $config;

	if (!$contato_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('contatos');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
	$sql->adOnde('contato_id = '.$contato_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}


function nome_painel($painel_id){
	if (!$painel_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('painel');
	$sql->adCampo('painel_nome');
	$sql->adOnde('painel_id = '.$painel_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_painel_odometro($painel_odometro_id){
	if (!$painel_odometro_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('painel_odometro');
	$sql->adCampo('painel_odometro_nome');
	$sql->adOnde('painel_odometro_id = '.$painel_odometro_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_painel_composicao($painel_composicao_id){
	if (!$painel_composicao_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('painel_composicao');
	$sql->adCampo('painel_composicao_nome');
	$sql->adOnde('painel_composicao_id = '.$painel_composicao_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_tr($tr_id){
	if (!$tr_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('tr');
	$sql->adCampo('tr_nome');
	$sql->adOnde('tr_id = '.$tr_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_viabilidade($projeto_viabilidade_id){
	if (!$projeto_viabilidade_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('projeto_viabilidade');
	$sql->adCampo('projeto_viabilidade_nome');
	$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function funcao_usuario($usuario_id){
	if (!$usuario_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('usuarios','u');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
	$sql->adCampo('contato_funcao, usuario_grupo_dept');
	$sql->adOnde('u.usuario_id = '.$usuario_id);
	$funcao = $sql->linha();
	$sql->limpar();
	return (!$funcao['usuario_grupo_dept'] ? $funcao['contato_funcao'] : '');
	}

function cia_usuario($usuario_id){
	if (!$usuario_id) return '&nbsp';
	$sql = new BDConsulta;

	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
	$sql->adCampo('cia_nome');
	$sql->adOnde('usuario_id = '.$usuario_id);
	$cia = $sql->Resultado();
	$sql->limpar();
		
	return $cia;
	}


function contato_id($usuario_id){
	if (!$usuario_id) return false;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('usuario_contato');
	$sql->adOnde('usuario_id = '.$usuario_id);
	$contato_id = $sql->Resultado();
	$sql->limpar();
	return $contato_id;
	}

function usuario_id($contato_id){
	if (!$contato_id) return false;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = usuario_contato');
	$sql->adCampo('usuario_id');
	$sql->adOnde('c.contato_id = '.$contato_id);
	$usuario_id = $sql->Resultado();
	$sql->limpar();
	return $usuario_id;
	}


function nome_tarefa($tarefa_id){
	if (!$tarefa_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('tarefas');
	$sql->adCampo('tarefa_nome');
	$sql->adOnde('tarefa_id = '.$tarefa_id);
	$remetente = $sql->Resultado();
	$sql->limpar();
	return $remetente;
	}

function nome_instrumento($instrumento_id){
	if (!$instrumento_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('instrumento');
	$sql->adCampo('instrumento_nome');
	$sql->adOnde('instrumento_id = '.$instrumento_id);
	$remetente = $sql->Resultado();
	$sql->limpar();
	return $remetente;
	}

function nome_pratica($pratica_id){
	if (!$pratica_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('praticas');
	$sql->adCampo('pratica_nome');
	$sql->adOnde('pratica_id = '.$pratica_id);
	$pratica = $sql->Resultado();
	$sql->limpar();
	return $pratica;
	}

function nome_risco($risco_id){
	if (!$risco_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('risco');
	$sql->adCampo('risco_nome');
	$sql->adOnde('risco_id = '.$risco_id);
	$risco = $sql->Resultado();
	$sql->limpar();
	return $risco;
	}

function nome_tgn($tgn_id){
	if (!$tgn_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('tgn');
	$sql->adCampo('tgn_nome');
	$sql->adOnde('tgn_id = '.$tgn_id);
	$tgn = $sql->Resultado();
	$sql->limpar();
	return $tgn;
	}

function nome_canvas($canvas_id){
	if (!$canvas_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('canvas');
	$sql->adCampo('canvas_nome');
	$sql->adOnde('canvas_id = '.$canvas_id);
	$canvas = $sql->Resultado();
	$sql->limpar();
	return $canvas;
	}

function nome_risco_resposta($risco_resposta_id){
	if (!$risco_resposta_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('risco_resposta');
	$sql->adCampo('risco_resposta_nome');
	$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
	$risco_resposta = $sql->Resultado();
	$sql->limpar();
	return $risco_resposta;
	}

function nome_checklist($checklist_id){
	if (!$checklist_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('checklist');
	$sql->adCampo('checklist_nome');
	$sql->adOnde('checklist_id = '.$checklist_id);
	$checklist = $sql->Resultado();
	$sql->limpar();
	return $checklist;
	}

function nome_acao($plano_acao_id){
	if (!$plano_acao_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('plano_acao');
	$sql->adCampo('plano_acao_nome');
	$sql->adOnde('plano_acao_id = '.$plano_acao_id);
	$plano_acao = $sql->Resultado();
	$sql->limpar();
	return $plano_acao;
	}

function nome_indicador($pratica_indicador_id){
	if (!$pratica_indicador_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('pratica_indicador');
	$sql->adCampo('pratica_indicador_nome');
	$sql->adOnde('pratica_indicador_id = '.$pratica_indicador_id);
	$pratica_indicador = $sql->Resultado();
	$sql->limpar();
	return $pratica_indicador;
	}

function nome_monitoramento($monitoramento_id){
	if (!$monitoramento_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('monitoramento');
	$sql->adCampo('monitoramento_nome');
	$sql->adOnde('monitoramento_id = '.$monitoramento_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_operativo($operativo_id){
	if (!$operativo_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('operativo');
	$sql->adCampo('operativo_nome');
	$sql->adOnde('operativo_id = '.$operativo_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}


function nome_objetivo($pg_objetivo_estrategico_id){
	if (!$pg_objetivo_estrategico_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('objetivos_estrategicos');
	$sql->adCampo('pg_objetivo_estrategico_nome');
	$sql->adOnde('pg_objetivo_estrategico_id IN ('.$pg_objetivo_estrategico_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_demanda($demanda_id){
	if (!$demanda_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('demandas');
	$sql->adCampo('demanda_nome');
	$sql->adOnde('demanda_id = '.$demanda_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_problema($problema_id){
	if (!$problema_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('problema');
	$sql->adCampo('problema_nome');
	$sql->adOnde('problema_id = '.$problema_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_licao($licao_id){
	if (!$licao_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('licao');
	$sql->adCampo('licao_nome');
	$sql->adOnde('licao_id = '.$licao_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_evento($evento_id){
	if (!$evento_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('eventos');
	$sql->adCampo('evento_titulo');
	$sql->adOnde('evento_id = '.$evento_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_link($link_id){
	if (!$link_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('links');
	$sql->adCampo('link_nome');
	$sql->adOnde('link_id = '.$link_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_avaliacao($avaliacao_id){
	if (!$avaliacao_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('avaliacao');
	$sql->adCampo('avaliacao_nome');
	$sql->adOnde('avaliacao_id = '.$avaliacao_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_brainstorm($brainstorm_id){
	if (!$brainstorm_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('brainstorm');
	$sql->adCampo('brainstorm_nome');
	$sql->adOnde('brainstorm_id = '.$brainstorm_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}


function nome_gut($gut_id){
	if (!$gut_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('gut');
	$sql->adCampo('gut_nome');
	$sql->adOnde('gut_id = '.$gut_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_causa_efeito($causa_efeito_id){
	if (!$causa_efeito_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('causa_efeito');
	$sql->adCampo('causa_efeito_nome');
	$sql->adOnde('causa_efeito_id = '.$causa_efeito_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_arquivo($arquivo_id){
	if (!$arquivo_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('arquivos');
	$sql->adCampo('arquivo_nome');
	$sql->adOnde('arquivo_id = '.$arquivo_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_forum($forum_id){
	if (!$forum_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('foruns');
	$sql->adCampo('forum_nome');
	$sql->adOnde('forum_id = '.$forum_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_tema($tema_id){
	if (!$tema_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('tema');
	$sql->adCampo('tema_nome');
	$sql->adOnde('tema_id IN ('.$tema_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_estrategia($pg_estrategia_id){
	if (!$pg_estrategia_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('estrategias');
	$sql->adCampo('pg_estrategia_nome');
	$sql->adOnde('pg_estrategia_id IN ('.$pg_estrategia_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_nd($nd_id){
	if (!$nd_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('nd');
	$sql->adCampo('concatenar_tres(nd_item_subitem, \' - \', nd_texto) AS nome');
	$sql->adOnde('nd_id='.(int)$nd_id);
	$nome = $sql->resultado();
	$sql->limpar();
	return $nome;
	}

function nome_me($me_id){
	if (!$me_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('me');
	$sql->adCampo('me_nome');
	$sql->adOnde('me_id IN ('.$me_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_fator($pg_fator_critico_id){
	if (!$pg_fator_critico_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('fatores_criticos');
	$sql->adCampo('pg_fator_critico_nome');
	$sql->adOnde('pg_fator_critico_id IN ('.$pg_fator_critico_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_perspectiva($pg_perspectiva_id){
	if (!$pg_perspectiva_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('perspectivas');
	$sql->adCampo('pg_perspectiva_nome');
	$sql->adOnde('pg_perspectiva_id IN ('.$pg_perspectiva_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_meta($pg_meta_id){
	if (!$pg_meta_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('metas');
	$sql->adCampo('pg_meta_nome');
	$sql->adOnde('pg_meta_id IN ('.$pg_meta_id.')');
	$nome = $sql->carregarColuna();
	$sql->limpar();
	return implode(';',$nome);
	}

function nome_pasta($arquivo_pasta_id){
	if (!$arquivo_pasta_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('arquivo_pasta');
	$sql->adCampo('arquivo_pasta_nome');
	$sql->adOnde('arquivo_pasta_id = '.$arquivo_pasta_id);
	$pasta = $sql->Resultado();
	$sql->limpar();
	return $pasta;
	}

function nome_projeto($projeto_id){
	if (!isset($projeto_id) || !$projeto_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('projetos');
	$sql->adCampo('projeto_nome');
	$sql->adOnde('projeto_id = '.$projeto_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_recurso($recurso_id){
	if (!$recurso_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('recursos');
	$sql->adCampo('recurso_nome');
	$sql->adOnde('recurso_id = '.$recurso_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_ata($ata_id){
	if (!$ata_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('ata');
	$sql->adCampo('ata_titulo, ata_numero');
	$sql->adOnde('ata_id = '.$ata_id);
	$linha = $sql->linha();
	$sql->limpar();
	return ($linha['ata_numero'] < 10 ? '00' : ($linha['ata_numero'] < 100 ? '0' : '')).$linha['ata_numero'].($linha['ata_titulo'] ? ' - '.$linha['ata_titulo'] : '');
	}

function nome_jornada($jornada_id){
	if (!$jornada_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('jornada');
	$sql->adCampo('jornada_nome');
	$sql->adOnde('jornada_id = '.$jornada_id);
	$nome = $sql->Resultado();
	$sql->limpar();
	return $nome;
	}

function nome_agenda($agenda_tipo_id){
	if (!$agenda_tipo_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('agenda_tipo');
	$sql->adCampo('nome');
	$sql->adOnde('agenda_tipo_id = '.$agenda_tipo_id);
	$agenda = $sql->Resultado();
	$sql->limpar();
	return $agenda;
	}

function nome_calendario($calendario_id){
	if (!$calendario_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('calendario');
	$sql->adCampo('calendario_nome');
	$sql->adOnde('calendario_id IN ('.$calendario_id.')');
	$calendario = $sql->carregarColuna();
	$sql->limpar();
	$calendario=implode(', ',$calendario);

	return $calendario;
	}

function msg_email_interno($email_usuario='', $titulo='', $texto='', $de='',$usuario_id=0, $arquivo=''){
	global $login_por_nome, $config, $Aplic, $bd;
	$data=date('Y-m-d H:i:s');

	$assinatura='';
	if (function_exists('openssl_sign') && $Aplic->chave_privada)	{
		$identificador='00'.$titulo.$Aplic->usuario_id.$texto.'00'.$data;
		openssl_sign($identificador, $assinatura, $Aplic->chave_privada, OPENSSL_ALGO_SHA1);
		}
	$sql = new BDConsulta;
	if($email_usuario && !$usuario_id){
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('usuario_id');
		$sql->adOnde('contato_email=\''.$email_usuario.'\' OR contato_email2=\''.$email_usuario.'\'');
		$usuario_id = $sql->Resultado();
		$sql->limpar();
		}
	if ($usuario_id){
		$sql->adTabela('msg');
		$sql->adInserir('referencia', $titulo);
		$sql->adInserir('de_id', ($de ? $de : ($Aplic->usuario_id > 0 ? $Aplic->usuario_id : null)));
		$sql->adInserir('texto', $texto);
		$sql->adInserir('data_envio', $data);
		$sql->adInserir('cm', 0);
		$sql->adInserir('nome_de', ($de ? nome_usuario($de) : $Aplic->usuario_nome) );
		$sql->adInserir('funcao_de', ($de ? funcao_usuario($de) : $Aplic->usuario_funcao));
		if ($assinatura) $sql->adInserir('assinatura', base64_encode($assinatura));
		if ($Aplic->chave_publica_id) $sql->adInserir('chave_publica', $Aplic->chave_publica_id);
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de msgs!');
		$msg_id=$bd->Insert_ID('msg','msg_id');
		$sql->Limpar();
		$sql->adTabela('msg_usuario');
		$sql->adInserir('de_id', ($de ? $de : ($Aplic->usuario_id > 0 ? $Aplic->usuario_id : null)));
		$sql->adInserir('para_id', $usuario_id);
		$sql->adInserir('msg_id', $msg_id);
		$sql->adInserir('datahora', $data);
		$sql->adInserir('nome_de', ($de ? nome_usuario($de) : $Aplic->usuario_nome));
		$sql->adInserir('funcao_de', ($de ? funcao_usuario($de) : $Aplic->usuario_funcao));
		$sql->adInserir('nome_para', nome_usuario($usuario_id));
		$sql->adInserir('funcao_para', funcao_usuario($usuario_id));
		if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de usuários d'.$config['genero_mensagem'].' '.$config['mensagem'].'!');
		$sql->Limpar();
	  $encaminha = retorna_encaminha($usuario_id);
	  if ($encaminha) {
			$sql->adTabela('msg_usuario');
			$sql->adInserir('de_id', $usuario_id);
			$sql->adInserir('para_id', $encaminha);
			$sql->adInserir('msg_id', $msg_id);
			$sql->adInserir('tipo', '1');
			$sql->adInserir('datahora', $data);
			$sql->adInserir('nome_de', nome_usuario($usuario_id));
			$sql->adInserir('funcao_de', funcao_usuario($usuario_id));
			$sql->adInserir('nome_para', nome_usuario($encaminha));
			$sql->adInserir('funcao_para', funcao_usuario($encaminha));
			if (!$sql->exec()) die('Não foi possível inserir os dados na tabela de usuários da mensagem!');
			$sql->Limpar();
			}
		grava_anexo(getParam($_REQUEST, 'doc_nr', ''), getParam($_REQUEST, 'doc_tipo', ''), 'doc', getParam($_REQUEST, 'nome_fantasia', ''), $msg_id);
		return false;
		}
	else return 'falha ao enviar mensagem. Não foi encontrado a conta do destinatário.';
	}

function msg_email_externo($para, $titulo, $texto, $msg_id=0, $modelo_id=0){
	global $login_por_nome, $config, $Aplic;
	if (!$config['email_ativo']) return false;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	$end_modelo=array();
	require_once ($Aplic->getClasseSistema('libmail'));
	$data=date('Y-m-d H:i:s');
	$email = new Mail;
    $email->De($config['email'], $Aplic->usuario_nome);

    if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
        $email->ResponderPara($Aplic->usuario_email);
        }
    else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
        $email->ResponderPara($Aplic->usuario_email2);
        }


	$email_ok=array();
	foreach ((array)$para as $destinatario) {
		if ($email->EmailValido($destinatario)) $email_ok[]=$destinatario;
		}
	$resultado=false;
	$sql = new BDConsulta;

	if($msg_id){
		$sql->adTabela('anexos');
		$sql->adCampo('nome_fantasia, nome, caminho');
		$sql->adOnde('msg_id = '.$msg_id);
		$sql->adOrdem('anexo_id DESC');
		$anexos = $sql->Lista();
		$sql->limpar();
		foreach ($anexos as $rs_anexo){
			$email->AddAttachment(($config['pasta_anexos'] ? $config['pasta_anexos'].'/':'').$rs_anexo['caminho'], $rs_anexo['nome_fantasia']);
			}
		}

	if($modelo_id){
		//criar o documento rtf do modelo
		require_once $Aplic->getClasseSistema('Modelo');
		require_once $Aplic->getClasseSistema('Template');
		$sql->adTabela('modelos');
		$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
		$sql->adCampo('class_sigilosa, modelo_assinatura, modelo_chave_publica, modelo_id, modelo_tipo, modelo_criador_original, modelo_data, modelo_versao_aprovada, modelo_protocolo, modelo_autoridade_assinou, modelo_autoridade_aprovou, modelo_assunto, modelo_tipo_html');
		$sql->adOnde('modelo_id='.$modelo_id);
		$linha=$sql->Linha();
		$sql->Limpar();

		$sql->adTabela('modelos_dados');
		$sql->esqUnir('usuarios', 'usuarios', 'usuario_id = modelos_dados_criador');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo('contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adCampo('modelo_dados_id, modelos_dados_campos, modelos_dados_criador, modelo_dados_data');
		$sql->adOnde('modelo_dados_modelo='.$modelo_id);
		$sql->adOrdem('modelo_dados_id DESC');
		$dados=$sql->Linha();
		$sql->Limpar();
		$modelo_dados_id=$dados['modelo_dados_id'];
		$criador=$dados['modelos_dados_criador'];
		$campos = unserialize($dados['modelos_dados_campos']);
		$modelo= new Modelo;
		$modelo->set_modelo_tipo($linha['modelo_tipo']);
		$modelo->set_modelo_id($modelo_id);
		foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao, $campo['extra'], $campo['larg_max'], $campo['outro_campo']);
		$tpl = new Template($linha['modelo_tipo_html'],'',$config['militar'], true);
		$modelo->set_modelo($tpl);
		$modelo->edicao=false;
		$modelo->impressao=true;
		for ($i=1; $i <= $modelo->quantidade(); $i++){
			$campo='campo_'.$i;
			$tpl->$campo = $modelo->get_campo($i);
			}
		$numero=$base_dir.'/arquivos/temp/'.$Aplic->usuario_id.'_'.rand();
		$end_modelo[]=$numero;
		$fp = fopen($numero.'.rtf', 'w+');
		fwrite($fp, $tpl->exibir());
		fclose($fp);
		$email->AddAttachment($numero.'.rtf', $linha['modelo_assunto'].'.rtf');
		$sql->adTabela('modelos_anexos');
		$sql->adCampo('nome_fantasia, nome, caminho');
		$sql->adOnde('modelo_id = '.$modelo_id);
		$sql->adOrdem('modelo_anexo_id DESC');
		$anexos = $sql->Lista();
		$sql->limpar();
		foreach ($anexos as $rs_anexo){
			$email->AddAttachment(($config['pasta_anexos'] ? $config['pasta_anexos'].'_modelos/':'').$rs_anexo['caminho'], $rs_anexo['nome']);
			}
		}

	if (count($email_ok) && $Aplic->profissional){
		require_once BASE_DIR.'/incluir/funcoes_principais_pro.php';
		foreach($email_ok as $contato_email){
			$sql = new BDConsulta;
			$sql->adTabela('contatos');
			$sql->esqUnir('usuarios', 'usuarios', 'usuario_contato=contato_id');
			$sql->adOnde('contato_email=\''.$contato_email.'\'');
			$sql->adCampo('usuario_id');
			$usuario_id=$sql->Resultado();
			$sql->limpar();

			$endereco=($usuario_id ? link_email_externo($usuario_id, ($msg_id ? 'm=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&msg_id='.$msg_id : 'm=email&a=modelo_editar&modelo_id='.$modelo_id)) : '');
			$link=($endereco ? '<br><a href="'.$endereco.'"><b>Clique para acessar</b></a>' : '');
			$email->Para($contato_email);
			$email->Assunto($titulo);
			$email->Corpo($texto.$link);
			$resultado=$email->Enviar();
			}
		}
	elseif (count($email_ok)){
		$email->Para($email_ok);
		$email->Assunto($titulo);
		$email->Corpo($texto);
		$resultado=$email->Enviar();
		}
	foreach($end_modelo as $numero)	@unlink($numero.'.rtf');
	return $resultado;
	}

function envia_email_interno($contato_id){
	return ' href="javascript:void(0);" onclick="javascript:window.open(\'?m=publico&a=email_interno&dialogo=1&contato_id='.$contato_id.'\', \'E-mail\', \'width=790, height=470, left=0, top=0, scrollbars=yes, resizable=no\')"';
	}

function email_valido($endereco) {
	if (preg_match('/^(.*)\<(.+)\>$/D', $endereco, $regs)) $endereco = $regs[2];
	return (bool)preg_match('/^[^@ ]+@([-a-zA-Z0-9..]+)$/D', $endereco);
	}


function envia_email_externo($contato_id=0, $usuario_id=0){
	return ' href="javascript:void(0);" onclick="javascript: window.open(\'?m=publico&a=email_externo&dialogo=1&contato_id='.$contato_id.'&usuario_id='.$usuario_id.'\', \'Email\', \'status=no, directories=no, menubar=no, titlebar=no, location=no, width=750, height=560, left=0, top=0, scrollbars=yes, resizable=no\');"';

	}

function prioridade($valor, $projeto=false, $texto=false) {
	global $config;
	$prioridade = getSisValor('PrioridadeTarefa');
	if (!isset($valor)) $valor=0;
	if ((int)$valor <= 0) $seta='icones/prioridade'.$valor.'.gif';
	else $seta='icones/prioridade+'.$valor.'.gif';
	if ($config['popup_detalhado']){
		if ($texto) return dica('Prioridade','A prioridade '.($projeto ? 'd'.$config['genero_projeto'].' '.$config['projeto'] : 'd'.$config['genero_tarefa'].' '.$config['tarefa']).' é <b>'.$prioridade[$valor].'</b>.').$prioridade[$valor].dicaF();
		else return dica('Prioridade','A prioridade '.($projeto ? 'd'.$config['genero_projeto'].' '.$config['projeto'] : 'd'.$config['genero_tarefa'].' '.$config['tarefa']).' é <b>'.$prioridade[$valor].'</b>.').imagem($seta).dicaF();
		}
	else {
		if ($texto) return $prioridade[$valor];
		else return imagem($seta);
		}
	}

function checar_sobrecarga($usuario_id, $data_dia){
	require_once BASE_DIR.'/modulos/calendario/jornada.class.php';
	$expediente=new Cjornada(null, $usuario_id);
	$d="%Y-%m-%d";
	$horas_diasuteis=array();
	$data_dia=new CData($data_dia);
	$horas_tarefas_diasuteis=array();
	$sql = new BDConsulta;
	$sql->adTabela('tarefas', 't1');
	$sql->adCampo('tarefa_inicio, tarefa_fim, tarefa_duracao ,perc_designado');
	$sql->adUnir('tarefa_designados', 'ut', 't1.tarefa_id = ut.tarefa_id');
	$sql->adOnde('ut.usuario_id = '.(int)$usuario_id);
	$sql->adOnde('tarefa_duracao > 0');
	$sql->adOnde('date(tarefa_inicio) <= \''.$data_dia->format($d).'\' AND date(tarefa_fim)>= \''.$data_dia->format($d).'\'');
	$tarefas=$sql->Lista();
	foreach ($tarefas as $tarefa) {
		$data_inicial=new CData($tarefa['tarefa_inicio']);
		$data_final=new CData($tarefa['tarefa_fim']);
		$data=$data_inicial;
		for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i <= $i_cmp; $i++) {
			if (!isset($horas_diasuteis[$data->format($d)])) $horas_diasuteis[$data->format($d)]= round($expediente->horas_dia($data->format($d)), 2);
			$data = $data->getNextDay();
			}
		$soma_hora_uteis=0;
		$data=$data_inicial;
		for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i <= $i_cmp; $i++) {
			if (isset($horas_diasuteis[$data->format($d)]) && $horas_diasuteis[$data->format($d)]) {
				$soma_hora_uteis+=$horas_diasuteis[$data->format($d)];
				}
			$data = $data->getNextDay();
			}
		$data=$data_inicial;
		for ($i = 0, $i_cmp = $data_inicial->dataDiferenca($data_final); $i <= $i_cmp; $i++) {
			if (isset($horas_diasuteis[$data->format($d)]) && $horas_diasuteis[$data->format($d)]) {
				$horas=round(($horas_diasuteis[$data->format($d)]/$soma_hora_uteis)*$tarefa['tarefa_duracao'], 2);
				if (isset($horas_tarefas_diasuteis[$data->format($d)]))$horas_tarefas_diasuteis[$data->format($d)]+=$horas;
				else $horas_tarefas_diasuteis[$data->format($d)]=$horas;
				}
			$data = $data->getNextDay();
			}
		}
	if (!$horas_diasuteis[$data_dia->format($d)]) return 0;
	return (int)(100*$horas_tarefas_diasuteis[$data_dia->format($d)]/$horas_diasuteis[$data_dia->format($d)]);
	}

function link_tarefa($tarefa_id, $sem_texto='', $so_texto='', $calendario=0, $texto_email=false) {
	global $Aplic,$config, $dialogo;
	if (!$tarefa_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta;
		$sql->adTabela('tarefas', 't');
		$sql->adCampo('tarefa_nome, tarefa_inicio, tarefa_fim, tarefa_projeto, tarefa_tipo, tarefa_descricao, tarefa_marco, tarefa_percentagem, projeto_nome, projeto_cor, cia_nome');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->esqUnir('usuarios', 'usuarios', 'tarefa_dono = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('projetos', 'p', 'tarefa_projeto = projeto_id');
		$sql->esqUnir('cias', 'c', 'tarefa_cia = cia_id');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$linha = $sql->Linha();
		$sql->limpar();
		$sql->adTabela('depts', 'd');
		$sql->adTabela('tarefa_depts', 't');
		$sql->adOnde('t.departamento_id = d.dept_id');
		$sql->adOnde('t.tarefa_id = '.(int)$tarefa_id);
		$sql->adCampo('dept_id, dept_nome');
		$depts = $sql->ListaChave('dept_id');
		$sql->limpar();

		$sql->adTabela('usuarios', 'u');
		$sql->adTabela('tarefa_designados', 'ut');
		$sql->adTabela('contatos', 'con');
		$sql->adCampo('u.usuario_id, concatenar_quatro(contato_posto, \' \', contato_nomeguerra, concatenar_dois( CAST( perc_designado AS char ), \'%\'))');

		$sql->adOnde('ut.tarefa_id = '.(int)$tarefa_id);
		$sql->adOnde('usuario_contato = contato_id');
		$sql->adOnde('ut.usuario_id = u.usuario_id');
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$designado = $sql->ListaChave();
		$sql->limpar();
		$data_inicio = $linha['tarefa_inicio'] ? new CData($linha['tarefa_inicio']) : null;
		$data_fim = $linha['tarefa_fim'] ? new CData($linha['tarefa_fim']) : null;
		$tt = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>'.ucfirst($config['projeto']).'</b></td><td width="100%">'.$linha['projeto_nome'].'</td></tr>';
		$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Responsável</b></td><td width="100%">'.$linha['responsavel'].'</td></tr>';
		$tt .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		$inicio = false;
		if (count($depts)){
			$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>'.(count($depts) > 1 ? $config['departamentos'] : $config['departamento']).'</b></td><td width="100%">';
			foreach ($depts as $dept_id => $dept_info) {
				if ($inicio)	$tt .= '<br/>';
				else $inicio = true;
				$tt .=$dept_info['dept_nome'];
				}
			$tt .='</td></tr>';
			}
		if ($linha['tarefa_tipo']) $tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Tipo</b></td><td width="100%" nowrap="nowrap">'.getSisValorCampo('TipoTarefa',$linha['tarefa_tipo']).'</td></tr>';
		$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Progresso</b></td><td width="100%" nowrap="nowrap">'.sprintf("%.1f%%", $linha['tarefa_percentagem']).'</td></tr>';
		$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Início</b></td><td nowrap="nowrap">'.($data_inicio ? $data_inicio->format($df.' '.$tf) : '').'</td></tr>';
		$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Término</b></td><td nowrap="nowrap">'.($data_fim ? $data_fim->format($df.' '.$tf) : '').'</td></tr>';
		if ($designado && count($designado)) {
			$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Designados</b></td><td nowrap="nowrap">';
			$inicio = false;
			foreach ($designado as $usuario) {
				if ($inicio)	$tt .= '<br/>';
				else $inicio = true;
				$tt .= $usuario;
				}
			$tt .= '</td></tr>';
			}
		if ($linha['tarefa_descricao'])	$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Descrição</b></td><td>'.$linha['tarefa_descricao'].'</td></tr>';
		if (!$so_texto) $tt .= '<tr><td align="left" width="100%" colspan="2">Clique para ver os detalhes d'.$config['genero_tarefa'].' '.$config['tarefa'].'</td></tr>';
		$tt .= '</table>';
		$nome=$linha['tarefa_nome'];
		if ($calendario){
			if (strlen($linha['tarefa_nome']) > $calendario) $nome=substr($linha['tarefa_nome'], 0, $calendario).'...';
			$nome='<span style="color:#'.melhorCor($linha['projeto_cor']).';background-color:#'.$linha['projeto_cor'].'">'.$nome.($linha['tarefa_marco'] ? '&nbsp;'.imagem('icones/marco.gif') : '').'</span>';
			}

		if ($texto_email) return $tt;
		elseif ($sem_texto) return dica($linha['tarefa_nome'],$tt,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  $Aplic->getEstado('link_em_janela') : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$tarefa_id.'\');">';
		elseif ($so_texto) return dica($linha['tarefa_nome'],$tt,'','',true).$nome.dicaF();
		else return dica($linha['tarefa_nome'],$tt,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  $Aplic->getEstado('link_em_janela') : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$tarefa_id.'\');">'.$nome.'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('tarefas');
		$sql->adCampo('tarefa_nome');
		$sql->adOnde('tarefa_id = '.(int)$tarefa_id);
		$linha = $sql->Linha();
		$sql->limpar();
		if ($sem_texto) return dica('','').'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  $Aplic->getEstado('link_em_janela') : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$tarefa_id.'\');">';
		else if ($so_texto) return '<a href="javascript:void(0);">'.$linha['tarefa_nome'].'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$tarefa_id.'\');">' : '').$linha['tarefa_nome'].(!$dialogo ? '</a>' : '');
		}
	}

function link_acao_item($plano_acao_item_id, $sem_texto='', $so_texto='', $calendario=0, $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;
	if (!$plano_acao_item_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_item', 'pai');
		$sql->esqUnir('plano_acao', 'pa', 'pa.plano_acao_id = pai.plano_acao_item_acao');
		$sql->esqUnir('usuarios', 'usuarios', 'plano_acao_item_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'c', 'plano_acao_cia = cia_id');
		$sql->adCampo('plano_acao_item_principal_indicador, plano_acao_item_nome, plano_acao_item_oque, cia_nome, plano_acao_cor, plano_acao_id');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
		$linha = $sql->Linha();
		$sql->limpar();
		$indicador='';
		if ($tem_indicador && $linha['plano_acao_item_principal_indicador']) $indicador=cor_indicador('plano_acao_item', '', $ano, $inicio, $fim, $linha['plano_acao_item_principal_indicador']);
		$tt = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($linha['responsavel']) $tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Responsável</b></td><td width="100%">'.$linha['responsavel'].'</td></tr>';
		if ($linha['cia_nome']) $tt .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		$inicio = false;
		if (!$so_texto) $tt .= '<tr><td align="left" width="100%" colspan="2">Clique para ver este item d'.$config['genero_acao'].' '.$config['acao'].'.</td></tr>';
		$tt .= '</table>';
		$nome=($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']);
		if ($calendario){
			if (strlen(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque'])) > $calendario) $nome=substr(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']), 0, $calendario).'...';
			$nome='<span style="color:#'.melhorCor($linha['plano_acao_cor']).';background-color:#'.$linha['plano_acao_cor'].'">'.$nome.'</span>';
			}
		if ($sem_texto) return dica(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']),$tt,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['plano_acao_id'].'\');">';
		else if ($so_texto) return '<a href="javascript:void(0);">'.dica(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']),$tt,'','',true).$nome.'</a>'.dicaF();
		else return $indicador.dica(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']),$tt,'','',true).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['plano_acao_id'].'\');">' : '').$nome.(!$dialogo ? '</a>' : '').dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao_item','pai');
		$sql->esqUnir('plano_acao', 'pa', 'pa.plano_acao_id = pai.plano_acao_item_acao');
		$sql->adCampo('plano_acao_item_nome, plano_acao_item_oque, plano_acao_item_acao, plano_acao_cor');
		$sql->adOnde('plano_acao_item_id = '.(int)$plano_acao_item_id);
		$linha = $sql->Linha();
		$sql->limpar();

		$nome=($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']);
		if ($calendario){
			if (strlen(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque'])) > $calendario) $nome=substr(($linha['plano_acao_item_nome'] ? $linha['plano_acao_item_nome'] : $linha['plano_acao_item_oque']), 0, $calendario).'...';
			$nome='<span style="color:#'.melhorCor($linha['plano_acao_cor']).';background-color:#'.$linha['plano_acao_cor'].'">'.$nome.'</span>';
			}

		if ($sem_texto) return dica('','').'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$linha['plano_acao_item_acao'].'\');">';
		else if ($so_texto) return '<a href="javascript:void(0);">'.$nome.'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_item_id.'\');">'.$nome.'</a>';
		}
	}

function link_acao($plano_acao_id, $sem_texto='', $so_texto='', $calendario=0, $so_descricao=false, $tem_indicador=false, $ano=null) {
	global $Aplic,$config, $dialogo;
	if (!$plano_acao_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao', 't');
		$sql->esqUnir('usuarios', 'usuarios', 'plano_acao_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'c', 'plano_acao_cia = cia_id');
		$sql->adCampo('plano_acao_principal_indicador, plano_acao_nome, plano_acao_descricao, cia_nome, plano_acao_cor');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('plano_acao_id = '.(int)$plano_acao_id);
		$linha = $sql->Linha();
		$sql->limpar();


		$indicador='';
		if ($tem_indicador && $linha['plano_acao_principal_indicador']) $indicador=cor_indicador('plano_acao', '', $ano, $inicio, $fim, $linha['plano_acao_principal_indicador']);


		$tt = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  $tt .= '<tr><td valign="top" colspan="2"><b>Detalhes d'.$config['genero_acao'].' '.ucfirst($config['acao']).'</b></td></tr>';
		if ($linha['responsavel']) $tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Responsável</b></td><td width="100%">'.$linha['responsavel'].'</td></tr>';
		if ($linha['cia_nome']) $tt .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		$inicio = false;

		if ($linha['plano_acao_descricao'])	$tt .= '<tr><td style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;" align="center"><b>Descrição</b></td><td>'.$linha['plano_acao_descricao'].'</td></tr>';
		if (!$so_texto) $tt .= '<tr><td align="left" width="100%" colspan="2">Clique para ver '.($config['genero_acao']=='a' ? 'esta' : 'este').' '.$config['acao'].'.</td></tr>';
		$tt .= '</table>';



		$nome=$linha['plano_acao_nome'];
		if ($calendario){
			if (strlen($linha['plano_acao_nome']) > $calendario) $nome=substr($linha['plano_acao_nome'], 0, $calendario).'...';
			$nome='<span style="color:#'.melhorCor($linha['plano_acao_cor']).';background-color:#'.$linha['plano_acao_cor'].'">'.$nome.'</span>';
			}
		if ($so_descricao) return	$tt;
		else if ($sem_texto) return dica($linha['plano_acao_nome'],$tt,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_id.'\');">';
		else if ($so_texto) return $indicador.'<a href="javascript:void(0);">'.dica($linha['plano_acao_nome'],$tt,'','',true).$nome.'</a>'.dicaF();
		else return $indicador.dica($linha['plano_acao_nome'],$tt,'','',true).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_id.'\');">' : '').$nome.(!$dialogo ? '</a>' : '').dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('plano_acao');
		$sql->adCampo('plano_acao_nome, plano_acao_cor');
		$sql->adOnde('plano_acao_id = '.(int)$plano_acao_id);
		$linha = $sql->Linha();
		$sql->limpar();

		$nome=$linha['plano_acao_nome'];
		if ($calendario){
			if (strlen($linha['plano_acao_nome']) > $calendario) $nome=substr($linha['plano_acao_nome'], 0, $calendario).'...';
			$nome='<span style="color:#'.melhorCor($linha['plano_acao_cor']).';background-color:#'.$linha['plano_acao_cor'].'">'.$nome.'</span>';
			}
		if ($sem_texto) return dica('','').'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_id.'\');">';
		else if ($so_texto) return '<a href="javascript:void(0);">'.$nome.'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao_id.'\');">'.$nome.'</a>';
		}
	}

function link_estrategia($pg_estrategia_id, $tem_indicador=false, $ano=null, $inicio=null, $fim=null){
	global $Aplic,$config, $dialogo;
	if (!$pg_estrategia_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('estrategias');
		$sql->esqUnir('fatores_criticos', 'fatores_criticos', 'pg_estrategia_fator = pg_fator_critico_id');
		$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_fator_critico_objetivo = pg_objetivo_estrategico_id');
		$sql->esqUnir('tema', 'tema', 'pg_objetivo_estrategico_tema = tema_id');
		$sql->esqUnir('perspectivas', 'perspectivas', 'pg_objetivo_estrategico_perspectiva = pg_perspectiva_id');
		$sql->esqUnir('usuarios', 'usuarios', 'pg_estrategia_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=pg_estrategia_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('pg_estrategia_principal_indicador, pg_estrategia_nome, pg_estrategia_descricao, pg_estrategia_oque, pg_estrategia_composicao, pg_perspectiva_nome, tema_nome, pg_objetivo_estrategico_nome, pg_fator_critico_nome');
		$sql->adOnde('pg_estrategia_id = '.$pg_estrategia_id);
		$estrategia = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$icone='';
		if ($estrategia['pg_estrategia_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=estrategia_explodir&dialogo=1&pg_estrategia_id='.$pg_estrategia_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/indicador_exp_p.png','Composição de Iniciativas Estratégicas','Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição de iniciativas estratégicas.').'</a>';

		$indicador='';
		if ($tem_indicador && $estrategia['pg_estrategia_principal_indicador']) $indicador=cor_indicador('estrategia', '', $ano, $inicio, $fim, $estrategia['pg_estrategia_principal_indicador']);


		if ($estrategia['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$estrategia['cia_nome'].'</td></tr>';
		if ($estrategia['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$estrategia['responsavel'].'</td></tr>';

		if ($estrategia['pg_estrategia_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$estrategia['pg_estrategia_descricao'].'</td></tr>';
		if ($estrategia['pg_estrategia_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$estrategia['pg_estrategia_oque'].'</td></tr>';

		if ($estrategia['pg_fator_critico_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['fator']).'</b></td><td>'.$estrategia['pg_fator_critico_nome'].'</td></tr>';
		if ($estrategia['pg_objetivo_estrategico_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['objetivo']).'</b></td><td>'.$estrategia['pg_objetivo_estrategico_nome'].'</td></tr>';
		if ($estrategia['tema_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['tema']).'</b></td><td>'.$estrategia['tema_nome'].'</td></tr>';
		if ($estrategia['pg_perspectiva_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['perspectiva']).'</b></td><td>'.$estrategia['pg_perspectiva_nome'].'</td></tr>';

		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver '.($config['genero_iniciativa']=='a' ? 'esta' : 'este').' '.$config['iniciativa'].'.';
		return $indicador.dica(ucfirst($config['iniciativa']), $dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=estrategia_ver&pg_estrategia_id='.$pg_estrategia_id.'\');">' : '').converte_texto_grafico($estrategia['pg_estrategia_nome']).(!$dialogo ? '</a>' : '').$icone.dicaF();
		}

function link_instrumento($instrumento_id){
	global $Aplic,$config;
	if (!$instrumento_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('instrumento');
		$sql->esqUnir('usuarios', 'usuarios', 'instrumento_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=instrumento_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('instrumento_nome, instrumento_objeto, instrumento_valor');
		$sql->adOnde('instrumento_id = '.$instrumento_id);
		$instrumento = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($instrumento['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$instrumento['cia_nome'].'</td></tr>';
		if ($instrumento['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$instrumento['responsavel'].'</td></tr>';
		if ($instrumento['instrumento_objeto']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Objeto</b></td><td>'.$instrumento['instrumento_objeto'].'</td></tr>';
		if ($instrumento['instrumento_valor']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Valor</b></td><td>'.$config['simbolo_moeda'].' '.number_format($instrumento['instrumento_valor'], 2, ',', '.').'</td></tr>';

		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver '.($config['genero_instrumento']=='a' ? 'esta' : 'este').' '.$config['instrumento'].'.';
		return dica(ucfirst($config['instrumento']), $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=recursos&a=instrumento_ver&instrumento_id='.$instrumento_id.'\');">'.converte_texto_grafico($instrumento['instrumento_nome']).'</a>'.dicaF();
		}

function link_recebimento($projeto_recebimento_id){
	global $Aplic,$config;
	if (!$projeto_recebimento_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('projeto_recebimento');
		$sql->esqUnir('usuarios', 'usuarios', 'projeto_recebimento_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('projeto_recebimento_numero, projeto_recebimento_observacao');
		$sql->adOnde('projeto_recebimento_id = '.$projeto_recebimento_id);
		$projeto_recebimento = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($projeto_recebimento['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$projeto_recebimento['responsavel'].'</td></tr>';
		if ($projeto_recebimento['projeto_recebimento_observacao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Justificativa</b></td><td>'.$projeto_recebimento['projeto_recebimento_observacao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este recebimento de produtos/serviços.';
		return dica('Recebimento de Produtos/Serviços', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=recebimento_ver&projeto_recebimento_id='.$projeto_recebimento_id.'\');">'.($projeto_recebimento['projeto_recebimento_numero']<100 ? '0' : '').($projeto_recebimento['projeto_recebimento_numero']<10 ? '0' : '').$projeto_recebimento['projeto_recebimento_numero'].'</a>'.dicaF();
		}

function link_mudanca($projeto_mudanca_id){
	global $Aplic,$config;
	if (!$projeto_mudanca_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('projeto_mudanca');
		$sql->esqUnir('usuarios', 'usuarios', 'projeto_mudanca_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('projeto_mudanca_numero, projeto_mudanca_justificativa');
		$sql->adOnde('projeto_mudanca_id = '.$projeto_mudanca_id);
		$projeto_mudanca = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($projeto_mudanca['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$projeto_mudanca['responsavel'].'</td></tr>';
		if ($projeto_mudanca['projeto_mudanca_justificativa']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Justificativa</b></td><td>'.$projeto_mudanca['projeto_mudanca_justificativa'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver os detalhes desta solicitação de mudança.';
		return dica('Solicitação de Mudança', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=mudanca_ver&projeto_mudanca_id='.$projeto_mudanca_id.'\');">'.($projeto_mudanca['projeto_mudanca_numero']<100 ? '0' : '').($projeto_mudanca['projeto_mudanca_numero']<10 ? '0' : '').$projeto_mudanca['projeto_mudanca_numero'].'</a>'.dicaF();
		}

function link_ata($ata_id){
	global $Aplic,$config;
	if (!$ata_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('ata');
		$sql->esqUnir('usuarios', 'usuarios', 'ata_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('ata_numero, ata_relato');
		$sql->adOnde('ata_id = '.$ata_id);
		$ata = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';

		if ($ata['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$ata['responsavel'].'</td></tr>';
		if ($ata['ata_relato']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Relato</b></td><td>'.$ata['ata_relato'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver esta ata de reunião.';
		return dica('Ata', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ata_ver&ata_id='.$ata_id.'\');">'.($ata['ata_numero']<100 ? '0' : '').($ata['ata_numero']<10 ? '0' : '').$ata['ata_numero'].'</a>'.dicaF();
		}

function link_demanda($demanda_id){
	global $Aplic,$config;
	if (!$demanda_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('demandas');
		$sql->esqUnir('usuarios', 'usuarios', 'demanda_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=demanda_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('demanda_nome, demanda_identificacao, demanda_justificativa');
		$sql->adOnde('demanda_id = '.(int)$demanda_id);
		$demanda = $sql->Linha();
		$sql->limpar();

		if ($Aplic->profissional){
			$sql->adTabela('demanda_portfolio');
			$sql->adCampo('count(demanda_portfolio_filho)');
			$sql->adOnde('demanda_portfolio_pai = '.(int)$demanda_id);
			$portfolio = $sql->Resultado();
			$sql->limpar();
			}
		else $portfolio = 0;
		$icone=($portfolio ? imagem('icones/portfolio_p.gif',ucfirst($config['portfolio']), 'Este é '.($config['genero_portfolio']=='a' ? 'uma' : 'um').' '.$config['portfolio'].' de demandas.') : '');


		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($demanda['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$demanda['cia_nome'].'</td></tr>';
		if ($demanda['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$demanda['responsavel'].'</td></tr>';
		if ($demanda['demanda_identificacao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$demanda['demanda_identificacao'].'</td></tr>';
		if ($demanda['demanda_justificativa']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Justificativa</b></td><td>'.$demanda['demanda_justificativa'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver esta demanda.';
		return $icone.dica('Demanda', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=demanda_ver&demanda_id='.$demanda_id.'\');">'.converte_texto_grafico($demanda['demanda_nome']).'</a>'.dicaF();
		}

function link_viabilidade($projeto_viabilidade_id){
	global $Aplic,$config;
	if (!$projeto_viabilidade_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('projeto_viabilidade');
		$sql->esqUnir('usuarios', 'usuarios', 'projeto_viabilidade_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=projeto_viabilidade_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('projeto_viabilidade_nome, projeto_viabilidade_necessidade');
		$sql->adOnde('projeto_viabilidade_id = '.$projeto_viabilidade_id);
		$projeto_viabilidade = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($projeto_viabilidade['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$projeto_viabilidade['cia_nome'].'</td></tr>';
		if ($projeto_viabilidade['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$projeto_viabilidade['responsavel'].'</td></tr>';
		if ($projeto_viabilidade['projeto_viabilidade_necessidade']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$projeto_viabilidade['projeto_viabilidade_necessidade'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este estudo de viabilidade.';
		return dica('Estudo de Viabilidade', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=viabilidade_ver&projeto_viabilidade_id='.$projeto_viabilidade_id.'\');">'.converte_texto_grafico($projeto_viabilidade['projeto_viabilidade_nome']).'</a>'.dicaF();
		}

function link_termo_abertura($projeto_abertura_id){
	global $Aplic,$config;
	if (!$projeto_abertura_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('projeto_abertura');
		$sql->esqUnir('usuarios', 'usuarios', 'projeto_abertura_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=projeto_abertura_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('projeto_abertura_nome, projeto_abertura_objetivo, projeto_abertura_justificativa');
		$sql->adOnde('projeto_abertura_id = '.$projeto_abertura_id);
		$projeto_abertura = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($projeto_abertura['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$projeto_abertura['cia_nome'].'</td></tr>';
		if ($projeto_abertura['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$projeto_abertura['responsavel'].'</td></tr>';
		if ($projeto_abertura['projeto_abertura_justificativa']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Justificativa</b></td><td>'.$projeto_abertura['projeto_abertura_justificativa'].'</td></tr>';
		if ($projeto_abertura['projeto_abertura_objetivo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Objetivo</b></td><td>'.$projeto_abertura['projeto_abertura_objetivo'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este termo de abertura.';
		return dica('Termo de Abertura', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=termo_abertura_ver&projeto_abertura_id='.$projeto_abertura_id.'\');">'.converte_texto_grafico($projeto_abertura['projeto_abertura_nome']).'</a>'.dicaF();
		}

function link_fator($pg_fator_critico_id, $tem_indicador=false, $ano=null, $inicio=null, $fim=null){
	global $Aplic,$config, $dialogo;
	if (!$pg_fator_critico_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('fatores_criticos');
		$sql->esqUnir('objetivos_estrategicos', 'objetivos_estrategicos', 'pg_fator_critico_objetivo = pg_objetivo_estrategico_id');
		$sql->esqUnir('tema', 'tema', 'pg_objetivo_estrategico_tema = tema_id');
		$sql->esqUnir('perspectivas', 'perspectivas', 'pg_objetivo_estrategico_perspectiva = pg_perspectiva_id');
		$sql->esqUnir('usuarios', 'usuarios', 'pg_fator_critico_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=pg_fator_critico_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('pg_fator_critico_nome, pg_fator_critico_descricao, pg_fator_critico_oque, pg_fator_critico_principal_indicador, pg_perspectiva_nome, tema_nome, pg_objetivo_estrategico_nome');
		$sql->adOnde('pg_fator_critico_id = '.$pg_fator_critico_id);
		$fator = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$indicador='';
		if ($tem_indicador && $fator['pg_fator_critico_principal_indicador']) $indicador=cor_indicador('fator', '', $ano, $inicio, $fim, $fator['pg_fator_critico_principal_indicador']);
		if ($fator['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$fator['cia_nome'].'</td></tr>';
		if ($fator['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$fator['responsavel'].'</td></tr>';
		if ($fator['pg_fator_critico_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$fator['pg_fator_critico_descricao'].'</td></tr>';
		if ($fator['pg_fator_critico_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$fator['pg_fator_critico_oque'].'</td></tr>';

		if ($fator['pg_objetivo_estrategico_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['objetivo']).'</b></td><td>'.$fator['pg_objetivo_estrategico_nome'].'</td></tr>';
		if ($fator['tema_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['tema']).'</b></td><td>'.$fator['tema_nome'].'</td></tr>';
		if ($fator['pg_perspectiva_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['perspectiva']).'</b></td><td>'.$fator['pg_perspectiva_nome'].'</td></tr>';


		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver '.($config['genero_fator']=='a' ? 'esta' : 'este').' '.$config['fator'].'.';
		return $indicador.dica(ucfirst($config['fator']), $dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=fator_ver&pg_fator_critico_id='.$pg_fator_critico_id.'\');">' : '').converte_texto_grafico($fator['pg_fator_critico_nome']).(!$dialogo ? '</a>' : '').dicaF();
		}


function permiteAcessarPrograma($acesso=0, $programa_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$programa_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('programa_usuario');
			$sql->adCampo('COUNT(DISTINCT programa_usuario_usuario)');
			$sql->adOnde('programa_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND programa_usuario_programa='.(int)$programa_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.$programa_id);
			$sql->adOnde('programa_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$programa_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $programa_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('programa_usuario');
			$sql->adCampo('COUNT(DISTINCT programa_usuario_usuario)');
			$sql->adOnde('programa_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND programa_usuario_programa='.(int)$programa_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.$programa_id);
			$sql->adOnde('programa_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$programa_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $programa_usuario);
			break;
		}
	return $valorRetorno;
	}



function permiteEditarPrograma($acesso=0, $programa_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$programa_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('programa_usuario');
			$sql->adCampo('COUNT(DISTINCT programa_usuario_usuario)');
			$sql->adOnde('programa_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND programa_usuario_programa='.(int)$programa_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.$programa_id);
			$sql->adOnde('programa_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$programa_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $programa_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('programa_usuario');
			$sql->adCampo('COUNT(DISTINCT programa_usuario_usuario)');
			$sql->adOnde('programa_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND programa_usuario_programa='.(int)$programa_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.$programa_id);
			$sql->adOnde('programa_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$programa_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $programa_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.$programa_id);
			$sql->adOnde('programa_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$programa_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($programa_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('programa');
			$sql->adCampo('programa_usuario');
			$sql->adOnde('programa_id = '.$programa_id);
			$sql->adOnde('programa_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$programa_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($programa_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function nome_programa($programa_id){
	if (!$programa_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('programa');
	$sql->adCampo('programa_nome');
	$sql->adOnde('programa_id = '.$programa_id);
	$programa = $sql->Resultado();
	$sql->limpar();
	return $programa;
	}

function link_programa($programa_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;

	if (!$programa_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('programa');
		$sql->esqUnir('usuarios', 'usuarios', 'programa_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=programa_cia');
		$sql->adCampo('programa_id, programa_nome, programa_descricao, programa_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('programa_id = '.$programa_id);
		$p = $sql->Linha();
		$sql->limpar();
		$indicador='';
		if ($tem_indicador && $p['programa_indicador']) $indicador=cor_indicador('programa', '', $ano, $inicio, $fim, $p['programa_indicador']);
	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';

		if ($p['programa_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['programa_descricao'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_programa']=='o' ? 'este' : 'esta').' '.$config['programa'].'.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['programa_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['programa']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['programa']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">'.converte_texto_grafico($p['programa_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['programa']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">'.converte_texto_grafico($p['programa_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['programa']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">'.converte_texto_grafico($p['programa_nome']).'</a>'.dicaF();

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('programa');
		$sql->adCampo('programa_nome');
		$sql->adOnde('programa_id = '.(int)$programa_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['programa_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">'.converte_texto_grafico($p['programa_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">'.converte_texto_grafico($p['programa_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=programa_pro_ver&programa_id='.$programa_id.'\');">' : '').converte_texto_grafico($p['programa_nome']).(!$dialogo ? '</a>' : '');
		}
	}


function permiteAcessarBeneficio($acesso=0, $beneficio_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$beneficio_id) return true;//sem pratica e acao desconsidera
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$valorRetorno = true;
			break;
		case 4:
			// protegido II
			$valorRetorno = true;
			break;
		case 2:
			// participante
			$sql->adTabela('beneficio_usuario');
			$sql->adCampo('COUNT(DISTINCT beneficio_usuario_usuario)');
			$sql->adOnde('beneficio_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND beneficio_usuario_beneficio='.(int)$beneficio_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.$beneficio_id);
			$sql->adOnde('beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$beneficio_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $beneficio_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('beneficio_usuario');
			$sql->adCampo('COUNT(DISTINCT beneficio_usuario_usuario)');
			$sql->adOnde('beneficio_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND beneficio_usuario_beneficio='.(int)$beneficio_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.$beneficio_id);
			$sql->adOnde('beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$beneficio_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $beneficio_usuario);
			break;
		}
	return $valorRetorno;
	}


function permiteEditarBeneficio($acesso=0, $beneficio_id=0) {
	global $Aplic;
	$valorRetorno = true;
	$sql = new BDConsulta;
	if ($Aplic->usuario_super_admin) return true;
	elseif (!$beneficio_id) return true;
	switch ($acesso) {
		case 0:
			// publico
			$valorRetorno = true;
			break;
		case 1:
			// protegido
			$sql->adTabela('beneficio_usuario');
			$sql->adCampo('COUNT(DISTINCT beneficio_usuario_usuario)');
			$sql->adOnde('beneficio_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND beneficio_usuario_beneficio='.(int)$beneficio_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.$beneficio_id);
			$sql->adOnde('beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$beneficio_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $beneficio_usuario);
			break;
		case 2:
			// participante
			$sql->adTabela('beneficio_usuario');
			$sql->adCampo('COUNT(DISTINCT beneficio_usuario_usuario)');
			$sql->adOnde('beneficio_usuario_usuario IN ('.$Aplic->usuario_lista_grupo.') AND beneficio_usuario_beneficio='.(int)$beneficio_id);
			$quantidade = $sql->Resultado();
			$sql->limpar();

			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.$beneficio_id);
			$sql->adOnde('beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$beneficio_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($quantidade > 0 || $beneficio_usuario);
			break;
		case 3:
			// privado
			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.$beneficio_id);
			$sql->adOnde('beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$beneficio_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($beneficio_usuario);
			break;
		case 4:
			// protegido II
			$sql->adTabela('beneficio');
			$sql->adCampo('beneficio_usuario');
			$sql->adOnde('beneficio_id = '.$beneficio_id);
			$sql->adOnde('beneficio_usuario IN ('.$Aplic->usuario_lista_grupo.')');
			$beneficio_usuario = $sql->Resultado();
			$sql->limpar();
			$valorRetorno = ($beneficio_usuario);
			break;
		default:
			$valorRetorno = false;
			break;
		}
	return $valorRetorno;
	}

function nome_beneficio($beneficio_id){
	if (!$beneficio_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('beneficio');
	$sql->adCampo('beneficio_nome');
	$sql->adOnde('beneficio_id = '.$beneficio_id);
	$beneficio = $sql->Resultado();
	$sql->limpar();
	return $beneficio;
	}

function link_beneficio($beneficio_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;

	if (!$beneficio_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('beneficio');
		$sql->esqUnir('usuarios', 'usuarios', 'beneficio_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=beneficio_cia');
		$sql->adCampo('beneficio_id, beneficio_nome, beneficio_descricao, beneficio_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('beneficio_id = '.$beneficio_id);
		$p = $sql->Linha();
		$sql->limpar();
		$indicador='';
		if ($tem_indicador && $p['beneficio_indicador']) $indicador=cor_indicador('beneficio', '', $ano, $inicio, $fim, $p['beneficio_indicador']);
	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['beneficio_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['beneficio_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_beneficio']=='o' ? 'este' : 'esta').' '.$config['beneficio'].'.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['beneficio_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['beneficio']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['beneficio']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">'.converte_texto_grafico($p['beneficio_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['beneficio']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">'.converte_texto_grafico($p['beneficio_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['beneficio']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">'.converte_texto_grafico($p['beneficio_nome']).'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('beneficio');
		$sql->adCampo('beneficio_nome');
		$sql->adOnde('beneficio_id = '.(int)$beneficio_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['beneficio_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">'.converte_texto_grafico($p['beneficio_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">'.converte_texto_grafico($p['beneficio_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=beneficio_pro_ver&beneficio_id='.$beneficio_id.'\');">' : '').converte_texto_grafico($p['beneficio_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_risco($risco_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;

	if (!$risco_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('risco');
		$sql->esqUnir('usuarios', 'usuarios', 'risco_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=risco_cia');
		$sql->adCampo('risco_id, risco_nome, risco_descricao, risco_acao_proposta, risco_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('risco_id = '.$risco_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';

		if ($tem_indicador && $p['risco_indicador']) $indicador=cor_indicador('risco', '', $ano, $inicio, $fim, $p['risco_indicador']);

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';

		if ($p['risco_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['risco_descricao'].'</td></tr>';
		if ($p['risco_acao_proposta']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Ação Proposta</b></td><td>'.$p['risco_acao_proposta'].'</td></tr>';


		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_risco']=='o' ? 'este' : 'esta').' '.$config['risco'].'.';


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['risco_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['risco']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['risco']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">'.converte_texto_grafico($p['risco_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['risco']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">'.converte_texto_grafico($p['risco_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['risco']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">'.converte_texto_grafico($p['risco_nome']).'</a>'.dicaF();

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('risco');
		$sql->adCampo('risco_nome');
		$sql->adOnde('risco_id = '.(int)$risco_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['risco_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">'.converte_texto_grafico($p['risco_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">'.converte_texto_grafico($p['risco_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_pro_ver&risco_id='.$risco_id.'\');">' : '').converte_texto_grafico($p['risco_nome']).(!$dialogo ? '</a>' : '');
		}
	}


function link_risco_resposta($risco_resposta_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;

	if (!$risco_resposta_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('risco_resposta');
		$sql->esqUnir('usuarios', 'usuarios', 'risco_resposta_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=risco_resposta_cia');
		$sql->adCampo('risco_resposta_id, risco_resposta_nome, risco_resposta_descricao, risco_resposta_acao_proposta, risco_resposta_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('risco_resposta_id = '.$risco_resposta_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';

		if ($tem_indicador && $p['risco_resposta_indicador']) $indicador=cor_indicador('risco_resposta', '', $ano, $inicio, $fim, $p['risco_resposta_indicador']);

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';

		if ($p['risco_resposta_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['risco_resposta_descricao'].'</td></tr>';
		if ($p['risco_resposta_acao_proposta']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Ação Proposta</b></td><td>'.$p['risco_resposta_acao_proposta'].'</td></tr>';


		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_risco_resposta']=='o' ? 'este' : 'esta').' '.$config['risco_resposta'].'.';


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['risco_resposta_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['risco_resposta']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['risco_resposta']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">'.converte_texto_grafico($p['risco_resposta_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['risco_resposta']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">'.converte_texto_grafico($p['risco_resposta_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['risco_resposta']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">'.converte_texto_grafico($p['risco_resposta_nome']).'</a>'.dicaF();

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('risco_resposta');
		$sql->adCampo('risco_resposta_nome');
		$sql->adOnde('risco_resposta_id = '.(int)$risco_resposta_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['risco_resposta_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">'.converte_texto_grafico($p['risco_resposta_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">'.converte_texto_grafico($p['risco_resposta_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=risco_resposta_pro_ver&risco_resposta_id='.$risco_resposta_id.'\');">' : '').converte_texto_grafico($p['risco_resposta_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_tgn($tgn_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null) {
	global $Aplic,$config, $dialogo;

	if (!$tgn_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('tgn');
		$sql->esqUnir('usuarios', 'usuarios', 'tgn_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=tgn_cia');
		$sql->adCampo('tgn_id, tgn_nome, tgn_descricao');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('tgn_id = '.$tgn_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['tgn_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['tgn_descricao'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_tgn']=='o' ? 'este' : 'esta').' '.$config['tgn'].'.';


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['tgn_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['tgn']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['tgn']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">'.converte_texto_grafico($p['tgn_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['tgn']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">'.converte_texto_grafico($p['tgn_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['tgn']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">'.converte_texto_grafico($p['tgn_nome']).'</a>'.dicaF();

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('tgn');
		$sql->adCampo('tgn_nome');
		$sql->adOnde('tgn_id = '.(int)$tgn_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['tgn_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">'.converte_texto_grafico($p['tgn_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">'.converte_texto_grafico($p['tgn_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tgn_pro_ver&tgn_id='.$tgn_id.'\');">' : '').converte_texto_grafico($p['tgn_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_canvas($canvas_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null) {
	global $Aplic,$config, $dialogo;

	if (!$canvas_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('canvas');
		$sql->esqUnir('usuarios', 'usuarios', 'canvas_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=canvas_cia');
		$sql->adCampo('canvas_id, canvas_nome, canvas_descricao');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('canvas_id = '.$canvas_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['canvas_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['canvas_descricao'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_canvas']=='o' ? 'este' : 'esta').' '.$config['canvas'].'.';


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['canvas_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['canvas']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['canvas']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">'.converte_texto_grafico($p['canvas_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['canvas']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">'.converte_texto_grafico($p['canvas_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['canvas']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">'.converte_texto_grafico($p['canvas_nome']).'</a>'.dicaF();

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('canvas');
		$sql->adCampo('canvas_nome');
		$sql->adOnde('canvas_id = '.(int)$canvas_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['canvas_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">'.converte_texto_grafico($p['canvas_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">'.converte_texto_grafico($p['canvas_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=canvas_pro_ver&canvas_id='.$canvas_id.'\');">' : '').converte_texto_grafico($p['canvas_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_me($me_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;

	if (!$me_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$sql = new BDConsulta;
		$sql->adTabela('me');
		$sql->esqUnir('usuarios', 'usuarios', 'me_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=me_cia');
		$sql->adCampo('me_id, me_nome, me_descricao, me_oque, me_composicao, me_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('me_id = '.$me_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';

		if ($tem_indicador && $p['me_indicador']) $indicador=cor_indicador('me', null, $ano, $inicio, $fim, $p['me_indicador']);



		$icone='';
		if ($p['me_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=me_explodir_pro&dialogo=1&me_id='.$me_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/indicador_exp_p.png','Composição de '.ucfirst($config['mes']),'Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição de '.$config['mes'].'.').'</a>';


	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';

		if ($p['me_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['me_descricao'].'</td></tr>';
		if ($p['me_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$p['me_oque'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_me']=='o' ? 'este' : 'esta').' '.$config['me'].'.';

		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['me_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['me']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['me']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">'.converte_texto_grafico($p['me_nome']).'</a>'.dicaF().$icone;
		elseif ($cor) return $indicador.dica(ucfirst($config['me']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">'.converte_texto_grafico($p['me_nome']).'</a>'.dicaF().$icone;
		else return $indicador.dica(ucfirst($config['me']),$dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">' : '').converte_texto_grafico($p['me_nome']).(!$dialogo ? '</a>' : '').dicaF().$icone;

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('me');
		$sql->adCampo('me_nome');
		$sql->adOnde('me_id = '.(int)$me_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['me_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">'.converte_texto_grafico($p['me_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">'.converte_texto_grafico($p['me_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=me_ver_pro&me_id='.$me_id.'\');">' : '').converte_texto_grafico($p['me_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_objetivo($pg_objetivo_estrategico_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;

	if (!$pg_objetivo_estrategico_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$sql = new BDConsulta;
		$sql->adTabela('objetivos_estrategicos');
		$sql->esqUnir('usuarios', 'usuarios', 'pg_objetivo_estrategico_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=pg_objetivo_estrategico_cia');
		$sql->adCampo('pg_objetivo_estrategico_id, pg_objetivo_estrategico_nome, pg_objetivo_estrategico_descricao, pg_objetivo_estrategico_oque, pg_objetivo_estrategico_composicao, pg_objetivo_estrategico_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('pg_objetivo_estrategico_id = '.$pg_objetivo_estrategico_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';

		if ($tem_indicador && $p['pg_objetivo_estrategico_indicador']) $indicador=cor_indicador('objetivo', null, $ano, $inicio, $fim, $p['pg_objetivo_estrategico_indicador']);



		$icone='';
		if ($p['pg_objetivo_estrategico_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=obj_estrategico_explodir&dialogo=1&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/indicador_exp_p.png','Composição de '.ucfirst($config['objetivos']),'Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição de '.$config['objetivos'].'.').'</a>';


	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';

		if ($p['pg_objetivo_estrategico_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['pg_objetivo_estrategico_descricao'].'</td></tr>';
		if ($p['pg_objetivo_estrategico_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$p['pg_objetivo_estrategico_oque'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_objetivo']=='o' ? 'este' : 'esta').' '.$config['objetivo'].'.';


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['pg_objetivo_estrategico_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['objetivo']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['objetivo']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">'.converte_texto_grafico($p['pg_objetivo_estrategico_nome']).'</a>'.dicaF().$icone;
		elseif ($cor) return $indicador.dica(ucfirst($config['objetivo']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">'.converte_texto_grafico($p['pg_objetivo_estrategico_nome']).'</a>'.dicaF().$icone;
		else return $indicador.dica(ucfirst($config['objetivo']),$dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">' : '').converte_texto_grafico($p['pg_objetivo_estrategico_nome']).(!$dialogo ? '</a>' : '').dicaF().$icone;

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('objetivos_estrategicos');
		$sql->adCampo('pg_objetivo_estrategico_nome');
		$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$pg_objetivo_estrategico_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['pg_objetivo_estrategico_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">'.converte_texto_grafico($p['pg_objetivo_estrategico_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">'.converte_texto_grafico($p['pg_objetivo_estrategico_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=obj_estrategico_ver&pg_objetivo_estrategico_id='.$pg_objetivo_estrategico_id.'\');">' : '').converte_texto_grafico($p['pg_objetivo_estrategico_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_monitoramento($monitoramento_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null) {
	global $Aplic,$config, $dialogo;

	if (!$monitoramento_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('monitoramento');
		$sql->esqUnir('usuarios', 'usuarios', 'monitoramento_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=monitoramento_cia');
		$sql->adCampo('monitoramento_id, monitoramento_nome, monitoramento_descricao, monitoramento_oque');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adOnde('monitoramento_id = '.$monitoramento_id);
		$p = $sql->Linha();
		$sql->limpar();

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['monitoramento_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['monitoramento_descricao'].'</td></tr>';
		if ($p['monitoramento_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$p['monitoramento_oque'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver este monitoramento.';


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['monitoramento_nome']);
		elseif ($sem_texto) return dica('Monitoramento',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">';
		elseif ($cor && $curto) return dica('Monitoramento',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">'.converte_texto_grafico($p['monitoramento_nome']).'</a>'.dicaF();
		elseif ($cor) return dica('Monitoramento',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">'.converte_texto_grafico($p['monitoramento_nome']).'</a>'.dicaF();
		else return dica('Monitoramento',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">'.converte_texto_grafico($p['monitoramento_nome']).'</a>'.dicaF();

		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('monitoramento');
		$sql->adCampo('monitoramento_nome');
		$sql->adOnde('monitoramento_id = '.(int)$monitoramento_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['monitoramento_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">'.converte_texto_grafico($p['monitoramento_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">'.converte_texto_grafico($p['monitoramento_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=monitoramento_ver_pro&monitoramento_id='.$monitoramento_id.'\');">' : '').converte_texto_grafico($p['monitoramento_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_tema($tema_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;
	if (!$tema_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$sql = new BDConsulta;
		$sql->adTabela('tema');
		$sql->esqUnir('perspectivas', 'perspectivas', 'tema_perspectiva = pg_perspectiva_id');
		$sql->esqUnir('usuarios', 'usuarios', 'tema_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=tema_cia');
		$sql->adCampo('tema_id, tema_nome, tema_descricao, tema_oque, tema_principal_indicador');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel, pg_perspectiva_nome');
		$sql->adOnde('tema_id = '.$tema_id);
		$p = $sql->Linha();
		$sql->limpar();

		$indicador='';
		if ($tem_indicador && $p['tema_principal_indicador']) $indicador=cor_indicador('tema', '', $ano, $inicio, $fim, $p['tema_principal_indicador']);

		$icone='';
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['tema_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['tema_descricao'].'</td></tr>';
		if ($p['tema_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$p['tema_oque'].'</td></tr>';
		if ($p['pg_perspectiva_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['perspectiva']).'</b></td><td>'.$p['pg_perspectiva_nome'].'</td></tr>';

		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes d'.($config['genero_tema']=='o' ? 'este' : 'esta').' '.$config['tema'].'.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($p['tema_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['tema']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['tema']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">'.converte_texto_grafico($p['tema_nome']).'</a>'.$icone.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['tema']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">'.converte_texto_grafico($p['tema_nome']).'</a>'.$icone.dicaF();
		else return $indicador.dica(ucfirst($config['tema']),$dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">' : '').converte_texto_grafico($p['tema_nome']).(!$dialogo ? '</a>' : '').$icone.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('tema');
		$sql->adCampo('tema_nome');
		$sql->adOnde('tema_id = '.(int)$tema_id);
		$p = $sql->Linha();
		$sql->limpar();
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($p['tema_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">'.converte_texto_grafico($p['tema_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">'.converte_texto_grafico($p['tema_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=tema_ver&tema_id='.$tema_id.'\');">' : '').converte_texto_grafico($p['tema_nome']).(!$dialogo ? '</a>' : '');
		}
	}

function link_indicador($pratica_indicador_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $com_popup=true, $ano=null) {
	global $Aplic, $config, $dialogo;
	
	if (!$pratica_indicador_id) return '&nbsp';
	if ($config['popup_detalhado'] && $com_popup){
		$sql = new BDConsulta;
		$sql->adTabela('pratica_indicador', 'pratica_indicador');
		$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=pratica_indicador_cia');
		$sql->esqUnir('usuarios', 'usuarios', 'pratica_indicador_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('pratica_indicador_externo, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_checklist, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_requisito_referencial, pratica_indicador_nome_curto, pratica_indicador_unidade, pratica_indicador_cia, pratica_indicador_nome, pratica_indicador_responsavel, pratica_indicador_requisito_oque, pratica_indicador_cor, pratica_indicador_unidade');

		if (!$Aplic->profissional) $sql->adCampo('pratica_indicador_projeto, pratica_indicador_tarefa, pratica_indicador_acao');
		else {
			$sql->esqUnir('pratica_indicador_gestao', 'pratica_indicador_gestao', 'pratica_indicador_gestao_indicador=pratica_indicador.pratica_indicador_id');
			$sql->adCampo('pratica_indicador_gestao_projeto AS pratica_indicador_projeto, pratica_indicador_gestao_tarefa AS pratica_indicador_tarefa, pratica_indicador_gestao_acao AS pratica_indicador_acao');
			}

		$sql->adOnde('pratica_indicador.pratica_indicador_id = '.(int)$pratica_indicador_id);
		$p = $sql->Linha();
		$sql->limpar();

		//se não existir no ano estipulado
		if (!$p['pratica_indicador_nome']){
			$sql->adTabela('pratica_indicador', 'pratica_indicador');
			$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
			$sql->esqUnir('cias', 'cias', 'cias.cia_id=pratica_indicador_cia');
			$sql->esqUnir('usuarios', 'usuarios', 'pratica_indicador_responsavel = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
			$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
			$sql->adCampo('pratica_indicador_projeto, pratica_indicador_externo, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_tarefa, pratica_indicador_acao, pratica_indicador_checklist, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_requisito_referencial, pratica_indicador_nome_curto, pratica_indicador_unidade, pratica_indicador_cia, pratica_indicador_nome, pratica_indicador_responsavel, pratica_indicador_requisito_oque, pratica_indicador_cor, pratica_indicador_unidade');
			$sql->adOnde('pratica_indicador.pratica_indicador_id = '.(int)$pratica_indicador_id);
			$p = $sql->Linha();
			$sql->limpar();
			}


		$icone='';
		if ($p['pratica_indicador_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Composição\', 830, 630, \'m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/indicador_exp_p.png','Composição de Pontução de Indicadores','Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição de pontuação de indicadores.').'</a>';
		elseif ($p['pratica_indicador_externo']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Externo\', 400, 200, \'m=praticas&a=externo_explodir_pro&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=externo_explodir_pro&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Externo\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/importar_sgbd_p.png','Externo','Clique neste ícone '.imagem('icones/importar_sgbd_p.png').' para visualizar as configurações da base de dados externa.').'</a>';
		elseif ($p['pratica_indicador_formula']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Fórmula\', 830, 630, \'m=praticas&a=formula_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=formula_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Fórmula\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/formula_p.gif','Fórmula','Clique neste ícone '.imagem('icones/formula_p.gif').' para visualizar a fórmula do indicador.').'</a>';
		elseif ($p['pratica_indicador_formula_simples']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Fórmula Simples\', 830, 630, \'m=praticas&a=formula_explodir_pro&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=formula_explodir_pro&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Fórmula Simples\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/formula2_p.png','Fórmula Simples','Clique neste ícone '.imagem('icones/formula2_p.png').' para visualizar a fórmula do indicador.').'</a>';
		elseif ($p['pratica_indicador_checklist']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\'Checklist\', 800, 600, \'m=praticas&a=checklist_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', null, window) : window.open(\'./index.php?m=praticas&a=checklist_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Checklist\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/todo_list_p.png','Checklist','Este indicador retira seus valores a partir de checklist.').'</a>';
		elseif ($p['pratica_indicador_campo_projeto']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\''.$config['projeto'].'\', 830, 630, \'m=projetos&a=ver&dialogo=1&ano='.$ano.'&projeto_id='.$p['pratica_indicador_projeto'].'\', null, window) : window.open(\'./index.php?m=projetos&a=ver&dialogo=1&ano='.$ano.'&projeto_id='.$p['pratica_indicador_projeto'].'\', \''.$config['projeto'].'\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/projeto_p.gif',ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para visualizar '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>';
		elseif ($p['pratica_indicador_campo_tarefa']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\''.$config['tarefa'].'\', 830, 630, \'m=tarefas&a=ver&dialogo=1&ano='.$ano.'&tarefa_id='.$p['pratica_indicador_tarefa'].'\', null, window) : window.open(\'./index.php?m=tarefas&a=ver&dialogo=1&ano='.$ano.'&tarefa_id='.$p['pratica_indicador_tarefa'].'\', \''.$config['tarefa'].'\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/tarefa_p.gif',ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' para visualizar '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</a>';
		elseif ($p['pratica_indicador_campo_acao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\''.$config['acao'].'\', 830, 630, \'m=praticas&a=plano_acao_ver&dialogo=1&ano='.$ano.'&plano_acao_id='.$p['pratica_indicador_acao'].'\', null, window) : window.open(\'./index.php?m=praticas&a=plano_acao_ver&dialogo=1&ano='.$ano.'&plano_acao_id='.$p['pratica_indicador_acao'].'\', \''.$config['acao'].'\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/plano_acao_p.gif',ucfirst($config['acao']),'Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para visualizar '.$config['genero_acao'].' '.$config['acao'].'.').'</a>';
	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['pratica_indicador_requisito_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Objetivos</b></td><td>'.$p['pratica_indicador_requisito_oque'].'</td></tr>';
		if ($p['pratica_indicador_requisito_referencial']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Referencial Comparativo</b></td><td>'.$p['pratica_indicador_requisito_referencial'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver este indicador.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return $p['pratica_indicador_nome'];
		elseif ($sem_texto) return dica($p['pratica_indicador_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id.'\');">';
		elseif ($cor && $curto) return dica('Indicador',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id.'\');" style="background-color:#'.$p['pratica_indicador_cor'].'; color:#'.melhorCor($p['pratica_indicador_cor']).'">'.$p['pratica_indicador_nome_curto'].'</a>'.dicaF();
		elseif ($cor) return dica('Indicador',$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id.'\');" style="background-color:#'.$p['pratica_indicador_cor'].'; color:#'.melhorCor($p['pratica_indicador_cor']).'">'.$p['pratica_indicador_nome'].'</a>'.dicaF().$icone;
		else return dica('Indicador', $dentro,'','',true).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&pratica_indicador_id='.$pratica_indicador_id.'\');">' : '').$p['pratica_indicador_nome'].(!$dialogo ? '</a>' : '').dicaF().$icone;
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('pratica_indicador');
		$sql->adCampo('pratica_indicador_projeto, pratica_indicador_campo_projeto, pratica_indicador_campo_tarefa, pratica_indicador_campo_acao, pratica_indicador_tarefa, pratica_indicador_nome_curto, pratica_indicador_cor, pratica_indicador_nome, pratica_indicador_composicao, pratica_indicador_formula, pratica_indicador_formula_simples, pratica_indicador_checklist');
		$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
		$p = $sql->Linha();
		$sql->limpar();

		$icone='';
		if ($p['pratica_indicador_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=indicador_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Composição\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/indicador_exp_p.png','Composição de Pontução de Indicadores','Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição de pontuação de indicadores.').'</a>';
		elseif ($p['pratica_indicador_formula']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=formula_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Fórmula\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/formula_p.gif','Fórmula','Clique neste ícone '.imagem('icones/formula_p.gif').' para visualizar a fórmula do indicador.').'</a>';
		elseif ($p['pratica_indicador_formula_simples']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=formula_explodir_pro&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Fórmula Simples\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/formula2_p.png','Fórmula Simples','Clique neste ícone '.imagem('icones/formula2_p.png').' para visualizar a fórmula do indicador.').'</a>';
		elseif ($p['pratica_indicador_checklist']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=checklist_explodir&dialogo=1&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\', \'Checklist\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/todo_list_p.png','Checklist','Este indicador retira seus valores a partir de checklist.').'</a>';
		elseif ($p['pratica_indicador_campo_projeto']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=projetos&a=ver&dialogo=1&ano='.$ano.'&projeto_id='.$p['pratica_indicador_projeto'].'\', \''.$config['projeto'].'\',\'height=630,width=830,scrollbars=yes\')">'.imagem('icones/projeto_p.gif',ucfirst($config['projeto']),'Clique neste ícone '.imagem('icones/projeto_p.gif').' para visualizar '.$config['genero_projeto'].' '.$config['projeto'].'.').'</a>';
	  elseif ($p['pratica_indicador_campo_tarefa']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\''.$config['tarefa'].'\', 830, 630, \'m=tarefas&a=ver&dialogo=1&ano='.$ano.'&tarefa_id='.$p['pratica_indicador_tarefa'].'\', null, window) : window.open(\'./index.php?m=tarefas&a=ver&dialogo=1&tarefa_id='.$p['pratica_indicador_tarefa'].'\', \''.$config['tarefa'].'\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/tarefa_p.gif',ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/tarefa_p.gif').' para visualizar '.$config['genero_tarefa'].' '.$config['tarefa'].'.').'</a>';
		elseif ($p['pratica_indicador_campo_acao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:((window.parent && window.parent.gpwebApp) ?  window.parent.gpwebApp.popUp(\''.$config['acao'].'\', 830, 630, \'m=praticas&a=plano_acao_ver&dialogo=1&ano='.$ano.'&plano_acao_id='.$p['pratica_indicador_acao'].'\', null, window) : window.open(\'./index.php?m=praticas&a=plano_acao_ver&dialogo=1&plano_acao_id='.$p['pratica_indicador_acao'].'\', \''.$config['acao'].'\',\'height=630,width=830,scrollbars=yes\'))">'.imagem('icones/tarefa_p.gif',ucfirst($config['tarefa']),'Clique neste ícone '.imagem('icones/plano_acao_p.gif').' para visualizar '.$config['genero_acao'].' '.$config['acao'].'.').'</a>';


		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['pratica_indicador_nome'].'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\');" style="background-color:#'.$p['pratica_indicador_cor'].'; color:#'.melhorCor($p['pratica_indicador_cor']).'">'.$p['pratica_indicador_nome_curto'].'</a>'.$icone;
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\');" style="background-color:#'.$p['pratica_indicador_cor'].'; color:#'.melhorCor($p['pratica_indicador_cor']).'">'.$p['pratica_indicador_nome'].'</a>'.$icone;
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&ano='.$ano.'&pratica_indicador_id='.$pratica_indicador_id.'\');">' : '').$p['pratica_indicador_nome'].(!$dialogo ? '</a>' : '').$icone;
		}
	}

function link_lacuna($indicador_lacuna_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='') {
	global $Aplic,$config;
	if (!$indicador_lacuna_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$sql = new BDConsulta;
		$sql->adTabela('indicador_lacuna', 'indicador_lacuna');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=indicador_lacuna_cia');
		$sql->esqUnir('usuarios', 'usuarios', 'indicador_lacuna_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$sql->adCampo('indicador_lacuna_nome, indicador_lacuna_descricao');
		$sql->adOnde('indicador_lacuna.indicador_lacuna_id = '.(int)$indicador_lacuna_id);
		$p = $sql->Linha();
		$sql->limpar();

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		if ($p['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['responsavel'].'</td></tr>';
		if ($p['indicador_lacuna_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$p['indicador_lacuna_descricao'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes desta lacuna de indicador.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return $p['indicador_lacuna_nome'];
		elseif ($sem_texto) return dica($p['indicador_lacuna_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');">';
		elseif ($cor && $curto) return dica($p['indicador_lacuna_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');" style="background-color:#'.$p['indicador_lacuna_cor'].'; color:#'.melhorCor($p['indicador_lacuna_cor']).'">'.$p['indicador_lacuna_nome_curto'].'</a>'.dicaF();
		elseif ($cor) return dica($p['indicador_lacuna_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');" style="background-color:#'.$p['indicador_lacuna_cor'].'; color:#'.melhorCor($p['indicador_lacuna_cor']).'">'.$p['indicador_lacuna_nome'].'</a>'.dicaF();
		else return dica($p['indicador_lacuna_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');">'.$p['indicador_lacuna_nome'].'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('indicador_lacuna');
		$sql->adCampo('indicador_lacuna_cor, indicador_lacuna_nome');
		$sql->adOnde('indicador_lacuna_id = '.(int)$indicador_lacuna_id);
		$p = $sql->Linha();
		$sql->limpar();


		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['indicador_lacuna_nome'].'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');" style="background-color:#'.$p['indicador_lacuna_cor'].'; color:#'.melhorCor($p['indicador_lacuna_cor']).'">'.$p['indicador_lacuna_nome_curto'].'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');" style="background-color:#'.$p['indicador_lacuna_cor'].'; color:#'.melhorCor($p['indicador_lacuna_cor']).'">'.$p['indicador_lacuna_nome'].'</a>';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=lacuna_ver&indicador_lacuna_id='.$indicador_lacuna_id.'\');">'.$p['indicador_lacuna_nome'].'</a>';
		}
	}

function link_projeto($projeto_id, $cor=false, $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $dialogo;
	if (!$projeto_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$horas_trabalhadas = ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->esqUnir('usuarios', 'usuarios', 'projeto_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = projeto_cia');
		$sql->adCampo('projeto_principal_indicador, projeto_portfolio, cia_nome,projeto_nome_curto,projeto_id,projeto_descricao,projeto_objetivos,projeto_data_inicio,projeto_data_fim, projeto_cor, projeto_nome, projeto_percentagem, projeto_social_acao, projeto_escopo, projeto_objetivo');
		$sql->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adOnde('projeto_id = '.(int)$projeto_id);
		$p = $sql->Linha();
		$sql->limpar();

		if (!$p) return ucfirst($config['projeto']).' com ID '.$projeto_id.' não existe!';


		$indicador='';
		if ($tem_indicador && $p['projeto_principal_indicador']) $indicador=cor_indicador('projeto', '', $ano, $inicio, $fim, $p['projeto_principal_indicador']);



	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	  if ($p['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$p['cia_nome'].'</td></tr>';
		$inicio = false;
		if(!$p['projeto_nome'])$p['projeto_nome']='Sem nome '.$p['projeto_id'];
		$icone=($p['projeto_portfolio'] ? imagem('icones/portfolio_p.gif',ucfirst($config['portfolio']), 'Este é '.($config['genero_portfolio']=='a' ? 'uma' : 'um').' '.$config['portfolio'].' de '.$config['projeto'].'.') : '').($p['projeto_social_acao'] ? imagem('../../../modulos/social/imagens/social_p.gif','Ação Social', 'Est'.($config['genero_projeto']=='a' ?  'a' : 'e').' '.$config['projeto'].' é referente a uma ação social.') : '');
		if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['gerente']).'</b></td><td>'.$p['dono'].'</td></tr>';

		$dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Completado</b></td><td>'.number_format($p['projeto_percentagem'], 2, ',', '.').'%</td></tr>';
		if ($p['projeto_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$p['projeto_descricao'].'</td></tr>';
		if ($p['projeto_objetivos']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Por Que</b></td><td>'.$p['projeto_objetivos'].'</td></tr>';
		if ($p['projeto_objetivo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Objetivo</b></td><td>'.$p['projeto_objetivo'].'</td></tr>';
		if ($p['projeto_escopo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Escopo</b></td><td>'.$p['projeto_escopo'].'</td></tr>';
		
		
		
		
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver os detalhes '.($config['genero_projeto']=='o' ? 'deste' : 'desta').' '.$config['projeto'].'.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return $indicador.dica($p['projeto_nome'],$dentro,'','',true).'<a href="javascript:void(0);">'.$p['projeto_nome'].'</a>'.dicaF().$icone;
		elseif ($sem_texto) return dica($p['projeto_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica($p['projeto_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" style="background-color:#'.$p['projeto_cor'].'; color:#'.melhorCor($p['projeto_cor']).'">'.$p['projeto_nome'].'</a>'.dicaF().$icone;
		elseif ($cor) return $indicador.dica($p['projeto_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" style="background-color:#'.$p['projeto_cor'].'; color:#'.melhorCor($p['projeto_cor']).'">'.$p['projeto_nome'].'</a>'.dicaF().$icone;
		else return $indicador.dica($p['projeto_nome'],$dentro,'','',true).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');">' : '').$p['projeto_nome'].(!$dialogo ? '</a>' : '').dicaF().$icone;
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('projetos');
		$sql->adCampo('projeto_principal_indicador,projeto_portfolio, projeto_nome_curto, projeto_cor, projeto_nome, projeto_social_acao');
		$sql->adOnde('projeto_id = '.(int)$projeto_id);
		$p = $sql->Linha();
		$sql->limpar();


		$indicador='';
		if ($tem_indicador && $p['projeto_principal_indicador']) $indicador=cor_indicador('projeto', '', $ano, $inicio, $fim, $p['projeto_principal_indicador']);


		if (!$p) return ucfirst($config['projeto']).' com ID '.$projeto_id.' não existe!';

		$icone=($p['projeto_portfolio'] ? imagem('icones/portfolio_p.gif') : '').($p['projeto_social_acao'] ? imagem('../../../modulos/social/imagens/social_p.gif') : '');
		if ($so_texto) return '';
		elseif ($sem_link) return '<a href="javascript:void(0);">'.$p['projeto_nome'].'</a>'.$icone;
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');">';
		elseif ($cor && $curto) return $indicador.'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" style="background-color:#'.$p['projeto_cor'].'; color:#'.melhorCor($p['projeto_cor']).'">'.$p['projeto_nome'].'</a>'.$icone;
		elseif ($cor) return $indicador.'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');" style="background-color:#'.$p['projeto_cor'].'; color:#'.melhorCor($p['projeto_cor']).'">'.$p['projeto_nome'].'</a>'.$icone;
		else return $indicador.(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=ver&projeto_id='.$projeto_id.'\');">' : '').$p['projeto_nome'].(!$dialogo ? '</a>' : '').$icone;
		}
	}

function link_pratica($pratica_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null) {
	global $Aplic,$config, $ano, $dialogo;
	if (!$pratica_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('praticas');
		$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id = praticas.pratica_id');
		$sql->esqUnir('usuarios', 'usuarios', 'pratica_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = pratica_cia');
		$sql->adCampo('cia_nome,pratica_nome,praticas.pratica_id, pratica_principal_indicador, pratica_oque, pratica_cor, pratica_composicao, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adOnde('praticas.pratica_id = '.(int)$pratica_id);
		if ($ano) $sql->adOnde('ano = '.(int)$ano);
		$p = $sql->Linha();
		$sql->limpar();

		if (!$p) return ucfirst($config['pratica']).' com ID '.$pratica_id.' não existe!';


		$indicador='';
		if ($tem_indicador && $p['pratica_principal_indicador']) $indicador=cor_indicador('pratica', '', $ano, $inicio, $fim, $p['pratica_principal_indicador']);

		$icone='';
		if ($p['pratica_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=pratica_explodir&dialogo=1&pratica_id='.$pratica_id.'\', \'Composição\',\'height=630,width=830,scrollbars=no\')">'.imagem('icones/indicador_exp_p.png','Composição d'.$config['genero_pratica'].' '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição d'.$config['genero_pratica'].' '.$config['pratica'].'.').'</a>';

	  $dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	 	if ($p['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$p['dono'].'</td></tr>';
		if ($p['pratica_oque']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>O que</b></td><td>'.$p['pratica_oque'].'</td></tr>';
		$dentro .= '</table>';
		if (!$so_texto && !$sem_link) $dentro .= '<br>Clique para ver '.($config['genero_pratica']=='a' ? 'esta' : 'este').' '.$config['pratica'].'.';
		if ($so_texto) return $dentro;
		elseif ($sem_link) return dica($p['pratica_nome'],$dentro,'','',true).'<a href="javascript:void(0);">'.$p['pratica_nome'].$icone.$indicado.'</a>'.dicaF();
		elseif ($sem_texto) return dica($p['pratica_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica($p['pratica_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');" style="background-color:#'.$p['pratica_cor'].'; color:#'.melhorCor($p['pratica_cor']).'">'.$p['pratica_nome'].$icone.'</a>'.dicaF();
		elseif ($cor) return $indicador.dica($p['pratica_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');" style="background-color:#'.$p['pratica_cor'].'; color:#'.melhorCor($p['pratica_cor']).'">'.$p['pratica_nome'].$icone.'</a>'.dicaF();
		else return $indicador.dica($p['pratica_nome'],$dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');">'.$p['pratica_nome'].$icone.'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('praticas');
		$sql->adCampo('pratica_cor, pratica_nome, pratica_principal_indicador, pratica_composicao');
		$sql->adOnde('pratica_id = '.(int)$pratica_id);
		$p = $sql->Linha();
		$sql->limpar();

		if (!$p) return ucfirst($config['pratica']).' com ID '.$pratica_id.' não existe!';

		$indicador='';
		if ($tem_indicador && $p['pratica_principal_indicador']) $indicador=cor_indicador('pratica', '', $ano, $inicio, $fim, $p['pratica_principal_indicador']);

		$icone='';
		if ($p['pratica_composicao']) $icone='&nbsp;<a href="javascript: void(0);" onclick="javascript:window.open(\'./index.php?m=praticas&a=pratica_explodir&dialogo=1&pratica_id='.$pratica_id.'\', \'Composição\',\'height=630,width=830,scrollbars=no\')">'.imagem('icones/indicador_exp_p.png','Composição d'.$config['genero_pratica'].' '.ucfirst($config['pratica']),'Clique neste ícone '.imagem('icones/indicador_exp_p.png').' para visualizar a composição d'.$config['genero_pratica'].' '.$config['pratica'].'.').'</a>';



		if ($so_texto) return '';
		elseif ($sem_link) return $indicador.'<a href="javascript:void(0);">'.$p['pratica_nome'].'</a>'.$icone;
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');">';
		elseif ($cor && $curto) return $indicador.'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');" style="background-color:#'.$p['pratica_cor'].'; color:#'.melhorCor($p['pratica_cor']).'">'.$p['pratica_nome'].$icone.'</a>';
		elseif ($cor) return $indicador.'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');" style="background-color:#'.$p['pratica_cor'].'; color:#'.melhorCor($p['pratica_cor']).'">'.$p['pratica_nome'].$icone.'</a>';
		else return $indicador.(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=pratica_ver&pratica_id='.$pratica_id.'\');">' : '').$p['pratica_nome'].(!$dialogo ? '</a>' : '').$icone;
		}
	}


function link_calendario($calendario_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null){
	global $Aplic,$config, $dialogo;
	if (!$calendario_id) return '&nbsp';
	$sql = new BDConsulta;
	if ($config['popup_detalhado'] && !$dialogo){
		$sql->adTabela('calendario');
		$sql->esqUnir('usuarios', 'usuarios', 'calendario_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = calendario_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adCampo('calendario_descricao, calendario_nome');
		$sql->adOnde('calendario_id = '.$calendario_id);
		$calendario = $sql->Linha();
		$sql->limpar();


		if (!$calendario) return 'Agenda coletiva com ID '.$calendario_id.' não existe!';
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($calendario['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$calendario['dono'].'</td></tr>';
		if ($calendario['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$calendario['cia_nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver esta agenda coletiva.';

		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($calendario['calendario_nome']);
		elseif ($sem_texto) return dica('Agenda Coletiva',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">';
		elseif ($cor && $curto) return dica('Agenda Coletiva',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">'.converte_texto_grafico($calendario['calendario_nome']).'</a>'.dicaF();
		elseif ($cor) return dica('Agenda Coletiva',$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">'.converte_texto_grafico($calendario['calendario_nome']).'</a>'.dicaF();
		else return dica('Agenda Coletiva', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">'.$calendario['calendario_nome'].'</a>'.dicaF();

		if (!$calendario) return 'Calendário com ID '.$calendario_id.' não existe!';
		return $calendario;
		}
	else{
		$sql->adTabela('calendario');
		$sql->adCampo('calendario_nome');
		$sql->adOnde('calendario_id = '.$calendario_id);
		$calendario = $sql->Linha();
		$sql->limpar();
		if (!$calendario) return 'Agenda coletiva com ID '.$calendario_id.' não existe!';

		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($calendario['calendario_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">'.converte_texto_grafico($calendario['calendario_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">'.converte_texto_grafico($calendario['calendario_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=sistema&u=calendario&a=calendario_ver&calendario_id='.$calendario_id.'\');">' : '').$calendario['calendario_nome'].(!$dialogo ? '</a>' : '');
		}
	}

function link_municipio($municipio_id){
	global $Aplic,$config;
	if (!$municipio_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('municipios', 'municipios');
		$sql->adCampo('municipio_nome, estado_sigla');
		$sql->adOnde('municipio_id = '.(int)$municipio_id);
		$municipio = $sql->Linha();
		$sql->limpar();
		if (!$municipio) return 'Municcípio com ID '.$municipio_id.' não existe!';
		return '<a href="javascript:void(0);" onclick="if (window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp(\'Município\', 770, 467, \'m=publico&a=coordenadas_municipios&dialogo=1&municipio_id='.$municipio_id.'\', null, window);  else window.open(\'./index.php?m=publico&a=coordenadas_municipios&dialogo=1'.$municipio_id.'\', \'Município\',\'height=467,width=770px,resizable,scrollbars=no\');">'.$municipio['municipio_nome'].'</a>';
		}



function link_meta($pg_meta_id, $tem_indicador=false, $ano=null, $inicio=null, $fim=null){
	global $Aplic,$config, $dialogo;
	if (!$pg_meta_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('metas');
		$sql->esqUnir('usuarios', 'usuarios', 'pg_meta_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = pg_meta_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adCampo('pg_meta_nome, pg_meta_principal_indicador');
		$sql->adOnde('metas.pg_meta_id = '.$pg_meta_id);
		$meta = $sql->Linha();
		$sql->limpar();
		if (!$meta) return 'Meta com ID '.$pg_meta_id.' não existe!';
		$indicador='';
		if ($tem_indicador && $meta['pg_meta_principal_indicador']) $indicador=cor_indicador('meta', '', $ano, $inicio, $fim, $meta['pg_meta_principal_indicador']);
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($meta['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$meta['dono'].'</td></tr>';
		if ($meta['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$meta['cia_nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver '.($config['genero_meta']=='a' ? 'esta' : 'este').' '.$config['meta'].'.';
		return $indicador.dica('Meta', $dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=meta_ver&pg_meta_id='.$pg_meta_id.'\');">' : '').$meta['pg_meta_nome'].(!$dialogo ? '</a>' : '').dicaF();
		}

function link_licao($licao_id){
	global $Aplic,$config, $dialogo;
	if (!$licao_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('licao');
		$sql->esqUnir('usuarios', 'usuarios', 'licao_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = licao_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adCampo('licao_nome');
		$sql->adOnde('licao.licao_id = '.$licao_id);
		$licao = $sql->Linha();
		$sql->limpar();

		if (!$licao) return 'Lição Aprendida com ID '.$licao_id.' não existe!';

		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($licao['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$licao['dono'].'</td></tr>';
		if ($licao['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$licao['cia_nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver os detalhes desta lição aprendida.';
		return dica('Lição Aprendida', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=projetos&a=licao_ver&licao_id='.$licao_id.'\');">'.$licao['licao_nome'].'</a>'.dicaF();
		}

function link_avaliacao($avaliacao_id){
	global $Aplic,$config;
	if (!$avaliacao_id) return '&nbsp';
		$sql = new BDConsulta;
		$sql->adTabela('avaliacao');
		$sql->esqUnir('usuarios', 'usuarios', 'avaliacao_responsavel = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = avaliacao_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adCampo('avaliacao_nome');
		$sql->adOnde('avaliacao.avaliacao_id = '.$avaliacao_id);
		$avaliacao = $sql->Linha();
		$sql->limpar();

		if (!$avaliacao) return 'Avaliação com ID '.$avaliacao_id.' não existe!';

		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($avaliacao['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$avaliacao['dono'].'</td></tr>';
		if ($avaliacao['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$avaliacao['cia_nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver esta avaliação.';
		return dica('Avaliação', $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=avaliacao_ver&avaliacao_id='.$avaliacao_id.'\');">'.$avaliacao['avaliacao_nome'].'</a>'.dicaF();
		}

function link_perspectiva($pg_perspectiva_id, $cor='', $curto='', $sem_texto='', $so_texto='', $sem_link='', $tem_indicador=false, $ano=null, $inicio=null, $fim=null){
	global $Aplic,$config, $dialogo;
	if (!$pg_perspectiva_id) return '&nbsp';
	$sql = new BDConsulta;
	if ($config['popup_detalhado']){
		$sql->adTabela('perspectivas');
		$sql->esqUnir('usuarios', 'usuarios', 'pg_perspectiva_usuario = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$sql->esqUnir('cias', 'com', 'cia_id = pg_perspectiva_cia');
		$sql->adCampo('cia_nome, concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS dono');
		$sql->adCampo('pg_perspectiva_descricao, pg_perspectiva_nome');
		$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
		$perspectiva = $sql->Linha();
		$sql->limpar();

		$indicador='';
		if ($tem_indicador && $perspectiva['pg_perspectiva_indicador_principal']) $indicador=cor_indicador('perspectiva', '', $ano, $inicio, $fim, $p['pg_perspectiva_indicador_principal']);


		if (!$perspectiva) return ucfirst($config['perspectivas']).' com ID '.$pg_perspectiva_id.' não existe!';
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($perspectiva['dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$perspectiva['dono'].'</td></tr>';
		if ($perspectiva['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$perspectiva['cia_nome'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver '.($config['genero_perspectiva']=='a' ? 'esta' : 'este').' '.$config['perspectiva'];


		if ($so_texto) return $dentro;
		elseif ($sem_link) return converte_texto_grafico($perspectiva['pg_perspectiva_nome']);
		elseif ($sem_texto) return dica(ucfirst($config['perspectiva']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">';
		elseif ($cor && $curto) return $indicador.dica(ucfirst($config['perspectiva']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">'.converte_texto_grafico($perspectiva['pg_perspectiva_nome']).'</a>'.dicaF();
		elseif ($cor) return $indicador.dica(ucfirst($config['perspectiva']),$dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">'.converte_texto_grafico($perspectiva['pg_perspectiva_nome']).'</a>'.dicaF();
		else return $indicador.dica(ucfirst($config['perspectiva']), $dentro).(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">' : '').$perspectiva['pg_perspectiva_nome'].(!$dialogo ? '</a>' : '').dicaF();
		}
	else{
		$sql->adTabela('perspectivas');
		$sql->adCampo('pg_perspectiva_nome');
		$sql->adOnde('pg_perspectiva_id = '.$pg_perspectiva_id);
		$perspectiva = $sql->Linha();
		$sql->limpar();
		if (!$perspectiva) return ucfirst($config['perspectivas']).' com ID '.$pg_perspectiva_id.' não existe!';

		if ($sem_link) return '<a href="javascript:void(0);">'.converte_texto_grafico($perspectiva['pg_perspectiva_nome']).'</a>';
		elseif ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">';
		elseif ($cor && $curto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">'.converte_texto_grafico($perspectiva['pg_perspectiva_nome']).'</a>';
		elseif ($cor) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">'.converte_texto_grafico($perspectiva['pg_perspectiva_nome']).'</a>';
		else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=perspectiva_ver&pg_perspectiva_id='.$pg_perspectiva_id.'\');">' : '').$perspectiva['pg_perspectiva_nome'].(!$dialogo ? '</a>' : '');
		}
	}

function link_cia($cia_id, $sem_texto='', $nome_completo=false){
	global $Aplic,$config, $dialogo;
	if (!$cia_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('cias');
		$sql->esqUnir('contatos','contatos','contatos.contato_id=cias.cia_responsavel');
		$sql->esqUnir('municipios','municipios','municipio_id=cia_cidade');
		$sql->adCampo('cia_nome, cia_nome_completo, cia_descricao, cia_tel1, cia_fax, cia_endereco1, cia_endereco2, municipio_nome, cia_estado, cia_pais, cia_email, cia_url, cia_acesso');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_responsavel');
		$sql->adOnde('cia_id = '.(int)$cia_id);
		$cia_nome = $sql->Linha();
		$sql->limpar();

		if (!$cia_nome) return $config['genero_organizacao'].' com ID '.$cia_id.' não existe!';

		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if (isset($cia_nome['nome_responsavel']) && $cia_nome['nome_responsavel']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$cia_nome['nome_responsavel'].'</td></tr>';
		if (isset($cia_nome['cia_tel1']) && $cia_nome['cia_tel1']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Telefone</b></td><td>'.$cia_nome['cia_tel1'].'</td></tr>';
		if (isset($cia_nome['cia_fax']) && $cia_nome['cia_fax']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Fax</b></td><td>'.$cia_nome['cia_fax'].'</td></tr>';
		if (isset($cia_nome['cia_email']) && $cia_nome['cia_email']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>E-mail</b></td><td>'.$cia_nome['cia_email'].'</td></tr>';
		if (isset($cia_nome['cia_endereco1']) && $cia_nome['cia_endereco1']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Endereço</b></td><td>'.$cia_nome['cia_endereco1'].((isset($cia_nome['cia_endereco2'])) ? '<br />'.$cia_nome['cia_endereco2'] : '').'<br />'.(isset($cia_nome['municipio_nome']) ? $cia_nome['municipio_nome']:'').'&nbsp;&nbsp;'.(isset($cia_nome['cia_estado']) ? $cia_nome['cia_estado']:''). '&nbsp;&nbsp;' .(isset($cia_nome['cia_cep']) ? $cia_nome['cia_cep']:'').((isset($cia_nome['cia_pais'])) ? '<br />'.$cia_nome['cia_pais'] : '').'</td></tr>';
		if (isset($cia_nome['cia_url']) && $cia_nome['cia_url']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Página Web</b></td><td>'.$cia_nome['cia_url'].'</td></tr>';
		if (isset($cia_nome['cia_descricao']) && $cia_nome['cia_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$cia_nome['cia_descricao'].'</td></tr>';
		$dentro .= '</table>';

		$permite=permiteAcessarCia($cia_id, $cia_nome['cia_acesso']);
		if ($permite){
			$dentro .= '<br>Clique para ver os detalhes dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.';
			if ($sem_texto) return dica($cia_nome['cia_nome_completo'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=cias&a=ver&tab=0&cia_id='.(int)$cia_id.'\');">';
			else return dica($cia_nome['cia_nome_completo'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=cias&a=ver&tab=0&cia_id='.(int)$cia_id.'\');">'.($nome_completo ? $cia_nome['cia_nome_completo'] : $cia_nome['cia_nome']).'</a>'.dicaF();
			}
		else return dica($cia_nome['cia_nome_completo'], $dentro,'','',true).($nome_completo ? $cia_nome['cia_nome_completo'] : $cia_nome['cia_nome']).dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('cias');
		$sql->adCampo('cia_nome, cia_acesso');
		$sql->adOnde('cia_id = '.(int)$cia_id);
		$cia_nome = $sql->Linha();
		$sql->limpar();
		if (!$cia_nome) return $config['genero_organizacao'].' com ID '.$cia_id.' não existe!';
		$permite=permiteAcessarCia($cia_id, $cia_nome['cia_acesso']);
		if ($permite){
			if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=cias&a=ver&tab=0&cia_id='.(int)$cia_id.'\');">';
			else return (!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=cias&a=ver&tab=0&cia_id='.(int)$cia_id.'\');">' : '').($nome_completo && isset($cia_nome['cia_nome_completo'])? $cia_nome['cia_nome_completo'] : (isset($cia_nome['cia_nome']) ? $cia_nome['cia_nome'] : 'falta nome na organização')).(!$dialogo ? '</a>' : '');
			}
		else return ($nome_completo ? $cia_nome['cia_nome_completo'] : $cia_nome['cia_nome']);
		}
	}

function link_secao($dept_id, $sem_texto=''){
	global $Aplic,$config, $dialogo;
	if (!$dept_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;

		$sql->adTabela('depts');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id=depts.dept_cia');
		$sql->esqUnir('contatos','contatos','contatos.contato_id=depts.dept_responsavel');
		$sql->adCampo('cia_nome,dept_id, dept_tel, dept_nome, dept_descricao, dept_fax, dept_endereco1 , dept_endereco2, dept_cidade, dept_estado, dept_cep, dept_url, dept_pais, dept_email, dept_acesso');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS responsavel');
		$sql->adOnde('dept_id = '.(int)$dept_id);
		$secao = $sql->Linha();
		$sql->limpar();

		if (!$secao) return $config['departamento'].' com ID '.$dept_id.' não existe!';

		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($secao['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$secao['cia_nome'].'</td></tr>';
		if ($secao['responsavel']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$secao['responsavel'].'</td></tr>';
		if ($secao['dept_tel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Telefone</b></td><td>'.$secao['dept_tel'].'</td></tr>';
		if ($secao['dept_fax']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Fax</b></td><td>'.$secao['dept_fax'].'</td></tr>';
		if ($secao['dept_email']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>E-mail</b></td><td>'.$secao['dept_email'].'</td></tr>';
		if ($secao['dept_endereco1']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Endereço</b></td><td>'.$secao['dept_endereco1'].(($secao['dept_endereco2']) ? '<br />'.$secao['dept_endereco2'] : '').'<br />'.$secao['dept_cidade'].'&nbsp;&nbsp;'.$secao['dept_estado'].'&nbsp;&nbsp;'.$secao['dept_cep'].(($secao['dept_pais']) ? '<br />'.$secao['dept_pais'] : '').'</td></tr>';
		if ($secao['dept_url']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Página Web</b></td><td>'.$secao['dept_url'].'</td></tr>';
		if ($secao['dept_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$secao['dept_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$permite=permiteAcessarDept($secao['dept_acesso'], $dept_id);
		if ($permite) {
			$dentro .= '<br>Clique para ver os detalhes '.($config['genero_dept']=='o' ? 'deste ': 'desta ').$config['departamento'].'.';
			if ($sem_texto) return dica($secao['dept_nome'], $dentro,'','').'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=depts&a=ver&tab=1&dept_id='.$dept_id.'\');">';
			else return dica($secao['dept_nome'], $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ? -1 : 0) : 0).', \'m=depts&a=ver&tab=1&dept_id='.$dept_id.'\');">'.$secao['dept_nome'].'</a>'.dicaF();
			}
		else return dica($secao['dept_nome'], $dentro).$secao['dept_nome'].dicaF();
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('depts');
		$sql->adCampo('dept_nome, dept_acesso');
		$sql->adOnde('dept_id = '.(int)$dept_id);
		$secao = $sql->Linha();
		$sql->limpar();

		if (!$secao) return $config['departamento'].' com ID '.$dept_id.' não existe!';

		$permite=permiteAcessarDept($secao['dept_acesso'], $dept_id);
		if ($permite) {
			if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=depts&a=ver&tab=1&dept_id='.$dept_id.'\');">';
			else return ($dialogo ? '' : '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=depts&a=ver&tab=1&dept_id='.$dept_id.'\');">').$secao['dept_nome'].($dialogo ? '' : '</a>');
			}
		else return $secao['dept_nome'];
		}
	}

function link_contato($contato_id, $extra='', $login=false, $email='', $sem_texto=''){
	global $Aplic,$config, $dialogo;
	if (!$contato_id) return '&nbsp';
	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->esqUnir('usuarios', '', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->esqUnir('depts', '', 'contato_dept = dept_id');
		$sql->esqUnir('cias', '', 'contato_cia = cia_id');
		$sql->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, contato_cia, cia_nome, dept_nome, contato_tel, contato_cel, contato_fax, contato_email, contato_email2, contato_arma, contato_funcao, usuario_login, contato_privado, contato_dono');
		$sql->adOnde('contatos.contato_id = '.(int)$contato_id);
		$usuario = $sql->Linha();
		$sql->limpar();

		if (!$usuario) return 'Contato com ID '.$contato_id.' não existe!';

		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($usuario['cia_nome']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$usuario['cia_nome'].'</td></tr>';
		if ($usuario['dept_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['departamento'].'</b></td><td>'.$usuario['dept_nome'].'</td></tr>';
		if ($usuario['contato_funcao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Função</b></td><td>'.$usuario['contato_funcao'].'</td></tr>';
		if ($usuario['contato_tel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Telefone</b></td><td>'.$usuario['contato_tel'].'</td></tr>';
		if ($usuario['contato_cel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Celular</b></td><td>'.$usuario['contato_cel'].'</td></tr>';
		if ($usuario['contato_fax']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Fax</b></td><td>'.$usuario['contato_fax'].'</td></tr>';
		if ($usuario['contato_email']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>E-mail</b></td><td>'.$usuario['contato_email'].'</td></tr>';
		if ($usuario['contato_email2']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>E-mail</b></td><td>'.$usuario['contato_email2'].'</td></tr>';
		if ($extra) $dentro .=$extra;
		$dentro .= '</table>';

		if ($usuario['usuario_id']) $permite=($usuario['usuario_id']==$Aplic->usuario_id || $Aplic->checarModulo('admin', 'acesso'));
		elseif ($usuario['contato_privado'] || $usuario['contato_dono']==$Aplic->usuario_id) $permite=true;
		elseif (!$usuario['contato_privado'])  $permite=$Aplic->checarModulo('contatos', 'acesso');
		else $permite=false;

		$texto_email='';
		if ($email && $usuario['nome']){
			$icone_mail='email'.((isset($usuario['contato_email']) && $usuario['contato_email']) || (isset($usuario['contato_email2']) && $usuario['contato_email2'])? '' : '2').'.gif';
			$texto_email=dica('E-Mail', 'Clique neste ícone '.imagem('icones/'.$icone_mail).' para enviar um E-mail.').'<a '.envia_email_externo($contato_id).'>'.imagem('icones/'.$icone_mail).'</a>'.dicaF();
			}
		else if ($email) $texto_email=imagem('icones/vazio16.gif');
		if ($permite) {
			$dentro .= '<br>Clique para ver este contato.';
			if ($sem_texto) return dica($usuario['nome'].($usuario['contato_arma'] ? ' - '.$usuario['contato_arma'] : ''), $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=contatos&a=ver&contato_id='.$contato_id.'\');">';
			else return ($email=='esquerda' ? $texto_email : '').dica($usuario['nome'].($usuario['contato_arma'] ? ' - '.$usuario['contato_arma'] : ''), $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=contatos&a=ver&contato_id='.$contato_id.'\');">'.($login ? $usuario['usuario_login'] : $usuario['nome']).'</a></span>'.($email=='direita' ? $texto_email : '');
			}
		else return ($email=='esquerda' ? $texto_email : '').dica($usuario['nome'].($usuario['contato_arma'] ? ' - '.$usuario['contato_arma'] : ''), $dentro,'','',true).($email=='esquerda' ? $texto_email : '').($login ? $usuario['usuario_login'] : $usuario['nome']).'</span>'.($email=='direita' ? $texto_email : '');
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->esqUnir('usuarios','usuarios', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, usuario_login, contato_email, usuario_id, contato_privado, contato_dono');
		$sql->adOnde('contatos.contato_id = '.(int)$contato_id);
		$usuario = $sql->Linha();
		$sql->limpar();

		if (!$usuario) return 'Contato com ID '.$contato_id.' não existe!';

		if ($usuario['usuario_id']) $permite=($usuario['usuario_id']==$Aplic->usuario_id || $Aplic->checarModulo('admin', 'acesso'));
		elseif ($usuario['contato_privado'] || $usuario['contato_dono']==$Aplic->usuario_id) $permite=true;
		elseif (!$usuario['contato_privado'])  $permite=$Aplic->checarModulo('contatos', 'acesso');
		else $permite=false;

		$texto_email='';
		if ($email && $usuario['contato_email'] && $usuario['nome']){
			$icone_mail='email'.((isset($usuario['contato_email']) && $usuario['contato_email']) || (isset($usuario['contato_email2']) && $usuario['contato_email2'])? '' : '2').'.gif';
			$texto_email=dica('E-Mail', 'Clique neste ícone '.imagem('icones/'.$icone_mail).' para enviar um E-mail.').'<a '.envia_email_externo($contato_id).'>'.imagem('icones/'.$icone_mail).'</a>'.dicaF();
			}
		if ($permite) {
			if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=contatos&a=ver&contato_id='.$contato_id.'\');">';
			else return ($email=='esquerda' && !$dialogo ? $texto_email : '').(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=contatos&a=ver&contato_id='.$contato_id.'\');">' : '').($login ? $usuario['usuario_login'] : $usuario['nome']).(!$dialogo ? '</a>' : '').($email=='direita' ? $texto_email : '');
			}
		else return ($email=='esquerda' ? $texto_email : '').($login ? $usuario['usuario_login'] : $usuario['nome']).($email=='direita' ? $texto_email : '');
		}
	}

function link_email($email='', $contato_id=0, $usuario_id=0){
	global $config;
	$texto_email='&nbsp;';
	if ($email){
		$icone_mail='email'.($email ? '' : '2').'.gif';
		$texto_email=dica('E-Mail', 'Clique neste ícone '.imagem('icones/'.$icone_mail).' para enviar um E-mail.').'<a '.envia_email_externo($contato_id, $usuario_id).'>'.imagem('icones/'.$icone_mail).'</a>'.dicaF();
		}
	return $texto_email.$email;
	}

function link_usuario($usuario_id, $extra='', $login=false, $email='', $sem_texto='', $mostrar_cia=false, $popup=true){
	global $Aplic,$config, $dialogo;
	if (!$usuario_id) return '&nbsp;';

	if ($config['popup_detalhado'] && !$dialogo){
		$sql = new BDConsulta;
		$sql->adTabela('usuarios');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
		$sql->esqUnir('depts', '', 'contato_dept = dept_id');
		$sql->adCampo('usuario_grupo_dept, contato_id, contato_cia, cia_nome, dept_nome, contato_tel, contato_cel, contato_fax, contato_email, contato_arma, contato_funcao, usuario_login, contato_nomeguerra');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome');
		$sql->adOnde('usuarios.usuario_id = '.(int)$usuario_id);
		$usuario = $sql->Linha();
		$sql->limpar();

		if (!$usuario) return ucfirst($config['usuario']).' com ID '.$usuario_id.' não existe!';

		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';

		if ($usuario['usuario_grupo_dept']){
			$dentro .= '<tr><td valign="top" colspan="2"><b>Conta de grupo</b></td></tr>';
			$conta_dept=imagem('icones/membros_p.png', 'Conta de Grupo', 'Esta é uma conta conta de grupo.');
			}
		else {
			$conta_dept='';
			if ($usuario['cia_nome'] && $usuario['contato_cia']) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$usuario['cia_nome'].'</td></tr>';
			if ($usuario['dept_nome'] && $usuario['contato_cia']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.$config['departamento'].'</b></td><td>'.$usuario['dept_nome'].'</td></tr>';
			if ($usuario['contato_funcao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Função</b></td><td>'.$usuario['contato_funcao'].'</td></tr>';
			if ($usuario['contato_tel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Telefone</b></td><td>'.$usuario['contato_tel'].'</td></tr>';
			if ($usuario['contato_cel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Celular</b></td><td>'.$usuario['contato_cel'].'</td></tr>';
			if ($usuario['contato_fax']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Fax</b></td><td>'.$usuario['contato_fax'].'</td></tr>';
			if ($usuario['contato_email']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>E-mail</b></td><td>'.$usuario['contato_email'].'</td></tr>';
			if ($extra) $dentro .=$extra;
			}
		$dentro .= '</table>';

		$permite=($usuario_id==$Aplic->usuario_id || $Aplic->checarModulo('admin', 'acesso'));
		$texto_email='';

		if ($email && $usuario['nome']){
			$icone_mail='email'.((isset($usuario['contato_email']) && $usuario['contato_email']) || (isset($usuario['contato_email2']) && $usuario['contato_email2'])? '' : '2').'.gif';
			$texto_email=dica('E-Mail', 'Clique neste ícone '.imagem('icones/'.$icone_mail).' para enviar um E-mail.').'<a '.envia_email_externo('', $usuario_id).'>'.imagem('icones/'.$icone_mail).'</a>'.dicaF();
			}
		else if ($email) $texto_email=imagem('icones/vazio16.gif');


		if ($permite && !$usuario['usuario_grupo_dept']) {
			$dentro .= '<br>Clique para ver '.($config['genero_usuario']=='a' ? 'esta' : 'este').' '.$config['usuario'].'.';
			if ($sem_texto) return dica($usuario['nome'].($usuario['contato_arma'] ? ' - '.$usuario['contato_arma'] : ''), $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=admin&a=ver_usuario&tab=3&usuario_id='.$usuario_id.'\');">';
			else return ($email=='esquerda' ? $texto_email : '').dica($usuario['nome'].($usuario['contato_arma'] ? ' - '.$usuario['contato_arma'] : ''), $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=admin&a=ver_usuario&tab=3&usuario_id='.$usuario_id.'\');">'.($login ? $usuario['usuario_login'] : $usuario['nome']).($mostrar_cia && $usuario['cia_nome'] ? ' - '.$usuario['cia_nome']:'').'</a></span>'.($email=='direita' ? $texto_email : '').$conta_dept;
			}

		if ($permite && $usuario['usuario_grupo_dept']) {
			$nome_dept=$usuario['contato_nomeguerra'];
			$dentro .= '<br>Clique para ver os detalhes desta conta de grupo.';
			if ($sem_texto) return dica($nome_dept, $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=admin&a=ver_usuario&tab=3&usuario_id='.$usuario_id.'\');">';
			else return ($email=='esquerda' ? $texto_email : '').dica($nome_dept, $dentro).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=admin&a=ver_usuario&tab=3&usuario_id='.$usuario_id.'\');">'.$nome_dept.'</a></span>'.($email=='direita' ? $texto_email : '').$conta_dept;
			}

		else return ($email=='esquerda' ? $texto_email : '').dica($usuario['nome'].($usuario['contato_arma'] ? ' - '.$usuario['contato_arma'] : ''), $dentro).($login ? $usuario['usuario_login'] : $usuario['nome']).($mostrar_cia && $usuario['cia_nome'] ? ' - '.$usuario['cia_nome']:'').'</span>'.($email=='direita' ? $texto_email : '').$conta_dept;
		}
	else {
		$sql = new BDConsulta;
		$sql->adTabela('contatos');
		$sql->adTabela('usuarios');
		$sql->adCampo('contato_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, usuario_login, contato_email');
		$sql->adOnde('usuarios.usuario_contato = contatos.contato_id');
		$sql->adOnde('usuarios.usuario_id = '.(int)$usuario_id);
		$usuario = $sql->Linha();
		$sql->limpar();

		if (!$usuario) return ucfirst($config['usuario']).' com ID '.$usuario_id.' não existe!';

		$permite=($usuario_id==$Aplic->usuario_id || $Aplic->checarModulo('admin', 'acesso'));
		$texto_email='';
		if ($email && $usuario['nome'] && !$dialogo){
			$icone_mail='email'.((isset($usuario['contato_email']) && $usuario['contato_email']) || (isset($usuario['contato_email2']) && $usuario['contato_email2'])? '' : '2').'.gif';
			$texto_email=dica('E-Mail', 'Clique neste ícone '.imagem('icones/'.$icone_mail).' para enviar um E-mail.').'<a '.envia_email_externo('', $usuario_id).'>'.imagem('icones/'.$icone_mail).'</a>'.dicaF();
			}
		if ($permite) {
			if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=admin&a=ver_usuario&tab=3&usuario_id='.$usuario_id.'\');">';
			else return ($email=='esquerda' ? $texto_email : '').(!$dialogo ? '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=admin&a=ver_usuario&tab=3&usuario_id='.$usuario_id.'\');">' : '').($login ? $usuario['usuario_login'] : $usuario['nome']).($mostrar_cia && $usuario['cia_nome'] ? ' - '.$usuario['cia_nome']:'').(!$dialogo ? '</a>' : '').($email=='direita' ? $texto_email : '');
			}
		else return ($email=='esquerda' ? $texto_email : '').($login ? $usuario['usuario_login'] : $usuario['nome']).($mostrar_cia && $usuario['cia_nome'] ? ' - '.$usuario['cia_nome']:'').($email=='direita' ? $texto_email : '');
		}
	}

function link_compromisso($agenda_id, $sem_texto='', $imagem=false){
	global $Aplic,$config;
	if (!$agenda_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$obj = new CAgenda();
		$obj->load($agenda_id);
		$recorrencia = array('Nunca', 'A cada hora', 'Diario', 'Semanalmente', 'Quinzenal', 'Mensal', 'Quadrimensal', 'Semestral', 'Anual');
		$designado = $obj->getDesignado();
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$data_inicio = $obj->agenda_inicio ? new CData($obj->agenda_inicio) : new CData();
		$data_fim = $obj->agenda_fim ? new CData($obj->agenda_fim) : new CData();
		$sql = new BDConsulta;
		$sql->adTabela('agenda', 'e');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = agenda_dono');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('agenda_dono, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS dono');
		$sql->adOnde('agenda_id = '.(int)$agenda_id);
		$linha = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($data_inicio) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Início</b></td><td>'.$data_inicio->format($df.' '.$tf).'</td></tr>';
		if ($data_fim) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Término</b></td><td>'.$data_fim->format($df.' '.$tf).'</td></tr>';
		if ($linha['agenda_dono'] != $Aplic->usuario_id) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$linha['dono'].'</td></tr>';
		if ($recorrencia[$obj->agenda_recorrencias]) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Recorrência</b></td><td>'.$recorrencia[$obj->agenda_recorrencias].($obj->agenda_recorrencias ? ' ('.$obj->agenda_nr_recorrencias.' vez'.((int)$obj->agenda_nr_recorrencias > 1 ? 'es':''). ')' : '').'</td></tr>';
		if (is_array($designado) && count($designado)>1) {
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Convidado'.(count($designado)>1 ? 's' :'').'</b></td><td>'.
			$inicio = false;
			foreach ($designado as $usuario) {
				if ($inicio)	$dentro .= '<br/>';
				else $inicio = true;
				$dentro.=$usuario;
				}
			$dentro.='</td></tr>';
			}
		if ($obj->agenda_descricao) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$obj->agenda_descricao.'</td></tr>';
		$sql->adTabela('agenda_arquivos');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS arquivo_dono');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = agenda_arquivo_usuario');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('agenda_arquivo_id, formatar_data(agenda_arquivo_data, "%d/%m/%Y %H:%i:%s") as data, agenda_arquivo_nome');
		$sql->adOnde('agenda_arquivo_agenda_id='.$agenda_id);
		$sql->adOrdem('agenda_arquivo_ordem ASC');
		$arquivos=$sql->Lista();
		$sql->limpar();
		$imagem='';
		if (count($arquivos) && $arquivos){
			$imagem=imagem('icones/miniclip.gif');
			$dentro .= '<tr><td colspan=2 align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Arquivos anexados</b></td></tr>';
			$tabela='';
			foreach($arquivos as $arquivo) $tabela.='<tr><td>'.$arquivo['agenda_arquivo_nome'].'</td><td>&nbsp;-&nbsp;</td><td>'.$arquivo['data'].'</td><td>&nbsp;-&nbsp;</td><td>'.$arquivo['arquivo_dono'].'</td></tr>';
			$dentro .= '<tr><td colspan=2><table>'.$tabela.'</table></td></tr>';
			}
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este compromisso.';
		if ($sem_texto) return dica($obj->agenda_titulo, $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=email&a=ver_compromisso&agenda_id='.$agenda_id.'\');">';
		else return dica($obj->agenda_titulo, $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=email&a=ver_compromisso&agenda_id='.$agenda_id.'\');"><span style="color:#'.melhorCor($obj->agenda_cor).';background-color:#'.$obj->agenda_cor.'">'.$imagem.$obj->agenda_titulo.'</span></a>'.dicaF();
		}
	else {
		$obj = new CAgenda();
		$obj->load($agenda_id);
		if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=email&a=ver_compromisso&agenda_id='.$agenda_id.'\');">';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=email&a=ver_compromisso&agenda_id='.$agenda_id.'\');">'.($imagem ? imagem('icones/compromisso'.$obj->agenda_tipo.'.png').' ': '').'<span style="color:#'.melhorCor($obj->agenda_cor).';background-color:#'.$obj->agenda_cor.'">'.$imagem.$obj->agenda_titulo.'</span></a>';
		}
	}

function link_evento($evento_id, $sem_texto='', $imagem=false){
	global $Aplic,$config;
	include_once BASE_DIR.'/modulos/calendario/calendario.class.php';
	if (!$evento_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$obj = new CEvento();
		$obj->load($evento_id);
		$tipos = getSisValor('TipoEvento');
		$recorrencia = array('Nunca', 'A cada hora', 'Diario', 'Semanalmente', 'Quinzenal', 'Mensal', 'Quadrimensal', 'Semestral', 'Anual');
		$designado = $obj->getDesignado();
		$sql = new BDConsulta;
		$sql->adTabela('eventos', 'e');
		$sql->esqUnir('cias', 'c', 'evento_cia = cia_id');
		$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = evento_dono');
		$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
		$sql->adCampo('cia_nome, formatar_data(evento_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(evento_fim, \'%d/%m/%Y  %H:%i\') AS fim');
		//$sql->adCampo('tema_nome, pratica_indicador_nome, plano_acao_nome, pratica_nome, tarefa_nome, projeto_nome,pg_estrategia_nome, pg_objetivo_estrategico_nome');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS dono');
		$sql->adOnde('evento_id = '.(int)$evento_id);
		$linha = $sql->Linha();
		$sql->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($tipos[$obj->evento_tipo]) $dentro .= '<tr><td width="100" align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$tipos[$obj->evento_tipo].' '.imagem('icones/evento'.$obj->evento_tipo.'.png').'</td></tr>';
		if ($obj->evento_descricao) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$obj->evento_descricao.'</td></tr>';
		if ($obj->evento_oque) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>O Que</b></td><td>'.$obj->evento_oque.'</td></tr>';
		if ($obj->evento_quem) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Quem</b></td><td>'.$obj->evento_quem.'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$linha['dono'].'</td></tr>';
		if (count($designado)) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Participante'.(count($designado)>1 ? 's' :'').'</b></td><td>'.implode('<br>', $designado).'</td></tr>';
		if ($obj->evento_quando) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Quando</b></td><td>'.$obj->evento_quando.'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Período</b></td><td>'.$linha['inicio'].' a '.$linha['fim'].'</td></tr>';
		if ($obj->evento_recorrencias) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Recorrência</b></td><td>'.$recorrencia[$obj->evento_recorrencias].($obj->evento_recorrencias ? ' ('.$obj->evento_nr_recorrencias.' vez'.((int)$obj->evento_nr_recorrencias > 1 ? 'es':''). ')' : '').'</td></tr>';
		if ($obj->evento_onde) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Onde</b></td><td>'.$obj->evento_onde.'</td></tr>';
		if ($obj->evento_porque) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Por Que</b></td><td>'.$obj->evento_porque.'</td></tr>';
		if ($obj->evento_como) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Como</b></td><td>'.$obj->evento_como.'</td></tr>';
		if ($obj->evento_quanto) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Quanto</b></td><td>'.$obj->evento_quanto.'</td></tr>';

		if ($sem_texto) return dica($obj->evento_titulo, $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=calendario&a=ver&evento_id='.$evento_id.'\');">';
		else return dica($obj->evento_titulo, $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=calendario&a=ver&evento_id='.$evento_id.'\');">'.($imagem ? imagem('icones/evento'.$obj->evento_tipo.'.png').' ': '').'<span style="color:#'.melhorCor($obj->evento_cor).';background-color:#'.$obj->evento_cor.'">'.$obj->evento_titulo.'</span></a>'.dicaF();

		}
	else {
		$obj = new CEvento();
		$obj->load($evento_id);
		if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=calendario&a=ver&evento_id='.$evento_id.'\');">';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=calendario&a=ver&evento_id='.$evento_id.'\');">'.($imagem ? imagem('icones/evento'.$obj->evento_tipo.'.png').' ': '').'<span style="color:#'.melhorCor($obj->evento_cor).';background-color:#'.$obj->evento_cor.'">'.$obj->evento_titulo.'</span></a>';
		}
	}

function link_despacho($msg_usuario_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$msg_usuario_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('msg_usuario');
	$sql->esqUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
	$sql->esqUnir('anotacao','anotacao','anotacao.anotacao_id=msg_usuario.anotacao_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS despacho_dono');
	$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = msg_usuario.de_id');
	$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
	$sql->adCampo('msg.msg_id, msg.referencia, msg_usuario.msg_usuario_id, anotacao.datahora, anotacao.texto, data_limite');
	$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
	$linha = $sql->Linha();
	$sql->limpar();
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['mensagem']).'</b></td><td>'.$linha['msg_id'].' - '.$linha['referencia'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Despachou</b></td><td>'.$linha['despacho_dono'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Texto</b></td><td>'.$linha['texto'].'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique para inserir uma resposta a este despacho.';
	if ($sem_texto) return dica('Data Limite de Despacho', $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=lista_despacho&tab=0&msg_usuario_id='.$msg_usuario_id.'\');">';
	else return dica('Despacho', $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=lista_despacho&tab=0&msg_usuario_id='.$msg_usuario_id.'\');">'.imagem('icones/msg10010.gif').' Despacho</a>'.dicaF();
	}

function link_msg_tarefa($msg_usuario_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$msg_usuario_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('msg_usuario');
	$sql->esqUnir('msg','msg','msg.msg_id=msg_usuario.msg_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS msg_dono');
	$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = msg_usuario.de_id');
	$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
	$sql->adCampo('msg.msg_id, msg.referencia, msg_usuario.msg_usuario_id, tarefa_data, texto, tarefa_progresso');
	$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
	$linha = $sql->Linha();
	$sql->limpar();
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['mensagem']).'</b></td><td>'.$linha['msg_id'].' - '.$linha['referencia'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Progresso</b></td><td>'.(int)$linha['tarefa_progresso'].'%</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Enviou</b></td><td>'.$linha['msg_dono'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Texto</b></td><td>'.$linha['texto'].'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique para inserir uma percentagem executada.';
	if ($sem_texto) return dica('Data Limite', $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=lista_msg_tarefa&tab=0&msg_usuario_id='.$msg_usuario_id.'\');">';
	else return dica(''.ucfirst($config['mensagem']).' do Tipo Atividade', $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=lista_msg_tarefa&tab=0&msg_usuario_id='.$msg_usuario_id.'\');">'.imagem('icones/task_p.png').(strlen($linha['referencia']) > 30 ? substr($linha['referencia'], 0, 28).'...' : $linha['referencia']).'</a>'.dicaF();
	}


function link_modelodespacho($modelo_usuario_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$modelo_usuario_id) return '&nbsp';
	$sql = new BDConsulta;
	$sql->adTabela('modelo_usuario');
	$sql->esqUnir('modelos','modelos','modelos.modelo_id=modelo_usuario.modelo_id');
	$sql->esqUnir('modelos_tipo','modelos_tipo','modelos_tipo.modelo_tipo_id=modelos.modelo_tipo');
	$sql->esqUnir('modelo_anotacao','modelo_anotacao','modelo_anotacao.modelo_anotacao_id=modelo_usuario.modelo_anotacao_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS despacho_dono');
	$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = modelo_usuario.de_id');
	$sql->esqUnir('contatos', 'contatos', 'usuarios.usuario_contato = contatos.contato_id');
	$sql->adCampo('modelos.modelo_id, modelo_assunto, modelo_usuario.modelo_usuario_id, modelo_anotacao.datahora, modelo_anotacao.texto, data_limite, modelo_tipo_nome');
	$sql->adOnde('modelo_usuario.modelo_usuario_id = '.$modelo_usuario_id);
	$linha = $sql->Linha();
	$sql->limpar();
	$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Documento</b></td><td>'.$linha['modelo_id'].' - '.$linha['modelo_assunto'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$linha['modelo_tipo_nome'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Despachou</b></td><td>'.$linha['despacho_dono'].'</td></tr>';
	$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Texto</b></td><td>'.$linha['texto'].'</td></tr>';
	$dentro .= '</table>';
	$dentro .= '<br>Clique para inserir uma resposta a este despacho.';
	if ($sem_texto) return dica('Data Limite de Despacho', $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0,  \'m=email&a=lista_despacho_modelo&tab=0&modelo_usuario_id='.$modelo_usuario_id.'\');">';
	else return dica('Despacho', $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=lista_despacho_modelo&tab=0&modelo_usuario_id='.$modelo_usuario_id.'\');">'.imagem('icones/msg10010.gif').' Despacho</a>'.dicaF();
	}


function link_link($link_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$link_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$tipo=getSisValor('TipoLink');
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta();
		$sql->adCampo('links.*');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, tarefa_nome, projeto_nome, cia_nome');
		$sql->adTabela('links');
		$sql->esqUnir('usuarios', 'u', 'link_dono = usuario_id');
		$sql->esqUnir('contatos', 'c', 'usuario_contato = contato_id');
		$sql->adUnir('projetos', 'p', 'projeto_id = link_projeto');
		$sql->adUnir('tarefas', 't', 'tarefa_id = link_tarefa');
		$sql->adUnir('cias', 'co', 'projeto_cia = cia_id');
		$sql->adOnde('link_id = '.(int)$link_id);
		$linha = $sql->Linha();
		$data = $linha['link_data'] ? new CData($linha['link_data']) : new CData();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($linha['link_url']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Endereço</b></td><td>'.$linha['link_url'].'</td></tr>';
		if ($linha['link_categoria']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Categoria</b></td><td>'.$tipo[$linha['link_categoria']].'</td></tr>';
		if ($linha['projeto_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['projeto']).'</b></td><td>'.$linha['projeto_nome'].'</td></tr>';
		if ($linha['tarefa_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tarefa</b></td><td>'.$linha['tarefa_nome'].'</td></tr>';
		if ($linha['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		if ($linha['link_dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Criador</b></td><td>'.$linha['nome'].'</td></tr>';
		if ($linha['link_data']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data</b></td><td>'.$data->format($df.' '.$tf).'</td></tr>';
		if ($linha['link_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$linha['link_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este link.';
		if ($sem_texto) return dica($linha['link_nome'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=links&a=ver&link_id='.$link_id.'\');">';
		else return dica($linha['link_nome'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=links&a=ver&link_id='.$link_id.'\');">'.$linha['link_nome'].'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta();
		$sql->adCampo('links.nome');
		$sql->adOnde('link_id = '.(int)$link_id);
		$linha = $sql->Linha();
		if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=links&a=ver&link_id='.$link_id.'\');">';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=links&a=ver&link_id='.$link_id.'\');">'.$linha['link_nome'].'</a>';
		}
	}


function link_recurso($recurso_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$recurso_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$tipo=getSisValor('TipoRecurso');
		$sql = new BDConsulta();
		$sql->adTabela('recursos');
		$sql->esqUnir('sisvalores','sisvalores','sisvalores.sisvalor_valor_id=recursos.recurso_nd');
		$sql->adCampo('sisvalor_valor AS nd');
		$sql->adCampo('recursos.*');
		$sql->adOnde('recurso_id = '.$recurso_id);

		$sql->adOnde('sisvalor_projeto IS NULL');

		$linha = $sql->Linha();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($linha['recurso_chave']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Código</b></td><td>'.$linha['recurso_chave'].'</td></tr>';
		if ($linha['recurso_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Nome</b></td><td>'.$linha['recurso_nome'].'</td></tr>';
		if (isset($linha['recurso_tipo'])) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.$tipo[$linha['recurso_tipo']].'</td></tr>';
		if ($linha['recurso_nota']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$linha['recurso_nota'].'</td></tr>';
		if ($linha['recurso_quantidade']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Quantidade Total</b></td><td>'.($linha['recurso_tipo']==5 ? $config['simbolo_moeda'].' '.number_format($linha['recurso_quantidade'], 2, ',', '.'):$linha['recurso_quantidade']).'</td></tr>';
		if ($linha['recurso_tipo']==5 && $linha['recurso_nd']){
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>ND</b></td><td>'.$linha['nd'].'</td></tr>';
			}
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este recurso.';
		if ($sem_texto) return dica($linha['recurso_nome'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=recursos&a=ver&recurso_id='.$recurso_id.'\');">';
		else return dica($linha['recurso_nome'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=recursos&a=ver&recurso_id='.$recurso_id.'\');">'.$linha['recurso_nome'].'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta();
		$sql->adCampo('recursos.recurso_nome');
		$sql->adOnde('recurso_id = '.(int)$recurso_id);
		$linha = $sql->Linha();
		if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=recursos&a=ver&recurso_id='.$recurso_id.'\');">';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=recursos&a=ver&recurso_id='.$recurso_id.'\');">'.$linha['recurso_nome'].'</a>';
		}
	}

function link_arquivo($arquivo_id, $sem_texto=''){
	global $Aplic,$config;
	include_once BASE_DIR.'/modulos/arquivos/arquivos.class.php';
	if (!$arquivo_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$arquivo_tipos = getSisValor('TipoArquivo');
		$sql = new BDConsulta();
		$sql->adCampo('arquivos.*');
		$sql->adCampo('arquivo_pasta_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, tarefa_nome, projeto_nome, cia_nome');
		$sql->adTabela('arquivos');
		$sql->adUnir('usuarios', 'u', 'arquivo_dono = usuario_id');
		$sql->adUnir('contatos', 'c', 'usuario_contato = contato_id');
		$sql->adUnir('projetos', 'p', 'projeto_id = arquivo_projeto');
		$sql->adUnir('tarefas', 't', 'tarefa_id = arquivo_tarefa');
		$sql->adUnir('arquivo_pasta', 'ff', 'arquivo_pasta_id = arquivo_pasta');
		$sql->adUnir('cias', 'co', 'projeto_cia = cia_id');
		$sql->adOnde('arquivo_id = '.(int)$arquivo_id);
		$linha = $sql->Linha();
		$sql->limpar();
		//$obj = new CArquivo();
		//$obj->load($arquivo_id);
		$data = $linha['arquivo_data'] ? new CData($linha['arquivo_data']) : new CData();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($linha['projeto_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['projeto']).'</b></td><td>'.$linha['projeto_nome'].'</td></tr>';
		if ($linha['tarefa_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tarefa</b></td><td>'.$linha['tarefa_nome'].'</td></tr>';
		if ($linha['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		if ($linha['arquivo_dono']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$linha['nome'].'</td></tr>';
		if ($linha['arquivo_data']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data</b></td><td>'.$data->format($df.' '.$tf).'</td></tr>';
		if ($linha['arquivo_pasta_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pasta</b></td><td>'.$linha['arquivo_pasta_nome'].'</td></tr>';
		if ($linha['arquivo_categoria']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Categoria</b></td><td>'.$arquivo_tipos[$linha['arquivo_categoria']].'</td></tr>';
		if ($linha['arquivo_versao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Versão</b></td><td>'.sprintf('%.2f',$linha['arquivo_versao']).'</td></tr>';
		if ($linha['arquivo_tipo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tipo</b></td><td>'.substr($linha['arquivo_tipo'], strpos($linha['arquivo_tipo'], '/') + 1).'<img border=0 width="16" heigth="16" src="'.acharImagem(getIcone($linha['arquivo_tipo'])).'" /></td></tr>';
		if ($linha['arquivo_tamanho']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tamanho</b></td><td>'.arquivo_tamanho($linha['arquivo_tamanho']).'</td></tr>';
		if ($linha['arquivo_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$linha['arquivo_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para abrir este arquivo.';
		if ($sem_texto) return dica($linha['arquivo_nome'], $dentro,'','',true).'<a href="codigo/arquivo_visualizar.php?arquivo_id='.$arquivo_id.'">';
		else return dica($linha['arquivo_nome'], $dentro,'','',true).'<a href="codigo/arquivo_visualizar.php?arquivo_id='.$arquivo_id.'">'.$linha['arquivo_nome'].'</a>'.dicaF();
		}
	else {
		$sql = new BDConsulta();
		$sql->adCampo('arquivos.nome');
		$sql->adTabela('arquivos');
		$sql->adOnde('arquivo_id = '.(int)$arquivo_id);
		$linha = $sql->Linha();
		$sql->limpar();
		if ($sem_texto) return '<a href="codigo/arquivo_visualizar.php?arquivo_id='.$arquivo_id.'">';
		else return '<a href="codigo/arquivo_visualizar.php?arquivo_id='.$arquivo_id.'">'.$linha['arquivo_nome'].'</a>';
		}
	}

function link_forum($forum_id){
	global $Aplic,$config;
	if (!$forum_id) return '&nbsp';
		$q = new BDConsulta;
		$q->adTabela('foruns');
		$q->esqUnir('usuarios', 'usuarios', 'forum_dono = usuarios.usuario_id');
		$q->esqUnir('contatos', 'con', 'con.contato_id = usuarios.usuario_contato');
		$q->adCampo('concatenar_tres(con.contato_posto, \' \', con.contato_nomeguerra) AS responsavel');
		$q->adCampo('forum_nome, forum_descricao');
		$q->adOnde('forum_id = '.(int)$forum_id);
		$forum = $q->Linha();
		$q->limpar();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($forum['responsavel']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Responsável</b></td><td>'.$forum['responsavel'].'</td></tr>';
		if ($forum['forum_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$forum['forum_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este fórum.';
		return dica('Fórum', $dentro).'<a href="javascript:void(0);" onclick="url_passar(0, \'m=foruns&a=ver&forum_id='.$forum_id.'\');">'.$forum['forum_nome'].'</a>'.dicaF();
		}


function link_mensagem($mensagem_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$mensagem_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta();
		$sql->adCampo('forum_mensagens.*');
		$sql->adCampo('cia_nome, forum_nome, forum_contagem_msg, concatenar_tres(c.contato_posto, \' \', c.contato_nomeguerra) as nome_dono, concatenar_tres(c2.contato_posto, \' \', c2.contato_nomeguerra) as editor, tarefa_nome, projeto_nome');
		$sql->adTabela('forum_mensagens');
		$sql->adUnir('foruns', 'f', 'mensagem_forum = forum_id');
		$sql->adUnir('usuarios', 'u', 'mensagem_autor = u.usuario_id');
		$sql->adUnir('contatos', 'c', 'u.usuario_contato = c.contato_id');
		$sql->adUnir('usuarios', 'u2', 'mensagem_editor = u2.usuario_id');
		$sql->adUnir('contatos', 'c2', 'u2.usuario_contato = c2.contato_id');
		$sql->adUnir('projetos', 'p', 'projeto_id = forum_projeto');
		$sql->adUnir('tarefas', 't', 'tarefa_id = forum_tarefa');
		$sql->adUnir('cias', 'co', 'projeto_cia = cia_id');
		$sql->adOnde('mensagem_id = '.(int)$mensagem_id);
		$linha = $sql->Linha();
		$data = $linha['mensagem_data'] ? new CData($linha['mensagem_data']) : new CData();
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($linha['forum_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Fórum</b></td><td>'.$linha['forum_nome'].'</td></tr>';
		if ($linha['projeto_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['projeto']).'</b></td><td>'.$linha['projeto_nome'].'</td></tr>';
		if ($linha['tarefa_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tarefa</b></td><td>'.$linha['tarefa_nome'].'</td></tr>';
		if ($linha['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		if ($linha['mensagem_autor']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Autor</b></td><td>'.$linha['nome_dono'].'</td></tr>';
		if ($linha['mensagem_editor']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Editor</b></td><td>'.$linha['editor'].'</td></tr>';
		if ($linha['mensagem_data']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data</b></td><td>'.$data->format($df.' '.$tf).'</td></tr>';
		if ($linha['forum_contagem_msg']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Número de Mensagem</b></td><td>'.$linha['forum_contagem_msg'].'</td></tr>';
		if ($linha['mensagem_texto']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Mensagem</b></td><td>'.$linha['mensagem_texto'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver esta mensagem do fórum.';
		if ($sem_texto) return dica($linha['mensagem_titulo'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=foruns&a=ver&mensagem_id='.$mensagem_id.'\');">';
		else return dica($linha['mensagem_titulo'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=foruns&a=ver&mensagem_id='.$mensagem_id.'\');">'.$linha['mensagem_titulo'].'</a>'.dicaF();
		}
	else {
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta();
		$sql->adCampo('forum_mensagens.mensagem_titulo');
		$sql->adOnde('mensagem_id = '.(int)$mensagem_id);
		$linha = $sql->Linha();
		if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=foruns&a=ver&mensagem_id='.$mensagem_id.'\');">';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=foruns&a=ver&mensagem_id='.$mensagem_id.'\');">'.$linha['mensagem_titulo'].'</a>';
		}
	}

function link_registro($tarefa_log_id, $sem_texto=''){
	global $Aplic,$config;
	if (!$tarefa_log_id) return '&nbsp';
	if ($config['popup_detalhado']){
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta();
		$sql->adTabela('tarefa_log');
		$sql->esqUnir('sisvalores','sisvalores','sisvalores.sisvalor_valor_id=tarefa_log.tarefa_log_nd');
		$sql->adCampo('sisvalor_valor AS nd');
		$sql->adCampo('tarefa_log.*');
		$sql->adCampo('cia_nome, concatenar_tres(c.contato_posto, \' \', c.contato_nomeguerra) as nome, tarefa_nome, projeto_nome, cia_nome');
		$sql->adUnir('usuarios', 'u', 'tarefa_log_criador = u.usuario_id');
		$sql->adUnir('contatos', 'c', 'u.usuario_contato = c.contato_id');
		$sql->adUnir('tarefas', 't', 't.tarefa_id = tarefa_log_tarefa');
		$sql->adUnir('projetos', 'p', 'p.projeto_id = t.tarefa_projeto');
		$sql->adUnir('cias', 'co', 'projeto_cia = cia_id');
		$sql->adOnde('tarefa_log_id = '.(int)$tarefa_log_id);

		$sql->adOnde('sisvalor_projeto IS NULL');

		$linha = $sql->Linha();
		$data = $linha['tarefa_log_data'] ? new CData($linha['tarefa_log_data']) : new CData();
		$RefRegistroTarefa = getSisValor('RefRegistroTarefa');
		$RefRegistroTarefaImagem = getSisValor('RefRegistroTarefaImagem');
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		if ($linha['projeto_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['projeto']).'</b></td><td>'.$linha['projeto_nome'].'</td></tr>';
		if ($linha['tarefa_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Tarefa</b></td><td>'.$linha['tarefa_nome'].'</td></tr>';
		if ($linha['cia_nome']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>'.ucfirst($config['organizacao']).'</b></td><td>'.$linha['cia_nome'].'</td></tr>';
		if ($linha['tarefa_log_criador']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Criador</b></td><td>'.$linha['nome'].'</td></tr>';
		if ($linha['tarefa_log_referencia']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Referência</b></td><td>'.$RefRegistroTarefa[$linha['tarefa_log_referencia']].' '.imagem('icones/'.$RefRegistroTarefaImagem[$linha['tarefa_log_referencia']]).'</td></tr>';
		if ($linha['tarefa_log_data']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Data</b></td><td>'.$data->format($df.' '.$tf).'</td></tr>';
		if ($linha['tarefa_log_horas']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Horas</b></td><td>'. sprintf('%.2f', $linha['tarefa_log_horas']).'</td></tr>';
		if ((int)$linha['tarefa_log_custo']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Custo</b></td><td>'.$config['simbolo_moeda'].' '.number_format($linha['tarefa_log_custo'], 2, ',', '.').'</td></tr>';
		if ((int)$linha['tarefa_log_custo'] && $linha['tarefa_log_nd']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>ND</b></td><td>'.$linha['nd'].'</td></tr>';
		if ($linha['tarefa_log_url_relacionada']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>URL</b></td><td>'.$linha['tarefa_log_url_relacionada'].'</td></tr>';
		if ($linha['tarefa_log_problema']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Problema</b></td><td>Sim</td></tr>';
		if ($linha['tarefa_log_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$linha['tarefa_log_descricao'].'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique para ver este registro.';
		if ($sem_texto) return dica($linha['tarefa_log_nome'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$linha['tarefa_log_tarefa'].'&tab=0&tarefa_log_id='.$tarefa_log_id.'\');">';
		else return dica($linha['tarefa_log_nome'], $dentro,'','',true).'<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$linha['tarefa_log_tarefa'].'&tab=0&tarefa_log_id='.$tarefa_log_id.'\');">'.$linha['tarefa_log_nome'].'</a>'.dicaF();
		}
	else {
		$df = '%d/%m/%Y';
		$tf = $Aplic->getPref('formatohora');
		$sql = new BDConsulta();
		$sql->adCampo('tarefa_log_tarefa, tarefa_log_nome');
		$sql->adTabela('tarefa_log');
		$sql->adOnde('tarefa_log_id = '.(int)$tarefa_log_id);
		$linha = $sql->Linha();
		if ($sem_texto) return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$linha['tarefa_log_tarefa'].'&tab=0&tarefa_log_id='.$tarefa_log_id.'\');">';
		else return '<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=tarefas&a=ver&tarefa_id='.$linha['tarefa_log_tarefa'].'&tab=0&tarefa_log_id='.$tarefa_log_id.'\');">'.$linha['tarefa_log_nome'].'</a>';
		}
	}

function dia_semana ($entrada){
	$portugues= array('Monday'=>'segunda-feira', 'Tuesday'=>'terça-feira', 'Wednesday'=>'quarta-feira', 'Thursday'=>'quinta-feira', 'Friday'=>'sexta-feira', 'Saturday'=>'sábado', 'Sunday'=>'domingo');
	if (array_key_exists($entrada, $portugues)) return $portugues[$entrada];
	else return  $entrada;
	}

function dia_semana_curto ($entrada){
	$portugues= array('Mon'=>'seg', 'Tue'=>'ter', 'Wed'=>'qua', 'Thu'=>'qui', 'Fri'=>'sex', 'Sat'=>'sáb', 'Sun'=>'dom');
	if (array_key_exists($entrada, $portugues)) return $portugues[$entrada];
	else return  $entrada;
	}

function html_para_javascript($entrada, $sem_br=false){
	$remover = array("\n","\r","\n\r","\r\n");
	if ($sem_br) $saida = str_replace($remover,'', $entrada);
	else $saida = str_replace($remover,'', nl2br($entrada));
	return $saida;
	}

function melhorCor($bg, $lt = 'ffffff', $dk = '000000') {
	$x = 128;
	if (!$bg) return $dk;
	$r = hexdec(substr($bg, 0, 2));
	$g = hexdec(substr($bg, 2, 2));
	$b = hexdec(substr($bg, 4, 2));
	if ($r < $x && $g < $x || $r < $x && $b < $x || $b < $x && $g < $x) return $lt;
	else return $dk;
	}


function selecionaVetorMultiplo($arr, $nome_selecionado, $atributo_selecionado='', $selecionado=array(), $ignorar_chave=false, $id='', $estilo=array()) {
	global $Aplic;
	$tem_estilo=(count($estilo) ? 1 : 0);
	if (!is_array($arr)) {
		dprint(__file__, __line__, 0, 'parâmetro passado tem que ser vetor');
		return '';
		}
	reset($arr);
	$s='<span '.$atributo_selecionado.' ><table cellspacing=0 cellpadding=0>';
	$selecionou = 0;
	foreach ($arr as $chave => $valores) {
		$s .= '<tr><td><INPUT TYPE="checkbox" NAME="'.$nome_selecionado.'[]" VALUE="'.$chave.'" '.(in_array($chave, $selecionado) ? 'checked="yes"' : '').' ></td><td>'.$valores.'</td></tr>';
		}
	$s .= '</table></span>';
	return $s;
	}


function selecionaVetorMultiploExibicao($arr, $nome_selecionado, $selecionado=array()) {
	global $dialogo;

	$saida='';
	$lista='';
	if (isset($selecionado) && count($selecionado)) {
		$saida= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';

		if (!$dialogo){
			$saida.= '<tr><td>'.(isset($arr[$selecionado[0]]) ? $arr[$selecionado[0]] : '&nbsp;');
			$qnt_lista=count($selecionado);
			if ($qnt_lista > 1) {
				for ($i = 1, $i_cmp = $qnt_lista; $i < $i_cmp; $i++) $lista.=(isset($arr[$selecionado[$i]]) ? $arr[$selecionado[$i]] : '&nbsp;').'<br>';
				$saida.= dica('Expandir', 'Clique para expandir a lista.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\''.$nome_selecionado.'\');">(+'.($qnt_lista - 1).')</a>'.dicaF(). '<span style="display: none" id="'.$nome_selecionado.'"><br>'.$lista.'</span>';
				}
			}
		else {
			$saida.= '<tr><td>';
			$qnt_lista=count($selecionado);
			if ($qnt_lista > 0) {
				for ($i = 0, $i_cmp = $qnt_lista; $i < $i_cmp; $i++) $saida.=(isset($arr[$selecionado[$i]]) ? $arr[$selecionado[$i]] : '&nbsp;').'<br>';
				}
			}
		$saida.= '</td></tr></table>';
		}

	return $saida;
	}



function selecionaVetorExibicao($arr, $selecionado='') {
	return (isset($arr[$selecionado]) ? $arr[$selecionado] : '');
	}

function selecionaVetor($arr, $nome_selecionado, $atributo_selecionado='', $selecionado='', $ignorar_chave=false, $id='', $estilo=array()) {
	global $Aplic;
	$eh_vetor=is_array($selecionado);
	$tem_estilo=(count($estilo) ? 1 : 0);
	$eh_digito=ctype_digit($selecionado);
	$s = '<select id="'.($id ? $id : $nome_selecionado).'" name="'.$nome_selecionado.'" '.$atributo_selecionado.'>';
	foreach ($arr as $k => $v) {
		$marcado='';
		if ($eh_vetor && ($ignorar_chave ? in_array($v,$selecionado) : in_array($k,$selecionado))) $marcado=' selected ';
		elseif ($eh_digito && ($ignorar_chave ? $v == $selecionado : $k == $selecionado))	$marcado=' selected ';
		elseif ($ignorar_chave ? $v === $selecionado : $k === $selecionado)	$marcado=' selected ';
		$s .= '<option value="'.($ignorar_chave ? $v : $k).'"'.$marcado.($tem_estilo && isset($estilo[$k])? ' style="'.$estilo[$k].'"' : '').'>'.$v.'</option>';
		}
	$s .= '</select>';
	return $s;
	}

function selecionaComboTraducao($arr, $nome_selecionado, $atributo_selecionado, $selecionado, $traducao = array()) {
	global $Aplic;
	if (!is_array($arr)) {
		dprint(__file__, __line__, 0, 'selecionaVetor chamado tem que ser vetor');
		return '';
		}
	reset($arr);
	$s = '<select id="'.$nome_selecionado.'" name="'.$nome_selecionado.'" '.$atributo_selecionado.'>';
	$selecionou = 0;
	foreach ($arr as $k => $v) {
		$s .= '<option value="'.$k.'"'.((($k == $selecionado && strcmp($k, $selecionado) == 0) && !$selecionou) ? ' selected="selected"' : '').'>'.(isset($traducao[$v])? $traducao[$v] : $v).'</option>';
		if (($k == $selecionado && strcmp($k, $selecionado) == 0)) $selecionou = 1;
		}
	$s .= '</select>';
	return $s;
	}

function resultafazer_combo($arr, $nome_selecionado, $selecionado) {
	global $Aplic;
	if (!is_array($arr)) {
		dprint(__file__, __line__, 0, 'Não foi passado um vetor');
		return '';
		}
	reset($arr);
	foreach ($arr as $k => $v) {
		if (($k == $selecionado && strcmp($k, $selecionado) == 0)) return $v;
		}
	return '';
	}

function meuCombo_chave($arr, $nome_selecionado,  $selecionado, $inverso=false) {
	global $Aplic;
	reset($arr);
	$s = '<select id="'.$nome_selecionado.'" name="'.$nome_selecionado.'" class=texto size=1 >';
	foreach ($arr as $k => $v) $s .= '<option value="'.$k.'"'.((!$inverso ? $v == $selecionado : $k == $selecionado) ? ' selected="selected"' : '').'>'.$v.'</option>';
	$s .= '</select>';
	return $s;
	}

function selecionaVetorArvore($arr, $nome_selecionado, $atributo_selecionado, $selecionado, $traduzir = false) {
	global $Aplic;
	reset($arr);
	$subordinada = array();
	foreach ($arr as $k => $v) {
		$id = $v[0];
		$pt = $v[2];
		$lista = (isset($subordinada[$pt]) && $subordinada[$pt] ? $subordinada[$pt] : array());
		array_push($lista, $v);
		$subordinada[$pt] = $lista;
		}
	$lista = arvore_recurso($arr[0][2], '', array(), $subordinada);
	return selecionaVetor($lista, $nome_selecionado, $atributo_selecionado, $selecionado, $traduzir);
	}

function arvore_recurso($id, $indent, $lista, $subordinada) {
	if (isset($subordinada[$id]) && $subordinada[$id]) {
		foreach ($subordinada[$id] as $v) {
			$id = $v[0];
			$txt = $v[1];
			$pt = $v[2];
			$lista[$id] = $indent.' '.$txt;
			$lista = arvore_recurso($id, $indent.'&nbsp;&nbsp;&nbsp;', $lista, $subordinada);
			}
		}
	return $lista;
	}

function resultadoArvore($arr, $nome_selecionado, $selecionado) {
	global $Aplic;
	reset($arr);
	$subordinada = array();
	foreach ($arr as $k => $v) {
		$id = $v[0];
		$pt = $v[2];
		$lista = (isset($subordinada[$pt]) && $subordinada[$pt]) ? $subordinada[$pt] : array();
		array_push($lista, $v);
		$subordinada[$pt] = $lista;
		}
	$lista = arvore_recurso($arr[0][2], '', array(), $subordinada);
	return resultafazer_combo($lista, $nome_selecionado, $selecionado);
	}

function projetoEscolhe($nome_selecionado, $atributo_selecionado='', $selecionado=0, $excluirProjetoComId = null, $semSelecao='', $cia_id=0, $filtro_nulo='', $responsavel_id=0) {
	global $Aplic;
	$sql = new BDConsulta();
	$sql->adTabela('projetos', 'pr');
	$sql->esqUnir('cias', 'co', 'co.cia_id=pr.projeto_cia');
	$sql->adCampo('pr.projeto_id, co.cia_nome, projeto_nome, projeto_acesso');
	if ($cia_id) $sql->adOnde('pr.projeto_cia='.(int)$cia_id);
	if ($responsavel_id) $sql->adOnde('pr.projeto_responsavel='.$responsavel_id);
	if (!empty($excluirProjetoComId)) $sql->adOnde('pr.projeto_id != '.$excluirProjetoComId);
	$sql->adOrdem('co.cia_nome, projeto_nome');
	$projetos = $sql->Lista();
	$s = '<select name="'.$nome_selecionado.'" '.$atributo_selecionado.'>';
	$s .= '<option value="0" '.(!$selecionado ? 'selected="selected"' : '').' >'.$semSelecao.'</option>';

	if ($filtro_nulo) $s .= '<option value="-1" '.($selecionado==-1 ? 'selected="selected"' : '').'>'.$filtro_nulo.'</option>';

	$cia_atual = '';
	foreach ($projetos as $p) {
		if ($p['cia_nome'] != $cia_atual) {
			$cia_atual = $p['cia_nome'];
			$s .= '<optgroup style="font-style:normal; font-style:normal;" label="'.$cia_atual.'" >'.$cia_atual.'</optgroup>';
			}
		if (permiteAcessar($p['projeto_acesso'],$p['projeto_id'], 0))	$s .= '<option value="'.$p['projeto_id'].'" '.($selecionado == $p['projeto_id'] ? 'selected="selected"' : '').'>&nbsp;&nbsp;&nbsp;'.$p['projeto_nome'].'</option>';
		}
	$s .= '</select>';
	return $s;
	}

function unirVetores($a1, $a2) {
	foreach ($a2 as $k => $v)	$a1[$k] = $v;
	return $a1;
	}

function mostrarBlocos($arr, $arr_titulo=array(), $arr_texto=array() ) {
	global $Aplic;
	$blocos = array();
	if (popup_ativado()){
		foreach ($arr as $k => $v) $blocos[] = dica($arr_titulo[$k], $arr_texto[$k]).'<a class="botao" href="javascript:void(0);" onclick="url_passar(0, \''.$k.'\');"><span>'.$v.'</span></a>'.dicaF();
		return implode('</td><td align="left" nowrap="nowrap">', $blocos);
		}
	else {
		foreach ($arr as $k => $v) $blocos[] = '<a class="botao" href="javascript:void(0);" onclick="url_passar(0, \''.$k.'\');"><span>'.$v.'</span></a>';
		return implode('</td><td align="left" nowrap="nowrap">', $blocos);
		}
	}

function config($chave, $default = null) {
	global $config;
	if (!is_array($config))return $default;
	if (array_key_exists($chave, $config)) return $config[$chave];
	else return $default;
	}

function getUsuarioNome($usuario) {
	if (!$usuario) return '&nbsp;';
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('contato_posto, contato_nomeguerra');
	$sql->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
	$sql->adOnde('usuario_login LIKE \'%'.$usuario.'\' OR usuario_id = '.(int)$usuario);
	$r = $sql->Lista();
	return $r[0]['contato_posto'].' '.$r[0]['contato_nomeguerra'];
	}

function getUsuarios($modulo = ''){
	global $Aplic, $config;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('usuario_id, CASE WHEN tamanho_caractere(contato_funcao) > 0 THEN '.($config['militar'] < 10 ? 'concatenar_cinco(contato_posto, \' \', contato_nomeguerra, \'-\', contato_funcao)' : 'concatenar_tres(contato_nomeguerra, \'-\', contato_funcao)').' ELSE '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' END AS name');
	$sql->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
	$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
	$sql->esqUnir('cias', 'com', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'dep', 'dept_id = contato_dept');
	return unirVetores(array(0 => 'Todos '.$config['genero_usuario'].'s '.$config['usuarios']), $sql->ListaChave());
	}

function getListaUsuarios($procura = null, $onde = null, $ordenarPor = 'con.contato_nomeguerra', $cia_id=0, $extra_onde='', $ordem='ASC', $ver_subordinadas=0) {
	global $Aplic, $config;
	if ($config['militar']< 10 && $ordenarPor =='contato_nomeguerra') $ordenarPor = 'con.contato_posto_valor '.$ordem.', con.contato_nomeguerra';

	$lista_cias='';
	if ($ver_subordinadas){
		$vetor_cias=array();
		lista_cias_subordinadas($cia_id, $vetor_cias);
		$vetor_cias[]=$cia_id;
		$lista_cias=implode(',',$vetor_cias);
		}
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('DISTINCT(usuario_id), usuario_ativo, usuario_login, usuario_admin,  contato_nomeguerra, contato_posto, contato_email, cia_nome, contato_cia,  dept_id, dept_nome, con.contato_posto_valor, con.contato_nomeguerra, con.contato_dept');
  $sql->adCampo(($config['militar'] < 10 ? 'concatenar_cinco(contato_posto, \' \', contato_nomeguerra, \'-\', contato_funcao)' : 'concatenar_tres(contato_nomeguerra, \' - \', contato_funcao)').' AS contato_nome');
	$sql->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'com', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'dep', 'dept_id = contato_dept');
	if ($extra_onde) $sql->adOnde($extra_onde);
	if ($cia_id && !$lista_cias) $sql->adOnde('com.cia_id='.(int)$cia_id);
	elseif ($lista_cias) $sql->adOnde('com.cia_id IN ('.$lista_cias.')');
	if ($procura) $sql->adOnde('(UPPER(usuario_login) LIKE \'%'.$procura.'%\' OR UPPER(contato_posto) LIKE \'%'.$procura.'%\' OR UPPER(contato_nomeguerra) LIKE \'%'.$procura.'%\')');
	elseif ($onde) {
		$onde = $sql->quote('%'.$onde.'%');
		$sql->adOnde('(UPPER(usuario_login) LIKE '.$onde.' OR UPPER(contato_posto) LIKE '.$onde.' OR UPPER(contato_nomeguerra) LIKE '.$onde.')');
	}
	$sql->adOrdem($ordenarPor.' '.$ordem);
	return $sql->Lista();
	}

function getListaUsuariosaLinha($procura = null, $onde = null, $ordenarPor = 'contato_posto_valor, contato_nomeguerra', $cia_id=0) {
	global $Aplic, $config;
	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->adCampo('DISTINCT(usuario_id), usuario_admin, usuario_login, contato_nomeguerra, contato_posto, contato_email, cia_nome, contato_cia, dept_id, dept_nome, IF(tamanho_caractere(contato_funcao)>0, '.($config['militar'] < 10 ? 'concatenar_cinco(contato_posto, \' \', contato_nomeguerra, \'-\', contato_funcao)' : 'concatenar_tres(contato_nomeguerra, \'-\', contato_funcao)').', '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').') AS contato_nome');
	$sql->esqUnir('contatos', 'con', 'contato_id = usuario_contato');
	if ($procura) $sql->adOnde('(UPPER(usuario_login) LIKE \'%'.$procura.'%\' or UPPER(contato_posto) LIKE \'%'.$procura.'%\' OR UPPER(contato_nomeguerra) LIKE \'%'.$procura.'%\')');
	elseif ($onde) {
		$onde = $sql->quote('%'.$onde.'%');
		$sql->adOnde('(UPPER(usuario_login) LIKE '.$onde.' OR UPPER(contato_posto) LIKE '.$onde.' OR UPPER(contato_nomeguerra) LIKE '.$onde.')');
		}
	$sql->adOnde('usuario_ativo=1');
	if ($cia_id) $sql->adOnde('contato_cia='.(int)$cia_id);
	$sql->adGrupo('usuario_id');
	$sql->adOrdem($ordenarPor);
	$sql->esqUnir('cias', 'com', 'cia_id = contato_cia');
	$sql->esqUnir('depts', 'dep', 'dept_id = contato_dept');
	return $sql->ListaChave('usuario_id');
	}

function mostrarConfigModulo($config) {
	global $Aplic;
	$s = '<table cellspacing=2 cellpadding=2 border=0 class="std" width="50%">';
	$s .= '<tr><th colspan="2">Configuração do Módulo</th></tr>';
	foreach ($config as $k => $v) $s .= '<tr><td width="50%">'.$k.'</td><td width="50%" class="realce">'.$v.'</td></tr>';
	$s .= '</table>';
	return ($s);
	}

function acharImagem($nome) {
	global $estilo_ui;
	if (file_exists(BASE_DIR.'/estilo/rondon/imagens/icones/'.$nome)) return './estilo/rondon/imagens/icones/'.$nome;
	elseif (file_exists(BASE_DIR.'/estilo/rondon/imagens/'.$nome)) return './estilo/rondon/imagens/'.$nome;
	else return './estilo/rondon/imagens/'.$nome;
	}

function imagem($src, $titulo = '', $texto = '', $celular=0) {
	global $Aplic, $m;
	if ($src == '' || ($celular && $Aplic->celular)) return '';
	else $src = acharImagem($src);
	$resultado = '<img style="vertical-align:middle" src="'.$src.'" alt="" border=0>';
	if (($texto || $titulo) && popup_ativado()) $resultado = dica($titulo, $texto).$resultado.dicaF();
	return $resultado;
	}

function defVal($var, $def) {
	return isset($var) ? $var : $def;
	}

function getParam($arr, $nome, $def = null) {
	global $Aplic;

	if (is_array($nome)){
		foreach($nome as $chave => $valor){
			if (!isset($arr[$valor])) return $def;
			else if($arr[$valor]==='null') return null;
			else $arr=$arr[$valor];
			}
		if(!is_array($arr)) return ($arr || $arr=='0' || $arr===0 ? previnirXSS($arr) : null);
		else {
			foreach($arr as $chave => $valor1) $arr[$chave]=($valor1 || $valor1=='0' || $valor1===0 ? previnirXSS($valor1): null);
			return $arr;
			}
		}

	else if (!isset($arr[$nome])) return $def;
	else if($arr[$nome]==='null') return null;
	else if(!is_array($arr[$nome])) return ($arr[$nome] || $arr[$nome]=='0' || $arr[$nome]===0 ? previnirXSS($arr[$nome]) : null);
	else {
		foreach($arr[$nome] as $chave => $valor) $arr[$chave]=($valor || $valor=='0' || $valor===0 ? previnirXSS($valor): null);
		return $arr[$nome];
		}
	}

function previnirXSS($texto, $permitir_html=false){
  global $Aplic, $config;
  $ruim=true;
  $blacklist=array (
      ' java',
		 	'java ',
		  'alert(',
		  'alert ',
      'DELETE ',
      'INSERT ',
		 	'DROP ',
      'ALTER ',
      'CREATE ',
      ' DATABASE',
      'UPDATE ',
      'SELECT ',
      'javascript',
      'script',
		  'FSCommand',
		  'onAbort',
		  'onActivate',
		  'onAfterPrint',
		  'onAfterUpdate',
		  'onBeforeActivate',
		  'onBeforeCopy',
		  'onBeforeCut',
		  'onBeforeDeactivate',
		  'onBeforeEditFocus',
		  'onBeforePaste',
		  'onBeforePrint',
		  'onBeforeUnload',
		  'onBegin',
		  'onBlur',
		  'onBounce',
		  'onCellChange',
		  'onChange',
		  'onClick',
		  'onContextMenu',
		  'onControlSelect',
		  'onCopy',
		  'onCut',
		  'onDataAvailable',
		  'onDataSetChanged',
		  'onDataSetComplete',
		  'onDblClick',
		  'onDeactivate',
		  'onDrag',
		  'onDragEnd',
		  'onDragLeave',
		  'onDragEnter',
		  'onDragOver',
		  'onDragDrop',
		  'onDrop',
		  'onEnd',
		  'onError',
		  'onErrorUpdate',
		  'onFilterChange',
		  'onFinish',
		  'onFocus',
		  'onFocusIn',
		  'onFocusOut',
		  'onHelp',
		  'onKeyDown',
		  'onKeyPress',
		  'onKeyUp',
		  'onLayoutComplete',
		  'onLoad',
		  'onLoseCapture',
		  'onMediaComplete',
		  'onMediaError',
		  'onMouseDown',
		  'onMouseEnter',
		  'onMouseLeave',
		  'onMouseMove',
		  'onMouseOut',
		  'onMouseOver',
		  'onMouseUp',
		  'onMouseWheel',
		  'onMove',
		  'onMoveEnd',
		  'onMoveStart',
		  'onOutOfSync',
		  'onPaste',
		  'onPause',
		  'onProgress',
		  'onPropertyChange',
		  'onReadyStateChange',
		  'onRepeat',
		  'onReset',
		  'onResize',
		  'onResizeEnd',
		  'onResizeStart',
		  'onResume',
		  'onReverse',
		  'onRowsEnter',
		  'onRowExit',
		  'onRowDelete',
		  'onRowInserted',
		  'onScroll',
		  'onSeek',
		  'onSelect',
		  'onSelectionChange',
		  'onSelectStart',
		  'onStart',
		  'onStop',
		  'onSyncRestored',
		  'onSubmit',
		  'onTimeError',
		  'onTrackChange',
		  'onUnload',
		  'onURLFlip',
		  'seekSegmentTime');
	if (isset($Aplic->profissional) && $Aplic->profissional) {
	 	$blacklist[]="\r";
	  //$blacklist[]="\n";
	  if ($config['caixa_texto_padrao']=='caixa_texto_padrao3' && !$permitir_html){
	  	$blacklist[]="'";
	  	$blacklist[]='"';
	  	}
		}



	while($ruim){
    $texto_final=str_ireplace($blacklist,'', $texto);

    if ($texto==$texto_final){
       $texto=$texto_final;
       $ruim=false;
       }
    else $texto=$texto_final;
    }
  if (isset($Aplic->profissional) && $Aplic->profissional && $config['caixa_texto_padrao']=='caixa_texto_padrao3' && !$permitir_html) $texto=htmlspecialchars($texto);

	return ($texto != '' && $texto !=null && $texto!='null' ? (isset($Aplic->profissional) && $Aplic->profissional && $config['caixa_texto_padrao']=='caixa_texto_padrao3' ? mysql_real_escape_string($texto) : $texto) : null);
	}


function getSisValor($titulo='', $desloc=0, $parametro='', $ordem='', $tem_vazio=false, $chave_vazio=0, $projeto_id=null) {
	global $config;
	$sql = new BDConsulta;
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo = \''.$titulo.'\'');
	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
	else  $sql->adOnde('sisvalor_projeto IS NULL OR sisvalor_projeto=0');
	if ($parametro) $sql->adOnde($parametro);
	if ($config['tipoBd'] == 'mysql') $sql->adOrdem(($ordem ? $ordem : 'CAST(sisvalor_valor_id AS SIGNED INTEGER) ASC'));
	elseif ($config['tipoBd'] == 'postgres') $sql->adOrdem(($ordem ? $ordem : 'CAST(sisvalor_valor_id AS int) ASC'));
	$linhas = $sql->Lista();
	$sql->limpar();
	$arr = array();
	if ($tem_vazio)$arr[$chave_vazio]='';
	foreach ($linhas as $chave => $item) {
		if ($item) $arr[($desloc ? trim($item['sisvalor_valor_id'])+$desloc : trim($item['sisvalor_valor_id']) )] = trim($item['sisvalor_valor']);
		}
	return $arr;
	}


function getSisValorCampo($titulo, $id='', $projeto_id=null) {
	if (!$id) return '&nbsp;';
	$sql = new BDConsulta;
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor');
	$sql->adOnde('sisvalor_titulo = \''.$titulo.'\'');
	$sql->adOnde('sisvalor_valor_id = \''.$id.'\'');

	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
	else  $sql->adOnde('sisvalor_projeto IS NULL');

	$resultado = $sql->Resultado();
	$sql->limpar();
	return $resultado;
	}


function getValorChaveSisVal($tipo, $chave, $projeto_id=null) {
	$sql = new BDConsulta;
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo = \''.$tipo.'\'');
	$sql->adOnde('sisvalor_valor_id = \''.$chave.'\'');

	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
	else  $sql->adOnde('sisvalor_projeto IS NULL');

	$linhas = $sql->Lista();
	$sql->limpar();
	foreach ($linhas as $chave => $item)	if ($item) return $item['sisvalor_valor'];
	}

function getPais($titulo, $projeto_id=null) {
	$sql = new BDConsulta;
	$sql->adTabela('sisvalores');
	$sql->adCampo('sisvalor_valor_id, sisvalor_valor');
	$sql->adOnde('sisvalor_titulo = \''.$titulo.'\'');

	if ($projeto_id) $sql->adOnde('sisvalor_projeto='.(int)$projeto_id);
	else  $sql->adOnde('sisvalor_projeto IS NULL OR sisvalor_projeto = 0');

	$sql->adOrdem('sisvalor_valor');
	$linhas = $sql->Lista();
	$sql->limpar();
	$arr = array();
	$chave_ordenar = SORT_NUMERIC;
	foreach ($linhas as $chave => $item) {
		if ($item) $arr[trim($item['sisvalor_valor_id'])] = trim($item['sisvalor_valor']);
		}
	return $arr;
	}

function setMicroTempo() {
	global $microTimeSet;
	list($usec, $sec) = explode(' ', microtime());
	$microTimeSet = (float)$usec + (float)$sec;
	}

function formSeguro($txt, $tira_barra = false) {
	global $localidade_tipo_caract;
	if ($txt=='') return '';
	if (!$localidade_tipo_caract) $localidade_tipo_caract = 'iso-8859-1';
	if (is_object($txt)) {
		foreach (get_object_vars($txt) as $k => $v) {
			if ($tira_barra) $obj->$k = htmlspecialchars(stripslashes($v), ENT_COMPAT, $localidade_tipo_caract);
			else $obj->$k = htmlspecialchars($v, ENT_COMPAT, $localidade_tipo_caract);
			}
		}
	elseif (is_array($txt)) {
		foreach ($txt as $k => $v) {
			if ($tira_barra) $txt[$k] = htmlspecialchars(stripslashes($v), ENT_COMPAT, $localidade_tipo_caract);
			else $txt[$k] = htmlspecialchars($v, ENT_COMPAT, $localidade_tipo_caract);
			}
		}
	else {
		if ($tira_barra)	$txt = htmlspecialchars(stripslashes($txt), ENT_COMPAT, $localidade_tipo_caract);
		else $txt = htmlspecialchars($txt, ENT_COMPAT, $localidade_tipo_caract);
		}
	return $txt;
	}

function converterParaDias($duracao, $units) {
	global $config;
	switch ($units) {
		case 0:
		case 1:
			return $duracao / ($config['horas_trab_diario'] ? $config['horas_trab_diario'] : 8);
			break;
		case 24:
			return $duracao;
		}
	}

function formatar_retorno($bt, $arquivo, $linha, $msg) {
	echo '<pre>';
	echo 'ERRO: '.$arquivo.'('.$linha.') : '.$msg."\n";
	echo 'retorno:'."\n";
	foreach ($bt as $nivel => $grade) {
		echo $nivel.' '.(isset($grade['file']) ? $grade['file'] : '').':'.(isset($grade['line']) ? $grade['line'] : '').' '.(isset($grade['function']) ? $grade['function'] : '').'(';
		$in = false;
		foreach ($grade['args'] as $arg) {
			if ($in) echo ',';
			else $in = true;
			echo var_export($arg, true);
			}
		echo ")\n";
		}
	}

function dprint($arquivo, $linha, $nivel, $msg) {
	$max_nivel = 0;
	$max_nivel = (int)config('debug');
	$mostrar_debug = config('mostrar_debug', false);
	if ($nivel <= $max_nivel) {
		error_log($arquivo.'('.$linha.'): '.$msg);
		if ($mostrar_debug) echo $arquivo.'('.$linha.'): '.$msg.' <br />';
		if ($nivel == 0 && $max_nivel > 0 && version_compare(phpversion(), '4.3.0') >= 0)	formatar_retorno(debug_backtrace(), $arquivo, $linha, $msg);
		}
	}

if (!function_exists('htmlspecialchars_decode')) {
	function htmlspecialchars_decode($str) {
		return strtr($str, array_flip(get_html_translation_table(HTML_SPECIALCHARS)));
		}
	}

function decodificarHTML($txt) {
	global $localidade_tipo_caract;
	if (!$localidade_tipo_caract) $localidade_tipo_caract = 'iso-8859-1';
	if (is_object($txt)) {
		foreach (get_object_vars($txt) as $k => $v) $obj->$k = html_entity_decode($v, ENT_COMPAT, $localidade_tipo_caract);
		}
	elseif (is_array($txt)) {
		foreach ($txt as $k => $v) $txt[$k] = html_entity_decode($v, ENT_COMPAT, $localidade_tipo_caract);
		}
	else $txt = html_entity_decode($txt, ENT_COMPAT, $localidade_tipo_caract);
	return $txt;
	}

function dica($cabecalho = '', $tip = '', $cru = true, $id = '', $campo='') {
	global $Aplic, $localidade_tipo_caract;
	if (popup_ativado($campo)){
		$tt= '<span '.($id ? 'id="'.$id.'"' : '').' title="'.htmlspecialchars($cabecalho, ENT_QUOTES, $localidade_tipo_caract).'::'.htmlspecialchars($tip, ENT_QUOTES, $localidade_tipo_caract).'">';
        return $tt;
		}
	else return	'';
	}

function dicaF($campo=''){
	if (popup_ativado($campo))return '</span>';
	else return '';
	}

function hora_min($entrada){
	$minutos = (int)(($entrada - ((int)$entrada)) * 60);
	$minutos = ((strlen($minutos) == 1) ? ('0'.$minutos) : $minutos);
	return (strlen((int)$entrada)==1 ? '0'.(int)$entrada : (int)$entrada).':'.$minutos;
	}


function retorna_caixa_cor($pontos, $sufixo=''){
	$cor=retornar_cor($pontos);
	$pontos=number_format((float)$pontos, 2, ',', '.');
	return '<span style="border-style:solid; border-color: #000000; border-width:1px; background-color: #'.$cor.'; color: #'.$cor.';">'.dica($pontos.$sufixo).'&nbsp;&nbsp;'.dicaF().'</span>';
	}

function cor_indicador($campo='objetivo', $chave=0, $ano=null, $inicio=null, $fim=null, $indicador=null){
	global $Aplic,$config;

	if (!$chave && !$indicador) return false;
	$sql = new BDConsulta;
	if (!$indicador){
		if ($campo=='perspectiva'){
			$sql->adTabela('perspectivas');
			$sql->adCampo('pg_perspectiva_principal_indicador');
			$sql->adOnde('pg_perspectiva_id = '.(int)$chave);
			}
		elseif ($campo=='tema'){
			$sql->adTabela('tema');
			$sql->adCampo('tema_principal_indicador');
			$sql->adOnde('tema_id = '.(int)$chave);
			}
		elseif ($campo=='objetivo'){
			$sql->adTabela('objetivos_estrategicos');
			$sql->adCampo('pg_objetivo_estrategico_indicador');
			$sql->adOnde('pg_objetivo_estrategico_id = '.(int)$chave);
			}
		elseif ($campo=='fator'){
			$sql->adTabela('fatores_criticos');
			$sql->adCampo('pg_fator_critico_principal_indicador');
			$sql->adOnde('pg_fator_critico_id = '.(int)$chave);
			}
		elseif ($campo=='estrategia'){
			$sql->adTabela('estrategias');
			$sql->adCampo('pg_estrategia_principal_indicador');
			$sql->adOnde('pg_estrategia_id = '.(int)$chave);
			}
		elseif ($campo=='meta'){
			$sql->adTabela('metas');
			$sql->adCampo('pg_meta_principal_indicador');
			$sql->adOnde('pg_meta_id = '.(int)$chave);
			}
		elseif ($campo=='me'){
			$sql->adTabela('me');
			$sql->adCampo('me_indicador');
			$sql->adOnde('me_id = '.(int)$chave);
			}
		elseif ($campo=='projeto'){
			$sql->adTabela('projetos');
			$sql->adCampo('projeto_principal_indicador');
			$sql->adOnde('projeto_id = '.(int)$chave);
			}
		elseif ($campo=='tarefa'){
			$sql->adTabela('tarefas');
			$sql->adCampo('tarefa_principal_indicador');
			$sql->adOnde('tarefa_id = '.(int)$chave);
			}
		elseif ($campo=='plano_acao'){
			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_principal_indicador');
			$sql->adOnde('plano_acao_id = '.(int)$chave);
			}
		elseif ($campo=='plano_acao_item'){
			$sql->adTabela('plano_acao_item');
			$sql->adCampo('plano_acao_item_principal_indicador');
			$sql->adOnde('plano_acao_item_id = '.(int)$chave);
			}
		elseif ($campo=='pratica'){
			$sql->adTabela('praticas');
			$sql->adCampo('pratica_principal_indicador');
			$sql->adOnde('pratica_id = '.(int)$chave);
			}
		elseif ($campo=='swot'){
			$sql->adTabela('swot');
			$sql->adCampo('swot_principal_indicador');
			$sql->adOnde('swot_id = '.(int)$chave);
			}
		elseif ($campo=='problema'){
			$sql->adTabela('problema');
			$sql->adCampo('problema_principal_indicador');
			$sql->adOnde('problema_id = '.(int)$chave);
			}	
		
		
		elseif ($campo=='monitoramento'){
			$sql->adTabela('monitoramento');
			$sql->adCampo('monitoramento_principal_indicador');
			$sql->adOnde('monitoramento_id = '.(int)$chave);
			}	
		elseif ($campo=='tgn'){
			$sql->adTabela('tgn');
			$sql->adCampo('tgn_principal_indicador');
			$sql->adOnde('tgn_id = '.(int)$chave);
			}		
		elseif ($campo=='recurso'){
			$sql->adTabela('recursos');
			$sql->adCampo('recurso_principal_indicador');
			$sql->adOnde('recurso_id = '.(int)$chave);
			}	
		elseif ($campo=='ata'){
			$sql->adTabela('ata');
			$sql->adCampo('ata_principal_indicador');
			$sql->adOnde('ata_id = '.(int)$chave);
			}	
		elseif ($campo=='instrumento'){
			$sql->adTabela('instrumento');
			$sql->adCampo('instrumento_principal_indicador');
			$sql->adOnde('instrumento_id = '.(int)$chave);
			}	
		elseif ($campo=='arquivo'){
			$sql->adTabela('arquivos');
			$sql->adCampo('arquivo_principal_indicador');
			$sql->adOnde('arquivo_id = '.(int)$chave);
			}	
		elseif ($campo=='forum'){
			$sql->adTabela('foruns');
			$sql->adCampo('forum_principal_indicador');
			$sql->adOnde('forum_id = '.(int)$chave);
			}	
		elseif ($campo=='link'){
			$sql->adTabela('links');
			$sql->adCampo('link_principal_indicador');
			$sql->adOnde('link_id = '.(int)$chave);
			}	
		elseif ($campo=='demanda'){
			$sql->adTabela('demandas');
			$sql->adCampo('demanda_principal_indicador');
			$sql->adOnde('demanda_id = '.(int)$chave);
			}	
		elseif ($campo=='evento'){
			$sql->adTabela('eventos');
			$sql->adCampo('evento_principal_indicador');
			$sql->adOnde('evento_id = '.(int)$chave);
			}						
		elseif ($campo=='brainstorm'){
			$sql->adTabela('brainstorm');
			$sql->adCampo('brainstorm_principal_indicador');
			$sql->adOnde('brainstorm_id = '.(int)$chave);
			}	
		elseif ($campo=='gut'){
			$sql->adTabela('gut');
			$sql->adCampo('gut_principal_indicador');
			$sql->adOnde('gut_id = '.(int)$chave);
			}	
		elseif ($campo=='causa_efeito'){
			$sql->adTabela('causa_efeito');
			$sql->adCampo('causa_efeito_principal_indicador');
			$sql->adOnde('causa_efeito_id = '.(int)$chave);
			}	
		elseif ($campo=='checklist'){
			$sql->adTabela('checklist');
			$sql->adCampo('checklist_principal_indicador');
			$sql->adOnde('checklist_id = '.(int)$chave);
			}	
		elseif ($campo=='tr'){
			$sql->adTabela('tr');
			$sql->adCampo('tr_principal_indicador');
			$sql->adOnde('tr_id = '.(int)$chave);
			}	
		elseif ($campo=='canvas'){
			$sql->adTabela('canvas');
			$sql->adCampo('canvas_principal_indicador');
			$sql->adOnde('canvas_id = '.(int)$chave);
			}			
		$indicador = $sql->Resultado();
		$sql->limpar();
		}

	if (!$indicador) return null;
	include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
	$obj_indicador = new Indicador($indicador, $ano, $inicio, $fim);

	$pontos=$obj_indicador->Pontuacao($ano, $inicio, $fim, false);
	$cor=retornar_cor($pontos);
	$indicador='<a href="javascript:void(0);" onclick="url_passar('.($Aplic->profissional ? ($Aplic->getEstado('link_em_janela')  ?  -1 : 0) : 0).', \'m=praticas&a=indicador_ver&pratica_indicador_id='.$indicador.'\');"><span style="border-style:solid; border-color: #000000; border-width:1px; background-color: #'.$cor.'; color: #'.$cor.';">'.dica('Pontuação',number_format((float)$pontos, 2, ',', '.').'% da meta foi alcançada. Clique para ver os detalhes do indicador').'&nbsp;&nbsp;'.dicaF().'</span></a>';
	return $indicador;
	}

function eb_anexo($projeto_id, $tipo){
	$sql = new BDConsulta;
	$sql->adTabela('eb_arquivo');
	$sql->adCampo('eb_arquivo_id, eb_arquivo_usuario, eb_arquivo_data, eb_arquivo_ordem, eb_arquivo_nome, eb_arquivo_endereco');
	$sql->adOnde('eb_arquivo_projeto='.(int)$projeto_id);
	$sql->adOnde('eb_arquivo_artefato=\''.$tipo.'\'');
	$sql->adOrdem('eb_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	$saida='';
	if (count($arquivos)) $saida.='<table cellspacing=0 cellpadding=0><tr><td style="font-size:12pt; font-weight:bold">Anexo'.(count($arquivos)>1 ? 's' : '').':</td></tr>';
	foreach ($arquivos as $arquivo) {
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120">Remetente</td><td>'.nome_funcao('', '', '', '',$arquivo['eb_arquivo_usuario']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;">Anexado em</td><td>'.retorna_data($arquivo['eb_arquivo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para fazer o download do arquivo ou visualizar o mesmo.';
		$saida.= '<tr><td style="font-size:10pt;"><a href="javascript:void(0);" onclick="javascript:url_passar(1, \'m=projetos&a=download_arquivo&u=eb&sem_cabecalho=1&eb_arquivo_id='.(int)$arquivo['eb_arquivo_id'].'\')">'.dica($arquivo['eb_arquivo_nome'],$dentro).$arquivo['eb_arquivo_nome'].'</a></td></tr>';
		}
	if (count($arquivos)) $saida.='</table>';
	return $saida;
	}

/**
* Cria um código de segurança adicional para verificar se o navegador
* é o mesmo que executou o login.
*
* O código é armazenado no cookie do navegador.
*/
function gpwCriarCodigoSeguranca(){
    global $_GPWEB_CODIGO_SEGURANCA_;
    $key = md5(uniqid(rand(), true));
    setcookie('gpweb_seckey', $key);
    $_SESSION['gpweb_seckey'] = $key;
    $_GPWEB_CODIGO_SEGURANCA_ = $key;
    }

/**
* Verifica se o navegador de origem é o mesmo que efetuou o login da sessão.
*
* Verifica se o cookie da chave de segurança é o mesmo que foi armazenado na sessão.
*
* @return {Boolean} true se o código enviado pelo navegador corresponde ao armazenado na sessão,
* false caso contrário.
*/
function gpwVerificaCodigoSeguranca(){
    global $_GPWEB_CODIGO_SEGURANCA_;
    if(!$_GPWEB_CODIGO_SEGURANCA_ || $_SESSION['gpweb_seckey'] != $_GPWEB_CODIGO_SEGURANCA_){
        return false;
        }
    gpwCriarCodigoSeguranca();
    return true;
    }

if(file_exists(BASE_DIR.'/incluir/ext_util_pro.php')) require_once (BASE_DIR.'/incluir/ext_util_pro.php');

?>