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

$Aplic->carregarCKEditorJS();

$arquivo_id = intval(getParam($_REQUEST, 'arquivo_id', 0));
$msg = '';
$obj = new CArquivo();
if ($arquivo_id > 0 && !$obj->load($arquivo_id)) {
	$Aplic->setMsg('Arquivo');
	$Aplic->setMsg('informa��es erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=arquivos');
	}
$botoesTitulo = new CBlocoTitulo('Sa�da', 'arquivo.png', $m, "$m.$a");
$botoesTitulo->mostrar();

echo '<form name="coFrm" method="post">';
echo '<input type="hidden" name="m" value="arquivos" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_arquivo_co" />';
echo '<input type="hidden" name="del" value="0" />';
echo '<input type="hidden" name="arquivo_id" value="'.$arquivo_id.'" />';
echo '<input type="hidden" name="arquivo_saida_versao" value="'.$obj->arquivo_versao.'" />';

$ArquivoAcao=getSisValor('ArquivoAcao','','','sisvalor_id');

echo estiloTopoCaixa();
echo '<table width="100%" cellpadding=1 cellspacing=1 class="std">';
if ($arquivo_id) {
	echo '<tr><td align="right" nowrap="nowrap" width=100>'.dica('Nome do Arquivo', 'Todo arquivo enviado para o Sistema dever� ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo:'.dicaF().'</td><td align="left" class="realce">'.(strlen($obj->arquivo_nome) == 0 ? "n/d" : $obj->arquivo_nome).'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Arquivo', 'Pela extens�o do arquivo, o sistema tentar� identificar qual o tipo de arquivo.').'Tipo:'.dicaF().'</td><td align="left" class="realce">'.$obj->arquivo_tipo.'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Tamanho', 'O tamanho do arquivo em bytes').'Tamanho:'.dicaF().'</td><td align="left" class="realce">'.$obj->arquivo_tamanho.'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Respons�vel pelo Envio', 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que enviou o arquivo.').'Enviado por:'.dicaF().'</td><td align="left" class="realce">'.link_usuario($obj->arquivo_usuario_upload,'','','esquerda').'</td></tr>';
	}
echo '<tr><td align="right" nowrap="nowrap">'.dica('Motivo Retirada', 'Ao retirar o arquivo � interessante deixar alguma informa��o sobre o motivo desta ocorr�ncia.').'Motivo Retirada:'.dicaF().'</td><td align="left"><textarea name="arquivo_saida_motivo" data-gpweb-cmp="ckeditor" class="textarea" rows="4" style="width:270px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('A��o', 'A a��o tomada quando da retirada do arquivo.').'A��o:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($ArquivoAcao, 'arquivo_saida_acao', 'size="1" class="texto"').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">&nbsp;</td><td align="left" nowrap="nowrap"><input type="checkbox" name="notificar_dono" id="notificar_dono"/><label for="notificar">Notificar o respons�vel pelo arquivo</label></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">&nbsp;</td><td align="left" nowrap="nowrap"><input type="checkbox" name="notificar_upload" id="notificar_upload"/><label for="notificar">Notificar o respons�vel pelo upload do arquivo</label></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">&nbsp;</td><td align="left"><input type="checkbox" name="notificar_participantes" id="notificar_participantes"/><label for="notificar_contatos">Notificar os participantes do arquivo </label></td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar a retirada do arquivo.','','coFrm.submit()').'</td><td  align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a retirada do arquivo.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';
echo '</form></table>';
echo estiloFundoCaixa();

?>