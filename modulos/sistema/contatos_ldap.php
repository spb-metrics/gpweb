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

if (!$dialogo) $Aplic->salvarPosicao();
$podeEditar = $Aplic->checarModulo('contatos', 'editar');
$podeAcessar = $Aplic->checarModulo('contatos', 'acesso');
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');

$sql_ldap_mapeado = array('givenname' => 'first_name', 'sn' => 'last_name', 'title' => 'job', 'o' => 'cia', 'ou' => 'secao', 'personaltitle' => 'title', 'employeetype' => 'tipo', 'mail' => 'mail', 'telephonenumber' => 'phone', 'homephone' => 'phone2', 'fax' => 'fax', 'mobile' => 'mobile', 'postaladdress' => 'address1', 'l' => 'city', 'st' => 'state', 'postalcode' => 'zip', 'c' => 'country', 'comment' => 'notas');
$botoesTitulo = new CBlocoTitulo('Importar contatos do diretório LDAP', '', 'admin', '');
$botoesTitulo->adicionaBotao('m=sistema', 'sistema','','Administração do Sistema','Voltar à tela de Administração do Sistema.');
$botoesTitulo->mostrar();
if (isset($_REQUEST['servidor'])) $Aplic->setEstado('LDAPServidor', getParam($_REQUEST, 'servidor', null));
$servidor = $Aplic->getEstado('LDAPServidor', '');
if (isset($_REQUEST['unir_nome'])) $Aplic->setEstado('LDAPUnirNome', getParam($_REQUEST, 'unir_nome', null));
$unir_nome = $Aplic->getEstado('LDAPUnirNome', '');
$unir_senha = getParam($_REQUEST, 'unir_senha', '');
if (isset($_REQUEST['porta'])) $Aplic->setEstado('LDAPPorta', getParam($_REQUEST, 'porta', null));
$porta = $Aplic->getEstado('LDAPPorta', '389');
if (isset($_REQUEST['dn'])) $Aplic->setEstado('LDAPDN', getParam($_REQUEST, 'dn', null));
$dn = $Aplic->getEstado('LDAPDN', '');
if (isset($_REQUEST['filtro'])) $Aplic->setEstado('LDAPFiltro', getParam($_REQUEST, 'filtro', null));
$filtro = $Aplic->getEstado('LDAPFiltro', '(objectclass=Person)');
$import = getParam($_REQUEST, 'import');
$test = getParam($_REQUEST, 'test');
$Aplic->setEstado('LDAPProto', getParam($_REQUEST, 'ldap_proto'));
$proto = $Aplic->getEstado('LDAPProto', '3');
echo '<form method="post" name="frmldap">';
echo '<input type="hidden" name="m" value="sistema" />';
echo '<input type="hidden" name="a" value="contatos_ldap" />';
echo '<input type="hidden" name="test" id="test" value="0" />';
echo '<input type="hidden" name="import" id="import" value="0" />';

