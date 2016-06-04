<?php
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

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
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();

echo '<form name="frmValSis" method="post">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="fazer_sisvalor_aed" />';
echo '<input type="hidden" name="u" value="sischaves" />';
echo '<input type="hidden" name="del" value="0" />';

echo estiloTopoCaixa();
echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1"><tr><th>&nbsp;</th>';
echo '<th>'.dica('Campo', 'O campo é o vetor que terá como índice os parâmetros do sistema e o valor relacionado a cada índice o que desejamos que seja extraído.').'Campo'.dicaF().'</th>';
echo '<th colspan="2">'.dica('Valores', 'Dentro  do vetor deverá haver como índice os parâmetros do sistema e um valor relacionado a cada um desses índices.').'Valores'.dicaF().'</th></tr>';
foreach ($valores as $linha) {
	echo '<tr><td width="12" valign="top" style="vertical-align:middle;">'.dica('Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o campo.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&u=sischaves&a=editar&sisvalor_titulo='.$linha['sisvalor_titulo'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF().'</td>';
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

