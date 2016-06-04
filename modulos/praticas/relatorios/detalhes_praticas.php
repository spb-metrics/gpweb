<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $dialogo, $pratica_modelo_id, $cia_id, $ano, $dialogo, $relatorio_tipo_var, $usuario_id, $ano;


$sql = new BDConsulta;

$sql->adTabela('pratica_nos_marcadores');
$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
$sql->adCampo('DISTINCT pratica_id');
$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
$sql->adOrdem('pratica_nome ASC');
$praticas=$sql->carregarColuna();
$sql->limpar();

if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');


if (!$dialogo){
	echo '<table border=0 cellpadding=0 cellspacing=0 width="100%"><tr><td colspan=20 align="center"><font size="4"><center>Lista d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).'</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	echo '<table width="100%" cellpadding=0 cellspacing=0 class="std">';
	}
else echo '<table width="750"><tr><td colspan=20 align="center"><font size="4"><center>Lista d'.$config['genero_pratica'].'s '.ucfirst($config['praticas']).'</center></font></td></tr>';		


foreach($praticas as $pratica_id) {
	$sql->adTabela('praticas');
	$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
	$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
	$sql->adCampo('praticas.*, pratica_requisito.*');
	$sql->adOnde('pratica_requisito.ano='.(int)$ano);
	$sql->adOnde('praticas.pratica_id='.(int)$pratica_id);
	$pratica=$sql->Linha();
	$sql->limpar();
	
	if (permiteAcessarPratica($pratica['pratica_acesso'],$pratica_id)){ 

		echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$pratica['pratica_cor'].'" colspan="2"><font color="'.melhorCor($pratica['pratica_cor']).'"><b>'.$pratica['pratica_nome'].'<b></font></td></tr>';
		$sql->adTabela('pratica_usuarios');
		$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
		$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
		$sql->esqUnir('depts', 'depts', 'depts.dept_id = contato_dept');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = contato_cia');
		$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, dept_nome, cia_nome');
		$sql->adOnde('pratica_id = '.(int)$pratica_id);
		$participantes = $sql->Lista();
		$sql->limpar();
		$sql->adTabela('pratica_depts');
		$sql->esqUnir('depts', 'depts', 'depts.dept_id = pratica_depts.dept_id');
		$sql->esqUnir('cias', 'cias', 'cias.cia_id = dept_cia');
		$sql->adCampo('dept_nome, cia_nome');
		$sql->adOnde('pratica_id = '.(int)$pratica_id);
		$departamentos = $sql->Lista();
		$sql->limpar();
		if ($pratica['pratica_descricao']) echo '<tr><td colspan="2" align="center" >'.dica('Descrição', 'Descrição d'.$config['genero_pratica'].' '.$config['pratica'].'.').'<b>Descrição</b>'.dicaF().'</td></tr><tr><td colspan="2" >'.$pratica['pratica_descricao'].'</td></tr>';
		if ($pratica['pratica_oque']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('O Que Fazer', 'Sumário sobre o que se trata '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'O Que:'.dicaF().'</td><td  width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_oque'].'</td></tr>';
		if ($pratica['pratica_porque']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Por Que Fazer', 'Por que '.$config['genero_pratica'].' '.$config['pratica'].' será executad'.$config['genero_pratica'].'.').'Por que:'.dicaF().'</td><td  width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_porque'].'</td></tr>';
		if ($pratica['pratica_onde']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Onde Fazer', 'Onde '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Onde:'.dicaF().'</td><td  width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_onde'].'</td></tr>';
		if ($pratica['pratica_quando']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quando Fazer', 'Quando '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Quando:'.dicaF().'</td><td  width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_quando'].'</td></tr>';
		$saida_quem='';
		if ($participantes && count($participantes)) {
				$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$saida_quem.= '<tr><td>';
				$qnt_participantes=count($participantes);
				if ($qnt_participantes) {		
					for ($i = 0, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $saida_quem.= ($i ? '<br>' : '').$participantes[$i]['nome_usuario'].($participantes[$i]['dept_nome'] ? ' - '.$participantes[$i]['dept_nome'] : '').($participantes[$i]['cia_nome'] ? ' - '.$participantes[$i]['cia_nome'] : '');		
					}
				$saida_quem.= '</td></tr></table>';
				} 
		if ($saida_quem || $pratica['pratica_quem']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quem', 'Quais '.$config['usuarios'].' estarão executando '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').'Quem:'.dicaF().'</td><td width="100%" colspan="2" ><table cellspacing=0 cellpadding=0><tr><td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.($pratica['pratica_quem'] ? $pratica['pratica_quem'] : '').'</td></tr><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';
		$saida_depts='';
		if ($departamentos && count($departamentos)) {
				$saida_depts.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
				$qnt_lista_depts=count($departamentos);
				if ($qnt_lista_depts) {		
						$lista='';
						for ($i = 0, $i_cmp = $qnt_lista_depts; $i < $i_cmp; $i++) $saida_depts.=($i ? '<br>' : '').$departamentos[$i]['dept_nome'].($departamentos[$i]['cia_nome'] ? ' - '.$departamentos[$i]['cia_nome'] : '');		
						}
				$saida_depts.= '</td></tr></table>';
				} 
		if ($saida_depts) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Qual '.strtolower($config['departamento']).' está relacionad'.$config['genero_dept'].' à '.($config['genero_pratica']=='a' ? 'esta ': 'este ').$config['pratica'].'.').ucfirst($config['departamento']).':'.dicaF().'</td><td width="100%" colspan="2" >'.$saida_depts.'</td></tr>';
		if ($pratica['pratica_como']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Como Fazer', 'Como '.$config['genero_pratica'].' '.$config['pratica'].' é executad'.$config['genero_pratica'].'.').'Como:'.dicaF().'</td><td  width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_como'].'</td></tr>';
		if ($pratica['pratica_quanto']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Quanto Custa', 'Custo para executar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Quanto:'.dicaF().'</td><td  width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_quanto'].'</td></tr>';
		require_once ($Aplic->getClasseSistema('CampoCustomizados'));
		$campos_customizados = new CampoCustomizados('praticas', $pratica['pratica_id'], 'ver');
		if ($campos_customizados->count()) {
				echo '<tr><td colspan="2">';
				$campos_customizados->imprimirHTML();
				echo '</td></tr>';
				}		
		if ($pratica['pratica_responsavel']) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_pratica'].' '.ucfirst($config['pratica']), ucfirst($config['usuario']).' responsável por gerenciar '.$config['genero_pratica'].' '.$config['pratica'].'.').'Responsável:'.dicaF().'</td><td  width="100%">'.nome_funcao('','','','',$pratica['pratica_responsavel'], true, true).'</td></tr>';		
		
		//campos utilizados na regua específica	
		$sql->adTabela('pratica_regra_campo');
		$sql->adCampo('pratica_regra_campo_nome, pratica_regra_campo_texto, pratica_regra_campo_descricao');
		$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
		$sql->adOnde('pratica_regra_campo_resultado=0');
		$vetor_campos=$sql->Lista();
		$sql->limpar();	
		
		$sql->adTabela('pratica_requisito');
		$sql->adCampo('pratica_requisito.*');
		$sql->adOnde('pratica_id = '.(int)$pratica_id);
		$sql->adOnde('ano = '.(int)$ano);
		$requisito = $sql->linha();
		$sql->limpar();

		
		
		$campos=array();
		echo '<tr><td colspan=20><table cellpadding=0 cellspacing=0>';
		foreach ($vetor_campos as $linha)	{
			$campos[]=$linha['pratica_regra_campo_nome'];
			if (isset($requisito[$linha['pratica_regra_campo_nome']])) echo '<tr><td align=right>'.dica($linha['pratica_regra_campo_texto'],$linha['pratica_regra_campo_descricao']).$linha['pratica_regra_campo_texto'].dicaF().'</td><td>'.($requisito[$linha['pratica_regra_campo_nome']] ? imagem('icones/ponto.png') : '&nbsp;').'</td></tr>';
			}
		echo '</table></td></tr>';
		
		$sql->adTabela('pratica_nos_marcadores');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_obs, pratica_criterio_pontos, pratica_criterio_numero');
		$sql->adOnde('pratica_id='.(int)$pratica_id);
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$criterios=$sql->ListaChaveSimples('pratica_criterio_id');
		$sql->limpar();
		
		$sql->adTabela('pratica_nos_marcadores');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		$sql->adCampo('pratica_item_id, pratica_item_numero, pratica_item_nome, pratica_item_pontos, pratica_item_obs');
		$sql->adOnde('pratica_id='.(int)$pratica_id);
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$itens=$sql->ListaChaveSimples('pratica_item_id');
		$sql->limpar();
		
		$sql->adTabela('pratica_nos_marcadores');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		$sql->adCampo('pratica_criterio_id, pratica_item_id, pratica_marcador_letra, pratica_marcador_texto, pratica_marcador_extra');
		$sql->adOnde('pratica_id='.(int)$pratica_id);
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOrdem('pratica_criterio_numero');
		$sql->adOrdem('pratica_item_numero');
		$sql->adOrdem('pratica_marcador_letra');
		$marcadores=$sql->Lista();
		$sql->limpar();
		
		$criterio_atual='';
		$marcador_atual='';

		if ($marcadores && count($marcadores)) echo '<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>'.ucfirst($config['marcadores']).' Atendid'.$config['genero_marcador'].'s pel'.$config['genero_pratica'].' '.$config['pratica'].'<b></p></td></tr>';
		else echo '<tr><td align="left" colspan=2 nowrap="nowrap"><p><b>Nenhum '.$config['marcador'].' encontrad'.$config['genero_marcador'].'<b></p></td></tr>';
		foreach($marcadores as $dado){
			if ($dado['pratica_criterio_id']!=$criterio_atual){
				$criterio_atual=$dado['pratica_criterio_id'];
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_obs'].'</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_pontos'].'</td></tr>';
				$dentro .= '</table>';
				echo '<tr><td align="left" colspan=2 nowrap="nowrap">'.dica('Dados Sobre o Critério', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$criterios[$dado['pratica_criterio_id']]['pratica_criterio_nome'].dicaF().'</td></tr>';
				}
			if ($dado['pratica_item_id']!=$marcador_atual){
				$marcador_atual=$dado['pratica_item_id'];
				$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Observações</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_obs'].'</td></tr>';
				$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Pontos</b></td><td>'.$itens[$dado['pratica_item_id']]['pratica_item_pontos'].'</td></tr>';
				$dentro .= '</table>';
				echo '<tr><td align="left" colspan=2 nowrap="nowrap">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.dica('Dados Sobre o Critério', $dentro).$criterios[$dado['pratica_criterio_id']]['pratica_criterio_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_numero'].'.'.$itens[$dado['pratica_item_id']]['pratica_item_nome'].dicaF().'</td></tr>';
				}
			echo '<tr><td align="right" nowrap="nowrap" valign="top">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>'.$dado['pratica_marcador_letra'].'</b></td><td  width="100%">'.dica('Informações Extras', $dado['pratica_marcador_extra']).$dado['pratica_marcador_texto'].'</td></tr>';
			}															
		echo '<tr><td colspan=20>&nbsp;</td></tr>';
		}
	}	
	
if (!count($praticas)) echo '<tr><td><h2>'.($config['genero_pratica']=='a' ? 'Nenhuma' :'Nenhum').' '.$config['pratica'].' encontrad'.$config['genero_pratica'].(!$pratica_modelo_id ? '. Escolha uma pauta de pontuação.':'').'<h2></td></tr>';	
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();	
else echo '<script>self.print();</script>';	


?>
<script language="javascript">
	
function imprimir(){
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('', 780, 500, 'm=praticas&a=relatorios&dialogo=1&relatorio_tipo=<?php echo $relatorio_tipo_var?>', null, window);
	else window.open('./index.php?m=praticas&a=relatorios&dialogo=1&relatorio_tipo=<?php echo $relatorio_tipo_var?>', 'Relatório','left=0,top=0,height=600,width=780,scrollbars=yes, resizable=yes')
	
	}	
	
	
function expandir_multipratica(id, tabelaNome) {
  var trs = document.getElementsByTagName('tr');
  for (var i=0, i_cmp=trs.length;i < i_cmp;i++) {
    var tr_nome = trs.item(i).id;
    if (tr_nome.indexOf(id) >= 0) {
     	var tr = document.getElementById(tr_nome);
     	tr.style.visibility = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'visible' : 'colapsar';
     	var img_expandir = document.getElementById(id+'_expandir');
     	var img_colapsar = document.getElementById(id+'_colapsar');
     	img_colapsar.style.display = (tr.style.visibility == 'visible') ? 'inline' : 'none';
     	img_expandir.style.display = (tr.style.visibility == '' || tr.style.visibility == 'colapsar') ? 'inline' : 'none';
			}
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>