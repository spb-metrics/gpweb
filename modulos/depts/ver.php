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

if (isset($_REQUEST['tab'])) $Aplic->setEstado('DeptVerTab', getParam($_REQUEST, 'tab', null));
$tab = $Aplic->getEstado('DeptVerTab') !== null ? $Aplic->getEstado('DeptVerTab') : 0;

$tipos = getSisValor('TipoDepartamento');
$acesso = getSisValor('NivelAcesso','','','sisvalor_id');
$dept_id = intval(getParam($_REQUEST, 'dept_id', 0));
require_once (BASE_DIR.'/modulos/depts/depts.class.php');
$obj= new CDept();
$obj->load($dept_id);

$paises = getPais('Paises');

	
	
if (!permiteAcessarDept($obj->dept_acesso, $dept_id)) $Aplic->redirecionar('m=publico&a=acesso_negado');
$permiteEditar=permiteEditarDept($obj->dept_acesso, $dept_id);
$cia_id = $obj->dept_cia;


if (!$dialogo && !$Aplic->profissional) {
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_dept'].' '.$config['departamento'], 'depts.png', $m, $m.'.'.$a);
	if ($podeAdicionar) {
		$botoesTitulo->adicionaCelula();
		$botoesTitulo->adicionaBotaoCelula('', 'url_passar(0, \'m=depts&a=editar&cia_id='.(int)$cia_id.'\');', 'nov'.$config['genero_dept'].' '.$config['dept'], '', 'Nov'.$config['genero_dept'].' '.$config['departamento'], 'Criar um'.$config['genero_dept'].' nov'.$config['genero_dept'].' '.$config['departamento'].' dentro d'.$config['genero_organizacao'].' '.$config['organizacao'].' atual.');
		}
	$botoesTitulo->adicionaBotao('m=depts', 'lista de '.strtolower($config['departamentos']),'','Lista de '.$config['departamentos'],'Visualizar a lista de tod'.$config['genero_dept'].'s '.strtolower($config['departamentos']).' cadastradas dentro dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.');
	$botoesTitulo->adicionaBotao('m=cias', 'lista de '.$config['organizacao'],'','Lista de '.$config['organizacao'],'Visualizar a lista de todas as '.$config['organizacao'].' cadastradas no Sistema.');
	$botoesTitulo->adicionaBotao('m=cias&a=ver&cia_id='.(int)$cia_id, 'ver est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'],'','Ver est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'],'Visualizar os detalhes relativos '.$config['genero_organizacao'].' '.$config['organizacao'].' a qual '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).' pertence.');
	if ($podeEditar && ($permiteEditar || $Aplic->usuario_super_admin || $Aplic->usuario_admin)) {
		$botoesTitulo->adicionaBotao('m=depts&a=editar&dept_id='.$dept_id, 'editar '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']),'','Editar '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.$config['departamento'],'Editar os detalhes relativos a '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).'.');
		if ($podeExcluir)	$botoesTitulo->adicionaBotaoExcluir('excluir '.strtolower($config['dept']), $podeExcluir, (isset($msg) ? $msg : ''), 'Excluir '.$config['departamento'],'Excluir '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).'.');
		}
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();	
	}
	
	
