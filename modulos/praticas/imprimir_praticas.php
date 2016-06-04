<?php
/*
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/
 
/********************************************************************************************
		
gpweb\modulos\praticas\imprimir_praticas.php		

Exibir pagina web com os dados das pr�ticas de gest�o para impress�o																																									
																																												
********************************************************************************************/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');
echo '<html><head><LINK REL="SHORTCUT ICON" href="estilo/rondon/imagens/organizacao/10/favicon.ico"><link rel="stylesheet" type="text/css" href="estilo/rondon/estilo_'.$config['estilo_css'].'.css"></head><body>';
if (!$dialogo) $Aplic->salvarPosicao();
require_once ($Aplic->getClasseModulo('cias'));


$tab = getParam($_REQUEST, 'tab', 0);
$cia_id = getParam($_REQUEST, 'cia_id', $Aplic->usuario_cia);
$dept_id = getParam($_REQUEST, 'dept_id', 0);

if (isset($_REQUEST['praticatextobusca'])) $Aplic->setEstado('praticatextobusca', getParam($_REQUEST, 'praticatextobusca', null));
$pesquisar_texto = ($Aplic->getEstado('praticatextobusca') ? $Aplic->getEstado('praticatextobusca') : '');

$tabAtualId = $tab;

if (isset($_REQUEST['pratica_modelo_id'])) $Aplic->setEstado('pratica_modelo_id', getParam($_REQUEST, 'pratica_modelo_id', null));
$pratica_modelo_id = ($Aplic->getEstado('pratica_modelo_id') !== null ? $Aplic->getEstado('pratica_modelo_id') : null);


	
$ordemDir = $Aplic->getEstado('PraticaIdxOrdemDir') ? $Aplic->getEstado('PraticaIdxOrdemDir') : 'asc';
if (isset($_REQUEST['ordemPor'])) {
	if ($Aplic->getEstado('PraticaIdxOrdemDir') == 'asc') $ordemDir = 'desc';
	else $ordemDir = 'asc';
	$Aplic->setEstado('PraticaIdxOrdemPor', getParam($_REQUEST, 'ordemPor', null));
	}
$ordenarPor = $Aplic->getEstado('PraticaIdxOrdemPor') ? $Aplic->getEstado('PraticaIdxOrdemPor') : 'projeto_data_fim';
$Aplic->setEstado('PraticaIdxOrdemDir', $ordemDir);


if (isset($_REQUEST['pratica_responsavel'])) $Aplic->setEstado('PraticaIdxResponsavel', getParam($_REQUEST, 'pratica_responsavel', null));
$pratica_responsavel = $Aplic->getEstado('PraticaIdxResponsavel') !== null ? $Aplic->getEstado('PraticaIdxResponsavel') : 0;


$ordenar = getParam($_REQUEST, 'ordenar', 'pratica_nome');
$ordem = getParam($_REQUEST, 'ordem', '0');



$sql = new BDConsulta();
$sql->adTabela('pratica_criterio');
$sql->adCampo('pratica_criterio_id, pratica_criterio_nome');
if ($pratica_modelo_id) $sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
$sql->adOnde('pratica_criterio_resultado=0');
$praticas_criterios=$sql->Lista();
$sql->limpar();

$titulo=array();


$sql->adTabela('pratica_requisito');
$sql->esqUnir('praticas','praticas', 'praticas.pratica_id=pratica_requisito.pratica_id');
$sql->adCampo('DISTINCT ano');
if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
$sql->adOrdem('ano');
$anos=$sql->listaVetorChave('ano','ano');
$sql->limpar();

$ultimo_ano=$anos;
$ultimo_ano=array_pop($ultimo_ano);

$ano = ($Aplic->getEstado('IdxPraticaAno') !== null && isset($anos[$Aplic->getEstado('IdxPraticaAno')])? $Aplic->getEstado('IdxPraticaAno') : $ultimo_ano);




$sql->adTabela('praticas');
$sql->esqUnir('pratica_requisito', 'pratica_requisito', 'pratica_requisito.pratica_id = praticas.pratica_id');
$sql->esqUnir('pratica_nos_marcadores', 'pratica_nos_marcadores', 'pratica_nos_marcadores.pratica =praticas.pratica_id');
$sql->esqUnir('pratica_marcador', 'pratica_marcador', 'pratica_marcador.pratica_marcador_id =pratica_nos_marcadores.marcador');
$sql->esqUnir('pratica_item', 'pratica_item', 'pratica_item.pratica_item_id =pratica_marcador.pratica_marcador_item');
$sql->esqUnir('pratica_criterio', 'pratica_criterio', 'pratica_criterio.pratica_criterio_id=pratica_item.pratica_item_criterio');
$sql->esqUnir('pratica_depts', 'pratica_depts', 'pratica_depts.pratica_id=praticas.pratica_id');
if ($ano) $sql->adOnde('pratica_requisito.ano = '.(int)$ano);
if ($cia_id) $sql->adOnde('pratica_cia='.(int)$cia_id);
if ($dept_id) $sql->adOnde('pratica_depts.dept_id='.(int)$dept_id);
if ($pratica_modelo_id && $tab) $sql->adOnde('pratica_criterio_modelo='.(int)$pratica_modelo_id);
if ($pesquisar_texto)$sql->adOnde('pratica_nome LIKE \'%'.$pesquisar_texto.'%\' OR pratica_descricao LIKE \'%'.$pesquisar_texto.'%\'');
if ($usuario_id) $sql->adOnde('pratica_responsavel='.(int)$usuario_id);
$sql->adCampo('DISTINCT praticas.pratica_id, pratica_acesso, pratica_nome, pratica_descricao, pratica_cor, pratica_responsavel');
if ($pratica_modelo_id) $sql->adCampo('(SELECT COUNT(marcador) FROM pratica_nos_marcadores WHERE pratica=praticas.pratica_id AND pratica_criterio_modelo='.(int)$pratica_modelo_id.') AS qnt_marcador');
if ($tab && isset($praticas_criterios[$tab-1]['pratica_criterio_id'])) $sql->adOnde('pratica_criterio_id='.$praticas_criterios[$tab-1]['pratica_criterio_id']);
$sql->adOrdem($ordenar.($ordem ? ' DESC' : ' ASC'));
$praticas=$sql->Lista();
$sql->limpar();

include_once (BASE_DIR.'/modulos/praticas/praticas_ver_idx.php'); 

?>

