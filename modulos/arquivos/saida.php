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

$Aplic->carregarCKEditorJS();

$arquivo_id = intval(getParam($_REQUEST, 'arquivo_id', 0));
$msg = '';
$obj = new CArquivo();
if ($arquivo_id > 0 && !$obj->load($arquivo_id)) {
	$Aplic->setMsg('Arquivo');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=arquivos');
	}
$botoesTitulo = new CBlocoTitulo('Saída', 'arquivo.png', $m, "$m.$a");
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
	echo '<tr><td align="right" nowrap="nowrap" width=100>'.dica('Nome do Arquivo', 'Todo arquivo enviado para o Sistema deverá ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo:'.dicaF().'</td><td align="left" class="realce">'.(strlen($obj->arquivo_nome) == 0 ? "n/d" : $obj->arquivo_nome).'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de Arquivo', 'Pela extensão do arquivo, o sistema tentará identificar qual o tipo de arquivo.').'Tipo:'.dicaF().'</td><td align="left" class="realce">'.$obj->arquivo_tipo.'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Tamanho', 'O tamanho do arquivo em bytes').'Tamanho:'.dicaF().'</td><td align="left" class="realce">'.$obj->arquivo_tamanho.'</td></tr>';
	echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pelo Envio', 'Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que enviou o arquivo.').'Enviado por:'.dicaF().'</td><td align="left" class="realce">'.link_usuario($obj->arquivo_usuario_upload,'','','esquerda').'</td></tr>';
	}
echo '<tr><td align="right" nowrap="nowrap">'.dica('Motivo Retirada', 'Ao retirar o arquivo é interessante deixar alguma informação sobre o motivo desta ocorrência.').'Motivo Retirada:'.dicaF().'</td><td align="left"><textarea name="arquivo_saida_motivo" data-gpweb-cmp="ckeditor" class="textarea" rows="4" style="width:270px"></textarea></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ação', 'A ação tomada quando da retirada do arquivo.').'Ação:'.dicaF().'</td><td nowrap="nowrap">'.selecionaVetor($ArquivoAcao, 'arquivo_saida_acao', 'size="1" class="texto"').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">&nbsp;</td><td align="left" nowrap="nowrap"><input type="checkbox" name="notificar_dono" id="notificar_dono"/><label for="notificar">Notificar o responsável pelo arquivo</label></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">&nbsp;</td><td align="left" nowrap="nowrap"><input type="checkbox" name="notificar_upload" id="notificar_upload"/><label for="notificar">Notificar o responsável pelo upload do arquivo</label></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">&nbsp;</td><td align="left"><input type="checkbox" name="notificar_participantes" id="notificar_participantes"/><label for="notificar_contatos">Notificar os participantes do arquivo </label></td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar', 'Salvar a retirada do arquivo.','','coFrm.submit()').'</td><td  align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a retirada do arquivo.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr>';
echo '</form></table>';
echo estiloFundoCaixa();

?>