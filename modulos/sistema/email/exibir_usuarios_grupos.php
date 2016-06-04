<?php  
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', 0));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', 0));
$usuario_id = ($Aplic->getEstado('usuario_id') ? $Aplic->getEstado('usuario_id') : 0);
if (!$dialogo) $Aplic->salvarPosicao();
echo '<form name="frm_filtro" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';

$botoesTitulo = new CBlocoTitulo('Grupos X '.ucfirst($config['usuarios']), 'grupos.png', $m, $m.'.'.$a);
$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.frm_filtro.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' responsável.').'</a></td></tr>';
$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.'</table>');
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
echo '</form>';


echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden id="m" name="m" value="email">';
echo '<input type=hidden id="u" name="u" value="">';
echo '<input type=hidden id="a" name="a" value="editar_pastas">';
echo '<input type=hidden name="grupo_id" id="grupo_id" value="0">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="0">';
echo '</form>';

echo estiloTopoCaixa();
echo '<table align="center" class="std" cellspacing=0 width="100%" cellpadding=0><tr><td>&nbsp;</td></tr><tr><td>';
$saida= '<table align="center" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
$saida.= '<td valign="BOTTOM" align="center" ><b>'.ucfirst($config['usuarios']).'</b></td>';
$sql = new BDConsulta;
$sql->adTabela('grupo');
$sql->adCampo('grupo_id, grupo_descricao, grupo_cia');
$sql->adOnde('grupo_usuario IS NULL');
if ($cia_id) $sql->adOnde('grupo_cia='.$cia_id.' OR grupo_cia=0 OR grupo_cia IS NULL');
$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
$sql_resultados1 = $sql->Lista();
$sql->limpar();
foreach($sql_resultados1 as $rs){
	$saida.= '<td valign="bottom" align="center" '.(!$rs['grupo_cia'] ? 'style="background-color: #e6e6e6;"' : '').'><a href="javascript:void(0);" onclick="javascript:exibir_grupo('.$rs["grupo_id"].');"><font face="Verdana" size=1><b>';
	$valor=$rs['grupo_descricao']; 
	for ($i=0; $i< strlen($rs['grupo_descricao']); $i++) { 
		$saida.= $valor[$i].'<br>';
		}
	$saida.= '</b></a></td>';
	}
$saida.= '</tr>';


$sql->adTabela('usuarios');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->esqUnir('cias', 'cias', 'contato_cia = cia_id');
$sql->adCampo('cia_nome, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, usuario_id, contato_posto_valor');
$sql->adOnde('usuario_ativo=1');	
$sql->adOnde('contato_cia='.(int)$cia_id);
if ($usuario_id) $sql->adOnde('usuario_id='.$usuario_id);
$sql->adOrdem(($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? ($Aplic->usuario_prefs['nomefuncao'] ? ($config['militar'] < 10 ? 'contato_posto_valor ASC, contato_nomeguerra ASC' : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC') : 'contato_nomeguerra ASC') : 'contato_funcao ASC, contato_nomeguerra ASC'));	
$sql_usuarios = $sql->Lista();
$sql->limpar();

$sem_grupo = array();
$cia_atual='';

foreach($sql_usuarios AS $rs){	
	if ($cia_atual!=$rs['cia_nome']){
		$cia_atual=$rs['cia_nome'];
		$saida.= '<tr><td colspan=50 align="center"><h1>'.$rs['cia_nome'].'</h1></td></tr>';
		}
	$saida.= '<tr><td><a href="javascript:void(0);" onclick="javascript:exibir_usuario('.$rs['usuario_id'].');">'.($Aplic->usuario_prefs['nomefuncao'] ? $rs['nome_usuario'].($rs['contato_funcao'] && $rs['nome_usuario'] && $Aplic->usuario_prefs['exibenomefuncao']? ' - ' : '').($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '') : ($Aplic->usuario_prefs['exibenomefuncao'] ? $rs['contato_funcao'] : '').($rs['nome_usuario'] && $rs['contato_funcao'] && $Aplic->usuario_prefs['exibenomefuncao'] ? ' - ' : '').$rs['nome_usuario']).'</a></td>';
	$contagem=0;
	$sql->adTabela('grupo');
	$sql->adCampo('grupo_id, grupo_descricao');
	$sql->adOnde('grupo_usuario IS NULL');
	$sql->adOrdem('grupo_descricao ASC');
	if ($cia_id) $sql->adOnde('grupo_cia='.$cia_id.' OR grupo_cia=0 OR grupo_cia IS NULL');
	$sql_resultados3 = $sql->Lista();
	$sql->limpar();
	foreach($sql_resultados3 as $rg){	
		$sql->adTabela('usuariogrupo');
		$sql->adCampo('usuario_id');
		$sql->adOnde('grupo_id = '.$rg['grupo_id']);
		$sql->adOnde('usuario_id = '.$rs['usuario_id']);
		$pertence = $sql->Resultado();
		$sql->limpar();
		if ($pertence){
			$saida.= "<td align='center'>X</td>"; 
			$contagem++;
			} 
		else $saida.= "<td>&nbsp;</td>"; 
		}	
	$saida.= "</tr>";
	}


if (!$sql_usuarios || !count($sql_usuarios || !$sql_resultados3 || !count($sql_resultados3))) $saida='<table align="center" cellpadding=0 cellspacing=0 class="tbl1" width="50%"><tr><td align="center"><br><h1>Não há '.$config['usuarios'].' ou grupos disponíveis</h1><br>&nbsp;</td></tr>';
$saida.= '</table></td></tr><tr><td>&nbsp;</td></tr></table>';	
echo $saida;
echo estiloFundoCaixa(); 
?>
<script language="javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}

function popResponsavel(campo) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Responsável', 500, 500, 'm=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, window.setResponsavel, window);
	else window.open('./index.php?m=publico&a=selecao_unico_usuario&dialogo=1&chamar_volta=setResponsavel&cia_id='+document.getElementById('cia_id').value+'&usuario_id='+document.getElementById('usuario_id').value, 'Responsável','height=500,width=500,resizable,scrollbars=yes, left=0, top=0');
	}	

function setResponsavel(usuario_id, posto, nome, funcao, campo, nome_cia){
	document.getElementById('usuario_id').value=usuario_id;		
	document.getElementById('nome_responsavel').value=posto+' '+nome+(funcao ? ' - '+funcao : '')+(nome_cia && <?php echo $Aplic->getPref('om_usuario') ?>? ' - '+nome_cia : '');	
	frm_filtro.submit();
	}	
	
function exibir_usuario(usuario){
	env.m.value="admin";
	env.a.value="ver_usuario";
	env.usuario_id.value=usuario;
	env.submit();	
	} 
	
function exibir_grupo(grupo){
	env.m.value="sistema";
	env.u.value="email";
	env.a.value="administracao";
	env.grupo_id.value=grupo; 
	env.submit();	
	} 	
</script>
