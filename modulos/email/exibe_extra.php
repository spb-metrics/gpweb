<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $msg_id;
$msg_usuario_id=getParam($_REQUEST, 'msg_usuario_id', 0);
$msg_id=getParam($_REQUEST, 'msg_id', $msg_id);
$tipos_status=array('' => 'indefinido') + getSisValor('status');
$primeiro=0;

$sql = new BDConsulta; 

$sql->adTabela('msg');
$sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_id = msg.msg_id');
$sql->adCampo('msg_usuario_id');
$sql->adOnde('msg_usuario.msg_usuario_id = '.$msg_usuario_id);
$sql->adOnde('msg.class_sigilosa <= '.$Aplic->usuario_acesso_email);
$permitido = $sql->Resultado();
$sql->limpar();

if (!$permitido) {
	echo '<script language=Javascript>alert("Não tem permissão de acesso ao histórico d'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'");self.close();</script>';
	exit();
	}		

$sql->adTabela('preferencia_cor');
$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
$sql->adOnde('usuario_id ='.$Aplic->usuario_id);
$cor=$sql->Linha();
$sql->limpar();

if (!isset($cor['cor_msg']) ) {
	$sql->adTabela('preferencia_cor');
	$sql->adCampo('cor_fundo, cor_menu, cor_msg, cor_anexo, cor_despacho, cor_anotacao, cor_resposta, cor_encamihamentos');
	$sql->adOnde('usuario_id = 0 OR usuario_id IS NULL');
	$cor=$sql->Linha();
	$sql->limpar();
 	}

$sql->adTabela('msg');
$sql->adCampo('data_envio,nome_de, funcao_de');
$sql->adOnde('msg_id = '.$msg_id);
$msg = $sql->Linha();
$sql->limpar();

echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';	
echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.($dialogo ? 'FFFFFF' : $cor['cor_encamihamentos']).'" cellspacing=0 width="770" cellpadding=0>';
echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Histórico</b></td></tr>';
echo '<tr><td align=center><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
echo '<tr align=center><td><b>'.ucfirst($config['usuario']).'</b></td><td><b>Ação</b></td><td><b>Data</b></td></tr>';
echo '<tr align=center><td>'.nome_funcao($msg['nome_de'],'',$msg['funcao_de']).'</td><td>Criou</td><td>'.retorna_data($msg['data_envio']).'</td></tr>';
echo '</table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';
echo '</td></tr></table>';
if (!$dialogo) echo sombra_baixo('', 770); 	



