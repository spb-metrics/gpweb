<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\gestao\funcoes_impressao.php		

Funções utilizadas para a impressão do relatório de gestão																																					
																																												
********************************************************************************************/
function tem_tabela($nome_tabela, $campo, $pg='pg_id'){
	global $sql, $pg_id;
	$sql->adTabela($nome_tabela);
	$sql->adCampo('count('.$campo.')');
	$sql->adOnde($pg.'='.(int)$pg_id);
	$qnt=$sql->Resultado();
	$sql->limpar();
	if ($qnt && count($qnt))return true;
	else return false;
	}


function tem_anexo($anexo){
	global $sql, $pg_id;
	$sql->adTabela('plano_gestao_arquivos');
	$sql->adCampo('count(pg_arquivos_id)');
	$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id);
	$sql->adOnde('pg_arquivo_campo=\''.$anexo.'\'');
	$qnt=$sql->Resultado();
	$sql->limpar();
	if ($qnt && count($qnt))return true;
	else return false;
	}

function imprimir_anexo($anexo){
	global $pg_id, $imagem,$sql, $tipos_imagem, $config;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR);
	$sql->adTabela('plano_gestao_arquivos');
	$sql->adCampo('pg_arquivos_id, pg_arquivo_tipo, pg_arquivo_extensao, pg_arquivo_nome, pg_arquivo_endereco');
	$sql->adOnde('pg_arquivo_pg_id='.(int)$pg_id);
	$sql->adOnde('pg_arquivo_campo=\''.$anexo.'\'');
	$sql->adOrdem('pg_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	$saida='';
	foreach ($arquivos as $ar) {
		if ($ar['pg_arquivo_tipo']=='image' && in_array($ar['pg_arquivo_extensao'], $tipos_imagem) && file_exists($base_dir.'/arquivos/gestao/'.$ar['pg_arquivo_endereco'])){
			list($largura, $altura, $tipo, $attr) = getimagesize($base_dir.'/arquivos/gestao/'.$ar['pg_arquivo_endereco']);
			$saida.='<tr><td><table cellpadding="2" cellspacing=0><tr><td><img '.($largura > 750 ? 'width="750" height="'.(int)((750/$largura)*$altura).'"' : '').' src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/gestao/'.$ar['pg_arquivo_endereco'].'" alt="" border=0 /></td></tr><tr><td align="center">imagem '.++$imagem.'</td></tr></table></td></tr>';
			}
		}
	if ($saida)$saida='<tr><td width="30">&nbsp;</td><td><table cellpadding="2" cellspacing=0>'.$saida.'</table></td></tr>';	
	return $saida;
	}	
	


