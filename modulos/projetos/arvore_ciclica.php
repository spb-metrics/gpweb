<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

$sql = new BDConsulta;

$tipos=array(1=>'Nr de projetos ativos', 2=>'Custo estimado dos projetos ativos',3=>'Gastos nos projetos ativos'); 

if (isset($_REQUEST['cia_atual']) && $_REQUEST['cia_atual']) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_atual', 0));
elseif (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', 0));
$cia_id = ($Aplic->getEstado('cia_id') ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia);

if (isset($_REQUEST['tipo'])) $Aplic->setEstado('arvore_tipo', getParam($_REQUEST, 'tipo', 1));
$tipo = ($Aplic->getEstado('arvore_tipo') ? $Aplic->getEstado('arvore_tipo') : 1);

if ($tipo==1) $cores=array(0=>'ff0000', 1=>'ff9600',2=>'ffc600',3=>'fffc00',4=>'deff00',5=>'ccff00',6=>'a2ff00',7=>'48ff00',8=>'00ff9c',9=>'00ffd2',10=>'00fffc',11=>'00e4ff',12=>'00c6ff',13=>'00a8ff',14=>'0084ff',15=>'0048ff',16=>'3600ff',17=>'7800ff',18=>'ae00ff', 19=>'e400ff', 20=>'d063c2');
elseif ($tipo==2 || $tipo==3) $cores=array(0=>'ff0000', 100000=>'ff9600',200000=>'ffc600',300000=>'fffc00',400000=>'deff00',500000=>'ccff00',600000=>'a2ff00',700000=>'48ff00',800000=>'00ff9c',900000=>'00ffd2',1000000=>'00fffc',1100000=>'00e4ff',1200000=>'00c6ff',1300000=>'00a8ff',1400000=>'0084ff',1500000=>'0048ff',1600000=>'3600ff',1700000=>'7800ff',1800000=>'ae00ff', 1900000=>'e400ff', 2000000=>'d063c2');


echo '<form name="env" method="post">';
echo '<input type="hidden" name="m" value="projetos" />';
echo '<input type="hidden" name="a" value="arvore_ciclica" />';
echo '<input type="hidden" name="cia_atual" value="" />';


echo '<table width="100%" cellspacing=0 cellpadding=0>';


echo '<tr><td colspan=20><table><tr>';
echo '<td>&nbsp;&nbsp;'.dica('Tipo de Exibição', 'Selecione qual informação deseja exibir sobre '.$config['genero_projeto'].'s '.strtolower($config['projetos']).' d'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada.').'Tipo:'.dicaF().'</td><td>'.selecionaVetor($tipos, 'tipo','class="texto" size=1 onchange="document.env.submit();"', $tipo).'</td>';
echo '<td>&nbsp;&nbsp;'.dica(ucfirst($config['organizacao']), 'Exibir '.$config['genero_projeto'].'s '.strtolower($config['projetos']).' d'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada e demais '.$config['organizacoes'].' subordinadas.').ucfirst($config['organizacao']).':'.dicaF().'</td><td><div id="combo_cia">'.selecionar_om($cia_id, 'cia_id', 'class=texto size=1 style="width:250px;" onchange="javascript:mudar_om();"').'</div></td><td><a href="javascript:void(0);" onclick="document.env.submit();">'.imagem('icones/filtrar_p.png',$config['organizacao'].' Inicial','Clique neste ícone '.imagem('icones/filtrar_p.png').' para exibir o sumário d'.$config['genero_projeto'].'s '.$config['projetos'].' a partir d'.$config['genero_organizacao'].' '.$config['organizacao'].' selecionada à esquerda.').'</a>&nbsp;&nbsp;&nbsp;</td>';
echo '<td>'.dica(ucfirst($config['projeto']),'Clique neste botão para visualizar '.$config['genero_projeto'].'s '.$config['projeto'].' d'.$config['genero_organizacao'].' '.$config['organizacao'].' em destaque.').'<a id="projetos" href="javascript:void(0);" onclick="env.a.value=\'index\'; env.submit();" class="botao"><span>'.$config['projetos'].'</span></a>'.dicaF().'</td>';
echo '</tr></table></td></tr>';

$sql->adTabela('cias');
$sql->adCampo('cia_id, cia_nome_completo, cia_superior');
if ($tipo==1) $sql->adCampo('(select count(projeto_id) FROM projetos WHERE projeto_cia='.(int)$cia_id.' AND projeto_ativo=1) AS valor');
elseif ($tipo==2) $sql->adCampo('(select SUM(tarefa_custos_quantidade*tarefa_custos_custo) FROM tarefa_custos LEFT JOIN tarefas AS tarefas ON tarefa_custos_tarefa=tarefas.tarefa_id LEFT JOIN projetos ON tarefa_projeto=projeto_id WHERE projeto_cia='.(int)$cia_id.' AND projeto_ativo=1'.($Aplic->profissional && $config['aprova_custo'] ? ' AND tarefa_custos_aprovado = 1' : '').') AS valor');
elseif ($tipo==3) $sql->adCampo('(select SUM(tarefa_gastos_quantidade*tarefa_gastos_custo) FROM tarefa_gastos LEFT JOIN tarefas AS tarefas ON tarefa_gastos_tarefa=tarefas.tarefa_id LEFT JOIN projetos ON tarefa_projeto=projeto_id WHERE projeto_cia='.(int)$cia_id.' AND projeto_ativo=1'.($Aplic->profissional && $config['aprova_gasto'] ? ' AND tarefa_gastos_aprovado = 1' : '').') AS valor');
$sql->adOnde('cia_id='.(int)$cia_id);
$atual=$sql->Linha();
$sql->limpar();

$cor=retorna_cor($atual['valor']);
if ($tipo==1){
	$dentro=($atual['valor'] > 1 ? ' '.$config['projetos'].' ativ'.$config['genero_projeto'].'s': ' '.$config['projeto'].' ativ'.$config['genero_projeto']).': '.$atual['valor'];
	}
elseif($tipo==2 || $tipo==3){
	$dentro=$config['simbolo_moeda'].' '.number_format($atual['valor'], 2, ',', '.');
	}	



$sql->adTabela('cias');
$sql->adCampo('cia_id, cia_nome_completo, cia_superior');
if ($tipo==1) $sql->adCampo('(select count(projeto_id) FROM projetos WHERE projeto_cia=cia_id AND projeto_ativo=1) AS valor');
elseif ($tipo==2) $sql->adCampo('(select SUM(tarefa_custos_quantidade*tarefa_custos_custo) FROM tarefa_custos LEFT JOIN tarefas AS tarefas ON tarefa_custos_tarefa=tarefas.tarefa_id LEFT JOIN projetos ON tarefa_projeto=projeto_id WHERE projeto_cia=cia_id AND projeto_ativo=1'.($Aplic->profissional ? ' AND tarefa_custos_aprovado = 1' : '').') AS valor');
elseif ($tipo==3) $sql->adCampo('(select SUM(tarefa_gastos_quantidade*tarefa_gastos_custo) FROM tarefa_gastos LEFT JOIN tarefas AS tarefas ON tarefa_gastos_tarefa=tarefas.tarefa_id LEFT JOIN projetos ON tarefa_projeto=projeto_id WHERE projeto_cia=cia_id AND projeto_ativo=1'.($Aplic->profissional ? ' AND tarefa_gastos_aprovado = 1' : '').') AS valor');
$sql->adOnde('cia_superior='.(int)$cia_id);
$linhas=$sql->Lista();
$sql->limpar();


echo '<tr><td colspan=20><table width="100%" cellspacing=0 cellpadding=0 class="mocal">';
echo '<tr><td rowspan='.count($linhas).' align="center" style="'.(count($linhas)< 5 ? 'height:150px;' : '').'border-style:solid;border-width:1px; background: #'.$cor.';" '.(permite_superior($cia_id) ? 'onclick="env.cia_atual.value='.$atual['cia_superior'].'; env.submit();"' : 'onclick="alert(\'Não tem permissão para ver '.$config['organizacao'].' superior.\');"').'>';

echo dica($tipos[$tipo],$dentro).$atual['cia_nome_completo'].dicaF();
echo '</td>';
$qnt=0;

foreach($linhas as $atual){
	$cor=retorna_cor($atual['valor']);
	
	if ($tipo==1){
		$dentro=($atual['valor']>1 ? ' '.$config['projetos'].' ativ'.$config['genero_projeto'].'s': ' '.$config['projeto'].' ativ'.$config['genero_projeto']).': '.$atual['valor'];
		}
	elseif($tipo==2){
		$dentro=$config['simbolo_moeda'].' '.number_format($atual['valor'], 2, ',', '.');
		}	
	elseif($tipo==3){
		$dentro=$config['simbolo_moeda'].' '.number_format($atual['valor'], 2, ',', '.');
		}
	echo ($qnt++? '<tr>' : '').'<td align="center" style="border-style:solid;border-width:1px; background: #'.$cor.';" onclick="env.cia_atual.value='.$atual['cia_id'].'; env.submit();">';
	echo dica($tipos[$tipo],$dentro).$atual['cia_nome_completo'].dicaF();
	echo '</td></tr>';
	}


echo '</table></td></tr>';


echo '<tr><td colspan=20><table><tr>';
if ($tipo==1) foreach($cores as $chave => $valor) echo '<td style="border-style:solid;border-width:1px; background: #'.$valor.';">'.dica(ucfirst($config['projetos']).' Ativos','Esta cor representa '.($chave <20 ? $chave : '20 ou mais').' '.$config['projetos'].' ativos').'&nbsp; &nbsp;'.dicaF().'</td>';
if ($tipo==2) foreach($cores as $chave => $valor) echo '<td style="border-style:solid;border-width:1px; background: #'.$valor.';">'.dica('Custo Estimado dos '.ucfirst($config['projetos']).' Ativos','Esta cor representa valores '.($chave<2000000 ? 'até' : 'acima').' '.$config['simbolo_moeda'].' '.number_format(($chave), 2, ',', '.').' no somatório dos custos prováveis n'.$config['genero_projeto'].'s '.$config['projetos'].' ativos').'&nbsp; &nbsp;'.dicaF().'</td>';
if ($tipo==3) foreach($cores as $chave => $valor) echo '<td style="border-style:solid;border-width:1px; background: #'.$valor.';">'.dica('Gastos nos '.ucfirst($config['projetos']).' Ativos','Esta cor representa valores '.($chave<2000000 ? 'até' : 'acima').' '.$config['simbolo_moeda'].' '.number_format(($chave), 2, ',', '.').' no somatório dos gastos efetuados n'.$config['genero_projeto'].'s '.$config['projetos'].' ativos').'&nbsp; &nbsp;'.dicaF().'</td>';
echo '</tr></table></td></tr>';

echo '</table>';
echo '</form>';

function retorna_cor($valor){
	global $cores;
	
	foreach ($cores as $qnt => $cor){
		if ($valor <= $qnt) break;
		}
	return $cor;
	}


function permite_superior($cia_id){
	global $sql,$Aplic;
	$sql->adTabela('cias');
	$sql->adCampo('cia_superior');
	$sql->adOnde('cia_id='.(int)$cia_id);
	$superior=$sql->resultado();
	$sql->limpar();
	$retorno=false;
	if ($superior==$Aplic->usuario_cia) return true;
	elseif ($superior > 1) $retorno=permite_superior($superior);
	return $retorno;
	}



?>

<script>
function mudar_om(){
	xajax_selecionar_om_ajax(document.getElementById('cia_id').value,'cia_id','combo_cia', 'class=\"texto\" size=1 style=\"width:250px;\" onchange=\"javascript:mudar_om();\"');
	}
</script>
