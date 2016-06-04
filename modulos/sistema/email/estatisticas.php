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


if (!isset($lista) && isset($_REQUEST["lista"])) $lista = getParam($_REQUEST, 'lista', null); else $lista = 1; 
$sql = new BDConsulta;

echo '<form method="POST" name="env" id="env">';
echo '<input type=hidden name="m" id="m" value="'.$m.'">';
echo '<input type=hidden name="u" id="u" value="'.$u.'">';
echo '<input type=hidden name="a" id="a" value="'.$a.'">';
echo '<input type=hidden name="lista" id="lista" value="">';


$botoesTitulo = new CBlocoTitulo('Estatísticas', 'estatistica.png', $m);
$procurar_om='<table><tr><td align=right>'.dica('Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png','Filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'],'Clique neste ícone '.imagem('icones/filtrar_p.png').' para filtrar pel'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a></td></tr></table>';
$botoesTitulo->adicionaCelula($procurar_om);
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
echo '</form>';

echo estiloTopoCaixa();
echo '<table align="center" border=0 cellspacing=0 cellpadding=0  class= "std2" width="100%">';
echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';	
echo '<tr><td width="100">&nbsp;</td><td align="left"><a href="javascript:void(0);" onclick="javascript:env.lista.value=2; env.submit();"><b>Total de '.$config['mensagens'].'</b></a></td><td>&nbsp;</td></tr>';	
echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';	
echo '<tr><td >&nbsp;</td><td align="left"><a href="javascript:void(0);" onclick="javascript:env.lista.value=1; env.submit();"><b>Total de '.$config['mensagens'].' não lid'.$config['genero_mensagem'].'s</b></a></td><td>&nbsp;</td></tr>';	
echo '<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>';	
if ($lista == 1 ){
	echo '<tr><td colspan=3 align="center"><h1>Total de '.ucfirst($config['mensagens']).' Não Lid'.$config['genero_mensagem'].'s</h1></td></tr>';
	echo '<tr><td colspan=3 align="center"><table align="center" border=0 cellpadding=0 cellspacing=0 class="tbl1">';
	echo "<tr><td><b>Grupos</b></td><td><b>Recebias</b></td><td><b>Enviadas</b></td></tr>";
	$sql->adTabela('grupo');
	$sql->adCampo('grupo_id, grupo_descricao');
	$sql->adOnde('grupo_usuario IS NULL');
	$sql->adOnde('grupo_cia='.$cia_id);
	$sql_resultados = $sql->Lista();
	$sql->Limpar();
	foreach($sql_resultados as $rs){	
		echo '<tr><td>'.$rs['grupo_descricao'].'</td>';
		$sql->adTabela('msg_usuario');
		$sql->adUnir('usuariogrupo', 'usuariogrupo', 'msg_usuario.para_id = usuariogrupo.usuario_id');
		$sql->adCampo('count(msg_usuario.msg_id) as soma');
		$sql->adOnde('usuariogrupo.grupo_id = '.$rs['grupo_id']);
		$sql->adOnde('msg_usuario.status = 0');
		$sql->adGrupo('grupo_id');
		$rs_soma = $sql->Resultado();
		$sql->Limpar();
		echo '<td>'.$rs_soma.'</td>';
		$sql->adTabela('msg_usuario');
		$sql->adUnir('usuariogrupo', 'usuariogrupo', 'msg_usuario.de_id = usuariogrupo.usuario_id');
		$sql->adCampo('count(msg_usuario.msg_id) as soma');
		$sql->adOnde('usuariogrupo.grupo_id = '.$rs['grupo_id']);
		$sql->adOnde('msg_usuario.status = 0');
		$sql->adGrupo('grupo_id');
		$rs_soma = $sql->Resultado();
		$sql->Limpar();
		echo '<td>'.$rs_soma.'</td></tr>';
		}
	echo '</table>';
	} 
else { 
	echo '<tr><td colspan=3 align="center"><table align="center" border=0 cellspacing="1" cellpadding="3" class= "tbl">';
	echo '<tr><td><b>Meio</b></td><td><b>Enviadas</b></td><td><b>Recebidas</b></td></tr>';
	
	$sql->adTabela('msg');
	$sql->esqUnir('usuarios','usuarios','msg.de_id=usuarios.usuario_id');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->adCampo('count(DISTINCT msg_id)');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$total_enviado = $sql->Resultado();
	$sql->Limpar();

	
	$sql->adTabela('msg');
	$sql->esqUnir('msg_usuario','msg_usuario','msg.msg_id=msg_usuario.msg_id');
	$sql->esqUnir('usuarios','usuarios','msg_usuario.para_id=usuarios.usuario_id');
	$sql->esqUnir('contatos','contatos','contatos.contato_id=usuarios.usuario_contato');
	$sql->adCampo('count(DISTINCT msg.msg_id)');
	$sql->adOnde('contato_cia='.(int)$cia_id);
	$total_recebido = $sql->Resultado();
	$sql->Limpar();
	
	echo '<tr><td>E-mail</td><td>'.$total_enviado.'</td><td>'.$total_recebido.'</td></tr>'; 

	
	echo '</table></td></tr>';
	} 
echo '<tr><td colspan=3>&nbsp;</td></tr></table>';


echo estiloFundoCaixa();
?>
<script LANGUAGE="javascript">
function mudar_om(){	
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class="texto" size=1 style="width:250px;" onchange="javascript:mudar_om();"'); 	
	}	
</script>
	
