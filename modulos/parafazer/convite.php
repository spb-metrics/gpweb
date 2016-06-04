<?php
/* Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

require_once('init.php');
require_once('lang/class.default.php');
require_once('lang/'.$config['lang'].'.php');


session_start();
$Aplic->carregar_usuario(getParam($_SESSION, 'usuario', null));

include BASE_DIR.'/modulos/parafazer/index_ajax.php';
require BASE_DIR.'/estilo/rondon/sobrecarga.php';

echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">';
echo '<html><HEAD>';
echo '<meta http-equiv="content-type" content="text/html; charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'">';
echo '<title>Lista de lembretes</title>';
echo '<link rel="stylesheet" type="text/css" href="../../estilo/rondon/estilo_'.$config['estilo_css'].'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../../estilo/rondon/estilo_'.$config['estilo_css'].'.css";</style>';
echo '<link rel="shortcut icon" href="../../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="../../lib/mootools/mootools.js"></script>';

$enderecoURI=BASE_URL.'/index.php';
$xajax->printJavascript(BASE_URL.'/../../lib/xajax');

echo '</HEAD><body>';

$sql = new BDConsulta;
$data = getParam($_REQUEST, 'data', 0);
if (getParam($_REQUEST, 'processar_convites', 0)){
	
	$convite = getParam($_REQUEST, 'convite', array());
	$aceitar = getParam($_REQUEST, 'aceitar', 0);
	$recusar = getParam($_REQUEST, 'recusar', 0);
	$nome_lista= getParam($_REQUEST, 'nome_lista', '');
	$lista_selecionada=getParam($_REQUEST, 'nome_lista', '');
	
	$atualizar=0;
	if (count($convite) && $aceitar){
		$sql->adTabela('parafazer_usuarios');
		$sql->adAtualizar('aceito', 1);
		$sql->adAtualizar('data', date('Y-m-d g:i'));
		$sql->adOnde('id IN ('.implode(',',(array)$convite).')');
		$sql->adOnde('usuario_id='.$Aplic->usuario_id);
		if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela parafazer_usuarios!'.$bd->stderr(true));
		$sql->limpar();	
		
		
		if ($nome_lista){
			$sql->adTabela('parafazer_listas');
			$sql->adInserir('nome', $nome_lista);
			$sql->adInserir('usuario_id', $Aplic->usuario_id);
			if (!$sql->exec()) die('Não foi possivel inserir na tabela parafazer_listas!'.$bd->stderr(true));
			$lista_selecionada=$bd->Insert_ID('parafazer_listas','id');
			$sql->limpar();
			}
		
		//criar uma cópia para o usuário
		
		foreach($convite as $individual){
			$sql->adTabela('parafazer_tarefa');
			$sql->adCampo('d, titulo, nota, prio, ow, parafazer_chave, datafinal');
			$sql->adOnde('id='.$individual);
			$linha=$sql->Linha();
			$sql->limpar();

			$sql->adTabela('parafazer_tarefa');
			$sql->adInserir('lista_id', $lista_selecionada);
			$sql->adInserir('d', $linha['d']);
			$sql->adInserir('titulo', $linha['titulo']);
			$sql->adInserir('nota', $linha['nota']);
			$sql->adInserir('prio', $linha['prio']);
			$sql->adInserir('ow', $linha['ow']);
			$sql->adInserir('parafazer_chave', $linha['parafazer_chave']);
			$sql->adInserir('datafinal', $linha['datafinal']);
			if (!$sql->exec()) die('Não foi possivel inserir na tabela parafazer_tarefa!'.$bd->stderr(true));
			$sql->limpar();
			}
		
		}
	if (count($convite) && $recusar){
		$sql->adTabela('parafazer_usuarios');
		$sql->adAtualizar('aceito', -1);
		$sql->adAtualizar('data', date('Y-m-d g:i'));
		$sql->adOnde('id IN ('.implode(',',(array)$convite).')');
		$sql->adOnde('usuario_id='.$Aplic->usuario_id);
		if (!$sql->exec()) die('Não foi possivel alterar os valores da tabela parafazer_usuarios!'.$bd->stderr(true));
		$sql->limpar();	
		}
	echo '<script>window.location = "./index.php"</script>';
	}




$sql->adTabela('parafazer_tarefa');
$sql->esqUnir('parafazer_usuarios', 'parafazer_usuarios', 'parafazer_usuarios.id = parafazer_tarefa.id');
$sql->esqUnir('parafazer_listas', 'parafazer_listas', 'parafazer_listas.id = parafazer_tarefa.lista_id');
$sql->adCampo('aceito, parafazer_tarefa.id, titulo, nota, parafazer_listas.usuario_id as dono');
$sql->adOrdem('parafazer_tarefa.id DESC');
$sql->adOnde('parafazer_listas.usuario_id != '.$Aplic->usuario_id);
$sql->adOnde('parafazer_usuarios.usuario_id='.$Aplic->usuario_id);
$sql->adOnde('parafazer_usuarios.aceito=0');
$convites=$sql->Lista();
$sql->Limpar();

if (!count($convites)) echo '<script>window.location = "./index.php"</script>';

echo '<form action="./convite.php" method="post" action="convite" id="frm" name="frm">'; 

echo '<input type="hidden" name="processar_convites" value="1" />';
echo '<input type="hidden" name="aceitar" value="" />';
echo '<input type="hidden" name="recusar" value="" />';


echo estiloTopoCaixa('','../../');	
echo '<table class="std" cellspacing=0 cellpadding="0" width="100%">';
echo '<tr style="background-color:#a6a6a6; font-weight:bold; text-align:center; font-size:20pt;"><td colspan=20 style="font-size:12pt;">Convite para um lembrete</td></tr>';
echo '<tr style="background-color:#a6a6a6; font-weight:bold; text-align:center;"><td>Marcar</td><td>Criador</td><td align="left">Título</td><td align="left">Descrição</td><td align="left">Quem</td></tr>';
foreach ($convites as $convite) {
	echo '<tr style="background-color:#ffffff;" align="center"><td><input type="checkbox" name="convite[]" value="'.$convite['id'].'"></td>';
	echo '<td nowrap="nowrap">'.link_usuario($convite['dono']).'</td>';
	echo '<td align="left">'.$convite['titulo'].'</td>';
	echo '<td align="left">'.$convite['nota'].'</td>';
	
	$sql->adTabela('parafazer_usuarios', 'e');
	$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=e.usuario_id');
	$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
	$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
	$sql->adOnde('id='.$convite['id']);
	$sql->adOnde('aceito!= -1');
	$participantes=$sql->Lista();
	$sql->Limpar();

	
	$saida_quem='';
	if ($participantes && count($participantes)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td nowrap="nowrap">'.link_usuario($participantes[0]['usuario_id'], '','','esquerda').($participantes[0]['contato_dept']? ' - '.link_secao($participantes[0]['contato_dept']) : '');
		$qnt_participantes=count($participantes);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i]['usuario_id'], '','','esquerda').($participantes[$i]['contato_dept']? ' - '.link_secao($participantes[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$convite['id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$convite['id'].'"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
	echo '<td nowrap="nowrap">'.$saida_quem.'</td>';
	echo '</tr>';
	}


$sql->adTabela('parafazer_listas');
$sql->adCampo('id, nome');
$sql->adOnde('usuario_id='.$Aplic->usuario_id);
$sql->adOrdem('nome');
$combo=$sql->listaVetorChave('id','nome');
$sql->Limpar();
$combo[0]='&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
echo '<tr><td colspan=20>'.dica('Lista', 'Selecione a lista em que deseje incluir.').'&nbsp;Lista:'.dicaF().selecionaVetor($combo, 'lista_selecionada', 'size="1" class="texto"',0).'&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Nova Lista', 'Caso queira inserir as tarefas a fazer selecionadas em uma nova lista insira o nome da lista a ser criada.').'Nova lista:'.dicaF().'<input type="text" name="nome_lista" id="nome_lista" value="" style="width:200px;" class="texto" /></td></tr>';



echo '<tr><td colspan=20><table width="100%"><tr><td align="left">'.botao('aceitar os marcados', 'Aceitar os Marcados', 'Aceitar os compromissos marcados.','','aceitacao();').'</td><td width="100%">&nbsp;</td><td align="right">'.botao('recusar os marcados', 'Recusar os Marcados', 'Recusar os compromissos marcados.','','frm.recusar.value=1; frm.submit();').'</td></tr></table></td></tr>';	

echo '<tr><td colspan=20><table width="100%"><tr><td align="left">'.botao('sair', 'Sair', 'Sair da lista de convites de tarefas a fazer.','','frm.submit();').'</td></tr></table></td></tr>';	



echo '</table>';	
echo estiloFundoCaixa('','../../');	
echo '</form>';	
?>
	
<script language="javascript">

function aceitacao(){
	if (document.getElementById('lista_selecionada').value<1 && document.getElementById('nome_lista').value.length <1) alert('Precisa escolher ou criar uma pasta para colocar este lembrete!');
	else {
		document.frm.aceitar.value=1; 
		document.frm.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>


















<?php 
echo '</body>';


$Aplic->carregarRodapeJS();
echo $Aplic->getMsg();
?>
