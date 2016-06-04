<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

$avaliacao_id = intval(getParam($_REQUEST, 'avaliacao_id', 0));

require_once BASE_DIR.'/modulos/praticas/avaliacao.class.php';

$obj = new CAvaliacao();
$obj->load($avaliacao_id);

if (!permiteAcessarAvaliacao($obj->avaliacao_acesso,$avaliacao_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$sql = new BDConsulta();





echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 width="750">';
echo '<tr><td style="border: outset #d1d1cd 1px;background-color:#'.$obj->avaliacao_cor.'" colspan="2"><font color="'.melhorCor($obj->avaliacao_cor).'"><b>'.$obj->avaliacao_nome.'<b></font></td></tr>';
if ($obj->avaliacao_descricao) echo '<tr><td colspan="2" align="center" >'.dica('Descrição', 'Descrição da avaliação.').'<b>Descrição</b>'.dicaF().'</td></tr><tr><td colspan="2" class="realce">'.$obj->avaliacao_descricao.'</td></tr>';
if ($obj->avaliacao_responsavel) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Responsável pela Avaliação', ucfirst($config['usuario']).' responsável por gerenciar a avaliação.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.nome_usuario($obj->avaliacao_responsavel).'</td></tr>';		
		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('avaliacao', $obj->avaliacao_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		
				


$sql = new BDConsulta;


$sql->adTabela('avaliacao_indicador_lista');
$sql->esqUnir('usuarios','usuarios','usuarios.usuario_id=avaliacao_indicador_lista_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->esqUnir('pratica_indicador','pratica_indicador','avaliacao_indicador_lista_pratica_indicador_id=pratica_indicador_id');
$sql->esqUnir('pratica_indicador_valor','pratica_indicador_valor','avaliacao_indicador_lista_pratica_indicador_valor_id=pratica_indicador_valor.pratica_indicador_valor_id');
$sql->esqUnir('checklist_dados','checklist_dados','avaliacao_indicador_lista_checklist_dados_id=checklist_dados.checklist_dados_id');
$sql->adCampo('avaliacao_indicador_lista_id, pratica_indicador_nome, pratica_indicador_checklist, avaliacao_indicador_lista_valor, checklist_dados.pratica_indicador_valor_valor AS valor_checklist, pratica_indicador_valor.pratica_indicador_valor_valor AS valor_simples, avaliacao_indicador_lista_checklist_campos, avaliacao_indicador_lista_observacao');
$sql->adOnde('avaliacao_indicador_lista_avaliacao='.(int)$avaliacao_id);
$sql->adOnde('avaliacao_indicador_lista_data IS NOT NULL');
$sql->adOrdem('contato_nomeguerra, pratica_indicador_nome');
$lista=$sql->Lista();
$sql->limpar();

$saida='';	
$maior=0;
$menor=0;
$igual=0;

$observacoes='';

foreach($lista as $linha){
	
	$detalhamento='';
	if ($linha['valor_checklist']) {
		$blob=unserialize($linha['avaliacao_indicador_lista_checklist_campos']);
		foreach($blob as $campo) {
			if ($campo['checklist_lista_justificativa']) $detalhamento.='<tr><td width="50%">'.$campo['checklist_lista_descricao'].'</td><td width="50%">'.$campo['checklist_lista_justificativa'].'</td></tr>';
			}
		}
	$valor=($linha['pratica_indicador_checklist'] ? $linha['valor_checklist'] : $linha['valor_simples']);
	if ($valor < $linha['avaliacao_indicador_lista_valor']) {
		$cor='168017';
		$maior++;
		}
	elseif ($valor > $linha['avaliacao_indicador_lista_valor']) {
		$cor='e74747';
		$menor++;
		}
	else {
		$cor='000000';
		$igual++;
		}	
	$saida.='<tr><td style="color: #'.$cor.'">'.$linha['pratica_indicador_nome'].'</td><td>'.number_format($valor, 2, ',', '.').'</td><td>'.number_format($linha['avaliacao_indicador_lista_valor'], 2, ',', '.').'</td><td>'.($linha['avaliacao_indicador_lista_observacao'] ? $linha['avaliacao_indicador_lista_observacao'] : '&nbsp;').'</td><td>'.($detalhamento ? botao_icone('vazio16.gif','Informações', 'selecionar ','expandir(\'indicador_'.$linha['avaliacao_indicador_lista_id'].'\')'): '&nbsp;').'</td><tr>';
	if ($detalhamento) $saida.='<tr id="indicador_'.$linha['avaliacao_indicador_lista_id'].'" style="display:" ><td colspan=20><table width="100%" cellpadding=2 cellspacing=0 class="tbl1"><tr><th>Tópico</th><th>Observação</th></tr>'.$detalhamento.'</table></td></tr>';
	}

if ($saida) echo '<tr><td></td><td><table border=1 cellpadding=0 cellspacing=0 class="tbl1" width="100%"><tr><th>Indicador</th><th width="40px;">Antes</th><th width="40px;">Após</th><th>Observação</th><th width="16px;">&nbsp;</th></tr>'.$saida.'</table></td></tr>';
	
if ($maior || $menor || $igual){	

	$src = '?m=praticas&a=grafico_pizza&sem_cabecalho=1&maior='.$maior.'&menor='.$menor.'&igual='.$igual."&width='+((navigator.appName=='Netscape'?window.innerWidth:document.body.offsetWidth)*0.95)+'";
	echo "<tr><td>&nbsp;</td><td align='center'><script>document.write('<img src=\"$src\">')</script></td></tr>";	
	}




echo '</table>';
echo '<script>self.print();</script>';

?>
<script language="javascript">

function expandir(id){
	var element = document.getElementById(id);
	element.style.display = (element.style.display == 'none') ? '' : 'none';
	}

</script>