$sql->adTabela('anotacao');
$sql->esqUnir('usuarios','usuarios','anotacao.usuario_id = usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('anotacao_usuarios, anotacao.datahora, anotacao.usuario_id, anotacao.nome_de, anotacao.funcao_de, anotacao.texto, anotacao.tipo, contato_funcao, anotacao_id');
$sql->adOnde('msg_id = '.$msg_id);
$sql->adOrdem('anotacao_id DESC');
$sql_resultadosb = $sql->Lista();
$sql->limpar();
$outros_despachos=array();
foreach ($sql_resultadosb as $rs_anot){ 
	if ($rs_anot['tipo'] == 1 ) { 
		//despacho
		$vetor_destinatarios=array();
		$saida = '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		$saida.= '<table align="center" cellspacing=0 width="770" cellpadding=0>';
		$saida.= '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['anotacao_id'].');">Despacho de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
		$saida.= '<tr id="linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr>';
		$saida.= '<tr id="2linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:8pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'"><table cellspacing=0 cellpadding=0><tr><td><b>Para</b>:</td><td>';
		$sql->adTabela('msg_usuario');
		$sql->esqUnir('usuarios','usuarios','msg_usuario.para_id = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, contato_funcao');
		$sql->adOnde('msg_id = '.$msg_id);
		$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
		$sql->adOnde('msg_usuario.datahora=\''.$rs_anot['datahora'].'\'');
		//EUZ
		//$sql->adGrupo('para_id');
    //$sql->adGrupo('msg_usuario.para_id, contatos.contato_posto, contatos.contato_nomeguerra, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_para, msg_usuario.funcao_de, msg_usuario.nome_para, msg_usuario.copia_oculta, contatos.contato_funcao');
    //EUD
		
		$destinatarios_despacho = $sql->Lista();
		$sql->limpar();
	  $quant=0; 
	  $primeira_linha=0; 
		if (!count($destinatarios_despacho)){
	  	$sql->adTabela('msg_usuario');
			$sql->esqUnir('usuarios','usuarios','msg_usuario.para_id = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
			$sql->adCampo('msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, contato_funcao');
			$sql->adOnde('msg_id = '.$msg_id);
			$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
			$sql->adOnde('msg_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
			//$sql->adGrupo('para_id');
			$destinatarios_despacho = $sql->Lista();
			$sql->limpar();
	  	}
	  if (isset($destinatarios_despacho[0]['para_id']) && $destinatarios_despacho[0]['para_id']) $vetor_destinatarios[]=$destinatarios_despacho[0]['para_id'];
		if (isset($destinatarios_despacho[0])) $saida.= formata_despacho2($destinatarios_despacho[0]);
		$qnt_destinatario=count($destinatarios_despacho);
		if ($qnt_destinatario > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_destinatario; $i < $i_cmp; $i++) {
					$lista.= formata_despacho2($destinatarios_despacho[$i]).'<br>';
					$vetor_destinatarios[]=$destinatarios_despacho[$i]['para_id'];
					}		
				$saida.= dica('Outros Destinatários', 'Clique para visualizar os demais destinatários.').' <a href="javascript: void(0);" onclick="mostrar_esconder(\'despacho_\', '.$rs_anot['anotacao_id'].');">(+'.($qnt_destinatario - 1).')</a>'.dicaF(). '<span style="display: none" id="despacho_'.$rs_anot['anotacao_id'].'"><br>'.$lista.'</span>';
				}
		$saida.= '</td></tr></table></td></tr></table>';
		$saida.= '</td></tr></table>'; 
		if (in_array($Aplic->usuario_id, $vetor_destinatarios) || $rs_anot['usuario_id']==$Aplic->usuario_id) echo $saida;
		else $outros_despachos[]=$saida;
		} 
	else if ($rs_anot['tipo'] == 2 ){ 
		echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
	  echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_resposta'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['anotacao_id'].');">Resposta de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao'])." em ".retorna_data($rs_anot['datahora']).'</a></td></tr>';
	  echo '<tr id="linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;  background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr></table>';
		echo '</td></tr></table>'; 
		} 
	else if ($rs_anot['tipo'] == 4 ){
		$pode_ver=0;
		if (!$rs_anot['anotacao_usuarios'] || $rs_anot['usuario_id']==$Aplic->usuario_id) $pode_ver=1;
		else {
			$sql->adTabela('anotacao_usuarios');
			$sql->adOnde('usuario_id');
			$sql->adOnde('anotacao_id = '.$rs_anot['anotacao_id']);
			$sql->adOnde('usuario_id='.$Aplic->usuario_id);
			$pode_ver= $sql->Resultado();
			$sql->limpar();
			}
		if ($pode_ver){
			echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		  echo '<table align="center" cellspacing=0 width="770" cellpadding=0>';
		  echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_anotacao'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['anotacao_id'].');">Nota de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
		  echo '<tr id="linha1_'.$rs_anot['anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr></table>';     
		  echo '</td></tr></table>'; 
			}
	  } 
	}    
if (count($sql_resultadosb) && !$dialogo) echo sombra_baixo('', 770); 


