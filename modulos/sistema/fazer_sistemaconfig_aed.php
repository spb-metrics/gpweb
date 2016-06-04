<?php 
/*
Copyright (c) 2007-2011 The web2Project Development Team <w2p-developers@web2project.net>
Copyright (c) 2003-2007 The dotProject Development Team <core-developers@dotproject.net>
Copyright [2008] -  S�rgio Fernandes Reinert de Lima
Este arquivo � parte do programa gpweb
O gpweb � um software livre; voc� pode redistribu�-lo e/ou modific�-lo dentro dos termos da Licen�a P�blica Geral GNU como publicada pela Funda��o do Software Livre (FSF); na vers�o 2 da Licen�a.
Este programa � distribu�do na esperan�a que possa ser  �til, mas SEM NENHUMA GARANTIA; sem uma garantia impl�cita de ADEQUA��O a qualquer  MERCADO ou APLICA��O EM PARTICULAR. Veja a Licen�a P�blica Geral GNU/GPL em portugu�s para maiores detalhes.
Voc� deve ter recebido uma c�pia da Licen�a P�blica Geral GNU, sob o t�tulo "licen�a GPL 2.odt", junto com este programa, se n�o, acesse o Portal do Software P�blico Brasileiro no endere�o www.softwarepublico.gov.br ou escreva para a Funda��o do Software Livre(FSF) Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301, USA 
*/

if (!defined('BASE_DIR')) die('Voc� n�o deveria acessar este arquivo diretamente.');

if (!$Aplic->usuario_super_admin) $Aplic->redirecionar('m=publico&a=acesso_negado');
$obj = new CConfig();
$q = new BDConsulta;
$q->adTabela('config');
$q->adAtualizar('config_valor', 'false');
$q->adOnde('config_tipo = \'checkbox\'');
$rs = $q->Resultado();
$q->limpar();
$erro=0;
foreach ($_REQUEST['cfg'] as $nome => $valor) {
	$obj->config_nome = previnirXSS($nome);
	$obj->config_valor = previnirXSS($valor);
	$obj->config_id = previnirXSS($_REQUEST['cfgId'][$nome]);
	if (($msg = $obj->armazenar())) $erro++;
	}
$Aplic->setMsg('Configura��es do Sistema');	
if($erro) $Aplic->setMsg($msg, UI_MSG_ERRO);
else $Aplic->setMsg('atualizadas', UI_MSG_OK, true);	
$Aplic->redirecionar('m=sistema&a=configuracao_sistema');
?>