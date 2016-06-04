<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

/********************************************************************************************
		
gpweb\modulos\praticas\imprimir_indicadores.php		

Exibir pagina web com os dados dos indicadores para impressão																																									
																																												
********************************************************************************************/
if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';
require_once ($Aplic->getClasseModulo('cias'));
require_once ($Aplic->getClasseModulo('depts'));
$sql = new BDConsulta();

$tab = getParam($_REQUEST, 'tab', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$dept_id = getParam($_REQUEST, 'dept_id', 0);

$sql->adTabela('pratica_indicador_requisito');
$sql->esqUnir('pratica_indicador','pratica_indicador', 'pratica_indicador.pratica_indicador_requisito = pratica_indicador_requisito.pratica_indicador_requisito_id');
$sql->adCampo('DISTINCT ano');
if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);

$ano = ($Aplic->getEstado('IdxIndicadorAno') !== null ? $Aplic->getEstado('IdxIndicadorAno') : $ultimo_ano);



if (isset($_REQUEST['indicadortextobusca'])) $Aplic->setEstado('indicadortextobusca', getParam($_REQUEST, 'indicadortextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('indicadortextobusca') ? $Aplic->getEstado('indicadortextobusca') : '');

$tabAtualId = $tab;

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);

$ordemDir = $Aplic->getEstado('IndicadorIdxOrdemDir') ? $Aplic->getEstado('IndicadorIdxOrdemDir') : 'asc';
if (isset($_REQUEST['ordemPor'])) {
	if ($Aplic->getEstado('IndicadorIdxOrdemDir') == 'asc') $ordemDir = 'desc';
	else $ordemDir = 'asc';
	$Aplic->setEstado('PraticaIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
	}
$ordenarPor = $Aplic->getEstado('PraticaIdxOrdemPor') ? $Aplic->getEstado('PraticaIdxOrdemPor') : 'projeto_data_fim';
$Aplic->setEstado('PraticaIdxOrdemDir', $ordemDir);


if (isset($_REQUEST['pratica_indicador_responsavel'])) $Aplic->setEstado('IndicadorIdxResponsavel', getParam($_REQUEST, 'pratica_indicador_responsavel', null));
$pratica_indicador_responsavel = $Aplic->getEstado('IndicadorIdxResponsavel') != null ? $Aplic->getEstado('IndicadorIdxResponsavel') : 0;


$ordenar = getParam($_REQUEST, 'ordenar', 'projeto_nome, pratica_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');

if ($ordenar=='data') $ordenar=($ordem ? 'link_data ASC, projeto_nome ASC, tarefa_nome ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'link_data DESC, projeto_nome ASC, tarefa_nome ASC, contato_posto_valor ASC, contato_nomeguerra ASC');
if ($ordenar=='tarefa') $ordenar=($ordem ? 'projeto_nome ASC, tarefa_nome ASC, link_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC' : 'projeto_nome DESC, tarefa_nome DESC, link_data ASC, contato_posto_valor ASC, contato_nomeguerra ASC');


$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome, pratica_criterio_resultado');
$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$praticas_criterios=$sql->Lista();
$sql->limpar();

$titulo=array();
$nomes_criterios=array();

$todos=array();
foreach ((array)$praticas_criterios as $chave => $criterio) {
	$total[$chave] = 0;
	if (!$criterio['pratica_criterio_resultado']){
		$sql->adTabela('pratica_indicador');
		$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id =pratica_item.pratica_item_criterio');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		}
	else{
		$sql->adTabela('pratica_indicador_nos_marcadores');
		$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
		$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_indicador_nos_marcadores.pratica_marcador_id');
		$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
		$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
		$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
		$sql->adOnde('pratica_indicador_resultado=1');
		$sql->adCampo('count(DISTINCT pratica_indicador.pratica_indicador_id)');
		}
		
	$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.(int)$dept_id);	
	$sql->adOnde('pratica_criterio_id='.(int)$criterio['pratica_criterio_id']);
	if ($pratica_indicador_responsavel) $sql->adOnde('pratica_indicador_responsavel='.(int)$pratica_indicador_responsavel);
	$soma=$sql->Resultado();
	$sql->limpar();
	$nomes_criterios[] = $criterio['pratica_criterio_nome'].'('.$soma.')';
	}


if ($pratica_modelo_id && isset($praticas_criterios[$tab-1]['pratica_criterio_resultado']) && !$praticas_criterios[$tab-1]['pratica_criterio_resultado']){
	//todos criterios menos resultado
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
	$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id =pratica_nos_marcadores.pratica');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.(int)$dept_id);	
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	if ($pratica_indicador_responsavel) $sql->adOnde('pratica_indicador_responsavel='.(int)$pratica_indicador_responsavel);
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_oque, pratica_indicador_sentido, pratica_indicador_acesso, pratica_indicador_cor, pratica_indicador_requisito_descricao, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador');
	if ($tab && isset($praticas_criterios[$tab-1]['pratica_criterio_id'])) $sql->adOnde('pratica_criterio_id='.(int)$praticas_criterios[$tab-1]['pratica_criterio_id']);
	$indicadores=$sql->Lista();
	$sql->limpar();
	}
elseif ($pratica_modelo_id && isset($praticas_criterios[$tab-1]['pratica_criterio_resultado']) && $praticas_criterios[$tab-1]['pratica_criterio_resultado']){
	//resultados
	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	if ($pratica_indicador_responsavel) $sql->adOnde('pratica_indicador_responsavel='.(int)$pratica_indicador_responsavel);
	$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.(int)$dept_id);	
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_resultado=1');
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_oque, pratica_indicador_sentido, pratica_indicador_acesso, pratica_indicador_cor, pratica_indicador_requisito_descricao, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador');
	$indicadores=$sql->Lista();
	$sql->limpar();
	}	
elseif ($pratica_modelo_id){
	//todos com Modelos
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_indicador.pratica_indicador_pratica=pratica_nos_marcadores.pratica');
	$sql->esqUnir('praticas', 'praticas', 'praticas.pratica_id =pratica_nos_marcadores.pratica');
	$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
	$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
	$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
	$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.(int)$dept_id);	
	$sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
	if ($pratica_indicador_responsavel) $sql->adOnde('pratica_indicador_responsavel='.(int)$pratica_indicador_responsavel);
	$sql->adOnde('pratica_indicador_resultado=0');
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_oque, pratica_indicador_sentido, pratica_indicador_acesso, pratica_indicador_cor, pratica_indicador_requisito_descricao, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador');
	$indicadores=$sql->Lista();
	$sql->limpar();
	

	$sql->adTabela('pratica_indicador_nos_marcadores');
	$sql->esqUnir('pratica_indicador', 'pratica_indicador', 'pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	if ($pratica_indicador_responsavel) $sql->adOnde('pratica_indicador_responsavel='.(int)$pratica_indicador_responsavel);
	$sql->esqUnir('pratica_indicador_depts', 'pratica_indicador_depts', 'pratica_indicador_depts.pratica_indicador_id=pratica_indicador.pratica_indicador_id');
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.(int)$dept_id);	
	$sql->adOnde('pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id);
	$sql->adOnde('pratica_indicador_resultado=1');
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_oque, pratica_indicador_sentido, pratica_indicador_acesso, pratica_indicador_cor, pratica_indicador_requisito_descricao, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador');
	$resultados=$sql->Lista();
	$sql->limpar();
	$indicadores=$indicadores+$resultados;
	
	
	}
else{
	//todos sem modelo
	$sql->adTabela('pratica_indicador');
	$sql->esqUnir('pratica_indicador_requisito', 'pratica_indicador_requisito','pratica_indicador_requisito.pratica_indicador_requisito_id=pratica_indicador.pratica_indicador_requisito');
	if ($cia_id) $sql->adOnde('pratica_indicador_cia='.(int)$cia_id);
	if ($dept_id) $sql->adOnde('pratica_indicador_depts.dept_id='.(int)$dept_id);	
	if ($pratica_indicador_responsavel) $sql->adOnde('pratica_indicador_responsavel='.(int)$pratica_indicador_responsavel);
	$sql->adCampo('DISTINCT pratica_indicador.pratica_indicador_id, pratica_indicador_nome, pratica_indicador_requisito_oque, pratica_indicador_sentido, pratica_indicador_acesso, pratica_indicador_cor, pratica_indicador_requisito_descricao, pratica_indicador_responsavel, (SELECT COUNT(pratica_marcador_id) FROM pratica_indicador_nos_marcadores WHERE pratica_indicador_nos_marcadores.pratica_indicador_id=pratica_indicador.pratica_indicador_id AND pratica_criterio.pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador, pratica_indicador_agrupar');
	$indicadores=$sql->Lista();
	$sql->limpar();
	}	

$sql->limpar();

if ($Aplic->profissional) require_once BASE_DIR.'/modulos/projetos/template_pro.class.php';
$ata_ativo=$Aplic->modulo_ativo('atas');
if ($ata_ativo) require_once BASE_DIR.'/modulos/atas/funcoes.php';
$swot_ativo=$Aplic->modulo_ativo('swot');
if ($swot_ativo) require_once BASE_DIR.'/modulos/swot/swot.class.php';
$operativo_ativo=$Aplic->modulo_ativo('operativo');
if ($operativo_ativo) require_once BASE_DIR.'/modulos/operativo/funcoes.php';
$problema_ativo=$Aplic->modulo_ativo('problema');
if ($problema_ativo) require_once BASE_DIR.'/modulos/problema/funcoes.php';
$agrupamento_ativo=$Aplic->modulo_ativo('agrupamento');
if($agrupamento_ativo) require_once BASE_DIR.'/modulos/agrupamento/funcoes.php';
$patrocinador_ativo=$Aplic->modulo_ativo('patrocinadores');
if($patrocinador_ativo) require_once BASE_DIR.'/modulos/patrocinadores/patrocinadores.class.php';
$tr_ativo=$Aplic->modulo_ativo('tr');

include_once (BASE_DIR.'/modulos/praticas/indicadores_ver_idx.php'); 

?>

