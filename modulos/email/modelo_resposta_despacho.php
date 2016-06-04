<?php
/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();

$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', 0);

$salvar=getParam($_REQUEST, 'salvar', 0);
$texto=getParam($_REQUEST, 'texto', '');
$sql = new BDConsulta;

if ($salvar){
	$sql->adTabela('modelo_usuario');
	$sql->adAtualizar('data_retorno', date('Y-m-d H:i:s'));
	$sql->adAtualizar('resposta_despacho', $texto);
	$sql->adOnde('modelo_usuario_id = '.$modelo_usuario_id);
	if (!$sql->exec()) die('N�o foi poss�vel atualizar msg_usuario.');
	$sql->limpar();

	echo '<script>window.opener.sumir(\'responder_despacho\'); alert("Resposta ao despacho foi salva."); self.close();</script>';
	exit();

	}

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="modelo_resposta_despacho">';
echo '<input type=hidden name="m" id="email" value="email">';
echo '<input type=hidden name="dialogo" id="dialogo" value="1">';
echo '<input type=hidden name="salvar" id="salvar"  value="1">';
echo '<input type=hidden name="modelo_usuario_id" id="modelo_usuario_id"  value="'.$modelo_usuario_id.'">';


$sql->adTabela('preferencia_cor');
$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
$sql->adOnde('usuario_id ='.$Aplic->usuario_id);
$cor=$sql->Linha();
$sql->limpar();

if (!isset($cor['cor_msg'])) {
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
	$sql->adOnde('usuario_id = 0 OR usuario_id IS NULL');
	$cor=$sql->Linha();
	$sql->limpar();
 	}


$sql->adTabela('modelo_anotacao');
$sql->esqUnir('modelos','modelos','modelos.modelo_id=modelo_anotacao.modelo_id');
$sql->esqUnir('modelo_usuario','modelo_usuario','modelo_anotacao.modelo_anotacao_id=modelo_usuario.modelo_anotacao_id');
$sql->adUnir('usuarios','usuarios','modelo_anotacao.usuario_id = usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
$sql->adCampo('modelo_anotacao.datahora, modelo_anotacao.texto, modelo_anotacao.nome_de, modelo_anotacao.funcao_de, modelo_anotacao.usuario_id');
$sql->adOnde('modelo_usuario.modelo_usuario_id = '.$modelo_usuario_id);
$sql->adOnde('modelo_anotacao.tipo = 1');
$sql->adOnde('modelo_anotacao.usuario_id = modelo_usuario.de_id');
$remetente = $sql->Linha();
$sql->limpar();

echo '<br>';


echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
echo '<table align="center" cellspacing=0 width="810" cellpadding=0>';
echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'" >Despacho de <b>'.nome_funcao($remetente['nome_de'], $remetente['nome_usuario'], $remetente['funcao_de'], $remetente['contato_funcao']).'</b> em '.retorna_data($remetente['datahora']).'</td></tr>';
echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$remetente['texto'].'</td></tr>';
echo '</table></td></tr></table>';
echo sombra_baixo('',810);

$sql->adTabela('modelo_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_usuario_id, data_retorno, data_limite, resposta_despacho, modelo_usuario.tipo, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, modelo_usuario.status, modelo_usuario.datahora_leitura, modelo_usuario.cm, modelo_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
$sql->adOnde('modelo_usuario_id = '.$modelo_usuario_id);
$linha = $sql->Linha();
$sql->limpar();

echo estiloTopoCaixa(810);
echo '<table border=0 cellpadding=0 cellspacing=1 width="810" class="std" align="center"><tr><td>';
 echo '<tr><td><table><tr><td width="360"><font size="3"><b>Resposta</b></font></td><td width="280">'.($linha['data_limite'] ? '<b>Prazo Limite: '.retorna_data($linha['data_limite'], false).'</b>' : '').'</td><td width="60">'.botao('gravar', '', '','','env.submit();').'</td><td>'.botao('cancelar', '', '','','self.close();').'</td></tr></table></td></tr>';
echo '<tr><td bgcolor="ffffff"><textarea data-gpweb-cmp="ckeditor" rows="10" id="texto" name="texto" style="width:800px; max-width:800px;">'.$linha['resposta_despacho'].'</textarea></td></tr>';
echo '</table>';
echo estiloFundoCaixa(810);
echo '</form>';
?>
