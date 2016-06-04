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


if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$dialogo) $Aplic->salvarPosicao();
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$sim_nao = getSisValor('SimNaoGlobal');
$html_tipos = array('textinput' => 'linha de texto', 'textarea' => 'área de texto', 'checkbox' => 'Caixa de opção', 'selecionar' => 'seleção de lista', 'label' => 'rótulo', 'separator' => 'linha horizontal', 'href' => 'Link para Web', );
if ($Aplic->profissional) {
    $html_tipos['data']='Data';
	$html_tipos['valor']='Valor';
	$html_tipos['formula']='Fórmula';
	}

$botoesTitulo = new CBlocoTitulo('Campos Customizados', 'customizado.png', 'admin', 'admin.campo_customizado');
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$edit_campo_id = getParam($_REQUEST, 'campo_id', null);
$botoesTitulo->mostrar();
$sql = new BDConsulta;


$modulos[]=array('mod_nome' => 'Agrupamento', 'mod_diretorio' => 'agrupamento');
$modulos[]=array('mod_nome' => 'Arquivo', 'mod_diretorio' => 'arquivos');
$modulos[]=array('mod_nome' => 'Ata de Reunião', 'mod_diretorio' => 'atas');
$modulos[]=array('mod_nome' => ucfirst($config['organizacao']), 'mod_diretorio' => 'cias');
$modulos[]=array('mod_nome' => ucfirst($config['usuario']), 'mod_diretorio' => 'usuario');
$modulos[]=array('mod_nome' => 'Contato', 'mod_diretorio' => 'contatos');
$modulos[]=array('mod_nome' => ucfirst($config['departamento']), 'mod_diretorio' => 'depts');
$modulos[]=array('mod_nome' => 'Forum', 'mod_diretorio' => 'foruns');
$modulos[]=array('mod_nome' => 'Link', 'mod_diretorio' => 'links');
$modulos[]=array('mod_nome' => 'Operativo', 'mod_diretorio' => 'operativo');
$modulos[]=array('mod_nome' => 'Patrocinador', 'mod_diretorio' => 'patrocinadores');
$modulos[]=array('mod_nome' => ucfirst($config['problema']), 'mod_diretorio' => 'problema');
$modulos[]=array('mod_nome' => ucfirst($config['projeto']), 'mod_diretorio' => 'projetos');
$modulos[]=array('mod_nome' => 'Recurso', 'mod_diretorio' => 'recursos');
$modulos[]=array('mod_nome' => 'SWOT', 'mod_diretorio' => 'swot');
$modulos[]=array('mod_nome' => ucfirst($config['tarefa']), 'mod_diretorio' => 'tarefas');
$modulos[]=array('mod_nome' => ucfirst($config['tr']), 'mod_diretorio' => 'tr');
$modulos[]=array('mod_nome' => 'Evento', 'mod_diretorio' => 'evento');

$modulos[]=array('mod_nome' => 'Registro de Ocorrência', 'mod_diretorio' => 'log');

$modulos[]=array('mod_nome' => 'Avaliação', 'mod_diretorio' => 'avaliacao');
$modulos[]=array('mod_nome' => 'Diagrama de Causa-Efeito', 'mod_diretorio' => 'causa_efeito');
$modulos[]=array('mod_nome' => 'Brainstorm', 'mod_diretorio' => 'brainstorm');
$modulos[]=array('mod_nome' => 'Matriz GUT', 'mod_diretorio' => 'gut');

$modulos[]=array('mod_nome' => 'Artefato - Encerramento de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_encerramento');
$modulos[]=array('mod_nome' => 'Artefato - Embasamento de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_embasamento');
$modulos[]=array('mod_nome' => 'Artefato - Qualidade de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_qualidade');
$modulos[]=array('mod_nome' => 'Artefato - Mudança de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_mudanca');
$modulos[]=array('mod_nome' => 'Artefato - Recebimento de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_recebimento');
$modulos[]=array('mod_nome' => 'Artefato - Abertura de '.ucfirst($config['projeto']), 'mod_diretorio' => 'termo_abertura');
$modulos[]=array('mod_nome' => 'Artefato - Risco de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_risco');
$modulos[]=array('mod_nome' => 'Artefato - Plano de Comunicação de '.ucfirst($config['projeto']), 'mod_diretorio' => 'projeto_comunicacao');
$modulos[]=array('mod_nome' => 'Artefato - Estudo de Viabilidade', 'mod_diretorio' => 'viabilidade');
$modulos[]=array('mod_nome' => 'Demanda', 'mod_diretorio' => 'demandas');



if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Painel de indicador', 'mod_diretorio' => 'painel');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Slideshow de Painéis', 'mod_diretorio' => 'painel_slideshow');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Composição de Painéis', 'mod_diretorio' => 'painel_composicao');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Odômetro de indicador', 'mod_diretorio' => 'painel_odometro');