echo estiloTopoCaixa();
echo '<table border=0 cellpadding="2" cellspacing="1" width="100%" class="std">';
echo '<tr><td align="right" nowrap="nowrap">Servidor:</td><td><input type="text" class="texto" name="servidor" value="'.$servidor.'" size="50" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Porta:</td><td><input type="text" class="texto" name="porta" value="'.$porta.'" size="4" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Protocolo:</td><td>Versão 2 <input type="radio" name="ldap_proto" value="2"'.($proto == '2' ? ' checked="checked"' : '').' />  Versão 3 <input type="radio" name="ldap_proto" value="3"'.($proto == '3' ? ' checked="checked"' : '').' /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Associar Nome:</td><td><input type="text" class="texto" name="unir_nome" value="'.$unir_nome.'" size="50" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Associar Senha:</td><td><input type="password" class="texto" name="unir_senha" value="'.$unir_senha.'" size="25" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Base DN:</td><td><input type="text" class="texto" name="dn" value="'.$dn.'" size="100" /></td></tr>';
echo '<tr><td align="right" nowrap="nowrap">Filtro:</td><td><input type="text" class="texto" name="filtro" value="'.$filtro.'" size="100" /></td></tr>';
echo '<tr><td align="left">'.botao('testar conexão e consulta', 'Testar Conexão e Consulta', 'Verificar se é possível importar  contatos via LDAP.','','document.getElementById(\'test\').value=1; frmldap.submit()').'</td><td align="right">'.botao('importar contatos', 'Importar Contatos', 'Serão importartados os contatos via LDAP.','','document.getElementById(\'import\').value=1; frmldap.submit()').'</td></tr>';
echo '<tr><td colspan="2"><pre>';
$s = '<b>';
if (isset($test)) $s .= $test;
if (isset($import)) $s .= $import;
$s .= '</b><hr />';
if (isset($test) || isset($import)) {
	if (function_exists('ldap_connect')) $ds = @ldap_connect($servidor, $porta);
	else {
		$s .= '<span style="color:red;font-weight:bold;">A função ldap_connect não está instalada.</span><br />';
		$ds = false;
		}
	if (!$ds) {
		if (function_exists('ldap_error')) $s .= ldap_error($ds);
		else $s .= '<span style="color:red;font-weight:bold;">a conexão ldap falhou.</span><br />';
		} 
	else $s .= 'ldap_connect funcionou.<br />';
	if (function_exists('ldap_set_option')) @ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, $proto);
	else $s .= '<span style="color:red;font-weight:bold;">A função ldap_set_option não está instalada.</span><br />';
	if (!function_exists('ldap_bind')){
		$s .= '<span style="color:red;font-weight:bold;">a função ldap_bind não está instalada.</span><br />';
		}
	elseif (!@ldap_bind($ds, $unir_nome, $unir_senha)) {
		$s .= '<span style="color:red;font-weight:bold;">ldap_bind falhou.</span><br />';
		if (function_exists('ldap_error')) $s .= ldap_error($ds);
		} 
	else $s .= 'ldap_unir funcionou.<br />';
	$retornaroTipos = array();
	foreach ($sql_ldap_mapeado as $ldap => $sql) $retornaroTipos[] = $ldap;
	$s .= 'Base DN: '.$dn.'<br />';
	$s .= 'Expressão: '.$filtro.'<br />';
	if (function_exists('ldap_search')) $sr = @ldap_search($ds, $dn, $filtro, $retornaroTipos);
	else $sr = false;
	if ($sr) $s .= 'Pesquisa completa com sucesso.<br />';
	else {
		$s .= '<span style="color:red;font-weight:bold;">ldap_search falhou.</span><br />';
		if (function_exists('ldap_error')) $s .= 'Erros na procura: ['.ldap_errno($ds).'] '.ldap_error($ds).'<br />';
		}
	$s .= '</pre>';
	if (function_exists('ldap_get_entries')) $info = @ldap_get_entries($ds, $sr);
	else {
		$s .= '<span style="color:red;font-weight:bold;">A função ldap_get_entries não está instalada.</span><br />';
		$info = array();		
		}
	if (!isset($info['count']) || (isset($info['count']) && !$info['count']) ) $s .= 'Nenhum contato foi encontrado.<br>';
	else {
		$s .= 'Total de contatos encontrados:'.$info['count'].'<hr />';
		$s .= '<table border=0 cellpadding="1" cellspacing=0 width="98%" class="std">';
		if (isset($test)) {
			foreach ($sql_ldap_mapeado as $ldap => $sql) $s .= '<th>'.$sql.'</th>';
			} 
		else {
			$q = new BDConsulta;
			$q->adTabela('contatos');
			$q->adCampo('contato_id, contato_posto, contato_nomeguerra');
			$q->adOrdem(($config['militar'] < 10 ? 'contato_posto_valor, contato_nomeguerra' : 'contato_nomeguerra'));
			$contatos = $q->Lista();
			$q->limpar();
			foreach ($contatos as $contato) $listaContato[$contato['contato_posto'].' '.$contato['contato_nomeguerra']] = $contato['contato_id'];
			unset($contatos);
			}
		for ($i = 0, $i_cmp = $info['count']; $i < $i_cmp; $i++) {
			$juntar = array();
			$s .= '<tr>';
			foreach ($sql_ldap_mapeado as $ldap_nome => $sql_nome) {
				if (isset($info[$i][$ldap_nome][0])) $val = limpar_valor($info[$i][$ldap_nome][0]);
				if (isset($val) && $ldap_nome == 'postaladdress') $val = str_replace('$', "\r", $val);
				if (isset($val)) {
					if (isset($test) && $ldap_nome == 'mail' && substr_count($val, '%') > 0) {
						$s .= '<td><span style="color:#880000;">e-mail ruim</span></td>';
						continue;
						}
					$juntar['contato_'.$sql_nome] = $val;
					if (isset($test)) $s .= '<td>'.$val.'</td>';
					} 
				elseif (isset($test)) $s .= '<td>-</td>';
				}
			if (isset($import)) {
				$juntar['contato_ordem'] = $juntar['contato_posto'].' '.$juntar['contato_nomeguerra'];
				if (isset($listaContato[$juntar['contato_posto'].' '.$juntar['contato_nomeguerra']])) {
					$juntar['contato_id'] = $listaContato[$juntar['contato_posto'].' '.$juntar['contato_nomeguerra']];
					$q = new BDConsulta;
					$q->adCampo('cia_id');
					$q->adTabela('cias');
					$q->adOnde('cia_nome LIKE \'%'.trim($juntar['contato_cia']).'%\'');
					$cia_id = $q->Resultado();
					$juntar['contato_cia'] = $cia_id ? $cia_id : 0;
					$q->limpar();
					$q = new BDConsulta;
					$q->adCampo('dept_id');
					$q->adTabela('depts');
					$q->adOnde('dept_nome LIKE \'%'.trim($juntar['contato_dept']).'%\'');
					$dept_id = $q->Resultado();
					$juntar['contato_dept'] = $dept_id ? $dept_id : 0;
					$q->limpar();
					$q = new BDConsulta;
					$q->atualizarVetor('contatos', $juntar, 'contato_id');
					$q->limpar();
					$s .= '<td><span style="color:#880000;">Há um registro duplicado para '.$juntar['contato_posto'].' '.$juntar['contato_nomeguerra'].', o registro foi atualizado.</span></td>';
					} 
				else {
					if (!trim($juntar['contato_posto'].' '.$juntar['contato_nomeguerra'])) continue;
					$s .= '<td>Adicionando '.$juntar['contato_posto'].' '.$juntar['contato_nomeguerra'].'.</td>';
					$q = new BDConsulta;
					$q->adCampo('cia_id');
					$q->adTabela('cias');
					$q->adOnde('cia_nome LIKE \'%'.trim($juntar['contato_cia']).'%\'');
					$cia_id = $q->Resultado();
					$juntar['contato_cia'] = $cia_id ? $cia_id : 0;
					$q->limpar();
					$q = new BDConsulta;
					$q->adCampo('dept_id');
					$q->adTabela('depts');
					$q->adOnde('dept_nome LIKE \'%'.trim($juntar['contato_dept']).'%\'');
					$dept_id = $q->Resultado();
					$juntar['contato_dept'] = $dept_id ? $dept_id : 0;
					$q->limpar();
					$q = new BDConsulta;
					$q->inserirVetor('contatos', $juntar);
					$q->limpar();
					}
				}
			$s .= '</tr>';
			}
		$s .= '</table>';
		}
	if (function_exists('ldap_close')) ldap_close($ds);
	else $s .= '<span style="color:red;font-weight:bold;">A função ldap_close não está instalada.</span>';
	}
echo $s;
echo '</td></tr></table>';
echo estiloFundoCaixa();

function limpar_valor($str) {
	$bad_valores = array("'");
	return str_replace($bad_valores, '', $str);
	}
?>
