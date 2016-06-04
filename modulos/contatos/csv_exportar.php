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

$contato_id = 1;
$podeAcessar = $Aplic->checarModulo('contatos', 'acesso');
if (!$podeAcessar) $Aplic->redirecionar("m=publico&a=acesso_negado");

if (1 == 1) {
	// Campos 1 - 5
	$texto = sprintf("%s", "\"Tratamento\",\"Primeiro nome\",\"Segundo nome\",\"Sobrenome\",\"Sufixo\",");
	// Campos 6 - 10
	$texto .= sprintf("%s", "\"Empresa\",\"Departamento\",\"Cargo\",\"Business Street\",\"Rua do endereþo comercial 2\",");
	// Campos 11 - 15
	$texto .= sprintf("%s", "\"Rua do endereþo comercial 4\",\"Business City\",\"Business State\",\"Business Postal Code\",\"PaÝs/RegiÒo da Empresa\",");
	// Campos 16 - 20
	$texto .= sprintf("%s", "\"Endereþo residencial\",\"Endereþo residencial 2\",\"Endereþo residencial 3\",\"Cidade do endereþo residencial\",\"Estado\",");
	// Campos 21 - 25
	$texto .= sprintf("%s", "\"CEP do endereþo residencial\",\"PaÝs/RegiÒo de ResidÛncia\",\"Outro endereþo\",\"Outro endereþo 2\",\"Outro endereþo 3\",");
	// Campos 26 - 30
	$texto .= sprintf("%s", "\"Cidade\",\"Estado\",\"CEP\",\"Outro PaÝs/RegiÒo\",\"Telefone do assistente\",");
	// Campos 31 - 35
	$texto .= sprintf("%s", "\"Fax comercial\",\"Telefone Comercial\",\"Telefone comercial 2\",\"Retorno de chamada\",\"Telefone do carro\",");
	// Campos 36 - 40
	$texto .= sprintf("%s", "\"Telefone principal da empresa\",\"Fax residencial\",\"Telefone residencial\",\"Telefone residencial 2\",\"ISDN\",");
	// Campos 41 - 45
	$texto .= sprintf("%s", "\"Telefone celular\",\"Outro fax\",\"Outro telefone\",\"Pager\",\"Telefone principal\",");
	// Campos 46 - 50
	$texto .= sprintf("%s", "\"Radiofone\",\"Telefone TTY/tdD\",\"Telex\",\"Anotaþ§es\",\"Birthday\",");
	// Campos 51 - 55
	$texto .= sprintf("%s", "\"Caixa postal de outro endereþo\",\"Caixa postal do endereþo comercial\",\"Caixa postal do endereþo residencial\",\"Categorias\",\"C¾digo da empresa\",");
	// Campos 56 - 60
	$texto .= sprintf("%s", "\"C¾digo do governo\",\"Conta\",\"Datas especiais\",\"Disponibilidade da Internet\",\"e-mail Address\",");
	// Campos 61 - 65
	$texto .= sprintf("%s", "\"Tipo de email\",\"Nome para exibiþÒo do email\",\"Endereþo de email 2\",\"Tipo de email 2\",\"Nome para exibiþÒo do email 2\",");
	// Campos 66 - 70
	$texto .= sprintf("%s", "\"Endereþo de email 3\",\"Tipo de email 3\",\"Nome para exibiþÒo do email 3\",\"Filhos\",\"Hobby\",");
	// Campos 71 - 75
	$texto .= sprintf("%s", "\"Idioma\",\"IndicaþÒo\",\"Informaþ§es para cobranþa\",\"Iniciais\",\"Local\",");
	// Campos 76 - 80
	$texto .= sprintf("%s", "\"Nome do assistente\",\"Nome do gerenciador\",\"Pßgina da Web\",\"Palavras-chave\",\"Particular\",");
	// Campos 81 - 85
	$texto .= sprintf("%s", "\"Personalizado 1\",\"Personalizado 2\",\"Personalizado 3\",\"Personalizado 4\",\"Prioridade\",");
	// Campos 86 - 90
	$texto .= sprintf("%s", "\"ProfissÒo\",\"Quilometragem\",\"Sala\",\"Sensibilidade\",\"Servidor de diret¾rio\",");
	// Campos 91 - 95
	$texto .= sprintf("%s", "\"Sexo\",\"Spouse\"");
	$q = new BDConsulta;
	$q->adTabela('campos_customizados_estrutura', 'cfs');
	$q->adOnde('cfs.campo_modulo = \'contatos\'');
	$q->adOrdem('cfs.campo_ordem');
	$campos_customizados = $q->Lista();
	foreach ($campos_customizados as $f) $texto .= sprintf("%s", "\"$f[campo_descricao]\",");
	$texto .= sprintf("%s\r\n", "");
	$q->limpar();
	

	$q->adTabela('contatos', 'con');
	$q->esqUnir('cias', 'co', 'co.cia_id = con.contato_cia');
	$q->esqUnir('depts', 'de', 'de.dept_id = con.contato_dept');
	$q->adCampo('con.*');
	$q->adCampo('co.cia_nome');
	$q->adCampo('de.dept_nome');
	$q->adOnde('(contato_privado=0 OR contato_privado IS NULL OR (contato_privado=1 AND contato_dono='.$Aplic->usuario_id.')	OR contato_dono IS NULL)');
	$q->adOrdem('contato_posto');
	$q->adOrdem('contato_nomeguerra');
	$contatos = $q->Lista();
	$q->limpar();
	foreach ($contatos as $linha) {
		// Campos 1 - 5
		$texto .= sprintf("\"%s\",\"%s\",\"%s\",\"%s\",,", $linha['contato_posto'], '', '', $linha['contato_nomeguerra'] );
	  // Campos 6 - 10
		$texto .= sprintf("\"%s\",\"%s\",\"%s\",\"%s\",,", $linha['cia_nome'], $linha['dept_nome'], $linha['contato_funcao'], $linha['contato_endereco1'] );
		// Campos 11 - 15
		$texto .= sprintf(",\"%s\",\"%s\",\"%s\",\"%s\",", $linha['contato_cidade'], $linha['contato_estado'], $linha['contato_cep'], $linha['contato_pais'] );
		// Campos 16 - 20
		$texto .= sprintf(",,,,,");
		// Campos 21 - 25
		$texto .= sprintf(",,,,,");
		// Campos 26 - 30
		$texto .= sprintf(",,,,,");
		// Campos 31 - 35
		$texto .= sprintf("\"%s\",\"%s\",,,,", $linha['contato_fax'], $linha['contato_tel']);
		// Campos 36 - 40
		$texto .= sprintf("\"%s\",,,,,",$linha['contato_tel2']);
		// Campos 41 - 45
		$texto .= sprintf("\"%s\",,,,,",$linha['contato_cel']);
		// Campos 46 - 50
		$texto .= sprintf(",,,\"%s\",\"%s\",",$linha['contato_notas'],$linha['contato_nascimento'] );
		// Campos 51 - 55
		$texto .= sprintf(",,,,,");
		// Campos 56 - 60
		$texto .= sprintf(",,,,\"%s\",",$linha['contato_email']);
		// Campos 61 - 65
		$texto .= sprintf(",,,,,");
		// Campos 66 - 70
		$texto .= sprintf(",,,,,");
		// Campos 71 - 75
		$texto .= sprintf(",,,,,");
		// Campos 76 - 80
		$texto .= sprintf(",,\"%s\",,,",$linha['contato_url']);
		// Campos 81 - 85
		$texto .= sprintf(",,,,,");
		// Campos 86 - 90
		$texto .= sprintf(",,,,,");
		// Campos 91 - 95
		$texto .= sprintf(",,");
		$texto .= sprintf("%s\r\n", '');
		}
	header('Pragma: ');
	header('Cache-Control: ');
	header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	header('Cache-Control: no-store, no-cache, must-revaldataInicio'); 
	header('Cache-Control: post-check=0, pre-check=0', false);
	header('MIME-Version: 1.0');
	header('Content-Type: text/x-csv');
	header('Content-Disposition: attachment; filename="contatos_'.$config['nome_om'].'.csv"');
	print_r($texto);
	} 
else {
	$Aplic->setMsg('Um manipulador inválido de contatos foi passado à função', UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos');
	}
?>