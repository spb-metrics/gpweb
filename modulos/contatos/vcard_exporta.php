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

$contato_id = intval(getParam($_REQUEST, 'contato_id', ''));
$podeAcessar = $Aplic->checarModulo('contatos', 'acesso');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (isset($_REQUEST['contato_id']) && !(getParam($_REQUEST, 'contato_id', '') == '')) {
	$q = new BDConsulta;
	$q->adTabela('contatos');
	$q->adUnir('cias', 'cp', 'cp.cia_id = contato_cia');
	$q->adOnde('contato_id = '.(int)$contato_id);
	$contatos = $q->Lista();
	require_once BASE_DIR.'/modulos/contatos/construir_vcard.class.php';
	$vcard = new construir_vcard();
	$vcard->setVersao('2.1');
	$vcard->setNomeFormatado($contatos[0]['contato_posto'].' '.$contatos[0]['contato_nomeguerra']);
	$vcard->setNome($contatos[0]['contato_nomeguerra'], $contatos[0]['contato_posto'], $contatos[0]['contato_tipo'], $contatos[0]['contato_arma'], '');
	$vcard->setOrigem($config['nome_om'].' gpweb: '.$config['dominio_site'].($Aplic->profissional ? '/server' : ''));
	$vcard->setTitulo($contatos[0]['contato_funcao']);
	$vcard->setURL($contatos[0]['contato_url']);
	$vcard->adParametro('WORK', null);
	$vcard->adYahoo($contatos[0]['contato_yahoo']);
	$vcard->adParametro('WORK', null);
	$vcard->adSkype($contatos[0]['contato_skype']);
	$vcard->adParametro('WORK', null);
	$vcard->adMSN($contatos[0]['contato_msn']);
	$vcard->adParametro('WORK', null);
	$vcard->adICQ($contatos[0]['contato_icq']);
	$vcard->adParametro('WORK', null);
	$vcard->adJabber($contatos[0]['contato_jabber']);
	$vcard->adParametro('WORK', null);
	$vcard->setAniversario($contatos[0]['contato_nascimento']);
	if ($contatos[0]['contato_notas']){
		$contatos[0]['contato_notas'] = str_replace("\r", ' ', $contatos[0]['contato_notas']);
		$contatos[0]['contato_notas'] = str_replace("\n", '=0D=0A=<br>', $contatos[0]['contato_notas']);
		if ($contatos[0]['contato_cpf']) $contatos[0]['contato_notas'] = 'CPF:'.$contatos[0]['contato_cpf'].('=0D=0A=<br>'.$contatos[0]['contato_notas']);
		if ($contatos[0]['contato_cnpj']) $contatos[0]['contato_notas'] = 'CNPJ:'.$contatos[0]['contato_cnpj'].'=0D=0A=<br>'.$contatos[0]['contato_notas'];
		}
	else {
		if ($contatos[0]['contato_cpf']) $contatos[0]['contato_notas'] = 'CPF:'.$contatos[0]['contato_cpf'];
		if ($contatos[0]['contato_cnpj']) $contatos[0]['contato_notas'] = 'CNPJ:'.$contatos[0]['contato_cnpj'].($contatos[0]['contato_cpf'] ? '=0D=0A=<br>'.$contatos[0]['contato_notas'] : '');
		}	
	$vcard->setNota($contatos[0]['contato_notas']);
	$vcard->adParametro('ENCODING', 'QUOTED-PRINTABLE');
	$vcard->adOrganizacao($contatos[0]['cia_nome']);
	$vcard->adDepartamento(nome_dept($contatos[0]['contato_dept']));
	$vcard->setIdExclusivo($contatos[0]['contato_cia']);
	$vcard->adTelefone('('.$contatos[0]['contato_dddtel'].') '.$contatos[0]['contato_tel']);
	$vcard->adParametro('VOICE', null);
	$vcard->adParametro('WORK', null);
	$vcard->adTelefone('('.$contatos[0]['contato_dddtel2'].') '.$contatos[0]['contato_tel2']);
	$vcard->adParametro('VOICE', null);
	$vcard->adParametro('HOME', null);
	$vcard->adTelefone('('.$contatos[0]['contato_dddcel'].') '.$contatos[0]['contato_cel']);
	$vcard->adParametro('VOICE', null);
	$vcard->adParametro('CELL', null);
	$vcard->adTelefone('('.$contatos[0]['contato_dddfax'].') '.$contatos[0]['contato_fax']);
	$vcard->adParametro('FAX', null);
	$vcard->adParametro('WORK', null);
	$vcard->adEmail($contatos[0]['contato_email']);
	$vcard->adParametro('PREF', null);
	$vcard->adParametro('INTERNET', null);
	$vcard->adEmail($contatos[0]['contato_email2']);
	$vcard->adParametro('INTERNET', null);
	$vcard->adEndereco('','', $config['nome_om']."=0D=0A=<br>".$contatos[0]['contato_endereco1']."=0D=0A=<br>".$contatos[0]['contato_endereco2'], $contatos[0]['contato_cidade'], $contatos[0]['contato_estado'], $contatos[0]['contato_cep'], $contatos[0]['contato_pais']);
	$vcard->adParametro('ENCODING', 'QUOTED-PRINTABLE');
	$vcard->adParametro('WORK', null);
	$vcard->adParametro('PREF', null);
	$texto = $vcard->fetch();
	header('Pragma: ');
	header('Cache-Control: ');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revaldataInicio'); 
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('MIME-Version: 1.0');
	header('Content-Type: text/x-vcard');
	$saida=$contatos[0]['contato_posto'].' '.$contatos[0]['contato_nomeguerra'].'.vcf';
	$saida=str_replace(' ', '_', $saida);
	header('Content-Disposition: attachment; filename='.$saida);
	print_r($texto);
	} 
else {
	$Aplic->setMsg('Um manipulador inválido de contatos foi passado à função', UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos');
	}
?>