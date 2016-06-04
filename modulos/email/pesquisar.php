<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$Aplic->carregarCalendarioJS();

$data_inicio = new CData(date("Y-m-d H:i:s"));
$data_fim = new CData(date("Y-m-d H:i:s"));

echo estiloTopoCaixa(620); 

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="a" name="a" value="lista_msg">';	
echo '<input type="hidden" name="status" value="10">';
echo '<table class="std" width="620" align="center" border=0 cellpadding=0 cellspacing=0 >';
echo '<tr><td colspan="2"><hr color="#000000"></td></tr>';
echo '<tr><td align="center" colspan="2"><b><font size="4">Pesquisar '.ucfirst($config['mensagens']).'</font></b></td></tr>';
echo '<tr><td colspan="2"><hr color="#000000"></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td align="right">'.dica('Texto à Pesquisar','Escreva a palavra chave a ser pesquisa n'.$config['genero_mensagem'].'s '.$config['mensagens'].' do sistema.').'Texto:'.dicaF().'</td><td><input type="text" class="texto" name="assunto" id="assunto" size="60"></td></tr>';
echo '<tr><td align="center" colspan="2"> </td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Data de Início', 'Digite ou escolha no calendário a data de início da pesquisa.').'De:'.dicaF().'</td><td nowrap="nowrap"><input type="hidden" name="pesquisa_inicio" id="pesquisa_inicio" value="" /><input type="text" name="data_inicio" style="width:70px;" id="data_inicio" onchange="setData(\'env\', \'data_inicio\', \'pesquisa_inicio\');" value="" class="texto" />'.dica('Data Inicial', 'Clique neste ícone '.imagem('icones/calendario.gif').' para abrir um calendário onde poderá selecionar a data de início deste evento.').'<a href="javascript: void(0);" ><img id="f_btn1" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().dica('Data de Término', 'Digite ou escolha no calendário a data de término da pesquisa.').'&nbsp;&nbsp;a&nbsp;&nbsp;'.dicaF().'<input type="hidden" name="pesquisa_fim" id="pesquisa_fim" value="" /><input type="text" name="data_fim" id="data_fim" style="width:70px;" onchange="setData(\'env\', \'data_fim\', \'pesquisa_fim\');" value="" class="texto" />'.dica('Data de Término', 'Clique neste ícone '.imagem('icones/calendario.gif').'  para abrir um calendário onde poderá selecionar a data de término deste evento.').'<a href="javascript: void(0);" ><img id="f_btn2" src="'.acharImagem('calendario.gif').'" style="vertical-align:middle" alt="Agenda"" border=0 /></a>'.dicaF().'</td></tr>';
echo '<tr><td align="right">'.dica('Enviadas Por','Selecione '.$config['genero_mensagem'].'s '.$config['mensagens'].' que tenham sido enviad'.$config['genero_mensagem'].'s pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado à direita.').'Enviadas por:'.dicaF().'</td><td colspan="2"><input type="hidden" id="cia_usuario_enviou" name="cia_usuario_enviou" value="" /><input type="text" id="nome_enviou" name="nome_enviou" value="" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popEnviada();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right">'.dica('Recebidas Por','Selecione '.$config['genero_mensagem'].'s '.$config['mensagens'].' que tenham sido recebid'.$config['genero_mensagem'].'s pel'.$config['genero_usuario'].' '.$config['usuario'].' selecionado à direita.').'Recebidas por:'.dicaF().'</td><td colspan="2"><input type="hidden" id="cia_usuario_recebeu" name="cia_usuario_recebeu" value="" /><input type="text" id="nome_recebeu" name="nome_recebeu" value="" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popRecebida();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<tr><td align="right">'.dica('Criador','Selecione qual '.$config['usuario'].' criou '.$config['genero_mensagem'].' '.$config['mensagem'].'.').'Criador d'.$config['genero_mensagem'].' '.$config['mensagem'].':'.dicaF().'</td><td colspan="2"><input type="hidden" id="cia_usuario_criou" name="cia_usuario_criou" value="" /><input type="text" id="nome_criador" name="nome_criador" value="" style="width:284px;" class="texto" READONLY /><a href="javascript: void(0);" onclick="popCriador();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
echo '<td>&nbsp;</td><td>'.dica('Pesquisar '.ucfirst($config['mensagens']).'','Clique neste botão para efetuar a pesquisa n'.$config['genero_mensagem'].'s '.$config['mensagens'].'.').'<a class="botao" href="javascript:void(0);" onclick="javascript:env.submit();"><span><b>pesquisar</b></span></a></td></tr>';
echo '<tr><td>&nbsp;</td></tr></form></table>';
echo estiloFundoCaixa(620); 



?>
<script language=Javascript>

function popEnviada(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setEnviada&usuario_id='+document.getElementById('cia_usuario_enviou').value, window.setEnviada, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setEnviada&usuario_id='+document.getElementById('cia_usuario_enviou').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setEnviada(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('cia_usuario_enviou').value=usuario_id;		
	document.getElementById('nome_enviou').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}

function popRecebida() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setRecebida&usuario_id='+document.getElementById('cia_usuario_recebeu').value, window.setRecebida, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setRecebida&usuario_id='+document.getElementById('cia_usuario_recebeu').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setRecebida(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('cia_usuario_recebeu').value=usuario_id;		
	document.getElementById('nome_recebeu').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}
	
function popCriador() {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('<?php echo ucfirst($config["usuario"])?>', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('cia_usuario_criou').value, window.setCriador, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setCriador&usuario_id='+document.getElementById('cia_usuario_criou').value, '<?php echo ucfirst($config["usuario"])?>','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}

function setCriador(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('cia_usuario_criou').value=usuario_id;		
	document.getElementById('nome_criador').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	}		
	
	
	
	
	
var cal1 = Calendario.setup({
  	trigger    : "f_btn1",
    inputField : "pesquisa_inicio",
  	date :  <?php echo $data_inicio->format("%Y%m%d")?>,
  	selection: <?php echo $data_inicio->format("%Y%m%d")?>,
    onSelect: function(cal1) { 
    var date = cal1.selection.get();
    if (date){
    	date = Calendario.intToDate(date);
      document.getElementById("data_inicio").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pesquisa_inicio").value = Calendario.printDate(date, "%Y%m%d");
      }
  	cal1.hide(); 
  	}
  });
  
	var cal2 = Calendario.setup({
		trigger : "f_btn2",
    inputField : "pesquisa_fim",
		date : <?php echo $data_fim->format("%Y%m%d")?>,
		selection : <?php echo $data_fim->format("%Y%m%d")?>,
    onSelect : function(cal2) { 
    var date = cal2.selection.get();
    if (date){
      date = Calendario.intToDate(date);
      document.getElementById("data_fim").value = Calendario.printDate(date, "%d/%m/%Y");
      document.getElementById("pesquisa_fim").value = Calendario.printDate(date, "%Y%m%d");
      }
  	cal2.hide(); 
  	}
  });	
	
function setData(frm_nome, f_data, f_data_real) {
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	campo_data_real = eval( 'document.' + frm_nome + '.' + f_data_real );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada não corresponde ao formato padrão. Redigite, por favor.');
      campo_data_real.value = '';
      campo_data.style.backgroundColor = 'red';
  		} 
    else{
    	campo_data_real.value = formatarData(parsfimData(campo_data.value), 'yyyy-MM-dd');
    	campo_data.value = formatarData(parsfimData(campo_data.value), 'dd/MM/Y');
      campo_data.style.backgroundColor = '';
			}
		} 
	else campo_data_real.value = '';
	}
		
	

</script>	
