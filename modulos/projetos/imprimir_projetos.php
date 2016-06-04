<?php
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb profissional - registrado no INPI sob o número RS 11802-5 e protegido pelo direito de autor.
É expressamente proibido utilizar este script em parte ou no todo sem o expresso consentimento do autor.
*/

if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

global $Aplic, $config,$projetos, $projStatus, $projetos_status, $projeto_status_filtro, $tabAtualId, $tabNomeAtual, $desenvolvedor;


if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';
require_once ($Aplic->getClasseModulo('cias'));
$projetoStatuses=getSisValor('StatusProjeto');
$quant=count($projetoStatuses)+2;
$status=array('0' => 'Todos', '1'=>'Ativos') + getSisValor('StatusProjeto', 2)+array($quant => 'Inativos');
$pesquisar_texto = $Aplic->getEstado('projtextobusca') ? $Aplic->getEstado('projtextobusca') : '';
$desenvolvedor = $Aplic->getEstado('ProjIdxDesenvolvedor') !== null ? $Aplic->getEstado('ProjIdxDesenvolvedor') : 0;

$tab = getParam($_REQUEST, 'tab', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$ativo = intval(!$tab);


$ordemDir = $Aplic->getEstado('ordemDir') ? $Aplic->getEstado('ordemDir') : 'desc';
if ($ordemDir == 'asc') $ordemDir = 'desc';
else $ordemDir = 'asc';

$ordenarPor = $Aplic->getEstado('ProjIdxOrdemPor') ? $Aplic->getEstado('ProjIdxOrdemPor') : 'projeto_data_fim';


if (isset($_REQUEST['projeto_responsavel'])) $Aplic->setEstado('ProjIdxResponsavel', getParam($_REQUEST, 'projeto_responsavel', null));
$responsavel = $Aplic->getEstado('ProjIdxResponsavel');
$projeto_tipo = $Aplic->getEstado('ProjIdxTipo') ? $Aplic->getEstado('ProjIdxTipo') : -1;
$projeto_tipos = array(-1 => 'todos') + getSisValor('TipoProjeto');
//projetos_inicio_data();

$filtrosBuilder = new FiltrosProjetoBuilder();
$filtrosBuilder->setUsuarioId(0)
    ->setCiaId($cia_id)
    ->setOrdenarPor($ordenarPor)
    ->setOrdemDir($ordemDir)
    ->setProjetoTipo($projeto_tipo)
    ->setPesquisarTexto($pesquisar_texto);

$projetos=projetos_inicio_data($filtrosBuilder);

echo '<table width="1024" border=0 align="center" cellpadding=0>';
echo '<tr><td colspan="4"><h2>Lista de '.ucfirst($config['projetos']).'</h2></td></tr>';
echo '<tr><td>Status: '.$status[$tab].'</td><td>Tipo: '.resultafazer_combo($projeto_tipos, 'projeto_tipo', $projeto_tipo).'</td>';
$q = new BDConsulta();
$q->adTabela('projetos', 'p');
$q->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
$q->esqUnir('usuarios', 'u', 'u.usuario_id = p.projeto_responsavel');
$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$q->adOnde('usuario_id > 0');
$q->adOnde('p.projeto_responsavel IS NOT NULL');
$lista_usuarios = array(0 => 'todos');
$lista_usuarios = $lista_usuarios + $q->ListaChave();
echo '<td>'.ucfirst($config['genero_gerente']).': '.(resultafazer_combo($lista_usuarios, 'projeto_responsavel', $responsavel)? resultafazer_combo($lista_usuarios, 'projeto_responsavel', $responsavel) : 'Todos').'</td>';
$q = new BDConsulta;
$q->adTabela('cias');
$q->adCampo('cia_id, cia_nome');
$q->adOrdem('cia_nome');
$cias = unirVetores(array(0 => ''), $q->ListaChave());
echo '<td>'.ucfirst($config['organizacao']).': ' .resultafazer_combo($cias, 'projeto_cia', $cia_id).'</td></tr></table>';
$q = new BDConsulta();
$q->adTabela('projetos', 'p');
$q->adCampo('usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').'');
$q->esqUnir('usuarios', 'u', 'u.usuario_id = p.projeto_responsavel');
$q->esqUnir('contatos', 'c', 'c.contato_id = u.usuario_contato');
$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
$q->adOnde('usuario_id > 0');
$lista_usuarios = array(0 => 'todos');
$lista_usuarios = $lista_usuarios + $q->ListaChave();
$df = '%d/%m/%Y';
$mostrar_todos_projetos = false;
$tabAtualId = ($Aplic->getEstado('ProjIdxTab') !== null ? $Aplic->getEstado('ProjIdxTab') : 0);
if ($tabAtualId == 0 || $tabAtualId == -1) $projeto_status_filtro = -1;
elseif ($tabAtualId == 1) $projeto_status_filtro = -2;
elseif ($tabAtualId == count($projetos_status) - 1) $projeto_status_filtro = -3;
else $projeto_status_filtro = ($projetoStatuses[0] ? $tabAtualId - 2 : $tabAtualId - 1);
if (($projeto_status_filtro == -1 || $projeto_status_filtro == -2 || $projeto_status_filtro == -3)) $mostrar_todos_projetos = true;
if ($projeto_status_filtro == -1) {
	//fazer algo?
	}