if (count($outros_despachos))	{
	echo '<table align="center"><tr><td>'.dica('Outros Despachos','Clique neste link para visualizar os outros despachos efetados n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a href="javascript:void(0);" onclick="javascript:mostrar_esconder(\'outros_despacho\', \'\');" style="padding-left: 5px; font-size:10pt; font-weight:Bold;">Outros despachos ('.count($outros_despachos).')</a>'.dicaF().'</td></tr></table>';
	echo '<span style="display: none" id="outros_despacho">';
	foreach($outros_despachos as $outro) echo $outro;
	echo '</span>';
	}


$sql->adTabela('msg_usuario');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('msg_usuario_id, data_retorno, data_limite, resposta_despacho, msg_usuario.tipo, msg_usuario.de_id, msg_usuario.nome_de, msg_usuario.funcao_de, msg_usuario.para_id, msg_usuario.nome_para, msg_usuario.funcao_para, msg_usuario.copia_oculta, msg_usuario.status, msg_usuario.datahora_leitura, msg_usuario.cm, msg_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
$sql->adOnde('msg_id = '.$msg_id);
$sql->adOnde('msg_usuario.para_id>0');
$sql_resultadosf = $sql->Lista();
$sql->limpar();




//todos os destinatários extras
$sql->adTabela('msg_usuario_ext');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao');
$sql->adCampo('para, tipo, datahora');
$sql->adOnde('msg_id ='.$msg_id);
//EUZ
//$sql->adGrupo('para, contatos.contato_posto, contatos.contato_nomeguerra, contatos.contato_funcao, msg_usuario_ext.tipo, msg_usuario_ext.datahora');
//EUD

$destinatarios_extras = $sql->Lista();
$sql->limpar();


$tipo=array('0'=>'envio', '1'=>'despacho', '2'=>'resposta', '3'=>'encaminhamento', '4'=>'nota');

$objeto_data = new CData();
$agora=$objeto_data->format(FMT_TIMESTAMP_MYSQL);

if (($sql_resultadosf && count($sql_resultadosf)) || count($destinatarios_extras)){
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td><table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.($dialogo ? 'FFFFFF' : $cor['cor_encamihamentos']).'" cellspacing=0 width="770" cellpadding=0>';
	echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Tramitação d'.$config['genero_mensagem'].' '.$config['mensagem'].'</b></td></tr>';
	echo '<tr><td><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
	echo '<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Tipo</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>De</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Para</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Envio</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Leitura</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Status</b></td></tr>'; 	

	foreach ($sql_resultadosf as $rs_enc){ 
	  if (($rs_enc['copia_oculta'] !=1) || ($rs_enc['de_id']==$Aplic->usuario_id || $rs_enc['para_id']==$Aplic->usuario_id )) {
	    if ($rs_enc['tipo']==1 && !$rs_enc['data_limite']) $cor_campo='FFFFFF';
	    elseif ($rs_enc['tipo']==1 && (($rs_enc['data_retorno']> $rs_enc['data_limite']) || ($rs_enc['data_limite']< $agora && !$rs_enc['data_retorno']))) $cor_campo='FFCCCC';
	    elseif ($rs_enc['tipo']==1 && ($rs_enc['data_retorno']<= $rs_enc['data_limite'])) $cor_campo='CCFFCC';
	    else $cor_campo='FFFFFF';
	    echo '<tr>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.$tipo[$rs_enc['tipo']].($rs_enc['resposta_despacho'] ? '<a href="javascript: void(0);" onclick="mostrar_esconder(\'despacho_\', '.$rs_enc['msg_usuario_id'].');">'.imagem('icones/msg10000.gif','Resposta ao Despacho','Clique neste ícone '.imagem('icones/msg10000.gif').' para visualizar a resposta ao despacho.').'</a>' :'').'</td>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.nome_funcao($rs_enc['nome_de'], '', $rs_enc['funcao_de'], '').'</td>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.formata_destinatario2($rs_enc).'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".retorna_data($rs_enc['datahora']).'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>";
			if (!$rs_enc['datahora_leitura'])	echo 'Não Lida';
			else echo retorna_data($rs_enc['datahora_leitura']).($rs_enc['cm'] == 1 ? '(CM:'.nome_usuario($rs_enc['cm']).' por '.$rs_enc['meio'].')' : '');
			echo '</td>';
			echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$tipos_status[$rs_enc['status']].'</td>';
			echo '</tr>';
			if ($rs_enc['resposta_despacho']) echo '<tr id="despacho_'.$rs_enc['msg_usuario_id'].'" style="display:none;"><td colspan=20>'.$rs_enc['resposta_despacho'].'</td></tr>';
			}
		}
		
	foreach ($destinatarios_extras as $extra){ 
		echo '<tr>';
		echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$tipo[$extra['tipo']].'</td>';
		echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.nome_funcao($extra['nome_usuario'], '', $extra['contato_funcao'], '').'</td>';
		echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$extra['para'].'</td>';
		echo '<td nowrap="nowrap" style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.retorna_data($extra['datahora']).'</td>';
		echo '<td colspan=2>&nbsp;</td>';
		echo '</tr>';
		}
	echo '</table></td></tr><tr><td>&nbsp;</td></tr></table></td></tr></table>';
	if (!$dialogo) echo sombra_baixo('', 770); 	
	}


$sql->adTabela('msg_tarefa_historico');
$sql->esqUnir('msg_usuario','msg_usuario','msg_usuario.msg_usuario_id=msg_tarefa_historico.msg_usuario_id');
$sql->adCampo('msg_tarefa_historico.data, msg_tarefa_historico.progresso, nome_para, funcao_para, copia_oculta, de_id, para_id');
$sql->adOnde('msg_id ='.$msg_id);
$sql->adOrdem('data ASC');
$porcentagens = $sql->Lista();
$sql->limpar();

if(count($porcentagens)){
	
	echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td><table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.($dialogo ? 'FFFFFF' : $cor['cor_encamihamentos']).'" cellspacing=1 width="770" cellpadding=0>';
	echo '<tr><td colspan=5 align="center" style="font-size:12pt;"><b>Histórico das Porcentagens</b></td></tr>';
	echo '<tr><td colspan=5><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
	echo '<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>'.$config['usuario'].'</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>%</b></td></tr>'; 	
	foreach($porcentagens as $linha) {
		echo '<tr>';
		echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.retorna_data($linha['data']).'</td>';
		echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.formata_destinatario2($linha).'</td>';
		echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.$linha['progresso'].'</td>';
		echo '</tr>';
		
		}
	echo '</table></td></tr></table></td></tr></table>';
	if (!$dialogo) echo sombra_baixo('', 770); 
	}



function formata_despacho2 ($rs_anotf=array()){
	global $Aplic;
	$saida='';
	if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '<b>';
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
  if ($rs_anotf['copia_oculta'] !=1 || ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= nome_funcao($rs_anotf['nome_para'], $rs_anotf['nome_usuario'], $rs_anotf['funcao_para'], $rs_anotf['contato_funcao'])."&nbsp;&nbsp;";
  if ($rs_anotf['copia_oculta'] ==1 && ($rs_anotf['de_id']==$Aplic->usuario_id || $rs_anotf['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 )) $saida.= '</i>'; 
  if ($rs_anotf['para_id'] == $Aplic->usuario_id ) $saida.= '</b>';
  return $saida;
	}
	
	
function formata_destinatario2($rs_para=array()){
	global $Aplic,$tipos_status;
	$saida='';
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3)) $saida.= '<i>';
	$saida.=($rs_para['copia_oculta'] !=1|| $rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id || $Aplic->usuario_acesso_email > 3 ? nome_funcao($rs_para['nome_para'], '', $rs_para['funcao_para'], '') : 'oculto');
	if (($rs_para['copia_oculta'] ==1) && ($rs_para['de_id']==$Aplic->usuario_id || $rs_para['para_id']==$Aplic->usuario_id  || $Aplic->usuario_acesso_email > 3)) $saida.= '</i>';
	return $saida;	
	}

	
?>
<script language=Javascript>

function mostrar_esconder(campo, numero){
	if (document.getElementById(campo+numero).style.display == 'none'){
		document.getElementById(campo+numero).style.display = '';
		if (document.getElementById('2'+campo+numero)) document.getElementById('2'+campo+numero).style.display = '';
		}
	else {
		document.getElementById(campo+numero).style.display = 'none';
		if (document.getElementById('2'+campo+numero)) document.getElementById('2'+campo+numero).style.display = 'none';
		}
	}


</script>	