if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Monitoramento', 'mod_diretorio' => 'monitoramento');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => ucfirst($config['canvas']), 'mod_diretorio' => 'canvas');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => ucfirst($config['risco']), 'mod_diretorio' => 'risco');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => ucfirst($config['risco_resposta']), 'mod_diretorio' => 'risco_resposta');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => ucfirst($config['tgn']), 'mod_diretorio' => 'tgn');

if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Programa', 'mod_diretorio' => 'programa');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Beneficio do Programa', 'mod_diretorio' => 'beneficio');
if ($Aplic->profissional) $modulos[]=array('mod_nome' => 'Modelo', 'mod_diretorio' => 'template');
$modulos[]=array('mod_nome' => 'Instrumento', 'mod_diretorio' => 'instrumento');
$modulos[]=array('mod_nome' => 'Compromissos', 'mod_diretorio' => 'agenda');
$modulos[]=array('mod_nome' => ucfirst($config['perspectiva']), 'mod_diretorio' => 'perspectivas');
$modulos[]=array('mod_nome' => ucfirst($config['tema']), 'mod_diretorio' => 'tema');
$modulos[]=array('mod_nome' => ucfirst($config['objetivo']), 'mod_diretorio' => 'objetivos');
if ($Aplic->profissional && $config['exibe_me']) $modulos[]=array('mod_nome' => ucfirst($config['me']), 'mod_diretorio' => 'me'); 
$modulos[]=array('mod_nome' => ucfirst($config['fator']), 'mod_diretorio' => 'fatores');
$modulos[]=array('mod_nome' => ucfirst($config['iniciativa']), 'mod_diretorio' => 'estrategias');
$modulos[]=array('mod_nome' => ucfirst($config['meta']), 'mod_diretorio' => 'metas');
$modulos[]=array('mod_nome' => ucfirst($config['pratica']), 'mod_diretorio' => 'praticas');
$modulos[]=array('mod_nome' => 'Indicador', 'mod_diretorio' => 'indicadores');
$modulos[]=array('mod_nome' => 'Checklist', 'mod_diretorio' => 'checklist');
$modulos[]=array('mod_nome' => 'Lição Aprendida', 'mod_diretorio' => 'licao_aprendida');


usort($modulos, 'sort_modulo');

echo estiloTopoCaixa();
echo '<table width="100%" class="std" cellpadding=0>';
echo'<tr valign="bottom"><td colspan="4">&nbsp;</td></tr>';
foreach ($modulos as $modulo) {
	echo '<tr valign="bottom"><td colspan="4">'.dica ('Adicionar Campos Customizados','Clique neste ícone '.imagem('icones/adicionar.png').' para adicionar no módulo '.$modulo['mod_nome'].' um campo customizado.').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_customizado_editar&modulo='.$modulo['mod_diretorio'].'&nome_modulo='.$modulo['mod_nome'].'\');"><b>'.imagem('icones/adicionar.png').$modulo['mod_nome'].'</b></a>'.dicaF().'</td></tr>';
	$sql->adTabela('campos_customizados_estrutura');
	$sql->adOnde('campo_modulo = \''.strtolower($modulo['mod_diretorio']).'\'');
	$sql->adOrdem('campo_ordem ASC');
	$campos_customizados = $sql->Lista();
	$sql->limpar();
	if (count($campos_customizados)) {
		echo'<tr><td colspan="4"><table width="90%" class="tbl1" cellpadding=0 cellspacing=0 align="center">';
		echo '<th>&nbsp;</th>';
		echo '<th>Nome</th>';
		echo '<th>Descrição</th>';
		echo '<th>Tipo</th>';
		echo '<th>Posição</th>';
		foreach ($campos_customizados as $f) {
			echo '<tr align="center"><td width="32">'.dica ('Editar Campo Customizado','Clique neste ícone '.imagem('icones/editar.gif').'para editar este campo customizado').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_customizado_editar&modulo='.$modulo['mod_diretorio'].'&nome_modulo='.$modulo['mod_nome'].'&campo_id='.$f['campo_id'].'\');">'.imagem('icones/editar.gif').'</a>'.dicaF();
			echo dica ('Excluir Campo Customizado','Clique neste icone '.imagem('icones/remover.png').' para excluir este campo customizado').'<a href="javascript:void(0);" onclick="url_passar(0, \'m=sistema&a=campo_customizado_editar&campo_id='.$f['campo_id'].'&excluir=1\');">'.imagem('icones/remover.png').'</a></td>';
			echo '<td>'.stripslashes($f['campo_nome']).'</td>';
			echo '<td>'.stripslashes($f['campo_descricao']).'</td>';
			echo '<td>'.$html_tipos[$f['campo_tipo_html']].'</td>';
			echo '<td>'.stripslashes($f['campo_ordem']).'</td>';
			echo '</tr>';
			}
		echo '</table></td></tr>';
		}
	echo'<tr valign="bottom"><td colspan="4">&nbsp;</td></tr>';
	}
echo '</table>';
echo estiloFundoCaixa();

function sort_modulo($a, $b){
  if ($a['mod_nome'] == $b['mod_nome']) return 0;
  return ($a['mod_nome'] < $b['mod_nome']) ? -1 : 1;
	}

?>