<?php  
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$modelo_anexo_id=getParam($_REQUEST, 'modelo_anexo_id', 0);
$excluir=getParam($_REQUEST, 'excluir', 0);
$posicao=getParam($_REQUEST, 'posicao', 0);
$idunico=getParam($_REQUEST, 'idunico', null);
$sql = new BDConsulta;
$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
if ($excluir){
	$sql->adTabela('modelos_anexos');
	$sql->adCampo('caminho, modelo_id');
	$sql->adOnde('modelo_anexo_id='.$modelo_anexo_id);
	$linha=$sql->Linha();
	$sql->limpar();
	$modelo_id=(int)$linha['modelo_id'];
	$caminho=str_replace('/', '\\', $linha['caminho']);
	if (file_exists($base_dir.'\\'.$config['pasta_anexos'].'_modelos'.'\\'.$caminho))	@unlink($base_dir.'\\'.$config['pasta_anexos'].'_modelos'.'\\'.$caminho);
	$sql->setExcluir('modelos_anexos');
	$sql->adOnde('modelo_anexo_id = '.$modelo_anexo_id);
	if (!$sql->exec()) echo db_error();
	$sql->limpar();

	$sql->adTabela('modelos_anexos');
	$sql->adUnir('usuarios','usuarios', 'modelos_anexos.usuario_id=usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('nome_fantasia, modelo_anexo_id, nome, caminho, modelos_anexos.usuario_id, nome_de, funcao_de, tipo_doc, doc_nr, data_envio, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	if ($modelo_id) $sql->adOnde('modelo_id = '.(int)$modelo_id);
	else $sql->adOnde('idunico = "'.$idunico.'"');
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

$sql->adTabela('modelos_anexos');
$sql->adUnir('usuarios','usuarios', 'modelos_anexos.usuario_id=usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('nome_fantasia, modelo_anexo_id, nome, caminho, modelos_anexos.usuario_id, nome_de, funcao_de, tipo_doc, doc_nr, data_envio, contato_funcao, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adOnde('modelo_anexo_id = '.$modelo_anexo_id);
$anexo = $sql->Linha();
$sql->limpar();



echo '<form method="POST" name="env" id="env" enctype="multipart/form-data">';
echo '<input type=hidden name="m" value="email">';
echo '<input type=hidden name="a" value="modelo_excluir_anexo">';
echo '<input type=hidden id="modelo_anexo_id" name="modelo_anexo_id" value="'.$modelo_anexo_id.'">';
echo '<input type=hidden id="idunico" name="idunico" value="'.$idunico.'">';
echo '<input type=hidden id="posicao" name="posicao" value="'.$posicao.'">';
echo '<input type=hidden id="excluir" name="excluir" value="">';
echo '<input type=hidden id="dialogo" name="dialogo" value="1">';

echo estiloTopoCaixa(500); 
echo '<table class="std" align="center" cellspacing="3" width="500"  cellpadding=0>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td colspan=2 align="center"><h1>'.($anexo['nome_fantasia'] ? $anexo['nome_fantasia'] : $anexo['nome']).'</h1></td></tr>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '<tr><td align="right" width="150"><b>Remetente</b>:</td><td>'.($Aplic->usuario_prefs['nomefuncao'] ? $anexo['nome_usuario'].($anexo['contato_funcao'] && $anexo['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $anexo['contato_funcao'] : '') : $anexo['contato_funcao'].($anexo['nome_usuario'] && $anexo['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $anexo['nome_usuario'] : '')).'</td></tr>';
echo '<tr><td align="right"><b>Anexado em</b>:</td><td>'.retorna_data($anexo['data_envio']).'</td></tr>';
if ($anexo['doc_nr']) echo '<tr><td align="right"><b>Referência</b>:</td><td>'.$anexo['doc_nr'].'</td></tr>';
if ($anexo['tipo_doc']) echo '<tr><td align="right"><b>Tipo</b>:</td><td>'.$anexo['tipo_doc'].'</td></tr>';
echo '<tr><td colspan=2 align="center">&nbsp;</td></tr>';
echo '<tr><td colspan=2 align="center"><h1>Tem certeza que deseja excluir?</h1></td></tr>';
echo '<tr><td colspan=2 align="center">&nbsp;</td></tr>';
echo '<tr><td colspan="2" align="center"><table><tr><td><a class="botao" href="javascript:void(0);" onclick="javascript:env.excluir.value=1; env.submit();"><span><b>Sim</b></span></a></td><td width="100">&nbsp;</td><td><a class="botao" href="javascript:void(0);" onclick="javascript:window.close();"><span><b>Não</b></span></a></td></tr></table></td></tr>';
echo '<tr><td colspan=2>&nbsp;</td></tr>';
echo '</table>'; 
echo estiloFundoCaixa(500); 			 
echo '</form>'

?>