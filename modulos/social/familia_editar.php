<?php
//apagar depois
require_once('familia.class.php');

//apagar depois

/* Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA
*/

/********************************************************************************************

gpweb\modulos\praticas\editar.php

Tela onde se edita pratica de gest�o

********************************************************************************************/

if (!defined('BASE_DIR'))	die('Voc� n�o deveria acessar este arquivo diretamente.');

global $Aplic, $cal_sdf;
$social_familia_id = intval(getParam($_REQUEST, 'social_familia_id', 0));
if ($social_familia_id && !($podeEditar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (!$social_familia_id && !($podeAdicionar || $Aplic->usuario_super_admin)) $Aplic->redirecionar('m=publico&a=acesso_negado');


if (!$Aplic->usuario_super_admin && !$Aplic->checarModulo('social', 'adicionar', $Aplic->usuario_id, 'cria_familia')) $Aplic->redirecionar('m=publico&a=acesso_negado');

require_once ($Aplic->getClasseSistema('CampoCustomizados'));

$Aplic->carregarCKEditorJS();
include_once BASE_DIR.'/modulos/social/familia.class.php';

$Aplic->carregarCalendarioJS();

$salvar = getParam($_REQUEST, 'salvar', 0);
$sql = new BDConsulta;

//esse faz parte a cultura
$vetor_animal=getSisValor('Animais');
$vetor_producao=getSisValor('FinalidadeProducao');
$vetor_cultura=getSisValor('Cultura');
$vetor_sistema=getSisValor('SistemaIrrigacao');
//fim
//esse faz parte a beber agua

$vetor_beber_agua_tipo=getSisValor('BeberAgua');



//fim
$sequencial=array();
for ($i = 0; $i <= 20; $i++) $sequencial[$i]=$i;

$estado=array('' => '');
$sql->adTabela('estado');
$sql->adCampo('estado_sigla, estado_nome');
$sql->adOrdem('estado_nome');
$estado+= $sql->listaVetorChave('estado_sigla', 'estado_nome');
$sql->limpar();
$comunidades=array(''=>'');
$cidades=array(''=>'');


$obj = new CFamilia;
$obj->load($social_familia_id);

if (!$social_familia_id){
	$obj->social_familia_estado=($Aplic->getEstado('estado_sigla') !== null ? $Aplic->getEstado('estado_sigla') : 'DF');
	$obj->social_familia_municipio=($Aplic->getEstado('municipio_id') !== null ? $Aplic->getEstado('municipio_id') : '5300108');
	$obj->social_familia_comunidade=($Aplic->getEstado('social_comunidade_id') !== null ? $Aplic->getEstado('social_comunidade_id') : 0);
	}

$df = '%d/%m/%Y';
$ttl = ($social_familia_id ? 'Editar '.ucfirst($config['beneficiario']) : 'Cadastrar '.ucfirst($config['beneficiario']));
$botoesTitulo = new CBlocoTitulo($ttl, '../../../modulos/social/imagens/familia.gif', $m, $m.'.'.$a);
$botoesTitulo->adicionaBotao('m=social&a=familia_lista', 'lista','','Lista de '.ucfirst($config['beneficiario']),'Visualizar a lista de todas os benefici�rios.');
if ($social_familia_id) $botoesTitulo->adicionaBotao('m=social&a=familia_ver&social_familia_id='.$social_familia_id, 'ver', '', 'Ver '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.ucfirst($config['beneficiario']), 'Visualizar os detalhes d'.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.');
if ($social_familia_id)	$botoesTitulo->adicionaBotaoExcluir('excluir', $social_familia_id, '', 'Excluir '.ucfirst($config['beneficiario']), 'Excluir '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.' );

$botoesTitulo->mostrar();


echo '<form name="env" id="env" method="post">';
echo '<input type="hidden" name="m" value="social" />';
echo '<input type="hidden" name="a" value="vazio" />';
echo '<input type="hidden" name="fazerSQL" value="fazer_sql_familia" />';
echo '<input type="hidden" name="dialogo" value="1" />';
echo '<input type="hidden" name="social_familia_id" id="social_familia_id" value="'.$social_familia_id.'" />';
echo '<input type="hidden" name="salvar" value="" />';
echo '<input type="hidden" name="excluir" value="" />';
echo '<input type="hidden" name="modulo" value="" />';


echo '<input type="hidden" name="tem_nis" id="tem_nis" value="" />';
echo '<input type="hidden" name="tem_cpf" id="tem_cpf" value="" />';
echo '<input type="hidden" name="tem_cnpj" id="tem_cnpj" value="" />';
echo '<input type="hidden" name="tem_cnes" id="tem_cnes" value="" />';
echo '<input type="hidden" name="tem_inep" id="tem_inep" value="" />';
echo '<input type="hidden" name="tem_inss" id="tem_inss" value="" />';
echo '<input type="hidden" name="social_familia_uuid" id="social_familia_uuid" value="'.$obj->social_familia_uuid.'" />';
echo '<input type="hidden" name="social_familia_data" id="social_familia_data" value="'.date('Y-m-d H:i:s').'" />';
echo '<input type="hidden" name="social_familia_cadastrador" id="social_familia_cadastrador" value="'.$Aplic->usuario_id.'" />';

echo estiloTopoCaixa();




echo '<table cellspacing=0 cellpadding=0 width="100%" class="std">';



echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_familia_id ? 'edi��o' : 'cria��o').' d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';

echo '<tr><td align="right" width=200>'.dica('Estado', 'O Estado d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Estado (UF):'.dicaF().'</td><td>'.selecionaVetor($estado, 'social_familia_estado', 'class="texto" style="width:160px;" size="1" onchange="mudar_cidades();"', $obj->social_familia_estado).'</td></tr>';

echo '<tr><td align="right">'.dica('Munic�pio', 'O munic�pio d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Munic�pio:'.dicaF().'</td><td><div id="combo_cidade">'.selecionar_cidades_para_ajax($obj->social_familia_estado, 'social_familia_municipio', 'class="texto" onchange="mudar_comunidades()" style="width:160px;"', '', $obj->social_familia_municipio, true, false).'</div></td></tr>';

//echo '<tr><td align="right">'.dica('C�digo do Munic�pio IBGE', 'C�digo d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'C�digo do Munic�pio IBGE:'.dicaF().'</td><td><div><input type="text" name="social_familia_cod_municipio_ibge" id="social_familia_cod_municipio_ibge" value="" style="width:200px;" class="texto" /></div></td></tr>';

echo '<tr><td align="right">'.dica('Comunidade', 'A comunidade d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Comunidade:'.dicaF().'</td><td><div id="combo_comunidade">'.selecionar_comunidade_para_ajax($obj->social_familia_municipio,'social_familia_comunidade', 'class="texto" style="width:160px;"', '', $obj->social_familia_comunidade, false).'</div></td></tr>';

echo '<tr><td align="right">'.dica('Vias de Acesso � Casa', 'Vias de Acesso � casa d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Vias de Acesso � casa:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('SocialViaAcessoCasa'), 'social_familia_via_acesso_casa', 'size="1" class="texto"', $obj->social_familia_via_acesso_casa).'</div></td></tr>';

echo '<tr><td align="right">'.dica('Telefone Principal', 'O telefone principal d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Telefone Principal:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_familia_dddtel" value="'.$obj->social_familia_dddtel.'" maxlength="2" size="1" />) <input type="text" class="texto" name="social_familia_tel" value="'.$obj->social_familia_tel.'" maxlength="9" size="25" /></div></td></tr>';

echo '<tr><td colspan=2 style="font-weight:bold;">'.dica('Se algum membro da unidade familiar participa de organiza��es sociais', 'Grau de organiza��o social d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Grau de Organiza��o Social dos membros da Fam�lia'.dicaF().'</td></tr>';

echo '<tr><td align="right">'.dica('Participa��o em Organiza��es Sociais', 'Em quais organiza��es sociais participa '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'Organiza��es Sociais:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('OrganizacaoSocial','','','sisvalor_id'), 'organizacao_social', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('organizacao_social', $social_familia_id)).'</td></tr>';

//echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Gerais','Informa��es b�sicas sobre '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Gerais</b>&nbsp</legend><table width="100%" cellspacing=0 cellpadding=0>';

echo '<tr><td align="left" style="font-weight:bold;" colspan=2>'.dica('Respons�vel pela unidade familiar', 'Respons�vel pela unidade familiar d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Respons�vel pela unidade familiar'.dicaF().'</td></tr>';


echo '<tr><td align="right">'.dica('Chefe de fam�lia', 'O benefici�rio � chefe do fam�lia.').'Chefe de fam�lia:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_chefe" id="social_familia_chefe" onchange="mostrar_chefe();" '.($obj->social_familia_chefe ? 'checked="checked"' : '').' /></td></tr>';

echo '<tr id="nome_do_chefe" style="display:'.($obj->social_familia_chefe ? 'none' : '').';"><td align="right">'.dica('Nome Completo do Chefe de Fam�lia', 'Nome completo do chefe de fam�lia.').'Nome do chefe de fam�lia:'.dicaF().'</td><td><input type="text" name="social_familia_nome_chefe" value="'.$obj->social_familia_nome_chefe.'" style="width:300px;" class="texto" /></td></tr>';

echo '<tr id="sexo_do_chefe" style="display:'.($obj->social_familia_chefe ? 'none' : '').';"><td align="right"'.dica('Sexo do Chefe de Fam�lia', 'O sexo do chefe de fam�lia.').'Sexo do chefe de fam�lia:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('Sexo'), 'social_familia_sexo_chefe', 'size="1" class="texto"', $obj->social_familia_sexo_chefe).'</td></tr>';

echo '<tr><td align="right">'.dica('Nome Completo Respons�vel', 'Nome completo d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Nome completo:'.dicaF().'</td><td><input type="text" name="social_familia_nome" id="social_familia_nome" value="'.$obj->social_familia_nome.'" style="width:300px;" class="texto" /> *</td></tr>';

echo '<tr><td align="right">'.dica('Sexo', 'O sexo d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').' Sexo:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('Sexo'), 'social_familia_sexo', 'size="1" class="texto"', $obj->social_familia_sexo).'</td></tr>';

echo '<tr><td align="right">'.dica('Escolaridade do Respons�vel', 'A escolaridade d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Escolaridade do respons�vel:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('Escolaridade'), 'social_familia_escolaridade', 'size="1" class="texto"', $obj->social_familia_escolaridade).'</td></tr>';

echo '<tr><td align="right">'.dica('Estado civil', 'O estado civil d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Estado civil:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('EstadoCivil'), 'social_familia_estado_civil', 'size="1" class="texto"', $obj->social_familia_estado_civil).'</td></tr>';

echo '<tr><td align="left" style="font-weight:bold;" colspan=2>'.dica('Dados do C�njuge', 'Dados do C�njuge d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Dados do C�njuge'.dicaF().'</td></tr>';

echo '<tr><td align="right">'.dica('Nome Completo do C�njuge', 'Nome completo do c�njuge.').'Nome do C�njuge:'.dicaF().'</td><td><input type="text" name="social_familia_conjuge" value="'.$obj->social_familia_conjuge.'" style="width:300px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('CPF do C�njuge', 'CPF do C�njuge.').'CPF do C�njuge:'.dicaF().'</td><td><input type="text" name="social_familia_conjuge_cpf" value="'.$obj->social_familia_conjuge_cpf.'" style="width:200px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('RG do C�njuge', 'RG do C�njuge.').'RG do C�njuge:'.dicaF().'</td><td><input type="text" name="social_familia_conjuge_rg" value="'.$obj->social_familia_conjuge_rg.'" style="width:200px;" class="texto" /></td></tr>';

echo '<tr><td align="right">'.dica('Entrevistado', 'O Entrevistado d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Entrevistado:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('SocialEntrevistado'), 'social_familia_entrevistado', 'size="1" class="texto"', $obj->social_familia_entrevistado).'</td></tr>';

echo '<tr><td align="right">'.dica('Grau de parentesco em rela��o ao respons�vel', 'Grau de parentesco.').'Grau de parentesco:'.dicaF().'</td><td><input type="text" name="social_familia_grau_parentesco" value="'.$obj->social_familia_grau_parentesco.'" style="width:300px;" class="texto" /></td></tr>';

echo '<tr><td colspan=2 style="font-weight:bold;">'.dica('Composi��o do grupo familiar', 'Composi��o do grupo familiar d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Composi��o do grupo familiar'.dicaF().'</td></tr>';

echo '<tr><td align="right">'.dica('Filhos', 'Quantos filhos vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Total de filhos que vivem junto:'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_filhos', 'size="1" class="texto"', $obj->social_familia_filhos).'</td></tr>';

echo '<tr><td align="right">'.dica('Filhos de 0 a 6 Anos', 'Quantos filhos vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].' com idade at� 6 anos.').'Filhos (at� 6 anos):'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_crianca_seis', 'size="1" class="texto"', $obj->social_familia_crianca_seis).'</td></tr>';

echo '<tr><td align="right">'.dica('Crian�as e Adolecentes na Escola', 'Quantas crian�as e adolecentes que vivem com est'.$config['genero_beneficiario'].' '.$config['beneficiario'].' frequentam escola.').'Na escola:'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_crianca_escola', 'size="1" class="texto"', $obj->social_familia_crianca_escola).'</td></tr>';

echo '<tr><td align="right">'.dica('Moradores com mais de 65 anos', 'Quantas pessoas com mais de 65 anos vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Mais de 65 anos:'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_sessenta_cinco', 'size="1" class="texto"', $obj->social_familia_sessenta_cinco).'</td></tr>';

echo '<tr><td align="right">'.dica('Moradores com Defici�ncia F�sica e Mental', 'Quantas pessoas portadores de defici�ncia f�sica e mental vivem com '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Defici�ncia f�sica e mental:'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_deficiente_mental', 'size="1" class="texto"', $obj->social_familia_deficiente_mental).'</td></tr>';

echo '<tr><td align="right">'.dica('Qual a Ccondi��o da Propriedade', 'Qual a condi��o d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Qual a condi��o de propriedade:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('SocialCondicaoCasa'), 'social_familia_condicao_casa', 'size="1" class="texto"', $obj->social_familia_condicao_casa).'</td></tr>';

echo '<tr><td align="right">'.dica('Tipo de Resid�ncia', 'O tipo de resid�ncia d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Tipo de resid�ncia:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('TipoResidencia'), 'social_familia_tipo_residencia', 'size="1" class="texto"', $obj->social_familia_tipo_residencia).'</td></tr>';

echo '<tr><td align="right">'.dica('Material da Coberta', 'O material da coberta da resid�ncia d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Material da coberta:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('TipoCobertaMaterial'), 'social_familia_tipo_coberta_material', 'size="1" class="texto"', $obj->social_familia_tipo_coberta_material).'</td></tr>';

echo '<tr><td align="right">'.dica('Estado da Coberta', 'O tipo de coberta da resid�ncia d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Estado da coberta:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('TipoCoberta'), 'social_familia_tipo_coberta', 'size="1" class="texto"', $obj->social_familia_tipo_coberta).'</td></tr>';

echo '<tr><td align="right">'.dica('Possui Energia', 'O benefici�rio tem energia eletrifica.').'Possui energia:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_eletrificacao" id="social_familia_eletrificacao" onchange="mostrar_energia();" '.($obj->social_familia_eletrificacao ? 'checked="checked"' : '').' /></td></tr>';

echo '<tr id="tipo_energia_label"><td align="right">'.dica('Tipo de Energia', 'O tipo de energia d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Tipo de energia:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('TipoEnergia'), 'social_familia_tipo_energia', 'size="1" class="texto"', $obj->social_familia_tipo_energia).'</td></tr>';

echo '<tr><td align="right">'.dica('A Casa Tem Banheiro', 'O benefici�rio tem esgotamento sanit�rio.').'A casa tem banheiro:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_sanitario" '.($obj->social_familia_sanitario ? 'checked="checked"' : '').' /></td></tr>';

echo '<tr id="tipo_energia_label"><td align="right">'.dica('Tipo de Esgotamento', 'O tipo de Esgotamento d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Tipo de esgotamento:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('EsgotamentoSanitario'), 'social_familia_esgoto', 'size="1" class="texto"', $obj->social_familia_esgoto).'</td></tr>';

echo '<tr id="tipo_energia_label"><td align="right">'.dica('Destino do Lixo', 'A forma de descarte do lixo pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Destino do lixo:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('Lixo'), 'social_familia_lixo', 'size="1" class="texto"', $obj->social_familia_lixo).'</td></tr>';

echo '<tr><td align="right">'.dica('Cisterna', 'Possui Cisterna.').'Possui cisterna:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_cisterna" id="social_familia_cisterna"'.($obj->social_familia_cisterna ? 'checked="checked"' : '').' /></td></tr>';

echo '<tr><td align="right">'.dica('Respons�vel Recebe Benef�cio', 'Respons�vel recebe benef�cio de programas sociais '.($config['genero_beneficiario']=='o' ? 'este' : 'esta').' '.$config['beneficiario'].'.').'Respons�vel recebe benef�cio:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('SocialResponsavelAuxilio','','','sisvalor_id'), 'Social_Responsavel_Auxilio', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('Social_Responsavel_Auxilio', $social_familia_id)).'</td></tr>';

echo '<tr><td align="right">'.dica('Ocupa��o', 'A ocupa��o econ�mica d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Ocupa��o do Respons�vel:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('Ocupacao'), 'social_familia_ocupacao', 'size="1" class="texto"', $obj->social_familia_ocupacao).'</td></tr>';

echo '<tr><td align="right">'.dica('Fonte de Renda', 'A principal fonte de renda d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Composi��o da Fonte de Renda:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('FonteRenda'), 'social_familia_principal_renda', 'size="1" class="texto"', $obj->social_familia_principal_renda).'</td></tr>';

echo '<tr><td align="right">'.dica('Per�odo da renda', 'A periodicidade da principal fonte de renda d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Per�odo da renda:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('PeriodoRenda'), 'social_familia_renda_periodo', 'size="1" class="texto"', $obj->social_familia_renda_periodo).'</td></tr>';

echo '<tr><td align="right">'.dica('N�mero de Dependentes', 'N�mero de dependentes.').'Nr de dependentes:'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_nr_dependentes', 'size="1" class="texto" onchange="calcular_renda_per_capita();"', $obj->social_familia_nr_dependentes).'</td></tr>';

echo '<tr><td align="right">'.dica('Valor Mensal da renda', 'O valor mensal da renda d'.$config['genero_beneficiario'].' '.$config['beneficiario'].' em reais.').'Valor mensal da renda:'.dicaF().'</td><td><input type="text" class="texto" onchange="calcular_renda_per_capita();" id="social_familia_renda_valor" name="social_familia_renda_valor" value="'.($obj->social_familia_renda_valor!=0 ? number_format($obj->social_familia_renda_valor, 2, ',', '.') : '').'"></td></tr>';

echo '<tr><td align="right">'.dica('Renda per Capita', 'O valor mensal da renda per capita.').'Renda per capita:'.dicaF().'</td><td><input type="text" class="texto" id="social_familia_renda_capita" onchange="calcular_renda();"  name="social_familia_renda_capita" value="'.($obj->social_familia_renda_valor!=0 ? number_format($obj->social_familia_renda_valor/($obj->social_familia_nr_dependentes+1), 2, ',', '.') : '').'"></td></tr>';

echo '<tr><td align="right">'.dica('Fonte de �gua', 'As principais fontes de �gua da propriedade.').'Fonte de �gua:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('BeberAgua','','','sisvalor_id'), 'agua_fonte', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('agua_fonte', $social_familia_id)).'</td></tr>';


echo '<tr><td align="right">'.dica('Dist�ncia', 'A dist�ncia em kil�metros at� a sede do munic�pio').'Dist�ncia � fonte de �gua:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_distancia" value="'.($obj->social_familia_distancia!=0 ? number_format($obj->social_familia_distancia, 2, ',', '.') : '').'" size="15" maxlength="15" />&nbsp;Km</td></tr>';


echo '<tr><td align="right">'.dica('Vias de Acesso � Casa', 'Vias de Acesso � casa d'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Vias de Acesso � casa:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('SocialViaAcessoCasa'), 'social_familia_via_acesso_casa', 'size="1" class="texto"', $obj->social_familia_via_acesso_casa).'</td></tr>';



echo '<tr><td align="right">'.dica('Coordenadas', 'As coordenadas geogr�ficas da localiza��o d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'').'Coordenadas:'.dicaF().'</td><td><table cellpadding=0>';
echo '<tr><td colspan=2 align=center>Geogr�fica</td><td colspan=2 align=center>UTM</td></tr>';
echo '<tr><td align=right>Lon:</td><td><input class="texto" type=text size=15 id="social_familia_longitude" name="social_familia_longitude" value="'.($obj->social_familia_longitude ? $obj->social_familia_longitude : 0).'" onChange="converter_decimal()"></td><td align=right>X:</td><td><input class="texto" type=text size=15 name="txtX" value=""></td></tr>';
echo '<tr><td align=right>Lat:</td><td><input class="texto" type=text size=15 id="social_familia_latitude" name="social_familia_latitude" value="'.($obj->social_familia_latitude ? $obj->social_familia_latitude : 0).'"  onChange="converter_decimal()"></td><td align=right>Y:</td><td><input class="texto" type=text size=15 name="txtY" value=""></td></tr>';
echo '<tr><td align=right>Lon:</td><td><input class="texto" type="text" name="txtlongraus" size="2" onChange="btnToUTM_OnClick()" value="0">�<input class="texto" type="text" name="txtlonmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlonsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'</td><td align=right>Zona:</td><td><input class="texto" type=text size=4 name="txtZone" value="22" value="0"></td></tr>';
echo '<tr><td align=right>Lat:</td><td><input class="texto" type="text" name="txtlatgraus" size="2" onChange="btnToUTM_OnClick()" value="0">�<input class="texto" type="text" name="txtlatmin" size="2" onChange="btnToUTM_OnClick()" value="0">\'<input class="texto" type="text" name="txtlatsec" size="2" onChange="btnToUTM_OnClick()" value="0">\'\'&nbsp;&nbsp;</td><td colspan=2>Hemisf�rio:<input class="texto" type=radio name="rbtnHemisphere" value="N" OnClick="0">N<input class="texto" type=radio name="rbtnHemisphere" value="S" OnClick="0" checked>S</td></tr>';
echo '<tr><td></td><td align=center>'.botao('>>', 'Transformar em UTM', 'Clique neste bot�o para converter as coordenadas de grau para UTM.','','btnToUTM_OnClick()').'</td><td></td><td align=center>'.botao('<<', 'Transformar em Grau', 'Clique neste bot�o para converter as coordenadas de UTM para grau.','','btnToGeographic_OnClick()').'</td></tr>';
echo '</table></td></tr>';


$data = new CData($obj->social_familia_nascimento);
echo '<tr><td align="right">'.dica('Data de Nascimento', 'A data de nascimento d'.$config['genero_beneficiario'].' '.$config['beneficiario'].' no formato <b>(dd/mm/aaaa)</b>.').'Nascimento:'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="social_familia_nascimento" onchange="setData(\'env\', \'social_familia_nascimento\');" value="'.($obj->social_familia_nascimento && $obj->social_familia_nascimento !='0000-00-00' ? $data->format($Aplic->getPref('datacurta')) : '').'" maxlength="10" size="14" onkeyup="barra(this)" />(dd/mm/aaaa)</td></tr>';
echo '<tr><td align="right">'.dica('CPF', 'O CPF d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'CPF:'.dicaF().'</td><td><input type="text" class="texto" id="social_familia_cpf" name="social_familia_cpf" value="'.$obj->social_familia_cpf.'" maxlength="14" size="14" onchange="verificarCPF()" /></td></tr>';
echo '<tr><td align="right">'.dica('CNPJ', 'Escreva o CNPJ d'.$config['genero_beneficiario'].' '.$config['beneficiario'].', caso seja um estabelecimento jur�dico.').'CNPJ:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_cnpj" id="social_familia_cnpj" value="'.$obj->social_familia_cnpj.'" maxlength="18" size="18" onchange="verificarCNPJ()" /></td></tr>';

echo '<tr><td align="right">'.dica('INEP', 'Escreva o INEP d'.$config['genero_beneficiario'].' '.$config['beneficiario'].', caso seja um estabelecimento jur�dico do tipo escola.').'INEP:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_inep" id="social_familia_inep" value="'.$obj->social_familia_inep.'" maxlength="8" size="8" onchange="verificarINEP()" /></td></tr>';
echo '<tr><td align="right">'.dica('CNES', 'Escreva o CNES d'.$config['genero_beneficiario'].' '.$config['beneficiario'].', caso seja um estabelecimento jur�dico do tipo posto de sa�de.').'CNES:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_cnes" id="social_familia_cnes" value="'.$obj->social_familia_cnes.'" maxlength="7" size="7" onchange="verificarCNES()" /></td></tr>';


echo '<tr><td align="right">'.dica('RG', 'O RG d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'RG:'.dicaF().'</td><td><table cellspacing=0 cellpadding=0><tr><td><input type="text" class="texto" name="social_familia_rg" value="'.$obj->social_familia_rg.'" maxlength="20" size="14" /></td><td>&nbsp;Org�o:</td><td><input type="text" class="texto" name="social_familia_orgao" value="'.$obj->social_familia_orgao.'" maxlength="12" size="5" /></td></tr></table></td></tr>';
echo '<tr><td align="right">'.dica('Endere�o', 'O ender�o d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Endere�o:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_endereco1" value="'.$obj->social_familia_endereco1.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right">'.dica('Complemento do Endere�o', 'O complemento do ender�o d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Complemento:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_endereco2" value="'.$obj->social_familia_endereco2.'" maxlength="60" size="25" /></td></tr>';







echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone Reserva', 'O telefone residencial d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Telefone Reserva:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_familia_dddtel2" value="'.$obj->social_familia_dddtel2.'" maxlength="2" size="1" />) <input type="text" class="texto" name="social_familia_tel2" value="'.$obj->social_familia_tel2.'" maxlength="9" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Celular', 'O celular d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Celular:'.dicaF().'</td><td>(<input type="text" class="texto" name="social_familia_dddcel" value="'.$obj->social_familia_dddcel.'" maxlength="2" size="1" />) <input type="text" class="texto" name="social_familia_cel" value="'.$obj->social_familia_cel.'" maxlength="9" size="25" /></td></tr>';
echo '<tr><td align="right">'.dica('e-mail', 'O e-mail d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'e-mail:'.dicaF().'</td><td nowrap="nowrap"><input type="text" class="texto" name="social_familia_email" value="'.$obj->social_familia_email.'" maxlength="255" size="25" /></td></tr>';

//echo '</table></fieldset></td></tr>';
//echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Socioecon�micos','Informa��es socioecon�micas sobre '.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Socioecon�micos</b>&nbsp</legend><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right">'.dica('Recebe Bolsa Fam�lia', 'O benefici�rio recebe Bolsa Fam�lia.').'Recebe Bolsa Fam�lia:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_bolsa" '.($obj->social_familia_bolsa ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right">'.dica('NIS', 'O NIS d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'. Preencha sem h�fen').'NIS:'.dicaF().'</td><td><input type="text" class="texto" id="social_familia_nis" name="social_familia_nis" value="'.$obj->social_familia_nis.'" maxlength="11" size="14" /></td></tr>';
echo '<tr><td align="right">'.dica('Benef�cio do INSS', 'Caso '.$config['genero_beneficiario'].' '.$config['beneficiario'].' seja aposentado, preencha o n�mero do benef�cio do INSS').'Benef�cio INSS:'.dicaF().'</td><td><input type="text" class="texto" id="social_familia_beneficio_inss" name="social_familia_beneficio_inss" value="'.$obj->social_familia_beneficio_inss.'" maxlength="20" size="23" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Necessita do Bolsa Fam�lia', 'O benefici�rio necessita do Bolsa Fam�lia.').'Necessita do Bolsa Fam�lia:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_necessita_bolsa" '.($obj->social_familia_necessita_bolsa ? 'checked="checked"' : '').' /></td></tr>';


//echo '<tr><td align="right">'.dica('Comprimento', 'O comprimento da resid�ncia').'Comprimento:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_comprimento" value="'.($obj->social_familia_comprimento!=0 ? number_format($obj->social_familia_comprimento, 2, ',', '.') : '').'" size="15" /></td></tr>';
//echo '<tr><td align="right">'.dica('Largura', 'A largura da resid�ncia').'Largura:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_largura" value="'.($obj->social_familia_largura!=0 ? number_format($obj->social_familia_largura, 2, ',', '.') : '').'" size="15" /></td></tr>';




//echo '<tr><td align="right">'.dica('Sanit�rio', 'O benefici�rio tem sanit�rio.').'Sanit�rio:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_sanitario" '.($obj->social_familia_sanitario ? 'checked="checked"' : '').' /></td></tr>';
//echo '<tr><td align="right">'.dica('Trata a �gua', 'O benefici�rio trata a �gua.').'Trata a �gua:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('TratamentoAgua'), 'social_familia_tratamento_agua', 'size="1" class="texto"', $obj->social_familia_tratamento_agua).'</td></tr>';
//echo '<tr><td align="right">'.dica('Frequ�ncia de Tratamento da �gua', 'A frequ�ncia de tratamento da �gua pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Freq. trat. �gua:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('FrequenciaTratamento','','','',true,''), 'social_familia_tratamento_agua_frequencia', 'size="1" class="texto"', $obj->social_familia_tratamento_agua_frequencia).'</td></tr>';
//echo '<tr><td align="right">'.dica('Dist�ncia Percorrida para Pegar �gua', 'A dist�ncia percorrida para pegar �gua em kil�metros.').'Dist�ncia da �gua:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_distancia_agua" value="'.($obj->social_familia_distancia_agua!=0 ? number_format($obj->social_familia_distancia_agua, 2, ',', '.') : '').'" size="15" />&nbsp;Km</td></tr>';
//echo '<tr><td align="right">'.dica('Fonte de �gua para Beber', 'As fonte de �gua dispon�veis para beber pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Beber:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('FonteAgua'), 'agua_beber', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('agua_beber', $social_familia_id)).'</td></tr>';
//echo '<tr><td align="right">'.dica('Fonte de �gua para Banho', 'As fonte de �gua dispon�veis para banho pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Banho:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('FonteAgua'), 'agua_banho', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('agua_banho', $social_familia_id)).'</td></tr>';
//echo '<tr><td align="right">'.dica('Fonte de �gua para Cozinhar', 'As fonte de �gua dispon�veis para cozinhar pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Cozinhar:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('FonteAgua'), 'agua_cozinhar', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('agua_cozinhar', $social_familia_id)).'</td></tr>';
//echo '<tr><td align="right">'.dica('Fonte de �gua para Lavar Roupa', 'As fonte de �gua dispon�veis para lavar roupa pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'Lavar roupa:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('FonteAgua'), 'agua_lavar', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('agua_lavar', $social_familia_id)).'</td></tr>';






//echo '</table></fieldset></td></tr>';

//echo '<tr><td colspan=20><fieldset><legend class=texto style="color: black;">'.dica('Dados Produtivos','Informa��es sobre produ��es econ�micas administradas pel'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.').'&nbsp;<b>Dados Produtivos</b>&nbsp</legend><table width="100%" cellspacing=0 cellpadding=0>';
echo '<tr><td align="right"  width="150">'.dica('Uso da Terra', 'O uso da terra pel'.$config['genero_beneficiario'].' '.$config['beneficiario']).'Uso da terra:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('UsoTerra'), 'social_familia_uso_terra', 'size="1" class="texto"', $obj->social_familia_uso_terra).'</td></tr>';
echo '<tr><td align="right">'.dica('M�o de Obra Familiar', 'N�mero de familiares que trabalham na propriedade').'M�o de obra familiar:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_mao_familiar" value="'.$obj->social_familia_mao_familiar.'" size="15" /></td></tr>';
echo '<tr><td align="right">'.dica('M�o de Obra Contratada', 'N�mero de pessoas contratadas que trabalham na propriedade').'M�o de obra contratada:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_mao_contratada" value="'.$obj->social_familia_mao_contratada.'" size="15" /></td></tr>';
echo '<tr><td align="right">'.dica('�rea Total da Propriedade', '�rea total aproximada da propriedade (casa  + terreno) em hectares.').'�rea total:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_area_propriedade" value="'.($obj->social_familia_area_propriedade!=0 ? number_format($obj->social_familia_area_propriedade, 2, ',', '.') : '').'" size="15" />&nbsp;ha</td></tr>';
echo '<tr><td align="right">'.dica('�rea de Produ��o da Propriedade', '�rea de produ��o da propriedade em hectares.').'�rea de produ��o:'.dicaF().'</td><td><input type="text" class="texto" name="social_familia_area_producao" value="'.($obj->social_familia_area_producao!=0 ? number_format($obj->social_familia_area_producao, 2, ',', '.') : '').'" size="15" />&nbsp;ha</td></tr>';


echo '<tr><td align="right"  width="150">'.dica('Inserir Cultura', 'Cadastre as principais culturas da propriedade.').'Inserir cultura:'.dicaF().'</td><td><table><tr><td>'.selecionaVetor(getSisValor('Cultura'), 'social_familia_producao_cultura', 'size="1" class="texto"').'</td><td>finalidade:&nbsp;'.selecionaVetor(getSisValor('FinalidadeProducao'), 'cultura_producao_finalidade', 'size="1" class="texto"').'</td><td>�rea:&nbsp;<input type="text" class="texto" id="cultura_producao_quantidade" name="social_familia_producao_quantidade" size="15" />&nbsp;ha</td><td><a href="javascript:void(0);" onclick="javascript:inserir_cultura();">'.imagem('icones/adicionar.png', 'Inserir Cultura', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar esta cultura.').'</a></td></tr></table></td></tr>';


$sql->adTabela('social_familia_producao');
$sql->adCampo('social_familia_producao_cultura, social_familia_producao_finalidade, social_familia_producao_quantidade');
$sql->adOnde('social_familia_producao_familia = '.(int)$social_familia_id);
$sql->adOnde('social_familia_producao_cultura IS NOT NULL');
$linhas=$sql->Lista();
$sql->limpar();
$vetor='';

$saida='';
foreach($linhas as $linha) {
	$vetor.=$linha['social_familia_producao_cultura'].'*'.$linha['social_familia_producao_finalidade'].'*'.$linha['social_familia_producao_quantidade'].';';
	$saida.='<tr><td>'.(isset($vetor_cultura[$linha['social_familia_producao_cultura']]) ? $vetor_cultura[$linha['social_familia_producao_cultura']] : '&nbsp;').'</td>';
	$saida.='<td>'.($vetor_producao[$linha['social_familia_producao_finalidade']] ? $vetor_producao[$linha['social_familia_producao_finalidade']] : '&nbsp;').'</td>';
	$saida.='<td>'.($linha['social_familia_producao_quantidade'] ? number_format($linha['social_familia_producao_quantidade'], 2, ',', '.') : '&nbsp;').'</td><td><a href="javascript: void(0);" onclick="excluir_cultura(\''.(isset($campos[0]) ? $campos[0] : '0').'*'.(isset($campos[1]) ? $campos[1] : '0').'*'.(isset($campos[2]) ? $campos[2] : '0').'\');".">'.imagem('icones/remover.png').'</a></td></tr>';
	}
if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Cultura</th><th>Finalidade</th><th>�rea</th><th></th></tr>'.$saida.'</table>';

echo '<input type="hidden" id="cultura_linhas" name="cultura_linhas" value="'.$vetor.'" />';
echo '<tr><td align="right">'.dica('Principais Culturas', 'As principais culturas da propriedade.').'Principais culturas:'.dicaF().'</td><td><div id="principais_culturas">'.($saida ? $saida : 'Nenhuma').'</div></td></tr>';




echo '<tr><td align="right" >'.dica('Inserir Cria��o', 'Cadastre as principais cria��es da animais.').'Inserir cria��o:'.dicaF().'</td><td><table><tr><td>'.selecionaVetor(getSisValor('Animais'), 'social_familia_producao_animal', 'size="1" class="texto"').'</td><td>finalidade:&nbsp;'.selecionaVetor(getSisValor('FinalidadeProducao'), 'animal_producao_finalidade', 'size="1" class="texto"').'</td><td>quantidade:&nbsp;<input type="text" class="texto" id="animal_producao_quantidade" name="social_familia_producao_quantidade" size="15" />&nbsp;cabe�as</td><td><a href="javascript:void(0);" onclick="javascript:inserir_animal();">'.imagem('icones/adicionar.png', 'Inserir Cria��o de Animais', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar esta cria��o de animais.').'</a></td></tr></table></td></tr>';
$sql->adTabela('social_familia_producao');
$sql->adCampo('social_familia_producao_animal, social_familia_producao_finalidade, social_familia_producao_quantidade');
$sql->adOnde('social_familia_producao_familia = '.(int)$social_familia_id);
$sql->adOnde('social_familia_producao_animal IS NOT NULL');
$linhas=$sql->Lista();
$sql->limpar();
$vetor='';

$saida='';
foreach($linhas as $linha) {
	$vetor.=$linha['social_familia_producao_animal'].'*'.$linha['social_familia_producao_finalidade'].'*'.$linha['social_familia_producao_quantidade'].';';
	$saida.='<tr><td>'.(isset($vetor_animal[$linha['social_familia_producao_animal']]) ? $vetor_animal[$linha['social_familia_producao_animal']] : '&nbsp;').'</td>';
	$saida.='<td>'.($vetor_producao[$linha['social_familia_producao_finalidade']] ? $vetor_producao[$linha['social_familia_producao_finalidade']] : '&nbsp;').'</td>';
	$saida.='<td>'.($linha['social_familia_producao_quantidade'] ? number_format($linha['social_familia_producao_quantidade'], 2, ',', '.') : '&nbsp;').'</td><td><a href="javascript: void(0);" onclick="excluir_animal(\''.(isset($campos[0]) ? $campos[0] : '0').'*'.(isset($campos[1]) ? $campos[1] : '0').'*'.(isset($campos[2]) ? $campos[2] : '0').'\');".">'.imagem('icones/remover.png').'</a></td></tr>';
	}
if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Animal</th><th>Finalidade</th><th>Qnt</th><th></th></tr>'.$saida.'</table>';



echo '<input type="hidden" id="animal_linhas" name="animal_linhas" value="'.$vetor.'" />';
echo '<tr><td align="right">'.dica('Principais Cria��es de Animais', 'As principais cria��es de animais da propriedade.').'Principais cria��es:'.dicaF().'</td><td><div id="principais_animais">'.($saida ? $saida : 'Nenhuma').'</div></td></tr>';


echo '<tr><td align="right">'.dica('N�mero de '.ucfirst($config['beneficiario']), 'N�mero de benefici�rios que poder�o trabalhar na propriedade.').'Nr '.ucfirst($config['beneficiario']).':'.dicaF().'</td><td>'.selecionaVetor($sequencial, 'social_familia_nr_familias_trabalhar', 'size="1" class="texto"', $obj->social_familia_nr_familias_trabalhar).'</td></tr>';
echo '<tr><td align="right">'.dica('Fonte h�drica para a agropecu�ria', 'As fonte h�drica para a agropecu�ria dispon�veis � benefici�rio.').'Fonte para agropecu�ria:'.dicaF().'</td><td>'.selecionaVetorMultiplo(getSisValor('FonteAgropecuaria'), 'agua_agropecuaria', 'class="texto" style="width: 400px;height: 55px;padding-left: 5px;padding-right: 5px;float: left;overflow: auto;"', valores('agua_agropecuaria', $social_familia_id)).'</td></tr>';
echo '<tr><td align="right">'.dica('Irriga��o', 'O benefici�rio tem irriga��o na propriedade.').'Irriga��o:'.dicaF().'</td><td><input type="checkbox" value="1" name="social_familia_irrigacao" '.($obj->social_familia_irrigacao ? 'checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right">'.dica('Inserir Cultura Irrigada', 'Cadastre as principais culturas irrigadas.').'Inserir irriga��o:'.dicaF().'</td><td><table><tr><td>'.selecionaVetor(getSisValor('Cultura'), 'irrigacao_cultura', 'size="1" class="texto"').'</td><td>sistema:&nbsp;'.selecionaVetor(getSisValor('SistemaIrrigacao'), 'irrigacao_sistema', 'size="1" class="texto"').'</td><td>�rea:&nbsp;<input type="text" class="texto" id="irrigacao_quantidade" name="irrigacao_quantidade" size="15" />&nbsp;ha</td><td><a href="javascript:void(0);" onclick="javascript:inserir_irrigacao();">'.imagem('icones/adicionar.png', 'Inserir Cultura Irrigada', 'Clique neste �cone '.imagem('icones/adicionar.png').' para adicionar esta cultura irrigada.').'</a></td></tr></table></td></tr>';
$sql->adTabela('social_familia_irrigacao');
$sql->adCampo('social_familia_irrigacao_cultura, social_familia_irrigacao_sistema, social_familia_irrigacao_area');
$sql->adOnde('social_familia_irrigacao_familia = '.(int)$social_familia_id);
$linhas=$sql->Lista();
$sql->limpar();
$vetor='';
$saida='';
foreach($linhas as $linha) {
	$vetor.=$linha['social_familia_irrigacao_cultura'].'*'.$linha['social_familia_irrigacao_sistema'].'*'.$linha['social_familia_irrigacao_area'].';';
	$saida.='<tr><td>'.(isset($vetor_cultura[$linha['social_familia_irrigacao_cultura']]) ? $vetor_cultura[$linha['social_familia_irrigacao_cultura']] : '&nbsp;').'</td>';
	$saida.='<td>'.($vetor_sistema[$linha['social_familia_irrigacao_sistema']] ? $vetor_sistema[$linha['social_familia_irrigacao_sistema']] : '&nbsp;').'</td>';
	$saida.='<td>'.($linha['social_familia_irrigacao_area'] ? number_format($linha['social_familia_irrigacao_area'], 2, ',', '.') : '&nbsp;').'</td><td><a href="javascript: void(0);" onclick="excluir_animal(\''.(isset($campos[0]) ? $campos[0] : '0').'*'.(isset($campos[1]) ? $campos[1] : '0').'*'.(isset($campos[2]) ? $campos[2] : '0').'\');".">'.imagem('icones/remover.png').'</a></td></tr>';
	}
if ($saida) $saida='<table class="tbl1" cellspacing=0 cellpadding=0><tr><th>Cultura</th><th>Sistema</th><th>�rea</th><th></th></tr>'.$saida.'</table>';




echo '<input type="hidden" id="irrigacao_linhas" name="irrigacao_linhas" value="'.$vetor.'" />';

echo '<tr><td align="right">'.dica('Principais Culturas Irrigadas', 'As principais culturas irrigadas da propriedade.').'Principais Irriga��es:'.dicaF().'</td><td><div id="principais_irrigacoes">'.($saida ? $saida : 'Nenhuma').'</div></td></tr>';




echo '<tr><td align="right">'.dica('Assist�ncia T�cnica', 'Recebe algum tipo de assist�ncia t�cnica.').'Assist�ncia t�cnica:'.dicaF().'</td><td>'.selecionaVetor(getSisValor('Assistencia','','','',true,''), 'social_familia_assistencia_tecnica', 'size="1" class="texto"', $obj->social_familia_assistencia_tecnica).'</td></tr>';


echo '<tr><td align="right">Observa��es:</td><td><textarea data-gpweb-cmp="ckeditor" rows="10" name="social_familia_observacao" id="social_familia_observacao">'.$obj->social_familia_observacao.'</textarea></td></tr>';


$campos_customizados = new CampoCustomizados('social_familia', $social_familia_id, 'editar');
$campos_customizados->imprimirHTML();

echo '<tr><td colspan=20><table cellspacing=0 cellpadding=0 width="100%"><tr><td>'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td><td align="right">'.botao('cancelar', 'Cancelar', 'Cancelar a '.($social_familia_id ? 'edi��o' : 'cria��o').' d'.$config['genero_beneficiario'].' '.$config['beneficiario'].'.','','if(confirm(\'Tem certeza que deseja cancelar?\')){url_passar(0, \''.$Aplic->getPosicao().'\');}').'</td></tr></table></td></tr>';
echo '</table>';
echo '</form>';

echo estiloFundoCaixa();

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

document.getElementById('social_familia_tipo_energia').style.display='none';
document.getElementById('tipo_energia_label').style.display='none';

function float2moeda(num){
	x=0;
	if (num<0){
		num=Math.abs(num);
		x=1;
		}
	if(isNaN(num))num="0";
	cents=Math.floor((num*100+0.5)%100);
	num=Math.floor((num*100+0.5)/100).toString();
	if(cents<10) cents="0"+cents;
	for (var i=0; i< Math.floor((num.length-(1+i))/3); i++) num=num.substring(0,num.length-(4*i+3))+'.'+num.substring(num.length-(4*i+3));
	ret=num+','+cents;
	if(x==1) ret = ' - '+ret;
	return ret;
	}

function moeda2float(moeda){
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(".","");
	moeda = moeda.replace(",",".");
	if (moeda=="") moeda='0';
	return parseFloat(moeda);
	}

function calcular_renda_per_capita(){
	var valor=moeda2float(document.getElementById('social_familia_renda_valor').value);
	var dependentes=Math.abs(document.getElementById('social_familia_nr_dependentes').value)+1;
	document.getElementById('social_familia_renda_capita').value=float2moeda(valor/dependentes);
	}

function calcular_renda(){
	var valor=moeda2float(document.getElementById('social_familia_renda_capita').value);
	var dependentes=Math.abs(document.getElementById('social_familia_nr_dependentes').value)+1;
	document.getElementById('social_familia_renda_valor').value=float2moeda(valor*dependentes);
	}


function mostrar_chefe(){
	if (document.getElementById('social_familia_chefe').checked){
		document.getElementById('sexo_do_chefe').style.display='none';
		document.getElementById('nome_do_chefe').style.display='none';
		}
	else {
		document.getElementById('sexo_do_chefe').style.display='';
		document.getElementById('nome_do_chefe').style.display='';
		}
	}

function mostrar_energia(){
	if (document.getElementById('social_familia_eletrificacao').checked){
		document.getElementById('social_familia_tipo_energia').style.display='';
		document.getElementById('tipo_energia_label').style.display='';


		}
	else {
		document.getElementById('social_familia_tipo_energia').style.display='none';
		document.getElementById('tipo_energia_label').style.display='none';
		}
	}



function excluir_irrigacao(chave){
	var vetor = document.getElementById('irrigacao_linhas').value;
	xajax_excluir_irrigacao_ajax(vetor, chave);
	}

function inserir_irrigacao(){
	var cultura=document.getElementById('irrigacao_cultura').value;
	var sistema=document.getElementById('irrigacao_sistema').value;
	var quantitade=document.getElementById('irrigacao_quantidade').value;

	if (!cultura || !sistema || !quantitade) {
		alert('Todos os campos precisam ser preenchidos');
		document.getElementById('irrigacao_quantidade').focus();
		}
	else {
		var vetor = document.getElementById('irrigacao_linhas').value;
		xajax_adicionar_irrigacao_ajax(vetor, cultura, sistema, quantitade);
		}
	}


function excluir_cultura(chave){
	var vetor = document.getElementById('cultura_linhas').value;
	xajax_excluir_cultura_ajax(vetor, chave);
	}

function inserir_cultura(){
	var cultura=document.getElementById('social_familia_producao_cultura').value;
	var finalidade=document.getElementById('cultura_producao_finalidade').value;
	var quantitade=document.getElementById('cultura_producao_quantidade').value;

	if (!cultura || !finalidade || !quantitade) {
		alert('Todos os campos precisam ser preenchidos');
		document.getElementById('cultura_producao_quantidade').focus();
		}
	else {
		var vetor = document.getElementById('cultura_linhas').value;
		xajax_adicionar_cultura_ajax(vetor, cultura, finalidade, quantitade);
		}
	}

function excluir_animal(chave){
	var vetor = document.getElementById('animal_linhas').value;
	xajax_excluir_animal_ajax(vetor, chave);
	}

function inserir_animal(){
	var animal=document.getElementById('social_familia_producao_animal').value;
	var finalidade=document.getElementById('animal_producao_finalidade').value;
	var quantitade=document.getElementById('animal_producao_quantidade').value;

	if (!animal || !finalidade || !quantitade) {
		alert('Todos os campos precisam ser preenchidos');
		document.getElementById('animal_producao_quantidade').focus();
		}
	else {
		var vetor = document.getElementById('animal_linhas').value;
		xajax_adicionar_animal_ajax(vetor, animal, finalidade, quantitade);
		}
	}



function mudar_cidades(){
	xajax_selecionar_cidades_ajax(document.getElementById('social_familia_estado').value,'social_familia_municipio','combo_cidade', 'class="texto" size=1 style="width:160px;" onchange="mudar_comunidades();"', (document.getElementById('social_familia_municipio').value ? document.getElementById('social_familia_municipio').value : <?php echo ($obj->social_familia_municipio ? $obj->social_familia_municipio : 0) ?>));
	document.getElementById('social_familia_comunidade').length=0;
	}

function mudar_comunidades(){
	var municipio_id=(document.getElementById('social_familia_municipio').value ? document.getElementById('social_familia_municipio').value : <?php echo ($obj->social_familia_municipio ? $obj->social_familia_municipio : 0) ?>);
	var social_comunidade_id=(document.getElementById('social_familia_comunidade').value ? document.getElementById('social_familia_comunidade').value : <?php echo ($obj->social_familia_comunidade ? $obj->social_familia_comunidade : 0) ?>);
	xajax_selecionar_comunidade_ajax(municipio_id, 'social_familia_comunidade', 'combo_comunidade', 'class="texto" size=1 style="width:160px;"', '', social_comunidade_id);
	}

function excluir() {
	if (confirm( "Tem certeza que deseja excluir esta familia?")) {
		var f = document.env;
		f.excluir.value=1;
		f.modulo.value='familia';
		f.submit();
		}
	}


function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}


function enviarDados() {

	xajax_existe_ajax(document.getElementById('social_familia_id').value, document.getElementById('social_familia_cpf').value, document.getElementById('social_familia_nis').value, document.getElementById('social_familia_cnpj').value, document.getElementById('social_familia_cnes').value,	document.getElementById('social_familia_inep').value,	document.getElementById('social_familia_beneficio_inss').value);

	if (document.getElementById('social_familia_nome').value.length < 3) {
		alert('Escreva um nome v�lido');
		document.getElementById('social_familia_nome').focus();
		}

	else if (document.getElementById('social_familia_estado').value.length < 2) {
		alert('Preencha o Estado do benefici�rio.');
		document.getElementById('social_familia_estado').focus();
		}
	else if (document.getElementById('social_familia_municipio').value.length < 1) {
		alert('Preencha o munic�pio do benefici�rio.');
		document.getElementById('social_familia_municipio').focus();
		}
	else if (document.getElementById('social_familia_comunidade').value.length < 1) {
		alert('Preencha a comunidade do benefici�rio.');
		document.getElementById('social_familia_comunidade').focus();
		}

	else if (document.getElementById('tem_nis').value  > 0) {
		alert('J� existe '+document.getElementById('tem_nis').value+' NIS id�nticos a este! Escolha um NIS �nico.');
		document.getElementById('social_familia_nis').focus();
		}
	else if (document.getElementById('tem_inss').value  > 0) {
		alert('J� existe '+document.getElementById('tem_inss').value+' benef�cios do INSS id�nticos a este! Escolha um benef�cio �nico.');
		document.getElementById('social_familia_beneficio_inss').focus();
		}
	else if (document.getElementById('tem_cpf').value  > 0) {
		alert('J� existe '+document.getElementById('tem_cpf').value+' CPF id�nticos a este! Escolha um CPF �nico.');
		document.getElementById('social_familia_cpf').focus();
		}
	else if (document.getElementById('tem_cnpj').value  > 0) {
		alert('J� existe '+document.getElementById('tem_cnpj').value+' CNPJ id�nticos a este! Escolha um CNPJ �nico.');
		document.getElementById('social_familia_cnpj').focus();
		}
		/*
	else if (document.getElementById('tem_cnes').value  > 0) {
		alert('J� existe '+document.getElementById('tem_cnes').value+' CNES id�nticos a este! Escolha um CNES �nico.');
		document.getElementById('social_familia_cnes').focus();
		}
	else if (document.getElementById('tem_inep').value  > 0) {
		alert('J� existe '+document.getElementById('tem_inep').value+' INEP id�nticos a este! Escolha um INEP �nico.');
		document.getElementById('social_familia_inep').focus();
		}
		*/
	else if (<?php echo ($config['cpf_obrigatorio'] ? 'true' : 'false') ?> && document.getElementById('social_familia_inep').value.length < 3 && document.getElementById('social_familia_cnes').value.length < 3 && document.getElementById('social_familia_cnpj').value.length < 3 && document.getElementById('social_familia_nis').value.length < 3 && document.getElementById('social_familia_cpf').value.length < 3&& document.getElementById('social_familia_beneficio_inss').value.length < 3) {
		alert('Se <?php echo $config["genero_beneficiario"]." ".$config["beneficiario"]?> for pessoa f�sica o CPF e o NIS ou benef�cio do INSS devem ser preenchidos,ou caso seja um estabelecimento jur�dico preencher o CNPJ, INEP ou CNES');
		document.getElementById('social_familia_cpf').focus();
		}
	else if ((document.getElementById('social_familia_cnpj').value.length > 3 || document.getElementById('social_familia_cnes').value.length > 3 || document.getElementById('social_familia_inep').value.length > 3) && (document.getElementById('social_familia_cpf').value.length > 3 || document.getElementById('social_familia_nis').value.length > 3|| document.getElementById('social_familia_beneficio_inss').value.length > 3)) {
		alert('Ou se preenche o CPF e NIS/benef�cio do INSS para pessoa f�sica ou no caso de um estabelecimento jur�dico escolher entre CNPJ, INEP e CNES');
		document.getElementById('social_familia_cpf').focus();
		}
	else if (<?php echo ($config['nis_obrigatorio'] ? 'true' : 'false') ?> && document.getElementById('social_familia_cpf').value.length > 3 && document.getElementById('social_familia_nis').value.length < 3 && document.getElementById('social_familia_beneficio_inss').value.length < 3) {
		alert('Preencha o NIS ou benef�cio do INSS d<?php echo $config["genero_beneficiario"]." ".$config["beneficiario"]?>');
		document.getElementById('social_familia_nis').focus();
		}
	else if (<?php echo ($config['cpf_obrigatorio'] ? 'true' : 'false') ?> && document.getElementById('social_familia_cnpj').value.length < 3 &&  document.getElementById('social_familia_inep').value.length < 3 && document.getElementById('social_familia_cnes').value.length < 3 && document.getElementById('social_familia_cpf').value.length < 3) {
		alert('Preencha o CPF do benefici�rio no caso de pessoa f�sica ou no caso de um estabelecimento jur�dico CNPJ, INEP e CNES, quanto for o caso.');
		document.getElementById('social_familia_cpf').focus();
		}
	else if (document.getElementById('social_familia_nis').value.length >0 && document.getElementById('social_familia_nis').value.length != 11) {
		alert('O NIS precisa ter 11 d�gitos.');
		document.getElementById('social_familia_nis').focus();
		}
	else if (document.getElementById('social_familia_longitude').value!="" && document.getElementById('social_familia_longitude').value!=0 && document.getElementById('social_familia_longitude').value < <?php echo $config['long_minima']?>) {
		alert('A longitude est� com valor menor que o m�nimo permitido.');
		document.getElementById('social_familia_longitude').focus();
		}
	else if (document.getElementById('social_familia_longitude').value!="" && document.getElementById('social_familia_longitude').value!=0 && document.getElementById('social_familia_longitude').value > <?php echo $config['long_maxima']?>) {
		alert('A longitude est� com valor maior que o m�ximo permitido.');
		document.getElementById('social_familia_longitude').focus();
		}
	else if (document.getElementById('social_familia_latitude').value!="" && document.getElementById('social_familia_latitude').value!=0 && document.getElementById('social_familia_latitude').value < <?php echo $config['lat_minima']?>) {
		alert('A latitude est� com valor menor que o m�nimo permitido.');
		document.getElementById('social_familia_latitude').focus();
		}
	else if (document.getElementById('social_familia_latitude').value!="" && document.getElementById('social_familia_latitude').value!=0 && document.getElementById('social_familia_latitude').value > <?php echo $config['lat_maxima']?>) {
		alert('A latitude est� com valor maior que o m�ximo permitido.');
		document.getElementById('social_familia_latitude').focus();
		}
	else {
		if (env.social_familia_longitude.value==0 && env.social_familia_latitude.value==0) {
			env.social_familia_longitude.value=null;
			env.social_familia_latitude.value=null;
			}

		document.env.salvar.value=1;
		document.env.submit();
		}
	}


function setData( frm_nome, f_data ){
	campo_data = eval( 'document.' + frm_nome + '.' + f_data );
	if (campo_data.value.length>0) {
    if ((parsfimData(campo_data.value))==null) {
      alert('A data/hora digitada n�o corresponde ao formato padr�o. Redigite, por favor.');
      campo_data.style.backgroundColor = 'red';
  		}
		else {
	    campo_data.style.backgroundColor = '';
			}
		}
	else campo_data_real.value = '';
	}

function barra(objeto){
	if (objeto.value.length == 2 || objeto.value.length ==5) objeto.value = objeto.value+"/";
	}



var NUM_DIGITOS_CPF = 11;
var NUM_DIGITOS_CNPJ = 14;
var NUM_DGT_CNPJ_BASE = 8;

String.prototype.lpad = function (pSize, pCharPad) {
	var str = this;
	var dif = pSize - str.length;
	var ch = String(pCharPad).charAt(0);
	for (; dif > 0; dif--) str = ch + str;
	return (str);
	}
String.prototype.trim = function () {
	return this.replace(/^\s*/, "").replace(/\s*$/, "");
	}

function unformatNumber(pNum) {
	return String(pNum).replace(/\D/g, "").replace(/^0+/, "");
	}

function formatCpfCnpj(pCpfCnpj, pUseSepar, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	if (pUseSepar == null) pUseSepar = true;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var numero = unformatNumber(pCpfCnpj);
	numero = numero.lpad(maxDigitos, '0');
	if (!pUseSepar) return numero;
	if (pIsCnpj) {
		reCnpj = /(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})$/;
		numero = numero.replace(reCnpj, "$1.$2.$3/$4-$5");
		}
	else {
		reCpf = /(\d{3})(\d{3})(\d{3})(\d{2})$/;
		numero = numero.replace(reCpf, "$1.$2.$3-$4");
		}
	return numero
	}

function dvCpfCnpj(pEfetivo, pIsCnpj) {
	if (pIsCnpj == null) pIsCnpj = false;
	var i, j, k, soma, dv;
	var cicloPeso = pIsCnpj ? NUM_DGT_CNPJ_BASE : NUM_DIGITOS_CPF;
	var maxDigitos = pIsCnpj ? NUM_DIGITOS_CNPJ : NUM_DIGITOS_CPF;
	var calculado = formatCpfCnpj(pEfetivo + "00", false, pIsCnpj);
	calculado = calculado.substring(0, maxDigitos - 2);
	var result = "";
	for (j = 1; j <= 2; j++) {
		k = 2;
		soma = 0;
		for (i = calculado.length - 1; i >= 0; i--) {
			soma += (calculado.charAt(i) - '0') * k;
			k = (k - 1) % cicloPeso + 2
			}
		dv = 11 - soma % 11;
		if (dv > 9) dv = 0;
		calculado += dv;
		result += dv
		}
	return result
	}

function isCpf(pCpf) {
	var numero = formatCpfCnpj(pCpf, false, false);
	if (numero.length > NUM_DIGITOS_CPF) return false;
	var base = numero.substring(0, numero.length - 2);
	var digitos = dvCpfCnpj(base, false);
	var algUnico, i;
	if (numero != "" + base + digitos) return false;
	algUnico = true;
	for (i = 1; algUnico && i < NUM_DIGITOS_CPF; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	return (!algUnico);
	}

function isCnpj(pCnpj) {
	var numero = formatCpfCnpj(pCnpj, false, true);
	if (numero.length > NUM_DIGITOS_CNPJ) return false;
	var base = numero.substring(0, NUM_DGT_CNPJ_BASE);
	var ordem = numero.substring(NUM_DGT_CNPJ_BASE, 12);
	var digitos = dvCpfCnpj(base + ordem, true);
	var algUnico;
	if (numero != "" + base + ordem + digitos) return false;
	algUnico = numero.charAt(0) != '0';
	for (i = 1; algUnico && i < NUM_DGT_CNPJ_BASE; i++) algUnico = (numero.charAt(i - 1) == numero.charAt(i));
	if (algUnico) return false;
	if (ordem == "0000") return false;
	return (base == "00000000" || parseInt(ordem, 10) <= 300 || base.substring(0, 3) != "000");
	}

function isCpfCnpj(pCpfCnpj) {
	var numero = pCpfCnpj.replace(/\D/g, "");
	if (numero.length > NUM_DIGITOS_CPF) return isCnpj(pCpfCnpj);
	else return isCpf(pCpfCnpj);
	}


function verificarCNES(){
	var cnes=env.social_familia_cnes.value;
	if (cnes=='') {
		env.social_familia_cnes.style.backgroundColor = '';
		return true;
		}
	if(cnes.length!=7){
		alert('CNES inv�lido! Necessita ter 7 algarismos');
		env.social_familia_cnes.style.backgroundColor = 'red';
		env.social_familia_cnes.focus();
		}
	else {
		env.social_familia_cnes.style.backgroundColor = '';
		}
	}

function verificarINEP(){
	var inep=env.social_familia_inep.value;
	if (inep=='') {
		env.social_familia_inep.style.backgroundColor = '';
		return true;
		}
	if(inep.length!=8){
		alert('INEP inv�lido! Necessita ter 8 algarismos');
		env.social_familia_inep.style.backgroundColor = 'red';
		env.social_familia_inep.focus();
		}
	else {
		env.social_familia_inep.style.backgroundColor = '';
		}
	}

function verificarCPF(){
	var cpf=env.social_familia_cpf.value;
	if (cpf=='') {
		env.social_familia_cpf.style.backgroundColor = '';
		return true;
		}
	if(!isCpf(cpf)){
		alert('CPF inv�lido!');
		env.social_familia_cpf.style.backgroundColor = 'red';
		env.social_familia_cpf.focus();
		}
	else {
		env.social_familia_cpf.style.backgroundColor = '';
		env.social_familia_cpf.value=formatCpfCnpj(cpf, true, false);
		}
	}

function verificarCNPJ(){
	var cnpj=env.social_familia_cnpj.value;
	if (cnpj=='') {
		env.social_familia_cnpj.style.backgroundColor = '';
		return true;
		}
	if(!isCnpj(cnpj)){
		alert('CNPJ inv�lido!');
		env.social_familia_cnpj.style.backgroundColor = 'red';
		env.social_familia_cnpj.focus();
		}
	else {
		env.social_familia_cnpj.style.backgroundColor = '';
		env.social_familia_cnpj.value=formatCpfCnpj(cnpj, true, true);
		}
	}



var pi = 3.14159265358979;
/* Ellipsoide (WGS84) */
/* var sm_a = 6378137.0; */
var sm_a = 6378160.0;
var sm_b = 6356752.314;
var sm_EccSquared = 6.69437999013e-03;
var wnumero = 0
var wgrau = 0
var wmin = 0
var wsec = 0
var UTMScaleFactor = 0.9996;


function DegToRad (deg){
  return (deg / 180.0 * pi);
	}


function RadToDeg (rad){
  return (rad / pi * 180.0);
	}


function ArcLengthOfMeridian (phi){
  var alpha, beta, gamma, delta, epsilon, n;
  var result;
  n = (sm_a - sm_b) / (sm_a + sm_b);
  alpha = ((sm_a + sm_b) / 2.0) * (1.0 + (Math.pow (n, 2.0) / 4.0) + (Math.pow (n, 4.0) / 64.0));
  beta = (-3.0 * n / 2.0) + (9.0 * Math.pow (n, 3.0) / 16.0) + (-3.0 * Math.pow (n, 5.0) / 32.0);
  gamma = (15.0 * Math.pow (n, 2.0) / 16.0) + (-15.0 * Math.pow (n, 4.0) / 32.0);
  delta = (-35.0 * Math.pow (n, 3.0) / 48.0) + (105.0 * Math.pow (n, 5.0) / 256.0);
  epsilon = (315.0 * Math.pow (n, 4.0) / 512.0);
	result = alpha * (phi + (beta * Math.sin (2.0 * phi)) + (gamma * Math.sin (4.0 * phi)) + (delta * Math.sin (6.0 * phi)) + (epsilon * Math.sin (8.0 * phi)));
	return result;
	}

function UTMCentralMeridian (zone){
  var cmeridian;
  cmeridian = DegToRad (-183.0 + (zone * 6.0));
  return cmeridian;
	}


function FootpointLatitude (y){
  var y_, alpha_, beta_, gamma_, delta_, epsilon_, n;
  var result;
  n = (sm_a - sm_b) / (sm_a + sm_b);
  alpha_ = ((sm_a + sm_b) / 2.0) * (1 + (Math.pow (n, 2.0) / 4) + (Math.pow (n, 4.0) / 64));
  y_ = y / alpha_;
  beta_ = (3.0 * n / 2.0) + (-27.0 * Math.pow (n, 3.0) / 32.0) + (269.0 * Math.pow (n, 5.0) / 512.0);
  gamma_ = (21.0 * Math.pow (n, 2.0) / 16.0) + (-55.0 * Math.pow (n, 4.0) / 32.0);
  delta_ = (151.0 * Math.pow (n, 3.0) / 96.0) + (-417.0 * Math.pow (n, 5.0) / 128.0);
  epsilon_ = (1097.0 * Math.pow (n, 4.0) / 512.0);
  result = y_ + (beta_ * Math.sin (2.0 * y_))  + (gamma_ * Math.sin (4.0 * y_)) + (delta_ * Math.sin (6.0 * y_))  + (epsilon_ * Math.sin (8.0 * y_));
  return result;
	}


function MapLatLonToXY (phi, lambda, lambda0, xy){
  var N, nu2, ep2, t, t2, l;
  var l3coef, l4coef, l5coef, l6coef, l7coef, l8coef;
  var tmp;
  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
  nu2 = ep2 * Math.pow (Math.cos (phi), 2.0);
  N = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nu2));
  t = Math.tan (phi);
  t2 = t * t;
  tmp = (t2 * t2 * t2) - Math.pow (t, 6.0);
  l = lambda - lambda0;
  l3coef = 1.0 - t2 + nu2;
  l4coef = 5.0 - t2 + 9 * nu2 + 4.0 * (nu2 * nu2);
  l5coef = 5.0 - 18.0 * t2 + (t2 * t2) + 14.0 * nu2 - 58.0 * t2 * nu2;
  l6coef = 61.0 - 58.0 * t2 + (t2 * t2) + 270.0 * nu2 - 330.0 * t2 * nu2;
  l7coef = 61.0 - 479.0 * t2 + 179.0 * (t2 * t2) - (t2 * t2 * t2);
  l8coef = 1385.0 - 3111.0 * t2 + 543.0 * (t2 * t2) - (t2 * t2 * t2);
  xy[0] = N * Math.cos (phi) * l   + (N / 6.0 * Math.pow (Math.cos (phi), 3.0) * l3coef * Math.pow (l, 3.0)) + (N / 120.0 * Math.pow (Math.cos (phi), 5.0) * l5coef * Math.pow (l, 5.0)) + (N / 5040.0 * Math.pow (Math.cos (phi), 7.0) * l7coef * Math.pow (l, 7.0));
  xy[1] = ArcLengthOfMeridian (phi) + (t / 2.0 * N * Math.pow (Math.cos (phi), 2.0) * Math.pow (l, 2.0)) + (t / 24.0 * N * Math.pow (Math.cos (phi), 4.0) * l4coef * Math.pow (l, 4.0)) + (t / 720.0 * N * Math.pow (Math.cos (phi), 6.0) * l6coef * Math.pow (l, 6.0)) + (t / 40320.0 * N * Math.pow (Math.cos (phi), 8.0) * l8coef * Math.pow (l, 8.0));
  return;
	}




function MapXYToLatLon (x, y, lambda0, philambda){
  var phif, Nf, Nfpow, nuf2, ep2, tf, tf2, tf4, cf;
  var x1frac, x2frac, x3frac, x4frac, x5frac, x6frac, x7frac, x8frac;
  var x2poly, x3poly, x4poly, x5poly, x6poly, x7poly, x8poly;
  phif = FootpointLatitude (y);
  ep2 = (Math.pow (sm_a, 2.0) - Math.pow (sm_b, 2.0)) / Math.pow (sm_b, 2.0);
  cf = Math.cos (phif);
  nuf2 = ep2 * Math.pow (cf, 2.0);
  Nf = Math.pow (sm_a, 2.0) / (sm_b * Math.sqrt (1 + nuf2));
  Nfpow = Nf;
  tf = Math.tan (phif);
  tf2 = tf * tf;
  tf4 = tf2 * tf2;
  x1frac = 1.0 / (Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**2) */
  x2frac = tf / (2.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**3) */
  x3frac = 1.0 / (6.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**4) */
  x4frac = tf / (24.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**5) */
  x5frac = 1.0 / (120.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**6) */
  x6frac = tf / (720.0 * Nfpow);
  Nfpow *= Nf;   /* now equals Nf**7) */
  x7frac = 1.0 / (5040.0 * Nfpow * cf);
  Nfpow *= Nf;   /* now equals Nf**8) */
  x8frac = tf / (40320.0 * Nfpow);
  x2poly = -1.0 - nuf2;
  x3poly = -1.0 - 2 * tf2 - nuf2;
  x4poly = 5.0 + 3.0 * tf2 + 6.0 * nuf2 - 6.0 * tf2 * nuf2	- 3.0 * (nuf2 *nuf2) - 9.0 * tf2 * (nuf2 * nuf2);
  x5poly = 5.0 + 28.0 * tf2 + 24.0 * tf4 + 6.0 * nuf2 + 8.0 * tf2 * nuf2;
  x6poly = -61.0 - 90.0 * tf2 - 45.0 * tf4 - 107.0 * nuf2	+ 162.0 * tf2 * nuf2;
  x7poly = -61.0 - 662.0 * tf2 - 1320.0 * tf4 - 720.0 * (tf4 * tf2);
  x8poly = 1385.0 + 3633.0 * tf2 + 4095.0 * tf4 + 1575 * (tf4 * tf2);
  philambda[0] = phif + x2frac * x2poly * (x * x)	+ x4frac * x4poly * Math.pow (x, 4.0)	+ x6frac * x6poly * Math.pow (x, 6.0)	+ x8frac * x8poly * Math.pow (x, 8.0);
  philambda[1] = lambda0 + x1frac * x	+ x3frac * x3poly * Math.pow (x, 3.0)	+ x5frac * x5poly * Math.pow (x, 5.0)	+ x7frac * x7poly * Math.pow (x, 7.0);
  return;
	}





function LatLonToUTMXY (lat, lon, zone, xy){
  MapLatLonToXY (lat, lon, UTMCentralMeridian (zone), xy);
  /* Adjust easting and northing for UTM system. */
  xy[0] = xy[0] * UTMScaleFactor + 500000.0;
  xy[1] = xy[1] * UTMScaleFactor;
  if (xy[1] < 0.0) xy[1] = xy[1] + 10000000.0;
  return zone;
	}




function UTMXYToLatLon (x, y, zone, southhemi, latlon){
  var cmeridian;
  x -= 500000.0;
  x /= UTMScaleFactor;
  /* If in southern hemisphere, adjust y accordingly. */
  if (southhemi)
  y -= 10000000.0;
  y /= UTMScaleFactor;
 	cmeridian = UTMCentralMeridian (zone);
  MapXYToLatLon (x, y, cmeridian, latlon);
  return;
	}





function btnToUTM_OnClick (){
  var xy = new Array(2);
  if (document.env.txtlongraus.value!=null) {
   	wgrau = parseFloat (document.env.txtlongraus.value);
   	wmin = parseFloat (document.env.txtlonmin.value) / 60;
  	wsec = parseFloat (document.env.txtlonsec.value) / 3600;
   	wnumero = wgrau + wmin + wsec

   	if (wmin <0) wmin=wmin*-1;
   	if (wsec <0) wsec=wsec*-1;

		if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
		if (wgrau < 0) wnumero = wgrau - wmin - wsec ;

   	document.env.social_familia_longitude.value = wnumero;
		}
  if (isNaN (parseFloat (document.env.social_familia_longitude.value))) {
    alert ("Entre com uma longitude v�lida.");
    return false;
		}
  lon = parseFloat (document.env.social_familia_longitude.value);
  if ((lon < -180.0) || (180.0 <= lon)) {
    alert ("Entre com um n�mero para latitude entre -180, 180.");
    return false;
		}
	if (document.env.txtlatgraus.value!=null) {
    wgrau = parseFloat (document.env.txtlatgraus.value);
    wmin = parseFloat (document.env.txtlatmin.value) / 60;
    wsec = parseFloat (document.env.txtlatsec.value) / 3600;

   	wnumero = wgrau + wmin + wsec

   	if (wmin <0) wmin=wmin*-1;
   	if (wsec <0) wsec=wsec*-1;

		if (wgrau >= 0) wnumero = wgrau + wmin + wsec ;
		if (wgrau < 0) wnumero = wgrau - wmin - wsec ;


    document.env.social_familia_latitude.value = wnumero;
  	}
  if (isNaN (parseFloat (document.env.social_familia_latitude.value))) {
    alert ("Entre com uma latitude v�lida.");
    return false;
		}
  lat = parseFloat (document.env.social_familia_latitude.value);
  if ((lat < -90.0) || (90.0 < lat)) {
    alert ("Entre com um n�mero para latitude entre -90, 90.");
    return false;
		}
  zone = Math.floor ((lon + 180.0) / 6) + 1;
  zone = LatLonToUTMXY (DegToRad (lat), DegToRad (lon), zone, xy);
  document.env.txtX.value = xy[0];
  document.env.txtY.value = xy[1];
  document.env.txtZone.value = zone;
  if (lat < 0) document.env.rbtnHemisphere[1].checked = true;
  else document.env.rbtnHemisphere[0].checked = true;
  return true;
	}



function btnToGeographic_OnClick (){
  latlon = new Array(2);
  var x, y, zone, southhemi;
  if (isNaN (parseFloat (document.env.txtX.value))) {
    alert ("Entre com uma Coordenada v�ida para X.");
    return false;
		}
  x = parseFloat (document.env.txtX.value);
  x = x - 75;
  if (isNaN (parseFloat (document.env.txtY.value))) {
    alert ("Entre com uma Coordenada v�ida para Y.");
    return false;
		}
  y = parseFloat (document.env.txtY.value);
  y = y - 25;
  if (isNaN (parseInt (document.env.txtZone.value))) {
    alert ("Entre com uma Zona v�lida.");
    return false;
		}
  zone = parseFloat (document.env.txtZone.value);
  if ((zone < 1) || (60 < zone)) {
    alert ("Zona Inv�lida entre com um n�mero de 1 � 60");
    return false;
		}
  if (document.env.rbtnHemisphere[1].checked == true) southhemi = true;
  else southhemi = false;
  UTMXYToLatLon (x, y, zone, southhemi, latlon);
  document.env.social_familia_longitude.value = RadToDeg (latlon[1]);
  document.env.social_familia_latitude.value = RadToDeg (latlon[0]);
  wnumero = Math.abs(RadToDeg (latlon[1]));
  wgrau = Math.floor(wnumero);
  wmin = Math.floor((wnumero - wgrau) * 60);
  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
  document.env.txtlongraus.value = wgrau;
  document.env.txtlonmin.value = wmin;
  document.env.txtlonsec.value = wsec;
  wnumero = Math.abs(RadToDeg (latlon[0]));
  wgrau = Math.floor(wnumero);
  wmin = Math.floor((wnumero - wgrau) * 60);
  wsec = Math.floor((((wnumero - wgrau) * 60) - wmin) * 60);
  document.env.txtlatgraus.value = wgrau;
  document.env.txtlatmin.value = wmin;
  document.env.txtlatsec.value = wsec;
  return true;
	}

function converter_decimal(){
	var long=env.social_familia_longitude.value;
	grau_long = parseInt(long);
	minuto=long-grau_long;
	minuto=minuto*60;
	if (minuto < 0) minuto=minuto*-1;
	minuto_long=parseInt(minuto);
	segundo=minuto-minuto_long;
	segundo=segundo*60;
	segundo_long=parseInt(segundo);
	env.txtlongraus.value=grau_long;
	env.txtlonmin.value=minuto_long;
	env.txtlonsec.value=segundo_long;

	var lat=env.social_familia_latitude.value;
	grau_lat = parseInt(lat);
	minuto=lat-grau_lat;
	minuto=minuto*60;
	if (minuto < 0) minuto=minuto*-1;
	minuto_lat=parseInt(minuto);
	segundo=minuto-minuto_lat;
	segundo=segundo*60;
	segundo_lat=parseInt(segundo);

	env.txtlatgraus.value=grau_lat;
	env.txtlatmin.value=minuto_lat;
	env.txtlatsec.value=segundo_lat;
	}


converter_decimal();



</script>

