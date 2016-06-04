<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$observado_id = getParam($_REQUEST, 'observado_id', 0);
$sql = new BDConsulta;

//excluir o projeto da lista
if ($observado_id){
	$sql->setExcluir('projeto_observado');
	$sql->adOnde('projeto_id = '.$observado_id);
	$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
	$sql->exec();
	$sql->limpar();
	
	}



$sql->adTabela('projeto_observado');
$sql->adCampo('projeto_id');
$sql->adOnde('aprovado = 1');
$sql->adOnde('cia_para ='.$Aplic->usuario_cia);
$lista=$sql->Lista();
$sql->limpar();



echo '<center><h1>Retirar '.$config['projeto'].' da lista de observad'.$config['genero_projeto'].'s</h1></center>';
echo estiloTopoCaixa(600);

echo '<form id="frm_administrar" name="frm_administrar" method="post">';
echo '<input type="hidden" name="m" value="projetos" />';
echo '<input type="hidden" name="a" value="administrar_projetos" />';
echo '<input type="hidden" name="observado_id" value="" />';


echo '<table width="600" cellpadding=2 cellspacing=0 class="std" align=center>';

foreach($lista as $linha) echo '<tr><td width="16"><a href="javascript:void(0);" onclick="javascript:if (confirm( \'Tem certeza que deseja retirar '.$config['genero_projeto'].' '.$config['projeto'].'?\')){frm_administrar.observado_id.value='.$linha['projeto_id'].'; frm_administrar.submit();}">'.imagem('icones/cancelar.png', 'Retirar', 'Clique neste �cone '.imagem('icones/cancelar.png').' para retirar '.$config['genero_projeto'].' '.$config['projeto'].' da lista de observados.').'</a></td><td>'.link_projeto($linha['projeto_id'], true).'</td></tr>';
echo '<tr><td colspan=2>'.botao('voltar','Voltar','Pressione este bot�o para voltar a lista de '.$config['projeto'].'.','','frm_administrar.a.value=\'index\';frm_administrar.submit();').'</td></tr>';
echo '</table>';
echo '</form>';
echo estiloFundoCaixa(600);
?>