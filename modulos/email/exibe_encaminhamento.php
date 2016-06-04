<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (isset($_REQUEST['vetor_msg_usuario'])) $vetor_msg_usuario = getParam($_REQUEST, 'vetor_msg_usuario', null); 
else if (isset($_REQUEST['msg_usuario_id'])) $vetor_msg_usuario[] = getParam($_REQUEST, 'msg_usuario_id', null);
else  $vetor_msg_usuario = array();
$msg_usuario_id=reset($vetor_msg_usuario);
$tipos_status=array('' => 'indefinido') + getSisValor('status');
$primeiro=0;

echo estiloTopoCaixa(); 	
echo '<table align="center" class="std" cellspacing=0 width="100%" cellpadding=0>';
echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Encaminhamentos</b></td></tr>';
echo '<tr><td><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">De</td><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">Para</td><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">Data de Envio</td><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">Data de Leitura</td><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;">Status</td></tr>'; 	
$sql = new BDConsulta; 	
$sql->adTabela('msg_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
$sql_resultadosf = $sql->Lista();
$sql->limpar();
foreach ($sql_resultadosf as $rs_enc){ 
  if (($rs_enc['copia_oculta'] !=1) || ($rs_enc['de_id']==$Aplic->usuario_id || $rs_enc['para_id']==$Aplic->usuario_id )) {
    echo '<tr>';
    echo '<td style="font-size:7pt; padding-left: 3px; padding-right: 3px;">'.nome_funcao($rs_enc['nome_de'], $rs_enc['nome_usuario'], $rs_enc['funcao_de'], $rs_enc['contato_funcao']).'</td>';
    echo '<td style="font-size:7pt; padding-left: 3px; padding-right: 3px;">'.($rs_enc['copia_oculta']==1 ? '<i>' : '').nome_funcao($rs_enc['nome_para'], '', $rs_enc['funcao_para'], '', $rs_enc['para_id']).($rs_enc['copia_oculta']==1 ? '</i>' : '').'</td>';
    echo "<td style='font-size:7pt; padding-left: 3px; padding-right: 3px;'>".retorna_data($rs_enc['datahora']).'</td>';
    echo "<td style='font-size:7pt; padding-left: 3px; padding-right: 3px;'>";
		if (!$rs_enc['datahora_leitura'])	echo ($Aplic->usuario_admin ? '<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'env\').action=\'envia_entrega_cm.php\'; document.getElementById(\'usuario_id\').value='.$rs_enc['para_id'].';document.getElementById(\'env\').submit();">Não Lida</a>' : 'Não Lida');
		else echo retorna_data($rs_enc['datahora_leitura']).($rs_enc['cm'] == 1 ? '(CM:'.nome_usuario($rs_enc['cm']).' por '.$rs_enc['meio'].')' : '');
		echo '</td>';
		echo '<td style="font-size:7pt; padding-left: 3px; padding-right: 3px;">'.$tipos_status[$rs_enc['status']].'</td></tr>';
		}
	}
echo '</table></td></tr><tr><td>&nbsp;</td></tr></table>';	
echo estiloFundoCaixa();



?>