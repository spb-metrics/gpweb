<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $estilo_interface, $perms, $Aplic, $podeEditar, $praticas, $ano, $cia_id, $tab, $praticas_criterios, $ordem, $ordenar, $dialogo, $pratica_modelo_id;

$sql = new BDConsulta();
//campos utilizados na regua específica	
$sql->adTabela('pratica_regra_campo');
$sql->adCampo('pratica_regra_campo_nome');
$sql->adOnde('pratica_regra_campo_modelo_id='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_regra_campo_resultado=0');
$vetor_campos=$sql->carregarColuna();
$sql->limpar();

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = \'pratica\'');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();


$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$tab = $Aplic->getEstado('PraticaIdxTab') !== null ? $Aplic->getEstado('PraticaIdxTab') : 0;
$pagina = getParam($_REQUEST, 'pagina', 1);
$xpg_tamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_praticas']);
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 
$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$xpg_totalregistros = ($praticas ? count($praticas) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 0;
if ($xpg_total_paginas > 1) mostrarBarraNav($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $config['pratica'], $config['praticas'],'','',($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));
echo '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$impressao && !$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Neste campo fica a cor de identificação d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Cor'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome d'.$config['genero_pratica'].' '.ucfirst($config['pratica']), 'Neste campo fica um nome para identificação d'.$config['genero_pratica'].' '.$config['pratica'].'.').'Nome d'.$config['genero_pratica'].' '.ucfirst($config['pratica']).dicaF().'</a></th>';
if ($exibir['pratica_descricao']) echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pratica_oque&&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_oque' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descrição', 'Caso exista um link para página ou arquivo na rede que faça referência ao registro.').'Descrição'.dicaF().'</a></th>';
echo '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pratica_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Responsável', 'O '.$config['usuario'].' responsável pel'.$config['genero_pratica'].' '.$config['pratica'].'.').'Responsável'.dicaF().'</a></th>';
echo '<th>'.dica('Quantidade de Marcadores', 'A quantidade de marcadores relacionados à pauta selecionada nest'.($config['genero_pratica']=='a' ? 'a' : 'e').' '.$config['pratica'].'.').'Qnt'.dicaF().'</th>';
echo '<th>'.dica('Oportunidades de Inovação e Melhoria', 'Lacunas relativas a requisitos a serem atingidos na pauta selecionada por est'.($config['genero_pratica']=='a' ? 'a' : 'e').' '.$config['pratica'].'.').'OIM'.dicaF().'</th>';
echo '</tr>';
$fp = -1;
$id = 0;
$qnt=0;
for ($i = ($pagina - 1) * $xpg_tamanhoPagina; $i < $pagina * $xpg_tamanhoPagina && $i < $xpg_totalregistros; $i++) {
	$linha = $praticas[$i];
	
	if (permiteAcessarPratica($linha['pratica_acesso'],$linha['pratica_id'])){
		$qnt++;
		$editar=($podeEditar && permiteEditarPratica($linha['pratica_acesso'],$linha['pratica_id']));
		echo '<tr>';
		if (!$impressao  && !$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['pratica']), 'Clique neste ícone '.imagem('icones/editar.gif').' para editar '.$config['genero_pratica'].' '.$config['pratica'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=pratica_editar&pratica_id='.$linha['pratica_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pratica_cor'].'"><font color="'.melhorCor($linha['pratica_cor']).'">&nbsp;&nbsp;</font></td>';
		
		echo '<td>'.link_pratica($linha['pratica_id'], '','','','','',true, $ano).'</td>';
		
		if ($exibir['pratica_descricao']) echo '<td>'.($linha['pratica_descricao'] ? $linha['pratica_descricao'] : '&nbsp;').'</td>';
		echo '<td>'.link_usuario($linha['pratica_responsavel'],'','','esquerda').'</td>';
		
		$sql->adTabela('pratica_nos_marcadores');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_marcadores.pratica=praticas.pratica_id');
		$sql->adCampo('COUNT(marcador)');
		$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('praticas.pratica_id='.(int)$linha['pratica_id']);
		$sql->adOnde('pratica_nos_marcadores.ano='.(int)$ano);
		$qnt_marcador=$sql->Resultado();
		$sql->limpar();
		
		echo '<td nowrap="nowrap" align=center>'.(int)$qnt_marcador.'</td>';
		
		$sql->adTabela('pratica_nos_verbos');
		$sql->esqUnir('pratica_verbo', 'pratica_verbo', 'pratica_verbo_id=verbo');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador_id=pratica_verbo_marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item_id=pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio_id=pratica_item_criterio');
		$sql->esqUnir('praticas', 'praticas', 'pratica_nos_verbos.pratica=praticas.pratica_id');
		$sql->adCampo('COUNT(verbo)');
		$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('praticas.pratica_id='.(int)$linha['pratica_id']);
		$sql->adOnde('pratica_nos_verbos.ano='.(int)$ano);
		$adequacao=$sql->Resultado();
		$sql->limpar();
		
		$linha['pratica_adequada']=(int)$adequacao;
		
		$oim=0;	
		foreach($vetor_campos as $campo) if (isset($linha[$campo]) && !$linha[$campo]) {
			$oim++;
			}
		if ($oim){
			//verifica se já tem plano de ação
			$sql->adTabela('plano_acao');
			$sql->adCampo('plano_acao_id, plano_acao_acesso');
			$sql->adOnde('plano_acao_pratica='.$linha['pratica_id']);
			$sql->adOnde('plano_acao_ativo=1');
			$plano_acao=$sql->linha();
			$sql->limpar();
	
			if ($plano_acao['plano_acao_id'] && permiteAcessarPlanoAcao($plano_acao['plano_acao_acesso'], $plano_acao['plano_acao_id']))  echo '<td style="background-color: #ffdddd" align=center><a href="javascript:void(0);" onclick="url_passar(0, \'m=praticas&a=plano_acao_ver&plano_acao_id='.$plano_acao['plano_acao_id'].'\');">'.dica('Plano de Ação','Clique para verificar '.$config['genero_acao'].' '.$config['acao'].' em andamento para melhorar '.$config['genero_pratica'].' '.$config['pratica'].'.').$oim.'</a></td>';
			elseif ($plano_acao['plano_acao_id'])  echo '<td style="background-color: #ffdddd" align=center>'.dica('Plano de Ação',ucfirst($config['genero_pratica']).' '.$config['pratica'].' contém um plano de ação, entretanto não tem permissão de visualizar.').$oim.'</td>';
			elseif ($editar) echo '<td style="background-color: #ff5050" align=center><a href="javascript:void(0);" onclick="if(confirm(\'Tem certeza que deseja criar um plano de ação?\')) url_passar(0, \'m=praticas&a=plano_acao_editar&plano_acao_pratica='.$linha['pratica_id'].'\');">'.dica('Criar Plano de Ação','Clique para criar um plano de ação para melhorar '.$config['genero_pratica'].' '.$config['pratica'].'.').$oim.'</a></td>';
			else echo '<td style="background-color: #ff5050" align=center>'.dica('OIM',ucfirst($config['genero_pratica']).' '.$config['pratica'].' pode possibilita oportunidade de inovação e melhoria.').$oim.'</td>';
			}
		else echo '<td>&nbsp;</td>';
		
		echo '</tr>';
		}
	}
if (!count($praticas)) echo '<tr><td colspan="8"><p>Nenh'.($config['genero_pratica']=='a' ? 'uma ': 'um ').$config['pratica'].' encontrad'.$config['genero_pratica'].'.</p></td></tr>';
elseif(count($praticas) && !$qnt) echo '<tr><td colspan="20"><p>Não teve permissão de visualizar qualquer d'.$config['genero_pratica'].'s '.$config['praticas'].'.</p></td></tr>';
echo '</table>';

if ($impressao || $dialogo) echo '<script language=Javascript>self.print();</script>';
?>