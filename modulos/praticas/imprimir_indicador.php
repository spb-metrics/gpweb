<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $m, $a;
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);
$pratica_indicador_id = intval(getParam($_REQUEST, 'pratica_indicador_id', 0));

if ($Aplic->profissional) {
	$barra=codigo_barra('indicador', $pratica_indicador_id);
	if ($barra['cabecalho']) echo $barra['imagem'];
	}

$sql = new BDConsulta();
$sql->adTabela('pratica_indicador_requisito');
$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador.pratica_indicador_requisito = pratica_indicador_requisito.pratica_indicador_requisito_id');
$sql->adCampo('DISTINCT pratica_indicador_requisito_ano');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOrdem('pratica_indicador_requisito_ano');
$anos=$sql->listaVetorChave('pratica_indicador_requisito_ano','pratica_indicador_requisito_ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);


$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null ? $Aplic->getEstado('IdxIndicadorAno') : $ultimo_ano);

echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';

$numero=0; 

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
$sql->esqUnir('pratica_indicador_nos_marcadores', 'pratica_indicador_nos_marcadores', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_indicador_nos_marcadores.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->esqUnir('cias', 'cias', 'pratica_indicador.pratica_indicador_cia=cias.cia_id');
$sql->adCampo('pratica_indicador.*, pratica_indicador_requisito.*, cia_cabacalho');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$pratica_indicador=$sql->Linha();
$sql->limpar();

include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
$obj_indicador = new Indicador($pratica_indicador_id, $ano);


echo '<table width="800" cellpadding=0 cellspacing="18">';
echo '<tr><td align="center"><h1>'.$pratica_indicador['cia_cabacalho'].'<br>INDICADOR<br>'.$pratica_indicador['pratica_indicador_nome'].'</h1></td></tr><tr><td>';
if ($pratica_indicador['pratica_indicador_requisito_descricao']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. DESCRIÇÃO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_descricao'].'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_responsavel']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. RESPONSÁVEL PELO INDICADOR</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.nome_funcao('','','','',$pratica_indicador['pratica_indicador_responsavel']).'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_oque']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. O QUE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_oque'].'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_porque']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. POR QUE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_porque'].'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_onde']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. ONDE</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_onde'].'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quando']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. QUANDO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_quando'].'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_como']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. COMO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_como'].'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_quanto']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. QUANTO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_quanto'].'</font></td></tr>';

$sql->adTabela('pratica_indicador_usuarios');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_indicador_usuarios.usuario_id');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$participantes = $sql->Lista();
$saida_quem='';
if ($participantes && count($participantes)) {
		$qnt_participantes=count($participantes);
		$lista='';
		for ($i = 0, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) {
			$saida_quem.= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.nome_funcao('','','','',$participantes[$i]['usuario_id']);	
			}	
		} 
if ($saida_quem || $pratica_indicador['pratica_indicador_requisito_quem']) echo '<tr><td align="left" style="margin-bottom:0cm; margin-top:0cm;"><font size=3><b>'.++$numero.'. QUEM</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.($pratica_indicador['pratica_indicador_requisito_quem'] ? $pratica_indicador['pratica_indicador_requisito_quem'] :'').'</font>'.$saida_quem.'</font></td></tr>';

$sql->adTabela('pratica_indicador_depts');
$sql->esqUnir('depts', 'depts', 'depts.dept_id = pratica_indicador_depts.dept_id');
$sql->adCampo('dept_nome');
$sql->adOnde('pratica_indicador_id = '.(int)$pratica_indicador_id);
$departamentos = $sql->Lista();
$sql->limpar();
$saida_depts='';
foreach($departamentos as $dept) $saida_depts.= '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.$dept['dept_nome'];	
if ($saida_depts) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. '.strtoupper($config['departamento']).'</b></font><font size=2>'.$saida_depts.'</font></td></tr>';

