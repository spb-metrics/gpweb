<?php 
/*
Copyright [2008] -  Sérgio Fernandes Reinert de Lima
Este arquivo é parte do programa gpweb
O gpweb é um software livre; você pode redistribuí-lo e/ou modificá-lo dentro dos termos da Licença Pública Geral GNU como publicada pela Fundação do Software Livre (FSF); na versão 2 da Licença.
Este programa é distribuído na esperança que possa ser  útil, mas SEM NENHUMA GARANTIA; sem uma garantia implícita de ADEQUAÇÃO a qualquer  MERCADO ou APLICAÇÃO EM PARTICULAR. Veja a Licença Pública Geral GNU/GPL em português para maiores detalhes.
Você deve ter recebido uma cópia da Licença Pública Geral GNU, sob o título "licença GPL 2.odt", junto com este programa, se não, acesse o Portal do Software Público Brasileiro no endereço www.softwarepublico.gov.br ou escreva para a Fundação do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/


if (!defined('BASE_DIR')) die('Você não deveria acessar este arquivo diretamente.');

if (!($podeAcessar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$editar=($Aplic->usuario_super_admin || $Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_familia'));

//para localização da familia
$sem_impressao=getParam($_REQUEST, 'sem_impressao', 0);


include_once BASE_DIR.'/modulos/social/familia.class.php';
$social_familia_id = intval(getParam($_REQUEST, 'social_familia_id', 0));
$vetor_animal=getSisValor('Animais');
$vetor_producao=getSisValor('FinalidadeProducao');
$vetor_cultura=getSisValor('Cultura');
$vetor_sistema=getSisValor('SistemaIrrigacao');


$sql = new BDConsulta;


$sql->adTabela('social_familia');
$sql->esqUnir('estado', 'estado', 'social_familia_estado=estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'social_familia_municipio=municipio_id');
$sql->esqUnir('social_comunidade', 'social_comunidade', 'social_familia_comunidade=social_comunidade_id');
$sql->adCampo('estado_nome, municipio_nome, social_comunidade_nome, municipio_id');
$sql->adOnde('social_familia_id='.$social_familia_id);
$endereco= $sql->Linha();
$sql->limpar();

$sequencial=array();
for ($i = 0; $i <= 20; $i++) $sequencial[$i]=$i;

$obj = new CFamilia;
$obj->load($social_familia_id);

if (!$dialogo){
	$Aplic->salvarPosicao();
	if (isset($_REQUEST['tab'])) $Aplic->setEstado('FamiliaVerTab', getParam($_REQUEST, 'tab', null));
	$tab = $Aplic->getEstado('FamiliaVerTab') !== null ? $Aplic->getEstado('FamiliaVerTab') : 0;
	$msg = '';
	
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_beneficiario'].' '.ucfirst($config['beneficiario']), '../../../modulos/Social/imagens/familia.gif', $m, $m.'.'.$a);
	if ($editar)$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap">'.dica('Nov'.$config['genero_beneficiario'].' '.ucfirst($config['beneficiario']), 'Cadastre uma nov'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'<a class="botao" href="javascript: void(0)" onclick="javascript:url_passar(0, \'m=social&a=familia_editar\');" ><span>beneficiário</span></a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaCelula('<table><tr><td nowrap="nowrap" align="center">'.dica('Imprimir '.ucfirst($config['beneficiario']), 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o dados cadastrados d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a='.$a.'&social_familia_id='.$social_familia_id.'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr></table>');
	$botoesTitulo->adicionaBotao('m=social&a=familia_lista', 'lista','','Lista de '.ucfirst($config['beneficiario']),'Clique neste botão para visualizar a lista de beneficiário.');
	if ($editar) {
		$botoesTitulo->adicionaBotao('m=social&a=familia_editar&social_familia_id='.$social_familia_id, 'editar','','Editar '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.ucfirst($config['beneficiario']),'Editar os detalhes d'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.');
		$botoesTitulo->adicionaBotao('m=social&a=familia_acao&social_familia_id='.$social_familia_id, 'ação social','','Ação Social','Incluir ou alterar o status de uma ação social vinculada à beneficiário.');
		}
	if ($editar) $botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir','Excluir '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].' do sistema.');	
	$botoesTitulo->mostrar();
	
	echo '<form name="env" method="post">';
	echo '<input type="hidden" name="m" value="social" />';
	echo '<input type="hidden" name="a" value="social_familia_ver" />';
	echo '<input type="hidden" name="social_familia_id" value="'.$social_familia_id.'" />';
	echo '<input type="hidden" name="del" value="" />';
	echo '<input type="hidden" name="modulo" value="" />';
	echo '<input type="hidden" name="sem_cabecalho" value="" />';
	echo '<input type="hidden" name="social_acao_arquivo_id" value="" />';
	echo '<input type="hidden" name="pasta" value="acoes" />';
	echo '</form>';
	echo estiloTopoCaixa();
	}

echo '<table id="tblObjetivos" cellpadding=0 cellspacing=1 '.(!$dialogo ? 'width="100%" class="std"' : 'width="740"').' >';
echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informações básicas sobre '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
echo '<tr><td align="right" width="150" nowrap="nowrap">'.dica('Nome Completo', 'Nome completo d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Nome completo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_nome.'</td></tr>';
echo '<tr><td align="right">'.dica('Sexo', 'O sexo d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Sexo:'.dicaF().'</td><td  class="realce">'.selecionaVetorExibicao(getSisValor('Sexo'), $obj->social_familia_sexo).'</td></tr>';

echo '<tr><td align="right">'.dica('Escolaridade do Responsável', 'A escolaridade d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Escolaridade:'.dicaF().'</td><td class="realce">'.selecionaVetorExibicao(getSisValor('Escolaridade'), $obj->social_familia_escolaridade).'</td></tr>';

echo '<tr><td align="right">'.dica('Chefe de Família', 'O beneficiário é chefe de família.').'Chefe de família:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_chefe ? 'Sim' : 'Não').'</td></tr>';

if ($obj->social_familia_via_acesso_casa) echo '<tr><td align="right">'.dica('Vias de Acesso à Casa', 'Vias de Acesso à casa d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Vias de Acesso à casa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('SocialViaAcessoCasa'), $obj->social_familia_via_acesso_casa).'</td></tr>';




if (!$obj->social_familia_chefe) echo '<tr><td align="right">'.dica('Sexo do Chefe do Família', 'O sexo do chefe de família.').'Sexo do chefe de família:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('Sexo'), $obj->social_familia_sexo_chefe).'</td></tr>';
if (!$obj->social_familia_chefe && $obj->social_familia_nome_chefe) echo '<tr><td align="right" width="150">'.dica('Nome Completo do Chefe do Família', 'Nome completo do chefe de família.').'Nome do chefe de família:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_nome_chefe.'</td></tr>';
if ($obj->social_familia_distancia!=0) echo '<tr><td align="right">'.dica('Distância', 'A distância em kilômetros até a sede do município').'Distância:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_distancia!=0 ? number_format($obj->social_familia_distancia, 2, ',', '.') : '').'&nbsp;Km</td></tr>';
if ($obj->social_familia_latitude && $obj->social_familia_longitude) echo '<tr><td align="right" nowrap="nowrap">'.dica('Coordenadas Geográficas', 'As coordenadas geográficas em graus decimais, de onde se encontra '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Coordenadas:'.dicaF().'</td><td class="realce" width="100%">'.$obj->social_familia_latitude.'º '.$obj->social_familia_longitude.'º&nbsp;'.(!$dialogo ? '<a href="javascript: void(0);" onclick="popCoordenadas('.$obj->social_familia_latitude.', '.$obj->social_familia_longitude.');">'.imagem('icones/coordenadas_p.png', 'Visualizar Coordenadas', 'Clique neste ícone '.imagem('icones/coordenadas_p.png').' para visualizar em um mapa as coordenadas geográficas.').'</a>' : '' ).'</td></tr>';
$data = new CData($obj->social_familia_nascimento);
if ($obj->social_familia_nascimento) echo '<tr><td align="right">'.dica('Data de Nascimento', 'A data de nascimento d'.$config['genero_beneficiario'].' '.$config['beneficiario'].' no formato <b>(dd/mm/aaaa)</b>.').'Nascimento:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.($obj->social_familia_nascimento && $obj->social_familia_nascimento !='0000-00-00' ? $data->format($Aplic->getPref('datacurta')) : '').'</td></tr>';
if ($obj->social_familia_cpf) echo '<tr><td align="right">'.dica('CPF', 'O CPF d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'CPF:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_cpf.'</td></tr>';
if ($obj->social_familia_cnpj) echo '<tr><td align="right">'.dica('CNPJ', 'O CNPJ d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'CNPJ:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_cnpj.'</td></tr>';
if ($obj->social_familia_cnes) echo '<tr><td align="right">'.dica('CNES', 'O CNES d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'CNES:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_cnes.'</td></tr>';
if ($obj->social_familia_inep) echo '<tr><td align="right">'.dica('INEP', 'O INEP d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'INEP:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_inep.'</td></tr>';

if ($obj->social_familia_grau_parentesco) echo '<tr><td align="right">'.dica('Parentesco', 'Grau Parentesco d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Grau Parentesco:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_grau_parentesco.'</td></tr>';

if ($obj->social_familia_rg) echo '<tr><td align="right">'.dica('RG', 'O RG d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'RG:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_rg.($obj->social_familia_orgao? ' - '.$obj->social_familia_orgao : '').'</td></tr>';
if ($obj->social_familia_endereco1) echo '<tr><td align="right">'.dica('Endereço', 'O enderço d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_endereco1.'</td></tr>';
if ($obj->social_familia_endereco2) echo '<tr><td align="right">'.dica('Complemento do Endereço', 'O complemento do enderço d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Complemento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_endereco2.'</td></tr>';
if ($endereco['social_comunidade_nome']) echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Comunidade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['social_comunidade_nome'].'</td></tr>';
if ($endereco['municipio_nome']) echo '<tr><td align="right">'.dica('Município', 'O município d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Município:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_nome'].'</td></tr>';
if ($endereco['municipio_id']) echo '<tr><td align="right">'.dica('Código do Município', 'O código pelo IBGE do município d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Código IBGE:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_id'].'</td></tr>';
if ($endereco['estado_nome']) echo '<tr><td align="right">'.dica('Estado', 'O Estado d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Estado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['estado_nome'].'</td></tr>';
if ($obj->social_familia_estado_civil) echo '<tr><td align="right">'.dica('Estado civil', 'O estado civil d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Estado civil:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('EstadoCivil'), $obj->social_familia_estado_civil).'</td></tr>';
if ($obj->social_familia_conjuge) echo '<tr><td align="right">'.dica('Nome Completo do Cônjuge', 'Nome completo do cônjuge.').'Cônjuge:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_conjuge.'</td></tr>';
if ($obj->social_familia_conjuge_cpf) echo '<tr><td align="right">'.dica('CPF do Cônjuge', 'CPF do Cônjuge').'Cônjuge CPF:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_conjuge_cpf.'</td></tr>';
if ($obj->social_familia_conjuge_rg) echo '<tr><td align="right">'.dica('RG do Cônjuge', 'RG do Cônjuge').'Cônjuge RG:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_conjuge_rg.'</td></tr>';
echo '<tr><td align="right">'.dica('Entrevistado', 'O Entrevistado d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Entrevistado:'.dicaF().'</td><td class="realce">'.selecionaVetorExibicao(getSisValor('SocialEntrevistado'), $obj->social_familia_entrevistado).'</td></tr>';
if ($obj->social_familia_escolaridade) echo '<tr><td align="right">'.dica('Escolaridade', 'A escolaridade d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Escolaridade:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('Escolaridade'), $obj->social_familia_escolaridade).'</td></tr>';

echo '<tr><td align="right">'.dica('Filhos', 'Quantos filhos vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Filhos:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao($sequencial, $obj->social_familia_filhos).'</td></tr>';
echo '<tr><td align="right">'.dica('Filhos de 0 a 6 Anos', 'Quantos filhos vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].' com idade até 6 anos.').'Filhos (até 6 anos):'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao($sequencial, $obj->social_familia_crianca_seis).'</td></tr>';
echo '<tr><td align="right">'.dica('Crianças e Adolecentes na Escola', 'Quantas crianças e adolecentes que vivem com '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].' frequentam escola.').'Na escola:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao($sequencial, $obj->social_familia_crianca_escola).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Moradores com mais de 65 anos', 'Quantas pessoas com mais de 65 anos vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Mais de 65 anos:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao($sequencial, $obj->social_familia_sessenta_cinco).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Moradores com Deficiência Física e Mental', 'Quantas pessoas portadores de deficiência física e mental vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Deficiência física e mental:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao($sequencial, $obj->social_familia_deficiente_mental).'</td></tr>';
if ($obj->social_familia_tel) echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Principal', 'O telefone principal d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Telefone Principal:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_dddtel ? '('.$obj->social_familia_dddtel.') ' : '').$obj->social_familia_tel.'</td></tr>';
if ($obj->social_familia_tel2) echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Reserva', 'O telefone residencial d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Telefone Reserva:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_dddtel2 ? '('.$obj->social_familia_dddtel2.') ' : '').$obj->social_familia_tel2.'</td></tr>';
if ($obj->social_familia_cel) echo '<tr><td align="right" nowrap="nowrap">'.dica('Celular', 'O celular d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Celular:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_dddcel ? '('.$obj->social_familia_dddcel.') ' : '').$obj->social_familia_cel.'</td></tr>';
if ($obj->social_familia_email) echo '<tr><td align="right">'.dica('e-mail', 'O e-mail d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'e-mail:'.dicaF().'</td><td nowrap="nowrap" class="realce">'.$obj->social_familia_email.'</td></tr>';
$valores=valores('organizacao_social', $social_familia_id);
if (count($valores)) echo '<tr><td align="right">'.dica('Participação em Organizações Sociais', 'Em quais organizações sociais participa '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'Org. sociais:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('OrganizacaoSocial'), 'organizacao_social', $valores).'</td></tr>';
echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Socioeconômicos','Informações socioeconômicas sobre '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Socioeconômicos</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';

echo '<tr><td align="right" width="150">'.dica('Possui Bolsa Família', 'O beneficiário recebe Bolsa Família.').'Possui Bolsa Família:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_bolsa ? 'Sim' : 'Não').'</td></tr>';
if ($obj->social_familia_nis) echo '<tr><td align="right">'.dica('NIS', 'O NIS d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'NIS:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_nis.'</td></tr>';
if ($obj->social_familia_beneficio_inss) echo '<tr><td align="right">'.dica('Benefício do INSS', 'Caso '.$config['genero_beneficiario'].' '.$config['beneficiario'].' seja aposentado, preencha o número do benefício do INSS').'Benefício INSS:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_beneficio_inss.'</td></tr>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessita do Bolsa Família', 'O beneficiário necessita do Bolsa Família.').'Necessita do Bolsa Família:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_necessita_bolsa ? 'Sim' : 'Não').'</td></tr>';
if ($obj->social_familia_tipo_residencia) echo '<tr><td align="right" width="150">'.dica('Tipo de Residência', 'O tipo de residência d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Tipo de residência:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('TipoResidencia'),  $obj->social_familia_tipo_residencia).'</td></tr>';

echo '<tr><td align="right">'.dica('Material da Coberta', 'O material da coberta da residência d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Material da coberta:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('TipoCobertaMaterial'), $obj->social_familia_tipo_coberta_material).'</td></tr>';
echo '<tr><td align="right">'.dica('Estado da Coberta', 'O tipo de coberta da residência d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Estado da coberta:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('TipoCoberta'), $obj->social_familia_tipo_coberta).'</td></tr>';

//if ($obj->social_familia_comprimento!=0) echo '<tr><td align="right">'.dica('Comprimento', 'O comprimento da residência').'Comprimento:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_comprimento!=0 ? number_format($obj->social_familia_comprimento, 2, ',', '.') : '').'</td></tr>';
//if ($obj->social_familia_largura!=0) echo '<tr><td align="right">'.dica('Largura', 'A largura da residência').'Largura:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_largura!=0 ? number_format($obj->social_familia_largura, 2, ',', '.') : '').'</td></tr>';
//if ($obj->social_familia_lixo) echo '<tr><td align="right">'.dica('Lixo', 'A forma de descarte do lixo pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Lixo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('Lixo'),  $obj->social_familia_lixo).'</td></tr>';

echo '<tr><td align="right">'.dica('Possui Energia', 'O beneficiário tem energia eletrifica.').'Possui energia:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_eletrificacao ? 'Sim' : 'Não').'</td></tr>';

if ($obj->social_familia_eletrificacao) echo '<tr><td align="right">'.dica('Tipo de energia', 'O beneficiário tem qual tipo de energia.').'Tipo de energia:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('TipoEnergia'), $obj->social_familia_tipo_energia).'</td></tr>';

echo '<tr><td align="right">'.dica('A Casa Tem Banheiro', 'O beneficiário tem esgotamento sanitário.').'A casa tem banheiro:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_sanitario ? 'Sim' : 'Não').'</td></tr>';

echo '<tr><td align="right">'.dica('Tipo de Esgotamento', 'O tipo de Esgotamento d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Tipo de esgotamento:'.dicaF().'</td><td class="realce" >'.selecionaVetorExibicao(getSisValor('EsgotamentoSanitario'), $obj->social_familia_esgoto).'</td></tr>';

echo '<tr><td align="right">'.dica('Destino do Lixo', 'A forma de descarte do lixo pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Destino do lixo:'.dicaF().'</td><td class="realce" >'.selecionaVetorExibicao(getSisValor('Lixo'), $obj->social_familia_lixo).'</td></tr>';

echo '<tr><td align="right">'.dica('Possui Cisterna', 'O beneficiário tem cisterna.').'Possui cisterna:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_cisterna ? 'Sim' : 'Não').'</td></tr>';

$valores=valores('Social_Responsavel_Auxilio', $social_familia_id);
if (count($valores)) echo '<tr><td align="right">'.dica('Responsável Recebe Benefício', 'Responsável recebe benefício de programas sociais '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'Recebe benefício:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('SocialResponsavelAuxilio'), 'Social_Responsavel_Auxilio', $valores).'</td></tr>';



//echo '<tr><td align="right">'.dica('Sanitário', 'O beneficiário tem sanitário.').'Sanitário:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_sanitario ? 'Sim' : 'Não').'</td></tr>';
//if ($obj->social_familia_tratamento_agua) echo '<tr><td align="right">'.dica('Trata a Água', 'O beneficiário trata a água.').'Trata a água:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('TratamentoAgua'), $obj->social_familia_tratamento_agua).'</td></tr>';
//if ($obj->social_familia_tratamento_agua_frequencia) echo '<tr><td align="right">'.dica('Frequência de Tratamento da Água', 'A frequência de tratamento da água pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Freq. trat. água:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('FrequenciaTratamento'),$obj->social_familia_tratamento_agua_frequencia).'</td></tr>';
//if ($obj->social_familia_distancia_agua!=0) echo '<tr><td align="right">'.dica('Distância Percorrida para Pegar Água', 'A distância percorrida para pegar água em kilômetros.').'Distância da água:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_distancia_agua!=0 ? number_format($obj->social_familia_distancia_agua, 2, ',', '.') : '').'&nbsp;Km</td></tr>';
//$valores=valores('agua_beber', $social_familia_id);
//if (count($valores)) echo '<tr><td align="right">'.dica('Fonte de Água para Beber', 'As fonte de água disponíveis para beber pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Beber:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('FonteAgua'), 'agua_beber', $valores).'</td></tr>';
//$valores=valores('agua_banho', $social_familia_id);
//if (count($valores)) echo '<tr><td align="right">'.dica('Fonte de Água para Banho', 'As fonte de água disponíveis para banho pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Banho:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('FonteAgua'), 'agua_banho', $valores).'</td></tr>';
//$valores=valores('agua_cozinhar', $social_familia_id);
//if (count($valores)) echo '<tr><td align="right">'.dica('Fonte de Água para Cozinhar', 'As fonte de água disponíveis para cozinhar pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Cozinhar:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('FonteAgua'), 'agua_cozinhar', $valores).'</td></tr>';
//$valores=valores('agua_lavar', $social_familia_id);
//if (count($valores)) echo '<tr><td align="right">'.dica('Fonte de Água para Lavar Roupa', 'As fonte de água disponíveis para lavar roupa pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Lavar roupa:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('FonteAgua'), 'agua_lavar', $valores).'</td></tr>';

if ($obj->social_familia_ocupacao) echo '<tr><td align="right">'.dica('Ocupação', 'A ocupação econômica d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Ocupação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('Ocupacao'), $obj->social_familia_ocupacao).'</td></tr>';
if ($obj->social_familia_principal_renda) echo '<tr><td align="right">'.dica('Fonte de Renda', 'A principal fonte de renda d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Fonte de renda:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('FonteRenda'), $obj->social_familia_principal_renda).'</td></tr>';
if ($obj->social_familia_renda_periodo) echo '<tr><td align="right">'.dica('Período da Renda', 'A periodicidade da principal fonte de renda d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Período da renda:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('PeriodoRenda'),$obj->social_familia_renda_periodo).'</td></tr>';
if ($obj->social_familia_renda_valor) echo '<tr><td align="right">'.dica('Valor Mensal da Renda', 'O valor mensal da renda d'.$config['genero_beneficiario'].' '.$config['beneficiario'].' em reais.').'Valor da renda:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_renda_valor!=0 ? number_format($obj->social_familia_renda_valor, 2, ',', '.') : '').'</td></tr>';
if ($obj->social_familia_nr_dependentes) echo '<tr><td align="right">'.dica('Número de Dependentes', 'Número de dependentes.').'Nr de dependentes:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_nr_dependentes.'</td></tr>';
if ($obj->social_familia_renda_capita) echo '<tr><td align="right">'.dica('Renda per Capita', 'O valor mensal da renda per capita.').'Renda per capita:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_renda_capita!=0 ? number_format($obj->social_familia_renda_capita, 2, ',', '.') : '').'</td></tr>';

$valores=valores('agua_fonte', $social_familia_id);
if (count($valores)) echo '<tr><td align="right">'.dica('Fonte de Água', 'As principais fontes de água da propriedade.').'Fonte de água:'.dicaF().'</td><td class="realce">'.selecionaVetorMultiploExibicao(getSisValor('BeberAgua','','','sisvalor_id'), 'agua_fonte', $valores).'</td></tr>';


echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Produtivos','Informações sobre produções econômicas administradas pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Produtivos</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
if ($obj->social_familia_uso_terra) echo '<tr><td align="right"  width="150">'.dica('Uso da Terra', 'O uso da terra pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Uso da terra:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('UsoTerra'),  $obj->social_familia_uso_terra).'</td></tr>';
echo '<tr><td align="right">'.dica('Mão de Obra Familiar', 'Número de familiares que trabalham na propriedade').'Mão de obra familiar:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_mao_familiar.'</td></tr>';
echo '<tr><td align="right">'.dica('Mão de Obra Contratada', 'Número de pessoas contratadas que trabalham na propriedade').'Mão de obra contratada:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_mao_contratada.'</td></tr>';
if ($obj->social_familia_area_propriedade!=0) echo '<tr><td align="right">'.dica('Área Total da Propriedade', 'Área total aproximada da propriedade (casa  + terreno) em hectares.').'Área total:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_area_propriedade!=0 ? number_format($obj->social_familia_area_propriedade, 2, ',', '.') : '').'&nbsp;ha</td></tr>';
if ($obj->social_familia_area_producao!=0) echo '<tr><td align="right">'.dica('Área de Produção da Propriedade', 'Área de produção da propriedade em hectares.').'Área de produção:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_area_producao!=0 ? number_format($obj->social_familia_area_producao, 2, ',', '.') : '').'&nbsp;ha</td></tr>';


$sql->adTabela('social_familia_producao');
$sql->adCampo('social_familia_producao_cultura, social_familia_producao_finalidade, social_familia_producao_quantidade');
$sql->adOnde('social_familia_producao_familia = '.(int)$social_familia_id);
$sql->adOnde('social_familia_producao_cultura IS NOT NULL');
$linhas=$sql->Lista();
$sql->limpar();
$saida='';
foreach($linhas as $linha) {
	$saida.='<tr><td>'.(isset($vetor_cultura[$linha['social_familia_producao_cultura']]) ? $vetor_cultura[$linha['social_familia_producao_cultura']] : '&nbsp;').'</td>';
	$saida.='<td>'.($vetor_producao[$linha['social_familia_producao_finalidade']] ? $vetor_producao[$linha['social_familia_producao_finalidade']] : '&nbsp;').'</td>';
	$saida.='<td>'.($linha['social_familia_producao_quantidade'] ? number_format($linha['social_familia_producao_quantidade'], 2, ',', '.') : '&nbsp;').'</td></tr>';
	}
if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Cultura</th><th>Finalidade</th><th>Área</th></tr>'.$saida.'</table>';
if ($saida) echo '<tr><td align="right">'.dica('Principais Culturas', 'As principais culturas da propriedade.').'Principais culturas:'.dicaF().'</td><td class="realce" style="text-align: justify;"><div id="principais_culturas">'.$saida.'</div></td></tr>';

$sql->adTabela('social_familia_producao');
$sql->adCampo('social_familia_producao_animal, social_familia_producao_finalidade, social_familia_producao_quantidade');
$sql->adOnde('social_familia_producao_familia = '.(int)$social_familia_id);
$sql->adOnde('social_familia_producao_animal IS NOT NULL');
$linhas=$sql->Lista();
$sql->limpar();
$saida='';
foreach($linhas as $linha) {
	$saida.='<tr><td>'.(isset($vetor_animal[$linha['social_familia_producao_animal']]) ? $vetor_animal[$linha['social_familia_producao_animal']] : '&nbsp;').'</td>';
	$saida.='<td>'.($vetor_producao[$linha['social_familia_producao_finalidade']] ? $vetor_producao[$linha['social_familia_producao_finalidade']] : '&nbsp;').'</td>';
	$saida.='<td>'.($linha['social_familia_producao_quantidade'] ? number_format($linha['social_familia_producao_quantidade'], 2, ',', '.') : '&nbsp;').'</td></tr>';
	}
if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Animal</th><th>Finalidade</th><th>Qnt</th</tr>'.$saida.'</table>';
if ($saida) echo '<tr><td align="right">'.dica('Principais Criações de Animais', 'As principais criações de animais da propriedade.').'Principais criações:'.dicaF().'</td><td class="realce" style="text-align: justify;"><div id="principais_animais">'.$saida.'</div></td></tr>';

echo '<tr><td align="right">'.dica('Número de '.ucfirst($config['beneficiario']), 'Número de beneficiários que poderão trabalhar na propriedade.').'Nr '.ucfirst($config['beneficiario']).':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao($sequencial, $obj->social_familia_nr_familias_trabalhar).'</td></tr>';

$valores=valores('agua_agropecuaria', $social_familia_id);
if (count($valores)) echo '<tr><td align="right">'.dica('Fonte hídrica para a agropecuária', 'As fonte hídrica para a agropecuária disponíveis à beneficiário.').'Fonte para agropecuária:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorMultiploExibicao(getSisValor('FonteAgropecuaria'), 'agua_agropecuaria', $valores).'</td></tr>';

echo '<tr><td align="right">'.dica('Irrigação', 'O beneficiário tem irrigação na propriedade.').'Irrigação:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->social_familia_irrigacao ? 'Sim' : 'Não').'</td></tr>';

$sql->adTabela('social_familia_irrigacao');
$sql->adCampo('social_familia_irrigacao_cultura, social_familia_irrigacao_sistema, social_familia_irrigacao_area');
$sql->adOnde('social_familia_irrigacao_familia = '.(int)$social_familia_id);
$linhas=$sql->Lista();
$sql->limpar();
$saida='';
foreach($linhas as $linha) {
	$saida.='<tr><td>'.(isset($vetor_cultura[$linha['social_familia_irrigacao_cultura']]) ? $vetor_cultura[$linha['social_familia_irrigacao_cultura']] : '&nbsp;').'</td>';
	$saida.='<td>'.($vetor_sistema[$linha['social_familia_irrigacao_sistema']] ? $vetor_sistema[$linha['social_familia_irrigacao_sistema']] : '&nbsp;').'</td>';
	$saida.='<td>'.($linha['social_familia_irrigacao_area'] ? number_format($linha['social_familia_irrigacao_area'], 2, ',', '.') : '&nbsp;').'</td></tr>';
	}
if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Cultura</th><th>Sistema</th><th>Área</th><th></th></tr>'.$saida.'</table>';
if ($saida) echo '<tr><td align="right">'.dica('Principais Culturas Irrigadas', 'As principais culturas irrigadas da propriedade.').'Principais irrigações:'.dicaF().'</td><td class="realce" style="text-align: justify;"><div id="principais_irrigacoes">'.$saida.'</div></td></tr>';
echo '<tr><td align="right">'.dica('Assistência Técnica', 'Recebe algum tipo de assistência técnica.').'Assistência técnica:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.selecionaVetorExibicao(getSisValor('Assistencia'), $obj->social_familia_assistencia_tecnica).'</td></tr>';
echo '</table></fieldset></td></tr>';

echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Extras','Informações extras sobre '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Extras</b>&nbsp</legend><table width="100%" cellspacing=2 cellpadding=0>';
if ($obj->social_familia_observacao) echo '<tr><td align="right" width="150">'.dica('Observações', 'Observações referentes a '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'Observações:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->social_familia_observacao.'</td></tr>';
echo '</table></fieldset></td></tr>';
	


$sql->adTabela('social_familia_lista');
$sql->adCampo('social_familia_lista_lista AS id');
$sql->adOnde('social_familia_lista_familia='.(int)$social_familia_id);
$lista_marcados=$sql->listaVetorChave('id', 'id');
$sql->limpar();

$sql->adTabela('social_familia_acao');
$sql->esqUnir('social_acao','social_acao','social_acao_id=social_familia_acao_acao');
$sql->adCampo('social_acao_id, social_acao_nome, social_familia_acao_concluido, social_familia_acao_data_previsao, social_familia_acao_codigo');
$sql->adOnde('social_familia_acao_familia='.(int)$social_familia_id);
$sql->adOrdem('social_acao_nome ASC');
$lista_acoes=$sql->Lista();
$sql->limpar();

foreach ($lista_acoes as $acao){
	
	$sql->adTabela('social_acao');
	$sql->adCampo('social_acao_codigo');
	$sql->adOnde('social_acao_id='.(int)$acao['social_acao_id']);
	$codigo=$sql->Resultado();
	$sql->limpar();
	
	
	
	
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica($acao['social_acao_nome'],'Detalhamento da ação social vinculada à beneficiário.').'&nbsp;<b>'.$acao['social_acao_nome'].'</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';
	if ($acao['social_familia_acao_codigo']) echo '<tr><td align="right" width="150">'.dica(($codigo ? $codigo : 'Código'),'Identificador da implantação desta ação no beneficiário').($codigo ? $codigo : 'Código').':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$acao['social_familia_acao_codigo'].'</td></tr>';
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Lista de Atividades','Lista de atividades da ação social vinculada à beneficiário.').'&nbsp;<b>Lista de Atividades</b>&nbsp</legend><table cellspacing=2 cellpadding=0>';
		
	echo '<tr><td><table cellpadding=0 cellspacing=0 class="tbl1"><tr><th>Atividade</th><th>Feito</th></tr>';
	$sql->adTabela('social_acao_lista');
	$sql->adCampo('social_acao_lista_id, social_acao_lista_descricao');
	$sql->adOnde('social_acao_lista_acao_id='.(int)$acao['social_acao_id']);
	$sql->adOnde('social_acao_lista_tipo=0');
	$sql->adOrdem('social_acao_lista_ordem ASC');
	$lista=$sql->Lista();
	foreach ($lista as $linha) echo '<tr><td>'.$linha['social_acao_lista_descricao'].'</td><td align="center">'.(isset($lista_marcados[$linha['social_acao_lista_id']])? '<b>X</b>' : '&nbsp;').'</td></tr>';
	echo '</table></td></tr>';
	
	if ($acao['social_familia_acao_data_previsao'] && $acao['social_familia_acao_data_previsao']!='0000-00-00') echo '<tr><td>'.dica('Previsão','Previsão de conclusão desta ação social n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'<b>Previsão</b>:'.dicaF().' '.retorna_data($acao['social_familia_acao_data_previsao'], false).'</td></tr>';

	
	echo '</table></fieldset></td></tr>';
	
	problema($acao['social_acao_id']);
	arquivos($acao['social_acao_id']);
	
	
	echo '<tr><td nowrap="nowrap" align="center">'.dica('Imprimir Termo de Recebimento', 'Clique neste ícone '.imagem('imprimir_p.png').' para imprimir o termo de recebimento.').'<a href="javascript: void(0);" onclick ="url_passar(1, \'m=social&a=social_familia_termo&social_familia_id='.$social_familia_id.'&social_acao_id='.$acao['social_acao_id'].'&dialogo=1\');">'.imagem('imprimir_p.png').'</a>'.dicaF().'</td></tr>';
	
	echo '</table></fieldset></td></tr>';
	}

$sql->adTabela('social_familia_acao_negada');
$sql->esqUnir('social_acao','social_acao','social_acao_id=social_familia_acao_negada_acao');
$sql->esqUnir('social_acao_negacao','social_acao_negacao','social_acao_negacao_id=social_familia_acao_negada_motivo');
$sql->adCampo('social_acao_id, social_acao_nome, social_acao_negacao_justificativa');
$sql->adOnde('social_familia_acao_negada_familia='.(int)$social_familia_id);
$sql->adOrdem('social_acao_nome ASC');
$lista_acoes=$sql->Lista();
$sql->limpar();	
$saida='';
foreach ($lista_acoes as $linha) $saida.='<tr><td>'.$linha['social_acao_nome'].'</td><td>'.$linha['social_acao_negacao_justificativa'].'</td></tr>';	
	
if ($saida){
	echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Negações','Lista de ações sociais que foram negadas a '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Negações</b>&nbsp</legend><table cellspacing=0 cellpadding=0 class="tbl1"><tr><th>Ação</th><th>Justificativa</th></tr>';
	echo $saida;
	echo '</table></fieldset></td></tr>';
	}
	
		
require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados('social_familia', $obj->social_familia_id, 'ver');
if ($campos_customizados->count()) {
		echo '<tr><td colspan="2">';
		$campos_customizados->imprimirHTML();
		echo '</td></tr>';
		}		

if (!$dialogo) {
	echo '</table>';
	echo estiloFundoCaixa();
	}
elseif(!$sem_impressao) {
	$data = new CData(($obj->social_familia_data ? $obj->social_familia_data : ''));
	$nome_meses=array('01'=>'janeiro', '02'=>'fevereiro', '03'=>'março', '04'=>'abril', '05'=>'maio', '06'=>'junho', '07'=>'julho', '08'=>'agosto', '09'=>'setembro', '10'=>'outubro', '11'=>'novembro', '12'=>'dezembro');
	$dia_mes=array('01'=>'1º', '02'=>'2', '03'=>'3', '04'=>'4', '05'=>'5', '06'=>'6', '07'=>'7', '08'=>'8', '09'=>'9');
	if ($data->dia < 10) $dia=$dia_mes[$data->dia];
	else  $dia=$data->dia;
	$saida.=($endereco['municipio_nome'] ? $endereco['municipio_nome'].', ' : '').$dia.' de '.$nome_meses[$data->mes].' de '.$data->ano;
	
	$sql->adTabela('usuarios');
	$sql->esqUnir('contatos','contatos','contato_id=usuario_contato');
	$sql->adCampo('contato_nomecompleto, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_curto');
	$sql->adOnde('usuario_id='.(int)($obj->social_familia_cadastrador ? $obj->social_familia_cadastrador : $Aplic->usuario_id));
	$nome=$sql->linha();
	
	echo '<tr><td colspan=20><table width="100%"><tr><td colspan=2>'.$saida.'<br><br><br><br><br></td></tr><tr><td width="50%">';
	echo '<table cellpadding=0 cellspacing=0><tr><td>_________________________________________________<br>'.$obj->social_familia_nome.'<br>'.ucfirst($config['beneficiario']).'</td></tr></table></td><td width="50%">';
	echo '<table cellpadding=0 cellspacing=0><tr><td>_________________________________________________<br>'.(isset($nome['contato_nomecompleto']) && $nome['contato_nomecompleto'] ? $nome['contato_nomecompleto'] : (isset($nome['nome_curto']) && $nome['nome_curto'] ? $nome['nome_curto'] : '&nbsp;')).'<br>Cadastrador</td></tr></table></td></tr></table></td></tr>';
	echo '</table>';
	echo '<script>self.print();</script>';
	}



function arquivos($acao=0){
	global $social_familia_id, $config;
	$base_dir=($config['dir_arquivo'] ? $config['dir_arquivo'] : BASE_DIR.'/modulos/social');
	$base_url=($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL.'/modulos/social');
	
	$sql = new BDConsulta;

	//arquivo anexo
	$sql->adTabela('social_acao_arquivo');
	$sql->adCampo('social_acao_arquivo_id, social_acao_arquivo_usuario, social_acao_arquivo_data, social_acao_arquivo_ordem, social_acao_arquivo_nome, social_acao_arquivo_endereco, social_acao_arquivo_depois');
	$sql->adOnde('social_acao_arquivo_acao='.(int)$acao);
	$sql->adOnde('social_acao_arquivo_familia='.(int)$social_familia_id);
	$sql->adOrdem('social_acao_arquivo_depois, social_acao_arquivo_ordem ASC');
	$arquivos=$sql->Lista();
	$sql->limpar();
	if (count($arquivos)) echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Arquivos','Lista de arquivos relacionados à execução desta ação n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Arquivos</b>&nbsp</legend><table cellspacing=0 cellpadding=0>';
	foreach ($arquivos as $arquivo) {
		$dentro = '<table cellspacing="4" cellpadding="2" border=0 width="100%">';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;" width="120"><b>Responsável</b></td><td>'.nome_funcao('', '', '', '',$arquivo['social_acao_arquivo_usuario']).'</td></tr>';
		$dentro .= '<tr><td align="center" style="border: 1px solid;-webkit-border-radius:3.5px;"><b>Anexado em</b></td><td>'.retorna_data($arquivo['social_acao_arquivo_data']).'</td></tr>';
		$dentro .= '</table>';
		$dentro .= '<br>Clique neste link para visualizar o arquivo no Navegador Web.';
		echo '<tr><td><a href="javascript:void(0);" onclick="javascript:env.a.value=\'download_acao\'; env.sem_cabecalho.value=1; env.social_acao_arquivo_id.value='.$arquivo['social_acao_arquivo_id'].'; env.submit();">'.dica($arquivo['social_acao_arquivo_nome'],$dentro).($arquivo['social_acao_arquivo_depois']? 'Depois - ' : 'Antes - ').$arquivo['social_acao_arquivo_nome'].'</a></td></tr>';
		}
	if (count($arquivos)) echo '</table></td></tr></table></fieldset></td></tr>';	
	}


function problema($acao_id){
	global $social_familia_id, $config;
	$sql = new BDConsulta;
	
	$sql->adTabela('social_acao_problema');
	$sql->adCampo('social_acao_problema_id, social_acao_problema_descricao');
	$sql->adOnde('social_acao_problema_acao_id='.(int)$acao_id);
	$sql->adOrdem('social_acao_problema_ordem ASC');
	$lista_problemas=$sql->listaVetorChave('social_acao_problema_id', 'social_acao_problema_descricao');
	$status=getSisValor('StatusProblema');
	
	$sql->adTabela('social_familia_problema');
	$sql->adCampo('social_familia_problema_id, social_familia_problema_tipo, social_familia_problema_status, social_familia_problema_observacao, social_familia_problema_usuario_insercao, social_familia_problema_usuario_insercao_nome, social_familia_problema_data_insercao');
	$sql->adOnde('social_familia_problema_acao='.(int)$acao_id);
	$sql->adOnde('social_familia_problema_familia='.(int)$social_familia_id);
	$sql->adOrdem('social_familia_problema_data_insercao ASC');
	$lista=$sql->Lista();
	
	$saida='';
	foreach ($lista as $linha) {
		$saida.='<tr>';
		$saida.='<td>'.(isset($lista_problemas[$linha['social_familia_problema_tipo']]) ? $lista_problemas[$linha['social_familia_problema_tipo']] : '&nbsp;').'</td>';
		$saida.='<td>'.($linha['social_familia_problema_observacao'] ? $linha['social_familia_problema_observacao'] : '&nbsp;').'</td>';
		$saida.='<td>'.retorna_data($linha['social_familia_problema_data_insercao'], false).'</td>';
		$saida.='<td>'.($linha['social_familia_problema_usuario_insercao'] ? link_usuario($linha['social_familia_problema_usuario_insercao'], '','','esquerda') : $linha['social_familia_problema_usuario_insercao_nome']).'</td>';
		$saida.='<td>'.(isset($status[$linha['social_familia_problema_status']]) ? $status[$linha['social_familia_problema_status']] : '&nbsp;').'</td>';
		$saida.='</tr>';
		}
	
	if ($saida) {
		echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Problemas','Lista de problemas relacionados à execução desta ação n'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'&nbsp;<b>Problemas</b>&nbsp</legend><table cellspacing=0 cellpadding=0><tr><td><table cellpadding=0 cellspacing=0 class="tbl1">';
		echo '<tr><th>Problema</th><th>Observação</th><th>Data</th><th>Responsável</th><th>Status</th></tr>';
		echo $saida;
		echo '</table></td></tr></table></fieldset></td></tr>';
		}
	}


function valores($campo='', $social_familia_id=0){
	global $sql;
	$sql->adTabela('social_familia_opcao');
	$sql->adCampo('social_familia_opcao_valor');
	$sql->adOnde('social_familia_opcao_familia = '.$social_familia_id);
	$sql->adOnde('social_familia_opcao_campo = "'.$campo.'"');
	$selecionado = $sql->carregarColuna();
	$sql->limpar();
	return $selecionado;
	}

?>
<script language="javascript">


function excluir() {
	if (confirm('Tem certeza que deseja excluir <?php echo ($config["genero_beneficiario"]=="o" ? "este" : "esta")." ".$config["beneficiario"]?>')) {
		var f = document.env;
		f.del.value=1;
		f.a.value='fazer_sql_familia';
		f.submit();
		}
	}

function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}
	
function popCoordenadas(latitude, longitude) {
	if (window.parent.gpwebApp) parent.gpwebApp.popUp('Coordenadas', 770, 467, 'm=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : ''), null, window);
	else window.open('./index.php?m=publico&a=coordenadas&dialogo=1'+(latitude ? '&latitude='+latitude : '')+(longitude ? '&longitude='+longitude : ''), 'Ver Coordenada','height=467,width=770px,resizable,scrollbars=no');
	}		
</script>