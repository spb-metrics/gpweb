<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


global $estilo_interface, $pratica_indicador_id, $editar, $pratica_indicador, $permite_inserir_valor;

$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_valor_data');
$ordem = getParam($_REQUEST, 'ordem', '0');
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$sql = new BDConsulta;

$sql->adTabela('pratica_indicador_valor');
$sql->adCampo('pratica_indicador_valor_id, pratica_indicador_valor_valor, pratica_indicador_valor_data, pratica_indicador_valor_responsavel, pratica_indicador_valor_obs');
$sql->adOnde('pratica_indicador_valor_indicador = '.$pratica_indicador_id);
$sql->adOrdem($ordenar.($ordem ? ' ASC' : ' DESC'));
$valores = $sql->Lista();
$sql->limpar();


$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = $config['qnt_indicadores'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$xpg_totalregistros = ($valores ? count($valores) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, 'valor', 'valores','','&pratica_indicador_id='.$pratica_indicador_id,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table cellpadding=0 cellspacing=0 width="100%" class="tbl1">';
echo '<tr>';
if ($permite_inserir_valor && !$pratica_indicador['pratica_indicador_externo']) echo '<th width="16"><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_editar_valor&pratica_indicador_id='.$pratica_indicador_id.'\');">'.imagem('icones/adicionar.png','Inserir','Clique neste �cone '.imagem('icones/adicionar.png').' para inserir um novo valor.').'</a></th>';

if ($permite_inserir_valor && $pratica_indicador['pratica_indicador_externo']) echo '<th width="16"><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id='.$pratica_indicador_id.'\');">'.imagem('icones/adicionar.png','Importar','Clique neste �cone '.imagem('icones/adicionar.png').' para importar um novo valor.').'</a></th>';

echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=2&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_indicador_valor_data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data', 'Data de inser��o do valor.').($ordenar=='pratica_indicador_valor_data' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Data'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=2&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_indicador_valor_valor&ordem='.($ordem ? '0' : '1').'\');">'.dica('Valor', 'O valor inserido no indicador.').($ordenar=='pratica_indicador_valor_valor' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Valor'.($pratica_indicador['pratica_indicador_unidade'] ? ' ('.$pratica_indicador['pratica_indicador_unidade'].')' : '').dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=2&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_indicador_valor_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.dica('Respons�vel', 'Respons�vel pela inser��o do valor.').($ordenar=='pratica_indicador_valor_responsavel' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Respons�vel'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_ver&tab=2&pratica_indicador_id='.$pratica_indicador_id.'&ordenar=pratica_indicador_valor_obs&ordem='.($ordem ? '0' : '1').'\');">'.dica('Observa��es', 'Observa��es nesta inser��o.').($ordenar=='pratica_indicador_valor_obs' ? imagem('icones/'.$seta[($ordem ? 1 : 0)]) : '').'Observa��es'.dicaF().'</th>';
echo '</tr>';


for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$valor = $valores[$i];	
		if ($editar  && !$pratica_indicador['pratica_indicador_externo']) echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_editar_valor&pratica_indicador_valor_id='.$valor['pratica_indicador_valor_id'].'\');">'.imagem('icones/editar.gif','Editar','Clique neste �cone '.imagem('icones/editar.gif').' para editar o valor.').'</a></td>';
		elseif ($editar  && $pratica_indicador['pratica_indicador_externo']) echo '<td><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=indicador_importar_valor_pro&pratica_indicador_id='.$pratica_indicador_id.'&inicio='.$valor['pratica_indicador_valor_data'].'&fim='.$valor['pratica_indicador_valor_data'].'\');">'.imagem('icones/editar.gif','Importar','Clique neste �cone '.imagem('icones/editar.gif').' para importar valor na mesma data.').'</a></td>';
		
		//verifica se tem anexo
		
		if ($Aplic->profissional){
			$sql->adTabela('indicador_valor_arquivo');
			$sql->adCampo('count(indicador_valor_arquivo_id)');
			$sql->adOnde('indicador_valor_arquivo_indicador_valor='.(int)$valor['pratica_indicador_valor_id']);
			$sql->adOrdem('indicador_valor_arquivo_ordem ASC');
			$tem_arquivo=$sql->Resultado();
			$sql->limpar();
			$icone=($tem_arquivo ? '<a href="javascript: void(0);" onclick="exibir_arquivo('.$valor['pratica_indicador_valor_id'].');">'.imagem('icones/anexar.png', 'Arquivos Anexados', 'Clique neste �cone '.imagem('icones/anexar.png').' para exibir os arquivos anexados ao valor.').'</a>' : imagem('icones/vazio16.gif'));
			}		
		else $icone='';
		
		
		echo '<td  width="60" nowrap="nowrap" align=center>'.retorna_data($valor['pratica_indicador_valor_data'], false).'</td>';
		echo '<td nowrap="nowrap" align=right>'.number_format($valor['pratica_indicador_valor_valor'], $config['casas_decimais'], ',', '.').$icone.'</td>';
		echo '<td>'.link_usuario($valor['pratica_indicador_valor_responsavel'], '','','esquerda').'</td>';
		echo '<td>'.($valor['pratica_indicador_valor_obs']? $valor['pratica_indicador_valor_obs'] : '&nbsp;').'</td>';
		echo '</tr>';
		}

		
if(!$valores || !count($valores)) echo '<tr><td colspan=20>N�o h� valores inseridos neste indicador</td></tr>';
		
echo '</table>';



?>
<script language="javascript">
function exibir_arquivo(pratica_indicador_valor_id){
	parent.gpwebApp.popUp('Arquivos Anexados', 400, 400, 'm=praticas&a=indicador_valor_anexo_exibir_pro&dialogo=1&pratica_indicador_valor_id='+pratica_indicador_valor_id, null, window);
	}
</script>