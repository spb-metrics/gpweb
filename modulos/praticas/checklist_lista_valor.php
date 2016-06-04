<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $estilo_interface, $pratica_indicador_id, $editar, $pratica_indicador;

$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_valor_data');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;

//echo '<table border=0 cellpadding=0 cellspacing=1 width="100%" class="std">';
$sql->adTabela('checklist_dados');
$sql->adCampo('checklist_dados_id, pratica_indicador_valor_valor, pratica_indicador_valor_data, checklist_dados_responsavel, checklist_dados_obs');
$sql->adOnde('pratica_indicador_valor_indicador = '.(int)$pratica_indicador_id);
$sql->adOrdem($ordenar.($ordem ? ' ASC' : ' DESC'));
$valores = $sql->Lista();
$sql->limpar();


$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_indicadores'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$xpg_totalregistros = ($valores ? count($valores) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'valor', 'valores','','&pratica_indicador_id='.$pratica_indicador_id,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table border=0 cellpadding="0" cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
if ($editar) echo '<th width="16"><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=checklist_editar_valor&pratica_indicador_id='.$pratica_indicador_id.'\');">'.imagem('icones/adicionar.png','Preencher Checklist','Clique neste ícone '.imagem('icones/adicionar.png').' inserir respostas para o checklist deste indicador.').'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_indicador_valor_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inserção do  valor.').($ordenar=='pratica_indicador_valor_data' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_indicador_valor_valor&ordem='.($ordem ? '0' : '1').'\');">'.dica('Valor', 'O valor inserido no indicador.').($ordenar=='pratica_indicador_valor_valor' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Valor'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].')' : '').dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=checklist_dados_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Responsável pela inserção do valor.').($ordenar=='checklist_dados_responsavel' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Responsável'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=0&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=checklist_dados_obs&ordem='.($ordem ? '0' : '1').'\');">'.dica('Observações', 'Observações nesta inserção.').($ordenar=='checklist_dados_obs' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Observações'.dicaF().'</th>';
echo '</tr>';


for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$valor = $valores[$i];	
	
		if ($Aplic->profissional){
			$sql->adTabela('indicador_valor_arquivo');
			$sql->adCampo('count(indicador_valor_arquivo_id)');
			$sql->adOnde('indicador_valor_arquivo_checklist='.(int)$valor['checklist_dados_id']);
			$sql->adOrdem('indicador_valor_arquivo_ordem ASC');
			$tem_arquivo=$sql->Resultado();
			$sql->limpar();
			$icone=($tem_arquivo ? '<a href="javascript: void(0);" onclick="exibir_arquivo('.$valor['checklist_dados_id'].');">'.imagem('icones/anexar.png', 'Anexar Arquivo', 'Clique neste ícone '.imagem('icones/anexar.png').' para exibir os arquivos anexados ao valor.').'</a>' : imagem('icones/vazio16.gif'));
			}		
		else $icone='';
	
		if ($editar) echo '<td width="16"><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=checklist_editar_valor&checklist_dados_id='.$valor['checklist_dados_id'].'\');">'.imagem('icones/editar.gif','Editar','Clique neste ícone '.imagem('icones/editar.gif').' para editar o checklist.').'</a></td>';
		echo '<td style="width:65px;">'.retorna_data($valor['pratica_indicador_valor_data'], false).'</td>';
		echo '<td align=right><a href="javascript:void(0);" onclick="popChecklist('.$pratica_indicador_id.','.$valor['checklist_dados_id'].');">'.dica('Checklist','Clique neste valor para verificar o preenchimento da checklist.').number_format($valor['pratica_indicador_valor_valor'], 2, ',', '.').dicaF().'</a>'.$icone.'</td>';
		echo '<td>'.link_usuario($valor['checklist_dados_responsavel'], '','','esquerda').'</td>';
		echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($valor['checklist_dados_obs']? $valor['checklist_dados_obs'] : '&nbsp;').'</td>';
		echo '</tr>';
		}

		
if(!$valores || !count($valores)) echo '<tr><td colspan=20>Não há checklist realizados para este indicador</td></tr>';
		
echo '</table>';



?>
<script language="javascript">
function popChecklist(pratica_indicador_id, checklist_dados_id) {
	if(window.parent && window.parent.gpwebApp) window.parent.gpwebApp.popUp("Checklist", 800, 600, 'm=praticas&a=checklist_explodir&dialogo=1&pratica_indicador_id='+pratica_indicador_id+'&checklist_dados_id='+checklist_dados_id, null, window);
	else window.open('./index.php?m=praticas&a=checklist_explodir&dialogo=1&pratica_indicador_id='+pratica_indicador_id+'&checklist_dados_id='+checklist_dados_id, 'Checklist','height=500,width=800,resizable,scrollbars=yes, left=0, top=0');
	}

function exibir_arquivo(checklist_dados_id){
	parent.gpwebApp.popUp('Arquivos Anexados', 400, 400, 'm=praticas&a=indicador_valor_anexo_exibir_pro&dialogo=1&checklist_dados_id='+checklist_dados_id, null, window);
	}
</script>