elseif ($projeto_status_filtro == -2) {
	$chave = 0;
	foreach ($projetos as $projeto) {
		if (!$projeto['projeto_ativo']) unset($projetos[$chave]);
		$chave++;
		}
	$chave = 0;
	foreach ($projetos as $projeto) {
		$tmp_projetos[$chave] = $projeto;
		$chave++;
		}
	$projetos = (isset($tmp_projetos) ? $tmp_projetos : array());
	}
elseif ($projeto_status_filtro == -3) {
	$chave = 0;
	foreach ($projetos as $projeto) {
		if ($projeto['projeto_ativo']) unset($projetos[$chave]);
		$chave++;
		}
	$chave = 0;
	foreach ($projetos as $projeto) {
		$tmp_projetos[$chave] = $projeto;
		$chave++;
		}
	$projetos = $tmp_projetos;
	}
else {
	//fazer algo?
	}
echo '<table width="98%" border=0 align="center" cellpadding=0 cellspacing=0 class="tbl1">';
echo '<tr><th nowrap="nowrap">Cor</th><th nowrap="nowrap">P</th><th nowrap="nowrap">Nome d'.$config['genero_projeto'].' '.ucfirst($config['projeto']).'</th><th nowrap="nowrap">'.$config['organizacao'].'</th><th nowrap="nowrap">Início</th><th nowrap="nowrap">Término</th><th nowrap="nowrap">Provável</th><th nowrap="nowrap">Responsável</th><th nowrap="nowrap">T M</th><th nowrap="nowrap">Custo</th>';
if ($projeto_status_filtro < 0) echo '<th nowrap="nowrap">Status</th>';
echo '</tr>';
$nenhum = true;
foreach ($projetos as $linha) {
	if (($mostrar_todos_projetos || ($linha['projeto_ativo'] && $linha['projeto_status'] == $projeto_status_filtro)) || (($linha['projeto_ativo'] && $linha['projeto_status'] == $projeto_status_filtro)) || ((!$linha['projeto_ativo'] && $projeto_status_filtro == -3))) {
		$nenhum = false;
		$data_inicio = intval($linha['projeto_data_inicio']) ? new CData($linha['projeto_data_inicio']) : null;
		$data_fim = intval($linha['projeto_data_fim']) ? new CData($linha['projeto_data_fim']) : null;
		$adjusted_data_final = (isset($linha['projeto_data_fim_ajustada']) && intval($linha['projeto_data_fim_ajustada'])) ? new CData($linha['projeto_data_fim_ajustada']) : null;
		$data_fim_atual = (isset($linha['projeto_fim_atualizado']) && intval($linha['projeto_fim_atualizado'])) ? new CData($linha['projeto_fim_atualizado']) : null;
		$estilo = (($data_fim_atual > $data_fim) && !empty($data_fim)) ? 'style="color:red; font-weight:bold"' : '';
		$s = '<tr><td width="45" align="center" style="background-color:#'.$linha['projeto_cor'].'"><font color="'.melhorCor($linha['projeto_cor']).'">'.sprintf("%.1f%%", $linha['projeto_percentagem']).'</font></td>';
		$s .= '<td align="center">'.prioridade($linha['projeto_prioridade'], true).'</td>';
		$s .= '<td>'.htmlspecialchars($linha['projeto_nome'], ENT_QUOTES, $localidade_tipo_caract).'</td>';
		$s .= '<td>'.htmlspecialchars($linha['cia_nome'], ENT_QUOTES, $localidade_tipo_caract).'</td>';
		$s .= '<td align="center" width="80">'.($data_inicio ? $data_inicio->format($df) : '&nbsp;').'</td><td align="center" width="80" nowrap="nowrap">'.($data_fim ? $data_fim->format($df) : '&nbsp;').'</td><td width="85" align="center">';
		$s .= $data_fim_atual ? '<span '.$estilo.'>'.$data_fim_atual->format($df).'</span>' : '&nbsp;';
		$s .= '</td><td nowrap="nowrap">'.htmlspecialchars($linha['nome_responsavel'], ENT_QUOTES, $localidade_tipo_caract).'</td><td align="center" nowrap="nowrap">';
		$s .= $linha['total_tarefas'].($linha['minhas_tarefas'] ? ' ('.$linha['minhas_tarefas'].')' : '');
		$s .= '</td>';
		$s .= '<td align="right" nowrap="nowrap">'.number_format($linha['projeto_custo'], 2, ',', '.').'</td>';
		if ($mostrar_todos_projetos) {
			$s .= '<td align="center" nowrap="nowrap">';
			$s .= $linha['projeto_status'] == 0 ? 'Não definido' : $projetoStatuses[$linha['projeto_status']];
			$s .= '</td>';
			}
		$s .= '</tr>';
		echo $s;
		}
	}
if ($nenhum) echo '<tr><td colspan="10"><p>'.($config['genero_projeto']=='o'? 'Nenhum' : 'Nenhuma').' '.$config['projeto'].' encontrad'.$config['genero_projeto'].'.</p></td></tr>';
echo '</td></tr></table>';
echo '</table>';
?>
