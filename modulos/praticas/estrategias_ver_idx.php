<?php 
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

global $estilo_interface, $sql, $perms, $dialogo, $Aplic, $cia_id, $dept_id, $lista_depts, $tab, $lista_cias, $favorito_id, $usuario_id, $pesquisar_texto, $pg_id;


$seta=array('0'=>'seta-cima.gif', '1'=>'seta-baixo.gif');
$impressao=getParam($_REQUEST, 'sem_cabecalho', 0);
$pagina = getParam($_REQUEST, 'pagina', 1);

$xtamanhoPagina = ($impressao || $dialogo ? 90000 : $config['qnt_estrategias']);
$xmin = $xtamanhoPagina * ($pagina - 1); 

$df = '%d/%m/%Y';
$tf = $Aplic->getPref('formatohora');
$ordenar = getParam($_REQUEST, 'ordenar', 'pg_estrategia_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

$sql->adTabela('campo_formulario');
$sql->adCampo('campo_formulario_campo, campo_formulario_ativo');
$sql->adOnde('campo_formulario_tipo = "iniciativas"');
$sql->adOnde('campo_formulario_usuario IS NULL OR campo_formulario_usuario=0');
$exibir = $sql->listaVetorChave('campo_formulario_campo','campo_formulario_ativo');
$sql->limpar();



$sql->adTabela('estrategias');
$sql->adCampo('count(DISTINCT estrategias.pg_estrategia_id)');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'estrategias.pg_estrategia_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept='.(int)$dept_id.' OR estrategias_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept IN ('.$lista_depts.') OR estrategias_depts.dept_id IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
	$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$favorito_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
elseif ($cia_id && !$favorito_id && $lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');

if ($pg_id){
	$sql->esqUnir('plano_gestao_estrategias','plano_gestao_estrategias','plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_estrategias.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('estrategias_usuarios', 'estrategias_usuarios', 'estrategias_usuarios.pg_estrategia_id = estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_usuario = '.(int)$usuario_id.' OR estrategias_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_descricao LIKE \'%'.$pesquisar_texto.'%\'');


if ($tab==0) $sql->adOnde('pg_estrategia_percentagem < 100');
if ($tab==1) $sql->adOnde('pg_estrategia_percentagem = 100');
if ($tab==2) $sql->adOnde('pg_estrategia_ativo = 0');
else $sql->adOnde('pg_estrategia_ativo = 1');
$xtotalregistros = $sql->Resultado();
$sql->limpar();


$sql->adTabela('estrategias');
$sql->adCampo('DISTINCT estrategias.pg_estrategia_id, pg_estrategia_inicio, pg_estrategia_fim, pg_estrategia_percentagem, pg_estrategia_nome, pg_estrategia_descricao, pg_estrategia_usuario, pg_estrategia_cor, pg_estrategia_acesso, pg_estrategia_ano, pg_estrategia_codigo, pg_estrategia_cia');
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'estrategias.pg_estrategia_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id='.$favorito_id);
	}
elseif ($dept_id && !$lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept='.(int)$dept_id.' OR estrategias_depts.dept_id='.(int)$dept_id);
	}
elseif ($lista_depts) {
	$sql->esqUnir('estrategias_depts','estrategias_depts', 'estrategias_depts.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_dept IN ('.$lista_depts.') OR estrategias_depts.dept_id IN ('.$lista_depts.')');
	}
elseif ($Aplic->profissional && ($cia_id || $lista_cias)) {
	$sql->esqUnir('estrategia_cia', 'estrategia_cia', 'estrategias.pg_estrategia_id=estrategia_cia_estrategia');
	$sql->adOnde('pg_estrategia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).') OR estrategia_cia_cia IN ('.($lista_cias ? $lista_cias  : $cia_id).')');
	}
elseif ($cia_id && !$favorito_id && !$lista_cias) $sql->adOnde('pg_estrategia_cia='.(int)$cia_id);
elseif ($cia_id && !$favorito_id && $lista_cias) $sql->adOnde('pg_estrategia_cia IN ('.$lista_cias.')');

if ($pesquisar_texto) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_codigo LIKE \'%'.$pesquisar_texto.'%\'');
if ($tab==0) $sql->adOnde('pg_estrategia_percentagem < 100');
if ($tab==1) $sql->adOnde('pg_estrategia_percentagem = 100');
if ($tab==2) $sql->adOnde('pg_estrategia_ativo = 0');
else $sql->adOnde('pg_estrategia_ativo = 1');
if ($pg_id){
	$sql->esqUnir('plano_gestao_estrategias','plano_gestao_estrategias','plano_gestao_estrategias.pg_estrategia_id=estrategias.pg_estrategia_id');
	$sql->esqUnir('plano_gestao','plano_gestao','plano_gestao.pg_id=plano_gestao_estrategias.pg_id');
	$sql->adOnde('plano_gestao.pg_id='.(int)$pg_id);
	}
