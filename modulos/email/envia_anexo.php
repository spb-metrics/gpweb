<?php  
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$msg_id=getParam($_REQUEST, 'msg_id', 0);
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);

$status=getParam($_REQUEST, 'status', 0);
$senha=getParam($_REQUEST, 'senha', '');
$cripto=getParam($_REQUEST, 'cripto', '');
$retornar=getParam($_REQUEST, 'retornar', '');

echo '<form method="POST" name="env" id="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="grava_anexo">';
echo '<input type=hidden id="status" name="status" value="'.$status.'">';
echo '<input type=hidden id="msg_id" name="msg_id" value="'.$msg_id.'">';
echo '<input type=hidden id="msg_usuario_id" name="msg_usuario_id" value="'.$msg_usuario_id.'">';

echo '<input type=hidden id="senha" name="senha" value="'.$senha.'">';
echo '<input type=hidden id="msg_id_cripto" name="msg_id_cripto" value="'.$msg_id.'">';
echo '<input type=hidden id="cripto" name="cripto" value="'.$cripto.'">';


echo estiloTopoCaixa(800); 
echo '<table class="std" align="center" cellspacing=0 width="800"  cellpadding=0>';
echo '<tr><td>&nbsp;</td></tr>';	
echo '<tr><td align="center"><b>'.ucfirst($config['mensagem']).' '.$msg_id.'</b></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';	

echo '<tr><td colspan="3" align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Anexar arquivos','Clique neste link para anexar um arquivo a '.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.<br>Caso necessite anexar multiplos arquivos basta clicar aqui sucessivamente para criar os campos necess�rios.').'<b>Anexar arquivos</b>'.dicaF().'</a></td></tr>';


echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><tbody name="div_anexos" id="div_anexos"></tbody></table></td></tr>';

echo '<tr><td>&nbsp;</td></tr>';	
echo '<tr><td><table align="right"><tr><td>&nbsp;&nbsp;'.dica('Notificar o Criador da Mensgem','Selecione esta caixa caso deseje que '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' seja enviad'.$config['mensagem'].' ao criador d'.$config['genero_mensagem'].' '.$config['mensagem'].' notificando sobre a inclus�o do(s) anexo(s).').'<b>Notificar o criador d'.$config['genero_mensagem'].' '.$config['mensagem'].':</b>'.dicaF().'<input type="checkbox" name="notifica_criador_anexo" size=50 value=1></td><td width="260">&nbsp;</td></tr></table></td></tr>';	
echo '<tr><td><table align="right"><tr><td>&nbsp;&nbsp;'.dica('Notificar os Demais Destinat�rios d'.$config['genero_mensagem'].' '.ucfirst($config['mensagem']),'Selecione esta caixa caso deseje que todos os destinat�rios d'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].' sejam notificandos sobre a inclus�o do(s) anexo(s).').'<b>Notificar os demais destinat�rios d'.$config['genero_mensagem'].' '.$config['mensagem'].':</b>'.dicaF().'<input type="checkbox" name="notifica_destinatarios_anexo" size=50 value=1 ></td><td width="260">&nbsp;</td></tr></table></td></tr>';		
echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td colspan="3" align="center"><table width="100%"><tr><td>'.($retornar ? botao('retornar', 'Retornar','Ao se pressionar este bot�o ir� retornar a tela anterior.','','env.a.value=\''.$retornar.'\'; env.submit();') : '').'</td><td><a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>anexar arquivos</b></span></a></td></tr></table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>'; 
echo estiloFundoCaixa(800); 			 
echo '</form>'?>

<script type="text/javascript">
	
function incluir_arquivo(){
	var r  = document.createElement('tr');
  var ca = document.createElement('td');
	var ta = document.createTextNode('Tipo:');
	myselect = document.createElement("select");
	myselect.className="texto";
	myselect.style.width="90px";
	myselect.name="doc_tipo[]";
	ca.appendChild(ta);
	<?php 
	foreach (getSisValor('tipo_anexo','','','sisvalor_id ASC') as $chave => $valor){
		echo 'theOption=document.createElement("OPTION");';
		echo 'theText=document.createTextNode("'.$valor.'");';
		echo 'theOption.setAttribute("value","'.$chave.'");';
		echo 'theOption.appendChild(theText);';
		echo 'myselect.appendChild(theOption);';
		}
	?>	
	ca.appendChild(myselect);
	
	var ta = document.createTextNode(' N�:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc_nr[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=2;
	campo.className="texto";
	ca.appendChild(campo);
	
	var ta = document.createTextNode(' Nome:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'nome_fantasia[]';
	campo.type = 'text';
	campo.value = '';
	campo.size=10;
	campo.className="texto";
	ca.appendChild(campo);
	
	var ta = document.createTextNode(' Arq:');
	ca.appendChild(ta);
	var campo = document.createElement("input");
	campo.name = 'doc[]';
	campo.type = 'file';
	campo.value = '';
	campo.size=30;
	campo.className="texto";
	ca.appendChild(campo);
	
	r.appendChild(ca);

	var aqui = document.getElementById('div_anexos');
	aqui.appendChild(r);
	}
	
</script>	