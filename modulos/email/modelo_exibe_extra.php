<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

echo '<br>';
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', 0);
$modelo_id=getParam($_REQUEST, 'modelo_id', 0);
$tipos_status=array('' => 'indefinido') + getSisValor('status');
$primeiro=0;
$sql = new BDConsulta; 
$sql->adTabela('modelos');
$sql->esqUnir('modelo_usuario','modelo_usuario','modelo_usuario.modelo_id = modelos.modelo_id');
$sql->adCampo('modelos.modelo_id');
if ($modelo_usuario_id) {
	$sql->adOnde('modelo_usuario.modelo_usuario_id = '.$modelo_usuario_id);
	$sql->adOnde('modelos.class_sigilosa <= '.$Aplic->usuario_acesso_email);
	}
else {
	$sql->adOnde('modelo_criador_original = '.$Aplic->usuario_id);
	}
$permitido = $sql->Resultado();
$sql->limpar();
if (!$permitido) {
	echo '<script language=Javascript>alert("Não tem permissão de acesso ao histórico deste documento");self.close();</script>';
	exit();
	}	
		
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
$sql->adTabela('modelos');
$sql->adCampo('modelo_data_protocolo, modelo_protocolo, modelo_protocolista, modelo_criador_original, modelo_data, modelo_autoridade_assinou, modelo_data_assinado, modelo_autoridade_aprovou, modelo_data_aprovado');
$sql->adOnde('modelo_id = '.$modelo_id);
$modelo = $sql->Linha();
$sql->limpar();
echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Histórico</b></td></tr>';
echo '<tr><td align=center><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
echo '<tr align=center><td><b>'.ucfirst($config['usuario']).'</b></td><td><b>Ação</b></td><td><b>Data</b></td></tr>';
$sql->adTabela('modelos_dados');
$sql->adCampo('modelos_dados_criador, modelo_dados_data');
$sql->adOnde('modelo_dados_modelo = '.$modelo_id);
//EUZ adicionada linha para Ordem
$sql->adOrdem('modelo_dados_data');
//EUD

$dados = $sql->Lista();
$sql->limpar();
$qnt=0;
foreach($dados as $dado) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_criador_original']).'</td><td>'.(!$qnt++ ? 'Criou' : 'Editou').'</td><td>'.retorna_data($dado['modelo_dados_data']).'</td></tr>';
if ($modelo['modelo_autoridade_assinou']) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_autoridade_assinou']).'</td><td>Assinou</td><td>'.retorna_data($modelo['modelo_data_assinado']).'</td></tr>';
elseif ($modelo['modelo_autoridade_aprovou']) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_autoridade_aprovou']).'</td><td>Aprovou</td><td>'.retorna_data($modelo['modelo_data_aprovado']).'</td></tr>';
if ($modelo['modelo_protocolista']) echo '<tr align=center><td>'.nome_funcao('','','','',$modelo['modelo_protocolista']).'</td><td>Protocolou</td><td>'.retorna_data($modelo['modelo_data_protocolo']).'</td></tr>';
if ($modelo['modelo_protocolo']) echo '<tr align=center><td><b>Protocolo :&nbsp;</b>'.$modelo['modelo_protocolo'].'</td><td colspan=2>&nbsp;</td></tr>';
echo '</table></td></tr>';
echo '<tr><td>&nbsp;</td></tr>';
echo '</table>';
//echo sombra_baixo('', 790);
$sql->adTabela('modelo_anotacao');
$sql->adUnir('usuarios','usuarios','modelo_anotacao.usuario_id = usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_anotacao_usuarios, modelo_anotacao.datahora, modelo_anotacao.usuario_id, modelo_anotacao.nome_de, modelo_anotacao.funcao_de, modelo_anotacao.texto, modelo_anotacao.tipo, contato_funcao, modelo_anotacao_id');
$sql->adOnde('modelo_id = '.$modelo_id);
//EUZ retirado o DESC
$sql->adOrdem('modelo_anotacao_id');
//EUD