if ($usuario_id) {
	$sql->esqUnir('estrategias_usuarios', 'estrategias_usuarios', 'estrategias_usuarios.pg_estrategia_id = estrategias.pg_estrategia_id');
	$sql->adOnde('pg_estrategia_usuario = '.(int)$usuario_id.' OR estrategias_usuarios.usuario_id='.(int)$usuario_id);
	}
if ($pesquisar_texto) $sql->adOnde('pg_estrategia_nome LIKE \'%'.$pesquisar_texto.'%\' OR pg_estrategia_descricao LIKE \'%'.$pesquisar_texto.'%\'');
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$sql->setLimite($xmin, $xtamanhoPagina);
$estrategias=$sql->Lista();
$sql->limpar();

$xtotal_paginas = ($xtotalregistros > $xtamanhoPagina) ? ceil($xtotalregistros / $xtamanhoPagina) : 0;
if ($xtotal_paginas > 1) mostrarBarraNav($xtotalregistros, $xtamanhoPagina, $xtotal_paginas, $pagina, 'Estrategia', 'Estrat�gias','','&ordenar='.$ordenar.'&ordem='.$ordem,($estilo_interface=='classico' ? 'a6a6a6' : '006fc2'));

echo '<table width="'.($dialogo ? '780' : '100%').'" border=0 cellpadding="2" cellspacing=0 class="tbl1">';
echo '<tr>';
if (!$dialogo) echo '<th nowrap="nowrap">&nbsp;</th>';
if ($exibir['cor']) echo '<th width=16><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_cor&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_cor' ? imagem('icones/'.$seta[$ordem]) : '').dica('Cor', 'Neste campo fica a cor de identifica��o d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Cor'.dicaF().'</a></th>';
echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_nome&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_nome' ? imagem('icones/'.$seta[$ordem]) : '').dica('Nome', 'Neste campo fica um nome para identifica��o d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Nome'.dicaF().'</a></th>';
if ($exibir['descricao']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_descricao&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_descricao' ? imagem('icones/'.$seta[$ordem]) : '').dica('Descri��o', 'Neste campo fica a descri��o d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Descri��o'.dicaF().'</a></th>';
if ($exibir['responsavel']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_usuario&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_usuario' ? imagem('icones/'.$seta[$ordem]) : '').dica('Respons�vel', 'O '.$config['usuario'].' respons�vel pel'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Respons�vel'.dicaF().'</a></th>';
if ($exibir['designados']) echo '<th nowrap="nowrap">'.dica('Designados', 'Neste campo fica os designados para '.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Designados'.dicaF().'</th>';
if ($exibir['dept']) echo '<th nowrap="nowrap">'.dica(ucfirst($config['departamentos']), 'Neste campo fica '.$config['genero_dept'].'s '.$config['departamentos'].' envolvid'.$config['genero_dept'].'s n'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').$config['dept'].dicaF().'</th>';
if ($exibir['inicio']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_inicio&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_inicio' ? imagem('icones/'.$seta[$ordem]) : '').dica('In�cio', 'A data de �nicio d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'In�cio'.dicaF().'</a></th>';
if ($exibir['fim']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_fim&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_fim' ? imagem('icones/'.$seta[$ordem]) : '').dica('T�rmino', 'A data de t�rmino d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'T�rmino'.dicaF().'</a></th>';
if ($exibir['percentagem']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_percentagem&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_percentagem' ? imagem('icones/'.$seta[$ordem]) : '').dica('Percentagem', 'A percentagem executada n'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'%'.dicaF().'</a></th>';
if ($exibir['cia_id']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_cia&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_cia' ? imagem('icones/'.$seta[$ordem]) : '').dica(ucfirst($config['organizacao']), 'As '.$config['organizacoes'].' d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').$config['organizacao'].dicaF().'</a></th>';
if ($exibir['ano']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_ano&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_ano' ? imagem('icones/'.$seta[$ordem]) : '').dica('Ano', 'O ano base dos'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'Ano'.dicaF().'</a></th>';
if ($exibir['codigo']) echo '<th nowrap="nowrap"><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').'&ordenar=pg_estrategia_codigo&ordem='.($ordem ? '0' : '1').'\');">'.($ordenar=='pg_estrategia_codigo' ? imagem('icones/'.$seta[$ordem]) : '').dica('C�digo', 'Os c�digos d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'C�digo'.dicaF().'</a></th>';

echo '</tr>';

$agora =date('Y-m-d');

$qnt=0;

foreach ($estrategias as $linha) {
	if (permiteAcessarEstrategia($linha['pg_estrategia_acesso'],$linha['pg_estrategia_id'])){
		$editar=permiteEditarEstrategia($linha['pg_estrategia_acesso'],$linha['pg_estrategia_id']);
		$estilo ='';
		if($linha['pg_estrategia_inicio'] && $linha['pg_estrategia_fim']){
			if ($agora < $linha['pg_estrategia_inicio'] && $linha['pg_estrategia_percentagem'] < 100) $estilo = 'style="background-color:#ffffff"';
			if ($agora > $linha['pg_estrategia_inicio'] && $agora < $linha['pg_estrategia_fim'] && $linha['pg_estrategia_percentagem'] > 0 && $linha['pg_estrategia_percentagem'] < 100 ) $estilo = 'style="background-color:#e6eedd"';
			if ($agora > $linha['pg_estrategia_inicio'] && $agora < $linha['pg_estrategia_fim'] && $linha['pg_estrategia_percentagem'] == 0) $estilo = 'style="background-color:#ffeebb"';
			if ($agora > $linha['pg_estrategia_fim'] && $linha['pg_estrategia_percentagem'] < 100) $estilo = 'style="background-color:#cc6666"';
			elseif ($linha['pg_estrategia_percentagem'] == 100) $estilo = 'style="background-color:#aaddaa"';
			}

		$qnt++;
		echo '<tr>';
		if (!$dialogo) echo '<td nowrap="nowrap" width="16">'.($editar ? dica('Editar '.ucfirst($config['acao']), 'Clique neste �cone '.imagem('icones/editar.gif').' para editar '.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a=estrategia_editar&pg_estrategia_id='.$linha['pg_estrategia_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF() : '&nbsp;').'</td>';
		if ($exibir['cor']) echo '<td id="ignore_td_" width="15" align="right" style="background-color:#'.$linha['pg_estrategia_cor'].'">&nbsp;&nbsp;</td>';
		echo '<td '.$estilo.' >'.link_estrategia($linha['pg_estrategia_id'],true).'</td>';
		if ($exibir['descricao']) echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_descricao'] ? $linha['pg_estrategia_descricao']: '&nbsp;').'</td>';
		if ($exibir['responsavel']) echo '<td nowrap="nowrap">'.link_usuario($linha['pg_estrategia_usuario'],'','','esquerda').'</td>';
		
		if ($exibir['designados']){ 
			$sql->adTabela('estrategias_usuarios');
			$sql->adCampo('usuario_id');
			$sql->adOnde('pg_estrategia_id = '.(int)$linha['pg_estrategia_id']);
			$participantes = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_quem='';
			if ($participantes && count($participantes)) {
					$saida_quem.= link_usuario($participantes[0], '','','esquerda');
					$qnt_participantes=count($participantes);
					if ($qnt_participantes > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_participantes; $i < $i_cmp; $i++) $lista.=link_usuario($participantes[$i], '','','esquerda').'<br>';		
							$saida_quem.= dica('Outros Participantes', 'Clique para visualizar os demais participantes.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'participantes_'.$linha['pg_estrategia_id'].'\');">(+'.($qnt_participantes - 1).')</a>'.dicaF(). '<span style="display: none" id="participantes_'.$linha['pg_estrategia_id'].'"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left" nowrap="nowrap">'.($saida_quem ? $saida_quem : '&nbsp;').'</td>';
			}
			
		if ($exibir['dept']){ 	
			$sql->adTabela('estrategias_depts');
			$sql->adCampo('dept_id');
			$sql->adOnde('pg_estrategia_id = '.(int)$linha['pg_estrategia_id']);
			$depts = $sql->carregarColuna();
			$sql->limpar();
			
			$saida_dept='';
			if ($depts && count($depts)) {
					$saida_dept.= link_secao($depts[0]);
					$qnt_depts=count($depts);
					if ($qnt_depts > 1) {		
							$lista='';
							for ($i = 1, $i_cmp = $qnt_depts; $i < $i_cmp; $i++) $lista.=link_secao($depts[$i]).'<br>';		
							$saida_dept.= dica('Outros Participantes', 'Clique para visualizar os demais depts.').'<a href="javascript: void(0);" onclick="expandir_colapsar(\'depts\');">(+'.($qnt_depts - 1).')</a>'.dicaF(). '<span style="display: none" id="depts"><br>'.$lista.'</span>';
							}
					} 
			echo '<td align="left" nowrap="nowrap">'.($saida_dept ? $saida_dept : '&nbsp;').'</td>';
			}
			
		if ($exibir['inicio'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_inicio'] ? retorna_data($linha['pg_estrategia_inicio'], false): '&nbsp;').'</td>';
		if ($exibir['fim'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_fim'] ? retorna_data($linha['pg_estrategia_fim'], false): '&nbsp;').'</td>';
		if ($exibir['percentagem'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_percentagem'] ? number_format($linha['pg_estrategia_percentagem'], 1, ',', '.') : '&nbsp;').'</td>';
		if ($exibir['cia_id'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_cia'] ? link_cia($linha['pg_estrategia_cia']): '&nbsp;').'</td>';
		if ($exibir['ano'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_ano'] ? $linha['pg_estrategia_ano'] : '&nbsp;').'</td>';
		if ($exibir['codigo'])echo '<td style="margin-bottom:0cm; margin-top:0cm;">'.($linha['pg_estrategia_codigo'] ? $linha['pg_estrategia_codigo'] : '&nbsp;').'</td>';
		
		echo '</tr>';
		}
	}
	
	
if (!count($estrategias)) echo '<tr><td colspan=20><p>Nenhum'.($config['genero_iniciativa']=='a' ? 'a' : '').' '.$config['iniciativa'].' encontrad'.$config['genero_iniciativa'].'.</p></td></tr>';
elseif(count($estrategias) && !$qnt) echo '<tr><td colspan="20"><p>N�o teve permiss�o de visualizar qualquer d'.$config['genero_iniciativa'].'s '.$config['iniciativas'].'.</p></td></tr>';	
	
echo '</table>';


echo '<table border=0 cellpadding=2 cellspacing=2 '.($dialogo ? '' : 'class="std"').' width="'.($dialogo ? '780' : '100%').'"><tr>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffffff;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Iniciativa Prevista', 'Iniciativa prevista � quando a data de �nicio da mesma ainda n�o passou.').'&nbsp;'.'Iniciativa para o futuro'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #e6eedd;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Iniciativa Iniciada e Dentro do Prazo', 'Iniciativa iniciada e dentro do prazo � quando a data de �nicio da mesma j� ocorreu, e a mesma j� est� acima de 0% executada, entretanto ainda n�o se chegou na data de t�rmino.').'&nbsp;Iniciada e dentro do prazo'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #ffeebb;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Iniciativa que Deveria ter Iniciada', 'Iniciativa deveria ter iniciada � quando a data de �nicio da mesma j� ocorreu, entretanto ainda se encontra em 0% executada.').'&nbsp;Deveria ter iniciada'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #cc6666;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Iniciativa em Atraso', 'Iniciativa em atraso � quando a data de t�rmino da mesma j� ocorreu, entretanto ainda n�o se encontra em 100% executada.').'&nbsp;Em atraso'.dicaF().'</td><td>&nbsp;&nbsp;&nbsp;&nbsp;</td>';
echo '<td nowrap="nowrap" style="border-style:solid;border-width:1px; background: #aaddaa;">&nbsp; &nbsp;</td><td nowrap="nowrap">'.dica('Iniciativa Terminada', 'Iniciativa terminada � quando est� 100% executada.').'&nbsp;Terminada'.dicaF().'</td>';
echo '<td width="100%">&nbsp;</td>';
echo '</tr></table>';


if ($dialogo) echo '<script language=Javascript>self.print();</script>';

?>
<script language="javascript">
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
</script>	