if ($pratica_indicador['pratica_indicador_desde_quando']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. DESDE QUANDO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.retorna_data($pratica_indicador['pratica_indicador_desde_quando'], false).'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_melhorias']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. MELHORIAS</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_melhorias'].'</font></td></tr>';
$tipo_polaridade=array(0 => 'Melhor se menor', 1 => 'Melhor se maior', 2 => 'Melhor se no centro');
echo '<tr><td align="left"><font size=3><b>'.++$numero.'. POLARIDADE DO INDICADOR</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.(isset($tipo_polaridade[$pratica_indicador['pratica_indicador_sentido']]) ? $tipo_polaridade[$pratica_indicador['pratica_indicador_sentido']] : 'Indefinido').'</font></td></tr>';
if ($pratica_indicador['pratica_indicador_requisito_referencial']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. REFERENCIAL COMPARATIVO</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_referencial'].'</font></td></tr>';
if ($obj_indicador->pratica_indicador_valor_referencial!=null) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. VALOR DO REFERENCIAL '.strtoupper($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').'</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.number_format($obj_indicador->pratica_indicador_valor_referencial, 2, ',', '.').'</font></td></tr>';
echo '<tr><td align="left"><font size=3><b>'.++$numero.'. META A ATINGIR '.strtoupper($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].') ' : '').'</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.number_format($obj_indicador->pratica_indicador_valor_meta, 2, ',', '.').($obj_indicador->pratica_indicador_data_meta ? ' em '.retorna_data($obj_indicador->pratica_indicador_data_meta, false) : '').'</font></td></tr>';



echo '<tr><td align="left"><font size=3><b>'.++$numero.'. TENDÊNCIA</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$obj_indicador->Tendencia().'</font></td></tr>';



echo '<tr><td align="left"><font size=3><b>'.++$numero.'. GRÁFICO</b></font></td></tr>';	
	

		
$pratica_indicador_mostrar_valor=(isset($_REQUEST['pratica_indicador_mostrar_valor']) && isset($_REQUEST['pratica_indicador_mostrar_valor']) || (!isset($_REQUEST['pratica_indicador_mostrar_valor']) && $pratica_indicador['pratica_indicador_mostrar_valor']) ? 1 : 0);
$pratica_indicador_mostrar_titulo=(isset($_REQUEST['pratica_indicador_mostrar_titulo']) && isset($_REQUEST['pratica_indicador_mostrar_titulo']) || (!isset($_REQUEST['pratica_indicador_mostrar_titulo']) && $pratica_indicador['pratica_indicador_mostrar_titulo']) ? 1 : 0);
$pratica_indicador_max_min=(isset($_REQUEST['pratica_indicador_max_min']) && isset($_REQUEST['pratica_indicador_max_min']) || (!isset($_REQUEST['pratica_indicador_max_min']) && $pratica_indicador['pratica_indicador_max_min']) ? 1 : 0);
$pratica_indicador_tipografico=(isset($_REQUEST['pratica_indicador_tipografico']) ? getParam($_REQUEST, 'pratica_indicador_tipografico', null) : $pratica_indicador['pratica_indicador_tipografico']);
$pratica_indicador_agrupar=(isset($_REQUEST['pratica_indicador_agrupar']) ? getParam($_REQUEST, 'pratica_indicador_agrupar', null) : $pratica_indicador['pratica_indicador_agrupar']);
$pratica_indicador_nr_pontos=(isset($_REQUEST['pratica_indicador_nr_pontos']) ? getParam($_REQUEST, 'pratica_indicador_nr_pontos', null) : $pratica_indicador['pratica_indicador_nr_pontos']);
$df = '%d/%m/%Y';
$data = (isset($_REQUEST['pratica_indicador_data']) ? new CData(getParam($_REQUEST, 'pratica_indicador_data', null)) : new CData());
$data_final=$data->format("%Y-%m-%d");
$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.$ano.'&mostrar_valor='.$pratica_indicador_mostrar_valor.'&data_final='.$data_final.'&nr_pontos='.$pratica_indicador_nr_pontos.'&mostrar_titulo='.$pratica_indicador_mostrar_titulo.'&max_min='.$pratica_indicador_max_min.'&agrupar='.$pratica_indicador_agrupar.'&tipografico='.$pratica_indicador_tipografico.'&pratica_indicador_id='.$pratica_indicador_id."&width=800";
echo '<tr><td align="left">';
echo "<table cellspacing='0' cellpadding='0' align='center' class='tbl1'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr></table>";
echo '</td></tr>';





