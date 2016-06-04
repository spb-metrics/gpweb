<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

global $estilo_interface, $localidade_tipo_caract, $onde;
require_once ($Aplic->getClasseSistema('libmail'));

$Aplic->carregarCKEditorJS();

if (isset($_REQUEST['pasta'])) $Aplic->setEstado('DespachosIdxPasta', getParam($_REQUEST, 'pasta', 0));
$pasta = ($Aplic->getEstado('DespachosIdxPasta')!== null ? $Aplic->getEstado('DespachosIdxPasta') : 0);

$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$ordenar = getParam($_REQUEST, 'ordenar', 'data_limite');
$ordem = getParam($_REQUEST, 'ordem', '0');
$tab = getParam($_REQUEST, 'tab', '0');

$mover=getParam($_REQUEST, 'mover', array());
$retornar=getParam($_REQUEST, 'retornar', '');
$data=getParam($_REQUEST, 'data', '');
if ($a=='ver_dia') $tab=$tab-1;
$modelo_usuario_id=getParam($_REQUEST, 'modelo_usuario_id', array());

$sql = new BDConsulta;
$tem_pasta=0;
//entrada
$sql->adTabela('pasta');
$sql->adCampo('count(pasta_id) as soma');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$tem_pasta = $sql->Resultado();
$sql->limpar();


if (getParam($_REQUEST, 'enviar_msg', 0)){
	$vetor_despacho=getParam($_REQUEST, 'vetor_despacho', array());
	$vetor_despacho=implode(',',$vetor_despacho);
	$modelo_assunto=getParam($_REQUEST, 'modelo_assunto', '');
	$texto=getParam($_REQUEST, 'texto', '');
	if ($tab==1){
		//destinatarios
		$sql->adTabela('modelo_usuario');
		$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=para_id');
		$sql->esqUnir('contatos','contatos','usuarios.usuario_contato=contatos.contato_id');
		$sql->adCampo('DISTINCT usuario_id, contato_email');
		$sql->adOnde('modelo_usuario.de_id = '.$Aplic->usuario_id);
		$sql->adOnde('tipo=1');
		$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
		$sql->adOnde('modelo_usuario.modelo_usuario_id IN ('.$vetor_despacho.')');
		$destinatarios = $sql->Lista();
		$sql->limpar();
		$email = new Mail;
        $email->De($config['email'], $Aplic->usuario_nome);

        if ($Aplic->usuario_email && $email->EmailValido($Aplic->usuario_email)){
            $email->ResponderPara($Aplic->usuario_email);
            }
        else if($Aplic->usuario_email2 && $email->EmailValido($Aplic->usuario_email2)){
            $email->ResponderPara($Aplic->usuario_email2);
            }

		foreach($destinatarios as $usuario){
			$email->Assunto($modelo_assunto, $localidade_tipo_caract);
			$email->Corpo($texto, isset($GLOBALS['locale_char_set']) ? $GLOBALS['locale_char_set'] : '');
			if ($usuario['usuario_id']!=$Aplic->usuario_id){
				msg_email_interno ('', $modelo_assunto, $texto,'',$usuario['usuario_id']);
				if ($email->EmailValido($usuario['contato_email']) && $config['email_ativo'] && $config['email_externo_auto']){
					$email->Para($usuario['contato_email'], true);
					$email->Enviar();
					}
				}
			}
		$Aplic->setMsg(ucfirst($config['mensagem']).' enviad'.$config['genero_mensagem'], UI_MSG_OK);
		}
	if ($tab==0){
		$sql->adTabela('modelo_usuario');
		$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=para_id');
		$sql->esqUnir('contatos','contatos','usuarios.usuario_contato=contatos.contato_id');
		$sql->adCampo('DISTINCT modelo_usuario_id');
		$sql->adOnde('modelo_usuario.para_id = '.$Aplic->usuario_id);
		$sql->adOnde('tipo=1');
		$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
		$sql->adOnde('modelo_usuario.modelo_usuario_id IN ('.$vetor_despacho.')');
		$despachos = $sql->Lista();
		$id_mensagens=array();
		foreach ($despachos as $despacho) $id_mensagens[]=$despacho['modelo_usuario_id'];
		$sql->limpar();
		$id_mensagens=implode(',',$id_mensagens);
		$sql->adTabela('modelo_usuario');
		$sql->adAtualizar('data_retorno', date('Y-m-d H:i:s'));
		$sql->adAtualizar('resposta_despacho', $texto);
		$sql->adOnde('modelo_usuario_id IN ('.$id_mensagens.')');
		if (!$sql->exec()) die('Não foi possível atualizar modelo_usuario.');
		$sql->limpar();
		$Aplic->setMsg('Resposta ao despacho enviada', UI_MSG_OK);
		}
	}

