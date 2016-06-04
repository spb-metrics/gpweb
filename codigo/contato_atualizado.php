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

require_once '../base.php';
require_once BASE_DIR.'/config.php';
if (!isset($GLOBALS['OS_WIN'])) $GLOBALS['OS_WIN'] = (stristr(PHP_OS, "WIN") !== false);
require_once BASE_DIR.'/incluir/funcoes_principais.php';
require_once BASE_DIR.'/incluir/db_adodb.php';
require_once BASE_DIR.'/classes/BDConsulta.class.php';
require_once BASE_DIR.'/classes/ui.class.php';
$Aplic = new CAplic();
$Aplic->carregarPrefs(0);
require_once BASE_DIR.'/classes/data.class.php';
require_once BASE_DIR.'/modulos/contatos/contatos.class.php';
require BASE_DIR.'/estilo/rondon/sobrecarga.php';


echo '<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">';
echo '<head>';
echo '<meta name="Description" content="gpweb Default Style" />';
echo '<meta name="Version" content="'.$Aplic->getVersao().'" />';
echo '<meta http-equiv="Content-Type" content="text/html;charset='.(isset($localidade_tipo_caract) ? $localidade_tipo_caract : 'iso-8859-1').'" />';
echo '<title>'.(isset($config['gpweb']) ? $config['gpweb'] : 'gpweb').'</title>';
echo '<link rel="stylesheet" type="text/css" href="../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css" media="all" />';
echo '<style type="text/css" media="all">@import "../estilo/rondon/estilo_'.(isset($config['estilo_css']) ? $config['estilo_css'] : 'metro').'.css";</style>';
echo '<link rel="shortcut icon" href="../estilo/rondon/imagens/organizacao/10/favicon.ico" type="image/ico" />';
echo '<script type="text/javascript" src="../js/gpweb.js"></script>';
echo '<script type="text/javascript" src="../lib/mootools/mootools.js"></script>';


$chave_atual = getParam($_REQUEST, 'chave_atual', 0);
$q = new BDConsulta;
$q->adTabela('contatos');
$q->adCampo('contato_id');
$q->adOnde('contato_chave_atualizacao= \''.$chave_atual.'\'');
$chave_contato = $q->Lista();
$q->limpar();
$contato_id = count($chave_contato) ? $chave_contato[0]['contato_id'] : null;
$cia_id = intval(getParam($_REQUEST, 'cia_id', 0));
$cia_nome = getParam($_REQUEST, 'cia_nome', null);
if (!$contato_id) {
	echo 'Não tem autorização para usar esta página. Se deseja autorização, entre em contato com '.$config['nome_om'].' para lhe enviem um link válido.';
	exit;
	}
$msg = '';



$linha = new CContato();
if (!$linha->load($contato_id) && $contato_id) {
	$Aplic->setMsg('Contato');
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar();
	}
elseif ($linha->contato_privado && $linha->contato_dono != $Aplic->usuario_id && $linha->contato_dono && $contato_id) $Aplic->redirecionar('m=publico&a=acesso_negado');
$df = '%d/%m/%Y';


$ttl = $contato_id ? 'Editar Contato' : 'Adicionar Contato';
$cia_detalhe = $linha->getCiaDetalhes();
$dept_detalhe = $linha->getDetalhesProfundos();
if ($contato_id == null && $cia_id) {
	$cia_detalhe['cia_id'] = $cia_id;
	$cia_detalhe['cia_nome'] = $cia_nome;
	echo $cia_nome;
	}
$estilo_ui = 'rondon';
$visitante = $linha->contato_posto.' '.$linha->contato_nomeguerra;


$paises = array('' => '(Selecione um país)') + getSisValor('Paises');

$estado=array('' => '');
$q->adTabela('estado');
$q->adCampo('estado_sigla, estado_nome');
$q->adOrdem('estado_nome');
$estado+= $q->listaVetorChave('estado_sigla', 'estado_nome');
$q->limpar();


echo '</HEAD><body>';
echo '<table width="100%" cellspacing=0 cellpadding=0 border=0><tr><td align=center>'.dica('Site do '.$config['gpweb'], 'Clique para entrar no site oficial do '.$config['gpweb'].'.').'<a href="'.$config['endereco_site'].'" target="_blank"><img border=0 alt="gpweb" src="'.$Aplic->gpweb_logo.'"/></a>'.dicaF().'</td></tr><tr><td>&nbsp;</td></tr></table>';
echo '<br>';

echo '<form name="frmEditar" action="fazer_atualizar_contato.php" method="post">';
echo '<input type="hidden" name="contato_atualizacao_exclusiva" value="'.uniqid('').'" />';
echo '<input type="hidden" name="chave_atual" value="'.$chave_atual.'" />';
echo '<input type="hidden" name="contato_id" value="'.$contato_id.'" />';
echo '<input type="hidden" name="contato_dono" value="'.($linha->contato_dono ? $linha->contato_dono : ($Aplic->usuario_id > 0 ? $Aplic->usuario_id : null)).'" />';
echo '<input type="hidden" name="contato_cia" value="'.($linha->contato_cia ? $linha->contato_cia : null).'" />';
echo '<input type="hidden" name="contato_dept" value="'.($linha->contato_dept ? $linha->contato_dept : null).'" />';

