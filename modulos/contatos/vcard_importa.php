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

$podeEditar = $Aplic->checarModulo('contatos', 'adicionar');
if (!$podeEditar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (isset($_FILES['vcf']) && isset($_REQUEST['sem_cabecalho']) && (getParam($_REQUEST, 'sem_cabecalho', false))) { 
	
	//cia
	$q = new BDConsulta;
	$q->adTabela('cias');
	$q->adCampo('cia_nome, cia_id');
	$cias = $q->Lista();
	$q->limpar();
	$valorcia=array();
	foreach ($cias as $cia) $valorcia[$cia['cia_nome']]=(int)$cia['cia_id'];
	
	//dept
	$q = new BDConsulta;
	$q->adTabela('depts');
	$q->adCampo('dept_nome, dept_id');
	$depts = $q->Lista();
	$q->limpar();
	$valordept=array();
	foreach ($depts as $dept) $valordept[$dept['dept_nome']]=(int)$dept['dept_id'];
	$posto=array();
	if ($config['militar'] < 10) $posto+= getSisValor('Posto'.$config['militar']);
	else $posto+= getSisValor('PronomeTratamento');
	$valorposto=array();
	foreach ($posto as $valor_posto=> $nome_posto) $valorposto[$nome_posto]=(int)$valor_posto;
	$vcf = $_FILES['vcf'];
	require_once BASE_DIR.'/modulos/contatos/importar_vcard.class.php';
	if (is_uploaded_file($vcf['tmp_name'])) {
		$cartao = new importar_vcard();
		$cartaoinfo = $cartao->doArquivo($vcf['tmp_name']);
		foreach ($cartaoinfo as $ci) {
			$obj = new CContato();
			if ($ci['ORG'][0]['valor'][0][0]) {
				if (isset($valorcia[$ci['ORG'][0]['valor'][0][0]])) $contatoValores['contato_cia']=$valorcia[$ci['ORG'][0]['valor'][0][0]];
				else {
					//criar nova cia
					require_once ($Aplic->getClasseModulo('cias'));
					$cia= new CCia();	
					$cia->cia_nome=$ci['ORG'][0]['valor'][0][0];
					$cia->armazenar();
					$contatoValores['contato_cia']=$cia->cia_id;	
					}
				}
			if ($ci['ORG'][0]['valor'][1][0]) {
				if (isset($valorcia[$ci['ORG'][0]['valor'][1][0]])) $contatoValores['contato_dept'] =$valorcia[$ci['ORG'][0]['valor'][1][0]];
				else {
					//criar novo dept
					require_once ($Aplic->getClasseModulo('depts'));
					$dept= new CDept();	
					$dept->dept_responsavel=null;
					$dept->dept_nome=$ci['ORG'][0]['valor'][1][0];
					$dept->dept_cia=$contatoValores['contato_cia']; 
					$dept->armazenar();
					$contatoValores['contato_dept']=$dept->dept_id;	
					}
				}
			$contatoValores['contato_funcao'] = $ci['TITLE'][0]['valor'][0][0];
			$contatoValores['contato_nomeguerra'] = $ci['N'][0]['valor'][0][0];
			$contatoValores['contato_posto'] = $ci['N'][0]['valor'][1][0];
			$contatoValores['contato_posto_valor'] =(isset($valorposto[$contatoValores['contato_posto']]) ? $valorposto[$contatoValores['contato_posto']] : 50);
			$contatoValores['contato_arma'] = $ci['N'][0]['valor'][3][0];
			$contatoValores['contato_nascimento'] = $ci['BDAY'][0]['valor'][0][0];
			$contatoValores['contato_tipo'] = $ci['N'][0]['valor'][2][0];
			$i=-1;
			while (isset($ci['EMAIL'][++$i])){
				if (in_array('PREF', $ci['TEL'][$i]['param']['TYPE'])) $contatoValores['contato_email'] = $ci['EMAIL'][$i]['valor'][0][0];
				else $contatoValores['contato_email2'] = $ci['EMAIL'][$i]['valor'][0][0];
				}
			$i=-1;
			while (isset($ci['TEL'][++$i])){
				if (in_array('VOICE', $ci['TEL'][$i]['param']['TYPE'])){
					if (in_array('CELL', $ci['TEL'][$i]['param']['TYPE'])){
						$valores=retirar_ddd($ci['TEL'][$i]['valor'][0][0]);
						if(isset($valores[1])){
							$contatoValores['contato_dddcel'] = $valores[0];
							$contatoValores['contato_cel'] = $valores[1];
							}
						else $contatoValores['contato_cel'] = $valores[0];
						} 
					elseif (in_array('HOME', $ci['TEL'][$i]['param']['TYPE'])){
						$valores=retirar_ddd($ci['TEL'][$i]['valor'][0][0]);
						if(isset($valores[1])){
							$contatoValores['contato_dddtel2'] = $valores[0];
							$contatoValores['contato_tel2'] = $valores[1];
							}
						else $contatoValores['contato_tel2'] = $valores[0];
						}
					elseif (in_array('WORK', $ci['TEL'][$i]['param']['TYPE'])){
						$valores=retirar_ddd($ci['TEL'][$i]['valor'][0][0]);
						if(isset($valores[1])){
							$contatoValores['contato_dddtel'] = $valores[0];
							$contatoValores['contato_tel'] = $valores[1];
							}
						else $contatoValores['contato_tel'] = $valores[0];
						} 
					}
				if (in_array('FAX', $ci['TEL'][$i]['param']['TYPE'])){
					$valores=retirar_ddd($ci['TEL'][$i]['valor'][0][0]);
					if(isset($valores[1])){
						$contatoValores['contato_dddfax'] = $valores[0];
						$contatoValores['contato_fax'] = $valores[1];
						}
					else $contatoValores['contato_fax'] = $valores[0];
					} 
				}
			$contatoValores['contato_endereco1'] = $ci['ADR'][0]['valor'][2][0];
			$contatoValores['contato_cidade'] = $ci['ADR'][0]['valor'][3][0];
			$contatoValores['contato_estado'] = $ci['ADR'][0]['valor'][4][0];
			$contatoValores['contato_cep'] = $ci['ADR'][0]['valor'][5][0];
			$contatoValores['contato_pais'] = $ci['ADR'][0]['valor'][6][0];
			$contatoValores['contato_notas'] = $ci['NOTE'][0]['valor'][0][0];
			$contatoValores['contato_yahoo'] = $ci['X-YAHOO'][0]['valor'][0][0];
			$contatoValores['contato_msn'] = $ci['X-MSN'][0]['valor'][0][0];
			$contatoValores['contato_icq'] = $ci['X-ICQ'][0]['valor'][0][0];
			$contatoValores['contato_jabber'] = $ci['X-JABBER'][0]['valor'][0][0];
			$contatoValores['contato_skype'] = $ci['X-SKYPE-USERNAME'][0]['valor'][0][0];
			$contatoValores['contato_ordem'] = $contatoValores['contato_posto'].' '.$contatoValores['contato_nomeguerra'];
			$contatoValores['contato_id'] = 0;
			if (!$obj->join($contatoValores)) {
				$Aplic->setMsg($obj->getErro(), UI_MSG_ERRO);
				$Aplic->redirecionar('m=contatos');
				}
			if (($msg = $obj->armazenar())) $Aplic->setMsg($msg, UI_MSG_ERRO);
			}
		$Aplic->setMsg('vCard importado', UI_MSG_OK, true);
		$Aplic->redirecionar('m=contatos');
		} 
	else { 
		$Aplic->setMsg('Falha no Upload do Vcard', UI_MSG_ERRO);
		$Aplic->redirecionar('m=contatos');
		}
	} 
elseif (isset($_REQUEST['dialogo']) && (getParam($_REQUEST, 'dialogo', null) == '0')) { 
	$botoesTitulo = new CBlocoTitulo('Importar vCard', 'contatos.png', $m, $m.'.'.$a);
	$botoesTitulo->adicionaBotao('m=contatos', 'lista de contatos','','Lista de Contatos','Visualizar a lista de contatos.');
	$botoesTitulo->mostrar();
	echo '<form name="vcfFrm" enctype="multipart/form-data" method="post">';
	echo '<input type="hidden" name="m" value="contatos" />';
	echo '<input type="hidden" name="a" value="vcard_importa" />';
	echo '<input type="hidden" name="sem_cabecalho" value="true" />';
	
	echo '<input type="hidden" name="max_file_size" value="109605000" />';
	echo estiloTopoCaixa();
	echo '<table width="100%" border=0 cellpadding="3" cellspacing="3" class="std"><tr><td>&nbsp;</td></tr>';
	echo '<tr><td align="center" nowrap="nowrap">'.dica('Arquivo vCard', 'Selecione o arquivo no formato vCard a ser importado.').'Arquivo vCard:'.dicaF().'<input name="vcf" type="File" style="width:280px" accept="text/x-vcard"></td></tr>';
	echo '<tr><td nowrap="nowrap">'.botao('salvar', 'Salvar', 'Salvar a importação.','','vcfFrm.submit()').'</td><td align="right" align="left">'.botao('cancelar', 'Cancelar', 'Cancelar e retornar a tela anterior.','','url_passar(0, \''.$Aplic->getPosicao().'\');').'</td></tr>';
	echo '</table></form>';
	echo estiloFundoCaixa();
	} 
else { 
	$Aplic->setMsg('Não foi possível importar o vCard', UI_MSG_ERRO);
	$Aplic->redirecionar('m=contatos');
	}

function retirar_ddd($numero){
	$numero = str_replace(" ", "", $numero);	
	$numero = str_replace("(", "", $numero);
	$vetor = explode(")", $numero);
	return $vetor;
	}	
	
?>