$sql_resultadosb = $sql->Lista();
$sql->limpar();
$outros_despachos=array();
foreach ($sql_resultadosb as $rs_anot){ 
	if ($rs_anot['tipo'] == 1 ) { 
		//despacho
		$vetor_destinatarios=array();
		$saida = '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		$saida.= '<table align="center" cellspacing=0 width="790" cellpadding=0>';
		$saida.= '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['modelo_anotacao_id'].');">Despacho de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
		$saida.= '<tr id="linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr>';
		$saida.= '<tr id="2linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:8pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_despacho'].'"><table cellspacing=0 cellpadding=0><tr><td><b>Para</b>:</td><td>';
		$sql->adTabela('modelo_usuario');
		$sql->adUnir('usuarios','usuarios','modelo_usuario.para_id = usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adCampo('modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, contato_funcao');
		$sql->adOnde('modelo_id = '.$modelo_id);
		$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
		$sql->adOnde('modelo_usuario.datahora=\''.$rs_anot['datahora'].'\'');
		//EUZ postgres
		//$sql->adGrupo('para_id');
    $sql->adGrupo('para_id, contatos.contato_posto, contatos.contato_nomeguerra, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, contato_funcao');
		//EUD

		$destinatarios_despacho = $sql->Lista();
		$sql->limpar();
	  $quant=0; 
	  $primeira_linha=0; 
		if (!count($destinatarios_despacho)){
	  	$sql->adTabela('modelo_usuario');
			$sql->adUnir('usuarios','usuarios','modelo_usuario.para_id = usuarios.usuario_id');
			$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
			$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
			$sql->adCampo('modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, contato_funcao');
			$sql->adOnde('modelo_id = '.$modelo_id);
			$sql->adOnde('de_id = '.$rs_anot['usuario_id']);
			
			$sql->adOnde('modelo_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
      $sql->adGrupo('para_id');
			
			$sql->adOnde('modelo_usuario.datahora BETWEEN adiciona_data(\''.$rs_anot['datahora'].'\', -60, \'SECOND\') AND adiciona_data(\''.$rs_anot['datahora'].'\', 60, \'SECOND\')');
			$sql->adGrupo('para_id');
			$destinatarios_despacho = $sql->Lista();
			$sql->limpar();
	  	}
	  	
	  if (isset($destinatarios_despacho[0]['para_id'])&& $destinatarios_despacho[0]['para_id']) $vetor_destinatarios[]=$destinatarios_despacho[0]['para_id'];
		if (isset($destinatarios_despacho[0]) && $destinatarios_despacho[0]) $saida.= formata_despacho($destinatarios_despacho[0]);
		$qnt_destinatario=count($destinatarios_despacho);
		if ($qnt_destinatario > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_destinatario; $i < $i_cmp; $i++) {
					$lista.= formata_despacho($destinatarios_despacho[$i]).'<br>';
					$vetor_destinatarios[]=$destinatarios_despacho[$i]['para_id'];
					}		
				$saida.= dica('Outros Destinatários', 'Clique para visualizar os demais destinatários.').' <a href="javascript: void(0);" onclick="mostrar_esconder(\'despacho_\', '.$rs_anot['modelo_anotacao_id'].');">(+'.($qnt_destinatario - 1).')</a>'.dicaF(). '<span style="display: none" id="despacho_'.$rs_anot['modelo_anotacao_id'].'"><br>'.$lista.'</span>';
				}
		$saida.= '</td></tr></table></td></tr></table>';
		$saida.= '</td></tr></table>'; 
		if (in_array($Aplic->usuario_id, $vetor_destinatarios) || $rs_anot['usuario_id']==$Aplic->usuario_id) echo $saida;
		else $outros_despachos[]=$saida;
		} 
	else if ($rs_anot['tipo'] == 2 ){ 
		echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		echo '<table align="center" cellspacing=0 width="790" cellpadding=0>';
	  echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_resposta'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['modelo_anotacao_id'].');">Resposta de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao'])." em ".retorna_data($rs_anot['datahora']).'</a></td></tr>';
	  echo '<tr id="linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px;  background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr></table>';
		echo '</td></tr></table>'; 
		} 
	else if ($rs_anot['tipo'] == 4 ){
		$pode_ver=0;
		if (!$rs_anot['modelo_anotacao_usuarios'] || $rs_anot['usuario_id']==$Aplic->usuario_id) $pode_ver=1;
		else {
			$sql->adTabela('modelo_anotacao_usuarios');
			$sql->adOnde('usuario_id');
			$sql->adOnde('modelo_anotacao_id = '.$rs_anot['modelo_anotacao_id']);
			$sql->adOnde('usuario_id='.$Aplic->usuario_id);
			$pode_ver= $sql->Resultado();
			$sql->limpar();
			}
		if ($pode_ver){
			echo '<table rules="ALL" border="1" cellspacing=0 cellpadding=0 align="center"><tr><td>';
		  echo '<table align="center" cellspacing=0 width="790" cellpadding=0>';
		  echo '<tr><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_anotacao'].'" ><a href="javascript:void(0);" onclick="javascript: mostrar_esconder(\'linha1_\', '.$rs_anot['modelo_anotacao_id'].');">Nota de '.nome_funcao($rs_anot['nome_de'], $rs_anot['nome_usuario'], $rs_anot['funcao_de'], $rs_anot['contato_funcao']).' em '.retorna_data($rs_anot['datahora']).'</a></td></tr>';
		  echo '<tr id="linha1_'.$rs_anot['modelo_anotacao_id'].'" style="display:none"><td style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_fundo'].'">'.$rs_anot['texto'].'</td></tr></table>';     
		  echo '</td></tr></table>'; 
			}
	  } 
	}    