echo estiloTopoCaixa('800','../');
echo '<table border=0 cellpadding=0 cellspacing=1 width="800" class="std" align=center>';
echo '<tr><td colspan="2"><table border=0 cellpadding="1" cellspacing="1">';
echo '<tr><td nowrap="nowrap"><b>Atualize as informações de cadastro:</b></td><td><input type="hidden" class="texto" size="25" name="contato_posto" value="'.$linha->contato_posto.'" maxlength="50" /></td></tr>';
echo '<tr><td><input type="hidden" class="texto" size="25" name="contato_nomeguerra" value="'.$linha->contato_nomeguerra.'" maxlength="50" '.($contato_id == 0 ? 'onblur="ordenarPorNome(\'name\')"' : '').' /></td></tr>';
echo '<input type="hidden" class="texto" size="25" name="contato_ordem" value="'.$linha->contato_ordem.'" maxlength="50" /></table></td></tr>';
echo '<td valign="top" width="50%">';
echo '<table border=0 cellpadding="1" cellspacing="1" class="details" width="100%">';
echo '<tr><td align="right" width="100">Função:</td><td nowrap="nowrap"><input type="text" class="texto" name="contato_funcao" value="'.$linha->contato_funcao.'" maxlength="100" size="25" /></td></tr>';
if ($config['militar']< 10) echo '<tr><td align="right">Arma/Quadro/Sv:</td><td><input type="text" class="texto" name="contato_arma" value="'.$linha->contato_arma.'" maxlength="50" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Tipo:</td><td><input type="text" class="texto" name="contato_tipo" value="'.$linha->contato_tipo.'" maxlength="50" size="25" /></td></tr>';
echo '<tr><td align="right" width="100" nowrap="nowrap">Endereço:</td><td><input type="text" class="texto" name="contato_endereco1" value="'.$linha->contato_endereco1.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Complemento:</td><td><input type="text" class="texto" name="contato_endereco2" value="'.$linha->contato_endereco2.'" maxlength="60" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Município:</td><td><input type="text" class="texto" name="contato_cidade" value="'.$linha->contato_cidade.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Estado:</td><td>'.selecionaVetor($estado, 'contato_estado','class="texto" size=1', $linha->contato_estado).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">CEP:</td><td><input type="text" class="texto" name="contato_cep" value="'.$linha->contato_cep.'" maxlength="11" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">País:</td><td>'.selecionaVetor($paises, 'contato_pais', 'size="1" class="texto"', ($linha->contato_pais ? $linha->contato_pais : 'BR')).'</td></tr>';
echo '<tr><td align="right" width="100" nowrap="nowrap">Telefone Comercial:</td><td><input type="text" class="texto" name="contato_tel" value="'.$linha->contato_tel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Telefone Residencial:</td><td><input type="text" class="texto" name="contato_tel2" value="'.$linha->contato_tel2.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Fax:</td><td><input type="text" class="texto" name="contato_fax" value="'.$linha->contato_fax.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Celular:</td><td><input type="text" class="texto" name="contato_cel" value="'.$linha->contato_cel.'" maxlength="30" size="25" /></td></tr>';
echo '<tr><td align="right" width="100" nowrap="nowrap">E-mail:</td><td nowrap="nowrap"><input type="text" class="texto" name="contato_email" value="'.$linha->contato_email.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">2º E-Mail:</td><td><input type="text" class="texto" name="contato_email2" value="'.$linha->contato_email2.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Página Web:</td><td><input type="text" class="texto" name="contato_url" value="'.$linha->contato_url.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">ICQ:</td><td><input type="text" class="texto" name="contato_icq" value="'.$linha->contato_icq.'" maxlength="20" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">MSN:</td><td><input type="text" class="texto" name="contato_msn" value="'.$linha->contato_msn.'" maxlength="255" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Yahoo:</td><td><input type="text" class="texto" name="contato_yahoo" value="'.$linha->contato_yahoo.'" maxlength="255" size="25" /></td></tr></tr>';
$data = new CData($linha->contato_nascimento);
echo '<tr><td align="right" nowrap="nowrap">Aniversário:</td><td nowrap="nowrap"><input type="text" class="texto" name="contato_nascimento" value="'.( $linha->contato_nascimento && $linha->contato_nascimento!='0000-00-00' ? $data->format($df) : '').'" maxlength="10" size="25" onkeyup="barra(this)" />(dd/mm/aaaa)</td></tr>';
echo '<tr><td align="right" colspan="3">';
require_once BASE_DIR.'/classes/CampoCustomizados.class.php';
$campos_customizados = new CampoCustomizados('contatos', $linha->contato_id, 'editar', 1);
$campos_customizados->imprimirHTML();
echo '</td></tr>';
echo '</table></td>';
echo '<td valign="top" width="50%"><b>Notas do Contato</b><br /><textarea class="textarea" name="contato_notas" rows="20" cols="40">'.$linha->contato_notas.'</textarea></td></tr>';
echo '<tr><td colspan="2"></td><td align="right">'.botao('salvar', 'Salvar', 'Salvar os dados.','','enviarDados()').'</td></tr>';
echo '</form></table>';
echo estiloFundoCaixa('800','../');
$Aplic->carregarRodapeJS();
echo '</body></HTML>';

?>

<script type="text/javascript">

function barra(objeto){
	if (objeto.value.length == 2 || objeto.value.length ==5) objeto.value = objeto.value+"/";
	}

function enviarDados() {
	var form = document.frmEditar;
	if (form.contato_nomeguerra.value.length < 1) {
		alert("Insira um nome de contato válido" );
		form.contato_nomeguerra.focus();
		}
	else form.submit();
	}

function ordenarPorNome( x ){
	var form = document.frmEditar;
	if (x == 'nome') form.contato_ordem.value = form.contato_nomeguerra.value + ", " + form.contato_posto.value;
	else form.contato_ordem.value = form.contato_cia_nome.value;
	}
</script>
