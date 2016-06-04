<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


$social_acao_conceder_id= getParam($_REQUEST, 'social_acao_conceder_id', null);
if ($social_acao_conceder_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_acao_conceder_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$direcao = getParam($_REQUEST, 'cmd', '');
$ordem = getParam($_REQUEST, 'ordem', '0');
$social_acao_id=getParam($_REQUEST, 'social_acao_id', null);


$social_acao_conceder_campo=getParam($_REQUEST, 'social_acao_conceder_campo', null);
$social_acao_conceder_situacao=getParam($_REQUEST, 'social_acao_conceder_situacao', '');

$excluirsocial_acao=getParam($_REQUEST, 'excluirsocial_acao', '0');
$editarsocial_acao=getParam($_REQUEST, 'editarsocial_acao', '0');
$mudar_social_acao_conceder_id=getParam($_REQUEST, 'mudar_social_acao_conceder_id', '0');
$cancelar=getParam($_REQUEST, 'cancelar', '0');
$inserir=getParam($_REQUEST, 'inserir', '0');
$alterar=getParam($_REQUEST, 'alterar', '0');
$sql = new BDConsulta;

echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="social_acao_id" value="'.$social_acao_id.'" />';

echo '<input type="hidden" name="inserir" value="" />';
echo '<input type="hidden" name="alterar" value="" />';
echo '<input type="hidden" name="cancelar" value="" />';
echo '<input type="hidden" name="cmd" value="" />';
echo '<input type="hidden" name="ordem" value="" />';
echo '<input type="hidden" name="social_acao_conceder_id" value="" />';
echo '<input type="hidden" name="mudar_social_acao_conceder_id" value="" />';
echo '<input type="hidden" name="excluirsocial_acao" value="" />';
echo '<input type="hidden" name="editarsocial_acao" value="" />';
echo '<input type="hidden" name="salvaranexo" value="" />';
echo '<input type="hidden" name="excluiranexo" value="" />';

$tipos_campos=getSisValor('FamiliaCampo');

//salvar dados na tabela
if ($inserir && !$mudar_social_acao_conceder_id && !$cancelar){

	$sql->adTabela('social_acao_conceder');
	$sql->adInserir('social_acao_conceder_acao', (int)$social_acao_id);
	$sql->adInserir('social_acao_conceder_campo', $social_acao_conceder_campo);
	$sql->adInserir('social_acao_conceder_situacao', $social_acao_conceder_situacao);
	$sql->exec();
	$social_acao_conceder_id=$bd->Insert_ID('social_acao_conceder','social_acao_conceder_id');
	$sql->Limpar();
	}
if ($alterar && $mudar_social_acao_conceder_id && !$cancelar){
	$sql->adTabela('social_acao_conceder');
	$sql->adAtualizar('social_acao_conceder_campo', $social_acao_conceder_campo);
	$sql->adAtualizar('social_acao_conceder_situacao', $social_acao_conceder_situacao);
	$sql->adOnde('social_acao_conceder_id = '.$mudar_social_acao_conceder_id);
	$sql->exec();
	$sql->Limpar();
	}



if ($excluirsocial_acao){
	$sql->setExcluir('social_acao_conceder');
	$sql->adOnde('social_acao_conceder_id='.(int)$social_acao_conceder_id);
	if (!$sql->exec()) die('N�o foi possivel alterar os valores da tabela social_acao_conceder!'.$bd->stderr(true));
	$sql->limpar();	
	}


$botoesTitulo = new CBlocoTitulo('Par�metros a Concess�o da A��o Social', '../../../modulos/social/imagens/acao.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=acao_ver&social_acao_id='.$social_acao_id, 'ver','','Ver esta A��o Social','Ver os detalhes desta a��o social.');
$botoesTitulo->mostrar();

	
echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0 cellspacing=0>';  
echo '<tr><td colspan=20 align="center"><table cellpadding=0 cellspacing=0>';
if ($editarsocial_acao) {
	$sql->adTabela('social_acao_conceder');
	$sql->adCampo('social_acao_conceder_campo, social_acao_conceder_situacao');
	$sql->adOnde('social_acao_conceder_id='.(int)$social_acao_conceder_id);
	$linha_social_acao=$sql->Linha();
	
	echo '<tr>';
	echo '<td align="left"><b>'.dica('Campo','Qual o campo em que ser� feito um teste l�gico.').'<b>Campo:</b>'.dicaF().selecionaVetor($tipos_campos, 'social_acao_conceder_campo', 'class="texto" style="width:400px;" size="1" onchange="mudar_cidades();"', $linha_social_acao['social_acao_conceder_campo']).'</td>';
	echo '<td><b>'.dica('Situa��o','Qual dever� ser a situa��o do campo para que a fam�lia possa receber o benef�cio da a��o social.<br>Ex:<ul><li>IS NOT NULL</li><li>> 20</li><li>=1</li></ul>').'<b>Situa��o:</b>'.dicaF().'<input type="text" name="social_acao_conceder_situacao" id="social_acao_conceder_situacao" value="'.$linha_social_acao['social_acao_conceder_situacao'].'" style="width:100px;" class="texto" /></td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.alterar.value=1; env.mudar_social_acao_conceder_id.value='.$social_acao_conceder_id.'; env.submit();">'.imagem('icones/ok.png', 'Aceitar Altera��es', 'Clique neste �cone '.imagem('icones/ok.png').' para aceitar as altera��es na justificativa da a��o social inserida � esquerda.').'</a><a href="javascript:void(0);" onclick="javascript:env.cancelar.value=1; env.submit();">'.imagem('icones/cancelar.png', 'Cancelar Altera��es', 'Clique neste �cone '.imagem('icones/cancelar.png').' para cancelar as altera��es na justificativa da a��o social inserida � esquerda.').'</a></td>';
	}
else {
	echo '<tr>';
	echo '<td align="left"><b>'.dica('Campo','Qual o campo em que ser� feito um teste l�gico.').'<b>Campo:</b>'.dicaF().selecionaVetor($tipos_campos, 'social_acao_conceder_campo', 'class="texto" style="width:400px;" size="1" onchange="mudar_cidades();"').'</td>';
	echo '<td><b>'.dica('Situa��o','Qual dever� ser a situa��o do campo para que a fam�lia possa receber o benef�cio da a��o social.<br>Ex:<ul><li>IS NOT NULL</li><li>> 20</li><li>=1</li></ul>').'<b>Situa��o:</b>'.dicaF().'<input type="text" name="social_acao_conceder_situacao" id="social_acao_conceder_situacao" value="" style="width:100px;" class="texto" /></td>';
	echo '<td><a href="javascript:void(0);" onclick="javascript:env.inserir.value=1; env.submit();">'.imagem('icones/adicionar.png', 'Inserir Negativa', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar a justificativa � a��o social.').'</a></td>';
	}
echo '</tr></table></td></tr>';



$sql->adTabela('social_acao_conceder');
$sql->adCampo('*');
$sql->adOnde('social_acao_conceder_acao='.(int)$social_acao_id);
$linhas_social_acao=$sql->Lista();
if ($linhas_social_acao && count($linhas_social_acao)) echo '<tr><td colspan=20>&nbsp;</td></tr><tr><td colspan=20 align=center><table class="tbl1" cellpadding=2 cellspacing=0 border=0><tr><th>&nbsp;'.dica('Campo','Qual o campo da tabela das fam�lias em que ser� feito teste l�gico').'Campo'.dicaF().'&nbsp;</th><th>&nbsp;'.dica('Situa��o','Qual dever� ser a situa��o do campo para que a fam�lia possa receber o benef�cio da a��o social').'Situa��o'.dicaF().'&nbsp;</th><th></th></tr>';
foreach ($linhas_social_acao as $linha_social_acao) {
	echo '<tr>';
	echo '<td>'.$tipos_campos[$linha_social_acao['social_acao_conceder_campo']].'</td>';
	echo '<td>'.$linha_social_acao['social_acao_conceder_situacao'].'</td>';
	echo '<td><a href="javascript: void(0);" onclick="env.editarsocial_acao.value=1; env.social_acao_conceder_id.value='.$linha_social_acao['social_acao_conceder_id'].'; env.submit();">'.imagem('icones/editar.gif', 'Editar Negativa', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar esta justificativa.').'</a>';
	echo '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta justificativa?\')) {env.excluirsocial_acao.value=1; env.social_acao_conceder_id.value='.$linha_social_acao['social_acao_conceder_id'].'; env.submit();}">'.imagem('icones/remover.png', 'Excluir Negativa', 'Clique neste �cone '.imagem('icones/remover.png').' para excluir esta justificativa da a��o social.').'</a></td>';
	echo '</tr>';
	}
if ($linhas_social_acao && count($linhas_social_acao)) echo '</table></td></tr>';


echo '</table>';
echo '</td></tr></table>';

echo estiloFundoCaixa();
?>
