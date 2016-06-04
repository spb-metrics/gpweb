<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

$Aplic->carregarCKEditorJS();

$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);
$msg_id=getParam($_REQUEST, 'msg_id', 0);
$anotacao_id=getParam($_REQUEST, 'anotacao_id', 0);
$salvar=getParam($_REQUEST, 'salvar', 0);
$texto=getParam($_REQUEST, 'texto', '');
$sql = new BDConsulta;

if ($salvar){
	$sql->adTabela('msg_usuario');
	$sql->adAtualizar('data_retorno', date('Y-m-d H:i:s'));
	$sql->adAtualizar('resposta_despacho', $texto);
	$sql->adOnde('msg_usuario_id = '.$msg_usuario_id);
	if (!$sql->exec()) die('Não foi possível atualizar msg_usuario.');
	$sql->limpar();
	echo '<script>opener.location.reload(); self.close();</script>';
	exit();
	}

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="resposta_despacho">';
echo '<input type=hidden name="m" id="email" value="email">';
echo '<input type=hidden name="dialogo" id="dialogo" value="1">';
echo '<input type=hidden name="salvar" id="salvar"  value="1">';
echo '<input type=hidden name="msg_usuario_id" id="msg_usuario_id"  value="'.$msg_usuario_id.'">';


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