if (!$dialogo && $Aplic->profissional){	
	$botoesTitulo = new CBlocoTitulo('Detalhes d'.$config['genero_dept'].' '.ucfirst($config['departamento']).'', 'depts.png', $m, $m.'.'.$a);
	$botoesTitulo->mostrar();
	echo estiloTopoCaixa();
	echo '<table align="center" cellspacing=0 cellpadding=0 width="100%">'; 
	echo '<tr><td colspan=2 style="background-color: #e6e6e6" width="100%">';
	require_once BASE_DIR.'/lib/coolcss/CoolControls/CoolMenu/coolmenu.php';
	$km = new CoolMenu("km");
	$km->scriptFolder ='lib/coolcss/CoolControls/CoolMenu';
	$km->styleFolder="default";
	$km->Add("root","ver",dica('Ver','Menu de opções de visualização').'Ver'.dicaF(), "javascript: void(0);");
	$km->Add("ver","ver_lista_depts",dica('Lista de '.$config['departamentos'],'Visualizar a lista de tod'.$config['genero_dept'].'s '.strtolower($config['departamentos']).' cadastradas dentro dest'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.$config['organizacao'].'.').'Lista de '.ucfirst($config['departamentos']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=depts\");");
	$km->Add("ver","ver_cia",dica('Ver est'.($config['genero_organizacao']=='o' ? 'e' : 'a').' '.ucfirst($config['organizacao']),'Visualizar os detalhes relativos '.$config['genero_organizacao'].' '.$config['organizacao'].' a qual '.($config['genero_dept']=='o' ? 'este' : 'esta').' '.strtolower($config['departamento']).' pertence.').ucfirst($config['organizacao']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=cias&a=ver&cia_id=".(int)$cia_id."\");");
	if ($podeEditar && $permiteEditar && ($Aplic->usuario_super_admin || $Aplic->usuario_admin)){
		$km->Add("root","inserir",dica('Inserir','Menu de opções').'Inserir'.dicaF(), "javascript: void(0);'");
		$km->Add("inserir","inserir_tarefa",dica('Nov'.$config['genero_dept'].' '.ucfirst($config['departamento']), 'Criar um nov'.$config['genero_dept'].' '.$config['departamento'].'.').'Nov'.$config['genero_dept'].' '.ucfirst($config['departamento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=depts&a=editar&cia_id=".(int)$cia_id."\");");
		}	
		
	$km->Add("root","acao",dica('Ação','Menu de ações.').'Ação'.dicaF(), "javascript: void(0);'");
	if ($podeEditar && $permiteEditar) {
		$km->Add("acao","acao_editar",dica('Editar '.ucfirst($config['departamento']),'Editar os detalhes d'.($config['genero_dept']=='a' ? 'esta' : 'este').' '.$config['departamento'].'.').'Editar '.ucfirst($config['departamento']).dicaF(), "javascript: void(0);' onclick='url_passar(0, \"m=depts&a=editar&dept_id=".$dept_id."\");");
		if ($podeExcluir && ($Aplic->usuario_super_admin || $Aplic->usuario_admin)) $km->Add("acao","acao_excluir",dica('Excluir','Excluir '.($config['genero_dept']=='a' ? 'esta' : 'este').' '.$config['departamento'].' do sistema.').'Excluir '.ucfirst($config['departamento']).dicaF(), "javascript: void(0);' onclick='excluir()");
		}
	$km->Add("acao","acao_imprimir",dica('Imprimir', 'Clique neste ícone '.imagem('imprimir_p.png').' para visualizar as opções de relatórios.').imagem('imprimir_p.png').' Imprimir'.dicaF(), "javascript: void(0);'");	
	$km->Add("acao_imprimir","acao_imprimir1",dica('Detalhes d'.($config['genero_dept']=='a' ? 'esta' : 'este').' '.$config['departamento'], 'Visualize os detalhes d'.($config['genero_dept']=='a' ? 'esta' : 'este').' '.$config['departamento'].'.').' Detalhes d'.($config['genero_dept']=='a' ? 'esta' : 'este').' '.$config['departamento'].dicaF(), "javascript: void(0);' onclick='url_passar(1, \"m=".$m."&a=".$a."&dialogo=1&dept_id=".$dept_id."\");");
	echo $km->Render();
	echo '</td></tr></table>';
	}
	
	
	
echo '<form name="frmExcluir" method="post"><input type="hidden" name="m" value="depts" /><input type="hidden" name="fazerSQL" value="fazer_dept_ead" /><input type="hidden" name="del" value="1" /><input type="hidden" name="dept_id" value="'.$dept_id.'" /></form>';

echo '<table cellpadding=0 cellspacing=1 '.($dialogo ? 'width="750"' : 'width="100%" class="std"').'>';

echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['organizacao']), 'Tod'.$config['genero_dept'].' '.strtolower($config['departamento']).' deve pertencer à um'.$config['genero_organizacao'].' '.$config['organizacao']).ucfirst($config['organizacao']).':'.dicaF().'</td><td class="realce" width="100%">'.link_cia($obj->dept_cia).'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nome d'.$config['genero_dept'].' '.$config['departamento'], 'Tod'.$config['genero_dept'].' '.strtolower($config['departamento']).' deve ter um nome exclusivo e obrigatório.').ucfirst($config['dept']).':'.dicaF().'</td><td class="realce" width="100%">'.$obj->dept_nome.'</td></tr>';
if ($obj->dept_superior) echo '<tr><td align="right" nowrap="nowrap">'.dica(ucfirst($config['departamento']).' Superior', ucfirst($config['genero_dept']).' '.strtolower($config['departamento']).' superior a est'.($config['genero_dept']=='a' ? 'a' : 'e').'.').ucfirst($config['departamento']).' superior:'.dicaF().'</td><td align="left" class="realce" width="100%">'.nome_dept($obj->dept_superior).'</td></td></tr>';
if ($obj->dept_responsavel) echo '<tr><td align="right" nowrap="nowrap">'.dica('Responsável', 'Tod'.$config['genero_dept'].' '.strtolower($config['departamento']).' deve ter um responsável.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Responsável:'.dicaF().'</td><td class="realce" width="100%">'.link_contato($obj->dept_responsavel,'','','esquerda').'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Tipo de '.$config['departamento'], 'Embora não tenha impacto no funcionamento do Sistema, facilita a organização ao separar '.$config['genero_dept'].'s '.strtolower($config['departamentos']).' por tipo.').'Tipo:'.dicaF().'</td><td class="realce" width="100%">'.$tipos[$obj->dept_tipo].'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Nível de Acesso', $config['genero_dept'].' '.strtolower($config['departamento']).' pode ter cinco níveis de acesso:<ul><li><b>Público</b> - Todos podem ver e editar.</li><li><b>Protegido</b> - Todos podem ver e o responsável junto com os integrantes d'.$config['genero_dept'].' '.strtolower($config['departamento']).' podem editar.</li><li><b>Protegido II</b> - Todos podem ver, porem apenas o responsável pode editar.</li><li><b>Participante</b> - Somente o responsável e os integrantes d'.$config['genero_dept'].' '.strtolower($config['departamento']).' podem ver e editar</li><li><b>Privado</b> - Somente os integrantes d'.$config['genero_dept'].' '.strtolower($config['departamento']).' podem ver e o responsável pel'.$config['genero_dept'].' mesm'.$config['genero_dept'].' ver e editar.</li></ul>').'Nível de Acesso'.dicaF().'</td><td class="realce" width="100%">'.(isset($acesso[$obj->dept_acesso]) ? $acesso[$obj->dept_acesso] : '').'</td></tr>';
if ($obj->dept_codigo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Código', 'Código d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.').'Código:'.dicaF().'</td><td class="realce" width="100%">'.$obj->dept_codigo.'</td></tr>';
if ($obj->dept_email) echo '<tr><td align="right" nowrap="nowrap">'.dica('e-mail', 'e-mail d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'e-mail:'.dicaF().'</td><td class="realce" width="100%">'.$obj->dept_email.'</td></tr>';
if ($obj->dept_tel) echo '<tr><td align="right" nowrap="nowrap">'.dica('Telefone', 'Telefone d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Telefone:'.dicaF().'</td><td class="realce" width="100%">'.$obj->dept_tel.'</td></tr>';
if ($obj->dept_fax) echo '<tr><td align="right" nowrap="nowrap">'.dica('Fax', 'Fax d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Fax:'.dicaF().'</td><td class="realce" width="100%">'.$obj->dept_fax.'</td></tr>';
if ($obj->dept_endereco1) echo '<tr valign="top"><td align="right" nowrap="nowrap">'.dica('Endereço', 'O enderço d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização e na eventual necessidade de entrar em contato.').'Endereço:'.dicaF().'</td><td class="realce">'.dica('Google Maps', 'Clique esta imagem para visualizar no Google Maps, aberto em uma nova janela, o endereço d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.').'<a href="http://maps.google.com/maps?q='.$obj->dept_endereco1.'+'.$obj->dept_endereco2.'+'.$obj->dept_cidade.'+'.$obj->dept_estado.'+'.$obj->dept_cep.'+'.$obj->dept_pais.'" target="_blank"><img align="right" border=0 src="'.acharImagem('googlemaps.gif').'" width="60" height="22" alt="Achar no Google Maps" /></a>'.dicaF().$obj->dept_endereco1.(($obj->dept_endereco2) ? '<br />'.$obj->dept_endereco2 : '').'<br />'.$obj->dept_cidade.'&nbsp;&nbsp;'.$obj->dept_estado.'&nbsp;&nbsp;'.$obj->dept_cep.(($obj->dept_pais) ? '<br />'.$paises[$obj->dept_pais] : '').'</td></tr>';
if ($obj->dept_nup) echo '<tr><td align="right" nowrap="nowrap">'.dica('Identificador d'.$config['genero_dept'].' '.$config['departamento'].' para NUP', 'Caso utilize o sistema único e processos faz-se necessário informar o número identificador d'.$config['genero_dept'].' '.$config['departamento'].' de 5 algarismos.').'Identificador de NUP:'.dicaF().'</td><td width="100%" class="realce">'.$obj->dept_nup.'</td></tr>';
if ($obj->dept_qnt_nr) echo '<tr><td align="right" nowrap="nowrap">'.dica('Quantos Protocolos d'.$config['genero_dept'].' '.$config['departamento'].' neste ano já foram inserido', 'Caso utilize utilize um sistema de protocolo, faz-se necessário informar quantos protocolos já foram emitidos, neste ano, para que aqueles emitidos pelo '.$config['gpweb'].' sigam a sequuência numérica crescente.').'Quantos protocolos:'.dicaF().'</td><td width="100%" class="realce" >'.$obj->dept_qnt_nr.'</td></tr>';
if ($obj->dept_prefixo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Prefixo','Preencha, caso exista, o prefixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Prefixo:'.dicaF().'</td><td class="realce"  width="100%" colspan="2">'.$obj->dept_prefixo.'</td></tr>';
if ($obj->dept_sufixo) echo '<tr><td align="right" nowrap="nowrap">'.dica('Sufixo','Preencha, caso exista, o sufixo à numeração sequencial crescente, nos protocolos diversos de NUP.').'Sufixo:'.dicaF().'</td><td class="realce" width="100%">'.$obj->dept_sufixo.'</td></tr>';
if ($obj->dept_descricao) echo '<tr><td align="right" valign="middle">'.dica('Descrição', 'A descrição d'.$config['genero_dept'].' '.strtolower($config['departamento']).'.<br><br>Embora não tenha impacto no funcionamento do Sistema, facilita a organização.').'Descrição:'.dicaF().'</td><td class="realce" style="text-align: justify;">'.$obj->dept_descricao.'</td></tr>';
echo '<tr><td align="right" nowrap="nowrap">'.dica('Ativ'.$config['genero_dept'], ucfirst($config['genero_dept']).' '.$config['dept'].' se encontra ativ'.$config['genero_dept'].'.').'Ativ'.$config['genero_dept'].':'.dicaF().'</td><td class="realce" style="text-align: justify;">'.($obj->dept_ativo ? 'Sim' : 'Não').'</td></tr>';

require_once ($Aplic->getClasseSistema('CampoCustomizados'));
$campos_customizados = new CampoCustomizados($m, $dept_id, 'ver');
$campos_customizados->imprimirHTML();
echo '</table>';


if (!$dialogo){	
	$caixaTab = new CTabBox('m=depts&a='.$a.'&dept_id='.$dept_id, '', $tab);
	$caixaTab->adicionar(BASE_DIR.'/modulos/depts/ver_integrantes', 'Integrantes',null,null,'Integrantes','Visualizar os integrantes '.($config['genero_dept']=='o' ? 'deste' : 'desta').' '.strtolower($config['departamento']).'.');
	$caixaTab->adicionar(BASE_DIR.'/modulos/depts/ver_contatos', 'Contatos',null,null,'Contatos','Visualizar os contatos '.($config['genero_dept']=='o' ? 'deste' : 'desta').' '.strtolower($config['departamento']).'.');
//	$caixaTab->adicionar(BASE_DIR.'/modulos/depts/tarefas', ucfirst($config['tarefas']),null,null,ucfirst($config['tarefas']),'Visualizar '.$config['genero_tarefa'].'s '.$config['tarefas'].' '.($config['genero_dept']=='o' ? 'deste' : 'desta').' '.strtolower($config['departamento']).'.');
//	$caixaTab->adicionar(BASE_DIR.'/modulos/depts/ver_eventos', 'Eventos',null,null,'Eventos','Visualizar os eventos d'.$config['genero_projeto'].'s '.$config['projetos'].' e '.$config['tarefas'].' relacionados a esta '.strtolower($config['departamentos']).'.');
//	$caixaTab->adicionar(BASE_DIR.'/modulos/depts/ver_arquivos', 'Arquivos',null,null,'Arquivos','Visualizar os arquivos d'.$config['genero_projeto'].'s '.$config['projetos'].' e '.$config['tarefas'].' relacionados a esta '.strtolower($config['departamentos']).'.');
	$caixaTab->mostrar('','','','',true);
	echo estiloFundoCaixa();	
	}

?>
<script language="javascript">
	function excluir() {
		if (confirm('Tem a certeza de que quer excluir <?php echo ($config["genero_dept"]=="o" ? "este" : "esta")." ".strtolower($config["departamento"])?>?')) {
			document.frmExcluir.submit();
			}
		}
		
function selecionar_caixa(box,id,linha_id,nome_formulario){

	}	
</script>
