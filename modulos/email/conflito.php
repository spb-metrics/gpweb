<?php 
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
global $Aplic;
$Aplic->carregarCalendarioJS();

$obj = new CAgenda();
$objeto = getParam($_REQUEST, 'objeto', null);
if($objeto){
	$objeto = base64_decode($objeto);
	if(get_magic_quotes_gpc()) $objeto = stripslashes($objeto);
	}
if (getParam($_REQUEST, 'aceitar', null)){
	$_REQUEST=unserialize($objeto);
	$obj->join($_REQUEST);
	
	if ($obj->agenda_inicio) {
		$data_inicio = new CData($obj->agenda_inicio.getParam($_REQUEST, 'inicio_hora', null));
		$obj->agenda_inicio = $data_inicio->format('%Y-%m-%d %H:%M:%S');
		}
	if ($obj->agenda_fim) {
		$data_fim = new CData($obj->agenda_fim.getParam($_REQUEST, 'fim_hora', null));
		$obj->agenda_fim = $data_fim->format('%Y-%m-%d %H:%M:%S');
		}
	
	$obj->armazenar();

	require_once $Aplic->getClasseSistema('CampoCustomizados');
	$campos_customizados = new CampoCustomizados('agenda', $obj->agenda_id, 'editar');
	$campos_customizados->join($_REQUEST);
	$sql = $campos_customizados->armazenar($obj->agenda_id);
	$Aplic->setMsg($_REQUEST['agenda_id'] ? 'Compromisso atualizado' : 'Compromisso adicionado', UI_MSG_OK, true);
	if (isset($_REQUEST['agenda_designado']) && $_REQUEST['agenda_designado']) $obj->atualizarDesignados(explode(',', getParam($_REQUEST, 'agenda_designado', null)));
	if ((isset($_REQUEST['agenda_inicio_antigo']) && $_REQUEST['agenda_inicio_antigo']!=$_REQUEST['agenda_inicio']) || (isset($_REQUEST['agenda_fim_antigo']) && $_REQUEST['agenda_fim_antigo']!=$_REQUEST['agenda_fim'])) $obj->atualizarDuracao(explode(',', getParam($_REQUEST, 'agenda_designado', null)));
	if (isset($_REQUEST['email_convidado'])) $obj->notificar(getParam($_REQUEST, 'agenda_designado', null), getParam($_REQUEST, 'agenda_id', null));
	$obj->adLembrete();
	$Aplic->redirecionar('m=email&a=ver_mes');
	exit();
	}



$botoesTitulo = new CBlocoTitulo(($obj->agenda_id ? 'Conflito na Edi��o do Compromisso' : 'Conflito na Adi��o do Compromisso'), 'calendario.png', $m, $m.'.'.$a);
$botoesTitulo->mostrar();


echo '<form name="env" method="POST" enctype="multipart/form-data">';
echo '<input type="hidden" name="m" value="email" />';
echo '<input type="hidden" name="a" value="conflito" />';
echo '<input type="hidden" name="u" value="" />';
echo '<input type="hidden" name="aceitar" value="1" />';
echo '<input type="hidden" name="objeto" value=\''.base64_encode($objeto).'\' />';
echo '</form>';

$conflito = getParam($_REQUEST, 'conflito', null);
$conflito =explode(',',$conflito);
$_REQUEST=unserialize($objeto);

echo estiloTopoCaixa();
echo '<table width="100%" class="std"><tr><td><b>Os seguintes '.$config['usuarios'].' n�o est�o dispon�veis para a data proposta</b></tr></tr>';
foreach ($conflito as $usuario) echo '<tr><td colspan=20>'.$usuario.'</td></tr>';
echo '<tr><td><table><tr>
<td>'.botao('editar evento', 'Editar evento','Clique neste bot�o para editar o evento.','','env.a.value=\'editar_compromisso\'; env.submit();', '','','',0).'</td>
<td>'.botao('registar evento', 'Registar evento','Clique neste bot�o para registar a '.($_REQUEST['agenda_id'] ? 'altera��o' : 'inclus�o').' deste evento apesar do conflito.','','env.submit();','','',0).'</td>
<td>'.botao('cancelar', 'Cancelar','Clique neste bot�o para cancelar a '.($_REQUEST['agenda_id'] ? 'edi��o' : 'inclus�o').' deste evento.','','url_passar(0, \''.$Aplic->getPosicao().'\');','','',0).'</td>
</tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa();
?>