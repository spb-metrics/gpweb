<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

include_once $Aplic->getClasseBiblioteca('xajax/xajax_core/xajax.inc');
$xajax = new xajax();

	
function mudar_usuario_grupo_ajax($grupo_id=0){
	global $Aplic, $config;

	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('usuario_ativo=1');	
	
	if ($grupo_id > 0) $sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
	elseif($grupo_id==-1) $sql->adOnde('contato_cia='.(int)$Aplic->usuario_cia);
	
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
  //EUZ
	//$sql->adGrupo('usuario_id');
  $sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor, cias.cia_nome');
  //EUD

	$usuarios = $sql->Lista();
	$sql->limpar();

	$saida='<select name="ListaDE[]" id="ListaDE" multiple size=12 style="width:100%;" class="texto" ondblClick="javascript:Mover(edittask.ListaDE, edittask.ListaPARA); return false;">';
 	foreach ($usuarios as $rs) {
 		$nome=($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['nome_usuario'] : '')).' - '.$rs['cia_nome'];
 		$saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode($nome).'</option>';
		}
	$saida.='</select>';

	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_de',"innerHTML", $saida);
	return $objResposta;
	}	
	


function mudar_destinatarios_ajax($tarefa=0){
	global $Aplic, $config;

	$sql = new BDConsulta;
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
	$sql->esqUnir('parafazer_usuarios','parafazer_usuarios','parafazer_usuarios.usuario_id=usuarios.usuario_id');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuarios.usuario_id, contato_posto_valor, cia_nome');
	$sql->adOnde('parafazer_usuarios.id='.$tarefa);
	$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));
  //EUZ //$sql->adGrupo('usuario_id');
  $sql->adGrupo('usuarios.usuario_id, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, contatos.contato_posto_valor, cias.cia_nome');
  //EUD
	$usuarios = $sql->Lista();
	$sql->limpar();

	$saida='<select name="ListaPARA[]" id="ListaPARA" class="texto" size=12 style="width:100%;" multiple ondblClick="javascript:Mover2(edittask.ListaPARA, edittask.ListaDE); return false;">';
 	foreach ($usuarios as $rs) {
 		$nome=($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['nome_usuario'] : '')).' - '.$rs['cia_nome'];
 		$saida.='<option value="'.$rs['usuario_id'].'">'.utf8_encode($nome).'</option>';
		}
	$saida.='</select>';
	$objResposta = new xajaxResponse();
	$objResposta->assign('combo_para',"innerHTML", $saida);
	return $objResposta;
	}		
	
$xajax->registerFunction("mudar_usuario_grupo_ajax");	
$xajax->registerFunction("mudar_destinatarios_ajax");
$xajax->processRequest();
?>