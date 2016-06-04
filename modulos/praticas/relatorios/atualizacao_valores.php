<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $dialogo;
$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');

if (isset($_REQUEST['usuario_id'])) $Aplic->setEstado('usuario_id', getParam($_REQUEST, 'usuario_id', null));
$usuario_id = ($Aplic->getEstado('usuario_id') !== null ? $Aplic->getEstado('usuario_id') : 0);

if (isset($_REQUEST['cia_id'])) $Aplic->setEstado('cia_id', getParam($_REQUEST, 'cia_id', null));
$cia_id = $Aplic->getEstado('cia_id') !== null ? $Aplic->getEstado('cia_id') : $Aplic->usuario_cia;



$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_indicador_id');
$ordem = getParam($_REQUEST, 'ordem', '0');
$periodo=array('dia' => 'Dia', 'semana' => 'Semana', 'mes' => 'M�s','bimestre' => 'Bimestre','trimestre' => 'Trimestre','quadrimestre' => 'Quadrimestre','semestre' => 'Semestre', 'ano' => 'Ano', 'nenhum' => 'Nenhum agrupamento');

$sql = new BDConsulta;
$sql->adTabela('pratica_indicador');
$sql->esqUnir('pratica_indicador_valor','iv','iv.pratica_indicador_valor_indicador=pratica_indicador.pratica_indicador_id');
$sql->adCampo('pratica_indicador_acesso, pratica_indicador_cor, pratica_indicador_agrupar, pratica_indicador_responsavel, pratica_indicador_id, MAX(pratica_indicador_valor_data) AS ultimo, pratica_indicador_valor_responsavel');
$sql->adOnde('pratica_indicador_composicao=0');
if($usuario_id)$sql->adOnde('pratica_indicador_responsavel='.(int)$usuario_id);
if($cia_id)$sql->adOnde('pratica_indicador_cia='.(int)$cia_id);


$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->adGrupo('pratica_indicador.pratica_indicador_id');

$valores = $sql->Lista();
$sql->limpar();

if (!$dialogo){
	echo '<table width="100%"><tr><td width="22">&nbsp;</td><td align="center"><font size="4"><center>Atualiza��o dos Indicadores</center></font></td><td width="22"><a href="javascript: void(0);" onclick ="frm_filtro.target=\'popup\'; frm_filtro.dialogo.value=1; frm_filtro.submit();">'.imagem('imprimir_p.png', 'Imprimir o Relat�rio', 'Clique neste �cone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poder� imprimir o relat�rio a partir do navegador Web.').'</a></td></tr></table>';
	echo estiloTopoCaixa();
	}
else echo '<table '.($dialogo ? 'width="1024"' : 'width="100%"').'><tr><td align="center"><font size="4"><center>Atualiza��o dos Indicadores</center></font></td></tr></table>';	
echo '<table cellpadding=0 cellspacing=0 '.($dialogo ? 'width="1024"' : 'width="100%"').' class="tbl1">';
echo '<tr><th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&relatorio_tipo=atualizacao_valores&ordenar=pratica_indicador_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor do Indicador', 'Neste campo fica a cor de identifica��o do indicador.').'Cor'.dicaF().'</th><th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&relatorio_tipo=atualizacao_valores&ordenar=pratica_indicador_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Indicador','Nome do indicador').'Indicador'.dicaF().'</th><th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&relatorio_tipo=atualizacao_valores&ordenar=pratica_indicador_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel','Nome do respons�vel pelo indicador').'Respons�vel'.dicaF().'</th><th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&relatorio_tipo=atualizacao_valores&ordenar=ultimo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='ultimo' ? imagem('icones/'.$seta[$ordem]) : '').dica('Data','�ltima data em que o indicador foi atualizado.').'Data'.dicaF().'</th><th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&relatorio_tipo=atualizacao_valores&ordenar=pratica_indicador_agrupar&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_agrupar' ? imagem('icones/'.$seta[$ordem]) : '').dica('Periodicidade','Periodicidade em que o indicador � observado.').'Per�odo'.dicaF().'</th><th nowrap="nowrap"><a class="hdr"href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&relatorio_tipo=atualizacao_valores&ordenar=pratica_indicador_valor_responsavel&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pratica_indicador_valor_responsavel' ? imagem('icones/'.$seta[$ordem]) : '').dica('Atualizador','Nome d'.$config['genero_usuario'].' '.$config['usuario'].' que atualizou por �ltimo o indicador').'Atualizador'.dicaF().'</th></tr>';
foreach($valores AS $linha)	{
	if (permiteAcessarIndicador($linha['pratica_indicador_acesso'], $linha['pratica_indicador_id'])) echo '<tr><td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pratica_indicador_cor'].'"><font color="'.melhorCor($linha['pratica_indicador_cor']).'">&nbsp;&nbsp;</font></td><td>'.link_indicador($linha['pratica_indicador_id']).'</td><td>'.link_usuario($linha['pratica_indicador_responsavel'],'','',(!$dialogo ? 'esquerda' : '')).'</td><td>'.($linha['ultimo'] ? retorna_data($linha['ultimo'], false): 'sem valor').'</td><td>'.$periodo[$linha['pratica_indicador_agrupar']].'</td><td>'.link_usuario($linha['pratica_indicador_valor_responsavel'],'','',(!$dialogo ? 'esquerda' : '')).'</td></tr>';
	
	}
if (!count($valores) || !$valores) echo '<tr><td colspan=20>N�o foram encontrados indicadores</td></tr>';
echo '</table>';
if (!$dialogo) echo estiloFundoCaixa();	
else echo '<script>self.print();</script>';	

?>