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

global $Aplic, $cal_sdf, $projeto_id;

$Aplic->carregarCalendarioJS();
if (!$Aplic->checarModulo('tarefas', 'acesso')) $Aplic->redirecionar('m=publico&a=acesso_negado');
$mostrarNomeProjeto=nome_projeto($projeto_id);
$data = new CData();	
$titulo=$data->format($df).' - '.ucfirst($config['tarefas']).' a serem concluíd'.$config['genero_tarefa'].'s nos próximos sete dias'.($projeto_id  ? ' n'.$config['genero_projeto'].' '.$config['projeto'].' '.$mostrarNomeProjeto : ' em tod'.$config['genero_projeto'].'s '.$config['genero_projeto'].'s '.$config['projetos']);
if (!$dialogo){
	echo '<table width="100%">';
	echo '<tr><td width="22">&nbsp;</td>';
	echo '<td align="center">';
	echo '<font size="4"><center><b>'.$titulo.'</b></center></font>';
	echo '</td>';
	echo ($dialogo ? '' : '<td width="32">'.dica('Imprimir o relatório', 'Clique neste ícone '.imagem('imprimir_p.png').' para abrir uma nova janela onde poderá imprimir o relatório a partir do navegador Web.').'<a href="javascript: void(0);" onclick="env.target=\'popup\'; env.dialogo.value=1; env.pdf.value=0; env.sem_cabecalho.value=0; env.submit();"><img src="'.acharImagem('imprimir.png').'" border=0 width="32" heigth="32" /></a>'.dicaF().'</td>');
	echo ($dialogo ? '' : '<td width="32">'.dica('Exportar o relatório para Pdf', 'Clique neste ícone '.imagem('pdf_3.png').' para exportar o relatório no formato Pdf.').'<a href="javascript: void(0);" onclick="env.target=\'\'; env.dialogo.value=1; env.sem_cabecalho.value=1; env.pdf.value=1; env.page_orientation.value=\'P\'; env.submit();"><img src="'.acharImagem('pdf_3.png').'" border=0 width="32" heigth="32" /></a>'.dicaF().'</td>');
	echo '</tr>';
	echo '</table>';
	}
else if ($Aplic->profissional) {
	include_once BASE_DIR.'/modulos/projetos/artefato.class.php';
	include_once BASE_DIR.'/modulos/projetos/artefato_template.class.php';
	$dados=array();
	$dados['projeto_cia'] = $Aplic->usuario_cia;
	$sql->adTabela('artefatos_tipo');
	$sql->adCampo('artefato_tipo_campos, artefato_tipo_endereco, artefato_tipo_html');
	$sql->adOnde('artefato_tipo_civil=\''.$config['anexo_civil'].'\'');
	$sql->adOnde('artefato_tipo_arquivo=\'cabecalho_simples_pro.html\'');
	$linha = $sql->linha();
	$sql->limpar();
	$campos = unserialize($linha['artefato_tipo_campos']);
	
	$modelo= new Modelo;
	$modelo->set_modelo_tipo(1);
	foreach((array)$campos['campo'] as $posicao => $campo) $modelo->set_campo($campo['tipo'], str_replace('\"','"',$campo['dados']), $posicao);
	$tpl = new Template($linha['artefato_tipo_html'],false,false, false, true);
	$modelo->set_modelo($tpl);
	echo '<table align="left" cellspacing=0 cellpadding=0 width=100%><tr><td>';
	for ($i=1; $i <= $modelo->quantidade(); $i++){
		$campo='campo_'.$i;
		$tpl->$campo = $modelo->get_campo($i);
		} 
	echo $tpl->exibir($modelo->edicao); 
	echo '</td></tr></table>';
	echo 	'<font size="4"><center>'.$titulo.'</center></font>';
	}
else echo '<font size="4"><center>'.$titulo.'</center></font>';


$temRecursos=0;

if (!$dialogo)echo estiloTopoCaixa();
if (!$dialogo) echo '<table border=0 width="100%" class="std"><tr><td>';
echo '<table cellspacing=0 cellpadding="2" border=0 class="tbl1" align="center">';
echo '<tr align="center"><td align="center"><b>Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).'</b></td><td align="center"><b>Nome d'.$config['genero_tarefa'].' '.ucfirst($config['tarefa']).'</b></td><td align="center"><b>Responsável</b></td></td><td align="center"><b>Data de Término</b></td></tr>';
$sql = new BDConsulta;


$sql->adTabela('projetos', 'pr');
$sql->esqUnir('usuarios', 'u', 'pr.projeto_responsavel = u.usuario_id');
$sql->esqUnir('cias', 'cias', 'pr.projeto_cia = cias.cia_id');
$sql->esqUnir('contatos', 'ct', 'ct.contato_id = u.usuario_contato');
$sql->esqUnir('tarefas', 'tarefas', 'tarefas.tarefa_projeto = pr.projeto_id');
$sql->adOnde('pr.projeto_template=0 OR pr.projeto_template IS NULL');

if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta) $sql->esqUnir('projeto_gestao', 'projeto_gestao', 'pr.projeto_id=projeto_gestao_projeto');
	
if ($filtro_criterio){
	$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica=projeto_gestao.projeto_gestao_pratica');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id=pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id=pratica_marcador.pratica_marcador_item');
	}
	
