<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $pratica_indicador_id, $podeEditar;

$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');
if ($ordem) $ordenar .= ' DESC'; else $ordenar .= ' ASC';

$sql = new BDConsulta;

$sql->adTabela('pratica_indicador');
$sql->adCampo('pratica_indicador_pratica');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$praticas = $sql->carregarColuna();
$sql->limpar();

echo '<table border=0 cellpadding="2" cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
if ($podeEditar) echo '<th width="16"></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver_praticas&tab=3&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_nome&ordem='.($ordem ? '0' : '1').'\');">'.dica(ucfirst($config['pratica']), 'Nome d'.$config['genero_pratica'].' '.$config['pratica'].'.').ucfirst($config['pratica']).dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver_praticas&tab=3&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_oque&ordem='.($ordem ? '0' : '1').'\');">'.dica('O Que', 'O que é para ser feito.').'O Que'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver_praticas&tab=3&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pel'.$config['genero_pratica'].' '.$config['pratica'].'.').'Responsável'.dicaF().'</a></th>';
echo '<th>'.dica('Designados', 'Designados para '.($config['genero_pratica']=='a' ? 'a ': 'o ').$config['pratica'].'.').'<b>Designados</b>'.dicaF().'</th>';
echo '</tr>';
$qnt=0;
foreach($praticas as $chave => $pratica_id){
	if ($pratica_id){
		$qnt++;
		$sql->adTabela('praticas');
		$sql->adCampo('pratica_cia, pratica_nome, pratica_responsavel, pratica_oque, pratica_cor');
		$sql->adOnde('pratica_id = '.(int)$pratica_id);
		$linha = $sql->Linha();
		$sql->limpar();
		$sql->adTabela('pratica_usuarios');
		$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
		$sql->esqUnir('contatos', 'con', 'usuario_contato = contato_id');
		$sql->adCampo('usuarios.usuario_id, contato_dept');
		$sql->adOnde('pratica_id = '.(int)$pratica_id);
		$sql->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
		$designados = $sql->Lista();
		$sql->limpar();
		echo '<tr>';
		if ($podeEditar)echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=pratica_editar&pratica_id='.$pratica_id.'\');">'.imagem('icones/editar.gif').'</a></td>';
		echo '<td>'.link_pratica($pratica_id).'</td>';
		echo '<td>'.$linha['pratica_oque'].'</td>';
		echo '<td>'.link_usuario($linha['pratica_responsavel'], '','','esquerda').'</td>';
		echo '<td>';
		if (count($designados)) {
		echo link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_secao($designados[0]['contato_dept']) : '');
		$qnt_participantes=count($designados);
		if ($qnt_participantes > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_secao($designados[$i]['contato_dept']) : '').'<br>';		
				echo dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'indicador_designdo_'.$pratica_indicador_id.'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="indicador_designdo_'.$pratica_indicador_id.'"><br>'.$lista.'</span>';
				}
			} else echo '&nbsp;';
		echo '</td>';
		echo '</tr>';
		}
	}
if (!$qnt) echo '<tr><td colspan=20>Não há '.$config['praticas'].' relacionad'.($config['genero_pratica']=='a' ? 'as ': 'os ').' a este indicador</td></tr>';

echo '</table>';

?>
<script language="javascript">
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>