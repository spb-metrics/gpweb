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

$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$cia_id = intval(getParam($_REQUEST, 'cia_id', 0));
if (!$podeAcessar) $Aplic->redirecionar('m=publico&a=acesso_negado');
if (isset($_REQUEST['tab'])) $Aplic->setEstado('CiaVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('CiaVerTab') !== null ? $Aplic->getEstado('CiaVerTab') : 0;

$podeEditarDept=$Aplic->checarModulo('depts', 'editar');

$sql = new BDConsulta;

$msg = '';
$obj = new CCia();

$obj->load($cia_id);



if (!$obj) {
	$Aplic->setMsg($config['organizacao']);
	$Aplic->setMsg('informações erradas', UI_MSG_ERRO, true);
	$Aplic->redirecionar('m=cias');
	} 
else $Aplic->salvarPosicao();
if (!permiteAcessarCia($cia_id, $obj->cia_acesso)) $Aplic->redirecionar('m=publico&a=acesso_negado');

$permiteEditar=permiteEditarCia($cia_id, $obj->cia_acesso);


if (getParam($_REQUEST, 'superior', 0) && $podeEditar && $permiteEditar && ($Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin))){
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_superior', $cia_id);
	$sql->adOnde('cia_superior IS NULL OR cia_superior=cia_id');
	if (!$sql->exec()) die('Não foi possível atualizar cias.');
	$sql->limpar();
	
	$sql->adTabela('cias');
	$sql->adAtualizar('cia_superior', null);
	$sql->adOnde('cia_id='.(int)$cia_id);
	if (!$sql->exec()) die('Não foi possível atualizar cias.');
	$sql->limpar();
	ver2(ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' se tornou '.$config['genero_organizacao'].' primeir'.$config['genero_organizacao'].' no organograma.');
	}

$sql->adTabela('cias');
$sql->esqUnir('estado', 'estado', 'cia_estado=estado.estado_sigla');
$sql->esqUnir('municipios', 'municipios', 'cia_cidade=municipio_id');
$sql->adCampo('estado_nome, municipio_nome');
$sql->adOnde('cia_id='.(int)$cia_id);
$endereco= $sql->Linha();
$sql->limpar();


$projStatus = getSisValor('StatusProjeto');
$tipos = getSisValor('TipoOrganizacao');
$paises = getPais('Paises');