$sql->adTabela('modelo_usuario');
$sql->esqUnir('modelo_anotacao','modelo_anotacao','modelo_anotacao.modelo_anotacao_id=modelo_usuario.modelo_anotacao_id');
$sql->adCampo('modelo_usuario.modelo_usuario_id, modelo_anotacao.modelo_anotacao_id, modelo_anotacao.datahora, data_retorno, resposta_despacho, texto, data_limite, de_id');
if ($tab==1 || $tab==3) $sql->adOnde('modelo_usuario.de_id = '.$Aplic->usuario_id);
if ($tab==0 || $tab==2) $sql->adOnde('modelo_usuario.para_id = '.$Aplic->usuario_id);
$sql->adOnde('modelo_usuario.tipo=1');
if ($pasta>0 && ($tab==0 || $tab==2)) $sql->adOnde('despacho_pasta_receb='.$pasta);
if ($pasta>0 && ($tab==1 || $tab==3)) $sql->adOnde('despacho_pasta_envio='.$pasta);
if ($pasta==0 && ($tab==0 || $tab==2)) $sql->adOnde('despacho_pasta_receb<1');
if ($pasta==0 && ($tab==1 || $tab==3)) $sql->adOnde('despacho_pasta_envio<1');
if ($tab==0 || $tab==1)$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
if ($tab==2 || $tab==3)$sql->adOnde('resposta_despacho IS NOT NULL');
if ($onde ) $sql->adOnde('(modelo_anotacao.texto LIKE \'%'.$onde.'%\' OR modelo_anotacao.nome_de LIKE \'%'.$onde.'%\' OR modelo_anotacao.funcao_de LIKE \'%'.$onde.'%\' OR modelo_usuario.nome_para LIKE \'%'.$onde.'%\' OR modelo_usuario.funcao_para LIKE \'%'.$onde.'%\' OR nota LIKE \'%'.$onde.'%\' OR resposta_despacho LIKE \'%'.$onde.'%\')');
$sql->adOnde('modelo_anotacao.modelo_anotacao_id IS NOT NULL');
$sql->adGrupo('modelo_anotacao.modelo_anotacao_id');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$despachos = $sql->Lista();
$sql->limpar();



echo '<form method="POST" id="frmdespacho" name="frmdespacho">';
echo '<input type=hidden name="a" id="a" value="lista_despacho_modelo">';
echo '<input type=hidden name="m" id="m" value="email">';
echo '<input type=hidden name="tab" id="tab" value="'.$tab.'">';
echo '<input type=hidden name="enviar_msg" id="enviar_msg" value="">';
echo '<input type=hidden name="pasta" id="pasta" value="'.$pasta.'">';
echo '<input type=hidden id="retornar" name="retornar" value="'.$retornar.'">';

echo '<table id="tblTexto" border=0 cellpadding=0 cellspacing=1 width="100%" class="std" style="display:none;">';
if ($tab==1) echo '<tr><td align="left">Assunto:</td><td align="left" colspan=2 width="100%"><input class="texto" type="text" name="modelo_assunto" id="modelo_assunto" size="79" maxlength="79"></td></tr>';
echo '<tr><td colspan="3" align="left" style="background:#ffffff; width:800px; max-width:800px;"><textarea data-gpweb-cmp="ckeditor" rows="10" name="texto" id="texto" ></textarea></td></tr>';
echo '<tr><td>'.botao('cancelar', 'Cancelar','Clique neste botão para cancelar o envio '.($tab==0 ? 'da resposta ao despacho.' : 'd'.$config['genero_mensagem'].' '.$config['mensagem'].'.'),'','document.getElementById(\'tblTexto\').style.display=\'none\'; document.getElementById(\'tblDespachos\').style.display=\'\';').'</td><td colspan=20>'.botao('enviar', 'Enviar','Clique neste botão para enviar '.($tab==0 ? 'a resposta ao despacho.' : $config['genero_mensagem'].' '.$config['mensagem'].'.'),'','frmdespacho.submit();').'</td></tr>';