function imprimir_praticas($pratica_marcador_id){
	global $pg_modelo_id, $cia_id, $ano, $sql, $config, $pratica_legenda, $praticas_vistas, $mostrado, $pratica_descricao, $pratica_5w2h, $praticas_nomes, $numero_praticas, $pratica_extra, $praticas_posicao;	
	//lista de praticas
	$sql->adTabela('pratica_nos_marcadores');
	$sql->esqUnir('pratica_marcador','pratica_marcador','pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item','pratica_item','pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio','pratica_criterio','pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
	$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id=praticas.pratica_id');
	$sql->adCampo('DISTINCT pratica, praticas.pratica_nome, pratica_criterio_numero, pratica_item_criterio, pratica_item_numero, pratica_marcador_letra');
	$sql->adCampo('pratica_requisito.*');
	$sql->adOnde('marcador='.(int)$pratica_marcador_id);
	
	$sql->adOnde('pratica_cia='.(int)$cia_id);
	
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pg_modelo_id);
	$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
	$sql->adOnde('pratica_requisito.ano='.(int)$ano);
	$sql->adOnde('pratica >0');
	$sql->adOrdem('pratica ASC');
	$praticas=$sql->Lista();
	$sql->limpar();	
	$saida='';
	
	
	if ($praticas&& count($praticas)) $saida.= '<table cellpadding="2" cellspacing=0 width="100%">';
	foreach($praticas as $pratica_atual){
		
		if (!isset($praticas_vistas[$pratica_atual['pratica']])){

				$pratica=$pratica_atual;
				
				
				$numero_praticas++;
				$praticas_vistas[$pratica_atual['pratica']]=$numero_praticas;
				$praticas_nomes[$pratica_atual['pratica']]=$pratica['pratica_nome'];

				$praticas_posicao[$pratica_atual['pratica']]=$pratica_atual['pratica_criterio_numero'].($pratica_atual['pratica_item_numero']? '.'.$pratica_atual['pratica_item_numero'] : '').' '.$pratica_atual['pratica_marcador_letra'];

				$saida.= '<tr><td colspan=2><b>'.$praticas_vistas[$pratica_atual['pratica']].' - '.$pratica['pratica_nome'].'</b></td></tr>';
				if ($pratica_descricao) $saida.= '<tr><td width="30"></td><td>'.$pratica['pratica_descricao'].'</td></tr>';
				
				
				if ($pratica_5w2h){
					$sql->adTabela('pratica_usuarios');
					$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_usuarios.usuario_id');
					$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
					$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
					$sql->adOnde('pratica_id = '.(int)$pratica_atual['pratica']);
					$participantes = $sql->Lista();
					$sql->limpar();
					$sql->adTabela('pratica_depts');
					$sql->adUnir('depts','depts','depts.dept_id=pratica_depts.dept_id');
					$sql->adCampo('dept_nome');
					$sql->adOnde('pratica_id = '.(int)$pratica_atual['pratica']);
					$lista_depts = $sql->Lista();
					$sql->limpar();
					if ($pratica['pratica_como'] || $pratica['pratica_quanto'] || ($lista_depts && count($lista_depts)) || ($participantes && count($participantes)) || $pratica['pratica_quem'] || $pratica['pratica_oque'] || $pratica['pratica_porque'] || $pratica['pratica_onde'] || $pratica['pratica_quando']) {
						$saida.= '<tr><td width="30"></td><td><table>';
						if ($pratica['pratica_oque']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">O Que:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_oque'].'</td></tr>';
						if ($pratica['pratica_porque']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Por que:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_porque'].'</td></tr>';
						if ($pratica['pratica_onde']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Onde:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_onde'].'</td></tr>';
						if ($pratica['pratica_quando']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quando:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_quando'].'</td></tr>';
						$saida_quem='';
						if ($participantes && count($participantes)) {
								$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
								foreach($participantes as $participante)	$saida_quem.= '<tr><td>'.nome_funcao('','','','',$participante['usuario_id']).'</td></tr>';
								$saida_quem.= '</table>';
								} 
						//if ($saida_quem || $pratica['pratica_quem']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quem:</td>' : '').'<td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td width="100%">'.($pratica['pratica_quem'] ? $pratica['pratica_quem'] : '').'</td></tr><tr><td>'.$saida_quem.'</td></tr></table></td></tr>';
						if ($saida_quem || $pratica['pratica_quem']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quem:</td>' : '').'<td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td width="100%"  style="margin-bottom:0cm; margin-top:0cm;">'.($pratica['pratica_quem'] ? $pratica['pratica_quem'] : '').'</td></tr><tr>'.($pratica_legenda ? '<td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($participantes)>1 ? $config['usuarios'] : $config['usuario'])).':</td><td width="100%">'.$saida_quem.'</td></tr></table></td></tr></table>' : '<td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($participantes)>1 ? $config['usuarios'] : $config['usuarios'])).':</td><td width="100%">'.$saida_quem.'</td></tr></table></td></tr></table>').'</td></tr>';
						$saida_depts='';
						if ($lista_depts && count($lista_depts)) {
								$saida_depts.= '<table cellspacing=0 cellpadding=0 width="100%">';
								foreach($lista_depts as $departamento)	$saida_depts.= '<tr><td>'.$departamento['dept_nome'].'</td></tr>';	
								$saida_depts.= '</table>';
								} 
						if ($saida_depts) $saida.= '<tr>'.($pratica_legenda ? '<td nowrap="nowrap"></td><td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($lista_depts)>1 ? $config['departamentos'] : $config['departamento'])).':</td><td width="100%">'.$saida_depts.'</td></tr></table>' : '<td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($lista_depts)>1 ? $config['departamentos'] : $config['departamento'])).':</td><td width="100%">'.$saida_depts.'</td></tr></table>').'</td></tr>';
						if ($pratica['pratica_como']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Como:</td>' : '').'<td width="100%"  style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_como'].'</td></tr>';
						if ($pratica['pratica_quanto']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quanto:</td>' : '').'<td width="100%"  style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_quanto'].'</td></tr>';
						$saida.= '</table></td></tr>';
						}
					}
				
				if ($pratica_extra && (
				($pratica['pratica_controlada'] && $pratica['pratica_justificativa_controlada']) || 
				($pratica['pratica_refinada'] && $pratica['pratica_justificativa_refinada']) || 
				($pratica['pratica_arte'] && $pratica['pratica_justificativa_arte']) || 
				($pratica['pratica_inovacao'] && $pratica['pratica_justificativa_inovacao'])
				)){
					$saida.= '<tr><td width="30"></td><td><table>';
					
					
					if ($pratica['pratica_controlada'] && $pratica['pratica_justificativa_controlada']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Controle:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_justificativa_controlada'].'</td></tr>';
					if ($pratica['pratica_refinada'] && $pratica['pratica_justificativa_refinada']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Refinada:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_justificativa_refinada'].'</td></tr>';
					if ($pratica['pratica_arte'] && $pratica['pratica_justificativa_arte']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Estado de Arte:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_justificativa_arte'].'</td></tr>';
					if ($pratica['pratica_inovacao'] && $pratica['pratica_justificativa_inovacao']) $saida.= '<tr>'.($pratica_legenda ? '<td align="right" valign="top" nowrap="nowrap">Inovadora:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$pratica['pratica_justificativa_inovacao'].'</td></tr>';
					
					
					
					
					$saida.= '</table></td></tr>';
					}
				
				}
		else {
			if ($mostrado=='nome_numero') $saida.= '<tr><td colspan=2><b><i>'.$praticas_vistas[$pratica_atual['pratica']].' - '.$praticas_nomes[$pratica_atual['pratica']].' ('.$praticas_posicao[$pratica_atual['pratica']].')</i></b></td></tr>';
			else $saida.= '<tr><td colspan=2><b><i>'.ucfirst($config['praticas']).' '.$praticas_vistas[$pratica_atual['pratica']].' ('.$praticas_posicao[$pratica_atual['pratica']].')</i></b></td></tr>';
			}
		}
	if ($praticas && count($praticas)) $saida.= '</table>';	
	return $saida;
	}
	
	





function imprimir_indicador($indicador_marcador_id){
	global $Aplic, $pg_modelo_id, $sql, $ano, $config, $cia_id, $indicador_legenda, $indicadores_vistos, $mostrado, $indicador_descricao, $indicador_5w2h, $indicadores_nomes, $numero_indicadores, $indicador_extra, $indicadores_posicao;	

	//lista de praticas
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_marcador','pratica_marcador','pratica_marcador.pratica_marcador_id=pratica_indicador_nos_marcadores.pratica_marcador_id');
	$sql->esqUnir('pratica_item','pratica_item','pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio','pratica_criterio','pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->adCampo('pratica_criterio_numero, pratica_indicador.pratica_indicador_id, pratica_item_criterio, pratica_item_numero, pratica_marcador_letra');
	$sql->adCampo('pratica_indicador.*, pratica_indicador_requisito.*');
	if ($ano) $sql->adOnde('pratica_indicador_nos_marcadores.ano='.(int)$ano);
	$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	$sql->adOnde('pratica_indicador_nos_marcadores.pratica_marcador_id='.(int)$indicador_marcador_id);
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pg_modelo_id);
	$sql->adOrdem('pratica_indicador.pratica_indicador_id ASC');
	$indicadores=$sql->Lista();
	$sql->limpar();	
	$saida='';
	if ($indicadores&& count($indicadores)) $saida.= '<table cellpadding="2" cellspacing=0 width="100%">';
	foreach($indicadores as $indicador_atual){
		
		if (!isset($indicadores_vistos[$indicador_atual['pratica_indicador_id']])){

				$indicador=$indicador_atual;
				
				$numero_indicadores++;
				$indicadores_vistos[$indicador_atual['pratica_indicador_id']]=$numero_indicadores;
				$indicadores_nomes[$indicador_atual['pratica_indicador_id']]=$indicador['pratica_indicador_nome'];

				$indicadores_posicao[$indicador_atual['pratica_indicador_id']]=$indicador_atual['pratica_criterio_numero'].($indicador_atual['pratica_item_numero']? '.'.$indicador_atual['pratica_item_numero'] : '').' '.$indicador_atual['pratica_marcador_letra'];

				$data_desde = isset($pratica_indicador['pratica_indicador_desde_quando']) ? new CData($pratica_indicador['pratica_indicador_desde_quando']) : new CData();
				$df = '%d/%m/%Y';

				$saida.= '<tr><td colspan=2><b>'.$indicadores_vistos[$indicador_atual['pratica_indicador_id']].' - '.$indicador['pratica_indicador_nome'].'</b></td></tr>';
				$src = '?m=praticas&a=grafico_free&sem_cabecalho=1&ano='.(int)$ano.'&mostrar_valor='.(int)$indicador['pratica_indicador_mostrar_valor'].'&mostrar_titulo='.(int)$indicador['pratica_indicador_mostrar_titulo'].'&media_movel='.(int)$indicador['pratica_indicador_media_movel'].'&agrupar='.(int)$indicador['pratica_indicador_agrupar'].'&tipografico='.(int)$indicador['pratica_indicador_tipografico'].'&pratica_indicador_id='.(int)$indicador['pratica_indicador_id']."&width=750";
				$saida.= "<tr><td width='30'>&nbsp;</td><td><table cellspacing='0' cellpadding='0' align='left'><tr><td><script>document.write('<img src=\"$src\">')</script></td></tr><tr><td align='center'>Indicador ".$indicadores_vistos[$indicador['pratica_indicador_id']]."</td></tr><tr><td>&nbsp;</td></tr><tr><td>&nbsp;</td></tr></table></td></tr>";

				
				if ($indicador_descricao) $saida.= '<tr><td width="30"></td><td>'.$indicador['pratica_indicador_requisito_descricao'].'</td></tr>';
				if ($indicador_5w2h){
					$sql->adTabela('pratica_indicador_usuarios');
					$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=pratica_indicador_usuarios.usuario_id');
					$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
					$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
					$sql->adOnde('pratica_indicador_id ='.(int)$indicador_atual['pratica_indicador_id']);
					$participantes = $sql->Lista();
					$sql->limpar();
					$sql->adTabela('pratica_indicador_depts');
					$sql->adUnir('depts','depts','depts.dept_id=pratica_indicador_depts.dept_id');
					$sql->adCampo('dept_nome');
					$sql->adOnde('pratica_indicador_id ='.(int)$indicador_atual['pratica_indicador_id']);
					$lista_depts = $sql->Lista();
					$sql->limpar();
					if ($indicador['pratica_indicador_requisito_como'] || $indicador['pratica_indicador_requisito_quanto'] || ($lista_depts && count($lista_depts)) || ($participantes && count($participantes)) || $indicador['pratica_indicador_requisito_quem'] || $indicador['pratica_indicador_requisito_oque'] || $indicador['pratica_indicador_requisito_porque'] || $indicador['pratica_indicador_requisito_onde'] || $indicador['pratica_indicador_requisito_quando']) {
						$saida.= '<tr><td width="30"></td><td><table>';
						if ($indicador['pratica_indicador_requisito_oque']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">O Que:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$indicador['pratica_indicador_requisito_oque'].'</td></tr>';
						if ($indicador['pratica_indicador_requisito_porque']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Por que:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$indicador['pratica_indicador_requisito_porque'].'</td></tr>';
						if ($indicador['pratica_indicador_requisito_onde']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Onde:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$indicador['pratica_indicador_requisito_onde'].'</td></tr>';
						if ($indicador['pratica_indicador_requisito_quando']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quando:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$indicador['pratica_indicador_requisito_quando'].'</td></tr>';
						$saida_quem='';
						if ($participantes && count($participantes)) {
								$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
								foreach($participantes as $participante)	$saida_quem.= '<tr><td>'.nome_funcao('','','','',$participante['usuario_id']).'</td></tr>';
								$saida_quem.= '</table>';
								} 
						if ($saida_quem || $indicador['pratica_indicador_requisito_quem']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quem:</td>' : '').'<td width="100%" colspan="2"><table cellspacing=0 cellpadding=0><tr><td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.($indicador['pratica_indicador_requisito_quem'] ? $indicador['pratica_indicador_requisito_quem'] : '').'</td></tr><tr>'.($indicador_legenda ? '<td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($participantes)>1 ? $config['usuarios'] : $config['usuario'])).':</td><td width="100%">'.$saida_quem.'</td></tr></table></td></tr></table>' : '<td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($participantes)>1 ? $config['usuarios'] : $config['usuarios'])).':</td><td width="100%">'.$saida_quem.'</td></tr></table></td></tr></table>').'</td></tr>';
						$saida_depts='';
						if ($lista_depts && count($lista_depts)) {
								$saida_depts.= '<table cellspacing=0 cellpadding=0 width="100%">';
								foreach($lista_depts as $departamento)	$saida_depts.= '<tr><td>'.$departamento['dept_nome'].'</td></tr>';	
								$saida_depts.= '</table>';
								} 
						if ($saida_depts) $saida.= '<tr>'.($indicador_legenda ? '<td nowrap="nowrap"></td><td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($lista_depts)>1 ? $config['departamentos'] : $config['departamento'])).':</td><td width="100%">'.$saida_depts.'</td></tr></table>' : '<td width="100%"><table><tr><td align="right" valign="top" nowrap="nowrap">'.ucfirst((count($lista_depts)>1 ? $config['departamentos'] : $config['departamento'])).':</td><td width="100%">'.$saida_depts.'</td></tr></table>').'</td></tr>';
						if ($indicador['pratica_indicador_requisito_como']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Como:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$indicador['pratica_indicador_requisito_como'].'</td></tr>';
						if ($indicador['pratica_indicador_requisito_quanto']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Quanto:</td>' : '').'<td width="100%" style="margin-bottom:0cm; margin-top:0cm;">'.$indicador['pratica_indicador_requisito_quanto'].'</td></tr>';
						$saida.= '</table></td></tr>';
						}
					}
				
				if ($indicador_extra && ($indicador['pratica_indicador_desde_quando'] || $indicador['pratica_indicador_controle'] || $indicador['pratica_indicador_metodo_aprendizado'] || $indicador['pratica_indicador_requisito_melhorias'] || $indicador['pratica_indicador_arte'] || $indicador['pratica_indicador_inovacao'])){
					$saida.= '<tr><td width="30"></td><td><table>';
					if ($indicador['pratica_indicador_requisito_melhorias']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Melhorias:</td>' : '').'<td width="100%" '.($indicador_legenda ? '' : 'colspan=2').'>'.($indicador['pratica_indicador_requisito_melhorias']).'</td></tr>';
					if ($indicador['pratica_indicador_requisito_referencial']) $saida.= '<tr>'.($indicador_legenda ? '<td align="right" valign="top" nowrap="nowrap">Referencial comparativo:</td>' : '').'<td width="100%" '.($indicador_legenda ? '' : 'colspan=2').'>'.$indicador['pratica_indicador_requisito_referencial'].'</td></tr>';
					if ($obj_indicador->pratica_indicador_valor_referencial!=null) $saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Valor do referencial'.($indicador['pratica_indicador_unidade'] ? ' ('.$indicador['pratica_indicador_unidade'].') ' : '').':</td><td width="100%">'.number_format($obj_indicador->pratica_indicador_valor_referencial, 2, ',', '.').'</td></tr>';
					if ($indicador['pratica_indicador_responsavel']) $saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Responsável:</td><td width="100%">'.nome_funcao('','','','',$indicador['pratica_indicador_responsavel']).'</td></tr>';
					if ($indicador['pratica_indicador_desde_quando']) $saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Desde quando:</td><td width="100%">'.$data_desde->format($df).'</td></tr>';
					$saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Direção do indicador:</td><td width="100%">'. ($indicador['pratica_indicador_sentido'] ? imagem('icones/prioridade+1.gif','Maior Melhor','Quanto maior o valor do indicador, melhor.') : imagem('icones/prioridade-2.gif','Menor Melhor','Quanto menor o valor do indicador, melhor.')).'</td></tr>';
					if ($obj_indicador->pratica_indicador_valor_meta!=null) $saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Meta'.($indicador['pratica_indicador_unidade'] ? ' ('.$indicador['pratica_indicador_unidade'].') ' : '').':</td><td width="100%">'.number_format($obj_indicador->pratica_indicador_valor_meta, 2, ',', '.').'</td></tr>';
					if ($obj_indicador->pratica_indicador_data_meta) $saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Data para meta:</td><td width="100%">'.retorna_data($obj_indicador->pratica_indicador_data_meta, false).'</td></tr>';

					//tendencia
					include_once BASE_DIR.'/modulos/praticas/indicador_simples.class.php';
					$obj_indicador = new Indicador($indicador_atual['pratica_indicador_id']);
					$saida.= '<tr><td align="right" valign="top" nowrap="nowrap">Tendência:</td><td width="100%">'.$obj_indicador->Tendencia().'</td></tr>';	
					$saida.= '</table></td></tr>';
					}
				
				}
		else {
			if ($mostrado=='nome_numero') $saida.= '<tr><td colspan=2><b><i>'.$indicadores_vistos[$indicador_atual['pratica_indicador_id']].' - '.$indicadores_nomes[$indicador_atual['pratica_indicador_id']].' ('.$indicadores_posicao[$indicador_atual['pratica_indicador_id']].')</i></b></td></tr>';
			else $saida.= '<tr><td colspan=2><b><i>Indicador '.$indicadores_vistos[$indicador_atual['pratica_indicador_id']].' ('.$indicadores_posicao[$indicador_atual['pratica_indicador_id']].')</i></b></td></tr>';
			}
		}
	if ($indicadores && count($indicadores)) $saida.= '</table>';	
	return $saida;
	}










?>