//if (count($sql_resultadosb)) echo sombra_baixo('', 790); 
if (count($outros_despachos))	{
	echo '<table align="center"><tr><td>'.dica('Outros Despachos','Clique neste link para visualizar os outros despachos efetados n'.($config['genero_mensagem']=='a' ? 'esta' : 'este').' '.$config['mensagem'].'.').'<a href="javascript:void(0);" onclick="javascript:mostrar_esconder(\'outros_despacho\', \'\');" style="padding-left: 5px; font-size:10pt; font-weight:Bold;">Outros despachos ('.count($outros_despachos).')</a>'.dicaF().'</td></tr></table>';
	echo '<span style="display: none" id="outros_despacho">';
	foreach($outros_despachos as $outro) echo $outro;
	echo '<br></span>';
	}
$sql->adTabela('modelo_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=de_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
$sql->adCampo('modelo_usuario_id, data_retorno, data_limite, resposta_despacho, modelo_usuario.tipo, modelo_usuario.de_id, modelo_usuario.nome_de, modelo_usuario.funcao_de, modelo_usuario.para_id, modelo_usuario.nome_para, modelo_usuario.funcao_para, modelo_usuario.copia_oculta, modelo_usuario.status, modelo_usuario.datahora_leitura, modelo_usuario.cm, modelo_usuario.meio, usuarios.usuario_id, contato_funcao, datahora');
$sql->adOnde('modelo_id = '.$modelo_id);
//EUZ adicionada linha para Ordem
$sql->adOrdem('datahora');
//EUD

$sql_resultadosf = $sql->Lista();
$sql->limpar();
$tipo=array('0'=>'envio', '1'=>'despacho', '2'=>'resposta', '3'=>'encaminhamento', '4'=>'nota');
$objeto_data = new CData();
$agora=$objeto_data->format(FMT_TIMESTAMP_MYSQL);
if ($sql_resultadosf && count($sql_resultadosf)){
	echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
	echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Tramitação do documento</b></td></tr>';
	echo '<tr><td><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
	echo '<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Tipo</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>De</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Para</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Envio</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Leitura</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Status</b></td></tr>'; 	

	foreach ($sql_resultadosf as $rs_enc){ 
	  if (($rs_enc['copia_oculta'] !=1) || ($rs_enc['de_id']==$Aplic->usuario_id || $rs_enc['para_id']==$Aplic->usuario_id )) {
	    if ($rs_enc['tipo']==1 && !$rs_enc['data_limite']) $cor_campo='FFFFFF';
	    elseif ($rs_enc['tipo']==1 && (($rs_enc['data_retorno']> $rs_enc['data_limite']) || ($rs_enc['data_limite']< $agora && !$rs_enc['data_retorno']))) $cor_campo='FFCCCC';
	    elseif ($rs_enc['tipo']==1 && ($rs_enc['data_retorno']<= $rs_enc['data_limite'])) $cor_campo='CCFFCC';
	    else $cor_campo='FFFFFF';
	    echo '<tr>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px; background-color:#'.$cor_campo.'">'.$tipo[$rs_enc['tipo']].($rs_enc['resposta_despacho'] ? '<a href="javascript: void(0);" onclick="mostrar_esconder(\'despacho_\', '.$rs_enc['modelo_usuario_id'].');">'.imagem('icones/msg10000.gif','Resposta ao Despacho','Clique neste ícone '.imagem('icones/msg1000.gif').' para visualizar a resposta ao despacho.').'</a>' :'').'</td>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.nome_funcao($rs_enc['nome_de'], '', $rs_enc['funcao_de'], '').'</td>';
	    echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.formata_destinatario($rs_enc).'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".retorna_data($rs_enc['datahora']).'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>";
			if (!$rs_enc['datahora_leitura'])	echo 'Não Lida';
			else echo retorna_data($rs_enc['datahora_leitura']).($rs_enc['cm'] == 1 ? '(CM:'.nome_usuario($rs_enc['cm']).' por '.$rs_enc['meio'].')' : '');
			echo '</td>';
			echo '<td style="font-size:7pt; padding-left: 2px; padding-right: 2px;">'.$tipos_status[$rs_enc['status']].'</td>';
			echo '</tr>';
			if ($rs_enc['resposta_despacho']) echo '<tr id="despacho_'.$rs_enc['modelo_usuario_id'].'" style="display:none;"><td colspan=20>'.$rs_enc['resposta_despacho'].'</td></tr>';
			}
		}
	echo '</table></td></tr><tr><td>&nbsp;</td></tr></table>';
	//echo sombra_baixo('', 790); 	
	}
	


$sql->adTabela('modelo_leitura');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=modelo_leitura.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('modelo_leitura.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, datahora_leitura');
$sql->adOnde('modelo_id = '.$modelo_id);
//$sql->adGrupo('modelo_leitura.usuario_id');
//EUZ
$sql->adGrupo('modelo_leitura.usuario_id, contatos.contato_posto, contatos.contato_posto, contatos.contato_nomeguerra, modelo_leitura.datahora_leitura');
//EUD

$sql->adOrdem('datahora_leitura ASC');
$linhas = $sql->Lista();
$sql->limpar();	


if ($linhas && count($linhas)){
	echo '<table align="center" style="font-size:10pt; padding-left: 5px; padding-right: 5px; background-color: #'.$cor['cor_encamihamentos'].'" cellspacing=0 width="790" cellpadding=0>';
	echo '<tr><td colspan="5" align="center" style="font-size:12pt;"><b>Histórico de leitura</b></td></tr>';
	echo '<tr><td><table align="center" class="tbl1" cellspacing=0 width="100%" cellpadding=0>';
	echo '<tr><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>'.ucfirst($config['usuario']).'</b></td><td style="font-size:9pt; padding-left: 2px; padding-right: 2px;"><b>Data de Leitura</b></td></tr>'; 	

	foreach ($linhas as $linha){ 
	    echo '<tr>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".$linha['nome_usuario'].'</td>';
	    echo "<td nowrap='nowrap' style='font-size:7pt; padding-left: 2px; padding-right: 2px;'>".retorna_data($linha['datahora_leitura']).'</td>';
			echo '</tr>';
			}
		}
	echo '</table></td></tr><tr><td>&nbsp;</td></tr></table>';
	//echo sombra_baixo('', 790); 	



	

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

function formata_destinatario($rs_para=array()){
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