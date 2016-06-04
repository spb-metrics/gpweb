<?php 
/* 
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $perms, $arquivo_usuario, $pastas_permitidas, $pastas_negadas, $tab, $arquivo_pasta_id, $m, $a,  $mostrarProjeto, $arquivo_pasta_id, $cia_id, $dept_id,
	$tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$pg_objetivo_estrategico_id, 
	$pg_fator_critico_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$arquivo_usuario,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;	


$pagina = getParam($_REQUEST, 'pagina', 1);

if (!isset($cia_id)) $cia_id = getParam($_REQUEST, 'cia_id', null);

$xpg_tamanhoPagina = $config['qnt_arquivos'];
$xpg_min = $xpg_tamanhoPagina * ($pagina - 1); 

$arquivo_tipos = getSisValor('TipoArquivo');

$sql = new BDConsulta();
$sql->adTabela('arquivos');
$sql->adCampo('count(DISTINCT arquivos.arquivo_id)');
$sql->esqUnir('usuarios', 'u', 'u.usuario_id = arquivo_usuario');
$sql->esqUnir('contatos', 'contatos', 'u.usuario_contato = contatos.contato_id');
if ($Aplic->profissional){
	$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivos.arquivo_id');
	if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa='.(int)$tarefa_id);
	elseif ($projeto_id) $sql->adOnde('arquivo_gestao_projeto='.(int)$projeto_id);
	elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva='.(int)$pg_perspectiva_id);
	elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema='.(int)$tema_id);
	elseif ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
	elseif ($pg_fator_critico_id) $sql->adOnde('arquivo_gestao_fator='.(int)$pg_fator_critico_id);
	elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia='.(int)$pg_estrategia_id);
	elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta='.(int)$pg_meta_id);
	elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica='.(int)$pratica_id);
	elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador='.(int)$pratica_indicador_id);
	elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao='.(int)$plano_acao_id);
	elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas='.(int)$canvas_id);
	elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco='.(int)$risco_id);
	elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta='.(int)$risco_resposta_id);
	elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario='.(int)$calendario_id);
	elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento='.(int)$monitoramento_id);
	elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata='.(int)$ata_id);
	elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot='.(int)$swot_id);
	elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo='.(int)$operativo_id);
	elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento='.(int)$instrumento_id);
	elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso='.(int)$recurso_id);
	elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema='.(int)$problema_id);
	elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda='.(int)$demanda_id);
	elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa='.(int)$programa_id);
	elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao='.(int)$licao_id);
	elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento='.(int)$evento_id);
	elseif ($link_id) $sql->adOnde('arquivo_gestao_link='.(int)$link_id);
	elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao='.(int)$avaliacao_id);
	elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn='.(int)$tgn_id);
	elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm='.(int)$brainstorm_id);
	elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut='.(int)$gut_id);
	elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito='.(int)$causa_efeito_id);
	elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum='.(int)$forum_id);
	elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist='.(int)$checklist_id);
	elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda='.(int)$agenda_id);
	elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento='.(int)$agrupamento_id);
	elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador='.(int)$patrocinador_id);
	elseif ($template_id) $sql->adOnde('arquivo_gestao_template='.(int)$template_id);	
	elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel='.(int)$painel_id);
	elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro='.(int)$painel_odometro_id);
	elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao='.(int)$painel_composicao_id);
	elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr='.(int)$tr_id);
	elseif ($me_id) $sql->adOnde('arquivo_gestao_me='.(int)$me_id);	
	else if ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	else $sql->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
	}
else {
	if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
	else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
	else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
	else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
	else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
	else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
	else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
	else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_objetivo = '.(int)$pg_objetivo_estrategico_id);
	else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
	else if ($pg_fator_critico_id) $sql->adOnde('arquivo_fator = '.(int)$pg_fator_critico_id);
	else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
	else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
	else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
	else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
	else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
	else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
	else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
	else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
	}
$resultados=$sql->Resultado();
$sql->Limpar();	



$xpg_totalregistros = ($resultados ? count($resultados) : 0);
$xpg_total_paginas = ($xpg_totalregistros > $xpg_tamanhoPagina) ? ceil($xpg_totalregistros / $xpg_tamanhoPagina) : 1;
$editar=$podeEditar = $Aplic->checarModulo('arquivos', 'editar');
$objPasta = new CPastaArquivo();
if ($arquivo_pasta_id > 0) {
	$objPasta->load($arquivo_pasta_id);
	$msg = '';
	$permiteEditar=permiteEditarPasta($objPasta->arquivo_pasta_acesso, $objPasta->arquivo_pasta_id);
	$editar=($podeEditar&&$permiteEditar);
	echo '<table border=0 cellpadding=0 cellspacing=1 width="100%"><tr><td><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id=0\');">'.imagem('icones/inicio.png', 'Voltar para a Raíz', 'Clique neste ícone '.imagem('icones/inicio.png').' para voltar à raíz do diretório.').'</a>';
	echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$objPasta->arquivo_pasta_superior.'\');">'.imagem('icones/voltar.png','Pasta Superior', 'Clique neste ícone '.imagem('icones/voltar.png').' para volta à pasta superior.').'</a>';
	if ($editar) echo '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&tab='.$tab.'&a=editar_pasta&arquivo_pasta_id='.$objPasta->arquivo_pasta_id.'\');" >'.imagem('icones/editar.gif','Editar Pasta', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar a pasta.').'</a>';
	echo '</td></tr></table>';
	}

echo '<table width="100%" class="std"><tr><td>';	
echo '<div id="pasta-lista">';
echo '<span class="pasta-nome-atual">'.imagem('icones/pasta.png').(isset($objPasta->arquivo_pasta_nome) && $objPasta->arquivo_pasta_nome ? $objPasta->arquivo_pasta_nome : 'Raiz').'</span>';
if (isset($objPasta->arquivo_pasta_descricao) && $objPasta->arquivo_pasta_descricao) echo '<p>'.$objPasta->arquivo_pasta_descricao.'</p>';
if (contarArquivos($arquivo_pasta_id) > 0) echo mostrarArquivos($arquivo_pasta_id);
elseif ($arquivo_pasta_id != 0) echo 'nenhum arquivo';
echo getPastas($arquivo_pasta_id);
echo '</div>';
echo '</td></tr>';	
echo '</table>';


function getPastas($superior, $nivel = 0) {
	global $Aplic, $perms, $pastas_permitidas, $pastas_negadas, $tab, $m, $a, $cia_id, $dept_id, $arquivo_usuario, $arquivo_tipos,
	$tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$pg_objetivo_estrategico_id, 
	$pg_fator_critico_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$arquivo_usuario,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;
	
	
	$podeEditar = $Aplic->checarModulo('arquivos', 'editar');
	$sql = new BDConsulta();
	$sql->adTabela('arquivo_pasta');
	$sql->adCampo('arquivo_pasta.*');
	
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_pasta_gestao','arquivo_pasta_gestao','arquivo_pasta_gestao_pasta = arquivo_pasta.arquivo_pasta_id');
		if ($tarefa_id) $sql->adOnde('arquivo_pasta_gestao_tarefa='.(int)$tarefa_id);
		elseif ($projeto_id) $sql->adOnde('arquivo_pasta_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_gestao_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($tema_id) $sql->adOnde('arquivo_pasta_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_pasta_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id) $sql->adOnde('arquivo_pasta_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id) $sql->adOnde('arquivo_pasta_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id) $sql->adOnde('arquivo_pasta_gestao_pratica='.(int)$pratica_id);
		elseif ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_gestao_indicador='.(int)$pratica_indicador_id);
		elseif ($plano_acao_id) $sql->adOnde('arquivo_pasta_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id) $sql->adOnde('arquivo_pasta_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id) $sql->adOnde('arquivo_pasta_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id) $sql->adOnde('arquivo_pasta_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id) $sql->adOnde('arquivo_pasta_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id) $sql->adOnde('arquivo_pasta_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id) $sql->adOnde('arquivo_pasta_gestao_ata='.(int)$ata_id);
		elseif ($swot_id) $sql->adOnde('arquivo_pasta_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id) $sql->adOnde('arquivo_pasta_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id) $sql->adOnde('arquivo_pasta_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id) $sql->adOnde('arquivo_pasta_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id) $sql->adOnde('arquivo_pasta_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id) $sql->adOnde('arquivo_pasta_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id) $sql->adOnde('arquivo_pasta_gestao_programa='.(int)$programa_id);
		elseif ($licao_id) $sql->adOnde('arquivo_pasta_gestao_licao='.(int)$licao_id);
		elseif ($evento_id) $sql->adOnde('arquivo_pasta_gestao_evento='.(int)$evento_id);
		elseif ($link_id) $sql->adOnde('arquivo_pasta_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id) $sql->adOnde('arquivo_pasta_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id) $sql->adOnde('arquivo_pasta_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id) $sql->adOnde('arquivo_pasta_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id) $sql->adOnde('arquivo_pasta_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id) $sql->adOnde('arquivo_pasta_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($forum_id) $sql->adOnde('arquivo_pasta_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id) $sql->adOnde('arquivo_pasta_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id) $sql->adOnde('arquivo_pasta_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id) $sql->adOnde('arquivo_pasta_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id) $sql->adOnde('arquivo_pasta_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id) $sql->adOnde('arquivo_pasta_gestao_template='.(int)$template_id);	
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);
		elseif ($painel_id) $sql->adOnde('arquivo_pasta_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id) $sql->adOnde('arquivo_pasta_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id) $sql->adOnde('arquivo_pasta_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id) $sql->adOnde('arquivo_pasta_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('arquivo_pasta_gestao_me='.(int)$me_id);
		
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_pasta_gestao_usuario=0 OR arquivo_pasta_gestao_usuario IS NULL OR arquivo_pasta_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $sql->adOnde('arquivo_pasta_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('arquivo_pasta_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $sql->adOnde('arquivo_pasta_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $sql->adOnde('arquivo_pasta_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $sql->adOnde('arquivo_pasta_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_pasta_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $sql->adOnde('arquivo_pasta_tema = '.(int)$tema_id);
		else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_pasta_objetivo = '.(int)$pg_objetivo_estrategico_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_pasta_estrategia = '.(int)$pg_estrategia_id);
		else if ($pg_fator_critico_id) $sql->adOnde('arquivo_pasta_fator = '.(int)$pg_fator_critico_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_pasta_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_pasta_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $sql->adOnde('arquivo_pasta_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $sql->adOnde('arquivo_pasta_calendario = '.(int)$calendario_id);
		else if ($ata_id) $sql->adOnde('arquivo_pasta_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_pasta_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $sql->adOnde('arquivo_pasta_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_pasta_usuario=0 OR arquivo_pasta_usuario IS NULL OR arquivo_pasta_usuario = '.(int)$Aplic->usuario_id);
		}

	if ($superior) $sql->adOnde('arquivo_pasta_superior = \''.$superior.'\'');
	else  $sql->adOnde('arquivo_pasta_superior IS NULL');
	
	$sql->adOrdem('arquivo_pasta_nome');
	$sql->adGrupo('arquivo_pasta.arquivo_pasta_id');	
	$pastas = $sql->Lista();
	$sql->limpar();
	$s = '';

	foreach ($pastas as $linha) {
		if (permiteAcessarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id'])){
			$permiteEditar=permiteEditarPasta($linha['arquivo_pasta_acesso'], $linha['arquivo_pasta_id']);
			$editar=($podeEditar&&$permiteEditar);
			$arquivo_contagem = contarArquivos($linha['arquivo_pasta_id']);
			$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
			if ($linha['arquivo_pasta_descricao']) $dentro .= '<tr><td align="center" style="border: 1px solid;-border-radius:3.5px;-webkit-border-radius:3.5px;"><b>Descrição</b></td><td>'.$linha['arquivo_pasta_descricao'].'</td></tr>';
			$dentro .= '</table>';
			$dentro .= '<br>Clique para abrir esta pasta.';
			$s .= '<ul><li><table width="100%"><tr><td><span class="pasta-nome">';
			for ($i=0 ; $i < $nivel ; $i++) $s .= '&nbsp;';
			$s .=($m == 'arquivos' ? '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');" name="ff'.$linha['arquivo_pasta_id'].'">'.imagem('icones/pasta.png', 'Pasta', 'Clique neste ícone '.imagem('icones/pasta.png').' para mostrar os arquivos dentro da pasta.').'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');" name="ff'.$linha['arquivo_pasta_id'].'">'.dica($linha['arquivo_pasta_nome'], $dentro).$linha['arquivo_pasta_nome'].dicaF().'</a>' : imagem('icones/pasta.png').$linha['arquivo_pasta_nome']);
			if ($arquivo_contagem > 0) {
				$plural=(($arquivo_contagem < 2) ? '' : 's');
				$s .= ' <a href="javascript: void(0);" onClick="expandir(\'arquivos_'.$linha['arquivo_pasta_id'].'\')" class="tem-arquivos">'.dica("Ver o$plural Arquivo$plural", "Clique em cima para visualizar o$plural ".(($arquivo_contagem < 2) ? "" :"$arquivo_contagem ")."arquivo$plural")."($arquivo_contagem arquivo$plural)" .dicaF().'</a>';
				}
			$s .= '</td><form name="frm_remover_pasta_'.$linha['arquivo_pasta_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_pasta_aed" /><input type="hidden" name="del" value="1" /><input type="hidden" name="arquivo_pasta_id" value="'.$linha['arquivo_pasta_id'].'" /></form>';
			$s .= '<td align="right" width="64">';
			if ($editar) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar_pasta&arquivo_pasta_id='.$linha['arquivo_pasta_id'].'\');">'.imagem('icones/editar.gif','Editar', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar esta pasta.').'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar_pasta&arquivo_pasta_superior='.$linha['arquivo_pasta_id'].'&arquivo_pasta_id=0\');">'.imagem('icones/adicionar.png', 'Nova Pasta', 'Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar uma nova subpasta.').'</a><a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir esta pasta?\')) {document.frm_remover_pasta_'.$linha['arquivo_pasta_id'].'.submit()}">'.imagem('icones/remover.png', 'Excluir', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir esta pasta.').'</a><a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&arquivo_pasta='.$linha['arquivo_pasta_id'].'&arquivo_id=0\');">'.imagem('icones/dentroPasta.png', 'Novo Arquivo', 'Clique neste ícone '.imagem('icones/dentroPasta.png').' para adicionar novo arquivo à pasta.').'</a>';
			$s .= '</td></tr></table></span>';
			if ($arquivo_contagem > 0) $s .= '<div class="arquivos-list" id="arquivos_'.$linha['arquivo_pasta_id'].'" style="display: none;">'.mostrarArquivos($linha['arquivo_pasta_id']).'</div>';
			$s .=getPastas($linha['arquivo_pasta_id'], $nivel + 1);
			$s .= '</li></ul>';
			}
		}
	return $s;
	}

function contarArquivos($arquivo_pasta_id) {
	global $Aplic, $cia_id, $tab, $arquivo_usuario, $mostrarProjeto, $arquivo_tipos,
	$tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$pg_objetivo_estrategico_id, 
	$pg_fator_critico_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$arquivo_usuario,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;
		
	$sql = new BDConsulta();
	$sql->adTabela('arquivos');
	$sql->adCampo('count(DISTINCT arquivos.arquivo_id)');
	$sql->esqUnir('arquivo_pasta', 'ff', 'ff.arquivo_pasta_id = arquivo_pasta');
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivos.arquivo_id');
		if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa='.(int)$tarefa_id);
		elseif ($projeto_id) $sql->adOnde('arquivo_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id) $sql->adOnde('arquivo_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica='.(int)$pratica_id);
		elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador='.(int)$pratica_indicador_id);
		elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata='.(int)$ata_id);
		elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa='.(int)$programa_id);
		elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao='.(int)$licao_id);
		elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento='.(int)$evento_id);
		elseif ($link_id) $sql->adOnde('arquivo_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id) $sql->adOnde('arquivo_gestao_template='.(int)$template_id);	
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('arquivo_gestao_me='.(int)$me_id);
		
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
		else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_objetivo = '.(int)$pg_objetivo_estrategico_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
		else if ($pg_fator_critico_id) $sql->adOnde('arquivo_fator = '.(int)$pg_fator_critico_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
		else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
		}

	$arquivos_na_pasta = $sql->Resultado();
	$sql->limpar();
	return $arquivos_na_pasta;
	}

function mostrarArquivos($arquivo_pasta_id) {
	global $m, $a, $tab, $Aplic, $xpg_min, $xpg_tamanhoPagina, $pratica_id, $demanda_id, $instrumento_id, $plano_acao_id, $tema_id, $pratica_indicador_id, $arquivo_usuario, $mostrarProjeto, $arquivo_tipos, $objPasta, $xpg_totalregistros, $xpg_total_paginas, $pagina, $cia_id, $dept_id, $config, $podeAcessar,$perms,
	$tarefa_id, 
	$projeto_id, 
	$pg_perspectiva_id, 
	$tema_id, 
	$pg_objetivo_estrategico_id, 
	$pg_fator_critico_id, 
	$pg_estrategia_id,
	$pg_meta_id, 
	$pratica_id, 
	$pratica_indicador_id, 
	$plano_acao_id, 
	$canvas_id, 
	$risco_id,
	$risco_resposta_id,
	$calendario_id, 
	$monitoramento_id, 
	$ata_id, 
	$swot_id, 
	$operativo_id,
	$instrumento_id,
	$recurso_id,
	$problema_id,
	$demanda_id,
	$programa_id,
	$licao_id,
	$evento_id,
	$link_id,
	$avaliacao_id,
	$tgn_id,
	$brainstorm_id,
	$gut_id,
	$causa_efeito_id,
	$arquivo_id,
	$forum_id,
	$checklist_id,
	$agenda_id,
	$agrupamento_id,
	$patrocinador_id,
	$template_id,
	$arquivo_usuario,
	$painel_id,
	$painel_odometro_id,
	$painel_composicao_id,
	$tr_id,
	$me_id;
	
	
	$extra='';
	$extra.=($cia_id ? '&cia_id='.(int)$cia_id : '');
	$extra.=($dept_id ? '&dept_id='.(int)$dept_id : '');
	$extra.=($tarefa_id ? '&tarefa_id='.(int)$tarefa_id  : '');
	$extra.=($projeto_id ? '&projeto_id='.(int)$projeto_id  : '');
	$extra.=($pg_perspectiva_id ? '&pg_perspectiva_id='.(int)$pg_perspectiva_id  : '');
	$extra.=($tema_id ? '&tema_id='.(int)$tema_id  : '');
	$extra.=($pg_objetivo_estrategico_id ? '&pg_objetivo_estrategico_id='.(int)$pg_objetivo_estrategico_id  : '');
	$extra.=($pg_fator_critico_id ? '&pg_fator_critico_id='.(int)$pg_fator_critico_id  : '');
	$extra.=($pg_estrategia_id? '&pg_estrategia_id='.(int)$pg_estrategia_id : '');
	$extra.=($pg_meta_id ? '&pg_meta_id='.(int)$pg_meta_id  : '');
	$extra.=($pratica_id ? '&pratica_id='.(int)$pratica_id  : '');
	$extra.=($pratica_indicador_id ? '&pratica_indicador_id='.(int)$pratica_indicador_id  : '');
	$extra.=($plano_acao_id ? '&plano_acao_id='.(int)$plano_acao_id  : '');
	$extra.=($canvas_id ? '&canvas_id='.(int)$canvas_id  : '');
	$extra.=($risco_id? '&risco_id='.(int)$risco_id : '');
	$extra.=($risco_resposta_id? '&risco_resposta_id='.(int)$risco_resposta_id : '');
	$extra.=($calendario_id ? '&calendario_id='.(int)$calendario_id  : '');
	$extra.=($monitoramento_id ? '&monitoramento_id='.(int)$monitoramento_id  : '');
	$extra.=($ata_id ? '&ata_id='.(int)$ata_id  : '');
	$extra.=($swot_id ? '&swot_id='.(int)$swot_id  : '');
	$extra.=($operativo_id? '&operativo_id='.(int)$operativo_id : '');
	$extra.=($instrumento_id? '&instrumento_id='.(int)$instrumento_id : '');
	$extra.=($recurso_id? '&recurso_id='.(int)$recurso_id : '');
	$extra.=($problema_id? '&problema_id='.(int)$problema_id : '');
	$extra.=($demanda_id? '&demanda_id='.(int)$demanda_id : '');
	$extra.=($programa_id? '&programa_id='.(int)$programa_id : '');
	$extra.=($licao_id? '&licao_id='.(int)$licao_id : '');
	$extra.=($evento_id? '&evento_id='.(int)$evento_id : '');
	$extra.=($link_id? '&link_id='.(int)$link_id : '');
	$extra.=($avaliacao_id? '&avaliacao_id='.(int)$avaliacao_id : '');
	$extra.=($tgn_id? '&tgn_id='.(int)$tgn_id : '');
	$extra.=($brainstorm_id? '&brainstorm_id='.(int)$brainstorm_id : '');
	$extra.=($gut_id? '&gut_id='.(int)$gut_id : '');
	$extra.=($causa_efeito_id? '&causa_efeito_id='.(int)$causa_efeito_id : '');
	$extra.=($arquivo_id? '&arquivo_id='.(int)$arquivo_id : '');
	$extra.=($forum_id? '&forum_id='.(int)$forum_id : '');
	$extra.=($checklist_id? '&checklist_id='.(int)$checklist_id : '');
	$extra.=($agenda_id? '&agenda_id='.(int)$agenda_id : '');
	$extra.=($agrupamento_id? '&agrupamento_id='.(int)$agrupamento_id : '');
	$extra.=($patrocinador_id? '&patrocinador_id='.(int)$patrocinador_id : '');
	$extra.=($template_id? '&template_id='.(int)$template_id : '');
	$extra.=($arquivo_usuario? '&arquivo_usuario='.(int)$arquivo_usuario : '');
	$extra.=($painel_id? '&painel_id='.(int)$painel_id : '');
	$extra.=($painel_odometro_id? '&painel_odometro_id='.(int)$painel_odometro_id : '');
	$extra.=($painel_composicao_id? '&painel_composicao_id='.(int)$painel_composicao_id : '');
	$extra.=($tr_id? '&tr_id='.(int)$tr_id : '');
	$extra.=($me_id? '&me_id='.(int)$me_id : '');
	
	
	
	
	
	
	
	$podeEditar = $Aplic->checarModulo('arquivos', 'editar');
	$ordenar = getParam($_REQUEST, 'ordenar', 'arquivo_data');
	$ordem = getParam($_REQUEST, 'ordem', '0');
	if ($ordenar=='nome') $ordenar=($ordem ? 'arquivo_nome DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_nome ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='pasta') $ordenar=($ordem ? 'arquivo_pasta_nome DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_pasta_nome ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='categoria') $ordenar=($ordem ? 'arquivo_categoria DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_categoria ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='responsavel') $ordenar=($ordem ? 'contato_posto_valor DESC, contato_nomeguerra DESC, arquivo_data ASC' : 'contato_posto_valor ASC, contato_nomeguerra ASC, arquivo_data ASC');
	if ($ordenar=='tamanho') $ordenar=($ordem ? 'arquivo_tamanho DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_tamanho ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='tipo') $ordenar=($ordem ? 'arquivo_tipo DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_tipo ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='data') $ordenar='arquivo_data'.($ordem ? ' DESC' : ' ASC' ); 
	if ($ordenar=='saida') $ordenar=($ordem ? 'arquivo_motivo_saida DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_motivo_saida ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='descricao') $ordenar=($ordem ? 'arquivo_descricao DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_descricao ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	if ($ordenar=='retirou') $ordenar=($ordem ? 'arquivo_saida DESC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'arquivo_saida ASC, arquivo_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
	$df = '%d/%m/%Y';
	$tf = $Aplic->getPref('formatohora');
	
	$sql = new BDConsulta();
	$sql->adTabela('arquivos');
	
	$sql->esqUnir('usuarios', 'u', 'u.usuario_id = arquivo_dono');
	$sql->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
	
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	
	if ($Aplic->profissional){
		$sql->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivos.arquivo_id');
		if ($tarefa_id) $sql->adOnde('arquivo_gestao_tarefa='.(int)$tarefa_id);
		elseif ($projeto_id) $sql->adOnde('arquivo_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id) $sql->adOnde('arquivo_gestao_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($tema_id) $sql->adOnde('arquivo_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id) $sql->adOnde('arquivo_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id) $sql->adOnde('arquivo_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id) $sql->adOnde('arquivo_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id) $sql->adOnde('arquivo_gestao_pratica='.(int)$pratica_id);
		elseif ($pratica_indicador_id) $sql->adOnde('arquivo_gestao_indicador='.(int)$pratica_indicador_id);
		elseif ($plano_acao_id) $sql->adOnde('arquivo_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id) $sql->adOnde('arquivo_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id) $sql->adOnde('arquivo_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id) $sql->adOnde('arquivo_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id) $sql->adOnde('arquivo_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id) $sql->adOnde('arquivo_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id) $sql->adOnde('arquivo_gestao_ata='.(int)$ata_id);
		elseif ($swot_id) $sql->adOnde('arquivo_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id) $sql->adOnde('arquivo_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id) $sql->adOnde('arquivo_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id) $sql->adOnde('arquivo_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id) $sql->adOnde('arquivo_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id) $sql->adOnde('arquivo_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id) $sql->adOnde('arquivo_gestao_programa='.(int)$programa_id);
		elseif ($licao_id) $sql->adOnde('arquivo_gestao_licao='.(int)$licao_id);
		elseif ($evento_id) $sql->adOnde('arquivo_gestao_evento='.(int)$evento_id);
		elseif ($link_id) $sql->adOnde('arquivo_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id) $sql->adOnde('arquivo_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id) $sql->adOnde('arquivo_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id) $sql->adOnde('arquivo_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id) $sql->adOnde('arquivo_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id) $sql->adOnde('arquivo_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($forum_id) $sql->adOnde('arquivo_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id) $sql->adOnde('arquivo_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id) $sql->adOnde('arquivo_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id) $sql->adOnde('arquivo_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id) $sql->adOnde('arquivo_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id) $sql->adOnde('arquivo_gestao_template='.(int)$template_id);	
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		elseif ($painel_id) $sql->adOnde('arquivo_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id) $sql->adOnde('arquivo_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id) $sql->adOnde('arquivo_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id) $sql->adOnde('arquivo_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $sql->adOnde('arquivo_gestao_me='.(int)$me_id);
		
		elseif ($arquivo_usuario) $sql->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $sql->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $sql->adOnde('arquivo_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $sql->adOnde('arquivo_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $sql->adOnde('arquivo_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $sql->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $sql->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $sql->adOnde('arquivo_tema = '.(int)$tema_id);
		else if ($pg_objetivo_estrategico_id) $sql->adOnde('arquivo_objetivo = '.(int)$pg_objetivo_estrategico_id);
		else if ($pg_estrategia_id) $sql->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
		else if ($pg_fator_critico_id) $sql->adOnde('arquivo_fator = '.(int)$pg_fator_critico_id);
		else if ($pg_meta_id) $sql->adOnde('arquivo_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $sql->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $sql->adOnde('arquivo_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $sql->adOnde('arquivo_calendario = '.(int)$calendario_id);
		else if ($ata_id) $sql->adOnde('arquivo_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $sql->adOnde('arquivo_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $sql->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
		else $sql->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
		}

	$sql->esqUnir('arquivo_pasta', 'ff', 'ff.arquivo_pasta_id = arquivo_pasta');
	$sql->adCampo('arquivos.*,count(arquivo_versao) as arquivo_versoes,round(max(arquivo_versao), 2) as arquivo_ultimaversao,arquivo_pasta_id, arquivo_pasta_nome, contato_id, usuario_id');
	if ($arquivo_pasta_id) $sql->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	else $sql->adOnde('arquivo_pasta = 0 OR arquivo_pasta IS NULL');
	$sql->adGrupo('arquivo_pasta, arquivo_versao_id, arquivos.arquivo_id, ff.arquivo_pasta_id, contato_id, usuario_id');

	$sql->adOrdem($ordenar);
	$sql->setLimite($xpg_min, $xpg_tamanhoPagina);
	$arquivos = $sql->Lista();
	
	$qv = new BDConsulta();
	$qv->adTabela('arquivos');
	$qv->esqUnir('usuarios', 'u', 'u.usuario_id = arquivo_dono');
	$qv->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
	$qv->esqUnir('arquivo_pasta', 'ff', 'ff.arquivo_pasta_id = arquivo_pasta');
	$qv->adCampo('arquivos.*, usuario_login as arquivo_dono, arquivo_pasta_nome, usuario_id');
	if ($arquivo_pasta_id) $qv->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	else $qv->adOnde('arquivo_pasta = 0 OR arquivo_pasta IS NULL');
	if ($arquivo_pasta_id) $qv->adOnde('arquivo_pasta = '.(int)$arquivo_pasta_id);
	
	if ($Aplic->profissional){
		$qv->esqUnir('arquivo_gestao','arquivo_gestao','arquivo_gestao_arquivo = arquivos.arquivo_id');
		if ($tarefa_id) $qv->adOnde('arquivo_gestao_tarefa='.(int)$tarefa_id);
		elseif ($projeto_id) $qv->adOnde('arquivo_gestao_projeto='.(int)$projeto_id);
		elseif ($pg_perspectiva_id) $qv->adOnde('arquivo_gestao_perspectiva='.(int)$pg_perspectiva_id);
		elseif ($tema_id) $qv->adOnde('arquivo_gestao_tema='.(int)$tema_id);
		elseif ($pg_objetivo_estrategico_id) $qv->adOnde('arquivo_gestao_objetivo='.(int)$pg_objetivo_estrategico_id);
		elseif ($pg_fator_critico_id) $qv->adOnde('arquivo_gestao_fator='.(int)$pg_fator_critico_id);
		elseif ($pg_estrategia_id) $qv->adOnde('arquivo_gestao_estrategia='.(int)$pg_estrategia_id);
		elseif ($pg_meta_id) $qv->adOnde('arquivo_gestao_meta='.(int)$pg_meta_id);
		elseif ($pratica_id) $qv->adOnde('arquivo_gestao_pratica='.(int)$pratica_id);
		elseif ($pratica_indicador_id) $qv->adOnde('arquivo_gestao_indicador='.(int)$pratica_indicador_id);
		elseif ($plano_acao_id) $qv->adOnde('arquivo_gestao_acao='.(int)$plano_acao_id);
		elseif ($canvas_id) $qv->adOnde('arquivo_gestao_canvas='.(int)$canvas_id);
		elseif ($risco_id) $qv->adOnde('arquivo_gestao_risco='.(int)$risco_id);
		elseif ($risco_resposta_id) $qv->adOnde('arquivo_gestao_risco_resposta='.(int)$risco_resposta_id);
		elseif ($calendario_id) $qv->adOnde('arquivo_gestao_calendario='.(int)$calendario_id);
		elseif ($monitoramento_id) $qv->adOnde('arquivo_gestao_monitoramento='.(int)$monitoramento_id);
		elseif ($ata_id) $qv->adOnde('arquivo_gestao_ata='.(int)$ata_id);
		elseif ($swot_id) $qv->adOnde('arquivo_gestao_swot='.(int)$swot_id);
		elseif ($operativo_id) $qv->adOnde('arquivo_gestao_operativo='.(int)$operativo_id);
		elseif ($instrumento_id) $qv->adOnde('arquivo_gestao_instrumento='.(int)$instrumento_id);
		elseif ($recurso_id) $qv->adOnde('arquivo_gestao_recurso='.(int)$recurso_id);
		elseif ($problema_id) $qv->adOnde('arquivo_gestao_problema='.(int)$problema_id);
		elseif ($demanda_id) $qv->adOnde('arquivo_gestao_demanda='.(int)$demanda_id);
		elseif ($programa_id) $qv->adOnde('arquivo_gestao_programa='.(int)$programa_id);
		elseif ($licao_id) $qv->adOnde('arquivo_gestao_licao='.(int)$licao_id);
		elseif ($evento_id) $qv->adOnde('arquivo_gestao_evento='.(int)$evento_id);
		elseif ($link_id) $qv->adOnde('arquivo_gestao_link='.(int)$link_id);
		elseif ($avaliacao_id) $qv->adOnde('arquivo_gestao_avaliacao='.(int)$avaliacao_id);
		elseif ($tgn_id) $qv->adOnde('arquivo_gestao_tgn='.(int)$tgn_id);
		elseif ($brainstorm_id) $qv->adOnde('arquivo_gestao_brainstorm='.(int)$brainstorm_id);
		elseif ($gut_id) $qv->adOnde('arquivo_gestao_gut='.(int)$gut_id);
		elseif ($causa_efeito_id) $qv->adOnde('arquivo_gestao_causa_efeito='.(int)$causa_efeito_id);
		elseif ($forum_id) $qv->adOnde('arquivo_gestao_forum='.(int)$forum_id);
		elseif ($checklist_id) $qv->adOnde('arquivo_gestao_checklist='.(int)$checklist_id);
		elseif ($agenda_id) $qv->adOnde('arquivo_gestao_agenda='.(int)$agenda_id);
		elseif ($agrupamento_id) $qv->adOnde('arquivo_gestao_agrupamento='.(int)$agrupamento_id);
		elseif ($patrocinador_id) $qv->adOnde('arquivo_gestao_patrocinador='.(int)$patrocinador_id);
		elseif ($template_id) $qv->adOnde('arquivo_gestao_template='.(int)$template_id);	
		elseif ($arquivo_usuario) $qv->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		elseif ($painel_id) $qv->adOnde('arquivo_gestao_painel='.(int)$painel_id);
		elseif ($painel_odometro_id) $qv->adOnde('arquivo_gestao_painel_odometro='.(int)$painel_odometro_id);
		elseif ($painel_composicao_id) $qv->adOnde('arquivo_gestao_painel_composicao='.(int)$painel_composicao_id);
		elseif ($tr_id) $qv->adOnde('arquivo_gestao_tr='.(int)$tr_id);
		elseif ($me_id) $qv->adOnde('arquivo_gestao_me='.(int)$me_id);
		
		elseif ($arquivo_usuario) $qv->adOnde('arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		else $qv->adOnde('arquivo_gestao_usuario=0 OR arquivo_gestao_usuario IS NULL OR arquivo_gestao_usuario = '.(int)$Aplic->usuario_id);
		}
	else {
		if ($tarefa_id) $qv->adOnde('arquivo_tarefa IN ('.$tarefa_id.')');
		else if ($projeto_id) $qv->adOnde('arquivo_projeto IN('.$projeto_id.')');
		else if ($pratica_id) $qv->adOnde('arquivo_pratica = '.(int)$pratica_id);
		else if ($demanda_id) $qv->adOnde('arquivo_demanda = '.(int)$demanda_id);
		else if ($instrumento_id) $qv->adOnde('arquivo_instrumento = '.(int)$instrumento_id);
		else if ($pratica_indicador_id) $qv->adOnde('arquivo_indicador = '.(int)$pratica_indicador_id);
		else if ($tema_id) $qv->adOnde('arquivo_tema = '.(int)$tema_id);
		else if ($pg_objetivo_estrategico_id) $qv->adOnde('arquivo_objetivo = '.(int)$pg_objetivo_estrategico_id);
		else if ($pg_estrategia_id) $qv->adOnde('arquivo_estrategia = '.(int)$pg_estrategia_id);
		else if ($pg_fator_critico_id) $qv->adOnde('arquivo_fator = '.(int)$pg_fator_critico_id);
		else if ($pg_meta_id) $qv->adOnde('arquivo_meta = '.(int)$pg_meta_id);
		else if ($pg_perspectiva_id) $qv->adOnde('arquivo_perspectiva = '.(int)$pg_perspectiva_id);
		else if ($canvas_id) $qv->adOnde('arquivo_canvas = '.(int)$canvas_id);
		else if ($calendario_id) $qv->adOnde('arquivo_calendario = '.(int)$calendario_id);
		else if ($ata_id) $qv->adOnde('arquivo_ata= '.(int)$ata_id);
		else if ($plano_acao_id) $qv->adOnde('arquivo_acao = '.(int)$plano_acao_id);
		else if ($arquivo_usuario) $qv->adOnde('arquivo_usuario = '.(int)$Aplic->usuario_id);
		else $qv->adOnde('arquivo_usuario=0 OR arquivo_usuario IS NULL OR arquivo_usuario = '.(int)$Aplic->usuario_id);
		}
	$arquivo_versoes = $qv->Lista();
	$sql->limpar();
	$qv->limpar();


	$s = '<table width="100%" cellpadding=0 cellspacing=0 class="tbl1"><tr>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=nome&ordem='.($ordem ? '0' : '1').'\');">'.dica('Nome do Arquivo', 'Clique para ordenar pelo nome dos arquivos.<br><br>Todo arquivo enviado para o Sistema deverá ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=descricao&ordem='.($ordem ? '0' : '1').'\');">'.dica('Descrição do Arquivo', 'Clique para ordenar pela descrição dos arquivos.<br><br>Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreensão do rquivo e facilitar futuras pesquisas.').'Descrição'.dicaF().'</th>';
	$s .= '<th>'.dica('Versão do Arquivo', 'O Sistema registra as modificações nos arquivos, mantendo um histórico.<ul><li>Para visualizar as modificações clique no número que aparecer entre parênteses. ex: 1.01 <b><font color="#000066">(2)</font></b></li></ul>').'Versão'.dicaF().'</th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=categoria&ordem='.($ordem ? '0' : '1').'\');">'.dica('Categoria do Arquivo', 'Clique para ordenar pela categoria dos arquivos.<br><br>Os arquivos podem ser :<ul><li>Documento - normalmente textos e imagens.</li><li>Arquivos - normalmente aplicativos executaveis.</li></ul>').'Categoria'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=responsavel&ordem='.($ordem ? '0' : '1').'\');">'.dica('Responsável', 'Clique para ordenar pelo nome d'.$config['genero_usuario'].'s '.$config['usuarios'].' que enviaram os arquivos').'Responsável'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=tamanho&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tamanho', 'Clique para ordenar pelo tamanho dos arquivos.<br><br>O tamanho do arquivo é em bytes').'Tamanho'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=tipo&ordem='.($ordem ? '0' : '1').'\');">'.dica('Tipo de Arquivo', 'Clique para ordenar pela extensão dos arquivos.<br><br>Pela extensão do arquivo, o sistema tentará identificar qual o tipo de arquivo.').'Tipo'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=data&ordem='.($ordem ? '0' : '1').'\');">'.dica('Data de Inclusão', 'Clique para ordenar pela data em que os arquivos foram inseridos no Sistema pela primeira vez.').'Data Inclusão'.dicaF().'</a></th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=saida&ordem='.($ordem ? '0' : '1').'\');">'.dica('Texto de Saída', 'Clique para ordenar pelo texto de retirada.<br><br>Quando um arquivo tiver um destinatário específico, este ao inves de clicar no nome do arquivo para fazer o <i>download</i>, deve utilizar o botão de retirada '.imagem('icones/acima.png').' , e poderá deixar um texto comentando sobre a retirada do arquivo que estava na caixa de saída.').'Saída'.dicaF().'</th>';
	$s .= '<th><a class="hdr" href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.($a ? '&a='.$a : '').($tab ? '&tab='.$tab : '').($arquivo_pasta_id ? '&arquivo_pasta_id='.$arquivo_pasta_id : '').$extra.'&ordenar=retirou&ordem='.($ordem ? '0' : '1').'\');">'.dica('Entrada e Saída de Arquivos', 'Clique para ordenar pel'.$config['genero_usuario'].'s '.$config['usuarios'].' que retiram os arquivos.<br><br>Nos campos abaixo há três situações :<br><br><li>Retirar arquivo '.imagem('icones/acima.png').'  : Quando um arquivo lhe for destinado, ao inves de clicar no nome do arquivo para fazer o <i>download</i>, utilize este botão para ficar registrado no sistema que já retirou. </li><br><br><li>Alterar o arquivo de saída '.imagem('icones/down.png').'  : Caso deseje modificar o arquivo na caixa de saída, Clique neste botão. </li><br><br><li>Nome do '.ucfirst($config['usuario']).' - caso outr'.$config['genero_usuario'].' '.$config['usuario'].' já tenha clicado no botão retirar arquivo,  constará neste campo o nome do mesmo.</li>').'E/S'.dicaF().'</th>';
	$s .='<th>&nbsp;</th></tr>';

	$arquivo_data = new CData();
	$id = 0;
	$qnt=0;
	
	foreach ($arquivos as $linha) {
		if (permiteAcessarArquivo($linha['arquivo_acesso'], $linha['arquivo_id'])){
			$qnt++;
			$arquivo_data = new CData($linha['arquivo_data']);
			
			$permiteEditar=permiteEditarArquivo($linha['arquivo_acesso'], $linha['arquivo_id']);
			 
			$editar=($podeEditar&&$permiteEditar);
			
			
			$arquivo = ($linha['arquivo_versoes'] > 1 ?  ultimo_arquivo1($arquivo_versoes, $linha['arquivo_versao_id']) : $linha);
			$s .= '<form name="frm_remover_file_'.$arquivo['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="del" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo['arquivo_id'].'" /></form><form name="frm_duplicar_file_'.$arquivo['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="duplicar" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo['arquivo_id'].'" /></form><tr><td>';
			$arquivo_icone = getIcone($arquivo['arquivo_tipo']);
			$dentro = '<br>Clique para abrir este arquivo.';
			$s .= dica($arquivo['arquivo_nome'], $dentro).'<a href="./codigo/arquivo_visualizar.php?arquivo_id='.$arquivo['arquivo_id'].'"><img border=0 width="16" heigth="16" src="'.acharImagem($arquivo_icone, 'arquivos').'" />&nbsp;'.$arquivo['arquivo_nome'].'</a>'.dicaF().'</td><td>'.($arquivo['arquivo_descricao'] ? limpar_paragrafo($arquivo['arquivo_descricao']) : '&nbsp;').'</td><td align="right">';
			$tabela_oculta = '';
			$s .= $linha['arquivo_ultimaversao'];
			if ($linha['arquivo_versoes'] > 1) {
				$s .= ' <a href="javascript: void(0);" onClick="expandir(\'versoes_'.$arquivo['arquivo_id'].'\'); ">('.$linha['arquivo_versoes'].')</a>';
				$tabela_oculta = '<tr style="display: none" id="versoes_'.$arquivo['arquivo_id'].'"><td colspan="20"><table width="100%" cellpadding=0 cellspacing=0 class="tbl1">';
				$tabela_oculta .= '<tr><th>'.dica('Nome do Arquivo', 'Clique para ordenar pelo nome dos arquivos.<br><br>Todo arquivo enviado para o Sistema deverá ter um nome, preferencialmente significativo, para facilitar um futura pesquisa.').'Nome do Arquivo'.dicaF().'</th><th>'.dica('Descrição do Arquivo', 'Clique para ordenar pela descrição dos arquivos.<br><br>Ao se enviar um arquivo, pode-se escrever um texto explicativo para facilitar a compreensão do rquivo e facilitar futuras pesquisas.').'Descrição'.dicaF().'</th><th>'.dica('Versão do Arquivo', 'O Sistema registra as modificações nos arquivos, mantendo um histórico.<ul><li>Para visualizar as modificações clique no número que aparecer entre parênteses. ex: 1.01 <b><font color="#000066">(2)</font></b></li></ul>').'Versão'.dicaF().'</th><th>'.dica('Categoria do Arquivo', 'Clique para ordenar pela categoria dos arquivos.<br><br>Os arquivos podem ser :<ul><li>Documento - normalmente textos e imagens.</li><li>Arquivos - normalmente aplicativos executaveis.</li></ul>').'Categoria'.dicaF().'</th><th>'.dica('Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).' Relacionada', 'Clique para ordenar pel'.$config['genero_tarefa'].' '.$config['tarefa'].'.<br><br>Caso o arquivo seja específico de um'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' d'.$config['genero_projeto'].' '.$config['projeto'].', este campo deverá constar o nome d'.$config['genero_tarefa'].' '.$config['tarefa']).'Tarefa'.dicaF().'</th><th>'.dica('Responsável', 'Clique para ordenar pelo nome d'.$config['genero_usuario'].'s '.$config['usuarios'].' que enviaram os arquivos').'Responsável'.dicaF().'</th><th>'.dica('Tamanho', 'Clique para ordenar pelo tamanho dos arquivos.<br><br>O tamanho do arquivo é em bytes').'Tamanho'.dicaF().'</th><th>'.dica('Tipo de Arquivo', 'Clique para ordenar pela extensão dos arquivos.<br><br>Pela extensão do arquivo, o sistema tentará identificar qual o tipo de arquivo.').'Tipo'.dicaF().'</a></th><th>'.dica('Data de Inclusão', 'Clique para ordenar pela data em que os arquivos foram inseridos no Sistema pela primeira vez.').'Data Inclusão'.dicaF().'</th><th>'.dica('Texto de Saída', 'Clique para ordenar pelo texto de retirada.<br><br>Quando um arquivo tiver um destinatário específico, este ao inves de clicar no nome do arquivo para fazer o <i>download</i>, deve utilizar o botão de retirada '.imagem('icones/acima.png').' , e poderá deixar um texto comentando sobre a retirada do arquivo que estava na caixa de saída.').'Saída'.dicaF().'</th><th>'.dica('Entrada e Saída de Arquivos', 'Clique para ordenar pel'.$config['genero_usuario'].'s '.$config['usuarios'].' que retiram os arquivos.<br><br>Nos campos abaixo há três situações :<br><br><li>Retirar arquivo '.imagem('icones/acima.png').'  : Quando um arquivo lhe for destinado, ao inves de clicar no nome do arquivo para fazer o <i>download</i>, utilize este botão para ficar registrado no sistema que já retirou. </li><br><br><li>Alterar o arquivo de saída '.imagem('icones/down.png').'  : Caso deseje modificar o arquivo na caixa de saída, Clique neste botão. </li><br><br><li>Nome do '.ucfirst($config['usuario']).' - caso outr'.$config['genero_usuario'].' '.$config['usuario'].' já tenha clicado no botão retirar arquivo,  constará neste campo o nome do mesmo.</li>').'E/S'.dicaF().'</th><th nowrap width="1"></th><th nowrap width="1"></th></tr>';
				foreach ($arquivo_versoes as $arquivo_linha) {
					if ($arquivo_linha['arquivo_nome'] == $linha['arquivo_nome'] && (($arquivo_linha['arquivo_projeto'] == $linha['arquivo_projeto']) || ($arquivo_linha['arquivo_indicador'] == $linha['arquivo_indicador']) || ($arquivo_linha['arquivo_pratica'] == $linha['arquivo_pratica']) || ($arquivo_linha['arquivo_demanda'] == $linha['arquivo_demanda']) || ($arquivo_linha['arquivo_instrumento'] == $linha['arquivo_instrumento']))) {
						$arquivo_icone = getIcone($arquivo_linha['arquivo_tipo']);
						$arquivo_data = new CData($arquivo_linha['arquivo_data']);
						$tabela_oculta .= '<form name="frm_excluir_sub_arquivo_'.$arquivo_linha['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="del" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo_linha['arquivo_id'].'" /></form>';
						$tabela_oculta .= '<form name="frm_duplicar_sub_arquivo_'.$arquivo_linha['arquivo_id'].'" method="post"><input type="hidden" name="m" value="arquivos" /><input type="hidden" name="fazerSQL" value="fazer_arquivo_aed" /><input type="hidden" name="duplicar" value="1" /><input type="hidden" name="arquivo_id" value="'.$arquivo_linha['arquivo_id'].'" /></form>';
						$tabela_oculta .= '<tr><td>'.dica($arquivo_linha['arquivo_nome'], $arquivo_linha['arquivo_descricao']).'<a href="./codigo/arquivo_visualizar.php?arquivo_id='.$arquivo_linha['arquivo_id'].'"><img border=0 width="16" heigth="16" src="'.acharImagem($arquivo_icone, 'arquivos').'" />&nbsp;'.$arquivo_linha['arquivo_nome'].'</a>'.dicaF().'</td><td>'.($arquivo_linha['arquivo_descricao'] ? $arquivo_linha['arquivo_descricao'] : '&nbsp;').'</td><td align="right">'.$arquivo_linha['arquivo_versao'].'</td><td align="left"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.($arquivo_linha['arquivo_categoria'] + 1).'\');">'.$arquivo_tipos[$arquivo_linha['arquivo_categoria'] + 1].'</a></td><td align="left">'.($arquivo_linha['arquivo_tarefa'] ? link_tarefa($arquivo_linha['arquivo_tarefa']) : '&nbsp;').'</td><td>' .link_usuario($linha['usuario_id'],'','','esquerda'). '</td><td align="right">'.intval($arquivo_linha['arquivo_tamanho'] / 1024).'kb </td><td>'.$arquivo_linha['arquivo_tipo'].'</td><td align="center">'.$arquivo_data->format($df.' '.$tf).'</td><td>'.($linha['arquivo_motivo_saida']? $linha['arquivo_motivo_saida'] : '&nbsp;').'</td>';
						$tabela_oculta .= '<td width="8" align="center">';
						if (empty($arquivo_linha['arquivo_saida'])) $tabela_oculta .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=saida&arquivo_id='.$arquivo_linha['arquivo_id'].'\');">'.imagem('icones/acima.png', 'saída', 'arquivos de saída').'</a>';
						else {
							if ($linha['arquivo_saida'] == $Aplic->usuario_id) $tabela_oculta .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&ci=1&arquivo_id='.$arquivo_linha['arquivo_id'].'\');">'.imagem('icones/down.png', 'entrada', 'arquivos de entrada').'</a>';
							else {
								if ($arquivo_linha['arquivo_saida'] == 'final') $tabela_oculta .= 'final';
								else {
									$q4 = new BDConsulta;
									$q4->adCampo('arquivo_id, arquivo_saida, usuario_id');
									$q4->adTabela('arquivos');
									$q4->esqUnir('usuarios', 'cu', 'cu.usuario_id = arquivo_saida');
									$q4->adOnde('arquivo_id = '.(int)$arquivo_linha['arquivo_id']);
									$co_usuario = array();
									$co_usuario = $q4->Lista();
									$co_usuario = $co_usuario[0];
									$q4->limpar();
									$tabela_oculta .= link_usuario($co_usuario['usuario_id'],'','','esquerda').'<br>';
									}
								}
							}
						$tabela_oculta .= '</td><td align="right" width="45" align="center">';
						if ($editar && (empty($arquivo_linha['arquivo_saida']) || ($arquivo_linha['arquivo_saida'] == 'final' && ($linha['projeto_responsavel'] == $Aplic->usuario_id))))	{
							$tabela_oculta .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&arquivo_id='.$arquivo_linha['arquivo_id'].'\');">'.imagem('icones/editar.gif', 'Editar Arquivo', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o arquivo.').'</a>';
							$tabela_oculta .= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este arquivo?\')) {document.frm_excluir_sub_arquivo_'.$arquivo_linha['arquivo_id'].'.submit()}">'.imagem('icones/remover.png', 'Excluir Arquivo', 'Clique neste ícone para excluir o arquivo.').'</a>';
							}
						$tabela_oculta .= '</td>';
						$tabela_oculta .= '</tr>';
						}
					}
				$tabela_oculta .= '</table>';
				}	
			$s .= '</td>';
			$s .='<td align="left"><a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=index&ver=categories&tab='.((int)$arquivo['arquivo_categoria']+1).'\');">'.dica($arquivo_tipos[$arquivo['arquivo_categoria']], 'Clique para ver os arquivos desta categoria.').$arquivo_tipos[$arquivo['arquivo_categoria']].dicaF().'</a></td>'; 
			$s .='<td>'.link_usuario($arquivo['usuario_id'],'','','esquerda').'</td>';
			$s .='<td align="right">'.intval($arquivo['arquivo_tamanho'] / 1024).' kb</td>';
			$s .='<td align="center">'.substr($arquivo['arquivo_tipo'], strpos($arquivo['arquivo_tipo'], '/') + 1).'</td>';
			$s .='<td align="center">'.$arquivo_data->format($df.' '.$tf).'</td><td >'.($linha['arquivo_motivo_saida']? $linha['arquivo_motivo_saida'] : '&nbsp;').'</td><td align="center" width="8">';
	    if ($editar){
		    if (empty($linha['arquivo_saida'])) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=saida&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/acima.png', 'Caixa de Saída', 'Clique neste ícone '.imagem('icones/acima.png').' para retirar o arquivo.').'</a>';
	    	elseif ($linha['arquivo_saida'] == $Aplic->usuario_id) $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&ci=1&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/down.png', 'Caixa de Entrada', 'Clique neste ícone '.imagem('icones/down.png').' para depositar arquivo.').'</a>';
	      elseif ($arquivo['arquivo_saida'] == 'final') $s .= 'final';
				else {
					$q4 = new BDConsulta;
					$q4->adCampo('arquivo_id, arquivo_saida, usuario_id');
					$q4->adTabela('arquivos');
					$q4->esqUnir('usuarios', 'cu', 'cu.usuario_id = arquivo_saida');
					$q4->adOnde('arquivo_id = '.(int)$arquivo['arquivo_id']);
					$co_usuario = array();
					$co_usuario = $q4->Lista();
					$co_usuario = $co_usuario[0];
					$q4->limpar();
					$s .= link_usuario($co_usuario['usuario_id'],'','','esquerda').'<br>';
					}
				}
			else $s .='&nbsp;';			
			$s .= '</td><td align="center" width="45">';
			if ($editar && (empty($arquivo['arquivo_saida']) || ($arquivo['arquivo_saida'] == 'final' && $arquivo['projeto_responsavel']==$Aplic->usuario_id))) {
				$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=editar&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/editar.gif','Editar Arquivo', 'Clique neste ícone '.imagem('icones/editar.gif').' para editar o arquivo.').'</a>';	
				$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m=arquivos&a=ver&arquivo_id='.$arquivo['arquivo_id'].'\');">'.imagem('icones/gnome-mime-application-vnd.ms-powerpoint.png', 'Ver Detalhes', 'Ao clicar neste ícone '.imagem('icones/gnome-mime-application-vnd.ms-powerpoint.png').' será possivel visualizar o detalhamento do arquivo.').'</a>';
				$s .= '<a href="javascript: void(0);" onclick="if (confirm(\'Tem certeza que deseja excluir este aquivo?\')) {document.frm_remover_file_'.$arquivo['arquivo_id'].'.submit()}">'.imagem('icones/remover.png','Excluir Arquivo', 'Clique neste ícone '.imagem('icones/remover.png').' para excluir o arquivo.', 'arquivos').'</a>';
				}
			else 	$s .='&nbsp;';
			$s .= '</td>';
			$s .='</tr>';
			$s .= $tabela_oculta;
			$tabela_oculta = '';
			}
		}
	if (!count($arquivos)) $s .= '<tr><td colspan="13">Nenhum arquivo encontrado.</td></tr>';
	elseif (!$qnt) $s .= '<tr><td colspan="13">Não tem autorização para visualizar nenhum dos arquivos.</td></tr>';	
	$s .= '</table>';
	if ($xpg_totalregistros > $xpg_tamanhoPagina) $s .= mostrarfnavbar($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $arquivo_pasta_id);
	$s .= '<br />';
	return $s;
	}

function mostrarfnavbar($xpg_totalregistros, $xpg_tamanhoPagina, $xpg_total_paginas, $pagina, $arquivo_pasta_id) {
	global $Aplic, $tab, $m, $a;
	$xpg_parar = false;
	$xpg_pag_ant = $xpg_pag_prox = 1;
	$s = '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr>';
	if ($xpg_totalregistros > $xpg_tamanhoPagina) {
		$xpg_pag_ant = $pagina - 1;
		$xpg_pag_prox = $pagina + 1;
		if ($xpg_pag_ant > 0) {
			$s .= '<td align="left"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina=1\');"><img src="'.acharImagem('navPrimeira.gif').'" border=0 Alt="First Page"></a>&nbsp;&nbsp;';
			$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$xpg_pag_ant.'\');"><img src="'.acharImagem('navAnterior.gif').'" border=0 Alt="Previous pagina ('.$xpg_pag_ant.')"></a></td>';
			} 
		else $s .= '<td>&nbsp;</td>';
		$s .= '<td align="center" >';
		$s .= $xpg_totalregistros.' Arquivo(s) Página(s): [ ';
		for ($n = $pagina > 16 ? $pagina - 16 : 1; $n <= $xpg_total_paginas; $n++) {
			if ($n == $pagina) $s .= '<b>'.$n.'</b></a>';
			else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$n.'\');"></a>';
			if ($n >= 30 + $pagina - 15) {
				$xpg_parar = true;
				break;
				} 
			elseif ($n < $xpg_total_paginas) $s .= ' | ';
			}
		if (!isset($xpg_parar)) {
			if ($n == $pagina) $s .= '<'.$n.'</a>';
			else $s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&pagina='.$xpg_total_paginas.'\');"></a>';
			}
		$s .= ' ] </td>';
		if ($xpg_pag_prox <= $xpg_total_paginas) {
			$s .= '<td align="right"><a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$xpg_pag_prox.'\');"><img src="'.acharImagem('navProximo.gif').'" border=0 Alt="Next Page ('.$xpg_pag_prox.')"></a>&nbsp;&nbsp;';
			$s .= '<a href="javascript:void(0);" onclick="url_passar(0, \'m='.$m.'&a='.$a.'&tab='.$tab.'&arquivo_pasta_id='.$arquivo_pasta_id.'&pagina='.$xpg_total_paginas.'\');"><img src="'.acharImagem('navUltima.gif').'" border=0 Alt="Last Page"></a></td>';
			} 
		else $s .= '<td>&nbsp;</td></tr>';
		} 
	else { 
		$s .= '<td align="center">';
		if ($xpg_pag_prox > $xpg_total_paginas) $s .= $xpg_sqlrecs.' Arquivos ';
		$s .= '</td></tr>';
		}
	$s .= '</table>';
	return $s;
	}

function ultimo_arquivo1($arquivo_versoes, $arquivo_versao_id) {
	$ultimo = null;
	if (isset($arquivo_versoes)) foreach ($arquivo_versoes as $arquivo_versao){
			if (($arquivo_versao['arquivo_versao_id'] == $arquivo_versao_id) && ($ultimo == null || $ultimo['arquivo_versao'] < $arquivo_versao['arquivo_versao']))	$ultimo = $arquivo_versao;
			}
	return $ultimo;
	}
	
function limpar_paragrafo($texto){
	$retirar=array('<p>', '</p>');
	$texto=str_replace('<p>', '', $texto);
	return str_replace('</p>', '<br>', $texto);
	}
	
?>
<script type="text/JavaScript">
function expandir(id){
	var element = document.getElementById(id);
	element.style.display = (element.style.display == 'none') ? '' : 'none';
	}
function adBlocoComponente(li) {
	if (document.all || navigator.appName == 'Microsoft Internet Explorer') {
		var form = document.frm_parte;
		var ni = document.getElementById('tbl_parte');
		var newitem = document.createElement('input');
		var htmltxt = '';
		newitem.id = 'parte_selecionado_arquivo['+li+']';
		newitem.name = 'parte_selecionado_arquivo['+li+']';
		newitem.type = 'hidden';
		ni.appendChild(newitem);
		} 
	else {
		var form = document.frm_parte;
		var ni = document.getElementById('tbl_parte');
		var newitem = document.createElement('input');
		newitem.setAttribute('id', 'parte_selecionado_arquivo['+li+']');
		newitem.setAttribute('name', 'parte_selecionado_arquivo['+li+']');
		newitem.setAttribute('type', 'hidden');
		ni.appendChild(newitem);
		}
	}
function removerBlocoComponente(li) {
	var t = document.getElementById('tbl_parte');
	var old = document.getElementById('parte_selecionado_arquivo['+li+']');
	t.removeChild(old);
	}
</script>