if ($filtro_criterio || $filtro_perspectiva || $filtro_tema || $filtro_objetivo || $filtro_fator || $filtro_estrategia || $filtro_meta)	{
	$filtragem=array();
	if ($filtro_criterio) $filtragem[]='pratica_item_criterio IN ('.$filtro_criterio.')';
	if ($filtro_perspectiva) $filtragem[]='projeto_gestao_perspectiva IN ('.$filtro_perspectiva.')';
	if ($filtro_tema) $filtragem[]='projeto_gestao_tema IN ('.$filtro_tema.')';
	if ($filtro_objetivo) $filtragem[]='projeto_gestao_objetivo IN ('.$filtro_objetivo.')';
	if ($filtro_fator) $filtragem[]='projeto_gestao_fator IN ('.$filtro_fator.')';
	if ($filtro_estrategia) $filtragem[]='projeto_gestao_estrategia IN ('.$filtro_estrategia.')';
	if ($filtro_meta) $filtragem[]='projeto_gestao_meta IN ('.$filtro_meta.')';
	if (count($filtragem)) $sql->adOnde(implode(' OR ', $filtragem));
	}	



if ($estado_sigla) $sql->adOnde('pr.projeto_estado=\''.$estado_sigla.'\'');
if ($municipio_id) $sql->adOnde('pr.projeto_cidade IN ('.$municipio_id.')');
if (!$portfolio && !$portfolio_pai) $sql->adOnde('pr.projeto_portfolio IS NULL OR pr.projeto_portfolio=0');
elseif($portfolio && !$portfolio_pai)  $sql->adOnde('pr.projeto_portfolio=1 AND (pr.projeto_plano_operativo=0 OR pr.projeto_plano_operativo IS NULL)');
if ($portfolio_pai){
	$sql->esqUnir('projeto_portfolio', 'projeto_portfolio', 'projeto_portfolio_filho = pr.projeto_id');
	$sql->adOnde('projeto_portfolio_pai = '.(int)$portfolio_pai);
	}
if ($favorito_id){
	$sql->internoUnir('favoritos_lista', 'favoritos_lista', 'pr.projeto_id=favoritos_lista.campo_id');
	$sql->internoUnir('favoritos', 'favoritos', 'favoritos.favorito_id =favoritos_lista.favorito_id');
	$sql->adOnde('favoritos.favorito_id IN ('.$favorito_id.')');
	}
if($dept_id) $sql->esqUnir('projeto_depts', 'projeto_depts', 'projeto_depts.projeto_id = pr.projeto_id');
if (!$nao_apenas_superiores) $sql->adOnde('pr.projeto_superior IS NULL OR pr.projeto_superior=0 OR pr.projeto_superior=pr.projeto_id');		
if ($projetostatus){
	if ($projetostatus == -1) $sql->adOnde('projeto_ativo = 1');
	elseif ($projetostatus == -2) $sql->adOnde('projeto_ativo = 0');
	elseif ($projetostatus > 0) $sql->adOnde('projeto_status IN ('.$projetostatus.')');
	}	
if($dept_id) $sql->adOnde('projeto_depts.departamento_id IN ('.$dept_id.')');	
if ($cia_id  && !$lista_cias && !$favorito_id)	$sql->adOnde('pr.projeto_cia = '.(int)$cia_id);
elseif ($lista_cias && !$favorito_id) $sql->adOnde('pr.projeto_cia IN ('.$lista_cias.')');
if ($projeto_tipo > -1)	$sql->adOnde('pr.projeto_tipo IN ('.$projeto_tipo.')');
if ($projeto_setor) $sql->adOnde('pr.projeto_setor = '.(int)$projeto_setor);
if ($projeto_segmento) $sql->adOnde('pr.projeto_segmento = '.(int)$projeto_segmento);
if ($projeto_intervencao) $sql->adOnde('pr.projeto_intervencao = '.(int)$projeto_intervencao);
if ($projeto_tipo_intervencao) $sql->adOnde('pr.projeto_tipo_intervencao = '.(int)$projeto_tipo_intervencao);
if ($supervisor) $sql->adOnde('pr.projeto_supervisor IN ('.$supervisor.')');
if ($autoridade) $sql->adOnde('pr.projeto_autoridade IN ('.$autoridade.')');
if ($responsavel) $sql->adOnde('pr.projeto_responsavel IN ('.$responsavel.')');
if (trim($pesquisar_texto)) $sql->adOnde('pr.projeto_nome LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_descricao LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_objetivos LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_como LIKE \'%'.$pesquisar_texto.'%\' OR pr.projeto_codigo LIKE \'%'.$pesquisar_texto.'%\'');

$sql->adOnde('projeto_ativo = 1');
	$sql->adOnde('projeto_template = 0');

$sql->adCampo('projeto_nome, tarefa_id, tarefa_fim, u.usuario_id');
$sql->adOnde('tarefa_percentagem < 100');
if ($projeto_id) $sql->adOnde('tarefa_projeto = '.(int)$projeto_id);
$sql->adOnde('tarefa_fim BETWEEN NOW() AND adiciona_data(NOW(), 7, \'DAY\')');

$tarefas = $sql->ListaChave('tarefa_id');
$sql->limpar();		


foreach ($tarefas as $tarefa_id => $detalhe) {
	echo '<tr><td align="left">'.$detalhe['projeto_nome'].'</td><td align="left">'.link_tarefa($tarefa_id).'</td><td align="left">'.link_usuario($detalhe['usuario_id'],'','','esquerda').'</td><td align="center">'.retorna_data($detalhe['tarefa_fim']).'</td></tr>';
	}
if (!count($tarefas)) {
	echo '<tr><td colspan=20><p>Nenhum'.($config['genero_tarefa']=='a' ?  'a' : '').' '.$config['tarefa'].' encontrad'.$config['genero_tarefa'].'</p></td></tr>';
	}		
	
echo '</table>';
if (!$dialogo){
	echo '</td></tr></table>';	
	echo estiloFundoCaixa();
	}
?>