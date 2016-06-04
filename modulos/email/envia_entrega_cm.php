<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$usuario_id=getParam($_REQUEST, 'usuario_id', 0);

$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);

$sql = new BDConsulta;
$sql->adTabela('msg_usuario');
$sql->adCampo('msg_id');
$sql->adOnde('msg_usuario_id ='.$msg_usuario_id);
$msg_id = $sql->Resultado();
$sql->limpar();


$entrega= getSisValor('EntregaCM');
echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="a" name="a" value="grava_entrega_cm">';	
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id" value="'.$msg_usuario_id.'">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="'.$usuario_id.'">';
echo '<input type=hidden name="msg_id" id="msg_id" value="'.$msg_id.'">';
echo estiloTopoCaixa(500);
echo '<table width="500" align="center" border=0 class="std" cellspacing=0 cellpadding=0 >';
echo '<tr width="100%"><td colspan="2" style="text-align:center; font-size:15px; font-weight: bold">Entrega pelo CM Msg '.$msg_id.'</td></tr>';
echo '<tr><td width="140" align="right">'.ucfirst($config['usuario']).':</td><td><font  size="2">'.nome_funcao('','','','',$usuario_id).'</td>';
echo '<tr><td width="140" align="right">Meio:</td><td>'.selecionaVetor($entrega, 'meio', 'class="texto" size=1', '', true).'</td></tr>';
echo '<tr><td  width="140" align="right">Data/Hora Entrega:</td><td><input type="text" class="texto" name="data_entrega" size="25" value="'.date('Y-m-d H:i').'"></td></tr>';
echo '<tr><td colspan="2"><table><tr><td width="170">&nbsp;</td><td>'.botao('cadastrar entrega', 'Cadastrar Entrega', 'Clique neste botão para confirmar a leitura d'.$config['genero_mensagem'].' '.$config['mensagem'].' entregue pelo Centro de Mensagens por outro meio que não seja este Sistema.','','env.submit();').'</td></tr></table></td></tr>';
echo '</table>';
echo estiloFundoCaixa(500);
echo '</form>';
?>
