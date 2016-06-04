<?php 
/* 
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');


$ponto_relatorio_tipo='data_custo';

$projeto_id=getParam($_REQUEST, 'projeto_id', 0);
$financeiro=getParam($_REQUEST, 'financeiro', '');
if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/funcoes_pro.php';
	$portfolio=ser_portfolio($projeto_id);
	if (!$portfolio) $portfolio=$projeto_id;
	}
else $portfolio=$projeto_id;

$unidade=getSisValor('TipoUnidade');
$unidade= getSisValor('TipoUnidade');

echo '<table cellpadding=0 cellspacing=1 width="100%">';


echo '<tr><td><h2>Períodos Trabalhados'.($financeiro ? ' ('.ucfirst($financeiro).')' : '').'<br></h2></td></tr>';

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


if ($financeiro){
	$sql->adTabela('folha_ponto_gasto');
	$sql->esqUnir('folha_ponto', 'folha_ponto', 'folha_ponto_gasto_folha=folha_ponto_id');
	$sql->esqUnir('eventos', 'eventos', 'eventos.evento_id = folha_ponto.folha_ponto_evento');
	$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = folha_ponto.folha_ponto_tarefa');
	$sql->adCampo('folha_ponto_id');
	$sql->adOnde('evento_projeto IN ('.$portfolio.') OR tarefa_projeto IN ('.$portfolio.')');
	if ($financeiro=='empenhado') $sql->adOnde('folha_ponto_gasto_empenhado > 0');
	elseif ($financeiro=='liquidado') $sql->adOnde('folha_ponto_gasto_liquidado > 0');
	elseif ($financeiro=='pago') $sql->adOnde('folha_ponto_gasto_pago > 0');
	$tem_gasto=$sql->carregarColuna();
	$sql->limpar();
	$tem_gasto=implode(',',$tem_gasto); 
	}


$sql->adTabela('folha_ponto');
$sql->esqUnir('eventos', 'eventos', 'eventos.evento_id = folha_ponto.folha_ponto_evento');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_id = folha_ponto.folha_ponto_tarefa');
$sql->esqUnir('usuarios', 'usuarios', 'usuarios.usuario_id = folha_ponto_usuario');
$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = usuario_contato');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, contato_funcao, folha_ponto_usuario');
$sql->adCampo('folha_ponto_id, folha_ponto_obs,  tarefa_nome, folha_ponto_evento, folha_ponto_tarefa, evento_titulo, formatar_data(folha_ponto_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(folha_ponto_fim, \'%d/%m/%Y  %H:%i\') AS fim, folha_ponto_duracao, folha_ponto_valor_hora, folha_ponto_empenhado, folha_ponto_liquidado, folha_ponto_pago');
if ($financeiro=='empenhado') $sql->adCampo('(folha_ponto_empenhado*folha_ponto_valor_hora) AS total, folha_ponto_empenhado AS duracao');
elseif ($financeiro=='liquidado') $sql->adCampo('(folha_ponto_liquidado*folha_ponto_valor_hora) AS total, folha_ponto_liquidado AS duracao');
elseif ($financeiro=='pago') $sql->adCampo('(folha_ponto_pago*folha_ponto_valor_hora) AS total, folha_ponto_pago AS duracao');
else $sql->adCampo('(folha_ponto_duracao*folha_ponto_valor_hora) AS total, folha_ponto_duracao AS duracao');
$sql->adOnde('evento_projeto IN ('.$portfolio.') OR tarefa_projeto IN ('.$portfolio.')');
$sql->adOnde('folha_ponto_fim IS NOT NULL');
if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');


if ($financeiro=='empenhado') $sql->adOnde('folha_ponto_empenhado > 0'.($tem_gasto ? ' OR folha_ponto_id IN ('.$tem_gasto.')' : ''));
elseif ($financeiro=='liquidado') $sql->adOnde('folha_ponto_liquidado > 0'.($tem_gasto ? ' OR folha_ponto_id IN ('.$tem_gasto.')' : ''));
elseif ($financeiro=='pago') $sql->adOnde('folha_ponto_pago > 0'.($tem_gasto ? ' OR folha_ponto_id IN ('.$tem_gasto.')' : ''));

$sql->adOrdem('folha_ponto_usuario, folha_ponto_inicio');
$existe=$sql->lista();
$sql->limpar();


echo '<tr><td align=center><table cellspacing=0 cellpadding=0 class="tbl1" width="100%">';
echo '<tr><th>Nome</th><th>Obs</th><th width="110">Início</th><th width="110">Fim</th><th width="50">Duração</th><th width="80">Valor '.$config['simbolo_moeda'].'</th></tr>';
$soma=0;
$soma2=0;
$soma3=0;
$gasto2=array();
$total2=0;

$hora_geral=0;
$valor_hora_geral=0;


$nd=getSisValorND();
$usuarioatual='';
foreach($existe as $linha) {
	if ($usuarioatual!=$linha['folha_ponto_usuario']) {
		if ($usuarioatual) echo '<tr><td colspan=4 align=right><b>Total</b></td><td align=right>'.number_format($soma, 1, ',', '.').'</td><td align=right>'.number_format($soma2, 2, ',', '.').'</td></tr>';	
		$hora_geral+=$soma;
		$valor_hora_geral+=$soma2;
		$soma=0;
		$soma2=0;
		$usuarioatual=$linha['folha_ponto_usuario'];
		echo '<tr><td colspan=20 height=30 valign=bottom><b>'.($dialogo ? $linha['nome'] : link_usuario($linha['folha_ponto_usuario'],'','','esquerda')).'</b></td></tr>';
		}
	echo '<tr><td>'.($linha['folha_ponto_tarefa'] ? ($dialogo ? $linha['tarefa_nome'] : link_tarefa($linha['folha_ponto_tarefa'])) : ($dialogo ? $linha['evento_titulo'] : link_evento($linha['folha_ponto_evento']))).'</td><td>'.($linha['folha_ponto_obs'] ? $linha['folha_ponto_obs'] : '&nbsp;').'</td><td>'.$linha['inicio'].'</td><td>'.$linha['fim'].'</td><td align=right>'.number_format($linha['duracao'], 1, ',', '.').'</td><td align=right>'.number_format($linha['total'], 2, ',', '.').'</td><tr>';
	$soma+=$linha['folha_ponto_duracao'];
	$soma2+=$linha['folha_ponto_duracao']*$linha['folha_ponto_valor_hora'];
	if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_resumido' || $ponto_relatorio_tipo=='data_custo_arquivo' || $ponto_relatorio_tipo=='data_custo_resumido_arquivo'){

		$sql->adTabela('folha_ponto_gasto');
		$sql->esqunir('usuarios','usuarios','usuario_id=folha_ponto_gasto_usuario');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adOnde('folha_ponto_gasto_folha = '.(int)$linha['folha_ponto_id']);
		if ($financeiro=='empenhado') $sql->adCampo('((folha_ponto_gasto_empenhado*folha_ponto_gasto_gasto)*((100+folha_ponto_gasto_bdi)/100)) AS total, folha_ponto_gasto_empenhado AS quantidade');
		elseif ($financeiro=='liquidado') $sql->adCampo('((folha_ponto_gasto_liquidado*folha_ponto_gasto_gasto)*((100+folha_ponto_gasto_bdi)/100)) AS total, folha_ponto_gasto_liquidado AS quantidade');
		elseif ($financeiro=='pago') $sql->adCampo('((folha_ponto_gasto_pago*folha_ponto_gasto_gasto)*((100+folha_ponto_gasto_bdi)/100)) AS total, folha_ponto_gasto_pago AS quantidade');
		else $sql->adCampo('((folha_ponto_gasto_quantidade*folha_ponto_gasto_gasto)*((100+folha_ponto_gasto_bdi)/100)) AS total, folha_ponto_gasto_quantidade AS quantidade');
		$sql->adCampo('folha_ponto_gasto.*');
		$sql->adOrdem('folha_ponto_gasto_ordem');
		if ($financeiro=='empenhado') $sql->adOnde('folha_ponto_gasto_empenhado > 0');
		elseif ($financeiro=='liquidado') $sql->adOnde('folha_ponto_gasto_liquidado > 0');
		elseif ($financeiro=='pago') $sql->adOnde('folha_ponto_gasto_pago > 0');
		$gastos=$sql->Lista();
		$sql->limpar();
		
		if ($financeiro){
			$sql->adTabela('folha_ponto_gasto');
			$sql->adCampo('folha_ponto_gasto_id');
			$sql->adOnde('folha_ponto_gasto_folha = '.(int)$linha['folha_ponto_id']);
			if ($financeiro=='empenhado') $sql->adOnde('folha_ponto_gasto_empenhado > 0');
			elseif ($financeiro=='liquidado') $sql->adOnde('folha_ponto_gasto_liquidado > 0');
			elseif ($financeiro=='pago') $sql->adOnde('folha_ponto_gasto_pago > 0');
			$itens=$sql->carregarColuna();
			$sql->limpar();
			if (!count($itens)) $gastos=array();
			}
			
		
		if (count($gastos)) {
			if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_arquivo') echo '<tr><td colspan=20><table cellspacing="1" cellpadding="2" border=0  align=left width="100%"><tr>
			<th>Nome</th>
			<th>Descrição</th>
			<th>Unidade</th>
			<th>Qnt</th>
			<th>Valor ('.$config['simbolo_moeda'].')</th>'.
			($config['bdi'] ? '<th>'.dica('BDI', 'Benefícios e Despesas Indiretas, é o elemento orçamentário destinado a cobrir todas as despesas que, num empreendimento, segundo critérios claramente definidos, classificam-se como indiretas (por simplicidade, as que não expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material — mão-de-obra, equipamento-obra, instrumento-obra etc.), e, também, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
			'<th>ND</th>
			<th>Total ('.$config['simbolo_moeda'].')</th>'.
			(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
			(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
			(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
			'<th>Data</th>
			<th>Responsável</th></tr>';
			else echo '<tr><td colspan=20><table cellspacing="1" cellpadding="2" border=0  align=left width="100%"><tr><th colspan=6>ND</th><th colspan=5>Valor ('.$config['simbolo_moeda'].')</th></tr>';
			$qnt=0;
			$total=0;
			$gasto=array();
			foreach ($gastos as $item) {
				
				if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_arquivo'){
					echo '<tr align="center">';
					echo '<td align="left" width="190">'.++$qnt.' - '.$item['folha_ponto_gasto_nome'].'</td>';
					echo '<td align="left">'.($item['folha_ponto_gasto_descricao'] ? $item['folha_ponto_gasto_descricao'] : '&nbsp;').'</td>';
					echo '<td width="30">'.(isset($unidade[$item['folha_ponto_gasto_tipo']]) ? $unidade[$item['folha_ponto_gasto_tipo']] : '&nbsp;').
					'</td><td width="50" align="right">'.number_format($item['quantidade'], 2, ',', '.').'</td>';
					echo '<td align="right" width="70" align="right">'.number_format($item['folha_ponto_gasto_gasto'], 2, ',', '.').'</td>';
					
					if ($config['bdi']) echo '<td align="right">'.number_format($item['folha_ponto_gasto_bdi'], 2, ',', '.').'</td>';
					
					echo '<td width="10" nowrap="nowrap">'.($item['folha_ponto_gasto_categoria_economica'] && $item['folha_ponto_gasto_grupo_despesa'] && $item['folha_ponto_gasto_modalidade_aplicacao'] ? $item['folha_ponto_gasto_categoria_economica'].'.'.$item['folha_ponto_gasto_grupo_despesa'].'.'.$item['folha_ponto_gasto_modalidade_aplicacao'].'.' : '&nbsp;').$item['folha_ponto_gasto_nd'].'</td>';
					echo '<td align="right" width="70">'.number_format($item['total'], 2, ',', '.').'</td>';
					
					if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($item['folha_ponto_gasto_codigo'] ? $item['folha_ponto_gasto_codigo'] : '&nbsp;').'</td>';
					if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($item['folha_ponto_gasto_fonte'] ? $item['folha_ponto_gasto_fonte'] : '&nbsp;').'</td>';
					if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($item['folha_ponto_gasto_regiao'] ? $item['folha_ponto_gasto_regiao'] : '&nbsp;').'</td>'; 
					
					echo '<td width="10" nowrap="nowrap">'.($item['folha_ponto_gasto_data_limite']? retorna_data($item['folha_ponto_gasto_data_limite'],false) : '&nbsp;').'</td>';
					echo '<td align="left" nowrap="nowrap" width="150" >'.$item['nome_usuario'].'</td>';
					echo '</tr>';
					}
					
				if(isset($gasto[$item['folha_ponto_gasto_nd']])) $gasto[$item['folha_ponto_gasto_nd']] += (float)($item['total']);
				else $gasto[$item['folha_ponto_gasto_nd']] = (float)($item['total']);
				
				if(isset($gasto2[$item['folha_ponto_gasto_nd']])) $gasto2[$item['folha_ponto_gasto_nd']] += (float)($item['total']);
				else $gasto2[$item['folha_ponto_gasto_nd']] = (float)($item['total']);
				
				$total+=(float)($item['total']);
				$total2+=(float)($item['total']);
				}
			if ($total) {
				echo '<tr><td colspan='.($config['bdi'] ? 7 : 6).' class="std" align="right">';
				foreach ($gasto as $indice_nd => $somatorio) if ($somatorio > 0) echo (isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND').'<br>';
				echo '<b>Total</td><td align="right" width="90">';	
				foreach ($gasto as $indice_nd => $somatorio) if ($somatorio > 0) echo number_format($somatorio, 2, ',', '.').'<br>';
				echo '<b>'.number_format($total, 2, ',', '.').'</b></td>'.($ponto_relatorio_tipo=='data_custo' ? '<td colspan=20>&nbsp;</td>' : '').'</tr>';	
				}	
			echo '</table></td></tr>';
			}
		}
	
	if ($ponto_relatorio_tipo=='data_custo_arquivo' || $ponto_relatorio_tipo=='data_custo_resumido_arquivo'){
		
		$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
		$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL);
		
		//arquivo anexo
		$sql->adTabela('folha_ponto_arquivo');
		$sql->adCampo('folha_ponto_arquivo_id, folha_ponto_arquivo_data, folha_ponto_arquivo_ordem, folha_ponto_arquivo_nome, folha_ponto_arquivo_endereco');
		$sql->adOnde('folha_ponto_arquivo_ponto='.(int)$linha['folha_ponto_id']);
		$sql->adOrdem('folha_ponto_arquivo_ordem ASC');
		$arquivos=$sql->Lista();
		$sql->limpar();
		$saida='';
		if (count($arquivos)) echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><th>'.(count($arquivos)>1 ? 'Arquivos anexados':'Arquivo anexado').'</th></tr><tr><td>';
		foreach ($arquivos as $linha) {
			echo '<a href="javascript:void(0);" onclick="javascript:url_passar(0, \'m=calendario&a=folha_ponto_download&sem_cabecalho=1&folha_ponto_arquivo_id='.$linha['folha_ponto_arquivo_id'].'\');">'.$linha['folha_ponto_arquivo_nome'].'</a><br>';
			}
		if (count($arquivos)) echo '</td></tr></table></td></tr>';
		}
	
	}
	
if ($usuarioatual) echo '<tr><td colspan=4 align=right><b>Total</b></td><td align=right>'.number_format($soma, 1, ',', '.').'</td><td align=right>'.number_format($soma2, 2, ',', '.').'</td></tr>';		
if (!count($existe)) '<tr><td colspan=4 align=right>Não foi encontrado nenhum período marcado como trabalhado</td></tr>';
echo '</table></td></tr>';
echo '</table>';

$hora_geral+=$soma;
$valor_hora_geral+=$soma2;	

if ($total2 || count($existe)) {
		echo '<table cellspacing=4 cellpadding=0 border=0>';
		echo '<tr><td align=right><b>Sumário</b></td>&nbsp;<td></tt><tr>';
		if ($total2) {
			echo '<tr><td align=right  nowrap="nowrap">';
			foreach ($gasto2 as $indice_nd => $somatorio) if ($somatorio > 0) echo (isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND').'<br>';
			echo 'Soma parcial</td><td align="right" nowrap="nowrap">';	
			foreach ($gasto2 as $indice_nd => $somatorio) if ($somatorio > 0) echo $config['simbolo_moeda'].' '.number_format($somatorio, 2, ',', '.').'<br>';
			echo $config['simbolo_moeda'].' '.number_format($total2, 2, ',', '.').'</td></tr>';	
			}
		if (count($existe))	{
			echo '<tr><td align=right>Horas</td><td align=right>'.number_format($hora_geral, 1, ',', '.').'</td></tr>';
			echo '<tr><td align=right>Custo das horas</td><td align=right>'.$config['simbolo_moeda'].' '.number_format($valor_hora_geral, 2, ',', '.').'</td></tr>';	
			}
		echo '<tr><td align=right><b>Soma Final</b></td><td align=right><b>'.$config['simbolo_moeda'].' '.number_format($valor_hora_geral+$total2, 2, ',', '.').'</b></td></tr>';
		echo '</table>';
		}		
echo '</form>';

?>