$sql->adTabela('anotacao');
$sql->adUnir('usuarios','usuarios','anotacao.usuario_id = usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('anotacao_usuarios, anotacao.datahora, anotacao.usuario_id, anotacao.nome_de, anotacao.funcao_de, anotacao.texto, anotacao.tipo, contato_funcao, anotacao.anotacao_id');
$sql->adOnde('anotacao.anotacao_id = '.$anotacao_id);
$sql->adOrdem('anotacao_id DESC');
$sql_resultadosb = $sql->Lista();
$sql->limpar();
$outros_despachos=array();
foreach ($sql_resultadosb as $rs_anot){

	//despacho
	$vetor_destinatarios=array();
	$saida = '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
	$saida.= '<table align="center" cellspacing=0 width="770" cellpadding=0>';
	$saida.= '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'" >Despacho de <b>'.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).'</b> em '.retorna_data($rs_anot['datahora']).'</td></tr>';
	$saida.= '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr>';
	$saida.= '<tr><td style="font-size:8pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'"><table cellspacing=0 cellpadding=0><tr><td><b>Para</b>:</td><td>';
	$sql->adTabela('msg_usuario');
	$sql->adUnir('usuarios','usuarios','msg_usuario.para_id = usuarios.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
	$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, contato_funcao');
	$sql->adOnde('msg_id = '.$msg_id);
	$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
	$sql->adOnde('msg_usuario.datahora=\''.$rs_anot['datahora'].'\'');
	$sql->adOnde('msg_usuario.tipo=1');
	$sql->adGrupo('para_id');
	$destinatarios_despacho = $sql->Lista();
	$sql->limpar();

	foreach($destinatarios_despacho as $chave => $destinatario){
	if ($destinatario['para_id']==$Aplic->usuario_id) {
		$apoio=$destinatarios_despacho[0];
		$destinatarios_despacho[0]=$destinatarios_despacho[$chave];
		$destinatarios_despacho[$chave]=$apoio;
		}
	}


  $quant=0;
  $primeira_linha=0;
	if (!count($destinatarios_despacho)){
  	$sql->adTabela('msg_usuario');
		$sql->adUnir('usuarios','usuarios','msg_usuario.para_id = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, contato_funcao');
		$sql->adOnde('msg_id = '.$msg_id);
		$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
		$sql->adOnde('msg_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
		$sql->adGrupo('para_id');
		$destinatarios_despacho = $sql->Lista();
		$sql->limpar();
  	}
  if ($destinatarios_despacho[0]['para_id']) $vetor_destinatarios[]=$destinatarios_despacho[0]['para_id'];
	$saida.= formata_despacho($destinatarios_despacho[0]);
	$qnt_destinatario=count($destinatarios_despacho);
	if ($qnt_destinatario > 1) {
			$lista='';
			for ($i = 1, $i_cmp = $qnt_destinatario; $i < $i_cmp; $i++) {
				$lista.= formata_despacho($destinatarios_despacho[$i]).'<br>';
				$vetor_destinatarios[]=$destinatarios_despacho[$i]['para_id'];
				}
			$saida.= dica('Outros Destinatários', 'Clique para visualizar os demais destinatários.').' <a href="javascript: void(0);" onclick="ver_destinatario_despacho('.$rs_anot['anotacao_id'].');">(+'.($qnt_destinatario - 1).')</a>'.dicaF(). '<span style="display: none" id="despacho_'.$rs_anot['anotacao_id'].'"><br>'.$lista.'</span>';
			}
	$saida.= '</td></tr></table></td></tr></table>';
	$saida.= '</td></tr></table>';
	$saida.= sombra_baixo();
	if (in_array($Aplic->usuario_id, $vetor_destinatarios) || $rs_anot['usuario_id']==$Aplic->usuario_id) echo $saida;
	else $outros_despachos[]=$saida;
	}


if (count($outros_despachos))	{
	echo '<table align="center"><tr><td>'.dica('Outros Despachos','Clique neste link para visualizar os outros despachos efetados n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a href="javascript:void(0);" onclick="javascript:visualizar_outros_despachos();" style="padding-left: 5px; font-size:10pt; font-weight:Bold;">Outros despachos ('.count($outros_despachos).')</a>'.dicaF().'</td></tr></table>';
	echo '<span style="display: none" id="outros_despacho">';
	foreach($outros_despachos as $outro) echo $outro;
	echo '</span>';
	}

$sql->adTabela('msg_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('msg_usuario_id, data_retorno, data_limite, resposta_despacho, msg_usuario.tipo, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
$sql->adOnde('msg_usuario.msg_id = '.$msg_id);
$sql->adOnde('msg_usuario.para_id = '.$Aplic->usuario_id);
$linha = $sql->Linha();
$sql->limpar();

echo estiloTopoCaixa(770);
echo '<table border=0 cellpadding=0 cellspacing=1 width="770" class="std" align="center"><tr><td>';
 echo '<tr><td><table><tr><td width="360"><font size="3"><b>Resposta</b></font></td><td width="280">'.($linha['data_limite'] ? '<b>Prazo Limite: '.retorna_data($linha['data_limite'], false).'</b>' : '').'</td></tr></table></td></tr>';
echo '<tr><td colspan=2 bgcolor="ffffff"><textarea data-gpweb-cmp="ckeditor" rows="10" id="texto" name="texto" style="width:768px; max-width:768px;">'.$linha['resposta_despacho'].'</textarea></td></tr>';
echo '<tr><td>'.botao('salvar', 'Salvar', 'Clique neste botão para salvar o texto','','env.submit();').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Clique neste botão para cancelar a inserção do texto','','self.close();').'</td></tr>';
echo '</table>';
echo estiloFundoCaixa(770);
echo '</form>';


function formata_despacho ($rs_anotf=array()){
	global $Aplic;
	$saida='';
	if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '<b>';
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
  if ($rs_anotf['copia_oculta'] !=1 || ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= nome_funcao($rs_anotf['nome_para'], $rs_anotf['nome_usuario'], $rs_anotf['funcao_para'], $rs_anotf['contato_funcao'])."&nbsp;&nbsp;";
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 )) $saida.= '</i>';
  if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '</b>';
  return $saida;
	}
?>
<script language=Javascript>

function visualizar_outros_despachos(){
		if (document.getElementById('outros_despacho').style.display=='none') document.getElementById('outros_despacho').style.display='';
	else document.getElementById('outros_despacho').style.display='none';
	}

function ver_destinatario_despacho(anotacao_id){
	if (document.getElementById('despacho_'+anotacao_id).style.display=='none') document.getElementById('despacho_'+anotacao_id).style.display='';
	else document.getElementById('despacho_'+anotacao_id).style.display='none';
	}
</script>