echo '</table>';

echo '<table id="tblDespachos" border=0 cellpadding=0 cellspacing=0 width="100%" class="tbl1">';

$xpg_tamanhoPagina = $config['qnt_despachos'];
$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1);
$xpg_totalregistros = ($despachos ? count($despachos) : 0);

$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'despacho', 'despachos','','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));


echo '<tr><th></th><th></th><th align="left"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=datahora&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='datahora' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data do Despacho', 'Clique para ordenar os despachos pela data em que o mesmo foi expedido.<br><br>Ao clicar em cima da data será mostrado o texto do despacho').'Data'.dicaF().'</a></th><th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=data_limite&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='data_limite' ? imagem('icones/'.$seta[$ordem]) : '').dica('Prazo', 'Clique para ordenar os despachos pelo prazo limite de resposta.').'Prazo'.dicaF().'</a></th>'.($tab==1 || $tab==3 ? '<th>Destinatários</th>' : '<th>Remetente</th>' ).'<th><a class="hdr" href="index.php?m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=modelo_usuario.modelo_id&ordem='.($ordem ? '0' : '1').'">'.($ordenar=='modelo_usuario.modelo_id' ? imagem('icones/'.$seta[$ordem]) : '').dica('Documento', 'Clique para ordenar os despachos pelo documento ao qual o mesmo faz referência.<br><br>Ao clicar em cima da data será mostrado o texto do despacho').'Documento'.dicaF().'</a></th></tr>';
for ($i = (($pagina - 1) * $xpg_tamanhoPagina); ($i < ($pagina * $xpg_tamanhoPagina)) && ($i < $xpg_totalregistros); $i++) {
	$linha = $despachos[$i];
	if($tab==1 || $tab==3){
		$sql->adTabela('modelo_usuario');
		$sql->adCampo('para_id, datahora');
		$sql->adOnde('modelo_usuario.de_id = '.$Aplic->usuario_id);
		$sql->adOnde('tipo=1');
		if($tab==1)$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
		if($tab==3)$sql->adOnde('resposta_despacho IS NOT NULL');
		$sql->adOnde('modelo_usuario.modelo_anotacao_id='.$linha['modelo_anotacao_id']);
		$destinatarios = $sql->Lista();
		$sql->limpar();
		$saida_destinatarios='';
		if ($destinatarios && count($destinatarios)) {

			$saida_destinatarios.= link_usuario($destinatarios[0]['para_id'], '','','esquerda');
			$qnt_destinatarios=count($destinatarios);
			if ($qnt_destinatarios > 1) {
				$lista='';
				for ($j = 1, $j_cmp = $qnt_destinatarios; $j < $j_cmp; $j++) $lista.=link_usuario($destinatarios[$j]['para_id'], '','','esquerda').'<br>';
				$saida_destinatarios.= dica('Outros Destinatários', 'Clique para visualizar os demais destinatarios do despacho que '.($tab==1 ? 'não ' : '').'responderam.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'destinatarios_'.$linha['modelo_anotacao_id'].'\');">(+'.($qnt_destinatarios - 1).')</a>'.dicaF(). '<span style="display: none" id="destinatarios_'.$linha['modelo_anotacao_id'].'"><br>'.$lista.'</span>';
				}
			}
		}
	$sql->adTabela('modelo_usuario');
	$sql->esqUnir('modelos','modelos','modelos.modelo_id=modelo_usuario.modelo_id');
	$sql->adCampo('DISTINCT modelos.modelo_id, modelo_usuario_id, modelo_assunto');
	if ($tab==1 || $tab==3) $sql->adOnde('modelo_usuario.de_id = '.$Aplic->usuario_id);
	if ($tab==0 || $tab==2) $sql->adOnde('modelo_usuario.para_id = '.$Aplic->usuario_id);
	$sql->adOnde('tipo=1');
	if ($tab==0 || $tab==1)$sql->adOnde('resposta_despacho IS NULL AND data_limite IS NOT NULL');
	if ($tab==2 || $tab==3)$sql->adOnde('resposta_despacho IS NOT NULL');
	$sql->adOnde('modelo_usuario.modelo_anotacao_id='.$linha['modelo_anotacao_id']);
	$sql->adGrupo('modelos.modelo_id');
	$mensagens = $sql->Lista();
	$sql->limpar();

	$saida_mensagens='';
	if ($mensagens && count($mensagens)) {
			$saida_mensagens.= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a=modelo_editar&modelo_id='.$mensagens[0]['modelo_id'].'\');">'.dica($mensagens[0]['modelo_assunto'], 'Clique para abrir este documento').$mensagens[0]['modelo_id'].' - '.$mensagens[0]['modelo_assunto'].dicaF();
			$qnt_mensagens=count($mensagens);
			if ($qnt_mensagens > 1) {
					$lista='';
					for ($j = 1, $j_cmp = $qnt_mensagens; $j < $j_cmp; $j++) $lista.='<a href="javascript:void(0);" onclick="url_passar(0, \'m=email&a='.$Aplic->usuario_prefs['modelo_msg'].'&modelo_usuario_id='.$mensagens[$j]['modelo_id'].'\');">'.dica($mensagens[$j]['modelo_assunto'], $mensagens[$j]['texto']).$mensagens[$j]['modelo_id'].' - '.$mensagens[$j]['modelo_assunto'].dicaF().'<br>';
					$saida_mensagens.= dica('Outr'.$config['genero_mensagem'].'s '.ucfirst($config['mensagens']), 'Clique para visualizar '.$config['genero_mensagem'].'s demais '.$config['mensagens'].'.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'mensagens_'.$linha['modelo_anotacao_id'].'\');">(+'.($qnt_mensagens - 1).')</a>'.dicaF(). '<span style="display: none" id="mensagens_'.$linha['modelo_anotacao_id'].'"><br>'.$lista.'</span>';
					}
			}


	$saida_texto_despacho=retorna_data($linha['data_retorno']);

	$cor=($linha['data_limite'] < date('Y-m-d H:i:s') ? 'ff0000' : '27740c');

	echo '<tr style="background-color:#ffffff;">';
	echo '<td width="16"><input type="checkbox" name="vetor_despacho[]" value="'.$linha['modelo_usuario_id'].'"></td>';
	echo '<td width="'.($tab > 1 ? '32' : '16').'"><a href="javascript: void(0);" onclick="expandir_colapsar(\'despacho_'.$linha['modelo_anotacao_id'].'\');">'.imagem('icones/msg10010.gif', 'Exibir Despacho', 'Clique neste ícone '.imagem('icones/msg10010.gif').' para exibir o texto do despacho.').'</a>'.($tab > 1 ? '<a href="javascript: void(0);" onclick="expandir_colapsar(\'resposta_despacho_'.$linha['modelo_usuario_id'].'\');">'.imagem('icones/msg10020.gif', 'Exibir Resposta ao Despacho', 'Clique neste ícone '.imagem('icones/msg10020.gif').' para exibir o texto da resposta ao despacho.').'</a>' : '').'</td>';
	echo '<td>'.$saida_texto_despacho.'</td>';
	echo '<td style="color:#'.$cor.';">'.($linha['data_limite'] ? retorna_data($linha['data_limite']) :'&nbsp;').'</td>';
	echo ($tab==1 || $tab==3 ? '<td>'.$saida_destinatarios.'</td>' : '<td>'.link_usuario($linha['de_id'],'','','esquerda').'</td>');
	echo '<td>'.$saida_mensagens.'</td></tr>';
	echo '<tr style="display: none" id="despacho_'.$linha['modelo_anotacao_id'].'"><td colspan=20 class="realce">'.$linha['texto'].'</td></tr>';


	if($tab > 1) echo '<tr style="display: none" id="resposta_despacho_'.$linha['modelo_usuario_id'].'"><td colspan=20 class="realce">'.$linha['resposta_despacho'].'</td></tr>';
	}

//responder clicando no calendario
if ($modelo_usuario_id) {
	echo '<input type="hidden" name="vetor_despacho[]" value="'.$modelo_usuario_id.'">';
	echo '<script>frmdespacho.enviar_msg.value=1; document.getElementById("tblTexto").style.display=\'\'; document.getElementById("tblDespachos").style.display=\'none\';</script>';
	}


if (!$xpg_totalregistros) echo '<tr><td colspan=20>Não existe despacho '.($tab==0 || $tab==2 ? 'recebido' : 'enviado').($tab==0 || $tab==1 ? ' não' : '').' respondido'.($pasta>0 ? ' nesta pasta' : '').'</td></tr>';


echo '</table>';

echo '<table cellpadding=0 cellspacing=0 width="100%" class="std">';

if ($tab==0 && $xpg_totalregistros) echo '<tr><td colspan=20>'.dica('Responder Despacho','Clique neste botão para enviar uma resposta aos despachos selecionados.').'<a class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>responder despacho</b></span></a>'.dicaF().'</td></tr>';
if ($tab==1 && $xpg_totalregistros) echo '<tr><td colspan=20>'.dica('Enviar '.ucfirst($config['mensagem']),'Clique neste botão para enviar '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para os destinatários dos despachos selecionados.').'<a class="botao" href="javascript:void(0);" onclick="javascript:enviar();"><span><b>enviar '.$config['mensagem'].'</b></span></a>'.dicaF().'</td></tr>';
echo '<tr><td colspan=20><table>'.($tem_pasta ? '<td>'.dica('Caixa de Seleção de Pasta','Selecione na caixa de opção em qual pasta deseja entrar para ver os documentos armazenados.<BR><BR>Para mover '.($config['genero_mensagem']=='a' ? 'uma': 'um').' '.$config['mensagem'].' para a pasta, selecione a mesma e utilize a próxima caixa de seleção, do lado direito.').'<a>'.comboPasta($Aplic->usuario_id, $pasta).'</a>'.dicaF().'</td>' : '').'</table></td></tr>';
echo '</table>';

echo '</form>';

function comboPasta($usuario_id, $pasta) {
	global $Aplic;
	$sql = new BDConsulta;
	$s = '<select id="codigo_pasta" name="codigo_pasta" class=text size=1 onchange="resulta_combo();">';
	$s .= '<option value="0" '.($pasta==0 ? ' selected="selected"' : '').' >fora das pastas</option>';
	$s .= '<option value="-1" '.($pasta==-1 ? ' selected="selected"' : '').' >todas as pastas</option>';
	$sql->adTabela('pasta');
  $sql->adCampo('pasta_id,nome');
	$sql->adOnde('pasta.usuario_id='.$usuario_id);
	$pastas=$sql->Lista();
	$sql->limpar();
	foreach ($pastas as $linha) $s .= '<option value="'.$linha['pasta_id'].'"'.(($linha['pasta_id'] == $pasta ) ? ' selected="selected"' : '').'>'.$linha['nome'].'</option>';
	$s .= '</select>';
	return $s;
	}

?>

<script language=Javascript>

function resulta_combo() {
  document.getElementById('pasta').value=document.getElementById('codigo_pasta').value;
	document.getElementById('frmdespacho').submit();
  }


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}

function enviar(){
 if (verifica_selecao()){
 		frmdespacho.enviar_msg.value=1
 		document.getElementById("tblTexto").style.display='';
 		document.getElementById("tblDespachos").style.display='none';
 		}
	}

function verifica_selecao(){
	var j=0;
	for(i=0;i < document.getElementById('frmdespacho').elements.length;i++) {
		if (document.getElementById('frmdespacho').elements[i].checked) j++;
		}
	if (j>0) return 1;
	else {
		alert ("Selecione ao menos um despacho!");
		return 0;
		}
	}

function editar_pastas(){
 	frmdespacho.a.value="editar_pastas";
 	frmdespacho.retornar.value="lista_despacho_modelo";
	frmdespacho.submit();
  }

</script>