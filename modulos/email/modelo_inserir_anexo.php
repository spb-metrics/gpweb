<?php  
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$modelo_id=getParam($_REQUEST, 'modelo_id', null);
$posicao=getParam($_REQUEST, 'posicao', 0);
$inserir=getParam($_REQUEST, 'inserir', 0);
$idunico=getParam($_REQUEST, 'idunico', null);
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
if ($inserir){
	grava_anexo_modelo($modelo_id, $idunico, 'doc', getParam($_REQUEST, 'doc_nr', ''), getParam($_REQUEST, 'doc_tipo', ''),  getParam($_REQUEST, 'nome_fantasia', ''));
	$sql = new BDConsulta;
	$sql->adTabela('modelos_anexos');
	$sql->adUnir('usuarios','usuarios', 'modelos_anexos.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('nome_fantasia, modelo_anexo_id, nome, caminho, modelos_anexos.usuario_id, nome_de, funcao_de, tipo_doc, doc_nr, data_envio, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	if ($modelo_id) $sql->adOnde('modelo_id = '.(int)$modelo_id);
	else $sql->adOnde('idunico = \''.$idunico.'\'');
	$anexos = $sql->Lista();

	$sql->limpar();
	$saida='';
	foreach($anexos as $rs_anexo){
				$saida.='<div><a href="javascript:void(0);">&nbsp;</a><a href="javascript:void(0);" onclick="window.open(\''.$base_url.'/'.($config['pasta_anexos'] ? $config['pasta_anexos'].'_modelo/':'').$rs_anexo['caminho'].'\')">'.($rs_anexo['nome_fantasia'] ? $rs_anexo['nome_fantasia'] : $rs_anexo['nome']).'</a>&nbsp;<a href="javascript:void(0);" onclick="popRenomear('.$rs_anexo['modelo_anexo_id'].', '.$posicao.')">'.imagem('icones/editar.gif').'</a><a href="javascript:void(0);" onclick="popExcluir('.$rs_anexo['modelo_anexo_id'].', '.$posicao.')">&nbsp;'.imagem('icones/excluir.gif').'</a></div>';
		}
	$saida=addslashes($saida);
	
	?>
	<script language="javascript">
		try {
   		if(parent && parent.gpwebApp) parent.gpwebApp._popupCallback('<?php echo $saida?>', <?php echo $posicao?>);
   		else window.opener.reescrever_anexos('<?php echo $saida?>', <?php echo $posicao?>); 
		 	} 
		catch(e) {
		  alert("falha");
		 	} 
		finally {
		 	window.close();
		 	} 
	</script>
	<?php
	}

echo '<form method="POST" name="env" id="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="modelo_inserir_anexo">';
echo '<input type=hidden id="modelo_id" name="modelo_id" value="'.$modelo_id.'">';
echo '<input type=hidden id="idunico" name="idunico" value="'.$idunico.'">';
echo '<input type=hidden id="posicao" name="posicao" value="'.$posicao.'">';
echo '<input type=hidden id="inserir" name="inserir" value="">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';


echo estiloTopoCaixa(); 
echo '<table class="std" align="center" cellspacing=0 width="100%"  cellpadding=0>';
echo '<tr><td>&nbsp;</td></tr>';	
echo '<tr><td align="center"><b>Documento '.($modelo_id ? 'NR '.$modelo_id : 'Novo').'</b></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';	

echo '<tr><td colspan="3" align="center"><a href="javascript: void(0);" onclick="javascript:incluir_arquivo();">'.dica('Selecionar Arquivos','Clique neste link para selecionar um arquivo a ser anexado a este documento.<br>Caso necessite anexar multiplos arquivos basta clicar aqui sucessivamente para criar os campos necess�rios.').'<b>selecionar arquivo</b>'.dicaF().'</a></td></tr>';
echo '<tr><td colspan="20" align="center"><table cellpadding=0 cellspacing=0><div name="div_anexos" id="div_anexos"></div></table></td></tr>';

echo '<tr><td>&nbsp;</td></tr>';
echo '<tr><td colspan=20><table width="100%"><tr><td><a class="botao" href="javascript:void(0);" onclick="anexar();"><span><b>anexar</b></span></a></td><td width="90%">&nbsp;</td><td align="right"><a class="botao" href="javascript:void(0);" onclick="javascript:window.close();"><span><b>cancelar</b></span></a></td></tr></table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>'; 
echo estiloFundoCaixa(); 			 
echo '</form>'
?>

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
	campo.size=3;
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

function anexar(){
	env.inserir.value=1; 
	env.submit();
	}
	

	
</script>	