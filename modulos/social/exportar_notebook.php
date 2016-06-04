<?php

global $config;
if (!($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'acesso', $Aplic->usuario_id, 'gera_notebook')) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
include_once BASE_DIR.'/modulos/social/funcoes.php';

$exportar = getParam($_REQUEST, 'exportar', 0);
 
$botoesTitulo = new CBlocoTitulo('Gerar Arquivo de Preparação de Dispositivo Off-Line', 'importar.gif', $m, "$m.$a");
$botoesTitulo->adicionaBotao('m=sistema&a=index&u=', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar(); 
 
 
 if ($exportar){
	
	$nome=exportar_social();
	
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20 align=center><table><tr><td align=right>Arquivo criado:</td><td><b><a href="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/temp/'.$nome.'.zip">'.$nome.'.zip</a></b></td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	}


echo '<form name="env" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="exportar" value="0" />'; 
 
if(!$exportar){
	echo estiloTopoCaixa();
	echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';
	echo'<tr><td colspan=20><table align=center><tr><td>'.botao('gerar arquivo', 'Gerar Arquivo', 'Clique neste botão para gerar o arquivo o arquivo de praparação dos dispositivos que irão trabalhar off-line no cadastramento de beneficiários, com os programas, as ações, comitês e comunidades.','','env.exportar.value=1; env.submit()').'</td></tr></table></td></tr>';
	echo estiloFundoCaixa();
	} 
 
 
 
 
 
echo '</form>';



 
 
 

 
 
 
?>