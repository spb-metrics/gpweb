<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', 0));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : 0);

$usuario_id = getParam($_REQUEST, 'usuario_id', 0);

$mudar = getParam($_REQUEST, 'mudar', 0);

$pertenceID=getParam($_REQUEST, 'pertenceID', array());
$permitidoID=getParam($_REQUEST, 'permitidoID', array());

if (!$dialogo) $Aplic->salvarPosicao();
echo '<form name="frm_filtro" method="POST">';
echo '<input type="hidden" name="m" value="'.$m.'" />';
echo '<input type="hidden" name="a" value="'.$a.'" />';
echo '<input type="hidden" name="u" value="'.$u.'" />';
$botoesTitulo = new CBlocoTitulo(ucfirst($config['usuario']).' nos Grupos', 'grupos.png', $m, $m.'.'.$a);
$procurar_om='<tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td colspan=2><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></tr>';
$procurar_usuario='<tr><td align=right>'.dica(ucfirst($config['usuario']), 'Filtrar pel'.$config['genero_usuario'].' '.$config['usuario'].' escolhido na caixa de seleção à direita.').ucfirst($config['usuario']).':'.dicaF().'</td><td colspan="2"><input type="hidden" id="usuario_id" name="usuario_id" value="'.$usuario_id.'" /><input type="text" id="nome_responsavel" name="nome_responsavel" value="'.nome_usuario($usuario_id).'" style="width:250px;" class="texto" READONLY /></td><td><a href="javascript: void(0);" onclick="popResponsavel();">'.imagem('icones/usuarios.gif','Selecionar '.ucfirst($config['usuario']),'Clique neste ícone '.imagem('icones/usuarios.gif').' para selecionar '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'.').'</a></td></tr>';
$botoesTitulo->adicionaCelula('<table cellspacing=0 cellpadding=0>'.$procurar_om.$procurar_usuario.'</table>');
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
echo '</form>';

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="m" id="m" value="sistema">';
echo '<input type=hidden name="u" id="u" value="email">';
echo '<input type=hidden name="a" id="a" value="usuario_grupos">';
echo '<input type=hidden name="mudar" id="mudar" value="">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="">';




$sql = new BDConsulta;




if (!$usuario_id){
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" class="std">';
	echo '<tr><td align="center"><h1>Selecione '.($config['genero_usuario']=='o' ? 'um' : 'uma').' '.$config['usuario'].'</h1></td></tr>';
	echo '</table>';
	echo estiloFundoCaixa();	
	}


if ($usuario_id){
	
	echo estiloTopoCaixa();
	echo '<table width="100%" align="center" class="std"><tr><td><table cellpadding=2 cellspacing=0 align="center" class="tbl1">';
	echo '<tr><td align="center" colspan=20><h1>'.nome_om($usuario_id,$Aplic->getPref('om_usuario'), true).'</h1></td></tr>';
	
	$sql->adTabela('usuariogrupo');
	$sql->adCampo('grupo_id');
	$sql->adOnde('usuario_id='.$usuario_id);
	$grupos_pertencentes = $sql->ListaChave('grupo_id');
	$sql->limpar();
	
	$sql->adTabela('grupo');
	$sql->adTabela('usuarios');
	$sql->esqUnir('usuariogrupo', 'usuariogrupo', 'usuariogrupo.usuario_id = usuarios.usuario_id AND grupo.grupo_id=usuariogrupo.grupo_id');
	
	$sql->esqUnir('grupo_permissao', 'grupo_permissao', 'grupo_permissao.usuario_id = usuarios.usuario_id AND grupo.grupo_id=grupo_permissao.grupo_id');
	$sql->esqUnir('cias', 'cias', 'grupo_cia = cias.cia_id');
	$sql->adCampo('DISTINCT grupo.grupo_id, grupo_descricao, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp1 WHERE gp1.grupo_id=grupo.grupo_id) AS protegido, (SELECT COUNT(usuario_id) FROM grupo_permissao AS gp2 WHERE gp2.grupo_id=grupo.grupo_id AND gp2.usuario_id='.$usuario_id.') AS pertence, grupo_cia, cia_nome');
	$sql->adOnde('grupo_usuario=0 OR grupo_usuario IS NULL');
	$sql->adOnde('usuariogrupo.usuario_id='.$usuario_id.' OR grupo_permissao.usuario_id='.$usuario_id);
	$sql->adOrdem('grupo_cia DESC, grupo_descricao ASC');
	$lista_grupos=$sql->Lista();
	$sql->limpar();	
	
	$cia_atual='';
	echo '<tr><td align="center"><b>Grupo</b></td><td width="70px" align="center"><b>Pertence</b></td><td width="60px" align="center"><b>Pode Ver</b></td></tr>';
	foreach ($lista_grupos as $rs){
		if ($cia_atual!=$rs['cia_nome'] || !$cia_atual){
			$cia_atual=$rs['cia_nome'];
			echo '<tr><td colspan=20 align="center"><h1>'.($rs['cia_nome'] ? $rs['cia_nome'] : 'Todas as '.$config['organizacao']).'</h1></td></tr>';
			}
		echo '<tr><td align="center">'.$rs['grupo_descricao'].'</td><td align="center">'.(isset($grupos_pertencentes[$rs['grupo_id']]) ? 'X' : '&nbsp;').'</td><td align="center">'.(!$rs['protegido'] || ( $rs['protegido'] && $rs['pertence']) ? 'X' : '&nbsp;').'</td></tr>';
		}
	if (!count($lista_grupos)) echo '<tr><td colspan=20 align="center">Não se encontra em nenhum grupo</td></tr>';	
	echo '</table></td></tr>';
	echo '</table>';
	
	
	echo estiloFundoCaixa();	
	}	


	
echo '</form>';
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
</script>