<?php  
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (isset($_REQUEST['grupo_id'])) $grupo_id = getParam($_REQUEST, 'grupo_id', null); else $grupo_id=0;
if (isset($_REQUEST['desc_grupo'])) $desc_grupo = getParam($_REQUEST, 'desc_grupo', null); else $desc_grupo='';
if (isset($_REQUEST['totais'])) $totais = getParam($_REQUEST, 'totais', null); else $totais=0;
if (isset($_REQUEST['depara']) && $_REQUEST['depara'] == 1){
	$titulo = 'Sum�rio de '.$config['mensagens'].' enviad'.$config['genero_mensagem'].'s';
	$depara = "de_id";
	} 
else{
	$titulo = 'Sum�rio de '.$config['mensagens'].' recebid'.$config['genero_mensagem'].'s';
	$depara = "para_id";
	}

echo '<form method="POST" id="env" name="env">';
echo '<input type=hidden name="a" id="a" value="lista_msg">';  		
echo '<input type=hidden name="m" id="email" value="email">';
echo '<input type=hidden name="numero_status" id="numero_status" value="">';
echo '<input type=hidden name="usuario_id" id="usuario_id" value="">';
echo '</form>';

$botoesTitulo = new CBlocoTitulo('Sum�rio', 'estatistica.png', $m);
$botoesTitulo->adicionaBotao('m=sistema&u=email&a=estatisticas', 'estat�sticas','','Estat�sticas','Voltar � tela de Estat�sticas sobre '.$config['genero_mensagem'].'s '.$config['mensagens'].'.');
$botoesTitulo->mostrar();

echo estiloTopoCaixa();
echo '<table align="center" class="std" cellspacing=0 width="100%" cellpadding=0>';
echo "<tr><td colspan=4 align='center'><h1>$titulo pelo grupo $desc_grupo</h1></td><tr>";
echo '<tr><td><table align="center" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><td><b>'.ucfirst($config['usuario']).'</b></td><td><b>'.($totais == 1 ? "Totais" : "N�o Lidas" )."</b></td><td><b>�ltimo Acesso</b></td><td><b>IP</b></td></tr>"; 

$sql = new BDConsulta;

$sql->adTabela('usuarios');
$sql->esqUnir('usuariogrupo','usuariogrupo','usuariogrupo.usuario_id=usuarios.usuario_id');
$sql->adCampo('usuarios.usuario_id');
$sql->adOnde('usuario_ativo=1');	
$sql->adOnde('usuariogrupo.grupo_id='.$grupo_id);
$sql_resultados=$sql->Lista();
$sql->Limpar();


foreach($sql_resultados as $rs){
	$sql->adTabela('usuario_reg_acesso', 'ual');
	$sql->adCampo('usuario_reg_acesso_id, if(isnull(saiu),\'1\',\'0\') as online, saiu, entrou');
	$sql->adOnde('usuario_id = '.(int)$rs['usuario_id']);
	$sql->adOrdem('usuario_reg_acesso_id DESC');
	$sql->setLimite(1);
	$log = $sql->Lista();
	$sql->Limpar();
	
	$sql->adTabela('msg_usuario');
	$sql->adCampo('count(msg_id) AS soma');
	$sql->adOnde('msg_usuario.'.$depara.' = '.$rs['usuario_id']);
	if (!$totais) $sql->adOnde('msg_usuario.status = 0');
	$qnt = $sql->Resultado();
	$sql->Limpar();
	
	
	if (!isset($log['entrou'])) $situacao='nunca entrou';
	elseif ($log['online']) $situacao='online';
	else $situacao=retorna_data($log['saiu']);
	echo "<tr><td>";
	if ($depara == "de_id") echo '<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'numero_status\').value=5; document.getElementById(\'usuario_id\').value='.$rs['usuario_id'].'; document.getElementById(\'env\').submit();">';
	else echo '<a href="javascript:void(0);" onclick="javascript:document.getElementById(\'numero_status\').value=0;document.getElementById(\'usuario_id\').value='.$rs['usuario_id'].'; document.getElementById(\'env\').submit();">';
	echo ($Aplic->usuario_prefs['nomefuncao'] ? nome_usuario($rs['usuario_id']).( funcao_usuario($rs['usuario_id']) ? " (".funcao_usuario($rs['usuario_id']).") " : "") : funcao_usuario($rs['usuario_id']).( nome_usuario($rs['usuario_id']) ? " (".nome_usuario($rs['usuario_id']).") " : ""));
  echo "</a></td><td>".(int)$qnt.'</td><td>'. $situacao.'</td><td>'.(isset($log['usuario_reg_acesso_id']) ? $log['usuario_reg_acesso_id'] : '&nbsp;').'</td></tr>';
	}
	
echo '</table><td></tr><tr><td>&nbsp;</td></tr></table>';
echo estiloFundoCaixa();
?>
