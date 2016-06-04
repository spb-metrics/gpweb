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

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
global $valoresFixosSistema;
if (!$dialogo) $Aplic->salvarPosicao();
$sql = new BDConsulta;
$sql->adTabela('sischaves');
$sql->adCampo('sischave_id,sischave_nome');
$sql->adOrdem('sischave_nome');
$chaves = unirVetores(array(0 => 'Selecione Tipo'), $sql->ListaChave());
$sql->limpar();

$sql->adTabela('sisvalores');
$sql->adCampo('DISTINCT sisvalor_titulo');
$sql->adOnde('sisvalor_projeto IS NULL');
$sql->adOrdem('sisvalor_titulo');
$sql->adOrdem('sisvalor_id');
$valores = $sql->Lista();
$sql->limpar();


$sql->adTabela('sisvalores');
$sql->adCampo('sisvalor_titulo, sisvalor_valor_id, sisvalor_valor, sisvalor_chave_id_pai');
$sql->adOnde('sisvalor_projeto IS NULL');
$sql->adOrdem('sisvalor_titulo');
$sql->adOrdem('sisvalor_id');
$vals = $sql->Lista();
$sql->limpar();

foreach ($valores as $chave => $valor) {
	$valores[$chave]['sisvalor_valor'] = '';
	foreach ($vals as $kval => $val) {
		if ($valor['sisvalor_titulo'] == $val['sisvalor_titulo']) {
			$valores[$chave]['sisvalor_valor'] .= $val['sisvalor_valor_id'].'|'.$val['sisvalor_valor'].($val['sisvalor_chave_id_pai'] ? '|'.$val['sisvalor_chave_id_pai']  : '')."\n";
			}
		}
	}
$sisvalor_titulo = getParam($_REQUEST, 'sisvalor_titulo', '');
$botoesTitulo = new CBlocoTitulo('Valores de Campos do Sistema', 'opcoes.png', $m, $m.'.'.$u.'.'.$a);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administra��o do Sistema','Voltar � tela de Administra��o do Sistema.');
$botoesTitulo->mostrar();

echo '<form name="frmValSis" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="fazer_sisvalor_aed" />';
echo '<input type="hidden" name="u" value="sischaves" />';
echo '<input type="hidden" name="del" value="0" />';

echo estiloTopoCaixa();
echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1"><tr><th>&nbsp;</th>';
echo '<th>'.dica('Campo', 'O campo � o vetor que ter� como �ndice os par�metros do sistema e o valor relacionado a cada �ndice o que desejamos que seja extra�do.').'Campo'.dicaF().'</th>';
echo '<th colspan="2">'.dica('Valores', 'Dentro  do vetor dever� haver como �ndice os par�metros do sistema e um valor relacionado a cada um desses �ndices.').'Valores'.dicaF().'</th></tr>';
foreach ($valores as $linha) {
	echo '<tr><td width="12" valign="top" style="vertical-align:middle;">'.dica('Editar', 'Clique neste �cone '.imagem('icones/editar.gif').' para editar o campo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves&a=editar&sisvalor_titulo='.$linha['sisvalor_titulo'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
	echo '<td valign="top" style="vertical-align:middle;">'.$linha['sisvalor_titulo'].'</td>';
	echo '<td valign="top" colspan="2" style="vertical-align:middle;">'.$linha['sisvalor_valor'].'</td>';
	echo '</tr>';
	}
echo '</table></form>';
echo estiloFundoCaixa();

?>

<script language="javascript">
function excluir(id) {
	if (confirm( 'Tem certeza que deseja excluir?' )) {
		f = document.frmValSis;
		f.del.value = 1;
		f.sisvalor_titulo.value = id;
		f.submit();
	}
}
</script>

