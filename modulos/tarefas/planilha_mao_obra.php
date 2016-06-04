<?php 
/* 
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

$Aplic->carregarCalendarioJS();

$ponto_relatorio_tipo='data_custo';

$baseline_id = getParam($_REQUEST, 'baseline_id', 0);
$tarefa_id=getParam($_REQUEST, 'tarefa_id', 0);
$tarefas_subordinadas=getParam($_REQUEST, 'tarefas_subordinadas', 0);
$impressao=getParam($_REQUEST, 'impressao', 0);

$unidade= getSisValor('TipoUnidade');

if (!$tarefas_subordinadas){
	$vetor=array();
	tarefas_subordinadas($tarefa_id, $vetor);
	$tarefas_subordinadas=implode(',',$vetor);
	}




echo '<table cellpadding=0 cellspacing=1 width="750">';


echo '<tr><td><h2>Per�odos Trabalhados<br></h2></td></tr>';

$sql = new BDConsulta;

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'valor\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();

$sql->adTabela(($baseline_id ? 'baseline_' : '').'folha_ponto', 'folha_ponto');
$sql->esqUnir('contatos', 'contatos', 'contatos.contato_id = folha_ponto_usuario');
$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome, contato_funcao, folha_ponto_usuario');
$sql->esqUnir('eventos', 'eventos', 'eventos.evento_id = folha_ponto.folha_ponto_evento');
$sql->esqUnir(($baseline_id ? 'baseline_' : '').'tarefas', 'tarefas', 'tarefas.tarefa_id = folha_ponto.folha_ponto_tarefa');
$sql->adCampo('folha_ponto_id, folha_ponto_obs, tarefa_nome, folha_ponto_evento, folha_ponto_tarefa, evento_titulo, formatar_data(folha_ponto_inicio, \'%d/%m/%Y %H:%i\') AS inicio, formatar_data(folha_ponto_fim, \'%d/%m/%Y  %H:%i\') AS fim, folha_ponto_duracao, folha_ponto_valor_hora');
$sql->adOnde('folha_ponto_tarefa IN ('.$tarefas_subordinadas.') OR evento_tarefa IN ('.$tarefas_subordinadas.')');
$sql->adOnde('folha_ponto_fim IS NOT NULL');
if ($config['aprova_mo']) $sql->adOnde('folha_ponto_aprovado = 1');
$sql->adOrdem('folha_ponto_usuario, folha_ponto_inicio');
$existe=$sql->lista();



$sql->limpar();
echo '<tr><td align=center><table cellspacing=0 cellpadding=0 class="tbl1" width="100%">';
echo '<tr><th>Nome</th><th>Obs</th><th width="110">In�cio</th><th width="110">Fim</th><th width="50">Dura��o</th><th width="80">Valor '.$config['simbolo_moeda'].'</th></tr>';
$horas=0;
$horas_geral=0;
$valor_horas_geral=0;

$valor_horas=0;
$gasto_nd_geral=array();
$gasto_itens_geral=0;

$nd=getSisValorND();
$usuarioatual='';
foreach($existe as $linha) {
	if ($usuarioatual!=$linha['folha_ponto_usuario']) {
		if ($usuarioatual) echo '<tr><td colspan=4 align=right><b>Total Parcial</b></td><td align=right>'.number_format($horas, 1, ',', '.').'</td><td align=right>'.number_format($valor_horas, 2, ',', '.').'</td></tr>';	
		$horas=0;
		$valor_horas=0;

		$usuarioatual=$linha['folha_ponto_usuario'];
		echo '<tr><td colspan=20 height=30 valign=bottom><b>'.($dialogo ? $linha['nome'] : link_usuario($linha['folha_ponto_usuario'],'','','esquerda')).'</td></tr>';
		}
	echo '<tr><td>'.($linha['folha_ponto_tarefa'] ? ($dialogo ? $linha['tarefa_nome'] : link_tarefa($linha['folha_ponto_tarefa'])) : ($dialogo ? $linha['evento_titulo'] : link_evento($linha['folha_ponto_evento']))).'</td><td>'.($linha['folha_ponto_obs'] ? $linha['folha_ponto_obs'] : '&nbsp;').'</td><td>'.$linha['inicio'].'</td><td>'.$linha['fim'].'</td><td align=right>'.number_format($linha['folha_ponto_duracao'], 1, ',', '.').'</td><td align=right>'.number_format($linha['folha_ponto_duracao']*$linha['folha_ponto_valor_hora'], 2, ',', '.').'</td><tr>';
	$horas+=$linha['folha_ponto_duracao'];
	$horas_geral+=$linha['folha_ponto_duracao'];
	$valor_horas+=$linha['folha_ponto_duracao']*$linha['folha_ponto_valor_hora'];
	$valor_horas_geral+=$linha['folha_ponto_duracao']*$linha['folha_ponto_valor_hora'];
	if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_resumido' || $ponto_relatorio_tipo=='data_custo_arquivo' || $ponto_relatorio_tipo=='data_custo_resumido_arquivo'){
		$sql->adTabela('folha_ponto_gasto');
		$sql->adUnir('usuarios','usuarios','usuario_id=folha_ponto_gasto_usuario');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->adCampo(($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario');
		$sql->adOnde('folha_ponto_gasto_folha = '.(int)$linha['folha_ponto_id']);
		$sql->adCampo('folha_ponto_gasto.*, ((folha_ponto_gasto_quantidade*folha_ponto_gasto_gasto)*((100+folha_ponto_gasto_bdi)/100)) AS valor');
		$sql->adOrdem('folha_ponto_gasto_ordem');
		$gastos=$sql->ListaChave('folha_ponto_gasto_id');
		$sql->limpar();
		if (count($gastos)) {
			if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_arquivo') echo '<tr><td colspan=20><table cellspacing="1" cellpadding="2" border=0  align=left width="100%"><tr>
			<th>Nome</th>
			<th>Descri��o</th>
			<th>Unidade</th>
			<th>Qnt</th>
			<th>Valor ('.$config['simbolo_moeda'].')</th>'.
			($config['bdi'] ? '<th>'.dica('BDI', 'Benef�cios e Despesas Indiretas, � o elemento or�ament�rio destinado a cobrir todas as despesas que, num empreendimento, segundo crit�rios claramente definidos, classificam-se como indiretas (por simplicidade, as que n�o expressam diretamente nem o custeio do material nem o dos elementos operativos sobre o material � m�o-de-obra, equipamento-obra, instrumento-obra etc.), e, tamb�m, necessariamente, atender o lucro.').'BDI (%)'.dicaF().'</th>' : '').
			'<th>ND</th>
			<th>Total ('.$config['simbolo_moeda'].')</th>'.
			(isset($exibir['codigo']) && $exibir['codigo'] ? '<th>'.dica(ucfirst($config['codigo_valor']), ucfirst($config['genero_codigo_valor']).' '.$config['codigo_valor'].' do item.').ucfirst($config['codigo_valor']).dicaF().'</th>' : '').
			(isset($exibir['fonte']) && $exibir['fonte'] ? '<th>'.dica(ucfirst($config['fonte_valor']), ucfirst($config['genero_fonte_valor']).' '.$config['fonte_valor'].' do item.').ucfirst($config['fonte_valor']).dicaF().'</th>' : '').
			(isset($exibir['regiao']) && $exibir['regiao'] ? '<th>'.dica(ucfirst($config['regiao_valor']), ucfirst($config['genero_regiao_valor']).' '.$config['regiao_valor'].' do item.').ucfirst($config['regiao_valor']).dicaF().'</th>' : '').
			'<th>Data</th>
			<th>Respons�vel</th>
			<th>Data</th>
			</tr>';
			else echo '<tr><td colspan=20><table cellspacing="1" cellpadding="2" border=0  align=left width="100%"><tr><th colspan=6>ND</th><th colspan=5>Valor ('.$config['simbolo_moeda'].')</th></tr>';
			$qnt=0;
			$gasto_itens=0;
			$gasto_nd=array();
			foreach ($gastos as $folha_ponto_id => $item) {
				
				if ($ponto_relatorio_tipo=='data_custo' || $ponto_relatorio_tipo=='data_custo_arquivo'){
					echo '<tr align="center">';
					echo '<td align="left" width="190">'.++$qnt.' - '.$item['folha_ponto_gasto_nome'].'</td>';
					echo '<td align="left">'.($item['folha_ponto_gasto_descricao'] ? $item['folha_ponto_gasto_descricao'] : '&nbsp;').'</td>';
					echo '<td width="30">'.$unidade[$item['folha_ponto_gasto_tipo']].'</td><td width="50" align="right">'.number_format($item['folha_ponto_gasto_quantidade'], 2, ',', '.').'</td>';
					echo '<td align="right" width="70" align="right">'.number_format($item['folha_ponto_gasto_gasto'], 2, ',', '.').'</td>';
					
					if ($config['bdi']) echo '<td align="right">'.number_format($item['folha_ponto_gasto_bdi'], 2, ',', '.').'</td>';
					
					echo '<td width="10" nowrap="nowrap">'.($item['folha_ponto_gasto_categoria_economica'] && $item['folha_ponto_gasto_grupo_despesa'] && $item['folha_ponto_gasto_modalidade_aplicacao'] ? $item['folha_ponto_gasto_categoria_economica'].'.'.$item['folha_ponto_gasto_grupo_despesa'].'.'.$item['folha_ponto_gasto_modalidade_aplicacao'].'.' : '&nbsp;').$item['folha_ponto_gasto_nd'].'</td>';
					echo '<td align="right" width="70">'.number_format($item['valor'], 2, ',', '.').'</td>';
					
					if (isset($exibir['codigo']) && $exibir['codigo']) echo'<td align="center">'.($item['folha_ponto_gasto_codigo'] ? $item['folha_ponto_gasto_codigo'] : '&nbsp;').'</td>';
					if (isset($exibir['fonte']) && $exibir['fonte']) echo'<td align="center">'.($item['folha_ponto_gasto_fonte'] ? $item['folha_ponto_gasto_fonte'] : '&nbsp;').'</td>';
					if (isset($exibir['regiao']) && $exibir['regiao']) echo'<td align="center">'.($item['folha_ponto_gasto_regiao'] ? $item['folha_ponto_gasto_regiao'] : '&nbsp;').'</td>'; 
					
					echo '<td width="10" nowrap="nowrap">'.($item['folha_ponto_gasto_data_limite']? retorna_data($item['folha_ponto_gasto_data_limite'],false) : '&nbsp;').'</td>';
					echo '<td align="left" nowrap="nowrap" width="150" >'.$item['nome_usuario'].'</td>';
					echo '<td width="10" nowrap="nowrap">'.($item['folha_ponto_gasto_data_limite']? retorna_data($item['folha_ponto_gasto_data_limite'],false) : '&nbsp;').'</td>';
					echo '</tr>';
					}
					
				if(isset($gasto_nd[$item['folha_ponto_gasto_nd']])) $gasto_nd[$item['folha_ponto_gasto_nd']] += (float)($item['valor']);
				else $gasto_nd[$item['folha_ponto_gasto_nd']] = (float)($item['valor']);
				
				if(isset($gasto_nd_geral[$item['folha_ponto_gasto_nd']])) $gasto_nd_geral[$item['folha_ponto_gasto_nd']] += (float)($item['valor']);
				else $gasto_nd_geral[$item['folha_ponto_gasto_nd']] = (float)($item['valor']);
				
				$gasto_itens+=(float)($item['valor']);
				$gasto_itens_geral+=(float)($item['valor']);
				}
			if ($gasto_itens) {
				echo '<tr><td colspan="7" class="std" align="right">';
				foreach ($gasto_nd as $indice_nd => $somatorio) if ($somatorio > 0) echo (isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND').'<br>';
				echo '<b>Total</td><td align="right" width="90">';	
				foreach ($gasto_nd as $indice_nd => $somatorio) if ($somatorio > 0) echo number_format($somatorio, 2, ',', '.').'<br>';
				echo '<b>'.number_format($gasto_itens, 2, ',', '.').'</b></td>'.($ponto_relatorio_tipo=='data_custo' ? '<td colspan="20">&nbsp;</td>' : '').'</tr>';	
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
if ($usuarioatual) echo '<tr><td colspan=4 align=right><b>Total Parcial</b></td><td align=right>'.number_format($horas, 1, ',', '.').'</td><td align=right>'.number_format($valor_horas, 2, ',', '.').'</td></tr>';		
if (!count($existe)) '<tr><td colspan=4 align=right>N�o foi encontrado nenhum per�odo marcado como trabalhado</td></tr>';
echo '</table></td></tr>';
echo '</table>';


if ($gasto_itens_geral || count($existe)) {
		echo '<table cellspacing=4 cellpadding=0 border=0>';
		echo '<tr><td align=right><b>Sum�rio</b></td>&nbsp;<td></tt><tr>';
		if ($gasto_itens_geral) {
			echo '<tr><td align=right  nowrap="nowrap">';
			foreach ($gasto_nd_geral as $indice_nd => $somatorio) if ($somatorio > 0) echo (isset($nd[$indice_nd]) ? $nd[$indice_nd] : 'Sem ND').'<br>';
			echo 'Soma parcial</td><td align="right" nowrap="nowrap">';	
			foreach ($gasto_nd_geral as $indice_nd => $somatorio) if ($somatorio > 0) echo $config['simbolo_moeda'].' '.number_format($somatorio, 2, ',', '.').'<br>';
			echo $config['simbolo_moeda'].' '.number_format($gasto_itens_geral, 2, ',', '.').'</td></tr>';	
			}
		if (count($existe))	{
			echo '<tr><td align=right>Horas</td><td align=right>'.number_format($horas_geral, 1, ',', '.').'</td></tr>';
			echo '<tr><td align=right>Custo das horas</td><td align=right>'.$config['simbolo_moeda'].' '.number_format($valor_horas_geral, 2, ',', '.').'</td></tr>';	
			}
		echo '<tr><td align=right><b>Soma Final</b></td><td align=right><b>'.$config['simbolo_moeda'].' '.number_format($valor_horas_geral+$gasto_itens_geral, 2, ',', '.').'</b></td></tr>';
		echo '</table>';
		}		
echo '</form>';

?>