if ($pratica_indicador['pratica_indicador_requisito_lider'] && $pratica_indicador['pratica_indicador_requisito_justificativa_lider']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. DEMONSTRA LIDERANÇA</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_justificativa_lider'].'</font></td></tr>';

if ($pratica_indicador['pratica_indicador_requisito_excelencia'] && $pratica_indicador['pratica_indicador_requisito_justificativa_excelencia']) echo '<tr><td align="left"><font size=3><b>'.++$numero.'. DEMONSTRA EXCELÊNCIA</b></font><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2>'.$pratica_indicador['pratica_indicador_requisito_justificativa_excelencia'].'</font></td></tr>';


//marcadores

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador_nos_marcadores');
$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=1');
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();

$sql->adTabela('pratica_indicador_nos_marcadores');
$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=1');
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();


$sql->adTabela('pratica_indicador_nos_marcadores');
$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=1');
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();

$criterio_atual='';
$marcador_atual='';


if ($marcadores && count($marcadores)){
	echo '<tr><td colspan=20><table width=100%>';
	echo '<tr><td align="left" colspan=20><font size=2><b>'.++$numero.'. '.strtoupper($config['marcador']).' DE RESULTADO</b></font></td></tr>';	
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			$criterio_atual=$dado['pratica_criterio_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
			$dentro .= '</table>';
			echo '<tr><td align="left" colspan=2 nowrap="nowrap">'.dica('Dados Sobre o Critério', $dentro).'<font size=2>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</font>'.dicaF().'</td></tr>';
			}
		if ($dado['pratica_item_id']!=$marcador_atual){
			$marcador_atual=$dado['pratica_item_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
			$dentro .= '</table>';
			echo '<tr><td align="left" colspan=2 nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Dados Sobre o Critério', $dentro).'<font size=2>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</font>'.dicaF().'</td></tr>';
			}
	
		echo '<tr><td align="right" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2><b>'.$dado['pratica_marcador_letra'].'</b></font></td><td class="realce" width="100%">'.dica('Informações Extras', $dado['pratica_marcador_extra']).'<font size=2>'.$dado['pratica_marcador_texto'].'</font>'.dicaF().'</td></tr>';
		}
	echo '</table></td></tr>';
	}

$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
$sql->limpar();

$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$itens=$sql->ListaChaveSimples('pratica_item_id');
$sql->limpar();

$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
$sql->adCampo('pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
$sql->adOnde('pratica_indicador.pratica_indicador_id='.(int)$pratica_indicador_id);
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOrdem('pratica_criterio_numero');
$sql->adOrdem('pratica_marcador_letra');
$marcadores=$sql->Lista();
$sql->limpar();

$criterio_atual='';
$marcador_atual='';

if ($marcadores && count($marcadores)) {
	
	echo '<tr><td colspan=20><table width=100%>';
	echo '<tr><td align="left" colspan=2 nowrap="nowrap"><p><font size=2><b>'.ucfirst($config['marcadores']).' Atendid'.$config['genero_marcador'].'s pel'.$config['genero_pratica'].'s '.$config['praticas'].' que utilizam o indicador<b></font></p></td></tr>';
	foreach($marcadores as $dado){
		if ($dado['pratica_criterio_id']!=$criterio_atual){
			$criterio_atual=$dado['pratica_criterio_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
			$dentro .= '</table>';
			echo '<tr><td align="left" colspan=2 nowrap="nowrap">'.dica('Dados Sobre o Critério', $dentro).'<font size=2>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].'</font>'.dicaF().'</td></tr>';
			}
		if ($dado['pratica_item_id']!=$marcador_atual){
			$marcador_atual=$dado['pratica_item_id'];
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
			$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
			$dentro .= '</table>';
			echo '<tr><td align="left" colspan=2 nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Dados Sobre o Critério', $dentro).'<font size=2>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].'</font>'.dicaF().'</td></tr>';
			}
	
		echo '<tr><td align="right" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font size=2><b>'.$dado['pratica_marcador_letra'].'</font></b></td><td class="realce" width="100%">'.dica('Informações Extras', $dado['pratica_marcador_extra']).'<font size=2>'.$dado['pratica_marcador_texto'].'</font></td></tr>';
		}
	echo '</table></td></tr>';	
	}
	

if (!$Aplic->profissional && $config['barra_projeto']){
	echo '<tr><td colspan=20><table><tr><td width=12></td><td><script>document.write(\'<img src="'.BASE_URL.'/lib/barras/barcode.php?quality=75&barcode=I'.$pratica_indicador_id.'\">\')</script></td></tr></table></td></tr>';
	}	
	
echo '</table>';	

if ($Aplic->profissional && $barra['rodape']) echo $barra['imagem'];

if($dialogo) echo '<script language="javascript">self.print();</script>';
?>