if (!$dialogo && !$Aplic->profissional) {
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_organizacao'].' '.$config['organizacao'], 'organizacao.png', $m, "$m.$a");
	if ($Aplic->usuario_super_admin) $botoesTitulo->adicionaBotaoCelula('', 'url_passar(0, \'m=cias&a=editar\');', 'nov'.$config['genero_organizacao'].' '.$config['organizacao'], '', 'Nov'.$config['genero_organizacao'].' '.$config['organizacao'], 'Criar uma nov'.$config['genero_organizacao'].' '.$config['organizacao'].' no Sistema.');
	if ($Aplic->checarModulo('depts', 'adicionar') &&(($cia_id!=$Aplic->usuario_cia && $Aplic->usuario_super_admin) || ($cia_id==$Aplic->usuario_cia &&$Aplic->usuario_admin))) $botoesTitulo->adicionaBotaoCelula('', 'url_passar(0, \'m=depts&a=editar&cia_id='.(int)$cia_id.'\');', 'nov'.$config['genero_dept'].' '.strtolower($config['dept']), '', 'Nov'.$config['genero_dept'].' '.$config['departamento'], 'Criar um'.$config['genero_dept'].' nov'.$config['genero_dept'].' '.$config['departamento'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].' atual.');
	$botoesTitulo->adicionaBotao('m=cias', 'lista','','Lista de '.$config['organizacao'],'Visualizar a lista de todas as '.$config['organizacao'].' cadastradas no Sistema.');
	if (($podeEditar && $permiteEditar) || $Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin)) {
		$botoesTitulo->adicionaBotao('m=cias&a=editar&cia_id='.(int)$cia_id, 'editar','','Editar est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'],'Editar os detalhes relativos a est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
		if (!(isset($config['restrito']) && $config['restrito']) && $podeExcluir && $Aplic->usuario_super_admin && $cia_id!=$Aplic->usuario_cia)	$botoesTitulo->adicionaBotaoExcluir('excluir', $podeExcluir, $msg,'Excluir '.$config['organizacao'],'Excluir est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].' do sistema.<br><br>Exclua antes '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
		if (!(isset($config['restrito']) && $config['restrito']) && $podeExcluir && $Aplic->usuario_super_admin)	$botoesTitulo->adicionaBotao('m=cias&a=ver&superior=1&cia_id='.(int)$cia_id, 'tornar superior','','Tornar Superior','Clique neste botão para que est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].' se torna '.$config['genero_organizacao'].' primeir'.$config['genero_organizacao'].' no organograma. '.ucfirst($config['genero_organizacao']).' que atualmente ocupa '.$config['genero_organizacao'].' primeir'.$config['genero_organizacao'].' posição será imediatamente subordinad'.$config['genero_organizacao'].' a '.($config['genero_organizacao']=='a' ? 'esta' : 'este').'.');
		}
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	}


if (!$dialogo && $Aplic->profissional){	
		$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_organizacao'].' '.ucfirst($config['organizacao']).'', 'organizacao.png', $m, $m.'.'.$a);
		$botoesTitulo->mostrar();
		echo estiloTopoCaixa();
		echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
		echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
		require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
		$km = new CoolMenu("km");
		$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
		$km->styleFolder="default";
		$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
		$km->Add("ver","ver_lista_cias",dica('Lista de '.$config['organizacoes'],'Visualizar a lista de tod'.$config['genero_organizacao'].'s '.strtolower($config['organizacoes']).' cadastradas.').'Lista de '.ucfirst($config['organizacoes']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=cias\");");
		if (($podeEditar && $permiteEditar) || $Aplic->usuario_super_admin || ($cia_id==$Aplic->usuario_cia && $Aplic->usuario_admin)){
			$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
			$km->Add("inserir","inserir_cia",dica('Nov'.$config['genero_organizacao'].' '.ucfirst($config['organizacao']), 'Criar um nov'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Nov'.$config['genero_organizacao'].' '.ucfirst($config['organizacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=cias&a=editar\");");
			$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_dept'].' '.ucfirst($config['departamento']), 'Criar um nov'.$config['genero_dept'].' '.$config['departamento'].'.').'Nov'.$config['genero_dept'].' '.ucfirst($config['departamento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=depts&a=editar&cia_id=".(int)$cia_id."\");");
			}	
		$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
		if ($podeEditar && ($permiteEditar || $Aplic->usuario_super_admin || $Aplic->usuario_admin)) {
			$km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['organizacao']),'Editar os detalhes d'.($config['genero_organizacao']=='a' ? 'esta' : 'este').' '.$config['organizacao'].'.').'Editar '.ucfirst($config['organizacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=cias&a=editar&cia_id=".(int)$cia_id."\");");
			if (!(isset($config['restrito']) && $config['restrito']) && $podeExcluir && $Aplic->usuario_super_admin && $cia_id!=$Aplic->usuario_cia) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_organizacao']=='a' ? 'esta' : 'este').' '.$config['organizacao'].' do sistema.').'Excluir '.ucfirst($config['organizacao']).dicaF(), "javascript: void(0);' onclick='excluir()");
			if (!(isset($config['restrito']) && $config['restrito']) && $podeExcluir && $Aplic->usuario_super_admin) $km->Add("acao","acao_superior",dica('Tornar Superior','Clique neste botão para que est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].' se torna '.$config['genero_organizacao'].' primeir'.$config['genero_organizacao'].' no organograma. '.ucfirst($config['genero_organizacao']).' que atualmente ocupa '.$config['genero_organizacao'].' primeir'.$config['genero_organizacao'].' posição será imediatamente subordinad'.$config['genero_organizacao'].' a '.($config['genero_organizacao']=='a' ? 'esta' : 'este').'.').'Tornar '.($config['genero_organizacao']=='a' ? 'esta' : 'este').' '.ucfirst($config['organizacao']).' Superior'.dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=cias&a=ver&superior=1&cia_id=".(int)$cia_id."\");");
			}
		$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
		$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.($config['genero_organizacao']=='a' ? 'esta' : 'este').' '.ucfirst($config['organizacao']), 'Visualize os detalhes d'.($config['genero_organizacao']=='a' ? 'esta' : 'este').' '.$config['organizacao'].'.').' Detalhes d'.($config['genero_organizacao']=='a' ? 'esta' : 'este').' '.$config['organizacao'].dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&cia_id=".$cia_id."\");");
		echo $km->Render();
		echo '</td></tr></table>';
		}

if ($podeExcluir) {
	echo '<form name="frmExcluir" method="post">';
	echo '<input type="hidden" name="m" value="cias" />';
	echo '<input name="a" type="hidden" value="vazio" />';
	echo '<input name="u" type="hidden" value="" />';
	echo '<input type="hidden" name="fazerSQL" value="fazer_cia_aed" />';
	echo '<input type="hidden" name="del" value="1" />';
	echo '<input type="hidden" name="cia_id" value="'.$cia_id.'" />';
	echo '</form>';
	}

echo '<table cellpadding=0 cellspacing=1 '.($dialogo ? 'width="750"' : 'width="100%" class="std"').'>';

echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Tod'.$config['genero_organizacao'].' '.$config['organizacao'].' tem um nome exclusivo e obrigatório.').'Nome:'.dicaF() .'</td><td class="realce" width="100%">'.$obj->cia_nome_completo.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Abreviatura d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Tod'.$config['genero_organizacao'].' '.$config['organizacao'].' tem uma abreviatura.').'Abreviatura:'.dicaF() .'</td><td class="realce" width="100%">'.$obj->cia_nome.'</td></tr>';
if ($obj->cia_superior) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']).' Superior', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' a qual esta é subordinada.').ucfirst($config['organizacao']).' superior:'.dicaF() .'</td><td class="realce" width="100%">'.link_cia($obj->cia_superior).'</td></tr>';
if ($obj->cia_responsavel) echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável pel'.$config['genero_organizacao'].' '.$config['organizacao'], 'Tod'.$config['genero_organizacao'].' '.$config['organizacao'].' deve ter um responsável.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Responsável:'.dicaF() .'</td><td class="realce" width="100%">'.link_contato($obj->cia_responsavel, '','','esquerda').'</td></tr>';



$sql->adTabela('cia_usuario');
$sql->adUnir('usuarios','usuarios','usuarios.usuario_id=cia_usuario_usuario');
$sql->esqUnir('contatos', 'contatos', 'contato_id = usuario_contato');
$sql->adCampo('usuarios.usuario_id, '.($config['militar'] < 10 ? 'concatenar_tres(contato_posto, \' \', contato_nomeguerra)' : 'contato_nomeguerra').' AS nome_usuario, contato_funcao, contato_dept');
$sql->adOnde('cia_usuario_cia = '.(int)$cia_id);
$designados = $sql->Lista();
$sql->limpar();

$saida_quem='';
if ($designados && count($designados)) {
		$saida_quem.= '<table cellspacing=0 cellpadding=0 border=0 width="100%">';
		$saida_quem.= '<tr><td>'.link_usuario($designados[0]['usuario_id'], '','','esquerda').($designados[0]['contato_dept']? ' - '.link_secao($designados[0]['contato_dept']) : '');
		$qnt_designados=count($designados);
		if ($qnt_designados > 1) {		
				$lista='';
				for ($i = 1, $i_cmp = $qnt_designados; $i < $i_cmp; $i++) $lista.=link_usuario($designados[$i]['usuario_id'], '','','esquerda').($designados[$i]['contato_dept']? ' - '.link_secao($designados[$i]['contato_dept']) : '').'<br>';		
				$saida_quem.= dica('Outros Designados', 'Clique para visualizar os demais designados.').' <a href="javascript: void(0);" onclick="expandir_colapsar(\'designados\');">(+'.($qnt_designados - 1).')</a>'.dicaF(). '<span style="display: none" id="designados"><br>'.$lista.'</span>';
				}
		$saida_quem.= '</td></tr></table>';
		} 
if ($saida_quem) echo '<tr><td align="right" valign="top" nowrap="nowrap">'.dica('Designados', 'Quais '.strtolower($config['usuarios']).' estão envolvid'.$config['genero_usuario'].'s.').'Designados:'.dicaF().'</td><td class="realce">'.$saida_quem.'</td></tr>';










if ($obj->cia_cnpj) echo '<tr><td align="right" nowrap="nowrap">'.dica('CNPJ', 'CNPJ d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'CNPJ:'.dicaF().'</td><td class="realce" width="100%">'.$obj->cia_cnpj.'</td></tr>';
if ($obj->cia_codigo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'Código d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Código:'.dicaF().'</td><td class="realce" width="100%">'.$obj->cia_codigo.'</td></tr>';
if ($obj->cia_email) echo '<tr><td align="right" nowrap="nowrap">'.dica('E-mail', 'E-mail d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'E-mail:'.dicaF().'</td><td class="realce" width="100%">'.$obj->cia_email.'</td></tr>';
if ($obj->cia_tel1) echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone', 'Telefone d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Telefone:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_tel1.'</td></tr>';
if ($obj->cia_tel2) echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone 2', 'Telefone alternativo d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Telefone 2:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_tel2.'</td></tr>';
if ($obj->cia_fax) echo '<tr><td align="right" nowrap="nowrap">'.dica('Fax', 'Fax d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Fax:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_fax.'</td></tr>';
if ($obj->cia_endereco1) echo '<tr valign="top"><td align="right" nowrap="nowrap">'.dica('Endereço', 'O enderço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Endereço:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Google Maps', 'Clique esta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'<a href="http://maps.google.com/maps?q='.(int)$obj->cia_endereco1.'+'.$obj->cia_endereco2.'+'.$endereco['municipio_nome'].'+'.$obj->cia_estado.'+'.$obj->cia_cep.'+'.$obj->cia_pais.'" target="_blank"><img align="right" border=0 src="'.acharImagem('googlemaps.gif').'" width="60" height="22" alt="Achar no Google Maps" /></a>'.dicaF().$obj->cia_endereco1.(($obj->cia_endereco2) ? '<br />'.$obj->cia_endereco2 : '').($obj->cia_pais ? ' - '.$paises[$obj->cia_pais] : '').(($obj->cia_cep) ? '<br />'.$obj->cia_cep : '').'</td></tr>';
if ($endereco['municipio_nome']) echo '<tr><td align="right">'.dica('Município', 'O município d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Município:'.dicaF().'</td><td  class="realce">'.$endereco['municipio_nome'].'</td></tr>';
if ($endereco['estado_nome']) echo '<tr><td align="right">'.dica('Estado', 'O Estado d'.$config['genero_organizacao'].' '.$config['organizacao'].'.').'Estado:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$endereco['estado_nome'].'</td></tr>';
if ($obj->cia_url) echo '<tr><td align="right" nowrap="nowrap">'.dica('Página Web d'.$config['genero_organizacao'].' '.$config['organizacao'], 'A página na internet d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Página:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.dica('Página Web', 'Clique neste endereço para visualizar a página web dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'<a href="http://'.$obj->cia_url.'" target="Cia">'.$obj->cia_url.'</a>'.dicaF().'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de '.$config['organizacao'], 'Embora não tenha impacto no funcionamento do Sistema, facilita a organização ao separar as OM por tipo.').'Tipo:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$tipos[$obj->cia_tipo].'</td></tr>';
//echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' pode ter três níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver e o responsável junto com os integrantes editar.</li><li><b>Protegido II</b> - Todos podem ver e o responsável editar.</li><li><b>Privado</b> - Todos d'.$config['genero_organizacao'].' '.$config['organizacao'].' podem ver e o responsável pel'.$config['genero_organizacao'].' mesm'.$config['genero_organizacao'].' ver e editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td width="100%" class="realce">'.(isset($acesso[$obj->cia_acesso]) ? $acesso[$obj->cia_acesso] : '').'</td></tr>';
if ($obj->cia_ug) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código Principal da Unidade Gestora', 'Para os órgãos do Governo Federal é um código de 6 algarismos.').'UASG Principal'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_ug.'</td></tr>';
if ($obj->cia_ug2) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código Secundário da Unidade Gestora', 'Para os órgãos do Governo Federal é um código de 6 algarismos.').'UASG Secundário'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_ug2.'</td></tr>';
if ($obj->cia_nup) echo '<tr><td align="right" nowrap="nowrap">'.dica('Identificador d'.$config['genero_organizacao'].' '.$config['organizacao'].' para NUP', 'Caso utilize o sistema único e processos faz-se necessário informar o número identificador d'.$config['genero_organizacao'].' '.$config['organizacao'].' de 5 algarismos.').'Identificador de NUP'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_nup.'</td></tr>';
if ($obj->cia_prefixo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Prefixo','O prefixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Prefixo'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_prefixo.'</td></tr>';
if ($obj->cia_sufixo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Sufixo','O sufixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Sufixo'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_sufixo.'</td></tr>';
if ($obj->cia_descricao) echo '<tr><td align="right" valign="middle">'.dica('Descrição d'.$config['genero_organizacao'].' '.$config['organizacao'], 'A descrição d'.$config['genero_organizacao'].' '.$config['organizacao'].'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_descricao.'</td></tr>';


if ($obj->cia_cabacalho)  echo '<tr><td align="right">'.dica('Cabeçalho dos Documentos d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Caso envie documentos criados dentro do '.$config['gpweb'].', este campo formata o cabeçalho dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'Cabeçalho:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->cia_cabacalho.'</td></tr>';
if ($obj->cia_logo) echo '<tr><td align="right" valign="middle">'.dica('Logotipo d'.$config['genero_organizacao'].' '.$config['organizacao'], 'Logotipo dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'Logotipo:'.dicaF().'</td><td align="left"><img src="'.($config['url_arquivo'] ? $config['url_arquivo'] : BASE_URL).'/arquivos/organizacoes/'.$obj->cia_logo.'" alt="" border=0 /></td></tr>';


$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', 'Pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver, porem apenas o responsável e os designados podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os designados podem ver e editar</li><li><b>Privado</b> - Somente o responsável e os designados podem ver, e o responsável editar.</li></ul>').'Nível de acesso:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.(isset($acesso[$obj->cia_acesso]) ? $acesso[$obj->cia_acesso] : '').'</td></tr>';



echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativ'.$config['genero_organizacao'], ucfirst($config['genero_organizacao']).' '.$config['organizacao'].' se encontra ativ'.$config['genero_organizacao'].'.').'Ativ'.$config['genero_organizacao'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->cia_ativo ? 'Sim' : 'Não').'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $obj->cia_id, 'ver');
$campos_customizados->imprimirHTML();
echo '</table>';

if (!$dialogo){
	$caixaTab = new CTabBox('m=cias&a=ver&cia_id='.(int)$cia_id, '', $tab);
	$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_depts', ucfirst($config['departamentos']),null,null,ucfirst($config['departamentos']),'Visualizar '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_usuarios', 'Integrantes',null,null,'Integrantes','Visualizar os integrantes dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_contatos', 'Contatos',null,null,'Contatos','Visualizar os contatos d'.$config['genero_organizacao'].' '.$config['organizacao'].'.');
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos relacionados à '.$config['organizacao'].' ou de '.strtolower($config['departamentos']).' internas.');
	//$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos relacionados à '.$config['organizacao'].' ou de '.strtolower($config['departamentos']).' internas.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/cias/ver_cias', 'Subordinad'.$config['genero_organizacao'].'s',null,null,'Subordinad'.$config['genero_organizacao'].'s','Visualizar '.$config['genero_organizacao'].'s '.$config['organizacoes'].' subordinad'.$config['genero_organizacao'].'s.');	
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa();
	}
?>
<script language="javascript">
function excluir() {
	if (confirm( "Tem certeza que desejas excluir esta <?php echo $config['organizacao']?>? Todos os dados vinculados como <?php echo $config['usuarios']?>, <?php echo $config['projetos']?>, etc. serão perdidos." )) document.frmExcluir.submit();
	}
	
function expandir_colapsar(campo){
	if (!document.getElementById(campo).style.display) document.getElementById(campo).style.display='none';
	else document.getElementById(campo).style.display='';
	}	
</script>
